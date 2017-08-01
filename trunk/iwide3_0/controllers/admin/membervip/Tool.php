<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台优惠券
*	@author Frandon
*	@time 四月十一号
*	@version www.iwide.cn
*	@
*/
class Tool extends CI_Controller
{
    /*qrc生成*/
    public function qrc(){
        $str = $this->input->get("str");
        $this->load->helper ('phpqrcode');
        $url = urldecode($str);
        $margin = isset($_GET['margin']) ? $_GET['margin']:0;
        QRcode::png($url,false,'Q',30,$margin,true);
    }

    public function scanqr(){
        $str = $this->input->get("str");
        $fc = $this->input->get('fc');
        $this->load->helper ('phpqrcode');
        $str .= "&fc={$fc}";
        $url = urldecode($str);
        $margin = isset($_GET['margin']) ? $_GET['margin']:0;
        QRcode::png($url,false,'L',50,$margin,true);
    }
}
?>