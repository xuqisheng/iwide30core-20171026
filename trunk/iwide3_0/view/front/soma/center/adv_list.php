<body>
<!-- <div class="pageloading"><p class="isload">正在加载</p></div> -->
<!-- 以上为header.php -->

<script>
wx.config({
    debug: false,
    appId: '<?php echo $wx_config["appId"]?>',
    timestamp: <?php echo $wx_config["timestamp"]?>,
    nonceStr: '<?php echo $wx_config["nonceStr"]?>',
    signature: '<?php echo $wx_config["signature"]?>',
    jsApiList: [<?php echo $js_api_list; ?>,'getLocation','openLocation']
});
wx.ready(function(){
    <?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
    <?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>

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

<div class="tp_list">
	<?php 
		if( count( $advList ) > 0 ):
			foreach( $advList as $k=>$v ):
	?>
	    <a class="item" href="<?php echo $v['link'];?>">
	    	<div class=" squareimg">
		    	<img src="<?php echo get_cdn_url('public/soma/images/default2.jpg');?>" data-original="<?php echo $v['logo'];?>" class="lazy"/>
		    </div>
	    </a>
	<?php endforeach;else:?>
		<div class="ui_none"><div>还没有活动预告<br>如有疑问，联系客服</div></div>
	<?php endif;?>
</div>

