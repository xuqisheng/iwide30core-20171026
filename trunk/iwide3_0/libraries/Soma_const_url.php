<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Soma_const_url extends Soma_base
{
	public function __construct()
	{
	     return parent::__construct();
	}

	public static function inst($className=__CLASS__)
	{
		return parent::inst($className);
	}
	
	public function add_essential_param($param)
	{
	    if( WEB_AREA=='front' ){
	        $CI = & get_instance();
	        // $inter_id = $CI->input->get('id');
	        $inter_id= substr ( $CI->input->get('id', true), 0, 10 );
	        if($inter_id) $param['id']= $inter_id;
	        
	        $saler_id= $CI->input->get('saler', true);
	        if($saler_id) $param['saler']= $saler_id;
	        
	        $fans_id= $CI->input->get('fans', true);
	        if($fans_id) $param['fans']= $fans_id;
	        
	        $fans_saler_id= $CI->input->get('fans_saler', true);
	        if($fans_saler_id) $param['fans_saler']= $fans_saler_id;

            $zbcode = $CI->input->get('zbcode', true);
            if($zbcode) $param['zbcode'] = $zbcode;

            $channelid = $CI->input->get('channelid', true);
            if($channelid) $param['channelid'] = $channelid;
	        
            $zburl = $CI->input->get('zburl', true);
            if($zburl) $param['zburl'] = $zburl;

            $rel_res = $CI->input->get('rel_res', true);
            if($rel_res) $param['rel_res'] = $rel_res;

	    } elseif( WEB_AREA=='admin' ){
	        //$CI = & get_instance();
	        
	    }
	    return $param;
	}

	public function get_share_url($openid, $route, $param=array(), $token=FALSE )
	{
	    $r= $this->get_url($route, $param, $token );
	    return $this->get_url('soma/gift/sharing_received', array(
	        'id'=> $param['id'],
	        'r'=> urlencode($r),
	        's'=> urlencode($openid),
	    ), $token);
	}
	/**
	 * 
	 * echo Soma_const_url::inst()->get_url('* / * /post', array('id'=>18, ), TRUE))
	 * @param string $route
	 * @param array $param
	 * @param bool $token
	 * @return string
	 */
	public function get_url($route, $param=array(), $token=FALSE )
	{
	    $URI =& load_class('URI', 'core', NULL);
        //自动追加传递参数
	    $param= $this->add_essential_param($param);
	    
	    $segments= $URI->segments;
	    $module= $segments[1];
	    $controller= isset($segments[2])? $segments[2]: 'index';
	    $action= isset($segments[3])? $segments[3]: 'index';
	    
	    $routeArr = explode('/', $route);
	    if(isset($routeArr[0]) && $routeArr[0]=='*') $routeArr[0]= $module;
	    if(isset($routeArr[1]) && $routeArr[1]=='*') $routeArr[1]= $controller;
	    if(isset($routeArr[2]) && $routeArr[2]=='*') $routeArr[2]= $action;
	    $path= implode('/', $routeArr);
	    
	    $pam= '?';
	    if( is_array($param) ) {
	        foreach ($param as $k=>$v){
	            $pam.= $k. '='. $v. '&';
	        }
	        if($token){
	            $token_name= config_item('token_filter_name');
	            $pam.= $token_name. '='. do_hash($action);
	            
	        } else {
	            $pam= substr($pam, 0, -1);
	        }
	    } else {
	        $pam.= $param;
	    }
	    return site_url(). '/'. $path. $pam;    //http://domian.com/index.php/pppp
	    //return base_url(). $path. $pam;    //http://domian.com/pppp
	}

	public function get_front_url($inter_id, $route, $param=array(), $domain=NULL )
	{
	    $pam= '?';
	    if( is_array($param) ) {
	        foreach ($param as $k=>$v){
	            $pam.= $k. '='. $v. '&';
	        }
	        $pam= substr($pam, 0, -1);
	    } else {
	        $pam.= $param;
	    }

	    if($domain=='file'){
	    	$domain= file_site_url();

	    } elseif($domain=='front'){
	    	$domain= front_site_url($inter_id);

	    } else {
	    	$domain= front_site_url($inter_id);
	    }
	    return $domain. '/index.php/'. $route. $pam;
	}
	
	public function get_base_url($p=NULL)
	{
	    /* const defind in @see MY_Model */
	    if($p=='image') return URL_IMG;
	    else if($p=='css') return URL_CSS;
	    else if($p=='js') return URL_JS;
		else 
		    return site_url();    //http://domian.com/index.php/pppp
		    //return base_url();    //http://domian.com/pppp
	}
	

	//统一业务类型数组输出
	public function get_business_type()
	{
	    $CI = & get_instance();
	    $CI->load->model('soma/ticket_center_model');
	    $business_type= $CI->ticket_center_model->get_soma_business_types();
	    return $business_type;
	}
	
	/** ***     front end url define     *** **/
	
	//根据业务类型进行成功/失败地址跳转
	public function get_payment_success($business, $param= array())
	{
	    $type= $this->get_business_type();
	    if( array_key_exists($business, $type) ){
	        $method= "get_payment_{$business}_success";
	        return $this->$method($param);
	    }
	    return '';
	}
	public function get_payment_fail($business, $param= array())
	{
	    $type= $this->get_business_type();
	    if( array_key_exists($business, $type) ){
	        $method= "get_payment_{$business}_fail";
	        return $this->$method($param);
	    }
	    return '';
	}

	public function get_payment_return()
	{
	    return site_url(). '/soma/payment/wxpay_return';
	}

	public function get_wft_payment_return()
	{
	    return site_url(). '/soma/payment/wftpay_return';
	}
	
    public function get_pacakge_home_page($param=array()){
        return $this->get_url('soma/package/', $param);
    }

	public function get_payment_package_success($param= array())
	{
	    return $this->get_url('soma/package/success', $param);
	}
	public function get_payment_package_fail($param= array())
	{
	    return $this->get_url('soma/package/fail', $param);
	}

    public function get_package_nearby_ajax($param=array()){
        return $this->get_url('soma/package/get_packages_nearby', $param);
    }

    public function get_package_detail($param=array())
    {
        return $this->get_url('soma/package/package_detail', $param);
    }
    
	public function get_killsec_detail($param=array())
    {
        return $this->get_url('soma/killsec/package_detail', $param);
    }

    //套票支付页面
    public function get_package_pay($param=array()){
        return $this->get_url('soma/package/package_pay', $param);
    }

    //支付生成订单地址AJAX
    public function get_prepay_order_ajax($param=array()){
        return $this->get_url('soma/order/get_order_id_by_ajax', $param);
    }

    //支付拉起
    public function go_to_pay($param=array()){
        return $this->get_url('wxpay/soma_pay', $param);
    }

    //支付拉起
    public function wft_go_to_pay($param=array()){
        return $this->get_url('Wftpay/soma_pay', $param);
    }

    public function get_category($param=array()){
        return $this->get_url('soma/package/category_list', $param);
    }

    public function get_groupon_first_pay($param=array()){
        return $this->get_url('soma/package/groupon_pay', $param);
    }

    //订单列表
    public function get_soma_order_list($param=array()){
        return $this->get_url('soma/order/my_order_list', $param);
    }

    //订单中心
    public function get_soma_ucenter($param=array()){
        // return $this->get_url('soma/asset/ucenter', $param);
        return $this->get_url('soma/order/my_order_list', $param);
    }

    //订单详情
    public function get_soma_order_detail($param=array()){
        return $this->get_url('soma/order/order_detail', $param);
    }
    //细单详情
    public function get_soma_order_item_detail($param=array()){
        return $this->get_url('soma/order/order_item_detail', $param);
    }
    //预约
    public function get_soma_order_booking($param=array()){
        return $this->get_url('soma/order/order_item_booking', $param);
    }

    //退款链接
    public function get_soma_refund_apply($param=array()){
        return $this->get_url('soma/refund/apply', $param);
    }

    //退款详情页 $param = array('b'=>$business,'id'=>$inter_id,'openid'=>$openid,'order_id' => $order_id)
    public function get_soma_refund_detail($param=array()){
        return $this->get_url('soma/refund/detail', $param);
    }

    //申请退款提交链接 $param = array('b'=>$business,'id'=>$inter_id,'openid'=>$openid,'order_id' => $order_id)
    public function get_soma_refund_apply_post($param=array()){
        return $this->get_url('soma/refund/apply_post', $param);
    }


    //赠送列表
    public function get_soma_gift_list( $tab='send' )
    {
        if($tab=='received'){
            return $this->get_url('soma/gift/package_list_received' );
        } else {
            return $this->get_url('soma/gift/package_list_send' );
        }
    }

    //赠送页面
    public function get_soma_gift_send($param=array()){
        return $this->get_url('soma/gift/package_send', $param);
    }

    //邮寄链接
    public function get_soma_shipping($param=array()){
        // return $this->get_url('soma/consumer/shipping_product_info', $param);
        return $this->get_url('soma/consumer/show_shipping_info', $param);
    }

    //邮寄列表
    public function get_my_mail_list($param=array()){
        // return $this->get_url('soma/consumer/shipping_product_info', $param);
        return $this->get_url('soma/consumer/my_shipping_list', $param);
    }

    //礼物列表
    public function get_my_gift_list($param=array()){
        // return $this->get_url('soma/consumer/shipping_product_info', $param);
        return $this->get_url('soma/gift/my_gift_list', $param);
    }

    //ajax获取市列表
    public function get_citys($param=array()){
    	return $this->get_url( 'soma/region/ajax_get_citys', $param );
    }

    //ajax获取区列表
    public function get_regions($param=array()){
    	return $this->get_url( 'soma/region/ajax_get_regions', $param );
    }
    /** ***     front end url define     *** **/
	
	/**
	 * 前端一般URL参数规范，仅为一般规范，如有不符请按照实际情况
	 * 
	 * id : inter_id
	 * hid : hotel_id
	 * rmid : room_id
	 * cdid : price_code
	 *
     * catid:cat_id
     *
	 * oid : order_id
	 * cid : consumer_id
	 * bid : booking_id
	 * aid : asset_id
	 * gid : gift_id
	 * uid : user_id
	 * 
	 * oiid : order item_id
	 * ciid : consumer item_id
	 * aiid : asset item_id
	 * giid : gift item_id
	 * 
	 * bsn : business
	 * stl : settlement
	 * 
	 * grid : group_id
	 * inid : instance_id
	 * 
	 * arid : address_id
	 * spid : shipping_id
	 * 
	 * 
	 */
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
