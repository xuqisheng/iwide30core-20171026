<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Club_customer_model extends MY_Model {

	public function get_resource_name()
	{
		return 'Club_customer_model';
	}

    const TAB_CLUB_CUSTOMER = 'club_customer';

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'club_customer';
	}

	public function table_primary_key()
	{
	    return 'customer_id';
	}

	public function attribute_labels()
	{
		return array(
		'customer_id'=> '编号',
        'name'=> '姓名',
		'openid'=> '会员订单/社群客订单',
		'inter_id'=> '公众号',
		'tel'=> '手机号',
		'apply_time'=> '申请时间',
		'update_time'=> '审核时间',
        'club_id'=> '社群客',
        'status'=> '状态'
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
		'customer_id',
        'name',
        'tel',
		'openid',
//		'inter_id',
		'apply_time',
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
	    /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
	    //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
	    $base_util= EA_base::inst();
	    $modules= config_item('admin_panels')? config_item('admin_panels'): array();


        $status = array (
            '1' => '通过',
            '2' => '未通过'
        );


        $orders = $this->a_orders();

	    return array(
                    'customer_id' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
                                        'club_id' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    'type' => 'combobox',
                    'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    'form_hide'=> TRUE,
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
                                        'name' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    //'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
                                        'tel' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    //'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
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

            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type' => 'combobox',
                'select' => $status,
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
            ),

            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type' => 'combobox',
                'select' => $orders,
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                    'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
            ),
            	    );
	}

	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'club_id', 'sort'=>'desc');
	}

	/* 以上为AdminLTE 后台UI输出配置函数 */


    function a_orders(){

        $db_read = $this->load->database('iwide_r1',true);

        $inter_id = $this->session->get_admin_inter_id ();

        $c_orders = $this->c_orders();

        $sql = "
                SELECT
                    openid,count(*) as total
                FROM
                    `iwide_hotel_orders`
                WHERE
                     inter_id = '{$inter_id}'
                GROUP BY
                    openid
        ";


        $orders = $db_read->query($sql)->result_array();

        $a_orders = array();

        if(!empty($orders)){
            foreach($orders as $arr){
                $a_orders[$arr['openid']] = $arr['total'].'/'.$c_orders[$arr['openid']];
            }
        }

        $club_customer = $db_read->where('inter_id',$inter_id)->get('iwide_club_customer')->result_array();

        foreach($club_customer as $customer){
            if(!isset($a_orders[$customer['openid']])){
                $a_orders[$customer['openid']] = 0;
            }
        }

        return $a_orders;

    }


    function c_orders(){

        $db_read = $this->load->database('iwide_r1',true);

        $inter_id = $this->session->get_admin_inter_id ();

        $c_orders = array();

        $club_id = $_GET['ids'];

        if(!empty($club_id)){

            $sql = "
                    SELECT
                        t1.openid,count(*) as total
                    FROM
                        `iwide_hotel_orders` as t1,
                        `iwide_hotel_order_items` as t2
                    WHERE
                         t1.inter_id = '{$inter_id}'
                    AND
                         t1.inter_id = t2.inter_id
                     AND
                         t1.orderid = t2.orderid
                    AND
                         t2.club_id = {$club_id}
                    GROUP BY
                         t1.openid,t1.orderid
            ";

            $orders = $db_read->query($sql)->result_array();

            if(!empty($orders)){
                foreach($orders as $arr){
                    $c_orders[$arr['openid']] = $arr['total'];
                }
            }

            $club_customer = $db_read->where('inter_id',$inter_id)->get('iwide_club_customer')->result_array();

            foreach($club_customer as $customer){

                if(!isset($c_orders[$customer['openid']])){
                    $c_orders[$customer['openid']] = 0;
                }
            }

        }

        return $c_orders;
    }


    function getClubCustomer($inter_id,$club_id){

        $this->db->where ('inter_id',$inter_id );
        $this->db->where ('club_id',$club_id );
        $res=$this->db->get( self::TAB_CLUB_CUSTOMER )->result_array();

        return $res;
    }

    function changeCustomerStatus($post_data,$update_data){

        $db = $this->db;
        $db->where ('club_id',$post_data['club_id'] );
        $db->where ('inter_id',$post_data['inter_id'] );
        $db->where ('customer_id',$post_data['customer_id']);
        $res=$db->update ( self::TAB_CLUB_CUSTOMER, $update_data );

        return $res;
    }

}
