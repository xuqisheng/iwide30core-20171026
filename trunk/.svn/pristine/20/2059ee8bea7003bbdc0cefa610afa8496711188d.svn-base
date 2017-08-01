<?php
	
	function rad($d){
		return $d * pi() / 180;
    }  
  
	function get_distance( $lng1,  $lat1,  $lng2,  $lat2){
		$radLat1 = rad($lat1);
		$radLat2 = rad($lat2);
		$a = $radLat1 - $radLat2;
		$b = rad($lng1) - rad($lng2);
		$s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
		$s = $s * 6378.137;
		$s = round($s * 10000) / 10000;
		return $s;
	}
	
	//将 BD-09 坐标转换成 GCJ-02坐标
	function bd2gcj($bd_lon,$bd_lat)
	{
		$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
		$x = $bd_lon - 0.0065;
		$y = $bd_lat - 0.006;
		$z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
		$theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
		$data['longitude'] = $z * cos($theta);
		$data['latitude'] = $z * sin($theta);
		return $data;
	}
	
	//将 GCJ-02 坐标转换成BD-09 坐标
	function gcj2bd($gg_lon,$gg_lat)
	{
		$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
		$x = $gg_lon;
		$y = $gg_lat;
		$z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
		$theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
		$data['longitude'] = $z * cos($theta) + 0.0065;
		$data['latitude'] = $z * sin($theta) + 0.006;
		return $data;
	}

    /**
     * 计算两个坐标之间的距离(米)
     * @param float $fP1Lat 起点(纬度)
     * @param float $fP1Lon 起点(经度)
     * @param float $fP2Lat 终点(纬度)
     * @param float $fP2Lon 终点(经度)
     * @return int
     */
    function distanceBetween($fP1Lat, $fP1Lon, $fP2Lat, $fP2Lon){
        $fEARTH_RADIUS = 6378137;
        //角度换算成弧度
        $fRadLon1 = deg2rad($fP1Lon);
        $fRadLon2 = deg2rad($fP2Lon);
        $fRadLat1 = deg2rad($fP1Lat);
        $fRadLat2 = deg2rad($fP2Lat);
        //计算经纬度的差值
        $fD1 = abs($fRadLat1 - $fRadLat2);
        $fD2 = abs($fRadLon1 - $fRadLon2);
        //距离计算
        $fP = pow(sin($fD1/2), 2) +
            cos($fRadLat1) * cos($fRadLat2) * pow(sin($fD2/2), 2);
        return intval($fEARTH_RADIUS * 2 * asin(sqrt($fP)) + 0.5);
    }