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
        <b>标签列表</b>
        <small>Labels List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#">首页</a></li>
        <li><a href="#">标签管理</a></li>
        <li class="active">标签列表</li>
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
                  				echo '?type='.$l['type_id'];
                  		}
                  	}
                  }
                  else echo $l[$k];?></td>
                  <?php }?>
                </tr>
                <?php }}?>
                </tbody>
              </table>
            <section class="box-footer">
                <a href="<?php echo site_url('hotel/label/edit')?>" class="btn btn-primary">新增</a>
            </section>
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
</body>
</html>
