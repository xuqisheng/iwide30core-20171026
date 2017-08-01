<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用于处理社交商城对应数据接口
 * @author libinyan@mofly.cn
 */
class Soma_api extends MY_Front {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 直接显示分销二维码
     * @author libinyan@mofly.cn
     */
    public function mk_saler_qrcode()
    {
        $openid= $this->openid;
        $inter_id= $this->inter_id;
        $this->load->model('distribute/Staff_model');
        $staff= $this->Staff_model->get_my_base_info_openid( $inter_id, $openid );
        if( $staff && $staff['qrcode_id'] ){
            if($inter_id){
                if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ) {
                    $domain= 'http://mk2016.iwide.cn';
                } else {
                    $domain= 'http://mooncake.iwide.cn';
                }
                $url= $domain. '/index.php/soma/package/index?id='. $inter_id. '&saler='. $staff['qrcode_id'];
                //echo $url;die;
                $this->_get_qrcode_png($url);
                 
            } else {
                die('URL 格式错误');
            }
        } else {
            die('您还不是分销员');
        }
    }
    public function show_saler_qrcode()
    {
        $openid= $this->openid;
        $inter_id= $this->inter_id;
        $this->load->model('distribute/Staff_model');
        $staff= $this->Staff_model->get_my_base_info_openid( $inter_id, $openid );
        if( $staff && $staff['qrcode_id'] ){
            if($inter_id){
                $url= front_site_url($inter_id). '/index.php/soma/package/index?id='. $inter_id. '&saler='. $staff['qrcode_id'];
                //echo $url;die;
                $this->_get_qrcode_png($url);
                 
            } else {
                die('URL 格式错误');
            }
        } else {
            die('您还不是分销员');
        }
    }
}