<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once dirname(__FILE__). DS. 'Consumer_item_interface.php';
class Consumer_item_package_model extends MY_Model_Soma 
    implements Consumer_item_interface {

    /** 消费细单的处理过程根据不同的business不同而节点有所不同，需要在item中细化 **/
    //const STATUS_ITEM_WAITING = 1;
    const STATUS_ITEM_PENDING = 2;
    const STATUS_ITEM_CONSUME = 3;
    const STATUS_ITEM_SHIPPING= 4;
    
    public function get_item_status_label(){
        return array(
            //self::STATUS_ITEM_WAITING => '未预约',
            self::STATUS_ITEM_PENDING => $this->lang->line('to_be_redapt'),
            self::STATUS_ITEM_CONSUME => $this->lang->line('redapted'),
            self::STATUS_ITEM_SHIPPING=> $this->lang->line('mailed'),
        );
    }
    //获取已经消费的状态
    public function get_consumer_yes()
    {
    	return array(
			self::STATUS_ITEM_CONSUME,
			self::STATUS_ITEM_SHIPPING,
		);
    }

    /**
     * 细单对象(数组)
     * @var Array 
     */
    public $order_item= array();

    public function main_table_name($business='package', $inter_id=NULL)
    {
        return $this->_shard_table("soma_consumer_order", $inter_id);
    }
    public function table_name($business='package', $inter_id=NULL)
    {
        return $this->_shard_table("soma_consumer_order_item_{$business}", $inter_id);
    }
    public function asset_item_table_name($business, $inter_id=NULL)
    {
        return $this->_shard_table("soma_asset_item_{$business}", $inter_id);
    }
    public function sales_order_table_name( $inter_id=NULL)
    {
        return $this->_shard_table("soma_sales_order", $inter_id);
    }
    public function sales_order_idx_table_name( $inter_id=NULL)
    {
        return $this->_shard_table("soma_sales_order_idx", $inter_id);
    }
	public function table_primary_key()
	{
	    return 'item_id';
	}

	/**
	 * 字段映射，key中字段将直接转移到item
	 * @return multitype:string
	 */
	public function order_item_field_mapping()
	{
	    return array(
	        'product_id'=> 'product_id',
	        'type' => 'type',
            'goods_type' => 'goods_type',
	        'inter_id'=> 'inter_id',
	        'hotel_id'=> 'hotel_id',
	        'sku'=> 'sku',
	        'name'=> 'name',
	        'name_en'=> 'name_en',
	        'price_market'=> 'price_market',
	        'price_package'=> 'price_package',
	        'compose'=> 'compose',
	        'compose_en'=> 'compose_en',
	        'face_img'=> 'face_img',
	        'transparent_img'=> 'transparent_img',
	        'use_cnt'=> 'use_cnt',
	        'can_split_use'=> 'can_split_use',
	        'can_wx_booking'=> 'can_wx_booking',
	        'can_refund'=> 'can_refund',
	        'can_mail'=> 'can_mail',
	        'can_gift'=> 'can_gift',
	        'can_pickup'=> 'can_pickup',
            'can_sms_notify' => 'can_sms_notify',
	        'can_invoice'=> 'can_invoice',
	        'can_reserve'=> 'can_reserve',
	        'room_id'=> 'room_id',
	        'hotel_name'=> 'hotel_name',
	        'validity_date'=> 'validity_date',
	        'expiration_date'=> 'expiration_date',
	        'openid'=> 'openid',
	        'order_item_id'=> 'item_id',
	        'order_id'=> 'order_id',
	    );
	}
	public function asset_item_field_mapping()
	{
	    return array(
	        'product_id'=> 'product_id',
	        'type' => 'type',
            'goods_type' => 'goods_type',
	        'inter_id'=> 'inter_id',
	        'hotel_id'=> 'hotel_id',
	        'sku'=> 'sku',
	        'name'=> 'name',
	        'name_en'=> 'name_en',
	        'price_market'=> 'price_market',
	        'price_package'=> 'price_package',
	        'compose'=> 'compose',
	        'compose_en'=> 'compose_en',
	        'face_img'=> 'face_img',
	        'transparent_img'=> 'transparent_img',
	        'use_cnt'=> 'use_cnt',
	        'can_split_use'=> 'can_split_use',
	        'can_wx_booking'=> 'can_wx_booking',
	        'can_refund'=> 'can_refund',
	        'can_mail'=> 'can_mail',
	        'can_gift'=> 'can_gift',
	        'can_pickup'=> 'can_pickup',
	        'can_invoice'=> 'can_invoice',
	        'can_reserve'=> 'can_reserve',
	        'room_id'=> 'room_id',
	        'hotel_name'=> 'hotel_name',
	        'validity_date'=> 'validity_date',
	        'expiration_date'=> 'expiration_date',
	        'openid'=> 'openid',
	        'asset_item_id'=> 'item_id',
	        'order_item_id'=> 'order_item_id',
	        'order_id'=> 'order_id',
	    );
	}

	public function attribute_labels()
	{
	    return array(
	        'consumer_id'=> '消费编号',
	        'order_id'=> '订单ID',
	        'inter_id'=> '公众号',
	        'hotel_id'=> '酒店',
	        'sku'=> 'SKU',
	        'name'=> '产品名称',
	        'face_img'=> '缩略图',
	        'price_market'=> '市场价',
	        'price_package'=> '微信价格',
	        'compose'=> '商品内容',
	        'consumer_qty'=> '份数',
	        'hotel_name'=> '酒店名称',
	        'validity_date'=> '生效日期',
	        'expiration_date'=> '失效日期',
	        'status'=> '状态',
	    );
	}
	
	//################# 以上为非必要函数，下面为业务必要函数 #####################

	/**
	 * 获取订单细单数组
	 */
	public function get_order_items($order, $inter_id)
	{
	    $opk = $order->table_primary_key();
	    $order_id= $order->m_get($opk);
	    $table= $this->table_name($order->business, $inter_id );
	    $data= $this->_shard_db_r('iwide_soma_r')
    	    ->get_where($table, array($opk => $order_id ))
    	    ->result_array();
	    return $data;
	}

	public function get_order_items_byIds($ids, $business, $inter_id)
	{
	    $table = $this->_db()->dbprefix( $this->table_name($business, $inter_id) );
	    $items= $this->_shard_db_r('iwide_soma_r')
	    ->where_in( 'consumer_id', $ids )
	    ->get($table)->result_array();
	    return $items;
	}
	public function get_order_items_byAssetItemIds($ids, $business, $inter_id)
	{
	    $table = $this->_db()->dbprefix( $this->table_name($business, $inter_id) );
	    $items= $this->_shard_db_r('iwide_soma_r')
    	    ->where_in( 'asset_item_id', $ids )
    	    ->get($table)->result_array();
	    return $items;
	}
	
	/**
	 * 从order item 保存细单，用于预付费券类订单保存
	 */
    public function save_item_from_order_item($consumer, $inter_id)
    {
        try {
            $debug = TRUE;

            $order = $consumer->order;
            	
            $data= array();
            $item= $consumer->order_item;
            $business= isset( $consumer->business ) ? $consumer->business : 'package';
            
            if( count($item)==0 ){
                Soma_base::inst()->show_exception('没有传入订单信息！');
            }
             
            //统一获取订单号
            $this->load->model('soma/ticket_center_model');
            $consumer_id= $this->ticket_center_model->get_increment_id_consumer($business);
            if( !$consumer_id ){
                Soma_base::inst()->show_exception('排队消费人山人海，系统正玩命加载中，请稍后再试。');
            }
            
            $CI = & get_instance();
            $remote_ip= $CI->input->ip_address();
            
            //处理消费主单
            $detail= array(
                'business'=> $business,
                'consumer_id'=> $consumer_id,
                'inter_id'=> $item[0]['inter_id'],
                'hotel_id'=> $item[0]['hotel_id'],
                'remote_ip'=> $remote_ip,
                'status'=> $consumer::STATUS_ALLUSE,
                'consumer_type'=> $consumer::CONSUME_TYPE_DEFAULT,
                'related_id'=> NULL,
		        'consumer_method'=> $consumer::CONSUME_VOUCHER_SELF,
		        'consumer'=> $consumer->consumer_person,//核销人
		        'consumer_time'=>date('Y-m-d H:i:s',time()),
		        'row_qty'=> 1,
            );

            $table= $consumer->table_name($business, $inter_id );
            $consumer_result= $consumer->_shard_db($inter_id)->insert($table, $detail);
		    if( $debug )$this->_write_log('兑换码消费主单：'.json_encode( $consumer_result ).', 消费ID：'.$consumer_id);
            
            if( $consumer_result ){
		    	$consumer = $consumer->load( $consumer_id );
		    }

            //处理消费细单
            $sales_order_ids= array();
            foreach ($item as $k=>$v){
                foreach ($this->order_item_field_mapping() as $sk=> $sv){
                    $data[$k][$sk]= isset($v[$sv])? $v[$sv]: '';
                    if( !in_array( $v['order_id'], $sales_order_ids ) ){

	                	$sales_order_ids[]= $v['order_id'];
	                }
                }
                $data[$k]['consumer_id']= $consumer_id;
	            $data[$k]['consumer_qty']= $consumer->consumer_qty;//暂时兑换码的核销数量为1
	            $data[$k]['status'] = self::STATUS_ITEM_CONSUME;//已核销
            }

            $table= $this->table_name($business, $inter_id );
            $consumer_item_result= $consumer->_shard_db($inter_id)->insert_batch($table, $data);
            $item_id = $consumer->_shard_db($inter_id)->insert_id();
		    if( $debug )$this->_write_log('兑换码消费细单：'.json_encode( $consumer_item_result ).', 消费细单ID：'.$item_id);

		    //生成兑换码记录
			$voucher = $consumer->voucher;
		    $CI = & get_instance();
		    $CI->load->model('soma/Sales_voucher_exchange_model','SalesVoucherExchangeModel');
		    $SalesVoucherExchangeModel = $CI->SalesVoucherExchangeModel;
		    $code_result = $SalesVoucherExchangeModel->record_exchange($order, $voucher, null, $voucher->admin, $consumer);
		    if( $debug )$this->_write_log('生成兑换码记录：'.json_encode( $code_result ));

		    //修改订单状态
            $order_update = array(
                    'update_time'=>date('Y-m-d H:i:s',time()),
                    'consume_status'=>$order::CONSUME_ALL,
                    'status'=>$order::STATUS_PAYMENT,
                );
            $sales_order_table= $this->sales_order_table_name($inter_id);
		    $order_result = $consumer->_shard_db($inter_id)->where_in( 'order_id', $sales_order_ids )
		       ->update($sales_order_table, $order_update );
		    if( $debug )$this->_write_log('修改订单状态：'.json_encode( $order_result ));

            //修改订单索引表的状态
            $order_idx_update = array(
                'status'=>$order::STATUS_PAYMENT,
            );
            $sales_order_idx_table= $this->sales_order_idx_table_name($inter_id);
            $order_item_result = $consumer->_shard_db($inter_id)->where_in( 'order_id', $sales_order_ids )
                ->update($sales_order_idx_table, $order_idx_update );
            if( $debug )$this->_write_log('修改订单状态：'.json_encode( $order_item_result ));

		    //修改兑换码的状态
			$voucher_update = array(
					'status'=>$voucher::STATUS_USED,
				);
			$voucher_table = $voucher->table_name( $inter_id );
			$voucher_pk = $voucher->table_primary_key();
			$update_code_result = $consumer->_shard_db($inter_id)->where( array( $voucher_pk=>$voucher->m_get($voucher_pk) ) )
		       ->update($voucher_table, $voucher_update );
		    if( $debug )$this->_write_log('修改兑换码的状态：'.json_encode( $update_code_result ));

		    if( $consumer_result && $consumer_item_result && $code_result && $order_result && $order_item_result && $update_code_result )
            {
                return TRUE;
            } else {
		        return FALSE;
            }
            //return $result;

		} catch (Exception $e) {
			// return FALSE;
			Soma_base::inst()->show_exception('消费过程遇到错误，请联系客服！');
		}
	} 

	/**
	 * 从asset item 保存细单
	 */
	public function save_item_from_asset_item($consumer, $inter_id, $business)
	{
		try {
			$debug = TRUE;
			
			$data= array();
		    $item= $consumer->asset_item;
		    $business= !empty( $business ) ? strtolower( $business ) : 'package';
		    
		    if( count($item)==0 ){
		        // return FALSE;
		        Soma_base::inst()->show_exception('消费失败，找不到购买商品！');
		    }

		    //统一获取订单号
	        $this->load->model('soma/ticket_center_model');
	        $consumer_id= $this->ticket_center_model->get_increment_id_consumer($business);
		    if( !$consumer_id ){
		        Soma_base::inst()->show_exception('排队消费人山人海，系统正玩命加载中，请稍后再试。');
		    }

		    $consumer_time = isset( $item[0]['consumer_time'] ) && !empty( $item[0]['consumer_time'] ) ? $item[0]['consumer_time'] : date( 'Y-m-d H:i:s', time() );//消费时间
		    $consume_status = isset( $item[0]['consume_status'] ) && !empty( $item[0]['consume_status'] ) ? $item[0]['consume_status'] : $consumer::STATUS_ALLUSE;//消费主单状态
		    $consumer_type = isset( $item[0]['consumer_type'] ) && !empty( $item[0]['consumer_type'] ) ? $item[0]['consumer_type'] : $consumer::CONSUME_TYPE_DEFAULT;//消费类型
		    $consume_item_status = isset( $item[0]['consume_item_status'] ) && !empty( $item[0]['consume_item_status'] ) ? $item[0]['consume_item_status'] : self::STATUS_ITEM_CONSUME;//消费细单状态
		    $consumer_qty = isset( $item[0]['minus_qty'] ) && !empty( $item[0]['minus_qty'] ) ? $item[0]['minus_qty'] : 1;//消费数量
		    $consumer_code = isset( $item[0]['consumer_code'] ) && !empty( $item[0]['consumer_code'] ) ? $item[0]['consumer_code'] : NULL;//核销码
		    $order_id = isset( $item[0]['order_id'] ) && !empty( $item[0]['order_id'] ) ? $item[0]['order_id'] : NULL;//订单ID
		    $consumer_method = isset( $item[0]['consumer_method'] ) && !empty( $item[0]['consumer_method'] ) ? $item[0]['consumer_method'] : NULL;//核销方式(自助核销／扫码核销...)
		    $consumer_person = isset( $item[0]['consumer'] ) && !empty( $item[0]['consumer'] ) ? $item[0]['consumer'] : NULL;//核销人
		    $hotel_id = isset( $item[0]['hotel_id'] ) && !empty( $item[0]['hotel_id'] ) ? $item[0]['hotel_id'] : NULL;//核销人

		    $salesOrderConsumeDefaultStatus = TRUE;//订单默认消费状态，部分消费
		    //消费的时候如果剩余数量为0并且赠送ID为空，标记为全部消费
		    if( isset( $item[0]['qty'] ) && ( $item[0]['qty'] == $consumer_qty ) && empty( $item[0]['gift_id'] ) ){
		    	// $consume_status = $consumer::STATUS_ALLUSE;//消费主单状态
		    	// $salesOrderConsumeDefaultStatus = FALSE;//订单消费状态,全部消费
		    }

		    // $can_reserve = $consumer->can_reserve;
		    // //消费主单的状态，如果是预约过来的，状态就为未使用，如果是不用预约核销过来的直接变为已使用
		    // if( $item[0]['can_reserve'] == $can_reserve ){
		    // 	$consumer_time = NULL;
		    // 	$consume_status = $consumer::STATUS_PENDING;//可以预约的生成消费主单，并且状态为未使用
		    // 	$consumer_type = $consumer::CONSUME_TYPE_DEFAULT;
		    	
		    // } else {
		    //     $consumer_time = date( 'Y-m-d H:i:s', time() );
		    // 	$consume_status = $consumer::STATUS_ALLUSE;//已使用
		    // 	$consumer_type = $consumer::CONSUME_TYPE_DEFAULT;
		    // }

		    //计算总消费数量
		    $row_qty = 0;
		    foreach($item as $k=>$v){
		    	if( isset( $v['minus_qty'] ) && $v['minus_qty'] ){
		    		$row_qty += $v['minus_qty'];//minus_qty这个参数是控制器传过来的，不一定会有这个值，没有就会默认1
		    	}
		    }

		    if( $row_qty > 0 ){
		    	$consumer_qty = $row_qty;
		    }

		    $CI = & get_instance();
		    $remote_ip= $CI->input->ip_address();
		     
		    $detail= array(
		        'business'=> $business,
		        'consumer_id'=> $consumer_id,
		        'inter_id'=> $item[0]['inter_id'],
		        'hotel_id'=> $hotel_id,
		        //'openid'=> $item[0]['openid'],  //后台核销无法获取真正的 openid;
		        'status'=> $consume_status,
		        'remote_ip'=> $remote_ip,
		        'consumer_type'=> $consumer_type,
		        'consumer_method'=> $consumer_method,
		        'consumer'=> $consumer_person,
		        'consumer_time'=>$consumer_time,
		        'row_qty'=> !empty( $row_qty ) ? $row_qty : $consumer_qty,
		        'related_id'=> NULL,
		    );

		    //消费主单
		    $table= $consumer->table_name( $inter_id );
		    $consuemr_result= $consumer->_shard_db($inter_id)->insert($table, $detail);
		    if( $debug )$this->_write_log('消费主单：'.json_encode( $consuemr_result ).', 消费ID：'.$consumer_id);
		    
		    if( $consuemr_result ){
		    	$this->load->library('session');
		    	$this->session->set_userdata('booking_hotel_consumer_id',$consumer_id);
		    	$consumer = $consumer->load( $consumer_id );
		    }else{
		    	return FALSE;
		    }

	        $sales_order_ids= array();
		    foreach ($item as $k=>$v){
	            foreach ($this->asset_item_field_mapping() as $sk=> $sv){
	                $data[$k][$sk]= isset($v[$sv])? $v[$sv]: '';
	                if( !in_array( $v['order_id'], $sales_order_ids ) ){

	                	$sales_order_ids[]= $v['order_id'];
	                }
	            }
	            $data[$k]['consumer_id']= $consumer_id;
	            $data[$k]['consumer_code']= isset( $v['consumer_code'] ) && !empty( $v['consumer_code'] ) ? $v['consumer_code'] : $consumer_code;
	            // $data[$k]['consumer_qty']= $consumer_qty;
	            $data[$k]['consumer_qty']= isset( $v['minus_qty'] ) && !empty( $v['minus_qty'] ) ? $v['minus_qty'] + 0 : $consumer_qty;
	            
	            //为无需预约的item标记status为已经预约
	            $data[$k]['status'] = $consume_item_status;
		    }

		    //消费细单
		    $table= $this->table_name($business, $inter_id );
		    $consumer_item_result= $consumer->_shard_db($inter_id)->insert_batch($table, $data);
		    $item_id = $consumer->_shard_db($inter_id)->insert_id();
		    if( $debug )$this->_write_log('消费细单：'.json_encode( $consumer_item_result ).', 消费细单ID：'.$item_id);

		    if( !$consumer_item_result ){
		    	return FALSE;
		    } else {
                $this->load->library('session');
                $this->session->set_userdata('booking_hotel_consumer_item_id',$item_id);
            }

		    /****处理订单消费状态start****/
            //这里核销数量为0的意思是，已经核销了，数量减少在前，判断在后，所以为0
		    $this->change_order_consumer_status( $consumer, $inter_id, $business, $sales_order_ids, 0 );
		    /****处理订单消费状态end****/

			//处理券
			$this->load->model('soma/Sales_order_discount_model');
	        $discount_model= $this->Sales_order_discount_model;
	        $discount_model->consume_discount($order_id, $inter_id);

	        //处理分销
	        $this->load->model('soma/Sales_order_model','SalesOrderModel');
        	$SalesOrderModel = $this->SalesOrderModel->load( $order_id );
        	if( $SalesOrderModel ){
	            $this->load->model('soma/Reward_benefit_model','RewardBenefitModel');
	            $RewardBenefitModel = $this->RewardBenefitModel;
	            $RewardBenefitModel->modify_benefit_queue_check( $inter_id, $SalesOrderModel );
        	}

	        //处理码的状态 核销和预约进来的
		    if( $consumer_code ){
		        $consumer_code_object_name= "Consumer_code_model";
				require_once dirname(__FILE__). DS. "Consumer_code_model.php";
		        $Consumer_code_model= new $consumer_code_object_name();
				$data = array();
				$data['consumer_id'] = $consumer_id;
				$data['consumer_item_id'] = $item_id;
				$code = $consumer_code;
				$code_result = $Consumer_code_model->consume_code( $consumer, $code, $data, $inter_id, $item[0]['item_id'] );
				if( $debug )$this->_write_log('消费码核销：'.json_encode( $code_result ).', 消费码：'.$consumer_code);
				if( !$code_result ){
					return FALSE;
				}
			}

            /**
             * 加载核销码的model
             */
            $consumer_code_object_name= "Consumer_code_model";
            require_once dirname(__FILE__). DS. "Consumer_code_model.php";
            $Consumer_code_model= new $consumer_code_object_name();

			//邮寄
			$shipping_status = $consumer::CONSUME_TYPE_SHIPPING;
			if( $consumer_type == $shipping_status ){

				$asset_item_id = isset( $item[0]['item_id'] ) && !empty( $item[0]['item_id'] ) ? $item[0]['item_id'] : NULL;

	            $filter = array();
	            // $filter['order_id'] = $order_id + 0; //赠送退回之后，没有order_id，只有asset_item_id
	            $filter['asset_item_id'] = $asset_item_id + 0;
	            $filter['status'] = $Consumer_code_model::STATUS_SIGNED;//取出没有消费的

	            $codeList = $Consumer_code_model->get_code_by_orderId( $filter, $consumer_qty, $inter_id );
                if( $debug )$this->_write_log( '消费码列表：'.json_encode( $codeList ) );

                $code_result = FALSE;
	            if( $codeList ){

		            $codeIds = array();
		            foreach ($codeList as $k => $v) {
		            	$codeIds[] = $v['code_id'];
		            }

		            if( count( $codeIds ) != 0 ){
		                $code_result = $Consumer_code_model->consume_code_by_mail( $consumer, $codeIds, $inter_id ,$consumer_id );
                        if( $debug )$this->_write_log( '处理码：'.json_encode( $code_result ).', 本次邮寄消费码处理ID：'.json_encode( $codeIds ) );
		            }
	            }

				//保存发货信息
				if( $code_result ){
					$consumer->order_id = $order_id;
					$consumer->hotel_id = $hotel_id;
					$consumer->consumer_id = $consumer_id;
					$consumer->consumer_qty = $consumer_qty;
			        $this->load->model('soma/Consumer_shipping_model','ConsumerShippingModel');
			        $shipping_result = $this->ConsumerShippingModel->save_shipping( $consumer, $consumer->address, $inter_id, $business, $item[0]['openid']);
			        if( $debug )$this->_write_log('邮寄：'.json_encode( $shipping_result ).', 订单ID：'.$order_id);
			        if( !$shipping_result ){
			        	return FALSE;
			        }

	                /**
	                 * 邮寄成功赠送会员礼包
	                 * @author     luguihong    2017/02/23
	                 */
	                $this->load->model('soma/Config_member_package_model','somaConfigMemberModel');
	                $somaConfigMemberModel = $this->somaConfigMemberModel;
	                $memberRecordData[] = array(
	                                'inter_id'      => $inter_id, 
	                                'openid'        => $item[0]['openid'], 
	                                'send_id'       => $order_id, 
	                                'product_id'    => $item[0]['product_id'], 
	                                'num'           => $consumer_qty, 
	                                'type'          => $somaConfigMemberModel::TYPE_MAIL_SUCCESS,
	                                'create_time'   => date('Y-m-d H:i:s'),
	                                'status'        => $somaConfigMemberModel::RECORD_STATUS_PENDING,
	                            );
	                $somaConfigMemberModel->insert_record( $consumer, $inter_id, $memberRecordData );
	                
			    }else{
			    	return FALSE;
			    }

			}

            //接口核销
            $apiMethod = $consumer::CONSUME_METHOD_API;
			if( $consumer_method == $apiMethod )
            {
                $codeIds = $consumer->codeIds;
                if( count( $codeIds ) > 0 )
                {
                    $code_result = $Consumer_code_model->consume_code_by_mail( $consumer, $codeIds, $inter_id ,$consumer_id);
                    if( $debug )$this->_write_log( '处理码：'.json_encode( $code_result ).', 本次接口核销消费码处理ID：'.json_encode( $codeIds ) );
                    if( !$code_result )
                    {
                        return FALSE;
                    }
                }
            }
		    
		    return TRUE;

		} catch (Exception $e) {
			// return FALSE;
			Soma_base::inst()->show_exception('消费过程遇到错误，请联系客服！');
		}
	    
	}
	
	
    public function _write_log( $content )
    {
        $path= APPPATH. 'logs'. DS. 'soma'. DS. 'consumer'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $file= $path. date('Y-m-d_H'). '.txt';
        $this->write_log($content, $file);
    }
	
	
	
	public function order_status_reflesh($consumer, $inter_id)
	{
	     
	}


	/**
	 * 核销(细单状态刷新)
	 * $model->consumer_order_consume( $consumer, $inter_id );
	 * @author luguihong@mofly.cn
	 */
	public function consumer_order_consume($consumer, $inter_id)
	{
		$order_item = isset( $consumer->order_item ) ? $consumer->order_item: '';
		if( !$order_item ){
			return FALSE;
		}

		//标记主单状态为已使用
        $item_id = $order_item[0]['item_id'];
        $data = array();
        $data['status'] = self::STATUS_ITEM_CONSUME;//已核销
        return $this->load( $item_id )->m_sets( $data )->m_save();

	}

	/**
	 * 根据资产细单ID和code查找消费单信息
	 * $model->get_consumer_order_item( $item_id, $code, $business, $inter_id );
	 * @author luguihong@mofly.cn
	 */
    public function get_consumer_order_item( $item_id, $code, $business, $inter_id )
    {
        if( !$code || !$item_id ){
            return FALSE;
        }
            
        $where = array();
        $where['inter_id'] = $inter_id;
        $where['consumer_code'] = $code + 0;
        $where['asset_item_id'] = $item_id + 0;

		$table_name = $this->table_name( $business, $inter_id );
        $result = $this->_shard_db_r('iwide_soma_r')
                      ->get_where( $table_name, $where )
                      ->result_array();

        return $result;
    }

    /**
	 * 核销细单
	 * $model->order_item = array( 'consumer_id'=>$consumer_id, 'status'=>$status );
	 * $model->consumer_order_item_use();
	 * @author luguihong@mofly.cn
	 */
    public function consumer_order_item_use( $business, $inter_id )
    {
    	$consumer_id = isset( $this->order_item['consumer_id'] ) ? $this->order_item['consumer_id'] : '';
    	$status = isset( $this->order_item['status'] ) ? $this->order_item['status'] : '';
        if( !$consumer_id || !$status ){
            return FALSE;
        }

        // $table_name = $this->_shard_db( $inter_id )->dbprefix( 'soma_consumer_order_item_'.$business );
        $table_name = $this->table_name( $business, $inter_id );

        $where = array();
        $where['consumer_id'] = $consumer_id;
        $where['inter_id'] = $inter_id;

        $data = array();
        $data['status'] = $status;

        $this->_shard_db( $inter_id )
             ->where( $where )
             ->update( $table_name, $data );

        return $this->_shard_db( $inter_id )->affected_rows();
    }

    //更新备注信息
    public function remark_save( $item_id, $remark, $inter_id, $business )
    {
    	$data = array('remark'=>$remark);
    	$table_name = $this->table_name( $business, $inter_id );
        $this->_shard_db( $inter_id )
             ->where( 'item_id', $item_id )
             ->update( $table_name, $data );

        return $this->_shard_db( $inter_id )->affected_rows();
    }

    ////核销纪录根据消费细单逆向搜索
    public function get_consumer_list_by_items( $consumer, $inter_id, $filter, $business, $limit=30, $limitStart=0 )
    {
    	$table = 'iwide_'.$this->table_name( $business, $inter_id );
    	$c_table = 'iwide_'.$this->main_table_name( $business, $inter_id );
    	// var_dump( $table , $c_table );die;
    	$sql = "SELECT i.consumer_id,i.consumer_code,i.name,i.price_package,i.order_id,i.remark,i.item_id,i.hotel_name,i.use_cnt,i.can_split_use,i.consumer_qty,c.consumer,c.consumer_method,c.consumer_time,c.consumer_type 
        FROM `{$table}` AS i 
        LEFT JOIN `{$c_table}` AS c 
        on c.consumer_id = i.consumer_id 
        WHERE c.inter_id = '{$inter_id}' AND 
        ";

        if( count($filter)>0 ){
            foreach ($filter as $k=> $v){
                if(is_array($v)){
                	$im = implode( ',', $v );
                	$im = trim( $im, ',' );
                    $sql .= " c.{$k} in ({$im}) AND ";
                } else {
                    $sql .= " c.{$k} = '{$v}' AND ";
                }
            }
        }
        
    	if(isset($consumer->start)&&$consumer->start) {
            $start = $consumer->start;
            if( strlen($start)<=10 ) $start.= ' 00:00:00';
            $sql .= " c.consumer_time >= '{$start}' AND ";
        }
        if(isset($consumer->end)&&$consumer->end) {
            $end = $consumer->end;
            if( strlen($end)<=10 ) $end.= ' 23:59:59';
            $sql .= " c.consumer_time < '{$end}' AND  ";
        }

        if(isset($consumer->consumer_code)&&$consumer->consumer_code) {
            $consumer_code = $consumer->consumer_code;
            $sql .= " i.consumer_code = '{$consumer_code}' AND  ";
        }
        if(isset($consumer->name)&&$consumer->name) {
            $name = $consumer->name;
            $sql .= " i.name LIKE '%{$name}%' AND  ";
        }
        if(isset($consumer->order_id)&&$consumer->order_id) {
            $order_id = $consumer->order_id;
            $sql .= " i.order_id = '{$order_id}' AND  ";
        }
        if(isset($consumer->remark)&&$consumer->remark) {
            $remark = $consumer->remark;
            $sql .= " i.remark LIKE '%{$remark}%' AND  ";
        }

        // $sql .= " 1 ORDER BY i.consumer_id DESC LIMIT {$limit},{$limitStart} ";
        $sql .= " 1 ORDER BY i.consumer_id DESC ";
        // echo $sql,'<br />';
    	$result= $this->_shard_db_r('iwide_soma_r')->query($sql)->result_array();
	 //    $tem_result = array();
	 //    $total = 0;
		// if( $result ){
	 //    	$total = count( $result );
	 //    	$len = $limitStart+$limit;
	 //    	for ($i=$limitStart; $i<$len; $i++) {
	 //    		if( isset( $result[$i] ) ){
	 //    			$tem_result[$i] = $result[$i];
	 //    		}
	 //    	}
		// }
		// $result = $tem_result;
		// $result['total'] = $total;
        // var_dump( $total, $limitStart, $limit  );die;
        return $result;
    }
    
}
