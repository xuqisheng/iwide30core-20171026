
var reg_phone = new RegExp(/^1\d{10}$/);
var reg_cid   = new RegExp(/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/);
var reg_email = new RegExp(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/);
var reg_pwd = new RegExp(/^[A-Za-z0-9\-]{6,12}$/);
$(function(){
	//共用
	$('input[name=tels]').keyup(function(event) {
		if($(this).val().length==11){
			$('input[name=tel]').val($(this).val());
			var str=$(this).val().substring(0,3)+'*****'+$(this).val().substring(8,11);
			$(this).val(str);
			$('.verification').addClass('actives');
		}else{
			$('.verification').removeClass('actives');
		}
	});
	$('.verification').click(function(){
		if($(this).hasClass('actives')){
			var This=$(this);
			$(this).removeClass('actives');
			var seconds=8;
			This.times=setInterval(function(){
				seconds--;
				This.html(seconds+'秒后重新获取');
				if(seconds==0){
					clearInterval(This.times);
					This.html('重新获取');
					This.addClass('actives');
				}
			},1000);
		}
	})
	function test_tel(){
		var f_tel=$('input[name=tel]').val();
		if(!reg_phone.test(f_tel)) return false;
		return true;
	}
	function test_name(){   //名字
		if($('input[name=name]').val()==''){
			$('input[name=name]').addClass('bg_ico_close');
			return false
		}
	}
	test_name();
	$('input[name=name]').keyup(function(event) { 
		$(this).removeClass('bg_ico_close')
	});
	function test_body_id(){
		var f_body_id=$('input[name=body_id]').val();
		if(!reg_cid.test(f_body_id)){
			$('input[name=body_id]').addClass('bg_ico_sigh');
			$('input[name=body_id]').val('');
			$('input[name=body_id]').attr('placeholder','对不起，当前证件号有误');
			return false;
		} 
		$('input[name=body_id]').removeClass('bg_ico_sigh');
		return true;
	}
	function test_pwd(){
		var f_pwd=$('input[name=pwd]').val();
		if(!reg_pwd.test(f_pwd)) return false;
		return true;
	}
	function test_email(){
		var f_email=$('input[name=email]').val();
		if(!reg_email.test(f_email)) return false;
		return true;
	}

	//登录
	function reg2(){
		for(var i=0;i<$('.form_list input').length;i++){
			if($('.form_list input').eq(i).val()==''){
				return false;
			}
		}
		console.log(test_tel());
		if(!test_tel()) return false;
		if(!test_pwd()) return false;
		//if(!test_email()) return false;
		$.ajax({
			type: "POST",
			url: "www.baidu.com",
			data: $('.form_list').serialize(),
			dataType: "json",
			success: function(data){
				
			}
		});
	}
	$('.land_btn').click(function(){
		reg2();
	})

	//注册页面
	function reg(){
		for(var i=0;i<$('.form_list input').length;i++){
			if($('.form_list input').eq(i).val()==''){
				return false;
			}
		}
		console.log(123)
		if(!test_body_id()) return false;
		if(!test_pwd()) return false;
		if(!test_email()) return false;
		if(!test_tel()) return false;
		// $.ajax({
		// 	type: "POST",
		// 	url: "www.baidu.com",
		// 	data: $('.form_list').serialize(),
		// 	dataType: "json",
		// 	success: function(data){
				
		// 	}
		// });
	}
	$('.register').click(function(){
		reg();
	})

	//个人信息

	function reg3(){
		for(var i=0;i<$('.form_list input').length;i++){
			if($('.form_list input').eq(i).val()==''){
				return false;
			}
		}
		if(!test_tel()) return false;
		if(!test_body_id()) return false;
		if(!test_pwd()) return false;
		if(!test_email()) return false;
		$.ajax({
			type: "POST",
			url: "www.baidu.com",
			data: $('.form_list').serialize(),
			dataType: "json",
			success: function(data){
				
			}
		});
	}
    $('.preservation').click(function(){
    	reg3();
    })
    //重置密码

	$('input[name=telss]').keyup(function(event) {
		if($(this).val().length==11){
			$('input[name=tel]').val($(this).val());
			$('.verification').addClass('actives');
		}else{
			$('.verification').removeClass('actives');
		}
	});
	function reg4(){
		console.log($('.form_list').serialize());
		for(var i=0;i<$('.form_list input').length;i++){
			if($('.form_list input').eq(i).val()==''){
				return false;
			}
		}
		if(!test_tel()) return false;
		if(!test_pwd()) return false;
		$.ajax({
			type: "POST",
			url: "www.baidu.com",
			data: $('.form_list').serialize(),
			dataType: "json",
			success: function(data){
				
			}
		});
	}
    $('.reset').click(function(){
    	reg4();
    })
    //重置支付密码
	function reg5(){
		console.log($('.form_list').serialize());
		for(var i=0;i<$('.form_list input').length;i++){
			if($('.form_list input').eq(i).val()==''){
				return false;
			}
		}
		if(!test_tel()) return false;
		if(!test_pwd()) return false;
		$.ajax({
			type: "POST",
			url: "www.baidu.com",
			data: $('.form_list').serialize(),
			dataType: "json",
			success: function(data){
				
			}
		});
	}
	var $input=$('.pwd_list_input input');  //支付效果
		$('#pwd_input').on('input',function(){
			$(this).focus();
			var pwd=$(this).val().trim();
			if(pwd>6){pwd=pwd.substr(0,6)}
			for(var i=0;i<$input.length;i++){
				$input.eq(i).val(pwd[i]);
			}
		})
    $('.reset_pay').click(function(){
    	reg5();
    })
    //我的余额  我的积分
    $('.recharge >div').click(function(){
    	var _index=$(this).index();
    	$(this).addClass('active').siblings().removeClass('active');
    	$('.containers_list >section').eq(_index).show().siblings().hide();
    })

    //我要购卡支付页
    $('.pay_mode >div').click(function(){
    	console.log(12);
    	$(this).addClass('check_item').siblings().removeClass('check_item');
    })
})