<!doctype html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no,email=no">
    <meta name="ML-Config" content="fullscreen=yes,preventMove=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=320,initial-scale=1,user-scalable=0">
    <?php echo referurl('css','new_index.css',1,$media_path) ?>
    <?php echo referurl('css','reward.css',1,$media_path) ?>
    <title>打赏</title>
</head>
<body>
<div class="res_header">  
    <img class="reward_success_ico"  src="<?php echo base_url('public/tips/default/images/success_ico.png')?>" style="">
    <p class="reward_success h36">打赏成功！</p>
    <p class="reward_success_thank" class="h30" style="color:#808080;">感谢您对我们服务的认可，期待再次为您服务！</p>
    <div class="reward_success_a"><a class="h30" style="color:#333333;" href=""><span>逛逛商城</span> <span>></span></a></div>
    
    <div  id="scratchCard">
    </div>
</div>
<section class="scratch_bg" id="scratch_success">
    <div class="layerbox">
        <p class="scratch_tips_title h36">存入成功</p>
        <p class="scratch_tips_word h30">奖励将在三个工作日内绑定至您的会员账户，敬请留意!</p>
        <div class="scratch_tips_button h34">
            <p class="scratch_tips_konw">知道啦</p>
            <p class="scratch_tips_check scratch_active">查看账户</p>
        </div>
    </div>
</section>
<section class="scratch_bg" id="scratch_error">
    <div class="layerbox">
        <p class="scratch_tips_title h36">存入失败</p>
        <p class="scratch_tips_word h30">您还没有绑定会员卡，请前往绑定会员卡领取奖励噢！</p>
        <div class="scratch_tips_button h34">
            <p class="scratch_tips_konw">不要啦</p>
            <p class="scratch_tips_check scratch_active">去绑定</p>
        </div>
    </div>
</section>
<?php echo referurl('js','jquery.min.js',1,$media_path) ?>
<?php echo referurl('js','jquery.scratchCard.js',1,$media_path) ?>
<script type="text/javascript">
    
     var _html = "<p class='scratch_title h36'>恭喜中奖!</p>";

     //内容
         _html+= "<p class='scratch_word h26'>20元代金卷 ＊1</p>"
         _html+= "<p class='scratch_word h26'>入住间夜点数 ＊1</p>"
         _html+= "<p class='scratch_word h26'>20元代金券 ＊1</p>"
        $("#scratchCard").scratchCard({
            backgroundColor:'#e6e8e8',
            tipsColor:'#808080',
            tips:'刮 刮 乐',
            btnCotent:'存入账户',
            btnCallBack:function(){
                $("#scratch_success").show()
            },
            prompt:_html,
        });

        $(".scratch_tips_konw").on("click",function(){
            $("#scratch_success").hide();
        })
</script>
</body>
</html>
