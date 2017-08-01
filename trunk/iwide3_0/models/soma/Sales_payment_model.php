<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_payment_model extends MY_Model_Soma {

    const PAY_TYPE_WX = '11';
    const PAY_TYPE_OK = '21';
    const PAY_TYPE_HD = '22';
    const PAY_TYPE_CP = '23';
    const PAY_TYPE_JF = '24';
    const PAY_TYPE_CZ = '25';
    const PAY_TYPE_XX = '31';
    const PAY_TYPE_WFT = '41';
    
    const PAY_TYPE_VC = '32';
    const PAY_TYPE_DF = '51';

    public function get_payment_label()
    {
        return array(
            //self::PAY_TYPE_WX => '支付宝',
            self::PAY_TYPE_WX => '微信支付',
            //self::PAY_TYPE_OK => '快乐付',
            self::PAY_TYPE_HD => '活动减免',
            self::PAY_TYPE_CP => '优惠券减免',
            self::PAY_TYPE_JF => '积分支付',
            self::PAY_TYPE_CZ => '储值支付',
            self::PAY_TYPE_XX => '线下支付',
            self::PAY_TYPE_WFT => '威富通支付',
            
            self::PAY_TYPE_VC => '礼品卡券',
            self::PAY_TYPE_DF => '订房套餐',
        );
    }
    
	public function get_resource_name()
	{
		return '支付记录';
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
		return $this->_shard_table('soma_sales_payment', $inter_id);
	}

	public function table_primary_key()
	{
	    return 'log_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'log_id'=> 'Log_id',
            'order_id'=> 'Order_id',
            'inter_id'=> 'Inter_id',
            'hotel_id'=> 'Hotel_id',
            'openid'=> 'Openid',
            'business'=> 'Business',
            'transaction_id'=> '交易流水号',
            'grand_total'=> 'Order_amount',
            'paid_type'=> 'Paid_type',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'log_id',
            'order_id',
            'inter_id',
            'hotel_id',
            'openid',
            'business',
            'grand_total',
            'paid_type',
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
            'log_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
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
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'hotel_id' => array(
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
            'business' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'transaction_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'grand_total' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'paid_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
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
	    return array('field'=>'log_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	public function save_payment($data, $write_xml= NULL)
	{
	    //insert payment record; 
	    $result= $this->m_sets($data)->m_save();
	    if($write_xml){
	        $path= APPPATH. 'logs'. DS. 'payment'. DS;
	        if( !file_exists($path) ) {
	            @mkdir($path, 0777, TRUE);
	        }
	        if( $data['paid_type']== self::PAY_TYPE_WX )
	            $file= $path. 'soma_wxpay_return_'. date('Y-m-d'). '.txt';
	        elseif( $data['paid_type']== self::PAY_TYPE_VC )
	            $file= $path. 'soma_voucher_return_'. date('Y-m-d'). '.txt';
	        else
	            $file= $path. 'soma_payreturn_'. date('Y-m-d'). '.txt';
	        $this->write_log($write_xml, $file);
	    }
	    return $result;
	}

    //根据订单号获取支付方式
    public function get_paid_type_byOrderIds( $orderIds, $inter_id, $select='*' )
    {
        $table_name = $this->table_name( $inter_id );
        return $this->_shard_db_r('iwide_soma_r')
                        ->select( $select )
                        ->where( 'inter_id', $inter_id )
                        ->where_in( 'order_id', $orderIds )
                        ->get( $table_name )
                        ->result_array();
    }
	
	
}
