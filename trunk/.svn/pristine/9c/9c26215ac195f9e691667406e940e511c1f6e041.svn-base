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
<?php include 'wxheader.php' ?>
<link rel="stylesheet" href="<?php echo base_url("public/member/zhouji/css/zhouji.css");?>" type="text/css">
<title>会员登记</title>
</head>
<style>
	body,html{background-color: #f5f5f5;}
	input::-webkit-input-placeholder, textarea::-webkit-input-placeholder {color: #cccccc; } 
	input:-ms-input-placeholder, textarea:-ms-input-placeholder {color: #cccccc; }
	.member_wrapper{background-color: white;margin: 0px 20px;padding: 35px 15px 35px 15px;position: relative;border-radius: 4px;}
	.logo_wraper{position: absolute;width: 100%;text-align: center;left: 0px;top: -40px;}
	.logo{display: inline-block;}
	.item{border-bottom: 0.5px dashed #ebebeb;padding-bottom: 10px;}
	.item span:nth-of-type(1){text-align: left;width: 93px;}
	.item span:nth-of-type(2){width: 200px;}
	.logo_text{margin-top: 20px;}
	.explain{margin-top: 20px;}
	.item input{border: 0px solid #ababab;width: 100%;border-radius: 0px;}
</style>
<body class="padding_t padding_b">
<div class="member_wrapper">
	<div class="logo_wraper">
		<div class="logo"><img src="<?php echo base_url("public/member/phase2");?>/images/changchun.jpg"></div>
	</div>
	<div class="center logo_text h32 color_d4b304" style="color:#ff9900;">尊敬的长春名人会员</div>
	<div class="center h30 color_ababab explain" style="width: 70%;">请填写您的会员号和个人信息,<br>我们会尽快审核您的资料</div>
	<form class="form"  action="#"  method="get">
		<div class="m_height_160" style="margin-top: 25px;">
			<div class="item">
				<span>会员号</span>
				<span><input class="use_member" type="text" name='membership_number' placeholder="请输入您的会员号" value='<?php  if (isset($member_info)) echo $member_info['membership_number'] ?>'/></span>
			</div>
			<div class="item">
				<span>姓名</span>
				<span><input class="use_name" type="text" name='name' placeholder="请输入您的姓名" value='<?php if (isset($member_info)) echo $member_info['name'] ?>'  /></span>
			</div>
			<div class="item">
				<span>手机号码</span>
				<span><input class="use_tel" type="text" name='telephone' placeholder="请输入您手机号码"  value='<?php  if (isset($member_info)) echo $member_info['telephone'] ?>'/></span>
			</div>
			<div class="item">
				<span>身份证号</span>
				<span><input class="use_id" type="text" name='id_card_no' placeholder="请输入您的身份证号" value='<?php  if (isset($member_info)) echo $member_info['id_card_no'] ?>' /></span>
			</div>
			<!-- <div class="item">
				<span><i class="color_d4b304">*</i>邮箱地址:</span>
				<span><input  class="use_email" type="text" name='email' value='<?php if (isset($member_info)) echo   $member_info['email'] ?>'/></span>
			</div> -->
			<!-- <div class="item">
				<span>公司名称:</span>
				<span><input type="text" name='company_name' value='<?php  if (isset($member_info)) echo $member_info['company_name'] ?>'/></span>
			</div>
			<div class="item">
				<span>职称:</span>
				<span><input type="text"name='duty'value='<?php   if (isset($member_info)) echo $member_info['duty'] ?>' /></span>
				<input type="hidden" name="type" value="old" />
			</div> -->
		</div>
	</form>
</div>
<div class="center btn_list m_t_20" style="margin-top: 35px;">
	<a class="c_fff bg_013763 submits h30" href="javascript:;" style="background-color: #ff9900;border:0px;display: block;
    margin: 0px 20px;width:auto;">提交</a>
</div>
<style>
.fixed{position:fixed;top:0px;left:0px;width:100%;height:100%;text-align:center;display:none;}
.fixed img{width:50px;height:50px;display:inline-block;margin-top:80%;}
</style>
<div class="fixed">< img src="images/loading.gif"></div>
<script src="<?php echo base_url("public/member/zhouji/js");?>/jquery.js"></script>
<script src="<?php echo base_url("public/member/zhouji/js");?>/reg.js"></script>
<script>
window.alert = function(name){  
     var iframe = document.createElement("IFRAME");  
    iframe.style.display="none";  
    iframe.setAttribute("src", 'data:text/plain,');  
    document.documentElement.appendChild(iframe);  
    window.frames[0].window.alert(name);  
    iframe.parentNode.removeChild(iframe);  
}  
window.onload=function(){
	//s5 504;
	if($(document).height()==418){

	};
	function reg(){
		if(!test_use_member()||!test_use_name()||!test_use_tel()||!test_use_id()){
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