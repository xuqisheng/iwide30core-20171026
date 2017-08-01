<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户积分 
*	@author  Frandon
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Bonus extends MY_Front_Member_Wxapp
{
	//会员积分列表
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
		$last_credit_id = $this->input->get('last_credit_id');
		$credit_type = $this->get_source('credit_type');
		
		$post_bonus_url = PMS_PATH_URL."member/getbouse";
		$post_bonus_data =  array(
			'inter_id'=>$this->inter_id,
			'openid'=>$this->openid,
			'member_info_id'=>$member_info_id,
			'last_credit_id'=>$last_credit_id,
			'credit_type'=>$credit_type,
			'pagenum'=>6,
			);
		//请求积分记录
		$bonus_list = $this->doCurlPostRequest( $post_bonus_url , $post_bonus_data )['data'];
		if($bonus_list){
		    $credit_log_id = array_column($bonus_list,'credit_log_id');
            if(!empty($credit_log_id))
			    $last_credit_id = max($credit_log_id);
            else
                $last_credit_id = 0;
        }else{
			$last_credit_id = 0;
		}

		$data = array(
			'bonuslist'=>$bonus_list,
			'last_cradit_id'=>$last_credit_id,
			'credit_type'=>$credit_type,
			);
		$this->out_put_msg ( 1, '', $data ,'membervip/bonus');
		//$this->load->view('member/'.$this->_template.'/bonus',$data);
	}
	
	
	//会员积分列表
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
		$last_credit_id = $this->input->get('last_credit_id');
		$credit_type = $this->get_source('credit_type');
	
		$post_bonus_url = PMS_PATH_URL."member/getbouse";
		$post_bonus_data =  array(
				'inter_id'=>$this->inter_id,
				'openid'=>$this->openid,
				'member_info_id'=>$member_info_id,
				'last_credit_id'=>$last_credit_id,
				'credit_type'=>$credit_type,
				'pagenum'=>6,
		);
		//请求积分记录
		$bonus_list = $this->doCurlPostRequest( $post_bonus_url , $post_bonus_data )['data'];
		if($bonus_list){
			$credit_log_id = array_column($bonus_list,'credit_log_id');
			if(!empty($credit_log_id))
				$last_credit_id = max($credit_log_id);
			else
				$last_credit_id = 0;
		}else{
			$last_credit_id = 0;
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
				'last_cradit_id'=>$last_credit_id,
				'credit_type'=>$credit_type,
				'centerinfo'=>$centerinfo
		);
		$this->out_put_msg ( 1, '', $data ,'membervip/bonus');
		//$this->load->view('member/'.$this->_template.'/bonus',$data);
	}

	//Ajax积分列表
	public function ajax_bouns(){
		$post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息
		$centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
		$member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
		$last_credit_id = $this->input->get('last_credit_id');
		$credit_type = $this->input->get('credit_type');
		$post_bonus_url = PMS_PATH_URL."member/getbouse";
		$post_bonus_data =  array(
			'inter_id'=>$this->inter_id,
			'openid'=>$this->openid,
			'member_info_id'=>$member_info_id,
			'last_credit_id'=>$last_credit_id,
			'credit_type'=>$credit_type,
			'pagenum'=>6,
			);
		//请求积分记录
		$bonus_list = $this->doCurlPostRequest( $post_bonus_url , $post_bonus_data )['data'];
		if($bonus_list){
			$last_credit_id = max(array_column($bonus_list,'credit_log_id'));
		}else{
			$last_credit_id = 0;
		}

		$data = array(
			'bonuslist'=>$bonus_list,
			'last_cradit_id'=>$last_credit_id,
			'credit_type'=>$credit_type,
			);
		echo json_encode($data);
	}

}
?>