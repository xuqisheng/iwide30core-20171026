<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

//套票商品分类管理
class Category_package_model extends MY_Model_Soma {

    const CUSTOM  = 1;
    const LOCATION = 2;
    
    public function get_sort_label()
    {
        return array(
            self::CUSTOM=>'自定义',
            self::LOCATION=>'地理位置',
        );
    }

	public function get_resource_name()
	{
		return '套票商品分类';
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
		return $this->_shard_table('soma_catalog_category_package', $inter_id);
	}
    public function table_name_r($inter_id=NULL)
    {
        return $this->_shard_table_r('soma_catalog_category_package', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'cat_id';
	}
	
	public function attribute_labels()
	{
		return array(
    		'cat_id' => '序列号',
			'inter_id' => '公众号',
			'hotel_id' => '酒店',
			'cat_name' => '分类名',
            'cat_name_en' => 'Name',
			'cat_img' => '分类图标',
			'cat_keyword' => '关键词',
			'cat_desc' => '描述',
			'cat_sort' => '分类排序',
			'parent_id' => '父ID',
			'sort' => '排序方式',
			'status' => '状态',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
    		'cat_id',
    		'inter_id',
    		'hotel_id',
    		'cat_name',
    		'cat_img',
    		'cat_keyword',
            // 'cat_desc',
    		// 'cat_sort',
            // 'parent_id',
    		'sort',
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
	    
        $inter_id = $this->session->get_admin_inter_id();
	    $parents= $this->get_cat_tree_option( $inter_id );
	    
	    $parents= array('0'=>'【根分类】')+ $parents;
	    
	    /** 获取本管理员的酒店权限  */
	    $hotels_hash= $this->get_hotels_hash();
	    $publics = $hotels_hash['publics'];
	    $hotels = $hotels_hash['hotels'];
	    $filter = $hotels_hash['filter'];
	    $filterH = $hotels_hash['filterH'];
	    /** 获取本管理员的酒店权限  */

	    return array(
            'cat_id' => array(
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
	        'cat_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'cat_name_en' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
	        'cat_img' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_cat_img|100|',
                'type'=>'logo',	//textarea|text|combobox|number|email|url|price
            ),
	        'cat_keyword' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
	        'cat_desc' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'textarea',	//textarea|text|combobox|number|email|url|price
            ),
	        'cat_sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number',	//textarea|text|combobox|number|email|url|price
            ),
	        'parent_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
	            'select'=> $parents,
            ),
	        'sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=>$this->get_sort_label(),
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
                'select'=> $Somabase_util::get_status_options(),
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'cat_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	public function get_cat_tree_option($inter_id=NULL, $hotel_id=NULL)
	{
        $base_filter= array();
        if($inter_id) $base_filter['inter_id']=  $inter_id;
        if($hotel_id) $base_filter['hotel_id']=  $hotel_id;
	    $array= array();
        //$array['_'. $k]= '+'. $v['label'];
        //
        $tmp= $this->get_data_filter(array('parent_id'=> '0' )+ $base_filter);

        //print_r($tmp);die;
        foreach ($tmp as $sv){
            $array[$sv['cat_id']]= '+'. $sv['cat_name'];
            $tmp2= $this->get_data_filter(array('parent_id'=> $sv['cat_id'])+ $base_filter );
            //print_r($array);die;
            foreach ($tmp2 as $ssv) {
                $array[$ssv['cat_id']]= '+---'. $ssv['cat_name'];
            }
        }
	    //print_r($array);die;
	    return $array;
	}

    public function url_cat_img($filename)
    {
        $path=  '/'. FD_PUBLIC. '/mall/common/cat_img/';
        return $path. $filename;
    }
    public function get_cat_img()
    {
        $path= FRONT_FD_. 'mall'. DS. 'common'. DS. 'cat_img'. DS;
        $dirHandle= @opendir($path);
        $array= array();
        if($dirHandle ) {
            while( ($file= readdir($dirHandle))!==false ) {
                if($file==='.' || $file==='..' || $file==='.svn' ) {
                    continue;
                } else {
                    $array[]= $file;
                }
            }
        }
        closedir($dirHandle);  
        return $array;
    }

    //获取套票分类列表
    public function get_package_category_list( $interId = NULL  , $pageNum= NULL, $listNum= NULL, $catId= NULL )
    {
        //分页
        $pageNum = empty($pageNum)? 1: $pageNum;

        //每页数目
        $listNum = empty($listNum)? 20: $listNum;

        //计算从哪一条开始取
        $startNum = ( $pageNum - 1 ) * $listNum;

        $inter_id = empty($interId)?$this->session->get_admin_inter_id():$interId;

        $s = isset( $sort ) ? $sort : 'cat_sort DESC';

        $where = array();
        $where['inter_id'] = $inter_id;
        $where['status'] = parent::STATUS_TRUE;//取出非禁止状态下的分类
        if($catId) $where['cat_id']= intval($catId);

        // $table_name = $this->table_name( $inter_id );
        $table_name = $this->table_name_r( $inter_id );
        // $result= $this->_shard_db( $inter_id )->where( $where )->limit( $listNum, $startNum )->order_by($s)
        $result= $this->_shard_db_r('iwide_soma_r')->where( $where )->limit( $listNum, $startNum )->order_by($s)
            ->get( $table_name )->result_array();
        //echo $db->last_query();die;
        return $result;
    }



    public function category_package_list_by_catIds( $catIds, $inter_id, $sort='cat_sort DESC', $pageNum=NULL, $listNum=NULL )
    {
        if( !$catIds ){
            return array();
        }

        //分页
        $pageNum = empty($pageNum)? 1: $pageNum;
        //每页数目
        $listNum = empty($listNum)? 20: $listNum;
        //计算从哪一条开始取
        $startNum = ( $pageNum - 1 ) * $listNum;

        $db = $this->_shard_db_r('iwide_soma_r');
        foreach ($catIds as $k=>$v){
            if(is_array($v)){
                $db->where_in($k, $v );
            } else {
                $db->where($k, $v );
            }
        }

        $table_name = $this->table_name_r( $inter_id );
        $result = $db->where( 'inter_id', $inter_id )
                    ->limit( $listNum, $startNum )
                    ->order_by( $sort )
                    ->get( $table_name )
                    ->result_array();
        // var_dump( $db->last_query() );die;
        return $result;
    }


	/**
	 * 获取套票列表
	 * @param $inter_id
	 * @return array|string
	 * @author: liguanglong  <liguanglong@mofly.cn>
	 */
	public function getCatalog($inter_id){

		return $this->_shard_db_r()->where('inter_id', $inter_id)->get($this->table_name())->result_array();
	}


}
