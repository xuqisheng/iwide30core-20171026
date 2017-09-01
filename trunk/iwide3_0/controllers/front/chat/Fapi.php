<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fapi extends MY_Front {

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
	 * map to /index.php/welcome/method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		
		if ( empty ( $this->openid ) ) {
			$this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
		}
		if ( empty ( $this->inter_id ) ) {
			$this->inter_id = $this->session->userdata ( 'inter_id' );
		}
		
		if ( empty ( $this->openid ) ) {echo 'openid';die();	}

		$this->fans = $this->db->query("select * from ".$this->db->dbprefix."fans where openid='".$this->openid."'")->result_array();
		$this->fans = $this->fans[0];
		
	}
	
	
	
	public function index()
	{
		
		$id = intval($this->input->get('iad'));
		$retfail = $this->input->get('ret');
		if (!$id) {
			echo 'err:id';
			die();
		}
		
		
		$submit = $this->input->post('submit');
		$datav = array();
		
		$data = array('data'=>array(),'input'=>array());
		if ($id) {
		    $query = $this->db->query("SELECT * FROM ".$this->db->dbprefix."custom where id=".$id);
		    $ret = $query->result_array();
		    
		    $sql = "select ".$this->db->dbprefix."custom_card.id as ciid,".$this->db->dbprefix."custom_card.status as cardstatus,".$this->db->dbprefix."custom_info.id as iid,".$this->db->dbprefix."custom.starttime as starttime,".$this->db->dbprefix."custom.keyword as keyword,".$this->db->dbprefix."custom_info.*,".$this->db->dbprefix."custom_card.* from";
		    $sql = $sql." ".$this->db->dbprefix."custom_info,".$this->db->dbprefix."custom_card,".$this->db->dbprefix."custom where ".$this->db->dbprefix."custom_card.openid='".$this->openid."' and ".$this->db->dbprefix."custom_card.inter_id='".$this->inter_id."'";
		    $sql = $sql." and ".$this->db->dbprefix."custom_info.cid = '".$id."' and ".$this->db->dbprefix."custom_info.id=".$this->db->dbprefix."custom_card.infoid and ".$this->db->dbprefix."custom_info.cid=".$this->db->dbprefix."custom.id order by ".$this->db->dbprefix."custom_card.status asc limit 1";   
		    
		    $allorder = array();
		    $allorder = qfselect($sql,$this->db);
		    $data['allorder'] = $allorder;
		    
		    if ($ret) {
		    	$data['data'] = $ret['0'];
		    	$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix."custom_input where cid=".$id." order by listorder desc");
		    	$cret = $query->result_array();
		    	$data['input'] = $cret;
		    }
		    else {
		    	die();
		    }
		}
		
		$template = isset($data['data']['template'])?$data['data']['template']:'';
		
		if ($submit && $id) {
			foreach ($_POST as $k=>$p){
				if (strpos($k,'id')===0) {
					$datav[substr($k, 2)] = $p;
				}
			}
			$inputstr = serialize($datav);
			if ($inputstr) {
				$datainsert['subinfo'] = $inputstr;
				$datainsert['addtime'] = time();
				$datainsert['adddate'] = date("Ymd");
				
				$datainsert['openid'] = $this->openid;
				$datainsert['username'] = isset($this->fans['nickname'])?$this->fans['nickname']:'';
				$datainsert['inter_id'] = $this->inter_id;
				
				$coupon = $this->input->post('coupon');
				if ($coupon) {
					$datainsert['coupon'] = $coupon;
				}
				$datainsert['cid'] = $id;
				
				if ($data['data']['ischeck'] == 1) {
					$datainsert['status'] = 2;
				}
				if ($template == 'signuppay') {
					$datainsert['status'] = 0;
				}
				if ($template == 'signuppaycard') {
					$datainsert['status'] = 0;
				}
				if ($template == 'signuptopay') {
					$datainsert['status'] = 0;
				}
				
		
				if ($datainsert) {
					$r = $this->db->insert('custom_info',$datainsert);
					$infoid = $this->db->insert_id();
					
					if ($template == 'signuppay') {
						$tourl = '/index.php/chat/fapi/topay?iad='.$infoid;
					}
					else {
						$tourl = '/index.php/chat/fapi/addresult?iad='.$infoid;
					}					
				}
			}
				
			$query = $this->db->query("SELECT count(*) as count FROM ".$this->db->dbprefix."custom_info where cid=".$id);
			$ret = $query->result_array();
			$count = 0;
			if ($ret) {
				$count = $ret['0']['count'];
			}
				
			$datacount['addnum'] = $count;
			$this->db->update('custom',$datacount,array('id'=>$id));
			
			
			
			///////////////////////////////////////////
			if ($infoid) {
				$ret = qfselect("select * from ".$this->db->dbprefix."custom_info,".$this->db->dbprefix."custom where ".$this->db->dbprefix."custom_info.id='".$infoid."' and ".$this->db->dbprefix."custom_info.cid=".$this->db->dbprefix."custom.id limit 1",$this->db);
				if (!$ret) {echo '';die();	}
				$price = floatval($ret[0]['price']);
				///////////////////这里嵌入邀请码
				$coupon = $ret[0]['coupon'];
				if ($coupon) {
					$datacoupon = qfselect("select * from ".$this->db->dbprefix."coupon where sid='".$id."' and coupon='".$coupon."' and status='0' limit 1",$this->db);
					if ($datacoupon) {
						$couponpricetype = $datacoupon[0]['pricetype'];
						$couponpricerole = $datacoupon[0]['pricerole'];
						if ($couponpricetype==2) {
							$price = intval($price*100-floatval($couponpricerole)*100)/100;
						}
						if ($couponpricetype==1) {
							$price = floatval($couponpricerole);
						}
						if ($couponpricetype==3) {
							$price = $price * $couponpricerole;
						}
					}
					
				}
				///////////////////
				
				$datadopay = array('infoid'=>$infoid,'cacid'=>$id,'openid'=>$this->openid,'body1'=>$ret[0]['title'],'price'=>$price);
				echo json_encode($datadopay);
			}
			die();
		}
		
		$data['retfail'] = $retfail;
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());		
		$this->display('chat/form.template.'.$template,$data);
	}
	
	public function aaa() {
		echo 'aaa';
	}

	/**
	 * @author 清风
	 * 提交表单后展示状态信息
	 */
	public function addresult() {
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$data['ntime'] = time();
		$data['openid'] = $this->openid;
		
		$id = intval($this->input->get('iad',true));
		
		$data['addinfo'] = array();
		$ret = qfselect("select * from ".$this->db->dbprefix."custom_info,".$this->db->dbprefix."custom where ".$this->db->dbprefix."custom_info.id='".$id."' and ".$this->db->dbprefix."custom_info.openid='".$this->openid."' and ".$this->db->dbprefix."custom_info.status='3' and ".$this->db->dbprefix."custom_info.cid=".$this->db->dbprefix."custom.id limit 1",$this->db);
		if ($ret) {
			$addinfo = $ret[0];
			$addinfo['subinfo'] = unserialize($addinfo['subinfo']);
				
			$data['addinfo'] = $addinfo;
			
			$query = $this->db->query("SELECT * FROM `".$this->db->dbprefix."custom_input` where cid=".$addinfo['cid']);
			$forminput = $query->result_array();
			$data['forminput'] = $forminput;
		}
		else {
			echo 'noinfo';
			die();
		}
		
		
		$this->load->model ( 'wx/Access_token_model' );
		$SignPackage = $this->Access_token_model->getSignPackage ( $this->inter_id );
		$accesstoken = $this->Access_token_model->get_access_token ( $this->inter_id );
		
		$data['signpackage'] = $SignPackage;
		$data['accesstoken'] = $accesstoken;
		
		$cardid = $addinfo['$addinfo'];
		
		/*
		$carddata = qfselect("select * from ".$this->db->dbprefix."custom_card where infoid='".$id."' and openid='".$this->openid."'",$this->db);
		if (!$carddata) {
			if ($cardid) {
				$cardin['infoid'] = $id;
				$cardin['cardid'] = $cardid;
				$cardin['inter_id'] = $this->inter_id;
				$cardin['openid'] = $this->openid;
				$cardin['addtime'] = $data['ntime'];	
				$cardin['status'] = 0;		
				$this->db->insert('custom_card',$cardin);		
				$insertcardid = $this->db->insert_id();		
				$cardin['id'] = $insertcardid;
			}
			
			$carddata[0] = $cardin;
			$carddata[0]['cardid'] = $cardid;
			$carddata[0]['ucode'] = '';
			
		}
		$data['card'] = $carddata[0];
		if (!$data['card']['ucode']) {
			$ucode = substr(time(), 3).rand(10000, 99999);
			$dataqr = array('action_name'=>'QR_CARD','expire_seconds'=>1800,
					'action_info'=>array('card'=>array('card_id'=>$data['card']['cardid'],'code'=>$ucode,'openid'=>'','is_unique_code'=>false,'outer_id'=>1)));
			$postdataqr = json_encode($dataqr);
			
			$createqrcode = qfpost('https://api.weixin.qq.com/card/qrcode/create?access_token='.$accesstoken,$postdataqr);
			$qrcode = json_decode($createqrcode,true);
			
			if ($qrcode['errcode']==0) {
				$this->db->update('custom_card',array('ucode'=>$ucode),array('infoid'=>$id));
				$data['card']['ucode'] = $ucode;
			}
			
		}
		*/
		
		/*
		$ticketret = file_get_contents('http://iwidecn.iwide.cn/index.php/wxdata_trans/api_ticket_auth?appid='.$data['hoteldata']['appid']);
		$ticketobj = json_decode($ticketret,true);
		$ticket = $ticketobj['api_ticket'];
		$data['ticket'] = $ticket;
		
		$cardticketrettimedata = qfselect("select * from ".$this->db->dbprefix."option where name='cardticketrettime".$this->inter_id."' limit 1",$this->db);
		$cardticketretdata = qfselect("select * from ".$this->db->dbprefix."option where name='cardticketret".$this->inter_id."' limit 1",$this->db);
		if (!$cardticketretdata) {
			$cardticketret = qfpost('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$accesstoken.'&type=wx_card','');
			$inoption1['name'] = 'cardticketret'.$this->inter_id;
			$inoption1['value'] = $cardticketret;
			$this->db->insert('option',$inoption1);
			unset($inoption1);
		}
		else {
			$cardticketret = $cardticketretdata[0]['value'];
		}
		
		if (!$cardticketrettimedata) {
			$inoption4['name'] = 'cardticketrettime'.$this->inter_id;
			$inoption4['value'] = $data['ntime'];
			$this->db->insert('option',$inoption4);
			unset($inoption4);
		}
		else {
			$cardticketrettime = intval($cardticketrettimedata[0]['value']);
			if ($data['ntime'] - $cardticketrettime>3600) {
				$cardticketret = qfpost('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$accesstoken.'&type=wx_card','');
				$this->db->update('option',array('value'=>$cardticketret),array('name'=>'cardticketret'.$this->inter_id));
				$this->db->update('option',array('value'=>$data['ntime']),array('name'=>'cardticketrettime'.$this->inter_id));
			}
		}
		$cardticketobj = json_decode($cardticketret,true);
		$data['cardticket'] = $cardticketobj;
		
		$signaturecard[]=$data['ntime'];
		$signaturecard[]=$data['cardticket']['ticket'];
		$signaturecard[]=$data['card']['cardid'];
		$signaturecard[]=$data['card']['ucode'];
		
		natcasesort($signaturecard);
		$data['signaturecard'] = sha1(implode('', $signaturecard));
		
		if ($data['card']['status']!=0) {
			$data['signaturecard'] = '';
		}
		
		$data['paying'] = '';
		$paying = $this->input->get('ret');
		if ($paying) {
			$data['paying'] = '1';
		}
		*/			
		$this->display('chat/form.template.'.$addinfo['template'].'_result',$data);
	}
	
	public function topay() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$id = intval($this->input->get('iad',true));
		
		$ret = qfselect("select * from ".$this->db->dbprefix."custom_info,".$this->db->dbprefix."custom where ".$this->db->dbprefix."custom_info.id='".$id."' and ".$this->db->dbprefix."custom_info.cid=".$this->db->dbprefix."custom.id limit 1",$this->db);
		
		if (!$ret) {
			echo 'nodata';
			die();
		}
		
		$data = array('id'=>$id,'openid'=>$this->openid,'tradeno'=>$id,'data'=>$ret[0]);
		
		$this->display('chat/form.show_topay',$data);
	}
	
	public function repay() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$id = intval($this->input->get('iad',true));
		
		$act = $this->input->get("act",true);
	
		$ret = qfselect("select * from ".$this->db->dbprefix."custom_info,".$this->db->dbprefix."custom where ".$this->db->dbprefix."custom_info.id='".$id."' and ".$this->db->dbprefix."custom_info.cid=".$this->db->dbprefix."custom.id limit 1",$this->db);
	
		if (!$ret) {
			echo 'nodata';
			die();
		}
	
		$data = array('id'=>$id,'openid'=>$this->openid,'tradeno'=>$id,'data'=>$ret[0]);
		
		if ($act == 'ispay') {
			
			echo $ret[0]['status'];
			die();
			
		}
	
		$this->display('chat/form.show_repay',$data);
	}
	
	public function signuptopay_order() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$data['ntime'] = time();
		
		$iad = intval($this->input->get('iad',true));
		
		$custom = $this->db->query("select * from ".$this->db->dbprefix."custom where id='".$iad."' and inter_id='".$this->inter_id."' limit 1")->result_array();
		if (!$custom) {
			echo '';die();
		}
		$data['custom'] = $custom[0];
		$custominfo = $this->db->query("select * from ".$this->db->dbprefix."custom_info where openid='".$this->openid."' and cid='".$iad."' and inter_id='".$this->inter_id."' and status='3' limit 1000")->result_array();
		$data['custominfo'] = $custominfo;
		
		$this->display('chat/form.template.'.$custom[0]['template'].'_order',$data);
	}
	
	public function order() {
	
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$data['ntime'] = time();
		
		$id = intval($this->input->get('iad',true));
		
		if (!$id) {
			echo 'noid';
			die();
		}
		
		$sql = "select ".$this->db->dbprefix."custom_card.id as ciid,".$this->db->dbprefix."custom_card.status as cardstatus,".$this->db->dbprefix."custom_info.id as iid,".$this->db->dbprefix."custom.starttime as starttime,".$this->db->dbprefix."custom.keyword as keyword,".$this->db->dbprefix."custom_info.*,".$this->db->dbprefix."custom_card.* from";
		$sql = $sql." ".$this->db->dbprefix."custom_info,".$this->db->dbprefix."custom_card,".$this->db->dbprefix."custom where ".$this->db->dbprefix."custom_card.openid='".$this->openid."' and ".$this->db->dbprefix."custom_card.inter_id='".$this->inter_id."'";
		$sql = $sql." and ".$this->db->dbprefix."custom_info.cid = '".$id."' and ".$this->db->dbprefix."custom_info.id=".$this->db->dbprefix."custom_card.infoid and ".$this->db->dbprefix."custom_info.cid=".$this->db->dbprefix."custom.id order by ".$this->db->dbprefix."custom_card.status asc limit 1000";
		
		$ret = qfselect($sql,$this->db);
		if (!$ret) {
			$ret = array();
		}
		
		$this->display('chat/form.template.signuppay_order',$data);
	}
	
	public function ordercard() {
	
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$id = intval($this->input->get('iad',true));
		$iframeweixin = intval($this->input->get("iframeweixin",true));
	
		if (!$id) {
			echo 'noid';
			die();
		}
	
		$sql = "select ".$this->db->dbprefix."custom_card.id as ciid,".$this->db->dbprefix."custom_card.status as cardstatus,".$this->db->dbprefix."custom_info.id as iid,".$this->db->dbprefix."custom.starttime as starttime,".$this->db->dbprefix."custom.keyword as keyword,".$this->db->dbprefix."custom_info.*,".$this->db->dbprefix."custom_card.* from";
		$sql = $sql." ".$this->db->dbprefix."custom_info,".$this->db->dbprefix."custom_card,".$this->db->dbprefix."custom where ".$this->db->dbprefix."custom_card.openid='".$this->openid."' and ".$this->db->dbprefix."custom_card.inter_id='".$this->inter_id."'";
		$sql = $sql." and ".$this->db->dbprefix."custom_info.cid = '".$id."' and ".$this->db->dbprefix."custom_info.id=".$this->db->dbprefix."custom_card.infoid and ".$this->db->dbprefix."custom_info.cid=".$this->db->dbprefix."custom.id order by ".$this->db->dbprefix."custom_card.status asc limit 1000";
	
		$ret = qfselect($sql,$this->db);
		if (!$ret) {
			$ret = array();
		}
	
		////////////////////
		$data['ntime'] = time();
		$hoteldata = qfselect("select * from iwide_chathotel where id='".$this->inter_id."'",$this->db);
		if (!$hoteldata) {
			die();
		}
		$data['hoteldata'] = $hoteldata[0];/****/
	
		$ticketret = file_get_contents('http://iwidecn.iwide.cn/index.php/wxdata_trans/api_ticket_auth?appid='.$data['hoteldata']['appid']);
		$ticketobj = json_decode($ticketret,true);
		$ticket = $ticketobj['api_ticket'];
		$data['ticket'] = $ticket;/****/
	
	
	
		$accesstokenret = file_get_contents('http://iwidecn.iwide.cn/index.php/wxdata_trans/access_token_auth?appid='.$data['hoteldata']['appid']);
		$accesstokenobj = json_decode($accesstokenret,true);
		$accesstoken = $accesstokenobj['access_token'];
		$cardticketrettimedata = qfselect("select * from ".$this->db->dbprefix."option where name='cardticketrettime".$this->inter_id."' limit 1",$this->db);
		$cardticketretdata = qfselect("select * from ".$this->db->dbprefix."option where name='cardticketret".$this->inter_id."' limit 1",$this->db);
		if (!$cardticketretdata) {
			$cardticketret = qfpost('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$accesstoken.'&type=wx_card','');
			$inoption1['name'] = 'cardticketret'.$this->inter_id;
			$inoption1['value'] = $cardticketret;
			$this->db->insert('option',$inoption1);
			unset($inoption1);
		}
		else {
			$cardticketret = $cardticketretdata[0]['value'];
		}
	
		if (!$cardticketrettimedata) {
			$inoption4['name'] = 'cardticketrettime'.$this->inter_id;
			$inoption4['value'] = $data['ntime'];
			$this->db->insert('option',$inoption4);
			unset($inoption4);
		}
		else {
			$cardticketrettime = intval($cardticketrettimedata[0]['value']);
			if ($data['ntime'] - $cardticketrettime>3600) {
				$cardticketret = qfpost('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$accesstoken.'&type=wx_card','');
				if (isset($cardticketret['ticket'])) {
					$this->db->update('option',array('value'=>$cardticketret),array('name'=>'cardticketret'.$this->inter_id));
					$this->db->update('option',array('value'=>$data['ntime']),array('name'=>'cardticketrettime'.$this->inter_id));
				}
			}
		}
		$cardticketobj = json_decode($cardticketret,true);
		$data['cardticket'] = $cardticketobj;/****/
	
		if (!isset($data['cardticket']['ticket'])) {
			echo 'accesstokentimeout';
			die();
		}
	
		$newret = array();
		foreach ($ret as $v) {
			$signaturecard[]=$data['ntime'];
			$signaturecard[]=$data['cardticket']['ticket'];
			$signaturecard[]=$v['cardid'];
				
			$signaturecard[]=$v['ucode'];
				
			natcasesort($signaturecard);
				
			//$data['signaturecard'] = sha1(implode('', $signaturecard));/****/
			$v['signaturecard'] = sha1(implode('', $signaturecard));/****/
			unset($signaturecard);
			$newret[] = $v;
				
		}
	
		$data['tiket'] = $newret;
	
		if ($iframeweixin) {
			$this->display('chat/form.template.signuppay_orderweixincard',$data);
		}
		else {
			$this->display('chat/form.template.signuppay_ordercard',$data);
		}
	
	}
	
	public function getcard() {
		$cardid = intval($this->input->post('cid'));
		
		if ($cardid) {
			$this->db->update('custom_card',array('status'=>3),array('id'=>$cardid,'openid'=>$this->openid));
		}
	    echo '1';
	}
	
	public function syncard() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$id = intval($this->input->get('iad'));
		$sql = "SELECT A.* FROM ".$this->db->dbprefix."custom_info A LEFT JOIN ".$this->db->dbprefix."custom_card B ON A.id=B.infoid WHERE A.openid='".$this->openid."' AND A.cid='".$id."' AND A.inter_id='".$this->inter_id."' AND A.`status`='3' AND B.id IS NULL;";
		$ret = qfselect($sql,$this->db);
		if ($ret) {
			$infoid = $ret['0']['id'];
			$payed = intval($ret['0']['payed']);
			
			$sql = "SELECT price from ".$this->db->dbprefix."custom where id='".$id."';";
			$retcustom = qfselect($sql,$this->db);
			
			$price = intval($retcustom['0']['price']);
			
			//if ($payed == $price) {
			header("location:/index.php/chat/fapi/addresult?iad=".$infoid);die();;
			//}
			
		}
		
		
		$sql = "select ".$this->db->dbprefix."custom_card.* from";
		$sql = $sql." ".$this->db->dbprefix."custom_info,".$this->db->dbprefix."custom_card where ".$this->db->dbprefix."custom_card.openid='".$this->openid."' and ".$this->db->dbprefix."custom_card.inter_id='".$this->inter_id."'";
		$sql = $sql." and ".$this->db->dbprefix."custom_info.cid = '".$id."' and ".$this->db->dbprefix."custom_card.status='0' and ".$this->db->dbprefix."custom_info.id=".$this->db->dbprefix."custom_card.infoid limit 20";
		
		$ret = qfselect($sql,$this->db);
		/*
		if ($ret) {
			$hoteldata = qfselect("select * from iwide_chathotel where id='".$this->inter_id."'",$this->db);
			if (!$hoteldata) {
				die();
			}
			$data['hoteldata'] = $hoteldata[0];
			
			
			$accesstokenret = file_get_contents('http://iwidecn.iwide.cn/index.php/wxdata_trans/access_token_auth?appid='.$data['hoteldata']['appid']);
			$accesstokenobj = json_decode($accesstokenret,true);
			$accesstoken = $accesstokenobj['access_token'];
			
			$ccardid = $ret['0']['cardid'];
			$ccode = $ret['0']['ucode'];
			
			$dataqr = array('action_name'=>'QR_CARD','expire_seconds'=>1800,'action_info'=>array('card'=>array('card_id'=>$ccardid,'code'=>$ccode,'openid'=>'','is_unique_code'=>false,'outer_id'=>1)));
			$postdataqr = json_encode($dataqr);
			$createqrcode = qfpost('https://api.weixin.qq.com/card/qrcode/create?access_token='.$accesstoken,$postdataqr);
			$qrcode = json_decode($createqrcode,true);
			
			if ($qrcode['errcode']==0) {
				$this->db->update('custom_card',array('status'=>'2'),array('id'=>$ret['0']['id']));
			}
			
		}
		*/
	}
	
	
}
