/*$(function(){
	$('input[name="satisefaction"]').click(function(){
		var _this = $(this);

		_this.closest('li.one-check').next('li').siblings(".explanation-li").slideUp();
		_this.closest('li.unsatisefy-check').next('li').slideDown();
		
	});
})

$(function(){
	$('.unsatisefy-choices a').click(function(){
		$(this).toggleClass('chosen');
	});
})*/

$(function(){
	$('.li-wid-2').not('.view-tit').click(function(){
		$(this).addClass('cmmChosAgree').next().removeClass('cmmChosdisAgree');
		$('#item_'+$(this).attr('tag')).val(1);
	})
	$('.li-wid-3').not('.view-tit').click(function(){
		$(this).addClass('cmmChosdisAgree').prev().removeClass('cmmChosAgree');
		$('#item_'+$(this).attr('tag')).val(0);
	})
})