<link href="<?php echo base_url('public/distribute/default/styles/incom.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/fill_in.css')?>" rel="stylesheet">
<title>填写信息</title>
</head>
<style>
<!--
.pull{ position:absolute; top:120%; height:auto; max-height:6rem; min-height:2rem;-webkit-overflow-scrolling:touch; overflow:scroll; background:#fff; color:#555; display:none; border:1px solid #e1e1e1; border-top:0; line-height:1.6}
-->
</style>
<body>
<div class="nav">
	<div class="state">
    	<div class="lis_sta">
        	<div class="radi bg">1</div>
        	<p class="col">填写信息</p>
        </div>
    	<div class="lis_sta">
        	<div>2</div>
        	<p>酒店审核</p>
        </div>
    	<div class="lis_sta">
        	<div>3</div>
        	<p>审核结果</p>
        </div>
        <div class="yello"></div>
        <div class="c_99"></div>
    </div>
</div>
<div class="titl">填写个人信息</div>
<div class="box">
	<div class="selsct">
    	<span class="front">姓&nbsp;&nbsp;名</span>
        <span><input type="text" class="use" placeholder="请输入姓名" name="name" id="name" value="<?php if (isset($saler['name'])):echo $saler['name'];endif;?>" /></span>
    </div>
	<div class="selsct">
    	<span class="front">手机号码</span>
        <span><input type="text" class="phone" placeholder="请输入手机号码" name="cellphone" id="cellphone" value="<?php if (isset($saler['cellphone'])):echo $saler['cellphone'];endif;?>" /></span>
    </div>
</div>
<div class="floot">
	<a href="javascript:;" onclick="submit()"><p>提交</p></a>
</div>
</body>
</html>
<script>
var testval={
		name:/^[\u4E00-\u9FA5]{2,4}/g,
		identity:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
		_phon:/^[1]\d{10}$/ 
}
function submit(){
	var sub = true;
	var name      = $('#name').val();
	var cellphone = $('#cellphone').val();
	if(name == undefined  || name == ''){
		alert('请输入姓名');return false;
	}
	if(cellphone == undefined ||!testval._phon.test(cellphone)){
		alert('请输入正确手机号码');return false;
	}
	if(sub){
		sub = false;
		$.post("<?php echo site_url('hotel/temp_msg_auth/do_reg')?>?id=<?php echo $inter_id?>",{"name":name,"cellphone":cellphone,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},function(datas){
			if(datas.errmsg == 'ok'){
				alert('信息提交成功');
				window.location.reload();
			}else{
				alert('信息提交失败');
				sub=true;
			}
		},'json');
	}else{
		alert('正在提交数据');
	}
}
$(document).on('blur','input[name]',function(){
	var _v =$(this).val();
	if(_v==''|| ($(this).hasClass('identit')&&!testval.identity.test(_v))||($(this).hasClass('phone')&&!testval._phon.test(_v))){	
		$(this).parent().removeClass('all_right').addClass('error');
	}
	else{
		$(this).parent().removeClass('error').addClass('all_right');
	}
	//$('.pull').stop().hide();
})	
</script>