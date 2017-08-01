<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户中心
*	@author  Frandon
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Myqrcode extends MY_Front
{
    public function index(){
        $this->load->library("MYLOG");
        $this->load->helper('phpqrcode');
        $code = !empty($_GET["mkeycode"])?$_GET["mkeycode"]:'';
        $url = site_url('membervip/invitate/register').'?id='.$this->inter_id.'&mkeycode='.$code;
        MYLOG::w($url,'membervip/invitate','qrcode');
        QRcode::png($url,false,'L',6,0,true);
    }
}
?>