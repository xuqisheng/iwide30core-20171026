<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 产品管理页面
class Sales_reserve_product extends MY_Admin_Soma {

	const DEV = FALSE;

	protected function main_model_name() {
		return 'soma/Sales_reserve_product_model';
	}

	// 显示预订单列表
	public function grid() {
		$this->label_action = "预订商品管理";
	    $filter = $this->_grid_filter();
		$this->_grid($filter);
	}

	private function _grid_filter() {
		$filter = array();
		
		$inter_id = $this->session->get_admin_inter_id();
		// 获取测试的$inter_id
		// $inter_id = $this->session->get_temp_inter_id();
		if( self::DEV ) { $inter_id = 'a450089706'; }

		if(!$inter_id) {
			$inter_id = 'deny';
		}
		$filter['inter_id'] = $inter_id;

		$hotel_ids = $this->session->get_admin_hotels();
		if(!$hotel_ids) {
			$filter['hotel_id'] = array();
		} else {
			$filter['hotel_id'] = explode(',', $hotel_ids );
		}

		if(is_ajax_request()){
			$get_filter= $this->_ajax_params_parse( $this->input->post(), $model );
		} else {
			$get_filter= $this->input->get('filter');
		}
		if(is_array($get_filter)) {
			if(isset($get_filter['inter_id'])) {
				unset($get_filter['inter_id']);
			}
			if(isset($get_filter['hotel_id'])) {
				unset($get_filter['hotel_id']);
			}
			$filter += $get_filter;
		}

		return $filter;
	}

	// 从套票模块获取商品列表，只考虑公众号，不考虑hotel_id
	public function edit() {
		$inter_id= $this->session->get_admin_inter_id();
		if( self::DEV ) { $inter_id = 'a450089706'; }

	    $this->load->model('soma/Product_package_model');
	    $product_model= $this->Product_package_model;
	    $products= $product_model->get_package_list($inter_id, array('inter_id'=>$inter_id));

	    // 已选择
	    $this->load->model('soma/Sales_reserve_product_model');
	    $reserve_pm = $this->Sales_reserve_product_model;
	    $on_sales_ids = $reserve_pm->get_product_ids($inter_id);

	    $grid_data = array();
	    $this->load->helper('soma/package');
	    foreach ($products as $p) {
	    	$row = array();
	    	$row[] = $p['product_id'];
	    	$row[] = $p['name'];
	    	$row[] = show_face_img($p['face_img'],100);
	    	$row[] = $p['price_market'];
	    	$row[] = $p['price_package'];
	    	$row['DT_RowId'] = $p['product_id'];
	    	$row[] = in_array($p['product_id'], $on_sales_ids)?1:2;
	    	$grid_data[] = $row;
	    }

	    $view_params = array(
	    	'model' => $product_model,
	    	'products' => $products,
	    	'grid_data' => $grid_data,
	    	'check_data' => FALSE,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    //echo $html;die;
	    echo $html;
	}

	// 会post产品ids过来
	public function edit_post() {
		$inter_id= $this->session->get_admin_inter_id();
		if( self::DEV ) { $inter_id = 'a450089706'; }

		$post = $this->input->post();
		$product_ids = isset( $post['product_ids'] ) && !empty( $post['product_ids'] ) ? explode( ',', $post['product_ids'] ) : NULL;
		if(!$product_ids) {
			$this->session->put_error_msg('请选择商品后再提交');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
		}

		// 剔除已选择的商品
		$this->load->model('soma/Sales_reserve_product_model');
	    $reserve_pm = $this->Sales_reserve_product_model;
	    $on_sales_ids = $reserve_pm->get_product_ids($inter_id);

	    $ids_insert = array();
	    $ids_update = array();

	    foreach ($product_ids as $k => $id) {
	    	if(in_array($id, $on_sales_ids)){
	    		// unset($product_ids[$k]);
	    		$ids_update[] = $id;
	    	} else {
	    		$ids_insert[] = $id;
	    	}
	    }

		$this->load->model('soma/Product_package_model');
	    $product_model= $this->Product_package_model;
		// $products= $product_model->get_product_package_by_ids($product_ids, $inter_id);
		$products['insert'] = $product_model->get_product_package_by_ids($ids_insert, $inter_id);
		if(!$products['insert']) { $products['insert'] = array(); }
		$products['update'] = $product_model->get_product_package_by_ids($ids_update, $inter_id);
		if(!$products['update']) { $products['update'] = array(); }

		$this->load->model('hotel/Hotel_model');
		$hotel_model = $this->Hotel_model;
		$hotels = $hotel_model->get_all_hotels($inter_id);

		$this->load->model($this->main_model_name(), 'model');
		$this->model->save_batch($products, $hotels);

		$this->session->put_success_msg('添加成功');
        $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	}
	
	/**
	 * 展示前端二维码入口
	 */
	public function qrcode_front()
	{
	    $inter_id= $this->session->get_temp_inter_id();
        if( !$inter_id ) $inter_id= $this->session->get_admin_inter_id();
        $id= $this->input->get('ids');
        
	    if( $inter_id==FULL_ACCESS && empty($id) ){
	        die('参数错误');
	    }
	    if( $inter_id==FULL_ACCESS ) {
	        $model_name= $this->main_model_name();
	        $model= $this->_load_model($model_name);
	        $model= $model->load($id);
	        
	        $inter_id= $model->m_get('inter_id');
	    }
	    $url= EA_const_url::inst()->get_front_url($inter_id, 'soma/reserve/index', array('id'=> $inter_id, ));
        $this->_get_qrcode_png($url);
	}
	
}