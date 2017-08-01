<link href="<?php echo base_url('public/mall/multi/style/pay_success.css')?>" rel="stylesheet">
<title>支付成功</title>
</head>
<body>
<div class="success">
	<div class="logo"><img src="<?php echo base_url('public/mall/multi/images/ico/cancel.png')?>"/></div>
    <p><a style="color:#000" href="<?php echo site_url('mall/wap/mail_order/'.$oid)?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>">支付成功，正在跳转...</a></p>
</div>
</body>
<script>
window.onload=function(){
	window.setTimeout(function(){
		window.location.href="<?php echo site_url('mall/wap/record/'.$oid)?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>";
	},1000);
}
</script>
</html>
