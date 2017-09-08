<?php
use App\services\hotel\CheckService;
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Check extends MY_Front_Hotel {
	public $default_skin='default2';
	public $common_show;
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'wx/Access_token_model' );
		$this->common_show ['signPackage'] = $this->Access_token_model->getSignPackage ( $this->inter_id );
		$this->common_show ['pagetitle'] = $this->public ['name'];
		$this->share ['title'] = $this->public ['name'] . '-微信订房';
		$slink = $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
		if (strpos ( $slink, '?' ))
			$slink = $slink . "&id=" . $this->inter_id;
		else
			$slink = $slink . "?id=" . $this->inter_id;
		$this->share ['link'] = $slink;
		$this->share ['imgUrl'] = 'http://7n.cdn.iwide.cn/public/uploads/201609/qf051934149038.jpg';
		$this->share ['desc'] = $this->public ['name'] . '欢迎您使用微信订房,享受快捷服务...';
		$this->share ['type'] = '';
		$this->share ['dataUrl'] = '';
		$this->common_show ['share'] = $this->share;
	}
	
	function nearby() {
		$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/nearby');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(CheckService::getInstance()->nearby(),$data);
        }
		
		$this->display ( 'hotel/nearby/near_hotel', $data,'',$module_view );
	}
	function my_collection() {
		$data = $this->common_show;
		$data['pagetitle'] = '我的收藏';
        $module_view=$this->get_display_view('hotel/my_collection');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(CheckService::getInstance()->my_collection(),$data);
        }
		$this->display ( 'hotel/my_collection/my_collection', $data,'',$module_view );
	}
	
	function check_repay(){
		$data = CheckService::getInstance()->check_repay();
		echo json_encode ( $data );
	}
	
	function ajax_hotel_list() {
		$data = CheckService::getInstance()->ajax_hotel_list();
		if($data['s']==1){
			$data['data'] = $this->display ( 'hotel/ajax_hotel_list/ajax_hotel_list', $data['data'], '', array (), TRUE );
			echo json_encode ( $data, JSON_UNESCAPED_UNICODE );
		}else{
			echo json_encode ( $data );
		}
	}
	function ajax_city_filter() {
		$data = CheckService::getInstance()->ajax_city_filter();
		if($data['s']==1){
			echo json_encode ( $data, JSON_UNESCAPED_UNICODE );
		}else{
			echo json_encode ( $data );
		}
	}
	function ajax_hotel_search() {
		$data = CheckService::getInstance()->ajax_hotel_search();
		if($data['s']==1){
			$data['data'] = $this->display ( 'hotel/ajax_hotel_search/ajax_hotel_search', $data['data'], '', array (), TRUE );
			echo json_encode ( $data, JSON_UNESCAPED_UNICODE );
		}else{
			echo json_encode ( $data );
		}
	}
    function check_order_canpay(){
    	$data = CheckService::getInstance()->check_order_canpay();
		echo json_encode ( $data );
    }
}