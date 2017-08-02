<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends MY_Front_Soma {

    public  $themeConfig;
    public  $theme = 'default';

    protected $log_path;

	public function __construct()
	{
		parent::__construct();
        //theme
        $this->load->model('soma/Theme_config_model');
        $this->themeConfig = $themeConfig = $this->Theme_config_model->get_using_theme($this->inter_id);
        $this->theme = $themeConfig['theme_path'];

        // 加载日志模块
        $this->load->helper('soma/package');
        $this->log_path = APPPATH. 'logs'. DS. 'soma'. DS . 'voucher' . DS;

	}

	public function index() {
		
		$this->load->model('soma/sales_voucher_theme_model', 't_model');
		$theme = $this->t_model->load_by_inter_id($this->inter_id);
		$record_url = Soma_const_url::inst()->get_url( 'soma/order/my_order_list', array('id' => $this->inter_id) );
		$soma_index_url = Soma_const_url::inst()->get_url('soma/package/index', array('id' => $this->inter_id));
		$member_center_url = base_url("index.php/membervip/center?id=" . $this->inter_id);

		$this->_view('header', array('title'=>'兑换券兑换页面'));
		$this->_view('index', array('theme' => $theme, 'record_url'=>$record_url, 'soma_index_url' => $soma_index_url, 'member_center_url' => $member_center_url));
	}

	//到店兑换方法
	// 流程：券码校验-->生成订单-->模拟支付-->生成兑换信息
	public function scaner_exchange()
	{
	    //仿照 front/soma/Order-> get_order_id_by_ajax()下单流程，注意保存 sales_order_payment支付记录
	    
		$cache= $this->_load_cache();
		$redis= $cache->redis->redis_instance();
		// $redis = $this->get_redis_instance();
		$lock_key = 'SOMA:VOUCHER_' . $this->input->post('code', true);
		$lock = $redis->setnx($lock_key, 'lock');
		if(!$lock) {
			// $this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
			write_log( __FUNCTION__ . ' ERROR', NULL, $this->log_path );
			echo json_encode(array('status' => Soma_base::STATUS_FALSE, 'message' => '正在快马加鞭的为您兑换，请耐心等待。。。'));exit;
		}

		$lock = $redis->setex($lock_key, 60, 'lock');

	    write_log( __FUNCTION__ . ' START', NULL, $this->log_path );
	    
	    $_log_data['input'] = array(
	    	'post' => $this->input->post(),
	    	'get' => $this->input->get(),
	    );
		write_log(json_encode($_log_data), NULL, $this->log_path);

	    $code = $this->input->post('code', true);
	    $valid_data = array('inter_id' => $this->inter_id);
	    $this->load->model('soma/Sales_voucher_model', 'v_model');
	    $this->v_model->load_by_code($code);

	    $op_res = array('status' => Soma_base::STATUS_FALSE, 'message' => '操作失败，请稍后重新尝试!');
	    
	    if($this->v_model->code_validation($valid_data)) {
	    	try {

	    		$_log_data['inter_id'] = $this->inter_id;
	    		$_log_data['voucher'] = array('code' => $code, 'status' => $this->v_model->m_get('status'));
	    		write_log( json_encode($_log_data), NULL, $this->log_path );
	    		
	    		$order = $this->v_model->generate_order($this->openid);
	    		if($order) {

	    			$_log_data['order'] = array(
	    				'order_id' => $order->m_get('order_id'),
	    				'status' => $order->m_get('status'),
	    			);
	    			write_log( json_encode($_log_data), NULL, $this->log_path );

	    			$payment = $this->v_model->virtual_pay($order);
	    			if($payment) {

	    				//luguihong 2017/02/27 特权券商品，礼品卡券兑换成功，若是不可转赠商品，则自动加入卡包
                        $this->load->model('soma/Consumer_order_model', 'somaConsumerModel');
                        $this->somaConsumerModel->package_consumer($order->m_get('order_id'), $order->m_get('openid'), $this->inter_id, $order->m_get('business'));

	    				$_log_data['order'] = array(
	    					'order_id' => $order->m_get('order_id'),
	    					'status' => $order->m_get('status'),
	    				);
	    				write_log( json_encode($_log_data), NULL, $this->log_path );

	    				$exchange = $this->v_model->exchange($order, $this->openid);
	    				if($exchange) {

	    					$_log_data['voucher'] = array('code' => $code, 'status' => $this->v_model->m_get('status'));
	    					write_log( json_encode($_log_data), NULL, $this->log_path );

	    					$op_res['status'] = Soma_base::STATUS_TRUE;
	    					$params = array(
	    						'id' => $this->inter_id,
	    						'oid' => $order->m_get('order_id'),
	    						'bsn' => $order->m_get('business'),
	    					);
	    					$url = Soma_const_url::inst()->get_url('*/order/order_detail',$params);
	    					$op_res['data'] = array('url' => $url);
	    				}
	    			} 
	    		}
	    	} catch (Exception $e) {
	    		// 默认失败信息
	    		$_log_data['exception'] = $e;
	    		write_log( json_encode($_log_data), NULL, $this->log_path );
	    	}
	    } else {
	    	$op_res['message'] = $this->v_model->code_valid_error();

	    	$_log_data['error_msg'] = $op_res['message'];
	    	write_log( json_encode($_log_data), NULL, $this->log_path );

	    }
	    
	    $redis->delete($lock_key);

	    echo json_encode($op_res);

	    write_log( __FUNCTION__ . ' END', NULL, $this->log_path );
	}

	//到店直接消费方法
	public function scaner_consume()
	{
	    //流程：保存订单->跳过资产保存-> 执行消费订单保存-> Consumer_item_package_model::save_item_from_order_item()
	    
	    
	}
    
	//展示为以后的皮肤做扩展
	protected function _view($file, $datas=array() )
	{
        parent::_view( 'voucher'. DS. $file, $datas);
	}
	
}
