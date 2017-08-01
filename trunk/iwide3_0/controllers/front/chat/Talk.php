<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Talk extends MY_Front {

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
	var $nickname;
	
	var $userinfo;
	var $hotelid;
	
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
		
		
		$getapp = getapp($this->inter_id,$this->openid, $this->db);
		
		if ($getapp['id']==0) {
			echo 'nohotel';
			die();
		}
		
		
		$this->appid = $getapp['appid'];
		
		$this->authurl = $getapp['authurl'];
		
		$this->hotelid = $getapp['id'];
		
		$this->nickname = $getapp['user']['nickname'];
		
		$this->getapp = $getapp;
		
		
		$sql = "SELECT * FROM ".$this->db->dbprefix."chat_member where hotelid='".$this->hotelid."' and openid='".$this->openid."';";
		
		$getuserinfo = qfselect($sql,$this->db);
		
		if (!$getuserinfo) {
			
			echo '<script type="text/javascript">top.location.href = "/index.php/chat/member/nologin?to=/index.php/chat/talk/";</script>';
				
			die();
			
		}
		
		
		
		$this->userinfo =$getuserinfo['0'];
		
		/*权限接口文件*/
	}
	
	public function index()
	{
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$submit = $this->input->post('submit');
		
		$getsubmit = $this->input->get('submit');
		
		if (empty($submit)) {
			$submit = $getsubmit;
		}
	
		if ($submit == 1) {
			
			$data['member'] = array();
		
			$sql = "SELECT t1.id,t1.logo,t1.nickname,t1.province,t1.area,t1.sex FROM ".$this->db->dbprefix."chat_member AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM ".$this->db->dbprefix."chat_member)-(SELECT MIN(id) FROM ".$this->db->dbprefix."chat_member))+(SELECT MIN(id) FROM ".$this->db->dbprefix."chat_member)) AS id) AS t2 WHERE hotelid='".($this->hotelid)."' and t1.online=1 and t1.id >= t2.id LIMIT 5;";
			
			//echo $sql;
			$getmember = qfselect($sql,$this->db);
			
			$data['member'] = shuffle($getmember);
			
			echo json_encode($getmember);
			
			die();
			
		}
		
		$dosubmit = $this->input->post('dosubmit');
		
		if ($dosubmit) {
			
			$num = intval($this->input->post('num'));
			
			$money = intval($this->input->post('money'));
			
			$desri = trim($this->input->post('desri'));
			
			if (!$desri) {
				$desri = '恭喜发财，大吉大利';
			}
			
			if (!$num) {
				echo '<script type="text/javascript">alert("红包数量不正确！");history.go(-1);</script>';
				die();
			}
			
			if (!$money) {
				echo '<script type="text/javascript">alert("红包金额不正确！");history.go(-1);</script>';
				die();
			}
				
			$datain['money'] = $money;
				
			$datain['num'] = 0;
			
			$datain['total'] = $num;
			
			$datain['desri'] = $desri;
			
			$datain['addtime'] = time();
			
			$datain['status'] = 1;////////////////后来加上当付款成功时才更新成1
				
			$datain['openid'] = $this->openid;
			
			$datain['hotelid'] = $this->hotelid;
				
			$ret = $this->db->insert('bonus',$datain);

			if ($ret) {
				
				$hbpreorder = '<xml>
				<sign><![CDATA[E1EE61A91C8E90F299DE6AE075D60A2D]]></sign>
				<mch_billno><![CDATA[0010010404201411170000046545]]></mch_billno>
				<mch_id><![CDATA[10000097]]></mch_id>
				<wxappid><![CDATA[wxcbda96de0b165486]]></wxappid>
				<send_name><![CDATA[send_name]]></send_name>
				<hb_type><![CDATA[NORMAL]]></hb_type>
				<auth_mchid><![CDATA[10000098]]></auth_mchid>
				<auth_appid><![CDATA[wx7777777]]></auth_appid>
				<total_amount><![CDATA[200]]></total_amount>
				<amt_type><![CDATA[ALL_RAND]]></amt_type>
				<total_num><![CDATA[3]]></total_num>
				<wishing><![CDATA[恭喜发财 ]]></wishing>
				<act_name><![CDATA[ 新年红包 ]]></act_name>
				<remark><![CDATA[新年红包 ]]></remark>
				<risk_cntl><![CDATA[NORMAL]]></risk_cntl>
				<nonce_str><![CDATA[50780e0cca98c8c8e814883e5caa672e]]></nonce_str>
				</xml>';
				
				//echo qfpost('https://api.mch.weixin.qq.com/mmpaymkttransfers/hbpreorder',$hbpreorder);
				
				//die();
				
				echo '<script type="text/javascript">alert("您的红包已经发出去了，等待朋友给您回复吧！");</script>';
				
			}
			
			
		}
		
		//print_r($this->userinfo);
		
		$data['active'] = array();
		
		$sql = "SELECT * FROM ".$this->db->dbprefix."active where hotelid='".$this->hotelid."' order by id desc LIMIT 1";
		
		$getactive = qfselect($sql,$this->db);
		
		if (!$getactive) {
			$getactive['0'] = array();
		}
		
		$data['active'] = $getactive['0'];
		
		$data['userinfo'] = $this->userinfo;
		
		$this->display('chat/show_square',$data);
		
	}
	
	public function myactive() {
	
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$data['active'] = array();
		
		$sql = "SELECT * FROM ".$this->db->dbprefix."active where hotelid='".$this->hotelid."' order by id desc LIMIT 30";
		
		$active = qfselect($sql,$this->db);
		
		$data['active'] = $active;
		
		$this->display('chat/show_myactive',$data);
	
	}
	
	public function active() {
	
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$id = intval($this->input->get('iad'));
		
		if (empty($id)) {
			
			header("location:/index.php/chat/talk");
			
			die();
			
		}
	
		$active = array();
	
		$signup = array();
	
		$sql = "SELECT * FROM ".$this->db->dbprefix."active where id='".$id."' limit 30";
	
		$active = qfselect($sql,$this->db);
		
	   if (empty($active)) {
			
			header("location:/index.php/chat/talk");
			
			die();
			
		}
	
		$data['active'] = $active[0];
	
		if ($data['active']['id']) {
	
			$sql = "SELECT B.*,A.nickname as anickname,A.uname as uname,A.addtime as signuptime FROM ".$this->db->dbprefix."signup A left join ".$this->db->dbprefix."chat_member B on A.openid=B.openid where A.aid='".$id."' limit 30";
	
			$signup = qfselect($sql,$this->db);
	
		}
	
		$data['signup'] = $signup;
		
		$this->display('chat/show_active',$data);
	}
	
	
	public function addactive() {
	
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$id = $this->input->get('iad');
	
		$openid = $this->openid;
	
		//$nickname = $this->nickname;
	
		$this->nickname;
	
		$data = array();
	
		$this->display('chat/show_data',$data);
	
	}
	
	
	public function bonus() {
	
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$submit = $this->input->post('submit');
		
		$getsubmit = $this->input->get('submit');
		
		if (empty($submit)) {
			$submit = $getsubmit;
		}
	
		if ($submit) {
			
			$sql = "select A.* from ".$this->db->dbprefix."bonus A where A.hotelid='".$this->hotelid."' and A.openid<>'".$this->openid."' and A.status='1' and (select count(0) as bnum from ".$this->db->dbprefix."mybonus B where B.bid = A.id and B.openid='".$this->openid."') = 0 order by A.addtime asc limit 1";
			
			$ret = qfselect($sql,$this->db);
			
			if (!$ret) {
				echo 'err:no';die();
			}
			
			if ($ret['0']['total']==$ret['0']['num']) {
				
				$this->db->update('bonus',array('status'=>3),array('id'=>$ret['0']['id']));
				
				echo 'err:over';die();
				
			}			
			
			if ($ret) {
			
				$datain['bid'] = $ret[0]['id'];
			
				$datain['openid'] = $this->openid;
				
				$datain['hotelid'] = $this->hotelid;
			
				$datain['fromu'] = $ret[0]['openid'];
			
				$datain['msg'] = $ret[0]['desri'];
			
				$datain['editetime'] = time();
			
				$datain['nickname'] = $ret[0]['nickname'];
			
				$datain['status'] = 0;
			
				$this->db->insert('mybonus',$datain);
				$mbid = $this->db->insert_id();
				
				$countnumnew  = intval($ret[0]['num'])+1;
			
				$this->db->update('bonus',array('num'=>$countnumnew),array('id'=>$ret[0]['id']));
				
				
				$sql = "SELECT * FROM ".$this->db->dbprefix."friend where hotelid='".$this->hotelid."' and fromu='".$this->openid."' and openid='".$ret[0]['openid']."' limit 1";
				
				$isfriend = qfselect($sql,$this->db);

				if (empty($isfriend)) {
					
					$data1['openid'] = $this->openid;
					$data1['hotelid'] = $this->hotelid;
					$data1['fromu'] = $ret[0]['openid'];
					$data1['editetime'] = time();
					$data1['nickname'] = $ret[0]['nickname'];
					$data1['status'] = 2;
					
					$this->db->insert('friend',$data1);
					$infoid1 = $this->db->insert_id();
					
					$this->db->update('friend',array('fid'=>$infoid1),array('id'=>$infoid1));
					
					
					$data2['openid'] = $ret[0]['openid'];
					$data2['hotelid'] = $this->hotelid;
					$data2['fromu'] = $this->openid;
					$data2['editetime'] = time();
					$data2['nickname'] = $this->nickname;
					$data2['status'] = 2;
					
					$this->db->insert('friend',$data2);
					$infoid2 = $this->db->insert_id();
					$this->db->update('friend',array('fid'=>$infoid1),array('id'=>$infoid2));
					
					$fid = $infoid1;
					
				}
				else {
					$fid = $isfriend['0']['fid'];
				}
				
				
				
				$datamsg['fid'] = $fid;
			
				$datamsg['msg'] = '<div class="red_packget"><div><div class="txtclip">'.$ret[0]['desri'].'</div><div>查看红包</div></div><div class="red_foot"><div>微信红包</div></div></div>';	
				
				$datamsg['addtime'] = time();
			
				$datamsg['status'] = 1;
			
				$datamsg['openid'] = $ret[0]['openid'];
			
				$this->db->insert('msg',$datamsg);
			
			}
			
			echo $fid;
			
		}
				
	}
	
	public function mymsg() {
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$data['myfriend'] = array();
		
		$sql = "SELECT ".$this->db->dbprefix."chat_member.*,".$this->db->dbprefix."friend.msg as msg,".$this->db->dbprefix."friend.fid as fid FROM ".$this->db->dbprefix."friend left join ".$this->db->dbprefix."chat_member on ".$this->db->dbprefix."friend.fromu=".$this->db->dbprefix."chat_member.openid where ".$this->db->dbprefix."friend.openid='".$this->openid."' and ".$this->db->dbprefix."friend.hotelid='".$this->hotelid."' and ".$this->db->dbprefix."friend.status>0 order by ".$this->db->dbprefix."friend.editetime desc limit 30";
		
		$myfriend = qfselect($sql,$this->db);
		
		$data['myfriend'] = $myfriend;
		
		$this->db->update('chat_member',array('newmsg'=>'0'),array('openid'=>$this->openid,'hotelid'=>$this->hotelid));
		
		$this->display('chat/show_mymsg',$data);
		
	}
	
	public function msg() {
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$id = intval($this->input->get('iad'));
		
		$submit = $this->input->get('submit');
		
		if ($submit) {
		
			$msg = $this->input->get('msg');
			
			$fid = intval($this->input->get('fid'));
			
			$msg = trim($msg);
				
			if (!$msg) {
				
				die();
				
			}
				
			$sql = "SELECT * FROM ".$this->db->dbprefix."friend where id='".$fid."' limit 1";
			
			$myfriend = qfselect($sql,$this->db);
				
			if ($myfriend) {
				
				if ($myfriend['0']['openid'] == $this->openid || $myfriend['0']['fromu'] == $this->openid) {
					
					$datain['fid'] = $fid;
					
					$datain['msg'] = $msg;;
					
					$datain['addtime'] = time();
					
					$datain['status'] = 1;
					
					$datain['openid'] = $this->openid;
					
					if($myfriend['0']['openid'] == $this->openid){
						$fromu = $myfriend['0']['fromu'];
					}
					else {
						$fromu = $myfriend['0']['openid'];
					}
					$this->db->update('chat_member',array('newmsg'=>'1'),array('openid'=>$fromu,'hotelid'=>$this->hotelid));
										
				}
		
				echo $this->db->insert('msg',$datain);
		
				if( strpos($msg, '<img src="')=== false ){
						
				}
				else {
					
					$msg = '[图片]';
					
				}
		
				$this->db->update('friend',array('editetime'=>time(),'status'=>3,'msg'=>$msg),array('id'=>$fid));
		
			}
				
			die();
				
		}
		
		if (!$id) {
			
			header("location:/index.php/chat/talk/mymsg");
			
			die();
			
		}
		
		
        $sql = "SELECT ".$this->db->dbprefix."chat_member.*,".$this->db->dbprefix."friend.msg as msg,".$this->db->dbprefix."friend.fid as fid FROM ".$this->db->dbprefix."friend left join ".$this->db->dbprefix."chat_member on ".$this->db->dbprefix."friend.fromu=".$this->db->dbprefix."chat_member.openid where ".$this->db->dbprefix."friend.fid='".$id."' and ".$this->db->dbprefix."friend.openid='".$this->openid."' and ".$this->db->dbprefix."friend.status>0 limit 1";
		
		$myfriend = qfselect($sql,$this->db);
		
		if (!$myfriend) {
				
			header("location:/index.php/chat/talk/mymsg");
			
			die();
				
		}
		
		$data['finfo'] = $myfriend['0'];
		
		$data['userinfo'] = $this->userinfo;

		$this->display('chat/show_msg',$data);
		
	}
	
	public function signup() {
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$submit = $this->input->post('dosubmit');
		
		if ($submit) {
			
			$id = intval($this->input->get('iad'));
			
			$uname = $this->input->post('uname');
		
			$idcard = $this->input->post('idcard');
			
			$telephone = $this->input->post('telephone');
			
			$roomid = $this->input->post('roomid');
			
			if (strlen($uname)<1 || strlen($uname)>20) {
				
				echo '<script type="text/javascript">alert("姓名不正确！");</script>';die();
				
			}
			
			if (!is_numeric($idcard) || strlen($idcard)<5) {
			
				echo '<script type="text/javascript">alert("证件输入不正确！请重新输入！");</script>';die();
			
			}
			
			if (!is_numeric($telephone) || strlen($telephone)<10 ) {
					
				echo '<script type="text/javascript">alert("手机号输入不正确！请重新输入！");</script>';die();
					
			}
			
			if ( strlen($roomid)<2 ) {
					
				echo '<script type="text/javascript">alert("房间号输入不正确！请重新输入！");</script>';die();
					
			}
			
			if (!$id) {
				
				echo 'err:id';
				
				die();
				
			}
			
			$retactive = qfselect("SELECT id FROM ".$this->db->dbprefix."active where id = '".$id."' LIMIT 1",$this->db);
			
			if (!$retactive) {
				
				echo 'err:no';
				
				die();
				
			}
			
			$ret = qfselect("SELECT id FROM ".$this->db->dbprefix."signup where openid = '".$this->openid."' and aid='".$id."' LIMIT 1",$this->db);
				
			if ($ret) {
			
				echo '<script type="text/javascript">alert("您已经报名过，请不要重复报名！");top.location.href="/index.php/chat/talk/active?iad='.$id.'";</script>';
			
				die();
			
			}
			
			$datain['aid'] = $id;
			
			$datain['openid'] = $this->openid;
			
			$datain['nickname'] = $this->nickname;
			
			$datain['uname'] = $uname;
			
			$datain['idcard'] = $idcard;
			
			$datain['telephone'] = $telephone;
			
			$datain['roomid'] = $roomid;
			
			$datain['addtime'] = time();
			
			$isin = $this->db->insert('signup',$datain);
				
			if ($isin) {
				
				$sex = isset($this->userinfo['sex'])?$this->userinfo['sex']:2;
				$upmale = '';
				if ($sex==1) {
					$upmale = ' ,male = male+1';
				}
				else {
					$upmale = ' ,female = female+1';
					
				}
				
				$this->db->query('UPDATE ".$this->db->dbprefix."active SET totalnum = totalnum+1'.$upmale.' WHERE id ='.$id);
				
				echo '<script type="text/javascript">top.location.href="/index.php/chat/talk/signupok?iad='.$id.'";</script>';die();
				
			}
			else {
				
				echo '<script type="text/javascript">alert("报名失败！");top.location.href="/index.php/chat/talk/active?iad='.$id.'";</script>';die();
				
			}
		}
		
		
		
		
		
		$this->display('chat/show_signup',$data);
		
	}
	
	public function signupok() {
	
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$id = intval($this->input->get('iad'));
		
		if (!$id) {
			
			echo "err:id";
			die();
			
		}
		
		$data['logo'] = array();
			
		$member = qfselect("SELECT logo FROM ".$this->db->dbprefix."chat_member where hotelid='".$this->hotelid."' and openid = '".$this->openid."' LIMIT 1",$this->db);
		
		if (!$member) {
				
			echo 'err:user';
				
			die();
				
		}
		
		$data['logo'] = $member['0']['logo'];
		
		$this->display('chat/show_signupok',$data);
		
	}
	
	public function signuplist() {
	
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$id = intval($this->input->get('iad'));
		
		if (!$id) {
				
			header("location:/index.php/chat/talk/myactive");
				
			die();
				
		}
		
		$signup = array();
	
		$sql = "SELECT B.*,A.nickname as anickname,A.addtime as signuptime FROM ".$this->db->dbprefix."signup A left join ".$this->db->dbprefix."chat_member B on A.openid=B.openid and b.hotelid='".$this->hotelid."' where A.aid='".$id."' limit 30";
	
		$signup = qfselect($sql,$this->db);
		
		$data['signup'] = $signup;		
		
		$this->display('chat/show_signuplist',$data);
	
	}
	
	public function makefri() {
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$touid = intval($this->input->get('uid'));
		
		if (!$touid) {
			
			echo 'err:uid';
			
			die();
			
		}
		
		$touser = qfselect("SELECT * FROM ".$this->db->dbprefix."chat_member where id = '".$touid."' LIMIT 1",$this->db);
		
		if (!$touser) {
			
			echo 'err:touser';
				
			die();
			
		}
		
		$fromu = isset($touser['0']['openid'])?$touser['0']['openid']:'';
		
		if ($fromu == $this->openid) {
			
			echo '<script type="text/javascript">alert("不能跟自己对话！");history.go(-1);</script>';die();
			
		}
		
		$fromnickname = isset($touser['0']['nickname'])?$touser['0']['nickname']:'';
		
		$friend = qfselect("SELECT * FROM ".$this->db->dbprefix."friend where hotelid='".$this->hotelid."' and openid = '".$this->openid."' and fromu='".$fromu."' LIMIT 1",$this->db);
		
		if (empty($friend)) {
			
			$data1['openid'] = $this->openid;
			$data1['hotelid'] = $this->hotelid;
			$data1['fromu'] = $fromu;
			$data1['editetime'] = time();
			$data1['nickname'] = $fromnickname;
			$data1['status'] = 2;
			
			$this->db->insert('friend',$data1);
			$infoid1 = $this->db->insert_id();
			
			$this->db->update('friend',array('fid'=>$infoid1),array('id'=>$infoid1));
			
			
			$data2['openid'] = $fromu;
			$data2['hotelid'] = $this->hotelid;
			$data2['fromu'] = $this->openid;
			$data2['editetime'] = time();
			$data2['nickname'] = $this->nickname;
			$data2['status'] = 2;
			
			$this->db->insert('friend',$data2);
			$infoid2 = $this->db->insert_id();
			$this->db->update('friend',array('fid'=>$infoid1),array('id'=>$infoid2));
			
			header("location:/index.php/chat/talk/msg?iad=".$infoid1);die();
			
		}
		
		header("location:/index.php/chat/talk/msg?iad=".$friend['0']['fid']);die();
		
	}
}
