// JavaScript Document
$(function(){
	$('.fenSan_b').on('touchstart',function(e){
			e.preventDefault;
			$('.fix').fadeIn();
			setTimeout(function(){
				$('.fix').fadeOut();
				},1500)
		})
})