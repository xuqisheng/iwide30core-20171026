// JavaScript Document



var testval = function(_this,type,length){
	if(_this.val()==''){
		$.MsgBox.Alert(_this.attr('placeholder'));
		return false;
	}
	if (!type) type='mail';
	if(!reg_phone.test(_this.val())){
		$.MsgBox.Alert('错误','手机号码格式有误');
		return false;
	}
	return true;
}
$(function(){
	
	$('input[required]').blur(function(){
		testval($(this));
	})
});