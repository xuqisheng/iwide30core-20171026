<?php

class No_auth extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		if(isset($_GET['debug'])){
			$this->output->enable_profiler(true);
		}
	}
	
	function file2base64(){
		try {
			echo base64_encode(file_get_contents( $this->input->get('url')));
		}catch (Exception $e){
			echo 'error';
		}
	}
	function base64_2file(){
		try {
			$data = $this->input->post('url');
			$file_name = $this->input->get('id').'_'.$this->input->get('qid').'.png';
			file_put_contents('./'.$file_name,base64_decode($data));
			
// 			$this->ftp->mkdir('/public_html/foo/bar/', 0755);
			
			$this->ftp= $this->_ftp_server('prod');
			$base_path= 'media/distribute/';
			$to_file = $this->ftp->floder. FD_PUBLIC. '/'. $base_path;
			$up_path = realpath('./').'/'.$file_name;
			$this->ftp->upload($up_path, $to_file.$file_name, 'binary', 0775);
			$this->ftp->close();
			
			@unlink($file_name);
			$upload_url= $this->ftp->weburl. '/'. FD_PUBLIC. '/media/distribute/'.$file_name;
			
			//保存上传完之后的URL
			echo $upload_url;
			
		}catch (Exception $e){
			echo 'error';
		}
	}
}