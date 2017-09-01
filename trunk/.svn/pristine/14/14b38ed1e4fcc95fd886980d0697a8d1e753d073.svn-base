<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wxfinanceexp_jinjiang extends MY_Admin {

	protected $label_module= '';		//统一在 constants.php 定义
	protected $label_controller= '酒店订单量报表';		//在文件定义
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
		return 'report/Wxfinanceexp_jinjiang_model';
	}
	
	public function grid()
	{
		$viewdata = array();
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('t1.inter_id'=>$inter_id );
		else $filter= array('t1.inter_id'=>'deny' );
		
		$entity_filter = "";
		$entity_id = $this->session->get_admin_hotels();
		if ($entity_id) {
			$entity_filter = " and t1.hotel_id in (".$entity_id.") ";
		}
		
		if($inter_id== FULL_ACCESS) $inter_id_filter = '1';
		else if($inter_id) $inter_id_filter = 't1.inter_id = "'.$inter_id.'"'.$entity_filter;
		else $inter_id_filter = 't1.inter_id = "deny"';
		
		$condition = array();
		
		$p = $this->input->get('p');
		$p = intval($p);
		$p = $p>0?$p:1;
		
		$get = $this->input->get();
		unset($get['p']);
		$nopage_get = http_build_query($get);
		
		///////////////
		$condition['inter_id'] = '';
		$get_inter_id = $this->input->get('inter_id');
		if ($get_inter_id && $inter_id==FULL_ACCESS) {
			$inter_id_filter = "t1.inter_id='".$get_inter_id."'";
			$condition['inter_id'] = $get_inter_id;
		}
		
		/////////////////////////新增条件
		$condition['time_type'] = '';
		$time_type = $this->input->get('time_type');
		if ($time_type!=1) {
			$condition['time_type'] = $time_type;
		}
		
		if ($condition['time_type']=='') {   //下单时间
			$timeup = $this->input->get('timeup');
			if ($timeup) {
				$inter_id_filter = $inter_id_filter." and t1.order_time<'".strtotime($timeup.' 23:59:59')."'";
				$condition['timeup'] = date('Y-m-d',strtotime($timeup));
			}
			else {
				$inter_id_filter = $inter_id_filter." and t1.order_time<'".strtotime(date('Y-m-d').' 23:59:59')."'";
				$condition['timeup'] = date('Y-m-d');
			}
			$timedown = $this->input->get('timedown');
			if ($timedown) {
				$inter_id_filter = $inter_id_filter." and t1.order_time>='".strtotime($timedown)."'";
				$condition['timedown'] = date('Y-m-d',strtotime($timedown));
			}
			else {
				$inter_id_filter = $inter_id_filter." and t1.order_time>='".strtotime(date('Y-m-d'))."'";
				$condition['timedown'] = date('Y-m-d');
			}
		}
		elseif($condition['time_type']==2) {  //入住时间
			$timeup = $this->input->get('timeup');
			$timeup = date('Ymd',strtotime($timeup));
			if ($timeup) {
				$inter_id_filter = $inter_id_filter." and t1.startdate<='".$timeup."'";
				$condition['timeup'] = date('Y-m-d',strtotime($timeup));
			}
			else {
				$inter_id_filter = $inter_id_filter." and t1.startdate<='".strtotime(date('Ymd'))."'";
				$condition['timeup'] = date('Y-m-d');
			}
			$timedown = $this->input->get('timedown');
			$timedown = date('Ymd',strtotime($timedown));
			if ($timedown) {
				$inter_id_filter = $inter_id_filter." and t1.startdate>='".$timedown."'";
				$condition['timedown'] = date('Y-m-d',strtotime($timedown));
			}
			else {
				$inter_id_filter = $inter_id_filter." and t1.startdate>='".strtotime(date('Ymd'))."'";
				$condition['timedown'] = date('Y-m-d');
			}
		}elseif($condition['time_type']==3){  //离店时间
            $timeup = $this->input->get('timeup');
            $timeup = date('Ymd',strtotime($timeup));
            if ($timeup) {
                $inter_id_filter = $inter_id_filter." and t1.enddate<='".$timeup."'";
                $condition['timeup'] = date('Y-m-d',strtotime($timeup));
            }
            else {
                $inter_id_filter = $inter_id_filter." and t1.enddate<='".strtotime(date('Ymd'))."'";
                $condition['timeup'] = date('Y-m-d');
            }
            $timedown = $this->input->get('timedown');
            $timedown = date('Ymd',strtotime($timedown));
            if ($timedown) {
                $inter_id_filter = $inter_id_filter." and t1.enddate>='".$timedown."'";
                $condition['timedown'] = date('Y-m-d',strtotime($timedown));
            }
            else {
                $inter_id_filter = $inter_id_filter." and t1.enddate>='".strtotime(date('Ymd'))."'";
                $condition['timedown'] = date('Y-m-d');
            }
        }else{    //在店时间

            $timeup = $this->input->get('timeup');
//            $timeup = date('Ymd',strtotime($timeup));
            $timedown = $this->input->get('timedown');
//            $timedown = date('Ymd',strtotime($timedown));

            if($timeup){
                $timeup = strtotime($timeup);
                $condition['timeup'] = date('Y-m-d',$timeup);
            }else{
                $timeup=strtotime(date('Ymd'));
                $condition['timeup'] = date('Y-m-d');
            }

            if($timedown){
                $timedown = strtotime($timedown);
                $condition['timedown'] = date('Y-m-d',$timedown);
            }else{
                $timeup=strtotime(date('Ymd'));
                $condition['timedown'] = date('Y-m-d');
            }


            $inter_id_filter = $inter_id_filter." and (
                (startdate>='".date('Ymd',$timedown)."' AND t1.startdate<='".date('Ymd',$timeup)."')
            OR (enddate>='".date('Ymd',$timedown)."' AND t1.enddate<='".date('Ymd',$timeup)."')
            OR (startdate<='".date('Ymd',$timedown)."' AND t1.enddate>='".date('Ymd',$timedown)."' AND t1.startdate<='".date('Ymd',$timeup)."' AND enddate>='".date('Ymd',$timeup)."')
            )";


        }


        $o_pay_status = array(
            0=>'未支付',
            1=>'已支付',
//            5=>'酒店取消',
//            4=>'用户取消',
//            11=>'系统取消',
        );


        $o_status = array(
            0=>'待确认',
            1=>'待入住',
            5=>'酒店取消',
            4=>'用户取消',
            11=>'系统取消'
        );


        if($o_status){

            foreach($o_status as $key => $arr){

                if(!isset($istatus_csv)){
                    $istatus_csv = $key;
                }else{
                    $istatus_csv = $istatus_csv.','.$key;
                }
            }

        }

        $inter_id_filter = $inter_id_filter." and t1.status in (".$istatus_csv.")";   //特定支付状态
		
		
		$condition['o_id'] = '';
		$o_id = $this->input->get('o_id');
		if ($o_id) {
			$inter_id_filter = $inter_id_filter." and t1.id='".$o_id."'";
			$condition['o_id'] = $o_id;
		}
		
		$condition['orderid'] = '';
		$orderid = $this->input->get('orderid');
		if ($orderid) {
			$inter_id_filter = $inter_id_filter." and (t1.orderid='".$orderid."' or t4.web_orderid like '%".$orderid."%')";
			$condition['orderid'] = $orderid;
		}
		
		$condition['tel'] = '';
		$tel = $this->input->get('tel');
		if ($tel) {
			$inter_id_filter = $inter_id_filter." and t1.tel='".$tel."'";
			$condition['tel'] = $tel;
		}
		
		$condition['member_no'] = '';
		$member_no = $this->input->get('member_no');
		if ($member_no) {
			$inter_id_filter = $inter_id_filter." and t1.member_no='".$member_no."'";
			$condition['member_no'] = $member_no;
		}
		
//		$condition['pay_type'] = '';
//		$pay_type = $this->input->get('pay_type');
//		if ($pay_type) {
//			$inter_id_filter = $inter_id_filter." and paytype='".$pay_type."'";
//			$condition['pay_type'] = $pay_type;
//		}
		
		$condition['pay_status'] = '';
		$pay_status = $this->input->get('pay_status');
		if (strlen($pay_status)>0) {
			$inter_id_filter = $inter_id_filter." and t1.paid='".$pay_status."'";
			$condition['pay_status'] = $pay_status;
		}
		
		$condition['name'] = '';
		$name = $this->input->get('name');
		if ($name) {
			$inter_id_filter = $inter_id_filter." and t1.name='".$name."'";
			$condition['name'] = $name;
		}
		
		$condition['hotel_name'] = '';
		$hotel_name = $this->input->get('hotel_name');
		if ($hotel_name) {
			$inter_id_filter = $inter_id_filter." and t5.name='".$hotel_name."'";
			$condition['hotel_name'] = $hotel_name;
		}
		
		$condition['roomname'] = '';
		$roomname = $this->input->get('roomname');
		if ($roomname) {
			$inter_id_filter = $inter_id_filter." and t1.roomname='".$roomname."'";
			$condition['roomname'] = $roomname;
		}
		
		$condition['status'] = '';
		$status = $this->input->get('status');
		if (strlen($status)>0) {
			$inter_id_filter = $inter_id_filter." and t1.status=".$status;
			$condition['status'] = $status;
		}
		
		$condition['hotel_id'] = '';
		$hotel_id = $this->input->get('hotel_id');
		if ($hotel_id) {
			$inter_id_filter = $inter_id_filter." and t1.hotel_id='".$hotel_id."'";
			$condition['hotel_id'] = $hotel_id;
		}
		/////////////////////////////

        $sql = "
        select
            t1.*,t4.refund,t4.coupon_favour,t4.coupon_des,t4.web_orderid,t5.name as hotel_name
        from
            iwide_hotel_orders as t1,
            iwide_hotel_order_additions as t4,
            iwide_hotels as t5
        where
            ".$inter_id_filter."
        and
            t1.paytype='weixin'
        and
            t1.orderid = t4.orderid
        and
            t1.inter_id = t5.inter_id
        and
            t1.hotel_id = t5.hotel_id
        order by
            t1.id desc
            ";


		$count = $this->db->query("select count(0) as count from (".$sql.") as t3 where 1")->result_array();
		$sum_price = $this->db->query("select SUM(t3.price) as price from (".$sql.") as t3 where 1")->result_array();


		$qfpage = qfpage3($count[0]['count'], 20, $p, 'index?p={p}&'.$nopage_get);



        $sql_all = "
            SELECT
                a.*,b.hotel_web_id
            FROM
                ({$sql}) AS a
            LEFT JOIN
                `iwide_hotel_additions` as b
            ON
                a.inter_id = b.inter_id
            AND
                a.hotel_id = b.hotel_id
        ";


		$datalist = $this->db->query($sql_all.'limit '.$qfpage['limit']." ")->result_array();

        $this->load->model("hotel/hotel_model");

        $hotel_res = $this->hotel_model->get_all_hotels($inter_id);
        $hotel_list = array();

        foreach($hotel_res as $arr){
            $hotel_list[$arr['hotel_id']]=$arr['name'];
        }


        $new_detail=array();

        foreach($datalist as $arr){

            if(!empty($arr['coupon_des'])){

                $coupon = json_decode($arr['coupon_des']);

                if(isset($coupon->cash_token)){
                    $arr['coupon_amount']=count($coupon->cash_token);
                }else{
                    $arr['coupon_amount']=0;
                }
            }else{
                    $arr['coupon_amount']=0;
            }

            $new_detail[]=$arr;

        }

        $datalist=$new_detail;


//		$o_status = $this->db->query("select * from iwide_enum_desc where type='HOTEL_ORDER_STATUS'")->result_array();
//		$o_pay_status = $this->db->query("select * from iwide_enum_desc where type='HOTEL_ORDER_PAY_STATUS'")->result_array();
		$o_pay_way = $this->db->query("select * from iwide_enum_desc where type='PAY_WAY'")->result_array();
//		foreach ($o_status as $v) {
//			$n_status[$v['code']]=$v['des'];
//		}
//		foreach ($o_pay_status as $v) {
//			$n_pay_status[$v['code']]=$v['des'];
//		}
		foreach ($o_pay_way as $v) {
			$n_pay_way[$v['code']]=$v['des'];
		}



		$condition['o_status'] = $o_status;
		$condition['o_pay_status'] = $o_pay_status;
		$condition['o_pay_way'] = $n_pay_way;


        $get_status=array();

        if(isset($_GET['status']) && $_GET['status']!=''){
            $get_status['status']=$_GET['status'];
        }

        if(isset($_GET['pay_status']) && $_GET['pay_status']!=''){
            $get_status['pay_status']=$_GET['pay_status'];
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
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort,
				'qfpage'=> $qfpage,
				'condition'=>$condition,
				'csrf'=>array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash()),
				'count'=>$count[0]['count'],
				'sum_price'=>$sum_price[0]['price'],
				'datalist'=>$datalist,
                'get_status'=>$get_status,
                'hotel_list'=>$hotel_list
		);
		
		$view_params= $view_params+ $viewdata;
		
		$html= $this->_render_content($this->_load_view_file('wxfinanceexp/grid_jj'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
		
		
	}
	
}
