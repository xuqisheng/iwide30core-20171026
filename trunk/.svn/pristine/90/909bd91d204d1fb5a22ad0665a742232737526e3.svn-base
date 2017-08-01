<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

interface Sales_item_interface {
    
    public function calculate_total($order,$inter_id);
    public function calculate_shipping($order,$inter_id);
    public function calculate_discount($order,$inter_id);
    
    public function save_item($order, $payment=FALSE, $inter_id);
    public function save_item_payment($order,$inter_id);

    public function get_asset_items($order,$inter_id);
    public function get_order_items($order,$inter_id);
    public function get_order_items_byIds($ids, $business, $inter_id);
    
    public function sign_item_to_asset($order,$inter_id);
    
//    public function reduce_item_stock($order,$inter_id);
    
    public function order_refund_status($order,$inter_id,$salesRefundModel);
    
}
