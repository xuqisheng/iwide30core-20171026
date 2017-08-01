<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

interface Asset_item_interface {

    public function save_item($asset, $inter_id);

    public function calculate_total($asset, $inter_id);
	
}
