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
<?php include 'wxheader.php' ?>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<title>二维码</title>
</head>
<style>
*{ font-size:20px;}
body{background:none}
html{width:100%;height:100%;background:rgba(0,0,0,0.6);}
.fixe{ width:80%; margin:auto; position:relative; padding-top:30%;}
.fixe .er_log{display:block;position:relative;margin:auto;text-align:center; background:#fff; color:#000; text-align:center; border-radius:10px 10px 0 0; padding-top:12%; padding-bottom:10%; font-size:20px;}
.back{display:block;position:absolute; top:10%; right:5%; color:#999;}
.fixe .er_log h1{padding-top:5%;}
.erwen_b{margin:auto; font-size:0;}
</style>
<body>

<div class="fixe">
    <div class="erwen_b">
        <img src="<?php echo $qrc_link;?>">
    </div>
</div>
</body>
</html>