<body>
<div class="pageloading"><p class="isload"><?php echo $lang->line('loading');?></p></div>
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

<ul class="bd list_style_1">
    <li class="input_item webkitbox justify">
        <span class="color_888"><?php echo $lang->line('order_number');?></span>
        <span><?php echo $orderDetail['order_id'];?></span>
        <?php if( isset( $record_detail ) ): ?>
            <span><a class="btn_void h24 xs color_888" href="<?php echo $product_record_url;?>" style="margin-right:8px;"><?php echo $lang->line('deal_snapshot');?></a></span>
        <?php else: ?>
            <span></span>
        <?php endif; ?>
    </li>
    <?php if($this->inter_id == 'a490782373' && !empty($saler_info_by_id['name'])): ?>
        <li class="input_item">
                   <span class="color_888">招募者</span>
                   <span><?php echo $saler_info_by_id['name']; ?></span>
        </li>
    <?php endif; ?>
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('order_time');?></span>
        <span><?php echo $orderDetail['create_time'];?></span>
    </li>

    <?php
        $show_y_flag = true;
        if( isset($orderDetail['items']) && isset($orderDetail['items'][0]) && $orderDetail['items'][0]['type'] == $ProductPackageModel::PRODUCT_TYPE_POINT)
        {
            $show_y_flag = false;
        }
    ?>
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('order_amount');?></span>
        <span class="color_main"><?php if($show_y_flag): ?>￥<?php endif; ?><?php echo $orderDetail['subtotal'];?></span>
        <?php if(!$is_groupon && ($orderDetail['subtotal']-$orderDetail['discount']) > 0): ?>
            <?php if($can_refund == $SalesOrderModel::CAN_REFUND_STATUS_SEVEN && !$isOverRefund): ?>
                <a class="btn_void h24 xs" href="<?php echo $refund_url; ?>" style="margin-right:8px;">
                    <?php echo $lang->line('7_refund_day');?>
                </a>
            <?php elseif($can_refund == $SalesOrderModel::CAN_REFUND_STATUS_ANY_TIME): ?>
                <a class="btn_void h24 xs" href="<?php echo $refund_url; ?>" style="margin-right:8px;">
                    <?php echo $lang->line('refund_any_time');?>
                </a>
            <?php else: ?>
            <?php endif; ?>
        <?php endif; ?>
        <!--
		<?php if($can_refund && !$isOverRefund && !$is_groupon && ($orderDetail['subtotal']-$orderDetail['discount']) >0): ?>
        <a class="btn_void h24 xs" href="<?php echo $refund_url; ?>" style="margin-right:8px;">退款</a>
        <?php endif; ?>
        -->
    </li>
    <?php if($orderDetail['discount']>0): ?>
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('dicount_amount');?></span>
        <span class="color_main">
            <?php if($show_y_flag): ?>￥<?php endif; ?>

            <?php echo $orderDetail['discount'];?>
        </span>
    </li>
    <?php endif; ?>
    <?php if($is_groupon): ?>
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('tips');?></span>
        <span class="color_888"><?php echo $lang->line('group_buy_no_refund_tip');?></span>
    </li>
    <?php endif; ?>
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('operat');?></span>
        <span class="color_main" id="operation">
            <?php if($invoice_enable): ?>
                <?php $invoice_url = Soma_const_url::inst()->get_url('*/invoice/show_invoice', array('id' => $inter_id, 'oid' => $orderDetail['order_id'])); ?>
        	   <a class="btn_void h24 xs" href="<?php echo $invoice_url; ?>" style="margin-right:8px;"><?php echo $lang->line('issue_invoice');?></a>
            <?php endif; ?>
        </span>
    </li>
</ul>

<?php
$is_show_empty= TRUE;
$is_show_mail= FALSE;
$group_qty = '';
$item_id = '';
$type = '';
// 显示¥符号
$show_y_flag = true;
if($orderDetail){
    $time = time();
    foreach( $orderDetail['items'] as $k => $v){
        $type = $v['type'];
        if($type == $ProductPackageModel::PRODUCT_TYPE_POINT)
        {
            $show_y_flag = false;
        }

        $expireTime = isset( $v['expiration_date'] ) ? strtotime( $v['expiration_date'] ) : NULL;

        $face_img = $v['face_img'];
        $name = $v['name'];
        $hotel_name = $v['hotel_name'];
        $price_package = $v['price_package'];

        if( $v['openid'] == $openid && empty($v['gift_id']) ){
            $group_qty = $v['qty'];
            $item_id = $v['item_id'];
        }
        
        if( $expireTime && $expireTime < $time ){
            $is_expire = TRUE;//已过期
            $group_url= '';
            
        } else {
            $is_expire = FALSE;//没过期

            //可以赠送
            if( $v['can_gift'] == $ProductPackageModel::CAN_T ){
                $group_url= Soma_const_url::inst()->get_url('*/gift/package_send',
                    array(
                        'aiid'=>$item_id,
                        'group'=>Soma_base::STATUS_TRUE,
                        'id'=>$inter_id,
                        'bsn'=>$business,
                        'send_from' => Gift_order_model::SEND_FROM_ORDER,
                        'send_order_id' => $orderDetail['order_id'],
                    )
                );
            }else{
                $group_url= '';
            }
            
        }
        //同一款产品
        for($i = 0; $i < $v['qty']; $i++){
		    $is_show_empty= FALSE;
            if( $v['can_mail'] == $can_mail_yes && !$is_expire ){
                $is_show_mail = TRUE;
            }
            ?>

<div class="order_list bd martop" data-item-id="<?php echo $v['item_id'];?>">
<!--<div class="item_foot bg_fff pad3 webkitbox">
    <p class="color_main"></p>
</div>-->
<?php if($v['openid']!= $openid || !empty($v['gift_id'])): ?>
<div class="is_open">&nbsp;</div>
<?php endif; ?>
    <div class="item bd_bottom bg_fff">
        <div class="tp_price">
            <?php // 2017年3月28日 按照产品要求取消显示无需预约字眼 ?>
            <?php if(false && $v['can_reserve'] != $ProductPackageModel::CAN_T){ ?>
                <p class="color_main"><?php echo $lang->line('no_need_appointment');?></p><!-- 使用状态 -->
            <?php } ?>
        </div>
        <div class="item_left">
        <?php if(!isset($record_detail)): ?>
            <a href="<?php echo Soma_const_url::inst()->get_url('*/package/package_detail', array( 'id'=>$inter_id, 'pid'=> $v['product_id'] ) )?>">
        <?php else: ?>
            <a href="<?php echo $product_record_url;?>">
        <?php endif; ?>
            <div class="img"><img src="<?php echo $v['face_img'];?>" /></div>
            <p class="txtclip h30"><?php echo $v['name'];?></p>
            <p class="txtclip color_888"><?php echo $v['hotel_name'];?></p>
            <p class="txtclip color_main"><?php if($show_y_flag): ?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $v['price_package'];?></span></p></a>
        </div>
    </div>
    <div class="item_foot bg_fff pad3 webkitbox">
        <p class="color_888">
        <?php if($v['openid']!= $openid || !empty($v['gift_id'])): ?>

            <?php echo $lang->line('sended');?>
            <?php echo isset($openids[$v['openid']])? $openids[$v['openid']]: $lang->line('friends'); ?>

            <?php echo $lang->line('get');?>

        <?php endif; ?>
        </p>
        <p class="txt_r color_main">
<?php if($v['openid']!= $openid || !empty($v['gift_id'])): ?>
            <a class="btn_void h24"  href="<?php echo Soma_const_url::inst()->get_url('*/gift/package_detail', array('gid'=>$v['gift_id'],'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                <?php echo $lang->line('view_details');?>
            </a>

        <?php elseif($orderDetail['refund_status'] == $SalesOrderModel::REFUND_PENDING && !$is_expire ): ?>

            <?php if ($v['type'] == $ProductPackageModel::PRODUCT_TYPE_PRIVILEGES_VOUCHER): ?>
                <a class="btn_void h24"  href="<?php echo Soma_const_url::inst()->get_url('*/*/voucher_detail', array('oid'=>$v['order_id'],'id'=>$inter_id,'used'=> Soma_base::STATUS_FALSE ) ); ?>">
                    <?php echo $lang->line('view_details');?>
                </a>
            <?php endif; ?>

            <?php if($v['can_reserve'] == $ProductPackageModel::CAN_T){ ?>
                <a class="btn_void h24"  href="<?php echo Soma_const_url::inst()->get_url('*/consumer/package_booking', array('aiid'=>$v['item_id'], 'aiidi'=>$i,'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                    <?php echo $lang->line('reservation_in_advance');?>
                </a>
            <?php } ?>

            <?php if($v['can_wx_booking'] == $ProductPackageModel::CAN_T){ ?>
                <a class="btn_void h24"  href="<?php echo Soma_const_url::inst()->get_url('*/booking/wx_select_hotel', array('aiid'=>$v['item_id'],'oid'=>$v['order_id'], 'aiidi'=>$i,'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                    <?php echo $lang->line('book_now');?>
                </a>
            <?php } ?>

            <?php if($v['can_gift'] == $ProductPackageModel::CAN_T){ ?>
                <a class="btn_void h24"  href="<?php echo Soma_const_url::inst()->get_url('*/gift/package_send',array('aiid'=>$v['item_id'], 'aiidi'=>$i,'id'=>$inter_id,'bsn'=>$business,'group'=>Soma_base::STATUS_FALSE,'send_from' => Gift_order_model::SEND_FROM_ORDER, 'send_order_id' => $orderDetail['order_id']));?>">
                    <?php echo $lang->line('gift_to_friend');?>
                </a>
            <?php } ?>
            <?php if($v['can_pickup'] == $ProductPackageModel::CAN_T): ?>
                <?php if($inter_id=='a483407432'):?>
                    <a class="btn_void h24"  href="<?php echo Soma_const_url::inst()->get_url('*/consumer/package_usage', array('aiid'=>$v['item_id'], 'aiidi'=>$i, 'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                        <?php echo $lang->line('exchange_in_shop');?>
                    </a>
                <?php else:?>
                    <a class="btn_void h24"  href="<?php echo Soma_const_url::inst()->get_url('*/consumer/package_usage', array('aiid'=>$v['item_id'], 'aiidi'=>$i, 'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                        <?php echo $lang->line('use_in_hotel');?>
                    </a>
                <?php endif;?>
            <?php endif; ?>
            
            <?php if ($v['type'] == $ProductPackageModel::PRODUCT_TYPE_PRIVILEGES_VOUCHER): ?>
                <a class="btn_void h24"  href="<?php echo Soma_const_url::inst()->get_url('*/*/voucher_sign', array('oid'=>$v['order_id'],'id'=>$inter_id, 'bsn'=>$business, 'aiid'=>$v['item_id'] ) ); ?>">
                    <?php echo $lang->line('add_card_pack');?>
                </a>
            <?php endif; ?>

        <?php elseif( $is_expire ): ?>
            <span class="h24"><?php echo $lang->line('expired');?></span>
        <?php endif;?>
        </p>
    </div>
</div>
        <?php
        }
    }
}
?>

<?php 
if($consumerDetail){
    foreach ($consumerDetail as $v2) {
        if (!empty($v2)) {
            foreach ($v2 as $v) {
		    $is_show_empty= FALSE;
?>
                <div class="order_list bd_bottom bg_fff martop">
                    
                   <!-- <div class="item_foot bg_fff pad3 webkitbox">
                        <p class="color_main"></p>
                    </div>-->
                    <div class="item bg_f6f m_t_3">
                        <div class="item_left">
                            <?php if(!isset($record_detail)): ?>
                                <a href="<?php echo Soma_const_url::inst()->get_url('*/package/package_detail', array('id'=> $this->inter_id, 'pid'=> $v['product_id'] ) )?>">
                            <?php else: ?>
                                <a href="<?php echo $product_record_url;?>">
                            <?php endif; ?>
                                <div class="img">
                                    <img src="<?php echo $v['face_img']; ?>"/>
                                </div>
                                <p class="txtclip f_s_11"><b class="f_weigh_no"><?php echo $v['name']; ?></b></p>
                                <p class="txtclip c_666"><?php echo $v['hotel_name'];?></p>
                                <p class="txtclip h2 color_main">
                                    <?php if($show_y_flag): ?>
                                        <span class="y f_weight">
                                    <?php else: ?>
                                        <span class="f_weight">
                                    <?php endif; ?>
                                            <?php echo $v['price_package'],'x',$v['consumer_qty']; ?>
                                        </span>
                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="item_foot bg_fff pad3 webkitbox" item-data="consumer" >
                        <p class="color_888">
                        <?php if(in_array($v['consumer_id'], $shipping_consumer_ids)): ?>
                            <?php
                            if(isset( $shipping_detail[ $v['consumer_id'] ]['status']) ):
                                echo $shipping_status_label[ $shipping_detail[ $v['consumer_id'] ]['status'] ] ;
                            else:
                                echo $lang->line('receive_orders');
                            endif;
                            ?>
                        <?php else: ?>
                            <?php if($type == $ProductPackageModel::PRODUCT_TYPE_PRIVILEGES_VOUCHER): ?>
                                <?php echo $lang->line('already_add_card_pack_tip'); ?>
                            <?php else: ?>
                                <?php
                                if(isset( $consumer_status[$v['status']]) ) :
                                    echo $consumer_status[$v['status']] ;
                                else:
                                    echo $lang->line('consume');
                                endif;
                                ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        </p>
                        <?php if( $openid== $v['openid']): //已经消费的不提供处理 ?>
        				<p class="txt_r color_main">
                            <?php if($type == $ProductPackageModel::PRODUCT_TYPE_PRIVILEGES_VOUCHER): ?>
                                <a class="btn_void h24"  href="<?php echo Soma_const_url::inst()->get_url('*/*/voucher_detail', array('oid'=>$v['order_id'],'id'=>$inter_id,'used'=> Soma_base::STATUS_TRUE ) ); ?>">
                                    <?php echo $lang->line('view_details');?>
                                </a>
                            <?php else: ?>
                                <?php if( isset( $consumer_status[$v['status']] ) && $v['status'] == $can_mail_status ): ?>
                                    <a class="btn_void h24" href="<?php $spid = $ConsumerShippingModel->get_shipping_id($v['order_id'],$v['consumer_id'],$inter_id,$business); echo Soma_const_url::inst()->get_url('*/consumer/shipping_detail', array('spid'=>$spid['shipping_id'], 'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                                        <?php echo $lang->line('view_details');?>
                                    </a>
                                <?php elseif( isset( $v['can_wx_booking'] ) && $v['can_wx_booking'] == Soma_base::STATUS_TRUE && $v['is_booking_hotel'] ): ?>
                                    <a class="btn_void h24" href="<?php echo Soma_const_url::inst()->get_url( '*/booking/success', array( 'cid'=>$v['consumer_id'], 'id'=>$inter_id, 'bsn'=>$business ) ); ?>">
                                        <?php echo $lang->line('view_details');?>
                                    </a>
                                <?php else: ?>
                                    <a class="btn_void h24" href="<?php echo Soma_const_url::inst()->get_url('*/consumer/package_review', array('ciid'=>$v['item_id'], 'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                                        <?php echo $lang->line('view_details');?>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </p>
                        <?php endif; ?>
                
                    </div>
                </div>
            <?php
            }
        }
    }
}
?>

<!-- 赠送 -->
<?php 
if($giftSendOrderDetail){
    foreach ($giftSendOrderDetail as $v2) {
        if (!empty($v2['items'])) {
            $is_show_empty= FALSE;
            foreach ($v2['items'] as $v) {
                ?>
                <div class="order_list bd_bottom bg_fff martop">
                    
                   <!-- <div class="item_foot bg_fff pad3 webkitbox">
                        <p class="color_main"></p>
                    </div>-->
                    <div class="item bg_f6f m_t_3">
                        <div class="item_left">
                        <?php if(!isset($record_detail)): ?>
                            <a href="<?php echo Soma_const_url::inst()->get_url('*/package/package_detail', array('id'=> $this->inter_id, 'pid'=> $v['product_id'] ) )?>">
                        <?php else: ?>
                            <a href="<?php echo $product_record_url;?>">
                        <?php endif; ?>
                                <div class="img"><img src="<?php echo $face_img; ?>"/></div>
                                <p class="txtclip f_s_11"><b class="f_weigh_no"><?php echo $name; ?></b></p>
                                <p class="txtclip c_666"><?php echo $hotel_name;?></p>
                                <p class="txtclip h2 color_main"><?php if($show_y_flag): ?><span class="y f_weight"><?php else: ?><span class="f_weight"><?php endif; ?><?php echo $price_package,'x',$v['qty']; ?></span></p>
                            </a>
                        </div>
                    </div>
                    <div class="item_foot bg_fff pad3 webkitbox" item-data="consumer" >
                    <!-- 赠送中，已赠送 -->
                        <p class="color_888"><?php echo $gift_status[$v2['status']] ?></p>
                        <p class="txt_r color_main">
                            <a class="btn_void h24" href="<?php echo Soma_const_url::inst()->get_url('*/gift/get_received_list', array('gid'=>$v['gift_id'], 'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                                <?php echo $lang->line('view_details');?>
                            </a>
                        </p>
                
                    </div>
                </div>
            <?php
            }
        }
    }
}
?>

<?php if($can_refund){ ?>
<!-- 
<div class="foot_btn">
	<a href="<?php echo $refund_url; ?>" class="color_fff">申请退款</a>
</div>
 -->
<?php } ?>

<?php if($is_show_empty==TRUE): ?>
    <div class="ui_success"><div> <?php echo $lang->line('gift_send_tip');?></div></div>
    
<!-- 推荐位  -->
<?php echo isset($block) ? $block: '';?>
<!-- 推荐位  -->
    
<?php endif; ?>

<script>
<?php if( $is_show_mail==TRUE ): ?>
var span = '<a class="btn_void h24 xs" href="<?php echo $mail_url; ?>" style="margin-right:8px;"><?php echo $lang->line('by_mail');?></a>';
$("#operation").append(span);
<?php endif; ?>

<?php if( $group_url && $group_qty>=2 ): ?>
var span = '<a class="btn_void h24 xs" href="<?php echo $group_url; ?>" style="margin-right:8px;"><?php echo $lang->line('gift_friends');?></a>';
$("#operation").append(span);
<?php endif; ?>
if( $("#operation *").length<=0) $("#operation").parent().hide();
</script>

</body>
</html>
