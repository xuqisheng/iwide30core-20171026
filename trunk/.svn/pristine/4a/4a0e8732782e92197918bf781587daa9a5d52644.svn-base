<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no,email=no">
    <meta name="ML-Config" content="fullscreen=yes,preventMove=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=320,initial-scale=1,user-scalable=0">
    <link href="<?php echo base_url("public/member/phase2/styles/global.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/mycss.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/green.css");?>" rel="stylesheet">
    <script src="<?php echo base_url("public/member/phase2/scripts/jquery.js"); ?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/alert.js"); ?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js"); ?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <title>优惠券详情</title>
    <style type="text/css">
        .card_fixed {
            background: rgba(0,0,0,0.8) url(<?php echo base_url("public/member/version4.0");?>/images/62714544492951331.png) no-repeat center top;
			background-size:94%;
            display: none;
        }

    </style>
</head>
<body class="">
<div class="use_coupon">
    <div class="u_c_box">
        <!--<span class="f_r h26 appoint">指定门店可用</span>-->
        <a href="<?=!empty($center_url)?$center_url:'';?>">
            <img class="hotel_img" src="<?=isset($public['logo'])?$public['logo']:'';?>">
            <span><?php echo isset($public['name'])?$public['name']:'';?></span>
        </a>
    </div>
    <div class="bg_fff use_coupo_txt">
        <!-- <img src="images/iconfont-erweima.png" /> -->
        <div class="single">
            <div class="room_na"><?=$card_info['title']?></div>

            <?php if(!isset($card_info['is_pms_card'])) {

                if (isset($card_info['card_type'])) {

                    ?>

                    <div class="discount">
                        <?php

                        switch ($card_info['card_type']) {
                            case 1:
                                // 抵用券样式
                                echo $card_info['reduce_cost'] . '元';
                                break;
                            case 2:
                                // 折扣券样式
                                echo $card_info['discount'] . '折';
                                break;
                            case 3:
                                // 兑换券样式
                                echo '兑换券';
                                break;
                            case 4:
                                // 储值卡样式
                                echo $card_info['money'] . '元';
                                break;
                            default:
                                //错误卡卷样式
                        }
                        ?>

                    </div>


                <?php
                }
            }
            ?>
            <?php if($card_info['is_active']=='t' && $my_card === true):?>
                <?php if($card_info['is_online']=='1'):?>
                    <div>仅线上可用</div>
                <?php elseif($card_info['is_online']=='2'):?>
                    <div>仅线下可用</div>
                <?php endif;?>
                <?php if($card_info['is_giving']=='t'):?>
                    <div>转赠中</div>
                <?php endif;?>
                <?php if($card_info['is_use']=='f' && $card_info['is_useoff']=='f' && $card_info['is_giving']=='f' && $card_info['is_active']=='t' && $card_info['card_type'] == 3 && $my_card === true):?>
                    <div class="use bg_main" onClick="toshow($('.fixed'))">立即使用</div>
                <?php endif;?>
            <?php else:?>
                <div><strong>优惠券已失效或已转赠</strong></div>
            <?php endif;?>
        </div>
        <div class="multi_line">
            <?php if($my_card === true):?>
            <div class="m_line_con">
                <div>券码:</div>
                <div><?=$card_info['coupon_code']?></div>
            </div>
            <?php endif;?>
            <div class="m_line_con">
                <div>使用说明:</div>
                <div><?=$card_info['description']?></div>
            </div>
            <div class="m_line_con">
                <div>使用提醒:</div>
                <div><?=$card_info['notice']?></div>
            </div>
            <div class="m_line_con">
                <div>可用时间:</div>
                <div><?=isset($card_info['expire_time'])?date('Y.m.d', $card_info['use_time_start']).'-'.date('Y.m.d', $card_info['expire_time']):''?></div>
            </div>
            <div class="m_line_con">
                <div>使用方式:</div>
                <div><?=isset($card_info['use_way'])?$card_info['use_way']:''?></div>
            </div>
        </div>
        <?php if($card_info['is_giving']=='f' && $card_info['is_active']=='t' && $my_card === true):?>
            <div class="list_style color_555" style="padding-left:0">
                <?php if($card_info && $card_info['is_online'] ==2){ ?>
                    <div class="input_item">
                        <span>消费码</span>
                        <input style="-webkit-box-flex:1" type="text" id='passwd' name="passwd" placeholder="可输入消费码使用" />
                    </div>
                <?php } ?>
                <?php if(!empty($card_info['is_can_give_friend']) && !empty($auth_gift) && $card_info['is_can_give_friend']=='t' && $card_info['is_giving']=='f' && $auth_gift === true):?>
                    <div class="fen_s arrow">转赠好友</div>
                <?php endif;?>
                <?php if(isset($card_info['header_url']) && !empty($card_info['header_url'])){ ?>
                    <a class="arrow" href="<?php echo $card_info['header_url'];?>">立即使用</a>
            <?php } ?>
                <?php if(isset($card_info['hotel_header_url'])  && !empty($card_info['hotel_header_url'])){ ?>
                    <a href="<?php echo $card_info['hotel_header_url']; ?>">去订房</a>
                <?php } ?>
                <?php if(isset($card_info['shop_header_url'])  && !empty($card_info['shop_header_url'])){ ?>
                    <a href="<?php echo $card_info['shop_header_url']; ?>" >去商城</a>
                <?php } ?>
                <?php if(isset($card_info['soma_header_url'])   && !empty($card_info['soma_header_url']) ){ ?>
                    <a href="<?php echo $card_info['soma_header_url']; ?>" >去团购</a>
                <?php } ?>
            </div>
        <?php endif;?>
        <?php if($card_info['is_use']=='f' && $card_info['is_useoff']=='f' && $card_info['is_online']=='2' && $card_info['is_giving']=='f' && $card_info['is_active']=='t' && $my_card === true):?>
            <img class="toggle" src="<?php echo base_url("public/member/phase2");?>/images/iconfont-erweima.png" onClick="toshow($('.fixed'))" />
        <?php endif;?>
    </div>
</div>
<div class="fixed">
    <div class="f_rwm">
        <div class="rwm_box">
            <div class="room_na"><?=$card_info['notice']?></div>
            <div class="room_na"><?=$card_info['title']?></div>
            <?php if(!isset($card_info['is_pms_card'])) {
                if (isset($card_info['card_type'])) {
                    ?>
                    <div class="discount">
                        <?php

                        switch ($card_info['card_type']) {
                            case 1:
                                // 抵用券样式
                                echo $card_info['reduce_cost'] . '元';
                                break;
                            case 2:
                                // 折扣券样式
                                echo $card_info['discount'] . '折';
                                break;
                            case 3:
                                // 兑换券样式
                                echo '兑换券';
                                break;
                            case 4:
                                // 储值卡样式
                                echo $card_info['money'] . '元';
                                break;
                            default:
                                //错误卡卷样式
                        }
                        ?>
                    </div>
                <?php
                }
            }
            ?>
            <div class="rwm_img">
                <img src="<?=!empty($qrcodecon_url)?"{$qrcodecon_url}&margin=0&data={$card_info['coupon_code']}":''?>" />
            </div>
            <div class="c_9b9b9b">使用时再出示此二维码哟~</div>
        </div>
        <div class="close" onClick="toclose()"><img src="<?php echo base_url("public/member/phase2");?>/images/529645798688903360.png"></div>
    </div>
</div>
<div class="card_fixed ui_pull" onClick="toclose()"></div>

<script>
<?php if(isset($wx_config) && !empty($wx_config) && !empty($auth_gift) && $auth_gift === true && $my_card === true):?>
wx.config({
	debug: false,
	appId: '<?php echo $wx_config["appId"]?>',
	timestamp: <?php echo $wx_config["timestamp"]?>,
	nonceStr: '<?php echo $wx_config["nonceStr"]?>',
	signature: '<?php echo $wx_config["signature"]?>',
	jsApiList: [<?php echo $js_api_list; ?>,'getLocation','openLocation']
});

wx.ready(function(){
	<?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
	<?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>
	<?php if( $js_share_config && !empty($card_info['is_can_give_friend']) && $card_info['is_can_give_friend']=='t' && $card_info['is_giving']=='f'): ?>
	wx.onMenuShareTimeline({
		title: '<?php echo !empty($js_share_config["title"])?$js_share_config["title"]:'分享到朋友圈';?>',
		link: '<?php echo $js_share_config["link"];?>',
		imgUrl: '<?php echo $js_share_config["imgUrl"];?>',
		success: function () {
			handle_share();
		},
		cancel: function () {
			$.MsgBox.Alert('您的优惠券发起转赠失败了，请刷新重新试试！');
		}
	});
	wx.onMenuShareAppMessage({
		title: '<?php echo !empty($js_share_config["title"])?$js_share_config["title"]:'发送给好友'?>',
		desc: '<?php echo $js_share_config["desc"];?>',
		link: '<?php echo $js_share_config["link"];?>',
		imgUrl: '<?php echo $js_share_config["imgUrl"];?>',
		success: function () {
			handle_share();
		},
		cancel: function () {
			$.MsgBox.Alert('您的优惠券发起转赠失败了，请刷新重新试试！');
		}
	});
	<?php endif; ?>
});

function handle_share() {
	var curl = "<?=!empty($gift_card_url)?$gift_card_url:''?>";
	var mcid = "<?=isset($card_info['member_card_id'])?$card_info['member_card_id']:0;?>";
	var module = "<?=$card_info['receive_module'];?>";
	var card_code = "<?=$card_info['coupon_code'];?>";
	var datas = {mcid:mcid,module:module,card_code:card_code};
	$.post(curl,datas,function (data) {
		var msg = '转赠成功';
		if(data.err>0) {
			msg = data.msg;
		}
		$.MsgBox.Alert(msg,function () {
			window.location.reload();
		});
	},'json');
}
<?php endif;?>

var check_useoff = null;
<?php if(!empty($check_useoff_url) && $my_card === true && $auth_useoff === true):?>
setTimeout(function () {
    check_useoff = setInterval(sp_check_useoff,3500); //设置一个定时器，获得定时器的ID
},3500);

function sp_check_useoff() {
    var url = "<?=$check_useoff_url?>",coupon_code = "<?=$card_info['coupon_code']?>";
    $.ajax({
        url:url,
        type:'POST',
        data:{coupon_code:coupon_code},
        dataType:'json',
        timeout:15000,
        success: function (result) {
            if(result.status == 1){
                if(check_useoff){
                    clearInterval(check_useoff);
                    check_useoff = null;
                }
                window.location.href=result.data;
            }
        }
    });
}
<?php endif;?>

$(function(){
	$('.toggle,.use').click(function(){
		toshow($('.fixed'))
	})
    $('.fen_s').click(function(){
	   toshow($('.card_fixed'));
	})
    //点击领取
    <?php if(!empty($card_info['is_online']) && $card_info['is_online']==2){ ?>
	$('#passwd').blur(function(){
		var passwd = $(this).val();
		if( passwd =='')return;
		$.MsgBox.Confirm('确定使用<?=$card_info['title']?>？',function(){
			pageloading();
            var post_url = "<?=!empty($passwduseoff_url)?$passwduseoff_url:''?>";
			var member_card_id = <?php echo $card_info['member_card_id']; ?>;
			$.post(post_url, {
				'member_card_id': member_card_id,
				'passwd':passwd,
			},
			function(data){
				removeload();
			   if(data.err==0){
					$('.priurl').attr('href',"<?=!empty($cardcenter_url)?$cardcenter_url:''?>");
					$.MsgBox.Alert( '使用成功');
			   }else{
					$.MsgBox.Alert( data['msg'] );
			   }
			}, "json");
		},null,'立即使用','再想想');
	});
    <?php } ?>
});
</script>
</body>
</html>
