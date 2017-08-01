<link href="<?php echo base_url('public/soma/styles/quantity.css'). config_item('css_debug');?>" rel="stylesheet">
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
<!--<div class="pageloading"><p class="isload" style="margin-top:150px">正在加载</p></div>-->
<!-- 以上为head -->

<div class="quantity_list">
    
    <?php foreach ($product_list as $item): ?>
        <a href="<?php echo Soma_const_url::inst()->get_url('*/*/reserve_page',array('id'=>$inter_id,'pid'=>$item['product_id']));?>" class="squareimg">
            <!-- <img src="<?php echo base_url('public/soma/images');?>/eg4.jpg" /> -->
            <img src="<?php echo $item['face_img']; ?>" />
            <div class="webkitbox absolute mask">
                <div>
                    <p class="color_fff"><?php echo $item['name']; ?></p>
                    <!-- <p class="color_main">188元/盒</p> -->
                </div>
                <div class="btn_main bdradius">立即预定</div>
            </div>
        </a>
    <?php endforeach; ?>

    <!--
	<a href="pay.html" class="squareimg">
    	<img src="<?php echo base_url('public/soma/images');?>/eg4.jpg" />
        <div class="webkitbox absolute mask">
        	<div>
            	<p class="color_fff">金房卡酒店月饼</p>
                <p class="color_main">188元/盒</p>
            </div>
            <div class="btn_main bdradius">立即预定</div>
        </div>
    </a>
	<a href="pay.html" class="squareimg">
    	<img src="<?php echo base_url('public/soma/images');?>/eg4.jpg" />
        <div class="webkitbox absolute mask">
        	<div>
            	<p class="color_fff">金房卡酒店月饼</p>
                <p class="color_main">188元/盒</p>
            </div>
            <div class="btn_main bdradius">立即预定</div>
        </div>
    </a>
    -->
</div>


</body>
</html>
