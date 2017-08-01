<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once dirname(__FILE__). DS. 'Sales_item_interface.php';
class Sales_item_package_model extends MY_Model_Soma implements Sales_item_interface
{

    public function table_name($business='package', $inter_id=NULL)
    {
        return $this->_shard_table("soma_sales_order_item_{$business}", $inter_id);
    }
    public function asset_item_table_name($business, $inter_id=NULL)
    {
        return $this->_shard_table("soma_asset_item_{$business}", $inter_id);
    }
    public function discount_table_name($business, $inter_id=NULL)
    {
		return $this->_shard_table('soma_sales_order_discount', $inter_id);
    }
    public function product_table_name($business, $inter_id=NULL)
    {
        return $this->_shard_table("soma_catalog_product_{$business}", $inter_id);
    }
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
	        'product_id'=> 'product_id',
	        'inter_id'=> 'inter_id',
	        'hotel_id'=> 'hotel_id',
	        'sku'=> 'sku',
	        'conn_devices'=> 'conn_devices',
            'type'=> 'type',
            'goods_type'=> 'goods_type',
            'name'=> 'name',
            'name_en'=> 'name_en',
	        'card_id'=> 'card_id',
	        'price_market'=> 'price_market',
	        'price_package'=> 'price_package',
	        'compose'=> 'compose',
            'compose_en'=> 'compose_en',
	        'face_img'=> 'face_img',
	        'transparent_img'=> 'transparent_img',
            'use_cnt'=> 'use_cnt',
            'can_split_use'=> 'can_split_use',
            'can_wx_booking'=> 'can_wx_booking',
            'wx_booking_config'=> 'wx_booking_config',
	        'can_refund'=> 'can_refund',
	        'can_mail'=> 'can_mail',
	        'can_gift'=> 'can_gift',
	        'can_pickup'=> 'can_pickup',
            'can_sms_notify' => 'can_sms_notify',
	        'can_invoice'=> 'can_invoice',
            'can_reserve'=> 'can_reserve',
	        'is_hide_reserve_date'=> 'is_hide_reserve_date',
	        'room_id'=> 'room_id',
            'hotel_name'=> 'hotel_name',
	        'hotel_tel'=> 'hotel_tel',
            'date_type'=> 'date_type',
            'use_date'=> 'use_date',
	        'validity_date'=> 'validity_date',
	        'expiration_date'=> 'expiration_date',
	    );
	}

    //定义 m_save 保存时不做转义字段
    public function unaddslashes_field()
    {
        return array(
            'wx_booking_config',
        );
    }
	
	//################# 以上为非必要函数，下面为业务必要函数 #####################
	/**
	 * 计算运费总额
	 * @see Sales_item_interface::calculate_shipping()
	 */
    public function calculate_shipping($order, $inter_id)
    {
        $shipping= $order->shipping;
        if( $shipping instanceof Sales_order_attr_shipping ){
            return $shipping->amount;
        } else
            return '0';
    }

    /**
     * 计算订单总额（先）
     * @see Sales_item_interface::calculate_shipping()
     */
    public function calculate_total($order, $inter_id)
    {
        $total= array('row_total'=>0, 'subtotal'=>0);
        $product= $order->product;
        foreach ($product as $k=>$v){
            $order->hotel_id= $v['hotel_id'];   //利用产品的hotel_id 标记order的hotel_id
    
            $qty= ($v['qty']<1)? 1: intval($v['qty']);
            //qty 为计算数量后附加的属性
            $total['row_total']= $v['price_market'] * $qty;
            //qty 为计算数量后附加的属性
            $total['subtotal']= $v['price_package']* $qty;
    
            $order->row_qty += $qty;
        }
        $order->row_total= $total['row_total'];
        $order->subtotal= $total['subtotal'];  //用于 discount 条件判断
    
        //print_r($total);die;
        return $total;
    }
    
	/**
	 * 计算折扣总额（后）
	 * @see Sales_item_interface::calculate_shipping()
	 */
    public function calculate_discount($order, $inter_id)
    {
        $debug= TRUE;
        $data= array();
        $CI = & get_instance();
        $CI->load->model('soma/Sales_order_discount_model');
        $discount_model= $CI->Sales_order_discount_model;
        
        $CI->load->model('soma/Sales_rule_model');
        /**
         * @var Sales_rule_model $rule_model
         */
        $rule_model= $CI->Sales_rule_model;
        
//调试规则-start---------------
        if($debug){
            $this->load->helper('soma/package');
            write_log( "订单{$order->order_id}保存原始规则：". json_encode($order->discount, JSON_UNESCAPED_UNICODE) );
        }
//调试规则-end---------------

        //$discount= $order->discount;
        $discount= $rule_model->filter_base_rule($inter_id, $order );
        //print_r($discount);die;

//调试规则-start---------------
        if($debug){
            write_log( "订单{$order->order_id}保存过滤后规则：". json_encode($discount, JSON_UNESCAPED_UNICODE) );
        }
//调试规则-end---------------

        $product = $order->product;
        $p_type = $product[0]['type'];

        foreach ( $discount as $k=>$v ){

            // 储值类商品不适合储值优惠规则
            if($p_type == self::PRODUCT_TYPE_BALANCE
                && $k == $discount_model::TYPE_BALENCE) {
                continue;
            }

            //优惠券批量使用，这里需要改成多个mcid 5
            // var_dump( 'sales_item_package_model',$k, $v );die;
            //luguihong 20161107
            //这里需要做个判断，如果是优惠券，是一个二维数组
            if( $k == $discount_model::TYPE_COUPON ){
                unset( $v['discount_type'] );
                foreach( $v as $sk=>$sv ){
                    $type= $sv['discount_type'];
                    $data[$sk]= $discount_model->convert_disount($order, $sv, $type);
                    if($data[$sk]) {
                        if( isset( $order->discount_mount[$k] ) && !empty( $order->discount_mount[$k] ) ){
                            $order->discount_mount[$k] += $data[$sk]['amount'];
                        }else{
                            $order->discount_mount[$k] = $data[$sk]['amount'];
                        }
                    } else {
                        $order->discount_mount[$k]= NULL;  //无效的规则
                    }
                }
                // var_dump( $order->discount_mount[$k] );
            }else{
                //不是优惠券的
                //目前一类优惠类型只会计算一次, $k 为折扣类型编号
                $type= $v['discount_type'];  //折扣的类型又控制器直接赋值，参考  Sales_order_discount_model::get_payment_label()
                $data[$k]= $discount_model->convert_disount($order, $v, $type);
                if($data[$k]) {
                    $order->discount_mount[$k]= $data[$k]['amount'];
                } else {
                    $order->discount_mount[$k]= NULL;  //无效的规则
                }
            }
            /*
                //目前一类优惠类型只会计算一次, $k 为折扣类型编号
                $type= $v['discount_type'];  //折扣的类型又控制器直接赋值，参考  Sales_order_discount_model::get_payment_label()
                $data[$k]= $discount_model->convert_disount($order, $v, $type);
                if($data[$k]) {
                    $order->discount_mount[$k]= $data[$k]['amount'];
                } else {
                    $order->discount_mount[$k]= NULL;  //无效的规则
                }
            */
        }
        //print_r($data);die;
        if( count($data)>0 ){
            $insert_data= array();
            foreach ($data as $k=>$v){
                if( $v ) $insert_data[]= $v;
            }
            if( count($insert_data)>0 ){
                $table= $this->discount_table_name('package', $inter_id );
                // var_dump( $order->discount_mount, $insert_data );die;
                $order->_shard_db($inter_id)->insert_batch($table, $insert_data);//优惠券批量使用，这里需要改成多个mcid 7
            }
	    }
	    
//调试规则-start---------------
        if($debug){
            write_log( "订单{$order->order_id}计算后的总额：". json_encode($order->discount_mount, JSON_UNESCAPED_UNICODE) );
        }
//调试规则-end---------------
        
	    //var_dump($order->discount_mount);die;
	    return array_sum($order->discount_mount);
    }
    
    /**
     * 扣减对应库存数量
     * @param array $product
     * @param string $inter_id
     * @return bool
     * @see Sales_item_interface::calculate_shipping()
     */
    public function reduce_item_stock($product, $inter_id)
    {
        $reduce_mapping= array();

        foreach ($product as $k=>$v){
            $qty= ($v['qty']<1)? 1: intval($v['qty']);
            $reduce_mapping[$v['product_id']]['qty']= $qty;
            $reduce_mapping[$v['product_id']]['setting_id'] = isset($v['setting_id']) ? $v['setting_id'] : -1;

            if( $v['can_split_use'] == Soma_base::STATUS_TRUE && $v['use_cnt'] > 1 ) {
                // 分时住库存扣减
                $reduce_mapping[$v['product_id']]['qty'] = $v['qty'] * $v['use_cnt'];
            }

        }
        //print_r($reduce_mapping);
        $reduce_setting_stock = array();
        $table_name= $this->_db()->dbprefix( $this->product_table_name('package', $inter_id) );
        if( count($reduce_mapping)>0 ){
            foreach ($reduce_mapping as $k=>$row){
                $v = $row['qty'];
                if($v>0){
                    if($row['setting_id'] == -1) {
                        // 无规格设定信息，走原产品库存
                        $sql = "update {$table_name} set `stock`=`stock`-{$v} where `product_id`={$k} and `stock`>={$v}";
        	            $res = $this->_shard_db($inter_id)->query($sql, array(), true);
                        // var_dump($res->conn_id->affected_rows);exit;
                        if($res->conn_id->affected_rows != 1) {
                            Soma_base::inst()->show_exception( '商品库存不足！' );
                        }
                    } else {
                        if($row['setting_id'] != 'all') {
                            if($this->_reduce_item_spec_stock($k, $row['setting_id'], $v, $inter_id)) {
                                $reduce_setting_stock[$k] = array('setting_id' => $row['setting_id'], 'qty' => $v);
                            } else {
                                Soma_base::inst()->show_exception( '商品库存不足！' );
                            }
                        } else {
                            // 从所有库存中开始扣减，循环扣减
                            $this->load->model('soma/Product_specification_setting_model', 'psp_model');
                            $settings = $this->psp_model->get_specification_setting($inter_id, $k);
                            if(count($settings) > 0) {
                                foreach ($settings as $setting) {
                                    if($v <= 0) { break; }
                                    $reduce_qty = $v;
                                    if($v > $setting['stock']) {
                                        $reduce_qty = $setting['stock'];
                                    }

                                    if($this->_reduce_item_spec_stock($k, $setting['setting_id'], $reduce_qty, $inter_id)) {
                                        $reduce_setting_stock[$k] = array('setting_id' => $setting['setting_id'], 'qty' => $reduce_qty);
                                        $v -= $reduce_qty;
                                    } else {
                                        Soma_base::inst()->show_exception( '商品库存不足！' );
                                    }
                                }
                                if($v > 0) {
                                    Soma_base::inst()->show_exception( '商品库存不足！' );
                                }
                            } else {
                                Soma_base::inst()->show_exception( '商品库存不足！' );
                            }
                        }
                    }
                }
            }
        }
        // 规格库存扣减情况，原格式应该为空
        $this->reduce_item_stock = $reduce_setting_stock;
        return TRUE;
    }

    /**
     * @param $pid
     * @param $sid
     * @param $qty
     * @param $inter_id
     * @return bool
     */
    protected function _reduce_item_spec_stock($pid, $sid, $qty, $inter_id) {
        $table_name = $this->_db()->dbprefix($this->_shard_table('soma_product_specification_setting', $inter_id));
        $sql = "update {$table_name} set `spec_stock`=`spec_stock`-{$qty} where `product_id`={$pid} and `setting_id`={$sid} and `spec_stock`>={$qty}";
        $res = $this->_shard_db($inter_id)->query($sql, array(), true);
        if($res->conn_id->affected_rows != 1) {
            return false;
        }
        return true;
    }


    /**
     *
     * 保存细单
     * @param $order_id
     * @param $product
     * @param $customer
     * @param $instance_id
     * @param $business
     * @param $inter_id
     * @return int
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function save_item_new($order_id, $product, $customer, $instance_id, $business, $inter_id)
    {
        if( $customer instanceof Sales_order_attr_customer ){
            $openid= $customer->openid;
        }

        $this->load->model('soma/Product_package_model','productPackageModel');
        $productModel = $this->productPackageModel;

        $data= array();

        foreach ($product as $k=>$v){
            //if( $v instanceof Product_package_model ){
            foreach ($this->product_item_field_mapping() as $sk=> $sv){
                $data[$k][$sk]= isset($v[$sv])? $v[$sv]: '';
            }
            $data[$k]['qty']= $v['qty'];
            $data[$k]['openid']= $openid;
            $data[$k]['order_id']= $order_id;

            //对于秒杀记录其价格
            if( $instance_id){
                $ks_table= $this->_db()->dbprefix('soma_activity_killsec_instance');
                $price_killsec = $this->_shard_db_r('iwide_soma_r')->select('killsec_price')->limit(1)
                    ->where('instance_id', $instance_id)->get($ks_table)->row_array();
                $data[$k]['price_killsec']= current( $price_killsec );
            }

            //设定的模式是存活时间的且不是时间规格的，要重新设置有效期
            if( $v['date_type'] == $productModel::DATE_TYPE_FLOAT && $v['setting_date'] == Soma_base::STATUS_FALSE ){
                $date = isset( $v['use_date'] ) ? $v['use_date'] : 0;
                $date_time = date('Y-m-d H:i:s',time()+$date*24*60*60);
                $data[$k]['expiration_date'] = $date_time;
            }

            // 添加规格信息保存
            if(isset($this->reduce_item_stock[$v['product_id']])){
                $data[$k]['reduce_item_stock'] = json_encode($this->reduce_item_stock[$v['product_id']]);
            }
        }

        $table= $this->table_name($business, $inter_id );
        $db = $this->_shard_db($inter_id);
        $result= $db->insert_batch($table, $data);

        return $result;
    }
    
	/**
	 * 保存细单
	 * @see Sales_item_interface::calculate_shipping()
     *
     * @deprecated 2017年4月11日
     */
    public function save_item($order, $payment=FALSE, $inter_id)
    {
        $data= array();
        $product= $order->product;
        $customer= $order->customer;
        if( $customer instanceof Sales_order_attr_customer ){
            $openid= $customer->openid;
        }

        $this->load->model('soma/Product_package_model','productPackageModel');
        $productModel = $this->productPackageModel;

        // $can_refund= TRUE;
        $can_refund = self::CAN_REFUND_STATUS_FAIL;
        foreach ($product as $k=>$v){
            //if( $v instanceof Product_package_model ){
            foreach ($this->product_item_field_mapping() as $sk=> $sv){
                $data[$k][$sk]= isset($v[$sv])? $v[$sv]: '';
            }
            $data[$k]['qty']= $v['qty'];
            $data[$k]['openid']= $openid;
            $data[$k]['order_id']= $order->m_get('order_id');
            
            //对于秒杀记录其价格
            if( $instance_id= $order->m_get('killsec_instance') ){
                $ks_table= $this->_db()->dbprefix('soma_activity_killsec_instance');
                $price_killsec = $order->_shard_db_r('iwide_soma_r')->select('killsec_price')->limit(1)
                    ->where('instance_id', $instance_id)->get($ks_table)->row_array();
                $data[$k]['price_killsec']= current( $price_killsec );
            }
            //}
            // if( $v['can_refund']==self::STATUS_FALSE ){
            //     $can_refund= FALSE;
            // }
            $can_refund = $v['can_refund'];

            //设定的模式是存活时间的且不是时间规格的，要重新设置有效期
            if( $v['date_type'] == $productModel::DATE_TYPE_FLOAT && $v['setting_date'] == Soma_base::STATUS_FALSE ){
                $date = isset( $v['use_date'] ) ? $v['use_date'] : 0;
                $date_time = date('Y-m-d H:i:s',time()+$date*24*60*60);
                $data[$k]['expiration_date'] = $date_time;
            }

            // 添加规格信息保存
            if(isset($this->reduce_item_stock[$v['product_id']])){
                $data[$k]['reduce_item_stock'] = json_encode($this->reduce_item_stock[$v['product_id']]);
            }
        }

        

        $table= $this->table_name($order->business, $inter_id );
        $result= $order->_shard_db($inter_id)->insert_batch($table, $data);

        //如果存在不可退款项目则标记
        // if( !$can_refund ){
        //     $order->m_set('can_refund', self::STATUS_FALSE)->m_save();
        // }
        $order->m_set('can_refund', $can_refund)->m_save();

        return $result;
    }
    
	/**
	 * 保存付款状态
	 * @see Sales_item_interface::calculate_shipping()
	 */
    public function save_item_payment($order, $inter_id)
    {
        //目前无需要做特别处理
        return TRUE;
    }

    /**
     * 获取订单细单数组
     * @see Sales_item_interface::calculate_shipping()
     */
    public function get_order_items($order, $inter_id)
    {
        $opk = $order->table_primary_key();
        $order_id = $order->m_get($opk);
        $table= $this->table_name($order->m_get('business'), $inter_id );

        $data= $this->_shard_db()
            ->get_where($table, array($opk => $order_id ))
            ->result_array();
        return $data;
    }

    public function get_asset_items($order, $inter_id)
    {
        $opk = $order->table_primary_key();
        $order_id= $order->m_get($opk);
        $table= $this->asset_item_table_name($order->m_get('business'), $inter_id );
        $data= $this->_shard_db()
            ->get_where($table, array($opk => $order_id ))
            ->result_array();
        return $data;
    }
    

    public function get_order_items_byIds($ids, $business, $inter_id)
    {
        $table = $this->_db()->dbprefix( $this->table_name($business, $inter_id) );
	    $items= $this->_shard_db_r('iwide_soma_r')
	        ->where_in( 'order_id', $ids )
	        ->get($table)->result_array();
        return $items;
    }

    /**
     * 根据商品ID查找不同过期时间的细单
     * @author luguihong
     */
    public function get_order_items_byProductIds($productIds, $business, $inter_id, $select='*', $orderby='expiration_date')
    {
        $table = $this->_db()->dbprefix( $this->table_name($business, $inter_id) );
        $db = $this->_shard_db_r('iwide_soma_r');

        if( $orderby ){
            $db->group_by( $orderby );
        }

        $this->load->model('soma/Product_package_model','productModel');
        $productModel = $this->productModel;

        $items= $db
            ->where( 'date_type', $productModel::DATE_TYPE_STATIC )//luguihong 这里做处理是因为，如果是存活时间的，买一个就是一个批次，所以做过滤
            ->where_in( 'product_id', $productIds )
            ->select( $select )
            ->get($table)
            ->result_array();
        return $items;
    }
    
	/**
	 * 将细单分配至资产库
	 * @see Sales_item_interface::calculate_shipping()
	 */
    public function sign_item_to_asset($order, $inter_id)
    {
        if (empty($order->item)) {
            $order->item = $this->get_order_items($order, $inter_id);
        }

        $this->load->model('soma/asset_customer_model', 'assertCustomerModel');
        /**
         * @var Asset_customer_model $assertCustomerModel
         */
        $assertCustomerModel = $this->assertCustomerModel;
        $result = $assertCustomerModel->sign_asset_item($order, $inter_id);
        return $result;
    }
    
    /**
     * 改变细单的状态位（退款管理）
     * Usage: $model->order_refund_status($order, $inter_id);
     * Usage: $salesRefundModel; 为了保存事务一致性
     * @author luguihong
     */
    public function order_refund_status( $order, $inter_id, $salesRefundModel )
    {
        //组装修改状态值
        $refund_item = $order->refund_item;
        $is_refund = isset( $refund_item['is_refund'] ) ? $refund_item['is_refund'] : '';
        if( !$is_refund ){
            return FALSE;
        }

        //组装细单条件
        $business = isset( $order->business ) ? $order->business : 'package';
        $business = strtolower( $business );

        $where = array();
        $where['inter_id'] = $inter_id;
        $where['order_id'] = $order->m_get('order_id');
        
        $data = array();
        $data['is_refund'] = $is_refund;
        $table = $this->table_name( $business, $inter_id );
        $salesRefundModel->_shard_db( $inter_id )
                                ->where( $where )
                                ->update( $table, $data );

        if( $salesRefundModel->_shard_db( $inter_id )->affected_rows() > 0 ){
            return TRUE;
        }else{
            return FALSE;
        }
       
    }
    
}
