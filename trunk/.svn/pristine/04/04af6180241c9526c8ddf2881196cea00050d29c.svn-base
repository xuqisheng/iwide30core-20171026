<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_coupon extends MY_Admin_Soma {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '优惠券管理';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'soma/Sales_coupon_model';
	}

	public function grid()
	{
	    $this->label_action= '优惠券管理';
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;

	    // $ent_ids= $this->session->get_admin_hotels();
	    // $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    // if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );

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
	    $this->label_action= '优惠券管理';
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

	    //TRUE为标记拦截管理员权限用户操作
	    $inter_id= $this->_get_real_inter_id(TRUE);
	     
	    $this->load->model('soma/Product_package_model');
	    $product_model= $this->Product_package_model;
	    $products= $product_model->get_package_list($inter_id, array('inter_id'=>$inter_id));
	    
	    $card_id = $model->m_get('card_id');
	    //取出已选到商品
	    $selectProducts = $model->get_coupon_product_list( $card_id, $inter_id );
	    $view_params= array(
	        'model'=> $model,
	        'products'=> $products,
	        'fields_config'=> $fields_config,
	        'check_data'=> FALSE,
	        'selectProducts'=> $selectProducts,
	    );
	
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    //echo $html;die;
	    echo $html;
	}

	public function edit_post()
	{
	    $this->label_action= '优惠券管理';
	    $this->_init_breadcrumb($this->label_action);
    	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();
// var_dump( $post );exit;

	    //TRUE为标记拦截管理员权限用户操作
		$inter_id= $this->_get_real_inter_id(TRUE);
		
		if( $inter_id==FULL_ACCESS || empty($post['inter_id']) ) 
		    $post['inter_id'] = $inter_id;
     
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'card_id'=> array(
	            'field' => 'card_id',
	            'label' => $labels['card_id'],
	            'rules' => 'trim|required',
	        ),
	    );

	    if( empty($post[$pk]) ){
	        //add data.
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
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
	            $result= $model->product_save( $post, $inter_id );
	            $message= ($result)?
    	            $this->session->put_success_msg('已保存数据！'):
    	            $this->session->put_notice_msg('此次数据修改失败，没有选择任何商品！');
	            $this->_log($model);
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit?ids='.$post[$pk]));
	
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

	//和会员同步优惠券
	public function refresh()
	{
	    //TRUE为标记拦截管理员权限用户操作
		$inter_id= $this->_get_real_inter_id(TRUE);
		
		$this->load->library('Soma/Api_member');
		$ApiMember =  new Api_member( $inter_id );
		$result= $ApiMember->get_token();
        $ApiMember->set_token($result['data']);
		$coupon_list = $ApiMember->conpon_all();
		
		if( isset( $coupon_list['data'] ) && !empty( $coupon_list['data'] ) ){
			//返回卡券内容
			$model_name= $this->main_model_name();
	    	$model= $this->_load_model($model_name);

	    	//酒店id
	    	$ent_ids= $this->session->get_admin_hotels();
	    	$hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    	$hotel_id = isset( $hotel_ids[0] ) ? $hotel_ids[0] : NULL;

	    	$return = $model->refresh_coupon( $coupon_list, $inter_id, $hotel_id );
	    	if( isset( $return['status'] ) && $return['status'] == 1 ){
	    		$this->session->put_success_msg($return['message']);
	    	}else{
	    		$this->session->put_error_msg($return['message']);
	    	}

		} else {
			$this->session->put_error_msg('更新数据失败，没有找到卡券数据');
		}
		
	    $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	}
	
	
}
