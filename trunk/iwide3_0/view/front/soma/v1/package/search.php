<body>
<link href="<?php echo get_cdn_url('public/soma/v1/v1.css'). config_item('css_debug');?>" rel="stylesheet">
<div class="pageloading"><p class="isload"><?php echo $lang->line('loading');?></p></div>
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

<div class="tp_list">
    <?php foreach($packages as $k=>$v){?>
        <?php
            // 是否显示¥符号
            $show_y_flag = true;
            if($v['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT)
            {
                $show_y_flag = false;
            }
        ?>
        <a href="<?php echo Soma_const_url::inst()->get_package_detail(array('pid'=>$v['product_id'],'id'=>$inter_id) );?>" class="item bg_fff">
            <div class="img" style="height: 193px;">
                <img src="<?php echo $v['face_img'];?>"/>

                <?php if(isset($v['scopes'])){ //专属价 ?>
                    <div class="j_label color_main f_s_12">
                    <?php echo $lang->line('exclusive'); ?>
                    </div>
                <?php }elseif(isset($v['killsec'])){ //有秒杀 ?>
                    <div class="j_label color_main f_s_12"><?php echo $lang->line('flash_sale');?></div>
                <?php } elseif(isset($v['groupon'])){ //有拼团 ?>
                    <div class="j_label color_main f_s_12"><?php echo $lang->line('group');?></div>
                <?php } elseif(isset($v['auto_rule'])){ //有活动 ?>
                    <div class="j_label color_main f_s_12"><?php echo $lang->line('offer');?></div>
                <?php } elseif($v['goods_type'] == Product_package_model::SPEC_TYPE_COMBINE){ //组合商品 ?>
                    <div class="j_label color_main f_s_12"><?php echo $lang->line('combined_product');?></div>
                <?php } ?>
            </div>
            <p class="h3 color_888"><?php echo $v['name'];?></p>

            <?php if(isset($v['scopes'])) { ?>
                <p class="item_foot">
                    <?php echo $lang->line('exclusive_price'); ?>
                    <em>|</em><?php if($show_y_flag):?>
                    <span class="color_main y"><?php else: ?><span class="color_main"><?php endif; ?><?php echo $v['scopes']['price'];?></span>
                </p>
            <?php } elseif(isset($v['killsec'])){ //有秒杀 ?>
                <p class="item_foot"><?php echo $lang->line('sale_price');?><em>|</em><?php if($show_y_flag): ?><span class="color_main y"><?php else: ?><span class="color_main"><?php endif; ?><?php echo $v['killsec']['killsec_price'];?></span></p>
            <?php } elseif(isset($v['groupon'])){ //有拼团 ?>
                <p class="item_foot"><?php echo $v['groupon']['group_count'];?><?php echo $lang->line('person_group');?><em>|</em><?php if($show_y_flag): ?><span class="color_main y"><?php else: ?><span class="color_main"><?php endif; ?><?php echo $v['groupon']['group_price'];?></span></p>
            <?php } else{ ?>
                <p class="item_foot"><?php echo $lang->line('surprise_offer');?><em>|</em><?php if($show_y_flag): ?><span class="color_main y"><?php else: ?><span class="color_main"><?php endif; ?><?php echo $v['price_package']?></span></p>
            <?php } ?>
        </a>
    <?php } ?>
</div>

<?php
        if(empty($packages)){
?>

<div class="ui_none" onClick="history.back(-1)"><div><?php $lang->line('category_not_add_tip');?><span style="color:blue;">(<?php echo $lang->line('back_to_before_tip');?>)</span></div></div>


<?php
        }
?>


<script>
    window.onload=function(){
        $('.img').each(function(index, element) {
            $(this).height($(this).width());
        });
    }
</script>
</body>
</html>