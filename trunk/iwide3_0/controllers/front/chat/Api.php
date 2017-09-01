<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

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
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function index()
	{
		
	}
	
	
	public function simplest_xml_to_array($xmlstring) {
	    return json_decode(json_encode((array) simplexml_load_string($xmlstring)), true);
	}
		
	public function wall() {
		$act = $this->input->get('act');
		$hid = $this->input->get('hid');
		$iad = $this->input->get('iad');
		if (strlen($iad)<1) {
			die();
		}
		$iad = intval($iad);
		if ($act == 'on') {
			if (strlen($hid)<1) {
				die();
			}
			
			$hid = intval($hid);
			
			$sql = "SELECT iwide_chat_wall.*,iwide_fans.sex,iwide_fans.nickname,iwide_fans.headimgurl FROM iwide_chat_wall LEFT JOIN iwide_fans ON iwide_chat_wall.openid = iwide_fans.openid ";
			$sql .= "AND iwide_chat_wall.inter_id = iwide_fans.inter_id WHERE iwide_chat_wall.iad='".$iad."' and iwide_chat_wall.id>".$hid." limit 10";
			$wall = $this->db->query($sql)->result_array();
			echo json_encode($wall);
			die();
		}
		
		$sql = "SELECT * FROM iwide_chat_wall_config WHERE id='".$iad."' limit 1";
		$wall = $this->db->query($sql)->result_array();
		
		if ($wall) {
			$data['wall'] = $wall['0'];
		}
		else {
			$data['wall'] = array();
		}
		
		$this->load->view('chat/default/wall/screen',$data);
	}
	
	public function notify() {		
		$ret = file_get_contents("php://input");

		preg_match_all("/<result_code><\!\[CDATA\[(.*?)\]\]><\/result_code>/is",$ret,$resultcode);
		if (!$resultcode[1]) {die();}
		
		preg_match_all("/<out_trade_no><\!\[CDATA\[(.*?)\]\]><\/out_trade_no>/is",$ret,$tradenoarr);
		$tradeno = $tradenoarr[1][0];
		
		preg_match_all("/<total_fee>(.*?)<\/total_fee>/is",$ret,$total_fee);
		$totalfee = intval($total_fee[1][0]);
		$totalfee = $totalfee/100;
		
		$rdata = qfselect("select * from ".$this->db->dbprefix."custom_info,".$this->db->dbprefix."custom where ".$this->db->dbprefix."custom_info.id='".$tradeno."' and ".$this->db->dbprefix."custom_info.cid=".$this->db->dbprefix."custom.id limit 1",$this->db);
		
		if (!$rdata) {
			echo 'nodata';die();
		}

		$price = $rdata[0]['price'];
		
		$coupon = $rdata[0]['coupon'];
		
		$cid = $rdata[0]['cid'];
		
		$this->db->update('custom_info',array('status'=>'3','payed'=>$totalfee),array('id'=>$tradeno));
			
		$this->db->update('coupon',array('status'=>'1'),array('coupon'=>$coupon,'sid'=>$cid));
		
	}
	
	public function xmlToArray($xml) {
		//将XML转为array
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $array_data;
	}
	
	public function shakingsendmsg() {}
	
	public function iscoupon() {
	
		$sid = $this->input->get('sid');
		$coupon = $this->input->get('coupon');
	
		$data = qfselect("select * from ".$this->db->dbprefix."coupon where sid='".$sid."' and coupon='".$coupon."' and status='0' limit 1",$this->db);
	
		$ret['code'] = 0;
		if ($data) {
			$ret['code'] = 1;
			$ret['status'] = $data[0]['status'];
			$ret['pricetype'] = $data[0]['pricetype'];
			$ret['pricerole'] = $data[0]['pricerole'];
			$ret['openid'] = $data[0]['openid'];
			
		}
		echo json_encode($ret);
	
	}
	
	public function tupload() {
		
		$this->load->view('chat/default/show_tupload',array());

	}
	
	public function qrcode() {
		$data = $this->input->get('data');
		if ($data) {
			require_once APPPATH.'helpers/qrcode_helper.php';
		    header("Content-type: image/gif");
		    $a=new QR($data);
		    echo $a->image(4);
		}
	}

	public function form() {
		$data['QUERY_STRING'] = $_SERVER['QUERY_STRING'];
		$iad = $this->input->get('iad');
		
		$formdata = $this->db->query("select * from ".$this->db->dbprefix."custom where id='".$iad."' limit 1")->result_array();
		if (!$formdata) {
			echo 'err:noform';
			die();
		}
		$data['formdata'] = $formdata[0];
		
		if (!$formdata[0]['toppic']) {
			header("location:/index.php/chat/fapi?".$data['QUERY_STRING']);
		}
		
		$this->load->view('chat/default/form/show_index',$data);
	}
	
	public function csrf() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		echo json_encode($data);
	}
	
	public function shake() {
		$act = $this->input->get('act');
		$iad = $this->input->get('iad');
		$inter_id = $this->input->get('id');
		
		$custom_shake = $this->db->query("SELECT * FROM ".$this->db->dbprefix."custom_shake where id='".$iad."' LIMIT 1")->result_array();
		if (!$custom_shake) {
			echo 'noinfo';
			die();
		}
		$isstart = $custom_shake[0]['isstart'];
		$totime = $custom_shake[0]['totime'];
		$nowtimes = $custom_shake[0]['nowtimes'];
		
		if ($act) {
			switch ($act) {
				case 'm':
					//手机端
					break;
				case 'sendmsg':
					
					$sendmsg = $custom_shake[0]['sendmsg'];
					if ($sendmsg) {
						$this->load->model ( 'wx/Access_token_model' );
						$access_token = $this->Access_token_model->get_access_token ( $inter_id );
		
						echo 'msg:';
						
						$uall = isset($_GET['u'])?$_GET['u']:'';
						$uarr = explode(',', $uall);
						
						$tall = isset($_GET['t'])?$_GET['t']:'';
						$tarr = explode(',', $tall);
						
						$lall = isset($_GET['l'])?$_GET['l']:'';
						$larr = explode(',', $lall);
						
						foreach ($uarr as $k=>$v) {
							if ($v) {
								$custom_shake_value = $this->db->query("SELECT * FROM ".$this->db->dbprefix."custom_shake_value where id='".intval($v)."' LIMIT 1")->result_array();
						
								if ($custom_shake_value) {
									$openid = $custom_shake_value[0]['openid'];			
									$pdata = array(
											'touser'=>$openid,
											'template_id'=>$sendmsg,
											'url'=>'',
											'topcolor'=>'#FF0000',
											'data'=>array(
													'first'=>array('value'=>'恭喜您，在摇一摇游戏中以'.$tarr[$k].'票获得了第'.$larr[$k].'名！。','color'=>'#173177'),
													'keyword1'=>array('value'=>'第'.$larr[$k].'名','color'=>'#173177'),
													'keyword2'=>array('value'=>date("Y年m月d日 H:i"),'color'=>'#173177'),
													'remark'=>array('value'=>'感谢您的使用，谢谢。','color'=>'#173177')
											)
									);
									
									for ($i = 0; $i < 3; $i++) {
										$ret = qfpost('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token,json_encode($pdata));
										$retobj = json_decode($ret);
										print_r($retobj);
										if ($retobj->errcode==0) {
											break;
										}
									}
								}
						
							}
						}
					}
					else {
						echo 'nomsg';
					}
					
					//先把本次数据归零
					$this->db->update('custom_shake',array('isstart'=>0,'nowtimes'=>$nowtimes+1),array('id'=>$iad));
					break;
				case 'g':
					$custom_shake_value = $this->db->query("SELECT * FROM ".$this->db->dbprefix."custom_shake_value where times='".$nowtimes."' and shid='".$iad."' order by num desc LIMIT 10")->result_array();
					echo json_encode($custom_shake_value);
					break;
				case 's':	
					if ($custom_shake) {
						if ( $isstart == 1 ) {
							$data['start'] = 1;
							$data['totime'] = $totime;
							if (time()-$totime>0) {				
								$this->db->update('custom_shake',array('isstart'=>0),array('id'=>$iad));
							}
						
						}
						else {
							$data['start'] = 0;
							$data['totime'] = $totime;
						}
						
						$data['ntime'] = time();
						echo json_encode($data);
						
					}
					break;
				case 'd':
					$this->db->update('custom_shake',array('isstart'=>1,'totime'=>((time()+30))),array('id'=>$iad));
					break;		
				default:
					echo 'err:1';
					break;
			}			
			die();
		}
		
		$data['shake'] = $custom_shake[0];
		$this->load->view('chat/default/shake/shake',$data);
	}
}
