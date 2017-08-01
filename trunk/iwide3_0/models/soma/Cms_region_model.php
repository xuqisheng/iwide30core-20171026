<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Cms_region_model extends MY_Model_Soma {

	const REGION_COUNTRY = 1;//代表中国

    /**
     *
     */
    const ENABLED_STATUS = 1;

	public function get_resource_name()
	{
		return 'Cms_region';
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
		return $this->_shard_table('soma_cms_region', $inter_id);
	}

	public function table_primary_key()
	{
	    return 'region_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'region_id'=> 'Region_id',
            'parent_id'=> 'Parent_id',
            'region_name'=> 'Region_name',
            'region_type'=> 'Region_type',
            'status'=> 'Status',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'region_id',
            'parent_id',
            'region_name',
            'region_type',
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

	    return array(
            'region_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'parent_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'region_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'region_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'region_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	//获取省市区列表
	public function get_region_list( $parentId=NULL, $interId=NULL )
	{

		//parentId为空，则获取国家

		$parentId = empty( $parentId ) ? '0' : $parentId + 0;
		$interId = empty( $interId ) ? $this->session->get_admin_inter_id() : $interId;

		$filter = array();
		$filter['parent_id'] = $parentId;//0代表中国

		$table_name = $this->table_name( $interId );
		return $this->_shard_db_r('iwide_soma_r')->where( $filter )->get( $table_name )->result_array();
	}

	//获取省列表
	public function get_provinces( $interId=NULL )
	{
		$parentId = self::REGION_COUNTRY;
		return $this->get_region_list( $parentId, $interId );
	}

	//获取市列表
	public function get_citys( $provinceId=NULL, $interId=NULL )
	{
		$parentId = $provinceId + 0;
		return $this->get_region_list( $parentId, $interId );
	}

	//获取区列表
	public function get_regions( $cityId=NULL, $interId=NULL )
	{
		$parentId = $cityId + 0;
		return $this->get_region_list( $parentId, $interId );
	}

	//获取详情
	public function get_region_detail( $regionId=NULL, $interId=NULL  )
	{
		if( !$regionId ){
			return FALSE;
		}

		$filter = array();
		$filter['region_id'] = $regionId + 0;

		$table_name = $this->table_name( $interId );
		return $this->_shard_db_r('iwide_soma_r')->where( $filter )->get( $table_name )->row_array();
	}

	//组装省市区地址详情
	public function get_address_detail( $provinceId, $cityId, $regionId )
	{
		$province = $this->get_region_detail( $provinceId );
		$city = $this->get_region_detail( $cityId );
		$region = $this->get_region_detail( $regionId );
		return $province['region_name'].$city['region_name'].$region['region_name'];
	}

    /**
     * 国家省市区列表
     * @author daikanwu
     *
     * @return array
     */
    public function all()
    {
        $table_name = $this->table_name();
        $filter['status'] = self::ENABLED_STATUS;
		return $this->soma_db_conn_read->select('region_id,parent_id,region_name')->where( $filter )->get( $table_name )->result_array();
    }




}
