<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use App\libraries\Support\Log;

class Consumer_shipping_model extends MY_Model_Soma {

    /**
     * 配送信息（数组）
     * @var Gift_order_attr_customer
     */
    public $shipping_info=array();

    const STATUS_APPLY   = 1;
    const STATUS_SHIPPED = 2;
    const STATUS_RECEIVED= 3;
    const STATUS_HOLDING = 4;
    const STATUS_FINISHED = 5;
    const STATUS_WAITTING_PAY = 6; // 运费待支付
    const STATUS_SHIPPED_FAIL = 7;

    const CAN_MAIL_YES = 1;//可以邮寄
    const CAN_MAIL_NO = 2;//不可以邮寄

    /**
     * 下拉列表redis key值
     */
    const DIST_NAME_LABEL = 'DIST_NAME_LABEL';

    public function get_status_label()
    {
        return array(
            self::STATUS_APPLY   => '邮寄申请',
            self::STATUS_SHIPPED => '邮寄发货',
            //self::STATUS_RECEIVED=> '已接受',
            self::STATUS_HOLDING => '异常挂起',
            self::STATUS_FINISHED => '已签收',
            self::STATUS_WAITTING_PAY => '待付运费',
            self::STATUS_SHIPPED_FAIL => '下单失败'
        );
    }

    public function get_status_label_lang_key()
    {
        return array(
            self::STATUS_APPLY => 'mail_application',
            self::STATUS_SHIPPED => 'mail_delivery',
            self::STATUS_HOLDING => 'loading',
            self::STATUS_FINISHED => 'loading',
            self::STATUS_WAITTING_PAY => 'loading',
        );
    }

    public function get_order_detail_status_label() {
        return array(
            self::STATUS_APPLY   => $this->lang->line('receive_orders'),
            self::STATUS_SHIPPED => $this->lang->line('mailed'),
            //self::STATUS_RECEIVED=> '已接受',
            self::STATUS_HOLDING => $this->lang->line('receive_orders'),  // 对客户不显示异常挂起
            self::STATUS_FINISHED => $this->lang->line('signed'),
            self::STATUS_WAITTING_PAY => $this->lang->line('to_be_done'),
        );
    }

    /**
     * 能否进行发货处理
     * @return multitype:string
     */
    public function can_shipped_status()
    {
        return array( self::STATUS_APPLY, );
    }

    public function can_hold_status()
    {
        return array( self::STATUS_APPLY, );
    }

	public function get_resource_name()
	{
		return '邮寄记录';
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
		return $this->_shard_table('soma_consumer_shipping', $inter_id);
	}
	public function dist_table_name($inter_id=NULL)
	{
		return $this->_shard_table('soma_cms_distributor', $inter_id);
	}

	public function table_primary_key()
	{
	    return 'shipping_id';
	}

	public function attribute_labels()
	{
		return array(
            'shipping_id'=> 'ID',
            'order_id'=> '订单号',
            'shipping_order'=> '邮费订单号',
            'shipping_fee' => '邮费金额',
            'consumer_id'=> '消费记录',
            'product_id'=> '商品ID',
            'name'=> '配送商品',
            'qty'=> '配送数量',
            'inter_id'=> '公众号',
            'hotel_id'=> '所属酒店',
            'openid'=> 'Openid',
            'address_id'=> '用户地址',
            'address'=> '地址信息',
            'contacts'=> '联系人',
            'phone'=> '联系电话',
            'reserve_date'=> '预约发货',
            'create_time'=> '创建时间',
            'post_fee'=> '邮寄费用',
            'post_time'=> '邮寄时间',
            'distributor'=> '配送商',
            'tracking_no'=> '配送单号',
            'note'=> '用户备注',
            'post_admin'=> '发货操作人',
            'remote_ip'=> '发货操作IP',
            'status'=> '当前状态',
            'remark'=> '地址备注',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'shipping_id',
            'order_id',
            'shipping_order',
            'shipping_fee',
            'reserve_date',
            'post_time',
            'distributor',
            'tracking_no',
            'address',
            'contacts',
            'phone',
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

	    return array(
            'shipping_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'shipping_order' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'shipping_fee' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'address' => array(
                'grid_ui'=> '',
                'grid_width'=> '30%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'contacts' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'phone' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'reserve_date' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'post_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'post_admin' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'post_fee' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',	//textarea|text|combobox|number|email|url|price
            ),
            'distributor' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' required ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_distributor_select_option(),
            ),
            'tracking_no' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' required ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'note' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'textarea', //textarea|text|combobox|number|email|url|price
            ),
            'remark' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' rows="3" ',
                //'form_default'=> '0',
                'form_tips'=> '请将邮寄地址修改内容填写在这里',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'textarea',	//textarea|text|combobox|number|email|url|price
            ),
            'remote_ip' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> self::get_status_label(),
            ),
	    );
	}

	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'shipping_id', 'sort'=>'desc');
	}

	/* 以上为AdminLTE 后台UI输出配置函数 */


	/**
	 * 保存消费配送信息
	 */
	public function save_shipping( $consumer_model, $address_model, $inter_id, $business, $openid = null )
	{
        $this->load->model('soma/Cms_region_model','CmsRegionModel');
        $CmsRegionModel = $this->CmsRegionModel;

        $province = $address_model->m_get('province');
        if( isset( $province ) ){
            $address = $CmsRegionModel->get_address_detail( $address_model->m_get('province'), $address_model->m_get('city'), $address_model->m_get('region') );
            $address .= $address_model->m_get('address');
        }else{
            $address = '';
        }

        $shipping_info = $address_model->shipping_info;

        $status = self::STATUS_APPLY;
        $datetime = isset( $shipping_info['datetime'] ) && !empty( $shipping_info['datetime'] ) ? $shipping_info['datetime'] : NULL;
        if( $datetime ){
            $status = self::STATUS_APPLY;
        }

        // 存在差价补偿订单，邮寄状态标记为待付运费
        // if(isset($consumer_model->shipping_fee)
        //     && $consumer_model->shipping_fee > 0) {
        //     $status = self::STATUS_WAITTING_PAY;
        // }

        $note = isset( $shipping_info['note'] ) && !empty( $shipping_info['note'] ) ? htmlspecialchars( $shipping_info['note'] ) : NULL;
        $contacts = $address_model->m_get('contact') ? $address_model->m_get('contact') : '';
        $phone = $address_model->m_get('phone') ? $address_model->m_get('phone') : '';

        $is_wx_address = isset( $shipping_info['is_wx_address'] ) ? $shipping_info['is_wx_address'] : '';
        // if( $is_wx_address && $is_wx_address == 1 ){
            $contacts = $shipping_info['contact'];//联系人
            $phone = $shipping_info['phone'];//联系电话
            $address = $shipping_info['address'];//收货地址
        // }

        // $items= $consumer_model->get_order_items($business, $inter_id);
        // $item= isset($items[0])? $items[0]: array();
        $items = $consumer_model->asset_item;
        $item= isset($items[0])? $items[0]: array();

	    //组装信息
        $data = array(
            'order_id'=>$consumer_model->order_id,
            'shipping_order'=> isset($consumer_model->shipping_order) ? $consumer_model->shipping_order : '',
            'shipping_fee' => isset($consumer_model->shipping_fee) ? $consumer_model->shipping_fee : '',
            'consumer_id'=>$consumer_model->consumer_id,
            'product_id'=> isset($item['product_id'])? $item['product_id']: '',
            'name'=> isset($item['name'])? $item['name']: '',
            'qty'=> $consumer_model->consumer_qty,
            'inter_id'=>$inter_id,
            'hotel_id'=>$consumer_model->hotel_id,
            'openid'=> ($openid == null) ? $this->openid : $openid,
            'address_id'=>$address_model->m_get('address_id') ? $address_model->m_get('address_id') : '',
            'address'=>$address,
            'contacts'=>$contacts,
            'phone'=>$phone,
            'reserve_date'=>$datetime,
            'create_time'=>date("Y-m-d H:i:s", time()),
            'status'=>$status,
            'note'=>$note,
        );

        $table_name = $this->table_name( $inter_id );
        // return $consumer_model->_shard_db( $inter_id )->insert( $table_name, $data );
        $rs = $consumer_model->_shard_db( $inter_id )->insert( $table_name, $data );
        $insertId = $consumer_model->_shard_db( $inter_id )->insert_id();

        // 发送邮寄申请后台通知
        if($insertId !== 0 && ENVIRONMENT != 'dev') {
            $this->load->model('soma/message_wxtemp_template_model', 'message_model');
            if ($message = $this->message_model->getOrderAdminNoticeMessage($insertId)) {
                $this->load->model('hotel/hotel_notify_model');
                $this->hotel_notify_model->insert_wxmsg_queue(
                    $data['inter_id'],
                    $data['hotel_id'],
                    'soma',
                    16,
                    $message
                );
            }
        }

        //这里设置session的作用是，把邮寄ID传回用于调整邮寄详情页
        $this->load->library('session');
        $this->session->set_userdata('spid', $insertId);

        return $rs;
	}

	/**
	 * 保存消费配送信息
	 * Usage:
	 *    $model->load($id)->post_shipping($data)
	 */
	public function post_shipping( $post_data )
	{
	    /** 配送商  **/
	    $distributor= empty($post_data['distributor'])? '': $post_data['distributor'];
	    /** 配送单号  **/
	    $tracking_no= empty($post_data['tracking_no'])? '': $post_data['tracking_no'];

	    if( $this->m_get('shipping_id') && $distributor && $tracking_no ){
	        //判断能否进行邮寄
	        $data= array();
	        $status= $this->m_get('status');
	        if( in_array($status, $this->can_shipped_status()) ) {
	            $data['distributor']= $distributor;
	            $data['tracking_no']= $tracking_no;
	            $data['post_time']= date('Y-m-d H:i:s');
	            $data['post_fee']= empty($post_data['post_fee'])? '0.00': $post_data['post_fee'];   //配送费用
	            $data['post_admin']= empty($post_data['post_admin'])? '': $post_data['post_admin'];
	            $data['remote_ip']= empty($post_data['remote_ip'])? '': $post_data['remote_ip'];
	            $data['status']= self::STATUS_SHIPPED;
                $data['remark'] = empty($post_data['remark'])? '': $post_data['remark'];
	            return $this->m_save($data);

	        } else {
	            Soma_base::inst()->show_exception('该状态不能做发货处理');
	        }

	    } else {
	        Soma_base::inst()->show_exception('参数错误，请仔细填写发货信息');
	    }
	}

	/**
	 * 挂起异常的配送信息
	 * Usage:
	 *    $model->load($id)->hold_shipping()
	 */
	public function hold_shipping($revert=FALSE)
	{
	    if($revert==TRUE){
	        //判断能否进行挂起？
	        $status= $this->m_get('status');
	        if( in_array($status, $this->can_hold_status()) ) {
	            $result= $this->m_set('status', self::STATUS_HOLDING )->m_save();
	            return $result;

	        } else {
	            Soma_base::inst()->show_exception('该状态不能做挂起处理');
	        }
	    } else {
	        $result= $this->m_set('status', self::STATUS_APPLY )->m_save();
	        return $result;
	    }
	}

    /**
     * 显示配送订单列表
     * Usage: $model->get_order_list('package', 'a123456789', array('openid'=>'asgaehae'), 'order_id desc', '20,150' );
     */
    public function get_shipping_list($business, $inter_id, $filter, $sort=NULL, $limit=NULL )
    {
        return $this->find_all($filter, $sort, $limit);
    }

    //修改配送信息
    public function edit_shipping_info( $business, $inter_id, $filter, $data )
    {
        if( count( $filter ) == 0 || count( $data ) == 0 ){
            return FALSE;
        }
        $data = $this->_addslashes( $data );

        if( isset( $data['reserve_date'] ) && $data['reserve_date'] == '') $data['reserve_date'] = null;

        $table_name = $this->table_name( $inter_id );
        return $this->_shard_db( $inter_id )->where( $filter )->update( $table_name, $data );
    }

    //根据order_id，consumer_id查找shipping_id
    public function get_shipping_id( $order_id, $consumer_id, $inter_id, $business )
    {
        if( !$order_id || !$consumer_id ){
            return FALSE;
        }

        $filter = array();
        $filter['order_id'] = $order_id;
        $filter['consumer_id'] = $consumer_id;
        $filter['inter_id'] = $inter_id;

        $table_name = $this->table_name( $inter_id );
        return $this->_shard_db_r('iwide_soma_r')
                    ->where( $filter )
                    ->select('shipping_id')
                    ->get( $table_name )
                    ->row_array();
    }


    /* 用配送商标识获取配送商记录 */
    public function get_distributor_byname($name)
    {
        return $this->find( array('dist_name'=> $name) );
    }
    /* 用配送商标识获取配送商名称 */
    public function get_label_byname($name)
    {
        $table= $this->dist_table_name();
        return $this->_shard_db_r('iwide_soma_r')
                    ->get_where($table, array('status'=> self::STATUS_TRUE,'dist_name'=>$name) )
                    ->row_array();
    }
    /* 展示配送商的hash数组 */
    public function get_distributor_select_option()
    {
        $table= $this->dist_table_name();
        $data= $this->_shard_db_r('iwide_soma_r')
                    ->get_where($table, array('status'=> self::STATUS_TRUE) )
                    ->result_array();

        $option= $this->array_to_hash($data, 'dist_label', 'dist_name');
        return array(''=> ' - ')+ $option;
    }
    /* 展示配送商的hash数组 */
    public function get_distributor_select_html($selected=NULL, $has_wrap=TRUE)
    {
        $html= '';
        $vdata= array();
        $table= $this->dist_table_name();
        $data= $this->_shard_db_r('iwide_soma_r')
                    ->get_where($table, array('status'=> self::STATUS_TRUE) )
                    ->result_array();

        foreach ($data as $k=>$v){
            $vdata[$v['dist_char']][$v['dist_id']]= array(
                'dist_name'=> $v['dist_name'],
                'dist_label'=> $v['dist_label'],
            );
        }
        if( $has_wrap ) $html.= "<select class='form-control selectpicker show-tick' data-live-search='true' name='distributor' id='el_distributor'>";
        $has_option= FALSE;
        foreach ($vdata as $k=>$v){
            $html.= "<optgroup label='---【{$k}】字母开头---'>";
            foreach ($v as $sk=>$sv){
                if($selected && $selected==$sv['dist_name']) {
                    $slt= ' selected="selected" ';
                    $has_option= TRUE;
                } else {
                    $slt= '';
                }

                $html.= "<option value='{$sv['dist_name']}' {$slt} >{$sv['dist_label']}</option>";
            }
            $html.= "</optgroup>";
        }
        if( $has_option==FALSE ) $html= '<option value="" selected="selected" >请选择服务商</option>'. $html;
        if( $has_wrap ) $html.= "</select>";
        echo $html;
    }

    public function export_item( $inter_id, $filter, $select, $start, $end )
    {
        $business = 'package';
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

        $result = $db->select($select)
                        ->order_by('consumer_id desc')
                        ->get( $this->table_name( $inter_id ) )
                        ->result_array();
        //echo $this->_shard_db()->last_query();die;

        //添加购买人/联系电话关联查询
        $contact= array();
        $contacts= $this->_shard_db_r('iwide_soma_r')
                        ->select('openid,name,mobile')
                        ->where('inter_id', $inter_id)
                        ->get('soma_customer_contact')
                        ->result_array();

        foreach ($contacts as $k=>$v){
            $contact[$v['openid']]= array('name'=>$v['name'], 'mobile'=>$v['mobile']);
        }

        //进行部分字段转换
        $distributor= $consumer_ids= array();
        $status_arr= $this->get_status_label();
        foreach ($result as $k=>$v){
            $distributor[]= $v['distributor'];
            if( array_key_exists($result[$k]['status'], $status_arr) ){
                $result[$k]['status']= $status_arr[$result[$k]['status']];
            }
            if( isset($contact[$v['openid']]) ){
                $result[$k]['purchase']= $contact[$v['openid']]['name'];
                $result[$k]['mobile']= $contact[$v['openid']]['mobile'];
            } else {
                $result[$k]['purchase']= '-';
                $result[$k]['mobile']= '-';
            }
            unset($result[$k]['openid']);

            $consumer_ids[$v['consumer_id']] = $v;
            // var_dump( $v );die;
        }
// var_dump( $result );die;
        if( count( $consumer_ids ) > 0 ){
            //获取sku
            $this->load->model('soma/Consumer_order_model','ConsumerOrderModel');
            $ConsumerOrderModel = $this->ConsumerOrderModel;
            $consumer_item_table = $ConsumerOrderModel->item_table_name($business, $inter_id);
            $consumer_items = $this->_shard_db_r('iwide_soma_r')
                                ->where_in( 'consumer_id', array_keys( $consumer_ids ) )
                                ->select('consumer_id,sku')
                                ->get($consumer_item_table)
                                ->result_array();

            if( count( $consumer_items ) > 0 ){
                $consumer_items_new = array();
                foreach ($consumer_items as $k => $v) {
                    $consumer_items_new[$v['consumer_id']] = $v['sku'];
                }

                foreach ($result as $k => $v) {
                    if( array_key_exists( $v['consumer_id'], $consumer_items_new ) ){
                        $result[$k]['consumer_id'] = $consumer_items_new[$v['consumer_id']];
                        // unset($result[$k]['consumer_id']);
                    }
                }
            }

        }
        // var_dump( $result );die;
        //============快递商信息替换
        if( count($distributor)>0 ){
            $contact= array();
            $contacts= $this->_shard_db_r('iwide_soma_r')
                            ->where_in('dist_name', $distributor)
                            ->get('soma_cms_distributor')
                            ->result_array();

            foreach ($contacts as $k=>$v) $contact[$v['dist_name']]= $v['dist_label'];
            foreach ($result as $k=>$v){
                if( isset($contact[$v['distributor']]) ){
                    $result[$k]['distributor']= $contact[$v['distributor']];
                } else {
                    $result[$k]['distributor']= '-';
                }
            }
        }
        //============快递商信息替换
        return $result;
    }

    /*
     * 批量邮寄查询
     * @author luguihong
    */
    public function get_apply_list_byIds( $ids, $inter_id, $select_arr='*' )
    {
        $filter = array();
        $filter['inter_id'] = $inter_id;
        $filter['status'] = self::STATUS_APPLY;

        $pk = $this->table_primary_key();
        $table_name = $this->table_name( $inter_id );
        return $this->_shard_db_r('iwide_soma_r')
                    ->where_in( $pk, $ids )
                    ->where( $filter )
                    ->select( $select_arr )
                    ->get( $table_name )
                    ->result_array();
    }

    public function get_shipping_by_consumer_id($consumer_ids, $inter_id) {

        if(count($consumer_ids) <= 0) { return array(); }

        $table_name = $this->table_name( $inter_id );
        $data = $this->_shard_db_r('iwide_soma_r')
                        ->where_in( 'consumer_id', $consumer_ids )
                        ->get( $table_name )
                        ->result_array();

        $fmt_data = array();
        foreach ($data as $row) {
            $fmt_data[ $row['consumer_id'] ] = $row;
        }

        return $fmt_data;
    }

    public function change_remark( $inter_id, $id, $remark )
    {
        $table_name = $this->table_name( $inter_id );
        $result = $this->_shard_db( $inter_id )
                        ->where_in( 'shipping_id', $id )
                        ->update( $table_name, array('remark'=> $remark) );
        //var_dump($result);die;
        return $result;
    }

    /*
     * 查询信息
     * @author luguihong
     * @deprecated
    */
    public function get_shipping_info( $filter, $inter_id, $select='*' )
    {
        $table_name = $this->table_name( $inter_id );
        $db = $this->_shard_db_r('iwide_soma_r');
        $dbfields= array_values($fields= $this->_shard_db_r('iwide_soma_r')->list_fields($table_name));
        foreach ($filter as $k=>$v){
            if(in_array($k, $dbfields) && is_array($v)){
                if( !empty( $v ) )
                {
                    $db->where_in($k, $v);
                }
            } else if(in_array($k, $dbfields)) {
                $db->where($k, $v);
            }
        }

        $result = $db->select( $select )
                        ->where( 'inter_id', $inter_id )
                        ->get( $table_name )
                        ->result_array();
        return $result;
    }

    // public function change_shipping_status($order, $status = null) {

    //     $inter_id = $order->m_get('inter_id');
    //     $order_id = $order->m_get('order_id');
    //     $table_name = $this->table_name($inter_id);
    //     $db = $order->_shard_db($inter_id);
    //     if($status == null) { $status = self::STATUS_APPLY; }

    //     try {
    //         $db->set('status', $status);
    //         $db->where('shipping_order', $order_id);
    //         return $db->update($table_name);
    //     } catch(Exception $e) {
    //         return false;
    //     }
    //     return false;

    // }

    /**
     * 新后台重写此方法,提供新后台的数据，注意保持ori_data中的原数据格式，避免其他地方调用异常
     *
     * @param      array   $params  The parameters
     * @param      array   $select  The select
     * @param      string  $format  The format
     */
    public function filter( $params=array(), $select= array(), $format='array' ) {
        $ori_data = parent::filter($params, $select, $format);
        return $this->get_new_backend_order_data($ori_data, $params);
    }

    public function get_new_backend_order_data($ori_data, $params = array()) {

        if(empty($ori_data['data'])) {
            return $ori_data;
        }

        $cids = $filter = $orders = $items = $ids = $hash_orders= array();
        foreach($ori_data['data'] as $row) {
            $ids[] = $row[1];
            $cids[] = $row['DT_RowId'];
        }

        $inter_id = isset($params['inter_id']) ? $params['inter_id'] : null;
        $corders = $this->_shard_db_r($params['inter_id'])
            ->where_in('shipping_id', $cids)->get($this->table_name($inter_id))->result_array();
        $hash_corders = array();
        foreach($corders as $row)
        {
            $hash_corders[$row['shipping_id']] = $row;
        }

        $this->load->model('soma/Sales_order_model', 'o_model');

        $filter['where'] = array('order_id' => $ids);
        $orders = $this->o_model->get_order_collection($filter);

        foreach($orders as $row) {
            $hash_orders[$row['order_id']] = $row;
        }

        $new_res = $ori_data;
        foreach($ori_data['data'] as $key => $row) {
            $new_res['data'][$key]['ori_info'] = $hash_corders[$row['DT_RowId']];
            $new_res['data'][$key]['order_info'] = array();
            if(isset($hash_orders[ $row[1] ])) {
                $new_res['data'][$key]['order_info'] = $hash_orders[ $row[1] ];
            }
        }
        // var_dump($new_res);exit;
        return $new_res;
    }


    /**
     * 获取某一时间段内的订单数量
     *
     * @param      string  $inter_id   公众号
     * @param      string  $s_time     开始时间
     * @param      array   $hotel_ids  酒店id集合
     * @param      int     $status     订单状态
     *
     * @return     int     订单数量
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getOrderQty(
        $inter_id,
        $start_time,
        $hotel_ids = array(),
        $status = self::STATUS_APPLY)
    {
        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $CI = &get_instance();
        $CI->db_shard_config = $this->model_shard_config->build_shard_config($inter_id);
        $CI->current_inter_id = $inter_id;

        $this->soma_db_conn_read->from($this->table_name($inter_id));
        $this->soma_db_conn_read->where('inter_id', $inter_id);
        $this->soma_db_conn_read->where('create_time >=', $start_time);
        if (!empty($hotel_ids)) {
            $this->soma_db_conn_read->where_in('hotel_id', $hotel_ids);
        }
        if (is_array($status)) {
            $this->soma_db_conn_read->where_in('status', $status);
        } else {
            $this->soma_db_conn_read->where('status', $status);
        }

        return $this->soma_db_conn_read->count_all_results();
    }

    /**
     * 获取订单列表
     * @param array $filter
     * @param null $inter_id
     * @param array $select
     * @param array $page
     * @param array $like
     * @param int $type $type=1 订单页面列表 $type=2 物流工具列表页面
     * @return array
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function get_list($filter= array(), $inter_id=NULL, $select=array(), $page = array(), $like = array(), $type)
    {
        $select = count($select) == 0 ? '*' : implode(',', $select);
        $limit = isset($page['page_size']) ? $page['page_size'] : 5;
        $offset = isset($page['page_num']) ? (($page['page_num'] - 1) >= 0 ? ($page['page_num'] - 1) * $limit : 0) : 0;

        $table_name = $this->table_name($inter_id);

        // 新后台必须对数据进行分页处理
        $result = $this->soma_db_conn_read->select($select);
        if (!empty($like)) $result = $this->set_like($result, $like);
        foreach ($filter as $k => $v) {
            if (is_array($v)){
                $result = $result->where_in($k, $v);
            } else {
                $result = $result->where($k, $v);
            }
        }

        if ($type == 2 && !isset($filter['status'])) {
            $result = $result->where("(status = 1 OR distributor = 'a_sf')");
        }
        //如果hotel_id不为空，添加hotel_id条件
        $ent_ids= $this->session->get_admin_hotels();
        if ($ent_ids) {
            $hotel_ids= explode(',', $ent_ids);
            if( count($hotel_ids)>0 ) {
                $result = $result->where_in('hotel_id', $hotel_ids);
            }
        }

        $result = $result->limit($limit, $offset)
            ->order_by('shipping_id', 'desc')
            ->get($table_name)
            ->result_array();

        //快递商换中文名
        $express_list = $this->get_express();
        $express_map = array_column($express_list, 'dist_label', 'dist_name');

        //组装订单价格
        $order_ids = array_column($result, 'order_id');
        $this->load->model('soma/Sales_order_model', 'order_model');
        $order_select = 'order_id,saler_id,row_total,row_qty,real_grand_total,contact,mobile';
        $order_filter = array('inter_id' => $inter_id);
        if (!empty($order_ids)) $order_filter['order_id'] = $order_ids;
        $order_list = $this->order_model->get(array_keys($order_filter),
            array_values($order_filter),
            $order_select,
            ['limit'=>count($order_ids)]
        );
        $order_map = array();
        foreach ($order_list as $v) {
            $order_map[$v['order_id']] = array(
                'per_price' => empty($v['row_qty'])?'0.0':number_format($v['real_grand_total']/$v['row_qty'], 3),
                'real_pay' => $v['real_grand_total'],
                'saler_id' => $v['saler_id'],
                'contact' => $v['contact'],
                'buyer_phone' => $v['mobile']
            );
        }

        //分销员
        $openid = array_column($result, 'openid');
        $this->load->library('Soma/Api_idistribute');
        $saler_map = array();
        if (!empty($openid)) {
            foreach ($openid as $v) {
                $saler_data = $this->api_idistribute->get_saler_info($inter_id, $v);
                if ($saler_data) {
                    if ($saler_data['typ'] == 'STAFF' && !empty($saler_data['info']['saler'])) {
                        $saler_map[$saler_data['info']['saler']] = $saler_data['info']['name'];
                    }
                }
            }
        }


        foreach ($result as &$val) {
            $val['remark'] = $val['note'];
            $val['distributor'] = isset($express_map[$val['distributor']])? $express_map[$val['distributor']]:null;
            if (isset($order_map[$val['order_id']])) {
                $val['per_price'] = isset($order_map[$val['order_id']]['per_price'])?
                    number_format(($order_map[$val['order_id']]['per_price'])*$val['qty'], 2):null;
                $val['real_pay'] = isset($order_map[$val['order_id']]['real_pay'])?$order_map[$val['order_id']]['real_pay']:null;
                $val['saler_id'] = isset($order_map[$val['order_id']]['saler_id'])?$order_map[$val['order_id']]['saler_id']:null;
                $val['saler_name'] = isset($saler_map[$order_map[$val['order_id']]['saler_id']])?
                    $saler_map[$order_map[$val['order_id']]['saler_id']]:null;
                $val['buyer'] = isset($order_map[$val['order_id']]['contact'])?
                    $order_map[$val['order_id']]['contact']:null;
                $val['buyer_phone'] = isset($order_map[$val['order_id']]['buyer_phone'])?
                    $order_map[$val['order_id']]['buyer_phone']:null;

            }
        }
        unset($val);

        //总条数
        $total = $this->soma_db_conn_read->select($select);
        foreach ($filter as $k => $v) {
            if (is_array($v)){
                $total = $total->where_in($k, $v);
            } else {
                $total = $total->where($k, $v);
            }
        }

        if ($type == 2 && !isset($filter['status'])) {
            $total = $total->where("(status = 1 OR distributor = 'a_sf')");
        }
        if (!empty($like)) $total = $this->set_like($total, $like);
        $total = $total->get($table_name)->num_rows();

        $res['page_size'] = $limit;
        $res['page_num'] = isset($page['page_num']) ? $page['page_num'] : 0;
        $res['total'] = $total;
        $res['data'] = $result;
        return $res;
    }

    /**
     * 导出订单
     * @param string $select
     * @param array $filter
     * @param array $like
     * @return mixed
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function export_order($filter= array(), $inter_id = NULL, $select=array(), $like = array(), $type)
    {
        $select = count($select) == 0 ? '*' : implode(',', $select);

        $filter['inter_id'] = $inter_id;

        $result = $this->soma_db_conn_read->select($select);
        if (!empty($like)) $result = $this->set_like($result, $like);
        foreach ($filter as $k => $v) {
            if (is_array($v)){
                $result = $result->where_in($k, $v);
            } else {
                $result = $result->where($k, $v);
            }
        }

        if ($type == 2 && !isset($filter['status'])) {
            $result = $result->where("(status = 1 OR distributor = 'a_sf')");
        }

        //不设定条件最多导出3个月的数据
        if(empty($filter) && empty($like)){
            $result->where('create_time >=', date('Y-m-d H:i:s', strtotime('-3 month') ) );
            $result->where('create_time <=', date('Y-m-d H:i:s', strtotime('+1 day') ) );
        }

        //如果hotel_id不为空，添加hotel_id条件
        $ent_ids= $this->session->get_admin_hotels();
        if ($ent_ids) {
            $hotel_ids= explode(',', $ent_ids);
            if( count($hotel_ids)>0 ) {
                $result = $result->where_in('hotel_id', $hotel_ids);
            }
        }

        $result = $result->order_by('shipping_id desc')
            ->get( $this->table_name( $inter_id ) )
            ->result_array();

        //快递商换中文名
        $express_list = $this->get_express();
        $express_map = array_column($express_list, 'dist_label', 'dist_name');

        //分销员
        $openid = array_column($result, 'openid');
        $this->load->library('Soma/Api_idistribute');
        $saler_map = array();
        if (!empty($openid)) {
            foreach ($openid as $v) {
                $saler_data = $this->api_idistribute->get_saler_info($inter_id, $v);
                if ($saler_data) {
                    if ($saler_data['typ'] == 'STAFF' && !empty($saler_data['info']['saler'])) {
                        $saler_map[$saler_data['info']['saler']] = $saler_data['info']['name'];
                    }
                }
            }
        }

        //组装订单价格
        $order_ids = array_column($result, 'order_id');
        $this->load->model('soma/Sales_order_model', 'order_model');
        $order_select = 'order_id,saler_id,row_total,row_qty,real_grand_total,contact,mobile';
        $order_filter = array('inter_id' => $inter_id);
        if (!empty($order_ids)) $order_filter['order_id'] = $order_ids;
        $order_list = $this->order_model->get(array_keys($order_filter),
            array_values($order_filter),
            $order_select,
            ['limit'=>count($order_ids)]
        );

        $order_map = array();
        foreach ($order_list as $v) {
            $order_map[$v['order_id']] = array(
                'per_price' => number_format($v['real_grand_total']/$v['row_qty'], 3),
                'real_pay' => $v['real_grand_total'],
                'saler_id' => $v['saler_id'],
                'contact' => $v['contact'],
                'buyer_phone' => $v['mobile']
            );
        }

        //状态中文映射
        $status_map= $this->get_status_label();

        foreach ($result as &$val) {
            $val['remark'] = $val['note'];
            $val['distributor'] = isset($express_map[$val['distributor']])? $express_map[$val['distributor']]:null;
            $val['status'] = isset($status_map[$val['status']])?$status_map[$val['status']]:'--';
            if (isset($order_map[$val['order_id']])) {
                $val['per_price'] = isset($order_map[$val['order_id']]['per_price'])?
                    number_format(($order_map[$val['order_id']]['per_price'])*$val['qty'], 2):null;
                $val['real_pay'] = isset($order_map[$val['order_id']]['real_pay'])?$order_map[$val['order_id']]['real_pay']:null;
                $val['saler_id'] = isset($order_map[$val['order_id']]['saler_id'])?$order_map[$val['order_id']]['saler_id']:null;
                $val['saler_name'] = isset($saler_map[$order_map[$val['order_id']]['saler_id']])?$saler_map[$order_map[$val['order_id']]['saler_id']]:null;
                $val['buyer'] = isset($order_map[$val['order_id']]['contact'])?
                    $order_map[$val['order_id']]['contact']:null;
                $val['buyer_phone'] = isset($order_map[$val['order_id']]['buyer_phone'])?
                    $order_map[$val['order_id']]['buyer_phone']:null;
            }
        }
        unset($val);

        return $result;
    }
    

    private function set_like($model = null, $like = array())
    {
        //like: [['and', 'name', '标题'], ['or', 'name', '标题']];
        $model->group_start();
        if (!empty($like)) {
            foreach ($like as $val) {
                $mode = 'like';
                if ($val[0] == 'or') {
                    $mode = 'or_like';
                }
                $model = $model->$mode($val[1], $val[2]);
            }
        }
        $model->group_end();
        return $model;
    }

    /**
     * 快递下拉列表
     * @return mixed
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function get_express()
    {
        $redis = $this->get_redis_instance();
        $cached = $redis->get(self::DIST_NAME_LABEL);

        if (empty($cached)) {
            $result = $this->_shard_db_r('iwide_soma_r')
                ->select('dist_name,dist_label')
                ->get('soma_cms_distributor')
                ->result_array();
            $redis->set(self::DIST_NAME_LABEL, json_encode($result),  86400);
            return $result;
        }

        return json_decode($cached, true);
    }
}
