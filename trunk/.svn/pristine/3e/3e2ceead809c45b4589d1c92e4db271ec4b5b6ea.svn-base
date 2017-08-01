<link href="<?php echo base_url('public/mall/multi/style/pay_success.css')?>" rel="stylesheet">
<title><?php echo $title; ?></title>
</head>
<body>
<div class="success">
	<div class="logo" id="logo_div">
	   <img src="<?php echo base_url('public/mall/multi/images/ico/cancel.png')?>" /></div>
    <p><?php echo $message; ?></p>
</div>
<script>
function call_qrcode(){
	wx.scanQRCode({
		needResult: 1,
		scanType: ["qrCode","barCode"],
		success: function (res) {
			var result = res.resultStr;
			$.post('<?php echo $callback; ?>', {'code':result,'openid':'<?php echo $openid; ?>','t':'<?php echo $t; ?>'}, function(r){
				if(r.status==1){
					alert(r.message);
				} else {
					alert(r.message);
				}
			}, 'json');
		}
	});
	$("title").html("扫码核销");
}
$('#logo_div').click(function(){ call_qrcode(); });
</script>
</body>
</html>
