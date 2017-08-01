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
<title>预约订座</title>
    <?php echo referurl('css','service.css',1,$media_path) ?>
    <?php echo referurl('css','global.css',1,$media_path) ?>
    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>
    <?php echo referurl('js','timepicker.js',1,$media_path) ?>
</head>
<?php include 'wxheader.php'?>
<body>
<div class="pageloading"></div>
<page class="page">
	<header>
        <?php
        if ($dining_room['book_style'] == 1) {
            ?>
            <div class="center padding" style="background:#f5e8d7">
                <a class="color_555 h22"
                   href="<?php echo site_url('/appointment/booking/take_show?id='.$this->inter_id.'&dining_room_id=' . $dining_room['dining_room_id']) ?>">您当前选择的是预约订座，如需要立即取号请<span
                        class="color_link">轻点此处</span></a>
            </div>
            <?php
        }
        ?>
        <!--div class="center bg_fff flex tablayer color_main" style="justify-content:space-around;">
        	<a href="" class="iscur"><tt>排队取号</tt></a>
        	<a href=""><tt>预约订座</tt></a>
        </div-->
    </header>
    <section class="scroll flexgrow h26">
    	<div class="flex flexjustify bg_fff pad10 bd_top linkblock">
        	<div>时间<input value="" name="book_time" readonly type="hidden"></div>
            <input class="h24 color_555 txt_r" id="select_time" value="" required readonly="readonly" placeholder="请选择时间">
        </div>
    	<div class="flex flexjustify bg_fff bd_top" style="padding:6px 10px;">
        	<div>人数</div>
            <div class="num_control">
                <div class="down_num color_main iconfont" onClick="down_num(event,this)">&#xe629;</div>
                <div class="result_num"><input readonly value="1" name="num" type="tel" min="1" max="<?php echo $dining_room['toplimit'];?>"></div>
                <div class="up_num iconfont color_main" onClick="up_num(event,this)">&#xe61d;</div>
            </div>
        </div>
    	<div class="flex flexjustify bg_fff pad10 martop">
        	<div>姓名</div>
            <input class="h24 color_555 txt_r cache" required name="name" value="" placeholder="请输入您的姓名">
        </div>
    	<div class="flex flexjustify bg_fff pad10 bd_top">
        	<div>手机</div>
            <input class="h24 color_555 txt_r cache" required name="phone" value="" placeholder="请输入您的手机号码">
        </div>
    	<div class="bg_fff pad10 martop">
        	<div>备注</div>
            <textarea class="h24 color_555 _w martop" name="note" rows="5" placeholder="请输入您的要求，我们会尽量安排"></textarea>
        </div>
        <div class="flex flexjustify bg_fff pad10 martop">
            <div>电话：</div>
            <div class="h24 color_666"> <?php echo $dining_room['shop_tel'];?></div>
        </div>
        <div class="flex flexjustify bg_fff pad10 martop linkblock" onclick="tonavigate(<?php echo $hotel['latitude'];?>,<?php echo $hotel['longitude'];?>,'<?php echo $hotel['name'];?>','<?php echo $dining_room['shop_address'];?>')">
            <div>地址：</div>
            <div class="h24 color_666"> <?php echo $dining_room['shop_address'];?></div>
        </div>
        <div class="flex flexjustify bg_fff pad10 martop">
            <div>营业时间：</div>
            <div class="h24 color_666"><?php echo $dining_room['open_time'];?></div>
        </div>
    </section>
    <footer>
        <button class="pad10 bg_main center _w submit disable" >
            立即预约
        </button>
    </footer>
</page>
</body>
<script>
var s_name = $.getsession('shopname');
if(s_name == ''){
    s_name = '';
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
    var obj = $(this);
	if(!testval(true)){
		return;
	};

    var book_time = $('input[name="book_time"]').val();
    var num = $('input[name="num"]').val();
    var name = $('input[name="name"]').val();
    var phone = $('input[name="phone"]').val();
    var note = $('textarea[name="note"]').val();
    $.ajax({
        dataType:'json',
        type:'post',
        data:
        {
            'dining_room_id':"<?php echo $dining_room['dining_room_id'];?>",
            'book_datetime':book_time,
            'book_number':num,
            'book_name':name,
            'book_phone':phone,
            'book_info':note,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        url:'<?php echo site_url('appointment/booking/save_order')?>',
        beforeSend: function()
        {
            obj.attr("disabled", true);
        },
        success:function(res)
        {
            if(res.status==1)
            {
                $.MsgBox.Alert(res.msg,function()
                {
                    window.location.href=res.data.url;
                });
                $('#mb_btn_no').remove();
            }
            else
            {
                if (res.status == 400)
                {
                    $.MsgBox.Confirm(res.msg,function(){
                        window.location.href=res.data.url;
                    },null,'查看','关闭');
                    // $.MsgBox.Alert(res.msg);
                }
                else
                {
                    $.MsgBox.Alert(res.msg);
                }
            }
            obj.removeAttr('disabled');
        }
    });
 });
testval();
var setting ={};
var callback = function(date){
	var today= new Date();
	var date = new Date(date);
	$('input[name="book_time"]').val(date.getTime());
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
/*
if($.getsession('booktype')=='partdate')
{
	setting={
		range:['11:30-14:30','17:30-24:00'],
		text:['午餐','晚餐'],
		increment:30,  //时间 间隔 单位分钟
	}
}else{
	setting={
		range:['0:0-23:30'],
		text:['全天'],
	}
}*/

setting = <?php echo $opentime;?>;
setting['SelectDate']=new Date();
setting['SelectTime']='';
setting['callback']=function(date){callback(date);}

$('#select_time').timePicker(setting);
</script>
</html>
