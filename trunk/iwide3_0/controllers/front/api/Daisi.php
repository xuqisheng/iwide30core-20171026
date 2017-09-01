<?php

class Daisi extends MY_Controller{

	private $inter_id = 'a472800181';
	private $cfg;

	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
		//测试专用
		/*$this->cfg = array(
			'inter_id'      => 'a472800181',
			'url'           => 'https://crs.daysinn.cn:4433/kws_www_train/',
			'user'          => 'KWSTEST',
			'pwd'           => 'G45H39',
			'channel_code'  => 'WEB',
			'market_code'   => 'BAR',
			'source_code'   => 'WEB',
			'member_source' => 'WECHAT'
		);*/
		//生产
		$this->cfg = array(
			'inter_id'      => $this->inter_id,
			'url'           => 'https://crs.daysinn.cn:4433/kws_wxc_grk/',
			'user'          => 'GRK',
			'pwd'           => 'xyB36cQ4',
			'channel_code'  => 'WEB',
			'market_code'   => 'BAR',
			'source_code'   => 'WEB',
			'member_source' => 'WECHAT'
		);
		$this->load->library('Baseapi/Shijiapi', $this->cfg, 'serv_api');
	}

	public function fixadditions(){
		$conf = $this->cfg;
		$data = [
			'pms_auth'           => json_encode($conf),
			'pms_room_state_way' => 4,
		];
		unset($conf['inter_id']);
		$this->db->update('hotel_additions', $data, ['inter_id' => $this->inter_id]);
		echo 'success';
	}

	public function hotels(){
		set_time_limit(0);
		$hotels = $this->serv_api->getHotels();
		$list = array();
		foreach($hotels as $v){
			$v['room_list'] = $this->serv_api->getHotelRoomType($v['Code']);
			$list[] = $v;
		}
		print_r($list);
//		echo json_encode($list);
		return $list;
//		file_put_contents(FD_PUBLIC . '/daishi_hotels.json', json_encode($list));
//		echo 'success';
	}

	public function catch_hotels(){
		set_time_limit(900);
//		$json = file_get_contents(FD_PUBLIC . '/daishi_hotels.json');
//		$hotel_list = json_decode($json, true);
		$hotel_list=$this->hotels();
		$this->load->model('hotel/pms/Shiji_hotel_model', 'pms');
		$db = $this->pms->_shard_db();

		$pms_auth_arr = $this->cfg;
		unset($pms_auth_arr['inter_id']);

		$additions = [];
		$room_icons = [];

		$icon_arr = [
			36 => '&#xe4;',
			72 => '&#xe3;',
		];

		foreach($hotel_list as $v){
			$add_arr = explode('|', $v['Address']);
			$province_arr = explode('|', $v['Province']['name']);
			$city_arr = explode('|', $v['CityName']);
			$params = array(
				'inter_id'    => $this->inter_id,
				'name'        => $v['Name'],
				'address'     => $add_arr[0],
				'tel'         => $v['Phone'],
				'email'       => $v['Email'],
				//				'latitude'    => '',
				//				'longitude'   => '',
				'intro'       => $v['Desc'],
				'short_intro' => $v['Desc'],
				//				'intro_img'   => $img,
				'fax'         => $v['Fax'],
				'star'        => $v['Stars'],
				'province'    => $province_arr[0],
				'city'        => $city_arr[0],
			);

			$db->set($params)->insert('hotels');
			$insert_id = $db->insert_id();

			$pms_auth = json_encode($pms_auth_arr);
			if(!$additions){
				$additions[] = array(
					'hotel_id'           => 0,
					'inter_id'           => $this->inter_id,
					'pms_type'           => 'shiji',
					'pms_auth'           => $pms_auth,
					'hotel_web_id'       => '',
					'pms_room_state_way' => 4,
					'pms_member_way'     => 1,
				);
			}

			$additions[] = array(
				'hotel_id'           => $insert_id,
				'inter_id'           => $this->inter_id,
				'pms_type'           => 'shiji',
				'pms_auth'           => $pms_auth,
				'hotel_web_id'       => $v['Code'],
				'pms_room_state_way' => 4,
				'pms_member_way'     => 1,
			);

			//房型
			if(!empty($v['room_list'])){
				is_array(current($v['room_list'])) or $v['room_list'] = array($v['room_list']);
				foreach($v['room_list'] as $t){
					$name_arr = explode('|', $t['name']);
					$des_arr = explode('|', $t['Des']);
					$rooms = array(
						'hotel_id'    => $insert_id,
						'inter_id'    => $this->inter_id,
						'name'        => $name_arr[0],
						'description' => $des_arr[0],
						'sub_des'     => '',
						'nums'        => 0,
						//						'bed_num'     => $t['numadults'],
						//						'sort'        => $t['Sort'],
						'webser_id'   => $t['RoomType']['code'],
						//						'room_img'    => $rimg,
						'area'        => $t['Area'],
					);
					$db->insert('hotel_rooms', $rooms);
					$room_id = $db->insert_id();

					if(!empty($t['HotelInstallations']['CommonInfo'])){
						is_array(current($t['HotelInstallations']['CommonInfo'])) or $t['HotelInstallations']['CommonInfo'] = array($t['HotelInstallations']['CommonInfo']);
						foreach($t['HotelInstallations']['CommonInfo'] as $w){
							if(!empty($icon_arr[$w['code']])){
								$icon_name_arr = explode('|', $w['name']);
								$room_icons[] = [
									'inter_id'  => $this->inter_id,
									'hotel_id'  => $insert_id,
									'room_id'   => $room_id,
									'image_url' => $icon_arr[$w['code']],
									'info'      => $icon_name_arr[0],
								];
							}

						}
					}
				}
			}
		}
		if($additions){
			$db->set_insert_batch($additions)->insert_batch('hotel_additions');
		}
		if($room_icons){
			$db->set_insert_batch($room_icons)->insert_batch('hotel_images');
		}
		/*if($rooms){
			$db->set_insert_batch($rooms)->insert_batch('hotel_rooms');
		}*/
		echo 'success';

	}




	public function get_lowest(){
		set_time_limit(0);
		$model_file='hotel/pms/Shiji_hotel_model';

		$this->load->model ( 'hotel/Hotel_model' );

		$startdate=date('Ymd');
		$enddate=date('Ymd',strtotime('+1 day',time()));

		$condit = array (
			'startdate' => $startdate,
			'enddate' => $enddate,
			'openid' => '',
			'member_level' => 0
		);

		$this->load->model ( 'hotel/Member_model' );
		$member_privilege = $this->Member_model->level_privilege ( $this->inter_id );
		if (! empty ( $member_privilege )) {
			$condit ['member_privilege'] = $member_privilege;
		}

		$hotels=$this->db->get_where('hotel_additions',['inter_id'=>$this->inter_id,'hotel_id>'=>0])->result_array();
		$this->load->model($model_file,'pms');

		$lowest_params=[];

		foreach($hotels as $v){
			$hotel_id=$v['hotel_id'];

			$rooms = $this->Hotel_model->get_hotel_rooms ( $this->inter_id, $hotel_id, 1 );
			if(!$rooms)
				continue;

			$pms_state=$this->pms->get_rooms_change ( $rooms, array (
				'inter_id' => $this->inter_id,
				'hotel_id' => $hotel_id
			), $condit, $v );

			$lowest_arr=[];
			foreach($pms_state as $t){
				if(!empty($t['lowest'])){
					$lowest = str_replace(',', '', $t['lowest']);
					if($lowest > 0){
						$lowest_arr[]=$lowest;
					}
				}
			}

			if($lowest_arr){
				$lowest_params[]=[
					'inter_id'=>$this->inter_id,
					'hotel_id'=>$hotel_id,
					'lowest_price' => min($lowest_arr),
					'update_time'  => date('Y-m-d H:i:s'),
				];
			}
		}

		if($lowest_params){
			echo json_encode($lowest_params);
		}
	}

	public function get_order($wid=null){
		$res=$this->serv_api->getOrder($wid);
		print_r($res);
	}
}