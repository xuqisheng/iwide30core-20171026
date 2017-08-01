<?php
class Gallery_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_HI = 'hotel_images';
	const TAB_HC = 'hotel_config';
	const PARAM_GALLERY = 'HOTEL_GALLERY_TYPE';
	function get_gallery_count($inter_id, $hotel_id = 0, $effect = true) {
		$db_read = $this->load->database('iwide_r1',true);
		$sql = 'select count(disp_type) g_nums,c.param_value gallery_name,c.priority,c.id gid,i.hotel_id from 
			   (SELECT * FROM `' . $db_read->dbprefix ( self::TAB_HI ) . "` where inter_id='$inter_id' ";
		empty ( $hotel_id ) ?  : $sql .= ' and hotel_id=' . $hotel_id;
		$sql .= " and disp_type >0 and status=1 order by sort desc) i ";
		if ($effect != true) {
			$sql .= ' right ';
		}
		$sql .= " join (select * from " . $db_read->dbprefix ( self::TAB_HC ) . " where param_name='HOTEL_GALLERY_TYPE' and inter_id='$inter_id' and module='HOTEL'";
		if ($effect == true) {
			$sql .= ' and priority > -1';
		}
		$sql .= " ) c on i.disp_type=c.id group by c.id order by c.priority desc";
		return $db_read->query ( $sql )->result_array ();
	}
	function get_gallery($inter_id, $condits, $effect = true, $nums = null, $offset = null) {
		$db_read = $this->load->database('iwide_r1',true);
		$sql = 'select i.*,c.id gid,c.param_value gallery_name from
			   (SELECT * FROM `' . $db_read->dbprefix ( self::TAB_HI ) . "` where inter_id='$inter_id' ";
		empty ( $condits ['hotel_id'] ) ?  : $sql .= ' and hotel_id=' . $condits ['hotel_id'];
		empty ( $condits ['room_id'] ) ?  : $sql .= ' and room_id=' . $condits ['room_id'];
		$sql .= empty ( $condits ['gallery_id'] ) ? ' and disp_type >0' : ' and disp_type=' . $condits ['gallery_id'];
		$sql .= " and status=1 order by sort desc) i
				   join (select * from " . $db_read->dbprefix ( self::TAB_HC ) . " where param_name='HOTEL_GALLERY_TYPE' and inter_id='$inter_id' and module='HOTEL'";
		if ($effect == true) {
			$sql .= ' and priority > -1';
		}
		$sql .= " ) c on i.disp_type=c.id ";
		is_null ( $nums ) ?  : $sql .= " limit $offset,$nums";
		return $db_read->query ( $sql )->result_array ();
	}
	Public function get_gallery_type($inter_id, $hotel_id = 0, $gallery_id = null) {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->order_by ( 'priority desc' );
		$db_read->where ( array (
				'param_name' => self::PARAM_GALLERY,
				'module' => 'HOTEL',
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id 
		) );
		if (! empty ( $gallery_id )) {
			$db_read->where ( 'id', $gallery_id );
			return $db_read->get ( self::TAB_HC )->row_array ();
		}
		return $db_read->get ( self::TAB_HC )->result_array ();
	}
	function get_hotel_gallery_by_gid($inter_id, $hotel_id, $gallery_id, $status = null, $nums = null, $offset = null) {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->order_by ( 'sort desc' );
		$db_read->where ( array (
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id,
				'disp_type' => $gallery_id 
		) );
		is_null ( $status ) || $db_read->where ( 'status', $status );
		return $db_read->get ( self::TAB_HI )->result_array ();
	}
	public function add_gallery_img($data) {
		if ($this->db->insert ( self::TAB_HI, $data ))
			return $this->db->insert_id ();
		return 0;
	}
	public function save_gallery_img_batch($inter_id, $hotel_id, $gallery_id, $datas) {
		$this->db->where_in ( 'id', array_keys ( $datas ) );
		$this->db->where ( array (
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id,
				'disp_type' => $gallery_id 
		) );
		return $this->db->update_batch ( self::TAB_HI, $datas, 'id' );
	}
	public function change_gallery_img_disp($inter_id, $hotel_id, $gallery_id, $ids, $disp_type) {
		$this->db->where_in ( 'id', $ids );
		$this->db->where ( array (
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id,
				'disp_type' => $gallery_id 
		) );
		return $this->db->update ( self::TAB_HI, array (
				'disp_type' => $disp_type 
		) );
	}
	public function update_gallery_type($inter_id, $id, $data) {
		$this->db->where ( array (
				'inter_id' => $inter_id,
				'id' => $id 
		) );
		return $this->db->update ( self::TAB_HC, $data );
	}
	public function add_hotel_gallery_type($inter_id, $data) {
		$data ['inter_id'] = $inter_id;
		$data ['param_name'] = 'HOTEL_GALLERY_TYPE';
		$data ['module'] = 'HOTEL';
		$data ['hotel_id'] = 0;
		return $this->db->insert ( self::TAB_HC, $data );
	}
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields() {
		return array (
				'gallery_name' => array (
						'label' => '相册名' 
				),
				'g_nums' => array (
						'label' => '图片数量' 
				) 
		);
	}
	public function type_fields() {
		return array (
				'param_value' => array (
						'label' => '相册名' 
				),
				'priority' => array (
						'label' => '排序' 
				) 
		);
	}
	public function table_fields() {
		return array (
				'param_name' => '',
				'param_value' => '',
				'priority' => 0,
				'id' => 0 
		);
	}
}