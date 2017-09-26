
<link href="<?php echo get_cdn_url('public/soma/mooncake_v1/mooncake.css');?>" rel="stylesheet">
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
        <a href="<?php echo $my_orders_url; ?>" ><?php echo $lang->line('my_puchases');?></a>
        <a href="<?php echo $my_receive_url; ?>" class="color_main"><?php echo $lang->line('my_gifts');?></a>
        <a href="<?php echo $my_mails_url; ?>" ><?php echo $lang->line('mailed_goods');?></a>
    </div>
</div>
<div style=" height:3.2rem"></div>

<!-- 我的礼物 received -->
<?php if( is_array($gift_list) && count($gift_list)>0 ): ?>
<div class="order_list2 scroll pad3">
<?php 
/**
 * $gift_list 格式
array([1000001234] array(
    gift_id=> 1000001234,
    ...
    items=> array( [0]=> array
        item_id=> 765
        face_img=> ....
        name=> ...
        ....
    ) )
    receivers=> array(
        [0]=> array(),
    )
))
 */
    foreach($gift_list as $k => $v): $item= $v['items'][0]; //print_r($v);die;
    ?>
    <a href="<?php echo Soma_const_url::inst()->get_url('*/*/package_received', array('gid'=>$v['gift_id'],'id'=>$inter_id,'bsn'=>$business ) ); ?>" class="center item h28" giftType='1'>
        <div class="bg_fff item_header pad3">
            <div class="squareimg">
                <img src="<?php if( isset( $item['transparent_img'] ) && !empty( $item['transparent_img'] ) ){
                    echo $item['transparent_img'];
                }else{  echo $item['face_img']; } ?>" />

                <span class="tag h20 bg_main"><?php echo $title; ?></span>
            </div>
            <p class="h24">
                <?php echo $lang->line('gift_number');?>: <?php echo $v['gift_id']; ?>
            </p>
            <p class="txtclip">
                <?php if( isset( $v['openid_nickname'] ) ) echo $v['openid_nickname']; ?>
                <?php echo $lang->line('from');?>
                <?php echo $item['name']; ?>
            </p>
            <span class="btn_main h24">
                <?php if($v['is_p2p']== Soma_base::STATUS_TRUE):
                    echo $v['consum_qty']==0? $lang->line('used'): $lang->line('not_used');
                else:
                    echo ( $v['consum_qty']==$item['qty'] && $item['qty'] > 0 ) ? $lang->line('not_used'): ($v['consum_qty']==0? $lang->line('used'): $lang->line('using'));
                endif;
            ?></span>
        </div>
        <div class="item_hr"><img src="<?php echo get_cdn_url('public/soma/images/hr.jpg');?>" /></div>
        <div class="bg_fff item_foot">
            <p class="h36">
                <?php //接收份数的计算
                    if($v['is_p2p']==Soma_base::STATUS_TRUE ):
                        echo str_replace('[0]',1, $lang->line('total_pice'));
                    else:
                        echo str_replace('[0]',$v['per_give'], $lang->line('total_pice'));
                    endif;
                ?>
            </p>
            <p class="h24"><?php echo $v['create_time']; ?></p>
        </div>
    </a>
    <?php endforeach;?>
        <!-- <div class="ui_none"><div>没有查询到相关的订单~</div></div> -->
</div>
<?php else: ?>
    <!-- <div class="ui_none"><div>没有查询到相关的订单~</div></div> -->
    <div class="ui_none">
        <div>
            <?php echo $lang->line('no_received_gift');?><br>
            <?php echo $lang->line('contact_service_tip');?>
        </div>
    </div>
<?php endif; ?>

<div class="webkitbox center foot_fixed tab_foot  bd_top bg_fff">
    <div giftType='1'>
        <a href="<?php echo $my_receive_url; ?>">
            <p class="btn_main" ><?php echo $lang->line('received_gift');?></p>
        </a>
    </div>
    <div giftType='2'>
        <a href="<?php echo $my_send_url; ?>">
            <p class="btn_void" ><?php echo $lang->line('send_gift');?></p>
        </a>
    </div>
</div>
<script>
/* $('.tab_foot div').click(function(){
    var giftType = $(this).attr('giftType');
    $('p',this).addClass('btn_main').removeClass('btn_void');
    $(this).siblings().find('p').addClass('btn_void').removeClass('btn_main');
    if ( giftType == undefined) $('.order_list2 .item').show();
    else{
        $('.order_list2 .item').hide();
        $('.order_list2 .item[giftType='+giftType+']').show();
    }
}); */
</script>
</body>
</html>