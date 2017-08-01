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
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/jedate.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/jquery.jedate.min.js"></script>
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

select {
	border: #d7e0f1 solid 1px;
	vertical-align: middle;
	font-size: 16px;
	width: 200px;
	text-align: left;
	padding: 5px;
	outline: none;
	margin: 0px 20px;
}

select option {
	font-size: 14px;
	font-family: 微软雅黑;
	color: #7e8e9f;
}

.jiansuo {
	padding: 5px 20px;
	color: white;
	background-color: #ff9900;
	cursor: pointer;
}

.l_title {
	font-size: 14px;
}

.l_info {
	font-size: 12px;
	color: #aeb9c3;
}

.ff503f {
	color: #ff503f;
}

.fenpei {
	padding-bottom: 10px;
	border-bottom: 1px solid #dde5f3;
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
.table2 th{
	padding: 10px 0px;
	text-align: center;
}
.table1 td:nth-child(1) {
	color: #7e8e9f;
}

.table1 td:nth-child(2) {
	color: #4caf50;
}

.table1 td:nth-child(3) {
	color: #ff503f;
}

.table1 td:nth-child(4) {
	color: #ff9900;
	text-decoration: underline;
	cursor: pointer;
}
.add{
	color: #ff9900;
	text-decoration: underline;
	cursor: pointer;
	margin-left: 20px;
}

.btn1 {
	border: 1px solid #dde5f3;
	background-color: white;
	padding: 5px;
	margin: 0px 5px;
	cursor: pointer;
}
.btn2{
	border: 1px solid #dde5f3;
	background-color: white;
	width: 30px;
	height: 30px;
	line-height: 30px;
	text-align: center;
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
.chakan{
    width: 150px;
	background-color: #4caf50;
	text-align: center;
	padding: 10px 0px;
	margin: 10px;
	color: white;
	border-radius: 10px;
	cursor: pointer;
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
<div class="content-wrapper" style="min-width:850px;">
<div class="banner bg_fff p_0_20">
    <?php echo $breadcrumb_html; ?>
</div>
<section class="content" id="section_content">
	<flex class="each_line" style="margin-top:0px;">
		<ib class="VA-M head">
			<colorline class="blue_line"></colorline>
			<div>选择酒店</div>
		</ib>
		<ib class="VA-M">
		<form action="<?php echo site_url('price/paritys/smart_price');?>" method="get" id="jsform">
			<ib>酒店名称</ib>
			<select name="hotel_id">
			<option value='0'>全部酒店</option>
			<?php if(!empty($hotels)){foreach($hotels as $h=>$hotel){?>
				<option value="<?php echo $hotel['hotel_id'];?>" <?php if($hotel_id==$hotel['hotel_id']){ echo 'selected';}?>><?php echo $hotel['name'];?></option>
			<?php }}?>
			</select>
			<ib class="jiansuo" id="jiansuo">检索</ib>
			</form>
			<div class="ff9900" style="margin-top:15px;">当前酒店：<span id="hotel_name"><?php echo $hotel_name;?></span></div>
			<div style="margin-top:5px;">最近更新时间：<?php echo $uptime;?></div>
		</ib>
	</flex>
	<form action="<?php echo site_url('price/paritys/smart_price_post');?>" method="post" id="pzform">
	<input type="hidden" name="csrf_token" value="<?php echo $csrf_value;?>">
	<input type="hidden" name="hotel_id" value="<?php echo $hotel_id;?>">
	<flex class="each_line">
		<ib class="VA-M head">
			<colorline class="red_line"></colorline>
			<div>设置调价</div>
		</ib>
		<ib class="VA-M">
			<flex class="mt-20">
				<div style="min-width:100px;">
					<label style="font-weight:normal;"><input type="radio" name="conf_type" value="1" <?php if(!empty($configs['conf_type'])){if($configs['conf_type']==1){echo 'checked';}}else{ echo 'checked';}?>>
					<ib>统一配置</ib></label>
				</div>
				<flex style="flex-wrap:wrap;">
					<div class="ml-20" style="background-color: #f0f3f6;">
						<flex class="m-40">
							<ib class="w100 center">
								<div class="l_title">价格范围设置</div>
								<div class="l_info">设置此价格在微信售卖最低价和最高价</div>
							</ib>
							<ib class="ml-20 white">
								<flex class="pad-20" width450>
									<label style="font-weight:normal;"><input type="radio" name="range_type" value="1" <?php if(!empty($configs_list['range_type'])){if($configs_list['range_type']==1){echo 'checked';}}else{ echo 'checked';}?>>
									<ib>固定最低</ib></label>
									<input type="number" style="width:50px;margin:0px 10px;" name="wx_fix[min]" value="<?php if(!empty($configs_list['range_type'])&&$configs_list['range_type']==1){echo $configs_list['price_section']['min'];}?>">
									<ib>元,最高</ib>
									<input type="number" style="width:50px;margin:0px 10px;" name="wx_fix[max]" value="<?php if(!empty($configs_list['range_type'])&&$configs_list['range_type']==1){echo $configs_list['price_section']['max'];}?>">
									<ib>元。</ib>
								</flex>
								<flex class="pad-20" width450>
									<label style="font-weight:normal;"><input type="radio" name="range_type" value="2" <?php if(!empty($configs_list['range_type'])&&$configs_list['range_type']==2){echo 'checked';}?>>
									<ib>不低于微信价</ib></label>
									<input type="number" style="width:50px;margin:0px 10px;" name="wx_per[min]" value="<?php if(!empty($configs_list['range_type'])&&$configs_list['range_type']==2){echo $configs_list['price_section']['min'];}?>">
									<ib>%，不高于微信价</ib>
									<input type="number" style="width:50px;margin:0px 10px;" name="wx_per[max]" value="<?php if(!empty($configs_list['range_type'])&&$configs_list['range_type']==2){echo $configs_list['price_section']['max'];}?>">
									<ib>%。</ib>
								</flex>
							</ib>
						</flex>
						<flex class="m-40">
							<ib class="w100 center">
								<div class="l_title">比携程价低</div>
								<div class="l_info">在“价格区间范围内”，会随着携程的价格变化而变化</div>
							</ib>
							<ib class="ml-20 white">
								<flex class="pad-20" width450>
									<label style="font-weight:normal;"><input type="radio" name="compare_type" value="1" <?php if(!empty($configs_list['compare_type'])){if($configs_list['compare_type']==1){echo 'checked';}}else{ echo 'checked';}?>>
									<ib>比携程价低</ib></label>
									<input type="number" style="width:50px;margin:0px 10px;" name="ch_fix" value="<?php if(!empty($configs_list['compare_type'])&&$configs_list['compare_type']==1){echo $configs_list['low_section'];}?>">
									<!-- <ib>元,最高</ib>
									<input type="text" style="width:50px;margin:0px 10px;" name="ch_fix[max]" value=""> -->
									<ib>元。</ib>
								</flex>
								<flex class="pad-20" width450>
									<label style="font-weight:normal;"><input type="radio" name="compare_type" value="2" <?php if(!empty($configs_list['compare_type'])&&$configs_list['compare_type']==2){echo 'checked';}?>>
									<ib>是携程价的</ib></label>
									<input type="number" style="width:50px;margin:0px 10px;" name="ch_per" value="<?php if(!empty($configs_list['compare_type'])&&$configs_list['compare_type']==2){echo $configs_list['low_section'];}?>">
									<!-- <ib>元,最高</ib>
									<input type="text" style="width:50px;margin:0px 10px;" name="ch_per[max]" value=""> -->
									<ib>%。</ib>
								</flex>
							</ib>
						</flex>
						<flex class="m-40">
							<ib class="w100 center">
							<div class="l_title">选择参与调价的价格代码</div>
							</ib>
							<ib class="ml-20 white" style="width:450px;">
							<?php foreach ($price_codes as $k => $val) {?>
								<ib style="padding: 5px;">
									<label style="font-weight:normal;">
									<input type="checkbox" name="price_codes[]" value="<?php echo $val['price_code'];?>" <?php if(in_array($val['price_code'],$price_codes_check)){ echo 'checked';}?>> <?php echo $val['price_name'];?>
									</label>
								</ib>
							<?php }?>
							</ib>
						</flex>
					</div>
				</flex>
			</flex>
			<?php if($hotel_id>0){?>
			<flex class="mt-20" id="rconf">
				<div style="min-width:100px;">
					<label style="font-weight:normal;"><input type="radio" name="conf_type" value="2" <?php if(!empty($configs['conf_type'])){if($configs['conf_type']==2){echo 'checked';}}?>>
					<ib>分别配置</ib></label>
				</div>
				<?php $n=1;if(!empty($rooms)){foreach($rooms as $kr=>$room){?>
				<flex style="flex-wrap:wrap;<?php if($n>1){echo 'display:none;';}else{ echo 'display:block;';}?>" id="room<?php echo $n;?>" rid="<?php echo $kr;?>">
					<div class="ml-20" style="background-color: #f0f3f6;">
						<div class="m-40 fenpei" style="padding-top:10px;color:#ff9900;">
							<div><?php echo current(current($room))['iwide_name'];?></div>
						</div>
						<?php if(!empty($room['room'])){foreach($room['room'] as $k => $r){?>
						<input type="hidden" name="pid<?php echo $k;?>" value="<?php echo $k;?>">
						<div class="m-40 fenpei">
							<div><?php echo $r['price_name'];?> <span style="color:#7ac37d">微信价:<?php echo $r['iwide_price'];?></span> <span class="ff503f">携程价:<?php echo $r['ctrip_prices'];?></span></div>
						</div>
						<flex class="m-40">
							<ib class="w100 center">
								<div class="l_title">价格范围设置</div>
								<div class="l_info">设置此价格在微信售卖最低价和最高价</div>
							</ib>
							<ib class="ml-20 white">
								<flex class="pad-20" width450>
									<label style="font-weight:normal;"><input type="radio" name="range_type<?php echo $kr.$k;?>" value="1" <?php if(!empty($r['range_type'])){if($r['range_type']==1){echo 'checked';}}else{ echo 'checked';}?>>
									<ib>固定最低</ib></label>
									<input type="number" style="width:50px;margin:0px 10px;" name="wx_fix<?php echo $kr.$k;?>[min]" value="<?php if(!empty($r['range_type'])&&$r['range_type']==1){echo $r['price_section']['min'];}?>">
									<ib>元,最高</ib>
									<input type="number" style="width:50px;margin:0px 10px;" name="wx_fix<?php echo $kr.$k;?>[max]" value="<?php if(!empty($r['range_type'])&&$r['range_type']==1){echo $r['price_section']['max'];}?>">
									<ib>元。</ib>
								</flex>
								<flex class="pad-20" width450>
									<label style="font-weight:normal;"><input type="radio" name="range_type<?php echo $kr.$k;?>" value="2" <?php if(!empty($r['range_type'])&&$r['range_type']==2){echo 'checked';}?>>
									<ib>不低于微信价</ib></label>
									<input type="number" style="width:50px;margin:0px 10px;" name="wx_per<?php echo $kr.$k;?>[min]" value="<?php if(!empty($r['range_type'])&&$r['range_type']==2){echo $r['price_section']['min'];}?>">
									<ib>%，不高于微信价</ib>
									<input type="number" style="width:50px;margin:0px 10px;" name="wx_per<?php echo $kr.$k;?>[max]" value="<?php if(!empty($r['range_type'])&&$r['range_type']==2){echo $r['price_section']['max'];}?>">
									<ib>%。</ib>
								</flex>
							</ib>
						</flex>
						<flex class="m-40">
							<ib class="w100 center">
								<div class="l_title">比携程价低</div>
								<div class="l_info">在“价格区间范围内”，会随着携程的价格变化而变化</div>
							</ib>
							<ib class="ml-20 white">
								<flex class="pad-20" width450>
									<label style="font-weight:normal;"><input type="radio" name="compare_type<?php echo $kr.$k;?>" value="1" <?php if(!empty($r['compare_type'])){if($r['compare_type']==1){echo 'checked';}}else{ echo 'checked';}?>>
									<ib>比携程价低</ib></label>
									<!-- <input type="text" style="width:50px;margin:0px 10px;">
									<ib>元,最高</ib> -->
									<input type="number" style="width:50px;margin:0px 10px;" name="ch_fix<?php echo $kr.$k;?>" value="<?php if(!empty($r['compare_type'])&&$r['compare_type']==1){echo $r['low_section'];}?>">
									<ib>元。</ib>
								</flex>
								<flex class="pad-20" width450>
									<label style="font-weight:normal;"><input type="radio" name="compare_type<?php echo $kr.$k;?>" value="2" <?php if(!empty($r['compare_type'])){if($r['compare_type']==2){echo 'checked';}}else{ echo 'checked';}?>>
									<ib>是携程价的</ib></label>
									<!-- <input type="text" style="width:50px;margin:0px 10px;">
									<ib>元,最高</ib>-->				
									<input type="number" style="width:50px;margin:0px 10px;" name="ch_per<?php echo $kr.$k;?>" value="<?php if(!empty($r['compare_type'])&&$r['compare_type']==2){echo $r['low_section'];}?>">
									<ib>%。</ib>
								</flex>
							</ib>
						</flex>
						<?php }}?>
						<div class="m-40">
							<table class="table1" style="width:570px;">
								<tr style="display:none">
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
								<?php if(!empty($room['no_rooms'])){foreach($room['no_rooms'] as $kn=>$no_room){?>
								<tr>
									<td><?php echo $no_room['price_name'];?></td>
									<td>微信价:<?php echo $no_room['iwide_price'];?></td>
									<td>无匹配</td>
									<td onclick="window.open('<?php echo site_url('price/paritys/hotel_edit?inter_id='.$this->inter_id.'&hotel_id='.$hotel_id);?>');">去匹配房型</td>
								</tr>
								<?php }}?>
							</table>
							<div style="text-align: right;margin-top:10px;">
								<ib class="baocun" style="width:60px;" onclick="save_room(<?php echo $n;?>)">保存</ib>
								<ib class="btn1" onclick="last_room(<?php echo $n;?>)" <?php if($n==1){echo 'disabled';}?>>上一个</ib>
								<ib class="btn1" onclick="next_room(<?php echo $n;?>)" <?php if($n==$count_room){echo 'disabled';}?>>下一个</ib>
							</div>
						</div>
					</div>
				</flex>
				<?php $n++;}}?>
			</flex>
			<?php }?>
		</ib>
	</flex>
	<flex class="each_line">
		<ib class="VA-M head">
			<colorline class="blue_line"></colorline>
			<div>调整方式</div>
		</ib>
		<ib class="VA-M">
			<div>
				<ib style="min-width:100px;">执行日期</ib>
				<ib style="margin-left:20px;">
					<input class="datainp wicon" id="date02" type="text" placeholder="YYYY-MM-DD hh:mm" value="<?php if(!empty($configs['exec_date'][0])){echo $configs['exec_date'][0];}?>" readonly="" style="width: 130px;" name="exec_date[]">
					<ib>-</ib>
					<input class="datainp wicon" id="date03" type="text" placeholder="YYYY-MM-DD hh:mm" value="<?php if(!empty($configs['exec_date'][1])){echo $configs['exec_date'][1];}?>" readonly="" style="width: 130px;" name="exec_date[]">
				</ib>
			</div>
			<div style="margin-top:20px;">
				<ib style="min-width:100px;">有效时长</ib>
				<ib style="margin-left:20px;">
					<!-- <input value="<?php if(!empty($configs['effect_time'])){echo $configs['effect_time'];}?>" style="width: 130px;" name="effect_time">分钟 -->
					30分钟内确认调价有效
				</ib>
			</div>
			<div style="margin-top:20px;">
				<ib style="min-width:100px;">调整方式</ib>
				<ib style="margin-left:20px;">
					<!-- <flex>
						<label style="font-weight:normal;"><input type="radio" name="adjust_type" value="2" <?php if(!empty($configs['adjust_type'])){if($configs['adjust_type']==2){echo 'checked';}}?>>
						<ib>发现倒挂后立即调整，无需确认</ib></label>
					</flex> -->
					<flex style="margin-top:5px;">
						<label style="font-weight:normal;"><!-- <input type="radio" name="adjust_type" value="1" checked="checked"> -->
						<ib>发现倒挂后发送模版消息给工作人员，确认后调整</ib></label>
						<a href="<?php echo site_url('hotel/tmmsg/index');?>" target="_blank"><ib class="add">去添加模板消息</ib></a>
					</flex>
				</ib>
			</div>
		</ib>
	</flex>
	<flex class="each_line" style="justify-content:center;">
		<ib class="baocun" id="baocun">保存</ib>
		<ib class="chakan" onclick="showprice();">查看所有调价</ib>
	</flex>
	</form>
	<div id="logs">
	<!-- ajax for get-->
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
<script>
	var err = "<?php echo $err;?>";
	var hid = "<?php echo $hotel_id;?>";
	var url_ajax_conf = "<?php echo site_url('price/paritys/save_room_config_ajax');?>";
	var url_ajax_log = "<?php echo site_url('price/paritys/get_operate_logs_ajax');?>";
	var count_rooms = "<?php echo $count_room;?>";
	var csrf_token = "<?php echo $csrf_value;?>";
	if(err=='1'){
		alert('保存失败');
	}
	$("#date02").jeDate({
	    isinitVal:true,
	    festival:true,
	    ishmsVal:false,
	    format:"YYYY-MM-DD hh:mm",
	    zIndex:3000,
	})
	$("#date03").jeDate({
	    isinitVal:true,
	    festival:true,
	    ishmsVal:false,
	    format:"YYYY-MM-DD hh:mm",
	    zIndex:3000,
	})
	$('#jiansuo').click(function(){
		$('#jsform').submit();
	});
	$('#baocun').click(function(){
		$('#pzform').submit();
	});
	function save_room(id){
		var obj = $('#room'+id);
		var rid = obj.attr('rid');
		var data = {};
		$('#room'+id+' input').each(function(i){
			var name = $(this).attr('name');
			var value = $(this).val();
			if($(this).attr('type')!='radio'||$(this).is(':checked')){
				var d = {};
				d.name = name;
				d.value = value;
				data[i] = d;
			}
		});
		var t;
		var s=false;
		for(t in data){
			s = true;
		}
		if(s===false){
			alert('没有可保存的配置');
			return false;
		}
		if(hid>0){
			$.post(url_ajax_conf,{
				hid:hid,
				rid:rid,
				data:data,
				csrf_token:csrf_token,
			},function(m){
				if(m==1){
					alert('保存成功');
				}else if(m=='null'){
					alert('保存数据填写不全');
				}else{
					alert('保存失败，请稍后再试');
				}
			});
		}
	}
	function last_room(id){
		if(id>1){
			$('#room'+id).hide();
			id = parseInt(id)-1;
			$('#room'+id).show();
		}
	}
	function next_room(id){
		if(id<count_rooms){
			$('#room'+id).hide();
			id = parseInt(id)+1;
			$('#room'+id).show();
		}
	}
	function showprice(){
		if(hid>0){
			window.location.href = '<?php echo site_url("price/paritys/result_price?hotel_id=".$hotel_id);?>';
		}else{
			alert('请选择具体的某个酒店');
			return false;
		}
	}
	function get_operate_logs(){
		$.get(url_ajax_log,function(m){
			$('#logs').html(m);
			get_logs_page();
		});
	}
	function get_logs_page(){
		$(document).ready(function(){
			$('.ajax_fpage').bind('click',function(e){
				if ( e && e.preventDefault ) {
				    e.preventDefault(); 
				}else {
		        	window.event.returnValue = false;  
				}
		    	var url = $(this).attr('href');
		        $.get(url,function(res){
		            $('#logs').html(res);
		            get_logs_page();
		        });
		    });
    	});
	}
	get_operate_logs();
</script>
</body>
</html>
