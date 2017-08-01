<body>
<style>
.bg_F3F4F8, body, html {
    background: #2d3132;
}

.fans_saler_qrcode_wrap {
	position: fixed;
	left: 0;
	top:0;
	width: 100%;
	height: 100%;
}

.fans_saler_qrcode {
  overflow: hidden;
  border-radius: 4px;
  width: 90%;
  position: absolute;
  left:50%;
  top:50%;
  -webkit-transform:translate(-50%,-50%);
}

.fans_saler_qrcode img {
  width: 100%;
  border: none;
}


</style>
<div class="fans_saler_qrcode_wrap">
	<div class="fans_saler_qrcode">
		<img src="<?php echo $qr_code; ?>">	
	</div>
</div>
</body>
</html>
