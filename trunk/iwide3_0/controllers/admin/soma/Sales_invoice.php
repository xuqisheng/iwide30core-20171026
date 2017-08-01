<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_invoice extends MY_Admin_Soma {

	const DEV = true;

	protected function main_model_name() {
		return 'soma/Sales_invoice_model';
	}

	// 显示预订单列表
	public function grid() {
		$this->label_action= '发票列表';
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
	    
	    if(is_ajax_request())
	        //处理ajax请求，参数规格不一样
	        $get_filter= $this->_ajax_params_parse( $this->input->post(), $model );
	    
	    else
	        $get_filter= $this->input->get('filter');
	    
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;
	    
	    $sts= $model->get_status_label();
	    $ops= '';
	    foreach( $sts as $k=> $v){
	        if( isset($filter['status']) && $filter['status']==$k ) $ops.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
	        else $ops.= '<option value="'. $k. '">'. $v. '</option>';
	    }
	    
	    if( !isset($filter['status']) || $filter['status']===NULL )
	        $active= 'disabled';
	    else
	        $active= 'btn-success';

	    if($inter_id == FULL_ACCESS && !$this->current_inter_id ){
	        $export_btn= '';
	        $batch_btn='';
	    } else {
	        $export_btn= '<span class="input-group-btn"><button id="export_order_btn" type="button" class="btn btn-sm btn-success"><i class="fa fa-download"></i> 导出</button></span>';
	        $batch_url = Soma_const_url::inst()->get_url('*/*/batch' );
	        $batch_btn= '<span class="input-group-btn"><button id="batch_btn" type="button" class="btn btn-sm btn-success"><a href="'
	            .$batch_url.'" style="color:#fff;" ><i class="fa fa-upload"></i> 去批量发货</a></button></span>';
	    }
	    
	    $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
	        . '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-filter"></i> 状态</button></div>'
            . '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
            . '<option value="-">全部</option>'. $ops
			. '</select>' 
			. $export_btn
			. $batch_btn
			. '</div>';
	    
	    //echo $ops;die;
	    $current_url= current_url();
	    $export_url= Soma_const_url::inst()->get_url('*/*/export_list' );
	    $jsfilter= <<<EOF
$('#filter_status').change(function(){
    var go_url= '?'+ $(this).attr('name')+ '='+  $(this).val();
    //alert(go_url);
    if($(this).val()=='-') window.location= '{$current_url}';
    else window.location= '{$current_url}'+ go_url;
});
$('#export_order_btn').click(function(){
    var status= $('#filter_status').val();
    var url= '{$export_url}';
    if( !isNaN(status) ){
	    var p= '?status='+ status;
	} else {
	    var p= '';
	}
	window.location= url+= p;
});
EOF;
        $viewdata= array(
	        'js_filter_btn'=> $jsfilter_btn,
	        'js_filter'=> $jsfilter,
        );
        // var_dump($filter);exit;
        $this->_grid($filter, $viewdata);
    }

	public function edit() { 
		$this->label_action= '发票邮寄信息处理';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids', true));
		if($id){
			$model= $model->load($id);
		}

		if(!$model) $model= $this->_load_model();
		$fields_origin= $model->get_field_config('form');
		$fields_config = array();
		$form_fields = $model->form_fields();

		foreach ($form_fields as $f) {
			$fields_config[$f] = $fields_origin[$f];
		}

		$waitting_un_show = array('post_admin','post_admin_ip','commet');

		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'waitting_un_show'=> $waitting_un_show,
		    'check_data'=> FALSE,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	public function edit_post() {

		$this->label_action= '发票邮寄信息处理';
		$this->_init_breadcrumb($this->label_action);
		
		$post = $this->input->post(null,true);

		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($post['invoice_id']);
		if($id){
			$model= $model->load($id);
		}

		if(!$model) $model= $this->_load_model();

		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}
		
		$data = array();
		if($model->m_get('status') == $model::STATUS_APPLY) {
			$data['distributor'] = $post['distributor'];
			$data['tracking_no'] = $post['tracking_no'];
			$data['post_time'] = date('Y-m-d H:i:s');
			$admin = $this->session->admin_profile;
			$data['post_admin'] = $admin['username'];
			$CI =& get_instance();
			$data['post_admin_ip'] = $CI->input->ip_address();
			$data['status'] = $model::STATUS_SHIPPED;
		} else {
			$data['commet'] = $post['commet'];
		}

		try {
			$model->m_sets($data)->m_save();	
			$this->session->put_success_msg('操作成功');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		} catch (Exception $e) {
			$this->session->put_error_msg('保存数据失败，请稍后重新尝试');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

	}
	
	public function export_list() {
		
	    $create_time = $this->input->get('create_time');
	    $post_time = $this->input->get('post_time');
	    $status = $this->input->get('status');
	    $inter_id= $this->session->get_admin_inter_id();

	    if($inter_id == FULL_ACCESS ){
	        $inter_id= $this->current_inter_id;
	    }
	    
	    $filter= array();
	    if($create_time) $filter['create_time']= $create_time;
	    if($post_time) $filter['post_time']= $post_time;
	    if($status) $filter['status']= $status;
	    $filter['inter_id'] = $inter_id;

	    $this->load->model('soma/Sales_invoice_model', 'i_model');
        $select= $this->i_model->get_export_fields();
	    $data= $this->i_model->export_item( $select, $filter );

        // print_r($data);die;
	    // $header= array('配送ID','订单编号','配送商','配送单号','邮寄时间','地址信息','联系人','联系电话','当前状态' );
	    $header = $this->i_model->get_export_header();
	    $url= $this->_do_export($data, $header, 'csv', TRUE );
	}

	// 显示批量发货页面
	public function batch() {
		$this->label_action= '邮寄信息处理';
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
	        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	    }
	    
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> FALSE,
	        'current_inter_id'=> $this->current_inter_id,
	        'distributor_list'=>$model->get_distributor_select_option(),
	    );
	    
	    $html= $this->_render_content($this->_load_view_file('batch'), $view_params, TRUE);
	    //echo $html;die;
	    echo $html;
	}

	/**
	 * 权限校验-->输入校验-->数据提取-->数据保存
	 * @return [type] [description]
	 */
	public function batch_post() {
		try {
			if(!$this->_privilege_validation()) {
				$this->session->put_error_msg('没有权限进行该操作');
		        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
			}	

			$post = $this->input->post(null, true);
			$file = $_FILES;
			$data = array_merge($post, $file);
			$this->load->model($this->main_model_name(), 'i_model');	

			if($this->i_model->batch_data_validation($data)) {
				$fmt_data = $this->i_model->format_batch_data($data);		
				if($this->i_model->batch_save($fmt_data)) {
					$this->session->put_success_msg('操作成功');
		        	$this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		        } else {
		        	$this->session->put_error_msg('操作失败，请稍后重新尝试');
		        	$this->_redirect(Soma_const_url::inst()->get_url('*/*/batch'));
		        }
			} else {
				$this->session->put_error_msg('数据提交有误，操作失败，请稍后重新尝试');
		        $this->_redirect(Soma_const_url::inst()->get_url('*/*/batch'));
			}
		} catch (Exception $e) {
			$this->session->put_error_msg('操作异常，请稍后重新尝试');
		    $this->_redirect(Soma_const_url::inst()->get_url('*/*/batch'));
		}

	}

	protected function _privilege_validation($id = null) {
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		if($id){ $model= $model->load($id); }
		if(!$model) { $model= $this->_load_model(); }

		if( !$this->_can_edit($model) ){ return false; }
		return true;
	}

}