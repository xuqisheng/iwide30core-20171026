<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Financeexp extends MY_Admin {

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
		return 'report/Financeexp_model';
	}
	
	public function grid()
	{
		$viewdata = array();
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		
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
			$inter_id_filter = "inter_id='".$get_inter_id."'";
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
				$inter_id_filter = $inter_id_filter." and order_time<'".strtotime($timeup.' 23:59:59')."'";
				$condition['timeup'] = date('Y-m-d',strtotime($timeup));
			}
			else {
				$inter_id_filter = $inter_id_filter." and order_time<'".strtotime(date('Y-m-d').' 23:59:59')."'";
				$condition['timeup'] = date('Y-m-d');
			}
			$timedown = $this->input->get('timedown');
			if ($timedown) {
				$inter_id_filter = $inter_id_filter." and order_time>='".strtotime($timedown)."'";
				$condition['timedown'] = date('Y-m-d',strtotime($timedown));
			}
			else {
				$inter_id_filter = $inter_id_filter." and order_time>='".strtotime(date('Y-m-d'))."'";
				$condition['timedown'] = date('Y-m-d');
			}
		}
		elseif($condition['time_type']==2) {  //入住时间
			$timeup = $this->input->get('timeup');
			$timeup = date('Ymd',strtotime($timeup));
			if ($timeup) {
				$inter_id_filter = $inter_id_filter." and startdate<='".$timeup."'";
				$condition['timeup'] = date('Y-m-d',strtotime($timeup));
			}
			else {
				$inter_id_filter = $inter_id_filter." and startdate<='".strtotime(date('Ymd'))."'";
				$condition['timeup'] = date('Y-m-d');
			}
			$timedown = $this->input->get('timedown');
			$timedown = date('Ymd',strtotime($timedown));
			if ($timedown) {
				$inter_id_filter = $inter_id_filter." and startdate>='".$timedown."'";
				$condition['timedown'] = date('Y-m-d',strtotime($timedown));
			}
			else {
				$inter_id_filter = $inter_id_filter." and startdate>='".strtotime(date('Ymd'))."'";
				$condition['timedown'] = date('Y-m-d');
			}
		}elseif($condition['time_type']==3){  //离店时间
            $timeup = $this->input->get('timeup');
            $timeup = date('Ymd',strtotime($timeup));
            if ($timeup) {
                $inter_id_filter = $inter_id_filter." and enddate<='".$timeup."'";
                $condition['timeup'] = date('Y-m-d',strtotime($timeup));
            }
            else {
                $inter_id_filter = $inter_id_filter." and enddate<='".strtotime(date('Ymd'))."'";
                $condition['timeup'] = date('Y-m-d');
            }
            $timedown = $this->input->get('timedown');
            $timedown = date('Ymd',strtotime($timedown));
            if ($timedown) {
                $inter_id_filter = $inter_id_filter." and enddate>='".$timedown."'";
                $condition['timedown'] = date('Y-m-d',strtotime($timedown));
            }
            else {
                $inter_id_filter = $inter_id_filter." and enddate>='".strtotime(date('Ymd'))."'";
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
                (startdate>='".date('Ymd',$timedown)."' AND startdate<='".date('Ymd',$timeup)."')
            OR (enddate>='".date('Ymd',$timedown)."' AND enddate<='".date('Ymd',$timeup)."')
            OR (startdate<='".date('Ymd',$timedown)."' AND enddate>='".date('Ymd',$timedown)."' AND startdate<='".date('Ymd',$timeup)."' AND enddate>='".date('Ymd',$timeup)."')
            )";


        }
		
		
		$condition['o_id'] = '';
		$o_id = $this->input->get('o_id');
		if ($o_id) {
			$inter_id_filter = $inter_id_filter." and id='".$o_id."'";
			$condition['o_id'] = $o_id;
		}
		
		$condition['orderid'] = '';
		$orderid = $this->input->get('orderid');
		if ($orderid) {
			$inter_id_filter = $inter_id_filter." and orderid='".$orderid."'";
			$condition['orderid'] = $orderid;
		}
		
		$condition['tel'] = '';
		$tel = $this->input->get('tel');
		if ($tel) {
			$inter_id_filter = $inter_id_filter." and tel='".$tel."'";
			$condition['tel'] = $tel;
		}
		
		$condition['member_no'] = '';
		$member_no = $this->input->get('member_no');
		if ($member_no) {
			$inter_id_filter = $inter_id_filter." and member_no='".$member_no."'";
			$condition['member_no'] = $member_no;
		}
		
		$condition['pay_type'] = '';
		$pay_type = $this->input->get('pay_type');
		if ($pay_type) {
			$inter_id_filter = $inter_id_filter." and paytype='".$pay_type."'";
			$condition['pay_type'] = $pay_type;
		}
		
		$condition['pay_status'] = '';
		$pay_status = $this->input->get('pay_status');
		if (strlen($pay_status)>0) {
			$inter_id_filter = $inter_id_filter." and paid='".$pay_status."'";
			$condition['pay_status'] = $pay_status;
		}
		
		$condition['name'] = '';
		$name = $this->input->get('name');
		if ($name) {
			$inter_id_filter = $inter_id_filter." and name='".$name."'";
			$condition['name'] = $name;
		}
		
		$condition['hotel_name'] = '';
		$hotel_name = $this->input->get('hotel_name');
		if ($hotel_name) {
			$inter_id_filter = $inter_id_filter." and hotel_name='".$hotel_name."'";
			$condition['hotel_name'] = $hotel_name;
		}
		
		$condition['roomname'] = '';
		$roomname = $this->input->get('roomname');
		if ($roomname) {
			$inter_id_filter = $inter_id_filter." and roomname='".$roomname."'";
			$condition['roomname'] = $roomname;
		}
		
		$condition['status'] = '';
		$status = $this->input->get('status');
		if (strlen($status)>0) {
			$inter_id_filter = $inter_id_filter." and status='".$status."'";
			$condition['status'] = $status;
		}
		
		$condition['hotel_id'] = '';
		$hotel_id = $this->input->get('hotel_id');
		if ($hotel_id) {
			$inter_id_filter = $inter_id_filter." and hotel_id='".$hotel_id."'";
			$condition['hotel_id'] = $hotel_id;
		}
		/////////////////////////////
		
		$count = $this->_db('iwide_r1')->query("select count(0) as count from iwide_v_report_orders where ".$inter_id_filter." ")->result_array();
		$sum_price = $this->_db('iwide_r1')->query("select SUM(iprice) as iprice from iwide_v_report_orders where ".$inter_id_filter." ")->result_array();
		
		
		$qfpage = qfpage3($count[0]['count'], 20, $p, 'index?p={p}&'.$nopage_get);
		
		$datalist = $this->_db('iwide_r1')->query("select * from iwide_v_report_orders where ".$inter_id_filter."  order by id desc limit ".$qfpage['limit']." ")->result_array();

        $new_detail=array();

        foreach($datalist as $arr){

             $oprice=$model->sumAllPrice($arr['allprice']);

            $arr['oprice']=$oprice;

            $new_detail[]=$arr;

        }

        $datalist=$new_detail;


		$o_status = $this->_db('iwide_r1')->query("select * from iwide_enum_desc where type='HOTEL_ORDER_STATUS'")->result_array();
		$o_pay_status = $this->_db('iwide_r1')->query("select * from iwide_enum_desc where type='HOTEL_ORDER_PAY_STATUS'")->result_array();
		$o_pay_way = $this->_db('iwide_r1')->query("select * from iwide_enum_desc where type='PAY_WAY'")->result_array();
		foreach ($o_status as $v) {
			$n_status[$v['code']]=$v['des'];
		}
		foreach ($o_pay_status as $v) {
			$n_pay_status[$v['code']]=$v['des'];
		}
		foreach ($o_pay_way as $v) {
			$n_pay_way[$v['code']]=$v['des'];
		}
		$condition['o_status'] = $n_status;
		$condition['o_pay_status'] = $n_pay_status;
		$condition['o_pay_way'] = $n_pay_way;
		
		$export = $this->input->post('export');
		if ($export==1) {
			set_time_limit(0);
			ini_set ('memory_limit', '1280M');
			
			$exportlist = $this->_db('iwide_r1')->query("select id,name,tel,roomnums,allprice,iprice,orderid,webs_orderid,paytype,paid,hotel_name,roomname,startdate,enddate,istatus,order_time from iwide_v_report_orders where ".$inter_id_filter."  order by id desc")->result_array();

			$exportlist_new = array();
			foreach ($exportlist as $v) {
				//$v['order_time'] = date('Y-m-d H:i:s',$v['order_time']);
				$v['paid'] = $n_pay_status[$v['paid']];
				$v['istatus'] = $n_status[$v['istatus']];
				$orderidold = $v['orderid'];
				$v['orderid'] = "'".$v['orderid'];
                $v['webs_orderid'] = "'".$v['webs_orderid'];
				$v['tel'] = "'".$v['tel'];
				$v['paytype'] = $n_pay_way[$v['paytype']];
				
				$v['adddate'] = date('Y-m-d',$v['order_time']);
				$v['addtime'] = date('H:i:s',$v['order_time']);
				unset($v['order_time']);
				
				$v['price_code_name'] = '';
				$price_code_name_new = $model->get_price_code_name($orderidold);
				if ($price_code_name_new) {
					$v['price_code_name'] = $price_code_name_new[0]['price_code_name'];
				}				
				unset($price_code_name_new);
				
				$v['coupon_favour'] = '';
				$order_additions_new = $model->order_additions($orderidold);
				if ($order_additions_new) {
					$v['coupon_favour'] = $order_additions_new[0]['coupon_favour'];
				}
				unset($order_additions_new);

                if(!empty($v['allprice'])){
                    $oprice=$model->sumAllPrice($v['allprice']);
                    $v['allprice']=$oprice;
                }else{
                    $v['allprice']='';
                }

				
				$exportlist_new[] = $v;
			}
			
			unset($exportlist);
			$exportlist = $exportlist_new;			
			
			$item_title = array('ID','入住人','电话','房间数','原价','总价','订单号','PMS订单号','支付方式','支付状态','酒店名','房型','入住时间','离店时间','状态','下单日期','下单时间','价格代码','优惠券金额');
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
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort,
				'qfpage'=> $qfpage,
				'condition'=>$condition,
				'csrf'=>array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash()),
				'count'=>$count[0]['count'],
				'sum_price'=>$sum_price[0]['iprice'],
				'datalist'=>$datalist
		);
		
		$view_params= $view_params+ $viewdata;
		
		$html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
		
		
	}
	
}
