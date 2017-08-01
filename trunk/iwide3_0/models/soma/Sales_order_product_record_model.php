<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order_product_record_model extends MY_Model_Soma {

    /**
     * 细单对象(数组)
     * @var Array 
     */
    public $order= array();

    public $status = NULL;

	public function get_resource_name()
	{
		return 'Sales_order_product_record';
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
        return $this->_shard_table('soma_sales_order_product_record', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'record_id';
	}

    public function product_item_field_mapping()
    {
        return array(
            'inter_id'=> 'inter_id',
            'hotel_id'=> 'hotel_id',
            'hotel_name'=> 'hotel_name',
            'product_id'=> 'product_id',
            'type'=> 'type',
            'goods_type' => 'goods_type',
            'name'=> 'name',
            'name_en'=> 'name_en',
            'card_id'=> 'card_id',
            'sku'=> 'sku',
            'price_package'=> 'price_package',
            'keyword'=> 'keyword',
            'compose'=> 'compose',
            'compose_en'=> 'compose_en',
            'order_notice'=> 'order_notice',
            'order_notice_en'=> 'order_notice_en',
            'img_detail'=> 'img_detail',
            'img_detail_en'=> 'img_detail_en',
            'face_img'=> 'face_img',
            'can_split_use'=> 'can_split_use',
            'use_cnt'=> 'use_cnt',
            'can_refund'=> 'can_refund',
            'can_mail'=> 'can_mail',
            'can_gift'=> 'can_gift',
            'can_pickup'=> 'can_pickup',
            'can_invoice'=> 'can_invoice',
            'expiration_date'=> 'expiration_date',
        );
    }
	
	public function attribute_labels()
	{
		return array(
            'record_id'=> '快照ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'hotel_name'=> '酒店名称',
            'order_id'=> '订单编号',
            'openid'=> 'openid',
            'qty'=> '购买数量',
            'order_time'=> '下单时间',
            'product_id'=> '商品ID',
            'type'=> '产品类型',
            'name'=> 'Name',
            'card_id'=> '礼包ID',
            'sku'=> 'sku',
            'price_package'=> '微信价',
            'keyword'=> '关键词描述',
            'compose'=> '套票构成，序列号内容',
            'order_notice'=> '订购须知',
            'img_detail'=> '图文详情',
            'face_img'=> '封面图',
            'can_split_use'=> '能否分时使用：1能，2不能',
            'use_cnt'=> '分时使用次数',
            'can_refund'=> '能否退',
            'can_mail'=> '能否邮寄',
            'can_gift'=> '能否赠送',
            'can_pickup'=> '能否到店',
            'can_invoice'=> '能否开发票',
            'expiration_date'=> '过期时间',
            'create_time'=> '创建时间',
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
            'hotel_id',
            'order_id',
            'openid',
            'qty',
            'order_time',
            'product_id',
            'type',
            'name',
            'card_id',
            'sku',
            'price_package',
            'keyword',
            'compose',
            'order_notice',
            'img_detail',
            'face_img',
            'can_split_use',
            'use_cnt',
            'can_refund',
            'can_mail',
            'can_gift',
            'can_pickup',
            'can_invoice',
            'expiration_date',
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
            'qty' => array(
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
            'order_time' => array(
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
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'type' => array(
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
            'name' => array(
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
            'card_id' => array(
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
            'sku' => array(
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
            'price_package' => array(
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
            'keyword' => array(
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
            'compose' => array(
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
            'order_notice' => array(
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
            'img_detail' => array(
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
            'face_img' => array(
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
            'can_split_use' => array(
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
            'use_cnt' => array(
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
            'can_refund' => array(
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
            'can_mail' => array(
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
            'can_gift' => array(
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
            'can_pickup' => array(
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
            'can_invoice' => array(
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
            'expiration_date' => array(
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
                'type'=>'text', //textarea|text|date|datetime|combobox|number|logo|email|url|price
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

    /**
     *
     * 交易快照
     * Usage: $order_model->product = $product_info;
     * Usage: $model->order = $order_model->load($order_id);
     * Usage: $model->product_record_save($inter_id);
     */
    public function product_record_save_new( $inter_id, $product, $order_id, $openid, $create_time, $row_qty, $status, $business='package' )
    {
        $product_info = $product[0];
        $mapping = $this->product_item_field_mapping();
        $data = array();

        $this->load->model('soma/Product_package_model','productPackageModel');
        $productModel = $this->productPackageModel;

        foreach( $mapping as $k=>$v ){
            $data[$v] = $product_info[$v];

            //设定的模式是存活时间的且不是时间规格的，要重新设置有效期
            if( $v == 'expiration_date' && $product_info['date_type'] == $productModel::DATE_TYPE_FLOAT && $product_info['setting_date'] == Soma_base::STATUS_FALSE ){
                $date = isset( $product_info['use_date'] ) ? $product_info['use_date'] : 0;
                $date_time = date('Y-m-d H:i:s',time()+$date*24*60*60);
                $data[$v] = $date_time;
            }
        }

        $this->load->model('soma/ticket_center_model');
        $record_id = $this->ticket_center_model->get_increment_id_order_product_record($business);
        $result = false;
        if( $record_id ){
            $data['record_id'] = $record_id;
            $data['order_id'] = $order_id;
            $data['openid'] = $openid;
            $data['order_time'] = $create_time;
            $data['qty'] = $row_qty;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['status'] = $status;

            $table_name = $this->table_name( $inter_id );
            $result = $this->_shard_db( $inter_id )->insert( $table_name, $data );
        }
        return $result;
    }

    /**
     * 
     * 交易快照
     * Usage: $order_model->product = $product_info;
     * Usage: $model->order = $order_model->load($order_id);
     * Usage: $model->product_record_save($inter_id);
     */
	public function product_record_save( $inter_id, $business='package' )
    {
        $order = $this->order;
        $product = $order->product;
        $product_info = $product[0];
        $mapping = $this->product_item_field_mapping();
        $data = array();

        $this->load->model('soma/Product_package_model','productPackageModel');
        $productModel = $this->productPackageModel;
        
        foreach( $mapping as $k=>$v ){
            $data[$v] = $product_info[$v];

            //设定的模式是存活时间的且不是时间规格的，要重新设置有效期
            if( $v == 'expiration_date' && $product_info['date_type'] == $productModel::DATE_TYPE_FLOAT && $product_info['setting_date'] == Soma_base::STATUS_FALSE ){
                $date = isset( $product_info['use_date'] ) ? $product_info['use_date'] : 0;
                $date_time = date('Y-m-d H:i:s',time()+$date*24*60*60);
                $data[$v] = $date_time;
            }
        }

        $this->load->model('soma/ticket_center_model');
        $record_id = $this->ticket_center_model->get_increment_id_order_product_record($business);
        if( $record_id ){

            $data['record_id'] = $record_id;
            $data['order_id'] = $order->m_get('order_id');
            $data['openid'] = $order->m_get('openid');
            $data['order_time'] = $order->m_get('create_time');
            $data['qty'] = $order->m_get('row_qty');
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['status'] = $this->status;

            $table_name = $this->table_name( $inter_id );
            $result = $this->_shard_db( $inter_id )->insert( $table_name, $data );
        }
    }

    /**
     * 更新交易快照状态，支付成功后修改
     * @param $openid
     * @param $order_id
     * @param $inter_id
     * @return bool
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function update_record_status( $openid, $order_id, $inter_id )
    {
        $filter = array();
        $filter['openid'] = array( $openid );
        $filter['order_id'] = $order_id;
        $result = $this->get_record_info( $filter, $inter_id );
        if( $result ){
            //支付成功后，修改快照状态
            $pk = $this->table_primary_key();
            if( $result['status'] == Soma_base::STATUS_FALSE ){
                $data = array(
                    'status' => Soma_base::STATUS_TRUE
                );
                $result = $this->load( $result[$pk] )->m_sets( $data )->m_save();
            }
        }
        return $result;
    }

    /**
     * 获取交易快照,取出第一条有效
     */
    public function get_record_info( $filter=array(), $inter_id )
    {
        $db = $this->_shard_db($inter_id);
        if( count($filter)>0 ){
            foreach ($filter as $k=> $v){
                if(is_array($v)){
                    $db->where_in($k, $v);
                } else {
                    $db->where($k, $v);
                }
            }
        }

        $table_name = $this->table_name( $inter_id );
        $result = $db
                        ->where( 'inter_id', $inter_id )
                        ->limit(1)
                        ->get( $table_name )
                        ->row_array();
        return $result;
    }

    /**
     * 获取交易快照列表
     */
    public function get_record_list( $inter_id )
    {
        $table_name = $this->table_name( $inter_id );
        $result = $this->_shard_db_r('iwide_soma_r')
                        ->where( 'inter_id', $inter_id )
                        ->where( 'status', Soma_base::STATUS_TRUE )
                        ->get( $table_name )
                        ->result_array();
        return $result;
    }


}
