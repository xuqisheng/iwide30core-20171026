<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distribute extends MY_Admin {

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
		return 'report/Distribute_model';
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

		if($inter_id== FULL_ACCESS) $inter_id_filter = '1';
		else if($inter_id) $inter_id_filter = 'inter_id = "'.$inter_id.'"';
		else $inter_id_filter = 'inter_id = "deny"';

		$p = $this->input->get('p');
		$p = intval($p);
		$p = $p>0?$p:1;

		$timeup = $this->input->get('timeup');
		if ($timeup) {
			$inter_id_filter = $inter_id_filter." and grade_time<'".$timeup." 23:59:59'";
			$condition['timeup'] = date('Y-m-d',strtotime($timeup));
		}
		else {
			$inter_id_filter = $inter_id_filter." and grade_time<'".date('Y-m-d')." 23:59:59'";
			$condition['timeup'] = date('Y-m-d');
		}

		$timedown = $this->input->get('timedown');
		if ($timedown) {
			$inter_id_filter = $inter_id_filter." and grade_time>='".$timedown." 00:00:00'";
			$condition['timedown'] = date('Y-m-d',strtotime($timedown));
		}
		else {
			$inter_id_filter = $inter_id_filter." and grade_time>='".date('Y-m-d',strtotime("-1 month"))." 00:00:00'";
			$condition['timedown'] = date('Y-m-d',strtotime("-1 month"));
		}

		$condition['paystatus'] = '';
		$paystatus = $this->input->get('paystatus');
		if ($paystatus) {
			$inter_id_filter = $inter_id_filter." and status='".$paystatus."'";
			$condition['paystatus'] = $paystatus;
		}

		$condition['staff_id'] = '';
		$staff_id = $this->input->get('staff_id');
		if ($staff_id) {
			$inter_id_filter = $inter_id_filter." and id='".$staff_id."'";
			$condition['staff_id'] = $staff_id;
		}

		$condition['staff_name'] = '';
		$staff_name = $this->input->get('staff_name');
		if ($staff_name) {
			$inter_id_filter = $inter_id_filter." and staff_name='".$staff_name."'";
			$condition['staff_name'] = $staff_name;
		}

		$condition['grade_table'] = '';
		$grade_table = $this->input->get('grade_table');
		if ($grade_table) {
			$inter_id_filter = $inter_id_filter." and grade_table='".$grade_table."'";
			$condition['grade_table'] = $grade_table;
		}

		$condition['saler'] = '';
		$saler = $this->input->get('saler');
		if ($saler) {
			$inter_id_filter = $inter_id_filter." and saler='".$saler."'";
			$condition['saler'] = $saler;
		}

		$condition['hotel_name'] = '';
		$hotel_name = $this->input->get('hotel_name');
		if ($hotel_name) {
			$inter_id_filter = $inter_id_filter." and hotel_name='".$hotel_name."'";
			$condition['hotel_name'] = $hotel_name;
		}

		$count = $this->_db('iwide_r1')->query("select count(0) as count from iwide_v_report_distribute_all where ".$inter_id_filter." ")->result_array();

		$get = $this->input->get();
		unset($get['p']);
		$nopage_get = http_build_query($get);

		$sum_grade_total = $this->_db('iwide_r1')->query("select SUM(grade_total) as sum_grade_total from iwide_v_report_distribute_all where ".$inter_id_filter." ")->result_array();


		$qfpage = qfpage3($count[0]['count'], 20, $p, 'index?p={p}&'.$nopage_get);

        $staff_info=$this->db->query("SELECT `qrcode_id`,`master_dept` FROM `iwide_hotel_staff` WHERE `inter_id`='{$inter_id}'")->result_array();

        foreach($staff_info as $arr){

            $staff_list[$arr['qrcode_id']] = $arr['master_dept'];

        }

        $web_orderId = $this->db->query("SELECT `orderid`,`web_orderid` FROM `iwide_hotel_order_additions` WHERE `inter_id`='{$inter_id}'")->result_array();
        $web_orderId_list=array();

        foreach ($web_orderId as $arr) {

            if(!empty($arr['web_orderid'])){
                $web_orderId_list[$arr['orderid']] = $arr['web_orderid'];
            }else{
                $web_orderId_list[$arr['orderid']] = 0;
            }
        }

		$datalist = $this->_db('iwide_r1')->query("select * from iwide_v_report_distribute_all where ".$inter_id_filter." order by id desc limit ".$qfpage['limit']." ")->result_array();


		$export = $this->input->post('export');
		if ($export==1) {
			set_time_limit(0);
			ini_set ('memory_limit', '1280M');
			$exportlist = $this->_db('iwide_r1')->query(
                "select
                    t1.id,t1.saler,t1.hotel_name,t1.staff_name,t1.cellphone,t1.product,t1.order_amount,t1.grade_total,t1.grade_amount,t1.status,t1.order_id,t1.grade_time,t2.web_orderid
                from
                    iwide_v_report_distribute_all as t1,
                    iwide_hotel_order_additions as t2
                where
                    t1.order_id = t2.orderid
                AND
                    t1.".$inter_id_filter." order by t1.id desc")->result_array();



			foreach ($exportlist as $v) {
				$v['cellphone'] = "'".$v['cellphone'];
				$exportlist_new[] = $v;
			}
			unset($exportlist);
			$exportlist = $exportlist_new;




			$item_title = array('ID','分销号','酒店名','分销员','电话','产品','订单金额','绩效金额','计算金额','状态(1:未发放,2:已发放,3:发放失败)','订单号1','时间','订单号2');
			qfexport($exportlist,$item_title);
			die();
		}




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
				'attribute_items'=>$model->attribute_items(),
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort,
				'qfpage'=> $qfpage,
				'condition'=>$condition,
				'count'=>$count[0]['count'],
				'csrf'=>array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash()),
				'sum_grade_total'=>$sum_grade_total[0]['sum_grade_total'],
				'datalist'=>$datalist,
                'staff_info'=>$staff_list,
                'orderId_list'=>$web_orderId_list
		);

		$view_params= $view_params+ $viewdata;

		$html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
		//echo $html;die;
		echo $html;

	}


	public function member()
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

		$inter_id_filter = $inter_id_filter." and is_distributed=1";

		$p = $this->input->get('p');
		$p = intval($p);
		$p = $p>0?$p:1;

		$timeup = $this->input->get('timeup');
		if ($timeup) {
			$inter_id_filter = $inter_id_filter." and grade_time<'".$timeup." 23:59:59'";
			$condition['timeup'] = date('Y-m-d',strtotime($timeup));
		}
		else {
			$inter_id_filter = $inter_id_filter." and grade_time<'".date('Y-m-d')." 23:59:59'";
			$condition['timeup'] = date('Y-m-d');
		}

		$timedown = $this->input->get('timedown');
		if ($timedown) {
			$inter_id_filter = $inter_id_filter." and grade_time>='".$timedown." 00:00:00'";
			$condition['timedown'] = date('Y-m-d',strtotime($timedown));
		}
		else {
			$inter_id_filter = $inter_id_filter." and grade_time>='".date('Y-m-d',strtotime("-1 month"))." 00:00:00'";
			$condition['timedown'] = date('Y-m-d',strtotime("-1 month"));
		}

		$condition['paystatus'] = '';
		$paystatus = $this->input->get('paystatus');
		if ($paystatus) {
			$inter_id_filter = $inter_id_filter." and status='".$paystatus."'";
			$condition['paystatus'] = $paystatus;
		}

		$condition['staff_id'] = '';
		$staff_id = $this->input->get('staff_id');
		if ($staff_id) {
			$inter_id_filter = $inter_id_filter." and id='".$staff_id."'";
			$condition['staff_id'] = $staff_id;
		}

		$condition['staff_name'] = '';
		$staff_name = $this->input->get('staff_name');
		if ($staff_name) {
			$inter_id_filter = $inter_id_filter." and staff_name='".$staff_name."'";
			$condition['staff_name'] = $staff_name;
		}

		$condition['grade_table'] = '';
		$grade_table = $this->input->get('grade_table');
		if ($grade_table) {
			$inter_id_filter = $inter_id_filter." and grade_table='".$grade_table."'";
			$condition['grade_table'] = $grade_table;
		}

		$condition['saler'] = '';
		$saler = $this->input->get('saler');
		if ($saler) {
			$inter_id_filter = $inter_id_filter." and saler='".$saler."'";
			$condition['saler'] = $saler;
		}

		$condition['hotel_name'] = '';
		$hotel_name = $this->input->get('hotel_name');
		if ($hotel_name) {
			$inter_id_filter = $inter_id_filter." and hotel_name='".$hotel_name."'";
			$condition['hotel_name'] = $hotel_name;
		}

		$count = $this->_db('iwide_r1')->query("select count(0) as count from iwide_v_report_distribute where ".$inter_id_filter." ")->result_array();

		$get = $this->input->get();
		unset($get['p']);
		$nopage_get = http_build_query($get);

		$sum_grade_total = $this->_db('iwide_r1')->query("select SUM(grade_total) as sum_grade_total from iwide_v_report_distribute where ".$inter_id_filter." ")->result_array();


		$qfpage = qfpage3($count[0]['count'], 20, $p, 'member?p={p}&'.$nopage_get);

		$datalist = $this->_db('iwide_r1')->query("select * from iwide_v_report_distribute where ".$inter_id_filter." order by id desc limit ".$qfpage['limit']." ")->result_array();


		$export = $this->input->post('export');
		if ($export==1) {
			set_time_limit(0);
			ini_set ('memory_limit', '1280M');
			$exportlist = $this->_db('iwide_r1')->query("select id,saler,hotel_name,staff_name,cellphone,product,order_amount,grade_total,grade_amount,status,order_id,grade_time from iwide_v_report_distribute where ".$inter_id_filter." order by id desc")->result_array();

			$item_title = array('ID','分销号','酒店名','分销员','电话','产品','订单金额','绩效金额','计算金额','状态','订单号','时间');
			qfexport($exportlist,$item_title);
			die();
		}


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
				'attribute_items'=>$model->attribute_items(),
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort,
				'qfpage'=> $qfpage,
				'condition'=>$condition,
				'count'=>$count[0]['count'],
				'csrf'=>array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash()),
				'sum_grade_total'=>$sum_grade_total[0]['sum_grade_total'],
				'datalist'=>$datalist
		);

		$view_params= $view_params+ $viewdata;

		$html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
		//echo $html;die;
		echo $html;

	}


	public function tpic()
	{
		$inter_id= $this->session->get_admin_inter_id();

		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );

		if($inter_id== FULL_ACCESS) $inter_id_filter = '1';
		else if($inter_id) $inter_id_filter = 'inter_id = "'.$inter_id.'"';
		else $inter_id_filter = 'inter_id = "deny"';

		$timeup = $this->input->get('timeup');
		if ($timeup) {
			$inter_id_filter = $inter_id_filter." and grade_time<'".$timeup." 23:59:59'";
			$condition['timeup'] = date('Y-m-d',strtotime($timeup));
		}
		else {
			$inter_id_filter = $inter_id_filter." and grade_time<'".date('Y-m-d')." 23:59:59'";
			$condition['timeup'] = date('Y-m-d');
		}

		$timedown = $this->input->get('timedown');
		if ($timedown) {
			$inter_id_filter = $inter_id_filter." and grade_time>='".$timedown." 00:00:00'";
			$condition['timedown'] = date('Y-m-d',strtotime($timedown));
		}
		else {
			$inter_id_filter = $inter_id_filter." and grade_time>='".date('Y-m-d',strtotime("-1 month"))." 00:00:00'";
			$condition['timedown'] = date('Y-m-d',strtotime("-1 month"));
		}


		$count = $this->_db('iwide_r1')->query("select count(0) as count,hotel_name from iwide_v_report_distribute where ".$inter_id_filter." GROUP BY hotel_name")->result_array();
		foreach ($count as $v) {
			if (!$v['hotel_name']) {
				$v['hotel_name'] = '未命名酒店';
			}
			$legend[] = $v['hotel_name'];
			$series[] = array('value'=>$v['count'],'name'=>$v['hotel_name']);
		}

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
				'condition'=>$condition,
				'legend'=>json_encode($legend),
				'series'=>json_encode($series)
		);

		$view_params= $view_params+ $viewdata;

		$html= $this->_render_content($this->_load_view_file('tpic'), $view_params, TRUE);
		//echo $html;die;
		echo $html;

	}

	public function comprehensive() {
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

		/////////////////////////////////////////////////////////////////////////这里含测试数据
		if ($_SERVER['HTTP_HOST']=='admin.iwide.cn') {
			$year_test = '2014';
		}
		else {
			$year_test = 'Y';
		}



		//今日实时订单总数
		$filter_order = $inter_id_filter." and grade_time>'".date($year_test."-m-d 00:00:00")."'";
		$all_order = $model->dis_count($filter_order);//所有订单

		//绩效订单
		$filter_order = $inter_id_filter." and grade_total>0 and grade_time>'".date($year_test."-m-d 00:00:00")."'";
		$all_order_grade_yes = $model->dis_count($filter_order);//所有订单

		//绩效金额
		$filter_order = $inter_id_filter." and grade_total>0 and grade_time>'".date($year_test."-m-d 00:00:00")."'";
		$all_order_grade_sum = $model->dis_sum($filter_order,'grade_total');//所有订单

		//订单金额
		$filter_order = $inter_id_filter." and grade_total>0 and grade_time>'".date($year_test."-m-d 00:00:00")."'";
		$all_order_sum = $model->dis_sum($filter_order,'order_amount');//所有订单

		//print_r($all_order_grade_sum);


		//按时间段
		$order_time = array();
		for ($i = 0; $i < 24; $i++) {
			//$filter_order = $inter_id_filter." and grade_time>='".date("Y-m-d ".$i.":00:00")."' and grade_time<'".date("Y-m-d ".($i+1).":00:00")."'";
			$filter_order = $inter_id_filter." and grade_time BETWEEN '".date("Y-m-d ".$i.":00:00")."' and '".date("Y-m-d ".($i+1).":00:00")."'";
			$all_order_time = $model->dis_count($filter_order);//所有订单
			array_push($order_time,$all_order_time[0]['count']);
		}

		//热销商品排行
		$filter_order = $inter_id_filter." and grade_time>'".date("2010-m-d 00:00:00")."'";
		/////////////////////////////////////////////////////////////////////////这里含测试数据
		$dis_hot_count = $model->dis_hot_count_1($filter_order,'count');

		//分销商品排行
		$filter_order = $inter_id_filter." and grade_time>'".date("2010-m-d 00:00:00")."'";
		/////////////////////////////////////////////////////////////////////////这里含测试数据
		$dis_amount_count = $model->dis_hot_count_1($filter_order,'all_grade_total');

		foreach ($dis_amount_count as $v) {

			$filter_order_distinct = $inter_id_filter." and product='".$v['product']."' and grade_time>'".date("2010-m-d 00:00:00")."'";
			/////////////////////////////////////////////////////////////////////////这里含测试数据
			$v['count_saler'] = $model->dis_distinct($filter_order_distinct,'saler');
			$dis_amount_count_new[] = $v;
		}
		unset($dis_amount_count);
		$dis_amount_count = $dis_amount_count_new;

		$view_params= array(
				'module'=> $this->module,
				'model'=> $model,
				'attribute_items'=>$model->attribute_items(),
				'fields_config'=> $fields_config,
				'all_order'=>$all_order,

				'all_order_grade_sum'=>$all_order_grade_sum,
				'all_order_sum'=>$all_order_sum,

				'all_order_grade_yes'=>$all_order_grade_yes,
				'order_time'=>$order_time,
				'dis_hot_count'=>$dis_hot_count,
				'dis_amount_count'=>$dis_amount_count,
				'default_sort'=> $default_sort,
		);

		$view_params= $view_params+ $viewdata;

		$html= $this->_render_content($this->_load_view_file('comprehensive'), $view_params, TRUE);
		//echo $html;die;
		echo $html;

	}

	public function saler() {
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

		$inter_id_filter_nohotel_id = "inter_id = 'deny'";
		if($inter_id== FULL_ACCESS) $inter_id_filter = "inter_id = 'deny'";///////////////////////管理员无权
		else if($inter_id){
			$inter_id_filter_nohotel_id =  $inter_id_filter = "inter_id = '".$inter_id."'";
			$inter_id_filter = "inter_id = '".$inter_id."'".$entity_filter;
		}
		else $inter_id_filter = "inter_id = 'deny'";

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

		/////////////////////////////////////////////////////////////////////////这里含测试数据
		if ($_SERVER['HTTP_HOST']=='admin.iwide.cn') {
			$year_test = '2014';
		}
		else {
			$year_test = 'Y';
		}

		$today = date($year_test."-m-d 00:00:00");


		//分销人员总数
		$all_saler= $model->staff_count($inter_id_filter);

		//粉丝总数
		$all_fans_log= $model->fans_log_count($inter_id_filter_nohotel_id);

		//今日新增
		$today_fans_log= $model->fans_log_count($inter_id_filter_nohotel_id." and event_time>'".$today."'");

		//关注状态
		$today_fans_log_2= $model->fans_log_count($inter_id_filter_nohotel_id." and event_time>'".$today."' and event=2");

		//取消关注
		$today_fans_log_1= $model->fans_log_count($inter_id_filter_nohotel_id." and event_time>'".$today."' and event<>2");

		//数据图表
		for ($i = 0; $i < 24; $i++) {
			$hour = date($year_test."-m-d ".$i.":00:00");$hour1 = date($year_test."-m-d ".($i+1).":00:00");
			$fans_log_pic = $model->fans_log_count($inter_id_filter_nohotel_id." and event_time>='".$hour."' and event_time<'".$hour1."'");
			$hour_data[] = $fans_log_pic[0]['count'];
		}

		//echo $inter_id_filter_nohotel_id;

		//男妇比例
		$inter_id_sex = str_replace('inter_id', 'iwide_fans.inter_id', $inter_id_filter_nohotel_id);
		$fans_log_sex[1] = $model->staff_sex($inter_id_sex." and iwide_fans.sex='1'");
		$fans_log_sex[2] = $model->staff_sex($inter_id_sex." and iwide_fans.sex='2'");

		$inter_id_order_sex = str_replace('inter_id', 'iwide_fans.inter_id', $inter_id_filter_nohotel_id);
		$staff_order_sex[1] = $model->staff_order_sex($inter_id_order_sex." and iwide_fans.sex='1'");
		$staff_order_sex[2] = $model->staff_order_sex($inter_id_order_sex." and iwide_fans.sex='2'");

		//print_r($staff_order_sex);
		//echo $inter_id_filter;

		////echo '<br><Br>';
		//echo $inter_id_filter_nohotel_id;


		$view_params= array(
				'module'=> $this->module,
				'model'=> $model,
				'all_saler'=>$all_saler,
				'all_fans_log'=>$all_fans_log,
				'today_fans_log'=>$today_fans_log,
				'today_fans_log_2'=>$today_fans_log_2,
				'today_fans_log_1'=>$today_fans_log_1,
				'hour_data'=>$hour_data,
				'fans_log_sex'=>$fans_log_sex,
				'staff_order_sex'=>$staff_order_sex,
				'attribute_items'=>$model->attribute_items(),
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort,
		);

		$view_params= $view_params+ $viewdata;

		$html= $this->_render_content($this->_load_view_file('saler'), $view_params, TRUE);
		//echo $html;die;
		echo $html;

	}

	public function order() {


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

		//$inter_id_filter = $inter_id_filter." and is_distributed=1";

		$p = $this->input->get('p');
		$p = intval($p);
		$p = $p>0?$p:1;

		$timeup = $this->input->get('timeup');
		if ($timeup) {
			$inter_id_filter = $inter_id_filter." and grade_time<'".$timeup." 23:59:59'";
			$condition['timeup'] = date('Y-m-d',strtotime($timeup));
		}
		else {
			$inter_id_filter = $inter_id_filter." and grade_time<'".date('Y-m-d')." 23:59:59'";
			$condition['timeup'] = date('Y-m-d');
		}

		$timedown = $this->input->get('timedown');
		if ($timedown) {
			$inter_id_filter = $inter_id_filter." and grade_time>='".$timedown." 00:00:00'";
			$condition['timedown'] = date('Y-m-d',strtotime($timedown));
		}
		else {
			$inter_id_filter = $inter_id_filter." and grade_time>='".date('Y-m-d',strtotime("-1 month"))." 00:00:00'";
			$condition['timedown'] = date('Y-m-d',strtotime("-1 month"));
		}

		$condition['paystatus'] = '';
		$paystatus = $this->input->get('paystatus');
		if ($paystatus) {
			$inter_id_filter = $inter_id_filter." and status='".$paystatus."'";
			$condition['paystatus'] = $paystatus;
		}

		$condition['order_id'] = '';
		$order_id = $this->input->get('order_id');
		if ($order_id) {
			$inter_id_filter = $inter_id_filter." and order_id='".$order_id."'";
			$condition['order_id'] = $order_id;
		}

		$condition['cellphone'] = '';
		$cellphone = $this->input->get('cellphone');
		if ($cellphone) {
			$inter_id_filter = $inter_id_filter." and cellphone='".$cellphone."'";
			$condition['cellphone'] = $cellphone;
		}

		$condition['product'] = '';
		$product = $this->input->get('product');
		if ($product) {
			$inter_id_filter = $inter_id_filter." and product='".$product."'";
			$condition['product'] = $product;
		}

		$condition['staff_id'] = '';
		$staff_id = $this->input->get('staff_id');
		if ($staff_id) {
			$inter_id_filter = $inter_id_filter." and id='".$staff_id."'";
			$condition['staff_id'] = $staff_id;
		}

		$condition['staff_name'] = '';
		$staff_name = $this->input->get('staff_name');
		if ($staff_name) {
			$inter_id_filter = $inter_id_filter." and staff_name='".$staff_name."'";
			$condition['staff_name'] = $staff_name;
		}

		$condition['grade_table'] = '';
		$grade_table = $this->input->get('grade_table');
		if ($grade_table) {
			$inter_id_filter = $inter_id_filter." and grade_table='".$grade_table."'";
			$condition['grade_table'] = $grade_table;
		}

		$condition['saler'] = '';
		$saler = $this->input->get('saler');
		if ($saler) {
			$inter_id_filter = $inter_id_filter." and saler='".$saler."'";
			$condition['saler'] = $saler;
		}

		$condition['hotel_name'] = '';
		$hotel_name = $this->input->get('hotel_name');
		if ($hotel_name) {
			$inter_id_filter = $inter_id_filter." and hotel_name='".$hotel_name."'";
			$condition['hotel_name'] = $hotel_name;
		}

		$count = $this->_db('iwide_r1')->query("select count(0) as count from iwide_v_report_distribute where ".$inter_id_filter." ")->result_array();

		$get = $this->input->get();
		unset($get['p']);
		$nopage_get = http_build_query($get);

		$sum_grade_total = $this->_db('iwide_r1')->query("select SUM(grade_total) as sum_grade_total from iwide_v_report_distribute where ".$inter_id_filter." ")->result_array();


		$qfpage = qfpage3($count[0]['count'], 20, $p, 'member?p={p}&'.$nopage_get);

		$datalist = $this->_db('iwide_r1')->query("select * from iwide_v_report_distribute where ".$inter_id_filter." order by id desc limit ".$qfpage['limit']." ")->result_array();

		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();
		}

		$export = $this->input->post('export');
		if ($export==1) {
			set_time_limit(0);
			ini_set ('memory_limit', '1280M');
			$exportlist = $this->_db('iwide_r1')->query("select id,saler,hotel_name,staff_name,cellphone,product,order_amount,grade_total,grade_amount,status,order_id,grade_time from iwide_v_report_distribute where ".$inter_id_filter." order by id desc")->result_array();

			$item_title = array('ID','分销号','酒店名','分销员','电话','产品','订单金额','绩效金额','计算金额','状态','订单号','时间');
			qfexport($exportlist,$item_title);
			die();
		}


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
				'attribute_items'=>$model->attribute_items(),
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort,
				'qfpage'=> $qfpage,
				'condition'=>$condition,
				'count'=>$count[0]['count'],
				'csrf'=>array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash()),
				'sum_grade_total'=>$sum_grade_total[0]['sum_grade_total'],
				'datalist'=>$datalist
		);

		$view_params= $view_params+ $viewdata;

		$html= $this->_render_content($this->_load_view_file('order'), $view_params, TRUE);
		//echo $html;die;
		echo $html;



	}


	public function salerdata() {
		$inter_id= $this->session->get_admin_inter_id();

		///////////////////////////////////////////////////////调试数据
		$get_inter_id = $this->input->get('inter_id');
		if ($get_inter_id) {
			$inter_id = $get_inter_id;
		}
		else {
			if ($_SERVER['HTTP_HOST']=='admin.iwide.cn') {
				$inter_id = 'a441624001';
			}

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
			//$entity_filter = " and hotel_id in (".$entity_id.") ";
		}

		$inter_id_filter_nohotel_id = "inter_id = 'deny'";
		if($inter_id== FULL_ACCESS) $inter_id_filter = "inter_id = 'deny'";///////////////////////管理员无权
		else if($inter_id){
			$inter_id_filter_nohotel_id =  $inter_id_filter = "inter_id = '".$inter_id."'";
			$inter_id_filter = "inter_id = '".$inter_id."'".$entity_filter;
		}
		else $inter_id_filter = "inter_id = 'deny'";

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

		/////////////////////////////////////////////////////////////////////////这里含测试数据
		if ($_SERVER['HTTP_HOST']=='admin.iwide.cn') {
			$year_test = '2014';
		}
		else {
			$year_test = 'Y';
		}

		$today = date($year_test."-m-d 00:00:00");


		$get = $this->input->get();
		unset($get['p']);
		$nopage_get = http_build_query($get);


		$p = $this->input->get('p');
		$p = intval($p);
		$p = $p>0?$p:1;


		//条件开始
		$condition['saler'] = '';
		$saler = $this->input->get('saler');
		if ($saler) {
			$inter_id_filter = $inter_id_filter." and saler='".$saler."'";
			$condition['saler'] = $saler;
		}
		$condition['name'] = '';
		$name = $this->input->get('name');
		if ($name) {
			$inter_id_filter = $inter_id_filter." and name='".$name."'";
			$condition['name'] = $name;
		}
		$condition['sex'] = '';
		$sex = $this->input->get('sex');
		if ($sex) {
			$inter_id_filter = $inter_id_filter." and sex='".$sex."'";
			$condition['sex'] = $sex;
		}
		$condition['hotel_name'] = '';
		$hotel_name = $this->input->get('hotel_name');
		if ($hotel_name) {
			$inter_id_filter = $inter_id_filter." and hotel_name='".$hotel_name."'";
			$condition['hotel_name'] = $hotel_name;
		}
		$condition['master_dept'] = '';
		$master_dept = $this->input->get('master_dept');
		if ($master_dept) {
			$inter_id_filter = $inter_id_filter." and master_dept='".$master_dept."'";
			$condition['master_dept'] = $master_dept;
		}
		$condition['income'] = '';
		$income = $this->input->get('income');
		if ($income) {
			$inter_id_filter = $inter_id_filter." and income='".$income."'";
			$condition['income'] = $income;
		}
		$condition['cellphone'] = '';
		$cellphone = $this->input->get('cellphone');
		if ($income) {
			$inter_id_filter = $inter_id_filter." and cellphone='".$cellphone."'";
			$condition['cellphone'] = $cellphone;
		}

		$inter_id_filter = str_replace('inter_id', 'iwide_hotel_staff.inter_id', $inter_id_filter);

		$saler_data = $model->salers_data($inter_id_filter,$p,'salerdata?p={p}&'.$nopage_get);


		$view_params= array(
				'module'=> $this->module,
				'model'=> $model,
				'saler_data'=>$saler_data,
				'condition'=>$condition,
				'attribute_items'=>$model->attribute_items(),
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort,
		);

		$view_params= $view_params+ $viewdata;

		$html= $this->_render_content($this->_load_view_file('salerdata'), $view_params, TRUE);
		//echo $html;die;
		echo $html;

	}


}
