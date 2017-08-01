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

          <section class="content-header">
              <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
                  <small></small>
              </h1>
              <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
          </section>

        <!-- Main content -->
        <section class="content">


            <?php echo $this->session->show_put_msg(); ?>
            <?php $pk= $model->table_primary_key(); ?>

<!-- Horizontal Form -->
<div class="box box-info">

    <div class="box-header with-border">
        <h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
    </div>

    <div class="tabbable "> <!-- Only required for left/right tabs -->

<!-- form start -->

        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
			<?php


                echo form_open(EA_const_url::inst()->get_url("*/*/edit_post"), array('id'=>'code_form',
                                                                                'class'=>'form-horizontal',
                                                                                'enctype'=>'multipart/form-data' ),
                                                                                array($pk=>$model->m_get($pk) )   ); ?>
                <div class="box-body">

                    <?php foreach ($fields_config as $k=>$v): ?>
                        <?php
                        if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model);
                        else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE);
                        ?>
                    <?php endforeach; ?>

					</div>
					</div>
                    <div class="box-footer ">
                        <div class="col-sm-4 col-sm-offset-4">
                            <button type="submit" class="btn btn-info pull-right">提交</button>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
				<?php echo form_close() ?>
                <!-- /.box-body -->

            </div><!-- /#tab1-->

        </div><!-- /.tab-content -->

</div>
<!-- /.box -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

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
<!--
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
$(function () {
	//CKEDITOR.replace('el_gs_detail');
	$(".wysihtml5").wysihtml5();
});
</script>
</body>
</html>
