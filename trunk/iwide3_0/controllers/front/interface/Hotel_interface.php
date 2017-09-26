<?php
// error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Hotel_interface extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->output->enable_profiler ( false );
		ini_set ( 'display_errors', 0 );
		if (version_compare ( PHP_VERSION, '5.3', '>=' )) {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_USER_NOTICE & ~ E_USER_DEPRECATED );
		} else {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_STRICT & ~ E_USER_NOTICE );
		}
	}
	public function send_order_template_msg() {
		try {
			$this->load->model ( 'plugins/Template_msg_model' );
			$this->load->model ( 'interface/Icommon_model' );
			$this->load->model ( 'interface/Isigniture_model' );
			$source = $this->Icommon_model->_base_input_valid ();
			$this->load->model ( 'hotel/Order_check_model' );
			if (empty ( $source ['order_id'] )) {
				$this->Icommon_model->out_put_msg ( FALSE, 'wrong order id' );
			}
			$inter_id = $source ['itd'];
			$order = $this->Order_check_model->get_order_by_weborderid ( $inter_id, $source ['order_id'] );
			if (empty ( $order )) {
				$this->Icommon_model->out_put_msg ( FALSE, 'no order found' );
			}
			$result = array (
					's' => 0,
					'errmsg' => '' 
			);
			if (! empty ( $source ['template_type'] )) {
				$result = $this->Template_msg_model->send_hotel_order_msg ( $order, $source ['template_type'],NULL );
			} else {
				if (empty ( $source ['template_content'] ) || empty ( json_decode ( $source ['template_content'] ) )) {
					$this->Icommon_model->out_put_msg ( FALSE, 'no or wrong template content (json required)' );
				}
				$msg_type = 'hotel_interface';
				$json = json_decode ( $source ['template_content'], TRUE );
				$json ['touser'] = $order ['openid'];
				if (! empty ( $source ['url_type'] )) {
					$this->load->model ( 'common/Enum_model' );
					$urls = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_TMPMSG_URL' );
					if (! empty ( $urls [$source ['url_type']] )) {
						$find_replace = $this->Template_msg_model->order_find_replace ( $order );
						$this->load->model ( 'wx/Publics_model' );
						$public = $this->Publics_model->get_public_by_id ( $order ['inter_id'] );
						$json ['url'] = $public ['domain'] . str_replace ( $find_replace ['find'], $find_replace ['replace'], $urls [$source ['url_type']] );
					}
				}
				$result = $this->Template_msg_model->send_template_msg ( $inter_id, $json, $msg_type );
			}
			$errmsg = empty ( $result ['errmsg'] ) ? '' : $result ['errmsg'];
			if ($result ['s'] == 1) {
				$this->Icommon_model->out_put_msg ( TRUE, $errmsg );
			} else {
				$datas = empty ( $result ['datas'] ) ? array () : $result ['datas'];
				$this->Icommon_model->out_put_msg ( FALSE, $errmsg, $datas );
			}
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}
	public function send_wx_template_msg() {
		try {
			$this->load->model ( 'plugins/Template_msg_model' );
			$this->load->model ( 'interface/Icommon_model' );
			$source = $this->Icommon_model->_base_input_valid ();
			$inter_id = $source ['itd'];
			$result = array (
					's' => 0,
					'errmsg' => '' 
			);
			$json = json_decode ( $source ['template_content'], TRUE );
			if (empty($json)){
				$this->Icommon_model->out_put_msg ( FALSE, 'no or wrong template content (json required)' );
			}
			$result = $this->Template_msg_model->send_template_msg ( $inter_id, $json, 'interface' );
			$errmsg = empty ( $result ['errmsg'] ) ? '' : $result ['errmsg'];
			if ($result ['s'] == 1) {
				$this->Icommon_model->out_put_msg ( TRUE, $errmsg );
			} else {
				$datas = empty ( $result ['datas'] ) ? array () : $result ['datas'];
				$this->Icommon_model->out_put_msg ( FALSE, $errmsg, $datas );
			}
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}
	public function send_reg_template_msg() {
	    try {
	        $this->load->model ( 'plugins/Template_msg_model' );
	        $this->load->model ( 'interface/Icommon_model' );
	        $source = $this->Icommon_model->_base_input_valid ();
	        $inter_id = $source ['itd'];
	        $result = array (
	            's' => 0,
	            'errmsg' => ''
	        );
	        $json = json_decode ( $source ['template_content'], TRUE );
	        if (empty($json)){
	            $this->Icommon_model->out_put_msg ( FALSE, 'no or wrong template content (json required)' );
	        }
	        $result = $this->Template_msg_model->send_template_msg ( $inter_id, $json, 'interface' );
	        $errmsg = empty ( $result ['errmsg'] ) ? '' : $result ['errmsg'];
	        if ($result ['s'] == 1) {
	            /*注册分销绩效*/
	                    $rule_info = $this->getCI()->Distribution_model->get_distribution_rule($inter_id,'reg','t');
	                    $saler_id = 0;
	                    switch ($rule_info['belonging']){
	                        case 1://粉丝归属
	                            $fan = $this->getCI()->Fans_model->get_fans_beloning($inter_id,$this->vars['openid']);
	                            if(!empty($fan) && $fan->source > 0){
	                                $saler_id = $fan->source;
	                            }
	                            break;
	                        case 2://归属于链接分销号
	                            $saler_id = intval($source ['salesId']);
	                            break;
	                        case 3://优先归属于链接分销号
	                            $saler_id = intval($source ['salesId']);
	                            if (!$saler_id){
	                                $fan = $this->getCI()->Fans_model->get_fans_beloning($inter_id,$this->vars['openid']);
	                                if(!empty($fan) && $fan->source > 0){
	                                    $saler_id = $fan->source;
	                                }
	                            }
	                            break;
	                        default:
	                            break;
	                    }
	                    if ($saler_id){
	                        $dis_record = array(
	                            'open_id' => $openid,
	                            'type' =>  $rule_info['rule_type'],
	                            'reward' => $rule_info['reward'],
	                            'record_title'=> $rule_info['title'],
	                            'sn'    =>  $this->vars['member_num'],
	                            'status' => 'f',
	                        );
	                        $this->getCI()->load->model('distribute/Staff_model');
	                        $sales = $this->getCI()->Staff_model->get_my_base_info_saler($inter_id,$saler_id);
	                        if ($sales){
	                            $dis_record['sales_id'] = $sales['qrcode_id'];
	                            $dis_record['sales_name'] = $sales['name'];
	                            $dis_record['hotel_name']  = $sales['hotel_name'];
                                $dis_record['hotel_id'] = $sales['hotel_id'];
	                            /*分销绩效记录写入*/
	                            $record_id = $this->getCI()->Distribution_model->add_distribution_record($inter_id,$dis_record);
	                            if(!$record_id){
	                                \MYLOG::w("Distribution Record Reg Insert :".json_encode($dis_record).'|'.json_encode($sales).'|'.$inter_id." | Result Failed ",'distribution_record/failed');
	                            }
	                        }else{
	                            \MYLOG::w("Staff not found :".json_encode($dis_record).'|'.$inter_id.'|'.$saler_id." | Staff Failed ",'distribution_record/failed');
	                        }
	                } /*end注册分销绩效*/
	            
	            $this->Icommon_model->out_put_msg ( TRUE, $errmsg );
	        } else {
	            $datas = empty ( $result ['datas'] ) ? array () : $result ['datas'];
	            $this->Icommon_model->out_put_msg ( FALSE, $errmsg, $datas );
	        }
	    } catch ( Exception $ex ) {
	        $this->Icommon_model->out_put_msg ( FALSE );
	    }
	}
	
	public function get_wx_url() {
		try {
			$this->load->model ( 'common/Enum_model' );
			$urls = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_TMPMSG_URL' );
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}
}