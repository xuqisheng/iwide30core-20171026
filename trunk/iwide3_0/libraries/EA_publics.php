<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

/**
 * 合作（非托管）公众号信息查询
 * @author libinyan
 */
class EA_publics extends EA_base
{
    public $session;
    
	public function __construct()
	{
	     return parent::__construct();
	}

	public static function inst($className=__CLASS__)
	{
		return parent::inst($className);
	}
	
    /**
     * 合作商家百动网
     * @param unknown $openid
     */
	static public function baidong($openid, $field=NULL)
	{
	    /**
	     * 正式接口地址：http://weixin.bestdo.com/thirdparty/userInfo
	     * 测试接口地址：http://test.weixin.bestdo.com/thirdparty/userInfo
	     * 加密key：Kj6i1qRL5R157fnNc86UEh1s
	     * $openid= 'o_y_Rt2TPrkV-nWDmJ2vZKaHx5Fg';
	     *
	     * 成功时输出示例：
	     * {"code":"200","data":"Tmk5pontemC\/SxZ7M79XS0Nwz81zcIu75\/19WAK1G+GvqwlEzuz8xOeZnN6t9+X9ewUN3PVC\/4AjFrVkQc1LsmwzfypBmygTNrODpKZ37u2PkKFoOxsDKzYCMBrvpdORzfHFHNlK4x0Fn8T\/RpDKA5rVtUB5\/W6Fi6kQSZqBr6dIIevJasIyB1hhOHn7Q\/e56BqStta5ZZMHV1qukiX8GhO0O6Eqqz\/x2QXvL1LRKOI6gs11ObVgkcpwWPev\/AEo6Da961CYZmaSdtHz8+IreW0Z5UcsSBElT77pLxiRjQt5HavwoSwWWk5d0EvMSjxyvou6YUARyOcj+qstHj0d2K2Hl05y4pC5clBj2BAkKCKt9Dp8lL32GF6sMg7WbVJYFF8abq359E4bwGfsffsMaxlHGJEjGy6COYDWz4YkJ\/XSjLgS6t0nmudcO2LsTnRKBjOtY+HeUyPVKCAvErn1xA=="}
	     * { 解密后数据格式
	     *     "openid":"o_y_Rt2TPrkV-nWDmJ2vZKaHx5Fg",
	     *     "accessToken":"Aim9JQCNu4i6Y4zoCIB_WRYj62ppZKrOegqA-gSh_ZcQSyo1zVxbahbeGK_soW5SECF-TdqvQCcHG_29myJyQEIw3tp8ghmNzl3IEJGQEUAADIiABADGL",
	     *     "userNick":"dozer",
	     *     "userHeadImgUrl":"http:\/\/wx.qlogo.cn\/mmopen\/OZ2ot2edg4jqz3PAAFIJw9BzI5P85dOLumpiacedatOs8fKXnpLfIUson1ibib7amRwEflgBxlEK9Nym7BRCFic8qNibNTt33g2ko\/0"
	     * }
	     * 失败时输出示例：
	     * {"code":"400","data":"\u8bf7\u6c42\u6570\u636e\u4e0d\u80fd\u4e3a\u7a7a"}
	     * 400   请求数据为空
	     * 401   解密失败
	     * 402   无查询数据
	     * 200   执行成功
	     */
	    $url= 'http://test.weixin.bestdo.com/thirdparty/userInfo';
	    $this->load->helper('common');
	    $this->load->helper('encrypt');
	    $encry= new Encrypt('Kj6i1qRL5R157fnNc86UEh1s');
	    
	    $openid= urlencode($encry->encrypt($openid));
	    $result= json_decode(doCurlPostRequest($url, 'openid='. $openid));
	    //print_r($result);
	    
	    if( isset($result->code) ) {
	        if( $result->code =='200' ){
	            $data= $encry->decrypt($result->data);
	            //print_r($data);
	            if( $field==NULL ){
	                return $data;
	                
	            } else if(isset($data[$field])){
	                return $data[$field];
	                
	            } else 
	                return '';
	    
	        } else {
	            //echo "错误代码[{$result->code}]：". $result->data;
	            return NULL;
	        }
	    
        } else {
	        //echo '接口查询失败';
	        return FALSE;
	    }
	}
    
}