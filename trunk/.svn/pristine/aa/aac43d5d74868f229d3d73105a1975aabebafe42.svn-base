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
<style>
.item_left .y{display:none}
</style>
<?php if( isset($fans) ): ?>
<div id="gift_notice" class="h30 center color_888 pad3">
	<?php
        $nickname = empty($fans['nickname'])?  '对方': '<span class="color_main">'. $fans['nickname']. '</span> ';
        echo str_replace('[0]', $nickname, $lang->line('received_your_gift'));
    ?>
</div>
<?php endif; ?>
<ul class="bd list_style_1">
	<li class="input_item">
        <span class="color_888"><?php echo $lang->line('gift_number');?></span>
        <span><?php echo $detail['gift_id']; ?></span>
    </li>
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('gift_time');?></span>
        <span><?php echo $detail['create_time']; ?></span>
    </li>
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('expiration');?></span>
        <span><?php echo $a_items[0]['expiration_date']; ?></span>
    </li>
    <li class="input_item">
        <span class="color_888"><?php echo $lang->line('operat');?></span>
        <span class="color_main" id="operation"></span>
    </li>
</ul>
<?php
$is_show_empty= TRUE;
$is_show_mail= FALSE;
$sender_openid = FALSE;

//显示资产库item，对应的qty循环输出
foreach( $a_items as $k => $v): 

    $face_img = $v['face_img'];
    $name = $v['name'];
    $hotel_name = $v['hotel_name'];
    $price_package = $v['price_package'];

    $show_y_flag = true;
    if($v['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT)
    {
        $show_y_flag = false;
    }

    if( $v['openid'] == $openid && !empty($v['gift_id']) ){
        $group_qty = $v['qty'];
        $item_id = $v['item_id'];
    }

    //同一款产品
    $time = time();
    for($i= 0; $i<$v['qty']; $i++):
        $expireTime = isset( $v['expiration_date'] ) ? strtotime( $v['expiration_date'] ) : NULL;
        $is_expire = FALSE;//没过期
        if( $expireTime && $expireTime < $time ){
            $is_expire = TRUE;//已过期
            $group_url= '';
        }else {
            $group_url= Soma_const_url::inst()->get_url('*/gift/package_send',array('aiid'=>$item_id, 'group'=>Soma_base::STATUS_TRUE, 'id'=>$inter_id,'bsn'=>$business ));
            
        }

		$is_show_empty= FALSE;
        if( $v['can_mail'] == $can_mail_yes && !$is_expire ){
            $is_show_mail = TRUE;
        }
        // $is_show_mail = TRUE;
?>
        <div class="order_list bd_bottom martop" item-data="aiid-<?php echo $v['item_id']; ?>">
            <div class="item bg_fff">
                <div class="tp_price">
                    <?php if($v['can_reserve'] != $product_model::CAN_T){ ?>
                        <p class="color_main">
                            <?php echo $lang->line('no_need_appointment'); ?>
                        </p>
                    <?php } ?>
                </div>
                <div class="item_left"><a href="<?php echo Soma_const_url::inst()->get_url('*/package/package_detail', 
            array('id'=> $this->inter_id, 'pid'=> $v['product_id'] ) )?>"><div class="img"><img src="<?php echo $v['face_img'];?>" /></div>
                    <p class="txtclip"><b><?php echo $v['name'];?></b></p>
                    <p class="txtclip"><?php echo $v['hotel_name'];?></p>
                    <p class="txtclip color_main"><?php if($show_y_flag): ?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $v['price_package'];?></span></p></a>
                </div>
            </div>
            <div class="item_foot bg_fff pad3 webkitbox" item-data="consumer" >
    <?php if( !$is_expire )://没有过期 ?>        
        <?php if( $openid== $v['openid']): //仅针对自己的资产才可以操作 ?>
                    <p class="txt_r btn">
            <?php if($v['can_reserve'] == $product_model::CAN_T): ?>
                <a href="<?php echo Soma_const_url::inst()->get_url('*/consumer/package_booking', array('aiid'=>$v['item_id'], 'aiidi'=>$i,'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                    <?php echo $lang->line('reservation_in_advance'); ?>
                </a>
            <?php endif; ?>
            <?php if($v['can_gift'] == $product_model::CAN_T): ?>
                <a href="<?php echo Soma_const_url::inst()->get_url('*/*/package_send', array('aiid'=>$v['item_id'],'id'=>$inter_id,'send_from'=>$send_from,'send_order_id'=>$send_order_id,'bsn'=>$business ) ); ?>">
                    <?php echo $lang->line('gift_a_friend'); ?>
                </a>
            <?php endif; ?>
            <?php if($v['can_pickup'] == $product_model::CAN_T): ?>
                <?php if($inter_id=='a483407432'):?>
                    <a href="<?php echo Soma_const_url::inst()->get_url('*/consumer/package_usage', array('aiid'=>$v['item_id'], 'aiidi'=>$i, 'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                        <?php echo $lang->line('exchange_in_shop'); ?>
                    </a>
                <?php else:?>
                    <a href="<?php echo Soma_const_url::inst()->get_url('*/consumer/package_usage', array('aiid'=>$v['item_id'], 'aiidi'=>$i, 'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                        <?php echo $lang->line('use_in_hotel'); ?>
                    </a>
                <?php endif;?>
            <?php endif; ?>

            <?php if( $v['type'] == $product_model::PRODUCT_TYPE_PRIVILEGES_VOUCHER ): ?>
                <a href="<?php echo Soma_const_url::inst()->get_url('*/order/voucher_sign', array('aiid'=>$v['item_id'], 'aiidi'=>$i, 'id'=>$inter_id, 'bsn'=>$business, 'gid' => $detail['gift_id'] ) ); ?>">
                    <?php echo $lang->line('add_card_pack'); ?>
                </a>
            <?php endif; ?>
                    </p>
        <?php else: $sender_openid = TRUE;//用于排除发送人邮寄按钮 ?>
        <?php endif; ?>
    <?php else: ?>
        <span class="h24">
            <?php echo $lang->line('expired');?>
        </span>
    <?php endif; ?>            
            </div>
        </div>
<?php
    endfor;
endforeach;

//asset_item 对应 consumer item，单条输出
foreach( $c_items as $k => $v): 
		$is_show_empty= FALSE;
?>
<?php
    // 跟随资产
    // $show_y_flag = true;
    // if($v['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT)
    // {
    //     $show_y_flag = false;
    // }
?>
        <div class="order_list bd_bottom bg_fff martop" item-data="aiid-<?php echo $v['item_id']; ?>">
            <div class="item">
                <div class="tp_price">
                    <p class="color_main">
                        <?php echo $lang->line('consume'); ?>
                    </p>
                </div>
                <div class="item_left"><a href="<?php echo Soma_const_url::inst()->get_url('*/package/package_detail', 
            array('id'=> $this->inter_id, 'pid'=> $v['product_id'] ) )?>">
                    <div class="img"><img src="<?php echo $v['face_img'];?>" /></div>
                    <p class="txtclip h30"><b><?php echo $v['name'];?></b></p>
                    <p class="txtclip"><?php echo $v['hotel_name'];?></p>
                    <p class="txtclip h30 color_main"><?php if($show_y_flag): ?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $v['price_package'];?></span></p></a>
                </div>
            </div>
            <div class="item_foot bg_fff pad3 webkitbox" item-data="consumer" >
                <p class="color_main">
                    <?php
                        if(isset( $consumer_status[$v['status']]) ):
                            echo $consumer_status[$v['status']] ;
                        else:
                            echo $lang->line('consume');
                        endif;
                    ?>
                </p>
    <?php if( $openid== $v['openid']): //已经消费的不提供处理 ?>
        <p class="txt_r btn">
            <a href="<?php echo Soma_const_url::inst()->get_url('*/consumer/package_review', array('ciid'=>$v['item_id'], 'id'=>$inter_id,'bsn'=>$business ) ); ?>">
                <?php echo $lang->line('view_details');?>
            </a>
        </p>
    <?php else: $sender_openid = TRUE;//用于排除发送人邮寄按钮 ?>
    <?php endif; ?>
            </div>
        </div>
<?php endforeach; ?>

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
                        <a href="<?php echo Soma_const_url::inst()->get_url('*/package/package_detail', 
            array('id'=> $this->inter_id, 'pid'=> $v['product_id'] ) )?>">
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

<?php if($is_show_empty==TRUE): ?>
    <div class="ui_none">
        <div>
            <?php echo $lang->line('gift_send_out_tip');?>
        </div>
    </div>
    
<!-- 推荐位  -->
<?php echo isset($block) ? $block: '';?>
<!-- 推荐位  -->
    
<?php endif; ?>

<script>
<?php $span = '<span class="color_main"><a href="'.$mail_url.'">' . $lang->line('by_mail') . '</a></span>';
 if( $is_show_mail==TRUE ): 
?>
        var span = '<a class="btn_void h24 xs" href="<?php echo $mail_url; ?>"><?php echo $lang->line('by_mail'); ?></a>';
        $("#operation").append(span);
        $("#gift_notice").hide();
        // document.getElementById("operation").appendChild("<?php echo $span; ?>");
<?php else: ?>
        $("#operation").parent().hide();
        // document.getElementById("operation").appendChild("<?php echo $span; ?>");
<?php endif; ?>
<?php if( !$sender_openid ): ?>
        $("#gift_notice").hide();
<?php endif; ?>

<?php if( $group_url && $group_qty>=2 ): ?>
var span = '<a class="btn_void h24 xs" href="<?php echo $group_url; ?>" style="margin-right:8px;"><?php echo $lang->line('gift_friends');?></a>';
$("#operation").append(span);
<?php endif; ?>
</script>
</body>
</html>
