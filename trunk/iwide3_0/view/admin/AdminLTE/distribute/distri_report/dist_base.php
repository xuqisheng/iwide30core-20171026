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
<style type="text/css">
  .list-inline>li{width: 13em;}
</style>
<div class="modal fade" id="hotelsModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">选择酒店</h4>
      </div>
      <div class="modal-body">
        <ul class="list-inline">
          <?php foreach ($paccounts as $k => $v) {
            ?><li><label class="checkbox-inline"><input type="checkbox" value="<?php echo $k?>" name="hotels"><?php echo $v?></label></li><?php
          }?>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="hotels_btn_save">选择</button>
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
          <h1>分销数据概览
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
                <?php echo form_open(site_url('distribute/distri_report/dist_base'),array('class'=>'form-inline'))?>
                <input type="hidden" name="inter_ids" value='<?php echo isset($posts['inter_ids']) ? $posts['inter_ids'] : ''?>'/>
                <div class="form-group">
                	<label class="control-label">统计时间</label>
                		<input type="text" class="input-sm form-control form_datetime" name="date_begin" value="<?php echo isset($posts['date_begin']) ? $posts['date_begin'] : ''?>" /><label>至</label><input type="text" class="input-sm form-control form_datetime" name="date_end" value="<?php echo isset($posts['date_end']) ? $posts['date_end'] : ''?>" />
                </div>
                <div class="form-group">
                	<label class="control-label">发放方式</label>
                		<select name="send_typ" class="form-control"><option value=""<?php echo (isset($posts['send_typ']) && $posts['send_typ'] === '') ? ' selected' : ''?>> -- 全部 -- </option><option value="0"<?php echo (isset($posts['send_typ']) && $posts['send_typ'] === 0) ? ' selected' : ''?>>自动发放</option><option value="1"<?php echo (isset($posts['send_typ']) && $posts['send_typ'] == 1) ? ' selected' : ''?>>线下发放</option></select>
                </div>
                <div class="form-group">
                	<label class="control-label">选择酒店</label>
                	<a href="#" data-toggle="modal" data-target="#hotelsModal">选择</a>
                  <span id="hotel_text"></span>
                </div>
                <input type="submit" value="检索" class="btn btn-default" />
                </form>
                <?php echo form_open(site_url('distribute/distri_report/exp_dist_base'))?>
                		<input type="hidden" name="inter_ids" value='<?php echo isset($posts['inter_ids']) ? $posts['inter_ids'] : ''?>'/>
                		<input type="hidden" name="date_begin" value="<?php echo isset($posts['date_begin']) ? $posts['date_begin'] : ''?>" />
                		<input type="hidden" name="date_end" value="<?php echo isset($posts['date_end']) ? $posts['date_end'] : ''?>" />
                		<input type="hidden" name="send_typ" value="<?php echo isset($posts['send_typ']) ? $posts['send_typ'] : ''?>" />
                <input type="submit" value="导出" class="btn btn-default" />
                </form>
                <div class="row">
                	<div class="col-sm-12">&nbsp;
                	</div>
                </div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    	<tr role="row">
                        <th class="sorting_desc" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-sort="descending" aria-label="interID: activate to sort column ascending">interID</th>
                    		<th class="sorting_desc" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-sort="descending" aria-label="酒店名称: activate to sort column ascending">酒店名称</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销间夜数: activate to sort column ascending">分销间夜数</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销商品数: activate to sort column ascending">分销商品数</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="新增分销员: activate to sort column ascending">新增分销员</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="新增粉丝数: activate to sort column ascending">新增粉丝数</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="门店总数: activate to sort column ascending">门店总数</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="产生交易酒店数: activate to sort column ascending">产生交易酒店数</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="交易总额: activate to sort column ascending">交易总额</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放方式: activate to sort column ascending">发放方式</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="员工佣金总额: activate to sort column ascending">员工佣金总额</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销间夜数排名: activate to sort column ascending">分销间夜数排名</th>
                    	</tr>
                    <tfoot></tfoot>
                    <tbody>
                    	<?php foreach ($base_summ as $bs):?>
                    	<tr>
                        <td><?php echo $bs->inter_id?></td>
                    		<td><?php echo empty($paccounts[$bs->inter_id]) ? '-' : $paccounts[$bs->inter_id]?></td><!-- 酒店名称 -->
                    		<td><?php echo empty($bs->room_counts) ? 0 : $bs->room_counts?></td><!-- 分销间夜数 -->
                    		<td><?php echo empty($bs->product_counts) ? 0 : $bs->product_counts?></td><!-- 分销商品数 -->
                    		<td><?php echo empty($bs->new_saler_counts) ? 0 : $bs->new_saler_counts?></td><!-- 新增分销员 -->
                    		<td><?php echo empty($bs->new_fans_counts) ? 0 : $bs->new_fans_counts?></td><!-- 新增粉丝数 -->
                    		<td><?php echo empty($hcounts[$bs->inter_id]) ? '-' : $hcounts[$bs->inter_id]?></td><!-- 门店总数 -->
                    		<td><?php echo empty($grade_arr[$bs->inter_id]->hotel_count) ? 0 : $grade_arr[$bs->inter_id]->hotel_count;?></td><!-- 产生交易酒店数 -->
                    		<td><?php echo empty($bs->mall_trans + $bs->room_trans) ? 0 : $bs->mall_trans + $bs->room_trans?></td><!-- 交易总额 -->
                    		<td><?php echo $bs->send_typ == 1 ? '线下发放' : '自动发放'?></td><!-- 发放方式 -->
                    		<td><?php echo empty($grade_arr[$bs->inter_id]->grade_total) ? 0 : $grade_arr[$bs->inter_id]->grade_total?></td><!-- 员工佣金总额 -->
                    		<td><?php echo $bs->rank?></td><!-- 分销间夜数排名 -->
                    	</tr>
                    	<?php endforeach;?>
                    </tbody>
                  </table>
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


$(document).ready(function() {
	$(".form_datetime").datepicker({format: 'yyyy-mm-dd',autoclose:true});
<?php 
// $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
// if( count($result['data'])<$num) 
// 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs.php';
// else 
// 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_ajax.php';
?>
  var val = $('input[name=inter_ids]').val();
  if(val != ''){
    var arr = val.split(',');
    $('#hotel_text').html('已选择' + arr.length + '间酒店');
    for (var i = 0; i < arr.length; i++) {
      $('input[name=hotels][value=' + arr[i] + ']').prop("checked", true);
    }
  }
});
$('#hotels_btn_save').on('click',function(){
  var objs = $('input[name=hotels]:checked');
  var valStr = new Array();
  $.each(objs,function(k,v){
    valStr.push($(v).val());
  });
  $('input[name=inter_ids]').val(valStr);
  if(valStr.length > 0)
    $('#hotel_text').html('已选择' + valStr.length + '间酒店');
  else
    $('#hotel_text').html('');
  $('#hotelsModal').modal('toggle');
})
</script>
</body>
</html>
