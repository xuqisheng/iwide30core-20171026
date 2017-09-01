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


<div class="notic_banner color_888 bd bg_fff h26">
	<span><?php echo $tips; ?></span>
</div>
<form action="<?php echo $post_url; ?>" method="POST">
<div class="order_list bd martop bg_fff">
   	<div class="item_header pad3 webkitbox bd_bottom h24">
    	<p><?php echo $lang->line('order_num'); ?><?php echo $detail['order_id']; ?></p>
        <p class="txt_r"><?php echo $detail['create_time']; ?></p>
    </div>
    <?php foreach( $detail['items'] as $k=>$v ){ ?>
        <?php
            // 是否显示¥符号
            $show_y_flag = true;
            if($v['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT)
            {
                $show_y_flag = false;
            }
        ?>
    <a href="<?php echo $v['detail_url']; ?>" class="item">
        <div class="img"><img src="<?php echo $v['face_img']; ?>" /></div>
        <p class="txtclip"><b><?php echo $v['name']; ?></b></p>
        <p class="txtclip"><?php echo $v['hotel_name']; ?></p>
        <p class="txtclip color_main">
            <?php if($show_y_flag):?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $v['price_package']; ?></span> x<?php echo $v['qty']; ?> <?php echo $lang->line('total_num'); ?><?php if($show_y_flag):?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $detail['subtotal']; ?></span>
        </p>
    </a> 
    <?php } ?>
    <!-- <a href="index.html" class="item">
        <div class="img"><img src="images/img/asdf.jpg" /></div>
        <p class="txtclip h2"><b>五星豪华海景房+温泉+双早</b></p>
        <p class="txtclip">台山碧桂园酒店</p>
        <p class="txtclip h2 color_main"><span class="y">938</span> x2 共<span class="y">1876</span></p>
    </a> -->
</div>

<div class="bd bg_fff martop pad3">
    <ul class="block_list">
    	<li>
        	<span class="color_888"><?php echo $lang->line('refund_method'); ?></span>
        	<span><?php echo $type; ?></span>
        </li>
    </ul>
</div>

<div class="bd martop ">
    <ul class="block_list pad3 bg_fff">
    	<li>
        	<span><?php echo $lang->line('refund_reasons'); ?></span>
        	<span><em class="iconfont">&#xe60d;</em></span>
        </li>
    </ul>
    <ul class="block_list pad3 color_888 refund_reason">
        <?php foreach( $cause as $k=>$v ){ ?>
        	<li class="28">
            	<input type="checkbox" name="cause[<?php echo $k; ?>]" style="display:none" value="<?php echo $v; ?>">
                <span><?php echo $v; ?></span>
            	<span><em class="iconfont">&#xe60e;</em></span>
            </li>
        <?php } ?>
    	<!-- <li>
        	<input type="checkbox" style="display:none" value="酒店预约上，但是没客房">
            <span>酒店预约上，但是没客房</span>
        	<span><em class="iconfont">&#xe60e;</em></span>
        </li>
    	<li>
        	<input type="checkbox" style="display:none" value="朋友／网上评价不好">
            <span>朋友／网上评价不好</span>
        	<span><em class="iconfont">&#xe60e;</em></span>
        </li>
    	<li>
        	<input type="checkbox" style="display:none" value="买多了／买错了">
            <span>买多了／买错了</span>
        	<span><em class="iconfont">&#xe60e;</em></span>
        </li>
    	<li>
        	<input type="checkbox" style="display:none" value="计划有变，没时间消费">
        	<span>计划有变，没时间消费</span>
        	<span><em class="iconfont">&#xe60e;</em></span>
        </li>
    	<li>
        	<input type="checkbox" style="display:none" value="联系不上商家">
        	<span>联系不上商家</span>
        	<span><em class="iconfont">&#xe60e;</em></span>
        </li>
    	<li>
        	<input type="checkbox" style="display:none" value="找到了更便宜的渠道">
            <span>找到了更便宜的渠道</span>
        	<span><em class="iconfont">&#xe60e;</em></span>
        </li> -->
    </ul>
</div>
<div class="bg_fff bd martop block">
	<p class="h28 bd_bottom"><?php echo $lang->line('other_reasons'); ?></p>
    <p><textarea placeholder="<?php echo $lang->line('type_reply'); ?>" name="cause[]" rows="4" maxlength="140" style="width:100%;"></textarea></p>
</div>

<div class="foot_btn">
	<button type="submit" id="button"><?php echo $lang->line('apply_refund'); ?></button>
</div>
</form>
</body>
<script>
$('textarea').focus(function(){
	window.setTimeout(function(){
		$(document).scrollTop($(document).height());
	},200);
})

$('.refund_reason li').click(function(){
	$(this).toggleClass('choose');
	if ($('input',this).get(0).checked ){$('input',this).get(0).checked=false;}
	else{$('input',this).get(0).checked=true;}
});

$("#button").click(function(){
    var is_pass = false;
    $('input').each(function(){
        if( $(this).is(':checked') ){
            is_pass = true;
        }
    });

    if( !is_pass ){
        $.MsgBox.Confirm( "<?php echo $lang->line('refund_reasons'); ?>" ,null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancel'); ?>");
    }
    return is_pass;
});

</script>
</html>
