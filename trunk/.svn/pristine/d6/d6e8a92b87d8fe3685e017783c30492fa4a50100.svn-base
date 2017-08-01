<?php include 'header.php';?>
<body>
<div class="head_sea">
<form id="sform" action="<?php echo site_url('price/paritys/search_result');?>" method="get">
	<div class="search_t"><input class="sea_input" name="wd" value="<?php echo $wd;?>" type="text" placeholder="请输入酒店名/城市"><i class="iconfont f_right search_btn" onclick="document.getElementById('sform').submit();">&#x3434;</i></div>
</form>
</div>
<div class="today_l m_b_4 today_number">
	<span class="f_right">酒店数量：<?php echo $hotel_count;?></span>
	<span><?php echo $hotel_city;?></span>
	<span><?php echo date('m月d日');?>(<?php $weekarray=array("日","一","二","三","四","五","六");
echo '周'.$weekarray[date('w')];?>)</span>
</div>
<div class="container" id="search_list">
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
</div>
<script>
window.onload=function(){
	//s5 504;
	$(".ratio_list >div:last-child").removeClass('border_bottom');
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
					$('.container').append(str);//触发加载
					$.get(
						url,
						{p:page},
						function(m){
							$('.loadgimg').remove();//加载完成
							$('#search_list').append(m);
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