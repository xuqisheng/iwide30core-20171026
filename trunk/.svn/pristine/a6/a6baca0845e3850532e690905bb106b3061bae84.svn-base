<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Api
 * @author luguihong  <luguihong@jperation.com>
 * @property Api_zhiyoubao $somaApiZhiyoubao
 */
class Api extends MY_Front_Soma {

    public function __construct()
    {
        //这里需要做过滤，如果是智游宝回调的，因为没有inter_id的
        $uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
        $interId = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';
        if(
            $uri && !$interId &&
            (
                stripos( $uri, 'soma/api/order_callback' ) > 0
                || stripos( $uri, 'soma/api/consumer_callback' ) > 0
                || stripos( $uri, 'soma/api/refund_callback' ) > 0
            )
        )
        {
            //inter_id不存在的时候
            if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' )
            {
                $interId = 'a452233816';
            } else {
                $interId = 'a450089706';
            }

            $openid = '123456';
            $protocol = ( ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&id={$interId}&openid={$openid}";
            header("Location: {$url}");
            die;
        }

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
	    if( isset( $result['status'] ) && $result['status'] == Soma_base::STATUS_TRUE ){
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
    	$openids = array(
//                            'oX99yuF23gSjy63CTOHHgvjx6D9o',
//                            'oX99yuLB3A0kvQ-yPP1thmMIG-IE',
        );
    	
    	$inter_id = 'a483407432';
    	$template_id = 'DWxCkhDL9JMeWB1RVmTHRMa4dnbb8Aqda6CpLASiQ3g';//799  审核结果通知
    	$url = 'http://junting.yuji8023.com/index.php/soma/package/index?id=a483407432';

    	$this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
		$MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

		$message = json_decode('{"touser":"","template_id":"","url":"","data":{"first":{"value":"","color":"#000000"},"remark":{"value":"","color":"#000000"},"keyword1":{"value":"","color":"#000000"},"keyword2":{"value":"","color":"#000000"},"keyword3":{"value":"","color":"#000000"},"keyword4":{"value":"","color":"#000000"}}}',true);

		$message['data']['first']['value'] = '君亭酒店集团';
		$message['data']['remark']['value'] = '点击详情进入会员集市';
		$message['data']['keyword1']['value'] = '很抱歉，您不符合活动条件，但是如果您已成为我们四季会会员，可在“会员集市”兑换专享限量版收藏级君亭日记本。感谢您的关注，我们将为您呈现更多精彩活动。';
		$message['data']['keyword2']['value'] = date('Y-m-d H:i:s');
		$message['data']['keyword3']['value'] = '君亭酒店集团';
		$message['data']['keyword4']['value'] = '';
		$message['template_id'] = $template_id;
		$message['url'] = $url;

		foreach( $openids as $openid ){
			if( $openid ){
				$message['touser'] = $openid;
				var_dump( $MessageWxtempTemplateModel->send_template( json_encode( $message ), $inter_id ) );
			}
		}
    }

    //团购失败发送模版
    public function test9()
    {
    	die('接口没开放');
    	$openids = array(
//	    					'oX99yuF23gSjy63CTOHHgvjx6D9o',
//	    					'oX99yuLS0kj1cimxX_kKEVb1ny1o',
//	    					'oX99yuIMtC2_1sQoe0meVPU81EFg',
//	    					'oX99yuAOdpzFdOLy7tfTSEwwYer0',
//	    					'oX99yuNY2Bma1J16Q0rd7b-mYdIs',
//	    					'oX99yuODH26de-IWadxFGfDWN5ro',
//	    					'oX99yuAQihs6Gx0UNrTW8PboHrw8',
    				);

    	$inter_id = 'a483407432';
    	$template_id = 'AG3jT_JlOj39MALjIH_8R_vP43Ea6M6JbLwupMx8w1U';//792  预约成功提醒
    	$url = 'http://junting.yuji8023.com/index.php/soma/package/package_detail?pid=68659&id=a483407432';

    	$this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
		$MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

		$message = json_decode('{"touser":"","template_id":"","url":"","data":{"first":{"value":"","color":"#000000"},"remark":{"value":"","color":"#000000"},"keyword1":{"value":"","color":"#000000"},"keyword2":{"value":"","color":"#000000"},"keyword3":{"value":"","color":"#000000"}}}',true);

		$message['data']['first']['value'] = '君亭酒店集团';
		$message['data']['remark']['value'] = '点击详情进入购买页面';
		$message['data']['keyword1']['value'] = '恭喜您，获得5元购买资格点击链接直接购买；还可在“会员集市”兑换专享限量版收藏级君亭日记本。感谢您的关注，我们将为您呈现更多精彩活动。';
		$message['data']['keyword2']['value'] = date('Y-m-d H:i:s');
		$message['data']['keyword3']['value'] = '';
		$message['template_id'] = $template_id;
		$message['url'] = $url;

		foreach( $openids as $openid ){
			if( $openid ){
				$message['touser'] = $openid;
				var_dump( $MessageWxtempTemplateModel->send_template( json_encode( $message ), $inter_id ) );
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

    public function get_info()
    {
    	die('接口没开放');
        $this->load->library('Soma/Api_member');
        $api= new Api_member('a450089706');
        $memberInfo = $api->get_member_info( 'oX3WojhDljblJ_U5e2om6THDfUio' );
        var_dump( $memberInfo );

        //获取会员卡ID
        $membershipNumber = '';
        $memberInfo = $api->get_member_info( $v['openid'] );
        if( $memberInfo )
        {
            $memberUserInfo = (array)$memberInfo['data'];
            $membershipNumber = $memberUserInfo['membership_number'];
        }
    }

/************以下为测试碧桂园接口使用*************/
    public function biguiyuan_test()
    {
    	die('接口没开放');
        $this->load->library('Soma/Api_member');
        //积分使用
        //$api= new Api_member('a421641095');
        //$api->point_use('', '', '', 1000119821);
        //核销一张优惠券
        $api= new Api_member('a468919145');
        $res = $api->conpon_consume('10300051', 'oUNZHxI4V5xrd6oUkwmoJHHQ3GOI');
        var_dump( $res );
    }

    //验证碧桂园接口
    public function get_a()
    {
        $id = 'a421641095';
        echo <<<EOF
<html>
<body>
<form action="get_b" method="post">
公众号：<input type="type" name="itd" value="{$id}"><br />
订单编号：<input type="type" name="oid" value=""><br />
开始时间：<input type="type" name="s_date" value=""><br />
结束时间：<input type="type" name="e_date" value=""><br />
<input type="submit" value="提交">
</form>
</body>
</html>
EOF;

    }

    //碧桂园获取积分信息接口
    public function get_b()
    {

        $param = array(
            'timestamp'     =>  time(),
            'noncestr'      =>  'gyqqeky4b439mlm2b2krxf8eji2jx660',
            'itd'           =>  isset( $_POST['itd'] ) ? $_POST['itd'] : '',
        );

        if( isset( $_POST['s_date'] ) && !empty( $_POST['s_date'] ) )
        {
        	$param['s_date'] = $_POST['s_date'];
        }

        if( isset( $_POST['e_date'] ) && !empty( $_POST['e_date'] ) )
        {
        	$param['e_date'] = $_POST['e_date'];
        }

        if( isset( $_POST['oid'] ) && !empty( $_POST['oid'] ) )
        {
            $param['oid'] = $_POST['oid'];
        }

        $key = '70jd9ey3f5ckigtvh03wjsbBZ0um9lyi';
        $this->load->model ( 'api/signiture_model' );
        $param['sign'] = $this->signiture_model->get_sign ( $param, $key );

		if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' )
		{
        	$api_url = 'http://biguiyuan30.iwide.cn/index.php/soma/api/get_point_list';
        } else {
        	$api_url = 'http://credit.iwide.cn/index.php/soma/api/get_point_list';
        }

        $this->load->model('pay/wxpay_model');
        $xml = $this->wxpay_model->arrayToXml( $param );

        $this->load->helper ( 'common' );
//        $content = doCurlPostRequest ( $api_url, $xml );
        $content = curl_post_xml ( $api_url, $xml, 600 );
        echo $content;die;
    }

    //碧桂园获取积分信息接口
    public function get_point_list()
    {
        $this->load->library('Soma/Api_point');
        $api= new Api_point();
        $api->get_point_list();
    }
/************以上为测试碧桂园接口使用*************/
    //测试秒杀订阅
    public function test10()
    {
        $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
        $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
        $inter_id = 'a450089706';
        $send = array(
            'notice_id'=>19,
            'act_id'=>168,
            'inter_id'=>'a450089706',
            'openid'=>'o9Vbtw3ELLZaarxtyw5UXV_MexFk',
            'product_id'=>'10129',
            'product_name'=>'豪华大床房',
            'killsec_price'=>'0.01',
            'killsec_time'=>'2017-03-20 16:40:40',
            'create_time'=>'2017-03-20 16:10:03',
            'notice_time'=>'2017-03-20 16:31:12',
            'status'=>1,
        );
        var_dump( $MessageWxtempTemplateModel->send_template_by_killsec_subscriber( $inter_id, array($send)), array('name'=>'放心住') );
    }

    /************以下为测试智游宝接口使用*************/
    protected function _zyb_connect($flag=TRUE)
    {
        //智游宝接口地址设定
        if( $flag && isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' )
        {
            die('生产环境不开放此接口！');
        }

        $db_soma = 'iwide_soma';
        $db_soma_read = 'iwide_soma_r';
        $this->load->somaDatabase($db_soma);
        $this->load->somaDatabaseRead($db_soma_read);
    }

    /**
     * 智游宝核销回调通知测试接口
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function zyb_consumer_callback()
    {
        //智游宝接口地址设定
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' )
        {
            $url             = 'http://boss.zhiyoubao.com/boss/service/code.htm';
            $key             = '';
            $username        = '';
            $company_code    = '';
        } else {
            $url             = 'http://tf.iwide.cn/index.php/soma/api/consumer_callback';
            $key             = 'TESTFX';
            $username        = 'admin';
            $company_code    = 'TESTFX';
        }

        $act = isset( $_GET['act'] ) ? $_GET['act'] : 1;
        if( $act == 1 )
        {
            echo <<<EOF
<html>
<title>订单完结回调测试</title>
<body>
<form action="zyb_consumer_callback?act=2" method="post">
订单编号：<input type="type" name="oid" value=""><br />
核销数量：<input type="type" name="num" value=""><br />
status：<select name="status"><option value="check">check</option></select><br />
<input type="submit" value="提交">
</form>
</body>
</html>
EOF;

        } elseif( $act == 2 ) {
            $orderId = isset( $_POST['oid'] ) ? $_POST['oid'] : '';
            $num = isset( $_POST['num'] ) ? $_POST['num'] : 0;
            $status = isset( $_POST['status'] ) ? $_POST['status'] : '';
            //$orderId = 1000009473;
            //echo $orderId;die;
            $sign = md5("order_no={$orderId}{$key}");
            $url = "{$url}?order_no={$orderId}&status={$status}&checkNum={$num}&returnNum=2&total=2&sign={$sign}";
            echo '<a href='.$url.'>点这</a>';
        }

    }
    public function consumer_callback()
    {
        $this->_zyb_connect(FALSE);
        $this->load->library('Soma/Api_zhiyoubao','','somaApiZhiyoubao');
        $this->somaApiZhiyoubao->consumer_callback();
    }

    /**
     * 智游宝订单完结回调通知测试接口
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function zyb_order_callback()
    {
        //智游宝接口地址设定
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' )
        {
            $url             = 'http://boss.zhiyoubao.com/boss/service/code.htm';
            $key             = '';
            $username        = '';
            $company_code    = '';
        } else {
            $url             = 'http://tf.iwide.cn/index.php/soma/api/order_callback';
            $key             = 'TESTFX';
            $username        = 'admin';
            $company_code    = 'TESTFX';
        }

        $act = isset( $_GET['act'] ) ? $_GET['act'] : 1;
        if( $act == 1 )
        {
            echo <<<EOF
<html>
<title>订单完结回调测试</title>
<body>
<form action="zyb_order_callback?act=2" method="post">
订单编号：<input type="type" name="oid" value=""><br />
status：<select name="status"><option value="success">success</option><option value="cancel">cancel</option></select><br />
<input type="submit" value="提交">
</form>
</body>
</html>
EOF;

        } elseif( $act == 2 ) {
            $orderId = isset( $_POST['oid'] ) ? $_POST['oid'] : '';
            $num = 1;//isset( $_POST['num'] ) ? $_POST['num'] : 0;
            $status = isset( $_POST['status'] ) ? $_POST['status'] : '';
            //$orderId = 1000009473;
            //echo $orderId;die;
            $sign = md5("order_code={$orderId}{$key}");
            $url = "{$url}?order_code={$orderId}&status={$status}&checkNum={$num}&returnNum=2&total=2&sign={$sign}";
            echo '<a href='.$url.'>点这</a>';
        }

    }
    public function order_callback()
    {
        $this->_zyb_connect(FALSE);
        $this->load->library('Soma/Api_zhiyoubao','','somaApiZhiyoubao');
        $this->somaApiZhiyoubao->order_callback();
    }

    /**
     * 智游宝退票回调通知测试接口
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function zyb_refund_callback()
    {
        //智游宝接口地址设定
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' )
        {
            $url             = 'http://boss.zhiyoubao.com/boss/service/code.htm';
            $key             = '';
            $username        = '';
            $company_code    = '';
        } else {
            $url             = 'http://tf.iwide.cn/index.php/soma/api/refund_callback';
            $key             = 'TESTFX';
            $username        = 'admin';
            $company_code    = 'TESTFX';
        }

        $act = isset( $_GET['act'] ) ? $_GET['act'] : 1;
        if( $act == 1 )
        {
            echo <<<EOF
<html>
<title>退票回调测试</title>
<body>
<form action="zyb_refund_callback?act=2" method="post">
订单编号：<input type="type" name="oid" value=""><br />
退票数量：<input type="type" name="num" value=""><br />
retreat_batch_no：<input type="type" name="retreat_batch_no" value=""><br />
auditStatus：<select name="auditStatus"><option value="success">success</option><option value="failure">failure</option></select><br />
<input type="submit" value="提交">
</form>
</body>
</html>
EOF;

        } elseif( $act == 2 ) {
            $orderId = isset( $_POST['oid'] ) ? $_POST['oid'] : '';
            $retreat_batch_no = isset( $_POST['retreat_batch_no'] ) ? $_POST['retreat_batch_no'] : 0;
            $num = isset( $_POST['num'] ) ? $_POST['num'] : 0;
            $auditStatus = isset( $_POST['auditStatus'] ) ? $_POST['auditStatus'] : '';
            //$orderId = 1000009473;
            //echo $auditStatus;die;
            $sign = md5("orderCode={$orderId}{$key}");
            $url = "{$url}?retreatBatchNo={$retreat_batch_no}&orderCode={$orderId}&auditStatus={$auditStatus}&checkNum=1&returnNum={$num}&total=2&sign={$sign}";
            echo '<a href='.$url.'>点这</a>';
        }

    }
    public function refund_callback()
    {
        $this->_zyb_connect(FALSE);
        $this->load->library('Soma/Api_zhiyoubao','','somaApiZhiyoubao');
        $this->somaApiZhiyoubao->refund_callback();
    }

    /**
     * 智游宝同步订单测试接口
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function zyb_send_order()
    {
        $orderId = $this->input->get('oid',TRUE);
        $inter_id = $this->input->get('id',TRUE);
        $inter_id = $inter_id ? $inter_id : 'a450089706';
        $this->_zyb_connect();
        $this->load->library('Soma/Api_zhiyoubao',array($inter_id),'somaApiZhiyoubao');
        $result = $this->somaApiZhiyoubao->send_order( $orderId );
        var_dump( $result );
    }

    /**
     * 智游宝发送消息模版通知测试接口
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function zyb_send_msg()
    {
        $orderId = $this->input->get('oid',TRUE);
        $inter_id = $this->input->get('id',TRUE);
        $inter_id = $inter_id ? $inter_id : 'a450089706';
        $this->_zyb_connect();
        $this->load->library('Soma/Api_zhiyoubao',array('inter_id'=>$inter_id),'somaApiZhiyoubao');
        $result = $this->somaApiZhiyoubao->send_message( $orderId );
        var_dump( $result );
    }

    /**
     * 智游宝检查订单是否已同步测试接口
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function zyb_check_oorder()
    {
        $orderId = $this->input->get('oid',TRUE);
        $inter_id = $this->input->get('id',TRUE);
        $inter_id = $inter_id ? $inter_id : 'a450089706';
        $this->_zyb_connect();
        $this->load->library('Soma/Api_zhiyoubao',array('inter_id'=>$inter_id),'somaApiZhiyoubao');
        $result = $this->somaApiZhiyoubao->check_order( $orderId );
        var_dump( $result );
    }

    /**
     * 智游宝检查订单使用情况测试接口
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function zyb_check_order_use_status()
    {
        $orderId = $this->input->get('oid',TRUE);
        $inter_id = $this->input->get('id',TRUE);
        $inter_id = $inter_id ? $inter_id : 'a450089706';
        $this->_zyb_connect();
        $this->load->library('Soma/Api_zhiyoubao',array('inter_id'=>$inter_id),'somaApiZhiyoubao');
        $result = $this->somaApiZhiyoubao->check_order_use_status( $orderId );
        var_dump( $result );
    }

    /**
     * 测试获取订单核销的二维码
     * @author luguihong  <luguihong@jperation.com>
     */
    public function zyb_get_qrcode()
    {
        $orderId = $this->input->get('oid',TRUE);
        $inter_id = $this->input->get('id',TRUE);
        $inter_id = $inter_id ? $inter_id : 'a450089706';
        $this->_zyb_connect();
        $this->load->library('Soma/Api_zhiyoubao',array('inter_id'=>$inter_id),'somaApiZhiyoubao');
        $result = $this->somaApiZhiyoubao->get_qrcode( $orderId );
        var_dump( $result );
    }
    /************以上为测试智游宝接口使用*************/


    //邮寄提交测试
    public function mail_post_test()
    {
        die('接口没有开放');

        $inter_id   = 'a468919145';
        $business   = 'package';
        $openid     = 'oUNZHxI4V5xrd6oUkwmoJHHQ3GOI';

        //这里初始化分片没有成功，就需要手动修改Asset_item_package_model、Sales_order_model的table_name的后缀
        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $this->current_inter_id = $inter_id;
        $this->db_shard_config  = $this->model_shard_config->build_shard_config( $inter_id );

        $db_soma        = 'iwide_soma';
        $db_soma_read   = 'iwide_soma_r';
        $this->load->somaDatabase($db_soma);
        $this->load->somaDatabaseRead($db_soma_read);

        $post['num']            = 1;
        $post['name']           = '姜璇';
        $post['mobile']         = '13879112741';
        $post['area']           = '江西南昌青山湖区';
        $post['address']        = '湖滨东路55号金色水岸2601';
        $post['note']           = '';
        $post['mail_type']      = 'on';
        $post['datetime']       = '';
        $post['is_wx_address']  = '';
        $post['arid']           = '32270';
        $post['aiid']           = '134366';
        $post['province']       = '17';
        $post['city']           = '233';
        $post['region']         = '1962';
        $post['product_id']     = '93827';

        /**
        $inter_id   = $this->inter_id;
        $business   = 'package';
        $openid     = $this->openid;
        $post = array(
            'num'           => "1",
            'name'          => "666你",
            'mobile'        => "15920327777",
            'area'          => "广东广州天河区",
            'address'       => "可怜咯",
            'note'          => "",
            'mail_type'     => "on",
            'datetime'      => "",
            'is_wx_address' => "",
            'arid'          => "10174",
            'aiid'          => "4597",
            'province'      => "6",
            'city'          => "76",
            'region'        => "693",
            'product_id'    => "10247",
        );
         */

        $op_res = array( 'status' => false, 'message' => '邮费支付失败');

        /**
         * @var Consumer_order_model $ConsumerOrderModel
         */
        //初始化消费单对象
        $this->load->model('soma/Consumer_order_model','ConsumerOrderModel');
        $ConsumerOrderModel = $this->ConsumerOrderModel;
        $ConsumerOrderModel->db_shard_config    = $this->db_shard_config;
        $order = $ConsumerOrderModel->generate_shipping_fee_order($post, $openid);

        if($order && $order->m_get('grand_total') > 0) {
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

        var_dump( $op_res );
    }

    //手动核销一张优惠券，使用于已经存在订单优惠里面的
    public function consume_coupon()
    {
        die('接口没开放');
        $inter_id = 'a468919145';
        $order_id = 1000173760;

        /**
         * 处理券
         * @var Sales_order_discount_model $Sales_order_discount_model
         */
        $this->load->model('soma/Sales_order_discount_model');
        $Sales_order_discount_model = $this->Sales_order_discount_model;
        $res = $Sales_order_discount_model->consume_discount($order_id, $inter_id);
        var_dump( $res );
    }

    public function test_hotel_product_api()
    {
        $this->load->model('soma/Product_package_model', 'p_model');
        var_dump($this->p_model->getHotelPackageProductList('a450089706', array('not_in' => array(12592)), null, null, true));
    }

    public function test_hotel_order_api()
    {
        $this->load->model('soma/Sales_order_model', 'o_model');
        $product_info = array(
            '12644' => array('qty' => 5, 'price' => '2.00'),
            '12645' => array('qty' => 1, 'price' => '10.00'),
            '12646' => array('qty' => 2, 'price' => '20.00'),
        );
        $openid = 'o9Vbtw30wn-MHB5TLqac2jJNvha4';
        $inter_id = 'a450089706';
        var_dump($this->o_model->batchHotelPackageOrder($inter_id, $openid, $product_info));
    }

    public function test_sms_api() 
    {
        $this->load->library('Soma/Api_sms');
        // $res = $this->api_sms->sendTemplateSMS('13422280480', array('金房卡测试短信'), 60225);
        $res = $this->api_sms->sendOrderSuccessSMS('1000010599');
        var_dump($res);
    }

    public function send_fans_sms()
    {
        // $this->load->library('Soma/Api_sms');

        $inter_id = 'a490942781';
        $temp_id = 182554;
        $datas = array(
            '长沙万达文华酒店',
            '1.微信关注"长沙万达文华酒店"',
            '微商城',
            '微商城',
            '微信零钱包'
        );

        // $to_arr = array(
            // 18673185055,13574843502,15616137060,17775836887,15116378813,18073181823,13787314762,13787046488,13787171730,15367996979,13142228887,13467515058,13687330303,13507495178,15111197964,13487570958,18670781018,13787072730,18673184543,18508467895,13467626567,13467681199,13975168928,13907173611,15873111815,18684742535,15873138591,17775867401,13387313239,18684646030,13469051155,13874885343,13607315111,18390566777,15807310417,13507445691,13787787944,18674395185,15308402077,13874975222,13787021058,13574825504,13637488064,13549665555,13975183947,18818686160,15343217810,18974980883,18684715720,13667331100,18670343090,15084736610,18670028883,13755056184,13787160918,18670073604,15807310570,13574120978,18670078722,18570323361,18075100975,15386402995,13787015272,18670326127,13787004456,18607588777,18684955905,18874132522,13875812081,13574133336,13873113108,18874895566,13737766977,13548640029,15387520799,18173126965,15874880622,18073318282
        // );
        $to_arr = array(13422280480);

        $this->load->model('soma/Sms_model', 'sms_model');
        foreach($to_arr as $to)
        {
            $sms_data = array(
                'to'      => $to,
                'datas'   => $datas,
                'temp_id' => $temp_id,
            );
            $this->sms_model->sms_insert($inter_id, 2, $to, $sms_data);
        }

        echo 'success';

        // var_dump($to_arr);exit;

        // $res = $this->api_sms->sendTemplateSMS('13422280480', $datas, 182554);
        // var_dump($res);
    }
}
