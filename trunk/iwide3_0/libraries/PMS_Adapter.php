<?php
include 'IPMS.php';
include 'PMS/Default_service.php';
include 'PMS/Yibo_webservice.php';
include 'PMS/Zhuzhe_webservice.php';
include 'PMS/Huayi_webservice.php';
include 'PMS/Lvyun_webservice.php';
include 'PMS/Yuanzhou_webservice.php';
include 'PMS/Luopan_webservice.php';
include 'PMS/Native_webservice.php';
include 'PMS/Buding_webservice.php';
include 'PMS/Zhongruan_webservice.php';
include 'PMS/Sanghu_webservice.php';
include 'PMS/Suba_webservice.php';
include 'PMS/Yasite_webservice.php';
include 'PMS/Beyondh_webservice.php';
include 'PMS/Yage_webservice.php';
include 'PMS/Kezhan_webservice.php';
include 'PMS/Jinjiang_webservice.php';
include 'PMS/Yasiteiw_webservice.php';
include 'PMS/Xiruan_webservice.php';
include 'PMS/Shiji_webservice.php';
include 'PMS/Qianlima_webservice.php';
include 'PMS/Youcheng_webservice.php';
include 'PMS/Yuheng_webservice.php';
include 'PMS/Xiruaniw_webservice.php';
include 'PMS/Zhongruaniw_webservice.php';
include 'PMS/Argyle_webservice.php';
include 'PMS/Xiruan3_webservice.php';
include 'PMS/Beyondh2_webservice.php';
include 'PMS/Gbsz_webservice.php';
include 'PMS/Quanzhou_webservice.php';

class PMS_Adapter implements IPMS{
	protected $CI;
	protected $obj;
	protected $pms_set;
	public function __construct($params){
		$this->CI = &get_instance();
		$this->pms_set = $this->get_pms_set($params ['inter_id'], $params ['hotel_id']);
		$pms_param = array(
			'pms_set' => $this->pms_set
		);
		//$this->pms_set ['pms_type'] = 'suba';//测试
		//$this->pms_set ['pms_member_way'] = 1;
		if(!empty ($this->pms_set ['pms_type'])){
			switch($this->pms_set ['pms_type']){
				case 'yibo' :
					$this->obj = new Yibo_webservice ($pms_param);
					break;
				case 'zhuzhe' :
					$this->obj = new Zhuzhe_webservice ($pms_param);
					break;
				case 'huayi' :
					$this->obj = new Huayi_webservice ($pms_param);
					break;
				case 'yuanzhou' :
					$this->obj = new Yuanzhou_webservice ($pms_param);
					break;
				case 'lvyun' :
					$this->obj = new lvyun_webservice ($pms_param);
					break;
				case 'luopan' :
					$this->obj = new Luopan_webservice ($pms_param);
					break;
				case 'native' :
					$this->obj = new Native_webservice ($pms_param);
					break;
				case 'buding' :
					$this->obj = new Buding_webservice ($pms_param);
					break;
				case 'zhongruan' :
					$this->obj = new Zhongruan_webservice ($pms_param);
					break;
				case 'sanghu' :
					$this->obj = new Sanghu_webservice ($pms_param);
					break;
				case 'suba' :
					$this->obj = new Suba_webservice ($pms_param);
					break;
				case 'yasite':
					$this->obj = new Yasite_webservice ($pms_param);
					break;
				case 'beyondh':
					$this->obj = new Beyondh_webservice($pms_param);
					break;
				case 'yage':
					$this->obj = new Yage_webservice($pms_param);
					break;
				case 'kezhan':
					$this->obj = new Kezhan_webservice($pms_param);
					break;
				case 'jinjiang' :
					$this->obj = new Jinjiang_webservice ( $pms_param );
					break;
				case 'yasiteiw':
					$this->obj = new Yasiteiw_webservice($pms_param);
					break;
				case 'shiji':
					$this->obj = new Shiji_webservice($pms_param);
					break;
				case 'xiruan':
					$this->obj = new Xiruan_webservice($pms_param);
					break;
				case 'qianlima':
					$this->obj = new Qianlima_webservice($pms_param);
					break;
                case 'youcheng':
                    $this->obj = new Youcheng_webservice($pms_param);
                    break;
				case 'yuheng':
					$this->obj = new Yuheng_webservice($pms_param);
					break;
				case 'xiruaniw':
					$this->obj = new Xiruaniw_webservice($pms_param);
					break;
				case 'zhongruaniw':
					$this->obj = new Zhongruaniw_webservice($pms_param);
					break;
				case 'argyle':
					$this->obj = new Argyle_webservice($pms_param);
					break;
				case 'xiruan3':
					$this->obj = new Xiruan3_webservice($pms_param);
					break;
				case 'beyondh2':
					$this->obj = new Beyondh2_webservice($pms_param);
					break;
				case 'gbsz':
					$this->obj = new Gbsz_webservice($pms_param);
					break;
				case 'quanzhou':
					$this->obj = new Quanzhou_webservice($pms_param);
					break;
				default :
					$this->obj = new default_service ($pms_param);
					break;
			}
		} else{
			$this->obj = new default_service ($pms_param);
		}
	}
	public function headerUrlCenter(){
		return $this->obj->headerUrlCenter();
	}

	public function get_new_hotel($param = array()){
		return $this->obj->get_new_hotel($param);
	}
	public function get_orders($inter_id, $status, $offset, $limit){
		return $this->obj->get_orders($inter_id, $status, $offset, $limit);
	}
	public function get_hotels($inter_id, $status, $offset, $limit){
		//
	}
	public function get_rooms_change($rooms, $idents = array(), $condit = array()){
		$this->CI->load->helper('common');
		$this->CI->load->library('Cache/Redis_proxy',array(
			'not_init'=>FALSE,
			'module'=>'common',
			'refresh'=>FALSE,
			'environment'=>ENVIRONMENT
		),'redis_proxy');

		$idents['hotel_id']=(int)$idents['hotel_id'];
		$condit['member_level']=(int)$condit['member_level'];
		$countdays=get_room_night($condit ['startdate'],$condit ['enddate'],'round');

		if(!empty($this->CI->input->get('recache')) || $this->CI->session->userdata($idents['inter_id'].'_roomstate_recache') == 1){
			$condit['recache']=1;
		}
		$result = $this->obj->get_rooms_change($rooms, $idents, $condit);

		/**
		 * 最低价缓存
		 * Add By 大鹏 On 2016-09-07
		 */
		if(!empty($condit['price_codes'])){
			$price_codes = explode(',',$condit['price_codes']);
			sort($price_codes);
			$ext = ':'.json_encode($price_codes);
		}else{
			$ext = '';
		}
		$key_date = 'lowest:' . $idents['inter_id'] . ':' . $idents['hotel_id'] . ':' . $condit['member_level'] . ':' . date('Ymd', strtotime($condit['startdate'])) . ':' . date('Ymd', strtotime($condit['enddate'])).$ext;
		$key = 'lowest:' . $idents['inter_id'] . ':' . $idents['hotel_id'] . ':' . $condit['member_level'];

		$pay_ways=array();
		if (!empty($condit['check_pointpay'])){
			$point_params=array(
					'startdate'    => $condit['startdate'],
					'enddate'      => $condit['enddate'],
					'openid'       => $condit['openid'],
					'hotel_id'     => $idents ['hotel_id'],
					'bonus'        => $condit['member_bonus'],
					'member_level' => $condit['member_level'],
					'countday' => $countdays
			);
			$this->CI->load->model ( 'hotel/Member_model' );
			$has_point_pay=0;
			if (!empty($condit['pay_ways'])){
				foreach ($condit['pay_ways'] as $p){
					$pay_ways[]=$p->pay_type;
					if ($p->pay_type=='point'){
						$has_point_pay=1;
					}
				}
			}else {
				$this->CI->load->model ( 'pay/Pay_model' );
				$this->CI->load->helper ( 'date' );
				$pay_days = get_day_range ( $condit ['startdate'], $condit ['enddate'], 'array' );
				array_pop ( $pay_days );
				$ways = $this->CI->Pay_model->get_pay_way ( array (
						'inter_id' => $idents['inter_id'],
						'module' => 'hotel',
						'status' => 1,
						'check_day' => 1,
						'hotel_ids' => $idents ['hotel_id'],
						'not_show'=>1
				), $pay_days );
				if (!empty($ways)){
					foreach ($ways as $p){
						$pay_ways[]=$p->pay_type;
						if ($p->pay_type=='point'){
							$has_point_pay=1;
						}
					}
				}
			}
		}
		
		//Redis以HASH保存，field为房型ID，下单时只获取单个房型最低价时只更新对应的field值，不影响其他房型最低价
		//读取已缓存房型，以当前日期的缓存值即可
		$rooms_cache_date=$this->CI->redis_proxy->hGetAll($key_date);
		//已缓存房型的KEY
		$room_keys_date=is_array($rooms_cache_date)?array_keys($rooms_cache_date):[];
		
		if ($idents['inter_id']=='a497596757'){
		    $config_data = array(
		          'PRICE_STATE_FORMAT' => '{"decimal_set":2}'  
		    );
		}
		if (!empty($config_data['PRICE_STATE_FORMAT'])){
		    $price_state_format=json_decode($config_data['PRICE_STATE_FORMAT'],TRUE);
    		foreach($result as $k=>$v){
    		    $roomnums=isset ( $condit ['nums'] [$v ['room_info'] ['room_id']] ) ? $condit ['nums'] [$v ['room_info'] ['room_id']] : 1;
    		    if (!empty($v['state_info'])){
    		        foreach ($v['state_info'] as $state_key=>$state){
    		            $result[$k]['state_info'][$state_key]=$this->format_room_price($state,$price_state_format,$roomnums,$countdays);
    		        }
    		    }
    		    if (!empty($v['show_info'])){
    		        foreach ($v['show_info'] as $show_key=>$show_state){
    		            $result[$k]['show_info'][$show_key]=$this->format_room_price($show_state,$price_state_format,$roomnums,$countdays);
    		        }
    		    }
    		}
		}

		$current_exists=[];
        $room_ids=array();
        $check_package=empty($condit['check_package'])?0:1;
        $extra_min_price=array();
        if ($check_package){
            $goods_ids=array();
            $tmp_result=$result;
            foreach($result as $k=>$v){
                if (!empty($v['state_info'])){
                    foreach ($v['state_info'] as $state_key=>$state){
                        if (!empty($state['goods_info']['items'])&&$state['is_packages']==1){
                            $goods_ids=array_merge($goods_ids,array_column($state['goods_info']['items'], 'gs_id'));
                            if ($state['goods_info']['sale_way']==1){
                                unset($result[$k]['state_info'][$state_key]);
                                if (empty($result[$k]['state_info'])){
                                    unset($result[$k]);
                                }
                            }
                        }
                    }
                }
            }
            $packages=array();
            if ($goods_ids){
                $this->CI->load->model('hotel/goods/Goods_order_model');
                $packages=$this->CI->Goods_order_model->get_price_package_state($idents['inter_id'],$tmp_result,array_unique($goods_ids),array('startdate'=>$condit['startdate'],'enddate'=>$condit['enddate']));
                unset($tmp_result);
                foreach ($packages as $p){
                    if (empty($extra_min_price[$p['room_info']['room_id']]) || $extra_min_price[$p['room_info']['room_id']]>$p['package_info']['total_show_price']){
                        $extra_min_price[$p['room_info']['room_id']]=$p['package_info']['total_show_price'];
                    }
                }
            }
        }
		$this->CI->load->model ( 'hotel/Price_code_model' );
		foreach($result as $k => $v){
			$min_price=[];
			$has_protrol=0;
			//判断是否为均价
			if(!empty($result[$k]['state_info'])){
				unset($v['lowest']);
				foreach($result[$k]['state_info'] as $rate => $t){
					$result[$k]['state_info'][$rate]['is_avg'] = false;
					$_avg_price = str_replace(',', '', $t['avg_price']); //均价
					$avg_price = (int)$_avg_price;
					$daily_detail = $t['date_detail'];
					$first_night = array_shift($daily_detail);
					$first_night_price = $first_night['price']; //首晚价格
					$first_night_price = (int)$first_night_price;
					//如果不等则为价格相同

					if($avg_price != $first_night_price){
						$result[$k]['state_info'][$rate]['is_avg'] = true;
					}

					//@Editor lGh 查询积分兑换
					//判断是否只支持积分支付 (酒店有积分支付方式)&&((此价格代码只支持积分支付)||(酒店只有一种支付方式))
					if (!empty($has_point_pay)&&((!empty($result[$k]['state_info'][$rate]['condition']['no_pay_way'])&&count($point_diff=array_diff($pay_ways, $result[$k]['state_info'][$rate]['condition']['no_pay_way']))==1&&in_array('point', $point_diff))||(count($pay_ways)==1))){
						$point_params['total_price']=$result[$k]['state_info'][$rate]['total_price'];
						$point_params['roomnums']=isset ( $condit ['nums'] [$result[$k] ['room_info'] ['room_id']] ) ? $condit ['nums'] [$result[$k] ['room_info'] ['room_id']] : 1;
						$point_params['room_id']=$result[$k] ['room_info'] ['room_id'];
						$point_params['price_code']=$result[$k]['state_info'][$rate]['price_code'];
						if (!empty($result[$k]['state_info'][$rate]['pms_point'])){
						    $point_params['extra_para']['pms_point']=$result[$k]['state_info'][$rate]['pms_point'];
						    $point_params['extra_para']['pms_total_point']=$result[$k]['state_info'][$rate]['pms_total_point'];
						}
						$point_pay_set = $this->CI->Member_model->point_pay_check($idents['inter_id'], $point_params);
						if (!empty($point_pay_set['point_need'])){
							$result[$k]['state_info'][$rate]['point_exchange']=$point_pay_set['can_exchange'];
							$result[$k]['state_info'][$rate]['total_point']=$point_pay_set['point_need'];
							$result[$k]['state_info'][$rate]['avg_point']=number_format($result[$k]['state_info'][$rate]['total_point']/$point_params['countday'],1,'.','');
							if(intval($result[$k]['state_info'][$rate]['avg_point'])==$result[$k]['state_info'][$rate]['avg_point']){
								$result[$k]['state_info'][$rate]['avg_point']=intval($result[$k]['state_info'][$rate]['avg_point']);
							}
						}else {
							$result[$k]['state_info'][$rate]['point_exchange']=-1;
						}
					}

					//重新计算最低价，积分兑换价不算入
					if (isset($result[$k]['state_info'][$rate]['point_exchange'])){
						if(isset($result[$k]['state_info'][$rate]['total_point'])&&(empty($result[$k]['lowest_point'])||(!empty($result[$k]['lowest_point'])&&$result[$k]['state_info'][$rate]['total_point']<$result[$k]['lowest_point']))){
							$result[$k]['lowest_point']=$result[$k]['state_info'][$rate]['total_point'];
						}
					}else {
						if(empty($v['lowest'])||($_avg_price>0&&$_avg_price<$v['lowest'])){
//							$v['lowest']=$result[$k]['state_info'][$rate]['avg_price'];
							$v['lowest']=$_avg_price;
						}
						//协议价不缓存作为起始价
						if($t['price_type']!='protrol'){
							if ($_avg_price>0)
								$min_price[]=$_avg_price;
						}else{
							$club_check=$this->CI->Price_code_model->check_special_code($rate,$idents['inter_id']);
							if (!empty($club_check)&&$club_check['type']=='club'){
								$result[$k]['state_info'][$rate]['is_club'] = 1;
								$result[$k]['state_info'][$rate]['price_tags'] = array('club'=>'社群客');
							}else{
								$has_protrol=1;
							}
						}
					}

                    $result[$k]['state_info'][$rate]['wxpay_favour_sign'] = '';
                    if(!empty($t['bookpolicy_condition']['wxpay_favour'])){
                        if(in_array('weixin',$pay_ways) ){
                            if((isset($t['condition']['[no_pay_way]']) && !in_array('weinxin',$t['condition']['[no_pay_way]'])) || !isset($t['condition']['[no_pay_way]'])){
                                $result[$k]['state_info'][$rate]['wxpay_favour_sign'] = 1;
                            }
                        }
                    }

				}
			}
			if ($has_protrol==0&&!empty($v['top_price'])&&in_array('协', $v['top_price'])){
				$tmp=array_flip($v['top_price']);
				unset($result[$k]['top_price'][$tmp['协']]);
			}
			if(!empty($v['lowest'])&&$v['lowest']>0){
				$result[$k]['lowest']=$v['lowest'];
			}
			/*if(!empty($v['lowest'])){
				$lowest = str_replace(',', '', $v['lowest']);
				if($lowest > 0){
					$result[$k]['lowest']=$lowest;
//					$lowest = number_format($lowest, 2, '.', '');
					$this->CI->redis_proxy->hSet($key_date, $k, $lowest);
					$this->CI->redis_proxy->hSet($key, $k, $lowest);
				}
			}*/
			if($min_price && !in_array($this->CI->uri->segment(3), ['saveorder', 'bookroom'])){
			    if (!empty($extra_min_price[$k])){
			        $min_price[]=$extra_min_price[$k];
			    }
				$current_exists[]=$k;
				$lowest=min($min_price);
				$lowest*=1;
				$this->CI->redis_proxy->hSet($key_date, $k, $lowest);
				$this->CI->redis_proxy->hSet($key, $k, $lowest);
			}
            $room_ids[]=$v['room_info']['room_id'];
		}
		
		if(!in_array($this->CI->uri->segment(3), ['saveorder', 'bookroom'])){
			//比较当前缓存的房型，不存在的移除出Redis缓存中，防止起价出错
			$diff_keys_date = array_diff($room_keys_date, $current_exists);
			foreach($diff_keys_date as $z){
				$this->CI->redis_proxy->hDel($key_date, $z);
				$this->CI->redis_proxy->hDel($key, $z);
			}
		}
		/**
		 * 排序 Add By 鹏 On 2016-10-20
		 */
		//房型房价码
		foreach($result as $k => $v){

			$full = [];
			$valid = [];
			if(empty($result[$k]['state_info'])){
				continue;
			}
			foreach($result[$k]['state_info'] as $kt => $t){
				//满房
				if($t['book_status'] == 'full'){
					$full[$kt] = $t;
				} else{
					$valid[$kt] = $t;
				}
			}

			uasort($valid, function ($a, $b){
				//后台设置的排序
				if($a['sort'] != $b['sort']){
					return $b['sort'] > $a['sort']?1:-1;
				}
				//按价格最低
				$a_price = str_replace(',', '', $a['avg_price']);
				$b_price = str_replace(',', '', $b['avg_price']);
				return bcsub($a_price, $b_price, 2) > 0 ? 1 : -1;
			});
			uasort($full, function ($a, $b){
				//后台设置的排序
				if($a['sort'] != $b['sort']){
					return $b['sort'] > $a['sort']?1:-1;
				}
				//按价格最低
				$a_price = str_replace(',', '', $a['avg_price']);
				$b_price = str_replace(',', '', $b['avg_price']);
				return bcsub($a_price, $b_price, 2) > 0 ? 1 : -1;
			});

			//排序后合拼
			$result[$k]['state_info'] = [];
			foreach($valid as $kt => $t){
				$result[$k]['state_info'][$kt] = $t;
			}
			foreach($full as $kt => $t){
				$result[$k]['state_info'][$kt] = $t;
			}
		}

		//对比价
		foreach($result as $k => $v){
			if(empty($result[$k]['state_info'])){
				continue;
			}
            if(!empty($result[$k]['show_info'])) {
                uasort($result[$k]['show_info'], function ($a, $b) {
                    if ($a['sort'] != $b['sort']) {
                        return $b['sort'] > $a['sort'] ? 1 : -1;
                    }
                    $a_price = str_replace(',', '', $a['avg_price']);
                    $b_price = str_replace(',', '', $b['avg_price']);
                    return bcsub($b_price, $a_price, 2) > 0 ? 1 : -1;
                });
            }
		}
		
		if(!empty($result)){
			if(!empty($condit['check_type_label'])){
				$this->CI->load->model('hotel/Label_model');
				$label_check = $this->CI->Label_model->get_hotel_tab_labels($idents['inter_id'], $idents['hotel_id'], 'roomtype', 'room', 'valid', array(
					'tab_ids' => $room_ids,
					'format'  => TRUE
				));
			}
			if(!empty($label_check)){//有标签分类时不将房型重新排列
				$label_rooms = array();
				$has_type_label = array();
				$no_type_label = array();
				foreach($result as $k => $v){
					if(!empty($label_check['labels'][$v['room_info']['room_id']])){
						foreach($label_check['labels'][$v['room_info']['room_id']] as $type_id => $label){
							$v['room_info']['type_label']['id'] = $type_id;
							$v['room_info']['type_label']['label_name'] = $label_check['types'][$type_id]['name'];
							$v['room_info']['type_label']['counts'] = $label_check['types'][$type_id]['counts'];
							$label_rooms[$type_id][$k] = $v;
						}
					}else{
						$no_type_label[$k] = $v;
					}
				}
				foreach($label_check['types'] as $type_id => $type){
					foreach($label_rooms[$type_id] as $k => $v){
						$has_type_label[] = $v;
					}
				}
				$result = $has_type_label + $no_type_label;
				$result = array_values($result);
			}else{
				$full = [];
				$valid = [];
				foreach($result as $k => $v){
					if(empty($result[$k]['state_info'])){
						continue;
					}
					$count = count($v['state_info']);
					if(!$count){
						$full[$k] = $v;
						continue;
					}
					$_exf = [];
					foreach($v['state_info'] as $kt => $t){
						if($t['book_status'] == 'full'){
							$_exf[] = $kt;
						}
					}
					if(count($_exf) == $count){
						$full[$k] = $v;
						continue;
					}
					$valid[$k] = $v;
				}
				
				uasort($valid, function($a, $b){
					//后台排序
					if($a['room_info']['sort'] != $b['room_info']['sort']){
						return $b['room_info']['sort'] > $a['room_info']['sort']?1:-1;
					}
					
					//价格排序
					$a_price = str_replace(',', '', $a['lowest']);
					$b_price = str_replace(',', '', $b['lowest']);
					return bcsub($a_price, $b_price, 2) > 0 ? 1 : -1;
				});
				uasort($full, function($a, $b){
					//后台排序
					if($a['room_info']['sort'] != $b['room_info']['sort']){
						return $b['room_info']['sort'] > $a['room_info']['sort']?1:-1;
					}
					
					//价格排序
					$a_price = str_replace(',', '', $a['lowest']);
					$b_price = str_replace(',', '', $b['lowest']);
					return bcsub($a_price, $b_price, 2) > 0 ? 1 : -1;
				});
				//合并
				$result = [];
				foreach($valid as $k => $v){
					$result[$k] = $v;
				}
				
				foreach($full as $k => $v){
					$result[$k] = $v;
				}
			}
		}

		reset($result);
		if ($check_package){
		    return array('rooms'=>$result,'packages'=>$packages);
		}
		//起价缓存保存7天
		$this->CI->redis_proxy->expire($key, 86400 * 7);
		//入住当天起价缓存只保存至入住当天过后24小时
		$this->CI->redis_proxy->expireAt($key_date, strtotime($condit['startdate']) + 86400+3);
		return $result;
	}
	public function order_submit($inter_id, $orderid, $params = array()){
		return $this->obj->order_submit($inter_id, $orderid, $params);
	}
	public function add_web_bill($order, $params = array()){
		return $this->obj->add_web_bill($order, $params);
	}
	public function cancel_order($inter_id, $params = array()){
		return $this->obj->cancel_order($inter_id, $params);
	}
	public function check_openid_member($inter_id, $openid, $paras = array()){
		if ($this->pms_set ['pms_member_way'] == 2)
			$this->obj = new default_service (array(
				                                  'pms_set' => $this->pms_set
			                                  ));
		return $this->obj->check_openid_member($inter_id, $openid, $paras);
	}
	public function update_web_order($inter_id, $order, $params = array()){
		return $this->obj->update_web_order($inter_id, $order, $params);
	}

	public function check_order_canpay($order, $params = array()){
		return $this->obj->check_order_canpay($order, $params);
	}

	public function get_pms_set($inter_id, $hotel_id){
		$db = $this->CI->load->database('iwide_r1',true);
		$db->where(array(
			                     'inter_id'    => $inter_id,
			                     'pms_type !=' => ''
		                     ));
		$db->where('hotel_id', $hotel_id); // hotel_id传0时，为公众号默认设置
		return $db->get_where('hotel_additions')->row_array();
	}
	public function __call($func, $args){
		static $funcs = array(
			'getMemberByOpenId',
			'getMemberById',
			'getMemberList',
			'deleteMemberByOpenId',
			'initMember',
			'createMember',
			'updateMemberByOpenId',
			'updateCode',
			'updateGrowth',
			'addGrowth',
			'reduceGrowth',
			'updateBalance',
			'addBalance',
			'reduceBalance',
			'updateBonus',
			'refund',
			'addBonusByRule',
			'addBonus',
			'reduceBonus',
			'updateLevel',
			'updateStatus',
			'updateValidity',
			'upgradeLevel',
			'getAllMemberLevels',
			'getMemberLevel',
			'getMemberDetailByOpenId',
			'getMemberDetailByMemId',
			'getMemberDetailList',
			'getMemberDetailListNumber',
			'getMemberInfoByOpenId',
			'getMemberInfoByMemId',
			'getMemberInfoList',
			'deleteMemberInfoByOpenId',
			'addMemberInfo',
			'updateMemberInfoCardNumber',
			'updateMemberInfoName',
			'updateMemberInfoSex',
			'updateMemberInfoDob',
			'updateMemberInfoTelephone',
			'updateMemberInfoQQ',
			'updateMemberInfoEmail',
			'updateMemberInfoById',
			'updateMemberInfoIdcard',
			'updateMemberInfoAddress',
			'updateMemberInfoCustom',
			'checklogin',
			'registerMember',
			'sendSms',
			'sendBgySms',
			'sendSetPassword',
			'checkSendSms',
			'modPassword',
			'modifiedMember',
			'getBonusRecords',
			'getBalanceRecords',
			'loginGetUserinfo',
			// 'checkCenterHeader',
			'updatePassWordin',
			'newchecklogin',
			'unBindMemberCard',//解绑会员卡
			'loginWithOpenid',//通过openid自动登录
			'getPmsMemberCard',
			'getIgetcard', //卡券列表
			'couponCardList',
			'sycMemberInfo'
		);

		if(in_array($func, $funcs)){
			if ($this->pms_set ['pms_member_way'] == 2)
				$this->obj = new default_service (array('pms_set' => $this->pms_set,));
			//@Editor lGh 2016-4-14 11:59:47
			if(!method_exists($this->obj, $func)){
				$default_obj = new default_service (array('pms_set' => $this->pms_set));
				return $default_obj->$func($args [0]);
			}
			return $this->obj->$func($args [0]);
		}

		//@Editor lGh 2016-4-20 17:46:56
		static $hotel_funcs = array(
			'search_hotel_front',
			'get_hotel_citys',
			'getRulesByParams',
			'get_hotel_comment_count',
			'get_city_filter',
			'get_hotel_comments',
			'add_comment',
			'get_order_state',
			'get_hotel_extra_info',
			'return_room_detail',
			'get_user_front_marks',//获取收藏
			'add_fav_to_pms',//添加收藏
			'remove_fav',//移除收藏
			'get_type_mark',//根据条件获取收藏
			'updateGcardStatus',
			'check_order_canpay',
			'order_checkin_type',
		    'continue_order_item'
		);
		if(in_array($func, $hotel_funcs)){
			if(!method_exists($this->obj, $func)){
				$default_obj = new default_service (array('pms_set' => $this->pms_set));
				return $default_obj->$func($args);
			}
			return $this->obj->$func($args);
		}
	}

	public function getWebServ(){
		return $this->obj;
	}
	public function format_room_price($state, $config = array(), $roomnums = 1, $countdays = 1) {
	    if (isset ( $config ['decimal_set'] )) {
	        if ($config ['decimal_set'] == 1) {
	            $state ['avg_price'] *= 1;
	            $state ['total_price'] *= 1;
	            $state ['total'] *= 1;
	            $allprice = '';
	            foreach ( explode ( ',', $state ['allprice'] ) as $p ) {
	                $allprice .= ',' . ($p * 1);
	            }
	            $state ['allprice'] = substr ( $allprice, 1 );
	            foreach ( $state ['date_detail'] as $dtk => $dtd ) {
	                $state ['date_detail'] [$dtk] ['price'] *= 1;
	            }
	        } else if ($config ['decimal_set'] == 2) {
	            $allprice = '';
	            $total = 0;
	            $total_price = 0;
	            foreach ( $state ['date_detail'] as $dtk => $dtd ) {
	                if ($state ['date_detail'] [$dtk] ['price']>=1){
	                    $state ['date_detail'] [$dtk] ['price'] = intval ( $state ['date_detail'] [$dtk] ['price'] );
	                }else{
	                    $state ['date_detail'] [$dtk] ['price'] *= 1;
	                }
	                $allprice .= ',' . $state ['date_detail'] [$dtk] ['price'];
	                $total += $state ['date_detail'] [$dtk] ['price'];
	            }
	            $avg_price = intval ( $total / $countdays );
	            $state ['avg_price'] = $avg_price > 0 ? $avg_price : number_format ( $total / $countdays, 2, '.', '' );
	            $state ['total_price'] = $total * $roomnums;
	            $state ['total'] = $total;
	            $state ['allprice'] = substr ( $allprice, 1 );
	        }
	    }
	    return $state;
	}
}