<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 返回开始日期与结束日期间的天数，可指定返回string或array
 *
 * @param string $start
 *        	开始日期
 * @param string $end
 *        	结束日期
 * @param string $type
 *        	返回类型
 * @param string $format
 *        	日期格式
 */
function get_day_range($start, $end, $type = 'string', $format = "Ymd") {
	$start = date ( 'Ymd', strtotime ( $start ) );
	$end = date ( 'Ymd', strtotime ( $end ) );
	if ($type == 'string') {
		$range = '';
		for(; $start <= $end;) {
			$range .= ',' . date ( $format, strtotime ( $start ) );
			$start = date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $start ) ) );
		}
		$range = substr ( $range, 1 );
	} else if ($type == 'array') {
		$range = array ();
		for(; $start <= $end;) {
			$range [] = date ( $format, strtotime ( $start ) );
			$start = date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $start ) ) );
		}
	}
	return $range;
}
/**
 *
 * @param array $check_date
 *        	进行查找的日期数组 如array('20160311-20160322','20160328')(连续日期的开始日期与结束日期用'-'分隔，单个日期作为单个元素)
 * @param array $in_date
 *        	要查找的日期的数组 如array('20160322','20160401') 元素都为单个日期
 * @return boolean 
 */
function check_date_in($check_date, $in_date) {
	$count = count ( $check_date );
	$in_date = array_flip ( $in_date );
	for($i = 0; $i < $count; $i ++) {
		if (strstr ( $check_date [$i], '-' )) {
			$randay = explode ( '-', $check_date [$i] );
			foreach ( $in_date as $d => $v ) {
				if ($d >= $randay [0] && $d <= $randay [1]) {
					return TRUE;
				}
			}
		} else if (isset ( $in_date [$check_date [$i]] )) {
			return TRUE;
		}
	}
	return false;
}
/**
 * Unix to "Human"
 *
 * Formats Unix timestamp to the following prototype: 2006-08-21 11:35 PM
 *
 * @param
 *        	int Unix timestamp
 * @param
 *        	bool whether to show seconds
 * @param
 *        	string format: us or euro
 * @return string
 */
function unix_to_human($time = '', $seconds = FALSE, $fmt = 'us') {
	if (! empty ( $time )) {
		$r = date ( 'Y', $time ) . '-' . date ( 'm', $time ) . '-' . date ( 'd', $time ) . ' ';
		
		if ($fmt === 'us' || $fmt === 'cn') {
			$r .= date ( 'h', $time ) . ':' . date ( 'i', $time );
		} else {
			$r .= date ( 'H', $time ) . ':' . date ( 'i', $time );
		}
		
		if ($seconds) {
			$r .= ':' . date ( 's', $time );
		}
		
		if ($fmt === 'us') {
			return $r . ' ' . date ( 'A', $time );
		}
		return $r;
	} else {
		return "--";
	}
}

/**
 * 返回开始日期与结束日期间的间夜数
 *
 * @param string $start
 *        	开始日期
 * @param string $end
 *        	结束日期
 * @param string $type
 *        	取整函数
 */
function get_room_night($start, $end , $type = 'round' , $order = array()) {
	if($type == 'ceil'){
		$room_night = ceil ( (strtotime ( $end ) - strtotime ( $start )) / 86400 );
	}else{
		$room_night = round ( (strtotime ( $end ) - strtotime ( $start )) / 86400 );
	}
    if($room_night <= 0) $room_night = 1;//至少有一个间夜
    return $room_night;
}