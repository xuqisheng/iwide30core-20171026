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
});
</script>

<?php $detail= $items[0]; $theme_id = $model->m_get('theme_id'); ?>
<body>
<div class="pageloading"><p class="isload" style="margin-top:150px"><?php echo $lang->line('loading');?></p></div>
<!-- 以上为head -->
<!--
收到的主题使用样式名是
theme0
theme1
theme2
theme3
-->
<style>
body,html{background:#ebebeb;}
.preview_theme.theme50{ <?php 
if( $theme_id == Soma_base::STATUS_TRUE ){
	/*颜色*/
	if( isset( $themeConfig['receive_main_color'] ) && !empty( $themeConfig['receive_main_color'] ) ){
		echo 'color:'.$themeConfig['receive_main_color'].';';
	}
	/*背景图*/
	if( isset( $themeConfig['receive_bg'] ) && !empty( $themeConfig['receive_bg'] ) ){
		echo 'background-image:url('.$themeConfig['receive_bg'].');';
	}
}
?>}
</style>
    <div class="preview_theme <?php $num = $theme_id - 1; if($num<0){$num =0;} echo 'theme'.$num;?>" style="position:relative;">
        <div class="relative themeparent scroll">
            <div class="themechid1">
            	<div class="squareimg"><img src="<?php if( isset( $detail['transparent_img'] ) && !empty( $detail['transparent_img'] ) ){
                    echo $detail['transparent_img'];
                }else{  echo $detail['face_img']; } ?>" /></div>
            </div>
            <div class="themechid2">
            	<p><img src="<?php echo isset( $fans_received['headimgurl'] ) ? $fans_received['headimgurl'] : get_cdn_url('public/soma/images/ucenter_headimg.jpg'); ?>"></p><!--  用户头像 -->
                <p>
                    <span>
                        <?php echo isset( $fans_received['nickname'] ) ? $fans_received['nickname'] : $lang->line('your_friends'); ?>
                    </span>
                </p>
                <p><?php echo $model->m_get('message'); ?></p>
            </div>
            <div class="themechid3"><?php echo $detail['name'];?></div>
			<div class="themechid4">
			<?php $per_give = $model->m_get('per_give') ? $model->m_get('per_give') : 1; 
            		$number = $per_give - $detail['qty'];
					if( $number == 0 ){
						echo $lang->line('not_used'),$number,'/',$per_give;
					}elseif( $number == $per_give  ){
						$html_a = '<a href="'.$go_url.'">'. $lang->line('used') .$number.'/'.$per_give.'</a>';
						echo $html_a;
					}else{
						echo $lang->line('using'),$number,'/',$per_give;
					}
				?></p>
            </div>
            <?php if( $items[0]['qty'] > 0 ):?>
            <div class="webkitbox themechid5">
            	<?php if( $detail['can_reserve'] == Soma_base::STATUS_TRUE ): ?>
                <div>
					<a href="<?php $i=0; echo Soma_const_url::inst()->get_url('*/consumer/package_booking', array('aiid'=>$items[0]['item_id'], 'aiidi'=>$i,'id'=>$inter_id,'bsn'=>$business ) );?>" class="btn_void h24">
						<?php echo $lang->line('reservation_in_advance'); ?>
					</a>
				</div>
                <?php endif;?>

                <?php if( $detail['can_mail'] == Soma_base::STATUS_TRUE ): ?>
                <div>
					<a href="<?php echo $mail_url;?>" class="btn_void h24">
						<?php echo $lang->line('deliver_to_home');?>
					</a>
				</div>
                <?php endif;?>

				<div>
					<a href="<?php echo $send_friend;?>" class="btn_void h24">
						<?php echo $lang->line('send_a_friend'); ?>
					</a>
				</div>
                <?php if( $detail['can_pickup'] == Soma_base::STATUS_TRUE ): ?>
                <div>
					<a href="<?php echo $usage_url;?>" class="btn_void h24">
						<?php echo $lang->line('use_in_hotel');?>
					</a>
				</div>
                <?php endif;?>

				<?php if( $detail['type'] == $model::PRODUCT_TYPE_PRIVILEGES_VOUCHER ): ?>
					<div>
						<a href="<?php echo $usage_url;?>" class="btn_void h24">
							<?php echo $lang->line('add_card_pack');?>
						</a>
					</div>
				<?php endif; ?>

                <?php if( $detail['can_wx_booking'] == $productionPackageModel::CAN_T ): ?>
                <div>
					<a href="<?php echo $booking_url;?>" class="btn_void h24">
						<?php echo $lang->line('book_now');?>
					</a>
				</div>
                <?php endif; ?>
                <div>
					<div class="btn_void h24" id="addUrl">
						<?php echo $lang->line('use_later');?>
					</div>
				</div>
            </div>
            <?php else:?>
            <a href="<?php echo Soma_const_url::inst()->get_url('*/package/index', array( 'id'=>$inter_id,'bsn'=>$business ) );?>" class="themechid5" >
                <?php echo $lang->line('enter_home_page');?><em class="iconfont">&#xe61B;</em>
            </a>
            <?php endif;?>

            <a class="themebottom" href="<?php echo Soma_const_url::inst()->get_url('*/gift/have_get_received', array( 'id'=>$inter_id,'bsn'=>$business,'gid'=>$model->m_get('gift_id') ) );?>">
				<?php echo $lang->line('other_people_received');?><em class="iconfont">&#xe61B;</em>
            </a>
        </div>
    </div>
<!--     <div class="preview_theme theme3">
        <div class="relative themeparent scroll">
            <div class="themechid1"><p><span>马丁</span>送您一份中秋新意</p><p>恭祝您全家团团圆圆</p></div>
            <div class="themechid2"><img src="images/eg1.png" /></div>
            <div class="webkitbox">
                <div><span class="btn_main h24">邮寄到家</span></div>
                <div><span class="btn_main h24">送给朋友</span></div>
                <div><span class="btn_main h24">到店用劵</span></div>
            </div>
            <div class="themechid3">暂不使用，放至订单中心<em class="iconfont">&#xe61B;</em></div>
        </div>
    </div> -->
</body>
<script>
$(document).ready(function() {
var imgrate  = 480/770;
var _w = $(window).width();
$('.preview_theme').height(_w /imgrate);
function checkFollow(){
	//异步查询是否关注
	$.ajax({
		type: 'POST',
		url: "<?php echo $check_follow_ajax; ?>",
		dataType: 'json',
		success:function(json){
			var tips = '';
			var leftLink = '';
			var leftUrl = '#';
			var rightLink = '';
			var rightUrl = '#';
			if( json.status == 2 ){<?php //2为未关注用户，json.data 为图文URL  ?>
				// alert( json.message );
				// $("#addUrl").attr('href',json.data);
				tips = json.message;
				leftLink = '<?php echo $lang->line('use_now');?>';
				rightLink = '<?php echo $lang->line('attention');?>';
				rightUrl = json.data;
			} else {<?php //2为关注用户，json.data 为首页URL  ?>
				// alert( json.message );
				// $("#addUrl").attr('href',json.data);
				tips = json.message;
				leftLink = '<?php echo $lang->line('continue_use');?>';
				rightLink = '<?php echo $lang->line('to_online_shop');?>';
				rightUrl = json.data;
			}
			$.MsgBox.Confirm(tips,function(){
				window.location.href=rightUrl;
			},function(){
				window.location.href=leftUrl;					
			},rightLink,leftLink);
		}
	});
}
// checkFollow();
// setInterval(checkFollow,5000);

$("#addUrl").click(function(){
	checkFollow();
	return;
});

});
</script>
</html>
