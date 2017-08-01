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

    <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/tips/default/styles/new_index.css">
    <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/tips/default/styles/reward.css">
    <title>打赏</title>
</head>
<body class="s8_bg">
<div class="res_header s8_wrap"> 
        <img class="s8_success_ico"  src="<?php echo base_url('public/tips/default/images/s8.png')?>" style="">
        <p class="s8_success h34">打赏成功!</p>
        <p class="reward_success_thank" class="h30" style="color:#808080;">您的鼓励，我的动力！当然！还有您的奖励！</p>
            <!-- <div class="reward_success_a"><a class="h30" style="color:#333333;" href=""><span>逛逛商城</span> <span>></span></a></div> -->
</div>
    <p class="reward_title_wrapper s8_title">
        <span class="reward_title_lline"></span>
        <span class="reward_title h30">活动</span>
        <span class="reward_title_rline"></span>
    </p>
    <div id="scratchCard" style="margin-top:0px;">
    </div>
<section class="scratch_bg" id="scratch_success">
    <div class="layerbox">
        <p class="scratch_tips_title h36">存入成功</p>
        <p class="scratch_tips_word h30">奖励将在三个工作日内绑定至您的会员账户，敬请留意!</p>
        <div class="scratch_tips_button h34">
            <p class="scratch_tips_konw">知道啦</p>
            <p class="scratch_tips_check scratch_active"><a href="<?php echo site_url('member/crecord/balances').'?id='.$order['inter_id']?>">查看账户</a></p>
        </div>
    </div>
</section>
<section class="scratch_bg" id="scratch_error">
    <div class="layerbox">
        <p class="scratch_tips_title h36">存入失败</p>
        <p class="scratch_tips_word h30">您还没有绑定会员卡，请前往绑定会员卡领取奖励噢！</p>
        <div class="scratch_tips_button h34">
            <p class="scratch_tips_konw" id="sendtmmsg">不要啦</p>
            <p class="scratch_tips_check scratch_active"><a href="<?php echo site_url('member/crecord/balances').'?id='.$order['inter_id']?>">去绑定</a></p>
        </div>
    </div>
</section>
<?php /*echo referurl('js','jquery.min.js',1,$media_path) */?><!--
--><?php /*echo referurl('js','jquery.scratchCard.js',1,$media_path) */?>
<script src="<?php echo base_url(FD_PUBLIC) ?>/tips/default/scripts/jquery.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/tips/default/scripts/jquery.scratchCard.js"></script>

<script type="text/javascript">
    var is_mem = <?php echo $is_member?>;

     var _html = "<p class='scratch_title h36'>恭喜中奖!</p>";

     //内容
         _html+= "<p class='scratch_word h26'><?php echo $prize_result['reward_title']?> ＊1</p>";

        $("#scratchCard").scratchCard({
            backgroundColor:'#e6e8e8',
            tipsColor:'#808080',
            tips:'刮 开 有 惊 喜！',
            btnCotent:'存入账户',
            btnCallBack:function(){
                //触发
                var url = '<?php echo site_url('subatips/tips/get_reward')?>';
                $.post(url,
                    {
                        'order_id':'<?php echo $order['order_id']?>',
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },function(data){
                        if(data.errcode == 0){
                            $("#scratch_success").show()
                        }else if(data.errcode == 999){//不是会员
                            $("#scratch_error").show()
                        }else{
                            alert(data.msg);
                        }
                    },'json');
            },
            prompt:_html,
        });

        $(".scratch_tips_konw").on("click",function(){
            $("#scratch_success").hide();
        });
        $('#sendtmmsg').on('click',function(){
            var msgurl = '<?php echo site_url('subatips/tips/send_tmmsg')?>';
            $.post(msgurl,
                {
                    'order_id':'<?php echo $order['order_id']?>',
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },function(data){

                },'json');
            $("#scratch_error").hide();
        })
</script>
</body>
</html>
