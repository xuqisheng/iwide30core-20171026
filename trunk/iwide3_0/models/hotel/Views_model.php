<?php
class Views_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const DEFAULT_SKIN='default2';
	function enums($type) {
		switch ($type) {
			case 'HOME_SETTING_SELECT' :
				return array (
						'always' => '常住酒店',
// 						'athour' => '时租房',
						'collect' => '我的收藏',
						'order' => '我的订单',
// 						'ticket' => '温泉预定' 
				);
				break;
			default :
				return array ();
				break;
		}
	}
	function get_view_config($inter_id, $view_function, $skin_name = 'default', $params = array()) {
		;
	}
	function get_homepage_config($inter_id, $effect = 1,$params=array()) {
// 		$this->load->model ( 'common/Enum_model' );
		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', 0, 'HOME_SETTING', array (
				'effect' => $effect 
		) );
		$config = array ();
		if (! empty ( $config_data ['HOME_SETTING'] )) {
			$select = $this->enums ( 'HOME_SETTING_SELECT' );
			$config = json_decode ( $config_data ['HOME_SETTING'], TRUE );
			if (! empty ( $config ['menu'] )) {
				foreach ( $config ['menu'] as &$m ) {
					$m ['menu_name'] = isset ( $select [$m ['code']] ) ? $select [$m ['code']] : '';
				}
			}
		}
		return $config;
	}
}