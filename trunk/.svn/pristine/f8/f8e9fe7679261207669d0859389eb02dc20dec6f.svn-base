/**
 * search_results.js 搜索结果js
 * @author 赵晓聪
 */
function go_hotel($url){
    location.href=$url+"&start="+$('#startdate').val()+"&end="+$('#enddate').val();
}

$(function(){
    if(getQueryString('nearby') == 1){
        
       if($('#first_local').val()==0){
            to_locate(1);
            wx.ready(function(){
                    to_locate(1);
            });
        }
    }else{
        pageloading();
    }
})
//fill_hotel('html',0,true);
var isfirst=true;
var isload =false;
var load_times = 1;
var each_nums = 1;   //每次加载的酒店数目
var city='none';
// 定位参数

function fill_hotel(fill_way,offset,first){
    var src=$("#ajax_src").val();
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

    $.get(src,{
        start:$('#startdate').val(),
        end:$('#enddate').val(),
        off:off,
        num:num,
        lat:lat,
        lnt:lng,
        sort_type:$('#sort_type').val(),
        city:$('#city').val(),
        area:$('#area').val(),
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
                {$('.hotel_list').append(tmp);}
            else
                $('.hotel_list').html(tmp);
        }else{
            $('.hotel_list').html('');
            if(first)$('.blankpage').show();
            else{
                showload('无更多结果',false);
            }
        }
        removeload();
        isload = false;
    },'json');
}
function to_locate(first){
    pageloading('定位中');
    wx.getLocation({
    type:'gcj02',
    success: function (res) {
            latitude  = res.latitude; // 纬度，浮点数，范围为90 ~ -90
            longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
            var speed = res.speed; // 速度，以米/每秒计
            var accuracy = res.accuracy; // 位置精度

            if(first==1){
                locate_city(latitude,longitude,1);
            }else{
                locate_city(latitude,longitude,0);
            }
        },
    cancel: function (res) {
            $('.local').html(fail_locate);
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
        var _h=result.detail.addressComponents;
        // $('.local').html(_h.district+_h.street+_h.streetNumber);
        $('#latitude').val(lat);
        $('#longitude').val(lng);
        $('#city').val(result.detail.addressComponents.city);
        if(first==1){
            fill_hotel('html',-1,isfirst);}
        else{
            fill_hotel('html',0);}
    });
    //若服务请求失败，则运行以下函数
    geocoder.setError(function() {
        // $('.local').html(fail_locate);
        $('#city').val('北京市');

        fill_hotel('html',0,true);
    });
}
    var pull_bol = true;
    $("#j_screen").on("click",function(){
        // if(pull_bol){
        //     pageloading();
        //     var url = $(this).attr('url');
        //     $.get(url,{
        //         city:$('#city').val()
        //         // city:"全部"
        //     },function(data){
        //         removeload();
                // if(data.s==1){
                //     $('#search_region').html(data.data);
        //             toshow($('#serach_whole'));
        //             $("#j_window").hide();
        //             $(".whole_all_eject").show();
        //             pull_bol = false;
        //         }else{
        //             $.MsgBox.Alert(data.data);
        //         }
        //     },'json');
        // }else{
            toshow($(".city_wrapper"));
            setheight();
        // }
    
    });
    $("#j_close").on("click",function(){
        toclose();
    });

    $("#search_sure").on("click",function(){
        $("#j_window").show();
        $(".whole_all_eject").hide();
    })

    $("#search_react").on("click",function(){
        $(".results_search_left").find("p").eq(0).click();
        $("#region_search").find(".bd_bottom").eq(0).addClass("active").siblings().removeClass("active");
    })
    $(".results_search_right p").on("click",function(){
        $(this).addClass("active").siblings().removeClass("active");
    })
    $(".results_search_left p").on("click",function(){
        $(this).addClass("active").siblings().removeClass("active");
    })
    $('#checkdate').cusCalendar({
        beginTime:parserDate($("#startdate").val()),
        endTime  :parserDate($("#enddate").val()),
        success  :function(date){ 
            var N =function(num){
                if(num<10) return '0'+num;
                else return num;
            }
            $('.checkin').html(N(date.beginTime.getMonth()+1)+'/'+N(date.beginTime.getDate()));
            
            $('.checkout').html(N(date.endTime.getMonth()+1)+'/'+N(date.endTime.getDate()));
            $("#startdate").val(date.beginTime.getFullYear()+'/'+N(date.beginTime.getMonth()+1)+'/'+N(date.beginTime.getDate()))
            $("#enddate").val(date.endTime.getFullYear()+'/'+N(date.endTime.getMonth()+1)+'/'+N(date.endTime.getDate()))

            if(getQueryString('nearby') == 1) return false;
            fill_hotel('html',0,true);
            
        }
    });
    function loadsc () {
        new IScroll('#region_search', { scrollbars: true,
        mouseWheel: true,
        click:true,
        interactiveScrollbars: true,
        shrinkScrollbars: 'scale',
        fadeScrollbars: true });
    }
    function loadcity () {
        new IScroll('#city_search_result', { scrollbars: true,
        mouseWheel: true,
        click:true,
        interactiveScrollbars: true,
        shrinkScrollbars: 'scale',
        fadeScrollbars: true });
    }

    $(".search_results_sort").on("click",function(){
        $(this).toggleClass("rise");
        $(this).addClass("sort_Active").siblings(".search_results_sort").removeClass("sort_Active");
        if($(this).hasClass("rise")){
            $('#sort_type').val($(this).attr('sort-up'));
         }else{
            $('#sort_type').val($(this).attr('sort-down'));
         }
         if($('#sort_type').val() == "distance"){
             $('#off').val(0);
            to_locate(1);
        }else{
            fill_hotel('html',0);
            pageloading();

        }
        
    })

var  startX ,startY;
$(document).bind('touchstart',function(e){
    startX = e.touches[0].pageX,
    startY = e.touches[0].pageY;
});
$(document).on('touchmove',function(e){
    endX = e.touches[0].pageX,
    endY = e.touches[0].pageY;
    //获取滑动距离
    distanceX = endX-startX;
    distanceY = endY-startY;
    // console.log(distanceY+'----'+($(document).height()-$(window).height())*0.4+'----'+$('body').scrollTop())
    if(distanceY<0&&($(document).height()-$(window).height())*0.4<=$('body').scrollTop()){
        if (!isload){
            e.preventDefault();
            fill_hotel('append',-1,false);
            isload  = true;
        }
        else{
            showload();
        }
    }
})