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
<title>订单跟踪</title>
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
    <section class="mainboxs bg_fff h24 ">
        <div class="scroll">
            <?php if(!empty($action)){
                    foreach($action as $k=>$v){
            ?>
            <div class="delivered flex">
                <div class="txt_r">	<p><?php echo !empty($v['add_time'])?substr($v['add_time'],11):''?></p > <p class="color_555 h18"><?php echo !empty($v['add_time'])?substr($v['add_time'],0,10):''?></p ></div>
                <div class="delivered_status"><span></span></div>
                <div><?php echo $v['content']?></div>
            </div>
            <?php }}?>

        </div>
    </section>
    <footer></footer>
</page>
</body>

</html>
