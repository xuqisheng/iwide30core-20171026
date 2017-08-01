<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class staff_list_model extends MY_Model {

	public function get_resource_name()
	{
		return 'staff_list_model';
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
		return 'company_staff';
	}

	public function table_primary_key()
	{
	    return 'staff_id';
	}

	public function attribute_labels()
	{
		return array(
		'staff_id'=> '编号',
        'name'=> '姓名',
		'company_id'=> '公司名称',
		'openid'=> '微信ID',
		'inter_id'=> '公众号',
		'status'=> '状态',
		'tel'=> '联系电话',
		'apply_time'=> '申请时间',
		'update_time'=> '审核时间',
         'cp_id'=> '协议ID'

		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
		'staff_id',
        'name',
		'company_id',
//		'openid',
//		'inter_id',
//		'status',
		'tel',
		'apply_time',

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
        $db_read = $this->load->database('iwide_r1',true);

        $status = array (
            '1' => '通过',
            '2' => '未通过'
        );


        $companies_list = $db_read->get ( 'company_list' )->result ();

        $companies_name_list =array();

        foreach($companies_list as $arr){

            $companies_name_list[$arr->company_id]=$arr->company_name;

        }

	    return array(
                            'staff_id' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
                                        'company_id' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    'type' => 'combobox',
                    'select' => $companies_name_list,
                    'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    //'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
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
                                        'inter_id' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
                                        'status' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    'type' => 'combobox',
                     'select' => $status,
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                ),
                                        'name' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    //'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
                                        'tel' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    //'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'number',	//textarea|text|combobox|number|email|url|price
                ),
                                        'apply_time' => array(
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
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
                                                      'cp_id' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    //'form_hide'=> TRUE,
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
	    return array('field'=>'staff_id', 'sort'=>'desc');
	}

	/* 以上为AdminLTE 后台UI输出配置函数 */

    public  function query($data){


        $query=$this->_db()->query("SELECT *
                            FROM
                                `iwide_company_staff`
                            WHERE
                                openid='{$data['openid']}'
                             AND
                                company_id={$data['company_id']}
                            AND
                                hotel_id={$data['hotel_id']}
                            AND
                                inter_id='{$data['inter_id']}'
                                ");

        $result=$query->result();

        return $result;
    }


    public function new_staff($data){

        $query=$this->_db()->query("INSERT INTO
                                    `iwide_company_staff`(`staff_id`, `company_id`, `openid`, `inter_id`, `status`, `name`, `tel`, `apply_time`, `update_time`, `cp_id`, `hotel_id`)
                                    VALUES
                                    ('',{$data['company_id']},'{$data['openid']}','{$data['inter_id']}',1 ,'{$data['name']}','{$data['tel']}','{$data['apply_time']}','{$data['update_time']}',{$data['cp_id']},{$data['hotel_id']})");

    }


    public  function getCidByCpid($cp_id){

        $result=$this->_db()->query("SELECT *
                            FROM
                                `iwide_company_price`
                            WHERE
                                cp_id={$cp_id}
                                ")->row();

        $company_id = $result->company_id;

        return $company_id;


    }


}
