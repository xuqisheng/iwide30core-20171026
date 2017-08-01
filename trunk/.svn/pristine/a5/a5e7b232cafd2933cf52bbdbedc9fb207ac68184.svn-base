<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="target-densitydpi=device-dpi,width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
  <title>微信安全支付</title>

	
</head>
<body>
	</br></br></br></br>
	<div align="center">
	</div>
	<input type='hidden' id='prevent' value='0' />
    <button data-theme="b" id="submit" type="submit">Submit</button>
</body>
<script src="http://tianyangpay.com/weixin/Public/js/jquery.js"></script>
<script type="text/javascript">
    function onBridgeReady() {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest', {
                "appId" : '<?php echo $jsApiParameters['appId'];?>',     //公众号名称，由商户传入
                "timeStamp": '<?php echo $jsApiParameters['timeStamp'];?>',         //时间戳，自1970年以来的秒数
                "nonceStr" : '<?php echo $jsApiParameters['nonceStr'];?>', //随机串
                "package" : '<?php echo $jsApiParameters['package'];?>',
                "signType" : '<?php echo $jsApiParameters['signType'];?>',         //微信签名方式:
                "paySign" : '<?php echo $jsApiParameters['paySign'];?>'    //微信签名
            },
            function (res) {
                WeixinJSBridge.log(res.err_msg);
                if (res.err_msg == "get_brand_wcpay_request:ok") {
                    location.href = '<?php echo $returnUrl;?>';
                    // WeixinJSBridge.call('closeWindow');
                }else if(res.err_msg == "get_brand_wcpay_request:fail"){
                    alert("支付失败");
                }    // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
            }
        );
    }


    function pay(data) {

        if (typeof WeixinJSBridge == "undefined") {
            if (document.addEventListener) {
                document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
            } else if (document.attachEvent) {
                document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
            }
        } else {
            onBridgeReady(data);
        }

    }

</script>
<script>
var str = '<?php echo $jsApiParameters['appId'];?>'; 
alert(str);
   // var obj = eval('['+str+']');
    $("#submit").click( function () {
        pay();
    });
</script>
</html>