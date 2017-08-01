<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Message_wxtemp_record_model extends MY_Model_Soma {

    const STATUS_SUCCESS= 1;
    const STATUS_FAIL= 2;

    public function get_status_label()
    {
        return array(
            self::STATUS_SUCCESS=> '成功',
            self::STATUS_FAIL => '失败',
        );
    }
    
	public function get_resource_name()
	{
		return 'Message_wxtemp_record_model';
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
        return $this->_shard_table('soma_message_wxtemp_record', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'record_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'record_id'=> 'ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店ID',
            'template_id'=> '模板ID',
            'openid'=> 'Openid',
            'type'=> '模板类型',
            'msg'=> '发送消息',
            'result'=> '返回结果',
            'create_time'=> '发送时间',
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
            'record_id',
            'inter_id',
            'openid',
            //'template_id',
            'type',
            'msg',
            // 'result',
            'create_time',
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

	    /** 获取本管理员的酒店权限  */
	    $hotels_hash= $this->get_hotels_hash();
	    $publics = $hotels_hash['publics'];
	    $hotels = $hotels_hash['hotels'];
	    $filter = $hotels_hash['filter'];
	    $filterH = $hotels_hash['filterH'];
	    /** 获取本管理员的酒店权限  */

        $this->load->model('soma/Message_wxtemp_template_model');
        $get_template_types = $this->Message_wxtemp_template_model->get_template_type();

	    return array(
            'record_id' => array(
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
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $hotels,
            ),
            'template_id' => array(
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
            'type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $get_template_types,
            ),
            'msg' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' width="30%" ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                'grid_function'=> 'show_wxtemp_content',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'result' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $this->get_status_label(),
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'record_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    public function save_record( $data, $inter_id=NULL )
    {
        $num = count( $data );
        if( $num == 0 ){
            return FALSE;
        }

        $table_name = $this->table_name( $inter_id );
        $db = $this->_shard_db( $inter_id );
        
        $is_true = isset( $data[0] ) ? TRUE : FALSE;
        $result = FALSE;
        if( $is_true ){
            $result = $db->insert_batch( $table_name, $data );
        }else{
            $result = $db->insert( $table_name, $data );
        }

        return $result;
    }

    /**
     * @param array $data
     * @param bool $is_batch
     * @return bool|int|object
     * @author renshuai  <renshuai@mofly.cn>
     */
	public function save_records(Array $data, $is_batch = false)
	{
        if (empty($data)) {
            return false;
        }

        $table_name = $this->table_name();
        $db = $this->_shard_db();
        if ($is_batch) {
            $result = $db->insert_batch( $table_name, $data );
        } else {
            $result = $db->insert( $table_name, $data );
        }

        return $result;
	}

}

