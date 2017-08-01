<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consumer extends MY_Front_Soma {

    public  $themeConfig;
    public  $theme = 'default';

	public function __construct()
	{
		parent::__construct();
        //theme
        $this->load->model('soma/Theme_config_model');
        $this->themeConfig = $themeConfig = $this->Theme_config_model->get_using_theme($this->inter_id);
        $this->theme = $themeConfig['theme_path'];
	}

    /**
     * 直接显示二维码，具有简单校验规则
     * @deprecated  因inter_id与openid缺失导致二维码无法显示，已移置 soma/api/get_consume_qrcode 
     */
    public function get_consume_qrcode()
    {
        $encrypt_code= base64_decode($this->input->get('code'));
        $validate= base64_decode($this->input->get('valid'));
        
        $this->load->helper('encrypt');
        $encrypt_util= new Encrypt();
        $content= $encrypt_util->decrypt( $encrypt_code );
        $lenght= $encrypt_util->decrypt( $validate );
        if( strlen($content)== $lenght ){
            $this->_get_qrcode_png($content);
            
        } else {
            echo '参数错误';
        }
    }
    
    protected function _find_consumer_code($inter_id, $aiid, $offset)
    {
	    $this->load->model('soma/Consumer_code_model' );
	    $model= $this->Consumer_code_model;
	    $codes= $model->find_all( array(
	        'asset_item_id'=> $aiid, 
	        'inter_id'=> $inter_id, 
	        'status'=> $model::STATUS_SIGNED
	    ) );
	    if( count($codes)>0 && isset($codes[$offset]) ){
	        return $codes[$offset]['code'];
	    } else 
	        return '';
    }
    
	/**
	 * 套票预定
	 */
	public function package_booking()
	{
	    $item_id= $this->input->get('aiid');
	    $offset= $this->input->get('aiidi');
	    $business= $this->input->get('bsn');
	    
	    $this->load->model('soma/Asset_item_package_model' );
	    $item= $this->Asset_item_package_model->find( array('item_id'=>$item_id ) );
// var_dump( $item );die;
	    $time = time();
	    $expireTime = isset( $item['expiration_date'] ) ? strtotime( $item['expiration_date'] ) : NULL;
	    $is_expire = FALSE;
        if( $expireTime && $expireTime < $time ){
        	$is_expire = TRUE;
        }

	    if( isset($item['openid']) && $item['openid']!= $this->openid ){
	        $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $item['inter_id'] ) );
	        redirect($url);
	    }
	    
	    // $hotel_tel= $item['room_id'];
	    // $filter = array('inter_id'=>$this->inter_id,'hotel_id'=>$item['hotel_id']);
	    // $hotel_info= $this->db->where( $filter )->get('iwide_hotels')->result_array();
	    // if( count($hotel_info)>0 ){
     //        $hotel_tel= $hotel_info[0]['tel'];
     //    }
	    // $room_info= $this->db->where('room_id', $item['room_id'] )->where('inter_id', $this->inter_id)->get('iwide_hotel_rooms')->result_array();
	    // if( count($room_info)>0 ){
	    //     $hotel_info= $this->db->where( 'hotel_id', $room_info[0]['hotel_id'] )->where('inter_id', $this->inter_id)->get('iwide_hotels')->result_array();
	    //     if( count($hotel_info)>0 ){
	    //         $hotel_tel= $hotel_info[0]['tel'];
	    //     }
	    // }
	    
	    $item['qrcode']= $this->_find_consumer_code($this->inter_id, $item_id, $offset);

	    $this->load->model('soma/Product_package_model','productModel');
	    $productModel = $this->productModel;
	    $productInfo = $productModel->get_product_package_phone_by_product_id( $item['product_id'], $this->inter_id );
	    $item['hotel_tel']= $productInfo['hotel_tel'];
	    // var_dump( $item );die;
	    
        $header = array( 
            'title'=> $this->lang->line('book_before'),
        );
        //点击分享之后开启这些按钮
        $js_menu_hide = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' );
        
        //获取推荐位
        $uri = 'soma_consumer_package_booking';
        $block = $this->get_page_block( $uri );

        // 双语翻译
        if(!empty($item['name_en']) && $this->langDir == self::LANG_DIR_EN)
        {
        	$item['name'] = $item['name_en'];
        }

        $this->load->helper('soma/package');
        $datas = array(
            'item'=> $item,
            'js_menu_hide'=> $js_menu_hide,
            'is_expire'=> $is_expire,
            'block'=> $block,
        );
        $this->_view("header",$header);
        $this->_view("package_booking", $datas);
	}
	
	/**
	 * 立即用券
	 */
	public function package_usage()
	{
	    $item_id= $this->input->get('aiid');
	    $offset= $this->input->get('aiidi');
	    $business= $this->input->get('bsn');

	    $this->load->model('soma/Asset_item_package_model' );
	    $item= $this->Asset_item_package_model->find( array('item_id'=>$item_id ) );

	    $time = time();
	    $expireTime = isset( $item['expiration_date'] ) ? strtotime( $item['expiration_date'] ) : NULL;
	    $is_expire = FALSE;
        if( $expireTime && $expireTime < $time ){
        	$is_expire = TRUE;
        }

	    if( isset($item['openid']) && $item['openid']!= $this->openid ){
	        $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $item['inter_id'] ) );
	        redirect($url);
	    }
	    
	    $item['qrcode']= $this->_find_consumer_code($this->inter_id, $item_id, $offset);
	    $this->load->helper('encrypt');
	    $encrypt_util= new Encrypt();
	    $content= $encrypt_util->encrypt( $item['qrcode'] );
	    $length= $encrypt_util->encrypt( strlen($item['qrcode']) );
	    $item['qrcode_url']= Soma_const_url::inst()->get_url('*/api/get_consume_qrcode', array('code'=> base64_encode($content), 'valid'=> base64_encode($length) ) );
	     
	    $header = array(
	        'title'=> $this->lang->line('use_in_hotel'),
	    );
        //点击分享之后开启这些按钮
        $js_menu_hide = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' );
        
        //获取推荐位
        $uri = 'soma_consumer_consumer_detail';
        $block = $this->get_page_block( $uri );

		// 双语翻译
		if(!empty($item['name_en']) && $this->langDir == self::LANG_DIR_EN)
		{
			$item['name'] = $item['name_en'];
		}

        $this->load->helper('soma/package');
	    $datas = array(
            'item'=> $item,
            'js_menu_hide'=> $js_menu_hide,
            'is_expire'=> $is_expire,
            'block'=> $block,
        );
        $this->_view("header", $header);
        $this->_view("package_usage", $datas);
	}

	/**
	 * 回顾消费记录
	 */
	public function package_review()
	{
	    $item_id= $this->input->get('ciid');
	    $business= $this->input->get('bsn');

	    $this->load->model('soma/Consumer_item_package_model' );
	    $item= $this->Consumer_item_package_model->find( array('item_id'=>$item_id ) );
	    
	    if( isset($item['openid']) && $item['openid']!= $this->openid ){
	        $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $item['inter_id'] ) );
	        redirect($url);
	    }
	    
	    $code= $item['consumer_code'];
	    
	    $this->load->helper('encrypt');
	    $encrypt_util= new Encrypt();
	    $content= $encrypt_util->encrypt( $code );
	    $length= $encrypt_util->encrypt( strlen($code) );
	    $item['qrcode']= $code;
	    $item['qrcode_url']= Soma_const_url::inst()->get_url('*/api/get_consume_qrcode', array('code'=> base64_encode($content), 'valid'=> base64_encode($length) ) );
	    
	    $is_consumer= $item['status']==Consumer_item_package_model::STATUS_ITEM_CONSUME? TRUE: FALSE;
	    $is_booking= $item['status']==Consumer_item_package_model::STATUS_ITEM_PENDING? TRUE: FALSE;
	    
	    $header = array(
	        'title'=> $this->lang->line('use_in_hotel'),
	    );
        //点击分享之后开启这些按钮
        $js_menu_hide = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' );
        
        //获取推荐位
        $uri = 'soma_consumer_consumer_detail';
        $block = $this->get_page_block( $uri );

        $this->load->helper('soma/package');
	    $datas = array(
            'item'=> $item,
            'js_menu_hide'=> $js_menu_hide,
            'is_consumer'=> $is_consumer,
            'is_booking'=> $is_booking,
            'block'=> $block,
        );
        $this->_view("header", $header);
        $this->_view("package_usage", $datas);
	}

	/**
	 * 扫码核销入口
	 */
	public function consumer_scaner()
	{
	    //print_r($this);die;
	    //TODO: 是否在管理员授权表中状态正常
	    $this->load->model('core/priv_admin_authid', 'admin_authid');
	    $is_permit= $this->admin_authid->can_access($this->openid);
	    if( $is_permit ){
    	    $header = array(
    	        'title'=> '扫码核销',
    	    );
	        $this->load->helper('encrypt');
	        $encrypt_util= new Encrypt();
	        $token= $encrypt_util->encrypt($this->openid. date('YmdH') );

	        //增加以下jsapi
	        $base_api_list = array( 'scanQRCode', 'closeWindow' );
	        
 	        $data= array(
 	            'message'=> '点击页面，开始核销',
	            'callback'=> EA_const_url::inst()->get_url('*/*/consumer_callback', array('id'=> $this->inter_id)),
                'js_api_list'=> $base_api_list,
	            'openid'=> $this->openid,
	            't'=> $token,
 	        );
            $this->_view("header", $header);
	        $this->_view('consumer_scaner', $data);
	         
	    } else {
    	    $header = array(
    	        'title'=> '认证失败',
    	    );
	        $base_api_list = array( 'closeWindow' );
	        $message= '您的微信号未经授权，不能进行此操作。';
	        $data= array(
	            'message'=> $message,
	            'js_api_list'=> $base_api_list,
            );
            $this->_view("header", $header);
	        $this->_view('consumer_deny', $data );
	    }
	}
	
    /**
     * 扫码核销异步请求
     */
	public function consumer_callback()
	{
	    $this->load->helper('encrypt');
	    $encrypt_util= new Encrypt();
	    $token= $encrypt_util->encrypt($this->openid. date('YmdH') );
	    $return= array('status'=>2, 'message'=>'校验码超时。');
	
	    try {
	        $t= $this->input->post('t');
	        $openid= $this->input->post('openid');
	        $code= $this->input->post('code');
	        if($token==$t){
	        	//核销流程,返回结果
	        	$this->load->model('soma/Consumer_order_model','ConsumerOrderModel');
	        	$ConsumerOrderModel = $this->ConsumerOrderModel;
	        	$consumer_method = $ConsumerOrderModel::CONSUME_METHOD_SCANER;//扫码核销
	        	$result = $ConsumerOrderModel->direct_consumer( $code, $openid, $consumer_method, $this->inter_id );
	            if( isset($result['status']) && $result['status']==1){
	                $return['status']= 1;
	                $return['message']= $result['message'];

					/***********************发送模版消息****************************/
		            //发送模版消息
		            $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
		            $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
		            
		            $openid = $this->openid;
					$inter_id= $this->inter_id;
					$business = isset( $business ) ? strtolower( $business ) : 'package';

		            $type = $MessageWxtempTemplateModel::TEMPLATE_CONSUMER_SUCCESS;

		            $this->load->model('soma/Asset_customer_model','AssetCustomerModel');
		            $AssetCustomerModel = $this->AssetCustomerModel;
		            $AssetCustomerModel->asset_item_id = isset( $result['data'][0]['item_id'] ) ? $result['data'][0]['item_id'] : 0;
		            $AssetCustomerModel->code = $code;

		            $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success( $type, $AssetCustomerModel, $openid, $inter_id, $business);
		            /***********************发送模版消息****************************/
	
	                //记录最后操作时间
	                $this->load->model("core/priv_admin_authid", 'authid');
	                $this->authid->update_last_operation($this->openid);
	
	            } else {
	                $return['message']= $result['message'];
	            }
	        }
	        echo json_encode($return);
	
	    } catch (Exception $e) {
	        //echo $e->getMessage();
	        $return['message']= '处理过程出现问题！';
	        echo json_encode($return);
	    }
	}

	/**
	 * 批量核销
	 * oid/gid 	int 	order_id/gift_id
	 * @author luguihong
	 */
	public function consumer_batch()
	{
		/**
		 批量核销
		 1.有可能是订单详情过来的 	oid=>order_id
		 2.也有可能是接受礼物详情页过来的 	gid=>gift_id

		 思路
		 1.根据订单ID或者赠送ID查找出资产细单
		 2.根据资产细单ID，去消费码表，查找出n（n代表要批量核销的数量）个消费码
		 3.根据查找出消费，进行循环核销
		*/

		$captcha = $this->input->post('captcha');//consumer_captcha
		$business = $this->input->get('bsn');//business
		$inter_id = $this->inter_id;
		$openid = $this->openid;

		$msg = array();
		if( $captcha != '795842' ){
			$msg['message'] = '输入的验证码错误！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die;
		}
	}

	/**
	 * 自助核销
	 * $aiid 	int 	asset_item_id
	 * $bsn 	char 	business
	 * $code 	char 	consumer_code
	 * @author luguihong
	 */
	public function self_consumer()
	{
		$item_id = $this->input->get('aiid');//asset_item_id
		$business = $this->input->get('bsn');//business
		$code = $this->input->post('code');//consumer_code
		$captcha = $this->input->post('captcha');//consumer_captcha
		$inter_id = $this->inter_id;
		$openid = $this->openid;

		$msg = array();
		if( $captcha != '795842' ){
			$msg['message'] = '输入的验证码错误！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die;
		}

		if( !$item_id || !$code ){
			$msg['message'] = '该券码已核销/失效，不可再次核销！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die;
		}

		$business = isset( $business ) ? strtolower( $business ) : 'package';

		$this->load->model('soma/Consumer_order_model','ConsumerOrderModel');
	    $ConsumerOrderModel = $this->ConsumerOrderModel;
	    $consumer_method = $ConsumerOrderModel::CONSUME_METHOD_FRONTEND;//自助核销
		$result = $ConsumerOrderModel->direct_consumer( $code, $openid, $consumer_method, $inter_id, $business );
	    if( isset( $result['status'] ) && $result['status'] == Soma_base::STATUS_TRUE ){

			/***********************发送模版消息****************************/
            //发送模版消息
            $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
            $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
            
            $openid = $this->openid;
			$inter_id= $this->inter_id;

            $type = $MessageWxtempTemplateModel::TEMPLATE_CONSUMER_SUCCESS;

            $this->load->model('soma/Asset_customer_model','AssetCustomerModel');
            $AssetCustomerModel = $this->AssetCustomerModel;
            $AssetCustomerModel->asset_item_id = $item_id;
            $AssetCustomerModel->code = $code;

            $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success( $type, $AssetCustomerModel, $openid, $inter_id, $business);
            /***********************发送模版消息****************************/

	    	//跳转页面
	    	$url= Soma_const_url::inst()->get_url('*/*/consumer_detail', array('id'=> $inter_id, 'aiid'=>$item_id, 'bsn'=>$business, 'code'=>$code ) );
	        $msg['url'] = $url;
	        $msg['message'] = '自助核销成功';
	        $msg['status'] = 1;
	    	echo json_encode( $msg );
	    	die;
	    }else{
	    	//提示自助核销失败
	    	$msg['message'] = isset( $result['message'] ) ? $result['message'] : '自助核销失败，请再尝试一次！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die;
	    }

	}
	
	/**
	 * 自助核销
	 * http://tf.iwide.cn/index.php/soma/Consumer/consume_self?id=a429262687&openid=oX3WojpcRoRbRvDJDAU9xymibGfc&aiid=837&bsn=package&code=474273954161
	 * $aiid 	int 	asset_item_id
	 * $bsn 	char 	business
	 * $code 	char 	consumer_code
	 * @author luguihong
	 * @deprecated
	 */
	public function consume_self()
	{
		$item_id = $this->input->get('aiid');//asset_item_id
		$business = $this->input->get('bsn');//business
		$code = $this->input->post('code');//consumer_code
		$inter_id = $this->inter_id;

		if( !$item_id || !$code ){
			$msg['message'] = '核销失败，请稍候再试！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die();
		}

		$msg = array();//核销信息

		//先处理码，根据码信息是否匹配到当前的asset_item_id，不匹配就返回错误
	    $this->load->model('soma/Consumer_code_model','ConsumerCodeModel');
	    $ConsumerCodeModel = $this->ConsumerCodeModel;
		$consumer_code_info = $ConsumerCodeModel->get_consumer_code_info_by_code( $code, $inter_id );
		if( !$consumer_code_info ){
    	    $msg['message'] = '核销码不存在！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die();
		}

		$status_unsign = $ConsumerCodeModel::STATUS_UNSIGN;//未分配
		$status = $consumer_code_info['status'];
		if( $status == $status_unsign ){
			// die( '预约码没有分配' );
	    	$msg['message'] = '核销码没有分配！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die();
		}

		$status_consume = $ConsumerCodeModel::STATUS_CONSUME;//已消费
		// if( $status == $status_consume ){
		// 	$msg['message'] = '核销码已消费！';
	 //        $msg['status'] = 2;
	 //    	echo json_encode( $msg );
	 //        die();
		// }
		//使用核销码查找出来的消费细单ID
		$asset_item_id = $consumer_code_info['asset_item_id'];

		//获取资产细单信息
		$this->load->model('soma/Asset_customer_model','AssetCustomerModel');
		$AssetCustomerModel = $this->AssetCustomerModel;
		$item = $AssetCustomerModel->get_asset_items_by_itemId( $item_id, $business, $inter_id );

		//对比消费细单ID是否一致
		if( $asset_item_id != $item_id ){
			//不是自己的单
			$msg['message'] = '自助核销失败！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die();
		}

		//是否是本人
	    if( isset($item[0]['openid']) && $item[0]['openid']!= $this->openid ){
	    	//不是自己的单
	        // $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $item['inter_id'] ) );
	        // redirect($url);
	        $msg['message'] = '自助核销失败！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die();
	    }

	    //是否是本人
	    if( isset($item[0]['qty']) && $item[0]['qty'] < 1 ){
	    	//不是自己的单
	        // $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $item['inter_id'] ) );
	        // redirect($url);
	        $msg['message'] = '自助核销失败！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die();
	    }

	    //是否是同一个公众号
	    if( isset($item[0]['inter_id']) && $item[0]['inter_id']!= $this->inter_id ){
	    	//不是自己的单
	        // $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $item['inter_id'] ) );
	        // redirect($url);
	        $msg['message'] = '自助核销失败！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die();
	    }

	    //初始化消费单对象
	    $this->load->model('soma/Consumer_order_model','ConsumerOrderModel');
	    $ConsumerOrderModel = $this->ConsumerOrderModel;
	    $ConsumerOrderModel->business = $business;
	    

    	$this->load->model( 'soma/Consumer_item_'.$business.'_model', 'ConsumerItemModel' );
    	$ConsumerItemModel = $this->ConsumerItemModel;

	    $can_reserve_yes = $AssetCustomerModel::ITEMS_CAN_RESERVE_YES;//需要预约
	    $order_item = $ConsumerOrderModel->get_consumer_order_item( $item_id, $code, $business, $inter_id );
	    if( $item[0]['can_reserve'] == $can_reserve_yes ){
	    	//需要预约
	    	if( !$order_item ){
	    		//需要预约的但是没有找到消费单(预约的时候会生成消费单)
	    		//提示没有进行预约
	    		$msg['message'] = '自助核销失败，没有预约！';
		        $msg['status'] = 2;
		    	echo json_encode( $msg );
	    		die();
	    	}
	    }

	    //检测是否已经核销
	    if( isset( $order_item ) && !empty( $order_item ) ){
	    	$order_item = array_reverse( $order_item );//翻转数组,防止数据有多条，造成错乱

	    	$consumer_yes = $ConsumerItemModel->get_consumer_yes();
	    	if( in_array( $order_item[0]['status'], $consumer_yes ) ){
	    		$msg['message'] = '核销码已消费！';
		        $msg['status'] = 2;
		    	echo json_encode( $msg );
		        die();
	    	}

    		$consume_item_status = $ConsumerItemModel::STATUS_ITEM_CONSUME;//已使用
    		if( ( $order_item[0]['status'] == $consume_item_status ) && ( $status == $status_consume ) ){
    			$msg['message'] = '核销码已消费！';
		        $msg['status'] = 2;
		    	echo json_encode( $msg );
		        die();
    		}
    	}
	    
	    $ConsumerOrderModel->order_item = $order_item;

	    //核销的时候需要传入核销码
	    $item[0]['consumer_code'] = $code;

	    //初始化核销需要的参数
	    $item[0]['consumer_time'] = date( 'Y-m-d H:i:s', time() );
    	$item[0]['consume_status'] = $ConsumerOrderModel::STATUS_ALLUSE;//已使用
    	$item[0]['consumer_type'] = $ConsumerOrderModel::CONSUME_TYPE_DEFAULT;
    	$item[0]['consumer_method'] = $ConsumerOrderModel::CONSUME_METHOD_FRONTEND;//核销方式
		$item[0]['consumer'] = isset( $openid ) ? $openid : $this->openid;//核销人

    	$item[0]['consume_item_status'] = $ConsumerItemModel::STATUS_ITEM_CONSUME;//消费细单状态

	    $ConsumerOrderModel->asset_item = $item;

	    $AssetCustomerModel->consumer = $ConsumerOrderModel;
	    $AssetCustomerModel->item = $item;
	    $AssetCustomerModel->business = $business;
	    $result = $AssetCustomerModel->consumer_asset_items( $business, $inter_id );

	    if( !$result ){

	    	//提示自助核销失败
	    	$msg['message'] = '自助核销失败，请再尝试一次！';
	        $msg['status'] = 2;
	    	echo json_encode( $msg );
	        die();
	    }else{
	    	//跳转页面
	    	$url= Soma_const_url::inst()->get_url('*/*/consumer_detail', array('id'=> $inter_id, 'aiid'=>$item_id, 'bsn'=>$business, 'code'=>$code ) );
	        $msg['url'] = $url;
	        $msg['message'] = '自助核销成功';
	        $msg['status'] = 1;
	    	echo json_encode( $msg );
	    	die();
	    }

	}

	//核销情况，自助核销之后跳转到这里
	public function consumer_detail()
	{

		$item_id= $this->input->get('aiid');
		$business = $this->input->get('bsn');//business
		$code = $this->input->get('code');//code
		$inter_id = $this->inter_id;

		//获取资产细单信息
		$this->load->model('soma/Asset_customer_model','AssetCustomerModel');
		$AssetCustomerModel = $this->AssetCustomerModel;
		$item = $AssetCustomerModel->get_asset_items_by_itemId( $item_id, $business, $inter_id );
		if( isset($item[0]['openid']) && $item[0]['openid']!= $this->openid ){
	    	//不是自己的单
	        $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $inter_id ) );
	        redirect($url);
	    }
		
		//获取消费时间
		$this->load->model('soma/Consumer_order_model');
		$consumer_item_info = $this->Consumer_order_model->get_consumer_order_item($item_id, $code, $business, $inter_id);
		if( !$consumer_item_info ){
			$url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $inter_id ) );
	        redirect($url);
		}

		$time = $this->Consumer_order_model->load( $consumer_item_info[0]['consumer_id'] )->m_get('consumer_time');

	    $datas['name'] = $item[0]['name'];

	    // 双语化翻译
	    if($this->langDir == self::LANG_DIR_EN)
	    {
	    	if(!empty($item[0]['name_en']))
	    	{
	    		$datas['name'] = $item[0]['name_en'];
	    	}
	    }

	    $datas['time'] = $time;

	    //获取推荐位
        $uri = 'soma_order_order_detail';
        $block = $this->get_page_block( $uri );
        $datas['block']= $block;

	    $time = date( 'date' );
		$header = array(
	        'title'=> $this->lang->line('situation'),
	    );
		$this->_view("header", $header);
        $this->_view("consumer_self", $datas);
	}

	//邮寄商品
	public function shipping_product_info()
	{
		//分两种情况，一：从订单进来的，参数是order_id。二：从赠送订单进来的，参数是gift_id
		$orderId= $this->input->get('oid');//order_id
		$giftId= $this->input->get('gid');//gift_id
		$business = $this->input->get('bsn');//business
		$interId = $this->inter_id;

		if( !$orderId && !$giftId ){
			$url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
	        redirect($url);
		}

		if( $orderId ){

			//自己的订单

			$this->load->model('soma/Sales_order_model','SalesOrderModel');
	        $SalesOrderModel = $this->SalesOrderModel;

	        $SalesOrderModel->business = $business;
	        $SalesOrderModel = $SalesOrderModel->load($orderId);
	        if( !$SalesOrderModel ){
	        	redirect( Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) ) );
	        }
	        
	        $detail = $SalesOrderModel->get_order_asset($business,$interId); //资产订单
	        if( isset($detail['openid']) && $detail['openid']!= $this->openid ){
		    	//不是自己的单
		        $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
		        redirect($url);
		    }

		    $expiration_date = isset( $detail['items'][0]['expiration_date'] ) ? strtotime( $detail['items'][0]['expiration_date'] ) : NULL;

		}elseif( $giftId ){
			//接受到赠送的礼物

			$this->load->model('soma/Gift_order_model','GiftOrderModel');
			$GiftOrderModel = $this->GiftOrderModel;

		    $this->load->model('soma/Asset_customer_model', 'assetCustomerModel');
	        // $this->load->model('soma/Consumer_item_package_model','ConsumerPackageModel');

	        $ids= array();
	        $filter= array( 'gift_id'=> $giftId );
	        $items = $this->assetCustomerModel->get_gift_recevied_item($filter, $business, $interId);
	        $expiration_date = isset( $items[0]['expiration_date'] ) ? strtotime( $items[0]['expiration_date'] ) : NULL;
	        //print_r($items);die;
	        // foreach ($items as $v) {
	        //     $ids[]= $v['item_id'];  //需要匹配的资产Item ID
	        // }
	        
	        $model= $GiftOrderModel->load($giftId);
	        if( !$giftId || !$model  || $model->m_get('openid_received')!= $this->openid  ){
	            redirect( Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) ) );
	        }
	        
	        $detail= $model->m_data();
	        $detail['items'] = $items;

	        // if(count($ids)>0 ){
	        //     $consumer_items= $this->ConsumerPackageModel->get_order_items_byAssetItemIds( array_values($ids), $business, $interId);
	        // } else {
	        //     $consumer_items= array();
	        // }
		}
		
        $this->datas = array();
		$data = array();

		$this->load->model('soma/Consumer_shipping_model','ConsumerShippingModel');
		$ConsumerShippingModel = $this->ConsumerShippingModel;
		$can_mail = $ConsumerShippingModel::CAN_MAIL_YES;
		$this->datas['can_mail'] = $can_mail;
		$this->datas['nowtime'] = time();
		$this->datas['expirtime'] = $expiration_date;
		
		$header = array(
            'title' => '邮寄商品',
        );

        $items = $detail['items'];
        foreach ($items as $k => $v) {
        	$num = $v['qty'];
        	for( $i=0; $i < $num; $i++ ){
        		$data[] = $v;
        	}
        }
        if( count( $data ) == 0 ){

        	//没有可邮寄的商品
        	$url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
		    redirect($url);
        }

        $this->datas['items'] = $data;
        $this->datas['openid'] = $this->openid;
        $this->datas['inter_id'] = $interId;

        $this->datas['address_url'] = Soma_const_url::inst()->get_url('*/*/shipping_address_info', array('id'=>$interId ) );
		$this->_view("header",$header);
	    $this->_view("shipping_product_info", $this->datas);

	}

	//邮寄商品 new
	public function show_shipping_info()
	{
		//分两种情况，一：从订单进来的，参数是order_id。二：从赠送订单进来的，参数是gift_id
		$orderId= $this->input->get('oid');//order_id
		$giftId= $this->input->get('gid');//gift_id
		$business = $this->input->get('bsn');//business
		$interId = $this->inter_id;

		if( !$orderId && !$giftId ){
			$url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
	        redirect($url);
		}

		if( $orderId ){

			//自己的订单

			$this->load->model('soma/Sales_order_model','SalesOrderModel');
	        $SalesOrderModel = $this->SalesOrderModel;

	        $SalesOrderModel->business = $business;
	        $SalesOrderModel = $SalesOrderModel->load($orderId);
	        if( !$SalesOrderModel ){
	        	redirect( Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) ) );
	        }
		
			//检查能否邮寄
			if( !$SalesOrderModel->can_mail_order() ){
				die($this->lang->line('can_not_mail'));
			}
	        
	        $detail = $SalesOrderModel->get_order_asset($business,$interId); //资产订单
	        //筛选属于自己的资产订单
        	$detail['items'] = $SalesOrderModel->filter_items_by_openid( $detail['items'], $this->openid );
        	
	        if( isset($detail['openid']) && $detail['openid']!= $this->openid ){
		    	//不是自己的单
		        $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
		        redirect($url);
		    }

		    $expiration_date = isset( $detail['items'][0]['expiration_date'] ) ? strtotime( $detail['items'][0]['expiration_date'] ) : NULL;

		}elseif( $giftId ){
			//接受到赠送的礼物

			$this->load->model('soma/Gift_order_model','GiftOrderModel');
			$GiftOrderModel = $this->GiftOrderModel;

		    $this->load->model('soma/Asset_customer_model', 'assetCustomerModel');
	        // $this->load->model('soma/Consumer_item_package_model','ConsumerPackageModel');

	        $ids= array();
	        $filter= array( 'gift_id'=> $giftId );
	        $items = $this->assetCustomerModel->get_gift_recevied_item($filter, $business, $interId);
	        //筛选属于自己的资产订单
        	$items = $this->assetCustomerModel->filter_items_by_openid( $items, $this->openid );
	        $expiration_date = isset( $items[0]['expiration_date'] ) ? strtotime( $items[0]['expiration_date'] ) : NULL;

	        
	        $model= $GiftOrderModel->load($giftId);
	        //取出群发收到的列表
        	$receive_list= $model->get_receiver_list_byOpenId($interId, $this->openid );
        	$giftIds= $model->array_to_hash($receive_list, 'gift_id');

	        // if( !$giftId || !$model  || $model->m_get('openid_received')!= $this->openid  ){//这里的判断条件要修改，如果群发接受到的礼物，不能用这条件判断
	        if( !$giftId || !$model  
	        	|| ( $model->m_get('openid_received')!= $this->openid && $model->m_get('is_p2p') == Soma_base::STATUS_TRUE )
	        	|| ( $model->m_get('is_p2p') == Soma_base::STATUS_FALSE && !in_array($giftId, $giftIds) ) ){
	            redirect( Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) ) );
	        }
	        
	        $detail= $model->m_data();
	        $detail['items'] = $items;
		}

		//筛选掉数量为空的数据
    	$filter_data = array();
        foreach ($detail['items'] as $k => $v) {
        	if( $v['qty'] > 0 ){
        		$filter_data[] = $v;
        	}
        }
        $detail['items'] = $filter_data;

        if( isset( $detail['items'][0]['can_mail'] ) && $detail['items'][0]['can_mail'] == Soma_base::STATUS_FALSE ){
        	if( $giftId ){

        		redirect( Soma_const_url::inst()->get_url('*/gift/package_detail', array('id'=> $interId,'gid'=>$giftId,'bsn'=>$business ) ) );
        	}else{
        		redirect( Soma_const_url::inst()->get_url('*/order/order_detail', array('id'=> $interId,'oid'=>$orderId,'bsn'=>$business ) ) );
        	}
        }
		
        $this->datas = array();
		$data = array();

		$this->datas['aiid'] = isset( $detail['items'][0]['item_id'] ) ? $detail['items'][0]['item_id'] : '';

		$this->load->model('soma/Consumer_shipping_model','ConsumerShippingModel');
		$ConsumerShippingModel = $this->ConsumerShippingModel;
		$can_mail = $ConsumerShippingModel::CAN_MAIL_YES;
		$this->datas['can_mail'] = $can_mail;
		$this->datas['nowtime'] = time();
		$this->datas['expirtime'] = $expiration_date;
		
		
        $items = $detail['items'];
        foreach ($items as $k => $v) {
        	$num = $v['qty'];
        	for( $i=0; $i < $num; $i++ ){
        		$data[] = $v;
        	}
        }

        if( count( $data ) == 0 ){

        	//没有可邮寄的商品
        	$url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
		    redirect($url);
        }

        /*******************以下为邮寄地址*******************/
        $this->datas['year'] = '';
		$this->datas['month'] = '';
		$this->datas['date'] = '';
		if( isset( $expiration_date ) && !empty( $expiration_date ) ){
	    	$time = $expiration_date;
	    	$this->datas['year'] = date( 'Y', strtotime( $time ) ) + 0;
	    	$this->datas['month'] = date( 'm', strtotime( $time ) ) + 0;
	    	$this->datas['date'] = date( 'd', strtotime( $time ) ) + 0;
    	}

		//获取消费地址
		$openid = $this->openid;

		$this->load->model('soma/Customer_address_model','CustomerAddressModel');
		$CustomerAddressModel = $this->CustomerAddressModel;

		$filter = array();
		$filter['openid'] = $openid;
		$filter['inter_id'] = $interId;

		$limit = 1;//取出一条地址信息
		$address = $CustomerAddressModel->get_addresses( $openid, $filter, $limit );
		if( $address ){
			$address = $address[0];
		}else{
			$address = array( 
					'contact'=>'', 
					'phone'=>'', 
					'province'=>'', 
					'province_name'=>'', 
					'city'=>'', 
					'city_name'=>'', 
					'region'=>'', 
					'region_name'=>'', 
					'address'=>'', 
				);

			// 邮寄从订单产生的资产，联系人信息更改为订单联系人信息
			if($orderId) {
				$this->load->model('soma/Sales_order_model', 'o_model');
				$filter = array('openid' => $this->openid);
				$order_contact = $this->o_model->get_customer_contact($filter);
				if($order_contact) {
					$address['contact'] = $order_contact['name'];
					$address['phone'] = $order_contact['mobile'];
				}
			}
			
		}

		

		$this->datas['address'] = $address;

		$addressId = isset( $address['address_id'] ) ? $address['address_id'] : NULL;
		$this->datas['arid'] = $addressId;

		$this->load->model('soma/Cms_region_model','CmsRegionModel');
		$CmsRegionModel = $this->CmsRegionModel;

		//取出省列表
		$provinces = $CmsRegionModel->get_provinces( $interId );
		$this->datas['provinces'] = $provinces;

		$citys = array();
		$province_name = '';
		if( isset( $address['province'] ) && !empty( $address['province'] ) ){
			//获取市列表
			$provinceId = $address['province'] + 0;
			$citys = $CmsRegionModel->get_citys( $provinceId, $interId );

			$provinceDetail = $CmsRegionModel->get_region_detail( $provinceId , $interId );
			$province_name = isset( $provinceDetail['region_name'] ) ? $provinceDetail['region_name'] : '';
		}
		$this->datas['citys'] = $citys;
		$this->datas['province_name'] = $province_name;

		$regions = array();
		$city_name = '';
		if( isset( $address['city'] ) && !empty( $address['city'] ) ){
			//获取区列表
			$cityId = $address['city'] + 0;
			$regions = $CmsRegionModel->get_regions( $cityId, $interId );

			$provinceDetail = $CmsRegionModel->get_region_detail( $cityId , $interId );
			$city_name = isset( $provinceDetail['region_name'] ) ? $provinceDetail['region_name'] : '';
		}
		$this->datas['regions'] = $regions;
		$this->datas['city_name'] = $city_name;
// var_dump( $regions, $citys, $provinces );die;
		$region_name = '';
		if( isset( $address['region'] ) && !empty( $address['region'] ) ){
			$regionId = $address['region'] + 0;
			$provinceDetail = $CmsRegionModel->get_region_detail( $regionId , $interId );
			$region_name = isset( $provinceDetail['region_name'] ) ? $provinceDetail['region_name'] : '';
		}
		$this->datas['region_name'] = $region_name;
		
		//获取市列表链接
		$citys_url = Soma_const_url::inst()->get_url('*/api/ajax_get_citys', array('id'=>$interId ) );
		$this->datas['citys_url'] = $citys_url;
		
		//获取区列表链接
		$regions_url = Soma_const_url::inst()->get_url('*/api/ajax_get_regions', array('id'=>$interId ) );
		$this->datas['regions_url'] = $regions_url;

		//保存地址链接
		$save_address = Soma_const_url::inst()->get_url('*/*/ajax_save_address', array('id'=>$interId, 'arid'=>$addressId ) );
		$this->datas['save_address'] = $save_address;

        $this->datas['shipping_url'] = Soma_const_url::inst()->get_url('*/*/mail_post', array('id'=>$interId, 'oid'=>$orderId, 'gid'=>$giftId ) );

        /*******************以上为邮寄地址*******************/
// var_dump( $address );

        // 取出商品信息
        $this->load->model('soma/product_package_model', 'p_model');
        $this->datas['product'] = $this->p_model->load($data[0]['product_id'])->m_data();
        if(isset($this->datas['product']['shipping_product_id'])
        	&& $spi = $this->datas['product']['shipping_product_id']) {
        	$this->datas['shipping_product'] = $this->p_model->load($spi)->m_data();
        }

        $this->datas['items'] = $data;
        $this->datas['openid'] = $this->openid;
        $this->datas['inter_id'] = $interId;

        $this->datas['address_url'] = Soma_const_url::inst()->get_url('*/*/shipping_address_info', array('id'=>$interId ) );

        $this->datas['check_follow_ajax'] = Soma_const_url::inst()->get_url( '*/gift/check_follow_ajax', array('id'=>$this->inter_id) );

		$mail_error_msg = $this->session->userdata('mail_error_msg');//邮寄错误信息
		$this->datas['mail_error_msg'] = $mail_error_msg;

		$header = array(
            'title' => $this->lang->line('mailed_goods'),
        );

		// 双语化翻译
		if($this->langDir == self::LANG_DIR_EN)
		{	
			$en_items = $this->datas['items'];
			foreach ($this->datas['items'] as $key => $item)
			{
				if(!empty($item['name_en']))
				{
					$en_items[$key]['name'] = $item['name_en'];
				}
			}
			$this->datas['items'] = $en_items;
		}

		$this->_view("header",$header);
	    // $this->_view("mail_address", $this->datas);
	    $this->_view("shipping_address_info", $this->datas);

	}

	//显现要填写的邮寄地址信息
	public function shipping_address_info()
	{
		// var_dump( $this->input->get() );exit;
		//联系人，联系电话，所在地区，详情地址，立即发货／预约发货，预约发货时间
		//点选立即发货／预约发货，保存邮寄信息
		$assetItemId = $this->input->get('aiid');
		$business = $this->input->get('bsn');
		$number = $this->input->get('num');
		$interId = $this->inter_id;
		$this->datas = array();
		$this->datas['aiid'] = $assetItemId;
		$this->datas['num'] = $number;

		//获取资产细单信息
		$this->load->model('soma/Asset_customer_model','AssetCustomerModel');
		$AssetCustomerModel = $this->AssetCustomerModel;
		$item = $AssetCustomerModel->get_asset_items_by_itemId( $assetItemId, $business, $interId );

		$this->datas['year'] = '';
		$this->datas['month'] = '';
		$this->datas['date'] = '';
		if( isset($item[0]['openid']) && $item[0]['openid']!= $this->openid ){
	    	//不是自己的单
	        $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
	        redirect($url);
	    }else{
	    	if( isset( $item[0]['expiration_date'] ) ){
		    	$time = $item[0]['expiration_date'];
		    	$this->datas['year'] = date( 'Y', strtotime( $time ) ) + 0;
		    	$this->datas['month'] = date( 'm', strtotime( $time ) ) + 0;
		    	$this->datas['date'] = date( 'd', strtotime( $time ) ) + 0;
	    	}
	    }
		//获取消费地址
		$openid = $this->openid;
		$interId = $this->inter_id;

		$this->load->model('soma/Customer_address_model','CustomerAddressModel');
		$CustomerAddressModel = $this->CustomerAddressModel;

		$filter = array();
		$filter['openid'] = $openid;
		$filter['inter_id'] = $interId;

		$limit = 1;//取出一条地址信息
		$address = $CustomerAddressModel->get_addresses( $openid, $filter, $limit );
		if( $address ){
			$address = $address[0];
		}else{
			$address = array( 
					'contact'=>'', 
					'phone'=>'', 
					'province'=>'', 
					'province_name'=>'', 
					'city'=>'', 
					'city_name'=>'', 
					'region'=>'', 
					'region_name'=>'', 
					'address'=>'', 
				);
		}
		$this->datas['address'] = $address;

		$addressId = isset( $address['address_id'] ) ? $address['address_id'] : NULL;
		$this->datas['arid'] = $addressId;

		$this->load->model('soma/Cms_region_model','CmsRegionModel');
		$CmsRegionModel = $this->CmsRegionModel;

		//取出省列表
		$provinces = $CmsRegionModel->get_provinces( $interId );
		$this->datas['provinces'] = $provinces;

		$citys = array();
		$province_name = '';
		if( isset( $address['province'] ) && !empty( $address['province'] ) ){
			//获取市列表
			$provinceId = $address['province'] + 0;
			$citys = $CmsRegionModel->get_citys( $provinceId, $interId );

			$provinceDetail = $CmsRegionModel->get_region_detail( $provinceId , $interId );
			$province_name = isset( $provinceDetail['region_name'] ) ? $provinceDetail['region_name'] : '';
		}
		$this->datas['citys'] = $citys;
		$this->datas['province_name'] = $province_name;

		$regions = array();
		$city_name = '';
		if( isset( $address['city'] ) && !empty( $address['city'] ) ){
			//获取区列表
			$cityId = $address['city'] + 0;
			$regions = $CmsRegionModel->get_regions( $cityId, $interId );

			$provinceDetail = $CmsRegionModel->get_region_detail( $cityId , $interId );
			$city_name = isset( $provinceDetail['region_name'] ) ? $provinceDetail['region_name'] : '';
		}
		$this->datas['regions'] = $regions;
		$this->datas['city_name'] = $city_name;

		$region_name = '';
		if( isset( $address['region'] ) && !empty( $address['region'] ) ){
			$regionId = $address['region'] + 0;
			$provinceDetail = $CmsRegionModel->get_region_detail( $regionId , $interId );
			$region_name = isset( $provinceDetail['region_name'] ) ? $provinceDetail['region_name'] : '';
		}
		$this->datas['region_name'] = $region_name;
		
		//获取市列表链接
		$citys_url = Soma_const_url::inst()->get_url('*/api/ajax_get_citys', array('id'=>$interId ) );
		$this->datas['citys_url'] = $citys_url;
		
		//获取区列表链接
		$regions_url = Soma_const_url::inst()->get_url('*/api/ajax_get_regions', array('id'=>$interId ) );
		$this->datas['regions_url'] = $regions_url;

		//保存地址链接
		$save_address = Soma_const_url::inst()->get_url('*/*/ajax_save_address', array('id'=>$interId, 'arid'=>$addressId ) );
		$this->datas['save_address'] = $save_address;

		$header = array(
            'title' => '邮寄地址',
        );

        $this->datas['shipping_url'] = Soma_const_url::inst()->get_url('*/*/mail_post', array('id'=>$interId ) );
		$this->_view("header",$header);
	    $this->_view("shipping_address_info", $this->datas);

	}

	/**
	 * 提交发货
	 * @author luguihong
	 * @deprecated
	 */
	public function shipping_post()
	{
// var_dump( $this->input->post() );exit;
		//需要的信息asset_item_id,数量,地址ID,预约时间
		$interId = $this->inter_id;
		$business = 'package';
		$itemId = $this->input->post('aiid');//资产细单ID
		$addressId = $this->input->post('arid');//地址ID
		$shippingNum = $this->input->post('num');//邮寄数量
		$datetime = $this->input->post('datetime');//邮寄数量

		if( !$itemId || !$addressId || !$shippingNum ){
			die('提交错误,请稍候再试');
		}

		//获取资产细单信息
		$this->load->model('soma/Asset_customer_model','AssetCustomerModel');
		$AssetCustomerModel = $this->AssetCustomerModel;
		$item = $AssetCustomerModel->get_asset_items_by_itemId( $itemId, $business, $interId );
		if( !$item ){
			$url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
	        redirect($url);
		}

		//是否是本人
	    if( isset($item[0]['openid']) && $item[0]['openid']!= $this->openid ){
	    	//不是自己的单
	        $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
	        redirect($url);
	    }

	    //是否是同一个公众号
	    if( isset($item[0]['inter_id']) && $item[0]['inter_id']!= $this->inter_id ){
	    	//不是自己的单
	        $url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
	        redirect($url);
	    }

	    //判断数量
	    if( isset( $item[0]['qty'] ) && ( $item[0]['qty'] < $shippingNum || $item[0]['qty'] < 1 || $shippingNum < 1 ) ){
	    	//数量不足
	    	$url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
	        redirect($url);
	    }

	    //初始化消费单对象
	    $this->load->model('soma/Consumer_order_model','ConsumerOrderModel');
	    $ConsumerOrderModel = $this->ConsumerOrderModel;
	    $ConsumerOrderModel->business = $business;
	    
	    //生成消费单需要的参数
	    $item[0]['consumer_time'] = date( 'Y-m-d H:i:s', time() );
    	$item[0]['consume_status'] = $ConsumerOrderModel::STATUS_ALLUSE;//已使用
    	$item[0]['consumer_type'] = $ConsumerOrderModel::CONSUME_TYPE_SHIPPING;//邮寄

	    //消费细单对象
    	$this->load->model( 'soma/Consumer_item_'.$business.'_model', 'ConsumerItemModel' );
    	$ConsumerItemModel = $this->ConsumerItemModel;

    	$item[0]['consume_item_status'] = $ConsumerItemModel::STATUS_ITEM_SHIPPING;//消费细单状态

    	$item[0]['minus_qty'] = $shippingNum + 0;//扣减的资产数量

    	//订单对象
    	$orderId = $item[0]['order_id'];
    	$this->load->model('soma/Sales_order_model','SalesOrderModel');
    	$SalesOrderModel = $this->SalesOrderModel->load( $orderId );
    	if( !$SalesOrderModel ){
	    	$url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
	        redirect($url);
	    }

	    $ConsumerOrderModel->asset_item = $item;
	    $ConsumerOrderModel->business = $business;
	    $ConsumerOrderModel->order = $SalesOrderModel;

	    //配送对象
	    $this->load->model('soma/Customer_address_model','CustomerAddressModel');
	    $CustomerAddressModel = $this->CustomerAddressModel->load( $addressId );
	    if( !$CustomerAddressModel ){
	    	$url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
	        redirect($url);
	    }

		//配送信息
		$shippingInfo = array();
		$shippingInfo['address_id'] = $addressId + 0;
		$shippingInfo['datetime'] = $datetime;

	    $CustomerAddressModel->shipping_info = $shippingInfo;
	    $ConsumerOrderModel->address = $CustomerAddressModel;

	    //处理数据
	    $result = $ConsumerOrderModel->asset_to_consumer_by_shipping( $business, $interId );
// var_dump( $result );die;
	    if( $result ){
			//处理完之后，跳转到订单中心
			$url= Soma_const_url::inst()->get_url('*/asset/ucenter', array('id'=> $interId ) );
		    redirect($url);
	    	
	    }else{
	    	$url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $interId ) );
		    redirect($url);
	    }

	}

	/**
	 * 提交发货
	 * @author luguihong
	 */
	public function mail_post()
	{
// var_dump( $_POST );die;
		//需要的信息资产细单ID(aiid),数量(num),地址ID(arid),预约时间(datetime)预约发货需要
		$inter_id = $this->inter_id;
		$business = 'package';
		$openid = $this->openid;
		$post = $this->input->post(null, true);

	    //初始化消费单对象
	    $this->load->model('soma/Consumer_order_model','ConsumerOrderModel');
	    $ConsumerOrderModel = $this->ConsumerOrderModel;
	    $result = $ConsumerOrderModel->mail_consumer( $post, $openid, $inter_id, $business );
	    if( isset( $result['status'] ) && $result['status'] == Soma_base::STATUS_TRUE ){
			//处理完之后，跳转到邮寄详情
			$spid = $this->session->userdata('spid');
			if( $spid ){

				$url= Soma_const_url::inst()->get_url('*/*/shipping_detail', array('id'=> $inter_id, 'spid'=>$spid ) );
			}else{
				$url= Soma_const_url::inst()->get_url('*/*/my_shipping_list', array('id'=> $inter_id ) );
			}

		    redirect($url);
	    	
	    }else{
	    	$orderId = $this->input->get('oid');
	    	$giftId = $this->input->get('gid');
		    $url= Soma_const_url::inst()->get_url('*/*/show_shipping_info', array('id'=> $inter_id, 'oid'=>$orderId, 'gid'=>$giftId, 'bsn'=>'package' ) );
		    $this->load->library('session');
        	$this->session->set_userdata('mail_error_msg', $result['message']);
		    redirect($url);
	    }

	}

	//ajax保存地址信息
	public function ajax_save_address()
	{
		// var_dump( $this->input->post() );exit;
		$openid = $this->openid;
		$addressId = $this->input->get('arid', true);//地址ID
		$interId = $this->inter_id;

		$post = $this->input->post(null, true);
		$post['openid'] = $openid;
		$post['inter_id'] = $interId;

		$this->load->model('soma/Customer_address_model','CustomerAddressModel');
		$CustomerAddressModel = $this->CustomerAddressModel;

		$filter = array();
		$filter['inter_id'] = $interId;
		$filter['status'] = $CustomerAddressModel::STATUS_ACTIVE;
		$limit = 1;
		$addressInfo = $CustomerAddressModel->get_addresses( $openid, $filter, $limit );
		if( count( $addressInfo ) != 0 ){
			$post['address_id'] = $addressInfo[0]['address_id'];
		}
		
		$post['status'] = $CustomerAddressModel::STATUS_ACTIVE;
		//转义
		foreach ($post as $key => $value) {
			$post[$key] = addslashes( $value );
		}

		$addressId = $CustomerAddressModel->save_address( $post, $addressId );
		// echo $addressId;
		
		$msg = array();
		if( $addressId && $addressId > 0 ){
			$msg['message'] = '成功';
			$msg['status'] = 1;
			$msg['data'] = $addressId;
		}else{
			$msg = array();
			$msg['message'] = '失败';
			$msg['status'] = 2;
			$msg['data'] = '';
		}

		echo json_encode( $msg );
		die;
	}

	//邮寄列表
	public function my_shipping_list()
	{
		// var_dump( '邮寄列表' );
		$type = $this->input->get('t'); //分类
        $this->load->model('soma/Consumer_shipping_model','ConsumerShippingModel');
        $ConsumerShippingModel = $this->ConsumerShippingModel;
        $business = $this->input->get('bsn');
        $business = $business ? $business : 'package';
        $inter_id = $this->inter_id;

        $filter = array(
            'openid' => $this->openid,
            'inter_id' => $this->inter_id,
        );
        $sort = 'create_time DESC';
        switch($type){
            case 1:
                // $filter['status'] = $ConsumerShippingModel::STATUS_APPLY; //邮寄申请
                $pageTitle = $this->lang->line('all_mails');
                $orders = $ConsumerShippingModel->get_shipping_list($business, $this->inter_id,$filter,$sort);
                break;
            case 2:
                $filter['status'] = $ConsumerShippingModel::STATUS_APPLY; //未发货
                $pageTitle = '未发货';
                $orders = $ConsumerShippingModel->get_shipping_list($business, $this->inter_id,$filter,$sort);
                break;
            case 3:
                $filter['status'] = $ConsumerShippingModel::STATUS_SHIPPED; //已发货
                $pageTitle = '已发货';
                $orders = $ConsumerShippingModel->get_shipping_list($business, $this->inter_id,$filter,$sort);
                break;
            // case 4:
            //     $filter['status'] = $ConsumerShippingModel::STATUS_RECEIVED; //已接受
            //     $pageTitle = '已接受';
            //     $orders = $ConsumerShippingModel->get_shipping_list($business, $this->inter_id,$filter,$sort);
            //     break;
            // case 5:
            //     $filter['status'] = $ConsumerShippingModel::STATUS_HOLDING; //异常处理
            //     $pageTitle = '异常处理';
            //     $orders = $ConsumerShippingModel->get_shipping_list($business, $this->inter_id,$filter,$sort);
            //     break;
            default:
                // $filter['status'] = $ConsumerShippingModel::STATUS_APPLY; //邮寄申请
                $pageTitle = $this->lang->line('all_mails');
                $orders = $ConsumerShippingModel->get_shipping_list($business, $this->inter_id,$filter,$sort);
                break;
        }

		$ids_array = array();
		if( $orders ){
			$this->load->model('soma/Consumer_item_'.$business.'_model','ConsumerItemModel');
			$ConsumerItemModel = $this->ConsumerItemModel;
			foreach ($orders as $k => $v) {
				$ids = $v['consumer_id'];
				$orders[$k]['items'] = $ConsumerItemModel->get_order_items_byIds( $ids, $business, $this->inter_id );
			}
		}else{
			$orders = array();
		}
// var_dump( $orders );exit;

		if( defined('PROJECT_AREA') 
            && PROJECT_AREA=='mooncake' ){
            $pageTitle = '月饼说-' . $pageTitle;
        }

        $header = array(
            'title' => $pageTitle
        );

        $this->datas['status_label'] = $ConsumerShippingModel->get_status_label();
        $this->datas['status_label_key'] = $ConsumerShippingModel->get_status_label_lang_key();
        $this->datas['orders'] = $orders;
        $this->datas['inter_id'] = $this->inter_id;
        $type = isset( $type ) ? $type : 1;
        $this->datas['type'] = $type;

        if($this->langDir == self::LANG_DIR_EN)
        {
        	foreach($this->datas['orders'] as $ok => $order)
        	{
        		$en_items = $order['items'];
        		foreach($order['items'] as $ik => $item)
        		{
        			if(!empty($item['name_en']))
        			{
        				$en_items[$ik]['name'] = $item['name_en'];
        			}
        		}
        		$this->datas['orders'][$ok]['items'] = $en_items;
        	}
        }

        //购买商品
        $myOrdersUrl = Soma_const_url::inst()->get_soma_order_list(array('id'=>$this->inter_id));
        $this->datas['my_orders_url'] = $myOrdersUrl;
        //我的礼物
        $myGiftsUrl = Soma_const_url::inst()->get_my_gift_list(array('id'=>$this->inter_id));
        $this->datas['my_gifts_url'] = $myGiftsUrl;
        //邮寄商品
        $myMailsUrl = Soma_const_url::inst()->get_my_mail_list(array('id'=>$this->inter_id));
        $this->datas['my_mails_url'] = $myMailsUrl;


        $this->_view("header",$header);
        $this->_view("my_shipping_list",$this->datas);
	}

	//邮寄详情
    public function shipping_detail()
    {
        $this->datas = array();
    	// var_dump( '邮寄列表' );
		// $type = $this->input->get('t'); //分类

        $business = $this->input->get('bsn');
        $business = $business ? $business : 'package';
        $inter_id = $this->inter_id;
        $shipping_id = $this->input->get('spid');
        if( !$shipping_id ){
        	$url= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $inter_id ) );
	        redirect($url);
        }

        $filter = array(
            'openid' => $this->openid,
            'inter_id' => $this->inter_id,
            'shipping_id' => $shipping_id,
        );
        $sort = 'create_time DESC';

        $this->load->model('soma/Consumer_shipping_model','ConsumerShippingModel');
        $this->load->library('Soma/Api_express');
        $Api_express = new Api_express();

        $ConsumerShippingModel = $this->ConsumerShippingModel;
        $orders = $ConsumerShippingModel->get_shipping_list($business, $this->inter_id,$filter,$sort);
		$ids_array = array();
		if( $orders ){
        	$orders = $orders[0];

			$this->load->model('soma/Consumer_item_'.$business.'_model','ConsumerItemModel');
			$ConsumerItemModel = $this->ConsumerItemModel;
			$ids = $orders['consumer_id'];
			$items = $ConsumerItemModel->get_order_items_byIds( $ids, $business, $this->inter_id );
			$orders['items'] = $items;

			//快递公司名称
//			$distributorName = $ConsumerShippingModel->get_label_byname($orders['distributor']);
//			$orders['distributor'] = $distributorName['dist_label'];
            $distributorArr = $Api_express->expressCom;
            $distributor = isset( $orders['distributor'] ) ? $orders['distributor'] :'';

            $expressComCode = $distributor;

            $orders['distributor'] = isset( $distributorArr[$distributor] ) ? $distributorArr[$distributor] : '';
            if(empty($orders['distributor'])){
                $distributor = $ConsumerShippingModel->get_label_byname($expressComCode);
                if(!empty($distributor))
                $orders['distributor'] = $distributor['dist_label'];
            }
		}else{
			$orders = array();
		}
        if( isset($orders['status']) && $orders['status'] ==  $ConsumerShippingModel::STATUS_SHIPPED || ($orders['status'] ==  $ConsumerShippingModel::STATUS_FINISHED) ){
            //邮寄状态，从redis读取快递信息
            $expressInfo = $Api_express->get_express_from_redis($expressComCode ,$orders['tracking_no'],$this->inter_id);
            if(isset($expressInfo['list']) && is_array($expressInfo['list']) && !empty($expressInfo['list']) ){
                krsort($expressInfo['list']);
                $this->datas['shippingTrack'] = $expressInfo['list'];
            }
            //update已签收
            if($expressInfo['status'] == 1 && $orders['status'] ==  $ConsumerShippingModel::STATUS_SHIPPED){
                $ConsumerShippingModel->edit_shipping_info('',$this->inter_id,array('shipping_id'=>$orders['shipping_id']),array('status'=>$ConsumerShippingModel::STATUS_FINISHED));
            }
        }



		$this->datas['status'] = $ConsumerShippingModel::STATUS_SHIPPED;//邮寄发货
		$this->datas['year'] = '';
		$this->datas['month'] = '';
		$this->datas['date'] = '';
		if( isset( $items[0]['expiration_date'] ) ){
	    	$time = $items[0]['expiration_date'];
	    	$this->datas['year'] = date( 'Y', strtotime( $time ) ) + 0;
	    	$this->datas['month'] = date( 'm', strtotime( $time ) ) + 0;
	    	$this->datas['date'] = date( 'd', strtotime( $time ) ) + 0;
    	}

        $this->datas['orders'] = $orders;
        $this->datas['ConsumerShippingModel'] = $ConsumerShippingModel;
        $this->datas['inter_id'] = $this->inter_id;
        $this->datas['spid'] = $shipping_id;
        $this->datas['bsn'] = $business;

        //获取推荐位
        $uri = 'soma_order_order_detail';
        $block = $this->get_page_block( $uri );
        $this->datas['block'] = $block;

        //修改发货方式
        $this->datas['edit_url'] = Soma_const_url::inst()->get_url('*/*/ajax_edit_shipping_type',array('id'=>$this->inter_id));

        $header = array(
            'title' => $this->lang->line('delivery_status'),
        );

        // 双语化翻译
        if($this->langDir == self::LANG_DIR_EN)
        {
        	foreach($this->datas['orders']['items'] as $key => $item)
        	{
        		if(!empty($item['name_en']))
        		{
        			$this->datas['orders']['items'][$key]['name'] = $item['name_en'];
        		}
        	}
        }

		$this->_view("header",$header);
	    $this->_view("shipping_detail", $this->datas);
    }

    //ajax修改发货方式
    public function ajax_edit_shipping_type()
    {
    	$interId = $this->inter_id;
    	$shippingId = $this->input->post('spid');
    	$business = $this->input->post('bsn');
    	$reserve_date = $this->input->post('datetime');

    	$filter = array();
    	$filter['openid'] = $this->openid;
    	$filter['inter_id'] = $interId;
    	$filter['shipping_id'] = $shippingId;

    	$data = array();
    	$data['reserve_date'] = isset( $reserve_date ) && !empty( $reserve_date ) ? $reserve_date : NULL;

    	$this->load->model('soma/Consumer_shipping_model','ConsumerShippingModel');
        $ConsumerShippingModel = $this->ConsumerShippingModel;
        $result = $ConsumerShippingModel->edit_shipping_info( $business, $interId, $filter, $data );

        $msg = array();
        if( $result ){
	        $msg['message'] = '修改成功';
	        $msg['status'] = 1;

        }else{
        	$msg['message'] = '修改失败';
	        $msg['status'] = 2;
        }

    	echo json_encode( $msg );
    	die();
    }
    
	//展示为以后的皮肤做扩展
	protected function _view($file, $datas=array() )
	{
//	    parent::_view('consumer'. DS. $file, $datas);
        parent::_view( 'consumer'. DS. $file, $datas);
	}
	
	/**
	 * 异步获取邮寄差价需要进行支付的订单号
	 * 因为要记录邮寄地址等信息，不能采用原Order::get_order_id_by_ajax的方式
	 */
	public function get_shipping_order_id_by_ajax() {

		$inter_id = $this->inter_id;
		$business = 'package';
		$openid = $this->openid;
		$post = $this->input->post(null, true);

		$op_res = array( 'status' => false, 'message' => '邮费支付失败');

	    //初始化消费单对象
	    $this->load->model('soma/Consumer_order_model','ConsumerOrderModel');
		/**
		 * @var Consumer_order_model $ConsumerOrderModel
		 */
	    $ConsumerOrderModel = $this->ConsumerOrderModel;
	    $order = $ConsumerOrderModel->generate_shipping_fee_order($post, $openid);

		if($order && $order->m_get('grand_total') > 0) {
			// $ConsumerOrderModel->shipping_order = $order->m_get('order_id');
			// $ConsumerOrderModel->shipping_fee = $order->m_get('grand_total');
			// 存在运费订单且运费大于0，记录订单邮寄信息，待订单支付完毕后生产邮费记录
			
			$data = array(
				'inter_id' => $inter_id,
				'hotel_id' => $order->m_get('hotel_id'),
				'openid' => $openid,
				'pay_order_id' => $order->m_get('order_id'),
				'shipping_data' => json_encode($post),
			);

			$this->load->model('soma/Sales_shipping_order_model', 'ss_model');
			if($this->ss_model->m_sets($data)->m_save()){
				$op_res['status'] = Soma_base::STATUS_TRUE;
				$op_res['message']  = '运费订单生成成功';
				$op_res['data'] = array('orderId' => $order->m_get('order_id'));
				$op_res['step'] = 'wxpay';
			}
	    } else {
	    	$result = $ConsumerOrderModel->mail_consumer( $post, $openid, $inter_id, $business );
	    	if( isset( $result['status'] ) && $result['status'] == Soma_base::STATUS_TRUE ){
	    		$spid = $this->session->userdata('spid');
				if( $spid ){	
					$url= Soma_const_url::inst()->get_url('*/*/shipping_detail', array('id'=> $inter_id, 'spid'=>$spid ) );
				}else{
					$url= Soma_const_url::inst()->get_url('*/*/my_shipping_list', array('id'=> $inter_id ) );
				}
				$op_res['status'] = Soma_base::STATUS_TRUE;
				$op_res['message']  = '无需支付邮费';
				$op_res['data'] = array('url' => $url);
				$op_res['step'] = 'success';
	    	}
	    }

	    echo json_encode($op_res);
	}

}
 