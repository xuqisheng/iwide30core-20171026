<body>
<script src="<?php echo base_url('public/soma/scripts/imgscroll.js');?>"></script>
<script src="<?php echo base_url('public/soma/scripts/jquery.touchwipe.min.js');?>"></script>
<div class="pageloading"><p class="isload">正在加载</p></div>
<script>
    wx.config({
        debug: false,
        appId: '<?php echo $wx_config["appId"]?>',
        timestamp: <?php echo $wx_config["timestamp"]?>,
        nonceStr: '<?php echo $wx_config["nonceStr"]?>',
        signature: '<?php echo $wx_config["signature"]?>',
        jsApiList: [<?php echo $js_api_list; ?>,'getLocation']
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
<header class="headers">
    <div class="headerslide"><?php
        if(!empty($advs)){  foreach($advs as $k => $v){ ?>
            <a class="slideson ui_img_auto_cut" href="<?php
            if( $v->product_id ) echo $advs_url.$v->product_id; else echo $v->link;?>">
                <img src="<?php echo $v->logo;?>" />
                <div class="bn_title"><p class="txtclip"><?php echo $v->name;?></p></div>
            </a>
        <?php } } ?>
    </div>
</header>
<style>
    *{margin:0px;padding:0px;}
    body,html{background:#f4f4f4;}
    .box{width:100%;box-sizing:border-box;padding:0 3% 3% 3%;}
    .box a{display:block;margin:12px auto;}
    /*.box a img{display:block;width:300px;height:135px;}*/
</style>
<script>
    $.fn.imgscroll({
        imgrate : 640/290,
        circlesize: '8px'
    })
</script>
<div class="tp_list hide"  id="nearbyBox">
    <div style="text-align: center;padding-top:10%;">努力加载中...</div>
</div>
<div class="box">

    <a href="<?php echo Soma_const_url::inst()->get_category(array('catid'=>10178,'id'=>$inter_id)); //枕头?>">
        <img src="<?php echo base_url('public/soma');?>/images/super82.jpg"/>
    </a>
</div>
</body>
</html>
