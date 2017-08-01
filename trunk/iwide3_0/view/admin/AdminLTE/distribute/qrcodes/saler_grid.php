<!-- DataTables -->
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
	<style>
<!--
.zero-clipboard{position: relative;display: block;}
.btn-clipboard{border-top-right-radius: 0;position: absolute;top: -1px;right: -1px;z-index: 10;display: block;padding: 5px 8px;font-size: 12px;color: #777;cursor: pointer;background-color: #fff;border: 1px solid #e1e1e8; }
/*#qjform .form-control{display:initial;width:58%;padding:2px 5px; height:auto}*/
#qjform .row{margin-top: 5px;}
#qjform .input-sm{padding: 2px 5px;}
.nav-tabs-custom > .nav-tabs > li.active { border-top-color:#ff9900}
-->
        .display_none{display: none!important;}
        .display_show{display: none!important;}
</style>
<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>
<link rel="stylesheet" href="http://mp.iwide.cn/public/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css">
<script src="http://mp.iwide.cn/public/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js"></script>
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
    <!-- Main content -->
    <section class="content">
        <?php echo $this->session->show_put_msg(); ?>
        <div class="panel panel-default bd">
            <div class="zero-clipboard"><span class="btn-clipboard">编辑</span></div>
              <div class="panel-body">
              <?php echo form_open('distribute/qrcodes/qjsave',array('id'=>'qjform'),array('inter_id'=>$saler_infos->inter_id,'qrcode_id'=>$saler_infos->qrcode_id))?>
            <div class="row">
                <div class="col-md-2" id="name">分销员：<span class="name_span"><?php echo empty($saler_infos->name) ? '-' : $saler_infos->name?></span><input type="text" name="name" class="form-control input-sm" style="display: none" value="<?php echo empty($saler_infos->name) ? '' : $saler_infos->name?>" /></div>
                <div class="col-md-2">分销号：<?php echo empty($saler_infos->qrcode_id) ? '-' : $saler_infos->qrcode_id;?></div>
                <div class="col-md-2" id="cellphone">手机号：<span class="name_span"><?php echo empty($saler_infos->cellphone) ? '-' : $saler_infos->cellphone?></span><input type="tel" name="cellphone" class="form-control input-sm" style="display: none" value="<?php echo empty($saler_infos->cellphone) ? '' : $saler_infos->cellphone?>" /></div>
                <div class="col-lg-2 col-md-3 col-sm-4" id="status">分销状态：<span class="name_span"><?php echo isset($grades_status[$saler_infos->status]) ? $grades_status[$saler_infos->status] : '--' ;?></span><select name="status" class="form-control input-sm" style="display:none"><?php foreach ($valid_stat as $stat):?><option value="<?php echo $stat?>"<?php echo $saler_infos->status == $stat ? ' selected' : ''?>><?php echo $grades_status[$stat]?></option><?php endforeach;?></select></div>
            </div>
            <div class="row">
                <div class="col-md-2" id="hotel" hid="<?php echo $saler_infos->hotel_id?>">
                    所属酒店：<span class="name_span"><?php echo empty($saler_infos->hotel_name) ? '-' : $saler_infos->hotel_name?></span>
                    <select name="hotel_id" data-live-search="true" class="form-control input-sm display_none selectpicker" style="display:none">
                        <?php foreach ($hotels as $k=>$v):?>
                            <option value="<?php echo $k?>"<?php if($k == $saler_infos->hotel_id):echo ' selected';endif;?>><?php echo $v?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="col-md-2" id="dept">
                    所属部门：<span class="name_span"><?php echo empty($saler_infos->master_dept) ? '-' : $saler_infos->master_dept;?></span>
                    <span>
                    <select name="master_dept" class="form-control input-sm display_none selectpicker " data-live-search="true" style="display:none">
                        <?php foreach ($depts as $dept):?>
                            <option value="<?php echo $dept->master_dept?>"<?php echo $saler_infos->master_dept == $dept->master_dept ? ' selected' : ''?>>
                            <?php echo $dept->master_dept?></option><?php endforeach;?>
                    </select>
                        </span>
                </div>
                <div class="col-md-2">申请时间：<?php echo empty($saler_infos->status_time) ? '-' : $saler_infos->status_time;?></div>
                <div class="col-md-2">通过时间：<?php echo empty($saler_infos->auth_time) ? '-' : $saler_infos->auth_time;?></div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-4">总收益：￥<?php echo empty($grades_summ->total_grades) ? '0' : $grades_summ->total_grades?></div>
                <div class="col-lg-2 col-md-3 col-sm-4">未发放收益：￥<?php echo empty($grades_summ->undeliver) ? '0' : $grades_summ->undeliver?></div>
                <div class="col-lg-2 col-md-3 col-sm-4">绩效笔数：<?php echo empty($grades_summ->total_counts) ? '0' : $grades_summ->total_counts?>&nbsp;笔</div>
                <div class="col-lg-2 col-md-3 col-sm-4">已发放笔数：<?php echo empty($grades_summ->delivered) ? '0' : $grades_summ->delivered?>&nbsp;笔</div>
            </div>
            <?php echo form_close();?>
            </div>
     	</div>
            
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#all" data-toggle="tab" aria-expanded="false">全部</a></li>
          <li class=""><a href="#delivered" data-toggle="tab" aria-expanded="false">已发放</a></li>
          <li class=""><a href="#undeliver" data-toggle="tab" aria-expanded="true">未发放</a></li>
          <li class=""><a href="#unconfirm" data-toggle="tab" aria-expanded="true">未核定</a></li>
          <li class=""><a href="#rooms" data-toggle="tab" aria-expanded="true">订房订单</a></li>
          <li class=""><a href="#mall" data-toggle="tab" aria-expanded="true">商城订单</a></li>
          <li class=""><a href="#package" data-toggle="tab" aria-expanded="true">套票订单</a></li>
          <li class=""><a href="#invalid" data-toggle="tab" aria-expanded="true">核定无绩效</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="all">
            <table id="all-grid"
                    class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>订单号</th>
                            <th>商品名</th>
                            <th>订单类型</th>
                            <th>交易粉丝</th>
                            <th>交易状态</th>
                            <th>交易金额</th>
                            <th>绩效金额</th>
                            <th>绩效状态</th>
                            <th>核定时间</th>
                        </tr>
                    </thead>
                            </table>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="delivered">
            <table id="delivered-grid"
                    class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>订单号</th>
                            <th>商品名</th>
                            <th>订单类型</th>
                            <th>交易粉丝</th>
                            <th>交易状态</th>
                            <th>交易金额</th>
                            <th>绩效金额</th>
                            <th>绩效状态</th>
                            <th>核定时间</th>
                        </tr>
                    </thead>
                            </table>
          </div>
          <!-- /.tab-pane -->
    
          <div class="tab-pane" id="undeliver">
            <table id="undeliver-grid"
                    class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>订单号</th>
                            <th>商品名</th>
                            <th>订单类型</th>
                            <th>交易粉丝</th>
                            <th>交易状态</th>
                            <th>交易金额</th>
                            <th>绩效金额</th>
                            <th>绩效状态</th>
                            <th>核定时间</th>
                        </tr>
                    </thead>
                            </table>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="unconfirm">
            <table id="unconfirm-grid"
                    class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>订单号</th>
                            <th>商品名</th>
                            <th>订单类型</th>
                            <th>交易粉丝</th>
                            <th>交易状态</th>
                            <th>交易金额</th>
                            <th>绩效金额</th>
                            <th>绩效状态</th>
                            <th>核定时间</th>
                        </tr>
                    </thead>
                            </table>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="rooms">
            <table id="rooms-grid"
                    class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>订单号</th>
                            <th>商品名</th>
                            <th>订单类型</th>
                            <th>交易粉丝</th>
                            <th>交易状态</th>
                            <th>交易金额</th>
                            <th>绩效金额</th>
                            <th>绩效状态</th>
                            <th>核定时间</th>
                        </tr>
                    </thead>
                            </table>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="mall">
            <table id="mall-grid"
                    class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>订单号</th>
                            <th>商品名</th>
                            <th>订单类型</th>
                            <th>交易粉丝</th>
                            <th>交易状态</th>
                            <th>交易金额</th>
                            <th>绩效金额</th>
                            <th>绩效状态</th>
                            <th>核定时间</th>
                        </tr>
                    </thead>
                            </table>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="package">
            <table id="package-grid"
                    class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>订单号</th>
                            <th>商品名</th>
                            <th>订单类型</th>
                            <th>交易粉丝</th>
                            <th>交易状态</th>
                            <th>交易金额</th>
                            <th>绩效金额</th>
                            <th>绩效状态</th>
                            <th>核定时间</th>
                        </tr>
                    </thead>
                            </table>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="invalid">
            <table id="invalid-grid"
                    class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>订单号</th>
                            <th>商品名</th>
                            <th>订单类型</th>
                            <th>交易粉丝</th>
                            <th>交易状态</th>
                            <th>交易金额</th>
                            <th>绩效金额</th>
                            <th>绩效状态</th>
                            <th>核定时间</th>
                        </tr>
                    </thead>
                            </table>
          </div>
          <!-- /.tab-pane -->
        </div>
    </div>
    <!-- /.tab-content -->
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
		<!-- /.content-wrapper -->
<?php 
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>

</div>
	<!-- ./wrapper -->

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>

<script
		src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
	<script
		src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<!-- SlimScroll -->
	<script
		src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
	<!-- page script -->
	<script>
var buttons = $('<div class="btn-group"></div>');


<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];
var gradeTypes  = <?php echo json_encode($grade_types)?>;
var gradeStatus = <?php echo json_encode($grade_status)?>;
var orderStatus = <?php echo json_encode($order_status)?>;

$(document).ready(function() {
	$('.btn-clipboard').on('click',function(){
		var _this = $(this);
		if(_this.html() == '编辑'){
			_this.html('保存');
			$('#qjform .name_span').css('display','none');
			$('#qjform select').css('display','block');
			$('#qjform input').css('display','');

            $('#qjform .bootstrap-select').removeClass('display_none');
		}else{
// 			_this.html('保存');
			$.post("<?php echo site_url('distribute/qrcodes/qjsave')?>",$('#qjform').serialize(),function(data){
				if(data == "success"){
					window.location.reload();
				}else{
					alert('资料保存失败');
				}
			});
		}
	});
	var configs = {
			"aLengthMenu": [20],
			"iDisplayLength": 20,
			"bProcessing": true,
			"paging": true,
			"bPaginate": true,
			"bLengthChange": true,
			"bInfo": true,
			"lengthChange": true,
//	 		"ordering": true,
//	 		"order": grid_sort,
			"info": true,
			"autoWidth": false,
			"sPaginationType":   "full_numbers",
			"language": {
				"sSearch": "搜索",
				"lengthMenu": "每页显示 _MENU_ 条记录",
				"zeroRecords": "找不到任何记录. ",
				"info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
				"infoEmpty": "",
				"infoFiltered": "(从 _MAX_ 条记录中过滤)",
				"paginate": {
					"sNext": "下一页",
					"sPrevious": "上一页",
					"sFirst": "首页",
					"sLast": "末页",
				}
			},
			buttons: [
		        'csv'
		    ],
			"processing": true,
			"serverSide": true,
			"recordsTotal": true,
			"ajax": {
				"type": 'POST',
				"url": "<?php echo site_url('distribute/qrcodes/saler_grade_summ')?>?sid=<?php echo $this->input->get('sid')?>",
				"data": {<?php echo config_item('csrf_token_name') ?>: '<?php echo $this->security->get_csrf_hash() ?>','sts':'ALL' }
			},
//	 		"rowCallback": function(row, data ) {
//	 			if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
//	 				$(row).addClass('bg-gray');
//	 			}
//	 		},
			"columns": [
	            { "data": "order_id" },
	            { "data": "product" },
	            { "data": "grade_table" },
	            { "data": "nickname" },
	            { "data": "order_status" },
	            { "data": "order_amount" },
	            { "data": "grade_total" },
	            { "data": "status" },
	            { "data": "grade_time" }
	        ],
			//"data": dataSet,
			"searching": false
		};
	$('a[data-toggle="tab"]').on('click',function(){
		var _this = $(this);
		configs.ajax.data.sts = 'ALL';
		switch(_this.attr('href')){
			case '#delivered':
				configs.ajax.data.sts = 'DELIVERED';
				break;
			case '#undeliver':
				configs.ajax.data.sts = 'UNDELIVER';
				break;
			case '#unconfirm':
				configs.ajax.data.sts = 'UNCONFIRM';
				break;
			case '#rooms':
				configs.ajax.data.sts = 'ROOMS';
				break;
			case '#mall':
				configs.ajax.data.sts = 'MALL';
				break;
			case '#package':
				configs.ajax.data.sts = 'PACKAGE';
				break;
			case '#invalid':
				configs.ajax.data.sts = 'INVALID';
				break;
		}
		if($(_this.attr('href') + "-grid>tbody").length == 0){
			var grid_table = $(_this.attr('href') + "-grid").DataTable(configs);
			$(_this.attr('href') + '-grid_length').append('&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-sm bg-green" href="<?php echo site_url('distribute/qrcodes/ex_gsalers').'?sid='.$this->input->get('sid')?>&sts=' + configs.ajax.data.sts + '">导出</a>');
		}
	});
	var grid_table = $("#all-grid").DataTable(configs);
	$('#all-grid_length').append('&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-sm bg-green" href="<?php echo site_url('distribute/qrcodes/ex_gsalers').'?sid='.$this->input->get('sid')?>&sts=' + configs.ajax.data.sts + '">导出</a>');
});
</script>
</body>
</html>
