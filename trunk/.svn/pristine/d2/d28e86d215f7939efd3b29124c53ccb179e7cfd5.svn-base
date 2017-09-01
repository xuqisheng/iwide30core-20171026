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
<?php echo referurl('js','jquery-1.11.1.min.js',2,$media_path) ?>
<script type="text/javascript">
$(function(){
document.getElementById("dopay1").submit();  
});

//if(self!=top){top.location.href=location.href;}
//else{}
</script>
</head><body>
<?php
$REQUEST_SCHEME = isset($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']:"http";
$HTTPHOST = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:"";
	$domain = $REQUEST_SCHEME.'://'.$HTTPHOST;
?>
<form id="dopay1" name="dopay1" method="post" action="http://iwidecn.iwide.cn/index.php/wxpay/superform?id=a429262687" style="display:">
<p><?php if($csrf){echo '<input type="hidden" name="'.$csrf['name'].'" value="'.$csrf['hash'].'" />';}?>
  <input name="openid" type="text" value="<?php echo $openid;?>" />
</p>
<p>
  <input name="body" type="text" value="<?php echo urlencode($data['title']);?>" />
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
  <input type="submit" id="dosubmit" name="Submit" value="提交" />
</p>
</form>
</body>
</html>