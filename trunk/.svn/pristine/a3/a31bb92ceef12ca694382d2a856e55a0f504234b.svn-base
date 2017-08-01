// JavaScript Document

var tmphtml = '<div class="filter_pull ui_pull"></div>';
var myGeo = new BMap.Geocoder();
var extra_condition={};
// 将地址解析结果显示在地图上,并调整地图视野
var getmap =function(city,key){
    myGeo.getPoint(city, function(point){
        if (point) {
            var local = new BMap.LocalSearch(point, {
                onSearchComplete:function(results){
                    var str='<div class="mar_t60"><div class="h24 color3">搜索到以下地标</div>';
                    console.log(results)
                    var data = results.vr;
                    for(var i = 0;i<data.length;i++){
                        str += '<div class="color3 h32 city_result_rows" filter="bdmap"  code="'+data[i].point.lat+','+data[i].point.lng+','+data[i].title+'" data-title="'+data[i].title+'">';
                        str += '<em class="iconfont color3 h26 mar_r30 s_ico">&#xE026;</em>';
                        str += '<p><a class="color3" href="javascript:;">'
                        str += data[i].title
                        str += '</a></p></div>'
                    }
                    str += '</div></div>';
                    eval("str = str.replace(/" + $j_input.val() + "/g,'<font color=white>" + $j_input.val() + "</font>')");
                    $('#city_search_result').append(str);
                    $('.city_result_rows').unbind('click',result_list_click);
                    $('.city_result_rows').bind('click',result_list_click);   
                    if($("#city_search_result p").length <= 0){
                        $("#city_content_wrapper").hide();
                        $("#city_search_result").hide();
                        $("#city_no_search_result").show();
                    }
                }
            });
            local.search(key,{forceLocal:true});
        }else{
                //error do something
        }
    }, city);
}
function result_list_click(){
    _this=$(this);
    if( !_this.hasClass('_alink')){
        extra_condition={};
        extra_condition[_this.attr('filter')]=_this.attr('code');
        $('#ec').val(JSON.stringify(extra_condition));
        $('.searchbox input').val(_this.html());
        recordsearch(_this)
        if($('#index_search').length>0){
             $('#index_search').submit();
        }else{
            $('.result_list').hide();
            pageloading('正在筛选',0.1);
            fill_hotel('html',0);
            toclose();
        }
    }else{
        recordsearch(_this)
    }
}
function baidumap(){
    if($('.result_list .address_list li').length<=5){
        var _val='北京市';
        if ($('#city').val()!=''&&$('#city').val()!=undefined)
            _val=$('#city').val()
        getmap(_val,$j_input.val());
    }
}
$j_input = $("#city_search_content");
$j_input.on("change",function(e){
        $('#city_search_result').html("")
        if ($j_input.val() == "") {
            $("#city_content_wrapper").show();
            $("#city_search_result").hide();
            $("#city_no_search_result").hide();
            $("#city_search_clear").hide();
            return false;
        }
        $("#city_search_clear").show();
        $.get('../check/ajax_hotel_search',{
                keyword:$(this).val(),
                city:$('#city').val()
            },function(data){
                if(data.s != 0){
                    eval("var _str = data.data.replace(/" + $j_input.val() + "/g,'<font color=white>" + $j_input.val() + "</font>')");
                    $('#city_search_result').append(_str);
                }
                $("#city_content_wrapper").hide();
                $("#city_search_result").show();
                $("#city_no_search_result").hide();
                baidumap();
        },'json');  

})
$("#city_search_clear").on("click",function(){
    $j_input.val("");
    $("#city_content_wrapper").show();
    $("#city_search_result").hide();
    $("#city_no_search_result").hide();
    $("#city_search_clear").hide();
});
var searchcache = [];
try{
    searchcache = JSON.parse(window.localStorage['searchcache']);
}catch(e){
    searchcache = [];
}
function setdate(date){
    var _date = date.split("/");
    _date =  _date[1]+'月'+_date[2]+'日';
    return _date
}
function recordsearch(obj){
    var _searchcache  = {};
    _searchcache.val = $("#city_val").val()+'&nbsp;&nbsp;&nbsp;'+setdate($("#startdate").val())+'-'+setdate($("#enddate").val()) + '&nbsp;&nbsp;' + obj.attr("data-title");
    _searchcache.ec = $('#ec').val()
    _searchcache.link =  obj.attr("data-href")
    searchcache.push(_searchcache)
    if(searchcache.length>2)searchcache.splice(0,1);
    try{
        window.localStorage['searchcache']=JSON.stringify(searchcache);
    }catch(e){
        console.log(e);
    }
}
if(searchcache.length > 0){
    var _searchhtml = "";
    for (var item in searchcache){
        if(searchcache[item]['ec'] == "[]"){
            _searchhtml += '<a class="color3 block"  href="'+ searchcache[item]['link'] +'">' + searchcache[item]['val'] + '</a>';
        }else{
            _searchhtml += "<a class='color3 block search-ec' data-ec='"+ searchcache[item]['ec'] +"'  href='javascript:;'>" + searchcache[item]['val'] + '</a>';
        }
    }
    $("#old_search").append(_searchhtml)
    $("#old_search").show();
}
$("body").on("click",".search-ec",function(){
    $('#ec').val($(this).attr("data-ec"));
    if($('#index_search').length>0){
         $('#index_search').submit();
    }
})
