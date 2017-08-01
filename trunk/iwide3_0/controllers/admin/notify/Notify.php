<?php
/**
* 后台订单提醒
* author chenjunyu
* date 2016-10-21
*/
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Notify extends MY_Admin{
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '订单通知管理';
	protected $label_action = '';
	function __construct(){
		parent::__construct();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
	}

	public function index(){
		redirect(site_url('notify/notify/target'));
	}

	public function target(){
		$data = $this->common_data;
		$this->label_action = '消息提醒设置';
		$this->_init_breadcrumb ( $this->label_action );
		$this->load->model('hotel/hotel_notify_model');
		if($this->input->get()){
			//保存配置修改
			$ins = array();
			foreach ($this->input->get() as $kp => $vp) {
				if($kp=='single'){
					if(!empty($vp['all'])){
						$ins['wx_notify'] = $vp['all'];
					}else{
						$ins['wx_notify'] = implode(',',$vp);
					}
				}else{
					if($kp!='membertable_length')
					$ins[$kp] = $vp=='on'?1:2;
				}
			}
			$ins['inter_id'] = $this->inter_id;
			//保存酒店消息提醒设置
			$this->hotel_notify_model->save_hotels_notify($ins);
		}
		$data['check_config'] = $this->hotel_notify_model->notify_check_config();
		$data['admin_config'] = $this->hotel_notify_model->get_admin_notify_config($this->inter_id);
		$data['hotels'] = $this->hotel_notify_model->get_all_hotels($this->inter_id);
		//压入全部酒店选项
		array_unshift($data['hotels'], array('hotel_id'=>0,'inter_id'=>$this->inter_id,'name'=>'全部酒店'));
		if(empty($data['admin_config'])){
			$data['admin_config'] = $this->hotel_notify_model->notify_default_config();
		}
		$this->_render_content ( $this->_load_view_file ( 'target' ), $data, false );
	}

	public function ajax_query_order() {
		//检查弹窗提醒设置
		$this->load->model('hotel/hotel_notify_model');
		$notifys = $this->hotel_notify_model->get_admin_notify_config($this->inter_id);
		if($notifys['is_popup']==1 || $notifys['is_voice']==1){
			// $check_time_o = $this->input->get ( 'order_t' );
			// $check_time_c = $this->input->get ( 'chkot_t' );
			// $check_time = empty ( $check_time ) ? time () : intval ( $check_time );
			//弹窗已操作，数量归零
			if($this->input->get('r')==1){
				$this->session->set_userdata('num_o',0);exit('1');
			}elseif($this->input->get('r')==2){
				$this->session->set_userdata('num_c',0);exit('1');
			}elseif($this->input->get('r')==3){
                $this->session->set_userdata('rs_num_o',0);exit('1');//快乐送新订单【客房内】
            }elseif($this->input->get('r')==4){
                $this->session->set_userdata('rs_num_r',0);exit('1');//快乐送催单【客房内】
            }
            elseif($this->input->get('r')==33){
                $this->session->set_userdata('ei_num_o',0);exit('1');//快乐送新订单【堂食】
            }elseif($this->input->get('r')==44){
                $this->session->set_userdata('ei_num_r',0);exit('1');//快乐送催单【堂食】
            }
            elseif($this->input->get('r')==333){
                $this->session->set_userdata('ta_num_o',0);exit('1');//快乐送新订单【外卖】
            }elseif($this->input->get('r')==444){
                $this->session->set_userdata('ta_num_r',0);exit('1');//快乐送催单【外卖】
            }
            elseif($this->input->get('r')==3333){
                $this->session->set_userdata('tk_num_o',0);exit('1');//快乐送新订单【门票】
            }elseif($this->input->get('r')==4444){
                $this->session->set_userdata('tk_num_r',0);exit('1');//快乐送催单【门票】
            }
            elseif($this->input->get('r')==5555){
                $this->session->set_userdata('soma_num_o',0);exit('1');//商城新订单
            }

            //先查询是否有权限
            $acl_array = $this->session->allow_actions;
            $acl_array = $acl_array [ADMINHTML];
            $hotel_acl = 1;//订房权限
            $rs_acl = $ei_acl = $ta_acl = $tk_acl = 1;//快乐送权限
            $soma_acl = 1;
            if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['roomservice']['orders']) )) {
                $rs_acl = 0;//没有权限
            }
            if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['eat-in']['orders']) )) {
                $ei_acl = 0;//没有权限
            }
            if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['take-away']['orders']) )) {
                $ta_acl = 0;//没有权限
            }
            if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['ticket']['orders']) )) {
                $tk_acl = 0;//没有权限
            }
            if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['soma']['sales_order']) )) {
                $soma_acl = 0;//没有权限
            }

            if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['hotel']['orders']) && !isset($acl_array ['hotel']['checkout']) )) {
                $hotel_acl = 0;//没有权限
            }
            if($hotel_acl){//有权限
                if(empty($this->session->userdata('num_o'))){
                    //记录check时间
                    if(!empty($this->session->userdata('check_time_o'))){
                        $check_time_o = $this->session->userdata('check_time_o');//新订单
                    }else{
                        $check_time_o = time();
                        $this->session->set_userdata('check_time_o',$check_time_o);
                    }
                    $this->load->model ( 'hotel/Order_check_model', 'checkm' );
                    $onums = $this->checkm->check_time_range_order ( $this->inter_id, 'gt_order_time', array (
                        'hotel_ids' => $this->session->get_admin_hotels ()==''?'':explode(',', $this->session->get_admin_hotels ()),
                        'check_time' => $check_time_o,
                        'exp_status' => array(9,10),
                    ), 'nums' );
                }else{
                    $onums = $this->session->userdata('num_o');
                }
                if(empty($this->session->userdata('num_c'))){
                    if(!empty($this->session->userdata('check_time_c'))){
                        $check_time_c = $this->session->userdata('check_time_c');//退房
                    }else{
                        $check_time_c = time();
                        $this->session->set_userdata('check_time_c',$check_time_c);
                    }
                    $this->load->model('invoice/invoice_model');
                    $inums = $this->invoice_model->get_new_checkout($this->inter_id,array(
                        'hotel_ids' => $this->session->get_admin_hotels ()==''?'':explode(',', $this->session->get_admin_hotels ()),
                        'check_time' => $check_time_c,
                    ));
                }else{
                    $inums = $this->session->userdata('num_c');
                }
            }else{//无权限
                $onums = 0;
                $inums = 0;
            }

            //商城
            if($soma_acl){//有权限
                if(empty($this->session->userdata('soma_num_o'))){
                    //记录check时间
                    if(!empty($this->session->userdata('soma_check_time_o'))){
                        $soma_check_time_o = $this->session->userdata('soma_check_time_o');//新订单
                    }else{
                        $soma_check_time_o = date('Y-m-d H:i:s');
                        $this->session->set_userdata('soma_check_time_o',$soma_check_time_o);
                    }
                    //调商城提供的查询新订单数量的方法
                    $this->load->model('soma/Sales_order_model');
                    $soma_onums = $this->Sales_order_model->getOrderQty($this->inter_id,$soma_check_time_o,
                        $this->session->get_admin_hotels ()==''?array():explode(',', $this->session->get_admin_hotels ()));
                }else{
                    $soma_onums = $this->session->userdata('soma_num_o');
                }
            }else{//无权限
                $soam_onums = 0;
            }

            //快乐送 通知
            if($rs_acl)
            {//有权限
                //客房内
                if(empty($this->session->userdata('rs_num_o')))
                {
                    //记录check时间
                    if(!empty($this->session->userdata('rs_check_time_o'))){
                        $rs_check_time_o = $this->session->userdata('rs_check_time_o');//新订单
                    }else{
                        $rs_check_time_o = time();
                        $this->session->set_userdata('rs_check_time_o',$rs_check_time_o);
                    }
                    //查询订餐新增订单
                    $this->load->model('roomservice/roomservice_orders_model');

                    $roomseriver_new_order = $this->roomservice_orders_model->get_new_time_order($this->inter_id,array(
                        'hotel_ids' => $this->session->get_admin_hotels ()==''?'':explode(',', $this->session->get_admin_hotels ()),
                        'check_time' => $rs_check_time_o,
                        'type' => 1,
                    ));
                }
                else
                {
                    $roomseriver_new_order = $this->session->userdata('rs_num_o');
                }

                //快乐送催单提醒
                if(empty($this->session->userdata('rs_num_r'))){
                    //记录check时间
                    if(!empty($this->session->userdata('rs_check_time_r'))){
                        $rs_check_time_r = $this->session->userdata('rs_check_time_r');//新订单
                    }else{
                        $rs_check_time_r = time();
                        $this->session->set_userdata('rs_check_time_r',$rs_check_time_r);
                    }
                    //查询inter_id下的催单信息
                    $this->load->model('roomservice/roomservice_action_model');
                    $roomseriver_order_reminder = $this->roomservice_action_model->get_new_remind_action($this->inter_id,array(
                        'hotel_ids' => $this->session->get_admin_hotels ()==''?'':explode(',', $this->session->get_admin_hotels ()),
                        'check_time' => $rs_check_time_r,
                        'type' => 1,
                    ));
                }else{
                    $roomseriver_order_reminder = $this->session->userdata('rs_num_r');
                }

            }else{//无权限
                $roomseriver_new_order = 0;
                $roomseriver_order_reminder = 0;
            }


            if($ei_acl)
            {//有权限
                //堂食
                if(empty($this->session->userdata('ei_num_o')))
                {
                    //记录check时间
                    if(!empty($this->session->userdata('ei_check_time_o'))){
                        $ei_check_time_o = $this->session->userdata('ei_check_time_o');//新订单
                    }else{
                        $ei_check_time_o = time();
                        $this->session->set_userdata('ei_check_time_o',$ei_check_time_o);
                    }
                    //查询订餐新增订单
                    $this->load->model('roomservice/roomservice_orders_model');

                    $eatin_new_order = $this->roomservice_orders_model->get_new_time_order($this->inter_id,array(
                        'hotel_ids' => $this->session->get_admin_hotels ()==''?'':explode(',', $this->session->get_admin_hotels ()),
                        'check_time' => $ei_check_time_o,
                        'type' => 2,
                    ));
                }
                else
                {
                    $eatin_new_order = $this->session->userdata('ei_num_o');
                }

                //快乐送催单提醒
                if(empty($this->session->userdata('rs_num_r'))){
                    //记录check时间
                    if(!empty($this->session->userdata('rs_check_time_r'))){
                        $rs_check_time_r = $this->session->userdata('rs_check_time_r');//新订单
                    }else{
                        $rs_check_time_r = time();
                        $this->session->set_userdata('rs_check_time_r',$rs_check_time_r);
                    }
                    //查询inter_id下的催单信息
                    $this->load->model('roomservice/roomservice_action_model');
                    $eatin_order_reminder = $this->roomservice_action_model->get_new_remind_action($this->inter_id,array(
                        'hotel_ids' => $this->session->get_admin_hotels ()==''?'':explode(',', $this->session->get_admin_hotels ()),
                        'check_time' => $rs_check_time_r,
                        'type' => 2,
                    ));
                }else{
                    $eatin_order_reminder = $this->session->userdata('rs_num_r');
                }

            }else{//无权限
                $eatin_new_order = 0;
                $eatin_order_reminder = 0;
            }

            if($ta_acl)
            {//有权限
                //外卖
                if(empty($this->session->userdata('ta_num_o')))
                {
                    //记录check时间
                    if(!empty($this->session->userdata('ta_check_time_o'))){
                        $ta_check_time_o = $this->session->userdata('ta_check_time_o');//新订单
                    }else{
                        $ta_check_time_o = time();
                        $this->session->set_userdata('ta_check_time_o',$ta_check_time_o);
                    }
                    //查询订餐新增订单
                    $this->load->model('roomservice/roomservice_orders_model');

                    $takeaway_new_order = $this->roomservice_orders_model->get_new_time_order($this->inter_id,array(
                        'hotel_ids' => $this->session->get_admin_hotels ()==''?'':explode(',', $this->session->get_admin_hotels ()),
                        'check_time' => $ta_check_time_o,
                        'type' => 3,
                    ));
                }
                else
                {
                    $takeaway_new_order = $this->session->userdata('ta_num_o');
                }

                //快乐送催单提醒
                if(empty($this->session->userdata('rs_num_r'))){
                    //记录check时间
                    if(!empty($this->session->userdata('rs_check_time_r'))){
                        $rs_check_time_r = $this->session->userdata('rs_check_time_r');//新订单
                    }else{
                        $rs_check_time_r = time();
                        $this->session->set_userdata('rs_check_time_r',$rs_check_time_r);
                    }
                    //查询inter_id下的催单信息
                    $this->load->model('roomservice/roomservice_action_model');
                    $takeaway_order_reminder = $this->roomservice_action_model->get_new_remind_action($this->inter_id,array(
                        'hotel_ids' => $this->session->get_admin_hotels ()==''?'':explode(',', $this->session->get_admin_hotels ()),
                        'check_time' => $rs_check_time_r,
                        'type' => 3,
                    ));
                }else{
                    $takeaway_order_reminder = $this->session->userdata('rs_num_r');
                }

            }else{//无权限
                $takeaway_new_order = 0;
                $takeaway_order_reminder = 0;
            }

            if($tk_acl)
            {//有权限
                //门票
                if(empty($this->session->userdata('tk_num_o')))
                {
                    //记录check时间
                    if(!empty($this->session->userdata('tk_check_time_o'))){
                        $tk_check_time_o = $this->session->userdata('tk_check_time_o');//新订单
                    }else{
                        $tk_check_time_o = time();
                        $this->session->set_userdata('tk_check_time_o',$tk_check_time_o);
                    }
                    //查询订餐新增订单
                    $this->load->model('roomservice/roomservice_orders_model');

                    $ticket_new_order = $this->roomservice_orders_model->get_new_time_order($this->inter_id,array(
                        'hotel_ids' => $this->session->get_admin_hotels ()==''?'':explode(',', $this->session->get_admin_hotels ()),
                        'check_time' => $tk_check_time_o,
                        'type' => 4,
                    ));
                }
                else
                {
                    $ticket_new_order = $this->session->userdata('tk_num_o');
                }

                //快乐送催单提醒
                if(empty($this->session->userdata('rs_num_r'))){
                    //记录check时间
                    if(!empty($this->session->userdata('rs_check_time_r'))){
                        $rs_check_time_r = $this->session->userdata('rs_check_time_r');//新订单
                    }else{
                        $rs_check_time_r = time();
                        $this->session->set_userdata('rs_check_time_r',$rs_check_time_r);
                    }
                    //查询inter_id下的催单信息
                    $this->load->model('roomservice/roomservice_action_model');
                    $ticket_order_reminder = $this->roomservice_action_model->get_new_remind_action($this->inter_id,array(
                        'hotel_ids' => $this->session->get_admin_hotels ()==''?'':explode(',', $this->session->get_admin_hotels ()),
                        'check_time' => $rs_check_time_r,
                        'type' => 4,
                    ));
                }else{
                    $ticket_order_reminder = $this->session->userdata('rs_num_r');
                }

            }else{//无权限
                $ticket_new_order = 0;
                $ticket_order_reminder = 0;
            }


		}else{
			$onums=0;
			$inums=0;
			$roomseriver_new_order = 0;
			$roomseriver_order_reminder = 0;
            $soma_onums = 0;
		}
		//更新check时间
		if($onums>0){
			$this->session->set_userdata('check_time_o',time());
			$this->session->set_userdata('num_o',$onums);
		}else{
			$this->session->set_userdata('num_o',0);
		}
		if($inums>0){
			$this->session->set_userdata('check_time_c',time());
			$this->session->set_userdata('num_c',$inums);
		}else{
			$this->session->set_userdata('num_c',0);
		}
        //客房内
        if($roomseriver_new_order>0){
            $this->session->set_userdata('rs_check_time_o',time());
            $this->session->set_userdata('rs_num_o',$roomseriver_new_order);
        }else{
            $this->session->set_userdata('rs_num_o',0);
        }
        if($roomseriver_order_reminder>0){
            $this->session->set_userdata('rs_check_time_r',time());
            $this->session->set_userdata('rs_num_r',$roomseriver_order_reminder);
        }else{
            $this->session->set_userdata('rs_num_r',0);
        }
        //堂食
        if($eatin_new_order>0){
            $this->session->set_userdata('ei_check_time_o',time());
            $this->session->set_userdata('ei_num_o',$eatin_new_order);
        }else{
            $this->session->set_userdata('ei_num_o',0);
        }
        if($eatin_order_reminder>0){
            $this->session->set_userdata('ei_check_time_r',time());
            $this->session->set_userdata('ei_num_r',$eatin_order_reminder);
        }else{
            $this->session->set_userdata('ei_num_r',0);
        }

        //外卖
        if($takeaway_new_order>0){
            $this->session->set_userdata('ta_check_time_o',time());
            $this->session->set_userdata('ta_num_o',$takeaway_new_order);
        }else{
            $this->session->set_userdata('ta_num_o',0);
        }
        if($takeaway_order_reminder>0){
            $this->session->set_userdata('ta_check_time_r',time());
            $this->session->set_userdata('ta_num_r',$takeaway_order_reminder);
        }else{
            $this->session->set_userdata('ta_num_r',0);
        }

        //门票
        if($ticket_new_order>0){
            $this->session->set_userdata('tk_check_time_o',time());
            $this->session->set_userdata('tk_num_o',$ticket_new_order);
        }else{
            $this->session->set_userdata('tk_num_o',0);
        }
        if($ticket_order_reminder>0){
            $this->session->set_userdata('tk_check_time_r',time());
            $this->session->set_userdata('tk_num_r',$ticket_order_reminder);
        }else{
            $this->session->set_userdata('tk_num_r',0);
        }

        //商城
        if($soma_onums>0){
            $this->session->set_userdata('soma_check_time_o',date('Y-m-d H:i:s'));
            $this->session->set_userdata('soma_num_o',$soma_onums);
        }else{
            $this->session->set_userdata('soma_num_o',0);
        }

		echo json_encode ( array(
                'order'=>array (
                    'status' => 1,
                    'total' => $onums,
                    'message' => '',
                    'type' => 'order',
                    'is_popup' => $notifys['is_popup'],
                    'is_voice' => $notifys['is_voice']
                ),
                'checkout' => array(
                    'status' => 1,
                    'total' => $inums,
                    'message' => '',
                    'type' => 'checkout',
                    'is_popup' => $notifys['is_popup'],
                    'is_voice' => $notifys['is_voice']
                    ),
                'roomseriver_new_order' => array(
                    'status' => 1,
                    'total' => $roomseriver_new_order,
                    'message' => '',
                    'type' => 'roomseriver_new_order',
                    'is_popup' => $notifys['is_popup'],
                    'is_voice' => $notifys['is_voice']
                ),
                'roomseriver_order_reminder' => array(
                    'status' => 1,
                    'total' => $roomseriver_order_reminder,
                    'message' => '',
                    'type' => 'roomseriver_order_reminder',
                    'is_popup' => $notifys['is_popup'],
                    'is_voice' => $notifys['is_voice']
                ),
                'eatin_new_order' => array(
                    'status' => 1,
                    'total' => $eatin_new_order,
                    'message' => '',
                    'type' => 'eatin_new_order',
                    'is_popup' => $notifys['is_popup'],
                    'is_voice' => $notifys['is_voice']
                ),
                'eatin_order_reminder' => array(
                    'status' => 1,
                    'total' => $eatin_order_reminder,
                    'message' => '',
                    'type' => 'eatin_order_reminder',
                    'is_popup' => $notifys['is_popup'],
                    'is_voice' => $notifys['is_voice']
                ),
                'takeaway_new_order' => array(
                    'status' => 1,
                    'total' => $takeaway_new_order,
                    'message' => '',
                    'type' => 'takeaway_new_order',
                    'is_popup' => $notifys['is_popup'],
                    'is_voice' => $notifys['is_voice']
                ),
                'takeaway_order_reminder' => array(
                    'status' => 1,
                    'total' => $takeaway_order_reminder,
                    'message' => '',
                    'type' => 'takeaway_order_reminder',
                    'is_popup' => $notifys['is_popup'],
                    'is_voice' => $notifys['is_voice']
                ),
                'ticket_new_order' => array(
                    'status' => 1,
                    'total' => $ticket_new_order,
                    'message' => '',
                    'type' => 'ticket_new_order',
                    'is_popup' => $notifys['is_popup'],
                    'is_voice' => $notifys['is_voice']
                ),
                'ticket_order_reminder' => array(
                    'status' => 1,
                    'total' => $ticket_order_reminder,
                    'message' => '',
                    'type' => 'ticket_order_reminder',
                    'is_popup' => $notifys['is_popup'],
                    'is_voice' => $notifys['is_voice']
                ),
                'soma_order'=>array (
                    'status' => 1,
                    'total' => $soma_onums,
                    'message' => '',
                    'type' => 'soma_order',
                    'is_popup' => $notifys['is_popup'],
                    'is_voice' => $notifys['is_voice']
                ),
			)
			);
	}

	public function ajax_get_reg(){
		$this->load->model('hotel/hotel_notify_model');
		$regs = $this->hotel_notify_model->get_hotels_reg();
		echo json_encode($regs);
	}

	public function ajax_to_permit(){
		$per = $this->input->get('per');
		$rid = $this->input->get('rid');
		if($per&&$rid){
			$this->load->model('hotel/hotel_notify_model');
			$pers = $this->hotel_notify_model->save_reg_permit($per,$rid);
			if($pers){
				echo 'ok';
			}
		}
	}

	public function ajax_edit_hotel(){
		$hid = $this->input->get('hid');
		$rid = $this->input->get('rid');
		if($rid){
			$this->load->model('hotel/hotel_notify_model');
			$ish = $this->hotel_notify_model->edit_hotel($hid,$rid);
			if($ish){
				echo 'ok';
			}
		}
	}

	//生成登记二维码
	public function apply_qr_code(){
		$this->_get_qrcode_png(EA_const_url::inst()->get_front_url($this->inter_id, 'hotel_notify/hotel_notify/register?id='.$this->inter_id));
	}

	public function notify_wx(){
		$data = $this->common_data;
		$this->label_action = '微信提醒设置';
		$this->_init_breadcrumb ( $this->label_action );
		$this->load->model('hotel/hotel_notify_model');
		$regs = $this->hotel_notify_model->get_hotels_reg();
		$data['regs'] = $regs;
		$this->_render_content ( $this->_load_view_file ( 'notify_wx' ), $data, false );
	}

	public function edit_wx(){
		$data = $this->common_data;
		$this->label_action = '编辑';
		$this->_init_breadcrumb ( $this->label_action );
		$id = $this->input->get('id')?$this->input->get('id'):0;
		$this->load->model('hotel/hotel_notify_model');
		$data['check_config'] = $this->hotel_notify_model->notify_check_config();
		$data['check_config']['all']['text'] = '全选';
		// $data['regs'] = $this->hotel_notify_model->get_hotels_reg();
		$data['hotels'] = $this->hotel_notify_model->get_all_hotels($this->inter_id);
		$data['weeks_config'] = $this->hotel_notify_model->weeks_config();
		if($id>0){
			$data['reg_info'] = $this->hotel_notify_model->get_reg_info($id);
		}
		$data['err'] = 0;
		if($this->input->get('err')){
			$data['err'] = $this->input->get('err');
		}
		$this->_render_content ( $this->_load_view_file ( 'edit_wx' ), $data, false );
	}

	public function edit_wx_post(){
		$post = $this->input->post();
		$this->load->model('hotel/hotel_notify_model');
		$data = array();
		if(!empty($post['single'])){
			if(!empty($post['single']['all'])){
				$data['wx_notify'] = 'all';
			}else{
				$data['wx_notify'] = implode(',',$post['single']);
			}
		}else{
			$data['wx_notify'] = '';
		}
		if(!empty([$post['weeks']])){
			$data['weeks'] = implode(',',$post['weeks']);
		}else{
			$data['weeks'] = '';
		}
		$data['id'] = $post['regid'];
		// $data['status'] = 1;
		$data['hotel_id'] = $post['hid'];
		$data['status'] = $post['status'];
		$res = $this->hotel_notify_model->edit_regs($data);
		if($res){
			redirect(site_url('notify/notify/notify_wx'));
		}
		redirect(site_url('notify/notify/edit_wx?id='.$data['id'].'&err=1'));
	}
}