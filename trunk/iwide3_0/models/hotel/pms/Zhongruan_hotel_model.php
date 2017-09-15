<?php
class Zhongruan_hotel_model extends CI_Model {
    function __construct() {
        parent::__construct ();
    }
    const WEB_TYPE = 'zhongruan';
    function test() {
    }
    function get_rooms_change($rooms, $idents, $condit, $pms_set = array()) {
	    $this->load->helper ('common');
    	statistic('G1');
        $pms_set ['pms_auth'] = json_decode ( $pms_set ['pms_auth'], TRUE );
        $this->load->model ( 'common/Webservice_model' );
        $web_reflect = $this->Webservice_model->get_web_reflect ( $idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], array (
                'member_level',
                'web_price_code',
                'member_price_code',
                'web_price_code_set' 
        ), 1, 'w2l' );
        $member_level = isset ( $web_reflect ['member_level'] [$condit ['member_level']] ) ? $web_reflect ['member_level'] [$condit ['member_level']] : '';
        // $this->db->insert('weixin_text',array('content'=>'biguiyuan+'.$member_level,'edit_date'=>date('Y-m-d H:i:s')));
	    statistic('G2');
        $web_price_code = '';
        if (! empty ( $condit ['price_codes'] )) {
            $web_price_code = $condit ['price_codes'];
            
            //对接模式是本地价格代码时，读取对应的external_code值【PMS价格代码】
            if($pms_set['pms_room_state_way'] == 3 || $pms_set['pms_room_state_way'] == 4){
                $web_code_arr = [];
                $price_code_list = $this->readDB()->from('hotel_price_info')->select('external_code')->where(['inter_id' => $pms_set['inter_id']])->where_in('price_code', explode(',', $condit['price_codes']))->get()->result_array();
                foreach($price_code_list as $v){
                    $web_code_arr[] = $v['external_code'];
                }
                if($web_code_arr){
                    $web_price_code = implode(',', $web_code_arr);
                }
            }

        } else {
            if (! empty ( $web_reflect ['web_price_code'] )) {
                foreach ( $web_reflect ['web_price_code'] as $wpc ) {
                    $web_price_code .= ',' . $wpc;
                }
            }
            $web_price_code .= isset ( $web_reflect ['member_price_code'] [$member_level] ) ? ',' . $web_reflect ['member_price_code'] [$member_level] : '';
            $web_price_code = substr ( $web_price_code, 1 );
        }
        // $web_price_code='08,09,10,11,27,28,01,07,31,32,33';
        $web_price_code = explode ( ',', $web_price_code );
        $countday = get_room_night ( $condit ['startdate'], $condit ['enddate'], 'ceil', $condit ); // 至少有一个间夜
        $web_rids = array ();
        foreach ( $rooms as $r ) {
            $web_rids [$r ['webser_id']] = $r ['room_id'];
        }
	    statistic('G3');
        $params = array (
                'countday' => $countday,
                'web_rids' => $web_rids,
                'condit' => $condit,
                'web_reflect' => $web_reflect,
                'idents' => $idents 
        );
        
        switch ($pms_set ['pms_room_state_way']) {
            case 1 :
            case 2 :
                if (! empty ( $web_price_code )) {
                    $pms_data = $this->get_web_roomtype ( $pms_set, $web_price_code, $condit ['startdate'], $condit ['enddate'], $params );
                }
                return $this->get_rooms_change_allpms ( $pms_data, array (
                        'rooms' => $rooms 
                ), $params );
                break;
            case 3 :
                $this->load->model ( 'hotel/Order_model' );
                $data = $this->Order_model->get_rooms_change ( $rooms, $params ['idents'], $params ['condit'] );
	            statistic('G4');
                /*$price_code=array();
                 foreach($data as $data_arr){
                 foreach($data_arr['state_info'] as $room_arr){
                 if(!in_array($room_arr['external_code'],$price_code)){
                 $price_code[]=$room_arr['external_code'];
                 }
                 }
                 }*/
                // 只有一个价格代码时不读取缓存
                if (count ( $web_price_code ) > 1) {
                    /*$price_code_list = $this->readDB ()->from ( 'hotel_price_info' )->select ( 'external_code' )->where ( [
                            'inter_id' => $pms_set ['inter_id'],
                            'status' => 1 
                    ] )->get ()->result_array ();
                    $price_code = [ ];
                    foreach ( $price_code_list as $v ) {
                        $price_code [] = $v ['external_code'];
                    }
                    $price_code = array_unique ( $price_code );*/
	
	                $price_code=[];
	                if($pms_set['pms_room_state_way'] == 3 || $pms_set['pms_room_state_way'] == 4){
		                $web_code_arr = [];
		                $price_code_list = $this->readDB()->from('hotel_price_info')->select('external_code')->where([
			                'inter_id'        => $pms_set['inter_id'],
			                'status'          => 1,
			                'external_code!=' => ''
		                ])->get()->result_array();
		                foreach($price_code_list as $v){
			                $web_code_arr[] = $v['external_code'];
		                }
		                if($web_code_arr){
			                $price_code = array_unique($web_code_arr);
		                }
	                }
	                
                } else {
                    $price_code = $web_price_code;
                }
	            statistic('G5');
                $pms_data_lmem = $this->get_web_roomtype ( $pms_set, $price_code, $condit ['startdate'], $condit ['enddate'], $params );
                statistic('G6');
                $return = $this->get_rooms_change_lmem ( $pms_data_lmem, array (
                        'rooms' => $rooms 
                ), $params, $data );
	            statistic('G7');
	            $timer_arr=[
	            	'webservice本地配置'=>statistic('G1','G2'),
		            '请求价格代码'=>statistic('G2','G3'),
		            '本地房态'=>statistic('G3','G4'),
		            '筛选价格代码'=>statistic('G4','G5'),
		            'PMS房价'=>statistic('G5','G6'),
		            'PMS房价与本地房价匹配'=>statistic('G6','G7'),
	            ];
	            $this->load->model ( 'common/Webservice_model' );
	
	            $openid = $this->session->userdata ( $pms_set ['inter_id'] . 'openid' );
	            $this->Webservice_model->add_webservice_record ( $pms_set ['inter_id'], self::WEB_TYPE, __METHOD__, $params, $timer_arr, 'query_time', time(), microtime(), $openid );
	            
	            return $return;
                break;
            default :
                return array ();
                break;
        }
    }
    function get_rooms_change_allpms($pms_state, $rooms, $params) {
        $data = array ();
        foreach ( $rooms ['rooms'] as $rm ) {
            if (! empty ( $pms_state ['pms_state'] [$rm ['webser_id']] )) {
                $data [$rm ['room_id']] ['room_info'] = $rm;
                $data [$rm ['room_id']] ['state_info'] = empty ( $pms_state ['pms_state'] [$rm ['webser_id']] ) ? array () : $pms_state ['pms_state'] [$rm ['webser_id']];
                $data [$rm ['room_id']] ['show_info'] = array ();
                $data [$rm ['room_id']] ['lowest'] = min ( $pms_state ['exprice'] [$rm ['webser_id']] );
                $data [$rm ['room_id']] ['highest'] = max ( $pms_state ['exprice'] [$rm ['webser_id']] );
            }
        }
        return $data;
    }
    function get_rooms_change_lmem($pms_data, $rooms, $params, $data) {
        $condit = $params ['condit'];
        $pms_state = $pms_data ['pms_state'];
        foreach ( $data as $room_key => $lrm ) {
            
            $min_price = array ();
            // if (empty ( $pms_state [$lrm ['room_info'] ['webser_id']] )) {
            // unset ( $data [$room_key] );
            // continue;
            // }
            if (! empty ( $lrm ['state_info'] )) {
                foreach ( $lrm ['state_info'] as $sik => $si ) {
                    
                    // if (isset ( $member_level ) && ! empty ( $condit ['member_privilege'] ) && isset ( $si ['condition'] ['member_level'] ) && array_key_exists ( $si ['condition'] ['member_level'], $condit ['member_privilege'] )) {
                    $external_code = '';
                    if ($si ['external_code'] !== '') {
                        $external_code = $si ['external_code'];
                        // $external_code_reflect = $external_code;
                        // $external_code_reflect = $params ['web_reflect'] ['member_price_code'] [$external_code];
                    }
                    
                    if (! empty ( $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code] )) {
                        
                        $tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code];
                        // $otmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code];
                        $nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
                        
                        if (! empty ( $data [$room_key] ['state_info'] [$sik] ['condition'] ['mxn'] ) && $data [$room_key] ['state_info'] [$sik] ['condition'] ['mxn'] > 0 && $data [$room_key] ['state_info'] [$sik] ['condition'] ['mxn'] < $tmp ['least_num']) {
                            $tmp ['least_num'] = $data [$room_key] ['state_info'] [$sik] ['condition'] ['mxn'];
                        }
                        
                        $tmp ['least_num'] = $si ['least_num'] <= $tmp ['least_num'] ? $si ['least_num'] : $tmp ['least_num'];
                        
                        $data [$room_key] ['state_info'] [$sik] ['least_num'] = $tmp ['least_num'];
                        if ($tmp ['least_num'] <= 0)
                            $tmp ['book_status'] = 'full';
                        $data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
                        
                        // $data [$room_key] ['state_info'] [$sik] ['extra_info'] ['channel_code'] = $price_level;
                        $allprice = '';
                        $amount = 0;
                        foreach ( $tmp ['date_detail'] as $dk => $td ) {
                            
                            if ($data [$room_key] ['state_info'] [$sik] ['price_type'] == 'member') {
                                $tmp ['date_detail'] [$dk] ['price'] = round ( $this->Order_model->cal_related_price ( $td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price' ) );
                            } else {
                                // $tmp ['date_detail'] [$dk] ['price'] = round ( $data [$room_key] ['state_info'] [$sik] ['date_detail'] [$dk] ['price'] );
                                // print_r( $tmp ['date_detail'] [$dk] ['price']);exit;
                            }
                            
                            //智能定价优先
                            if (!empty($data [$room_key] ['state_info'] [$sik] ['date_detail'][$dk]['type'])&&$data [$room_key] ['state_info'] [$sik] ['date_detail'][$dk]['type']=='parity'){
                                $tmp ['date_detail'] [$dk] ['price']=$data [$room_key] ['state_info'] [$sik] ['date_detail'][$dk]['price'];
                                $tmp ['extra_info']['parity'][$dk]=$tmp ['date_detail'] [$dk] ['price'];
                            }else if ($data [$room_key] ['state_info'] [$sik] ['related_code'] != 0 && ! empty ( $data [$room_key] ['state_info'] [$sik] ['related_cal_way'] )) {
                                $tmp ['date_detail'] [$dk] ['price'] = round ( $this->Order_model->cal_related_price ( $td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price' ) );
                            }
                            
                            // $tmp ['date_detail'] [$dk] ['nums'] = $data [$room_key] ['state_info'] [$sik] ['least_num'];
                            $tmp ['date_detail'] [$dk] ['price'] = round ( $tmp ['date_detail'] [$dk] ['price'] );
                            $allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
                            $amount += $tmp ['date_detail'] [$dk] ['price'];
                        }
                        $data [$room_key] ['state_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];
                        $data [$room_key] ['state_info'] [$sik] ['extra_info'] = $tmp ['extra_info'];
                        $data [$room_key] ['state_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['countday'], 1, '.', '' );
                        $data [$room_key] ['state_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
                        $data [$room_key] ['state_info'] [$sik] ['total'] = $amount;
                        $data [$room_key] ['state_info'] [$sik] ['total_price'] = $data [$room_key] ['state_info'] [$sik] ['total'] * $nums;
                        
                        // $min_price [] = $data [$room_key] ['state_info'] [$sik] ['avg_price'];
                        $min_price [] = str_replace ( ',', '', $data [$room_key] ['state_info'] [$sik] ['avg_price'] );
                        // $min_price [] = number_format($data [$room_key] ['state_info'] [$sik] ['avg_price'],2,'.',"");
                        
                        if ($data [$room_key] ['state_info'] [$sik] ['price_type'] == 'member') {
                            $data [$room_key] ['show_info'] [$sik] = $data [$room_key] ['state_info'] [$sik];
                        }
                        $data [$room_key] ['show_info'] = array ();
                    } else {
                        if (! empty ( $external_code )) {
                            unset ( $data [$room_key] ['state_info'] [$sik] );
                        } else {
                            $min_price [] = str_replace ( ',', '', $data [$room_key] ['state_info'] [$sik] ['avg_price'] );
                        }
                    }
                }
                
                $data [$room_key] ['lowest'] = empty ( $min_price ) ? 0 : min ( $min_price );
                $data [$room_key] ['highest'] = empty ( $min_price ) ? 0 : max ( $min_price );
                
                /*foreach ( $lrm ['show_info'] as $sik => $si ) {
                 if ($si ['external_code'] !== '') {
                 $external_code_reflect = $params ['level_reflect'] ['member_level'] [$si ['external_code']];
                 $external_code_reflect = $params ['level_reflect'] ['member_price_code'] [$external_code_reflect];
                 }
                 if (isset ( $external_code_reflect ) && ! empty ( $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect] )) {
                 $tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect];
                 $nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
                 $data [$room_key] ['show_info'] [$sik] ['least_num'] = $tmp ['least_num'];
                 $data [$room_key] ['show_info'] [$sik] ['book_status'] = $tmp ['book_status'];
                 $allprice = '';
                 $amount = 0;
                 foreach ( $tmp ['date_detail'] as $dk => $td ) {
                 $tmp ['date_detail'] [$dk] ['price'] = round ( $this->Order_model->cal_related_price ( $td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price' ) );
                 $tmp ['date_detail'] [$dk] ['nums'] = $tmp ['least_num'];
                 $allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
                 $amount += $tmp ['date_detail'] [$dk] ['price'];
                 }
                 $data [$room_key] ['show_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];
                 
                 $data [$room_key] ['show_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['countday'], 1 );
                 $data [$room_key] ['show_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
                 $data [$room_key] ['show_info'] [$sik] ['total'] = intval ( $amount );
                 $data [$room_key] ['show_info'] [$sik] ['total_price'] = $data [$room_key] ['show_info'] [$sik] ['total'] * $nums;
                 } else {
                 unset ( $data [$room_key] ['show_info'] [$sik] );
                 }
                 }*/
            }
            if (empty ( $data [$room_key] ['state_info'] )) {
                unset ( $data [$room_key] );
            }
        }
        return $data;
    }
	
	function get_web_roomtype($pms_set, $web_price_code, $startdate, $enddate, $params = array()){
		
		$this->load->helper('common');
		
		$result = null;
		/*if(empty ($pms_set ['pms_auth'] ['get_num_way'])){
			$rm_cds = implode(',', array_keys($params ['web_rids']));
			$web_left = $this->get_web_nums($pms_set, date('Y-m-d', strtotime($startdate)), date('Y-m-d', strtotime($enddate)), $rm_cds);
			$params ['web_left'] = $web_left;
		}*/
//		if ($enddate>$startdate){
//		    $enddate=date('Ymd',strtotime('- 1 day ',strtotime($enddate)));
//		}
		if(!empty ($pms_set ['pms_auth'] ['get_hotels_way'])){
			if($pms_set ['pms_auth'] ['get_hotels_way'] == 'multi'){ // 同时获取多个价格代码房态
				$result = $this->get_web_roomtype_multi($pms_set, $web_price_code, $startdate, $enddate, $params);
				/*$redis->set($rk,json_encode($result),25*60);
				 return $result;*/
			}else if($pms_set ['pms_auth'] ['get_hotels_way'] == 'thread'){ // 多线程同时获取一个价格代码房态
			}
		}
		if($result === null){
			$result = $this->get_web_roomtype_single($pms_set, $web_price_code, $startdate, $enddate, $params);
		}
		return $result;
	}
    function get_web_roomtype_single($pms_set, $web_price_code, $startdate, $enddate, $params = array()) {
        $sh_hotels = new HotelInfo ();
        $sh_hotels->arr_dt = date ( 'Y-m-d', strtotime ( $startdate ) );
        $sh_hotels->dpt_dt = date ( 'Y-m-d', strtotime ( $enddate ) );
        $sh_hotels->rp_nm = $pms_set ['pms_auth'] ['rp_nm'];
        $sh_hotels->htlcls = 99;
        $sh_hotels->T_channel = $pms_set ['pms_auth'] ['channel'];
        $sh_hotels->htlcd = $pms_set ['hotel_web_id'];
        $pms_state = array ();
        // $valid_state = array ();
        $exprice = array ();
        $sh_hotels->rm_list [0] = '';
        $web_left = empty ( $params ['web_left'] ) ? array () : $params ['web_left'];
        $pre_day = ceil ( (strtotime ( $startdate ) - strtotime ( date ( 'Ymd' ) )) / 86400 );
        foreach ( $web_price_code as $code ) {
            $sh_hotels->rp_cd = $code;
            $web_set = array ();
            
            // 判断提前预订和连住
            if (isset ( $params ['web_reflect'] ['web_price_code_set'] [$code] )) {
                $web_set = json_decode ( $params ['web_reflect'] ['web_price_code_set'] [$code], TRUE );
            }
            if (isset ( $web_set ['pre_d'] ) && (($pre_day < $web_set ['pre_d']) || ($web_set ['pre_d'] == 0 && $pre_day != 0))) {
                continue;
            }
            if (! empty ( $web_set ['mnd'] ) && $web_set ['mnd'] > $params ['countday']) {
                continue;
            }
            
            $result = $this->Get_Hotels ( $pms_set, $sh_hotels );
            if (! empty ( $result ['rooms'] )) {
                foreach ( $result ['rooms'] as $r ) {
                    $min_price = array ();
                    if (! empty ( $r->drt_amt )) {
                        $pms_state [$r->rm_cd] [$code] ['price_name'] = $result ['code_name'];
                        $pms_state [$r->rm_cd] [$code] ['price_type'] = 'pms';
                        $pms_state [$r->rm_cd] [$code] ['extra_info'] = array (
                                'type' => 'code',
                                'pms_code' => $code
                        );
                        $pms_state [$r->rm_cd] [$code] ['price_code'] = $code;
                        $pms_state [$r->rm_cd] [$code] ['des'] = '';
                        $pms_state [$r->rm_cd] [$code] ['sort'] = 0;
                        $pms_state [$r->rm_cd] [$code] ['disp_type'] = 'buy';
                        // $web_set = array ();
                        // if (isset ( $params ['web_reflect'] ['web_price_code_set'] [$code] )) {
                        // $web_set = json_decode ( $params ['web_reflect'] ['web_price_code_set'] [$code], TRUE );
                        // }
                        $pms_state [$r->rm_cd] [$code] ['condition'] = $web_set;
                        // $pms_state [$r->rm_cd] [$code] ['condition'] ['pre_pay'] = isset ( $web_set ['pre_pay'] ) ? $web_set ['pre_pay'] : '';
                        // $pms_state [$r->rm_cd] [$code] ['condition'] ['no_pay_way'] = isset ( $web_set ['no_pay_way'] ) ? $web_set ['no_pay_way'] : '';
                        $allprice = '';
                        $amount = '';
                        
                        $left_nums = isset ( $web_left [$r->rm_cd] ['nums'] ) ? $web_left [$r->rm_cd] ['nums'] : $r->rm_avl;
                        
                        $p = explode ( ',', $r->drt_amt ); // 取结果中的每日价格数组
                        for($n = 0; $n < $params ['countday']; $n ++) {
                            $pms_state [$r->rm_cd] [$code] ['date_detail'] [date ( "Ymd", strtotime ( "+" . $n . "day", strtotime ( $startdate ) ) )] = array (
                                    'price' => $p [$n],
                                    'nums' => isset ( $web_left [$r->rm_cd] ['everyday_num'] [$n] ) ? $web_left [$r->rm_cd] ['everyday_num'] [$n] : $left_nums
                            );
                            $allprice .= ',' . $p [$n];
                            $amount += $p [$n];
                        }
                        if (isset ( $params ['web_rids'] [$r->rm_cd] )) {
                            $nums = empty ( $params ['condit'] ['nums'] [$params ['web_rids'] [$r->rm_cd]] ) ? 1 : $params ['condit'] ['nums'] [$params ['web_rids'] [$r->rm_cd]];
                        } else {
                            $nums = 1;
                        }
                        $pms_state [$r->rm_cd] [$code] ['allprice'] = substr ( $allprice, 1 );
                        $pms_state [$r->rm_cd] [$code] ['total'] = $amount;
                        $pms_state [$r->rm_cd] [$code] ['related_des'] = '';
                        $pms_state [$r->rm_cd] [$code] ['total_price'] = $amount * $nums;
                        $pms_state [$r->rm_cd] [$code] ['avg_price'] = number_format ( $amount / $params ['countday'], 2, '.', '' );
                        $pms_state [$r->rm_cd] [$code] ['price_resource'] = 'webservice';
                        // $left_nums = isset ( $web_left [$r->rm_cd] ['nums'] ) ? $web_left [$r->rm_cd] ['nums'] : 0;
                        $pms_state [$r->rm_cd] [$code] ['least_num'] = $left_nums;
                        $book_status = 'full';
                        if ($left_nums >= $nums)
                            $book_status = 'available';
                        $pms_state [$r->rm_cd] [$code] ['book_status'] = $book_status;
                        $exprice [$r->rm_cd] [] = $pms_state [$r->rm_cd] [$code] ['avg_price'];
                        // if ($room_detail ['canBook'] == 1) {
                        // $valid_state [$r->rm_cd] [$code] = $pms_state [$r->rm_cd] [$code];
                        // }
                    }
                }
            }
        }
        return array (
                'pms_state' => $pms_state,
                // 'valid_state' => $valid_state,
                'exprice' => $exprice
        );
    }
    function get_web_roomtype_multi($pms_set, $web_price_code, $startdate, $enddate, $params = array()) {
	
	    //缓存数据
	    $countday = $params['countday'];
	    $this->load->library('Cache/Redis_proxy', array(
		    'not_init'    => FALSE,
		    'module'      => 'common',
		    'refresh'     => FALSE,
		    'environment' => ENVIRONMENT
	    ), 'redis_proxy');
	    $redis = $this->redis_proxy;
	
	    //判断本地缓存中每日都有数据
	    $all_exists = true;
	    $rk_temp = $pms_set['inter_id'] . ':price_lite:' . $params['idents']['hotel_id'] . ':';
	    $sdate = date('Ymd', strtotime($startdate));
	    $edate = date('Ymd', strtotime($enddate));
	
	    for($start = $sdate; $start < $edate;){
		    $rk = $rk_temp . $start;
		    if(!$redis->exists($rk)){
			    $all_exists = false;
			    break;
		    }
		    $start = date('Ymd', strtotime($start) + 86400);
	    }
	
	    $web_room_rate = [];
	
	    $recache_uri = ['bookroom', 'saveorder'];
	    if(!$all_exists || !empty($params['condit']['recache']) || in_array($this->uri->segment(3), $recache_uri)){
		    
		    //读取PMS房价
		    $sh_hotels = new HotelInfo ();
		    $sh_hotels->arr_dt = date ( 'Y-m-d', strtotime ( $startdate ) );
		    $sh_hotels->dpt_dt = date ( 'Y-m-d', strtotime ( $enddate ) );
		    $sh_hotels->rp_nm = $pms_set ['pms_auth'] ['rp_nm'];
		    $sh_hotels->htlcls = 99;
		    $sh_hotels->T_channel = $pms_set ['pms_auth'] ['channel'];
		    $sh_hotels->htlcd = $pms_set ['hotel_web_id'];
		    $sh_hotels->rm_list [0] = '';

		    $sh_hotels->rp_cd = implode ( ',', $web_price_code );
		
		    $web_result = $this->Get_HotelsByBGY ( $pms_set, $sh_hotels );
		
		    $rm_cds = implode(',', array_keys($params ['web_rids']));
		    if(!in_array($this->uri->segment(3), $recache_uri)){
			    $web_qty_result = $this->get_web_nums($pms_set, date('Y-m-d', strtotime($startdate)), date('Y-m-d', strtotime($enddate)), $rm_cds);
		    }else{
		    	$web_qty_result=[];
		    }
	    	$cache_data=[];
		    
		    //删除缓存
		    if(!in_array($this->uri->segment(3), $recache_uri)){
			    for($start = $sdate; $start < $edate;){
				    $rk = $rk_temp . $start;
				    //删除缓存数据，防止接口没有数据返回时，本地仍有缓存
				    $redis->del($rk);
				    $start = date('Ymd', strtotime($start) + 86400);
			    }
		    }
		
	    	if(!empty($web_result)){
			    foreach($web_result as $web_rate_code => $v) {
			    	foreach($v['rooms'] as $t){
					    if(!empty($t->drt_amt )){
						    $daily_price_arr=explode(',', $t->drt_amt); // 取结果中的每日价格数组
						    
						    //每日房量
						    $web_least=isset($web_qty_result[$t->rm_cd]['nums'])?$web_qty_result[$t->rm_cd]['nums']:$t->rm_avl;
						    
						    for($i = 0; $i < $countday; $i++){
							    $in_date = date('Ymd', strtotime($startdate)+86400*$i);
							    //每日价格
							    $cache_data[$in_date][$t->rm_cd]['name'] = $t->rm_nm;
							
							    $cache_data[$in_date][$t->rm_cd]['rates'][$web_rate_code] = [
								    'rate'  => ['code' => $web_rate_code, 'name' => $web_rate_code],
								    'daily' => [
									    'price'    => isset($daily_price_arr[$i]) ? $daily_price_arr[$i] : 0,
									    'quantity' => isset($web_qty_result[$t->rm_cd]['everyday_num'][$i]) ? $web_qty_result[$t->rm_cd]['everyday_num'][$i] : $web_least
								    ]
							    ];
						    }
				        }
				    }
			    }
		    }
	    	
		    foreach($cache_data as $k => $v){
			    //设置本地缓存
			    if(!in_array($this->uri->segment(3), $recache_uri)){
					$rk = $rk_temp . $k;
					//保存到redis的数据,将数组的值转为JSON，避免多次循环
					$redis_data = array_map('json_encode', $v);
					$redis->hMset($rk, $redis_data);
					//记录当前KEY获取缓存的数量
					pms_logger([
						$rk,
						$_SERVER['REQUEST_URI']
					], $v, __METHOD__ . '->set_redis', $pms_set['inter_id']);
					$redis->expire($rk, 7200);
				}
			
			    //组合
			    foreach($v as $web_room => $_row){
				    //每个房型的数据
				    $web_room_rate[$web_room]['name'] = $_row['name'];
				    $web_room_rate[$web_room]['code'] = $web_room;
				    if(!empty($_row['rates'])){
					    foreach($_row['rates'] as $web_rate => $t){
						    if(empty($web_room_rate[$web_room]['rates'][$web_rate])){
							    $web_room_rate[$web_room]['rates'][$web_rate] = $t['rate'];
						    }
						    //每日价格记录
						    $daily_rec = $t['daily'];
						    $daily_rec['in_date'] = $k;
						    $web_room_rate[$web_room]['rates'][$web_rate]['daily'][] = $daily_rec;
					    }
				    }
			    }
		    }
	    }else{
		    for($start = $sdate; $start < $edate;){
			    $rk = $rk_temp . $start;
			
			    $redis_data = $redis->hGetAll($rk);
			    pms_logger([
				    $rk,
				    $_SERVER['REQUEST_URI']
			    ], $redis_data, __METHOD__ . '->get_redis', $pms_set['inter_id']);
			    if($redis_data){
				    foreach($redis_data as $web_room => $v){
					    //每个房型的数据
					    $_row = json_decode($v, true);
					    $web_room_rate[$web_room]['name'] = $_row['name'];
					    $web_room_rate[$web_room]['code'] = $web_room;
					    if(!empty($_row['rates'])){
						    foreach($_row['rates'] as $web_rate => $t){
							    if(empty($web_room_rate[$web_room]['rates'][$web_rate])){
								    $web_room_rate[$web_room]['rates'][$web_rate] = $t['rate'];
							    }
							    //每日价格记录
							    $daily_rec = $t['daily'];
							    $daily_rec['in_date'] = $start;
							    $web_room_rate[$web_room]['rates'][$web_rate]['daily'][] = $daily_rec;
						    }
					    }
				    }
			    }
			
			    $start = date('Ymd', strtotime($start) + 86400);
		    }
	    }
	
	    $pms_state = [];
	    $valid_state = [];
	    $exprice = [];
	
	    if($web_room_rate){
		    foreach($web_room_rate as $web_room => $v){
			    if(!array_key_exists($web_room, $params['web_rids'])){
				    continue;
			    }
			    $pms_state[$web_room] = [];
			    foreach($v['rates'] as $web_rate => $t){
				
				    $pms_state[$web_room][$web_rate]['price_name'] = $t['name'];
				    $pms_state[$web_room][$web_rate]['price_type'] = 'pms';
				    $pms_state[$web_room][$web_rate]['price_code'] = $web_rate;
				    $pms_state[$web_room][$web_rate]['extra_info'] = [
					    'type'     => 'code',
					    'pms_code' => $web_rate,
				    ];
				    $pms_state[$web_room][$web_rate]['des'] = $t['name'];
				    $pms_state[$web_room][$web_rate]['sort'] = 0;
				    $pms_state[$web_room][$web_rate]['disp_type'] = 'buy';
				
				    $web_set = [];
				    if(isset ($params['web_reflect']['web_price_code_set'][$web_rate])){
					    $web_set = json_decode($params['web_reflect']['web_price_code_set'][$web_rate], true);
				    }
				
				    $pms_state[$web_room][$web_rate]['condition'] = $web_set;
				
				    if(isset($params['web_rids'][$web_room]) && isset($params['condit']['nums'][$params['web_rids'][$web_room]])){
					    $nums = $params['condit']['nums'][$params['web_rids'][$web_room]];
				    }else{
					    $nums = 1;
				    }
				
				    $allprice = [];
				    $amount = 0;
				
				    $least_arr = [];
				
				    $date_status = true;
				
				    foreach($t['daily'] as $w){
					    if($w['price']>0){
						    if($w['in_date'] < date('Ymd', strtotime($enddate))){
							
							    $pms_state[$web_room][$web_rate]['date_detail'][$w['in_date']] = [
								    'price' => $w['price'],
								    'nums'  => $w['price'] > 0 ? $w['quantity'] : 0,
							    ];
							
							    $allprice[$w['in_date']] = $w['price'];
							    $amount += $w['price'];
							    $least_arr[] = $w['quantity'];
							
							    $date_status = $date_status && $w['quantity'] > 0 && $w['price'] > 0;
						    }
					    }
				    }
				
				    //校验日期价格
				    $all_exists = true;
				    for($start = date('Ymd', strtotime($startdate)); $start < date('Ymd', strtotime($enddate));){
					    if(empty($pms_state[$web_room][$web_rate]['date_detail'][$start])){
						    $all_exists = false;
						    break;
					    }
					    $start = date('Ymd', strtotime($start) + 86400);
				    }
				
				    //是否所有日期都直接价格代码
				    if(!$all_exists){
					    unset($pms_state[$web_room][$web_rate]);
					    continue;
				    }
				
				    ksort($allprice);
				    $least_count = min($least_arr);
				    $least_count > 0 or $least_count = 0;
				
				    $pms_state[$web_room][$web_rate]['allprice'] = implode(',', $allprice);
				    $pms_state[$web_room][$web_rate]['total'] = $amount;
				    $pms_state[$web_room][$web_rate]['related_des'] = '';
				    $pms_state[$web_room][$web_rate]['total_price'] = $amount * $nums;
				
				    $pms_state[$web_room][$web_rate]['avg_price'] = number_format($amount / $params ['countday'], 2, '.', '');
				    $pms_state[$web_room][$web_rate]['price_resource'] = 'webservice';
				
				
				    $book_status = 'full';
				    if($date_status){
					    $book_status = 'available';
				    }
				
				    $pms_state[$web_room][$web_rate]['book_status'] = $book_status;
				    $exprice [$web_room][] = $pms_state[$web_room][$web_rate]['avg_price'];
				
				    $pms_state[$web_room][$web_rate]['least_num'] = $least_count;
				    $valid_state[$web_room][$web_rate] = $pms_state[$web_room][$web_rate];
				
			    }
		    }
	    }
	
	    return [
		    'pms_state'   => $pms_state,
		    'valid_state' => $valid_state,
		    'exprice'     => $exprice,
	    ];
	    
    }
    function get_web_nums($pms_set, $startdate, $enddate, $rm_cd) {
        $sg = new GetRmAvl ();
        $sg->arr_dt = $startdate;
        $sg->dpt_dt = $enddate;
        $sg->htl_list = $pms_set ['hotel_web_id'];
        $sg->rm_list = $rm_cd;
        $sg->channel_cd = $pms_set ['pms_auth'] ['channel'];
        $s = $this->sub_to_web ( $pms_set, 'Get_RmAvl', array (
                'sG' => $sg
        ) );
        $data = array ();
        if (! empty ( $s->Get_RmAvlResult->GetRmAvl ) && $s->Err->string [1]) {
            $room = $s->Get_RmAvlResult->GetRmAvl;
            if (count ( $room ) == 1) {
                $room = array (
                        '0' => $room
                );
            }
            foreach ( $room as $r ) {
                $data [$r->rm_list] ['total_num'] = $r->tot_avl;
                $data [$r->rm_list] ['nums'] = $r->rm_avl;
                $data [$r->rm_list] ['everyday_num'] = explode ( ',', $r->day_amt );
            }
        }
        return $data;
    }
    function order_to_web($inter_id, $orderid, $params = array(), $pms_set = array()) {
        $this->load->model ( 'hotel/Order_model' );
        $order = $this->Order_model->get_main_order ( $inter_id, array (
                'orderid' => $orderid,
                'idetail' => array (
                        'i'
                )
        ) );
        if (! empty ( $order )) {
            MYLOG::w ( json_encode($order,JSON_UNESCAPED_UNICODE), 'hotel'.DS.'order_select' );
            $order = $order [0];
            $room_codes = json_decode ( $order ['room_codes'], TRUE );
            $room_codes = $room_codes [$order ['first_detail'] ['room_id']];
            // add by ping 本地订单不提交pms
            if (! isset ( $room_codes ['code'] ['extra_info'] ['pms_code'] ) || empty ( $room_codes ['code'] ['extra_info'] ['pms_code'] )) {
                MYLOG::w ( json_encode($order,JSON_UNESCAPED_UNICODE), 'hotel'.DS.'order_select','_error');
//                 return array ( // 返回成功
//                         's' => 1 ,
//                         'msg' => ' 下单成功'
//                 );
            }
            $pms_set ['pms_auth'] = json_decode ( $pms_set ['pms_auth'], TRUE );
            
            $ri_add = new ResvInfo ();
            $ri_add->arr_dt = date ( "Y-m-d", strtotime ( $order ['startdate'] ) );
            $ri_add->dpt_dt = date ( "Y-m-d", strtotime ( $order ['enddate'] ) );
            $ri_add->fst_arr_tm = date ( "Y-m-d", strtotime ( $order ['startdate'] ) );
            $ri_add->lst_arr_tm = date ( "Y-m-d", strtotime ( $order ['enddate'] ) );
            $ri_add->org_dt = date ( "Y-m-d", $order ['order_time'] ) . 'T' . date ( "H:i:s", $order ['order_time'] );
            $ri_add->trust_dt = '0001-01-01';
            $ri_add->htl_cd = $pms_set ['hotel_web_id'];
            $ri_add->rm_typ = $room_codes ['room'] ['webser_id'];
            // $ri_add->htl_cd = '099';//测试酒店
            // $ri_add->rm_typ = 'BD';
            $ri_add->manipulate = 1;
            $ri_add->acct_stus = '2';
            $ri_add->affirm_typ = 0;
            $ri_add->contact = $order ['name'];
            $ri_add->contact_tel = $order ['tel'];
            $ri_add->rp_cd = $room_codes ['code'] ['extra_info'] ['pms_code'];
            $ri_add->channel_cd = $pms_set ['pms_auth'] ['channel'];
            $ri_add->crdt_cd = $pms_set ['pms_auth'] ['crdt_cd'];
            $ri_add->acctnm = $order ['name'];
            // @Editor lGh 2016-9-29 16:22:51 增加第三方单号
            $ri_add->crs_resvnum = $orderid;
            $ri_add->sw_crsresv_num = $orderid;
            $ri_add->org_cd = isset ( $pms_set ['pms_auth'] ['org_cd'] ) ? $pms_set ['pms_auth'] ['org_cd'] : '';
            // if ($order ['openid']) {
            // $member = $this->get_member_login ( $order ['openid'] );
            // }
            // if (! empty ( $member ['membership_number'] )) {
            // $ri_add->ic_num = $member ['membership_number'];
            // }
            // $this->load->model ( 'member/Imember' );
            // $check = $this->Imember->getMemberInfoByOpenId ( $order ['openid'], $inter_id, 0 );
            if (! empty ( $order ['member_no'] )) {
                $ri_add->ic_num = $order ['member_no'];
            }
            
            // @Editor lGh 2016-7-12 10:42:57 碧桂园积分单支付用01，下单用02
            if (! empty ( $order ['paytype'] ) && ($order ['paytype'] == 'bonus' || $order ['paytype'] == 'point') && $room_codes ['code'] ['extra_info'] ['pms_code'] == '01') {
                $param = array ();
                $param ['web_rids'] = array (
                        $room_codes ['room'] ['webser_id'] => $order ['first_detail'] ['room_id']
                );
                $param ['countday'] = get_room_night ( $order ['startdate'], $order ['enddate'], 'ceil', $order ); // 至少有一个间夜
                $param ['condit'] ['nums'] = array (
                        $order ['first_detail'] ['room_id'] => $order ['roomnums']
                );
                $book_state = $this->get_web_roomtype ( $pms_set, array (
                        '02'
                ), $order ['startdate'], $order ['enddate'], $param );
                if (empty ( $book_state ['pms_state'] [$room_codes ['room'] ['webser_id']] ['02'] )) {
                    return array (
                            's' => 0,
                            'errmsg' => '暂不能进行积分支付'
                    );
                }
                $book_state = $book_state ['pms_state'] [$room_codes ['room'] ['webser_id']] ['02'];
                if ($book_state ['book_status'] != 'available') {
                    return array (
                            's' => 0,
                            'errmsg' => '房间数不足'
                    );
                }
                $all_price = $book_state ['allprice'];
                $order_price = $book_state ['total_price'];
            }
            
            $favor = empty ( $order ['coupon_favour'] ) ? 0 : floatval ( $order ['coupon_favour'] );
            $favor += empty ( $order ['point_favour'] ) ? 0 : floatval ( $order ['point_favour'] ); // 优惠的总金额
            $remark = '微信预订(' . $order ['first_detail'] ['price_code_name'] . '/' . $order ['first_detail'] ['roomname'] . ')';
            $remark .= ',微信订单号：' . $order ['orderid'];
            // if ($order ['paid'] == 1) {
            // $ri_add->pay_num = $wx_trans_no;
            // $remark .= '/预付订单/商户订单号：' . $order ['orderid'] . '。已付定金：' . $order ['price'];
            // } else
            // $remark .= '/现付订单。';
            // $remark .= '微信预订房价：' . $ts;
            if (! empty ( floatval ( $order ['coupon_favour'] ) ))
                $remark .= ',代金券优惠：' . floatval ( $order ['coupon_favour'] ) . '元';
            if (! empty ( floatval ( $order ['point_favour'] ) ))
                $remark .= ',积分扣减：' . floatval ( $order ['point_favour'] ) . '元';
            if ($favor)
                $remark .= ',总优惠：' . floatval ( $favor ) . '元';
            if (!empty($room_codes ['code'] ['extra_info']['parity'])){
                $parity_info=$room_codes ['code'] ['extra_info']['parity'];
                $favour_info='，已智能调价(';
                foreach ($parity_info as $date=>$price){
                    $favour_info.=date('Y-m-d',strtotime($date)).':'.$price.',';
                }
                $remark .= substr($favour_info, 0,strlen($favour_info)-1).')';
            }
            if (! empty ( $order ['paytype'] ) && $order ['paytype'] == 'balance') {
                $remark .= ',总价：' . $order ['price'] . '元';
                $remark .= ',支付方式：' . '储值支付';
            } elseif (! empty ( $order ['paytype'] ) && ($order ['paytype'] == 'bonus' || $order ['paytype'] == 'point')) {
                $this->load->model('Hotel/Member_model');
                $check=$this->Member_model->check_openid_member($order['inter_id'],$order['openid']);
                $remark .= ',积分预付：' . $order ['price'] . '元';
                $remark .= ',支付方式：' . '积分支付，' . '会员卡号/手机：' . $check->membership_number . '/' . $check->telephone;
                $ri_add->crdt_cd = 'BX';
                $ri_add->rp_cd = '02';
                $ri_add->deposit_cd = '871';
            } elseif (! empty ( $order ['paytype'] ) && $order ['paytype'] == 'daofu') {
                $remark .= ',总价：' . $order ['price'] . '元';
                $remark .= ',支付方式：' . '到付';
            } elseif (! empty ( $order ['paytype'] ) && $order ['paytype'] == 'weixin') {
                $remark .= ',总价：' . $order ['price'] . '元';
                $remark .= ',支付方式：' . '微信支付';
            }elseif($order['paytype']=='balance'){
	            $remark .= ',总价：' . $order ['price'] . '元';
	            $remark .= ',支付方式：' . '储值支付';
            } else {
                $remark .= ',总价：' . $order ['price'] . '元';
                $remark .= ',支付方式：' . '其他';
            }
            if(!empty($order['first_detail']['club_id'])){
                $this->load->model ( 'club/Clubs_model' );
                $club_info = $this->Clubs_model->get_club_by_id($order['first_detail']['club_id']);
                if(!empty($club_info)){
                    $remark .= ',社群客名称：' .$club_info['club_name'];
                    isset ( $pms_set ['pms_auth'] ['club_org_cd'] ) and $ri_add->org_cd = $pms_set ['pms_auth'] ['club_org_cd'];
                    isset ( $pms_set ['pms_auth'] ['club_acct_typ'] ) and $ri_add->acct_typ = $pms_set ['pms_auth'] ['club_acct_typ'];
                }
                if(!empty($club_info['remark'])){
                    $remark .= ',社群客协议代码：' .$club_info['remark'];
                }
            };
            $remark .= '.';
            $ri_add->remark = $remark;
            
            $ri_add_a = array ();
            $avg_favour = intval ( $favor / $order ['roomnums'] );
            $extra_favour = $favor - ($avg_favour * $order ['roomnums']);
            
            // 20160225 修改全部订金入第一张单
            for($i = 0; $i < $order ['roomnums']; $i ++) { // 房间数大于1,传入多条订单数据
                $ri_add_a [$i] = clone $ri_add;
                if (empty ( $all_price )) {
                    $tmp = explode ( ',', $order ['order_details'] [$i] ['real_allprice'] );
                    $last_price = end ( $tmp );
                } else {
                    $tmp = explode ( ',', $all_price );
                    $last_price = end ( $tmp );
                    if (! empty ( $avg_favour )) {
                        $favor = $avg_favour;
                        if ($i == 0) {
                            $favor += $extra_favour;
                        }
                        for($j = count ( $tmp ) - 1; $j >= 0; $j --) { // 要传的每日房价，从最后的价钱开始扣除优惠金额
                            if ($tmp [$j] >= $favor) {
                                $tmp [$j] = $tmp [$j] - $favor;
                                $favor = 0;
                                break;
                            } else {
                                $favor = $favor - $tmp [$j];
                                $tmp [$j] = 0;
                            }
                            if ($favor == 0)
                                break;
                        }
                    }
                }
                $ri_add_a [$i]->rt_amt = $tmp [0];
                
                $ri_add_a [$i]->everyday_amt = implode ( ',', $tmp ) . ',' . $last_price; // 每日房价需加入离店日期后一天的价格#脑瘫设计#
            }
            
            $result = $this->Add_ResvInfo ( $pms_set, $ri_add_a, $order );
            
            if (! empty ( $result )) {
            	$has_paid=null;
                $web_orderid = $result [0]->resvnum;
                $order ['web_orderid'] = $web_orderid;
                $this->db->where ( array (
                        'orderid' => $order ['orderid'],
                        'inter_id' => $order ['inter_id'] 
                ) );
                $this->db->update ( 'hotel_order_additions', array (
                        'web_orderid' => $web_orderid 
                ) );
                // if ($order ['status'] != 9) {
                // $this->db->where ( array (
                // 'orderid' => $order ['orderid'],
                // 'inter_id' => $order ['inter_id']
                // ) );
                // $this->db->update ( 'hotel_orders', array (
                // 'status' => 1
                // ) );
                // $this->Order_model->handle_order ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
                // }
                // if ($order ['paytype'] != 'point') {
                if ($order ['point_used_amount'] <= 0) {
                    foreach ( $order ['order_details'] as $k => $od ) {
                        $this->db->where ( array (
                                'id' => $od ['sub_id'],
                                'inter_id' => $inter_id,
                                'orderid' => $order ['orderid'] 
                        ) );
                        $everyday_amt = explode ( ',', $result [$k]->everyday_amt );
                        $this->db->update ( 'hotel_order_items', array (
                                'webs_orderid' => $result [$k]->acctnum,
                                'iprice' => array_sum ( $everyday_amt ) - array_pop ( $everyday_amt ) 
                        ) );
                    }
                    if (! empty ( $paras ['trans_no'] )) { // 提交账务
                        $this->add_web_bill ( $web_orderid, $order, $pms_set, $paras ['trans_no'] );
                    }
                    if ($order ['status'] != 9 || $order['paytype']=='weixin') {
                        return array ( // 返回成功
                                's' => 1,
                                'has_paid'=>$has_paid
                        );
                    }
                } else if ($order ['point_used_amount'] > 0) {
                    foreach ( $order ['order_details'] as $k => $od ) {
                        $this->db->where ( array (
                                'id' => $od ['sub_id'],
                                'inter_id' => $inter_id,
                                'orderid' => $order ['orderid'] 
                        ) );
                        $this->db->update ( 'hotel_order_items', array (
                                'webs_orderid' => $result [$k]->acctnum 
                        ) );
                    }
                    // @Editor lGh 2016-12-2 11:40:48 增加提交积分日志
                    $point_reduce_type = 'pay';
                    $point_detail = array ();
                    $log_data = array ();
                    if ($order ['paytype'] != 'point') {
                        $point_rate = 10;
                        $point_reduce_type = 'ex';
                        $point_avg_favour = intval ( $order ['point_favour'] / $order ['roomnums'] );
                        $point_extra_favour = $order ['point_favour'] - ($point_avg_favour * $order ['roomnums']);
                        $avg_point = intval ( $order ['point_used_amount'] / $order ['roomnums'] );
                        $extra_point = $order ['point_used_amount'] - ($avg_point * $order ['roomnums']);
                        $extra = ($avg_point - intval ( $avg_point / $point_rate ) * $point_rate) * $order ['roomnums'];
                        $data = array (
                                "roomCode" => $ri_add->rm_typ,
                                "hotelCode" => $ri_add->htl_cd,
                                "icNum" => $ri_add->ic_num,
                                "arriveDate" => $ri_add->arr_dt,
                                "departureDate" => $ri_add->dpt_dt,
                                "roomCount" => $order ['roomnums'],
                                "crsResvNum" => $web_orderid,
                                "acctNum" => '', //
                                "acctStus" => $ri_add->acct_stus,
                                "createdTime" => date ( 'Y-m-d', $order ['order_time'] ) . 'T' . date ( 'H:i:s', $order ['order_time'] ),
                                "roomPriceCode" => $ri_add->rp_cd,
                                "channelCode" => $ri_add->channel_cd,
                                "totalRoomRate" => 0, //
                                "payRoomRate" => 0, //
                                "payScore" => 0, //
                                "guestName" => $order ['name'],
                                "doctType" => "",
                                "doctNo" => "",
                                "mobile" => $order ['tel'],
                                "remark" => $ri_add->remark 
                        );
                        foreach ( $result as $k => $r ) {
                            $data ['acctNum'] = $r->acctnum;
                            
                            $everyday_amt = explode ( ',', $r->everyday_amt );
                            $data ['payRoomRate'] = array_sum ( $everyday_amt ) - array_pop ( $everyday_amt );
                            $data ['payScore'] = intval ( $avg_point / $point_rate ) * $point_rate;
                            $data ['totalRoomRate'] = $data ['payRoomRate'] + $point_avg_favour;
                            if ($k == 0) {
                                $data ['payScore'] += $extra + $extra_point;
                                $data ['totalRoomRate'] += $point_extra_favour;
                            }
                            $data ['payScore'] = intval ( $data ['payScore'] );
                            $point_detail [$r->acctnum] = $data ['payScore'];
                            $log_data [] = $data;
                        }
                    } else {
                        $point_detail = $result [0]->acctnum;
                    }
                    
                    if (! $this->set_icstat ( $order, $ri_add->htl_cd, $point_reduce_type, $point_detail, $remark )) {
                        $info = $this->Order_model->cancel_order ( $inter_id, array (
                                'only_openid' => $order ['openid'],
                                'member_no' => '',
                                'orderid' => $order ['orderid'],
                                'cancel_status' => 5,
                                'no_tmpmsg' => 1,
                                'delete' => 2,
                                'idetail' => array (
                                        'i' 
                                ) 
                        ) );
                        return array (
                                's' => 0,
                                'errmsg' => $order ['paytype'] == 'point' ? '积分支付失败' : '积分扣减失败' 
                        );
                    } else {
                        if ($order ['paytype'] == 'point') {
                        	$has_paid=1;
//                            $this->Order_model->update_order_status ( $order ['inter_id'], $order ['orderid'], 1, $order ['openid'], true, true );
                            $order = $this->Order_model->get_main_order ( $inter_id, array (
                                    'orderid' => $orderid,
                                    'idetail' => array (
                                            'i' 
                                    ) 
                            ) );
                            if (! empty ( $order )) {
                                $order = $order [0];
                                $this->add_web_bill ( $web_orderid, $order, $pms_set, '' );
                            }
                        }
                        
                        // @Editor lGh 2016-12-2 11:40:48 增加提交积分日志
                        if (! empty ( $log_data )) {
                            foreach ( $log_data as $l ) {
                                if ($l ['payScore'] > 0)
                                    $this->web_add_log ( $pms_set, 'rws/order/log', $l );
                            }
                        }
                        
                        return array ( // 返回成功
                                's' => 1,
                                'has_paid'=>$has_paid
                        );
                    }
                }
                if($order['paytype'] == 'balance'){
                    $this->load->model('hotel/Hotel_config_model');
                    $config_data = $this->Hotel_config_model->get_hotel_config($inter_id, 'HOTEL', $order ['hotel_id'], array(
                            'PMS_BANCLANCE_REDUCE_WAY'
                    ));
                    if(!empty ($config_data ['PMS_BANCLANCE_REDUCE_WAY']) && $config_data ['PMS_BANCLANCE_REDUCE_WAY'] == 'after'){
                        $this->load->model('hotel/Member_model');
                        $sub_orders = [];
                        foreach($order['order_details'] as $v){
                            $sub_orders[$v['sub_id']] = $v['iprice'];
                        }
                        $balance_param = [
                                'crsNo' => $web_orderid,
                                'extra' => [
                                        'hotelCode' => $pms_set['hotel_web_id'],
                                        'orders'    => $sub_orders
                                ],
                        ];
                        if(!empty($room_codes['room']['consume_code'])){
                            $balance_param['password']=$room_codes ['room'] ['consume_code'];
                        }
                        if(!$this->Member_model->reduce_balance($inter_id, $order['openid'], $order['price'], $order['orderid'], '订房订单余额支付', $balance_param, $order)){
                            $info = $this->Order_model->cancel_order($inter_id, array(
                                    'only_openid'   => $order ['openid'],
                                    'member_no'     => '',
                                    'orderid'       => $order ['orderid'],
                                    'cancel_status' => 5,
                                    'no_tmpmsg'     => 1,
                                    'delete'        => 2,
                                    'idetail'       => array(
                                            'i'
                                    )
                            ));
                
                            return [
                                    's'      => 0,
                                    'errmsg' => '储值支付失败！',
                            ];
                        }
                        $order = $this->Order_model->get_main_order ( $inter_id, array (
                                'orderid' => $orderid,
                                'idetail' => array (
                                        'i'
                                )
                        ) );
                        if (! empty ( $order )) {
                            $order = $order [0];
                            $this->add_web_bill($web_orderid, $order, $pms_set, $order['orderid']);
                        }
                        $has_paid=1;
                        return array ( // 返回成功
                                's' => 1,
                                'has_paid'=>$has_paid
                        );
                    }
                }
            } else {
                $this->db->where ( array (
                        'orderid' => $order ['orderid'],
                        'inter_id' => $order ['inter_id'] 
                ) );
                $this->db->update ( 'hotel_orders', array ( // 提交失败，把订单状态改为下单失败
                        'status' => 10 
                ) );
                return array ( // 返回失败
                        's' => 0,
                        'errmsg' => '提交订单失败' . ',' . $result ['message'] 
                );
            }
        }
        return array (
                's' => 0,
                'errmsg' => '提交订单失败' 
        );
    }
    function update_web_order($inter_id, $order, $pms_set) {
        switch ($inter_id) {
            case 'a421641095' :
                return $this->update_web_order_sub ( $inter_id, $order, $pms_set );
                break;
            default :
                return $this->update_web_order_main ( $inter_id, $order, $pms_set );
                break;
        }
        return FALSE;
    }
    function update_web_order_sub($inter_id, $order, $pms_set) {
        // add by ping 本地订单不提交pms
        $room_codes = json_decode ( $order ['room_codes'], TRUE );
        $room_codes = $room_codes [$order ['first_detail'] ['room_id']];
        if (! isset ( $room_codes ['code'] ['extra_info'] ['pms_code'] ) || empty ( $room_codes ['code'] ['extra_info'] ['pms_code'] )) {
            return true;
        }
        
        $web_order = $this->get_order_info ( $order, $pms_set, 'key' );
        $istatus = - 1;
        if (! empty ( $web_order )) {
            $status_arr = $this->pms_enum ( 'status' );
            $this->load->model ( 'hotel/Order_model' );
            $ensure_check = 0;
            foreach ( $order ['order_details'] as $od ) {
                $webs_orderid = $od ['webs_orderid'];
                if (! empty ( $web_order [$webs_orderid] )) {
                    $istatus = $status_arr [$web_order [$webs_orderid]->acct_stus];
                    if ($od ['istatus'] == 4 && $istatus == 5) {
                        $istatus = 4;
                    }
                    // 未确认单先确认
                    if ($istatus != 0 && $order ['status'] == 0 && $ensure_check == 0) {
                        $this->db->where ( array (
                                'orderid' => $order ['orderid'],
                                'inter_id' => $inter_id 
                        ) );
                        $this->db->update ( 'hotel_orders', array (
                                'status' => 1 
                        ) );
                        $this->Order_model->handle_order ( $inter_id, $order ['orderid'], 1, '', array (
                                'no_tmpmsg' => 1 
                        ) );
                        $ensure_check = 1;
                    }
                    $web_start = date ( 'Ymd', strtotime ( $web_order [$webs_orderid]->arr_dt ) );
                    $web_end = date ( 'Ymd', strtotime ( $web_order [$webs_orderid]->dpt_dt ) );
//                    $web_end = $web_end == $web_start ? date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $web_start ) ) ) : $web_end;
                    $web_end < $web_start and $web_end == $web_start;
                    $ori_day_diff = get_room_night ( $od ['startdate'], $od ['enddate'], 'ceil', $od ); // 至少有一个间夜
                    $web_day_diff = get_room_night ( $web_start, $web_end, 'ceil' ); // 至少有一个间夜
                    $day_diff = $web_day_diff - $ori_day_diff;
                    $everyday_amt = explode ( ',', $web_order [$webs_orderid]->everyday_amt );
                    if(count($everyday_amt)==1){
                        $web_price = array_sum ( $everyday_amt );
                    }else{
                        $web_price = array_sum ( $everyday_amt ) - array_pop ( $everyday_amt );
                    }
                    
                    $updata = array ();
                    if ($istatus != $od ['istatus']) {
                        $updata ['istatus'] = $istatus;
                    }
                    if(!empty($updata ['istatus']) && $updata ['istatus'] == 3 && $web_price <= 0){
                        $this->db->where(array(
                            'inter_id' => $order ['inter_id'],
                            'orderid'  => $order ['orderid']
                        ));
                        $this->db->update('hotel_order_items', array(
                            'istatus' => 1
                        ));
                        $updata ['istatus'] = 5;
                    }
                    // 积分支付单不进行金额更新
                    if ($order ['paytype'] != 'point' ) {
                        if (($day_diff != 0 || $web_start != $od ['startdate'] || $web_end != $od ['enddate'])){
                            $updata ['no_check_date'] = 1;
                            $updata ['startdate'] = $web_start;
                            $updata ['enddate'] = $web_end;
                        }
                        // $web_price = 0;
                        if ($web_price != $od['iprice']) {
                            $updata ['new_price'] = $web_price;
                        }
                    }
                    if (! empty ( $updata )) {
                        $this->Order_model->update_order_item ( $inter_id, $order ['orderid'], $od ['sub_id'], $updata );
                    }
                }
            }
        }
        return $istatus;
    }
    function update_web_order_main($inter_id, $order, $params) {
    }
    function cancel_order_web($inter_id, $order, $pms_set = array()) {
        if (empty ( $order ['web_orderid'] )) {
            return array (
                    's' => 1,
                    'errmsg' => '取消成功' 
            );
        }
        $web_order = $this->get_order_info ( $order, $pms_set );
        if (empty ( $web_order )) {
            return array (
                    's' => 0,
                    'errmsg' => '取消失败' 
            );
        }
        $ri_edit = array ();
        foreach ( $web_order as $wo ) {
            $wo->acct_stus = '*';
            $wo->manipulate = 2;
            $wo->remark .= '微信用户取消订单';
            $ri_edit [] = $wo;
        }
        $result = $this->Mod_ResvInfo ( $pms_set, $ri_edit, $order );
        if (! empty ( $result )) {
            return array (
                    's' => 1,
                    'errmsg' => '取消成功' 
            );
        }
        return array (
                's' => 0,
                'errmsg' => '取消失败' 
        );
    }
    /*
     * 预订单修改方法
     */
    function Mod_ResvInfo($pms_set, $ri, $order = array()) {
        $s = $this->sub_to_web ( $pms_set, 'Mod_ResvInfo', array (
                'RI' => $ri 
        ), array (
                'orderid' => $order ['orderid'] 
        ) );
        if ($s->Mod_ResvInfoResult && $s->Err->string [1])
            return TRUE;
        else
            return false;
    }
    function add_web_bill($web_orderid, $order, $pms_set, $trans_no) {
        $result = FALSE;
        $web_paid = 2;
        if (empty ( $order ['web_orderid'] )) {
            $this->db->where ( array (
                    'orderid' => $order ['orderid'],
                    'inter_id' => $order ['inter_id'] 
            ) );
            $this->db->update ( 'hotel_order_additions', array ( // 更新web_paid 状态，2为失败，1为成功
                    'web_paid' => $web_paid 
            ) );
            return $result;
        }
        $web_order = $this->get_order_info ( $order, $pms_set, 'key' );
        if (empty ( $web_order )) {
            $this->db->where ( array (
                    'orderid' => $order ['orderid'],
                    'inter_id' => $order ['inter_id'] 
            ) );
            $this->db->update ( 'hotel_order_additions', array (
                    'web_paid' => $web_paid 
            ) );
            return $result;
        }
        $ri_edit = array ();
        foreach ( $order ['order_details'] as $od ) {
            if (! empty ( $web_order [$od ['webs_orderid']] )) {
                if (! empty ( $trans_no ))
                    $web_order [$od ['webs_orderid']]->pay_num = $trans_no;
                $web_order [$od ['webs_orderid']]->remark .= ' 已支付';
                if ($order ['paytype'] == 'point' && ! empty ( $order ['point_used_amount'] )) {
                    $web_order [$od ['webs_orderid']]->remark .= ' 已扣减积分' . $order ['point_used_amount'] . '分';
                    $web_order [$od ['webs_orderid']]->deposit_cd = '871';
                }
                $ri_edit [] = $web_order [$od ['webs_orderid']];
                if ($order ['paytype'] == 'point' && ! empty ( $order ['point_used_amount'] )) {
                    $tmp = explode ( ',', $web_order [$od ['webs_orderid']]->everyday_amt );
                    array_pop ( $tmp );
                    $ri_edit [0]->subscription += array_sum ( $tmp );
                } else {
                    $ri_edit [0]->subscription += $od ['iprice'];
                }
            }
        }
        if (! empty ( $ri_edit )) {
            $result = $this->Mod_ResvInfo ( $pms_set, $ri_edit, $order );
        }
        if ($result == TRUE) {
            $web_paid = 1;
        }
        $this->db->where ( array (
                'orderid' => $order ['orderid'],
                'inter_id' => $order ['inter_id'] 
        ) );
        $this->db->update ( 'hotel_order_additions', array (
                'web_paid' => $web_paid 
        ) );
        return $result;
    }
    function pms_enum($type, $key = NULL, $value = NULL) {
        $data = array ();
        switch ($type) {
            case 'status' :
                $data = array (
                        '1' => 1,
                        '*' => 5,
                        '4' => 2,
                        '5' => 3,
                        '0' => 8,
                        '2' => 0,
                        '@' => 6,
                        '!' => 7 
                );
                break;
            case 'func_name' :
                $data = array (
                        'Get_RmAvl' => '查询剩余房量',
                        'Mod_ResvInfo' => '修改订单',
                        'Get_ResvInfo' => '查询订单',
                        'Get_Hotels' => '查询房态',
                        'Get_HotelsByBGY' => '查询房态(碧桂园)',
                        'Get_HotelCollection' => '查询酒店信息',
                        'Add_ResvInfo' => '新增订单',
                        'Get_SysConf' => '查询配置' 
                );
                break;
            default :
                return array ();
                break;
        }
        if (is_array ( $data )) {
            if (isset ( $key )) {
                return isset ( $data [$key] ) ? $data [$key] : NULL;
            }
            if (isset ( $value )) {
                return in_array ( $value, $data );
            }
        }
        return $data;
    }
    /*
     * 查看订单状态
     */
    public function get_order_info($order, $pms_set, $key = '') {
        $ri_check = new ResvInfo ();
        $ri_check->htl_cd = $pms_set ['hotel_web_id'];
        // $ri_check->htl_cd = '099';//测试酒店
        $ri_check->contact_tel = $order ['tel'];
        $ri_check->resvnum = $order ['web_orderid'];
        $ri_check->manipulate = 0;
        $s = $this->sub_to_web ( $pms_set, 'Get_ResvInfo', array (
                'RI' => $ri_check 
        ), array (
                'orderid' => $order ['orderid'] 
        ) );
        $order = array ();
        if (! empty ( $s->Get_ResvInfoResult->ResvInfo ) && $s->Err->string [1]) {
            $order = $s->Get_ResvInfoResult->ResvInfo;
            if (count ( $order ) == 1) {
                $order = array (
                        '0' => $order 
                );
            }
            if ($key == 'key') {
                foreach ( $order as $o ) {
                    $info [$o->acctnum] = $o;
                }
                return $info;
            }
        }
        return $order;
    }
    function Get_Hotels($pms_set, $sh_hotels) {
        $s = $this->sub_to_web ( $pms_set, 'Get_Hotels', array (
                'sH' => $sh_hotels 
        ) );
        $room = array ();
        $code_name = '';
        if (! empty ( $s->Get_HotelsResult->HotelInfo ) && $s->Err->string [1]) {
            $room = $s->Get_HotelsResult->HotelInfo->rm_list->RoomInfo;
            $code_name = $s->Get_HotelsResult->HotelInfo->rp_nm;
            if (count ( $room ) == 1) {
                $room = array (
                        '0' => $room 
                );
            }
        }
        return array (
                'rooms' => $room,
                'code_name' => $code_name 
        );
    }
    function Get_HotelsByBGY($pms_set, $sh_hotels) {
        // ini_set("soap.wsdl_cache_enabled", 0);
        $s = $this->sub_to_web ( $pms_set, 'Get_HotelsByBGY', array (
                'sH' => $sh_hotels 
        ) );
        $hotels = array ();
        if (! empty ( $s->Get_HotelsByBGYResult->HotelInfo ) && $s->Err->string [1]) {
            $hotel_infos = $s->Get_HotelsByBGYResult->HotelInfo;
            if (count ( $hotel_infos ) == 1) {
                $hotel_infos = array (
                        $hotel_infos 
                );
            }
            foreach ( $hotel_infos as $h ) {
                $room = $h->rm_list->RoomInfo;
                if (count ( $room ) == 1) {
                    $room = array (
                            '0' => $room 
                    );
                }
                $hotels [$h->rp_cd] ['rooms'] = $room;
                $hotels [$h->rp_cd] ['code_name'] = $h->rp_nm;
            }
        }
        return $hotels;
    }
    function get_hotel_info($params, $pms_set) {
        $sh_hotel = new HotelInfo ();
        $sh_hotel->arr_dt = date ( 'Y-m-d', strtotime ( $params ['startdate'] ) );
        $sh_hotel->dpt_dt = date ( 'Y-m-d', strtotime ( $params ['enddate'] ) );
        $sh_hotel->htlcd = $pms_set ['hotel_web_id'];
        $params ['_sH'] = $sh_hotel;
        return $this->sub_to_web ( $pms_set, 'Get_HotelCollection', $params );
    }
    
    /*
     * 增加订单方法
     */
    function Add_ResvInfo($pms_set, $ri_adds, $order = array()) {
        $s = $this->sub_to_web ( $pms_set, 'Add_ResvInfo', array (
                'RI' => $ri_adds 
        ), array (
                'orderid' => $order ['orderid'] 
        ) );
        $order = array ();
        if (! empty ( $s->Add_ResvInfoResult->ResvInfo ) && $s->Err->string [1]) {
            $order = $s->Add_ResvInfoResult->ResvInfo;
            if (count ( $order ) == 1) {
                $order = array (
                        '0' => $order 
                );
            }
        }
        return $order;
    }
    function get_web_point_rate($pms_set) {
        $sysconf = new SysConf ();
        $sysconf->para_typ = 'M4';
        $s = $this->sub_to_web ( $pms_set, 'Get_SysConf', array (
                'sc' => $sysconf 
        ) );
        return $s;
    }
    function sub_to_web($pms_set, $fun_name, $params, $func_data = array()) {
        if (! is_array ( $pms_set ['pms_auth'] ))
            $pms_set ['pms_auth'] = json_decode ( $pms_set ['pms_auth'], TRUE );
        $Err = array (
                '0',
                '0',
                '0' 
        );
        $params ['Err'] = $Err;
        $params ['user_cd'] = $pms_set ['pms_auth'] ['user'];
        $params ['password'] = $pms_set ['pms_auth'] ['pwd'];
        $params ['lang'] = $pms_set ['pms_auth'] ['lang'];
        $now = time ();
        $run_alarm = 0;
        try {
            $soap = new soapclient ( $pms_set ['pms_auth'] ['url'], array (
                    'encoding' => 'UTF-8',
                    'trace' => TRUE
            ) );
            $s = $soap->__Call ( $fun_name, array (
                    'parameters' => $params 
            ) );
        } catch ( SoapFault $e ) {
            $s = $e;
            $run_alarm = 1;
            MYLOG::w(json_encode($s),"hotel/biguiyuan",'_exception' );
            if (!empty($soap) && is_object($soap)){
                if (method_exists($soap, '__getLastResponseHeaders')){
                    MYLOG::w($soap->__getLastResponseHeaders(),"hotel/biguiyuan",'_exception' );
                }
                if (method_exists($soap, '__getLastResponse')){
                    MYLOG::w($soap->__getLastResponse (),"hotel/biguiyuan",'_exception' );
                }
            }
        } catch ( Exception $e ) {
            $s = $e;
            $run_alarm = 1;
            MYLOG::w(json_encode($e),"hotel/biguiyuan",'_exception' );
            if (!empty($soap) && is_object($soap)){
                if (method_exists($soap, '__getLastResponseHeaders')){
                    MYLOG::w($soap->__getLastResponseHeaders(),"hotel/biguiyuan",'_exception' );
                }
                if (method_exists($soap, '__getLastResponse')){
                    MYLOG::w($soap->__getLastResponse (),"hotel/biguiyuan",'_exception' );
                }
            }
        }
        $inter_id = $pms_set ['inter_id'];
        $record_type = 'webservice';
        $mirco_receive_time = microtime ();
        $web_path = $pms_set ['pms_auth'] ['url'] . '/' . $fun_name;
        $openid = $this->session->userdata ( $pms_set ['inter_id'] . 'openid' );
        $this->load->model ( 'common/Webservice_model' );
        $this->Webservice_model->add_webservice_record ( $pms_set ['inter_id'], self::WEB_TYPE, $web_path, $params, $s, $record_type, $now, $mirco_receive_time, $openid );
        
        $func_data ['openid'] = $openid;
        $this->check_web_result ( $inter_id, $web_path, $params, $s, $now, $mirco_receive_time, $func_data, array (
                'run_alarm' => $run_alarm 
        ) );
        
        return $s;
    }
    function web_add_log($pms_set, $fun_name, $data, $need_token = TRUE) {
        if (! is_array ( $pms_set ['pms_auth'] ))
            $pms_set ['pms_auth'] = json_decode ( $pms_set ['pms_auth'], TRUE );
        $this->load->helper ( 'common' );
        $send_content = json_encode ( $data );
        $url = $pms_set ['pms_auth'] ['log_url'] . '/' . $fun_name;
        if ($need_token) {
            $url .= '?ssotoken=' . $this->get_log_accesstoken ( $pms_set );
        }
        $now = time ();
        $s = doCurlPostRequest ( $url, $send_content, array (
                'Content-Type' => 'application/json' 
        ), 5 );
        
        $this->load->model ( 'common/Webservice_model' );
        $this->Webservice_model->add_webservice_record ( $pms_set ['inter_id'], 'zhongruan', $url, $data, $s, 'query_post', $now, microtime (), $this->session->userdata ( $pms_set ['inter_id'] . 'openid' ) );
        
        $s = json_decode ( $s, TRUE );
        if (! empty ( $s ) && ! empty ( $s ['result'] ) && $s ['result'] == 'failed') {
            $url = $pms_set ['pms_auth'] ['log_url'] . '/' . $fun_name;
            if ($need_token) {
                $url .= '?ssotoken=' . $this->get_log_accesstoken ( $pms_set, TRUE );
            }
            $now = time ();
            $s = doCurlPostRequest ( $url, $send_content, array (
                    'Content-Type' => 'application/json' 
            ), 5 );
            
            $this->load->model ( 'common/Webservice_model' );
            $this->Webservice_model->add_webservice_record ( $pms_set ['inter_id'], 'zhongruan', $url, $data, $s, 'query_post', $now, microtime (), $this->session->userdata ( $pms_set ['inter_id'] . 'openid' ) );
            $s = json_decode ( $s, TRUE );
        }
        return $s;
    }
    function get_log_accesstoken($pms_set, $refresh = FALSE) {
        if (! isset ( $this->redis_proxy )) {
            $this->load->library ( 'Cache/Redis_proxy', array (
                    'not_init' => FALSE,
                    'module' => 'common',
                    'refresh' => FALSE,
                    'environment' => ENVIRONMENT 
            ), 'redis_proxy' );
        }
        $token = $this->redis_proxy->get ( $pms_set ['inter_id'] . '_weblog_accesstoken' );
        if ($token && ! $refresh) {
            return $token;
        } else {
            $result = $this->web_add_log ( $pms_set, 'rws/user-access-token', array (
                    'username' => $pms_set ['pms_auth'] ['log_user'],
                    'password' => $pms_set ['pms_auth'] ['log_pwd'] 
            ), FALSE );
            if (! empty ( $result ) && ! empty ( $result ['token'] )) {
                $this->redis_proxy->setex ( $pms_set ['inter_id'] . '_weblog_accesstoken', 300, $result ['token'] );
                return $result ['token'];
            }
        }
        return FALSE;
    }
    function set_icstat($order, $htl_cd, $type = 'pay', $point_detail = array(), $remark = '') {
        $this->load->model ( 'hotel/Member_model' );
        $param = array (
                'remark' => $remark 
        );
        $sort_drpt = '微信X+Y兑换';
        if ($type == 'pay') {
            return $this->Member_model->consum_point ( $order ['inter_id'], $order ['orderid'] . ',' . $htl_cd . ',' . $point_detail . ',' . $sort_drpt, $order ['openid'], $order ['point_used_amount'], $param );
        } else {
            foreach ( $point_detail as $acctnum => $point ) {
                if (! empty ( $point )) {
                    if (! $this->Member_model->consum_point ( $order ['inter_id'], $order ['orderid'] . ',' . $htl_cd . ',' . $acctnum . ',' . $sort_drpt, $order ['openid'], $point, $param ))
                        return FALSE;
                }
            }
            return TRUE;
        }
    }
    function get_order_state_tmp($order, $pms_set, $status_des) {
        $state = array ();
        return array ();
        if ($order ['handled'] == 0 && ! empty ( $order ['web_orderid'] )) {
            $web_order = $this->get_order_info ( $order, $pms_set );
            if (! empty ( $web_order )) {
                $cancels = $this->pms_enum ( 'cancel_status' );
                $can_cancel = NULL;
                $web_re_pay = NULL;
                $web_check = NULL;
                $web_des = $status_des [$order ['status']];
                if ($web_order ['OrderStatus'] < 10) {
                    if ($order ['paytype'] == 'daofu') {
                        $can_cancel = 1;
                    } else if ($order ['paytype'] == 'weixin' && $order ['status'] == 9) {
                        $can_cancel = 1;
                    } else if ($web_order ['IsCancel'] == 1 && strtotime ( $web_order ['LastCancelTime'] ) > time ()) {
                        $can_cancel = 1;
                    } else {
                        $can_cancel = 0;
                    }
                    if ($web_order ['PayStatus'] == 2 && ($order ['paytype'] == 'daofu' || ($order ['paytype'] == 'weixin' && $order ['paid'] == 0)) && strtotime ( $web_order ['ArrDate'] ) > time () && $web_order ['HotelCanPrepay'] == 1) {
                        $web_re_pay = 1;
                    } else {
                        $web_re_pay = 0;
                    }
                } else {
                    $can_cancel = 0;
                    $web_re_pay = 0;
                }
                if ($order ['status'] == 9) {
                    $web_check = 1;
                }
                $state ['can_cancel'] = $can_cancel;
                $state ['re_pay'] = $web_re_pay;
                $state ['web_check'] = $web_check;
                $state ['web_des'] = $web_des;
                $state ['web_comment'] = 0;
            }
        }
        return $state;
    }
    // 判断订单是否能支付
    function check_order_canpay($order, $pms_set) {
        $web_order = $this->get_order_info ( $order, $pms_set, 'key' );
        if (! empty ( $web_order )) {
            $status_arr = $this->pms_enum ( 'status' );
            foreach ( $order ['order_details'] as $od ) {
                $webs_orderid = $od ['webs_orderid'];
                if (! empty ( $web_order [$webs_orderid] )) {
                    $istatus = $status_arr [$web_order [$webs_orderid]->acct_stus];
                    if ($istatus != 1 && $istatus != 0) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
    }
    private function readDB() {
        static $db_read;
        if (! $db_read) {
            $db_read = $this->load->database ( 'iwide_r1', true );
        }
        return $db_read;
    }
    /**
     * @param unknown $inter_id
     * @param unknown $web_path 调用地址，含调用方法
     * @param unknown $send 发送数据
     * @param unknown $receive 接收数据，程序报错时，为报错信息
     * @param unknown $now 调用接口前时间戳
     * @param unknown $micro_receive_time 调用接口后micro_time()
     * @param array $func_data 调用方法的数据，如下单、查询订单接口把订单号传进来
     * @param array $params 参数数组，含 'run_alarm'(1:程序运行报错)
     */
    function check_web_result($inter_id, $web_path, $send, $receive, $now, $micro_receive_time, $func_data = array(), $params = array()) {
        $func_name = substr ( $web_path, strrpos ( $web_path, '/' ) + 1 );//取出调用方法名
        $func_name_des = $this->pms_enum ( 'func_name', $func_name );
        isset ( $func_name_des ) or $func_name_des = $func_name;//方法名描述
        $err_msg = '';//错误提示信息
        $err_lv = NULL;//错误级别，1报警，2警告
        $alarm_wait_time = 5;//默认超时时间
        if (! empty ( $params ['run_alarm'] )) {//程序运行报错，直接报警
            $err_msg = '程序报错,' . json_encode ( $receive, JSON_UNESCAPED_UNICODE );
            $err_lv = 1;
        } else {
            switch ($func_name) {//针对不同方法判断是否出错
                case 'Get_RmAvl' :
                    if (empty ( $receive->Get_RmAvlResult->GetRmAvl ) || empty ( $receive->Err->string [1] )) {
                        $err_msg = isset ( $receive->Err->string [0] ) ? $receive->Err->string [0] . '。' . $receive->Err->string [2] : '无';
                        $err_lv = 2;
                    }
                    break;
                case 'Mod_ResvInfo' :
                    if (empty ( $receive->Mod_ResvInfoResult ) || empty ( $receive->Err->string [1] )) {
                        $err_msg = isset ( $receive->Err->string [0] ) ? $receive->Err->string [0] . '。' . $receive->Err->string [2] : '无';
                        $err_lv = 1;
                    }
                    break;
                case 'Get_ResvInfo' :
                    if (empty ( $receive->Get_ResvInfoResult->ResvInfo ) || empty ( $receive->Err->string [1] )) {
                        $err_msg = isset ( $receive->Err->string [0] ) ? $receive->Err->string [0] . '。' . $receive->Err->string [2] : '无';
                        $err_lv = 2;
                    }
                    break;
                case 'Get_Hotels' :
                    if (empty ( $receive->Get_HotelsResult->HotelInfo ) || empty ( $receive->Err->string [1] )) {
                        $err_msg = isset ( $receive->Err->string [0] ) ? $receive->Err->string [0] . '。' . $receive->Err->string [2] : '无';
                        $err_lv = 2;
                    }
                    break;
                case 'Get_HotelsByBGY' :
                    if (empty ( $receive->Get_HotelsByBGYResult->HotelInfo ) || empty ( $receive->Err->string [1] )) {
                        $err_msg = isset ( $receive->Err->string ) ? $receive->Err->string : '无';
                        $err_lv = 2;
                    }
                    $alarm_wait_time = 20;//可根据方法不同设置不同的超时报警时间
                    break;
                case 'Get_HotelCollection' :
                    if (empty ( $receive->Get_HotelCollectionResult->HotelInfo ) || empty ( $receive->Err->string [1] )) {
                        $err_msg = isset ( $receive->Err->string [0] ) ? $receive->Err->string [0] . '。' . $receive->Err->string [2] : '无';
                        $err_lv = 2;
                    }
                    break;
                case 'Add_ResvInfo' :
                    if (empty ( $receive->Add_ResvInfoResult->ResvInfo ) || ! $receive->Err->string [1]) {
                        $err_msg = isset ( $receive->Err->string [0] ) ? $receive->Err->string [0] . '。' . $receive->Err->string [2] : '无';
                        $err_lv = 1;
                    }
                    $alarm_wait_time = 10;
                    break;
                case 'Get_SysConf' :
                    if (empty ( $receive->Get_SysConfResult->ResvInfo ) || ! $receive->Err->string [1]) {
                        $err_msg = isset ( $receive->Err->string [0] ) ? $receive->Err->string [0] . '。' . $receive->Err->string [2] : '无';
                        $err_lv = 2;
                    }
                    break;
                default :
                    break;
            }
        }
        $this->load->model ( 'common/Webservice_model' );
        $this->Webservice_model->webservice_error_log ( $inter_id, self::WEB_TYPE, $err_lv, $err_msg, array (
                'web_path' => $web_path,
                'send' => $send,
                'receive' => $receive,
                'send_time' => $now,
                'receive_time' => $micro_receive_time,
                'fun_name' => $func_name_des,
                'alarm_wait_time' => $alarm_wait_time 
        ), $func_data );
    }
}
class HotelInfo {
    public $htlcd = '099';
    public $htlnm = '';
    public $htlcity = '';
    public $T_channel = 'WX';
    public $rp_cd = '';
    public $htlcls = 99;
    public $htltyp = 0;
    public $personnelsum = 0;
    public $guestroom_area = 0;
    public $meal_area = 0;
    public $entertainment_area = 0;
    public $pricemax = 0;
    public $pricemin = 0;
    public $arr_dt = '';
    public $dpt_dt = '';
    public $breviary_img = '';
    public $email = '';
    public $fax = '';
    public $htl_imgarr = '';
    public $htl_intro = '';
    public $htl_map = '';
    public $htladdr = '';
    public $htlcoun = '';
    public $htlfeature = '';
    public $htlgeography = '';
    public $htlgro = '';
    public $htlmanner = '';
    public $htlpro = '';
    public $linkman = '';
    public $rm_list = array (); // 该酒店房类列表roominfo
    public $rp_nm = '01';
    public $sales_promotion = '';
    public $tel = '';
    public $web = '';
    public $ic_num = '';
    public $company = '';
    public $resv_parameter = '';
    public $specialinfo = '';
    public $trn_flg = ''; // 为1则为推荐房类
    public $pic_info = '';
    public $meal_typ = '';
    public $freermurl = '';
    public $cb_amt = 0;
    public $deal_subscription = '';
    public $su_tm = 0;
}
class RoomInfo {
    public $htl_cd = '099';
    public $rm_cd = '';
    public $rm_nm = '';
    public $rm_sum = 0;
    public $rm_area = 0;
    public $bed_num = 0;
    public $price = 0;
    public $favourable_price = 0;
    public $meal = 0;
    public $meal_add_pic = 0;
    public $bed_add_pic = 0;
    public $full = false;
    public $arr_dt = '2014-12-25T00:00:00';
    public $dpt_dt = '2014-12-30T00:00:00';
    public $comm_amt = 0;
    public $fxcomm_amt = 0;
    public $quty_vlu = 0;
    public $cb_amt = 0;
    public $deal_subscription = 0;
    public $su_tm = 0;
    public $remark = 0;
    public $bed_establishment = '';
    public $rm_about = '';
    public $rm_img = array ();
    public $rm_service = '';
    public $dcomm_amt = '';
    public $dfxcomm_amt = '';
    public $drt_amt = '';
    public $ic_num = '';
    public $company = '';
    public $resv_parameter = '';
    public $specialinfo = '';
    public $trn_flg = '';
    public $pic_info = '';
    public $meal_typ = '';
    public $freermurl = '';
}
class GetRmAvl {
    public $arr_dt = '';
    public $dpt_dt = '';
    public $rm_avl = 0;
    public $tot_avl = 0;
    public $htl_list = '';
    public $channel_cd = 'WX';
    public $rm_list = '';
    public $day_amt = '';
}
class ResvInfo {
    public $saasacctnum = '';
    public $acctnm = '';
    public $payment_type = '';
    public $acctnum = '';
    public $acct_stus = '';
    public $careificate_num = '';
    public $careificate_typ = '';
    public $everyday_amt = '';
    public $contact = '';
    public $e_mail = '';
    public $htl_nm = '';
    public $ic_num = '';
    public $manipulate = 0;
    public $arr_dt = '0001-01-01';
    public $dpt_dt = '0001-01-01';
    public $pay_num = '';
    public $arr_tm = '';
    public $rt_amt = 0;
    public $deal_subscription = 0;
    public $Apply_nums = 1;
    public $su_tm = 0;
    public $Favor_id = 0;
    public $Apply_id = 0;
    public $favor_num = 0;
    public $fst_arr_tm = '0001-01-01';
    public $lst_arr_tm = '0001-01-01';
    public $remark = '';
    public $rm_nm = '';
    public $rm_typ = '';
    public $resvnum = '';
    public $geo1 = '';
    public $company_num = '';
    public $service_list = '';
    public $trust_num = '';
    public $crdt_cd = '';
    public $fax = '';
    public $channel_cd = 'WX';
    public $comm_amt = '';
    public $dcomm_amt = '';
    public $Fxcomm_amt = '';
    public $dfxcomm_amt = '';
    public $arr_flt = '';
    public $gh_num = '';
    public $bed_amt = 0;
    public $Meal_cnt = 0;
    public $subscription = 0;
    public $room_num = 0;
    public $bed_add = 0;
    public $breakfast_amt = 0;
    public $breakfast_add = 0;
    public $org_dt = '0001-01-01';
    public $people_num = 1;
    public $affirm_typ = 0;
    public $trust_dt = '0001-01-01';
    public $htl_typ = '';
    public $Favor_nums = 0;
    public $contact_tel = '';
    public $htl_cd = '099'; // 099为测试酒店
    public $coupon_amt = 0.0; // 新加属性
    public $crs_resvnum = ''; // 新加属性
    public $sw_crsresv_num = ''; // 新加属性
    public $org_cd = ''; // 新加属性
}
class UserInfo_1 {
    public $Ic_num = "";
    public $Ic_typ = "Q";
    public $Ic_ref = "";
    public $Ic_pwd = "";
    public $ic_stus = "";
    public $gh_num = "";
    public $Company_num = "";
    public $tot_rvu = 0;
    public $gh_typ = "";
    public $gh_nm = "";
    public $addr = "";
    public $postal = "";
    public $mobile = "";
    public $email = "";
    public $officefax = "";
    public $geo1 = "";
    public $geo2 = "";
    public $geo3 = "";
    public $crtf_typ = "";
    public $crtf_num = "";
    public $birthday = "0001-01-01";
    public $sex_cd = "01";
    public $nation = "";
    public $notice = "";
    public $tot_score = 0;
    public $phone = "";
    public $degree = "";
    public $htl_cd = "";
    public $lang_cd = "";
    public $officephone = "";
    public $Degree_cd = "";
    public $crd_num = "";
    public $crtf_nm = "";
    public $vip = "";
    public $interest = "";
    public $hskp_notice = "";
    public $pos_notice = "";
    public $org_dt = "0001-01-01";
    public $org_oper = "";
    public $bind_dt = "0001-01-01";
    public $chg_oper = "";
    public $geo1_nm = "";
    public $geo2_nm = "";
    public $geo3_nm = "";
    public $org_cd = "";
    public $hissumscore = 0;
    public $ic_bal = 0;
    public $trnflg = "";
    public $staffname = "";
    public $UniteParam = "";
    public $staffhtlcd = "";
    public $ed_dt = "";
    public $other = "";
    public $typdrpt = "";
    public $flgdrpt = "";
    public $s_score = "";
    public $is_notlogin = '';
    public $introducer = "";
    public $track2 = "";
    public $track3 = "";
    public $reco_person = "";
    public $send_oper = "";
    public $to_dt = "";
    public $quad_rt = "";
    public $crd_dt = "0001-01-01";
    public $crtf_dt = "0001-01-01";
    public $ic_score = 0;
    public $tot_vst = 0;
}
class SysConf {
    public $adj_cd;
    public $htl_cd;
    public $para_cd;
    public $para_drpt;
    public $para_typ;
    public $subcd_flg;
    public $trn_flg;
    public $trn_cd;
    public $quad_rt = 0;
    public $sgl_rt = 0;
    public $dbl_rt = 0;
}
class IcTypUpdateInfo_1 {
    public $Ic_typ = 'Q';
    public $Ic_num = '';
    public $Para_drpt = '';
    public $Para_cd = '01';
    public $Trn_flg = 'R';
    public $Sgl_rt = 0.00;
    public $Dbl_rt = 0.00;
}