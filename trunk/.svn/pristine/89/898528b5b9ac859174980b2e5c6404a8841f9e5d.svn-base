<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Shp_orders extends MY_Model_Mall {

    public $items= array();
    public $gifts= array();
    
	public function get_resource_name()
	{
		return 'shp_orders';
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
		return 'shp_orders';
	}

	public function table_primary_key()
	{
	    return 'order_id';
	}

	const CAN_MAIL_T	= '0';
	const CAN_MAIL_F	= '1';
	const CAN_PICKUP_T	= '0';
	const CAN_PICKUP_F	= '1';
	
	const STATUS_DEFAULT= '0';
	const STATUS_GIFT	= '1'; //弃用
	const STATUS_GIFTED	= '2'; //弃用
	const STATUS_PROCESSING	= '1';
	const STATUS_COMPLETE	= '2';

	const PAYMENT_T	= '1';
	const PAYMENT_F	= '0';


	public function payment_status()
	{
	    return array(
	        self::PAYMENT_T => '微信支付',
	        self::PAYMENT_F => '未支付',
	    );
	}
	public function invoice_status()
	{
	    return array(
            EA_base::STATUS_TRUE=> '已开',
            EA_base::STATUS_FALSE=> '未开',
	    );
	}
    public function order_status()
    {
        return array(
            self::STATUS_DEFAULT    => '已确认',
            self::STATUS_PROCESSING => '处理中', //包含几重含义，有些商品已经发货，有些商品属于自提
            self::STATUS_COMPLETE   => '已处理', //全部核销/收货
        );
    }

	public function attribute_labels()
	{
		return array(
			'order_id'=> 'ID',
			'hotel_id'=> '酒店ID',
			'inter_id'=> '公众号',
			'topic_id'=> '相关专题',
			'out_trade_no'=> '订单SN',
			'openid'=> 'OPENID',
			'order_time'=> '下单时间',
			'pay_status'=> '支付状态',
			'pay_time'=> '支付确认时间',
			'transaction_id'=> '交易流水号',
			'total_fee'=> '总金额',
			'card_fee'=> '用券金额',
			'sub_fee'=> '立减金额',
			'shipping_fee'=> '运费',
			'saler'=> '分销人员',
			'fans_id'=> '粉丝ID',
			'status'=> '订单状态',
			'is_invoice'=> '已开发票？',

		    'qrcode_url'=> '核销二维码',

		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
            'order_id',
	        'inter_id',
            'out_trade_no',
			'order_time',
			'pay_status',
			'total_fee',
			'card_fee',
			'sub_fee',
			'shipping_fee',
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
	    $modules= config_item('admin_panels')? config_item('admin_panels'): array();

	    /** 获取本管理员的酒店权限  */
	    $this->_init_admin_hotels();
	    $publics = $hotels= $topics= array();
	    $filter= $filterH= NULL;
	    
	    if( $this->_admin_inter_id== FULL_ACCESS ) $filter= array();
	    else if( $this->_admin_inter_id ) $filter= array('inter_id'=> $this->_admin_inter_id);
	    if(is_array($filter)){
	        $this->load->model('wx/publics_model');
	        $publics= $this->publics_model->get_public_hash($filter);
	        $publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
	        //$publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
           
            $this->load->model('mall/shp_topic');
            $topics= $this->shp_topic->get_data_filter($filter);
            $topics= $this->shp_topic->array_to_hash_multi($topics, 'identity|page_title', 'topic_id');
	    }
	     
	    if( $this->_admin_hotels== FULL_ACCESS ) $filterH= array();
	    else if( $this->_admin_hotels ) $filterH= array('hotel_id'=> $this->_admin_hotels);
	    else $filterH= array();
	    
	    if( $publics && is_array($filterH)){
	        $this->load->model('hotel/hotel_model');
	        $hotels= $this->hotel_model->get_hotel_hash($filterH);
	        $hotels= $this->hotel_model->array_to_hash($hotels, 'name', 'hotel_id');
	        $hotels= $hotels+ array('0'=>'-不限定-');
	    }
	    /** 获取本管理员的酒店权限  */

	    return array(
            'order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'topic_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $topics,
            ),
            'out_trade_no' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'function'=> 'hide_string_prefix|6',
                'type'=>'text',	//textarea|text|combobox
            ),
            'order_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'datetime',	//textarea|text|combobox
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $publics,
            ),
            'pay_status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox',	//textarea|text|combobox
                'select'=> $this::payment_status(),
            ),
            'pay_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'datetime',	//textarea|text|combobox
            ),
            'transaction_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'total_fee' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',	//textarea|text|combobox
            ),
            'card_fee' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',	//textarea|text|combobox
            ),
            'sub_fee' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',	//textarea|text|combobox
            ),
            'shipping_fee' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',	//textarea|text|combobox
            ),
            'saler' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'fans_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $this->order_status(),
            ),
            'is_invoice' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $this->invoice_status(),
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'order_id', 'sort'=>'desc');
	}

	/* 以上为AdminLTE 后台UI输出配置函数 */
	
	public function get_items($filter=array())
	{
	    $pk= $this->table_primary_key();
	    if($this->m_get($pk)){
	        $this->load->model('mall/shp_order_items');
	        $filter += array(
	            'order_id'=> $this->m_get($pk),
	        );
	        $in_status= $this->shp_order_items->can_consume_status();
	        $items= $this->_db()->where($filter)->where_in('status', $in_status)
	            ->get('shp_order_items')->result_array();
	        return $items;
	
	    } else {
	        return array();
	    }
	}

	/**
	 * [函数必须放在核销完成之后]订单状态刷新，如核销商品后，配送之后进行刷新
	 * @param String $order_id
	 * @return boolean
	 */
	public function order_status_flush($order_id)
	{
	    $this->load->model('mall/shp_order_items');
	    $item_model= $this->shp_order_items;
	    $items= $item_model->find_all( array( 'order_id'=> $order_id, ) );
	    if(count($items)>0 ){
	        $default= $finish= TRUE;
	        foreach ($items as $k=>$v){
	            if( $v['status']!=$item_model::STATUS_DEFAULT )
	                $default= FALSE;
	             
	            if( in_array($v['status'], $item_model->can_consume_status() ) )
	                $finish= FALSE;
	        }
	        
			if( !$this->m_get('order_id') ) $this->load($order_id);

	        if($finish){
	            $this->m_set('status', self::STATUS_COMPLETE )->m_save();
	             
	        } else if( $default ) {
	            $this->m_set('status', self::STATUS_DEFAULT )->m_save();
	             
	        } else {
	            $this->m_set('status', self::STATUS_PROCESSING )->m_save();
	        }
	        return TRUE;
	    }
	    return FALSE;
	}
	
	/**
	 * 扫二维码核销订单单品
	 * @param String $qr_number 
	 * @param string $item_id  默认为空，整单所有单品核销
	 * @return boolean
	 */
	public function qr_consumer($qr_number, $openid, $inter_id=NULL, $item_id=NULL)
	{
	    /**
* 新版的核销码为混淆的20位英文数字，前16位是单号 out_trade_no 的混淆，后面4位是item_id的移位处理
* 核销处理逻辑：先将 qr_number填充为20位（不满则右边补0000），进行混淆逆运算，取得真实号码，由于格式化，末4位可能仅为细单id的右边部分。
* 取左边16位，由于取得 out_trade_no；用intval取末4位，如果为0则属于整单核销，否则用 正则匹配对应的细单id，进行核销。
	    */
	    $real_qr_number= $this->qr_order_no( $qr_number, NULL, $type='de' );
        //echo $real_qr_number;die;
	    $qr_order= substr($real_qr_number, 0, 16);
        //echo $real_qr_number;die;
	    if( strlen($qr_number)> 16 ) {
	        $qr_item= intval( substr($real_qr_number, -4) );
	    } else {
	        $qr_item= FALSE;
	    }
        //echo $qr_item;die;

	    $pk= $this->table_primary_key();

        //echo $qr_order;die;
        $order_filter= array( 'out_trade_no'=> $qr_order, );
        if($inter_id) $order_filter['inter_id']= $inter_id;
	    $order= $this->find($order_filter);
        //var_dump($order);die;
	    
	    if($order){
	        $model= $this->load($order[$pk]);
	        $items= $model->get_items(); //经筛选商品的核销状态
	        //print_r($items);die;
	        
	        $ids= $match= array();
	        foreach ($items as $k=> $v) {
	            $match[$k]= substr($v['id'], -(strlen($qr_item)) );
	            $ids[$k]= $v['id'];
	        }
	        //print_r($match); //将所有细单id截取到要匹配的长度
	        //print_r($ids); //所有细单的真实id

	        $real_ids= array(); //未拆单的所有细单id
	        if( $qr_item && in_array($qr_item, $match) ){
	            //单品核销
	            /**foreach ($match as $sk=> $sv){
	                if($sv== $qr_item){
	                    $this->_db()->where('id', $ids[$sk] );
	                    $item_id= $sk;
	                }
	            }**/
	            /** 连带没有拆单的其他细单一起核销  **/
	            foreach ($match as $sk=> $sv){
	                if($sv== $qr_item) $real_id= $ids[$sk];
	            }
	            foreach ($ids as $sk=> $sv){
	                if($sv== $real_id) {
	                    $item_id= $sk;
	                    $real_gs_id= $items[$item_id]['gs_id'];
	                }
	            }
                //die($real_gs_id);
	            foreach ($items as $sk=> $sv){
	                if( $items[$item_id]['ex_order']== Shp_order_items::EX_ORDER_T ){
	                    //对于拆单的，需判断并单独处理
	                    if( $sv['id']== $real_id ) $real_ids[]= $real_id;
	                    
	                } else {
	                    //对于没拆单，需连带处理
	                    if($sv['gs_id']==$real_gs_id ) $real_ids[]= $sv['id'];
	                }
	            }
	            //print_r($real_ids);die;
	            $this->_db()->where_in('id', $real_ids );
	            /** 连带没有拆单的其他细单一起核销  **/
	
	        } elseif(count($real_ids)==0) {
	            //全部商品核销完，直接返回false
	            return array('status'=>3, 'message'=>'该订单已被核销过，请勿重复核销');
	
	        } else {
	            //整单核销
	            $this->_db()->where_in('id', $real_ids );
	        }
            //var_dump($real_ids);die;
	        
	        $data= array(
	            'consumer'=> $openid,
	            'consume_time'=> date('Y-m-d H:i:s'),
	            'status'=> Shp_order_items::STATUS_COMSUME,
	        );
	        $this->_db()->where_in('status', array( Shp_order_items::STATUS_DEFAULT ) );

	        if( count($real_ids)>0 ){
    	        $result= $this->_db()->update('shp_order_items', $data );
	            if( $result )
	                //刷新订单的状态
	                $this->order_status_flush( $order[$pk] );
	                return array('status'=>1, 'message'=> '核销成功：商品"'. $items[$item_id]['gs_name']. '"' );
	        }
// 	        if($result && !$qr_item ) {
// 	            //取消总单核销
// 	            return array('status'=>1, 'message'=>'总单核销成功');
	             
// 	        } else if($result) {
// 	            return array('status'=>1, 'message'=> '核销成功：商品"'. $items[$item_id]['gs_name']. '"' );
// 	        }

	    }
	    return array('status'=>2, 'message'=>'核销码不正确，核销失败');;
	}

	/**
	 * @deprecated 此函数已经废弃
	 */
	public function __qr_consumer($qr_number, $openid, $inter_id, $item_id=NULL)
	{
	    $pk= $this->table_primary_key();
	
	    $order= $this->find(array(
	        'out_trade_no'=> $qr_number,
	        'inter_id'=> $inter_id,
	    ));
	
	    if($order){
	        $model= $this->load($order[$pk]);
	        $items= $model->get_items(); //经筛选商品的核销状态
	        //print_r($items);die;
	
	        $ids= array();
	        foreach ($items as $v) {
	            $ids[]= $v['id'];
	        }
	        if( $item_id && in_array($item_id, $ids) ){
	            $this->_db()->where('id', $item_id);
	
	        } elseif(count($ids)==0) {
	            //全部商品核销完，直接返回false
	            return array('status'=>3, 'message'=>'该订单已被核销过，请勿重复核销');
	
	        } else {
	            $this->_db()->where_in('id', $ids);
	        }
	        $data= array(
	            'consumer'=> $openid,
	            'consume_time'=> date('Y-m-d H:i:s'),
	            'status'=> Shp_order_items::STATUS_COMSUME,
	        );
	        $result= $this->_db()->where_in('status', array(
	            Shp_order_items::STATUS_DEFAULT
	        ) )->update('shp_order_items', $data );
	
	        if($result && $item_id==NULL) {
	            return array('status'=>1, 'message'=>'总单核销成功');
	
	        } else if($result) {
	            return array('status'=>1, 'message'=>$items[$item_id]['name']. '核销成功');
	        }
	    }
	    return array('status'=>2, 'message'=>'核销失败');;
	}
	
	/**
	 * 用于定时检测订单内细单的核销总体状况
	 * @param String $order_id
	 * @param String $inter_id
	 * @return string|boolean
	 */
	public function item_consume_check($order_id, $inter_id)
	{
	    if($order_id){
	        $order= $this->load($order_id);
	        if($order){
	            $items= $order->get_items();
	            $result= array();
	            foreach ($items as $k=>$v){
	                $result[$v['id']]= $v['status'];
	            }
	            return json_encode($result);
	        }
	    }
	    return FALSE;
	}

	/**
	 * 订单多数量细单拆分操作
	 * @param String $order_id
	 * @param String $item_id
	 * @param String $inter_id
	 * @return multitype:number string
	 */
	public function item_separate($order_id, $item_id, $inter_id=NULL)
	{
	    $item_id= strchr($item_id, ',', TRUE);
	    if($order_id && $item_id){
	        $model= $this->load($order_id);
	        $items= $model->get_items(); //经筛选商品的核销状态
	         
	        if($items){
	            $ids= array();
	            foreach ($items as $sk=> $sv){
	                if($sv['id']== $item_id) $item_gs_id= $items[$sk]['gs_id'];
	            }
	            foreach ($items as $sk=> $sv){
	                if($sv['gs_id']== $item_gs_id) {
	                    $ids[]= $items[$sk]['id'];
	                    $item_mail= ($items[$sk]['can_mail']==EA_base::STATUS_TRUE_ )? TRUE: FALSE; 
	                }
	            }
	            
	            if( $item_mail ){
	                return array('status'=>2, 'message'=>'该商品为可邮寄状态，分拆会增加邮寄成本。');
	            }
	            
                //print_r($item_gs_id);die;
	            $this->_db()->where_in('id', $ids );

	            $this->_db()->where('can_mail', EA_base::STATUS_FALSE_ );  //邮寄商品不能分拆
	            $this->_db()->where_in('status', array(
	                Shp_order_items::STATUS_DEFAULT,
	            ) );
	            
	            $data= array(
	                'ex_order'=> Shp_order_items::EX_ORDER_T,
	            );
	            $result= $this->_db()->update('shp_order_items', $data );
	            
	            if( $result ){
	                return array('status'=>1, 'message'=>'细单商品已被成功分拆！');
	            }
	        }
	    }
	    return array('status'=>2, 'message'=>'商品分拆失败。');
	}

	/**
	 * 导出订单细单明细
	 * @param Array $filter      过滤条件
	 * @param Array $item_field  摘选哪些字段
	 * @param string $start      订单开始时间
	 * @param string $end        订单结束时间
	 * @return Array
	 */
	public function export_item($filter=array(), $item_field=array(), $start=FALSE, $end=FALSE )
	{
	    $this->_init_admin_hotels();
	    if( $this->_admin_inter_id== FULL_ACCESS ){
	        
	    } else if( $this->_admin_inter_id ) {
	        $filter+= array('inter_id'=> $this->_admin_inter_id);
	    }
	    
	    if( count($filter)>0 ){
	        foreach ($filter as $k=> $v){
	            if(is_array($v)){
	                $this->_db()->where_in($k, $v);
	            } else {
	                $this->_db()->where($k, $v);
	            }
	        }
	    }
	    if($start) {
	        if( strlen($start)<=10 ) $start.= ' 00:00:00';
	        $this->_db()->where('order_time >', $start);
	    }
	    if($end) {
	        if( strlen($end)<=10 ) $end.= ' 23:59:59';
	        $this->_db()->where('order_time <', $end);
	    }
	     
	    //不设定时间最多导出3个月的数据
	    if(!$start && !$end){
	        $this->_db()->where('order_time >', date('Y-m-d H:i:s', strtotime('-3 month') ) );
	    }
	    $orders= $this->_db()->select('order_id, out_trade_no, order_time, pay_status')
	        ->order_by('order_id desc')->get($this->table_name())->result_array();

	    //查找数据为空
        if( count( $orders ) == 0 ){
        	return array();
        }

	    $ids= array();
	    foreach ($orders as $k=>$v){
	        $ids[$v['order_id']]= array(
	            'out_trade_no'=>$v['out_trade_no'],
	            'order_time'=>$v['order_time'],
	            'pay_status'=>$v['pay_status'],
	        );
	    }
	     
	    $this->_db()->where_in('order_id', array_keys($ids) );
	    $items= $this->_db()->get('shp_order_items' )->result_array();
	    $result= array();
	    $this->load->model('mall/shp_order_items');
	    $status_arr= $this->shp_order_items->order_item_status();
	    $payment_arr= $this->payment_status();
	     
	    foreach ($items as $k=>$v){
	        //状态标签转换
	        if( array_key_exists($items[$k]['status'], $status_arr) ){
	            $items[$k]['status']= $status_arr[$items[$k]['status']];
	        }
	
	        if( empty($item_field) ){
	            $result[$k][]= $items[$k];
	             
	        } else {
	            foreach ($items[$k] as $sk=> $sv){
	                if( in_array($sk, $item_field) ){
	                    $result[$k][$sk]= $sv ;
	                }
	            }
	            //下面2个字段用来匹配数量合并
	            $result[$k]['order_id']= $v['order_id'];
	            $result[$k]['gs_id']= $v['gs_id'];
	        }
	        $pay_status= array_key_exists($ids[$v['order_id']]['pay_status'], $payment_arr)?
	        $payment_arr[$ids[$v['order_id']]['pay_status']]: '-';
	        array_unshift( $result[$k], $pay_status );
	        array_unshift( $result[$k], $ids[$v['order_id']]['order_time']);
	        array_unshift( $result[$k], $ids[$v['order_id']]['out_trade_no']);
	    }
	    //print_r( $result );die;
	     
	    //数量合并
	    $lines= array();
	    $last_orderid= $last_gsid= $last_key='';
	    foreach ($result as $k=>$v){
	        if( $last_orderid== $v['order_id'] && $last_gsid== $v['gs_id'] ){
	            $lines[$last_key]['qty']++;
	             
	        } else {
	            $last_orderid= $v['order_id'];
	            $last_gsid= $v['gs_id'];
	            $last_key= $k;
	            $lines[$k]= $v+ array('qty'=>1);
	        }
	        //清除下面2个用来匹配数量合并的字段
	        unset($lines[$k]['order_id']);
	        unset($lines[$k]['gs_id']);
	    }
	    //print_r( $lines );die;
	    return $lines;
	}
	
	/**
	 * 新建订单
	 * @param unknown $arr
	 * @return boolean|unknown
	 */
	public function create_order($arr)
	{
	    $this->load->model('mall/shp_cart');
	    $this->load->model('mall/shp_order_items');
	    $item_obj= $this->shp_order_items;
	    $this->load->model('mall/shp_goods');
	    $shp_goods= $this->shp_goods;
	    
	    //处理非法数据
	    foreach ($arr['products'] as $k=> $v) {
	        if($v<1) $arr['products'][$k]= 1;
	        else $arr['products'][$k]= intval($v);
	    }
	    
	    if (is_array( $arr ) && isset($arr['openid']) && isset($arr['inter_id']) && isset($arr['hotel_id']) 
	           && isset($arr['products']) && is_array($arr['products'])) {
	               
//BUG: 当商品的归属酒店跟专题的归属不一致的时候，导致商品拉取失败，导致订单细单为空，支付失败
//$this->_db()->where('inter_id', $arr['inter_id']);
            $products= array();
            foreach ($arr['products'] as $pid=> $pnum){
                $tmp = $this->_db()->where('onsale', $shp_goods::STATUS_T )
                    ->where('gs_id', $pid )->where('gs_nums>=', $pnum)
                    ->get('shp_goods')->result_array();
                
                if( $tmp && count($tmp)>0 ){
                    $products[$pid]= $tmp[0];
                    
                } else {
                    //一旦有库存不够的商品，即下单失败
	                return FALSE;
                }
                
                if( isset($tmp[0]['gs_nums']) && $tmp[0]['gs_nums']==$pnum ){
                    $arr['disabled'][] = $pid;
                }
            }
/**
 * Array (
     [openid] => asg23134
     [inter_id] => a429262687
     [hotel_id] => 1
     [fans_id] =>
     [saler] =>
     [topic] => Array (
         .....
     )
     [products] => Array (
         [13] => 1
         [14] => 2
     )
     //下架产品id
     [disabled] => Array (
         [13] => 1
         [14] => 2
     )
 )
 */
	        if(empty($products)){
	            //商品筛选失败，返回错误
	            return FALSE;
	        }

	        //代金券金额
	        $discount  = 0;
	        
	        //立减金额
	        $sub_count = 0;
	        
	        //商品总金额
	        $amount = 0;
	        foreach ($products as $product) {
	            //单价 *数量
                $pnum= $arr['products'][$product['gs_id']]<1? 1: $arr['products'][$product['gs_id']];
	            $amount += $product['gs_wx_price']* $pnum;
	        }
	        
	        //运费金额计算
	        $shipping_amount = 0;
	        if( isset($arr['topic']['freeship_level']) && isset($arr['topic']['shipment_fee']) ){
	            if($amount<$arr['topic']['freeship_level']){
	                $shipping_amount= $arr['topic']['shipment_fee'];
	            }
	        }
	        $amount += $shipping_amount;

	        
	        $this->_db()->trans_begin ();
	        
	        $insert_id= $this->save_ex_order($products, $arr, $amount, $shipping_amount, $discount, $sub_count);
/** 原插入订单流程
	        $out_trade_no = $this->gen_order_no();
	        $topic_id= isset($arr['topic']['topic_id'])? $arr['topic']['topic_id']:NULL;
	        
            //print_r($arr);die;
	        $this->_db()->insert('shp_orders',array(
	            'out_trade_no'=> $out_trade_no,
	            'openid'      => $arr['openid'],
	            'hotel_id'    => $arr['hotel_id'],
	            'inter_id'    => $arr['inter_id'],
                'topic_id'    => $topic_id,
	            'status'      => $this::STATUS_DEFAULT,
	            'order_time'  => date('Y-m-d H:i:s'),
	            'pay_status'  => $this::PAYMENT_F,
	            
	            'total_fee'   => $amount,
	            'card_fee'    => $discount,
	            'sub_fee'     => $sub_count,
	            'shipping_fee'=> $shipping_amount,
	            
	            'saler'       => $arr['saler'],
	            'fans_id'     => $arr['fans_id'],
	        ));
	        
	        $insert_id = $this->_db()->insert_id();
	        $time = date('Y-m-d H:i:s');
	        $order_items = array();
	        foreach ($products as $item) {
	            //循环插入订单细单
	            $nums= $arr['products'][$item['gs_id']];
	            for ($i=0; $i < $nums; $i++) {
	                array_push($order_items, array(
	                    'order_id'     =>$insert_id,
                        //'topic_id'=> $topic_id,
        	            'hotel_id'     => $arr['hotel_id'],
        	            'inter_id'     => $arr['inter_id'],
    	                'gs_id'        => $item['gs_id'],
    	                'gs_name'      => $item['gs_name'],
    	                'gs_unit'      => $item['gs_unit'],
    	                'market_price' => $item['gs_market_price'],
    	                'price'        => $item['gs_wx_price'],
    	                'promote_price'=> $item['gs_wx_price'],
    	                'openid'       => $arr['openid'],
    	                'get_openid'   => $arr['openid'],   //原购买人
                        'get_time'     => $time,             //购买时间
    	                'gs_code'      => time(). mt_rand(1000,9999),
    	                'can_mail'     => $item['can_mail'],
    	                'can_pickup'   => $item['can_pickup'],
    	                'ex_order'     => $item_obj::EX_ORDER_F, //是否拆单
    	                'is_add_pack'  => EA_base::STATUS_FALSE_,//是否放入卡包
    	                'status'       => $item_obj::STATUS_DEFAULT,
    	                
    	                'consume_start'=> isset($item['consume_start'])? $item['consume_start']: NULL,
        	            'consume_end'  => isset($item['consume_end'])? $item['consume_end']: NULL,
	                ) );
	            }
	        }
	        if($order_items) $this->_db()->insert_batch('shp_order_items', $order_items);
*/
	        
	        //下单后清空购物车
	        $this->shp_cart->flush_cart($arr['openid'], $arr['inter_id'], $arr['hotel_id']);
	        
	        //下单后扣减库存，
	        $sql = "update `{$this->_db()->dbprefix('shp_goods')}` set `gs_nums`=`gs_nums`-? WHERE `gs_id`= ?";
            foreach ($arr['products'] as $pid=> $pnum){
                 $this->_db()->query($sql, array($pnum, $pid));
            }
            //下架0库存商品，注意：数组为空会导致所有商品同时下架
            if( isset($arr['disabled']) && count($arr['disabled'])>0 )
                $this->_db()->where_in('gs_id', $arr['disabled'])->update('shp_goods', array('onsale'=>$shp_goods::STATUS_F ) );
            
            
	        $this->_db()->trans_complete();
	        if ($this->_db()->trans_status() === FALSE) {
	            $this->_db()->trans_rollback ();
	            return FALSE;
	            
	        } else {
	            $this->_db()->trans_commit();
	            return $insert_id;
	        }
	    } else {
	        return FALSE;
	    }
	}
	
	/**
	 * 拆分思路：如果拆开2个主订单，会为统计、发票等环节带来非常复杂的判断，所以最终决定不拆分主订单，而分别将邮寄标记放入细单中，在各环节自行判断
	 *          商品配送方式两者选择其一，由商品编辑时负责控制；免邮费的金额按照全部订单总和，附加到可配送订单中去
	 * 前端显示思路：调整为按照细单的配送形式分为2类子订单，分别做处理，进入不同的处理通道。
	 *              至于该订单的自提特征和送朋友特征，采用和运算，要么全部可以，否则全部不可以（剩余问题交给商户和消费者决策）
	 *              如查找订单的第一个商品不可以邮寄，则默认全部可以自提，分享方式按照要么全部可以，否则全部不可以
	 * 
	 * @param array $products 筛选后的商品
	 * @param array $arr 订单保存数据
	 * @param Integer $amount 所有订单总额
	 * @param Integer $shipping_amount 运费
	 * @param Integer $discount 
	 * @param Integer $sub_count
	 */
	public function save_ex_order($products, $arr, $amount, $shipping_amount, $discount=0, $sub_count=0)
	{
	    try {
    	    $this->load->model('mall/shp_order_items');
    	    $item_obj= $this->shp_order_items;
    	    
	        $out_trade_no = $this->gen_order_no();
	        $topic_id= isset($arr['topic']['topic_id'])? $arr['topic']['topic_id']:NULL;
	        
	        //print_r($arr);die;
	        $this->_db()->insert('shp_orders',array(
	            'out_trade_no'=> $out_trade_no,
	            'openid'      => $arr['openid'],
	            'hotel_id'    => $arr['hotel_id'],
	            'inter_id'    => $arr['inter_id'],
	            'topic_id'    => $topic_id,
	            'status'      => $this::STATUS_DEFAULT,
	            'order_time'  => date('Y-m-d H:i:s'),
	            'pay_status'  => $this::PAYMENT_F,
	        
	            'total_fee'   => $amount,
	            'card_fee'    => $discount,
	            'sub_fee'     => $sub_count,
	            'shipping_fee'=> $shipping_amount,
	        
	            'saler'       => $arr['saler'],
	            'fans_id'     => $arr['fans_id'],
	        ));
	         
	        $insert_id = $this->_db()->insert_id();
	        if($insert_id){
    	        $time = date('Y-m-d H:i:s');
    	        $order_items = array();
    	        foreach ($products as $item) {
    	            //循环插入订单细单
    	            $nums= $arr['products'][$item['gs_id']];
    	            for ($i=0; $i < $nums; $i++) {
    	                array_push($order_items, array(
        	                'order_id'     => $insert_id,
        	                //'topic_id'=> $topic_id,
        	                'hotel_id'     => $arr['hotel_id'],
        	                'inter_id'     => $arr['inter_id'],
        	                'gs_id'        => $item['gs_id'],
        	                'gs_name'      => $item['gs_name'],
        	                'gs_unit'      => $item['gs_unit'],
        	                'market_price' => $item['gs_market_price'],
        	                'price'        => $item['gs_wx_price'],
        	                'promote_price'=> $item['gs_wx_price'],
        	                'openid'       => $arr['openid'],
        	                'get_openid'   => $arr['openid'],   //原购买人
        	                'get_time'     => $time,             //购买时间
        	                'gs_code'      => time(). mt_rand(1000,9999),
        	                'can_mail'     => $item['can_mail'],
        	                'can_gift'     => $item['can_gift'],
        	                'can_pickup'   => $item['can_pickup'],
        	                'ex_order'     => $item_obj::EX_ORDER_F, //是否拆单
        	                'is_add_pack'  => EA_base::STATUS_FALSE_,//是否放入卡包
        	                'status'       => $item_obj::STATUS_DEFAULT,

        	                'consume_start'=> isset($item['consume_start'])? $item['consume_start']: NULL,
        	                'consume_end'  => isset($item['consume_end'])? $item['consume_end']: NULL,

        	                'sku'          => $item['sku'],
        	                'is_virtual'   => $item['is_virtual'],
        	                'card_use_type'=> $item['card_use_type'],
        	                'wx_card_id'   => $item['wx_card_id'],
    	                ) );
    	            }
    	        }
    	        if($order_items) $this->_db()->insert_batch('shp_order_items', $order_items);
    	        return $insert_id;
    	        
	        } else {
	            return NULL;
	        }
	        
	    } catch (Exception $e) {
	        echo $e->getMessage();
	        return FALSE;
	    }
	}
	
	public function shipping_amount_calculate($products, $qty, $limit, $fee)
	{
	    foreach ($products as $v) {
	        //单价 *数量
	        $amount += $v['gs_wx_price']* $qty[$v['gs_id']];
	    }
	}
	
	/**
	 * 更新订单支付状态为已支付
	 * @param $inter_id
	 * @param $out_trade_no
	 * @param $openid
	 * @param $transaction_id
	 * @return boolean
	 */
	public function update_pay_status($inter_id, $out_trade_no, $openid, $transaction_id)
	{
	    /** 
支付返回数据格式，下列场景中，收款账号为子商户，用户在主商户号中的openid系统流程中无法获知
<xml><appid><![CDATA[wx07108d6280b84cb8]]></appid>
<bank_type><![CDATA[CFT]]></bank_type>
<cash_fee><![CDATA[1]]></cash_fee>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[Y]]></is_subscribe>
<mch_id><![CDATA[1228379302]]></mch_id>
<nonce_str><![CDATA[bbc6db5wmhj4csfm9u5bw8q0we851bd8]]></nonce_str>
<openid><![CDATA[oo89wt4XzfH-RxyZwoy-fa7-a0vU]]></openid>    <----  在主商务号openid
                 ovLoZv7qfD0D5B_TAc_FVhowNXuE       <-------   在子商务号中的openid，无法匹配
<out_trade_no><![CDATA[116M724286126485]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[E9D6B2481032B7F1E081153282C3D8D9]]></sign>
<sub_mch_id><![CDATA[1297763001]]></sub_mch_id>
<time_end><![CDATA[20160119110037]]></time_end>
<total_fee>1</total_fee>
<trade_type><![CDATA[JSAPI]]></trade_type>
<transaction_id><![CDATA[1002120795201601192779296905]]></transaction_id>
</xml>
	     */
	    $query = $this->get_order_pay_status( $inter_id, $out_trade_no, $openid );
	    if ($query) {
	        return TRUE;
	        
	    } else {
	        $this->_db()->where( array(
	            'inter_id' => $inter_id,
	            'out_trade_no' => $out_trade_no,
	            //'openid' => $openid,     //先屏蔽此参数，以防在子商户号支付时更新失败，参数作用是高并发时区分不同订单
	        ) );
	        if ($this->_db()->update( 'shp_orders', array( 
	            'pay_status' => $this::PAYMENT_T,
	            'pay_time' => date('Y-m-d H:i:s'),
	            'transaction_id' => $transaction_id,
	        ) )) {
	            return TRUE;
	        } else {
	            return FALSE;
	        }
	    }
	}
	/**
	 * 用户选择订单的邮寄地址，关联操作
	 * @param unknown $order_id
	 * @param unknown $address_id
	 * @return boolean
	 */
	public function save_mail_order($order_id,$address_id)
	{
	    $this->load->model('mall/shp_order_items');
	    $item_obj= $this->shp_order_items;

	    try {
	       $this->_db()->trans_begin ();
           //处理单品
	       $this->_db()->where(array(
	           'order_id'=> $order_id,
	           'can_mail'=> EA_base::STATUS_TRUE_, //将所有可邮寄的细单处理
	       ));
	       $this->_db()->update('shp_order_items', array(
	           'addr_id'=>$address_id,
	           'order_time'=>date('Y-m-d H:i:s'),
	           'status'=> $item_obj::STATUS_SHIP_PRE,
	       ));
	       //处理订单
	       $this->_db()->where(array(
	           'order_id'=> $order_id,
	           'status'=> self::STATUS_DEFAULT,
	       ));
	       $this->_db()->update('shp_orders', array(
	           'status'=> self::STATUS_PROCESSING,
	       ));
	       
	       $this->_db()->trans_commit();
	       return TRUE;
	       
	    } catch (Exception $e) {
	        $this->_db()->trans_rollback ();
	        return FALSE;
	    }
	}
	/**
	 * 查询订单支付状态
	 * @param $inter_id
	 * @param $out_trade_no
	 * @param $openid
	 * @return
	 */
	public function get_order_pay_status($inter_id,$out_trade_no,$openid)
	{
	    $this->_db()->where(array(
	        'inter_id'=> $inter_id,
	        'out_trade_no'=> $out_trade_no,
	        'openid'=> $openid,
	        'pay_status'=> $this::PAYMENT_T,
	    ));
	    $this->_db()->select('out_trade_no, pay_time, transaction_id');
	    $this->_db()->limit(1);
	    $query = $this->_db()->get('shp_orders')->row_array();
	    return $query;
	}
	
	public function get_order_details($inter_id,$hotel_id = null,$order_id)
	{
	    $this->_db()->where(array('inter_id'=>$inter_id,'order_id'=>$order_id));
	    if(!empty($hotel_id)){
	        $this->_db()->where('hotel_id',$hotel_id);
	    }
	    $this->_db()->limit(1);
	    $oi = $this->_db()->get('shp_orders')->row_array();
	
	    $sql = "SELECT soi.*, sg.gs_logo, sg.gs_desc, COUNT(soi.id) nums FROM ".
	           $this->_db()->dbprefix('shp_order_items'). " soi LEFT JOIN ". $this->_db()->dbprefix('shp_goods')
	           . " sg ON soi.gs_id=sg.gs_id WHERE soi.order_id=? GROUP BY sg.gs_id";
	
	    // 		$this->_db()->where(array('order_id'=>$order_id));
	    // 		$order_items = $this->_db()->get('shp_order_items')->row_array();
	    $order_items = $this->_db()->query($sql, array($order_id) )->result_array();
	    $oi['items'] = $order_items;
	    return $oi;
	}
	/**
	 * 订单详情（包括赠送的好友）
	 * @param $inter_id
	 * @param $hotel_id
	 * @param $order_id
	 * @return
	 */
	public function get_order_details_with_to_frns($inter_id,$hotel_id = null,$order_id)
	{
	    $this->_db()->where(array('inter_id'=>$inter_id,'order_id'=>$order_id));
	    if(!empty($hotel_id)){
	        $this->_db()->where('hotel_id',$hotel_id);
	    }
	    $this->_db()->limit(1);
	    $oi = $this->_db()->get('shp_orders')->row_array();
	
	    /**
SELECT oi.*,f.headimgurl,f.nickname,sgl.ge_openid,sgl.gt_openid,sgl.status gstatus FROM 
    (SELECT soi.*, sg.gs_logo, sg.gs_desc, COUNT(soi.id) nums FROM iwide_shp_order_items soi 
        LEFT JOIN iwide_shp_goods sg ON soi.gs_id=sg.gs_id WHERE soi.order_id='336' AND soi.inter_id='a429262687' GROUP BY sg.gs_id) oi 
  LEFT JOIN iwide_fans f ON f.openid=oi.openid AND f.inter_id=oi.inter_id 
LEFT JOIN iwide_shp_gift_log sgl ON oi.share_code=sgl.ge_code 
	     */
	    //$sql = "SELECT soi.*,sg.gs_logo,sg.gs_desc,COUNT(soi.id) nums FROM iwide_shp_order_items soi LEFT JOIN iwide_shp_goods sg ON soi.gs_id=sg.gs_id WHERE soi.order_id=? GROUP BY sg.gs_id";
	    $sql = "SELECT oi.*,f.headimgurl,f.nickname,sgl.ge_openid,sgl.gt_openid,sgl.status gstatus 
	        FROM (SELECT soi.*, sg.gs_logo, sg.gs_desc, COUNT(soi.id) nums 
	           FROM ".$this->_db()->dbprefix('shp_order_items')." soi 
	               LEFT JOIN ".$this->_db()->dbprefix('shp_goods')." sg 
	                   ON soi.gs_id=sg.gs_id WHERE soi.order_id=? AND soi.inter_id=? 
	                   GROUP BY sg.gs_id) oi LEFT JOIN ".$this->_db()->dbprefix('fans')." f 
	                       ON f.openid=oi.openid AND f.inter_id=oi.inter_id 
	                       LEFT JOIN ".$this->_db()->dbprefix('shp_gift_log')." sgl 
	                           ON oi.share_code=sgl.ge_code";

	    // 		$this->_db()->where(array('order_id'=>$order_id));
	    // 		$order_items = $this->_db()->get('shp_order_items')->row_array();
	    $order_items = $this->_db()->query($sql,array($order_id,$inter_id))->result_array();
	    $oi['items'] = $order_items;
	    return $oi;
	}
	
	/**
	 * 取我的订单
	 * @param $inter_id
	 * @param $openid
	 * @return
	 */
	public function get_my_orders($inter_id, $openid)
    {
        $this->_db()->where(array('inter_id'=>$inter_id, 'pay_status'=> $this::PAYMENT_T ));
        $this->_db()->where( 'openid', $openid );
        //$this->_db()->or_where( 'get_openid', $openid );

	    if(!empty($hotel_id)){
	        $this->_db()->where('hotel_id',$hotel_id);
	    }
	    $this->_db()->order_by('order_time DESC');
	    $oi = $this->_db()->get('shp_orders')->result_array();

	    foreach($oi as $key => $order) {
	        $sql = "SELECT soi.*, sg.gs_logo, sg.gs_desc, COUNT(soi.id) nums 
	            FROM ". $this->_db()->dbprefix('shp_order_items'). " soi 
	                LEFT JOIN ". $this->_db()->dbprefix('shp_goods'). " sg 
	                    ON soi.gs_id=sg.gs_id WHERE soi.order_id=? GROUP BY sg.gs_id";
	        
	        $order_items = $this->_db()->query($sql, array($order['order_id']))->result_array();
	        $oi[$key]['items'] = $order_items;
	    }
	    return $oi;
	}
	
	public function get_gift_items($order_id,$openid)
	{
	    $this->_db()->where(array('openid'=>$openid,'order_id'=>$order_id));
	    return $this->_db()->get('shp_order_items')->result_array();
	}
	/**
	 * 是否自己分享的记录
	 * @param  $code 分享码
	 * @param  $openid
	 * @param  $order_id 订单ID
	 * 如果是，返回当次分享记录
	 */
	public function share_myself($code,$openid,$order_id)
	{
	    $this->_db()->where(array(
	        'ge_code'=>$this->uri->segment(6),
	        'ge_openid'=>$openid,
	        'order_id'=>$this->uri->segment(4)
	    ));
	    $this->_db()->limit(1);
	    return $this->_db()->get('shp_gift_log')->row_array();
	}
	/**
	 * 保存分享记录
	 * @param $inter_id
	 * @param $openid
	 * @param $share_code 分享码
	 * @param $order_id 订单ID
	 * @param $items array(订单子项目)
	 * @param $type 分享类型0：订单，1：订单子项目
	 */
	public function save_share($inter_id,$openid,$share_code,$order_id,$item,$type)
	{
	    $this->load->model('mall/shp_order_items');
	    $this->load->model('mall/shp_gift_log');
	    $item_obj= $this->shp_order_items;
	    $log_obj= $this->shp_gift_log;
	    
	    $this->_db()->trans_begin ();
	    if($type == 0){
	        $this->_db()->where(array('order_id'=>$order_id,'status'=>$this::STATUS_DEFAULT ));
	        $this->_db()->update('shp_orders',array('status'=> $this::STATUS_GIFT ));
	        $this->_db()->where(array('order_id'=>$order_id,'status'=> $this::STATUS_DEFAULT ));
	    }else{
	        $this->_db()->where(array('status'=> $this::STATUS_DEFAULT ));
	        $this->_db()->where_in($item);
	    }
	    $this->_db()->update('shp_order_items',array(
            //'openid'=>'',
	        'status'=>$item_obj::STATUS_GIFTING ,
	        'share_code'=>$share_code
	    ));
	    $item = implode($item, ',');
	    
	    $this->_db()->insert('shp_gift_log',array(
	        'ge_time'=>date('Y-m-d H:i:s'),
	        'ge_code'=>$share_code,
	        'status'=> $log_obj::STATUS_GIFTING,
	        'order_id'=>$order_id,
	        'ge_openid'=>$openid,
	        'order_items'=>$item,
	    ));
	    $this->_db()->trans_complete ();
	    if ($this->_db()->trans_status () === FALSE) {
	        $this->_db()->trans_rollback ();
	        return FALSE;
	    } else {
	        $this->_db()->trans_commit ();
	        return TRUE;
	    }
	}
	
	public function get_share_log($code,$order_id)
	{
	    $this->load->model('mall/shp_gift_log');
	    $log_obj= $this->shp_gift_log;
	    $sql = 'SELECT oi.*,gl.status gstatus FROM '.$this->_db()->dbprefix('shp_order_items').' oi 
	        LEFT JOIN '.$this->_db()->dbprefix('shp_gift_log')
	            .' gl ON gl.ge_code=oi.share_code AND gl.order_id=oi.order_id AND gl.`status`<' . $log_obj::STATUS_TIMEOUT
	            . ' WHERE oi.order_id=? AND gl.ge_code=?';
	    return $this->_db()->query($sql,array($order_id,$code))->result_array();
	}
	/**
	 * 接受赠送后处理
	 * @param String $openid
	 * @param String $share_code
	 * @param String $order_id
	 * @param String $type
	 * @return boolean
	 */
	public function save_receive($openid,$share_code,$order_id,$type )
	{
	    $this->load->model('mall/shp_order_items');
	    $item_obj= $this->shp_order_items;
	    
	    $this->load->model('mall/shp_gift_log');
	    $log_obj= $this->shp_gift_log;
	    
	    $this->_db()->trans_begin ();
	
	    if($type == 0){
	        $this->_db()->where(array('order_id'=>$order_id,'status'=>$this::STATUS_GIFT ));
	        $this->_db()->update('shp_orders',array('status'=>$this::STATUS_DEFAULT ));
	        $this->_db()->where(array('order_id'=>$order_id,'share_code'=>$share_code));
	    }else{
	        $this->_db()->where(array('order_id'=>$order_id,'status'=>$this::STATUS_GIFT,'share_code'=>$share_code));
	    }
	    $this->_db()->update('shp_order_items',array(
	        'status'=> $item_obj::STATUS_DEFAULT,
	        'openid'=>$openid,   //变更细单的持有人，get_openid 记录原购买人
	        'get_time'=>date('Y-m-d H:i:s')
	    ));
	    $this->_db()->where(array('order_id'=>$order_id, 'ge_code'=>$share_code) );
	    $this->_db()->update('shp_gift_log',array(
	        'gt_time'=>date('Y-m-d H:i:s'),
	        'status'=> $log_obj::STATUS_GETTED,
	        'gt_openid'=>$openid
	    ));
	    $this->_db()->where(array('order_id'=>$order_id));
	    $this->_db()->update('shp_orders', array('status'=>$this::STATUS_DEFAULT ));
	    $this->_db()->trans_complete ();
	    
	    if ($this->_db()->trans_status () === FALSE) {
	        $this->_db()->trans_rollback ();
	        return FALSE;
	    } else {
	        $this->_db()->trans_commit ();
	        return TRUE;
	    }
	}
	/**
	 * 撤销/回收分享操作
	 * @param unknown $share_code
	 * @return boolean
	 */
	public function recy_share($share_code)
	{
	    $this->load->model('mall/shp_order_items');
	    $item_obj= $this->shp_order_items;
	    $this->_db()->trans_begin ();
	    $this->_db()->where(array('ge_code'=>$share_code, 'status'=>$this::STATUS_DEFAULT ));
	    $this->_db()->select('order_id');
	    $this->_db()->limit(1);
	    
	    $order_id = $this->_db()->get('shp_gift_log')->row_array();
	    $order_id = $order_id['order_id'];
	    
	    $this->_db()->where(array('order_id'=>$order_id));
	    $this->_db()->update('shp_orders', array('status'=>$this::STATUS_DEFAULT));    //订单恢复到默认
	    
	    $this->_db()->where(array('ge_code'=>$share_code,'status'=>$this::STATUS_DEFAULT ));
	    $this->_db()->update('shp_gift_log', array('status'=>$this::STATUS_GIFTED, 'gt_time'=>date('Y-m-d H:i:s')));
	    
	    $this->_db()->where(array('share_code'=>$share_code,'status'=>$this::STATUS_GIFT ));
	    $this->_db()->update('shp_order_items', array('status'=> $item_obj::STATUS_DEFAULT, 'share_code'=>''));
	    
	    $this->_db()->trans_complete ();
	    if ($this->_db()->trans_status () === FALSE) {
	        $this->_db()->trans_rollback ();
	        return FALSE;
	    } else {
	        $this->_db()->trans_commit ();
	        return TRUE;
	    }
	}
	/**
	 * 分享人的信息
	 * @param unknown $code
	 * @param unknown $order_id
	 */
	public function get_share_man_details($code, $order_id)
	{
	    $sql = 'SELECT * FROM '.$this->_db()->dbprefix('shp_gift_log').' gl 
	        LEFT JOIN '.$this->_db()->dbprefix('fans').' f 
	            ON gl.ge_openid=f.openid WHERE gl.ge_code=? AND gl.order_id=?';
	    $this->_db()->limit(1);
	    return $this->_db()->query($sql,array($code,$order_id))->row_array();
	}
	
	/**
	 * 已填写邮寄地址的订单
	 * @param $inter_id
	 * @param $hotel_id
	 */
	public function get_mail_items($inter_id,$hotel_id,$offset = 0,$limit = null)
	{
	    $this->load->model('mall/shp_order_items');
	    $item= $this->shp_order_items;
	    $sql = 'SELECT soi.gs_name, soi.id soid, soi.order_time, sa.* 
	        FROM (SELECT * FROM iwide_shp_order_items WHERE `status`='. $item::STATUS_SHIPPING. ') soi 
	        LEFT JOIN iwide_shp_address sa 
	        ON sa.id=soi.addr_id ORDER BY soi.order_time DESC';
	    if(!empty($limit)) {
	        $sql .= ' limit ?,?';
	        return $this->_db()->query($sql,array($offset,$limit));
	    } else
	        return $this->_db()->query($sql);
	}
	
	/**
	 * 发放红包
	 * @param char 公众号唯一标识
	 * @param int 订单号
	 * @param String 用户唯一标识OPENID
	 * @see https://pay.weixin.qq.com/wiki/doc/api/cash_coupon.php?chapter=13_5
	 * @return boolean
	 */
	public function send_coupon($inter_id,$order_id,$openid)
	{
	    $this->_db()->where( array (
	        'inter_id' => $inter_id,
	        'openid'   => $openid,
	        'order_id' => $order_id,
	        'status'   => EA_base::STATUS_FALSE_,
	    ) );
	    // 订单已发放过
	    if ($this->_db()->get( 'shp_coupons' )->num_rows () > 0)
	        return TRUE;
	    $this->load->model( 'pay/wxpay_model' );
		$this->load->model( 'wx/publics_model', 'publics');
	    $this->load->helper( 'common' );
	    $param = $this->wxpay_model->get_pay_paras( $inter_id );
	    
	    $public_info          = $this->publics->get_public_by_id( $inter_id );
	    $arr ['nonce_str']    = createNoncestr ();
	    $arr ['mch_billno']   = $param ['mch_id'] . date( 'Ymd' ) . time ();
	    // 组成： mch_id+yyyymmdd+10位一天内不能重复的数字。
	    $arr ['mch_id']       = $param ['mch_id'];
	    $arr ['wxappid']      = $public_info ['app_id'];
	    $arr ['nick_name']    = '信息驿站';
	    $arr ['send_name']    = '信息驿站';
	    $arr ['re_openid']    = $openid;
	    $arr ['total_amount'] = 100;
	    $arr ['min_value']    = 100;
	    $arr ['max_value']    = 100;
	    $arr ['total_num']    = 1;
	    $arr ['wishing']      = '再接再厉';
	    $arr ['client_ip']    = $_SERVER ["REMOTE_ADDR"];
	    $arr ['act_name']     = '送红包活动';
	    $arr ['remark']       = '送红包活动';
	    $this->load->model( 'wxpay_model' );
	    $arr ['sign'] = $this->wxpay_model->getSign( $arr, array (
	        'key'    => $param ['key'],
	        'app_id' => $public_info ['app_id']
	    ) );
	    $extras = array ();
	    $extras ['CURLOPT_CAINFO']  = realpath( './media/pay_certi' ) . '/rootca_' . $param ['mch_id'] . '.pem';
	    $extras ['CURLOPT_SSLCERT'] = realpath( './media/pay_certi' ) . '/apiclient_cert_' . $param ['mch_id'] . '.pem';
	    $extras ['CURLOPT_SSLKEY']  = realpath( './media/pay_certi' ) . '/apiclient_key_' . $param ['mch_id'] . '.pem';
	    $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack ';
	    $res = doCurlPostRequest( $url, $this->wxpay_model->arrayToXml( $arr ), $extras );
	    if ($res) {
	        $res_arr = $this->wxpay_model->xmlToArray( $res );
	        if ($res_arr ['return_code'] == 'SUCCESS' && $res_arr ['result_code'] == 'SUCCESS') {
	            $arr = array (
	                'order_id'    => $order_id,
	                'inter_id'    => $inter_id,
	                'hotel_id'    => $hotel_id,
	                'openid'      => $openid,
	                'total_fee'   => $arr ['total_amount'],
	                'fee_time'    => date( 'Y-m-d H:i:s' ),
	                'status'      => EA_base::STATUS_TRUE_,
	                'mch_billno'  => $res_arr['mch_billno'],
	                'send_time'   => date('Y-m-d H:i:s',strtotime($res_arr['send_time'])),
	                'send_listid' => $res_arr['send_listid']
	            );
	            $this->_db()->insert('shp_coupons',$arr);
	            // $this->_db()->trans_begin();
	            // $this->
	            // $this->_db()->trans_complete();
	            // if($this->_db()->trans_status() === FALSE){
	            // $this->_db()->trans_rollback();
	            // }else{
	            // $this->_db()->trans_commit();
	            // return TRUE;
	            // }
	            return TRUE;
	        }
	        return FALSE;
	    } else {
	        return FALSE;
	    }
	}
	
	/**
	 * 生成订单号
	 * @return string
	 */
	private function gen_order_no()
	{
	    $date_code= array(
	        '0','1','2','3','4','5','6','7','8','9',
	        'A','C','D','E','F','G','H','J','K',
	        'M','N','P','Q','R','T','U','V','W','X','Y','Z','S');
	    //eg: C 15 X 94737 74906 00
	    return strtoupper( dechex(date('m'))). date('y'). $date_code[intval(date('d'))]
	        . substr(time(),-5). substr(microtime(),2,5) .sprintf('%02d',rand(0,99));
	}

	/**
	 * 将双位字符计算出混淆后的结果（含双向处理）
	 * @param unknown $string
	 * @param unknown $gendev
	 * @param string $type
	 * @return string
	 */
	private function _order_no_cal($string, $gendev, $type='en')
	{
        $int= intval($string);
        if($type=='en'){
            $tmp= $int+ $gendev;
            if($tmp>= 100) return substr($tmp, -2);
            return str_pad($tmp, 2, "0", STR_PAD_LEFT);
        } else {
            if($int< $gendev) $int+= 100;
            $tmp= $int- $gendev;
            return str_pad($tmp, 2, "0", STR_PAD_LEFT);
        }
	}
	/**
	 * 将订单号顺序混淆作为二维码数字，无法直接猜出，用于核销加密
	 * @param  String $type en|de 参数分别为混淆和还原操作
	 * @param  String $item_id  仅在 $type='en' 时有效
	 * @return String 混淆/还原后的订单号
	 */
    public function qr_order_no( $order_no, $item_id= NULL, $type='en' )
    {
        $data_arr= preg_split("/([a-zA-Z0-9]?)/", $order_no, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        //print_r($data_arr);die;
        $new_arr= array();
        $first= hexdec($data_arr[0]);
        $rootdev= intval($data_arr[1]. $data_arr[2])% $first;
        
        if($type=='en'){
            $new_arr[]= $data_arr[0]. $data_arr[1]. $data_arr[2]. $data_arr[3];
            $new_arr[]= $gendev= $this->_order_no_cal($data_arr[4]. $data_arr[5], $rootdev);
            $new_arr[]= $gendev= $this->_order_no_cal($data_arr[6]. $data_arr[7], $gendev);
            $new_arr[]= $gendev= $this->_order_no_cal($data_arr[8]. $data_arr[9], $gendev);
            
            $new_arr[]= $gendev= $this->_order_no_cal($data_arr[14]. $data_arr[15], $gendev);
            $new_arr[]= $gendev= $this->_order_no_cal($data_arr[12]. $data_arr[13], $gendev);
            $new_arr[]= $gendev= $this->_order_no_cal($data_arr[10]. $data_arr[11], $gendev);
            
            if($item_id){
                $tmp= substr($item_id, -4);
                $tmp= strtoupper( dechex( intval($tmp) ) ); //十进制转十六进制
                $tmp= str_pad($tmp, 4, "0", STR_PAD_LEFT);
                $new_arr[]= $tmp;
            }
            
        } else {
            $gendev= intval($data_arr[8]. $data_arr[9]);
            $ele= $this->_order_no_cal($data_arr[10]. $data_arr[11], $gendev, 'de');
            array_unshift($new_arr, $ele);
            $gendev= intval($data_arr[10]. $data_arr[11]);
            $ele= $this->_order_no_cal($data_arr[12]. $data_arr[13], $gendev, 'de');
            array_unshift($new_arr, $ele);
            $gendev= intval($data_arr[12]. $data_arr[13]);
            $ele= $this->_order_no_cal($data_arr[14]. $data_arr[15], $gendev, 'de');
            array_unshift($new_arr, $ele);
            
            $gendev= intval($data_arr[6]. $data_arr[7]);
            $ele= $this->_order_no_cal($data_arr[8]. $data_arr[9], $gendev, 'de');
            array_unshift($new_arr, $ele);
            $gendev= intval($data_arr[4]. $data_arr[5]);
            $ele= $this->_order_no_cal($data_arr[6]. $data_arr[7], $gendev, 'de');
            array_unshift($new_arr, $ele);
            $ele= $this->_order_no_cal($data_arr[4]. $data_arr[5], $rootdev, 'de');
            array_unshift($new_arr, $ele);
            array_unshift($new_arr, $data_arr[0]. $data_arr[1]. $data_arr[2]. $data_arr[3]);

            if( count($data_arr)>=19 ){
                //十六进制转十进制
                $tmp= hexdec( $data_arr[16]. $data_arr[17]. $data_arr[18]. $data_arr[19] );
                $new_arr[]= str_pad($tmp, 4, '0', STR_PAD_LEFT);
            }
        }
        //print_r($new_arr);
        $new_no= implode('', $new_arr);
        return $new_no;
    }
    
    public function qr_order_no_splite($order_no, $item_id, $splite='-')
    {
        $code= $this->qr_order_no($order_no, $item_id, 'en');
        $data_arr= preg_split("/([a-zA-Z0-9]?)/", $code, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $new_code= '';
        foreach ($data_arr as $k=> $v) {
            $new_code.= $v;
            if( $k!=(count($data_arr)-1) && ($k+1)%4==0 ) $new_code.= $splite;
        }
        return $new_code; 
    }
	
}
