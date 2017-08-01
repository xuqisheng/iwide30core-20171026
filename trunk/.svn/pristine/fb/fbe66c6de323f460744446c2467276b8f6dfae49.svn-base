<?php
/**
 * 订房模块接口
 * @author  luguihong <luguihong@mofly.com>
 */
class Api_hotel extends Soma_base {

    const BOOKING_STATUS_TURE = 'available';//可预订

    const BOOKING_STATUS_FULL = 'full';//满房

    const CAN_BOOKING_TRUE = 1;//可订
    const CAN_BOOKING_FULL = 3;//满房
    const CAN_BOOKING_FALSE = 2;//不可订

	protected $HotelModel;

	public function __construct( $inter_id=NULL ) {

		$CI = &get_instance();
		$CI->load->model('hotel/Package_model','HotelModel');
		$this->HotelModel  = $CI->HotelModel;
	}

	/**
     * 把请求/返回记录记入文件
     * @param String $content
     * @param string $type
     */
    public function _write_log( $content, $type='request', $file=NULL )
    {
        if($file==NULL) $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'soma'. DS. 'booking_hotel'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $path. $file, 'a');
    
        $content= str_repeat('-', 40). "\n[". $type. ' : '. date('Y-m-d H:i:s'). ' : '. $ip. ']'
            . "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }

	//获取单个房态的日期是否可订
	public function get_rooms( $openid, $inter_id, $hotel_id, $room_id, $price_code, $start=NULL, $end=NULL )
	{
		if( empty($openid) ) $this->show_exception('openid can not be empty!');
		if( empty($inter_id) ) $this->show_exception('Inter_id can not be empty!');
		if( empty($hotel_id) ) $this->show_exception('Hotel_id can not be empty!');
		if( empty($room_id) ) $this->show_exception('Room_id can not be empty!');
		if( empty($price_code) ) $this->show_exception('Price_code can not be empty!');

        $hotel_rooms = array( $hotel_id => array( $room_id => array( $price_code ) ) );

        // $start = isset( $start ) ? $start : date('Ym01');//查询房态的开始时间  默认为当前月的1号
        $start = isset( $start ) ? $start : date('Ymd');//查询房态的开始时间  默认为当前月的1号
		$start_day = date('d',strtotime( $start ) );//查询房态的开始时间  默认为当前月的1号
        //结束时间都以下个月1号为结束
        $start_one = date( "Ym01", strtotime( $start ) );
        $end = isset( $end ) ? $end : date( "Ym01", strtotime( "{$start_one} +1 month" ) );
		// $end = isset( $end ) ? $end : date('Ym31');//查询房态的结束时间  默认为当前月的31号
		
		$params = array();
		$params['openid'] = $openid;
		$params['rtype'] = 'room';//默认room

		//暂时返回的是数组
		$request = array(
				'inter_id'=>$inter_id,
				'hotel_id'=>$hotel_id,
				'hotel_rooms'=>$hotel_rooms,
				'openid'=>$openid,
				'start_date'=>$start,
				'end_date'=>$end,
			);

		$this->_write_log( json_encode($request, JSON_UNESCAPED_UNICODE), 'request: hotel/Package_model/get_hotels_roomstate' );
        $result = $this->HotelModel->get_hotels_roomstate( $inter_id, $start, $end, $hotel_rooms, $params );
        $this->_write_log( json_encode( $result ), 'response' );
        
        //处理数据
        $return = array( 'status'=>Soma_base::STATUS_FALSE, 'message'=>'', 'data'=>array() );//标记日历是否可订);//组装成一个月内的房态
        if( isset( $result['s'] ) && !empty( $result['s'] ) && $result['s'] == Soma_base::STATUS_TRUE ){
        	// var_dump( $result['data'] );
            
            //有数据返回
            $return['status'] = Soma_base::STATUS_TRUE;

        	//s=1代表调用成功，s=0 则调用失败 会返回errmsg
            if( isset( $result['data'] ) && !empty( $result['data'] ) ){
                //拆解数据，组装成日历是否可用
                foreach( $result['data'] as $k=>$v ){
                    //$k = hotel_id $v['room_state']
                    if( isset( $v['room_state'] ) && !empty( $v['room_state'] ) ){
                        foreach( $v['room_state'] as $sk=>$sv ){
                            if( $sk == $room_id ){
                                //$sk = room_id $sv['state_info']
                                $return['data']['room_name'] = isset( $sv['room_info']['name'] ) ? $sv['room_info']['name'] : '';
                                $return['data']['code_name'] = isset( $sv['state_info'][$price_code]['price_name'] ) ? $sv['state_info'][$price_code]['price_name'] : '';
                                if( isset( $sv['state_info'] ) && !empty( $sv['state_info'] ) ){
                                    foreach( $sv['state_info'] as $ssk=>$ssv ){
                                        //$ssk = price_code $ssv['date_detail']
                                        if( isset( $ssv['date_detail'] ) && !empty( $ssv['date_detail'] ) ){

                                            //20161228 luguihong 如果时间不是从一号算起，防止订房没有返回这部分数据，做预处理 start
                                            $first_date = date('Ym01');
                                            if( strtotime( $start ) > strtotime( $first_date ) ){

                                                //因为没有订房数据返回，所以这里处理为全部不可选
                                                $pre_treated_date = date( 'Y-m', strtotime( $start ) );

                                                //日期使用这样横杠的方式，是为了前端输出(天前面不能带0，例如：01、02)
                                                for( $i=1; $i<$start_day; $i++ ){
                                                    $return['data']['rooms']['un_can_booking'][$pre_treated_date.'-'.$i] = array(
                                                                                'can_booking'=>self::CAN_BOOKING_FULL,
                                                                                'num'=>0,
                                                                                'price'=>0,
                                                                            );
                                                }
                                            }
                                            //20161228 luguihong 如果时间不是从一号算起，防止订房没有返回这部分数据，做预处理 end

                                            foreach( $ssv['date_detail'] as $sssk=>$sssv ){
                                                $sssk = date( 'Y-m-j', strtotime( $sssk ) );
                                                if( $sssv['nums'] > 0 ){
                                                    $return['data']['rooms']['can_booking'][$sssk] = array(
                                                            'can_booking'=>self::CAN_BOOKING_TRUE,//可订
                                                            // 'state'=>'可定',//可定
                                                            'num'=>$sssv['nums'],//可订数量
                                                            'price'=>$sssv['price'],//当天价格
                                                        );
                                                }else{
                                                    $return['data']['rooms']['un_can_booking'][$sssk] = array(
                                                            'can_booking'=>self::CAN_BOOKING_FULL,//满房
                                                            'num'=>0,//可订数量
                                                            'price'=>$sssv['price'],//当天价格
                                                        );
                                                }
                                            }
                                        }else{
                                            $return['message'] = '获取订房的可订日期信息为空';
                                        }
                                    }
                                }else{
                                    $return['message'] = '获取订房的价格代码信息为空';
                                }
                            }
                        }
                    }else{
                        $return['message'] = '获取订房的房型信息为空';
                    }
                }
            }else{
                $return['message'] = '获取订房的数据为空';
            }
        }else{
            $return['message'] = isset( $result['errmsg'] ) ? $result['errmsg'] : '获取订房数据失败';
        }

        return $return;

	}

    //获取一个月的不可用状态
    public function get_un_booking( $start=NULL )
    {
        if( !$start ){
            $date = date( 'Y-m', time() );
        }else{
            //因为没有订房数据返回，所以这里处理为全部不可选
            $date = date( 'Y-m', strtotime( $start ) );
        }

        //日期使用这样横杠的方式，是为了前端输出(天前面不能带0，例如：01、02)
        $arr = array();
        for( $i=1; $i<=31; $i++ ){
            $arr[$date.'-'.$i] = array(
                                        'can_booking'=>self::CAN_BOOKING_FULL,
                                        'num'=>0,
                                        'price'=>0,
                                    );
        }

        return $arr;
    }

    //提交订房信息
    public function post_booking_room( $inter_id, $params )
    {
        $this->_write_log( json_encode($params, JSON_UNESCAPED_UNICODE), 'request: hotel/Package_model/package_to_order' );
        $result = $this->HotelModel->package_to_order( $inter_id, $params );
        $this->_write_log( json_encode( $result ), 'response' );

        //处理数据
        $return = array( 'status'=>Soma_base::STATUS_FALSE, 'message'=>'', 'data'=>array() );//标记日历是否可订);//组装成一个月内的房态
        if( isset( $result['s'] ) && !empty( $result['s'] ) && $result['s'] == Soma_base::STATUS_TRUE ){
            $return['status'] = Soma_base::STATUS_TRUE;
            $return['data'] = $result['data'];
            /*
                array(
                    'orderid'=>''//微信订单号 内部接口都用此单号 
                    'show_orderid'=>''//展示给客人的订单号 当不对接pms时，show_orderid与orderid相同，对接pms时，show_orderid为pms订单号 
                  )
            */
        }else{
            $return['message'] = isset( $result['errmsg'] ) ? $result['errmsg'] : '订房失败';
        }

        return $return;

    }


}

