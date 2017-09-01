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
          <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
<?php echo $this->session->show_put_msg(); ?>

<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">积分列表</h3>
	</div>
    <div class="box-body">
        <?php echo form_open(EA_const_url::inst()->get_url('*/*/index'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
            <thead>
                <tr>
                    <td>
                        <strong>快速搜索：</strong>
                        <input style="width:100%" name="search_char" class="form-cotrol" placeholder="搜索会员姓名、会员卡号" />
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search">查找</i></button>
                    </td>
                </tr>
            </thead>
        </table>
        <?php echo form_close() ?>
    </div>
	<div class="box-body">
        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
            <thead>
            	<tr>
            		<th>ID</th>
                    <th>微信昵称</th>
            		<th>会员名称</th>
                    <th>会员卡号</th>
                    <th>使用积分</th>
                    <th>来源</th>
                    <th>创建时间</th>
            	</tr>
            </thead>
            <thead>
                <?php foreach ($deposit as $key => $value) { ?>
                    <tr>
                        <td> <?php echo $value['credit_log_id'] ?> </td>
                        <td> <?php echo $value['nickname'] ?> </td>
                        <td> <?php echo $value['name'] ?> </td>
                        <td> <?php echo $value['membership_number'] ?> </td>
                        <td>
                            <?php if($value['log_type']==1){ echo "+";}else{echo "-";} ?>
                            <?php echo $value['amount'] ?>
                        </td>
                        <td> <?php echo $value['note']?$value['note']:$value['remark'] ?> </td>
                        <td> <?php echo $value['last_update_time'] ?> </td>
                    </tr>
                <?php } ?>
            </thead>

        </table>
        <div class="btn-group">
            <button type="button" class="btn btn-default ">
                <a href="<?php echo EA_const_url::inst()->get_url('*/*/index?last_credit_log_id=').$last_credit_log_id; ?>">
                    <i class="fa fa-edit"></i>&nbsp;
                    <?php if($last_credit_log_id){ ?>
                        下一页
                    <?php }else{ ?>
                        第一页
                    <?php } ?>
                </a>
            </button>
        </div>
	</div>
		<!-- /.box-footer -->
</div>

</div> -->
<!-- Horizontal Form -->

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
</body>
</html>
