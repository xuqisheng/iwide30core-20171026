<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户积分 
*	@author  Frandon
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Balance extends MY_Front_Member_Wxapp
{
	//会员余额记录
	public function index(){
		//$this->openid = 'oo89wt3bAXi_gR3gQ8KTJIhqMulM';
		$post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息
		$centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
		$member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
		$last_balance_id = $this->input->get('last_balance_id');
		$credit_type = $this->get_source ( 'b_type' );
		$post_bonus_url = PMS_PATH_URL."member/getbalance";
		$post_bonus_data =  array(
			'inter_id'=>$this->inter_id,
			'openid'=>$this->openid,
			'member_info_id'=>$member_info_id,
			'last_balance_id'=>$last_balance_id,
			'credit_type'=>$credit_type,
			'pagenum'=>6,
			);
		//请求余额记录
		$bonus_list = $this->doCurlPostRequest( $post_bonus_url , $post_bonus_data )['data'];
		if($bonus_list){
            if(isset($bonus_list[0]['balance_log_id']))
			    $last_balance_id = max(array_column($bonus_list,'balance_log_id'));
            else
                $last_balance_id = 0;
		}else{
            $bonus_list = array();
			$last_balance_id = 0;
		}

		$data = array(
			'bonuslist'=>$bonus_list,
			'last_cradit_id'=>$last_balance_id,
			'credit_type'=>$credit_type,
			);
		$this->out_put_msg ( 1, '', $data ,'membervip/balance');
		//$this->load->view('member/'.$this->_template.'/balance',$data);
	}
	
	
	public function new_ui(){
		//$this->openid = 'oo89wt3bAXi_gR3gQ8KTJIhqMulM';
		$post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
				'inter_id'=>$this->inter_id,
				'openid' =>$this->openid,
		);
		//请求用户登录(默认)会员卡信息
		$centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
		$member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
		$last_balance_id = $this->input->get('last_balance_id');
		$credit_type = $this->get_source ( 'b_type' );
		$post_bonus_url = PMS_PATH_URL."member/getbalance";
		$post_bonus_data =  array(
				'inter_id'=>$this->inter_id,
				'openid'=>$this->openid,
				'member_info_id'=>$member_info_id,
				'last_balance_id'=>$last_balance_id,
				'credit_type'=>$credit_type,
				'pagenum'=>6,
		);
		//请求余额记录
		$bonus_list = $this->doCurlPostRequest( $post_bonus_url , $post_bonus_data )['data'];
		if($bonus_list){
			if(isset($bonus_list[0]['balance_log_id']))
				$last_balance_id = max(array_column($bonus_list,'balance_log_id'));
			else
				$last_balance_id = 0;
		}else{
			$bonus_list = array();
			$last_balance_id = 0;
		}
		
		//将金额前增加"+","-"
		foreach($bonus_list as $key => $d ){
			
			if($d['log_type'] == 1){
				$bonus_list[$key]['amount_str'] = "+".$d['amount'];
			}else{
				$bonus_list[$key]['amount_str'] = "-".$d['amount'];
			}
			
		}
		
		//按新ui按月分类数据
		$bonus_list_bymonth = array();
		
		foreach($bonus_list as $d){
			
			$temp_time = strtotime($d['last_update_time']);
			$month = date("Y-m",$temp_time);
			$bonus_list_bymonth[$month]['month'] = date("Y年m",$temp_time);
			$bonus_list_bymonth[$month]['list'][] = $d;
			
		}
		
		//高到低排序
		krsort($bonus_list_bymonth);
		
		//小程序去key处理，将所有数组的key变为0-n
		$sort_bonus_list_bymonth = array();
		foreach($bonus_list_bymonth as $d){
			$sort_bonus_list_bymonth[] = $d;
		}
	
		$data = array(
				'bonuslist'=>$sort_bonus_list_bymonth,
				'last_cradit_id'=>$last_balance_id,
				'credit_type'=>$credit_type,
				'centerinfo'=>$centerinfo
		);
		$this->out_put_msg ( 1, '', $data ,'membervip/balance');
		//$this->load->view('member/'.$this->_template.'/balance',$data);
	}
	


    //余额支付密码设置
    public function setpwd(){
    	$this->load->model('wx/Publics_model');
	    $data['info'] =$this->Publics_model->get_fans_info($this->openid);
	    $post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息
		$centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
		$member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
    	//获取支付密码设置状态
    	$post_url = INTER_PATH_URL.'setpassword/pay_password_status';
    	$post_data = array(
    		'inter_id'=>$this->inter_id,
    		'openid'=>$this->openid,
    		'member_info_id'=>$member_info_id,
    		'token'=>$this->_token,
    		);
    	$status= $this->doCurlPostRequest( $post_url , $post_data );
	    if($status['err']==0){
	    	Header( "Location:".base_url('index.php/membervip/balance/changepwd?id='.$this->inter_id) );exit;
	    }
        $this->load->view('member/'.$this->_template.'/setpwd',$data);
    }

    //保存支付密码设置
    public function save_setpwd(){
    	$edit_data = $this->input->post();
    	if($edit_data['password']!=$edit_data['confirm_pwd']){
    		echo json_encode( array('err'=>40003,'msg'=>'密码不一致') );exit;
    	}
    	if( strlen($edit_data['password'])<6 ){
    		echo json_encode( array('err'=>40003,'msg'=>'密码长度小于6位') );exit;	
    	}
    	$post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息
		$centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
		$member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
    	//获取支付密码设置状态
    	$post_url = INTER_PATH_URL.'setpassword/set_pay_password';
    	$post_data = array(
    		'inter_id'=>$this->inter_id,
    		'openid'=>$this->openid,
    		'member_info_id'=>$member_info_id,
    		'password'=>$edit_data['password'],
    		'token'=>$this->_token,
    		);
    	$status= $this->doCurlPostRequest( $post_url , $post_data );
    	echo json_encode($status);
    }

    //余额支付密码修改
    public function changepwd(){
    	$this->load->model('wx/Publics_model');
	    $data['info'] =$this->Publics_model->get_fans_info($this->openid);
	    $post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息
		$centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
		$member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
    	//获取支付密码设置状态
    	$post_url = INTER_PATH_URL.'setpassword/pay_password_status';
    	$post_data = array(
    		'inter_id'=>$this->inter_id,
    		'openid'=>$this->openid,
    		'member_info_id'=>$member_info_id,
    		'token'=>$this->_token,
    		);
    	$status= $this->doCurlPostRequest( $post_url , $post_data );
	    if($status['err']>0){
	    	Header( "Location:".base_url('index.php/membervip/balance/setpwd?id='.$this->inter_id) );exit;
	    }
        $this->load->view('member/'.$this->_template.'/changepwd',$data);
    }

    //保存支付密码修改
    public function save_changepwd(){
    	$edit_data = $this->input->post();
    	$post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息
		$centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
		$member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
		$balance_passwd = isset( $centerinfo['data']['balance_passwd'] )?$centerinfo['data']['balance_passwd']:null;
		if( !$balance_passwd ){
			echo json_encode( array('err'=>40003,'msg'=>'用户密码获取错误') );exit;
		}
		if( sha1( $edit_data['oldpassword'].'jfkhp' ) != $balance_passwd ){
			echo json_encode( array('err'=>40003,'msg'=>'原始密码不正确') );exit;	
		}
		if($edit_data['newpassword']!=$edit_data['confirm_pwd']){
			echo json_encode( array('err'=>40003,'msg'=>'确认密码不一致') );exit;	
		}
		$post_url = INTER_PATH_URL.'setpassword/set_pay_password';
    	$post_data = array(
    		'inter_id'=>$this->inter_id,
    		'openid'=>$this->openid,
    		'member_info_id'=>$member_info_id,
    		'password'=>$edit_data['newpassword'],
    		'token'=>$this->_token,
    		);
    	$status= $this->doCurlPostRequest( $post_url , $post_data );
    	echo json_encode($status);
    }

}
?>