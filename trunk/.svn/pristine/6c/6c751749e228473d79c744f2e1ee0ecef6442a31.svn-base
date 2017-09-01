<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forminput extends MY_Admin {

	protected $label_module= '';		//统一在 constants.php 定义
	protected $label_controller= '输入项管理';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'report/Forminput_model';
	}
	
	public function grid()
	{
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		//print_r($filter);die;
		
		 
		$this->_grid($filter);
	}
	
	
	public function edit_post()
	{
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$pk= $model->table_primary_key();
		$post= $this->input->post();
		
		
		$data = array();
		$ids = intval($this->input->post("ids"));
		$cid = intval($this->input->post("cid"));

				
		$data['iname'] = $this->input->post("iname");
		$data['itype'] = $this->input->post('itype');
		$data['fieldmatch'] = htmlspecialchars($this->input->post('fieldmatch'));
		$data['filedoption'] = htmlspecialchars($this->input->post('filedoption'));
		$data['isempty'] = intval($this->input->post('isempty'));
		$data['isshow'] = intval($this->input->post('isshow'));
		$data['listorder'] = intval($this->input->post('listorder'));
		$data['errinfo'] = htmlspecialchars($this->input->post('errinfo'));
		$data['addtime'] = time();
		
			
		if (strlen($data['iname'])<1) {
			echo 'err:1';die();
		}
		if (strlen($data['itype'])<1) {
			echo 'err:2';die();
		}
		if ($data['itype'] == 'text' || $data['itype'] == 'textarea') {
			if (strlen($data['fieldmatch'])<3) {
				echo 'err:3';die();
			}
		}
		
		if ($ids) {

			$this->db->update('custom_input',$data,array('id'=>$ids));
			$inputobj= $this->db->select("*")->get_where('custom_input', array('id'=>$ids))->result_array();
			$cid = $inputobj[0]['cid'];
			

		} else {
			$data['cid'] = $cid;
			$this->db->insert('custom_input',$data);
		}

		$this->session->put_success_msg('操作成功！');
		$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
	
	}
	
	public function delete()
	{

		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
	
		$ids= explode(',', $this->input->get('ids'));
		$result= $model->delete_in($ids);
	
		if( $result ){
			$this->session->put_success_msg("删除成功");
	
		} else {
			$this->session->put_error_msg('删除失败');
		}

		$url= EA_const_url::inst()->get_url('*/suform');
		$this->_redirect($url);
	}
	
}
