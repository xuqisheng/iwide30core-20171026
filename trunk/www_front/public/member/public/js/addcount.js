// JavaScript Document
$(document).ready(function(e) {
	var price = $('.detail .ui_price').html();
	$('.addcount .add').on('touchstart',function(){
		var i=parseInt($(this).siblings('.addnum').val());
		var max = $(":input[name=maxnum]").val();
		if(i==max) {
			alert('已经达到最大的购买数量!');
			return false;
		}
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
	