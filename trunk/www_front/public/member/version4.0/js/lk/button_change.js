// JavaScript Document
$(document).ready(function(e) {
	$('.item').find('input').blur(function(){
		for( var i=0; i<$('.item').find('input').length;i++){
			if(　$('.item').find('input').eq(i).val()==''){
				$('.ui_foot_btn').addClass('disable')
			}
			else{
				$('.ui_foot_btn').removeClass('disable')
			}
		}
	})
});

