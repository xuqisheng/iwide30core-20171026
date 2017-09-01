<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台会员等级升级规则
*	@author Frandon
*	@time 四月十一号
*	@version www.iwide.cn
*	@
*/
class Memberuplevel extends MY_Admin
{

	//升级等级规则列表
	public function index(){
		$post_data = array(
			'inter_id'=>$this->session->get_admin_inter_id(),
			);
		//请求登录配置信息URL
		$level_config = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_all_level_config" , $post_data );
		$data = array(
			'levelconfig' =>$level_config['data'],
			);
		$html= $this->_render_content($this->_load_view_file('edit'),$data,false);
	}

	public function edit_post(){
		$post_level_data = $_POST;
		$post_data['inter_id'] =$this->session->get_admin_inter_id();
		$level_config = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_all_level_config" , $post_data );
		//对修改的数据进行处理
		foreach ($level_config['data'] as $key => $value) {
			$level_id = $value['member_lvl_id'];
			//进行修改
			$post_lvl_data = array(
				'inter_id'=>$this->session->get_admin_inter_id(),
				'credit_up_level'=> $post_level_data['credit_up_level_'.$level_id],
				'deposit_up_level'=> $post_level_data['deposit_up_level_'.$level_id],
				'member_lvl_id'=>$value['member_lvl_id'],
				'lvl_name'=> $value['lvl_name'],
				'lvl_pms_code'=> $value['lvl_pms_code'],
				'base_discount'=>$value['base_discount'],
				'bonus_size'=>$value['bonus_size'],
				);
			$this->doCurlPostRequest( PMS_PATH_URL."adminmember/update_level_config" , $post_lvl_data );
		}
		redirect('membervip/memberuplevel');
		exit;
	}

	/**
	* 封装curl的调用接口，post的请求方式
	* @param string URL
	* @param string POST表单值
	* @param array 扩展字段值
	* @param second 超时时间
	* @return 请求成功返回成功结构，否则返回FALSE
	*/
	protected function doCurlPostRequest( $url , $post_data , $timeout = 5) {
		$requestString = http_build_query($post_data);
		if ($url == "" || $timeout <= 0) {
			return false;
		}
		$curl = curl_init();
		//设置抓取的url
		curl_setopt($curl, CURLOPT_URL, $url);
		//设置头文件的信息作为数据流输出
		curl_setopt($curl, CURLOPT_HEADER, false);
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		//設置請求數據返回的過期時間
		curl_setopt ( $curl, CURLOPT_TIMEOUT, ( int ) $timeout );
		//设置post方式提交
		curl_setopt($curl, CURLOPT_POST, true);
		//设置post数据
		curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
		//执行命令
		$res = curl_exec($curl);
		//关闭URL请求
		curl_close($curl);
		return json_decode($res,true);
	}

}
?>