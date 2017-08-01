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
<header>
	<div class="headfixed">
    	<div class="ui_btn_block float checkdate" id='checkdate' style="width:89%;">
            <span class="checkin" id='checkin'><span class="date"><?php echo date("m月d日",strtotime($startdate));?></span></span>
            &nbsp;
            &nbsp;
            <span class="checkout" id='checkout'><span class="date"><?php echo date("m月d日",strtotime($enddate));?></span></span>
            <span class="checkin_time ui_color"><?php echo round(strtotime($enddate)-strtotime($startdate))/86400;?></span>
        </div>
        <a class="map" style="display:none">
        	<em class="iconfont ui_color">&#x25;</em>
            <span class="ui_color">地图预览</span>
        </a>
    </div>
</header>
<div class="hotel_list">
<?php if(!empty($result)){ foreach($result as $r){?>
	<a onclick="go_hotel('index?id=<?php echo $inter_id?>&h=<?php echo $r->hotel_id?>')" href="javascript:void(0);" class="item">
        <div class="ui_img_auto_cut"><img src="<?php echo $r->intro_img?>" /></div>
        <div class="allin_box">
            <div class="name"><?php echo $r->name;?></div>
            <div class="coupon">
            <?php if(!empty($r->lowest)){?>
            	<div id="lowest_p_<?php echo $r->hotel_id;?>" class="ui_price"><b><?php echo $r->lowest;?></b></div>
            	<?php }else{?>
            	<div id="lowest_p_<?php echo $r->hotel_id;?>" class="big ui_color_gray">暂无价格</div>
            	<?php }?>
               <?php if(!empty($icons_set['coupon_back'])){?><div class="backvote">入住返券</div><?php }?>
            </div>
            <div class="ever hide"><span class="ui_color_gray">12条评论</span></div>
            <div class="sever">
            	<?php if(!empty($r->service)){ foreach ($r->service as $rs) {?> <em class="iconfont"><?php echo $rs['image_url'];?></em><?php }}?>	
                <em class="iconfont">&nbsp;</em>
            </div>
            <div class="distance txtclip"><?php echo $r->address?></div>
        </div>
    </a>
    <?php }?>
    <?php }?>

</div>

	<div class="ui_none middle"  style=" position:fixed;display:none">
    	<div>没有搜索到相关结果~<span class=" ui_color" onClick="history.back(-1);">重新搜索</span></div>
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
			$('.checkin .week').html(weekNames[data.inDate.getDay()] );
			$('.checkin .date').html( (data.inDate.getMonth() + 1) + '月' + data.inDate.getDate() + '日');
			
			$('.checkout .week').html(weekNames[data.outDate.getDay()]);
			$('.checkout .date').html( (data.outDate.getMonth() + 1) + '月' + data.outDate.getDate() + '日');
			
			$('.checkin_time').html(data.dateSpan);
			get_lowest($('#startdate').val(),$('#enddate').val());
			isnone();
		}
	});
	 isnone();
});
function get_lowest(startdate,enddate){
	$.get('/index.php/hotel/hotel/return_lowest_price?id=<?php echo $inter_id;?>',{
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

