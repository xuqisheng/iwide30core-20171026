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
<div class="reward_header an_rows">  
    <img class="portrait"  src="<?php echo !empty($saler_info['headimgurl'])?$saler_info['headimgurl']:base_url('public/tips/default/images/head1.jpg')?>" style="">
    <p class="portrait_name h34"><?php echo isset($saler_info['name'])?$saler_info['name']:''?></p>
    <div class="portrait_evaluate">
        <p>
            <img class="portrait_ico" src="<?php echo base_url('public/tips/default/images/wealth_ico.png')?>" alt="">
            <span class="h30 portrait_num"><?php echo isset($tips_count)?$tips_count:0?></span>
        </p>
        <p>
            <img class="portrait_ico" src="<?php echo base_url('public/tips/default/images/score_ico.png')?>" alt="">
            <span class="h30 portrait_num"><?php echo isset($avg_score)?$avg_score:0?></span>
        </p>
    </div>
</div>
<div class="reward_content">
    <div class="reward_rows an_rows">
        <p class="reward_title_wrapper">
            <span class="reward_title_lline"></span>
            <span class="reward_title h30">服务评价</span>
            <span class="reward_title_rline"></span>
        </p>
        <div class="reward_content">
            <span class="reward_star reward_star_active"></span>
            <span class="reward_star reward_star_active"></span>
            <span class="reward_star reward_star_active"></span>
            <span class="reward_star reward_star_active"></span>
            <span class="reward_star reward_star_active"></span>
        </div>
    </div>
    <div class="reward_rows">
        <p class="reward_title_wrapper">
            <span class="reward_title_lline"></span>
            <span class="reward_title h30">服务打赏</span>
            <span class="reward_title_rline"></span>
        </p>
        <div class="reward_content">
            <ul class="reward_content_money">
                <li class="reward_content_rows" data-money="1"><span class="h32 reward_money">1</span><span class="h20">元<span></li>
                <li class="reward_content_rows" data-money="5"><span class="h32 reward_money">5</span><span class="h20">元<span></li>
                <li class="reward_content_rows" data-money="10"><span class="h32 reward_money">10</span><span class="h20">元<span></li>
                <li class="reward_content_rows" data-money="30"><span class="h32 reward_money">30</span><span class="h20">元<span></li>
                <li class="reward_content_rows" data-money="50"><span class="h32 reward_money">50</span><span class="h20">元<span></li>
                <li class="reward_content_rows" data-money="100"><span class="h32 reward_money">100</span><span class="h20">元<span></li>
            </ul>
            <div class="reward_money_input">
                <p class="h30 reward_money_title">输入其他金额</p>
                <input id="money_number" class="h30" type="text" placeholder="0.00" onkeyup="clearNoNum(this)">
                <span class="h30 reward reward_money_wrod">元</span>                
            </div>
                <div class="w_money_inpout">
                    <p  class="reward_money_tips h28"><img class="reward_tips_ico" src="<?php echo base_url('public/tips/default/images/warning_ico.png')?>"  alt="">请输入不少于1元的金额</p>  
                </div>
                
            <a class="h36 reward_money_button" id="to_tips" href="javascript:;">立即打赏</a>
        </div>
    </div>
</div>
<?php echo referurl('js','jquery.min.js',1,$media_path) ?>
<script type="text/javascript">
    $(function(){

        $(".reward_content_rows").on("click",function(){
            $(this).siblings().removeClass("reward_active").end().addClass("reward_active");
            $(".reward_money_tips").hide();
            $("#money_number").val("");
        });

        $(".reward_star").on("click",function(){
            $star = $(".reward_star");
            var _index = $(this).index() + 1;
            $star.removeClass("reward_star_active");
            for (var i = 0 ; i < _index ; i ++){
                $star.eq(i).addClass("reward_star_active");
            }
        });

        $("#money_number").on("focus",function(){
             $(".reward_active").removeClass("reward_active");
             $(".reward_money_tips").hide();
        });

        //提交
        $('#to_tips').click(function(){
            var _active = $(".reward_star_active").length;//星星分数
            var money;
                if( $(".reward_active").length > 0 ){
                    money = $(".reward_active").attr("data-money");
                }else{
                    money =  $("#money_number").val();
                }

            if(money == "" ||  money < 1){
               $(".reward_money_tips").show();
            }else{
                $.post('<?php echo site_url('tips/tips/save_order');?>',{
                    'saler':'<?php echo $saler?>',
                    'pay_money':money,
                    'score':_active,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },function(res){
                    if(res.errcode == 0){
                        window.location.href = res.data.pay_url;
                    }else{
                        alert(res.msg);
                    }
                },'json');
            }
        });
        if(/Android [4-6]/.test(navigator.appVersion)) {
            $("#money_number").on("focus",function(){
                $(".an_rows").hide();
            })
            .on("blur",function(){
                $(".an_rows").show();
            });
        }
    });
    function clearNoNum(obj){    
          obj.value = obj.value.replace(/\D/g,'');    
          // obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");    
          // obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');
          if(obj.value.indexOf(".")< 0 && obj.value !=""){   
           obj.value= parseFloat(obj.value);    
          }    
        }  
</script>
</body>
</html>
