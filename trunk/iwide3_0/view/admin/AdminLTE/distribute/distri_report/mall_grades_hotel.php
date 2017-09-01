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
          <h1>商城分销绩效报表（按酒店）
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
	                	<?php echo form_open('distribute/distri_report/mall_hotels/','class="form-inline"')?>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>订单酒店</label>
	                		<select name="hotel_id" class="form-control input-sm"><option value="">--全部--</option>
	                		<?php foreach ($hotels as $key=>$val):?><option value="<?php echo $key?>"><?php echo $val?></option><?php endforeach;?>
	                		</select>
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>核定时间 </label>
	                		<input type="text" name="btime" class="form-control input-sm datetime" placeholder="" aria-controls="data-grid" value="<?php echo empty($btime) ? '' : $btime?>">
	                		<label>至 </label>
	                		<input type="text" name="etime" class="form-control input-sm datetime" placeholder="" aria-controls="data-grid" value="<?php echo empty($etime) ? '' : $etime?>">
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
                    	 <td><?php if(isset($hotels [$item ['hotel_id']])):echo $hotels [$item ['hotel_id']];else:echo '--';endif; ?></td>
                    	<?php
                    	if (in_array ( 'hotel_group', $fields )) {
                    		?>
                    	<td><?php echo empty($item['hotel_group']) ? '--' : $item['hotel_group'] ;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'ORDER_COUNTS', $fields )) {
                    		?>
                    	<td><?=$item['ORDER_COUNTS']?></td>
                    	<?php
                    	}?>
                    	<?php
                    	if (in_array ( 'BALANCE_PAY_COUNTS', $fields )) {
                    		?>
                    	<td><?=$item['BALANCE_PAY_COUNTS']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'WEIXIN_PAY_COUNTS', $fields )) {
                    		?>
                    	<td><?=$item['WEIXIN_PAY_COUNTS']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'POINT_PAY_COUNTS', $fields )) {
                    		?>
                    	<td><?=$item['POINT_PAY_COUNTS']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'TICKET_PAY_COUNTS', $fields )) {
                    		?>
                    	<td><?=$item ['TICKET_PAY_COUNTS']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'GRADES_PRODUCTS_COUNTS', $fields )) {
                    		?>
                    	<td><?=$item['GRADES_PRODUCTS_COUNTS']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'TOTAL_AMOUNT', $fields )) {
                    		?>
                    	<td><?=$item['TOTAL_AMOUNT']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'BALANCE_PAY_AMOUNT', $fields )) {
                    		?>
                    	<td><?=$item['BALANCE_PAY_AMOUNT']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'WEIXIN_PAY_AMOUNT', $fields )) {
                    		?>
                    	<td><?=$item['WEIXIN_PAY_AMOUNT']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'SHOP_PAY_AMOUNT', $fields )) {
                    		?>
                    	<td><?=$item['SHOP_PAY_AMOUNT']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'TICKET_PAY_AMOUNT', $fields )) {
                    		?>
                    	<td><?php if(isset( $item ['TICKET_PAY_AMOUNT'] )):echo $item ['TICKET_PAY_AMOUNT'];else:echo '0';endif;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'POINT_PAY_AMOUNT', $fields )) {
                    		?>
                    	<td><?php if(isset( $item ['POINT_PAY_AMOUNT'] )):echo $item ['POINT_PAY_AMOUNT'];else:echo '0';endif;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'GRADES_AMOUNT', $fields )) {
                    		?>
                    	<td><?=$item['GRADES_AMOUNT']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'GRADES_COUNT', $fields )) {
                    		?>
                    	<td><?php if(isset( $item ['GRADES_COUNT'] )):echo $item ['GRADES_COUNT'];else:echo '-';endif;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'SALER_COUNT', $fields )) {
	                    	$saler_count = '--';
							if(is_null($item['hotel_id'])){
								$saler_count = $h_salers['NULL'];
							}else{
								$saler_count = $h_salers[$item['hotel_id']];
							}
                    		?>
                    	<td><?php echo $saler_count?></td>
                    	<?php
                    	}
                    	if (in_array ( 'RANKING', $fields )) {
                    		?>
                    	<td><?php if(isset( $item  ['RANKING'] )):echo $item ['RANKING'];else:echo '-';endif;?></td>
                    	<?php
                    	}
                    }?>
                    </tbody>
                  </table>
                  
                  <div class="row">
	                  <div class="col-sm-5">
	                  	<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条<a class="btn btn-sm bg-green" href="<?php echo site_url("distribute/distri_report/ext_mall_hotels/".$hotel_id.'_'.$btime.'_'.$etime)?>">导出</a></div>
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
	var str = $('#setting_form').html();
	if(baseStr != ""){
		str = baseStr;
	}else{
		baseStr = str;
	}
	$.getJSON('<?php echo site_url("distribute/distri_report/get_cofigs?ctyp=dist_mall_hotels")?>',function(data){
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
	$.post('<?php echo site_url("distribute/distri_report/save_cofigs?ctyp=dist_mall_hotels")?>',$("#setting_form").serialize(),function(data){
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
});
</script>
</body>
</html>
