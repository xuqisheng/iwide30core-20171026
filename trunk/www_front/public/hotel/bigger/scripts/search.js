/**
 * search.js 搜索页面js
 * @author 赵晓聪
 */
$(function(){
    if($("#city_val").val() == ""){
        $("#city_val").val("全部")
    }
	$('.hotel_search').on('click',function(){
            if($(this).html()!=fail_locate){
            	$(".hotel_search ").removeClass("color1");
                $(this).addClass('color1');
                $('#city_val').val($(this).html());

                if($(this).attr('city') !=undefined){
                    $('#area').val($(this).attr('area'));
                    $('#city').val($(this).attr('city'));
                }else{
                    $('#area').val('')
                    if($(this).html()!='全部')
                        $('#city').val($(this).html());
                    else
                        $('#city').val('');
                }
            }
            $("#search_hotel").click();
        });
	$('#checkdate').cusCalendar({
		beginTime:new Date(),
		endTime  :new Date((new Date()/1000+86400)*1000),
		success	 :function(date){
			
			var N =function(num){
				if(num<10) return '0'+num;
				else return num;
			}
			$('.checkin .day').html(N(date.beginTime.getDate()));
			$('.checkin .date').html(date.beginTime.getFullYear()+'/'+N(date.beginTime.getMonth()+1));
			
			$('.checkout .day').html(N(date.endTime.getDate()));
			$('.checkout .date').html(date.endTime.getFullYear()+'/'+N(date.endTime.getMonth()+1));
            $("#startdate").val(date.beginTime.getFullYear()+'/'+N(date.beginTime.getMonth()+1)+'/'+N(date.beginTime.getDate()))
            $("#enddate").val(date.endTime.getFullYear()+'/'+N(date.endTime.getMonth()+1)+'/'+N(date.endTime.getDate()))
		}
	});

    new Swiper('.search_img_wrapper', {
        spaceBetween: 10
    });

	
   $("#search_click").on("click",function(){
   		toshow($(".city_wrapper"));
         setheight();
   		// new Swiper('.city-list-swiper', {
	    //     slidesPerView: 'auto'
	    // });
   });

    var cache = [];
    try{
		cache = JSON.parse(window.localStorage['hotel_cache']);
	}catch(e){
		console.log(e);
		cache = [];
	}
    function saveCache(){
		var bool 		= true;
		var _cache 		= {};
		var index		= 0;
		// _cache.ec  		= $('#ec').val();
		_cache.city		= $('#city_val').val();
		_cache.startTxt = $('.checkin .date').html() +'/'+$('.checkin .day').html();
		_cache.endTxt	= $('.checkout .date').html() +'/'+$('.checkout .day').html();
		// _cache.key		= key?key:'';
		$.each(cache,function(m,n){
			if(n.city==_cache.city&& n.startTxt==_cache.startTxt&& n.endTxt==_cache.endTxt){
				bool=false;
				index=m;
				return false;
			}
		});
		if( bool){cache = _cache }
		else{
			cache.splice(index,1,_cache);
		}
		if(cache.length>5)cache.splice(0,1);
		try{
			window.localStorage['hotel_cache']=JSON.stringify(cache);
		}catch(e){
			console.log(e);
		}
	}

    $(".nearby").on("click",function(){
        $("#index_search").attr('action', $("#index_search").attr('action')+'&nearby=1');
        $("#city").val("");
        $("#index_search").submit();
    })
    function to_search(obj){
        if($('#city_val').val()=='全部'){
            $('#city_val').val('');
        }else if($('#city_val').val()!=undefined){
            obj.attr('action', obj.attr('action')+'&city_val='+escape($('#city_val').val())+'&area='+escape($('#area').val()));
        }else{
            obj.attr('action', obj.attr('action')+'&city_val='+escape($('#city_val').val()));
        }
    }
	$("#search_hotel").on("click",function(){
        to_search($("#index_search"))
		$("#index_search").submit();
		saveCache();
	})
	
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
        });
        //若服务请求失败，则运行以下函数
        geocoder.setError(function() {
            $('.local').html(fail_locate);
            $('#cur_city').html(fail_locate);
        });
    }
    $(".city_letter_list").on("click","div",function(){
        var _letter = $(this).html();
        $(".city_list_wrap").children().each(function(){
            if(_letter == $(this).attr("data-letter")){
                $('.city_wrapper').scrollTop($(this).offset().top + $('.city_wrapper').scrollTop());
            }
        })
    });

    $(".always").on("click",function(e){
        $(".always_book").show();
         e.stopPropagation();
        console.log("yes")
    });
    $(".collect").on("click",function(e){
         e.stopPropagation();
        $(".my_collect").show();
    });
    $('.close,.button').on("click",function(){
         $("#always_book, #my_collect").hide();
    })
})