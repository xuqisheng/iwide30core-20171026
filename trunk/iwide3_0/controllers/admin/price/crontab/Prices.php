<?php
/*
 * 获取公众号房型信息入库
 * Date 2016-08-22
 * author chenjunyu
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Prices extends MY_Controller {
	private $verifcode = array(
		'a421641095'=>'feTZhKgOqClIry7B',//碧桂园
		'a449675133'=>'UzylHGarFacxQmIK',//书香
		'a441098524'=>'ExG4SQkJbEvbuxXc',//逸柏
		'a445223616'=>'cEKm2ZywY9HOruJB',//云盟
		'a464919542'=>'Q6cgfdUjcN6mYWLO',//清沐
		'a455510007'=>'BMqlgUL4VhF2XrJc',//速8
		'a426755343'=>'GTf8LI2giy7HRWN1',//岭南佳园
		'a452223043'=>'p7H0molXrfAR9i8x',//莫林风尚
		'a464177542'=>'GjTKFq5ICLz9Ulgp',//百时快捷
		'a434597274'=>'fVAc5Rnaeoy0tjZG',//流花宾馆
		'a450682197'=>'Eg0zh8SwbgV18Thx',//江门柏丽宜居
		'a456970175'=>'oMImQhAz1P5s1biB',//君亭
		'a454320235'=>'UIAzITCW5hPHjdmr',//智尚
		'a457946152'=>'Gclejig0zn36SrNU',//隐居
		'a468209719'=>'ca7BezJnEiMhbs69',//戴斯
		'a472731074'=>'NUq09F5c1nMaKJjd',//金泰旅游
		'a440577876'=>'KjgSGuc9kHFdr0NC',//远洲
		'a472731996'=>'QscEdCFvlHBqUhk6',//雅斯特
		);
	public function __construct(){
		parent::__construct();
		// if($_SERVER['REMOTE_ADDR']!='112.124.42.69'&&$_SERVER['SERVER_ADDR']!=$_SERVER['REMOTE_ADDR']){
		// 	exit('非法访问！');
		// }
		if(!in_array($this->input->get('verifcode'),$this->verifcode)){
			exit('非法访问！');
		}
		$this->load->library('LOG');
	}

	public function getIwideRoomInfo(){
		set_time_limit(0);
		ini_set('memory_limit','-1');
		$this->load->model ( 'price/Order_api_model' );
		$interid = array_search($this->input->get('verifcode'),$this->verifcode);
		$where = "where (ctrip_id is not null or ctrip_id<>'') and grab_flag=1 and inter_id='".$interid."'";
		$out = $this->db->query("select * from ctrip_hotels ".$where);
		if(!$out){
			Log::w('对应酒店信息查询失败','prices');exit;
		}
		if(!$out->result_array()){
			Log::w('对应酒店信息查询结果为空','prices');exit;
		}
		$maxbatch = $this->db->query("select max(batch) as maxbatch from iwide_room_info where inter_id='".$interid."'");
		if(!$maxbatch){
			Log::w('获取上一次更新号失败','prices');exit;
		}
		$nowbatch = $maxbatch->row()->maxbatch>0?$maxbatch->row()->maxbatch+1:1;
		foreach ($out->result_array() as $k => $v) {
			$iwiderooms = $this->Order_api_model->get_roomstate($v['inter_id'],$v['hotel_id']);
			if($iwiderooms['result_code']==1&&$iwiderooms['errmsg']=='查询成功'){
				if(isset($iwiderooms['data'])&&is_array($iwiderooms['data'])){
					$values = '';
					foreach ($iwiderooms['data'] as $kr => $vr) {
						if(isset($vr['state_info'])&&is_array($vr['state_info'])){
							foreach ($vr['state_info'] as $ks => $vs) {
								$time = time();
								$values .= "(null,'{$vr['room_info']['room_id']}','{$v['inter_id']}','{$v['hotel_id']}','{$v['name']}','{$vr['room_info']['name']}','{$vs['price_name']}','{$vs['price_code']}','{$vs['total_price']}','{$vs['avg_price']}','$time','$nowbatch','{$vs['book_status']}'),";
							}
						}
					}
					if($values){
						$values = rtrim($values,',');
						$in = $this->db->query("insert into iwide_room_info values ".$values);
						if(!$in){
							Log::w('公众号酒店房型入库失败，hotel_id：'.$v['hotel_id'].'，inter_id：'.$v['inter_id'].'name：'.$v['name'],'psrices');
						}
					}
				}else{
					Log::w('房型信息为空，errno:'.$iwiderooms['result_code'].'，error:'.$iwiderooms['errmsg'].'，hotel_id：'.$v['hotel_id'].'，inter_id：'.$v['inter_id'].'，name：'.$v['name'],'prices');
				}
			}else{
				Log::w('接口获取公众号酒店房型信息失败，errno:'.$iwiderooms['result_code'].'，error:'.$iwiderooms['errmsg'].'，hotel_id：'.$v['hotel_id'].'，inter_id：'.$v['inter_id'].'，name：'.$v['name'],'prices');
			}
		}
		Log::w('接口获取公众号酒店房型信息更新完成，inter_id:'.$interid,'prices');
	}
}