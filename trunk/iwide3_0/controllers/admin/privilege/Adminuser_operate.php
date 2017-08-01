<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminuser_operate extends MY_Admin_Priv {

	protected $label_module= NAV_PRIVILEGE;		//统一在 constants.php 定义
	protected $label_controller= '管理员';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'core/priv_admin_operate';
	}

    public function grid()
    {

        $inter_id= $this->session->get_admin_inter_id();

        if($inter_id== FULL_ACCESS){

            $filter= array();
            $admin_edit=1;

        }elseif($inter_id){

            $this->load->model('wx/Public_admin_model');
            $adminid= $this->session->get_admin_id();
            $publics=$this->Public_admin_model->getPublicsById($adminid,$inter_id);

            if(!$publics){
                $publics=$inter_id;
            }else{
                $publics[]=$inter_id;
            };

            $filter= array('inter_id'=>$publics );

        }else {

            $filter= array('inter_id'=>'deny' );

        }
        //print_r($filter);die;

/** 添加 过滤条件js开始  **/
        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);
        
        if(is_ajax_request())
            //处理ajax请求，参数规格不一样
            $get_filter= $this->_ajax_params_parse( $this->input->post(), $model );
        else
            $get_filter= $this->input->get('filter');
        
        if(is_array($get_filter)) $filter= $get_filter+ $filter;

        $this->load->model('core/priv_admin_role');
        $role_array= $this->priv_admin_role->get_role_array();
        $sts= $this->priv_admin_role->array_to_hash($role_array, 'role_label', 'role_id');
        $field_name= 'role_id';
        $ops= '';
        foreach( $sts as $k=> $v){
            if($v !='超级管理员'){
                if( isset($filter[$field_name]) && $filter[$field_name]==$k )
                    $ops.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
                else $ops.= '<option value="'. $k. '">'. $v. '</option>';
            }
        }
        
        if( !isset($filter[$field_name]) || $filter[$field_name]===NULL )
            $active= 'disabled';
        else
            $active= 'btn-success';
        $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
            . '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active
            . '"><i class="fa fa-filter"></i> 权限角色</button></div>'
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

        if(isset($admin_edit)){
            $viewdata['admin']=$admin_edit;
        }
/** 添加 过滤条件js结束  **/

        $this->_grid($filter, $viewdata);
    }

	/**
	 * 客户自助添加账号
	 */
	public function plus()
	{
	    $this->label_action= '修改信息';
	    $this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids'));
		if($id){
			$model= $model->load($id);
		}

$inter_id= $this->session->get_admin_inter_id();
//由于新增账号会跟自身账号的公众号查看权限一致，所以管理员不建议在此添加普通账号
if($inter_id== FULL_ACCESS && !$id ){
    $this->session->put_notice_msg('您是拥有查看所有公众号权限，建议使用管理员方式添加账号');
    $this->_redirect(EA_const_url::inst()->get_url('*/*/edit', array('ids'=> $id )));
}
		
        if(!$model) $model= $this->_load_model();
		$fields_config= $model->get_field_config('form');

		//越权查看数据跳转
		if( !$this->can_edit($model) ){
            $this->session->put_error_msg('无权访问该数据');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}
		
		$this->load->model('core/priv_admin_role', 'admin_role');
		$role_arr= $this->admin_role->get_staff_role_arr();

        $scancode_url= $this->_get_scancode_url($model);
        $consume_url= $this->_get_consume_url($model);

        $this->load->model('core/priv_admin_authid', 'admin_authid');
        $authid_list= $this->_get_authid_list($model);
        
//print_r($authid_list);die;
        
		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'saff_role_arr'=> $role_arr,
            'scancode_url'=> $scancode_url,
            'consume_url'=> $consume_url,
            'authid_model'=> $this->admin_authid,
            'authid_list'=> $authid_list,
		);
		
		$html= $this->_render_content($this->_load_view_file('plus'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	public function plus_post()
	{
	    $this->label_action= '账号信息';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	     
	    $this->load->library('form_validation');
	    $post= $this->input->post();
	    
	    $labels= $model->attribute_labels();
	    $base_rules= array(
            'role_id'=> array(
                'field' => 'role_id',
                'label' => $labels['role_id'],
                'rules' => 'trim|required',
            ),
	        'username'=> array(
	            'field' => 'username',
                'label' => $labels['username'],
	            'rules' => array(
	                'trim',
	                'required',
	                'min_length[5]',
	                'max_length[12]',
	            ),
	        ),
            'password'=> array(
                'field' => 'password',
                'label' => $labels['password'],
                'rules' => array(
                    'trim',
        			'min_length[8]',
        		),
    			'errors' => array(
    				'min_length'=> '密码长度必须大于等于8位的英文+数字',
    			),
            ),
            'password_cf'=> array(
                'field' => 'password_cf',
                'label' => '密码确认',
                'rules' => 'trim|matches[password]',
            ),
            'nickname'=> array(
                'field' => 'nickname',
                'label' => $labels['nickname'],
                'rules' => 'trim|required',
            ),
            'email'=> array(
                'field' => 'email',
                'label' => $labels['email'],
    			'rules' => 'trim|required|valid_email',
            ),
        );

	    //检测并上传文件。
	    $post= $this->_do_upload($post, 'head_pic', 'admin', 'admin_head_pic');
	    $adminid= $this->session->get_admin_id();

$post['inter_id']= $this->session->get_admin_inter_id();
$this->load->model('core/priv_admin_role', 'admin_role');
$r= $this->admin_role->load($post['role_id']);
if( isset($r) && $r->m_get('is_open')== EA_base::STATUS_FALSE && $post['role_id']<=$r::PRESERVE_ID ) 
    $post['role_id']= '';
	    
	    if( empty($post[$pk]) ){
	        //add data.
	        $base_rules['username']['rules'][]= 'is_unique[core_admin.username]';
	        $base_rules['password']['rules'][]= 'required';
	        $base_rules['password']['rules'][]= 'callback__password_check';
	        $this->form_validation->set_rules($base_rules);
	        
	        if ($this->form_validation->run() != FALSE) {
	            $post['password']= $model->encrytion_password($post['password']);
	            $post['create_time']= date('Y-m-d H:i:s');
	            $post['parent_id']= $adminid;
	            
	            $result= $model->m_sets($post)->m_save();
	            $message= ($result)?
    	            //$this->session->put_success_msg('已新增数据！'):
    	            $this->session->put_success_msg('已新增数据！请选择管理员对应的酒店'):
    	            $this->session->put_notice_msg('此次数据保存失败！');
	            //$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	            $this->_redirect(EA_const_url::inst()->get_url('*/*/plus', array('ids'=> $model->insert_id(), 'tab'=> '3' )));

	        } else
	            $model= $this->_load_model();
	        
	    } else {
	        //edit data.
	        //如果勾选修改密码，增加检测规则
    	    if(isset($post['password']))
    	        $base_rules['password']['rules'][]= 'callback__password_check['. $post[$pk]. ']';
	        
    	    $base_rules['username']['rules'][]= 'callback__username_check['. $post[$pk]. ']';
	        $this->form_validation->set_rules($base_rules);
	        
	        if ($this->form_validation->run() != FALSE) {
	            if(isset($post['password']))
	               $post['password']= $model->encrytion_password($post['password']);
				
				if(isset($post['hotel_ids']))
	               $post['entity_id']= implode(',', $post['hotel_ids']);
				else 
				   $post['entity_id']= '';
				
	            $result= $model->load($post[$pk])->m_sets($post)->m_save();

	            $message= ($result)?
    	            $this->session->put_success_msg('已保存数据！'):
    	            $this->session->put_notice_msg('此次数据修改失败！');
				$this->_log($model);
	            $this->_redirect(EA_const_url::inst()->get_url('*/*/staff'));
	
	        } else
	            $model= $model->load($post[$pk]);
	    }
	
	    //验证失败的情况
	    $validat_obj= _get_validation_object();
	    $message= $validat_obj->error_html();
	    //页面没有发生跳转时用寄存器存储消息
	    $this->session->put_error_msg($message, 'register');

	    $this->load->model('core/priv_admin_role', 'admin_role');
	    $role_arr= $this->admin_role->get_staff_role_arr();
	     
	    $fields_config= $model->get_field_config('form');
		$scancode_url= $this->_get_scancode_url($model);
        $consume_url= $this->_get_consume_url($model);

		$this->load->model('core/priv_admin_authid', 'admin_authid');
        $authid_list= $this->_get_authid_list($model);
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
		    'saff_role_arr'=> $role_arr,
			'scancode_url'=> $scancode_url,
            'consume_url'=> $consume_url,
            'authid_model'=> $this->admin_authid,
            'authid_list'=> $authid_list,
	    );
		
		$html= $this->_render_content($this->_load_view_file('plus'), $view_params, TRUE);
	    echo $html;
	}

	public function _list($filter= array(), $viewdata=array())
	{
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	
	    //filter params: the same with table fields...
	    //sort params: sort_direct, sort_field
	    //page params: page_size, page_num
	    $params= $this->input->get();
	    if(is_array($filter) && count($filter)>0 )
	        $params= array_merge($params, $filter);
	
	    if(is_ajax_request()){
	        //处理ajax请求
	        $result= $model->filter_json($params );
	        echo json_encode($result);
	        	
	    } else {
	        //HTML输出
	        $this->label_action= '账号列表';
	        $this->_init_breadcrumb($this->label_action);
	
	        //base grid data..
	        $result= $model->filter($params);
	        $fields_config= $model->get_field_config('grid');
	        $default_sort= $model::default_sort_field();
	        	
	        $view_params= array(
	            'model'=> $model,
	            'result'=> $result,
	            'fields_config'=> $fields_config,
	            'default_sort'=> $default_sort,
	        );
	        $view_params= $view_params+ $viewdata;
	
	        $html= $this->_render_content($this->_load_view_file('staff'), $view_params, TRUE);
	        //echo $html;die;
	        echo $html;
	    }
	}
	public function delete()
	{
	    $this->_del('grid');
	}
	public function remove()
	{
	    $this->_del('staff');
	}
	public function _del($return_view='grid')
	{
	    try {
	        $model_name= $this->main_model_name();
	        $model= $this->_load_model($model_name);
	
	        $ids= explode(',', $this->input->get('ids'));

	        $message= '';
	        $protect_ids= $model->proected_ids;
	        foreach ($ids as $k=>$v){
	            if(in_array($v, $protect_ids)){
	                unset($ids[$k]);
	                $message = '提交数据中有被保护的账号，不能删除。';
	            }
	        }
	        if(count($ids)>0 )
	            $result= $model->delete_in($ids);
	
	        if( $result ){
	            $this->session->put_success_msg("删除成功". $message);
	
	        } else {
	            $this->session->put_error_msg('删除失败');
	        }
	
	    } catch (Exception $e) {
	        $message= '删除失败过程中出现问题！';
	        //$message= $e->getMessage();
	        $this->session->put_error_msg('删除失败');
	    }
	    $url= EA_const_url::inst()->get_url('*/*/'. $return_view);
	    $this->_redirect($url);
	}
	
	
	
	
	
	
	/**
	 * 编辑个人信息
	 */
	public function profile()
	{
	    $this->label_action= '个人资料';
	    $this->_init_breadcrumb($this->label_action);

	    $profile= $this->session->admin_profile;
	    $id= $profile['admin_id'];
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name)->load($id);
        $fields_config= $model->get_field_config('form');
	    
		$scancode_url= $this->_get_scancode_url($model);
        $consume_url= $this->_get_consume_url($model);

		$this->load->model('core/priv_admin_authid', 'admin_authid');
        $authid_list= $this->_get_authid_list($model);
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'profile'=> $profile,
	        'check_data'=> FALSE,
            'scancode_url'=> $scancode_url,
            'consume_url'=> $consume_url,
            'authid_model'=> $this->admin_authid,
            'authid_list'=> $authid_list,
	    );
	    $html= $this->_render_content($this->_load_view_file('profile'), $view_params, TRUE);
	    echo $html;
	}

	public function profile_post()
	{
	    $this->label_action= '个人资料';
	    $this->_init_breadcrumb($this->label_action);
	    $this->load->library('form_validation');
	    $post= $this->input->post();

	    $profile= $this->session->admin_profile;
	    $id= $profile['admin_id'];
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name)->load($id);
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'nickname'=> array(
	            'field' => 'nickname',
	            'label' => $labels['nickname'],
	            'rules' => 'trim|required',
	        ),
	        'email'=> array(
	            'field' => 'email',
	            'label' => $labels['email'],
	            'rules' => 'trim|required|valid_email',
	        ),
	        'password'=> array(
	            'field' => 'password',
	            'label' => $labels['password'],
	            'rules' => array(
	                'trim',
	                'min_length[8]',
	            ),
	            'errors' => array(
	                'min_length'=> '密码长度必须大于等于8位的英文+数字',
	            ),
	        ),
	        'password_cf'=> array(
	            'field' => 'password_cf',
	            'label' => '密码确认',
	            'rules' => 'trim|matches[password]',
	        ),
	    );
	    
	    //如果勾选修改密码，增加检测规则
	    if(isset($post['password'])) 
	        $base_rules['password']['rules'][]= 'callback__password_check['. $id. ']';

	    //检测并上传文件。
	    $post= $this->_do_upload($post, 'head_pic', 'admin', 'admin_head_pic');
	    
	    $this->form_validation->set_rules($base_rules);
        $redirect= FALSE;
	    if ($this->form_validation->run() != FALSE) {
            if(isset($post['password'])){
                $post['password']= $model->encrytion_password($post['password']);
                $result= $model->m_sets($post)->m_save();
                $message= ($result)?
                    $this->session->put_success_msg('您已修改了密码，请重新登录！', 'register'):
                    $this->session->put_notice_msg('此次数据修改失败！', 'register');
				$this->_log($model);
                $redirect= TRUE;
                
            } else {
                $result= $model->m_sets($post)->m_save();
                $message= ($result)?
                    $this->session->put_success_msg('已保存数据！', 'register'):
                    $this->session->put_notice_msg('此次数据修改失败！', 'register');
                //刷新会话信息
                $this->session->reflash_profile($model);
            }
        } else {
            //验证失败的情况
            $validat_obj= _get_validation_object();
            $message= $validat_obj->error_html();
            //页面没有发生跳转时用寄存器存储消息
            $this->session->put_error_msg($message, 'register');
        }
        $fields_config= $model->get_field_config('form');

        $scancode_url= $this->_get_scancode_url($model);
        $consume_url= $this->_get_consume_url($model);

        $this->load->model('core/priv_admin_authid', 'admin_authid');
        $authid_list= $this->_get_authid_list($model);
        $view_params= array(
            'model'=> $model,
            'fields_config'=> $fields_config,
            'check_data'=> TRUE,
            'scancode_url'=> $scancode_url,
            'consume_url'=> $consume_url,
            'authid_list'=> $authid_list,
            'authid_model'=> $this->admin_authid,
            'redirect'=> $redirect,
        );
        $html= $this->_render_content($this->_load_view_file('profile'), $view_params, TRUE);
        echo $html;
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



		//越权查看数据跳转
		if( !$this->can_edit($model) ){
            $this->session->put_error_msg('无权访问该数据');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

		$scancode_url= $this->_get_scancode_url($model);
        $consume_url= $this->_get_consume_url($model);

		$this->load->model('core/priv_admin_authid', 'admin_authid');
        $authid_list= $this->_get_authid_list($model);
        unset($fields_config['role_id']['select'][1]);
		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
			'scancode_url'=> $scancode_url,
            'consume_url'=> $consume_url,
            'authid_model'=> $this->admin_authid,
            'authid_list'=> $authid_list,
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
            'role_id'=> array(
                'field' => 'role_id',
                'label' => $labels['role_id'],
                'rules' => 'trim|required',
            ),
            'inter_id'=> array(
                'field' => 'inter_id',
                'label' => $labels['inter_id'],
                'rules' => 'trim|required',
            ),
	        'username'=> array(
	            'field' => 'username',
                'label' => $labels['username'],
	            'rules' => array(
	                'trim',
	                'required',
	                'min_length[5]',
	                'max_length[12]',
	            ),
	        ),
            'password'=> array(
                'field' => 'password',
                'label' => $labels['password'],
                'rules' => array(
                    'trim',
        			'min_length[8]',
        		),
    			'errors' => array(
    				'min_length'=> '密码长度必须大于等于8位的英文+数字',
    			),
            ),
            'password_cf'=> array(
                'field' => 'password_cf',
                'label' => '密码确认',
                'rules' => 'trim|matches[password]',
            ),
            'nickname'=> array(
                'field' => 'nickname',
                'label' => $labels['nickname'],
                'rules' => 'trim|required',
            ),
            'email'=> array(
                'field' => 'email',
                'label' => $labels['email'],
    			'rules' => 'trim|required|valid_email',
            ),
        );

	    //检测并上传文件。
	    $post= $this->_do_upload($post, 'head_pic', 'admin', 'admin_head_pic');
	    $adminid= $this->session->get_admin_id();
	    
	    if( empty($post[$pk]) ){
	        //add data.
	        $base_rules['username']['rules'][]= 'is_unique[core_admin.username]';
	        $base_rules['password']['rules'][]= 'required';
	        $base_rules['password']['rules'][]= 'callback__password_check';
	        $this->form_validation->set_rules($base_rules);
	        
	        if ($this->form_validation->run() != FALSE) {
	            $post['password']= $model->encrytion_password($post['password']);
	            $post['create_time']= date('Y-m-d H:i:s');
	            $post['parent_id']= $adminid;
	            
	            $result= $model->m_sets($post)->m_save();
	            $message= ($result)?
    	            //$this->session->put_success_msg('已新增数据！'):
    	            $this->session->put_success_msg('已新增数据！请选择管理员对应的酒店'):
    	            $this->session->put_notice_msg('此次数据保存失败！');
	            //$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	            $this->_redirect(EA_const_url::inst()->get_url('*/*/edit', array('ids'=> $model->insert_id(), 'tab'=> '3' )));

	        } else
	            $model= $this->_load_model();
	        
	    } else {
	        //edit data.
	        //如果勾选修改密码，增加检测规则
    	    if(isset($post['password']))
    	        $base_rules['password']['rules'][]= 'callback__password_check['. $post[$pk]. ']';
	        
    	    $base_rules['username']['rules'][]= 'callback__username_check['. $post[$pk]. ']';
	        $this->form_validation->set_rules($base_rules);
	        
	        if ($this->form_validation->run() != FALSE) {
	            if(isset($post['password']))
	               $post['password']= $model->encrytion_password($post['password']);
				
				if(isset($post['hotel_ids']))
	               $post['entity_id']= implode(',', $post['hotel_ids']);
				else 
				   $post['entity_id']= '';

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

		$scancode_url= $this->_get_scancode_url($model);
        $consume_url= $this->_get_consume_url($model);

		$this->load->model('core/priv_admin_authid', 'admin_authid');
        $authid_list= $this->_get_authid_list($model);
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
			'scancode_url'=> $scancode_url,
            'consume_url'=> $consume_url,
            'authid_model'=> $this->admin_authid,
            'authid_list'=> $authid_list,
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
	public function _username_check($username, $id)
	{
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $admin= $model->load_by_username($username);
	    if( $admin && $admin->admin_id!= $id){
	        $this->form_validation->set_message('_username_check', $username .'"已经被占用了  。');
	        return FALSE;
	    } else 
	        return TRUE;
	}
	
	/**
	 * 这里只考虑勾选修改密码的情况，因为 勾选没填写/没有勾选的值均为NULL，无法判断
	 * 可以返回 TRUE/FALSE，也可返回一个处理后的值。
	 */
	public function _password_check($str, $edit_id=false)
	{
	    if( $edit_id>0 && $str==NULL ){
	        $this->form_validation->set_message('_password_check', '密码不能为空。');
	        return FALSE;
	    }
	    if ( in_array($str, array('12345678','11111111')) ) {
	        $this->form_validation->set_message('_password_check', '{field}不能用 "'. $str .'" 这种简单密码。');
	        return FALSE;
	        
	    } else {
	        return TRUE;
	    }
	}

	public function show_consume_code()
	{
	    $inter_id= $this->input->get('id');
	    $url= front_site_url($inter_id). '/mall/handle/consume?id='. $inter_id;
	    $this->_get_qrcode_png($url);
	}
	private function _get_consume_url($model)
	{
	    $inter_id= $model->m_get('inter_id');
        return EA_const_url::inst()->get_url('*/*/show_consume_code'). '?id='. $inter_id;
	}
	private function _get_scancode_url($model)
	{
        $this->load->helper('encrypt');
        $encrypt_util= new Encrypt();
		$admin_id= $encrypt_util->encrypt( $model->m_get('admin_id') );
        return EA_const_url::inst()->get_url('*/auth/admin_qrcode'). '?id='. base64_encode($admin_id);
	}

    private function _get_authid_list($model)
    {
        $this->load->model('core/priv_admin_authid', 'admin_authid');
        if( $admin_id= $model->m_get('admin_id')){
            //$filter= array('admin_id'=>$admin_id );
            
            $inter_id= $this->session->get_temp_inter_id();
            if( !$inter_id ) $inter_id= $this->session->get_admin_inter_id();
            
            if( $inter_id !=FULL_ACCESS ) {
                $filter= array('inter_id'=> $inter_id );
                
            } else {
                $filter= array();
            }
            
            $list= $this->admin_authid->get_data_filter($filter);
            return $list;
            
        } else {
            return array();
        }
    }
    
    public function authid_handle()
    {
        $do= $this->input->get('do');
        $id= $this->input->get('ids');
        
        $this->load->model('core/priv_admin_authid');
        $model= $this->priv_admin_authid;
        if($do=='toggle'){
            $model->load($id)->status_toggle();
        } else if($do='remove'){
            $model->load($id)->m_delete();
        }
        $this->session->put_success_msg('操作成功！');
        $admin_id= $this->input->get('aid');
        if($this->action=='plus')
            $this->_redirect(EA_const_url::inst()->get_url('*/*/plus', array('ids'=> $admin_id, 'tab'=> '4') ));
        else 
            $this->_redirect(EA_const_url::inst()->get_url('*/*/profile'));
    }
    
    public function ajax_admin_hotels()
    {
        $result= array('status'=>2, 'html'=>'<select></select>', 'message'=>'失败');
        $inter_id= $this->input->post('id');
        $admin_id= $this->input->post('admin_id');
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
        $hotel_ids= $model->load($admin_id)->m_get('entity_id');
        $hotel_ids= explode(',',$hotel_ids);
        
        $this->load->model('hotel/hotel_ext_model', 'hotel_model');
        $result= $this->hotel_model->find_all(array('inter_id'=> $inter_id));
        
        if(count($result)>0){
            $result= $this->hotel_model->array_to_hash($result, 'name', 'hotel_id');
            $html= $this->hotel_model->hash_to_optionhtml($result, $hotel_ids);
            $result['html']= "<select multiple class='form-control' name='hotel_ids[]' style='min-height:400px;'>{$html}</select>";
            $result['status']=1;
            $result['message']= '成功返回';
            
        } else {
            $result['message']= '该公众号下没有任何酒店';
        }
        echo json_encode($result);
    }


    public function edit_publics(){

        $inter_id= $this->session->get_admin_inter_id();

        if($inter_id!= FULL_ACCESS){

            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));

        }

        $admin_id=$this->input->get('ids');

        $this->load->model("core/Priv_admin_operate");

        $publics=$this->Priv_admin_operate->getEditPublics($admin_id);

        if($publics){
            $data['publics']=$publics;
        }

        $allPublics=$this->Priv_admin_operate->getAllInter();

        $data['allPublics']=$allPublics;

        $html= $this->_render_content($this->_load_view_file('edit_publics'), $data, TRUE);
        echo $html;

    }



    public function save_public_edit(){

        $post=$this->input->post();

        if(!empty($post['admin_id'])&&!empty($post['publics'])){
            $this->load->model("core/Priv_admin_operate");
            $result=$this->Priv_admin_operate->update_publics($post);

            $message= ($result)?
            $this->session->put_success_msg('已保存数据！'):
            $this->session->put_notice_msg('此次数据修改失败！');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));

        }else{

            $this->session->put_notice_msg('此次数据修改失败！');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
        }

    }


    public function can_edit($model)
    {
        $this->load->model('wx/Public_admin_model');
        $adminid= $this->session->get_admin_id();
        $inter_id= $this->session->get_admin_inter_id();
        $publics=$this->Public_admin_model->getPublicsById($adminid,$inter_id);

        if(!$publics){
            $publics=$inter_id;
        }else{
            $publics[]=$inter_id;
        };

        if( !$model || !$model->table_primary_key() ){
            return TRUE;
        }
        $pk= $model->table_primary_key();
        if( !$model->m_get($pk)  ){
            return TRUE;
        }
        $inter_id= $this->session->get_admin_inter_id();
        if( $inter_id== FULL_ACCESS ){
            return TRUE;
        }
        if(in_array($model->m_get('inter_id'),$publics)){
            return TRUE;
        }
        return TRUE;
    }
	
}
