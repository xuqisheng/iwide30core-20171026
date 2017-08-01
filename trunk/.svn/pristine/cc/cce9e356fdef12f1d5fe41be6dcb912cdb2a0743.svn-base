<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Center_activity_adv_model extends MY_Model_Soma {

	public function get_resource_name()
	{
		return '活动预告设置';
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
		return 'iwide_soma_center_catalog_adv';
	}

	public function table_primary_key()
	{
	    return 'adv_id';
	}

	public function attribute_labels()
	{
	    return array(
	        'adv_id'=> 'ID',
            'inter_id'=> '公众号',
	        'hotel_id'=> '酒店ID',
            'hotel_inter_id'=> '酒店公众号',
            'hotel_hotel_id'=> '酒店ID',
	        'name'=> '名称',
	        'type'=>'类型',
	        'cat_id'=> '分类',
	        'product_id'=> '链接产品',
	        'logo'=> '焦点图',
	        'link'=> '自定义链接',
	        'sort'=> '排序',
            'remark'=>'备注',
	        'status'=>'状态',
	    );
	}
	
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
	        'adv_id',
            // 'inter_id',
	        'hotel_inter_id',
	        // 'hotel_id',
	        // 'name',
	        'type',
// 	        'cat_id',
	        // 'product_id',
	        'logo',
	        'link',
	        'sort',
            // 'remark',
	        'status',
	    );
	}
	
	const PRODUCT_TYPE  = 1;//产品广告
	const CATEGORY_TYPE = 2;//分类广告
    const LINK_TYPE = 3;//链接广告
	const ACTIVITY_NOTICE_TYPE = 4;//活动预告
	
	public function get_position_array()
	{
	    return array(
	        // self::PRODUCT_TYPE => '产品广告',
// 	        self::CATEGORY_TYPE => '分类广告',
            // self::LINK_TYPE => '链接广告',
	        self::ACTIVITY_NOTICE_TYPE => '活动预告',
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
	    
	    //获取该公众号列表
        $this->load->model('wx/publics_model');
        $interIds= $this->publics_model->get_public_hash();
        $interIds= $this->publics_model->array_to_hash($interIds, 'name', 'inter_id');
	     
	    return array(
            'adv_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $this->get_position_array(),
            ),
            'cat_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_tips'=> '优先级最高',
                'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> array(),
            ),
            'product_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_tips'=> '优先级仅次于分类',
                'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> array(),
            ),
            'link' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> 'http://',
                'form_tips'=> '优先级次于分类,产品',
                //'form_tips'=> '暂不允许外链，链接不带域名：如“/public/medis/123.jpg”',
                // 'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'text',
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $publics,
            ),
            'hotel_inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $interIds,
            ),
            'hotel_hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'logo' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_admin_head|100',
                'type'=>'logo',
            ),
            'sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'number',   //textarea|text|combobox
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',	//textarea|text|combobox
                'select'=> $Somabase_util::get_status_options(),
            ),
            'remark' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_tips'=> '可以备注为哪个公众号',
                'form_hide'=> TRUE,
                'type'=>'text', //textarea|text|combobox
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'adv_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    /**
     * @param $category
     */
    function get_adv_list( $filter, $orderby='adv_id DESC', $limit=8 ) 
    {
        if( count( $filter ) > 0 ){
            foreach ($filter as $k=>$v) {
                if( is_array($v) ){
                    $this->_shard_db()->where_in($k, $v);
                } else {
                    $this->_shard_db()->where($k, $v);
                }
            }
        }

        //取出条数
        $limit = isset( $limit ) && !empty( $limit ) ? $limit : 8;//默认取八张
        if( $limit ){
            $startNum = 0;//从哪条开始取
            $limitNum = $limit + 0;//取多少条
            $this->_shard_db()->limit( $limitNum, $startNum );
        }
        
        $table_name = $this->table_name();
        return $this->_shard_db()
                    ->where( 'status', self::STATUS_TRUE )
                    ->order_by( $orderby )
                    ->get ( $table_name )
                    ->result_array();
    }

}
