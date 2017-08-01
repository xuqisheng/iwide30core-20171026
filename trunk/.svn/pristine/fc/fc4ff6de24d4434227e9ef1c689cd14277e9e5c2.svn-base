<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//推荐位管理
class Cms_block extends MY_Admin_Soma{
	protected $label_module= NAV_PACKAGE_GROUPON;		//统一在 constants.php 定义
	protected $label_controller= '推荐位管理';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'soma/Cms_block_model';
	}

	public function grid()
	{
		$this->label_action= '推荐位管理';
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;

	    $ent_ids= $this->session->get_admin_hotels();
	    $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );
	     
/* 兼容grid变为ajax加载加这一段 */
	    if(is_ajax_request())
	        //处理ajax请求，参数规格不一样
	        $get_filter= $this->input->post();
	    else
	        $get_filter= $this->input->get('filter');
	    
	    if( !$get_filter) $get_filter= $this->input->get('filter');
	    
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;
/* 兼容grid变为ajax加载加这一段 */
	    // echo 'ad';exit;
	    $this->_grid($filter);
	}

	/**
     * 处理新增和编辑方法
     */
	public function edit()
	{
		$this->label_action= '推荐位修改';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids'));

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
		// $inter_id= $this->session->get_admin_inter_id();
// //测试使用
$cat_id = '';
// $inter_id = 'a429262687';//'';//
$limit = 0;

        
		//显示已经添加的商品
        $products_arr = $model->get_cms_block_product( $id, $limit, $inter_id );
        $products_ids = array();
        $products_select_list = array();//已添加的商品
        if( $products_arr ){

	        foreach( $products_arr as $k => $v ){
	            $products_ids[] = $v['product_id'];
	            $products_select_list[$v['product_id']] = $v; 
	        }
        }
	// var_dump( $products_select_list );exit;
		//添加商品
        $this->load->model( 'soma/Product_package_model' );
        $products = $this->Product_package_model->get_product_package_list( $cat_id, $inter_id );
        $product_options = '';
        foreach( $products as $sk => $sv ){
        	if( !in_array( $sv['product_id'], $products_ids ) ){

            	$product_options .= '<option value="'.$sv['product_id'].'" >'.$sv['name'].'</option>';
        	}else{
        		// $sv['sort'] = $products_select_list[$v['product_id']]['sort'];
        		$products_select_list[$sv['product_id']]['name'] = $sv['name'];
        		$products_select_list[$sv['product_id']]['face_img'] = $sv['face_img'];
        		$products_select_list[$sv['product_id']]['price_package'] = $sv['price_package'];
        		$products_select_list[$sv['product_id']]['hotel_name'] = $sv['hotel_name'];
        		$products_select_list[$sv['product_id']]['expiration_date'] = $sv['expiration_date'];
        	}
        }
        // var_dump( $products_select_list );exit;
		$product_select = '';
		$product_select = '<div class="form-group">
							<label for="el_product_id" class="col-sm-2 control-label">选择添加商品</label>
							<div class="col-sm-8">
								<select class="form-control selectpicker show-tick" data-live-search="true" name="product_id" id="el_product_id">
									'.$product_options.'
								</select>
							</div>
						</div>';

		$product_sort = '';
		$product_sort = '<div class="form-group">
							<label for="el_product_sort" class="col-sm-2 control-label">商品排序</label>
							<div class="col-sm-8">
								<input type="number" class="form-control " name="product_sort" id="el_product_sort" placeholder="排序" value="">
							</div>
						</div>';

		$grid_field= array(
			'face_img'=>'图片',
			// 'product_id'=>'商品ID',
			// 'product_id'=>'商品ID',
// 				'sku'=>'sku',
			'name'=>'商品名称',
			'price_package'=>'价格',
			'hotel_name'=>'酒店',
			'expiration_date'=>'有效期',
			'sort'=>'排序',
			// 'refund_total'=>'退款金额',
		);
// var_dump( $products_select_list );exit;
		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'product_select'=> $product_select,
		    'product_sort'=> $product_sort,
		    'products_arr'=> $products_arr,
		    'products'=> $products,
		    'grid_field'=> $grid_field,
		    'products_select_list'=> $products_select_list,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	public function edit_post()
	{
	    $this->label_action= '信息维护';
	    $this->_init_breadcrumb($this->label_action);
    	
    	//cat_id从ticket_center->get_id_category('package')获取
	    // $this->load->model('soma/ticket_center_model','ticket_center');
	    // $catId = $this->ticket_center->get_id_category('package');
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();
	    $inter_id= $this->session->get_admin_inter_id();

// //测试使用
// $cat_id = '';
// $inter_id = 'a429262687';//'';//

		$post['inter_id'] = $inter_id;
// var_dump( $post );exit;
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'title'=> array(
	            'field' => 'title',
	            'label' => $labels['title'],
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
	    );

	    if( empty($post[$pk]) ){
	        //add data.
	        
	        $this->form_validation->set_rules($base_rules);
	         
	        if ($this->form_validation->run() != FALSE) {
	            $result= $model->block_save($post,$inter_id);
	            $message= ($result)?
    	            $this->session->put_success_msg('已新增数据！'):
    	            $this->session->put_notice_msg('此次数据保存失败！');
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $this->_load_model();
	         
	    } else {
	    	//edit
	        $this->form_validation->set_rules($base_rules);
	         
	        if ($this->form_validation->run() != FALSE) {

	            $result= $model->block_save( $post, $inter_id );
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
		    'imgs'=> $model->get_cat_img(),
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}

	public function delete()
	{
		$url= Soma_const_url::inst()->get_url('*/*/index');
	    redirect($url);
	}

	public function get_product_list()
	{
		$model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $uri = 'soma_package_package_detail';
	    $filter['inter_id'] = 'a429262687';
	    $limit = 1;
	    $rs = $model->show_in_page( $uri, $filter, $limit );
	    var_dump( $rs );
	}

	public function edit_product()
	{
		// var_dump( $this->input->post() );exit;
		$model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();
	    $inter_id= $this->session->get_admin_inter_id();
// //测试使用
// $cat_id = '';
// $inter_id = 'a429262687';//'';//
		$post = $this->input->post();
		$result= $model->product_edit( $post, $inter_id );
	            $message= ($result)?
    	            $this->session->put_success_msg('已保存数据！'):
    	            $this->session->put_notice_msg('此次数据修改失败！');
	    $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	}

	public function add_product()
	{
		$model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();
	    $inter_id= $this->session->get_admin_inter_id();
// //测试使用
// $cat_id = '';
// $inter_id = 'a429262687';//'';//
		$post = $this->input->post();
		$result= $model->product_save( $post, $inter_id );
	            $message= ($result)?
    	            $this->session->put_success_msg('已保存数据！'):
    	            $this->session->put_notice_msg('此次数据修改失败！');
	    $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	}

}
