<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
	<meta name="Maker" content="Taoja" tel="13544425200">
	<meta name="format-detection" content="telephone=no" />
	<title><?=!empty($view_conf['home']['title'])?$view_conf['home']['title']:'邀请好友'?></title>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <style>
        p{margin:0;font-family:HeiTi SC}.w100{width:100%}.absolute{position:absolute}body{width:100%;height:100%;position:absolute;margin:0;font-size:14px;font-family:Heiti SC}.right{float:right}.full{width:100%;height:100%}.maxscreen{width:100%}.left{float:left}.coverpage,.coverpage2{width:100%;height:100%;background-color:#000;opacity:.8}.fixed{position:fixed}.none{display:none}.relative{position:relative}.left{float:left}.fullbg{background-size:100% 100%}.main{transform-origin:top left;-webkit-transform-origin:top left;-o-transform-origin:top left;-moz-transform-origin:top left}.full{height:100%}.center{text-align:center}.ib{display:inline-block}.cry{width:100%;margin-top:50px}.sorry{text-align:center;font-size:1.2em;color:#9d9d9d}.card{text-align:center;font-size:1.2em;color:#9d9d9d;margin:2px}.btn{display:inline-block;width:60%;font-size:1.2em;background-color:#f90;padding:10px;border-radius:5px;color:white}.ewmk{width:40%;margin:10px auto}.btnk{text-align:center;margin-top:20px}.or{text-align:center;margin-top:30px;font-size:1.2em}.guanzhu{text-align:center;margin:10px}P{text-align:center;font-size:1.5em;font-weight:bold;color:#666}@media screen and (max-width:375px){body{font-size:13px}}@media screen and (max-width:320px){body{font-size:12px}}
	</style>
</head>
<body>
    <div class="cry">
        <img src="<?=base_url("public/member/nvitedkim");?>/img/end.png" alt="" class="w100">
    </div>
    <p><?=!empty($msg)?$msg:'活动还没开始'?></p>
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