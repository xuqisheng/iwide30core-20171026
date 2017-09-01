<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	会员卡卡券
*	@author  Frandon
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Card extends MY_Front_Member_Wxapp
{
	//会员卡券列表
	public function index(){
		if($this->inter_id == 'a452233816'){
			$this->openid = 'oo89wt1nRe9r4-xQ-wu6hYhhD7dY';
		}
		$post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息
		$member_info = $this->doCurlPostRequest( $post_center_url , $post_center_data )['data'];
		$member_info_id = isset($member_info['member_info_id'])?$member_info['member_info_id']:0;
		//获取会员卡券列表
		$post_member_card_url = INTER_PATH_URL."membercard/getlist";
		$post_member_card_data = array(
			'inter_id'=>$this->inter_id,
			'member_info_id'=>$member_info_id,
			'token'=>$this->_token,
			'num'=>30,
        );
		$card_info = $this->doCurlPostRequest( $post_member_card_url , $post_member_card_data );
		if( isset($card_info['err']) && $card_info['err'] ){
			$data['cardlist'] = array();
			$data['next_id'] = 0;
		}else{
			$data['cardlist'] = $card_info['data'];
			$data['next_id'] = isset($card_info['next_id'])?$card_info['next_id']:0;
		}
		$data['inter_id'] = $this->inter_id;
		
		foreach($data['cardlist']  as $key => $card){
			
			if(!isset($card['is_pms_card'])){
				
				if($card['card_type']==1){
					
					$data['cardlist'][$key]['card_type_name'] = '抵用券';
					
				}elseif($card['card_type']==2){
					
					$data['cardlist'][$key]['card_type_name'] = '折扣券';
					
				}elseif($card['card_type']==3){
					
					$data['cardlist'][$key]['card_type_name'] = '兑换券';
					
				}elseif($card['card_type']==4){
					
					$data['cardlist'][$key]['card_type_name'] = '储值卡';
					
				}else{
					
					$data['cardlist'][$key]['card_type_name'] = '错误卡券';
					
				}
				
			}else{
				
				$data['cardlist'][$key]['card_type_name'] = '官方券';
				
			}

			
		}
		
		$this->out_put_msg ( 1, "", $data ,'membervip/card');
		//$this->load->view('member/'.$this->_template.'/card',$data);
	}
	
	
	

    //获取pms卡券列表-隐居定制
    public function pcard(){
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );
        //请求用户登录(默认)会员卡信息
        $member_info = $this->doCurlPostRequest( $post_center_url , $post_center_data )['data'];
        $member_info_id = isset($member_info['member_info_id'])?$member_info['member_info_id']:0;
        //获取会员卡券列表
        $post_member_card_url = INTER_PATH_URL."membercard/get_pms_card_list";
        $post_member_card_data = array(
            'inter_id'=>$this->inter_id,
            'member_info_id'=>$member_info_id,
            'token'=>$this->_token,
            'num'=>30,
        );
        $card_info = $this->doCurlPostRequest( $post_member_card_url , $post_member_card_data );
        if( isset($card_info['err']) && $card_info['err'] ){
            $data['cardlist'] = array();
            $data['next_id'] = 0;
        }else{
            $data['cardlist'] = $card_info['data'];
            $data['next_id'] = isset($card_info['next_id'])?$card_info['next_id']:0;
        }
        $data['inter_id'] = $this->inter_id;
        $this->load->view('member/'.$this->_template.'/card',$data);
    }

    public function pcardinfo(){
        $member_card_id = $this->input->get('member_card_id');
        if(!empty($member_card_id)){
            $post_center_url = PMS_PATH_URL."member/center";
            $post_center_data =  array(
                'inter_id'=>$this->inter_id,
                'openid' =>$this->openid,
            );
            //请求用户登录(默认)会员卡信息
            $member_info = $this->doCurlPostRequest( $post_center_url , $post_center_data )['data'];
            $member_info_id = isset($member_info['member_info_id'])?$member_info['member_info_id']:0;
            //获取会员卡券列表
            $post_member_card_url = INTER_PATH_URL."membercard/get_pms_card_info";
            $post_member_card_data = array(
                'inter_id'=>$this->inter_id,
                'member_info_id'=>$member_info_id,
                'member_card_id'=>$member_card_id,
                'token'=>$this->_token,
                'num'=>30,
            );
            $card_info = $this->doCurlPostRequest( $post_member_card_url , $post_member_card_data );

            if(isset($card_info['err']) && $card_info['err']>0){
                $data['card_info'] = array();
                $data['next_id'] = 0;
            }else{
                $data['card_info'] = $card_info['data'];
                $data['next_id'] = isset($card_info['next_id'])?$card_info['next_id']:0;
            }
        }
        $this->load->model('wx/access_token_model');
        $this->load->model('wx/Publics_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        $data['public'] = $this->Publics_model->get_public_by_id($this->inter_id);
        $data['inter_id'] = $this->inter_id;
        $this->load->view('member/'.$this->_template.'/pcardinfo',$data);
    }

	//Ajax会员卡券列表
	public function ajax_card(){
		$next_id = $this->input->post('next_id');
		$post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息
		$member_info = $this->doCurlPostRequest( $post_center_url , $post_center_data )['data'];
		$member_info_id = isset($member_info['member_info_id'])?$member_info['member_info_id']:0;
		//获取会员卡券列表
		$post_member_card_url = INTER_PATH_URL."membercard/getlist";
		$post_member_card_data = array(
			'inter_id'=>$this->inter_id,
			'member_info_id'=>$member_info_id,
			'token'=>$this->_token,
			'next_id'=>$next_id,
			'num'=>10,
			);
		$card_info = $this->doCurlPostRequest( $post_member_card_url , $post_member_card_data );
		if( isset($card_info['err']) && $card_info['err'] ){
			$data['cardlist'] = array();
			$data['next_id'] = 0;
		}else{
			$data['cardlist'] = $card_info['data'];
			$data['next_id'] = $card_info['next_id'];
		}
		$data['inter_id'] = $this->inter_id;
		echo json_encode($data);
	}

	/**
	*	会员关注送券页面
	*
	*
	*
	*/
	public function getcard(){
//		$this->check_user_login();
        //获取用户的详细信息
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );

        //请求用户登录(默认)会员卡信息
        $memberInfo= $this->doCurlPostRequest( PMS_PATH_URL."member/center" , $post_center_data )['data'];
        if(!$memberInfo['member_info_id']){
            $uri = EA_const_url::inst()->get_url('*/center',array('id'=>$this->inter_id));
            redirect($uri);exit;
        }

        $card_rule_id = isset($_GET['card_rule_id']) ? intval($_GET['card_rule_id']) : 0;
        //获取领取卡券/礼包的信息
		$post_card = array(
            'token'=>$this->_token,
			'inter_id'=>$this->inter_id,
			'card_rule_id'=>$card_rule_id,
			'type'=>'gaze',
            'is_active'=>'t',
            'status'=>1,
            'member_info_id'=>$memberInfo['member_info_id'],
            'open_id'=>$this->openid,
            'model'=>'vip'
        );
		$data['card_info'] = $this->doCurlPostRequest( PMS_PATH_URL."cardrule/get_package_card_info" , $post_card )['data'];

        $data['gain_count'] = isset($data['card_info']['receive_num']) ? intval($data['card_info']['receive_num']) : 0;

		$this->load->model('wx/access_token_model');
		$this->load->model('wx/Publics_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		$data['public'] = $this->Publics_model->get_public_by_id($this->inter_id);
		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        $data['card_rule_id'] = $card_rule_id;
        /*echo '<pre>';
        print_r($data);
        echo '</pre>';exit;*/
		$this->load->view('member/'.$this->_template.'/getcard',$data);
	}

	//领取卡券
	public function addcard(){
		$card_rule_id = isset($_POST['card_rule_id'])?(int)$_POST['card_rule_id']:0;
		//获取领取卡券的信息
		$post_card = array(
			'inter_id'=>$this->inter_id,
			'card_rule_id'=>$card_rule_id,
			'active'=>'gaze',
			);
		$card_info = $this->doCurlPostRequest( PMS_PATH_URL."cardrule/get_rule_card_info" , $post_card )['data'];
		if(!$card_info){
			echo json_encode(array('err'=>3,'msg'=>'卡券信息不存在'));exit;
		}
		//获取用户的详细信息
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息
		$memberInfo= $this->doCurlPostRequest( PMS_PATH_URL."member/center" , $post_center_data )['data'];
		if(!$memberInfo['member_info_id']){
			echo json_encode(array('err'=>3,'msg'=>'会员卡信息不存在'));exit;
		}
		//获取用户已领取过该券的总数
		$post_card_gain = array(
			'inter_id'=>$this->inter_id,
			'member_info_id'=>$memberInfo['member_info_id'],
			'card_id'=> isset($card_info['card_id'])?$card_info['card_id']:0,
			);
		$gain_count = $this->doCurlPostRequest( PMS_PATH_URL."cardrule/member_gain_card_count" , $post_card_gain )['data'];
		if(  $gain_count>=$card_info['frequency']){
			echo json_encode(array('err'=>2,'msg'=>'您已领取过卡券了'));exit;
		}
		//领取卡券
		$add_card_data = array(
			'inter_id'=>$this->inter_id,
			'member_info_id'=>$memberInfo['member_info_id'],
			'card_id'=>$card_info['card_id'],
			'module'=>'vip',
			'token'=>$this->_token,
			'uu_code'=>$this->openid.'gaze'.uniqid()
			);
		$add_card_result = $this->doCurlPostRequest( INTER_PATH_URL."intercard/receive" , $add_card_data );
		echo json_encode($add_card_result);
	}

	//卡券转赠页面
	public function givecard(){
		$this->load->model('wx/Publics_model');
	    $data['info'] =$this->Publics_model->get_fans_info($this->openid);
//	    $this->check_user_login();
		//获取卡券的详细
		$card_openid = isset($_GET['cardOpenid'])?$_GET['cardOpenid']:$this->openid;
		$member_card_id = isset($_GET['member_card_id'])?(int)$_GET['member_card_id']:0;
		$post_card_info_data = array(
			'token'=>$this->_token,
			'inter_id'=>$this->inter_id,
			'openid'=>$card_openid,
			'member_card_id'=>$member_card_id,
			);
		$card_info = $this->doCurlPostRequest( INTER_PATH_URL."membercard/getinfo" , $post_card_info_data );
		if(isset($card_info['data'])){
			$data['card_info']=$card_info['data'];
		}else{
			$data['card_info']=array();
		}
		$data['card_openid'] = $card_openid;
		$data['openid'] = $this->openid;
		$data['inter_id'] = $this->inter_id;
		$this->load->model('wx/access_token_model');
		$this->load->model('wx/Publics_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		$data['public'] = $this->Publics_model->get_public_by_id($this->inter_id);
		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		$this->load->view('member/'.$this->_template.'/givecard',$data);
	}

	//转赠卡券挂起
	public function hang_card(){
		//卡券转赠挂起
		$post_give_status_url = INTER_PATH_URL.'membercard/setgiving';
		$post_give_statue_data = array(
			'token'=>$this->_token,
			'inter_id'=>$this->inter_id,
			'openid'=>$this->openid,
			'member_card_id'=>$this->input->post('card_id'),
			);
		$result = $this->doCurlPostRequest( $post_give_status_url , $post_give_statue_data );
		echo json_encode($result);
	}

	//保存卡券转赠信息
	public function savegivecard(){
		$openid = isset($_POST['cardOpenid'])?$_POST['cardOpenid']:'';
		if(!$openid){ echo json_encode(array( 'err'=>1,'msg'=>'卡券用户信息不存在' )); }
		$card_id = isset($_POST['card_id'])?$_POST['card_id']:'';
		if(!$card_id){ echo json_encode(array( 'err'=>2,'msg'=>'卡券信息不存在' )); }
		$cardModule = 'vip';
		//卡券转赠
		$post_give_card_data = array(
			'member_card_id'=>$card_id,
			'from_openid'=>$openid,
			'to_openid'=>$this->openid,
			'token'=>$this->_token,
			'inter_id'=>$this->inter_id,
			'module'=>$cardModule,
			'scene'=>'give',
			'remark'=>'好友转赠',
			);
		$give_info = $this->doCurlPostRequest( INTER_PATH_URL."membercard/give" , $post_give_card_data );
		echo json_encode($give_info);
	}

	//卡券详细页面
	public function cardinfo(){
		$member_card_id = $this->input->get('member_card_id');
		$post_cardinfo_data = array(
			'member_card_id' =>$member_card_id,
			'token'=>$this->_token,
			'openid'=>$this->openid,
			'inter_id'=>$this->inter_id,
			);
		$data['card_info'] = $this->doCurlPostRequest( INTER_PATH_URL."membercard/getinfo" , $post_cardinfo_data )['data'];
		$this->load->model('wx/access_token_model');
		$this->load->model('wx/Publics_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		$data['public'] = $this->Publics_model->get_public_by_id($this->inter_id);
		$this->load->view('member/'.$this->_template.'/cardinfo',$data);
	}

	//卡券扫码使用
	public function codeuseoff(){
		$this->load->model('core/priv_admin_authid', 'admin_authid');
	    $is_permit= $this->admin_authid->can_access($this->openid);
	    if( $is_permit ){
    	    $header = array(
    	        'title'=> '扫码核销',
    	        'type'=>1,
    	    );
	        $this->load->helper('encrypt');
	        $encrypt_util= new Encrypt();
	        $token= $encrypt_util->encrypt($this->openid. date('YmdH') );

	        //增加以下jsapi
	        $base_api_list = array( 'scanQRCode', 'closeWindow' );
 	        $data= array(
 	            'message'=> '点击页面，开始核销',
	            'callback'=> EA_const_url::inst()->get_url('*/*/card_callback', array('id'=> $this->inter_id)),
                'js_api_list'=> $base_api_list,
	            'openid'=> $this->openid,
	            't'=> $token,
 	        );
 	        $data = array_merge($header,$data);
	    } else {
    	    $header = array(
    	        'title'=> '认证失败',
    	        'type'=>2,
    	    );
	        $base_api_list = array( 'closeWindow' );
	        $message= '您的微信号未经授权，不能进行此操作。';
	        $data= array(
	            'message'=> $message,
	            'js_api_list'=> $base_api_list,
            );
            $data = array_merge($header,$data);
	    }
	    $this->load->model('wx/access_token_model');
		$this->load->model('wx/Publics_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		$data['public'] = $this->Publics_model->get_public_by_id($this->inter_id);
	    $this->load->view('member/'.$this->_template.'/codeuseoff',$data);
	}
	/**
     * 扫码核销异步请求
     */
	public function card_callback(){
		$code = $this->input->post('code');
		$post_code_useoff_url = INTER_PATH_URL.'membercard/useoff_code';
		$post_card_useoff_data = array(
			'token'=>$this->_token,
			'inter_id'=>$this->inter_id,
			'consume_code'=>$code,
			);
		$useoff_result = $this->doCurlPostRequest( $post_code_useoff_url , $post_card_useoff_data );
		echo json_encode($useoff_result);
	}

	/**
	*	消费码核销
	*
	*/
	public function passwduseoff(){
		$member_card_id = $this->input->post('member_card_id');
		$passwd = $this->input->post('passwd');
		if(!$passwd){
			echo json_encode(array('err'=>110,'msg'=>'消费码不能为空'));exit;
		}
		if(!$member_card_id){
			echo json_encode(array('err'=>11,'msg'=>'卡券ID不存在'));exit;
		}
		$post_cardinfo_data = array(
			'member_card_id' =>$member_card_id,
			'token'=>$this->_token,
			'openid'=>$this->openid,
			'inter_id'=>$this->inter_id,
			);
		$cardInfo = $this->doCurlPostRequest( INTER_PATH_URL."membercard/getinfo" , $post_cardinfo_data );
		if(isset($cardInfo['err'])){
			echo json_encode($cardInfo);exit;
		}else{
			$cardInfo = $cardInfo['data'];
		}
		if($cardInfo['is_online']==1){
			echo json_encode(array('err'=>122,'msg'=>'卡券类型不支持消费码消费'));exit;
		}
		if($cardInfo['passwd']!=$passwd){
			echo json_encode(array('err'=>121,'msg'=>'消费码不正确'));exit;
		}
		//消费码使用
		$post_useone_url = INTER_PATH_URL.'membercard/useone';
		$post_useone_data = array(
			'member_card_id'=>$member_card_id,
			'token'=>$this->_token,
			'openid'=>$this->openid,
			'inter_id'=>$this->inter_id,
			'module'=>'vip',
			'scene'=>'vip',
			'remark'=>'消费码使用',
			'offline'=>2,
			);
		$useone = $this->doCurlPostRequest( $post_useone_url , $post_useone_data );
		if($useone['err'] == 0){
			$post_useoff_url = INTER_PATH_URL.'membercard/useoff';
			$post_useone_data = array(
				'member_card_id'=>$member_card_id,
				'token'=>$this->_token,
				'openid'=>$this->openid,
				'inter_id'=>$this->inter_id,
				'module'=>'vip',
				'scene'=>'vip',
				'remark'=>'消费码核销',
			);
			$useoff = $this->doCurlPostRequest( $post_useoff_url , $post_useone_data );
			echo json_encode($useoff);exit;
		}else{
			echo json_encode($useone);exit;
		}

	}

	//获取会员模式，对用户的操作进行限制
	protected function check_user_login(){
		//获取微信会员卡的信息
		$post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
		$userInfo = $this->doCurlPostRequest( $post_center_url , $post_center_data );
		if( isset($userInfo['data'])  ){
			$userinfo = $userInfo['data'];
			if($userinfo['value']=="login" && $userinfo['member_mode']==1 ){
				header("Location:".base_url("index.php/membervip/login?id=".$this->inter_id));exit;
			}
		}else{
			exit('userinfo is error');
		}
	}

	public function getpackage(){
        $package_id = isset($_POST['package_id'])?(int)$_POST['package_id']:0;
        $frequency = isset($_POST['frequency'])?(int)$_POST['frequency']:0;
        $card_rule_id = isset($_POST['card_rule_id'])? intval($_POST['card_rule_id']):0;
        //获取领取礼包的信息
        $post_card = array(
            'token'=>$this->_token,
            'inter_id'=>$this->inter_id,
            'status'=>1,
            'package_id'=>$package_id
        );
        $rule_info = $this->doCurlPostRequest( INTER_PATH_URL."package/getinfo" , $post_card );
        if(!isset($rule_info['data']) || empty($rule_info['data'])){
            echo json_encode(array('err'=>3,'msg'=>'礼包信息不存在'));exit;
        }

        //获取用户的详细信息
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );
        //请求用户登录(默认)会员卡信息
        $memberInfo= $this->doCurlPostRequest( PMS_PATH_URL."member/center" , $post_center_data )['data'];
        if(!$memberInfo['member_info_id']){
            echo json_encode(array('err'=>3,'msg'=>'会员卡信息不存在'));exit;
        }

        //获取用户已领取过礼包的总数
        $post_card_gain = array(
            'token'=>$this->_token,
            'inter_id'=>$this->inter_id,
            'member_info_id'=>$memberInfo['member_info_id'],
            'package_id'=> $package_id,
            'openid' =>$this->openid,
        );
        $package_count = $this->doCurlPostRequest( INTER_PATH_URL."package/member_gain_package_count" , $post_card_gain );

        if(isset($package_count['data']) && $package_count['data']>=$frequency){
            echo json_encode(array('err'=>2,'msg'=>'您已领取过礼包了'));exit;
        }

        //发送优惠套餐
        $packge_url = INTER_PATH_URL.'package/give';
        $package_data = array(
            'card_rule_id'=>$card_rule_id,
            'token'=>$this->_token,
            'inter_id'=>$this->inter_id,
            'openid'=>$this->openid,
            'uu_code'=>$this->openid.'gaze'.uniqid(),
            'package_id'=>$package_id,
            'number'=>$frequency
        );
        $package = $this->doCurlPostRequest( $packge_url , $package_data );
        echo json_encode($package);
    }
}
?>