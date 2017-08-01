<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

interface Gift_item_interface {

    /** $order 代表gift order，里面携带转移的item信息  **/
    public function check_item_asset($order,$inter_id);
    public function save_item($order,$inter_id);
    
    public function handle_after_gifting($order,$inter_id);
    public function handle_after_gifted($order,$inter_id);
    public function rollback_gift_item($order,$inter_id);
    
    public function get_order_items($order,$inter_id);
    public function get_order_items_byIds($ids, $business, $inter_id);

    
    
}
