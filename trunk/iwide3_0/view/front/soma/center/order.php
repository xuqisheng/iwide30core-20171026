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

<link href="<?php echo base_url('public/soma/mooncake_v1/mooncake.css');?>" rel="stylesheet">
<?php if(count($orders) <= 0): ?>
    <div class="ui_none"><div>您还没订单<br>如有疑问，联系客服</div></div>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
        <?php $item = $order['items'][0]; ?>
        <div class="order_list bd_top" style="margin-bottom:8px">
            <a href="<?php echo $order['link'];?>">
                <div class="item_header bg_fff pad3 webkitbox">
                    <p>订单编号：<?php echo $order['order_id'];?></p>
                    <p class="txt_r"><?php echo $order['create_time'];?></p>
                </div>
                <div class="item bd">
                    <div class="img"><img class="lazy" src="<?php echo base_url('public/soma/images/default2.jpg'); ?>" data-original="<?php echo $item['face_img'];?>" /></div>
                    <p class="txtclip h30"><?php echo $item['name'];?></p>
                    <p class="txtclip color_555"><?php echo $order['hotel_name'];?></p>
                    <p class="txtclip h30 color_main"><span class="y"><?php echo $item['price_package'];?> x<?php echo $item['qty'];?></span></p>
                </div>
            </a>
            <div class="bg_fff pad3 bd_bottom webkitbox item_foot">
                <p class="color_888">购买成功</p>
                <p class="color_main txt_r">
                    <a class="btn_void h24" href="<?php echo $order['link'];?>">查看</a>
                    <!-- <a class="btn_void h24" href="">使用</a> -->
                </p>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
