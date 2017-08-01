<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
	<meta name="Maker" content="Taoja" tel="13544425200">
	<meta name="format-detection" content="telephone=no" />
	<title><?=!empty($lvl_name)?'邀请'.$lvl_name.'资格':'邀请好友'?></title>
    <script src="<?php echo base_url("public/member/nvitedkim/js/jquery.js");?>"></script>
    <link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <style>
        .bg_F3F4F8, body, html {background-color: #ffffff;}p{margin:0;font-family:HeiTi SC}.w100{width:100%}.absolute{position:absolute}body{width:100%;height:100%;position:absolute;margin:0;font-size:14px;font-family:Heiti SC}.right{float:right}.full{width:100%;height:100%}.maxscreen{width:100%}.left{float:left}.coverpage,.coverpage2{width:100%;height:100%;background-color:#000;opacity:.8}.fixed{position:fixed}.none{display:none}.relative{position:relative}.left{float:left}.fullbg{background-size:100% 100%}.main{transform-origin:top left;-webkit-transform-origin:top left;-o-transform-origin:top left;-moz-transform-origin:top left}.full{height:100%}.center{text-align:center}.ib{display:inline-block}.icon{width:12%}.pad20{margin:15px 20px}.info{margin-left:5px}.line1{font-size:1.2em}.line2{font-size:1.1em;margin-top:5px;color:#6b6b6b}.line{font-size:1.2em;color:#6b6b6b}.rule_img{width:100px;margin:auto}.middle{vertical-align:middle}.rule{margin-top:50px;font-size:1.1em;line-height:1.4em;color:#666;margin-bottom:30px}.tubiao{width:15%;display:inline-block}.num{background-color:#ffc266;padding:5px;border-radius:100%;color:white;width:10px!important;height:10px!important;line-height:10px;text-align:center;font-weight:bold}.wenzi{display:inline-block;width:85%}.see{color:black;text-align:center;font-size:1.1em}.rule_body{margin-top:80px}.ewm_body{position:relative;top:40%;left:25%;width:50%}.ewm_title{text-align:center;width:100%;margin-top:7%;color:#333;font-size:1.3em;font-weight:bold}.ewm_title2{font-size:1.1em;text-align:center;color:#333;margin-top:10%}.pad10{margin:10px 20px}.guize_body{background-color:white;width:90%;position:absolute;left:5%;top:15%;border-radius:10px;max-height:75%;overflow:auto}.hdgz{text-align:center;font-size:1.1em;color:#f90}.hdgz span{color:black}@media screen and (max-width:375px){body{font-size:13px}}@media screen and (max-width:320px){body{font-size:12px}}.zhengwen{margin:15px 15px;}.zhengwen p{line-height: 25px;}
	</style>
</head>

<body>
	<div class="main absolute">
		<div class="headimg">
			<img class="w100" src="<?php echo base_url("public/member/nvitedkim");?>/img/title.png">
		</div>
		<div class="ewm_title">邀请朋友成为<?=$lvl_name?>资格</div>
		<div class="ewm_title2">请朋友扫码或分享给好友领取会员权益</div>
		<div class="ewm_body">
			<img id="qrcode_img" style="display: none;" src="<?php echo base_url("public/member/nvitedkim");?>/img/ewm.png" class="w100" alt="">
		</div>
		<div class="hdgz">查看活动规则<span>></span></div>
	</div>
	<div class="coverpage full fixed none"></div>
	<div class="guize full fixed none">
		<div class="guize_body">
			<div class="rule_img" style="margin-top:10px;">
                <img src="<?php echo base_url("public/member/nvitedkim");?>/img/jj.png" class="w100">
			</div>
			<div class="zhengwen">
                <?=!empty($view_conf['activity_rule']['content'])?$view_conf['activity_rule']['content']:''?>
            </div>
		</div>
	</div>
	<script>
        /*微信JSSDK*/
        wx.config({
            debug: false,
            appId: '<?php if (!empty($signpackage["appId"])) echo $signpackage["appId"];?>',
            timestamp: '<?php if (isset($signpackage["timestamp"])) echo $signpackage["timestamp"];?>',
            nonceStr: '<?php if (isset($signpackage["nonceStr"])) echo $signpackage["nonceStr"];?>',
            signature: '<?php if (isset($signpackage["signature"])) echo $signpackage["signature"];?>',
            jsApiList: [<?php echo $js_api_list; ?>,'getLocation','openLocation']
        });

        wx.ready(function () {
            <?php if($js_menu_hide):?>wx.hideMenuItems({menuList:[<?php echo $js_menu_hide;?>]});<?php endif;?>
            <?php if($js_menu_show):?>wx.showMenuItems({menuList:[<?php echo $js_menu_show;?>]});<?php endif;?>
        <?php if( $js_share_config ): ?>
            wx.onMenuShareTimeline({
                title: '<?=!empty($view_conf['invited_share']["title"])?$view_conf['invited_share']["title"]:'分享到朋友圈';?>',
                link: '<?=!empty($js_share_config["link"])?$js_share_config["link"].'&channel=share':''?>',
                imgUrl: '<?=!empty($view_conf['invited_share']["banner"])?$view_conf['invited_share']["banner"]:'';?>',
                success: function () {},
                cancel: function () {}
            });

            wx.onMenuShareAppMessage({
                title: '<?php echo !empty($view_conf['invited_share']["title"])?$view_conf['invited_share']["title"]:'发送给好友'?>',
                desc: '<?=!empty($view_conf['invited_share']["sub_title"])?$view_conf['invited_share']["sub_title"]:'';?>',
                link: '<?=!empty($js_share_config["link"])?$js_share_config["link"].'&channel=share':''?>',
                imgUrl: '<?=!empty($view_conf['invited_share']["banner"])?$view_conf['invited_share']["banner"]:'';?>',
                success: function () {},
                cancel: function () {}
            });
            <?php endif; ?>
        });
        /*end*/

		var click = "ontouchstart" in window ? "touchstart" : "mousedown";
		var hdgz = document.querySelector(".hdgz");
		var guize = document.querySelector(".guize");
		var coverpage = document.querySelector(".coverpage");
		hdgz.addEventListener(click, function() {
			coverpage.style.display = "block";
			guize.style.display = "block";
		});
		guize.addEventListener(click, function() {
			coverpage.style.display = "none";
			guize.style.display = "none";
		});

        var numbe=59,times=null,sendto=1;
        starshare();
        $(function () {
            $("#qrcode_img").click(function () {
                if(sendto==1){
                    starshare();
                    times=setInterval(function(){
                        numbe--;
                        if(numbe==0){
                            clearInterval(times);
                            numbe=59;
                            sendto=1;
                        }
                    },1000);
                }
                sendto = 0;
            });
        });

        function starshare() {
            var postUrl = "<?php echo EA_const_url::inst()->get_url('*/api/invitate/start')?>";
            var code = "<?=$lvlcode?>";
            var datas = {code: code,channel:'face'};
            $.ajax({
                url:postUrl,
                type:'POST',
                data:datas,
                dataType:'json',
                timeout:15000,
                success: function (data) {
                    if(data.status==1){
                        var qrcode_url = "<?php echo EA_const_url::inst()->get_url('*/api/myqrcode')?>?share_key="+data.data;
                        $("#qrcode_img").attr("src",qrcode_url);
                        setTimeout(function () {
                            $("#qrcode_img").show();
                        },100);
                    }
                },
                error: function () {

                }
            });
        }
	</script>
</body>

</html>
