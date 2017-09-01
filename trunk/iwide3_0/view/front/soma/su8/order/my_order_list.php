<body>
<div class="pageloading"><p class="isload">正在加载</p></div>
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

<div class="header_fixed">
    <div class="drop_down webkitbox border_bottom j_title bg_fff t_a_center">
        <div class="<?php if($type==1) echo 'j_active'; ?>">
            <a href="<?php echo Soma_const_url::inst()->get_soma_order_list(array());?>">
                <p class="">成功购买</p>
            </a>
        </div>
        <div class="<?php if($type==2) echo 'j_active'; ?>">
            <a href="<?php echo Soma_const_url::inst()->get_soma_order_list(array('t'=>2));?>">
                <p class="">已付款</p>
            </a>
        </div>
        <div class="<?php if($type==3) echo 'j_active'; ?>">
            <a href="<?php echo Soma_const_url::inst()->get_soma_order_list(array('t'=>3));?>">
                <p class="">已退款</p>
            </a>
        </div>
    </div>
</div>

<div class="ui_pull mask" style="display:none"></div>
<div style="padding-top:13%"></div>

<?php
    /*
    if(!empty($orders)){

    foreach($orders as $k => $v){
 ?>
        <div class="order_list border martop">
            <div class="item_header bgcolor_fff pad3 webkitbox">
                <p>订单：<?php echo $k;?></p>
                <p class="txt_r"><?php echo $v['create_time'];?></p>
            </div>
            <?php foreach($v['items'] as $vItem){?>
                <a href="#" class="item border">
                    <div class="img"><img src="<?php echo $vItem['face_img'];?>" /></div>
                    <p class="txtclip h2"><b><?php echo $vItem['name'];?></b></p>
<!--                    <p class="txtclip">台山碧桂园酒店</p>-->
                    <p class="txtclip h2 mcolor"><span class="y"><?php echo $vItem['price_package'];?></span></p>
                </a>
            <?php } ?>

            <div class="item_foot bgcolor_fff pad3 webkitbox">
                <p class="mcolor">
                    未支付
<!--                    待使用-->
<!--                    已使用-->
                </p>
                <p class="txt_r">
                    <a href="">赠送朋友</a>
                    <a href="">提前预约</a>
                    <a href="">到店用劵</a>
                </p>
            </div>

        </div>


    <?php
    }
}
    */
?>
<?php
if(empty($orders)){
 ?>
    <div class="ui_none"><div>没有查询到相关的订单~</div></div>

 <?php
}else{

    foreach($orders as $k => $v){

        ?>

        <?php if($v['status'] == $salesModel::STATUS_GROUPING ){ ?>
            <a href="<?php echo Soma_const_url::inst()->get_url('*/groupon/groupon_detail',array('id'=>$inter_id,'grid'=> $activityGrouponModel->get_group_id_by_order_id($v['order_id'],$inter_id) )) ;?>">
        <?php }elseif($v['refund_status'] != $salesModel::REFUND_PENDING){ //退款订单 ?>
            <a href="<?php echo Soma_const_url::inst()->get_soma_refund_detail(array('id'=>$inter_id ,'oid'=> $v['order_id'],'bsn'=>$v['business'])) ;?>">
        <?php }else{ ?>
            <a href="<?php echo Soma_const_url::inst()->get_soma_order_detail(array('id'=>$inter_id ,'oid'=> $v['order_id'],'bsn'=>$v['business'])) ;?>">
         <?php } ?>
        <div class="order_list border_top martop">
            <div class="item_header bgcolor_fff pad3 webkitbox">
                <p>订单编号：<?php echo $v['order_id'];?></p>
                <p class="txt_r"><?php echo $v['create_time'];?></p>
            </div>
            <?php foreach($v['items'] as $k2 => $item){?>
            <div class="item border">
                <div class="img"><img src="<?php echo $item['face_img'];?>" /></div>
                <p class="txtclip f_s_11"><b class="f_weigh_no"><?php echo $item['name'];?> </b></p>
                <p class="txtclip c_666"><?php echo $item['hotel_name'];?></p>
                <p class="txtclip h2 mcolor"><span class="y f_weight"><?php echo $v['subtotal'];?> x<?php echo $item['qty'];?></span></p>
            </div>
            <?php } ?>
        </div>
        </a>
        <div class="bgcolor_fff pad3 border_bottom">
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
            <span class="c_fc9 p_3 f_s_9 p_l_0">
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
            </span>
            <!-- <span class="c_fc9 p_3 f_s_9 p_l_0">退款中</span> -->
            <?php if($v['status'] == $salesModel::STATUS_GROUPING ){ ?>
                <a href="<?php echo Soma_const_url::inst()->get_url('*/groupon/groupon_detail',array('id'=>$inter_id,'grid'=> $activityGrouponModel->get_group_id_by_order_id($v['order_id'],$inter_id) )) ;?>">
                    <span class="f_right j_btns m_tt_1 b_1_0 f_s_9">查看</span>
            <?php }elseif($v['refund_status'] != $salesModel::REFUND_PENDING){ //退款订单 ?>
                <a href="<?php echo Soma_const_url::inst()->get_soma_refund_detail(array('id'=>$inter_id ,'oid'=> $v['order_id'],'bsn'=>$v['business'])) ;?>">
                    <span class="f_right j_btns m_tt_1 b_1_0 f_s_9">查看</span>
            <?php }else{ ?>
                <a href="<?php echo Soma_const_url::inst()->get_soma_order_detail(array('id'=>$inter_id ,'oid'=> $v['order_id'],'bsn'=>$v['business'])) ;?>">
                    <span class="f_right j_btns m_tt_1 b_1_0 f_s_9">使用</span>
            <?php } ?>
            </a>
            <!-- <a href="#"><span class="f_right j_btns m_tt_1 b_1_0 f_s_9">查看</span></a> -->
        </div>
    <?php
    }
}
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
    // $('.mask').click(function(){
    //     toclose();
    //     $('.drop_down_header').removeClass('silde');
    //     $('.drop_down_item').removeClass('silde').slideUp();
    // });

</script>
</html>