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
          <h1>粉丝分销记录
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
                	<?php echo form_open('distribute/distri_report/exnew_fas/','class="form-inline"')?>
                	<div class="form-group">
                		<label>所属酒店</label><select name='hotel_id' class="form-control input-sm">
                		<option value=""<?php if(empty($hotel_id)):echo ' selected';endif;?>>-- 全部 --</option>
                		<?php foreach ($hotels as $key=>$val):?><option value="<?php echo $key?>"<?php if(!empty($posts['hotel_id']) && $key == $posts['hotel_id']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?></select>
                	</div>
                	<div class="form-group">
                		<label>取消关注时间 </label>
                		<input class="form_datetime form-control input-sm" data-date-format="yyyymmdd" type="text" name="unfollow_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['unfollow_time']) ? '' : $posts['unfollow_time']?>">
                	</div>
                	<div class="form-group">
                		<label>分销员 </label><input type="text" name="saler_name" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['saler_name']) ? '' : $posts['saler_name']?>">
                	</div>
                	<div class="form-group">
                		<label>分销号</label> <input type="datetime" name="saler_no" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['saler_no']) ? '' : $posts['saler_no']?>">
                	</div>
                	<div class="btn-group">
                		<button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                	</div>
                	<div class="btn-group">
                		<button type="button" class="btn btn-sm bg-green" id="grid-btn-set" data-toggle="modal" data-target="#setModal" ><i class="fa"></i>&nbsp;设置</button>
                	</div>
                	</div>
                	</form>
                	</div>
                </div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    <!-- 订单号	粉丝微信名	微信会员号	关注时间	所属分销员姓名	所属分销员分销号	所属酒店	所属酒店分组	粉丝绩效规则	分销员绩效	绩效发放时间	发放状态
                     -->
                    		<tr role="row">
							<?php $index = 0;
		$fields = array();
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
?>                        	<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="<?php echo $item['name'];?>: activate to sort column ascending"><?php echo $item['name'];?></th>
				<?php $index ++;
				$fields[] = $key;
			}
		}?>
                    </tr>
                    <tfoot></tfoot>
                    <tbody>
                    <?php
                    foreach ( $res as $item ):?>
                    <tr>
                    	 <td><?=$item['hotelname']?></td>
                    	 <td><?=$item['nickname']?></td>
                    	 <td><?=$item['wx_member']?></td>
                    	 <td><?=$item['pms_member']?></td>
                    	 <td><?=$item['unfollow_time']?></td>
                    	 <td><?=$item['saler_name']?></td>
                    	 <td><?=$item['saler']?></td>
                    	 <td><?=$item['fans_gz']?></td>
                    	 <td><?=$item['sale_jx']?></td>
                    	 <td><?=$item['pay_time']?></td>
                    	 <td><?php echo $item['pay_status'] == 2 ? '已发放' : '未发放'?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                  </table>
                  
                  <div class="row">
	                  <div class="col-sm-5">
	                  	<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条<a class="btn btn-sm bg-green" href="<?php echo site_url("distribute/distri_report/ext_hotel_fans_unfollow/".$posts['hotel_id'].'_'.$posts['begin_time'].'_'.$posts['end_time'].'_'.$posts['fans_id'].'_'.$posts['saler_name'].'_'.$posts['saler_no'])?>">导出</a></div>
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
	$.getJSON('<?php echo site_url("distribute/distri_report/get_cofigs?ctyp=dist_fans_sale")?>',function(data){
		if(data != null){
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
		}
		
		
	});

})});
$('#set_btn_save').click(function(){
	$.post('<?php echo site_url("distribute/distri_report/save_cofigs?ctyp=dist_fans_sale")?>',$("#setting_form").serialize(),function(data){
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
