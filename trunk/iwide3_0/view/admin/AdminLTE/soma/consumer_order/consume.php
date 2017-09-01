<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css'>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js'></script>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/i18n/defaults-zh_CN.min.js'></script>

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
          <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">

<?php echo $this->session->show_put_msg(); ?>
<?php $pk= $model->table_primary_key(); ?>

<div class="box box-info">
        
    <div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-edit"></i> 输入核销码 </a></li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
<?php 
echo form_open( Soma_const_url::inst()->get_url('*/*/consume'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
        		<div class="box-body "><br/>
                    <?php echo $btn; ?>
        		</div>
        		<!-- /.box-body -->
        		<div class="box-footer ">
                    <div class="col-sm-4 ">
                        <button type="submit" id="consumer" class="btn btn-info pull-right">核销</button>
                    </div>
        		</div>
            		<!-- /.box-footer -->
<?php echo form_close() ?>
            </div><!-- /#tab1-->
        </div>
    </div>
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
<script>
    $("#consumer").click(function(){
        var inter_id = $("#interId").val();
        var code = $("#el_consumer_code").val();
        if( !code || !inter_id ){
            alert( '请输入消费码或者选择公众号' );
            return false;
        }

        var is_true = confirm( '你确定要进行核销' );
        if( !is_true ){
            return false;
        }
    });
</script>
</body>
</html>
