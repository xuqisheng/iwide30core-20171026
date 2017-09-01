// JavaScript Document
// 大图滚动
(function($) {
$.fn.imgscroll = function(options) {
    var defualt= { 
		 //宽比高
		imgrate			 : 640/160,   
	    partent_div      : 'headers',  // 轮播元素
		circlesize		 : '8px',   //圆点大小
		circlergb		 : '255,255,255',  // 圆点颜色
		circleshow		 : true,    //是否显示圆点
		autowipe		 : true,
		speed			 : 300,
		delay			 : 4,
		prebtn			 : null,
		nextbtn			 : null,
		overflow		 : 'hidden',
		imgpadding       : '0px',
		callback		 : function(){}
	};
	var option = $.extend({}, defualt, options);
	if( option.speed>400) option.speed=400;	
	if( option.speed<100) option.speed=100;
	var curdiv = $('.'+option.partent_div);
	var oWidth = curdiv.outerWidth();
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
		var tmp ='<style>.'+option.partent_div+'{width:100%;height:'+oWidth/option.imgrate+'px;overflow: '+option.overflow+';position: relative;}';
			tmp+='.'+option.partent_div+' .headerslide{width:'+oSlide.find('img').length+'00%;height: 100%;position:relative; white-space:nowrap;font-size:0}';
			tmp+='.'+option.partent_div+' .slideson{position:relative;font-size:1rem; height:100%;display:inline-block; width:100%;padding:'+option.imgpadding+'}';
			tmp+='.'+option.partent_div+' .slideson img{width:100%; min-height:100%;}';
			tmp+='.'+option.partent_div+' .banner{position: absolute;bottom: 0;padding: 1% 0;width: 100%;color: white;overflow: hidden;}';
			tmp+='.'+option.partent_div+' .circle{overflow: hidden;';
			if (!option.circleshow) tmp+='display:none;';
			tmp+='text-align:center}';
			tmp+='.'+option.partent_div+' .circle span{display:inline-block;width:'+option.circlesize+';height:'+option.circlesize+';border-radius:50%; margin-right:0.5em;background:rgba('+option.circlergb+',0.5);}';
			tmp+='.'+option.partent_div+' .circle span.disc_show{background:rgba('+option.circlergb+',0.9);}</style>';
		curdiv.prepend(tmp);
	}
	creat_css();
	function swiPelft(){
		count++;
		oSlide.stop().animate({'left':-count*oWidth+'px'}, option.speed,function(){
			if(count>oLength*2 - 1){count=oLength;oSlide.css('left',-count*oWidth+'px');}	
			oDisc.removeClass('disc_show').eq(count-oLength).addClass('disc_show');
			option.callback({"index":count-oLength});
		});
	}
	function swiPergt(){
		count--;
		oSlide.stop().animate({'left':-count*oWidth+'px'}, option.speed,function(){
			if(count<oLength) { count=oLength*2-1;oSlide.css('left',-count*oWidth+'px');}
			oDisc.removeClass('disc_show').eq(count-oLength).addClass('disc_show');
			option.callback({"index":count-oLength});
		});
	}
	var autoSwipleft='';
	if (oLength>1 ){
		if(option.autowipe)
			autoSwipleft=window.setInterval(swiPelft,option.delay*1000);
		if(option.prebtn!=null){
			$(option.prebtn).click(swiPergt);
		}
		if(option.nextbtn!=null){
			$(option.nextbtn).click(swiPelft);
		}
		curdiv.touchwipe({
			 wipeLeft  : function() {   //next
				 window.clearInterval(autoSwipleft);
				 swiPelft();
			     if(option.autowipe)
					 autoSwipleft=window.setInterval(swiPelft,4000);
			 },
			 wipeRight : function() { 
				window.clearInterval(autoSwipleft);
				swiPergt(); 
				if(option.autowipe)
					autoSwipleft=window.setInterval(swiPelft,4000);
			 },
			 min_move_x: 15,
			 min_move_y: 15,
			 preventDefaultEvents: true
		});
	}else{
		if(option.prebtn!=null)$(option.prebtn).hide();
		if(option.nextbtn!=null)$(option.nextbtn).hide();
	}
}
})(jQuery);