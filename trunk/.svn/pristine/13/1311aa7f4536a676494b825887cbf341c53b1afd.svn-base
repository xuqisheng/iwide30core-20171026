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
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/index.css");?>" rel="stylesheet">
<title>领取卡券</title>
</head>
<body>
<script>
wx.config({
    debug:false,
    appId:'<?php echo $signpackage["appId"];?>',
    timestamp:<?php echo $signpackage["timestamp"];?>,
    nonceStr:'<?php echo $signpackage["nonceStr"];?>',
    signature:'<?php echo $signpackage["signature"];?>',
    jsApiList: [
        'hideOptionMenu',
        'addCard'
     ]
   });
   wx.ready(function () {
	   wx.hideOptionMenu();
	   wx.addCard({
	       cardList:[
           <?php foreach($cardpackage as $package) {?>
		   {
	           cardId:"<?php echo $package['card_id'];?>",
	           cardExt:'{"code":"<?php echo $package['card_ext']['code'];?>","open_id":"<?php echo $package['card_ext']['openid'];?>","timestamp":"<?php echo $package['card_ext']['timestamp'];?>","nonce_str":"<?php echo $package['card_ext']['nonce_str'];?>","signature":"<?php echo $package['card_ext']['signature'];?>"}'
	       },
	       <?php } ?>
	       ],
	       success: function (res) {
	           var cardList = res.cardList;
	       }
	   });
   });
</script>
</body>
</html>