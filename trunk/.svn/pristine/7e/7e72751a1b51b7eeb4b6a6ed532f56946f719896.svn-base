<?php include 'header.php';?>

<style>
body,html{background:#fff}
</style>
<body class="bg_index">
<div class="logo"><img src="<?php echo base_url('public/price/default/images/bij.png');?>"></div>
<form id='form' action="<?php echo site_url('price/paritys/sign_in');?>" method="post">
<div class="form c_fff">
	<div class="border_bottom form_list">
		<!--<img class="ico_img" src="<?php echo base_url('public/price/default/images/name.png');?>">-->
		<input class="center" style="width:100%;" type="text" name="username" placeholder="请输入账号">
	</div>
	<div class="border_bottom form_list">
		<!--<img class="ico_img" src="<?php echo base_url('public/price/default/images/post.png');?>">-->
		<input class="center" style="width:100%;" type="password" name="password" placeholder="请输入密码">
	</div>
	<div class="center" id="warn_text" style="color:red;"><?php echo $warn_text;?></div>
	<buttom class="btn m_t_45" id="sign">立即登录</buttom>
</div>
</form>
<div class="copyright"><img src="<?php echo base_url('public/price/default/images/jfk.png');?>"></div>
<script>
window.onload=function(){
	if($(document).height()==418){
		$('.form').css({"margin":"30% auto 0"});
	};
	$('#sign').click(function(){
		var un = $('input[name=username]').val();
		var pw = $('input[name=password]').val();
		if(un==''){
			$('#warn_text').text('请输入账号！');
			return;
		}
		if(pw==''){
			$('#warn_text').text('请输入密码！');
			return;
		}
		$('#form').submit();
	});
}
</script>
</body>
</html>