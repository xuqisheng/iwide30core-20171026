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
            <h1><?php echo isset($breadcrumb_array['action']) ? $breadcrumb_array['action'] : ''; ?>
                <small></small>
            </h1>
            <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">

            <?php echo $this->session->show_put_msg(); ?>
            <?php $pk = $model->table_primary_key(); ?>
            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border" style="text-align: center;">
                    <h3 class="box-title" ><?php echo $model->m_get('title'); ?></h3>
                </div>
                <div class="box-body">
                    <?php
                        echo htmlspecialchars_decode($model->m_get('content'));
                    ?>
                </div>
                <div class="box-footer ">
                    <div class="col-sm-4 col-sm-offset-4">
<!--                        <a download="操作手册.pdf" class="btn btn-info pull-right" href="--><?php //echo $model->m_get('file') ?><!--">下载资料</a>-->
                        <a class="btn btn-info pull-right" href="<?php echo EA_const_url::inst()->get_url("*/*/download") . '?' . http_build_query(['name' => $model->m_get('file_name'), 'url' => $model->m_get('file_url')]) ?>" download="<?php echo $model->m_get('file_name'); ?>" class="btn btn-info pull-right" href="#">下载资料</a>
<!--                        <button type="button" class="btn btn-info pull-right">下载操作手册</button>-->
                    </div>
                </div>
                <!-- /.box-header -->
            </div>
            <!-- /.box -->

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
</body>
</html>