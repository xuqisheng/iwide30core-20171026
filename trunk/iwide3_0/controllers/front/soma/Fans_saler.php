<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * class Fans_saler
 * 泛分销控制器
 */
class Fans_saler extends CI_Controller
{
    /**
     * 激活
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function active()
    {
        $header['title']    = '招募令';
        $data['origin_url'] = "http://1.njt3s.com/index.php/soma/package/package_detail?pid=150812&id=a490782373&channel=15338&fans_act=1";
        $base_path = 'soma' . DS . 'default' . DS . 'package' . DS;
        $html = $this->load->view($base_path . 'fans_saler_header', $header, true);
        $html .= $this->load->view($base_path . 'fans_saler_active', $data, true);
        echo $html;
    }

    public function hsl_qrcode()
    {
        $header['title'] = '推荐有礼';
        $data['qr_code'] = 'http://upload.tyxshn.com/qrcode/tVmyaZ9gyf.jpg';
        $base_path = 'soma' . DS . 'default' . DS . 'package' . DS;
        $html = $this->load->view($base_path . 'fans_saler_header', $header, true);
        $html .= $this->load->view($base_path . 'fans_saler_qrcode', $data, true);
        echo $html;
    }
}