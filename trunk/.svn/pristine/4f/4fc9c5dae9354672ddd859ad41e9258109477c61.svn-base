// JavaScript Document
$(function(){
	var ba=true;
	$('.i_no').click(function(){
		if(ba){
			$(this).addClass('i_ge');
		}else{
			$(this).removeClass('i_ge');
		}
		ba=!ba;
	})
	var json={
			name:/^[\u4e00-\u9fa5]{2,4}$/,
			identity:/\d{15}|\d{18}/,
			_phon:/^1[0-9]{10}$/
		}
	$('.submit_b').click(function(){
		var _use=$('.use').val();
		var _identit=$('.identit').val();
		var _phone=$('.phone').val();
		var names=json.name.test(_use);
		var _identity=json.identity.test(_identit);
		var _iphone=json._phon.test(_phone);
		var boll=true;
		function form(){
			if(!names){
				$('.txt_in1').addClass('on');
				boll=false;
			}else{
				$('.txt_in1').removeClass('on');
			}
			if(!_identity){
				$('.txt_in2').addClass('on');
				boll=false;
			}else{
				$('.txt_in2').removeClass('on');
			}
			if(!_iphone){
				$('.txt_in3').addClass('on');
				boll=false;
			}else{
				$('.txt_in3').removeClass('on');
			}
			if(ba){
				alert('请选择我同意');
				boll=false
			};
			return boll;
		}
		if(form()){$('#b_form').submit();}
	})
})
	
