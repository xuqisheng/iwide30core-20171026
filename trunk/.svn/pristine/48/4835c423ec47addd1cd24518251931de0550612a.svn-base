<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/tao.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<style>
.each_line {
	margin-top: 10px;
	background-color: white;
	border: 1px solid #d7e0f1;
}

.each_line .VA-M:first-child {
	min-width: 150px;
	margin-left: 50px;
}

.each_line .VA-M:nth-of-type(2) {
	padding: 15px 20px;
	border-left: 1px solid #d7e0f1;
}

.each_line a{
	color: #72afd2;
}
table tr {
	border-bottom: 1px solid #dde5f3;
}

table {
	border-top: 1px solid #dde5f3;
}

table td {
	padding: 10px 0px;
}

table th {
	padding: 10px 0px;
	background-color: #f8f9fb;
}

table td:nth-child(6) {
	color: #00b7ec;
	text-decoration: underline;
	cursor: pointer;
}
.baocun{
    width: 150px;
	background-color: #ff9900;
	text-align: center;
	padding: 10px 0px;
	margin: 10px;
	color: white;
	border-radius: 10px;
	cursor: pointer;
}
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
	border-radius: 10px;
	background-color: white;
	left: 50%;
	margin-left: -150px;
	padding: 5px;
}
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
<div class="content-wrapper" style="min-height: 1271px;">
<div class="banner bg_fff p_0_20">
    <?php echo $breadcrumb_html; ?>
</div>
<section class="content">
	<flex class="each_line" style="margin-top:0px;">
		<table>
			<tr>
				<th>姓名</th>
				<th>酒店</th>
				<th>提醒内容</th>
				<th>星期</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
			<?php if(!empty($regs)){foreach($regs as $kr=>$vr){?>
			<tr>
				<td><?php echo $vr['name'];?></td>
				<td><?php echo $vr['hname'];?></td>
				<td><?php echo $vr['wx_notify'];?></td>
				<td><?php echo $vr['weeks'];?></td>
				<td><?php if($vr['status']==1){echo '已通过';}else{echo '未通过';};?></td>
				<td><a href="<?php echo site_url('notify/notify/edit_wx').'?id='.$vr['id'];?>">编辑</a></td>
			</tr>
			<?php }}?>
		</table>
	</flex>
	<flex class="each_line" style="justify-content:center;">
		<ib class="baocun" id="qrapply">扫码登记</ib>
		<!-- <ib class="baocun" id="" onclick="window.location.href='<?php echo site_url('notify/notify/edit_wx');?>'">添加成员</ib> -->
	</flex>
	<div class="tanchu" style="display:none">
		<div class="kuang">
		<img id="qrimg" src="<?php echo site_url('notify/notify/apply_qr_code');?>">
		<div><a href="javascript:void(0);" style="text_align:center;" id="qrclose">点击关闭</a></div>
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

$('#qrapply').click(function(){
	if($('.tanchu').css('display')=='none'){
		$('.tanchu').css('display','block');
	}else{
		$('.tanchu').css('display','none');
	}
});
$('#qrclose').click(function(){
	$('.tanchu').css('display','none');
});


</script>
</body>
</html>
