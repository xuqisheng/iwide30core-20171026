<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
	<meta name="Maker" content="Taoja" tel="13544425200">
	<meta name="format-detection" content="telephone=no" />
	<script src="<?php echo base_url('public/price/default/scripts/jquery.js');?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('public/price/default/styles/ftao.css');?>" type="text/css">
	<title>智能调价</title>
	<style>
		body {
			margin: 0px;
			padding: 0px;
			font-size: 14px;
			font-family: 微软雅黑;
		}
		
		.w100 {
			width: 100%;
		}
		
		img {
			margin-top: 20px;
		}
		
		.wz {
			margin-top: 20px;
			text-align: center;
		}
		
		[line] {
			width: 100%;
			background-color: white;
			margin-bottom: 10px;
		}
		
		.btn {
			margin-right: 10px;
			min-width: 120px;
			border: 1px solid #ff9900;
			padding: 2px;
			text-align: center;
		}
		
		.title {
			font-size: 16px;
		}
		
		.ff9900 {
			color: #ff9900;
		}
		
		div {
			line-height: 1.5;
		}
		
		.red_squ {
			width: 10px;
			height: 10px;
			background-color: red;
		}
		
		[fontsize="16"] {
			font-size: 16px;
		}
		[fontsize="18"]{
			font-size: 18px;
		}
		[fontsize="12"]{
			font-size: 12px;
		}
		.neirong{
			margin: 0px 10px 0px 30px;
			padding: 10px 0px;
			border-bottom: 1px #d7e0f1 dashed;
		}
		.xiecheng{
			margin-left:4px;color:#ff0000;
		}
		.btn1{
			    margin: 10px 0px;
    padding: 10px;
    background-color: #ff9900;
    color: white;
    width: 60%;
    border-radius: 10px;
		}
		.btn2{
			    margin: 10px 0px;
    padding: 10px;
    background-color: #d7d7d7;
    color: white;
    width: 60%;
    border-radius: 10px;
		}
	</style>
</head>

<body>
	<flex line between nowarp>
		<ib style="padding:10px;">
			<div class="title ff9900">当前酒店：<?php echo $hotel_name;?></div>
			<div>检测时间：<?php echo $uptime;?></div>
			<div>共<?php echo $room_num;?>个房型，<?php echo $price_code_num;?>个价格</div>
		</ib>
		<ib>
			<div class="btn ff9900" onclick="window.open('<?php echo $ctrip_url;?>');">点击查看携程价格</a></div>
		</ib>
	</flex>
	<flex line>
		<div style="width:100%;border-bottom:1px solid #d7e0f1">
			<div style="margin:10px;">
				<ib class="red_squ"></ib>
				<!-- <ib fontsize="16" style="margin-left:5px;"><?php if(!empty($configs['configs']['conf_type'])){if($configs['configs']['conf_type']==1){ echo '统一配置';}elseif($configs['configs']['conf_type']==2){ echo '分别配置';}}?></ib> -->
				<ib fontsize="16" style="margin-left:5px;">房型列表</ib>
			</div>
		</div>
		<div style="width:100%;font-size:14px;">
		<?php if(!empty($rooms)){foreach($rooms as $k=>$room){?>
			<?php foreach($room as $kc=>$conf){?>
			<div class="neirong">
				<div>
					<ib fontsize="18" style="color:#555555;"><?php echo $conf['iwide_name'];?></ib>
					<ib style="color:#555555;"><?php echo !empty($conf['ibreakfast'])?$conf['price_name'].'-'.$conf['ibreakfast']:$conf['price_name'];?></ib>
				</div>
				<div>
					<ib>携程：<?php echo $conf['ctrip_name'].'-'.$conf['bed'].'-'.$conf['breakfast'];?></ib>
				</div>
				<div>
					<ib style="color:#20bdaa;">微信价：¥<?php echo $conf['iwide_price'];?></ib> <ib>携程价：¥<?php echo $conf['ctrip_price'];?></ib> <ib class="<?php if($conf['chajia']>0){echo 'xiecheng';}?>">差价：<?php echo $conf['chajia']>0?'+'.$conf['chajia'].'↑':$conf['chajia'];?></ib> <ib style="color:#20bdaa;">调整后价格：¥<?php echo $conf['iwide_price_change'];?></ib>
				</div>
				<div>
					<ib fontsize="12">调价规则：</ib>
					<!--<ib><span class="ff9900">*</span></ib--><ib fontsize="12"><?php if(!empty($conf['confs_list'])){if($conf['confs_list']['compare_type']==1){ echo '微信价比携程价低'.$conf['confs_list']['low_section'].'元';}elseif($conf['confs_list']['compare_type']==2){ echo '微信价是携程价的'.$conf['confs_list']['low_section'].'%';}}?>
					</ib>
					<!--<ib><span class="ff9900">*</span></ib>--><ib fontsize="12">，同时<?php if(!empty($conf['confs_list'])){if($conf['confs_list']['range_type']==1){ echo '微信价固定最低'.$conf['confs_list']['price_section']['min'].'元，最高'.$conf['confs_list']['price_section']['max'].'元';}elseif($conf['confs_list']['range_type']==2) { echo '不低于微信价'.$conf['confs_list']['price_section']['min'].'%，不高于微信价'.$conf['confs_list']['price_section']['max'].'%';}}?>
					</ib>
				</div>
			</div>
			<?php }?>
		<?php }}?>
		</div>
	</flex>
	<flex line>
		<!-- <div style="width:100%;border-bottom:1px solid #d7e0f1">
			<div style="margin:10px;">
				<ib class="red_squ"></ib>
				<ib fontsize="16" style="margin-left:5px;">执行日期</ib>
			</div>
		</div>
		<div style="width:100%;font-size:16px;">
			<div class="neirong">
				<ib><?php echo !empty($configs['configs'])?$configs['configs']['exec_date'][0]:'';?> ~ <?php echo !empty($configs['configs'])?$configs['configs']['exec_date'][1]:'';?></ib>
			</div>
		</div> -->
		<div style="width:100%;font-size:16px;">
			<div class="neirong">
				<ib>请于30分钟内修改，过期将不能修改</ib>
			</div>
		</div>
	</flex>
	<?php if(empty($adjust)){ ?>
	<flex line>
		<div style="width:100%;text-align:center;">
			<ib class="btn1" onclick="do_handle();">确认调整</ib>
		</div>
		<div style="width:100%;text-align:center;">
			<ib class="btn2" onclick="no_handle();">暂不处理</ib>
		</div>
	</flex>
	<?php }?>
</body>
<script type="text/javascript">
	function no_handle(){
		if(confirm('您确定要取消此次价格调整吗？')){
			window.location.href='<?php echo site_url("price/smarts/confirm?id=".$inter_id."&hotel_id=".$hotel_id."&batch=".$batch."&date=".$date."&h=cancel");?>';
		}
	}
	function do_handle(){
		if(confirm('您确定要调整此次价格吗？')){
			window.location.href='<?php echo site_url("price/smarts/confirm?id=".$inter_id."&hotel_id=".$hotel_id."&batch=".$batch."&date=".$date."&h=ok");?>';
		}
	}
</script>
</html>
