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
<title>会员信息</title>
</head>
<body>
<style>
<!--
.ui_btn_list .item span,.ui_normal_list .item span{ font-size:0.65rem;}
-->
</style>
<div class="ui_normal_list ui_border">
	<a  class="item">
    	<tt style="display:inline-block;padding-top:1.25rem">头像</tt>
    	<span class="user_img"><img src="<?php if(isset($info)) echo $info['headimgurl'];?>"></span>
    </a>
</div>
<div class="ui_btn_list ui_border">
    <?php if(isset($fields['name']) && ($fields['name']['must']==1)) {?>
	<a  class="item">
    	<tt><?php echo $fields['name']['name'];?></tt>
    	<span><?php echo $memberinfo->name;?></span>
    </a>
    <?php } ?>
    <?php if(isset($fields['sex']) && ($fields['sex']['must']==1)) {?>
	<a  class="item">
    	<tt><?php echo $fields['sex']['name'];?></tt>
        <span><?php if($memberinfo->sex==1) {echo "男";} elseif($memberinfo->sex==2) {echo "女";}elseif($memberinfo->sex=='0') {echo "性别未选择";};?></span>
    </a>
    <?php } ?>
    <?php if(isset($fields['telephone']) && ($fields['telephone']['must']==1)) {?>
	<a  class="item">
    	<tt><?php echo $fields['telephone']['name'];?></tt>
        <span><?php if(!empty($memberinfo->telephone))echo $memberinfo->telephone;?></span>
    </a>
    <?php } ?>
    <?php if(isset($fields['identity_card']) && ($fields['identity_card']['must']==1)) {?>
	<a  class="item">
    	<tt><?php echo $fields['identity_card']['name'];?></tt>
        <span><?php echo $memberinfo->identity_card;?></span>
    </a>
    <?php } ?>
    <?php if(isset($fields['dob']) && ($fields['dob']['must']==1)) {?>
	<a  class="item">
    	<tt><?php echo $fields['dob']['name'];?></tt>
        <span><?php if(!empty($memberinfo->dob)) echo $memberinfo->dob;?></span>
    </a>
    <?php } ?>
    <?php if(isset($fields['qq']) && ($fields['qq']['must']==1)) {?>
	<a  class="item">
    	<tt><?php echo $fields['qq']['name'];?></tt>
        <span><?php echo $memberinfo->qq;?></span>
    </a>
    <?php } ?>
    <?php if(isset($fields['email']) && ($fields['email']['must']==1)) {?>
	<a  class="item">
    	<tt><?php echo $fields['email']['name'];?></tt>
        <span><?php echo $memberinfo->email;?></span>
    </a>
    <?php } ?>
    <?php if(isset($fields['address']) && ($fields['address']['must']==1)) {?>
	<a  class="item">
    	<tt><?php echo $fields['address']['name'];?></tt>
        <span><?php echo $memberinfo->address;?></span>
    </a>
    <?php } ?>
    <?php if(isset($fields['custom1']) && ($fields['custom1']['must']==1)) {?>
	<a  class="item">
    	<tt><?php echo $fields['custom1']['name'];?></tt>
        <span><?php echo $memberinfo->custom1;?></span>
    </a>
    <?php } ?>
    <?php if(isset($fields['custom2']) && ($fields['custom2']['must']==1)) {?>
	<a  class="item">
    	<tt><?php echo $fields['custom2']['name'];?></tt>
        <span><?php echo $memberinfo->custom2;?></span>
    </a>
    <?php } ?>
    <?php if(isset($fields['custom3']) && ($fields['custom3']['must']==1)) {?>
	<a  class="item">
    	<tt><?php echo $fields['custom3']['name'];?></tt>
        <span><?php echo $memberinfo->custom3;?></span>
    </a>
    <?php } ?>
</div>
<?php if(isset($membermodel) && $membermodel=='login') {?>
<div style="padding-top:15%;">
	<a href="javascript:logout();" class="ui_foot_fixed_btn">
    	<em class="ui_ico  <?php if( isset($inter_id) && ($inter_id== 'a455510007')){ ?> ui_ico22 <?php }else{?> ui_ico10 <?php } ?>"></em>
        <div>退出登录</div>
    </a>
</div>
<?php } ?>
<script>
function logout()
{
	$.get("<?php echo site_url("member/account/logout");?>", {},
      	function(data){
		    data = eval("("+data+")");
      	    if(data.result) {
          	    //$.MsgBox.Alert(data.message);
          	    alert(data.message);
				location.href = "<?php echo site_url("member/center");?>";
      	    }
    });
}
</script>
</body>
</html>
