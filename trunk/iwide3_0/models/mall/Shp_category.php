<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Shp_category extends MY_Model_Mall {

	public function get_resource_name()
	{
		return '商品分类';
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
		return 'shp_category';
	}

	public function table_primary_key()
	{
	    return 'cat_id';
	}
	
	public function attribute_labels()
	{
		return array(
			'cat_id'=> '分类ID',
			'cat_name'=> '分类名称',
			'cat_keyword'=> '关键词',
			'cat_desc'=> '分类描述',
			'cat_sort'=> '排序',
			'parent_id'=> '父分类',
			'hotel_id'=> '酒店ID',
			'inter_id'=> '公众号',
			'cat_img'=> '分类图标',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
			'cat_id',
			'cat_img',
			'cat_name',
			'cat_keyword',
			'cat_desc',
			'cat_sort',
			'parent_id',
			'hotel_id',
			'inter_id',
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
		$parents= $this->get_cat_tree_option();

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
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'cat_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '15%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'cat_img' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_cat_img|50',
                'type'=>'logo',	//textarea|text|combobox
            ),
            'cat_keyword' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'cat_desc' => array(
                'grid_ui'=> '',
                'grid_width'=> '15%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'textarea',	//textarea|text|combobox
            ),
            'cat_sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'number',	//textarea|text|combobox
            ),
            'parent_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '15%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_tips'=> '选择分类级别',
                //'form_hide'=> TRUE,
	            'type'=>'combobox',
	            'select'=> $parents,
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
	
}
