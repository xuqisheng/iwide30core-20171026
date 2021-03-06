<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// error_reporting( 0 );
class Refund_order extends MY_Admin_Soma {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '退款管理';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	const PENDING = 'pending';//审核
	const REFUND = 'refund';//退款
	const CANCEL = 'cancel';//取消
	const CHECK = 'check';
	
	public function __construct()
	{
	    parent::__construct();
	}
	
	public function get_pending_label(){
	    return self::PENDING;
	}
	public function get_refund_label(){
	    return self::REFUND;
	}
	
	public function get_cancel_label(){
	    return self::CANCEL;
	}

	public function get_check_label(){
	    return self::CHECK;
	}
	
	protected function main_model_name()
	{
		return 'soma/sales_refund_model';
	}

	public function grid()
	{
		$this->label_action= '退款列表';
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
		$this->label_action= '退款详情';
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

		//查看退款订单明细 
		$business = 'package';
		$inter_id = $this->session->get_admin_inter_id();
		$model->business = $business;
		$order_detail = $model->get_order_detail( $business, $inter_id );
		if( $order_detail ){
		    $items = $order_detail['items'];
		}else{
		    $items = array();
		}
// var_dump( $items, $order_detail );exit;
		//订单明细要显示字段
		$grid_field= array(
				'order_item_id'=>'订单ID',
				// 'product_id'=>'商品ID',
// 				'sku'=>'sku',
				'name'=>'商品名称',
				'qty'=>'数量',
				'price'=>'价格',
				// 'refund_total'=>'退款金额',
			);


		//按钮操作
		$pk = $model->table_primary_key();
		$param = array( 'ids'=>$model->m_get($pk) );
		
		//通过审核
		$pending_url = Soma_const_url::inst()->get_url('*/*/pending',$param);
		
		//通过审核并退款
		$refund_url = Soma_const_url::inst()->get_url('*/*/refund',$param);
		
		//取消退款申请
		$cancel_url = Soma_const_url::inst()->get_url('*/*/cancel',$param);
		
		$button_str = '';
		$status = $order_detail['status'];//当前退款状态

        $this->load->model('soma/Sales_order_model','somaSalesOrderModel');
        $somaSalesOrderModel = $this->somaSalesOrderModel->load( $order_detail['order_id'] );
        $connDevicesStatus = $somaSalesOrderModel->m_get('conn_devices_status');
        if( !empty( $connDevicesStatus ) )
        {
            //这个字段不等于0，说明是对接设置，对接设置，不显示退款操作按钮
            $button_str = '请至第三方平台进行退款审核';
        } else
        {
            if ($status == $model::STATUS_WAITING)
            {
                //提交按钮
                $button_str .= '<a type="button" href="' . $pending_url . '" class="btn btn-info">通过审核</a>&nbsp;&nbsp;&nbsp;';
                $button_str .= '<a type="button" href="' . $refund_url . '" class="btn btn-info">通过审核并退款</a>&nbsp;&nbsp;&nbsp;';
                $button_str .= '<a type="button" href="' . $cancel_url . '" class="btn btn-info">拒绝退款</a>&nbsp;&nbsp;&nbsp;';
            } elseif ($status == $model::STATUS_PENDING)
            {
                // $button_str .= '<a type="button" href="'.$cancel_url.'" class="btn btn-info">拒绝退款</a>&nbsp;&nbsp;&nbsp;';
                $button_str .= '<a type="button" href="' . $refund_url . '" class="btn btn-info">退款</a>&nbsp;&nbsp;&nbsp;';
            }
        }

		//申请人
        // $openid = isset( $order_detail['openid'] ) ? $order_detail['openid'] : '';
        // $user_info = $this->db->where_in( 'openid', $openid )->select('id,nickname,inter_id')->get('fans' )->result_array();
        // // $user_info = $publics_model->get_fans_info( $openid );
        // if( $user_info ){
        // 	$nickname = $user_info[0]['nickname'];
        // }else{
        // 	$nickname = '';
        // }

// 		var_dump( $status,$button_str,$order_detail );exit;
		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'items'=>$items,
		    'button_str'=>$button_str,
		    'grid_field'=>$grid_field,
		    // 'nickname'=>$nickname,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}
	
	//取消退款申请
	public function cancel()
	{
	    $id= intval($this->input->get('ids'));
	    $param = array();
	    $param['act'] = $this->get_cancel_label();
	    $param['ids'] = $id;
	    $this->_redirect( Soma_const_url::inst()->get_url('*/*/edit_get',$param) );
	}
	
	//通过审核
	public function pending()
	{
	    $id= intval($this->input->get('ids'));
	    $param = array();
	    $param['act'] = $this->get_pending_label();
	    $param['ids'] = $id;
	    $this->_redirect( Soma_const_url::inst()->get_url('*/*/edit_get',$param) );
	
	}
	
	//退款
	public function refund()
	{
	    $id= intval($this->input->get('ids'));
	    $param = array();
	    $param['act'] = $this->get_refund_label();
	    $param['ids'] = $id;
	    $this->_redirect( Soma_const_url::inst()->get_url('*/*/edit_get',$param) );
	}

	//查询
	public function check()
	{
	    $id= intval($this->input->get('ids'));
	    $param = array();
	    $param['act'] = $this->get_check_label();
	    $param['ids'] = $id;
	    $this->_redirect( Soma_const_url::inst()->get_url('*/*/edit_get',$param) );
	}
	
	public function edit_get()
	{
	    $this->label_action= '信息维护';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $get = $this->input->get();
	    $id = $get['ids'];
	    $act = $get['act'];
        
        $business = 'package';
        $inter_id = $this->_get_real_inter_id(TRUE);

        //改变状态之前要检查清楚单是否在这个公众号下单退款单号
        $model->business = $business;
        $model = $model->load($id);
        if( !$model ){
			$this->session->put_error_msg('找不到该数据');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
        }

        $order_detail = $model->get_order_detail( $business, $inter_id );

        $refund_type = $model->m_get('refund_type');
 
        $param = array();
        $param['ids'] = $id;
        if( !$order_detail ){
            $this->_redirect( Soma_const_url::inst()->get_url('*/*/edit',$param) );
        }

        //越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		}

         //业务订单
        $this->load->model('soma/Sales_order_model','sales_order_model');
        $sales_order_model = $this->sales_order_model;
        $sales_order_model->business = $business;
        
        //获取详情
        $order_id = isset( $order_detail['order_id'] ) ? $order_detail['order_id'] : '';
        $sales_order_model = $sales_order_model->load($order_id);
        if( !$sales_order_model ){
			$this->session->put_notice_msg('此次数据修改失败！');
	  		$this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
        }

        //资产库
        $this->load->model('soma/asset_customer_model');
        $asset_customer_model = $this->asset_customer_model;
        
        //资产明细
        $asset_item_pk = $asset_customer_model->item_table_primary_key();

        $asset_items_detail = $sales_order_model->get_order_asset( $business, $inter_id );

        $asset_customer_model->{$asset_item_pk} = $asset_items_detail['items'][0][$asset_item_pk];
        
        //订单细单数量
        $asset_customer_model->qty = $order_detail['items'][0]['qty'];
        
        $model->asset = $asset_customer_model; 
        
        //退款时需要用到hotel_id
        $hotel_id = isset( $order_detail['hotel_id'] ) ? $order_detail['hotel_id'] : '';
        $model->hotel_id = $hotel_id;

        $notice_msg = '此次数据修改失败！';
        //退款订单单相应处理
        $result = FALSE;
        switch ($act) {
        	case self::REFUND:
        		$refund_item = array( 'is_refund'=>$sales_order_model::STATUS_ITEM_REFUNDED );//细单退款状态已退款
  		    	$sales_order_model->refund_item = $refund_item;
        		$model->order = $sales_order_model;

  		        //进行退款操作  //微信自动退款没有测试
  		    	$order_id = isset( $order_id ) ? $order_id : '';
  		    	$business = isset( $business ) ? $business : 'package';
  		    	$return_err_msg = TRUE;

  		    	if( $refund_type == $model::REFUND_TYPE_WX ){
  		        	$rs = $model->wx_refund_send( $order_id, $business, $inter_id, $return_err_msg );
  		    	}elseif( $refund_type == $model::REFUND_TYPE_CZ ){
  		        	$rs = $model->cz_refund_send( $order_id, $business, $inter_id, $return_err_msg );
  		    	}elseif( $refund_type == $model::REFUND_TYPE_JF ){
  		        	$rs = $model->jf_refund_send( $order_id, $business, $inter_id, $return_err_msg );
  		    	}

  		        if( isset( $rs['status'] ) && $rs['status'] == 1 ){
  		        	$result = $model->order_payment( $business, $inter_id );
  		        }else{
  		        	$result = FALSE;
  		        	$notice_msg = $rs['message'];
  		        }
        		break;

        	case self::CANCEL:
        		$refund = array('refund_status'=>$sales_order_model::REFUND_PENDING); //主单退款状态无退款
        		$sales_order_model->refund = $refund;
  		    	$refund_item = array( 'is_refund'=>$sales_order_model::STATUS_ITEM_UNREFUND );//细单退款状态无申请
  		    	$sales_order_model->refund_item = $refund_item;
        		$model->order = $sales_order_model;

  		        // 取消操作，恢复分账
                $this->soma_db_conn->trans_start();
  		        $result = $model->order_cancel( $business, $inter_id );
                $billing_service = \App\services\soma\SeparateBillingService::getInstance();
                $billing_result  = $billing_service->updateOrderSeparateBillingInfo(
                    $order_id,
                    \App\models\soma\SeparateBilling::STATUS_WAITING_CHECK
                );
                if ($result && $billing_result) {
                    $this->soma_db_conn->trans_complete();
                } else {
                    $result = false;
                }
        		break;

        	case self::PENDING:
        		//审核操作
  		        $result = $model->order_check( $business, $inter_id );
			    if( $result ){
					/***********************发送模版消息****************************/
			    	//发送模版消息
			    	$this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
					$MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

					$openid = $order_detail['openid'];//发送给那个用户
					$inter_id= $this->session->get_admin_inter_id();
			    	$MessageWxtempTemplateModel->send_template_by_refund_success( $model, $openid, $inter_id, $business);
				}
        		break;

    		case self::CHECK:
        		$refund_item = array( 'is_refund'=>$sales_order_model::STATUS_ITEM_REFUNDED );//细单退款状态已退款
  		    	$sales_order_model->refund_item = $refund_item;
        		$model->order = $sales_order_model;

  		    	//查询微信退款情况
  		    	$order_id = isset( $order_id ) ? $order_id : '';
  		    	$business = isset( $business ) ? $business : 'package';
  		    	$inter_id = isset( $inter_id ) ? $inter_id : $this->session->get_admin_inter_id();
  		        $rs = $model->wx_refund_check( $order_id, $business, $inter_id );
  		        if( $rs ){
  		        	$result = $model->order_payment( $business, $inter_id );
  		        }
        		break;
        	
        	default:
        		$result = FALSE;
        		break;
        }

  		$message= ($result)?
  		$this->session->put_success_msg('已保存数据！'):
  		$this->session->put_notice_msg($notice_msg);

  		$this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	}
	
	public function edit_post()
	{
	    $this->label_action= '信息维护';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post = $this->input->post();

  		if( !isset( $post['update_time'] ) ){
  			$post['update_time'] = date( 'Y-m-d H:i:s', time() );
  		}

  		//修改状态  如果退款失败,订单标记退款状态也要修改
        $result= $model->load($post[$pk])->m_sets($post)->m_save();
        $message= ($result)?
            $this->session->put_success_msg('已保存数据！'):
            $this->session->put_notice_msg('此次数据修改失败！');

        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	}

	/**
	 * 禁止进行删除操作
	 */
	public function delete()
	{
	    $url= Soma_const_url::inst()->get_url('*/*/index');
	    redirect($url);
	}
	
    public function export()
    {
        $filter = $this->input->get('filter', true);
        if ($inter_id = $this->session->get_temp_inter_id()) {
            $filter['inter_id'] = $inter_id;
        } else {
            $filter['inter_id'] = $this->session->get_admin_inter_id();
        }
        
        $ent_ids= $this->session->get_admin_hotels();
        $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
        if(count($hotel_ids) > 0) {
            $filter['hotel_id'] = $hotel_ids;
        }

        if($s_time = $this->input->get('s_time', true)) {
            $filter['create_time >='] = date('Y-m-d H:i:s', strtotime($s_time));
        } else {
            // $filter['create_time >='] = date('Y-m-d', strtotime("-90 days")) . '00:00:00';
        }

        if($e_time = $this->input->get('e_time', true)) {
            $filter['create_time <='] = date('Y-m-d H:i:s', strtotime($e_time));
        }
        
        $model_name  = $this->main_model_name();
        $model       = $this->_load_model($model_name);
        $result      = $model->get(array_keys($filter), array_values($filter), '*', array('limit' => '3000'));
        
        $data        = array();
        $business    = $model->get_business_type();
        $status      = $model->get_status_label();
        $inter_hotel = $model->get_hotels_hash();
        foreach ($result as $row) {
            $tmp['refund_id']    = $row['refund_id'];
            $tmp['business']     = $row['business'];
            $tmp['inter_id']     = $inter_hotel['publics'][ $row['inter_id'] ];
            $tmp['hotel_id']     = $inter_hotel['hotels'][ $row['hotel_id'] ];
            $tmp['order_id']     = $row['order_id'];
            $tmp['nickname']     = empty($row['nickname']) ? '未知申请人' : $row['nickname'];
            $tmp['subtotal']     = $row['subtotal'];
            $tmp['refund_total'] = $row['refund_total'];
            $tmp['create_time']  = $row['create_time'];
            $tmp['status']       = $status[ $row['status'] ];
            $data[] = $tmp;
        }

        $header = ['退款ID', '所属业务', '公众号', '酒店', '订单ID', '退款人', '商品统计', '退款总额', '申请时间', '状态'];
        $this->_do_export($data, $header, 'csv', true);
    }
    
}
