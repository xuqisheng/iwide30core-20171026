<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_coupon_model extends MY_Model_Soma {

    const SCOPE_WIDE= 1;
    const SCOPE_PART= 2;
    
	public function get_resource_name()
	{
		return 'Sales_coupon_model';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function get_scope_label()
	{
	    return array(
	        self::SCOPE_PART=> '部分适用',
	        self::SCOPE_WIDE=> '全部适用',
	    );
	}
	
	/**
	 * @return string the associated database table name
	 */
    public function table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_sales_coupon', $inter_id);
    }

    public function coupon_product_table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_sales_coupon_product', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'coupon_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'coupon_id'=> '优惠券ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'card_id'=> '卡ID',
            'card_type'=> '卡类型',
            'card_name'=> '卡名称',
            'create_time'=> '添加时间',
            'update_time'=> '更改时间',
            'scope'=> '使用范围',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'coupon_id',
            'inter_id',
            'hotel_id',
            'card_id',
            'card_type',
            'card_name',
            'create_time',
            'update_time',
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

        $this->load->model('soma/Sales_order_discount_model','SalesOrderDiscountModel');
        $SalesOrderDiscountModel = $this->SalesOrderDiscountModel;

	    return array(
            'coupon_id' => array(
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
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
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
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $hotels,
            ),
            'card_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'card_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=>$SalesOrderDiscountModel->get_card_type(),
            ),
            'card_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'scope' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'checkbox',	//textarea|text|combobox|number|email|url|price
                'select'=> self::get_scope_label(),
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'coupon_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    //取出卡券列表
    public function get_coupon_list( $inter_id=NULL )
    {
        if( !$inter_id ){
            return FALSE;
        }

        $filter = array();
        $filter['inter_id'] = $inter_id;

        $table_name = $this->table_name( $inter_id );
        return $this->_shard_db_r('iwide_soma_r')->where( $filter )->get( $table_name )->result_array();
    }

    //更新卡券
    public function refresh_coupon( $coupon_list=array(), $inter_id=NULL, $hotel_id=NULL )
    {
        $return = array();
        if( empty( $coupon_list ) || !$inter_id ){
            $return['status'] = 2;
            $return['message'] = 'inter_id为空';
            return $return;
        }
        
        $coupon_data = isset( $coupon_list['data'] ) && !empty( $coupon_list['data'] ) ? $coupon_list['data'] : NULL;
        if( empty( $coupon_data ) ){
            // return FALSE;
            $return['status'] = 2;
            $return['message'] = '拉回的券列表为空';
            return $return;
        }

        //取出先有的卡券
        $old_coupon_list = $this->get_coupon_list( $inter_id );
        
        $table_name = $this->table_name( $inter_id );

        $result = FALSE;
        $insert_data = array();
        $full_access = '';
        foreach ($coupon_data as $k => $v) {
            
            //传入的是json格式的，要转为数组
            $cv = json_decode( json_encode( $v ), TRUE );
            if( $cv['inter_id'] == FULL_ACCESS ){
                //跨店可用券，已作废，不做处理
                $full_access .= $cv['card_name'].'card_id='.$cv['card_id']."属于通用券\r\n";
            }else{

                if( !empty( $old_coupon_list ) ){
                    //原来有数据（ 指的是sales_coupon表的数据 ）

                    $is_exist = FALSE;//判断原数据是否存在该卡券,如果有is_exist为TRUE代表要更新,没有就是FALSE代表要插入
                    foreach ($old_coupon_list as $sk => $sv) {
                        if( $sv['card_id'] == $cv['card_id'] ){
                            //卡券的名称或者类型改变了，就更新
                            $where = array();
                            $where['inter_id'] = $inter_id;
                            $where['card_id'] = $cv['card_id'];
                            $where['coupon_id'] = $sv['coupon_id'];

                            $update_data = array();
                            $update_data['card_type'] = $cv['card_type'];
                            $update_data['card_name'] = $cv['title'];
                            $update_data['update_time'] = date( 'Y-m-d H:i:s', time() );
                            $this->_shard_db( $inter_id )->where( $where )->update( $table_name, $update_data );
                            $rows = $this->_shard_db( $inter_id )->affected_rows();
                            if( $rows > 0 ){
                                $result = TRUE;
                            }
                            $is_exist = TRUE;
                            break;
                        }
                    }

                    //不存在，则进行插入操作
                    if( !$is_exist ){
                        $data = array();
                        $data['inter_id'] = $inter_id;
                        $data['hotel_id'] = $hotel_id;
                        $data['card_id'] = $cv['card_id'];
                        $data['card_type'] = $cv['card_type'];
                        $data['card_name'] = $cv['title'];
                        $data['create_time'] = date( 'Y-m-d H:i:s', time() );
                        // $data['scope'] = self::SCOPE_PART;//默认为部分适用
                        $insert_data[] = $data;
                    }

                }else{
                    //原来没有数据的，就全部插入（ 指的是sales_coupon表的数据 ）
                    $data = array();
                    $data['inter_id'] = $inter_id;
                    $data['hotel_id'] = $hotel_id;
                    $data['card_id'] = $cv['card_id'];
                    $data['card_type'] = $cv['card_type'];
                    $data['card_name'] = $cv['title'];
                    $data['create_time'] = date( 'Y-m-d H:i:s', time() );
                    // $data['scope'] = self::SCOPE_PART;//默认为部分适用
                    $insert_data[] = $data;
                }
            }
        }

        if( !empty( $insert_data ) ){
            //如果现有券为空，则把卡券都插入
            $result = $this->_shard_db( $inter_id )->insert_batch( $table_name, $insert_data );
        }

        if( $result ){
            $return['status'] = 1;
            $return['message'] = '更新数据成功';
        }else{
            $return['status'] = 2;
            $return['message'] = '更新数据失败';
        }

        if( $full_access ){
            $return['message'] .= $full_access;
        }
        
        return $return;
    }

    //保存优惠券适用的商品
    public function product_save( $post, $inter_id )
    {
        if( empty( $post ) || !$inter_id ){
            return FALSE;
        }
        
        $this->_shard_db($inter_id)->trans_begin ();

        //删除旧数据
        $filter = array();
        $filter['card_id'] = isset( $post['card_id'] ) && !empty( $post['card_id'] ) ? $post['card_id'] : NULL;
        $filter['inter_id'] = $inter_id;
        $coupon_product_table_name = $this->coupon_product_table_name( $inter_id );

        $this->_shard_db( $inter_id )->where( $filter )->delete( $coupon_product_table_name );

        //以下代码是添加新数据

        $this->load->model('soma/Product_package_model','ProductModel');
        $ProductModel = $this->ProductModel;
        
        //修改券表的适用状态条件
        $table_name = $this->table_name( $inter_id );
        $cou_where = array();
        $cou_where['inter_id'] = $inter_id;
        $cou_where['coupon_id'] = isset( $post['coupon_id'] ) && !empty( $post['coupon_id'] ) ? $post['coupon_id'] : NULL;
        $cou_where['card_id'] = isset( $post['card_id'] ) && !empty( $post['card_id'] ) ? $post['card_id'] : NULL;

        $cou_data = array();
    
        //取出要添加的数据
        $p_type = isset( $post['p_type'] ) && !empty( $post['p_type'] ) ? strtolower( $post['p_type'] ) : NULL;
        if( $p_type == 'ids' ){
            //根据商品id添加
            
            $product_ids = isset( $post['product_ids'] ) && !empty( $post['product_ids'] ) ? explode( ',', $post['product_ids'] ) : NULL;
            if( !$product_ids ){
                return FALSE;
            }
            
            //查找出商品
            $products = $ProductModel->get_product_package_by_ids( $product_ids, $inter_id );
            if( empty( $products ) ){
                return FALSE;
            }

            $cou_data['scope'] = self::SCOPE_PART;//部分适用

        }elseif( $p_type == 'all' ){

            //添加全部商品
            $catId = '';
            $products = array();
            // $products = $ProductModel->get_product_package_list(  $catId, $inter_id );
            if( empty( $products ) ){
                // return FALSE;
            }

            $cou_data['scope'] = self::SCOPE_PART;//部分适用

        }elseif( $p_type == 'all_use' ){
            //全部适用，以后添加的商品也适用，修改券表的适用范围

            $cou_data['scope'] = self::SCOPE_WIDE;//全部适用

            $products = array();

        }else{
            return FALSE;
        }

        //修改券表状态
        if( !empty( $cou_data ) && isset( $cou_where['coupon_id'] ) ){

            $this->_shard_db( $inter_id )->where( $cou_where )->update( $table_name, $cou_data );
        }

        //组装要添加的数据
        $insert_data = array();
        if( $products ){
            foreach ($products as $k => $v) {
                $data = array();
                $data['inter_id'] = $inter_id;
                $data['card_id'] = isset( $post['card_id'] ) && !empty( $post['card_id'] ) ? $post['card_id'] : NULL;
                $data['hotel_id'] = isset( $post['hotel_id'] ) && !empty( $post['hotel_id'] ) ? $post['hotel_id'] : NULL;
                $data['product_id'] = $v['product_id'];
                $data['name'] = $v['name'];
                $data['status'] = $v['status'];
                $insert_data[] = $data;
            }
        }

        //添加新数据
        if( !empty( $insert_data ) ){

            $this->_shard_db( $inter_id )->insert_batch( $coupon_product_table_name, $insert_data );
        }

        
        //$this->_shard_db($inter_id)->trans_complete();
             
        if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
            $this->_shard_db($inter_id)->trans_rollback();
            return FALSE;
             
        } else {
            $this->_shard_db($inter_id)->trans_commit();
            return TRUE;
        }


    }

    /**
     * 根据会员模块返回的数据，查找适用的商品 
     * @see Sales_order_discount_model::calculate_discount()
     * @param string $discount
     * @return boolean|multitype:unknown
     */
    public function get_card_product( $discount=NULL ){
        if( !$discount ){
            return FALSE;
        }
        //取出数据
        $return_data = array();
        $products = $this->get_coupon_product_list( $discount['card_id'], $discount['inter_id'] );
        foreach ($products as $sk => $sv) {
            $return_data[$sv['product_id']] = $sv;
        }
        return $return_data;
    }

    //取出已选择商品的ID数组
    public function get_coupon_product_list( $card_id, $inter_id )
    {
        if( !$card_id || !$inter_id ){
            return FALSE;
        }

        $db = $this->_shard_db_r('iwide_soma_r');

        $filter = array();
        $filter['inter_id'] = $inter_id;
        $filter['status'] = self::STATUS_TRUE;
        $table_name = $this->coupon_product_table_name( $inter_id );
        $db->where( $filter );
        
        if( is_array($card_id) ){
            $db->where_in('card_id', $card_id);
        } else {
            $db->where('card_id', $card_id);
        }
        $result= $db->get( $table_name )->result_array();
        //echo $this->_shard_db( $inter_id )->last_query();die;
        return $result;
    }

    //找出公众号下所有全适用券
    public function get_wide_scope_coupon( $inter_id, $hash= FALSE )
    {
        $db = $this->_shard_db_r('iwide_soma_r');
        $table_name = $this->table_name( $inter_id );
        $result= $db->where('scope', self::SCOPE_WIDE )
                    ->get($table_name)
                    ->result_array();
        if($hash){
            return $this->array_to_hash($result, 'card_name', 'card_id');
            
        } else {
            return $result;
            
        }
    }

	
}
