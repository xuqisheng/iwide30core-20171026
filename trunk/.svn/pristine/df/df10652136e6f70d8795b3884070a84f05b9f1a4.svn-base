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
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>

<link href="<?php echo base_url('public/soma/styles/global.css');?>" rel="stylesheet">
<script src="<?php echo base_url('public/soma/scripts/alert.js');?>"></script>
    
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">


<title>用户注册</title>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
#imgcode{float:none; position:relative; width:5rem;}
#imgcode img{width:4.5rem !important; height:1.4rem; position:absolute; top:-35%;}
input[type=radio]{ width:0.55rem; height:0.55rem; vertical-align:middle;-webkit-appearance: radio;}
.ui_normal_btn,.ui_foot_btn{border:none;background: #d40f20;}
.ui_normal_btn.disable{background: #e3e3e3;color:#FFF}
-->
</style>
</head>
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
<form id="pinfo" action="<?php echo site_url("member/account/registersave");?>" method="post">

<div class="ui_normal_list ui_border">
    <?php if(isset($fields['name']) && ($fields['name']['must']==1)) {?>
	<div class="item">
    	<tt><?php echo $fields['name']['name'];?></tt>
    	<input name="name" type="text" placeholder="请输入<?php echo $fields['name']['name'];?>" value="<?php if(isset($member)) echo $member->name;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['sex']) && ($fields['sex']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['sex']['name'];?></tt>
    	<select name="sex"　style="width:100%;border: 1px solid #fd4;">
    	    <option value="1" <?php if(isset($member) && ($member->sex==1)) echo "selected";?>>男</option>
    	    <option value="2" <?php if(isset($member) && ($member->sex==2)) echo "selected";?>>女</option>
    	</select>
    </div>
    <?php } ?>
    <?php if(isset($fields['identity_card']) && ($fields['identity_card']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['identity_card']['name'];?></tt>
    	<input name="identity_card" type="text" placeholder="请输入<?php echo $fields['identity_card']['name'];?>" value="<?php if(isset($member)) echo $member->identity_card;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['dob']) && ($fields['dob']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['dob']['name'];?></tt>
    	<input name="dob" type="text" placeholder="请输入<?php echo $fields['dob']['name'];?>" value="<?php if(isset($member) && !empty($member->dob)) echo $member->dob;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['qq']) && ($fields['qq']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['qq']['name'];?></tt>
    	<input name="qq" type="text" placeholder="请输入<?php echo $fields['qq']['name'];?>" value="<?php if(isset($member)) echo $member->qq;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['email']) && ($fields['email']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['email']['name'];?></tt>
    	<input name="email" type="text" placeholder="请输入<?php echo $fields['email']['name'];?>" value="<?php if(isset($member)) echo $member->email;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['address']) && ($fields['address']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['address']['name'];?></tt>
    	<input name="address" type="text" placeholder="请输入<?php echo $fields['address']['name'];?>" value="<?php if(isset($member)) echo $member->address;?>">
    </div>
    <?php } ?>
    <?php if(isset($fields['password']) && ($fields['password']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['password']['name'];?></tt>
    	<input name="password" type="password" placeholder="请输入<?php if($inter_id=='a421641095'){echo '6位数字密码';}else{ echo $fields['password']['name'];}?>" value="<?php if(isset($member)) echo $member->password;?>">
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
    <?php if(isset($fields['telephone']) && ($fields['telephone']['must']==1)) {?>
    <div class="item">
        <tt><?php echo $fields['telephone']['name'];?></tt>
        <input name="telephone" type="tel" maxlength="11" placeholder="请输入<?php echo $fields['telephone']['name'];?>" value="<?php if(isset($member) && !empty($member->telephone)) echo $member->telephone;?>">
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
                <input type="tel" name="sms" style="width:43%" placeholder="请输入短信验证码" max="6">
                <button id="sms" class="ui_normal_btn">发送验证码</button>
            </div>


    <?php } ?>
</div>
<input id="sub" class="ui_foot_btn" type="button" value="提交注册">
</form>
<script>
$(document).ready(function(){
	$("#sms").click(function() {
        if(_sent)return;
        if($("input[name='telephone']").val().length==0) {
            $.MsgBox.Alert("请输入手机号码!");
            return false;
        }
        var getstr= "";
        <?php if(isset($fields['name']) && ($fields['name']['must']==1)) {?>
        	getstr += 'name=';
        	getstr += $("input[name='name']").val();
        	getstr +='&';
        <?php } ?>
        <?php if(isset($fields['telephone']) && ($fields['telephone']['must']==1)) {?>
        	getstr += 'telephone=';
	    	getstr += $("input[name='telephone']").val();
	    	getstr +='&';
    	<?php } ?>
    	<?php if(isset($fields['sms']) && ($fields['sms']['must']==1)) {?>
    	getstr += 'sms=';
    	getstr += $("input[name='sms']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['identity_card']) && ($fields['identity_card']['must']==1)) {?>
    	getstr += 'identity_card=';
    	getstr += $("input[name='identity_card']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['sex']) && ($fields['sex']['must']==1)) {?>
    	getstr += 'sex=';
    	getstr += $("select[name='sex']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['dob']) && ($fields['dob']['must']==1)) {?>
    	getstr += 'dob=';
    	getstr += $("input[name='dob']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['qq']) && ($fields['qq']['must']==1)) {?>
    	getstr += 'qq=';
    	getstr += $("input[name='qq']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['email']) && ($fields['email']['must']==1)) {?>
    	getstr += 'email=';
    	getstr += $("input[name='email']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['address']) && ($fields['address']['must']==1)) {?>
    	getstr += 'address=';
    	getstr += $("input[name='address']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['password']) && ($fields['password']['must']==1)) {?>
    	getstr += 'password=';
    	getstr += $("input[name='password']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['custom1']) && ($fields['custom1']['must']==1)) {?>
    	getstr += 'custom1=';
    	getstr += $("input[name='custom1']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['custom2']) && ($fields['custom2']['must']==1)) {?>
    	getstr += 'custom2=';
    	getstr += $("input[name='custom2']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['custom3']) && ($fields['custom3']['must']==1)) {?>
    	getstr += 'custom3=';
    	getstr += $("input[name='custom3']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['custom4']) && ($fields['custom4']['must']==1)) {?>
    	getstr += 'custom4=';
    	getstr += $("input[name='custom4']").val();
    	getstr +='&';
	    <?php } ?>
	    <?php if(isset($fields['custom5']) && ($fields['custom5']['must']==1)) {?>
    	getstr += 'custom5=';
    	getstr += $("input[name='custom5']").val();
	    <?php } ?>

        var picCode =  $("input[name='pic_verify']").val();
        if(picCode.length !=4){
            $.MsgBox.Alert('请输入正确的图片验证码');
            return false;
        }
        getstr +='picCode=';
        getstr += picCode;

        var tel = $('input[name=telephone]').val();

        if(tel.length !=11){
            $.MsgBox.Alert('请输入正确的手机号码');
            return false;
        }

        pageloading('验证会员中...',0.2);
        $.ajax({
            url:'<?php echo site_url("member/account/seMemberCheck");?>',
            dataType:'json',
            data:{tel:tel,picCode:picCode},
            type: 'POST',
            success:function(data){
                $('.page_loading').remove();
                if(data.status == 2){
                    $.ajax({
                           type: "GET",
                           dataType:'json',
                           url: "<?php echo site_url("member/center/sendsms");?>",
                           data: getstr,
                           success: function(data){
                               if(data.status== 1){
                                   cutdown();
                                   $.MsgBox.Alert(data.msg);
                                   $(this).attr('disabled',"true");
                                   $(this).addClass("ui_disable_btn");
                               }else{
                                    $.MsgBox.Alert(data.msg);
                               }
                               }
                        });

                }else{
                    $.MsgBox.Alert(data.msg);
                }
                    $('#picCodeImg').click();
            }
        });

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
           $.MsgBox.Alert("手机号码必须为11位!");
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
       <?php if(isset($fields['dob']) && ($fields['dob']['must']==1)) {?>
       if($("input[name='dob']").val().length==0) {
           $.MsgBox.Alert("请输入<?php echo $fields['dob']['name'];?>");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['qq']) && ($fields['qq']['must']==1)) {?>
       if($("input[name='qq']").val().length==0) {
           $.MsgBox.Alert("请输入<?php echo $fields['qq']['name'];?>");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['email']) && ($fields['email']['must']==1)) {?>
       if($("input[name='email']").val().length==0) {
           $.MsgBox.Alert("请输入<?php echo $fields['email']['name'];?>");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['address']) && ($fields['address']['must']==1)) {?>
       if($("input[name='address']").val().length==0) {
           $.MsgBox.Alert("请输入<?php echo $fields['address']['name'];?>");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['password']) && ($fields['password']['must']==1)) {?>
       if($("input[name='password']").val().length==0) {
           $.MsgBox.Alert("请输入<?php echo $fields['password']['name'];?>");
           return false;
       }
       <?php } ?>
       <?php if(isset($fields['password']) && ($fields['password']['must']==1 && $inter_id=='a421641095')) {?>
       if($("input[name='password']").val().length != 6) {
           $.MsgBox.Alert("请输入6位密码");
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

       $("#pinfo").submit();
   });


});

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
</script>
<div id="show_message" style="display:none"><?php if(isset($message)) echo $message;?></div>
<script>
$(document).ready(function(){
	if($("#show_message").html().length) {
		$.MsgBox.Alert($("#show_message").html());
	}
})
</script>
</body>
</html>
