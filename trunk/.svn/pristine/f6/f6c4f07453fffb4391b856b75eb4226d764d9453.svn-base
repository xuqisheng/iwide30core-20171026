<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_rule_model extends MY_Model_Soma {

    const OPEN_USE_ACTIVITY = TRUE;  //遇到活动异常时，用FALSE关闭接口同步
    const OPEN_USE_BALENCE = TRUE; //遇到储值异常时，用FALSE关闭接口同步
    const OPEN_USE_POINTS = TRUE;  //遇到积分异常时，用FALSE关闭接口同步
    
    const RULE_TYPE_POINT   = 30;  //积分
    const RULE_TYPE_BALENCE = 40;  //与 Sales_order_discount_model常量保持一致
    const RULE_TYPE_REDUCE  = 51;  //自然优先级越大，采用越大的序号
    const RULE_TYPE_DISCOUNT= 52;  //
    const RULE_TYPE_REDUCE_RAND= 55;
    
    const LIMIT_TYPE_FIXED = 1;  //固定比例
    const LIMIT_TYPE_SCOPE = 2;  //比例范围

    const POINT_MODULE_VIP = 'vip';
    const POINT_MODULE_SOMA = 'soma';
    const POINT_MODULE_HOTEL = 'hotel';
    const POINT_MODULE_MEMBER = 'member';
    const POINT_MODULE_MALL = 'shop';

    public function get_point_module()
    {
        return array(
            self::POINT_MODULE_SOMA => '社交商城',
            self::POINT_MODULE_HOTEL => '酒店订房',
            self::POINT_MODULE_VIP => '新版会员',
            //self::POINT_MODULE_MEMBER => '旧版会员',
            //self::POINT_MODULE_MEMBER => '旧版商城',
        );
    }
    
    public function get_settle_label()
    {
        return array(
            self::SETTLE_DEFAULT => '普通购买',
            // self::SETTLE_GROUPON => '拼团购买',
            // self::SETTLE_KILLSEC => '秒杀购买',
        );
    }

    const SCOPE_WIDE= 1;
    const SCOPE_PART= 2;
    
    public function get_scope_label()
    {
        return array(
            self::SCOPE_PART=> '部分适用',
            self::SCOPE_WIDE=> '全部适用',
        );
    }

    /**
     * 几种促销规则的排斥规则为：
     *   1，储值、积分、满减只能选择其一
     *   2，优惠券可以与三者合并使用
     *   3，如果没有定义对应规则，或者没有选择商品，不能使用
     * @return multitype:string
     */
    public function get_rule_label()
    {
        return array(
            self::RULE_TYPE_POINT   => '积分使用规则',  //积分规则的使用前端页面尚存在问题，先关闭使用
            self::RULE_TYPE_BALENCE => '储值使用规则',
            self::RULE_TYPE_REDUCE  => '满减现金规则',
            self::RULE_TYPE_DISCOUNT => '满打折规则',
            //self::RULE_TYPE_REDUCE_RAND  => '随机立减规则',
        );
    }

    public function get_auto_rule()
    {
        return array(self::RULE_TYPE_DISCOUNT, self::RULE_TYPE_REDUCE, self::RULE_TYPE_REDUCE_RAND );
    }
    public function get_asset_rule()
    {
        return array(self::RULE_TYPE_POINT, self::RULE_TYPE_BALENCE );
    }
    
    public function get_limit_label()
    {
        return array(
            self::LIMIT_TYPE_FIXED => '固定比例',
            self::LIMIT_TYPE_SCOPE => '浮动比例',
        );
    }
    
    /**
     * @return string the associated database table name
     */
    public function table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_sales_base_rule', $inter_id);
    }
    public function table_name_r($inter_id=NULL)
	{
	    return $this->_shard_table_r('soma_sales_base_rule', $inter_id);
    }

    public function rule_product_table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_sales_base_product', $inter_id);
    }
    public function rule_product_table_name_r($inter_id=NULL)
    {
        return $this->_shard_table_r('soma_sales_base_product', $inter_id);
    }
    
	public function get_resource_name()
	{
		return '促销规则';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function table_primary_key()
	{
	    return 'rule_id';
	}
	
	public function unaddslashes_field()
	{
	    return array( 'bonus_size' );
	}
	
	public function attribute_labels()
	{
		return array(
            'rule_id'=> 'ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店ID',
            'settlement'=> '结算类型',
            'rule_type'=> '规则类型',
            'name'=> '活动名称',
            'name_en' => 'Name',
            'rule_detail'=> '规则明细',
            'limit_type'=> '限制方式',
            'limit_percent'=> '最高比例',  //含义：0% - N%
            'lease_percent'=> '最低比例',
            'over_limit'=> '最高金额',  //含义：超过多少不能用，默认0
            'lease_cost'=> '起减金额',  //含义：满多少钱才能用，默认0
            'reduce_cost'=> '满减/满折', //含义：减多少
            'bonus_size'=> '积分比例', //1积分等值x元
            'can_use_point'=> '同时用积分',
            'can_use_coupon'=> '同时用优惠券',
            'can_use_balence'=> '同时用储值',
            'can_use_reduce'=> '同时用满减',
            'require_password'=> '需要支付密码',
            'start_time'=> '开始时间',
            'end_time'=> '结束时间',
            'create_time'=> '创建时间',
            'create_admin'=> '创建人',
            'update_time'=> '更改时间',
            'update_admin'=> '更改人',
            'scope'=> '适用范围',
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
            'rule_detail',
            'settlement',
            'rule_type',
            //'limit_type',
            //'limit_percent',
            //'lease_percent',
            //'lease_cost',
            //'reduce_cost',
            //'can_use_point',
            //'can_use_coupon',
            //'can_use_balence',
            'start_time',
            'end_time',
            'sort',
            'status',
	    );
	}

	/**
	 * name的变动规则：
	 * rule_type的变动规则：
	 *     积分/储值时隐藏 reduce_cost
	 *     满减/立减时，隐藏limit_type, limit_percent
	 * 
	 */
	
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
	    $Somabase_util= Soma_base::inst();
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
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox',
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'settlement' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_settle_label()
            ),
            'rule_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_rule_label()
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '12%',
                'input_unit'=> '前台显示',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'name_en' => array(
                'grid_ui'=> '',
                'grid_width'=> '12%',
                // 'input_unit'=> 'Front',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'rule_detail' => array(
                'grid_ui'=> '',
                'grid_width'=> '35%',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'limit_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_limit_label()
            ),
            'limit_percent' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' step="0.0001" max="100" ',
                'input_unit'=> ' % ',
                'form_default'=> '100',
                'type'=>'number',	//textarea|text|combobox|number|email|url|price
            ),
            'lease_percent' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' step="0.0001" min="0" ',
                'input_unit'=> ' % ',
                'form_default'=> '0',
                'type'=>'number',	//textarea|text|combobox|number|email|url|price
            ),
            'over_limit' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_default'=> '0',
                'input_unit'=> '元(0元不生效)',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'lease_cost' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'input_unit'=> '元',
                'form_default'=> '0',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'reduce_cost' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'input_unit'=> '元 / 折',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'bonus_size' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_tips'=> '1积分等值x元',
                'form_default'=> '1',
                'form_hide'=> TRUE,
                'type'=>'textarea',	//textarea|text|combobox|number|email|url|price
            ),
            'can_use_coupon' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> Soma_base::STATUS_TRUE,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_status_can_label()
            ),
            'can_use_point' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> Soma_base::STATUS_FALSE,
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_status_can_label()
            ),
            'can_use_balence' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> Soma_base::STATUS_FALSE,
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_status_can_label()
            ),
            'require_password' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> Soma_base::STATUS_FALSE,
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_status_can_label()
            ),
            'start_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' required ',
                'form_default'=> date('Y-m-d H:i:s'),
                'form_tips'=> '不填写代表不限定开始时间',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'end_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' required ',
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
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'create_admin' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'update_admin' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' step="1" min="1" ',
                'form_default'=> '1',
                'form_tips'=> '越大越优先',
                'input_unit'=> '优先级仅生效于同类规则之内',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number',	//textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> Soma_base::get_status_options(),
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

	
	//保存优惠券适用的商品
	public function product_save( $post, $inter_id )
	{
	    if( empty( $post ) || !$inter_id ){
	        return FALSE;
	    }
	
	    $this->_shard_db($inter_id)->trans_begin ();
        $pk = $this->table_primary_key();

	    //删除旧数据
	    $filter = array();
	    $filter['rule_id'] = isset( $post['rule_id'] ) && !empty( $post['rule_id'] ) ? $post['rule_id'] : NULL;
	    $filter['inter_id'] = $inter_id;
	    $rule_product_table_name = $this->rule_product_table_name( $inter_id );
	
	    $this->_shard_db( $inter_id )->where( $filter )->delete( $rule_product_table_name );
	
	    //以下代码是添加新数据
	
	    $this->load->model('soma/Product_package_model','ProductModel');
	    $ProductModel = $this->ProductModel;
	
	    //修改券表的适用状态条件
	    $table_name = $this->table_name( $inter_id );
	    $cou_where = array();
	    $cou_where['inter_id'] = $inter_id;
	    $cou_where['rule_id'] = isset( $post['rule_id'] ) && !empty( $post['rule_id'] ) ? $post['rule_id'] : NULL;
	
	    $cou_data = array();
	
	    //取出要添加的数据
	    $p_type = isset( $post['p_type'] ) && !empty( $post['p_type'] ) ? strtolower( $post['p_type'] ) : NULL;
	    if( $p_type == 'ids' ){
	        //根据商品id添加
	
	        $product_ids = isset( $post['product_ids'] ) && !empty( $post['product_ids'] ) ? explode( ',', $post['product_ids'] ) : NULL;
	        if( !$product_ids ){
	            return FALSE;
	        }
	
	        //查找出商品
	        $products = $ProductModel->get_product_package_by_ids( $product_ids, $inter_id );
	        if( empty( $products ) ){
	            return FALSE;
	        }
	
	        $cou_data['scope'] = self::SCOPE_PART;//部分适用
	
	    }elseif( $p_type == 'all' ){
	
	        //添加全部商品
	        $catId = '';
	        $products = array();
	        // $products = $ProductModel->get_product_package_list(  $catId, $inter_id );
	        if( empty( $products ) ){
	            // return FALSE;
	        }
	
	        $cou_data['scope'] = self::SCOPE_PART;//部分适用
	
	    }elseif( $p_type == 'all_use' ){
	        //全部适用，以后添加的商品也适用，修改券表的适用范围
	
	        $cou_data['scope'] = self::SCOPE_WIDE;//全部适用
	
	        $products = array();
	
	    }else{
	        return FALSE;
	    }
	
	    $post['scope'] = isset( $cou_data['scope'] ) ? $cou_data['scope'] : $post['scope'];
        //修改券表状态
	    if( !empty( $cou_data ) && isset( $cou_where['rule_id'] ) ){
            $this->load($post[$pk])->m_sets($post)->m_save();
	        // $this->_shard_db( $inter_id )->where( $cou_where )->update( $table_name, $cou_data );
	    }else{
            $last_insertid= $this->m_sets( $post )->m_save($post);
        }
	
	    //组装要添加的数据
	    $insert_data = array();
	    if( $products ){
	        foreach ($products as $k => $v) {
	            $data = array();
	            $data['inter_id'] = $inter_id;
                $data['rule_id'] = isset( $post['rule_id'] ) && !empty( $post['rule_id'] ) ? $post['rule_id'] : $last_insertid;
	            $data['hotel_id'] = isset( $post['hotel_id'] ) && !empty( $post['hotel_id'] ) ? $post['hotel_id'] : NULL;
	            $data['product_id'] = $v['product_id'];
                $data['name'] = $v['name'];
	            // $data['qty'] = $v['stock'];//买满多少生效
	            $data['status'] = $v['status'];
	            $insert_data[] = $data;
	        }
	    }
	
	    //添加新数据
	    if( !empty( $insert_data ) ){
	
	        $this->_shard_db( $inter_id )->insert_batch( $rule_product_table_name, $insert_data );
	    }
	
	
	    $this->_shard_db($inter_id)->trans_complete();
	     
	    if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
	        $this->_shard_db($inter_id)->trans_rollback();
	        return FALSE;
	         
	    } else {
	        $this->_shard_db($inter_id)->trans_commit();
	        return TRUE;
	    }
	
	
	}
	
	/**
	 * 根据会员模块返回的数据，查找适用的商品
	 * @see Sales_order_discount_model::calculate_discount()
	 * @param string $discount
	 * @return boolean|multitype:unknown
	 */
	public function get_card_product( $discount=NULL ){
	    if( !$discount ){
	        return FALSE;
	    }
	    //取出数据
	    $return_data = array();
	    $products = $this->get_rule_product_list( $discount['rule_id'], $discount['inter_id'] );
	    foreach ($products as $sk => $sv) {
	        $return_data[$sv['product_id']] = $sv;
	    }
	    return $return_data;
	}
	
	//取出已选择商品的ID数组
	public function get_rule_product_list( $rule_id, $inter_id )
	{
	    if( !$rule_id || !$inter_id ){
	        return FALSE;
	    }

        $db = $this->_shard_db_r('iwide_soma_r');

	    $filter = array();
	    $filter['inter_id'] = $inter_id;
	    $filter['status'] = self::STATUS_TRUE;
	    $table_name = $this->rule_product_table_name( $inter_id );
        $db->where( $filter );
	
	    if( is_array($rule_id) ){
            $db->where_in('rule_id', $rule_id);
	    } else {
            $db->where('rule_id', $rule_id);
	    }
	    $result= $db->get( $table_name )->result_array();

	    return $result;
	}
	
	//找出公众号下所有全适用券
	public function get_wide_scope_rule( $inter_id, $hash= FALSE )
	{
	    $table_name = $this->table_name( $inter_id );
	    $result= $this->_shard_db_r('iwide_soma_r')
                        ->where('scope', self::SCOPE_WIDE )
	                    ->get($table_name)
                        ->result_array();
	    if($hash){
	        return $this->array_to_hash($result, 'card_name', 'rule_id');
	
	    } else {
	        return $result;
	
	    }
	}

	/**
	 * 获取当前有效的规则配置，包含优先级，时间排除
	 * @param String $inter_id
	 * @param Sales_order_model $order
	 */
	public function get_available_rule( $inter_id )
	{
	    $now= time();
	    $table= $this->table_name($inter_id);
	    $rule= $this->_shard_db_r('iwide_soma_r')
                    ->order_by('rule_type desc, sort desc')
	                ->where('inter_id', $inter_id)
                    ->where('status', self::STATUS_TRUE)
                    //->where('start_time<', $now)->where('end_time>', $now )
                    ->get($table)
                    ->result_array();

	    $table= $this->rule_product_table_name($inter_id);
	    $product= $this->_shard_db_r('iwide_soma_r')
                        ->where('inter_id', $inter_id)
                        ->where('status', self::STATUS_TRUE)
                        ->get($table)
                        ->result_array();

	    $rule_pids= array();
	    foreach ($product as $k=>$v){
	        $rule_pids[$v['rule_id']][$v['product_id']]= $v['qty'];
	    }
	    
	    foreach ($rule as $k=>$v){
	        if( $v['start_time'] && strtotime($v['start_time'])> $now ){
	            unset($rule[$k]);
	        } else if( $v['end_time'] && strtotime($v['end_time'])< $now ){
	            unset($rule[$k]);
	        } else {
	            if( isset($rule_pids[$v['rule_id']]) ){
	                $rule[$k]['products'] = $rule_pids[$v['rule_id']];
	            }
	        }
	    }
	    return $rule;
	}

	//打折计算公式
	public function reduce_discount_cal($discount, $subtotal )
	{
	    return round($subtotal * (10- $discount) /10, 2);
	}
	
	/**
	 * 用于下单时，根据配置表、用户、购物清单得出目前可以享受的优惠信息
	 * @param String $inter_id
	 * @param String $openid
	 * @param Array $product_array
	 * @param String $subtotal
	 * @param String $settlement
	 * @return Array 二维数组，返回结果格式如下

array(  //key相同为二选一显示, quote为使用额度，如5000积分，scale为比例值, can_use_coupon为1指限制使用优惠券
    'auto_rule'=> array('rule_type'=>51, 'name'=>'xx满减活动', 'reduce_cost'=>'￥10.00', 'least_cost'=>'100.00', 'can_use_coupon'=>2 ),
    'auto_rule'=> array('rule_type'=>51, 'name'=>'xx满打折活动', 'reduce_cost'=>'￥30.00', 'least_cost'=>'100.00', 'can_use_coupon'=>2 ),
    'auto_rule'=> array('rule_type'=>52, 'name'=>'xx随机立减', 'reduce_cost'=>'￥3.00', 'least_cost'=>'0', 'can_use_coupon'=>2 ),
    'cal_rule'=> array('rule_type'=>30, 'quote'=>'5000', 'reduce_cost'=>'￥50.00', 'can_use_coupon'=>1 ),
    'cal_rule'=> array('rule_type'=>40, 'quote'=>'100', 'reduce_cost'=>'￥100.00', 'can_use_coupon'=>1 ),
)
	 */
	public function get_discount_rule($inter_id, $openid, $product_array, $subtotal, $settlement )
	{
	    $debug= TRUE;
	    if($debug) {
	        $this->load->helper('soma/package');
	        $log_txt= "规则搜索：订单的原总金额￥{$subtotal}--\n";
	    }
	    
	    $setting= $this->get_available_rule( $inter_id );
	    if($debug) $log_txt.= '规则加载顺序：'. json_encode($this->array_to_hash($setting, 'name', 'rule_id'), JSON_UNESCAPED_UNICODE). "--\n";
        //print_r($setting);die;

	    $auto_rule= $cal_rule= NULL;
	    switch ($settlement) {
	        case 'default':
	            $reduce_cost= 0;
	            //循环查找活动类规则
	            foreach ($setting as $k=>$v){
	                if( ! in_array($v['rule_type'], $this->get_auto_rule() ) ) {
	                    continue;
	                }
	                if( ! self::OPEN_USE_ACTIVITY ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 活动类规则开关关闭，跳过此规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                if( $auto_rule ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 活动类规则已经应用，跳过此规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                
	                //不符合满减最低额度，排除此规则
	                if( $v['lease_cost'] && $v['lease_cost']> $subtotal ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 订单额度未满最低线，跳过此活动规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                if( isset($v['over_limit']) && $v['over_limit'] > 0 && $v['over_limit'] < $subtotal  ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 订单额度超过最高线，跳过此活动规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                
	                //产品是否符合条件
	                $is_wide_scope= ($v['scope']==self::STATUS_TRUE)? TRUE: FALSE;
	                $pqtys= isset($v['products'])? $v['products']: array();
	                $is_match_product= $this->_match_link_product($product_array, $pqtys, $is_wide_scope);
	                if( ! $is_match_product ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 购买商品不符合条件，跳过此活动规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
                    if( $v['rule_type']==self::RULE_TYPE_REDUCE ){
                        $reduce_cost= isset($v['reduce_cost'])? $v['reduce_cost']: '0';
                        $lease_cost= isset($v['rule_type'])? $v['lease_cost']: '0';
                        $can_use_coupon= $v['can_use_coupon'];
                        
                    } else if( $v['rule_type']==self::RULE_TYPE_DISCOUNT ){
                        $reduce_cost= isset($v['reduce_cost'])? 
                            $this->reduce_discount_cal($v['reduce_cost'], $subtotal) : '0';
                        $lease_cost= isset($v['rule_type'])? $v['lease_cost']: '0';
                        //$can_use_coupon= Soma_base::STATUS_FALSE;  //满x打折功能暂时无法使用优惠券
                        $can_use_coupon= $v['can_use_coupon'];
                        
                    } else if( $v['rule_type']==self::RULE_TYPE_REDUCE_RAND ){
//随机立减规则待定
                        $reduce_cost= '0';
                        $lease_cost= '0';
                        $can_use_coupon= $v['can_use_coupon'];
                    } 
	                //全部符合抓取规则
	                $auto_rule= array(
	                    'id'=> $v['rule_id'],
	                    'name'=> $v['name'],
                        'name_en'=> $v['name_en'],
	                    'least_cost'=> $lease_cost,
	                    'reduce_cost'=> $reduce_cost,
	                    'can_use_coupon'=> $can_use_coupon,
	                    'rule_type'=> $v['rule_type'],
	                );
	                if($debug) $log_txt.= $v['rule_id']. ', 活动规则检测通过：'. $v['rule_detail']. "--\n";
	            }
	            $else_total = $subtotal- $reduce_cost;
	            $else_total = ($else_total<0)? 0: $else_total;

                // 储值积分余额不足信息
                $can_not_use_info = array();

	            //循环查找资产类规则
	            foreach ($setting as $k=>$v){
	                if( ! in_array($v['rule_type'], $this->get_asset_rule() ) ) {
	                    continue;
	                }
	                if( ! self::OPEN_USE_POINTS && $v['rule_type']== self::RULE_TYPE_POINT ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 积分规则开关关闭，跳过此规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                if( (! self::OPEN_USE_BALENCE 
                        || $product_array[0]['type'] == MY_Model_Soma::PRODUCT_TYPE_BALANCE )
                        && $v['rule_type']== self::RULE_TYPE_BALENCE ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 储值类规则开关关闭，跳过此规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                if( $cal_rule ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 资产类规则已经应用，跳过此规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                
	                //产品是否符合条件
	                $is_wide_scope= ($v['scope']==self::STATUS_TRUE)? TRUE: FALSE;
	                $pqtys= isset($v['products'])? $v['products']: array();
	                $is_match_product= $this->_match_link_product($product_array, $pqtys, $is_wide_scope);
	                if( ! $is_match_product ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 购买商品不符合条件，跳过此资产规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                
	                //计算个人资产能否付多少钱
    	            if( !isset($api) ){
            	        $this->load->library('Soma/Api_member');
            	        $api= new Api_member($v['inter_id']);
            	        $result= $api->get_token();
            	        $api->set_token($result['data']);
            	    }
            	    if( $v['rule_type']== self::RULE_TYPE_BALENCE ){
            	        $info= $api->balence_info( $openid );
            	        if($debug) $log_txt.= $v['rule_id']. ', 用户储值：'. $info['data']. "--\n";
            	        
            	        $scale= $api->balence_scale( $openid );
            	        $eq_money= $api->balence_scale_convert($scale, $info['data'], TRUE );
            	        if($debug) $log_txt.= $v['rule_id']. ', 储值比例'. json_encode($scale). '，估计可抵扣￥'. $eq_money. "--\n";
            	        
            	    } else if( $v['rule_type']== self::RULE_TYPE_POINT ){
            	        $info= $api->point_info( $openid );
            	        if($debug) $log_txt.= $v['rule_id']. ', 用户积分：'. $info['data']. "--\n";
            	        
            	        $scale= $api->point_scale( $openid, $v['bonus_size'] );
            	        if($debug && $scale===FALSE) $log_txt.= $v['rule_id']. ', 会员级别识别有误，无法判断积分比例--\n';
            	        
            	        $eq_money= $api->point_scale_convert($scale, $info['data'], TRUE );
            	        if($debug) $log_txt.= $v['rule_id']. ', 积分比例'. json_encode($scale). '，估计可抵扣￥'. $eq_money. "--\n";
            	        
            	    } 
            	    
	                //计算个人资产是否足够
            	    $use_quote= $this->_cal_use_quote($v, $eq_money, $else_total);   //计算出实际可以抵扣的金额
            	    // var_dump($use_quote);die;   //该订单所用金额
            	    if( ! $use_quote ) {
            	        if($debug) $log_txt.= $v['rule_id']. ", 余下金额{$else_total} 需配备资产等值￥". $use_quote. "--\n";

                        // 储值积分余额不足的情况
                        // 优先显示储值余额不足提示
                        if(!isset($can_not_use_info['rule_type'])
                            || $can_not_use_info['rule_type'] == self::RULE_TYPE_POINT) {
                            $r_type = ($v['rule_type']== self::RULE_TYPE_BALENCE) ? $this->show_name : '积分';
                            $can_not_use_info = array(
                                'rule_type' => $v['rule_type'],
                                'label'     => $r_type,
                                'can_use'   => Soma_base::STATUS_FALSE,
                            );
                        }

            	        continue;
            	    }
            	    else {
                        // 清空资产余额不足提示
                        $can_not_use_info = array();

                	    if( $v['rule_type']== self::RULE_TYPE_BALENCE ){
                	        $quote= $api->balence_scale_convert($scale, $use_quote, FALSE ); //实际花费的 储值
            	            if($debug) $log_txt.= $v['rule_id']. ", 需对应支付储值 ". $quote. "--\n";
                	        
                	    } else if( $v['rule_type']== self::RULE_TYPE_POINT ){
                	        $quote= $api->point_scale_convert($scale, $use_quote, FALSE ); //实际花费的积分 
                	        
                	        //当抵扣积分<1的时候，
                	        if($quote<1){
                	            if($debug) $log_txt.= $v['rule_id']. ", 积分使用数量小于1分，规则跳过--\n";
                	            continue;
                	            
                	        } else {
                	            $quote= intval($quote);
                	            if($debug) $log_txt.= $v['rule_id']. ", 需对应支付积分 ". $quote. "--\n";
                	        }
                	    }
            	    }
	                //全部符合抓取规则
	                $cal_rule= array(
	                    'id'=> $v['rule_id'],
	                    'name'=> $v['name'],
                        'name_en'=> $v['name_en'],
	                    'quote'=> $quote,              //实际花费的资产
	                    'reduce_cost'=> $use_quote,    //实际抵扣的金额
	                    'scale'=> $scale,              //(积分/储值)兑换金额比例
                        'can_use_coupon'=> $v['can_use_coupon'],
	                    'require_password'=> $v['require_password'],
	                    'rule_type'=> $v['rule_type'],
                        'can_use' => Soma_base::STATUS_TRUE,
	                );
            	    if( isset($scale['data']) ) $cal_rule['scale']= $scale['data'];
            	    
	                if($debug) $log_txt.= $v['rule_id']. ', 资产规则检测通过：'. $v['rule_detail']. "--\n";
	            }

                // var_dump($cal_rule, $can_not_use_info);die;

                if(!is_array($cal_rule)
                    || count($cal_rule) == 0) {
                    if(count($can_not_use_info) > 0) {
                        $cal_rule = $can_not_use_info;
                    }
                }
	        break;
	    }
	    if($debug) {
	        $log_txt.= '所有规则匹配完毕！';
	        write_log($log_txt);
	    }
	    
	    $base_info= array('slt'=> $settlement, 'total'=> $subtotal, );
	    return array( 'auto_rule'=> $auto_rule, 'cal_rule'=>$cal_rule, 'base_info'=> $base_info, );
	}
	
	/**
	 * 根据配置规则筛选不能并存的规则，包含产品检测，扣减比例判断
	 * @param String $inter_id
	 * @param Array $discount_array
	 * @param Array $product_array
	 * @param Array $subtotal
	 */
	public function filter_base_rule( $inter_id, $order )
	{
	    //原参数 $inter_id, $discount_array, $product_array, $subtotal, $settlement='default' )
	    $discount_array= $order->discount;
	    $product_array= $order->product;
	    $subtotal= $order->subtotal;
	    $settlement= $order->settlement? $order->settlement: 'default';
	    $openid= $order->customer->openid;
	    
	    $debug= TRUE;
	    if($debug) {
	        $this->load->helper('soma/package');
	        $log_txt= "下单规则过滤：订单的原总金额￥{$subtotal}--\n";
	    }
	    
        //积分规则不适用任何规则，且并非通用规则排除此规则
        if( $product_array[0]['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT ) {
            return $discount_array;
        }

	    $avali_rule= $this->get_available_rule($inter_id);
	    if($debug) $log_txt.= '规则加载顺序：'. json_encode($this->array_to_hash($avali_rule, 'name', 'rule_id'), JSON_UNESCAPED_UNICODE). "--\n";
	    
	    $this->load->model('soma/Sales_order_discount_model');
	    
	    $is_use_coupon= FALSE;
	    foreach ($discount_array as $k=> $v){//优惠券批量使用，这里需要改成多个mcid 6
	        //里面是否包含优惠券
	        if($v['discount_type']== Sales_order_discount_model::TYPE_COUPON )
	            $is_use_coupon= TRUE;
	    }
	    
	    //$pqtys= array();
	    //foreach ($product_array as $k=> $v){
	        //购买商品 pid=> qty 数组
	    //    $pqtys[$v['product_id']]= $v['qty'];
	    //}

	    $use_auto_rule= FALSE;  //自动结算规则标记
	    $use_cal_rule= FALSE;   //储值/积分规则标记
	    $reduce_cost= 0;
	    foreach ($avali_rule as $k=>$v){
	        if( isset($v['settlement']) && $settlement!=$v['settlement'] ){
	            if($debug) $log_txt.= $v['rule_id']. ', 购买方式不匹配，跳过此规则：'. $v['rule_detail']. "--\n";
	            continue;
	        }
            
	        $else_total = $subtotal- $reduce_cost; //循环之前除去上次活动扣减之外的金额
	        
	        $is_wide_scope= ($v['scope']==self::STATUS_TRUE)? TRUE: FALSE;
	        $pqtys= isset($v['products'])? $v['products']: array();
	        $is_match_product= $this->_match_link_product($product_array, $pqtys, $is_wide_scope);
	        
	        switch ( $v['rule_type'] ){
	            case self::RULE_TYPE_REDUCE_RAND:
	                //还没开通该类优惠规则
	                unset($discount_array[$v['rule_type']]);
	            break;

	            case self::RULE_TYPE_REDUCE:
	            case self::RULE_TYPE_DISCOUNT:
	                if( ! self::OPEN_USE_ACTIVITY ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 活动类规则开关关闭，跳过此规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                if( $use_auto_rule ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 活动类规则已经应用，跳过此规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                //不符合满减最低额度，排除此规则
	                if( $v['lease_cost'] && $v['lease_cost']> $subtotal ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 订单额度未满最低线，跳过此活动规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
                    //20170206 luguihong 优惠规则支付和显示要一致
                    if( isset($v['over_limit']) && $v['over_limit'] > 0 && $v['over_limit'] < $subtotal  ) {
                        if($debug) $log_txt.= $v['rule_id']. ', 订单额度超过最高线，跳过此活动规则：'. $v['rule_detail']. "--\n";
                        continue;
                    }

	                //有用优惠券排除此规则
	                if( $is_use_coupon && $v['can_use_coupon']== self::STATUS_CAN_NO ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 产生优惠券使用冲突，跳过此活动规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                //购买商品不符合，且并非通用规则排除此规则
	                if( ! $is_match_product ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 购买商品不符合条件，跳过此活动规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                if($v['rule_type']==self::RULE_TYPE_REDUCE){
	                    //追加优惠记录，并标记，用于资产类循环时冲减
	                    $reduce_cost= $v['reduce_cost'];
	                    
	                } else if($v['rule_type']==self::RULE_TYPE_DISCOUNT){
	                    //追加优惠记录，并标记，用于资产类循环时冲减
	                    $reduce_cost= $this->reduce_discount_cal($v['reduce_cost'], $subtotal);
	                    
	                } else if($v['rule_type']==self::RULE_TYPE_REDUCE_RAND){
//随机立减规则待定
	                    $reduce_cost= 0;
	                } 
	                
	                $discount_array[$v['rule_type']] = $avali_rule[$k];
                    $discount_array[$v['rule_type']]['discount_type']= $v['rule_type'];
	                $use_auto_rule= TRUE;

	                if($debug) $log_txt.= $v['rule_id']. ', 活动规则检测通过：'. $v['rule_detail']. "--\n";
	            break;
	                
	            case self::RULE_TYPE_BALENCE:
	            case self::RULE_TYPE_POINT:
	                if( $use_cal_rule ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 积分开关关闭，跳过此规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                //积分开关判断
	                if( ! self::OPEN_USE_POINTS && $v['rule_type']== self::RULE_TYPE_POINT ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 积分开关关闭，跳过此规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                //储值开关判断
	                if( ! self::OPEN_USE_BALENCE && $v['rule_type']== self::RULE_TYPE_BALENCE ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 储值开关关闭，跳过此规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                
	                //有用优惠券排除此规则
	                if( $is_use_coupon && $v['can_use_coupon']== self::STATUS_CAN_NO ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 产生优惠券使用冲突，跳过此资产规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                //购买商品不符合，且并非通用规则排除此规则
	                if( ! $is_match_product ) {
	                    if($debug) $log_txt.= $v['rule_id']. ', 购买商品不符合条件，跳过此资产规则：'. $v['rule_detail']. "--\n";
	                    continue;
	                }
	                
	                //用户有没有使用此规则？
	                if( array_key_exists( $v['rule_type'], $discount_array ) ){
	                    if( !isset($api) ){
	                        $this->load->library('Soma/Api_member');
	                        $api= new Api_member($v['inter_id']);
	                        $result= $api->get_token();
	                        $api->set_token($result['data']);
	                    }
	                    
	                    //所用比例超过配置比例，排除此规则
	                    if( $v['rule_type']== self::RULE_TYPE_BALENCE ){
	                        $use_quote= $discount_array[$v['rule_type']]['quote'];
	                        $scale= $api->balence_scale( $openid );
	                        $eq_money= $api->balence_scale_convert($scale, $use_quote );
	                    
	                    } else if( $v['rule_type']== self::RULE_TYPE_POINT ){
	                        $use_quote= $discount_array[$v['rule_type']]['quote'];

	                        $scale= $api->point_scale( $openid, $v['bonus_size'] );
	                        $eq_money= $api->point_scale_convert($scale, $use_quote );
	                        if(number_format($else_total, 2, '.', '') < number_format($eq_money, 2, '.', '')){
	                            if($debug) $log_txt.= $v['rule_id']. ', 积分使用数量小于1分，规则跳过--\n';
	                            continue;
	                        }
	                    }
	                    
	                    $use_tag = $this->_can_use_quote($v, $eq_money, $else_total);
	                    
	                    if( $use_tag ){
	                        //如通过条件，设置排斥标记，组合优惠信息数据
	                        $discount_array[$v['rule_type']] += $avali_rule[$k];
	                        $discount_array[$v['rule_type']]['quote'] = $use_quote;
	                        $discount_array[$v['rule_type']]['scale'] = $scale;
	                        $discount_array[$v['rule_type']]['reduce_cost'] = $eq_money;
	                        $use_cal_rule= TRUE;
	                        
	                    } else {
	                        unset($discount_array[$v['rule_type']]);
	                    }
	                }
	                if($debug) $log_txt.= $v['rule_id']. ', 资产规则检测通过：'. $v['rule_detail']. "--\n";
	            break;
	        }
	    }
	    if($debug) {
	        $log_txt.= '所有规则匹配完毕！';
	        write_log($log_txt);
	    }
	    
        //var_dump($discount_array);die;
	    return $discount_array;
	}

	protected function _match_link_product($product_array, $link_product, $is_wide_scope=FALSE )
	{
	    $result= FALSE;
	    foreach ($product_array as $k=>$v){
	        if( $is_wide_scope || array_key_exists($v['product_id'], $link_product) ){
	            $p_qty= (int) $v['qty'];
	            if( $is_wide_scope || $p_qty >= $link_product[$v['product_id']] ){
	                $result= TRUE;
	            }
	        }
	    }
	    return $result;
	}

	protected function _can_use_quote($rule, $use_quote, $grand_total)
	{
	    if( !$rule['limit_type'] || !$rule['limit_percent'] ){
	        return TRUE;
	    }
	    $return= FALSE;
	    $max_quote= $grand_total* $rule['limit_percent']/100;
	    $min_quote= $grand_total* $rule['lease_percent']/100;

        //20170205 luguihong 数字类型转换，不做这个转换可能会相同数字不相等的结果 
        $max_quote = number_format($max_quote,6, '.', '');
        $min_quote = number_format($min_quote,6, '.', '');
        $use_quote = number_format($use_quote,6, '.', '');
	    
	    switch ($rule['limit_type']) {
	        case self::LIMIT_TYPE_SCOPE:
	            if( $max_quote>= $use_quote )
	                $return= TRUE;
                elseif( $rule['lease_percent'] && $min_quote> $use_quote )
	                Soma_base::inst()->show_exception('您可以抵扣的金额必须大于'. $max_quote, TRUE);
                else
                    Soma_base::inst()->show_exception('您可以抵扣的金额不能超过'. $max_quote, TRUE);
	        break;
	        case self::LIMIT_TYPE_FIXED:
	        default:
	            if( $max_quote== $use_quote )
	                $return= TRUE;
                else
                    Soma_base::inst()->show_exception('您可以抵扣的金额只能为'. $max_quote, TRUE);
            break;
	    }
	    return $return;
	}

	/**
	 * 按照规则计算所用金额
	 * @param Array $rule  规则订单数组
	 * @param integer $eq_money    账户等值金额
	 * @param integer $subtotal    订单总金额
	 * @return number|boolean
	 */
	protected function _cal_use_quote($rule, $eq_money, $subtotal)
	{
	    $max_quote= $subtotal* $rule['limit_percent']/ 100;
	    $min_quote= $subtotal* $rule['lease_percent']/ 100;
	    
	    //echo $max_quote;die;
	    switch ($rule['limit_type']) {
	        case self::LIMIT_TYPE_SCOPE:
	            if( $rule['rule_type']== self::RULE_TYPE_POINT && $min_quote> $eq_money ){
	                return FALSE;  //个人额度不足最低限
	            } elseif( $max_quote>= $eq_money )
	                return $eq_money;  //用完所有额度
                else
	                return $max_quote; //用对应额度
                    
	        break;
	        case self::LIMIT_TYPE_FIXED:
	        default:
	            if( $max_quote> $eq_money )
	                return FALSE;   //不能用
                else
	                return $max_quote; //用对应额度
            break;
	    }
	    return FALSE;
	}

	public function get_product_rule($pids, $inter_id, $type='auto_rule')
	{
	    if( !$pids ) return FALSE;
        // $link_table= $this->rule_product_table_name($inter_id);
	    $link_table= $this->rule_product_table_name_r($inter_id);
	    $tfilter= array('inter_id'=> $inter_id, 'status'=> self::STATUS_TRUE );
        // $rule_link= $this->_shard_db($inter_id)->where($tfilter)
	    $rule_link= $this->_shard_db_r('iwide_soma_r')
                            ->where($tfilter)
	                        ->where_in('product_id', $pids )
                            ->get($link_table)
                            ->result_array();
	    //echo $this->_shard_db($inter_id)->last_query();die;
	    
        // $rule_table= $this->table_name($inter_id);
        $rule_table= $this->table_name_r($inter_id);
        $filter= array('inter_id'=> $inter_id, 'status'=> self::STATUS_TRUE );
        $link_ids= array();
        $rules = array();
        if($rule_link){
            foreach ($rule_link as $k=>$v){
                $link_ids[$v['rule_id']][] = $v['product_id'];
            }
	        
	        switch ($type) {
	            case 'asset_rule':
                    // $rules= $this->_shard_db($inter_id)
	                $rules= $this->_shard_db_r('iwide_soma_r')
	                   ->where_in('rule_type', $this->get_asset_rule() )
	                   ->where_in('rule_id', array_keys($link_ids) )
	                   ->where($filter)
	                   ->order_by('rule_type desc, sort desc')
	                   ->get($rule_table)->result_array();
	                break;
	            case 'auto_rule':
	            default:
                    // $rules= $this->_shard_db($inter_id)
	                $rules= $this->_shard_db_r('iwide_soma_r')
    	               // ->where_in('rule_type', $this->get_auto_rule() )
    	               // ->where_in('rule_id', array_keys($link_ids) )
                       ->where( ' rule_type in('.implode(',',$this->get_auto_rule()).') and (rule_id in('.implode(',',array_keys( $link_ids )).') or scope='.self::STATUS_TRUE.')' ) 
                       // ->where( ' (( rule_type in('.implode(',',$this->get_auto_rule()).') and rule_id in('.implode(',',array_keys( $link_ids )).')) or scope='.self::STATUS_TRUE.')' )
	                   ->order_by('rule_type desc, sort desc')
	                   ->where($filter)
	                   ->get($rule_table)->result_array();
	                break;
	        }
	        //echo $this->_shard_db($inter_id)->last_query();die;
	        
	    }else{
            // $rules= $this->_shard_db($inter_id)
            $rules= $this->_shard_db_r('iwide_soma_r')
                   ->where_in('rule_type', $this->get_auto_rule() )
                   ->where('scope',self::STATUS_TRUE)//全部适用
                   ->order_by('rule_type desc, sort desc')
                   ->where($filter)
                   ->get($rule_table)->result_array();
        }
        
        if( count( $rules ) > 0 ){
            $now= time();
            foreach ($rules as $k => $v ) {
                if( $v['status']== self::STATUS_FALSE ) {
                    unset($rules[$k]);
                } else if( $v['start_time'] && strtotime($v['start_time'])> $now ){
                    unset($rules[$k]);
                } else if( $v['end_time'] && strtotime($v['end_time'])< $now ){
                    unset($rules[$k]);
                } else if( isset($link_ids[$v['rule_id']]) ) 
                    $rules[$k]['product_id']= $link_ids[$v['rule_id']];
            }
            return $rules;
        }else{
            return FALSE;
        }

	}
}
