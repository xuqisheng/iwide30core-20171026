<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Goods extends MY_Admin_Iapi {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '商品管理';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'hotel';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'hotel/goods/Goods_info_model';
	}
	public function index() {
		$data = $this->common_data;
		$this->label_action = '商品列表';
		$model = $this->_load_model ( $this->main_model_name () );
		$this->_init_breadcrumb ( $this->label_action );
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}

	public function get_list(){
		$data = $this->common_data;
		$condit ['inter_id'] = $this->inter_id;
		$model = $this->_load_model ( $this->main_model_name () );
		$condit ['page'] = $this->input->get ( 'page' )>0 ? intval($this->input->get ( 'page' )) : 1;
		$condit ['size'] = $this->input->get ( 'size' )>0 ? intval($this->input->get ( 'size' )) : 20;
		$condit ['status'] = $this->input->get ( 'status' ) ? $this->input->get ( 'status' ) : 'normal';
		$list = $model->get_list ( $condit );
		$data['items'] = $list['items'];
		$ext['page'] = $condit ['page'];
		$ext['size'] = $condit ['size'];
		$ext['count'] = $list ['count'];
		$add = site_url('soma/product_package/add').'?from=hotel&succ_url='.urlencode(site_url('hotel/hotel_goods/index'));
		$ext['links'] = array('add'=>$add);
		$this->out_put_msg(1,'',$data,'hotel/goods/get_list',200,$ext);
	}

	public function edit_post() {
		$post = json_decode($this->input->raw_input_stream,true);
		$gid = $post['goods_id'];
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$data ['price'] = floatval($post['price']);
		$data ['unit'] = $post['unit'];
		$data ['sort'] = intval($post['sort']);
		$data ['short_intro'] = $post['short_intro'];
		$data ['status'] = intval($post['status']);
		if(empty($gid)){
			$this->out_put_msg(2,'缺少参数','','hotel/goods/edit_post');
		}
		if(empty($data ['price'])){
			$this->out_put_msg(2,'价格错误','','hotel/goods/edit_post');
		}
		if(empty($data ['unit'])){
			$this->out_put_msg(2,'单位不能为空','','hotel/goods/edit_post');
		}
		if(empty($data ['status'])){
			$this->out_put_msg(2,'状态异常','','hotel/goods/edit_post');
		}
	
		if ($gid) {
			$re = $model->update_data ( $this->inter_id,$gid, $data );
		}
		if(empty($re)){
			$this->out_put_msg(2,'更新失败',$this->common_data,'hotel/goods/edit_post');
		} else {
			$this->out_put_msg(1,'更新成功',$this->common_data,'hotel/goods/edit_post');
		}
	}
}
