<?php

class MY_Admin_Priv extends MY_Admin {

    protected $label_module= NAV_PRIVILEGE;		//统一在 constants.php 定义

	/**
	 * 删除和批量删除
	 */
	public function delete()
	{
	    try {
    		$model_name= $this->main_model_name();
    		$model= $this->_load_model($model_name);
    		
    		$ids= explode(',', $this->input->get('ids'));
    		$result= $model->delete_in($ids);
    		
    		if( $result ){
                $this->session->put_success_msg("删除成功");
    		    
    		} else {
                $this->session->put_error_msg('删除失败');
    		}
    		
	    } catch (Exception $e) {
	        $message= '删除失败过程中出现问题！';
	        //$message= $e->getMessage();
            $this->session->put_error_msg('删除失败');
	    }
	    $url= EA_const_url::inst()->get_url('*/*/grid');
	    $this->_redirect($url);
	}
	
}
