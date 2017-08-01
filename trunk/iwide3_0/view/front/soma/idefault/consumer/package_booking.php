<?php
    $show_y_flag = true;
    if($item['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT)
    {
        $show_y_flag = false;
    }
?>
<body style="padding-bottom:3%">
<div class="pageloading"><p class="isload"><?php echo $lang->line('loading');?></p></div>
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


<div class="notic_banner color_888 bd bg_fff h24">
    <span>
        <?php echo $lang->line('purchase_tips');?>
    </span>
</div>

<div class="order_list bd martop">
    <div class="item bg_fff"><!-- 
        <div class="tp_price color_fff hotel_link">
            <a href="" class="wxpay">微信预定</a>
        </div> -->
        <div class="img"><img src="<?php echo $item['face_img']; ?>" /></div>
        <p class="txtclip"><?php echo $item['name']; ?></p>
        <p class="txtclip"><?php echo $item['hotel_name']; ?></p>
        <p>
            <?php if($show_y_flag): ?>
            <span class="y color_main h30">
            <?php else: ?>
            <span class="color_main h30">
            <?php endif; ?>

            <?php echo $item['price_package']; ?>
            </span>
        </p>
    </div>
</div>
<div class="bg_fff bd martop block">
    <p class="bd_bottom" ><?php echo $lang->line('telephone_reservation');?></p>
    <ul class="step_style webkitbox color_main h24">
        <li>
            <em>1</em>
            <p>
                <?php echo $lang->line('dial_number');?>
            </p>
        </li>
        <li>
            <em>2</em>
            <p>
                <?php echo $lang->line('tell_customer_service');?>
                <br>
                <?php if($langDir == 'english'): ?>

                    <?php echo $lang->line('Package');?>
                <?php else: ?>
                    <?php echo CONSTANTS_BSN;?>

                <?php endif; ?>

                <?php echo $lang->line('vocher_code');?>
            </p>
        </li>
        <li>
            <em>3</em>
            <p>
                <?php echo $lang->line('reservation_success');?>
            </p>
        </li>
    </ul>
</div>

<div class="bg_fff bd martop block" id="showdetail">
    <?php if( $is_expire ): ?>
        <p class="h24">
            <?php echo $lang->line('expired');?>
            <span class="color_main">（<?php echo $item['expiration_date']; ?>）</span>
        </p>
    <?php else: ?>
        <p class="h24">
            <?php echo $lang->line('vocher_code');?>
            <span class="color_main"> <?php echo $item['qrcode']; ?></span>
        </p>
    <?php endif; ?>
</div>

<div class="foot_btn">
    <a href="tel:<?php echo $item['hotel_tel']; ?>" class="color_fff"><em class="iconfont">&#xE611; </em><?php echo $item['hotel_tel']; ?></a>
</div>

<!-- 推荐位  -->
<?php echo isset($block) ? $block: '';?>
<!-- 推荐位  -->
    
<script>
    $('input').focus(function(){
        window.setTimeout(function(){
            $(document).scrollTop($(document).height());
        },200);
    })
    $('input').blur(function(){
        if ($(this).val()=='') return;
        $.MsgBox.Confirm('<?php $lang->line('confirm_to_redeem');?>',null,null,'<?php echo $lang->line('ok');?>','<?php echo $lang->line('cancel');?>');
    })
</script>
</body>
</html>