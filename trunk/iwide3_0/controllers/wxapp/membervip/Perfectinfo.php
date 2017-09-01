<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户资料 
*	@author  Frandon
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Perfectinfo extends MY_Front_Member_Wxapp
{
	//会员卡资料修改页面
	public function index(){
		$post_config_url = PMS_PATH_URL."adminmember/getmodifyconfig";
		$post_config_data =  array(
			'inter_id'=>$this->inter_id,
			);
		//请求资料修改配置
		$data['modify_config'] = $this->doCurlPostRequest( $post_config_url , $post_config_data )['data'];
		$post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息
		$data['centerinfo'] = $this->doCurlPostRequest( $post_center_url , $post_center_data )['data'];

		$this->out_put_msg ( 1, '', $data ,'membervip/perfectinfo');
		//$this->load->view('member/'.$this->_template.'/perfectinfo',$data);
	}

    /**
     * 2016-07-20
     * @author knight
     * 变更领取卡劵和礼包的方式,改为接口请求
     * 保存修改资料（本地化同步）
     */
	public function save(){
		
		if($this->get_source ( 'name')){ $data['name']=$this->get_source ( 'name'); }
		if($this->get_source ( 'phone')){ $data['cellphone']=$this->get_source ( 'phone'); }
		if($this->get_source ( 'email')){ $data['email']=$this->get_source ( 'email'); }
		if($this->get_source ( 'idno')){ $data['id_card_no']=$this->get_source ( 'idno'); }
		if($this->get_source ( 'sex')){ $data['sex']=$this->get_source ( 'sex'); }
		if($this->get_source ( 'birthday')){ $data['birth']=strtotime($this->get_source ( 'birthday')); }
		if(isset($_POST['phonesms'])){ $data['sms']=$this->get_source ( 'phonesms'); }
		if(isset($_POST['smstype'])){ $data['smstype']=$this->get_source ( 'smstype'); }
		//碧桂园先写死验证
		if($this->inter_id == 'a421641095'){
				
				
			if( !isset($_POST['name']) || $_POST['name'] == ""){
					
				$this->out_put_msg ( 2,'姓名不能为空', $login_result ,'membervip/reg/savereg');
				return;
					
			}
				
			
		
		}
		$post_savevip_url = PMS_PATH_URL."member/save_memberinfo";
		$post_savevip_data = array(
			'inter_id'=>$this->inter_id,
			'openid'=>$this->openid,
			'data'=>$data,
        );
		$save_result = $this->doCurlPostRequest( $post_savevip_url , $post_savevip_data );
        if($save_result['err']==0){
            //获取优惠信息
            $post_card = array(
                'token'=>$this->_token,
                'inter_id'=>$this->inter_id,
                'type'=>'perfect',
                'is_active'=>'t'
            );
            $rule_info= $this->doCurlPostRequest( PMS_PATH_URL."cardrule/get_package_card_rule_info" , $post_card );
            if(isset($rule_info['data'])){
                $rule_info = $rule_info['data'];
            }
            $packge_url = INTER_PATH_URL.'package/give'; //领取礼包
            $card_url = PMS_PATH_URL.'cardrule/reg_gain_card'; //领取卡劵
            if(!empty($rule_info) && is_array($rule_info)){
                foreach ($rule_info as $key => $item){
                    if( isset($item['is_package']) && $item['is_package']=='t'){
                        $package_data = array(
                            'token'=>$this->_token,
                            'inter_id'=>$this->inter_id,
                            'openid'=>$this->openid,
                            'uu_code'=>$this->openid.'perfect'.uniqid(),
                            'package_id'=>$item['package_id'],
                            'card_rule_id'=>$item['card_rule_id'],
                            'number'=>$item['frequency']
                        );
                        $this->doCurlPostRequest( $packge_url , $package_data );
                    }elseif (isset($item['is_package']) && $item['is_package']=='f'){
                        $card_data = array(
                            'token'=>$this->_token,
                            'inter_id'=>$this->inter_id,
                            'openid'=>$this->openid,
                            'card_id'=>$item['card_id'],
                            'type'=>'perfect'
                        );
                        $this->doCurlPostRequest( $card_url , $card_data );
                    }
                }
            }
		}
		if($save_result['err'] == 0){
			$this->out_put_msg ( 1, '修改成功', $save_result ,'membervip/balance/save');
		}else{
			$this->out_put_msg ( 2, $save_result['msg'], "" ,'membervip/balance/save');
		}
       // echo json_encode($save_result);
	}



}
?>