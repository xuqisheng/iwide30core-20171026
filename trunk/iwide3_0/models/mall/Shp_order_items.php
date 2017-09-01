<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Shp_order_items extends MY_Model_Mall {

	public function get_resource_name()
	{
		return '订单明细';
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
		return 'shp_order_items';
	}

	public function table_primary_key()
	{
	    return 'id';
	}

	public function can_consume_status()
	{
	    return array(
	        self::STATUS_DEFAULT,
	        self::STATUS_GIFTED,
	    );
	}
	public function can_shipping_status()
	{
	    return array(
	        self::STATUS_SHIP_PRE,
	    );
	}
	
	const STATUS_DEFAULT = '0';
	const STATUS_GIFTING = '1';
	const STATUS_GIFTED  = '2';
	const STATUS_SHIP_PRE= '5'; //申请寄送
	const STATUS_SHIPPING= '3'; //寄送中
	const STATUS_COMSUME = '4';
	const STATUS_FINISH  = '6';

	const ADD_PACK_T = '0';
	const ADD_PACK_F = '1';

	const EX_ORDER_T = '0';
	const EX_ORDER_F = '1';

    public function order_item_status()
    {
        /**
         * 流程1：待处理=> 待邮寄（客户）=> 已邮寄（商家）=> 完成
         * 流程2：待处理=> 已核销=> 完成
         * 流程3：待处理=> 赠送中=> 已领取=> (赠送中=> 已领取=>...) 待邮寄（客户）=> 已邮寄=> 完成
         * 流程4：待处理=> 赠送中=> 已领取=> (赠送中=> 已领取=>...) 待邮寄（客户）=> 已邮寄=> 完成
         *                待邮寄（客户）=> 已邮寄（商家）=> 完成
         */
        return array(
            self::STATUS_DEFAULT => '待处理',
            self::STATUS_GIFTING => '赠送中',
            self::STATUS_GIFTED => '已领取',
            self::STATUS_SHIP_PRE => '待发货',
            self::STATUS_SHIPPING => '已发货',
            self::STATUS_COMSUME => '已核销',
        );
    }

	public function attribute_labels()
	{
		return array(
			'id'=> '#ID',
			'order_id'=> 'ID',
			'hotel_id'=> '酒店ID',
			'inter_id'=> '公众号',
			'gs_id'=> '商品ID',
			'gs_name'=> '商品名称',
			'market_price'=> '市场价',
			'price'=> '实际售价',
			'promote_price'=> '促销价格',
            'gs_code'=> '产品码',
            'order_time'=> '下单时间',
            
			'openid'=> 'OPENID',
		    
//'is_virtual'=> '能否加入卡包',
//'wx_card_id'=> '微信卡券类型',
//'wx_card_no'=> '微信卡券码',
//'wx_card_status'=> '卡券状态',
			'is_add_pack'=> '添加到卡包？',

            'addr_id'=> '邮寄地址ID',       //关联address表中的地址
            'trans_time'=> '寄送时间',
			'trans_company'=> '快递公司',
			'trans_no'=> '快递单号',
			'send_name'=> '寄送人',
			'send_phone'=> '寄送人电话',
            'ex_order'=> '是否拆单',

            'consume_time'=> '核销时间',
            'consumer'=> '核销人',

            'status'=> '状态',

		    'gs_unit'=> '计量单位',
            'get_openid'=> '原始购买人',     //转赠过程中，openid会随着持有人变化，get_openid记录第一个购买人
            'get_time'=> '接赠时间',
            'share_code'=> '分享码',

            //下列为虚拟字段
            'num_order'=> '包含订单数',
            'num_item'=> '数量',
		    'out_trade_no'=> '订单SN',
		    'total_fee'=> '订单总额',
		    'contact'=> '联系人',
		    'phone'=> '联系电话',
		    'address'=> '配送地址',

		    'consume_start'=> '开始核销时间',
            'consume_end'=> '结束核销时间',

		);
	}

    public function virtual_field()
    {
        return array( 'order_id','num_item','out_trade_no','total_fee','contact','phone','address');
        //return array('num_order','order_id','num_item','out_trade_no','total_fee','contact','phone','address');
    }

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
			//'id',      //因grid选择行不需要主键值标记，故可以屏蔽
			'gs_name',
			'price',
			'trans_time',
			'trans_company',
			'trans_no',
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
	        //$hotels= $hotels+ array('0'=>'-不限定-');
	    }
	    /** 获取本管理员的酒店权限  */
	    	  
	    return array(
            'id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'gs_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'gs_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'sku' => array(
                'grid_ui'=> '',
                'grid_width'=> '20%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'market_price' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',	//textarea|text|combobox
            ),
            'price' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',	//textarea|text|combobox
            ),
            'promote_price' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',	//textarea|text|combobox
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'get_openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'gs_unit' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'gs_code' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'get_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'datetime',	//textarea|text|combobox
            ),
            'consume_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'datetime',	//textarea|text|combobox
            ),
            'consumer' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'ex_order' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $base_util::get_status_options_(),
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox', //textarea|text|combobox
                'select'=> $this->order_item_status(),
            ),
            'is_add_pack' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $base_util::get_status_options_(),
            ),
            'order_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'datetime',	//textarea|text|combobox
            ),
            'trans_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'substr|5|11',
                'type'=>'datetime',	//textarea|text|combobox
            ),
            'trans_company' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'trans_no' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'send_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'send_phone' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'share_code' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'addr_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),

            //虚拟字段开始
            'num_order' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text', //textarea|text|combobox
            ),
            'num_item' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text', //textarea|text|combobox
            ),
            'total_fee' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price', //textarea|text|combobox
            ),
            'out_trade_no' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text', //textarea|text|combobox
            ),
            'contact' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                'type'=>'text', //textarea|text|combobox
            ),
            'phone' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'type'=>'text', //textarea|text|combobox
            ),
            'address' => array(
                'grid_ui'=> '',
                'grid_width'=> '15%',
                'type'=>'text', //textarea|text|combobox
            ),
	        'hotel_id' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '10%',
	            'form_ui'=> ' disabled ',
	            //'form_default'=> '0',
	            //'form_tips'=> '注意事项',
	            'form_hide'=> TRUE,
	            'type'=>'combobox',
	            'select'=> $hotels,
	        ),
	        'inter_id' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '10%',
	            'form_ui'=> ' disabled ',
	            //'form_default'=> '0',
	            //'form_tips'=> '注意事项',
	            'form_hide'=> TRUE,
	            'type'=>'combobox',
	            'select'=> $publics,
	        ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */
	

    public function filter( $params=array(), $select= array(), $format='array' )
    {
        return array(
            'total'=> 0,
            'data'=> array(),
            'page_size'=> 20,
            'page_num'=> 1,
        );
    }

    /**
     * 功能跟filter差不多，作用于datatable grid ajax查询，下列为 $params 中带有的参数
order[0][column]:6 排序列索引
order[0][dir]:desc 排序方向
start:0 开始记录
length:20 每页条数
search[value]: 搜索字眼
search[regex]:false 
     */
    public function filter_json( $params=array(), $select= array() )
    {
        $table= $this->table_name();
        $where= array();
        $dbfields= array_values($fields= $this->_db()->list_fields($table));
        foreach ($params as $k=>$v){
            //过滤非数据库字段，以免产生sql报错
            if(in_array($k, $dbfields)) $where[$k]= $v;
        }
        
//         if( isset($params['order'][0]['column']) && isset($params['order'][0]['dir']) ){
//             $field= $this->field_name_in_grid($params['order'][0]['column']);
//             $sort= $field. ' '. $params['order'][0]['dir'];
            
//         } else {
            $pk= $this->table_primary_key();
            $sort= "{$pk} DESC";  //默认排序
//         }

/* select gs_name, price, addr_id, count(addr_id) as num_order '1地址有多少订单', order_id, count(order_id) as num_item '1订单有多少明细'
     from iwide_shp_order_items where addr_id>0 group by addr_id,order_id order by addr_id ;
*/
        if(count($select)==0) {
            $select= $this->grid_fields();
        }
        $select= count($select)==0? '*': implode(',', $select);
        $select.= ', addr_id, order_id, count(order_id) as num_item';
        //$select.= ', addr_id, count(addr_id) as num_order, order_id, count(order_id) as num_item';
        
        $total= $this->_db()->select(" {$select} ")->order_by($sort)->group_by('addr_id,order_id')
            ->where('addr_id > ', '0')->get_where($table, $where)->num_rows();
        
        $result= $this->_db()->select(" {$select} ")->order_by($sort)->group_by('addr_id,order_id')
            ->where('addr_id > ', '0')->limit($params['length'], $params['start'])->get_where($table, $where)
            ->result_array(); 

        if(count($result)>0) $result= $this->fill_order_data($result);
        if(count($result)>0) $result= $this->fill_address_data($result);
        
        $tmp= array();

        $field_config= $this->get_field_config('grid');
        foreach ($result as $k=> $v){
            //判断combobox类型需要对值进行转换
            foreach($field_config as $sk=>$sv){
                if($field_config[$sk]['type']=='combobox') {
                    if( isset($field_config[$sk]['select'][$v[$sk]]))
                        $v[$sk]= $field_config[$sk]['select'][$v[$sk]];
                    else $v[$sk]= '-';
                }
                if( $field_config[$sk]['grid_function'] ) {
                    $funp= explode('|', $field_config[$sk]['grid_function']);
                    $fun= $funp[0];
                    $funp[0]= $v[$sk];
                    $v[$sk]= call_user_func_array ($fun, $funp);
                } else if( $field_config[$sk]['function'] ) {
                    $funp= explode('|', $field_config[$sk]['function']);
                    $fun= $funp[0];
                    $funp[0]= $v[$sk];
                    $v[$sk]= call_user_func_array ($fun, $funp);
                }
            }//----- 

            $el= array_values($v);

            //数据行顺序置换
            $el= $this->element_seq_convert($el);

            $el['DT_RowId']= $v['order_id'];
            $tmp[]= $el;
        }
        $result= $tmp;
        
        return array(
            'draw'=> isset($params['draw'])? $params['draw']: 1,
            'data'=> $result,
            'recordsTotal'=>$total,
            'recordsFiltered'=>$total,
        );
    }
    
    /**
     * 给细单group by后的数据填充主单信息
     * @param array $items
     * @return array $items
     */
    public function fill_order_data($items)
    {
        $ids= array();
        foreach($items as $v){
            $ids[]= $v['order_id'];
        }
        $data= array();
        $result= $this->_db()->select('*')->where_in('order_id', $ids)->get('shp_orders')->result_array();
        foreach($result as $k=> $v){
            $data[$v['order_id']]= $v;
        }
        //print_r($orders);die;
        foreach ($items as $k=> $v){
            $items[$k]['out_trade_no']= $data[$items[$k]['order_id']]['out_trade_no'];
            $items[$k]['total_fee']= $data[$items[$k]['order_id']]['total_fee'];
            //unset($items[$k]['order_id']);    //作为主键不能删除
        }
        //print_r($items);die;
        return $items;
    }

    /**
     * 给细单group by后的数据填充订单信息
     * @param array $items
     * @return array $items
     */
    public function fill_address_data($items)
    {
        $ids= array();
        foreach($items as $v){
            $ids[]= $v['addr_id'];
        }
        $data= array();
        $result= $this->_db()->select('*')->where_in('id', $ids)->get('shp_address')->result_array();
        foreach($result as $k=> $v){
            $data[$v['id']]= $v;
        }
        //print_r($data);die;
        foreach ($items as $k=> $v){
            $items[$k]['contact']= $data[$items[$k]['addr_id']]['contact'];
            $items[$k]['phone']= $data[$items[$k]['addr_id']]['phone'];
            $items[$k]['address']= $data[$items[$k]['addr_id']]['province']
                . $data[$items[$k]['addr_id']]['city']. $data[$items[$k]['addr_id']]['region'] ;
            unset($items[$k]['addr_id']);
        }
        //print_r($items);die;
        return $items;
    }

    /*
     * 将数组顺序进行个性顺序置换，同时 表格中头部顺序需要对应置换
     */
    public function element_seq_convert($el)
    {
        list($v3,$v4,$v9,$v7,$v8,   $v13,$v1,$v5,$v2,$v6,   $v10,$v11,$v12 )= $el;
        $tmp= array();
        for($i=2; $i<=13; $i++){
            $n= 'v'. $i;
            $tmp[]= $$n;
        }
        return $tmp;
    }
    public function element_seq_header()
    {
        return array( /* 'order_id',*/'out_trade_no', 'gs_name','price','num_item','total_fee',
            'trans_company', 'trans_no','trans_time','contact','phone','address', 'status',
        );
    }

    public function get_field_config($type='grid')
    {
        $data= array();
        if($type=='grid'){
            $show= $this->grid_fields();
    
        } else {
            //有时需要取数据库以外的字段，如 密码确认字段，在模板手动添加
            $show= $this->_db()->list_fields($this->table_name());
        }
    
//        $virtual_field= $this->virtual_field();
//        $show= array_merge($show, $virtual_field);

        //直接定制头部顺序
        $show= $this->element_seq_header();

        $fields= $this->attribute_labels();
    
        $fields_ui= $this->attribute_ui();
        foreach ($show as $v){
            if( !isset($fields[$v]) || !isset($fields_ui[$v])  ) continue;
    
            $data[$v]['label']= $fields[$v];
    
            if($type=='grid'){
                //grid所需配置信息
                if( array_key_exists($v, $fields_ui) ){
                    $data[$v]['grid_ui'] = isset($fields_ui[$v]['grid_ui'])?$fields_ui[$v]['grid_ui']: '';
                    $data[$v]['grid_width'] = isset($fields_ui[$v]['grid_width'])?$fields_ui[$v]['grid_width']: "";
                    $data[$v]['grid_function'] = isset($fields_ui[$v]['grid_function'])? $fields_ui[$v]['grid_function']: FALSE;
                    $data[$v]['function'] = isset($fields_ui[$v]['function'])? $fields_ui[$v]['function']: FALSE;
                    $data[$v]['type'] = isset($fields_ui[$v]['type'])?$fields_ui[$v]['type']: 'text';
                    if( $data[$v]['type']=='combobox' ) $data[$v]['select'] = $fields_ui[$v]['select'];
                }
    
            } else if($type=='form') {
                //form所需配置信息
                $data[$v]['form_ui'] = isset($fields_ui[$v]['form_ui'])? $fields_ui[$v]['form_ui']: '';
                $data[$v]['form_tips'] = !empty($fields_ui[$v]['form_tips'])? $fields_ui[$v]['form_tips']: NULL;
                $data[$v]['form_default'] = isset($fields_ui[$v]['form_default'])? $fields_ui[$v]['form_default']: NULL;
                $data[$v]['form_hide'] = isset($fields_ui[$v]['form_hide'])? $fields_ui[$v]['form_hide']: FALSE;
                $data[$v]['function'] = isset($fields_ui[$v]['function'])? $fields_ui[$v]['function']: FALSE;
                $data[$v]['type'] = isset($fields_ui[$v]['type'])? $fields_ui[$v]['type']: 'text';
                if( $data[$v]['type']=='combobox' ) $data[$v]['select'] = $fields_ui[$v]['select'];
                if( isset($fields_ui[$v]['form_type'])) $data[$v]['type'] = $fields_ui[$v]['form_type'];
            }
        }
        return $data;
    }
    
    /**
     * 后台填写单号，快递公司，更新对应细单信息
     * @param  [type] $data     [description]
     * @param  [type] $item_ids [description]
     * @param  array  $filter   [description]
     * @return [type]           [description]
     */
    public function update_transno_batch($data, $item_ids, $filter= array())
    {
        //过滤只有提交收货地址的才能发货
        $filter+= array(
            'status'=> self::STATUS_SHIP_PRE,
        );
        $data= $data+ array(
            'trans_time'=> date("Y-m-d H:i:s"),
            'status'=> self::STATUS_SHIPPING,
        );
        $table= $this->table_name();
        if(count($item_ids)>0 ) $this->_db()->where_in('id', $item_ids);
        //$this->_db()->where('status!=', self::STATUS_SHIPPING );
        //$this->_db()->where('trans_no is ', NULL );
        //print_r($filter);print_r($data);die;
        $this->_db()->where($filter);
        $result= $this->_db()->update($table, $data);
        return $result;
    }
    
    
}
