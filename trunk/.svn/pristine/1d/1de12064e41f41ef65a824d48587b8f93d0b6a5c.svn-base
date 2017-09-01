<body>
<div class="pageloading"><p class="isload">正在加载</p></div>
<script>
var redirectUrl = '<?php 
$redirect= urlencode( Soma_const_url::inst()->get_url('*/*/package_list_send', array('id'=> $this->inter_id) ) ); 
echo Soma_const_url::inst()->get_url('*/*/package_sending', array('redirect'=> $redirect, 'gid'=> $gid ) );
?>';
wx.config({
    debug: false,
    appId: '<?php echo $wx_config["appId"]?>',
    timestamp: <?php echo $wx_config["timestamp"]?>,
    nonceStr: '<?php echo $wx_config["nonceStr"]?>',
    signature: '<?php echo $wx_config["signature"]?>',
    jsApiList: [<?php echo $js_api_list; ?>]
});
wx.ready(function(){
	<?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
    <?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>
    <?php if( $js_share_config ): ?>
       	wx.onMenuShareTimeline({
     	    title: '<?php echo $js_share_config["title"]?>',
     	    link: '<?php echo $js_share_config["link"]?>',
     	    imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
     	    success: function () {
                location.href = redirectUrl;
            },
     	    cancel: function () {}
     	});
     	wx.onMenuShareAppMessage({
     	    title: '<?php echo $js_share_config["title"]?>',
     	    desc: '<?php echo $js_share_config["desc"]?>',
     	    link: '<?php echo $js_share_config["link"]?>',
     	    imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
     	    //type: '', //music|video|link(default)
     	    //dataUrl: '', //use in music|video
     	    success: function () {
     	    	location.href = redirectUrl;
             },
     	    cancel: function () {}
     	});
    <?php endif; ?>
});
</script>

<?php $detail= $items[0] ?>

<div class="notic_banner color_888 bd bg_fff h3">
    <span>小提示：将此单的套票送给朋友后，您的订单将不能退款</span>
</div>

<div class="sent_gift bg_fff">
    <div class="bg_main bdradius" style="width:2rem; height:0.5rem;margin:auto;"></div>
    <div class="pad3 color_main center h2"><?php echo $detail['hotel_name']; ?></div>
    <div class="img"><a href="<?php echo Soma_const_url::inst()->get_url('*/package/package_detail', 
            array('id'=> $this->inter_id, 'pid'=> $detail['product_id'] ) )?>"><img src="<?php echo $detail['face_img']; ?>" /></a></div>

    <div class="bg_fff  block">
        <p class="h2 color_888">套票名称</p>
        <p class="bd_bottom"><a href="<?php echo Soma_const_url::inst()->get_url('*/package/package_detail', 
            array('id'=> $this->inter_id, 'pid'=> $detail['product_id'] ) )?>"><?php echo $detail['name']; ?></a></p>
    </div>
    <div class="bg_fff  block">
        <p class="h2 color_888">套票内容</p>
        <p class="bd_bottom"><?php echo show_compose($detail['compose']); ?></p>
    </div>
    <div class="bg_fff  block">
        <p class="h2 color_888">有效期</p>
        <p class="bd_bottom"><?php echo show_date($detail['validity_date']); ?> - <?php echo show_date($detail['expiration_date']); ?></p>
    </div>
    <div class="foot_btn webkitbox">
        <a class="to_sent">赠送好友</a>
        <!--
             <span class="to_sent">继续赠送</span>
                <span>使用</span>
             <span>我也要送</span>-->
    </div>
</div>

<div class="ui_pull share_pull" style="display:none">
<!--    <div class="fen_bg"></div>-->
</div>
</body>

<script>
    $('.to_sent').click(function(){
        $('.share_pull').show();
    });
    $('.share_pull').click(function(){
        $(this).hide();
    });
</script>
</html>