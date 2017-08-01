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
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/mycss.css");?>" rel="stylesheet">
<title>领取卡券</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
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
        'hideOptionMenu',
        'addCard',
        'chooseCard',
        'openCard'

     ]
   });
   wx.ready(function (){
	   wx.hideOptionMenu();
   });
</script>
<div class="nav">
    <img src="<?php echo base_url("public/member/public/images/978521546876132.jpg");?>"/>
    <div class="p_ab"></div>
</div>
<div class="content">
      <div class="coupon">
        <div class="cou_con">
            <p class="money"><?php if(isset($title)):?>699<?php else:?>30<?php endif;?>元</p>
            <p class="cou_titl"><?php if(isset($title)):?>699元套票券<?php else:?>30元入住送券<?php endif;?></p>
            <p class="cou_text">指定门店可使用</p>
            <p class="cou_time"><?php if(isset($title)):?>2016.06.20<?php else:?>领取后2日内有效<?php endif;?></p>
            <p>1张</p>
              <div class="bg_1"></div>
          </div>
      </div>
</div>
<div class="flooter">
		<?php if(isset($sec)):?><a class="fl_btn" href="javascript:;">您已经领过了</a><?php else:?>
        <a class="fl_btn" href="javascript:getcard();">立即领取</a><?php endif;?>
</div>
<script>

function getcard()
{
      wx.addCard({
      cardList: [{"cardId": "<?php echo $cardid; ?>",
              "cardExt": '{\"code\": \"<?php echo $card_ext["code"]; ?>\", \"openid\": \"<?php echo $openid; ?>\", \"timestamp\": \"<?php echo $card_ext["timestamp"];?>\", \"signature\":\"<?php echo $card_ext["signature"];?>\"}'
          }], // 需要添加的卡券列表
          success: function (res) {
              var cardList = res.cardList; // 添加的卡券列表信息
          }
      });

}
</script>
</body>
</html>
