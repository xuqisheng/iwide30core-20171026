// JavaScript Document

document.addEventListener("touchstart", function(){}, true);
var _waitting =false;			
var _winWidth=$(window).width();
var _winHeigh=$(window).height();
$(function(){
	if(!_waitting){
		window.clearTimeout(tmptime);
		removeload();
	}
});
$(".scroll").on("touchstart",function(e){
    e.preventDefault();
	e.stopPropagation();
})
$(".scroll").on("touchmove",function(e){;
   e.preventDefault();
	e.stopPropagation();
})

function parserDate(date) {  
    var t = Date.parse(date);  
    if (!isNaN(t)) {  
        return new Date(Date.parse(date.replace(/-/g, "/")));  
    } else {  
        return new Date();  
    }  
};  
window.onresize=function(){
	//if ( _winWidth != $(window).width())
		//alert("为了您更好的体验，请您竖屏浏览");		
}
var showload =function(_str,haveico){
	if(_str==undefined)_str=' ';
	if(haveico==undefined)haveico=true;
	removeload();
	var tmp = "<div class='ui_loadmore'><span>";
	tmp +=_str+'</span>';
	if( haveico)tmp+="<span class='ui_ico ui_loading'></span>";
	tmp +="</div>";
	$('.loading_wrap').after(tmp);
}  
function removeload(){
	$('.ui_loadmore').remove();
	$('.pageloading').remove();
}
var _scroll;
function setheight(){
	_scroll = $("body").scrollTop();
	$("body,html").css({
		height:"100%",
		overflow:"hidden"
	})
	$("body").scrollTop(_scroll)
}
function cloheight(){
	$("body,html").css({
		height:"auto",
		overflow:"auto"
	});
	$("body").scrollTop(_scroll)
}
function showload(_str,parent,haveico){
	if(!_str)_str=' ';
	if(!haveico)haveico=true;
	if(!parent) parent='body';
	removeload();
	var tmp = "<div class='ui_loadmore'><span>";
	tmp +=_str+'</span>';
	if( haveico)tmp+="<em class='ui_ico ui_loading'></em>";
	tmp +="</div>";
	$(parent).after(tmp);
}
function isnull(parent,tips,linkstr,url){
	if (!parent) parent='body';
	if (!url) url='#';
	removenull(parent);
	var html = '';
	html+= '<div class="ui_none middle"><div>';
	if (!tips)
	   tips	= '没有相关信息';
    html+= tips;
	if (!linkstr) linkstr='';
	html+= '<a href="'+url+'" class="color_link">'+linkstr+'</a></div>';
	html+= '</div>';
	$(parent).append(html);
}
function removenull(parent){
	if(!parent)parent='body';
	$(parent).find('.ui_none').remove();
}

function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}
function pageloading (_str,alpha){ //页面loading动画
	removeload();
	if(!alpha){alpha=0.9;}
	if(!_str) _str='';
	var tmp = '<div class="pageloading">';
	tmp+='<p class="isload" style="margin-top:'+$(window).height()*0.4+'px">'+_str+'</p></div>';
	$('body').append(tmp);
}
var tmptime;
function first_load(){
	pageloading();
}
	// tmptime=window.setTimeout(function(){
	// 	$('.pageloading p').html('玩命加载中');
	// 	tmptime=window.setTimeout(function(){
	// 		$('.pageloading p').removeClass('isload').addClass('error').html('网络似乎有点问题~');
	// 		window.setTimeout(function(){
	// 			$('.pageloading').remove();
	// 		},1000);
	// 	},10000);
	// },10000);

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
	$(_this).show();
	// $('body').css('overflow','hidden');
	// $('html').css('overflow','hidden');
	$(_this).scroll(function(e){
		e.preventDefault();
	})
	history.pushState({ path: this.path }, '', this.href);
	$(window).bind('popstate', function() {
		setTimeout(function(){
			$(_this).hide();
			$('body').removeAttr('style');
			$('html').removeAttr('style');
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
function showtips(option,fn){
	$('#layout_tips').remove();
	if ( option==undefined) option={};
	if ( option.title ==undefined)option.title='提示';
	if ( option.tips ==undefined)option.tips='NuLL';
	if ( option.leftLink ==undefined)option.leftLink='取消';
	if ( option.leftUrl ==undefined)option.leftUrl='#';
	if ( option.leftClick ==undefined)option.leftClick='';
	if ( option.rightLink ==undefined)option.rightLink='确定';
	if ( option.rightUrl ==undefined)option.rightUrl='#';
	if ( option.rightClick ==undefined)option.rightClick='';
	if ( option.isLink ==undefined)option.rightClick=true;
	var tmp='a';
	if (!option.isLink) tmp='div';
	var html = '<div class="ui_pull" id="layout_tips" onclick="$(this).remove()"><div class="pullbox bg_fff">'
	+ '<div class="pulltitle h32">'+option.title+'</div>'
	+ '<div style="text-align:justify">'+option.tips+'</div>'
	+ '<div class="webkitbox center">'
	+ '<div><'+tmp+' id="left_btn" onclick="'+option.leftClick+'" href="'+option.leftUrl+'" class="btn_main">'+option.leftLink+'</'+tmp+'></div>'
	+ '<div><'+tmp+' id="right_btn" onclick="'+option.rightClick+'" href="'+option.rightUrl+'" class="btn_main">'+option.rightLink+'</'+tmp+'></div>'
	+ '</div></div></div>';
	$('body').append(html);
	$('#left_btn').bind('click',eval(fn));
}
$(function(){
	$('.checkbox').click(function(){
		var _tmp=$(this).siblings('input[type=radio]').get(0);
		var bool = _tmp.checked;
		_tmp.checked = (!bool);
	});
	$('.radio').click(function(e){
		$(this).siblings('input[type=radio]').get(0).checked=true;
	});
	$('.down_num').on('touchstart',function(e){
		e.stopPropagation();
		var input =  $(this).siblings('.result_num').find('input');
		var $min = input.attr('min');
		if(input.attr('min')==undefined||input.attr('min')=='')$min=1;
		var val = parseInt(input.val());
		if(isNaN(val)){$.MsgBox.Alert('数量必须为数字');return false;}
		if( val>$min&&val>1){input.val(val-1);}
	})
	$('.result_num').change(function(){
		var input =  $(this).find('input');
		var $max = input.attr('max');
		var $min = input.attr('min');
		var val = parseInt(input.val());
		if(isNaN(val)){$.MsgBox.Alert('数量必须为数字');input.val(1);}
		if(input.attr('min')==undefined||input.attr('min')=='')$min=1;
		if(val<=$min){input.val($min);}
		if(input.attr('max')==undefined||input.attr('max')=='')return;
		else if( val>=$max){input.val($max);}
	});
	$('.up_num').on('touchstart',function(e){
		e.stopPropagation();
		var input =  $(this).siblings('.result_num').find('input');
		var $max = input.attr('max');
		if(input.attr('max')==undefined||input.attr('max')=='')$max=9999;
		var val = parseInt(input.val());
		if(isNaN(val)){$.MsgBox.Alert('数量必须为数字');return false;}
		if( val<$max){input.val(val+1);}		
	})
});

function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return decodeURIComponent(r[2]);
    return null;
}

(function () {
    $.MsgBox = {
        Alert: function ( msg,callback,cancel) {
            GenerateHtml("alert", msg);
            btnOk(callback);
            btnNo(cancel);
        },
        Confirm: function (msg, callback,cancel,str1,str2) {
			if(str1==undefined)str1='确定';
			if(str2==undefined)str2='取消';
            GenerateHtml("confirm",msg,str1,str2); 
            btnOk(callback);
            btnNo(cancel);
        }
    }
    var GenerateHtml = function (type, msg,str1,str2) {
		$("#mb_box,#mb_con").remove();
        var _html = "";
//		if(msg==undefined){
//			msg=title;
//			title='提示';
//		}
        _html += '<div id="mb_box" class="h30"><div id="mb_con" class="bg_282828">';
        // <em class="close iconfont color6"></em>';
		_html += '<!--div id="mb_title" class="color_main h36"> + title + </div-->';
        _html += '<div class="color1 mar_tb30" id="mb_msg">' + msg + '</div><div class="" id="mb_btnbox">';
        if (type == "alert") {
            // _html += '<div id="mb_btn_no" class="color_888">关闭</div>';
            _html += '<div id="mb_btn_ok" class="color_main">关闭</div>';
        }
        if (type == "confirm") {
            _html += '<div id="mb_btn_no" class="color_888">'+str2+'</div>';
            _html += '<div id="mb_btn_ok" class="color_main">'+str1+'</div>';
        }
        _html += '</div></div></div>';
        $("body").append(_html);
        GenerateCss();
    }
    var GenerateCss = function () {
		var tmp='<style>';
        tmp+="#mb_box{width:100%;height:100%;z-index:99999;position:fixed;background:rgba(0,0,0,0.4);top:0;left:0;}";
        tmp+="#mb_con{z-index:999999;width:92%;position:fixed;text-align:center;border-radius:6px;padding-bottom: 20px;}";
        tmp+="#mb_title{ padding-top:15px;text-align:center;}";
        tmp+="#mb_msg{ padding:35px;text-align:center;line-height: 1.2;}";
        tmp+="#mb_btnbox{text-align: center;}";
        tmp+="#mb_btnbox>*{ padding:10px;width:40%;margin:0 3%;display:inline-block;}";
        tmp+="#mb_btn_ok{border: .5px solid #b2945e;}";
        tmp+="#mb_btn_no{border: .5px solid #e3e3e3;}";    
		$('body').before(tmp);
        var _widht = document.documentElement.clientWidth;  //屏幕宽
        var _height = document.documentElement.clientHeight; //屏幕高
        var boxWidth = $("#mb_con").width();
        var boxHeight = $("#mb_con").height();
        //让提示框居中
        $("#mb_con").css({ top: (_height - boxHeight) / 2.5 + "px", left: (_widht - boxWidth) / 2 + "px" });
    }
    //确定按钮事件
    var btnOk = function (callback) {
        $("#mb_btn_ok").click(function () {
            $("#mb_box,#mb_con").remove();
            if (typeof (callback) == 'function') {
                callback();
            }
        });
    }
    //取消按钮事件
    var btnNo = function (callback) {
        $("#mb_btn_no").click(function () {
            $("#mb_box,#mb_con").remove();
            if (typeof (callback) == 'function') {
                // callback();
            }
        });
    }
})();

function getNowFormatDate() {
    var date = new Date();
    var seperator1 = "/";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
            + " " + date.getHours() + seperator2 + date.getMinutes()
            + seperator2 + date.getSeconds();
    return currentdate;
}