// JavaScript Document


function changetxt(){
	var _this=$('.choosebtn');
	for ( var i=0; i<_this.length-1; i++){
		if(!_this.eq(i).hasClass('ischoose')){
			$('.chooseall .choosebtn').removeClass('ischoose')
			$('.chooseall').find('span').html('全选');
			return;
		}
	}
	$('.chooseall .choosebtn').addClass('ischoose')
	$('.chooseall').find('span').html('全不选');
}
function choose(_this){
	console.warn();
	if(_this.hasClass('ischoose')){
		_this.removeClass('ischoose');
	}
	else{
		_this.addClass('ischoose');
	}
}
function add(_this){
	var _input=_this.parent().find('input');
	_input.val(parseInt(_input.val())+1);
	_this.parent().find('.down').css('opacity',1);
}
function down(_this){
	var _input = _this.parent().find('input');
	var tmpval = parseInt(_input.val());
	if ( tmpval<=2){
		_this.parent().find('.down').css('opacity',0.6);
	}
	if ( tmpval<=1){
		 return;
	}
	_input.val(tmpval-1);
}
function _total(){
	var tmp=0;
	var count=0;
	var sum=0;
	for ( var i=0;i<$('.content .ischoose').length;i++){
		tmp=$('.ischoose').eq(i).parent().find('.ui_price').html();
		count=$('.ischoose').eq(i).parent().find('input').val();
		sum+=tmp*count;
	}
	$('.total').html(sum);
	return sum;	
}
$(function(){
	$('.chooseall').click(function(){
		if($(this).find('.choosebtn').hasClass('ischoose'))
			$('.choosebtn').removeClass('ischoose');
		else
			$('.choosebtn').addClass('ischoose');
		changetxt();
		_total();
	})
	$('.content .choosebtn').click(function(){
		choose($(this));
		changetxt();
		_total();
	})
	$('.add').on('click',function(e){
		add($(this));
		_total();
	});
	$('.down').on('click',function(e){
		down($(this));
		_total();
	});
})