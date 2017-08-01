<body>
<link href="<?php echo base_url('public/soma/v2/v2.css'). config_item('css_debug');?>" rel="stylesheet">
<div class="pageloading"><p class="isload">正在加载</p></div>
<script>
wx.config({
    debug: false,
    appId: '<?php echo $wx_config["appId"]?>',
    timestamp: <?php echo $wx_config["timestamp"]?>,
    nonceStr: '<?php echo $wx_config["nonceStr"]?>',
    signature: '<?php echo $wx_config["signature"]?>',
    jsApiList: [<?php echo $js_api_list; ?>,'getLocation']
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

<div class="tp_list" style="padding-top:4px;">
    <?php foreach($packages as $k=>$v){?>
    <?php
        // 是否显示¥符号
        $show_y_flag = true;
        if($v['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT)
        {
            $show_y_flag = false;
        }
    ?>
    <a class="item" href="<?php echo Soma_const_url::inst()->get_package_detail(array('pid'=>$v['product_id'],'id'=>$inter_id) );?>">
    <div class=" squareimg">
    	<img src="<?php echo base_url('public/soma/images/default2.jpg'); ?>" data-original="<?php echo $v['face_img'];?>" class="lazy"/>
        <div class="webkitbox absolute bn_title justify">
        	<span class="bn_title_name"><?php echo $v['name']; ?></span>
            <?php if($show_y_flag): ?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $v['price_package']; ?></span>
        </div>
    </div>
    
    </a>
    <?php } ?>
</div>

<?php if(empty($packages)){?>
<div class="ui_none" onClick="history.back(-1)"><div>此分类暂未添加~<span class="color_link">返回</span></div></div>
<?php }?>


<script>
    window.onload=function(){
        $('.img').each(function(index, element) {
            $(this).height($(this).width());
        });
    }
</script>
</body>
</html>