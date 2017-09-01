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
<body>
<div class="pageloading"><p class="isload" style="margin-top:150px">正在加载</p></div>
<!-- 以上为head -->

<div class="whiteblock webkitbox justify bd" style="margin-top:0">
	<!-- <span>订单号：21212121212121</span> -->
    <!-- <span>2016-03-08 16：00：00</span> -->
    <span>预订号：<?php echo $order['reserve_id']; ?></span>
    <span><?php echo $order['create_time']; ?></span>
</div>
<a class="goods webkitbox bd whiteblock">
	<div class="goodsimg">
        <div class="squareimg">
            <!-- <img src="<?php echo base_url('public/soma/images');?>/eg4.jpg" /> -->
            <img src="<?php echo $product['face_img']; ?>" />
        </div>
    </div>
    <div>
    	<!-- <p class="h30">金房卡月饼</p>
        <p class="color_888">零售价：¥188/盒</p> -->
        <p class="h30"><?php echo $order['name']; ?></p>
        <p class="color_888">订购数量：<?php echo $order['qty']; ?>盒</p>
        <?php if($order_status): ?>
            <p class="color_888">零售价：<?php echo $order['product_price']; ?></p>
        <?php endif; ?>
    </div>
</a>
<?php if(!$order_status): ?>
    <div class="pad10 center martop color_888 h26">您的信息已提交，2小时内，将会有专门人员跟您确认付款事宜，请保持手机畅通</div>   

    <div class="webkitbox center martop pad10">
    	<p><a class="btn_main bdradius" href="tel:<?php echo $hotel['tel']; ?>">主动联系商家</a></p>
        <p><a class="btn_main bdradius" href="<?php echo Soma_const_url::inst()->get_url('*/*/index',array('id'=>$inter_id));?>">看看其他商品</a></p>
    </div>
<?php else: ?>
<div class="webkitbox center martop pad10">
    <p><a class="btn_main bdradius" href="<?php echo Soma_const_url::inst()->get_url('*/order/my_order_list',array('id'=>$inter_id)); ?>">您的订单已确认，请点击使用</a></p>
</div>
<?php endif; ?>
</body>
</html>
