
<body d="失败">
<!-- 
<div class="pageloading"><p class="isload">正在加载</p></div>
 -->
<div class="center pad3" style="padding-top:100px;">
	<p class="pad3 color_888" style="font-size:60px;"><em class="iconfont">&#xe612;</em></p>
	<p class="h32 color_888 pad3"><?php echo $message; ?></p>
    <p class="color_888" style="padding-top:50px"><tt>5</tt>秒后关闭</p>
</div>
<script>
window.onload=function(){
	window.setTimeout(function(){
		wx.closeWindow();
	},5000);
}
</script>
</body>
</html>