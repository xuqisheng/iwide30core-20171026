<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends MY_Front_Soma {

    public  $themeConfig;
    public $theme = 'default';

    public function __construct()
    {
        parent::__construct();
        //theme
        $this->load->model('soma/Theme_config_model');
        $this->themeConfig = $themeConfig = $this->Theme_config_model->get_using_theme($this->inter_id);
        $this->theme = $themeConfig['theme_path'];
    }

    public function ucenter()
    {
        $fans = array();
        //获取微信用户的信息
        $fans= $this->_get_wx_userinfo();
        $inter_id = $this->inter_id;
        
        $header = array(
            'title' => '用户中心',
        );

        $this->datas = array();
        $this->datas['fans'] = $fans;
        $this->datas['headimgurl'] = isset($fans['headimgurl'])? $fans['headimgurl']: base_url('public/soma/images/ucenter_headimg.jpg');
        $this->datas['nickname'] = isset($fans['nickname'])? $fans['nickname']: '昵称';

        //商城首页
        $this->datas['home_url'] = Soma_const_url::inst()->get_url( '*/package/index', array( 'id'=>$inter_id ) );
        
        //全部订单
        $this->datas['order_url'] = Soma_const_url::inst()->get_url( '*/order/my_order_list', array( 'id'=>$inter_id ) );

        //邮寄商品
        $this->datas['shipping_url'] = Soma_const_url::inst()->get_url( '*/consumer/my_shipping_list', array( 'id'=>$inter_id ) );

        //赠送商品
        $this->datas['gift_url'] = Soma_const_url::inst()->get_url( '*/gift/package_list_send', array( 'id'=>$inter_id ) );

        $this->_view("header",$header);
        $this->_view("ucenter", $this->datas);
    }
    
    //展示为以后的皮肤做扩展
    protected function _view($file, $datas=array() )
    {
//        parent::_view('asset'. DS. $file, $datas);
        parent::_view('asset'. DS. $file, $datas);
    }
    
    
}
