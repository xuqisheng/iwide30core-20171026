


<link href="<?php echo base_url('public/soma/styles/receive.css');?>" rel="stylesheet">
<body>


<!-- 蒙版 -->
<div class="ui_pull pullgift" style="text-align:center; background:rgba(53,57,60,0.9)">
    <img style="width:55%; padding-top:23%;" src="<?php echo base_url('public/soma/images/txt1.png');?>" />
</div>
<div style="min-height:90%">
    <div class="fromuser">
        <div class="userimg"><img src="<?php $headimgurl = isset( $fans['headimgurl'] ) && !empty( $fans['headimgurl'] ) ? $fans['headimgurl'] 
                        : base_url('public/soma/images/ucenter_headimg.jpg'); echo $headimgurl; ?>" />
        </div>
        <div class="name"><?php echo isset( $fans['nickname'] ) && !empty( $fans['nickname'] ) ? $fans['nickname'] 
                        : '您的好友';?></div>
        <div class="time"><?php echo $gift_data['create_time']; ?></div>
        <div class="wish">送你一份礼物</div>
    </div>
    
    <div class="giftbox">
        <div class="boximg">
            <div class="lid"><img src="<?php echo base_url('public/soma/images/gift02.png');?>"></div>
            <div class="relative">
                <img src="<?php echo base_url('public/soma/images/gift01.png');?>">
                <img class="boxlogo" src="<?php echo base_url('public/soma/images/logo2.png');?>">
            </div>
        </div>
    </div>
</div>
<!--div class="again h3"><span>关注公众号</span></div-->
</body>
<script>
    $(window).load(function(){
        $('.pullgift').fadeIn();
        $('body').addClass('overflow');
        $('html').addClass('overflow');
        $('.giftbox').css({
            "position":"relative",
            "z-index" :"99999",
            "width"	  :"100%",
        });
        $('.boximg').addClass('shaking');
        window.setTimeout(function(){
            $('.boximg').removeClass('shaking');
        },600);
        $('.boximg').click(function(){
            var _this=$(this);
            _this.addClass('shaking');
            window.setTimeout(function(){
                _this.find('.lid img').attr('src',"<?php echo base_url('public/soma/images/gift03.png');?>");
                //动作结束
                _this.removeClass('shaking');
                $('.pullgift').fadeOut(function(){
           			location.href='<?php echo $current_url; ?>';
                });
            },600);
        })
    });
</script>
</html>