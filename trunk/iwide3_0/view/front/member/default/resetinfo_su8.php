<!doctype html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
    
<link href="<?php echo base_url('public/soma/styles/global.css');?>" rel="stylesheet">
    
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<script src="<?php echo base_url('public/soma/scripts/alert.js');?>"></script>
<title>密码重置</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
.ui_normal_btn,.ui_foot_btn{border:none;background: #d40f20;}
.ui_normal_btn.disable{background: #e3e3e3;color:#FFF}
-->
</style>
<body>
<script>
wx.config({
    debug:false,
    appId:'<?php echo $signpackage["appId"];?>',
    timestamp:<?php echo $signpackage["timestamp"];?>,
    nonceStr:'<?php echo $signpackage["nonceStr"];?>',
    signature:'<?php echo $signpackage["signature"];?>',
    jsApiList: [
        'hideOptionMenu'
     ]
   });
   wx.ready(function () {
	   wx.hideOptionMenu();
   });
</script>
<form id="pinfo" action="<?php echo base_url("index.php/member/account/resetpasswordsave");?>" method="post">
<div class="ui_normal_list ui_border">
    <?php if(isset($fields['name']) && ($fields['name']['must']==1)) {?>
	<div class="item">
    	<tt><?php echo $fields['name']['name'];?></tt>
    	<input name="name" type="text" placeholder="请输入<?php echo $fields['name']['name'];?>" value="<?php if(isset($member)) echo $member->name;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['telephone']) && ($fields['telephone']['must']==1)) {?>
	<div class="item">
    	<tt><?php echo $fields['telephone']['name'];?></tt>
    	<input name="telephone" type="tel" placeholder="请输入<?php echo $fields['telephone']['name'];?>" value="<?php if(isset($member) && !empty($member->telephone)) echo $member->telephone;?>" maxlength="11">
    </div>

        <div class="item">
            <tt >验证码</tt>
            <input type="pic_code" name="pic_verify" maxlength="4" style="width:43%" placeholder="请输入验证码">
        </div>
        <div class="item" style="padding: 0px">
            <img id="picCodeImg" style="width:40%;height:10%;margin-left:25%;margin-top: 10px" src="<?php echo base_url('index.php/member/account/pic_code')."?id=".$inter_id."&openid=XX3WojhfNUD4JzmlwTzuKba1Mxxx";?>" onClick="this.src='<?php echo base_url('index.php/member/account/pic_code')."?id=".$inter_id."&openid=XX3WojhfNUD4JzmlwTzuKba1Mxxx";?>';" alt="点击刷新图片" title="点击刷新图片">
        </div>

	<div class="item">
    	<tt>短信验证</tt>
    	<input type="tel" name="sms" style="width:43%" placeholder="请输入短信验证码" maxlength="6">
        <button id="sms" class="ui_normal_btn">发送验证码</button>
    </div>
    <?php } ?>
    <?php if(isset($fields['identity_card']) && ($fields['identity_card']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['identity_card']['name'];?></tt>
    	<input name="identity_card" type="text" placeholder="请输入<?php echo $fields['identity_card']['name'];?>" value="<?php if(isset($member)) echo $member->identity_card;?>">
    </div>
    <?php } ?>

     <?php if(isset($fields['newpassword']) && ($fields['newpassword']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['newpassword']['name'];?></tt>
    	<input name="newpassword" type="password" placeholder="请输入<?php echo $fields['newpassword']['name'];?>" value="<?php if(isset($member)) echo $member->newpassword;?>">
    </div>
    <?php } ?>

    <?php if(isset($fields['email']) && ($fields['email']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['email']['name'];?></tt>
    	<input name="email" type="text" placeholder="请输入<?php echo $fields['email']['name'];?>" value="<?php if(isset($member)) echo $member->email;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['custom1']) && ($fields['custom1']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['custom1']['name'];?></tt>
    	<input name="custom1" type="text" placeholder="请输入<?php echo $fields['custom1']['name'];?>" value="<?php if(isset($member)) echo $member->custom1;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['custom2']) && ($fields['custom2']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['custom2']['name'];?></tt>
    	<input name="custom2" type="text" placeholder="请输入<?php echo $fields['custom2']['name'];?>" value="<?php if(isset($member)) echo $member->custom2;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['custom3']) && ($fields['custom3']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['custom3']['name'];?></tt>
    	<input name="custom3" type="text" placeholder="请输入<?php echo $fields['custom3']['name'];?>" value="<?php if(isset($member)) echo $member->custom3;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['custom3']) && ($fields['custom3']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['custom3']['name'];?></tt>
    	<input name="custom3" type="text" placeholder="请输入<?php echo $fields['custom3']['name'];?>" value="<?php if(isset($member)) echo $member->custom3;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['custom4']) && ($fields['custom4']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['custom4']['name'];?></tt>
    	<input name="custom4" type="text" placeholder="请输入<?php echo $fields['custom4']['name'];?>" value="<?php if(isset($member)) echo $member->custom4;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['custom5']) && ($fields['custom5']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['custom5']['name'];?></tt>
    	<input name="custom5" type="text" placeholder="请输入<?php echo $fields['custom5']['name'];?>" value="<?php if(isset($member)) echo $member->custom5;?>">
    </div>
    <?php } ?>
</div>
<input id="sub" class="ui_foot_btn" type="button" value="提交重置">
</form>
<script>
$(document).ready(function(){


    var time=60; //短信CD时间
    var _time; //定时器
    var _sent =false;
    var cutdown = function(){
        _sent = true;
        $('#sms').html(time+'s').addClass('disable');
        _time = window.setInterval(function(){
            time--;
            $('#sms').html(time+'s');
            if(time<=0){
                window.clearInterval(_time);
                $('#sms').html('重新发送').removeClass('disable');
                _sent =false;
                time = 60;
            }
        },1000);
    }

	$("#sms").click(function() {
        if($("input[name='telephone']").val().length==0) {
            $.MsgBox.Alert("请输入手机号码");
//            alert("请输入手机号码!");
            return false;
        }
	    var tel = $("input[name='telephone']").val();

        if(tel.length != 11){
            $.MsgBox.Alert("请输入正确的手机号码");
            return false;
        }

        var picCode =  $("input[name='pic_verify']").val();
        if(picCode.length !=4){
            $.MsgBox.Alert('请输入正确的图片验证码');
            return false;
        }

        pageloading('处理中，请稍后...',0.2);
        $.ajax({
            url:'<?php echo site_url("member/account/seMemberCheck");?>',
            dataType:'json',
            data:{tel:tel,picCode:picCode},
            type: 'POST',
            success:function(data){
                $('.page_loading').remove();
                if(data.status == 1){
                    if(_sent) return;
                    _sent = true;
                    cutdown();
                    $.get("<?php echo site_url("member/center/sendsmspassword");?>", {telephone:tel,picCode:picCode},
                        function(data){
                            $('#picCodeImg').click();
                            $.MsgBox.Alert(data);

                        });
                    $(this).attr('disabled',"true");
                    $(this).addClass("ui_disable_btn");

                }else{
                    $.MsgBox.Alert(data.msg);
                    $('#picCodeImg').click();
                }
               // setTimeout($('#picCodeImg').click(),1000);
            }

        })



		return false;
	});

   $("#sub").click(function(){
	   <?php if(isset($fields['name']) && ($fields['name']['must']==1)) {?>
       if($("input[name='name']").val().length==0) {
           $.MsgBox.Alert("请填写姓名!");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['telephone']) && ($fields['telephone']['must']==1)) {?>
       if($("input[name='telephone']").val().length==0) {
           $.MsgBox.Alert("请输入手机号码!");
           return false;
       }

       if($("input[name='telephone']").val().length!=11) {
           alert("手机号码必须为11位!");
           return false;
       }

       if($("input[name='sms']").val().length==0) {
           $.MsgBox.Alert("请输入短信验证码!");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['identity_card']) && ($fields['identity_card']['must']==1)) {?>
       if($("input[name='identity_card']").val().length==0) {
           $.MsgBox.Alert("请输入身份证号!");
           return false;
       }
       <?php } ?>

       <?php if(isset($fields['newpassword']) && ($fields['newpassword']['must']==1)) {?>
       if($("input[name='newpassword']").val().length==0) {
           alert("请输入新密码!");
           return false;
       }
       <?php } ?>

       <?php if(isset($fields['email']) && ($fields['email']['must']==1)) {?>
       if($("input[name='email']").val().length==0) {
           $.MsgBox.Alert("请输入<?php echo $fields['email']['name'];?>");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['custom1']) && ($fields['custom1']['must']==1)) {?>
       if($("input[name='custom1']").val().length==0) {
           $.MsgBox.Alert("请输入<?php echo $fields['custom1']['name'];?>");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['custom2']) && ($fields['custom2']['must']==1)) {?>
       if($("input[name='custom2']").val().length==0) {
           $.MsgBox.Alert("请输入<?php echo $fields['custom2']['name'];?>");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['custom3']) && ($fields['custom3']['must']==1)) {?>
       if($("input[name='custom3']").val().length==0) {
           $.MsgBox.Alert("请输入<?php echo $fields['custom3']['name'];?>");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['custom4']) && ($fields['custom4']['must']==1)) {?>
       if($("input[name='custom4']").val().length==0) {
           $.MsgBox.Alert("请输入<?php echo $fields['custom4']['name'];?>");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['custom5']) && ($fields['custom5']['must']==1)) {?>
       if($("input[name='custom5']").val().length==0) {
           $.MsgBox.Alert("请输入<?php echo $fields['custom5']['name'];?>");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['telephone']) && ($fields['telephone']['must']==1)) {?>

       if($("input[name='telephone']").val().length!=11) {
           $.MsgBox.Alert("手机号码必须为11位!");
           return false;
       }
       var sms = $("input[name='sms']").val();
       $.get("<?php echo site_url("member/center/smsvalid");?>", {sms:sms},
      	   function(data) {
    	      data = parseInt(data);
      		  if(data){
          		  $("#pinfo").submit();
      		  }else{
                  $.MsgBox.Alert("验证码不正确");
//        		  alert("验证码不正确!");
      		  }
       });
       <?php } else { ?>
       $("#pinfo").submit();
       <?php } ?>
   });
});
</script>
<div id="show_message" style="display:none"><?php if(isset($message)) echo $message;?></div>
<script>
    $(document).ready(function(){
        if($("#show_message").html().length) {
            $.MsgBox.Alert($("#show_message").html());
        }
        //密码由text转password
        $('.item tt').each(function(n){
            if(this.innerHTML == '新密码'){
                var x = $(this).parent().find('input');
                $(x).attr('type','password');
            }
        });
    })
</script>
</body>
</html>
