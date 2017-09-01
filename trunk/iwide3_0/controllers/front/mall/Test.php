<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type:text/html;charset=utf-8");
if(ENVIRONMENT=='development') error_reporting(E_ALL);

class Test extends MY_Front {

    public function consume()
    {
        error_reporting(E_ALL);
        $code= 'C15Q448370172849';
        $openid= 'o9Vbtw5bgFCel1nuSugUG4uVVZ3k';
        $inter_id= 'a450089706';
        $this->load->model('mall/shp_orders');
        //$result= $this->shp_orders->qr_consumer($code, $openid, $inter_id, 727 );
        //print_r($result);
        $this->load->helper('encrypt');
        $encrypt_util= new Encrypt();
        $token= $encrypt_util->encrypt($openid. date('YmdH') );
        $callback= EA_const_url::inst()->get_url('*/handle/consume_callback', array('id'=> $inter_id ));
        echo <<<EOF
<script src="http://credit.iwide.cn/public/mall/multi/script/jquery.js"></script>
<script>
//alert('start ajax.');
$.post('{$callback}', {'code':'{$code}',openid:'{$openid}','t':'{$token}'}, function(r){
    //alert(' ajax success.');
	if(r.status==1){
		alert(r.message);
	} else {
		alert(r.message);
	}
}, 'json');
//alert('end ajax.');
</script>W
EOF
;
    }

    public function qr()
    {
        $this->load->model('mall/shp_orders');
        $inter_id= $this->inter_id;
        $orderid= 'C15U888888999999';
        $itemid= NULL;
        $newid= $this->shp_orders->qr_order_no($orderid, $itemid);
        $base_path= 'qrcode/mall/wap/pay_success';
        $url= $this->_get_qrcode_png($orderid, "{$inter_id}_{$orderid}_{$newid}", 5, 1, $base_path);
        echo $url;
    }

    public function code()
    {
        $this->load->model('mall/shp_orders');
        $orders= $this->shp_orders->find_all(array());
        foreach ($orders as $v){
            die;
            //$orderid= 'C15U945950909886';
            $orderid= $v['out_trade_no'];
echo '<p>';
            $itemid= rand(11, 99999);
            echo $orderid. $itemid;
echo '<br/>';
            echo $newid= $this->shp_orders->qr_order_no($orderid, $itemid);
echo '<br/>';
            echo $this->shp_orders->qr_order_no($newid, $itemid, 'de');
echo '<br/>';
            echo $newid= $this->shp_orders->qr_order_no_splite($newid);
            //if($orderid!= $newid) echo "----";
echo '</p>';
        }
    }
    
    public function t1()
    {
        /**
         * 测试接口地址：http://test.weixin.bestdo.com/thirdparty/userInfo
         * 加密key：Kj6i1qRL5R157fnNc86UEh1s
         * $openid= 'o_y_Rt2TPrkV-nWDmJ2vZKaHx5Fg';
         *
         * 正式接口地址：http://weixin.bestdo.com/thirdparty/userInfo
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
         *
         * 400   请求数据为空
         * 401   解密失败
         * 402   无查询数据
         * 200   执行成功
         */
        $url= 'http://test.weixin.bestdo.com/thirdparty/userInfo';
        $openid= 'o_y_Rt2TPrkV-nWDmJ2vZKaHx5Fg';

        require_once './encrypt.php';
        $encry= new Encrypt('Kj6i1qRL5R157fnNc86UEh1s');

        $this->load->helper('common');
        $value= urlencode($encry->encrypt($openid));
        $result= json_decode(doCurlPostRequest($url, 'openid='. $value));
        //print_r($result);
        
        if( isset($result->code) ) {
            if( $result->code =='200' ){
                $data= $result->data;
                
                $data= $encry->decrypt($data);
                print_r($data);

            } else {
                echo "错误代码[{$result->code}]：". $result->data;
            }
            
        } else {
            echo '接口查询失败';
        }
        
    }
    
    
    
}