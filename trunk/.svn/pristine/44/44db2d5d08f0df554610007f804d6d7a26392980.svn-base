<body>
<div class="pageloading"><p class="isload">正在加载</p></div>
<script src="<?php echo base_url('public/soma/scripts/imgscroll.js');?>"></script>
<script src="<?php echo base_url('public/soma/scripts/jquery.touchwipe.min.js');?>"></script>
<script>
wx.config({
    debug: false,
    appId: '<?php echo $wx_config["appId"]?>',
    timestamp: <?php echo $wx_config["timestamp"]?>,
    nonceStr: '<?php echo $wx_config["nonceStr"]?>',
    signature: '<?php echo $wx_config["signature"]?>',
    jsApiList: [<?php echo $js_api_list; ?>]
});
wx.ready(function(){
	<?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>

	<?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>

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

<header class="headers">
    <div class="headerslide">
        <a class="slideson ui_img_auto_cut" href="<?php echo Soma_const_url::inst()->get_package_detail(array('pid'=>$packageDetail['product_id'],'id'=>$inter_id));?>">
            <img src="<?php echo $packageDetail['face_img'];?>" />
        </a>
    </div>
</header>
<div class="bg_fff bd_bottom pad3">
    <div class="txtclip h30"><b><?php echo $packageDetail['name'];?></b></div>
    <div class="webkitbox price_list">
        <div class="item"><p class="color_fff bg_main"><?php echo $activityInfo['group_count'];?>人团</p><p><?php echo $activityInfo['group_price'];?></p></div>
        <div class="item"><p class="color_fff gray_bg">套餐价</p><p><?php echo $packageDetail['price_package'];?></p></div>
        <div class="item"><p class="color_fff gray_bg">市场价</p><p><?php echo $packageDetail['price_market'];?></p></div>
        <div><a href="<?php echo Soma_const_url::inst()->get_package_detail(array('pid'=>$packageDetail['product_id'],'id'=>$inter_id));?>">查看商品详情>></a></div>
        <!--div><p>拼团价：<?php echo $activityInfo['group_price'];?></p></div-->
    </div>
	<div class="group_full relative">
    	<div class="bg_main absolute color_fff h30"><b>本团<br>已满</b></div>
        <p class="h30" style="padding-left:80px;"><?php if($inGroup){ echo '恭喜！组团成功！商品已购买成功';}else{ echo '太晚了，此团已满~，再开一团';} ?></p>
		<p class="h22">组团成功</p>
     </div>
</div>
<div class="group color_888 bg_fff martop bd">
    <div class="group_member">
        <div class="pad3"> <?php if($inGroup){ ?>开团成功！快去查看我的订单吧！<?php }else{ ?> 来晚啦~此团已成功组建！<?php } ?></div>
        <div class="group_member_list">
            <?php
            $s=0;
            foreach($users as $v){ ?>
                <div class="item">
                    <div class="img"><img src="<?php echo $v['headimgurl'];?>"/></div>
                    <?php if($s== 0) { $s++; ?><p>已开团</p> <?php }else{ ?><p>已入伙</p><?php }?>
                </div>
            <?php } ?>
            <?php for($i=0;$i< $restNum;$i++){?>
                <div class="item">
                    <div class="img"></div>
                    <p>待入伙</p>
                </div>
            <?php }?>
        </div>
    </div>
</div>

<div class="martop bg_fff bd">
    <div class="h30 bd_bottom pad3">拼团流程</div>
    <div class="group_rule">
        <div class="active">
            <em></em>
            <p>选择心仪商品</p>
        </div>
        <div class="active">
            <em></em><hr>
            <p>支付开团或参团</p>
        </div>
        <div class="active">
            <em></em><hr>
            <p>等待好友参团支付</p>
        </div>
        <div class="active">
            <em></em><hr>
            <p>组团成功尽享优惠</p>
        </div>
    </div>
</div>
<div class="foot_fixed">
    <div class="bg_fff foot_fixed_list center bd_top_img">
        <a href="<?php echo Soma_const_url::inst()->get_pacakge_home_page();?>" class="store_link">商城首页</a>
        <?php if($inGroup){ ?>
            <a class="bg_main pad10" href="<?php echo Soma_const_url::inst()->get_soma_order_list();?>">组团成功！点击查看我的订单</a>
        <?php }else{ ?>
            <a class="bg_main pad10" href="<?php echo Soma_const_url::inst()->get_pacakge_home_page();?>">我要开团！</a>
        <?php } ?>
    </div>
</div>
<div class="ui_pull share_pull" style="display:none"><div class="fen_bg"></div></div>


<div style="padding-top:20%"></div>
<script>
    $(function(){
        $('.fensan').click(function(){
            $('.share_pull').show();
        })
        $('.share_pull').click(function(){
            $(this).hide();
        })

    })
</script>
</body>
</html> 
