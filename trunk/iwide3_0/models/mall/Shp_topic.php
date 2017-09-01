<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Shp_topic extends MY_Model_Mall {

	public function get_resource_name()
	{
		return '社交专题';
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
		return 'shp_topic';
	}

	public function table_primary_key()
	{
	    return 'topic_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'topic_id'=> 'ID',
            'hotel_id'=> '酒店ID',
            'inter_id'=> '公众号',
            'identity'=> '统一标识',
            'page_theme'=> '页面皮肤',
            'theme_color'=> '专题背景色',
            'theme_image'=> '专题背景图',
            'page_title'=> '页面标题',
			'page_starttime'=> '开始时间',
			'page_endtime'=> '结束时间',
            'share_title'=> '分享标题',
            'share_link'=> '分享链接',
            'share_img'=> '分享图标',
            'share_desc'=> '分享描述',
            'share_title_gift'=> '转赠分享标题',
            'share_link_gift'=> '转赠分享链接',
            'share_img_gift'=> '转赠分享图标',
            'share_desc_gift'=> '转赠分享描述',
            'is_invoice'=> '可否开发票?',
            'shipping_desc'=> '邮寄说明',
			'sort'=> '优先级',
			'status'=> '状态',
		    
            'freeship_level'=> '包邮价格',
            'shipment_fee'=> '固定邮费',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
            'topic_id',
            'inter_id',
            'hotel_id',
            'identity',
			'page_theme',
			'page_title',
			'shipment_fee',
			'freeship_level',
			'status',
		);
	}

    const STATUS_T = 0;
    const STATUS_F= 1;
    
    public function get_status_label()
    {
        return array(
            self::STATUS_T => '正常',
            self::STATUS_F => '隐藏',
        );
    }

	const THEME_DEFAULT= 'default';
	const THEME_LESS   = 'less';
	const THEME_MULTI  = 'multi';
	const THEME_SUIT   = 'suit';
	const THEME_CARD   = 'card';
	
	//数组定义可见的公众号所属管理员，其他账号看不到该皮肤
	public function get_theme_whitelist()
	{
	    return array(
	        self::THEME_CARD => array(
	            'a453956624', 'a429262687',
	        ),
	    );
	}
	
	public function get_theme_option($themes)
	{
	    $array= array();
	    foreach ($themes as $v){
	        switch ($v) {
	            case self::THEME_CARD:
	                $array[$v]= '卡购专用';
	                break;
	            case self::THEME_SUIT:
	                $array[$v]= '商城 简约版';
	                break;
	            case self::THEME_MULTI:
	                $array[$v]= '商城（多分类）';
	                break;
	            case self::THEME_LESS:
	                $array[$v]= '列表（多商品）';
	                break;
	            case self::THEME_DEFAULT:
	                $array[$v]= '专题（单商品）';
	                break;
	            default:
	                $array[$v]= $v;
	                break;
	        }
	    }
	    return $array;
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

	    $this->load->model('mall/shp_category');
	    $cats= $this->shp_category->get_cat_tree_option();

	    /** 获取本管理员的酒店权限  */
	    $hotels_hash= $this->get_hotels_hash();
	    $publics = $hotels_hash['publics'];
	    $hotels = $hotels_hash['hotels'];
	    $filter = $hotels_hash['filter'];
	    $filterH = $hotels_hash['filterH'];
	    /** 获取本管理员的酒店权限  */
	    	  
	    $themes= array();
	    $theme_path= APPPATH. 'view'. DS. 'front'. DS. 'mall'. DS. 'wap'. DS;
	    $dirHandle= @opendir($theme_path);
	    if($dirHandle ) {
	        while( ($file= readdir($dirHandle))!==false ) {
	            $ext= explode('.', $file);
	            if($file==='.' || $file==='..' || $file==='.svn' ) {
	                continue;
	                 
	            } else {
	                $themes[$file]= $file;
	            }
	        }
	    }
	    $themes= $this->get_theme_option($themes);
	    $whitelist= $this->get_theme_whitelist();
	    foreach ($whitelist as $k=>$v){
	        if( $this->_admin_inter_id== FULL_ACCESS ) continue;
	        if( !in_array($this->_admin_inter_id, $v) ){
	            unset($themes[$k]);
	        }
	    }
	    
        $this->load->helper('common');
	    return array(
            'topic_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text', //textarea|text|combobox
            ),
            'identity' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> createNoncestr(6),
                'form_tips'=> '必须全局唯一，可填写数字与英文，用于配置专属访问链接',
                //'form_hide'=> TRUE,
                'type'=>'text', //textarea|text|combobox
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
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
            'page_theme' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                'form_default'=> 'multi',
                'form_tips'=> '选择最终商品的前端样式界面',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $themes,
            ),
            'theme_color' => array(
                'grid_ui'=> '',
                //'form_ui'=> ' disabled ',
                'form_default'=> '#eed700',
                'form_tips'=> '点击输入框点选颜色',
                //'form_hide'=> TRUE,
                'type'=>'color',
            ),
            'theme_image' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_tips'=> '选择专题的背景图案',
                'type'=>'logo',	//textarea|text|combobox
            ),
            'page_title' => array(
                'grid_ui'=> '',
                'grid_width'=> '15%',
                'form_ui'=> ' maxlength="10" ',
                'form_default'=> '',
                'form_tips'=> '分享商城时的标题内容，限制8个字为佳',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'page_starttime' => array(
                'grid_ui'=> '',
                'grid_width'=> '15%',
                //'form_ui'=> ' disabled ',
                'form_default'=> date('Y-m-d H:i:s', time()),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'datetime',	//textarea|text|combobox
            ),
            'page_endtime' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> date('Y-m-d H:i:s', strtotime('+30 days')),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'datetime',	//textarea|text|combobox
            ),
            'share_title' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '精选大牌，限时抢购',
                'form_tips'=> '分享到朋友圈标题内容',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'share_link' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> 'http://',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'url',	//textarea|text|combobox
            ),
            'share_img' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'logo',	//textarea|text|combobox
            ),
            'share_desc' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' maxlength="20" ',
                'form_default'=> '每天精选推荐，无限特价商品就等你来享！',
                'form_tips'=> '分享商城时的主要内容，限制16个字为佳',
                //'form_hide'=> TRUE,
                'type'=>'text',
            ),
            'share_title_gift' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '送您一份小礼物',
                'form_tips'=> '分享到好友的标题内容，会自动追加分享人昵称',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'share_link_gift' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> 'http://',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'url',	//textarea|text|combobox
            ),
            'share_img_gift' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'logo',	//textarea|text|combobox
            ),
            'share_desc_gift' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' maxlength="20" ',
                'form_default'=> '小声告诉你，嘘！已经付过钱了，全国包邮，快快领取吧！',
                'form_tips'=> '分享商城时的主要内容，限制16个字为佳',
                //'form_hide'=> TRUE,
                'type'=>'text',
            ),
            'is_invoice' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '1',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox',
	            'select'=> $base_util::get_status_options_(),
            ),
            'sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '7%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '0',
                'form_tips'=> '优先级越大越高',
                //'form_hide'=> TRUE,
                'type'=>'number',	//textarea|text|combobox
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '7%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
	            'select'=> self::get_status_label(),
            ),
            'shipping_desc' => array(
                'grid_ui'=> '',
                'form_default'=> '全国包邮；港澳台、新疆、西藏除外',
                'form_tips'=> '支付确认前提示邮寄注意信息',
                'type'=>'textarea',
            ),
            'freeship_level' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_default'=> '99.00',
                'form_tips'=> '订单达到此金额即免邮',
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',
            ),
            'shipment_fee' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_default'=> '10.00',
                'form_tips'=> '未达到免邮金额的订单收取邮费',
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'topic_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	/**
	 * 保存数据的同时保存 专题到 商品，到广告图的映射关系
	 * @see MY_Model::m_save()
	 */
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

            
            $this->_db()->query("delete from {$prefix}shp_topic_advs where topic_id={$this->_data[$pk]}");
            $this->_db()->query("delete from {$prefix}shp_topic_category where topic_id={$this->_data[$pk]}");
            $this->_db()->query("delete from {$prefix}shp_topic_goods where topic_id={$this->_data[$pk]}");
            
            if(isset($data['adv_ids'])){
                $idata= implode(',', $data['adv_ids']);
                $sql= "insert into {$prefix}shp_topic_advs (`topic_id`, `adv_id`, `sort`) 
                        select {$this->_data[$pk]}, `id`, `sort` from {$prefix}shp_advs where id in ($idata)";
                //echo $sql;die;
                $this->_db()->query($sql);
            }
            if(isset($data['good_ids'])){
                $idata= implode(',', $data['good_ids']);
                $sql= "insert into {$prefix}shp_topic_goods (`topic_id`, `gs_id`, `sort`) 
                        select {$this->_data[$pk]}, `gs_id`, `gs_sort` from {$prefix}shp_goods where gs_id in ($idata)";
                $this->_db()->query($sql);
            }
            if(isset($data['category_ids'])){
                $idata= implode(',', $data['category_ids']);
                $sql= "insert into {$prefix}shp_topic_category (`topic_id`, `cat_id`, `sort`) 
                        select {$this->_data[$pk]}, `cat_id`, `cat_sort` from {$prefix}shp_category where cat_id in ($idata)";
                $this->_db()->query($sql);
            }
            
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

            if($last_id){
                $this->_db()->query("delete from {$prefix}shp_topic_advs where topic_id={$last_id}");
                $this->_db()->query("delete from {$prefix}shp_topic_category where topic_id={$last_id}");
                $this->_db()->query("delete from {$prefix}shp_topic_goods where topic_id={$last_id}");
                
                if(isset($data['adv_ids'])){
                    $idata= implode(',', $data['adv_ids']);
                    $sql= "insert into {$prefix}shp_topic_advs (`topic_id`, `adv_id`, `sort`) 
                            select {$last_id}, `id`, `sort` from {$prefix}shp_advs where id in ($idata)";
                    $this->_db()->query($sql);
                    //echo $sql;die;
                }
                if(isset($data['good_ids'])){
                    $idata= implode(',', $data['good_ids']);
                    $sql= "insert into {$prefix}shp_topic_goods (`topic_id`, `gs_id`, `sort`) 
                            select {$last_id}, `gs_id`, `gs_sort` from {$prefix}shp_goods where gs_id in ($idata)";
                    $this->_db()->query($sql);
                }
                if(isset($data['category_ids'])){
                    $idata= implode(',', $data['category_ids']);
                    $sql= "insert into {$prefix}shp_topic_category (`topic_id`, `cat_id`, `sort`) 
                            select {$last_id}, `cat_id`, `cat_sort` from {$prefix}shp_category where cat_id in ($idata)";
                    $this->_db()->query($sql);
                }
            }
            
            if ($this->_db()->trans_status() === FALSE) {
                $this->_db()->trans_rollback();
                return FALSE;

            } else {
                $this->_db()->trans_commit();
                return TRUE;
            }
        }
    }

    public function get_topic_link($table='goods', $where_string='')
    {
        $pk= $this->table_primary_key();
        $prefix= $this->_db()->dbprefix;
        
        if( isset($this->_data[$pk]) && $this->_data[$pk]>0 ) {
            if($where_string) $where_string= " and {$where_string}";
            if($table== 'advs'){
                $sql= "select g.* from {$prefix}shp_topic_advs as l left join {$prefix}shp_advs as g"
                    ." on l.adv_id=g.id where l.topic_id=". $this->_data[$pk]. $where_string. ' order by sort desc';
//echo $sql;die;
            } else if($table== 'goods'){
                $sql= "select g.* from {$prefix}shp_topic_goods as l left join {$prefix}shp_goods as g"
                    ." on l.gs_id=g.gs_id where l.topic_id=". $this->_data[$pk]. $where_string. ' order by gs_sort desc';
//echo $sql;die;
            } else if($table== 'category') {
                $sql= "select g.* from {$prefix}shp_topic_category as l left join {$prefix}shp_category as g"
                    ." on l.cat_id=g.cat_id where l.topic_id=". $this->_data[$pk]. $where_string. ' order by cat_sort desc';
            }

            $result= $this->_db()->query($sql)->result_array();
            return $result;
    
        } else {
            return array();
        }
    }

    public function get_default_topic($params)
    {
        $table= $this->table_name();
        $where= array();
        $dbfields= array_values($fields= $this->_db()->list_fields($table));
        foreach ($params as $k=>$v){
            //过滤非数据库字段，以免产生sql报错
            if(in_array($k, $dbfields)) $where[$k]= $v;
        }
        $result= $this->_db()->select(" * ")->order_by("`sort` DESC")
            ->limit(1)->get_where($table, $where)
            ->result_array();
        return $result;
    }


}
