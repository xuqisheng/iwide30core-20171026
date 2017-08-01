<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminrole extends MY_Admin_Priv {

	protected $label_module= NAV_PRIVILEGE;		//统一在 constants.php 定义
	protected $label_controller= '权限管理';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'core/priv_admin_role';
	}

	public function grid()
	{
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;
	
	    /** 添加 过滤条件js开始  **/
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	
	    if(is_ajax_request())
	        //处理ajax请求，参数规格不一样
	        $get_filter= $this->input->post();
	    else
	        $get_filter= $this->input->get('filter');
	
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;

	    $sts= $model->is_open_label();
	    $field_name= 'is_open';
	    $ops= '';
	    foreach( $sts as $k=> $v){
	        if( isset($filter[$field_name]) && $filter[$field_name]==$k )
	            $ops.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
	        else $ops.= '<option value="'. $k. '">'. $v. '</option>';
	    }
	
	    if( !isset($filter[$field_name]) || $filter[$field_name]===NULL )
	        $active= 'disabled';
	    else
	        $active= 'btn-success';
	    $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
	        . '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active
	        . '"><i class="fa fa-filter"></i> 开放商户</button></div>'
	            . '<select class="form-control input-sm" name="filter['. $field_name. ']" id="filter_'. $field_name. '" >'
	                . '<option value="-">全部</option>'. $ops
	                . '</select>'
	                    . '</div>';
	
	    //echo $ops;die;
	    $current_url= current_url();
	    $jsfilter= <<<EOF
$('#filter_{$field_name}').change(function(){
	var go_url= '?'+ $(this).attr('name')+ '='+  $(this).val();
	//alert(go_url);
	if($(this).val()=='-') window.location= '{$current_url}';
	else window.location= '{$current_url}'+ go_url;
});
EOF;
	    $viewdata= array(
	        'js_filter_btn'=> $jsfilter_btn,
	        'js_filter'=> $jsfilter,
	    );
	    /** 添加 过滤条件js结束  **/
	
	    $this->_grid($filter, $viewdata);
	}
	
	public function edit()
	{
	    $this->label_action= '信息维护';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	
	    $id= intval($this->input->get('ids'));
		if($id){
			$model= $model->load($id);
		}

        if(!$model) $model= $this->_load_model();
		$fields_config= $model->get_field_config('form');

	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> FALSE,
	    );
	
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    //echo $html;die;
	    echo $html;
	}
	
	public function edit_post()
	{
	    $this->label_action= '信息维护';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	     
	    $this->load->library('form_validation');
	    $post= $this->input->post();

	    $labels= $model->attribute_labels();
	    $base_rules= array(
            'module'=> array(
	            'field' => 'module',
                'label' => $labels['module'],
	            'rules' => 'trim|required',
	        ),
            'role_name'=> array(
	            'field' => 'role_name',
                'label' => $labels['role_name'],
	            'rules' => array(
					'trim','required',
				),
	        ),
            'role_label'=> array(
	            'field' => 'role_label',
                'label' => $labels['role_label'],
	            'rules' => 'trim|required',
	        ),
            'acl_type'=> array(
	            'field' => 'acl_type',
                'label' => '权限类型',
	            'rules' => 'trim|required',
	        ),
            'acl_detail'=> array(
	            'field' => 'acl_detail',
                'label' => '权限明细',
				'errors' => array('required'=>'您选了定制权限，但还没有勾选任何可用模块。'),
	        ),
	    );

		//增加验证条件
		if( isset($post['acl_type']) && $post['acl_type']== $model::ROLE_TYPE_DEFINE  && !isset($post['acl_detail']) ){
			$this->session->put_error_msg('您选了定制权限，但还没有勾选任何可用模块。');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

		//权限信息转换
	    if(isset($post['acl_type']) && $post['acl_type']== $model::ROLE_TYPE_ALL ){
			$post['acl_desc']= serialize( array(ADMINHTML=> FULL_ACCESS ) );

		} else if(isset($post['acl_type']) && $post['acl_type']== $model::ROLE_TYPE_DEFINE && !empty($post['acl_detail']) ){
			/** Array ( 
				[privilege] => Array (
					[adminlog] => Array ( 
						[edit] => on [edit_post] => on [index] => on [add] => on [grid] => on [delete] => on 
					) 
				) 
			)
			*/
			//print_r($post['acl_detail']);die;
			foreach($post['acl_detail'] as $k=>$v){
				foreach($v as $sk=>$sv){
					$post['acl_detail'][$k][$sk]= array_keys($sv);
				}
			}
			$post['acl_desc']= serialize( array(ADMINHTML=> $post['acl_detail'] ));
		} 

	    if( empty($post[$pk]) ){
	        //add data.
	        $base_rules['role_name']['rules'][]= 'is_unique[core_admin_role.role_name]';
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            $post['create_time']= date('Y-m-d H:i:s');
	            $result= $model->m_sets($post)->m_save();
	            $message= ($result)?
	            $this->session->put_success_msg('已新增数据！'):
	            $this->session->put_notice_msg('此次数据保存失败！');
	            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $this->_load_model();
	    } else {
	        //edit data.
    	    $base_rules['role_name']['rules'][]= 'callback__rolename_check['. $post[$pk]. ']';
	        $this->form_validation->set_rules($base_rules);

	        if ($this->form_validation->run() != FALSE) {
	            $post['update_time']= date('Y-m-d H:i:s');
	            $result= $model->load($post[$pk])->m_sets($post)->m_save();
	            $message= ($result)?
	            $this->session->put_success_msg('已保存数据！'):
	            $this->session->put_notice_msg('此次数据修改失败！');
				$this->_log($model);
	            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $model->load($post[$pk]);
	    }
	
	    //验证失败的情况
	    $validat_obj= _get_validation_object();
	    $message= $validat_obj->error_html();
	    //页面没有发生跳转时用寄存器存储消息
	    $this->session->put_error_msg($message, 'register');
	
	    $fields_config= $model->get_field_config('form');
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}
	
	/**
	 * 修改信息时，坚持是否存在同名。
	 * @param String $username
	 * @param Number $id
	 * @return boolean
	 */
	public function _rolename_check($rolename, $id)
	{
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $role= $model->get_data_filter( array('role_name'=>$rolename) );
	    if( count($role)>0 && $role['0']['role_id']!= $id){
	        $this->form_validation->set_message('_rolename_check', $rolename .'"已经被占用了  。');
	        return FALSE;
	    } else 
	        return TRUE;
	}
	
	
}
