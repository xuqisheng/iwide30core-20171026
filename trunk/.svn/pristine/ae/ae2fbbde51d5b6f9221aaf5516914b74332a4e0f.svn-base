<?php
use App\services\member\CenterService;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户中心
*	@author  Frandon、liwensong
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Center extends MY_Front_Member
{
	//会员卡用户中心
	public function index(){
	    $skins = array(
	        'phase2',
            'yinzuo',
            'zhouji',
            'yasite',
            'highclass',
            'changchun',
            'green'
        );
	    if($this->inter_id=='a450089706' || in_array($this->_template,$skins))
	        redirect('membervip/center/member_center?id='.$this->inter_id);

        $data['data'] = array();
        if(!$this->is_restful()){
            $data = CenterService::getInstance()->index($this->inter_id,$this->openid,$this->_template_filed_names);
        }
        $this->template_show('member',$this->_template,'center',$data);
    }


    //会员卡用户中心
    public function member_center(){
        $data['data'] = array();
        if(!$this->is_restful()){
            $data = CenterService::getInstance()->member_center($this->inter_id,$this->openid,$this->_template_filed_names,false);
        }
        if ($this->inter_id=='a476756979'){
            $this->_template='yinzuo';//银座测试用强制转换皮肤
        }
        $this->template_show('member',$this->_template,'center_new',$data['data']);
    }
	//会员卡用户资料
	public function info(){
        $data['data'] = array();
        if(!$this->is_restful()){
            $data = CenterService::getInstance()->info($this->inter_id,$this->openid,'template');
        }
        $this->template_show('member',$this->_template,'memberinfo',$data['data']);
    }

    //储值卡二维码页面
    public function qrcode(){
        $data['data'] = array();
        if(!$this->is_restful()){
            $data = CenterService::getInstance()->qrcode($this->inter_id,$this->openid,'template');
        }
        $this->template_show('member',$this->_template,'qrcode',$data['data']);
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
        $margin = isset($_GET['margin']) ? $_GET['margin']:10;
        QRcode::png($url,false,'Q',30,$margin,true);
    }

    public function soma(){
        $this->load->model('membervip/front/Member_model','mem');
        $user = $this->mem->get_user_info($this->inter_id,$this->openid,'member_mode,is_login');
        if(empty($user) || $user['member_mode']=='1' || $user['is_login']=='f'){
            redirect('membervip/login').'?id='.$this->inter_id;
        }elseif ($user['is_login']=='t'){
            redirect('http://junting.hfmc99.com/index.php/soma/package/index').'?id='.$this->inter_id;
        }
        redirect(site_url('membervip/center').'?id='.$this->inter_id);
    }

    public function bgywechat(){
        $this->load->model('membervip/front/Member_model','m_model');
        $this->load->library("MYLOG");
        $user = $this->m_model->get_user_info($this->inter_id,$this->openid);
        MYLOG::w(json_encode(array('res'=>$user)),'front/membervip/center','bgywechat');
        if(empty($user)) redirect(site_url('membervip/center').'?id='.$this->inter_id);
        if($user['member_mode']==1) redirect(site_url('membervip/login').'?id='.$this->inter_id);
        $jumpurl = "http://h5.bgyhotel.com:9090/m/h5/wechat?icNum={$user['membership_number']}&mobile={$user['telephone']}&openId={$user['open_id']}";
        MYLOG::w(json_encode(array('jumpurl'=>$jumpurl)),'front/membervip/center','bgywechat');
        redirect($jumpurl);
    }
	
	
	public function showopenid(){
		
		
		echo $this->openid;
	}
}
?>