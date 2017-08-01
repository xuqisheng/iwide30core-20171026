<?php

class Jinjiang extends MY_Controller{
	private $inter_id = 'a464177542';

	public function __construct(){
		parent::__construct();
		$this->load->helper('common');

		$this->pms_set = array(
			'hotel_web_id' => 68086, //68007
			'pms_auth'     => json_encode(array(
				                              'url'  => 'http://taxml.hubs1.net/servlet/SwitchReceiveServlet',
				                              'user' => 'ids_207_900111_bestayapp',
				                              'pwd'  => 'bestay900111',
			                              )),
		);
		$this->load->library('JinService', json_decode($this->pms_set['pms_auth'], true), 'serv_api');
	}

	public function fiximgconf(){
		set_time_limit(900);
		//生产数据
		$db = $this->load->database('iwide_r1', true);
		$sw = 1047;
		$zp = 1048;
		//测试数据
//		$db=$db;
//		$sw=848;
//		$zp=849;
		$hotel_additions = $db->from('hotel_additions')->select('hotel_id,hotel_web_id')->where(array(
			                                                                                        'inter_id'       => $this->inter_id,
			                                                                                        'hotel_web_id!=' => ''
		                                                                                        ))->get()->result_array();

		$icon_arr = $db->from('hotel_config')->where(array(
			                                             'inter_id'   => $this->inter_id,
			                                             'param_name' => 'ICONS_IMG_SERACH_RESULT',
			                                             'module'     => 'HOTEL',
		                                             ))->get()->result_array();
		$icon_current = array();
		foreach($icon_arr as $v){
			$v['param_value'] = explode(',', $v['param_value']);
			$icon_current[$v['hotel_id']] = $v;
		}

		$updata_sql = '';
		$insert_sql = '';
		foreach($hotel_additions as $v){
			//查找 API数据
			$detail = $this->serv_api->getProperty($v['hotel_web_id']);
			//是否专票
			$pv = array();
			if(!empty($detail['prop']['specialinvoices']['specialinvoice']) && $detail['prop']['specialinvoices']['specialinvoice'] == 'Y'){
				$pv[] = $zp;
			}

			if(!empty($detail['prop']['receptionforeigner']) && $detail['prop']['receptionforeigner'] == 'Y'){
				$pv[] = $sw;
			}
			if(!empty($icon_current[$v['hotel_id']])){ //数据库中已存在相应记录
				$curr = $icon_current[$v['hotel_id']];
				if($pv){
					$updata_sql .= "update iwide_hotel_config set `param_value` = '" . implode(',', $pv) . "' where id = " . $curr['id'] . ";\n";
				} else{
					$updata_sql .= "update iwide_hotel_config set `param_value` = '' where id = " . $curr['id'] . ";\n";
				}

			} else{
				if($pv){
					$insert_sql .= "insert into iwide_hotel_config (`param_name`,`module`,`param_value`,`hotel_id`,`inter_id`) VALUES ('ICONS_IMG_SERACH_RESULT','HOTEL','" . implode(',', $pv) . "'," . (int)$v['hotel_id'] . ",'" . $this->inter_id . "');\n";
				}
			}


			//是否存在涉外
			/*if(!empty($detail['prop']['receptionforeigner']) && $detail['prop']['receptionforeigner'] == 'Y'){

				if(!empty($icon_current[$v['hotel_id']])){ //数据库中已存在相应记录
					$curr = $icon_current[$v['hotel_id']];
					$pv = array($sw);
					if(in_array($zp, $curr['param_value'])){
						$pv[] = $zp;
					}

					$updata_sql .= "update iwide_hotel_config set `param_value` = '" . implode(',', $pv) . "' where id = " . $curr['id'] . ";\n";
				} else{ //不存在记录
					$insert_sql .= "insert into iwide_hotel_config (`param_name`,`module`,`param_value`,`hotel_id`,`inter_id`) VALUES ('ICONS_IMG_SERACH_RESULT','HOTEL','" . $sw . "'," . (int)$v['hotel_id'] . ",'" . $this->inter_id . "');\n";
				}
			} else{ //非涉外
				if(!empty($icon_current[$v['hotel_id']])){  //数据库中存在相应记录
					$curr = $icon_current[$v['hotel_id']];
					$new = array();
					if(in_array($zp, $curr['param_value'])){//数据库中记录有专票图标
						$updata_sql .= "update iwide_hotel_config set `param_value` = '" . $zp . "' where id=" . $curr['id'] . ";\n";
					} else{
						$updata_sql .= "update iwide_hotel_config set `param_value` = '' where id=" . $curr['id'] . ";\n";
					}
				}
			}*/
		}
		echo $updata_sql . "\n\n" . $insert_sql;


	}

	public function get_hotels(){
		set_time_limit(9000);
		$arr_list = $this->serv_api->getPropList();
		$props_list = $arr_list['props']['prop'];
		$page = (int)$this->input->get('page');
		$page > 0 or $page = 0;
		$limit = 10;
		$total = count($props_list);
		$total_page = ceil($total / $limit);
		if($page > $total_page){
			return array();
		}

//		$props_list = array_slice($props_list, ($page - 1) * $limit, $limit);

		/*foreach($props_list as &$v){
			$room = $this->serv_api->getRoomObj($v['id']);
			if($room['@attributes']['result'] === 'success'){
				$v['room_list'] = isset($room['roomobjmap']['roomobjdata']['roomobjdetail']) ? $room['roomobjmap']['roomobjdata']['roomobjdetail'] : array();
			}
			//基本资料
			$detail = $this->serv_api->getProperty($v['id']);
			if($detail['@attributes']['result'] === 'success'){
				$v['detail'] = $detail['prop'];
			} else{
				$v['detail'] = array();
			}

			//描述信息
			$desc = $this->serv_api->getPropDesc($v['id']);
			$desc_arr = array();
			if($desc['@attributes']['result'] === 'success'){
				$desc_arr = $desc['descriptionmap']['description'];
			}
			$v['descriptions'] = $desc_arr;

			//图片信息
			$image = $this->serv_api->getImage($v['id']);
			$image_list = array();
			if($image['@attributes']['result'] === 'success'){
				$image_list = array(
					'picture'     => $image['picture'],
					'coverimages' => isset($image['coverimages']) ? $image['coverimages'] : array(),
					'gallerys'    => isset($image['gallerys']) ? $image['gallerys'] : array(),

				);
			}
			$v['image'] = $image_list;
		}*/
		echo json_encode(array(
			                 'total' => count($props_list),
			                 'list'  => $props_list,
		                 ));
	}

	public function testModResv(){
		$this->load->library('JinService', array(), 'serv_api');
		$this->serv_api->setPmsAuth(json_decode($this->pms_set['pms_auth'], true));

		$web_order_json = '{"@attributes":{"msgid":"20160908165119854-5254007059FF-00957","result":"success","msgtype":"getpropresv"},"resvdata":{"reservation":{"prop":"68039","propname":"\u767e\u65f6\u5feb\u6377\u91d1\u5c71\u57ce\u5e02\u6c99\u6ee9\u9152\u5e97","confnum":"68039R01035","pmsno":null,"sign":"h","signname":"HOLD\u8ba2\u5355","status":"n","statusname":"\u65b0\u5355","channel":"Website","channelname":"Website","timestamp":null,"createdate":"2016-09-08 16:51:19","lastmodifydate":"2016-09-08 16:50:47","guestinfo":{"firstname":"\u91d1\u623f\u5361\u6d4b\u8bd5\u5427","lastname":null,"mobile":"18888888888","holdTime":"2016-10-03 18:00:00","phone":"18888888888","email":null,"country":null,"city":null,"street1":null,"street2":null,"postcode":null,"title":null},"staydetail":{"roomno":null,"roomtype":"SIRB","roomtypename":"\u5355\u4eba\u623fB","rateplan":"SIRB-MEMG2","rateplanname":"\u91d1\u5361\u4f1a\u5458\u7f51\u7ad9\u4ef7","bookdate":"2016-09-08","indate":"2016-10-03","outdate":"2016-10-04","departuredate":null,"adults":"1","children":"0","nights":"1","rooms":"1"},"ccinfo":null,"remarks":{"remark":null},"modifyhistory":null,"miscinfo":{"iata":"900111","discount":null,"group":null,"subsource":"211","maincontractedid":null,"contractedid":null,"companyno":null},"bookedrates":{"totalrevenue":"71.0","bookedrate":{"adult":"1","child":"0","date":"2016-10-03","extracharge":"0.0","rate":"71.0"}},"guarruledetail":{"rule":"RH","description":"\u9884\u8ba2\u4fdd\u7559\u81f3\u5165\u4f4f\u65e518:00\u3002"},"cxlruledetail":{"rule":"NP","description":"\u5165\u4f4f\u65e5\u5f53\u592918:00\u524d\u53d8\u66f4\u6216\u53d6\u6d88\u4e0d\u6536\u8d39\uff0c\u903e\u671f\u5c06\u6536\u53d6\u9996\u665a\u623f\u8d39\u4f5c\u4e3a\u7f5a\u91d1\u3002","allowcancel":"0","lastcanceltime":"2016-10-03 18:00:00"},"resvclass":"RT","paymentinfo":{"payment":"\u623f\u8d39\u5168\u989d\u652f\u4ed8\u62c5\u4fdd\uff0c\u5176\u4ed6\u8d39\u7528\u524d\u53f0\u73b0\u4ed8","paymentamount":"71.0","paymentsource":"4","refundamount":null},"tracelogid":"2U147332464787644","couponinfo":{"couponcount":null,"coupontotalamount":null},"memberinfo":{"memberno":"1149701026","memberclass":"GCM","guestid":"205aaf1428df5457283a37da20db66c2"}}}}';
		$res = json_decode($web_order_json, true);
		$web_order = $res['resvdata']['reservation'];

		$rateplan_arr = explode('-', $web_order['staydetail']['rateplan']);
		$data = [
			'confnum'     => $web_order['confnum'],
			'staydetail'  => [
				'date'      => $web_order['staydetail']['indate'],
				'nights'    => $web_order['staydetail']['nights'],
				'roomtype'  => $web_order['staydetail']['roomtype'],
				'rateclass' => $rateplan_arr[1],
				'rooms'     => $web_order['staydetail']['rooms'],
				'adults'    => $web_order['staydetail']['adults'],
				'children'  => $web_order['staydetail']['children'],
				'channel'   => 'Website',
			],
			'guestinfo'   => [
				'firstname'  => $web_order['guestinfo']['firstname'],
				'lastname'   => $web_order['guestinfo']['lastname'],
				'otherguest' => [],
				'holdTime'   => $web_order['guestinfo']['holdTime'],
				'phone'      => $web_order['guestinfo']['phone'],
				'email'      => $web_order['guestinfo']['email'],
				'mobile'     => $web_order['guestinfo']['mobile'],
				'street1'    => $web_order['guestinfo']['street1'],
			],
			/*'paymentinfo'=>[
				'payment'=>$web_order['paymentinfo']['payment'],
				'paymentstatus'=>1,
				'paymentamount'=>$web_order['paymentinfo']['paymentamount'],
				'paymentsource'=>$web_order['paymentinfo']['paymentsource'],
				'tradeno'=>$trans_no,
//				    'paidurl'=>$web_order['paymentinfo']['payment'],
//				    'returnurl'=>$web_order['paymentinfo']['payment'],
			],*/
			'paymentinfo' => $web_order['paymentinfo'],
			'remarks'     => $web_order['remarks'],
			'memberinfo'  => $web_order['memberinfo'],
			'miscinfo'    => [
				'IATA' => $web_order['miscinfo']['iata'],
			],
			'tracelogid'  => $web_order['tracelogid'],
			'couponinfo'  => $web_order['couponinfo'],
			'bookedrates' => [
				'bookedrate' => $web_order['bookedrates']['bookedrate'],
			],
			'isassure'    => 4,
		];

		$trans_no = $data['confnum'];
		if($trans_no){
//				$data['holdresv'] = 1;
			$data['paymentinfo']['paymentstatus'] = 1;
			$data['paymentinfo']['tradeno'] = $trans_no;
		}

		$res = $this->serv_api->setModResv($web_order['prop'], $data);
		echo json_encode($res);
	}

	public function get_weborder($order_id){
		$db = $this->load->database('iwide_r1', true);

		$row = $db->from('hotel_additions a')->join('hotel_orders o', 'o.hotel_id=a.hotel_id')->join('hotel_order_additions oa', 'oa.orderid=o.orderid')->select('a.hotel_id,a.hotel_web_id,a.pms_auth,oa.web_orderid,a.inter_id,o.orderid')->where(['o.orderid' => $order_id])->get()->row_array();

		$pms_set = [
			'inter_id'     => $row['inter_id'],
			'hotel_web_id' => $row['hotel_web_id'],
			'pms_auth'     => $row['pms_auth'],
		];

		$this->load->model('hotel/pms/Jinjiang_hotel_model', 'pms');
		$web_order = $this->pms->get_web_order($row['web_orderid'], $pms_set, true);

		return $web_order;

//		echo json_encode($web_order);exit;

		$result = [
			'orderid'       => $order_id,
			'indate'        => $web_order['staydetail']['indate'],
			'outdate'       => $web_order['staydetail']['outdate'],
			'departuredate' => $web_order['staydetail']['departuredate'],
		];
		foreach($web_order['room_list'] as $v){
			if($web_order['staydetail']['indate'] != $v['realindate'] || ($web_order['staydetail']['outdate'] != $v['realoutdate'])){
				$result['sub_order'][] = [
					'realindate'  => $v['realindate'],
					'realoutdate' => $v['realoutdate'],
				];
			}
		}
		return $result;
//		echo json_encode($web_order);
	}

	public function check_diff($page = null){
		set_time_limit(900);
		$page !== null or $page = 1;
		$db = $this->load->database('iwide_r1', true);
		$this->load->model('hotel/pms/Jinjiang_hotel_model', 'pms');

		$list = $db->from('hotel_order_items')->where("inter_id = '" . $this->inter_id . "' and istatus=3 and enddate!=DATE_FORMAT(leavetime,'%Y%m%d') group by orderid")->select('orderid')->limit(100)->offset(($page - 1) * 100)->order_by('orderid desc')->get()->result_array();

		$result = [];
		foreach($list as $v){
			$tmp = $this->get_web_order($v['orderid']);
			if(!empty($tmp['sub_order'])){
				$result[] = $tmp;
			}
		}
		$redis = new Redis ();
		$this->load->config('redis', true);
		$redis->connect($this->config->item('host', 'redis'), $this->config->item('port', 'redis'));
		$redis->select(7);
		$key = 'jinjiang:orderid';
		foreach($result as $v){
			$redis->sAdd($key, $v['orderid']);
			echo $v['orderid'] . "\n";
		}
		$redis->expire($key, 7 * 86400);

		/*echo json_encode([
			'count'=>count($result),
		    'list'=>$result,
		                 ]);*/

	}

	public function weborderlist($page = null){
		set_time_limit(900);
		$page !== null or $page = 1;

		$redis = new Redis ();
		$this->load->config('redis', true);
		$redis->connect($this->config->item('host', 'redis'), $this->config->item('port', 'redis'));
		$redis->select(7);
		$key = 'jinjiang:orderid';
		$limit = 20;
		$all = $redis->sMembers($key);
		$offset = ($page - 1) * $limit;

		$arr = array_slice($all, $offset, $limit);
		$list = [];
		foreach($arr as $v){
			$list[] = $this->get_weborder($v);
		}
		echo json_encode($list);
	}

	public function getredis(){
		$redis = new Redis ();
		$this->load->config('redis', true);
		$redis->connect($this->config->item('host', 'redis'), $this->config->item('port', 'redis'));
		$redis->select(7);
		$key = 'jinjiang:orderid';
		$lis=$redis->sMembers($key);
		$lis[]=$this->config->item('redis');
		echo json_encode($lis);
	}

}