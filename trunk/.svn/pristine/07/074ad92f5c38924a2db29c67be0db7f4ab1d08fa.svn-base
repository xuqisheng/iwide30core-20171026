<?php include 'header.php';?>
<body>
<div class="head_sea">
<form id="sform" action="<?php echo site_url('price/paritys/parity_list');?>" method="get">
	<div class="search_t"><input class="sea_input" name="wd" value="<?php echo $wd;?>" type="text" placeholder="请输入酒店名/城市"><i class="iconfont f_right search_btn" onclick="document.getElementById('sform').submit();">&#x3434;</i></div>
</form>
</div>
<div class="today_l m_b_4 today_number">
	<span class="f_right">酒店数量：<?php echo $hotel_count;?></span>
	<span>全部</span>
	<span><?php echo date('m月d日');?>(<?php $weekarray=array("日","一","二","三","四","五","六");
echo '周'.$weekarray[date('w')];?>)</span>
</div>
<div class="parity" id="parity_list">
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
<script>
window.onload=function(){
	var str='<div class="loadgimg" style="text-align:center;"><img style="width:20px;height:20px;vertical-align:top;" src="<?php echo base_url('public/price/default/images/loads.jpg');?>"/>加载中...</div>';
	var locked = <?php echo $ismore;?>;
	var url = "<?php echo $url;?>";
	var page = 1;
	var maxpage = <?php echo $maxpage;?>;
	if(locked==1&&maxpage>1){ 
		$(document).scroll(function(e) {
	       	if ($(document).scrollTop() >= $(document).height()-$(window).height()) {  
	        	// 滚动到底端了TODO加载内容 
				if(locked==1){
					locked = 0;
					page = page+1;
					$('.parity').append(str);//触发加载
					$.get(
						url,
						{p:page},
						function(m){
							$('.loadgimg').remove();//加载完成
							$('#parity_list').append(m);
							if(page<maxpage){
								locked = 1;
							}
						}); 
				}
	        } else {  
	        	// 没有滚动到底端TODO 其他处理  
	        }         
	   }); 
   }
}
</script>
</body>
</html>