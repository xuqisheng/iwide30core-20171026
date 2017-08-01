<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Soma_api extends MY_Front_Soma {

	function __construct() {
	    include_once APPPATH."config/zb_config.php";
        $_GET ['scope'] = "snsapi_userinfo";
        $_GET ['id'] = ZB_INTER_ID;
		parent::__construct ();
		$this->source = json_decode ( file_get_contents ( 'php://input' ), TRUE );
	}
	
	protected function get_source($index = '', $filter = '', $in = TRUE) {
	    if ($index === '')
	        return $this->source;
	    if ($in)
	        $data = isset ( $this->source ['send_data'] [$index] ) ? $this->source ['send_data'] [$index] : NULL;
	    else
	        $data = isset ( $this->source [$index] ) ? $this->source [$index] : NULL;
	    if (isset ( $data ) && ! empty ( $filter )) {
	        switch ($filter) {
	            case 'int' :
	                $data = intval ( $data );
	                break;
	            default :
	                break;
	        }
	    }
	    return $data;
	}
	
	
	
	/**
	 * 套票详情页页面
	 * 复合[default|groupon|killsec]三种类型页面判断
	 */
	public function package_detail()
	{
	    $this->load->model('soma/Product_package_model','productPackageModel');
	    $this->load->model('soma/Activity_groupon_model','grouponModel');
	    
	    $productId = intval($this->get_source('pid'));
	    $inter_id = $this->get_source('inter_id');
	    $channel_id = intval($this->get_source('channel_id'));
	    
	  /*    $productId = 12394;
	    $inter_id = 'a450089706';
	      */
	    $this->datas = array();
	
	    if(empty($productId)){
	        return '';
	    }
	
	    //获取推荐位
	    $uri = 'soma_package_package_detail';
	    $block = $this->get_page_block( $uri );
	
	    $productDetail =  $this->productPackageModel
	    ->get_product_package_detail_by_product_id($productId,$inter_id);
	    if( !$productDetail ){
	        //查找不出来，就是商品下架了
	        //添加false条件，秒杀更新不涉及到快照功能，先屏蔽，2016-11-4 10:37:11，2016年11月7日11:08:02已重新开启
	        $header = array(
	            'title'   => '商品下架',
	        );
	        $this->_view("header",$header);
	        $this->_view("offline",array('block'=>$block));
	    }else{
	
	        $productGallery = $this->productPackageModel
	        ->get_gallery_front( $productId, $inter_id );
	
	        $groupons = $this->grouponModel->groupon_list($productId);
	        if( $groupons && count( $groupons ) > 1 ){
	            $groupons[0] = array_pop($groupons);
	        }
	
	        $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
	        $killsec = $this->activityKillsecModel->killsec_by_product_id($productId,$inter_id);
	        if( is_array($killsec) && count( $killsec ) > 1 ){
	            $killsec['killsec_time_ms']= strtotime($killsec['killsec_time']) * 1000;
	            $killsec['end_time_ms']= strtotime($killsec['end_time']) * 1000;
	        }
	
	        //查找出公众号名
	        $this->load->model( 'wx/Publics_model' );
	        $publics = $this->Publics_model->get_public_by_id($productDetail['inter_id']);
	        if( $publics ){
	            $inter_id_name = $publics['name'];
	        }else{
	            $inter_id_name = '';
	        }
	
	        //点击分享之后开启这些按钮
	        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl' );
	        $uparams= $this->input->get()+ array('id'=> $inter_id);
	        $share_config = array(
	            'title'=> isset( $productDetail['name'] ) && !empty( $productDetail['name'] ) ? $productDetail['name'] : '发现一家好去处，快点开看看',//商品的标题
	            // 'desc'=> isset( $productDetail['hotel_name'] ) && !empty( $productDetail['hotel_name'] ) ? $productDetail['hotel_name'].'精品推荐' : '优惠不等人',//酒店名+精品推荐
	            'desc'=> isset( $inter_id_name ) && !empty( $inter_id_name ) ? $inter_id_name.'精品推荐' : '优惠不等人',//酒店名+精品推荐
	            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/*/*', $uparams ),
	            'imgUrl'=> isset( $productDetail['face_img'] ) && !empty( $productDetail['face_img'] ) ? $productDetail['face_img'] : base_url('public/soma/images/sharing_package.png'),//商品的logo
	        );
	
	        $header = array(
	            'title'   => $productDetail['name']
	        );
	        //$this->_view("header",$header);
	
	        //做过期处理过滤
	        $productModel = $this->productPackageModel;
	        $is_expire = FALSE;
	        if( $productDetail['date_type'] == $productModel::DATE_TYPE_STATIC ){
	            $time = time();
	            $expireTime = isset( $productDetail['expiration_date'] ) ? strtotime( $productDetail['expiration_date'] ) : NULL;
	            if( $expireTime && $expireTime < $time ){
	                $is_expire = TRUE;
	                //添加false条件，秒杀更新不涉及到快照功能，先屏蔽，2016-11-4 10:37:11，2016年11月7日11:08:02已重新开启
	                //商品已过期，就是商品下架了
	                $header = array(
	                    'title'   => '商品下架',
	                );
	                $this->_view("header",$header);
	                $this->_view("offline",array('block'=>$block));
	                die;
	            }
	        }
	        $this->datas['is_expire']= $is_expire;
	
	        // 如果加载不到，显示信息驿站
	        $this->load->model('wx/publics_model');
	        $public_info = $publics;
	        if(!isset($public_info['name'])){ $public_info['name'] = ''; }
	        $this->datas['public'] = $public_info;
	
	        /** 秒杀结束判断标记 */
	        $finish_killsec= FALSE;
	        $ks_model= $this->activityKillsecModel;
	        if( $killsec ){
	            //if( $killsec && $killsec['schedule_type']== $ks_model::SCHEDULE_TYPE_CYC ){
	            $instance= $ks_model->get_aviliable_instance( array('act_id'=>$killsec['act_id'], 'status'=>$ks_model::INSTANCE_STATUS_FINISH ) );
	            if( isset($instance[0]) && $instance[0]['close_time']> date('Y-m-d H:i:s') ){
	                $finish_killsec= TRUE;  //离秒杀开始时间超过半小时，显示秒杀已经结束
	            }
	        }
	
	        /** 秒杀库存计算(已改为 ajax更新) */
	        if( false && $killsec ){
	            //             $instance= $ks_model->get_aviliable_instance( array('act_id'=>$killsec['act_id'], 'status < '=>$ks_model::INSTANCE_STATUS_FINISH ) );
	            //             if( isset($instance[0]) && $instance[0]['status']==$ks_model::INSTANCE_STATUS_GOING ){
	            //                 $cache= $this->_load_cache();
	            //                 $redis= $cache->redis->redis_instance();
	            //                 $key= $this->activityKillsecModel->redis_token_key($instance[0]['instance_id']);
	            //                 $ks_stock = $redis->lSize($key);
	            //                 $ks_count = $instance[0]['killsec_count'];
	
	            //             } else {
	            //                 $ks_count = $killsec['killsec_count'];
	            //                 $ks_stock = $killsec['killsec_count'];
	            //             }
	            //             $ks_percent= round($ks_stock / $ks_count, 2);
	            //             $this->datas['ks_stock'] = $ks_stock;
	            //             $this->datas['ks_count'] = $ks_count;
	            //             $this->datas['ks_percent'] = ( $ks_percent>1? 1: $ks_percent ) * 100;
	        }
	        //秒杀库存刷新频率
	        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
	            $this->datas['stock_reflesh_rate'] = 60000;
	        } else {
	            $this->datas['stock_reflesh_rate'] = 10000;
	        }
	
	        /** 促销规则加载 */
	        $this->load->model('soma/Sales_rule_model');
	        $auto_rule= $this->Sales_rule_model->get_product_rule(array($productId), $inter_id, 'auto_rule');
	        $auto_rule_new = array();
	        if( $auto_rule && count( $auto_rule ) > 0 ){
	            foreach( $auto_rule as $v ){
	                $auto_rule_new[] = $v;
	            }
	        }
	        $this->datas['auto_rule'] = $auto_rule_new;
	
	        /** 对秒杀开始时间进行处理 */
	        // 不用减少一分钟，导致前端显示有误
	        // if($killsec) $killsec['killsec_time']= date('Y-m-d H:i:s', strtotime($killsec['killsec_time'])- Activity_killsec_model::PRESTART_TIME );
	
	        $this->datas['gallery'] = $productGallery;
	        $this->datas['packageModel'] =  $this->productPackageModel;
	        $this->datas['package'] = $productDetail;
	        $this->datas['groupons'] = $groupons;   //拼团
	        $this->datas['killsec']  = $killsec;    //秒杀
	        $this->datas['finish_killsec']  = $finish_killsec;    //秒杀
	        $this->datas['js_menu_show']= $js_menu_show;
	        $this->datas['js_share_config']= $share_config;
	        $this->datas['block']= $block;
	
	        $package = $this->datas['package'];
	        $packageModel = $this->productPackageModel;
	        $tips_list = array();
	        if($package['can_refund'] == $packageModel::CAN_T && $package['type'] != $packageModel::PRODUCT_TYPE_BALANCE){
	             
	            $oj = array();
	            $oj['tips'] = "购买后，您可以在订单中心直接申请退款，并原路退回";
	            $oj['text'] = "微信退款";
	            $tips_list[] = $oj;
	             
	        }
	        if($package['can_gift'] == $packageModel::CAN_T){
	
	            $oj = array();
	            $oj['tips'] = "该商品购买成功后，可微信转赠给好友，好友可继续使用";
	            $oj['text'] = "赠送朋友";
	            $tips_list[] = $oj;
	
	        }
	        if($package['can_mail'] == $packageModel::CAN_T){
	
	            $oj = array();
	            $oj['tips'] = "这件商品，是可以邮寄的商品哟";
	            $oj['text'] = "邮寄到家";
	            $tips_list[] = $oj;
	
	        }
	        if($package['can_pickup'] == $packageModel::CAN_T){
	
	            $oj = array();
	            $oj['tips'] = "此商品支持您到店使用／自提";
	            $oj['text'] = "到店自提";
	            $tips_list[] = $oj;
	
	        }
	        if($package['can_invoice'] == $packageModel::CAN_T){
	
	            $oj = array();
	            $oj['tips'] = "此商品购买成功后，您可以提交发票信息开票";
	            $oj['text'] = "开具发票";
	            $tips_list[] = $oj;
	
	        }
	        if($package['can_split_use'] == $packageModel::CAN_T){
	
	            $oj = array();
	            $oj['tips'] = "此商品分时可用";
	            $oj['text'] = "分时可用";
	            $tips_list[] = $oj;
	
	        }
	
	        if( isset($this->datas['finish_killsec']) && $this->datas['finish_killsec'] ){
	            $this->datas['killsec_state_name'] = "本轮秒杀已结束";
	        }else{
	            $this->datas['killsec_state_name'] = "秒杀进行中";
	        }
	
	        $this->datas['package']['compose'] = unserialize($this->datas['package']['compose']);
	        if(!empty($this->datas['package']['compose'])){
	            $this->datas['package']['show_compose'] = true;
	        }
	        //暂关闭商品内容
	        $this->datas['package']['show_compose'] = false;
	        $this->datas['package']['order_notice'] = strip_tags($this->datas['package']['order_notice']);
	
	        //将img转成image标签
	        //$this->datas['package']['img_detail'] = str_replace("<img","<image>",$this->datas['package']['img_detail']);
	        //$this->datas['package']['img_detail'] = str_replace("/>","></image>",$this->datas['package']['img_detail']);
	        $reg = '/<img +src=[\'"](http.*?)[\'"]/i';
	        preg_match_all( $reg , $this->datas['package']['img_detail'] , $matches );
	        $this->datas['package']['img_detail'] = $matches[1];
	
	        // 将<br/><Br/>替换成/n
	        $replace = array(
	            "<br>", "<Br>", "<br/>", "<Br/>", "<br />", "<Br />",
	            "&lt;br&gt;", "&lt;Br&gt;", "&lt;br/&gt;", "&lt;Br/&gt;", "&lt;br /&gt;", "&lt;Br /&gt;",
	            "&#60;br&#62;", "&#60;Br&#62;", "&#60;br/&#62;", "&#60;Br/&#62;", "&#60;br /&#62;", "&#60;Br /&#62;",
	        );
	        $this->datas['package']['order_notice'] = str_replace($replace, "\n", $this->datas['package']['order_notice']);
	        $this->datas['package']['order_notice'] = str_replace("&nbsp;", ' ', $this->datas['package']['order_notice']);
	        $this->datas['package']['img_detail'] = str_replace($replace, "\n", $this->datas['package']['img_detail']);
	        $this->datas['package']['img_detail'] = str_replace("&nbsp;", ' ', $this->datas['package']['img_detail']);
	
	        //购买按钮显示
	        $finish_killsec = $this->datas['finish_killsec'];
	        $killsec = $this->datas['killsec'];
	        $package = $this->datas['package'];
	        $packageModel = $this->datas['packageModel'];
	        $groupons = $this->datas['groupons'];
	        if( isset($finish_killsec) && $finish_killsec ){
	            $btn_name = $killsec['killsec_price']."已售馨";
	            $btn_disable = false;
	            $btn_event = "";
	        }elseif( isset($killsec) && !empty($killsec)){
	            $temp = ($package['type'] != $packageModel::PRODUCT_TYPE_BALANCE) ? "秒杀购买" : "储值秒杀";
	            $btn_name = "¥{$killsec['killsec_price']}".$temp;
	            $btn_disable = true;
	            $btn_event = "killsec";
	        } elseif( !empty($groupons) && !$this->datas['is_expire'] ){
	             
	            foreach($groupons as $k=>$v){
	
	                $btn_name = "¥{$v['group_price']} | ".$v['group_count']."人团";
	                $btn_disable = true;
	                $btn_event = "tuan";
	                break;
	            }
	             
	        }elseif( isset($this->datas['auto_rule'][0]) ){
	            $btn_name = "团购特惠";
	            $btn_disable = true;
	            $btn_event = "tuan";
	        }else{
	            $btn_name = "";
	            $btn_disable = false;
	            $btn_event = "tuan";
	        }
	
	        if( $this->datas['is_expire'] ){
	            $btn_2_name = "已过期";
	            $btn_2_disable = false;
	            $btn_2_event = "";
	        }else{
	            $btn_2_name = "¥{$package['price_package']} ";
	            $temp = ($package['type'] != $packageModel::PRODUCT_TYPE_BALANCE) ? "立即购买" : "储值购买";
	            $btn_2_name .= $temp;
	            $btn_2_disable = true;
	            $btn_2_event = "buy";
	        }
	
	        $this->datas['btn_1_name'] = $btn_name;
	        $this->datas['btn_1_disable'] = $btn_disable;
	        $this->datas['btn_1_event'] = $btn_event;
	
	        $this->datas['btn_2_name'] = $btn_2_name;
	        $this->datas['btn_2_disable'] = $btn_2_disable;
	        $this->datas['btn_2_event'] = $btn_2_event;
	
	        $this->datas['tips_list'] = $tips_list;
	
	        //cdn处理
	        foreach($this->datas['gallery'] as $key=>$d){
	            $this->datas['gallery'][$key]['gry_url'] = $this->_replace_cdn_url($this->datas['gallery'][$key]['gry_url']);
	             
	        }
	        $this->datas['package']['img_detail'] = $this->_replace_cdn_url($this->datas['package']['img_detail']);
	         
	        $this->load->model ( 'livebc/Common_model' );
	        
	        //$zburl = urlencode( $_SERVER['HTTP_REFERER'] );
	        $zburl = urlencode($this->getOrderFinishUrl());
	        	        
	        $this->datas['buy_url'] = $this->getUrlByPidInterId($productId, $inter_id)."&zbcode={$this->openid}&channelid={$channel_id}&zburl={$zburl}";
	        
	        $this->Common_model->out_put_msg(1,'',$this->datas);
	        //$this->_view("package_detail",$this->datas);
	    }
	}
	
	private function getUrlByPidInterId($pid,$inter_id){
	    
	    $this->load->model('wx/publics_model');
	    $public_info= $this->publics_model->get_public_by_id( $inter_id );
	    
	    $domain = $public_info['domain'];
	    
	    $url = "http://{$domain}/index.php/soma/package/package_pay?pid={$pid}&id={$inter_id}";
	    
	    return $url;
	    
	    
	}
	
	private function getOrderFinishUrl(){
	    
	    $this->load->model('wx/publics_model');
	    
	    $public_info= $this->publics_model->get_public_by_id( $this->inter_id );
	     
	    $domain = $public_info['domain'];
	    
	    $url = "http://{$domain}/index.php/zb/zb/success_buy";
	    return $url;
	    
	}
	
	
	function _view($file, $datas=array()){
	    
	    $this->load->model ( 'livebc/Common_model' );
	    if(strpos($file, "header")){
	        return;
	    }
	    if(strpos($file, "footer")){
	        return;
	    }
	    
	    $this->Common_model->out_put_msg(1,'',$datas,$file);
	    
	}
}
