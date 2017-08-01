<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// error_reporting(0);
class Consumer_shipping extends MY_Admin_Soma {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '订单邮寄';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'soma/Consumer_shipping_model';
	}
	
	public function grid()
	{
	    $this->label_action= '邮寄信息处理';
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

        $status = $this->input->get('status');
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

        	if( $status ){
        		$filter['status'] = $status;
        	}else{
        		$filter_get = $this->input->get('filter');
        		if(!empty($filter_get['status']))
        		{
        			$filter['status'] = $status = $filter_get['status'];
        		}
        	}
        	if( $status ){
        		$filter_time .= ' and status = ' . $status;
        	}

        	foreach($filter as $key => $value)
        	{
        		if(!empty($value) && $key != 'inter_id')
        		{
        			$filter_time .= " and {$key} = '{$value}'"; 
        		}
        	}
        	// echo $filter_time;exit;
        }
	    
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

	    if($inter_id == FULL_ACCESS && !$this->current_inter_id ){
	        $export_btn= '';
	        $batch_btn= '';
	    } else {
	        $export_btn= '<span class="input-group-btn"><button id="export_order_btn" type="button" class="btn btn-sm btn-success"><i class="fa fa-download"></i> 导出</button></span>';

	        $batch_url = Soma_const_url::inst()->get_url('*/*/batch' );
	        $batch_btn= '<span class="input-group-btn"><button id="batch_btn" type="button" class="btn btn-sm btn-success"><a href="'
	            .$batch_url.'" style="color:#fff;" ><i class="fa fa-upload"></i> 去批量发货</a></button></span>';
	    }
	    
	    $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
	        . '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-filter"></i> 状态</button></div>'
            . '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
            . '<option value="-">全部</option>'. $ops
			. '</select>' 
			    
			. $export_btn
			. $batch_btn

			. '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-calendar"></i> 日期</button></div>'
			. '<input type="text" id="el_start" name="start" class="form-control input-sm" placeholder="开始时间" value="'.$start.'">'
			. '<div class="input-group-btn"> ~ </div>'
			. '<input type="text" id="el_end" name="end" class="form-control input-sm" placeholder="结束时间" value="'.$end.'">'
			. '<span class="input-group-btn"><button id="search" type="button" class="btn btn-sm btn-success"><i class="fa fa-search"></i> 查看</button></span>'
			    
			. '</div>';
	    
	    //echo $ops;die;
	    $current_url= current_url();
	    $export_url= Soma_const_url::inst()->get_url('*/*/export_list?1' );
	    $url = Soma_const_url::inst()->get_url('*/*/grid?1' );
	    $jsfilter= <<<EOF
$('#filter_status').change(function(){
    var go_url= '?'+ $(this).attr('name')+ '='+  $(this).val() + '&start=' + $("#el_start").val() + '&end=' + $("#el_end").val();
    //alert(go_url);
    if($(this).val()=='-') window.location= '{$current_url}';
    else window.location= '{$current_url}'+ go_url;
});
$('#export_order_btn').click(function(){
    var status= $('#filter_status').val();
    var start = $('#el_start').val();
    var end = $('#el_end').val();
    var url= '{$export_url}';
    var p = '';
    if( !isNaN(status) ){
	    p += '&status='+ status;
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
    var start = $('#el_start').val();
    var end = $('#el_end').val();
    var url= '{$url}';
    var p = '';
    if( !isNaN(status) ){
	    var p= '&status='+ status;
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
        );

        // 分页
		$page = array('page_num' => 1, 'page_size' => 20);
		if($page_size = $this->input->get('page_size', true)) {
			$page['page_size'] = $page_size;
		}
		if($page_num = $this->input->get('page_num', true)) {
			$page['page_num'] = $page_num;
		}
		// 强制分页，没有分页的设置默认分页，有的话因为都是从get参数获取的，覆盖没有关系
		$filter['page_num'] = $page['page_num'];
		$filter['page_size'] = $page['page_size'];

		// 去除空值过滤
		foreach($filter as $key => $value)
		{
			if(empty($value))
			{
				unset($filter[$key]);
			}
		}

        if( $filter_time ){
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
		$inter_id= $this->session->get_admin_inter_id();
		return $model->get_new_backend_order_data($ori_data, array('inter_id' => $inter_id));
	}

	public function edit()
	{
	    $this->label_action= '邮寄信息处理';
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
	    
	    // 查出订单的优惠信息
	    $this->load->model('soma/Consumer_item_package_model');
	    $item_model= $this->Consumer_item_package_model;
	    $item_i= $item_model->find_all( array('consumer_id'=> $model->m_get('consumer_id') ) );
	    $item_h= $item_model->attribute_labels();
	    $item_s= $item_model->get_item_status_label();
	    $this->load->helper('soma/package');
	    foreach ($item_i as $k=>$v ){
	        $item_i[$k]['face_img']= show_face_img($item_i[$k]['face_img'], 100, '');
	        $item_i[$k]['price_package']= show_price_prefix($item_i[$k]['price_package'], '￥');
	        $item_i[$k]['compose']= show_compose($item_i[$k]['compose']);
	        if( array_key_exists($item_i[$k]['status'], $item_s) ) $item_i[$k]['status']= $item_s[$item_i[$k]['status']];
	    }
	    
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> FALSE,
		    'item_i'=> $item_i,
		    'item_h'=> $item_h,
		    'item_s'=> $item_s,
		    'item_model'=> $item_model,
	        'current_inter_id'=> $this->current_inter_id,
	    );
	    
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    //echo $html;die;
	    echo $html;
	}

	public function hold_post()
	{
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	     
	    $labels= $model->attribute_labels();
	    if( $post[$pk] ) $model= $model->load( $post[$pk] );
	    
	    if( !empty($post[$pk])&& !empty($post['hold_status']) ){
	        if( $post['hold_status']==1 ){
	            $model->hold_shipping(TRUE);
	            $this->session->put_success_msg('已成功挂起！');
	            
	        } elseif( $post['hold_status']==2 ){
	            $model->hold_shipping(FALSE);
	            $this->session->put_success_msg('已取消挂起！');
	        }
	    }
	    $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	}
	
	
	public function edit_post()
	{
	    $this->label_action= '邮寄信息处理';
	    $this->_init_breadcrumb($this->label_action);

	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	    
	    $this->load->library('form_validation');
	    $post= $this->input->post();
	    
	    $labels= $model->attribute_labels();
	    if( $post[$pk] ) $model= $model->load( $post[$pk] );

	    //检查地址是否为空
	    if( $model ){
		    $address = $model->m_get('address');
		    if( empty( $address ) ){
		    	$this->session->put_notice_msg('地址信息不能为空');
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	            $model= $this->_load_model();
		    }
	    }
	    
	    if( empty($post[$pk]) ){
	        //add data.
	        $this->session->put_notice_msg('找不到该数据记录！');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
            $model= $this->_load_model();
	    
	    } elseif( $model->m_get('status')== $model::STATUS_SHIPPED ) {
	        $data= isset($post['remark'])? array('remark'=> $post['remark']): array();
            $result= $model->load($post[$pk])->m_sets( $data )->m_save();
            $message= ($result)?
	            $this->session->put_success_msg('已保存数据！'):
	            $this->session->put_notice_msg('此次数据修改失败！');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	        
	    } else {
	        //
	        $revt= $model->m_get('reserve_date');
	        if( $revt && strtotime($revt)> time() ){
	            $this->session->put_notice_msg('还没到预约发货时间，不能处理！');
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	            
	        } else {
	            $base_rules= array(
	                'distributor'=> array(
	                    'field' => 'distributor',
	                    'label' => $labels['distributor'],
	                    'rules' => 'trim|required',
	                ),
	                'tracking_no'=> array(
	                    'field' => 'tracking_no',
	                    'label' => $labels['tracking_no'],
	                    'rules' => 'trim|required',
	                ),
	            );
	            $this->form_validation->set_rules($base_rules);
	             
	            if ($this->form_validation->run() != FALSE) {
	                $post['post_admin']= $this->session->get_admin_username();
	                $post['remote_ip']= $this->input->ip_address();
	            // var_dump( $post );exit;
	                $result= $model->load($post[$pk])->post_shipping($post);

	                //发货成功，发送模版消息
				    if( $result ){
						/***********************发送模版消息****************************/
				    	//发送模版消息
				    	
				    	$this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
						$MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

				    	$inter_id= $this->session->get_admin_inter_id();
				    	$business = 'package';
						$model = $model->load( $post[$pk] );
						$openid = $model->m_get('openid');
			            $model->distributor = $post['distributor'];
			            $model->tracking_no = $post['tracking_no'];
			            $model->consumer_id = $post['consumer_id'];

			            $MessageWxtempTemplateModel->send_template_by_shipping_success( $model, $openid, $inter_id, $business);
						/***********************发送模版消息****************************/
					}
	                
	                $message= ($result)?
    	                $this->session->put_success_msg('已保存数据！'):
    	                $this->session->put_notice_msg('此次数据修改失败！');
	                $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	                 
	            } else
	                $model= $model->load($post[$pk]);
	        }
	    }
	    
	    //验证失败的情况
	    $validat_obj= _get_validation_object();
	    $message= $validat_obj->error_html();
	    //页面没有发生跳转时用寄存器存储消息
	    $this->session->put_error_msg($message, 'register');
	    
	    $fields_config= $model->get_field_config('form');
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}

	public function export_list()
	{
		// var_dump( $this->input->get() );die;
	    $this->load->model('soma/Consumer_shipping_model');
	    $start= $this->input->get('start');  //'2015-12-01'
	    $end= $this->input->get('end');     //'2015-12-01'
	    $status= $this->input->get('status');     //'2015-12-01'
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id == FULL_ACCESS ){
	        $inter_id= $this->current_inter_id;
	    }
	    
	    $filter= array();
	    if($status) $filter['status']= $status;
        $select= 'shipping_id,order_id,tracking_no,shipping_order,shipping_fee,distributor,name,consumer_id,qty,create_time,reserve_date,contacts,phone,address,status,remark,openid';

        //如果hotel_id不为空，添加hotel_id条件
        $ent_ids= $this->session->get_admin_hotels();
	    $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );
	    
	    $data= $this->Consumer_shipping_model->export_item( $inter_id, $filter, $select, $start, $end );
        //print_r($data);die;
	    $header= array('邮寄ID','订单号', '快递单号','邮费补差单号', '邮费补差费用','服务商','发货商品','sku','发货数量','邮寄申请时间','预约发货时间','收件人','收件电话','收件地址','状态','备注','购买人','联系电话');
	    $url= $this->_do_export($data, $header, 'csv', TRUE );
	}

	//批量发货
	public function batch()
	{
		$this->label_action= '邮寄信息处理';
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
	        'distributor_list'=>$model->get_distributor_select_option(),
	    );
	    
	    $html= $this->_render_content($this->_load_view_file('batch'), $view_params, TRUE);
	    //echo $html;die;
	    echo $html;
	}

	/*
	导出的格式
	array (size=14)
      0 => string '邮寄ID' (length=6)
      1 => string '订单号' (length=6)
      2 => string '快递单号' (length=8)
      3 => string '服务商' (length=6)
      4 => string '发货商品' (length=8)
      5 => string '发货数量' (length=8)
      6 => string '邮寄申请时间' (length=12)
      7 => string '收件人' (length=6)
      8 => string '收件电话' (length=8)
      9 => string '收件地址' (length=8)
      10 => string '状态' (length=4)
      11 => string '备注' (length=4)
      12 => string '购买人' (length=6)
      13 => string '联系电话' (length=8)
    */
	public function batch_post()
	{
		$distributor = $this->input->post('distributor');
		if( empty( $distributor ) ){
			$this->session->put_notice_msg('请选择快递商！');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/batch'));
		}

		if( isset( $_FILES['batch'] ) && $_FILES['batch']['error'] > 0 ){
            $this->session->put_notice_msg('上传的文件错误！');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/batch'));
		}

		//检查上传文件的类型
		$type = $_FILES['batch']['type'];
		$files_name = $_FILES['batch']['name'];
		if( strpos( $files_name, 'csv' ) === false ){
			$this->session->put_notice_msg('上传的文件只限csv格式！');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/batch'));
		}

		//组装上传的数据＝》array
		$obj = fopen($_FILES['batch']['tmp_name'], 'r');
		$batch_data = array (); 
	    $n = 0; 
	    while ($data = fgetcsv($obj)) { 
	        $num = count($data); 
	        for ($i = 0; $i < $num; $i++) { 
	            $batch_data[$n][$i] = $data[$i]; 
	        } 
	        $n++; 
	    }

	    //只取前三列
	    unset( $batch_data[0] );//第一行数据是中文描述头，第二行开始才是数据
	    $shippingIds = array();
		foreach ($batch_data as $k => $v) {
			$shippingIds[$v[0]] = isset( $v[2] ) ? htmlspecialchars( $v[2] ) : '';
		}
		if( count( $shippingIds ) == 0 ){
			$this->session->put_notice_msg('解析文件错误！');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/batch'));
		}

	    $this->label_action= '邮寄信息处理';
	    $this->_init_breadcrumb($this->label_action);
	    
	    $inter_id= $this->session->get_admin_inter_id();
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk = $model->table_primary_key();

		//查找适用数据
		$select_arr = array('shipping_id','order_id','consumer_id','openid','inter_id','address','distributor','tracking_no','reserve_date','status');
		$list = $model->get_apply_list_byIds( array_keys( $shippingIds ), $inter_id, $select_arr );
// var_dump( $list, $batch_data );die;
		$update_data = array();
		$update_data['distributor']= $distributor;
        $update_data['status']= $model::STATUS_SHIPPED;
        $update_data['post_admin']= $this->session->get_admin_username();
        $update_data['remote_ip']= $this->input->ip_address();

        $fail_data = $openids = array();
        $n = 0;
		foreach( $list as $k=>$v ){
			if( !empty( $v['address'] ) ){
				$update_data['tracking_no'] = $shippingIds[$v['shipping_id']];
				if( !empty( $update_data['tracking_no'] ) ){
					if( strpos( $update_data['tracking_no'], 'E+') !== false ){
						$fail_data[$k]['message'] = '请查看csv文件的快递单号，不能有E+符号！';
						$fail_data[$k][$pk] = $v[$pk];
					}else{
						//如果是预约的，要判断是否到了预约时间
						$time = date('Y-m-d H:i:s');
						if( !empty( $v['reserve_date'] ) && $time >= $v['reserve_date'] || empty( $v['reserve_date'] ) ){
							$update_data['post_time']= $time;
							//更新数据
							$result = $model->load($v[$pk])->m_sets( $update_data )->m_save();
							if( $result ){
								$n++;
								$openids[] = array(
												'openid'=>$v['openid'],
												'inter_id'=>$v['inter_id'],
												$pk=>$v[$pk],
												'tracking_no'=>$update_data['tracking_no'],
												'distributor'=>$distributor,
												'consumer_id'=>$v['consumer_id'],
											);
							}
						}else{
							$fail_data[$k]['message'] = '没有到预约时间，不能发货';
							$fail_data[$k][$pk] = $v[$pk];
						}
					}
				}else{
					$fail_data[$k]['message'] = '快递单不能为空';
					$fail_data[$k][$pk] = $v[$pk];
				}
			}else{
				$fail_data[$k]['message'] = '地址信息不能为空';
				$fail_data[$k][$pk] = $v[$pk];
			}
		}

		//发送模版消息
		if( count( $openids ) > 0 ){
			$this->load->model('soma/Message_wxtemp_template_model');
			$business = 'package';
			foreach ($openids as $k=>$v) {
				$model = $model->load( $v[$pk] );
				if( $model ){
					$model->consumer_id = $v['consumer_id'];
					$model->distributor = $v['distributor'];
					$model->tracking_no = $v['tracking_no'];
					$this->Message_wxtemp_template_model->send_template_by_shipping_success( $model, $v['openid'], $v['inter_id'], $business);
				}
			}
		}
		
		if( count( $fail_data ) == 0 ){
		    $this->session->put_success_msg('批量发货成功！');
		    $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		}
		// var_dump( $fail_data);
		$fields_config= $model->get_field_config('form');
		$view_params= array(
		    'model'=> $model,
		    'fail_data'=> $fail_data,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'current_inter_id'=> $this->current_inter_id,
		    'distributor_list'=>$model->get_distributor_select_option(),
		);

		$this->session->put_notice_msg('部分发货失败，请查看下面的［邮寄发货失败列表］' );
		$html= $this->_render_content($this->_load_view_file('batch'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	public function remark()
    {
        $return= array('status'=>Soma_base::STATUS_FALSE, 'message'=>'参数错误。');
    
        $item_id= $this->input->get('id');
        $inter_id= $this->input->post('inter_id');
        $remark= $this->input->post('remark');
    
        if( !$item_id || !$inter_id ){
            $return['status']= Soma_base::STATUS_FALSE;
            
        } else {
            $this->load->model('soma/Consumer_shipping_model');
            $result= $this->Consumer_shipping_model->change_remark($inter_id, $item_id, $remark);
            if($result){
                $return['status']= Soma_base::STATUS_TRUE;
            }
        }
        echo json_encode($return);
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
	 * 修改发货信息
	 * 
	 * 目前只可以修改地址信息，可以保存地址备注信息
	 * @return [type]
	 */
	public function modify_mail_info() {
		
		$this->label_action= '邮寄信息处理';
	    $this->_init_breadcrumb($this->label_action);

	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	    
	    $this->load->library('form_validation');
	    $post= $this->input->post(null, true);
	    
	    $labels= $model->attribute_labels();
	    if( isset($post[$pk]) ) $model= $model->load( $post[$pk] );
	    
	    if( empty($post[$pk]) || !$this->_can_edit($model) ){
	        // 无数据或者越权修改数据
	        $this->session->put_notice_msg('找不到该数据记录！');
	        $url = Soma_const_url::inst()->get_url('*/*/grid');
	        echo json_encode(array('success' => false, 'url' => $url));
	        exit;
	    }

	    $data = $post;
	    unset($data['remark']);
	    if( $model->m_get('status') != $model::STATUS_APPLY
	    	&& count($data) >0 ) {
	    	$this->session->put_notice_msg('不允许修改除地址备注信息外的其他信息！');
	    	$url = Soma_const_url::inst()->get_url('*/*/edit', array('ids' => $post[$pk]));
            echo json_encode(array('success' => false, 'url' => $url));
            exit;
	    }

		try {
			$model->m_sets($post)->m_save();
			$this->session->put_success_msg('操作成功！');
			$url = Soma_const_url::inst()->get_url('*/*/edit', array('ids' => $post[$pk]));
            echo json_encode(array('success' => true, 'url' => $url));
            exit;
		} catch (Exception $e) {
			$this->session->put_error_msg('操作失败，请稍后再重新尝试！');
            $url = Soma_const_url::inst()->get_url('*/*/edit', array('ids' => $post[$pk]));
            echo json_encode(array('success' => false, 'url' => $url));
            exit;
		}

	}

}
