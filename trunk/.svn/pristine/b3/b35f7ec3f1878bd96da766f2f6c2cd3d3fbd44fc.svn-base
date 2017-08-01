<body>
<script src="<?php echo base_url('public/soma/scripts/imgscroll.js'); ?>"></script>
<script src="<?php echo base_url('public/soma/scripts/jquery.touchwipe.min.js'); ?>"></script>
<script>
    wx.config({
        debug: false,
        appId: '<?php echo $wx_config["appId"]?>',
        timestamp: <?php echo $wx_config["timestamp"]?>,
        nonceStr: '<?php echo $wx_config["nonceStr"]?>',
        signature: '<?php echo $wx_config["signature"]?>',
        jsApiList: [<?php echo $js_api_list; ?>, 'getLocation', 'openLocation']
    });
    wx.ready(function () {
        <?php if( $js_menu_hide ): ?>wx.hideMenuItems({menuList: [<?php echo $js_menu_hide; ?>]});<?php endif; ?>
        <?php if( $js_menu_show ): ?>wx.showMenuItems({menuList: [<?php echo $js_menu_show; ?>]});<?php endif; ?>

        <?php if( $js_share_config ): ?>
        wx.onMenuShareTimeline({
            title: '<?php echo $js_share_config["title"]?>',
            link: '<?php echo $js_share_config["link"]?>',
            imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
            success: function () {
            },
            cancel: function () {
            }
        });
        wx.onMenuShareAppMessage({
            title: '<?php echo $js_share_config["title"]?>',
            desc: '<?php echo $js_share_config["desc"]?>',
            link: '<?php echo $js_share_config["link"]?>',
            imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
            //type: '', //music|video|link(default)
            //dataUrl: '', //use in music|video
            success: function () {
            },
            cancel: function () {
            }
        });
        <?php endif; ?>
    });
</script>
<div id="seckill-detail">
    <div class="wrap">
        <div class="pageloading"><p class="isload"><?php echo $lang->line('loading'); ?></p></div>

        <header class="headers banner">
            <div class="headerslide">
                <?php if ($gallery): ?>
                    <?php foreach ($gallery as $k => $v) { ?>
                        <a class="slideson ui_img_auto_cut">
                            <img src="<?php echo $v['gry_url']; ?>"/>
                        </a>
                    <?php } ?>
                <?php else: ?>
                    <a class="slideson ui_img_auto_cut">
                        <img src="<?php echo base_url('public/soma/images/default.jpg'); ?>"/>
                    </a>
                <?php endif; ?>
            </div>
            <?php if (isset($package['scopes']) && isset($package['scopes'][0]) && $package['scopes'][0]['limit_num'] > 0) : ?>
                <div class="xgcs"><?php echo str_replace('[0]', $package['scopes'][0]['limit_num'] - $package['scopes'][0]['used_num'], $lang->line('exclusive_limit')); ?></div>
            <?php endif; ?>
        </header>

        <!-- 商品信息 -->
        <div class="info pd-19 bg-white  box">
            <div class="info_left f24 c333">
                <?php echo $package['name']; ?>
            </div>
            <div class="info_right f20 ta-r flex1 c98">
                <?php if ($package['show_sales_cnt'] == Soma_base::STATUS_TRUE): ?>
                    <?php echo $lang->line('sold') . ' ' . $package['sales_cnt']; ?>
                <?php endif; ?>
            </div>
        </div>
        <!-- 商品信息 -->

        <?php
            // 无属性
            $no_attr_flag = true;
            if ($package['can_refund'] != $packageModel::CAN_REFUND_STATUS_FAIL && $package['type'] != $packageModel::PRODUCT_TYPE_BALANCE && $package['type'] != $packageModel::PRODUCT_TYPE_POINT) {
                $no_attr_flag = false;
            }

            if ($package['can_gift'] == $packageModel::CAN_T) {
                $no_attr_flag = false;
            }

            if ($package['can_mail'] == $packageModel::CAN_T) {
                $no_attr_flag = false;
            }

            if ($package['can_pickup'] == $packageModel::CAN_T) {
                $no_attr_flag = false;
            }

            if ($package['can_invoice'] == $packageModel::CAN_T) {
                $no_attr_flag = false;
            }

            if ($package['can_split_use'] == $packageModel::CAN_T) {
                $no_attr_flag = false;
            }
        ?>

        <div class="post bg-white mt-16 support_list <?php if ($no_attr_flag): ?>Ldn<?php endif; ?>">
        <?php if ($package['can_refund'] != $packageModel::CAN_REFUND_STATUS_FAIL && $package['type'] != $packageModel::PRODUCT_TYPE_BALANCE && $package['type'] != $packageModel::PRODUCT_TYPE_POINT): ?>

            <div class="item fl f20 c666 box" tips="<?php echo $lang->line('after_buy_apply_refund'); ?>">
                <span class="icon"></span>
                    <span>
                       <?php if ($package['can_refund'] == $packageModel::CAN_REFUND_STATUS_SEVEN): ?>
                           <?php echo $lang->line('7_refund_day'); ?>
                       <?php else: ?>
                           <?php echo $lang->line('refund_any_time'); ?>
                       <?php endif; ?>
                    </span>
            </div>

        <?php endif; ?>

        <?php if ($package['can_gift'] == $packageModel::CAN_T) { ?>
            <div class="item fl f20 c666 box" tips="<?php echo $lang->line('after_buy_donated'); ?>">
                <span class="icon"></span>
                <span>
                    <?php echo $lang->line('gift_a_friend'); ?>
                </span>
            </div>
        <?php } ?>


        <?php if ($package['can_mail'] == $packageModel::CAN_T) { ?>
            <div class="item fl f20 c666 box" tips="<?php echo $lang->line('goods_can_mail'); ?>">
                <span class="icon"></span>
                   <span>
                      <?php echo $lang->line('deliver_to_home'); ?>
                   </span>
            </div>
        <?php } ?>

        <?php if ($package['can_pickup'] == $packageModel::CAN_T) { ?>
            <div class="item fl f20 c666 box" tips="<?php echo $lang->line('goods_support_shop_or_self'); ?>">
                <span class="icon"></span>
                  <span>
                    <?php echo $lang->line('collect_from_hotel'); ?>
                  </span>
            </div>
        <?php } ?>

        <?php if ($package['can_invoice'] == $packageModel::CAN_T) { ?>

            <div class="item fl f20 c666 box" tips="<?php echo $lang->line('purchase_can_invoice'); ?>">
                <span class="icon"></span>
                   <span>
                     <?php echo $lang->line('invoice'); ?>
                   </span>
            </div>

        <?php } ?>

        <?php if ($package['can_split_use'] == $packageModel::CAN_T) { ?>
            <div class="item fl f20 c666 box" tips="<?php echo $lang->line('can_be_used_splitting'); ?>">
                <span class="icon"></span>
                        <span>
                         <?php echo $lang->line('multi_usage');?>
                        </span>
            </div>
        <?php } ?>
        </div>
        <?php
            $content = unserialize($package['compose']);
            // 商品内容为空时自动隐藏
            $flag = false;
            if(is_array($content)) {
                foreach($content as $k=>$v) {
                    if(empty($v['content'])) continue;
                    $flag = true;
                }
            }

            if(!empty($content) && $flag){ ?>
                <div class="bg_fff bd martop block h24 color_555">
                    <p class="bd_bottom f24 c333">
                        <?php echo $lang->line('item_details');?>
                    </p>
                    <ul class="block_list color_888">
                        <li class="color_888 bd_bottom h24">
                            <span class="f20"><?php echo $lang->line('name'); ?></span>
                            <span class="f20"><?php echo $lang->line('number'); ?></span>
                        </li>
                        <?php if(is_array($content)){ foreach($content as $k=>$v){if(empty($v['content'])) continue; ?>
                            <li class="bd_bottom h24 color_555">
                                <span class="f20"><?php echo $v['content'];?></span>
                                <span class="f20"><?php echo $v['num'];?></span>
                            </li>
                        <?php }  }  ?>
                    </ul>
                </div>
        <?php } ?>

        <?php if($package['img_detail'] != null && $package['img_detail'] != ''): ?>
            <div class="bg_fff bd martop block h24 c333" id="showdetail">
                <p class="bd_bottom">
                    <?php echo $lang->line('details');?>
                </p>
                <div class="h24 fillcontent"><?php echo $package['img_detail'];?></div>
            </div>
        <?php endif; ?>

        <?php if($package['hotel_address'] != null && $package['hotel_address'] != ''): ?>
            <div class="bg_fff bd martop block" id="openLocation">
                <em class="iconfont color_888" style="float:right;">&#xe607;</em>
                <p class="txtclip f24" style="width:82%;">
                    <?php echo $lang->line('address') . '：' . $package['hotel_address'];?>
                </p>
            </div>
        <?php endif; ?>
            <div class="foot_fixed foot_fixed__fsy">
                <div class="bg_fff webkitbox bd_top">
                    <a href="<?php echo Soma_const_url::inst()->get_pacakge_home_page(array('id'=>$inter_id)); ?>" class="img_link">
                        <img src="<?php echo base_url('public/soma/v1/images'); ?>/ico9.png"/>
                    </a>
                    <a href="<?php echo Soma_const_url::inst()->get_soma_ucenter(array('id'=>$inter_id)); ?>" class="img_link">
                        <img src="<?php echo base_url('public/soma/v1/images'); ?>/ico10.png"/>
                    </a>
                    <div class="h24 bdradius bg_999" style="border: 1px solid transparent">
                        <?php echo $lang->line('goods_offline_tip'); ?>
                    </div>
                </div>
            </div>
    </div>
</body>

</html>
