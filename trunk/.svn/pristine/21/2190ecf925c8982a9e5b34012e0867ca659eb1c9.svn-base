<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Book extends MY_Front {
    public  $themeConfig;
    public  $theme = 'revision';//皮肤 改版
    public $openid;
    public $module;
    //protected $_token;
	function __construct() {
		parent::__construct ();
        $this->inter_id = $this->session->userdata ( 'inter_id' );
        $this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
        //统计探针
        $this->load->library('MYLOG');
        MYLOG::distribute_tracker($this->session->userdata ( $this->inter_id . 'openid' ),   $this->session->userdata ( 'inter_id' ));

        $this->load->model('wx/Access_token_model');
        $this->common_data = $this->Access_token_model->getSignPackage($this->inter_id);
    }

    /**
     * 商品列表
     */
    public function goods_list()
    {
        $data = array();

        $this->display('ticket/index',$data,$this->theme);
    }

    /**
     * 商品详情
     */
    public function goods_detail()
    {
        $data = array();
        $this->display('ticket/detail',$data,$this->theme);
    }

    /**
     * 我的购物车列表
     */
    public function cart_list()
    {
        $data = array();
        $this->display('ticket/cart',$data,$this->theme);
    }

    /**
     * 结算界面
     */
    public function checkout()
    {
        $data = array();
        $this->display('ticket/order',$data,$this->theme);
    }

    /**
     * 订单中心
     */
    public function order_list()
    {
        $data = array();
        $this->display('ticket/orderList',$data,$this->theme);
    }


    /**
     * 订单详情
     */
    public function order_detail()
    {
        $data = array();
        $this->display('ticket/orderDetail',$data,$this->theme);
    }

}