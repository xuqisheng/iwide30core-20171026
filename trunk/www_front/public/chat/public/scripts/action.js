// JavaScript Document

var scan_time;
var max_user=0;
var cur_user=0;
var useri = 0;

function scan( no_first){
	
	max_user = memberonline.length;
	
	if ( no_first　!= undefined){
		window.clearTimeout(scan_time);
		cur_user = 0;
		for( var i=0; i<$('.ui_canvas_around').length;i++){
			$('.ui_canvas_around').eq(i).removeClass('fadein').addClass('fadeout');;
			$('.ui_canvas_around').eq(i).fadeOut(function(){
				$('.ui_canvas_around').eq(i).html('');
			})
		}
	}
	$('.ui_canvas_bg').stop().fadeIn();
	$('.ui_canvas_bg').addClass('rotate');
	var _this=$('.ui_canvas_around').eq(cur_user);
	
	scan_time = window.setTimeout(function(){
		displayno = '';
		if(parseInt(memberonline[useri].id)==parseInt($('#userid').val())){
			displayno = ' style="display:none"'
		}
		_this.html('<img'+displayno+' src="'+memberonline[useri].logo+'" data=\''+JSON.stringify(memberonline[useri])+'\' />');
		if(useri<max_user-1){
		    useri += 1;
		}
		else {
			useri = 0;
		}
		_this.fadeIn();
		_this.removeClass('fadeout').addClass('fadein');
		position(cur_user);
		cur_user++;
		
		
		scan();
	},3000);
	if ( cur_user >= max_user){
		window.clearTimeout(scan_time);
		stop_scan();
	}
}
function stop_scan(){
	$('.ui_canvas_bg').stop().fadeOut(2000,function(){
		$('.ui_canvas_bg').removeClass('rotate');
	});
}
function position(_i){
	var _random_x = new Array(0.5,-0.5,-0.9,1,0.5);
	var _random_y = new Array(-1,1,-1,1,1);
	var _r = $('.ui_canvas_around').eq(_i).parent().innerWidth()*0.5;
	var _w = $('.ui_canvas_around').eq(_i).find('img').width()*0.5
	var _x = _random_x[_i]*_r;
	var _y = _random_y[_i]*Math.sqrt(_r*_r-_x*_x); 
	$('.ui_canvas_around').siblings().length;
	$('.ui_canvas_around').eq(_i).css({'top':_r-_y-_w,'left':_r-_x-_w});
}
function creat_ciycle(){
	var _w_canvas = $(".ui_canvas_min").width();
	var _w_center = $(".ui_canvas_center img").width();
	var _top = 0.5*(_w_canvas-_w_center);
	$('.ui_canvas_center').css({'top':_top,'left':_top});
}
window.addEventListener('load',creat_ciycle,false);