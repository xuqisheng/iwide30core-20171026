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
        <b>发放规则列表</b>
        <small>Coupons Using Rules</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#">首页</a></li>
        <li><a href="#">酒店订房</a></li>
        <li><a href="#">优惠券配置</a></li>
        <li class="active">用券规则列表</li>
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
                    <?php foreach($label as $arr){  ?>

                        <th><?php echo $arr;?></th>

                    <?php  }   ?>

                  <th>操作</th>
                </tr>
                </thead>
                <tbody>

                <?php
                if(isset($rules) && !empty($rules) && is_array($rules)){
                    foreach($rules as $res){
                ?>
                <tr>
                        <td><?php echo $res->rule_id; ?></td>
                        <td><?php echo $res->rule_name; ?></td>
                        <td>
                            <?php
                                foreach($res->coupon_ids as $coupon_id){
                                    echo $title[$coupon_id]."&nbsp;&nbsp;";
                                }
                            ?>
                        </td>
                        <td><?php echo $status[$res->status]; ?></td>
                        <td><?php echo $res->create_time; ?></td>
                        <td><?php echo $res->update_time; ?></td>
                        <td>
                          <a href="gr_check?rid=<?php echo $res->rule_id;?>" class="btn btn-info btn-xs" title="查看"><i class="fa fa-file-o"></i> 查看</a>
                          <a href="gr_edit?rid=<?php echo $res->rule_id;?>" class="btn btn-success btn-xs" title="编辑"><i class="fa fa-edit"></i> 编辑</a>
                        </td>
                </tr>
                <?php }} ?>
                </tbody>
              </table>
                    
            <section class="box-footer">
                <a href="gr_edit" class="btn btn-primary">新增</a>
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
//  $(function () {
//    $('#coupons_table').DataTable({
//      "paging": true,
//      "lengthChange": true,
//      "searching": false,
//      "ordering": true,
//      "info": false,
//      "autoWidth": false,
//	  "oLanguage":{
//		  "oPaginate":{ "sFirst": "页首","sPrevious": "上一页","sNext": "下一页","sLast": "页尾"},
//		  "sLengthMenu": "每页显示 _MENU_ 条数据",
//	  	  "sEmptyTable": "暂无相关数据"
//	  }
//    });
//  });
</script>
</body>
</html>
