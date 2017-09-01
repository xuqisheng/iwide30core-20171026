<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order_discount_model extends MY_Model_Soma {

    const OPEN_MEMBER = TRUE;  //遇到会员接口异常时，用FALSE关闭接口同步
    
    const STATUS_ACTIVE = 1;
    const STATUS_USE    = 2;
    const STATUS_CONSUME= 3;
    const STATUS_ROLLBACK=4;
    
    const TYPE_COUPON = 10;
    const TYPE_FCODE  = 20;
    const TYPE_POINT  = 30;
    const TYPE_BALENCE= 40;
    const TYPE_REDUCE = 51;
    const TYPE_DISCOUNT= 52;
    const TYPE_REDUCE_RAND= 55;

    //由会员中心指定
    const TYPE_COUPON_DJ = 1;  //代金券，对应 reduce_cost 字段
    const TYPE_COUPON_ZK = 2;  //折扣券，对应 discount 字段
    const TYPE_COUPON_DH = 3;  //兑换券，直接抵扣某项商品金额
    const TYPE_COUPON_CZ = 4;  //储值券，对应 money 字段

    public function get_status_label()
    {
        return array(
            self::STATUS_ACTIVE  => '未支付',
            self::STATUS_USE     => '已锁定',
            self::STATUS_CONSUME => '已核销',
            self::STATUS_ROLLBACK=> '退回',
        );
    }
    public function get_payment_label()
    {
        return array(
            self::TYPE_COUPON  => '优惠券',
            self::TYPE_FCODE   => '优惠码/F码',
            self::TYPE_POINT   => '积分',
            self::TYPE_BALENCE => '储值',
            self::TYPE_REDUCE  => '满减优惠',
            self::TYPE_DISCOUNT => '满额打折',
            //self::TYPE_REDUCE_RAND => '随机立减',
        );
    }
    public function get_card_type()
    {
        return array(
            self::TYPE_COUPON_DJ  => '抵扣券',
            self::TYPE_COUPON_ZK  => '折扣券',
            self::TYPE_COUPON_DH  => '兑换券',
            self::TYPE_COUPON_CZ  => '储值券',
        );
    }
    
	public function get_resource_name()
	{
		return '优惠内容';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function get_auto_rule()
	{
	    return array(self::TYPE_DISCOUNT, self::TYPE_REDUCE, self::TYPE_REDUCE_RAND );
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name($inter_id=NULL)
	{
		return $this->_shard_table('soma_sales_order_discount', $inter_id);
	}

	public function table_primary_key()
	{
	    return 'discount_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'discount_id'=> 'ID',
            'order_id'=> '订单ID',
            'openid'=> 'Openid',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店ID',
            'create_time'=> '创建时间',
            'status'=> '状态',
		    //以下字段由控制器传入
            'type'=> '类型',
            'amount'=> '抵扣额',
            'value'=> '优惠描述',          //相对抵扣券为面额；相对积分为分值；相对复杂情况为序列号数值
            'f_code'=> '优惠码',            //F码
            'member_card_id'=> '领取卡ID', //优惠券
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'discount_id',
            'order_id',
            //'openid',
            //'inter_id',
            //'hotel_id',
            'type',
            'amount',
            'value',
            'f_code',
	        //'member_card_id',
            'create_time',
            'status',
	    );
	}

	//定义 m_save 保存时不做转义字段
	public function unaddslashes_field()
	{
	    return array( 'value', );
	}
	
	/**
	 * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
	 *   type: grid中的表头类型定义 
	 *   form_type: form中的元素类型定义
	 *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
	 *   form_tips: form中的label信息提示
	 *   form_hide: form中自动化输出中剔除
	 *   form_default: form中的默认值，请用字符类型，不要用数字
	 *   select: form中的类型为 combobox时，定义其下来列表
	 */
	public function attribute_ui()
	{
	    /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
	    //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
	    $base_util= EA_base::inst();
	    $modules= config_item('admin_panels')? config_item('admin_panels'): array();

	    /** 获取本管理员的酒店权限  */
	    $hotels_hash= $this->get_hotels_hash();
	    $publics = $hotels_hash['publics'];
	    $hotels = $hotels_hash['hotels'];
	    $filter = $hotels_hash['filter'];
	    $filterH = $hotels_hash['filterH'];
	    /** 获取本管理员的酒店权限  */
	    	  
	    return array(
            'discount_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '7%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $hotels,
            ),
            'type' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> self::get_payment_label(),
            ),
            'value' => array(
                'grid_ui'=> '',
                'grid_width'=> '20%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'member_card_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'f_code' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'amount' => array(
                'grid_ui'=> '',
                'grid_width'=> '7%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '15%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> self::get_status_label(),
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'discount_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	protected function _match_link_product($product_array, $link_product, $is_wide_scope=FALSE )
	{
	    $result= array();
	    foreach ($product_array as $k=>$v){
	        if( $is_wide_scope || array_key_exists($v['product_id'], $link_product) ){
	            $p_qty= (int) $v['qty'];
	            if( $is_wide_scope || $p_qty >= $link_product[$v['product_id']]['qty'] ){
	                $result[$v['product_id']]= $v['price_package'];
	            }
	        }
	    }
	    return $result;
	}

	//打折计算公式
	public function reduce_discount_cal($discount, $subtotal )
	{
	    return round($subtotal * (10- $discount) /10, 2);
	}
	
	/**
	 * 计算折扣总额
	 * @param $product_array  array('1201'=>array(...'qty'=>1), '1133'=>array(...'qty'=>2), );
	 * @param $discount  array('....');
	 */
	//public function calculate_discount( $discount, $product_array=array(), $type=10, $subtotal=0, $customer=NULL, $order_id=NULL )
	public function calculate_discount( $discount, $product_array=array(), $type=10, $order )
	{
	    $subtotal= $order->subtotal;
	    $customer= $order->customer;
	    $order_id= $order->order_id;
	    
	    $total= 0;
        //处理优惠卡类逻辑
        if( $type==self::TYPE_COUPON ){
            //是否满足最低使用额
    	    if( (isset($discount['least_cost']) && $subtotal < $discount['least_cost']) 
				|| (isset($discount['over_limit']) && $discount['over_limit'] > 0 && $subtotal > $discount['over_limit']) ){
    	        return NULL;
    	        
    	    } else {
                $this->load->model('soma/Sales_coupon_model');
                $link_product= $this->Sales_coupon_model->get_card_product($discount);
                
                $wide_scope_card= $this->Sales_coupon_model->get_wide_scope_coupon($discount['inter_id'], TRUE);
                $is_wide_scope= ( array_key_exists($discount['card_id'], $wide_scope_card))? TRUE: FALSE;
                
                //检查购物清单是否与券适用商品配置 的匹配情况
                $match_price_array= $this->_match_link_product($product_array, $link_product, $is_wide_scope);
                
                //获取购物清单的 pid=> qty hash数组
                $qty_arr= array();
                foreach ($product_array as $sk=>$sv ){
                    if( array_key_exists($sv['product_id'], $match_price_array ))
                        $qty_arr[$sv['product_id']]= (int) $sv['qty'];
                }
                
                if( count($match_price_array)>0 ){
                    switch ($discount['card_type']) {
                        case self::TYPE_COUPON_ZK:
                            /** 带最高优惠额度判断，测试未完成
                            $dsc_total= $dsc_total_= 0;
                            foreach ($match_price_array as $sk=> $sv){
                                for($ii=1; $ii<= $sv; $ii++){
                                    $tmp_total = $this->reduce_discount_cal( $discount['discount'], $qty_arr[$sk] * $ii );
                                    if( isset($discount['over_limit']) && $discount['over_limit'] > 0 
                                        && $tmp_total <= $discount['over_limit']
                                    ){
                                        $dsc_total_ = $tmp_total;
                                    }
                                }
                                $dsc_total += $dsc_total_;
                            }
                            $total= $dsc_total;
                             */
                            /** 折扣券处理流程，根据商品 */
                            foreach ($match_price_array as $sk=> $sv){
                              $total+= $this->reduce_discount_cal( $discount['discount'], $qty_arr[$sk] * $sv );
                              //$total+= (100- $discount['discount'])/100 * $qty_arr[$sk] * $sv;
                            }
                            /** 按照整个订单 */
                            //if( count($match_price_array)>0 ) $total+= $subtotal* (100- $discount['discount'])/100;
                            break;
                        case self::TYPE_COUPON_DH:
                            //兑换券处理流程，根据商品
                            foreach ($match_price_array as $sk=> $sv){
                                if($total>0) continue; //兑换券只减1件
                                else $total+= $sv;
                            }
                            break;
                        case self::TYPE_COUPON_CZ:
                            //储值券处理流程
                            //$match_price_array= $this->_match_link_product($product_array, $link_product);
                
                            break;
                        case self::TYPE_COUPON_DJ:
                        default:
                            //代金券处理流程
                            //foreach ($match_price_array as $sk=> $sv){
                            //      $total+= $discount['reduce_cost']; //按照每件扣减一定额度
                            //}
                            //按照整个订单
                            if( count($match_price_array)>0 ) $total+= $discount['reduce_cost'];
                            break;
                    }
                    $order->coupon_reduce= $total;

                    // 订单表添加优惠券抵扣金额
                    $order->conpon_total += $total;
                }
            }
	        
        } elseif( $type==self::TYPE_BALENCE || $type==self::TYPE_POINT || $type==self::TYPE_REDUCE || $type==self::TYPE_DISCOUNT ){
            $start_date= strtotime($discount['start_time']);
            $end_time= strtotime($discount['end_time']);
            
            if( in_array($type, $this->get_auto_rule() ) && ( (isset($discount['least_cost']) && $subtotal < $discount['least_cost']) 
				|| (isset($discount['over_limit']) && $discount['over_limit'] > 0 && $subtotal > $discount['over_limit']) )
            ){
                return NULL;
                
            } else if( time()< $start_date && time()> $end_date ){
                return NULL;
                
            } else {
                /*
                $this->load->model('soma/Sales_rule_model');
                $link_product= $this->Sales_rule_model->get_card_product($discount);
                
                $wide_scope_card= $this->Sales_rule_model->get_wide_scope_rule($discount['inter_id'], TRUE);
                $is_wide_scope= ( array_key_exists($discount['rule_id'], $wide_scope_card))? TRUE: FALSE;
                
                //检查购物清单是否与券适用商品配置 的匹配情况
                $match_price_array= $this->_match_link_product($product_array, $link_product, $is_wide_scope);
                */
                $this->load->library('Soma/Api_member');
                $api= new Api_member($discount['inter_id']);
                $result= $api->get_token();
                $api->set_token($result['data']);
                $uu_code= $api->uuCode();
                
                if( $type==self::TYPE_BALENCE && $customer->openid ){
                    //处理储值逻辑，前面已经做分数验证、优先级排序（包含时间排除+个人账户查询+扣减比例判断）
                    //$result= $api->balence_info( $customer->openid );
                    //if($result['data']>= $discount['reduce_cost']){
                    
                    //优惠券抵扣计算
                    if( $order->coupon_reduce && $discount['quote'] ){
                        $scale= $api->balence_scale( $customer->openid );
                        $quote_reduce= $api->balence_scale_convert($scale, $order->coupon_reduce, FALSE );  //金额转储值
                        $discount['quote']= $discount['quote']- $quote_reduce;  //减去优惠券所占金额的储值
                    }
                    //var_dump($quote_reduce);die;
                    
                    if( $order_id ){
                        // 隐居的储值使用调用特殊接口 a471258436 为30上的特殊测试号
                        $use_result['err'] = 1; // 默认调用失败
                        $yinju_inter_ids = array('a457946152', 'a471258436', 'a450089706');
                        if(in_array($order->inter_id, $yinju_inter_ids)) {
                            $use_result= (array) $api->yinju_balence_use($discount['quote'], $customer->openid, $discount['passwd'], $uu_code, $order_id);
                        } else {
                            $use_result= (array) $api->balence_use($discount['quote'], $customer->openid, $discount['passwd'], $uu_code, $order_id);
                        }
                        if( $use_result['err']!= 0 ){  //密码错误时会员接口返回异常，以防接口异常err不返回0则中断
                            Soma_base::inst()->show_exception('您输入的储值密码错误！');
                        }
                    }
                    $total= $discount['reduce_cost']- $order->coupon_reduce;

                    // 订单加上一个储值支付金额
                    $order->balance_total = $order->balance_total + $total;
                    
                } else if( $type==self::TYPE_POINT && $customer->openid ){
                    //处理积分逻辑，前面已经做分数验证、优先级排序（包含时间排除+个人账户查询+扣减比例判断）
                    //$result= $api->point_info( $customer->openid );
                    //if($result['data']>= $discount['reduce_cost']){

                    //优惠券抵扣计算
                    if( $order->coupon_reduce && $discount['quote'] ){
                        $scale= $api->point_scale( $customer->openid, $discount['bonus_size'] );
                        $quote_reduce= $api->point_scale_convert($scale, $order->coupon_reduce, FALSE );  //金额转积分
                        $discount['quote']= $discount['quote']- $quote_reduce;  //减去优惠券所占金额的积分
                    }
                    
                    //下单即扣积分（原因：通过异步队列推送积分存在时间间隙，可能存在机器人刷积分的情况 )
                    if( $order_id ){
                       $api->point_use($discount['quote'], $customer->openid, $uu_code, $order_id, $order);
                    }
                    // 不需要扣减优惠券金额，前端已经计算
                    // $total= $discount['reduce_cost']- $order->coupon_reduce;
                    $total = $discount['reduce_cost'];
                    
                    // 订单加上一个积分抵扣金额
                    $order->point_total = $order->point_total + $total;

                } else if( $type==self::TYPE_REDUCE ){
                    //处理满减逻辑，前面已经做优先级排序（包含时间排除+扣减比例判断）
                    $total= (float) $discount['reduce_cost'];
                    
                } else if( $type==self::TYPE_DISCOUNT ){
                    //处理满减逻辑，前面已经做优先级排序（包含时间排除+扣减比例判断）
                    $total= (float) $this->reduce_discount_cal($discount['reduce_cost'], $subtotal );
                }
            }
            
        } elseif( $type==self::TYPE_REDUCE_RAND ){
            //处理随机立减逻辑
            
        } elseif( $type==self::TYPE_FCODE ){
            
        } 
	    return ($total<0)? 0: $total;
	}
	
	/**
	 * 将折扣数据转换为可保存格式
	 * @param Sales_order_model $order  订单此刻还没有保存数据
	 * @param Array $discount  单条优惠规则
	 * @param Int $type
	 * @return multitype:string number unknown NULL
	 */
	public function convert_disount($order, $discount, $type=10 )
	{
	    //print_r($order);   //订单此刻还没有保存数据
	    //$discount_total= $this->calculate_discount( $discount, $order->product, $type, $order->subtotal, $order->customer, $order->order_id );
	    $discount_total= $this->calculate_discount( $discount, $order->product, $type, $order );
	    if( !$discount_total || $discount_total==0){
	        return NULL;
	        
	    } else {
    	    $data= array(
    	        'type' => $type,
    	        'order_id'=> $order->order_id,
    	        'inter_id'=> $order->inter_id,
    	        'hotel_id'=> $order->hotel_id,
    	        'openid'  => $order->openid,
    	        'amount'  => $discount_total,
    	        'create_time'=> date('Y-m-d H:i:s'),
    	        'member_card_id'=> '',
    	        'f_code'=> '',
    	        'value'=> '',
    	        'status'=> self::STATUS_ACTIVE,
    	    );
            switch ($type ) {
                case self::TYPE_COUPON:
                    $data['member_card_id']= $discount['member_card_id'];
                    $value_field['card_type']= isset($discount['card_type'])? $discount['card_type']: '';
                        
                    if($value_field['card_type']== self::TYPE_COUPON_DJ )
                        $value_field['reduce_cost']= isset($discount['reduce_cost'])? $discount['reduce_cost']: '';
                    
                    else if($value_field['card_type']== self::TYPE_COUPON_ZK )
                        $value_field['reduce_cost']= isset($discount['discount'])? $discount['discount'].'折' : '';
                    
                    else if($value_field['card_type']== self::TYPE_COUPON_DH )
                        $value_field['reduce_cost']= '兑换券';
                    
                    else if($value_field['card_type']== self::TYPE_COUPON_CZ )
                        $value_field['reduce_cost']= isset($discount['money'])? $discount['money']: '';
                     
                break;
                case self::TYPE_REDUCE_RAND: 
                    $value_field['rule_id']= $discount['rule_id'];
                    $value_field['reduce_cost']= $discount['reduce_cost'];
                break;
                case self::TYPE_REDUCE:
                    $value_field['rule_id']= $discount['rule_id'];
                    $value_field['reduce_cost']= $discount['reduce_cost'];
                    $value_field['lease_cost']= $discount['lease_cost'];
                break;
                case self::TYPE_DISCOUNT:
                    $value_field['rule_id']= $discount['rule_id'];
                    $value_field['reduce_cost']= $discount['reduce_cost'];  //代表折扣
                    $value_field['lease_cost']= $discount['lease_cost'];
                break;
                case self::TYPE_BALENCE:
                case self::TYPE_POINT: 
                    $data['quote']= $discount['quote']; //写入quote字段
                    
                    $value_field['rule_id']= $discount['rule_id'];
                    //$value_field['reduce_cost']= $discount['reduce_cost'];
                    $value_field['quote']= $discount['quote'];
                    $value_field['scale']= $discount['scale'];
                break;
            }
            if( !isset($data['quote']) ) $data['quote']= '';
            $data['value']= json_encode( $value_field, JSON_UNESCAPED_UNICODE );
            return $data;
	    }
	}
	
	/** 解析 value字段含义，封装格式参考  @see Sales_order_discount_model::convert_disount() */
	public function parse_discount_value($row)
	{
	    $type= $row[2];  //key=2 为  type
	    switch ($type ) {
	        case '优惠券':
	            $card_type= $this->get_card_type();
	            $array= json_decode($row[4], TRUE); //key=2 为  value
	            $html= "属{$card_type[$array['card_type']]},关键信息：{$array['reduce_cost']}";
	            break;
	        case '储值':
	            $array= json_decode($row[4], TRUE); //key=2 为  value
	            if( is_object($array['scale']) ) {
	               $array['scale']= json_encode($array['scale']->data,JSON_UNESCAPED_UNICODE);
	            } else
	                $array['scale']= '';
	            $html= "规则ID {$array['rule_id']}，使用 储值{$array['quote']}，兑换比例参数：{$array['scale']}";
	            //$html= "使用储值 {$array['quote']} ";
	            break;
	        case '积分':
	            $array= json_decode($row[4], TRUE); //key=2 为  value
	            $array['scale']= $array['scale'];
	            $html= "规则ID {$array['rule_id']}，使用积分  {$array['quote']}，兑换比例参数：{$array['scale']}";
	            //$html= "使用积分  {$array['quote']} ";
	            break;
	        case '满减优惠':
	            $array= json_decode($row[4], TRUE); //key=2 为  value
	            if( !isset($array['rule_id']) ) $array['rule_id']= '';
	            $html= "规则ID {$array['rule_id']}，满￥ {$array['lease_cost']}，立减￥{$array['reduce_cost']}活动";
	            break;
	        case '满额打折':
	            $array= json_decode($row[4], TRUE); //key=2 为  value
	            if( !isset($array['rule_id']) ) $array['rule_id']= '';
	            $html= "规则ID {$array['rule_id']}，满￥ {$array['lease_cost']}，即享 {$array['reduce_cost']} 折活动";
	            break;
	        case '随机立减':
	            if( !isset($array['rule_id']) ) $array['rule_id']= '';
	            $html= "规则ID {$array['rule_id']}，随机立减活动 ";
	            break;
	    }
	    return $html;
	}

    /**
     * 核销处理
     * @param $order_id
     * @param $inter_id
     * @param int $step
     * @param bool $isOrderRollback
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
	protected function _handle_discount($order_id, $inter_id, $step=1, $isOrderRollback=FALSE)//优惠券批量使用，这里需要改成多个mcid 10
	{
        $debug= TRUE;
        if($debug) {
            $this->load->helper('soma/package');
            $log_txt= "折扣处理：订单号{$order_id}，处理阶段码{$step}--\n";
        }
	    
	     $list= $this->find_all( array('order_id'=> $order_id) );
	     //print_r($list);die;
	     $this->load->library('Soma/Api_member');
	     $api= new Api_member($inter_id);

        /**
         * @var Sales_order_model $somaSalesOrderModel
         */
	     $this->load->model('soma/Sales_order_model','somaSalesOrderModel');
	     $somaSalesOrderModel = $this->somaSalesOrderModel;
         
         if(is_array($list) && count($list) > 0) {
            $result= $api->get_token();
            $api->set_token($result['data']);
         }

	     $table= $this->table_name($inter_id);
	     foreach ($list as $k=>$v){
            /*
                20161214 luguihong 处理订单优惠回滚
                Ties:如果是回滚的需要特殊处理一下。因为这里是根据订单号检索出来的，有可能其中的一两条优惠回滚，因为发生未知错误
                ，导致该订单后面的优惠回滚失败，下次再执行回滚的话，已经回滚的可能会再回滚。
            */
            if( $step == self::STATUS_ROLLBACK && $v['status'] == self::STATUS_ROLLBACK ){
                //如果已经是回滚过了的，就停止这条信息处理
                if($debug) $log_txt.= "优惠ID{$v['discount_id']}已经回滚，不必再次处理". json_encode($v) . "-\n";
                continue;
            }

	         if($v['discount_id']) {
	             $this->_shard_db($inter_id)->where('discount_id', $v['discount_id'])->update($table, array(
	                 //'update_time'=> date('Y-m-d H:i:s'),
	                 'status'=> $step,
	             ) );
	             //检测update的生效情况防止重复更新
	             $row= $this->_shard_db($inter_id)->affected_rows();
	             
	             if($debug) $log_txt.= '待处理条数为：'. $row. "--\n";
	         }
	         if( $row>0 ){
	             $this->_shard_db($inter_id)->where('discount_id', $v['discount_id'])->update($table, array(
	                 'update_time'=> date('Y-m-d H:i:s'),
	                 //'status'=> $step,
	             ) );
    	         switch ($v['type']){
    	             case self::TYPE_COUPON:
    	                 //后续的优惠券处理：使用、核销、回滚
    	                 if( $step== self::STATUS_USE ){
    	                     $simpleOrder = $somaSalesOrderModel->get_order_simple($order_id);
    	                     $order_payment = isset( $simpleOrder['real_grand_total'] ) ? $simpleOrder['real_grand_total'] : '';
    	                     $api->conpon_useone($v['member_card_id'], $v['openid'], $order_id, $v['amount'], $order_payment);
    	                     
                	     } elseif( $step== self::STATUS_CONSUME ){
                	         $api->conpon_consume($v['member_card_id'], $v['openid']);
                	         
            	         } elseif( $step== self::STATUS_ROLLBACK ){
                             //如果是订单回滚的，不处理优惠券
                             if( !$isOrderRollback )
                             {
                                 $api->conpon_rollback($v['member_card_id'], $v['openid']);
                             }
            	         }
            	         
	                     if($debug) $log_txt.= "优惠券处理成功，ID{$v['member_card_id']}，优惠明细". json_encode($v) . "-\n";
    	             break;
    	             case self::TYPE_POINT:
    	                 //积分为下单时候扣减、回滚
    	                 $this->load->model('soma/Sales_point_model');
    	                 $point_model= $this->Sales_point_model;
    	                 if( $step== self::STATUS_USE ){
    	                     //下单成功推送积分扣减记录到队列（已改为下单马上扣积分，移至 calculate_discount() ）
    	                     //$push_result= $point_model->insert_record_data($v['order_id'], $v['quote'], $point_model::RECORD_TYPE_USE);
    	                     //if($debug) $log_txt.= "积分扣减推送结果{$push_result}，积分数{$v['quote']}，优惠明细". json_encode($v) . "-\n";
    	                     
            	         } elseif( $step== self::STATUS_CONSUME ){
    	                     if($debug) $log_txt.= "商品核销时，积分不做扣减。-\n";
    	                     
            	         } elseif( $step== self::STATUS_ROLLBACK ){
    	                     //2016-12-13前回滚暂时不做处理
                             /*
                             回滚操作屏蔽插入积分推送队列（回滚积分直接调用接口回滚），
                             防止队列出现未推送的记录，致使队列爆栈
                             
    	                     $push_result= $point_model->insert_record_data($v['order_id'], $v['quote'], $point_model::RECORD_TYPE_REFUND);
    	                     if($debug) $log_txt.= "积分回滚推送结果{$push_result}，积分数{$v['quote']}，优惠明细". json_encode($v) . "-\n";
                             */

                             //2016-12-13 luguihong 处理积分回滚
                             $uu_code= 'jf_rollback'.$inter_id.$v['order_id'].$v['quote'];//rand(1000, 9999);暂时使用订单号作为随机码，防止发送多次，添加多次
                             $rollback_result = $api->point_rollback( $v['order_id'], $uu_code );
                             $rollback_result = json_encode( $rollback_result );
                             $log_txt.= "积分回滚推送，调用会员接口回滚结果{$rollback_result}，积分数{$v['quote']}，优惠明细". json_encode($v) . "-\n";

            	         }
            	         
    	             break;
    	             case self::TYPE_BALENCE:
    	                 //储值为下单时候扣减
                         if( $step== self::STATUS_USE ){
                             //下单成功推送储值扣减记录到队列（已改为下单马上扣储值，移至 calculate_discount() ）
                             
                         } elseif( $step== self::STATUS_CONSUME ){
                             if($debug) $log_txt.= "商品核销时，储值不做扣减。-\n";
                             
                         } elseif( $step== self::STATUS_ROLLBACK ){

                             //2016-12-13 luguihong 处理储值回滚
                            $member_info_id = '';
                            $uu_code= 'cz_rollback'.$inter_id.$v['order_id'].$v['quote'];//rand(1000, 9999);暂时使用订单号作为随机码，防止发送多次，添加多次
                            $module='soma';
                            $note = '套票商城订单，拉起支付15分钟内未支付的订单，储值回滚';
                            $rollback_result = $api->balence_rollback( $v['openid'], $member_info_id, $v['quote'], $uu_code, $module, $note );
                            $rollback_result = json_encode( $rollback_result );
                            $log_txt.= "储值回滚推送，调用会员接口回滚结果{$rollback_result}，储值数{$v['quote']}，优惠明细". json_encode($v) . "-\n";
                         }
    	                 
    	             break;
    	             case self::TYPE_REDUCE:
    	                 //满减
    	                 
    	             break;
    	             case self::TYPE_DISCOUNT:
    	                 //满额打折
    	                 
    	             break;
    	             case self::TYPE_REDUCE_RAND:
    	                  //随机立减
    	             break;
    	         }
	         }
	     }
	     if($debug) write_log($log_txt);
         return TRUE;
	}
    //下单时调用，锁定优惠项目
	public function used_discount($order_id, $inter_id)
	{
		//优惠券锁定不能用开关关闭，会导致重复使用
	    return $this->_handle_discount($order_id, $inter_id, self::STATUS_USE );//优惠券批量使用，这里需要改成多个mcid 9
	}
	//消费时调用，核销优惠项目
	public function consume_discount($order_id, $inter_id)
	{
	     if( self::OPEN_MEMBER ) return $this->_handle_discount($order_id, $inter_id, self::STATUS_CONSUME );
	}
	
    //消费时调用，核销优惠项目
    public function rollback_discount($order_id, $inter_id, $isOrderRollback=FALSE)
    {
         if( self::OPEN_MEMBER ) return $this->_handle_discount($order_id, $inter_id, self::STATUS_ROLLBACK, $isOrderRollback );
    }
    
	public function get_disount_by_filter($filter)
	{
	     $result= $this->find_all( $filter );
	     //echo $this->_shard_db()->last_query();die;
	     return $result;
	}

    //根据订单号获取优惠记录
    public function get_discount_by_orderIds( $inter_id, $orderIds, $start=NULL, $end=NULL, $select='*', $status=self::STATUS_ACTIVE )
    {
        if( !$inter_id || !$orderIds ){
            return array();
        }

        $db = $this->_shard_db_r('iwide_soma_r');
        if($start) {
            if( strlen($start)<=10 ) $start.= ' 00:00:00';
            $db->where('create_time >=', $start);
        }
        if($end) {
            if( strlen($end)<=10 ) $end.= ' 23:59:59';
            $db->where('create_time <', $end);
        }

        $table = $this->table_name($inter_id);
        return $db
                ->where('inter_id',$inter_id)
                ->where('status',$status)
                ->where_in('order_id',$orderIds)
                ->select($select)
                ->get($table)
                ->result_array();
    }

}
