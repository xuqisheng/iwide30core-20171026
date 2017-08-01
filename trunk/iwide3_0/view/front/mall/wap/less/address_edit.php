<script src="<?php echo base_url('public/mall/multi/script/area.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/address.css')?>" rel="stylesheet">
<title>编辑地址</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:5em;}
-->
</style>
<body>
<div class="ui_normal_list ui_border">
	<div class="item">
    	<tt>收货人</tt>
    	<input type="text" name="contact" id="name" placeholder="姓名">
    </div>
	<div class="item">
    	<tt>手机号码</tt>
    	<input type="tel" name="cellphone" id="phone" placeholder="11位手机号">
    </div>
	<div class="item">
    	<tt>选择地区</tt>
        <select name="province" id="province">
			<option value="请选择省份">请选择省份</option>
		</select>
		<select name="city" id="city">
			<option value="请选择城市">请选择城市</option>
		</select>
    </div>
	<div class="item">
    	<tt>详细地址</tt>
    	<input type="text" name="address" id="address" placeholder="街道门牌信息">
    </div>
</div>
<input class="footfixed bg_orange savebtn" type="button" onclick="testval()" value="保存">
</body>
</html>
<script>
var tmp='';
for( var i=0; i<province.length; i++)
	tmp+='<option value="'+province[i] +'">'+province[i] +'</option>';
$('#province').append(tmp);
$('#province').blur(function(){
	var _this =$(this);
	if( _this.val()=='请选择省份') return;
	tmp='';
	for(var i=0;i<cities[ _this.val()].length;i++)
		tmp+='<option value="'+cities[ _this.val()][i] +'">'+cities[ _this.val()][i] +'</option>';
	
	$('#city').html(tmp);
})
function testval(){
	var cellreglex = new RegExp(/^\d{11}$/);
	if($('#address').val()==''|| $('#phone').val()==''|| $('#name').val()=='' ){
		$('.savebtn').val('以上信息不能为空');
		$('.savebtn').css('background',"#f00");
		return false;
	}
	if(!cellreglex.test($('#phone').val())){
		$('.savebtn').val('手机格式有误');
		$('.savebtn').css('background',"#f00");
		return false;
	}
	if($('#province').val() == '请选择省份'){
		$('.savebtn').val('请选择省份');
		$('.savebtn').css('background',"#f00");
		return false;
	}
	if($('#city').val() == '请选择城市'){
		$('.savebtn').val('请选择城市');
		$('.savebtn').css('background',"#f00");
		return false;
	}
	save_address();
}
function save_address(){
	$.post("<?php echo site_url('mall/wap/save_address');?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>",{'contact':$(':input[name=contact]').val(),'phone':$(':input[name=cellphone]').val(),'province':$(':input[name=province]').val(),'city':$(':input[name=city]').val(),'address':$(':input[name=address]').val()},function(data){
		if(data.errmsg == 'ok'){
			window.location.href="<?php echo site_url('mall/wap/mail_order/'.$from_seg);?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&naid="+data.aid;
		}else{
			alert('地址保存失败');
			}
		},'json');
}
$('input').change(function(){
	$('.green').css('background',"#fe4e00");
	$('.green').html('保存');
})
$('select').change(function(){
	$('.green').css('background',"#fe4e00");
	$('.green').html('保存');
})

</script>