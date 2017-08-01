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
          <h1>消息编辑
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <?php echo $this->session->show_put_msg(); ?>
                <div class="box-body">
                	<table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    	<tr role="row">
                    		<th width="10%" class="sorting_desc" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-sort="descending" aria-label="信息ID: activate to sort column ascending">信息ID</th>
                    		<th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分类: activate to sort column ascending">分类</th>
                    		<th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="员工: activate to sort column ascending">员工</th>
                    		<th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="标题: activate to sort column ascending">标题</th>
                    		<th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="副标题: activate to sort column ascending">副标题</th>
                    		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="内容: activate to sort column ascending">内容</th>
                    		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发送时间: activate to sort column ascending">发送时间</th>
                    		</thead>
                    
                    <tfoot></tfoot>
                    <tbody><?php foreach ($res as $msg):?>
                    	<tr>
                    		<td><?=$msg->nid?></td>
                    		<td><?=$msg_typs[$msg->msg_typ]?></td>
                    		<td><?=$msg->staff_name?></td>
                    		<td><?=$msg->title?></td>
                    		<td><?=$msg->sub_title?></td>
                    		<td><?=$msg->content?></td>
                    		<td><?=$msg->create_time?></td>
                    	</tr><?php endforeach;?>
                    </tbody>
                  </table>
                  <div class="row">
                  <div class="col-sm-5">
                  <!-- <div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">当前显示第1 / 1页，记录从 1 到 4 ，共 4 条</div>-->
                  </div> 
                  <div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate"><ul class="pagination"><?php echo $pagination?></ul></div></div></div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>

$(document).ready(function() {
});
</script>
</body>
</html>
