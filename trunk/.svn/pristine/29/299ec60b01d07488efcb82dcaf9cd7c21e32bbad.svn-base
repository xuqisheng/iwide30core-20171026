<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadftp extends MY_Admin {

	protected $label_module= NAV_BASIC;		//统一在 constants.php 定义
	protected $label_controller= '上传管理';	//在文件定义
	protected $label_action= '';				//在方法中定义

	
	public function __construct() {
	
		parent::__construct ();
		$this->sysconfig = $this->config->config;
		$this->load->helper(array('form', 'url'));

	}
	
	public function index()
	{
		$this->model();
	}
	
	public function ckeditor()
	{
	    $this->label_action= '编辑器上传';
	    $this->_init_breadcrumb($this->label_action);
		echo 'x';

	}

	/**
	 * 商城模块上传处理
	 * @example ../index.php/basic/upload/browse?t=images&p=a23523967|mall|goods|gs_detail&token=35HxSsg6s8g6&CKEditor=el_gs_detail&CKEditorFuncNum=1&langCode=zh-cn
	 * @return [type] [description]
	 */
	public function browse()
	{
		echo '文件列表';
	}

	/**
	 * 商城模块上传处理
	 * @example ../index.php/basic/upload/mall?t=images&p=a23523967|mall|goods|gs_detail&token=35HxSsg6s8g6&CKEditor=el_gs_detail&CKEditorFuncNum=1&langCode=zh-cn
	 *   t:上传类型；image|file|flash
	 *   p:上传路径：分别为公众号/资源名称（同时为控制器）/字段名称
	 *   token:校验token
	 *   后面参数为ckeditor自动追加
	 * @return [type] [description]
	 */
	public function mall_upload()
	{
		$post= $this->input->post();

	}

	public function hotel_upload()
	{
		$post= $this->input->post();
	}

	public function do_upload() {

		if (isset($_FILES['imgFile'])) {
			if ($_FILES['imgFile']['type']=="application/octet-stream") {
				$file_ext = $this->get_ext($_FILES['imgFile']['name']);
				$type = $this->get_mines($file_ext);
				$_FILES['imgFile']['type'] = $type;
			}
		}
		
		$file_system_path = '/public/uploads/' .date("Ym"). '/';
		
		$config['upload_path']      = './public/base/tmp/';
        $config['allowed_types']    = 'gif|jpg|jpeg|png|bmp|swf|flv|swf|flv|mp3|wav|wma|wmv|mid|avi|mpg|asf|rm|rmvb|doc|docx|xls|xlsx|ppt|htm|html|txt|zip|rar|gz|bz2';
        $config['max_size']     = 20480;
        $config['max_width']        = 10240;
        $config['max_height']       = 7680;
                
        $config['file_name'] = 'qf'.date("dHis").rand(1000, 9999);
        
        $this->load->library('upload', $config);

        if ( !$this->upload->do_upload('imgFile') )
        {
            echo json_encode(array('error' => 1, 'message' => strip_tags($this->upload->display_errors())));
        }
        else
       {
            $data = array('upload_data' => $this->upload->data());
            ini_set('memory_limit', '1280M');
            
            $recof['image_library'] = 'gd2';
            $recof['source_image'] = $data['upload_data']['full_path'];
            //$recof['create_thumb'] = TRUE;
            $recof['maintain_ratio'] = TRUE;

            $image_width1 = $data['upload_data']['image_width'];
            $image_height1 = $data['upload_data']['image_height'];
            if ($image_width1>=$image_height1) {
            	if ($image_width1>1024) {
            		$recof['width']  = 1024;
            	}
            }
            /*
            else {
            	if ($image_height1>768) {
            		$recof['height']  = 768;
            	}
            }*/
            $this->load->library('image_lib', $recof);
            $this->image_lib->resize();
            
            //ftp开始
            $this->load->library('ftp');
            $configftp['hostname'] = $this->sysconfig['ftphostname'];
            $configftp['username'] = $this->sysconfig['ftpusername'];
            $configftp['password'] = $this->sysconfig['ftppassword'];
            $configftp['port']     = $this->sysconfig['ftpport'];
            $configftp['passive']  = $this->sysconfig['ftppassive'];
            $configftp['debug']    = $this->sysconfig['ftpdebug'];
            	
            $this->ftp->connect($configftp);
            	
            $toftppath = '/public_html'.$file_system_path;
            $isdir = $this->ftp->list_files($toftppath);
            	
            if (empty($isdir)) {
            	$newpath = '/';$arrpath = explode('/', $toftppath);
            	foreach ($arrpath as $v) {
            		if ($v!='') {
            			$newpath = $newpath.$v.'/';
            			$isdirchild = $this->ftp->list_files($newpath);
            			if (empty($isdirchild)) {
            				$this->ftp->mkdir($newpath);
            			}
            		}
            	}
            }
            
            $this->ftp->upload($data['upload_data']['full_path'], $toftppath.$data['upload_data']['file_name'], 'binary', 0775);
            $this->ftp->close();
            //ftp结束
            
            $username = $this->session->userdata['admin_profile']['username'];
            $inter_id = $this->session->userdata['admin_profile']['inter_id'];
            
            $in['username'] = $username;
            $in['inter_id'] = $inter_id;
            $in['dir'] = date("Ym");
            $in['addtime'] = time();
            
            $in['filesize'] = $data['upload_data']['file_size'];
            $in['filetype'] = $data['upload_data']['image_type'];
            $in['filename'] = $data['upload_data']['client_name'];
            
            $file_domain=empty($this->sysconfig['ftp_cdn_url'])?$this->sysconfig['ftpurl']:$this->sysconfig['ftp_cdn_url'];
            $in['src'] = $file_domain.$file_system_path.$data['upload_data']['file_name'];
            
            $this->db->insert('upload',$in);
            
            $file_url = $in['src'];
            echo json_encode(array('error' => 0, 'url' => $file_url));
            @unlink($config['upload_path'].$config['file_name'].$data['upload_data']['file_ext']);       
            exit;
            
        }
        
        
        
	}
	
	//新增其他类型永久素材
	public function add_material(){
		if (isset($_FILES['imgFile'])) {
			if ($_FILES['imgFile']['type']=="application/octet-stream") {
				$file_ext = $this->get_ext($_FILES['imgFile']['name']);
				$type = $this->get_mines($file_ext);
				$_FILES['imgFile']['type'] = $type;
			}
		}
		
		$file_system_path = '/public/uploads/' .date("Ym"). '/';
		
		$config['upload_path']      = './public/base/tmp/';
        $config['allowed_types']    = 'jpg|png|bmp|jpeg|gif';
        $config['max_size']     = 20480;
        $config['max_width']        = 10240;
        $config['max_height']       = 7680;
                
        $config['file_name'] = 'qf'.date("dHis").rand(1000, 9999);
        
        $this->load->library('upload', $config);

        if ( !$this->upload->do_upload('imgFile') )
        {
            echo json_encode(array('error' => 1, 'message' => strip_tags($this->upload->display_errors())));
        }
        else
       {
            $data = array('upload_data' => $this->upload->data());
            ini_set('memory_limit', '1280M');
            
            $recof['image_library'] = 'gd2';
            $recof['source_image'] = $data['upload_data']['full_path'];
            $recof['maintain_ratio'] = TRUE;

            $image_width1 = $data['upload_data']['image_width'];
            $image_height1 = $data['upload_data']['image_height'];
            if ($image_width1>=$image_height1) {
            	if ($image_width1>1024) {
            		$recof['width']  = 1024;
            	}
            }
            
            $this->load->library('image_lib', $recof);
            $this->image_lib->resize();
            
            //微信上传开始
            $model= $this->_load_model('wx/wxapi_model');
    		$this->load->model('wx/access_token_model');
    		$inter_id= $this->session->get_admin_inter_id();
    		$this->access_token_model->reflash_access_token( $inter_id );
            $access_token= $this->access_token_model->get_access_token( $inter_id );
    		$re = $model->add_material($data['upload_data']['full_path'],$_FILES['imgFile']['name'],'image', $access_token);
    		$re = json_decode($re,true);
            //结束
            
            $username = $this->session->userdata['admin_profile']['username'];
            $inter_id = $this->session->userdata['admin_profile']['inter_id'];
            
            $in['username'] = $username;
            $in['inter_id'] = $inter_id;
            $in['dir'] = date("Ym");
            $in['addtime'] = time();
            
            $in['filesize'] = $data['upload_data']['file_size'];
            $in['filetype'] = $data['upload_data']['image_type'];
            $in['filename'] = $data['upload_data']['client_name'];
            
            $in['src'] = $re['url'];
            
            $this->db->insert('upload',$in);
            
            $file_url = $in['src'];
            if(!isset($re['url'])){
    			echo json_encode(array('code'=>1,'msg'=>$re['errmsg']));
    		}else{
    			echo json_encode(array('code'=>0,'msg'=>'success','url'=>$re['url'],'media_id'=>$re['media_id'],'name'=>$config['file_name'].$data['upload_data']['file_ext']));
    		}
            @unlink($config['upload_path'].$config['file_name'].$data['upload_data']['file_ext']);  
            exit;
            
        }
	}

	//上传图文消息内的图片获取URL
	public function uploadimg(){
		if (isset($_FILES['imgFile'])) {
			if ($_FILES['imgFile']['type']=="application/octet-stream") {
				$file_ext = $this->get_ext($_FILES['imgFile']['name']);
				$type = $this->get_mines($file_ext);
				$_FILES['imgFile']['type'] = $type;
			}
		}
		
		$file_system_path = '/public/uploads/' .date("Ym"). '/';
		
		$config['upload_path']      = './public/base/tmp/';
        $config['allowed_types']    = 'jpg|png';
        $config['max_size']     = 20480;
        $config['max_width']        = 10240;
        $config['max_height']       = 7680;
                
        $config['file_name'] = 'qf'.date("dHis").rand(1000, 9999);
        
        $this->load->library('upload', $config);

        if ( !$this->upload->do_upload('imgFile') )
        {
            echo json_encode(array('error' => 1, 'message' => strip_tags($this->upload->display_errors())));
        }
        else
       {
            $data = array('upload_data' => $this->upload->data());
            ini_set('memory_limit', '1280M');
            
            $recof['image_library'] = 'gd2';
            $recof['source_image'] = $data['upload_data']['full_path'];
            $recof['maintain_ratio'] = TRUE;

            $image_width1 = $data['upload_data']['image_width'];
            $image_height1 = $data['upload_data']['image_height'];
            if ($image_width1>=$image_height1) {
            	if ($image_width1>1024) {
            		$recof['width']  = 1024;
            	}
            }
            
            $this->load->library('image_lib', $recof);
            $this->image_lib->resize();
            
            //微信上传开始
            $model= $this->_load_model('wx/wxapi_model');
    		$this->load->model('wx/access_token_model');
    		$inter_id= $this->session->get_admin_inter_id();
    		$this->access_token_model->reflash_access_token( $inter_id );
            $access_token= $this->access_token_model->get_access_token( $inter_id );
    		$re = $model->uploadimg($data['upload_data']['full_path'],$_FILES['imgFile']['name'], $access_token);
    		$re = json_decode($re,true);
    		
            //结束
            
            $username = $this->session->userdata['admin_profile']['username'];
            $inter_id = $this->session->userdata['admin_profile']['inter_id'];
            
            $in['username'] = $username;
            $in['inter_id'] = $inter_id;
            $in['dir'] = date("Ym");
            $in['addtime'] = time();
            
            $in['filesize'] = $data['upload_data']['file_size'];
            $in['filetype'] = $data['upload_data']['image_type'];
            $in['filename'] = $data['upload_data']['client_name'];
            
            $in['src'] = $re['url'];
            
            $this->db->insert('upload',$in);
            
            $file_url = $in['src'];
            if(!isset($re['url'])){
    			echo json_encode(array('code'=>1,'msg'=>$re['errmsg']));
    		}else{
    			echo json_encode(array('code'=>0,'msg'=>'success','url'=>site_url('publics/material/get_weixin_img').'?url='.$re['url'],'name'=>$config['file_name'].$data['upload_data']['file_ext']));
    		}
            @unlink($config['upload_path'].$config['file_name'].$data['upload_data']['file_ext']);  
            exit;
            
        }
	}

	public function listfiles() {
		
		$username = $this->session->userdata['admin_profile']['username'];
		$inter_id = $this->session->userdata['admin_profile']['inter_id'];
		
		$path = $this->input->get('path');
		$path = substr($path,0,-1);
		if (!$path) {
			$this->db->select ( '*' );
			$this->db->from ( 'upload' );
			$this->db->where ( 'username', $username );
			$this->db->group_by ( 'dir' );
			$result = $this->db->get()->result_array();
			
			foreach ($result as $v) {
				$newfile['is_dir'] = 1;
				$newfile['has_file'] = 1;
				$newfile['filesize'] = 0;
				$newfile['is_photo'] = '';
				$newfile['filetype'] = '';
				$newfile['filename'] = $v['dir'];
				$newfile['datetime'] = date('Y-m-d H:i:s',$v['addtime']);
					
				$newresult[] = $newfile;
			}
			
			$retdata['moveup_dir_path'] = '';
			$retdata['current_dir_path'] = '';
			$retdata['current_url'] = '';
			$retdata['total_count'] = count($result);
			$retdata['file_list'] = $newresult;
			
			echo json_encode($retdata);
		}
		else {
			$this->db->select ( '*' );
			$this->db->from ( 'upload' );
			$this->db->where ( 'username', $username );
			$this->db->where ( 'dir', $path );
	
			$result = $this->db->get()->result_array();
			foreach ($result as $v) {
				$newfile['is_dir'] = '';
				$newfile['has_file'] = '';
				$newfile['filesize'] = $v['filesize'];
				$newfile['dir_path'] = '';
				$newfile['is_photo'] = 1;
				$newfile['filetype'] = $v['filetype'];
				$newfile['filename'] = $v['src'];
				$newfile['fname'] = $v['filename'];
				$newfile['datetime'] = date('Y-m-d H:i:s',$v['addtime']);
				
				$newresult[] = $newfile;
			}
			
			$retdata['moveup_dir_path'] = '';
			$retdata['current_dir_path'] = '';
			$retdata['current_url'] = '';
			$retdata['total_count'] = count($result);
			$retdata['file_list'] = $newresult;
			
			echo json_encode($retdata);
		}		
	}	
	
	private function createdir($path, $mode) {
		if (is_dir ( $path )) {
	
		} else {
			$re = mkdir ( $path, $mode, true );
		}
	}
	
	private function get_ext($file) {
		return pathinfo($file, PATHINFO_EXTENSION);
	}
	
	private function get_mines($type) {
		$mimes = $this->output->mimes;
		
		$nime = $mimes[$type];
		if (is_array($nime)) {
			return $nime[0];
		}
		else {
			return $nime;
		}
	}
}
