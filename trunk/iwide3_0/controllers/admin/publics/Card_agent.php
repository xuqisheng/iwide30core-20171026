<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Card_agent extends MY_Admin {
	public function __construct() {
		parent::__construct ();
	}
	public function upload_card_agent_qualification() {
		$this->load->model ( 'wx/access_token_model' );
		$url = 'http://api.weixin.qq.com/cgi-bin/component/upload_card_agent_qualification?access_token='.$this->access_token_model->get_component_access_token();
		$this->load->helper ( 'common' );
		//{"type":"image","media_id":"MXa9QcgaOkpSL44hW0kT92d0uJvwiSqMJoKLGSr682ErAmP8CSse87Lr9jo-sbFk","created_at":1471345852}
		//{"type":"image","media_id":"cE9NYx-AQJUbKfTzKZ0QRGsUeptJDFZDQtGQ0fg_657U-h7HlTIz05Z565GDwyfk","created_at":1471345959}
		//{"type":"image","media_id":"nmz8veQYJIvzggay859g2i9SjB27eApWkXfC39YnTaaXU9lj9f-uQfnCYR86YYf_","created_at":1471345994}
		//{"type":"image","media_id":"ZFKLe0XRf_UEHVi6z0CwYAjZ9oiih-TwJTX386moRjsQy0iGqnNxB-JKxBlgeLpB","created_at":1471346036}
		//{"type":"image","media_id":"7iuiqUZuPWwJ7_W7nluk1k-eVfywou_C1-Gh5qBdlK4_Xeb5KOY7TrduHAPrSCWQ","created_at":1471346062}
		
		$params = array (
				"register_capital"                      => 1195932000,//注册资本，数字，单位：分
				"business_license_media_id"             => "MXa9QcgaOkpSL44hW0kT92d0uJvwiSqMJoKLGSr682ErAmP8CSse87Lr9jo-sbFk",//营业执照扫描件的media_id
				"tax_registration_certificate_media_id" => "MXa9QcgaOkpSL44hW0kT92d0uJvwiSqMJoKLGSr682ErAmP8CSse87Lr9jo-sbFk",//税务登记证扫描件的media_id
				"last_quarter_tax_listing_media_id"     => "-tPm2O8yiPARfnUeTz0DeAmXjECIiarUb0VWVBD0wpALiR6AE6GH2l73BEZ_446_" //上个季度纳税证明扫描件media_id
		);
		var_dump($url);
		var_dump($params);
		var_dump(doCurlPostRequest($url, json_encode($params)));
		// $view_params = array ( 'app_id' => $account_info ['app_id'], 'pre_auth_code' => $pre_auth_code );
		// echo $this->_render_content ( $this->_load_view_file ( 'guid' ), $view_params, TRUE );
	}
	
	public function check_card_agent_qualification(){
		$this->load->model ( 'wx/access_token_model' );
		$url = 'http://api.weixin.qq.com/cgi-bin/component/check_card_agent_qualification?access_token='.$this->access_token_model->get_component_access_token();
		$this->load->helper ( 'common' );
		
		$res = json_decode(doCurlGetRequest($url));
		$res_arr = array('RESULT_PASS'=>'审核通过','RESULT_NOT_PASS'=>'审核驳回','RESULT_CHECKING'=>'待审核','RESULT_NOTHING_TO_CHECK'=>'无提审记录');
		echo isset($res_arr[$res->result]) ? $res_arr[$res->result] : $res->result;
		echo '<br />';
		echo isset($res_arr[$res->errmsg]) ? $res->errmsg : '';
		echo '<br />';
		echo isset($res_arr[$res->errcode]) ? $res->errcode : '';
	}
	
	public function uploadimg(){
		$this->load->model('wx/access_token_model');
		$admin_profiler = $this->session->get_admin_profile();
		$view_params = array ( 'access_token'=>$this->access_token_model->get_access_token($admin_profiler['inter_id']) );
		echo $this->_render_content ( $this->_load_view_file ( 'guid' ), $view_params, TRUE );
	}
}
?>