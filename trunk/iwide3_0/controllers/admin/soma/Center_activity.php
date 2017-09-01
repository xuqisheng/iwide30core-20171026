<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Center_activity extends MY_Admin_Soma {
    
	protected $label_module= NAV_PACKAGE_GROUPON;		//统一在 constants.php 定义
	protected $label_controller= '同步活动';		//在文件定义
	protected $label_action= '';				//在方法中定义

	public function __construct()
	{
		parent::__construct();

		//防止其他账号使用该功能。
	    $this->_center_writelist();
	}

	protected function main_model_name()
	{
		return 'soma/Center_activity_model';
	}

	public function grid()
	{

		$this->label_action= '同步活动列表';
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;
	    // $filter = array('inter_id'=>'a471258436');
	    
	    // $ent_ids= $this->session->get_admin_hotels();
	    // $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    // if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );
	    
        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);

/* 兼容grid变为ajax加载加这一段 */
	    if(is_ajax_request())
	        //处理ajax请求，参数规格不一样
	        $get_filter= $this->input->post();
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

		$this->label_action= '同步活动修改';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids'));

		$model= $model->load($id);
        if(!$model){
        	$this->session->put_error_msg('操作出错。请稍后再试');
	        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
        }
		$fields_config= $model->get_field_config('form');

		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		}

		$temp_id= $this->session->get_temp_inter_id();
	    if($temp_id) $inter_id= $temp_id;
	    else $inter_id= $this->session->get_admin_inter_id();
	    
	    $disabled = FALSE;
		if( $inter_id == FULL_ACCESS ){
			$disabled = TRUE;
		}

		$act_info = array();
		$item_labels = array();
		$act_type_label = array();
		if( $model->m_get( 'sync_type' ) == $model::SYNC_TYPE_KILLSEC ){
			$this->load->model('soma/Activity_killsec_model','ActivityModel');
		}elseif( $model->m_get( 'sync_type' ) == $model::SYNC_TYPE_GROUPON ){
			$this->load->model('soma/Activity_groupon_model','ActivityModel');
		}

		$ActivityModel = $this->ActivityModel;
		$act_info = $ActivityModel->load( $model->m_get('act_id') )->m_data();
		$item_labels = $ActivityModel->attribute_labels();
		$Activity_status = $ActivityModel->act_type_status();
		if( count( $act_info ) > 0 ){
			unset($act_info['hotel_inter_id']);
			unset($act_info['hotel_hotel_id']);
			foreach ($act_info as $k => $v) {
				if( $k=='act_type' ){
					$act_info[$k] = $Activity_status[$v];
				}

				if( $k=='status' ){
					$act_info[$k] = Soma_base::get_status_options()[$v];
				}

				if( $k=='is_subscribe' ){
					$act_info[$k] = $ActivityModel->get_status_can_label()[$v];
				}

				if( $k=='is_sync_center' ){
					$act_info[$k] = $ActivityModel->get_sync_status_label()[$v];
				}
			}
		}

		$view_params= array(
			'disabled'=> $disabled,
		    'model'=> $model,
		    'act_info'=> $act_info,
		    'item_labels'=> $item_labels,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
//		    'options'=>$options,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}
	
	public function edit_post()
	{
	    
		//如果状态传过来的是未审核，返回失败。因为未审核这个状态位是酒店同步到中心平台的状态位
		//后台操作都要添加操作日志，写入数据库。之前商城已经有方法，可以直接调用

	    $this->label_action= '信息维护';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();

	    $this->load->library('form_validation');
	    $post= $this->input->post();
	    if( !isset( $post[$pk] ) ){
	    	$this->session->put_notice_msg('操作出错。请稍后再试');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	    }

	    if( $post['status'] == $model::STATUS_UNREVIEW ){
            $this->session->put_notice_msg('状态不能修改为未审核！请重新选择状态位的值');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit?ids='.$post[$pk]));
	    }

	    $temp_id= $this->session->get_temp_inter_id();
	    if($temp_id) $inter_id= $temp_id;
	    else $inter_id= $this->session->get_admin_inter_id();

	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'sort'=> array(
	            'field' => 'sort',
	            'label' => $labels['sort'],
	            'rules' => 'trim|required',
	        ),
	    );

        $this->form_validation->set_rules($base_rules);
        if ($this->form_validation->run() != FALSE) {

        	$post_admin = $this->session->get_admin_username();
		    $remote_ip = $this->input->ip_address();
		    $post['center_update_time'] = date('Y-m-d H:i:s');
		    $post['center_post_admin'] = $post_admin;
		    $post['center_post_admin_ip'] = $remote_ip;

            $result= $model->load($post[$pk])->m_sets($post)->m_save($post);
            
            $message= ($result)?
	            $this->session->put_success_msg('已保存数据！'):
	            $this->session->put_notice_msg('此次数据修改失败！');
	            $this->_log($model);
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));

        } else {
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
	
	}
    
}
