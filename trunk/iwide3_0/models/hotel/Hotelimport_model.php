<?php
class Hotelimport_model extends CI_Model {
	
	const TAB_HOTEL = 'iwide_hotels';
	const TAB_ROOM = 'iwide_hotel_rooms';
	const TAB_HOTEL_IMAGE = 'iwide_hotel_images';
	const TAB_HOTEL_ADD = 'iwide_hotel_additions';
	
	private $icon_arr = array(
	
			'100000001' => '&#xe8',
			'100000002' => '&#xe7',
			'100000003' => '&#xe7',
			'100000008' => '&#xeb',
			'100000009' => '&#xec',
			'100000013' => '&#xfa',
	
	);
	
	function __construct() {
		parent::__construct ();
	}
	
	
	public function subaImport(){
		
		$start = $this->input->get("start");
		
		$end = $this->input->get("end");
		
		$length = $this->input->get("length");
		
		$this->load->library ( 'Baseapi/Subaapi_webservice',array(
		
				'_testModel'=>false
		) );
		
		
		$inter_id = 'a455510007';
		
		$start = $start?$start:3251;
		
		$end = $end?$end:20000;
		//hotel_id >3164
		$sql = "
				SELECT 
					H.hotel_id,HA.hotel_web_id
				FROM
					".self::TAB_HOTEL." as H,
					".self::TAB_HOTEL_ADD." as HA
			
				WHERE
					H.inter_id = '{$inter_id}'
					AND H.hotel_id > {$start}
					AND H.hotel_id < {$end}
					AND H.hotel_id = HA.hotel_id
					AND HA.inter_id = H.inter_id
				";
		
		

		$hotel_data = $this->db->query($sql)->result_array();
		
	
	
		
		$hotel_array = array();
		
		foreach($hotel_data as $hotel){
			
			$hotel_array[ $hotel['hotel_web_id'] ] = $hotel['hotel_id'];
			
		}
		
		$suba = new Subaapi_webservice(true);
		//$suba->local_test = true;
		
		foreach( $hotel_array as $hotel_web_id => $hotel_id ){
			
			
		
			
			$hotel_img = $suba->GetHotelImgs($hotel_web_id);
			
			$hotel_img = $hotel_img['GetHotelImgsResult']['Content']['string'];
			
			$num = 0;
			foreach($hotel_img as $img_url){
				
				if($num < 5){
					
					$this->insertHotelImage($inter_id, $hotel_id, 0, $img_url,'' , 'hotel_lightbox');
					
					
				}
				
				$this->insertHotelImageAlbum($inter_id, $hotel_id, 0, $img_url,'' , 'hotel_album',85);

				
				$num++;
				
			}
			
			
			
		
		
			//$suba->local_test = true;
			$room_info = $suba->GetHotelRooms($hotel_web_id, "2016-05-28", "2016-05-29", 1);
			
			$rooms = $room_info['GetHotelRoomsResult']['Content']['HotelRoom'];
			
		
		
		
			foreach($rooms as $room){
					
				
				$room['RoomTypeID'];
				$room['RoomName'];
				$room['SpecialDesc'];
				$room['RoomPic'];
					
					
				$this->insertRoom($inter_id,$hotel_id,$room['RoomName'],'200','300',
						'',
						$room['RoomPic'], "", $room['RoomTypeID']);
					
				
					
			}
			
		
			$suba_hotel = $suba->GetHotelDetail($hotel_web_id);
				
				
			
			/* print_r($suba_hotel);
			 exit; */
			if( !$suba_hotel['GetHotelDetailResult']['IsError']){
			
				$service_arr = $suba_hotel['GetHotelDetailResult']['Content']['HotelServices']['HotelService'];
			
				if( empty($service_arr)){
						
					continue;
						
				}
			
				if( !isset( $service_arr[0] )){
						
					$service_arr[] = $service_arr;
						
				}
			
			
				$this->insertService($service_arr,$hotel_id);
			
			
			}
		
	
		}
	
		
		
		
		
		
		
		//print_r($room_info);
		/* [HotelID] => 931
		[HotelName] => 速8酒店广西北海北部湾广场店(内宾)
		[RoomTypeID] => 20531
		[RoomCode] => SK
		[RoomName] => 标准大床房
		[RoomArea] => 25.0
		[BedSize] => 1.8*2
		[BedName] => 大床
		[RoomConfig] => 0
		[SpecialDesc] => 0
		[RoomDesc] =>
		[IfAddBed] =>
		[AddBedPrice] => 0
		[AddBedDesc] =>
		[RoomPic] => http://admin.super8.com.cn/upload/Hotel/931/middle/32f16807-621a-445f-a5c8-60f49c062da4.jpg
		[IfAdvancePayMent] => 1
		[MinRoomPrice] => 99999
		[MinRoomQuantity] => 0
		[MinTeamBuyRoomQuantity] => 0
		[SpecialPriceType] => 0 */
		
	}
	
	public function subaImportOnlyRoom(){
	
	    $start = $this->input->get("start");
	
	    $end = $this->input->get("end");
	
	    $length = $this->input->get("length");
	
	    $this->load->library ( 'Baseapi/Subaapi_webservice',array(
	
	        '_testModel'=>false
	    ) );
	
	
	    $inter_id = 'a455510007';
	
	    $start = $start?$start:3251;
	
	    $end = $end?$end:20000;
	    //hotel_id >3164
	    $sql = "
				SELECT
					H.hotel_id,HA.hotel_web_id
				FROM
					".self::TAB_HOTEL." as H,
					".self::TAB_HOTEL_ADD." as HA
							
						WHERE
						H.inter_id = '{$inter_id}'
						AND H.hotel_id > {$start}
	    AND H.hotel_id < {$end}
	    AND H.hotel_id = HA.hotel_id
	    AND HA.inter_id = H.inter_id
	    ";
	
	
	
	    $hotel_data = $this->db->query($sql)->result_array();
	
	
	
	
	    $hotel_array = array();
	
	    foreach($hotel_data as $hotel){
	        	
	        $hotel_array[ $hotel['hotel_web_id'] ] = $hotel['hotel_id'];
	        	
	    }
	
	    $suba = new Subaapi_webservice(true);
	    //$suba->local_test = true;
	
	    foreach( $hotel_array as $hotel_web_id => $hotel_id ){
	        	
	        	
	
	        /* 	
	        $hotel_img = $suba->GetHotelImgs($hotel_web_id);
	        	
	        $hotel_img = $hotel_img['GetHotelImgsResult']['Content']['string'];
	        	
	        $num = 0;
	        foreach($hotel_img as $img_url){
	
	            if($num < 5){
	                	
	                $this->insertHotelImage($inter_id, $hotel_id, 0, $img_url,'' , 'hotel_lightbox');
	                	
	                	
	            }
	
	            $this->insertHotelImageAlbum($inter_id, $hotel_id, 0, $img_url,'' , 'hotel_album',85);
	
	
	            $num++;
	
	        } */
	        	
	        	
	        	
	
	
	        //$suba->local_test = true;
	        $room_info = $suba->GetHotelRooms($hotel_web_id, "2016-05-28", "2016-05-29", 1);
	        	
	        $rooms = $room_info['GetHotelRoomsResult']['Content']['HotelRoom'];
	        	
	
	
	
	        foreach($rooms as $room){
	            	
	
	            $room['RoomTypeID'];
	            $room['RoomName'];
	            $room['SpecialDesc'];
	            $room['RoomPic'];
	            	
	            	
	            $this->insertRoom($inter_id,$hotel_id,$room['RoomName'],'200','300',
	                '',
	                $room['RoomPic'], "", $room['RoomTypeID']);
	            	
	
	            	
	        }

	
	
	    }
	
	
	
	
	
	
	
	    //print_r($room_info);
	    /* [HotelID] => 931
	     [HotelName] => 速8酒店广西北海北部湾广场店(内宾)
	     [RoomTypeID] => 20531
	     [RoomCode] => SK
	     [RoomName] => 标准大床房
	     [RoomArea] => 25.0
	     [BedSize] => 1.8*2
	     [BedName] => 大床
	     [RoomConfig] => 0
	     [SpecialDesc] => 0
	     [RoomDesc] =>
	     [IfAddBed] =>
	     [AddBedPrice] => 0
	     [AddBedDesc] =>
	     [RoomPic] => http://admin.super8.com.cn/upload/Hotel/931/middle/32f16807-621a-445f-a5c8-60f49c062da4.jpg
	     [IfAdvancePayMent] => 1
	     [MinRoomPrice] => 99999
	     [MinRoomQuantity] => 0
	     [MinTeamBuyRoomQuantity] => 0
	     [SpecialPriceType] => 0 */
	
	}
	
	private function insertRoom($inter_id,$hotel_id,$name,$price,$oprice,
								$description, 
								$room_img, $sub_des, $webser_id
			){
		
		/* INSERT INTO `iwide_hotel_rooms` (`room_id`, `inter_id`, `hotel_id`, `name`, `price`, `oprice`, `description`, `nums`, `bed_num`, `area`, `status`, `sort`, `room_img`, `book_policy`, `sub_des`, `webser_id`) VALUES
		 (5175, 'a455510007', 1107, '经济大床房', '155.00', '1380.00', 'Business Room (One Bed) 位于酒店主楼一楼后段，房间设施有：两张小床或可拼大床、洗手间风格特异、宽带上网服务等，别墅或后花园山水景观。面积为36平方米', 10, 0, 0, 1, 0, 'http://file.iwide.cn/public/uploads/201603/a421641095hri_1_7.jpg', '', '', '2850');
		*/
		$sql = "
				SELECT
					count(room_id) as NUM
				FROM
					".self::TAB_ROOM."
				WHERE
					webser_id = '{$webser_id}'
					AND inter_id = '{$inter_id}'
		";
		
		$hotel_data = $this->db->query($sql)->result_array();
		
		
		if($hotel_data[0]['NUM'] > 0){
			
			return ;
			
		}
		
		
		$sql = "
				INSERT INTO 
					".self::TAB_ROOM."
						(`inter_id`, `hotel_id`, `name`, `price`, `oprice`,
						 `description`, `nums`, `bed_num`, `area`, `status`, `sort`, 
						`room_img`, `book_policy`, `sub_des`, `webser_id`) 
				      VALUES
						(
							'$inter_id',
							'$hotel_id',
							'$name',
							'$price',
							'$oprice',
							'$description',
							'10',
							'0',
							'0',
							'1',
							'0',
							'$room_img',
							'',
							'$sub_des',
							'$webser_id'
						)
				
				";
		
		
		echo $sql.';';
		return;
		$this->db->query($sql);
		
	}
	
	private function insertHotelImage($inter_id,$hotel_id,$room_id,$image_url,$info,$type){
		
		$sql = "
					INSERT INTO
					 ".self::TAB_HOTEL_IMAGE."
						(
						 `inter_id`,
						 `hotel_id` ,
						 `room_id`,
						 `image_url`,
						 `info`,
						 `type` 
						)
					VALUES
						(
							'{$inter_id}',
					 		'{$hotel_id}',
					 		'{$room_id}',
					 		'{$image_url}',
					 		'{$info}',
					 		'{$type}'
					 		
						)
		
				";
		
		echo $sql.";";
		
		//$this->db->query($sql);
		
	}
	
	private function insertHotelImageAlbum($inter_id,$hotel_id,$room_id,$image_url,$info,$type,$album_id){
	
		
	
	
		$sql = "
					INSERT INTO
					 ".self::TAB_HOTEL_IMAGE."
						 (
						 `inter_id`,
						 `hotel_id` ,
						 `room_id`,
						 `image_url`,
						 `info`,
						 `type`,
						 `disp_type`
						 )
						 VALUES
						 (
						 '{$inter_id}',
						 '{$hotel_id}',
						 	'{$room_id}',
						 	'{$image_url}',
						 	'{$info}',
						 	'{$type}',
							{$album_id}
						 )
	
						";
							
					echo $sql.";";
					
				 	//$this->db->query($sql);
	
	}
	
	private function insertHotelImageAlbum_sql($inter_id,$hotel_id,$room_id,$image_url,$info,$type){
	
		$sql = "
				INSERT INTO ".self::TAB_HOTEL_IMAGE."
						(`inter_id`, `hotel_id`, `room_id`, `image_url`, `info`, `type`, `sort`, `status`, `link`, `disp_type`)
	
					SELECT
						inter_id,hotel_id,room_id,image_url,info,'hotel_service',sort,status,link,840
					FROM ".self::TAB_HOTEL_IMAGE."
					WHERE
						inter_id = 'a455510007'
						AND type= 'hotel_lightbox'
	
							INSERT INTO iwide_hotel_images
						(`inter_id`, `hotel_id`, `room_id`, `image_url`, `info`, `type`, `sort`, `status`, `link`, `disp_type`)
	
					SELECT
						inter_id,hotel_id,room_id,image_url,info,'hotel_album',sort,status,link,840
					FROM  iwide_hotel_images
					WHERE
						inter_id = 'a455510007'
						AND type= 'hotel_lightbox'
				";
	
	
		$sql = "
					INSERT INTO
					 ".self::TAB_HOTEL_IMAGE."
						 (
						 `inter_id`,
						 `hotel_id` ,
						 `room_id`,
						 `image_url`,
						 `info`,
						 `type`
						 )
						 VALUES
						 (
						 '{$inter_id}',
						 '{$hotel_id}',
								'{$room_id}',
								'{$image_url}',
								'{$info}',
								'{$type}'
	
								)
	
								";
								$sql_arr[] = "('a455510007','{$hotel_id}',0,'{$icon}','{$icon_name}','hotel_service', 1, 1, '', 0)";
									
	
								$this->db->query($sql);
	
	}
	
	public function subaServiceImport(){
	
	//localhost/iwide/www_front/index.php/hotel/hotel_import/subaServiceImport
		$this->load->library ( 'Baseapi/subaapi_webservice',array(
	
				'_testModel'=>false
		) );
	
	
		$suba = new subaapi_webservice(true);
	
		$suba->local_test = true;
		print_r( $suba->GetHotelDetail(931) );
	
	
	}
	
	public function insertService($service_array,$hotel_id){
	
	
	/*
	*
	*  [5] => stdClass Object
		(
		 [DicFTypeID] => 3
		 [FtName] => 酒店服务
		 [FacilityTypeID] => 100000010
		 [FacilityName] => 电梯
		 [IFCharge] => 1
		 [ServiceDesc] => 1部
		 [ChargeDesc] =>
		 )
		 * 100000001 wifi &#xe8
	
			100000003 停车 &#xe7
	
			100000013 洗衣 &#xfa
	
			100000002 室内停车场 &#xe7
	
			100000009 会议室 &#xec
	
			100000011 接机  &#xed
	
			100000008 24小时热水 &#xeb */
			$sql = "
		 INSERT INTO `iwide_hotel_images` (
		 		`inter_id`, `hotel_id`, `room_id`, `image_url`, `info`, `type`, `sort`, `status`, `link`, `disp_type`)
		 VALUES
			";
	
			foreach($service_array as $service_info){
		 	
		 $service_info = (array)$service_info;
		 	
		 $icon = htmlspecialchars( $this->getIconById( $service_info['FacilityTypeID'] ) );
		 	
		 $icon_name = $service_info['FacilityName'];
		 	
		 if($icon){
		 	
		 $sql_arr[] = "('a455510007','{$hotel_id}',0,'{$icon}','{$icon_name}','hotel_service', 1, 1, '', 0)";
		 	
		 }
		 	
			}
	
	
	
			$addsql = implode(",", $sql_arr);
	
			if($addsql == ""){
		 	
				 echo $sql;
		 	
		 		 return ;
			}
	
			$sql .= $addsql.";";
			
			//$this->db->query($sql);
	
			echo $sql.";";
		
		/* (143, 'defaultimg', 0, 0, '&#xe7;', '停车', 'hotel_service', 1, 1, '', 0),
		(142, 'defaultimg', 0, 0, '&#xed;', '接机服务', 'hotel_service', 1, 1, '', 0),
			(141, 'defaultimg', 0, 0, '&#xe5;', '叫醒服务', 'hotel_service', 1, 1, '', 0),
			(138, 'defaultimg', 0, 0, '&#xe9;', '行李寄存', 'hotel_service', 1, 1, '', 0),
		(136, 'defaultimg', 0, 0, '&#xea;', '餐厅', 'hotel_service', 1, 1, '', 0),
			(135, 'defaultimg', 0, 0, '&#xe8;', 'Wifi', 'hotel_service', 1, 1, '', 0),
			(132, 'defaultimg', 0, 0, '&#xeb;', '热水', 'hotel_service', 1, 1, '', 0) */
	
	}
	
	
	public function getHotels(){
	
	$sql = "SELECT
	H.hotel_id,HA.hotel_web_id
	FROM
	iwide_hotels AS H,
	iwide_hotel_additions AS HA
	WHERE
	H.inter_id = 'a455510007'
	AND H.hotel_id = HA.hotel_id
	AND HA.hotel_web_id > 0
	AND H.hotel_id IN (1107,1108,1118,1133,1109)
	ORDER BY hotel_id ASC
	";
	
	$data = $this->db->query($sql)->result_array();
	
	
	$hotel_arr = array();
	
	foreach($data as $d){
		
	$hotel_arr[ $d['hotel_id'] ] = $d['hotel_web_id'];
		
	}
	
	
	return $hotel_arr;
	
	}
	
	private function getIconById($icon_id){
	
	
	if(isset( $this->icon_arr[$icon_id] ) ){
		
	return $this->icon_arr[$icon_id];
		
	}else{
		
	return "";
		
	}
	
	}
	
}