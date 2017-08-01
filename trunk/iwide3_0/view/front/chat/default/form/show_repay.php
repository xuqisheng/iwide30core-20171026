<!doctype html>
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
<?php echo referurl('js','viewport2.js',2,$media_path) ?>
<?php echo referurl('js','jquery.js',2,$media_path) ?>
<?php echo referurl('css','global.css',2,$media_path) ?>
<?php echo referurl('css','repay.css',2,$media_path) ?>
<script type="text/javascript">

</script>
</head><body>
<?php
$REQUEST_SCHEME = isset($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']:"http";
$HTTPHOST = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:"";
	$domain = $REQUEST_SCHEME.'://'.$HTTPHOST;
?>


<?php 
$postopenid = isset($_POST['openid'])?$_POST['openid']:'';
if($postopenid){
	$payret = qfpost('http://iwidecn.iwide.cn/index.php/wxpay/superform_nav?id=a429262687',$_POST);

    preg_match_all("%<code_url>(.*?)\[CDATA\[(.*)\]\](.*?)</code_url>%",$payret,$payretarr);
 
    $payurl = isset($payretarr[2][0])?$payretarr[2][0]:'';
    if ($payurl) {
?>

<div class="head">
	<div style="background:#fff; padding:4%;">
        <div class="name">酒店邦年会邀请函</div>
        <div class="time">2015-11-25 09:30~17:00</div>
        <div class="thing">
            <span>年度活动入场券</span>
            <span>x1</span>
            <span><?php echo floatval($data['price']);?>元</span>
        </div>
        <div class="price"><span>共1张</span><span>总计<b><?php echo floatval($data['price']);?></b>元</span></div>
    </div>
    <div class="borderimg"></div>
</div>	
<div class="saoma">
	<div class="title">微信扫码支付</div>
    <img style="width:80%;" src="/index.php/chat/api/qrcode?data=<?php echo urlencode($payurl);?>" />
    <div>长按图片（识别二维码）付款</div>
</div>
<script>
setInterval(function(){
	$.get('/index.php/chat/fapi/repay?act=ispay&iad=<?php echo $id;?>',{},function(d){
	    if(d==3){
		    top.location.href = '/index.php/chat/fapi/addresult?iad=<?php echo $id;?>';
		}
	});
},1000);
</script>
    <?php 
    }
	else {
        echo '<script>alert("支付失败，请重新支付");location.href="'.$domain.'/index.php/chat/fapi?iad='.$data['cid'].'&ret=fail";</script>';
	}
}
else {
?>
<form id="dopay1" name="dopay1" method="post" action="" style="display:none">
<p><?php if($csrf){echo '<input type="hidden" name="'.$csrf['name'].'" value="'.$csrf['hash'].'" />';}?>
  <input name="openid" type="text" value="<?php echo $openid;?>" />
</p>
<p>
  <input name="body" type="text" value="<?php echo $data['title'];?>" />
</p>
<p>
  <input name="success_url" type="text" value="<?php echo $domain;?>/index.php/chat/fapi/addresult?iad=<?php echo $id;?>&ret=success" />
</p>
<p>
  <input name="fail_url" type="text" value="<?php echo $domain;?>/index.php/chat/fapi?iad=<?php echo $data['cid'];?>&ret=fail" />
</p>

  <input name="out_trade_no" type="text" value="<?php echo $tradeno;?>" />
  
  <input name="__pa_openid" type="text" value="<?php echo $openid;?>" />
  
  <input name="product_id" type="text" value="po89wt5P587fL2iHY6fWu6VDy4yg" />

<p>
  <input name="total_fee" type="text" value="<?php echo floatval($data['price'])*100;?>" />
</p>
<p>
  <input name="notify_url" type="text" value="<?php echo $domain;?>/index.php/chat/api/notify" />
  <input type="submit" id="dosubmit" name="Submit" value=" 二 维 码 支 付 " />
</p>
</form>
<script>$("#dopay1").submit();</script>
<?php } ?>
</body>
</html>