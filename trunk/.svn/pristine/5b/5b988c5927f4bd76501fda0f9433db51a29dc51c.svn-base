<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends MY_Admin {

	protected $label_module= NAV_BASIC;			//统一在 constants.php 定义
	protected $label_controller= '上传管理';	//在文件定义
	protected $label_action= '';				//在方法中定义

	protected function main_model_name()
	{
		return 'basic/notice';
	}

	public function index()
	{
		$this->model();
	}
	
	public function grid()
	{
	    $this->_redirect('privilege/auth/nofound');
	}


}
