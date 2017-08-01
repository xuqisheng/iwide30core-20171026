<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Admin {

	protected $label_module= '';		//统一在 constants.php 定义
	protected $label_controller= '';		//在文件定义
	protected $label_action= '';				//在方法中定义
	protected $db_resource= array();
	
	protected function _db($select=NULL)
	{
		$select= $select? $select: $this->db_read;
		if( !isset($this->db_resource[$select]) ) {
			$this->db_resource[$select]= $this->load->database($select, TRUE);
		}
		return $this->db_resource[$select];
	}
	
	protected function main_model_name()
	{
		return 'report/Oraders_model';
	}
	
	public function grid()
	{
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		
		$entity_filter = "";
		$entity_id = $this->session->get_admin_hotels();
		if ($entity_id) {
			$entity_filter = " and hotel_id in (".$entity_id.") ";
		}
		
		if($inter_id== FULL_ACCESS) $inter_id_filter = '1';
		else if($inter_id) $inter_id_filter = 'inter_id = "'.$inter_id.'"'.$entity_filter;
		else $inter_id_filter = 'inter_id = "deny"';
		
		$viewdata = array();
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		
		//filter params: the same with table fields...
		//sort params: sort_direct, sort_field
		//page params: page_size, page_num
		$params= $this->input->get();
		if(is_array($filter) && count($filter)>0 )
			$params= array_merge($params, $filter);
		

		//HTML输出
		$this->label_action= '信息列表';
		$this->_init_breadcrumb($this->label_action);
	
		$fields_config= $model->get_field_config('grid');
		$default_sort= $model::default_sort_field();
		
		$time = time();
		
		$today0 = date("Y-m-d 00:00:00",$time );		

		$condition = $inter_id_filter.' and order_time>'.strtotime($today0);
		$order_count_all = $model->order_count($condition);
		
		//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常 8未到 9未支付 10下单失败
		
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status in ( 0,1 )';
		$order_count_notcheck = $model->order_count($condition);		
		
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status in ( 2,3 )';
		$order_count_check = $model->order_count($condition);		
		
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status in ( 4,5,6 )';
		$order_count_canel = $model->order_count($condition);
		
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status not in ( 0,1,2,3,4,5,6 )';
		$order_count_else = $model->order_count($condition);
		
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and paid="1"';
		$order_sum_paid = $model->order_sum('price',$condition);

		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status not in ( 4,5,6 )';
		$order_sum_all = $model->order_sum('price',$condition);
		
		for ($i = 0; $i < 24; $i++) {
			$condition = $inter_id_filter.' and order_time>='.strtotime(date("Y-m-d ".$i.":00:00",$time )).' and order_time<'.strtotime(date("Y-m-d ".($i+1).":00:00",$time ));
			$order_count_time = $model->order_count($condition);
			$order_time['count'][] = $order_count_time[0]['count'];
			$order_time['time'][] = $i.':00';
			
		}
			
		$view_params= array(
				'module'=> $this->module,
				'model'=> $model,
				'order_count_all'=>$order_count_all[0],
				'order_count_notcheck'=>$order_count_notcheck[0],
				'order_count_check'=>$order_count_check[0],
				'order_count_canel'=>$order_count_canel[0],			
				'order_count_else'=>$order_count_else[0],
				'order_time'=>$order_time,				
				'order_sum_paid'=>$order_sum_paid[0],
				'order_sum_all'=>$order_sum_all[0],
				
				'attribute_items'=>$model->attribute_items(),
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort
		);
		
		$view_params= $view_params+ $viewdata;
	
		$html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
		//echo $html;die;
		echo $html;

	}
	
	private function get_order_array($hotel_id='',$inter_id) {
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
	
		$entity_filter = "";
		if ($hotel_id) {
			$entity_filter = " and hotel_id in (".$hotel_id.") ";
		}
	
		if($inter_id== FULL_ACCESS) $inter_id_filter = '1';
		else if($inter_id) $inter_id_filter = 'inter_id = "'.$inter_id.'"'.$entity_filter;
		else $inter_id_filter = 'inter_id = "deny"';
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
	
		$time = time();
		///////////////////////////////////////////////////////调试数据
		$get_time = $this->input->get('time');
		if ($get_time) {
			$time = time()-$get_time;
		}
		
		$today0 = date("Y-m-d 00:00:00",$time );
		$condition = $inter_id_filter.' and order_time>'.strtotime($today0);
		$order_count_all = $model->order_count($condition);
	
		//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常 8未到 9未支付 10下单失败
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status in ( 0,1 )';
		$order_count_notcheck = $model->order_count($condition);
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status in ( 2,3 )';
		$order_count_check = $model->order_count($condition);
		
		//////////////////
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status in ( 2,3 )';
		$order_count_roomnums = $model->order_sum('roomnums',$condition);
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status in ( 4,5,6 )';
		$order_count_canel = $model->order_count($condition);
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status not in ( 0,1,2,3,4,5,6 )';
		$order_count_else = $model->order_count($condition);
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and paid="1"';
		$order_sum_paid = $model->order_sum('price',$condition);
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status not in ( 4,5,6 )';
		$order_sum_all = $model->order_sum('price',$condition);
	
		for ($i = 0; $i < 24; $i++) {
			$condition = $inter_id_filter.' and order_time>='.strtotime(date("Y-m-d ".$i.":00:00",$time )).' and order_time<'.strtotime(date("Y-m-d ".($i+1).":00:00",$time ));
			$order_count_time = $model->order_count($condition);
			$order_time['count'][] = $order_count_time[0]['count'];
			$order_time['time'][] = $i.':00';
		}		
			
		$view_params= array(
				'order_count_all'=>$order_count_all[0],
				'order_count_notcheck'=>$order_count_notcheck[0],
				'order_count_check'=>$order_count_check[0],
				'order_count_canel'=>$order_count_canel[0],
				'order_count_else'=>$order_count_else[0],
				'order_time'=>$order_time,
				'order_count_roomnums'=>$order_count_roomnums[0],
				'order_sum_paid'=>$order_sum_paid[0],
				'order_sum_all'=>$order_sum_all[0],
		);
	
		return $view_params;
	}
	
	
	public function rate()
	{
		$inter_id= $this->session->get_admin_inter_id();

		///////////////////////////////////////////////////////调试数据
		$get_inter_id = $this->input->get('inter_id');
		if ($get_inter_id) {
			$inter_id = $get_inter_id;
		}
		
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
	
		$entity_filter = "";
		$entity_id = $this->session->get_admin_hotels();

		///////////////////////////////////////////////////////调试数据
		$get_entity_id = $this->input->get('entity_id');
		if ($get_entity_id) {
			$entity_id = $get_entity_id;
		}

		if ($entity_id) {
			$entity_filter = " and hotel_id in (".$entity_id.") ";
		}
		
		
	
		if($inter_id== FULL_ACCESS) $inter_id_filter = '1';
		else if($inter_id) $inter_id_filter = 'inter_id = "'.$inter_id.'"'.$entity_filter;
		else $inter_id_filter = 'inter_id = "deny"';
	
		$viewdata = array();
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
	
	
		//filter params: the same with table fields...
		//sort params: sort_direct, sort_field
		//page params: page_size, page_num
		$params= $this->input->get();
		if(is_array($filter) && count($filter)>0 )
			$params= array_merge($params, $filter);
	
	
		//HTML输出
		$this->label_action= '信息列表';
		$this->_init_breadcrumb($this->label_action);
	
		$fields_config= $model->get_field_config('grid');
		$default_sort= $model::default_sort_field();
	
		$time = time();
		///////////////////////////////////////////////////////调试数据
		$get_time = $this->input->get('time');
		if ($get_time) {
			$time = time()-$get_time;
		}
	
		$today0 = date("Y-m-d 00:00:00",$time );
	
		$condition = $inter_id_filter.' and order_time>'.strtotime($today0);
		$order_count_all = $model->order_count($condition);
	
		//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常 8未到 9未支付 10下单失败
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status in ( 0,1 )';
		$order_count_notcheck = $model->order_count($condition);
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status in ( 2,3 )';
		$order_count_check = $model->order_count($condition);
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status in ( 4,5,6 )';
		$order_count_canel = $model->order_count($condition);
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status not in ( 0,1,2,3,4,5,6 )';
		$order_count_else = $model->order_count($condition);
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and paid="1"';
		$order_sum_paid = $model->order_sum('price',$condition);
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($today0).' and status not in ( 4,5,6 )';
		$order_sum_all = $model->order_sum('price',$condition);
	
		for ($i = 0; $i < 24; $i++) {
			$condition = $inter_id_filter.' and order_time>='.strtotime(date("Y-m-d ".$i.":00:00",$time )).' and order_time<'.strtotime(date("Y-m-d ".($i+1).":00:00",$time ));			
			$order_count_time = $model->order_sum('roomnums',$condition);
			$count = $order_count_time[0]['sum']?$order_count_time[0]['sum']:0;
			$order_time['count'][] = $count;
			$order_time['time'][] = $i.':00';
		}
		
		$hotels_data_content = array();
		if ($entity_id) {//产品需求没判断清楚，暂先留着以备后用
			$hotels_data = $model->get_hotels($entity_id,$inter_id);
			foreach ($hotels_data as $v) {
			
				$v['hotel_id_data'] = $this->get_order_array($v['hotel_id'],$inter_id);
			
				$hotels_data_content[] = $v;
			}
		}
		else {
			$hotels_data = $model->get_hotels('',$inter_id);
			foreach ($hotels_data as $v) {
			
				$v['hotel_id_data'] = $this->get_order_array($v['hotel_id'],$inter_id);
			
				$hotels_data_content[] = $v;
			}
		}
			
		$view_params= array(
				'module'=> $this->module,
				'model'=> $model,
				'order_count_all'=>$order_count_all[0],
				'order_count_notcheck'=>$order_count_notcheck[0],
				'order_count_check'=>$order_count_check[0],
				'order_count_canel'=>$order_count_canel[0],
				'order_count_else'=>$order_count_else[0],
				'order_time'=>$order_time,
				'order_sum_paid'=>$order_sum_paid[0],
				'order_sum_all'=>$order_sum_all[0],
				
				'order_hotels'=>$hotels_data_content,
	
				'attribute_items'=>$model->attribute_items(),
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort
		);
	
		$view_params= $view_params+ $viewdata;
	
		$html= $this->_render_content($this->_load_view_file('rate'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	
	}
	
	
	public function old()
	{
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		
		$entity_filter = "";
		$entity_id = $this->session->get_admin_hotels();
		if ($entity_id) {
			$entity_filter = " and hotel_id in (".$entity_id.") ";
		}
		
		if($inter_id== FULL_ACCESS) $inter_id_filter = '1';
		else if($inter_id) $inter_id_filter = 'inter_id = "'.$inter_id.'"'.$entity_filter;
		else $inter_id_filter = 'inter_id = "deny"';
	
		$viewdata = array();
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
	
		//filter params: the same with table fields...
		//sort params: sort_direct, sort_field
		//page params: page_size, page_num
		$params= $this->input->get();
		if(is_array($filter) && count($filter)>0 )
			$params= array_merge($params, $filter);
	
	
		//HTML输出
		$this->label_action= '信息列表';
		$this->_init_breadcrumb($this->label_action);
	
		$fields_config= $model->get_field_config('grid');
		$default_sort= $model::default_sort_field();
		
		$mindate = $this->input->get('mindate');
		if (!$mindate) {
			$mindate = date("Y-m-d");
		}
		$maxdate = $this->input->get('maxdate');
		if (!$maxdate) {
			$maxdate = date("Y-m-d");
		}
		
		$startdate=strtotime($mindate);
		$enddate=strtotime($maxdate);
		$days=round(($enddate-$startdate)/3600/24) ;
		
		
		$search_date = array('mindate'=>$mindate,'maxdate'=>$maxdate);
		
		$today0 = date("Y-m-d 00:00:00");
	
		$condition = $inter_id_filter.' and order_time>='.strtotime($mindate).' and order_time<='.(strtotime($maxdate)+86400);
		$order_count_all = $model->order_count($condition);
	
		//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常 8未到 9未支付 10下单失败
	
		$condition = $inter_id_filter.' and order_time>'.strtotime($mindate).' and order_time<'.(strtotime($maxdate)+86400).' and status in ( 0,1 )';
		$order_count_notcheck = $model->order_count($condition);
	
		$condition = $inter_id_filter.' and order_time>'.strtotime($mindate).' and order_time<'.(strtotime($maxdate)+86400).' and status in ( 2,3 )';
		$order_count_check = $model->order_count($condition);
	
		$condition = $inter_id_filter.' and order_time>'.strtotime($mindate).' and order_time<'.(strtotime($maxdate)+86400).' and status in ( 4,5,6 )';
		$order_count_canel = $model->order_count($condition);
	
		$condition = $inter_id_filter.' and order_time>'.strtotime($mindate).' and order_time<'.(strtotime($maxdate)+86400).' and status not in ( 0,1,2,3,4,5,6 )';
		$order_count_else = $model->order_count($condition);
	
		$condition = $inter_id_filter.' and order_time>'.strtotime($mindate).' and order_time<'.(strtotime($maxdate)+86400).' and paid="1"';
		$order_sum_paid = $model->order_sum('price',$condition);
	
		$condition = $inter_id_filter.' and order_time>'.strtotime($mindate).' and order_time<'.(strtotime($maxdate)+86400).' and status not in ( 4,5,6 )';
		$order_sum_all = $model->order_sum('price',$condition);
		
		for ($i = 0; $i <= $days; $i++) {
			$condition = $inter_id_filter.' and order_time>='.(  strtotime($mindate." 00:00:00")+($i*86400)).' and order_time<'.(strtotime($mindate." 23:59:59")+($i*86400));
			$order_count_time = $model->order_count($condition);
			$order_time['count'][] = $order_count_time[0]['count'];
			$order_time['date'][] = date("Ymd",(strtotime($mindate." 00:00:00")+($i*86400)));
			
		}
			
		$view_params= array(
				'module'=> $this->module,
				'model'=> $model,
				'order_count_all'=>$order_count_all[0],
				'order_count_notcheck'=>$order_count_notcheck[0],
				'order_count_check'=>$order_count_check[0],
				'order_count_canel'=>$order_count_canel[0],
				'order_count_else'=>$order_count_else[0],
				'search_date'=>$search_date,
				'order_sum_paid'=>$order_sum_paid[0],
				'order_sum_all'=>$order_sum_all[0],
				'order_time'=>$order_time,
				'attribute_items'=>$model->attribute_items(),
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort
		);
	
		$view_params= $view_params+ $viewdata;
	
		$html= $this->_render_content($this->_load_view_file('old'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	
	}
	
	
	public function hotel()
	{
		$inter_id= $this->session->get_admin_inter_id();
		
		///////////////////////////////////////////////////////调试数据
		$get_inter_id = $this->input->get('inter_id');
		if ($get_inter_id) {
			$inter_id = $get_inter_id;
		}
		
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
	
		$entity_filter = "";
		$entity_id = $this->session->get_admin_hotels();
		
		///////////////////////////////////////////////////////调试数据
		$get_entity_id = $this->input->get('entity_id');
		if ($get_entity_id) {
			$entity_id = $get_entity_id;
		}
		
		if ($entity_id) {
			$entity_filter = " and hotel_id in (".$entity_id.") ";
		}
	
		if($inter_id== FULL_ACCESS) $inter_id_filter = '1';
		else if($inter_id) $inter_id_filter = 'inter_id = "'.$inter_id.'"'.$entity_filter;
		else $inter_id_filter = 'inter_id = "deny"';
	
		$viewdata = array();
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
	
		//filter params: the same with table fields...
		//sort params: sort_direct, sort_field
		//page params: page_size, page_num
		$params= $this->input->get();
		if(is_array($filter) && count($filter)>0 )
			$params= array_merge($params, $filter);
	
	
		//HTML输出
		$this->label_action= '信息列表';
		$this->_init_breadcrumb($this->label_action);
	
		$fields_config= $model->get_field_config('grid');
		$default_sort= $model::default_sort_field();
	

		//////////////////////////////////////////
		$mindate = $this->input->get('mindate');
		if (!$mindate) {
			$mindate = date("Y-m-d");
		}
		$maxdate = $this->input->get('maxdate');
		if (!$maxdate) {
			$maxdate = date("Y-m-d");
		}
		
		$startdate=strtotime($mindate);
		$enddate=strtotime($maxdate);
		$days=round(($enddate-$startdate)/3600/24) ;
		$search_date = array('mindate'=>$mindate,'maxdate'=>$maxdate);
		/////////////////////////////////////////
		
		$hotels_data = array();
		//if ($entity_id) {
		$hotels_data = $model->get_hotels($entity_id,$inter_id);
		//}
		
		$select_hotel = $this->input->get('select_hotel');
		$hotelid = '';
		if ($select_hotel) {
			$hotelid = $select_hotel;
		}
		else {
			if ($hotels_data) {
				$hotelid = $hotels_data['0']['hotel_id'];
			}
		}
		
		$rooms_data = $model->get_rooms($hotelid,$inter_id);
		$rooms = array();
		foreach ($rooms_data as $v) {
		
			$v['room_count'] = $model->get_rooms_order_count($v['room_id']," and order_time>".strtotime($mindate)." and order_time<".(strtotime($maxdate)+86400)." ");
		
			$rooms[] = $v;
		}
		
		
		$order_all = $model->order_count("inter_id='".$inter_id."' and hotel_id = '".$hotelid."'");
		$order_cannel_weixin = $model->order_count("inter_id='".$inter_id."' and order_time>".strtotime($mindate)." and order_time<".(strtotime($maxdate)+86400)." and hotel_id = '".$hotelid."' and paytype='weixin' and status in ( 4,5,6 )");
		$order_cannel_daofu = $model->order_count("inter_id='".$inter_id."' and order_time>".strtotime($mindate)." and order_time<".(strtotime($maxdate)+86400)." and hotel_id = '".$hotelid."' and paytype='daofu' and status in ( 4,5,6 )");
		$order_cannel_balance = $model->order_count("inter_id='".$inter_id."' and order_time>".strtotime($mindate)." and order_time<".(strtotime($maxdate)+86400)." and hotel_id = '".$hotelid."' and paytype='balance' and status in ( 4,5,6 )");
		$cannel_probability = array('order_all'=>$order_all[0]['count'],'order_cannel_weixin'=>$order_cannel_weixin[0]['count'],'order_cannel_daofu'=>$order_cannel_daofu[0]['count'],'order_cannel_balance'=>$order_cannel_balance[0]['count']);
		
		//入住率
		$order_check_weixin = $model->order_count("inter_id='".$inter_id."' and order_time>".strtotime($mindate)." and order_time<".(strtotime($maxdate)+86400)." and hotel_id = '".$hotelid."' and paytype='weixin' and status in ( 2,3 )");
		$order_check_daofu = $model->order_count("inter_id='".$inter_id."' and order_time>".strtotime($mindate)." and order_time<".(strtotime($maxdate)+86400)." and hotel_id = '".$hotelid."' and paytype='daofu' and status in ( 2,3 )");
		$order_check = array('order_check_weixin'=>$order_check_weixin[0]['count'],'order_check_daofu'=>$order_check_daofu[0]['count']);
		
			
		$view_params= array(
				'module'=> $this->module,
				'model'=> $model,
				'attribute_items'=>$model->attribute_items(),
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort,
				'hotels_data'=>$hotels_data,
				'rooms'=>$rooms,
				'order_check'=>$order_check,
				'cannel_probability'=>$cannel_probability,
				'search_date'=>$search_date
		);
	
		$view_params= $view_params+ $viewdata;
	
		$html= $this->_render_content($this->_load_view_file('hotel'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	
	}
	
	public function user() {
		$inter_id= $this->session->get_admin_inter_id();

		///////////////////////////////////////////////////////调试数据
		$get_inter_id = $this->input->get('inter_id');
		if ($get_inter_id) {
			$inter_id = $get_inter_id;
		}

		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
	
		$entity_filter = "";
		$entity_id = $this->session->get_admin_hotels();

		///////////////////////////////////////////////////////调试数据
		$get_entity_id = $this->input->get('entity_id');
		if ($get_entity_id) {
			$entity_id = $get_entity_id;
		}

		if ($entity_id) {
			$entity_filter = " and hotel_id in (".$entity_id.") ";
		}
	
		if($inter_id== FULL_ACCESS) $inter_id_filter = '1';
		else if($inter_id) $inter_id_filter = 'inter_id = "'.$inter_id.'"'.$entity_filter;
		else $inter_id_filter = 'inter_id = "deny"';
	
		$viewdata = array();
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
	
		//filter params: the same with table fields...
		//sort params: sort_direct, sort_field
		//page params: page_size, page_num
		$params= $this->input->get();
		if(is_array($filter) && count($filter)>0 )
			$params= array_merge($params, $filter);
	
	
		//HTML输出
		$this->label_action= '信息列表';
		$this->_init_breadcrumb($this->label_action);
	
		$fields_config= $model->get_field_config('grid');
		$default_sort= $model::default_sort_field();
		
		$today_time = strtotime(date("Y-m-d"));
		$inter_id_filter = " and order_time>".$today_time." and ".$inter_id_filter;
		
		$inter_id_filter_sex = str_replace('inter_id', 'iwide_hotel_orders.inter_id', $inter_id_filter);
		$inter_id_filter_sex = str_replace('hotel_id', 'iwide_hotel_orders.hotel_id', $inter_id_filter_sex);
		$user_sex = $model->user_sex($inter_id_filter_sex);
		
		$inter_id_filter_old = " where 1 ".$inter_id_filter;
		$user_old = $model->user_old($inter_id_filter_old);
		
		
		$view_params= array(
				'module'=> $this->module,
				'model'=> $model,
				'attribute_items'=>$model->attribute_items(),
				'fields_config'=> $fields_config,
				'user_sex'=>$user_sex,
				'user_old'=>$user_old,
				'default_sort'=> $default_sort,
		);
		
		$view_params= $view_params+ $viewdata;
		
		$html= $this->_render_content($this->_load_view_file('user'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	
	}
}
