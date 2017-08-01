<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Admin_Mall {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '交易订单';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'mall/shp_orders';
	}

	public function delete()
	{
	    
	}
	
	public function grid()
	{
		$this->label_action= '订单列表';
	    $temp_id= $this->session->get_temp_inter_id();
	    if( $temp_id ) $inter_id= $temp_id;
	    else $inter_id= $this->session->get_admin_inter_id();
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

        $sts= $model->order_status();
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
			. '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-filter"></i> 订单状态</button></div>'
			. '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
			. '<option value="-">全部</option>'. $ops
			. '</select>'
			. '<span class="input-group-btn"><button id="export_order_btn" type="button" class="btn btn-sm btn-success"><i class="fa fa-download"></i> 导出</button></span>'
			. '</div>';
//      $jsfilter_btn.= '&nbsp;&nbsp;<div class="input-group">'
			// . '<div class="input-group-btn"><button type="button" class="btn btn-sm btn-success">支付状态</button></div>'
			// . '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
			// . '<option value="-">全部</option>'. $ops
			// . '</select>'
			// . '</div>';

        //echo $ops;die;
        $current_url= current_url();
        $export_url= EA_const_url::inst()->get_url('*/*/export_list' );
        $jsfilter= <<<EOF
$('#filter_status').change(function(){
	var go_url= '?'+ $(this).attr('name')+ '='+  $(this).val();
	//alert(go_url);
	if($(this).val()=='-') window.location= '{$current_url}';
	else window.location= '{$current_url}'+ go_url;
});
$('#export_order_btn').click(function(){
	var status= $('#filter_status').val();
	var url= '{$export_url}';
    if( !isNaN(status) ){
	    var p= '?status='+ status;
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

			$item_model= $this->items;
			$items= $this->items->get_data_filter(array('order_id'=> $model->m_get('order_id')));
			$item_status= $this->items->order_item_status();

			$item_arr= array();
			$status= EA_base::inst()->get_status_options_();
			$openid_array= array( );
			foreach($items as $k=> $v){
			    //用来累计查询openid的详细信息
				if($v['consumer']) $openid_array[$v['consumer']]= $v['consumer'];
				
				//本订单单品信息格式转换
				$tmp= $v;
				//$tmp['status']= (isset($item_status[$v['status']]))? $item_status[$v['status']]: $v['status'];
				$tmp['is_add_pack']= (isset($status[$v['is_add_pack']]))? $status[$v['is_add_pack']]: $v['is_add_pack'];
				$tmp['market_price']= show_price_prefix($tmp['market_price'],'￥');
				$tmp['promote_price']= show_price_prefix($tmp['promote_price'],'￥');
				$tmp['price']= show_price_prefix($tmp['price'],'￥');

				//本订单地址信息格式转换
				$address_id= $v['addr_id'];
				$address= $this->shp_address->load($address_id);
				if($address) 
					$tmp['address']= $address->m_get('country')
						. $address->m_get('province'). ' '
						. $address->m_get('city'). ' '
						. $address->m_get('region'). ' '
						. $address->m_get('address'). ' '
						. $address->m_get('zip_code'). ','
						. $address->m_get('contact'). ','
						. $address->m_get('phone')
					;
				if( $tmp['ex_order']== $item_model::EX_ORDER_T ){
				    $item_arr[$tmp['gs_id']. '_'. $k]= $tmp;
				    $item_arr[$tmp['gs_id']. '_'. $k]['num_item']= 1;
				    
				} else {
				    if( isset($item_arr[$tmp['gs_id']]) ) {
				        $item_arr[$tmp['gs_id']]['id'].= ','. $tmp['id'];
				        $item_arr[$tmp['gs_id']]['num_item']+= 1;
				    } else {
				        $item_arr[$tmp['gs_id']]= $tmp;
        				$item_arr[$tmp['gs_id']]['num_item']= 1;
				    }
				}
			}
//print_r($item_arr);die;

			//转赠信息
			$gifts= $this->gifts->get_data_filter(array('order_id'=> $model->m_get('order_id')));
			$gift_status= $this->gifts->order_gift_status();
			foreach($gifts as $k=> $v){
			    //用来累计查询openid的详细信息
				if($v['ge_openid']) $openid_array[$v['ge_openid']]= $v['ge_openid'];
				if($v['ge_openid']) $openid_array[$v['gt_openid']]= $v['gt_openid'];
				
				$gifts[$k]['status']= (isset($gift_status[$v['status']]))? $gift_status[$v['status']]: $v['status'];
				//$gifts[$k]['ge_openid']= hide_string_prefix($gifts[$k]['ge_openid'],'6');
				//$gifts[$k]['gt_openid']= hide_string_prefix($gifts[$k]['gt_openid'],'6');
			}
			
			if( count($openid_array)>0 ){
			    $openids= array_keys($openid_array);
			    $result= $this->db->where_in( 'openid', $openids)->get('fans' )->result_array();
			    foreach ($result as $k=>$v){
			        $openid_array[$v['openid']]= $v['nickname'];
			    }
			    //print_r($openid_array);
			}

			//客户信息
			$fans= $this->publics_model->get_fans_info( $model->m_get('openid') );
				
			//发票信息
			$this->load->model('mall/shp_invoice');
			$shp_invoice= $this->shp_invoice;
			$invocie= $this->shp_invoice->find( array(
			    'order_id'=>$model->m_get('order_id'),
			    'single'=>$shp_invoice::SINGLE_F,
			    'status'=>$shp_invoice::STATUS_DEFAULT,
			) );

		} else {
			//for add page.
	        $model= $model->load($id);
	        if(!$model) $model= $this->_load_model();
			$fields_config= $model->get_field_config('form');

			$items= array();
		}

		$view_params= array(
		    'model'=> $model,
		    'item_model'=> $item_model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'items'=> $item_arr,
		    'label_items'=> $label_items,  
		    'item_status'=> $item_status, //状态中文名 
		    'item_ext'=> $status,         //是/否
		    'gifts'=> $gifts,
		    'fans'=> $fans,
		    'invoice'=> $invocie,
		    'label_gifts'=> $label_gifts,
		    'openid_array'=> $openid_array,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}
	
	public function edit_post()
	{
	    $item_model= $this->_load_model('mall/shp_order_items');
		$post= $this->input->post();
        //print_r($post);die;
        if( !empty($post['order_id'])){
            if( is_array($post['trans_no']) ){
                //多个单号添加
                
                //分解数量大于2的快递单号
                foreach ($post['trans_no'] as $k=>$v){
                     if(strstr($k, '_')){
                        $pids= explode('_', $k);
                        foreach($pids as $sv){
                            $post['trans_no'][$sv]= $v;
                        }
                        unset($post['trans_no'][$k]);
                    }
                }
                //分解数量大于2的快递公司
                foreach ($post['trans_company'] as $k=>$v){
                    if(strstr($k, '_')){
                        $pids= explode('_', $k);
                        foreach($pids as $sv){
                            $post['trans_company'][$sv]= $v;
                        }
                        unset($post['trans_company'][$k]);
                    }
                }
                
                //print_r($post);die;
                foreach ($post['trans_no'] as $k=>$v){
                    if(!empty($v[0])){
                        $data= array(
                            'trans_no'=> $v[0],
                            'trans_company'=> $post['trans_company'][$k][0],
                        );
                        //print_r($data);die;
                        $reuslt= $item_model->update_transno_batch( $data, NULL, array('id'=>$k) );
                    }
                }
                
            } else {
                //批量设置
                $data= array(
                    'trans_no'=> $post['trans_no'],
                    'trans_company'=> $post['trans_company'],
                );
                $reuslt= $item_model->update_transno_batch($data, array(), array('order_id'=>$post['order_id']) );
            }
            //刷新订单的状态
    		$this->load->model('mall/shp_orders');
            $this->shp_orders->order_status_flush($post['order_id']);
            
            if($reuslt){
                //更新发票状态
                if( isset($post['invoice_id']) && $post['invoice_id'] ){
                    $this->load->model('mall/shp_invoice');
                    $inv_model= $this->shp_invoice;
                    $inv_model->load($post['invoice_id'])->m_set('update_time', date('Y-m-d H:i:s') )
                        ->m_set('status', $inv_model::STATUS_SHIPPING)->m_save();
                }
                
                $this->session->put_success_msg('订单处理成功');
                $this->_redirect( urldecode( $this->input->get('referer') ) );
                
            } else {
                $this->session->put_error_msg('发货失败');
                $this->_redirect(EA_const_url::inst()->get_url('*/items/edit', array('order_id'=> $post['order_id'])));
            }
        }
	}

	/**
	 * 单品核销操作
	 */
    public function consume_handle()
    {
	    $return= array('status'=>2, 'message'=>'核销码格式错误。');
	    $admin_id= $this->session->get_admin_id();
	    $username= $this->session->get_admin_username();
        $admin= "{$admin_id}:{$username}";
        $code= $this->input->post('code');
        $inter_id= $this->input->post('inter_id');
        $order_id= $this->input->post('order_id');
        
        $code= str_replace(array('-',' '), array('',''), $code);
        if(strlen($code)!= 20){
            $return['status']= 2;
            die( json_encode($return) );
        }
        
        $this->load->model('mall/shp_orders');
        $result= $this->shp_orders->qr_consumer($code, $admin, $inter_id);
        
        if( isset($result['status']) && $result['status']==1){
            $return['status']= 1;
            $return['message']= $result['message'];
    
        } else {
            $return['message']= $result['message'];
        }
        echo json_encode($return);
    }

    /**
     * 仅供酒店管理人员强制核销商品
     */
    public function consume_force()
    {
        $return= array('status'=>2, 'message'=>'参数错误。');
        $admin_id= $this->session->get_admin_id();
        $username= $this->session->get_admin_username();
        $admin= "{$admin_id}:{$username}";
        $code= $this->input->post('code');
        $inter_id= $this->input->post('inter_id');
        $order_id= $this->input->post('order_id');
        
        $code= str_replace(array('-',' '), array('',''), $code);
        if(strlen($code)!= 20){
            $return['status']= 2;
            die( json_encode($return) );
        }
        
        $this->load->model('mall/shp_orders');
        $result= $this->shp_orders->force_consumer($ids, $admin, $inter_id);
        
        if( isset($result['status']) && $result['status']==1){
            $return['status']= 1;
            $return['message']= $result['message'];
        
        } else {
            $return['message']= $result['message'];
        }
        echo json_encode($return);
    }

    /**
     * 后台拆分细单操作
     */
    public function separate_item()
    {
        $return= array('status'=>2, 'message'=>'参数错误。');
    
        $item_id= $this->input->post('id');
        $order_id= $this->input->post('order_id');
        $inter_id= $this->input->post('inter_id');
    
        if( !$item_id || !$order_id || !$inter_id ){
            $return['status']= 2;
            die( json_encode($return) );
        }
    
        $this->load->model('mall/shp_orders');
        $result= $this->shp_orders->item_separate($order_id, $item_id, $inter_id);
        echo json_encode($result);
    }

    /**
     * 卡购订单
     */
    public function sync_order()
    {
        $return= array('status'=>2, 'message'=>'参数错误。');
    
        $order_id= $this->input->post('order_id');
        $inter_id= $this->input->post('inter_id');
    
        if( !$order_id || !$inter_id ){
            $return['status']= 2;
            
        } else {
            if( $inter_id=='a453956624' ){
        		$this->load->model('mall/shp_orders', 'orders_model');
        		$this->load->model('mall/shp_order_items', 'items_model');
                $order= $this->orders_model->find( array('order_id'=>$order_id) );
                $items= $this->items_model->find_all( array( 'order_id'=>$order_id ) );
                if($order && $order['transaction_id']){
                    $payment= TRUE;
                    $this->load->library('Mall/Lib_kargo');
                    $result= Lib_kargo::inst()->order_create($order, $items, $payment);
                    if($result){
                        //获取单号卡购的单号（以供查询之用）和key（解密卡号之用），写入订单中
                        $this->orders_model->load($order['order_id'])->m_save( array(
                            'out_order_id'=> $result['kc-ord-id'],
                            'out_order_key'=> $result['kc-ord-key'],
                        ) );
                        $return['status']= 1;
                    } else {
                        $return['message']= '订单同步失败';
                    }
                }
            }
        }
        echo json_encode($return);
    }
    
    public function consume_translate()
    {
	    $username= $this->session->get_admin_username();
	    if($username=='admin'){
            $oid= $this->input->get('o');
            $iid= $this->input->get('i');
            $this->load->model('mall/shp_orders');
            echo $this->shp_orders->qr_order_no_splite($oid, $iid);
            
	    } else {
	        echo '参数错误';
	    }
    }

    /**
     * 订单列表按钮导出处理
     */
    public function export_list()
    {
        $this->load->model('mall/shp_orders');
        $start= $this->input->get('start');  //'2015-12-01'
        $end= $this->input->get('end');     //'2015-12-01'
    
        //不设定时间最多导出3个月的数据
        $field= array('gs_name','price','status' );
        // $data= $this->shp_orders->export_item( array('status'=>0 ), $field, $start, $end );
        //状态不过滤，只过滤支付状态，导出已支付的单  @author luguihong 2016-07-28
        $orderModel = $this->shp_orders;
        $data= $this->shp_orders->export_item( array('pay_status'=>$orderModel::PAYMENT_T ), $field, $start, $end );
        $header= array('订单号', '下单时间', '支付状态', '商品名称', '金额', '目前状态', '数量' );
        $url= $this->_do_export($data, $header);
    }
    
}
