<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Atools extends MY_Admin {
	protected $label_module = NAV_HOTELS;
	protected $label_controller = '酒店列表';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
	}
	function exchange_ym_location() {
		$sql = "SELECT * FROM `iwide_hotels` WHERE `inter_id` LIKE 'a445223616' and status =1";
		$hotels = $this->db->query ( $sql )->result_array ();
		// var_dump($hotels);exit;
		$this->load->helper ( 'calculate' );
		foreach ( $hotels as $h ) {
			if (! empty ( $h ['longitude'] )) {
				$data = bd2gcj ( $h ['longitude'], $h ['latitude'] );
				$this->db->where ( array (
						'inter_id' => 'a445223616',
						'hotel_id' => $h ['hotel_id'] 
				) );
				$this->db->update ( 'hotels', array (
						'longitude' => $data ['longitude'],
						'latitude' => $data ['latitude'] 
				) );
			}
		}
	}
	function lowest_price() {
	}
	function point_fix() {//29516 
		set_time_limit(0);
		ini_set('memory', '1680M');
		$inter_id = 'a445223616';
		$inter_id = $this->input->get ( 'id' );
		if (empty ( $inter_id ) || date ( 'Ymd' ) != 20160429)
			exit ('he');
		$sql = "SELECT o.orderid,o.inter_id,o.startdate,o.enddate,i.iprice,i.istatus,i.handled ihandled,a.complete_point_given,a.complete_point_info,sum(i.iprice) total,m.mem_id,m.bonus,m.growth,m.level 
				 FROM `iwide_hotel_order_items` i join iwide_hotel_orders o join iwide_hotel_order_additions a join iwide_member m 
			 	  on m.openid=o.openid and m.inter_id=o.inter_id and a.inter_id=o.inter_id and a.orderid=o.orderid and i.inter_id=o.inter_id and i.orderid=o.orderid 
				   WHERE o.`inter_id` = '$inter_id' and i.istatus = 3 and i.price_code !=0 and o.handled=1
				    and o.order_time between 1451577600 and 1459526399 
				     and o.orderid not in (SELECT order_id from iwide_member_consumption_record where inter_id='$inter_id') 
				      group by o.orderid";
		$orders = $this->db->query ( $sql )->result_array ();
		$consums = array (
				'inter_id' => $inter_id,
				'type' => 3,
				'on_offline' => 1,
				'note' => '订单离店，赠送积分' 
		);
		$count = 0;
		$l=array(
				'0'=>1.5,'1'=>2,'2'=>2.5,'3'=>2.5
		);
		foreach ( $orders as $o ) {
			$total=intval($o ['total']*$l[$o['level']]);
			$this->db->where ( array (
					'orderid' => $o ['orderid'],
					'inter_id' => $o ['inter_id'] 
			) );
			$this->db->update ( 'hotel_order_additions', array (
					'complete_point_given' => 3,
					'complete_point_info' => '{"type":"BALANCE","give_amount":' . $total . ',"give_rate":1}' 
			) );
			$this->db->where ( array (
					'mem_id' => $o ['mem_id'],
					'inter_id' => $o ['inter_id'] 
			) );
			$this->db->update ( 'member', array (
					'bonus' => $o ['bonus'] + $total,
					'growth' => $o ['growth'] + $total 
			) );
			$consums ['order_id'] = $o ['orderid'];
			$consums ['mem_id'] = $o ['mem_id'];
			$consums ['bonus'] = $total;
			$consums ['create_time'] = date ( 'Y-m-d H:i:s', strtotime($o ['enddate'].' 12:13:11' ));
			$this->db->insert ( 'member_consumption_record', $consums );
			$count ++;
		}
		echo $count;
	}
	function bgy_order_fix(){
		if ( date ( 'Ymd' ) != 20160503)
			exit ('he');
		$sql="SELECT o.*,i.id iid,i.iprice,i.istatus,i.startdate istart,i.enddate iend,i.allprice,a.* 
				FROM `iwide_hotel_orders` o join iwide_hotel_order_items i 
				 join iwide_hotel_order_additions a 
				  on o.orderid=a.orderid and o.inter_id=a.inter_id and o.inter_id=i.inter_id 
				   and o.orderid=i.orderid and (o.enddate!=i.enddate or o.startdate!=i.startdate ) 
				    WHERE o.`inter_id` LIKE 'a421641095' and order_time>=1459872000 and order_time <=1461600000 
				     and TIMESTAMPDIFF(day,o.startdate,o.enddate) =1 and TIMESTAMPDIFF(day,i.startdate,i.enddate) =1 
				      and i.istatus=3 and o.roomnums = 1 and a.coupon_used=1 ORDER BY `o`.`id` DESC";
		$result=$this->db->query($sql)->result_array();
		$debug=$this->input->get('debug');
		if($debug==1){
			var_dump($result);
			exit;
		}
		$count=0;
		foreach ($result as $r){
			if($r['price']!=$r['iprice']){
				if(empty($debug)){
					$this->db->where(array('id'=>$r['iid'],'orderid'=>$r['orderid']));
					$this->db->update('hotel_order_items',array('iprice'=>$r['price']));
				}
				$count++;
			}
		}
		echo $count;
	}
}
