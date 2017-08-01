<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_customer_model extends MY_Model_Soma {

    public $business;
    /**
     * 订单对象 
     * @var Sales_order_model 
     */
    public $order= array();
    /**
     * 消费对象 
     * @var Consumer_order_model 
     */
    public $consumer= array();
    /**
     * 消费码对象 
     * @var Consumer_order_model 
     */
    public $consumer_code= array();
    /**
     * 资产明细对象(数组)
     * @var Array 
     */
    public $item= array();
    
    const STATUS_ACTIVE = 1;
    const STATUS_UNACTIVE = 2;

    const STATUS_ITEM_UNSIGN = 1;
    const STATUS_ITEM_SIGNED = 2;

    const ITEMS_CAN_RESERVE_YES = 1;
    const ITEMS_CAN_RESERVE_NO = 2;
    
    public function get_status_label(){
        return array(
            self::STATUS_ACTIVE  => '正常',
            self::STATUS_UNACTIVE => '冻结',
        );
    }
    public function get_status_item_label()
    {
        return array(
            self::STATUS_ITEM_UNSIGN => '正常',  //已消费
            self::STATUS_ITEM_SIGNED => '禁用',  //未消费
        );
    }

    public function get_items_can_reserver_label(){
    	return array(
    			self::ITEMS_CAN_RESERVE_YES => '需要预约',
    			self::ITEMS_CAN_RESERVE_NO  => '不需要预约',
    		);
    }
    
	public function get_resource_name()
	{
		return '用户资产';
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
		return $this->_shard_table('soma_asset_customer', $inter_id);
	}
	public function item_table_name($business, $inter_id=NULL)
	{
	    $business= strtolower($business);
		return $this->_shard_table('soma_asset_item_'. $business, $inter_id);
	}

	public function table_primary_key()
	{
	    return 'asset_id';
	}
	public function item_table_primary_key()
	{
	    return 'item_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'asset_id'=> 'ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'openid'=> 'Openid',
            'row_total'=> '总价值',
            'amount'=> '件数',
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
            'asset_id',
            'inter_id',
            'hotel_id',
            'openid',
            'row_total',
            'amount',
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
            'asset_id' => array(
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
            'row_total' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'amount' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
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
	    return array('field'=>'asset_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	public function get_assetid_ticket($business)
	{
	    $this->load->model('soma/ticket_center_model');
	    return $this->ticket_center_model->get_increment_id_asset($business);
	}

	/**
	 * 显示细单明细
	 * Usage: $model->load($id)->get_order_items();
	 */
	public function get_asset_items($business, $inter_id)
	{
	    $primary_key= $this->table_primary_key();
	    if( !$this->m_get($primary_key) ){
	        Soma_base::inst()->show_exception('Please Load Model first.');
	    }
	    //根据业务类型初始化对象
	    $business= strtolower($business);
	    $item_object_name= "Asset_item_{$business}_model";
	    require_once dirname(__FILE__). DS. "$item_object_name.php";
	    $object= new $item_object_name();
	    
	    //细单订单保存支付
	    $result= $object->get_order_items($this, $inter_id);
	    return $result;
	}
	
	public function get_gift_recevied_item($filter, $business, $inter_id)
	{
	    $item_table= $this->item_table_name($business, $inter_id);
        $db = $this->_shard_db_r('iwide_soma_r');
	    foreach ($filter as $k=>$v){
	        if( is_array($v) ){
                $db->where_in($k, $v);
	        } else {
                $db->where($k, $v);
	        }
	    }
	    $result= $db->get( $item_table )->result_array();
	    return $result;
	}
	
	/**
	 * 交易后分配到资产库 （根据订单确定是哪个资产库，每个openid一条记录）
	 * 
        $this->load->model('soma/asset_customer_model');
        $result= $this->asset_customer_model->sign_asset_item($order, $inter_id);
	 */
	public function sign_asset_item($order, $inter_id)
	{
	    try {
	        $this->_shard_db($inter_id)->trans_begin();

	        $business = strtolower( $order->business );
	        
	        //根据业务类型初始化对象
	        $item_object_name= "soma/Asset_item_{$business}_model";
            $this->load->model($item_object_name, 'assetItemModel');
            /**
             * @var Asset_item_package_model $assetItemModel
             */
            $assetItemModel = $this->assetItemModel;

            //不存在则创建个人资产账户信息
	        $pk = $this->table_primary_key();
	        $account= $this->_shard_db_r('iwide_soma_r')->where( array('openid'=> $order->m_get('openid') ) )->limit(1)->get($this->table_name())->row_array();
	        if( ! $account ){
	        
	            //统一资产编号
	            $asset_id = $this->get_assetid_ticket($business);
	            if( !$asset_id ){
	                Soma_base::inst()->show_exception('账户创建数量太多，系统正玩命加载中，请稍后再试。');
	            }
	            $this->_shard_db($inter_id)->insert($this->table_name($inter_id), array(
	                'asset_id' => $asset_id,
	                'inter_id' => $inter_id,
	                'hotel_id' => $order->m_get('hotel_id'),
	                'openid' => $order->m_get('openid'),
	                'row_total' => '0',
	                'amount' => '0',
	                'status' => self::STATUS_ACTIVE,
	            ));
	             
	        } else {
	            $asset_id= $account[$pk];
	        }

            /**
             * @var Asset_item_package_model $asset
             */
	        $asset = $this->load($asset_id);
	        $asset->order= $order;
	         
	        //插入资产明细
	        $save_item_result = $assetItemModel->save_item($asset, $inter_id);
			if (!$save_item_result) {
                return false;
            }
	         
	        //计算资产统计字段，返回对象并修改保存
	        $asset= $assetItemModel->calculate_total($asset, $inter_id);
	        $asset->m_save();

	        
	        if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
	            $this->_shard_db($inter_id)->trans_rollback();
	            return FALSE;
	        
	        } else {
	            $this->_shard_db($inter_id)->trans_commit();
	            return TRUE;
	        }
	        
        } catch (Exception $e) {
            return FALSE;
        }
	}

	/**
     * 订单退款改变资产库数量、状态
     * Usage: $model->load($id)->order_refund_status($business, $inter_id);
     * Usage: $salesRefundModel; 为了保存事务一致性
     * @author luguihong
     */
    public function order_refund_status( $business, $inter_id, $salesRefundModel )
    {
        //根据业务类型初始化对象
        $item_object_name= "Asset_item_{$business}_model";
        require_once dirname(__FILE__). DS. "$item_object_name.php";
        $object= new $item_object_name();
        
        $this->business = $business;
        return $object->order_refund_status( $this, $inter_id, $salesRefundModel );
    }

    /**
     * 预约
	 * 根据资产细单id查找细单信息
	 * $model->get_asset_items_by_itemId();
	 * @author luguihong@mofly.cn
	 */
    public function get_asset_items_by_itemId( $item_id, $business, $inter_id )
    {
    	//根据业务类型初始化对象
        $item_object_name= "Asset_item_{$business}_model";
        require_once dirname(__FILE__). DS. "$item_object_name.php";
        $object= new $item_object_name();
        
        $this->business = $business;
        return $object->get_order_items_byItemids( $item_id, $business, $inter_id );
    }
    
    /**
     * 核销
	 * 根据资产细单id查找细单信息
	 * $item[0]['consumer_code'] = $code;
	 * $model->item = $item;//资产细单明细 array(array('item_id'=>1,'can_reserve'=>1,));
	 * $consumer->asset_item = $item;
	 * $model->consumer = $consumer;//消费对象
	 * $model->business = $business;
	 * $model->consumer_asset_items($business, $inter_id);
	 * @author luguihong@mofly.cn
	 * @deprecated
	 */
    public function consumer_asset_items( $business, $inter_id )
    {
    	try {
	        $business= strtolower( $business );
	        
	        $items = $this->item;
	        
	    	//不需要预约的直接生成消费单，并核销处理
	    	//已经预约的直接核销
	    	$consumer = $this->consumer;
	        $items = $items[0];
	    	$can_reserve_yes = self::ITEMS_CAN_RESERVE_YES;//需要预约
			$can_reserve_no = self::ITEMS_CAN_RESERVE_NO;//不需要预约
			$consumer->can_reserve = $can_reserve_yes;
			if( $items['can_reserve'] == $can_reserve_yes ){
				//需要预约的已经生成过消费单,改变消费单状态
				$result = $consumer->consumer_order_consume( $business, $inter_id );
				
			} else {
				//不需要预约的核销的时候再生成消费单
				$result = $consumer->asset_to_consumer( $business, $inter_id );
				// if( $result ){

			 //        //根据业务类型初始化对象
			 //        $item_object_name= "Asset_item_{$business}_model";
			 //        require_once dirname(__FILE__). DS. "$item_object_name.php";
			 //        $object= new $item_object_name();
				//		已经在生成消费单的时候减了数量
			 //        //处理细单数量减一
			 //        $result = $object->consumer_asset_items( $consumer, $this->item, $inter_id );
			 //    }
			}

			return $result;
			
		} catch (Exception $e) {
            return FALSE;
        }
	        
    }

	
}
