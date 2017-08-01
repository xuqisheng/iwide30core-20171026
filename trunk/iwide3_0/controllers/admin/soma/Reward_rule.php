<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reward_rule extends MY_Admin_Soma {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '分销奖励规则';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'soma/Reward_rule_model';
	}

	public function grid()
	{
	    $this->label_action= '奖励规则';
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;

	    $ent_ids= $this->session->get_admin_hotels();
	    $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );

	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	     
/* 兼容grid变为ajax加载加这一段 */
	    if(is_ajax_request())
	        //处理ajax请求，参数规格不一样
	        $get_filter= $this->_ajax_params_parse( $this->input->post(), $model );
	    else
	        $get_filter= $this->input->get('filter');
	     
	    if( !$get_filter) $get_filter= $this->input->get('filter');
	     
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;
/* 兼容grid变为ajax加载加这一段 */

        $sts= $model->get_status_label();
        $ops= '';
        foreach( $sts as $k=> $v){
	        if( isset($filter['status']) && $filter['status']==$k ) $ops.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
	        else $ops.= '<option value="'. $k. '">'. $v. '</option>';
	    }
	    
	    if( !isset($filter['status']) || $filter['status']===NULL )
	        $active= '';
	    else
	        $active= 'btn-success';
	    
	    $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
	        . '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-filter"></i> 状态</button></div>'
            . '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
            . '<option value="-">全部</option>'. $ops
            . '</select>'
            . '</div>';
	    
	    $current_url= current_url();
	    $jsfilter= <<<EOF
$('#filter_status').change(function(){
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
	    $this->_grid($filter, $viewdata);
	}

	public function edit()
	{
	    $this->label_action= '修改规则';
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
	    if( !$this->_can_edit($model) ){
	        $this->session->put_error_msg('找不到该数据');
	        $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	    }

	    $inter_id = $model->m_get('inter_id');
	    if(empty($inter_id))
	    {
	    	$inter_id= $this->session->get_admin_inter_id();
	    }
	    $this->load->library('Soma/Api_idistribute');
	    $api = new Api_idistribute();
	    $group_list = $api->get_hotel_group_info($inter_id);
	    $group_hash = array();
	    foreach($group_list as $group_info)
	    {
	    	$group_hash[ $group_info['group_id'] ] = $group_info['group_name'];
	    }
		
		$group_compose = explode(',', $model->m_get('group_compose'));

	    // $inter_id= $this->session->get_admin_inter_id();
	    $this->load->model('soma/Product_package_model');
	    $product_model= $this->Product_package_model;
	    $products= $product_model->get_package_list($inter_id, array('inter_id'=>$inter_id));
	    $view_params= array(
	        'model'=> $model,
	        'products'=> $products,
	        'group_hash' => $group_hash,
	        'group_compose' => $group_compose,
	        'fields_config'=> $fields_config,
	        'check_data'=> FALSE,
	    );
	
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    //echo $html;die;
	    echo $html;
	}

	public function edit_post()
	{
	    $this->label_action= '修改规则';
	    $this->_init_breadcrumb($this->label_action);
    	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();

	    if($post['reward_source'] == Reward_rule_model::REWARD_SOURCE_FANS_SALER)
	    {
	    	// 泛分销固定是全员绩效
	    	$post['group_mode'] = Reward_rule_model::GROUP_MODE_ALL;
	    	$post['group_compose'] = array();
	    }

	    if($post['group_mode'] == Reward_rule_model::GROUP_MODE_SPEC)
	    {
	    	if(empty($post['group_compose'])
	    		|| !is_array($post['group_compose']))
	    	{
	    		$this->session->put_error_msg('按分组计算绩效时必须选择分组！');
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit', array('ids'=> $this->input->post('rule_id') ) ));
	    	}
	    	else
	    	{
	    		$post['group_compose'] = ',' . implode(',', $post['group_compose']) . ',';
	    	}
	    }
	    else
	    {
	    	$post['group_compose'] = '';
	    }
	    
	    $inter_id= $this->session->get_admin_inter_id();
		if( $inter_id==FULL_ACCESS || empty($post['inter_id']) ) 
		    $post['inter_id'] = $inter_id;
     
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'name'=> array(
	            'field' => 'name',
	            'label' => $labels['name'],
	            'rules' => 'trim|required',
	        ),
	        'reward_type'=> array(
	            'field' => 'reward_type',
	            'label' => $labels['reward_type'],
	            'rules' => 'trim|required',
	        ),
	        'rule_type'=> array(
	            'field' => 'rule_type',
	            'label' => $labels['rule_type'],
	            'rules' => 'trim|required',
	        ),
	        //'hotel_id'=> array(
	        //    'field' => 'hotel_id',
	        //    'label' => $labels['hotel_id'],
	        //    'rules' => 'trim|required',
	        //),
	        // 'inter_id'=> array(
	        //     'field' => 'inter_id',
	        //     'label' => $labels['inter_id'],
	        //     'rules' => 'trim|required',
	        // ),
	    );
	    if( $post['reward_type']== $model::REWARD_TYPE_PERCENT ){
	        if( $post['reward_rate']<0 || $post['reward_rate']>1 ){
	            $this->session->put_notice_msg('奖励计算方式为按百分比时，奖励额度必须在 0到1之间！');
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit', array('ids'=> $this->input->post('rule_id') ) ));
	        }
	    }
	    
	    if( $post['p_type']== 'all' ) $post['product_ids']= '';
	    if( !$post['start_time'] || $post['start_time']== '0000-00-00 00:00:00' ) $post['start_time']= NULL;
	    if( !$post['end_time'] || $post['end_time']== '0000-00-00 00:00:00' ) $post['end_time']= NULL;

	    if( empty($post[$pk]) ){
	        //add data.
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            $post['create_time']= date('Y-m-d H:i:s');
	            $post['create_admin']= $this->session->get_admin_username();
	            $result= $model->_m_save($post);
	            $message= ($result)?
    	            $this->session->put_success_msg('已新增数据！'):
    	            $this->session->put_notice_msg('此次数据保存失败！');
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $this->_load_model();
	         
	    } else {
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            $post['update_time']= date('Y-m-d H:i:s');
	            $post['update_admin']= $this->session->get_admin_username();
	            $result= $model->load($post[$pk])->m_sets($post)->m_save();
	            $message= ($result)?
    	            $this->session->put_success_msg('已保存数据！'):
    	            $this->session->put_notice_msg('此次数据修改失败！');
	            $this->_log($model);
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $model->load($post[$pk]);
	    }
	
	    //验证失败的情况
	    $validat_obj= _get_validation_object();
	    $message= $validat_obj->error_html();
	    //页面没有发生跳转时用寄存器存储消息
	    $this->session->put_error_msg($message, 'register');
	    $fields_config= $model->get_field_config('form');

	    $this->load->model('soma/Product_package_model');
	    $product_model= $this->Product_package_model;
	    $products= $product_model->get_package_list($inter_id, array('inter_id'=>$inter_id));
	    $view_params= array(
	        'model'=> $model,
	        'products'=> $products,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}
	
	
}
