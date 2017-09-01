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
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/history.css");?>" rel="stylesheet">
<title>充值消费记录</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
<?php if(!empty($data_record)) echo '._tips{display:none !important;}';?>
<?php if($inter_id == 'a455510007'){ ?>
.ui_foot_btn{border:none;background: #d40f20;}
<?php }?>
-->
</style>
<body>
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
<div class="ui_tab_btn">
    <?php foreach($data_title as $k=>$t) {?>
        <div class="item <?php if($k==0) echo "cur";?>"><?php echo $t;?></div>
    <?php } ?>
    	<div class="_tips">登录后才可查看记录~</div>
</div>
<?php foreach($data_record as $k=>$records) {?>
	<div class="ui_normal_list ui_border point" <?php if($k!=0) echo 'style="display:none;"';?>>
		<?php foreach($records as $obj) {?>
		<div class="item">
	    	<tt><?php echo $obj->note;?></tt>
	    	<tt><?php echo $obj->balance;?>元</tt>
	    	<div><?php echo $obj->create_time;?>
                <?php /*if(isset($obj->status)){
                    if($obj->status == 1){
                ?>
                    <span style="float:right;color:red;width:6em;">有效</span>
                <?php }elseif($obj->status == 2){ ?>
                    <span style="float:right;color:red;width:6em;">无效</span>
                <?php }
                }*/
                ?>
            </div>
	    </div>
	    <?php } ?>
	</div>
<?php } ?>

<a href="<?php echo base_url("/member/account/login");?>" class="ui_foot_btn _tips">
    <div>马上登录</div>
</a>
<!--
<div style="padding-top:15%;">
	<a href="<?php echo base_url("index.php/member/ccharge/gocharge");?>" class="ui_foot_fixed_btn">
    	<em class="ui_ico ui_ico10"></em>
        <div>马上充值</div>
    </a>
</div>
-->
</body>
<script>
$(function(){
	$('.ui_tab_btn .item').click(function(){
		$(this).addClass('cur').siblings().removeClass('cur');
		var _index=$(this).index();
		$('.ui_normal_list').eq(_index).show();
		$('.ui_normal_list').eq(_index).siblings('.ui_normal_list').hide();
	})

})
</script>
</html>
