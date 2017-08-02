<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order extends MY_Admin_Soma {

	protected $label_controller= '交易订单';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'soma/sales_order_model';
	}

	public function grid()
	{
		$this->label_action= '订单列表';
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
// var_dump( $this->input->get() );die;
		$status = $this->input->get('status');
		$settlement = $this->input->get('settlement');
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        $filter_time = '';
        if( $start || $end ){

            $filter_time = " inter_id= '".$inter_id."' ";
            if( $ent_ids ) $filter_time .= ' and hotel_id in('.$ent_ids.')';

            if( $start ){
            	if( strlen($start)<=10 ) $start.= ' 00:00:00';
            	$filter_time .= " and create_time > '" . $start ."'";
            }
            if( $end ){
            	if( strlen($end)<=10 ) $end.= ' 23:59:59';
            	$filter_time .= " and create_time < '" . $end ."'";
            }
            
        	$filter_get = $this->input->get('filter');

        	if( $status ){
        		$filter['status'] = $status;
        	}else{
        		$filter['status'] = $status = $filter_get['status'];
        	}
        	
        	$status = trim( $status, '-' );
        	if( $status ){
        		$filter_time .= ' and status = ' . $status;
        	}

        	if( $settlement ){
        		$filter['settlement'] = $settlement;
        	}else{
        		$filter['settlement'] = $settlement = $filter_get['settlement'];
        	}

        	$settlement = trim( $settlement, '-' );
        	if( $settlement ){
        		$filter_time .= " and settlement = '" . $settlement . "'";
        	}

        }

        //过滤状态=============
		$sts= $model->get_status_label();
		$ops= '';
		foreach( $sts as $k=> $v){
		    if( isset($filter['status']) && $filter['status']==$k ) $ops.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
		    else $ops.= '<option value="'. $k. '">'. $v. '</option>';
		}
		if( !isset($filter['status']) || $filter['status']===NULL ) $active= 'disabled';
		else $active= 'btn-success';

		//过滤业务类型=============
		$sts3= $model->get_settle_label();
		$ops3= '';
		foreach( $sts3 as $k=> $v){
		    if( isset($filter['settlement']) && $filter['settlement']==$k ) $ops3.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
		    else $ops3.= '<option value="'. $k. '">'. $v. '</option>';
		}
		if( !isset($filter['settlement']) || $filter['settlement']===NULL ) $active3= 'disabled';
		else $active3= 'btn-success';
		$sts2= $model->get_business_type();
		$ops2= '';
		foreach( $sts2 as $k=> $v){
		    if( isset($filter['business']) && $filter['business']==$k ) $ops2.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
		    else $ops2.= '<option value="'. $k. '">'. $v. '</option>';
		}
		if( !isset($filter['business']) || $filter['business']===NULL ) $active2= 'disabled';
		else $active2= 'btn-success';
		
		if($inter_id == FULL_ACCESS && !$this->current_inter_id ){
		    $export_btn= '';
		} else {
		    $export_btn= '<span class="input-group-btn"><button id="export_order_btn" type="button" class="btn btn-sm btn-success"><i class="fa fa-download"></i> 导出</button></span>';
		}
		
        $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
			. '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active3. '"><i class="fa fa-filter"></i> 购买方式</button></div>'
			. '<select class="form-control input-sm" name="filter[settlement]" id="filter_settlement" >'
			. '<option value="">全部</option>'. $ops3
			. '</select>' 
// 			. '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active2. '"><i class="fa fa-filter"></i> 业务类型</button></div>'
// 			. '<select class="form-control input-sm" name="filter[business]" id="filter_business" >'
// 			. '<option value="-">全部</option>'. $ops2
// 			. '</select>' 
			. '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-filter"></i> 状态</button></div>'
			. '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
			. '<option value="">全部</option>'. $ops
			. '</select>' 
			    
			. $export_btn

			. '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-calendar"></i> 日期</button></div>'
			. '<input type="text" id="el_start" name="start" class="form-control input-sm" placeholder="开始日期" value="'.$start.'">'
			. '<div class="input-group-btn"> ~ </div>'
			. '<input type="text" id="el_end" name="end" class="form-control input-sm" placeholder="结束日期" value="'.$end.'">'
			. '<span class="input-group-btn"><button id="search" type="button" class="btn btn-sm btn-success"><i class="fa fa-search"></i> 查看</button></span>'
			    
			. '</div>';

        //echo $ops;die;
		$index_url= Soma_const_url::inst()->get_url('*/*/index' );
        $current_url= current_url();
        $filter_p= $this->input->get('filter');
        //print_r( $filter_p );die;
        $base_url= '';
        if($filter_p){
            foreach ( $filter_p as $k=>$v ){
                if( $base_url ) {
                    $base_url.= "&filter[{$k}]=". $v;
                } else {
                    $base_url.= "?filter[{$k}]=". $v;
                }
            }
            $current_url.= $base_url;
        }
        
        $link_label= ( $current_url== current_url() )? '?': '&';
        if( $base_url ){
        	$export_url= Soma_const_url::inst()->get_url('*/*/export_list' ). $base_url;
        }else{
        	$export_url= Soma_const_url::inst()->get_url('*/*/export_list?1' );
        }
        $url = Soma_const_url::inst()->get_url('*/*/grid?1' );
        $jsfilter= <<<EOF
$('#filter_settlement').change(function(){
	var go_url= '{$link_label}'+ $(this).attr('name')+ '='+  $(this).val();
	//alert(go_url);
	if($(this).val()=='-') window.location= '{$current_url}';
	else if( $(this).val()=='' ) window.location='{$index_url}';
	else window.location= '{$current_url}'+ go_url;
});
$('#filter_business').change(function(){
	var go_url= '{$link_label}'+ $(this).attr('name')+ '='+  $(this).val();
	//alert(go_url);
	if($(this).val()=='-') window.location= '{$current_url}';
	else if( $(this).val()=='' ) window.location='{$index_url}';
	else window.location= '{$current_url}'+ go_url;
});
$('#filter_status').change(function(){
	var go_url= '{$link_label}'+ $(this).attr('name')+ '='+  $(this).val();
	//alert(go_url);
	if($(this).val()=='-') window.location= '{$current_url}';
	else if( $(this).val()=='' ) window.location='{$index_url}';
	else window.location= '{$current_url}'+ go_url;
});
$('#export_order_btn').click(function(){
	var status= $('#filter_status').val();
	var settlement= $('#filter_settlement').val();
	var url= '{$export_url}';
	var start = $('#el_start').val();
    var end = $('#el_end').val();
	var p = '';
    if( !isNaN(status) ){
    	p += '&status='+ status;
    }
	if( settlement != '' ){
	    p += '&settlement='+ settlement;
	}
	if( start != '' ){
	    p += '&start='+ start;
	}
	if( end != '' ){
	    p += '&end='+ end;
	}
	window.location= url+= p;
});
$("#search").click(function(){
	var status= $('#filter_status').val();
	var settlement= $('#filter_settlement').val();
    var start = $('#el_start').val();
    var end = $('#el_end').val();
    var url= '{$url}&search=1';
    var p = '';
    if( status != '' ){
	    p += '&status='+ status;
	}
	if( settlement != '' ){
	    p += '&settlement='+ settlement;
	}
	if( start != '' ){
	    p += '&start='+ start;
	}
	if( end != '' ){
	    p += '&end='+ end;
	}
	// alert(url+p);
	window.location.href=url+p;
});
EOF;
        $viewdata= array(
            'js_filter_btn'=> $jsfilter_btn,
            'js_filter'=> $jsfilter,
            'search'=> $this->input->get('search'),
        );

        if( $filter_time ){
			// 分页
			$page = array();
			if($page_size = $this->input->get('page_size', true)) {
				$page['page_size'] = $page_size;
			}
			if($page_size = $this->input->get('page_num', true)) {
				$page['page_num'] = $page_size;
			}
			ini_set('memory_limit','-1');
            $this->_grid_new( $filter_time, $inter_id, $viewdata, array(), $page);
            die;
        }

	    $this->_grid($filter, $viewdata);
	}

	
	/**
	 * 改造新后台，适应_grid_new数据获取，参考 Sales_order_model::filter()
	 *
	 * @param      <type>  $reuslt  The reuslt
	 * @param      <type>  $model   The model
	 */
	public function get_result_grid($reuslt, $model, $total = 100){
		$ori_data = parent::get_result_grid($reuslt, $model, $total);
		return $model->get_new_backend_order_data($ori_data);
	}

	public function edit()
	{
		$this->label_action= '订单详情';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);

		$id= intval($this->input->get('ids'));
		$model= $model->load($id);
		
		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		}
		
		if($id){
			//for edit page.
			if(!$model) $model= $this->_load_model();

			/* @var Sales_order_model $model */
			$order_info = $model->getByID($id);// add by chencong <chencong@mofly.cn> 2017/07/31 增加备注字段信息

			$this->load->model('soma/gift_order_model','gifts');
			$label_gifts= $this->gifts->attribute_labels();

			//细单明细
			$business = 'package';
			$inter_id = $this->session->get_admin_inter_id();
			$model->business = $business;
			$items = $model->get_order_items( $business, $inter_id );
			
			// 组合细单信息
			$combine_itmes = $model->getCombineOrderItems($inter_id);
			$combine_assets= $model->getCombineOrderAssets($inter_id);
			$assets_hash = array();
			foreach($combine_assets as $row)
			{
				$assets_hash[$row['order_item_id']] = $row;
			}

			$combine_usage = array();
			foreach ($combine_itmes as $item)
			{
				$tmp_row['name']      = $item['name'];
				$tmp_row['child_oid'] = $item['order_id'];
				$tmp_row['get_qty']   = $tmp_row['now_qty'] = 0;
				if(isset($assets_hash[$item['item_id']]))
				{
					$tmp_row['get_qty'] = $item['qty'];
					$tmp_row['now_qty'] = $assets_hash[$item['item_id']]['qty'];
				}
				$combine_usage[] = $tmp_row;
			}
			// var_dump($combine_usage);exit;

			//购买总数
            $total = 0;
			
			//套票内容反序列化输出
			$this->load->helper('soma/package');
			$status_can = $model->get_status_can_label();
			foreach( $items as $k=>$v ){

			    $total += $v['qty'];

			    if( $v['compose'] ){
			         $items[$k]['compose'] = show_compose($v['compose']);
			    }

			    $items[$k]['face_img'] = show_cat_img( $v['face_img'], 200, NULL );
			    
			    //能否退款／赠送／状态
			    $items[$k]['can_refund'] = $status_can[$v['can_refund']];
			    $items[$k]['can_gift'] = $status_can[$v['can_gift']];
			    $items[$k]['can_mail'] = $status_can[$v['can_mail']];
			    $items[$k]['can_pickup'] = $status_can[$v['can_pickup']];
			    $items[$k]['can_reserve'] = $status_can[$v['can_reserve']];

			    //价格显示
			    $items[$k]['price_market'] = show_price_prefix($v['price_market'], '￥');
			    $items[$k]['price_package'] = show_price_prefix($v['price_package'], '￥');

			    $this->load->model( 'hotel/hotel_model' );
		        $hotel_info = $this->hotel_model->get_hotel_detail( $v['inter_id'], $v['hotel_id'] );
		        if( $hotel_info ){
		        	$items[$k]['hotel_name'] = $hotel_info['name'];
		        }else{
		        	$items[$k]['hotel_name'] = '';
		        }

		        $rooms_detail = $this->hotel_model->get_room_detail( $v['inter_id'], $v['hotel_id'], $v['room_id'] );
		        if( $rooms_detail ){ 
		        	$items[$k]['room_name'] = $rooms_detail['name'];
		        }else{ 
		        	$items[$k]['room_name'] = '';
		        }
			}

			$openid= $model->m_get('openid');
			$user_info = (array) $model->get_userinfo( $openid );
			
			//购买人微信账号
			$this->load->model('wx/publics_model');
			$fans= (array) $this->publics_model->get_fans_info( $openid );
			$fans+= $user_info;
			
			
			// 查出订单的优惠信息
			$this->load->model('soma/Sales_order_discount_model');
			$discount_model= $this->Sales_order_discount_model;
			$discount= $discount_model->filter( array('order_id'=> $model->m_get('order_id') ) );
			foreach ($discount['data'] as $k=>$v ){
			    $discount['data'][$k][4]= $discount_model->parse_discount_value($v);
			}
			$discount_fields_config= $discount_model->get_field_config('grid');
			$discount_default_sort= $discount_model::default_sort_field();
			
			//查询赠送订单信息=============
			$aitem_ids= $gift_ids= $self_gift= $other_gift= array();
			
			$this->load->model('soma/Asset_item_package_model');
			$asset_model= $this->Asset_item_package_model;
			$aitems= $asset_model->get_order_items_byOrderids( array($model->m_get('order_id')), $business, $inter_id );
			foreach ($aitems as $v) $aitem_ids[]= $v['item_id']; //得出订单对应的资产细单id

            $pending = $gifting  = $received = $timeout = 0;
			if( count($aitem_ids)>0 ){
			    $this->load->model('soma/Gift_order_model');
			    $this->load->model('soma/Gift_item_package_model');
			    $gift_model= $this->Gift_order_model;
			    $gitem_model= $this->Gift_item_package_model;
			    	
			    $gitems= $gitem_model->get_order_items_byAssetItemIds( $aitem_ids, $business, $inter_id );
			    foreach ($gitems as $v) $gift_ids[]= $v['gift_id']; //得出订单对应的赠送细单，还没赠送为空
			    
			    if( count($gitems)>0 ){
			        $gifts= $gift_model->get_order_list_byIds($business, $inter_id, $gift_ids);
			        foreach ($gifts as $k=>$v){
			            if( $v['openid_give']== $openid )
                        {
                            $self_gift= $v;    //自己赠送他人的赠送单

                            if( $v['status'] == $gift_model::STATUS_PENDING )
                            {
                                //待发送
                                $pending += $v['total_qty'];

                            } elseif( $v['status'] == $gift_model::STATUS_GIFTING ) {
                                //赠送中
                                $gifting += $v['total_qty'];

                            } elseif( $v['status'] == $gift_model::STATUS_GETTING ) {
                                //接受中，就是群发的没有接收完
                                $receivedCount = $gift_model->load( $v['gift_id'] )->get_receiver_count($inter_id, $v['gift_id'], $v['openid_give']);
                                $num = $receivedCount*$v['per_give'];
                                $received += $num;
                                $gifting += $v['total_qty'] - $num;//还剩多少份没有接收

                            } elseif( $v['status'] == $gift_model::STATUS_RECEIVED ) {
                                //已领取
                                $received += $v['total_qty'];

                            } elseif( $v['status'] == $gift_model::STATUS_TIMEOUT ) {
                                //超时退回
                                $timeout += $v['total_qty'];
                                $receivedCount = $gift_model->load( $v['gift_id'] )->get_receiver_count($inter_id, $v['gift_id'], $v['openid_give']);
                                $num = $receivedCount*$v['per_give'];
                                $received += $num;

                            }
                        } else {
                            $other_gift= $v;    //朋友的赠送单
                        }
			        }
			    }
			}

		} else {
			//for add page.
			$items= array();
			$discount= array();
		}

        $scopeName = $this->getScopeDiscountName( $model->m_get('scope_product_link_id') );

		$items_grid_field= array(
		    // 'product_id'=> '商品ID',
		    // 'inter_id'=> '公众号',
		    // 'hotel_id'=> '酒店',
		    'face_img'=> '封面图',
		    'hotel_name'=> '酒店',
		    'name'=> '商品名',
		    'price_market'=> '市场价',
		    'price_package'=> '现价',
		    'compose'=> '内容',
		    // 'face_img'=> '封面图',
		    'can_refund'=> '能否退款',
		    'can_gift'=> '能否赠送',
		    'can_mail'=> '能否邮寄',
		    'can_pickup'=> '能否自提',
		    'can_reserve'=> '能否预定',
		    'room_name'=> '房型',
		    //'nickname'=> '购买人',     //转移至客户信息一栏
		    //'phone'=> '电话',         //转移至客户信息一栏
		    'validity_date'=> '有效日期',
		    'expiration_date'=> '过期日期',
		);
		
		$fields_config= $model->get_field_config('form');
		$view_params= array(
		    'fans'=> $fans,
		    'model'=> $model,
		    'items'=> $items,
		    'discount'=> $discount,
		    'discount_model'=> $discount_model,
		    'check_data'=> FALSE,
		    'fields_config'=> $fields_config,
		    'items_grid_field'=> $items_grid_field,  
		    'discount_default_sort'=> $discount_default_sort,  
		    'discount_fields_config'=> $discount_fields_config,  
		    // 'item_status'=> $item_status, //状态中文名 
		    // 'item_ext'=> $status,         //是/否
		    
		    'self_gift'=> $self_gift,
		    'other_gift'=> $other_gift,

		    'pending'=> $pending,
		    'gifting'=> $gifting,
		    'received'=> $received,
		    'not_gift'=> $total-$pending-$gifting-$received,
		    'combine_usage' => $combine_usage,

		    'scopeName' => $scopeName,
			'order_id' => $id,// add by chencong <chencong@mofly.cn> 2017/07/31 增加备注字段信息
			'remark' => isset($order_info['remark']) ? $order_info['remark'] : '',// add by chencong <chencong@mofly.cn> 2017/07/31 增加备注字段信息
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	/**
	 * 编辑备注
	 * @author chencong <chencong@mofly.cn>
	 * @date 2017/07/31
	 */
	public function ajax_edit_remark(){
		$id = intval($this->input->post('soid'));
		$remark = addslashes($this->input->post('remark'));
		$inter_id = $this->session->get_admin_inter_id();

		// 更新备注
		$model_name = $this->main_model_name();
		/* @var Sales_order_model $model */
		$model = $this->_load_model($model_name);
		if(empty($model)){
			return $this->_ajaxReturn('参数错误，请稍后重试');
		}
		$res = $model->save_remark($inter_id, $id, $remark);
		if(!$res['res']){
			return $this->_ajaxReturn($res['msg']);
		}

		return $this->_ajaxReturn('编辑成功', array(), 1);
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
                $this->_redirect(Soma_const_url::inst()->get_url('*/items/edit', array('order_id'=> $post['order_id'])));
            }
        }
	}

	/**
     * 订单列表按钮导出处理
     */
    public function export_list()
    {
    	// var_dump( $this->input->get() );die;
        $this->load->model('soma/sales_order_model');
        $start= $this->input->get('start');  //'2015-12-01'
        $end= $this->input->get('end');     //'2015-12-01'
        $status= $this->input->get('status');     //'2015-12-01'
        $settlement= $this->input->get('settlement');     //'2015-12-01'
        $business= 'package'; //$this->input->get('bsn');
        $inter_id= $this->session->get_admin_inter_id();
        if($inter_id == FULL_ACCESS ){
            $inter_id= $this->current_inter_id;
        }
    
        //不设定时间最多导出3个月的数据
        $item_field= array( 'hotel_id','name','sku','price_package', 'qty' );
//使用测试
// $inter_id = 'a445091342';
        $get_filter= $this->input->get('filter');
        $filter= array();
        if( $get_filter ){
	        foreach ($get_filter as $k=>$v){
	            $filter[$k]= $v;
	        }
	    }
	    $status = trim( $status, '-' );
        if($status) $filter['status']= $status;
	    $settlement = trim( $settlement, '-' );
        if($settlement) $filter['settlement']= $settlement;

        //如果hotel_id不为空，添加hotel_id条件
        $ent_ids= $this->session->get_admin_hotels();
	    $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );

        $data= $this->sales_order_model->export_item( $business, $inter_id, $filter, $item_field, $start, $end );
// var_dump( $data );die;
        /**
         [order_id] => 1000000201
         [openid] => o9Vbtw8ESEPPNLdrW2EHhyzz52JM  -> 需填充
         [create_time] => 2016-04-06 20:18:44
         [settlement] => 普通购买
         [consume_status] => 部分消费
         [hotel_id] => 1081   -> 需填充
         [name] => 测试商品4
         [price_package] => 0.01
         [qty] => 4
         [total] => 0.04
         [gift_total] => 0
         [shipping_total] => 0
         [consumer_total] => 0
         [shipment_total] => 0
         [shipping_ids] => ''
         */
        $hotels= $contacts= array();
        foreach ($data as $k=> $v){
            $hotels[$v['hotel_id']]= $v['hotel_id'];
            $contacts[$v['openid']]= $v['openid'];
        }
        if( count( $hotels ) > 0 ){

	        $this->load->model('hotel/Hotel_ext_model');
	        $hotel= $this->Hotel_ext_model->get_data_filter( array('hotel_id'=> array_values($hotels),'inter_id'=>$inter_id ) );
        	$hotel= $this->Hotel_ext_model->array_to_hash( $hotel, 'name', 'hotel_id' );
        }else{
        	$hotels = array();
        }
        
        if( count( $contacts ) > 0 ){

	        $this->load->model('soma/Sales_order_model');
	        $contact= $this->Sales_order_model->get_customer_contact( array('openid'=> array_values($contacts)), TRUE );
	        $contact= $this->Sales_order_model->array_to_hash( $contact, 'name', 'openid' );
        }else{
        	$contact = array();
        }
        
        foreach ($data as $k=> $v){
            $data[$k]['hotel_id']= isset($hotel[$v['hotel_id']])? $hotel[$v['hotel_id']]: $v['hotel_id'];
            // $data[$k]['openid']= isset($contact[$v['openid']])? $contact[$v['openid']]: $v['openid'];
        }
        // $header= array('订单号', '购买人', '购买电话', '下单时间', '购买方式', '消费状态','退款状态', '实付金额', '酒店名称', '商品名称', '单价', '数量', '订单总额', '未使用数量' );
        $header= array(
	        		'订单号',
	        		'购买人',
	        		'购买电话',
	        		'下单时间',
	        		'支付时间',
	        		'购买方式', 
	        		'消费状态',
	        		'退款状态', 
	        		'储值支付金额',
	        		'积分使用',
	        		'实付金额', 
	        		'酒店名称', 
	        		'商品名称', 
	        		'SKU',
	        		'单价', 
	        		'购买件数', 
	        		'订单总额',
	        		'获得数量',
	        		// '未使用数量', 
	        		'已赠送', //全部已赠送				思路：使用单号去赠送表查询。一个数量不论被赠送多少次，数量都是1
	        		'已邮寄', //全部已经邮寄  			思路：使用单号去邮寄表查找数量
	        		'已自提',//全部已经自提			思路：使用单号去消费细单查找
	        		'已出货总数',//已邮寄＋已自提		
	        		'邮寄编号',//全部已经邮寄了的编号  	思路：使用单号去邮寄表查找对应邮寄ID
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
    
    /**
     * 针对微信支付成功，却没有资产的订单进行资产重建
     * 注：此类型订单是订单已记录了相应的支付信息，没有该信息默认订单未支付
     * @return [type] [description]
     */
    public function rebuild_order_payment() {
    	
    	//防止其他账号使用该功能。
	    $this->_toolkit_writelist();

    	// $order_id = $this->input->post('oid');
    	$order_id = $this->input->get('oid', true);
    	$this->load->model('soma/sales_order_model', 'o_model');
    	$order = $this->o_model->load($order_id);
    	if(!$order || $order->m_get('is_payment') != $order::IS_PAYMENT_YES) {
    		die('订单信息错误，不能重建数据！');
    	}

    	$this->load->model('soma/asset_item_package_model', 'a_model');
    	$order->business = $order->m_get('business');
    	$asset_items = $this->a_model->get_order_items($order, $order->m_get('inter_id'));
    	if(count($asset_items) > 0 ) {
    		die('订单存在资产，不能重建资产信息！');
    	}

    	$this->load->model('soma/sales_payment_model');
        $payment_model = $this->sales_payment_model;

    	$log_data['paid_ip'] = $this->input->ip_address();
        $log_data['paid_type'] = $payment_model::PAY_TYPE_WX;
        $log_data['order_id'] = $order->m_get('order_id');
        $log_data['openid'] = $order->m_get('openid');
        $log_data['business'] = $order->m_get('business');
        $log_data['settlement'] = $order->m_get('settlement');
        $log_data['inter_id'] = $order->m_get('inter_id');
        $log_data['hotel_id'] = $order->m_get('hotel_id');
        $log_data['grand_total'] = $order->m_get('grand_total');
        $log_data['transaction_id'] = $order->m_get('transaction_id');
        
        $order->order_payment_post( $log_data );
		$payment_model->save_payment($log_data, NULL);  //校验签名时已经记录

		echo "success";
    }

    /**
     * 显示订单信息
     * 
     * @return [type] [description]
     */
	public function show_order() {
		try {
			
			$this->label_action= '公众号订单【套票】';
			$this->_init_breadcrumb($this->label_action);

			$filter = $this->_order_filter();
			$search_form = $this->input->get(null, true);

			$this->load->model('soma/sales_order_model', 'o_model');
			$order = $this->o_model->get_grid_data($filter);
			$header = $this->o_model->get_grid_header();

			$data['data_set'] = $this->o_model->format_grid_data($order, $header);
			$data['column_set'] = $this->o_model->format_grid_header($header);
			$data['form'] = $search_form;
			$data['export_url'] = Soma_const_url::inst()->get_url('*/*/export_order', $search_form);
			$html = $this->_render_content($this->_load_view_file('show_order'), $data, TRUE);
			echo $html;
		} catch (Exception $e) {
			$this->session->put_error_msg('操作失败，请稍后再重新尝试!');
			$this->_redirect(Soma_const_url::inst()->get_url('*/*/show_order'));
		}
	}

    /**
     * 导出订单信息
     * 
     * @return [type] [description]
     */
    public function export_order() {
    	try {
    		$filter = $this->_order_filter();
    		$this->load->model('soma/sales_order_model', 'o_model');
			$order = $this->o_model->get_grid_data($filter);
			$header = $this->o_model->get_grid_header();
			$data = $this->o_model->format_grid_data($order, $header);
			$this->_do_export($data, $header, 'csv', TRUE );
    	} catch (Exception $e) {
    		$this->session->put_error_msg('操作失败，请稍后再重新尝试!');
			$params = $this->input->get(null, true);
			$this->_redirect(Soma_const_url::inst()->get_url('*/*/show_order', $params));
    	}
    }

	protected function _order_filter() {

		$publics = $this->input->get('publics', true);
		$cs_time = $this->input->get('cs_time', true);
		$ce_time = $this->input->get('ce_time', true);
		$ps_time = $this->input->get('ps_time', true);
		$pe_time = $this->input->get('pe_time', true);

		$where = array();
		if($publics != null) { 
			if($publics == FULL_ACCESS) {
				$where['inter_id'] = array();
			} else {
				$where['inter_id'] = explode(',', $publics);
			}
		} else {
			// 没有输入公众号，取当前账号的公众号
			$inter_id = $this->session->get_admin_inter_id();
			if($inter_id == null) { $inter_id = array('deny'); }
			if($inter_id == FULL_ACCESS) { $inter_id = array(); }
			if(!is_array($inter_id)) { $inter_id = array($inter_id); }
			$where['inter_id'] = $inter_id;
		}
		if(count($where['inter_id']) <= 0) { unset($where['inter_id']); }

		if($cs_time != null && $ce_time != null) {
			$where['create_time >='] = $cs_time;
			$where['create_time <='] = $ce_time;
		}
		
		if($ps_time != null && $pe_time != null) {
			$where['payment_time >='] = date('Y-m-d', strtotime($ps_time)) . ' 00:00:00';
			$where['payment_time <='] = date('Y-m-d', strtotime($pe_time)) . ' 23:59:59';
		} else {
			$where['payment_time >='] = date('Y-m-d') . ' 00:00:00';
			$where['payment_time <='] = date('Y-m-d H:i:s');
		}

		$filter['where'] = $where;
		return $filter;
	}


	/**
	 * 储值支付数据初始化，防止数据过大，做分时间段处理
	 */
	public function balance_data_init() {
		
		//防止其他账号使用该功能。
	    $this->_toolkit_writelist();

		$days   = $this->input->get('days', true);
		$s_time = $this->input->get('s_time', true);

		if(!$days) { $days = 30; }
		if(!$s_time) { $s_time = date('Y-m-d'); }

		$this->load->model('soma/Sales_order_model', 'o_model');
		$res = $this->o_model->rebuild_balance_data($s_time, $days);

		if($res) {
			$html = '<html><body><table border="1">';
			foreach ($res as $row) {
				$html .= '<tr>';
				$html .= '<td>' . $row['order_id'] . '</td>';
				$html .= '<td>' . $row['balance_total'] . '</td>';
				$html .= '<td>' . $row['real_grand_total'] . '</td>';
				$html .= '</tr>';
			}
			$html .= '</table></body></html>';
			echo $html;
		} else {
			echo 'fail';
		}

	}

	public function order_list()
	{
		$this->label_action = '订单列表';
        $this->_init_breadcrumb($this->label_action);

		$serviceName  = $this->serviceName(Order_Service::class);
        $serviceAlias = $this->serviceAlias(Order_Service::class);
        $this->load->service($serviceName, null, $serviceAlias);

		$post               = $this->input->post(null, true);
		$get                = $this->input->get(null, true);
		$filter             = array_merge($get, $post);
		$filter['inter_id'] = $this->session->get_admin_inter_id();

        $temp_id = $this->session->get_temp_inter_id();
        if ($temp_id)
        {
            $filter['inter_id'] = $temp_id;
        }

        $ent_ids= $this->session->get_admin_hotels();
        $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
        if(count($hotel_ids)>0)
        {
            $filter['hotel_id'] = $hotel_ids;
        }

        $base_page = array('page_num' => 1, 'page_size' => 20);
        if(empty($filter['page_num'])
        	|| empty($filter['page_size']))
        {
			$filter['page_num']  = $base_page['page_num'];
			$filter['page_size'] = $base_page['page_size'];
        }

        $data = $this->soma_order_service->getNewBackendListData($filter);
        $data['filter'] = $filter;
        $data['base_page'] = $base_page;
        // var_dump($data);exit;
       	
       	$html = $this->_render_content($this->_load_view_file('order_list'), $data, TRUE);
       	echo $html;
	}

	public function export_order_list()
	{
		$post               = $this->input->post(null, true);
		$get                = $this->input->get(null, true);
		$filter             = array_merge($get, $post);
		$filter['inter_id'] = $this->session->get_admin_inter_id();

        $temp_id = $this->session->get_temp_inter_id();
        if ($temp_id)
        {
            $filter['inter_id'] = $temp_id;
        }

        $ent_ids= $this->session->get_admin_hotels();
        $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
        if(count($hotel_ids)>0)
        {
            $filter['hotel_id'] = $hotel_ids;
        }
        
        if(isset($filter['page_num']))
        {
        	unset($filter['page_num']);
        }
        if(isset($filter['page_size']))
        {
        	unset($filter['page_size']);
        }
        if(empty($filter['create_start_time']))
        {
        	// 最多导出3个月数据
        	$filter['create_start_time']    = date('Y-m-d H:i:s', strtotime('-3 month'));
            $filter['create_end_time']      = date('Y-m-d H:i:s');
        }

        $serviceName  = $this->serviceName(Order_Service::class);
        $serviceAlias = $this->serviceAlias(Order_Service::class);
        $this->load->service($serviceName, null, $serviceAlias);
        $data = $this->soma_order_service->getExportListData($filter);

        $this->_do_export($data['data'], $data['header'], 'csv', TRUE);
	}

}
