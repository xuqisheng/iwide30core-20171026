<?php

class Sales_reserve extends MY_Admin_Soma {

	const DEV = FALSE;
	// 是否开启审核功能标识
	const WHOLESALE_REVIEW_FLAG = TRUE;

	protected function main_model_name() {
		return 'soma/Sales_reserve_model';
	}

	protected function write_log( $content, $file='', $path=NULL ) {
        $file= $file . date('Y-m-d'). '.txt';
        if(!$path) $path= APPPATH. 'logs'. DS. 'soma'. DS . 'wholesale' . DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        // $content= str_repeat('-', 40). "\n[". date('Y-m-d H:i:s'). ']'
        //     ."\n". $ip. "\n". $content. "\n";
        $content = $ip . ' ' . date('Y-m-d H:i:s') . ':' . $content;
        fwrite($fp, $content);
        fclose($fp);
    }

	// 显示预订单列表
	public function grid() {
		$this->label_action = "大客户预订单列表";
	    $filter = $this->_grid_filter();
		$this->_grid($filter);
	}

	private function _grid_filter() {
		$filter = array();
		
		$inter_id = $this->session->get_admin_inter_id();
		// 获取测试的$inter_id
		// $inter_id = $this->session->get_temp_inter_id();
		if(self::DEV) { $inter_id = 'a450089706'; }

		if(!$inter_id) {
			$inter_id = 'deny';
		}
		$filter['inter_id'] = $inter_id;

		$hotel_ids = $this->session->get_admin_hotels();
		if(!$hotel_ids) {
			$filter['hotel_id'] = array();
		} else {
			$filter['hotel_id'] = explode(',', $hotel_ids );
		}

		if(is_ajax_request()){
			$model_name= $this->main_model_name();
	    	$model= $this->_load_model($model_name);
			$get_filter= $this->_ajax_params_parse( $this->input->post(null, true), $model );
		} else {
			$get_filter= $this->input->get('filter', true);
		}
		if(is_array($get_filter)) {
			if(isset($get_filter['inter_id'])) {
				unset($get_filter['inter_id']);
			}
			if(isset($get_filter['hotel_id'])) {
				unset($get_filter['hotel_id']);
			}
			$filter += $get_filter;
		}

		return $filter;
	}

	/**
	 * 
	 * @return [type] [description]
	 */
	public function edit() {
		$this->label_action= '大客户订单处理';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids', true));
		if($id){
			$model= $model->load($id);
		}

        if(!$model) $model= $this->_load_model();
		$fields_config= $model->get_field_config('form');

		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

		$comfirm_info_arr = array(
			'grand_total',
			'product_price',
			'salesman',
			'comfirmed_status',
			'comfirmed_note',
			'comfirmed_time',
			'comfirmed_user',
		);

		$review_info_arr = array(
			'reviewed_status',
			'reviewed_note',
			'reviewed_time',
			'reviewed_user',
		);
		$comfirm_fields = array();
		foreach ($comfirm_info_arr as $k) {
			$comfirm_fields[$k] = $fields_config[$k];
			if(!in_array($k, array('grand_total','comfirmed_status','product_price'))) {unset($fields_config[$k]);}
		}

		$review_info_arr = array(
			'reviewed_status',
			'reviewed_note',
			'reviewed_time',
			'reviewed_user',
		);
		$review_fields = array();
		foreach ($review_info_arr as $k) {
			$review_fields[$k] = $fields_config[$k];
			if(!in_array($k, array('reviewed_status'))) {unset($fields_config[$k]);}
		}

		/*
		$comfirm_info_arr = array(
			'grand_total',
			'salesman',
			'comfirmed_status',
			'comfirmed_note',
			'comfirmed_time',
			'comfirmed_user',
			'reviewed_status',
			'reviewed_note',
			'reviewed_time',
			'reviewed_user',
		);
		
		foreach ($fields_config as $k => $v) {
			if(in_array($k, $comfirm_info_arr)) {
				$comfirm_fields[$k] = $v;
				if(!in_array($k, array('grand_total','comfirmed_status','reviewed_status'))){
					unset($fields_config[$k]);
				}
			}
		}
		*/

		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'comfirm_fields' => $comfirm_fields,
		    'review_fields' => $review_fields,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	/**
	 * 确认预订购订单，保存确认信息
	 * 1.form_validation校验数据是否正确
	 * 2.先根据reserve_id 加载到预订购订单
	 * 3.设置预订购订单确认数据--->直接将post里面的数据设置到reserve_order模型
	 * 4.开启事务
	 * 5.生成大客户订单，获取大客户订单ID（总细单）
	 * 6.reserve_order模型设置大客户订单ID，保存数据
	 * 7.关闭事务
	 * 8.返回订单列表界面
	 * @return [type] [description]
	 */
	public function comfirm() {

		$this->write_log("================大客户订单确认逻辑==================\r\n");

		$post = $this->input->post(null, true);
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);

		$admin = $this->session->admin_profile;

		$this->write_log("当前用户:" . $admin['username'] . "\r\n");
		$this->write_log("用户输入信息：" . json_encode($post) . "\r\n");
		$this->write_log("开始校验用户输入信息\r\n");

		$this->load->library('form_validation');
		$labels= $model->attribute_labels();
		$rules = array(
			'grand_total' => array(
				'field' => 'grand_total',
	            'label' => $labels['grand_total'],
	            'rules' => 'required'
			),
			'salesman' => array(
				'field' => 'salesman',
				'label' => $labels['salesman'],
				'rules' => 'required',
			),
		);
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE) {

			$this->write_log("错误：用户输入信息校验失败，不操作任何数据，逻辑结束\r\n");

			$this->session->put_error_msg('提交数据有误，请检查后再提交');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/edit', array('ids' => $post['reserve_id'])));
		} else {
			
			$this->write_log("校验成功，加载大客户订单(" . $post['reserve_id'] . ")\r\n");

			$model = $model->load($post['reserve_id']);
			if($model === NULL) {

				$this->write_log("错误：加载大客户订单(" . $post['reserve_id'] . ")失败，未操作任何数据，逻辑结束\r\n");

				$this->session->put_error_msg('找不到订单记录');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
			} else {
				// 没办法开启数据库事务：两个连贯操作无法共用同一个数据库链接
				
				$this->write_log("加载预订购订单(" . $post['reserve_id'] . ")成功，处理订单确认数据\r\n");

				try {
					$data = array();
					$data['grand_total'] = $post['grand_total'];
					$data['salesman'] = $post['salesman'];
					$data['comfirmed_note'] = $post['comfirmed_note'];
					$data['comfirmed_status'] = $post['comfirmed_status'];	//订单状态应该由用户提交
					$data['comfirmed_time'] = date('Y-m-d H:i:s');
					$data['update_time'] = $data['comfirmed_time'];
					$admin = $this->session->admin_profile;
					$data['comfirmed_user'] = $admin['username'];
					$model->m_sets($data);

					if($post['comfirmed_status'] == $model::STATUS_SUCCESS) {

						$this->write_log("根据用户输入的确认状态，生成销售订单\r\n");
						
						// 确认成功，生成销售订单
						$order = $model->generate_order();
						if(!$order) {
							// 生成销售订单失败，日志记录一下
							
							$this->write_log("错误：生成销售订单失败，未操作任何数据，逻辑结束\r\n");

							$this->session->put_error_msg('无法生成有效订单，请检查预订产品信息是否正常，核对后再尝试操作');
							$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
						}
						
						$this->write_log("生成销售订单(销售单号；" . $order->order_id . ")成功\r\n");

						$model->m_set('order_id', $order->order_id);
					}				

					$model->m_save();	

					$this->write_log("成功：大客户预订订单(" . $post['reserve_id'] . ")确认成功\r\n");

					// 不开启审核流程时直接支付订单并分配资产
					if(!self::WHOLESALE_REVIEW_FLAG) {
						$this->write_log("未开启人工审核流程，开始执行自动审核\r\n");

						$reviewed_status = $model::STATUS_FAILURE;
						if($model->m_get('comfirmed_status') == $model::STATUS_SUCCESS) {
							$reviewed_status = $model::STATUS_SUCCESS;
						}

						$data = array(
							'reserve_id' => $model->m_get('reserve_id'),
							'reviewed_status' => $reviewed_status,
							'reviewed_note' => '未开启人工审核，根据确认信息自动审核',
						);
						$this->review($data);
					}

					$this->session->put_success_msg('操作成功');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
				} catch (Exception $e) {
					// 记录一下异常日志
					
					$this->write_log("出现异常：" . $e->getTraceAsString() . "\r\n");

					$this->session->put_error_msg('订单异常，请稍后再尝试操作');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
				}
			}
		}
	}

	/**
	 * 审核订单
	 * 1.根据reserve_id 加载预订购订单
	 * 2.根据获取到的预订购订单中的销售订单ID，加载销售订单
	 * 3.开启事务
	 * 4.更新预订购订单审核信息
	 * 5.分配订单资产//这个可能在comfirm中做
	 * 6.关闭事务
	 * 7.返回订单列表界面
	 * @return [type] [description]
	 */
	public function review($data = array()) {
		
		$this->write_log("================大客户订单审核逻辑==================\r\n");

		$post = $data;
		if(count($post) == 0){
			$post = $this->input->post(null, true);
		}

		$redis = $this->get_redis_instance();
		$key = 'SOMA:WHOLESALE_REVIEW_' . $post['reserve_id'];
		if (!$redis->setnx($key, 'lock')) {
			$this->session->put_error_msg('系统正在执行预订单审核操作，请耐心等待，勿重复操作！');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}
		$redis->setex($key, 60, 'lock');

		$admin = $this->session->admin_profile;
		$this->write_log("当前用户:" . $admin['username'] . "\r\n");
		$this->write_log("用户输入信息：" . json_encode($post) . "\r\n");
		$this->write_log("开始加载预订订单(" . $post['reserve_id'] . ")\r\n");

		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$model = $model->load($post['reserve_id']);
		if($model === NULL) {
			
			$this->write_log("错误：加载预订订单(" . $post['reserve_id'] . ")失败，逻辑结束\r\n");

			$this->session->put_error_msg('找不到订单记录');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

		$this->write_log("加载预订订单完毕\r\n");
		$this->write_log("开始加载相应的销售订单(". $model->order_id .")\r\n");

		$order = $model->get_order();
		if($order === NULL) {

			$this->write_log("错误：加载预订订单(" . $post['reserve_id'] . ")的销售订单失败，逻辑结束\r\n");

			$this->session->put_error_msg('订单记录异常，无对应销售订单记录');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

		$this->write_log("加载销售订单完毕\r\n");
		$this->write_log("根据用户输入的审核状态(". $post['reviewed_status'] .")执行审核操作\r\n");
		$this->write_log("1.审核通过-->订单支付;2.审核不通过-->订单取消;3.待审核不处理，保存用户提交数据\r\n");

		// 1.审核通过---->订单支付
		// 2.审核不通过-->订单取消
		// 3.待审核不处理，保存用户提交数据
		try {
			$op_flag = FALSE;
			switch ($post['reviewed_status']) {
				case $model::STATUS_SUCCESS:
					$this->write_log("执行销售订单(" . $order->order_id . ")支付\r\n");
					$op_flag = $this->wholesale_pay($order);
					break;
				case $model::STATUS_FAILURE:
					$this->write_log("执行销售订单" . $order->order_id . "取消\r\n");
					$op_flag = TRUE;
					// $op_flag = $order->order_cancel($model->business, $model->inter_id);
					break;
				case $model::STATUS_WAITTING:
					$op_flag = TRUE;
					break;
				default:
					// 提交了错误数据
					break;
			}

			if(!$op_flag) {
				// 出现异常，记录日志，不处理订单
				
				$this->write_log("错误：操作失败，尚未更新数据，逻辑结束\r\n");

				$this->session->put_error_msg('操作失败，请稍后尝试');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
			}

			$data['reviewed_status'] = $post['reviewed_status'];
			$data['reviewed_note'] = $post['reviewed_note'];
			$data['reviewed_time'] = date('Y-m-d H:i:s');
			$data['update_time'] = $data['reviewed_time'];
			$admin = $this->session->admin_profile;
			$data['reviewed_user'] = $admin['username'];
			$model->m_sets($data)->m_save();

			$this->write_log('开始发送模板消息');
			//发送模版消息
    		$this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
    		$MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
    		$inter_id = $model->m_get('inter_id');
    		$openid = $model->m_get('openid');
    		$business = $model->m_get('business');
    		$MessageWxtempTemplateModel->send_template_by_big_customer_success( $model, $openid, $inter_id, $business);

			$this->write_log("审核逻辑结束：审核操作成功\r\n");

			$this->session->put_success_msg('操作成功');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
			
		} catch (Exception $e) {
			// 记录异常
			
			$this->write_log("出现异常：" . $e->getTraceAsString() . "\r\n");

			$this->session->put_error_msg('操作失败，请稍后尝试');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}	
	}

	// 模拟订单支付
	protected function wholesale_pay($order) {
		// var_dump($order->business);exit;
		$data = $order->m_data();
		try {
			$this->load->model('soma/sales_payment_model');
	        $payment_model= $this->sales_payment_model;
			$log_data= array();
			$log_data['paid_ip'] = $this->input->ip_address();
			$log_data['paid_type']= $payment_model::PAY_TYPE_XX;
			/*
			$log_data['order_id'] = $order->order_id;
			$log_data['openid'] = $order->openid;
			$log_data['business'] = $order->business;
			$log_data['settlement'] = $order->settlement;
			$log_data['inter_id'] = $order->inter_id;
	        $log_data['hotel_id'] = $order->hotel_id;
	        $log_data['grand_total'] = $order->grand_total;
	        */
	        $log_data['order_id'] = $data['order_id'];
			$log_data['openid'] = $data['openid'];
			$log_data['business'] = $data['business'];
			$log_data['settlement'] = $data['settlement'];
			$log_data['inter_id'] = $data['inter_id'];
	        $log_data['hotel_id'] = $data['hotel_id'];
	        $log_data['grand_total'] = $data['grand_total'];
	        $log_data['transaction_id'] = '-1';
	        
	        $order->order_payment( $log_data );
	        $order->order_payment_post( $log_data );
	        $payment_model->save_payment($log_data, NULL);
	        return TRUE;
	    } catch (Exception $e) {
	    	return FALSE;
	    }
	}

	protected function send_msg() {
		//发送模版消息
    $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
    $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
    
    $salesReserveModel = '';//$salesReserveModel->load( $reserve_id );
    $openid = '';//客户openid
    $MessageWxtempTemplateModel->send_template_by_big_customer_success( $salesReserveModel, $openid, $inter_id, $business);
	}

	/**
     * Gets the redis instance.
     *
     * @param      string $select The select
     *
     * @return     Redis|null  The redis instance.
     */
    public function get_redis_instance($select = 'soma_redis')
    {
        $this->load->library('Redis_selector');
        if ($redis = $this->redis_selector->get_soma_redis($select)) {
            return $redis;
        }

        return null;
    }

}