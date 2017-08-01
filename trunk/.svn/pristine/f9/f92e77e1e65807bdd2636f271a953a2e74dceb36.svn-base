// JavaScript Document

document.addEventListener("touchstart", function(){}, true);
$(window).load(function(){
	window.clearTimeout(tmptime);
	$('.page_loading').remove();
//	for(var _i=0; _i<$('.ui_square_h').length; _i++){
//		$('.ui_square_h').eq(_i).height($('.ui_square_h').eq(_i).width());	
//	}
	for(var _i=0; _i<$('.align_middle').length; _i++){
		$('.align_middle').eq(_i).css('line-height',$('.align_middle').eq(_i).height()+'px');	
	}
	$('.ui_pull').scroll(function(e){
		e.preventDefault();
	})
});
var _winWidth=$(window).width();
var _winHeigh=$(window).height();
window.onresize=function(){
	//if ( _winWidth != $(window).width())
		//alert("为了您更好的体验，请您竖屏浏览");		
}

function pageloading (_str,alpha){ //页面loading动画
	if(!alpha){alpha=0.7;}
	if(!_str) _str='正在加载';
	var tmp = '<div class="page_loading" style="background:rgba(255,255,255,'+alpha+')"><p class="isload">'+_str+'</p></div>';
	$('body').prepend(tmp);
}
var tmptime;
	
tmptime=window.setTimeout(function(){
	$('.page_loading p').html('玩命加载中');
	tmptime=window.setTimeout(function(){
		$('.page_loading p').removeClass('isload').addClass('error').html('网络似乎有点问题~');
		window.setTimeout(function(){
			window.clearTimeout(tmptime);
			$('.page_loading').remove();
		},1000);
	},10000);
},10000);

var reg_phone = new RegExp(/^1\d{10}$/);
var reg_cid   = new RegExp(/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/);
var reg_email = new RegExp(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/);


//if ( _winWidth> _winHeigh )
	//alert("为了您更好的体验，请您竖屏浏览");
// 最小高度 
var minRows = 1; 
// 最大高度，超过则出现滚动条 
var maxRows = 3; 

var toshow=function(_this){
	_this.show();
	$('body').addClass('overflow');
	$('html').addClass('overflow');
	_this.scroll(function(e){
		e.preventDefault();
	})
	history.pushState({ path: this.path }, '', this.href);
	$(window).bind('popstate', function() {
		setTimeout(function(){
			_this.fadeOut();
			$('body').removeClass('overflow');
			$('body').removeAttr('style');
			$('html').removeClass('overflow');
		},100);
	})
}
var toclose = function(){
	history.back(-1);
}
var stop_bubble=function(){
    e=this.event||window.event;
    if(!e)return;
    if(e.stopPropagation){
        e.stopPropagation();
    }
    e.cancelBubble=true;
}
function changerow(_this){  
	
//var l=_this.value.split("\n");
	//var rows = l.length
		if (_this.scrollTop == 0) _this.scrollTop=1; 
		while (_this.scrollTop == 0){ 
		if (_this.rows > minRows) 
			_this.rows--; 
		else 
			break; 
		_this.scrollTop = 1; 
		if (_this.rows < maxRows) 
			_this.style.overflowY = "hidden"; 
		if (_this.scrollTop > 0){ 
			_this.rows++; 
			break; 
			} 
		} 
	while(_this.scrollTop > 0){ 
		if (_this.rows < maxRows){ 
			_this.rows++; 
			if (_this.scrollTop == 0) _this.scrollTop=1; 
		} 
		else{ 
			_this.style.overflowY = "auto"; 
			break; 
		} 
	} 
} 
function img_auto_cut(){
	var _this = $('.ui_img_auto_cut');
	var _p_w,_p_h,_w,_h;
	for (var i=0; i<_this.length;i++){
		_p_w  = _this.eq(i).width();
		_p_h  = _this.eq(i).height();
		var _thisimg = _this.eq(i).find('img');
		if(_thisimg.length && !_thisimg.is(":hidden")){
			_w=_thisimg.width();
			if( _w > _p_w  ){
				_thisimg.removeClass('_w_h').addClass('_h_w');
				_thisimg.css('left',(_p_w-_thisimg.width())/2);
			}
			else if( _w < _p_w ){
				_thisimg.removeClass('_h_w').addClass('_w_h');
				_thisimg.css('top',(_p_h-_thisimg.height())/2);
			}
		}
		//console.warn(_p_w+' '+_p_h+' '+' '+_h+' ');
	}
}
function scrollBottom(){  //滚动到文档底部
	window.setTimeout(function(){
		$(document).scrollTop($(document).height());
	},300)
}