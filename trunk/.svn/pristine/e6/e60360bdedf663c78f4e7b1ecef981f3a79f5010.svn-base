<body>
<!-- <div class="pageloading"><p class="isload">正在加载</p></div> -->
<!-- 以上为header.php -->

<script src="<?php echo base_url('public/soma/scripts/imgscroll.js');?>"></script>
<script src="<?php echo base_url('public/soma/scripts/jquery.touchwipe.min.js');?>"></script>
<!-- <header class="headers" style="border-bottom:7px solid #e5e5e5">
    <div class="headerslide">
        <a class="slideson" href="">
            <img src="<?php echo base_url('public/soma/images/super81.jpg');?>" />
        </a>
        <a class="slideson" href="">
            <img src="<?php echo base_url('public/soma/images/super81.jpg');?>" />
        </a>
    </div>
</header> -->

<script>
wx.config({
    debug: false,
    appId: '<?php echo $wx_config["appId"]?>',
    timestamp: <?php echo $wx_config["timestamp"]?>,
    nonceStr: '<?php echo $wx_config["nonceStr"]?>',
    signature: '<?php echo $wx_config["signature"]?>',
    jsApiList: [<?php echo $js_api_list; ?>,'getLocation','openLocation']
});
wx.ready(function(){
    <?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
    <?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>

<?php if( $js_share_config ): ?>
        wx.onMenuShareTimeline({
            title: '<?php echo $js_share_config["title"]?>',
            link: '<?php echo $js_share_config["link"]?>',
            imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
            success: function () {},
            cancel: function () {}
        });
        wx.onMenuShareAppMessage({
            title: '<?php echo $js_share_config["title"]?>',
            desc: '<?php echo $js_share_config["desc"]?>',
            link: '<?php echo $js_share_config["link"]?>', 
            imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
            //type: '', //music|video|link(default)
            //dataUrl: '', //use in music|video
            success: function () {},
            cancel: function () {}
        });

<?php endif; ?>
});
</script>

<?php if( count( $comingList ) > 0 ):?>
<div class="list_style_1 actlist">
    <?php foreach( $comingList as $k=>$v ):?>
	   <div class="webkitbox">
        	<div class="img"><div class="squareimg" href="<?php echo $v['link'];?>" inter_name="正在跳转至<?php echo $v['inter_name'];?>官网商城" aid="<?php echo $v['act_id'];?>"><img src="<?php echo $v['face_img'];?>"></div></div>
            <div class="h20 color_888">
            	<p class="h24 color_000"><?php echo $v['product_name'];?></p>
                <p><?php echo $v['inter_name'];?></p>
                <div class="webkitbox">
                	<p class="color_main h22"><?php echo $v['killsec_time'],'准时开抢';?></p>
                </div>
                <div class="webkitbox justify">
                	<div>
                    	<span class="color_main"><tt class="y h18"></tt><tt class="h34"><?php echo $v['killsec_price'];?></tt></span>
                    	<span class="color_888 h22"><tt class="y"></tt><del><?php echo $v['price_market'];?></del></span>
                    </div>
                    <div class="btn_minor bdradius setNotice <?php if($v['subscribe']==Soma_base::STATUS_TRUE) echo 'disable';?>" aid="<?php echo $v['act_id'];?>" >
                        <?php echo $v['subscribe']==Soma_base::STATUS_TRUE ? '已订阅提醒':'设置提醒';?>
                    </div>
                    <!-- <div class="btn_minor bdradius" href="<?php echo $v['link'];?>" inter_name="正在跳转至<?php echo $v['inter_name'];?>官网商城" aid="<?php echo $v['act_id'];?>">去抢购</div> -->
                </div>
            </div>
        </div>
    <?php endforeach;?>
</div>
<?php else:?>
    <div class="ui_none"><div>没有更多即将开始的活动<br>如有疑问，联系客服</div></div>
<?php endif;?>

<div class="ui_pull center" id="newurl" style="background:#fff; padding:50px; display:none">
    <div><img src="<?php echo base_url('public/soma/images/href.jpg');?>" style=" margin-bottom:20px"></div>
    <div id="interNmae">正在跳转至金房卡酒店官网商城</div>
</div>

<script>

//设置秒杀订阅
$(".setNotice").click(function(){
    var setNotice = $(this);
    var actId = $(this).attr('aid');
    if( actId != '' && actId != undefined ){
        $.MsgBox.Confirm('活动尚未开始，你可以订阅提醒，活动开始前10分钟将提醒您', function(){
            //window.location.href='';//rightEvent;
            // pageloading('数据发送中，请稍后', 0.2);
            $.ajax({
            url:'<?php echo $killsec_notice_url; ?>',
                type: 'POST',
                dataType:'JSON',
                data:{
                act_id :actId,
                },
                success:function(json){
                    // $('.pageloading').remove();
                    subscribe_lock= true;
                    if( json.status == 1 ){
                        $.MsgBox.Alert( json.message );                             
                        setNotice.removeAttr('aid');
                        setNotice.addClass('disable');
                        setNotice.html('已订阅提醒');
                    } else if( json.status == 2 ){
                        $.MsgBox.Alert( json.message );
                    }
                }
            });
        },function(){
            //window.location.href='';//leftEvent;                  
        },'立即订阅', '稍候再说');
    }
});

</script>

<script>
$.fn.imgscroll({
    imgrate : 640/235,
    circlesize: '4px'
})
$('.actlist').on('click','.squareimg',function(){
    var a = $(this).attr('href');
    $("#interNmae").html($(this).attr('inter_name'));
    if(a ==undefined) return;
    $('#newurl').fadeIn();
    window.setTimeout(function(){
        window.location.href= a;
    },2000);
})
</script>

