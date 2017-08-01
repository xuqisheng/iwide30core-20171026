<?php
class Upload extends MY_Front {
	
	function __construct() {

		parent::__construct ();
		$this->sysconfig = $this->config->config;
		$this->load->helper ( array (
				'form',
				'url' 
		) );
	}
	
	function index() {
		
		$this->display('chat/upload_form', array (

				'error' => '' 
		) );
	}
	
	/**
	 * @author 站长清风二次开发
	 */
	function do_upload() {
		
		$config ['upload_path'] = './public/chat/uploads/' . date ( "Ym" ) . '/';
		
		$config ['show_path'] = '/public/chat/uploads/' . date ( "Ym" ) . '/';
		
		if (! is_dir ( $config ['upload_path'] )) {

			$mode=0755;
			
			$this->createdir( $config ['upload_path'] , $mode);
		}
		
		$config ['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
		
		$config ['max_size'] = '20480';
		
		//$config ['max_width'] = '1024';
		
		//$config ['max_height'] = '768';
		
		$config ['file_name'] = 'qf'.date("dHis").rand(1000, 9999);
		
		$this->load->library ( 'upload', $config );
		
		if (! $this->upload->do_upload ()) {

			$error = array (

					'error' => $this->upload->display_errors () 
			);
			
			$this->display('chat/upload_form', $error );
		
		} else {

			$retdata = $this->upload->data ();
			
			$retdata ['upload_path'] = $config ['show_path'];
			
			$retdata ['file_url'] = $this->sysconfig['ftpurl'].$retdata ['upload_path'].$retdata ['file_name'];
			
			$data = array (

					'upload_data' => $retdata 
			);
		
			ini_set ('memory_limit', '1280M');
			
			$recof['image_library'] = 'gd2';
			$recof['source_image'] = $retdata['full_path'];
			//$recof['create_thumb'] = TRUE;
			$recof['maintain_ratio'] = TRUE;
			//$recof['width']     = 1024;
			//$recof['height']   = 768;
			
			
			
			$image_width1 = $retdata['image_width'];
			$image_height1 = $retdata['image_height'];
				
			$newwidth = 0;$newheight = 0;
			if ($image_width1>$image_height1) {
				if ($image_width1>1024) {
					$recof['width']  = 1024;
				}
			}
			else {
				if ($image_height1>768) {
					$recof['height']  = 768;
				}
			}
			
			
			
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
			
			$toftppath = '/public_html'.$retdata['upload_path'];
			$isdir = $this->ftp->list_files($toftppath);
			
			if (empty($isdir)) {$newpath = '/';$arrpath = explode('/', $toftppath);
				foreach ($arrpath as $v) {	if ($v!='') {$newpath = $newpath.$v.'/';
					$isdirchild = $this->ftp->list_files($newpath);if (empty($isdirchild)) {$this->ftp->mkdir($newpath);}	}
				}
			}

			$this->ftp->upload($retdata['full_path'], $toftppath.$retdata['file_name'], 'binary', 0775);
			$this->ftp->close();
			//ftp结束
			$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
			$this->display('chat/upload_success', $data );
		
		}
	}
	
	private function createdir($path, $mode) {
		
		if (is_dir ( $path )) {
		
		
		} else {

			$re = mkdir ( $path, $mode, true );
		
		}
	}
	
}
?>