<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_voucher_exchange extends MY_Admin_Soma {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '兑换管理';		//在文件定义
	protected $label_action= '';				//在方法中定义

	protected function main_model_name() {
		return 'soma/Sales_voucher_exchange_model';
	}

	public function grid() 
	{
		$this->label_action= '兑换列表';
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
	    
	    $sts= $model->get_exchange_type_status_label();
	    $ops= '';
	    foreach( $sts as $k=> $v){
	        if( isset($filter['exchange_type']) && $filter['exchange_type']==$k ) $ops.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
	        else $ops.= '<option value="'. $k. '">'. $v. '</option>';
	    }
	    
	    if( !isset($filter['exchange_type']) || $filter['exchange_type']===NULL )
	        $active= 'disabled';
	    else
	        $active= 'btn-success';

	    if($inter_id == FULL_ACCESS && !$this->current_inter_id ){
	        $export_btn= '';
	        $exchange_btn= '';
	    } else {
	        $export_btn= '<span class="input-group-btn"><button id="export_order_btn" type="button" class="btn btn-sm btn-success"><i class="fa fa-download"></i> 导出</button></span>';

	        $exchange_url = Soma_const_url::inst()->get_url('*/*/exchange' );
	        $exchange_btn= '<span class="input-group-btn"><button id="exchange_btn" type="button" class="btn btn-sm btn-success"><a href="'
	            .$exchange_url.'" style="color:#fff;" ><i class="fa "></i> 券码兑换</a></button></span>';
	    }
	    
	    $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
	        . '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-filter"></i> 兑换类型</button></div>'
            . '<select class="form-control input-sm" name="filter[exchange_type]" id="filter_status" >'
            . '<option value="-">全部</option>'. $ops
			. '</select>' 
			. $export_btn
			. $exchange_btn
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
    var exchange_type= $('#filter_status').val();
    var url= '{$export_url}';
    if( !isNaN(exchange_type) ){
	    var p= '?exchange_type='+ exchange_type;
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
        $this->_grid($filter, $viewdata);
		
	}

	public function edit()
	{
		die('暂时不开放编辑功能');
	    $this->label_action= '兑换信息处理';
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
	    );
	    
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    //echo $html;die;
	    echo $html;
	}

	//券码兑换
	public function exchange()
	{
		$this->label_action= '兑换码处理';
		$this->_init_breadcrumb($this->label_action);
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    
	    if(is_array($filter)){
	        $this->load->model('wx/publics_model');
	        $publics= $this->publics_model->get_public_hash($filter);
	        $publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
	        //$publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
	    }

		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
        if(!$model) $model= $this->_load_model();
		$fields_config= $model->get_field_config('form');
		$code= $this->input->get('ids');

		$view_params= array(
		    'model'=> $model,
		    'publics'=> $publics,
		    'code'=> $code,
		    'fields_config'=> $fields_config,
		);
		
		$html= $this->_render_content($this->_load_view_file('exchange'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	//根据兑换码获取到信息
	public function exchange_info(){
		$this->label_action= '使用兑换码兑换';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);

		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		}
		
		$code = $this->input->post('exchange_code');
		if( !$code ){
			$this->session->put_error_msg('没有输入兑换码');
			$this->_redirect( Soma_const_url::inst()->get_url( '*/*/exchange?ids='.$code ) );
		}

		$business = 'package';
		$inter_id = $this->_get_real_inter_id( TRUE );

		//获取兑换码信息
		$exchangeInfo = $model->get_exchange_info_byCode( $code, $inter_id );
		if( count( $exchangeInfo ) == 0 ){
			$this->session->put_error_msg('找不到该兑换码的相关信息');
			$this->_redirect( Soma_const_url::inst()->get_url( '*/*/exchange?ids='.$code ) );
		}

		$this->load->model( 'hotel/hotel_model' );
        $hotel_info = $this->hotel_model->get_hotel_detail( $exchangeInfo['inter_id'], $exchangeInfo['hotel_id'] );
        if( $hotel_info ){
        	$exchangeInfo['hotel_name'] = $hotel_info['name'];
        }else{
        	$exchangeInfo['hotel_name'] = '';
        }

		$this->load->model( 'wx/Publics_model' );
		$publics = $this->Publics_model->get_public_by_id($inter_id);
		if( $publics ){
        	$exchangeInfo['inter_id_name'] = $publics['name'];
        }else{
        	$exchangeInfo['inter_id_name'] = '';
        }
// var_dump( $exchangeInfo );die;

        $button_str = '';
        $exchange_use_url = Soma_const_url::inst()->get_url( '*/*/exchange_post' );
        //1已经使用，2未使用
        if( $exchangeInfo['status'] == Soma_base::STATUS_TRUE )
			$button_str = '<input type="submit" id="button" class="btn btn-info" value="兑换">';
		elseif( $exchangeInfo['status'] == Soma_base::STATUS_FALSE )
			$button_str = '<button type="button" class="btn btn-info">已经兑换</button>';
		
		$fields_config= $model->get_field_config('form');

		//输出字段
		$grid_field= array(
						'inter_id_name' => '公众号',
						'hotel_name' => '酒店',
				        'template_id'=> '模板id',
				        'code'=> '兑换码',
				        'password'=> '密码',
				        'create_time'=> '创建时间',
						'status' => '状态',
					);

		$view_params= array(
		    'model'=> $model,
		    'grid_field'=> $grid_field,
		    'items'=> $exchangeInfo,
		    'fields_config'=> $fields_config,
		    'code'=> $code,
		    'code_id'=> $exchangeInfo['code_id'],
		    'template_id'=> $exchangeInfo['template_id'],
		    'button_str'=> $button_str,
		);

		$html= $this->_render_content($this->_load_view_file('exchange_info'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	//进行兑换  tid=>template_id , cid=>code_id
	public function exchange_post()
	{
		$post = $this->input->post();

		$inter_id = $this->_get_real_inter_id( TRUE );
		
		$this->load->helper('soma/package');
		$log_path = APPPATH. 'logs'. DS. 'soma'. DS . 'consumer' . DS;
		$file = date('Y-m-d_H'). '.txt';
		write_log('公众号：'.$inter_id.', 后台兑换码兑换开始', $file, $log_path);
		write_log('兑换信息：'.json_encode($post), $file, $log_path);

		$admin = $this->session->admin_profile;//后台兑换人员信息，生成兑换记录需要
        write_log('兑换操作人信息：'.json_encode($admin), $file, $log_path);

		// die('进行兑换');
		$business = 'package';
		$this->load->model('soma/Sales_voucher_model','SalesVoucherModel');
		$SalesVoucherModel = $this->SalesVoucherModel;
		$return = $SalesVoucherModel->goto_exchange( $post, $inter_id, $business );
		write_log('兑换返回信息：'.json_encode($return), $file, $log_path);
		
		if( isset( $return['status'] ) && $return['status'] == Soma_base::STATUS_TRUE ){
			$this->session->put_success_msg('兑换成功');
		}else{
			$this->session->put_notice_msg( isset( $return['message'] ) ? $return['message'] : '兑换失败' );
		}

		write_log('公众号：'.$inter_id.', 后台兑换码兑换结束', $file, $log_path);

        $this->_redirect(Soma_const_url::inst()->get_url('*/*/exchange'));
	}

	//兑换纪录导出
	public function export_list()
	{
		$exchange_type = $this->input->get('exchange_type');
		$start= $this->input->get('start');
        $end= $this->input->get('end'); 
        $business= 'package'; //$this->input->get('bsn');
        $inter_id= $this->session->get_admin_inter_id();
        if($inter_id == FULL_ACCESS ){
            $inter_id= $this->current_inter_id;
        }

        $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);

        //如果hotel_id不为空，添加hotel_id条件
        // echo $exchange_type;die;
        $filter = array();
        if( $exchange_type ) $filter['exchange_type'] = $exchange_type + 0;
        $ent_ids= $this->session->get_admin_hotels();
	    $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );

	    $select = array('record_id','exchange_type','product_name','product_price','exchange_qty','code','order_id','op_user','create_time');
	    $data = $model->export_item( $business, $inter_id, $filter, $select, $start, $end );
	    // var_dump( $data );

	    $header= array(
	        		'兑换ID',
	        		'兑换类型',
	        		'产品名称',
	        		'产品价格',
	        		'兑换数量',
	        		'券码', 
	        		'订单编号',
	        		'操作人', 
	        		'兑换时间', 
        		);
        $url= $this->_do_export($data, $header, 'csv', TRUE );
        //$url= $this->_do_export($data, $header, 'csv', FALSE ); //FALSE 直接echo内容
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