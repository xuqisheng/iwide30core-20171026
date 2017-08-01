<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,initial-scale=1,user-scalable=0">
<title>会员中心</title>
<link rel="stylesheet" href="<?php echo base_url("public/member/styles/global.css");?>"/>
<link rel="stylesheet" href="<?php echo base_url("public/member/junting/styles/junting.css");?>"/>

<script src="<?php echo base_url("public/member/scripts/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/scripts/ui_control.js");?>"></script>
<script src="<?php echo base_url("public/member/scripts/alert.js");?>"></script>
</head>
<body>
<style>
body,html{background:#f8f8f8}
</style>


<?php if($centerinfo){?>
	<?php if ($centerinfo['value'] == "login" && $centerinfo['member_mode'] == 1) {?>
	<div class="txt_r top_btn absolute" style="font-size:0 z-index:2">
    <a class="color_555 h26" href="<?php echo base_url("index.php/membervip/login");?>">登录</a>
    <a class="color_555 h26" href="<?php echo base_url("index.php/membervip/reg");?>">注册</a>
</div>
<a style="padding:20px 10px 15px 10px; display:block" class="center bg_fff bd_bottom" href="<?php //echo base_url("index.php/membervip/center/info"); ?>">
	<img style="width:auto; height:60px; border-radius:50%" src="<?php echo $info['headimgurl']; ?>"> <!-- 头像 -->
    <p class="pad3 h32">
	<?php if ($centerinfo['name'] == '微信用户' || $centerinfo['name'] == '') { ?>
		<?php echo $centerinfo['nickname']; ?>
    <?php } else { ?>
        <?php echo $centerinfo['name']; ?>
    <?php } ?>
    </p>
    <p class="color_main h24">
<?php echo $centerinfo['lvl_name']  ; ?>
  
    </p>
</a>
<?php }else {?>

<div class="center bg_fff bd_bottom cardimg" style="background-image:url(<?php echo $centerinfo['lvl_pms_code']==',VIP2,' ?  base_url("public/member/junting/image/vip2.png"):base_url("public/member/junting/image/vip1.png")    ?>);"> <!-- 卡片尺寸 660*320 -->
	<p class="color_main h22">   <?php echo $centerinfo['membership_number'];?></p>
</div>
<?php }?>
<?php }?>
<div class="webkitbox center pad3 bg_fff">
	<a href="<?php echo base_url("index.php/membervip/bonus"); ?>">
    	<p class="color_key h32"><?php if ($centerinfo['value'] == "login" && $centerinfo['member_mode'] == 1) { ?>
                    --
                <?php } else { ?>
                    <?php echo $centerinfo['credit'] ?>
                <?php } ?></p>
        <p class="h24"><img class="iconimg" src="<?php echo base_url("public/member/junting/icon/ico01.png");?>"> 积分</p>
    </a>
	<a href="<?php echo base_url("index.php/membervip/card"); ?>" class="bd_left">
    	<p class="color_key h32"><?php echo $centerinfo['card_count'] ?></p>
        <p class="h24"><img class="iconimg" src="<?php echo base_url("public/member/junting/icon/ico02.png");?>"> 优惠券</p>
    </a>
</div>
<div class="domain_con martop flex flexgrow flexwrap h24 center">


<?php foreach ($menu as $key =>$val){?>

 <?php if($centerinfo['member_mode']==2 && $centerinfo['is_login'] == 'f' ||$centerinfo['member_mode']==1 && $centerinfo['value']=="login" ){?>
    <a class="bg_fff color_999" href="<?php echo base_url("index.php/membervip/login");?>">
    <?php }else {?>
        <a class="bg_fff color_999" href="<?php echo $val['link']?>">
        <?php }?>
        <div class="square bd_bottom bd_right">
            <div class="centerbox flex flexrow flexjustify">
                <p class="ico_img <?php echo $val['ico']?>"></p>
                
                <p><?php echo $val['modelname']?></p>
            </div>
        </div>
    </a>
    
<?php }?>
    
</div>

</body>
</html>
