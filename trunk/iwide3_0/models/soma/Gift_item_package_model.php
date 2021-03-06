<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once dirname(__FILE__). DS. 'Gift_item_interface.php';
class Gift_item_package_model extends MY_Model_Soma 
    implements Gift_item_interface {
    
    public function table_name($business='package', $inter_id=NULL)
    {
        return $this->_shard_table("soma_gift_order_item_{$business}", $inter_id);
    }
    public function asset_item_table_name($business, $inter_id=NULL)
    {
        return $this->_shard_table("soma_asset_item_{$business}", $inter_id);
    }
    public function sales_order_table_name( $inter_id=NULL)
    {
        return $this->_shard_table("soma_sales_order", $inter_id);
    }
	public function table_primary_key()
	{
	    return 'item_id';
	}

	/**
	 * 字段映射，key中字段将直接转移到item
	 * @return multitype:string
	 */
	public function asset_item_field_mapping()
	{
	    return array(
	        'asset_item_id'=> 'item_id',
	        'inter_id'=> 'inter_id',
	        'hotel_id'=> 'hotel_id',
	        'product_id'=> 'product_id',
	        'qty'=> 'qty_require',  
	        'sku'=> 'sku',
	        'name'=> 'name',
	        'name_en'=> 'name_en',
	    );
	}
	
	//################# 以上为非必要函数，下面为业务必要函数 #####################

	/**
	 * 获取订单细单数组
	 */
	public function get_order_items($order, $inter_id)
	{
	    $gift_id= $order->m_get('gift_id');
	    $table= $this->table_name('package', $inter_id );
	    $data= $order->_shard_db_r('iwide_soma_r')
    	    ->get_where($table, array('gift_id' => $gift_id ))
    	    ->result_array();
	    return $data;
	}
	/**
	 * 获取赠礼对应的资产项目明细
	 */
	public function get_asset_items($order, $inter_id)
	{
        $order_item_table = $this->_db()->dbprefix($this->table_name('package', $inter_id ));
        $asset_item_table =  $this->_db()->dbprefix( $this->asset_item_table_name('package', $inter_id) );
        $gift_id = $order->m_get('gift_id');
        $sql = "select * from `{$asset_item_table}` where item_id in ( SELECT asset_item_id FROM `{$order_item_table}` WHERE gift_id = {$gift_id} )";
        $items = $order->_shard_db_r('iwide_soma_r')->query($sql)->result_array();

	    $gift_items= $this->get_order_items($order, $inter_id);
	    $ids= $this->array_to_hash($gift_items, 'gift_id', 'asset_item_id');
//
//        $table = $this->_db()->dbprefix( $this->asset_item_table_name('package', $inter_id) );
//	    $items= $order->_shard_db_r('iwide_soma_r')
//	        ->where_in( 'item_id', array_keys($ids) )
//	        ->get($table)->result_array();
//
	    foreach ($items as $k=> $v){
	        if( array_key_exists($v['item_id'], $ids) ) $items[$k]['gift_id']= $v['item_id'];
	    }
        return $items;
	}
	/**
	 * 获取赠礼对应简要明细  gift_item_package
	 * $ids= gift_id
	 */
	public function get_order_items_byIds($gids, $business, $inter_id)
	{
	    $table = $this->_db()->dbprefix( $this->table_name('package', $inter_id) );
	    $items= $this->_shard_db_r('iwide_soma_r')
    	    ->where_in( 'gift_id', $gids )
    	    ->get($table)->result_array();
	    return $items;
	}
	/**
	 * 获取赠礼对应简要明细  gift_item_package
	 * $ids= gift_id
	 */
	public function get_order_items_byAssetItemIds($assetItemIds, $business, $inter_id)
	{
	    $table = $this->_db()->dbprefix( $this->table_name('package', $inter_id) );
	    $items= $this->_shard_db_r('iwide_soma_r')
    	    ->where_in( 'asset_item_id', $assetItemIds )
    	    ->get($table)->result_array();
	    return $items;
	}
	/**
	 * 获取赠礼对应的资产项目明细   asset_item_package
	 * $ids= gift_id
	 */
	public function get_asset_items_byIds($gids, $business, $inter_id)
	{
	    //print_r($gids);die;
	    $gift_items= $this->get_order_items_byIds($gids, $business, $inter_id);
	    $aiids= $this->array_to_hash($gift_items, 'asset_item_id', 'gift_id');
	    /**
	     * Array (
    [1000000170] => 8
    [1000000171] => 8
    [1000000172] => 8
         )
	     */
	    $item_qty= $this->array_to_hash($gift_items, 'qty', 'gift_id');
	    /**
	     * Array (
    [1000000170] => 1
    [1000000171] => 2
    [1000000172] => 1
         )
	     */
        //print_R($aiids);die;  //$ids 为$asset_item->item_id, 可能由多个gift_item 合成1个 asset_item 
        $asset_table = $this->_db()->dbprefix( $this->asset_item_table_name($business, $inter_id) );
	    $asset_items= $this->_shard_db_r('iwide_soma_r')
	        ->where_in( 'item_id', array_values($aiids) )
	        ->get($asset_table)->result_array();

	    //print_r($asset_items);die;
	    $return1= array();
	    foreach ($asset_items as $k=> $v){
	        $return1[$v['item_id']]= $v;   //asset_item_id=> asset item
	    }
	    $return2= array();
	    foreach ($aiids as $k=> $v){
	        $return2[$k]= $return1[$v];    //gift_id=> asset item
	    }
	    foreach ($return2 as $k=> $v){
	        $return2[$k]['qty_require']= $item_qty[$k];    //gift_id=> asset item
	    }
	    //print_r($return2);die;
        return $return2;
	}
	
	/**
	 * 检测是否有足够的资产进行赠送
	 * $order->item 为 asset_item, asset_item->qty_require 为本次赠送数量
	 */
	public function check_item_asset($order, $inter_id)
	{
	    $require_mapping= $this->array_to_hash($order->item, 'qty_require', 'item_id');
	    $real_mapping= $this->array_to_hash($order->item, 'qty', 'item_id');
	    $can_gift= TRUE;
	    foreach ($require_mapping as $k=> $v){
	        if( !isset($real_mapping[$k]) || $v> $real_mapping[$k] ) 
	            $can_gift= FALSE;  //标识失败
	    }
	    return $can_gift;
	}

	/**
	 * 从资产库明细保存赠送细单
	 * $order->item 为 asset_item
	 */
	public function save_item($order, $inter_id)
	{
	    $data= array();
	    $item= $order->item;
	    foreach ($item as $k=>$v){
	        foreach ($this->asset_item_field_mapping() as $sk=> $sv){
	            $data[$k][$sk]= isset($v[$sv])? $v[$sv]: '';
	        }
	        $data[$k]['gift_id']= $order->m_get('gift_id');
	    }
	    $table= $this->table_name('package', $inter_id );
	    $result= $order->_shard_db($inter_id)->insert_batch($table, $data);
	    return $result;
	}
	
	/**
	 * 送出前标记资产库对应状态
	 * $order->item 为 asset_item
	 */
	public function handle_after_gifting($order, $inter_id)
	{
	    $business= 'package';
	    $db_prefix= $order->_shard_db($inter_id)->dbprefix;
	    $asset_item_table= $db_prefix. $this->asset_item_table_name($business, $inter_id);
	    $openid= $order->sender->openid;
	     
	    //将二维数组转换为mapping数组，键值为item_id
	    $require_mapping= $this->array_to_hash($order->item, 'qty_require', 'item_id');
	    $order_mapping= $this->array_to_hash($order->item, 'order_id', 'item_id');
	    
	    //资产库数量递减
	    foreach ($require_mapping as $k=>$v){
	        if( $v==0 ) continue;
	        
	        $where= "where `item_id`='{$k}'";
	        $sql= "update {$asset_item_table} set `qty_origin`=`qty`,`qty`=`qty`-{$v} {$where};";
	        $order->_shard_db($inter_id)->query($sql);
	        
	        //根据id生成对应数量的code（按照倒序删除，不影响asset_item利用偏移量查找code）
	        // $aiid= $order->_shard_db($inter_id)->insert_id(); // 注释无用代码
	        $this->load->model('soma/consumer_code_model');
	        // $r= $this->consumer_code_model->remove_asset_code($k, $v, $inter_id);
	        // 资产转赠后仅更新被转赠的资产核销码信息，不再删除资产核销码
	        if(!$this->consumer_code_model->updateGiftConsumerCodeStatus($inter_id, $k, $v)) {
	        	return false;
	        }
	    }

	    //标记订单的refund_status，锁定不可退款
	    if( count($order_mapping)>0 ){
    	    $CI = & get_instance();
    	    $CI->load->model('soma/Sales_order_model');
    	    $status= Sales_order_model::GIFT_PART;
    	    $sales_order_table= $this->sales_order_table_name($inter_id);
    	    $order->_shard_db($inter_id)->where_in( 'order_id', array_values( $order_mapping ) )
    	       ->update($sales_order_table, array('gift_status'=> $status ) );
	    }
	    return TRUE;
	}

	/**
	 * 送出后标记资产库对应状态
	 * $order->item 为 asset_item
	 */
	public function handle_after_gifted($order, $inter_id)
	{
	    $business= 'package';
	    $asset_item_table= $this->asset_item_table_name($business, $inter_id);
	    $openid_s= $order->sender->openid;
	    $openid_r= $order->received->openid;

	    //将二维数组转换为mapping数组，键值为item_id
	    $require_mapping= $this->array_to_hash($order->received_item, 'qty_require', 'item_id');
	    //print_r($order->received_item);die;
	    
	    $asset_item= $order->_shard_db_r('iwide_soma_r')
	        ->where_in( 'item_id', array_keys($require_mapping ))
	        ->get($asset_item_table)->result_array();
	    //print_r( $asset_item );die;
	    
	    $data= array();
	    //资产库项目插入
	    foreach ($asset_item as $k=>$v){
	        if( $v==0 ) continue;
	        
	        $data[$k]= $v;  //修正以下属性值
	        $data[$k]['gift_id']= $order->m_get('gift_id');
	        $data[$k]['openid_origin']= $openid_s;
	        $data[$k]['openid']= $openid_r;
	        $data[$k]['parent_id']= $v['item_id'];
	        $data[$k]['add_time']= date('Y-m-d H:i:s');
	        $data[$k]['qty']= isset( $order->rule->per_give ) ? $order->rule->per_give : $require_mapping[$v['item_id']];
	        $data[$k]['qty_origin']= NULL;
	        $data[$k]['item_id']= NULL;
	        $result= $order->_shard_db($inter_id)->insert($asset_item_table, $data[$k] );
	        
	        //根据id生成对应数量的code
	        $id_array= array(
	            'asset_item_id'=> $order->_shard_db($inter_id)->insert_id(),
	            'order_item_id'=> $data[$k]['order_item_id'],
	            'order_id'=> $data[$k]['order_id'],
	            'asset_id'=> $data[$k]['asset_id'],
	        );
	        $this->load->model('soma/consumer_code_model');
	        $r= $this->consumer_code_model->generate_asset_code($id_array, $data[$k]['qty'], $inter_id);
	    }
	    //print_r($data);die;
	    return $result;
	}

	/**
	 * 赠送过期回收资产库对应状态
	 * $order->item 为 asset_item
	 */
	public function rollback_gift_item($order, $inter_id)
	{
	    $business= 'package';
	    $db_prefix= $order->_shard_db($inter_id)->dbprefix;
	    $asset_item_table= $db_prefix. $this->asset_item_table_name($business, $inter_id);
	
	    //将二维数组转换为mapping数组，键值为item_id
	    $gift_item= $this->find_all( array('gift_id'=> $order->m_get('gift_id')) );
	    $require_mapping= $this->array_to_hash( $gift_item, 'qty', 'asset_item_id');
        //var_dump($require_mapping);die;
        
	    //资产库数量递减
	    foreach ($require_mapping as $k=>$v){
	        if( $v==0 ) continue;
	        
	        $where= "where `item_id`='{$k}'";
	        $sql= "update {$asset_item_table} set `qty_origin`=`qty`,`qty`=`qty`+{$v} {$where};";
	        //echo $sql;die;
	        $order->_shard_db($inter_id)->query($sql);
	        
	        /*
	        //根据id补充对应数量的code
	        $id_array= array( 'asset_item_id'=> $k, );
	        $this->load->model('soma/consumer_code_model');
	        
	        $r= $this->consumer_code_model->generate_asset_code($id_array, $v, $inter_id);
	        */
	        // 礼物回退时不再生成核销码(因为赠送时不再删除)，将已转赠的核销码状态改为已分配即可
	        $this->load->model('soma/consumer_code_model');
	        if(!$this->consumer_code_model->updateGiftConsumerCodeStatus($inter_id, $k, $v, 'rollback')) {
	        	return false;
	        }
	    }
	    return TRUE;
	}
	public function rollback_gift_item_group($order, $inter_id)
	{
	    $gift_id= $order->m_get('gift_id');
	    $business= 'package';
	    $db_prefix= $order->_shard_db($inter_id)->dbprefix;
	    $asset_item_table= $db_prefix. $this->asset_item_table_name($business, $inter_id);
	
	    //将二维数组转换为mapping数组，键值为item_id
	    $gift_item= $this->find_all( array('gift_id'=> $gift_id ) );
	    $require_mapping= $this->array_to_hash( $gift_item, 'qty', 'asset_item_id');
        //var_dump($require_mapping);die;
	    
$filter= array('status'=> $order::STATUS_RECEIVE_DEFAULT );
$receiver_list= $order->get_receiver_list($inter_id, $gift_id, $filter, 'get_qty');
$item_reduce= array_sum($receiver_list);  //计算一共已经接收多少件，作为退回扣减数量

foreach ($require_mapping as $k=> $v){
    $require_mapping[$k]= $v- $item_reduce;
}
	    
	    //资产库数量递减
	    foreach ($require_mapping as $k=>$v){
	        if( $v==0 ) continue;
	        
	        $where= "where `item_id`='{$k}'";
	        $sql= "update {$asset_item_table} set `qty_origin`=`qty`,`qty`=`qty`+{$v} {$where};";
	        //echo $sql;die;
	        $order->_shard_db($inter_id)->query($sql);
	        
	        /*
	        //根据id补充对应数量的code
	        $id_array= array( 'asset_item_id'=> $k, );
	        $this->load->model('soma/consumer_code_model');
	        
	        $r= $this->consumer_code_model->generate_asset_code($id_array, $v, $inter_id);
	        */
	        // 礼物回退时不再生成核销码(因为赠送时不再删除)，将已转赠的核销码状态改为已分配即可
	        $this->load->model('soma/consumer_code_model');
	        if(!$this->consumer_code_model->updateGiftConsumerCodeStatus($inter_id, $k, $v, 'rollback')) {
	        	return false;
	        }
	    }
	    return TRUE;
	}
	
}
