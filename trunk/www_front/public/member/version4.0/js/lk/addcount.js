// JavaScript Document
$(document).ready(function(e) {
	var price = $('.detail .ui_price').html();
	$('.addcount .add').on('touchstart',function(){
		var i=parseInt($(this).siblings('.addnum').val());
		i++;
		$(this).siblings('.addnum').val(i);
		$('.total').html(parseFloat(i*price).toFixed(2));
	});
	$('.addcount .desc').on('touchstart',function(){
		var i=parseInt($(this).siblings('.addnum').val());
		if ( i<=1 )
			return false;
		i--;
		$(this).siblings('.addnum').val(i);
		$('.total').html(parseFloat(i*price).toFixed(2));
	});
});
	