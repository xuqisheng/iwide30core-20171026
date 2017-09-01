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
<script src="<?php echo base_url("public/member/nvitedkim/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/nvitedkim/js/ui_control.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
<link href="<?php echo base_url("public/member/nvitedkim/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/nvitedkim/css/mycss.css");?>" rel="stylesheet">
<title><?php echo $dis_conf['page_title']?></title>
<style type="text/css">
    .bg1{background: url(<?php echo isset($dis_conf['background'])?$dis_conf['background']:'';?>) no-repeat;background-size: 100% 100%;}
    .times{left: 45%;}
</style>
</head>
<body class="bg1">
<script type="text/javascript">
    <?php if(isset($info) && !empty($info) && $info['start_time']<=time() && $info['isopen']=='1'):?>
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

        <?php if( $js_share_config ): ?>
        wx.onMenuShareTimeline({
            title: '<?php echo !empty($js_share_config["title"])?$js_share_config["title"]:'分享到朋友圈';?>',
            link: '<?php echo $js_share_config["link"];?>&channel=2',
            imgUrl: '<?php echo $js_share_config["imgUrl"];?>',
            success: function () {},
            cancel: function () {}
        });
        wx.onMenuShareAppMessage({
            title: '<?php echo !empty($js_share_config["title"])?$js_share_config["title"]:'发送给好友'?>',
            desc: '<?php echo $js_share_config["desc"];?>',
            link: '<?php echo $js_share_config["link"];?>&channel=2',
            imgUrl: '<?php echo $js_share_config["imgUrl"];?>',
            success: function () {},
            cancel: function () {}
        });

        <?php endif; ?>
    });
    <?php endif; ?>
</script>
<div class="pageloading"><p class="isload">正在加载</p></div>
	<div class="times color_fff h32">
<!--        --><?php //if(isset($info['start_time']) && !empty($info['start_time'])) echo date('Y.m.d',$info['start_time']).' - ';?>
<!--        --><?php //if(isset($info['end_time']) && !empty($info['end_time'])) echo date('Y.m.d',$info['end_time']);?>
    </div>
	<div class="con_txt color_fff center">
        <?php
        if(isset($dis_conf['act_config']['steps']) && !empty($dis_conf['act_config']['steps']))
            echo $dis_conf['act_config']['steps'];
        else
            echo '';
        ?>

        <?php
        if(!isset($info) || empty($info)){
            echo '抱歉，活动消失了...';
        }else{
            if($info['start_time']>time()){
                echo '來早啦，活动还没开始呢！';
            }elseif ($info['isopen']=='2'){
                echo '活动已关闭';
            }
        }
        ?>
        <?php if(isset($info) && !empty($info) && $info['start_time']<=time() && $info['isopen']=='1'):?>
        <?php if(isset($reward_info['mode']) && $reward_info['mode']=='1'):?>
        <div class="calculation">
            <div class="cal_bg"></div>
            <div class="ruler">
                <div class="triangle"></div>
            </div>
        </div>
        <div class="number center h24">
            <?php echo $total_number;?>/<?php echo $full_number;?>
        </div>
        <?php endif;?>
        <?php endif;?>
    </div>
<?php if(isset($info) && !empty($info) && $info['start_time']<=time() && $info['isopen']=='1'):?>
    <div class="webkitbox center btn_list p_t_85">
    	<a class="ico_btn btn_toface" href="javascript:;">
            <p class="ic_img"><img src="<?php echo base_url("public/member/nvitedkim");?>/images/1.png"/></p>
            <p><?php echo $invite_title;?></p>
        </a>
    	<a class="ico_btn btn_fenxiang" href="javascript:;">
            <p class="ic_img"><img src="<?php echo base_url("public/member/nvitedkim");?>/images/2.png"/></p>
            <p><?php echo $share_title;?></p>
        </a>
    	<a class="ico_btn" href="<?php echo EA_const_url::inst()->get_url('*/*/myrecord',array('id'=>isset($user['inter_id'])?$user['inter_id']:0));?>">
            <p class="ic_img"><img src="<?php echo base_url("public/member/nvitedkim");?>/images/3.png"/></p>
            <p><?php echo $my_title;?></p>
        </a>
    </div>
    <div class="fix">
    	<div class="to_face center">
        	<div class="b_close"><img src="<?php echo base_url("public/member/nvitedkim");?>/images/close.png" /></div>
        	<div class="qr_code"><img src="<?php echo site_url('membervip/invitatedkim/qrcode').'?url_code='.$url_code;?>" /></div>
            <div class="h24 code_txt"><?php echo !empty($js_share_config["title"])?$js_share_config["title"]:'扫描二维码分享';?><?php echo !empty($js_share_config["desc"])?'<br>('.$js_share_config["desc"].')':'';?></div>
        </div>
        <div class="fen_xiang"><img src="<?php echo base_url("public/member/nvitedkim");?>/images/fensan_03.png"/></div>
    </div>
<?php endif;?>
<script>
$(function(){
	if($(window).height()==416){  //iphone 4s兼容
		$('.to_face').css("width","66%");
		$('.code_txt').css("font-size","0.785rem")
	}
	if($('.con_txt').height()>232){
		$('.con_txt').css("padding","10% 0");
	}
    <?php if(isset($info) && !empty($info) && $info['start_time']<=time() && $info['isopen']=='1'):?>
    <?php if(isset($reward_info['mode']) && $reward_info['mode']=='1'):?>
	var arr=$('.number').html().split("/");	
	var percentage=Math.floor(arr[0]/arr[1]*100);
	if(percentage>100){percentage=100}
	$(".cal_bg").css("width",percentage+"%");
	$(".ruler").css("left",percentage+"%");
    <?php endif;?>
    <?php endif;?>
    $('.btn_toface').click(function(){
		$('.fix,.to_face').show();
		$('.fix,.b_close').click(function(){
			$('.fix,.to_face').hide();
		})	
	})
	$('.qrcode').click(function(ev){
		ev.stopPropagation();	
	})
	$('.btn_fenxiang').click(function(){
		$('.fix,.fen_xiang').show();
		$('.fix').click(function(){
			$('.fix,.fen_xiang').hide();
		})		
	})
})
</script>
</body>
</html>
