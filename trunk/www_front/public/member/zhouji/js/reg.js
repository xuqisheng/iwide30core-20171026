
	var reg_phone = new RegExp(/^1\d{10}$/);
	var reg_cid   = new RegExp(/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/);
	var reg_email = new RegExp(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/);
	function test_use_member(){
		var use_member=$.trim($('.use_member').val());
		if(use_member==''){
			alert("会员号不能为空,请输入。。。");
			return false;
		}
		return true;
	}
	function test_use_name(){
		var use_name=$.trim($('.use_name').val());
		if(use_name==''){
			alert("姓名不能为空,请输入。。。");
			return false;
		}
		return true;
	}
	function test_use_tel(){
		var use_tel=$('.use_tel').val();
		if(use_tel==''){
			alert("手机号码不能为空,请输入。。。");
			return false;
		}else if(!reg_phone.test(use_tel)){
			alert("输入的手机号码格式有误");
			return false;
		}
		return true;
	}
	function test_use_email(){
		var use_email=$('.use_email').val();
		if(use_email==''){
			alert("邮箱地址不能为空;请输入。。。");
			return false;
		}else if(!reg_email.test(use_email)){
			alert("输入的邮箱格式有误");
			return false;
		}
		return true;
	}
	function test_use_id(){
		var use_id=$('.use_id').val();
		if(use_id==''){
			alert("身份证号不能为空,请输入。。。");
			return false;
		}else if(!reg_cid.test(use_id)){
			alert("输入的身份证号码有误");
			return false;
		}
		return true;
	}