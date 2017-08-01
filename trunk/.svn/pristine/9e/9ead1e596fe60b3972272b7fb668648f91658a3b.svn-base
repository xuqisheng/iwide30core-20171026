<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
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
      <h1>
        <b>分销规则列表</b>
        <small></small>
      </h1>
      <ol class="breadcrumb">
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="coupons_table" class="table table-bordered table-striped">
                <thead>
                <tr>
                   <?php foreach ($fields_config as $k=> $v){?>
             <th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?>>
             <?php echo $v['label'];?></th> 
             <?php }?>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($list)){foreach ($list as $l){?>
                <tr>
              	  <?php foreach ($fields_config as $k=>$v){?>
                  <td>
                  <?php if (!empty($v['select'])&&!empty($v['select'][$l[$k]])){
                  	echo $v['select'][$l[$k]];
                  }
                  else if (!empty($v['user_operations'])){
                  	foreach ($v['user_operations'] as $oper){
                  		foreach ($oper as $ok=>$os){
                  			echo $os;
                  			if ($ok==='key')
                  				echo '?rid='.$l['id'];
                  		}
                  	}
                  }else if (!empty($v['format'])){
                    echo date($v['format'],$l[$k]);
                  }
                  else echo $l[$k];?></td>
                  <?php }?>
                </tr>
                <?php }}?>
                </tbody>
              </table>
             <?php if (!empty($fields_config['user_operations']['user_operations']['rule_edit'])){?>       
            <section class="box-footer">
                <a href="<?php echo site_url('hotel/distribute/rule_edit')?>" class="btn btn-primary">新增</a>
            </section>
            <?php }?>
			</div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
  <!-- /.content-wrapper -->

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

<script src="/public/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/public/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
</script>
</body>
</html>
