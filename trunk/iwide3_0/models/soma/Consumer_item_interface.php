<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

interface Consumer_item_interface {

    public function save_item_from_order_item($consumer, $inter_id);
    
}
