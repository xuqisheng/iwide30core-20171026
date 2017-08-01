<?php
class Keyword_reply_model extends CI_Model{
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 根据关键字取关键字返回内容
	 * @param String $keyword 关键字
	 * @param String $inter_id 公众号内部ID
	 */
	function get_keyword_reply_text($keyword,$inter_id){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where(array('keyword' => $keyword,'inter_id' => $inter_id));
		$db_read->limit(1);
		$query = $db_read->get('keyword');
		if($query->num_rows() > 0){
			$query = $query->row_array();
			$result='';
			$db_read->select('*');
			$db_read->from('keyword k');
			//0：文本,1：图文,2：多图文,3：语音
			if($query['reply_type'] == 0 || $query['reply_type'] == 1){
				$db_read->join('replyinfo r', 'k.key_id = r.keyword_id');
				$db_read->where(array('k.keyword' => $keyword,'k.inter_id' => $inter_id));
				$db_read->limit(1);
				$query = $db_read->get()->row_array();
				return $query;
			}else if($query['reply_type'] == 2){
				$db_read->join('replyinfo r', 'k.key_id = r.keyword_id');
			}
		}else{
			return null;
		}
	}
	
	/**
	 * 根据关键词读取数据
	 * @param string $keyword
	 * @param string $inter_id
	 * @return obj|NULL
	 */
	function get_keyword_reply_text_all($keyword,$inter_id){
		$db_read = $this->load->database('iwide_r1',true);
		$sql = 'SELECT rn.*,ks.match_type,krl.sort FROM iwide_keywords ks LEFT JOIN iwide_keyword_reply_rel krl ON ks.inter_id=krl.inter_id AND ks.id=krl.keyword_id LEFT JOIN iwide_reply_news rn ON krl.inter_id=rn.inter_id AND krl.news_id=rn.id WHERE keyword=? AND ks.inter_id=?';
		$rs = $db_read->query($sql,array($keyword,$inter_id));
		if($rs->num_rows() > 0){
			return $rs;
		}else{
			return null;
		}
	}
	
	/**
	 * 根据关键字id取关键字返回内容
	 * @param String $keyword_id 关键字ID
	 * @param String $inter_id 公众号内部ID
	 */
	function get_keyword_reply_text_by_id($keyword_id,$inter_id){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->limit(1);
		$query = $db_read->get_where('keyword',array('key_id' => $keyword_id,'inter_id' => $inter_id));
		if($query->num_rows() > 0){
			$result='';
			$db_read->select('*');
			$db_read->from('keyword k');
			$query = $query->row_array();
			//0：文本,1：图文,2：多图文,3：语音
			if($query['reply_type'] == 0 || $query['reply_type'] == 1){
				$db_read->join('replyinfo r', 'k.key_id = r.keyword_id');
				$db_read->where(array('k.key_id' => $keyword_id,'k.inter_id' => $inter_id));
				$db_read->limit(1);
				return $this->db->get()->row_array();
			}else if($query['reply_type'] == 2){
				$db_read->join('replyinfo r', 'k.key_id = r.keyword_id');
			}
		}else{
			return null;
		}
	}
	
	function get_keyword_reply_all_by_id($keyword_id,$inter_id){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->limit(1);
		$query = $db_read->get_where('keyword',array('key_id' => $keyword_id,'inter_id' => $inter_id));
		if($query->num_rows() > 0){
			$result='';

			$db_read->select('*');
			$db_read->from('keyword k');
			$query = $query->row_array();
			//0：文本,1：图文,2：多图文,3：语音
			if($query['reply_type'] == 0 || $query['reply_type'] == 1){
				$db_read->join('replyinfo r', 'k.key_id = r.keyword_id');
				$db_read->where(array('k.key_id' => $keyword_id,'k.inter_id' => $inter_id));
				return $db_read->get();
			}else if($query['reply_type'] == 2){
				$db_read->join('replyinfo r', 'k.key_id = r.keyword_id');
			}
		}else return null;
	}
	
	/**
	 * 取关键字文本图文回复列表
	 * @param String $inter_id
	 * @param int $reply_type 0:文本,1:图文
	 * @param int $num
	 * @param int $offset
	 */
	function get_keyword_reply_list($reply_type,$inter_id,$num = null,$offset = null){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->select('*');
		$db_read->from('keyword k');
		$db_read->join('replyinfo r', 'k.key_id = r.keyword_id');
		$db_read->where(array('k.inter_id' => $inter_id,'k.reply_type' => $reply_type, 'r.inter_id' => $inter_id));
		if($num == null){
			return $db_read->get();
		}else{
			return $db_read->get()->limit($num,$offset);
		}
	}
	
	/**
	 * 取关键字图文回复列表
	 * @param String $inter_id
	 * @param int $num
	 * @param int $offset
	 */
	function get_keyword_reply_multi_list($inter_id,$num = null,$offset = null){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->select('*');
		$db_read->from('keyword k');
		$db_read->join('replyinfo r', 'k.key_id = r.keyword_id');
		$db_read->where(array('k.inter_id' => $inter_id, 'r.inter_id' => $inter_id));
	
		if($num == null){
			//$this->db->query('SELECT * FROM iwide_keyword k JOIN replyinfo r ON k.key_id=r.keyword_id WHERE k.inter_id=')
			return $db_read->get();
		}else{
			return $db_read->get()->limit($num,$offset);
		}
	}
	
	/**
	 * 创建关键字图文/文本回复
	 * @param Array $array
	 */
	function create_keyword_reply_text($array)
	{
		if(isset($array['keyword']))
			$keyword['keyword'] = $array['keyword'];
			if(isset($array['reply_type']))
				$keyword['reply_type'] = $array['reply_type'];
				if(isset($array['match_type']))
					$keyword['match_type'] = $array['match_type'];
					if(isset($array['inter_id']))
						$keyword['inter_id'] = $array['inter_id'];
						$keyword['reply_type'] = $array['reply_type'];
						$keyword['create_time'] = date('Y-m-d h:i:s');
	
						if(isset($array['title']))
							$replyinfo['title'] = $array['title'];
							if(isset($array['description']))
								$replyinfo['description'] = $array['description'];
								if(isset($array['cover_img']))
									$replyinfo['cover_img'] = $array['cover_img'];
									if(isset($array['url']))
										$replyinfo['url'] = $array['url'];
										if(isset($array['inter_id']))
											$replyinfo['inter_id'] = $array['inter_id'];
											$this->db->trans_begin();
											$this->db->insert('keyword',$keyword);
											//$this->db->insert_id();
											$replyinfo['keyword_id'] = $this->db->insert_id();
											$this->db->insert('replyinfo',$replyinfo);
											$this->db->trans_complete();
											if ($this->db->trans_status() === FALSE){
												$this->db->trans_rollback();
												return FALSE;
											}
											else{
												$this->db->trans_commit();
												return TRUE;
											}
	}
	
	
	function update_keyword_reply_text($data){
		$this->db->trans_begin();
		$this->db->where(array('inter_id'=>$data['inter_id'],'key_id'=>$data['id']));
		$this->db->update('keyword',array('keyword'=>$data['keyword'],'match_type'=>$data['match_type'],'edit_time'=>date('Y-m-d h:i:s')));
	
		$this->db->where(array('inter_id'=>$data['inter_id'],'keyword_id'=>$data['id']));
		$arr['description'] = $data['description'];
		if($data['reply_type'] == 1){
			$arr['title']       = $data['title'];
			$arr['description'] = $data['description'];
			$arr['cover_img']   = $data['cover_img'];
			$arr['url']         = $data['url'];
		}
		$this->db->update('replyinfo',$arr);
	
		$this->db->trans_complete();
		if ($this->db->trans_status() === false){
			$this->db->trans_rollback();
			return false;
		}
		else{
			$this->db->trans_commit();
			return true;
		}
	}
	
	function delete_keyword_reply_text($inter_id,$keyword_id){
		$this->db->trans_begin();
		$this->db->delete('keyword',array('inter_id'=>$inter_id,'key_id'=>$keyword_id));
		$this->db->delete('replyinfo',array('inter_id'=>$inter_id,'keyword_id'=>$keyword_id));
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		}
		else{
			$this->db->trans_commit();
			return TRUE;
		}
	}
	
	/**
	 * 保存自动回复
	 */
	function save_sub_auto_reply($inter_id,$data){
		$this->db->where(array('inter_id'=>$inter_id,'keyword'=>'关注自动回复'));
		$query = $this->db->get('keyword');
		$this->db->trans_begin();
		if($query->num_rows() > 0){
			$query = $query->row_array();
			$this->db->where(array('inter_id'=>$inter_id,'keyword'=>'关注自动回复'));
			$this->db->update('keyword',array('keyword'=>'关注自动回复','reply_type'=>$data['reply_type'],'match_type'=>1,'edit_time'=>date('Y-m-d h:i:s')));
			$this->db->where(array('inter_id'=>$inter_id,'keyword_id'=>$query['key_id']));
			$arr = array();
			if($data['reply_type'] == 1){
				$arr['title']     = $data['title'];
				$arr['cover_img'] = $data['cover_img'];
				$arr['url']       = $data['url'];
			}
			$arr['description']   = $data['description'];
			$this->db->update('replyinfo',$arr);
		}else{
			$this->db->insert('keyword',array('inter_id'=>$inter_id,'keyword'=>'关注自动回复','reply_type'=>$data['reply_type'],'match_type'=>1,'create_time'=>date('Y-m-d h:i:s')));
			$insert_id = $this->db->insert_id();
			$arr = array();
			$arr['description']   = $data['description'];
			if($data['reply_type'] == 1){
				$arr['title']     = $data['title'];
				$arr['cover_img'] = $data['cover_img'];
				$arr['url']       = $data['url'];
			}
			$arr['keyword_id']    = $insert_id;
			$arr['inter_id']      = $inter_id;
			$this->db->insert('replyinfo',$arr);
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === false){
			$this->db->trans_rollback();
			return false;
		}
		else{
			$this->db->trans_commit();
			return true;
		}
	}
}

