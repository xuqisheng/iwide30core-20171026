<?php 
header('Cache-Control: public');
?><!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="user-scalable=no,width=320.1">
<!-- 全局控制 -->
<?php echo referurl('css','global.css',3,$media_path) ?>
<?php echo referurl('css','ui.css',3,$media_path) ?>
<?php echo referurl('css','ui_ico.css',2,$media_path) ?>
<?php echo referurl('css','ui_style.css',2,$media_path) ?>
<?php echo referurl('css','ui_pull.css',2,$media_path) ?>
<?php echo referurl('js','jquery.js',3,$media_path) ?>
<?php echo referurl('js','ui_control.js',3,$media_path) ?>
<?php echo referurl('js','alert.js',3,$media_path) ?>
<?php echo referurl('js','lazyload.js',3,$media_path) ?>
<?php include 'wxheader.php'?>
<?php echo $statistics_js;?>
<!-- end -->
<title><?php echo $pagetitle;?></title>
<style>

<?php $main_color=isset($overall_style['theme_color'])? $overall_style['theme_color']:'#ff9900';?>
<?php
 //$fontx=isset($overall_style['fontx'])?$overall_style['fontx']:10;
// if($fontx>20) $fontx=20;
// if($fontx<10) $fontx=10;
?>
body,html { font-size:5px !important;}

.h1,.h40{font-size:20px}
.h2,.h36{font-size:18px}
.h3,.h32{font-size:16px}
.h4,.h28{font-size:14px}
.h5,.h24{font-size:12px}
.h6,.h20{font-size:10px}

.bg_F3F4F8,body,html{background-color:#F3F4F8;}
.bg_fff{background-color:#fff;}
.bg_F8F8F8{background-color:#F8F8F8;}
.bg_eee{background-color:#eee;}
.bg_E4E4E4{background-color:#E4E4E4;}
.bg_D3D3D3{background-color:#D3D3D3;}
.bg_C3C3C3{background-color:#C3C3C3;}
.bg_999{background-color:#999;}
.bg_888{background-color:#888;}
.bg_555{background-color:#555;}
.bg_000{background-color:#000;}

/*副背景色系*/
.bg_link,a.bg_link{ background-color:#2d87e2;}
.bg_key,a.bg_key{background-color:#e22e3b;}
.bg_main,.bg_minor,.bg_link,.bg_key,/*主色*/
.bg_C3C3C3,.bg_999,.bg_888,.bg_555,.bg_000{color:#fff}

/* 字体颜色 */ 
/*辅助色系*/
.color_fff,a.color_fff{ color:#fff;}
.color_F8F8F8,a.color_F8F8F8{color:#F8F8F8;}
.color_F3F4F8,a.color_F3F4F8{color:#F3F4F8;}
.color_eee,a.color_eee{color:#eee;}
.color_E4E4E4,a.color_E4E4E4{color:#E4E4E4;}
.color_D3D3D3,a.color_D3D3D3{color:#D3D3D3;}
.color_C3C3C3,a.color_C3C3C3{color:#C3C3C3;}
.color_999,a.color_999{color:#999;}
.color_888,a.color_888{color:#888;}
.color_555,a.color_555{color:#555;}
.color_000,a.color_000{color:#000;}

/*主色系*/
.color_link,a.color_link{color:#2d87e2;}
.color_key,a.color_key{ color:#e22e3b;}

.color_main,a.color_main,#ncalendar thead td:first-child, #ncalendar thead td:last-child, #ncalendar tbody td.span ,
.ui_tab .cur, .near p,.hotel_list .ui_price,.sure p,.vote_spread .ui_price,.ui_vote,.apply_list .level,.apply_list .point,
.ui_color,.room_list .ui_price,.pre_pay p:last-child,.form_list .pay_way .ui_price,.order_detail .ui_price
{color:<?php echo $main_color;?> !important}
.bg_main,a.bg_main,.btn_main,a.btn_main,#ncalendar tbody td.current, #ncalendar tbody td.begin, #ncalendar tbody td.end ,
.ui_btn.isable,.vote_pull .ischeck .ui_ico,.realtime_status li.iscured em,.photo_class .cur,.pre_pay p:first-child,.now_pay p,.footbtn,.form_list .ischeck .ui_ico,.submit_btn,.chooseroom_pull .sure_btn,.vote_pull .ischeck .ui_ico
{background-color:<?php echo $main_color;?> !important;}
.pre_pay,.now_pay,.no_pay,.guest_pull li,.form_list .ischeck .ui_ico,.footfixed .submit_btn,.vote_pull .ischeck .ui_ico,.realtime_status .iscured hr,.bdtom,.realtime_status .iscur .circle em
{ border-color:<?php echo $main_color;?>;}
</style>

</head>
<body>
<div class="page_loading"><p class="isload">正在加载</p></div>
<script>
$(function(){
$("img.lazy").lazyload();  //惰性加载
});
</script>