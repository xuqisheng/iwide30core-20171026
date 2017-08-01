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


    <script src="<?php echo base_url("public/member/public/js/viewport.js"); ?>"></script>
    <script src="<?php echo base_url("public/member/public/js/jquery.js"); ?>"></script>
    <script src="<?php echo base_url("public/member/public/js/ui_control.js"); ?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <link href="<?php echo base_url("public/member/public/css/global.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/public/css/ui.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/public/css/ui_style.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/public/css/index.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/public/css/price.css"); ?>" rel="stylesheet">

<?php echo referurl('js','viewport.js',1,$media_path) ?>
<?php echo referurl('js','jquery.js',1,$media_path) ?>
<?php echo referurl('js','ui_control.js',1,$media_path) ?>
<?php echo referurl('css','global.css',1,$media_path) ?>
<?php echo referurl('css','ui.css',1,$media_path) ?>
<?php echo referurl('css','ui_style.css',1,$media_path) ?>
<?php echo referurl('css','bind_company.css',1,$media_path) ?>
<title>协议企业绑定</title>
</head>
<body>
<script>
wx.config({
    debug:false,
    appId:'<?php echo $signPackage["appId"];?>',
    timestamp:<?php echo $signPackage["timestamp"];?>,
    nonceStr:'<?php echo $signPackage["nonceStr"];?>',
    signature:'<?php echo $signPackage["signature"];?>',
    jsApiList: [
        'hideOptionMenu'
     ]
   });
   wx.ready(function () {
	   wx.hideOptionMenu();
   });
</script>
<form id="pinfo" action="" method="post">
    <input type='hidden' name='<?php echo $csrf_token; ?>' value='<?php echo $csrf_value; ?>' />
    <div class="list_title">填写协议代码</div>

    <div class="ui_normal_list ui_border">
        <div class="item">
            <tt>协议代码</tt>
            <input name="" type="text" id='agreement' placeholder="请输入贵公司协议代码" />
        </div>
    </div>

    <div class="list_title" style=" margin-top:4%">填写个人信息</div>

    <div class="ui_normal_list ui_border">
        <div class="item">
            <tt>姓名</tt>
            <input name="" type="text" id="username" placeholder="请输入姓名" />
        </div>
        <div class="item">
            <tt>手机号码</tt>
            <input name="" type="tel" id="phone" placeholder="请输入手机号码" value="" />
        </div>
<!--        <div class="item">-->
<!--            <tt>协议企业</tt>-->
<!--            <input name="" type="text" placeholder="请输入协议企业" value="" />-->
<!--        </div>-->
    </div>
    <div class="notic">
        <p>温馨提示:<br>登记前请确认您是该企业员工，协议价预订入住时可能需要出示相关证件进行核验</p>
    </div>
    <input id="sub" class="ui_foot_btn" type="button" value="提交">
</form>
</body>
<script>
var testphone = /^1\d{10}$/;
$(document).ready(function(){
   $("#sub").click(function(){
	   for( var i=0; i<$('.ui_border input').length; i++){
		   if ($('.ui_border input').eq(i).val()==''){
			   alert($('.ui_border input').eq(i).attr('placeholder'));
			   return;
		   }
	   }
	   if (  $('#phone').length!=0 && !testphone.test($('#phone').val()) ){
		   alert('输入的手机号码格式有误');
		   return;
	   }

       var postUrl = "<?php echo site_url('company/Company/check_company');?>";

       $.ajax({

           type: 'POST',

           url: postUrl,

           data: {
               name:$('#username').val(),
               tel: $('#phone').val(),
               cp_code: $('#agreement').val(),
               inter_id:'',
               company_id:'',
               '<?php echo $csrf_token; ?>':'<?php echo $csrf_value; ?>'


           },

           success: function(e){

               if(e==1){
                   alert('提交成功');
                   location.href='./registered';
               }else if(e==2){
                   alert('你已经登记过');
               }else if(e==3){
                   alert('不存在该协议码');
               }else if(e==4){
                   alert('公司人数超过上限')
               }
           }

//                dataType:'jason'

       });

//           $('form').submit();
   });
});
</script>
</html>
