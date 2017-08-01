<?php include 'header.php'?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ggmZIrqw5hOjnXwT7ypK0aIoZXrn4yfS"></script>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('js','imgscroll.js',2,$media_path) ?>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<?php echo referurl('js','search.js',2,$media_path) ?>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<style>
    body,html{background:#fff}
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
        $.get('<?php echo Hotel_base::inst()->get_url("GET_NEAR_HOTEL");?>',
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
<header class="headers">
    <div class="headerslide">
        <?php foreach ($pubimgs as $pi){?>
            <a class="slideson" href="<?php echo Hotel_base::inst()->get_url($pi['link'],array(),TRUE);?>">
                <img src="<?php echo $pi['image_url'];?>" />
            </a>
        <?php }?>
    </div>
</header>
<form action="<?php echo Hotel_base::inst()->get_url("SRESULT",array('type'=>$type))?>" onsubmit="to_search($(this))" method="post" id="index_search">
    <input type="hidden" id="startdate" name="startdate" value='<?php echo date('Y/m/d',strtotime($startdate));?>' />
    <input type="hidden" id="enddate" name="enddate" value='<?php echo date('Y/m/d',strtotime($startdate));?>' />
    <input type="hidden" id="off" name="off" value='0' />
    <input type="hidden" id="num" name="num" value='20' />
    <input type="hidden" id="latitude" name="latitude" value='' />
    <input type="hidden" id="longitude" name="longitude" value='' />
    <input type="hidden" id="sort_type" name="sort_type" value='distance' />
    <input type="hidden" id="city" name="city" value='<?php echo $first_city;?>' />
    <input type="hidden" id="ec" name="ec" value='[]' />
    <input type="hidden" id="first_local" name="first_local" value='0' />
    <div class="list_style index_list ">
        <div class="webkitbox">
            <div class="location arrow bd_right">
                <div class="h22 color_888">入住城市</div>
                <div class="local txtclip h36"><?php if($inter_id=='a421641095'){echo '广州';}else{echo $first_city;} ?></div>
            </div>
            <a class="near color_main center" href='<?php echo Hotel_base::inst()->get_url("NEARBY")?>'>
                <em class="iconfont" style="font-size:25px">&#x24;</em>
                <p class="h20" id="cc">附近酒店</p>
            </a>
        </div>
        <div class="webkitbox" id="checkdate" style="border:0">
            <div class="checkin arrow bd_bottom" id='checkin' style="padding-bottom:8px">
                <div class="h22 color_888">入住日期</div>
                <span class="date">1月1日</span>
                <span class="h24 color_888 week">一</span>
            </div>
        </div>
        <div class="webkitbox searchbox arrow" style="padding:10px 0">
            <input name="keyword" placeholder="关键字/位置/名称" readonly class="keyword" url="<?php echo Hotel_base::inst()->get_url("AJAX_CITY_FILTER")?>">
        </div>
    </div>
    <div class="pad3 center martop">
        <button class="isable submitbtn bg_main">查询</button>
    </div>
</form>
<div class="pad3">
    <div class="often_like webkitbox pad3 <?php if ($inter_id!='a476756979'){?>bg_F8F8F8<?php }?> bdradius">
        <div class="often ">
            <em class="iconfont color_main h34">&#x28;</em> <span>常住酒店</span>
        </div>
        <div class="like bd_left" style="padding-left:8px">
            <em class="iconfont color_main h34">&#x2a;</em> <span>我的收藏</span>
        </div>
    </div>
</div>
<div class="ui_pull" id="collects" style="display:none;" onClick="toclose()">
    <div class="bg_fff pull_box center">
        <div class="pull_title color_main pad3" >我的收藏</div>
        <ul class="list_style_2 scroll" >
            <?php if(!empty($hotel_collection)) {?>
                <?php foreach($hotel_collection as $hc){ ?>
                    <li><a href="<?php echo Hotel_base::inst()->get_url($hc['mark_link'],array(),TRUE);?>" onclick="stop_bubble()"><?php echo $hc['mark_title'];?></a></li>
                <?php }?>
            <?php }else{?>
                <li>无</li>
            <?php }?>
        </ul>
        <div class="close" style="display:none"><em class="iconfont">&#x27;</em><p>关闭</p></div>
    </div>
</div>
<div class="ui_pull" id="near_c_i" style="display:none;">
    <div class="bg_fff pull_box center">
        <div class="pull_title color_main pad3">附近酒店</div>
        <ul class="list_style_2 scroll" id='near_c'></ul>
        <div class="close pad3"><em class="iconfont h30">&#x27;</em><p class="h20">关闭</p></div>
    </div>
</div>
<div class="ui_pull often_pull" style="display:none;" onClick="toclose()">
    <div class="bg_fff pull_box center">
        <div class="pull_title color_main pad3">常住酒店</div>
        <ul class="list_style_2 scroll">
            <?php if(!empty($last_orders)){ ?>
                <?php foreach($last_orders as $lo){?>
                    <li><a href="<?php echo Hotel_base::inst()->get_url("INDEX",array('h'=>$lo['hotel_id']))?>" onclick="stop_bubble()"><?php echo $lo['hname']; ?></a></li>
                <?php }?>
            <?php } else {?>
                <li>无</li>
            <?php }?>
        </ul>
        <div class="close" style="display:none"><em class="iconfont">&#x27;</em><p>关闭</p></div>
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
            <div class="title h22">历史城市</div>
            <ul class="address_list list_style">
                <?php foreach($last_orders as $lo){?>
                    <li><?php echo $lo['hcity'];?></li>
                <?php }?>
            </ul>
        <?php }?>
        <?php if(!empty($hot_city)){ ?>
            <div class="title h22">热门城市</div>
            <ul class="address_list list_style">
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
<?php if(isset($foot_ads['ads']) && !empty($foot_ads['ads'])){ ?>
    <div class="h28" style="padding:3% 0;"><?php echo $foot_ads['title']?></div>
    <div class="bg_fff" style="padding:0 10px 10px 0">
    <div class="vote_spread">
        <?php foreach($foot_ads['ads'] as $fad){ foreach($fad as $fa){?>
            <a href="<?php echo Hotel_base::inst()->get_url($fa['ad_link'],array(),TRUE);?>"><img src="<?php echo $fa['ad_img'];?>" info="<?php echo $fa['ad_title'];?>"/></a>
        <?php }}?>
    </div>
    </div>
    <script>
        $(function(){
            var l= $('.vote_spread>*').length;
            if( l<=1) $('.vote_spread .squareimg').css('padding-bottom','40%');
            if( l<=2) $('.vote_spread>*').css({'-webkit-box-flex':'1','box-flex':'1'});
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

    function layer(_this){
        var _h=_this.find('.pull_box').height();
        var _wh=$(window).height();
        _this.find('.pull_box').css('margin-top',(_wh-_h)/2);
        _this.find('.scroll').height(_h-96);
    }
    $(function(){
        $.fn.imgscroll({
            imgrate			 : 600/160,
            partent_div      : 'headers',
            circlesize		 : '5px'
        });
        $('.often').on('click',function(){  toshow($('.often_pull'));	layer($('.often_pull'));});
        $('.like').on('click',function(){  toshow($('#collects'));      layer($('#collects'));});
        $('.close').on('click',function(){toclose();});

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
            if($(this).html()!=fail_locate){
                $(this).addClass('color_000');
                toclose();
                $('.location .local').html($(this).html());
                if($(this).html()!='全部')
                    $('#city').val($(this).html());
                else
                    $('#city').val('');
            }
        });
        var fill_date =function(data){
            $('.checkin .week').html(weekNames[data.inDate.getDay()] );
            $('.checkin .date').html( (data.inDate.getMonth() + 1) + '月' + data.inDate.getDate() + '日');

            $('.checkin_time').html(data.dateSpan);
        }
        
    })


    var fill_hotel =function(){$('.page_loading').remove();}
    function to_search(obj){
    	if($('#city').val()=='全部'){
    		$('#city').val('');
        }else{
        	obj.attr('action', obj.attr('action')+'&city='+escape($('#city').val()));
        }
    }
</script>
</html>
