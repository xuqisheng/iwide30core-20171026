<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
	<meta name="Maker" content="Taoja" tel="13544425200">
	<meta name="format-detection" content="telephone=no" />
	<title><?=!empty($view_conf['upgrade_success']['title'])?$view_conf['upgrade_success']['title']:'邀请好友'?></title>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <style>
        p{margin:0;font-family:HeiTi SC}.w100{width:100%}.absolute{position:absolute}body{width:100%;height:100%;position:absolute;margin:0;font-size:14px;font-family:Heiti SC}.right{float:right}.full{width:100%;height:100%}.maxscreen{width:100%}.left{float:left}.coverpage,.coverpage2{width:100%;height:100%;background-color:#000;opacity:.8}.fixed{position:fixed}.none{display:none}.relative{position:relative}.left{float:left}.fullbg{background-size:100% 100%}.main{transform-origin:top left;-webkit-transform-origin:top left;-o-transform-origin:top left;-moz-transform-origin:top left}.full{height:100%}.center{text-align:center}.ib{display:inline-block}.star{width:60%;margin:5px auto}.title1,.title2{text-align:center;color:#ea4940;font-size:1.4em;font-weight:bold;margin:10px}.said1{text-align:center;margin-top:20px}.jdbtn{text-align:center;margin-top:20px}.jdbtn div{display:inline-block;width:50%;background-color:#f90;padding:10px;border-radius:5px;color:white;font-weight:bold;font-size:1.2em}.ewmk{text-align:center;margin-top:20px}.mt5{margin-top:5px}.banner{width:80%;margin:10px auto}.ewmk img{width:45%}@media screen and (max-width:375px){body{font-size:13px}}@media screen and (max-width:320px){body{font-size:12px}}
	</style>
</head>

<body>
		<div class="star">
			<img src="<?=base_url("public/member/nvitedkim")?>/img/pic_star.png" class="w100">
		</div>
		<div class="title1"><?=!empty($msg)?$msg:'恭喜你成功获得'?></div>
        <?php if(!empty($msg2)):?>
            <div class="title2"><?=$msg2?></div>
        <?php endif;?>
		<div class="said1">马上享受会员权益预定酒店</div>
		<div class="jdbtn"><a href="<?=!empty($view_conf['upgrade_success']['url'])?$view_conf['upgrade_success']['url']:'javascript:void(0);'?>"><div><?=!empty($view_conf['upgrade_success']['button_name'])?$view_conf['upgrade_success']['button_name']:'预定酒店'?></div></a></div>
		<div class="ewmk">
			<img src="<?=site_url('membervip/api/invitate/subqrcode').'?id='.$inter_id.'&mid='.$vip_user['member_info_id'];?>" alt="">
		</div>
		<div class="said2 center mt5"><?=empty($view_conf['upgrade_success']['scan_tip'])?'':$view_conf['upgrade_success']['scan_tip']?></div>
		<?php if(!empty($view_conf['custom']['title1']) && !empty($view_conf['custom']['banner1'])):?>
		<div class="banner">
            <a href="<?=empty($view_conf['custom']['url1'])?'javascript:void(0);':$view_conf['custom']['url1']?>">
            <img src="<?=$view_conf['custom']['banner1']?>" class="w100">
            </a>
            <div class="js"><?=$view_conf['custom']['title1']?></div>
		</div>
		<?php endif;?>

        <?php if(!empty($view_conf['custom']['title2']) && !empty($view_conf['custom']['banner2'])):?>
        <div class="banner">
            <a href="<?=empty($view_conf['custom']['url2'])?'javascript:void(0);':$view_conf['custom']['url2']?>">
            <img src="<?=$view_conf['custom']['banner2']?>" class="w100">
            </a>
            <div class="js"><?=$view_conf['custom']['title2']?></div>
        </div>
        <?php endif;?>

        <?php if(!empty($view_conf['custom']['title3']) && !empty($view_conf['custom']['banner3'])):?>
        <div class="banner">
            <a href="<?=empty($view_conf['custom']['url3'])?'javascript:void(0);':$view_conf['custom']['url3']?>">
            <img src="<?=$view_conf['custom']['banner3']?>" class="w100">
            </a>
            <div class="js"><?=$view_conf['custom']['title3']?></div>
        </div>
        <?php endif;?>
<script type="text/javascript">
    /*微信JSSDK*/
    wx.config({
        debug: false,
        appId: '<?php if (!empty($signpackage["appId"])) echo $signpackage["appId"];?>',
        timestamp: '<?php if (isset($signpackage["timestamp"])) echo $signpackage["timestamp"];?>',
        nonceStr: '<?php if (isset($signpackage["nonceStr"])) echo $signpackage["nonceStr"];?>',
        signature: '<?php if (isset($signpackage["signature"])) echo $signpackage["signature"];?>',
        jsApiList: ['hideMenuItems']
    });
    wx.ready(function () {
        wx.hideOptionMenu();
    });
</script>
</body>
</html>
