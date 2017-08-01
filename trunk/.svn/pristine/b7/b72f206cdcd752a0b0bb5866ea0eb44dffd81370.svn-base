<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Bonus extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '积分配置';
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
		return 'hotel/Bonus_rules_model';
	}
	protected function main_model() {
		if (!isset($this->m_model)){
			$this->load->model($this->main_model_name(),'m_model');
		}
		return $this->m_model;
	}
	public function userules() {
		$data = $this->common_data;
		$model=$this->main_model();
		$data['list']=$model->get_userule_list($this->inter_id);
		$data['fields_config']=$model->userule_fields_config();
		$this->_render_content ( $this->_load_view_file ( 'userules' ), $data, false );
	}
	public function ur_edit() {
		$data = $this->common_data;
		$model = $this->main_model();
		$rule_id = $this->input->get ( 'rid' );
		if (! empty ( $rule_id )) {
			$rule=$model->get_userule($this->inter_id, $rule_id,1);
			$data ['list']=$rule;
		} else {
			$data ['list'] = $model->userule_table_fields ();
		}
		$data['priorities']= $model->get_rule_priorities($this->inter_id,1,$rule_id);
		$this->_render_content ( $this->_load_view_file ( 'ur_edit' ), $data, false );
	}
	public function ur_check() {
		$data = $this->common_data;
		$model = $this->main_model();
		$rule_id = $this->input->get ( 'rid' );
		if (! empty ( $rule_id )) {
			$rule=$model->get_userule($this->inter_id, $rule_id,1);
			if (empty($rule))
				redirect(site_url('hotel/bonus/pur_check'));
			$data ['list']=$rule;
		} else {
			redirect(site_url('hotel/bonus/pur_check'));
		}
		$params=array(
				'ident'=>'hotel_bonus_urules#'.$rule_id,
				'log_des'=>'{admin_name}{type_des}了记录',
				'offset'=>0,
				'nums'=>20
		);
		$this->load->model('hotel/Hotel_log_model');
		$data['logs']=$this->Hotel_log_model->get_admin_log($this->inter_id,$params);
		$this->_render_content ( $this->_load_view_file ( 'ur_check' ), $data, false );
	}
	public function pur_edit() {
		$data = $this->common_data;
		$model = $this->main_model();
		$rule_id = $this->input->get ( 'rid' );
		$this->load->model ( 'hotel/Member_new_model' );
		$data ['member_levels'] = $this->Member_new_model->getAllMemberLevels ( $this->inter_id );
		if (! empty ( $rule_id )) {
			$rule=$model->get_userule($this->inter_id, $rule_id,2);
			$data ['list']=$rule;
		} else {
			$data ['list'] = $model->userule_table_fields ();
			$data ['list']['rule_type']=2;
		}
		$this->load->model ( 'pay/Pay_model' );
		$data ['pay_ways'] = $this->Pay_model->get_pay_way ( array (
				'inter_id' => $this->inter_id,
				'module' => $this->module,
				'status' => 1,
				'key' => 'value'
		) );
		$data['priorities']= $model->get_rule_priorities($this->inter_id,2,$rule_id);
		$this->_render_content ( $this->_load_view_file ( 'pur_edit' ), $data, false );
	}
	public function pur_check() {
		$data = $this->common_data;
		$model = $this->main_model();
		$rule_id = $this->input->get ( 'rid' );
		if (! empty ( $rule_id )) {
			$rule=$model->get_userule($this->inter_id, $rule_id,2);
			if (empty($rule))
				redirect(site_url('hotel/bonus/pur_check'));
			$data ['list']=$rule;
		} else {
			redirect(site_url('hotel/bonus/pur_check'));
		}
		$this->load->model ( 'pay/Pay_model' );
		$data ['pay_ways'] = $this->Pay_model->get_pay_way ( array (
				'inter_id' => $this->inter_id,
				'module' => $this->module,
				'status' => 1,
				'key' => 'value'
		) );
		$this->load->model ( 'hotel/Member_new_model' );
		$data ['member_levels'] = $this->Member_new_model->getAllMemberLevels ( $this->inter_id );
		$params=array(
				'ident'=>'hotel_bonus_urules#'.$rule_id,
				'log_des'=>'{admin_name}{type_des}了记录',
				'offset'=>0,
				'nums'=>20
		);
		$this->load->model('hotel/Hotel_log_model');
		$data['logs']=$this->Hotel_log_model->get_admin_log($this->inter_id,$params);
		$this->_render_content ( $this->_load_view_file ( 'pur_check' ), $data, false );
	}

	public function ajax_ur_hotel_rooms() {
		$rule_id = $this->input->get ( 'rid' );
		$model = $this->main_model();
		$hotel_rooms = '';
		if (! empty ( $rule_id )) {
			$check = $model->get_userule ( $this->inter_id, $rule_id );
			$hotel_rooms = empty ( $check ['hotel_rooms'] ) ? '' : $check ['hotel_rooms'];
		}
		$this->load->model('hotel/Coupons_model');
		$hotel_room_codes = $this->Coupons_model->hotel_rooms_check ( $this->inter_id, $hotel_rooms, $this->session->admin_profile ['entity_id'] );
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
	public function save_userule() {
		$data = $this->input->post ();
		
		$this->load->helper('array');
		$data=jqjson2arr(json_decode($data['datas'],TRUE));
		
		$rule = array ();
		$model = $this->main_model();
		$info = array (
				'status' => 2,
				'message' => 'error'
		);
		if (empty ( $data ['ex_way'] )) {
			$info ['message'] = '请选择兑换规则';
			exit ( json_encode ( $info ) );
		}
		if (empty ( $data ['rule_name'] )) {
			$info ['message'] = '请填写规则名';
			exit ( json_encode ( $info ) );
		}
		if (empty ( $data ['priority'] ) || intval($data ['priority'])<=0) {
			$info ['message'] = '请填写优先级';
			exit ( json_encode ( $info ) );
		}
		$rule_id=empty ( $data ['rule_id'] )?NULL:$data ['rule_id'];
		if (!$model->get_rule_priorities($this->inter_id,1,$rule_id,TRUE,$data ['priority'])){
			$info ['message'] = '优先级重复，请重新填写';
			exit ( json_encode ( $info ) );
		}
		$rule ['rule_name'] = $data ['rule_name'];
		$rule ['priority'] = $data ['priority'];
		if($data ['ex_way']==1){
			$rule ['ex_way'] = $data ['ex_way'];
			if (empty(floatval($data ['rate_value']))){
				$info ['message'] = '请填写兑换值';
				exit ( json_encode ( $info ) );
			}
			$rule ['ex_value'] = $data ['rate_value'];
		}else if ($data ['ex_way']==2){
			$rule ['ex_way'] = $data ['ex_way'];
			if (empty(floatval($data ['fix_value']))){
				$info ['message'] = '请填写兑换值';
				exit ( json_encode ( $info ) );
			}
			$rule ['ex_value'] = $data ['fix_value'];
		}
		// 其他规则
		$rule ['extra_condition'] = array ();
		if (isset($data ['min_p']) && $data ['min_p'] == 1 && !empty($data ['min_p_v'])) {
			$rule ['extra_condition'] ['min_price'] = $data ['min_p_v'];
		}
		if (isset($data ['min_h']) && $data ['min_h'] == 1 && !empty($data ['min_h_v'])) {
			$rule ['extra_condition'] ['min_haven'] = $data ['min_h_v'];
		}
		$rule ['extra_condition'] = json_encode ( $rule ['extra_condition'], JSON_UNESCAPED_UNICODE );
		// 限制日期
		if (! empty ( $data ['start_time'] ) ) {
			if (strtotime($data['start_time'])){
				$rule['start_time']=strtotime(date('Y-m-d 00:00:00',strtotime($data['start_time'])));
			}else {
				$info ['message'] = '开始日期错误';
				exit ( json_encode ( $info ) );
			}
		} else {
			$rule ['start_time'] = '';
		}
		if (! empty ( $data ['end_time'] ) ) {
			if (strtotime($data['end_time'])){
				$rule['end_time']=strtotime(date('Y-m-d 23:59:59',strtotime($data['end_time'])));
			}else {
				$info ['message'] = '结束日期错误';
				exit ( json_encode ( $info ) );
			}
		} else {
			$rule ['end_time'] = '';
		}
		if (! empty ( $rule ['start_time'] )&&! empty ( $rule ['end_time'] ) && $rule ['end_time']<$rule ['start_time']) {
			$info ['message'] = '日期错误';
			exit ( json_encode ( $info ) );
		}
	
		// 门店规则
		if ($data['hotel_rooms']=='all'){
			$rule ['hotel_rooms']='';
		}
		else if (! empty ( $data ['hotel_ids'] )) {
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
	
		$rule ['rule_type'] = 1;
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
	public function save_puserule() {
		$data = $this->input->post ();
		
		$this->load->helper('array');
		$data=jqjson2arr(json_decode($data['datas'],TRUE));
		
		$rule = array ();
		$model = $this->_load_model ( $this->main_model_name () );
		$info = array (
				'status' => 2,
				'message' => 'error'
		);
		if (empty ( $data ['rule_name'] )) {
			$info ['message'] = '请填写规则名';
			exit ( json_encode ( $info ) );
		}
		if (empty ( $data ['priority'] ) || intval($data ['priority'])<=0) {
			$info ['message'] = '请填写优先级';
			exit ( json_encode ( $info ) );
		}
		$rule_id=empty ( $data ['rule_id'] )?NULL:$data ['rule_id'];
		if (!$model->get_rule_priorities($this->inter_id,2,$rule_id,TRUE,$data ['priority'])){
			$info ['message'] = '优先级重复，请重新填写';
			exit ( json_encode ( $info ) );
		}
		$rule ['rule_name'] = $data ['rule_name'];
		$rule ['priority'] = $data ['priority'];
		$rule ['ex_way'] = 1;
		if (empty(floatval($data ['rate_value']))){
			$info ['message'] = '请填写兑换值';
			exit ( json_encode ( $info ) );
		}
		$rule ['ex_value'] = $data ['rate_value'];
		// 其他规则
		$rule ['extra_condition'] = array ();
		$rule_para=$model->enums('purule_para');
		if ($data ['member_level'] === 'part' && !empty($data ['levels'])) {
			$rule ['extra_condition'] ['level'] = $data ['levels'];
		} else {
			$rule ['extra_condition'] ['level'] = array ();
		}
		if ($data ['paytype'] === 'part' && !empty($data ['pay_ways'])) {
			$rule ['extra_condition'] ['paytype'] = $data ['pay_ways'];
		} else {
			$rule ['extra_condition'] ['paytype'] = array ();
		}
		foreach ($rule_para as $r){
			if (isset($data [$r]) && $data [$r] == 1 ) {
				if (!empty($data [$r.'_v'])){
					$rule ['extra_condition'] [$r] = $data [$r.'_v'];
				}else {
					$info ['message'] = '勾选的抵扣条件不能为空';
					exit ( json_encode ( $info ) );
				}
			}
		}
		$rule ['extra_condition'] = json_encode ( $rule ['extra_condition'], JSON_UNESCAPED_UNICODE );
		// 限制日期
		if (! empty ( $data ['start_time'] ) ) {
			if (strtotime($data['start_time'])){
				$rule['start_time']=strtotime(date('Y-m-d 00:00:00',strtotime($data['start_time'])));
			}else {
				$info ['message'] = '开始日期错误';
				exit ( json_encode ( $info ) );
			}
		} else {
			$rule ['start_time'] = 0;
		}
		if (! empty ( $data ['end_time'] ) ) {
			if (strtotime($data['end_time'])){
				$rule['end_time']=strtotime(date('Y-m-d 23:59:59',strtotime($data['end_time'])));
			}else {
				$info ['message'] = '结束日期错误';
				exit ( json_encode ( $info ) );
			}
		} else {
			$rule ['end_time'] = 0;
		}
		if (! empty ( $rule ['start_time'] )&&! empty ( $rule ['end_time'] ) && $rule ['end_time']<$rule ['start_time']) {
			$info ['message'] = '日期错误';
			exit ( json_encode ( $info ) );
		}
	
		// 门店规则
		if ($data['hotel_rooms']=='all'){
			$rule ['hotel_rooms']='';
		}
		else if (! empty ( $data ['hotel_ids'] )) {
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
	
		$rule ['rule_type'] = 2;
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

    public function giverules() {
        $data = $this->common_data;
        $model=$this->main_model();
        $data['list']=$model->get_giverule_list($this->inter_id);
        $data['status']=$model->enums('status');

        $this->_render_content ( $this->_load_view_file ( 'giverules' ), $data, false );
    }

    public function gr_edit() {
        $data = $this->common_data;
        $model = $this->main_model();

        $this->load->model('hotel/Member_new_model','Member_new_model');
        $data['levels']=$this->Member_new_model->getAllMemberLevels($this->inter_id);

        $this->load->model ( 'pay/Pay_model' );
        $data ['pay_ways'] = $this->Pay_model->get_pay_way ( array (
            'inter_id' => $this->inter_id,
            'module' => $this->module,
            'status' => 1,
            'key' => 'value'
        ) );


        $rule_id = $this->input->get ( 'rid' );
        $data['priorities']= $model->get_giverule_priorities($this->inter_id,1,$rule_id);
        if (! empty ( $rule_id )) {
            $rule=$model->get_giverule($this->inter_id, $rule_id);

            if(!$rule){
                redirect('hotel/bonus/giverules');
            }

            if(!empty($rule['paytype'])){
                $rule['paytype']=json_decode($rule['paytype']);
            }

            if(!empty($rule['valid_time'])){
                $valid_time=explode('-',$rule['valid_time']);
                $rule['b_time']=date('Y-m-d',strtotime($valid_time[0]));
                $rule['e_time']=date('Y-m-d',strtotime($valid_time[1]));
            }

            if(!empty($rule['give_rule'])){
                $give_rule=json_decode($rule['give_rule']);

                if(isset($give_rule->consume)){     //消费送积分规则
                    if(isset($give_rule->consume->all)){
                        $rule['give_type']='all';
                        $rule['allCost']=$give_rule->consume->all->cost;
                        $rule['allAmount']=$give_rule->consume->all->amount;
                    }else{
                        $rule['give_type']='part';
                        foreach($give_rule->consume as $levels=>$arr){
                            $rule['bonus_rule'][$levels]=$arr;
                        }
                    }
                }

                if(isset($give_rule->comment)){    //评论送积分
                    if(isset($give_rule->comment->all)){
                        $rule['comment_give_type']='all';
                        $rule['comment_give_amount']=$give_rule->comment->all->amount;
                    }else{
                        $rule['comment_give_type']='part';
                        foreach($give_rule->comment as $levels=>$arr){
                            $rule['comment_bonus_rule'][$levels]=$arr;
                        }
                    }
                }

            }


            $data ['list']=$rule;
        } else {
            $data ['list'] = $model->userule_table_fields ();
        }

        $this->_render_content ( $this->_load_view_file ( 'gr_edit' ), $data, false );
    }


    public function gr_edit_post(){

        $data=$this->input->post();
        
        $this->load->helper('array');
        $data=jqjson2arr(json_decode($data['datas'],TRUE));

        //赠送积分的酒店
        if(isset($data['hotel_rooms']) && $data['hotel_rooms']=='all'){
            $post ['hotels_id']='';
            unset($post ['hotel_rooms']);
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
                $post ['hotels_id'] = json_encode ( $post ['hotel_rooms'] );
                unset($post ['hotel_rooms']);
            }

        }



        //消费赠送规则
        $give_rule=array();
        if(isset($data['member_level'])&&$data['member_level']=='all'){
            $give_rule['consume']['all']=array('cost'=>$data['allcost'],'amount'=>$data['allamount']);
        }else{
            if(isset($data['levels'])){
                foreach($data['levels'] as $arr){
                    $give_rule['consume'][$arr]=array('cost'=>$data['cost['.$arr.']'],'amount'=>$data['amount['.$arr.']']);
                }
            }
        }

        //评论赠送规则
        if(isset($data['comment_bonus'])&&$data['comment_bonus']=='all'){
            $give_rule['comment']['all']=array('amount'=>$data['all_comment_give']);
        }elseif(isset($data['comment_bonus'])&&$data['comment_bonus']=='part'){
            if(isset($data['comment_levels'])){
                foreach($data['comment_levels'] as $arr){
                    $give_rule['comment'][$arr]=array('amount'=>$data['comment_give['.$arr.']']);
                }
            }
        }

        $post['give_rule']=json_encode($give_rule);

        //有效期
        if(isset($data['start_range'])&&!empty($data['start_range'])&&isset($data['end_range'])&&!empty($data['end_range'])){
            $start_time=str_replace('-','',$data['start_range']);
            $end_time=str_replace('-','',$data['end_range']);
            $post['valid_time']=$start_time.'-'.$end_time;
        }


        //支付方式
        if($data['byPay']=='all'){
            $post['paytype']='';
        }else{
            if(isset($data['payType'])){
                $post['paytype']=json_encode($data['payType']);
            }else{
                $post['paytype']='';
            }
        }

        if(isset($data['rule_name'])&&!empty($data['rule_name'])){
            $post['rule_name']=addslashes($data['rule_name']);
        }

        if(isset($data['status'])&&!empty($data['status'])){
            $post['status']=$data['status'];
        }

        if(isset($data['priority'])&&!empty($data['priority'])){
            $post['priority']=$data['priority'];
        }

        if(isset($data['rule_id'])&&!empty($data['rule_id'])){
            $post['rule_id']=$data['rule_id'];
        }


        $model=$this->main_model();
        $this->load->model ( 'hotel/Bonus_rules_model' );

        if(isset($data['bonus_grules_id']) && !empty($data['bonus_grules_id']) && $data['bonus_grules_id']!=''){

            if(isset($data['priority'])){
                $check_priority=$this->Bonus_rules_model->check_grules_priority($this->inter_id,$data['priority'],$data['bonus_grules_id']);
                if($check_priority){
                    $info ['msg'] = '已存在相同优先级';
                    exit ( json_encode ( $info ) );
                }
            }

            $check = $this->Bonus_rules_model->get_giverule ( $this->inter_id, $data ['bonus_grules_id'], FALSE );
            if (empty ( $check )) {
                $info ['msg'] = '规则不存在';
                exit ( json_encode ( $info ) );
            }
            if ($this->Bonus_rules_model->update_giverule ( $this->inter_id, $data ['bonus_grules_id'], $post )) {
                $info ['code'] = 1;
                $info ['msg'] = '保存成功';
            } else {
                $info ['msg'] = '保存失败';
            }
        } else {

            if(isset($data['priority'])){
                $check_priority=$this->Bonus_rules_model->check_grules_priority($this->inter_id,$data['priority']);
                if($check_priority){
                    $info ['msg'] = '已存在相同优先级';
                    exit ( json_encode ( $info ) );
                }
            }


            if ($this->Bonus_rules_model->add_giverule ( $this->inter_id, $post )) {

                $info ['code'] = 3;
                $info ['msg'] = '添加成功';
            } else {
                $info ['msg'] = '添加失败';
            }
        }

        echo  json_encode($info);
        exit();

    }


    public function gr_check() {
        $data = $this->common_data;
        $model = $this->main_model();
        $rule_id = $this->input->get ( 'rid' );
        if (! empty ( $rule_id )) {
            $rule=$model->get_giverule($this->inter_id, $rule_id);

            if(!$rule){
                redirect('hotel/bonus/giverules');
            }

            $data ['list']=$rule;
        } else {
            $data ['list'] = $model->table_fields ();
        }

        $this->load->model ( 'pay/Pay_model' );
        $data ['pay_ways'] = $this->Pay_model->get_pay_way ( array (
            'inter_id' => $this->inter_id,
            'module' => $this->module,
            'status' => 1,
            'key' => 'value'
        ) );

        if(isset($rule['paytype'])&&!empty($rule['paytype'])){
            $data['paytype']=json_decode($rule['paytype']);
        }

        $data['give_rule']=json_decode($rule['give_rule']);

        $this->load->model('hotel/Member_new_model','Member_new_model');
        $data['levels']=$this->Member_new_model->getAllMemberLevels($this->inter_id);

        $logs=$model->get_rule_logs($this->inter_id,'hotel_bonus_grules#'.$rule_id,0,20);

        $data['log_type']=array(
            'add'=>'新增',
            'edit'=>'修改'
        );

        if($logs){
            foreach($logs as $arr){
                $edit_admin=json_decode($arr['admin']);
                $arr['admin']=$edit_admin;
                $data['logs'][]=$arr;
            }
        }

//        if(!empty($rule['hotels_id'])){
//            $hotels_id=json_decode($rule['hotels_id']);
//            foreach($hotels_id as $key=>$arr){
//                foreach($arr as $room_id=>$rooms){
//                    $data['list']['hotel_rooms'][$key][$room_id]=$rooms;
//                }
//            }
////            $data['list']['hotel_rooms']=json_decode($rule['hotels_id']);
//        }

        $this->_render_content ( $this->_load_view_file ( 'gr_check' ), $data, false );
    }



    public function ajax_gr_hotel_rooms() {
        $rule_id = $this->input->get ( 'rid' );
        $model = $this->_load_model ( $this->main_model_name () );
        $hotel_rooms = '';
        if (! empty ( $rule_id )) {
            $check = $model->get_giverule ( $this->inter_id, $rule_id );

            if(!empty($check ['hotels_id'])){
                $check ['hotel_rooms']=$check ['hotels_id'];
            }
            $hotel_rooms = empty ( $check ['hotel_rooms'] ) ? '' : $check ['hotel_rooms'];
        }
        $this->load->model('hotel/Coupons_model');
        $hotel_room_codes = $this->Coupons_model->hotel_rooms_check ( $this->inter_id, $hotel_rooms, $this->session->admin_profile ['entity_id'] );

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


    public function deal_bonus_rule(){

        $model = $this->main_model();
        $all_rules = $model->allRules();

        if(!empty($all_rules)){
            foreach($all_rules as $rule){
                $give_rule = json_decode($rule['give_rule']);
                if(!isset($give_rule->consume)){
                    $update['consume'] = $give_rule;
                    echo $model->update_rules($rule['bonus_grules_id'],json_encode($update)).';';
                }
            }
        }

    }

}
