<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Killsec extends MY_Front_Soma_Wxapp {

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

    //展示为以后的皮肤做扩展
    protected function _view($file, $datas=array() )
    {
//        parent::_view('package'. DS. $file, $datas);
        parent::_view( 'package'. DS. $file, $datas);
    }

    /**
     * 获取当前openid的分销员ID
     */
    protected function _get_saler_id($inter_id, $openid)
    {   
        $this->load->library('Soma/Api_idistribute');
        $api = new Api_idistribute();
        return $api->get_saler_info($inter_id, $openid);
        /*
        $this->load->model('distribute/Staff_model');
        $staff= $this->Staff_model->get_my_base_info_openid( $inter_id, $openid );
        if( $staff && $staff['qrcode_id'] ){
            return $staff['qrcode_id'];
        } else {
            return FALSE;
        }
        */
    }

    /* 兼容函数 */
    public function killsec_pay()
    {
        $this->package_pay();
    }
    public function killsec_detail()
    {
        $this->package_detail();
    }
    public function package_detail()
    {
        $uparams= $this->input->get();
        $url= Soma_const_url::inst()->get_url('*/package/package_detail', $uparams );
    }

    public function find_killsec_stock_ajax()
    {
        $return= array('status'=>Soma_base::STATUS_FALSE, 'total'=>1, 'stock'=>1, 'percent'=>'100%');
    
        $actId = $this->input->post('act_id');
        $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
        $killsec= $this->activityKillsecModel->get_aviliable_activity( array('act_id'=>$actId ) );
        if($killsec){
            $killsec= $killsec[0];
            $ks_model= $this->activityKillsecModel;
            $instance= $ks_model->get_aviliable_instance( array(
                'act_id'=>$killsec['act_id'], 'status'=> array_keys($ks_model->get_instance_status())
            ) );
            if( !isset($instance[0]) ){
                //没有任何活动实例，适用于新活动
                $ks_count = $killsec['killsec_count'];
                $ks_stock = $killsec['killsec_count'];
                
            } else {
                if( $instance[0]['status']==$ks_model::INSTANCE_STATUS_GOING ){
                    //活动进行中的库存显示
                    $cache= $this->_load_cache();
                    $redis= $cache->redis->redis_instance();
                    $key= $this->activityKillsecModel->redis_token_key($instance[0]['instance_id']);
                    $ks_stock = $redis->lSize($key);
                
                } elseif( $instance[0]['status']==$ks_model::INSTANCE_STATUS_PREVIEW ){
                    //活动开始半小时内的库存显示
                    $ks_stock = $killsec['killsec_count'];
                
                } elseif( $instance[0]['close_time']< date('Y-m-d H:i:s') ){
                    //活动上一轮结束 - 开始半小时前的库存显示
                    $ks_stock = $killsec['killsec_count'];
                
                } else {
                    //活动卖光的库存显示
                    $ks_stock = 0;
                }
                $ks_count = $killsec['killsec_count'];
            }
            $ks_stock= ($ks_stock>= $ks_count)? $ks_count: $ks_stock;
            $ks_percent= round($ks_stock / $ks_count, 2) * 100;
            $return= array('status'=>Soma_base::STATUS_TRUE, 'total'=>$ks_count, 'stock'=>$ks_stock, 'percent'=> $ks_percent );
        }
        echo json_encode( $return );
    }
    
    public function subscribe_killsec_notice_ajax()
    {
        $return= array('status'=> Soma_base::STATUS_FALSE, 'data'=>array(), 'message'=> '找不到活动信息。' );
        $actId = $this->input->post('act_id');
        
        $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
        $activity= $this->activityKillsecModel->get_aviliable_activity( array('act_id'=>$actId ) );
        
        if( count($activity)>0 ){
            $activity= $activity[0];
            if( isset($activity['status']) && $activity['status']==Activity_killsec_model::STATUS_TRUE ){
                if( $activity['killsec_time']< date('Y-m-d H:i:s', strtotime('+15 minute')) ){
                    //已经超过订阅时间
                    $return['message']= '已超过订阅时间！';
                    
                } else {
                    $inter_id= $this->inter_id;
                    $openid= $this->openid;
                    $data= array(
                        'act_id'=> $actId,
                        'openid'=> $openid,
                        'inter_id'=> $activity['inter_id'],
                        'product_id'=> $activity['product_id'],
                        'product_name'=> $activity['product_name'],
                        'killsec_price'=> $activity['killsec_price'],
                        'killsec_time'=> $activity['killsec_time'],
                    );
                    $result= $this->activityKillsecModel->save_waiting_notice_list($inter_id, $data);
                    if($result){
                        $return['status']= Soma_base::STATUS_TRUE;
                        $return['message']= '订阅成功';
                    
                    } else {
                        $return['message']= '订阅失败';
                    }
                }
            }
        }
        echo json_encode( $return );
    }
    
    //获取秒杀token
    public function get_killsec_token_ajax()
    {
        $return = array('status'=> Soma_base::STATUS_FALSE, 'data'=>array(), 'message'=> '活动尚未开始，敬请期待。' );
        $actId = $this->input->post('act_id');
        if(!$actId) $actId = $this->input->get('act_id');

        if($actId){
            $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
            $instance= $this->activityKillsecModel->get_aviliable_instance( array('act_id'=>$actId ) );
            if(isset($instance[0]) ) $instance= $instance[0];

            if( isset($instance['status']) && $instance['status']==Activity_killsec_model::INSTANCE_STATUS_PREVIEW ){
                //token发放之前做提醒
                $return['message']= '活动尚未开始，敬请期待。';
                if( $this->input->get('prev')=='t' ) {
                    $in_blacklist = $this->activityKillsecModel->set_redis_white_user($instance['instance_id'], $this->openid );
                }elseif( $this->input->get('prev')=='f' ) {
                    $in_blacklist = $this->activityKillsecModel->set_redis_white_user($instance['instance_id'], $this->openid, TRUE );
                }
                
            } elseif( isset($instance['instance_id']) && $instance['instance_id'] ){
                $instance_id = $instance['instance_id'];
                
                $now_time = date('Y-m-d H:i:s');
                if(strtotime($now_time) < strtotime($instance['start_time'])) {
                    $return['message']= '活动尚未开始，敬请期待。';
                    // echo json_encode( $return ); exit;
                    $this->out_put_msg(1, '', $return); exit;
                }

                //检查是否处于黑名单
                $in_blacklist = $this->activityKillsecModel->get_redis_black_user($instance_id, $this->openid);
                    
                //检查是否已经购买过
                $join_data = $this->activityKillsecModel->get_redis_order_user($instance_id, $this->openid);
                
                if( $in_blacklist ){
                    $return['message'] = '当前参加人数过多，请耐心等候。';
                    
                } elseif( $join_data ){
                    $return['message'] = '秒杀活动数量有限，请勿重复参加。';
                    
                } else {
                    //未成功购买过 开始-------------------
                    $token = $this->activityKillsecModel->get_redis_token($instance_id, $this->openid);
                    
                    if( $token ){
                        $return['data']= array(
                            'instance_id'=> $instance_id,
                        );
                        if($token== intval($token)){
                            $return['data']['token']= $token;
                    
                            $insert_user= array(
                                'instance_id'=> $instance_id,
                                'inter_id'=> $this->inter_id,
                                'business'=> 'package',
                                'token'=> $token,
                                'act_id'=> $actId,
                                'openid'=> $this->openid,
                                'join_time'=> date('Y-m-d H:i:s'),
                                'remote_ip'=> $this->input->ip_address(),
                                'status'=> Activity_killsec_model::USER_STATUS_JOIN,
                            );
                            $this->activityKillsecModel->save_instance_user($this->inter_id, $insert_user);
                    
                        } else {
                            //从缓存中得到token
                            $return['data']['token']= $token['token'];
                        }
                    
                        $return['status']= Soma_base::STATUS_TRUE;
                        //$return['message']= '还有机会，未支付订单将会逐步释放 。';
                        $return['message']= '手慢了，抢到的客人正在付款，未支付订单将持续释放';
                    
                    } else {
                        $return['message']= '手慢了，抢到的客人正在付款，未支付订单将持续释放';
                    }
                    //未成功购买过 结束-------------------
                }
                
            } else {
                $return['message']= '活动已结束。';
            }
            
        } else {
            $return['message']= '参数错误，请联系客服。';
        }
        $this->out_put_msg(1, '', $return);
        // echo json_encode( $return );
    }

    /**
     *  套票支付
     *  eg: index.php/soma/killsec/package_pay?id=a450089706&act_id=10337&pid=10029&instance_id=15&token=936930
     */
    public function package_pay()
    {
        $productId = intval($this->input->get('pid'));
        
        $act_id = intval($this->input->get('act_id'));
        $instance_id= $this->input->get('instance_id');
        $token= $this->input->get('token');
        //print_r($this->input->get());die;

        $back_url= Soma_const_url::inst()->get_url('*/package/package_detail',
            array('id'=> $this->inter_id, 'pid'=> $productId)
        );
        
        if( !$productId || !$instance_id || !$act_id || !$token ){
            //缺少关键参数，跳回原来页面
            redirect($back_url);die;
        }

        $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
        $killsec= $this->activityKillsecModel->find( array('act_id'=> $act_id, 'inter_id'=> $this->inter_id) );
        $this->datas['killsec']= $killsec;
        
        $cache= $this->_load_cache();
	    //$cache->redis->select_db(Activity_killsec_model::REDIS_DB);  //由redis.php 配置文件自动识别哪个库
	    $redis= $cache->redis->redis_instance();
        $cache_key= $this->activityKillsecModel->redis_token_key($instance_id, 'cache');
        if( ! $redis->hExists($cache_key, $this->openid) ){
            //此openid未得到授权，跳回原来页面
            redirect($back_url);die;
        }
        $cache_hash= (array) json_decode($redis->hGet($cache_key, $this->openid));
        if( !isset( $cache_hash['token']) || $token != $cache_hash['token'] ){
            //校验得到token是否伪造？，跳回原来页面
            redirect($back_url);die;
        }

        $cache_hash['create_at_ms'] = strtotime($cache_hash['create_at']) * 1000;
        
        $this->load->model('soma/Product_package_model','productPackageModel');
        $productDetail =  $this->productPackageModel
            ->get_product_package_detail_by_product_id($killsec['product_id'],$this->inter_id);
        if( !$productDetail ){
            //获取商品信息为空，跳回原来页面
            redirect($back_url);die;
        }

        $header = array(
            'title' => '购买支付'
        );

        $productModel = $this->productPackageModel;
        $is_expire = FALSE;
        if( $productDetail['date_type'] == $productModel::DATE_TYPE_STATIC ){
          $time = time();
          $expireTime = isset( $productDetail['expiration_date'] ) ? strtotime( $productDetail['expiration_date'] ) : NULL;
          if( $expireTime && $expireTime < $time ){
            //商品已经过期，跳回原来页面
            $is_expire = TRUE;
            redirect($back_url);die;
          }
        }
        $this->datas['is_expire']= $is_expire;

        //点击分享之后开启这些按钮
        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline' );
        $uparams= $this->input->get()+ array('id'=> $this->inter_id);

        //取出分享配置
        $this->load->model( 'soma/Share_config_model', 'ShareConfigModel' );
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list( $position, $this->inter_id );
        $share_config = array(
            'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : '发现一家好去处，快点开看看',
            'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : '优惠不等人',
            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/package/package_detail', $uparams ),
            'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : base_url('public/soma/images/sharing_package.png'),
        );

        //取出联系人和电话
        $filter = array();
        $filter['openid'] = $this->openid;
        $customer_info = $this->productPackageModel->get_customer_contact( $filter );
        $this->datas['customer_info']= $customer_info;
        // var_dump( $customer_contact );exit;
        
/** 读取购买人的可用券 ********************************/
//         $this->load->library('Soma/Api_member');
//         $api= new Api_member($this->inter_id);
// 	    $result= $api->get_token();
// 	    $api->set_token($result['data']);
//         $result= $api->conpon_sign_list($this->openid);
//         $this->datas['coupons'] = $result['data'];
/**  ***********************/

        $this->load->helper('soma/time_calculate');
        $this->datas['packageModel'] =  $this->productPackageModel;
        $this->datas['package'] = $productDetail;
        $this->datas['cache_hash'] = $cache_hash;
        $this->datas['js_menu_show']= $js_menu_show;
        $this->datas['js_share_config']= $share_config;

/** 检测 自己saler_id ********************************/
        // 修改个人分销信息获取 fengzhongcheng
        // $saler_id= $this->_get_saler_id( $this->inter_id, $this->openid );
        // if($saler_id) $this->datas['saler_self'] = $saler_id;

        $saler_info= $this->_get_saler_id( $this->inter_id, $this->openid );
        if($saler_info) {
          $data_key = 'saler_self';
          if($saler_info['typ'] == 'FANS') {
            $data_key = 'fans_saler_self';
          }
          $this->datas[$data_key] = $saler_info['info']['saler'];
        }
/**  ***********************/
        
        // $this->_view("header",$header);
         
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
        $this->datas['tips_list'] = $tips_list; 
        
        $this->_view("killsec_pay",$this->datas);
    }

    /**
     * 处理ajax请求，生成订单，已经移到 Order->get_order_id_by_ajax()
     */
    public function get_order_id_by_ajax()
    {
        //很抱歉，您的订单超时未确认，已被取消
    }

    
    #  以下为测试方法    ######################################################
    
    public function index()
    {
        $instance_id= 11;
        $count= 10;
	    $key= "SOMA_KILLSEC_TOKEN_{$instance_id}";
        $cache= $this->_load_cache();
        
        $cache->redis->select_db(2);
        $redis= $cache->redis->redis_instance();
        
        $this->load->helper('soma/math');
        $token_array= gen_unique_rand(100000, 999999, $count);
        
        foreach ($token_array as $k=>$v){
            $redis->lPush($key, $v );
        }
        $redis->expireAt($key, time()+ 3600);
        
    }
}
