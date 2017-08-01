// JavaScript Document
// 大图滚动
(function($) {
$.fn.imgscroll = function(options) {
    var defualt= { 
		 //宽比高
		imgrate			 : 640/160,   
	    partent_div      : 'headers',  // 轮播元素
		circlesize		 : '1em',   //圆点大小
		circlergb		 : '255,255,255',  // 圆点颜色
		circleshow		 : true,    //是否显示圆点
	};
	var option = $.extend({}, defualt, options);
	var curdiv = $('.'+option.partent_div);
	var oWidth = curdiv.width();
	var oSlide = curdiv.find('.headerslide');
	var oLength= oSlide.find('img').length;
	var count  = oLength;
	var creat_circle =function(){
		var  tmp ='';
		if (oLength>1 ){
			tmp +='<div class="banner"><div class="circle">';
			for ( var i=0; i<oLength; i++){
				tmp+='<span class="disc';
				if ( i==0) tmp+=' disc_show';
				tmp+='"></span>';
			}
			tmp += '</div></div>';
		}
		for ( var i=0;i<oLength*2;i++){
			var tmpdiv = curdiv.find('.slideson').eq(i).clone();
			curdiv.find('.headerslide').append(tmpdiv);
		}
		curdiv.append(tmp);
		oSlide.css('left',-count*oWidth+'px');
	};
	creat_circle();
	var oSlideson=curdiv.find('.slideson');
	var oDisc=curdiv.find('.disc');
	oSlideson.width(oWidth);
	var creat_css =function(){
		var tmp ='<style>.'+option.partent_div+'{width:100%;height:'+oWidth/option.imgrate+'px;overflow: hidden;position: relative;}';
			tmp+='.'+option.partent_div+' .headerslide{width:'+oSlide.find('img').length+'00%;height: 100%;position:relative;}';
			tmp+='.'+option.partent_div+' .slideson{float: left;position:relative; height:100%;display:block; width:100%;}';
			tmp+='.'+option.partent_div+' .slideson img{width:100%; min-height:100%;}';
			tmp+='.'+option.partent_div+' .banner{position: absolute;bottom: 0;padding: 1% 0;width: 100%;color: white;overflow: hidden;}';
			tmp+='.'+option.partent_div+' .circle{overflow: hidden;';
			if (!option.circleshow) tmp+='display:none;';tmp+='text-align:center}';
			tmp+='.'+option.partent_div+' .circle span{display:inline-block;width:'+option.circlesize+';height:'+option.circlesize+';border-radius:50%; margin-right:0.5em;background:rgba('+option.circlergb+',0.5);}';
			tmp+='.'+option.partent_div+' .circle span.disc_show{background:rgba('+option.circlergb+',0.9);}</style>';
		curdiv.prepend(tmp);
	}
	creat_css();
	function swiPelft(){
		count++;
		oSlide.stop().animate({'left':-count*oWidth+'px'}, 200,function(){
			if(count>oLength*2 - 1){count=oLength;oSlide.css('left',-count*oWidth+'px');}	
			oDisc.removeClass('disc_show').eq(count-oLength).addClass('disc_show');
		});
	}
	function swiPergt(){
		count--;
		oSlide.stop().animate({'left':-count*oWidth+'px'}, 200,function(){
			if(count<oLength) { count=oLength*2-1;oSlide.css('left',-count*oWidth+'px');}
			oDisc.removeClass('disc_show').eq(count-oLength).addClass('disc_show');
		});
	}
	var autoSwipleft='';
	if (oLength>1 ){
		autoSwipleft=window.setInterval(swiPelft,4000);
		
		curdiv.touchwipe({
			 wipeLeft  : function() {  
				 window.clearInterval(autoSwipleft);
				 swiPelft();
				 autoSwipleft=window.setInterval(swiPelft,4000);
			 },
			 wipeRight : function() { 
				window.clearInterval(autoSwipleft);
				swiPergt(); 
				autoSwipleft=window.setInterval(swiPelft,4000);
			 },
			 min_move_x: 15,
			 min_move_y: 15,
			 preventDefaultEvents: true
		});
	}
}
})(jQuery);