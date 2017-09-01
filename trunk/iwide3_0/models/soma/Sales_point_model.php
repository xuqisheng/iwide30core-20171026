<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_point_model extends MY_Model_Soma {

    const RECORD_TYPE_ADD = 1;
    const RECORD_TYPE_USE = 2;
    const RECORD_TYPE_REFUND = 3;

    const RECORD_STATUS_SYNC = 1; // 已同步
    const RECORD_STATUS_UNSYNC = 2;  // 未同步
    const RECORD_STATUS_HOLD = 3;  // 挂起，待确认

    public function get_settle_label()
    {
        return array(
            self::SETTLE_DEFAULT => '普通购买',
            // self::SETTLE_GROUPON => '拼团购买',
            self::SETTLE_KILLSEC => '秒杀购买',
            // self::SETTLE_WHOLESALE => '大客户预订',
            // self::SETTLE_VOUCHER => '礼品卡券',
        );
    }

	public function get_resource_name()
	{
		return 'Sales_point_model';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'soma_sales_income_rule';
	}

    public function rule_product_table_name() {
        return 'soma_sales_income_product';
    }

    public function record_table_name() {
        return 'soma_sales_income_record';
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
            'rule_id'=> '规则ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店号',
            'settlement'=> '结算方式',
            'rule_type'=> '规则类型',
            'name'=> '规则名',
            'bonus_size'=> '积分比例',
            'member_lvl_id'=> '会员等级',
            'lvl_name'=> '会员等级',
            'can_use_coupon'=> '使用优惠券是否计算积分',
            'can_use_balence'=> '使用储值是否计算积分',
            'can_use_point'=> '使用积分是否计算积分',
            'can_use_reduce'=> '使用折扣满减是否计算积分',  // 满减和满打折
            'start_time'=> '开始时间',
            'end_time'=> '结束时间',
            'create_time'=> '创建时间',
            'create_admin'=> '创建人',
            'update_time'=> '更新时间',
            'update_admin'=> '更新人',
            'scope'=> '适用产品范围',
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
            'inter_id',
            // 'hotel_id',
            'settlement',
            // 'rule_type',
            'name',
            // 'bonus_size',
            // 'member_lvl_id',
            // 'lvl_name',
            // 'can_use_coupon',
            // 'can_use_balence',
            // 'can_use_point',
            // 'can_use_reduce',
            'start_time',
            'end_time',
            'create_time',
            'create_admin',
            'update_time',
            'update_admin',
            // 'scope',
            'sort',
            'status',
	    );
	}

	/**
	 * 后台UI输出定义函数
	 *   type: grid中的表头类型定义 
	 *   function: 数值转换函数 
	 *   select: form中的类型为 combobox时，定义其下来列表
	 grid专用属性名
	 *   grid_function: grid生效的数值转换，如'grid_function'=> 'show_price_prefix|￥',
	 *   grid_width: grid的宽度
	 *   grid_ui:  grid中的属性追加
	 form专用属性名
	 *   js_config: 用于 datetime, date 等js初始化中追加此参数
	 *   input_unit: input框中的单位提示
	 *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
	 *   form_tips: form中的label信息提示
	 *   form_hide: form中自动化输出中剔除
	 *   form_default: form中的默认值，请用字符类型，不要用数字
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

	    return array(
            'rule_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select' => $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select' => $hotels,
            ),
            'settlement' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select' => $this->get_settle_label(),
            ),
            'rule_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'bonus_size' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                // 'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'member_lvl_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select'=>$this->get_member_level_name(),
            ),
            'lvl_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'can_use_coupon' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select'=>$this->get_status_yes_label(),
            ),
            'can_use_balence' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                // 'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'can_use_point' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                // 'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'can_use_reduce' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'start_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'end_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'create_admin' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'update_admin' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'scope' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select'=>array(self::STATUS_TRUE => '激活', self::STATUS_FALSE => '禁用'),
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

    protected $_m_api = null;

    public function init_member_api($inter_id) {
        if($this->_m_api == null) {
            $this->load->library('Soma/Api_member');
            $this->_m_api = new Api_member($inter_id);
        } else {
            $this->_m_api->set_inter_id($inter_id);
        }
        return $this;
    }
	
    public function get_member_level_name() {

        if($this->_m_api == null) {
            return array();
        }

        $result= $this->_m_api->get_token();

        if(!isset($result['data'])) {
            throw new Exception("Token error!", 1);
        }

        $this->_m_api->set_token($result['data']);
        $data = $this->_m_api->member_lvl();

        if(!isset($data['data'])) {
            throw new Exception("get member level info error!", 1);
        }

        // api解析返回是staticClass，此处再转换为数组模式
        $str = json_encode($data);
        $arr = json_decode($str, true);

        return $this->array_to_hash($arr['data'], 'lvl_name' ,'member_lvl_id');

    }


    /**
     * 验证表单提交是否正确
     *
     * @param      <type>  $data   表单提交数据
     *
     * @return     <type>  验证结果true|false
     */
    public function form_validation($data) {
        $this->load->library('form_validation');
        $this->form_validation->set_data($data);
        if(empty($data['template_id'])) {
            $this->form_validation->set_rules('inter_id', '公众号', 'required');
            // $this->form_validation->set_rules('hotel_id', '公众号', 'required');
            $this->form_validation->set_rules('name', '规则名', 'required');
            // $this->form_validation->set_rules('bonus_size', '积分兑换比例', 'required');
            // $this->form_validation->set_rules('member_lvl_id', '会员等级', 'required');
            $this->form_validation->set_rules('start_time', '开始时间', 'required');
            $this->form_validation->set_rules('end_time', '结束时间', 'required');
            $this->form_validation->set_rules('sort', '优先顺序', 'required');
            $this->form_validation->set_rules('p_type', '产品适用范围', 'required');
            if($data['p_type'] == 'ids') {
                $this->form_validation->set_rules('product_ids', '适用产品', 'required');
            }
        }
        return $this->form_validation->run();
    }

    /**
     * 格式化表单数据，生成适合数据保存的数据格式
     *
     * @param      array   $data   表单数据
     *
     * @return     array   格式化后的数据
     */
    public function format_rule_data($data) {

        $fmt_data = array();
        
        $fmt_data['inter_id'] = $data['inter_id'];
        $fmt_data['hotel_id'] = $data['hotel_id'];
        $fmt_data['settlement'] = $data['settlement'];
        $fmt_data['name'] = $data['name'];
        $fmt_data['bonus_size'] = json_encode($data['bonus_size']);

        $fmt_data['can_use_coupon'] = isset($data['can_use_coupon'])
            ? $data['can_use_coupon'] : Soma_base::STATUS_TRUE;
        $fmt_data['can_use_point'] = isset($data['can_use_point'])
            ? $data['can_use_point'] : Soma_base::STATUS_TRUE;
        $fmt_data['can_use_balence'] = isset($data['can_use_balence'])
            ? $data['can_use_balence'] : Soma_base::STATUS_TRUE;
        $fmt_data['can_use_reduce'] = isset($data['can_use_reduce'])
            ? $data['can_use_reduce'] : Soma_base::STATUS_TRUE;

        // 已经没有意义，数据存放在bonus_size
        // $fmt_data['member_lvl_id'] = $data['member_lvl_id'];
        // $this->init_member_api($fmt_data['inter_id']);
        // $level_info = $this->get_member_level_name();
        // $fmt_data['lvl_name'] = $level_info[ $fmt_data['member_lvl_id'] ];

        $fmt_data['start_time'] = $data['start_time'];
        $fmt_data['end_time'] = $data['end_time'];
        $fmt_data['update_time'] = date('Y-m-d H:i:s');
        $fmt_data['update_admin'] = $data['op_user'];
        $fmt_data['sort'] = $data['sort'];
        $fmt_data['status'] = $data['status'];
        $fmt_data['scope'] = ($data['p_type'] == 'all_use') ? Soma_base::STATUS_TRUE : Soma_base::STATUS_FALSE;
        if($fmt_data['scope'] == Soma_base::STATUS_TRUE) {
            $fmt_data['product_ids'] = '';
        } else {
            $fmt_data['product_ids'] = $data['product_ids'];
        }

        if(isset($data['rule_id']) && $data['rule_id'] != '') {
            $fmt_data['rule_id'] = $data['rule_id'];
        } else {
            $fmt_data['create_time'] = $fmt_data['update_time'];
            $fmt_data['create_admin'] = $fmt_data['update_admin'];
        }

        return $fmt_data;
    }


    /**
     * 获取符合数据库保存格式的规则数据
     *
     * @param      <type>  $data   原始数据
     *
     * @return     array   处理后的数据
     */
    public function format_rule_product_data($data) {
        if($data['p_type'] == Soma_base::STATUS_TRUE
            || !isset($data['product_ids']) || $data['product_ids'] == '') {
            return array();
        }
        
        $p_ids = explode(',', $data['product_ids']);
        $this->load->model('soma/Product_package_model', 'p_model');
        $products = $this->p_model->get_product_package_by_ids($p_ids, $data['inter_id']);

        $fmt_data = array();
        foreach ($products as $p) {
            $tmp_row = array();
            $tmp_row['inter_id'] = $data['inter_id'];
            $tmp_row['hotel_id'] = $data['hotel_id'];
            $tmp_row['rule_id'] = $data['rule_id'];
            $tmp_row['product_id'] = $p['product_id'];
            $tmp_row['qty'] = $p['stock'];
            $tmp_row['name'] = $p['name'];
            $tmp_row['status'] = $p['status'];
            $fmt_data[] = $tmp_row;
        }

        return $fmt_data;
    }


    /**
     * 保存规则对应的产品数据
     *
     * @param      <type>   $data   用户提交的表单数据
     *
     * @return     boolean  保存结果true|false;
     */
    public function save_rule_product($data) {
        
        if(count($data) <= 0) { return true; }

        $db = $this->_shard_db();
        $table = $this->_shard_table($this->rule_product_table_name());

        try {
            // 插入前先删除数据
            $db->delete($table, array('rule_id' => $data[0]['rule_id']));
            $db->insert_batch($table, $data);
            return true;
        } catch (Exception $e) {
            return false;
        }
        return false;

    }

    public function trans_begin() {
        $this->_shard_db()->trans_begin();
    }

    public function trans_commit() {
        $this->_shard_db()->trans_commit();
    }

    public function trans_rollback() {
        $this->_shard_db()->trans_rollback();
    }

    public function get_rule_can_discount_list() {
        $this->load->model('soma/Sales_order_discount_model');
        $d_model = $this->Sales_order_discount_model;
        return array(
            $d_model::TYPE_COUPON => 'can_use_coupon',
            // $d_model::TYPE_FCODE => 'can_use_fcode',
            $d_model::TYPE_POINT => 'can_use_point',
            $d_model::TYPE_BALENCE => 'can_use_balence',
            $d_model::TYPE_REDUCE => 'can_use_reduce',
            $d_model::TYPE_DISCOUNT => 'can_use_reduce',
            $d_model::TYPE_REDUCE_RAND => 'can_use_reduce',
        );
    }

    /**
     * 计算积分，更新积分队列
     *
     * @param      <type>  $s_time  The start time
     */
    public function update_point_queue($s_time = null) {
        if($s_time == null) {
            $s_time = $this->get_init_start_time();
        }

        $order_set = $this->_get_order_set($s_time);

        $point_set = array();
        foreach ($order_set as $order) {
            $point_row = $this->_calc_order_point($order);
            if(count($point_row) > 0) { $point_set[] = $point_row; }
            // $point_set[] = $this->_calc_order_point($order);
        }

        return $this->_write_point_queue($point_set);
    }

    /**
     * Gets the initialize start time.
     * 
     * 1.从record表中最大的支付时间，如果存在则返回
     * 2.默认从今天0点开始进行计算
     * 
     */
    protected function _get_init_start_time() {

        $table = $this->_shard_table($this->record_table_name());
        $row = $this->_shard_db_r('iwide_soma_r')
                    ->order_by('payment_time', 'DESC')
                    ->get($table)->limit(1)
                    ->row_array();
        if($row) { return $row['payment_time']; }

        return date('Y-m-d') . ' 00:00:00';
    }

    /**
     * 获取数据库分片信息
     * @return [type] [description]
     */
    protected function _get_table_suffix() {
        $shards= $this->_db()->get('soma_shard')->result_array();
        $data = array();
        foreach ($shards as $row) { $data[] = $row['table_suffix']; }
        return $data;
    }

    /**
     * Gets the order set.
     * 
     * 订单按支付时间从小到大的排序，保证时间越靠前的订单先处理
     * 
     * @param      <type>  $s_time  The s time
     *
     * @return     array   The order set.
     */
    protected function _get_order_set($s_time) {

        $this->load->model('soma/Sales_order_model', 'o_model');
        $o_model = $this->o_model;

        $o_idx_tb = $this->_shard_table('soma_sales_order_idx');
        $o_set = $this->_shard_db_r('iwide_soma_r')
                        ->where('payment_time >=', $s_time)
                        ->where('status', $o_model::STATUS_PAYMENT)
                        ->order_by('payment_time', 'ASC')
                        ->get($o_idx_tb)
                        ->result_array();

        $order_set = array();
        foreach ($o_set as $row) {
            $order_set[ $row['order_id'] ] = $row;
            $order_set[ $row['order_id'] ]['item_set'] = array();
            $order_set[ $row['order_id'] ]['discount_set'] = array();
        }

        $order_set = $this->filter_order_record_exist($order_set);

        if(count($order_set) > 0) {
            $item_set = array();
            $suffixs = $this->_get_table_suffix(); 
            foreach ($suffixs as $suffix) {
                $i_tb = 'soma_sales_order_item_package' . $suffix;
                $item_set += $this->_shard_db_r('iwide_soma_r')
                                    ->where_in('order_id', array_keys($order_set))
                                    ->get($i_tb)
                                    ->result_array();
            }
            foreach ($item_set as $item) {
                $order_set[ $item['order_id'] ]['item_set'][] = $item;
            }

            $d_tb = 'soma_sales_order_discount';
            $discount_set = $this->_shard_db_r('iwide_soma_r')
                                    ->where_in('order_id', array_keys($order_set))
                                    ->get($d_tb)
                                    ->result_array();

            foreach ($discount_set as $discount) {
                $order_set[ $discount['order_id'] ]['discount_set'][] = $discount;
            }
        }

        return $order_set;
    }

    /**
     * 过滤掉已经存在积分记录的订单
     *
     * @param      array   $order_set    The order set
     * @param      array   $record_type  The record type
     *
     * @return     array   The order set
     */
    public function filter_order_record_exist($order_set, $record_type = self::RECORD_TYPE_ADD) {

        if(count($order_set) <= 0) { return array(); }
        
        $table = $this->record_table_name();
        $record_set = $this->_shard_db_r('iwide_soma_r')
                            ->where('record_type', $record_type)
                            ->where_in('order_id', array_keys($order_set))
                            ->get($table)
                            ->result_array();

        foreach ($record_set as $record) {
            unset($order_set[ $record['order_id'] ]);
        }
        return $order_set;
    }

    /**
     * Calculates the order point.
     *
     * @param      <type>  $order  The order
     */
    protected function _calc_order_point($order) {

        $this->init_member_api($order['inter_id']);
        // $rule_set = $this->_get_rule_set($order['inter_id'], $order['hotel_id']);
        $rule_set = $this->_get_rule_set($order['inter_id']);

        $point_cnt = 0;
        $rule_select = array();
        $this->_add_log("======开始匹配积分计算规则======\r\n");
        foreach ($rule_set as $rule) {
            if($this->_can_order_use_rule($order, $rule)) {
                $rule_select = $rule;
                // $point_cnt += intval( $order['grand_total'] * $rule['bonus_size'] );
                $scale = $this->_m_api->point_scale($order['openid'], $rule['bonus_size'], 'get');
                $point_cnt += $this->_m_api->point_scale_convert_get($scale, $order['grand_total']);
                break; // 终止继续匹配
            }
        }
        $this->_add_log("======匹配积分计算规则结束======\r\n");
        $this->_write_log();
        if(count($rule_select) == 0) { return array(); }

        // return $this->_format_point_record_data($order, $rule_select, $point_cnt);
        return $this->_format_record_data($order, $rule_select, $point_cnt);
    }

    protected $rule_set = array();

    /**
     * Gets the rule set.
     *
     * @param      string  $inter_id  The inter identifier
     * @param      array   $hotel_id  The hotel identifier
     *
     * @return     array   The rule set.
     */
    protected function _get_rule_set($inter_id, $hotel_id = null) {
        $cache_key = $inter_id . '_' . $hotel_id;
        if(!isset($this->rule_set[$cache_key])) {
            $basic_table = $this->table_name();
            $table = $this->_shard_table($basic_table, $inter_id);  

            $db = $this->_shard_db_r('iwide_soma_r');
            $db->where('inter_id', $inter_id);
            if($hotel_id != null) {
                if(!is_array($hotel_id)) { $hotel_id = array($hotel_id); }
                $db->where_in('hotel_id', $hotel_id);
            }   
            // where_or hotel_id = all ???
            $db->where('status', Soma_base::STATUS_TRUE);
            $res = $db->order_by('sort DESC, create_time DESC')->get($table)->result_array();
            if($res) {
                $this->rule_set[$cache_key] = $res;
            } else {
                return array();
            }
        }
        return $this->rule_set[$cache_key];
    }

    /**
     * Determines ability to order use rule.
     *
     * @param      <type>   $order  The order
     * @param      <type>   $rule   The rule
     *
     * @return     boolean  True if able to order use rule, False otherwise.
     */
    protected function _can_order_use_rule($order, $rule) {

        $this->_add_log("order:" . var_export($order, true) . "\r\n");
        $this->_add_log("rule:" . var_export($rule, true) . "\r\n");
        $this->_add_log("匹配结果：");

        // 结算方式不匹配
        if($order['settlement'] != $rule['settlement']) {
            $this->_add_log("结算方式不匹配！\r\n");
            return false;
        }

        // 产品不匹配，架构问题，一个订单只对应一个细单
        $p_id = '-1';
        if(isset($order['item_set'][0]['product_id'])) {
            $p_id = $order['item_set'][0]['product_id'];
        }
        $p_arr = explode(',', $rule['product_ids']);
        if(!in_array($p_id, $p_arr) 
            && $rule['scope'] != Soma_base::STATUS_TRUE) {
            $this->_add_log("产品不匹配！\r\n");
            return false;
        }
        
        if(isset($order['item_set'][0]['type'])
            && $order['item_set'][0]['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT) {
            // 积分商品不再计算积分赠送
            return false;
        }

        // 时间不匹配
        if(strtotime($order['payment_time']) < strtotime($rule['start_time'])
            || strtotime($order['payment_time']) > strtotime($rule['end_time'])) {
            $this->_add_log("时间不匹配！\r\n");
            return false;
        }

        // 折扣规则不匹配
        $can_arr = array();
        $can_list = $this->get_rule_can_discount_list();
        foreach ($can_list as $type => $key) {
            if($rule[$key] == Soma_base::STATUS_TRUE) {
                $can_arr[] = $type;
            }
        }
        foreach ($order['discount_set'] as $row) {
            if(!in_array($row['type'], $can_arr)) {
                $this->_add_log("折扣不匹配！\r\n");
                return false;
            }
        }

        /* 不做会员匹配，因为bonus_size里面针对了所有会员等级进行设置,即所有会员都可以计算
        // 没有会员信息或者会员等级不匹配
        $info = $this->_get_member_lv_info($order['inter_id'], $order['openid']);
        // var_dump($order, $info);exit;
        if(count($info) <= 0 || $info['err'] != 0) {
            return false;
        } else {
            $data = json_decode(json_encode($info['data']), true);
            if($data['member_lvl_id'] != $rule['member_lvl_id']) {
                return false;
            }
        }
        */
        $this->_add_log("匹配成功！\r\n");
        return true;
    }

    protected $_member_lv_info = array();

    protected function _get_member_lv_info($inter_id, $openid) {
        $key = $inter_id . '_'. $openid;
        if(!isset($this->_member_lv_info[$key])) {
            $this->init_member_api($inter_id);
            $info = $this->_m_api->member_lv_info($openid);
            if(isset($info) && is_array($info)) {
                $this->_member_lv_info[$key] = $info;
            } else {
                $this->_member_lv_info[$key] = array();
            }
        }
        return $this->_member_lv_info[$key];
    }

    /**
     * Format data into record row
     *
     * @param      <type>  $order      The order
     * @param      <type>  $rule       The rule
     * @param      <type>  $point_cnt  The point count
     *
     * @return     array   ( description_of_the_return_value )
     */
    protected function _format_point_record_data($order, $rule, $point_cnt) {
        return array(); // 废弃
        $data = array();
        $data['record_type'] = self::RECORD_TYPE_ADD;
        $data['inter_id'] = $order['inter_id'];
        $data['hotel_id'] = $order['hotel_id'];
        $data['openid'] = $order['openid'];
        $data['order_id'] = $order['order_id'];
        $data['payment_time'] = $order['payment_time'];
        $data['rule_id'] = $data['rule_compose'] = null;
        if(count($rule) > 0) {
            $data['rule_id'] = $rule['rule_id'];
            $data['rule_compose'] = json_encode($rule);
        }
        $data['count'] = $point_cnt;
        $data['sence'] = $order['business'];
        $data['remark'] = 'add point';
        // $data['sync_status'] = Soma_base::STATUS_FALSE;
        $data['sync_status'] = self::RECORD_STATUS_UNSYNC;
        if($point_cnt <= 0) {
            // $data['sync_status'] = Soma_base::STATUS_TRUE;
            $data['sync_status'] = self::RECORD_STATUS_HOLD;
        }
        return $data;
    }

    /**
     * { function_description }
     *
     * @param      <type>   $order      The order
     * @param      array    $rule       The rule
     * @param      integer  $point_cnt  The point count
     * @param      <type>   $type       The type
     * @param      <type>   $status     The status
     *
     * @return     array    ( description_of_the_return_value )
     */
    protected function _format_record_data($order, $rule = array(), $point_cnt = 0,  $type = self::RECORD_TYPE_ADD, $status = self::RECORD_STATUS_UNSYNC) {
        $data = array();
        $data['record_type'] = $type;
        $data['inter_id'] = $order['inter_id'];
        $data['hotel_id'] = $order['hotel_id'];
        $data['openid'] = $order['openid'];
        $data['order_id'] = $order['order_id'];
        $data['payment_time'] = isset($order['payment_time']) ? $order['payment_time'] : date('Y-m-d H:i:s');
        $data['rule_id'] = $data['rule_compose'] = null;
        if(count($rule) > 0) {
            $data['rule_id'] = $rule['rule_id'];
            $data['rule_compose'] = json_encode($rule);
        }
        $data['count'] = $point_cnt;
        $data['sence'] = $order['business'];
        $data['remark'] = '订单ID：' . $order['order_id'];
        $data['sync_status'] = $status;
        if($point_cnt <= 0) {
            $data['sync_status'] = self::RECORD_STATUS_HOLD;
        }
        return $data;
    }

    /**
     * Writes a point queue.
     *
     * @param      <type>   $point_set  The point set
     *
     * @return     boolean  Affect rows if write success, False otherwise.
     */
    protected function _write_point_queue($point_set) {
        if(count($point_set) <= 0) { return false; }
        // 批量插入积分记录表
        $table = $this->record_table_name();
        return $this->_shard_db()->insert_batch($table, $point_set);
    }


    /**
     * 用于外部插入一条积分记录
     *
     * @param      <type>  $order_id  The order identifier
     * @param      <type>  $count     The count
     * @param      string  $type      The type
     * @param      <type>  $status    The status
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function insert_record_data($order_id, $count, 
        $type = self::RECORD_TYPE_ADD, $status = self::RECORD_STATUS_UNSYNC) {
        $this->load->model('soma/Sales_order_model', 'o_model');
        $order = $this->o_model->load($order_id);
        if($order == null) { return false; }
        $order_data = $order->m_data();
        $point_set = array();
        $point_set[] = $this->_format_record_data($order_data, array(), $count, $type, $status);
        return $this->_write_point_queue($point_set);
    }

    /**
     * Pushes a point information.
     */
    public function push_point_info() {
        $this->_add_log("===== push_point_info start! =====\r\n");
        $max_row = 100; // 防止超时，一次处理100条
        $table = $this->record_table_name();
        $point_set = $this->_shard_db_r('iwide_soma_r')
                            // ->where('sync_status', Soma_base::STATUS_FALSE)
                            ->where('sync_status', self::RECORD_STATUS_UNSYNC)
                            ->order_by('payment_time', 'ASC')
                            ->limit($max_row)
                            ->get($table)
                            ->result_array();

        // $this->_add_log("SQL:" . $this->_shard_db()->last_query() . "\r\n");
        $this->_add_log("原始数据:" . var_export($point_set, true) . "\r\n");

        if(count($point_set) > 0) {
            $this->init_member_api($point_set[0]['inter_id']);
            $token = $this->_m_api->get_token();
            if(!isset($token['data'])) {
                return false;
            } else {
                $this->_m_api->set_token($token['data']);
            }
        }

        $cnt = 1;   // 拼接uucode使用
        $update_data = array();

        foreach ($point_set as $row) {
            $uu_code = 'soma_point_' . time() . $cnt;
            $this->init_member_api($row['inter_id']);

            switch ($row['record_type']) {
                case self::RECORD_TYPE_ADD:
                    $res = $this->_m_api->point_plus($row['count'], $row['openid'], $uu_code, $row['order_id']);
                    break;
                case self::RECORD_TYPE_USE:
                    $res = $this->_m_api->point_use($row['count'], $row['openid'], $uu_code, $row['order_id']);
                    break;
                case self::RECORD_TYPE_REFUND:
                default:
                    $res = null;
                    break;
            }
            
            if(isset($res) && is_array($res) && $res['err'] == 0) {
                $tmp_row = $row;
                $tmp_row['sync_status'] = self::RECORD_STATUS_SYNC;
                $update_data[] = $tmp_row;
            }
            $cnt++;
        }
        
        if(count($update_data) > 0) {
            $this->_add_log("需要更新的而数据:" . var_export($update_data, true) . "\r\n");
            $res = $this->_shard_db()->update_batch($table, $update_data, 'record_id');
            $this->_add_log("结果:" . var_export($res, true) . "\r\n");
            $this->_add_log("===== push_point_info end! =====");
            $this->_write_log();
            return $res;
        } else {
            $this->_add_log("结果：无数据更新!\r\n");
            $this->_add_log("===== push_point_info end! =====");
            $this->_write_log();
            return true;
        }
    }

    protected $log = '';

    protected function _add_log($content) {
        $this->log .= $content;
    }

    protected function _write_log() {
        $this->load->helper('soma/package');
        $path = APPPATH. 'logs'. DS. 'soma'. DS . 'sales_point' . DS;
        write_log($this->log, NULL, $path );
        $this->log = '';
    }

}
