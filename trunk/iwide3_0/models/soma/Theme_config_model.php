<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_config_model extends MY_Model_Soma {

    const SOMA_THEME_REDIS = 'Soma_theme';
    //const REDIS_DB = 2;

	public function get_resource_name()
	{
		return 'Theme_model';
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
        return $this->_shard_table('soma_theme', $inter_id);
    }

    public function table_name_using_theme()
    {
        return 'soma_theme_use';
    }

	public function table_primary_key()
	{
	    return 'theme_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'theme_id'=> '皮肤ID',
            'inter_id'=> '公众号',
            'theme_name'=> '皮肤名称',
            'theme_path'=> '皮肤路径',
            'thumbnail'=> '皮肤缩略图',
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
            'theme_id',
            'theme_name',
            'inter_id',
            // 'theme_path',
            'thumbnail',
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
            'theme_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'theme_name' => array(
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
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $publics,
            ),
            'theme_path' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'thumbnail' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_cat_img|100|',
                'type'=>'logo', //textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $Somabase_util::get_status_options(),
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'theme_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    public function get_themes( $inter_id=NULL )
    {
        $db = $this->_shard_db_r('iwide_soma_r');
        if( $inter_id == FULL_ACCESS ){

        }else{
            $db->where( "(inter_id = '".$inter_id."' or inter_id is NULL)" );
        }

        $filter = array();
        $filter['status'] = self::STATUS_TRUE;
        $table_name = $this->table_name( $inter_id );
        return $db->where( $filter )
                    ->get( $table_name )
                    ->result_array();
    }

	//获取皮肤详情
    public function get_theme_detail( $themeIds, $inter_id )
    {
        $table_name = $this->table_name( $inter_id );
        return $this->_shard_db_r('iwide_soma_r')
                    ->where_in( 'theme_id', $themeIds )
                    ->get( $table_name )
                    ->result_array();
    }

    //当前正在用的theme，如果为空，则返回' default '
    public function get_using_theme($inter_id)
    {
        $this->init_service();
        $redisKey = self::SOMA_THEME_REDIS.":".$inter_id;
        $redis= $this->redis;

        $success = Soma_base::inst()->check_cache_redis();
        if( $success ){
            // $this->init_service();
            // $redisKey = self::SOMA_THEME_REDIS.":".$inter_id;
            
            // $redis= $this->redis;
            $redisThemeConfig =  $redis->get($redisKey);
            
            if($redisThemeConfig){
                return json_decode($redisThemeConfig,true);
            }
        }
        $table = $this->table_name_using_theme();
        $filter = array(
            'inter_id' => $inter_id
        );
        $themes = $this->_shard_db_r('iwide_soma_r')
                        ->where($filter)
                        ->get($table)
                        ->row_array();

        if(empty($themes) || !isset($themes['theme_path']) || empty($themes['theme_path'] )){
            $returnThemes =  array('theme_path'=>'default');
        }else{
            $returnThemes = $themes;
        }
        
        if( $success ){
            $redis->set($redisKey, json_encode($returnThemes));
        }
        return $returnThemes;
    }

    /**
     * 后台更新皮肤的时候刷新此方法
     * @param String $inter_id
     * @param Array $themeConfig
     */
    public function update_redis_theme($inter_id,$themeConfig)
    {
        $this->init_service();

        $redisKey = self::SOMA_THEME_REDIS.":".$inter_id;

        $redis= $this->redis;
        $redis->getSet($redisKey, json_encode($themeConfig));

    }

    /**
     * 初始化redis实例
     * @return Statis_sales_model
     */
    public function init_service()
    {
        // $cache= $this->_load_cache();
        // //$cache->redis->select_db(self::REDIS_DB);  //由redis.php 配置文件自动识别哪个库
        // $this->redis= $cache->redis->redis_instance();
        $this->redis = $this->get_redis_instance();
        return $this;
    }

    /**
     * 加载缓存组件
     * @see MY_Controller::_load_cache()
     */
    protected function _load_cache( $name='Cache' )
    {
        if(!$name || $name=='cache') //不能为小写cache
        $name='Cache';
        $CI = & get_instance();
        $CI->load->driver('cache',
            array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'soma_'),
            $name
        );
        return $CI->$name;
    }
	
}
