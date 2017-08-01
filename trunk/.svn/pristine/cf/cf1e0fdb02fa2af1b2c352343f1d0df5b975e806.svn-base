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
.box-title{font-size:24px !important;}
.r_btn{float:right;background:#009900;font-size:13px;color:#fff;padding:2px 22px;}
.m_width{max-width:550px;}
.title_t{background:#FFCC66;}
.tables{width:100%;max-width:100%;margin-bottom:20px;}
.table-bordereds{border:0px solid #f4f4f4;border-radius:4px;}
.new_body >tr:nth-of-type(1)>td a,.new_body >tr:nth-of-type(2)>td a,.new_body >tr:nth-of-type(3)>td a{color:#FF0000;}
.new_body >tr>td a{color:#000;}
.new_body >tr>td:after{content: "" ;display:block;height:0;clear:both;visibility:hidden;}
tbody > tr td> a span{display:inline-block;}
.numb_text{width:25px;margin-right:3px;}
.numb_na{width:280px;margin-right:10px;}
.fasd{float:right;color:#999;}
tbody > tr td{background:#fff;}
.box{box-shadow:0 0px 0px rgba(0,0,0,0.1)}
.hotel_ranking{background:#FFCC66;font-size:16px;padding:8px 12px;border:1px solid #E4E4E4;}
#sam_box_yi{margin-top:0px !important;}
.tanchu{
	position: fixed;
	background-color: rgba(0,0,0,0.5);
	text-align: center;
	width: 100%;
	height: 100%;
	top: 0px;
	left: 0px;
}
.kuang{
	width: auto;
	position: absolute;
	top: 100px;
	color: white;
	/*border-radius: 10px;*/
	/*background-color: white;*/
	left: 50%;
	margin-left: -150px;
}
</style>
<div class="content-wrapper" style="min-height:775px;">
<div class="banner bg_fff p_0_20">
    <?php echo $breadcrumb_html; ?>
</div>
<?php if(empty($is_list)){?>
<div class="big_box" style="text-align:center;padding:0% 0%;margin-top:0%; min-width:850px;background:#fff;height:80px;">
<!-- <h3 class="box-title">暂不可用！！</h3> -->
	<div style="padding:20px;">
		<span style="font-size:16px;">尚未开启比价系统，点击开启</span>
		<button id="pbtn" onclick="to_parity()" style="background:#009900;border:1px;color:#fff;width:42px;">确认</button>
	</div>
</div>
<?php }else{?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="big_box">
					<!-- <div class="nav_b">
						<a>比价 </a> >
						<a> 酒店比价 </a>
					</div> -->
					<div class="big_box" style="padding:0% 0%;margin-top:0%; min-width:850px;background:#fff;">
						<div class="box-header">
							<a class="r_btn" target="_blank" href="<?php echo site_url('price/paritys/detail?inter_id='.$inter_id);?>">点此进入比价详情   > </a>
			            	<h3 class="box-title"><?php echo $hname;?></h3>
			            </div>
			            <div style="padding:10px;">
						生成比价结果：您还可生成<span id="tnum" style="color:#FF0000;"><?php echo $num;?></span>次比价&nbsp;&nbsp;&nbsp;&nbsp;<button id="tbtn" style="background:#009900;border:1px;color:#fff;" <?php if($num<1){ echo 'disabled';}?>>确认生成</button><br>
			            <a target="_blank" style="color:#009900;font-size:13px;text-decoration:underline;" href="<?php echo site_url('price/paritys/smart_price');?>">去设置智能调价？</a></div>
			            <div class="box-body contion_box">
			            	<table id="sam_box_yi" class="table table-bordered table-striped">
			            		<thead style="background:#fff;">
							        <tr>
							            <th style="display:none;">title-1</th>
							        </tr>
							    </thead>
						        <tbody class="new_body">
						        <?php $ser = 0;foreach ($list as $k => $v) { ?>
						            <tr>
						                <td>
						                	<a style="display:block;" target="_blank" href="<?php echo site_url('price/paritys/detail?inter_id='.$inter_id.'&hotel_id='.$v['hotel_id']);?>"><i class="fa fasd fa-fw fa-arrow-circle-o-right"></i>
						                	<span class="numb_text"><?php echo ++$ser;?></span>
						                	<span class="numb_na"><?php echo $v['hotel_name'];?></span>
						                	<span class="percentage"><?php echo $v['percent'].'%';?></span>
						                	</a>
						                </td>
						            </tr>
					            <?php } ?>
						        </tbody>
						    </table>
			            </div>
			        	<hr style="width:85%;color:#666;height:3px;background:#666;border:0px;border-radius:6px;">
			        </div>
				</div>
			</div>
		</div>
	</section>
	<div class="tanchu" style="display:none">
		<div class="kuang">
		<div class="tc_title">比价结果生成中...</div>
			<div style="margin-top:10px;">
				完成度：<span id='al_num'>0</span>/<?php echo $hotel_nums;?>
			</div>
		</div>
	</div>
	<?php }?>
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
var inter_id = '<?php echo $inter_id;?>';
var hotel_nums = '<?php echo $hotel_nums;?>';
var num = '<?php echo $num;?>';
var tp = 0;
// 确认生成
function to_confirm(){
	to_bp(1);
	$.get('<?php echo site_url('price/paritys/request_price');?>',
		function(m){
			if(m==1){
				// alert('后台已开始生成比价结果，请等待');
				setInterval('get_last_num()',3000);
			}else{
				alert(m);
				window.location.reload();
			}
		}
		);
}
//获取剩余未生成的酒店数
function get_last_num(mode){
	$.getJSON("<?php echo site_url('price/paritys/request_num');?>"+'?inter_id='+inter_id+'&num='+num,
		function(m){
			//完成
			if(m.stat=='complete'){
				if(mode!='check'){
					// alert('比价结果生成完毕');
					window.location.reload();
				}
			}else if(m.stat=='doing'){
				if(mode=='check'){
					$('.tanchu').show();
					$('#al_num').text(m.n);
					setInterval('get_last_num()',3000);
				}else{
					$('#al_num').text(m.n);
				}
			}
		}
	);
}
function to_bp(t){
	var num = $('#tnum').text();
	if(num<1){
		$('#tbtn').css('background','gray');
		$('#tbtn').attr('disabled',true);
	}
	if(t==1){
		$('.tanchu').show();
		$('#tnum').text(num-1);
		$('#tbtn').css('background','gray');
		$('#tbtn').attr('disabled',true);
	}
}
//确认开启比价系统
function to_parity(){
	if(tp==0){
		if(confirm('确认开启比价系统吗？')){
			tp = 1;
			//切换loading图
			var loadimg = "<img style='width:70%;' src='<?php echo base_url('public/img/loads.jpg');?>'>";
			$('#pbtn').css('background','#fff');
			$('#pbtn').html(loadimg);
			$.get("<?php echo site_url('price/paritys/auto_parity');?>",
				function(m){
					if(m=='finish'){
						window.location.href="<?php site_url('price/paritys/hotel_index');?>";
					}else if(m=='exist'){
						window.location.href="<?php site_url('price/paritys/hotel_index');?>";
					}else if(m=='fail'){
						alert('开启失败');
					}else{
						alert('执行时间过长,请稍后刷新查看');
					}
				});
		}
	}
}
window.onload=function() {
	var odiv=$('<div class="hotel_ranking">携程倒挂率排名</div>');
	var otable=$('#sam_box_yi').DataTable({
		    "aLengthMenu": [20,50,100,200],
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
			"rowCallback":function(nRow,aData, iDataIndex){
            	return nRow;
        	},
	});
	// $(".big_box").addClass('m_width');
	$("#sam_box_yi_wrapper>div >div:nth-of-type(1)>div>label").parent().parent().addClass('float_btn');
	$(".float_btn").css({"float":"right","text-align":"right"})
	$("#sam_box_yi").parent().prepend(odiv);
	to_bp(0);
	get_last_num('check');
	$('#tbtn').click(function(){
		to_confirm();
	});
};
</script>
