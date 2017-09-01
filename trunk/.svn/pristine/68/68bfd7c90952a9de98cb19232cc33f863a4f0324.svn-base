<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_reserve_product_model extends MY_Model_Soma {

    const ENABLE_STATUS = 1;
    const DISABLED_STATUS = 2;

	public function get_resource_name()
	{
		return 'Soma_sales_reserve_product';
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
		return 'soma_sales_reserve_product';
	}

	public function table_primary_key()
	{
	    return 'reserve_product_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'reserve_product_id'=> 'ID',
            'inter_id'=> '公众号ID',
            'hotel_id'=> '酒店ID',
            'hotel_name'=> '酒店名',
            'product_id'=> '商品ID',
            'sku'=> 'Sku',
            'product_name'=> '商品名',
            'product_img_detail'=> '图文详情',
            'product_face_img'=> '封面图片',
            'on_sales'=> '是否上架',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'reserve_product_id',
            'inter_id',
            'hotel_id',
            'hotel_name',
            'product_id',
            'sku',
            'product_name',
            //'product_img_detail',
            'product_face_img',
            'on_sales',
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
            'reserve_product_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'hotel_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'product_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'sku' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'product_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'product_img_detail' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'product_face_img' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                'grid_function'=> 'show_cat_img|100|',
                'type'=>'logo',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'on_sales' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select' => array(
                    '1' => '已上架',
                    '2' => '已下架',
                ),
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'reserve_product_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    public function get_product_ids($inter_id, $filter = array()) {
        $table = $this->table_name( $inter_id );
        if(!isset($filter['inter_id'])) { $filter['inter_id'] = $inter_id; }
        $result = $this->_shard_db_r('iwide_soma_r')
                        ->get_where($table, $filter)
                        ->result_array();
        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row['product_id'];
        }
        return $ids;
    }

    public function save_batch($products, $hotels) {
        
        // var_dump($products);exit;

        foreach ($hotels as $h) {
            $hotel_id_name[$h['hotel_id']] = $h['name'];
        }

        $insert_data = array();
        $now_time = date('Y-m-d H:i:s');
        foreach ($products as $k => $v) {
            foreach ($v as $p) {
                $row = array();
                $row['inter_id'] = $p['inter_id'];
                $row['hotel_id'] = $p['hotel_id'];
                $row['hotel_name'] = $hotel_id_name[$p['hotel_id']];
                $row['product_id'] = $p['product_id'];
                $row['sku'] = $p['sku'];
                $row['product_name'] = $p['name'];
                $row['product_img_detail'] = $p['img_detail'];
                $row['product_face_img'] = $p['face_img'];
                $row['create_time'] = $now_time;
                $row['update_time'] = $now_time;
                $row['on_sales'] = self::ENABLE_STATUS;
                if($k == 'insert') { 
                    $insert_data[] = $row;
                } else {
                    $update_data[] = $row;
                }

            }
        }

        // 添加新数据
        if( !empty( $insert_data ) ){
            $this->_shard_db()->insert_batch( $this->table_name(), $insert_data );
        }
        // 更新数据
        if( !empty( $update_data ) ) {
            $this->_shard_db()->update_batch( $this->table_name(), $update_data, 'product_id');
        }

    }

    /**
     * 根据公众号ID获取产品列表
     * 
     * @param  string $inter_id 公众号ID
     * @param  string $hotel_id 酒店ID（暂时没用）
     * @return array            一组产品信息
     */
    public function get_product_list($inter_id, $hotel_id = null) {
        $filter = array('inter_id' => $inter_id);
        if($hotel_id != null) { $filter['hotel_id'] = $hotel_id; }
        $table = $this->table_name( $inter_id );
        $id_res = $this->_shard_db_r('iwide_soma_r')
                        ->select('product_id')
                        ->where($filter)
                        ->get($table)
                        ->result_array();
                // ->get_where($table, $filter)
                // ->result_array();
        $ids = array();
        foreach ($id_res as $row) { $ids[] = $row['product_id']; }

        $this->load->model('soma/Product_package_model', 'ppm');
        $result = $this->ppm->get_product_package_by_ids($ids, $inter_id);

        return $result;
    }

    /**
     * 获取产品信息
     * @param  string $inter_id   公众号ID
     * @param  int    $product_id 产品ID
     * @return mix                成功返回产品信息，失败返回FALSE
     */
    public function get_product_detail($inter_id, $product_id) {
        // $filter = array('product_id' => $product_id);
        // $table = $this->table_name( $inter_id );
        // $result = $this->_shard_db()
        //         ->get_where($table, $filter)
        //         ->result_array();
        // if(count($result) != 1) {
        //     return FALSE;
        // }
        $this->load->model('soma/Product_package_model', 'ppm');
        $result = $this->ppm->get_product_package_by_ids(array($product_id), $inter_id);
        return $result[0];
    }
	
}
