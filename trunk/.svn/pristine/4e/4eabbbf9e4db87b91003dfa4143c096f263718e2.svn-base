<?php 
//月饼说专用部分 开始==============================================================
if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ): 
?>
<style>
body,html{width:100%; height:100%;}
</style>
<body style="overflow:hidden">
<div class="pageloading"><p class="isload">&nbsp;</p></div>
<div class="gift_action" style="padding-top:50%">
	<div class="bg_fff relative center pad10 bdradius" style="width:80%; margin:auto; padding-top:60px">
		<div class="headimg"><img src="<?php echo base_url('public/soma/images/box2.png');?>"></div>
		<div>恭喜您收到一个中秋礼盒</div>
		<a href="<?php echo $current_url; ?>" class="open_gift color_fff">
			<p><span>拆礼盒</span></p>
		</a>
	</div>
</div>
</body>
<?php 
//月饼专用部分 结束==============================================================
else:
//套票专用部分 开始==============================================================
?>
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
                            : $lang->line('your_friends'); ?></div>
            <div class="time"><?php echo $gift_data['create_time']; ?></div>
            <div class="wish"><?php echo $lang->line('send_you_a_gift'); ?></div>
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
<?php 
//套票专用部分 结束==============================================================
endif;
?>
</html>