<?php
/**
 * 微信自定义菜单
 */
class Menu_model extends CI_Model {
	function get_menus($inter_id){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where(array('inter_id'=>$inter_id,'is_show'=>0));
		$db_read->order_by('parent_id');
		$rs = $db_read->get('menu')->result();
		$nres = array();
		foreach ($rs as $item){
			if($item->parent_id == 0){
				$nres[$item->id] = array('id'          => $item->id,
								         'title'       => $item->title,
								         'keyword'     => $item->keyword,
								         'url'         => $item->url,
								         'is_show'     => $item->is_show,
								         'sort'        => $item->sort,
								         'extend_menu' => $item->extend_menu,
								         'menu_type'   => $item->menu_type,
									     'sub_items'   => array()
				);
			}else{
				$nres[$item->parent_id]['sub_items'][$item->id] = array('id'         => $item->id,
																	   'parent_id'   => $item->parent_id,
																	   'title'       => $item->title,
																	   'keyword'     => $item->keyword,
																	   'url'         => $item->url,
																	   'is_show'     => $item->is_show,
																	   'sort'        => $item->sort,
																	   'extend_menu' => $item->extend_menu,
																	   'menu_type'   => $item->menu_type
				);
			}
		}
		foreach ($nres as $k => $item){
			usort($item['sub_items'], array('menu_model',"arrCmp"));
			$nres[$k] = $item;
		}
		usort($nres, array('menu_model',"arrCmp"));
		return $nres;
	}
	function save_menu_item($inter_id){
		$param['parent_id'] = $this->input->post('parent',true);
		$param['title']     = $this->input->post('name',true);
		$menu_type          = $this->input->post('menu_type',true);
// 		var_dump($this->input->post('menu_content',true));exit();
		if($menu_type == 0){
			$param['keyword'] = $this->input->post('menu_content');
		}else if($menu_type == 1){
			$param['url'] = $this->input->post('menu_content');
		}else if($menu_type == 2){
			$param['extend_menu'] = $this->input->post('sys_val',true);
		}
// 		$param['sort']      = $this->input->post('sort',true);
		$param['sort']      = 0;
		$param['menu_type'] = $menu_type;
		if($this->input->post('id',true)){
			$this->db->where(array('id'=>$this->input->post('id',true),'inter_id'=>$inter_id));
			if($this->db->update('menu',$param) > 0){
				return json_encode(array('errmsg'=>'ok'));
			}else{
				return json_encode(array('errmsg'=>'菜单更新失败'));
			}
		}else{
			$param['inter_id'] = $inter_id;
            //添加限制：一级菜单最多三个 2级菜单最多5个 stgc 20161110
            if($param['parent_id'] == 0){//父级菜单
               $parent = $this->db->where(array('inter_id'=>$inter_id,'is_show'=>0,'parent_id'=>0))->get('menu')->result_array();
                if(is_array($parent) && count($parent) >= 3){
                    return json_encode(array('errmsg'=>'添加失败：一级菜单最多显示三个'));
                }
            }else{//二级菜单
               $son_menu = $this->db->where(array('inter_id'=>$inter_id,'is_show'=>0,'parent_id'=>$param['parent_id']))->get('menu')->result_array();
                if(is_array($son_menu) && count($son_menu) >= 5){
                    return json_encode(array('errmsg'=>'添加失败：二级菜单最多显示五个'));
                }
            }
			if($this->db->insert('menu',$param) > 0){
				MYLOG::w('Save Menu Item | Admin Id:'.$this->session->get_admin_id().' | Admin Name:'.$this->session->get_admin_username().' | sort:'.json_encode($param).' | Result:SUCCESS','weixin_api');
				return json_encode(array('errmsg'=>'ok'));
			}else{
				MYLOG::w('Save Menu Item | Admin Id:'.$this->session->get_admin_id().' | Admin Name:'.$this->session->get_admin_username().' | sort:'.json_encode($param).' | Result:FAILD','weixin_api');
				return json_encode(array('errmsg'=>'菜单写入失败'));
			}
		}
	}
	function save_menu($inter_id){
		$sort_str = $this->input->post('sort');
		$sort_arr = explode(',', $sort_str);
		$this->db->trans_begin();
		foreach ($sort_arr as $item) {
			$keys = explode(':', $item);
			$this->db->where(array('inter_id' => $inter_id, 'id'=>$keys[0]));
			$this->db->update('menu',array('sort'=>$keys[1]));
		}
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			MYLOG::w('Save Menu Sort | Admin Id:'.$this->session->get_admin_id().' | Admin Name:'.$this->session->get_admin_username().' | sort:'.json_encode($this->input->post('sort')).' | Result:SUCCESS','weixin_api');
			return json_encode(array('errmsg'=>'数据保存失败'));
		}else{
			$this->db->trans_commit();
			MYLOG::w('Save Menu Sort | Admin Id:'.$this->session->get_admin_id().' | Admin Name:'.$this->session->get_admin_username().' | sort:'.json_encode($this->input->post('sort')).' | Result:FAILD','weixin_api');
			return json_encode(array('errmsg'=>'ok'));
		}
	}
	function delete_menu_item($inter_id,$id){
		$sql = 'UPDATE iwide_menu SET is_show=2 WHERE inter_id=? AND (id=? OR parent_id=?)';
		if($this->db->query($sql,array($inter_id,$id,$id))){
			return json_encode(array('errmsg'=>'ok'));
		}else{
			return json_encode(array('errmsg'=>'菜单删除失败'));
		}
	}
	/**
	 * 生成菜单
	 *
	 * @param String $app_id
	 * @param String $app_secret
	 * @return boolean
	 */
	function generate_menu($app_id, $app_secret,$inter_id) {
		$this->load->helper ( 'common' );
		$db_read = $this->load->database('iwide_r1',true);
		// $url_get = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $app_id . '&secret=' . $app_secret;
		// // $url_get = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $app_id . '&secret=' . $app_secret;
		// $json = json_decode ( doCurlGetRequest ( $url_get ), TRUE );
		// if (! isset ( $json ['errmsg'] )) {
		$this->load->model ( 'wx/access_token_model' );
		$access_token = $this->access_token_model->get_access_token ( $inter_id );
		$data = '{"button":[';
		$db_read->limit ( 3 );
		$db_read->order_by ( 'sort asc,id asc' );
		$menus = $db_read->get_where ( 'menu', array (
				'inter_id' => $inter_id,
				'is_show' => 0,
				'parent_id' => 0 
		) );
		$kcount = $menus->num_rows ();
		$menus = $menus->result ();
		$k = 1;
		foreach ( $menus as $vo ) {
			$db_read->limit ( 5 );
			$db_read->order_by ( 'sort asc,id asc' );
			$submenus = $db_read->get_where ( 'menu', array (
					'inter_id' => $inter_id,
					'is_show' => 0,
					'parent_id' => $vo->id 
			) );
			// 主菜单
			$data .= '{"name":"' . $vo->title . '",';
			// $this->db->insert('weixin_text',array('content'=>json_encode($this->db->last_query())));
			$count = $submenus->num_rows ();
			$c = $submenus->result ();
			// 子菜单
			if ($count > 0) {
				$data .= '"sub_button":[';
			} else {
				if (! empty ( $vo->keyword )) {
					$data .= '"type":"click","key":"' . $vo->keyword . '"';
				} else if (! empty ( $vo->url )) {
					$data .= '"type":"view","url":"' . prep_url ( $vo->url ) . '"';
				} else if (! empty ( $vo->extend_menu )) {
					$data .= '"type":"' . $this->_get_sys ( 'send', $vo->extend_menu ) . '","key":"' . $vo->extend_menu . '"';
				}
			}
			$i = 1;
			foreach ( $c as $voo ) {
				if ($i == $count) {
					if (! empty ( $voo->keyword )) {
						$data .= '{"type":"click","name":"' . $voo->title . '","key":"' . $voo->keyword . '"}';
					} else if (! empty ( $voo->url )) {
						$data .= '{"type":"view","name":"' . $voo->title . '","url":"' . prep_url ( $voo->url ) . '"}';
					} else if (! empty ( $voo->extend_menu )) {
						$data .= '{"type":"' . $this->_get_sys ( 'send', $voo->extend_menu ) . '","name":"' . $voo->title . '","key":"' . $voo->extend_menu . '"}';
					}
				} else {
					if (! empty ( $voo->keyword )) {
						$data .= '{"type":"click","name":"' . $voo->title . '","key":"' . $voo->keyword . '"},';
					} else if (! empty ( $voo->url )) {
						$data .= '{"type":"view","name":"' . $voo->title . '","url":"' . prep_url ( $voo->url ) . '"},';
					} else if (! empty ( $voo->extend_menu )) {
						$data .= '{"type":"' . $this->_get_sys ( 'send', $voo->extend_menu ) . '","name":"' . $voo->title . '","key":"' . $voo->extend_menu . '"},';
					}
				}
				$i ++;
			}
			if ($count > 0) {
				$data .= ']';
			}
			if ($k == $kcount) {
				$data .= '}';
			} else {
				$data .= '},';
			}
			$k ++;
		}
		$data .= ']}';
		// var_dump($data);die;
		// $this->db->insert('weixin_text',array('content'=>$data));
		$result = doCurlGetRequest ( 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $access_token );
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token;
		$result = doCurlPostRequest ( $url, $data );
		$res = json_decode ( $result );
		if (isset ( $res->errcode ) && ($res->errcode == 40001 || $res->errcode == 42001)) {
			$this->access_token_model->reflash_access_token ( $inter_id );
			return $this->generate_menu ( $app_id, $app_secret, $inter_id );
		}
		return $result;
		// } else {
		// return json_encode($json);
		// }
	}
	/**
	 * 生成菜单
	 *
	 * @param String $app_id
	 * @param String $app_secret
	 * @return boolean
	 */
	function generate_menu_access_token($inter_id,$access_token,$do_continue = TRUE) {
		$this->load->helper('common');
		$db_read = $this->load->database('iwide_r1',true);
// 		$url_get = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $app_id . '&secret=' . $app_secret;
// 		$url_get = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token;
// 		$json = json_decode ( doCurlGetRequest ( $url_get ), TRUE );
// 		if (! isset ( $json ['errmsg'] )) {
			$data = '{"button":[';
			$db_read->limit ( 3 );
			$db_read->order_by ( 'sort asc,id asc' );
			$menus = $db_read->get_where ( 'menu', array (
					'inter_id' => $inter_id,
					'is_show' => 0,
					'parent_id' => 0
			) );
			$kcount = $menus->num_rows ();
			$menus = $menus->result ();
			$k = 1;
			foreach ( $menus as $vo ) {
				$db_read->limit ( 5 );
				$db_read->order_by ( 'sort asc,id asc' );
				$submenus = $db_read->get_where ( 'menu', array (
						'inter_id'  => $inter_id,
						'is_show'   => 0,
						'parent_id' => $vo->id
				) );
				// 主菜单
				$data .= '{"name":"' . $vo->title . '",';
				// $this->db->insert('weixin_text',array('content'=>json_encode($this->db->last_query())));
				$count = $submenus->num_rows ();
				$c = $submenus->result ();
				// 子菜单
				if ($count > 0) {
					$data .= '"sub_button":[';
				} else {
					if (!empty($vo->keyword)) {
						$data .= '"type":"click","key":"' . $vo->keyword . '"';
					} else if (!empty($vo->url)) {
						$data .= '"type":"view","url":"' . prep_url($vo->url) . '"';
					} else if (!empty($vo->extend_menu)) {
						$data .= '"type":"' . $this->_get_sys ( 'send', $vo->extend_menu ) . '","key":"' . $vo->extend_menu . '"';
					}
				}
				$i = 1;
				foreach ( $c as $voo ) {
					if ($i == $count) {
						if (!empty($voo->keyword)) {
							$data .= '{"type":"click","name":"' . $voo->title . '","key":"' . $voo->keyword . '"}';
						} else if (!empty($voo->url)) {
							$data .= '{"type":"view","name":"' . $voo->title . '","url":"' . prep_url($voo->url) . '"}';
						} else if (!empty($voo->extend_menu)) {
							$data .= '{"type":"' . $this->_get_sys ( 'send', $voo->extend_menu ) . '","name":"' . $voo->title . '","key":"' . $voo->extend_menu . '"}';
						}
					} else {
						if (!empty($voo->keyword)) {
							$data .= '{"type":"click","name":"' . $voo->title . '","key":"' . $voo->keyword . '"},';
						} else if (!empty($voo->url)) {
							$data .= '{"type":"view","name":"' . $voo->title . '","url":"' . prep_url($voo->url) . '"},';
						} else if (!empty($voo->extend_menu)) {
							$data .= '{"type":"' . $this->_get_sys ( 'send', $voo->extend_menu ) . '","name":"' . $voo->title . '","key":"' . $voo->extend_menu . '"},';
						}
					}
					$i ++;
				}
				if ($count > 0) {
					$data .= ']';
				}
				if ($k == $kcount) {
					$data .= '}';
				} else {
					$data .= '},';
				}
				$k ++;
			}
			$data .= ']}';
			//var_dump($data);die;
			// $this->db->insert('weixin_text',array('content'=>$data));
			doCurlGetRequest ( 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $access_token );
			$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token;
			$result = doCurlPostRequest ( $url, $data );
			MYLOG::w('Generate Menu | Admin Id:'.$this->session->get_admin_id().' | Admin Name:'.$this->session->get_admin_username().' | Access Token:'.$access_token.' | Menu:'.$data.' | Result:'.$result,'weixin_api');
			$res = json_decode($result);
			if (isset ( $res->errcode ) && ($res->errcode == 40001 || $res->errcode == 42001) && $do_continue) {
				$this->load->model('wx/access_token_model');
				$access_token = $this->access_token_model->reflash_access_token ( $inter_id );
				return $this->generate_menu_access_token ( $inter_id, $access_token,FALSE );
			}
			return $result;
// 		} else {
// 			return json_encode($json);
// 		}
	}
	function _get_sys($type = '', $key = '') {
		if ($type == 'send') {
			$wxsys = array (
					'扫码带提示' => 'scancode_waitmsg',
					'扫码推事件' => 'scancode_push',
					'系统拍照发图' => 'pic_sysphoto',
					'拍照或者相册发图' => 'pic_photo_or_album',
					'微信相册发图' => 'pic_weixin',
					'发送位置' => 'location_select'
			);
			return $wxsys [$key];
		}
		$wxsys = array (
				'扫码带提示',
				'扫码推事件',
				'系统拍照发图',
				'拍照或者相册发图',
				'微信相册发图',
				'发送位置'
		);
		return $wxsys;
	}
	private function arrCmp($a,$b){
		if(isset($a['sort']) && isset($b['sort'])){
			if($a['sort'] == $b['sort']){
				return 0;
			}
			return($a['sort']<$b['sort']) ? -1 : 1;
		}
		return 0;
	}
}