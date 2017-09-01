<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adv extends MY_Admin_Soma {

	protected $label_module= NAV_PACKAGE_GROUPON;		//统一在 constants.php 定义
	protected $label_controller= '首页管理';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'soma/Adv_model';
	}

	public function grid()
	{
		$this->label_action= '焦点图管理';
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;

	    $ent_ids= $this->session->get_admin_hotels();
	    $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );
	     
	    /** 添加 过滤条件js开始  **/
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);

	    if(is_ajax_request())
	        //处理ajax请求，参数规格不一样
	        $get_filter= $this->input->post();
	    else
	        $get_filter= $this->input->get('filter');
	    
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;
	    
	    $sts= $model->get_position_array();
	    $field_name= 'type';
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
	        . '"><i class="fa fa-filter"></i> 位置</button></div>'
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

	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'name'=> array(
	            'field' => 'name',
	            'label' => $labels['name'],
	            'rules' => 'required',
	        ),
	        // 'link'=> array(
	        //     'field' => 'link',
	        //     'label' => $labels['link'],
	        //     'rules' => 'trim|required',
	        // ),
	        'hotel_id'=> array(
	            'field' => 'hotel_id',
	            'label' => $labels['hotel_id'],
	            'rules' => 'trim|required',
	        ),
	        'product_id'=> array(
	            'field' => 'product_id',
	            'label' => $labels['product_id'],
	            'rules' => 'trim|required',
	        ),
	        // 'inter_id'=> array(
	        //     'field' => 'inter_id',
	        //     'label' => $labels['inter_id'],
	        //     'rules' => 'trim|required',
	        // ),
	    );

	    $post['inter_id'] = $this->session->get_admin_inter_id();
	    $param['pid'] = $post['product_id'];
	    // $post['link'] = Soma_const_url::inst()->get_package_detail( $param );

	    //检测并上传文件。
		$post= $this->_do_upload($post, 'logo');
		
	    //$adminid= $this->session->get_admin_id();
	    if( empty($post[$pk]) ){
	        //add data.
	        $this->form_validation->set_rules($base_rules);
	
	        if ($this->form_validation->run() != FALSE) {
	            //$post['add_date']= date('Y-m-d H:i:s');
	            //$post['add_user']= $adminid;

	            $result= $model->m_sets($post)->m_save();
	            $message= ($result)?
		            $this->session->put_success_msg('已新增数据！'):
		            $this->session->put_notice_msg('此次数据保存失败！');
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $this->_load_model();
	
	    } else {
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            $post['last_edit_time']= date('Y-m-d H:i:s');
	            //$post['last_update_user']= $adminid;
	
	            $result= $model->load($post[$pk])->m_sets($post)->m_save();
	            $message= ($result)?
		            $this->session->put_success_msg('已保存数据！'):
		            $this->session->put_notice_msg('此次数据修改失败！');
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
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}

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
	    $url= Soma_const_url::inst()->get_url('*/*/grid');
	    $this->_redirect($url);
	}
	
	/**
	 * 展示前端二维码入口
	 */
	public function qrcode_front()
	{
	    if($id= $this->input->get('ids')){
	        $model_name= $this->main_model_name();
	        $model= $this->_load_model($model_name);
	        $model= $model->load($id);
	        $url= Soma_const_url::inst()->get_front_url($model->m_get('inter_id'), 'soma/package/index', array('id'=> $model->m_get('inter_id') ));
	        $this->_get_qrcode_png($url);
	    } else 
	        echo '参数错误';
	}
	
}
