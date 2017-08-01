<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<!-- 分页 -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<style type="text/css">
    .record{float: right;color: #337ab7;}.scanauth-ok{font-size: 1.3rem}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper wrapper-verifie">
    <?php
    /* 顶部导航 */
    echo $block_top;
    ?>

    <?php
    /* 左栏菜单 */
    echo $block_left;
    ?>
    <link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/jie_h.css'>
    <link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/j_loading.css'>
    <style type="text/css">
        .elastic_con_1_2{width: 355px;}
    </style>
    <div class="over_x">
        <div class="content-wrapper">
            <!-- <div class="banner bg_fff p_0_20">test2</div> -->
            <div class="contents card-info padding_20 font_12 color_333">
                <div class="bg_fff padding_0_15 padding_b_40">
                    <div class="flex font_14 nav_list_btn margin_bottom_30">
                        <div class="actives">券码核销</div>
                        <div class="">扫码核销</div>
                        <div class="">密码核销</div>
                    </div>
                    <div class="nav_list_content">
                        <div class="list_content_item">
                            <div class="bg_f6f6f6 flex secarch_container form_head margin_bottom_30">
                                <div class="bg_fff margin_right_10 relative">
                                    <input class="secarch_input width_327 radius_3 height_28 text_indent_10" type="text" placeholder="请输入12位优惠券码、手机号、会员号" />
                                    <i class="absolute iconfonts color_bfbfbf secarch_ico pointer" >&#xe600;</i>
                                </div>
                                <div>
                                    <div class="btn font_14 bg_b69b69 radius_3 color_fff margin_right_10 secarch_btn">查询</div>
                                </div>
                            </div>
                            <div class="original_con">
                                <div>
                                    <div class="flex start">
                                        <div class="width_60 margin_right_15"><em class="italic color_e0e0e0 font_46 font_familys">01</em></div>
                                        <div class="flex_1">
                                            <div class="padding_5_0">请客人出示优惠券码</div>
                                            <div class="color_808080 padding_5_0">方法一： 请在会员中心>优惠券，点击需要核销的优惠券，进入优惠券详情页面查看券码。</div>
                                            <div class="color_808080 padding_5_0">方法二： 登陆金房卡后台，会员中心4.0>优惠券设置>优惠券列表，找到需要核销的优惠券，点击“领取详情”通过会员号查询该会员的</div>
                                        </div>
                                    </div>
                                    <div class="flex start padding_t_40">
                                        <div class="width_60 margin_right_15"><em class="italic color_e0e0e0 font_46 font_familys">02</em></div>
                                        <div class="flex_1">
                                            <div class="padding_5_0">查询优惠券的有效性</div>
                                            <div class="color_808080 padding_5_0">录入12位优惠券码、用户手机号或会员号查询优惠券的使用状态。</div>
                                            <div class="color_808080 padding_5_0">可使用的优惠券可进行核销。</div>
                                        </div>
                                    </div>
                                    <div class="flex start padding_t_40">
                                        <div class="width_60 margin_right_15"><em class="italic color_e0e0e0 font_46 font_familys">03</em></div>
                                        <div class="flex_1">
                                            <div class="padding_5_0">核销券码</div>
                                            <div class="color_808080 padding_5_0"><span class="color_b69b69">核销</span>，即将优惠券核销为已使用。</div>
                                            <div class="color_808080 padding_5_0"><span class="color_b69b69">设为无效</span>，即取消用户的优惠券，不可使用。</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="j_form-div j_form none">
                                <table id="coupons_table" class="color_333 center j_form none" style="width:100%;">
                                    <thead>
                                    <tr class="border_bottom_ccd6e1_1">
                                        <?php foreach ($fields_config as $k=> $v):?>
                                            <th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?>>
                                                <?php echo $v['label'];?>
                                            </th>
                                        <?php endforeach;?>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="list_content_item none">
                            <div class="flex start padding_20 margin_bottom_45">
                                <div class="flex_1">
                                    <div class="flex start border_right_ccc_1">
                                        <div class="width_60 margin_right_15"><em class="italic color_e0e0e0 font_46 font_familys">01</em></div>
                                        <div class="flex_1">
                                            <div class="padding_5_0">添加核销权限</div>
                                            <div class="color_808080 padding_5_0 margin_bottom_15">管理员须在本页面为店员添加核销权限</div>
                                            <div class="aa_norms pointer flex padding_0_8 height_30 font_14 color_b69b69 radius_3 border_eee_1 bg_f6f6f6 width_110 add_writeoff">+ 添加核销员</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_1" style="padding-left:40px;">
                                    <div class="flex start">
                                        <div class="width_60 margin_right_15"><em class="italic color_e0e0e0 font_46 font_familys">02</em></div>
                                        <div class="flex_1">
                                            <div class="padding_5_0">获取核销入口</div>
                                            <div class="color_808080 padding_5_0">核销员在公众号的会员中心，会增加“扫码核销”功能， 点击进入即可进行扫码核销</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="j_form-div2 j_form none">
                                <table id="coupons_table2" class="color_333 center j_form none" style="width:100%;">
                                    <thead>
                                    <tr class="border_bottom_ccd6e1_1">
                                        <?php foreach ($scanauth_info['fields_config'] as $k=> $v):?>
                                            <th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?>>
                                                <?php echo $v['label'];?>
                                            </th>
                                        <?php endforeach;?>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="list_content_item none">
                            <div class="flex start padding_20">
                                <div class="flex_1">
                                    <div class="flex start" style="margin-bottom:80px;">
                                        <div class="width_60 margin_right_15"><em class="italic color_e0e0e0 font_46 font_familys">01</em></div>
                                        <div class="flex_1">
                                            <div class="padding_5_0">请客人出示密码核销页面</div>
                                            <div class="color_808080 padding_5_0">请客人打开“会员中心>优惠券”,选择需要核销的优惠券， 点击进入查看优惠券详情</div>
                                        </div>
                                    </div>
                                    <div class="flex start">
                                        <div class="width_60 margin_right_15"><em class="italic color_e0e0e0 font_46 font_familys">02</em></div>
                                        <div class="flex_1">
                                            <div class="padding_5_0">录入核销密码</div>
                                            <div class="color_808080 padding_5_0">在优惠券详情页中的“消费码”中录入该优惠券设置的核销密码 录入后点击页面任意位置，即可进行核销</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="width_330 j_img"><img src="http://test008.iwide.cn/public/img/magnifier.png" /></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="p_fixed color_333 none">
        <div class="flex w_h_100">

            <!--展示二维码 modal START-->
            <div class="alert1 center elastic_con_1 elastic_con_1_2 bg_fff radius_3 relative padding_t_20 none">
                <div class="absolute close_btn"><i class="iconfonts font_16">&#xe60a;</i></div>
                <div class="margin_bottom_25 padding_t_40">
                    <div class="er_ma"><img class="qr_er_ma_img" src="<?=EA_const_url::inst()->get_url('*/tool/scanqr',array('str'=>"http://{$public['domain']}/membervip/auth/scan?id={$public['inter_id']}",'fc'=>$rand_code));?>"></div>
                </div>
                <div class="scanauth-ok">请需要添加核销权限的员工用微信扫码上方二维码</div>
                <div class="margin_40_0">
                </div>
            </div>
            <!--展示二维码 modal END-->

            <!--授权扫码核销权限 modal START-->
            <div class="alert2 center elastic_con_1 bg_fff radius_3 relative padding_t_20 none">
                <div class="absolute close_btn"><i class="iconfonts font_16">&#xe60a;</i></div>
                <div class="font_14 font_weight padding_t_40">授权扫码核销权限</div>
                <div class="margin_bottom_25 padding_t_20">
                    <div class="er_ma er_ma_fans"><img src="http://a.hiphotos.baidu.com/baike/w%3D268%3Bg%3D0/sign=7bcb659c9745d688a302b5a29cf91a23/2934349b033b5bb571dc8c5133d3d539b600bc12.jpg"></div>
                </div>
                <div id="fans-name">vv-wing</div>
                <div class="margin_40_0">
                    <div id="auth-content" class="authorized_btn batch_setting_btn pointer padding_10_0 radius_3 font_weight font_16 color_fff bg_b69b69 width_300 center margin_auto">授 权</div>
                </div>
            </div>
            <!--授权扫码核销权限 modal END-->

            <!--核销该优惠券 modal START-->
            <div class="write_alert1 center bg_fff radius_3 relative padding_t_20 width_380 margin_auto none">
                <div class="absolute close_btn"><i class="iconfonts font_16">&#xe60a;</i></div>
                <div class="font_14 padding_t_30 margin_bottom_10">是否将核销该优惠券？</div>
                <div class="color_666" id="write_alert1_code">券码：8739 3849 4730</div>
                <div class="margin_40_0 flex centers">
                    <div class="batch_setting_btn pointer padding_10_0 radius_3 font_weight font_16 color_fff bg_b69b69 width_150 center margin_right_15 cancel_btn">取 消</div>
                    <div class="batch_setting_btn pointer padding_10_0 radius_3 font_weight font_16 color_fff bg_b69b69 width_150 center yes_btn">是 的</div>
                </div>
            </div>
            <!--核销该优惠券 modal END-->

            <!--优惠券设置为”无效“ modal START-->
            <div class="write_alert2 center bg_fff radius_3 relative padding_t_20 width_380 margin_auto none">
                <div class="absolute close_btn"><i class="iconfonts font_16">&#xe60a;</i></div>
                <div class="font_14 padding_t_30 margin_bottom_10">是否将该优惠券设置为”无效“？</div>
                <div class="color_666 margin_bottom_15" id="write_alert2_code">券码：8739 3849 4730</div>
                <div class="color_666 flex centers">
                    <input class="radius_3 border_eee_1 height_28 text_indent_3 write_alert2_remark" name="remak" type="text" required placeholder="填写备注" />
                    <span>&nbsp;必填</span>
                </div>
                <div class="margin_40_0 flex centers">
                    <div class="batch_setting_btn pointer padding_10_0 radius_3 font_weight font_16 color_fff bg_b69b69 width_150 center margin_right_15 cancel_btn">取 消</div>
                    <div class="batch_setting_btn pointer padding_10_0 radius_3 font_weight font_16 color_fff bg_b69b69 width_150 center invalid_btn">是 的</div>
                </div>
            </div>
            <!--优惠券设置为”无效“ modal END-->

            <!--取消授权 modal START-->
            <div class="write_alert3 center bg_fff radius_3 relative padding_t_20 width_380 margin_auto none">
                <div class="absolute close_btn"><i class="iconfonts font_16">&#xe60a;</i></div>
                <div class="font_14 padding_t_30 margin_bottom_10">是否取消该会员的核销资格？</div>
                <div class="color_666" id="write_alert3_name">核销员：</div>
                <div class="margin_40_0 flex centers">
                    <div class="batch_setting_btn pointer padding_10_0 radius_3 font_weight font_16 color_fff bg_b69b69 width_150 center margin_right_15 cancel_btn">取 消</div>
                    <div class="batch_setting_btn pointer padding_10_0 radius_3 font_weight font_16 color_fff bg_b69b69 width_150 center cancel_auth_btn">是 的</div>
                </div>
            </div>
            <!--取消授权 modal END-->

            <div class="j_load_ico margin_auto none">
                <div class="loader">
                    <div class="loader-inner ball-triangle-path relative">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <div class="color_fff load_msg">数据加载中...</div>
            </div>
        </div>
    </div>
    <?php
    /* Footer Block @see footer.php */
    require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
    ?>
    <?php
    /* Right Block @see right.php */
    require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
    ?>
</div><!-- ./wrapper -->
<?php
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.member.min.js"></script>

<script type="text/javascript">
    <?php
    $sort_index= $module->field_index_in_grid($default_sort['field'],13);
    $sort_direct= $default_sort['sort'];

    $sort_index2 = $module->field_index_in_grid($default_sort['field'],14);
    $sort_direct2 = $scanauth_info['default_sort']['sort'];
    $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
    $buttions= '';	//button之间不能有字符空格，用php组装输出
    $get_param = !empty($_GET)?$_GET:array();
    ?>
    var grid_sort = [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];
    var grid_sort2 = [[ <?php echo $sort_index2 ?>, "<?php echo $sort_direct2 ?>" ]];
    var dataSet= [];
    var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
    var columnSet2= <?php echo json_encode( $model->get_column_config($scanauth_info['fields_config']) ); ?>;
    var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');
    var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
    var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
    var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
    var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/verifie",$get_param); ?>';
    var url_ajax2= '<?php echo EA_const_url::inst()->get_url("*/*/get_scanauth_info",$get_param); ?>';
    var url_extra= [];



    $(function(){
        var str='';
        $('.nav_list_btn >div').click(function(){
            var _index=$(this).index();
            $(this).addClass('actives').siblings().removeClass('actives');
            $('.nav_list_content >div').eq(_index).show().siblings().hide();
        });
    })
</script>
<?php
require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'membercard'. DS. 'verifiejs_ajax.php';
?>
</body>
</html>