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
<meta name="viewport" content="width=320.1,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">

<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/carddt.css");?>" rel="stylesheet">
<title>卡券详情</title>
</head>
<!--div class="headers"> 
  <div class="headerslide">
  	  <a class="slideson imgshow relative" href="#">
     	 <img src="<?php echo $detail->logo_url;?>">
      </a>
  </div>
</div>
<div class="detail">
	<div class="goods_name"><?php echo $detail->title;?></div>
    <div class=" ui_border erweima">
    	<div>卡号： <?php echo $card->code;?>
    	<?php if(isset($card->wxcard) && $card->wxcard) {?>
    	     <div>微信卡券</div>
    	<?php } elseif(isset($card->addpackage) && $card->addpackage) {?>
    	    <a href="<?php echo base_url("index.php/member/wxgetcard/addpackage?card_id=".$detail->card_id."&code=".$card->code);?>">放到微信卡包</a>
    	<?php } ?></div>
    </div>
    <div class="how_use">
    	<?php echo $detail->description;?>
    </div>
</div-->
<body>
<div class="fixe">
	<div class="er_log">
		<div class="itemimg"><img src="<?php echo $detail->logo_url;?>"></div>
    	<div class="back" onClick="history.back(-1)">&times;</div>
        <div class="hotelname"><?php echo $public['name'];?></div>
        <div class="votename"><?php echo $detail->title?></div>
		<?php if(isset($card->wxcard) && $card->wxcard) {?>
		    <div style="margin-top:2%;">微信卡券</div>
		<?php } elseif(isset($card->addpackage) && $card->addpackage) {?>
		    <a class="addto" style="margin:2%;" href="<?php echo base_url("index.php/member/wxgetcard/addpackage?card_id=".$detail->card_id."&code=".$card->code);?>">添加到微信卡包</a>
		<?php } ?>
        <div class="valuetime">有效期至<?php echo date('Y月m月d日',$detail->date_info_end_timestamp);?></div>
    </div>
    <img style="margin:-2px 0;" src="<?php echo base_url("public/member/public/images/fen_bg_1.png");?>">
    <div class="erwen_b">
    	<div class="saoma"><img src="<?php echo base_url("index.php/member/qrcodecon?data=").$card->code;?>"></div>
    	<div style="text-align:center; background:#fff; padding:3% 0">卡号：<?php echo $card->code;?></div>
        <div class="detail rowbtn">优惠券详情</div>
        <!--hr><div class="rowbtn">立即使用</div-->
    </div>
</div>
<div class="pull detail_pull" style="display:none">
<?php echo $detail->description;?>
</div>
</body>
<script>
$(function(){
	$('.detail').click(function(){
		toshow($('.detail_pull'));
	})
	$('.detail_pull').click(toclose);
	
})
</script>
</html>