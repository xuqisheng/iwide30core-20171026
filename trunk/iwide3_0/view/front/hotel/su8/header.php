<?php 
header('Cache-Control: public');
?><!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="public" />
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
<?php echo referurl('css','fontchange.css',3,$media_path) ?>
<?php echo referurl('css','ui.css',3,$media_path) ?>
<?php echo referurl('css','ui_ico.css',2,$media_path) ?>
<?php echo referurl('css','ui_style.css',2,$media_path) ?>
<?php echo referurl('css','ui_pull.css',2,$media_path) ?>
<?php echo referurl('js','jquery.js',3,$media_path) ?>
<?php echo referurl('js','ui_control.js',3,$media_path) ?>
<?php echo referurl('js','alert.js',3,$media_path) ?>
<?php echo referurl('js','om_code.js',1,$media_path) ?>
<?php include 'wxheader.php'?>
<!-- end -->
<title><?php echo $pagetitle;?></title>
<style>
body,html { font-size:5px !important;}

.h1{font-size:20px}
.h2{font-size:18px}
.h3{font-size:16px}
.h4{font-size:14px}
.h5{font-size:12px}
.h6{font-size:10px}

#ncalendar thead td:first-child, #ncalendar thead td:last-child, #ncalendar tbody td.span ,
.ui_tab .cur,.hotel_list .ui_price,.sure p,.vote_spread .ui_price,.ui_vote,.apply_list .level,.apply_list .point,
.ui_color,.room_list .ui_price,.pre_pay p:last-child,.form_list .pay_way .ui_price,.order_detail .ui_price
{color:#d40f20 !important}
#ncalendar tbody td.current, #ncalendar tbody td.begin, #ncalendar tbody td.end ,
.ui_btn.isable,.vote_pull .ischeck .ui_ico,.realtime_status li.iscured em,.photo_class .cur,.pre_pay p:first-child,.now_pay p,.footbtn,.form_list .ischeck .ui_ico,.submit_btn,.chooseroom_pull .sure_btn,.vote_pull .ischeck .ui_ico,.filter_pull .ischeck .ui_ico,.mbg
{background-color:#d40f20 !important;}
.pre_pay,.now_pay,.no_pay,.guest_pull li,.form_list .ischeck .ui_ico,.footfixed .submit_btn,.vote_pull .ischeck .ui_ico,.realtime_status .iscured hr,.bdtom,.filter_pull .ischeck .ui_ico
{ border-color:#d40f20;}
</style>
</head>
<body>
<div class="page_loading"><p class="isload">正在加载</p></div>

<div class="ui_pull tips_box" style="display:none" id="first_tips">
	<div class="box" style="text-align:center">
    	<div class="pull_close iconfont ui_color" style="display:none">&#x4e;</div>
        <div class="h2 ui_color">温馨提示</div>
        <div class="h4" style="padding-top:10px">登录或注册会员可享受更低房价</div>
        <div class="btn_list">
        	<a href="<?php echo base_url('/index.php/member/account/register?id=').$inter_id;?>" class="ui_btn mbg">注册</a>
        	<a href="<?php echo base_url('/index.php/member/account/login?id=').$inter_id;?>" class="ui_btn mbg">登录</a>
        	<div class="ui_color ui_btn" onClick="toclose()" style="width:89%; background:none; margin:4%; border:1px solid #d40f20">直接预订</div>
        </div>
    </div>
</div>
<script>
//if(window.localStorage){
//	<?php if(isset($member->is_login) && ($member->is_login ==1)){ ?>
//	window.localStorage.firstVisit=1;	
//	<?php } ?>
//	if(window.localStorage.firstVisit==undefined){
//		window.localStorage.firstVisit=1;
//		toshow($('#first_tips'));
//		var _h=$('#first_tips').height()-$('#first_tips .box').outerHeight();
//		$('#first_tips .box').css('margin-top',_h/3+'px');
//	}
//}
</script>