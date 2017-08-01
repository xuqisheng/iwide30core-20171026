<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" c ontent="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,user-scalable=0">
<title>确认订单</title>
    <?php echo referurl('css','service.css',1,$media_path) ?>
    <?php echo referurl('css','global.css',1,$media_path) ?>
    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>
    <?php echo referurl('js','timepicker.js',1,$media_path) ?>
</head>
<body>
<div class="pageloading"></div>
<page class="page">
    <section class="scroll flexgrow h26">
    	<div class="flex flexjustify bg_fff pad10 martop">
        	<div>餐厅</div>
            <div class="h24 color_666" shopname></div>
        </div>
    	<div class="flex flexjustify bg_fff pad10 bd_top linkblock">
        	<div>时间<input value="" name="book_time" readonly type="hidden"></div>
            <input class="h24 color_555 txt_r" id="select_time" value="" required readonly placeholder="请选择时间">
        </div>
    	<div class="flex flexjustify bg_fff bd_top" style="padding:6px 10px;">
        	<div>人数</div>
            <div class="num_control">
                <div class="down_num color_main iconfont" onClick="down_num(event,this)">&#xe629;</div>
                <div class="result_num"><input readonly value="1" name="num" type="tel" min="1" max="12"></div>
                <div class="up_num iconfont color_main" onClick="up_num(event,this)">&#xe61d;</div>
            </div>
        </div>
    	<div class="flex flexjustify bg_fff pad10 martop">
        	<div>姓名</div>
            <input class="h24 color_555 txt_r cache" required name="name" value="<?php echo isset($name)?$name:''?>" placeholder="请输入您的姓名">
        </div>
    	<div class="flex flexjustify bg_fff pad10 bd_top">
        	<div>手机</div>
            <input class="h24 color_555 txt_r cache" required name="phone" value="<?php echo isset($phone)?$phone:''?>" placeholder="请输入您的手机号码">
        </div>
    	<div class="bg_fff pad10 martop">
        	<div>备注</div>
            <textarea class="h24 color_555 _w martop" name="note" rows="5" placeholder="请输入您的要求，我们会尽量安排"></textarea>
        </div>
    </section>
    <footer>
        <div class="pad10 bg_main center _w submit disable" href="<?php echo site_url('booking/booking/show?id='.$inter_id.'&sid=2')?>" >
        	<div>立即预约</div>
        </div>
    </footer>
</page>
</body>
<script>
var s_name = $.getsession('shopname');
if(s_name == ''){
    s_name = '<?php echo $shop[$shop_id]?>';
}
$('[shopname]').html(s_name);

/**/
function testval(bool){
	bool=bool?bool:false; 
	for(var i=0;i<$('input[required]').length;i++){
        if($('input[required]').eq(i).val()==''){
			$('.submit').addClass('disable');
			if(bool)
				$.MsgBox.Alert(tips);
			return false;
		}
	}
	if(!reg_phone.test($('input[name="phone"]').val())){
		$('.submit').addClass('disable');
		if(bool)$.MsgBox.Alert('手机号码格式有误');
		return false;
	}
	$('.submit').removeClass('disable');
	return true;
}
$('input').bind('input propertychange',function(){testval()});
$('.submit').click(function(){
	if(!testval(true)){
		return;
	};
    var shopname = s_name;
    var book_time = $('input[name="book_time"]').val();
    var num = $('input[name="num"]').val();
    var name = $('input[name="name"]').val();
    var phone = $('input[name="phone"]').val();
    var note = $('textarea[name="note"]').val();
    $.post('<?php echo site_url('booking/booking/create_booking')?>',{
        'shop_name':shopname,
        'book_time':book_time,
        'num':num,
        'name':name,
        'phone':phone,
        'note':note,
        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
    },function(res){
        if(res.errcode==0){
			$.MsgBox.Alert('您已预约成功',function(){
            	window.location.href=res.data.url;
			});
			$('#mb_btn_no').remove();
        }else{
            $.MsgBox.Alert(res.msg);
        }
    },'json');
})
testval();
var setting ={};
var callback = function(date){
	var today= new Date();
	var date = new Date(date);
	$('input[name="book_time"]').val(date.toLocaleDateString()+' '+date.getHours()+':'+date.getMinutes());
	var tmp  = new Date(date);
	var str  = '';
	today.setHours(0,0,0,0);
	tmp.setHours(0,0,0,0);
	if(today.getTime()==tmp.getTime())str ='今天 '+$.getnumber(date.getHours())+':'+$.getnumber(date.getMinutes());
	else
		str = (date.getMonth()+1)+'月'+$.getnumber(date.getDate())+'日 '+$.getnumber(date.getHours())+':'+$.getnumber(date.getMinutes());
	$('#select_time').val(str);
	testval();
}
if($.getsession('booktype')=='partdate'){
	setting={
		range:['11:30-14:30','17:30-22:00'],
		text:['午餐','晚餐'],
		increment:30,  //时间 间隔 单位分钟
	}
}/*else{
	setting={
		range:['0:0-23:30'],
		text:['全天'],
	}
}*/
setting['SelectDate']=new Date();
setting['SelectTime']='';
setting['callback']=function(date){callback(date);}

$('#select_time').timePicker(setting);
</script>
</html>
