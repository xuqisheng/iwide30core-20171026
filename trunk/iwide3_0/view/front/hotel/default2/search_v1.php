<?php include 'header.php'?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('js','imgscroll.js',2,$media_path) ?>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ggmZIrqw5hOjnXwT7ypK0aIoZXrn4yfS"></script>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
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
        $.get('<?php echo Hotel_base::inst()->get_url("*/*/get_near_hotel")?>',
            {
                lat:lati,
                lnt:logi
            },function(data){
                var tmp='';
                $.each(data,function(i,n){
                    tmp+=' <li><a href="<?php echo Hotel_base::inst()->get_url('*/*/index')?>&h='+n.hotel_id+'">'+n.name+'</a></li>';
                });
                $('#near_c').html(tmp);
                $('.near').on('click',function(){
                    toshow($('#near_c_i'));
                    layer($('#near_c_i'));
                });
            },'json');
    }
</script>
<style>
/*专题页样式*/
body,html{background:#f5f5f5}
.qi:after{content:"起"}
.middle>*{vertical-align:middle}
.act_select{position:absolute; top:0; left:0; width:100%; opacity:0; height:100%}
.headers{position:absolute !important; top:0; z-index:0; min-height:80px}
#index_search,.address_layer{position:relative; z-index:10}
.index_list{width:93%; margin:auto; border-radius:6px; overflow:hidden; padding:0 20px; box-shadow:0px 0px 5px rgba(215,215,215,0.4)}
.index_list .webkitbox>*{padding-top:10px; padding-bottom:10px}
.checkin_time{ position:relative; text-align:center}
.checkin_time span{background:#fff; position:relative; z-index:9; display:inline-block; height:1.4em;}
.checkin_time span:after{content:"晚"}
.checkin_time:after{content:""; height:100%; width:1px; background:#e4e4e4; position:absolute; left:50%; top:0;}
.filter_option{background:#fff; color:inherit; position:static; padding:10px 0}
.filter_option>*{border-right:1px solid #e4e4e4; padding:0}
.hotel_list .btn_void{ background:#fecd83; padding:0 3px; color:#fff !important;}
.hotel_list .squareimg{border-radius:2px; overflow:hidden}
.hotel_list .color_link{display:none}
#act_layer{-webkit-box-align:center;}
#act_layer .pull_box>*{ padding:10px;}
#act_layer .pull_box{margin:auto; max-height:60%; overflow:auto; border-radius:5px; padding-bottom:10px;}
#act_layer .btn{ display:inline-block; width:125px; padding:5px; border-radius:4px;}
.hot_city *{display:inline-block; border:0.5px solid #d0d0d0; padding:1px 0; border-radius:1em; min-width:15.5%; text-align:center; margin-top:10px; margin-right:5%; padding:0 4px;}
.hot_city *:nth-child(5n){margin-right:0}
.address_layer {height:100%}
.address_layer .index_list{height:90%}
.address_list {padding:0; position:absolute; top:100%; left:0; width:100%; background:#fff; z-index:10; display:none;}
#search_hotel{display:inline-block; padding:0 5px 0 20px}
</style>
<header class="headers">
    <div class="headerslide">
        <a class="slideson" href="#">
       	 <img src="<?php if(isset($row['intro_img'])) echo $row['intro_img'];?>"/>
        </a>
    </div>
</header>
<form action="<?php echo Hotel_base::inst()->get_url('*/*/sresult')?>" onsubmit="to_search($(this))" method="post" id="index_search">
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
    <div class="center middle relative">
    	<div class="pad10 color_fff h32">
			<span class="txtclip" style=" display:inline-block;max-width:80%;"><?php foreach ($list as $t){ if(isset($row) && $row['id']==$t['id']) echo $t['act_name'];}?></span>
            <span class="iconfont" style="font-size:5px; vertical-align:middle">&#x34;</span>
        </div>
        <select class="act_select">
        <?php foreach ($list as $t) {?>
            <option value="<?php echo $t['id']?>" <?php if(isset($row) && $row['id']==$t['id']) echo 'selected';?>><?php echo $t['act_name'];?></option>
        <?php }?>
        </select>
    
    </div>
    <div class="list_style_1 index_list bd_bottom">
        <div class="webkitbox">
            <div class="location" style="padding:15px 0">
                <div class="h22 color_888 hide">入住城市</div>
                <div class="local txtclip h30"><?php echo $first_city; ?></div>
            </div>
            <div class="iconfont txt_r" id="search_hotel">&#x2c;</div>
        </div>
        <div class="webkitbox center" id="checkdate">
            <div class="checkin" id='checkin' style="padding-bottom:8px">
                <div class="h22 color_888">入住日期</div>
                <span class="date color_main">1月1日</span>
                <span class="h24 color_888 week hide">一</span>
            </div>
            <div class="checkin_time h24"><span>1</span></div>
            <div class="checkout" id='checkout' style="padding-bottom:8px">
                <div class="h22 color_888">离店日期</div>
                <span class="date color_main">1月1日</span>
                <span class="h24 color_888 week hide">一</span>
            </div>
        </div>
    </div>
    <div class="pad3 center martop hide">
        <button class="isable submitbtn bg_main" onBlur="fu(this)">查询</button>
    </div>
    <div class="pad12 bg_fff h24 martop linkblock" onClick="toshow($('#act_layer'))">
        <span class="iconfont color_C3C3C3">&#x53;</span> 活动详情
    </div>
    
    <div class="filter_option webkitbox martop">
        <div sort_tag="default" class="color_main">推荐排序</div>
        <div sort_tag='price_down'>价格<em class="iconfont h20">&#x3e;</em></div>
        <div sort_tag='comment_score'>评分<em class="iconfont h20">&#x3e;</em></div>
    </div>
    <div class="hotel_list list_style_2 bd_top">
        
    </div>
</form>

<div class="address_layer" style="display:none">
	<div class="center color_fff pad10"><b>选择目的地</b></div>
    <div class="list_style_1 index_list bd_bottom">
        <div class="webkitbox">
            <div class="relative">
                <span class="iconfont txt_r">&#x2c;</span>
				<input placeholder="目的地/品牌/商圈" id="input_search"/>
                <div class="address_list list_style_1 scroll">
                	<div url="baidu.com">广州</div><div>广州</div>
                </div>
            </div>
        </div>
         <div class="webkitbox" id="hotCity">
            <div>
                <div class="h24">推荐城市</div>

                     <div class="hot_city h24">
                    <?php foreach($hot_city as $cs){?>
                            <span class="item"><?php echo $cs['city']; ?></span>
                    <?php }?>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="ui_pull webkitbox" id="act_layer" style="display:none">
	<div class="pull_box bg_fff center ">
    	<div class="bg_main"><?php if(isset($row)) echo $row['act_name']?></div>
        <div style="text-align:justify; word-break:break-all; max-height:200px" class="scroll"><?php if(isset($row)) echo nl2br($row['act_intro'])?></div>
        <div class="bg_main btn" onClick="toclose()">我知道了</div>
    </div>
</div>
<div class="ui_none"  style="display:none">
    <div>没有搜索到相关结果~</div>
</div>
</body>
<script>
    var isfirst=true;
    var isload =false;
    var load_times = 1;
    var each_nums = 1;     //定义每次加载的酒店数目
    $('.local').html($('#city').val()!=''?$('#city').val():'全部');
    $('#ec').val('[]');
    var overmonth = 0;
    var weekNames = [ '日', '一', '二', '三', '四', '五', '六' ];
    var today=new Date(<?php echo strtotime($startdate)*1000; ?>);
    var morrow=new Date(<?php echo strtotime($enddate)*1000; ?>);

    $('.checkin .date').html((today.getMonth() + 1) + '月' + today.getDate() + '日');
    $('.checkin .week').html(weekNames[today.getDay()]);

    $('.checkout .date').html((morrow.getMonth() + 1) + '月' + morrow.getDate() + '日');
    $('.checkout .week').html(weekNames[morrow.getDay()]);
	function hidesearch(){
		$('.address_layer').hide();
		$('#index_search').show();
	}
    function go_hotel($url){
        location.href=$url+"&start="+$('#startdate').val()+"&end="+$('#enddate').val();
    }
	var myGeo = new BMap.Geocoder();
	function getmap(city,key){
		myGeo.getPoint(city, function(point){
			if (point) {
				var local = new BMap.LocalSearch(point, {
					onSearchComplete:function(results){
						console.log(results)
						var str = '';
						var data = results.mr; //results.xr;
						for(var i = 0;i<data.length;i++){
							str += '<div onclick="result_list_click(this)" filter="bdmap" code="'+data[i].point.lat+','+data[i].point.lng+','+data[i].title+'" >'+data[i].title+'</div>';
						}
						$('.address_list').append(str);
					}
				});
				local.search(key,{forceLocal:true});
			}
		}, city);
	}
	function result_list_click(_this){
		_this=$(_this);
        $('.address_list').hide();
        $('.ui_none').hide();
		hidesearch();
		if( !_this.hasClass('_alink')){
			$('.address_list').hide();
			extra_condition={};
			extra_condition[_this.attr('filter')]=_this.attr('code');
			$('#ec').val(JSON.stringify(extra_condition));
			window.sessionStorage.ec=$('#ec').val();
			fill_hotel('html',0,true);
		}
	}
	function search_hotel(keyword){
        var tc_id = '<?php echo $row['id']?>';
        if(tc_id == 0){
            $('.address_list').prepend('');
            return;
        }
		$.get('<?php echo site_url('hotel/check/ajax_hotel_search').'?id='.$inter_id;?>',{
			keyword:keyword,
            tc_id:tc_id,
			city:$('#city').val()
		},function(data){
            removeload();
			if(data.s==1){
				var html = data.data.replace(/<li/g,'<div').replace(/<\/li>/g,'</div>');
				$('.address_list').prepend(html);
			}else{
				return '';
			}
		},'json');
	}
	function _alink_click(url){
		//extra_condition={};
		pageloading('',0.6);
		hidesearch();
		$('#ec').val('');
		window.location.href=url+"&start="+$('#startdate').val()+"&end="+$('#enddate').val();
	}

        function fill_hotel(fill_way,offset,first){
        //    console.log($('#city').val());
            city = $('.local').html();
            if(city=='全部'){
                city = '';
            }
            tmp='';
            var off=$('#off').val()*1;
            var num=parseInt($('#num').val())+each_nums*parseInt(load_times);
            load_times = parseInt(load_times) + 1
            if(offset!=-1){
                off=offset;
            }
            if($('.hotel_list').find('.item').length<=0){
                off=0;
                num=num+$('#off').val()*1;
            }

                var lat=$('#latitude').val();
                var lng=$('#longitude').val();
            if($('#ec').val()!=undefined && $('#latitude').val()=='' && $('#longitude').val()==''){
                var map = JSON.parse($('#ec').val());
                if(map.bdmap!=undefined){
                    var lat=map.bdmap[0];
                    var lng=map.bdmap[1];
                }
            }
			pageloading('',0.3);
            var tc_id = '<?php echo $row['id']?>';
            if(tc_id == 0){
                $('.hotel_list').html('');
                $('.ui_none').show();
                removeload();
                return;
            }
            $.get('<?php echo site_url('hotel/check/ajax_hotel_list').'?id='.$inter_id;?>',{
                start:$('#startdate').val(),
                end:$('#enddate').val(),
                off:off,
                num:num,
                lat:lat,
                lnt:lng,
                sort_type:$('#sort_type').val(),
                city:city,
                tc_id:tc_id,
                ec:$('#ec').val()
            },function(data){
                if(data.s==1){
                    $('.hotel_list').html('');
                    $('.ui_none').hide();
                    tmp=data.data;
                    $('#off').val(off+$('#num').val()*1);
                    if(fill_way=='append')
                        $('.hotel_list').append(tmp);
                    else
                        $('.hotel_list').html(tmp);
                }else{
                    $('.hotel_list').html('');
                    $('.ui_none').show();
                }
                removeload();
            },'json');
        }

    $(function(){
		
        $.fn.imgscroll({
            imgrate			 : 600/160,
            partent_div      : 'headers',
            circlesize		 : '5px'
        });
        fill_hotel('html',0,true);
        var fill_date =function(data){
            $('.checkin .week').html(weekNames[data.inDate.getDay()] );
            $('.checkin .date').html( (data.inDate.getMonth() + 1) + '月' + data.inDate.getDate() + '日');

            $('.checkout .week').html(weekNames[data.outDate.getDay()]);
            $('.checkout .date').html( (data.outDate.getMonth() + 1) + '月' + data.outDate.getDate() + '日');

            $('.checkin_time >span').html(data.dateSpan);
        }
        $('#checkdate').cusCalendar({
            _parent			:'checkdate',
            beginTimeElement:'checkin',
            endTimeElement  :'checkout',
            bTimeValElement :'startdate',
            eTimeValElement :'enddate',
            preSpDate:<?php echo $pre_sp_date; ?>,
            minSelect:<?php echo $minSelect; ?>,
            selectedCallBack:function(data){
				fill_date(data);
				fill_hotel('html',0,true);
			}
        });
		$('.location').click(function(){
			$('.address_layer').show();
			$('#index_search').hide();
		});
		$('#input_search').bind('blur',function(){
			if($(this).val()==''){
                $('.address_list').hide();
                return;
            }
			var val = $(this).val();
			$('.address_list').show();
			var _h = $('.address_layer .index_list').outerHeight();
			_h = _h-$('.address_layer .address_list').parent().outerHeight();
			$('.address_layer .address_list').height(_h);
			 /*搜索城市结果点击事件*/
			 
			pageloading('',0.3);
			$('.address_list').html('');
			search_hotel(val);
			getmap($('#city').val()!='全部'?$('#city').val():'广州',val);
		});
		 /*热门城市点击事件*/
		$('.address_layer .item').click(function(){
			hidesearch();
			$('.local').html($(this).html());
            fill_hotel('html',0,true);
		});
		$('.filter_option div').click(function(){
			$(this).addClass('color_main').siblings().removeClass('color_main');
            $('#sort_type').val($(this).attr('sort_tag'));
            fill_hotel('html',0);
		})
        $('.act_select').change(function(){
            window.location.href = '<?php echo site_url('hotel/hotel/thematic_index').'?id='.$inter_id.'&tc_id=';?>'+ $(this).val();
        })
    });
	
</script>
</html>
