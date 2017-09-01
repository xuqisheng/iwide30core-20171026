<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bindcard extends MY_Front 
{
	protected $member_types = array('P','Q','R','S');
	protected $value_types = array('U','V','X');
	protected function getOpenId()
	{
		return $this->openid;
	}
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->helper('url');
	}
	
	public function index()
	{
		$openid = $this->getOpenId();

		$this->load->model('pms_cshis/ivcardadd');
		$data['cards'] = $this->ivcardadd->getCardDetaiInfoList($openid);
		$this->load->model('pms_cshis/userinfo');
		$this->load->model('member/igetcard');
		$this->load->model('member/getcard');
		$this->load->model('member/imember');
		$this->load->model('pms_cshis/ivcardadd');
		$this->load->model('member/member');

		$balance = 0;
		foreach($data['cards'] as $card) {
			$user = new UserInfo();
			$user->Ic_num = $card->code;
			$user->mobile = $card->telephone;
			$getresult = $this->imember->getUserinfo($user);
			
			if($getresult) {
				$bal = abs($getresult->ic_bal);
				if(($card->status==0) && ($bal>0)) {
			        $this->igetcard->updateGcardStatus($openid, $card->code, Getcard::STATUS_HAVE_RECEIVE);
			        $this->ivcardadd->updateInfoById($card->gc_id, array('balance'=>$bal), $field='gc_id');
				}
				if($card->status) {
					$this->ivcardadd->updateInfoById($card->gc_id, array('balance'=>$bal), $field='gc_id');
				}
				$balance += $bal;
			}
		}
		
		$this->member->updateBalance(array('openid'=>$openid,'balance'=>$balance), $balance);

		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);

		if(count($data['cards'])==0) {
			redirect('member/bindcard/bind');
		}
		
		$this->load->library('session');
		if($this->session->has_userdata('message')) {
			$data['message'] = $this->session->message;
			$this->session->unset_userdata('message');
		}

		$this->display('member/vcardlist', $data);
	}
	
	public function resetpwd()
	{
		$gc_id = $this->input->get('id');
		
        $this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		$data['id'] = intval($gc_id);
		
		$this->load->view('member/resetpwd', $data);
	}
	
	//储值卡解除绑定
	public function unbind()
	{	
		$gc_id = $this->input->get('id');
		$data['id'] = intval($gc_id);
		
		$this->load->model('member/igetcard');
		$card = $this->igetcard->getCardById($gc_id);
		
        $this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		
		$data['code'] = $card->code;
		$this->display('member/unbind', $data);
	}
	
	//储值卡解除绑定保存
	public function dounbind()
	{
		$openid = $this->getOpenId();
		
		$gc_id = $this->input->post('id');
		$password = $this->input->post('password');
		
		$this->load->model('member/igetcard');
		$card = $this->igetcard->getCardById($gc_id);
		
		$this->load->model('member/imember');
		$member = $this->imember->getUserModel();
		$member->Ic_num = $card->code;
		$member->Ic_pwd = $password;
		$memberinfo = $this->imember->getUserinfo($member);
		if($memberinfo) {
		    $result = $this->igetcard->consumeCard($openid,$card->code,'用户取消绑定');
		    if($result && !isset($result->error)) {
		    	echo "解除绑定成功!";
		    	exit;
		    }
		}
		
		echo "解除绑定失败!";
		exit;
	}
	
	public function doresetpwd()
	{
		$telephone = $this->input->post('telephone');
		$password = $this->input->post('password');
		$oldpassword = $this->input->post('oldpassword');
		$gc_id = $this->input->post('id');
		
		$this->load->model('member/igetcard');
		$card = $this->igetcard->getCardById($gc_id);

		$this->load->model('member/imember');
		$member = $this->imember->getUserModel();

		$member->Ic_num = $card->code;
		$member->mobile = $telephone;
		$member->Ic_pwd = $oldpassword;
		
		
		$memberinfo = $this->imember->getUserinfo($member);
		
		if($memberinfo) {
			$memberinfo->Ic_pwd = $password;
			$result = $this->imember->modUserinfo($memberinfo);
			
			if($result) {
				$this->load->model('pms_cshis/ivcardadd');
				$this->ivcardadd->updateInfoById($gc_id, array('password'=>$password), 'gc_id');
				echo "密码修改成功！";
			}
		} else {
			echo "资料不正确!";
		}
	}
	
	public function qrcode()
	{
		$gc_id = $this->input->get('id');
		
		$this->load->model('member/igetcard');
		$this->load->model('pms_cshis/ivcardadd');
		$this->load->model('member/icard');
		
		$data['card'] = $this->igetcard->getCardById($gc_id);
		$data['addinfo'] = $this->ivcardadd->getInfoByGcId($gc_id);
        $data['detail']  = $this->icard->getCardById($data['card']->ci_id);

	    $this->display('member/vqrcode', $data);
	}
	
	public function charge()
	{
		$openid = $this->getOpenId();
		
		$gc_id = $this->input->get('id');
		
		$this->load->model('pms_cshis/vcardadd');
		$data['info'] = $this->vcardadd->getInfoByGcId($gc_id);
		
		$data['openid'] = $openid;
		$data['out_trade_no'] = '';
		$data['body'] = '';
		$data['total_fee'] = '';
		$data['notify_url'] = '';
		$data['success_url'] = '';
		$data['fail_url'] = '';
		
		$this->load->view('member/charge', $data);
	}
	
	
	public function bind()
	{
		$this->getOpenId();
		
        $this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		
		$this->display('member/bindcard', $data);
	}
	
	//会员退出登录页面
	public function unbindmember()
	{
		$openid = $this->getOpenId();
		
		$this->load->model('member/imember');
		$data['member'] = $this->imember->getMemberInfoByOpenId($openid);
		
		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		
		$this->load->view('member/unbindmember', $data);
	}
	
	//会员退出登录保存
	public function unbindmembercard()
	{
	    $openid = $this->getOpenId();
	
		if(!isset($openid)) {
			redirect('member/center');
			return;
		}
		
		$this->load->library('session');
		
		$data['membership_number'] =  '';
		$this->load->model('member/imember');
		$result = $this->imember->addMemberInfo($openid, $data);
		$this->imember->updateStatus($openid,0);
		$this->imember->updateLevel($openid,0);
		if($result) {
			$this->session->set_userdata('message', "退出成功！");
		} else {
			$this->session->set_userdata('message', "退出失败！");
		}
		
        redirect('member/center');
	}
	
	//会员卡登录
	public function bindmembercard()
	{
		$openid = $this->getOpenId();
	
		$this->load->model('member/imember');
		$member = $this->imember->getMemberInfoByOpenId($openid);
	
		if($member && !empty($member->membership_number)) {
			redirect('bgyhotel/bindcard/unbindmember');
		}
	
		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
	
		$this->display('member/bindmembercard');
	}
	
	//会员卡登录保存
	public function bindmcsave()
	{
		$openid = $this->getOpenId();
	
		if(!isset($openid)) {
			redirect('member/center');
			return;
		}
	
		$this->load->library('session');
	
		$this->load->model('member/imember');
		$member = $this->imember->getMemberByOpenId($openid);
	
		if(!$member || !isset($member->mem_id)) {
			redirect('member/center');
			return;
		}
	
		$account = $this->input->post('account');
		$password = $this->input->post('password');
		
		$this->load->model('member/imember');
		$user = $this->imember->getUserModel();
		$user->Ic_pwd = $password;
		//$user->Ic_typ = "P";
		
		if(strpos($account,'@') !== false) {
			$user->email = $account;
		} elseif(strlen($account)==11) {
			$user->mobile = $account;
		} else {
			$user->Ic_num = $account;
		}

		$retuser = $this->imember->getUserinfo($user);

		if($retuser && in_array($retuser->Ic_typ,$this->member_types)) {
			$data['mem_id']            =  $member->mem_id;
			$data['membership_number'] =  $retuser->Ic_num;
			$data['email']             =  $retuser->email;
			$data['name']              =  $retuser->gh_nm;
			$data['telephone']         =  $retuser->mobile;
			$data['identity_card']     =  $retuser->crtf_num;
			$data['custom3']           =  $retuser->Ic_pwd;
			
			if($retuser->sex_cd=='男') {
				$data['sex']=1;
			} else {
				$data['sex']=2;
			}
			
			$this->imember->addMemberInfo($openid, $data);
			$this->imember->updateStatus($openid,1);
			$this->imember->updateLevel($openid,array_search($retuser->Ic_typ,$this->member_types));
			$this->imember->updateBonus($openid,abs((string)$retuser->tot_score));
			$this->session->set_userdata('message', "登录成功!");
		} else {
			$this->session->set_userdata('message', "登录失败!");
		}
	
		redirect('member/center');
	}
	
	//会员卡修改密码页面
	public function mcmodpwd()
	{
		$openid = $this->getOpenId();
		
		if(!isset($openid)) {
			redirect('member/center');
			return;
		}
		
		$this->load->model('member/imember');
		$member = $this->imember->getMemberByOpenId($openid);
		$data['mem_id'] = $member->mem_id;
		
		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		
		$this->display('member/mcmodpassword',$data);
	}
	
	//会员卡修改密码保存
	public function mcmodpwdsave()
	{		
		$openid = $this->getOpenId();
		
		if(!isset($openid)) {
			redirect('member/center');
			return;
		}
		
	    $this->load->model('member/imember');
		$member = $this->imember->getMemberInfoByOpenId($openid);
	
		if(!$member || !isset($member->mem_id)) {
			redirect('member/center');
		}
		
		$this->load->library('session');
		if(empty($member->membership_number)) {
			$this->session->set_userdata('message', "不存在会员卡!");
			redirect('member/center');
			exit;
		}

		$oldpassword = $this->input->post('oldpassword');
		$password = $this->input->post('password');
		
		$this->load->model('member/imember');
		$user = $this->imember->getUserModel();
		$user->Ic_pwd = $oldpassword;
		$user->Ic_typ = "P";
		$user->Ic_num = $member->membership_number;
		
		$retuser = $this->imember->getUserinfo($user);

		if($retuser) {
			$user->Ic_pwd = $password;
			$result = $this->imember->modUserinfo($user);
			
			if($result) {
				$this->session->set_userdata('message', "修改密码成功!");
			} else {
				$this->session->set_userdata('message', "修改密码失败!");
			}
		} else {
			$this->session->set_userdata('message', "原密码错误!");
		}
		redirect('member/center');
	}
	
	//找回密码界面
	public function findpwd()
	{
		$openid = $this->getOpenId();
		
		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		
		$this->display('member/findpwd', $data);
	}
	
	//找回密码保存
	public function findpwdsave()
	{
		$openid = $this->getOpenId();
		
		$this->load->library('session');
		
		$telephone = $this->input->post('telephone');
		$password = $this->input->post('password');
		
		$this->load->model('member/imember');
		$user = $this->imember->getUserModel();
		$user->Ic_typ = "P";
		$user->mobile = $telephone;
		
		$retuser = $this->imember->getUserinfo($user);
		
		if($retuser) {
			$retuser->Ic_pwd = $password;
			$result = $this->imember->modUserinfo($retuser);
				
			if($result) {
				$this->session->set_userdata('message', "密码找回成功!请使用新密码登录!");
			} else {
				$this->session->set_userdata('message', "密码找回失败!");
			}
		} else {
			$this->session->set_userdata('message', "手机号码错误!");
		}
		
		redirect('member/center');
	}
	
	public function displayresult()
	{
		$openid = $this->getOpenId();
	
		if(!isset($openid)) {
			redirect('member/center');
			return;
		}
	
		$result = $this->input->get('r');
	
		if(isset($result) && ($result==1)) {
			$this->load->model('member/imember');
			$member = $this->imember->getMemberDetailByOpenId($openid);
	
			if(!$member || !isset($member->mem_id)) {
				redirect('member/center');
				return;
			}
	
			if($member->membership_number) {
				$result=1;
			} else {
				$result=0;
			}
	
			$data['member'] = $member;
		} else {
			$result = 0;
		}
	
		$data['result'] = $result;
		$this->display('member/displayresult', $data);
	}
	
	public function sendsms()
	{
		if(ENVIRONMENT=='development') {
			return true;
		}
		
		$telephone = $this->input->get("telephone");
		$code = $this->input->get("code");

		$this->load->model('bgyhotel/bgymember');
		$user = $this->bgymember->getUserModel();
		$user->Ic_num = $code;
		$user->mobile = $telephone;
		$getresult = $this->bgymember->getUserinfo($user);
		
		if($getresult) {
			$num = mt_rand(100000, 999999);
			
			$this->load->library('session');
			$this->session->set_userdata('sms', $num);
			
			$this->load->model('sendsms');
			$this->sendsms->MessageContent = "您的验证码为".$num."请妥善保管并及时输入。";
			$this->sendsms->UserNumber = $telephone;
			$res = $this->sendsms->send();
			
			echo "验证码已经发送!";
		} else {
			echo "卡号跟手机号码匹配不上!";
		}	
	}
	
	//储值卡绑定保存
	public function save()
	{
		$openid = $this->getOpenId();

		$this->load->library('session');
		
		$data = $this->input->post();
		$this->load->model('member/getcard');
		
		$this->load->model('member/imember');
		$member = $this->imember->getMemberByOpenId($openid);
		
		$this->load->model('member/igetcard');
		$card = $this->igetcard->getGcardByCode($data['code']);
		if(count($card) > 0)
			$card = $card[0];
		if($card && $card->status==Getcard::STATUS_HAVE_RECEIVE) {
			$this->session->set_userdata('message', "该卡已经被绑定!");
			redirect('member/bindcard');
			return $this;
		}
		if($member && isset($member->mem_id)) {
			$this->load->model('member/imember');
			$getresult = $this->imember->getPmsMemberCard($data['code'],$data['password'],$this->inter_id,0);
			if($getresult && in_array($getresult->Ic_typ,$this->value_types)) {
				$writeAdapter = $this->load->database('member_write',true);
				
				$gdata['ci_id'] = 1;
				$gdata['code']  = $data['code'];
				$gdata['mem_id'] = $member->mem_id;
				$gdata['openid'] = $openid;
				$gdata['status'] = Getcard::STATUS_HAVE_RECEIVE;

				$writeAdapter->trans_begin();
				$gc_id = $this->igetcard->addGetCard($gdata);

				if($gc_id && !is_object($gc_id)) {
					$adddata['mem_id'] = $member->mem_id;
					$adddata['gc_id'] = $gc_id;
					$adddata['telephone'] = $getresult->mobile;
					$adddata['password'] = $getresult->Ic_pwd;
					$adddata['balance'] = abs($getresult->ic_bal);
					$adddata['name']    = $getresult->gh_nm;
					$adddata['identity_card'] = $getresult->crtf_num;
	
					$this->load->model('pms_cshis/ivcardadd');
					$ga_id = $this->ivcardadd->createInfo($adddata);
					if($ga_id) {
						$writeAdapter->trans_commit();
						$this->session->set_userdata('message', "绑定成功!");
					} else {
						$this->session->set_userdata('message', "绑定失败!");
						$writeAdapter->trans_rollback();
					}
				} else {
					$this->session->set_userdata('message', "绑定失败!");
					$writeAdapter->trans_rollback();
				}
			} else {
				$this->session->set_userdata('message', "卡信息不正确，请重新输入!");
			}
		} else {
			$this->session->set_userdata('message', "系统出错!");
		}
		
		redirect('member/bindcard');
	}
}