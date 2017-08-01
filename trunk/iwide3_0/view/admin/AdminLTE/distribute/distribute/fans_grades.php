<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
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
          <h1>泛分销分销绩效
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
              <!-- 
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                <div class="row">
                	<div class="col-sm-12">
                	<?php echo form_open('distribute/dis_ext/grades',array('class'=>'form-inline'))?>
                	<div class="dataTables_length" id="data-grid_length">
                	<label>购买时间</label><input type="text" name="obtime" class="form-control input-sm form_datetime" placeholder="" aria-controls="data-grid" value="<?php if(!empty($posts['obtime'])):echo $posts['obtime'];endif;?>">
                	<label>至</label><input type="text" name="oetime" class="form-control input-sm form_datetime" placeholder="" aria-controls="data-grid" value="<?php if(!empty($posts['oetime'])):echo $posts['oetime'];endif;?>">
                	<label>粉丝编号 <input type="text" name="key" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php if(!empty($posts['key'])):echo $posts['key'];endif;?>"></label>
                	<label>发放时间</label><input type="text" name="sbtime" class="form-control input-sm form_datetime" placeholder="" aria-controls="data-grid" value="<?php if(!empty($posts['sbtime'])):echo $posts['sbtime'];endif;?>">
                	<label>至</label><input type="text" name="setime" class="form-control input-sm form_datetime" placeholder="" aria-controls="data-grid" value="<?php if(!empty($posts['setime'])):echo $posts['setime'];endif;?>">
                	<label>发放状态<select name="sstatus" class="form-control input-sm"><option value=""<?php echo (!isset($posts['sstatus']) || $posts['sstatus'] == '') ? ' selected' : ''?>> -- 全部 -- </option>
                	<?php foreach ($gstatus as $k=>$v):?><option value="<?php echo $k?>"<?php echo (isset($posts['sstatus']) && $posts['sstatus'] == $k) ? ' selected' : ''?>> <?php echo $v?> </option><?php endforeach;?></select></label>
                		<button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                		<button type="submit" name="ext_grades" value="1" class="btn btn-sm bg-green" id="grid-btn-ext"><i class="fa"></i>&nbsp;导出</button>
                	</div>
                	<div class="btn-group">
                	</div>
                	</form>
                	</div>
                </div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    	<tr role="row">
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="订单号: activate to sort column ascending">订单号</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="购买时间: activate to sort column ascending">购买时间</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="商品名称: activate to sort column ascending">商品名称</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="商品数量: activate to sort column ascending">商品数量</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="实付金额: activate to sort column ascending">实付金额</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="粉丝编号: activate to sort column ascending">粉丝编号</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="绩效金额: activate to sort column ascending">绩效金额</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放时间: activate to sort column ascending">发放时间</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="绩效状态: activate to sort column ascending">绩效状态</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放状态: activate to sort column ascending">发放状态</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="备注: activate to sort column ascending">备注</th>
                    <tfoot></tfoot>
                    <tbody><?php foreach ($res as $grade):?>
                    	<tr>
                    		<td><?=$grade['order_id']?></td>
                    		<td><?=$grade['order_time']?></td>
                    		<td><?=$grade['product']?></td>
                    		<td><?=$grade['counts']?></td>
                    		<td><?=$grade['actually_paid']?></td>
                    		<td><?=$grade['saler']?></td>
                    		<td><?=$grade['grade_total']?></td>
                    		<td><?php echo empty($grade['send_time']) ? '-' : $grade['send_time']?></td>
                    		<td><?php echo empty($gstatus[$grade['status']]) ? '-' : $gstatus[$grade['status']]?></td>
                    		<td><?php echo empty($sstatus[$grade['status']]) ? '-' : $sstatus[$grade['status']]?></td>
                    		<td><?php echo empty($grade['remark']) ? '-' : $grade['remark']?></td>
                    	</tr><?php endforeach;?>
                    </tbody>
                  </table>
                  <div class="row">
                  <div class="col-sm-5">
                  	<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?php echo $total?>条</div>
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>
<?php 
// $sort_index= $model->field_index_in_grid($default_sort['field']);
// $sort_direct= $default_sort['sort'];

$buttions= '';	//button之间不能有字符空格，用php组装输出
//$buttions.= '<button type="button" class="btn btn-sm bg-green" id="grid-btn-add"><i class="fa fa-plus"></i>&nbsp;批量发放绩效</button>';
// /*$buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-edit"><i class="fa fa-edit"></i>&nbsp;编辑</button>';
// $buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-del"><i class="fa fa-trash"></i>&nbsp;删除</button>';*/
// /*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
// $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=1">员工</a>';
// $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=2">酒店</a>';
// $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=3">金房卡</a>';
// $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=4">集团</a>';
?>
var buttons = $('<div class="btn-group"></div>');

var grid_sort= [[ , "" ]];

<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];


$(document).ready(function() {
	$(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
<?php 
// $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
// if( count($result['data'])<$num) 
// 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs.php';
// else 
// 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_ajax.php';
?>
});
</script>
</body>
</html>
