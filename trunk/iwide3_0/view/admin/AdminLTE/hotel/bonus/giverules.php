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
        <b>赠送规则列表</b>
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
                <!--<thead>
                <tr>
                   <?php foreach ($fields_config as $k=> $v){?>
             <th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?>>
             <?php echo $v['label'];?></th> 
             <?php }?>
                </tr>
                </thead>-->
                <thead>
                    <tr>
                      <th>规则编号</th> 
                      <th>规则名称</th> 
                      <th>优先级</th> 
                      <th>状态</th> 
                      <th>创建时间</th> 
                      <th>最后更新时间</th> 
                      <th>操作</th> 
                    </tr>
                </thead>
                <tbody>
                <?php if(isset($list)&&!empty($list)){ foreach($list as $arr){ ?>
                    <tr>
                        <td><?php echo $arr['bonus_grules_id'];?></td>
                        <td><?php echo $arr['rule_name'];?></td>
                        <td><?php echo $arr['priority'];?></td>
                        <td><?php echo $status[$arr['status']];?></td>
                        <td><?php echo $arr['create_time'];?></td>
                        <td><?php echo $arr['update_time'];?></td>
                        <td>
                            <a href="./gr_check?rid=<?php echo $arr['bonus_grules_id'];?>" class="btn btn-info btn-xs" title="查看"><i class="fa fa-file-o"></i>查看</a>
                            <a href="./gr_edit?rid=<?php echo $arr['bonus_grules_id'];?>" class="btn btn-success btn-xs" title="编辑"><i class="fa fa-edit"></i> 编辑</a>
                        </td>
                    </tr>
                <?php }}?>
                </tbody>
              </table>      
            <section class="box-footer">
                <a href="./gr_edit" class="btn btn-primary">新增</a>
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

<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
</script>
</body>
</html>
