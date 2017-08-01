<?php

class Qingmu extends MY_Controller{
	private $inter_id;

	public function __construct(){
		parent::__construct();
		$this->inter_id = 'a464919542';
		$this->load->model('hotel/pms/Beyondh_hotel_model', 'pms');
		$this->load->helper('common');
	}

	public function check(){
		$hotel_id = $this->input->get('id');
		$db = $this->load->database('iwide_r1', true);
//		$db=$this->db;
		$row = $db->from('hotel_additions')->where(array(
			                                           'hotel_id' => (int)$hotel_id,
			                                           'inter_id' => $this->inter_id
		                                           ))->select('hotel_web_id')->get()->row_array();
		if(!$row){
			echo 'no data';
			exit;
		}

		$result = $this->pms->searchHotelById($row['hotel_web_id'], date('Y-m-d'), date('Y-m-d', time() + 86400), ['recache' => true], $this->inter_id);
		$list = obj2array($result);


		if(!empty($list[$row['hotel_web_id']]['RoomCounts']['RoomCount'])){
			$rooms = $list[$row['hotel_web_id']]['RoomCounts']['RoomCount'];
			$html = '<div style="text-align:center">';
			foreach($rooms as $v){
				$html .= '<p style="line-height:24px;">' . $v['RoomType']['RoomTypeName'] . '===>' . $v['RoomTypeId'] . '</p>';
			}
			$html .= '</div>';
			echo $html;
			exit;
		}
		echo 'NO DATA';

	}

	public function check_hour(){
		set_time_limit(1800);
		$db = $this->load->database('iwide_r1', true);

		$hotels = $db->from('hotels h')->join('hotel_additions ha', 'ha.hotel_id=h.hotel_id', 'inner')->select('h.name,h.hotel_id,ha.hotel_web_id')->where(['h.inter_id' => $this->inter_id])->get()->result_array();
		$html = '<table style="width:640px; text-align:center;" border="1">';
		$refresh=$this->input->get('refresh')?true:false;
		foreach($hotels as $v){
			$html .= '<tr><td colspan="3">' . $v['name'] . '</td></tr>';
			$hour_list = $this->pms->getHourPrice($v['hotel_web_id'], date('Y-m-d'), $this->inter_id,$refresh);
			is_array(current($hour_list)) or $hour_list = [$hour_list];
			foreach($hour_list as $t){
				if(!empty($t['Prices']['RoomPrice'])){
					$room_price = $t['Prices']['RoomPrice'];
					is_array(current($room_price)) or $room_price = [$room_price];
					$price_count = count($room_price);
					for($i = 0; $i < $price_count; $i++){
						$w = $room_price[$i];
						$html .= '<tr>';

						if($i == 0){
							$html .= '<td rowspan="' . count($room_price) . '">' . $t['CheckinType'] . '</td>';
						}

						$html .= '<td>' . $w['RoomTypeId'] . '</td><td>' . $w['ActualPrice'] . '</td>';
						$html .= '</tr>';
					}
				}else{
					$html.='<tr><td colspan="3">没有数据</td></tr>';
				}

			}
		}
		$html .= '</table>';

		echo $html;
		exit;
	}

}