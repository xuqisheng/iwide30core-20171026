<?php include 'header.php'?>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('js','load_more.js',1,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<?php echo referurl('css','search_result.css',1,$media_path) ?>
<style>
.checkin .date:after{content:"入住"}
.checkout .date:after{content:"离店"}
.checkin_time:before{ content:"共"}
.checkin_time:after{ content:"晚"}
.ui_price:after{ content:"起";}
</style>

<input type="hidden" id="startdate" name="startdate" value='<?php echo date('Y/m/d',strtotime($startdate));?>' />
<input type="hidden" id="enddate" name="enddate" value='<?php echo date('Y/m/d',strtotime($enddate));?>' />
<div class="bg_fff webkitbox justify pad3 linkblock bd_bottom headfixed" id='checkdate'>
    <span class="checkin" id='checkin'><?php echo date("m月d日",strtotime($startdate));?></span>
    <span class="checkout" id='checkout'><?php echo date("m月d日",strtotime($enddate));?></span>
    <span class="checkin_time color_main"><?php echo round(strtotime($enddate)-strtotime($startdate))/86400;?></span>
</div>
<div class="hotel_list list_style_2 bd_bottom" style="padding-top:37px">
<?php if(!empty($result)){ foreach($result as $r){?>
	<div onclick="go_hotel('<?php echo Hotel_base::inst()->get_url("INDEX",array("h"=>$r->hotel_id));?>')"  class="webkitbox justify">
        <div class="img"><div class="squareimg"><img class="lazy" src="<?php echo referurl('img','default2.jpg',3,$media_path) ?>"  data-original="<?php echo $r->intro_img?>" /></div></div>
        <div class="info">
            <div class="name"><?php echo $r->name;?></div>
            <div class="ever hide"><span class="color_main_gray">12条评论</span></div>
            <div class="sever">
            	<?php if(!empty($r->service)){ foreach ($r->service as $rs) {?> <em class="iconfont"><?php echo $rs['image_url'];?></em><?php }}?>	
                <em>&nbsp;</em>
            </div>
            <div class="address h20 color_888"><?php echo $r->address?></div>
        </div>
        <div class="price color_888" style="font-size:10px">
        <?php if(!empty($r->lowest)){?>
            <div id="lowest_p_<?php echo $r->hotel_id;?>" class="qi">
            	<span class="color_main">¥</span><span class="color_main h36"><?php echo $r->lowest;?></span></div>
            <?php }else{?>
            <div id="lowest_p_<?php echo $r->hotel_id;?>">暂无价格</div>
            <?php }?>
            <?php if(!empty($icons_set['coupon_back'])){?><div class="backvote">入住返券</div><?php }?>
        </div>
    </div>
    <?php }?>
    <?php }?>

</div>

	<div class="ui_none"  style="display:none">
    	<div>没有搜索到相关结果~<span class=" color_main" onClick="history.back(-1);">重新搜索</span></div>
    </div>
</body>
<script>
var setheight=0
//var server_item=['24小时热水','无线上网','吹风机','行李寄存','叫醒服务','接机服务','免费停车','有线上网'];

var isfirst=true;
function go_hotel($url){
	location.href=$url+"&start="+$('#startdate').val()+"&end="+$('#enddate').val();
}
function isnone(){
	if($('.hotel_list').find('.item').length<=0)
		$('.ui_none').show();
	else
		$('.ui_none').hide();
}
$(function(){		
	setheight=$('.ui_img_auto_cut').width();
	var overmonth = 0;
	var weekNames = [ '日', '一', '二', '三', '四', '五', '六' ];
	var today=new Date();
	var morrow=new Date((today/1000+86400)*1000);
	var r;
	$('#checkdate').cusCalendar({
		_parent			: 'checkdate',
		beginTimeElement:'checkin',
		endTimeElement  :'checkout',
		bTimeValElement :'startdate',
		eTimeValElement :'enddate',
		selectedCallBack:function(data){
			//$('.checkin .week').html(weekNames[data.inDate.getDay()] );
			$('#checkin').html( (data.inDate.getMonth() + 1) + '月' + data.inDate.getDate() + '日');
			
			//$('.checkout .week').html(weekNames[data.outDate.getDay()]);
			$('#checkout').html( (data.outDate.getMonth() + 1) + '月' + data.outDate.getDate() + '日');
			
			$('.checkin_time').html(data.dateSpan);
			get_lowest($('#startdate').val(),$('#enddate').val());
			isnone();
		}
	});
	 isnone();
});
function get_lowest(startdate,enddate){
	$.get('<?php echo Hotel_base::inst()->get_url("RETURN_LOWEST_PRICE");?>',{
			s:startdate,
			e:enddate,
			hs:'<?php echo $hotel_ids;?>'
		},function(data){
			$.each(data,function(i,n){
				$('#lowest_p_'+i).html(n);
			});
		},'json');
}	
//	$(document).on('touchmove',function(e){
//		return;
//		if($(document).height()-$(window).height()-4<=$(document).scrollTop()){;
//			if (!isfirst){
//				e.preventDefault();
//				isfirst = true;
//				showload('正在加载',true);
//				window.setTimeout(function(){  //模拟加载
//					add_hotel_to(
//						'room_list.html',   		   //  链接地址
//						'images/egimg/eg01.png',   //  图片地址
//						'广州天美酒店公寓体育中心店',   //  酒店名
//						'398',  				   //  价格
//						true,   				   //  是否返券,不返券传false
//						'12',   				   //  评论数,没有评论数传 null 
//						'&#xeb;,&#xe8;,&#xea;,&#xe4;,&#xe7;',  // 服务项,没有服务项传 null 
//						'酒店地址'  // 服务项,没有服务项传 null
//					);
//					showload('无更多结果');
//					removeload();
//				},2000);
//			}
//			else{
//				showload();
//				isfirst = false;
//			}
//		}
//	})
</script>
</html>

