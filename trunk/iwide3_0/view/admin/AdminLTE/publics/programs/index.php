<!-- DataTables -->
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/images/laydate12.css">

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
</head>
<style>
.weborder {
	background: #FFFFFF !important;
	display: none;
}

.morder {
	background: #FAFAFA !important;
}

.a_like {
	cursor: pointer;
	color: #72afd2;
}

.page {
	text-align: right;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 2em;
}

.page a {
	padding: 10px;
}

.current {
	color: #000000;
}
</style>
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
<style>
.display_flex{display:flex;display:-webkit-flex;display:box;display:-webkit-box;justify-content:top;align-items:center;-webkit-align-items:center;}
.display_flex >th,.display_flex >td,.display_flex >div{-webkit-flex:1;flex:1;-webkit-box-flex:1;box-flex:1;cursor:pointer;}
.j_toshow{width:320px;min-height:100%;position:absolute;top:50px;right:-330px;box-shadow:-5px 0px 15px rgba(0,0,0,0.1);-webkit-box-shadow:-5px 0px 15px rgba(0,0,0,0.1);z-index:9999;}
.toshow_con{padding:12px;}
.t_con_list{margin-bottom:12px;height:170px;}
.close_btn{cursor:pointer;}
.toshow_con_titl{background:#f0f3f6;font-size:13px;padding:10px;border-bottom:1px solid #d7e0f1;}
.toshow_con_list{padding:10px;font-size:11px;height:114px;overflow:hidden;}
.toshow_con_list >a{display:block;margin-bottom:5px;}
.toshow_con_list >a:last-child{margin-bottom:0px;}
.toshow_titl_txt{position:relative;}
.banner {
    height: 50px;
    width: 100%;
    line-height: 50px;
    border-bottom: 1px solid #d7e0f1;
    padding-right: 0px;
}
@font-face {
  font-family: 'iconfont';
  src: url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.eot');
  src: url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.eot?#iefix') format('embedded-opentype'),
  url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.woff') format('woff'),
  url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.ttf') format('truetype'),
  url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.svg#iconfont') format('svg');
}
.iconfont{
  font-family:"iconfont" !important;
  font-size:16px;font-style:normal;
  -webkit-font-smoothing: antialiased;
  -webkit-text-stroke-width: 0.2px;
  -moz-osx-font-smoothing: grayscale;
}
.over_x{width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;-webkit-overflow-scrolling:touch;overflow-scrolling:touch;}
.clearfix:after{content:"" ;display:block;height:0;clear:both;visibility:hidden;}
.bg_fff{background:#fff;}
.color_fff{color:#fff;}
.bg_ff0000{background:#ff0000;}
.bg_f8f9fb{background:#f8f9fb;}
.bg_ff9900{background:#ff9900;}
.bg_f8f9fb{background:#f8f9fb;}
.bg_fe6464{background:#fe6464;}
.bg_eee{background:#EEEEEE}
.color_72afd2{color:#72afd2;}
.color_ff9900{color:#ff9900;}
.color_F99E12{color:#F99E12;}
a{color:#92a0ae;}

.border_1{border:1px solid #d7e0f1;}
.f_r{float:right;}
.p_0_20{padding:0 20px;}
.w_90{width:90px;}
.w_200{width:200px;}
.p_r_30{padding-right:30px;}
.m_t_10{margin-top:10px;}
.p_0_30_0_10{padding:0 30px 0 10px;}
.b_b_1{border-bottom:1px solid #d7e0f1;}
.b_t_1{border-top:1px solid #d7e0f1;}
.p_t_10{padding-top:10px;}
.p_b_10{padding-bottom:10px;}

.contents{padding:10px 0px 20px 20px;}
.contents_list{display:table;width:100%;border-bottom:1px solid #d7e0f1;margin-bottom:10px;}
.head_cont{padding:20px 0 20px 10px;}
.head_cont >div{margin-bottom:10px;cursor:pointer;}
.head_cont >div:last-child{margin-bottom:0px;}
.j_head >div{display:inline-block;}
.j_head >div:nth-of-type(1){width:307px;}
.j_head >div:nth-of-type(2){width:526px;}
.j_head >div:nth-of-type(3){width:255px;}
.j_head >div >span:nth-of-type(1){display:inline-block;width:60px;text-align:center;}
.head_cont .actives{background:#ff9900;color:#fff;border:1px solid #ff9900 !important;}
.h_btn_list> div{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;}
.h_btn_list> div:last-child{margin-right:0px;}
.classification{height:30px;line-height:30px;border-top:1px solid #d7e0f1;border-right:1px solid #d7e0f1;border-left:1px solid #d7e0f1;width:300px;}
.classification >div{text-align:center;height:30px;border-right:1px solid #d7e0f1;}
.classification >div:last-child{border-right:none;}
.classification .add_active{background:#ecf0f5;border-bottom:1px solid #ecf0f5;}
.fomr_term{height:30px;line-height:30px;}
.classification >div,.all_open_order{cursor:pointer;}
.all_open_order{margin-right:10px;margin-top:5px;}
.template >div{text-align:center;}
.template_img{float:left;width:50px;height:50px;overflow:hidden;vertical-align:middle;margin-right:2%;}
.template_span{display:inline-block;margin-top:2px;}
.template_btn{padding:1px 8px;border-radius:3px;}
.form_con,.form_title{height:30px;line-height:30px;}
.form_con >td,.form_title >th{text-align:center;font-weight:normal;}
.form_thead >tr,.containers >tr{padding:8px 0;}
.form_title >th:nth-of-type(2),.form_con >td:nth-of-type(2){flex:1.5;}
.form_title >th:nth-of-type(7),.form_con >td:nth-of-type(7){flex:2.9;}
.form_title >th:nth-of-type(6),.form_con >td:nth-of-type(6){flex:2.9;}
.containers >tr:nth-child(even){background:#F8F8F8 !important;}
.containers >tr:nth-child(odd){background:#fff !important;}
.form_con >td{padding-right:20px !important;}
.box-body{padding:0px;overflow:hidden;}
#coupons_table_length{display:none;}


.drow_list{display:none;position:absolute;width:100%;top:100%;left:0;background:#fff;border:1px solid #e4e4e4;padding:0;overflow:auto;z-index:999}
.drow_list li{height:35px;padding-left:15px;line-height:35px;list-style:none; cursor:pointer}
.drow_list li:hover{background:#f1f1f1}
.drow_list li.cur{background:#ff9900;color:#fff}
#drowdown:hover .drow_list{display:block}
</style>
<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;" >
            <div class="banner bg_fff p_0_20">
                <?php echo $breadcrumb_html; ?>
            </div>
            <div class="contents">
				<div class="contents_list" style="font-size:13px;">
					<a class="f_r all_open_order color_72afd2" href="/index.php/publics/programs/edit">新增</a>
					<div class="classification display_flex bg_fff">
						<div value="有效">有效</div>
						<div value="失效">失效</div>
						<div class="add_active" value="效">全部</div>
					</div>
				</div>
				<div class="box-body">
					<table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;">
						<thead class="bg_f8f9fb form_thead">
							<tr class="bg_f8f9fb form_title">
								<th>小程序ID</th>
								<th>名称</th>
								<th>简介</th>
								<th>缩略图</th>
								<th>状态</th>
								<th>推荐值</th>
								<th>点击量</th>
								<th>时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
							<?php if(!empty($list)){?>
								<?php $status = array('1'=>'有效','2'=>'失效');?>
				                <?php foreach ($list as $r){?>
								<tr class=" form_con">
									<td><?php echo $r['pro_id'];?></td>
									<td><?php echo $r['name'];?></td>
									<td><?php echo $r['short_intro'];?></td>
									<td><img width="50px" src="<?php echo $r['intro_img'];?>"></td>
									<td><?php echo $status[$r['status']];?></td>
									<td><?php echo $r['recommend'];?></td>
									<td><?php echo $r['hit'];?></td>
									<td><?php echo $r['create_time'];?></td>
									<td><a class="color_72afd2" href="/index.php/publics/programs/edit?ids=<?php echo $r['pro_id'];?>">编辑</a></td>
								</tr>
				                <?php }?>
					        <?php }?>
						</tbody>
					</table>
				</div>
			</div>
        </div>
    </div>

</div>
     
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

<script>
$(function(){
	
	$('#coupons_table').DataTable({
		    "aLengthMenu": [8,50,100,200],
			"iDisplayLength": 10,
			"bProcessing": true,
			"paging": true,
			"lengthChange": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"searching": false,
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
			"searching": true
	});
	var table = $('#coupons_table').DataTable();
	$('.classification >div').click(function(){
		$(this).addClass('add_active').siblings().removeClass('add_active');
		table
	        .columns( 4 )
	        .search( $(this).attr('value') )
	        .draw();
	})
	var tips=$('#tips');
})

</script>
</body>
</html>
