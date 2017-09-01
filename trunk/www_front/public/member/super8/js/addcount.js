// JavaScript Document
$(document).ready(function(e) {
	 $('.addcount .add').click(function(){
		var i=parseInt($(this).siblings('.addnum').val());
		i++;
		$(this).siblings('.addnum').val(i);
	});
	$('.addcount .desc').click(function(){
		var i=parseInt($(this).siblings('.addnum').val());
		if ( i<=1 )
			return false;
		i--;
		$(this).siblings('.addnum').val(i);
	});
});
	