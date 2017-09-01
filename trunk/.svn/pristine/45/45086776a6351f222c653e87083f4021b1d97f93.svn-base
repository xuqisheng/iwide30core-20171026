// JavaScript Document


var removeload=function(){
	$('.ui_loadmore').fadeOut();
	$('.ui_loadmore').remove();	
}
var showload =function(_str,haveico){
	if(_str==undefined)_str='';
	if(haveico==undefined)haveico=true;
	removeload();
	var tmp = "<div class='ui_loadmore'><span>";
	if(_str=='') _str="正在加载";
	tmp +=_str+'</span>';
	if( haveico)tmp+="<em class='ui_ico ui_loading'></em>";
	tmp +="</div>";
	$('body').append(tmp);
}
var add_hotel_to = function(_link,_imgurl,_name,_price,hasvote,_ever,_severitem,_address){
	var tmp='';
	tmp+='<a href="'+_link+'" class="item">';
    tmp+='<div class="ui_img_auto_cut" style="height:'+setheight+'px"><img src="'+_imgurl+'" /></div>'
    tmp+='<div class="allin_box">';
    tmp+='<div class="name">'+_name+'</div>'
    tmp+='<div class="coupon"><div class="ui_price">'+_price+'</div>';
	if ( hasvote) tmp+='<div class="backvote">入住返券</div></div>';
	if ( _ever!=null && _ever!='')tmp+='<div class="ever hide"><span class="ui_color_gray">'+_ever+'条评论</span></div>';
	if ( _severitem!='' && _severitem!=null){
    	tmp+='<div class="sever">';
		var thisitem=_severitem.split(',');
		for(var i=0;i<thisitem.length;i++)
			tmp+='<em class="iconfont">'+thisitem[i]+'</em>';
		tmp+='</div>';
	}
	if ( _address!='' && _address!=null)
        tmp+='<div class="distance txtclip">'+_address+'</div>';
	tmp+='</div></a>';
	$('.hotel_list').append(tmp);
}