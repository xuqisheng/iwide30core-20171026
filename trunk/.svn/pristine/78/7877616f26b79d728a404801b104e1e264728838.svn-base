<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_reserve_model extends MY_Model_Soma {

    const STATUS_SUCCESS = 1;
    const STATUS_FAILURE = 2;
    const STATUS_WAITTING = 3;

    const RESERVE_BUSINESS = 'package';

	public function get_resource_name()
	{
		return '大客户预订';
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
		return $this->_shard_table('soma_sales_reserve_order',$inter_id);
	}

	public function table_primary_key()
	{
	    return 'reserve_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'reserve_id'=> '订购单ID',
            'inter_id'=> '公众号ID',
            'hotel_id'=> '酒店ID',
            'openid'=> 'Openid',
            'sku'=> 'Sku',
            'product_id'=> '商品ID',
            'name'=> '商品名',
            'qty'=> '订购数量',
            'customer_name'=> '客户名称',
            'customer_tel'=> '客户电话',
            'customer_com'=> '企业信息',
            'business'=> '业务类型',
            'create_time'=> '创建时间',
            'update_time'=> '更新时间',
            'order_id'=> '销售单ID',
            'product_price'=> '产品单价',
            'grand_total'=> '收款总计',
            'discount'=> '折扣',
            'salesman'=> '销售人',
            'comfirmed_status'=> '确认状态',
            'comfirmed_note'=> '付款备注',
            'comfirmed_time'=> '确认时间',
            'comfirmed_user'=> '确认人',
            'reviewed_status'=> '审核状态',
            'reviewed_note'=> '财务备注',
            'reviewed_time'=> '审核时间',
            'reviewed_user'=> '审核人',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'reserve_id',
            // 'inter_id',
            // 'hotel_id',
            // 'openid',
            // 'sku',
            'product_id',
            'name',
            'qty',
            'customer_name',
            'customer_tel',
            'customer_com',
            // 'business',
            'create_time',
            'update_time',
            // 'order_id',
            // 'product_price',
            'grand_total',
            // 'discount',
            'salesman',
            'comfirmed_status',
            // 'comfirmed_note',
            // 'comfirmed_time',
            // 'comfirmed_user',
            'reviewed_status',
            // 'reviewed_note',
            // 'reviewed_time',
            // 'reviewed_user',
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
	    /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
	    //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
	    $base_util= EA_base::inst();
	    $modules= config_item('admin_panels')? config_item('admin_panels'): array();

	    return array(
            'reserve_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            /*
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'sku' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            */
            'product_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'qty' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'customer_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'customer_tel' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'customer_com' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            /*
            'business' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            */
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                // 'form_tips'=> '预订订单确认后才会生成销售单号',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'product_price' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                // 'form_tips'=> '预订订单确认后才会生成算出单价',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'grand_total' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                // 'form_tips'=> '收款总价',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            /*
            'discount' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            */
            'salesman' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                // 'form_tips'=> '销售人',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'comfirmed_status' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=>array(
                    // '2' => '待确认',
                    // '1' => '确认成功',
                    // '3' => '确认失败',
                    '1' => '确认成功',
                    '2' => '确认失败',
                    '3' => '待确认',
                ),
            ),
            'comfirmed_note' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'textarea', //textarea|text|combobox|number|email|url|price
            ),
            'comfirmed_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'comfirmed_user' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'reviewed_status' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=>array(
                    // '2' => '待审核',
                    // '1' => '审核通过',
                    // '3' => '审核不通过',
                    '1' => '审核通过',
                    '2' => '审核不通过',
                    '3' => '待审核'
                ),
            ),
            'reviewed_note' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'textarea', //textarea|text|combobox|number|email|url|price
            ),
            'reviewed_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'reviewed_user' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'reserve_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    public function generate_order() {

        $this->m_set('product_price', $this->grand_total / $this->qty);
        $this->load->model('soma/Sales_order_model','order');
        $order = $this->order;

        $order->business = $this->business;
        $order->settlement = self::SETTLE_WHOLESALE;
        $order->inter_id = $this->inter_id;
        if($this->m_get('hotel_id') !=NULL ){
            $order->hotel_id = $this->hotel_id;
        }
        $order->openid = $this->openid;
        // $order->row_qty = 0;
        // $order->row_total = $this->grand_total;
        // $order->subtotal = 0;
        // $order->grand_total = $this->grand_total;
        $order->discount = array();

        $this->load->library('Soma/Api_member');
        $api= new Api_member($this->inter_id);
        $result= $api->get_token();
        $api->set_token($result['data']);

        // 查询会员信息
        $member_info = $api->get_member_info($this->openid);
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

        // 包含用户类文件，加载用户类
        require_once dirname(__FILE__). DS. 'Sales_order_model.php';
        $customer = new Sales_order_attr_customer($this->openid);
        $order->customer = $customer;

        $this->load->model('soma/Product_package_model','ppm');
        $pid_arr = array($this->product_id);
        $tmp_p_arr = $this->ppm->get_product_package_by_ids($pid_arr,$this->inter_id);

        if(count($tmp_p_arr) <= 0) {
            return false;
        }

        $p_arr = array();
        foreach ($tmp_p_arr as $p) {
            $p['qty'] = $this->qty;
            $p['price_package'] = $this->product_price;
            $p_arr[] = $p;
        }

        $order->product = $p_arr;

        $order->saler_id = '0';  // 没有saler_id
        // var_dump($order);exit;
        $order->order_save($this->business, $this->inter_id);

        return $order;
    }

    public function get_order() {
        $this->load->model('soma/Sales_order_model','order');
        $this->order->load($this->order_id);
        return $this->order;
    }

    public function get_status_select_html($key = 'comfirmed_status') {
        $status = $this->comfirmed_status;
        $status_name = '确认';
        if($key == 'reviewed_status') {
            $status = $this->reviewed_status;
            $status_name = '审核';
        }

        $options = array(
            '1' => $status_name . '通过',
            '2' => $status_name . '不通过',
            '3' => '待' . $status_name,
        );

        $html = '';
        foreach ($options as $k => $v) {
            $html .= "<option value='$k'";
            if($k == $status) {
                $html .= " selected='selected'";
            }
            $html .= ">$v</option>";
        }

        return $html;
    }

}
