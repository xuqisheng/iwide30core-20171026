<?php
class Api_member extends Soma_base
{
    protected $_debug= FALSE;
    //protected $_debug= TRUE;
    protected $_cache;
    protected $_token;
    protected $_server;
    protected $_inter_id;
    protected $_module= 'soma';
    protected $_params= array();
    //protected $_url;

    public function __construct($inter_id=NULL, $module=NULL)
    {
        //会员接口地址设定
        if( ENVIRONMENT === 'production' ){
            $this->_server = 'http://member.iwide.cn/';
        } else {
            $this->_server = 'http://vip.iwide.cn/';
        }
        $this->_inter_id= $inter_id;
        if( $module ) $this->_module= $module;
        $this->_params['inter_id']= $this->_inter_id;
        $this->_params['module']= $this->_module;
        return parent::__construct();
    }

    /**
     * Sets the inter identifier.
     * 
     * 防止批量操作时多次实例化这个对象
     * 
     * @param      <type>  $inter_id  The inter identifier
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function set_inter_id($inter_id) {
        $this->_inter_id = $inter_id;
        $this->_params['inter_id'] = $inter_id;
        return $this;
    }

    /**
     * 把请求/返回记录记入文件
     * @param String $content
     * @param string $type
     */
    protected function _write_log( $content, $type='request', $file=NULL )
    {
        if($file==NULL) $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'soma'. DS. 'member'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $path. $file, 'a');
    
        $content= str_repeat('-', 40). "\n[". $type. ' : '. date('Y-m-d H:i:s'). ' : '. $ip. ']'
            . "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }
    /**
     * 请求数据
     * @param String $url
     * @param unknown $params
     * @param unknown $extra
     * @param number $timeout
     * @return mixed
     */
    protected function _post_request($url, $params, $extra= array(), $timeout= 10)
    {
        if( empty($this->_inter_id) ) $this->show_exception('Inter_id can not be empty!');

        if( $this->_debug && isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']!='production' ){
            echo "Debug Request:"; var_dump($params);
        }

        // 增加接口uucode复杂性
        if (isset($params['uu_code'])) {
            $params['uu_code'] = time() . rand(0,10000) . $params['uu_code'];
        }
        
        $this->_write_log( json_encode($params, JSON_UNESCAPED_UNICODE), 'request:'. $url );
        
        $requestString = http_build_query( $params, false );
        //echo $requestString;die;
        
        $con = curl_init( (string) $url );
        curl_setopt( $con, CURLOPT_HEADER, false );
        curl_setopt( $con, CURLOPT_POSTFIELDS, $requestString );
        curl_setopt( $con, CURLOPT_POST, true );
        curl_setopt( $con, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $con, CURLOPT_TIMEOUT, ( int ) $timeout );
        curl_setopt( $con, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $con, CURLOPT_SSL_VERIFYHOST, 0 );
        
        if (! empty ( $extra ) && is_array ( $extra )) {
            $headers = array ();
            foreach ( $extra as $opt => $value ) {
                if (strexists( $opt, 'CURLOPT_' )) {
                    curl_setopt( $con, constant ( $opt ), $value );
                } elseif (is_numeric ( $opt )) {
                    curl_setopt( $con, $opt, $value );
                } else {
                    $headers[] = "{$opt}: {$value}";
                }
            }
            if (! empty ( $headers )) {
                curl_setopt( $con, CURLOPT_HTTPHEADER, $headers );
            }
        }
        $result= curl_exec($con);
        //var_dump(curl_error($con));
        return $result;
    }
    /**
     * 处理返回数据
     * @param String $result
     * @return array
     */
    protected function _handle_result($result)
    {

        $CI = &get_instance();

        $this->_write_log($result, 'response');
        
        $result = (array) json_decode( $result );
        
        if( $this->_debug && isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']!='production' ){
            echo "Debug Response:"; var_dump($result);
        }
        
        $is_silent= TRUE;
        if( isset($result['err']) && isset($result['msg']) && $result['err']==1000 ){
            $this->show_exception('API-ERROR：Token expired！', $is_silent);
            
        } elseif( isset($result['err']) && isset($result['msg']) && $result['err']==1026 ){
            $this->show_exception( $CI->lang->line('stord_fail_tip') .'：'. $result['msg'], $is_silent);

        } elseif( empty($result) || (isset($result['err']) && $result['err']==123456) ){
            //Redis server went away
            $this->_write_log( '', 'response', 'redis_went_away.txt' );
            $this->show_exception($CI->lang->line('use_ponint_coupon_fail_tip'), $is_silent);
             
        } elseif( isset($result['err']) && isset($result['msg']) && $result['err']> 0 ){
            $this->show_exception('API-EXCEPTION：'. $result['msg'], $is_silent);
             
        } else {
            //此处可能返回{err:0;msg:ok},或者对应的数据，直接返回函数体进行处理
            return $result;
        }
    }
    /**
     * 请求Token后设置Token
     * @param String $token
     * @return Api_member
     */
    public function set_token($token)
    {
        $this->_token= $token;
        $this->_params['token']= $this->_token;
        return $this;
    }
    public function set_debug($debug)
    {
        $this->_debug= $debug;
        return $this;
    }
    
    protected function _load_cache( $name='Cache' )
    {
        if(!$name || $name=='cache') //不能为小写cache
            $name='Cache';
        $CI= & get_instance();
        $CI->load->driver('cache',
            array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'soma_'),
            $name
        );
        return $CI->$name;
    }
    
    /**
     * Gets the redis instance.
     *
     * @param      string      $select  The select
     *
     * @return     Redis|null  The redis instance.
     */
    public function get_redis_instance($select = 'soma_redis') {
        $CI = & get_instance();
        $CI->load->library('Redis_selector');
        if($redis = $CI->redis_selector->get_soma_redis($select)) {
            return $redis;
        }
        return null;
    }

    /**
     * 请求token
     * @return Ambigous <multitype:, array>
     */
    public function get_token()
    {
//         $cache= $this->_load_cache();
//         $this->_cache= $cache->redis->redis_instance();
//         $cache_key= 'SOMA:_TOKEN_VIP';
//         $token= $this->_cache->get($cache_key);
//         if($token){
//             $result['data']= $token;
//         } else{
            $url= $this->_server. 'api/accesstoken/get';
            $param= array('id'=>'soma', 'secret'=>'iwide30soma' );
            $result= $this->_post_request($url, $param);
            $result= $this->_handle_result($result);
            
//             if( isset($result['data']) )
//                 $this->_cache->setex($cache_key, 3600*10, $result['data']);
//         }
        return $result;
    } 
    
    //优惠券接口群##################################################
    /** <?php if($card['card_type']==1) {?>¥<font><?php echo $card['reduce_cost']; ?></font>
        <?php }elseif($card['card_type']==2){ ?>折<font><?php echo $card['discount']; ?></font>
        <?php }elseif($card['card_type']==3){ ?>
        <?php }elseif($card['card_type']==4){ ?>¥<font><?php echo $card['money']; ?></font>
        <?php }else{ ?><?php } ?>
     */

    /** 领取的优惠券详细信息 */
    public function conpon_sign_info($m_card_id, $openid)
    {
        $url= $this->_server. 'api/membercard/getinfo';
        $this->_params['member_card_id']= $m_card_id;
        $this->_params['openid']= $openid;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }
    /** 领取的优惠券列表信息 */
    public function conpon_sign_list( $openid, $num=100 )
    {
        $url= $this->_server. 'api/membercard/getlist';
        $this->_params['openid']= $openid;
        $this->_params['num']= $num;  //优惠券的数量
        //unset($this->_params['module']);
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }
    /** 锁定/使用一张优惠券 */
    public function conpon_useone($m_card_id, $openid, $order_id='', $deduction='', $order_payment='')
    {
        $url= $this->_server. 'api/membercard/useone';
        $this->_params['member_card_id']= $m_card_id;
        $this->_params['openid']= $openid;
        //$this->_params['scene']= '';
        //$this->_params['remark']= '';
        $this->_params['order_id']= $order_id;
        $this->_params['deduction']= $deduction;//优惠券抵扣金额
        $this->_params['order_payment']= $order_payment;//支付的订单金额
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }
    /** 核销一张优惠券 */
    public function conpon_consume($m_card_id, $openid)
    {
        $url= $this->_server. 'api/membercard/useoff';
        $this->_params['member_card_id']= $m_card_id;
        $this->_params['openid']= $openid;
        //$this->_params['scene']= '';
        //$this->_params['remark']= '';
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }
    /** 回滚优惠券（用于退款情况）  */
    public function conpon_rollback($m_card_id, $openid)
    {
        $url= $this->_server. 'api/membercard/rollback';
        $this->_params['member_card_id']= $m_card_id;
        $this->_params['openid']= $openid;
        //$this->_params['scene']= '';
        //$this->_params['remark']= '';
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    /** 酒店所有的优惠券列表 */
    public function conpon_all()
    {
        $url= $this->_server. 'api/intercard/getlist';
        //$this->_params['module']= '';
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }
    /** 某个酒店优惠券详情 */
    public function conpon_info($card_id)
    {
        $url= $this->_server. 'api/intercard/getinfo';
        $this->_params['card_id']= $card_id;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }
    /** 领取/获取优惠券 */
    public function conpon_sign($card_id, $openid, $uu_code)
    {
        $url= $this->_server. 'api/intercard/receive';
        $this->_params['card_id']= $card_id;
        $this->_params['uu_code']= $uu_code;
        $this->_params['openid']= $openid;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }
    
/** 转增优惠券   */
public function conpon_gift($m_card_id, $openid_send, $openid_receive)
{
    $url= $this->_server. 'api/membercard/rollback';
    $this->_params['member_card_id']= $m_card_id;
    $this->_params['from_openid']= $openid_send;
    $this->_params['to_openid']= $openid_receive;
    //$this->_params['scene']= '';
    //$this->_params['remark']= '';
    $result= $this->_post_request($url, $this->_params);
    return $this->_handle_result($result);
}

    //储值接口群##################################################

    public function balence_info( $openid )
    {
        $url= $this->_server. 'api/deposit/getinfo';
        $this->_params['openid']= $openid;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    public function balence_list( $openid, $type='useoff', $num=10, $next_id=NULL )
    {
        $url= $this->_server. 'api/deposit/getlogs';
        $this->_params['openid']= $openid;
        $this->_params['type']= $type;
        $this->_params['num']= $num;
        if($next_id) $this->_params['next_id']= $next_id;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }
    
    public function balence_scale( $openid=NULL )
    {
        $url= $this->_server. 'api2/deposit/getscale';
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    public function balence_scale_convert($result, $quote, $to_money=TRUE, $type='use' )
    {
        if( $result==NULL || $quote==NULL || $result=='' || $quote=='' )
            $this->show_exception('转换参数不能为空', TRUE);
        
        $data= (array) $result['data'];
        if( $type=='use' ) $percent= $data['use'];
        else $percent= $data['add'];
        if($to_money) return $quote/ $percent;  //储值得出抵扣额
        else return $quote* $percent;   //消费额得出储值
    }

    protected function check_passwd( $passwd )
    {
        // $yinju_inter_ids = array('a457946152', 'a471258436');
        $yinju_inter_ids = array('a457946152', 'a471258436', 'a450089706');
        $passwd = trim( $passwd );
        if( in_array( $this->_params['inter_id'], $yinju_inter_ids ) ) {
            if( isset( $passwd ) && !empty( $passwd ) ){

            }else{
                $this->show_exception('密码不能为空！');die;
            }
        }
    }
    
    public function balence_use($count, $openid, $passwd, $uu_code, $order_id)
    {   
        $this->check_passwd( $passwd );
        $url= $this->_server. 'api/deposit/useoff';
        $this->_params['count']= $count;
        $this->_params['uu_code']= $uu_code;
        $this->_params['openid']= $openid;
        $this->_params['passwd']= $passwd;
        $this->_params['order_id']= $order_id;

        /*
         * crsNo        pms订单号
         * localNo      本地订单号
         * 2017/03/23 luguihong 储值使用添加多两个字段
         * tips：如果没有对接pms的，两个字段值一致
        */
        $this->_params['crsNo']     = $order_id;
        $this->_params['localNo']   = $order_id;

        /**
         * 以下为非必填。储值支付的时候
         * $this->_params['couponId']       = $couponId;        //优惠券ID
         * $this->_params['couponCardId']   = $couponCardId;    //优惠券种类ID
         * $this->_params['discountNum']    = $discountNum;     //总优惠金额
         * $this->_params['hotelName']      = $hotelName;       //酒店名
         * $this->_params['hotelId']        = $hotelId;         //酒店ID
         */

        $this->_params['remark']= '订单号：'. $order_id;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    // 隐居储值消费接口不一样，sales_discount_model里面通过inter_id限定
    public function yinju_balence_use($count, $openid, $passwd, $uu_code, $order_id)
    {
        $this->check_passwd( $passwd );
        $url= $this->_server. 'api/deposit/deposit_useoff';
        $this->_params['count']= $count;
        $this->_params['uu_code']= $uu_code;
        $this->_params['openid']= $openid;
        $this->_params['password']= $passwd;
        $this->_params['order_id']= $order_id;

        /*
         * crsNo        pms订单号
         * localNo      本地订单号
         * 2017/03/23 luguihong 储值使用添加多两个字段
         * tips：如果没有对接pms的，两个字段值一致
        */
        $this->_params['crsNo']     = $order_id;
        $this->_params['localNo']   = $order_id;

        /**
         * 以下为非必填。储值支付的时候
         * $this->_params['couponId']       = $couponId;        //优惠券ID
         * $this->_params['couponCardId']   = $couponCardId;    //优惠券种类ID
         * $this->_params['discountNum']    = $discountNum;     //总优惠金额
         * $this->_params['hotelName']      = $hotelName;       //酒店名
         * $this->_params['hotelId']        = $hotelId;         //酒店ID
         */

        $this->_params['remark']= '订单号：'. $order_id;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    // 储值回滚
    public function balence_rollback( $openid, $member_info_id, $count, $uu_code, $module='soma', $note )
    {
        /*
            增加储值：
            deposit/add  HTTP POST
            param:token、inter_id、openid、member_info_id、count、uu_code、module、scene、note
            ps：count（储值数值，必传），module（模块，必传），uu_code（调用频率基数，必传），scene（场景），note（备注）
        */
        //2016-12-13目前会员没有储值回滚，所以这里处理为添加储值
        $url= $this->_server. 'api/deposit/add';
        $this->_params['openid']= $openid;
        $this->_params['member_info_id']= $member_info_id;//不是必传
        $this->_params['count']= $count;//储值数值，必传
        $this->_params['uu_code']= $uu_code;//调用频率基数，必传
        $this->_params['module']= $module;//模块，必传
        $this->_params['note']= $note;//套票商城订单，拉起支付15分钟内未支付的订单，储值回滚
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);


    }

    /**
     * 储值充值链接
     */
    public function balence_deposit_url($inter_id) {
        // 20161219 luguihong 储值充值隐居链接 http://touch.19yin.com/index.php/membervip/depositcard?id=a457946152&from=groupmessage&isappinstalled=0
        $ci =& get_instance();
        $ci->load->model('wx/Publics_model','PublicsModel');
        $publics= $ci->PublicsModel->get_public_by_id($inter_id);
        $balance_url = '';
        if( $publics ){
            // $balance_url = isset( $publics['domain'] ) ? 'http://' . $publics['domain'] 
            //                   . '/index.php/membervip/depositcard/buydeposit?id='.$inter_id : '';
            $yinju_inter_ids = array('a457946152', 'a471258436');
            if( in_array( $inter_id, $yinju_inter_ids ) ){
                $balance_url = isset( $publics['domain'] ) ? 'http://' . $publics['domain'] 
                                  . '/index.php/membervip/depositcard?id='.$inter_id : '';
            }else{
                $balance_url = isset( $publics['domain'] ) ? 'http://' . $publics['domain'] 
                              . '/index.php/membervip/depositcard/buydeposit?id='.$inter_id : '';
            }

        }
        return $balance_url;
    }

    
    //积分接口群##################################################

    public function point_info( $openid )
    {
        $url= $this->_server. 'api/credit/getinfo';
        $this->_params['openid']= $openid;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    public function point_list( $openid, $type='useoff', $num=10, $next_id=NULL )
    {
        $url= $this->_server. 'api/credit/getlogs';
        $this->_params['openid']= $openid;
        $this->_params['type']= $type;
        $this->_params['num']= $num;
        if($next_id) $this->_params['next_id']= $next_id;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }
    
    public function point_scale( $openid, $scale_config, $type='use' )
    {
        if($openid){
            $lvl_info= $this->member_lv_info($openid);
            $lvl_info = json_decode(json_encode($lvl_info), true);

            if($type == 'use'){
                //使用积分比例
                if( isset($lvl_info['data']['member_lvl_id']) ){
                    $lvl_id= $lvl_info['data']['member_lvl_id'];
                
                    $scale_config= json_decode($scale_config, TRUE);
                    if( is_array($scale_config) && array_key_exists($lvl_id, $scale_config ) ){
                        return $scale_config[$lvl_id];
                    }
                }
                
            } else {
                //获得积分比例，使用与获取可能不一样
                if( isset($lvl_info['data']['member_lvl_id']) ){
                    $lvl_id= $lvl_info['data']['member_lvl_id'];
                
                    $scale_config= json_decode($scale_config, TRUE);
                    if( is_array($scale_config) && array_key_exists($lvl_id, $scale_config ) ){
                        return $scale_config[$lvl_id];
                    }
                }
                
            }
        }
        return FALSE;
    }

    // 使用积分，积分与钱互转函数
    public function point_scale_convert($scale, $quote, $to_money=TRUE )
    {
        if($scale>0 && $quote>0){
            if($to_money) 
                return $quote* $scale;  //积分转为钱
            else 
                return $quote/ $scale;  //钱转为积分
            
        } else {
            return 0;
        }
    }

    // 获取积分钱转积分函数
    public function point_scale_convert_get($scale, $quote) {
        return floor($quote* $scale);
    }

    /**
     * 积分使用
     * @param $count
     * @param $openid
     * @param $uu_code
     * @param $order_id
     * @return array
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function point_use($count, $openid, $uu_code, $order_id, $orderModel=NULL)
    {
        $remark = '订单号：'. $order_id;

        //这里是为了特殊处理碧桂园的
        $return = $this->_get_new_params( $order_id, $orderModel );
        if( $return )
        {
            $order_id   = $return['order_id'];
            $remark     = $return['remark'];
        }

        $url= $this->_server. 'api/credit/useoff';
        $this->_params['count']= $count;
        $this->_params['uu_code']= $uu_code;
        $this->_params['openid']= $openid;
        $this->_params['order_id']= $order_id;
        $this->_params['remark']= $remark;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }
    protected function _get_new_params( $order_id, $orderModel )
    {
        $return = array();

        //20170303 碧桂园积分使用修改
        $interArray = array(
            'a429262688',//金房卡
            'a421641095',//碧桂园
            'a452233816',//信息驿站
        );

        if( in_array( $this->_params['inter_id'], $interArray ) )
        {
            if( $orderModel )
            {
                $product = $orderModel->product;
                $product_info = $product[0];

                //传商品内容和兑换明细
                $remark = $product_info['name'];

                $wxOrderId = $orderModel->wx_out_trade_no_encode(
                    $order_id,
                    $orderModel->m_get('settlement'),
                    $orderModel->m_get('business')
                );
                $hotelId = $orderModel->m_get('hotel_id');
                $order_id = "{$order_id},{$hotelId},{$wxOrderId},微信积分商城兑换";

                $return['order_id'] = $order_id;
                $return['remark'] = $remark;

                $this->_write_log( $order_id.$remark, 'request:sssddd:'.$order_id );

            } else {

                //这里存在一种情况，刚下完单，然后查找不到数据。。可能是事务没有提交，读取的旧数据，所以查找不到刚下单的数据

                $CI =& get_instance();
                $interId = $this->_params['inter_id'];

                //需要初始化分片信息
                $CI->current_inter_id = $interId;
                $CI->load->model('soma/shard_config_model', 'somaShardConfigModel');
                if( $CI->current_inter_id )
                {
                    $CI->db_shard_config= $CI->somaShardConfigModel->build_shard_config( $interId );
                }

                $CI->load->model('soma/Sales_order_model','somaSalesOrderModel');
                $business = 'package';
                $filter = array('order_id'=>$order_id);
                $orderDetail = $CI->somaSalesOrderModel->get_order_list_with_filter($business, $interId, $filter);
                if( $orderDetail )
                {
                    //传商品内容和兑换明细
                    $remark = $orderDetail[$order_id]['items'][0]['name'];

                    $wxOrderId = $CI->somaSalesOrderModel->wx_out_trade_no_encode(
                        $order_id,
                        $orderDetail[$order_id]['settlement'],
                        $orderDetail[$order_id]['business']
                    );
                    $hotelId = $orderDetail[$order_id]['hotel_id'];
                    $order_id = "{$order_id},{$hotelId},{$wxOrderId},微信积分商城兑换";
                }

                $return['order_id'] = $order_id;
                $return['remark'] = $remark;

                $this->_write_log( $order_id.$remark, 'request:sssddd:'.$order_id );

            }

        }

        return $return;

    }

    public function point_rollback( $order_id, $uu_code )
    {
        $url= $this->_server. 'api/credit/giveback';
        $this->_params['order_id']= $order_id;
        $this->_params['uu_code']= $uu_code;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    public function point_plus($count, $openid, $uu_code, $order_id)
    {
        $url= $this->_server. 'api/credit/add';
        $this->_params['count']= $count;
        $this->_params['uu_code']= $uu_code;
        $this->_params['openid']= $openid;
        $this->_params['order_id']= $order_id;
        $this->_params['remark']= '订单号：'. $order_id;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    //F码接口群##################################################
    

    //会员级别信息接口群##################################################

    //20170103 luguihong 判断是否登录接口
    public function get_member_info( $openid )
    {
        $url= $this->_server. 'api/member/getinfo';
        $this->_params['openid']= $openid;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    //会员登录链接
    public function goto_member_login( $inter_id, $return_url )
    {
        // $url= $this->_server. 'membervip/login?redir='.$return_url;
        // redirect($url);
        
        $ci =& get_instance();
        $ci->load->model('wx/Publics_model','PublicsModel');
        $publics= $ci->PublicsModel->get_public_by_id($inter_id);
        $login_url = '';
        if( $publics ){
            $login_url = isset( $publics['domain'] ) ? 'http://' . $publics['domain'] 
                          . '/index.php/membervip/login?redir='.$return_url:'';
        }

        redirect($login_url);
    }

    public function member_lvl()
    {
        $url= $this->_server. 'api2/member/lvl_info';
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }
    
    public function member_info($openid)
    {
        $url= $this->_server. 'api2/member/getinfo';
        $this->_params['openid']= $openid;
        $this->_params['is_pms']= 1;    //是为1否为空
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    public function member_lv_info($openid)
    {
        $url = $this->_server . 'vapi/member/GetMemberLevelInfo';
        $this->_params['openid']= $openid;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }


    //礼包信息接口群##################################################

    //单个礼包查询 
    public function get_package_info( $package_id, $status='1' )
    {
        $url= $this->_server. 'api/package/getinfo';
        $this->_params['package_id']= $package_id;
        $this->_params['status']= $status;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    //公众号礼包列表
    public function get_package_list( $is_active='t', $num=100 )
    {
        $url= $this->_server. 'api/package/getlist';
        $this->_params['is_active']= $is_active;
        $this->_params['num']= $num;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    //礼包使用 inter_id在实例化传入
    public function package_use( $openid, $package_id, $uu_code )
    {
        $url= $this->_server. 'api/package/receive';
        $this->_params['openid']= $openid;
        $this->_params['package_id']= $package_id;
        $this->_params['uu_code']= $uu_code;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    //礼包使用 inter_id在实例化传入 $package_id可以用多个使用逗号隔开。例：package_id_1,package_id_2
    public function package_use_batch( $openid, $package_id, $uu_code, $count=1 )
    {
        $url= $this->_server. 'api/package/receive';
        $this->_params['openid']= $openid;
        $this->_params['package_id']= $package_id;
        $this->_params['uu_code']= $uu_code;
        $this->_params['count']= $count;
        $result= $this->_post_request($url, $this->_params);
        return $this->_handle_result($result);
    }

    //处理礼包里面优惠券的内容，礼包拉取回来的优惠券是不包含详情信息的
    public function _package_card( $packageList=array(), $inter_id )
    {
        if( count( $packageList ) > 0 ){
            $packageList = json_decode( json_encode($packageList), true );
            if( isset( $packageList['data']['card'] ) && !empty( $packageList['data']['card'] ) ){
                $cardList = $packageList['data']['card'];
                $cardIds = array();
                foreach( $cardList as $k=>$v ){
                    $cardIds[$v['value']] = $v;
                }

                if( count( $cardIds ) > 0 ){
                    //拉取所有的优惠券,先从缓存里面获取，如果没有再调用会员接口
                    $key = "SOMA_MEMBER_CARD:COUPON_LIST_{$inter_id}";
                    // $cache= $this->_load_cache();
                    // $redis= $cache->redis->redis_instance();
                    $redis = $this->get_redis_instance();

                    $couponList = array();
                    if( $redis->hExists($key, $inter_id) ){
                        $couponList = json_decode( $redis->hGet($key, $inter_id), true );
                    }else{
                        $couponList = json_decode( json_encode( $this->conpon_all() ), true );
                        if( count( $couponList ) > 0 ){
                            $redis->hSet($key, $inter_id, json_encode($couponList) );
                            $redis->expireAt($key, time()+ 300 );
                        }
                    }
                    // var_dump( $redis->hExists($key, $inter_id), $couponList );die;
                    if( isset( $couponList['data'] ) && count( $couponList['data'] ) > 0 ){
                        $cardList_new = array();
                        foreach( $couponList['data'] as $k=>$v ){
                            if( array_key_exists( $v['card_id'], $cardIds ) ){
                                $data = array();
                                $data = $v;
                                $data['number'] = $cardIds[$v['card_id']]['number'];
                                $cardList_new[] = $data;
                            }
                        }
                    }

                    $packageList['data']['card'] = $cardList_new;
                }
            }
        }

        return $packageList;
    }

    
}