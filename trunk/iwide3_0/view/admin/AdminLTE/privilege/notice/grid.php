<!-- DataTables -->
<link rel="stylesheet"
      href="<?php echo base_url(FD_PUBLIC) . '/' . $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php
    /* 顶部导航 */
    echo $block_top;
    ?>

    <?php
    /* 左栏菜单 */
    echo $block_left;
    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $breadcrumb_array['action']; ?>
                <small></small>
            </h1>
            <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <?php echo $this->session->show_put_msg(); ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <!--
                        <div class="box-header">
                          <h3 class="box-title">Data Table With Full Features</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="data-grid"
                                   class="table table-bordered table-striped table-condensed table-hover">
                                <thead>
                                <tr>
                                    <?php
                                    foreach ($fields_config as $k => $v) {
                                        echo sprintf('<th %s>%s</th>', isset($v['grid_width']) ? 'width="' . $v['grid_width'] . '"' : '', $v['label']);
                                    }
                                    ?>
                                </tr>
                                </thead>

                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php
    /* Footer Block @see footer.php */
    require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
    ?>

    <?php
    /* Right Block @see right.php */
    require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
    ?>

</div><!-- ./wrapper -->

<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>

<script src="<?php echo base_url(FD_PUBLIC) . '/' . $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) . '/' . $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC) . '/' . $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>
    <?php
    /** @var Priv_notice $sort_index */
    $sort_index = $model->field_index_in_grid($default_sort['field']);
    $sort_direct = $default_sort['sort'];

    $buttions = '';    //button之间不能有字符空格，用php组装输出
    if ($is_edit) { // 只有超管才能编辑
        $buttions .= '<button type="button" class="btn btn-sm bg-green" id="grid-btn-add"><i class="fa fa-plus"></i>&nbsp;新增</button>';
        $buttions .= '<button type="button" class="btn btn-sm disabled" id="grid-btn-edit"><i class="fa fa-edit"></i>&nbsp;编辑</button>';
    }
    $buttions .= '<button type="button" class="btn btn-sm disabled" id="grid-btn-detail"><i class="fa fa-detail"></i>&nbsp;详情</button>';
    if (isset($js_filter_btn)) $buttions .= $js_filter_btn;
    ?>
    var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');

    var grid_sort = [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>"]];

    var dataSet = <?php echo json_encode($result['data']); ?>;
    var columnSet = <?php echo json_encode($model->get_column_config($fields_config)); ?>;
    var url_add = '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
    var url_edit = '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
    var url_detail = '<?php echo EA_const_url::inst()->get_url("*/*/detail"); ?>';		//跟button对应
    <?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
    var url_extra = [];


    $(document).ready(function () {
        <?php
        $num = (config_item('grid_static_num')) ? config_item('grid_static_num') : 500;
        if (count($result['data']) < $num)
            require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'gridjs.php';
        else
            require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'gridjs_ajax.php';
        ?>

        <?php if (isset($js_filter)) echo $js_filter; ?>

        // 绑定其他按钮
        $('#data-grid tbody').on('click', 'tr', function () {
            if (selected.length == 1) {
                $('#grid-btn-detail').removeClass('disabled').bind('click', selected, function (ev) {
                    window.location = url_detail + '?ids=' + ev.data;
                });
                $('#grid-btn-detail').addClass('bg-green');
            } else if (selected.length > 0) {
                $('#grid-btn-detail').addClass('disabled').removeClass('bg-green').unbind();
            } else {
                $('#grid-btn-detail').addClass('disabled').removeClass('bg-green').unbind();
            }
        });

    });
</script>
</body>
</html>
