<?php

//控制器记得要添加公众号过滤，不然其它公众号知道链接，一样可以进来中心平台，可能会造成不可预料的结果

class Center extends MY_Front_Soma {

	const CENTER_PRODUCE_TIME = '2016-10-08 16:00:00';

	public function __construct()
	{
		parent::__construct();
        //theme
        // $this->load->model('soma/Theme_config_model');
        // $this->themeConfig = $themeConfig = $this->Theme_config_model->get_using_theme($this->inter_id);
        // $this->theme = $themeConfig['theme_path'];
        $this->theme = 'center';

        //检查公众号是否可以访问中心平台
       	$center_inter_id = $this->get_center_inter_id();
       	if( $this->inter_id != $center_inter_id ){
       		die('没有权限访问！');
       	}
	}

	protected function _public_view()
	{
		$inter_id = $this->inter_id;
		//活动预告
		$adv_notice_url = Soma_const_url::inst()->get_url( '*/*/get_adv_notice_list' );

		//进行中的活动
		$start_url = Soma_const_url::inst()->get_url( '*/*/get_activity_list', array( 'id'=>$inter_id, 't'=>Soma_base::STATUS_TRUE ) );

		//即将开始的活动
		$coming_url = Soma_const_url::inst()->get_url( '*/*/get_activity_list', array( 'id'=>$inter_id, 't'=>Soma_base::STATUS_FALSE ) );

		//我的订单
		$my_order_list = Soma_const_url::inst()->get_url( '*/*/order_list', array( 'id'=>$inter_id ) );

		$this->datas['adv_notice_url'] = $adv_notice_url;
		$this->datas['start_url'] = $start_url;
		$this->datas['coming_url'] = $coming_url;
		$this->datas['my_order_list'] = $my_order_list;
	}

	protected function get_default_sharing()
    {
      if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
        $share_img = base_url('public/soma/images/center_sharing.png');
        $default_title = '金房卡商城';
        $default_desc = '秒杀购买更实惠';
      } else {
        $share_img = base_url('public/soma/images/center_sharing.png');
        $default_title = '金房卡商城';
        $default_desc = '秒杀购买更实惠';
      }

      $default_share_config = array(
          'share_img' => $share_img,
          'default_title' => $default_title,
          'default_desc' => $default_desc,
        );
      return $default_share_config;
    }

	protected function _write_log($content) {
		$this->write_log($content, 'soma' . DS . 'center');
	}

	/**
	 * 建立酒店-中心平台openid映射记录
	 */
	public function bulid_openid_map_record() {

		$this->_write_log('bulid_openid_map_record start ……');

		$hotel_data = $this->input->get('hotel_info', true);
		$extra = $this->input->get('extra', true);
		$notify_url = base64_url_decode($this->input->get('notify_url', true));

		$_rec_data_log = "receive:\n" 
			. var_export(array("hotel_info" => $hotel_data), true) . "\n"
			. var_export(array("extra" => $extra), true) . "\n" 
			. var_export(array("notify_url" => $notify_url), true);
		$this->_write_log($_rec_data_log);

		$this->load->model('soma/center_openid_map_model', 'o_model');
		$_fmt_data = $this->o_model->format_map_record_data($this->inter_id, $this->openid, $hotel_data);
		
		$result = array('success' => false, 'msg' => '写入数据失败');

		if($this->o_model->data_validation($_fmt_data)) {
			if($this->o_model->save_map_record($_fmt_data)) {
				$result = array('success' => true, 'msg' => '');
			}
		}

		$_result_log = "result:\n" . var_export(array("result" => $result), true);
		$this->_write_log($_result_log);

		$url = urldecode($notify_url);
		strrpos($url, '?') >=0 ? $url .= '&' : $url .= '?';
		$extra_url_params = '';
		if(is_array($extra)) {
			foreach ($extra as $key => $value) {
				$extra_url_params .= "&extra[" . $key. "]=" . $value;
			}
		}

		$redirect_url = $url
			. 'res[success]=' . $result['success'] . '&res[msg]=' . $result['msg'] . $extra_url_params;

		$this->_write_log("redirect_url:" . $redirect_url);

		$this->_write_log('bulid_openid_map_record end ……');
		
		redirect($redirect_url);
	}

	//加载同步秒杀的活动列表
	public function get_activity_list()
	{
		try {

			/*
				注意：可能不显示的问题
				1.秒杀商品不能设置为同一个商品，否则只会显示其中的一个商品
				2.检查活动的状态、开始时间、秒杀时间、结束时间
				3.检查商品的状态、有效期
				4.如果按星期的秒杀，这一轮秒杀结束了，秒杀时间没过也不会显示在秒杀列表，而是显示在即将开始，因为秒杀开始时间修改成下一次开始的时间
			*/
			
			$debug = TRUE;
			$inter_id = $this->inter_id;
			$openid = $this->openid;
			$type = $this->input->get('t');
			// var_dump( $inter_id, $openid );die;

			$this->load->model('wx/publics_model');

			$this->load->model('soma/Center_activity_model','centerModel');
			$centerModel = $this->centerModel;

			$filter = array();
			$filter['sync_type'] = $centerModel::SYNC_TYPE_KILLSEC;
			$activityList = $centerModel->get_activity_list( $filter, 'sort DESC' );
			if( $debug )$this->_write_log('中心平台的数据:'.json_encode($activityList));
			// var_dump( $activityList );die;
			$actIds = array();
			if( count( $activityList ) > 0 ){
				foreach( $activityList as $k=>$v ){
					$actIds[$v['act_id']] = $v;
				}
			}

			$in_array = array();//在秒杀表查找出来的数据，act_id集合，去除没有查找出来的数据
			$nowTime = date('Y-m-d H:i:s');
			$productIds = array();
			$goods = array();
			$startList = array();
			$comingList = array();//根据秒杀是否开始来做判断是否开始和即将开始
			$unSalesList = array();
			if( count( $actIds ) > 0 ){
				
				$actIds_key = array_keys( $actIds );

				$this->load->model('soma/Activity_killsec_model','killsecModel');
				$killsecModel = $this->killsecModel;
				$killsecList = $killsecModel->get_activity_killsec_list_byActIds( $actIds_key );
				if( $debug )$this->_write_log('秒杀的数据:'.json_encode($killsecList));
				// var_dump( $killsecList );die;

				if( count( $killsecList ) > 0 ){

					//是否订阅了秒杀提醒
					$subscribeIds = array();
					if( $type == Soma_base::STATUS_FALSE ){
			        	$notices = $killsecModel->get_waiting_notice_list_byActIds( $actIds_key, $inter_id, $openid );
			        	// var_dump( $notices );die;
			        	if( count( $notices ) > 0 ){
			        		foreach ($notices as $k => $v) {
			        			$subscribeIds[$v['act_id']] = $v;
			        		}
			        	}
					}

					//第一次筛选，筛选活动是否已经下架
					foreach ($killsecList as $k => $v) {
						$in_array[] = $v['act_id'];
						if( $v['end_time'] > $nowTime || $v['schedule_type'] == $killsecModel::SCHEDULE_TYPE_CYC ){
							//是否已经订阅了秒杀提醒
							$v['subscribe'] = Soma_base::STATUS_FALSE;
							if( isset( $subscribeIds[$v['act_id']] ) ){
				        		$v['subscribe'] = Soma_base::STATUS_TRUE;
				        	}

							//把链接带到商品详情
							if( isset( $actIds[$v['act_id']]['link'] ) && !empty( $actIds[$v['act_id']]['link'] ) ){
								$v['link'] = $this->_add_center_info_to_url( $actIds[$v['act_id']]['link'] );
							}else{
								//在同步操作的时候，已经生成过链接的了，这里的作用是防止，没有生成链接的
								$this->load->model('wx/Publics_model','PublicsModel');
								$publics= $this->PublicsModel->get_public_by_id($inter_id);
						        $link = '';
						        if( $publics ){
						        	$link = isset( $publics['domain'] ) ? 'http://' . $publics['domain'] 
						        											. DS . 'index.php' 
						        											. DS . 'soma' 
						        											. DS . 'package' 
						        											. DS . 'package_detail'
						        											. DS . '?id='.$v['inter_id'].'&pid='.$v['product_id'] : '';
						        }
								$v['link'] = $link;
							}

							//取出公众号名称
							$filter= array();
							$filter['inter_id'] = $v['inter_id'];
					        $publics= $this->publics_model->get_public_hash($filter);
					        $publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
					        $v['inter_name'] = $publics[$v['inter_id']];

					        //查找剩余库存量
					        if( $type != Soma_base::STATUS_FALSE ){//2代表的是即将开始的，所以不用计算秒杀库存剩余量
					            $instance= $killsecModel->get_aviliable_instance( array('act_id'=>$v['act_id'], 'status < '=>$killsecModel::INSTANCE_STATUS_FINISH ) );
					            if( isset($instance[0]) && $instance[0]['status']==$killsecModel::INSTANCE_STATUS_GOING ){
					                $cache= $this->_load_cache();
					                $redis= $cache->redis->redis_instance();
					                $key= $killsecModel->redis_token_key($instance[0]['instance_id']);
					                $ks_stock = $redis->lSize($key);
					                $ks_count = $instance[0]['killsec_count'];
					        
					            } else {
					                $ks_count = $v['killsec_count'];
					                $ks_stock = 0;
					            }
					            $ks_percent= round($ks_stock / $ks_count, 2);
					            $v['ks_stock'] = $ks_stock;
					            $v['ks_count'] = $ks_count;
					            $v['ks_percent'] = ( $ks_percent>1? 1: $ks_percent ) * 100;
					        }

							$productIds[$v['product_id']] = $v;
							$actIds[$v['act_id']] = $v;

						}else{
							$v['message'] = '在秒杀活动中被筛选掉';
							$unSalesList[$v['act_id']] = $v['act_id'];
							unset( $actIds[$v['act_id']] );
						}
					}

					// var_dump( $productIds );die;
					if( count( $productIds ) > 0 ){

						//查询商品是否已经下架
						$this->load->model('soma/Product_package_model','productModel');
						$productModel = $this->productModel;
						$interId = NULL;//这里公众号的条件必须为空
						$select = 'product_id,price_market,face_img';
						//因为这里的查询已经带了时间条件，所以不需要再筛选时间，是否已经过期的商品
						$productList = $productModel->get_product_package_by_ids( array_keys( $productIds ), $interId, $select );
						if( $debug )$this->_write_log('商品的数据:'.json_encode($productList));
						// var_dump( $productList );die;

						if( count( $productList ) > 0 ){
							//第二次筛选，筛选商品是否已经下架
							foreach ($productList as $k => $v) {
								//市场价
								$goods[$v['product_id']]['price_market'] = $v['price_market'];
								$goods[$v['product_id']]['face_img'] = $v['face_img'];

								unset( $productIds[$v['product_id']] );//删除没有下架的商品，那么剩下的就是活动没有结束，但是商品已经下架了的商品
							}

							//过滤掉没有过期切没有下架的商品，那么剩下的商品对应的活动，在中心平台修改活动的下线状态
							if( count( $productIds ) > 0 ){
								foreach ($productIds as $k => $v) {
									$v['message'] = '在商品中被筛选掉';
									$unSalesList[$v['act_id']] = $v['act_id'];
									unset( $actIds[$v['act_id']] );
								}
							}

						}else{
							//使用商品ID去查找，没有查找到数据，那么全部数据状态修改为下线
							$centerModel->update_status_byActIds( array_keys( $actIds ) );
							if( $debug )$this->_write_log( '在商品里全部被筛选掉:'.json_encode( array_keys( $actIds ) ) );
							$actIds = array();
						}

					}


				}else{
					//如果在中心平台搜索有数据，但是使用活动ID去查找，没有查找到数据，那么全部数据状态修改为下线
					$centerModel->update_status_byActIds( $actIds_key );
					if( $debug )$this->_write_log('在活动里全部被筛选掉:'.json_encode($actIds_key));
					$actIds = array();

				}

			}

	// echo '需要修改状态的数据<br />';print_r( $unSalesList );echo '<br /><br />';
	// echo '进行中的数据<br />';print_r( $startList );echo '<br /><br />';
	// echo '即将开始的数据<br />';print_r( $comingList );echo '<br /><br />';
			if( count( $actIds ) > 0 ){
				foreach( $actIds as $k=>$v ){
					if( in_array( $v['act_id'], $in_array ) ){
						$v['price_market'] = $goods[$v['product_id']]['price_market'];
						$v['face_img'] = $goods[$v['product_id']]['face_img'];

				        if( $v['killsec_time'] <= $nowTime && $v['end_time'] >= $nowTime ){
							//秒杀已经开始
							$startList[$v['act_id']] = $v;
						}elseif( $v['start_time'] <= $nowTime && $v['killsec_time'] > $nowTime ){
							//即将开始
							$comingList[$v['act_id']] = $v;
						}
					}else{
						$v['message'] = '在秒杀查找数据中被筛选掉，没有在秒杀表中查到数据。';
						$unSalesList[$v['act_id']] = $v['act_id'];
					}
				}
			}

			if( count( $unSalesList ) > 0 ){
				//有需要下线的活动，只修改中心平台活动下线状态
				$centerModel->update_status_byActIds( array_keys( $unSalesList ) );
				$this->_write_log('被筛掉的数据:'.json_encode($unSalesList));
			}
			
			if( $debug ){
				$this->_write_log( '进行中的数据:'.json_encode( array_keys( $startList) ) );
				$this->_write_log( '即将开始的数据:'.json_encode( array_keys( $comingList ) ) );
			}

			//点击分享之后开启这些按钮
	        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl' );
	        $uparams= $this->input->get()+ array('id'=> $this->inter_id);

			//获取分享配置
			$this->load->model('soma/Center_share_config_model','shareModel');
			$shareModel = $this->shareModel;
			$share_config_detail = $shareModel->get_share_config_list( $shareModel::POSITION_ACTIVITY, $inter_id );
			// var_dump( $share_config_detail );

			$default_share_config = $this->get_default_sharing();

			$share_config = array(
	            'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
	            'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
	            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/*/*', $uparams ),//$share_config_detail['share_link'],
	            'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
	        );
			
			//秒杀订阅链接
			$killsec_notice_url = Soma_const_url::inst()->get_url( '*/*/subscribe_killsec_notice_ajax', array( 'id'=>$inter_id, 'openid'=>$openid ) );
			
			$this->datas = array(
					'startList'=>$startList,
					'comingList'=>$comingList,
					'killsec_notice_url'=>$killsec_notice_url,
					'js_share_config'=> $share_config,
					'js_menu_show'=> $js_menu_show,
				);

			$this->_public_view();

			if( $type == Soma_base::STATUS_FALSE ){
				$header = array(
		            'title'   => '即将开始'
		        );
				$this->_view("header",$header);
		        $this->_view("coming_list",$this->datas);
		        $this->_view("footer",array('active'=>'coming'));
			}else{
				//已经开始的列表
				$header = array(
		            'title'   => '秒杀列表'
		        );
				$this->_view("header",$header);
		        $this->_view("start_list",$this->datas);
		        $this->_view("footer",array('active'=>'killsec'));
			}
		} catch (Exception $e) {
			$this->_write_log( 'errorMessage:' . $e->getMessage() );
			die('发生未知错误！');
		}

	}

	//加载活动预告列表(活动列表和活动预告是同一个东西)
	public function get_adv_notice_list()
	{
		$inter_id = $this->get_center_inter_id();
		$openid = $this->openid;
		// var_dump( $inter_id, $openid );die;
		
		$this->load->model('soma/Center_activity_adv_model','CenterAdvModel');
		$CenterAdvModel = $this->CenterAdvModel;

		$filter = array();//筛选条件为空，查找全部上线的活动
		$filter['type'] = $CenterAdvModel::ACTIVITY_NOTICE_TYPE;
		$advList = $CenterAdvModel->get_adv_list( $filter, 'sort DESC' );
		// var_dump( $advList );

		//点击分享之后开启这些按钮
        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl' );
        $uparams= $this->input->get()+ array('id'=> $this->inter_id);

        //获取分享配置
		$this->load->model('soma/Center_share_config_model','shareModel');
		$shareModel = $this->shareModel;
		$share_config_detail = $shareModel->get_share_config_list( $shareModel::POSITION_ACTIVITY, $inter_id );
		// var_dump( $share_config_detail );

		$default_share_config = $this->get_default_sharing();

		$share_config = array(
            'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/*/*', $uparams ),//$share_config_detail['share_link'],
            'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );

		$this->datas = array(
				'advList'=>$advList,
				'js_share_config'=> $share_config,
				'js_menu_show'=> $js_menu_show,
			);

		$this->_public_view();

		//已经开始的列表
		$header = array(
            'title'   => '活动预告'
        );
		$this->_view("header",$header);
        $this->_view("adv_list",$this->datas);
        $this->_view("footer",array('active'=>'adv'));

	}

	//秒杀订阅
    public function subscribe_killsec_notice_ajax()
    {
        $return= array('status'=> Soma_base::STATUS_FALSE, 'data'=>array(), 'message'=> '找不到活动信息。' );
        $actId = $this->input->post('act_id');
        $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
        $activity= $this->activityKillsecModel->get_aviliable_activity( array('act_id'=>$actId ) );
        
        if( count($activity)>0 ){
            $activity= $activity[0];
            if( isset($activity['status']) && $activity['status']==Activity_killsec_model::STATUS_TRUE ){
                if( $activity['killsec_time']< date('Y-m-d H:i:s', strtotime('+30 minute')) ){
                    //已经超过订阅时间
                    $return['message']= '已超过订阅时间！';
                    
                } else {
                    $inter_id = $this->get_center_inter_id();
					$openid = $this->openid;
                    $data= array(
                        'act_id'=> $actId,
                        'openid'=> $openid,
                        'inter_id'=> $inter_id,
                        'product_id'=> $activity['product_id'],
                        'product_name'=> $activity['product_name'],
                        'killsec_price'=> $activity['killsec_price'],
                        'killsec_time'=> $activity['killsec_time'],
                    );
                    $result= $this->activityKillsecModel->save_waiting_notice_list($inter_id, $data);
                    if($result){
                        $return['status']= Soma_base::STATUS_TRUE;
                        $return['message']= '订阅成功';
                    
                    } else {
                        $return['message']= '订阅失败';
                    }
                }
            }
        }
        echo json_encode( $return );
    }

	/**
	 * 中心平台订单列表
	 * 获取中心平台openid->获取各酒店映射openid->获取上线时间后所有订单信息
	 * 
	 * 订单信息：
	 * 订单编号，创建时间
	 * 产品名称，产品封面图，酒店名称，产品单价，数量
	 * 
	 */
	public function order_list() {

		try {
			$this->load->model('soma/sales_order_model', 'm_order');
			$this->load->model('soma/center_openid_map_model', 'm_openid');
			$this->load->model('wx/publics_model', 'm_publics');
			
			$openids = $this->m_openid->get_hotel_openid_collection($this->inter_id, $this->openid);

			$filter['where']['create_time >'] = self::CENTER_PRODUCE_TIME;
			$filter['where']['openid'] = $openids;
			$filter['where']['status'] = Sales_order_model::STATUS_PAYMENT;
			$filter['order_by']['order_id'] = 'DESC';
			$ori_orders = $this->m_order->get_order_collection($filter);

			$inter_ids = $inter_domains = $domain_hash = array();
			foreach ($ori_orders as $order) {
				$inter_ids[] = $order['inter_id'];
			}
			if(count($inter_ids) > 0) {
				$inter_domains = $this->m_publics->get_public_hash(array('inter_id' => $inter_ids));
				$domain_hash = $this->m_publics->array_to_hash($inter_domains, 'domain', 'inter_id');
			}

			//点击分享之后开启这些按钮
			$js_menu_hide = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl' );
	        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl' );
	        $uparams= $this->input->get()+ array('id'=> $this->inter_id);

	        //获取分享配置
			$this->load->model('soma/Center_share_config_model','shareModel');
			$shareModel = $this->shareModel;
			$share_config_detail = $shareModel->get_share_config_list( $shareModel::POSITION_ACTIVITY, $this->inter_id );
			// var_dump( $share_config_detail );

			$default_share_config = $this->get_default_sharing();

			$share_config = array(
	            'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
	            'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
	            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/*/*', $uparams ),//$share_config_detail['share_link'],
	            'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
	        );

			$orders = array();
			foreach ($ori_orders as $order) {
				if(!isset($domain_hash[ $order['inter_id'] ])) {
					continue;
				}
				$_tmp_o = $order;
				$base_url = 'http://'
						. $domain_hash[ $order['inter_id'] ]
						. '/index.php/soma/order/order_detail'
						. '?id=' . $order['inter_id'] . '&oid=' . $order['order_id'] 
						. '&bsn=' . $order['business'];
				$_tmp_o['link'] = $this->_add_center_info_to_url($base_url);
				$orders[] = $_tmp_o;
			}

		} catch (Exception $e) {
			$orders = array();
		}

		$header = array('title' => '我的订单');
		$this->datas['orders'] = $orders;
		$this->datas['js_share_config']= $share_config;
		$this->datas['js_menu_show']= $js_menu_show;
		$this->datas['js_menu_hide']= $js_menu_hide;

		$this->_public_view();

		$this->_view('header', $header);
		$this->_view('order', $this->datas);
		$this->_view("footer",array('active'=>'order'));
	}


	/**
	 * 向url添加中心平台参数
	 *
	 * @param      string  $url    链接地址
	 *
	 * @return     string  附加了中心平台参数的链接地址
	 */
	protected function _add_center_info_to_url($url) {
		
		if(strrpos($url, '?') >=0){
			$url .= '&';
		} else {
			$url .= '?';
		}

		$url .= 'c_open=' . $this->openid;
		// $url .= '&openid=' . $this->openid;

		return $url;
	}

}