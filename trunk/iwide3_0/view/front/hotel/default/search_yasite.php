<?php include 'header.php'?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('js','imgscroll.js',3,$media_path) ?>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<?php echo referurl('css','index.css',1,$media_path) ?>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<style>
.checkin_time:after{ content:"晚"}
</style>
<!--<?php echo $extra_style ?>-->
<script>
var fail_locate='定位失败';
var latitude=0;
var longitude=0;

function to_locate(){
	$('#cc').html('定位中');
	wx.getLocation({
    success: function (res) {
	        latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
	        longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
	        var speed = res.speed; // 速度，以米/每秒计
	        var accuracy = res.accuracy; // 位置精度
	        locate_city(latitude,longitude);
	    },
	cancel: function (res) {
			$('.local').html(fail_locate);
			$('#cur_city').html(fail_locate);
			$('#cc').html(fail_locate);
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
<header class="headers"> 
  <div class="headerslide">
  <?php foreach ($pubimgs as $pi){?>
  	  <a class="slideson ui_img_auto_cut" href="<?php echo $pi['link'];?>">
     	 <img src="<?php echo $pi['image_url'];?>" />
      </a>
      <?php }?>
  </div>
</header>
<form action="sresult?id=<?php echo $inter_id?>" onsubmit="$(this).attr('action', $(this).attr('action')+'&city='+escape($('#city').val()))" method="post">
<input type="hidden" id="startdate" name="startdate" value="<?php echo date('Y/m/d',strtotime($startdate));?>" />
<input type="hidden" id="enddate" name="enddate" value="<?php echo date('Y/m/d',strtotime($enddate));?>" />
<input type="hidden" id="city" name="city" value="<?php if($inter_id=='a421641095'){echo '广州';}else{echo $first_city;} ?>" />
<input type="hidden" id="<?php echo $csrf_token;?>" name="<?php echo $csrf_token;?>" value="<?php echo $csrf_value;?>" />
<div class="ui_list">
	<div class="ui_item">
        <div class="location ui_btn_block">
            <div class="btn_title">入住城市</div>
            <div class="local txtclip"><?php if($inter_id=='a421641095'){echo '广州';}else{echo $first_city;} ?></div>
        </div>
        <div class="near" onclick="to_locate()"><em class="iconfont ui_color">&#x24;</em><p id="cc">定位</p></div>
    </div>
	<div class="ui_item" id="checkdate">
        <div class="checkin ui_btn_block float" id='checkin'>
            <div class="btn_title">入住日期</div>
            <span class="date">1月1日</span>
            <span class="week">一</span>
        </div>
        <div class="border_circle float">
            <div class="checkin_time ui_square_h align_middle">1</div>
        </div>
        <div class="checkout ui_btn_block float_r" id='checkout'>
            <div class="btn_title">离店日期</div>
            <span class="date">1月1日</span>
            <span class="week">一</span>
        </div>
    </div>
	<div class="ui_item">
        <div class="searchbox">
            <input name="keyword" placeholder="关键字/位置/名称" class="keyword">
        </div>
    </div>
</div>
<div class="ui_foot_btn">
	<button class="ui_btn isable">查询</button>
</div>
</form>
<div class="often_like">
	<div class="often">
    	<em class="iconfont ui_color">&#x28;</em><span>常住酒店</span>
    </div>
    <!-- 雅思特修改部分 -->
	<a class="like" href="http://yesite.liyewl.com/index.php/hotel/hotel/myorder?id=a472731996">
    	<em class="iconfont ui_color">&#x30;</em><span>我的订单</span>
    </a>
	<a class="like" style="margin-top:10px; border:0" href="http://yesite.liyewl.com/index.php/soma/package/category_list?catid=11095&id=a472731996">
    	<em class="iconfont ui_color">&#x29;</em><span>定制服务</span>
    </a>
	<a class="like" style="margin-top:10px;" href="http://v1.iwide.cn/index.php?g=Wap&m=Index&a=lists&token=unjmar1474966064&classid=1208">
    	<em class="iconfont ui_color">&#x35;</em><span>协议优惠</span>
    </a>
    <!-- end -->
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
<!--         <a href="often_like.html" class="pull_more">查看更多</a> -->
        <?php }else{?>
        <ul>
            <li>无</li>
        </ul>
        <?php }?>
        <div class="close" style="display:none"><em class="iconfont">&#x27;</em><p>关闭</p></div>
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
        <div class="ui_color pull_title bdtom">常住酒店</div>
        <?php if(!empty($last_orders)){ ?>
        <ul>
       <?php foreach($last_orders as $lo){?>
            <li><a href="<?php echo site_url('hotel/hotel/index').'?id='.$inter_id.'&h='.$lo['hotel_id']?>"><?php echo $lo['hname']; ?></a></li>
            <?php }?>
        </ul>
<!--         <a href="often_like.html" class="pull_more">查看更多</a> -->
        <?php } else {?>
        <ul>
         <li><?php echo '无'; ?></li>
        </ul>
        <?php }?>
        <div class="close" style="display:none"><em class="iconfont">&#x27;</em><p>关闭</p></div>
    </div>
</div>
<div class="ui_pull address_pull" style="display:none"><!-- 地址列表 -->
<!-- 	<div class="pull_searchbox"><input type="search" placeholder="输入城市名称或者拼音" class="search"></div> -->
    <div class="content_pull">
    	<ul class="address_list">
        	<li>全部</li>
        </ul>
    	<div class="title">当前城市</div>
    	 <ul class="address_list">
        	<li id="cur_city"></li>
        </ul>
       <!--  <div class="around ui_color">我附近的酒店</div>
        <ul class="address_list">
        	<li>广州</li>
        	<li>深圳</li>
        </ul> -->
     
            
         <?php if(!empty($last_orders)){ ?>
    	<div class="title">历史城市</div>
        <ul class="address_list">
          <?php foreach($last_orders as $lo){?>
        	<li><?php echo $lo['hcity'];?></li>
        	<?php }?>
        </ul>
        <?php }?>
		<?php if(!empty($hot_city)){ ?>
    	<div class="title">热门城市</div>
        <ul class="address_list">
        	 <?php foreach($hot_city as $hc){?>
        	<li><?php echo $hc;?></li>
        	<?php }?>
        </ul>
		<?php }?>
        <?php $let=array(); foreach($citys as $ck=>$cs){ $let[]=$ck;?>
    	<div class="title" id="<?php echo $ck;?>"><?php echo $ck;?></div>
        <ul class="address_list">
        <?php foreach($cs as $c){ ?>
        	<li><?php echo $c['city']; ?></li>
        	<?php }?>
        </ul>
        <?php }?>
    	<div class="address_index"><?php foreach($let as $l){?>
			<div><?php echo $l;?></div>
		<?php }?></div>
    </div>
</div>
<!--
<?php if(!empty($hotel_visited)){?>
<div class="history">
	<div class="title"><hr><span>浏览历史</span><hr></div>
    <ul>
    <?php foreach($hotel_visited as $hv){?>
    	<li><a href="<?php echo $hv['mark_link']?>"><?php echo $hv['mark_title']?></a></li>
    	<?php }?>
    </ul>
    <div class="clear_history">清除历史</div>
</div>
<?php }?>
-->
</body>
<script>
$('.local').html($('#city').val());
var overmonth = 0;
var weekNames = [ '日', '一', '二', '三', '四', '五', '六' ];
var today=new Date(<?php echo strtotime($startdate)*1000; ?>);
var morrow=new Date((today/1000+86400)*1000);

$('.checkin .date').html((today.getMonth() + 1) + '月' + today.getDate() + '日');
$('.checkin .week').html(weekNames[today.getDay()]);

$('.checkout .date').html((morrow.getMonth() + 1) + '月' + morrow.getDate() + '日');
$('.checkout .week').html(weekNames[morrow.getDay()]);
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
$(function(){
	
	$.fn.imgscroll({
		imgrate			 : 600/160, 
	    partent_div      : 'headers',
	});
	$('.ui_square_h').height($('.ui_square_h').width());	
	$('.often').on('click',function(){
		toshow($('.often_pull'));
	});
	$('.like').on('click',function(){
    	<!-- 雅思特修改部分 -->
		//toshow($('#collects'));
	});
	$('.close').on('click',function(){
		toclose();
	});
	$('.location').on('click',function(){
		toshow($('.address_pull'));
		var __top = ($('.address_pull').height()-$('.address_index').height())/2;
		$('.address_index').css('top',__top);
	});
	$('.address_index div').on('click',function(){
		var _scltop = $(this).html();
		_scltop=$('#'+_scltop).offset().top+$('.content_pull').scrollTop();
		$('.content_pull').scrollTop(_scltop);
	});
	$('.address_list li').on('click',function(){
		if($(this).html()!=fail_locate){
			toclose();
			$('.location .local').html($(this).html());
			if($(this).html()!='全部')
				$('#city').val($(this).html());
			else
				$('#city').val('');
		}
	});
	$('.search').on('blur',function(){
		address_search($(this).val());
	});
	$('.clear_history').on('click',function(){
		var $confirm = confirm("历史记录清除后将不可恢复，是否继续？");
		if( $confirm ){
			$.get('/index.php/hotel/hotel/clear_visited_hotel?id=<?php echo $inter_id?>',function(data){
				if(data==1)
					$('.history').fadeOut();
			});
		}
	});
	var fill_date =function(data){
			$('.checkin .week').html(weekNames[data.inDate.getDay()] );
			$('.checkin .date').html( (data.inDate.getMonth() + 1) + '月' + data.inDate.getDate() + '日');
			
			$('.checkout .week').html(weekNames[data.outDate.getDay()]);
			$('.checkout .date').html( (data.outDate.getMonth() + 1) + '月' + data.outDate.getDate() + '日');
			
			$('.checkin_time').html(data.dateSpan);
	}
	$('#checkdate').cusCalendar({
		_parent			:'checkdate',
		beginTimeElement:'checkin',
		endTimeElement  :'checkout',
		bTimeValElement :'startdate',
		eTimeValElement :'enddate',
		preSpDate:<?php echo $pre_sp_date; ?>,
		selectedCallBack:function(data){fill_date(data)}
	});
})
</script>
</html>
