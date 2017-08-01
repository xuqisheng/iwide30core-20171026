// JavaScript Document
function rotate(){
	var _w= $(window).width();
	var _h= $(window).height();
	if ( _w >_h )
		alert("为了您更好的体验，请您竖屏浏览");
}
window.onload=function(){
	rotate();	
}
window.onresize=function(){
	rotate();	
}

