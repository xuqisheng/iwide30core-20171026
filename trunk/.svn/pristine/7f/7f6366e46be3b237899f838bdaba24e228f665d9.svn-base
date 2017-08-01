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
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/highcharts.js"></script>
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
                <div class="row">
                	<div class="col-sm-12">
                	</div>
                </div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    	<tr role="row">
                        <th class="sorting_desc" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-sort="descending" aria-label="时间: activate to sort column ascending">时间</th>
                    		<th class="sorting_desc" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-sort="descending" aria-label="分销间夜数: activate to sort column ascending">分销间夜数</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销商品数: activate to sort column ascending">分销商品数</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="新增分销员: activate to sort column ascending">新增分销员</th>
                    		<!-- <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="新增粉丝数: activate to sort column ascending">新增粉丝数</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="新增粉丝转化率: activate to sort column ascending">新增粉丝转化率</th> -->
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="粉丝月活度: activate to sort column ascending">粉丝月活度</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="粉丝交易总额: activate to sort column ascending">粉丝交易总额</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="员工佣金总额: activate to sort column ascending">员工佣金总额</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放佣金总额: activate to sort column ascending">发放佣金总额</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="参与酒店数: activate to sort column ascending">参与酒店数</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="产生交易酒店数: activate to sort column ascending">产生交易酒店数</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="自动发放酒店数: activate to sort column ascending">自动发放酒店数</th>
                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="操作: activate to sort column ascending">操作</th>
                    	</tr>
                    <tfoot></tfoot>
                    <tbody>
                    	<tr>
                        <td>昨天</td>
                    		<td><?php echo empty($base_summ->room_counts) ? 0 : $base_summ->room_counts?></td><!-- 分销间夜数 -->
                    		<td><?php echo empty($base_summ->product_counts) ? 0 : $base_summ->product_counts?></td><!-- 分销商品数 -->
                    		<td><?php echo empty($base_summ->new_saler_count) ? 0 : $base_summ->new_saler_count?></td><!-- 新增分销员 -->
                    		<td><?php echo empty($base_summ->new_fans_count) ? 0 : $base_summ->new_fans_count?></td><!-- 新增粉丝数 -->
                    		<!-- <td><?php echo empty($base_summ->room_counts) ? 0 : $base_summ->room_counts?></td> --><!-- 新增粉丝转化率 -->
                    		<!-- <td><?php echo empty($base_summ->room_counts) ? 0 : $base_summ->room_counts?></td> --><!-- 粉丝月活度 -->
                    		<td><?php echo round($base_summ->room_trans + $base_summ->mall_trans,2)?></td><!-- 粉丝交易总额 -->
                    		<td><?php echo round(empty($base_grades->grades_total) ? 0 : $base_grades->grades_total,2)?></td><!-- 员工佣金总额 -->
                    		<td><?php echo round(empty($base_grades->send_total) ? 0 : $base_grades->send_total,2)?></td><!-- 发放佣金总额 -->
                    		<td><?php echo empty($base_summ->distri_hotels_count) ? 0 : $base_summ->distri_hotels_count?></td><!-- 参与酒店数 -->
                    		<td><?php echo empty($base_summ->trans_hotel_count) ? 0 : $base_summ->trans_hotel_count?></td><!-- 产生交易酒店数 -->
                    		<td><?php echo empty($base_summ->auto_deliver_counts) ? 0 : $base_summ->auto_deliver_counts?></td><!-- 自动发放酒店数 -->
                    		<td><a href="<?php echo site_url('distribute/distri_report/dist_base')?>">详细</a></td>
                    	</tr>
                    	<tr>
                        	<td>环比</td>
                        	<td><?php echo chain($base_summ->room_counts,$prevent_summ->room_counts)?>%</td><!-- 分销间夜数 -->
                        	<td><?php echo chain($base_summ->product_counts,$prevent_summ->product_counts)?>%</td><!-- 分销商品数 -->
                        	<td><?php echo chain($base_summ->new_saler_count,$prevent_summ->new_saler_count)?>%</td><!-- 新增分销员 -->
                        	<td><?php echo chain($base_summ->new_fans_count,$prevent_summ->new_fans_count)?>%</td><!-- 新增粉丝数 -->
                        	<!-- <td><?php chain($base_summ->room_counts,$prevent_summ->room_counts)?>%</td> --><!-- 新增粉丝转化率 -->
                        	<!-- <td><?php chain($base_summ->room_counts,$prevent_summ->room_counts)?>%</td> --><!-- 粉丝月活度 -->
                        	<td><?php echo chain($base_summ->mall_trans + $base_summ->room_trans,$prevent_summ->mall_trans + $prevent_summ->room_trans)?>%</td><!-- 粉丝交易总额 -->
                        	<td><?php echo chain($base_grades->grade_total,$prevent_grades->grade_total)?>%</td><!-- 员工佣金总额 -->
                        	<td><?php echo chain($base_grades->send_total,$prevent_grades->send_total)?>%</td><!-- 发放佣金总额 -->
                        	<td><?php echo chain($base_summ->distri_hotels_count,$prevent_summ->distri_hotels_count)?>%</td><!-- 参与酒店数 -->
                        	<td><?php echo chain($base_summ->trans_hotel_count,$prevent_summ->trans_hotel_count)?>%</td><!-- 产生交易酒店数 -->
                        	<td><?php echo chain($base_summ->auto_deliver_counts,$prevent_summ->auto_deliver_counts)?>%</td><!-- 自动发放酒店数 -->
                        	<td>-</td>
                    	</tr>
                    </tbody>
                  </table>
					<div class="row">&nbsp;</div>
                  <div class="box box-info">
                  	<div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
			        	<ul class="nav nav-tabs">
				            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 最近7天 </a></li>
				            <li><a href="#tab2" data-toggle="tab"><i class="fa fa-building"></i> 最近30天 </a></li>
			        	</ul>
			        </div>
			        <div class="tab-content">
			        <div class="tab-pane active" id="tab1">
		                <div class="box-body" id="container7">
		                    
		                </div>
		                <!-- /.box-body -->
		                <div class="box-footer ">
		                    <div class="col-sm-4 col-sm-offset-4">
		                    </div>
		                </div>
		            </div><!-- /#tab2-->
			        <div class="tab-pane" id="tab2">
		                <div class="box-body" id="container30">
		                    
		                </div>
		                <!-- /.box-body -->
		                <div class="box-footer ">
		                    <div class="col-sm-4 col-sm-offset-4">
		                    </div>
		                </div>
		            </div><!-- /#tab2-->
		            </div>
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

$(document).ready(function() {

    // new_chart();
	$(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
<?php 
// $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
// if( count($result['data'])<$num) 
// 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs.php';
// else 
// 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_ajax.php';
?>

});
$(function () {
var dataJson = {};
    $.getJSON("<?php echo site_url('distribute/distri_report/get_dis_summ_chart')?>",function(datas){
        
	    $('#container7').highcharts({
	        title: {
	            text: '分销数据概览',
	            x: -20 //center
	        },
	        subtitle: {
	            text: '来源: www.iwide.cn',
	            x: -20
	        },
	        xAxis: {
	            categories: datas.index
	        },
	        yAxis: {
	            title: {
	                text: '数量'
	            },
	            plotLines: [{
	                value: 0,
	                width: 1,
	                color: '#808080'
	            }]
	        },
	        tooltip: {
	            valueSuffix: '个'
	        },
	        legend: {
	            layout: 'vertical',
	            align: 'right',
	            verticalAlign: 'middle',
	            borderWidth: 0
	        },
	        series: [{
	            name: '分销订房数',
	            data: datas.rc
	        }, {
	            name: '新增粉丝数',
	            data: datas.nfc
	        }, {
	            name: '分销商品数',
	            data: datas.pc
	        }]
	    });
    });
	var date = new Date();
	var date_7 = new Array();
	var date_30 = new Array();
	for(var i=0;i<7;i++){
		date.setDate(date.getDate()-1);
		date_7.push(date.getDate());
	}
	date = new Date();
	for(var i=0;i<30;i++){
		date.setDate(date.getDate()-1);
		date_30.push(date.getDate());
	}
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    	$.getJSON("<?php echo site_url('distribute/distri_report/get_dis_summ_chart')?>?ds=30",function(datas){
            
    	    $('#container30').highcharts({
    	        title: {
    	            text: '分销数据概览',
    	            x: -20 //center
    	        },
    	        subtitle: {
    	            text: '来源: www.iwide.cn',
    	            x: -20
    	        },
    	        xAxis: {
    	            categories: datas.index
    	        },
    	        yAxis: {
    	            title: {
    	                text: '数量'
    	            },
    	            plotLines: [{
    	                value: 0,
    	                width: 1,
    	                color: '#808080'
    	            }]
    	        },
    	        tooltip: {
    	            valueSuffix: '个'
    	        },
    	        legend: {
    	            layout: 'vertical',
    	            align: 'right',
    	            verticalAlign: 'middle',
    	            borderWidth: 0
    	        },
    	        series: [{
    	            name: '分销订房数',
    	            data: datas.rc
    	        }, {
    	            name: '新增粉丝数',
    	            data: datas.nfc
    	        }, {
    	            name: '分销商品数',
    	            data: datas.pc
    	        }]
    	    });
        });
    })
});
</script>
</body>
</html>
