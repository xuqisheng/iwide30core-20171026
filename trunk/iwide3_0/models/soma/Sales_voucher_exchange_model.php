<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_voucher_exchange_model extends MY_Model_Soma {

    const EXCHANGE_TYPE_STORE_VOUCHER = 1;//门店兑换
    const EXCHANGE_TYPE_SELF_VOUCHER = 2;//自助兑换

	public function get_resource_name()
	{
		return 'Sales_voucher_exchange_model';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function get_exchange_type_status_label()
    {
        return array(
                self::EXCHANGE_TYPE_STORE_VOUCHER=>'门店兑换',
                self::EXCHANGE_TYPE_SELF_VOUCHER=>'自助兑换',
            );
    }
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name( $inter_id=NULL )
	{
        return $this->_shard_table('soma_sales_voucher_exchange', $inter_id);
	}

    public function voucher_table_name( $inter_id=NULL )
    {
        return $this->_shard_table('soma_sales_voucher', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'record_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'record_id'=> '兑换记录ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'exchange_type'=> '兑换类型',
            'template_id' => '模板ID',
            'code'=> '券码',
            'product_id' => '产品ID',
            'product_name' => '产品名称',
            'product_price' => '产品价格',
            'exchange_qty' => '兑换数量',
            'openid'=> 'Openid',
            'order_id'=> '订单编号',
            'admin_id'=> '管理员ID',
            'op_user'=> '操作人',
            'create_time'=> '创建时间',
            'remote_ip'=> '操作IP',
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
            // 'inter_id',
            // 'hotel_id',
            // 'template_id',
            'exchange_type',
            // 'product_id',
            'product_name',
            'product_price',
            'exchange_qty',
            'code',
            // 'openid',
            'order_id',
            // 'admin_id',
            'op_user',
            'create_time',
            // 'remote_ip',
            // 'status',
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
            'record_id' => array(
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
                'type'=>'combobox',
                'select'=> $publics,
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
                'type'=>'combobox',
                'select'=> $hotels,
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
                'type'=>'text', //textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'exchange_type' => array(
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
                'select' => $this->get_exchange_type_status_label(),
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
            'product_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'product_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'product_price' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'exchange_qty' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'openid' => array(
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
            'order_id' => array(
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
            'admin_id' => array(
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
            'op_user' => array(
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
            'remote_ip' => array(
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
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $Somabase_util::get_status_options(),
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


    //根据兑换码查找兑换信息
    public function get_exchange_info_byCode( $code, $inter_id )
    {
        $filter = array();
        $filter['code'] = $code;
        $filter['inter_id'] = $inter_id;
        $table_name = $this->voucher_table_name($inter_id);
        return $this->_shard_db_r('iwide_soma_r')->where( $filter )->get($table_name)->row_array();
    }

    /**
     * 生成一条兑换记录
     * 
     * @param  [type] $order   兑换订单
     * @param  [type] $voucher 兑换卡
     * @param  [type] $openid  后台操作传空，前端操作传值
     * @param  [type] $admin   前台操作传空，后台操作传session->admin_profile
     * @param  [type] $consumer   消费对象，为了事务而设
     * @return [type]          true|false
     */
    public function record_exchange($order, $voucher, $openid = null, $admin = null, $db_trans_obj=null) {

        $exchang_log['inter_id'] = $voucher->m_get('inter_id');
        $exchang_log['hotel_id'] = $voucher->m_get('hotel_id');
        $exchang_log['code'] = $voucher->m_get('code');
        $exchang_log['order_id'] = $order->m_get('order_id');

        if($admin) {
            // 后台用户操作记录后台用户信息
            $exchang_log['admin_id'] = $admin['admin_id'];
            $exchang_log['op_user'] = $admin['username'];
            $exchang_log['exchange_type'] = self::EXCHANGE_TYPE_STORE_VOUCHER;
        }
        
        if($openid) {
            // 自助用户操作记录自助用户信息
            $this->load->model('wx/publics_model');
            $fans= $this->publics_model->get_fans_info( $openid );
            // var_dump($fans);exit;
            $exchang_log['openid'] = $openid;
            $exchang_log['op_user'] = isset($fans['nickname']) ? $fans['nickname'] : '自助兑换';
            $exchang_log['exchange_type'] = self::EXCHANGE_TYPE_SELF_VOUCHER;
        }

        $exchang_log['template_id'] = $voucher->m_get('template_id');
        $this->load->model('soma/Sales_voucher_template_model', 't_model');
        $tpl_model = $this->t_model->load($exchang_log['template_id']);
        if($tpl_model) {
            $exchang_log['product_id']    = $tpl_model->m_get('product_id');
            $exchang_log['product_name']  = $tpl_model->m_get('product_name');
            $exchang_log['product_price'] = $tpl_model->m_get('product_price');
            $exchang_log['exchange_qty']   = $tpl_model->m_get('exchange_qty');
        }

        $exchang_log['create_time'] = date('Y-m-d H:i:s');
        $CI = & get_instance();
        $exchang_log['remote_ip'] = $CI->input->ip_address();
        $exchang_log['status'] = self::STATUS_TRUE;

        //$db_trans_obj 开启事务的对象
        if( $db_trans_obj ){
            $inter_id = $voucher->m_get('inter_id');
            $table_name = $this->table_name( $inter_id );
            return $db_trans_obj->_shard_db( $inter_id )->insert( $table_name, $exchang_log );
        }else{
            return $this->m_sets($exchang_log)->m_save();
        }


    }

    //导出兑换纪录
    public function export_item( $business, $inter_id, $filter, $select='*', $start, $end )
    {
        $inter_id = isset( $inter_id ) ? $inter_id : $this->session->get_admin_inter_id();

        if( $inter_id == FULL_ACCESS ){
            
        } else if( $inter_id ) {
            $filter+= array('inter_id'=> $inter_id);
        }

        $db = $this->_shard_db_r('iwide_soma_r');

        if( count($filter)>0 ){
            foreach ($filter as $k=> $v){
                if(is_array($v)){
                    $db->where_in($k, $v);
                } else {
                    $db->where($k, $v);
                }
            }
        }
        if($start) {
            if( strlen($start)<=10 ) $start.= ' 00:00:00';
            $db->where('create_time >=', $start);
        }
        if($end) {
            if( strlen($end)<=10 ) $end.= ' 23:59:59';
            $db->where('create_time <', $end);
        }

        //不设定时间最多导出3个月的数据
        if(!$start && !$end){
            $db->where('create_time >', date('Y-m-d H:i:s', strtotime('-3 month') ) );
        }

        $table_name = $this->table_name( $inter_id );
        $result = $db->select( $select )
                        ->get( $table_name )
                        ->result_array();

        if( !$result ) return array();

        $exchangeTypes = $this->get_exchange_type_status_label();
        foreach( $result as $k=>$v ){
            $result[$k]['exchange_type'] = $exchangeTypes[$v['exchange_type']];
        }
        
        return $result;

    }
	
    /**
     * 新后台重写此方法,提供新后台的数据，注意保持ori_data中的原数据格式，避免其他地方调用异常
     *
     * @param      array   $params  The parameters
     * @param      array   $select  The select
     * @param      string  $format  The format
     */
    public function filter( $params=array(), $select= array(), $format='array' ) {
        $ori_data = parent::filter($params, $select, $format);
        return $this->get_new_backend_order_data($ori_data);
    }

    public function get_new_backend_order_data($ori_data) {
        $ids = array();
        foreach ($ori_data['data'] as $row) {
            $ids[] = $row['DT_RowId'];
        }
        $record_data = $this->find_all(array('record_id' => $ids));

        foreach ($record_data as $row) {
            $p_ids[] = $row['product_id'];
        }
        $this->load->model('soma/Product_package_model', 'somaProductModel');
        $p_data = $this->somaProductModel->find_all(array('product_id' => $p_ids));

        $fmt_data = array();
        foreach ($record_data as $row) {
            $fmt_data[$row['record_id']] = $row;
            foreach($p_data as $p_row) {
                if($p_row['product_id'] == $row['product_id']) {
                    $fmt_data[$row['record_id']]['product_info'] = $p_row;
                }
            }
        }

        $new_data = $ori_data;
        foreach ($ori_data['data'] as $key => $row) {
            $new_data['data'][$key]['new_info'] = array();
            if(isset($fmt_data[$row['DT_RowId']])) {
                $new_data['data'][$key]['new_info'] = $fmt_data[$row['DT_RowId']];
            }
        }
        return $new_data;
    }

}
