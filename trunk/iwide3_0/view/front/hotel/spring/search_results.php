<?php include 'header.php'?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ggmZIrqw5hOjnXwT7ypK0aIoZXrn4yfS"></script>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<?php echo referurl('js','search.js',2,$media_path) ?>
<style>
.checkin:after{content:"入住"}
.checkout:after{content:"离店"}
.checkin_time:before{ content:"共"}
.checkin_time:after{ content:"晚"}
.qi:after{ content:"起";}
</style>

<input type="hidden" id="startdate" name="startdate" value='<?php echo date('Y/m/d',strtotime($startdate));?>' />
<input type="hidden" id="enddate" name="enddate" value='<?php echo date('Y/m/d',strtotime($enddate));?>' />
<input type="hidden" id="city" name="city" value="<?php echo $city; ?>" />
<input type="hidden" id="startdate" name="startdate" value='<?php echo date('Y/m/d',strtotime($startdate));?>' />
<input type="hidden" id="enddate" name="enddate" value='<?php echo date('Y/m/d',strtotime($enddate));?>' />
<input type="hidden" id="off" name="off" value='0' />
<input type="hidden" id="num" name="num" value='20' />
<input type="hidden" id="latitude" name="latitude" value='' />
<input type="hidden" id="longitude" name="longitude" value='' />
<input type="hidden" id="sort_type" name="sort_type" value='distance' />
<input type="hidden" id="city" name="city" value='' />
<input type="hidden" id="ec" name="ec" value='<?php echo $extra_condition;?>' />
<input type="hidden" id="first_local" name="first_local" value='0' />

<div class="bg_fff bd_bottom headfixed list_style">
    <div class="webkitbox justify arrow" id='checkdate'>
        <span class="checkin" id='checkin'><?php echo date("m月d日",strtotime($startdate));?></span>
        <span class="checkout" id='checkout'><?php echo date("m月d日",strtotime($enddate));?></span>
        <span class="checkin_time color_main"><?php echo round(strtotime($enddate)-strtotime($startdate))/86400;?></span>
    </div>
</div>
<!--a class="map" style="display:none">
    <em class="iconfont color_main">&#x25;</em>
    <span class="color_main">地图预览</span>
</a-->

<div  style="padding-top:35px"></div>
<div class="hotel_list list_style_2 bd_bottom">

</div>

<div class="filter_option webkitbox">
	<div id="show_sort_pull"><span>推荐排序</span><em class="iconfont h20">&#x3e;</em></div>
	<div id="filter_result">筛选<em class="iconfont h20">&#x40;</em></div>
</div>

<div class="sort_list_pull ui_pull h26" style="display:none" onClick="toclose()">
    <div class="relative"><ul class="list_style_1 center color_main">
            <li class="cur" sort_tag='default'>推荐排序</li>
            <li sort_tag='price_down' >价格由低到高</li>
            <li sort_tag='price_up' >价格由高到低</li>
            <li sort_tag='comment_score' >酒店好评率</li>
        </ul></div>
</div>

<div class="ui_none"  style="display:none">
    <div>没有搜索到相关结果~</div>
</div>
<div style="padding-top:45px"></div>
</body>
<script>
var isfirst=true;
var isload =false;


function go_hotel($url){
	location.href=$url+"&start="+$('#startdate').val()+"&end="+$('#enddate').val();
}
var city='none';
$(function(){
	$('#filter_result').click(function(){
		if(city!=$('#city').val()){
			city=$('#city').val();
			$.get('<?php echo site_url('hotel/check/ajax_city_filter').'?id='.$inter_id;?>',{
				city:$('#city').val()
			},function(data){
				removeload();
				if(data.s==1){
					$('.filter_pull').html(data.data);
					toshow($('.filter_pull'));
					tobind();
				}else{
					$.MsgBox.Alert(data.data);
				}
			},'json');
			pageloading();
		}else{
			toshow($('.filter_pull'));
		}
	})

	$('#show_sort_pull').click(function(){
		toshow($('.sort_list_pull'));
	})
	var overmonth = 0;
	var weekNames = [ '日', '一', '二', '三', '四', '五', '六' ];
	var r;
	$('#checkdate').cusCalendar({
		_parent         :'checkdate',
		beginTimeElement:'checkin',
		endTimeElement  :'checkout',
		bTimeValElement :'startdate',
		eTimeValElement :'enddate',
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
	showload();
	fill_hotel('html',0,true);

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
	$.get('/index.php/hotel/hotel/return_lowest_price?id=<?php echo $inter_id;?>',{
			s:startdate,
			e:enddate,
			hs:hotel_ids
		},function(data){
			$.each(data,function(i,n){
				$('#lowest_p_'+i).html(''+n+'');
			});
		},'json');
}
function fill_hotel(fill_way,offset,first){
//    console.log($('#city').val());
    tmp='';
    var off=$('#off').val()*1;
    var num=$('#num').val()*1;
    if(offset!=-1){
        off=offset;
    }
    if($('.hotel_list').find('.item').length<=0){
        off=0;
        num=num+$('#off').val()*1;
    }

        var lat=$('#latitude').val();
        var lng=$('#longitude').val();
        var lm = '';
    if($('#ec').val()!=''&&$('#ec').val()!=undefined && $('#latitude').val()=='' && $('#longitude').val()==''){
        var map = JSON.parse($('#ec').val());
        if(map.bdmap!=undefined){
            var pl = map.bdmap.split(',');
            lat=pl[0];
            lng=pl[1];
            lm = pl[2];
        }
    }

    $.get('<?php echo site_url('hotel/check/ajax_hotel_list').'?id='.$inter_id;?>',{
        start:$('#startdate').val(),
        end:$('#enddate').val(),
        off:off,
        num:num,
        lat:lat,
        lnt:lng,
        sort_type:$('#sort_type').val(),
        city:$('#city').val(),
        ec:$('#ec').val(),
        lm: lm
    },function(data){
        if(data.s==1){
            $('.hotel_list').html('');
            $('.ui_none').hide();
            if(data.data=='')
                showload('无更多结果',false);
            tmp=data.data;
            $('#off').val(off+$('#num').val()*1);
            if(fill_way=='append')
                $('.hotel_list').append(tmp);
            else
                $('.hotel_list').html(tmp);
        }else{
            $('.hotel_list').html('');
            if(first)$('.ui_none').show();
            else{
                showload('无更多结果',false);
            }
        }
        removeload();
        isload = false;
    },'json');
}

var  startX ,startY;
$(document).bind('touchstart',function(e){
    startX = e.originalEvent.changedTouches[0].pageX,
    startY = e.originalEvent.changedTouches[0].pageY;
});
$(document).on('touchmove',function(e){
    endX = e.originalEvent.changedTouches[0].pageX,
    endY = e.originalEvent.changedTouches[0].pageY;
    //获取滑动距离
    distanceX = endX-startX;
    distanceY = endY-startY;
    //判断滑动方向
    /*if(Math.abs(distanceX)>Math.abs(distanceY) && distanceX>0){
       	//alert('往右滑动');
    }else if(Math.abs(distanceX)>Math.abs(distanceY) && distanceX<0){
        //alert('往左滑动');
    }else if(Math.abs(distanceX)<Math.abs(distanceY) && distanceY<0){
        //alert('往上滑动');
    }else if(Math.abs(distanceX)<Math.abs(distanceY) && distanceY>0){
        //alert('往下滑动');
    }
	*/
	if(distanceY<0&&($(document).height()-$(window).height())*0.4<=$(document).scrollTop()){
		if (!isload){
			e.preventDefault();
			fill_hotel('append',-1,false);
			//isfirst = true;
			isload  = true;
		}
		else{
			showload();
			//isfirst = false;
		}
	}
})
</script>
</html>

