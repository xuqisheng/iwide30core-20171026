// JavaScript Document

$(function()
{
	// 大图滚动
	var oHeader=$('.headers');
	var oWidth=oHeader.width();
	var oSlide=$('.headerslide');
	var oSlideson=$('.imgshow');
	var oDisc=$('.disc');
	oSlideson.width(oWidth);
	var count=0;
	function swiPelft(){
		count++;
		if($(this).attr('alt') != '')
			$('.banner span.tex',$(this).parent().parent()).html($(this).attr('alt'));
		if(count>$('.headerslide img').length - 1)
		{
			count=0;
		}
		oSlide.stop().animate({'left':-count*oWidth+'px'}, 500);
		oDisc.removeClass('disc_show').eq(count).addClass('disc_show');
	}
	var autoSwipleft=window.setInterval(swiPelft,4000)
	
	$(".imgscroll").touchwipe({
		 wipeLeft: function() { scrollbox(true); },
		 wipeRight: function() { scrollbox(false); },
		 min_move_x: 15,
		 min_move_y: 15,
		 preventDefaultEvents: true
	});
		
})