
<link href="<?php echo base_url('public/soma/mooncake_v1/mooncake.css');?>" rel="stylesheet">
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
<div class="pageloading"><p class="isload" style="margin-top:150px"><?php echo $lang->line('loading');?></p></div>
<!-- 以上为head -->
<div class="header_fixed">
    <div class="drop_down webkitbox bd_bottom bg_fff center">
        <a href="<?php echo $my_orders_url; ?>"><?php echo $lang->line('my_puchases');?></a>
        <a href="<?php echo $my_gifts_url; ?>"><?php echo $lang->line('my_gifts');?></a>
        <a class="color_main" href="<?php echo $my_mails_url; ?>"><?php echo $lang->line('mailed_goods');?></a>
    </div>
</div>
<div style=" height:3.2rem"></div>

<?php if(empty($orders)): ?>
<div class="ui_none">
    <div>
        <?php echo $lang->line('good_not_mailed_tip');?><br>
        <?php echo $lang->line('contact_service_tip');?>
    </div>
</div>
<?php
else:
    foreach($orders as $k => $v):
?>
    <div class="order_list bd_top martop">
        <div class="item_header bg_fff pad3 webkitbox">
            <p><?php echo $lang->line('purchase_history');?>：<?php echo $v['consumer_id'];?></p>
            <p class="txt_r"><?php echo $v['create_time'];?></p>
        </div>
        <?php foreach($v['items'] as $vItem){?>
            <?php
                $show_y_flag = true;
                if($vItem['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT)
                {
                    $show_y_flag = false;
                }
            ?>
            <a href="javascript:;<?php //echo Soma_const_url::inst()->get_url('*/*/package_detail', array('gid'=>$v['gift_id'],'id'=>$inter_id,'bsn'=>$business ) );?>" class="item bd">
                <div class="img"><img class="lazy" src="<?php echo base_url('public/soma/images/default2.jpg'); ?>" data-original="<?php echo $vItem['face_img'];?>" /></div>
                <p class="txtclip h30"><?php echo $vItem['name'];?></p>
                <p class="txtclip color_555"><?php echo $vItem['hotel_name'];?></p>
                <p class="txtclip h30 color_main"><?php if($show_y_flag): ?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $vItem['price_package'];?></span></p>
            </a>
        <?php } ?>

        <div class="bg_fff pad3 bd_bottom webkitbox item_foot">
            <!-- <p class="color_888"><?php echo $status_label[$v['status']];?></p> -->
            <p class="color_888"><?php echo $lang->line($status_label_key[$v['status']]);?></p>
            <p class="txt_r color_main">
            	<a class="btn_void h24" href="<?php echo Soma_const_url::inst()->get_url('*/*/shipping_detail', array('spid'=>$v['shipping_id'],'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                    <?php echo $lang->line('status_check');?>
                </a>
            </p>
        </div>
    </div>
<?php
    endforeach;
endif;
?>
</body>
<script>

//     $('.drop_down_menu').click(function(e){
//         e.stopPropagation();
//         if($('.mask').is(':hidden'))toshow($('.mask'));
//         var o =$(this).siblings();
//         o.find('.drop_down_item').hide();
//         o.find('.drop_down_header').removeClass('silde');
//         $('.drop_down_header',this).addClass('silde');
//         $('.drop_down_item',this).slideDown();
//     });
//     $('.drop_down_item p').click(function(e){
//         e.stopPropagation();
//         location.href = $(this).attr('ref');
// //        $(this).parent().siblings('.drop_down_header').html($(this).html());
// //        $(this).parents('.drop_down_menu').addClass('cur').siblings().removeClass('cur');
// //        to_slideup();
//     });
//     $('.mask').click(function(){
//         toclose();
//         $('.drop_down_header').removeClass('silde');
//         $('.drop_down_item').removeClass('silde').slideUp();
//     });

</script>
</html>