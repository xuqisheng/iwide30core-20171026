<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_voucher_model extends MY_Model_Soma {

    public $openid = NULL;//自助兑换

    public $admin = array();//后台兑换人信息

    const STATUS_UNUSED = 1;
    const STATUS_USED = 2;

    protected $code_validation_error;

    public function get_status_label()
    {
        return array(
            self::STATUS_UNUSED   => '未使用',
            self::STATUS_USED => '已使用',
        );
    }

	public function get_resource_name()
	{
		return 'Sales_voucher_model';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
    public function table_name( $inter_id=NULL )
    {
        return $this->_shard_table('soma_sales_voucher', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'code_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'code_id'=> 'Code_id',
            'inter_id'=> 'Inter_id',
            'hotel_id'=> 'Hotel_id',
            'template_id'=> 'Template_id',
            'code'=> 'Code',
            'password'=> 'Password',
            'batch_no'=> 'Batch_no',
            'create_time'=> 'Create_time',
            'status'=> 'Status',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'code_id',
            'inter_id',
            'hotel_id',
            'template_id',
            'code',
            'password',
            'batch_no',
            'create_time',
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

	    return array(
            'code_id' => array(
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
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'hotel_id' => array(
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
            'template_id' => array(
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
            'code' => array(
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
            'password' => array(
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
            'batch_no' => array(
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
            'create_time' => array(
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
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'code_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    /**
     * 批量保存券码，只能是插入
     * @param  [type] $tpl_model [description]
     * @param  [type] $data      [description]
     * @return [type]            [description]
     */
	public function batch_save($tpl_model, $data) {

        $inter_id = $tpl_model->m_get('inter_id');
        $fmt_data = $this->_format_data($tpl_model, $data);
        $table = $this->_shard_table($this->table_name(), $inter_id);
        $db = $this->_shard_db($inter_id);
        
        $db->trans_begin();
        try {
            $db->insert_batch($table, $fmt_data);
            if($db->trans_status()) {
                $db->trans_commit();
                return true;
            } else {
                $db->trans_rollback();
                return false;
            }
        } catch (Exception $e) {
            $db->trans_rollback();
            return false;
        }

    }

    protected function _format_data($tpl_model, $data) {
        $fmt_data = array();
        $now_time = date('Y-m-d H:i:s');
        foreach ($data as $code) {
            $row['inter_id'] = $tpl_model->m_get('inter_id');
            $row['hotel_id'] = $tpl_model->m_get('hotel_id');
            $row['template_id'] = $tpl_model->m_get('template_id');
            $row['code'] = $code;
            // $row['password'] = '';
            $row['batch_no'] = $tpl_model->m_get('batch_no');
            $row['create_time'] = $now_time;
            $row['status'] = self::STATUS_UNUSED;
            $fmt_data[] = $row;
        }
        // var_dump($fmt_data);exit;
        return $fmt_data;
    }

    /**
     * 通过券码load到兑换券信息
     * 
     * @param  [type] $code [description]
     * @return [type]       [description]
     */
    public function load_by_code($code) {

        $values= $this->find(array('code'=> $code));
        
        if($values){
            $table= $this->table_name();
            $fields= $this->_shard_db_r('iwide_soma_r')->list_fields($table);
            $this->_attribute= array_values($fields);
            
            foreach ($fields as $v) {
                $this->_data[$v]= $values[$v];
            }
            //确保 $this->_data_org 的值是完整的
            $this->_data_org = $this->_data;
            return $this;
            
        } else {
            return NULL;
        }
    }

    /**
     * 校验券码信息,公众号不对不能使用，已使用的不能使用
     * 过期时间校验,要加载模板
     * @return [type] [description]
     */
    public function code_validation($data) {
        
        $this->code_validation_error = '';

        if( !$this->m_get($this->table_primary_key())
            || $data['inter_id'] != $this->m_get('inter_id')
            || !$this->m_get('template_id')){
            $this->code_validation_error = '您所输入的验证码有误，不可使用！';
            return false;
        }

        $this->load->model('soma/Sales_voucher_template_model', 't_model');
        $t_model = $this->t_model->load($this->m_get('template_id'));
        if(!$t_model) { return false; }

        $effective_time = $t_model->m_get('effective_time');
        $expiration_time = $t_model->m_get('expiration_time');
        $now_time = date('Y-m-d H:i:s');

        if(strtotime($effective_time) > strtotime($now_time)) {
            $this->code_validation_error = '您所输入的验证码未到有效期，暂不可使用！';
            return false;
        }

        if(strtotime($expiration_time) < strtotime($now_time)) {
            $this->code_validation_error = '您所输入的券码已过期，不可使用！';
            return false;
        }

        if($this->m_get('status') == self::STATUS_USED) {
            $this->code_validation_error = '您所输入的券码已使用，不可重复使用！';
            return false;
        }

        return true;
    }

    public function code_valid_error() {
        return $this->code_validation_error;
    }

    /**
     * 生成一个订单，需要传入openid确定订单归属
     * @param  [type] $openid [description]
     * @return [type]         [description]
     */
    public function generate_order($openid = null) {
        // var_dump($openid);exit;
        $this->load->model('soma/Sales_voucher_template_model', 't_model');
        $t_model = $this->t_model->load($this->m_get('template_id'));
        if(!$t_model) {
            throw new Exception("Can't generate order by voucher code!", 1);
        }

        $this->load->model('soma/Sales_order_model');
        $order = $this->Sales_order_model;
        $order->business = $t_model->m_get('business');
        $order->settlement = self::SETTLE_VOUCHER;
        $order->inter_id = $this->m_get('inter_id');
        $order->openid = $openid;
        // $order->row_qty = 0; // 商品总个数
        // $order->row_total = 0; // 商品总价
        // $order->subtotal = 0;
        // $order->grand_total = $t_model->m_get('product_price');
        $order->discount = array();
        
        if(!empty($openid))
        {
            $this->load->library('Soma/Api_member');
            $api= new Api_member($this->m_get('inter_id'));
            $result= $api->get_token();
            $api->set_token($result['data']);

            // 查询会员信息
            $member_info = $api->get_member_info($openid);
            if($member_info)
            {
                // "member_mode":"1" 则是 本地会员， member_mode =2 & is_login = t, 则是对接而且登录的会员
                // 对接会员membership_number与jfk_member_info值不一样，非对接会员一样，下单取membership_number即可
                $order->member_id = $member_info['data']->member_id;
                $order->member_card_id = $member_info['data']->membership_number;
            }
            else
            {
                Soma_base::inst()->show_exception('会员信息获取失败，请稍后再重新尝试下单');
            }
        }

        require_once dirname(__FILE__). DS. 'Sales_order_model.php';
        $customer = new Sales_order_attr_customer($openid);
        $order->customer = $customer;

        $this->load->model('soma/Product_package_model','productPackageModel');
        /**
         * @var Product_package_model $productPackageModel
         */
        $productPackageModel = $this->productPackageModel;
        $product = $productPackageModel->get('product_id', $t_model->m_get('product_id'));
        if (empty($product)) {
            Soma_base::inst()->show_exception('缺少产品信息。');
        }

        $product = $product[0];
        $productPackageModel->appendEnInfo($product);
        $product['qty'] = 1;
        $product['price_package'] = $t_model->m_get('product_price');
        $product['can_refund'] = Soma_base::STATUS_FALSE;
        $product['setting_date'] = Soma_base::STATUS_FALSE;

        $order->product = array($product);
        $order->saler_id = '0';  // 没有saler_id
        $order->killsec_instance = 0;
        // var_dump($order);exit;
        $order = $order->order_save($t_model->m_get('business'), $t_model->m_get('inter_id'));   
        return $order;
    }

    /**
     * 生成兑换记录并修改兑换券状态
     * 
     * @param  [type] $order  订单信息
     * @param  [type] $openid 后台操作传空，前端操作传值
     * @param  [type] $admin  前台操作传空，后台操作传session->admin_profile
     * @return [type]         true|false
     */
    public function exchange($order, $openid = null, $admin = null) {

        $this->load->model('soma/Sales_voucher_exchange_model', 'e_model');
        /*
        $e_model = $this->Sales_voucher_exchange_model;
        $exchang_log['inter_id'] = $this->m_get('inter_id');
        $exchang_log['hotel_id'] = $this->m_get('hotel_id');
        $exchang_log['code'] = $this->m_get('code');
        $exchang_log['order_id'] = $order->m_get('order_id');

        if($admin) {
            // 后台用户操作记录后台用户信息
            $exchang_log['admin_id'] = $admin['admin_id'];
            $exchang_log['op_user'] = $admin['username'];
            $exchang_log['exchange_type'] = $e_model::EXCHANGE_TYPE_STORE_VOUCHER;
        }
        
        if($openid) {
            // 自助用户操作记录自助用户信息
            $this->load->model('wx/publics_model');
            $fans= $this->publics_model->get_fans_info( $openid );
            // var_dump($fans);exit;
            $exchang_log['openid'] = $openid;
            $exchang_log['op_user'] = isset($fans['nickname']) ? $fans['nickname'] : '自助兑换';
            $exchang_log['exchange_type'] = $e_model::EXCHANGE_TYPE_SELF_VOUCHER;
        }

        $exchang_log['create_time'] = date('Y-m-d H:i:s');
        $CI = & get_instance();
        $exchang_log['remote_ip'] = $CI->input->ip_address();
        $exchang_log['status'] = self::STATUS_TRUE;
        */
        if($this->e_model->record_exchange($order,$this, $openid, $admin)) {
            return $this->m_set('status', self::STATUS_USED)->m_save();
        } else {
            return false;
        }

    }

    /**
     * 订单虚拟支付  PAY_TYPE_VC
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
    public function virtual_pay($order) {
        $data = $order->m_data();
        try {
            $this->load->model('soma/sales_payment_model');
            $payment_model= $this->sales_payment_model;
            $log_data= array();
            $log_data['paid_ip'] = $this->input->ip_address();
            $log_data['paid_type']= $payment_model::PAY_TYPE_VC;

            $log_data['order_id'] = $data['order_id'];
            $log_data['openid'] = $data['openid'];
            $log_data['business'] = $data['business'];
            $log_data['settlement'] = $data['settlement'];
            $log_data['inter_id'] = $data['inter_id'];
            $log_data['hotel_id'] = $data['hotel_id'];
            $log_data['grand_total'] = $data['grand_total'];
            $log_data['transaction_id'] = '-1';
            
            $order->order_payment( $log_data );
            $order->order_payment_post( $log_data );
            $payment_model->save_payment($log_data, NULL);
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
    }


    /*
     * 保存订单 tid=>template_id , cid=>code_id
     * 1:生成订单，不分配资产，不分配消费码
     * 2:根据订单号进行核销，生成消费单，处理订单状态，处理兑换码表的状态
     * tips:生成订单和以前生成订单不一样，以前会分配资产，生成消费码，现在只生成订单
            消费的时候也不一样，没有了资产，只能从订单里处理消费
    */
    public function goto_exchange( $post, $inter_id, $business )
    {
        $return = array();
        try {

            //以下为保存订单主单和细单
            $code = isset( $post['code'] ) ? htmlspecialchars( $post['code'] ) : '';
            if( !$code ){
                $return['status'] = 2;
                $return['message'] = '参数不足！';
                return $return;
            }

            if( !$this->load_by_code($code) ){
                // die('没有找到兑换码信息！');
                $return['status'] = 2;
                $return['message'] = '没有找到兑换码信息！';
                return $return;
            }

            $is_true = $this->code_validation( array( 'inter_id'=>$inter_id ) );
            if( !$is_true ){
                // die('该兑换码已经核销！');
                $return['status'] = 2;
                $return['message'] = $this->code_valid_error();
                return $return;
            }

            //生成订单
            $OrderModel = $this->generate_order();//生成订单，返回的是订单model对象
            if( !$OrderModel->m_get( 'order_id' ) ){
                // die('生成订单错误！');
                $return['status'] = 2;
                $return['message'] = '生成订单错误！';
                return $return;
            }

            $return['order_id'] = $OrderModel->m_get( 'order_id' );

            //以上为保存订单主单和细单


            //以下为改变订单状态，核销订单，生成消费单
            $admin = $this->session->admin_profile;
            $this->admin = $admin;//后台兑换人员信息，生成兑换记录需要

            //进行核销改订单
            $this->load->model('soma/Consumer_order_model','ConsumerOrderModel');
            $ConsumerOrderModel = $this->ConsumerOrderModel;

            //订单对象
            $ConsumerOrderModel->order = $OrderModel;
            $ConsumerOrderModel->order_item = $OrderModel->get_order_items($business, $inter_id);

            //兑换码对象
            $ConsumerOrderModel->voucher = $this;

            $ConsumerOrderModel->consumer_qty = 1;//核销数量暂时为1
            $ConsumerOrderModel->consumer_person = $admin['username'];//核销人
            $ConsumerOrderModel->business = $business;
            $result = $ConsumerOrderModel->order_to_consumer($business, $inter_id);
            
            if( $result ){
                $return['status'] = 1;
                $return['message'] = '兑换成功';
            }else{
                $return['status'] = 2;
                $return['message'] = '兑换失败';
            }
            return $return;
            
        } catch (Exception $e) {
            $return['status'] = 2;
            $return['message'] = $e->getMessage();
            return $return;
        }
    }
}
