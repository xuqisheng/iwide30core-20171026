<?php if(!empty($lists)){foreach ($lists as $k => $list) {?>
<div class="result_con">
	<div class="hotel_name border_bottom">
		<p class="ratio f_right">
			<span>倒挂率</span>
			<span class="color_fe8"><?php echo !empty($list[0]['hotel_id'])?$down_rates[$list[0]['hotel_id']]:'';?>%</span>
		</p>
		<span class="color_5f6"><?php echo $k;?></span>
	</div>
	<div class="ratio_list p_0_4">
		<div class="list_con lis_titl color_888 d_flex border_bottom">
			<div>房型</div>
			<div>微信价</div>
			<div><?php echo $third_type;?>价</div>
			<div>差价</div>
		</div>
		<?php if(!empty($list)){foreach ($list as $kl => $vl) {?>
		<div class="list_con d_flex border_bottom">
			<div class="<?php if(empty($vl['ctrip_price'])){ echo 'color_888';}elseif ($vl['chajia_rev']>0){ echo 'color_fe8';}else{ echo 'color_20b';}?>"><?php echo $vl['iwide_name'].'_'.$vl['iwide_price_name'].$vl['ibreakfast'];?></div>
				<div class="<?php if(empty($vl['ctrip_price'])){ echo 'color_888';}elseif ($vl['chajia_rev']>0){ echo 'color_fe8';}else{ echo 'color_20b';}?>"><?php echo '¥'.$vl['iwide_price'];?></div>
				<div class="<?php if(empty($vl['ctrip_price'])){ echo 'color_888';}elseif ($vl['chajia_rev']>0){ echo 'color_fe8';}else{ echo 'color_20b';}?>"><?php if(!empty($vl['ctrip_price'])){echo '¥'.$vl['ctrip_price'];}else{ echo '未匹配到';}?></div>
				<div class="<?php if(empty($vl['ctrip_price'])){ echo 'color_888';}elseif ($vl['chajia_rev']>0){ echo 'color_fe8';}else{ echo 'color_20b';}?>"><?php if(!empty($vl['chajia_rev'])){echo $vl['chajia_rev'];}?></div>
		</div>
		<?php }}else{echo '无房型匹配结果！';}?>
	</div>
</div>
<?php }}?>