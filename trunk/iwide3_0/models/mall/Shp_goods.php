<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Shp_goods extends MY_Model_Mall {

	public function get_resource_name()
	{
		return '商品信息';
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
		return 'shp_goods';
	}

	public function table_primary_key()
	{
	    return 'gs_id';
	}

	const GS_TYPE_1 = 1;
	const GS_TYPE_2 = 2;
	//const GS_TYPE_3 = 3;

	const CARD_USE_TYPE_1 = 1;
	const CARD_USE_TYPE_2 = 2;
	const CARD_USE_TYPE_3 = 3;
	
	const STATUS_T = 0;
	const STATUS_F = 1;

	public function get_can_label()
	{
	    return array(
	        self::STATUS_T => '能',
	        self::STATUS_F => '不能',
	    );
	}
	public function get_type_label()
	{
	    return array(
	        self::GS_TYPE_1 => '普通商品',
	        self::GS_TYPE_2 => '卡券类商品',
	        //self::GS_TYPE_3 => '虚拟商品',
	    );
	}
	public function get_use_type_label()
	{
	    return array(
	        self::CARD_USE_TYPE_1 => '卡号',
	        self::CARD_USE_TYPE_2 => '卡密',
	        self::CARD_USE_TYPE_3 => '卡号+卡密',
	    );
	}
    public function get_onsale_label()
    {
        return array(
            self::STATUS_T => '在售',
            self::STATUS_F => '下架',
        );
    }

	public function attribute_labels()
	{
		return array(
			'gs_id'=> 'ID',
			'cat_id'=> '所属分类',
			'gs_name'=> '商品名称',
		    'sku'=> 'SKU/UPC',
			'gs_brand'=> '品牌名称',
			'gs_nums'=> '在售数量',
			'gs_weight'=> '商品重量',
			'gs_market_price'=> '市场价',
			'gs_wx_price'=> '微信价',
			'gs_warm_nums'=> '报警数',
			'gs_keyword'=> '关键词',
			'gs_unit'=> '计量单位',
			'gs_sort'=> '商品排序',
			'gs_desc'=> '摘要介绍',
			'gs_logo'=> '缩略图',
			'add_user'=> '添加人',
			'add_date'=> '添加时间',
			'last_update_time'=> '最后更新时间',
			'last_update_user'=> '最后更新人',
			'hotel_id'=> '酒店ID',
			'inter_id'=> '公众号',
		    
			//状态标识适用于以下几个个字段
			'onsale'=> '在售状态',
			'can_mail'=> '能邮寄?',
			'can_gift'=> '能赠送?',
			'can_pickup'=> '能自提?',
		    
			'sales_good'=> '畅销?',
			'is_promote'=> '促销?',
		    
			'is_delete'=> '删除商品?',
			'is_new'=> '新品?',
			'is_hot'=> '秒杀商品?',

//'shipping_desc'=> '邮寄说明',

		    'is_virtual'=> '商品模型',
            'card_use_type'=> '消费方式',
            'wx_card_id'=> '微信card_id',

		    'consume_start'=> '核销生效时间',
            'consume_end'=> '核销失效时间',
		    
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
			'gs_id',
            'gs_logo',
			'inter_id',
			'cat_id',
			'can_mail',
			'can_gift',
			'can_pickup',
			'gs_name',
			'sku',
			'gs_nums',
			'gs_market_price',
			'gs_wx_price',
	        'onsale',
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
	    	  
        $this->load->model('mall/shp_category');
        $cat_inter_id= isset($filter['inter_id'])? $filter['inter_id']: NULL;
        $cats= $this->shp_category->get_cat_tree_option($cat_inter_id);
	    
	    return array(
            'gs_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'cat_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
	            'type'=>'combobox',
	            'select'=> $cats,
            ),
            'gs_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '20%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'sku' => array(
                'grid_ui'=> '',
                'grid_width'=> '20%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'gs_brand' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> array(),
            ),
            'gs_nums' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '100',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_good_stock',
                'type'=>'number',	//textarea|text|combobox
            ),
            'gs_weight' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_tips'=> '单位为KG/千克',
                //'form_hide'=> TRUE,
                'type'=>'weight',	//textarea|text|combobox
            ),
            'gs_unit' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '个',
                'form_tips'=> '如“组”，“盒”，“箱”，不能带有数字',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'gs_market_price' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '9999.00',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',	//textarea|text|combobox
            ),
            'gs_wx_price' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '0.01',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',	//textarea|text|combobox
            ),
            'gs_warm_nums' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '10',
                'form_tips'=> '增加描述：商品库存少于多少时进行提醒',
                'form_hide'=> TRUE,
                'type'=>'number',	//textarea|text|combobox
            ),
            'gs_keyword' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'gs_sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '0',
                'form_tips'=> '优先级越大排序越靠前',
                //'form_hide'=> TRUE,
                'type'=>'number',	//textarea|text|combobox
            ),
            'gs_desc' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' rows="2" ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'textarea',
            ),
            'gs_logo' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_admin_head|80',
                'type'=>'logo',	//textarea|text|combobox
            ),
            'can_mail' => array( //能否邮寄
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
	            'select'=> self::get_can_label(),
            ),
            'can_gift' => array( //能否分享
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
	            'select'=> self::get_can_label(),
            ),
            'can_pickup' => array( //能否自提
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
	            'select'=> self::get_can_label(),
            ),
            'onsale' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'grid_function'=> 'show_status_color|在售|下架',
	            'select'=> self::get_onsale_label(),
            ),
            'add_user' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'add_date' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'sales_good' => array(  //热销
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
	            'select'=> $base_util::get_status_options_(),
            ),
            'is_promote' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
	            'select'=> $base_util::get_status_options_(),
            ),
            'is_delete' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
	            'select'=> $base_util::get_status_options_(),
            ),
            'is_new' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox',
	            'select'=> $base_util::get_status_options_(),
            ),
            'is_hot' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'type'=>'combobox',
	            'select'=> $base_util::get_status_options_(),
            ),
            'last_update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'last_update_user' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $publics,
            ),
            'consume_start' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_default'=> date('Y-m-d H:i:s'),
                'type'=>'datetime',	//textarea|text|combobox
            ),
            'consume_end' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_default'=> date('Y-m-d H:i:s', strtotime('+30 days') ),
                'type'=>'datetime',	//textarea|text|combobox
            ),
            'is_virtual' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox',
                'select'=> $this->get_type_label(),
            ),
            'card_use_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox',
                'select'=> array(''=>'- 无 -')+ $this->get_use_type_label(),
            ),
            'wx_card_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_hide'=> TRUE,
                'form_tips'=> '微信卡券类型对应ID',
                'type'=>'text',	//textarea|text|combobox
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'gs_nums', 'sort'=>'asc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */


    public function m_save($data=NULL, $update=FALSE)
    {
        $pk= $this->table_primary_key();
        $table= $this->table_name();
        $fields= $this->_db()->list_fields($table);
        $prefix= $this->_db()->dbprefix;
        
        if( isset($this->_data[$pk]) && $this->_data[$pk]>0 ) {
            //修改
            $this->_db()->trans_begin();
            
            if($data){
                foreach ($data as $k=>$v){
                    if(in_array($k,$fields)) $this->_data[$k]= $v;
                }
            }
            $where= array( $pk=> $this->_data[$pk] );
            $this->_db()->where($where);
            $result= $this->_db()->update($table, $this->_data);

            $sql= "update {$this->_db()->dbprefix}shp_goods_attr set attr_value='{$data['gs_detail']}' where gs_id=". $this->_data[$pk]. " and attr_id=1";
            $this->_db()->query($sql);

            if ($this->_db()->trans_status() === FALSE) {
                $this->_db()->trans_rollback();
                return FALSE;

            } else {
                $this->_db()->trans_commit();
                return TRUE;
            }
             
        } else {
            //新增情况
            $this->_db()->trans_begin();

            if($data){
                foreach ($data as $k=>$v){
                    if(in_array($k,$fields)) $this->_data[$k]= $v;
                }
            }
            unset($this->_data[$pk]);
            $result= $this->_db()->insert($table, $this->_data);
            $last_id= $this->insert_id();

            $sql= "insert into {$this->_db()->dbprefix}shp_goods_attr (`attr_value`, gs_id, attr_id ) values 
                ('". $data['gs_detail']. "', ". $last_id. " ,1)";
            $this->_db()->query($sql);
            
            if ($this->_db()->trans_status() === FALSE) {
                $this->_db()->trans_rollback();
                return FALSE;

            } else {
                $this->_db()->trans_commit();
                return $last_id;
            }
        }
    }
    
    //获取相册记录
    public function get_gallery()
    {
        $pk= $this->table_primary_key();
        if($pkv= $this->m_get($pk)){
            $table= 'shp_goods_gallery';
            $items= $this->_db()->get_where($table, array('gs_id'=>$pkv))->result_array();
            return $items;
    
        } else {
            return array();
        }
    }
    
    //新增相册记录
    public function plus_gallery($data)
    {
        $table= 'shp_goods_gallery';
        $result= $this->_db()->insert($table, $data);
        return $result;
    }

    //删除相册记录
    public function delete_gallery($ids, $pkv)
    {
        $pk= $this->table_primary_key();
        if($pkv){
            $table= 'shp_goods_gallery';
            $result= $this->_db()->where(array('gs_id'=>$pkv))->where_in('gry_id', $ids)->delete($table);
            return $result;
    
        } else {
            return array();
        }
    }



                /**
                 * 获取多个产品基本信息，(包含产品的扩展属性)
                 * @param Array $ids
                 * @param string $inter_id
                 * @param string $hotel_id
                 */
            	public function get_products_by_ids($ids, $inter_id=NULL, $hotel_id=NULL)
            	{
            	    $datas= array();
            	    foreach ($ids as $v){
            	        $datas[$v]= $this->get_single_goods_details($v, $inter_id, $hotel_id);
            	    }
            	    return $datas;
            	}
	
            	/**
            	 * 获取单个产品的全部属性信息，(包含产品的扩展属性)
            	 */
            	public function get_single_goods_details($goods_id, $inter_id=NULL, $hotel_id=NULL)
            	{
        	        $filter= array( 'gs_id'=> $goods_id );
        	        if($inter_id) $filter['inter_id']= $inter_id;
        	        if($hotel_id) $filter['hotel_id']= $hotel_id;
            	        
        	        $data= $this->get_one_detail($filter);
        	        if( $data ){
        	            return $data;
        	            
            	    } else {
            	        return array();
            	    }
            	}
            	/**
            	 * 过滤得到单个产品信息，(包含产品的扩展属性)
            	 * @param Array $filter
            	 * @param string $select
            	 * @return Ambigous <multitype:, mixed, unknown>
            	 */
            	public function get_one_detail($filter, $select=NULL)
            	{
            	    $data= $this->find($filter);
            	    if(isset($filter['gs_id'])){
                	    $where= array( 'gs_id'=> $filter['gs_id'] );
                	    $attrs = $this->_db()->where($where)->get('shp_goods_attr')->result_array();
            	        $data['ext_attr']= $attrs;
            	    }
            	    return $data;
            	}
            	/**
            	 * 获取多个产品基本信息，(不包含产品的扩展属性)
            	 * @param unknown $filter
            	 * @param string $select
            	 * @param string $sort
            	 * @param number $offset
            	 * @param string $limit
            	 */
            	public function get_good_records($filter, $select=NULL, $sort=NULL, $offset=0, $limit=NULL)
            	{
            	    $table= $this->table_name();
            	    $select= count($select)==0? '*': implode(',', $select);
            	    $this->_db()->select(" {$select} ");
            	     
            	    $where= array();
            	    $dbfields= array_values($fields= $this->_db()->list_fields($table));
            	    foreach ($filter as $k=>$v){
            	        //过滤非数据库字段，以免产生sql报错
            	        if(in_array($k, $dbfields) && is_array($v)){
            	            $this->_db()->where_in($k, $v);
            	        } else if(in_array($k, $dbfields)) {
            	            $this->_db()->where($k, $v);
            	        }
            	    }
            	    if($sort) $this->_db()->order_by($sort);
            	    if($limit) $this->_db()->limit($limit, $offset);
            	    $result= $this->_db()->get($table);
            	    return $result->result_array();
            	}


    public function get_cat_goods($topic_id=null, $category_id=null, $offset=0, $limit=100)
    {
        $filter= array();
        $base_obj= EA_base::inst();
        //限定专题内分类
        $cat_where = " g.cat_id in (select cat_id from ". $this->_db()->dbprefix('shp_topic_category'). " where topic_id={$topic_id}) and ";
        if($category_id=='all' || !$category_id ){
            //专题内全部分类
            $cat_where .= '';
        } else if($category_id){
            $cat_where .= " g.cat_id=". $category_id. " and ";
        }
        $sql= "select g.* from ". $this->_db()->dbprefix('shp_goods'). " as g where {$cat_where} ";
        
        $sql.= " g.onsale=". $base_obj::STATUS_TRUE_;
        $sql.= " order by gs_sort desc, gs_id desc limit {$offset}, {$limit}";
        
        $return= $this->_db()->query($sql)->result_array();
        return $return;
    }

    //首发新品
    public function get_new_goods($topic_id=null, $category_id=null, $offset=0, $limit=100)
    {
        return $this->get_featrue_goods('is_new', $topic_id, $category_id, $offset, $limit);
    }
    //热门主推商品
    public function get_hot_goods($topic_id=null, $category_id=null, $offset=0, $limit=100)
    {
        return $this->get_featrue_goods('is_hot', $topic_id, $category_id, $offset, $limit);
    }
    //标记热销的商品
    public function get_wellsales_goods($topic_id, $category_id=null, $offset=0, $limit=100)
    {
        return $this->get_featrue_goods('sales_good', $topic_id, $category_id, $offset, $limit);
    }
    //标记促销的商品
    public function get_promotion_goods($topic_id=null, $category_id=null, $offset=0, $limit=100)
    {
        return $this->get_featrue_goods('is_promote', $topic_id, $category_id, $offset, $limit);
    }
    /* 寻找字段特征的产品（带分类过滤） */
    public function get_featrue_goods($field, $topic_id=null, $category_id=null, $offset=0, $limit=100)
    {
        $filter= array();
        $base_obj= EA_base::inst();
        $cat_where = " g.cat_id in (select cat_id from ". $this->_db()->dbprefix('shp_topic_category'). " where topic_id={$topic_id}) and ";
        if($category_id){
            $cat_where .= " g.cat_id=". $category_id. " and ";
        } 

        $sql= "select g.* from ". $this->_db()->dbprefix('shp_goods'). " as g where {$cat_where} ";
        
        $sql.= " g.onsale=". $base_obj::STATUS_TRUE_. " and g.". $field. "=". $base_obj::STATUS_TRUE_;
        $sql.= " order by gs_sort desc, gs_id desc limit {$offset}, {$limit}";
    //echo ($sql);die;
        $return= $this->_db()->query($sql)->result_array();
        return $return;
    }
                /**
                 * 取商品相册图片（多条）
                 * @param $goods_id 商品ID
                 * @return NULL
                 * */
                public function get_single_goods_gallery($goods_id)
                {
                    if(!empty($goods_id)){
                        $this->_db()->where(array('gs_id'=>$goods_id));
                        return $this->_db()->get('shp_goods_gallery')->result_array();
                        
                    }else{
                        return null;
                    }
                }
                
}
