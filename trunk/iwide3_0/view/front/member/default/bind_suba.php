<!doctype html>
<html>
<head>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<script src="<?php echo base_url('public/soma/scripts/ui_control.js');?>"></script>
<script src="<?php echo base_url('public/soma/scripts/alert.js');?>"></script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no" />
<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>速8酒店 - 绑定会员卡</title>
<link rel="stylesheet" href="<?php echo base_url('public/member/super8/css/activate_card.css'); ?>">
</head>

<body>
<div class="wrap-outer wrap-jfk">
	<div class="wrap">
		
	
        <div class="form-wrap form-jfk">
        	<div class="input-box"><input type="text" class="i-t-normal"   name='tel'   placeholder="请输入手机号码"></div>
			<div class="jfk-tip">请输入手机号码以绑定会员资格</div>
        </div>
        <div class="btn-wrap">
        	<a href="javascript:;"  id="btn-submit"  class="btn">验证</a>
        </div>
    </div>
</div>

</body>
<script type="text/javascript">
$(function(){
	
	$('#btn-submit').on('click',function(){
	    if($("input[name='tel']").val().length==0) {
            alert("请输入手机号码!");
            return false;
        }
	    if($("input[name='tel']").val().length!=11) {
            alert("请输入正确的手机号码!");
            return false;
        }
		$.ajax({
			url:'<?php echo site_url("member/account/bind_validate"); ?>',
			type:'POST',
			dataType:'json',
			data:$('input[name=tel]'),
			
			beforeSend:function(){
				pageloading('资料提交中...',0.2);
			},
			complete:function(){
				$('.pageloading').remove();
			},
			success:function(res){
				if(res.redirect){
					location.href=res.redirect;
				}
				if(res.errmsg){
					if(res.is_active){
						geneAbox(res.errmsg,res.route_to);
					}else{
						$.MsgBox.Alert(res.errmsg);
					}
				}
			}
		});
	});

});

	</script>
</html>
