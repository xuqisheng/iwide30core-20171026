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
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">

    <script src="<?php echo base_url('public/soma/scripts/alert.js');?>"></script>

<title>资料完善</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
<?php if($inter_id == 'a455510007'){ ?>
.ui_foot_btn{border:none;background: #d40f20;}
<?php }?>
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
<form id="pinfo" action="<?php echo base_url("index.php/member/perfectinfo/save");?>" method="post">
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
    	<input name="telephone" type="tel" placeholder="请输入<?php echo $fields['telephone']['name'];?>" <?php if(isset($inter_id)) echo "disabled='disabled'";?> value="<?php if(isset($member) && !empty($member->telephone)) echo $member->telephone;?>">
    </div>
	<!-- <div class="item">
    	<tt>短信验证</tt>
    	<input type="tel" name="sms" style="width:43%" placeholder="请输入短信验证码">
        <button id="sms" class="ui_normal_btn">发送验证码</button>
    </div> -->
    <?php } ?>
    <?php if(isset($fields['sex']) && ($fields['sex']['must']==1)) {?>
    <div class="item">
    	<tt><?php echo $fields['sex']['name'];?></tt>
    	<select name="sex">
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
</div>
<input id="sub" class="ui_foot_btn" type="button" value="保存">
</form>
<script>
$(document).ready(function(){
	$("#sms").click(function() {
        if($("input[name='telephone']").val().length==0) {
            $.MsgBox.Alert("请输入手机号码!");
            return false;
        }
	    var tel = $("input[name='telephone']").val();
		$.get("<?php echo site_url("member/center/sendsms");?>", {telephone:tel},
		    function(data){
		});
        $(this).attr('disabled',"true");
		$(this).addClass("ui_disable_btn");

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
       /*if($("input[name='sms']").val().length==0) {
           $.MsgBox.Alert("请输入短信验证码!");
           return false;
       }*/
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
       <?php if(isset($fields['telephone']) && ($fields['telephone']['must']==1)) {?>
       var sms = $("input[name='sms']").val();
       $.get("<?php echo site_url("member/center/smsvalid");?>", {sms:sms},
      	   function(data){
      		  if(data) {
          		  $("#pinfo").submit();
      		  } else {
          		  $.MsgBox.Alert("验证码不正确!");
      		  }
       });
       <?php } else { ?>
       $("#pinfo").submit();
       <?php } ?>
   });
});
</script>
</body>
</html>
