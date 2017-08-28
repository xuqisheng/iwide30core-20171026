<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no" />
<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>速8酒店 - 绑定会员卡</title>
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<script src="<?php echo base_url('public/soma/scripts/ui_control.js');?>"></script>
<link rel="stylesheet" href="<?php echo base_url('public/member/super8/css/activate_card.css'); ?>">
</head>

<body>
<div class="wrap-outer wrap-jfk">
	<div class="wrap">
	
	<input type="hidden"   name ='tel'   id='tel'  value="<?php echo $tel?>"> 
        <div class="form-wrap form-jfk">
        	<div class="input-box">手机号码：<?php echo $tel?></div>
        		<div class="input-box">
				<input type="text" class="i-t-normal i-t-short" maxlength="4" name="pic_verify" placeholder="请输入验证码">

				<img id="picCodeImg" class="btn-check" src="<?php echo base_url('index.php/member/account/pic_code')."?id=".$inter_id."&openid={$openid}";?>" onClick="this.src='<?php echo base_url('index.php/member/account/pic_code')."?id=".$inter_id."&openid={$openid}";?>';" alt="点击刷新图片" title="点击刷新图片">
			</div>
            <div class="input-box">
            	<input type="text" class="i-t-normal i-t-short"  name='sms'   placeholder="短信验证码">
               <button class="btn-check"  id='get_sms' type="button">获取验证码</button>
                  <button class="btn-check btn-check-grey"   id='get_sms2' style="display:none  " type="button">剩余60秒</button>
            </div>
			<div class="jfk-tip"   style="display:none  ">请在5分钟内输入短信验证码以绑定会员卡</div>
        </div>
        <div class="btn-wrap">
        	<a href="javascript:;" class="btn">绑定</a>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
$(function(){
	$(".btn-check").click(function() {
		var getstr='';
		
		getstr += 'telephone='+$("#tel").val();
		if($("input[name='pic_verify']").val().length==0) {
			$.MsgBox.Alert("请输入图片验证码!");
			return false;
		}
		getstr+='&picCode='+$("input[name='pic_verify']").val();
		  $.ajax({
              type: "GET",
              dataType:'json',
              url: "<?php echo site_url("member/center/sendsms");?>",
              data      : getstr,
              success: function(data){
                  if(data.status== 1){
             	     $("#get_sms").hide();
          			$('#get_sms2').show();
          			cutdown();
                  }else{
                       $.MsgBox.Alert(data.msg);
                  }
                  }
           });
			})
	
	$('.btn').on('click',function(){
	    if($('input[name=sms]').val().length!=6) {
            alert("请输入正确的验证码");
            return false;
        }
		$.ajax({
			url:'<?php echo site_url("member/account/bind_check"); ?>',
			type:'POST',
			dataType:'json',
			data:$('input'),
			
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
	
	
	
	
	        var time=60; //短信CD时间
			var _time; //定时器
			var _sent =false;
			var cutdown = function(){
			    _sent = true;
			    _time = window.setInterval(function(){
			        time--;
			        $('#get_sms2').html('剩余'+time+'秒');
			        if(time<=0){
			            window.clearInterval(_time);
			            $("#get_sms").show();
			            $('#get_sms2').hide();
			            _sent =false;
			            time = 60;
			        }
			    },1000);

			}
});
</script>
</html>
