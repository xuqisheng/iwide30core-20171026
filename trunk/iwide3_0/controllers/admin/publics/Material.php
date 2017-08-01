<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material extends MY_Admin {

	protected $label_module= '素材管理';
	protected $label_controller= '素材列表';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'wx/wxapi_model';
	}

	public function grid()
	{
		$html= $this->_render_content($this->_load_view_file('index'));
		echo $html;	
	}
	//获取素材列表
	public function ajax_get_materials()
	{
		$type = $this->input->get('type');
		$p = $this->input->get('page');
		$count = 9;
		$offset = ($p-1)*$count;
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$this->load->model('wx/access_token_model');
		$inter_id= $this->session->get_admin_inter_id();
        $access_token= $this->access_token_model->get_access_token( $inter_id );
		$res = $model->batchget_material($type,$offset,$count,$access_token);
		$res = json_decode($res,true);
		$res['page'] = $p;
		echo json_encode($res);

	}
	//新增/编辑图文消息
	public function edit_news()
	{
		$this->label_action= '素材编辑';
		$this->_init_breadcrumb($this->label_action);
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$media_id = $this->input->get('media_id');
		
		$data = array();
		if($media_id){
			$this->load->model('wx/access_token_model');
			$inter_id= $this->session->get_admin_inter_id();
	        $access_token= $this->access_token_model->get_access_token( $inter_id );
			$material = $model->get_material($media_id,$access_token);
			$material = json_decode($material,true);
			foreach ($material['news_item'] as $k => $v) {
				$material['news_item'][$k]['content'] = str_replace('data-src="http://','src="'.site_url('publics/material/get_weixin_img').'?url=http://',$material['news_item'][$k]['content']);
			}
			$material['content'] = $material;
			$material['media_id'] = $media_id;
			unset($material['news_item']);
			$data['material'] = json_encode($material);
		}

		$html= $this->_render_content($this->_load_view_file('edit'), $data, TRUE);
		echo $html;
	}
	
	//更新指定图文
	public function update_news()
	{
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		//获取数据
		$media_id = $this->input->post('media_id');
		$articles = $this->input->post('articles');
		$flag = false;
		$msg = '保存失败';
		if(empty($articles)){
			echo json_encode(array('code'=>1,'msg'=>$msg));exit;
		}
		//检查/整理数据
		$del_url = site_url('publics/material/get_weixin_img').'?url=';
		foreach($articles as $ak=>$article){
			if(empty($article['title']) || empty($article['content']) || empty($article['author']) || empty($article['thumb_media_id'])){
				echo json_encode(array('code'=>1,'msg'=>'缺少必填项'));exit;
			}
			unset($articles[$ak]['thumb_media_url']);
			$articles[$ak]['content'] = str_replace($del_url , '' ,$articles[$ak]['content']);
			$articles[$ak]['show_cover_pic'] = 0;
			// if(!empty($article['thumb_media_id'])){
			// }else{
			// 	$articles[$ak]['show_cover_pic'] = 0;
			// }
			if(isset($article['content_source_url_key'])){
				unset($articles[$ak]['content_source_url_key']);
			}
		}
		$this->load->model('wx/access_token_model');
		$inter_id= $this->session->get_admin_inter_id();
        $access_token= $this->access_token_model->get_access_token( $inter_id );
		if($media_id){
			$material = $model->get_material($media_id,$access_token);
			$material = json_decode($material,true);
			//判断需要更新的index
			if(isset($material['news_item'])){
				$flag = true;
				foreach ($material['news_item'] as $k => $v) {
					unset($v['url']);
					if(json_encode($articles[$k]) != json_encode($v)){//图文有改动
						$re = $model->update_news($media_id, $k ,$access_token,$articles[$k]);
						$re = json_decode($re,true);
						if($re['errcode'] !=0 ){
							$flag = false;
							break;
						}
					}
				}
			}
		}else{
			//新增
			$re = $model->add_news($articles,$access_token);
			$re = json_decode($re,true);
			if(isset($re['media_id'])){
				$flag = true;
			}
		}
		if($flag){
			echo json_encode(array('code'=>0,'msg'=>'success','media_id'=>$re['media_id']));
		}else{
			if(isset($k)&&isset($re['errmsg'])){
				$msg = '第'.($k+1).'个图文保存失败,原因：'.$re['errmsg'];
			}
			echo json_encode(array('code'=>1,'msg'=>$msg));
		}
	}
	
	

	//删除永久素材
	public function ajax_del_material(){
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$media_ids = $this->input->get('media_ids');
		$this->load->model('wx/access_token_model');
		$inter_id= $this->session->get_admin_inter_id();
        $access_token= $this->access_token_model->get_access_token( $inter_id );
		$re = $model->del_material($media_ids,$access_token);
		echo json_encode($re);
		
	}
	
	//代理访问微信图片
	public function get_weixin_img(){
		header("Content-Type:image/png");
		/*换一张空白图片，如果遇到错误，需要用上*/
		$im = imagecreate(600, 300);
		$white = imagecolorallocate($im, 255, 255, 255);
		/*获取图片的真实地址*/
		$url = $this->input->get('url');
		if (!$url) {
		    imagettftext($im, 18, 0, 200, 100, $white, FD_PUBLIC."/fonts/hwxh.ttf", "Error 001");
		    imagettftext($im, 14, 0, 150, 150, $white, FD_PUBLIC."/fonts/hwxh.ttf", "请在参数中输入图片的绝对地址。");
		    imagepng($im);
		    exit();
		}

		$ua = 'MQQBrowser/26 Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; MB200 Build/GRJ22; CyanogenMod-7) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		@$imgString = curl_exec($ch);
		curl_close($ch);

		if ($imgString == "") {
		    imagettftext($im, 18, 0, 200, 100, $white, FD_PUBLIC."fonts/hwxh.ttf", "Error 002");
		    imagettftext($im, 14, 0, 70, 150, $white, FD_PUBLIC."fonts/hwxh.ttf", "加载远程图片失败，请确认图片的地址能正常访问。");
		    imagepng($im);
		    exit();
		}
		/*如果没有错误*/
		$im = imagecreatefromstring($imgString);
		imageAlphaBlending($im, true);
		imageSaveAlpha($im, true);
		imagepng($im);

	}

}
