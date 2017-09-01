<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gift_order extends MY_Admin_Soma {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '赠送订单';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'soma/gift_order_model';
	}

	public function grid()
	{
		$this->label_action= '赠送列表';
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
        $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
			. '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-filter"></i> 赠送状态</button></div>'
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
		$this->label_action= '赠送详情';
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

		//查看赠送订单明细 
		$asset_items = array();
		$item = array('gift_id'=>$model->m_get('gift_id'),'qty'=>$model->m_get('total_qty').'份');
		$receive_list = array();

		$openids = array();
		$code_list = array();
		$code_item_ids = array();
		if( $giftId = $model->m_get('gift_id') ){

			$this->load->model('wx/publics_model','soma_publics_model');
			$send_openid_info = $this->soma_publics_model->get_fans_info( $model->m_get('openid_give') );
			$item['nickname_send'] = $send_openid_info['nickname'];

			$business = 'package';
			$inter_id = $this->session->get_admin_inter_id();
			// $items = $model->get_gift_order_item( $business, $inter_id );
			// if( $model->m_get('is_p2p') == Soma_base::STATUS_FALSE ){
			// 	$receive_list = $model->get_receiver_list($inter_id, $model->m_get('gift_id') );
			// }
			$this->load->model('soma/Asset_item_package_model','AssetItemModel');
			$AssetItemModel = $this->AssetItemModel;
			$asset_items = $AssetItemModel->get_order_items_byGiftids( array($giftId), $business, $inter_id );

			if( $model->m_get('openid_receive') ){
				$openids = array( $model->m_get('openid_receive')=>$model->m_get('openid_receive'));
			}

			if( count( $asset_items ) > 0 ){
				foreach ($asset_items as $k => $v) {
					if( $inter_id == $v['inter_id'] ){
						$openids[$v['openid']] = $v['openid'];
						$code_item_ids[$v['item_id']] = $v['openid'];
					}
				}
			}
// var_dump( $openids );
			$nickname_receive = '';
			if( count( $openids ) > 0 ){
	            $openids_info = $this->soma_publics_model->get_fans_info_byIds( array_keys( $openids ) );
	            // var_dump( $openids_info );
	            if( $openids_info ){
		            foreach( $openids_info as $kk=>$vv ){
		            	$openids[$vv['openid']] = $vv['nickname'];
		            	$nickname_receive .= $vv['nickname'] . '; ';
		            }
				}
			}
// var_dump( $openids );die;
			if( count( $code_item_ids ) > 0 ){
				$this->load->model('soma/Consumer_code_model','CodeModel');
				$CodeModel = $this->CodeModel;
				$code_list = $CodeModel->get_code_by_assetItemIds( array_keys( $code_item_ids ), $inter_id );
				// if( $code_list ){
				// 	foreach( $code_list as $kkk=>$vvv ){
				// 		if( isset( $code_item_ids[$v['item_id']] ) ){

				// 		}
		  //           }
				// }
				// var_dump( $code_list );
			}
// var_dump( $code_item_ids);die;
			$item['order_id'] = isset( $asset_items[0]['order_id'] ) ? $asset_items[0]['order_id'] : '';
			$item['name'] = isset( $asset_items[0]['name'] ) ? $asset_items[0]['name'] : '';
			$item['nickname_receive'] = $nickname_receive;
			$item['openids'] = $openids;
			$item['code_list'] = $code_list;
			$item['code_item_ids'] = $code_item_ids;
		}

		//赠送订单明细要显示字段
		$items_grid_field= array(
			// 'item_id'=>'ID',
			'gift_id'=>'赠送编号',
			'order_id'=>'订单编号',
			'name'=>'商品名称',
			'qty'=>'送出数量',
			'nickname_send'=>'送礼人昵称',
			'nickname_receive'=>'收礼人昵称',
		);

		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'asset_items'=>$asset_items,
		    'item'=>$item,
		    'openids'=>$openids,
		    'code_list'=>$code_list,
		    'code_item_ids'=>$code_item_ids,
		    'items_grid_field'=>$items_grid_field,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	/**
	 * 禁止进行删除操作
	 */
	public function delete()
	{
	    $url= Soma_const_url::inst()->get_url('*/*/index');
	    redirect($url);
	}
	
}
