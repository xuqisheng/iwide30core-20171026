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
form>div{margin:.5rem auto;}
-->
</style>
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
          <h1>员工分销按时段绩效报表
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
                	<?php echo form_open('distribute/distri_report/saler_grades/','class="form-inline"')?>
                	<div class="form-group">
                		<label>分销员 </label><input type="text" name="saler_name" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo $saler_name?>">
                	</div>
                	<div class="form-group">
                		<label>分销号</label> <input type="text" name="saler_no" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo $saler_no?>">
                	</div>
                	<div class="form-group">
                		<label>分销员所属酒店</label> <input type="text" name="hotel_name" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo $hotel_name?>">
                	</div>
                    <div class="form-group">
                        <label>所属部门</label>
                        <select name="dept" class="form-control input-sm">
                            <option value=""> -- 全部 -- </option>
                            <?php
                            if (!empty($depts))
                            {


                            foreach($depts as $dept){?>
                                <?php
                                if (!empty($dept->master_dept))
                                {
                                    ?>
                                    <option<?php if ($dept_name == $dept->master_dept):echo ' selected';endif; ?>><?php echo $dept->master_dept ?></option>
                                    <?php
                                }
                                    ?>
                            <?php }}?>
                        </select>
                    </div>
                	<div class="form-group">
                		<label>绩效时间</label>
                		<input type="datetime" name="btime" class="form-control input-sm" placeholder="起始时间" aria-controls="data-grid" value="<?php echo $btime?>">
                		<input type="datetime" name="etime" class="form-control input-sm" placeholder="结束时间" aria-controls="data-grid" value="<?php echo $etime?>">
                	</div>
                	<div class="btn-group">
                		<button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                	</div>
                	</form>
                	</div>
                </div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    		<tr role="row">
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销员姓名: activate to sort column ascending">分销员姓名</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销号: activate to sort column ascending">分销号</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销员所属酒店: activate to sort column ascending">分销员所属酒店</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销员所属部门: activate to sort column ascending">分销员所属部门</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="绩效总金额: activate to sort column ascending">绩效总金额</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="粉丝绩效金额: activate to sort column ascending">粉丝绩效金额</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="订房绩效金额: activate to sort column ascending">订房绩效金额</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="商城绩效金额: activate to sort column ascending">商城绩效金额</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="会员卡绩效金额: activate to sort column ascending">会员卡绩效金额</th>
                                <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="额外绩效金额(首单): activate to sort column ascending">额外绩效金额(首单)</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="未发绩效: activate to sort column ascending">未发绩效</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="已发绩效: activate to sort column ascending">已发绩效</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="绩效金额排行榜: activate to sort column ascending">绩效金额排行榜</th>
		                    </tr>
                    <tfoot></tfoot>
                    <tbody>
                    <?php foreach ($res as $item):?>
                    	<tr>
                    		<td><?=$item->name?></td>
                    		<td><?=$item->qrcode_id?></td>
                    		<td><?=$item->hotel_name?></td>
                    		<td><?=$item->master_dept?></td>
                    		<td><?=$item->GRADE_TOTAL?></td>
                    		<td><?=$item->GRADE_FANS?></td>
                    		<td><?=$item->GRADE_ROOMS?></td>
                    		<td><?=$item->GRADE_MALL_ALL?></td>
                    		<td><?=$item->GRADE_MEMBER?></td>
                        <td><?=$item->GRADE_EXTRA?></td>
                    		<td><?=$item->UNDELIVER?></td>
                    		<td><?php echo $item->GRADE_TOTAL-$item->UNDELIVER?></td>
                    		<td><?=$item->rank?></td>
                    	</tr>
                    <?php endforeach;?>
                    </tbody>
                  </table>
                  
                  <div class="row">
	                  <div class="col-sm-5">
	                  	<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条<a class="btn btn-sm bg-green" href="<?php echo site_url("distribute/distri_report/ext_saler_grades/".urlencode($hotel_name).'_'.urlencode($saler_name).'_'.$saler_no.'_'.$btime.'_'.$etime.'_'.$dept_name)?>">导出</a></div>
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

var url_extra= [
//'http://iwide.cn/',
];
var baseStr = "";
$("input[name=btime]").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true
});
$("input[name=etime]").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true
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
