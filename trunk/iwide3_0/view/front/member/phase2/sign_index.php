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
<script src="<?php echo base_url("public/member/phase2/scripts/jquery.js");?>"></script>
<script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
<script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
<script src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
<link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/version4.0/css/my.css"); ?>" rel="stylesheet">
<style>
    div, p{line-height: normal;}
	.show{display: inline-block;}
	.hide{display: none;}
</style>
<title>每日签到</title>
</head>
<body class="bg1">
<div class="display_flex sign">
	<div class="ranking">
        <p class="randking <?php echo $signData['is_sign'] ? '' : 'hide';?>">今日签到排行</p>
        <p class="randking  <?php echo $signData['is_sign'] ? '' : 'hide';?>">第<span id="day_ranking"><?php echo $signData['is_sign'] ? $signData['day_ranking'] : ''; ?></span>名</p>
	</div>
	<div class="integral">
		<p>本月签到<?php echo $this->_ci_cached_vars['filed_name']['credit_name'];?></p>
		<p><span id="accumulative_bonus"><?php echo $signData['accumulative_bonus']; ?></span></p>
	</div>
</div>
<div class="sign_btn <?php echo $signData['is_sign'] ? 'actives' : 'enlarge_actives';?>">签到<img class="sign_img <?php echo $signData['is_sign'] ? 'show' : '';?>"  src="<?php echo base_url("public/member/version4.0/images/al_sign.png"); ?>"></div>
<div class="sign_time">
	<div class="container">
		<div class="display_flex week">
			<?php for($i = 1;$i <= 7;$i++): ?>
			<div>
				<img class="money <?php echo ($i > ($signData['serial_days'] % 7) && ($signData['serial_days'] % 7) != 0) ? 'nomoney' : ''; ?>"  src="<?php echo base_url('public/member/version4.0/images/' . ($signData['serial_days'] != 0 && (($signData['serial_days'] % 7) == 0 || $i <= ($signData['serial_days'] % 7)) ? 'money' : 'money2') . '.png'); ?>"><br>
				<img class="hook <?php echo ($signData['serial_days'] != 0 && ($signData['serial_days'] % 7) == 0 || $i <= ($signData['serial_days'] % 7)) ? 'show' : ''; ?>" src="<?php echo base_url('public/member/version4.0/images/hook.png'); ?>">
				<span><?php echo $i; ?></span>
			</div>
			<?php endfor; ?>
		</div>
		<div class="cont_text"><?php echo isset($confInfo['serial_content']) ? $confInfo['serial_content'] : ''; ?></div>
	</div>
</div>
<div class="sing_number display_flex">
	<div>
		<?php if ($signData['serial_days'] > 1): ?>
			连续签到<span id="serial_days"><?php echo $signData['serial_days']; ?></span>天
		<?php endif; ?>
	</div>
	<div>累计签到<span id="accumulative_days"><?php echo $signData['accumulative_days']; ?></span>天</div>
</div>
<div class="flroot_btn">
	<a href="<?php echo base_url("index.php/membervip/sign/ranking_list")?>">排行榜</a>
</div>
<div class="fixed">
	<div class="f_con">
		<div class="congratulations"><img src="<?php echo base_url("public/member/version4.0/images/congratulations.png"); ?>"></div>
		<div class="close"><img src="<?php echo base_url("public/member/version4.0/images/close.png"); ?>"></div>
		<img class="trophy" src="<?php echo base_url("public/member/version4.0/images/trophy.png"); ?>">
		<div class="f_txt"><?php echo isset($confInfo['serial_reward_content']) ? $confInfo['serial_reward_content'] : ''; ?></div>
	</div>
</div>
<script>
$(function(){
    // 点击签到事件（防止多次请求，只绑定一次）
    var $enlarge_actives = $('.enlarge_actives');
    $enlarge_actives.one('click',function(){
        // ajax请求签到
        $.ajax({
            type:'POST',
            url:'<?=base_url('index.php/membervip/sign/sign_in')?>',
            cache: false,
            dataType:'json',
            timeout:5000,
            beforeSend:function () {
                pageloading();
            },
            success:function (data ,textStatus) {
                removeload();
                if(!data.errcode){
                    var locat_url="<?php echo EA_const_url::inst()->get_url('*/*/*');?>";
                    $.MsgBox.Alert(data.msg,function () {
                        window.location.href=locat_url;
                    });
                    return false;
                }else if(data.errcode==-2){
                    var locat_url="<?php echo base_url('index.php/membervip/login');?>";
                    $.MsgBox.Alert(data.msg,function () {
                        window.location.href=locat_url;
                    });
                    return false;
                }

                if(!data.is_serial){
                    window.location.reload();
                    return true;
                }
                $('.fixed').fadeIn();
            },
            error:function (XMLHttpRequest, textStatus) {
                removeload();
                if(textStatus=='timeout'){
                    $(".sign_btn").removeClass("enlarge_actives");
                    $(".sign_btn").addClass("actives");
                    $.MsgBox.Alert('签到人数太多，积分发放稍有延迟，请稍后查看。');
                }
                return false;
            },
            complete:function () {
                removeload();
                return false;
            }
        });

    });

	$('.close').on('click',function(){   //弹窗关闭
		$('.fixed').fadeOut();
        window.location.reload();
	});
});
</script>
</body>
</html>
