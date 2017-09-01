<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<!-- Morris chart -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/morris/morris.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/raphael-min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/morris/morris.min.js"></script>

<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css' >
<script src='<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js'></script>
<script src='<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js'></script>
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
          <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">

<?php echo $this->session->show_put_msg(); ?>
<!-- Horizontal Form -->
<div class="box box-info">

    <div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 当日数据分析 </a></li>
            <li class="" id="chart_tab_header"><a href="#tab3" data-toggle="tab"><i class="fa fa-line-chart"></i> 统计图表 </a></li>
            <li class=""><a href="#tab2" data-toggle="tab"><i class="fa fa fa-list"></i> 每日数据明细 </a></li>
            <li class=""><a href="#tab4" data-toggle="tab"><i class="fa fa-pie-chart"></i> 构成饼状图 </a></li>
            <li class=""><a href="#tab5" data-toggle="tab"><i class="fa fa-list-ol"></i> 销售额排行榜 </a></li>
            <li class=""><a href="#tab6" data-toggle="tab"><i class="fa fa-list-ol"></i> 客单价排行榜 </a></li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="box-body">
                    <div class="box-header">统计口径 : [ <?php echo $data_title; ?> ]</div>
                    <table class="table table-bordered table-striped table-condensed">
                        <thead><tr><th>分析项目</th><th>订单金额</th><th>订单数</th><th>总份数</th><th>核销数量</th></tr></thead>
                        <tbody>
                            <tr><td><?php echo $end; ?></td><?php foreach($compare_data['line1'] as $k=>$v ){ echo ($k==0)? "<td>￥{$v}</td>": "<td>{$v}</td>"; } ?></tr>
                            <tr><td>前一日数额</td><?php foreach($compare_data['line2'] as $k=>$v ){ echo ($k==0)? "<td>￥{$v}</td>": "<td>{$v}</td>"; } ?></tr>
                            <tr><td>上周平均值 </td><?php foreach($compare_data['line3'] as $k=>$v ){ echo ($k==0)? "<td>￥{$v}</td>": "<td>{$v}</td>"; } ?></tr>
                            <tr><td>较前一日(%)</td><?php foreach($compare_data['line4'] as $k=>$v ){ echo '<td>'. render_percent_number($v). '</td>'; } ?></tr>
                            <tr><td>较上一周(%)</td><?php foreach($compare_data['line5'] as $k=>$v ){ echo '<td>'. render_percent_number($v). '</td>'; } ?></tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer ">
					<div class="col-sm-3 ">
						<button type="submit" class="btn btn-info pull-right" onclick="window.location.reload();"><i class="fa fa-refresh"></i> 刷新今天数据</button>
					</div>
					<div class="col-sm-9">
<?php echo form_open( Soma_const_url::inst()->get_url('*/*/*' ), array('class'=>'form-inline') ); ?>
    <div class="form-group">
        <label class="sr-only" for="select_date"></label>
        <div class="input-group">
            <div class="input-group-addon">截至日期</div>
            <input type="text" class="form-control" name="date" value="<?php echo $end; ?>" id="select_date" placeholder="请选择">
            <div class="input-group-addon">统计图表天数</div>
            <input name="days" value="<?php echo $days; ?>" placeholder="多少天前" type="number" step="1" min="<?php echo $min_days; ?>" max="<?php echo $max_days; ?>" class="form-control">
        </div>
    </div>
    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查看</button>
<?php echo form_close() ?>
					</div>
                </div>
            </div><!-- /#tab1-->
            
            <div class="tab-pane" id="tab2">
                <div class="box-body">
                  <table id="data-grid-2" class="table table-bordered table-striped table-condensed">
                    <thead><tr><?php foreach ($table_head as $k=> $v): 
		     ?><th><?php echo $v;?></th><?php 
	    endforeach; ?></tr></thead>
                  </table>
                </div><!-- /.box-body -->
            </div><!-- /#tab2-->
            
            <div class="tab-pane" id="tab3">
                <div class="box-body">
            
<!-- Custom tabs (Charts with tabs) 订单总额-->
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
        <li class="pull-left header"><i class="fa fa-inbox"></i><?php echo $chart_head[1]; ?></li>
    </ul>
    <div class="tab-content no-padding">
        <!-- Morris chart - Sales -->
        <div class="chart tab-pane active" id="sales-total" style="position: relative; height: 300px;"></div>
    </div>
</div><!-- /.nav-tabs-custom -->


<!-- Custom tabs (Charts with tabs)-->
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
        <li class="pull-left header"><i class="fa fa-inbox"></i><?php echo $chart_head[2]; ?></li>
    </ul>
    <div class="tab-content no-padding">
        <!-- Morris chart - Sales -->
        <div class="chart tab-pane active" id="sales-order" style="position: relative; height: 300px;"></div>
    </div>
</div><!-- /.nav-tabs-custom -->


<!-- Custom tabs (Charts with tabs) 份数分析-->
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
        <li class="pull-left header"><i class="fa fa-inbox"></i><?php echo $chart_head[3]; ?></li>
    </ul>
    <div class="tab-content no-padding">
        <!-- Morris chart - Sales -->
        <div class="chart tab-pane active" id="sales-count" style="position: relative; height: 300px;"></div>
    </div>
</div><!-- /.nav-tabs-custom -->


<!-- Custom tabs (Charts with tabs) 核销量分析-->
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
        <li class="pull-left header"><i class="fa fa-inbox"></i><?php echo $chart_head[4]; ?></li>
    </ul>
    <div class="tab-content no-padding">
        <!-- Morris chart - Sales -->
        <div class="chart tab-pane active" id="sales-consumer" style="position: relative; height: 300px;"></div>
    </div>
</div><!-- /.nav-tabs-custom -->
    
                </div>
    			<!-- /.box-body -->
            </div><!-- /#tab3-->

            <div class="tab-pane" id="tab4">
                <div class="box-body">
                    <div class="col-sm-6 ">
                
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right" id="l-chart-nav">
        <li class="pull-left header"><i class="fa fa-credit-card"></i> 购买方式</li>
        <li class="active"><a href="#l-chart-1" data-toggle="tab">订单金额</a></li>
        <li><a href="#l-chart-2" data-toggle="tab">订单数</a></li>
        <li><a href="#l-chart-3" data-toggle="tab">购买件数</a></li>
    </ul>
    <div class="tab-content no-padding">
        <div class="chart tab-pane active" id="l-chart-1" style="position: relative; height: 300px;"></div>
        <div class="chart tab-pane" id="l-chart-2" style="position: relative; height: 300px;"></div>
        <div class="chart tab-pane" id="l-chart-3" style="position: relative; height: 300px;"></div>
    </div>
</div><!-- /.nav-tabs-custom -->
                
                    </div><!-- /.Left col -->
                    <div class="col-sm-6 hide">
                
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right">
        <li class="pull-left header"><i class="fa fa-puzzle-piece"></i> 业务类型</li>
        <!-- <li class="active"><a href="#r-chart-1" data-toggle="tab">1</a></li>
        <li><a href="#r-chart-2" data-toggle="tab">2</a></li> -->
    </ul>
    <div class="tab-content no-padding">
        <div class="chart tab-pane active" id="r-chart-1" style="position: relative; height: 300px;"></div>
        <!-- <div class="chart tab-pane" id="r-chart-2" style="position: relative; height: 300px;">2</div> -->
    </div>
</div><!-- /.nav-tabs-custom -->
                
                    </div><!-- /.Right col -->
                </div><!-- /.box-body -->
            </div><!-- /#tab4-->
            
            <div class="tab-pane" id="tab5">
                <div class="box-body">
                  <table id="data-grid-5" class="table table-bordered table-striped table-condensed">
                    <thead><tr><?php foreach ($total_head as $k=> $v): 
		     ?><th><?php echo $v;?></th><?php 
	    endforeach; ?></tr></thead>
                  </table>
                </div><!-- /.box-body -->
            </div><!-- /#tab5-->
            
            <div class="tab-pane" id="tab6">
                <div class="box-body">
                  <table id="data-grid-6" class="table table-bordered table-striped table-condensed">
                    <thead><tr><?php foreach ($avg_head as $k=> $v): 
		     ?><th><?php echo $v;?></th><?php 
	    endforeach; ?></tr></thead>
                  </table>
                </div><!-- /.box-body -->
            </div><!-- /#tab6-->
    </div>
</div>

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

<!--    datatable js start  -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>

$("#select_date").datepicker({
	format:"yyyy-mm-dd", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left"
});

<?php if($t= $this->input->get('tab')) echo "$('". '#top_tabs a[href="#tab'. $t. '"]'. "').tab('show');"; ?>

   var salesTotalData = <?php echo json_encode($data_1);?>;//订单总额
   var salesOrder = <?php echo json_encode($data_2);?>;
   var countData = <?php echo json_encode($data_3);?>;
   var consumerData = <?php echo json_encode($data_4);?>;

   var chartClick = true;

$('#top_tabs a[href="#tab3"]').on('shown.bs.tab', function () {
    /* Morris.js Charts */
    // Sales chart
    if(chartClick){
    	chartClick = false;
    	//var tab_html_org= $('#chart_tab_header').html();
    	if( salesOrder.length==0 ){
        	alert('该时间段没有任何数据。');
        	return false;
    	} else {
        	alert('图表加载可能会耗费点时间，请耐心等候...');
    	}
        var salesTotalArea = new Morris.Area({ //订单总额
            element: 'sales-total',
            resize: true,
            data: salesTotalData,
            xkey: 'date',
            behaveLikeLine: true,
            parseTime:false,
            ykeys: ['amount'],
            labels: ['总金额'],
            lineColors: ['#3c8dbc'],
            hideHover: 'auto'
        });

        var salesOrderArea = new Morris.Area({ //订单份数
            element: 'sales-order',
            resize: true,
            data: salesOrder,
            xkey: 'date',
            behaveLikeLine: true,
            parseTime:false,
            ykeys: ['amount'],
            labels: ['份数'],
            lineColors: ['#a0d0e0'],
            hideHover: 'auto'
        });

        var countDataArea = new Morris.Area({
            element: 'sales-count',
            resize: true,
            data: countData,
            xkey: 'date',
            behaveLikeLine: true,
            parseTime:false,
            ykeys: ['amount'],
            labels: ['份数'],
            lineColors: ['#C88FE7'],
            hideHover: 'auto'
        });

        var consumerDataArea = new Morris.Area({
            element: 'sales-consumer',
            resize: true,
            data: consumerData,
            xkey: 'date',
            behaveLikeLine: true,
            parseTime:false,
            ykeys: ['amount'],
            labels: ['份数'],
            lineColors: ['#57C43C'],
            hideHover: 'auto'
        });
    	
    } else {<?php 
//        $.ajax({
//            type: 'POST',
//            url: 'http://localhost/iwide30dev/www_admin/index.php/soma/statis_sales/list_chart_ajax',
//            data:{
//                'date':'2016-05-25'
//            },
//            success: function(data){
//                console.log(data);
//            }
//        })?>
    }
});


var donutClick = true;
$('#top_tabs a[href="#tab4"]').on('shown.bs.tab', function () {
    // Pie chart
    if(donutClick){
    	donutClick = false;
    	//DONUT CHART
    	var donut_l1 = new Morris.Donut({
        	element: 'l-chart-1',
        	resize: true,
        	colors: ["#33adbc", "#f66954", "#04475a", "#00a65a"],
        	data: <?php echo json_encode($total_pie, JSON_UNESCAPED_UNICODE);?>,
        	formatter: function(y, data){ return '￥' + y.toLocaleString() },
        	hideHover: 'auto'
    	});
    	$('#l-chart-nav a[href="#l-chart-2"]').on('shown.bs.tab', function () {
        	var donut_l2 = new Morris.Donut({
            	element: 'l-chart-2',
            	resize: true,
            	colors: ["#33adbc", "#f66954", "#04475a", "#00a65a"],
            	data: <?php echo json_encode($count_pie, JSON_UNESCAPED_UNICODE);?>,
            	formatter: function(y, data){ return y+ '单' },
            	hideHover: 'auto'
        	});
        });
    	$('#l-chart-nav a[href="#l-chart-3"]').on('shown.bs.tab', function () {
        	var donut_l3 = new Morris.Donut({
            	element: 'l-chart-3',
            	resize: true,
            	colors: ["#33adbc", "#f66954", "#04475a", "#00a65a"],
            	data: <?php echo json_encode($qty_pie, JSON_UNESCAPED_UNICODE);?>,
            	formatter: function(y, data){ return y+ '件' },
            	hideHover: 'auto'
        	});
        });
    	var donut_r2 = new Morris.Donut({
        	element: 'r-chart-1',
        	resize: true,
        	colors: ["#00a65a"],
        	data: [
        	  {label: "套票业务", value: 100},
        	],
        	formatter: function(y, data){ return y+ '%' },
        	hideHover: 'auto'
    	});
        
    } else {<?php 
//        $.ajax({
//            type: 'POST',
//            url: 'http://localhost/iwide30dev/www_admin/index.php/soma/statis_sales/list_chart_ajax',
//            data:{
//                'date':'2016-05-25'
//            },
//            success: function(data){
//                console.log(data);
//            }
//        })?>
    }
});


/** gridjs start **/
var grid_sort= [[0, "asc" ]];
var dataSet= <?php echo json_encode($table_data, JSON_UNESCAPED_UNICODE); ?>;
var dataSet_total= <?php echo json_encode($total_data, JSON_UNESCAPED_UNICODE); ?>;
var dataSet_price= <?php echo json_encode($avg_data, JSON_UNESCAPED_UNICODE); ?>;
var tableClick = true;

//
function init_tab_click () {
    if(tableClick){
    	tableClick = false;
	    <?php $grid_id_name='data-grid-2'; 
        $data_set_name = 'dataSet';
	    require VIEWPATH. $tpl .DS .'soma'. DS. 'gridjs_lite.php'; ?>

	    <?php $grid_id_name='data-grid-5'; 
        $data_set_name = 'dataSet_total';
	    require VIEWPATH. $tpl .DS .'soma'. DS. 'gridjs_lite.php'; ?>

	    <?php $grid_id_name='data-grid-6'; 
        $data_set_name = 'dataSet_price';
	    require VIEWPATH. $tpl .DS .'soma'. DS. 'gridjs_lite.php'; ?>
	    
	
    } else {<?php 
//      $.ajax({
//          type: 'POST',
//          url: 'http://localhost/iwide30dev/www_admin/index.php/soma/statis_sales/list_chart_ajax',
//          data:{
//              'date':'2016-05-25'
//          },
//          success: function(data){
//              console.log(data);
//          }
//      })?>
    }
}

$('#top_tabs a[href="#tab2"]').on('shown.bs.tab', init_tab_click());
$('#top_tabs a[href="#tab5"]').on('shown.bs.tab', init_tab_click());
$('#top_tabs a[href="#tab6"]').on('shown.bs.tab', init_tab_click());
/** gridjs end **/
 
</script>
</body>
</html>
