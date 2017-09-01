// JavaScript Document
// 大图滚动
var imgrate='';
$(function(){
	if(imgrate=='') imgrate=640/240;
	var oWidth=$('.headers').width();
	var oSlide=$('.headerslide');
	var creat_circle =function(){
		var  tmp ='';
		if (oSlide.find('img').length>1 ){
			tmp +='<div class="banner"><div class="circle">';
			for ( var i=0; i<oSlide.find('img').length; i++){
				tmp+='<span class="disc';
				if ( i==0) tmp+=' disc_show';
				tmp+='"></span>';
			}
			tmp += '</div></div>';
		}
		$('.headers').append(tmp);
	}();
	var creat_css =function(){
		var tmp ='<style>.headers{width:100%;height:'+$(window).width()/imgrate+'px;overflow: hidden;position: relative;}';
			tmp+='.headerslide{width:500%;height: 100%;position:relative;}';
			tmp+='.slideson{float: left;position:relative;}';
			tmp+='.slideson{ height:100%;display:block; width:100%;}';
			tmp+='.headers .banner{position: absolute;bottom: 0;padding: 1% 0;width: 100%;color: white;overflow: hidden;}';
			tmp+='.circle{overflow: hidden; text-align:center}';
			tmp+='.circle span{display:inline-block;width:0.3rem;height:0.3rem;border-radius:50%; margin-right:0.5em;background:rgba(228,228,228,0.5);}';
			tmp+='.circle span.disc_show{background:rgba(255,255,255,0.9);}</style>';
		$('body').before(tmp);
	}();
	
	var oSlideson=$('.slideson');
	var oDisc=$('.disc');
	oSlideson.width(oWidth);
	var count=0;
	function swiPelft(){
		count++;
		if(count>oSlide.find('img').length - 1)  count=0;
		oSlide.stop().animate({'left':-count*oWidth+'px'}, 500);
		oDisc.removeClass('disc_show').eq(count).addClass('disc_show');
	}
	function swiPergt(){
		count--;
		if(count<0)  count=oSlide.find('img').length - 1;
		oSlide.stop().animate({'left':-count*oWidth+'px'}, 500);
		oDisc.removeClass('disc_show').eq(count).addClass('disc_show');
	}
	var autoSwipleft=window.setInterval(swiPelft,4000);
	
	$(".headers").touchwipe({
		 wipeLeft  : function() {  swiPelft();},
		 wipeRight : function() { swiPergt(); },
		 min_move_x: 15,
		 min_move_y: 15,
		 preventDefaultEvents: true
	});
})