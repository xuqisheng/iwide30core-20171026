<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" c ontent="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,user-scalable=0">
<title>联系信息</title>
    <?php echo referurl('css','global.css',1,$media_path) ?>
    <?php echo referurl('css','service.css',1,$media_path) ?>
    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>
    <script type='text/javascript'>
        var _vds = _vds || [];
        window._vds = _vds;
        (function(){
            _vds.push(['setAccountId', '9035a905d6d239a4']);
            (function() {
                var vds = document.createElement('script');
                vds.type='text/javascript';
                vds.async = true;
                vds.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'dn-growing.qbox.me/vds.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(vds, s);
            })();
        })();
    </script>
    <script type='text/javascript' src='https://assets.growingio.com/sdk/wx/vds-wx-plugin.js'></script>
</head>
<body>
<div class="pageloading"></div>
<page class="page">
	<header></header>
    <section class="scroll flexgrow h26">
    	<div class="flexlist list_style_1">
            <?php if(!empty($user_address)){
                        foreach($user_address as $k=>$v){
            ?>
            <a href="<?php echo site_url('ticket/ticket/address?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id.'&addr_id='.$v['address_id'])?>" class="flex bg_fff pad10 arrow">
                <em class="iconfont h36 color_main">&#XA8;</em>
                <div style="margin-left:5px">
                    <div class="h26"><?php echo $v['contact'].' '.$v['phone']?></div>
                    <!--
                    <div class="h20 color_C3C3C3"><?php echo $v['select_addr'].' '.$v['address']?></div>
                    -->
                </div>
            </a>
            <?php }}?>

        </div>
    </section>
    <footer>
        <a class="bg_main center pad12" style="display:block" href="<?php echo site_url('ticket/ticket/address?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id)?>" ><em class="iconfont">&#XAD;</em> 新建联系信息</a>
    </footer>
</page>
</body>
</html>
