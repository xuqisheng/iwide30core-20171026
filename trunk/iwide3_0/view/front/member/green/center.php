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
<link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/phase2/styles/mycss.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/phase2/styles/green.css");?>" rel="stylesheet">
<script src="<?php echo base_url("public/member/phase2/scripts/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<title>会员中心</title>
</head>
<body>
<style>body,html{background:#fff}</style>
<div class="heads bg_l_g_fec50f_ffa70a">
<?php if($centerinfo['value']=="login"){ ?>
	<?php if($centerinfo['is_login']=='t' && $centerinfo['member_mode'] ==2 ){ ?>
		<a class="btn_list h26" href="<?php echo base_url("index.php/membervip/perfectinfo");?>">修改资料</a>
	<?php }else{ ?>
    	<div class="btn_list h26">
            <a class="" href="<?php echo base_url("index.php/membervip/login");?>">登录</a> |
            <a class="" href="<?php echo base_url("index.php/membervip/reg");?>">注册</a>
        </div>
	<?php } ?>
<?php } ?>
<?php if($centerinfo['value']=="perfect"){ ?>
		<a class="btn_list color_fff h26" href="<?php echo base_url("index.php/membervip/perfectinfo");?>">完善资料</a>
<?php } ?>
    <div class="img_con bd_bottom">
        <a href="<?php echo base_url("index.php/membervip/center/info");?>"><img class="head_img" src="<?php echo $info['headimgurl'];?>"></a>
        <p class="name"><?php if($centerinfo['name']=='微信用户' || $centerinfo['name']=='' ){echo $centerinfo['nickname'];}else{  echo $centerinfo['name'];}?></p>
        <p class="grade"><?php if($centerinfo['value']=="login" && $centerinfo['member_mode'] ==1 ){ echo '微信粉丝';}else{ echo $centerinfo['lvl_name'];}?> <?php if(isset($centerinfo['membership_number'])) echo $centerinfo['membership_number'];?></p>
    </div>
    <div class="display_flex number_list">
        <a href="<?php echo base_url("index.php/membervip/balance");?>" class="number_con">
            <p><?php if($centerinfo['value']=="login" && $centerinfo['member_mode'] ==1 ){ echo '-';}else{ echo $centerinfo['balance']; } ?></p>
            <p class="color_fff"><?php echo $this->_ci_cached_vars['filed_name']['balance_name'];?></p>
        </a>
        <a href="<?php echo base_url("index.php/membervip/bonus?credit_type=1");?>" class="number_con">
            <p><?php if($centerinfo['value']=="login" && $centerinfo['member_mode'] ==1 ){ echo '-';}else{ echo $centerinfo['credit']; } ?></p>
            <p class="color_fff"><?php echo $this->_ci_cached_vars['filed_name']['credit_name'];?></p>
        </a>
        <a href="<?=!empty($card_url)?$card_url:base_url("index.php/membervip/card")?>" class="number_con">
            <p><?php echo $centerinfo['card_count'] ?></p>
            <p class="color_fff">卡券</p>
        </a>
    </div>
</div>
    <div class="domain_con flex flexgrow flexwrap h24 center">
<?php $modelnames=array('全员营销','分销注册','分销中心','我的凤凰礼卡','快速订房','会员权益','套票订单','客房订单','社群客','我的订单','商城订单','酒店订单','扫码核销');?>
<?php foreach ($menukey as $k => $v) { ?>
<?php foreach ($menu as $key => $value) { ?>
	<?php if($v==$value['group']) { ?>
		<a class="c_9b9b9b " href="<?php
		 if($centerinfo['member_mode']==2 && $centerinfo['is_login'] == 'f' && !in_array($value['modelname'],$modelnames)){
			echo base_url("index.php/membervip/login");}
		 elseif($centerinfo['member_mode']==1 && $centerinfo['value']=="login" && !in_array($value['modelname'],$modelnames)){
			echo base_url("index.php/membervip/login");}
		 else{ echo $value['link'];} ?>">
		<div class="square bd_bottom bd_right">
			<div class="centerbox flex flexrow flexjustify">
            	<p class="ico_img <?php echo $value['ico'] ?>"></p>
				<p><?php echo $value['modelname'] ?></p>
				<?php if($value['modelname']=='会员资料'){ ?>
				<?php if($centerinfo['value']=="login" && $centerinfo['member_mode'] ==1 ){}else{ ?>
                <p>(<?php echo $centerinfo['membership_number']; ?>)</p>
				<?php }  } ?>
			</div>
		</div>
		</a>
	<?php } ?>
<?php } ?>
<?php } ?>

    </div>
<?php if(isset($message)) {?>
<script>
$(function(){
	$.MsgBox.Alert('<?php echo $message;?>');
})
</script>
<?php }?>
</body>
</html>