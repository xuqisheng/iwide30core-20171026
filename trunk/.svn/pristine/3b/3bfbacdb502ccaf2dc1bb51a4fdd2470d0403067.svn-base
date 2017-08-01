<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	二维码中心
*	@author  liwenesong
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 171708252@qq.com
*/
class Myqrcode extends MY_Front
{
    public function index(){
        $this->load->library("MYLOG");
        $this->load->helper('phpqrcode');
        $code = !empty($_GET["share_key"])?$_GET["share_key"]:'';
        $url = site_url('membervip/invitate/register').'?id='.$this->inter_id.'&share_key='.$code;
        MYLOG::w($url,'membervip/invitate','qrcode');
        QRcode::png($url,false,'L',6,0,true);
    }
}
?>