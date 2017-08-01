<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Codegen extends MY_Admin {

	protected $label_module= NAV_BASIC;		//统一在 constants.php 定义
	protected $label_controller= '代码生成器';	//在文件定义
	protected $label_action= '';				//在方法中定义

	public function index()
	{
		$this->model();
	}
	
	public function model()
	{
	    $this->label_action= '自动生成Model';
	    $this->_init_breadcrumb($this->label_action);
	
		$post= $this->input->post();
		if( isset( $post['db_resource']) ){
		    $db_resource= $this->load->database(($post['db_resource']), TRUE);
		} else {
		    $db_resource= $this->db;
		}
		//print_r($post);die;

		if( !isset($post['table']) || !isset($post['class']) || !isset($post['template']) || !isset($post['path']) ){
			$view_params= array(
				'prefix'=> $db_resource->dbprefix,
				'parent'=> 'MY_Model',
				'path'=> 'models/',
			);

		} else {
			if( isset($post['table'])&& !$db_resource->table_exists($post['table'])){
				$this->session->put_error_msg("数据表'{$post['table']}'不存在！");
				$this->_redirect(EA_const_url::inst()->get_url('*/*/model'));
			}
			
			$attributes = $db_resource->list_fields($post['table']);
			$fields = $db_resource->field_data($post['table']);
			//print_r($fields);die;
			$pk= '';
			foreach($fields as $v){
				if($v->primary_key==1) $pk= $v->name;
			}
			$param= array(
				'class'=> $post['class'],
				'parent'=> $post['parent'],
				'table'=> $post['table'],
				'pk'=> $pk,
				'attributes'=> $attributes,
				'fields'=> $fields,
			);
			$template= $this->module. DS. $this->controller. DS. $post['template'];
			$html= $this->_load_content(substr($template, 0, -4), $param, TRUE);

			$post['file']= str_replace(array("\n","\t","\s"), array('<br/>','&nbsp;&nbsp;&nbsp;&nbsp;','&nbsp;'), $html);
			$view_params= $post;
		}
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		echo $html;
	}


	public function save()
	{
		$post= $this->input->post();
		
		if( isset( $post['db_resource']) ){
		    $db_resource= $this->load->database(($post['db_resource']), TRUE);
		} else {
		    $db_resource= $this->db;
		}
		//print_r($db_resource);die;
		
	    if( isset($post['table'])&& !$db_resource->table_exists($post['table'])){
			$this->session->put_error_msg("数据表'{$post['table']}'不存在！");
			$this->_redirect(EA_const_url::inst()->get_url('*/*/model'));
		}
		if( isset($post['path'])&& ! file_exists( APPPATH. $post['path'] ) ){
		    @mkdir(APPPATH. $post['path'], '755' );
		}
			
		$attributes = $db_resource->list_fields($post['table']);
		$fields = $db_resource->field_data($post['table']);
		//print_r($fields);die;
		$pk= '';
		foreach($fields as $v){
			if($v->primary_key==1) $pk= $v->name;
		}
		$param= array(
			'class'=> $post['class'],
			'parent'=> $post['parent'],
			'table'=> $post['table'],
			'pk'=> $pk,
			'attributes'=> $attributes,
			'fields'=> $fields,
		);
		$template= $this->module. DS. $this->controller. DS. $post['template'];
		$html= $this->_load_content(substr($template, 0, -4), $param, TRUE);

		$file_path= APPPATH. $post['path']. DS. ucfirst($post['class']). '.demo.php';
		$file = fopen($file_path, "w");
		fwrite($file, "<?php \n". $html);
	    fclose($file);
		$this->session->put_success_msg("文件{$file_path}已经生成。");
		$this->_redirect(EA_const_url::inst()->get_url('*/*/model'));
	}


}
