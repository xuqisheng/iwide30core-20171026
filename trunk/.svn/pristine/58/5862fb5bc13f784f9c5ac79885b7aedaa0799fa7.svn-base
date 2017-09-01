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
[checkbox]{
	width: 150px;
	margin-left: 10px;
	margin-bottom: 10px;
}
select {
	border: #d7e0f1 solid 1px;
	vertical-align: middle;
	font-size: 16px;
	width: 200px;
	text-align: left;
	padding: 5px;
	outline: none;
	margin: 0px 10px;
}

select option {
	font-size: 16px;
	font-family: 微软雅黑;
	color: #7e8e9f;
}

.lab {
	font-weight: normal;
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
<form action="<?php echo site_url('notify/notify/edit_wx_post');?>" method="post" id="tform">
<input type="hidden" name="<?php echo $csrf_token;?>" value="<?php echo $csrf_value;?>">
	<flex class="each_line" style="margin-top:0px;">
		<ib class="VA-M head">
			<colorline class="blue_line"></colorline>
			<div>提醒内容</div>
		</ib>
		<ib class="VA-M">
		<?php foreach($check_config as $k=>$v){if($k!='all'){?>
			<ib checkbox>
			<label class="lab">
				<input type="checkbox" name="<?php echo $v['name'];?>" value="<?php echo $k;?>" ctype="notifys" class="_notify" <?php if(!empty($reg_info['wx_notify'])){if(in_array($k,explode(',',$reg_info['wx_notify']))||$reg_info['wx_notify']=='all'){echo 'checked';}}?>>
				<ib><?php echo $v['text'];?></ib>
			</label>
			</ib>
		<?php }}?>
			<div checkbox style="margin-top:20px;">
			<label class="lab">
				<input class="quanxuan" type="checkbox" name="<?php echo $check_config['all']['name'];?>" value="<?php echo 'all';?>" ctype="notifys" id="qx_notify" <?php if(!empty($reg_info['wx_notify'])){if($reg_info['wx_notify']=='all'){echo 'checked';}}?>>
				<ib><?php echo $check_config['all']['text'];?></ib>
			</label>
			</div>
		</ib>
	</flex>
	<!-- <flex class="each_line">
		<ib class="VA-M head">
			<colorline class="red_line"></colorline>
			<div>选择成员</div>
		</ib>
		<ib class="VA-M">
			<select name="regid">
			<option value="0">扫码登记过的成员列表</option>
			<?php foreach($regs as $k => $reg){?>
				<option value="<?php echo $reg['id'];?>" <?php if($reg['id']==$reg_info['id']){echo 'selected';}?>><?php echo $reg['name'];?></option>
			<?php }?>
			</select>
		</ib>
	</flex> -->
	<input type="hidden" name="regid" value="<?php echo $reg_info['id'];?>">
	<flex class="each_line">
		<ib class="VA-M head">
			<colorline class="red_line"></colorline>
			<div>选择酒店</div>
		</ib>
		<ib class="VA-M">
			<select name="hid">
			<option value="0">全部酒店</option>
			<?php foreach($hotels as $k => $hotel){?>
				<option value="<?php echo $hotel['hotel_id'];?>" <?php if($hotel['hotel_id']==$reg_info['hotel_id']){echo 'selected';}?>><?php echo $hotel['name'];?></option>
			<?php }?>
			</select>
		</ib>
	</flex>
	<flex class="each_line">
		<ib class="VA-M head">
			<colorline class="red_line"></colorline>
			<div>审核状态</div>
		</ib>
		<ib class="VA-M">
			<select name="status">
			<option value="2">未通过</option>
			<option value="1" <?php if($reg_info['status']==1)echo 'selected';?>>已通过</option>
			</select>
		</ib>
	</flex>
	<flex class="each_line">
		<ib class="VA-M head">
			<colorline class="green_line"></colorline>
			<div>选择星期</div>
		</ib>
		<ib class="VA-M">
			<?php foreach($weeks_config as $k=>$week){?>
			<ib checkbox>
			<label class="lab">
				<input type="checkbox" name="weeks[<?php echo $k;?>]" value="<?php echo $k;?>" ctype="weeks" class="_week" <?php if(!empty($reg_info['weeks'])){if(in_array($k,explode(',',$reg_info['weeks']))){echo 'checked';}}?>>
				<ib>周<?php echo $week;?></ib>
			</label>
			</ib>
			<?php }?>
			<div checkbox style="margin-top:20px;">
			<label class="lab">
				<input class="quanxuan" type="checkbox" ctype="weeks" id="qx_week">
				<ib>全选</ib>
			</label>
			</div>
		</ib>
	</flex>
	<flex class="each_line" style="justify-content:center;">
		<ib class="baocun" id="savebtn">保存设置</ib>
	</flex>
</form>
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
<script>
	$(".quanxuan").on("click",function(){
		var type = $(this).attr('ctype');
		if(this.checked){
			$("input[ctype='"+type+"']").prop("checked",true);
		}else{
			$("input[ctype='"+type+"']").prop("checked",false);
		}
	});
	$("._notify").on("click",function(){
		if(!this.checked){
			$('#qx_notify').prop("checked",false);
		}
		var checked = true;
		$("._notify").each(function(i){
			if(!this.checked){
				checked = false;
			}
		});
		if(checked==true){
			$('#qx_notify').prop("checked",true);
		}
	});
	$("._week").on("click",function(){
		if(!this.checked){
			$('#qx_week').prop("checked",false);
		}
		var checked = true;
		$("._week").each(function(i){
			if(!this.checked){
				checked = false;
			}
		});
		if(checked==true){
			$('#qx_week').prop("checked",true);
		}
	});
	$('#savebtn').on('click',function(){
		$('#tform').submit();
	});
	var err = <?php echo $err;?>;
	if(err==1){
		alert('保存失败，请稍后再试');
	}
</script>
</body>
</html>
