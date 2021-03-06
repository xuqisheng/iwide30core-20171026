<?php include 'header.php'?>
<!-- <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ggmZIrqw5hOjnXwT7ypK0aIoZXrn4yfS"></script> -->
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<!-- <?php echo referurl('js','search.js',2,$media_path) ?> -->
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<style>
.checkin:after{content:"入住"}
.checkout:after{content:"离店"}
.checkin_time:before{ content:"共"}
.checkin_time:after{ content:"晚"}
.qi:after{ content:"起";}
</style>

<script>
var fail_locate='定位失败';
var latitude=0;
var longitude=0;
var is_get_local=false;
var isfirst=true;
var isload =false;
function to_locate(first){
	$('#location_tips').html('定位中');
	wx.getLocation({
	type:'gcj02',
    success: function (res) {
	        latitude  = res.latitude; // 纬度，浮点数，范围为90 ~ -90
	        longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
	        var speed = res.speed; // 速度，以米/每秒计
	        var accuracy = res.accuracy; // 位置精度

	        if(first==1){
// 		        $('#first_local').val('1');
		        locate_city(latitude,longitude,1);
	        }else{

	        	locate_city(latitude,longitude,0);
	        }
	    },
	cancel: function (res) {

			$('#location_tips').html(fail_locate);
			fill_hotel('html',0,true);
	    }
	});
}
function locate_city(lati,logi,first){
	geocoder = new qq.maps.Geocoder();
	var lat = parseFloat(lati);
	var lng = parseFloat(logi);
	var latLng = new qq.maps.LatLng(lat, lng);
	geocoder.getAddress(latLng);
	geocoder.setComplete(function(result) {
		is_get_local=true;
		console.log(result)
		var _h=result.detail.addressComponents;
		$('#location_tips').html(_h.district+_h.street+_h.streetNumber);
		$('#latitude').val(lat);
		$('#longitude').val(lng);
        $('#city').val(result.detail.addressComponents.city);
		if(first==1)
			fill_hotel('html',-1,isfirst);
		else
			fill_hotel('html',0);
	});
	//若服务请求失败，则运行以下函数
	geocoder.setError(function() {
		$('#location_tips').html(fail_locate);

		fill_hotel('html',0,true);
	});
}
</script>
<input type="hidden" id="startdate" name="startdate" value='<?php echo date('Y/m/d',strtotime($startdate));?>' />
<input type="hidden" id="enddate" name="enddate" value='<?php echo date('Y/m/d',strtotime($enddate));?>' />
<input type="hidden" id="off" name="off" value='0' />
<input type="hidden" id="num" name="num" value='20' />
<input type="hidden" id="latitude" name="latitude" value='' />
<input type="hidden" id="longitude" name="longitude" value='' />
<input type="hidden" id="sort_type" name="sort_type" value='distance' />
<input type="hidden" id="city" name="city" value='' />
<input type="hidden" id="ec" name="ec" value='[]' />
<input type="hidden" id="first_local" name="first_local" value='0' />

<div class="bg_fff bd_bottom headfixed txt_r" style="padding:4px 8px;">
     <span id="location_tips"></span><a class="color_main iconfont h30">&#x25;</a>
</div>

<div class="hotel_list list_style_2 bd_bottom" style="padding-top:30px"></div>
<div class="filter_option webkitbox">
	<div class="up" sort_tag='comment_score_'><em class="iconfont">&#x56;</em> 评价 </div>
	<div class="down" sort_tag='price_'><em class="iconfont">&#x45;</em> 价格 </div>
	<div class="up" sort_tag='distance_'><em class="iconfont">&#x36;</em> 距离 </div>
</div>

<div class="ui_none"  style="display:none">
    <div>没有搜索到相关结果~<span class="color_main" onClick="history.back(-1);">重新搜索</span></div>
</div>
<div style="padding-top:15%"></div>
</body>
<script>
var extra_condition={};
var city='';

if($('#first_local').val()==0){
    to_locate(1);
	wx.ready(function(){
		    to_locate(1);
	});
}
function go_hotel($url){
	location.href=$url+"&start="+$('#startdate').val()+"&end="+$('#enddate').val();
}
function distance_sort(){
	$('#sort_type').val('distance');
	pageloading();
	fill_hotel('html',0);
}

function price_down(){
    $('#sort_type').val('price_down');
    pageloading('请稍候',0.1);
    fill_hotel('html',0);
}

function comment_score(){
    $('#sort_type').val('comment_score');
    pageloading('请稍候',0.1);
    fill_hotel('html',0);
}

// fill_hotel('html',-1,isfirst);
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
    if($('#ec').val()!=undefined && $('#latitude').val()=='' && $('#longitude').val()==''){
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
        lm:lm
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
		$('.star').each(function(index, element) {
			var point=$(this).attr('star')*1;
			for(var i=0; i<point;i++){
				$('em',this).eq(i).addClass('star1');
				if ( i+1!= point & i+1>point) $('em',this).eq(i).addClass('star2');
				
			}
        });
	},'json');
}
$(function(){

    to_locate(1);

	$('.filter_option >*').click(function(){
		var isdown;
		var tag = '';
		if($(this).hasClass('down')){
			if($(this).hasClass('color_main')){
				$(this).removeClass('down');
				$(this).addClass('up');
				isdown = false;
				tag = 'up';
			}else{
				isdown = true;
				tag = 'down';
			}
		}
		else{
			if($(this).hasClass('color_main')){
				$(this).addClass('down');
				$(this).removeClass('up');
				isdown = true;
				tag = 'down';
			}else{
				isdown = false;
				tag = 'up';
			}
		}
		$(this).addClass('color_main').siblings().removeClass('color_main');
		$('#sort_type').val($(this).attr('sort_tag')+tag);
		fill_hotel('html',0);
		pageloading();
	});
	$('#filter_result').click(function(){
		if (!is_get_local){
			$.MsgBox.Alert('未获取到地理位置!当前默认:广州');
			$('#city').val('广州');
		}
		if(city!=$('#city').val()){
			city=$('#city').val();
			pageloading();
			$.get('<?php echo site_url('hotel/check/ajax_city_filter').'?id='.$inter_id;?>',{
				"city":city
			},function(data){
				console.log(data);
				removeload();
				if(data.s==1){
					$('.filter_pull').html(data.data);
					tobind();
					toshow($('.filter_pull'));
				}else{
					$.MsgBox.Alert(data.data);
				}
			},'json');
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
			select_day  :14,
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
	showload();

//	 fill_hotel('html',0,true);
	 //isnone();
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
