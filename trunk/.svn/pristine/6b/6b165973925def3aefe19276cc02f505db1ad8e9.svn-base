<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Xu8Test extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	var $username = 'crs.test';
	
	var $key = 'crs.test.pass';
	
	
	
	public function __construct() {
		parent::__construct();
		//$this->load->database();
	}
	
	public function index()
	{
		
		//header("content-type:text/html;charset=utf-8");
		
		
		$username = 'crs.test';
		$key = 'crs.test.pass';
		
		
		$now_time = time();
		
		
		$sign = md5( $username + $now_time + $key );
		
		
		//用了ip白名单，不需要作认证
		/* $header_array = array(
			'UserName'=>$username,
			'TS'=>$now_time,
			'Sign'=>$sign
			
		); */
		
		try {
			$client = new SoapClient("http://ct.super8.com.cn/WeChatAPI/Region.svc?wsdl");
			
			print_r($client->GetCity());
			//$client->
			//print_r(array($headers));
			//exit;
			//print_r($client->__getFunctions());
		//print_r($client->__getTypes()); 
		} catch (SOAPFault $e) {
			print $e;
		}
		
		
	}
	
	public function getHotel(){
		
		try {
			$client = new SoapClient("http://ct.super8.com.cn/WeChatAPI/Hotel.svc?wsdl");
			echo "debug1018:<Br>\n\r";
			$searchModel = array(
				//	'HotelID'=>'',
					'CityCode'=>'110100',
					//'RegionCode'=>'',
					//'LandMarkID'=>'',
					'ArrDate'=>'2016-04-16',
					'OutDate'=>'2016-04-17',
					'RoomCount'=>1,
				/* 	'Honour'=>'',
					'NewOpen'=>'',
					'Exchange'=>'',
					'Vouchers'=>'',
					'Longitude'=>'',
					'Latitude'=>'', */
					'SortType'=>1,
					'PageIndex'=>1,
					'PageSize'=>10
					);
	
			$searchModel = $searchModel;
			$send_message = array(
					"searchModel"=>$searchModel
					
			);
			
			$send_message = $send_message;
			//print_r($send_message);
			
			//print_r($client->getError());
			//exit;
				
			print_r($client->GetHotels($send_message));
			
		} catch (SOAPFault $e) {
			print $e;
		}
		
// 		/961
	}
	
	public function getHotelByHours(){
	
		try {
			$client = new SoapClient("http://ct.super8.com.cn/WeChatAPI/Hotel.svc?wsdl");
			echo "debug1018:<Br>\n\r";
			
			//print_r($searchModel);
			
			$searchModel = array(
				//	'HotelID'=>'',
					'CityCode'=>'110100',
					//'RegionCode'=>'',
				//	'LandMarkID'=>'',
					'ArrDate'=>'2016-04-24',
					'OutDate'=>'2016-04-25',
					'RoomCount'=>1,
					/* 'Honour'=>'',
					'NewOpen'=>'',
					'Exchange'=>'',
					'Vouchers'=>'',
					'Longitude'=>'',
					'Latitude'=>'', */
					'SortType'=>1,
					'PageIndex'=>1,
					'PageSize'=>10
			);
	
			$searchModel = $searchModel;
			$send_message = array(
					"searchModel"=>$searchModel
						
			);
				
			$send_message = $send_message;
			//print_r($send_message);
				
			//print_r($client->getError());
			//exit;
	
			print_r($client->GetHotels($send_message));
				
		} catch (SOAPFault $e) {
			print $e;
		}
	
		// 		/961
	}
	
	
	public function getHotelDetail(){
	
		try {
			$client = new SoapClient("http://ct.super8.com.cn/WeChatAPI/Hotel.svc?wsdl");
			
			echo "debug1001:<Br>\n\r";
	
			print_r($client->GetHotelDetail(array('hotelID'=>961)));
				
		} catch (SOAPFault $e) {
			print $e;
		}
	
		// 		/961
	}
	
	public function getHotelRoom(){
	
		try {
			$client = new SoapClient("http://ct.super8.com.cn/WeChatAPI/Hotel.svc?wsdl");
				
			echo "debug1002:<Br>\n\r";
			
			$searchModel = array(
					'HotelID'=>'961',
					'ArrDate'=>'2016-04-16',
					'OutDate'=>'2016-04-17',
					'RoomCount'=>1,
					'RoomTypeID'=>'',
					'RoomCode'=>'',
					'CardTypeID'=>''
			);
			
			$searchModel = $searchModel;
			$send_message = array(
					"searchModel"=>$searchModel		
			);
				
			$send_message = $send_message;

			print_r($client->GetHotelRooms($send_message));
			
			
		} catch (SOAPFault $e) {
			print $e;
		}
	
		// 		/961
	}
	
	
	public function testApi(){
	
		$json = file_get_contents ( 'php://input' );
		
		echo $json."\n\r\n\r";
		
		$json_arr = $this->objectToArray( json_decode ( $json, TRUE ) );
		
		//echo $json."\n\r\n\r";
		
		$url = urldecode( $json_arr['url'] );
		
		echo "url\n\r\n\r";
		echo $url;
		
		$method_name = $json_arr['method_name'];
		
		$send_data = $json_arr['data'];
		
		echo "\n\r\n\rjson_arr:\n\r";
		
		print_r($json_arr);
		
		echo "\n\r\n\rsendData:\n\r";
		
		print_r($send_data);
		
		if( $json_arr['debug'] == '1' ){
			
			exit;
			
		}
		
		try {
			$client = new SoapClient($url);
			
			
			
			//echo "debug1016:<Br>\n\r";
			/* $searchModel = array(
					'HotelID'=>'',
					'CityCode'=>'110100',
					'RegionCode'=>'',
					'LandMarkID'=>'',
					'ArrDate'=>'2016-04-24',
					'OutDate'=>'2016-04-25',
					'RoomCount'=>1,
					'Honour'=>'',
					'NewOpen'=>'',
					'Exchange'=>'',
					'Vouchers'=>'',
					'Longitude'=>'',
					'Latitude'=>'',
					'SortType'=>1,
					'PageIndex'=>1,
					'PageSize'=>10
			);
	
			$searchModel = $searchModel;
			$send_message = array(
					"searchModel"=>$searchModel
	
			);
	
			$send_message = $send_message; */
			//print_r($send_message);
	
			//print_r($client->getError());
			//exit;
	
			if($send_data == ''){
				
				print_r($client->$method_name());
				
			}else{
				
				print_r($client->$method_name($send_data));
				
			}
			
	
		} catch (SOAPFault $e) {
			print $e;
		}
	
		// 		/961
	}
	
	public function reg(){
	
		try {
			$client = new SoapClient("http://ct.super8.com.cn/WeChatAPI/Mem.svc?wsdl");
			echo "debug1001:<Br>\n\r";
			$searchModel = array(
	
			'OperationType'=>'1',
	
			'CustomeName'=>'测试人',
			'Password'=>'123456',
			
			'PhoneNum'=>'13560000000',
			'ActivateChannel'=>6,
			'ActivateCode'=>'微信2013'
			
			);
	
			$searchModel = $searchModel;
			$send_message = array(
					"registerModel"=>$searchModel
						
			);
				
			$send_message = $send_message;
			//print_r($send_message);
				
			//print_r($client->getError());
			//exit;
	
			print_r($client->Register($send_message));
				
		} catch (SOAPFault $e) {
			print $e;
		}
	
		// 		/961
	}
	
	private function sendModelMsg(){
		
		
		
		
	}
	
	private function objectToArray($e){
		
	    $e=(array)$e;
	    foreach($e as $k=>$v){
	        if( gettype($v)=='resource' ) return;
	        if( gettype($v)=='object' || gettype($v)=='array' )
	            $e[$k]=(array)$this->objectToArray($v);
	    }
	    return $e;
	}
	
	public function testClass(){
		
		//include_once "Super8Webservice.php";
		
		$this->load->library('MyLibs/Super8Webservice',array(
				'testModel'=>true
				
		),'s8');
		
		//localhost/iwide_test/iwide3_0/controllers/front/api/testApiClass.php
		
	/* 	$s8 = new Super8Webservice(true,array(
				
				'testModel' => true
		)); */
		
		/* if($_GET['m']!=""){
		
		$method_name = $_GET['m'];
		
		$s8->$method_name()
		
		} */
		
		
		$this->s8->GetHotelImgs(931);
		
	}
	
	
	public function localTest(){
	
		$json = file_get_contents ( 'php://input' );
	
		//echo $json."\n\r\n\r";
	
		$json_arr = $this->objectToArray( json_decode ( $json, TRUE ) );
	
		//echo $json."\n\r\n\r";
	
		$url = urldecode( $json_arr['url'] );
	
		//echo "url\n\r\n\r";
	//	echo $url;
	
		$method_name = $json_arr['method_name'];
	
		$send_data = $json_arr['data'];
	
		//echo "\n\r\n\rjson_arr:\n\r";
	
		//print_r($json_arr);
	
		//echo "\n\r\n\rsendData:\n\r";
	
		//print_r($send_data);
	
		if( $json_arr['debug'] == '1' ){
				
			exit;
				
		}
	
		try {
			$client = new SoapClient($url);
				
				
				
			//echo "debug1016:<Br>\n\r";
			/* $searchModel = array(
			 'HotelID'=>'',
					'CityCode'=>'110100',
					'RegionCode'=>'',
					'LandMarkID'=>'',
					'ArrDate'=>'2016-04-24',
					'OutDate'=>'2016-04-25',
					'RoomCount'=>1,
					'Honour'=>'',
					'NewOpen'=>'',
					'Exchange'=>'',
					'Vouchers'=>'',
					'Longitude'=>'',
					'Latitude'=>'',
					'SortType'=>1,
					'PageIndex'=>1,
					'PageSize'=>10
			);
	
			$searchModel = $searchModel;
			$send_message = array(
					"searchModel"=>$searchModel
	
			);
	
			$send_message = $send_message; */
			//print_r($send_message);
	
			//print_r($client->getError());
			//exit;
	
			if($send_data == ''){
	
				$data = $client->$method_name();
	
			}else{
	
				$data = $client->$method_name($send_data);
	
			}
			
			ob_clean();
			echo json_encode( $data,JSON_UNESCAPED_UNICODE );
				
	
		} catch (SOAPFault $e) {
			print $e;
		}
	
		// 		/961
	}
	
	
	
	
	
	
	
	
	
}
