<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Coupons extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '优惠券配置';
	protected $label_action = '';
	protected $common_data = array ();
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'hotel';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		$this->common_data ['inter_id'] = $this->inter_id;
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'hotel/Coupons_model';
	}
	public function userule() {
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$list = $model->get_userule_list ( $this->inter_id );
		$data ['fields_config'] = $model->userule_fields_config ();
		$data ['list'] = $list;
		$this->load->model('hotel/Hotel_log_model');
		$this->Hotel_log_model->add_admin_log('hotel_coupon_urules','check','');
		$this->_render_content ( $this->_load_view_file ( 'userule' ), $data, false );
	}
	public function ur_edit() {
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$rule_id = $this->input->get ( 'rid' );
		$this->load->model ( 'hotel/Member_new_model' );
		$data ['member_levels'] = $this->Member_new_model->getAllMemberLevels ( $this->inter_id );
		if (! empty ( $rule_id )) {
			$data ['list'] = $model->get_userule ( $this->inter_id, $rule_id );
			if (empty ( $data ['list'] )) {
				redirect ( site_url ( 'hotel/coupons/userule' ) );
			}
			$this->load->model('hotel/Hotel_log_model');
			$this->Hotel_log_model->add_admin_log('hotel_coupon_urules#'.$rule_id,'edit');
			$coupon_types = $model->get_hotel_coupons ( $this->inter_id, $data ['list'] ['rule_type'] );
		} else {
			$coupon_types = $model->get_hotel_coupons ( $this->inter_id, 'voucher' );
			$data ['list'] = $model->userule_table_fields ();
			$this->load->model('hotel/Hotel_log_model');
			$this->Hotel_log_model->add_admin_log('hotel_coupon_urules','to_add');
		}
		$this->load->model ( 'pay/Pay_model' );
		$data ['pay_ways'] = $this->Pay_model->get_pay_way ( array (
				'inter_id' => $this->inter_id,
				'module' => $this->module,
				'status' => 1,
				'key' => 'value'
		) );
		$data ['coupon_types'] = $coupon_types ['coupon_types'];
		$this->_render_content ( $this->_load_view_file ( 'ur_edit' ), $data, false );
	}
	public function save_userule() {
		$data = $this->input->post ();
		$this->load->helper('array');
		$data=jqjson2arr(json_decode($data['datas'],TRUE));
		$rule = array ();
		$model = $this->_load_model ( $this->main_model_name () );
		$info = array (
				'status' => 2,
				'message' => 'error' 
		);
		if (empty ( $model->rule_types [$data ['rule_type']] )) {
			$info ['message'] = '卡券类型错误';
			exit ( json_encode ( $info ) );
		}
		if (empty ( $data ['coupon_ids'] )) {
			$info ['message'] = '没选择卡券';
			exit ( json_encode ( $info ) );
		}
		if (empty ( $data ['rule_name'] )) {
			$info ['message'] = '请填写规则名';
			exit ( json_encode ( $info ) );
		}
		$coupon_types = $model->get_hotel_coupons ( $this->inter_id, $data ['rule_type'] );
		foreach ( $data ['coupon_ids'] as $cid ) {
			if (! array_key_exists ( $cid, $coupon_types ['coupon_types'] )) {
				$info ['message'] = '卡券错误';
				exit ( json_encode ( $info ) );
			}
		}
		$rule ['rule_type'] = $data ['rule_type'];
		$rule ['rule_name'] = $data ['rule_name'];
		$rule ['coupon_ids'] = '|'.implode ( '|', $data ['coupon_ids'] ).'|';
		// 其他规则
		$rule ['extra_rule'] = array ();
		if ($data ['member_level'] === 'part' && !empty($data ['levels'])) {
			$rule ['extra_rule'] ['level'] = $data ['levels'];
		} else {
			$rule ['extra_rule'] ['level'] = array ();
		}
		if ($data ['paytype'] === 'part' && !empty($data ['pay_ways'])) {
			$rule ['extra_rule'] ['paytype'] = $data ['pay_ways'];
		} else {
			$rule ['extra_rule'] ['paytype'] = array ();
		}
		
		if(!empty($data['min_money'])){
			$min_money=intval($data['min_money']);
			if ($min_money>0){
				$rule ['extra_rule'] ['min_money'] = $min_money;
			}else{
				$info ['message'] = '最低消费错误';
				exit ( json_encode ( $info ) );
			}
		}
		
		$rule ['extra_rule'] = json_encode ( $rule ['extra_rule'], JSON_UNESCAPED_UNICODE );
		// 限制日期
		$rule ['rule_dates'] = array ();
		if (! empty ( $data ['rule_dates_r'] ) && intval ( $data ['rule_dates_r'] ) == 2) {
			$rule ['rule_dates'] ['r'] = 2;
		} else {
			$rule ['rule_dates'] ['r'] = 1;
		}
		$rule ['rule_dates'] ['d'] = array ();
		if (! empty ( $data ['start_range'] )) {
			$count = count ( $data ['start_range'] );
			$rule ['rule_dates'] ['d'] ['d'] = '';
			for($i = 0; $i < $count; $i ++) {
				$s = '';
				if (! empty ( $data ['start_range'] [$i] )) {
					if (! strtotime ( $data ['start_range'] [$i] )) {
						$info ['message'] = '日期错误';
						exit ( json_encode ( $info ) );
					}
					$data ['start_range'] [$i] = date ( 'Ymd', strtotime ( $data ['start_range'] [$i] ) );
					$s .= $data ['start_range'] [$i];
					if (! empty ( $data ['end_range'] [$i] )) {
						if (! strtotime ( $data ['end_range'] [$i] ) || date ( 'Ymd', strtotime ( $data ['end_range'] [$i] ) ) < $data ['start_range'] [$i]) {
							$info ['message'] = '日期错误';
							exit ( json_encode ( $info ) );
						}
						if (date ( 'Ymd', strtotime ( $data ['end_range'] [$i] ) ) > $data ['start_range'] [$i]) {
							$s .= '-' . date ( 'Ymd', strtotime ( $data ['end_range'] [$i] ) );
						}
					}
					$rule ['rule_dates'] ['d'] ['d'] .= ',' . $s;
				}
			}
			if (!empty($rule ['rule_dates'] ['d'] ['d']))
				$rule ['rule_dates'] ['d'] ['d'] = substr ( $rule ['rule_dates'] ['d'] ['d'], 1 );
			else 
				unset($rule ['rule_dates'] ['d'] ['d']);
		}
		$rule ['rule_dates'] ['d'] ['r'] ['week'] = array ();
		if (! empty ( $data ['weekdays'] )) {
			foreach ( $data ['weekdays'] as $w ) {
				if (in_array ( intval ( $w ), array (
						0,
						1,
						2,
						3,
						4,
						5,
						6 
				) )) {
					$rule ['rule_dates'] ['d'] ['r'] ['week'] [] = intval ( $w );
				}
			}
			$rule ['rule_dates'] ['d'] ['r'] ['week'] = implode ( ',', $rule ['rule_dates'] ['d'] ['r'] ['week'] );
		}else {
			$rule ['rule_dates'] ['d'] ['r'] ['week'] = '';
		}
		$rule ['rule_dates'] = json_encode ( $rule ['rule_dates'], JSON_UNESCAPED_UNICODE );
		
		// 门店规则
		if (! empty ( $data ['hotel_ids'] )) {
			$rule ['hotel_rooms'] = array ();
			if ($data ['hotel_rooms'] === 'part') {
				foreach ( $data ['hotel_ids'] as $hotel_id ) {
					if (! empty ( $data ['room_ids_' . $hotel_id] )) {
						foreach ( $data ['room_ids_' . $hotel_id] as $room_id ) {
							if (! empty ( $data ['price_codes_' . $room_id] )) {
								foreach ( $data ['price_codes_' . $room_id] as $price_code ) {
									$rule ['hotel_rooms'] [$hotel_id] [$room_id] [] = $price_code;
								}
							}
						}
					}
				}
			}
			$rule ['hotel_rooms'] = json_encode ( $rule ['hotel_rooms'] );
		}
		
		$rule ['status'] = intval ( $data ['status'] ) == 1 ? 1 : 2;
		
		if (! empty ( $data ['rule_id'] )) {
			if ($model->update_userule ( $this->inter_id, $data ['rule_id'], $rule )) {
				$info ['status'] = 1;
				$info ['message'] = '保存成功';
			} else {
				$info ['message'] = '保存失败';
			}
		} else {
			if ($model->add_userule ( $this->inter_id, $rule )) {
				$info ['status'] = 10;
				$info ['message'] = '添加成功';
			} else {
				$info ['message'] = '添加失败';
			}
		}
		echo json_encode ( $info );
		exit ();
	}
	public function ur_check() {
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$rule_id = $this->input->get ( 'rid' );
		$this->load->model ( 'pay/Pay_model' );
		$data ['pay_ways'] = $this->Pay_model->get_pay_way ( array (
				'inter_id' => $this->inter_id,
				'module' => $this->module,
				'status' => 1,
				'key' => 'value' 
		) );
		$this->load->model ( 'hotel/Member_new_model' );
		$data ['member_levels'] = $this->Member_new_model->getAllMemberLevels ( $this->inter_id );
		if (! empty ( $rule_id )) {
			$data ['list'] = $model->get_userule ( $this->inter_id, $rule_id );
			$coupon_types = $model->get_hotel_coupons ( $this->inter_id, $data ['list'] ['rule_type'] );
		} else {
			$coupon_types = $model->get_hotel_coupons ( $this->inter_id, 'voucher' );
			$data ['list'] = $model->userule_table_fields ();
		}
		$data ['coupon_types'] = $coupon_types ['coupon_types'];
		$this->load->model('hotel/Hotel_log_model');
		$params=array(
				'ident'=>'hotel_coupon_urules#'.$rule_id,
				'log_des'=>'{admin_name}于{record_time}{type_des}了记录',
				'offset'=>0,
				'nums'=>20
		);
		$data['logs']=$this->Hotel_log_model->get_admin_log($this->inter_id,$params);
		$this->_render_content ( $this->_load_view_file ( 'ur_check' ), $data, false );
	}
	public function ajax_ur_hotel_rooms() {
		$rule_id = $this->input->get ( 'rid' );
		$model = $this->_load_model ( $this->main_model_name () );
		$hotel_rooms = '';
		if (! empty ( $rule_id )) {
			$check = $model->get_userule ( $this->inter_id, $rule_id );
			$hotel_rooms = empty ( $check ['hotel_rooms'] ) ? '' : $check ['hotel_rooms'];
		}
		$hotel_room_codes = $model->hotel_rooms_check ( $this->inter_id, $hotel_rooms, $this->session->admin_profile ['entity_id'] );
		if (! empty ( $hotel_room_codes )) {
			echo json_encode ( array (
					'status' => 1,
					'data' => $hotel_room_codes 
			), JSON_UNESCAPED_UNICODE );
		} else {
			echo json_encode ( array (
					'status' => 2,
					'message' => '无数据' 
			), JSON_UNESCAPED_UNICODE );
		}
	}
    public function giverule() {
        $data=$this->common_data;

        $this->load->model('hotel/Coupon_new_model','Coupon_new_model');
        $couponsTitle=$this->Coupon_new_model->allCouponsList($this->inter_id);

//        $myCoupons=$this->Coupon_new_model->myCoupons($this->inter_id);
//        print_r($myCoupons);exit;


        $this->load->model('hotel/Coupons_rules_model','rules_model');
        $title=$this->rules_model->getCouponTitle($couponsTitle);


        $data['rules']=$this->rules_model->give_rules_list($this->inter_id);
        $data['title']=$title;
        $data['label']=$this->rules_model->rules_list_labels();
        $data['status']=$this->rules_model->coupons_status();

        $this->_render_content ( $this->_load_view_file ( 'giverule' ), $data, false );
    }
    public function gr_save() {

        $data = $this->input->post ();
        $this->load->helper('array');
		$data=jqjson2arr(json_decode($data['datas'],TRUE));

        $this->load->model('hotel/Coupons_rules_model','rules_model');

        $extra_rule=array();
        $send_condition=array();
        $send_condition['num']=array();
        $rule_dates=array();
        $time_zone=array();

        $info=array(
            'code'=>1,
            'msg'=>"保存成功"
        );

        $post['rule_type']=$data['rule_type'];
        $post['status']=$data['status'];

        //规则名称
        if(empty($data['rule_name'])){
            $info['msg']='规则名称不能为空！';
            $info['code']=2;
            echo  json_encode($info);exit;
        }
        $post['rule_name']=$data['rule_name'];

        //规则内容
        if(empty($data['couponIds'])){
            $info['msg']='发放内容不能为空！';
            $info['code']=2;
            echo  json_encode($info);exit;
        }
        $post['coupon_ids']=implode(',',$data['couponIds']);


        //发放规则
        if(empty($data['nightAmount']) && empty($data['orderAmount'])){
            $info['msg']='请填写发放规则';
            $info['code']=2;
            echo  json_encode($info);exit;
        }else{
            if($data['byOrder']=='aveOrder'){
                $send_condition['num']['order']=$data['orderAmount'];
            }elseif($data['byOrder']=='aveNight'){
                $send_condition['num']['night']=$data['nightAmount'];
            }
            $post['send_condition']=$send_condition;
        }

        //发放次数
        if($data['times']==''){
            $post['trigger_times']=0;
        }else{
            $post['trigger_times']=$data['times'];
        }

        //支付方式
        if($data['byPay']=='all'){
            $paytype='';
        }else{
            if(isset($data['payType'])){
                $paytype=$data['payType'];
            }else{
                $paytype='';
            }
        }

        //会员等级
        if($data['byLevel']=='all'){
            $level='';
        }else{
            if(isset($data['levels'])){
                $level=$data['levels'];
            }else{
                $level='';
            }
//            if(is_array($data['levels'])){
//                $level=implode(',',$data['levels']);
//            }else{
//                $level=array(0=>$data['levels']);
//            }
        }


        //满消费
        if(isset($data['byAmount']) && $data['byAmount']=='byAmount' && $data['orderCost']!=''){
            $min_amount=$data['orderCost'];
        }else{
            $min_amount=0;
        }

        //满次数
        if(isset($data['byTimes']) && $data['byTimes']=='byTimes' && $data['orderTimes']!=''){
            $order_nums=$data['orderTimes'];
        }else{
            $order_nums=0;
        }

        //随机发放 1为不随机，2随机
        if(!isset($data['random'])){
            $is_random=1;
        }else{
            $is_random=$data['random'];
        }

        //其他条件
        $extra_rule=array(
            'paytype'=>$paytype,
            'level'=>$level,
            'min_amount'=>$min_amount,
            'order_nums'=>$order_nums,
            'is_random'=>$is_random,
            'random_percent'=>$data['percentage'],
            'random_amounts'=>$data['r_amounts']
        );


        //规则日期
        if(isset($data['weekdays']) && !empty($data['weekdays'])){
            $weeks=implode(',',$data['weekdays']);
        }else{
            $weeks='7';
        }

        if(isset($data['start_range'])){

            foreach($data['start_range'] as $key=>$start){

                if($start!='' && $data['end_range'][$key]!=''){
                    $from=str_replace('-','',$start);
                    $to=str_replace('-','',$data['end_range'][$key]);
                    $date[$key]=$from.'-'.$to;
                }
                if($start==''){
                    $date[$key]=str_replace('-','',$data['end_range'][$key]);
                }
                if($data['end_range'][$key]==''){
                    $date[$key]=str_replace('-','',$start);
                }
                if($start='' && $data['end_range'][$key]=''){
                    $date[$key]='';
                }
            }
        }

        if(is_array($date)){
            $time_zone=implode(',',$date);
        }else{
            $time_zone=array();
            $time_zone[]=$date;
        }


        $rule_dates=array(
            'r'=>1,
            'd'=>array(
                'd'=>$time_zone,
                'r'=>array(
                    'week'=>$weeks
                )
            )
        );

        $post['extra_rule']=json_encode($extra_rule);
        $post['send_condition']=json_encode($send_condition);
        $post['rule_dates']=json_encode($rule_dates);


        // 门店规则
        if(isset($data['hotel_rooms']) && $data['hotel_rooms']=='all'){
                $post ['hotel_rooms']='';
        }elseif(isset($data['hotel_rooms']) && $data['hotel_rooms']=='part'){
            if (! empty ( $data ['hotel_ids'] )) {
                $post ['hotel_rooms'] = array ();
                if ($data ['hotel_rooms'] === 'part') {
                    foreach ( $data ['hotel_ids'] as $hotel_id ) {
                        if (! empty ( $data ['room_ids_' . $hotel_id] )) {
                            foreach ( $data ['room_ids_' . $hotel_id] as $room_id ) {
                                if (! empty ( $data ['price_codes_' . $room_id] )) {
                                    foreach ( $data ['price_codes_' . $room_id] as $price_code ) {
                                        $post ['hotel_rooms'] [$hotel_id] [$room_id] [] = $price_code;
                                    }
                                }
                            }
                        }
                    }
                }
                $post ['hotel_rooms'] = json_encode ( $post ['hotel_rooms'] );
            }
        }


        if (! empty ( $data ['rule_id'] )) {
            $this->load->model ( 'hotel/Coupons_model' );
            $check = $this->Coupons_model->get_giverule ( $this->inter_id, $data ['rule_id'], FALSE );
            if (empty ( $check )) {
                $info ['msg'] = '规则不存在';
                exit ( json_encode ( $info ) );
            }
            if ($this->rules_model->update_giverule ( $this->inter_id, $data ['rule_id'], $post )) {
                $info ['code'] = 1;
                $info ['msg'] = '保存成功';
            } else {
                $info ['msg'] = '保存失败';
            }
        } else {

            if ($this->rules_model->add_giverule ( $this->inter_id, $post )) {
//                redirect ( site_url ( 'hotel/coupons/giverule' ) );
                $info ['code'] = 3;
                $info ['msg'] = '添加成功';
            } else {
                $info ['msg'] = '添加失败';
            }
        }


        echo  json_encode($info);
        exit();

    }
    public function gr_edit() {
        $data = $this->common_data;

        $this->load->model('hotel/Coupon_new_model','Coupon_new_model');
        $couponsTitle=$this->Coupon_new_model->allCouponsList($this->inter_id);

        $this->load->model('hotel/Member_new_model','Member_new_model');
        $allLevels=$this->Member_new_model->getAllMemberLevels($this->inter_id);


        $data['coupons']=$couponsTitle['data'];
        $data['allLevels']=$allLevels;

        $rule_id = $this->input->get ( 'rid' );
        $this->load->model ( 'pay/Pay_model' );
        $data ['pay_ways'] = $this->Pay_model->get_pay_way ( array (
            'inter_id' => $this->inter_id,
            'module' => $this->module,
            'status' => 1,
            'key' => 'value'
        ) );
        $this->load->model ( 'hotel/Coupons_rules_model' );
        $data['rule_type']=$this->Coupons_rules_model->OrderStatusField();
        $this->load->model ( 'hotel/Coupons_model' );
        $data ['member_levels'] = $this->Member_new_model->getAllMemberLevels ( $this->inter_id );
        if (! empty ( $rule_id )) {
            $data ['list'] = $this->Coupons_model->get_giverule ( $this->inter_id, $rule_id );
            if (empty ( $data ['list'] )) {
                redirect ( site_url ( 'hotel/coupons/giverule' ) );
            }
            $coupon_types = $this->Coupons_model->get_hotel_coupons ( $this->inter_id, $data ['list'] ['rule_type'] );
        } else {
            $coupon_types = $this->Coupons_model->get_hotel_coupons ( $this->inter_id, '' );
        }
        $data ['coupon_types'] = $coupon_types ['coupon_types'];

        if(!empty($data['list']['send_condition'])){
            $data ['send_condition']=json_decode($data['list']['send_condition']);
        }else{
            $data ['send_condition']='';
        }

        $this->_render_content ( $this->_load_view_file ( 'gr_edit' ), $data, false );
    }
    public function gr_check() {
        $data = $this->common_data;
        $this->load->model('hotel/Coupon_new_model');
        $rule_id = $this->input->get ( 'rid' );
        $this->load->model ( 'pay/Pay_model' );
        $data ['pay_ways'] = $this->Pay_model->get_pay_way ( array (
            'inter_id' => $this->inter_id,
            'module' => $this->module,
            'status' => 1,
            'key' => 'value'
        ) );
        $this->load->model ( 'hotel/Member_new_model' );
        $this->load->model ( 'hotel/Coupons_rules_model' );
        $data['rule_type']=$this->Coupons_rules_model->OrderStatusField();
        $this->load->model ( 'hotel/Coupons_model' );
        $data ['member_levels'] = $this->Member_new_model->getAllMemberLevels ( $this->inter_id );
        if (! empty ( $rule_id )) {
            $data ['list'] = $this->Coupons_model->get_giverule ( $this->inter_id, $rule_id );
            $coupon_types = $this->Coupons_model->get_hotel_coupons ( $this->inter_id, $data ['list'] ['rule_type'] );
        } else {
            $coupon_types = $this->Coupons_model->get_hotel_coupons ( $this->inter_id, '' );
            $data ['list'] = $this->Member_new_model->userule_table_fields ();
        }
        $data ['coupon_types'] = $coupon_types ['coupon_types'];
        $data ['send_condition']=json_decode($data['list']['send_condition']);

        $this->load->model('hotel/Hotel_log_model');
        $params=array(
            'ident'=>'hotel_coupon_grules#'.$rule_id,
            'log_des'=>'{admin_name}于{record_time}{type_des}了记录',
			'offset'=>0,
			'nums'=>20
        );
        $data['logs']=$this->Hotel_log_model->get_admin_log($this->inter_id,$params);

        $this->_render_content ( $this->_load_view_file ( 'gr_check' ), $data, false );
    }


    public function ajax_gr_hotel_rooms() {
        $rule_id = $this->input->get ( 'rid' );
        $model = $this->_load_model ( $this->main_model_name () );
        $hotel_rooms = '';
        if (! empty ( $rule_id )) {
            $check = $model->get_giverule ( $this->inter_id, $rule_id );
            $hotel_rooms = empty ( $check ['hotel_rooms'] ) ? '' : $check ['hotel_rooms'];
        }
        $hotel_room_codes = $model->hotel_rooms_check ( $this->inter_id, $hotel_rooms, $this->session->admin_profile ['entity_id'] );
        if (! empty ( $hotel_room_codes )) {
            echo json_encode ( array (
                'status' => 1,
                'data' => $hotel_room_codes
            ), JSON_UNESCAPED_UNICODE );
        } else {
            echo json_encode ( array (
                'status' => 2,
                'message' => '无数据'
            ), JSON_UNESCAPED_UNICODE );
        }
    }

	public function tips_set(){
		$inter_id = $this->session->get_admin_inter_id();
		$this->load->model('hotel/Hotel_config_model','hcm');

		$tips_row=$this->hcm->get_hotels_config_row($inter_id,'HOTEL',0,'COUPON_TIPS');
		if(!$tips_row){
			$tips_row=[
				'id'=>0,
				'param_value'=>'',
			];
		}

		$data=$tips_row;

		$this->_render_content($this->_load_view_file('tips_set'), $data, false);

	}

	public function edit_tips(){
		$inter_id = $this->session->get_admin_inter_id();
		$this->load->model('hotel/Hotel_config_model','hcm');

		$param_value=$this->input->post('param_value');
		$id=$this->input->post('id');
		$post_param=[
			'id'=>$id,
			'param_value'=>$param_value,
			'param_name'=>'COUPON_TIPS',
			'hotel_id'=>0,
			'inter_id'=>$inter_id,
			'module'=>'HOTEL',
		];
		$this->hcm->replace_config($post_param);

		exit(json_encode([
			                 'status'=>true,
			                 'info'=>'修改成功',
		                 ]));
//		redirect(site_url('membervip/membercard/tips_set'));
	}
}
