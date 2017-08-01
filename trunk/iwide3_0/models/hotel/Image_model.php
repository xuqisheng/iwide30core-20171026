<?php
class Image_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_HC = 'hotel_config';
	const TAB_HOTEL_IMGS = 'hotel_images';
	
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields() {
		return array (
				'info' => array (
						'label' => '图片介绍',
						'enable' => true 
				),
				'image_url' => array (
						'label' => '图片路径(点击显示)' 
				),
				'sort' => array (
						'label' => '排序(越大越前)',
						'enable' => true 
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
	function get_hotels_img($inter_id, $hotel_ids, $img_types, $status = 1) {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where ( 'inter_id', $inter_id );
		is_array ( $hotel_ids ) ? $db_read->where_in ( 'hotel_id', $hotel_ids ) : $db_read->where ( 'hotel_id', $hotel_ids );
		is_array ( $img_types ) ? $db_read->where_in ( 'type', $img_types ) : $db_read->where ( 'type', $img_types );
		is_null ( $status ) ? $db_read->where_in ( 'status', array (
				1,
				2 
		) ) : $db_read->where ( 'status', $status );
		$data = $db_read->get ( self::TAB_HOTEL_IMGS )->result_array ();
		$imgs = array ();
		foreach ( $data as $d ) {
			$imgs [$d ['hotel_id']] [$d ['type']] [$d ['id']] = $d;
		}
		return $imgs;
	}
	function get_hotels_icon($inter_id, $hotel_ids, $types, $effect = TRUE, $module = 'HOTEL') {
		$this->load->model ( 'hotel/Hotel_config_model' );
		$params = array ();
		$params ['effect'] = $effect == TRUE ? 1 : 0;
		$params ['default'] = 1;
		$data = $this->Hotel_config_model->get_hotels_config ( $inter_id, $module, $hotel_ids, $types, $params );
		$icons = array ();
		if (! empty ( $data [0] )) {
			foreach ( $data as $hotel_id => $config ) {
				if ($hotel_id != 0) {
					foreach ( $config as $type => $d ) {
						foreach ( $d as $dd ) {
							$tmp = explode ( ',', $dd ['param_value'] );
							foreach ( $tmp as $t ) {
								if (! empty ( $data [0] [$type] [$t] ['param_value'] ))
									$icons [$hotel_id] [$type] [$t] = $data [0] [$type] [$t] ['param_value'];
							}
						}
					}
				}
			}
		}
		return $icons;
	}
}