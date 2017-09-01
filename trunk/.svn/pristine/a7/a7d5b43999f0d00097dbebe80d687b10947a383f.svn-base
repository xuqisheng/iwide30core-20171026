// JavaScript Document


$(function(){
	$('.submit_btn').click(function(){
		if($('#prevend').val()==1){
			$.MsgBox.Alert('错误','请不要重复提交订单。');
			//$.MsgBox.Confirm('错误','请不要重复提交订单',function(){
				//点击了确定后回调;
			//});
			return false;
		}
		var name=$('#name').val();
		var tel=$('#tel').val();
		var bonus=$('#bonus').val();
		if(bonus){
			if(isNaN(bonus)||(parseInt(bonus)!=bonus)){
				$.MsgBox.Alert('错误','输入的悦银格式错误');
				return;
			}
			if(bonus*1<=0||bonus*1>$('#bonus').attr('max')*1){
				$.MsgBox.Alert('错误','输入的悦银数量不对');
				return;
			}
            if($('#consume_pwd').val()==''){
                $.MsgBox.Alert('错误','请输入消费密码');
				return;
			}
//            if(bonus % 100 != 0){
//                $.MsgBox.Alert('错误','悦银数量必须是100倍数');
//                bonus = bonus - (bonus % 100);
//                $("#bonus").val(bonus);
//                return;
//            }
		        }
		if(banlance_code==1&&$('#pay_type').val()=='balance'){
			if($('#consume_pwd').val()==''){
				$.MsgBox.Alert('温馨提示','请输入消费密码');
				return;
			}
		}
        if(point_pay_code==1&&$('#pay_type').val()=='point'){
            if($('#consume_pwd').val()==''){
                $.MsgBox.Alert('请输入消费密码');
                return;
            }
        }
		$('#prevend').val(1);
		data={
				name:name,
				tel:tel,
				paytype:$('#pay_type').val(),
				datas:JSON.stringify(roomnums),
				roomnos:JSON.stringify(roomnos), 
				coupons:JSON.stringify(coupons),
				startdate:$('#startdate').val(),
				enddate:$('#enddate').val(),
				hotel_id:$('#hotel_id').val(),
				price_codes:$('#price_codes').val(),
				price_type:$('#price_type').val(),
				bonus:bonus,
				consume_code:$('#consume_pwd').val()
			};
		data[csrf_name]=csrf_value;
		$.MsgBox.Alert('温馨提示','您的订单已经提交, 请稍候...');
		$('#mb_btnbox').remove();
		$.post('/index.php/hotel/hotel/saveorder',data,function(data){
			if(data.s==1){
				$('#prevend').val(1);
				window.location.replace(data.link);
				}
			else{
				$('#prevend').val(0);
				$.MsgBox.Alert('错误',data.errmsg.replace(/积分/g,'悦银'));
				
			}
		},'json');
	});
	var testval = function(_this){
		if(_this.val()==''){
			$.MsgBox.Alert('错误',_this.attr('placeholder'));
			return false;
		}
		if(_this.hasClass('phone')){
			if(!reg_phone.test(_this.val())){
				$.MsgBox.Alert('错误','手机号码格式有误');
				return false;
			}
		}
	}
	$('input[required]').blur(function(){
		testval($(this));
	})
	$('.pay_way').click(function(){
			$(this).addClass('ischeck').siblings().removeClass('ischeck');
			$('#pay_type').val($(this).attr('pay_type'));
		ajax_coupon();
	})
	$('.room_num').click(function(){
		toshow($('.chooseroom_pull'));
	})
	$('.item').click(function(){
		$(this).find('input').focus();
	})
	$('.sure_btn').click(function(){
		toclose();
		if( $('.chooseroom_pull .default').hasClass('ischoose')){
			$('.room_num .num').html( $('.chooseroom_pull .default').html());
			return;
		}
		roomnos={};
		var roomnum='已选';
		for ( var i=0;i<$('.ischoose').length;i++){
			roomnum+=$('.ischoose').eq(i).find('span').html();
			rid=$('.ischoose').eq(i).parent().attr('rid');
			rno=$('.ischoose').eq(i).attr('rno');
			if(roomnos[rid]==undefined)
				roomnos[rid]={};
			roomnos[rid][rno]=rno=$('.ischoose').eq(i).attr('rnn');
			if(i<$('.ischoose').length-1) roomnum+=','
		}
		var count=$('select.num').val();
		if($('.ischoose').length<count){
			roomnum+=',前台分配'+(count-$('.ischoose').length)+'间';
		}
		$('.room_num .num').html(roomnum);
	});
	$('.roomid').click(function(){
		var _this=$(this);
		var _parent = $(this).parents('.item');
		var all_count=parseInt($('.chooseroom_pull .default tt').html());
		var cur_count=parseInt(_this.siblings('.room_name').find('span').html());
		if ( _this.hasClass('ischoose') ){
			_this.removeClass('ischoose');
			$('.roomid').siblings().removeClass('disable');
			return;
		}
		if ( _this.siblings('.ischoose').length >= cur_count || $('.item .ischoose').length >= all_count){
			return;
		}
		
		if ( $('.item .ischoose').length >= all_count-1){
			$('.roomid').addClass('disable');
			_this.removeClass('disable');
			$('.ischoose').removeClass('disable');
		}
		if ( _this.siblings('.ischoose').length >= cur_count-1){
			_this.siblings('.roomid').addClass('disable');
			$('.ischoose').removeClass('disable');
		}
		_this.addClass('ischoose');
		$('.default').removeClass('ischoose');
	})
	$('.default').click(function(){
		$(this).addClass('ischoose');
		$('.roomid').removeClass('ischoose');
		$('.roomid').removeClass('disable');
	})
});