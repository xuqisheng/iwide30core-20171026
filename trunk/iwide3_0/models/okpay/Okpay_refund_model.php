<?php
class Okpay_refund_model extends MY_Model{
    function __construct() {
        parent::__construct ();
    }

    const TAB_OKPAY_REFUND = 'okpay_refund';
    public function get_resource_name()
    {
    	return 'Okpay_refund_model';
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
    	return self::TAB_OKPAY_REFUND;
    }
    
    public function table_primary_key()
    {
    	return 'id';
    }
    
    public function attribute_labels()
    {
    	return array(
    			'id'=> '编号',
    			'nickname'=> '用户昵称',
    			'create_time'=> '创建时间',
    			'update_time'=> '更新时间',
    			'refund_time'=> '退款时间',
    			'refund_fee'=> '实退金额',
    			'refund_money'=> '应退金额',
    			'refund_status'=> '退款状态',
    			'out_trade_no'=> '订单号',
    			'out_refund_no'=> '退款单号',
    			'refund_id'=> '退款交易号',
    			'inter_id'=> '公众号',
    			'hotel_id'=> '酒店',
    			
    			'openid'=>'openid',
    			'trade_no'=>'交易号',
    			'coupon_refund_fee'=>'优惠立减退款金额',
    			'status'=>'记录状态',
    			'appid'=>'公众号id',
    			'mch_id'=>'商户号',
    	);
    }
    
    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields()
    {
    	//主键字段一定要放在第一位置，否则 grid位置会发生偏移
    	return array(
    			'id',
    			'nickname',
    			'refund_fee',
    			'refund_money',
    			'out_trade_no',
    			'out_refund_no',
    			'refund_status',
    			'inter_id',
    			'hotel_id',
    			'create_time',
    			'refund_time',
    			/* 'update_time',
    			
    			'openid',
    			'trade_no',
    			'coupon_refund_fee',
    			'status',
    			'appid',
    			'mch_id', */
    			
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
    	$base_util = EA_base::inst();
    	$modules   = config_item('admin_panels')? config_item('admin_panels'): array();
    	/** 获取本管理员的酒店权限  */
    	$this->_init_admin_hotels ();
    	$publics = $hotels = array ();
    	$filter = $filterH = NULL;
    	
    	if ($this->_admin_inter_id == FULL_ACCESS)
			$filter = array ();
		else if ($this->_admin_inter_id)
			$filter = array (
					'inter_id' => $this->_admin_inter_id 
			);
		if (is_array ( $filter )) {
			$this->load->model ( 'wx/publics_model' );
			$publics = $this->publics_model->get_public_hash ( $filter );
			$publics = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
			// $publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
		}
		
		if ($this->_admin_hotels == FULL_ACCESS)
			$filterH = array ();
		else if ($this->_admin_hotels)
			$filterH = array (
					'hotel_id' => $this->_admin_hotels 
			);
		else
			$filterH = array ();
		
		if ($publics && is_array ( $filterH )) {
			$this->load->model ( 'hotel/hotel_model' );
			$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
			$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
			$hotels = $hotels + array (
					'0' => '-不限定-' 
			);
		}
    	return array(
    			'id' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '5%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'nickname' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'update_time' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					'function'=> 'unix_to_human|true|cn2',
    					'type'=>'datebox',	//textarea|text|combobox|number|email|url|price
    			),
    			'create_time' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					'function'=> 'unix_to_human|true|cn2',
    					'type'=>'datebox',	//textarea|text|combobox|number|email|url|price
    			),
    			'refund_time' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					'function'=> 'unix_to_human|true|cn2',
    					'type'=>'datebox',	//textarea|text|combobox|number|email|url|price
    			),
    			'refund_fee' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '8%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					'function'=> 'string_format_fee_to_money|true|cn2',
    					'type'=>'price',	//textarea|text|combobox|number|email|url|price
    			),
    			'refund_money' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '8%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					//'function'=> 'unix_to_human|true|cn2',
    					'type'=>'price',	//textarea|text|combobox|number|email|url|price
    			),
    			'refund_status' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
//     					'function'=> 'unix_to_human|true|cn',
    					'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
    					'select' => array(1 => '退款中', 3 => '退款成功')
    			),
    			'out_trade_no' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
//     					'function'=> 'unix_to_human|true|cn',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'out_refund_no' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
//     					'function'=> 'unix_to_human|true|cn',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    					
    			),
    			'refund_id' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
//     					'function'=> 'unix_to_human|true|cn',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    					
    			),
    			'inter_id' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
    					'select'=>$publics
    			),
    			'hotel_id' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
    					'select' => $hotels
    			),
    			'openid' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'trade_no' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'coupon_refund_fee' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'status' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'appid' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'mch_id' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			), 
    			
    	);
    }
    
    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field()
    {
    	return array('field'=>'id', 'sort'=>'desc');
    }
    
    public function create_okpay_refund($arr){
        $arr['create_time'] = time();
        $arr['update_time'] = time();
        $arr['status']		= 1;

        $this->db->insert(self::TAB_OKPAY_REFUND,$arr);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            return true;
        }else{
            return false;
        }
    }

    function get_okpay_refund_detail($out_refund_no,$status = 1) {
        $type = $this->_db('iwide_r1')->get_where ( self::TAB_OKPAY_REFUND, array (
            'out_refund_no'=>$out_refund_no,
            'status' => $status
        ) )->row_array();

        return $type;
    }
    
    function set_okpay_refund($out_refund_no,$trade_no,$data){
    	$this->db->where ( array (
    			'out_refund_no' => $out_refund_no,
    			'trade_no' => $trade_no
    	) );
    	
    	$data['refund_status'] 	= 3;
    	$data['refund_time'] 	= time();
    	$data['update_time'] 	= time();
    	$result = $this->db->update (self::TAB_OKPAY_REFUND,$data);
    	if($result > 0){
    		return true;
    	}else{
    		return false;
    	}
    }

}
