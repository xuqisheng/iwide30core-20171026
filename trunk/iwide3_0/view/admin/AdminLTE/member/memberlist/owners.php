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
          <h1>员工/业主申请
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <?php echo $this->session->show_put_msg(); ?>
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <!-- 
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
          <div class="row">
            <div class="col-xs-12">
            <?php echo form_open(site_url('member/memberlist/owners'));?>
            <label>关键字<input type="text" name="key" value="" /></label>
            <input type="submit" value="检索" />
            <button type="button" class="btn btn-sm bg-green" id="grid-btn-auth-batch"><i class="fa fa-plus"></i>&nbsp;批量通过</button>
            <!-- <button type="button" class="btn btn-sm bg-green" id="grid-btn-auth-all"><i class="fa fa-plus"></i>&nbsp;全部通过</button> -->
            </form>
            </div></div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed table-hover">
                    <thead><tr>
                    			<th><label><input type="checkbox" name="check-all">全选</label></th>
                    			<th>会员号</th>
                    			<th>姓名</th>
                    			<th>电话</th>
                    			<th>类型</th>
                    			<th>物业中心</th>
                    			<th>证件号</th>
                    			<th>操作</th>
							</tr></thead>
                    <tbody><?php foreach ($res as $item):?>
	                    <tr>
	                    	<td><?php if($item->audit == 2):?>-<?php elseif($item->audit == 1):?><input type="checkbox" name="ids[]" value="<?php echo $item->mem_id?>" /><?php endif;?></td>
	                    	<td><?php echo $item->membership_number?></td>
	                    	<td><?php echo $item->name?></td>
	                    	<td><?php echo $item->telephone?></td>
	                    	<td><?php echo $item->member_type == 97 ? '员工' : '业主'?></td>
	                    	<td><?php echo $item->owner_name?></td>
	                    	<td><?php echo $item->owner_no?></td>
	                    	<td><?php if($item->audit == 2):?>已通过<?php elseif($item->audit == 1):?><a href="javascript:;" onclick="pass(<?php echo $item->mem_id?>,'<?php echo $item->membership_number?>')" class="button">通过</a><?php endif;?></td>
	                    </tr>
                    <?php endforeach;?></tbody>
                    
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
          <div class="row">
          	<div class="col-sm-5">
          		<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite"></div></div>
          	<div class="col-sm-7">
          		<div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
          			<ul class="pagination"><?php echo $pagination?></ul></div></div></div>
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

<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];
function pass(id,mid){
	if(confirm('确定要通过吗？请谨慎操作！')){
		$.post("<?php echo site_url('member/memberlist/auth_member') ?>",{'ids':id,'mid':mid,'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},function(res){
    		alert($.trim(res));
    		location.reload();
    	});
    }
}

$(document).ready(function() {
	$('input:checkbox[name=check-all]').change(function(){
		if($(this).prop('checked')){
			$('input:checkbox[name="ids[]"]').prop('checked',true);
		}else{
			$('input:checkbox[name="ids[]"]').prop('checked',false);
		}
	});
	$('#grid-btn-auth-batch').click(function(){
		if($('input:checkbox[name="ids[]"]:checked').length < 1){
			alert('请至少选择一项');
			return false;
		}
		if(confirm('确定要执行批量通过操作吗？')){
			var vals = $('input:checkbox[name="ids[]"]:checked').serializeArray();
			var o = new Object();
			o.name = '<?php echo $this->security->get_csrf_token_name(); ?>';
			o.value = '<?php echo $this->security->get_csrf_hash(); ?>';
			vals.push(o);
			console.log(vals);
			$.post("<?php echo site_url('member/memberlist/auth_member') ?>",vals,function(res){
				alert($.trim(res));
        		location.reload();
	    	});
		}
	});
});
</script>
</body>
</html>
