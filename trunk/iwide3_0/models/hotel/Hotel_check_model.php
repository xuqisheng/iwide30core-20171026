<?php
class Hotel_check_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_H = 'hotels';
	function get_near_hotel($inter_id, $longitude, $latitude, $nums = null) {
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->helper ( 'calculate' );
		$hotels = $this->Hotel_model->get_all_hotels ( $inter_id, 1 );
		$count = count ( $hotels );
		for($i = 0; $i < $count; $i ++) {
			$hotels [$i] ['distance'] = get_distance ( $hotels [$i] ['longitude'], $hotels [$i] ['latitude'], $longitude, $latitude );
		}
		$hotels = $this->Hotel_model->sort_dyd_array ( $hotels, 'distance', 'gt', $nums );
		return $hotels;
	}

	/**
	 *
	 * @param unknown $inter_id
	 * @param object $hotel
	 * @param array $info_types
	 */
	function get_extra_info($inter_id, $hotel, $info_types = array(), $params = array(),$openid='') {

        if (!empty($params['sort_type'])){
            switch ($params['sort_type']){
                case 'price_up':
                    $info_types[]='lowest_price';
                    break;
                case 'good_rate':
                    $info_types[]='comment_data';
                    break;
                default:
                    break;
            }
        }

		$info_types = array_unique ( $info_types );
		$hotel_ids = array ();
		foreach ( $hotel as $h ) {
			$hotel_ids [] = $h->hotel_id;
		}
		$first_hotel = current ( $hotel );
		foreach ( $info_types as $it ) {
			switch ($it) {
				case 'hotel_service' :
					$this->load->model ( 'hotel/Image_model' );
					$imgs = $this->Image_model->get_hotels_img ( $inter_id, $hotel_ids, 'hotel_service' );
					foreach ( $hotel as $h ) {
						$h->service = empty ( $imgs [$h->hotel_id] ['hotel_service'] ) ? array () : $imgs [$h->hotel_id] ['hotel_service'];
					}
					break;
				case 'lowest_price' :
					$lowed = array ();
					if (! isset ( $first_hotel->lowest )) {
						$this->load->model ( 'hotel/Order_model' );
						$lowests = $this->Order_model->get_lowest_price ( $inter_id, array (
							'startdate' => $params ['startdate'],
							'enddate' => $params ['enddate'],
							'hotel_ids' => implode ( ',', $hotel_ids ),
							'member_level'=>$params['member_level'],
							'price_codes' => isset($params ['price_codes'])? $params ['price_codes']:null,
						) );
						foreach ( $hotel as $rt ) {
							$rt->lowest = empty ( $lowests [$rt->hotel_id] ) ? 0 : $lowests [$rt->hotel_id];
						}
					}
					break;
				case 'search_icons' :
					$this->load->model ( 'hotel/Image_model' );
					$icons = $this->Image_model->get_hotels_icon ( $inter_id, $hotel_ids, 'ICONS_IMG_SERACH_RESULT' );
					foreach ( $hotel as $h ) {
						empty ( $icons [$h->hotel_id] ['ICONS_IMG_SERACH_RESULT'] ) ?  : $h->search_icons = $icons [$h->hotel_id] ['ICONS_IMG_SERACH_RESULT'];
					}
					break;
				case 'comment_data' :
					$commented = array ();
					if (isset ( $first_hotel->comment_data )) {
						foreach ( $hotel as $h ) {
							$commented [$h->hotel_id] = $h->comment_data;
						}
					}
					$this->load->model ( 'hotel/Hotel_cache_model' );
					$comment_data = $this->Hotel_cache_model->get_cache ( $inter_id, 'comment_data', array (
						'hotel_ids' => $hotel_ids,
						'commented' => $commented
					) );
                    $this->load->model ( 'hotel/Comment_model' );
                    $score = $this->Comment_model->get_hotel_score_from_redis($rt->inter_id,$hotel_ids,$openid);
					foreach ( $hotel as $rt ) {
                            $h_score = json_decode($score[$rt->hotel_id]);
                            if(!isset($h_score->good_rate)){
                                $h_score->good_rate = 100;
                            }
                            $tmp = explode ( ':', $comment_data [$rt->hotel_id] ['value'] );
                            $rt->comment_data = array (
                                'comment_count' => $h_score->score_count,
                                'comment_score' => round($h_score->score,1),
                                'score_count' => $h_score->score_count,
                                'good_rate' => $h_score->good_rate
                            );
                        }
					break;
				case 'distance' : // 计算距离，需总在最后一个case
					$sort_type = empty ( $params ['distance_sort'] ) ? '' : $params ['distance_sort'];
					$slice = isset ( $first_hotel->distance ) ? 1 : 0;
					$hotel = $this->get_hotel_distance ( $hotel, $params ['latitude'], $params ['longitude'], $sort_type );
					if (! empty ( $params ['check_distance'] ) && ! empty ( $params ['nums'] ) && $slice == 0 && ! empty ( $sort_type )) {
						$hotel = array_slice ( $hotel, $params ['offset'], $params ['nums'] );
					}
					break;
				default :
					break;
			}
		}

        if (!empty($params['sort_type'])){
            switch ($params['sort_type']){
                case 'price_up':
                    uasort ( $hotel, function ($a, $b) {
                        return $b->lowest >= $a->lowest ? 1 : - 1;
                    } );
                    if (! empty ( $params ['nums'] ) ) {
                        $hotel = array_slice ( $hotel, $params ['offset'], $params ['nums'] );
                    }
                    break;
                case 'price_down':
                    uasort ( $hotel, function ($a, $b) {
                        return $b->lowest <= $a->lowest ? 1 : - 1;
                    } );
                    if (! empty ( $params ['nums'] ) ) {
                        $hotel = array_slice ( $hotel, $params ['offset'], $params ['nums'] );
                    }
                    break;
                case 'good_rate':
                    uasort ( $hotel, function ($a, $b) {
                        return $b->comment_data['good_rate'] >= $a->comment_data['good_rate'] ? 1 : - 1;
                    } );
                    if (! empty ( $params ['nums'] ) ) {
                        $hotel = array_slice ( $hotel, $params ['offset'], $params ['nums'] );
                    }
                    break;
                case 'comment_score':
                case 'comment_score_up':
                case 'comment_score_down':
                    $this->load->model ( 'hotel/Comment_model' );
                    $score = $this->Comment_model->get_hotel_score_from_redis($rt->inter_id,$hotel_ids,$openid);
                    foreach($hotel as $key => $arr){
                        if(!empty($score->$arr->hotel_id)){
                            $score = json_decode($score->$arr->hotel_id);
                            $hotel[$key]->score = $score->score;
                        }else{
                            $hotel[$key]->score = 0;
                        }
                    }
                    if ($params['sort_type']=='comment_score_down'){
                        uasort ( $hotel, function ($a, $b) {
                            return $b->comment_data['comment_score'] <= $a->comment_data['comment_score'] ? 1 : - 1;
                        } );
                    }else {
                        uasort ( $hotel, function ($a, $b) {
                            return $b->comment_data['comment_score'] >= $a->comment_data['comment_score'] ? 1 : - 1;
                        } );
                    }
                    if (! empty ( $params ['nums'] ) ) {
                        $hotel = array_slice ( $hotel, $params ['offset'], $params ['nums'] );
                    }
                    break;
                default:
                    break;
            }
        }
		return $hotel;
	}
	function search_hotel_front($inter_id, $paras, $pms_set = array()) {
		$db_read = $this->load->database('iwide_r1',true);
		$extra_where='';
		if (!empty($paras['extra_condition'])){
			$extra_condition = json_decode ( $paras ['extra_condition'], TRUE );
			if (isset($extra_condition['tag'])){
				$tag_id=$extra_condition['tag'];
				$this->load->model('hotel/Tag_model');
				$tag_hotels=$this->Tag_model->get_tag_hotel($inter_id,$tag_id,1);
				if (!empty($tag_hotels)){
					$first_tag=current($tag_hotels);
					if ($first_tag['in_city']==0){
						unset($paras ['city']);
					}
					$extra_where.=' and hotel_id in ('.implode(',', array_column($tag_hotels, 'hotel_id')).')';
				}else {
					return array();
				}
				unset($paras ['keyword']);
			}
		}
		
		$s = 'select * from ' . $db_read->dbprefix ( self::TAB_H );
		$s .= ' where inter_id = "' . $inter_id . '" and status=1 ';
		if (isset ( $paras ['city'] ) || isset ( $paras ['keyword'] )) {
			if (isset ( $paras ['city'] )) {
				$s .= ' and (city like "%' . $paras ['city'] . '%" or CONCAT(city,"市") like "%' . $paras ['city'] . '%")';
			}
			if (isset ( $paras ['keyword'] )) {
				$s .= 'and ( name like "%' . $paras ['keyword'] . '%" or address like "%' . $paras ['keyword'] . '%" or tel like "%' . $paras ['keyword'] . '%" )';
			}
		}

        if(isset($paras['area']) && !empty($paras['area'])){
            $s .=' and area = "' . $paras ['area'] . '"';
        }
		
		if ($extra_where){
			$s .=$extra_where;
		}
		
		$s .= " order by sort desc ";
		if (! empty ( $paras ['nums'] ) && empty ( $paras ['check_distance'] ) && empty ( $paras ['sort_type'] )) {
			$s .= ' limit ' . $paras ['offset'] . ',' . $paras ['nums'];
		}
		$hotels = $db_read->query ( $s )->result ();
		$hotel_ids=array();
		if (!empty($hotels)){
			foreach ($hotels as $h){
				$hotel_ids[$h->hotel_id]=$h->hotel_id;
			}
		}
		//时租房过滤没有设置时租价的酒店
		if(!empty($paras['type'])){
			$this->load->model('hotel/Price_code_model');
			$type_counts=$this->Price_code_model->check_type_exist($inter_id,$hotel_ids,$paras['type'],'valid');
			foreach ( $hotels as $k => $h ) {
// 				$count = $db_read->query ( "select * from " . $db_read->dbprefix ( 'hotel_price_set' ) . " ps left join (select * from " . $db_read->dbprefix ( 'hotel_price_info' ) . " where inter_id='$inter_id') pi on ps.price_code = pi.price_code where ps.inter_id='$inter_id' and pi.type = '".$paras['type']."' and hotel_id=" . $h->hotel_id )->num_rows ();
				if(empty($type_counts[$h->hotel_id])){
					unset($hotels[$k]);
					unset($hotel_ids[$h->hotel_id]);
				}
			}
		}
		if (empty ( $paras ['sort_type'] )){
			$this->load->model('hotel/Order_check_model');
			$order_counts=$this->Order_check_model->get_order_status_count($inter_id,$hotel_ids);
			foreach ( $hotels as $h ) {
				$h->total = isset($order_counts[$h->hotel_id])?$order_counts[$h->hotel_id]:0;
			}
			uasort ( $hotels, function ($a, $b) {
				return $b->total > $a->total ? 1 : - 1;
			} );
	        uasort ( $hotels, function ($a, $b) {
	        	if ($b->sort == $a->sort)
	        		return 0;
	            return $b->sort > $a->sort ? 1 : - 1;
	        } );
		}
		return $hotels;
	}
	function get_hotel_distance($hotels, $latitude, $longitude, $sort = '') {
		$this->load->helper ( 'calculate' );
		foreach ( $hotels as $h ) {
			if (! isset ( $h->distance )) {
				$h->distance = number_format ( get_distance ( $h->longitude, $h->latitude, $longitude, $latitude ), 2, '.', '' );
			}
		}
		if ($sort == 'gt') {
			uasort ( $hotels, function ($a, $b) {
				return $b->distance > $a->distance ? 1 : - 1;
			} );
		} else if ($sort == 'lt') {
			uasort ( $hotels, function ($a, $b) {
				return $b->distance < $a->distance ? 1 : - 1;
			} );
		}
		return $hotels;
	}
	function get_hotel_citys($inter_id, $params = array()) {
		$db_read = $this->load->database('iwide_r1',true);
		// $sql = "SELECT DISTINCT `city`, getPY(LEFT(`city`, 1)) py
		$sql = "SELECT DISTINCT `city`, name py,count(city) hotel_num
			   FROM " . $db_read->dbprefix ( self::TAB_H ) . " WHERE `inter_id` = '$inter_id' AND `status` =1 AND `city` != '' group by city ORDER BY `py`";
		$city = $db_read->query ( $sql )->result ();
		$py = array ();
		$this->load->helper ( 'string' );
		foreach ( $city as $c ) {
			$city_py = get_first_py ( $c->city );
			$py [$city_py] [] = array (
				'city' => $c->city,
				'hotel_num' => $c->hotel_num
			);
		}
		ksort ( $py );
		return $py;
	}
    function get_hotel_area($inter_id, $params = array()) {
        $db_read = $this->load->database('iwide_r1',true);
        // $sql = "SELECT DISTINCT `city`, getPY(LEFT(`city`, 1)) py
        $sql = "SELECT DISTINCT `area`,`city`, name py,count(city) hotel_num
			   FROM " . $db_read->dbprefix ( self::TAB_H ) . " WHERE `inter_id` = '$inter_id' AND `status` =1 AND `city` != '' group by area ORDER BY `py`";
        $area = $db_read->query ( $sql )->result ();
        $py = array ();
        $this->load->helper ( 'string' );
        foreach ( $area as $c ) {
            if(!empty($c->area)){
                $area_py = get_first_py ( $c->area );
                $py [$area_py] [] = array (
                    'area' => $c->area,
                    'hotel_num' => $c->hotel_num,
                    'city'=>$c->city
                );
            }
        }
        ksort ( $py );
        return $py;
    }
	function get_city_filter($inter_id, $city, $params = array()) {
		$adapter = $this->get_hotel_adapter ( $inter_id, 0 );
		return $adapter->get_city_filter ( $inter_id, $city, $params );
	}
	
	function get_city_filter_local($inter_id, $city, $params = array()){
		$this->load->model('hotel/Tag_model');
		$filter=array();
		$filter['tag']=$this->Tag_model->get_city_tag($inter_id, $city,1, $params);
		return $filter;
	}
	function get_hotel_adapter($inter_id, $hotel_id, $refresh = FALSE) {
		if ($hotel_id == 0) {
			if (isset ( $this->pub_pmsa ) && ! $refresh) {
				return $this->pub_pmsa;
			}
			unset ( $this->pub_pmsa );
			$this->load->library ( 'PMS_Adapter',array (
				                                    'inter_id' => $inter_id,
				                                    'hotel_id' => $hotel_id
			                                    ) );
			$this->pub_pmsa = new PMS_Adapter ( array (
				                                    'inter_id' => $inter_id,
				                                    'hotel_id' => $hotel_id
			                                    ) );
			return $this->pub_pmsa;
		} else {
			$nick = 'pmsa';
			if (isset ( $this->$nick ) && ! $refresh) {
				return $this->$nick;
			}
			unset ( $this->$nick );
			$this->load->library ( 'PMS_Adapter',array (
				                                    'inter_id' => $inter_id,
				                                    'hotel_id' => $hotel_id
			                                    ) );
			$this->$nick = new PMS_Adapter ( array (
				                                 'inter_id' => $inter_id,
				                                 'hotel_id' => $hotel_id
			                                 ) );
			return $this->$nick;
		}
	}
}