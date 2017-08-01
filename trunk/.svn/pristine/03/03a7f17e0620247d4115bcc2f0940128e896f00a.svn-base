<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hotelordert extends MY_Admin {

	protected $label_module= '';		//统一在 constants.php 定义
	protected $label_controller= '酒店订单量报表';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'report/Hotelordert_model';
	}
	
	public function grid()
	{
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		
		if($inter_id== FULL_ACCESS) $inter_id_filter = '1';
		else if($inter_id) $inter_id_filter = 'inter_id = "'.$inter_id.'"';
		else $inter_id_filter = 'inter_id = "deny"';
		
		
		$p = $this->input->get('p');
		$p = intval($p);
		$p = $p>0?$p:1;

		
		///////////////
		/*
		$timeup = $this->input->get('timeup');
		if ($timeup) {
			$inter_id_filter = $inter_id_filter." and order_time<'".strtotime($timeup)."'";
		}
		$timedown = $this->input->get('timedown');
		if ($timedown) {
			$inter_id_filter = $inter_id_filter." and order_time>='".strtotime($timedown)."'";
		}
		$condition['timedown'] = date('Y-m-d',strtotime("-1 month"));
		$condition['timeup'] = date('Y-m-d');
		if ($timedown) {
			$condition['timedown'] = date('Y-m-d',strtotime($timedown));
		}
		
		if ($timeup) {
			$condition['timeup'] = date('Y-m-d',strtotime($timeup));
		}
		
		
		$condition['o_id'] = '';
		$o_id = $this->input->get('o_id');
		if ($o_id) {
			$inter_id_filter = $inter_id_filter." and id='".$o_id."'";
			$condition['o_id'] = $o_id;
		}
		
		$condition['name'] = '';
		$name = $this->input->get('name');
		if ($name) {
			$inter_id_filter = $inter_id_filter." and name='".$name."'";
			$condition['name'] = $name;
		}
		*/
		/////////////////////////////
		
		$count = $this->db->query("select count(0) as count from iwide_v_report_hotels where ".$inter_id_filter." ")->result_array();
		//$sum_price = $this->db->query("select SUM(price) as price from iwide_v_report_hotels where ".$inter_id_filter." ")->result_array();
		
		
		$qfpage = qfpage3($count[0]['count'], 20, $p, 'index?p={p}');
		
		$datalist = $this->db->query("select * from iwide_v_report_hotels where ".$inter_id_filter." limit ".$qfpage['limit']." ")->result_array();
		
		foreach ($datalist as $v) {
			$count_order = $this->db->query("select count(0) as count from iwide_hotel_orders where inter_id='".$v['inter_id']."' and hotel_id='".$v['hotel_id']."'")->result_array();
			$v['count'] = $count_order[0]['count'];
			unset($count_order);
			$datalist_count[] = $v;
		}
		unset($datalist);


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
		
		//base grid data..
		$result= $model->filter($params);
		$fields_config= $model->get_field_config('grid');
		$default_sort= $model::default_sort_field();
			
		$view_params= array(
				'module'=> $this->module,
				'model'=> $model,
				'result'=> $result,
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort,
				'qfpage'=> $qfpage,
				'count'=>$count[0]['count'],
				//'sum_price'=>$sum_price[0]['price'],
				'datalist'=>$datalist_count
		);
		
		$view_params= $view_params+ $viewdata;
		
		$html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}
	
}
