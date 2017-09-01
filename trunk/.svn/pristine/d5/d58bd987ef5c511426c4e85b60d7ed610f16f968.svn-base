<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends MY_Admin_Mall {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '订单明细';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'mall/shp_order_items';
	}
	
	public function delete()
	{
	    
	}
	

	public function grid()
	{
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
            $get_filter= $this->input->post();
        else 
		    $get_filter= $this->input->get('filter');
        
		if( !$get_filter) $get_filter= $this->input->get('filter');

		if(is_array($get_filter)) $filter= $get_filter+ $filter;

        $sts= $model->order_item_status();
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
			. '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-filter"></i> 状态筛选</button></div>'
			. '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
			. '<option value="-">全部</option>'. $ops
			. '</select>'
			. '</div>';
//      $jsfilter_btn.= '&nbsp;&nbsp;<div class="input-group">'
			// . '<div class="input-group-btn"><button type="button" class="btn btn-sm btn-success">支付状态</button></div>'
			// . '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
			// . '<option value="-">全部</option>'. $ops
			// . '</select>'
			// . '</div>';

        //echo $ops;die;
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
		$this->label_action= '订单详情';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids'));

		$this->load->model('mall/shp_address' );
		$this->load->model('mall/shp_order_items', 'items');
		$this->load->model('mall/shp_gift_log', 'gifts');
		$label_items= $this->items->attribute_labels();
		$label_gifts= $this->gifts->attribute_labels();

		if($id){
			//for edit page.
			$model= $model->load($id);
			$fields_config= $model->get_field_config('form');

			$items= $this->items->get_data_filter(array('order_id'=> $model->m_get('order_id')));
			$item_status= $this->items->order_item_status();

			$status= EA_base::inst()->get_status_options_();
			foreach($items as $k=> $v){
				//本订单单品信息格式转换
				$items[$k]['status']= (isset($item_status[$v['status']]))? $item_status[$v['status']]: $v['status'];
				$items[$k]['is_add_pack']= (isset($status[$v['is_add_pack']]))? $status[$v['is_add_pack']]: $v['is_add_pack'];
				$items[$k]['ex_order']= (isset($status[$v['ex_order']]))? $status[$v['ex_order']]: $v['ex_order'];
				$items[$k]['market_price']= show_price_prefix($items[$k]['market_price'],'￥');
				$items[$k]['promote_price']= show_price_prefix($items[$k]['promote_price'],'￥');
				$items[$k]['price']= show_price_prefix($items[$k]['price'],'￥');

				//本订单地址信息格式转换
				$address_id= $v['addr_id'];
				$address= $this->shp_address->load($address_id);
				if($address) 
					$items[$k]['address']= $address->m_get('country')
						. $address->m_get('province'). ' '
						. $address->m_get('city'). ' '
						. $address->m_get('region'). ' '
						. $address->m_get('address'). ' '
						. $address->m_get('zip_code'). ','
						. $address->m_get('contact'). ','
						. $address->m_get('phone')
					;
			}

			//本订单单品信息格式转换
			$gifts= $this->gifts->get_data_filter(array('order_id'=> $model->m_get('order_id')));
			$gift_status= $this->gifts->order_gift_status();
			foreach($gifts as $k=> $v){
				$gifts[$k]['status']= (isset($gift_status[$v['status']]))? $gift_status[$v['status']]: $v['status'];
				$gifts[$k]['ge_openid']= hide_string_prefix($gifts[$k]['ge_openid'],'6');
				$gifts[$k]['gt_openid']= hide_string_prefix($gifts[$k]['gt_openid'],'6');

			}


		} else {
			//for add page.
	        $model= $model->load($id);
	        if(!$model) $model= $this->_load_model();
			$fields_config= $model->get_field_config('form');

			$items= array();
		}

		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'items'=> $items,
		    'label_items'=> $label_items,
		    'gifts'=> $gifts,
		    'label_gifts'=> $label_gifts,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}
	
	public function edit_post()
	{
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
		$post= $this->input->post();
		//print_r($post);

		$this->session->put_notice_msg('功能正在完善中！');
		$this->_redirect(EA_const_url::inst()->get_url('*/*/edit', array('ids'=> $post[$pk] ) ));

	}




	
	
}
