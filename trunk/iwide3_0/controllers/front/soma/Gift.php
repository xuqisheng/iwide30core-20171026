<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gift extends MY_Front_Soma {

    public  $themeConfig;
    public $theme;
    public $send_pertime=1;   //在此定义每次赠送数量
    
	public function __construct()
	{
		parent::__construct();
        //theme
        $this->load->model('soma/Theme_config_model');
        $this->themeConfig = $themeConfig = $this->Theme_config_model->get_using_theme($this->inter_id);
        $this->theme = $themeConfig['theme_path'];
	}
	
// 	protected function _view_($file, $datas=array() )
// 	{
// 	    /*
// 	     * js_api_list: eg: array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' ); 一般不需要修改
// 	     * js_menu_hide: eg: array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' ); 主动隐藏
// 	     * *** 关注 js_menu_show: eg: array( 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' ); 主动显示
// 	     * *** 关注 js_share_config: eg: array('title','desc','link','imgUrl')
// 	     */
// 	    $js_api_list= $menu_show_list= $menu_hide_list= '';
// 	    $datas['wx_config'] = $this->_get_sign_package($this->inter_id);
// 	    $datas['base_api_list'] = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' );
// 	    if( isset($datas['js_api_list']) ) {
// 	        $datas['js_api_list']+= $datas['base_api_list'];
// 	    } else {
// 	        $datas['js_api_list']= $datas['base_api_list'];
// 	    }
// 	    foreach ($datas['js_api_list'] as $v){
// 	        $js_api_list.= "'{$v}',";
// 	    }
// 	    $datas['js_api_list']= substr($js_api_list, 0, -1);
	    
// 	    //主动显示某些菜单
// 	    if( !isset($datas['js_menu_show']) ) 
// 	        $datas['js_menu_show']= array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' );
// 	    foreach ($datas['js_menu_show'] as $v){
// 	        $menu_show_list.= "'{$v}',";
// 	    }
// 	    $datas['js_menu_show']= substr($menu_show_list, 0, -1);

// 	    //主动隐藏某些菜单
// 	    if( !isset($datas['js_menu_hide']) )
// 	        $datas['js_menu_hide']= array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl' );
// 	    foreach ($datas['js_menu_hide'] as $v){
// 	        $menu_hide_list.= "'{$v}',";
// 	    }
// 	    $datas['js_menu_hide']= substr($menu_hide_list, 0, -1);

// 	    if( !isset($datas['js_share_config']) )
// 	        $datas['js_share_config']= FALSE;   //array('title','desc','link','imgUrl')

// 	    $datas['uri']= array(
// 	        'module'=> $this->module,
// 	        'controller'=> $this->controller,
// 	        'action'=> $this->action,
// 	    );
// 	    $datas['inter_id']= $this->inter_id;
// 	    $datas['openid']= $this->openid;
	    
// 	    $datas['business']= $this->input->get('bsn')? $this->input->get('bsn'): ($this->input->post('bsn')? $this->input->post('bsn'): '' ) ;
// 	    $datas['settlement']= $this->input->get('stl')? $this->input->get('stl'): ($this->input->post('stl')? $this->input->post('stl'): '' ) ;
// 	    $datas['saler']= $this->input->get('saler')? $this->input->get('saler'): ($this->input->post('saler')? $this->input->post('saler'): '' ) ;
// 	    $datas['fans_id']= $this->input->get('fans_id')? $this->input->get('fans_id'): ($this->input->post('fans_id')? $this->input->post('fans_id'): '' ) ;
// 	    $path= 'soma'. DS. 'gift'. DS;
// 	    $this->load->view($path. $file, $datas);
// 	}

    /**
     * 赠礼接受列表
     */
    public function get_received_list()
    {
        //获取微信用户的信息
        $fans= $this->_get_wx_userinfo();

        $gift_id = $this->input->get('gid');
        $business = 'package';
        $inter_id = $this->inter_id;
        $this->load->model('soma/Gift_order_model', 'giftOrderModel');
        $this->datas = array();

        if(!$this->isNewTheme()){     //------------------------------------旧皮肤

            $giftOrderModel = $this->giftOrderModel->load( $gift_id );

            //能否再次赠送
            $can_receive_repeat = FALSE;
            if( in_array( $giftOrderModel->m_get('status'), $giftOrderModel->can_recevie_status() ) ){
                $can_receive_repeat = TRUE;
            }

            $filter = array();
            $filter['openid_give'] = $this->openid;
            $orders = $giftOrderModel->get_order_detail($business, $inter_id);
            //var_dump( $orders) ;

            $receiveOrders= $giftOrderModel->get_receiver_list($inter_id, $gift_id, $filter );

            // 已经分完了，不能再重复发送了
            if(count($receiveOrders) == $giftOrderModel->m_get('count_give')) {
                $can_receive_repeat = FALSE;
            }

            $openids= !empty($orders['openid_received']) ? array( $orders['openid_received'] ) : array();
            foreach ($receiveOrders as $k=>$v){
                $openids[]= $v['openid'];
            }
            $this->load->model('wx/Publics_model');
            $openid_data= !empty($openids)?$this->Publics_model->get_fans_info_byIds($openids): array();
            $openid_hash= $this->giftOrderModel->array_to_hash($openid_data, 'nickname', 'openid');
            $headimg_hash= $this->giftOrderModel->array_to_hash($openid_data, 'headimgurl', 'openid');

            foreach ($receiveOrders as $k=>$v){
                //填充openid昵称
                if( array_key_exists($v['openid'], $openid_hash ))
                    $receiveOrders[$k]['openid_nickname']= $openid_hash[$v['openid']];
                if( array_key_exists($v['openid'], $headimg_hash ))
                    $receiveOrders[$k]['openid_headimg']= $headimg_hash[$v['openid']];
            }
            //print_r($receiveOrders);die;

            if( array_key_exists($orders['openid_received'], $openid_hash ) ){
                $orders['openid_received_nickname']= $openid_hash[$orders['openid_received']];
                $orders['openid_received_headimg']= $headimg_hash[$orders['openid_received']];
            }

            $status= $this->giftOrderModel->get_status_label();
            if( array_key_exists($orders['status'], $status ) )
                $orders['status_label']= $status[$orders['status']];
        } //------------------------------------旧皮肤


        //点击分享之后开启这些按钮
        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline' );
        //分享参数配置
        $gift_sign= Soma_base::inst()->str_encrypt($gift_id, TRUE); //对链接进行签名？
        $params= array(
            'id'=> $this->inter_id,
            'gid'=> $gift_id,
            'bsn'=> $business,
            'sign'=> $gift_sign,
        );
        $nickname= empty($fans['nickname'])? '': $fans['nickname'];
        $send_link= Soma_const_url::inst()->get_url( '*/*/package_received', $params );

         //取出分享配置
        $this->load->model( 'soma/Share_config_model', 'ShareConfigModel' );
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_GIFT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list( $position, $this->inter_id );

        // 分享标题双语翻译
        if($this->langDir == self::LANG_DIR_EN)
        {
            if(!empty($share_config_detail['share_title_en'])
                && !empty($share_config_detail['share_desc_en']))
            {
                $share_config_detail['share_title'] = $share_config_detail['share_title_en'];
                $share_config_detail['share_desc'] = $share_config_detail['share_desc_en'];
            }
        }

        $this->load->helper('soma/package');
        // write_log(json_encode( $share_config_detail ), 'share_config_detail.txt' );
        if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
          $share_img = base_url('public/soma/images/gift_box.png');
        } else {
          $share_img = base_url('public/soma/images/gift_box.png');
        }
        // $share_config = array(
        $share_config = array(
            'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : "亲，{$nickname}送您一份小礼物",
            'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : '小声告诉你，嘘！已经付过钱了，快快领取吧',
            'link'=> $send_link,
            'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : $share_img,
        );

        //     'title'=> "亲，{$nickname}送您一份小礼物",
        //     'desc'=> '小声告诉你，嘘！已经付过钱了，快快领取吧',
        //     'link'=> $send_link,
        //     'imgUrl'=> base_url('public/soma/images/gift_box.png'),
        // );
        if(!$this->isNewTheme()){
            $this->datas = array( 'orders'=> $orders, 'receiveOrders'=>$receiveOrders, 'gift_model'=> $giftOrderModel );
            $this->datas['gift_model']= $giftOrderModel;
            $this->datas['js_menu_show']= $js_menu_show;
            $this->datas['js_share_config']= $share_config;
            $this->datas['send_pertime']= $giftOrderModel->m_get('per_give');
            $this->datas['can_receive_repeat']= $can_receive_repeat;
            $this->datas['gid']= $gift_id;
            $this->datas['sign']= Soma_base::inst()->str_encrypt($gift_id, TRUE);

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

        }else{     //新皮肤
            $js_menu_show = array( "'menuItem:share:appMessage'", "'menuItem:share:timeline'" );
            $this->footerDatas['js_menu_show']= implode(",",$js_menu_show);
            $this->footerDatas['js_share_config']= $share_config;
//            $redirect= urlencode( Soma_const_url::inst()->get_url('*/*/package_list_send', array('id'=> $this->inter_id) ) );
//            $redirectUrl = Soma_const_url::inst()->get_url('*/*/package_sending', array('id'=> $this->inter_id, 'redirect'=> $redirect ) );
//            $this->footerDatas['js_share_success_function'] = $this->headerDatas['js_share_app_success_function'] = "location.href = '".$redirectUrl."&gid=".$gift_id."&sign=".$gift_sign."';";


        }




        $header = array( 
            'title' => $this->lang->line('sent_list'),
        );
        $this->_view("header", $header);
        $this->_view("receive_list", $this->datas);
    }

                /**
                 * 礼物中心（发送的/接收的）
                 * @deprecated   旧版已弃用
                 * @param unknown $header
                 * @param unknown $filter
                 */
            	public function _package_list_($header, $filter=array())
            	{
            	    //print_R($filter);die;
            	    $this->load->model('soma/Gift_order_model', 'giftOrderModel');
            	    $orders= $this->giftOrderModel->get_order_list(
            	        'package',
            	        $this->inter_id,
            	        $filter,
            	        'gift_id desc'
            	        //'20,150', //pagesize, offset
            	    );
            	    // print_R($orders);die;
            	    $status= $this->giftOrderModel->get_status_label(); 
            	    foreach ($orders as $k=>$v) {
            	        if( array_key_exists($v['status'], $status)) $orders[$k]['status_label']= $status[$v['status']];
                    }
                    // print_r($orders);die;
            
                    //取出群发收到的列表
                    $receive_list= $this->giftOrderModel->get_receiver_list_byOpenId( $this->inter_id, $this->openid );
                    //根据gift_id获取的资产有其他人的，进一步过滤
                    //$receive_list= $this->giftOrderModel->filter_items_by_openid( $receive_list, $this->openid );
            
                    $this->datas = array( 'orders'=> $orders, 'gift_model'=> $this->giftOrderModel, 'receive_list'=> $receive_list );
            
                    $this->load->model('wx/publics_model');
                    $publicsModel = $this->publics_model;
                    $this->datas['publicsModel'] = $publicsModel;
            
                    $this->load->model('soma/Asset_item_package_model');
                    $AssetItemModel = $this->Asset_item_package_model;
                    $this->datas['AssetItemModel'] = $AssetItemModel;
            
                    $t = $this->input->get('t');
                    $this->datas['type'] = $t ? $t : 1;
                    
                    //购买商品
                    $myOrdersUrl = Soma_const_url::inst()->get_soma_order_list(array('id'=>$this->inter_id));
                    $this->datas['my_orders_url'] = $myOrdersUrl;
                    //我的礼物
                    $myGiftsUrl = Soma_const_url::inst()->get_my_gift_list(array('id'=>$this->inter_id));
                    $this->datas['my_gifts_url'] = $myGiftsUrl;
                    //邮寄商品
                    $myMailsUrl = Soma_const_url::inst()->get_my_mail_list(array('id'=>$this->inter_id));
                    $this->datas['my_mails_url'] = $myMailsUrl;
            
            	    $this->_view("header", $header);
                    // $this->_view("package_list", $this->datas);
            	    $this->_view("my_package_list", $this->datas);
            	}
	
	
	/**
	 * 发出礼物列表页面
	 */
    public function package_list_send()
    {
        $business= 'package';
        $inter_id= $this->inter_id;
        $openid= $this->openid;
        $this->datas = array();

        if(!$this->isNewTheme()){
            $pageTitle = $this->lang->line('send_gift');
            if( defined('PROJECT_AREA')
                && PROJECT_AREA=='mooncake' ){
                $pageTitle = '月饼说-' . $pageTitle;
            }

            $header = array(
                'title' => $pageTitle,
                'gift_identify'=> 'received',
            );

            $this->load->model('soma/Gift_order_model', 'giftOrderModel');

            //我送出的订单
            //$gift_list= $this->giftOrderModel->get_order_list($business, $inter_id, array('is_p2p'=> Soma_base::STATUS_FALSE, 'openid_give'=>$openid ), 'gift_id desc' );
            $gift_list= $this->giftOrderModel->get_order_list($business, $inter_id, array( 'openid_give'=>$openid ), 'gift_id desc' );
            //print_r($gift_list);die;

            $gift_ids= $this->giftOrderModel->array_to_hash($gift_list, 'gift_id');
            //print_r($gift_ids);die;
            if( count($gift_ids)>0 ){
                //所有群发接受清单
                $all_receiver= $this->giftOrderModel->get_receiver_list($inter_id, NULL, array('gift_id'=> $gift_ids) );
                //print_r($all_receiver);die;
            } else {
                $all_receiver= array();
            }

            //领取信息组装，注意有些群发订单是没有接收人的
            foreach ($all_receiver as $k=>$v){
                if( array_key_exists($v['gift_id'], $gift_list) ){
                    $gift_list[$v['gift_id']]['receivers'][]= $v;
                }
            }
            //print_r($gift_list );die;

            //循环提取必要信息
            /*
            $openids= array();
            foreach ($gift_list as $k=>$v){
                $openids[]= $v['openid_give'];
            }
            $this->load->model('wx/Publics_model');
            $openid_data= $this->Publics_model->get_fans_info_byIds($openids);
            $openid_hash= $this->giftOrderModel->array_to_hash($openid_data, 'nickname', 'openid');
            //var_dump($openids, $openid_hash);die;
             */

            $status= $this->giftOrderModel->get_status_label();
            //填充必要字段信息
            foreach ($gift_list as $k=>$v){
                if( array_key_exists($v['status'], $status) )
                    $gift_list[$k]['status_label']= $status[$v['status']];
            }

            $this->datas['recevie_status'] = $this->giftOrderModel->can_recevie_status();
            $this->datas['gift_list'] = $gift_list;

            //购买商品
            $this->datas['my_orders_url']  = Soma_const_url::inst()->get_soma_order_list(array('id'=>$this->inter_id));
            //收到礼物
            $this->datas['my_receive_url']  = Soma_const_url::inst()->get_url('*/*/package_list_received', array('id'=>$this->inter_id));
            //发送礼物
            $this->datas['my_send_url']  = Soma_const_url::inst()->get_url('*/*/package_list_send', array('id'=>$this->inter_id));
            //邮寄商品
            $this->datas['my_mails_url']  = Soma_const_url::inst()->get_my_mail_list(array('id'=>$this->inter_id));

            // 双语化翻译
            if($this->langDir == self::LANG_DIR_EN)
            {
                // var_dump($this->datas['gift_list']);
                $status_label_key = $this->giftOrderModel->get_status_label_lang_key();

                foreach($this->datas['gift_list'] as $ok => $gift)
                {
                    $en_items = $gift['items'];
                    foreach($gift['items'] as $ik => $item)
                    {
                        if(!empty($item['name_en']))
                        {
                            $en_items[$ik]['name'] = $item['name_en'];
                        }
                    }
                    $this->datas['gift_list'][$ok]['items'] = $en_items;

                    if(isset($status_label_key[$v['status']]))
                    {
                        $this->datas['gift_list'][$ok]['status_label'] = $this->lang->line($status_label_key[$gift['status']]);
                    }
                }

                // var_dump($status_label_key, $this->datas['gift_list']); exit;
            }
            $this->_view("header", $header);
        }

        if($this->isNewTheme()){
            $gift_id = $this->input->get('gid');
            $gift_sign= Soma_base::inst()->str_encrypt($gift_id, TRUE); //对链接进行签名？
            $params= array(
                'id'=> $this->inter_id,
                'gid'=> $gift_id,
                'bsn'=> $business,
                'sign'=> $gift_sign,
            );
            //获取微信用户的信息
            $fans= $this->_get_wx_userinfo();
            $nickname= empty($fans['nickname'])? '': $fans['nickname'];
            $send_link= Soma_const_url::inst()->get_url( '*/*/package_received', $params );
            //点击分享之后开启这些按钮
            $js_menu_show = array( "'menuItem:share:appMessage'", "'menuItem:share:timeline'" );
            $share_img = base_url('public/soma/images/gift_box.png');
            $share_config = array(
                'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : "亲，{$nickname}送您一份小礼物",
                'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : '小声告诉你，嘘！已经付过钱了，快快领取吧',
                'link'=> $send_link,
                'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : $share_img,
            );

            $this->footerDatas['js_menu_show']= implode(",",$js_menu_show);
            $this->footerDatas['js_share_config']= $share_config;
            $pageTitle = $this->lang->line('send_gift');
            $this->headerDatas['titile'] = $pageTitle;
        }

        $this->_view("my_send_list", $this->datas);
        
    }

    public function write_log( $content , $dir = 'mooncake')
    {
        $tmpfile = date('Y-m-d_H'). '.txt';
        //echo $tmpfile;die;
        $path= APPPATH. 'logs'. DS. 'soma'. DS. 'gift_receive'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $fp = fopen( $path. $tmpfile, 'a');
// var_dump( $fp,$path );die;
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $content= str_repeat('-', 40). "\n[". date('Y-m-d H:i:s'). ']'
            ."\n". $ip. "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }
	/**
	 * 接受礼物列表页面
	 */
    public function package_list_received()
    {
        $business= 'package';
        $inter_id= $this->inter_id;
        $openid= $this->openid;
        $this->datas = array();

        if(!$this->isNewTheme()){

            $pageTitle = $this->lang->line('received_gift');
            if( defined('PROJECT_AREA')
                && PROJECT_AREA=='mooncake' ){
                $pageTitle = '月饼说-' . $pageTitle;
            }

            $header = array(
                'title' => $pageTitle,
                'gift_identify'=> 'received',
            );

            $this->load->model('soma/Gift_order_model', 'giftOrderModel');

            //私人对私人赠送单
            $gift_list= $this->giftOrderModel->get_order_list($business, $inter_id, array('is_p2p'=> Soma_base::STATUS_TRUE, 'openid_received'=>$openid ), 'gift_id desc' );
            //print_r($gift_list1);die;
            $rec_list= $this->giftOrderModel->get_receiver_list_byOpenId($inter_id, $openid );
            $gift_ids= $this->giftOrderModel->array_to_hash($rec_list, 'gift_id');
            //print_r($gift_ids);die;
            $rec_gift_list = array();
            if( count($gift_ids)>0 ){
                //叠加群发接收的订单，得到最终的接受列表

                //资产细单model luguihong 20160907 添加这个目的，准确查找到对应的资产
                $this->load->model( 'soma/Asset_item_'.$business.'_model', 'AssetItemModel' );
                $AssetItemModel = $this->AssetItemModel;
                $assetItems = $AssetItemModel->get_order_items_byGiftids($gift_ids, $business, $inter_id);
                $assetItems = $this->giftOrderModel->filter_items_by_openid( $assetItems, $openid );
                // print_r( array_reverse( $assetItems ) );die;
                $rec_gift_list= $this->giftOrderModel->get_order_list_byIds('package', $inter_id, $gift_ids, array('is_p2p'=> Soma_base::STATUS_FALSE ), 'gift_id desc' );//这里查找到的资产不准确
                foreach( $assetItems as $k=>$v ){
                    if( in_array( $v['gift_id'], $gift_ids ) ){
                        $rec_gift_list[$v['gift_id']]['items'][0] = $v;
                    }
                }
                // print_r( $rec_gift_list );die;

                $gift_list += $rec_gift_list;

                //统计各个群发订单的接受情况
                //$all_ids= $this->giftOrderModel->array_to_hash($rec_gift_list, 'gift_id');
                //print_r($all_ids);die;
            }

            //键值做排序，由高到低
            krsort( $gift_list );

            //print_r( $gift_list );die;
            //所有群发接受清单
            //$all_receiver= $this->giftOrderModel->get_receiver_list($inter_id, NULL, array('gift_id'=> $all_ids) );
            //print_r($all_receiver);die;

            //进行群发数组组装
            foreach ($rec_gift_list as $k=>$v){
                if( array_key_exists($v['gift_id'], $gift_list) )
                    $gift_list[$v['gift_id']]['receivers'][]= $v;
            }
            //print_r($gift_list['1000000746']['items'][0]);die;

            if(count($gift_list) > 0) {
                //消费信息
                $this->load->model('soma/Asset_item_package_model');
                $asset_item= $this->Asset_item_package_model->get_order_items_by_filter($inter_id, array(
                    'openid'=> $openid, 'gift_id'=> array_keys($gift_list),
                ));
                $consum_hash= $this->Asset_item_package_model->array_to_hash($asset_item, 'qty', 'gift_id');
                //count($consum_hash);die;

                //循环提取必要信息
                $openids= array();
                foreach ($gift_list as $k=>$v){
                    $openids[]= $v['openid_give'];
                }
                $this->load->model('wx/Publics_model');
                $openid_data= $this->Publics_model->get_fans_info_byIds($openids);
                $openid_hash= $this->giftOrderModel->array_to_hash($openid_data, 'nickname', 'openid');
                //var_dump($openids, $openid_hash);die;

                $status= $this->giftOrderModel->get_status_label();
                //填充必要字段信息
                foreach ($gift_list as $k=>$v){
                    if( array_key_exists($v['status'], $status) )
                        $gift_list[$k]['status_label']= $status[$v['status']];

                    //填充openid昵称
                    if( array_key_exists($v['openid_give'], $openid_hash ))
                        $gift_list[$k]['openid_nickname']= $openid_hash[$v['openid_give']];

                    //填充消费情况
                    if( array_key_exists($v['gift_id'], $consum_hash) ) $gift_list[$k]['consum_qty']= $consum_hash[$v['gift_id']];
                    else $gift_list[$k]['consum_qty']= 0;
                }
            }

            $this->datas['gift_list'] = $gift_list;

            //购买商品
            $this->datas['my_orders_url']  = Soma_const_url::inst()->get_soma_order_list(array('id'=>$this->inter_id));
            //收到礼物
            $this->datas['my_receive_url']  = Soma_const_url::inst()->get_url('*/*/package_list_received', array('id'=>$this->inter_id));
            //发送礼物
            $this->datas['my_send_url']  = Soma_const_url::inst()->get_url('*/*/package_list_send', array('id'=>$this->inter_id));
            //邮寄商品
            $this->datas['my_mails_url']  = Soma_const_url::inst()->get_my_mail_list(array('id'=>$this->inter_id));
    // $this->write_log( $inter_id . ':openid ' . $openid );
    // $this->write_log( $inter_id . ':gift_list ' . json_encode( $gift_list ) );
    // $this->write_log( $inter_id . ':datas ' . json_encode( $this->datas ) );
    //
            // var_dump($this->datas['gift_list']);
            // 双语化翻译
            if($this->langDir == self::LANG_DIR_EN)
            {
                foreach($this->datas['gift_list'] as $ok => $gift)
                {
                    $en_items = $gift['items'];
                    foreach($gift['items'] as $ik => $item)
                    {
                        if(!empty($item['name_en']))
                        {
                            $en_items[$ik]['name'] = $item['name_en'];
                        }
                    }
                    $this->datas['gift_list'][$ok]['items'] = $en_items;
                }
            }

            $this->_view("header", $header);
        }
        if($this->isNewTheme()){
            $pageTitle = $this->lang->line('received_gift');
            $this->headerDatas['titile'] = $pageTitle;
        }
        $this->_view("my_receive_list", $this->datas);
        
    }
    
    
    /**
     * 全部礼物列表页面(已重定向)
     * @deprecated
     */
    public function my_gift_list()
    {
        $this->package_list_received();
    }

    /**
     * 送礼详情页面
     */
    public function package_detail()
    {
        $inter_id = $this->inter_id;
	    $gift_id= $this->input->get('gid');
	    $openid= $this->openid;
        $business= 'package';
	    $this->load->model('soma/Asset_customer_model', 'assetCustomerModel');
	    $this->load->model('soma/Gift_order_model', 'giftOrderModel');
        $this->load->model('soma/product_package_model','ProductPackageModel');
        $this->load->model('soma/Consumer_item_package_model','ConsumerPackageModel');
        $this->load->model('soma/Gift_item_package_model','GiftItemModel');
        $GiftItemModel = $this->GiftItemModel;
        $GiftOrderModel = $this->giftOrderModel;

        //针对 发送中、未领取的赠送订单，会出现找不到对应 asset_item 的情况，所以会造成报错。
        $ids= array();
        $filter= array( 'gift_id'=> $gift_id );
        $items = $this->assetCustomerModel->get_gift_recevied_item($filter, $business, $inter_id);

        //筛选属于自己的资产订单
        $items = $this->assetCustomerModel->filter_items_by_openid( $items, $this->openid );
        //print_r($items);die;
        $giftSendOrderDetail = array();
        foreach ($items as $v) {
            $ids[]= $v['item_id'];  //需要匹配的资产Item ID
            $orderId = isset( $v['order_id'] ) ? $v['order_id'] : NULL;
            $giftItems = $GiftItemModel->get_order_items_byAssetItemIds($v['item_id'],$business,$this->inter_id);
            $giftDetail = array();
            foreach ($giftItems as $sk => $sv) {
                $giftDetail = $GiftOrderModel->load($sv['gift_id'])->m_data();
                if( $giftDetail['status'] != $GiftOrderModel::STATUS_TIMEOUT ){//超时退回的暂时不显示 
                    $giftDetail['items'][] = $sv;
                    $giftSendOrderDetail[] = $giftDetail;
                }
            }
        }
        $gift_status = $GiftOrderModel->get_status_label();
        
        $param = array();
        $param['id'] = $inter_id;
        $param['gid'] = $gift_id;
        $param['bsn'] = $business;
        $mail_url = Soma_const_url::inst()->get_soma_shipping( $param );//邮寄
        
        $model= $this->giftOrderModel->load($gift_id);
        if( !$gift_id || !$model /* || $model->m_get('openid_received')!= $this->openid */ ){
            redirect( Soma_const_url::inst()->get_url('*/package/index', array('id'=> $this->inter_id ) ) );
        }
        
        $detail= $model->m_data();
//         $items = $this->giftOrderModel->load($gift_id)->get_order_items($business, $inter_id );   //获取赠礼对应的资产明细
//         $pids= $this->giftOrderModel->array_to_hash($items, 'item_id');
        //print_r($items);die;
        //gift item 中的真实数量
        
//         $requirement= $this->giftOrderModel->load($gift_id)->get_requirement($business, $inter_id );  //array( asset_item_id=>qty_require )
//         foreach ($items as $k=>$v){
//             if( array_key_exists($v['item_id'], $requirement) ) $items[$k]['qty_require']= $requirement[$v['item_id']];
//         }

        if(count($ids)>0 ){
            $consumer_items= $this->ConsumerPackageModel->get_order_items_byAssetItemIds( array_values($ids), $business, $inter_id);
            //print_r( $consumer_items );die;
        } else {
            $consumer_items= array();
        }

        $this->load->model('soma/Consumer_shipping_model','ConsumerShippingModel');
        $ConsumerShippingModel = $this->ConsumerShippingModel;
        $can_mail_yes = $ConsumerShippingModel::CAN_MAIL_YES;
        // $this->datas['can_mail_yes'] = $can_mail_yes;
        
        $send_from = $this->input->get('send_from', true);
        if(!$send_from) { $send_from = ''; }
        $send_order_id = $this->input->get('send_order_id', true);
        if(!$send_order_id) { $send_order_id = ''; }

	    $header = array( 
	        'title' => $this->lang->line('gift_details')
	    );

        //获取推荐位
        $uri = 'soma_gift_package_detail';
        $block = $this->get_page_block( $uri );

	    $datas= array( 
            'detail'=> $detail,
            'gift_model'=> $model, 
	        'gift_status'=> $gift_status, 
	        'a_items'=> $items, 
	        'consumer_status'=> $this->ConsumerPackageModel->get_item_status_label(), 
	        'product_model'=> $this->ProductPackageModel, 
            'c_items'=> $consumer_items,
            'mail_url'=> $mail_url,
            'can_mail_yes'=> $can_mail_yes,
            'giftSendOrderDetail'=> $giftSendOrderDetail,
            'send_from' => $send_from,
            'send_order_id' => $send_order_id,
            'block' => $block,
	    );
	    
        //对于送出订单，领取后获取其昵称
        if( $detail['openid_received'] && $this->openid== $detail['openid_give'] ){
            $this->load->model('wx/publics_model');
            $fans= $this->publics_model->get_fans_info( $detail['openid_received'] );
            $datas['fans']= $fans;
        }
        $this->_view("header", $header);
        $this->_view("package_detail", $datas );
        
    }

    /**
     * 拉起赠送好友，生成赠礼记录之后执行发送动作
     */
    public function package_send_ajax()
    {
        $return= array('status'=> Soma_base::STATUS_FALSE, 'message'=>'参数错误');
    
        $inter_id= $this->inter_id;
        $business= 'package';
        $aiids= $this->input->post('aiids'); //asset_item的需求量json数组
        $message= $this->input->post('msg'); //祝福语
        $theme_id= $this->input->post('tid'); //礼物主题ID
        $is_group= $this->input->post('is_group'); //是否是群发
        $count_give= (int) $this->input->post('count_give'); //收礼人数
        $per_give= (int) $this->input->post('per_give'); //发出礼盒数
        $total_qty= $per_give* $count_give;

        $send_from = $this->input->post('send_from');
        $send_order_id = $this->input->post('send_order_id');

//var_dump( $_POST );die;

        $item_ids= array_keys($aiids);
        $this->load->model('soma/Asset_item_package_model','assetItemModel');
        $items= $this->assetItemModel->get_order_items_byItemids( $item_ids, $business, $inter_id );
        
        if( !$items || $items[0]['qty']< $total_qty ){
            Soma_base::inst()->show_exception( '您的礼品数量不足！' );
        }
        foreach ($items as $k=>$v){
            if( $v['can_gift']== Asset_item_package_model::STATUS_CAN_NO ) 
	            Soma_base::inst()->show_exception( $v['name']. '不允许赠送' );
            
            else if( in_array($v['item_id'], $item_ids) ){
                $items[$k]['qty_require']= $aiids[$v['item_id']];
            }
        }
        //print_r($items);die;
    
        $this->load->model('soma/Gift_order_model','giftOrderModel');
        $this->giftOrderModel->is_p2p= ( $is_group== Soma_base::STATUS_TRUE )? Soma_base::STATUS_FALSE: Soma_base::STATUS_TRUE;
        $this->giftOrderModel->sender= new Gift_order_attr_customer( $this->openid );
        $this->giftOrderModel->rule= new Gift_order_attr_rule($per_give, $count_give);
        $this->giftOrderModel->theme= new Gift_order_attr_theme($theme_id, $message);
        $this->giftOrderModel->item= $items;
        
        $this->giftOrderModel->send_from = $send_from;
        $this->giftOrderModel->send_order_id = $send_order_id;

        $gift_id= $this->giftOrderModel->order_save($business, $this->inter_id);
        
        if($gift_id){
            if( $is_group== Soma_base::STATUS_TRUE ){
                $result= $this->giftOrderModel->set_redis_list($inter_id, $gift_id);
            } else {
                $result= TRUE;
            }
            $sign= Soma_base::inst()->str_encrypt($gift_id, TRUE);
            if($gift_id){
                $return['status']= Soma_base::STATUS_TRUE;
                $return['data']= $gift_id;
                $return['sign']= $sign;
                $return['desc']= $message;
                $return['message']= $result? '赠礼打包成功': 
                    '您群发的礼物暂时无法领取，'. Gift_order_model::EXPIRED_HOURS. '小时候将自动退回';
            }
        }
        echo json_encode($return);
    }

    /**
     * 礼物完成赠送动作标记状态为已发出
     */
    public function package_sending()
    {
        //标记礼物已经发送
        $business= 'package';
        $gid= $this->input->get('gid');
        $this->load->model('soma/Gift_order_model');
        $model= $this->Gift_order_model->load($gid);
        if($model){
            $model->order_gifting($business, $this->inter_id );
        }
        //按照redirect重定向
        $redirect= urldecode( $this->input->get('redirect') );
        redirect($redirect);
    }

    /**
     * 月饼说首页选择送朋友，购买后进行跳转页面
     */
    public function package_pre_send()
    {
        $inter_id = $this->input->get('id');
        if(!$inter_id) { 
            $inter_id = $this->inter_id; 
        }

        $order_id = $this->input->get('oid');
        $this->load->model('soma/Sales_order_model', 'o_model');
        $order = $this->o_model->load($order_id);
        if(!$order) { 
            die("无效订单"); 
        }

        $bsn = $this->input->get('bsn');
        if($bsn != $order->m_get('business')) {
            die('无效业务类型');
        }

        $order_asset_detail = $order->get_order_asset($order->m_get('business'), $order->m_get('inter_id'));
        $asset_items = $order_asset_detail['items'];
        if(count($asset_items) <= 0) {
            die('订单资产异常');
        }

        $params['aiid'] = $asset_items[0]['item_id'];
        $params['group'] = $asset_items[0]['qty']>1 ? Soma_base::STATUS_TRUE : Soma_base::STATUS_FALSE;
        $params['bsn'] = $bsn;
        $params['send_from'] = $this->input->get('send_from', true);
        $params['send_order_id'] = $this->input->get('send_order_id', true);

        if( $asset_items[0]['can_gift']==Soma_base::STATUS_TRUE ){
            $url= Soma_const_url::inst()->get_url( '*/*/package_send', $params );
        } else {
            //礼物不可赠送时调到订单中心
            $url= Soma_const_url::inst()->get_url( '*/order/order_detail', array(
                'oid'=> $order_id, 'bsn'=> $bsn, 'id'=> $inter_id
            ) );
        }
        redirect( $url );
    }

    /**
     *  赠送生成页面
     */
    public function package_send()
    {
        //获取微信用户的信息
        $fans= $this->_get_wx_userinfo();
        $this->datas = array();
//        $this->load->model('soma/shard_config_model', 'model_shard_config');
//        $this->db_shard_config = $this->model_shard_config->build_shard_config($this->inter_id);
        if(!$this->isNewTheme()){

            $is_group= $this->input->get('group');
            $business= $this->input->get('bsn');
            //$business= 'package';

            $header = array(
                'title' => $this->lang->line('gift_to_friend'),
            );
            $this->load->model('soma/Gift_order_model','giftOrderModel');
            $this->load->model('soma/Asset_item_package_model','assetItemModel');
            $asset_item_id= $this->input->get('aiid');
            $inter_id= $this->inter_id;
            $items= $this->assetItemModel->get_order_items_byItemids( array($asset_item_id), $business, $inter_id );
            $item_ids= $this->assetItemModel->array_to_hash($items, 'item_id' );
            //print_r($item_ids);die;       //array( 1275, 1276 )

            //检查能否赠送
            $this->load->model("soma/Sales_order_model",'SalesOrderModel');
            $order_id = isset( $items[0]['order_id'] ) ? $items[0]['order_id'] : '';
            $SalesOrderModel = $this->SalesOrderModel->load( $order_id );
            if( $SalesOrderModel ){
                if( !$SalesOrderModel->can_gift_order() ){
                    die($this->lang->line('can_not_gift_tip'));
                }
            }else{
                die('检查能否赠送失败，加载sales_order_model失败！');
            }

            if( count($items)==0 ){
                die('参数错误！');

            } elseif( $items[0]['openid'] != $this->openid ){
                //并非自己的资产不能处理
                die('无可赠送礼物');
            }
            //纠正发送方式
            if( $items[0]['qty']<2 ){
                $is_group= Soma_base::STATUS_FALSE;
            }
            if( !$is_group ){
                $is_group = Soma_base::STATUS_FALSE;//防止没有传group参数进来，而且剩余数量大于2
            }

            $time = time();
            $expireTime = isset( $items[0]['expiration_date'] ) ? strtotime( $items[0]['expiration_date'] ) : NULL;
            $is_expire = FALSE;
            if( $expireTime && $expireTime < $time ){
                $is_expire = TRUE;
                die('已经过期不能进行赠送！');
            }

            $giftTheme = array(
                //这些数据暂时是不可修改的，后台有上传功能，直接从数据库取
                //theme字段是暂时定义的，到时候在后台上传赠送主题，则不需要，直接使用背景图链接即可
                array('theme_id'=>1,'theme'=>'theme0.jpg','theme_name'=>$this->lang->line('featured')),
                // array('theme_id'=>2,'theme'=>'theme1.jpg','theme_name'=>'爱情'),
                // array('theme_id'=>3,'theme'=>'theme2.jpg','theme_name'=>'父母'),
                // array('theme_id'=>4,'theme'=>'theme3.jpg','theme_name'=>'长辈'),
                // array('theme_id'=>5,'theme'=>'theme4.jpg','theme_name'=>'亲人'),
                // array('theme_id'=>6,'theme'=>'theme5.jpg','theme_name'=>'朋友'),
                // array('theme_id'=>7,'theme'=>'theme6.jpg','theme_name'=>'精选'),
            );

            //点击分享之后开启这些按钮
            $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline' );
            //分享参数配置
            $gift_sign= ''; //对链接进行签名，在前端请求获得
            $params= array(
                'id'=> $this->inter_id,
                'bsn'=> $business,
            );
            $nickname= empty($fans['nickname'])? '': $fans['nickname'];
            $send_link= Soma_const_url::inst()->get_url( '*/*/package_received', $params );

            //取出分享配置
           $this->load->model( 'soma/Share_config_model', 'ShareConfigModel' );
           $ShareConfigModel = $this->ShareConfigModel;
           $position = $ShareConfigModel::POSITION_GIFT;//分享类型
           $share_config_detail = $ShareConfigModel->get_share_config_list( $position, $this->inter_id );

           // 分享标题双语翻译
            if($this->langDir == self::LANG_DIR_EN)
            {
                if(!empty($share_config_detail['share_title_en'])
                    && !empty($share_config_detail['share_desc_en']))
                {
                    $share_config_detail['share_title'] = $share_config_detail['share_title_en'];
                    $share_config_detail['share_desc'] = $share_config_detail['share_desc_en'];
                }
            }

           $this->load->helper('soma/package');
           // write_log(json_encode( $share_config_detail ), 'share_config_detail.txt' );
            if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
              $share_img = base_url('public/soma/images/gift_box.png');
            } else {
              $share_img = base_url('public/soma/images/gift_box.png');
            }
            $share_config = array(
                'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : str_replace('[0]', $nickname, $this->lang->line('send_you_gift')),//"亲，{$nickname}送您一份小礼物",
                'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : $this->lang->line('pay_success_tip'),
                'link'=> $send_link,
                'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : $share_img,
            );
    // var_dump( $items );die;
            $check_follow_ajax= Soma_const_url::inst()->get_url( '*/*/check_follow_ajax', array('id'=>$this->inter_id) );
            $this->datas = array(
                'items'=> $items,
                'nickname'=> $nickname,
                'fans'=> $fans,
                'themeConfig'=> $this->themeConfig,
                'item_ids'=> array_values($item_ids),
                'item_model'=> $this->assetItemModel,
                'js_menu_show'=> $js_menu_show,
                'js_share_config'=> $share_config,
                'send_pertime'=> $this->send_pertime,
                'is_expire'=> $is_expire,
                'is_group'=> $is_group,
                'giftTheme'=> $giftTheme,
                'check_follow_ajax'=> $check_follow_ajax,
                'send_from' => $this->input->get('send_from',true),
                'send_order_id' => $this->input->get('send_order_id',true),
            );

            // 双语化翻译
            if($this->langDir == self::LANG_DIR_EN)
            {
                foreach($this->datas['items'] as $key => $item)
                {
                    if(!empty($item['name_en']))
                    {
                        $this->datas['items'][$key]['name'] = $item['name_en'];
                    }
                }
            }
            $this->_view("header",$header);
        }
        if($this->isNewTheme()){
            $pageTitle = $this->lang->line('gift_to_friend');
            $this->headerDatas['titile'] = $pageTitle;
        }
        $this->_view("package_send",$this->datas);
    }

    /**
     *  重复赠送生成页面
     *  @deprecated
     */
    public function package_send_repeat_()
    {
        //获取微信用户的信息
        $fans= $this->_get_wx_userinfo();
        
        $header = array(
            'title' => $this->lang->line('gift_to_friend')
        );
        $this->load->model('soma/Gift_order_model','giftOrderModel');
        $this->load->model('soma/Asset_item_package_model','assetItemModel');
        $gift_id= $this->input->get('gid');
        
        $gift_model= $this->giftOrderModel->load($gift_id);
        if( !$gift_model || !in_array($gift_model->m_get('status'), $gift_model->can_recevie_status() ) ){
            $url= Soma_const_url::inst()->get_url('*/*/package_list_send', $this->input->get() );
            redirect($url);
        }
        
        $inter_id= $this->inter_id;
        $business= 'package';
        $items= $this->giftOrderModel->load($gift_id)->get_order_items( $business, $inter_id );
        $item_ids= $this->assetItemModel->array_to_hash($items, 'item_id' );
        //print_r($items);die;
        
        if( count($items)==0 ){
            die('参数错误！');
        
        } elseif( $items[0]['openid'] != $this->openid ){
            //并非自己的资产不能处理
            die('无可赠送礼物');
        }
        //点击分享之后开启这些按钮
        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline' );
        //分享参数配置
        $gift_sign= Soma_base::inst()->str_encrypt($gift_id, TRUE); //对链接进行签名？
        $params= array(
            'id'=> $this->inter_id,
            'gid'=> $gift_id,
            'bsn'=> $business,
            'sign'=> $gift_sign,
        );
        $nickname= empty($fans['nickname'])? '': $fans['nickname'];
        $send_link= Soma_const_url::inst()->get_url( '*/*/package_received', $params );
        $share_config = array(
            'title'=> str_replace('[0]', $nickname, $this->lang->line('send_you_gift')),
            'desc'=> $this->lang->line('pay_success_tip'),
            'link'=> $send_link,
            'imgUrl'=> base_url('public/soma/images/gift_box.png'),
        );
        $this->load->helper('soma/package');
        $this->datas = array(
            'gid'=> $gift_id,
            'items'=> $items,
            'item_ids'=> array_values($item_ids),
            'item_model'=> $this->assetItemModel,
            'js_menu_show'=> $js_menu_show,
            'js_share_config'=> $share_config,
            'send_pertime'=> $this->send_pertime,
        );
        $this->_view("header",$header);
        $this->_view("package_send_repeat",$this->datas);
    }

    /**
     * 接受赠送
     * eg: index.php/soma/gift/package_received?id=a429262687&sign=&gid=1000000199&openid=seiferli
     */
    public function package_received()
    {

        if($this->isNewTheme()){

            $this->gift_new_router();


            return;

        }


        $rec= $this->_received();  //TRUE: 已经领过；FALSE: 初次领取
        if( $rec ) {
            $header = array(
                'title' => $this->lang->line('received_gift')
            );
            $this->load->model('soma/Gift_order_model','giftOrderModel');
            $this->load->model('soma/Asset_item_package_model','assetItemModel');

            $inter_id= $this->inter_id;
            $business= 'package';
            $gift_id= $this->input->get('gid');
            $giftModel= $this->giftOrderModel->load($gift_id);
            //$sign= Soma_base::inst()->str_decrypt($this->input->get('sign'), TRUE);
            
            //if( $gift_id != $sign ){  //此处移至_received()做处理
            //    Soma_base::inst()->show_exception('接受分享链接签名错误！');
            //}
            $items= $this->assetItemModel->get_order_items_byGiftids( array( $gift_id ), $business, $inter_id);

            //筛选自己的资产，群发的时候，有可能取出其他人的资产，gift_id相同
            $items = $this->assetItemModel->filter_items_by_openid( $items, $this->openid );

            $item_ids= $this->assetItemModel->array_to_hash($items, 'item_id' );
            
            if( count($items)==0 ){
                die('参数错误！');
            }
            //点击分享之后开启这些按钮
            $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline' );
            //分享参数配置
            $gift_sign= ''; //对链接进行签名？
            $params= array(
                'id'=> $this->inter_id,
                'bsn'=> $business,
                'sign'=> $gift_sign,
            );
            $send_link= Soma_const_url::inst()->get_url( '*/*/package_received', $params );
            $share_config = array(
                'title'=> str_replace('[0]', '', $this->lang->line('send_you_gift')),
                'desc'=> $this->lang->line('pay_success_tip'),
                'link'=> $send_link,
                'imgUrl'=> base_url('public/soma/images/gift_box.png'),
            );

            $go_url = '';
            $receive_items = array();
            //要通过赠送编号来确定是哪个资产细单
            foreach ($items as $k => $v) {
                if( $v['gift_id'] == $gift_id ){
                    $receive_items[] = $v;
                }
            }
            //如果已经使用完了
            if( count( $receive_items ) > 0 && isset( $receive_items[0]['qty'] ) && $receive_items[0]['qty'] == 0 ){
                /*
                    luguihong 20160826
                    备注：这个功能，只有当数量用完之后才会生效
                    1:赠送数量为1的情况 
                        1.如果是赠送朋友的就跳到赠送详情页
                        2.如果是邮寄到家的就跳到邮寄详情页
                        3.如果是到店自提的就跳到核销详情页
                    2:赠送数量>=2的情况
                        1.暂时的处理方式是跳到邮寄的商品（邮寄列表页）
                */
                $this->load->model('soma/Consumer_item_package_model','ConsumerItemModel');
                $ConsumerItemModel = $this->ConsumerItemModel;
                $consumerItems = $ConsumerItemModel->get_order_items_byAssetItemIds($receive_items[0]['item_id'],$business,$inter_id);

                // $this->load->model('soma/Gift_item_package_model','GiftItemModel');
                // $GiftItemModel = $this->GiftItemModel;
                // $giftItems = $GiftItemModel->get_order_items_byAssetItemIds($receive_items[0]['item_id'], $business, $inter_id);
                // $giftItems_new = array();
                // foreach( $giftItems as $k=>$v ){
                //     if( $v['gift_id'] == $gift_id ){
                //         $giftItems_new[] = $v;
                //     }
                // }

                $this->load->model('soma/Consumer_shipping_model','ConsumerShippingModel');
                $ConsumerShippingModel = $this->ConsumerShippingModel;

                $giftNum = $giftModel->m_get('total_qty') / $giftModel->m_get('count_give');
                if( $giftNum == 1 ){
                    if( count( $consumerItems ) > 0 ){
                        //判断是邮寄／自提
                        if( !empty( $consumerItems[0]['consumer_code'] ) ){
                            if( $consumerItems[0]['status'] == $ConsumerItemModel::STATUS_ITEM_PENDING )
                            {
                                //待核销

                                //邮寄在这里是没有记录核销码的，所以这里是自提
                                $go_url = Soma_const_url::inst()->get_url('*/order/order_detail', array(
                                        'id'=> $inter_id,
                                        'oid'=>$consumerItems[0]['order_id'],
                                        'bsn'=>$business,
                                    )
                                );
                            } else {
                                //已核销

                                //邮寄在这里是没有记录核销码的，所以这里是自提
                                $go_url = Soma_const_url::inst()->get_url('*/consumer/consumer_detail', array(
                                        'id'=> $inter_id,
                                        'aiid'=>$consumerItems[0]['asset_item_id'],
                                        'bsn'=>$business,
                                        'code'=>$consumerItems[0]['consumer_code']
                                    )
                                );
                            }
                        }else{
                            $shippingIdArr = $ConsumerShippingModel->get_shipping_id( $consumerItems[0]['order_id'], $consumerItems[0]['consumer_id'], $inter_id, $business );
                            $go_url = Soma_const_url::inst()->get_url('*/consumer/shipping_detail', array('id'=> $inter_id, 'spid'=>$shippingIdArr['shipping_id'] ) );
                        }
                    }else{
                        //没有消费，就剩下赠送了，使用parent_id，openid_origin＝openid 来判断
                        $filter = array();
                        $filter['inter_id'] = $inter_id;
                        $filter['parent_id'] = $items[0]['item_id'];
                        $filter['openid_origin'] = array( $this->openid );
                        $gift_items = $this->assetItemModel->get_order_items_by_filter($inter_id, $filter);
                        if( count( $gift_items ) > 0 ){
                            $go_url = Soma_const_url::inst()->get_url( '*/*/get_received_list', array( 'id'=>$inter_id, 'gid'=>$gift_items[0]['gift_id'] ) );
                        }
                    }
                }elseif( $giftNum > 1 ){
                    if( $consumerItems[0]['status'] == $ConsumerItemModel::STATUS_ITEM_PENDING )
                    {
                        $go_url = Soma_const_url::inst()->get_url('*/gift/package_detail', array( 'id'=> $inter_id, 'gid'=>$gift_id ) );
                    } else {
                        $go_url = Soma_const_url::inst()->get_url('*/consumer/my_shipping_list', array( 'id'=> $inter_id ) );
                    }
                }

            }

            //异步查询是否关注链接
            $check_follow_ajax = Soma_const_url::inst()->get_url( '*/*/check_follow_ajax', array('id'=>$this->inter_id) );
            //邮寄
            $mailParam = array();
            $mailParam['gid'] = $gift_id;
            $mailParam['bsn'] = $business;
            $mail_url = Soma_const_url::inst()->get_soma_shipping($mailParam);

            //送朋友
            $send_friend = Soma_const_url::inst()->get_url( '*/*/package_send', 
            	array(
            		'id'=>$this->inter_id,
            		'group'=>Soma_base::STATUS_TRUE,
            		'aiid'=>$items[0]['item_id'],
            		'bsn'=>$business,
            		'send_from' => $giftModel::SEND_FROM_GIFT,
            		'send_order_id' => $gift_id,
            	)
            );

            //到店用券 跳转到赠送详情
            // $usage_url = Soma_const_url::inst()->get_url('*/consumer/package_usage', array('aiid'=>$items[0]['item_id'], 'aiidi'=>0, 'id'=>$this->inter_id,'bsn'=>$business ) );
            $usage_url = Soma_const_url::inst()->get_url('*/*/package_detail', 
                array(
                    'gid'=>$gift_id,
                    'id'=>$this->inter_id,
                    'bsn'=>$business,
                    'send_from' => $giftModel::SEND_FROM_GIFT,
                    'send_order_id' => $gift_id,
                )
            );

            //现在定房
            $booking_url = Soma_const_url::inst()->get_url('*/booking/wx_select_hotel',
                array(
                    'aiid'=>$items[0]['item_id'],
                    'oid'=>$items[0]['order_id'],
                    'aiidi'=>0,  //TODO 不确定这个值是什么意思
                    'id'=>$inter_id,
                    'bsn'=>$business
                )
            );
            //http://jfk.iwide.cn/index.php/soma/booking/wx_select_hotel?aiid=86195&oid=1000133025&aiidi=0&id=a429262688&bsn=package


            $this->load->model('soma/Product_package_model', 'ProductPackageModel');

            $fans_received= $this->publics_model->get_fans_info( $giftModel->m_get('openid_give') );

            $this->load->helper('soma/package');
            $this->datas = array(
                'model'=> $giftModel,
                'origin_gid'=> $gift_id,
                'items'=> $items,
                'item_ids'=> array_values($item_ids),
                'item_model'=> $this->assetItemModel,
                //'js_menu_show'=> $js_menu_show, //不显示分享
                'js_share_config'=> $share_config,
                'send_pertime'=> $this->send_pertime,
                'check_follow_ajax'=> $check_follow_ajax,
                'mail_url'=> $mail_url,
                'send_friend'=> $send_friend,
                'usage_url'=> $usage_url,
                'go_url'=> $go_url,
                'themeConfig'=> $this->themeConfig,
                'fans_received'=> $fans_received,
                'productionPackageModel' => $this->ProductPackageModel,
                'booking_url' => $booking_url
            );

            // 双语化翻译
            if($this->langDir == self::LANG_DIR_EN)
            {
                // var_dump($items);exit;
                foreach($this->datas['items'] as $key => $item)
                {
                    if(!empty($item['name_en']))
                    {
                        $this->datas['items'][$key]['name'] = $item['name_en'];
                    }
                }
            }

            $this->_view("header",$header);
            $this->_view("package_received", $this->datas);
            
        }
    }

    //查看还有谁领取了礼物
    public function have_get_received()
    {
        $business= 'package';
        $inter_id= $this->inter_id;
        $openid= $this->openid;
        $gift_id= $this->input->get('gid');

        $header = array(
            'title' => $this->lang->line('get_gift_list')
        );

        $this->load->model('soma/Gift_order_model','giftOrderModel');
        $this->load->model('soma/Asset_item_package_model','assetItemModel');
        $giftOrderModel = $this->giftOrderModel->load( $gift_id );
        if( !$giftOrderModel ){
            Soma_base::inst()->show_exception('未找到该赠送编号');
            
        } else {
            $gift_data= $giftOrderModel->m_data();
        }

        $receive_list = array();
        if( $gift_data['is_p2p']== Soma_base::STATUS_TRUE ){
            if( isset( $gift_data['openid_received'] ) ) {
                $this->load->model('wx/Publics_model');
                $fans_received= $this->Publics_model->get_fans_info( $gift_data['openid_received'] );
                $gift_data['openid_received_nickname'] = isset( $fans_received['nickname'] ) ? $fans_received['nickname'] : '他/她的好友';
                $gift_data['openid_received_headimg'] = isset( $fans_received['headimgurl'] ) ? $fans_received['headimgurl'] : base_url('public/soma/images/ucenter_headimg.jpg');
            }
            
        } else {
            $receive_list= $giftOrderModel->get_receiver_list($inter_id, $gift_id );
            if( count( $receive_list ) > 0 ){

                $openids= array();
                foreach ($receive_list as $k=>$v){
                    $openids[]= $v['openid'];
                }
                $this->load->model('wx/Publics_model');
                $openid_data= $this->Publics_model->get_fans_info_byIds($openids);
                $openid_hash= $giftOrderModel->array_to_hash($openid_data, 'nickname', 'openid');
                $headimg_hash= $giftOrderModel->array_to_hash($openid_data, 'headimgurl', 'openid');
                foreach ($receive_list as $k=>$v){
                    //填充openid昵称
                    if( array_key_exists($v['openid'], $openid_hash )){
                        $receive_list[$k]['openid_nickname']= $openid_hash[$v['openid']];
                    }else{
                        $receive_list[$k]['openid_nickname']='他/她的好友';
                    }

                    if( array_key_exists($v['openid'], $headimg_hash )) {
                        $receive_list[$k]['openid_headimg']= $headimg_hash[$v['openid']];
                    }else{
                        $receive_list[$k]['openid_headimg']=base_url('public/soma/images/ucenter_headimg.jpg');
                    }
                }
            }
        }

        $items = $giftOrderModel->get_order_items($business, $inter_id);
        $item = isset( $items[0] ) ? $items[0] : array();

        //获取推荐位
        $uri = 'soma_gift_package_detail';
        $block = $this->get_page_block( $uri );

        $this->datas = array(
            'gift_model'=> $giftOrderModel,
            'item'=> $item,
            'gift_data'=> $gift_data,
            'receive_list'=> $receive_list,
            'block'=> $block,
        );

        if($this->langDir == self::LANG_DIR_EN)
        {
            if(!empty($this->datas['item']['name_en']))
            {
                $this->datas['items']['name'] = $this->datas['item']['name_en'];
            }
        }

        $this->_view("header",$header);
        $this->_view("have_get_received", $this->datas);
    }

    /**
     * 判断标准：单个人送单个人，一次领完的赠送方式判断
     */
    public function _received()
    {
        //获取微信用户的信息
        $fans= $this->_get_wx_userinfo();
        $business= 'package';
        $header = array(
            'title' => $this->lang->line('open_gift'),
        );
        $this->load->model('soma/Gift_order_model','giftOrderModel');
        $inter_id= $this->inter_id;
        $gift_id= $this->input->get('gid');
        $gift_received_status = $this->giftOrderModel->can_recevie_status();//可以接受礼物的状态
        // $gift_array= $this->giftOrderModel->get_data_filter( array('gift_id'=>$gift_id, 'status'=>$gift_received_status ) );
        $gift_array= $this->giftOrderModel->get_data_filter( array('gift_id'=>$gift_id ) );

        if( count($gift_array)==0 ){
            Soma_base::inst()->show_exception($this->lang->line('gift_num_not_found'));
            
        } else {
            $gift_data= $gift_array[0];
        }

        $this->load->model('wx/publics_model');
        $fans= $this->publics_model->get_fans_info( $gift_data['openid_give'] );
        $this->datas['fans'] = $fans;

        // 针对所有情况，添加一个数据
        $this->datas['gift_data'] = $gift_data;
        $this->datas['gift_model'] = $this->giftOrderModel;

        if( $this->openid== $gift_array[0]['openid_give'] ){
            //针对赠送本人
            //print_r($gift_data);die;
            $url= Soma_const_url::inst()->get_url('*/*/get_received_list', array('id'=> $this->inter_id,'gid'=>$gift_id,'bsn'=>$business ) );
            redirect( $url );exit();
        }

        if( $gift_data['is_p2p']== Soma_base::STATUS_TRUE ){
            //对于个人对个人，已经领过的情况
            if($gift_array[0]['openid_received'] && $this->openid == $gift_array[0]['openid_received']){
                $this->datas = array(
                    'business'=> $business,
                    'gift_data'=> $gift_data,
                );
                return TRUE;
            }

            //20160905 luguihong 礼物接收完显示接受人列表
            if( isset( $gift_data['openid_received'] ) ) {
                $this->load->model('wx/Publics_model');
                $fans_received= $this->Publics_model->get_fans_info( $gift_data['openid_received'] );
                $gift_data['openid_received_nickname'] = isset( $fans_received['nickname'] ) ? $fans_received['nickname'] : $this->lang->line('their_friend');
                $gift_data['openid_received_headimg'] = isset( $fans_received['headimgurl'] ) ? $fans_received['headimgurl'] : base_url('public/soma/images/ucenter_headimg.jpg');
                $this->datas['gift_data'] = $gift_data;
            }
            
        } else {
            //对于群发，已经领过的情况
            $receive_list= $this->giftOrderModel->get_receiver_list($inter_id, $gift_id );
            $openids= $this->giftOrderModel->array_to_hash($receive_list, 'openid');
            $my_receiver= array();
            if( in_array($this->openid, $openids) ){
                foreach ($receive_list as $k=> $v){
                    if($v['openid']==$this->openid) $my_receiver= $v;
                }
                $this->datas = array(
                    'business'=> $business,
                    'gift_data'=> $gift_data,
                    'my_receiver'=> $my_receiver,
                );
                return TRUE;
            }

            //20160905 luguihong 礼物接收完显示接受人列表
            if( count( $receive_list ) > 0 ){

                $openids= array();
                foreach ($receive_list as $k=>$v){
                    $openids[]= $v['openid'];
                }
                $this->load->model('wx/Publics_model');
                $openid_data= $this->Publics_model->get_fans_info_byIds($openids);
                $openid_hash= $this->giftOrderModel->array_to_hash($openid_data, 'nickname', 'openid');
                $headimg_hash= $this->giftOrderModel->array_to_hash($openid_data, 'headimgurl', 'openid');
                foreach ($receive_list as $k=>$v){
                    //填充openid昵称
                    if( array_key_exists($v['openid'], $openid_hash )){
                        $receive_list[$k]['openid_nickname']= $openid_hash[$v['openid']];
                    }else{
                        $receive_list[$k]['openid_nickname']=$this->lang->line('their_friend');
                    }

                    if( array_key_exists($v['openid'], $headimg_hash )) {
                        $receive_list[$k]['openid_headimg']= $headimg_hash[$v['openid']];
                    }else{
                        $receive_list[$k]['openid_headimg']=base_url('public/soma/images/ucenter_headimg.jpg');
                    }
                }
            }
            $this->datas['receive_list'] = $receive_list;
        }    

        //获取推荐位
        $uri = 'soma_gift_package_detail';
        $block = $this->get_page_block( $uri );
        $this->datas['block'] = $block;

        // if( $gift_data['status']== Gift_order_model::STATUS_RECEIVED || $gift_data['status']== Gift_order_model::STATUS_TIMEOUT ){
        //     //不能再领取：1，已经领完；2，自己已经领过；3, 礼物退回
        //     //echo 'I am late.';die;
        //     $this->_view("header", $header);
        //     $this->_view("empty", $this->datas);
        //     return FALSE;
        //     //$url= Soma_const_url::inst()->get_pacakge_home_page( array('id'=> $this->inter_id ) );
        //     //redirect( $url );
        // }
        if( !in_array( $gift_data['status'], $gift_received_status ) ){
            //不能再领取：1，已经领完；2，自己已经领过；3, 礼物退回
            //echo 'I am late.';die;
            $items = $this->giftOrderModel->load( $gift_id )->get_order_items($business, $inter_id);
            $this->datas['item'] = isset( $items[0] ) ? $items[0] : array();
            // var_dump( $items );die;
            $this->_view("header", $header);
            $this->_view("empty", $this->datas);
            return FALSE;
            //$url= Soma_const_url::inst()->get_pacakge_home_page( array('id'=> $this->inter_id ) );
            //redirect( $url );
        }

        //接受礼物动作标志位
        $giftReceived = $this->input->get('grd');

        //抢购指标
        if( $gift_data['is_p2p']== Soma_base::STATUS_FALSE ){

            /************************以下为接受礼物动作 20160817 luguihong 关于点击礼盒，才算接受礼物问题修改 *****************************/
            //这里处理的作用是，是否还有礼物没有接受的，没有礼物了就提示用户
            //还有赠送数量，并且点击了礼盒动作，才分配token
            
            $receive_count = count( $receive_list );
            if( $gift_data['total_qty'] == $receive_count ){
                //没有数量
                $this->_view("header", $header);
                $this->_view("empty", $this->datas);
                return FALSE;
            }elseif( $gift_data['total_qty'] > $receive_count ){
                //还有数量

                //如果没有标志位，先不获取token
                if( $giftReceived == Soma_base::STATUS_TRUE ){
                    //群发检测是否还有配额
                    $token= $this->giftOrderModel->get_redis_token( $inter_id, $gift_id);
                    if( !$token ){
                        $this->_view("header", $header);
                        $this->_view("empty", $this->datas);
                        return FALSE;
                    }
                }

            }else{
                //超出数量
                $this->_view("header", $header);
                $this->_view("empty", $this->datas);
                return FALSE;
            }

            /************************以下为接受礼物动作 20160817 luguihong 关于点击礼盒，才算接受礼物问题修改 *****************************/

        } else {
            $token= TRUE;//array( 'gift_id'=> $gift_id, 'qty' => 1, 'token' => '000000' );
        }

        //echo 'this is my gift.';die;
        $business= $gift_data['business'];
        
        //还没有人领过，成功领取，显示打开礼盒
        //echo 'I can get the gift.';die;

        $sign= Soma_base::inst()->str_decrypt($this->input->get('sign'), TRUE);
        if( $gift_id != $sign ){
            Soma_base::inst()->show_exception($this->lang->line('share_link_error_tip'));
        }

        /***********************以下为接受礼物动作 20160817 luguihong 关于点击礼盒，才算接受礼物问题修改 ****************************/

        if( $giftReceived == Soma_base::STATUS_TRUE ){

            $gift= $this->giftOrderModel->load($gift_data['gift_id']);
            $gift_requirement= $gift->get_requirement($business, $this->inter_id);  //array( asset_item_id=>qty_require )

            $this->load->model('soma/Asset_item_package_model', 'somaAssetItemModel');
            $somaAssetItemModel = $this->somaAssetItemModel;
            $items= $somaAssetItemModel->get_order_items_byItemids( array_keys($gift_requirement), $business, $this->inter_id);
            //$gift_desc= '';
            $orderIds = array();
            foreach ($items as $k=>$v){
                if( array_key_exists($v['item_id'], $gift_requirement) ) {
                    $items[$k]['qty_require']= $gift_requirement[$v['item_id']];
                    //$gift_desc.= $v['name'];  //组合出模板消息的礼物描述
                    $orderIds[] = $v['order_id'];
                }
            }
            $gift->received_item= $items;
            //print_r($items);die;
            
            if( $gift_data['is_p2p']== Soma_base::STATUS_FALSE ) {
                //$per_give= $token['qty'];  //群发中的数量
                $per_give= $gift_data['per_give'];  //群发中的数量
                $count_give= $gift_data['count_give'];  //群发中的数量
                $gift->rule= new Gift_order_attr_rule($per_give, $count_give);
            }
            $gift->sender= new Gift_order_attr_customer( $gift_data['openid_give'] );
            $gift->received= new Gift_order_attr_customer( $this->openid );
            $received_result = $gift->order_received($business, $this->inter_id);

            
            /***********************发送模版消息****************************/
            //发送模版消息
            $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
            $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
            
            $type = $MessageWxtempTemplateModel::TEMPLATE_GIFT_RECEIVED;// 礼物被领取
            $openid = $gift->m_get('openid_give');
            $fans_received= $this->publics_model->get_fans_info( $this->openid );
            $gift->nickname = isset( $fans_received['nickname'] ) ? $fans_received['nickname'] : $this->lang->line('your_friends'); // 领取人昵称
            $inter_id = $this->inter_id;//公众号
            $business = 'package';

            $MessageWxtempTemplateModel->send_template_by_gift_success( $type, $gift, $openid, $inter_id, $business);
            /***********************发送模版消息****************************/
            
            //判断赠送人是否为购买人，是购买人那么赠送人肯定不是二次赠送的
            if( isset( $items[0] ) && empty( $items[0]['gift_id'] ) ){
                /**
                 * 接收成功赠送会员礼包，只限购买人。要判断赠送人是不是购买人，只能通过接收人资产里面的parent_id是不是等于购买人的资产ID
                 * @author      luguihong    2017/02/23
                 * @param       array        items              赠送人的资产明细
                 */
                $this->load->model('soma/Config_member_package_model','somaConfigMemberModel');
                $somaConfigMemberModel = $this->somaConfigMemberModel;
                $memberRecordData[] = array(
                                'inter_id'      => $inter_id, 
                                'openid'        => $items[0]['openid'], 
                                'send_id'       => $gift_id, 
                                'product_id'    => $items[0]['product_id'], 
                                'num'           => $gift_data['per_give'], 
                                'type'          => $somaConfigMemberModel::TYPE_RECEIVED_SUCCESS,
                                'create_time'   => date('Y-m-d H:i:s'),
                                'status'        => $somaConfigMemberModel::RECORD_STATUS_PENDING,
                            );
                $somaConfigMemberModel->insert_record( $gift, $inter_id, $memberRecordData );

            }

            if( $received_result ){
                //这里为0的意思是，已经接受了，数量减少在前，判断在后，所以为0
                $gift->change_order_consumer_status( $gift, $inter_id, $business, $orderIds, 0 );
                return TRUE;
            }
        }

        /***********************以上为接受礼物动作 20160817 luguihong 关于点击礼盒，才算接受礼物问题修改 ****************************/

        //index.php/soma/gift/package_received?id=a450089706&bsn=package&sign=cjZQUWJ1TWY1Tkp4Mnc1OFd2eDF2UT09&gid=1000000869&from=singlemessage&isappinstalled=0';
        $redirect_param= array(
            'id'=> $this->inter_id,
            'gid'=> $this->input->get('gid'),
            'sign'=> $this->input->get('sign'),
            'bsn'=> $this->input->get('bsn'),
            'grd'=> Soma_base::STATUS_TRUE,//gift_received标记位，点击拆礼盒触发
        );
        $this->datas = array(
            'fans'=> $fans,
            'gift_data'=> $gift_data,
            'current_url'=> Soma_const_url::inst()->get_url('*/*/*', $redirect_param ),
        );
        $this->_view("header", $header);
        $this->_view("received", $this->datas);
        return FALSE;
    }

    
    public function _received_bak()
    {
        $this->load->model('soma/Gift_order_model','giftOrderModel');
        $gift_id= $this->input->get('gid');
        //$gift_array= $this->giftOrderModel->get_data_filter( array('gift_id'=>$gift_id, 'status'=> $this->giftOrderModel->can_recevie_status()) );
        $gift_array= $this->giftOrderModel->get_data_filter( array('gift_id'=>$gift_id ) );
        if( count($gift_array)==0 ){
            Soma_base::inst()->show_exception('未找到该赠送编号');
        }

        //获取推荐位
        $uri = 'soma_gift_package_detail';
        $block = $this->get_page_block( $uri );
        $this->datas['block'] = $block;

        if( $this->openid== $gift_array[0]['openid_give'] ){
            //针对赠送本人
            //print_r($gift_data);die;
            $url= Soma_const_url::inst()->get_url('*/*/package_list_send', array('id'=> $this->inter_id ) );
            redirect( $url );
    
        } else if( $gift_array[0]['openid_received'] && $this->openid != $gift_array[0]['openid_received'] ){
            //对于已被其他人领取
            //echo 'I am late.';die;
            $this->_view("header", $header);
            $this->_view("empty", $this->datas);
            return FALSE;
            //$url= Soma_const_url::inst()->get_pacakge_home_page( array('id'=> $this->inter_id ) );
            //redirect( $url );
    
        } else {
            //echo 'this is my gift.';die;
            $gift_data= $gift_array[0];
            $business= $gift_data['business'];
            if( empty($gift_data['openid_received']) ){
                //还没有人领过，成功领取，显示打开礼盒
                //echo 'I can get the gift.';die;
                $header = array(
                    'title' => '打开礼物',
                );
    
                $sign= Soma_base::inst()->str_decrypt($this->input->get('sign'), TRUE);
                if( $gift_id != $sign ){
                    Soma_base::inst()->show_exception('接受分享链接签名错误！');
                }
    
                //获取微信用户的信息
                $fans= $this->_get_wx_userinfo();
    
                $gift= $this->giftOrderModel->load($gift_data['gift_id']);
                $gift_requirement= $gift->get_requirement($business, $this->inter_id);  //array( asset_item_id=>qty_require )
    
                $this->load->model('soma/Asset_item_package_model', 'assetItemModel');
                $items= $this->assetItemModel->get_order_items_byItemids( array_keys($gift_requirement), $business, $this->inter_id);
                $gift_desc= '';
                foreach ($items as $k=>$v){
                    if( array_key_exists($v['item_id'], $gift_requirement) ) {
                        $items[$k]['qty_require']= $gift_requirement[$v['item_id']];
                        $gift_desc.= $v['name'];  //组合出模板消息的礼物描述
                    }
                }
                $gift->received_item= $items;
                //print_r($items);die;
    
                $gift->sender= new Gift_order_attr_customer( $gift_data['openid_give'] );
                $gift->received= new Gift_order_attr_customer( $this->openid );
                $gift->order_received($business, $this->inter_id);
    
    
                /***********************发送模版消息****************************/
                //发送模版消息
                $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
                $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
    
                $openid = $gift->m_get('openid_give');//发送给当初赠送人
                $inter_id = $this->inter_id;//公众号
                //模版测试使用
                //$openid = 'o9Vbtw3ELLZaarxtyw5UXV_MexFk';
                //$inter_id = 'a450089706';
                $type = $MessageWxtempTemplateModel::TEMPLATE_GIFT_RECEIVED;//礼物被领取
                $templateInfo = $MessageWxtempTemplateModel->get_template_detail_byType( $type, $inter_id );
                if( $templateInfo ){
                    $template_id = $templateInfo['template_id'];
                    $array = array();
                    $array['username'] = $fans['nickname'];//$username;//礼物领取人
                    $array['name'] = $gift_desc;//$name;//礼物名称
                    $array['time'] = date( 'Y-d-d H:i:s', time() );//$time;//领取时间
                    $sort_array = $MessageWxtempTemplateModel->get_template_send_sort();
                    $array['sort'] = $sort_array[$type];
    
                    $createInfo = $MessageWxtempTemplateModel->create_template_message( $openid, $template_id, $type, $array, $inter_id, $business );
                    if( isset( $createInfo['status'] ) && $createInfo['status'] == 1 ){
                        //方式一：保存到队列里
                        $MessageWxtempTemplateModel->save_template_message( $createInfo['data'], $inter_id );
    
                        //方式二：立即发送模版消息
                        // $MessageWxtempTemplateModel->save_template_record( $createInfo, $inter_id );
                    }
                }
                /***********************发送模版消息****************************/
    
    
                $this->load->model('wx/publics_model');
                $fans= $this->publics_model->get_fans_info( $gift_data['openid_give'] );
                //index.php/soma/gift/package_received?id=a450089706&bsn=package&sign=cjZQUWJ1TWY1Tkp4Mnc1OFd2eDF2UT09&gid=1000000869&from=singlemessage&isappinstalled=0';
                $redirect_param= array(
                    'id'=> $this->inter_id,
                    'gid'=> $this->input->get('gid'),
                    'sign'=> $this->input->get('sign'),
                    'bsn'=> $this->input->get('bsn'),
                );
                $this->datas = array(
                    'fans'=> $fans,
                    'gift_data'=> $gift_data,
                    'current_url'=> Soma_const_url::inst()->get_url('*/*/*', $redirect_param ),
                );
                $this->_view("header", $header);
                $this->_view("received", $this->datas);
                return FALSE;
    
            } else {
                //本人已经领取过
                //echo 'I got the gift.';die;
                $this->datas = array(
                    'business'=> $business,
                    'gift_data'=> $gift_data,
                );
                return TRUE;
            }
        }
    }
    
    /**
     * 处理分享链接，集中处理转换 saler_id，未完成
     */
    public function sharing_received()
    {
        $r = $this->input->get('r');
        $sender = $this->input->get('s');
        $gid = $this->input->get('gid');
        $sign = $this->input->get('sign');
        if( $r ){
            $redirect= urldecode($r);
            
        } else {
            $redirect= Soma_const_url::inst()->get_url('*/package/index', array('id'=> $this->inter_id ));
        }
        
        //$url= Soma_const_url::inst()->get_url('soma/gift/sharing_received', array('id'=>'a1234123','saler'=>'111'));
        //$url= urlencode($url);
        //$redirect= urldecode('http%3A%2F%2Ftf.iwide.cn%2Findex.php%2Fsoma%2Fgift%2Fsharing_received%3Fid%3Da450089706%26saler%3D111');
        
        /* 修改分销员信息获取, 统一从分销接口获取
        $this->load->model('distribute/Staff_model');
        $staff= $this->Staff_model->get_my_base_info_openid( $this->inter_id, $this->openid );
        if( $staff && $staff['qrcode_id'] ){
            //当接收人是分销员
            $redirect.= "&saler={$staff['qrcode_id']}";
            
        } else {
            $staff= $this->Staff_model->get_my_base_info_openid( $this->inter_id, urldecode($sender) );
            if( $staff && $staff['qrcode_id'] ){
                //当分享人是分销员
                $redirect.= "&saler={$staff['qrcode_id']}";
            
            } else {
                //分享前就带有分销ID
                $saler_id= $this->input->get('saler');
                if($saler_id) $redirect.= "&saler={$saler_id}";
            }
        }
        */

        // 注：利用URL参数同名后面覆盖前面，小心转发多次出现url链接过长
        // 另：
        // 1.酒店分销员和泛分销员不能同时出现，以酒店分销员优先
        // 2.两个分销员交叉分享时以接收人的分销身份为准
        
        // 清空分享链接中的酒店分销员和泛分销员信息,清空泛分销静默授权信息
        $redirect .= '&saler=&fans_saler=&rel_res=';

        $this->load->library('Soma/Api_idistribute');
        $api = new Api_idistribute();
        $self_saler = $api->get_saler_info($this->inter_id, $this->openid);

        $giveDistributeValue = '';
        $salerId        = $this->input->get('saler');
        $fansSalerId    = $this->input->get('fans_saler');

        // 接收人不是分销员，查看分享人是否是分销员
        $share_saler = $this->api_idistribute->get_saler_info($this->inter_id, urldecode($sender));

        if($self_saler) {
            // 接收人自身是分销员，替换原来的分销员信息
            /**
             * 20170503 luguihogn 如果接受人是泛分销员，不是分销员，但是链接带有分销号或者泛分销号，那么链接应该带上这个号
             */
            if( $self_saler['typ'] == 'STAFF' )
            {
                $redirect .= '&saler=' . $self_saler['info']['saler'];
                $giveDistributeValue = $self_saler['info']['saler'];
            } elseif ($self_saler['typ'] == 'FANS') {
                if($share_saler) {
                    $param_key = 'saler';
                    if($share_saler['typ'] == 'FANS') { $param_key = 'fans_saler'; }
                    $redirect .= '&' . $param_key . '=' . $share_saler['info']['saler'];
                    $giveDistributeValue = $share_saler['info']['saler'];
                } else {
                    if( $salerId )
                    {
                        $redirect .= "&saler={$salerId}";
                        $giveDistributeValue = $salerId;
                    } else {
                        if( $fansSalerId ) {
                            $redirect .= "&fans_saler={$fansSalerId}";
                            $giveDistributeValue = $fansSalerId;
                        } else {
                            //自己是泛分销员，买了不算绩效
                            $redirect .= "&fans_saler={$self_saler['info']['saler']}";
                        }
                    }
                }
            }
        } else {
            if($share_saler) {
                $param_key = 'saler';
                if($share_saler['typ'] == 'FANS') { $param_key = 'fans_saler'; }
                $redirect .= '&' . $param_key . '=' . $share_saler['info']['saler'];
                $giveDistributeValue = $share_saler['info']['saler'];
            } else {
                // 都不是分销员，查看分享前是否有分销员信息
                if( $salerId )
                {
                    $redirect .= "&saler={$salerId}";
                    $giveDistributeValue = $salerId;
                } else {
                    if( $fansSalerId )
                    {
                        $redirect .= "&fans_saler={$fansSalerId}";
                        $giveDistributeValue = $fansSalerId;
                    }
                }
            }
        }

        $this->session->set_userdata( 'giveDistribute'.$this->inter_id.$this->openid, $giveDistributeValue );
        if($gid) $redirect.= "&gid={$gid}";
        if($sign) $redirect.= "&sign={$sign}";
        if($this->inter_id) $redirect.= "&id={$this->inter_id}";
        redirect($redirect);
    }
    
    //展示为以后的皮肤做扩展
    protected function _view($file, $datas=array() )
    {
//        parent::_view('gift'. DS. $file, $datas);
        parent::_view('gift'. DS. $file, $datas);
    }
    
    /**
     * 接受礼物页面异步检测关注情况
     */
    public function check_follow_ajax()
    {
        $this->load->model('wx/publics_model');
        $userinfo= $this->publics_model->get_wxuser_info($this->inter_id, $this->openid );
        $result= array(
            'status'=> Soma_base::STATUS_TRUE,
            'message'=> '',
            'data'=> '',
        );
        $public= $this->publics_model->get_public_by_id($this->inter_id);
        $statis_url= $public['follow_page'];
        
        if( isset($userinfo['subscribe']) && $userinfo['subscribe']==0 ){
            //微信返回的信息显示没有关注
            $result['status']= 2;
            $result['message']= str_replace('[0]', $public['name'], $this->lang->line('follow_use'));
            $result['data']= $statis_url;
    
        } else {
            $result['message']= str_replace('[0]', $public['name'], $this->lang->line('use_gift_in_officaial_account_tip'));
            $result['data']= Soma_const_url::inst()->get_pacakge_home_page();
        }
        echo json_encode($result);
    }

    /**
     * 获取礼品回退页面上的查看回退礼品信息URL
     * @return string   json字符串：{success:true,redirect_url:"http://xxxx"}
     */
    public function gift_return_ajax() {
    	$inter_id = $this->inter_id;
    	$gid = $this->input->get('gid',true);
    	$this->load->model('soma/Gift_order_model', 'g_model');
    	$this->g_model->load($gid);
    	if(!$this->g_model) {
    		echo json_encode(array('success'=>false, 'msg' => '没有找到礼品信息'));
    	}

    	$send_from = $this->g_model->m_get('send_from');
    	$send_order_id = $this->g_model->m_get('send_order_id');
    	$business = $this->g_model->m_get('business');

    	// 默认跳转到订单中心
    	$redirect_url = Soma_const_url::inst()->get_url('*/order/order_detail', array('id' => $inter_id, 'oid'=> $send_order_id, 'bsn' => $business));
    	if($send_from == Gift_order_model::SEND_FROM_GIFT) {
    		$redirect_url = Soma_const_url::inst()->get_url('*/gift/package_received', array('id' => $inter_id, 'gid'=> $send_order_id, 'sign' => ''));
    	}

    	echo json_encode(array('success' => true, 'redirect_url' => $redirect_url));
    }
    
    /**
     * 套票接受保存电话，联系人
     * @deprecated 目前已经去掉此功能
     */
    public function package_received_ajax()
    {
        $data= $this->input->post('contact');
        $data['gift_id']= Soma_base::inst()->str_decrypt($data['gift_id']);
        $data['openid']= $this->openid;
        $data['create_time']= date('Y-m-d H:i:s');
        if( !empty($data['mobile']) ){
            //$data['status']= '';
            $this->load->model('soma/Gift_order_model','giftOrderModel');
            $result= $this->giftOrderModel->save_customer_contact($data, array('openid'=> $this->openid ) );
        }
        $url= Soma_const_url::inst()->get_url('*/gift/package_detail', array(
            'gid'=> $data['gift_id'],
            'id'=> $this->inter_id,
            'bsn'=> 'package')
        );
        redirect($url);
    }
    

    //测试用---------------------debug 上线前删除-----------------------------------------------------------------
    /* public function testing()
    {
        $this->load->model('soma/Gift_order_model','giftOrderModel');
        $gift_data= $this->giftOrderModel->load('1000000834');
        $fans= $this->_get_wx_userinfo();
        $this->datas = array(
           'fans'=> $fans,
           'gift_data'=> $gift_data,
        );
        $this->_view("header", $header);
        $this->_view("received", $this->datas);
        
    } */
    
    public function testredis()
    {
        $inter_id= 'a450089706';
        $gift_id= '1000000933';
        $this->load->model('soma/Gift_order_model', 'giftOrderModel');
        $model= $this->giftOrderModel->load($gift_id);
        if($model){
            $model->rule= new Gift_order_attr_rule(2, 2);
            $model->set_redis_list($inter_id, $gift_id);
            
            echo $model->get_redis_token($inter_id, $gift_id);
            
        } else {
            echo 'x';
        }
    }





    /**************************************************** 以下为前后端分离新增的action **************************************************/

    /**
     * @return bool
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function gift_new_router(){

        $redis = $this->get_redis_instance();
        $layout = $redis->get('layout');
        $tkId = $redis->get('tkid');
        $brandName = $redis->get('brandname');

        //初始化数据库分片配置
        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $this->db_shard_config = $this->model_shard_config->build_shard_config($this->inter_id);
        $gift_id= $this->input->get('gid');
        $business = "package";
        $this->load->model('soma/Gift_order_model','giftOrderModel');
        $gift_received_status = $this->giftOrderModel->can_recevie_status();//可以接受礼物的状态
        $gift_array= $this->giftOrderModel->get_data_filter( array('gift_id'=>$gift_id ) );
        $receive_list = array();
        if( count($gift_array)==0 ){
            show_404();
        } else {
            $gift_data= $gift_array[0];
        }


        /***************自己送出去的礼物****************/
        if( $this->openid== $gift_data['openid_give'] ){
            //针对赠送本人
            $url= Soma_const_url::inst()->get_url('*/*/get_received_list', array(
                'id'=> $this->inter_id,'gid'=>$gift_id,'bsn'=>$business,
                'tkid' => $tkId,
                'brandname' => $brandName,
                'layout' => $layout
            ) );
            redirect( $url );exit();
        }

        /***************自己领取过的的礼物****************/
        if( $gift_data['is_p2p']== Soma_base::STATUS_TRUE ){
            //对于个人对个人，已经领过的情况
            if($gift_data['openid_received'] && $this->openid == $gift_data['openid_received']){
                //$url= Soma_const_url::inst()->get_url('*/*/received_gift_detail', array('id'=> $this->inter_id,'gid'=>$gift_id,'bsn'=>$business ) );
                //redirect( $url );exit();
                $this->datas = array();
                $this->headerDatas['title'] = $this->lang->line('received_gift');
                $this->_view("package_received", $this->datas);
                return TRUE;
            }
        } else {
            //对于群发，已经领过的情况
            $receive_list= $this->giftOrderModel->get_receiver_list($this->inter_id, $gift_id );
            $openids= $this->giftOrderModel->array_to_hash($receive_list, 'openid');
            if( in_array($this->openid, $openids) ){
                //$url= Soma_const_url::inst()->get_url('*/*/received_gift_detail', array('id'=> $this->inter_id,'gid'=>$gift_id,'bsn'=>$business ) );
//                redirect( $url );exit();
                $this->datas = array();
                $this->headerDatas['title'] = $this->lang->line('received_gift');
                $this->_view("package_received", $this->datas);
                return TRUE;
            }


        }

        if( !in_array( $gift_data['status'], $gift_received_status ) ){
            //不能再领取：1，已经领完；2，自己已经领过；3, 礼物退回
            $url= Soma_const_url::inst()->get_url('*/*/received_gift_empty', array(
                'id'=> $this->inter_id,'gid'=>$gift_id,'bsn'=>$business ,
                'tkid' => $tkId,
                'brandname' => $brandName,
                'layout' => $layout
            ) );
            redirect( $url );exit();
        }


        /*********可以领取的礼物***********/
        $validation_of_order = \App\services\soma\PresentsService::validation_of_received_status( $this->inter_id , $gift_id, $gift_data ,$receive_list);
        if(!$validation_of_order){  //领取人数已超
            $url= Soma_const_url::inst()->get_url('*/*/received_gift_empty', array(
                'id'=> $this->inter_id,'gid'=>$gift_id,'bsn'=>$business,
                'tkid' => $tkId,
                'brandname' => $brandName,
                'layout' => $layout
            ) );
            redirect( $url );exit();
        }else{
            $this->datas = array();
            $this->headerDatas['title'] = $this->lang->line('open_gift');
            $this->_view("received", $this->datas);
            return TRUE;
        }

    }

    /**
     * @author zhangyi  <zhangyi@mofly.cn>
     * 礼物领取已经失效：不能再领取：1，已经领完；2，自己已经领过；3, 礼物退回
     */
    public function  received_gift_empty(){
        $this->datas = array();
        $this->_view("empty", $this->datas);
        return TRUE;
    }

    /**
     * @author zhangyi  <zhangyi@mofly.cn>
     * 礼物详情
     */
    public function received_gift_detail(){

        $this->datas = array();
        $this->_view("package_received", $this->datas);
        return TRUE;
    }

    /**************************************************** 以上为前后端分离新增的action **************************************************/
}
