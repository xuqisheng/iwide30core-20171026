<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>比价系统-<?php echo $hgname;?></title>
<style type="text/css">
	.hotel_name{
		font-size: 16px;
		padding: 5px;
		padding-bottom: 12px;
		font-weight: bold;
	}
	table{
		font-size: 12px;
	}
.pages{ width:100.5%; padding:10px 0; clear:both;}
.pages a,.pages b{ border:1px solid #5FA623; background:#fff; padding:2px 6px; text-decoration:none}
.pages b,.pages a:hover{ background:#7AB63F; color:#fff;}
</style>
</head>
<body>
	<?php $n=0;foreach ($lists as $kl => $list) {?>
	<div class="hotel_name"><?php echo ++$n.'.'.$kl;?></div>
	<table border="1" cellpadding="5" cellspacing="1" width="80%" style="background-color: #b9d8f3;border-collapse:collapse;">
		<th width="36%">携程</th>
		<th width="6%">价格</th>
		<th width="36%">公众号</th>
		<th width="6%">价格</th>
		<th width="6%">差价</th>
		<tbody>
			<?php
			foreach ($list as $k => $v) {?>
			<tr style="<?php if(isset($v['chajia'])&&$v['chajia']<0){echo 'color:red;';}else{ echo 'COLOR: #0076C8';}?>BACKGROUND-COLOR: #F4FAFF; font-weight: bold;">
			<?php if($k>=1&&$v['ctrip_name']==$list[$k-1]['ctrip_name']&&!empty($v['ctrip_name'])){?>
			<?php }else{?>
				<td <?php if(isset($v['cop'])&&$v['cop']>0){echo 'rowspan="'.$v['cop'].'"';} if(isset($v['ismore'])&&$v['ismore']==1){echo 'style="COLOR: #000;background-color: #b9d8f3;"';}?>><?php echo $v['ctrip_name'];?></td>
				<td style="text-align:right;<?php if(isset($v['ismore'])&&$v['ismore']==1){echo 'COLOR: #000;background-color: #b9d8f3;';}?>" <?php if(isset($v['cop'])&&$v['cop']>0){echo 'rowspan="'.$v['cop'].'"';}?>><?php echo $v['ctrip_price'];?></td>
			<?php }?>
			<?php if(!empty($list[$k+1]['iwide_name'])&&empty($v['iwide_name'])&&$v['ctrip_name']==$list[$k+1]['ctrip_name']){ 
				 }elseif($k>=1&&!empty($list[$k-1]['iwide_name'])&&empty($v['iwide_name'])&&$v['ctrip_name']==$list[$k-1]['ctrip_name']){ 
				 }elseif($k>=1&&empty($list[$k-1]['iwide_name'])&&empty($v['iwide_name'])&&$v['ctrip_name']==$list[$k-1]['ctrip_name']){ 
				 }elseif(empty($v['iwide_name'])){
			?>
				<td <?php if(isset($v['cop'])&&$v['cop']>0){echo 'rowspan="'.$v['cop'].'"';}?>><?php echo $v['iwide_name'],'<span style="color:red;">'.$v['book_status'].'</span>';?></td>
				<td <?php if(isset($v['cop'])&&$v['cop']>0){echo 'rowspan="'.$v['cop'].'"';}?> style="text-align:right;"><?php echo $v['iwide_price'];?></td>
				<td <?php if(isset($v['cop'])&&$v['cop']>0){echo 'rowspan="'.$v['cop'].'"';}?> style="text-align:right;"><?php echo $v['chajia'];?></td>
			<?php }else{?>
				<td><?php echo $v['iwide_name'],'<span style="color:red;">'.$v['book_status'].'</span>';?></td>
				<td style="text-align:right;"><?php echo $v['iwide_price'];?></td>
				<td style="text-align:right;"><?php echo $v['chajia'];?></td>
			<?php }?>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php }?>
	<!-- <div class="pages"><?php //echo $page;?></div> -->
</body>
</html>