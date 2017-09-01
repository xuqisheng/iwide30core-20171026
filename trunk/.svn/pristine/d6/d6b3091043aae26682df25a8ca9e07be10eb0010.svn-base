<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Node extends MY_Admin_Priv {

	protected $label_module= NAV_PRIVILEGE;		//统一在 constants.php 定义
	protected $label_controller= '菜单管理';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'core/priv_node';
	}

	public function icons()
	{
		$this->label_action= '图标范例';
		$this->_init_breadcrumb($this->label_action);
	    echo $this->_render_content($this->priv_dir. '/node/icons');
	}
	
	/**
	 * 显示后台菜单左栏
	 * @deprecated
	 * @param String $type
	 */
	public function _show_menu_html()
	{
		return $this->_ajax_menu('html');
	}
	/**
	 * @deprecated json返回左栏菜单
	 */
	public function _show_menu_json()
	{
		return $this->_ajax_menu('json');
	}
	protected function _ajax_menu($type='html')
	{
	    $menu= $this->_load_menu();
		if($type=='json'){
			echo EA_block_admin::inst()->json_menu($menu);;
			
		} else {
			$this->_load_view($this->priv_dir. '/left', array('menu'=> $menu));
		}
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
	
	    $field_name= 'project';
	    $sts= $model->get_project_hash();
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
	        . '"><i class="fa fa-filter"></i> 所属板块</button></div>'
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
	
	public function edit_post()
	{
		$this->label_action= '信息维护';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();

        $this->load->library('form_validation');
        $post= $this->input->post();
        
        if( !$post['p_href'] || $post['p_href']=='#' || strpos($post['p_href'], '/')=== false ){
            $this->session->put_notice_msg('菜单Href属性必须以三段显示“模块/控制器/方法”！');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
        }

        $rulename= str_replace('/', '_', $this->main_model_name());
        if( empty($post[$pk]) ){
            //add data.
            if ($this->form_validation->run($rulename) != FALSE) {
                $result= $model->m_sets($post)->m_save();
                $this->_log($model);
                $message= ($result)? 
                    $this->session->put_success_msg('已新增数据！'): 
                    $this->session->put_notice_msg('此次数据保存失败！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
            
            } else 
			    $model= $this->_load_model();
        } else {
            //edit data.
            if ($this->form_validation->run($rulename) != FALSE) {
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
	
}
