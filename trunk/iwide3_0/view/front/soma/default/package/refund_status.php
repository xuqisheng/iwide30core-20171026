<body>
<div class="pageloading"><p class="isload"><?php echo $lang->line('loading'); ?></p></div>
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
<ul class="list_style_2 bd">
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('order_number'); ?></span>
        <span><?php echo $order_id; ?></span>
    </li>
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('order_amount'); ?></span>
        <span class="color_main"><?php echo $refund_total; ?><?php echo $lang->line('yuan');  ?></span>
    </li>
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('return_account');  ?></span>
        <span><?php echo $refund_recv; ?></span>
    </li>
    <!--
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('arrive_time');  ?></span>
        <span><?php echo $time; ?></span>
    </li>
    -->
</ul>

<div class="bg_fff bd martop">
    <ul class="block_list pad3 bd_bottom">
    	<li>
        	<span><?php echo $lang->line('refund_progress'); ?></span>
        	<span><a href="tel:<?php echo $product->m_get('hotel_tel'); ?>"><em class="ico_style">i</em> <?php echo $lang->line('customer_service'); ?></a></span>
        </li>
    </ul>
    <ul class="group_rule">
        <?php echo $status_str; ?>
	   <!-- <li <?php if( $refund_status == $model::STATUS_WAITING ){ ?>class="active"<?php }?>><em></em><p>酒店审核中</p></li>
	   <li  <?php if( $refund_status == $model::STATUS_PENDING ) { ?>class="active cur"<?php }?>><em></em><hr><p>同意退款</p></li>
        <li><em></em><hr><p>微信退款中</p></li>
	   <li <?php if( $refund_status == $model::STATUS_REFUND ) { ?>class="active"<?php }?>><em></em><hr><p>退款成功</p></li> -->
    </ul>
</div>
<a class="whiteblock center bd" href="<?php echo Soma_const_url::inst()->get_url('*/package/index', array( 'id'=>$inter_id ));?>"><?php echo $lang->line('visit_online_shop'); ?></a>

<!-- 推荐位  -->
<?php echo isset($block) ? $block: '';?>
<!-- 推荐位  -->
    
</body>
</html>
