
<link href="<?php echo base_url('public/distribute/default/styles/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/bind_company.css')?>" rel="stylesheet">
<title>绑定酒店</title>
</head>
<body>
<div class="header">
	<div class="img"><img src=""></div><!-- 酒店LOGO  -->
    <div style="font-size:0.9rem;">酒店名</div>
    <div>分销员工信息补登记</div>
</div>
<form id="pinfo" action="" method="post">
    <div class="list_title">填写个人信息</div>
    
    <div class="ui_normal_list ui_border">
        <div class="item">
            <tt>姓名</tt>
            <input name="" type="text" id="username" placeholder="请输入姓名" />
        </div>
        <div class="item">
            <tt>分销ID</tt>
            <input name="" type="text" placeholder="请输入分销ID" value="" />
        </div>
    </div>
    <div class="notic">
        <p>温馨提示：<br>请员工如实填写个人信息，如因信息填写错误导致绩效发放失败或者发放错误导致造成损失由员工个人负责。</p>
    </div>
    <input id="sub" class="ui_foot_btn" type="button" value="提交">
</form>
</body>
<script>
$(document).ready(function(){
   $("#sub").click(function(){
	   if ( $('#username').val()==''){
		   alert('请输入姓名');
	   }
	   $('form').submit();
   });
});
</script>
</html>
