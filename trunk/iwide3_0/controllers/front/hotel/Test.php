<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Test extends MY_Controller {
	function __construct() {
		parent::__construct ();
		set_time_limit ( 0 );
	}
	function db_test() {
		// var_dump ( $this->db->get ( 'enum_desc' )->result () );
		// $result = $this->db->get ( 'hotel_lowest_price' )->result ();
		// var_dump ( $result );
		// echo $this->db->last_query ();
        $arr=array('a'=>1);
        $_POST=$arr;
        echo 'post:';
        var_dump($this->input->post());
        $this->session->set_flashdata('ccc',json_encode($arr));
        var_dump($this->session->flashdata('ccc'));
	}
	function update_lowest() {
		$this->db->where ( 'inter_id', 'a441098524' );
		// $this->db->where('update_time','a441098524');
		$lowest = $this->db->get ( 'hotel_lowest_price' )->result_array ();
		$has_h = array ();
		foreach ( $lowest as $l ) {
			$has_h [] = $l ['hotel_id'];
		}
		$this->db->where ( 'inter_id', 'a441098524' );
		$this->db->where ( 'hotel_id >', 0 );
		$result = $this->db->get ( 'hotel_additions' )->result_array ();
		$this->load->model ( 'hotel/pms/Yibo_hotel_model', 'yibo' );
		$startdate = date ( 'Ymd' );
		$enddate = date ( 'Ymd', strtotime ( '+ 5 day', time () ) );
		$tmp = array (
				'inter_id' => 'a441098524',
				'update_time' => date ( 'Y-m-d H:i:s' ) 
		);
		foreach ( $result as $r ) {
			if (! in_array ( $r ['hotel_id'], $has_h )) {
				echo $r ['hotel_web_id'] . ',';
				$low = $this->yibo->get_web_room_status ( $r ['hotel_web_id'], $startdate, $enddate, '' );
				if (! empty ( $low ['Data'] ['LowestRoomRate'] )) {
					$tmp ['hotel_id'] = $r ['hotel_id'];
					$tmp ['lowest_price'] = $low ['Data'] ['LowestRoomRate'];
					$this->db->replace ( 'hotel_lowest_price', $tmp );
				}
			}
		}
	}
	function yibo_member() {
		$this->load->library ( 'PMS_Adapter' );
		$adapter = new PMS_Adapter ( array (
				'inter_id' => 'a429262687',
				'hotel_id' => 0 
		) );
		$info = $adapter->getMemberByOpenId ( array (
				'oX3WojhfNUD4JzmlwTzuKba1MywY' 
		) );
		var_dump ( $info );
	}
	function date_test() {
		$this->load->helper ( 'date' );
		$range = date_range ( '2012-01-01', '2012-01-15' );
		echo implode ( ',', $range );
	}
	function clear_session() {
		var_dump ( $this->db->get ( 'weixin_text' )->result () );
		// unset($_SESSION);
		// $this->db->insert('hotel_room_state',array('hotel_id'=>1,'inter_id'=>'a429262687','price'=>0.01,'nums'=>4,'room_id'=>1,'price_code'=>2,'date'=>20151119));
	}
	function pay_test() {
		$this->load->model ( 'hotel/Order_model' );
		$this->Order_model->pay_return ( 'wY144790069869117' );
	}
	function handel_test() {
		$this->load->model ( 'hotel/Order_model' );
		$this->Order_model->handle_order ( 'a429262687', $this->input->get ( 'o' ), $this->input->get ( 's' ) );
	}
	function add_room() {
		exit ();
		$hotels = $this->db->get_where ( 'hotels' )->result ();
		$rooms = array (
				'1' => '豪华大床房',
				'2' => '标准双人房',
				'3' => '豪华双人房',
				'4' => '行政双人房' 
		);
		$room_img = 'http://ihotels.iwide.cn/public/hotel/uploads/a429262687/img/rm_';
		$room_r = 'http://ihotels.iwide.cn/public/hotel/uploads/a429262687/img/rmr_';
		$room = array (
				'inter_id' => 'a429262687' 
		);
		foreach ( $hotels as $h ) {
			foreach ( $rooms as $k => $r ) {
				$room ['hotel_id'] = $h->hotel_id;
				$room ['name'] = $r;
				$room ['price'] = mt_rand ( 300, 500 );
				$room ['oprice'] = mt_rand ( 500, 700 );
				$room ['description'] = $h->name . '-' . $r;
				$room ['nums'] = mt_rand ( 2, 20 );
				$room ['sort'] = mt_rand ( 1, 20 );
				$room ['room_img'] = $room_img . $k . '.jpg';
				$this->db->insert ( 'hotel_rooms', $room );
				$img = array ();
				$img ['room_id'] = $this->db->insert_id ();
				$img ['inter_id'] = 'a429262687';
				$img ['hotel_id'] = $h->hotel_id;
				$img ['image_url'] = $room_r . $k . '.png';
				$img ['info'] = $r;
				$img ['type'] = 'hotel_room_lightbox';
				$img ['sort'] = mt_rand ( 1, 20 );
				$this->db->insert ( 'hotel_images', $img );
			}
		}
	}
	function add_hotel_lb() {
		$hotels = $this->db->get_where ( 'hotels' )->result ();
		$rooms = array (
				'1',
				'2',
				'3',
				'4' 
		);
		$room_img = 'http://ihotels.iwide.cn/public/hotel/uploads/a429262687/img/hrb_';
		foreach ( $hotels as $h ) {
			foreach ( $rooms as $k => $r ) {
				$img = array ();
				$img ['room_id'] = 0;
				$img ['inter_id'] = 'a429262687';
				$img ['hotel_id'] = $h->hotel_id;
				$img ['image_url'] = $room_img . $r . '.jpg';
				$img ['info'] = $h->name;
				$img ['type'] = 'hotel_lightbox';
				$img ['sort'] = mt_rand ( 1, 20 );
				$this->db->insert ( 'hotel_images', $img );
			}
		}
	}
	function add_hotel_gallery() {
		exit ();
		$hotels = $this->db->get_where ( 'hotels' )->result ();
		$rooms = array (
				'1',
				'2',
				'3',
				'4',
				'5' 
		);
		$room_img = 'http://ihotels.iwide.cn/public/hotel/uploads/a429262687/img/hg_';
		foreach ( $hotels as $h ) {
			foreach ( $rooms as $k => $r ) {
				$img = array ();
				$img ['room_id'] = 0;
				$img ['inter_id'] = 'a429262687';
				$img ['hotel_id'] = $h->hotel_id;
				$img ['image_url'] = $room_img . $r . '.jpg';
				$img ['info'] = $h->name;
				$img ['type'] = 'hotel_gallery';
				$img ['disp_type'] = '20';
				$img ['sort'] = mt_rand ( 1, 20 );
				$this->db->insert ( 'hotel_images', $img );
			}
		}
	}
	function add_state() {
		exit ();
		// $this->db->limit ( 5 );
		// $this->db->where ( 'area', 0 );
		$rooms = $this->db->get ( 'hotel_rooms' )->result ();
		$price_codes = array (
				'4' 
		);
		$s = 20151121;
		$e = 20161231;
		$data = array ();
		$this->load->helper ( 'date' );
		$day_range = get_day_range ( $s, $e, 'array' );
		foreach ( $rooms as $r ) {
			$tmp = array (
					'inter_id' => 'a429262687',
					'edittime' => time () 
			);
			$tmp ['room_id'] = $r->room_id;
			$tmp ['hotel_id'] = $r->hotel_id;
			foreach ( $day_range as $d ) {
				$tmp ['date'] = $d;
				foreach ( $price_codes as $pcs ) {
					$tmp ['price_code'] = $pcs;
					$tmp ['nums'] = null;
					if ($pcs == 4) {
						if ($r->name == '豪华大床房')
							$tmp ['price'] = mt_rand ( 30, 32 ) . '8';
						else if ($r->name == '标准双人房')
							$tmp ['price'] = mt_rand ( 20, 22 ) . '8';
						else if ($r->name == '豪华双人房')
							$tmp ['price'] = mt_rand ( 40, 42 ) . '8';
						else if ($r->name == '行政双人房')
							$tmp ['price'] = mt_rand ( 35, 37 ) . '8';
						else
							$tmp ['price'] = mt_rand ( 20, 40 ) . '8';
					} else if ($pcs == 2) {
						if ($r->name == '豪华大床房')
							$tmp ['price'] = mt_rand ( 32, 35 ) . '8';
						else if ($r->name == '标准双人房')
							$tmp ['price'] = mt_rand ( 22, 25 ) . '8';
						else if ($r->name == '豪华双人房')
							$tmp ['price'] = mt_rand ( 42, 45 ) . '8';
						else if ($r->name == '行政双人房')
							$tmp ['price'] = mt_rand ( 37, 40 ) . '8';
						else
							$tmp ['price'] = mt_rand ( 20, 40 ) . '8';
					} else if ($pcs == 3) {
						if ($r->name == '豪华大床房')
							$tmp ['price'] = mt_rand ( 27, 30 ) . '8';
						else if ($r->name == '标准双人房')
							$tmp ['price'] = mt_rand ( 17, 20 ) . '8';
						else if ($r->name == '豪华双人房')
							$tmp ['price'] = mt_rand ( 37, 40 ) . '8';
						else if ($r->name == '行政双人房')
							$tmp ['price'] = mt_rand ( 30, 35 ) . '8';
						else
							$tmp ['price'] = mt_rand ( 20, 40 ) . '8';
					} else if ($pcs == - 1) {
						if ($r->name == '豪华大床房') {
							$tmp ['price'] = '388';
							$tmp ['nums'] = '10';
						} else if ($r->name == '标准双人房') {
							$tmp ['price'] = '288';
							$tmp ['nums'] = '15';
						} else if ($r->name == '豪华双人房') {
							$tmp ['price'] = '488';
							$tmp ['nums'] = '8';
						} else if ($r->name == '行政双人房') {
							$tmp ['price'] = '408';
							$tmp ['nums'] = '10';
						} else {
							$tmp ['price'] = mt_rand ( 20, 40 ) . '8';
							$tmp ['nums'] = mt_rand ( 5, 10 );
						}
					}
					// $this->db->insert('hotel_room_state',$tmp);
					$tmp ['price'] -= 20;
					$data [] = $tmp;
				}
			}
			// $data=array();
			// $this->db->where ( 'room_id', $r->room_id );
			// $this->db->update ( 'hotel_rooms', array (
			// 'area' => 1
			// ) );
		}
		$this->db->insert_batch ( 'hotel_room_state', $data );
		var_dump ( $data );
		echo 's';
	}
	function add_room_no() {
		exit ();
		$rooms = $this->db->get ( 'hotel_rooms' )->result ();
		$floors = array (
				'1',
				'2',
				'3',
				'4',
				'5' 
		);
		$des = array (
				'无烟',
				'有窗',
				'无窗',
				'靠电梯',
				'安静',
				'Wifi特好',
				'',
				'',
				'',
				'光线好' 
		);
		$data = array ();
		foreach ( $rooms as $r ) {
			$tmp = array (
					'inter_id' => 'a429262687' 
			);
			$tmp ['room_id'] = $r->room_id;
			$tmp ['hotel_id'] = $r->hotel_id;
			if ($r->name == '豪华大床房')
				$floor = 1;
			else if ($r->name == '标准双人房')
				$floor = 2;
			else if ($r->name == '豪华双人房')
				$floor = 3;
			else if ($r->name == '行政双人房')
				$floor = 4;
			else
				$floor = 5;
			for($i = 0; $i < $r->nums; $i ++) {
				if (strlen ( $i ) == 1)
					$tmp ['room_no'] = $floor . '0' . $i;
				else
					$tmp ['room_no'] = $floor . $i;
				$tmp ['sort'] = mt_rand ( 1, 50 );
				$tmp ['des'] = $des [array_rand ( $des )];
				$data [] = $tmp;
			}
		}
		$this->db->insert_batch ( 'hotel_room_numbers', $data );
		var_dump ( $data );
		echo 's';
	}
	function add_price_set() {
		exit ();
		$rooms = $this->db->get ( 'hotel_rooms' )->result ();
		$set = array (
				'inter_id' => 'a429262687' 
		);
		$data = array ();
		$price_codes = array (
				'1' => array (
						'more' => - 20 
				),
				'2' => array (
						'more' => 0 
				),
				'3' => array (
						'more' => - 30 
				),
				'4' => array (
						'more' => - 10 
				),
				'5' => array (
						'more' => 0 
				),
				'6' => array (
						'more' => 0 
				) 
		);
		foreach ( $rooms as $r ) {
			$set ['hotel_id'] = $r->hotel_id;
			$set ['room_id'] = $r->room_id;
			foreach ( $price_codes as $k => $pcs ) {
				$set ['price_code'] = $k;
				$set ['price'] = $r->price + $pcs ['more'];
				$set ['nums'] = $r->nums;
				$set ['edittime'] = time ();
				$data [] = $set;
			}
		}
		$this->db->insert_batch ( 'hotel_price_set', $data );
		echo 's';
	}
	function add_room_price_set() {
		exit ();
		$hotels = $this->db->get_where ( 'hotels', array (
				'inter_id' => 'a429262687' 
		) )->result ();
		$set = array (
				'inter_id' => 'a429262687' 
		);
		$data = array ();
		$price_codes = array (
				'1',
				'2',
				'3',
				'4' 
		);
		foreach ( $hotels as $h ) {
			$set ['hotel_id'] = $h->hotel_id;
			foreach ( $price_codes as $pcs ) {
				$set ['price_code'] = $pcs;
				$set ['edittime'] = time ();
				$data [] = $set;
			}
		}
		$this->db->insert_batch ( 'hotel_price_set', $data );
		echo 's';
	}
	function add_user_card() {
		$this->load->model ( 'member/igetcard' );
		var_dump ( $this->igetcard->userGetCard ( 'oX3WojhfNUD4JzmlwTzuKba1MywY', 'a429262687', 1, 1, 5, array (
				'module' => 'hotel',
				'scene' => 'order',
				'scene_id' => 'wY144774542886576' 
		) ) );
	}
	function get_mem_rule() {
		$this->load->model ( 'member/Irule' );
		var_dump ( $this->Irule->getRewardByRules ( 'room', null, 'a429262687', array (
				'hotel' => 1,
				'rooms' => 1,
				'days' => 1,
				'product_num' => 1,
				'hotel_checkout' => 1,
				'consume_completed' => 1 
		) ) );
	}
	function level_p() {
		$this->load->model ( 'member/Iconfig' );
		var_dump ( $this->Iconfig->getPrivilegeByModule ( 'room', $this->input->get ( 'id' ) ) );
	}
	function point_rate() {
		// $this->load->model('member/Iconfig');
		// var_dump($this->Iconfig->getBtoMByModule('room','a429262687'));
		$this->load->model ( 'hotel/Member_model' );
		var_dump ( $this->Member_model->get_point_give_rate ( 'a429262687', 1 ) );
		var_dump ( $this->Member_model->get_point_consum_rate ( 'a429262687', 1 ) );
	}
	function mem_level() {
		$this->load->model ( 'member/Imember' );
		var_dump ( $this->Imember->getAllMemberLevels ( 'a429262687' ) );
	}
	function check_member() {
		$this->load->model ( 'hotel/Coupon_model' );
		var_dump ( $this->Coupon_model->check_openid_member ( 'a429262687', 'oX3Wojs1TeNMthK2L9u2MOWYvTaw' ) );
	}
	function find_mem() {
		$this->load->model ( 'hotel/Member_model' );
		$check = $this->Member_model->check_openid_member ( 'a429262687', $_GET ['openid'] );
		var_dump ( $check );
	}
	function point_get_rate() {
		$this->load->model ( 'member/Iconfig' );
		var_dump ( $this->Iconfig->getBonusruleMByModule ( 'room', 'a429262687' ) );
		// var_dump ( $this->Iconfig->getBtoMByModule ( 'room', 'a429262687' ) );
	}
	function balance_pay() {
		$this->load->model ( 'hotel/Order_model' );
		var_dump ( $this->Order_model->update_order_status ( 'a429262687', 'wY144887672192657', 1 ) );
	}
	function update_order_status() {
		$this->load->model ( 'hotel/Order_model' );
		var_dump ( $this->Order_model->update_order_status ( $this->input->get ( 'id' ), $this->input->get ( 'o' ), $this->input->get ( 's' ) ) );
	}
	function price_code() {
		$this->load->model ( 'hotel/Price_code_model' );
		var_dump ( $this->Price_code_model->get_room_price_set ( 'a429262687', 1, 1 ) );
		// var_dump($this->Price_code_model->get_price_codes('a429262687', 1));
		// var_dump($this->Price_code_model->get_hotel_price_codes('a429262687',2, 1));
	}
	function temp_stock() {
		$this->load->model ( 'hotel/Room_status_model' );
		// $this->Room_status_model->change_hotel_temp_stock(array('inter_id'=>'a429262687','hotel_id'=>1,'room_id'=>1,'price_code'=>1),'20151203','20151209',3);
		var_dump ( $this->Room_status_model->get_hotel_type_stock ( 'a429262687', 2, '32', 20151204, 20151207 ) );
	}
	function get_room_state() {
		$this->load->model ( 'hotel/Room_status_model' );
		var_dump ( $this->Room_status_model->get_room_price ( 'a429262687', 1, 1, 1, 20151201, 20151212 ) );
	}
	function add_room_service() {
		exit ();
		$this->db->where ( 'inter_id', 'a449664652' );
		$rooms = $this->db->get ( 'hotel_rooms' )->result ();
		$sql = "SELECT * FROM `iwide_hotel_images` WHERE `inter_id` LIKE 'defaultimg' AND `type` LIKE 'hotel_room_service'";
		$service = $this->db->query ( $sql )->result_array ();
		$data = array ();
		$tmp = array (
				'inter_id' => 'a449664652' 
		);
		foreach ( $rooms as $r ) {
			$tmp ['hotel_id'] = $r->hotel_id;
			$tmp ['room_id'] = $r->room_id;
			$tmp ['type'] = 'hotel_room_service';
			$t_s = array_rand ( $service, 3 );
			foreach ( $t_s as $t ) {
				$tmp ['info'] = $service [$t] ['info'];
				$tmp ['image_url'] = $service [$t] ['image_url'];
				$data [] = $tmp;
			}
		}
		$this->db->insert_batch ( 'hotel_images', $data );
	}
	function yibohotel_trans() {
		$inter_id = 'a441098524';
		$sql = "SELECT * FROM `iwide_tmp_hotels` where inter_id='$inter_id' and fax=0 ";
		$old_hotels = $this->db->query ( $sql )->result_array ();
		$sql = "SELECT * FROM `iwide_tmp_hotels_rooms`  where inter_id='$inter_id'";
		$old_rooms = $this->db->query ( $sql )->result_array ();
		$hotel_rooms = array ();
		foreach ( $old_rooms as $or ) {
			$hotel_rooms [$or ['hotel_id']] [] = $or;
		}
		// var_dump($hotel_rooms);
		// var_dump($old_hotels);
		// exit;
		$room = array (
				'inter_id' => $inter_id 
		);
		
		$data = array (
				'inter_id' => $inter_id 
		);
		$addition = array (
				'inter_id' => $inter_id,
				'pms_type' => 'yibo',
				'pms_room_state_way' => 3 
		);
		$hotel_additions = array ();
		$img = array (
				'inter_id' => $inter_id,
				'info' => 'intro',
				'type' => 'hotel_lightbox',
				'room_id' => 0 
		);
		$imgs = array ();
		$hotel_count = 0;
		$room_count = 0;
		$img_count = 0;
		$addition_count = 0;
		foreach ( $old_hotels as $oh ) {
			$data ['latitude'] = $oh ['longitude'];
			$data ['longitude'] = $oh ['latitude'];
			$data ['address'] = $oh ['address'];
			$data ['tel'] = $oh ['tel'];
			$data ['name'] = $oh ['name'];
			$data ['hotel_id'] = $oh ['hotel_id'];
			$data ['intro'] = $oh ['intro'];
			$data ['email'] = $oh ['email'];
			$data ['fax'] = $oh ['fax'];
			$data ['star'] = 0;
			$data ['country'] = $oh ['country'];
			$data ['province'] = $oh ['province'];
			$data ['web'] = 'http://www.100inn.cc';
			$data ['city'] = $oh ['city'];
			$data ['intro_img'] = 'http://file.iwide.cn/public/uploads/201512/yibohotelintro_' . $oh ['hotel_id'] . '.jpg';
			$data ['status'] = $oh ['status'] == 0 ? 1 : 2;
			$this->db->insert ( 'yibohotels', $data );
			// $new_id = $this->db->insert_id ();
			$this->db->where ( 'hotel_id', $oh ['hotel_id'] );
			$this->db->update ( 'tmp_hotels', array (
					'fax' => 1 
			) );
			$hotel_count ++;
			$addition ['hotel_id'] = $oh ['hotel_id'];
			$addition ['hotel_web_id'] = $oh ['webser_id'];
			
			$this->db->insert ( 'yibohotel_additions', $addition );
			$addition_count ++;
			// for($i = 1; $i <= 3; $i ++) {
			// $img ['hotel_id'] = $new_id;
			// $img ['image_url'] = 'http://file.iwide.cn/public/uploads/201512/yibohotel_' . $oh ['hotel_id'] . '-' . $i . '.jpg';
			// $this->db->insert ( 'hotel_images', $img );
			// $img_count ++;
			// }
			$room ['hotel_id'] = $oh ['hotel_id'];
			if (! empty ( $hotel_rooms [$oh ['hotel_id']] )) {
				foreach ( $hotel_rooms [$oh ['hotel_id']] as $hr ) {
					$room ['name'] = $hr ['name'];
					$room ['room_id'] = $hr ['room_id'];
					$room ['price'] = $hr ['price'];
					$room ['oprice'] = $hr ['vprice'];
					$room ['description'] = $hr ['description'];
					$room ['nums'] = $hr ['nums'];
					$room ['bed_num'] = $hr ['bed_num'];
					$room ['area'] = $hr ['area'];
					$room ['status'] = $hr ['status'] == 0 ? 1 : 2;
					$room ['webser_id'] = $hr ['webser_id'];
					$room ['sort'] = $hr ['sort'];
					$this->db->insert ( 'yibohotel_rooms', $room );
					// $rid = $this->db->insert_id ();
					// $this->db->where ( 'room_id', $hr ['room_id'] );
					// $this->db->update ( 'tmp_hotels_rooms', array (
					// 'new_id' => $rid
					// ) );
					$room_count ++;
				}
			}
		}
		// $this->db->insert_batch ( 'hotel_additions', $hotel_additions );
		// $this->db->insert_batch ( 'hotel_images', $imgs );
		echo 'hotel:' . $hotel_count . '<br />';
		echo 'room:' . $room_count . '<br />';
		echo 'img:' . $img_count . '<br />';
		echo 'addition:' . $addition_count . '<br />';
	}
	function update_new_img() {
		$inter_id = 'a441098524';
		$hotel_light_box = 'yibohotel_1-1.jpg,yibohotel_100-1.jpg,yibohotel_100-3.jpg,yibohotel_103-3.jpg,yibohotel_108-1.jpg,yibohotel_116-1.jpg,yibohotel_116-2.jpg,yibohotel_116-3.jpg,yibohotel_13-1.jpg,yibohotel_13-2.jpg,yibohotel_131-1.jpg,yibohotel_131-2.jpg,yibohotel_131-3.jpg,yibohotel_14-1.jpg,yibohotel_142-1.jpg,yibohotel_142-2.jpg,yibohotel_142-3.jpg,yibohotel_148-2.jpg,yibohotel_148-3.jpg,yibohotel_152-1.jpg,yibohotel_152-2.jpg,yibohotel_152-3.jpg,yibohotel_157-1.jpg,yibohotel_157-2.jpg,yibohotel_157-3.jpg,yibohotel_16-2.jpg,yibohotel_16-3.jpg,yibohotel_168-1.jpg,yibohotel_168-2.jpg,yibohotel_168-3.jpg,yibohotel_176-1.jpg,yibohotel_176-2.jpg,yibohotel_176-3.jpg,yibohotel_177-2.jpg,yibohotel_177-3.jpg,yibohotel_177-4.jpg,yibohotel_184-1.jpg,yibohotel_184-2.jpg,yibohotel_184-3.jpg,yibohotel_189-1.jpg,yibohotel_189-2.jpg,yibohotel_189-3.jpg,yibohotel_192-1.jpg,yibohotel_192-2.jpg,yibohotel_192-3.jpg,yibohotel_192-4.jpg,yibohotel_197-1.jpg,yibohotel_197-2.jpg,yibohotel_197-3.jpg,yibohotel_198-1.jpg,yibohotel_198-2.jpg,yibohotel_198-3.jpg,yibohotel_199-1.jpg,yibohotel_199-2.jpg,yibohotel_199-3.jpg,yibohotel_2-2.jpg,yibohotel_2-3.jpg,yibohotel_20-2.jpg,yibohotel_20-3.jpg,yibohotel_200-1.jpg,yibohotel_200-2.jpg,yibohotel_200-3.jpg,yibohotel_202-1.jpg,yibohotel_202-2.jpg,yibohotel_202-3.jpg,yibohotel_206-1.jpg,yibohotel_206-3.jpg,yibohotel_21-2.jpg,yibohotel_21-3.jpg,yibohotel_22-1.jpg,yibohotel_220-1.jpg,yibohotel_220-2.jpg,yibohotel_220-3.jpg,yibohotel_223-1.jpg,yibohotel_223-2.jpg,yibohotel_223-3.jpg,yibohotel_224-1.jpg,yibohotel_226-1.jpg,yibohotel_226-2.jpg,yibohotel_226-3.jpg,yibohotel_232-1.jpg,yibohotel_232-2.jpg,yibohotel_232-3.jpg,yibohotel_237-2.jpg,yibohotel_237-3.jpg,yibohotel_24-1.jpg,yibohotel_24-3.jpg,yibohotel_244-1.jpg,yibohotel_244-2.jpg,yibohotel_244-3.jpg,yibohotel_246-1.jpg,yibohotel_246-2.jpg,yibohotel_247-1.jpg,yibohotel_247-2.jpg,yibohotel_247-3.jpg,yibohotel_254-3.jpg,yibohotel_260-3.png,yibohotel_264-1.jpg,yibohotel_264-2.jpg,yibohotel_264-3.jpg,yibohotel_269-2.jpg,yibohotel_269-3.jpg,yibohotel_271-1.jpg,yibohotel_271-3.jpg,yibohotel_273-2.jpg,yibohotel_273-3.jpg,yibohotel_274-1.jpg,yibohotel_274-2.jpg,yibohotel_276-1.jpg,yibohotel_276-2.jpg,yibohotel_276-3.jpg,yibohotel_284-1.jpg,yibohotel_284-3.jpg,yibohotel_285-1.jpg,yibohotel_285-2.jpg,yibohotel_287-1.jpg,yibohotel_287-2.jpg,yibohotel_287-3.jpg,yibohotel_288-1.jpg,yibohotel_288-2.jpg,yibohotel_288-3.jpg,yibohotel_291-2.jpg,yibohotel_291-3.jpg,yibohotel_292-1.jpg,yibohotel_292-2.jpg,yibohotel_293-1.jpg,yibohotel_293-3.jpg,yibohotel_294-3.jpg,yibohotel_295-2.jpg,yibohotel_295-3.jpg,yibohotel_296-1.jpg,yibohotel_297-1.jpg,yibohotel_297-2.jpg,yibohotel_297-3.jpg,yibohotel_299-2.jpg,yibohotel_299-3.jpg,yibohotel_30-1.jpg,yibohotel_30-3.jpg,yibohotel_300-1.jpg,yibohotel_300-2.jpg,yibohotel_300-3.jpg,yibohotel_301-1.jpg,yibohotel_301-2.jpg,yibohotel_302-1.jpg,yibohotel_302-2.jpg,yibohotel_303-1.jpg,yibohotel_303-3.jpg,yibohotel_305-1.jpg,yibohotel_305-2.jpg,yibohotel_305-3.jpg';
		$hotel_light_box = explode ( ',', $hotel_light_box );
		// foreach ($hotel_light_box as $hb){
		// echo '<img src="http://file.iwide.cn/public/uploads/201512/'.$hb.'" />';
		// }exit;
		// $b = $this->input->get ( 'b' );
		// $e = $this->input->get ( 'e' );
		// $in_sql="SELECT group_concat(hotel_id) insh FROM `iwide_hotels` where inter_id='a441098524' LIMIT $b,$e";
		// $hotel_in=$this->db->query($in_sql)->row_array();
		// $hotel_in=substr($hotel_in['insh'],0,strlen($hotel_in['insh'])-1);
		$sql = "select * from iwide_hotels where inter_id = '$inter_id'";
		$old_box = $this->db->query ( $sql )->result_array ();
		$prefix = 'http://file.iwide.cn/public/uploads/201512/';
		$img = array (
				'inter_id' => $inter_id,
				'info' => 'intro',
				'type' => 'hotel_lightbox',
				'room_id' => 0 
		);
		$imgs = array ();
		foreach ( $old_box as $od ) {
			$rnd = array_rand ( $hotel_light_box, 3 );
			$img ['hotel_id'] = $od ['hotel_id'];
			foreach ( $rnd as $r ) {
				$img ['image_url'] = $prefix . $hotel_light_box [$r];
				$imgs [] = $img;
			}
		}
		$this->db->insert_batch ( 'hotel_images', $imgs );
		echo 'ok';
	}
	function update_room_new_img() {
		$inter_id = 'a441098524';
		$single = 'yibohotel_27-2.jpg,yibohotel_15-2.jpg,yibohotel_27-2.jpg,yibohotel_39-2.jpg,yibohotel_50-2.jpg,yibohotel_110-3.jpg,yibohotel_212-2.jpg,yibohotel_259-2.jpg,yibohotel_290-1.jpg,yibohotel_322-3.jpg';
		$single = explode ( ',', $single );
		$double = 'yibohotel_11-2.jpg,yibohotel_12-2.jpg,yibohotel_15-3.jpg,yibohotel_19-2.jpg,yibohotel_37-2.jpg,yibohotel_149-2.jpg,yibohotel_174-2.jpg,yibohotel_186-2.jpg,yibohotel_213-2.jpg,yibohotel_228-2.jpg,yibohotel_241-2.jpg,yibohotel_304-3.jpg';
		$double = explode ( ',', $double );
		// $b=$this->input->get('b');
		// $e=$this->input->get('e');
		// $in_sql="SELECT group_concat(hotel_id) insh FROM `iwide_hotels` where inter_id='a441098524' LIMIT $b,$e";
		// $hotel_in=$this->db->query($in_sql)->row_array();
		// $hotel_in=substr($hotel_in['insh'],0,strlen($hotel_in['insh'])-1);
		$sql = "select * from iwide_hotel_rooms where inter_id = '$inter_id'";
		$old_box = $this->db->query ( $sql )->result_array ();
		$prefix = 'http://file.iwide.cn/public/uploads/201512/';
		foreach ( $old_box as $od ) {
			if (strpos ( $od ['name'], '双' ) !== false) {
				$rnd = mt_rand ( 0, 11 );
				$img ['room_img'] = $prefix . $double [$rnd];
				$this->db->where ( 'room_id', $od ['room_id'] );
				$this->db->update ( 'hotel_rooms', $img );
			} else {
				$rnd = mt_rand ( 0, 9 );
				$img ['room_img'] = $prefix . $single [$rnd];
				$this->db->where ( 'room_id', $od ['room_id'] );
				$this->db->update ( 'hotel_rooms', $img );
			}
		}
		echo 'ok';
	}
	function update_room_new_intro_img() {
		$inter_id = 'a441098524';
		$single = 'yibohotel_147-1.jpg,yibohotel_177-3.jpg,yibohotel_226-2.jpg,yibohotel_233-1.jpg,yibohotel_276-2.jpg,yibohotel_305-2.jpg,yibohotel_198-3.jpg,yibohotel_235-1.jpg,yibohotel_200-2.jpg,yibohotel_120-1.jpg';
		$single = explode ( ',', $single );
		$double = 'yibohotel_2-3.jpg,yibohotel_18-1.jpg,yibohotel_30-1.jpg,yibohotel_30-3.jpg,yibohotel_122-1.jpg,yibohotel_136-1.jpg,yibohotel_139-3.jpg,yibohotel_198-2.jpg,yibohotel_219-3.jpg,yibohotel_220-3.jpg';
		$double = explode ( ',', $double );
		$sql = "select * from iwide_hotel_rooms where inter_id = '$inter_id'";
		$old_box = $this->db->query ( $sql )->result_array ();
		$prefix = 'http://file.iwide.cn/public/uploads/201512/';
		$img = array (
				'inter_id' => $inter_id,
				'info' => 'intro',
				'type' => 'hotel_room_lightbox' 
		);
		$imgs = array ();
		foreach ( $old_box as $od ) {
			$img ['hotel_id'] = $od ['hotel_id'];
			$img ['room_id'] = $od ['room_id'];
			if (strpos ( $od ['name'], '双' ) !== false) {
				$rnd = array_rand ( $double, 3 );
				foreach ( $rnd as $r ) {
					$img ['image_url'] = $prefix . $double [$r];
					$imgs [] = $img;
					// $this->db->insert ( 'hotel_images', $img );
				}
			} else {
				$rnd = array_rand ( $single, 3 );
				foreach ( $rnd as $r ) {
					$img ['image_url'] = $prefix . $single [$r];
					$imgs [] = $img;
					// $this->db->insert ( 'hotel_images', $img );
				}
			}
		}
		$this->db->insert_batch ( 'hotel_images', $imgs );
		echo 'ok';
	}
	function add_hotel_service() {
		$inter_id = 'a441098524';
		$sql = "select * from iwide_hotels where inter_id = '$inter_id'";
		$old_box = $this->db->query ( $sql )->result_array ();
		$img1 = array (
				'inter_id' => $inter_id,
				'info' => 'Wifi',
				'image_url' => '&#xe8;',
				'type' => 'hotel_service',
				'room_id' => '0' 
		);
		$img2 = array (
				'inter_id' => $inter_id,
				'info' => '电吹风',
				'image_url' => '&#xe4;',
				'type' => 'hotel_service',
				'room_id' => '0' 
		);
		$img3 = array (
				'inter_id' => $inter_id,
				'info' => '停车',
				'image_url' => '&#xe7;',
				'type' => 'hotel_service',
				'room_id' => '0' 
		);
		$img4 = array (
				'inter_id' => $inter_id,
				'info' => '寄存',
				'image_url' => '&#xe9;',
				'type' => 'hotel_service',
				'room_id' => '0' 
		);
		$img5 = array (
				'inter_id' => $inter_id,
				'info' => '热水',
				'image_url' => '&#xeb;',
				'type' => 'hotel_service',
				'room_id' => '0' 
		);
		foreach ( $old_box as $od ) {
			$img1 ['hotel_id'] = $od ['hotel_id'];
			$img2 ['hotel_id'] = $od ['hotel_id'];
			$img3 ['hotel_id'] = $od ['hotel_id'];
			$img4 ['hotel_id'] = $od ['hotel_id'];
			$img5 ['hotel_id'] = $od ['hotel_id'];
			$this->db->insert ( 'hotel_images', $img1 );
			$this->db->insert ( 'hotel_images', $img2 );
			$this->db->insert ( 'hotel_images', $img3 );
			$this->db->insert ( 'hotel_images', $img4 );
			$this->db->insert ( 'hotel_images', $img5 );
		}
		echo 'ok';
	}
	function add_hotel_room_service() {
		$inter_id = 'a441098524';
		$sql = "select * from iwide_hotel_rooms where inter_id = '$inter_id'";
		$old_box = $this->db->query ( $sql )->result_array ();
		$img1 = array (
				'inter_id' => $inter_id,
				'info' => 'Wifi',
				'image_url' => '&#xe8;',
				'type' => 'hotel_room_service' 
		);
		$img2 = array (
				'inter_id' => $inter_id,
				'info' => '电吹风',
				'image_url' => '&#xe4;',
				'type' => 'hotel_room_service' 
		);
		$img5 = array (
				'inter_id' => $inter_id,
				'info' => '热水',
				'image_url' => '&#xeb;',
				'type' => 'hotel_room_service' 
		);
		$imgs = array ();
		foreach ( $old_box as $od ) {
			$img1 ['hotel_id'] = $od ['hotel_id'];
			$img1 ['room_id'] = $od ['room_id'];
			$img2 ['hotel_id'] = $od ['hotel_id'];
			$img2 ['room_id'] = $od ['room_id'];
			$img5 ['hotel_id'] = $od ['hotel_id'];
			$img5 ['room_id'] = $od ['room_id'];
			$imgs [] = $img1;
			$imgs [] = $img2;
			$imgs [] = $img5;
			// $this->db->insert ( 'hotel_images', $img1 );
			// $this->db->insert ( 'hotel_images', $img2 );
			// $this->db->insert ( 'hotel_images', $img5 );
		}
		$this->db->insert_batch ( 'hotel_images', $imgs );
		echo 'ok';
	}
	function trans_order() {exit;
		$inter_id = 'a440577876';
		$sql = 'SELECT * FROM `iwide_tmp_hotels_order` t join iwide_tmp_hotels_webser_order o on t.orderid=o.orderid';
		$old_order = $this->db->query ( $sql )->result_array ();
		$this->db->where ( array (
				'inter_id' => $inter_id 
		) );
		$rooms = $this->db->get ( 'hotel_rooms' )->result_array ();
		$room_names = array ();
		foreach ( $rooms as $r ) {
			$room_names [$r ['room_id']] = $r;
		}
		// var_dump($old_order);
		// exit;
		$orders = array ();
		$order_additions = array ();
		$order_items = array ();
		$tmp_order = array (
				'inter_id' => $inter_id 
		);
		$tmp_addition = array (
				'inter_id' => $inter_id 
		);
		$tmp_item = array (
				'inter_id' => $inter_id 
		);
		$paytype = array (
				'0' => 'daofu',
				'1' => 'weixin' 
		);
		$code_name = array (
				'WXHYJ' => '品悦银卡',
				'WXSKJ' => '微会员' 
		);
		foreach ( $old_order as $oo ) {
			$tmp_order ['hotel_id'] = $oo ['hotel_id'];
			$tmp_order ['openid'] = $oo ['openid'];
			$tmp_order ['price'] = $oo ['price'];
			$tmp_order ['roomnums'] = $oo ['roomnums'];
			$tmp_order ['name'] = $oo ['name'];
			$tmp_order ['tel'] = $oo ['tel'];
			$tmp_order ['order_time'] = strtotime ( $oo ['time'] );
			$tmp_order ['startdate'] = $oo ['startdate'];
			$tmp_order ['enddate'] = $oo ['enddate'];
			$tmp_order ['paid'] = $oo ['paid'];
			$tmp_order ['orderid'] = $oo ['orderid'];
			$tmp_order ['status'] = $oo ['status'];
			$tmp_order ['holdtime'] = $oo ['holdtime'];
			$tmp_order ['paytype'] = $paytype [$oo ['paytype']];
			$tmp_order ['isdel'] = $oo ['isdel'];
			$tmp_order ['operate_reason'] = $oo ['operate_reason'];
			$tmp_order ['member_no'] = '';
			$tmp_order ['handled'] = $oo ['handled'];
			$tmp_order ['isdel'] = $oo ['isdel'];
			$orders [] = $tmp_order;
			
			$tmp_addition ['orderid'] = $oo ['orderid'];
			$tmp_addition ['web_orderid'] = $oo ['web_orderid'];
			// $tmp_addition ['web_paid'] = $oo ['web_paid'];
			$tmp_addition ['coupon_favour'] = intval ( $oo ['vouchertotal'] );
			$tmp_addition ['coupon_used'] = intval ( $oo ['voucherused'] );
			$order_additions [] = $tmp_addition;
			
			$iprice = intval ( $oo ['price'] / $oo ['roomnums'] );
			$change = $oo ['price'] - ($iprice * $oo ['roomnums']);
			$p_check = 0;
			for($i = 0; $i < $oo ['roomnums']; $i ++) {
				$tmp_item ['orderid'] = $oo ['orderid'];
				$tmp_item ['room_id'] = $oo ['room_id'];
				if ($p_check == 0) {
					$tmp_item ['iprice'] = $iprice + $change;
					$p_check = 1;
				} else {
					$tmp_item ['iprice'] = $iprice;
				}
				$tmp_item ['startdate'] = $oo ['startdate'];
				$tmp_item ['enddate'] = $oo ['enddate'];
				$tmp_item ['istatus'] = $oo ['status'];
				$tmp_item ['price_code'] = $oo ['channel_cd'];
				$tmp_item ['price_code_name'] = $code_name [$oo ['channel_cd']];
				$tmp_item ['handled'] = $oo ['handled'];
				// $tmp_item ['webs_orderid'] = $oo ['web_orderid'];
				$tmp_item ['allprice'] = str_replace ( '+', ',', $oo ['allprice'] );
				$tmp_item ['roomname'] = $room_names [$oo ['room_id']] ['name'];
				$order_items [] = $tmp_item;
			}
		}
		// var_dump ( $orders );
		// var_dump ( $order_additions );
		// var_dump ( $order_items );
		// exit ();
		$this->db->insert_batch ( 'hotel_orders', $orders );
		$this->db->insert_batch ( 'hotel_order_additions', $order_additions );
		$this->db->insert_batch ( 'hotel_order_items', $order_items );
	}
	function create_member_coupon() {
		$inter_id = 'a440577876';
		$now = date ( 'Y-m-d H:i:s' );
		$sql = "SELECT * FROM `iwide_tmp_hotels_vouchers` WHERE validtime > '$now' and `status` = 0 and openid not like '0' and inter_id='$inter_id' ";
		$coupons = $this->db->query ( $sql )->result_array ();
		$this->load->model ( 'hotel/Member_model' );
		$this->load->model ( 'hotel/Coupon_model' );
		$coupon_id = 23;
		$scene = array (
				'1' => 'hotel_order_complete',
				'2' => 'hotel_order_share' 
		);
		$extra_data = array (
				'module' => 'hotel' 
		);
		$this->load->model ( 'member/Igetcard' );
		$openids = array ();
		foreach ( $coupons as $c ) {
			$member = $this->Member_model->check_openid_member ( $inter_id, $c ['openid'], array (
					'create' => TRUE 
			) );
			if (isset ( $member->mem_id )) {
				$extra_data ['scene'] = $scene [$c ['type']];
				$extra_data ['scene_id'] = $c ['fromorderid'];
				$this->Igetcard->userGetCard ( $c ['openid'], $inter_id, array (
						$coupon_id => 1 
				), 1, $extra_data );
				// var_dump ( $this->Coupon_model->add_user_card ( $inter_id, $c ['openid'], $coupon_id, $c ['c_num'], 'give', $extra_data ) );
			}
		}
		echo 'ok';
	}
	function pay_tesy() {
		$this->load->model ( 'hotel/Order_model' );
		$this->Order_model->pay_return ( 'GI148793453564691' );
	}
	function update_tmp_room() {
		$sql = 'SELECT * FROM `iwide_tmp_hotel_rooms`';
		$rooms = $this->db->query ( $sql )->result_array ();
		foreach ( $rooms as $r ) {
			$this->db->where ( array (
					'room_id' => $r ['room_id'],
					'hotel_id' => $r ['hotel_id'],
					'inter_id' => $r ['inter_id'] 
			) );
			$this->db->update ( 'hotel_rooms', array (
					'room_img' => $r ['room_img'] 
			) );
		}
	}
	function add_zhuzhe_hotel() {
		$inter_id = 'a445223616';
		$sql = "SELECT * FROM `iwide_tmp_hotels` where inter_id='$inter_id' ";
		$old_hotels = $this->db->query ( $sql )->result_array ();
		$data = array (
				'inter_id' => $inter_id 
		);
		$addition = array (
				'inter_id' => $inter_id,
				'pms_type' => 'zhuzhe',
				'pms_room_state_way' => 3,
				'pms_member_way' => 2 
		);
		$hotel_additions = array ();
		$hotel_count = 0;
		$addition_count = 0;
		$hotel_id = 343;
		foreach ( $old_hotels as $oh ) {
			$data ['latitude'] = $oh ['longitude'];
			$data ['longitude'] = $oh ['latitude'];
			$data ['address'] = $oh ['address'];
			$data ['tel'] = $oh ['tel'];
			$data ['name'] = $oh ['name'];
			$data ['hotel_id'] = $hotel_id;
			$data ['intro'] = $oh ['intro'];
			$data ['email'] = $oh ['email'];
			$data ['fax'] = $oh ['fax'];
			$data ['star'] = $oh ['star'];
			$data ['country'] = $oh ['country'];
			$data ['province'] = $oh ['province'];
			$data ['web'] = '';
			$data ['city'] = $oh ['city'];
			$data ['intro_img'] = $oh ['intro_img'];
			$data ['status'] = $oh ['status'] == 0 ? 1 : 2;
			$this->db->insert ( 'hotels', $data );
			$hotel_count ++;
			$addition ['hotel_id'] = $hotel_id;
			$addition ['hotel_web_id'] = $oh ['webser_id'];
			
			$this->db->insert ( 'hotel_additions', $addition );
			$addition_count ++;
			$hotel_id ++;
			
			// var_dump($addition);
			// var_dump($data);
		}
		echo 'hotel:' . $hotel_count . '<br />';
		echo 'addition:' . $addition_count . '<br />';
	}
	function grab_zhuzhe_room() {
		$days = 1;
		$inter_id = 'a445223616';
		$sql = "SELECT h.*,a.hotel_web_id FROM `iwide_hotels` h 
					join iwide_hotel_additions a on h.inter_id=a.inter_id and a.hotel_id=h.hotel_id WHERE h.`inter_id` LIKE '$inter_id'";
		$hotels = $this->db->query ( $sql )->result_array ();
		$this->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'pms' );
		$in_rooms ['inter_id'] = $inter_id;
		$in_rooms ['status'] = "1";
		$room_id = 2067;
		foreach ( $hotels as $h ) {
			$hotelretdata = $this->pms->qfcurl ( $this->pms->path ( '/hotel/' . $h ['hotel_web_id'] . '/' . date ( 'Y-m-d' ) . '/' . date ( 'Y-m-d', strtotime ( '+ 1 day', time () ) ) ) );
			preg_match_all ( "/\<houseTypeList\>(.*?)\<\/houseTypeList\>/", $hotelretdata, $hotelretdataarr );
			$allprice = array ();
			$qfrooms = array ();
			if (isset ( $hotelretdataarr [0] )) {
				$hotelretxml = $hotelretdataarr [0] [0];
				$xmlobj = simplexml_load_string ( $hotelretxml );
				foreach ( $xmlobj->houseType as $key => $value ) {
					$houseTypeId = intval ( $value->houseTypeId );
					$nums = intval ( $value->available );
					$price = $value->price;
					
					$name = $value->houseTypeName;
					
					$in_rooms ['hotel_id'] = $h ['hotel_id'];
					$in_rooms ['room_id'] = $room_id;
					$in_rooms ['name'] = "$name";
					$in_rooms ['price'] = "$price";
					$in_rooms ['oprice'] = "$price";
					$in_rooms ['nums'] = "$nums";
					$in_rooms ['webser_id'] = "$houseTypeId";
					// $rooms[]=$in_rooms;
					$this->db->insert ( 'hotel_rooms', $in_rooms );
					$room_id ++;
				}
			}
		}
		// var_dump($rooms);
		echo 'ok';
	}
	function zhuzhe_img() {
		$inter_id = 'a445223616';
		$sql = "SELECT h.*,a.hotel_web_id FROM `iwide_hotels` h join iwide_hotel_additions a on h.hotel_id=a.hotel_id and h.inter_id=a.inter_id WHERE h.`inter_id` LIKE '$inter_id'";
		$old_hotels = $this->db->query ( $sql )->result_array ();
		$img_id = 15262;
		$prefix = 'http://file.iwide.cn/public/uploads/201601/';
		$img = array (
				'inter_id' => $inter_id,
				'info' => 'intro',
				'type' => 'hotel_lightbox',
				'room_id' => 0 
		);
		$imgs = array ();
		foreach ( $old_hotels as $od ) {
			$img ['hotel_id'] = $od ['hotel_id'];
			for($i = 1; $i <= 3; $i ++) {
				$img ['id'] = $img_id;
				$img ['image_url'] = $prefix . 'zhuzhehlb_' . $od ['hotel_web_id'] . '-' . $i . '.jpg';
				// echo '<img src="'.$img ['image_url'].'" />';
				$imgs [] = $img;
				$img_id ++;
			}
		}
		
		$room_sql = "SELECT * FROM `iwide_hotel_rooms` WHERE `inter_id` LIKE '$inter_id'";
		$old_rooms = $this->db->query ( $room_sql )->result_array ();
		$rl_img = array (
				'inter_id' => $inter_id,
				'info' => 'intro',
				'type' => 'hotel_room_lightbox' 
		);
		foreach ( $old_rooms as $od ) {
			$rl_img ['hotel_id'] = $od ['hotel_id'];
			$rl_img ['room_id'] = $od ['room_id'];
			$rl_img ['id'] = $img_id;
			$rl_img ['image_url'] = $prefix . 'zhuzhehri_' . $od ['webser_id'] . '.jpg';
			$imgs [] = $rl_img;
			$img_id ++;
			$this->db->where ( array (
					'inter_id' => $inter_id,
					'room_id' => $od ['room_id'] 
			) );
			$this->db->update ( 'hotel_rooms', array (
					'room_img' => $prefix . 'zhuzhehrlb_' . $od ['webser_id'] . '-1.jpg' 
			) );
			// echo $this->db->last_query();
			// echo ';';
			// echo '<img src="'.$prefix .'zhuzhehrlb_'.$od['webser_id'].'-1.jpg'.'">';
			// echo '<img src="'.$prefix .'zhuzhehri_'.$od['webser_id'].'.jpg'.'">';
		}
		
		// exit;
		// $this->db->insert_batch ( 'hotel_images', $imgs );
		echo 'ok';
	}
	function yuanzhouhotel_trans() {
		$inter_id = 'a440577876';
		$sql = "SELECT * FROM `iwide_tmp_hotels` where inter_id='$inter_id' ";
		$old_hotels = $this->db->query ( $sql )->result_array ();
		
		$sql = "SELECT * FROM `iwide_tmp_hotels_rooms`  where inter_id='$inter_id'";
		$old_rooms = $this->db->query ( $sql )->result_array ();
		
		$data = array (
				'inter_id' => $inter_id 
		);
		$addition = array (
				'inter_id' => $inter_id,
				'pms_type' => 'yuanzhou',
				'pms_room_state_way' => 2,
				'pms_member_way' => 2 
		);
		$hotel_additions = array ();
		$hotel_count = 0;
		$addition_count = 0;
		foreach ( $old_hotels as $oh ) {
			$data ['latitude'] = $oh ['longitude'];
			$data ['longitude'] = $oh ['latitude'];
			$data ['address'] = $oh ['address'];
			$data ['tel'] = $oh ['tel'];
			$data ['name'] = $oh ['name'];
			$data ['hotel_id'] = $oh ['hotel_id'];
			$data ['intro'] = $oh ['intro'];
			$data ['email'] = $oh ['email'];
			$data ['fax'] = $oh ['fax'];
			$data ['star'] = $oh ['star'];
			$data ['country'] = $oh ['country'];
			$data ['province'] = $oh ['province'];
			$data ['web'] = '';
			$data ['city'] = $oh ['city'];
			$data ['intro_img'] = $oh ['intro_img'];
			$data ['status'] = $oh ['status'] == 0 ? 1 : 2;
			// $this->db->insert ( 'hotels', $data );
			$hotel_count ++;
			
			$addition ['hotel_id'] = $oh ['hotel_id'];
			$addition ['hotel_web_id'] = $oh ['webser_id'];
			
			// $this->db->insert ( 'hotel_additions', $addition );
			$addition_count ++;
			
			var_dump ( $addition );
			var_dump ( $data );
		}
		echo 'hotel:' . $hotel_count . '<br />';
		echo 'addition:' . $addition_count . '<br />';
	}
	function yuanzhouroom_trans() {
		exit ();
		$inter_id = 'a440577876';
		$sql = "SELECT * FROM `iwide_tmp_hotels_rooms`  where inter_id='$inter_id'";
		$old_rooms = $this->db->query ( $sql )->result_array ();
		$room = array (
				'inter_id' => $inter_id 
		);
		$room_count = 0;
		foreach ( $old_rooms as $hr ) {
			$room ['hotel_id'] = $hr ['hotel_id'];
			$room ['name'] = $hr ['name'];
			$room ['room_id'] = $hr ['room_id'];
			$room ['price'] = $hr ['price'];
			$room ['oprice'] = $hr ['vprice'];
			$room ['description'] = $hr ['description'];
			$room ['nums'] = $hr ['nums'];
			$room ['bed_num'] = $hr ['bed_num'];
			$room ['area'] = $hr ['area'];
			$room ['status'] = $hr ['status'] == 0 ? 1 : 2;
			$room ['webser_id'] = $hr ['webser_id'];
			$room ['sort'] = $hr ['sort'];
			$this->db->insert ( 'hotel_rooms', $room );
			// var_dump($room);
			$room_count ++;
		}
		echo 'room:' . $room_count . '<br />';
	}
	function yuanzhou_img() {
		$inter_id = 'a440577876';
		
		$room_sql = "SELECT * FROM `iwide_hotel_rooms` WHERE `inter_id` LIKE '$inter_id'";
		$old_rooms = $this->db->query ( $room_sql )->result_array ();
		$rl_img = array (
				'inter_id' => $inter_id,
				'info' => 'intro',
				'type' => 'hotel_room_lightbox' 
		);
		$img_id = 15367;
		$prefix = 'http://file.iwide.cn/public/uploads/201601/';
		foreach ( $old_rooms as $od ) {
			$rl_img ['hotel_id'] = $od ['hotel_id'];
			$rl_img ['room_id'] = $od ['room_id'];
			$rl_img ['id'] = $img_id;
			$rl_img ['image_url'] = $prefix . 'yuanzhouhrlb_' . $od ['room_id'] . '-1.jpg';
			$imgs [] = $rl_img;
			$img_id ++;
			$this->db->where ( array (
					'inter_id' => $inter_id,
					'room_id' => $od ['room_id'] 
			) );
			$this->db->update ( 'hotel_rooms', array (
					'room_img' => $prefix . 'yuanzhouhri_' . $od ['room_id'] . '.jpg' 
			) );
			echo $this->db->last_query ();
			echo ';';
			// echo '<img src="'.$prefix .'yuanzhouhrlb_'.$od['room_id'].'-1.jpg'.'">';
			// echo '<img src="'.$prefix .'yuanzhouhri_'.$od['room_id'].'.jpg'.'">';
		}
		
		// exit;
		$this->db->insert_batch ( 'hotel_images', $imgs );
		echo 'ok';
	}
	
	function add_huayi_price_code(){
		$inter_id = 'a445223616';
		$sql="SELECT * FROM `iwide_hotel_rooms` WHERE `inter_id` LIKE '$inter_id' AND `hotel_id` > 599 and hotel_id <645";
		$rooms=$this->db->query($sql)->result_array();
		$code_sql="SELECT * FROM `iwide_hotel_price_set` WHERE `inter_id` LIKE '$inter_id' group by price_code";
		$codes=$this->db->query($code_sql)->result_array();
		foreach($rooms as $r){
			foreach($codes as $c){
				$c['room_id']=$r['room_id'];
				$c['hotel_id']=$r['hotel_id'];
				$this->db->insert('hotel_price_set',$c);
			}
		}
	}
	function yuanzhou_hy(){
		$this->load->model ( 'common/Webservice_model' );
		$level_reflect = $this->Webservice_model->get_web_reflect ( 'a440577876', 0, 'yuanzhou',
				'member_level',
				1, 'w2l' );
		$pms_level='pyyk';
		echo $level_reflect[$pms_level];
		var_dump($level_reflect);
	}
	function jieding_hotel(){exit;
		$this->load->model('hotel/pms/Zhuzhe_hotel_model');
		$hotels=$this->Zhuzhe_hotel_model->get_web_hotels('jieting_83383_20160106_web:admin'); 
		$hotels=json_decode(json_encode($hotels),TRUE);
		$inter_id = 'a441624001';
		
		$data = array (
				'inter_id' => $inter_id
		);
		$addition = array (
				'inter_id' => $inter_id,
				'pms_type' => 'zhuzhe',
				'pms_room_state_way' => 1,
				'pms_member_way' => 1
		);
		$hotel_additions = array ();
		$hotel_count = 0;
		$addition_count = 0;
		$hotel_id=678;
		foreach ( $hotels as $oh ) {
			$data ['latitude'] = isset($oh ['latitude'])?$oh ['latitude']:0;
			$data ['longitude'] = isset($oh ['longitude'])?$oh ['longitude']:0;
			$data ['address'] = $oh ['addres'];
			$data ['tel'] = empty($oh ['htel'])?'':$oh ['htel'];
			$data ['name'] = $oh ['name'];
			$data ['hotel_id'] =$hotel_id;
			$data ['intro'] = empty($oh ['detail'])?'':$oh ['detail'];
			$data ['email'] = empty($oh ['email'][0])?'':$oh ['email'][0];
			$data ['fax'] = empty($oh ['hfax'][0])?'':$oh ['hfax'][0];
// 			$data ['star'] = $oh ['star'];
// 			$data ['country'] = $oh ['country'];
// 			$data ['province'] = $oh ['province'];
			$data ['web'] = '';
			$data ['city'] = $oh ['city'];
			$data ['intro_img'] = '';
// 			$data ['status'] = $oh ['status'] == 0 ? 1 : 2;
// 			$this->db->insert ( 'hotels', $data );
			$hotel_count ++;
			
				
			$addition ['hotel_id'] = $hotel_id;
			$addition ['hotel_web_id'] = $oh ['id'];
				
// 			$this->db->insert ( 'hotel_additions', $addition );
			$addition_count ++;
			$hotel_id++;
// 			var_dump ( $addition );
// 			var_dump ( $data );
		}
		echo $hotel_count;
		echo $addition_count;
	}
	function jieding_room(){
		// 			$room=array('inter_id'=>'a441624001');
		// 			$sql="SELECT count(room_id)+2327 rid FROM `iwide_hotel_rooms` WHERE `inter_id` LIKE 'a441624001'";
		// 			$count=$this->db->query($sql)->row_array();
		// 			$room_id=$count['rid'];
		// 			echo $room_id;
		// 			foreach($pms_data['houseType'] as $house){
		// 				$room ['hotel_id'] = $pms_set ['hotel_id'];
		// 				$room ['name'] = $house ['houseTypeName'];
		// 				$room ['room_id'] = $room_id;
		// 				$room ['price'] = $house ['price'];
		// // 				$room ['oprice'] = $hr ['vprice'];
		// 				$room ['description'] = empty($house ['description'])?'':$house ['description'];
		// 				$room ['nums'] = $house ['available'];
		// // 				$room ['bed_num'] = $hr ['bed_num'];
		// // 				$room ['area'] = $hr ['area'];
		// // 				$room ['status'] = $hr ['status'] == 0 ? 1 : 2;
		// 				$room ['webser_id'] = $house ['houseTypeId'];
		// // 				$room ['sort'] = $hr ['sort'];
		// 				$this->db->insert ( 'hotel_rooms', $room );
		// 				$room_id++;
		// 			}
		// 			echo $room_id;
		// 			exit;
	}
    function daili() { 
		error_reporting(-1);
		ini_set ( 'display_errors', 0 );
		$t=$this->input->get('t');
		$this->load->helper('common');
		$user_IP = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
		$user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
		$this->db->insert('weixin_text',array('content'=>$t.'-'.$user_IP,
				'edit_date'=>date('Y-m-d H:i:s')));
		sleep(15);
		$result=$this->db->query("select * from iwide_weixin_text where content like '$t-%' and id >13774 order by id desc limit 100")->result_array();
		foreach ($result as $r){
			echo $r['content'].'-'.$r['edit_date'].'<br />';
		}
		echo count($result).'条访问记录';
	}
    function stime(){
		echo time();
	}
	function check_order_canpay()
	{
		$orderid = $this->input->get ( 'o' );
		$this->db->from ( 'iwide_hotel_orders' );
		$this->db->where ( array (
				'orderid' => $orderid 
		) );
		$row = $this->db->get ()->row_array ();
		if(empty($row)){
			echo '没有这个订单!';exit;
		}
		$inter_id = $row['inter_id'];
		$hotel_id = $row['hotel_id'];
		$oid = $row['id'];
		$member_no = $row['member_no'];
		$openid = $row['openid'];

		$this->load->library ( 'PMS_Adapter', array (
			'inter_id' => $inter_id,
			'hotel_id' => $hotel_id
		), 'pmsa' );
		$this->load->model ( 'hotel/Order_model' );
		$list = $this->Order_model->get_main_order ( $inter_id, array (
			'oid' => $oid,
			'openid' => $openid,
			'member_no' => $member_no,
			'idetail' => array (
				'i'
			)
		) );
		if ($list) {
			$list = $list [0];
			$a = $this->pmsa->check_order_canpay ( $list );
			echo '<pre>';
			var_dump($a);
		}else{
			echo '查找不到这个单';
		}
		echo '<br>'.'完成';
	}
     function testproxy(){
    	$this->load->library('Cache/Redis_proxy',array(
    			'not_init'=>FALSE,
    			'module'=>'common',
    			'refresh'=>FALSE,
    			'environment'=>ENVIRONMENT
    	),'redis_proxy');
    	$proxy=$this->redis_proxy->set('a','23');
    	var_dump($this->redis_proxy->get('a'));
    }
    function testbgyproxy(){
    	$this->load->model("hotel/pms/Zhongruan_hotel_model",'pms');
    	var_dump($this->pms->get_log_accesstoken(array('inter_id'=>'a421641095','pms_auth'=>array("log_url"=>"http://h5.bgyhotel.com:9090","log_user"=>"xyservice","log_pwd"=>"xyservice^2017")),$_GET['r']));
    }
    
    function smart_price_tmpmsg_test(){
		$info['inter_id'] = 'a426755343';
		$info['hotel_id'] = 1;
		$info['batch'] = 7;
		$info['openid'] = 'oBWOYv9KTD1oFankHjNEaI0qsy4A';
		$info['hotel'] = '佳园连锁酒店';
		$info['warn_type'] = !empty($this->input->get('w'))?$this->input->get('w'):'down';
		$info['remark_type'] = !empty($this->input->get('t'))?$this->input->get('t'):'down';
		$tmp_type = !empty($this->input->get('type'))?$this->input->get('type'):'smart_price_notice';
		$info['warndate'] = date('Y-m-d H:i:s');
		$info['starttime'] = date('Y-m-d H:i:s');
		$info['endtime'] = date('Y-m-d H:i:s');
		$this->load->model('plugins/template_msg_model');
		$res = $this->template_msg_model->send_smart_price_msg('a426755343',$info,$tmp_type);
		var_dump($res);
    }
    
    function test_pub(){
        $this->load->model('wx/access_token_model');
        $app_info = $this->access_token_model->get_redis_key_status ( 'a499938809_AUTH_INFO' );
        var_dump($app_info);
    }
}