<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,user-scalable=0">
<title>激活社群客</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/global.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/group.css');?>">
<script src="<?php echo base_url('public/club/scripts/jquery.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/ui_control.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/alert.js');?>"></script>
</head>
<body class="pad3">
<div class="statustep webkitbox center bg_fff bdradius">
	<div>
    	<span class="bg_main h24">1</span>
        <p class="h22 color_main">填写信息</p>
    </div>
    <div>
    	<span class="bg_555 h24">2</span><hr>
        <p class="h22">激活</p>
    </div>
    <div>
    	<span class="bg_555 h24">3</span><hr>
        <p class="h22">享受专属价</p>
    </div>
</div>
<div class="detail_list bg_fff bdradius martop overflow">
    <div class="pad3"><em class="iconfont icon">&#xE60f;</em>尊贵的客户，请激活您的社群客信息</div>
    <ul class="pad10" style="padding-left:32px; padding-top:0">
    	<li>社群名：<?php echo $club_name;?></li>
        <?php if(!empty($price_name)){ ?>
    	<li>订房价格：<?php echo $price_name;?></li>
        <?php } if(!empty($soma_name)){ ?>
        <li>商城价格：<?php echo $soma_name;?></li>
        <?php }?>
    	<li>有效期：<?php echo $valid_time;?></li>
    </ul>
</div>
<form id="pinfo" class="list_style_2 add_new_list martop bdradius overflow" method="post">
	<div class="input_item webkitbox">
    	<p><em class="iconfont">&#xE606;</em>姓名</p>
        <p><input name="" type="text" id="username" placeholder="请输入姓名" /></p>
    </div>
    <div class="input_item webkitbox">
    	<p><em class="iconfont">&#xE608;</em>手机</p>
        <p><input name="" type="tel" id="phone" placeholder="请输入手机号码" value="" /></p>
    </div>
</form>
<div class="whiteblock bdradius overflow color_main" style="padding-left:32px">温馨提示:<br>加入前请确认你要加入的社群客的信息</div>
<div class="foot_btn martop">
	<button id="_submit" class="btn_main h28 submitbtn" type="button">激活</button>
</div>

</body>
<script>
var testphone = /^1\d{10}$/;
var click=0;
$(document).ready(function(){
   $("#_submit").click(function(){
       if(click !=0){
           return;
       }
	   for( var i=0; i<$('form input').length; i++){
		   if ($('forminput').eq(i).val()==''){
			   $.MsgBox.Alert($('form input').eq(i).attr('placeholder'));
			   return;
		   }
	   }
	   if (  $('#phone').length!=0 && !testphone.test($('#phone').val()) ){
		   $.MsgBox.Alert('输入的手机号码格式有误');
		   return;
	   }

       if (  $('#username').val()=='' ){
           $.MsgBox.Alert('请输入登记姓名');
           return;
       }

       var postUrl = "<?php echo site_url('club/Club/add_new_customer');?>";

       $.ajax({

           type: 'POST',

           url: postUrl,

           data: {
               name:$('#username').val(),
               tel: $('#phone').val(),
               '<?php echo $csrf_token; ?>':'<?php echo $csrf_value; ?>',
               cid:'<?php echo $club_id; ?>'


           },

           success: function(e){
               console.log(e);
               if(e==1){
                   click = 1;
                   $.MsgBox.Alert('登记成功');
                   location.href='./scan_qrcode?cid='+'<?php echo $club_id; ?>'+'&id='+'<?php echo $inter_id; ?>';
//                   location.href='./scan_qrcode';
               }else if(e==2){
                   $.MsgBox.Alert('登记失败');
               }
           }

       });
   });
});
</script>
</html>
