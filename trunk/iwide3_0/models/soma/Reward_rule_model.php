<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Reward_rule_model extends MY_Model_Soma {

    const STATUS_ACTIVE  = 1;
    const STATUS_DISABLE = 2;

    const REWARD_TYPE_FIXED  = 1;  //固定
    const REWARD_TYPE_PERCENT= 2;  //百分比
    
    const REWARD_SOURCE_FIXED = 1;  //粉丝归属
    const REWARD_SOURCE_SALER = 2;  //分销分享
    const REWARD_SOURCE_FANS_SALER = 3; // 粉丝分销（泛分销）

    const GROUP_MODE_ALL = 1; // 全部人员
    const GROUP_MODE_SPEC = 2; // 指定分组

    public function get_status_label()
    {
        return array(
            self::STATUS_ACTIVE => '激活',
            self::STATUS_DISABLE=> '禁用',
        );
    }
    public function get_rule_type()
    {
        return array(
            self::SETTLE_DEFAULT => '立即购买',  //定义在 MY_Model_Soma.php
            self::SETTLE_GROUPON => '拼团购买',
            self::SETTLE_KILLSEC => '秒杀购买',
            //self::SETTLE_WHOLESALE => '大客户预订',
            //self::SETTLE_VOUCHER => '礼品卡券',
        );
    }

    public function get_reward_type()
    {
        return array(
            self::REWARD_TYPE_FIXED   => '按固定金额',
            self::REWARD_TYPE_PERCENT => '按百分比',
        );
    }
    
    public function get_reward_source()
    {
        return array(
            self::REWARD_SOURCE_FIXED => '分销粉丝购买',
            self::REWARD_SOURCE_SALER => '爆款推荐',
            self::REWARD_SOURCE_FANS_SALER => '泛分销',
        );
    }
    
    public function get_group_mode_label()
    {
        return array(
            self::GROUP_MODE_ALL => '全部分销员',
            self::GROUP_MODE_SPEC => '按分组绩效',
        );
    }

	public function get_resource_name()
	{
		return '分销奖励规则';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name($inter_id=NULL)
	{
		return $this->_shard_table('soma_reward_rule', $inter_id);
	}

	public function table_primary_key()
	{
	    return 'rule_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'rule_id'=> 'ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店ID',
            'name'=> '规则名称',
            'rule_type'=> '购买方式',
            'reward_source'=> '奖励来源',
            'group_mode'=> '绩效范围',
            'group_compose'=> '组别选择信息',
            'reward_type'=> '计算方式',
            'reward_rate'=> '奖励额度',
            'can_show_hip' => '显示分销提示',
            'product_ids'=> '生效商品',
            'start_time'=> '生效时间',
            'end_time'=> '失效时间',
            'create_time'=> '创建时间',
            'create_admin'=> '创建人',
            'update_time'=> '更改时间',
            'update_admin'=> '更改人',
            'sort'=> '优先级',
            'status'=> '状态',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'rule_id',
            'name',
            'rule_type',
            'reward_source',
            'reward_type',
            'reward_rate',
            'start_time',
            'end_time',
            //'create_time',
            'update_time',
            'update_admin',
            'sort',
            'status',
	    );
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
	    /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,combobox,validatebox. */
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
            'rule_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
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
                // 'form_ui'=> ' disabled ',
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
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $hotels,
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '15%',
                'form_ui'=> ' required ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'rule_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' required ',
                //'form_default'=> '0',
                'form_tips'=> '目前只支持部分规则',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_rule_type()
            ),
            'reward_source' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' required ',
                'form_default'=> self::REWARD_SOURCE_SALER,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_reward_source()
            ),
            'reward_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' required ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_reward_type()
            ),
            'group_mode' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' required ',
                'form_default'=> self::GROUP_MODE_ALL,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $this->get_group_mode_label()
            ),
            'group_compose' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'reward_rate' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' required step="0.0001" min="0.0001" ',
                'form_default'=> '0.0000',
                'form_tips'=> '注意按百分比时，"1.00"代表100%',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number',	//textarea|text|combobox|number|email|url|price
            ),
            'can_show_hip' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $this->get_status_can_label()
            ),
            'product_ids' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'start_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_tips'=> '不填写代表不限定开始时间',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'end_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_tips'=> '不填写代表不限定结束时间',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'update_admin' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '1',
                'form_tips'=> '越大越优先',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number',	//textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_status_label()
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'rule_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	public function _write_log( $content )
	{
	    $path= APPPATH. 'logs'. DS. 'soma'. DS. 'reward'. DS;
	    if( !file_exists($path) ) @mkdir($path, 0777, TRUE);
	    $this->write_log($content, $path. date('Y-m-d_H'). '.txt');
	}
	
	/**
	 * 获取有效的规则记录
	 * @return array
	 */
	public function get_reward_rule($inter_id, $rule_type=array(), $filter=array() )
	{
        $debug= FALSE;  //debug开关

        $db = $this->_shard_db_r('iwide_soma_r');
	    $table= $this->table_name($inter_id);
        $db->where('inter_id', $inter_id)
	        ->where('status', self::STATUS_ACTIVE);
	    if( count($rule_type)>0 ){
            $db->where_in('rule_type', $rule_type);
	    }
	    foreach ($filter as $k=>$v){
            $db->where( $k, $v );
	    }
	    $rules= $db->order_by('sort desc')
	                ->get($table)
                    ->result_array();

	    if($debug) $this->_write_log( $inter_id. '分销规则筛选语句：'. $db->last_query() );
	    
	    $time= time();
	    //过滤过期的规则
	    foreach ($rules as $k=>$v){
            if( empty($v['start_time']) || empty($v['end_time'])) unset($rules[$k]);
	        if( !empty($v['start_time']) && strtotime( $v['start_time'])> $time ) unset($rules[$k]);
	        if( !empty($v['end_time']) && strtotime( $v['end_time'])< $time ) unset($rules[$k]);
	    }
	    return $rules;
	}

    /**
     *
     * 计算所获绩效
     * @param $inter_id
     * @param Sales_order_model $order
     * @param $rules
     * @param null $source
     * @return array|bool
     * @author renshuai  <renshuai@mofly.cn>
     */
	public function calculate_reward_data($inter_id, $order, $rules, $source=NULL )
	{
	    $debug= TRUE;  //debug开关
	
	    if($source==NULL) $source= self::REWARD_SOURCE_SALER;
	     
	    if($order && $rules){
	        $order_id= $order->m_get('order_id');
	        $settlement= $order->m_get('settlement');
	        $business= $order->m_get('business');
	        $rule= NULL;
	
	        $log_txt= "分销绩效计算规则匹配 {$inter_id}：--\n购买订单：{$order_id}；";

	        $items = $order->get_order_items($business, $inter_id);
	        //var_dump($items);die;
	        $buy_pids = $order->array_to_hash($items, 'product_id');
	
	        foreach ($rules as $k=> $v) {
	            $is_match_buy_pids= empty($v['product_ids'])? TRUE: FALSE;
	            $match_pids= explode(',', $v['product_ids']);
	            foreach ($buy_pids as $sv){
	                if( in_array($sv, $match_pids) ) $is_match_buy_pids= TRUE;
	            }
	            if( !$is_match_buy_pids ) {
	                if($debug) $log_txt.= "--\n【产品不匹配】：". json_encode($v)
	                    . "--\n配置商品：{$v['product_ids']}；". "--\n购买商品：{$sv}；";
	
                } else if( $source!=$v['reward_source'] ) {
                    if($debug) $log_txt.= "--\n【奖励来源不匹配】：". json_encode($v)
                        . "--\n配置来源：{$v['reward_source']}；". "--\n统计来源：{$source}；";

                } else if( $settlement!= $v['rule_type'] ) {
                    if($debug) $log_txt.= "--\n【计算方式不匹配】：". json_encode($v)
                        . "--\n配置方式：{$v['rule_type']}；". "--\n统计方式：{$settlement}；";

                } else {
                    if($debug) $log_txt.= "--\n < 规则匹配成功！>：". json_encode($v);
                    $rule= $rules[$k]; //目前按照匹配到第一个产品的有效规则
                    break;
                }
            }
            //把规则过滤结果写入log
            if($debug) $this->_write_log( $log_txt );

            $log_txt = '';
            if( $rule && isset($rule['rule_type']) ){
                $match_pids= explode(',', $rule['product_ids']);

                $can_refund= self::CAN_REFUND_STATUS_FAIL;  //标记是否所有的商品都能够退款，如果是则选择定期核定业绩方式
                $reward_total= 0;
                $reward_detail= array();

                /** 使用优惠券之后的扣减？ **/
                $discont_total = $order->m_get('subtotal') - $order->m_get('grand_total');
                if($discont_total>0){
                    $log_txt .= "--\n【订单优惠扣减】：{$discont_total}";
                    $reward_detail['discount']= $discont_total;
                }
                
                switch ($rule['rule_type']){
                    case self::SETTLE_GROUPON:
                        $this->load->model('soma/Activity_groupon_model');
                        //假如不是自己的开的团，直接返回false
                        $group = $this->Activity_groupon_model->get_groupon_by_order_id($order_id,$inter_id);
                        if($order->m_get('openid') != $group['create_openid'] ){
                            return FALSE;
                        }
    
                        $this->load->model('soma/Product_package_model');
                        $price_fields= $this->Product_package_model->product_price_fieldname();
                        $price_field = $price_fields[$business];
    
                        foreach ($items as $k=>$v){
                            //                            $v['qty'] = 1; //拼团均是1件
                            if( empty($rule['product_ids']) || in_array($v['product_id'], $match_pids) ){
                                //匹配到的item进行计算
                                
                                // 综合分时住与普通商品的业绩计算
                                $qty = $v['qty'];
                                if($v['can_split_use'] == Soma_base::STATUS_TRUE && $v['use_cnt'] > 1) {
                                    $qty /= $v['use_cnt'];
                                }

                                if($rule['reward_type']==self::REWARD_TYPE_FIXED) {
                                    $reward_tmp_= $qty;
                                    $reward_tmp = $reward_tmp_ * $rule['reward_rate'];
    
                                } else {
                                    //计算规则减掉优惠额部分：（匹配商品价格* 数量  - 所有优惠金额 ）* 绩效   
                                    $reward_tmp_ = ($v[$price_field]* $qty- $discont_total);
                                    $reward_tmp = $reward_tmp_ * $rule['reward_rate'];
                                }
                                
                                $reward_total+= $reward_tmp;
                                $reward_detail[]= array(
                                    'qty'=> $qty,
                                    'product_id'=> $v['product_id'],
                                    'price'=> $v[$price_field],
                                    'reward_basic'=> $reward_tmp_,  //计算基数
                                    'reward_total'=> $reward_tmp,
                                );
                                $can_refund= self::CAN_REFUND_STATUS_SEVEN; //拼团不能即时核销
                            }
                        }
                    break;

                    case self::SETTLE_KILLSEC:
                    case self::SETTLE_DEFAULT:

                        $this->load->model('soma/Product_package_model');
                        $price_fields= $this->Product_package_model->product_price_fieldname();
                        $price_field = $price_fields[$business];
                        
                        if( $rule['rule_type']==self::SETTLE_KILLSEC ){
                            //对于秒杀，修正 order item，增加 'killsec_price'
                            $price_field = $price_fields['killsec'];
                        }
                        
                        foreach ($items as $k=>$v){
                            if( empty($rule['product_ids']) || in_array($v['product_id'], $match_pids) ){
                                //匹配到的item进行计算
                                
                                // 综合分时住与普通商品的业绩计算
                                $qty = $v['qty'];
                                if($v['can_split_use'] == Soma_base::STATUS_TRUE && $v['use_cnt'] > 1) {
                                    $qty /= $v['use_cnt'];
                                }
                                
                                if($rule['reward_type']==self::REWARD_TYPE_FIXED) {
                                    $reward_tmp_= $qty;
                                    $reward_tmp = $reward_tmp_ * $rule['reward_rate'];
    
                                } else {
                                    //计算规则减掉优惠额部分：（匹配商品价格* 数量  - 所有优惠金额 ）* 绩效   
                                    $reward_tmp_ = ($v[$price_field]* $qty- $discont_total);
                                    $reward_tmp = $reward_tmp_ * $rule['reward_rate'];
                                }
                                $reward_total+= $reward_tmp;
                                $reward_detail[]= array(
                                    'qty'=> $qty,
                                    'product_id'=> $v['product_id'],
                                    'price'=> $v[$price_field],
                                    'reward_basic'=> $reward_tmp_,  //计算基数
                                    'reward_total'=> $reward_tmp,
                                );
                                /* 修改秒杀支持退款，跟随产品属性 2017年6月6日 by fengzhongcheng
                                if( $rule['rule_type']==self::SETTLE_KILLSEC ){
                                    //对于秒杀，修正 order item，增加 'killsec_price'
                                    $reward_detail['killsec_instance']= $order->m_get('killsec_instance');
                                    $can_refund= FALSE; //秒杀不能退款/要即时核销
                                    
                                } else {
                                    //判断细单能不能进行退款
                                    if( $v['can_refund']==self::STATUS_TRUE )
                                        $can_refund= TRUE;  //可退款，定期核定
                                }
                                */
                                if( $rule['rule_type']==self::SETTLE_KILLSEC ){
                                    $reward_detail['killsec_instance']= $order->m_get('killsec_instance');
                                }
                                $can_refund = $v['can_refund'];
                            }
                        }
                    break;
                }
                //echo $reward_total;die;

                /** 没有符合的商品，不记录业绩 */
                if( count($reward_detail)==0 )
                    return FALSE;

                $data= array();
                $data['rule_type']= $settlement;
                $data['reward_source']= $source;
                $data['rule_id']= $rule['rule_id'];         //匹配规则ID
                $data['reward_type']= $rule['reward_type']; //规则中计算方式
                $data['reward_rate']= $rule['reward_rate']; //规则中的比例
                $data['reward_total']= round($reward_total, 2);       //累计绩效
                $data['reward_detail']= json_encode($reward_detail);     //绩效构成

                /** 总状态为可退款则采用定期核定，否则立即核定 */
                $data['reward_status']= $can_refund;

                //把log写入文件
                $log_txt .= "--\n【绩效计算结果】：". json_encode($data);
                if($debug) $this->_write_log( $log_txt );

                return $data;

            } else {
                return FALSE;
            }
	
        } else {
            return FALSE;
        }
    }

    /**
     * Gets the reward rules.
     *
     * @param      string  $inter_id     The inter identifier
     * @param      string  $saler_group  The saler group
     *
     * @return     <type>  The reward rules.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getRewardRules($inter_id, $saler_group = '')
    {
        $this->soma_db_conn_read->select('*')->from($this->table_name($inter_id));
        $this->soma_db_conn_read->where('inter_id', $inter_id);
        $this->soma_db_conn_read->where('start_time <=', date('Y-m-d H:i:s'));
        $this->soma_db_conn_read->where('end_time >=', date('Y-m-d H:i:s'));
        $this->soma_db_conn_read->where('status', self::STATUS_ACTIVE);
        
        $group_arr = empty($saler_group) ? array() : explode(',', $saler_group);
        if(!empty($group_arr))
        {
            $this->soma_db_conn_read->group_start();
            
            $this->soma_db_conn_read->group_start();
            $this->soma_db_conn_read->where('group_mode', self::GROUP_MODE_SPEC);
            $this->soma_db_conn_read->group_start();
            foreach ($group_arr as $group)
            {
                $this->soma_db_conn_read->or_like('group_compose', ',' . $group . ',');
            }
            $this->soma_db_conn_read->group_end();
            $this->soma_db_conn_read->group_end();
            
            $this->soma_db_conn_read->or_where('group_mode', self::GROUP_MODE_ALL);
            
            $this->soma_db_conn_read->group_end();
        }
        else
        {
            $this->soma_db_conn_read->where('group_mode', self::GROUP_MODE_ALL);
        }
        
        // 优先级大的优先，同级的创建时间晚（id大）的优先
        $this->soma_db_conn_read->order_by('sort desc, rule_id desc');

        $data = $this->soma_db_conn_read->get()->result_array();
        // echo $this->soma_db_conn_read->last_query();exit;

        return $data;
    }
	
}
