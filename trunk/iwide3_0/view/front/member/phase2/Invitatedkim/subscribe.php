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
<title><?php echo $dis_conf['page_title']?></title>
<link href="<?php echo base_url("public/member/nvitedkim/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/nvitedkim/css/mycss.css");?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
<link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
<link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
<script src="<?php echo base_url("public/member/nvitedkim/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/nvitedkim/js/ui_control.js");?>"></script>
<style type="text/css">
    /*二维码start*/
    .explain{font-size:15px;}
    .explain>span{color:#ffde00;}
    .erwei_box{background:#fff;width:122px;height:122px;border-radius:7px;margin:3% auto;padding:5px;}
    .erwei{display:block;width:112px;height:112px;}
    .ex_txt{font-size:12px;width: 87%; margin: 0 auto;}
    /*二维码end*/
    .bg1{background:url(<?php echo isset($dis_conf['notice_banner'])?$dis_conf['notice_banner']:'';?>) no-repeat;background-size:100% 100%;}
</style>
<title>免房金攻略</title>
</head>
<body class="bg1">
<div class="pageloading"><p class="isload">正在加载</p></div>
	<div class="con_txt color_fff center P_17">
    	<div class="explain"><?php echo isset($dis_conf['regnotice_config']['value1'])?$dis_conf['regnotice_config']['value1']:'你已经获得';?><span><?php echo isset($user_info['name'])?$user_info['name']:'';?></span><br><?php echo isset($dis_conf['regnotice_config']['value2'])?$dis_conf['regnotice_config']['value2']:'';?></div>
        <div class="erwei_box"><img class="erwei" src="<?php echo site_url('membervip/invitatedkim/subqrcode').'?mid='.$share_member_id;?>"></div>
    	<div class="ex_txt"><?php echo isset($dis_conf['regnotice_config']['value3'])?$dis_conf['regnotice_config']['value3']:'请扫码公众号查看和使用你获得的优惠';?></div>
    </div>
<!--    <a class="btn_h h30 center" href="form_d.html">抢免房金</a>-->
</body>
</html>
