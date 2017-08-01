<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_refund_item_package_model extends MY_Model_Soma {

	public function get_resource_name()
	{
		return 'Sales_refund_item_package_model';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function table_name($business='package', $inter_id=NULL)
    {
        return $this->_shard_table("soma_sales_refund_item_{$business}", $inter_id);
    }
	
	/**
	 * @return string the associated database table name
	 */
	// public function table_name()
	// {
	// 	return 'soma_sales_refund_item_package';
	// }

	public function table_primary_key()
	{
	    return 'item_id';
	}

    /**
     * 字段映射，key中字段将直接转移到item
     * @return multitype:string
     */
    public function product_item_field_mapping()
    {
        return array(
//             'item_id'=> 'item_id',
            'refund_id'=> 'refund_id',
            'order_item_id'=> 'order_item_id',
            'product_id'=> 'product_id',
            'sku'=> 'sku',
            'name'=> 'name',
            'qty'=> 'qty',
            'price'=> 'price',
            'refund_total'=> 'refund_total',
        );
    }
	
	public function attribute_labels()
	{
		return array(
            'item_id'=> 'Item_id',
            'refund_id'=> 'Refund_id',
            'order_item_id'=> '订单号',
            'product_id'=> '商品ID',
            'sku'=> 'Sku',
            'name'=> '商品名',
            'qty'=> 'Qty',
            'price'=> 'Price',
            'refund_total'=> 'Refund_total',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'item_id',
            'refund_id',
            'order_item_id',
            'product_id',
            'sku',
            'name',
            'qty',
            'price',
            'refund_total',
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
            'item_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'refund_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'order_item_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'product_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'sku' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'qty' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'price' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'refund_total' => array(
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
	    return array('field'=>'item_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	   /**
     * 保存细单
     * @see Sales_item_interface::calculate_shipping()
     */
    public function save_item($refundModel, $inter_id)
    {
        $data= array();
        $product= $refundModel->product;
        $order = $refundModel->order;

        foreach ($product as $k=>$v){
            //if( $v instanceof Product_package_model ){
            foreach ($this->product_item_field_mapping() as $sk=> $sv){
                $data[$k][$sk]= isset($v[$sv])? $v[$sv]: '';
            }
            
            $pk = $refundModel->table_primary_key();
            
            $data[$k][$pk]= $refundModel->{$pk};
            $data[$k]['order_item_id']= $order->m_get('order_id');
            $data[$k]['price']= $v['price_package'];
            $grand_total = $order->m_get('grand_total');
            $data[$k]['refund_total']= isset( $grand_total ) ? $grand_total : '0';
            //}
        }
        $table= $this->table_name($refundModel->business, $inter_id );
        $refundModel->_shard_db($inter_id)->insert_batch($table, $data);
        if( $refundModel->_shard_db($inter_id)->affected_rows() > 0 ){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * 获取退款订单细单数组
     * @see Sales_item_interface::calculate_shipping()
     */
    public function get_order_items($order, $inter_id)
    {
        $opk = $order->table_primary_key();
        $refund_id= $order->m_get($opk);
        $table= $this->table_name($order->business, $inter_id );
        $data= $this->_shard_db_r('iwide_soma_r')
            ->get_where($table, array($opk => $refund_id ))
            ->result_array();
        return $data;
    }
}
