<?php include 'header.php';?>
<body>
<div class="head border_bottom clearfix">
	<span class="log_out"><a href="<?php echo site_url('price/paritys/logout');?>">退出账号</a></span>
	<img class="head_port" src="<?php echo $userinfo['head_pic'];?>">
	<div class="di_in_block user_name">您好！<?php echo $userinfo['nickname'];?></div>
</div>
<div class="today_l m_t_4 center">今日概览</div>
<div class="long_time">最后更新时间为：<?php echo $uptime;?></div>
<div class="parity">
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
</div>
<div class="bottom_box"><a class="more_holet" href="<?php echo site_url('price/paritys/parity_list?inter_id='.$userinfo['inter_id']);?>"><?php if($ismore){ echo '更多酒店';}?></a></div>
<script>
window.onload=function(){
	//s5 504;
	if($(document).height()==418){

	};
}
</script>
</body>
</html>