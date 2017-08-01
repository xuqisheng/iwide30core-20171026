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
          <h1>订房分销报表
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
	                	<?php
	                	$segment = $this->uri->segment(3);
	                	 echo form_open("distribute/distri_report/$segment",'class="form-inline"')?>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>订单酒店</label><select name='hotel_id' class="form-control input-sm">
	                		<option value=""<?php if(empty($hotel_id)):echo ' selected';endif;?>>-- 全部 --</option>
	                		<?php foreach ($hotels as $key=>$val):?><option value="<?php echo $key?>"<?php if(!empty($posts['hotel_id']) && $key == $posts['hotel_id']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?></select>
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>离店日期 </label>
	                		<input class="form-control input-sm form_datetime_l" data-date-format="yyyymmdd" type="text" name="cout_date_begin" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['cout_date_begin']) ? '' : $posts['cout_date_begin']?>">
	                		<label>至 </label>
	                		<input class="form-control input-sm form_datetime_l" data-date-format="yyyymmdd" type="text" name="cout_date_end" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['cout_date_end']) ? '' : $posts['cout_date_end']?>">
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>订单号 </label><input type="text" name="order_id" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['order_id']) ? '' : $posts['order_id']?>">
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>分销员 </label><input type="text" name="saler_name" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['saler_name']) ? '' : $posts['saler_name']?>">
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>分销号</label> <input type="datetime" name="saler_no" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['saler_no']) ? '' : $posts['saler_no']?>">
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>核定日期 </label>
	                		<input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="grade_date_begin" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['grade_date_begin']) ? '' : $posts['grade_date_begin']?>">
	                		<label>至 </label>
	                		<input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="grade_date_end" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['grade_date_end']) ? '' : $posts['grade_date_end']?>">
	                	</div>
	                	<div class="form-group" style="margin-bottom:10px;">
	                		<label>发放日期 </label>
	                		<input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="send_date_begin" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['send_date_begin']) ? '' : $posts['send_date_begin']?>">
	                		<label>至 </label>
	                		<input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="send_date_end" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['send_date_end']) ? '' : $posts['send_date_end']?>">
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
                        <?php
                        if (in_array ( 'csname', $fields )) {
                        ?>
                        <td><?=$item['csname']?></td>
                        <?php
                        }
                        if (in_array ( 'club_name', $fields )) {
                            ?>
                            <td><?=$item['club_name']?></td>
                        <?php
                        }
                        if (in_array ( 'coupon_give', $fields )) {
                            ?>
                            <td><?=$item['coupon_give']?></td>
                        <?php
                        }
                        if (in_array ( 'point_give', $fields )) {
                            ?>
                            <td><?=$item['point_give']?></td>
                        <?php
                        }
                        if (in_array ( 'dis_status', $fields )) {
                            ?>
                            <td><?=$item['dis_status']?></td>
                        <?php
                        }
                        ?>
                    	 <td><?=$item['orderid']?></td>
                    	<?php
                    	if (in_array ( 'webs_orderid', $fields )) {
                    		?>
                    	<td><?=$item['webs_orderid']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'oiid', $fields )) {
                    		?>
                    	<td><?=$item['oiid']?></td>
                    	<?php
                    	}?>
                    	<?php
                    	if (in_array ( 'web_orderid', $fields )) {
                    		?>
                    	<td><?=$item['web_orderid']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'mem_card_no', $fields )) {
                    		?>
                    	<td><?=$item['mem_card_no']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'membership_number', $fields )) {
                    		?>
                    	<td><?=$item['membership_number']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'name', $fields )) {
                    		?>
                    	<td><?=$item ['name']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'in_hotel_id', $fields )) {
                    		?>
                    	<td><?php if(isset($hotels [$item ['order_hotel']])):echo $hotels [$item ['order_hotel']];else:echo '--';endif; ?></td>
                    	<?php
                    	}
                    	if (in_array ( 'roomname', $fields )) {
                    		?>
                    	<td><?=$item['roomname']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'startdate', $fields )) {
                    		?>
                    	<td><?=$item['startdate']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'enddate', $fields )) {
                    		?>
                    	<td><?=$item['enddate']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'grade_time', $fields )) {
                    		?>
                    	<td><?=$item['grade_time']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'price', $fields )) {
                    		?>
                    	<td><?=$item['price']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'coupon_favour', $fields )) {
                    		?>
                    	<td><?php if(isset( $item ['coupon_favour'] )):echo $item ['coupon_favour'];else:echo '0';endif;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'point_used', $fields )) {
                    		?>
                    	<td><?php if(isset( $item ['point_used'] )):echo $item ['point_used'];else:echo '0';endif;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'paytype', $fields )) {
                    		?>
                    	<td><?php if(isset( $paytype [$item ['paytype']] )):echo $paytype [$item ['paytype']];else:echo '到付';endif;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'iprice', $fields )) {
                    		?>
                    	<td><?=$item['iprice']?></td>
                    	<?php
                    	}
                    	if (in_array ( 'staff_name', $fields )) {
                    		?>
                    	<td><?php if(isset( $item ['staff_name'] )):echo $item ['staff_name'];else:echo '-';endif;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'saler', $fields )) {
                    		?>
                    	<td><?php if(isset( $item ['saler'] )):echo $item ['saler'];else:echo '-';endif;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'saler_hotel_name', $fields )) {
                    		?>
                    	<td><?php if(isset( $hotels[$item ['hotel_id']] )):echo $hotels[$item ['hotel_id']];else:echo '-';endif;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'grade_total', $fields )) {
                    		?>
                    	<td><?php if(isset( $item ['grade_total'] )):echo $item ['grade_total'];else:echo '-';endif;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'send_time', $fields )) {
                    		?>
                    	<td><?php if(isset( $item  ['send_time'] )):echo $item ['send_time'];else:echo '-';endif;?></td>
                    	<?php
                    	}
                    	if (in_array ( 'fans_hotel_name', $fields )) {
                    		?>
                    	<td><?php if(isset( $hotels[$item ['fans_hotel']] )):echo $hotels[$item ['fans_hotel']];else:echo '-';endif;?></td>
                    </tr>
                    	<?php
                    	}
                    	if (in_array ( 'partner_trade_no', $fields )) {
                    		?>
                    	<td><?php if(isset( $item  ['partner_trade_no'] )):echo $item ['partner_trade_no'];else:echo '-';endif;?></td>
                    </tr>
                    	<?php
                    	}
                    }?>
                    </tbody>
                  </table>
                  
                  <div class="row">
	                  <div class="col-sm-5">
	                  	<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条<a class="btn btn-sm bg-green" href="<?php echo site_url("distribute/distri_report/ext_club_orders_all/".$posts['hotel_id'].'_'.$posts['cout_date_begin'].'_'.$posts['cout_date_end'].'_'.$posts['order_id'].'_'.$posts['saler_name'].'_'.$posts['saler_no'].'_'.$posts['grade_date_begin'].'_'.$posts['grade_date_end'].'_'.$posts['send_date_begin'].'_'.$posts['send_date_end'])?>">导出</a></div>
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
var baseStr = "";
$(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
$(".form_datetime_l").datepicker({format: 'yyyymmdd'});
$('#grid-btn-set').click(function(){
$('#setModal').on('show.bs.modal', function (event) {
// 	  modal.find('.modal-body input').val(recipient)
	var str = $('#setting_form').html();
	if(baseStr != ""){
		str = baseStr;
	}else{
		baseStr = str;
	}
	$.getJSON('<?php echo site_url("distribute/distri_report/get_cofigs?ctyp=dist_club_order")?>',function(data){
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
	$.post('<?php echo site_url("distribute/distri_report/save_cofigs?ctyp=dist_club_order")?>',$("#setting_form").serialize(),function(data){
		if(data == 'success'){
			window.location.reload();
		}else{
			alert('保存失败');
		}
	});
});
$(document).ready(function() {
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
