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
<title>会员中心</title>
</head>
<body>
<div class="head">
    <div class="padding">
		<?php if($centerinfo['value']=="login"){ ?>
			<?php if($centerinfo['is_login']=='t' && $centerinfo['member_mode'] ==2 &&$inter_id!='a482718699'){ ?>
				<div class="user_data"><a href="<?php echo base_url("index.php/membervip/perfectinfo");?>">修改资料</a></div>
			<?php }elseif($centerinfo['is_login']=='f'){ ?>
				<div class="user_data"><a href="<?php echo base_url("index.php/membervip/reg");?>">用户注册</a></div>
			<?php } ?>
		<?php } ?>
		<?php if($centerinfo['value']=="perfect"){ ?>
		<?php if($inter_id!='a467261753'){?>
			<div class="user_data"><a href="<?php echo base_url("index.php/membervip/perfectinfo");?>">完善资料</a></div>
		<?php }else { ?>
		<div class="user_data"><a href="<?php echo base_url("index.php/membervip/member_witch/");?>">提交资料</a></div>
<?php }}?>
        <div class="user_img"><a href="<?php echo base_url("index.php/membervip/center/info");?>"><img src="<?php echo $info['headimgurl'];?>"></a></div>
        <div class="user_name">
            <?php if($centerinfo['name']=='微信用户' || $centerinfo['name']=='' ){ ?>
                <?php echo $info['nickname']; ?>
            <?php }else{ ?>
                <?php echo $centerinfo['name']; ?>
            <?php } ?>
       	</div>
        <div class="viplv_black">
            <?php if($centerinfo['value']=="login" && $centerinfo['member_mode'] ==1 ){ ?>
                微信粉丝
            <?php }else{ ?>
                <?php echo $centerinfo['lvl_name'] ?>
            <?php } ?>
        </div>
    </div>
    <div class="mask">
    	<a href="<?php echo base_url("index.php/membervip/card");?>" class="item">
        	<div><?php echo $centerinfo['card_count'] ?></div>
            <div>我的卡券</div>
        </a>
    	<a href="<?php echo base_url("index.php/membervip/balance");?>" class="item">
        	<div>
                <?php if($centerinfo['value']=="login" && $centerinfo['member_mode'] ==1 ){ ?>
                    --
                <?php }else{ ?>
                    <?php echo $centerinfo['balance'] ?>
                <?php } ?>
            </div>
            <div>我的<?php echo $this->_ci_cached_vars['filed_name']['balance_name'];?></div>
        </a>
    	<a href="<?php echo base_url("index.php/membervip/bonus");?>" class="item">
        	<div>
                <?php if($centerinfo['value']=="login" && $centerinfo['member_mode'] ==1 ){ ?>
                    --
                <?php }else{ ?>
                    <?php echo $centerinfo['credit'] ?>
                <?php } ?>
            </div>
            <div>我的<?php echo $this->_ci_cached_vars['filed_name']['credit_name'];?></div>
        </a>
    </div>
</div>
<?php $modelnames=array('全员营销','分销注册','分销中心','我的凤凰礼卡','快速订房','会员权益','套票订单','客房订单','社群客','我的订单','商城订单','酒店订单','社群帮','商城预定','套票预定' );?>
<?php foreach ($menukey as $k => $v) { ?>
        <?php foreach ($menu as $key => $value) { ?>
            <?php if($v==$value['group']) { ?>
                <div class="ui_btn_list ui_border">
                <?php if($centerinfo['member_mode']==2 && $centerinfo['is_login'] == 'f' && !in_array($value['modelname'],$modelnames)){ ?>
                    <a href="<?php echo base_url("index.php/membervip/login");?>" class="item">
                <?php }elseif($centerinfo['member_mode']==1 && $centerinfo['value']=="login" && !in_array($value['modelname'],$modelnames)){ ?>
                    <a href="<?php echo base_url("index.php/membervip/login");?>" class="item">
                <?php }else{ ?>
                    <a href="<?php echo $value['link'] ?>" class="item">
                <?php } ?>
        	    <em class="ui_ico <?php echo $value['ico'] ?>"></em>
        	    <tt><?php echo $value['modelname'] ?>
                    <?php if($value['modelname']=='会员资料'){ ?>
                        <?php if($centerinfo['value']=="login" && $centerinfo['member_mode'] ==1 ){ ?>
                        <?php }else{ ?>
                            (<?php echo $centerinfo['membership_number']; ?>)
                        <?php } ?>
                    <?php } ?>
                </tt>
        	    <span>
                    <?php if($value['modelname']=='会员资料' ){ ?>
                        <?php if($centerinfo['value']=="login"){ ?>
                            <?php if($centerinfo['is_login']=='f' || $centerinfo['member_mode']==1 ){ ?>
                                会员登录
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </span>
                </a>
                </div>
            <?php } ?>
        <?php } ?>
<?php } ?>
<div id="show_message" style="display:none"><?php if(isset($message)) echo $message;?></div>
<script>
$(document).ready(function(){
	if($("#show_message").html().length) {
		alert($("#show_message").html());
	}
})
</script>
</body>
</html>