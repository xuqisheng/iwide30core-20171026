<?php include 'header.php'?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ggmZIrqw5hOjnXwT7ypK0aIoZXrn4yfS"></script>

<?php echo referurl('css','skin1.css',2,$media_path) ?>

<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('js','imgscroll.js',2,$media_path) ?>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<?php echo referurl('js','search.js',2,$media_path) ?>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<style>
    body,html{background:#f1f1f1}
    .checkin_time:after{ content:"晚"}
</style>
<script>
    var fail_locate='-';
    var latitude=0;
    var longitude=0;
    var city='-1';
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
        $.get('<?php echo Hotel_base::inst()->get_url('GET_NEAR_HOTEL')?>',
            {
                lat:lati,
                lnt:logi
            },function(data){
                var tmp='';
                $.each(data,function(i,n){
                    tmp+=' <li><a href="<?php echo Hotel_base::inst()->get_url("INDEX")?>&h='+n.hotel_id+'">'+n.name+'</a></li>';
                });
                $('#near_c').html(tmp);
                $('.near').on('click',function(){
                    toshow($('#near_c_i'));
                    layer($('#near_c_i'));
                });
            },'json');
    }
</script>
<div class="webkitbox justify bg_fff">
	<div style="font-size:0"><?php if (!empty($homepage_set['img'])){?><img class="logo" src="<?php echo $homepage_set['img'];?>"><?php }?></div><!-- 酒店logo位-->
    <div class="pad3">
    <?php if (isset($member->logined)&&$member->logined==0){if (isset($_SERVER['HTTPS'])&&strtolower($_SERVER['HTTPS'])=='on')$scheme='https://';else $scheme='http://';?>
        <a href="<?php echo site_url('membervip/login/index').'?id='.$inter_id.'&redir='.urlencode($scheme.$_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI']);;?>"><em class="iconfont">&#X4B;</em> 登录</a>
    	<a href="<?php echo site_url('membervip/reg/index').'?id='.$inter_id.'&redir='.urlencode($scheme.$_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI']);;?>"><em class="iconfont">&#X4C;</em> 注册</a>
    	<?php }?>
        欢迎您<?php echo empty($member->nickname)?'':' '.$member->nickname;?>
    </div>
</div>
<header class="headers">
    <div class="headerslide">
        <?php foreach ($pubimgs as $pi){?>
            <a class="slideson" href="<?php echo Hotel_base::inst()->get_url($pi['link'],array(),TRUE);?>">
                <img src="<?php echo $pi['image_url'];?>" />
            </a>
        <?php }?>
    </div>
</header>
<form action="<?php echo Hotel_base::inst()->get_url("SRESULT")?>" onsubmit="to_search($(this))" method="post" id="index_search">
    <input type="hidden" id="startdate" name="startdate" value='<?php echo date('Y/m/d',strtotime($startdate));?>' />
    <input type="hidden" id="enddate" name="enddate" value='<?php echo date('Y/m/d',strtotime($enddate));?>' />
    <input type="hidden" id="off" name="off" value='0' />
    <input type="hidden" id="num" name="num" value='20' />
    <input type="hidden" id="latitude" name="latitude" value='' />
    <input type="hidden" id="longitude" name="longitude" value='' />
    <input type="hidden" id="sort_type" name="sort_type" value='distance' />
    <input type="hidden" id="city" name="city" value='<?php echo $first_city;?>' />
    <input type="hidden" id="ec" name="ec" value='[]' />
    <input type="hidden" id="first_local" name="first_local" value='0' />
   <div class="list_style index_list bd_bottom">
	<div class="webkitbox">
        <div class="iconfont">&#x2F; </div>
        <div class="location arrow" style="padding:12px 0">
            <span class="local txtclip"><?php if($inter_id=='a421641095'){echo '广州';}else{echo $first_city;} ?></span>
        </div>
        <a href="<?php echo Hotel_base::inst()->get_url("NEARBY")?>" class="center">
        	<em class="iconfont">&#x36;</em>
            <span id="cc">附近酒店</span>
        </a>
    </div>
	<div class="webkitbox arrow" id="checkdate">
        <div class="iconfont">&#x3B; </div>
        <div class="checkin" id='checkin'>
            <div class="h24 color_888">入住 <span class="week">一</span> </div>
            <span class="date ">1月1日</span>
        </div>
        <div class="checkout" id='checkout'>
            <div class="h24 color_888">离店 <span class="week">一</span></div>
            <span class="date">1月2日</span>
        </div>
        <div class="checkin_time">1</div>
    </div>
	<div class="webkitbox arrow">
        <div class="iconfont">&#x37; </div>
        <div class="keyword" style="padding:12px 0" url="<?php echo Hotel_base::inst()->get_url("AJAX_CITY_FILTER")?>">关键字/位置/名称</div>
    </div>
</div>
<div class="pad3 center martop">
	<button class="isable submitbtn bg_main">查询</button>
</div>
</form>
<!--  三个广告位 -->
<?php if(isset($homepage_set['open']) && $homepage_set['open'] == 2 && !empty($homepage_set['menu'])){?>
<style>
.iconfont.always:after{ content:'\52'}
.iconfont.athour:after{content:'\50'}
.iconfont.collect:after{content:'\2A'}
.iconfont.order:after{content:'\51'}
.iconfont.ticket:after{content:'\A7'}
</style>
<div class="webkitbox others center">
	<?php foreach($homepage_set['menu'] as $k=>$v){?>
		<div class="<?php echo $v['code'];?>" <?php if($v['code'] == 'athour') echo 'href="'.Hotel_base::inst()->get_url("SEARCH",array('type'=>'athour'));?> <?php if($v['code'] == 'order') echo 'href="'.Hotel_base::inst()->get_url("MYORDER").'"';?> <?php if($v['code'] == 'ticket') echo 'href="'.Hotel_base::inst()->get_url("SEARCH",array('type'=>'ticket')).'"';?>>
	    	<div class="img"><em class="iconfont <?php echo $v['code'];?>"></em><!--p class="squareimg"><img src=""></p--></div>
	        <div class="txtclip h24"><?php echo $v['desc'];?></div>
	        <div class="txtclip h22"><?php echo $v['menu_name'];?></div>
	    </div>
	<?php }?>
</div>
<div class="ui_pull often_pull" style="display:none;" onClick="toclose()">
    <div class="bg_fff pull_box center">
        <div class="pull_title color_main pad3">常住酒店</div>
        <ul class="list_style_2 scroll">
        <?php if(!empty($last_orders)){ ?>
       <?php foreach($last_orders as $lo){?>
            <li><a href="<?php echo Hotel_base::inst()->get_url("INDEX",array('h'=>$lo['hotel_id']))?>"><?php echo $lo['hname']; ?></a></li>
            <?php }?>
        <?php } else {?>
         <li>无</li>
        <?php }?>
        </ul>
        <div class="close" style="display:none"><em class="iconfont">&#x27;</em><p>关闭</p></div>
    </div>
</div>
<div class="ui_pull" id="collects" style="display:none;" onClick="toclose()">
    <div class="bg_fff pull_box center">
        <div class="pull_title color_main pad3" >我的收藏</div>
        <ul class="list_style_2 scroll" >
        <?php if(!empty($hotel_collection)) {?>
       		<?php foreach($hotel_collection as $hc){ ?>
            <li><a href="<?php echo Hotel_base::inst()->get_url($hc['mark_link'],array(),TRUE);?>"><?php echo $hc['mark_title'];?></a></li>
            <?php }?>
        <?php }else{?>
            <li>无</li>
        <?php }?>
        </ul>
        <div class="close" style="display:none"><em class="iconfont">&#x27;</em><p>关闭</p></div>
    </div>
</div>
<script>
$(function(){
	$('.others [href]').click(function(){window.location.href=$(this).attr('href');});
	$('.always').click(function(){toshow($('.often_pull'));});
	$('.collect').click(function(){toshow($('#collects'));});
});
</script>
<?php }?>
<div class="ui_pull" id="near_c_i" style="display:none;">
    <div class="bg_fff pull_box center">
        <div class="pull_title color_main pad3">附近酒店</div>
        <ul class="list_style_2 scroll" id='near_c'></ul>
        <div class="close pad3"><em class="iconfont h30">&#x27;</em><p class="h20">关闭</p></div>
    </div>
</div>
<div class="ui_pull address_pull" style="display:none">
    <div class="bg_fff scroll" content_pull>
        <ul class="address_list list_style"><li>全部</li></ul>
        <div class="title h22">当前定位</div>
        <ul class="address_list list_style">
            <li id="cur_city">-</li>
        </ul>
        <?php if(!empty($last_orders)){ ?>
    	<div class="title2 h22">历史城市</div>
            <ul class="address_list list_style">
                <?php foreach($last_orders as $lo){?>
                    <li><?php echo $lo['hcity'];?></li>
                <?php }?>
            </ul>
        <?php }?>
        <?php if(!empty($hot_city)){ ?>
    	<div class="title2 h22">热门城市</div>
        <ul class="address_list list_style hotcity">
                <?php foreach($hot_city as $hc){?>
                    <li><?php echo $hc;?></li>
                <?php }?>
            </ul>
        <?php }?>
        <?php $let=array(); foreach($citys as $ck=>$cs){ $let[]=$ck;?>
            <div class="title h22" id="<?php echo $ck;?>"><?php echo $ck;?></div>
            <ul class="address_list list_style">
                <?php foreach($cs as $c){ ?>
                    <li><?php echo $c['city']; ?></li>
                <?php }?>
            </ul>
        <?php }?>
    </div>
    <div class="address_index h20"><?php foreach($let as $l){?>
            <div><?php echo $l;?></div>
        <?php }?></div>
</div>
<!-- 推荐 -->
<?php if(isset($foot_ads['ads']) && !empty($foot_ads['ads'])){ ?>
<div class="h28 pad3 bd_top bg_fff martop"><em class="iconfont">&#X43;</em> <?php if(isset($foot_ads['title']) && !empty($foot_ads['title'])) echo $foot_ads['title'];else echo '推荐';?></div>
<div class="vote_spread bg_fff">
	<?php foreach($foot_ads['ads'] as $fad){ foreach($fad as $fa){?>
	<a href="<?php echo Hotel_base::inst()->get_url($fa['ad_link'],array(),TRUE);?>">
    	<div class="squareimg"><img src="<?php echo $fa['ad_img'];?>" info="<?php echo $fa['ad_title'];?>"/></div>
        <div class="h28 txtclip"><?php echo $fa['ad_title'];?></div>
        <div class="h22 txtclip"><?php echo $fa['des'];?></div>
    </a>
	<?php }}?>
</div>  
<script>
$(function(){
	var l= $('.vote_spread>*').length;
	if( l<=1) $('.vote_spread .squareimg').css('padding-bottom','40%');
	if( l>1) $('.vote_spread>*').css('width','50%');
	if( l>2) $('.vote_spread>*').css('width','45%');
});
</script>
<?php }?>

</body>
<script>
    $('.local').html($('#city').val());
    $('#ec').val('[]');
    var overmonth = 0;
    var weekNames = [ '日', '一', '二', '三', '四', '五', '六' ];
    var today=new Date(<?php echo strtotime($startdate)*1000; ?>);
    var morrow=new Date((today/1000+86400)*1000);

    $('.checkin .date').html((today.getMonth() + 1) + '月' + today.getDate() + '日');
    $('.checkin .week').html(weekNames[today.getDay()]);

    $('.checkout .date').html((morrow.getMonth() + 1) + '月' + morrow.getDate() + '日');
    $('.checkout .week').html(weekNames[morrow.getDay()]);
	var fill_hotel =function(){removeload();}
    function layer(_this){
        var _h=_this.find('.pull_box').height();
        var _wh=$(window).height();
        _this.find('.pull_box').css('margin-top',(_wh-_h)/2);
        _this.find('.scroll').height(_h-96);
    }
    function to_search(obj){
		if($('#city').val()=='全部'){
			$('#city').val('');
	    }else{
	    	obj.attr('action', obj.attr('action')+'&city='+escape($('#city').val()));
	    }
	}
    $(function(){
        $.fn.imgscroll({
		imgrate			 : 600/290, 
            partent_div      : 'headers',
            circlesize		 : '5px'
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
            _scltop=$('#'+_scltop).offset().top+$('.address_pull [content_pull]').scrollTop();
            $('.address_pull [content_pull]').scrollTop(_scltop);
        });
        $('.address_list li').on('click',function(){
		$('.address_list li').removeClass('color_main');
            if($(this).html()!=fail_locate){
			$(this).addClass('color_main');
                toclose();
                $('.location .local').html($(this).html());
                if($(this).html()!='全部')
                    $('#city').val($(this).html());
                else
                    $('#city').val('');
            }
        });
	$('.clear_history').on('click',function(){
		var $confirm = confirm("历史记录清除后将不可恢复，是否继续？");
		if( $confirm ){
			$.get('<?php echo Hotel_base::inst()->get_url("CLEAR_VISITED_HOTEL");?>',function(data){
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
