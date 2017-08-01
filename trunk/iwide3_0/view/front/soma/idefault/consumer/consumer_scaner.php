
<body d="扫码页面">
<!-- 
<div class="pageloading"><p class="isload">正在加载</p></div>
 -->
<script>
wx.config({
    debug: false,
    appId: '<?php echo $wx_config["appId"]?>',
    timestamp: <?php echo $wx_config["timestamp"]?>,
    nonceStr: '<?php echo $wx_config["nonceStr"]?>',
    signature: '<?php echo $wx_config["signature"]?>',
    jsApiList: [<?php echo $js_api_list; ?>]
});
wx.ready(function(){
	<?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>
	
	<?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
});
</script>
<div id="logo_div" class="center" style="padding-top:100px;">
	<p class="pad3 color_main" style="font-size:60px;"><em class="iconfont">&#xe61a;</em></p>
	<p class="h32 color_888 pad3"><?php echo $message; ?></p>
    <p class="color_888" style="padding-top:50px">小提示：右上角收藏本页面，以后打开即可核销</p>
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
					$.MsgBox.Confirm(r.message,null,null,'好的','取消');
				} else {
					$.MsgBox.Confirm(r.message,null,null,'好的','取消');
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