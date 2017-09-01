<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refund extends MY_Admin {
	protected $label_module= '快乐付';
	protected $label_controller= '退款列表';
	protected $label_action= '退款记录';
	
	public function __construct() {
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'okpay/Okpay_refund_model';
	}
	function index(){
		$this->grid();
	}
	
	public function grid()
	{
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		$entity_id = $this->session->get_admin_hotels ();
		//$hotel_ids = explode ( ',', $entity_id );
		if(!empty($entity_id)){
			$hotel_ids = explode ( ',', $entity_id );
			$filter['hotel_id'] = $hotel_ids;
		}
		if(is_ajax_request())
			$get_filter= $this->input->post();
			else
				$get_filter= $this->input->get('filter');
					
				if( !$get_filter) $get_filter= $this->input->get('filter');
					
				if(is_array($get_filter)) $filter= $get_filter+ $filter;
				$this->_grid($filter);
	}
	
	public function edit_post()
	{
		$this->label_action= '编辑类型';
		$this->_init_breadcrumb($this->label_action);
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$pk= $model->table_primary_key();
	
		$this->load->library('form_validation');
		$post= $this->input->post();
		$labels= $model->attribute_labels();
		$base_rules= array(
				'name'=> array(
						'field' => 'name',
						'label' => $labels['name'],
						'rules' => 'trim|required',
				),
				'hotel_id'=> array(
						'field' => 'hotel_id',
						'label' => $labels['hotel_id'],
						'rules' => 'trim|required',
				),
				'status'=> array(
						'field' => 'status',
						'label' => $labels['status'],
						'rules' => 'trim',
				)
		);
	
		$adminid= $this->session->get_admin_id();
		if( empty($post[$pk]) ){
			//add data.
			$this->form_validation->set_rules($base_rules);
	
			if ($this->form_validation->run() != FALSE) {
				$post['create_time'] = time();
				$post['update_time']= time();
				$post['inter_id']    = $this->session->get_admin_inter_id();
				$result= $model->m_sets($post)->m_save($post);
				$message= ($result)?
				$this->session->put_success_msg('已新增数据！'):
				$this->session->put_notice_msg('此次数据保存失败！');
				$this->_log($model);
				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
			} else
				$model= $this->_load_model();
	
		} else {
			$this->form_validation->set_rules($base_rules);
			if ($this->form_validation->run() != FALSE) {
				$post['update_time']= time();
				$result= $model->m_sets($post)->m_save($post);
				$message= ($result)?
				$this->session->put_success_msg('已保存数据！'):
				$this->session->put_notice_msg('此次数据修改失败！');
				$this->_log($model);
				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
			} else
				$model= $model->load($post[$pk]);
		}
	
		//验证失败的情况
		$validat_obj= _get_validation_object();
		$message= $validat_obj->error_html();
		//页面没有发生跳转时用寄存器存储消息
		$this->session->put_error_msg($message, 'register');
	
		$fields_config= $model->get_field_config('form');
		$view_params= array(
				'model'=> $model,
				'fields_config'=> $fields_config,
				'check_data'=> TRUE,
		);
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		echo $html;
	}
	
	
	
	
	
	public function refund_notify(){
		$notify = new PayNotifyCallBack();
		$data = $notify->Handle(false);
		if(is_array($data)){
			if("SUCCESS" == $data['return_code']){
				$trade_no = $data['transaction_id'];
				$out_refund_no = $data['out_refund_no'];
				
				$refund_data = array();
				$refund_data['appid'] = $data['appid'];
				$refund_data['mch_id'] = $data['mch_id'];
				$refund_data['refund_id'] = $data['refund_id'];
				$refund_data['refund_fee'] = $data['refund_fee'];
				$refund_data['coupon_refund_fee'] = $data['coupon_refund_fee'];
				
				$this->load->model("okpay/Okpay_refund_model");
				$r = $this->Okpay_refund_model->set_okpay_refund($out_refund_no,$trade_no,$refund_data);
				if($r){
					
				}else{
					//echo json_encode(array('status'=>0,'errmsg'=>'fail'));
				}
			}else{
				
				
			}
			
		}
	}
	
	
	
	
}
	

/* global $application_folder;
require_once $application_folder . '/libraries/WxPaySDK/lib/WxPay.Api.php';
require_once $application_folder . '/libraries/WxPaySDK/lib/WxPay.Notify.php';
class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryrefund($refund_id)
	{
		$input = new WxPayRefundQuery();
		$input->SetRefund_id($refund_id);
		$result = WxPayApi::refundQuery($input);
		
		if(array_key_exists("return_code", $result)
				&& array_key_exists("result_code", $result)
				&& $result["return_code"] == "SUCCESS"
				&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}

	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		//$notfiyOutput = array();
		if(!array_key_exists("refund_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		
		//查询订单，判断订单真实性
		if(!$this->Queryrefund($data["refund_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return $data;
	}
	
	
} */