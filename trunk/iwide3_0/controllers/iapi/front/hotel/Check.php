<?php
use App\services\hotel\CheckService;
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Check extends MY_Front_Hotel_Iapi {
	public $default_skin='default2';
	function __construct() {
		parent::__construct ();
	}
	
	function nearby() {
		$data = CheckService::getInstance()->nearby();
        $this->out_put_msg(1,'',$data,'hotel/check/nearby');
	}
	function my_collection() {
		$data = CheckService::getInstance()->my_collection();
        $this->out_put_msg(1,'',$data,'hotel/check/my_collection');
	}
	
	function check_repay(){

		$data = CheckService::getInstance()->check_repay();
		if($data['s']==1){
        	$this->out_put_msg(1,'','','hotel/check/check_repay');
		}else{
        	$this->out_put_msg(2,$data['errmsg'],'','hotel/check/check_repay');
		}

	}
	
	function ajax_hotel_list() {
		
		$data = CheckService::getInstance()->ajax_hotel_list();
		if($data['s']==1){
			unset($data['s']);
			foreach ($data['data']['result'] as $k => $r) {
				$data['data']['result'][$k]->link = Hotel_base::inst()->get_url("INDEX",array('h'=>$r->hotel_id)).$data['data']['exe_param'];
			}
        	$this->out_put_msg(1,'',$data['data'],'hotel/check/ajax_hotel_list');
		}else{
        	$this->out_put_msg(2,'','','hotel/check/ajax_hotel_list');
		}
	}
	function ajax_city_filter() {
		$data = CheckService::getInstance()->ajax_city_filter();
		if($data['s']==1){
			unset($data['s']);
        	$this->out_put_msg(1,'',$data,'hotel/check/ajax_city_filter');
		}else{
        	$this->out_put_msg(2,$data['data'],'','hotel/check/ajax_city_filter');
		}
		
	}
	function ajax_hotel_search() {
		$data = CheckService::getInstance()->ajax_hotel_search();
		if($data['s']==1){
			unset($data['s']);
        	$this->out_put_msg(1,'',$data['data'],'hotel/check/ajax_hotel_search');
		}else{
        	$this->out_put_msg(2,$data['data'],'','hotel/check/ajax_hotel_search');
		}
		
	}
    function check_order_canpay(){
    	$data = CheckService::getInstance()->check_order_canpay();
		if($data['s']==1){
        	$this->out_put_msg(1,$data['errmsg'],'','hotel/check/check_order_canpay');
		}else{
        	$this->out_put_msg(2,$data['errmsg'],'','hotel/check/check_order_canpay');
		}

    }
}