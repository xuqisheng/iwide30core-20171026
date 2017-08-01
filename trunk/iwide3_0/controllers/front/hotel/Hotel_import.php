<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Hotel_import extends CI_Controller {
	public $common_data;
	public $openid;
	public $module;
	function __construct() {
		parent::__construct ();
		
		
	}
	
	function subaimport_room() {
	
		set_time_limit(0);

	
		$this->load->model ('hotel/Hotelimport_model' );
	
	
		$this->Hotelimport_model->subaImport();
	
		/* INSERT INTO `iwide_hotel_rooms` (`room_id`, `inter_id`, `hotel_id`, `name`, `price`, `oprice`, `description`, `nums`, `bed_num`, `area`, `status`, `sort`, `room_img`, `book_policy`, `sub_des`, `webser_id`) VALUES
			(5175, 'a455510007', 1107, '经济大床房', '155.00', '1380.00', 'Business Room (One Bed) 位于酒店主楼一楼后段，房间设施有：两张小床或可拼大床、洗手间风格特异、宽带上网服务等，别墅或后花园山水景观。面积为36平方米', 10, 0, 0, 1, 0, 'http://file.iwide.cn/public/uploads/201603/a421641095hri_1_7.jpg', '', '', '2850');
		*/
	
	
	}
	
	function subaimport_onlyroom() {
	
	    set_time_limit(0);
	
	
	    $this->load->model ('hotel/Hotelimport_model' );
	
	
	    $this->Hotelimport_model->subaImportOnlyRoom();
	
	    /* INSERT INTO `iwide_hotel_rooms` (`room_id`, `inter_id`, `hotel_id`, `name`, `price`, `oprice`, `description`, `nums`, `bed_num`, `area`, `status`, `sort`, `room_img`, `book_policy`, `sub_des`, `webser_id`) VALUES
	     (5175, 'a455510007', 1107, '经济大床房', '155.00', '1380.00', 'Business Room (One Bed) 位于酒店主楼一楼后段，房间设施有：两张小床或可拼大床、洗手间风格特异、宽带上网服务等，别墅或后花园山水景观。面积为36平方米', 10, 0, 0, 1, 0, 'http://file.iwide.cn/public/uploads/201603/a421641095hri_1_7.jpg', '', '', '2850');
	    */
	
	
	}
	

	
	
	
}
