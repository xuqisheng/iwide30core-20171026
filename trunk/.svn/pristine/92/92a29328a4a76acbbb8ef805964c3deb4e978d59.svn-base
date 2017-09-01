// JavaScript Document

var tmphtml = '<div class="filter_pull ui_pull"></div>';
var myGeo = new BMap.Geocoder();
var extra_condition={};
var load_times = 1;
// 将地址解析结果显示在地图上,并调整地图视野
var getmap =function(city,key){
	/*var url = 'http://apis.map.qq.com/ws/place/v1/suggestion/?region='+city+'&keyword='+key+'&output=jsonp&key=OZQBZ-6DZ32-NQJUJ-C6HX7-VUIA5-B4BB2';
	$.get(url,'',function(data){
		if( data.status==0){
			console.log(data);
			var str='<div class="h22 pad3 bg_E4E4E4">搜索到以下地标<em class="ui_ico ui_loading" style="width:12px;height:12px;"></em></div>';
			for(var i = 0;i<data.data.length;i++){
				str += '<li filter="bdmap" code="'+data.data[i].location.lat+','+data.data[i].location.lng+','+data.data[i].title+'" >'+data.data[i].title+'</li>';
			}
			$('.result_list .address_list').append(str);
			$('.result_list li').unbind('click',result_list_click);
			$('.result_list li').bind('click',result_list_click);	
			$(".result_list .ui_loading").remove();	
        }
	},'jsonp');*/
		
	myGeo.getPoint(city, function(point){
		if (point) {
			var local = new BMap.LocalSearch(point, {
				onSearchComplete:function(results){
//					console.log(results);
					//console.log(results.wr);//xr//mr
					var str='<div class="h22 pad3 bg_E4E4E4">搜索到以下地标<em class="ui_ico ui_loading" style="width:12px;height:12px;"></em></div>';
					var data = results.ur;
					if(data==undefined){
						var fuck_bd='abcdefghijklmnopqrstuvwxyz';
						for(i=0;i<26;i++){
							var tmp=fuck_bd[i]+'r';
							if(results[tmp]!=undefined){
								data=results[tmp];
								break;
							}
						}
					}
					if(data!=undefined){
						for(var i = 0;i<data.length;i++){
							str += '<li filter="bdmap" code="'+data[i].point.lat+','+data[i].point.lng+','+data[i].title+'" >'+data[i].title+'</li>';
						}
					}
					$('.result_list .address_list').append(str);
					$('.result_list li').unbind('click',result_list_click);
					$('.result_list li').bind('click',result_list_click);	
					$(".result_list .ui_loading").remove();		
				}
			});
			local.search(key,{forceLocal:true});
		}else{
//			$.MsgBox.Alert("您选择地址没有解析到结果!");
		}
	}, city);
}
function isnone(){
	if($('.hotel_list').find('.item').length<=0)
		$('.ui_none').show();
	else
		$('.ui_none').hide();
}

var showload =function(_str,haveico){
	if(_str==undefined)_str=' ';
	if(haveico==undefined)haveico=true;
	removeload();
	var tmp = "<div class='ui_loadmore'><span>";
	tmp +=_str+'</span>';
	if( haveico)tmp+="<em class='ui_ico ui_loading'></em>";
	tmp +="</div>";
	$('.hotel_list').after(tmp);
}  
function tab_list_click(){
	$('p',this).addClass('bg_main');
	$(this).siblings().find('p').removeClass('bg_main');
	$('.get_result').eq($(this).index()).show().siblings('.get_result').hide();
}
function _alink_click(url){
	//extra_condition={};
	$('#ec').val('');
	$(".searchbox input").val("");
	window.location.href=url+"&start="+$('#startdate').val()+"&end="+$('#enddate').val();
}
function result_list_click(){
	_this=$(this);
	if( !_this.hasClass('_alink')){
        load_times = 1;
		$('.get_result li').removeClass('color_main');
		_this.addClass('color_main');
		extra_condition={};
		extra_condition[_this.attr('filter')]=_this.attr('code');
		$('#ec').val(JSON.stringify(extra_condition));
		$('.result_list').hide();
		pageloading();
		fill_hotel('html',0,true);
//		$('.searchbox input').val(_this.html());
		window.sessionStorage.ec=$('#ec').val();
		if($('#index_search').length>0) $('#index_search').submit();
		else toclose();
	}
}
function search2(){
	
	var val=$(this).val();
	if( val ==''){$('.result_list').stop().slideUp();}
	else{
		var str = '';
		$('.result_list .address_list').html(str);
		/*for( var i=0; i<$('.get_result li').length; i++){
			if ( $('.get_result li').eq(i).html().indexOf(val) >= 0){
				str += '<li filter="'+$('.get_result li').eq(i).attr('filter');
				str +='" code="'+$('.get_result li').eq(i).attr('code')+'">'+$('.get_result li').eq(i).html()+'</li>';
			}
		}*/
		search_hotel(val);
		$('.result_list .address_list').html(str);
		$('.result_list span').html($('.result_list .address_list li').length);
		if($('.result_list .address_list li').length<=5){
			var _val='北京市';
			if ($('#city').val()!=''&&$('#city').val()!=undefined)
				_val=$('#city').val()
//			$('.result_list .address_list').append('<div class="h4" style="padding:3%;">搜索到以下地标<em class="ui_ico ui_loading" style="width:12px;height:12px;"></em></div>')
			getmap(_val,val);
		}
		$('.result_list li').bind('click',result_list_click);			
		$('.result_list').stop().slideDown();
	}
	/*
	var val=$(this).val();
	if( val ==''){$('.result_list').stop().slideUp();}
	else{
		var str = '';
		$('.result_list .address_list').html(str);*/
/*		for( var i=0; i<$('.get_result li').length; i++){  //原列表搜索
			if ( $('.get_result li').eq(i).html().indexOf(val) >= 0){
				str += '<li filter="'+$('.get_result li').eq(i).attr('filter');
				str +='" code="'+$('.get_result li').eq(i).attr('code')+'">'+$('.get_result li').eq(i).html()+'</li>';
			}
		}
		$('.result_list .address_list').html(str);
		$('.result_list span').html($('.result_list .address_list li').length);*/
		/*search_hotel(val);
		var _val='北京市';
		if ($('#city').val()!=''&&$('#city').val()!=undefined)	_val=$('#city').val();
		getmap(_val,val);
		$('.result_list li').bind('click',result_list_click);			
		$('.result_list').stop().slideDown();
	}
	return false;*/
}
function search_hotel(keyword){
	$.get('../check/ajax_hotel_search',{
		keyword:keyword,
		city:$('#city').val()
	},function(data){
		if(data.s==1){
			$('.result_list .address_list').prepend(data.data);
			$('.result_list [length] span').html($('.result_list ._alink').length);
			$('.result_list [length]').show();
		}else{
			return '';
		}
	},'json');
}
function title_click(){
	$(this).next().stop().slideToggle();
}
function tobind(){
	$('.filter_pull .tab_list li').bind('click',tab_list_click);
	$('.get_result li').bind('click',result_list_click);
	$('#search2').bind('change',search2);/*input propertychange*/
	$('.get_result .title').bind('click',title_click);
	
	var _h = $(window).height() -$('.filter_pull .pull_searchbox').outerHeight()-16;
	$('.result_list').height(_h);
	$('.tab_list').height(_h);
	$('.get_result').height(_h);
}

$(function(){
	$('body').append(tmphtml);
	$('#show_sort_pull').click(function(){
		if($('.sort_list_pull').is(":hidden"))toshow($('.sort_list_pull'));
	})
	$('.sort_list_pull li').click(function(){
        load_times = 1;
		$(this).addClass('cur').siblings().removeClass('cur');
		$('#show_sort_pull').find('span').html($(this).html());
		$('#sort_type').val($(this).attr('sort_tag'));
		fill_hotel('html',0);
		pageloading();
	});
	$('.keyword').click(function(){
		var url =$(this).attr('url');
		if($('.filter_pull').length){
			if(city!=$('#city').val()){
//				if (city=='') city=='广州';
				city=$('#city').val();
				$.get(url,{
					city:$('#city').val()
				},function(data){
					removeload();
					if(data.s==1){
						$('.filter_pull').html(data.data);
						toshow($('.filter_pull'));
						tobind();
					}else{
						$.MsgBox.Alert(data.data);
					}
				},'json');
				pageloading();
			}else{
				toshow($('.filter_pull'));
			}
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
})