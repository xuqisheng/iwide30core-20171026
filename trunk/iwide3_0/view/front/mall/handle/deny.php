<link href="<?php echo base_url('public/mall/multi/style/pay_success.css')?>" rel="stylesheet">
<title>认证失败</title>
</head>
<body>
<div class="success">
	<div class="logo"><img src="<?php echo base_url('public/mall/multi/images/ico/wrong.png')?>"/></div>
    <p><a style="color:#f00;" href="javascript:;"><?php echo $message; ?></a></p>
	<p>窗口会在5秒钟后关闭...</p>
</div>
</body>
<script>
window.onload=function(){
	window.setTimeout(function(){
		wx.closeWindow();
	},5000);
}
</script>
</html>
