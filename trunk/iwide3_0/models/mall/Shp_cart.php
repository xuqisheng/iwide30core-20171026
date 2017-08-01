<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Shp_cart extends MY_Model_Mall {

	public function get_resource_name()
	{
		return 'shp_cart';
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
		return 'shp_cart';
	}

	public function table_primary_key()
	{
	    return 'cart_id';
	}

	const STATUS_DEFAULT= '0';
	const STATUS_DELETE = '1';
	const STATUS_SETTLE = '2';
	
	public function attribute_labels()
	{
		return array(
            'cart_id'=> 'ID',
            'openid'=> 'Openid',
            'gs_id'=> '商品ID',
            'nums'=> '购买数量',
			'hotel_id'=> '酒店ID',
			'inter_id'=> '公众号',
            'add_time'=> '更改时间',
            'status'=> '状态',
            'attrs'=> 'Attrs',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
            'cart_id',
            'openid',
            'gs_id',
            'nums',
            'hotel_id',
            'inter_id',
            'status',
            'add_time',
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
            'cart_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'function'=> 'hide_string_prefix|6',
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
            'nums' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
	            'select'=> $base_util::get_status_options_(),
            ),
            'add_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '14%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'attrs' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'cart_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	 
/*
public function get_cart_product_($openid, $inter_id, $hotel_id)
{
    $base_obj= EA_base::inst();
    $this->_db()->where(array (
        'openid'   => $openid,
        'inter_id' => $inter_id,
        'hotel_id' => $hotel_id,
        'status'   => $base_obj::STATUS_TRUE_,
    ) );
    return $this->dn->get('shp_cart');
}
*/	


	public function get_cart_products($openid, $inter_id, $hotel_id)
	{
	    $base_obj= EA_base::inst();
	    $sql = 'SELECT sg.*,sc.nums FROM (SELECT * FROM iwide_shp_cart 
	        WHERE inter_id=? AND hotel_id=? AND openid=? AND `status`='. $base_obj::STATUS_TRUE_
	        .') sc LEFT JOIN iwide_shp_goods sg ON sc.gs_id=sg.gs_id';
        //echo $sql;die;
	    return $this->_db()->query($sql,array($inter_id,$hotel_id,$openid));
	}
	
	/**
	 * 查询购物车产品数量
	 * @param $openid
	 * @param $inter_id
	 * @param $hotel_id
	 * @return int
	 */
	public function get_cart_product_count($openid, $inter_id, $hotel_id)
	{
	    $base_obj= EA_base::inst();
	    $sql = 'SELECT SUM(nums) counts FROM iwide_shp_cart 
	        WHERE inter_id=? AND hotel_id=? AND openid=? AND `status`='. $base_obj::STATUS_TRUE_;
	    $count_result = $this->_db()->query($sql, array($inter_id, $hotel_id, $openid) )->row_array ();
	    return $count_result ['counts'];
	}
	/**
	 * 添加产品到购物车
	 * @param unknown $openid
	 * @param unknown $inter_id
	 * @param unknown $hotel_id
	 * @param unknown $product_id
	 * @param unknown $attrs
	 * @param unknown $nums
	 * @return boolean
	 */
	public function add_to_cart($openid, $inter_id, $hotel_id, $product_id, $attrs, $nums)
	{
	    $base_obj= EA_base::inst();
	    $data= array (
	        'openid'   => $openid,
	        'inter_id' => $inter_id,
	        'hotel_id' => $hotel_id,
	        'gs_id'    => $product_id,
	        'status'   => $base_obj::STATUS_TRUE_,
	    );
	    $this->_db()->where($data);
	    //print_r($data);die;
	    $query = $this->_db()->get('shp_cart' );
	    
	    if($query->num_rows() > 0){
	        //数量叠加
	        $this->_db()->where(array (
	            'openid'   => $openid,
	            'inter_id' => $inter_id,
	            'hotel_id' => $hotel_id,
	            'gs_id'    => $product_id,
	            'status'   => $base_obj::STATUS_TRUE_,
	        ) ); 
	        //print_r($data);die;
	        $query = $query->row_array();
	        return $this->_db()->update('shp_cart', array('nums'=> $query['nums'] + $nums) );
	        
	    } else {
	        //创建新纪录
	        return $this->_db()->insert ('shp_cart', array (
	            'openid'   => $openid,
	            'inter_id' => $inter_id,
	            'hotel_id' => $hotel_id,
	            'gs_id'    => $product_id,
	            'add_time' => date('Y-m-d H:i:s' ),
	            'nums'     => $nums,
	            'status'   => $base_obj::STATUS_TRUE_,
	            'attrs'    => $attrs,
	        ) ) > 0;
	    }
	}
	/**
	 * 将购物车条目标记为状态禁用（不直接删除）
	 * @return boolean
	 */
	public function del_from_cart($openid, $inter_id, $hotel_id, $product_id=NULL)
	{
	    $base_obj= EA_base::inst();
	    $filter= array (
	        'openid'   => $openid,
	        'inter_id' => $inter_id,
	        'hotel_id' => $hotel_id,
	    );
	    if($product_id) $filter['gs_id']= $product_id;
	    $this->_db()->where($filter);
	    return $this->_db()->update('shp_cart', array (
	        'status'   => $base_obj::STATUS_FALSE_,
	        'add_time' => date('Y-m-d H:i:s' ),
	    ) ) > 0;
	}
	/**
	 * 将购物车条目标记为状态禁用（不直接删除）
	 * @return boolean
	 */
	public function flush_cart($openid, $inter_id, $hotel_id)
	{
	    $base_obj= EA_base::inst();
	    $filter= array (
	        'openid'   => $openid,
	        'inter_id' => $inter_id,
	        'hotel_id' => $hotel_id,
	    );
	    $this->_db()->where($filter);
	    return $this->_db()->delete($this->table_name());
	}
	
	/**
	 * 更新购物车条目标
	 * @return boolean
	 */
	function update_cart($openid, $inter_id, $hotel_id, $product_id, $nums)
	{
	    $this->_db()->where(array (
	        'openid'   => $openid,
	        'inter_id' => $inter_id,
	        'hotel_id' => $hotel_id,
	        'gs_id'    => $product_id,
	    ) );
	    return $this->_db()->update(array (
	        'add_time' => date('Y-m-d H:i:s' ),
	        'nums'     => $nums
	    ) ) > 0;
	}
	
}
