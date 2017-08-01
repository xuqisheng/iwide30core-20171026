<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Tag_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_TAG_TYPE = 'hotels_tag_types';
	const TAB_TAG_ITEMS = 'hotels_tag_items';
	const TAB_HOTEL_TAGS = 'hotels_tags';
	function type_fields_config() {
		$user_operations = array (
				'edit' => array (
						'<a href="',
						'key' => site_url ( 'hotel/tag/edit' ),
						'" class="btn btn-success btn-xs" title="编辑"><i class="fa fa-edit"></i> 编辑</a>' 
				),
				'items' => array (
						'<a href="',
						'key' => site_url ( 'hotel/tag/items' ),
						'" class="btn btn-info btn-xs" title="子项"><i class="fa fa-file-o"></i>子项</a> ' 
				) 
		);
		// $acl_array = $this->session->allow_actions;
		// $acl_array = $acl_array [ADMINHTML];
		// foreach ( $user_operations as $oper => $link ) {
		// if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['hotel'] ['coupons'] ) || ! in_array ( $oper, $acl_array ['hotel'] ['coupons'] ))) {
		// unset ( $user_operations [$oper] );
		// }
		// }
		return array (
				'name' => array (
						'label' => '标签名' 
				),
				'in_search' => array (
						'label' => '是否用于酒店搜索',
						'select' => array (
								'1' => '是',
								'0' => '否',
								'2' => '总是' 
						) 
				),
				'in_city' => array (
						'label' => '是否与城市关联',
						'select' => array (
								'1' => '是',
								'0' => '否'
						) 
				),
				'sort' => array (
						'label' => '排序(越大越前)'
				),
				'status' => array (
						'label' => '状态',
						'select' => array (
								'1' => '有效',
								'2' => '无效',
								'3' => '删除' 
						) 
				),
				'create_time' => array (
						'label' => '创建时间' 
				),
				'update_time' => array (
						'label' => '最后更新时间' 
				),
				'user_operations' => array (
						'label' => '操作',
						'user_operations' => $user_operations 
				) 
		);
	}
	function item_fields_config() {
		$user_operations = array (
				'edit' => array (
						'<a href="',
						'key' => site_url ( 'hotel/tag/item_edit' ),
						'" class="btn btn-success btn-xs" title="编辑"><i class="fa fa-edit"></i> 编辑</a>' 
				),
// 				'quick_save' => array (
// 						'|<button class="btn btn-success btn-xs" title="快捷保存" code="',
// 						'key'=>'',
// 						'"><i class="fa fa-save"></i> 快捷保存</button>'
// 				) 
		);
		// $acl_array = $this->session->allow_actions;
		// $acl_array = $acl_array [ADMINHTML];
		// foreach ( $user_operations as $oper => $link ) {
		// if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['hotel'] ['coupons'] ) || ! in_array ( $oper, $acl_array ['hotel'] ['coupons'] ))) {
		// unset ( $user_operations [$oper] );
		// }
		// }
		return array (
				'name' => array (
						'label' => '子项名' 
				),
				'city' => array (
						'label' => '城市' 
				),
				'sort' => array (
						'label' => '排序(越大越前)',
						'type'=>'input',
						'key'=>'item_id'
				),
				'status' => array (
						'label' => '状态',
						'select' => array (
								'1' => '有效',
								'2' => '无效',
								'3' => '删除' 
						) 
				),
				'user_operations' => array (
						'label' => '操作',
						'user_operations' => $user_operations 
				) 
		);
	}
	protected function _load_db($type) {
		switch ($type){
			case 'read':
				return $this->load->database('iwide_r1',true);
				break;
			default:
				return $this->db;
				break;
		}
	}
	public function get_tag_types($inter_id, $status = NULL, $nums = NULL, $offset = NULL) {
		$db = $this->_load_db ('read');
		$db->where ( 'inter_id', $inter_id );
		is_null ( $status ) ? $db->where_in ( 'status', array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		is_null ( $nums ) or $db->limit ( $nums, $offset );
		return $db->get ( self::TAB_TAG_TYPE )->result_array ();
	}
	public function get_tag_items($inter_id, $type_id, $status = NULL, $nums = NULL, $offset = NULL) {
		$db = $this->_load_db ('read');
		$db->where ( array (
				'inter_id' => $inter_id,
				'type_id' => $type_id 
		) );
		is_null ( $status ) ? $db->where_in ( 'status', array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		is_null ( $nums ) or $db->limit ( $nums, $offset );
		return $db->get ( self::TAB_TAG_ITEMS )->result_array ();
	}
	public function get_tag_item($inter_id, $type_id, $item_id, $status = NULL) {
		$db = $this->_load_db ('read');
		$db->where ( array (
				'inter_id' => $inter_id,
				'type_id' => $type_id,
				'item_id' => $item_id 
		) );
		is_null ( $status ) ? $db->where_in ( 'status', array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		$db->limit ( 1 );
		return $db->get ( self::TAB_TAG_ITEMS )->row_array ();
	}
	public function get_tag_type($inter_id, $type_id, $status = NULL) {
		$db = $this->_load_db ('read');
		$db->where ( array (
				'inter_id' => $inter_id,
				'type_id' => $type_id 
		) );
		is_null ( $status ) ? $db->where_in ( 'status', array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		$db->limit ( 1 );
		return $db->get ( self::TAB_TAG_TYPE )->row_array ();
	}
	function type_table_fields() {
		return array (
				'type_id' => '',
				'name' => '',
				'in_search' => 0,
				'in_city' => 0,
				'status' => 1,
				'sort'=>0
		);
	}
	function item_table_fields() {
		return array (
				'type_id' => '',
				'item_id' => '',
				'status' => 1,
				'name' => '',
				'city' => '',
				'sort'=>0
		);
	}
	function get_tag_hotels($inter_id, $tag_item_id, $status = NULL, $get_hotel = FALSE, $nums = NULL, $offset = NULL) {
		$db = $this->_load_db ('read');
		$db->where ( array (
				't.inter_id' => $inter_id,
				't.tag_item_id' => $tag_item_id 
		) );
		is_null ( $status ) ? $db->where_in ( 't.status', array (
				1,
				2 
		) ) : $db->where ( 't.status', $status );
		is_null ( $nums ) or $db->limit ( $nums, $offset );
		if ($get_hotel) {
			$db->select ( 't.*,h.name hotel_name' );
			$db->from ( self::TAB_HOTEL_TAGS . ' t' );
			$db->join ( 'hotels h', 'h.inter_id=t.inter_id and h.hotel_id=t.hotel_id' );
			return $db->get ()->result_array ();
		}
		return $db->get ( self::TAB_HOTEL_TAGS . ' t' )->result_array ();
	}
	function save_type($inter_id, $data, $mode = 'update') {
		$db = $this->_load_db ();
		if ($mode == 'add') {
			unset ( $data ['type_id'] );
			$data ['inter_id'] = $inter_id;
			$data ['create_time'] = date ( 'Y-m-d H:i:s' );
			return $db->insert ( self::TAB_TAG_TYPE, $data );
		} else if ($mode == 'update') {
			if (! empty ( $data ['type_id'] ) && ! empty ( $this->get_tag_type ( $inter_id, $data ['type_id'] ) )) {
				$db->where ( array (
						'inter_id' => $inter_id,
						'type_id' => $data ['type_id'] 
				) );
				unset ( $data ['type_id'] );
				unset ( $data ['inter_id'] );
				return $db->update ( self::TAB_TAG_TYPE, $data );
			}
		}
		return FALSE;
	}
	function add_tag_item($inter_id, $type_id, $data, $hotel_ids = array()) {
		$db = $this->_load_db ();
		$data ['inter_id'] = $inter_id;
		$data ['type_id'] = $type_id;
		$now = date ( 'Y-m-d H:i:s' );
		$data ['create_time'] = $now;
		if ($db->insert ( self::TAB_TAG_ITEMS, $data )) {
			$tag_item_id = $db->insert_id ();
			if (! empty ( $hotel_ids )) {
				$tag = array (
						'inter_id' => $inter_id,
						'tag_item_id' => $tag_item_id,
						'tag_type_id' => $type_id,
						'create_time' => $now,
						'update_time' => $now 
				);
				$tags = array ();
				foreach ( $hotel_ids as $h ) {
					$tag ['hotel_id'] = $h;
					$tags [] = $tag;
				}
				return $db->insert_batch ( self::TAB_HOTEL_TAGS, $tags );
			}
			return TRUE;
		}
		return FALSE;
	}
	/**
	 * @param unknown $inter_id
	 * @param unknown $type_id
	 * @param unknown $item_id
	 * @param unknown $data
	 * @param unknown $hotel_ids 要更新的酒店，为NULL则不需修改酒店
	 * @return boolean
	 */
	function update_tag_item($inter_id, $type_id, $item_id, $data, $hotel_ids = NULL) {
		$db = $this->_load_db ();
		$db->where ( array (
				'inter_id' => $inter_id,
				'type_id' => $type_id,
				'item_id' => $item_id 
		) );
		if ($db->update ( self::TAB_TAG_ITEMS, $data )) {
			if ($hotel_ids!==NULL){
				if (! empty ( $hotel_ids )) {
					$tag_hotels = $this->get_tag_hotels ( $inter_id, $item_id );
					$tag_hotels = array_column ( $tag_hotels, NULL, 'hotel_id' );
					$new_ids = array ();
					$valid_ids = array ();
					foreach ( $hotel_ids as $item ) {
						if (isset ( $tag_hotels [$item] )) {
							if ($tag_hotels [$item] ['status'] != 1) {
								$valid_ids [] = $item;
							}
							unset ( $tag_hotels [$item] );
						} else {
							$new_ids [] = $item;
						}
					}
					if (! empty ( $tag_hotels )) {
						$db->where ( array (
								'inter_id' => $inter_id,
								'tag_item_id' => $item_id,
								'tag_type_id' => $type_id
						) );
						$db->where_in ( 'hotel_id', array_keys ( $tag_hotels ) );
						$db->update ( self::TAB_HOTEL_TAGS, array (
								'status' => 2 
						) );
					}
					if (! empty ( $valid_ids )) {
						$db->where ( array (
								'inter_id' => $inter_id,
								'tag_item_id' => $item_id,
								'tag_type_id' => $type_id 
						) );
						$db->where_in ( 'hotel_id', $valid_ids );
						$db->update ( self::TAB_HOTEL_TAGS, array (
								'status' => 1 
						) );
					}
					if (! empty ( $new_ids )) {
						$now = date ( 'Y-m-d H:i:s' );
						$tag = array (
								'inter_id' => $inter_id,
								'tag_item_id' => $item_id,
								'create_time' => $now,
								'update_time' => $now,
								'tag_type_id' => $type_id 
						);
						$tags = array ();
						foreach ( $new_ids as $h ) {
							$tag ['hotel_id'] = $h;
							$tags [] = $tag;
						}
						$db->insert_batch ( self::TAB_HOTEL_TAGS, $tags );
					}
					return TRUE;
				} else {
					$db->where ( array (
							'inter_id' => $inter_id,
							'tag_item_id' => $item_id 
					) );
					$db->update ( self::TAB_HOTEL_TAGS, array (
							'status' => 2 
					) );
				}
			}
			return TRUE;
		}
		return FALSE;
	}
	function get_city_tag($inter_id,$city,$status=NULL,$params=array()){
		$db = $this->_load_db ('read');
		$sql='select t.name type_name,i.* from '.$db->dbprefix(self::TAB_TAG_TYPE).' t join '.$db->dbprefix(self::TAB_TAG_ITEMS).' i';
		$sql.=' on t.inter_id=i.inter_id and t.type_id=i.type_id ';
		$where=" where t.inter_id='$inter_id' and t.in_search in (1,2) and t.status=1 ";
		$where.=is_null($status)?' and i.status in (1,2) ':' and i.status = 1 ';
		$where.=" and ( i.city like '%".addslashes($city)."%' or t.in_search = 2 ) ";
		$sql.=$where;
		$sql.=' order by t.sort desc,i.sort desc';
// 		$db->select('t.name type_name,i.*');
// 		$db->from(self::TAB_TAG_TYPE.' t');
// 		$db->join(self::TAB_TAG_ITEMS.' i','t.inter_id=i.inter_id and t.type_id=i.type_id');
// 		$db->where(array('t.inter_id'=>$inter_id,'t.in_city'=>1,'t.status'=>1));
// 		$db->like('i.city',$city);
// 		is_null($status)?$db->where_in('i.status',array(1,2)):$db->where('i.status',1);
// 		$result=$db->get()->result_array();
		$result=$db->query($sql)->result_array();
		$data=array();
		if (!empty($result)){
			foreach ($result as $r){
				$data[$r['type_id']]['type_name']=$r['type_name'];
				$data[$r['type_id']]['items'][$r['item_id']]['name']=$r['name'];
				$data[$r['type_id']]['items'][$r['item_id']]['item_id']=$r['item_id'];
			}
		}
		return $data;
	}
	function get_tag_hotel($inter_id,$item_id,$status=NULL,$params=array()){
		$db = $this->_load_db ('read');
		$db->select('tags.*,typ.in_city,typ.in_search,typ.name type_name');
		$db->from(self::TAB_HOTEL_TAGS.' tags');
		$db->join(self::TAB_TAG_TYPE.' typ',' typ.inter_id=tags.inter_id and typ.type_id=tags.tag_type_id ');
		$db->where(array('tags.inter_id'=>$inter_id,'tags.tag_item_id'=>$item_id));
		is_null($status)?$db->where_in('tags.status',array(1,2)):$db->where('tags.status',1);
		return $db->get()->result_array();
	}
}