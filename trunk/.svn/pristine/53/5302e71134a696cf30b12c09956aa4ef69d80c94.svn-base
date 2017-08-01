// JavaScript Document

if(typeof(banlance_code)==undefined)
	var banlance_code=0;
if(typeof(point_pay_code)==undefined)
    var point_pay_code=0;
if(!point_name)
	var point_name='积分';
if(!pay_favour)
	var pay_favour=0;
var paytype_click=0;
$(function(){
	$('.submit').click(function(){
//        console.log(JSON.stringify(roomnums));return;
		if($('#prevend').val()==1){
			$.MsgBox.Alert('请不要重复提交订单。');
			//$.MsgBox.Confirm('请不要重复提交订单',function(){
				//点击了确定后回调;
			//});
			return false;
		}
/*		if($('#email').length){
			if(!$('#email').val()){
				$.MsgBox.Alert('请输入邮箱');
				return;
			}
			var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$/;
			if(!myreg.test($('#email').val())){
				$.MsgBox.Alert('请输入正确的邮箱');
				return;
			}
		}*/
		var name=$('#name').val();
		var tel=$('#tel').val();
		var bonus=$('#bonus').val();
		if(bonus){
			if(isNaN(bonus)||(parseInt(bonus)!=bonus)){
				$.MsgBox.Alert('输入的'+point_name+'格式错误');
				return;
			}
			if(bonus*1<=0||bonus*1>$('#bonus').attr('max')*1){
				$.MsgBox.Alert('输入的'+point_name+'数量不对');
				return;
			}
			if(part_bonus_set['max_use']!=undefined&&part_bonus_set['max_use']*1<bonus*1){
				$.MsgBox.Alert('您最多可使用'+part_bonus_set['max_use']+point_name+'呢');
				return;
			}
			if(part_bonus_set['use_rate']!=undefined&&(bonus*1)%(part_bonus_set['use_rate']*1)!=0){
				$.MsgBox.Alert('使用的'+point_name+'只能为'+part_bonus_set['use_rate']+'的倍数哦');
				return;
			}
			if(bonus_condition['poc']!=undefined&&bonus_condition['poc']==1){
				 var Length = 0;
		        for (var c in coupons) {
		            Length++;
		        }
		        if(Length>0){
					$.MsgBox.Alert('此价格不能同时使用'+point_name+'与优惠券！请重新选择');
					$('#bonus').val('');
					return;
		        }
			}
		}
		if(banlance_code==1&&$('#pay_type').val()=='balance'){
			if($('#consume_pwd').val()==''){
				$.MsgBox.Alert('请输入消费密码');
				return;
			}
		}
		data={
				name:name,
				tel:tel,
				// email:$('#email').val(),
				custom_remark:$('#custom_remark').val(),
				paytype:$('#pay_type').val(),
				datas:JSON.stringify(roomnums),
				roomnos:JSON.stringify(roomnos),
				coupons:JSON.stringify(coupons),
				add_services:JSON.stringify(add_services),
				startdate:$('#startdate').val(),
				enddate:$('#enddate').val(),
				hotel_id:$('#hotel_id').val(),
				price_codes:$('#price_codes').val(),
				price_type:$('#price_type').val(),
				bonus:bonus,
				consume_code:$('#consume_pwd').val(),
				intime:$('#intime').val(),
                invoice:$('#invoice_id').val(),
                package_info:$('#post_packages').val()
			};
		if($('input[key="extra_formdata"]').length>0){
			var ret=0;
			var ret_obj;
			var errmsg='';
			data['extra_formdata']={};
			$.each($('input[key="extra_formdata"]'),function(k,v){
				if($(this).attr('must')==1&&!$(this).val()){
					ret_obj=$(this);
					errmsg='请输入'+$(this).attr('placeholder');
					ret=1;
					return false;
				}
				if($(this).attr('type')=='date'||$(this).attr('type')=='datetime'){
					var tmp_date=new Date($(this).val());
					var year=tmp_date.getFullYear();
					if($(this).attr('min_year')&&year<$(this).attr('min_year')){
						ret_obj=$(this);
						errmsg=$(this).attr('tips');
						ret=1;
						return false;
					}
					if($(this).attr('max_year')&&year>$(this).attr('max_year')){
						ret_obj=$(this);
						errmsg=$(this).attr('tips');
						ret=1;
						return false;
					}
				}
				var key_type=$(this).attr('key_type') || '';
				var value_type=$(this).attr('name');
				var multi=0;
				if(value_type.indexOf('[]')>0){
					multi=1;
					value_type=value_type.substr(0,value_type.length-2);
				}
				if(key_type){
					if(data['extra_formdata'][key_type]==undefined){
						data['extra_formdata'][key_type]={};
					}
					if(data['extra_formdata'][key_type][value_type]==undefined){
						data['extra_formdata'][key_type][value_type]={};
					}
					if(multi){
						data['extra_formdata'][key_type][value_type][k]=$(this).val();
					}else{
						data['extra_formdata'][key_type][value_type]=$(this).val();
					}
				}else{
					if(data['extra_formdata']==undefined){
						data['extra_formdata']={};
					}
					if(data['extra_formdata'][value_type]==undefined){
						data['extra_formdata'][value_type]={};
					}
					if(multi){
						data['extra_formdata'][value_type][k]=$(this).val();
					}else{
						data['extra_formdata'][value_type]=$(this).val();
					}
				}

			});
			if(ret==1){
				$.MsgBox.Alert(errmsg,function(){
					ret_obj.focus();
				},function(){
					ret_obj.focus();
				});
				return;
			}
			data['extra_formdata']=JSON.stringify(data['extra_formdata']);
		}
		if($('input[name="customer[]"]').length>0){
			data['customer']=[];
			$.each($('input[name="customer[]"]'),function(k,v){
				data['customer'].push($(this).val());
			});
		}
		$('#prevend').val(1);
		data[csrf_name]=csrf_value;
		pageloading('您的订单已经提交, 请稍候');
		$('#mb_btnbox').remove();
		var order_sub_url=$('#order_sub_url').val();
        if(!order_sub_url){
            var order_sub_url='/index.php/hotel/hotel/saveorder';
        }
		$.post(order_sub_url,data,function(data){
			if(data.s==1){
				$('#prevend').val(1);
				window.location.replace(data.link);
				}
			else{
				$('#prevend').val(0);

				$.MsgBox.Alert(data.errmsg);
				 removeload();
			}
		},'json');
	});
	var testval = function(_this){
		if(_this.val()==''){
			_this.parents(".input_item").addClass("bg_ico_close");
			_this.attr('placeholder',_this.attr('placeholder'));
			return false;
		}else{
			_this.parents(".input_item").removeClass("bg_ico_close");
		}
		if(_this.hasClass('color1 h30 phone')){
			if(!reg_phone.test(_this.val())){
				_this.val('')
				_this.parents(".input_item").addClass("bg_ico_close");
				_this.attr('placeholder','请输入正确的手机号码');
				return false;
			}else{
				_this.parents(".input_item").removeClass("bg_ico_close");
			}
		}
	}
	$('input[required]').blur(function(){
		testval($(this));
	})
	$('.pay_list .pay_way').click(function(){
        if(click_check == true){
            $('#bonuspay2').click();
        }
		if(!$(this).hasClass('disable')){
			$(this).addClass('ischeck').siblings().removeClass('ischeck');
			if($(this).attr('pay_type')=='bonus'){
				$('.usevote').addClass('disable');
				$('#coupon_i').html('不可用');
				var tmpval=$('.num').val();
				real_price=total_price*tmpval;
				total_favour-=coupon_amount;
				coupon_amount=0;
				coupons={};
				$('#total_price').html((real_price-total_favour).toFixed(2));
			}else{
				if(coupon_amount>0){
					if(paytype_counts==undefined||paytype_counts==0){
						$('#coupon_i').html('已选￥'+coupon_amount);
					}
					else if(paytype_counts==1){
						$('#coupon_i').html('请重新选择优惠券');
						var tmpval=$('.num').val();
						real_price=total_price*tmpval;
						total_favour-=coupon_amount;
						coupon_amount=0;
						coupons={};
						$('#total_price').html((real_price-total_favour).toFixed(2));
					}
				}
				else
					$('#coupon_i').html('选择优惠券');
				$('.usevote').removeClass('disable');
			}
			if($(this).attr('pay_type')=='balance'&&banlance_code==1){
				$('#consume_code').show();
			}else{
				$('#consume_code').hide();
			}
			$('#pay_type').val($(this).attr('pay_type'));

            //根据支付方式显示不同的房间保留时间，退房时间
            if($(this).attr('delay-policy')!=''){
                $('#delay-policy').show().children('span').text($(this).attr('delay-policy'));
            }else{
                $('#delay-policy').hide();
            }
            if($(this).attr('retain-policy')!=''){
                $('#retain-policy').show().children('span').text($(this).attr('retain-policy'));
            }else{
                $('#retain-policy').hide();
            }
            total_favour-=pay_favour*1;
            pay_favour=$(this).attr('pfavour')*1;
            total_favour+=pay_favour*1;
//            $('#total_price').html((real_price-total_favour).toFixed(2));
			getBonusSet();
            use_vote();
            price_detail();
			// ajax_coupon();
		}
	})
//	$('.room_num').click(function(){
//		toshow($('.chooseroom_pull'));
//		var _h=$(window).height()-$('.chooseroom_pull .default').outerHeight()-$('.chooseroom_pull .sure_btn').outerHeight();
//		$('.chooseroom_pull .scroll').height(_h);
//	})
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