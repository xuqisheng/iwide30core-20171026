<?php include 'header.php'?>
<?php echo referurl('js','calendar_wuye.js?v='.time(),3,$media_path) ?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ggmZIrqw5hOjnXwT7ypK0aIoZXrn4yfS"></script>
<?php echo referurl('js','search.js?t='.time(),1,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<?php echo referurl('css','search_result.css',1,$media_path) ?>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<style>
.checkin .date:after{content:""}
.checkout .date:after{content:""}
.checkin_time:before{ content:"共"}
.checkin_time:after{ content:"晚"}
.ui_price:after{ content:"起";}
</style>

<script>
var fail_locate='定位失败,当前默认: <span>北京</span>';
var latitude=0;
var longitude=0;
var is_get_local=false;
var isfirst=true;
var isload =false;
function to_locate(first){
	$('.curlocal span').html('定位中');
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
			$('.curlocal').html(fail_locate);
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
		$('.curlocal span').html(_h.district+_h.street+_h.streetNumber);
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
		$('.curlocal').html(fail_locate);
		$('#city').val('北京市');
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
<style>
.curlocal span{vertical-align:baseline;}
</style>
<div class="headfixed">
  <div class="ui_btn_block checkdate" id='checkdate' style="width:92%">
        <em class="iconfont ui_color_gray h4">&#x3B;</em>&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="checkin" id='checkin'><span class="date"><?php echo date("m月d日",strtotime($startdate));?></span><span style="font-size:13px;">入住</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="checkout" id='checkout'><span class="date"><?php echo date("m月d日",strtotime($enddate));?></span><span style="font-size:13px;">离店</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="checkin_time"><?php echo round(strtotime($enddate)-strtotime($startdate))/86400;?></span>
    </div>
    <div class="ui_btn_block h4 curlocal txtclip" onClick="to_locate(0)">
        当前位置：<span>-</span>
    </div>
</div>
<div class="hotel_list" style="padding-top:25%">

</div>
<div class="filter_option h5">
	<div id="distance_sort" onclick="distance_sort()">距离<em class="iconfont h4">&#x32;</em></div>
	<div id="show_sort_pull">推荐排序<em class="iconfont h4">&#x3e;</em></div>
	<div id="filter_result">筛选<em class="iconfont h4">&#x40;</em></div>
</div>

<div class="sort_list_pull ui_pull" style="display:none">
	<div class="relative"><ul>
    	<li class="ui_color" sort_tag='default'>推荐排序</li>
    	<li sort_tag='price_up'>价格由低到高</li>
    	<li sort_tag='good_rate'>酒店好评率</li>
    </ul></div>
</div>
<div class="filter_pull ui_pull" style="display:none; background:#f7f7f7;">
	
</div>
<div class="ui_none middle"  style="position:fixed;display:none">
    <div>没有搜索到相关结果~<span class="ui_color" onClick="history.back(-1);">重新搜索</span></div>
</div>
<div style="padding-top:15%"></div>
</body>
<script>
var setheight=0;
var extra_condition={};
var city='';

if($('#first_local').val()==0){ 
	wx.ready(function(){
		to_locate(1);
	});
}
function go_hotel($url){
	location.href=$url+"&start="+$('#startdate').val()+"&end="+$('#enddate').val();
}
function distance_sort(){
	$('#sort_type').val('distance');
	pageloading('请稍候',0.1);
	fill_hotel('html',0);
}

// fill_hotel('html',-1,isfirst);
function fill_hotel(fill_way,offset,first){
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
	$.get('<?php echo site_url('hotel/check/ajax_hotel_list').'?id='.$inter_id;?>',{
		start:$('#startdate').val(),
		end:$('#enddate').val(),
		off:off,
		num:num,
		lat:$('#latitude').val(),
		lnt:$('#longitude').val(),
		sort_type:$('#sort_type').val(),
		city:$('#city').val(),
		ec:$('#ec').val()
	},function(data){
		if(data.s==1){
			if (window.sessionStorage){
				window.sessionStorage.latitude=$('#latitude').val();
				window.sessionStorage.longitude=$('#longitude').val();
				window.sessionStorage.sort_type=$('#sort_type').val();
				window.sessionStorage.city=$('#city').val();
				window.sessionStorage.ec=$('#ec').val();
			}
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
			if(first)$('.ui_none').show();
			else{
				showload('无更多结果',false);
			}
		}
		removeload();
		$('.page_loading').remove();
		isload = false;
	},'json');
}
$(function(){
	setheight=$('.ui_img_auto_cut').width();
	
	$('#filter_result').click(function(){
		if (!is_get_local){
			alert('未获取到地理位置!当前默认:北京');
			$('#city').val('北京');
		}
		if(city!=$('#city').val()){
			city=$('#city').val();
			$.get('<?php echo site_url('hotel/check/ajax_city_filter').'?id='.$inter_id;?>',{
				"city":city
			},function(data){
				$('.page_loading').remove();
				if(data.s==1){
					$('.filter_pull').html(data.data);
					tobind();
					toshow($('.filter_pull'));
				}else{
					alert(data.data);
				}
			},'json');
			pageloading('请稍候',0.1);
		}else{
			toshow($('.filter_pull'));
		}
	})
	$('#show_sort_pull').click(function(){
		toshow($('.sort_list_pull'));
	})
	var overmonth = 0;
	var weekNames = [ '日', '一', '二', '三', '四', '五', '六' ];
	if (window.sessionStorage){
		var today, morrow,dateSpan;
		if(window.sessionStorage.checkin!=undefined){
			today =new Date(window.sessionStorage.checkin);
			morrow =new Date(window.sessionStorage.checkout);
			$('.checkin .date').html( (today.getMonth() + 1) + '月' + today.getDate() + '日');
			$('.checkout .date').html( (morrow.getMonth() + 1) + '月' + morrow.getDate() + '日');
			$('#startdate').val(today.getFullYear()+'\/'+(today.getMonth()+1)+'\/'+today.getDate());
			$('#enddate').val(morrow.getFullYear()+'\/'+(morrow.getMonth()+1)+'\/'+morrow.getDate());
			$('.checkin_time').html(window.sessionStorage.checkin_time);
		}
	}
	var r;
	$('#checkdate').cusCalendar({
		_parent         :'checkdate',
		beginTimeElement:'checkin',
		endTimeElement  :'checkout',
		bTimeValElement :'startdate',
		eTimeValElement :'enddate',
			select_day  :14,
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
	showload();
	
	 //fill_hotel('html',0,true);
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
$(document).on('touchmove',function(e){
	if( ($(document).height()-$(window).height())*0.4<=$(document).scrollTop()){;
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
