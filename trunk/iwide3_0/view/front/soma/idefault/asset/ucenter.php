<body>
<div class="pageloading"><p class="isload">正在加载</p></div>
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

<?php if( $js_share_config ): ?>
      	wx.onMenuShareTimeline({
    	    title: '<?php echo $js_share_config["title"]?>',
    	    link: '<?php echo $js_share_config["link"]?>',
    	    imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
    	    success: function () {},
    	    cancel: function () {}
    	});
    	wx.onMenuShareAppMessage({
    	    title: '<?php echo $js_share_config["title"]?>',
    	    desc: '<?php echo $js_share_config["desc"]?>',
    	    link: '<?php echo $js_share_config["link"]?>', 
    	    imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
    	    //type: '', //music|video|link(default)
    	    //dataUrl: '', //use in music|video
    	    success: function () {},
    	    cancel: function () {}
    	});
<?php endif; ?>
});
</script>

<div class="j_head">
    <div class="img"><img src="<?php echo $headimgurl; ?>"/></div>
    <p class="color_fff"><?php echo $nickname; ?></p>
</div>

<div class="list_style martop bd">
    <a href="<?php echo $order_url; ?>" class="color_555">
        <span><em class="iconfont h30">&#xE618;</em> <tt>我的订单</tt></span>
    </a>
    <a href="<?php echo $gift_url; ?>"class="color_555">
        <span><em class="iconfont h30">&#xE619;</em> <tt>赠送礼物</tt></span>
    </a>
    <a href="<?php echo $shipping_url; ?>" class="color_555">
        <span><em class="iconfont h30">&#xE614;</em> <tt>邮寄商品</tt></span>
    </a>
</div>
<div class="list_style martop bd">
    <a href="<?php echo $home_url; ?>" class="color_555">
        <span><em class="iconfont h30">&#xE615;</em> <tt>商城首页</tt></span>
    </a>
</div>
</body>
</html>
