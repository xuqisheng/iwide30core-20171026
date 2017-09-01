// JavaScript Document
$(document).ready(function(e) {
	var _scroll;
	$('textarea').click(function(){
		window.clearTimeout(_scroll);
		_scroll=window.setTimeout(function(){
			$(document).scrollTop($(window).height());
		},300);
	})	
	$('.chat_content_box').scroll(function(e){
		e.preventDefault();
	})
	$(document).scroll(function(e){
		e.preventDefault();
	})
});

