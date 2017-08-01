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
    <title><?php echo $title?></title>
    <link rel="stylesheet" href="<?php echo base_url('public/okpay/default/styles/guide.css')?>"/>
</head>
<body>
<div class="box">
    <div class="dc_txt">激活“微宝客”后推荐好友购买成功即可获得<br>最高<font class="profit">10%</font>收益</div>
    <div class="btn_list">
	<?php if(isset($confirm_form) && $confirm_form):echo form_open($confirm_url);?>
        <input type="hidden" name="inter_id" value="<?php echo $posts['inter_id']?>" />
        <input type="hidden" name="openid" value="<?php echo $posts['openid']?>" />
        <input type="hidden" name="rtn_url" value="<?php echo $posts['rtn_url']?>" />
        <button class="money" style="border:0"><?php echo isset($confirm_btn_text) ? $confirm_btn_text : '马上赚钱'?></button>
    <?php else:?>
        <?php if(isset($confirm_btn) && $confirm_btn):?><a
            href="<?php if(isset($confirm_url)):echo prep_url($confirm_url);else:echo 'javascript:;';endif;?>"
            class="money"><?php echo isset($confirm_btn_text) ? $confirm_btn_text : '马上赚钱'?></a><?php endif;?>
    <?php endif;?>
        <?php if(isset($cancel_btn) && $cancel_btn):?>
        <button class="recom" type="button" style="border:0">免费推荐</button>
		<?php endif;?>
    </div>
</div>
<div class="bt_img">
	<img src="<?php echo base_url('public/okpay/default/images/bottom.png')?>" />
</div>
</body>
</html>
<script src="<?php echo base_url('public/okpay/default/scripts/jquery.min.js')?>"></script>
<script type="text/javascript">
if($(window).height()==416){
	$('.bt_img').css({"position":"static"});	
};
$(function(){
	$('.recom').click(function(){
        <?php if(isset($cancel_btn) && $cancel_btn){?>
		<?php if(isset($cancel_url)){?>
		window.location.href="<?php echo prep_url($cancel_url);?>"
		<?php } else{?>
		window.history.back(-1);
		<?php }}?>
	})
})
</script>
</body>

