<script>
    var package_obj= {
        'appId': '<?php echo $wx_config["appId"]?>',
        'timestamp': <?php echo $wx_config["timestamp"]?>,
        'nonceStr': '<?php echo $wx_config["nonceStr"]?>',
        'signature': '<?php echo $wx_config["signature"]?>'
    }
    /*下列字符不能删除，用作替换之用*/
    //[<sign_update_code>]
    wx.config({
        debug: false,
        appId: package_obj.appId,
        timestamp: package_obj.timestamp,
        nonceStr: package_obj.nonceStr,
        signature: package_obj.signature,
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

        // wx.getLocation({
        //     success: function (res) {
        //         get_package_nearby(res.latitude,res.longitude);
        //     },
        //     cancel: function (res) {
        //         $.MsgBox.Confirm('为了更好的体验，请先授权获取地理位置');
        //     }
        // });
    });
</script>
<body class="bg_fafafa">
<div class="pageloading"><p class="isload">正在加载</p></div>
<div class="index_title" style="display: none;">
    <div><a><span class="active">热门景点</span></a></div>
    <div><a><span class="">附近景点</span></a></div>
</div>
<?php if( $ticketList ):?>
    <?php foreach( $ticketList as $k=>$v ):?>
        <div class="container">
            <div id="slide" class="slide">
                <?php
                    if( isset( $v['block_arr'] ) && $v['block_arr'] ):
                        foreach( $v['block_arr'] as $sk=>$sv ):?>
                            <div class="img">
                                <a href="<?php echo $sv['block_link'];?>"><img src="<?php echo $sv['block_img'];?>"></a>
                            </div>
                <?php 
                        endforeach;
                    endif;
                ?>
                <div class="slide-bt"></div>
            </div>
        </div>
    <?php endforeach;?>
<?php endif;?>
<div class="container_list">
    <?php if( $products ):?>
        <?php foreach( $products as $k=>$v ):?>
            <div class="bg_fff containers m_b_12 clearfix">
                <a href="">
                    <div class="con_img"><img src="<?php echo isset($v['face_img']) ? $v['face_img'] : get_cdn_url('public/soma/images/default.jpg'); ?>"></div>
                    <div class="contents">
                        <div class="cont_title text_ellipsis"><?php echo $v['name'];?></div>
                        <div class="cont_txt"><?php echo $v['hotel_name'];?></div>
                        <div class="cont_price"><span class="cont_price_ico">¥</span><?php echo $v['price_package'];?></div>
                        <div class="d_flex_r cont_btn_list">
                            <a href="<?php echo Soma_const_url::inst()->get_url('*/*/package_detail',array('id'=>$this->inter_id,'pid'=>$v['product_id'],'tkid'=>$ticketId)); ?>">
                                <span class="btn_details color_ff9900">查看详情</span>
                            </a>
                            <a href="<?php echo Soma_const_url::inst()->get_url('*/*/ticket_select_time',array('id'=>$this->inter_id,'pid'=>$v['product_id'],'tkid'=>$ticketId)); ?>">
                                <span class="btn_buy bg_ff9900 color_fff">快速购买</span>
                            </a>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach;?>
    <?php endif;?>
</div>
</body>
</html>
