<title>琅琊榜</title>
</head>
<style>
.fixed_header .webkitbox > *{padding:3% 0;}
.user_list .item:first-child{ background:#f99e12; color:#fff !important;}
</style>
<body>
<div class="fixed_header">
	<div class="webkitbox bg_d8">
    	<div class="iscur" range="day" typ="amount">日收益</div>
    	<div range="day" typ="fans">日粉丝</div>
    	<div range="month" typ="amount">月收益</div>
    	<div range="month" typ="fans">月粉丝</div>
    	<div range="all" typ="amount">总收益</div>
    	<div range="all" typ="fans">总粉丝</div>
    </div>
</div>

<div class="user_list bg_fff" style="padding-top:7.5%">
	
</div>

</body>
</html>
<!-- 
<div class="headr">
    <div><a<?php if($this->input->get('c') == 'day' && $this->uri->segment(3) == 'ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/ranking')?>?id=<?php echo $inter_id?>&c=day"<?php endif;?>>日收益</a></div>
    <div><a<?php if($this->input->get('c') == 'day' && $this->uri->segment(3) == 'fans_ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/fans_ranking')?>?id=<?php echo $inter_id?>&c=day"<?php endif;?>>日粉丝</a></div>
    <div><a<?php if($this->input->get('c') == 'month' && $this->uri->segment(3) == 'ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/ranking')?>?id=<?php echo $inter_id?>&c=month"<?php endif;?>>月收益</a></div>
    <div><a<?php if($this->input->get('c') == 'month' && $this->uri->segment(3) == 'fans_ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/fans_ranking')?>?id=<?php echo $inter_id?>&c=month"<?php endif;?>>月粉丝</a></div>
    <div><a<?php if((!$this->input->get('c') || $this->input->get('c') == 'all') && $this->uri->segment(3) == 'ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/ranking');?>?id=<?php echo $inter_id?>&c=all"<?php endif;?>>总收益</a></div>
    <div><a<?php if((!$this->input->get('c') || $this->input->get('c') == 'all') && $this->uri->segment(3) == 'fans_ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/fans_ranking')?>?id=<?php echo $inter_id?>&c=all"<?php endif;?>>总粉丝</a></div>
</div> -->

<script type="text/javascript">
	$(document).ready(function() {
		var datas  = {};
		var myInfo = {};
		$('.fixed_header .webkitbox div').click(function(){
			$(this).addClass('iscur').siblings().removeClass('iscur');
			var range = $(this).attr('range');
			var typ   = $(this).attr('typ');
			getRanking(typ,range);
		});
		getRanking('amount','day');
		$("img.lazy").lazyload({effect: "fadeIn"});
		function generate(datas,myInfo,typ,range){
			var str = '';
			if(myInfo != null){
		    	str = '<a href="#" class="item">';
		  		str += '<div class="user_img"><img class="lazy" src="<?php echo base_url('public/distribute/default/images/header.jpg')?>" data-original="' + myInfo.headimgurl + '"/></div>';
		  		str += '<div class="rank">第<tt>' + myInfo.rank + '</tt>名</div>';
		  		str += '<div class="h1 txtclip">' + myInfo.name + ' <span class="h3" style="display:none">' + myInfo.hotel_name + '</span></div><div>';
				if (range=='day') str+='今';
				if (range=='month') str+='本';				
				str += $("div[typ="+typ+"][range="+range+"]").html(); 
				if(myInfo.fans_count !== undefined){
					if(myInfo.fans_count === null){
						str += '：'+0 + '</div>';
					}else{
	  					str += '：'+myInfo.fans_count + '</div>';
					}
				}else{
					if(myInfo.total_amount === null){
			  			str += '：￥0.00</div>';
					}else{
			  			str += '：￥' + myInfo.total_amount + '</div>';
					}
				}
		  		str += '<div class="txtclip">' + myInfo.hotel_name + '</div>';
		  		str += '</a>';
			}
	  		for(var i = 0; i < datas.length; i++){
	  			str += '<a href="#" class="item">';
	  			str += '<div class="user_img"><img class="lazy" src="<?php echo base_url('public/distribute/default/images/header.jpg')?>" data-original="' + datas[i].headimgurl + '"/></div>';
	  			str += '<div class="rank">第<tt>' + datas[i].rank + '</tt>名</div>';
	  			str += '<div class="h1 txtclip">' + datas[i].name + ' <span class="h3" style="display:none">>' + datas[i].hotel_name + '</span></div><div>';
				if (range=='day') str+='今';
				if (range=='month') str+='本';			
				str += $("div[typ="+typ+"][range="+range+"]").html(); 
				if(datas[i].fans_count === null || datas[i].fans_count != undefined){
					if(datas[i].fans_count === null){
						str += '：0</div>';
					}else{
						if(datas[i].fans_count === null){
							str += '：'+0 + '</div>';
						}else{
		  					str += '：'+datas[i].fans_count + '</div>';
						}
					}
				}else{
			  		if(datas[i].total_amount === null){
				  		str += '：￥0.00';
			  		}else{
					  	str += datas[i].total_amount;
					}
				  	str += '</div>';
				}
	  			str += '<div class="txtclip">' + datas[i].hotel_name + '</div>';
	  			str += '</a>';
	  		}
			$('.user_list').html(str);
		}
		function getRanking(typ,range){
			pageloading('请稍候',0.5);
			$.getJSON('<?php echo site_url('distribute/dis_v1/rank_asy');?>',{'id':'<?php echo $inter_id?>','typ':typ,'range':range},function(res){
				datas = res.ranking;
				if(datas.length > 0){
					myInfo = res.my_rank;
					generate(datas,myInfo,typ,range);
					$("img.lazy").lazyload({effect: "fadeIn"});
				}else{
					$('.user_list').html('<p style="padding:1rem 0; text-align:center; color:#aaa;">暂无排名~</p>');
				}
				$('.page_loading').remove();
			});
		}
	});
</script>
