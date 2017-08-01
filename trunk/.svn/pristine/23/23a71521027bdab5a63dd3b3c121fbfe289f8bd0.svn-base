<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

    public $db_shard_config= array();
    public $current_inter_id= '';
    
	public function __construct()
	{
		parent::__construct();
	}

	public function get_sign_ajax()
	{
	    $inter_id= $this->input->post('id');
	    $url= $this->input->post('url');
	    
	    if($inter_id){
	        $this->load->helper('common');
	        $this->load->model('wx/publics_model', 'publics');
	        $this->load->model('wx/access_token_model');
	        $jsapiTicket = $this->access_token_model->get_api_ticket( $inter_id );
	        //$jsapiTicket = $this->access_token_model->get_api_ticket($this->session->userdata('inter_id'), $this->openid);
	        
	        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
	            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	        if(!$url)
	            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	        
	        $timestamp = time();
	        $nonceStr = createNonceStr();
	        $public = $this->publics->get_public_by_id( $inter_id );
	        
	        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
	        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
	        $signature = sha1($string);
	        $signPackage = array(
	            "appId"     => $public['app_id'],
	            "nonceStr"  => $nonceStr,
	            "timestamp" => $timestamp,
	            "url"       => $url,
	            "signature" => $signature,
	            "rawString" => $string
	        );
	        echo json_encode($signPackage);
	        
	        //触发删除对应的缓存页面
	        $cache= $this->_load_cache();
            $redis= $cache->redis->redis_instance();
            $cache_key= 'SOMA_HTML:'. $inter_id. ':';
            $all_keys = $redis->keys("{$cache_key}*");
            foreach ($all_keys as $k=>$v){
                $del_rlt= $redis->delete($v);
            }
	    }
	}
	/**
	 * 直接显示二维码，具有简单校验规则
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

	//ajax获取市列表
	public function ajax_get_citys()
	{
		$flag = FALSE;
	    $interId = $this->input->get('id');
	    $parentId = $this->input->post('pid');

	    $this->load->model('soma/Cms_region_model','CmsRegionModel');
	    $CmsRegionModel = $this->CmsRegionModel;

	    if( $flag ){
	    	if(  $parentId == 2 ){
	    		$cityId = 52;
	    	}elseif( $parentId == 27 ){
	    		$cityId = 343;
	    	}elseif( $parentId == 32 ){
	    		$cityId = 394;
	    	}else{
	    		$cityId = $parentId + 0;
	    	}
		    $regions = $CmsRegionModel->get_regions( $cityId, $interId );
		    echo json_encode($regions);
	    }else{
		    $provinceId = $parentId + 0;
		    $citys = $CmsRegionModel->get_citys( $provinceId, $interId );
		    echo json_encode($citys);
	    }
	    // $options = '';
	    // if( count( $citys ) != 0 ){
	    // 	foreach ($citys as $k => $v) {
	    // 		$options .= '<option value="'.$v['region_id'].'">'.$v['region_name'].'</option>';
	    // 	}
	    // }

	    // $msg = array();
	    // $msg['status'] = 1;
	    // $msg['message'] = '成功';
	    // $msg['first_city_id'] = $citys[0]['region_id'];
	    // $msg['html'] = $options;
	    // echo json_encode( $msg );
	    die;
	
	}
	
	//ajax获取区列表
	public function ajax_get_regions()
	{
	    $interId = $this->input->get('id');
	    $parentId = $this->input->post('pid');
	
	    $this->load->model('soma/Cms_region_model','CmsRegionModel');
	    $CmsRegionModel = $this->CmsRegionModel;
	
	    $cityId = $parentId + 0;
	    $regions = $CmsRegionModel->get_regions( $cityId, $interId );
	    if( $regions ){

	    	echo json_encode($regions);
	    }else{
	    	echo json_encode( array( array( 'region_id'=>'-1', 'region_name'=>'城区' ) ) );
	    }
	    // $options = '';
	    // if( count( $regions ) != 0 ){
	    // 	foreach ($regions as $k => $v) {
	    // 		$options .= '<option value="'.$v['region_id'].'">'.$v['region_name'].'</option>';
	    // 	}
	    // }
	    
	    // $msg = array();
	    // $msg['status'] = 1;
	    // $msg['message'] = '成功';
	    // $msg['html'] = $options;
	    // echo json_encode( $msg );
	    die;
	
	}

	/**
	 * 接口核销（双合成）
	 * @request_method 			请求方式POST
	 * @param  consumer_code 	12位的数字
	 * @param  order_id 		订单号
	 * @param  consumer_shop 	门店
	 * @param  merch_id 		对应的inter_id
	 * @param  token 			检验token
	 * @return json
	 */
	public function consumer_invoke()
	{
	    die('接口开发中');
	    
	    
		$consumer_code = isset( $_POST['consumer_code'] ) && !empty( $_POST['consumer_code'] ) ? htmlspecialchars( $_POST['consumer_code'] ) : NULL;//核销码
		$order_id = isset( $_POST['order_id'] ) && !empty( $_POST['order_id'] ) ? htmlspecialchars( $_POST['order_id'] ) : NULL;//订单号
		$consumer_shop = isset( $_POST['consumer_shop'] ) && !empty( $_POST['consumer_shop'] ) ? htmlspecialchars( $_POST['consumer_shop'] ) : NULL;//门店
		$merch_id = isset( $_POST['merch_id'] ) && !empty( $_POST['merch_id'] ) ? htmlspecialchars( $_POST['merch_id'] ) : NULL;//inter_id
		$token = isset( $_POST['token'] ) && !empty( $_POST['token'] ) ? htmlspecialchars( $_POST['token'] ) : NULL;//token

	    //参数校验
	    $return = array();
		if( empty( $token ) ){
			$return['message'] = '缺少参数token';
    		$return['error_code'] = 11001;
    		$return['result_code'] = 'FAIL';
    		return json_encode( $return );
		}

	    if( empty( $consumer_code ) ){
			$return['message'] = '缺少参数consumer_code';
    		$return['error_code'] = 11002;
    		$return['result_code'] = 'FAIL';
    		return json_encode( $return );
		}

		if( empty( $consumer_shop ) ){
			$return['message'] = '缺少参数consumer_shop';
    		$return['error_code'] = 11003;
    		$return['result_code'] = 'FAIL';
    		return json_encode( $return );
		}

		if( empty( $order_id ) ){
			$return['message'] = '缺少参数order_id';
    		$return['error_code'] = 11004;
    		$return['result_code'] = 'FAIL';
    		return json_encode( $return );
		}

		if( empty( $merch_id ) ){
			$return['message'] = '缺少参数merch_id';
    		$return['error_code'] = 11005;
    		$return['result_code'] = 'FAIL';
    		return json_encode( $return );
		}


		//检测token
	    

	    //核销操作
		$this->load->model('soma/Consumer_order_model','ConsumerOrderModel');
	    $ConsumerOrderModel = $this->ConsumerOrderModel;
	    $consumer_method = $ConsumerOrderModel::CONSUME_METHOD_API;//接口核销
	    $business = isset( $business ) ? strtolower( $business ) : 'package';
		$result = $ConsumerOrderModel->api_consumer( $consumer_code, $order_id, $consumer_shop, $consumer_method, $merch_id, $business );
	    //反馈成功与否
	    if( isset( $result['status'] ) && $result['status'] == 1 ){
	    	$return['message'] = '核销成功';
	    	$return['result_code'] = 'SUCCESS';
	    	//成功
	    }else{
	    	$return['message'] = isset( $result['message'] ) && !empty( $result['message'] ) ? $result['message'] : '核销失败';
	    	$return['error_code'] = isset( $result['error_code'] ) && !empty( $result['error_code'] ) ? $result['error_code'] : NULL;
	    	$return['result_code'] = 'FAIL';
	    	//失败
	    }
	    
	    echo json_encode( $return );
	
	}
	
	/**
	 * 月饼说跳转函数
	 */
	public function mooncake_decode_cb(){

        //有code参数，进行数据存储。
        $code = $this->input->get ( 'code' );
        // $redirect_uri = urldecode($this->input->get ( 'refer' ));
        $uri = $this->input->get('refer');
        $redirect_uri = base64_url_decode($uri);
        $inter_id = $this->input->get('id');

        if (strpos($redirect_uri, '?') !== FALSE) {
            $redirect_uri = $redirect_uri. '&code='. $this->input->get('code'). "&refer=".$uri;
        } else {
            $redirect_uri = $redirect_uri. '?code='. $this->input->get('code'). "&refer=".$uri;
        }

        $this->write_log("mooncake redirect_uri : ". $redirect_uri . " , old:" . $uri);
        redirect($redirect_uri);
        exit();


    }

    //日志写入
    public function write_log( $content, $dir = 'mooncake')
    {
        $file= date('Y-m-d_H'). '.txt';
        //echo $tmpfile;die;
        $path= APPPATH.'logs'.DS. $dir. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $fp = fopen( $path. $file, 'a');

        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $content= str_repeat('-', 40). "\n[". date('Y-m-d H:i:s'). ']'
            ."\n". $ip. "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }
	
	
	
	
	

	/**  会员接口测试  *********************************************/

	public function get_card_testing()
	{
	    $get= $this->input->get();
	    if( !isset($get['openid']) ){
	        die('请求格式：http://domain/index.php/soma/api/get_card_testing?openid=xxxxxxxxxxxxx');
	        
	    } if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
	        die('此脚本必须在测试环境运行');

        } else {
            $this->load->library('Soma/Api_member');
            $api= new Api_member('a429262687');
    	    $result= $api->get_token();
    	    $api->set_token($result['data']);
    	    
    	    if( isset($get['step']) && $get['step']=='list' ){
         	    $result= $api->conpon_sign_list( $get['openid'], rand(1,999));
    	        print_r($result);
    	        
    	    } else {
         	    $result= $api->conpon_sign(7, $get['openid'], rand(0,99));
    	        die('请求成功，查看列表：http://domain/index.php/soma/api/get_card_testing?openid=xxxxxxxxxxxxx&step=list');
    	    }
        }
	}

	//使用会员礼包
	public function package_use_testing()
	{
	    $get= $this->input->get();
	    if( !isset($get['openid']) ){
	        die('请求格式：http://domain/index.php/soma/api/package_use_testing?openid=xxxxxxxxxxxxx');
	        
	    } if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
	        die('此脚本必须在测试环境运行');

        } else {
            $this->load->library('Soma/Api_member');
            $api= new Api_member('a450089706');//放心住
    	    $result= $api->get_token();
    	    $api->set_token($result['data']);
    	    
    	    $uu_code= rand(1000, 9999);
         	$result= $api->package_use_batch( $get['openid'], $get['pid'], $uu_code, 3 );
         	var_dump( $result );
        }
	}
	
	
	//inter_id:a450089706  member_info_id :14  openid :14
	public function test1x8()
	{
	    $this->load->model('soma/Reward_benefit_model');
        $benefit_model= $this->Reward_benefit_model;
        $inter_id= 'a450089706';
        
        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $this->current_inter_id= $inter_id;
        $this->db_shard_config= $this->model_shard_config->build_shard_config($inter_id);

        $this->load->model('soma/Sales_order_model');
        $order= $this->Sales_order_model->load('1000001080');
        $order->business= 'package';
        
        $benefit_model->write_benefit_queue($inter_id, $order);
	}
	
	public function test2x8()
	{
	    $this->load->library('Soma/Api_member');
	    $api= new Api_member('a450089706');
	    //var_dump($api);
	    $result= $api->get_token();
	    $api->set_token($result['data']);
// 	    $result= $api->conpon_sign_info('43', 'o9Vbtw5bgFCel1nuSugUG4uVVZ3k');
// 	    $result= $api->conpon_all();
// 	    $result= $api->conpon_info(4);
// 	    $result= $api->conpon_sign(4, 'o9Vbtw5bgFCel1nuSugUG4uVVZ3k', rand(0,99));
// 	    $result= $api->conpon_sign_list( 'o9Vbtw5bgFCel1nuSugUG4uVVZ3k');
 	    $result= $api->conpon_sign_info('48', 'o9Vbtw5bgFCel1nuSugUG4uVVZ3k');
	    var_dump($result);
	}

	public function test3x8()
	{
	    $this->load->library('Soma/Api_member');
	    $api= new Api_member('a450089706');
	    //var_dump($api);
	    $result= $api->get_token();
	    $api->set_token($result['data']);
	    
	    $lvl= $api->member_lvl();
	    //print_r($lvl);die;

	    $info= $api->point_scale('o9Vbtw4CiTD2DvKlxy01nYhjaLdM');
	    print_r($info);die;
	    
	    
	    
	    
// 	    $result= $api->conpon_sign_info('43', 'o9Vbtw5bgFCel1nuSugUG4uVVZ3k');
// 	    $result= $api->conpon_all();
// 	    $result= $api->conpon_info(4);
// 	    $result= $api->conpon_sign(4, 'o9Vbtw5bgFCel1nuSugUG4uVVZ3k', rand(0,99));
// 	    $result= $api->conpon_sign_list( 'o9Vbtw5bgFCel1nuSugUG4uVVZ3k');
        
        $card_id= array( 1000178, 1000179, 1000180 );
        foreach ($card_id as $k=>$v){
            $result= $api->conpon_sign($v, 'o9Vbtw1W0ke-eb0g6kE4SD1eh6qU', rand(1000,9999));
            $result= $api->conpon_sign($v, 'o9Vbtw5bgFCel1nuSugUG4uVVZ3k', rand(1000,9999));
            $result= $api->conpon_sign($v, 'o9Vbtw3ELLZaarxtyw5UXV_MexFk', rand(1000,9999));
            $result= $api->conpon_sign($v, 'o9Vbtw4CiTD2DvKlxy01nYhjaLdM', rand(1000,9999));
            $result= $api->conpon_sign($v, 'o9Vbtwyb5OKXrnFJ3hF9qzYKKbJ8', rand(1000,9999));
            $result= $api->conpon_sign($v, 'o9Vbtw8ikhTrqYVxoBsEFcPw6w7M', rand(1000,9999));
            $result= $api->conpon_sign($v, 'o9Vbtw30wn-MHB5TLqac2jJNvha4', rand(1000,9999));
            $result= $api->conpon_sign($v, 'o9Vbtwx75kuo8lfQUP9Lebriv7pk', rand(1000,9999));
            $result= $api->conpon_sign($v, 'o9Vbtw3XG3Skjll1MQtXzrcAYH_4', rand(1000,9999));
	        var_dump($result);
        }
        
	}

	public function test4x8()
	{
	    $this->load->library('Soma/Api_member');
	    $api= new Api_member('a450089706');
	    //var_dump($api);
	    $result= $api->get_token();
	    $api->set_token($result['data']);
	    $openid= 'o9Vbtw5bgFCel1nuSugUG4uVVZ3k';

  	    $result= $api->point_info( $openid );
  	    var_dump($result);die;
// 	    //$result= $api->point_use( 10, $openid, rand(10,99), '10000' );
// 	    //var_dump($result);
// 	    $result= $api->point_info( $openid );
// 	    var_dump($result);
	    $result= $api->point_list( $openid );
	    var_dump($result);
	    $result= $api->point_scale();
	    var_dump($result);

  	    $result= $api->balence_info( $openid );
  	    var_dump($result);
// 	    //$result= $api->balence_use( 10, $openid, rand(10,99), '10000' );
// 	    //var_dump($result);
// 	    $result= $api->balence_info( $openid );
// 	    var_dump($result);
	    $result= $api->balence_list( $openid );
	    var_dump($result);
	    $result= $api->balence_scale();
	    var_dump($result);
    }

    //测试拼团失败退款
    public function test5x8()
	{
		die('接口没开放');
	    $this->load->model('soma/Sales_refund_model');
	    $order_id = '1000001721';
	    $inter_id = 'a450089706';
	    $business = 'package';

	    //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $this->current_inter_id= $inter_id;
        $this->db_shard_config= $this->model_shard_config->build_shard_config( $inter_id );

	    $rs = $this->Sales_refund_model->groupon_fail( $order_id, $business, $inter_id );
	    var_dump( $rs );
    }

/**  发送微信模版消息测试  *********************************************/
    //大客户模版消息
    public function test6x8()
    {
    	die('接口没开放');
        $inter_id = 'a450089706';
	    $business = 'package';
	    $openid = 'o9Vbtw3ELLZaarxtyw5UXV_MexFk';

    	//初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $this->current_inter_id= $inter_id;
        $this->db_shard_config= $this->model_shard_config->build_shard_config( $inter_id );


    	//大客户发送模版消息
	    $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
	    $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
	    
	    $this->load->model('soma/Sales_reserve_model','salesReserveModel');
	    $salesReserveModel = $this->salesReserveModel;
	    $salesReserveModel = $salesReserveModel->load( '10001' );
	    $MessageWxtempTemplateModel->send_template_by_big_customer_success( $salesReserveModel, $openid, $inter_id, $business);
	    echo 'ok';
    }

    //套票过期模版消息
    public function test7x8()
    {
    	die('接口没开放');
    	$inter_id = 'a450089706';
    	$business = 'package';
    	// o9Vbtwx75kuo8lfQUP9Lebriv7pk o9Vbtw3ELLZaarxtyw5UXV_MexFk http://credit.iwide.cn/index.php/soma/api/test7
    	$list = array(
    				array(
    					'openid'=>'o9Vbtwx75kuo8lfQUP9Lebriv7pk',
    					'gift_id'=>'',
    					'name'=>'帽峰山赏花祈福套餐＋帽峰山门票2张黄山碧［过期模版通知更换测试］',
    					'expiration_date'=>date( 'Y-m-d H:i:s', time() ),
    					'order_id'=>'1000000936'
					)
				);
    	$this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
		$MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
    	if( $list ){
            foreach ($list as $k => $v) {
                //套票到期
                /***********************发送模版消息****************************/
                $openid = $v['openid'];//发送给那个用户
                $type = $MessageWxtempTemplateModel::TEMPLATE_PACKAGE_EXPIRE;//套票到期
                $templateInfo = $MessageWxtempTemplateModel->get_template_detail_byType( $type, $inter_id );
                if( $templateInfo ){
                    $template_id = $templateInfo['template_id'];
                    $array = array();

                    if( empty( $v['gift_id'] ) ){
                        $array['name'] = '订单编号：'.$v['order_id'].'，购买的商品［'.$v['name'].'］，';
                        $array['expiration_date'] = $v['expiration_date'];//过期时间
                    }else{
                        $array['name'] = '赠送编号：'.$v['gift_id'].'，收到的礼物［'.$v['name'].'］，';
                        $array['expiration_date'] = $v['expiration_date'];//过期时间
                    }

                    $sort_array = $MessageWxtempTemplateModel->get_template_send_sort();
                    $array['sort'] = $sort_array[$type];
                    $createInfo = $MessageWxtempTemplateModel->create_template_message( $openid, $template_id, $type, $array, $inter_id, $business );
                    if( isset( $createInfo['status'] ) && $createInfo['status'] == 1 ){
                        //方式一：保存到队列里
                        // $this->save_template_message( $createInfo['data'], $inter_id );

                        //方式二：立即发送模版消息
                        $result = $MessageWxtempTemplateModel->save_template_record( $createInfo, $inter_id );
                    }
                }
                /***********************发送模版消息****************************/
            }
        }

        echo 'ok';
    }

    //秒杀订阅发送模版
    public function test8()
    {
    	die('接口没开放');
    	$inter_id = 'a450089706';
    	$business = 'package';
    	$list = array(array('openid'=>'o9Vbtw3ELLZaarxtyw5UXV_MexFk','killsec_price'=>'0.01','product_name'=>'帽峰山赏花祈福套餐＋帽峰山门票2张黄山碧','killsec_time'=>'2016-03-24 14:41:08','product_id'=>'10936'));
    	$this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
		$MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

		$this->load->model('wx/Publics_model');
        $interInfo = $this->Publics_model->get_public_by_id( $inter_id );
        $inter_id_name = isset( $interInfo['name'] ) && !empty( $interInfo['name'] ) ? $interInfo['name'] : $inter_id;

    	if( $list ){
            foreach ($list as $k => $v) {
                /***********************发送模版消息****************************/
                $openid = $v['openid'];//发送给那个用户
                $type = $MessageWxtempTemplateModel::TEMPLATE_KILLSEC_SUBSCRIBER;
                $templateInfo = $MessageWxtempTemplateModel->get_template_detail_byType( $type, $inter_id );
                if( $templateInfo ){
                    $template_id = $templateInfo['template_id'];
                    $array = array();

                    $array['name'] = $v['product_name'];
                    $array['time'] = $v['killsec_time'];
                    $array['address'] = $inter_id_name;
                    $array['money'] = $v['killsec_price'];
                    $array['product_id'] = $v['product_id'];

                    $sort_array = $MessageWxtempTemplateModel->get_template_send_sort();
                    $array['sort'] = $sort_array[$type];
                    $createInfo = $MessageWxtempTemplateModel->create_template_message( $openid, $template_id, $type, $array, $inter_id, $business );
                    if( isset( $createInfo['status'] ) && $createInfo['status'] == 1 ){
                        //方式一：保存到队列里
                        // $this->save_template_message( $createInfo['data'], $inter_id );

                        //方式二：立即发送模版消息
                        $result = $MessageWxtempTemplateModel->save_template_record( $createInfo, $inter_id );
                    }
                }
                /***********************发送模版消息****************************/
            }
        }
    }
	
	// http://tf.iwide.cn/index.php/soma/api/test_token?id=a450089706&act_id=88
    public function test_token() {
    	$this->load->helper('common');

    	$inter_id = $this->input->get('id', true);
    	$act_id = $this->input->get('act_id', true);

    	$_suffix = rand(1, 99999);
    	$openid = 'o9Vbtw1W0ke-eb0g6kE4SD1' . sprintf("%05d", $_suffix);

    	$params = array('id' => $inter_id, 'act_id' => $act_id, 'openid' => $openid);
    	$url = Soma_const_url::inst()->get_url('soma/killsec/get_killsec_token_ajax', $params);
    	$content = doCurlGetRequest($url);
    	$log = var_export(array_merge($params, array('content' => $content)), true);
    	$this->write_log($log, 'soma' . DS . 'killsec_test');
    	var_dump($content);
    }

    // http://tf.iwide.cn/index.php/soma/api/test_order?id=a450089706&hid=180&pid=10021
    public function test_order() {
    	$this->load->helper('common');

    	$inter_id = $this->input->get('id', true);
    	$hotel_id = $this->input->get('hid', true);
    	$product_id = $this->input->get('pid', true);

    	$_suffix = rand(1, 99999);
    	$openid = 'o9Vbtw1W0ke-eb0g6kE4SD1' . sprintf("%05d", $_suffix);

    	$params = array('id' => $inter_id, 'openid' => $openid);
    	$url = Soma_const_url::inst()->get_url('soma/order/get_order_id_by_ajax', $params);

    	$_SERVER["HTTP_X_REQUESTED_WITH"] = "xmlhttprequest";

    	$post_str = "business=package&settlement=default&hotel_id="
    		. $hotel_id ."&qty%5B" . $product_id . "%5D=1&name=%E5%8E%8B%E5%8A%9B%E6%B5%8B%E8%AF%95"
    		. "&phone=10086100101&mcid=&saler=&fans_saler=&fans=&product_id=" . $product_id
    		. "&quote_type=&quote=&password=&u_type=-1";

    	$content = doCurlPostRequest($url, $post_str);
    	$log = var_export(array_merge($params, array('content' => $content)), true);
    	$this->write_log($log, 'soma' . DS . 'killsec_test');
    	var_dump($content);
    }
	
}
