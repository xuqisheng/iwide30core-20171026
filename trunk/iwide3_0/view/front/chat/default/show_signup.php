<?php require_once 'header.php';?>
<?php echo referurl('css','activity.css',2,$media_path) ?>
<body>
<iframe id="submitform" name="submitform" src="" style="display:none"></iframe>
<form id="sendmsg" name="sendmsg" method="post" target="submitform" action="">
<div class="reg_head">个人信息登记</div>
<div class="reg_box">
	<div class="input_box">
    	<span>姓&nbsp;&nbsp;名</span>
        <input type="text" name="uname">
    </div>
	<div class="input_box">
    	<span>证件号</span>
        <input type="text" name="idcard">
    </div>
	<div class="input_box">
    	<span>手机号</span>
        <input type="text" name="telephone">
    </div>
	<div class="input_box">
    	<span>房间号</span>
        <input type="text" name="roomid">
    </div><input type="hidden" name="dosubmit" value="1" />
</div>
<div style="padding-top:15%">
	<a class="foot_btn">保存信息</a>
</div><?php if($csrf){echo '<input type="hidden" name="'.$csrf['name'].'" value="'.$csrf['hash'].'" />';}?>
</form>
<script type="text/javascript">
$(function(){
    $('.foot_btn').click(function(){
	    $('#sendmsg').submit();
	});
	
	
});
</script>
