<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户中心
*	@author  Frandon
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Center extends MY_Front_Member_Wxapp
{
	//会员卡用户中心
	public function index(){

		$this->load->model('wx/Publics_model');
	    $data['info'] =$this->Publics_model->get_fans_info($this->openid);
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
        $center_data = $this->doCurlPostRequest( $post_center_url , $post_center_data );
		$data['centerinfo'] = isset($center_data['data']) ? $center_data['data'] : null;
        //获取会员中心菜单列表
		$post_center_config_url = PMS_PATH_URL."adminmember/get_center_info";
		$post_center_config_data = array(
			'inter_id'=>$this->inter_id,
			);
		$center_config = $this->doCurlPostRequest( $post_center_config_url , $post_center_config_data )['data'];
		if(isset($center_config['value'])){
			$data['menukey'] = array_unique( array_column($center_config['value'],'group'));
			sort($data['menukey']);
		}
		//检测是否是分销账号和是否是协议客用户
		$this->load->model ( 'distribute/staff_model' );
		$saler_info = $this->staff_model->saler_info ( $this->openid, $this->inter_id );
		if($saler_info) {
			if ($saler_info && $saler_info ['status'] == 2){
                if(isset($saler_info ['distribute_hidden']) && $saler_info ['distribute_hidden'] == 0){
                    $data['isDistribution'] = 1;
                }else{
                    $data['isDistribution'] = 0;
                }
                    $data['is_club'] = $saler_info['is_club'];
			}else{
				$data['isDistribution'] = 0;
				$data['is_club'] = 0;
			}
		}else{
			$data['isDistribution'] = 0;
			$data['is_club'] = 0;
		}
		//检测是否是绑定用户，如果是，绑定过后，，去掉绑定菜单
		if( $data['centerinfo']['value']=='perfect' ){
			if( $data['centerinfo']['id_card_no'] || $data['centerinfo']['pms_user_id'] ){
				$is_binning = true;
			}else{
				$is_binning = false;
			}
		}else{
			$is_binning = false;
		}
		$data['menu'] = isset($center_config['value'])?$center_config['value']:array();
		foreach ($data['menu'] as $key => $value) {
		    if(isset($data['centerinfo']['is_login']) && $data['centerinfo']['is_login']=='f' && $value['modelname']=='我的电子会员卡') unset($data['menu'][$key]);

			if($data['is_club']==0 && $value['modelname']=='社群客' ){
				unset($data['menu'][$key]);
			}
			if($data['isDistribution']==0 && ($value['modelname']=='全员营销' || $value['modelname']=='分销中心')){
				unset($data['menu'][$key]);
			}

            if($data['isDistribution']==1 && $value['modelname']=='分销注册'){
                unset($data['menu'][$key]);
            }

			if( $is_binning && ($value['modelname']=='会员登录' || $value['modelname']=='会员绑定' || $value['modelname']=='绑定登录' ) ){
				unset($data['menu'][$key]);
			}
		}
        //$this->load->view('member/'.$this->_template.'/center',$data);
		$this->out_put_msg ( 1, '', $data ,'membervip/center');
	}

	//会员卡用户资料
	public function info(){
		$post_config_url = PMS_PATH_URL."adminmember/getmodifyconfig";
		$post_config_data =  array(
			'inter_id'=>$this->inter_id,
			);
		//请求资料信息
		$data['modify_config'] = $this->doCurlPostRequest( $post_config_url , $post_config_data )['data'];
		$post_center_url = PMS_PATH_URL."member/center";
		$post_center_data =  array(
			'inter_id'=>$this->inter_id,
			'openid' =>$this->openid,
			);
		//请求用户登录(默认)会员卡信息
		$data['centerinfo'] = $this->doCurlPostRequest( $post_center_url , $post_center_data )['data'];
		$this->load->view('member/'.$this->_template.'/memberinfo',$data);
	}

    //储值卡二维码页面
    public function qrcode(){
        $data['centerinfo'] = array();
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );
        //请求用户登录(默认)会员卡信息
        $centerinfo = $this->doCurlPostRequest($post_center_url,$post_center_data);
        if(isset($centerinfo['data']) && !empty($centerinfo['data'])){
            $data['centerinfo'] = $centerinfo['data'];
        }
        $this->load->view('member/'.$this->_template.'/qrcode',$data);
    }

	public function remote(){
        if($this->inter_id!='a449675133'){
            $this->index();
            exit;
        }
        //获取appID
          $this->load->library ("MYLOG");
          $appid=$this->db->query('SELECT * FROM `iwide_publics` WHERE `inter_id` LIKE \'a449675133\'')->result_array()['0']['app_id'];
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );
        //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
        $center_data = $this->doCurlPostRequest( $post_center_url , $post_center_data );
        if(!empty($center_data['data']['membership_number'])){
            $url='http://mts.xiezhuwang.com/hotelmaster/firstLookV2?appID='.$appid.'&memberKey='.$center_data['data']['membership_number'];
            header("Location:".$url);
            exit;
        }else {
            $this->index();
            exit;
        }
    }
    
    public function qrcodecon(){
        $this->load->helper ('phpqrcode');
        $url = urldecode($_GET["data"]);
        QRcode::png($url,false,'Q',30,10,true);
    }
}
?>