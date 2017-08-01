<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_rule extends MY_Admin_Soma {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '促销规则';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'soma/Sales_rule_model';
	}

	public function grid()
	{
	    $this->label_action= '促销规则';
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
	    $this->label_action= '修改规则';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk = $model->table_primary_key();
	
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
	// var_dump( $model );die;

	    //TRUE为标记拦截管理员权限用户操作
	    $inter_id= $this->_get_real_inter_id(TRUE);
	    
	    $disabled = FALSE;
		if( $inter_id == FULL_ACCESS ){
			$disabled = TRUE;
		}

	    $this->load->model('soma/Product_package_model');
	    $product_model= $this->Product_package_model;
	    $products= $product_model->get_package_list($inter_id, array('inter_id'=>$inter_id));

	    $rule_id = $model->m_get($pk);
	    if( $rule_id ){
	    	$selectProducts = $model->get_rule_product_list( $rule_id, $inter_id );
	    }else{
	    	$selectProducts = array();
	    }
	    
	    // var_dump( $selectProducts );
	    $view_params= array(
	        'model'=> $model,
	        'disabled'=> $disabled,
	        'products'=> $products,
	        'fields_config'=> $fields_config,
	        'check_data'=> FALSE,
			'selectProducts'=> $selectProducts,
	    );

	    /** 积分级别拉取 **/
	    $this->load->library('Soma/Api_member');
	    $api= new Api_member( $inter_id );
	    $result= $api->get_token();
	    $api->set_token($result['data']);
	    $lvl_data= $api->member_lvl();
	    //print_r($lvl_data);die;
	     
	    $bonus_data= $bonus_array= array();
	    if( $bonus_size= $model->m_get('bonus_size') )
	        $bonus_array= json_decode( $bonus_size, TRUE );
	     
	    foreach ($lvl_data['data'] as $obj ){
	        $bonus_data[$obj->member_lvl_id]['name']= $obj->lvl_name;
	        if( array_key_exists($obj->member_lvl_id, $bonus_array ) ){
	            $bonus_data[$obj->member_lvl_id]['size']= $bonus_array[$obj->member_lvl_id];
	        }else {
	            $bonus_data[$obj->member_lvl_id]['size']= $obj->consume_bonus_size;
	        }
	    }
	    $view_params['inter_id']= $inter_id;
	    $view_params['bonus_data']= $bonus_data;
        //var_dump($bonus_data, $bonus_array);die;
	    /** 积分级别拉取 **/
	    
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
// var_dump( $post );die;
	    $temp_id= $this->session->get_temp_inter_id();
	    if($temp_id) $inter_id= $temp_id;
	    else $inter_id= $this->session->get_admin_inter_id();
		if( $inter_id==FULL_ACCESS || empty($post['inter_id']) ) 
		    $post['inter_id'] = $inter_id;

	    $disabled = FALSE;
		if( $inter_id == FULL_ACCESS ){
			$disabled = TRUE;
		}
     
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'name'=> array(
	            'field' => 'name',
	            'label' => $labels['name'],
	            'rules' => 'trim|required',
	        ),
	        'rule_type'=> array(
	            'field' => 'rule_type',
	            'label' => $labels['rule_type'],
	            'rules' => 'trim|required',
	        ),
	    );

	    /*****************储值规则，暂时是100%，以后可能会改变start**************/
		//限制方式为固定比例   el_limit_type
		//百分比为100%  el_limit_percent
		//优惠券不能用  el_can_use_coupon
	    $rule_type = isset( $post['rule_type'] ) && !empty( $post['rule_type'] ) ? $post['rule_type'] : '';

	    $limit_type = isset( $post['limit_type'] ) && !empty( $post['limit_type'] ) ? $post['limit_type'] : $model::LIMIT_TYPE_FIXED;
	    $limit_percent = isset( $post['limit_percent'] ) && !empty( $post['limit_percent'] ) ? $post['limit_percent'] : 0;
	    $can_use_coupon = isset( $post['can_use_coupon'] ) && !empty( $post['can_use_coupon'] ) ? $post['can_use_coupon'] : $model::STATUS_CAN_NO;
	    if( $rule_type == $model::RULE_TYPE_BALENCE ){
	    	$limit_type = $model::LIMIT_TYPE_FIXED;
	    	$limit_percent = 100;
	    	$can_use_coupon = $model::STATUS_CAN_NO;
	    }

	    if( $limit_percent < 0 && $limit_percent > 100 ){
	    	$this->session->put_notice_msg('比例值必须在0～100之间！');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit', array('ids'=> $this->input->post('rule_id') ) ));
	    }

	    /*****************积分规则，禁用优惠券，以后可能会改变start**************/
	    if( $rule_type == $model::RULE_TYPE_POINT ){
	    	$can_use_coupon = $model::STATUS_CAN_NO;
	    }
	    /*****************积分规则，禁用优惠券，以后可能会改变end**************/

	    $post['limit_type'] = $limit_type;
	    $post['limit_percent'] = $limit_percent;
	    $post['can_use_coupon'] = $can_use_coupon;

	    $lease_cost = isset( $post['lease_cost'] ) && !empty( $post['lease_cost'] ) ? $post['lease_cost'] : 0;
	    $reduce_cost = isset( $post['reduce_cost'] ) && !empty( $post['reduce_cost'] ) ? $post['reduce_cost'] : 0;
	    if( $rule_type == $model::RULE_TYPE_REDUCE && $lease_cost*0.9 < $reduce_cost ){
	    	$this->session->put_notice_msg('满减金额不能超过满减标准的90%');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit', array('ids'=> $this->input->post('rule_id') ) ));
	    }
	    if( $rule_type == $model::RULE_TYPE_REDUCE ){
	    	$post['reduce_cost'] = round( $post['reduce_cost'], 2 );
	    }
	    if( $rule_type == $model::RULE_TYPE_DISCOUNT && $reduce_cost > 10 ){
	        $this->session->put_notice_msg('满打折不能超过10(折)');
	        $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit', array('ids'=> $this->input->post('rule_id') ) ));
	    }
	     
	    if( !isset( $post['p_type'] ) || empty( $post['p_type'] ) ){
	        $this->session->put_notice_msg('请选择适用商品');
	        $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit', array('ids'=> $this->input->post('rule_id') ) ));
	    }

	    if( isset( $post['p_type'] ) && $post['p_type'] == 'ids' && empty( $post['product_ids'] ) ){
	        $this->session->put_notice_msg('您已经选择了部分商品，请选择商品');
	        $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit', array('ids'=> $this->input->post('rule_id') ) ));
	    }
        //处理积分选择的模块
	    if( isset($post['modules']) && is_array($post['modules']) ){
	        $post['modules']= implode(',', $post['modules']);
	    }
	    //
	    if( isset($post['bonus_size']) && is_array($post['bonus_size']) ){
	        $post['bonus_size']= json_encode( $post['bonus_size'] );
	    }
	    if( empty($post[$pk]) ){
	        //add data.
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            $post['create_time']= date('Y-m-d H:i:s');
	            $post['create_admin']= $this->session->get_admin_username();
	            // $result= $model->_m_save($post);
	            // $result= $model->m_sets( $post )->m_save($post);
	            $result= $model->product_save( $post, $inter_id );
	            $message= ($result)?
    	            $this->session->put_success_msg('已新增数据！'):
    	            $this->session->put_notice_msg('此次数据保存失败！');
    	        $this->_log($model);
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $this->_load_model();
	         
	    } else {
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            $post['update_time']= date('Y-m-d H:i:s');
	            $post['update_admin']= $this->session->get_admin_username();
	            // $result= $model->load($post[$pk])->m_sets($post)->m_save();
	            $result= $model->product_save( $post, $inter_id );
	            $message= ($result)?
    	            $this->session->put_success_msg('已保存数据！'):
    	            $this->session->put_notice_msg('此次编辑没有选择任何商品！');
	            $this->_log($model);
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit?ids='.$post[$pk]));
	
	        } else
	            $model= $model->load($post[$pk]);
	    }

	    $rule_id = $model->m_get($pk);
	    if( $rule_id ){

	    	$selectProducts = $model->get_rule_product_list( $rule_id, $inter_id );
	    }else{
	    	$selectProducts = array();
	    }
	// var_dump( $selectProducts );
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
	        'disabled'=> $disabled,
	        'model'=> $model,
	        'products'=> $products,
	        'selectProducts'=> $selectProducts,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}
	
	
}
