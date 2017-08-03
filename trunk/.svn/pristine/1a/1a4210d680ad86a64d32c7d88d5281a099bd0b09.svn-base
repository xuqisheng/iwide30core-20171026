<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Gift_order_attr_customer {
    public $ip;
    public $openid;
    public $nickname;
    public $name;
    public $mobile;
    public function __construct($openid){
        $CI = & get_instance();
        $this->ip= $CI->input->ip_address();
        $this->openid= $openid;
    }
}
class Gift_order_attr_rule {
    public $total_give;  //一共消耗多少份
    public $count_give;  //几个人？
    public $per_give;  //每人领几个？
    public function __construct($per_give=1, $count_give=1){
        $this->per_give= $per_give? $per_give: 1;
        $this->count_give= $count_give? $count_give: 1;
        $this->total_give= $per_give * $count_give;
    }
}
class Gift_order_attr_theme {
    public $theme_id;
    public $message;
    public function __construct($theme_id, $message){
        $this->theme_id= $theme_id? $theme_id: 1;
        $this->message= $message? $message: '';
    }
}
class Gift_order extends MY_Model_Soma {

    public $business;

    public $hotel_id;
    
    public $is_p2p;
    /**
     * 主题对象
     * @var Gift_order_attr_theme
     */
    public $theme;
    public $send_from;
    public $send_order_id;
    /**
     * 客户对象
     * @var Gift_order_attr_customer
     */
    public $sender; //发送人
    public $received;   //接收人
    /**
     * 赠送规则对象
     * @var Gift_order_attr_rule
     */
    public $rule= NULL;
    
    /**
     * 细单对象(数组)
     * @var Array
     */
    public $item= array();
    public $received_item= array();
    public $rollback_item= array();
    
    const STATUS_PENDING = 1;
    const STATUS_GIFTING = 2;
    const STATUS_GETTING = 5;
    const STATUS_RECEIVED= 3;
    const STATUS_TIMEOUT = 4;

    const STATUS_ITEM_GIFT = 1;
    const STATUS_ITEM_RECEIVE= 2;

    const STATUS_RECEIVE_DEFAULT = 1;
    const STATUS_RECEIVE_ROLLBACK= 2;

    const GIFT_TYPE_P2P  = 1;
    const GIFT_TYPE_GROUP= 2;
    
    const EXPIRED_HOURS= 24;  //24小时候算过期
    const UNSENT_TIME_LIMIT = 300; // 5分钟后状态还未发出算为发送

    const SEND_FROM_ORDER = 1;
    const SEND_FROM_GIFT = 2;

    public function get_status_label()
    {
        return array(
            self::STATUS_PENDING => $this->lang->line('for_forwarding'),  //礼物生成，扣减可赠送数量
            self::STATUS_GIFTING => $this->lang->line('gifting'),  //发出动作后触发，可能由于快速关闭并不能100%准确
            self::STATUS_GETTING => $this->lang->line('accepting'),  //开始有人领取第一份礼物
            self::STATUS_RECEIVED=> $this->lang->line('received'),  //全部领取完毕
            self::STATUS_TIMEOUT=> $this->lang->line('overtime_return'),  //超时退回礼物
        );
    }

	/**
	 * @return array
	 *
     * todo 没用的话删除掉吧
	 */
    public function get_status_label_lang_key()
    {
        return array(
            self::STATUS_PENDING => 'for_forwarding',  //礼物生成，扣减可赠送数量
            self::STATUS_GIFTING => 'gifting',  //发出动作后触发，可能由于快速关闭并不能100%准确
            self::STATUS_GETTING => 'accepting',  //开始有人领取第一份礼物
            self::STATUS_RECEIVED=> 'received',  //全部领取完毕
            self::STATUS_TIMEOUT=> 'overtime_return',  //超时退回礼物
        );
    }

    public function get_item_status_label()
    {
        return array(
            self::STATUS_ITEM_GIFTED  => $this->lang->line('gifting'),
            self::STATUS_ITEM_RECEIVED=> $this->lang->line('received'),
        );
    }

    public function get_receiver_status_label()
    {
        return array(
            self::STATUS_RECEIVE_DEFAULT  => $this->lang->line('gift_received'),
            self::STATUS_RECEIVE_ROLLBACK => $this->lang->line('gift_return'),
        );
    }

    public function get_gift_type_label()
    {
        return array(
            self::GIFT_TYPE_P2P  => '简单赠送',
            self::GIFT_TYPE_GROUP=> '群发赠送',
        );
    }
    
    public function get_send_from_label()
    {
        return array(
            self::SEND_FROM_ORDER  => '来自订单',
            self::SEND_FROM_GIFT => '来自礼物',
        );
    }

	public function get_resource_name()
	{
		return '赠送订单';
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
		return $this->_shard_table('soma_gift_order', $inter_id);
	}
	public function receiver_table_name($inter_id=NULL)
	{
		return $this->_shard_table('soma_gift_order_receiver', $inter_id);
	}
    public function item_table_name($business, $inter_id=NULL)
    {
        return $this->_shard_table("soma_gift_order_item_{$business}", $inter_id);
    }
    public function order_idx_table_name( $inter_id=NULL)
    {
        return $this->_shard_table('soma_gift_order_idx', $inter_id);
    }
    
	public function table_primary_key()
	{
	    return 'gift_id';
	}
	public function item_table_primary_key()
	{
	    return 'item_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'gift_id'=> '赠送编号',
            'business'=> '所属业务',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'total_qty'=> '发送总数',
            'count_give'=> '收礼人数',
            'per_give'=> '收礼份数',
            'is_p2p'=> '赠送形式',
            'theme_id'=> '赠送主题',
            'message'=> '祝福语',
            'openid_give'=> '赠送人',
            'openid_received'=> '接收人',
            'create_time'=> '赠送时间',
            'update_time'=> '更新时间',
            'status'=> '状态',
            'send_from' => '礼物来源',
            'send_order_id' => '源编号',
		);
	}

	//发送礼物状态检测
	public function can_gifting_status()
	{
	    return array( self::STATUS_PENDING );
	}
	//接受礼物状态检测
	public function can_recevie_status()
	{
	    return array( self::STATUS_PENDING, self::STATUS_GIFTING, self::STATUS_GETTING );
	}
	//回滚礼物状态检测
	public function can_rollback_status()
	{
	    return array( self::STATUS_PENDING, self::STATUS_GIFTING, self::STATUS_GETTING );
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'gift_id',
            //'business',
            'inter_id',
            'hotel_id',
            'total_qty',
            'count_give',
            'per_give',
            'send_from',
            'send_order_id',
            //'openid_give',
            //'openid_received',
            'create_time',
            'update_time',
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
            'gift_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'business' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_business_type(),
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $hotels,
            ),
            'total_qty' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled step="1" ',
                'type'=>'number', 
            ),
            'per_give' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number',	//textarea|text|combobox|number|email|url|price
            ),
            'count_give' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled step="1" ',
                'type'=>'number', 
            ),
            'is_p2p' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=> 'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> self::get_gift_type_label(),
            ),
            'theme_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> array(),
            ),
            'message' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' rows="3" ',
                'type'=>'textarea',	//textarea|text|combobox|number|email|url|price
            ),
            'send_from' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $this->get_send_from_label(),
            ),
            'send_order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'openid_give' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'openid_received' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
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
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> self::get_status_label(),
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'gift_id', 'sort'=>'desc');
	}

	public function get_orderid_ticket($business)
	{
	    $this->load->model('soma/ticket_center_model');
	    return $this->ticket_center_model->get_increment_id_gift($business);
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	/**
	 * 显示赠送细单明细（以asset item明细信息显示）
	 * Usage: $model->load($id)->get_order_items();
	 */
	public function get_order_items($business, $inter_id)
	{
	    $primary_key= $this->table_primary_key();
	    if( !$this->m_get($primary_key) ){
	        Soma_base::inst()->show_exception('Please Load Model first.');
	    }
	    //根据业务类型初始化对象
	    $business= strtolower($business);
	    $item_object_name= "Gift_item_{$business}_model";
	    require_once dirname(__FILE__). DS. "$item_object_name.php";
	    $object= new $item_object_name();
	    
	    $result= $object->get_asset_items($this, $inter_id);
	    return $result;
	}

    public function _write_log( $content )
    {
        $path= APPPATH. 'logs'. DS. 'soma'. DS. 'gift_receive'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $file= $path. date('Y-m-d_H'). '.txt';
        $this->write_log($content, $file);
    }
	/**
	 * 显示赠送订单列表（包含明细）
	 * Usage: $model->get_order_list('package', 'a123456789', array('openid'=>'asgaehae'), 'order_id desc', '20,150' );
	 */
	public function get_order_list($business, $inter_id, $filter, $sort=NULL, $limit=NULL )
	{
	    //根据业务类型初始化对象
	    $business= strtolower($business);
	    $item_object_name= "Gift_item_{$business}_model";
	    require_once dirname(__FILE__). DS. "$item_object_name.php";
	    $object= new $item_object_name();
	     
	    $ids= $sort_data= array();
	    $data= $this->find_all($filter, $sort, $limit);
        $pk= $this->table_primary_key();
        foreach ($data as $k=> $v){
            array_push($ids , $v[$pk]);
            //将主单排好序
            $sort_data[$v[$pk]]= $v;
        }
        //print_r($sort_data);die;
        //print_R($ids);die;  //$ids 为$gift_order->gift_id,
        if( count($ids)>0 ){
            //列表item引用asset来表示
            $items= $object->get_asset_items_byIds($ids, $business, $inter_id);
            foreach ($items as $k=> $v){
                //将细单按照上面排好的顺序加入到 items 数组中
                $sort_data[$k]['items'][]= $v;
            }
        }

	    return $sort_data;
	}
	public function get_order_list_byIds($business, $inter_id, $gids, $filter=array(), $sort=NULL, $limit=NULL )
	{
	    //根据业务类型初始化对象
	    $business= strtolower($business);
	    $item_object_name= "Gift_item_{$business}_model";
	    require_once dirname(__FILE__). DS. "$item_object_name.php";
	    $object= new $item_object_name();
	    
	    $ids= $sort_data= array();
	    $table= $this->table_name();
	    $db = $this->_shard_db_r('iwide_soma_r');
        $db->where_in('gift_id', $gids);
	    
	    foreach ($filter as $k=>$v){
	        if(is_array($v)){
                $db->where_in($k, $v);
	        } else {
                $db->where($k, $v);
	        }
	    }
	    $data= $db->order_by($sort)->limit($limit)->get($table)->result_array();
	
	    $pk= $this->table_primary_key();
	    foreach ($data as $k=> $v){
	        array_push($ids , $v[$pk]);
	        //将主单排好序
	        $sort_data[$v[$pk]]= $v;
	    }
	    //print_r($sort_data);die;
	    //print_R($ids);die;  //$ids 为$gift_order->gift_id,
	    if( count($ids)>0 ){
	        //列表item引用asset来表示
	        $items= $object->get_asset_items_byIds($ids, $business, $inter_id);
	        foreach ($items as $k=> $v){
	            //将细单按照上面排好的顺序加入到 items 数组中
	            $sort_data[$k]['items'][]= $v;
	        }
	    }
	    return $sort_data;
	}

	/**
	 * 显示赠送订单对应资产明细
	 * Usage: $model->load($id)->get_order_detail();
	 */
	public function get_order_detail($business, $inter_id)
	{
	    $primary_key= $this->table_primary_key();
	    if( !$this->m_get($primary_key) ){
	        Soma_base::inst()->show_exception('Please Load Model first.');
	    }
	    $detail= $this->m_data();
	    $detail['items']= $this->get_order_items($business, $inter_id);
	    return $detail;
	}
	
	/**
	 * 订单赠礼保存
	 * @author  libinyan@mofly.cn
	 * Usage:
	 *   $model->sender= '';
	 *   $model->rule= '';
	 *   $model->item= '';  //每个带上qty_require需求量，item为 asset item.
	 *   $model->order_save($business, $inter_id);
	 */
	public function order_save($business, $inter_id)
	{
        $return = array(
            'status' => soma_base::STATUS_FALSE,
            'msg'   => ''
        );
	    try {
	        $this->_shard_db($inter_id)->trans_begin ();
	        $business= strtolower($business);
	        $this->business= $business;
	         
	        //根据业务类型初始化对象
	        $item_object_name= "Gift_item_{$business}_model";
	        require_once dirname(__FILE__). DS. "$item_object_name.php";
	        $object= new $item_object_name();
	
	        //赠送资产检测
	        $stock_enough= $object->check_item_asset($this, $inter_id);
	        if( !$stock_enough ){
                $return['msg'] = '很抱歉，您的可赠送数量不足。';
                return $return;
	        }
	        
	        //统一获取订单号
	        $order_id= $this->get_orderid_ticket($business);
	        if( !$order_id ){
                $return['msg'] = '大家赠礼热情高涨，系统正玩命加载中，请稍后再试。';
                return $return;
	        }
            //echo $order_id;die;
            
	        $item= $this->item;
	        $this->hotel_id= $item[0]['hotel_id'];
	        $CI = & get_instance();
	        $remote_ip= $CI->input->ip_address();
	        //组装插入数据
	        $data= array(
	            'gift_id'=> $order_id,
	            'business'=> $this->business,
	            'inter_id'=> $inter_id,
	            'hotel_id'=> $this->hotel_id,
	            'theme_id'=> $this->theme->theme_id,
	            'message'=> $this->theme->message,
	            'total_qty'=> $this->rule->total_give,
	            'count_give'=> $this->rule->count_give,
	            'per_give'=> $this->rule->per_give,
	            'is_p2p'=> $this->is_p2p,
                'send_from'=> $this->send_from,
                'send_order_id'=> $this->send_order_id,
	            'openid_give'=> $this->sender->openid,
	            //'openid_received'=> '',
	            'create_time'=> date('Y-m-d H:i:s'),
	            'remote_ip'=> $remote_ip,
	            'status'=> self::STATUS_PENDING,
	        );
	        //print_r($data);die;
	        //根据保存主订单相关的表，自定义主键需要用 _m_save()
	        $result= $this->_m_save($data);
            //echo $this->_shard_db($inter_id)->last_query();
            //var_dump($result);die;
	        $idx_data= array(
	            'gift_id'=> $order_id,
	            'business'=> $this->business,
	            'inter_id'=> $inter_id,
	            'hotel_id'=> $this->hotel_id,
	            'openid_give'=> $this->sender->openid,
	            'create_time'=> date('Y-m-d H:i:s'),
	            'is_p2p'=> $this->is_p2p,
	            'status'=> self::STATUS_PENDING,
	        );
	        $this->_shard_db()->insert($this->order_idx_table_name(), $idx_data);
	        //保存各个细单
	        $object->save_item($this, $inter_id);
	
	        //$this->item= $this->load($order_id)->get_order_items($business, $inter_id);
	        //送出后，对应资产明细更改，订单消费情况更改
	        $object->handle_after_gifting($this, $inter_id);
	         
	        $this->_shard_db($inter_id)->trans_complete();
	         
	        if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
	            $this->_shard_db($inter_id)->trans_rollback();
                $return['msg'] = '系统错误，请稍后再试。[code:trans error]';
                return $return;


	        } else {
	            $this->_shard_db($inter_id)->trans_commit();
                $return['status']  = Soma_base::STATUS_TRUE;
                $return['gift_id'] = $order_id;
	            return $return;
	        }
	         
	    } catch (Exception $e) {
            $return['msg'] = '系统错误，请稍后再试。';
            return $return;
        }
	}

	/**
	 * @author  libinyan@mofly.cn
	 * Usage:
	 *     $model->load()->order_gifting($business, $inter_id);
	 */
	public function order_gifting($business, $inter_id)
	{
	    try {
	        $this->_shard_db($inter_id)->trans_begin ();
	        
    	    if( in_array($this->m_get('status'), $this->can_gifting_status()) ){
    	        $this->m_set('status', self::STATUS_GIFTING )->m_save();
    	        //更新订单索引表
                $order_idx_table = $this->order_idx_table_name();
    	        $this->_db()->where('gift_id', $this->m_get('gift_id') )->update($order_idx_table, array(
    	            'status'=> self::STATUS_GIFTING,
    	        ) );
    	    }
    	    
	        $this->_shard_db($inter_id)->trans_complete();
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
	 * 赠礼订单被接受
	 * @author  libinyan@mofly.cn
	 * Usage:
	 *   $model->sender= '';
	 *   $model->received= '';
	 *   $model->order_received($business, $inter_id);
	 */
	public function order_received($business, $inter_id)
	{
	    try {
	        $this->_shard_db($inter_id)->trans_begin ();
	        $business= strtolower($business);
	         
	        //根据业务类型初始化对象
	        $item_object_name= "Gift_item_{$business}_model";
	        require_once dirname(__FILE__). DS. "$item_object_name.php";
	        $object= new $item_object_name();
	        
	        if( !$this->received_item ){
	            Soma_base::inst()->show_exception('赠送明细不能为空。');
	        }
	        //print_r($this->received_item);die;
	        
	        //送出后，对应资产明细更改，订单消费情况更改
	        $object->handle_after_gifted($this, $inter_id);

	        $order_idx_table = $this->order_idx_table_name();
	        
	        $is_p2p= $this->m_get('is_p2p');
	        if($is_p2p==self::GIFT_TYPE_P2P){
	            $return= $this->m_set('status', self::STATUS_RECEIVED )
    	            ->m_set('openid_received', $this->received->openid )
    	            ->m_set('update_time', date('Y-m-d H:i:s') )
    	            ->m_save();
	            
	            //更新订单索引表
	            $this->_db()->where('gift_id', $this->m_get('gift_id') )->update($order_idx_table, array(
	                'status'=> self::STATUS_RECEIVED,
	            ) );
	            
	        } else {
	            $count_give = $this->rule->count_give;
                $receiver_table_name= $this->receiver_table_name($inter_id);
                $this->_shard_db($inter_id)->insert($receiver_table_name, array(
                    'gift_id'=> $this->m_get('gift_id'),//$gift_id,
                    'inter_id'=> $inter_id,
                    'hotel_id'=> $this->m_get('hotel_id'),
                    'openid_give'=> $this->sender->openid,
                    'openid'=> $this->received->openid,
                    'total_qty'=> $this->rule->total_give,
                    'get_qty'=> $this->rule->per_give,
                    'get_time'=> date('Y-m-d H:i:s'),
                    'source'=> 1,  //默认
                    'remote_ip'=> $this->received->ip,
                    'status'=> self::STATUS_RECEIVE_DEFAULT,
                ) );
                $isNow = TRUE;//是否读主库
	            $receive_list= $this->giftOrderModel->get_receiver_list($inter_id, $this->m_get('gift_id'), array(), NULL, $isNow );
	            if( count($receive_list)== $count_give ){
	                $order_status= self::STATUS_RECEIVED;
	                
	            } else {
	                $order_status= self::STATUS_GETTING;
	            }
	            $return= $this->m_set('status', $order_status )
	                ->m_set('update_time', date('Y-m-d H:i:s') )->m_save();
	        
	            //更新订单索引表
	            $this->_db()->where('gift_id', $this->m_get('gift_id') )->update($order_idx_table, array(
	                'status'=> $order_status,
	            ) );
	        }
	        
	        $this->_shard_db($inter_id)->trans_complete();
	        if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
	            $this->_shard_db($inter_id)->trans_rollback();
	            return FALSE;
	        } else {
	            $this->_shard_db($inter_id)->trans_commit();
	            return $return;
	        }
	
	    } catch (Exception $e) {
	        return FALSE;
	    }
	}
	
	/**
	 * 获取可以回收的赠送订单
	 */
	public function get_expired_orders( $limit=20 )
	{
	    if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
	        //生产环境24小时退回
    	    $expired_time= self::EXPIRED_HOURS * 3600;
	    } else {
    	    //其他环境8分钟退回
	        $expired_time= self::EXPIRED_HOURS * 20;
	    }
	    $expired_datetime= date('Y-m-d H:i:s', time()- $expired_time );

	    $order_idx_table = $this->order_idx_table_name();
	    
	    $orders= $this->_shard_db_r('iwide_soma_r')->where('create_time <', $expired_datetime)
	       ->where_in('status', $this->can_rollback_status() )
	       ->order_by('gift_id asc')->limit($limit)
	       ->get($order_idx_table)->result_array();
	    return $orders;
	}

	/**
	 * 订单超时回退所有的赠礼
	 * @author  libinyan@mofly.cn
	 * Usage:
	 *   $model->load($id)->order_rollback($business, $inter_id);
	 */
	public function order_rollback($business, $inter_id)
	{
	    try {
	        $this->_shard_db($inter_id)->trans_begin ();
	        $business= strtolower($business);

	        //根据业务类型初始化对象
	        $item_object_name= "Gift_item_{$business}_model";
	        require_once dirname(__FILE__). DS. "$item_object_name.php";
	        $object= new $item_object_name();
	        
	        //$this->item= $this->get_order_items($business, $inter_id);
	        //送出后，对应资产明细更改，订单消费情况更改
	        $is_p2p= $this->m_get('is_p2p');
	        if($is_p2p == Soma_base::STATUS_TRUE){
	            $object->rollback_gift_item($this, $inter_id);
	        } else {
	            $object->rollback_gift_item_group($this, $inter_id);
                //删除redis对应的key
                $this->del_redis_list($inter_id, $this->m_get('gift_id'));
	        }
	        
	        $this->m_set('status', self::STATUS_TIMEOUT )
	            ->m_set('update_time', date('Y-m-d H:i:s') )
	            ->m_save();

	        //更新订单索引表
	        $order_idx_table = $this->order_idx_table_name();
	        $this->_db()->where('gift_id', $this->m_get('gift_id') )->update($order_idx_table, array(
	            'status'=> self::STATUS_TIMEOUT,
	        ) );
	        
	        $this->_shard_db($inter_id)->trans_complete();
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
	 * 获得
	 * @author  libinyan@mofly.cn
	 * Usage:
	 *   $model->load($id)->get_requirement($business, $inter_id);
	 */
	public function get_requirement($business, $inter_id)
	{
	    $item_table= $this->item_table_name($business);
	    $item= $this->_shard_db_r('iwide_soma_r')->where('gift_id', $this->m_get('gift_id'))
	       ->get($item_table)->result_array();
	    return $this->array_to_hash($item, 'qty', 'asset_item_id');
	}
	
	/**
	 * 获取赠送细单，用于后台显示赠送订单明细
	 * @author luguihong@mofly.cn
	 * @deprecated 
	 */
	
    public function get_gift_order_item( $business, $inter_id )
    {
        //赠送id
        $gift_id = isset( $this->_data['gift_id'] ) ? $this->_data['gift_id'] : '';

        if( !$inter_id || !$gift_id ){
            return FALSE;
        }

        $where = array();
        $where['inter_id'] = $inter_id;
        $where['gift_id'] = $gift_id;

        $table_name = $this->item_table_name($business, $inter_id);

        //获取数据
        $result = $this->_shard_db_r('iwide_soma_r')
                        ->where( $where )
                        ->get( $table_name )
                        ->result_array();
                        
        //处理输出信息
        $items = array();
        if( count( $result ) > 0 && isset( $result[0] ) ){

            $items = $result[0];

            //获取酒店名
            $this->load->model( 'hotel/hotel_model' );
            $hotel_info = $this->hotel_model->get_hotel_detail( $items['inter_id'], $items['hotel_id'] );
            if( $hotel_info ){
                $items['hotel_name'] = $hotel_info['name'];
            }else{
                $items['hotel_name'] = '';
            }

            //公众号名
            $this->load->model('wx/publics_model');
            $inter_info = $this->publics_model->get_public_by_id( $items['inter_id'] );
            if( $inter_info ){
                $items['inter_name'] = $inter_info['name'];
            }else{
                $items['inter_name'] = '';
            }

        }

        return $items;
    }
    
    /**
     * 获取某个赠送订单的接受列表
     * @param String $inter_id
     * @param String $gift_id
     * @param Array $filter
     * @param string $only_openid  是否只返回某个字段的哈希数组
     * @return Array
     */
    public function get_receiver_list($inter_id, $gift_id=NULL, $filter=array(), $only_field=NULL, $isNow=FALSE )
    {
        $table= $this->receiver_table_name($inter_id);

        //是否实时读取，读写分离存在时间差
        if( $isNow )
        {
            $db = $this->_shard_db($inter_id);
        } else {
            $db = $this->_shard_db_r('iwide_soma_r');
        }

        foreach ($filter as $k=>$v){
            if(is_array($v)){
                $db->where_in($k, $v);

            } else {
                $db->where($k, $v);
            }
        }
        $where= array(
            'inter_id'=> $inter_id,
            'status'=> self::STATUS_RECEIVE_DEFAULT,
        );
        if( $gift_id ) $where['gift_id']= $gift_id;
        
        $result= $db->where($where)
            ->get($table)->result_array();
        if( $only_field ){
            return $this->array_to_hash($result, $only_field);
        } else {
            return $result;
        }
    }
    //根据openid获取收到的礼物列表
    public function get_receiver_list_byOpenId($inter_id, $openid)
    {
        $table= $this->receiver_table_name($inter_id);
        return $this->_shard_db_r('iwide_soma_r')->where_in('openid',$openid)
            ->get($table)->result_array();
    }

    //查询礼物单被多少个人领取
    public function get_receiver_count($inter_id, $gift_id, $openid)
    {
        $where= array(
            'inter_id'=> $inter_id,
            'openid_give'=> $openid,
            'gift_id'=> $gift_id,
            'status'=> self::STATUS_RECEIVE_DEFAULT,
        );
        $table= $this->receiver_table_name($inter_id);
        return $this->_shard_db_r('iwide_soma_r')->where($where)
            ->from($table)->count_all_results();
    }

    /**
     * Redis储存对应键值
     * @param String $inter_id
     * @param String $gift_id
     * @return string
     */
    public function redis_token_key( $inter_id, $gift_id )
    {
        $base= 'SOMA_GIFT';
        return "{$base}:RECEIVE_{$inter_id}_{$gift_id}";
	}

	/**
	 * 按照数量数组生成对应元素放入redis（用于初始化/redis故障后的token重建）
	 * @param String $qty_array   礼物数量数组  array(2,3,3,3,4) 元素是礼物数量
	 * @param String $gift_id   赠送编号
	 * @return boolean
	 */
    public function generate_redis_token( $qty_array, $inter_id, $gift_id)
    {
        $key= $this->redis_token_key( $inter_id, $gift_id);
        $cache= $this->_load_cache();
        //$cache->redis->select_db(self::REDIS_DB);  //由redis.php 配置文件自动识别哪个库
        $redis= $cache->redis->redis_instance();
        $count= count($qty_array);
    
        if($count>0 ){
            $this->load->helper('soma/math');
            $token_array= gen_unique_rand(100000, 999999, $count);
            
            foreach ($qty_array as $k=> $v){
                if( $redis->lSize($key)>= $count ) break;
                else {
                    $array= array(
                        'gift_id'=> $gift_id,
                        'qty' => $v,
                        'token' => $token_array[$k],
                    );
                    $sign= $this->get_redis_sign($array, array('sign') );
                    $array['sign']= $sign;
                    $redis->lPush($key, json_encode($array) );
                }
            }
        }
        
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
            //生产环境24小时退回
            $expired_time= self::EXPIRED_HOURS * 3600;
        } else {
            //其他环境8分钟退回
            $expired_time= self::EXPIRED_HOURS * 20;
        }
        $redis->expireAt($key, time()+ $expired_time );
        return TRUE;
    }

    /**
     * 获取赠送礼物配额（带签名校验）
     * @param String $inter_id
     * @param String $gift_id
     * @return boolean|array
     */
    public function get_redis_token( $inter_id, $gift_id)
    {
        $key= $this->redis_token_key( $inter_id, $gift_id);
        $cache= $this->_load_cache();
        //$cache->redis->select_db(self::REDIS_DB);  //由redis.php 配置文件自动识别哪个库
        $redis= $cache->redis->redis_instance();
    
        $string= $redis->rPop($key);

        if( !$string ) return FALSE;
        
        $array= (array) json_decode($string);
        if( $array['sign']== $this->get_redis_sign($array, array('sign') ) ){
            return $array;
            
        } else {
            Soma_base::inst()->show_exception('Gift receiver token sign error.', TRUE);
            return FALSE;
        }
    }
    
    /**
     * 群发接受配额分配到redis
     * Usage:
     *   $model->rule= new Gift_order_attr_rule(1, 1);
     *   $model->set_redis_list( $inter_id );
     */
    public function set_redis_list($inter_id, $gift_id)
    {
        $receiver_list= $this->get_receiver_list($inter_id, $gift_id);
        if( $this->rule ){
            $total_give= $this->rule->total_give;
        } else {
            $total_give= 0;
        }
        if( $total_give && $total_give > count($receiver_list) ){
            //$plus_give= ($total_give- count($receiver_list)*$this->rule->per_give)/$this->rule->per_give;
            $plus_give= $this->rule->count_give - count($receiver_list);
            $qty_array= array();
            
            for($i=0; $i< $plus_give; $i++){
                $qty_array[]= $this->rule->per_give;
            }
            return $this->generate_redis_token( $qty_array, $inter_id, $gift_id);
            
        } else {
            //全部赠送完毕
            return FALSE;
        }
    }

    /**
     * 礼物退回清除redis对应的list
     * bug 如果没有删除对应的list 会出现礼物退回后还可以领取礼物(相当于凭空多出了领取人的资产)
     * 领取页应该也要做一个超时判断，超时不能领取
     * @author luguihong
     */
    public function del_redis_list($inter_id, $gift_id)
    {
        $key= $this->redis_token_key( $inter_id, $gift_id);
        $cache= $this->_load_cache();
        //$cache->redis->select_db(self::REDIS_DB);  //由redis.php 配置文件自动识别哪个库
        $redis= $cache->redis->redis_instance();
    
        $result = $redis->delete($key);
        if( $result ){
            return TRUE;
            
        } else {
            Soma_base::inst()->show_exception('Gift return delete redis list error.', TRUE);
            return FALSE;
        }
    }
    
    /**
     * 获取尚未发送出去的礼物订单
     *
     * @param      int    $limit  取出数量
     *
     * @return     array  The unsent order.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getUnsentOrder($limit = 20)
    {
        $limit_time = date('Y-m-d H:i:s', time() - self::UNSENT_TIME_LIMIT);
        $order_idx_table = $this->order_idx_table_name();
        
        $orders = $this->soma_db_conn
            ->where('create_time <', $limit_time)
            ->where_in('status', self::STATUS_PENDING )
            ->order_by('gift_id asc')->limit($limit)
            ->get($order_idx_table)->result_array();

        return $orders;
    }
    
}