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
			name:/^[\u4E00-\u9FA5]{2,4}/g,
			identity:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
			_phon:/^[1]\d{10}$/ 
		}

	$('.use').blur(function(){
		if(!json.name.test($(this).val())){			
			$(this).val("请输入2~4位中文名");
		}
	})	
	$('.identit').blur(function(){
		if(!json.identity.test($(this).val())){			
			$(this).val("请输入正确身份证");
			$(this).parent().removeClass('all_right');
		}else if(json.identity.test($(this).val())){
			$(this).parent().addClass('all_right');
			}
	})
	$('.phone').blur(function(){
		if(!json._phon.test($(this).val())){			
			$(this).val("请输入正确手机号码");
			$('.floa').removeClass('send');
		}else if(json._phon.test($(this).val())){
				$('.floa').addClass('send');
			}
	})
})
	
