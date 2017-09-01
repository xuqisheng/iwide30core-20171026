<!doctype html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<title>余额充值</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
#pinfo ul,#pinfo li{overflow:hidden; padding:3% 0;}
#pinfo li{ width:15%; margin-left:3%; text-align:center; border-radius:0.2rem;  margin-bottom:3%;border:1px solid #e4e4e4; display:inline-block; background:#fff; white-space:nowrap; text-overflow:ellipsis;}
#pinfo li:after{content:"元"}
#pinfo li.iscur{color:#ff7200; border-color:#ff7200}
-->
</style>
<body>
<script>
wx.config({
    debug:false,
    appId:'<?php echo $signpackage["appId"];?>',
    timestamp:<?php echo $signpackage["timestamp"];?>,
    nonceStr:'<?php echo $signpackage["nonceStr"];?>',
    signature:'<?php echo $signpackage["signature"];?>',
    jsApiList: [
        'hideOptionMenu'
     ]
   });
   wx.ready(function () {
	   wx.hideOptionMenu();
   });
</script>
<form id="pinfo" action="<?php echo $request_url;?>" method="post">
    <?php if(empty($charge_amount)) {?>
    <div class="ui_normal_list ui_border">
        <div class="item">
            <input name="total_fee" type="text" placeholder="请输入充值金额" value="">
        </div>
    </div>
    <?php } else { ?>
    <div style="padding:3%; padding-bottom:0; color:#666;">请选择充值金额</div>
    <ul>
    <?php foreach($charge_amount as $amount=>$add) {?>
    	<li><?php echo $amount;?></li>
    <?php } ?>
    </ul>
    <input id="" name="total_fee" type="hidden" value="">
    <?php } ?>
<input type="hidden" name="openid" value="<?php echo $iwide_openid;?>" />
<input type="hidden" name="out_trade_no" value="" />
<input type="hidden" name="body" value="用户充值" />
<input type="hidden" name="notify_url" value="<?php echo $notify_url;?>" />
<input type="hidden" name="success_url" value="<?php echo $success_url;?>" />
<input type="hidden" name="fail_url" value="<?php echo $fail_url;?>" />
<input type="hidden" name="<?php echo $token_name;?>" value="<?php echo $token_value;?>" />
<input id="sub" class="ui_foot_btn" type="button" value="马上充值">
</form>
<script>
$(document).ready(function(){
   $("#sub").click(function() {
       if($(":input[name=total_fee]").val().length==0) {
           alert("请输入充值金额!");
           return false;
       }
       $.post("<?php echo site_url("member/ccharge/createorder");?>", {amount:$(":input[name=total_fee]").val()},
           function(data) {
    	       data = eval('(' + data + ')');
          	   if(data.result) {
            	   $("input[name='out_trade_no']").val(data.order_number);
              	   $("#pinfo").submit();
          	   } else {
              	   alert("创建订单失败!");
          	   }
       });
   });
   $("#pinfo li").click(function(){
	   $(this).addClass('iscur').siblings().removeClass('iscur');
	   $('input[name=total_fee]').val($(this).html());
   })
   $("#pinfo li").eq(0).trigger('click');
});
</script>
</body>
</html>
