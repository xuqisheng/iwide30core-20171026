<!DOCTYPE html>
<html lang="en">
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,initial-scale=1,user-scalable=0">
<link rel="stylesheet" href="<?php echo base_url("public/member/zhouji/css/zhouji.css");?>" type="text/css">
<title>新会员注册</title>
</head>
<body class="padding_t padding_b">
<div class="logo"><img src="<?php echo base_url("public/member/zhouji");?>/images/bij.jpg"></div>
<div class="center logo_text h30 color_d4b304">尊敬洲际会员,您好</div>
<div class="center w_55 h30 color_ababab explain">欢迎注册洲际会员</div>
<form class="form" action="#" method="get">
	<div class="m_height_160">
		<div class="item">
			<span><i class="color_d4b304">*</i>姓名:</span>
			<span><input class="use_name" type="text" name='name' value='<?php if (isset($member_info)) echo $member_info['name'] ?>' /></span>
		</div>
		<div class="item">
			<span><i class="color_d4b304">*</i>手机号码:</span>
			<span><input class="use_tel" type="text"name='telephone' value='<?php  if (isset($member_info)) echo $member_info['telephone'] ?>'/></span>
		</div>
		<div class="item">
			<span><i class="color_d4b304">*</i>邮箱地址:</span>
			<span><input  class="use_email" type="text" name='email' value='<?php if (isset($member_info)) echo   $member_info['email'] ?>'/></span>
		</div>
		<div class="item">
			<span><i class="color_d4b304">*</i>身份证号:</span>
			<span><input class="use_id" type="text" name='id_card_no' value='<?php  if (isset($member_info)) echo $member_info['id_card_no'] ?>'/></span>
		</div>
		<div class="item">
			<span>公司名称:</span>
			<span><input type="text" name='company_name' value='<?php  if (isset($member_info)) echo $member_info['company_name'] ?>'/></span>
		</div>
		<div class="item">
			<span>职称:</span>
			<span><input type="text" name='duty' value='<?php   if (isset($member_info)) echo $member_info['duty'] ?>' /></span>
			<input type="hidden" name="type" value="new" />
		</div>
	</div>
	<div class="center btn_list m_t_20">
		<a class="c_fff bg_013763 submits" href="javascript:;">提交</a>
	</div>
</form>

<script src="<?php echo base_url("public/member/zhouji/js");?>/jquery.js"></script>
<script src="<?php echo base_url("public/member/zhouji/js");?>/reg.js"></script>
<script>
window.onload=function(){
	//s5 504;
	if($(document).height()==418){

	};

	function reg(){
		if(!test_use_name()||!test_use_tel()||!test_use_id()||!test_use_email()){
			return false;
		}
		return true;
	}
	$('.submits').click(function(){
		var params = $(".form").serialize();
		 params = decodeURIComponent(params,true);
		if(reg()){
		   $.ajax({
               	url:'<?php echo base_url("index.php/membervip/verify/save_verify");?>',
               	type:'POST',
                data:params,// 要提交的表单
                dataType:'json',
                success:function(res){
               	 if(res.err==0){
						alert(res.msg);
						window.location.href='<?php echo base_url("index.php/membervip/center");?>';
                      }else{
                      	alert(res.msg);
                          }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
				}
            });
		}
		
	})
}
</script>
</body>
</html>