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
	$('.total').html(sum.toFixed(2));
	return sum;	
}
$(function(){
	$(".delete").width()
	var wipeleft = function(_this){
		$(_this).siblings().css({'transform':'translateX(-'+$(".delete").width()+'px)','line-height':$(_this).siblings().height()+'px'});
		$(_this).css({'transform':'translateX(-'+$(".delete").width()+'px)'});
	}
	var wiperight= function(_this){
		$(_this).css({'transform':'translateX(0px)'});
		$(_this).siblings().css({'transform':'translateX(0px)'});
	}
	$(".wipe").touchwipe({
		 wipeLeft: function(_this) {wipeleft(_this);},
		 wipeRight: function(_this) { wiperight(_this); },
		 min_move_x: 15,
		 min_move_y: 15,
		 preventDefaultEvents: true
	});
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
	$('.content .itemimg ').click(function(){
		choose($(this).siblings('.choosebtn'));
		changetxt();
		_total();
	})
	$('.add').on('touchstart',function(e){
		add($(this));
		_total();
	});
	$('.down').on('touchstart',function(e){
		down($(this));
		_total();
	});
})