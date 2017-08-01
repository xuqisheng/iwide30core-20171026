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
td a{text-decoration:underline;color:#009900;}
tr td:nth-of-type(5){color:#FF6633;}
#hotel_fang_wrapper .row:nth-of-type(2){display:none;}
#hotel_fang_wrapper .row:nth-of-type(3) table{margin-top:1px !important}
#hotel_fang_wrapper .row:nth-of-type(4){margin-top:5%;}
.long_box{background:#fff;margin-top:0%;}
.titles{background:#E4E4E4;font-size:13px;text-align:center;padding:20px;}
.b_con{display:flex;display:-webkit-flex;width:100%;}
.con_le{margin-right:5px;}
.con_le,.con_ri{display:flex;display:-webkit-flex;background:#F2F2F2;flex:1;-webkit-flex:1;padding:15px 0 15px 0;}
.con_le>div,.con_ri>div{flex:1;-webkit-flex:1;text-align:center;border-right:1px dashed #E4E4E4;}
.con_le>div:last-child,.con_ri>div:last-child{border-right:none;}
.b_con p{margin:0px;}
.b_con div p:nth-of-type(1){font-size:24px;font-weight:normal;}
.color_FF6{color:#FF6633;}
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
						<a href=""> <?php echo $public_name;?> </a> >
						<a href=""> 房型匹配 </a> 
					</div> -->
					<div class="long_box">
						<div class="titles">实时数据</div>
						<div class="b_con">
							<div class="con_le" >
								<div>
									<p><?php echo $hotel_num;?></p>
									<p>酒店数</p>
								</div>
								<div>
									<p><?php echo $hotel_al_num;?></p>
									<p>已匹配酒店数</p>
								</div>
								<div>
									<p class="color_FF6"><?php echo $hotel_nal_num;?></p>
									<p>未匹配酒店数</p>
								</div>
							</div>
							<div class="con_ri">
								<div>
									<p><?php echo $room_num;?></p>
									<p>房型数</p>
								</div>
								<div>
									<p><?php echo $room_al_num;?></p>
									<p>已匹配房型</p>
								</div>
								<div>
									<p class="color_FF6"><?php echo $room_nal_num;?></p>
									<p>未匹配房型</p>
								</div>
							</div>
						</div>
						<div class="box-body contion_box">
			            	<table id="hotel_fang" class="table table-bordered table-striped">
			            		<thead>
							        <tr>
							            <th>酒店ID</th>
							            <th>酒店名称</th>
							            <th>房型数量</th>
							            <th>已匹配</th>
							            <th>未匹配</th>
							            <th>是否已匹配酒店名称</th>
							            <th>状态</th>
							        </tr>
							    </thead>
						        <tbody class="new_body">
						            <?php foreach ($parity_lists as $k => $plist) {?>
						            <tr>
						                <td class=""><?php echo $plist['hotel_id'];?></td>
						                <td class=""><?php echo $plist['name'];?></td>
						                <td><?php echo $plist['room_num'];?></td>
						                <td><?php echo $plist['room_al_num'];?></td>
						                <td><?php echo $plist['room_nal_num'];?></td>
						                <td <?php if($plist['grab_flag']!=1){echo "style='color:#FF6633;'";}?>><?php if($plist['grab_flag']==1){ echo '是';}else{ echo '否';}?></td>
						                <td><a href="<?php echo site_url('price/paritys/hotel_edit?inter_id='.$plist['inter_id'].'&hotel_id='.$plist['hotel_id']);?>">修改</a></td>
						            </tr>
						            <?php }?>
						        </tbody>
						    </table>
				        </div>
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
	var odiv=$('<div class="titles">匹配详情</div>')
	var otable=$('#hotel_fang').DataTable({
		    "aLengthMenu": [10,20,50],
			"iDisplayLength": 20,
			"bProcessing": true,
			"paging": true,
			"lengthChange": true,
			"ordering": false,
			"searching":false,
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
	//console.log($(".float_btn"));
	//$("#hotel_fang_wrapper .row:nth-of-type(3)").appendTo($(".float_btn"));
	$(".float_btn").appendTo($("#hotel_fang_wrapper .row:nth-of-type(3)"));
	$("#hotel_fang_wrapper").prepend(odiv);
};
</script>