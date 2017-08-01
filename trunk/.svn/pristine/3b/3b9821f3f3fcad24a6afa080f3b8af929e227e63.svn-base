<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_news extends MY_Admin {

	protected $label_module= '群发功能';
	protected $label_controller= '群发管理';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'wx/wxapi_model';
	}

	public function send()
	{
	    $model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$this->load->model('wx/access_token_model');
		$inter_id= $this->session->get_admin_inter_id();
        $access_token= $this->access_token_model->get_access_token( $inter_id );
		$tags = $model->get_tags($access_token);
		$data = json_decode($tags,true);
		$media_id = $this->input->get('media_id');
		if($media_id){
			$material = $model->get_material($media_id,$access_token);
			$material = json_decode($material,true);
			$material['content']['news_item'] = $material['news_item'];
			$material['media_id'] = $media_id;
			unset($material['news_item']);
			$data['material'] = json_encode($material);
		}
		$html= $this->_render_content($this->_load_view_file('send'),$data,TRUE);
		echo $html;
	}

	//异步发送信息
	public function ajax_send()
	{
		$result = $this->input->post('param');
		$type_arr = array('news','text','image');
		if(!isset($result['type']) || !in_array($result['type'], $type_arr)){
			echo json_encode(array('code'=>1,'msg'=>'错误发送类型'));exit;
		}
		if(!isset($result['sendways']) || (!isset($result['content']) && !isset($result['media_id']) )){
			echo json_encode(array('code'=>1,'msg'=>'缺少必填项'));exit;
		}
		if($result['sendways']==0){
			$tag_id = 0;
		}else{
			$tag_id = $result['tag_id'];
		}
		$send_ignore_reprint = 1;//1为继续群发（转载）
		if($result['type']!='text'){
			$param = array('media_id'=>$result['media_id']);
		}else{
			$param = array('content'=>$result['content']);
		}
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$this->load->model('wx/access_token_model');
		$inter_id= $this->session->get_admin_inter_id();
        $access_token= $this->access_token_model->get_access_token( $inter_id );
		$re = $model->sendall($result['type'],$tag_id,$send_ignore_reprint,$param,$access_token);

		//记录日志
		$this->load->model('hotel/Hotel_log_model');
		$this->Hotel_log_model->add_admin_log('Wxapi/sendnew#',$result['type'],array('tag_id' => $tag_id,'param' => $param,'re'=>$re));

		$re = json_decode($re,true);
		if($re['errcode'] != 0 ){
			echo json_encode(array('code'=>1,'msg'=>$re['errmsg']));
		}else{
			echo json_encode(array('code'=>0,'msg'=>'success'));
		}
	}
}
