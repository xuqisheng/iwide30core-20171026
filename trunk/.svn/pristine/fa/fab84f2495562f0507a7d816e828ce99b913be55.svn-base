<?php include 'header.php'?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ggmZIrqw5hOjnXwT7ypK0aIoZXrn4yfS"></script>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<?php echo referurl('js','search.js',2,$media_path) ?>
<style>
.checkin:after{content:"出行"}
.checkout:after{content:"离店"}
.checkin_time:before{ content:"共"}
.checkin_time:after{ content:"晚"}
.qi:after{ content:"起";}
._tips{display:none}
</style>

<input type="hidden" id="startdate" name="startdate" value='<?php echo date('Y/m/d',strtotime($startdate));?>' />
<input type="hidden" id="enddate" name="enddate" value='<?php echo date('Y/m/d',strtotime($enddate));?>' />
<input type="hidden" id="city" name="city" value="<?php echo $city; ?>" />

<div class="bg_fff bd_bottom headfixed list_style">
    <div class="webkitbox justify arrow" id='checkdate'>
        <span class="iconfont icon">&#x3B; </span>
        <span class="checkin" id='checkin'><?php echo date("m月d日",strtotime($startdate));?></span>
        <span></span>
    </div>
</div>
<!--a class="map" style="display:none">
    <em class="iconfont color_main">&#x25;</em>
    <span class="color_main">地图预览</span>
</a-->

<div class="hotel_list list_style_2 bd_bottom" style="padding-top:35px">
<?php if(!empty($result)){ foreach($result as $r){?>
	<div onclick="go_hotel('<?php echo Hotel_base::inst()->get_url("INDEX",array("h"=>$r->hotel_id,'type'=>$type));?>')" class="webkitbox justify">
        <div class="img"><div class="squareimg"><img class="lazy" src="<?php echo referurl('img','default2.jpg',3,$media_path) ?>"  data-original="<?php echo $r->intro_img?>" /></div></div>
        <div class="info">
            <div class="name"><?php echo $r->name;?></div>
            <div class="h22 color_link txtclip" style="max-width:15em"><?php if(!empty($r->characters)){echo $r->characters;}?></div>
            <div class="address h20 color_888"><?php echo $r->address?></div>
            <div class="h20"><span class="color_main">4.0分</span>/5.0分</div>
        </div>
        <div class="price color_888" style="font-size:10px">
        <?php if(!empty($r->lowest)){?>
            <div class="qi"><span class="color_main">¥</span><span class="color_main h34 arial" id="lowest_p_<?php echo $r->hotel_id;?>"><?php echo $r->lowest;?></span></div>
            <?php }else{?>
            <div id="lowest_p_<?php echo $r->hotel_id;?>">暂无价格</div>
            <?php }?>
            <?php if(!empty($icons_set['coupon_back'])){?><div class="backvote">入住返券</div><?php }?>
        </div>
    </div>
    <?php }?>
    <?php }?>
</div>

<div class="filter_option webkitbox">
	<div id="show_sort_pull"><span>推荐排序</span><em class="iconfont h20">&#x3e;</em></div>
	<div id="filter_result">筛选<em class="iconfont h20">&#x40;</em></div>
</div>

<div class="sort_list_pull ui_pull h26" style="display:none" onClick="toclose()">
	<div class="relative"><ul class="list_style_1 center color_main">
    	<li class="cur" sort_tag='default'>推荐排序</li>
    	<li sort_tag='price_up'>价格由低到高</li>
    	<li sort_tag='good_rate'>酒店好评率</li>
    </ul></div>
</div>

<div class="ui_none"  style="display:none">
    <div>没有搜索到相关结果~</div>
</div>
<div style="padding-top:45px"></div>
</body>
<script>
var setheight=0
//var server_item=['24小时热水','无线上网','吹风机','行李寄存','叫醒服务','接机服务','免费停车','有线上网'];

var isfirst=true;
function go_hotel($url){
	location.href=$url+"&start="+$('#startdate').val()+"&end="+$('#enddate').val();
}
function isnone(){
	if($('.hotel_list *').length<=0)
		$('.ui_none').show();
	else
		$('.ui_none').hide();
}
$(function(){		
	var overmonth = 0;
	var weekNames = [ '日', '一', '二', '三', '四', '五', '六' ];
	var today=new Date();
	var morrow=new Date((today/1000+86400)*1000);
	$('#checkdate').cusCalendar({
		beginTimeElement:'checkin',
		//endTimeElement  :'checkout',
		bTimeValElement :'startdate',
		//eTimeValElement :'enddate',
		preSpDate:<?php echo $pre_sp_date; ?>,
		selectedCallBack:function(data){
			//$('.checkin .week').html(weekNames[data.inDate.getDay()] );
			$('#checkin').html( (data.inDate.getMonth() + 1) + '月' + data.inDate.getDate() + '日');
			
			//$('.checkout .week').html(weekNames[data.outDate.getDay()]);
			$('#checkout').html( (data.outDate.getMonth() + 1) + '月' + data.outDate.getDate() + '日');
			
			$('.checkin_time').html(data.dateSpan);
			get_lowest($('#startdate').val(),$('#enddate').val());
		}
	});
});
function get_lowest(startdate,enddate){
	var hotel_ids='';
	ranges=$('.hotel_list>div');
	$.each(ranges,function(i,n){
		if($(n).attr('tmp')!=undefined){
			hotel_ids+=','+$(n).attr('tmp');
		}
	});
	hotel_ids=hotel_ids.substring(1);
	$.get('<?php echo Hotel_base::inst()->get_url("RETURN_LOWEST_PRICE");?>',{
			s:startdate,
			e:enddate,
			hs:hotel_ids
		},function(data){
			$.each(data,function(i,n){
				$('#lowest_p_'+i).html(n);
			});
		},'json');
}	
</script>
</html>

