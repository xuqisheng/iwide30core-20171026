<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_package extends MY_Admin_Soma {

	protected $label_module= NAV_PACKAGE_GROUPON;		//统一在 constants.php 定义
	protected $label_controller= '商品管理';		//在文件定义
	protected $label_action= '';				//在方法中定义

	protected $update_order_attr = false;		// 仅在editattribute()中改变该值
	
	protected function main_model_name()
	{
		return 'soma/product_package_model';
	}

	public function grid()
	{
        $this->_render_content($this->_load_view_file('grid'), [], false);
	}

	public function edit()
	{
		$this->label_action= '商品管理';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);

		// $detail_field= '';
		$id = $this->input->get('ids');
		if($id !== null)
		{
			$id = intval($id);
		}
		$model= $model->load($id);
        if(!$model) $model= $this->_load_model();
		$fields_config= $model->get_field_config('form');

		if($this->update_order_attr) {
			$update_attr = $model->can_edit_attribute();
			$tmp_config = array();
			foreach ($update_attr as $key) {
				$tmp_config[$key] = $fields_config[$key];
			}
			$fields_config = $tmp_config;
		}

		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		}
		
		//反序列化compose，输出到详情
		$compose = array();
		$compose = $model->unserialize_compose();

		$compose_en = array();
		$compose_en = $model->unserialize_compose_en();

		//获取相册数组
		$gallery= $model->get_gallery();

        //获取运费差价商品列表
        $inter_id = $this->session->get_admin_inter_id();
        $shipping_product_list = $model->get_shipping_product_list($inter_id);

        // 订房套餐相关
        $from = $this->input->get('from', true);
        $succ_url = $this->input->get('succ_url', true);
        $succ_url = ($succ_url == null) ? null : urldecode($succ_url);

		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'gallery'=> $gallery,
		    'compose'=>$compose,
		    'compose_en'=>$compose_en,
		    'update_order_attr'=>$this->update_order_attr,
		    'shipping_product_list' => $shipping_product_list,
		    'combine_products' => json_encode($model->getCombineChildProductInfo()),
            'from' => $from,
            'succ_url' => $succ_url,
		);

		$view_params = $this->spec_view_params( $model, $inter_id, $view_params );

		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	protected function spec_view_params( $model, $inter_id, $view_params )
	{
		$ent_ids= $this->session->get_admin_hotels();
		$hotel_address = '';
        $hotel_tel = '';
	    $hotelIds = array();
		$this->load->model( 'hotel/hotel_model' );
		$hotels = $this->hotel_model->get_all_hotels($inter_id);
		if( $hotels ){
			$ent_ids = '';
			foreach ($hotels as $k => $v) {
				$ent_ids .= $v['hotel_id'].',';
			}
			$ent_ids = trim( $ent_ids, ',' );
		}
		// var_dump( $hotels );
        // $hotel_info = $this->hotel_model->get_hotel_detail( $inter_id, $hotel_ids[0] );
        $hotel_infos = $this->hotel_model->get_hotel_by_ids( $inter_id, $ent_ids );
        // var_dump( $hotel_infos );die;
        if( $hotel_infos ){
        	foreach ($hotel_infos as $k => $v) {
        		if( $k == 0 ){
        			$hotel_address = $v['province'].$v['city'].$v['address'];
        			$hotel_tel = $v['tel'];
        		}
        		$data = array();
	        	$data['hotel_address'] = $v['province'].$v['city'].$v['address'];
	            $data['hotel_tel'] = isset( $v['tel'] ) && !empty( $v['tel'] ) 
	            						? $v['tel'] 
	            						: '';
				$data['name'] = $v['name'];
				$data['hotel_id'] = $v['hotel_id'];
        		$hotelIds[$v['hotel_id']] = $data;
        	}
        }
        
        if( !$product_id = $model->m_get('product_id') ){

	    }else{
	    	$hotel_address = $model->m_get('hotel_address');
			$hotel_tel = $model->m_get('hotel_tel');
	    }
	    $view_params['hotel_tel'] = $hotel_tel;
	    $view_params['hotel_address'] = $hotel_address;
	    $view_params['hotelIds'] = $hotelIds;

		/****************新改版添加start*****************/
		//查找出公众号名
        $this->load->model( 'wx/Publics_model' );
        $publics = $this->Publics_model->get_public_by_id($inter_id);
        $interIds = array();
        if( $publics ){
          $interIds[$inter_id] = $publics['name'];
        }else{
          $interIds[$inter_id] = '';
        }
        $view_params['interIds'] = $interIds;
        $view_params['inter_id'] = $inter_id;
// var_dump($interIds );
        //获取产品类型
        $product_type = $model->get_product_type_label();
        $view_params['product_type'] = $product_type;

        //获取商品类型
        $goods_type = $model->get_goods_type_label();
        $view_params['goods_type'] = $goods_type;

        //取出分类
        $this->load->model('soma/category_package_model');
	    $cate_list = $this->category_package_model->get_package_category_list($inter_id);
        $view_params['cate_list'] = $cate_list;
        // var_dump( $cate_list );die;

        //礼包ID列表
	    /** 会员礼包拉取 **/
        $this->load->library('Soma/Api_member');
        $api= new Api_member( $inter_id );
        $result= $api->get_token();
        $api->set_token($result['data']);
        $giftAll= $api->get_package_list();
        $packages = array();
        $data = (array)$giftAll['data'];
        if( $data ){
	        foreach( $data as $k=>$v ){
	            $packages[$v->package_id] = $v->name;
	        }
	        $view_params['packages'] = $packages;
        	// var_dump( $view_params['packages'] );die;
	    }

        //失效模式
        $date_type = $model->get_date_type();
        $view_params['date_type'] = $date_type;

        $status_type = $model->get_status_label();
        $view_params['status_type'] = $status_type;

        $post_url = Soma_const_url::inst()->get_url('*/*/edit_post',array('inter_id'=>$inter_id));
        $view_params['post_url'] = $post_url;

        $pk= $model->table_primary_key();
        $view_params['pk'] = $pk;

        //取出商品规格
        $setting_list = array();
        $spec_list = '';
        $date_set = '';
        $auto_increment_id = 0;

        $this->load->model('soma/Product_specification_model','ProductSpecModel');
        $ProductSpecModel = $this->ProductSpecModel;

        $this->load->model('soma/Product_specification_setting_model','ProductSettingModel');
        $ProductSettingModel = $this->ProductSettingModel;

        if( $product_id ){
	        $setting_list = $ProductSettingModel->get_specification_compose( $inter_id, $product_id );
	        // var_dump( $setting_list );

	        $specs = $ProductSpecModel->get_spec_list( $inter_id, $product_id );
//var_dump( $specs );die;
	        $spec_data = array();
	        if( $specs )
	        {
	            foreach ($specs as $spec)
                {
                    $spec_compose = isset( $spec['spec_compose'] ) ? $spec['spec_compose'] : '';
                    $specList = json_decode( $spec_compose, true );
                    $spec_data = $specList['data'];
                    if( $setting_list && $spec_data ){
                        foreach ($setting_list as $k => $v) {
                            if( isset( $spec_data[$k] ) && !empty( $spec_data[$k] ) && $spec['type'] == $v['type'] )
                            {
                                $spec_data[$k]['admin_setting_id'] = $v['setting_id'];
                                $spec_data[$k]['specprice'] = $v['specprice'];
                                $spec_data[$k]['stock'] = $v['spec_stock'];
                            }
                        }
                        $specList['data'] = $spec_data;
                        $specList = json_encode( $specList );
                    }else{
                        $specList = $spec['spec_compose'];
                    }

                    if( $spec['type'] == $model::SPEC_TYPE_SCOPE )
                    {
                        $spec_list = $specList;
                    } elseif( $spec['type'] == $model::SPEC_TYPE_TICKET && $spec_data ) {
                        $date_set = $specList;
                    }
                }
	        }

	        // var_dump( $spec_list );die;
		    
	    }
        // var_dump( json_encode( $setting_list ) );die;
        // $view_params['setting_list'] = json_encode( $setting_list );
        $view_params['spec_list'] = $spec_list;
        $view_params['dateset'] = $date_set;
//echo $date_set;die;
	    $auto_increment_id = $ProductSettingModel->get_package_auto_increment_id();
	    // echo $auto_increment_id;die;
		$view_params['auto_increment_id'] = $auto_increment_id;

		$conn_devices = $model->get_conn_devices();
		$view_params['conn_devices'] = $conn_devices;

        //20170531 luguihong 获取订房价格代码配置已经移到 ajax_get_booking_config 方法处理
        $wx_booking_config = $rooms = array();

        $wx_booking_config = $model->m_get('wx_booking_config');
        if( $wx_booking_config )
        {
            $wx_booking_config  = json_decode( $wx_booking_config, true );
            $wx_booking_config     = $wx_booking_config['select_ids'];
        }

        $interArr = array(
            'a492669988',
            'a491796658',
        );
        if( !in_array($inter_id,$interArr) )
        {
            $rooms = $this->get_rooms();
        }
        /**
            //获取已保存的房型数据
            $wx_booking_config = $model->m_get('wx_booking_config');
            if( $wx_booking_config ){
            $wx_booking_config = json_decode( $wx_booking_config, true );
            $wx_booking_config = $wx_booking_config['select_ids'];
            }
            //下面的公众号不拉取房型
            $interArr = array(
            'a492669988',
            'a491796658',
            );
            if( !in_array($inter_id,$interArr) )
            {
            $rooms = $this->get_rooms();
            } else {
            $rooms = array();
            }
         */
        $view_params['wx_booking_config'] = $wx_booking_config;
        $view_params['rooms'] = $rooms;

		/****************新改版添加end*******************/
		return $view_params;
	}

	public function edit_post()
	{
		// die('sss');
	    $this->label_action= '产品修改';
	    $this->_init_breadcrumb($this->label_action);

	    //cat_id从ticket_center->get_id_category('package')获取
	    $this->load->model('soma/ticket_center_model','ticket_center');
	    $productId = $this->ticket_center->get_id_product('package');
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();

        // 默认不可预约邮寄
        $post['is_hide_reserve_date'] = 2;

	    // var_dump( $post );die;
// var_dump( explode( '","', trim( trim( $post['gallery'], '[""'), '""]') ) );die;
	    //不同的产品类型不能相互转换
	    if( isset( $post[$pk] ) && !empty( $post[$pk] ) ){
	    	$model = $model->load( $post[$pk] );
            if( !$model ){
                $this->session->put_notice_msg('检查产品类型出错！');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
            }
	    	if( $model && !empty($post['type']) && $model->m_get( 'type' ) != $post['type'] ){
	    		$this->session->put_notice_msg('此次数据修改失败！不能转换产品类型');
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	    	} else {
                $post['type'] = $model->m_get('type');
            }
            if( $model && !empty($post['goods_type']) && $model->m_get( 'goods_type' ) != $post['goods_type'] ){
                $this->session->put_notice_msg('此次数据修改失败！不能转换商品类型');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
            } else {
                $post['goods_type'] = $model->m_get('goods_type');
            }
	    }

	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'name'=> array(
	            'field' => 'name',
	            'label' => $labels['name'],
	            'rules' => 'trim|required',
	        ),
	        // 'price_package'=> array(
	        //     'field' => 'price_package',
	        //     'label' => $labels['price_package'],
	        //     'rules' => 'trim|required',
	        // ),
	        'stock'=> array(
	            'field' => 'stock',
	            'label' => $labels['stock'],
	            'rules' => 'trim|required',
	        ),
	    );

	    //酒店地址详情处理
	    $inter_id = $this->session->get_admin_inter_id();
		$post['inter_id'] = $inter_id;

        // 酒店多选
        // if (is_array($post['hotel_id']) && count($post['hotel_id']) >= 0) {
        //     $post['hotel_ids_str'] = implode(',', $post['hotel_id']);
        //     if (count($post['hotel_id']) == 1) {
        //         $post['hotel_id'] = $post['hotel_id'][0];
        //     } else {
        //         $post['hotel_id'] = -1;
        //     }
        // } else {
        //     $post['hotel_ids_str'] = $post['hotel_id'];
        // }
        if (isset($post['hotel_id'])) {
            $post['hotel_ids_str'] = $post['hotel_id'];
            $post_hotels = explode(',', $post['hotel_id']);
            if (count($post_hotels) > 1) {
                $post['hotel_id'] = \App\models\soma\SeparateBilling::MULITPLE_HOTEL_ID;
            }
            if (count($post_hotels) == 1) {
                $post['hotel_id'] = $post_hotels[0];
            }
        }

        $hotel_id = isset( $post['hotel_id'] ) ? $post['hotel_id'] + 0 : '';
	    
	    //检测并上传文件。
	    if( isset( $post['goods_img'] ) && !empty( $post['goods_img'] ) ){
	    	$post['face_img'] = $post['goods_img'];
	    }else{
		    $post= $this->_do_upload($post, 'face_img');
		    $post= $this->_do_upload($post, 'transparent_img');
		}

	    //处理套票内容和数量,序列化存在compose
	    $compose = array();
	    $compose = isset( $post['compose'] ) ? $post['compose'] : '';
	    if( !empty( $compose ) ){
		    foreach ($compose as $k => $v) {
		    	$num = isset( $v['num'] ) ? $v['num'] + 0 : 0;
		    	if( $num < 0 ){ 
		    		$compose[$k]['num'] = 0;
		    	}else{ 
		    		$compose[$k]['num'] = $num;
		    	}
		    }
		}
	    $post['compose'] = serialize( $compose );

	    //处理套票内容和数量,序列化存在compose_en
	    $compose_en = array();
	    $compose_en = isset( $post['compose_en'] ) ? $post['compose_en'] : '';
	    if( !empty( $compose_en ) ){
		    foreach ($compose_en as $k => $v) {
		    	$num = isset( $v['num'] ) ? $v['num'] + 0 : 0;
		    	if( $num < 0 ){ 
		    		$compose_en[$k]['num'] = 0;
		    	}else{ 
		    		$compose_en[$k]['num'] = $num;
		    	}
		    }
		}
	    $post['compose_en'] = serialize( $compose_en );

	    //在售数量
	    $stock = isset( $post['stock'] ) ? $post['stock'] + 0 : 0;
	    if( $stock < 0 ){ 
	    	$post['stock'] = 0;
	    }else{ 
	    	$post['stock'] = $stock;
	    }

	    //门市价
	    $price_market = isset( $post['price_market'] ) ? $post['price_market'] + 0 : 0;
	    if( $price_market <= 0 ){ 
	    	$post['price_market'] = 0.01;
	    }else{ 
	    	$post['price_market'] = $price_market;
	    }

	    //组合价
		$price_package = isset( $post['price_package'] ) ? $post['price_package'] + 0 : 0;
		if( $price_package <= 0 ){ 
	    	$post['price_package'] = 0.01;
		}else{ 
			$post['price_package'] = $price_package;
		}

		//邮费补差单位不能少于0
		$shipping_fee_unit = isset( $post['shipping_fee_unit'] ) ? $post['shipping_fee_unit'] + 0 : 1;
		if( $shipping_fee_unit <= 0 ){ 
	    	$post['shipping_fee_unit'] = 1;
		}else{ 
			$post['shipping_fee_unit'] = $shipping_fee_unit;
		}

		//分时使用不能小于0
		$use_cnt = isset( $post['use_cnt'] ) ? $post['use_cnt'] + 0 : 1;
		if( $use_cnt <= 0 ){ 
	    	$post['use_cnt'] = 1;
		}else{ 
			$post['use_cnt'] = $use_cnt;
		}

        $this->load->model( 'hotel/hotel_model' );
        $hotel_info = $this->hotel_model->get_hotel_detail( $inter_id, $hotel_id );
        if( $hotel_info ){
        	$post['hotel_address'] = isset( $post['hotel_address'] ) && !empty( $post['hotel_address'] ) 
            						? $post['hotel_address'] 
            						: ($hotel_info['province'].$hotel_info['city'].$hotel_info['address']);
        	$post['hotel_name'] = $hotel_info['name'];
            $post['latitude'] = $hotel_info['latitude'];
            $post['longitude'] = $hotel_info['longitude'];
            $post['product_city'] = $hotel_info['city'];
            $post['hotel_tel'] = isset( $post['hotel_tel'] ) && !empty( $post['hotel_tel'] ) 
            						? $post['hotel_tel'] 
            						: $hotel_info['tel'];
        }

        if( isset( $post['can_reserve'] ) && $post['can_reserve'] == Soma_base::STATUS_TRUE ){
        	//如果可以预约的，一定要填写电话
        	if( !isset( $post['hotel_tel'] ) || !$post['hotel_tel'] ){
        		$base_rules['hotel_tel'] = array(
								            'field' => 'hotel_tel',
								            'label' => $labels['hotel_tel'],
								            'rules' => 'trim|required',
								        );
        	}
        }

        //如果是特权券，赠送好友必须开着
        if( isset( $post['type'] ) && $post['type'] == Soma_base::STATUS_FALSE ){
        	// $post['can_gift'] = 1;
        	$post['can_pickup'] = 2;//不能自提
        	$post['can_mail'] = 2;//不能邮寄
        	$post['can_reserve'] = 2;//不能预约
        }

        //对接智游宝，关闭一些操作按钮
        if(
//            isset( $post['goods_type'] ) && $post['goods_type'] == $model::SPEC_TYPE_TICKET &&
            isset( $post['conn_devices'] ) && $post['conn_devices'] == $model::DEVICE_ZHIYOUBAO
        ) {
            $post['can_gift'] = 2;//不能赠送
            $post['can_mail'] = 2;//不能邮寄
            $post['can_wx_booking'] = 2;//不能微信订房
            $post['can_reserve'] = 2;//不能预约
            $post['can_split_use'] = 2;//不能拆分
        }

        /*
	    	$hotelIds ＝ array(1) {
				  [180]=&gt;
				  string(3) "180"
				}
			$roomIds ＝ array(1) {
				  [180]=&gt;
				  array(1) {
				    [62]=&gt;
				    string(2) "62"
				  }
				}
			$codeIds ＝ array(1) {
						  [180]=&gt;
						  array(1) {
						    [62]=&gt;
						    array(3) {
						      [1]=&gt;
						      string(1) "1"
						      [2]=&gt;
						      string(1) "2"
						      [3]=&gt;
						      string(1) "3"
						    }
						  }
						}
        */
		//选中的酒店列表
        $hotelIds = isset( $post['hotel_ids'] ) ?$post['hotel_ids'] : array();
        //选中的房型列表
        $roomIds = isset( $post['room_ids'] ) ?$post['room_ids'] : array();
        if( $roomIds ){
        	//只要选择了房型，那么就替代之前的值，不存在则保存
        	foreach( $roomIds as $k=>$v ){
				$hotelIds[$k] = $v;
        	}
        }
        //选中的价格列表
        $codeIds = isset( $post['code_ids'] ) ?$post['code_ids'] : array();
        // var_dump( $codeIds );die;
        if( $codeIds ){
        	foreach( $codeIds as $k=>$v ){
        		foreach( $v as $sk=>$sv ){
        			//只要选择了价格，那么就替代之前的值，不存在则保存
    				$hotelIds[$k][$sk] = $sv;
    			}
        	}
        }
        //获取要存储的酒店信息，房型信息，价格信息
		$hotels = $this->_get_hotels( $inter_id, array_keys( $hotelIds ) );
		$hotels = $this->_get_rooms( $inter_id, $hotels, $hotelIds );
		$hotels = $this->_get_codes( $inter_id, $hotels, $hotelIds );
		$hotels['select_ids'] = $hotelIds;
		$post['wx_booking_config'] = json_encode( $hotels );
		// var_dump( $hotels, $hotelIds, $post['wx_booking_config'] );die;

        //规格信息
        $spec_save_sign = FALSE;
        $specList = array(
            'spec_data'                 => array(),
            'spec_setting_data'         => array(),
            'update_spec_setting_data'  => array(),
            'delete_spec_setting_data'  => array(),
        );
        //普通规格
		if( isset( $post['spec_list'] ) )
		{
	    	$spec_list                              = json_decode( $post['spec_list'], true );
	    	$returnList                             = $this->_get_spec_list( $model, $inter_id, $spec_list, $post, $productId, $model::SPEC_TYPE_SCOPE, $spec_save_sign );
            $post                                   = $returnList['post'];
            $spec_save_sign                         = $returnList['spec_save_sign'];
            $specList['spec_data']                  += $returnList['spec_data'];
            $specList['spec_setting_data']          = array_merge( $specList['spec_setting_data'], $returnList['spec_setting_data'] );
            $specList['update_spec_setting_data']   += $returnList['update_spec_setting_data'];
            $specList['delete_spec_setting_data']   = array_merge( $specList['delete_spec_setting_data'], $returnList['delete_spec_setting_data'] );
		}
//var_dump( $specList);die;
		//门票规格
        if( isset( $post['dateset'] ) )
        {
            $dateSet                                = json_decode( $post['dateset'], true );
            $returnList                             = $this->_get_spec_list( $model, $inter_id, $dateSet, $post, $productId, $model::SPEC_TYPE_TICKET, $spec_save_sign );
            $post                                   = $returnList['post'];
            $spec_save_sign                         = $returnList['spec_save_sign'];
            $specList['spec_data']                  += $returnList['spec_data'];
            $specList['spec_setting_data']          = array_merge( $specList['spec_setting_data'], $returnList['spec_setting_data'] );
            $specList['update_spec_setting_data']   += $returnList['update_spec_setting_data'];
            $specList['delete_spec_setting_data']   = array_merge( $specList['delete_spec_setting_data'], $returnList['delete_spec_setting_data'] );
        }
//var_dump( $specList );die;

        // 组合商品
        $check_combine = true;
        if($post['goods_type'] == product_package_model::SPEC_TYPE_COMBINE)
        {
        	$combine_data = json_decode($post['combine_products'], true);
        	$combine_cpids = $combine_cspecids = array();
        	foreach($combine_data as $index => $row)
        	{
        		if($row['child_pid'] != -1)
        		{
        			$combine_cpids[] = array('product_id' => $row['child_pid']);
        			if($row['spec_id'] != -1)
        			{
        				$combine_cspecids[] = array(
        					'product_id' => $row['child_pid'],
        					'setting_id' => $row['spec_id'],
        				);
        			}
        		}
        		if($row['num'] <= 0)
        		{
        			$check_combine = false;
        			break;
        		}
				$combine_data[$index]['parent_pid'] = !empty($post[$pk]) ? $post[$pk] : $productId;
				$combine_data[$index]['inter_id']   = $post['inter_id'];
				$combine_data[$index]['hotel_id']   = $post['hotel_id'];
				$combine_data[$index]['created_at']  = $combine_data[$index]['updated_at'] = date('Y-m-d H:i:s');
        	}
        	if($check_combine
        		&& $model->checkCombineProducts($combine_cpids, $combine_cspecids))
        	{
        		$check_combine = true;
        		$model->combine_data = $combine_data;
        	}
        	else
        	{
        		$check_combine = false;
        	}
        }

        // 升级房券默认属性
        if ($post['goods_type'] == product_package_model::SPEC_TYPE_ROOM) {
            // 不支持微信订房
            $post['can_wx_booking'] = $model::CAN_F;
            // 不支持拆分使用
            $post['can_split_use']  = $model::CAN_F;
            // 不支持邮寄
            $post['can_mail']       = $model::CAN_F;
            // 不支持转赠
            $post['can_gift']       = $model::CAN_F;
            // 不支持退款
            $post['can_refund']     = $model::CAN_REFUND_STATUS_FAIL;
            // 不支持提前预约
            $post['can_reserve']    = $model::CAN_F;
        }

        $model->spec_save_sign              = $spec_save_sign;
        $model->spec_data                   = $specList['spec_data'];
        $model->spec_setting_data           = $specList['spec_setting_data'];
        $model->update_spec_setting_data    = $specList['update_spec_setting_data'];
        $model->delete_spec_setting_data    = $specList['delete_spec_setting_data'];

        $business = 'package';
        if($check_combine)
        {
		    if( empty($post[$pk]) ){
		        //add data.	

		        $post[$pk] = $productId;//添加的自定义主键值	

		        $this->form_validation->set_rules($base_rules);
		        if ($this->form_validation->run() != FALSE) {
	        		$model->post = $post;
		            $result = $model->product_package_save( $business, $inter_id );
		            $message= ($result)?
			            $this->session->put_success_msg('保存数据成功！'):
			            $this->session->put_notice_msg('此次数据保存失败！');
						$this->_log($model);
					//不是自增字段
					if( $result ){
						$result = $productId;
					}
                    
                    if(!empty($post['succ_url']))
                    {
                        $this->_redirect($post['succ_url']);
                    }
                    else
                    {
		              $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
                    }

		        } else
		            $model= $this->_load_model();
		
		    } else {
		    	$this->form_validation->set_rules($base_rules);
		        if ($this->form_validation->run() != FALSE) {
		        	$model->post = $post;
		            $result= $model->product_package_update( $business, $inter_id );
		            $message= ($result)?
	    	            $this->session->put_success_msg('已保存数据！'):
	    	            $this->session->put_notice_msg('此次数据修改失败！');
					$this->_log($model);
                    
                    if(!empty($post['succ_url']))
                    {
                        $this->_redirect($post['succ_url']);
                    }
                    else
                    {
                      $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
                    }
		
		        } else
		            $model= $model->load($post[$pk]);
		    }
		}
		else
		{
			$this->session->put_notice_msg('此次数据保存失败,组合商品信息有误！');
		}

	    //验证失败的情况
	    $validat_obj= _get_validation_object();
	    $message= $validat_obj->error_html();
	    //页面没有发生跳转时用寄存器存储消息
	    $this->session->put_error_msg($message, 'register');
	    
	    //获取相册数组
	    $gallery= $model->get_gallery();
	    
	    $fields_config= $model->get_field_config('form');

	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	        'gallery'=> $gallery,
		    'compose'=> $compose,
		    'update_order_attr'=>$this->update_order_attr,
	    );

	    $view_params = $this->spec_view_params( $model, $inter_id, $view_params );
	    
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}

	protected function _get_spec_list( $model, $inter_id, $spec_list, $post, $productId, $type, $spec_save_sign )
    {

        $is_jf = FALSE;
        if( $post['type'] == $model::PRODUCT_TYPE_POINT )
        {
            $is_jf = TRUE;
            $post['price_package'] = ceil( $post['price_package'] );
        }

        $spec_hotel_id = isset( $post['hotel_id'] ) && !empty( $post['hotel_id'] ) ? $post['hotel_id'] : 0;
        $spec_product_id = isset( $post['product_id'] ) && !empty( $post['product_id'] ) ? $post['product_id'] : $productId;

        //是否已经存在规格
        $this->load->model('soma/Product_specification_setting_model','ProductSettingModel');
        $ProductSettingModel = $this->ProductSettingModel;
        $setting_list = $ProductSettingModel->get_specification_setting( $inter_id, $spec_product_id, null, $type );

        $setting_ids = array();
        if( $setting_list )
        {
            foreach ($setting_list as $k => $v)
            {
                $setting_ids[$v['setting_id']] = $v;
            }
        }

        //组装规格信息
        $spec_data = array();
        $spec_setting_data = $update_spec_setting_data = $delete_spec_setting_data = array();
        if( count( $spec_list ) > 0 )
        {

            $spec_save_sign = TRUE;

            $spec_setting = $spec_list['data'];
            if( count( $spec_setting ) > 0 )
            {

                //组装表spec_setting信息
                foreach( $spec_setting as $k=>$v )
                {
                    $setting_spec_compose = json_encode( array($k=>$v) );

                    $spec_price = $v['specprice'] ? $v['specprice'] + 0 : $post['price_package'];/*规格价格*/
                    if( $spec_price <= 0 )
                    {
                        $spec_price = 0.01;
                    }

                    if( $is_jf )
                    {
                        //积分向上取整
                        $spec_price = ceil( $spec_price );/*规格价格*/
                    }

                    $sku = isset( $v['sku'] ) ? $v['sku'] : '';
                    if(
                        isset( $v['admin_setting_id'] ) && !empty( $v['admin_setting_id'] )
                        && isset( $setting_ids[$v['admin_setting_id']] ) && !empty( $setting_ids[$v['admin_setting_id']] )
                    )
                    {
                        if( $setting_spec_compose == $setting_ids[$v['admin_setting_id']]['setting_spec_compose'] )
                        {

                        } else {
                            $array = array();
                            $v['specprice']                     = $spec_price;
                            $spec_setting[$k]['specprice']      = $spec_price;
                            $array['setting_spec_compose']      = json_encode( array($k=>$v) );
                            $array['inter_id']                  = $inter_id;/*规格价格*/
                            $array['hotel_id']                  = $spec_hotel_id;/*规格价格*/
                            $array['spec_price']                = $spec_price;
                            $array['spec_stock']                = $v['stock'] ? $v['stock'] : 0;/*规格价格*/
                            $array['outter_sku']                = $sku;/*规格价格*/
                            $update_spec_setting_data[$v['admin_setting_id']] = $array;
                        }

                        //规格删除操作
                        unset( $setting_ids[$v['admin_setting_id']] );

                    } else {
                        //规格添加操作
                        $array = array();
                        $v['specprice'] = $spec_price;
                        $spec_setting[$k]['specprice']   = $spec_price;
                        $array['setting_spec_compose']   = json_encode( array($k=>$v) );
                        $array['inter_id']               = $inter_id;
                        $array['hotel_id']               = $spec_hotel_id;
                        $array['type']                   = $type;
                        $array['product_id']             = $spec_product_id;
                        $array['spec_price']             = $spec_price;/*规格价格*/
                        $array['spec_stock']             = $v['stock'];/*规格价格*/
                        $array['outter_sku']             = $sku;/*规格价格*/
                        $spec_setting_data[]             = $array;
                    }
                }

                $spec_list['data'] = $spec_setting;

                $post['spec_type'] = $type;
                $post['spec_list'] = json_encode( $spec_list );

            }

            //组装表spec信息
            $specData['inter_id']      = $inter_id;
            $specData['hotel_id']      = $spec_hotel_id;
            $specData['type']          = $type;
            $specData['product_id']    = $spec_product_id;
            $specData['spec_compose']  = json_encode( $spec_list );
            $spec_data[$type] = $specData;

        } else {
            //如果传过来是空的，要把spec处理掉
            $specData['inter_id']      = $inter_id;
            $specData['hotel_id']      = $spec_hotel_id;
            $specData['type']          = $type;
            $specData['product_id']    = $spec_product_id;
            $specData['spec_compose']  = '';
            $spec_data[$type] = $specData;
        }

        $data = array(
            'post'=>$post,
            'spec_data'=>$spec_data,
            'spec_save_sign'=>$spec_save_sign,
            'spec_setting_data'=>$spec_setting_data,
            'update_spec_setting_data'=>$update_spec_setting_data,
            'delete_spec_setting_data'=>$setting_ids,
        );
        return $data;
    }

	public function edit_focus()
	{
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	    $post= $this->input->post();
	    $get = $this->input->get();

	    $id = isset( $post[$pk] ) ? $post[$pk] : 0;
	    $url = Soma_const_url::inst()->get_url('*/*/edit', array('ids'=> $id) );
	    if(isset( $post['del_gallery'] ) ){
	        $model->delete_gallery($post['del_gallery'], $post[$pk]);
	    }
	    if( isset( $get['del_gallery'] ) && !empty( $get['del_gallery'] ) ){
	    	$model->delete_gallery($get['del_gallery'], $get[$pk]);
	    	$url = Soma_const_url::inst()->get_url('*/*/edit', array('ids'=> $get[$pk]) );
	    }

	    //检测并上传新的文件。
	    if( isset( $post['gallery'] ) && !empty( $post['gallery'] ) && strstr( $post['gallery'], 'http') ){
	    	// var_dump( $post['gallery'] );
	    }else{
	    	$post= $this->_do_upload($post, 'gallery');
	    }

	    if( isset($post['gallery']) && !empty( $post['gallery'] ) ){
	        $data= array(
	            'gry_url'=> $post['gallery'],
	            'gry_intro'=> isset( $post['gry_intro'] ) ? $post['gry_intro'] : '',
	            'product_id'=> $post['product_id'],
	        );
	        $model->plus_gallery($data);
	    }
	    $this->session->put_success_msg('成功保存产品相册，请继续编辑产品信息');
	    $this->_redirect($url);
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
	 * 仅限开发使用
	 * @return [type] [description]
	 */
	public function upattribute()
	{
		$this->_toolkit_writelist();
	    $this->view_file= 'upattribute';
	    $this->grid();
	}

	/**
	 * 仅限开发使用
	 * @return [type] [description]
	 */
	public function editattribute() {
		$this->_toolkit_writelist();
		$this->update_order_attr = true;
		$this->edit();
	}

	/**
	 * 仅限开发使用
	 * @return [type] [description]
	 */
	public function update_post() {

		$this->_toolkit_writelist();

		$post = $this->input->post();

		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$id= intval($post['product_id']);

		if($model->load($id)->update_related_table($post)) {
			$this->session->put_success_msg('已更新订单/资产/消费数据！');
			$this->_redirect(Soma_const_url::inst()->get_url('*/*/upattribute'));
		} else {
			$this->session->put_error_msg('更新数据失败，请重新尝试！');
			$this->_redirect(Soma_const_url::inst()->get_url('*/*/editattribute'));
		}

	}

	public function _get_hotels( $inter_id, $hotelIds=array() )
	{
		//获取酒店列表
		$this->load->model ( 'hotel/Hotel_model' );
		if( count( $hotelIds ) == 0 ){
			$hotels = $this->Hotel_model->get_all_hotels( $inter_id );
		}else{
			$hotels = $this->Hotel_model->get_hotel_by_ids( $inter_id, implode( ',', $hotelIds ) );
		}

		$hotels_new = array();
		if( $hotels ){
			foreach( $hotels as $v ){
				//删除一些不需要的字段
				$data = array();
				$data['hotel_id'] = $v['hotel_id'];
				$data['inter_id'] = $v['inter_id'];
				$data['name'] = $v['name'];
				$data['address'] = $v['province'].$v['city'].$v['address'];
				$data['latitude'] = $v['latitude'];
				$data['longitude'] = $v['longitude'];
				$data['tel'] = $v['tel'];
				$hotels_new[$v['hotel_id']] = $data;
			}
		}

		return $hotels_new;
	}
	public function _get_rooms( $inter_id, $hotelIds, $roomIds=array() )
	{
		//获取所有酒店的房型
		$this->load->model ( 'hotel/Rooms_model' );
		$rooms = $this->Rooms_model->get_hotels_rooms( $inter_id, array_keys( $hotelIds ), 'name,room_id,hotel_id,room_img' );
		// var_dump( $rooms );die;
		// var_dump( $hotelIds, $roomIds, $rooms );die;
		if( $hotelIds ){
			$len = count( $roomIds );
			foreach( $rooms as $k=>$v ){
				// var_dump( $v );die;
				if( $len == 0 ){
					//多个房型
					$hotelIds[$k]['room_ids'] = $v; 
				}else{
					//过滤房型
					foreach( $v as $sk=>$sv ){
						// var_dump( $sv );die;
						//一个房型
						if( isset( $roomIds[$k] ) && is_array( $roomIds[$k] ) ){
							if( in_array( $sv['room_id'], array_keys( $roomIds[$k] ) ) ){
								$hotelIds[$k]['room_ids'][$sk] = $sv;
							}
						}
					}
				}
			}
		}

		return $hotelIds;
	}
	public function _get_codes( $inter_id, $hotelIds, $codeIds=array() )
	{
		//获取不同房型的价格
		$this->load->model ( 'hotel/Price_code_model' );
		//这里存了不同的价格(2.00，1.00，0.500)
		$sets = $this->Price_code_model->get_hotels_price_set( $inter_id, array_keys( $hotelIds ), 'price_code,room_id,hotel_id,price' );
		//这里存了不同的价格名称(普通价，微信价，金房卡价)
		$codes = $this->Price_code_model->get_price_codes( $inter_id );
		if( $hotelIds ){
			$len = count( $codeIds );
			// var_dump( $len, $codeIds );die;
			foreach( $sets as $k=>$v ){
				foreach( $v as $sk=>$sv ){
					foreach( $sv as $ssk=>$ssv ){
						if( $len == 0 ){
							//多个价格
							$sv[$ssk]['price_name'] = $codes[$ssk]['price_name'];
							$hotelIds[$k]['room_ids'][$sk]['price_codes'] = $sv;
						}else{
							//过滤房型
							// var_dump(  $codeIds[$k][$sk] = array(1,2) );die;
							if( isset( $codeIds[$k][$sk] ) && is_array( $codeIds[$k][$sk] ) ){
								if( in_array( $ssv['price_code'], array_keys( $codeIds[$k][$sk] ) ) ){
									//一个价格
									$sv[$ssk]['price_name'] = $codes[$ssk]['price_name'];
									$hotelIds[$k]['room_ids'][$sk]['price_codes'][$ssk] = $sv[$ssk];
								}
							}
						}
					}
				}
			}
		}

		return $hotelIds;
	}

	//套票转预订，拉取房型
	public function get_rooms()
	{
		$inter_id = $this->session->get_admin_inter_id();

		//获取所有酒店
		$hotelIds = $this->_get_hotels( $inter_id );
		// var_dump( $hotelIds );die;

		$hotelIds = $this->_get_rooms( $inter_id, $hotelIds );
		// var_dump( $hotelIds );

		$hotelIds = $this->_get_codes( $inter_id, $hotelIds );
		// var_dump( $hotelIds );die;

		return $hotelIds;

	}

	/**
	 * 拉取组合商品的列表信息
	 */
	public function get_compose_product_list()
	{
		$serviceName  = $this->serviceName(Product_Service::class);
        $serviceAlias = $this->serviceAlias(Product_Service::class);
        $this->load->service($serviceName, null, $serviceAlias);

        $temp_id = $this->session->get_temp_inter_id();
        if ($temp_id) {
            $inter_id = $temp_id;
        } else {
            $inter_id = $this->session->get_admin_inter_id();
        }

        $ent_ids= $this->session->get_admin_hotels();
	    $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();

	    $pname = $this->input->get('pname', true);
	    if(!empty($pname))
	    {
	    	$filter['name'] = $pname;
	    }

	    $page = $this->input->get('page', true);
	    if(!empty($page) && isset($page['page_num']) && isset($page['page_size'])
	    	&& is_int($page['page_num']) && is_int($page['page_size']))
	    {
	    	$filter['page'] = $page;
	    }

	    $where_one['type'] = Product_package_model::PRODUCT_TYPE_DEFAULT;
	    $where_one['goods_type'] = array(
	    	Product_package_model::SPEC_TYPE_SCOPE,
	    	Product_package_model::SPEC_TYPE_TICKET,
	    );

	    $where_two['type'] = Product_package_model::PRODUCT_TYPE_PRIVILEGES_VOUCHER;

	    $filter['where_group'][] = $where_one;
	    $filter['where_group'][] = $where_two;

        $data = $this->soma_product_service->getComposeProductList($inter_id, $hotel_ids, $filter);

        echo json_encode($data);
	}

	//异步获取订房信息
	public function ajax_get_booking_config()
    {
        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);

        $interId = $this->session->get_admin_inter_id();
        $wxBookConf = $rooms = $page_rooms = array();

        //下面的公众号不拉取房型
        $interArr = array(
            'a492669988',
            'a491796658',
        );

        $id = $this->input->post('id');
        if( !empty( $id ) )
        {
            $id = intval($id);
            $model = $model->load($id);
            if( $model )
            {
                $bookingConfig = $model->m_get('wx_booking_config');
                if( $bookingConfig )
                {
                    $bookingConfig  = json_decode( $bookingConfig, true );
                    $wxBookConf     = $bookingConfig['select_ids'];
                }
            }
        }

        if( !in_array($interId,$interArr) )
        {
            $rooms = $this->get_rooms();
        }

        $total  = 0;
        $end_page = FALSE;//是否最后一页，是为true，否为false

        $limit  = 500;//每次取500条价格代码
        $page   = $this->input->post('page') ? $this->input->post('page') + 0 : 1;
        if( $page < 0 )
        {
            $page = 1;
        }
        $offset_start   = ($page-1) * $limit;//从那条开始取
        $offset_end     = $offset_start + $limit;//取到第几条结束

        //搜索按照酒店名
        $is_search  = $this->input->post('is_search') ? $this->input->post('is_search') : '';
        $search     = $this->input->post('search') ? $this->input->post('search') : '';
        $is_search  = trim( $is_search );
        $search     = trim( $search );

        //处理逻辑
        if( $rooms )
        {
            foreach( $rooms as $k=>$v )
            {
                $hotelName = isset( $v['name'] ) ? trim($v['name']) : '';

                //搜索
                if( $search )
                {
                    if( !$hotelName || strpos( $hotelName, $search ) === FALSE )
                    {
                        //酒店名称为空或者没有搜索到酒店内容，跳过这条数据
                        continue;
                    }
                }

                //酒店是否选中
                if(
                    isset( $wxBookConf )
                    && is_array( $wxBookConf )
                    && isset( $v['hotel_id'] )
                    && in_array( $v['hotel_id'], array_keys( $wxBookConf ) )
                )
                {
                    $checked = TRUE;
                } else {
                    $checked = FALSE;
                }

                $data = array(
                    'hotel_id'  => $k,
                    'name'      => $hotelName,
                    'checked'   => $checked,
                    'room_ids'  => array(),
                );

                if(isset($v['room_ids']))
                {
                    foreach( $v['room_ids'] as $sk=>$sv )
                    {
                        //房型是否选中
                        if(
                            isset( $wxBookConf[$k] )
                            && is_array( $wxBookConf[$k] )
                            && isset( $sv['room_id'] )
                            && in_array( $sv['room_id'], array_keys( $wxBookConf[$k] ) )
                        )
                        {
                            $roomChecked = TRUE;
                        } else {
                            $roomChecked = FALSE;
                        }

                        $roomData = array(
                            'hotel_id'      => $k,
                            'room_id'       => $sk,
                            'name'          => isset($sv['name'])?$sv['name']:'',
                            'checked'       => $roomChecked,
                            'price_codes'   => array(),
                        );

                        if(isset($sv['price_codes']))
                        {
                            foreach( $sv['price_codes'] as $ssk=>$ssv )
                            {
                                //价格代码是否选中
                                if(
                                    isset( $wxBookConf[$k][$sk] )
                                    && is_array( $wxBookConf[$k][$sk] )
                                    && isset( $ssv['price_code'] )
                                    && in_array( $ssv['price_code'], array_keys( $wxBookConf[$k][$sk] ) )
                                )
                                {
                                    $priceChecked = TRUE;
                                } else {
                                    $priceChecked = FALSE;
                                }

                                $priceData = array(
                                    'hotel_id'      => $k,
                                    'room_id'       => $sk,
                                    'price_code'    => $ssk,
                                    'price_name'    => isset($ssv['price_name'])?$ssv['price_name']:'',
                                    'checked'       => $priceChecked,
                                );

                                //处理分页价格代码内容
                                $total += 1;
                                if( $total > $offset_start && $total <= $offset_end )
                                {
                                    $page_rooms[$k]['room_ids'][$sk]['price_codes'][$ssk] = $priceData;
                                }
                            }
                        }

                        //处理分页房型内容
                        if( isset( $page_rooms[$k]['room_ids'][$sk]['price_codes'] ) && !empty( $page_rooms[$k]['room_ids'][$sk]['price_codes'] ) )
                        {
                            $roomData['price_codes'] = $page_rooms[$k]['room_ids'][$sk]['price_codes'];
                            $page_rooms[$k]['room_ids'][$sk] = $roomData;
                        }
                    }
                }

                //处理分页酒店内容
                if( isset( $page_rooms[$k]['room_ids'] ) && !empty( $page_rooms[$k]['room_ids'] ) )
                {
                    $data['room_ids'] = $page_rooms[$k]['room_ids'];
                    $page_rooms[$k] = $data;
                }
            }
        }

        //最后一页
        if( $total <= $offset_end )
        {
            $end_page = TRUE;
        }

        $returnData = array(
            'total'     => $total,
            'data'      => $page_rooms,
            'page'      => $page,
            'end_page'  => $end_page,
            'is_search' => $is_search,
            'search'    => $search,
        );

//        var_dump( $returnData );die;
        echo json_encode( $returnData );

    }

}
