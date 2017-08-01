<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_package extends MY_Admin_Soma {
    
	protected $label_module= NAV_PACKAGE_GROUPON;		//统一在 constants.php 定义
	protected $label_controller= '活动管理';		//在文件定义
	protected $label_action= '';				//在方法中定义

// 	protected function get_act_type_label()
// 	{
// 		return array(
// 				self::GROUPON=>'团购',
// 				self::COUPON=>'优惠券',
// 			);
// 	}
	
	protected function main_model_name()
	{
		return 'soma/activity_groupon_model';
	}

	public function grid()
	{
		$this->label_action= '活动列表';
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
	        $get_filter= $this->input->post();
	    else
	        $get_filter= $this->input->get('filter');
	    
	    if( !$get_filter) $get_filter= $this->input->get('filter');
	    
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;
/* 兼容grid变为ajax加载加这一段 */

        $sts= Soma_base::get_status_options();
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
		$this->label_action= '活动修改';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids'));
// var_dump( $id );exit;
		$model= $model->load($id);
        if(!$model) $model= $this->_load_model();
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

// 		$options = '';
// 		$act_type = $this->get_act_type_label();
// 		foreach( $act_type as $k=>$v ){
// 			$options .= '<option value="'.$k.'">'.$v.'</option>';
// 		}
		$this->load->model('soma/Activity_model');

		$view_params= array(
			'disabled'=> $disabled,
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'ActivityModel'=> $this->Activity_model,
//		    'options'=>$options,
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

	    $temp_id= $this->session->get_temp_inter_id();
	    if($temp_id) $inter_id= $temp_id;
	    else $inter_id= $this->session->get_admin_inter_id();
		$post['inter_id'] = isset( $post['inter_id'] ) ? $post['inter_id'] : $inter_id;
// var_dump( $post);exit;
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'act_name'=> array(
	            'field' => 'act_name',
	            'label' => $labels['act_name'],
	            'rules' => 'trim|required',
	        ),
	        // 'product_id'=> array(
	        //     'field' => 'product_id',
	        //     'label' => $labels['product_id'],
	        //     'rules' => 'trim|required',
	        // ),
	        'hotel_id'=> array(
	            'field' => 'hotel_id',
	            'label' => $labels['hotel_id'],
	            'rules' => 'trim|required',
	        ),
	        // 'inter_id'=> array(
	        //     'field' => 'inter_id',
	        //     'label' => $labels['inter_id'],
	        //     'rules' => 'trim|required',
	        // ),
	        'group_count'=> array(
	            'field' => 'group_count',
	            'label' => $labels['group_count'],
	            'rules' => 'trim|required',
	        ),
	        'group_deadline'=> array(
	            'field' => 'group_deadline',
	            'label' => $labels['group_deadline'],
	            'rules' => 'trim|required',
	        ),
	    );

	    //检测并上传文件。
		$post= $this->_do_upload($post, 'banner_url');

		//检测活动结束时间是否是开始时间大7天内
		$startTime = isset( $post['start_time'] ) ? strtotime( $post['start_time'] ) : NULL;
		$endTime = isset( $post['end_time'] ) ? strtotime( $post['end_time'] ) : NULL;
		if( !$startTime || !$endTime ){
			$this->session->put_notice_msg('此次数据修改失败，请设置好活动的开始和结束时间！');
	        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		}

		// if( $startTime >= $endTime ){
		// 	$this->session->put_notice_msg('此次数据修改失败，结束时间不能少于等于开始时间！');
	 //        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		// }

		// if( ( $startTime  + 7*24*60*60 ) < $endTime ){
		// 	$this->session->put_notice_msg('此次数据修改失败，活动的持续时间不能超过7天(结束时间－开始时间少于等于7天)！');
	 //        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		// }

	    //团购价
	    $group_price = isset( $post['group_price'] ) ? $post['group_price'] + 0 : 0;
	    if( $group_price < 0 ){ 
	    	$post['group_price'] = 0;
	    }else{ 
	    	$post['group_price'] = $group_price;
	    }

	    //团购人数
		$group_count = isset( $post['group_count'] ) ? $post['group_count'] + 0 : 0;
		if( $group_count < 0 ){ 
			$post['group_count'] = 0;
		}else{ 
			$post['group_count'] = $group_count;
		}

		//时间限制 天
		$group_deadline = isset( $post['group_deadline'] ) ? $post['group_deadline'] + 0 : 1;
		if( $group_deadline < 0 ){ 
			$this->session->put_notice_msg('持续时间不能为负数！');
	        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		}elseif( $group_deadline > 7 ){
			$this->session->put_notice_msg('持续时间不能超过7天！');
	        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		}else{ 
			$post['group_deadline'] = $group_deadline;
		}

		//商品名称
        $this->load->model( 'soma/product_package_model', 'product_package' );
        $product_id = isset( $post['product_id'] ) ? $post['product_id'] : NULL;
        if( $product_id ){
	        $product_info = $this->product_package->get_product_package_detail_by_product_id( $product_id, $inter_id );
	        if( $product_info ){
	            $post['product_name'] = $product_info['name'];
	        }else{
	            $post['product_name'] = '';
	        }
        }else{
        	$post['product_name'] = NULL;
        }

// var_dump( $post );exit;
	    if( empty($post[$pk]) ){
	        //add data.

	        $post['create_time'] = date( 'Y-m-d H:i:s', time() );

	        $this->form_validation->set_rules($base_rules);
	         
	        if ($this->form_validation->run() != FALSE) {

	        	// $model->product = $post;
// $model->product['product_id'] = 10010;//测试商品id
	            $inter_id = $this->session->get_admin_inter_id();
	            $result = $model->_activity_save( $post, $inter_id );

	            $message= ($result)?
    	            $this->session->put_success_msg('已新增数据！'):
    	            $this->session->put_notice_msg('此次数据保存失败！');
    	            $this->_log($model);
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $this->_load_model();
	         
	    } else {
	    	
	    	$post['act_type'] = $model::ACT_TYPE_GROUPON;

	        $this->form_validation->set_rules($base_rules);
	         
	        if ($this->form_validation->run() != FALSE) {
                //
// 	            $result= $model->load($post[$pk])->m_sets($post)->m_save();
	            
	            // $model->product = $post;
	            $inter_id = $this->session->get_admin_inter_id();
	            $result = $model->_activity_edit( $post, $inter_id );
	            
	            $message= ($result)?
    	            $this->session->put_success_msg('已保存数据！'):
    	            $this->session->put_notice_msg('此次数据修改失败！');
    	            $this->_log($model);
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $model->load($post[$pk]);
	    }

// 	    $options = '';
// 		$act_type = $this->get_act_type_label();
// 		foreach( $act_type as $k=>$v ){
// 			$options .= '<option value="'.$k.'">'.$v.'</option>';
// 		}
	
	    //验证失败的情况
	    $validat_obj= _get_validation_object();
	    $message= $validat_obj->error_html();
	    //页面没有发生跳转时用寄存器存储消息
	    $this->session->put_error_msg($message, 'register');
		
		$this->load->model('soma/Activity_model');

	    $fields_config= $model->get_field_config('form');
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	        'ActivityModel'=> $this->Activity_model,
// 	        'options'=>$options,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}

	public function delete()
	{
		
	}
 
    
}
