<?php include 'header.php'?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('js','imgscroll.js',3,$media_path) ?>
<?php echo referurl('js','calendar_wuye.js?v='.time(),3,$media_path) ?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ggmZIrqw5hOjnXwT7ypK0aIoZXrn4yfS"></script>
<?php echo referurl('js','search.js',1,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<?php echo referurl('css','index.css',1,$media_path) ?>
<?php echo referurl('css','su8.css',1,$media_path) ?>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script>
var fail_locate='定位失败';
var latitude=0;
var longitude=0;
var city='-1';
function to_locate(){
	$('#cur_city').html('定位中');
	wx.getLocation({
	type:'gcj02',
    success: function (res) {
	        latitude  = res.latitude; // 纬度，浮点数，范围为90 ~ -90
	        longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
	        var speed = res.speed; // 速度，以米/每秒计
	        var accuracy = res.accuracy; // 位置精度
	        locate_city(latitude,longitude);
	    },
	cancel: function (res) {
			$('.local').html(fail_locate);
			$('#cur_city').html(fail_locate);
	    }
	});
}
function locate_city(lati,logi){
	geocoder = new qq.maps.Geocoder();
	var lat = parseFloat(lati);
	var lng = parseFloat(logi);
	var latLng = new qq.maps.LatLng(lat, lng);
	geocoder.getAddress(latLng);
	geocoder.setComplete(function(result) {
		$('.local').html(result.detail.addressComponents.city);
		$('#cur_city').html(result.detail.addressComponents.city);
		$('#city').val(result.detail.addressComponents.city);
		$('#cc').html(result.detail.addressComponents.city);
		// get_near(lati,logi);
	});
	//若服务请求失败，则运行以下函数
	geocoder.setError(function() {
		$('.local').html(fail_locate);
		$('#cur_city').html(fail_locate);
	});
}
function get_near(lati,logi){
	$('#cc').html('附近酒店');
	$.get('/index.php/hotel/hotel/get_near_hotel?id=<?php echo $inter_id;?>',
		{
			lat:lati,
			lnt:logi
		},function(data){
			var tmp='';
			$.each(data,function(i,n){
				tmp+=' <li><a href="/index.php/hotel/hotel/index?id=<?php echo $inter_id;?>&h='+n.hotel_id+'">'+n.name+'</a></li>';
			});
			$('#near_c').html(tmp);
		},'json');
	$('.near').on('click',function(){
		toshow($('#near_c_i'));
	});
}
</script>
<style>
.checkin_time:after{ content:"晚"}
<?php echo $extra_style;?>
.tmp_su8_logo{ display:-webkit-box; padding:1% 3%; background:#fff; -webkit-box-align:center}
.tmp_su8_logo >*{-webkit-box-flex:1; font-size:11px;}
.tmp_su8_logo img{height:32px; width:auto}
.tmp_su8_logo .iconfont{vertical-align:baseline; font-size:13px;}
.tmp_su8_logo .txtclip{max-width:32%}
</style>
<div class="tmp_su8_logo">
	<div><img src="/public/hotel/su8/images/logo.png"></div>
    <div class="txtclip" style="text-align:right">
        <?php if(isset($member->is_login) && ($member->is_login ==1)){ ?>
            <?php echo $member->name."，你好";?>
        <?php }else{  ?>
        <a href="<?php echo base_url('/index.php/member/account/register?id=').$inter_id;?>" style="margin-right:5%"><em class="iconfont">&#x4b;</em>注册</a>
        <a href="<?php echo base_url('/index.php/member/account/login?id=').$inter_id;?>"><em class="iconfont">&#x4c;</em>登录</a>
         <?php } ?>
    </div>
</div>
<header class="headers"> 
    <div class="headerslide">
	<?php foreach ($pubimgs as $pi){?>
    <a class="slideson ui_img_auto_cut" href="<?php echo $pi['link'];?>">
		<img src="<?php echo $pi['image_url'];?>" />
    </a>
    <?php }?>
    </div>
</header>
<form action="sresult?id=<?php echo $inter_id?>" method="post" id="index_search">
<input type="hidden" id="startdate" name="startdate" value="<?php echo date('Y/m/d',strtotime($startdate));?>" />
<input type="hidden" id="enddate" name="enddate" value="<?php echo date('Y/m/d',strtotime($enddate));?>" />
<input type="hidden" id="city" name="city" value="<?php echo $first_city; ?>" />
<input type="hidden" id="ec" name="ec" value='[]' />
<input type="hidden" id="<?php echo $csrf_token;?>" name="<?php echo $csrf_token;?>" value="<?php echo $csrf_value;?>" />

<div class="livein_type hide">
	<div class="cur">全日房</div>
    <div>时租房</div>
    <input type="hidden" id='livein_type' value="">
</div>
<div class="ui_list">
	<div class="ui_item">
        <div class="location ui_btn_block">
        	<em class="iconfont ui_color_gray">&#x2F;</em>
            <div class="local txtclip"><?php echo $first_city; ?></div>
        </div>
        <a class="near" href="<?php echo site_url('hotel/check/nearby').'?id='.$inter_id;?>" nonclick="to_locate()"><em class="iconfont" style="vertical-align:top; color:#989898;">&#x36;</em><p style="color:#555;">附近酒店</p></a>
    </div>
	<div class="ui_item ui_btn_block" id="checkdate">
        <em class="iconfont ui_color_gray">&#x3B;</em>
        <div class="checkin" id='checkin'>
            <div class="btn_title"><span class="h6">入住</span> <span class="week h6">一</span></div>
            <span class="date">1.1</span>
        </div>
        <div class="checkout" id='checkout'>
            <div class="btn_title"><span class="h6">离店</span> <span class="week h6">一</span></div>
            <span class="date">1.1</span>
        </div>
        <div class="border_circle">
            <div class="checkin_time ui_color_gray" style="display:inline-block">1</div>
        </div>
    </div>
	<div class="ui_item ui_btn_block searchbox">
        <em class="iconfont ui_color_gray" style="width:1.3em">&#x37;</em>
        <input name="keyword" placeholder=" 关键字/位置/名称" class="keyword" readonly>
    </div>
</div>
<div class="ui_foot_btn">
	<button class="ui_btn isable" type="submit"><em class="iconfont">&#x2C;</em>查询</button>
</div>
</form>
<div class="often_like">
	<a href="<?php echo site_url('member/center/index').'?id='.$inter_id;?>">
    	<em class="iconfont" style=" background:#ff8822">&#x38;</em>
        <p><span>我的</span></p>
    </a>
	<a href="<?php echo site_url('hotel/hotel/myorder').'?id='.$inter_id;?>">
    	<em class="iconfont" style="background:#62cced">&#x35;</em>
        <p><span>订单</span></p>
    </a>
	<a href="<?php echo site_url('hotel/check/my_collection').'?id='.$inter_id;?>">
    	<em class="iconfont" style="background:#7889f1">&#x42;</em>
        <p><span>收藏</span></p>
    </a>
	<a href="<?php echo site_url('member/crecord/balances').'?id='.$inter_id;?>" class="">
    	<em class="iconfont" style="background:#f5b95a">&#x45;</em>
        <p><span>余额</span></p>
    </a>
</div>

<div class="ui_foot_btn">
	<a href="tel:40018-40018" class="ui_btn ui_color">订房专线  40018 - 40018</a>
</div>
<div class="ui_list hide" style="border-width:1px 0;">
	<div class="ui_item ui_btn_block" style="border:0" id="often_like">
        <em class="iconfont ui_color_gray">&#x2f;</em>
        <span class="h4">我的酒店</span>
        <span class="h5 ui_color_gray">常住/收藏酒店</span>
    </div>
</div>
<div class="ui_pull history_pull" id="history_pull" style="display:none;" onClick="toclose()">
    <div class="pull_box">
        <div class="ui_color pull_title bdtom" >浏览历史</div>
<?php if(!empty($hotel_visited)){?>
        <ul>
        <?php foreach($hotel_visited as $hv){?>
            <li><a href="<?php echo $hv['mark_link']?>"><?php echo $hv['mark_title']?></a></li>
            <?php }?>
        <li class="clear_history">清除历史</li>
        </ul>
 <?php }else{?>
        <ul>
            <li>无</li>
        </ul>
  <?php }?>
	</div>
</div>

<div class="ui_pull like_pull" id="near_c_i" style="display:none;">
    <div class="pull_box">
        <div class="ui_color pull_title">附近酒店</div>
        <ul id='near_c'>
        </ul>
        <div class="close"><em class="iconfont">&#x27;</em><p>关闭</p></div>
    </div>
</div>
<div class="ui_pull often_pull" style="display:none;" onClick="toclose()">
    <div class="pull_box">
        <div class="ui_color pull_title">我的酒店</div>
        <?php if(!empty($last_orders)){ ?>
        <ul>
        	<li>常住酒店</li>
       <?php foreach($last_orders as $lo){?>
            <li><a href="<?php echo site_url('hotel/hotel/index').'?id='.$inter_id.'&h='.$lo['hotel_id']?>"><?php echo $lo['hname']; ?></a></li>
            <?php }?>
        </ul>
        <?php } else {?>
        <ul>
         <li><?php echo '无'; ?></li>
        </ul>
        <?php }?>
       
        <?php if(!empty($hotel_collection)) {?>
        <ul>
        	<li>收藏的酒店</li>
       		<?php foreach($hotel_collection as $hc){ ?>
            <li><a href="<?php echo $hc['mark_link']?>"><?php echo $hc['mark_title'];?></a></li>
            <?php }?>
        </ul>
        <?php }else{?>
        <ul>
            <li>无</li>
        </ul>
        <?php }?>
        <div class="close" style="display:none"><em class="iconfont">&#x27;</em><p>关闭</p></div>
    </div>
</div>
<div class="ui_pull like_pull" id="collects" style="display:none;" onClick="toclose()">
    <div class="pull_box">
        <div class="ui_color pull_title bdtom" >我的收藏</div>
        <?php if(!empty($hotel_collection)) {?>
        <ul>
       		<?php foreach($hotel_collection as $hc){ ?>
            <li><a href="<?php echo $hc['mark_link']?>"><?php echo $hc['mark_title'];?></a></li>
            <?php }?>
        </ul>
<!-- <a href="often_like.html" class="pull_more">查看更多</a> -->
        <?php }else{?>
        <ul>
            <li>无</li>
        </ul>
        <?php }?>
        <div class="close" style="display:none"><em class="iconfont">&#x27;</em><p>关闭</p></div>
    </div>
</div>
<div class="ui_pull address_pull" style="display:none;"><!-- 地址列表 -->
    <div class="pull_searchbox"><input type="text" placeholder="输入城市名称" class="search" id="search"></div>
    <div class="content_pull" style="height:90%">
        <div class="local_pull h3 border" onClick="to_locate()">
            <em class="iconfont h3">&#x25;</em>当前城市:
            <span id="cur_city"><?php echo $first_city; ?></span>
        </div>
<!--    <?php if(!empty($last_orders)){ ?>  	
        <div class="title">历史城市</div>
        <ul class="address_list">
          <?php foreach($last_orders as $lo){?>
        	<li><?php echo $lo['hcity'];?></li>
        	<?php }?>
        </ul><?php }?>-->
		<?php if(!empty($hot_city)){ ?>
        <div class="hot_city">
            <div class="title">
                <span class="h6" style="color:#fff; background:#f23030;padding:0 5px">hot</span>
                热门城市</div>
            <ul class="address_list">
                 <?php foreach($hot_city as $hc){?>
                <li><?php echo $hc;?></li>
                <?php }?>
            </ul>
        </div>
		<?php }?>
        <?php $let=array(); foreach($citys as $ck=>$cs){ $let[]=$ck;?>
    	<div class="title" id="<?php echo $ck;?>"><?php echo $ck;?></div>
        <ul class="address_list" id="get_result">
        <?php foreach($cs as $c){ ?>
        	<li><?php echo $c['city']; ?></li>
        	<?php }?>
        </ul>
        <?php }?>
    </div>
    <div class="address_index">
        <p class="iconfont">&#x2c;</p>
        <?php foreach($let as $l){?>
        <div><?php echo $l;?></div>
    <?php }?></div>
</div>

<div class="filter_pull ui_pull" style="display:none; background:#f7f7f7;">
</div>
</body>
<script>

if (window.sessionStorage){
	window.sessionStorage.latitude='';
	window.sessionStorage.sort_type='';
	window.sessionStorage.city='';
	window.sessionStorage.ec='';
}

if (window.localStorage){
	if(window.localStorage.city !=undefined){
		$('.local').html(window.localStorage.city);
		$('#city').val(window.localStorage.city);
	}
	else window.localStorage.city=$('#city').val();
}
var extra_condition={};
var fill_hotel =function(){$('.page_loading').remove();}
var address_search = function(_str){
	if(!_str)_str='全部';
	var tmp='';	
	
	tmp+='广州,高州';
	address_fill(tmp);
}
var address_fill = function(_str){
	var adrs = _str.split(',');
	if ( _str=='')
		adrs='';
	var tmp  ='<div class="title">搜索到'+adrs.length+'个城市</div>';
	if ( adrs.length){
		tmp +='<ul class="address_list">';
		for(var i=0; i<adrs.length; i++)
			tmp+='<li>'+adrs[i]+'</li>';
		tmp+='</ul>';
	}
	$('.address_pull .content_pull').html(tmp);
}
var add_0 = function(num){
	if ( num <10 )
		return '0'+num;
	return num;
}


$('.local').html($('#city').val());
var overmonth = 0;
var weekNames = [ '日', '一', '二', '三', '四', '五', '六' ];
var today=new Date($('#startdate').val());
var morrow=new Date($('#enddate').val());//(today1000+86400000));
if (window.sessionStorage){
	if(window.sessionStorage.checkin==undefined){
		window.sessionStorage.checkin=today;
		window.sessionStorage.checkout=morrow;
		window.sessionStorage.checkin_time=1;
	}
	else{
		today =new Date(window.sessionStorage.checkin);
		morrow =new Date(window.sessionStorage.checkout);
	}
	$('#startdate').val(today.getFullYear()+'\/'+(today.getMonth()+1)+'\/'+today.getDate());
	$('#enddate').val(morrow.getFullYear()+'\/'+(morrow.getMonth()+1)+'\/'+morrow.getDate());
	$('.checkin_time').html(window.sessionStorage.checkin_time);
}
$('.checkin .date').html( add_0(today.getMonth() + 1) + '.<b>' +add_0(today.getDate()) + '</b>');
$('.checkin .week').html(weekNames[today.getDay()]);

$('.checkout .date').html(add_0(morrow.getMonth() + 1) + '.<b>' +add_0( morrow.getDate()) + '</b>');
$('.checkout .week').html(weekNames[morrow.getDay()]);

$(function(){
	$.fn.imgscroll({
		imgrate			 : 1244/547, 
	    partent_div      : 'headers',
	});
	$('#often_like').on('click',function(){
		toshow($('.often_pull'));
	});
	$('.showhistory').on('click',function(){
		toshow($('.history_pull'));
	});
	$('.like').on('click',function(){
		toshow($('#collects'));
	});
	$('.close').on('click',function(){
		toclose();
	});
	$('.ui_pull a').on('click',function(e){
		e.stopPropagation();
	});
	$('.location').on('click',function(){
		toshow($('.address_pull'));
		//var __top = ($('.address_pull').height()-$('.address_index').height())/2;
		//$('.address_index').css('top',__top);
	});
	$('.address_index div').on('click',function(){
		var _scltop = $(this).html();
		_scltop=$('#'+_scltop).offset().top+$('.content_pull').scrollTop();
		$('.content_pull').scrollTop(_scltop);
	});
	$('.address_pull li').on('click',function(){
		toclose();
		if($(this).html()!=fail_locate){
			$('.location .local').html($(this).html());
			if($(this).html()!=$('#city').val()){
				$('#ec').val('');
				$('.searchbox input').val('');
			}
			if($(this).html()!='全部')
				$('#city').val($(this).html());
			else
				$('#city').val('');
		}
		if(window.localStorage)window.localStorage.city=$('#city').val();
	});
	$('.clear_history').on('click',function(){
		var $confirm = confirm("历史记录清除后将不可恢复，是否继续？");
		if( $confirm ){
			$.get('/index.php/hotel/hotel/clear_visited_hotel?id=<?php echo $inter_id?>',function(data){
				if(data==1)
					$('.history_pull ul').html('<li>无</li>');
			});
		}
	});
		
	var fill_date =function(data){
		$('.checkin .week').html(weekNames[data.inDate.getDay()] );
		$('.checkin .date').html( (data.inDate.getMonth() + 1) + '月' + data.inDate.getDate() + '日');
		$('.checkout .week').html(weekNames[data.outDate.getDay()]);
		$('.checkout .date').html( (data.outDate.getMonth() + 1) + '月' + data.outDate.getDate() + '日');
		$('.checkin_time').html(data.dateSpan);
		if (window.sessionStorage){
			window.sessionStorage.checkin=data.inDate;
			window.sessionStorage.checkout=data.outDate;
			window.sessionStorage.checkin_time=data.dateSpan;
		}
	}
		
	$('.livein_type div').click(function(){
		$(this).addClass('cur').siblings().removeClass('cur');
		var _boll;
		if($(this).index()==1){
			_boll=true;
			$('.checkout').stop().hide();
			$('.checkin_time ').stop().hide();
		}
		else{
			_boll=false;
			$('.checkout').stop().show();
			$('.checkin_time ').stop().show();
		}
		$('#checkdate').cusCalendar({
			_parent			:'checkdate',
			beginTimeElement:'checkin',
			endTimeElement  :'checkout',
			bTimeValElement :'startdate',
			eTimeValElement :'enddate',
			select_single   : _boll,
			select_day      :14,
			selectedCallBack:function(data){fill_date(data)}
		});
	});
	$('.livein_type div').eq(0).trigger('click');
	
	$('.searchbox').on('click',function(){
		if(city!=$('#city').val()){
			city=$('#city').val();
			$.get('<?php echo site_url('hotel/check/ajax_city_filter').'?id='.$inter_id;?>',{
				city:$('#city').val()
			},function(data){
				$('.page_loading').remove();
				if(data.s==1){
					$('.filter_pull').html(data.data);
					toshow($('.filter_pull'));
					tobind();
				}else{
					alert(data.data);
				}
			},'json');
			pageloading('请稍候',0.1);
		}else{
			toshow($('.filter_pull'));
		}
	});
	$('#search').bind('change', function() {/*input propertychange*/
		var val=$(this).val();
		if( val ==''){
			$('#get_result li').stop().show();
			$('.content_pull div').show();
		}
		else{
			$('.content_pull div').hide();
			for( var i=0; i<$('#get_result li').length; i++){
				if ( $('#get_result li').eq(i).html().indexOf(val) >= 0){
					$('#get_result li').eq(i).stop().show();
				}
				else{
					$('#get_result li').eq(i).stop().hide();
				}
			}
		}
	});  
	$('.keyword').val('');
	$('#ec').val('');
})
</script>
</html>
