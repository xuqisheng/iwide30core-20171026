<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<head>
    <link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet"/>
    <!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        //全局变量
        var GV = {
            DIMAUB: "<?php echo base_url();?>",
            JS_ROOT: "<?php echo FD_PUBLIC ?>/js/",
        };
    </script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/wind.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/content_addtop.js"></script>
    <style type="text/css">
        .box-body .table-striped > tbody > tr > td {
            background: #FFFFFF;
            vertical-align: middle;
            text-align: center;
        }

        .control-group {
            background: #fff;
            padding: 10px;
            float: left;
            width: 100%;
        }

        .controls {
            float: left;
            padding: 5px;
            width: 30%;
        }

        .control-input {
            float: right;
        }

        .controls span {
            margin-left: 10%;
        }

        .control-group .title {
        }

        .controls1 {
            float: left;
            padding: 5px;
        }

        .m_r_75 {
            margin-right: 11px;
        }

        .m_r_20 {
            margin-rgiht: 20px;
        }

        .m_l_10 {
            margin-left: 10px;
        }

        .p_3 {
            padding: 3px !important;
        }

        .width_60 {
            width: 60px;
        }

        .min_w_80 {
            width: 100px;
        }

        .width_120 {
            width: 120px;
            display: inline-block;
            text-align: left;
        }

        .width_376 {
            width: 376px;
        }

        .f_notes {
            font-size: 10px;
            color: #999999;
            float: left;
            padding-left: 10px;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php /* 顶部导航 */
    echo $block_top; ?>

    <?php /*左栏菜单*/
    echo $block_left; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                微信会员卡
                <small></small>
            </h1>
        </section>

        <section class="content">
            <form id="myForm" action="<?php echo base_url('index.php/membervip/wxmember/saveconfig'); ?>" method="post">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" />
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">会员卡领取链接</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <tbody>
                            <tr>
                                <td class="min_w_80">领取链接</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            http://front.iwide.com
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">会员卡设置</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <tbody>
                            <tr>
                                <td class="min_w_80">审核状态</td>
                                <td>

                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
<?php if (isset($send_check)) {if ($send_check == 1){echo '通过';}elseif ($send_check == 2) {echo '不通过';}elseif ($send_check == 3){echo '审核中';}}else {echo '请完善资料';}?>

                                            &nbsp;&nbsp;
                                            <label></label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">商户名称</td>
                                <td>

                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="text" name="brand_name" value="<?php if (isset($brand_name))echo $brand_name;?>">
                                            &nbsp;&nbsp;
                                            <label>商户名字，字数上限为12个汉字。</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">卡名称</td>
                                <td>

                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="text" name="title" value="<?php if (isset($title))echo $title;?>">
                                            &nbsp;&nbsp;
                                            <label>卡名称，字数上限为9个汉字。</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">使用提醒</td>
                                <td>

                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="text" name="notice" value="<?php if (isset($notice))echo $notice;?>">
                                            &nbsp;&nbsp;
                                            <label>卡卷提醒，字数上限为9个汉字。</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">数量</td>
                                <td>

                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="text" name="quantity" value="<?php if (isset($quantity))echo $quantity;?>" <?php if (isset($quantity))echo 'disabled';?>>
                                            &nbsp;&nbsp;
                                            <label>至少一张。</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">商户图标</td>
                                <td>

                                    <div class="control-group p_3">
                                        <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                                            <input type="hidden" name="ftp_logo_url" id="ftp_logo_url" value="<?php if (isset($ftp_logo_url) && $ftp_logo_url != '')echo $ftp_logo_url;?>">
                                            <a class="thumb-row" href="javascript:void(0);"
                                               onclick="flashupload('thumb_images', 'logo上传','ftp_logo_url',thumb_images,'front,wx_card,1,1024,jpg|png');return false;">
                                                <?php if (isset($ftp_logo_url) && $ftp_logo_url != ''){?>
                                                <img
                                                    src="<?php if (isset($ftp_logo_url))echo $ftp_logo_url;?>"
                                                    id="ftp_logo_url_preview"
                                                    style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                <?php }else{?>
                                                <img src="<?php echo base_url(FD_PUBLIC); ?>/images/default-thumb.png"
                                                     id="ftp_logo_url_preview"
                                                     style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                <?php }?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">会员卡背景类型</td>
                                <td>

                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="radio" name="wx_bg_type" value="unify" <?php if (isset($wx_bg_type) && $wx_bg_type == 'unify') echo 'checked';?>><label>统一设置</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            <input type="radio" name="wx_bg_type"
                                                   value="differ" <?php if (isset($wx_bg_type) && $wx_bg_type == 'differ') echo 'checked';?>><label>按等级设置</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">背景图</td>
                                <td>

                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                                                <input type="hidden" name="ftp_wx_unify_bg_url" id="ftp_wx_unify_bg_url" value="<?php  if (isset($ftp_wx_unify_bg_url) && $ftp_wx_unify_bg_url != '')echo $ftp_wx_unify_bg_url; ?>">
                                                <a class="thumb-row" href="javascript:void(0);"
                                                   onclick="flashupload('thumb_images', '背景图上传','ftp_wx_unify_bg_url',thumb_images,'front,wx_card,1,1024,jpg|png');return false;">
                                                    <?php if (isset($ftp_wx_unify_bg_url) && $ftp_wx_unify_bg_url != ''){?>
                                                    <img
                                                        src="<?php if (isset($ftp_wx_unify_bg_url) && $ftp_wx_unify_bg_url != '') echo $ftp_wx_unify_bg_url; ?>"
                                                        id="ftp_wx_unify_bg_url_preview"
                                                        style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                    <?php }else{?>
                                                    <img
                                                        src="<?php echo base_url(FD_PUBLIC); ?>/images/default-thumb.png"
                                                        id="ftp_wx_unify_bg_url_preview"
                                                        style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                    <?php } ?>
                                                </a>
                                                <div>统一背景</div>

                                            </div>
                                        </div>
                                        <?php
                                        if (isset($wx_bg_list) && is_array($wx_bg_list)) {
                                            foreach ($wx_bg_list as $k => $v) {
                                                ?>
                                                <div class="controls1 m_r_75">
                                                    <div class="tv-thumb"
                                                         style="float: left;width: 130px;text-align: center;">
                                                        <input type="hidden"
                                                               name="ftp_wx_lvl_bg_url_<?php if(isset($v['member_lvl_id'])) echo $v['member_lvl_id']; ?>"
                                                               id="ftp_wx_lvl_bg_url_<?php if (isset($v['member_lvl_id'])) echo $v['member_lvl_id']; ?>"
                                                               value="<?php if (isset($v['ftp_wx_lvl_bg_url']) && $v['ftp_wx_lvl_bg_url'] != '') echo $v['ftp_wx_lvl_bg_url']; ?>">
                                                        <a class="thumb-row" href="javascript:void(0);"
                                                           onclick="flashupload('thumb_images', '背景图上传','ftp_wx_lvl_bg_url_<?php  if(isset($v['member_lvl_id'])) echo $v['member_lvl_id']; ?>',thumb_images,'front,wx_card,1,1024,jpg|png');return false;">
                                                            <?php  if (isset($v['ftp_wx_lvl_bg_url']) && $v['ftp_wx_lvl_bg_url'] != ''){?>
                                                            <img
                                                                src="<?php if (isset($v['ftp_wx_lvl_bg_url']) && $v['ftp_wx_lvl_bg_url'] != '') echo $v['ftp_wx_lvl_bg_url'];?>"
                                                                id="ftp_wx_lvl_bg_url_<?php if(isset($v['member_lvl_id'])) echo $v['member_lvl_id']; ?>_preview"
                                                                style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                            <?php } else {?>
                                                            <img

                                                                src="<?php echo base_url(FD_PUBLIC); ?>/images/default-thumb.png"
                                                                id="ftp_wx_lvl_bg_url_<?php if(isset($v['member_lvl_id'])) echo $v['member_lvl_id']; ?>_preview"
                                                                style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                            <?php }?>
                                                        </a>
                                                        <div><?php if (isset($v['lvl_name'])) echo $v['lvl_name']; ?></div>

                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">会员卡基本信息</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <tbody>
                            <tr>
                                <input type="hidden" name="supply_bonus" id="supply_bonus" value="<?php if (isset($supply_bonus)) echo $supply_bonus;?>">
                                <input type="hidden" name="supply_balance" id="supply_balance" value="<?php if (isset($supply_balance)) echo $supply_balance;?>">
                            </tr>
                            <tr>
                                <td class="min_w_80">快捷栏1</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input <?php if ((isset($custom_field1) && $custom_field1 != '') || (isset($bonus_order) && $bonus_order == 1) || (isset($balance_order) && $balance_order == 1)) {echo 'checked';} ?>
                                                type="radio" name="shortcut_bar1_is_set" class="shortcut_bar1_is_set" value="1"
                                                <?php if ((isset($bonus_order) && $bonus_order == 1) || (isset($balance_order) && $balance_order == 1)) {echo 'disabled';}?>
                                            ><label>显示</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            <input type="radio" name="shortcut_bar1_is_set" <?php if (isset($custom_field1) && $custom_field1 == '' && (isset($bonus_order) && $bonus_order != 1) && (isset($balance_order) && $balance_order != 1)) {echo 'checked';} ?> class="shortcut_bar1_is_set" value="2"
                                                <?php if ((isset($bonus_order) && $bonus_order == 1) || (isset($balance_order) && $balance_order == 1)) {echo 'disabled';}?>><label>不显示</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            <select name="shortcut_bar1_name_type" id="shortcut_bar1_name_type" <?php if ((isset($bonus_order) && $bonus_order == 1) || (isset($balance_order) && $balance_order == 1)) {echo 'disabled';}?> >
                                                <option value="custom" <?php if (isset($custom_field1['name']) && $custom_field1['name'] == 'custom') { echo 'selected = "selected"';} ?> >自定义</option>
                                                <option value="bonus" <?php if ((isset($bonus_order) && $bonus_order == 1)) {echo 'selected = "selected"';} ?> >积分</option>
                                                <option value="balance" <?php if ((isset($balance_order) && $balance_order == 1)) {echo 'selected = "selected"';} ?> >余额</option>
                                                <option value="FIELD_NAME_TYPE_LEVEL" <?php if (isset($custom_field1['name_type']) && $custom_field1['name_type'] == 'FIELD_NAME_TYPE_LEVEL') { echo 'selected = "selected"';} ?>>等级</option
                                                <option value="FIELD_NAME_TYPE_COUPON" <?php if (isset($custom_field1['name_type']) && $custom_field1['name_type'] == 'FIELD_NAME_TYPE_COUPON') { echo 'selected = "selected"';} ?>>优惠券</option>

                                            </select>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            显示名称：
                                            <input <?php if (!isset($custom_field1['name'])) {echo 'disabled';}?> type="text" name="shortcut_bar1_name" id="shortcut_bar1_name" value="<?php if (isset($custom_field2['name'])) {echo $custom_field2['name'];}?>">
                                        </div>
                                        <div class="controls1 m_r_75">
                                            跳转URL
                                            <input <?php if (!isset($custom_field1['name'])) {echo 'disabled';}?> type="text" name="shortcut_bar1_url" id="shortcut_bar1_url" value="<?php if (isset($custom_field2['name'])) {echo $custom_field2['url'];}?>" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">快捷栏2</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input <?php if ((isset($custom_field2) && $custom_field2 != '') || (isset($bonus_order) && $bonus_order == 2) || (isset($balance_order) && $balance_order == 2)) {echo 'checked';} ?>
                                                type="radio" name="shortcut_bar2_is_set" class="shortcut_bar2_is_set" value="1"
                                                <?php if ((isset($bonus_order) && $bonus_order == 2) || (isset($balance_order) && $balance_order == 2)) {echo 'disabled';}?>><label>显示</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            <input <?php if (isset($custom_field2) && $custom_field2 == '' && (isset($bonus_order) && $bonus_order != 2) && (isset($balance_order) && $balance_order != 2)) {echo 'checked';} ?>
                                                type="radio" name="shortcut_bar2_is_set" class="shortcut_bar2_is_set" value="2"
                                                <?php if ((isset($bonus_order) && $bonus_order == 2) || (isset($balance_order) && $balance_order == 2)) {echo 'disabled';}?> ><label>不显示</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            <select name="shortcut_bar2_name_type" id="shortcut_bar2_name_type" <?php if ((isset($bonus_order) && $bonus_order == 2) || (isset($balance_order) && $balance_order == 2)) {echo 'disabled';}?> >
                                                <option value="custom" <?php if (isset($custom_field2['name']) && $custom_field2['name'] == 'custom') { echo 'selected = "selected"';} ?> >自定义</option>
                                                <option value="bonus" <?php if ((isset($bonus_order) && $bonus_order == 2)) {echo 'selected = "selected"';} ?> >积分</option>
                                                <option value="balance" <?php if ((isset($balance_order) && $balance_order == 2)) {echo 'selected = "selected"';} ?> >余额</option>
                                                <option value="FIELD_NAME_TYPE_LEVEL" <?php if (isset($custom_field2['name_type']) && $custom_field2['name_type'] == 'FIELD_NAME_TYPE_LEVEL') { echo 'selected = "selected"';} ?>>等级</option
                                                <option value="FIELD_NAME_TYPE_COUPON" <?php if (isset($custom_field2['name_type']) && $custom_field2['name_type'] == 'FIELD_NAME_TYPE_COUPON') { echo 'selected = "selected"';} ?>>优惠券</option>

                                            </select>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            显示名称：
                                            <input <?php if (!isset($custom_field2['name'])) {echo 'disabled';}?> type="text" name="shortcut_bar2_name" id="shortcut_bar2_name" value="<?php if (isset($custom_field2['name'])) {echo $custom_field2['name'];}?>">
                                        </div>
                                        <div class="controls1 m_r_75">
                                            跳转URL
                                            <input <?php if (!isset($custom_field2['name'])) {echo 'disabled';}?> type="text" name="shortcut_bar2_url" id="shortcut_bar2_url" value="<?php if (isset($custom_field2['name'])) {echo $custom_field2['url'];}?>">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">快捷栏3</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input <?php if (isset($custom_field3) && $custom_field3 != '') {echo 'checked';} ?> type="radio" name="shortcut_bar3_is_set" class="shortcut_bar3_is_set" value="1"><label>显示</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            <input <?php if (isset($custom_field3) && $custom_field3 == '') {echo 'checked';} ?> type="radio" name="shortcut_bar3_is_set" class="shortcut_bar3_is_set" value="2"><label>不显示</label>

                                        </div>
                                        <div class="controls1 m_r_75">
                                            <select name="shortcut_bar3_name_type" id="shortcut_bar3_name_type">
                                                <option value="custom" <?php if (isset($custom_field3['name']) && $custom_field3['name'] == 'custom') { echo 'selected = "selected"';} ?> >自定义</option>
                                                <option value="FIELD_NAME_TYPE_LEVEL" <?php if (isset($custom_field3['name_type']) && $custom_field3['name_type'] == 'FIELD_NAME_TYPE_LEVEL') { echo 'selected = "selected"';} ?> >等级</option>
                                                <option value="FIELD_NAME_TYPE_COUPON" <?php if (isset($custom_field3['name_type']) && $custom_field3['name_type'] == 'FIELD_NAME_TYPE_COUPON') { echo 'selected = "selected"';} ?> >优惠券</option>
                                            </select>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            显示名称：
                                            <input <?php if (!isset($custom_field3['name'])) {echo 'disabled';}?> type="text" name="shortcut_bar3_name" id="shortcut_bar3_name" value="<?php if (isset($custom_field3['name'])) {echo $custom_field3['name'];}?>" >
                                        </div>
                                        <div class="controls1 m_r_75">
                                            跳转URL
                                            <input <?php if (!isset($custom_field3['name'])) {echo 'disabled';}?> type="text" name="shortcut_bar3_url" id="shortcut_bar3_url" value="<?php if (isset($custom_field3['name'])) {echo $custom_field3['url'];}?>">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">中间按钮设置</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <tbody>
                            <tr>
                                <td class="min_w_80">是否设置</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input <?php if (isset($center_title) && $center_title != '') {echo 'checked';} ?> type="radio" name="center_btn_is_set" value="1" class="center_btn_is_set"><label>设置</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            <input <?php if (isset($center_title) && $center_title == '') {echo 'checked';} ?> type="radio" name="center_btn_is_set"
                                                   value="2" class="center_btn_is_set"><label>不设置</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">使用按钮名称</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="text" name="center_btn_title" value="<?php if (isset($center_title) && $center_title != '') {echo $center_title;} ?>" class="center_btn">&nbsp;&nbsp;<label>使用按钮名称，字数上限为6个汉字</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">使用按钮链接</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="text" name="center_btn_url"
                                                   value="<?php if (isset($center_sub_title) && $center_sub_title != '') {echo $center_sub_title;} ?>" class="center_btn">&nbsp;&nbsp;<label>跳转的页面网址</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">使用按钮说明</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="text" name="center_btn_sub_title"
                                                   value="<?php if (isset($center_url) && $center_url != '') {echo $center_url;} ?>" class="center_btn">&nbsp;&nbsp;<label>使用按钮名称，字数上限为20个汉字</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">自定义跳转栏设置</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <tbody>
                            <tr>
                                <td class="min_w_80">自定义跳转栏1是否设置</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="radio" name="custom_field1_is_set" <?php if (isset($custom_cell1) && $custom_cell1 != '') {echo 'checked';} ?>
                                                
                                                   value="1" class="custom_field1_is_set">&nbsp;&nbsp;<label>设置</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            <input type="radio" name="custom_field1_is_set" <?php if (isset($custom_cell1) && $custom_cell1 == '') {echo 'checked';} ?>
                                                   value="2" class="custom_field1_is_set">&nbsp;&nbsp;<label>不设置</label>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">自定义跳转栏1具体内容</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            名称
                                            <input type="text" name="custom_field1_name"
                                                   value="<?php if (isset($custom_cell1['name'])) {echo $custom_cell1['name'];} ?>" class="custom_field1">&nbsp;&nbsp;<label>使用按钮名称，字数上限为5个汉字</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            引导语
                                            <input type="text" name="custom_field1_tips"
                                                   value="<?php if (isset($custom_cell1['tips'])) {echo $custom_cell1['tips'];} ?>" class="custom_field1">&nbsp;&nbsp;<label>使用按钮名称，字数上限为6个汉字</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            链接地址
                                            <input type="text" name="custom_field1_url"
                                                   value="<?php if (isset($custom_cell1['url'])) {echo $custom_cell1['url'];} ?>" class="custom_field1">&nbsp;&nbsp;<label>使用按钮名称，字数上限为6个汉字</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">自定义跳转栏2是否设置</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="radio" name="custom_field2_is_set" <?php if (isset($custom_cell2) && $custom_cell2 != '') {echo 'checked';} ?>
                                                   value="1" class="custom_field2_is_set">&nbsp;&nbsp;<label>设置</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            <input type="radio" name="custom_field2_is_set" <?php if (isset($custom_cell2) && $custom_cell2 == '') {echo 'checked';} ?>
                                                   value="2" class="custom_field2_is_set">&nbsp;&nbsp;<label>不设置</label>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">自定义跳转栏2</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            名称
                                            <input type="text" name="custom_field2_name"
                                                   value="<?php if (isset($custom_cell2['name'])) {echo $custom_cell2['name'];} ?>" class="custom_field2">&nbsp;&nbsp;<label>使用按钮名称，字数上限为5个汉字</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            引导语
                                            <input type="text" name="custom_field2_tips"
                                                   value="<?php if (isset($custom_cell2['tips'])) {echo $custom_cell2['tips'];} ?>" class="custom_field2">&nbsp;<label>使用按钮名称，字数上限为6个汉字</label>
                                        </div>
                                        <div class="controls1 m_r_75">
                                            链接地址
                                            <input type="text" name="custom_field2_url"
                                                   value="<?php if (isset($custom_cell2['url'])) {echo $custom_cell2['url'];} ?>" class="custom_field2">&nbsp;&nbsp;<label>使用按钮名称，字数上限为6个汉字</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">其他信息</h3>
                    </div>
                    <div class="box-body">
                        <table
                            class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <tbody>
                            <tr>
                                <td class="min_w_80">商家电话</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="text" name="service_phone"
                                                   value="<?php if (isset($service_phone)) {echo $service_phone;}?>">&nbsp;&nbsp;<label>该信息会显示在会员卡详情页页上，只能填写一个电话（建议可填写400热线）</label>
                                            <div class="controls1 m_r_75">
                                            </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">可用类型</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                            <input type="radio" name="location_id_type" value="all" class="location_id_type" checked>&nbsp;&nbsp;<label>全部店铺可用</label>
                                        </div>
<!--                                        <div class="controls1 m_r_75">-->
<!--                                            <input type="radio" name="location_id_type"-->
<!--                                                   value="choose" class="location_id_type">&nbsp;&nbsp;<label>选中店铺可用</label>-->
<!--                                        </div>-->
                                    </div>
                                </td>
                            </tr>
<!--                            <tr>-->
<!--                                <td class="min_w_80">选择店铺</td>-->
<!--                                <td>-->
<!--                                    <div class="control-group p_3">-->
<!--                                        <div class="controls1 m_r_75">-->
<!--                                            <input type="checkbox" name="location_id_list"-->
<!--                                                   value="293" class="location_id_list"><label>阳光酒店</label>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </td>-->
<!--                            </tr>-->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">会员卡详情</h3>
                    </div>
                    <div class="box-body">
                        <table
                            class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <tbody>
                            <tr>
                                <td class="min_w_80">会员卡特权</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                                            <textarea name="prerogative"><?php if( isset($prerogative) ) {echo $prerogative;} ?></textarea>
                                            <label>字数上限为300个汉字。</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="min_w_80">使用须知</td>
                                <td>
                                    <div class="control-group p_3">
                                        <div class="controls1 m_r_75">
                                                            <textarea name="description"><?php if( isset($description) ) {echo $description;} ?></textarea>
                                            <label>字数上限为300个汉字。</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </section>
        <div class="" style="padding:10px 0;width:420px; text-align: center;">
            <button id="bnt_sub" type="submit" class="btn btn-primary dosave" style="margin: 0, auto;">保存</button>
        </div>
    </div><!-- /.content-wrapper -->
</div><!-- ./wrapper -->
<script type="application/javascript">
    $(function () {
        Wind.use("ajaxForm", function () {
            $(document).on('click', '.dosave', function (e) {
                e.preventDefault();
                var form = $('#myForm');
                var form_url = form.attr("action");
                console.log(form_url);
                form.ajaxSubmit({
                    url: form_url,
                    dataType: 'json',
                    type: 'post',
                    beforeSubmit: function (arr) {
                        console.log(JSON.stringify(arr));

                    },
                    success: function (data) {
                        console.log(data);
//                        if (data.status == 1) {
//                           alert('保存成功');
//                        } else {
//                            alert('保存失败,原因:'+data.data);
//                        }
                    },
                    error: function (XmlHttpRequest, textStatus, errorThrown) {
                        console.log(textStatus);
                        console.log(XmlHttpRequest);
                        console.log(errorThrown);
                    }
                });
            });
        });
        // START=========不显示快捷栏,就禁用========================================
        $('.shortcut_bar1_is_set').on('change',function () {
            if($(this).val() == 2) {
                $('#shortcut_bar1_name').attr("disabled","disabled");
                $('#shortcut_bar1_name_type').attr("disabled","disabled");
                $('#shortcut_bar1_url').attr("disabled","disabled");
            } else {
                $('#shortcut_bar1_name').removeAttr("disabled");
                $('#shortcut_bar1_name_type').removeAttr("disabled");
                $('#shortcut_bar1_url').removeAttr("disabled");
            }
        });
        $('.shortcut_bar2_is_set').on('change',function () {
            if($(this).val() == 2) {
                $('#shortcut_bar2_name').attr("disabled","disabled");
                $('#shortcut_bar2_name_type').attr("disabled","disabled");
                $('#shortcut_bar2_url').attr("disabled","disabled");
            } else {
                $('#shortcut_bar2_name').removeAttr("disabled");
                $('#shortcut_bar2_name_type').removeAttr("disabled");
                $('#shortcut_bar2_url').removeAttr("disabled");
            }
        });
        $('.shortcut_bar3_is_set').on('change',function () {
            if($(this).val() == 2) {
                $('#shortcut_bar3_name').attr("disabled","disabled");
                $('#shortcut_bar3_name_type').attr("disabled","disabled");
                $('#shortcut_bar3_url').attr("disabled","disabled");
            } else {
                $('#shortcut_bar3_name').removeAttr("disabled");
                $('#shortcut_bar3_name_type').removeAttr("disabled");
                $('#shortcut_bar3_url').removeAttr("disabled");
            }
        })
        // END=========不显示快捷栏,就禁用========================================


        // start ============避免快捷栏信息重复==================================
        var shortcut_options = {
            custom: '<option value="custom">自定义</option>',
            bonus: '<option value="bonus">积分</option>',
            balance: '<option value="balance">余额</option>',
            FIELD_NAME_TYPE_LEVEL: '<option value="FIELD_NAME_TYPE_LEVEL">等级</option>',
            FIELD_NAME_TYPE_COUPON: '<option value="FIELD_NAME_TYPE_COUPON">优惠券</option>'
        };
        var dis_arr = ["bonus", "balance", "FIELD_NAME_TYPE_LEVEL", "FIELD_NAME_TYPE_COUPON"];
        $('#shortcut_bar1_name_type').on('focus',function () {
            var bar2_val = $('#shortcut_bar2_name_type').val();
            var bar3_val = $('#shortcut_bar3_name_type').val();
            var able_opts = [];
            $(this).html("");
            var x;
            for (x in shortcut_options) {
                if (x != bar2_val && x != bar3_val) {
                    able_opts.push(shortcut_options[x]);
                }
            }
            if (able_opts.indexOf(shortcut_options['custom']) < 0) able_opts.push(shortcut_options['custom']);
            console.log(able_opts);
            $(this).html(able_opts.join(" "));
            $('#shortcut_bar1_name').removeAttr("disabled");
            $('#shortcut_bar1_url').removeAttr("disabled");
        });
        $('#shortcut_bar2_name_type').on('focus',function () {
            var bar1_val = $('#shortcut_bar1_name_type').val();
            var bar3_val = $('#shortcut_bar3_name_type').val();
            var able_opts = [];
            $(this).html("");
            var x;
            for (x in shortcut_options) {
                if (bar1_val == 'custom') {
                    able_opts.push(shortcut_options['custom']);
                    break;
                }
                if (x != bar1_val && x != bar3_val) {

                    able_opts.push(shortcut_options[x]);
                }
            }
            if (able_opts.indexOf(shortcut_options['custom']) < 0) able_opts.push(shortcut_options['custom']);
            console.log(able_opts);
            $(this).html(able_opts.join(" "));
            $('#shortcut_bar2_name').removeAttr("disabled");
            $('#shortcut_bar2_url').removeAttr("disabled");
        });
        $('#shortcut_bar3_name_type').on('focus',function () {
            var bar1_val = $('#shortcut_bar1_name_type').val();
            var bar2_val = $('#shortcut_bar2_name_type').val();
            var able_opts = [];
            $(this).html("");
            var x;
            for (x in shortcut_options) {
                if (bar1_val == 'custom') {
                    able_opts.push(shortcut_options['custom']);
                    break;
                }
                if (x != bar1_val && x != bar2_val) {

                    able_opts.push(shortcut_options[x]);
                }
            }
            if (able_opts.indexOf(shortcut_options['custom']) < 0) able_opts.push(shortcut_options['custom']);
            if (able_opts.indexOf(shortcut_options['bonus']) >= 0) delete able_opts[able_opts.indexOf(shortcut_options['bonus'])];
            if (able_opts.indexOf(shortcut_options['balance']) >= 0) delete able_opts[able_opts.indexOf(shortcut_options['balance'])];
            console.log(able_opts);
            $(this).html(able_opts.join(" "));
            $('#shortcut_bar3_name').removeAttr("disabled");
            $('#shortcut_bar3_url').removeAttr("disabled");
        });
        $('#shortcut_bar1_name_type').on('change',function () {
            var key = $(this).val();
            var bar2_val = $('#shortcut_bar2_name_type').val();
            if (key == 'bonus' || bar2_val == 'bonus') {
                $('#supply_bonus').val("t");
            } else {
                $('#supply_bonus').val("f");
            }
            if (key == 'balance' || bar2_val == 'balance') {
                $('#supply_balance').val("t");
            } else {
                $('#supply_balance').val("f");
            }
            if (dis_arr.indexOf(key) >= 0) {
                $('#shortcut_bar1_name').attr("disabled","disabled");
                $('#shortcut_bar1_url').attr("disabled","disabled");
            }
        });
        $('#shortcut_bar2_name_type').on('change',function () {
            var key = $(this).val();
            var bar1_val = $('#shortcut_bar1_name_type').val();
            if (key == 'bonus' || bar1_val == 'bonus') {
                $('#supply_bonus').val("t");
            } else {
                $('#supply_bonus').val("f");
            }
            if (key == 'balance' || bar1_val == 'balance') {
                $('#supply_balance').val("t");
            } else {
                $('#supply_balance').val("f");
            }
            if (dis_arr.indexOf(key) >= 0) {
                $('#shortcut_bar2_name').attr("disabled","disabled");
                $('#shortcut_bar2_url').attr("disabled","disabled");
            }
        });

        $('#shortcut_bar3_name_type').on('change',function () {
            var key = $(this).val();
            console.log(key);
            if (dis_arr.indexOf(key) >= 0) {
                $('#shortcut_bar3_name').attr("disabled","disabled");
                $('#shortcut_bar3_url').attr("disabled","disabled");
            }
        });
        // end ============避免快捷栏信息重复==================================
        // start ==========中间按钮禁用判断=============
        $('.center_btn_is_set').on('change', function () {
            if ($(this).val() == '2') {
                $('.center_btn').attr("disabled", "disabled");
            } else {
                $('.center_btn').removeAttr("disabled");
            }
        });
        // end ==========中间按钮禁用判断=============
        // start ========自定义判断=================
        $('.custom_field1_is_set').on('change', function () {
            if ($(this).val() == '2') {
                $('.custom_field1').attr("disabled", "disabled");
            } else {
                $('.custom_field1').removeAttr("disabled");
            }
        });
        $('.custom_field2_is_set').on('change', function () {
            if ($(this).val() == '2') {
                $('.custom_field2').attr("disabled", "disabled");
            } else {
                $('.custom_field2').removeAttr("disabled");
            }
        });
        // end ========自定义判断=================
        // start =======店铺可用判断=================
        $('.location_id_type').on('change', function () {
            if ($(this).val() == 'all') {
                $('.location_id_list').attr("disabled", "disabled");
            } else {
                $('.location_id_list').removeAttr("disabled");
            }
        });
        // end =======店铺可用判断=================
        // start ==========背景=============

        // end ========================

    });
</script>
<?php /* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php'; ?> -->
</body>
</html>
