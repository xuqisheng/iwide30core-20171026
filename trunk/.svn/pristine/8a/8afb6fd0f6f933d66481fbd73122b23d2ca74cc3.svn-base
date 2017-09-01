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
</body>
<script type="text/javascript">

		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters;?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					if(res.err_msg=='get_brand_wcpay_request:ok'){
						location.href='<?php echo $success_url;?>';
					}
					else{
						location.href='<?php echo $fail_url;?>';
					}
// 					alert(res.err_code+res.err_desc+res.err_msg);
				}
			);
		}

		//function callpay()
		//{
		prevent=document.getElementById('prevent').value;
		if(prevent==0){
			document.getElementById('prevent').value=1;
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
		//}
	</script>
</html>