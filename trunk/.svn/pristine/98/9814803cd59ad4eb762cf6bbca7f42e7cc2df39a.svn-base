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
._nav{font-size:18px;padding:15px;}
._nav a{color:#000;}
.content-wrapper {
	background-color: #f0f3f6;
	font-size: 14px;
	font-family: 微软雅黑;
	color: #7e8e9f;
}
._content {
    /*margin: 10px;*/
	background-color: white;
	border: 1px solid #d7e0f1;
}
._content table{
	width: 100%;
	text-align: center;
	border-collapse:collapse;
	border:none;
}
._content table input{
	/*border: 0px;*/
	margin: 0px;
	padding: 0px;
	text-align: center;
	font-size: 14px;
	outline: none;
	font-family: normal;
}
._content:first-child table td{
	padding: 10px;
	border:solid #d7e0f1 1px;
}
._content:first-child table td:nth-child(5){
	color: #2d87e2;
	cursor: pointer;
}
._content:nth-child(2) table td:nth-child(5){
	color: #2d87e2;
	cursor: pointer;
}
._content:first-child table tr:nth-child(3){
	background-color: #f8f9fb;
}
._content:nth-child(2) table td:nth-child(5){
	color: #2d87e2;
}
._content table tr:nth-child(2) td{
	padding: 10px;
	border-top:none;
}
._content table tr:last-child td{
	border-bottom: none;
}
._content table td{
	padding: 10px;
	border-top:solid #d7e0f1 1px;
}
._content table tr:nth-child(2n) td{
	background-color: #f8f9fb;
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
}
.tc_title{
	margin: 10px 20px;
	padding: 10px 20px;
	border-bottom: 1px solid black;
	color: black;
}
.tc_neirong{
	padding: 10px;
	padding-left: 35px;
	text-align: left;
}
.ff9900{
	color: #ff9900;
}
.scrollbar{
	overflow: auto;
	max-height: 300px;
}
.room_table tr >td:nth-of-type(2),.room_table tr >td:nth-of-type(3){
	text-align: left;
	padding-left: 50px;
}
.room_table tr >td:nth-of-type(4){
	text-align: right;
	padding-right: 50px;
}
</style>
<div class="content-wrapper" style="min-width:850px;">
<div class="banner bg_fff p_0_20">
    <?php echo $breadcrumb_html; ?>
</div>
<!-- <div class="_nav">
	<a href="">比价 </a> >
	<a href=""> <?php echo $public_name;?> </a> >
	<a href=""><?php if(!empty($hotel_info['hotel_parity']['name'])){echo $hotel_info['hotel_parity']['name'];}?> </a> >
	<a>修改 </a> 
</div> -->
<div class="content">
<section class="_content">
		<table>
			<tr style="display:none;">
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<tr>
				<td colspan="6">匹配酒店名称</td>
			</tr>
			<tr>
				<td>酒店ID</td>
				<td style="color:#4caf50;">微信酒店名称</td>
				<td colspan="2" style="color:#ff503f;">携程酒店ID/酒店名称</td>
				<td>状态</td>
			</tr>
			<?php if(!empty($hotel_info['hotel_parity'])){?>
			<tr>
				<td><?php echo $hotel_info['hotel_parity']['hotel_id'];?></td>
				<td><?php echo $hotel_info['hotel_parity']['name'];?></td>
				<td><input type="text" value="<?php echo $hotel_info['hotel_parity']['ctrip_id'];?>" placeholder="填写携程酒店ID" id="tid"></td>
				<td><input type="text" value="<?php echo $hotel_info['hotel_parity']['ctrip_name'];?>" placeholder="填写携程酒店名称" id="thn"></td>
				<td><span onclick="save_hotel()">保存</span></td>
			</tr>
			<?php }?>
		</table>
	</section>
	<section class="_content" style="margin-top:10px;">
		<table class="room_table">
			<tr style="display:none;">
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<tr>
				<td colspan="5" style="padding:10px;">匹配房型</td>
			</tr>
			<tr>
				<td>房型ID</td>
				<td>微信酒店名称</td>
				<td>携程房型匹配</td>
				<td>差价</td>
				<td style="color:#7e8e9f;cursor: inherit;">操作</td>
			</tr>
			<?php if(!empty($hotel_info['room_parity'])){foreach ($hotel_info['room_parity'] as $k => $vr) {?>
			<tr>
				<td><?php echo $vr['room_id'];?></td>
				<td><?php echo $vr['w_room_name'].'-'.$vr['price_name'].'-'.$vr['ibreakfast'].'-'.$vr['total_price'];?></td>
				<td><?php echo $vr['c_room_name'].'-'.$vr['breakfast'].'-'.$vr['bed'].'-'.$vr['remark'].$vr['gift'].'-'.$vr['price'];?></td>
				<td><span <?php if(trim($vr['chajia'],'¥')>0){ echo 'style="color:#ff503f;"';}?>><?php echo $vr['chajia'];?></span></td>
				<td><span onclick="alert_rooms('<?php echo $vr['id'];?>','<?php echo $vr['third_room_id'];?>')">修改</span></td>
			</tr>
			<?php }}?>
		</table>
	</section>
	<div class="tanchu" style="display:none">
		<div class="kuang">
			<div class="tc_title">编辑携程匹配房型</div>
			<div class="scrollbar">
			<?php if(!empty($third_rooms)){ foreach($third_rooms as $k=>$room){ ?>
			<div class="tc_neirong">
				<ib><input type="radio" name="third_room_id" value="<?php echo $room['id']; ?>" id="r<?php echo $room['id']; ?>" pid=""></ib><ib><?php echo $room['room_name'].'-'.$room['breakfast'].'-'.$room['bed'].'-'.$room['remark'].$room['gift'].'-'.$room['price'];?></ib>
				<ib class="ff9900" id="c<?php echo $room['id']; ?>" style="display:none">(当前匹配)</ib>
			</div>
			<?php }}?>
			</div>
			<div style="margin-top:50px;">
				<ib><input type="checkbox" name="athour" value="1"></ib>此房为钟点房
				<ib><input type="checkbox" name="nop" value="1"></ib>不匹配任何房型
			</div>
			<div style="border-radius:2px;color:white;background-color:#ff9900;margin:10px auto;width:75px;cursor:pointer;padding:5px;float:left;margin-left:138px;" onclick="save_room()">
				提交
			</div>
			<div id="alert_cancel" style="border-radius:2px;color:white;margin:10px auto;background:gray;width:75px;cursor:pointer;padding:5px;float:left;margin-left:80px;">
				取消
			</div>
		</div>
	</div>
	</div>
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
var iid = "<?php echo $hotel_info['hotel_parity']['inter_id'];?>";
var hid = "<?php echo $hotel_info['hotel_parity']['hotel_id'];?>";
var url = "<?php echo site_url('price/paritys/hotel_edit_ajax');?>";
window.onload=function() {
	$('#alert_cancel').click(function(){
		$('.tanchu').hide();
	});
}
function save_hotel(){
	var tid = $('#tid').val();
	var thn = $('#thn').val();
	$.get(url+'?t=1&iid='+iid+'&hid='+hid+'&tid='+tid+'&thn='+thn,function(m){
		if(m=='success'){
			location.reload();
		}else if(m=='false'){
			alert('保存失败，请稍后再试！');
		}else{
			alert(m);
		}
	});
}
function alert_rooms(pid,currid){
	$('input[name=third_room_id]').attr('pid',pid);
	var scrollTo = $('#r'+currid);
	var srtop = 0;
	$('.ff9900').hide();
	$('.tanchu').show();
	if(scrollTo.length>0){
		var srtop = scrollTo.offset().top;
		$('input[name=third_room_id]').attr('checked',false);
		// $('#r'+currid).attr('checked','checked');
		$('#r'+currid).prop("checked","checked").siblings().removeAttr("checked"); 
		$('#c'+currid).show();
	}else{
		var radios = $('input[name=third_room_id]');
		radios.each(function(){
		    if($(this).attr('checked')){
		    	$(this).removeAttr('checked');
		    }
	    });
	}
	var container = $('.scrollbar');
	container.animate({ 
		scrollTop:srtop - container.offset().top + container.scrollTop()
	});
}
function save_room(){
	var tid = $('#tid').val();
	var pid = $('input[name=third_room_id]:checked').attr('pid');
	var tr_id = $('input[name=third_room_id]:checked').val();
	var ot = '';
	if($('input[name=athour]').is(':checked')){
		ot += '&ot='+$('input[name=athour]').val();
	}
	if($('input[name=nop]').is(':checked')){
		tr_id = 0;
	}
	$.get(url+'?t=2&iid='+iid+'&hid='+hid+'&tid='+tid+'&pid='+pid+'&tr_id='+tr_id+ot,function(m){
		if(m=='success'){
			location.reload();
		}else if(m=='false'){
			alert('保存失败，请稍后再试！');
		}else{
			alert(m);
		}
	});
}
</script>
</body>
</html>