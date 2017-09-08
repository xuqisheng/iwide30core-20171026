<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH. 'models'. DS. 'soma'. DS. 'Activity_model.php');
class Activity_killsec_model extends Activity_model {

    const INSTANCE_STATUS_PREVIEW= 1;
    const INSTANCE_STATUS_GOING  = 2;
    const INSTANCE_STATUS_FINISH = 3;
    
    const USER_STATUS_JOIN  = 1;
    const USER_STATUS_ORDER = 2;
    const USER_STATUS_PAYMENT= 3;

    const ORDER_WAIT_TIME= 300;  //秒
    const PAYMENT_WAIT_TIME= 300;  //秒

    const PREVIEW_TIME= 600;  //活动提前多久生成实例，同时作为活动手动结束的时间限制
    const END_PREVIEW_TIME = 900;  //活动结束后多久才能重新编辑
    const PRESTART_TIME= 1800; // 提前半个小时释放token

    // 3分钟内没产生实例或没生成Redis信息即触发警报
    const WARNING_TIME = 180;
    
    //const REDIS_DB = 2;

    const PRICE_PERCENT_LIMIT = 0;    //低于此价格倍数不允许下单
    const PRICE_PERCENT_NOTICE = 0.5;    //低于此价格倍数发出报警

	/**
	 * 固定日期
	 */
    const SCHEDULE_TYPE_FIX = 1;

	/**
	 * 周期循环
	 */
    const SCHEDULE_TYPE_CYC = 2;

    const SYNC_STATUS_TRUE = 1;
    const SYNC_STATUS_FALSE = 2;

	/**
	 * 秒杀方式 按照名额
	 */
	const TYPE_PLACES = 1;
	/**
	 * 秒杀方式 按照库存
	 */
	const TYPE_STOCK = 2;


	/**
	 * 秒杀订阅推送
	 * 推送次数>3，计划任务则不推送订阅信息
	 */
	const SEND_COUNT = 3;

    public function get_instance_status()
    {
        return array(
            self::INSTANCE_STATUS_PREVIEW => '准备开始',
            self::INSTANCE_STATUS_GOING  => '进行中',
            self::INSTANCE_STATUS_FINISH => '活动完成'
        );
    }

    public function get_user_status()
    {
        return array(
            self::USER_STATUS_JOIN   => '占位',
            self::USER_STATUS_ORDER  => '下单',
            self::USER_STATUS_PAYMENT=> '支付'
        );
    }

    public function get_schedule_type()
    {
        return array(
            self::SCHEDULE_TYPE_FIX => '按日期',
            self::SCHEDULE_TYPE_CYC => '按星期',
        );
    }

    public function get_sync_status_label()
    {
        return array(
                self::SYNC_STATUS_TRUE=>'是',
                self::SYNC_STATUS_FALSE=>'否',
            );
    }
    
    public function instance_exist_status()
    {
        return array( self::INSTANCE_STATUS_PREVIEW, self::INSTANCE_STATUS_GOING );
    }
    
	public function get_resource_name()
	{
		return '秒杀活动';
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
        return $this->_shard_table('soma_activity_killsec', $inter_id);
    }
    public function table_name_r($inter_id=NULL)
    {
		return $this->_shard_table_r('soma_activity_killsec', $inter_id);
    }

    public function instance_table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_activity_killsec_instance', $inter_id);
    }
    public function instance_table_name_r($inter_id=NULL)
    {
        return $this->_shard_table_r('soma_activity_killsec_instance', $inter_id);
    }

    public function user_table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_activity_killsec_user', $inter_id);
    }

    public function notice_table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_activity_killsec_notice', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'act_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'act_id'=> '活动编号',
            'inter_id'=> '公众号ID',
            'hotel_id'=> '酒店',
            'banner_url'=> '封面图',
            'act_type'=> '活动类型',
            'act_name'=> '活动名称',
            'product_id'=> '商品ID',
            'product_name'=> '商品名',
            'keyword'=> '关键词描述',
            'is_stock'=> '显示名额',
            'is_subscribe'=> '能否订阅',
            'is_sync_center' => '已同步到中心平台',
            'killsec_price'=> '秒杀价',
            'killsec_count'=> '秒杀库存',
            'killsec_permax'=> '每人限购',
            'schedule_type'=> '活动模式',
            'schedule'=> '星期',
            'start_time'=> '活动展示时间',
            'killsec_time'=> '秒杀启动时间',
            'end_time'=> '秒杀关闭时间',
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
            'act_id',
            'inter_id',
            //'hotel_id',
            //'act_type',
            //'act_name',
            //'banner_url',
            //'product_id',
            'product_name',
            'killsec_price',
            'killsec_count',
            'killsec_permax',
            'killsec_time',
            //'keyword',
            'is_stock',
            'is_subscribe',
            'is_sync_center',
            'start_time',
            'end_time',
            //'create_time',
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
	    
	    //获取该公众号下的套票商品列表
	    $this->load->model( 'soma/product_package_model', 'product_package' );
	    
	    //测试使用
	    $cat_id = '';
        $temp_id= $this->session->get_temp_inter_id();
        if($temp_id) $inter_id= $temp_id;
        else $inter_id= $this->session->get_admin_inter_id();
	    // $inter_id = $this->session->get_admin_inter_id();  //'a429262687';//
	    
	    $products_arr = $this->product_package->get_product_package_list( $cat_id, $inter_id , NULL, 1000);
	    //把套票商品转成array(product_id=>product_name)数组
	    $products = array();
	    foreach( $products_arr as $k => $v ){
	        $products[$v['product_id']] = $v['name'];
	    }
	    
	    return array(
            'act_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $hotels,
            ),
            'act_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                'form_default'=> parent::ACT_TYPE_KILLSEC,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> parent::act_type_status(),
            ),
            'act_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '12%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'banner_url' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_cat_img|100|',
                'type'=>'logo',	//textarea|text|combobox|number|email|url|price
            ),
            'product_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $products,
            ),
            'product_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '12%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'killsec_price' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price', //textarea|text|combobox|number|email|url|price
            ),
            'killsec_count' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' required step="1" min="1" max="100000" ',
                'input_unit'=> '人/次',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number',	//textarea|text|combobox|number|email|url|price
                'form_tips'=> '允许多少人参与该次活动',
            ),
            'killsec_permax' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' required step="1" min="1" max="100" ',
                'input_unit'=> '份',
                'type'=>'number',	//textarea|text|combobox|number|email|url|price
                'form_tips'=> '每人最多能买几份',
            ),
            'killsec_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'js_config'=> ' startDate:"'. date('Y-m-d' ) .'",endDate:"'. date('Y-m-d', strtotime('30 days') ) .'",',
                //'form_default'=> '0',
                'form_ui'=> ' required ',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'time',	//textarea|text|combobox|number|email|url|price
            ),
            'schedule_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> '',
            ),
            'schedule' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_default'=> '0',
                'form_hide'=> TRUE,
                'type'=>'text',
            ),
            'keyword' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'is_stock' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_default'=> 1,
                //'form_hide'=> TRUE,
                //'form_ui'=> ' disabled ',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> self::get_status_yes_label(),
            ),
            'is_subscribe' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_default'=> 1,
                //'form_hide'=> TRUE,
                //'form_ui'=> ' disabled ',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> self::get_status_can_label(),
            ),
            'is_sync_center' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_default'=> 2,
                //'form_hide'=> TRUE,
                'form_ui'=> ' disabled ',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> self::get_sync_status_label(),
            ),
            'start_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' required ',
                'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime', //textarea|text|combobox|number|email|url|price
            ),
            'end_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> date('Y-m-d H:i:s'),
                'form_tips'=> '结束时间不能大于开始时间＋7天',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime', //textarea|text|combobox|number|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime', //textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $base_util::get_status_options(),
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'act_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	
	//提取当前可用的活动记录
	public function get_aviliable_activity( $filter=array(), $orderby=' act_id desc' )
	{
        $db = $this->_shard_db_r('iwide_soma_r');
	    $table= $this->table_name_r();
	    foreach ($filter as $k=>$v) {
	        if( is_array($v) ){
                $db->where_in($k, $v);
	        } else {
                $db->where($k, $v);
	        }
	    }
        $db->where('status', self::STATUS_TRUE );
	    $result= $db->order_by($orderby)->get($table)->result_array();
	    $datetime= date('Y-m-d H:i:s');
	    foreach ($result as $k=> $v){
	       if( $v['start_time'] && $v['start_time']> $datetime ) unset($result[$k]);
	       elseif( $v['end_time'] && $v['end_time']< $datetime ) unset($result[$k]);
	    }
	    return $result;
	}

	//提取即将开始的活动记录
	public function get_preview_activity( $filter=array(), $orderby=' act_id desc' )
	{
	    $preview_time= self::PREVIEW_TIME;  //提前 1800秒开始
	    $table= $this->table_name();
	    foreach ($filter as $k=>$v) {
	        if( is_array($v) ){
	            $this->_db()->where_in($k, $v);
	        } else {
	            $this->_db()->where($k, $v);
	        }
	    }
		$this->_db()->where('status', self::STATUS_TRUE );
		$this->_db()->where('killsec_time >', date('Y-m-d H:i:s' ) );
		$this->_db()->where('killsec_time <', date('Y-m-d H:i:s', time()+ $preview_time ) );
		$this->_db()->where('killsec_count >', 0 );
	    $result= $this->_db()->order_by($orderby)->get($table)->result_array();
	    //echo $this->_db()->last_query();die;
	    return $result;
	}

	//清理过期的实例
	public function close_timeout_instance( $inter_id, $activity=NULL )
	{
	    $date= date('Y-m-d H:i:s');
	    $status= self::INSTANCE_STATUS_FINISH;
	    $table= $this->_db()->dbprefix($this->instance_table_name($inter_id));
	    if( isset($activity['act_id']) && $activity['act_id'] )
	        $sql= "update `{$table}` set `status`='{$status}' WHERE `close_time`< '{$date}' && `act_id`='{$activity['act_id']}'";
	    else 
	        $sql= "update `{$table}` set `status`='{$status}' WHERE `close_time`< '{$date}'";
	    return $this->_shard_db($inter_id)->query($sql);
	}
	
	//带判断将活动放入秒杀实例表
	public function insert_new_instance($inter_id, $activity )
	{
	    $table= $this->instance_table_name($inter_id);
	    $act_id= $activity['act_id'];
	    $exist= $this->_shard_db($inter_id)->where_in('status', $this->instance_exist_status() )
	       ->where('act_id', $act_id)->get($table)->result_array();
	    $result= TRUE;
	    if( count($exist)==0 ){
	        $data= array();
	        $data['act_id']= $activity['act_id'];
	        $data['inter_id']= $activity['inter_id'];
	        $data['hotel_id']= $activity['hotel_id'];
	        $data['start_time']= $activity['killsec_time'];
	        $data['close_time']= $activity['end_time'];
	        $data['product_id']= $activity['product_id'];
	        $data['schedule_type']= $activity['schedule_type'];
	        $data['schedule']= $activity['schedule'];
	        $data['killsec_price']= $activity['killsec_price'];
	        $data['killsec_count']= $activity['killsec_count']>10000? 10000: $activity['killsec_count'];
	        $data['killsec_permax']= $activity['killsec_permax'];
	        $data['join_count']= 0;
	        $data['create_time']= date('Y-m-d H:i:s');
	        $data['status']= self::INSTANCE_STATUS_PREVIEW;
	        $result= $this->_shard_db($inter_id)->insert($table, $data);
	        
	    } else {
	        $this->_shard_db($inter_id)->where_in('status', $this->instance_exist_status() )
	           ->where('act_id', $act_id)->update($table, array('close_time'=> $activity['end_time'] ));
	    }
	    return $result;
	}

	public function get_aviliable_instance( $filter=array(), $orderby=' instance_id desc' )
	{
        // $table= $this->instance_table_name();
	    $table= $this->instance_table_name_r();
        $db = $this->_shard_db_r('iwide_soma_r');
	    foreach ($filter as $k=>$v) {
	        if( is_array($v) ){
	            $db->where_in($k, $v);
	        } else {
	            $db->where($k, $v);
	        }
	    }
	    //如不传入status默认取几个有效状态
	    if( !isset($filter['status']) ) 
	        $db->where_in('status', $this->instance_exist_status() );
	    $result= $db->order_by($orderby)->get($table)->result_array();
	    return $result;
	}
	
	/**
	 * 生成若干个redis  totken
	 * @return boolean
	 */
	public function generate_redis_token($count, $total, $instance_id)
	{
	    $key= $this->redis_token_key($instance_id);
        $cache= $this->_load_cache();
        //$cache->redis->select_db(self::REDIS_DB);  //由redis.php 配置文件自动识别哪个库
        $redis= $cache->redis->redis_instance();
        
        if($count>0 ){
            $this->load->helper('soma/math');
            $token_array= gen_unique_rand(100000, 999999, $count);
            
            $add_log= array();
            foreach ($token_array as $k=>$v){
                if( $redis->lSize($key)>= $total ) {
                    break;
                    
                } else {
                    $add_log[]= $redis->lPush($key, $v );
                }
            }
            $this->_write_log( "generate_redis_result: ". json_encode($token_array)
                . ', count: '. $count. ', push result: '. json_encode($add_log) );
        }
        $redis->expireAt($key, time()+ 3600);
        return TRUE;
	}
	/**
	 * @param String $instance_id
	 * @param string $type  'cache'|'click'|'order'|'black'
	 * @return string
	 */
	public function redis_token_key( $instance_id, $type=NULL)
	{
        $base= 'SOMA';
	    if($type==NULL)
	        return "{$base}:KILLSEC_TOKEN_{$instance_id}";
	    else 
	        return "{$base}:KILLSEC_TOKEN_{$instance_id}_{$type}";
	}
	/**
	 * 带cache，拦截获取token
	 * @param String $instance_id
	 * @param String $openid
	 * @return String
	 */
	public function get_redis_token($instance_id, $openid)
	{
	    $max_request_time= 30;
	    $key= $this->redis_token_key($instance_id);
	    $w_key= $this->redis_token_key($instance_id, 'white');
	    $cache_key= $this->redis_token_key($instance_id, 'cache');
	    $click_key= $this->redis_token_key($instance_id, 'click');
	    
	    $cache= $this->_load_cache();
	    //$cache->redis->select_db(self::REDIS_DB);  //由redis.php 配置文件自动识别哪个库
	    $redis= $cache->redis->redis_instance();

	    $redis->hIncrBy($click_key, $openid, 1);
	    $click_max= $redis->hGet($click_key, $openid );
	    if($click_max> $max_request_time){
	        Soma_base::inst()->show_exception('点击过于频繁，请稍后再试。');
	    }
	    
	    if( $redis->hExists($w_key, $openid) ){
	        $cache_data= (array) json_decode( $redis->hGet($w_key, $openid) );
	        $cache_data['create_at']= date('Y-m-d H:i:s');
            $redis->hSet($cache_key, $openid, json_encode($cache_data) );
            $redis->expireAt($cache_key, time()+ 300 );
            $redis->hDel($w_key, $openid);
	        return $cache_data['token'];
	        
	    } else if( $redis->hExists($cache_key, $openid) ){
	        //获取cache中命中的token
	        $array= (array) json_decode( $redis->hGet($cache_key, $openid) );
            if(strtotime($array['create_at']) + 300 > time())
            {
	            return $array;
            }
            else
            {
                // 删除过期资格
                $redis->hDel($cache_key, $openid);
            }
	        
	    } else {
            // 不做处理，把原来从redis获取资格的方法放到最外面，
            // 统一从cache命中过期的资格时与没有cache时的资格获取方式
        }

        $token= $redis->rPop($key);
        //var_dump($token);die;
        if($token){
            //对于成功获取的openid进行cache记录
            $cache_data= array('create_at'=>date('Y-m-d H:i:s'), 'token'=> $token);
            $redis->hSet($cache_key, $openid, json_encode($cache_data) );
            $redis->expireAt($cache_key, time()+ 300 );
        }
        return $token;
	}

	public function wx_message_notice($inter_id, $instance)
	{
	    //测试发送订阅模板消息，等功能稳定之后再发
	    $this->load->model('soma/Activity_killsec_model' );
	    $activity= $this->Activity_killsec_model->find(array('act_id'=>$instance['act_id']));
	    if($activity && isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
	        if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
	            $project_name= '月饼说';
	        } else {
	            $project_name= '社交商城';
	        }
	        $this->load->model('wx/publics_model');
	        $public= $this->publics_model->get_public_by_id($inter_id);
	        
	        $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
	        $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
	        $type = $MessageWxtempTemplateModel::TEMPLATE_CONSUMER_SUCCESS;
	        $openid= array();
	        $openid[1] = 'o9Vbtw5bgFCel1nuSugUG4uVVZ3k';
	        $openid[2] = 'o9Vbtw8ia-umIeOqZxzX4P2X7uLM';
	        $openid[3] = 'o9Vbtw1EwFDED1rBFv-Q1dgBo5ek';
	        $inter_id= 'a450089706';
	        $business= 'package';
	        $message= "{$project_name}【{$public['name']}】秒杀将于【{$instance['start_time']}】开始，"
	            . "商品【{$activity['product_id']}:{$activity['product_name']}】价格【￥{$instance['killsec_price']}】名额【{$instance['killsec_count']}个】"
	            . "详情 :{$public['domain']}/soma/killsec/get_killsec_token_ajax?id={$public['inter_id']}&act_id={$activity['act_id']}";
	        foreach ($openid as $k=> $v){
	            if( $k>1 && $instance['killsec_count']>50 ) continue;
	            if( $k>2 && $instance['killsec_count']>10 ) continue;
	            $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success( $type, '', $v, $inter_id, $business, $message);
	        }
	    }
	}
	
	/**
	 * 清理过期不下单的user记录
	 * @return boolean
	 */
	public function instance_processing($inter_id, $instance)
	{
	    $instance_table= $this->instance_table_name($inter_id);
	    $user_table= $this->user_table_name($inter_id);
	    $instance_id= $instance['instance_id'];
	    $total= $instance['killsec_count'];
	    //print_r($instance);die;

	    $log= array('instance_id'=> $instance_id );
	    
	    if( $instance['status']==self::INSTANCE_STATUS_PREVIEW ){
	        if( strtotime($instance['start_time'])- self::PRESTART_TIME <= time()){ //提前半个小时释放token
	            //启动秒杀使用，分配token
	            $set_result= $this->_shard_db($inter_id)->where('inter_id', $inter_id)
    	            ->where('instance_id', $instance_id)
    	            ->update($instance_table, array('status'=> self::INSTANCE_STATUS_GOING ));
    	             
	            $cache= $this->_load_cache();
	            $redis= $cache->redis->redis_instance();
	            $w_key= $this->redis_token_key( $instance_id, 'white');
	            
	            //redis放入token
	            $count= $instance['killsec_count']- $redis->hLen($w_key);
	            if($set_result) $this->generate_redis_token($count, $total, $instance_id);
	        
	        } else {
	            //生成实例，未开始秒杀之前
	            $preview_time= 119;
	            if( ( time()- strtotime($instance['create_time']) ) < $preview_time ){
	                $this->wx_message_notice($inter_id, $instance);
	            }
	        }

	    } elseif( $instance['status']==self::INSTANCE_STATUS_GOING ){
	        //进行user表扫描
	        $deadline= time()- self::ORDER_WAIT_TIME;
	        $user= $this->_shard_db_r('iwide_soma_r')->where('inter_id', $inter_id)
    	        ->where('instance_id', $instance_id)
    	        ->get($user_table )->result_array();
	        $delete_ids= array();
	        foreach ($user as $k=>$v){
	            if( empty($v['order_time']) && strtotime($v['join_time'])<$deadline ){
	                $delete_ids[]= $v['user_id'];
	            }
	        }

	        $log['join_total']= count($user);
	        $log['delete_ids']= $delete_ids;
	        
	        if( count($delete_ids)>0 ){
	            //删除若干个名额
	            $this->_shard_db($inter_id)->where('inter_id', $inter_id)
	               ->where_in('user_id', $delete_ids)->delete($user_table );
	             
	            //补充若干个token
	            $this->generate_redis_token( count($delete_ids), $total, $instance_id);
	            $log['add_token']= count($delete_ids);
	            $log['total']= $total;
	            
	            //刷新join_count, status, finish_time
	            $join_count= count($user)- count($delete_ids);
	             
	            //更新目前参加的人数
	            $data['join_count']= $join_count;
	
	        } else {
	            //刷新数组的有效期，防止活动没结束，key已经丢失
	            $this->generate_redis_token( 0, $total, $instance_id);
	             
	            //更新目前参加的人数
	            $data['join_count']= count($user);
	        }
	        
	        if( count($user)== $total ) {
	            $is_all_pay= TRUE;
	            foreach ($user as $k=>$v){
	                //扫描是否所有的人都支付完毕？
	                if($v['status']!= self::USER_STATUS_PAYMENT) $is_all_pay=FALSE;
	            }
	            if($is_all_pay){
	                $data['join_count']= $total;
	                $data['finish_time']= date('Y-m-d H:i:s');
	                $data['status']= self::INSTANCE_STATUS_FINISH;
	                 
	                $this->_shard_db($inter_id)->where('inter_id', $inter_id)
    	                ->where('instance_id', $instance_id)
    	                ->update($instance_table, $data );
	            } else {
	                //等候处理。
	            }
	            
	        } else {
	            //到期关闭实例
	            if( $instance['close_time']<= date('Y-m-d H:i:s') ){
	                $data['finish_time']= date('Y-m-d H:i:s');
	                $data['status']= self::INSTANCE_STATUS_FINISH;
	            }
	            
	            //刷新instance表相关数据
	            $this->_shard_db($inter_id)->where('inter_id', $inter_id)
    	            ->where('instance_id', $instance_id)
    	            ->update($instance_table, $data );
	        }
	        //$this->_write_log( json_encode($log) );

	        //按星期类活动，关闭实例的同时修正下一轮活动的开始/结束时间
	        if( isset($instance['schedule_type']) && isset($data['status']) && 
	            $instance['schedule_type']==self::SCHEDULE_TYPE_CYC && $data['status']== self::INSTANCE_STATUS_FINISH 
	        ){
	            $c_hours= round( ( strtotime($instance['close_time'])- strtotime($instance['start_time']) )/3600, 0 ); //求出上一次延续时间的小时数。
	            $upd_time= $this->update_last_week_date( explode(',', $instance['schedule']), $instance['start_time'], $c_hours );
	            
	            $table= $this->table_name($inter_id);
	            $this->_shard_db($inter_id)->where('inter_id', $inter_id)
    	            ->where('act_id', $instance['act_id'])
    	            ->update($table, $upd_time );
	        }
	        
	    } else {
	        //活动结束清理相关缓存数据
	        /**
	        $key= $this->redis_token_key($instance_id);
	        $cache_key= $this->redis_token_key($instance_id, 'cache');
	        $click_key= $this->redis_token_key($instance_id, 'click');
	         
	        $cache= $this->_load_cache();
	        //$cache->redis->select_db(self::REDIS_DB);  //由redis.php 配置文件自动识别哪个库
	        $redis= $cache->redis->redis_instance();
	
	        $redis->delete($key);
	        $redis->delete($cache_key);
	        $redis->delete($click_key);
	        */
        }
        return TRUE;
	}
	
	/**
	 * 把日期阿拉伯数组转换为最近的开始日期和结束日期
	 * @param Array $schedule_array
	 * @param String $run_time 执行秒杀时间，用于与当前时间比较
	 * @return Array
	 */
	public function update_last_week_date($schedule_array, $run_time, $hour=1)
	{
	    $run_time= substr($run_time, -8);
	    if(count($schedule_array)==0){
	        return FALSE;
	        
	    } else {
	        $date= '';
	        $tmp= array();
	        $today_w= date("w")==0? 7: date("w");
	        $this->load->helper('soma/time_calculate');
	        //$lt= ($run_time < date('H:i:s', time()-10))? TRUE: FALSE; //如果执行时间小于当前时间10分钟，则选择星期必须大于今天
	        foreach ($schedule_array as $v){
	            $tmp[]= last_week_date($v). ' '. $run_time;
	            $tmp[]= last_week_date($v, '+1'). ' '. $run_time;
	        }
	        sort($tmp);
            foreach ($tmp as $v){
                if( date('Y-m-d H:i:s', time()-60) < $v ){
                    $date= $v;
                    break;
                }
            }
	        $return_array= array();
	        $return_array['killsec_time']= $date;
	        $return_array['end_time']= date('Y-m-d H:i:s', strtotime($return_array['killsec_time'])+ $hour* 3600 );
            //print_r($return_array);die;
	        return $return_array;
	    }
	}
	
	/**
	* 清理过期不付款的user记录
	* @return boolean
	*/
	public function instance_payment_clean($inter_id, $instance)
	{
    	$instance_table= $this->instance_table_name($inter_id);
    	$user_table= $this->user_table_name($inter_id);
    	$instance_id= $instance['instance_id'];
    	$total= $instance['killsec_count'];
    	
    	//进行user表扫描
    	$deadline= time()- self::PAYMENT_WAIT_TIME;
    	$user= $this->_shard_db_r('iwide_soma_r')->where('inter_id', $inter_id)
    	    ->where('instance_id', $instance_id)
    	    ->get($user_table )->result_array();
	    $delete_ids= array();
	    
	    $this->load->model('soma/Sales_refund_model');
	    foreach ($user as $k=>$v){
    	    if( !empty($v['order_time']) && empty($v['pay_time']) && strtotime($v['order_time'])<$deadline ){
    	        $delete_ids[]= $v['user_id'];
    	         
    	        //调用微信接口关闭订单，需要在 controller 初始化 shard_id
    	        $this->Sales_refund_model->wx_order_close($v['order_id'], $v['business'], $v['inter_id']);
        	}
        }
	 
	    if( count($delete_ids)>0 ){
        	//删除若干个名额
        	$this->_shard_db($inter_id)->where('inter_id', $inter_id)
    	        ->where_in('user_id', $delete_ids)->delete($user_table );
    	     
    	    //补充若干个token
    	    $this->generate_redis_token( count($delete_ids), $total, $instance_id);
    	}
    	return TRUE;
	}

	/**
	 * 首次获取token时保存user表数据
	 * @param String $inter_id
	 * @param Array $data
	 */
	public function save_instance_user($inter_id, $data)
	{
	    $instance_table= $this->instance_table_name($inter_id);
	    $table= $this->user_table_name($inter_id);
	    $result= $this->_shard_db($inter_id)->insert($table, $data);
	    
	    //if($result) {
	    //    $result= $this->_shard_db($inter_id)->where('instance_id', $data['instance_id'])
	    //        ->update($instance_table, array('join_count', '`join_count`+1') );
	    //}
	    return $result;
	}

    /**
     * @param $productIds
     * @param null $inter_id
     * @return array
     */
    public function killsec_list_by_productIds($productIds, $inter_id = NULL)
    {
        if(!is_array($productIds) || empty($productIds)) 
            return array();
//        $preview_time= self::PREVIEW_TIME;  //提前 1800秒开始
        // $table= $this->table_name($inter_id);
        $table= $this->table_name_r($inter_id);
        $db = $this->_shard_db_r('iwide_soma_r');
        if(!empty($inter_id)){
            $db->where('inter_id ', $inter_id );
        }
        $db->where('start_time <=', date('Y-m-d H:i:s' ) );
        $db->where('end_time >', date('Y-m-d H:i:s' ) );
        $db->where('status', self::STATUS_TRUE );
        $result= $db
            ->where_in('product_id',$productIds)
            ->get($table)
            ->result_array();
        return $result;
    }

    /**
     * @param $productId
     * @param null $inter_id
     * @return array
     */
    public function killsec_by_product_id($productId, $inter_id)
    {
        $productId = intval($productId);
        if(empty($productId)){
            return array();
        }

        $table= $this->table_name_r($inter_id);
        $result = $this->_shard_db_r('iwide_soma_r')
            ->where('start_time <=', date('Y-m-d H:i:s' ) )
            ->where('end_time >', date('Y-m-d H:i:s' ) )
            ->where('status', self::STATUS_TRUE )
            ->where('product_id', $productId)
            ->where('inter_id',$inter_id)
            ->get($table)
            ->row_array();
//        print_r($result);exit;
        return $result;
    }
    
    /**
     * 下单时查找有没有符合条件的user记录（前提条件是killsec_user_payment_cleaning正常运作）
     * @param String $inter_id
     * @param String $openid
     * @param String $instance_id
     */
    public function find_user_by_openid($inter_id, $openid, $instance_id )
    {
	    $user_table= $this->user_table_name($inter_id);
        // $full_tb = $this->_shard_db_r('iwide_soma_r')->dbprefix($user_table);
        // $five_min_ago = date('Y-m-d H:i:s', time()-300);
        // $sql = "select * from `{$full_tb}` where `inter_id` = '{$inter_id}'" 
        //      . " and `openid` = '{$openid}' and `instance_id` = {$instance_id}" 
        //      . " and (`join_time` > '{$five_min_ago}'" 
        //      . " or (`order_time` is not null and `order_time` > '{$five_min_ago}'))"
        //      . " order by `join_time` desc limit 1";
        // echo $sql;exit;
        // return $this->_shard_db_r('iwide_soma_r')->query($sql)->row_array();
        // 存在新旧资格记录并存的情况，选最新的资格
        return $this->_shard_db_r('iwide_soma_r')
            ->where('inter_id', $inter_id )
            ->where('instance_id', $instance_id )
            ->where('openid', $openid )
            ->order_by('join_time', 'desc')
            ->limit(1)
            ->get($user_table)->row_array();
    }

    public function update_user_by_filter($inter_id, $filter, $info )
    {
	    $user_table= $this->user_table_name($inter_id);
        foreach ($filter as $k=>$v){
            if(is_array($v)){
                $this->_shard_db($inter_id)->where_in($k, $v );
            } else {
                $this->_shard_db($inter_id)->where($k, $v );
            }
        }
        // 存在新旧资格记录并存的情况，选最新的资格进行更新
        $result= $this->_shard_db($inter_id)->where('inter_id', $inter_id )
            ->order_by('join_time', 'desc')->limit(1)->update($user_table, $info );
        //记录下单和支付时候的状态改写
        $this->_write_log("Update instance user table where payment return.\n". 
            $this->_shard_db($inter_id)->last_query() );
        return $result;
    }

    public function get_instance_by_order_id($inter_id, $order_id)
    {
        $user_table= $this->user_table_name($inter_id);
        return $this->_shard_db_r('iwide_soma_r')->where('inter_id', $inter_id )
            ->where('order_id', $order_id)->get($user_table)->row_array();
    }
    
    /**
     * 订单支付后清理对应准入标识，标记已经购买记录
     * @param unknown $order
     */
    public function clean_cache_after_payment($inter_id, $openid, $instance_id, $order_id=NULL)
    {
        $cache= $this->_load_cache();
        $redis= $cache->redis->redis_instance();
        
        $cache_key= $this->redis_token_key( $instance_id, 'cache');
        $result= $redis->hDel($cache_key, $openid);
        
        $cache_data= array('create_at'=>date('Y-m-d H:i:s'), 'order_id'=> $order_id);
        $order_key= $this->redis_token_key( $instance_id, 'order');
        $result= $redis->hSet($order_key, $openid, json_encode($cache_data) );
        return $result;
    }

    /**
     * 查询已经购买成功的redis记录
     */
    public function get_redis_order_user($instance_id, $openid)
    {
        $cache= $this->_load_cache();
        $redis= $cache->redis->redis_instance();
        $order_key= $this->redis_token_key( $instance_id, 'order');
        return $redis->hGet($order_key, $openid );
    }

    public function set_redis_white_user($instance_id, $openid, $del=FALSE)
    {
        $cache= $this->_load_cache();
        $redis= $cache->redis->redis_instance();
        $key= $this->redis_token_key( $instance_id );
        if( !$redis->lSize($key) ){
            $w_key= $this->redis_token_key( $instance_id, 'white');
            if($del){
                $redis->hDel($w_key, $openid );
                
            } else {
                $val= json_encode( array('create_at'=>date('Y-m-d H:i:s'), 'token'=> 123456) );
                $redis->hSet($w_key, $openid, $val );
                $redis->expireAt($w_key, time()+ 3600);
            }
        }
        return '';
    }
    public function get_redis_black_user($instance_id, $openid)
    {
        $cache= $this->_load_cache();
        $redis= $cache->redis->redis_instance();
        $order_key= $this->redis_token_key( $instance_id, 'black');
        return $redis->hGet($order_key, $openid );
    }
    
    public function _write_log( $content )
    {
        $path= APPPATH. 'logs'. DS. 'soma'. DS. 'killsec'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $file= $path. date('Y-m-d_H'). '.txt';
        $this->write_log($content, $file);
    }
    
    public function get_user_by_filter($inter_id, $filter )
    {
        $db = $this->_shard_db_r('iwide_soma_r');
        $user_table= $this->user_table_name($inter_id);
        foreach ($filter as $k=>$v){
            if(is_array($v)){
                $db->where_in($k, $v );
            } else {
                $db->where($k, $v );
            }
        }
        return $db->order_by('status desc')
            ->where('inter_id', $inter_id )
            ->get($user_table)->result_array();
    }
    
    /**
     * 剔除非法参与用户记录
     * @param unknown $inter_id
     * @param unknown $filter
     */
    public function kick_user_by_filter($inter_id, $user_id )
    {
        $user_table= $this->user_table_name($inter_id);
        $result= $this->_shard_db_r('iwide_soma_r')->where('user_id', $user_id )
            ->where('inter_id', $inter_id )->get($user_table)->row_array();
        
        if( $result ) {
            $instance_id= $result['instance_id'];
            $result= $this->_shard_db($inter_id)->where('user_id', $user_id )
                ->where('inter_id', $inter_id )->delete($user_table);
            
    	    $cache= $this->_load_cache();
    	    $redis= $cache->redis->redis_instance();
    	    $cache_key= $this->redis_token_key($instance_id, 'cache');
            $redis->hDel($cache_key, $openid);
        }
        return $result;
    }

    public function get_join_status_by_instance($instance_id )
    {
        $k1= $this->redis_token_key($instance_id);
        $k2= $this->redis_token_key($instance_id, 'cache');
        $k3= $this->redis_token_key($instance_id, 'click');
        $cache= $this->_load_cache();
        $redis= $cache->redis->redis_instance();
        $cache_list= (array) $redis->hGetAll($k2);
        $click_list= (array) $redis->hGetAll($k3);
        arsort($cache_list);
        arsort($click_list);
        
        $sql= "select p.* from iwide_soma_activity_killsec_instance as k left join iwide_soma_catalog_product_package as p ".
            "on p.product_id=k.product_id where k.instance_id='{$instance_id}'";
        $product= $this->_db($this->db_soma)->query($sql)->row_array();
        return array(
            'token'=> $redis->lSize($k1),
            'cache'=> $cache_list,
            'click'=> $click_list,
            'product'=> $product,
        );
    }

    public function save_waiting_notice_list($inter_id, $data )
    {
        if( isset($data['openid']) && isset($data['act_id']) ){
            $table = $this->notice_table_name($inter_id);
            $where= array('openid'=>$data['openid'], 'act_id'=> $data['act_id']);
            $result= $this->_shard_db_r('iwide_soma_r')->get_where( $table, $where )->result_array();
            if( count($result)==0 ){
                $data['status']= Soma_base::STATUS_TRUE;
                $data['create_time']= date('Y-m-d H:i:s');
                $result= $this->_shard_db( $inter_id )->insert( $table, $data );
    
            } else {
                $data['status']= Soma_base::STATUS_TRUE;
                $data['notice_time']= NULL;
                $result= $this->_shard_db( $inter_id )->where( 'notice_id', $result[0]['notice_id'] )->update( $table, $data );
            }
            //echo $this->_shard_db($inter_id)->last_query();
            return $result;
        } else
            return FALSE;
    }
    public function get_waiting_notice_list_byActIds( $actIds, $inter_id, $openid )
    {
        $filter = array();
        $filter['inter_id'] = $inter_id;
        $filter['openid'] = $openid;
        $filter['status'] = Soma_base::STATUS_TRUE;
    
        $killsec_table_name = $this->notice_table_name($inter_id);
        $sendlist = $this->_shard_db_r('iwide_soma_r')
            ->order_by('notice_id asc')
            ->where( $filter )
            ->where_in( 'act_id', $actIds )
            ->get( $killsec_table_name )
            ->result_array();
        return $sendlist;
    }

    /**
     * @param $limit
     * @return array|string
     * @author renshuai  <renshuai@mofly.cn>
     *
     * 秒杀开始提前十分钟和之后一小时的记录
     */
	public function get_waiting_notices($limit)
    {
        $notice_table_name = $this->soma_db_conn_read->dbprefix($this->notice_table_name());
        $current_time = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM $notice_table_name WHERE status = ? AND send_count <= ".self::SEND_COUNT." AND date_sub(killsec_time, interval 10 minute) < ?  AND ? < date_sub(killsec_time, interval -1 hour) limit $limit";
        $query = $this->soma_db_conn->query($sql, [
            Soma_base::STATUS_TRUE,
            $current_time,
            $current_time
        ]);
        return $query->result_array();
    }

    /**
     * @param string|array $inter_id
     * @param $limit
     * @return mixed
     *
     * 秒杀开始提前十分钟和之后一小时的记录
     */
    public function get_waiting_notice_list($inter_id, $limit )
    {
        $notice_table_name = $this->soma_db_conn_read->dbprefix($this->notice_table_name());
        $current_time = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM $notice_table_name WHERE status = ? AND inter_id = ? AND  date_sub(killsec_time, interval 10 minute) < ?  AND ? < date_sub(killsec_time, interval -1 hour) limit $limit";
        $query = $this->soma_db_conn_read->query($sql, [
            Soma_base::STATUS_TRUE,
            $inter_id,
            $current_time,
            $current_time
        ]);
        return $query->result_array();
    }

    /**
     * @param $noticeIds
     * @return bool|object
     * @author renshuai  <renshuai@mofly.cn>
     */
	public function update_waiting_notice_by_ids($noticeIds)
	{
		if(empty($noticeIds)) {
            return false;
        }

        $data = [
            'status' => Soma_base::STATUS_FALSE,
            'notice_time' => date('Y-m-d H:i:s')
        ];

        $killsec_table_name = $this->notice_table_name();
        $result= $this->_shard_db()
            ->where_in( 'notice_id', $noticeIds )
            ->update( $killsec_table_name, $data );

        return $result;
	}


	/**
	 * @param $noticeId
	 * @param $field
	 * @param int $increaseCount
	 * @author: liguanglong  <liguanglong@mofly.cn>
	 */
	public function update_waiting_notice_count_by_id($noticeId, $field, $increaseCount = 1)
	{
		$this->increase($noticeId, $field, $increaseCount);
	}

    public function set_waiting_notice_list($inter_id, $noticeIds )
    {
        //修改状态
        $result= '';
        if( count( $noticeIds ) > 0 ){
            $data = array();
            $data['status'] = Soma_base::STATUS_FALSE;
            $data['notice_time'] = date('Y-m-d H:i:s');
    
            $where = array();
            $where['inter_id'] = $inter_id;
    
            $killsec_table_name = $this->notice_table_name($inter_id);
            //修改资产细单模版消息状态
            $result= $this->_shard_db( $inter_id )
                ->where( $where )
                ->where_in( 'notice_id', $noticeIds )
                ->update( $killsec_table_name, $data );
        }
        return $result;
    }

    public function cleanup_waiting_notice_list( $days=7 )
    {
        $limit_date= date('Y-m-d H:i:s', strtotime("-{$days} days") );
        $killsec_table_name = $this->notice_table_name();
        $result= $this->_db()
            ->where( 'notice_time<', $limit_date )
            ->where( 'status', self::STATUS_FALSE )
            ->delete( $killsec_table_name );
        return $result;
    }
    
    /**
     * 添加一个活动(注：合并到父类里)
     * @author luguihong
     * @deprecated
    */
    public function activity_save( $post, $inter_id=NULL )
    {

        try {

            $this->_shard_db($inter_id)->trans_begin ();

            $product_id = isset( $post['product_id'] ) ? $post['product_id'] : '';
            $act_name = isset( $post['act_name'] ) ? $post['act_name'] : '';
            $status = isset( $post['status'] ) ? $post['status'] : '';

            //添加活动主单内容
            //添加活动类型和活动名称到activity_idx表
            $data = array();
            $data['act_name'] = $act_name;
            $data['act_type'] = parent::ACT_TYPE_KILLSEC;
            $data['status'] = $status;

            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_idx');
            $this->_shard_db( $inter_id )->insert( $table, $data );
            $act_id = $this->_shard_db( $inter_id )->insert_id();

            //添加活动价格表内容
            $data = array();
            $data['act_id'] = $act_id;
            $data['act_name'] = $act_name;
            $data['product_id'] = $product_id;
            $data['price'] = isset( $post['killsec_price'] ) ? $post['killsec_price'] : '';

            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_product_price');
            $this->_shard_db( $inter_id )->insert( $table, $data );

            $pk = $this->table_primary_key();
            $post[$pk] = $act_id;

            //保存秒杀内容
            $result = $this->_m_save($post);
            
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
     * 修改一个活动(注：合并到父类里)
     * @author luguihong
     * @deprecated
    */
    public function activity_edit( $post, $inter_id=NULL )
    {
    
        try {
    
            $this->_shard_db($inter_id)->trans_begin ();
            
            $pk = $this->table_primary_key();

            $product_id = isset( $post['product_id'] ) ? $post['product_id'] : '';
            $act_name = isset( $post['act_name'] ) ? $post['act_name'] : '';
            $status = isset( $post['status'] ) ? $post['status'] : '';
    
            //添加活动主单内容
            //添加活动类型和活动名称到activity_idx表
            $data = array();
            $data['act_name'] = $act_name;
            $data['status'] = $status;
            
            $where = array();
            $where[$pk] = $post[$pk];
    
            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_idx');
            $this->_shard_db( $inter_id )->where( $where )->update( $table, $data );
    
            //添加活动价格表内容
            $data = array();
            $data['act_name'] = $act_name;
            $data['product_id'] = $product_id;
            $data['price'] = isset( $post['killsec_price'] ) ? $post['killsec_price'] : '';
            
            $where = array();
            $where[$pk] = $post[$pk];
    
            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_product_price');
            $this->_shard_db( $inter_id )->where( $where )->update( $table, $data );
    
            //保存团购内容
            $result = $this->load( $post[$pk] )->m_sets( $post )->m_save();
    
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

    //活动是否可以修改
    public function can_modify()
    {
        $status = $this->m_get( 'status' );
        $instanceExistStatus = $this->instance_exist_status();
        if( in_array( $status, $instanceExistStatus ) ){
            /*
            $nowTime = time() + self::PREVIEW_TIME;
            $killsecTime = $this->m_get( 'killsec_time' );
            $killsecTime = strtotime( $killsecTime );
            if( time()< $killsecTime && $nowTime > $killsecTime ){
                return FALSE;
            }
            */
           
            // 活动前半个小时到活动结束后半个小时内不允许修改活动内容
            $now_time = time();
            $only_view_start = strtotime($this->m_get('killsec_time')) - self::PREVIEW_TIME;
            $only_view_end = strtotime($this->m_get('end_time')) + self::END_PREVIEW_TIME;
            if($now_time >= $only_view_start && $now_time <= $only_view_end) {
                return FALSE;
            }
        }

        return TRUE;

    }

    //获取活动列表
    public function get_activity_killsec_list_byActIds( $actIds, $inter_id=NULL )
    {
		if (empty($actIds)) {
			return array();
		}

        $table= $this->table_name($inter_id);
        $db = $this->_shard_db_r('iwide_soma_r');
        if(!empty($inter_id)){
            $db->where('inter_id', $inter_id );
        }

        //过滤条件过期时间
        $time = date('Y-m-d H:i:s');

        $result= $db
            ->where_in('act_id',$actIds)
            ->where('status',self::STATUS_TRUE)
            // ->where( 'end_time > ', $time )
            ->get($table)
            ->result_array();

            // var_dump( $this->_shard_db()->last_query() );die;
        return $result;
    }

	/**
	 * @param array $arr
	 * @return mixed
	 * @author renshuai  <renshuai@mofly.cn>
	 */
	public function search(Array $arr)
	{
		$query = $this->db_conn_read;

		if (isset($arr['where'])) {
			$query->where($arr['where']);
		}

		if (isset($arr['pagination'])) {
			$query->limit($arr['pagination']['limit'], $arr['pagination']['offset']);
		}
		return $query->get($this->table_name())->result_array();
	}

	/**
	 * @param array $arr
	 * @return object
	 * @author renshuai  <renshuai@mofly.cn>
	 */
    public function create(Array $arr)
    {
        return $this->soma_db_conn->insert($this->table_name(), $arr);
    }

	/**
	 *
	 * @param string $actID
	 * @param array $arr
	 * @return object
	 * @author renshuai  <renshuai@mofly.cn>
	 */
	public function update($actID, Array $arr)
	{
		$this->soma_db_conn->where($this->table_primary_key(), $actID);
		return $this->soma_db_conn->update($this->table_name(), $arr);
	}
    # 秒杀监控代码开始
    
    /**
     * 获取还没结束或者已结束没到1小时的秒杀活动信息
     *
     * @param      string  $inter_id  The inter identifier
     * @param      int     $act_id    The activity identifier
     *
     * @return     array   活动列表
     * 
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function monitorActivityInfo($inter_id = null, $act_id = null)
    {   
        $now_time = date('Y-m-d H:i:s');
        $next_hour = date('Y-m-d H:i:s', strtotime("+1 hours"));
        $last_hour = date('Y-m-d H:i:s', strtotime("-1 hours"));
        // $s_time = date('Y-m-d H:i:s', strtotime("-1 hours"));
        // $e_time = date('Y-m-d H:i:s', strtotime("+1 hours"));
        $select = 'act_id, act_name, product_id, product_name, killsec_time, end_time';
        $table_name = $this->db_conn_read->dbprefix($this->table_name_r($inter_id));
        $ins_table_name = $this->db_conn_read->dbprefix($this->instance_table_name_r($inter_id));

        $sql = "select " . $select 
             . " from " . $table_name
             . " where ((killsec_time > '" . $now_time . "' and killsec_time < '" . $next_hour . "')"
             . " or (killsec_time <= '" . $now_time . "' and end_time > '" . $last_hour . "')"
             . " or act_id in (select act_id from " . $ins_table_name . " where close_time > '" . $last_hour . "'))";

        if(isset($inter_id))
        {
            $sql .= " and inter_id = '" . $inter_id . "'";
        }
        if(isset($act_id))
        {
            $sql .= " and act_id = " . $act_id;
        }
        $sql .= " and status = " . Soma_base::STATUS_TRUE;

        $result = $this->db_conn_read->query($sql)->result_array();
        // echo $this->db_conn_read->last_query();exit;

        /*
        $this->db_conn_read->select($select);
        $this->db_conn_read->or_group_start();
        // 秒杀开始前1个小时还没开始的秒杀
        $this->db_conn_read->or_group_start();
        $this->db_conn_read->where('killsec_time >', $last_hour);
        $this->db_conn_read->where('killsec_time <', $n_time);
        $this->db_conn_read->or_group_end();
        // 秒杀时间在一个小时之外，但是还没结束的秒杀
        // 秒杀结束时间在一个小时之内，结束时间比当前时间的上一个小时要大
        $this->db_conn_read->or_group_start();
        $this->db_conn_read->where('killsec_time <=', $last_hour);
        $this->db_conn_read->where('end_time >', $last_hour);
        $this->db_conn_read->or_group_end();

        $this->db_conn_read->or_group_end();

        // $this->db_conn_read->where('end_time >', $now_time);

        // $this->db_conn_read->where('end_time <', $e_time);
        $this->db_conn_read->where('status', Soma_base::STATUS_TRUE);
        if($inter_id != null)
        {
            $this->db_conn_read->where('inter_id', $inter_id);
        }
        if($act_id != null) {
            $this->db_conn_read->where('act_id', $act_id);
        }
        $result = $this->db_conn_read->get($this->table_name_r($inter_id))->result_array();
        */

        $data = array();
        foreach ($result as $row)
        {
            $data[$row['act_id']] = $row;
        }

        return $data;
    }

    /**
     * 根据活动id获取秒杀前后1小时内的实例信息
     *
     * @param      array   $act_ids   The activity identifiers
     * @param      string  $inter_id  The inter identifier
     *
     * @return     array   活动实例列表
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function monitorInstanceInfo($act_ids, $inter_id = null)
    {
        if(empty($act_ids))
        {
            return array('ins_ids' => array());
        }

        $now_time = date('Y-m-d H:i:s');
        $next_hour = date('Y-m-d H:i:s', strtotime("+1 hours"));
        $last_hour = date('Y-m-d H:i:s', strtotime("-1 hours"));
        // $s_time = date('Y-m-d H:i:s', strtotime("-1 hours"));
        // $e_time = date('Y-m-d H:i:s', strtotime("+1 hours"));
        $select = 'instance_id, act_id, start_time, close_time, killsec_count, status';
        $table_name = $this->db_conn_read->dbprefix($this->instance_table_name_r($inter_id));

        $sql = "select " . $select 
             . " from " . $table_name
             . " where ((start_time > '" . $now_time . "' and start_time < '" . $next_hour . "')"
             . " or (start_time <= '" . $now_time . "' and close_time > '" . $last_hour . "'))";

        $in_sql = implode(',', $act_ids);
        $sql .= " and act_id in (" . $in_sql . ")";

        $result = $this->db_conn_read->query($sql)->result_array();

        /*
        $this->db_conn_read->select($select);
        $this->db_conn_read->where('start_time >', $s_time);
        $this->db_conn_read->where('close_time <', $e_time);
        $this->db_conn_read->where_in('act_id', $act_ids);

        $result = $this->db_conn_read->get($this->instance_table_name_r($inter_id))->result_array();
        */

        $data = $ins_ids = array();
        foreach ($result as $row)
        {
            $data[$row['act_id']][] = $row;
            $ins_ids[] = $row['instance_id'];
        }
        $data['ins_ids'] = $ins_ids;

        return $data;
    }

    /**
     * 根据实例ID获取秒杀Redis信息
     *
     * @param      array  $ins_ids  The instance identifiers
     *
     * @return     array  秒杀Redis信息
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function monitorRedisKillsecInfo($ins_ids)
    {
        $info    = array();
        $success = Soma_base::inst()->check_cache_redis();
        if(!$success) {
            return array('connect' => Soma_base::STATUS_FALSE, 'killsec' => array(), 'lock' => array());
        }

        $info['connect'] = Soma_base::STATUS_TRUE;

        $cache = $this->_load_cache();
        $redis = $cache->redis->redis_instance();

        $info['killsec'] = array();
        foreach($ins_ids as $ins_id)
        {
            $data = array(
                'token_key' => $this->_get_redis_info($redis, $ins_id), 
                'white_key' => $this->_get_redis_info($redis, $ins_id, 'white'),
                'cache_key' => $this->_get_redis_info($redis, $ins_id, 'cache'),
                'order_key' => $this->_get_redis_info($redis, $ins_id, 'order'),
                'click_key' => $this->_get_redis_info($redis, $ins_id, 'click'),
                'black_key' => $this->_get_redis_info($redis, $ins_id, 'black'),
            );
            foreach ($data as $key => $item)
            {
                if($item['exist'] == Soma_base::STATUS_TRUE)
                {
                    $info['killsec'][$ins_id] = $data;
                    break;
                }
            }
        }

        return $info;
    }

    /**
     * 检测两个Redis链接状态以及定时任务锁状态
     *
     * @return     array  Redis链接状态与定时任务锁信息
     * 
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function monitorRedisConnInfo()
    {
        $info = array();

        // Redis业务服务器检测
        if(!Soma_base::inst()->check_cache_redis())
        {
            $info['k_server_conn'] = Soma_base::STATUS_FALSE;
        }
        else
        {
            $info['k_server_conn'] = Soma_base::STATUS_TRUE;
        }

        if(!$redis = $this->get_redis_instance())
        {
            $info['l_server_conn'] = Soma_base::STATUS_FALSE;
        }
        else
        {
            $info['l_server_conn'] = Soma_base::STATUS_TRUE;
            // 复制定时任务中的锁键
            $init_key = 'SOMA_CRON:KILLSEC_INSTANCE_INIT_LOCK';
            $user_key = 'SOMA_CRON:KILLSEC_USER_ORDER_CLEANING_LOCK';
            $info['lock'] = array(
                'init_key' => $redis->exists($init_key) ? Soma_base::STATUS_FALSE : Soma_base::STATUS_TRUE,
                'user_key' => $redis->exists($user_key) ? Soma_base::STATUS_FALSE : Soma_base::STATUS_TRUE,
            );
        }

        return $info;
    }

    /**
     * 删除秒杀相关锁
     * 
     * 1:实例锁 2:订单锁 
     * 
     * @param      integer  $type   The type [1|2]
     *
     * @return     boolean  成功返回true,失败返回false
     * 
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function deleteKillsecLock($type)
    {
        if(!in_array($type, array(1,2)))
        {
            return false;
        }

        if(!$redis = $this->get_redis_instance())
        {
            return false;
        }
        else
        {
            $key = 'SOMA_CRON:KILLSEC_INSTANCE_INIT_LOCK';
            if($type == 2)
            {
                $key = 'SOMA_CRON:KILLSEC_USER_ORDER_CLEANING_LOCK';
            }

            if($redis->delete($key) == 1)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the redis information.
     *
     * @param      Redis   $redis   The redis instance
     * @param      int     $ins_id  The instance identifier
     * @param      string  $key     The key
     *
     * @return     array   The redis information.
     * 
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    protected function _get_redis_info($redis, $ins_id, $key = null)
    {
        $info      = array();
        $redis_key = $this->redis_token_key($ins_id, $key);

        if($redis->exists($redis_key))
        {
            $info['exist'] = Soma_base::STATUS_TRUE;
            if($key == null)
            {
                // list,秒杀名额
                $info['info'] = array(
                    'size'   => $redis->lSize($redis_key),
                    'detail' => $redis->lRange($redis_key, 0, -1),
                );
            }
            else
            {
                // hset，用户相关信息
                $info['info'] = array(
                    'size'   => $redis->hLen($redis_key),
                    'detail' => $redis->hGetAll($redis_key),
                );
            }
        }
        else
        {
            $info = array('exist' => Soma_base::STATUS_FALSE, 'info' => array('size' => 0, 'detail' => array()));
        }

        return $info;
    }

    /**
     * @param $id
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getByID($id)
    {
        $params = array(
            'act_id' => $id
        );
        $result = $this->db_conn_read->where($params)->get($this->table_name())->result_array();

        if (empty($result)) {
            return array();
        }
        return $result[0];
    }

    /**
     * @param array $arr
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function searchInstance(Array $arr)
    {
        if (empty($arr)) {
            return array();
        }

        foreach ($arr as $k=>$v) {
            if( is_array($v) ){
                $this->db_conn_read->where_in($k, $v);
            } else {
                $this->db_conn_read->where($k, $v);
            }
        }

        $result = $this->db_conn_read->get($this->table_name())->result_array();

        if (empty($result)) {
            return array();
        }
        return $result;
    }

    /**
     * @param $actIds
     * @param null $inter_id
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
	public function get_available_killsec_list_byActIds(Array $actIds, $inter_id=NULL )
	{
		if (empty($actIds)) {
			return array();
		}

		$table= $this->table_name($inter_id);
		$db = $this->db_conn_read;
		if(!empty($inter_id)){
			$db->where('inter_id', $inter_id );
		}

		$currentDate = date('Y-m-d H:i:s');

		$result= $db
			->where('status',self::STATUS_TRUE)
            ->where_in('act_id', $actIds)
            ->where( 'end_time > ', $currentDate )
            ->where( 'start_time < ', $currentDate )
			->get($table)
			->result_array();

		return $result;
	}

    # 秒杀监控代码结束

}
