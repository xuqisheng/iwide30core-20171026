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
          <h1>分销业绩
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
                	<?php echo form_open('distribute/distribute/income_salers')?>
                	<div class="dataTables_length" id="data-grid_length">
                	<label>关键字 <input type="text" name="key" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php if(!empty($key)):echo $key;endif;?>"></label>
                	<label>开始日期 <input type="text" name="begin_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php if(!empty($begin_time)):echo $begin_time;endif;?>"></label>
                	<label>结束日期 <input type="text" name="end_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php if(!empty($end_time)):echo $end_time;endif;?>"></label>
                		<button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                	<label><input type="checkbox" name="dis_cate" value="1" id="valid_only"<?php if($this->session->userdata('income_saler_sort_type') == 'VALID'):echo ' checked';endif;?> />只显示金额大于0的记录</label>
                	</div>
                	<div class="btn-group">
                	</div>
                	</form>
                	<!-- <button type="button" class="btn btn-sm bg-green" id="grid-btn-send-all"><i class="fa fa-plus"></i>&nbsp;批量发放全部绩效</button> -->
                	</div>
                </div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    	<tr role="row">
                    		<!-- <th width="10%" class="sorting_desc" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-sort="descending" aria-label="绩效ID: activate to sort column ascending">绩效ID</th> -->
                    		<th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销号: activate to sort column ascending">分销号</th>
                    		<th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="员工名: activate to sort column ascending">员工名</th>
                    		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="电话: activate to sort column ascending">电话</th>
                    		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="酒店名: activate to sort column ascending">酒店名</th>
                    		<!-- <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="订单金额: activate to sort column ascending">订单金额</th>
                    		<th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="计算金额: activate to sort column ascending">计算金额</th>-->
                    		<th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="绩效金额: activate to sort column ascending">绩效金额</th>
                    		<th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="状态: activate to sort column ascending">操作</th></tr></thead>
                    
                    <tfoot></tfoot>
                    <tbody><?php foreach ($res as $income):?>
                    	<tr>
                    		<!-- <td><?=$income->id?></td> -->
                    		<td><?=$income->saler?></td>
                    		<td><?=$income->staff_name?></td>
                    		<td><?=$income->cellphone?></td>
                    		<td><?=$income->hotel_name?></td>
                    		<td><?=$income->total?></td>
                    		<td><a href="javascript:;" onclick="send(<?=$income->saler?>);">发放</a></td>
                    	</tr><?php endforeach;?>
                    </tbody>
                  </table>
                  <div class="row">
                  <div class="col-sm-5">
                  <!-- <div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">当前显示第1 / 1页，记录从 1 到 4 ，共 4 条</div>
                  </div> --></div>
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
function send(id){
	if(confirm('确定要发放绩效吗？')){
		$.post("<?php echo site_url('distribute/distribute/send_saler')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','ids':id,'bd':'<?php echo empty($begin_time) ? "" : $begin_time?>','ed':'<?php echo empty($end_time) ? "" : $end_time?>'},function(datas){
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
	$('#valid_only').change(function(){
		window.location.href="<?php echo site_url('distribute/distribute/income_salers')?>?sort="+($(this).prop('checked') ? 'VALID' : 'ALL');
	});
	$('#grid-btn-send-all').click(function(){
		if(confirm('确定要发放选中的激励吗？')){
			var ids   = '';
			$.each(items,function(k,v){
				ids += ',' + $(v).val();
			});
			ids.length > 1 && (ids = ids.substring(1));
			$.post("<?php echo site_url('admins/distribute/send_saler_all')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'},function(datas){
				alert('已成功发放' + datas.success + '笔，失败' + datas.error + '.笔');
				window.location.reload();
			},'json');
		}
});
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
