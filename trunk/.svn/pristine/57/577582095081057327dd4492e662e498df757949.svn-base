<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<!-- 日历 -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/js/jedate.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/jquery.jedate.min.js"></script>
<!-- 分页 -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    //全局变量
    var GV = {
        DIMAUB: "<?php echo base_url();?>",
        JS_ROOT: "<?php echo FD_PUBLIC;?>/js/",
        JS__ROOT:"<?php echo base_url(FD_PUBLIC);?>/js/"
    };
</script>
<script src="<?php echo base_url(FD_PUBLIC);?>/js/wind.js"></script>
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
    <div class="over_x">
        <div class="content-wrapper">
            <!-- <div class="banner bg_fff p_0_20">test1</div> -->
            <div class="contents card-info padding_20 font_12 color_333">
                <div class="bg_fff padding_t_30 padding_b_40">
                    <div class="j_item padding_b_40">
                        <div class="flex row_line center padding_right_30 font_14 color_bfbfbf margin_bottom_30">
                            <div class=" width_150 text_right margin_right_15"><?=$breadcrumb_array['action']?></div>
                        </div>
                        <form class="form_head">
                            <div class="j_tiem_conter secarch_input">
                                <div class="flex wrap padding_right_30">
                                    <div class="flex_1">
                                        <div class="flex margin_bottom_15">
                                            <div class="width_100 text_right margin_right_10">卡券ID或券名:</div>
                                            <div class="">
                                                <input class="width_130 radius_3 border_eee_1 height_30 text_indent_3" type="text" name="keywords" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex_1">
                                        <div class="flex margin_bottom_15">
                                            <div class="width_100 text_right margin_right_10">核销时间:</div>
                                            <div class="width_300">
                                        <span class="relative">
                                            <input name="useoff_sttime" class="formdate width_130 radius_3 border_eee_1 height_30 text_indent_3" type="text" /><i class="absolute iconfonts color_bfbfbf date_ico pointer" >&#xe604;</i></span>
                                                至
                                                <span class="relative">
                                            <input name="useoff_edtime" class="formdate width_130 radius_3 border_eee_1 height_30 text_indent_3" type="text" /><i class="absolute iconfonts color_bfbfbf date_ico pointer">&#xe604;</i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex_1">
                                        <div class="flex margin_bottom_15">
                                            <div class="width_100 text_right margin_right_10">券码:</div>
                                            <div class="">
                                                <input class="width_130 radius_3 border_eee_1 height_30 text_indent_3" type="text" name="coupon_code" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex wrap padding_right_30">
                                    <div class="flex_1">
                                        <div class="flex margin_bottom_15">
                                            <div class="width_100 text_right margin_right_10">状态:</div>
                                            <div class="">
                                                <select class="width_130 radius_3 border_eee_1 height_30 text_indent_3 margin_right_15" name="status">
                                                    <option value="">请选择</option>
                                                    <option value="1">已使用</option>
                                                    <option value="2">已核销</option>
                                                    <option value="3">已失效</option>
                                                </select>
                                            </div>
                                            <div class="btn font_14 bg_b69b69 radius_3 color_fff margin_right_10 secarch_btn">查询</div>
                                            <div class="btn font_14 color_808080 border_bfbfbf_1 radius_3 explore_btn" data-action="<?=EA_const_url::inst()->get_url('*/memberexport/export');?>">导出</div>
                                        </div>
                                    </div>
                                    <!--<div class="flex_1">
                                        <div class="flex margin_bottom_15">
                                            <div class="width_100 text_right margin_right_10">使用方式:</div>
                                            <div class="margin_right_15">
                                                <select class="width_130 radius_3 border_eee_1 height_30 text_indent_3">
                                                    <option>券码核销</option>
                                                    <option>券码核销</option>
                                                    <option>券码核销</option>
                                                </select>
                                            </div>
                                            <div class="btn font_14 bg_b69b69 radius_3 color_fff margin_right_10 secarch_btn">查询</div>
                                            <div class="btn font_14 color_808080 border_bfbfbf_1 radius_3">导出</div>
                                        </div>
                                    </div>-->
                                    <div class="flex_1">&nbsp;</div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="padding_0_15">
                        <div class="j_form-div color_bfbfbf center j_form">
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
                </div>
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
    $sort_index= $module->field_index_in_grid($default_sort['field'],15);
    $sort_direct= $default_sort['sort'];

    $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
    $buttions= '';	//button之间不能有字符空格，用php组装输出
    $get_param = !empty($_GET)?$_GET:array();
    ?>
    var grid_sort = [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];
    var dataSet= [];
    var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
    var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');
    var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
    var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
    var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
    var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/uselist",$get_param); ?>';
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
require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'membercard'. DS. 'uselistjs_ajax.php';
?>
</body>
</html>