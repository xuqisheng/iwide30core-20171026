<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
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
<style>
.nav_b{font-size:18px;}
.nav_b a{color:#000;}
.big_box{background:#fff;}
.h_row_list{margin-bottom:1%;}
.checks>div,.h_row_list>div{display:inline-block;}
.noe{width:100px;}
.cheng_fan,.wenxi_fan{font-weight:normal;}
#hotel_fang{border:none;}
tbody tr td,thead > tr >th{border: 1px solid #fff !important;text-align:center;}
thead > tr >th{font-weight:normal;font-size:13px;}
thead > tr{background:#E4E4E4 !important;}
#hotel_fang tbody tr:nth-of-type(odd){background:#F2F2F2 !important;}
#hotel_fang tbody tr:nth-of-type(even){background:#E4E4E4 !important;}
thead > tr >th:nth-of-type(2),tbody tr td:nth-of-type(2){border-right:2px solid #fff;}
thead > tr >th:nth-of-type(5),tbody tr td:nth-of-type(5){border-right:3px solid #fff;}
thead > tr >th:nth-of-type(8),tbody tr td:nth-of-type(8){border-right:3px solid #fff;}
td a{text-decoration:underline;color:#009900;}
tr td:nth-of-type(8),tr td:nth-of-type(5){color:#FF6633;}
#hotel_fang_wrapper .row{background:#fff;}
#hotel_fang_wrapper .row:nth-of-type(1){padding:15px 0 10px 0;}
#hotel_fang_wrapper .row:nth-of-type(2){margin-top:5px;}
#hotel_fang_wrapper .row:nth-of-type(3){padding-top:30px;}
.sevch_b{display:inline-block;background:#009900;padding:5px 30px;color:#fff;}
</style>
<div class="content-wrapper" style="min-height:775px;">
<div class="banner bg_fff p_0_20">
    <?php echo $breadcrumb_html; ?>
</div>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="big-box">
					<!-- <div class="nav_b">
						<a href="">比价 </a> >
						<a href=""> 房型匹配</a>
					</div> -->
					<div class="box-body contion_box">
		            	<table id="hotel_fang" class="table table-bordered table-striped">
		            		<thead>
						        <tr>
						            <th>公众号interID</th>
						            <th>公众号名称</th>
						            <th>酒店数</th>
						            <th>已匹配</th>
						            <th>未匹配</th>
						            <th>房型数</th>
						            <th>已匹配</th>
						            <th>未匹配</th>
						            <th>状态</th>
						        </tr>
						    </thead>
					        <tbody class="new_body">
					        <?php foreach ($lists as $k => $list) {?>
					            <tr>
					                <td class=""><?php echo $list['inter_id'];?></td>
					                <td class=""><?php echo $list['public_name'];?></td>
					                <td><?php echo $list['hotel_num'];?></td>
					                <td><?php echo $list['hotel_al_num'];?></td>
					                <td  class=""><?php echo $list['hotel_nal_num'];?></td>
					                <td  class=""><?php echo $list['room_num'];?></td>
					                <td  class=""><?php echo $list['room_al_num'];?></td>
					                <td  class=""><?php echo $list['room_nal_num'];?></td>
					                <td  class=""><a href="<?php echo site_url('price/paritys/hotels_match?inter_id='.$list['inter_id']);?>">查看</a></td>
					            </tr>
					           <?php }?>
					        </tbody>
					    </table>
			        </div>
				</div>
			</div>
		</div>
	</section>
</div><!-- /.content-wrapper -->
<?php
/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>

<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
?>



<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>
<script type="text/javascript">
window.onload=function() {
	var sevch_b=$('<div class="sevch_b">搜索</div>');
	var otable=$('#hotel_fang').DataTable({
		    "aLengthMenu": [10,20,50],
			"iDisplayLength": 20,
			"bProcessing": true,
			"paging": true,
			"lengthChange": true,
			"ordering": false,
			"searching":true,
			"info": true,
			"autoWidth": false,
			"language": {
				"sSearch": "搜索",
				"lengthMenu": "每页显示 _MENU_ 条记录",
				"zeroRecords": "找不到任何记录. ",
				"info": "",
				//"info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
				"infoEmpty": "",
				"infoFiltered": "(从 _MAX_ 条记录中过滤)",
				"paginate": {
					"sNext": "下一页",
					"sPrevious": "上一页",
				}
			},
	});

	
	$("#hotel_fang_wrapper>div >div:nth-of-type(1)>div>label").parent().parent().addClass('float_btn');
	$(".float_btn").css({"float":"right","text-align":"right"});
	$(".float_btn").removeClass('col-sm-6').addClass('col-sm-3');
	$("#hotel_fang_filter").css({"text-align":"left"})
	$("#hotel_fang").parent().css({"padding-left":"15px"})
	$("#hotel_fang_info").parent().css({"display":"none"})
	$("#hotel_fang_paginate").css({"text-align":"left"});
	var str2=$("#hotel_fang_filter >label").html().replace(/搜索/, "");
	$("hotel_fang_filter >label").html(str2).append(sevch_b);
	/*$("#hotel_fang_filter >label input").css({"float":"left"});*/
	$("#hotel_fang_filter >label input").attr({"placeholder":"请输入公众号名称"});
};
</script>
