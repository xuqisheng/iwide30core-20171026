<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>卡券详细</title>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/card.css");?>"/>
    <?php include 'wxheader.php' ?>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/zepto.min.js");?>"></script>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/example.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/js/login.js");?>"></script>
    <script>
    wx.config({
        debug:false,
        appId:'<?php echo $signpackage["appId"];?>',
        timestamp:<?php echo $signpackage["timestamp"];?>,
        nonceStr:'<?php echo $signpackage["nonceStr"];?>',
        signature:'<?php echo $signpackage["signature"];?>',
        jsApiList: [
            'hideOptionMenu',
         ]
       });
        wx.ready(function (){
            wx.hideOptionMenu();
        });
    </script>
</head>
<style>

</style>
<body>
<div class="ticket">
	<div class="t_head"></div>
	<h1><?php echo $public['name'];?></h1>
    <?php if(isset($card_info) && !empty($card_info)){ ?>
<!--	<div class="t_img"><img src="--><?php //echo $card_info['logo_url'] ?><!--"/></div>-->
	<div class="con_list">
		<div class="c_868">卡券名称</div>
		<div class="c_l_c ellipsis"><?php echo $card_info['title'] ?></div>
	</div>
	<div class="con_list">
		<div class="c_868">卡券说明</div>
		<div class="c_l_c" ><?php echo str_replace(array('；',';'), '.<br/>', $card_info['remark']) ?></div>
	</div>
    <?php if(isset($card_info['expire_time'])):?>
	<div class="con_list">
		<div class="c_868">有效期</div>
		<div class="c_l_c ellipsis">
            <?php echo date('Y.m.d',$card_info['use_time_start']); ?>至
            <?php echo date('Y.m.d',$card_info['expire_time']); ?>
        </div>
	</div>
    <?php endif;?>
        <div class="btn_lst">
            <a class="m_r_4" href="javascript:history.go(-1);location.reload();"><span>返回</span></a>
        </div>
    <?php }else{ ?>
    <div class="btn_lst">
        <div class="c_l_c ellipsis">卡券信息不存在或已经赠送</div>
        <a class="m_r_4" href="javascript:history.go(-1);location.reload();"><span>返回</span></a>
    </div>
    <?php } ?>
</div>
<div class="fixed"></div>
</body>
</html>
