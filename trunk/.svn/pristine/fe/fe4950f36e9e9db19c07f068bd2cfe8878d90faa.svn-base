
<link href="<?php echo base_url('public/soma/mooncake_v1/mooncake.css');?>" rel="stylesheet">
<script>
var redirectUrl = '<?php 
$redirect= urlencode( Soma_const_url::inst()->get_url('*/*/package_list_send', array('id'=> $this->inter_id) ) ); 
echo Soma_const_url::inst()->get_url('*/*/package_sending', array('id'=> $this->inter_id, 'redirect'=> $redirect ) );
?>';
var gid = '<?php echo $gid;?>';
var sign = '<?php echo $sign;?>';
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
            success: function () {location.href = redirectUrl+ '&gid='+ gid+ '&sign='+ sign;},
            cancel: function () {}
        });
        wx.onMenuShareAppMessage({
            title: '<?php echo $js_share_config["title"]?>',
            desc: '<?php echo $js_share_config["desc"]?>',
            link: '<?php echo $js_share_config["link"]?>', 
            imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
            //type: '', //music|video|link(default)
            //dataUrl: '', //use in music|video
            success: function () {location.href = redirectUrl+ '&gid='+ gid+ '&sign='+ sign;},
            cancel: function () {}
        });
<?php endif; ?>
});
</script>
<body>
<div class="pageloading"><p class="isload" style="margin-top:150px"><?php echo $lang->line('loading');?></p></div>
<!-- 以上为head -->

<div class="receive_list" style="background-image:url(<?php echo base_url('public/soma/mooncake_v1/bg3.jpg'); ?>)">
    <?php foreach( $orders['items'] as $k=>$v ):
    ?>
	<!--div class="receive_list_head center">
        <p><?php echo $v['name']?></p>
        <p><?php echo $orders['message']?></p>
    </div-->
    <?php endforeach;?>

    <!-- 群发赠送 -->
    <?php if( $orders['is_p2p'] == $gift_model::GIFT_TYPE_GROUP ): ?>
        <div class="receive_list_tips bg_main center">
            <span><?php echo $lang->line($orders['status_label']);?>&nbsp;</span>
            <!-- 此礼物是购买的 跳转至购买的订单详情
            此礼物是收到的礼物赠送，跳转至收礼详情页面 -->
                <!-- <a href="#" class="btn_minor color_000 h22">查看退回礼物</a> -->
            <?php if( $can_receive_repeat ):?>
                <a class="btn_minor h22 goOnSend"><?php echo $lang->line('continue_give');?></a>
            <?php elseif($orders['status']== 4): ?>
                <a class="btn_minor h22 viewReturn"><?php echo $lang->line('view');?></a>
            <?php endif;?>
        </div>
        <div class="center h26 pad3"><?php echo $lang->line('send_gift');?>：<?php echo $orders['items'][0]['name'];?></div>
        <?php if( count($receiveOrders)>0 ): ?>
            <ul class="list_style_2 bd">
                <?php foreach( $receiveOrders as $sk=> $sv ): ?>
                <li class="webkitbox">
                    <div class="img">
                        <div class="squareimg">
                            <!-- 用户头像 -->
                            <img src="<?php echo isset($sv['openid_headimg'])? $sv['openid_headimg']: base_url('public/soma/images/ucenter_headimg.jpg');?>">
                        </div>
                    </div>
                    <div>
                        <p><?php echo isset($sv['openid_nickname'])? $sv['openid_nickname']: $lang->line('your_friends');?></p>
                        <p><?php echo $sv['get_time'];?></p>
                    </div>
                    <div class="txt_r"><?php echo str_replace('[0]', $sv['get_qty'], $lang->line('received_copies'));?></div>
                </li>
                <?php endforeach;?>
            </ul>
        <?php else: ?>
            <div class="color_888 center" style="padding:20%;">
                <p><?php echo $lang->line('your_gift_not_receive');?></p>
                <p><?php echo $lang->line('click_continue_give');?></p>
                <p><?php echo $lang->line('gift_return_tip');?></p>
        		<p style="text-align:right; padding-top:10%"><img src="<?php echo base_url('public/soma/images/ico1.png'); ?>" style="width:20px;"></p>
            </div>
        <?php endif; ?>
    
    <!-- 单人赠送 -->
    <?php else:?>
        <div class="receive_list_tips bg_main center">
            <span><?php echo $orders['status_label'];?>&nbsp;</span>
            <!-- 此礼物是购买的 跳转至购买的订单详情
            此礼物是收到的礼物赠送，跳转至收礼详情页面 -->
                <!-- <a href="#" class="btn_minor color_000 h22">查看退回礼物</a> -->
            <?php if( $can_receive_repeat ):?>
                <a class="btn_minor h22 goOnSend"><?php echo $lang->line('continue_give');?></a>
            <?php elseif($orders['status']== 4): ?>
                <a class="btn_minor h22 viewReturn"><?php echo $lang->line('view');?></a>
            <?php endif;?>
        </div>
        <div class="center h26 pad3"><?php echo $lang->line('send_gift');?>：<?php echo $orders['items'][0]['name'];?></div>
        <?php if( !empty( $orders['openid_received'] ) ):?>
        <ul class="list_style_2 bd">
            <li class="webkitbox">
                <div class="img">
                    <div class="squareimg">
                        <!-- 用户头像 -->
                        <img src="<?php echo isset($orders['openid_received_headimg'])? $orders['openid_received_headimg']: base_url('public/soma/images/ucenter_headimg.jpg');?>">
                    </div>
                </div>
                <div>
                    <p><?php echo isset( $orders['openid_received_nickname'] )? $orders['openid_received_nickname']: $lang->line('your_friends'); ?></p>
                    <p><?php echo $orders['update_time'];?></p>
                </div>
                <div class="txt_r"><?php echo str_replace('[0]', 1, $lang->line('received_copies') );?></div>
            </li>
        </ul>
        <?php else: ?>
            <?php if($orders['status']== 4): ?>
                <div class="color_888 center" style="padding:20%;">
                    <p><?php echo $lang->line('gift_not_received_tip');?></p>
                    <p><?php echo $lang->line('click_to_check_return_gift_tip');?></p>
            		<p style="text-align:right; padding-top:10%"><img src="<?php echo base_url('public/soma/images/ico1.png'); ?>" style="width:20px;"></p>
                </div>
            <?php else: ?>
                <div class="color_888 center" style="padding:20%;">
                    <p><?php echo $lang->line('your_gift_not_receive');?></p>
                    <p><?php echo $lang->line('click_continue_give');?></p>
                    <p><?php echo $lang->line('gift_return_tip');?></p>
            		<p style="text-align:right; padding-top:10%"><img src="<?php echo base_url('public/soma/images/ico1.png'); ?>" style="width:20px;"></p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif;?>
    
</div>
<div class="ui_pull share_pull" style="display:none"></div>
<script>
$(document).ready(function() {
	
    $(".goOnSend").click(function(){
        $.MsgBox.Confirm("<?php echo $lang->line('continue_give_tip');?>",function(){
            <?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>
            $('.share_pull').show();
        },null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancel'); ?>");
    });
    
    $(".viewReturn").click(function(){
		$.ajax({
			type: 'GET',
			url: "<?php echo Soma_const_url::inst()->get_url('*/*/gift_return_ajax', array('id'=> $this->inter_id, 'gid' => $gid));?>",
			success: function(data){
				if(data.success) {
					location.href = data.redirect_url;
				} else {
					console.log(data);
				}
			} ,
			dataType: 'json'
		});
    });
	
});
</script>


</body>
</html>
