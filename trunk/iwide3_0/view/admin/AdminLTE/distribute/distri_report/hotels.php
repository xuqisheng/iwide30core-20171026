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
          <h1>酒店返佣明细
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
                	<?php echo form_open('distribute/distri_report/orders/'.$type)?>
                	<div class="dataTables_length" id="data-grid_length">
                	<!--  <label>关键字 <input type="text" name="key" class="form-control input-sm" placeholder="" aria-controls="data-grid"></label>-->
                	<label>开始日期 <input type="text" name="begin_time" class="form-control input-sm form_datetime" placeholder="" aria-controls="data-grid" value="<?php echo empty($btime) ? '' : $btime?>"></label>
                	<label>结束日期 <input type="text" name="end_time" class="form-control input-sm form_datetime" placeholder="" aria-controls="data-grid" value="<?php echo empty($etime) ? '' : $etime?>"></label>
                		<button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                	</div>
                	<div class="btn-group">
                	</div>
                	</form>
                	</div>
                </div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    	<tr role="row">
                        <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="返佣酒店: activate to sort column ascending">返佣酒店</th>
                        <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放时间: activate to sort column ascending">发放时间</th>
                        <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放金额: activate to sort column ascending">发放金额</th>
                        <th class="sorting_desc" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-sort="descending" aria-label="订单号: activate to sort column ascending">系统订单号</th>
                    		<th class="sorting_desc" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-sort="descending" aria-label="订单号: activate to sort column ascending">PMS订单号</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="预定酒店: activate to sort column ascending">预定酒店</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="预定时间: activate to sort column ascending">预定时间</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="核定时间: activate to sort column ascending">核定时间</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="房型: activate to sort column ascending">房型</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="房价: activate to sort column ascending">房价</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="入住日期: activate to sort column ascending">入住日期</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="离店日期: activate to sort column ascending">离店日期</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="间夜: activate to sort column ascending">间夜</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="订单金额: activate to sort column ascending">订单金额</th>
                        <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="粉丝所属酒店: activate to sort column ascending">粉丝所属酒店</th>
                        <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销员: activate to sort column ascending">分销员</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="酒店返佣: activate to sort column ascending">酒店返佣</th></tr></thead>
                    
                    <tfoot></tfoot>
                    <tbody><?php foreach ($res as $item):?>
                    	<tr>
                        <td><?=isset($hotels[$item['hotel_id']]) ? $hotels[$item['hotel_id']] : '-'?></td>
                        <td><?=isset($item['send_time']) ? $item['send_time'] : '-'?></td>
                        <td><?=isset($item['grade_total']) ? $item['grade_total'] : '-'?></td>
                        <td><?=$item['orderid']?></td>
                    		<td><?=$item['web_orderid']?></td>
                    		<td><?=$hotels[$item['ohotel_id']]?></td>
                    		<td><?=$item['order_time']?></td>
                    		<td><?=$item['grade_time']?></td>
                    		<td><?=$item['roomname']?></td>
                    		<td><?=$item['iprice']?></td>
                    		<td><?=$item['startdate']?></td>
                    		<td><?=$item['enddate']?></td>
                    		<td><?=$this->report_model->date_difference($item['enddate'],$item['startdate'])?></td>
                    		<td><?=$item['price']?></td>
                        <td><?=isset($hotels[$item['fans_hotel']]) ? $hotels[$item['fans_hotel']] : isset($hotels[$item['hotel_id']]) ? $hotels[$item['hotel_id']] : '-'?></td>
                        <td><?=isset($item['saler_name']) ? $item['saler_name'] : '-'?></td>
                    		<td><?=isset($item['grade_total']) ? $item['grade_total'] : '-'?></td>
                    	</tr><?php endforeach;?>
                    </tbody>
                  </table>
                  
                  <div class="row">
	                  <div class="col-sm-5">
	                  	<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条<a class="btn btn-sm bg-green" href="<?php echo site_url("distribute/distri_report/exp_orders/".$type.'_'.$btime.'_'.$etime)?>">导出</a></div>
	                  </div>
	                  <div class="col-sm-7">
	                  	<div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
	                  		<ul class="pagination"><?php echo $pagination?></ul>
	                  	</div>
	                  </div>
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
function send(id){
	if(confirm('确定要发放绩效吗？')){
		$.post("<?php echo site_url('distribute/distribute/send_items')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','ids':id},function(datas){
			alert('已成功发放' + datas.success + '笔，失败' + datas.error + '.笔');
			window.location.reload();
		},'json');
	}
}
function send_items(){
	var items = $("input[name='verifys[]']:checked");
	if(items.length > 0){
		if(confirm('确定要发放选中的激励吗？')){
			var ids   = '';
			$.each(items,function(k,v){
				ids += ',' + $(v).val();
			});
			ids.length > 1 && (ids = ids.substring(1));
			$.post("<?php echo site_url('admins/distribute/send_grade_all')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',"ids":ids},function(datas){
				alert('已成功发放' + datas.success + '笔，失败' + datas.error + '.笔');
				window.location.reload();
			},'json');
		}
	}else{
		alert('请最少选择一个条目');
	}
}
<?php 
// $sort_index= $model->field_index_in_grid($default_sort['field']);
// $sort_direct= $default_sort['sort'];

// $buttions= '';	//button之间不能有字符空格，用php组装输出
// $buttions.= '<button type="button" class="btn btn-sm bg-green" id="grid-btn-add"><i class="fa fa-plus"></i>&nbsp;发放绩效</button>';
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
