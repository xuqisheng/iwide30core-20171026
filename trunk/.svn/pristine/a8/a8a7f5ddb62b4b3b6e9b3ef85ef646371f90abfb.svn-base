<?php


class Member_card_model extends MY_Model {

	function __construct() {
		parent::__construct ();
	}
	
	public function get_resource_name()
	{
		return 'Member_card_model';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function _shard_db($inter_id=NULL)
    {
        return $this->_db();
    }

    public function _shard_table($basename, $inter_id=NULL )
    {
        return $basename;
    }

	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'member_card_order';
	}

	public function table_primary_key()
	{
	    return 'co_id';
	}

	public function attribute_labels()
	{
		return array(
		'co_id'=> 'ID',
        'mem_id'=>'会员ID',
		'order_number'=> '订单号',
		'ci_id'=> '券名',
        'amount'=>'总价',
        'name'=>'姓名',
        'telephone'=>'电话',
        'identity_card'=>'身份证',
		'create_time'=> '购买时间',
        'distribution_no'=>'分销号',
        'saler_name'=>'分销员'
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'co_id',
            'mem_id',
            'order_number',
            'ci_id',
            'amount',
            'name',
            'telephone',
            'identity_card',
	    	'create_time',
            'distribution_no',
            'saler_name'
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
                            'co_id' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
//                     'select'=>$this->getCompanyName(),
//                     'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                ),

            'mem_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
//                     'select'=>$this->getCompanyName(),
//                     'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
            ),
                                        'order_number' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '20%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
//                                        'inter_id' => array(
//                    'grid_ui'=> '',
//                    'grid_width'=> '10%',
//                    //'form_ui'=> ' disabled ',
//                    //'form_default'=> '0',
//                    //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
//                    //'function'=> 'show_price_prefix|￥',
//                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
//                ),
                                          'amount' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),

            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'telephone' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'identity_card' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),

                                        'ci_id' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
                    'select'=>$this->getCardTitle(),
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                ),

            'distribution_no' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),

            'saler_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
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
	    return array('field'=>'co_id', 'sort'=>'DESC');
	}

	/* 以上为AdminLTE 后台UI输出配置函数 */


    public function  getCardTitle(){

        $result=$this->db->get('iwide_member_card_infomation')->result_array();

        foreach($result as $arr){

            $card_list[$arr['ci_id']] = $arr['title'];

        }

        return $card_list;

    }


}
