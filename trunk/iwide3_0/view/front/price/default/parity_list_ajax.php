<?php if(!empty($lists)){foreach ($lists as $k => $list) {?>
<div class="par_con" onclick="window.location.href='<?php echo site_url('price/paritys/search_result?inter_id='.$list['inter_id'].'&hotel_id='.$list['hotel_id']);?>'">
	<div class="clearfix con_title">
		<a class="f_right right_angle" href="<?php echo site_url('price/paritys/search_result?inter_id='.$list['inter_id'].'&hotel_id='.$list['hotel_id']);?>">查看详情</a>
		<div><?php echo $list['hotel_name'];?></div>
	</div>
	<div class="con_number color_<?php if($list['avg_diffprice']>=0){ echo 'fe8';}else{ echo '20b';}?> clearfix">
		<div class="f_right m_t_1 m_r_8"><?php echo $list['down_rate'];?>%</div>
		<div class="con_txt"><div class="di_in_block number"><?php echo '¥'.$list['avg_diffprice'];?></div><i class="iconfont f_s_22"><?php if($list['avg_diffprice']>=0){ echo '&#xe680;';}else{ echo '&#xe681;';}?></i></div>
	</div>
	<div class="clearfix three">
		<div class="f_right m_r_12">倒挂率</div>
		<div class="threess ">比<?php echo $list['third_type'];?></div>
	</div>
</div>
<?php }}?>