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
	<?php if( $inter_id!='a465195239'){?>
		<a class="btn_list color_fff h26" href="<?php echo base_url("index.php/membervip/perfectinfo");?>">修改资料</a>
		<?php }?>
	<?php }else{ ?>
        <div class="btn_list h26">
            <a class="color_fff" href="<?php echo base_url("index.php/membervip/login");?>">登录</a> |
            <a class="color_fff" href="<?php echo base_url("index.php/membervip/reg");?>">注册</a>
        </div>
	<?php } ?>
<?php } ?>
<?php if($centerinfo['value']=="perfect"){ ?>
<?php if ($centerinfo['audit']==1){?>
		<a class="btn_list color_fff h26" href="<?php echo base_url("index.php/membervip/perfectinfo");?>">完善资料</a>
		<?php }?>
		<?php if ($centerinfo['audit']!=1){?>
			<a class="btn_list color_fff h26" href="<?php echo base_url("index.php/membervip/verify/member_witch/");?>">提交资料</a>
	<?php }?>
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
        <a href="<?php echo base_url("index.php/membervip/card");?>" class="number_con">
            <p><?php echo $centerinfo['card_count'] ?></p>
            <p class="color_fff">卡券</p>
        </a>
    </div>
</div>
    <div class="domain_con flex flexgrow flexwrap h24 center">

        <?php
        foreach ($menu as  $group_key => $group){
            if(!empty($group)){
                foreach($group as $menu_key => $menu_link){
                    if(!empty($menu_link)){
                        $menuShow[] = $menu_link;
                    }
                }
            }
        }
        ?>
<?php foreach ($menuShow as $key => $value) { ?>
		<a class="c_9b9b9b " href="<?php
		 if( $centerinfo['is_login'] == 'f' && $value['is_login'] == 1 &&  ( isset($centerinfo['value']) && $centerinfo['value'] != 'perfect' ) ){
			echo base_url("index.php/membervip/login");}
		 else{ echo $value['link'];} ?>">
		<div class="square bd_bottom bd_right">
			<div class="centerbox flex flexrow flexjustify">
            	<p class="ico_img <?php if(isset($value['icon'])) echo str_replace("ui_",'',$value['icon']); ?>"></p>
				<p><?php echo $value['modelname'] ?></p>
			</div>
		</div>
		</a>
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