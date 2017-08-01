<?php
class Api_distribute extends Soma_base
{
    protected $_debug= FALSE;
    //protected $_debug= TRUE;
    protected $_token;
    protected $_server;
    protected $_inter_id;
    protected $_module= 'soma';
    protected $_params= array();

    public function __construct()
    {
        return parent::__construct();
    }
    /**
     * 把请求/返回记录记入文件
     * @param String $content
     * @param string $type
     */
    protected function _write_log( $content, $type='request' )
    {
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'soma'. DS. 'distribute'. DS;
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
     * 
	 * Array {
"order_id":"订单号",
"order_amount":"订单总金额（包含优惠折扣金额）",
"inter_id":"公众号ID",
"hotel_id":"酒店ID",
"saler":"分销号，粉丝归属的分销号不用传，按次计算需要传",
"grade_openid":"粉丝openid",
"grade_table":"订房iwide_hotels_order，商城iwide_shp_orders，套票iwide_product_package_orders",
"grade_id":"记录产生绩效的表的主键值",
"grade_id_name":"记录产生绩效的表的ID名称",
"grade_total":"绩效总金额（默认-1）",
"grade_amount":"订单计算绩效部分的金额",
"grade_amount_rate":"绩效值/比例（grade_total等于-1，此字段可以不传）",
"status":"1:已核定未发放/线下发放，2:已核定已发放，3:未核定，4：已退款",
"grade_rate_type":"计算类型0：固定金额，1：比例（grade_total等于-1，此字段可以不传）",
"remark":"备注",
"product":"产品名称",
}
     * @param Array $v
     */
    public function reward_sending($v)
    {
        $CI= & get_instance();
	    $CI->load->model('soma/Reward_rule_model');
        $CI->load->model('distribute/Idistribute_model');
        $tmp= array();
        $tmp['order_id']= $v['order_id'];
        $tmp['inter_id']= $v['inter_id'];
        $tmp['order_amount']= $v['subtotal'];
        $tmp['saler']= $v['saler_id'];
        $tmp['grade_openid']= $v['openid'];
        
        if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
            //区分月饼订单
            $tmp['grade_table']= 'iwide_soma_mooncake_order:'.  $v['rule_type'];
        } else {
            $tmp['grade_table']= 'iwide_soma_sales_order:'.  $v['rule_type'];
        }
        $tmp['grade_id']= $v['order_id'];
        $tmp['grade_id_name']= 'order_id';
        $tmp['grade_total']= $v['reward_total'];
        $tmp['grade_typ']= $v['reward_source'];
        $tmp['grade_amount']= $v['grand_total'];
        $tmp['grade_amount_rate']= $v['reward_rate'];
        $tmp['grade_rate_type']= $v['reward_type']==Reward_rule_model::REWARD_TYPE_FIXED? 0: 1;
        $tmp['remark']= '';
        $tmp['product']= '套票订单'. $v['order_id'];
        $tmp['status']= $v['reward_status']% 10;  //对于 REWARD_STATUS_11 做取模处理
        //print_r($tmp);
        $result= $CI->Idistribute_model->create_dist($tmp);
        //var_dump($result);die;
        $result_echo= $result? '同步失败': '同步失败';
        if( $this->_debug ) $this->_write_log( json_encode($tmp) );

        if( !$result ) {
            //错误时记录日志
            $sql= $this->db->last_query();
            $this->_write_log( $v['order_id']. '订单同步失败，查询语句：'. $sql, 'sending');
        }
        return $result;
    }

    /**
     * 注意事项：分销模块业绩匹配关键字grade_id,grade_table,grade_typ,inter_id，
     * 其中一项不匹配将导致业绩重复记录，修改时应注意！！
     */
    public function reward_modify($v)
    {
        $CI= & get_instance();
        $CI->load->model('soma/Reward_rule_model');
        $CI->load->model('distribute/Idistribute_model');
        $tmp= array();
        $tmp['grade_id']= $v['order_id'];
        $tmp['inter_id']= $v['inter_id'];
        $tmp['grade_typ']= $v['reward_source'];

        if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
            //区分月饼订单
            $tmp['grade_table']= 'iwide_soma_mooncake_order:'.  $v['rule_type'];
        } else {
            $tmp['grade_table']= 'iwide_soma_sales_order:'.  $v['rule_type'];
        }
        
        /**
         * 收益记录状态定义  @see Reward_benefit_model
    self::REWARD_STATUS_1 => '已核定 - 未发放',    //确认发放/拼团成功
    self::REWARD_STATUS_2 => '已核定 - 已发放',
    self::REWARD_STATUS_4 => '未核定 - 尚未离店',
    self::REWARD_STATUS_5 => '已核定 - 无绩效',    //退款/拼图失败
    self::REWARD_STATUS_6 => '未核定 - 付款成功',  //订单付款/参团
         * 订单状态定义  @see Sales_order_model
    const STATUS_PAYMENT = 12;  //购买成功
    const STATUS_CANCLE  = 14;  //订单取消
    const STATUS_GROUPING= 15;  //拼团中
    const STATUS_GROUPED = 16;  //拼团成功
    const STATUS_GROUPFAIL = 17;  //拼团失败
         */
        if($v['rule_type']=='groupon'){
            if($v['reward_status']== 11) $tmp['order_status']= 16;
            if($v['reward_status']== 1) $tmp['order_status']= 16;
            if($v['reward_status']== 5) $tmp['order_status']= 14;
            if($v['reward_status']== 6) $tmp['order_status']= 15;
            
        } else {
            if($v['reward_status']== 11) $tmp['order_status']= 12;
            if($v['reward_status']== 1) $tmp['order_status']= 12;
            if($v['reward_status']== 5) $tmp['order_status']= 14;
            if($v['reward_status']== 6) $tmp['order_status']= 12;
        }
        $tmp['status']= $v['reward_status'];
        //print_r($tmp);
        $result= $CI->Idistribute_model->create_dist($tmp);
        //var_dump($result);die;
        $result_echo= $result? '同步失败': '同步失败';
        if( $this->_debug ) $this->_write_log( json_encode($tmp) );
        
        if( !$result ) {
            //错误时记录日志
            $sql= $CI->db->last_query();
            $this->_write_log( $v['order_id']. '订单同步失败，查询语句：'. $sql, 'modify');
        }
        return $result;
    }
    
}