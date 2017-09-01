<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<style>
<!--

-->
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="modal fade" id="setModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">显示设置</h4>
      </div>
      <div class="modal-body">
        <div id='cfg_items'>
        <?php echo form_open('distribute/distri_report/save_cofigs','id="setting_form"')?>
        	
        </form></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="set_btn_save">保存</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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
          <h1>商城分销绩效报表（按订单）
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
	                	<?php echo form_open('distribute/distri_report/mall_orders','class="form-inline"')?>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>主订单号 </label><input type="text" name="order_id" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['order_id']) ? '' : $posts['order_id']?>">
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>购买人 </label><input type="text" name="customer" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['customer']) ? '' : $posts['customer']?>">
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>购买时间</label>
	                		<input type="text" name="botime" class="form-control input-sm datetime" placeholder="购买时间" aria-controls="data-grid" value="<?php echo empty($posts['botime']) ? '' : $posts['botime']?>">
	                		<label>至 </label>
	                		<input type="text" name="eotime" class="form-control input-sm datetime" placeholder="购买时间" aria-controls="data-grid" value="<?php echo empty($posts['eotime']) ? '' : $posts['eotime']?>">
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>核定时间</label>
	                		<input type="text" name="bgtime" class="form-control input-sm datetime" placeholder="核定时间" aria-controls="data-grid" value="<?php echo empty($posts['bgtime']) ? '' : $posts['bgtime']?>">
	                		<label>至 </label>
	                		<input type="text" name="egtime" class="form-control input-sm datetime" placeholder="核定时间" aria-controls="data-grid" value="<?php echo empty($posts['egtime']) ? '' : $posts['egtime']?>">
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>是否用券</label><select name="ticket" class="form-control input-sm"><option value=""> --全部-- </option><option value="1"<?php if(isset($posts['ticket']) && $posts['ticket'] == 1):echo ' selected';endif;?>>有用券</option><option value="2"<?php if(isset($posts['ticket']) && $posts['ticket'] == 2):echo ' selected';endif;?>>无用券</option></select>
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>支付方式</label><select name="pay_typ" class="form-control input-sm"><option value=""> --全部-- </option><option<?php if(isset($posts['pay_typ']) && $posts['pay_typ'] == '微信支付'):echo ' selected';endif;?>>微信支付</option><option<?php if(isset($posts['pay_typ']) && $posts['pay_typ'] == '储值支付'):echo ' selected';endif;?>>储值支付</option><option<?php if(isset($posts['pay_typ']) && $posts['pay_typ'] == '积分支付'):echo ' selected';endif;?>>积分支付</option><option<?php if(isset($posts['pay_typ']) && $posts['pay_typ'] == '到店支付'):echo ' selected';endif;?>>到店支付</option></select>
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>分销员姓名</label> <input type="text" name="saler" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['saler']) ? '' : $posts['saler']?>">
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>分销号</label> <input type="text" name="saler_no" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['saler_no']) ? '' : $posts['saler_no']?>">
	                	</div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label>所属部门</label>
                            <select class="form-control selectpicker"
                                    data-live-search="true" name="department">
                                <option value="">所有部门</option>
                                <?php foreach ($depts as $k=>$v):if(!empty($v->master_dept)):?>
                                    <option value="<?php echo $v->master_dept;?>"
                                        <?php if($deptment == $v->master_dept):echo ' selected';endif;?>><?php echo $v->master_dept;?></option>
                                <?php endif; endforeach;?>
                            </select>
                        </div>
	                	<div class="btn-group" style="margin-bottom:10px;">
	                		<button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
	                	</div>
	                	<div class="btn-group" style="margin-bottom:10px;">
	                		<button type="button" class="btn btn-sm bg-green" id="grid-btn-set" data-toggle="modal" data-target="#setModal" ><i class="fa"></i>&nbsp;设置</button>
	                	</div>
	                	</form>
                	</div>
                </div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    		<tr role="row">
                  <?php $index = 0;
		$fields = array();
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
?>                        	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="<?php echo $item['name'];?>: activate to sort column ascending"><?php echo $item['name'];?></th>
				<?php $index ++;
				$fields[] = $key;
			}
		}?>
                    </tr>
                    <tfoot></tfoot>
                    <tbody>
                    <?php
                    foreach ( $res as $item ) {
                    	?>
                    <tr>
                    	 <td><?=$item['order_id']?></td>
                    	<?php
                    	if (in_array ( 'sub_order_id', $fields )) {
                    		?>
                    	<td><?=$item['sub_order_id']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'pms_order_id', $fields )) {
                    		?>
                    	<td><?=$item['pms_order_id']?></td>
                    	<?php
                    	}?>
                    	<?php
                    	if (in_array ( 'member_card_no', $fields )) {
                    		?>
                    	<td><?=$item['member_card_no']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'membership_number', $fields )) {
                    		?>
                    	<td><?=$item['membership_number']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'customer', $fields )) {
                    		?>
                    	<td><?=$item['customer']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'cellphone', $fields )) {
                    		?>
                    	<td><?=$item ['cellphone']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'product', $fields )) {
                    		?>
                    	<td><?=$item ['product']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'product_group', $fields )) {
                    		?>
                    	<td><?=$item['product_group']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'order_time', $fields )) {
                    		?>
                    	<td><?=$item['order_time']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'counts', $fields )) {
                    		?>
                    	<td><?=$item['counts']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'order_status', $fields )) {
                    		?>
                    	<td><?php echo isset($ostatus[$item['order_status']]) ? $ostatus[$item['order_status']] : $item['order_status']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'price', $fields )) {
                    		?>
                    	<td><?=$item['price']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'shopping_mode', $fields )) {
                    		?>
                    	<td><?=$item['shopping_mode']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'ticket', $fields )) {
                    		?>
                    	<td><?=$item['ticket']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'point', $fields )) {
                    		?>
                    	<td><?=$item['point']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'balance', $fields )) {
                    		?>
                    	<td><?=$item['balance']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'pay_typ', $fields )) {
                    		?>
                    	<td><?=$item['pay_typ']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'actually_paid', $fields )) {
                    		?>
                    	<td><?=$item['actually_paid']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'grade_typ', $fields )) {
                    		?>
                    	<td><?php echo $item['grade_typ'] == 2 ? '按次' : '粉丝归属'?></td>
                    	<?php
                    	}
                    	if (in_array ( 'grade_time', $fields )) {
                    		?>
                    	<td><?php echo empty($item['grade_time']) ? '--' : $item['grade_time']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'saler', $fields )) {
                    		?>
                    	<td><?=$item['name']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'saler_no', $fields )) {
                    		?>
                    	<td><?=$item['saler']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'saler_hotel', $fields )) {
                    		?>
                    	<td><?php echo isset($hotels[$item['hotel_id']]) ? $hotels[$item['hotel_id']] : '--' ?></td>
                    	<?php
                    	}
                        if (in_array ( 'master_dept', $fields )) {
                    		?>
                    	<td><?php if(isset($item ['master_dept'] )):echo $item ['master_dept'];else:echo '-';endif;?></td>

                    	<?php
                    	}
                    	if (in_array ( 'hotel_group', $fields )) {
                    		?>
                    	<td><?php echo empty($item['hotel_group']) ? '--' : $item['hotel_group'] ;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'grade_rates', $fields )) {
                    		?>
                    	<td><?=$item['grade_amount_rate']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'grade_total', $fields )) {
                    		?>
                    	<td><?=$item['grade_total']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'send_time', $fields )) {
                    		?>
                    	<td><?php echo empty($item['send_time']) ? '--' : $item['send_time']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'send_status', $fields )) {
                    		?>
                    	<td><?php echo $item['status'] == 2 ? '已发放' : '未发放'?></td>
                    	<?php
                    	}
                    	if (in_array ( 'fans_hotel', $fields )) {
                    		?>
                    	<td><?php echo isset($hotels[$item['fans_hotel']]) ? $hotels[$item['fans_hotel']] : '--' ?></td>
                    	<?php
                    	}
                    	if (in_array ( 'hotel_group1', $fields )) {
                    		?>
                    	<td><?php echo empty($item['hotel_group1']) ? '--' : $item['hotel_group1'] ;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'fans_hotel_grades', $fields )) {
                    		?>
                    	<td><?php echo empty($item['fans_hotel_grades']) ? '--' : $item['fans_hotel_grades'] ;?></td>
                    	<?php
                    	}?>
                    </tr>
                    <?php }?>
                    </tbody>
                  </table>
                  
                  <div class="row">
	                  <div class="col-sm-5">
	                  	<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条<a class="btn btn-sm bg-green" href="<?php echo site_url("distribute/distri_report/ext_mall_orders/".$posts['order_id'].'_'.$posts['botime'].'_'.$posts['eotime'].'_'.$posts['bgtime'].'_'.$posts['egtime'].'_'.$posts['customer'].'_'.$posts['ticket'].'_'.$posts['pay_typ'].'_'.$posts['saler'].'_'.$posts['saler_no'].'_'.$posts['department'])?>">导出</a></div>
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

var buttons = $('<div class="btn-group"></div>');

var grid_sort= [[ , "" ]];

<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];
var baseStr = "";
$(".form_datetime").datepicker({format: 'yyyymmdd'});
$('#grid-btn-set').click(function(){
$('#setModal').on('show.bs.modal', function (event) {
// 	  modal.find('.modal-body input').val(recipient)
	var str = $('#setting_form').html();
	if(baseStr != ""){
		str = baseStr;
	}else{
		baseStr = str;
	}
	$.getJSON('<?php echo site_url("distribute/distri_report/get_cofigs?ctyp=dist_mall_orders")?>',function(data){
		$.each(data,function(k,v){
			str += '<div class="checkbox"><label><input type="checkbox" name="' + k + '"';
			if(v.must == 1){
				str += ' disabled checked ';
			}else if(v.choose == 1){
				str += ' checked ';
			}
			str += '>' + v.name + '</label></div>';
		});
		$('#setting_form').html(str);
	});

})});
$('#set_btn_save').click(function(){
	$.post('<?php echo site_url("distribute/distri_report/save_cofigs?ctyp=dist_mall_orders")?>',$("#setting_form").serialize(),function(data){
		if(data == 'success'){
			window.location.reload();
		}else{
			alert('保存失败');
		}
	});
});
$(document).ready(function() {
    $(".datetime").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
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
