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
<?php echo referurl('js','viewport.js',1,$media_path) ?>
<?php echo referurl('js','jquery.js',1,$media_path) ?>
<?php echo referurl('js','ui_control.js',1,$media_path) ?>
<?php echo referurl('css','global.css',1,$media_path) ?>
<?php echo referurl('css','ui.css',1,$media_path) ?>
<?php echo referurl('css','ui_style.css',1,$media_path) ?>
<?php echo referurl('css','bind_company.css',1,$media_path) ?>
<title>协议企业绑定</title>
</head>
<body>
<?php



?>
<script>
wx.config({
    debug:false,
    appId:'<?php echo $signpackage["appId"];?>',
    timestamp:<?php echo $signpackage["timestamp"];?>,
    nonceStr:'<?php echo $signpackage["nonceStr"];?>',
    signature:'<?php echo $signpackage["signature"];?>',
    jsApiList: [
        'hideOptionMenu'
     ]
   });
   wx.ready(function () {
	   wx.hideOptionMenu();
   });
</script>
<!-------------------          ↓↓↓↓↓ 绑定失败↓↓↓↓                    ------------------->
<!--
<div class="bind_status fail">
	<p>绑定失败</p>
    <p></p>
</div>
-->
<!-------------------          ↓↓↓↓↓ 绑定成功↓↓↓↓                    ------------------->
<div class="bind_status success">
	<p>当前绑定</p>
	<p><?php
                if(!empty($_GET['cpname'])){
                    echo $_GET['cpname'];
                }else{
                    echo $company_name;
                }
        ?>
        </p>
</div>

<div class="notic">
    <p>温馨提示:<br>登记前请确认您是该企业员工，协议价预订入住时可能需要出示相关证件进行核验</p>
</div>

<?php  if($is_multy==2){?>
<a href="<?php echo site_url('hotel/hotel/search');?>" class="ui_foot_btn" style="color:#fff;">马上订房</a>
<?php }else{ ?>
<a href="<?php echo site_url("hotel/hotel/index?id={$inter_id}");?>" class="ui_foot_btn" style="color:#fff;">马上订房</a>
<?php }?>

</body>
</html>
