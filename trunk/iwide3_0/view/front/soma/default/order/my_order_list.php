
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

<body>
<div class="pageloading"><p class="isload" style="margin-top:150px"><?php echo $lang->line('loading');?></p></div>
<!-- 以上为head -->
<div class="header_fixed">
    <div class="drop_down webkitbox bd_bottom bg_fff center">
        <a class="color_main" href="<?php echo $my_orders_url; ?>"><?php echo $lang->line('my_puchases');?></a>
        <a  href="<?php echo $my_gifts_url; ?>"><?php echo $lang->line('my_gifts');?></a>
        <a href="<?php echo $my_mails_url; ?>"><?php echo $lang->line('mailed_goods');?></a>
    </div>
</div>
<div style=" height:3.2rem"></div>

<!-- 购买的商品 -->
<?php if(empty($orders)): ?>
    <!-- <div class="ui_none"><div>没有查询到相关的订单~</div></div> -->
    <div class="ui_none"><div><?php echo $lang->line('no_purchase_order');?>，<a class="color_link" href="<?php
        echo Soma_const_url::inst()->get_pacakge_home_page(); ?>"<?php echo $lang->line('purchaseing');?></a><br><?php echo $lang->line('contact_service_tip');?></div></div>
<?php else:
    foreach($orders as $k => $v): ?>
        <div class="order_list bd_top martop">
        <?php if($v['status'] == $salesModel::STATUS_GROUPING ){ ?>
            <a href="<?php echo Soma_const_url::inst()->get_url('*/groupon/groupon_detail',array('id'=>$inter_id,'grid'=> $activityGrouponModel->get_group_id_by_order_id($v['order_id'],$inter_id) )) ;?>">
        <?php }elseif($v['refund_status'] != $salesModel::REFUND_PENDING){ //退款订单 ?>
            <a href="<?php echo Soma_const_url::inst()->get_soma_refund_detail(array('id'=>$inter_id ,'oid'=> $v['order_id'],'bsn'=>$v['business'])) ;?>">
        <?php }else{ ?>
            <a href="<?php echo Soma_const_url::inst()->get_soma_order_detail(array('id'=>$inter_id ,'oid'=> $v['order_id'],'bsn'=>$v['business'])) ;?>">
         <?php } ?>
            <div class="item_header bg_fff pad3 webkitbox">
                <p><?php echo $lang->line('order_number');?>：<?php echo $v['order_id'];?></p>
                <p class="txt_r"><?php echo $v['create_time'];?></p>
            </div>
            <?php if( isset( $v['items'] ) ) foreach($v['items'] as $k2 => $item){?>
            <?php
                // 是否显示¥符号
                $show_y_flag = true;
                if($item['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT)
                {
                    $show_y_flag = false;
                }
            ?>
            <div class="item bd">
                <div class="img"><img class="lazy" src="<?php echo get_cdn_url('public/soma/images/default2.jpg'); ?>" data-original="<?php echo $item['face_img'];?>" /></div>
                <p class="txtclip h30"><?php echo $item['name'];?></p>
                <p class="txtclip color_555"><?php echo $item['hotel_name'];?></p>
                <p class="txtclip h30 color_main"><?php if($show_y_flag): ?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $item['price_package'];?> x<?php echo $item['qty'];?></span></p>
            </div>
            <?php } ?>
        </a>
        <div class="bg_fff pad3 bd_bottom webkitbox item_foot">
            <?php /*?><p class="">
                <?php
                if($v['settlement'] == 'groupon' && $v['refund_status'] ==  $salesModel::REFUND_ALL){
                    echo "拼团失败";
                } else if($v['settlement'] == 'groupon' && $v['status'] ==  $salesModel::STATUS_PAYMENT){
                    echo "拼团成功";
                }else{
                    echo $order_status[$v['status']];
                }
                ?>
                <?php if($v['settlement'] == 'groupon'){ ?> | 拼团订单<?php }?>
                <?php if($v['refund_status'] ==  $salesModel::REFUND_ALL){ ?> | 退款订单 <?php } ?>
            </p><?php */?>
            <p class="color_888">
                <?php
                    if( $v['consume_status'] == $salesModel::CONSUME_ALL ){
                        echo $lang->line('consumption_finish');
                    }else{
                        if($v['settlement'] == 'groupon' && $v['refund_status'] ==  $salesModel::REFUND_ALL){
                            echo $lang->line('group_fail');;
                        } else if($v['settlement'] == 'groupon' && $v['status'] ==  $salesModel::STATUS_PAYMENT){
                            echo $lang->line('group_success');
                        }else{
                            echo $lang->line($order_status_key[$v['status']]);
                        }
                    }
                ?>
                <?php if($v['settlement'] == 'groupon'){ ?>
                    | <?php echo $lang->line('group_order');?>
                <?php }?>
                <?php if($v['refund_status'] ==  $salesModel::REFUND_ALL){ ?>
                    | <?php echo $lang->line('refund_orders');?>
                <?php } ?>
            </p>
            <p class="color_main txt_r">
            <?php if($v['status'] == $salesModel::STATUS_GROUPING ){ ?>
                <a class="btn_void h24" href="<?php echo Soma_const_url::inst()->get_url('*/groupon/groupon_detail',array('id'=>$inter_id,'grid'=> $activityGrouponModel->get_group_id_by_order_id($v['order_id'],$inter_id) )) ;?>"><?php echo $lang->line('');?><?php echo $lang->line('status_check');?></a>
            <?php }elseif($v['refund_status'] != $salesModel::REFUND_PENDING){ //退款订单 ?>
                <a class="btn_void h24" href="<?php echo Soma_const_url::inst()->get_soma_refund_detail(array('id'=>$inter_id ,'oid'=> $v['order_id'],'bsn'=>$v['business'])) ;?>"><?php echo $lang->line('status_check');?></a>
            <?php }else{ ?>
                <?php if( $v['consume_status'] == $salesModel::CONSUME_ALL ):?>
                    <a class="btn_void h24" href="<?php echo Soma_const_url::inst()->get_soma_order_detail(array('id'=>$inter_id ,'oid'=> $v['order_id'],'bsn'=>$v['business'])) ;?>"><?php echo $lang->line('consumption_detail');?></a>
                <?php else:?>
                    <a class="btn_void h24" href="<?php echo Soma_const_url::inst()->get_soma_order_detail(array('id'=>$inter_id ,'oid'=> $v['order_id'],'bsn'=>$v['business'])) ;?>"><?php echo $lang->line('use');?></a>
                <?php endif;?>
            <?php } ?>
            </p>
        </div>
        </div>
<?php endforeach;
endif;
?>

</body>

</html>