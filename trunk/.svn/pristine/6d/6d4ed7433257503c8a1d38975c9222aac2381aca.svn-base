<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,user-scalable=0">
<title>新增社群客</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/global.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/group.css');?>">
<script src="<?php echo base_url('public/club/scripts/jquery.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/ui_control.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/alert.js');?>"></script>
<style type="text/css">
    .none{
        display: none !important;
    }
</style>
</head>
<body>

<div style="font-size:0;"><img src="<?php echo base_url('public/club/images/bg_02.jpg');?>"></div>
<div class="statustep webkitbox center bg_fff">
	<div>
    	<span class="bg_main h24">1</span>
        <p class="h22 color_main">添加社群客</p>
    </div>
    <div>
    	<span class="bg_555 h24">2</span><hr>
        <p class="h22">审核社群客</p>
    </div>
    <div>
    	<span class="bg_555 h24">3</span><hr>
        <p class="h22">生成圣火令</p>
    </div>
</div>
<form class="list_style_2 bd bd_color_d3 add_new_list" action="" method="post">
	<div class="input_item webkitbox">
    	<p><em class="iconfont">&#xE606;</em>社群名</p>
        <p><input type="text" required placeholder="请填写公司或组织名称" tips="填写公司或组织名称" id="_name" ></p>
    </div>
	<div class="input_item webkitbox">
    	<p><em class="iconfont">&#xE60f;</em>人　数</p>
        <p><input type="tel" required placeholder="请填写上限人数" tips="填写人数" id="_num" name="amount"></p>
    </div>
	<div class="input_item webkitbox">
    	<p><em class="iconfont">&#xE600;</em>有效期</p>
        <p style="width:5rem"><input type="tel" required placeholder="选开始日期" tips="选择开始日期" readonly id="_start" ></p>
        <p>-</p>
        <p style="width:5rem"><input type="tel" required placeholder="选结束日期" tips="选择结束日期" readonly id="_end" ></p>
    </div>
	<div class="input_item webkitbox arrow" id="select_type">
    	<p><em class="iconfont">&#xE60d;</em>协议价</p>
    	<p>
            <input type="text" id="_type" placeholder="请选择协议价" style="text-overflow:ellipsis" readonly value="">
            <input type="hidden"   id="_type_hidden" name="price_code">
        </p>
    </div>
    <div class="input_item webkitbox none scj arrow" id="select_type_soma">
        <p><em class="iconfont">&#xE60d;</em>商城价</p>
        <p>
            <div class="scj_show" style="width: 100%;text-overflow: ellipsis;">商城价</div>
            <input type="hidden"   id="_type_hidden_soma" name="price_code_soma">
        </p>
    </div>
    <input type='hidden' name='<?php echo $csrf_token; ?>' value='<?php echo $csrf_value; ?>' />
    <!-- 弹层 -->
    <div class="ui_pull type_pull" id="_type_pull" style="display:none; padding:0;border:none">
        <div class="webkitbox relative">
            <ul class="scroll bg_F3F4F8 list_style_2" id="_tab_menus">
                <li class="color_main hotel_li">
                    <em class="icon iconfont">&#XE607;</em>全部酒店<em class="iconfont">&#XE611;</em>
                    <input type="radio" name="hotel" checked value="0" style="display:none">
                </li>
                <li class="hotel_li">
                    <em class="icon iconfont">&#XE607;</em>自己酒店<em class="iconfont">&#XE611;</em>
                    <input type="radio" name="hotel" value="<?php echo $hotel_id;?>" style="display:none">
                 </li>
                 <li class="soma_li">
                    <em class="icon iconfont">&#XE607;</em>商城<em class="iconfont">&#XE611;</em>
                    <input type="radio" name="soma" value="0" style="display:none">
                </li>
            </ul>
            <ul class="scroll list_style _tab_list" style="padding-bottom:41px;">
                <?php if(isset($all_price_code)&&!empty($all_price_code)&&is_array($all_price_code)){
                        foreach($all_price_code as $key=>$all_arr){ ?>
                            <li val="<?php echo $key;?>"><?php echo $all_arr;?></li>
                        <?php }
                        }else{  ?>
                            <li>无</li>
                <?php } ?>
            </ul>
            <ul class="scroll list_style _tab_list" style="display:none;padding-bottom:41px">
                <?php if(isset($price_code)&&!empty($price_code)&&is_array($price_code)){
                     foreach($price_code as $key1=>$arr){ ?>
                        <li val="<?php echo $key1;?>"><?php echo $arr;?></li>
                    <?php }
                    }else{  ?>
                    <li>无</li>
                <?php } ?>
            </ul>
            <ul class="scroll list_style soma_list" style="display:none;padding-bottom:41px">
                <?php if(isset($soma_code)&&!empty($soma_code)&&is_array($soma_code)){
                    foreach($soma_code as $key2=>$arr){ ?>
                        <li val="<?php echo $key2;?>"><?php echo $arr;?></li>
                    <?php }
                }else{  ?>
                    <li>无</li>
                <?php } ?>
            </ul>
            <div class="_w center bg_main pad10 absolute" style="bottom:0;left:0;" id="closebtn">确定</div>
        </div>
    </div>
</form>

<div class="foot_btn" style="margin-top:10px">
	<button class="btn_main h28 submitbtn disable" type="button" id="_submit">提交</button>
</div>

<script src="<?php echo base_url('public/club/scripts/alert.js');?>"></script>

<script src="<?php echo base_url('public/club/calendar/mobiscroll.core.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.scroller.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.widget.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.util.datetime.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.datetimebase.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.widget.ios.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.i18n.zh.js');?>"></script>


<link href="<?php echo base_url('public/club/calendar/mobiscroll.animation.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('public/club/calendar/mobiscroll.widget.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('public/club/calendar/mobiscroll.widget.ios.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('public/club/calendar/mobiscroll.scroller.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('public/club/calendar/mobiscroll.scroller.ios.css');?>" rel="stylesheet" type="text/css">

<script>
var today =  new Date();
var add_code=0;
var opt= { 
	theme:'ios', //设置显示主题 
	mode:'scroller', //设置日期选择方式，这里用滚动
	display:'bottom', //设置控件出现方式及样式
	preset : 'date', //日期:年 月 日 时 分
	minDate: today, 
	maxDate: new Date(today.getTime()+24*60*60*1000*365*5),//60天内
	dateFormat: 'yy-mm-dd', // 日期格式
	dateOrder: 'yymmdd', //面板中日期排列格式
	stepMinute: 5, //设置分钟步长
	yearText:'年', 
	monthText:'月',
	dayText:'日',
	lang:'zh' //设置控件语言};
};
	
$('#_start').mobiscroll(opt);
$('#_end').mobiscroll(opt);

$(document).ready(function(){
function testval(){
	$('input[tips]').each(function(index, element) {
        if($(this).val()==''){
			$.MsgBox.Alert( '你还没有'+$(this).attr('tips'));
			$('#_submit').removeClass('disable').addClass('disable');
		}
    });
}
function button_change(){
	for ( var i=0;i<$('input[required]').length;i++){
		if ( $('input[required]').eq(i).val()=='')return;
	}
	$('#_submit').removeClass('disable');
}
$('#_submit').click(function(){

    if(($('#_type_hidden').val()=='' || $('#_type_hidden').val()=='[]')  && ($('#_type_hidden_soma').val()=='' || $('#_type_hidden_soma').val()=='[]') ){
        alert('请选择价格代码');return;
        $('#_submit').removeClass('disable');
    }

    if(add_code==0){
        add_code=1;
        testval();
        if($(this).hasClass('disable')){
            testval();
            return;
        }
        var postUrl = "<?php echo site_url('club/Club/add_post');?>";

        $.ajax({

            type: 'POST',
            dataType : 'json',
            url: postUrl,

            data: {
                name:$('#_name').val(),
                b_time:$('#_start').val(),
                e_time:$('#_end').val(),
                amount:$('#_num').val(),
                price_code:$('#_type_hidden').val(),
                soma_code:$('#_type_hidden_soma').val(),
                hotel_id:$("input[name='hotel']:checked").val(),
                '<?php echo $csrf_token; ?>':'<?php echo $csrf_value; ?>'


            },

            success: function(data){
                if(data.code==1){
                   // alert(data.info);
                    location.href='./add_club_result?code='+data.code+'&left='+data.left+'&limited_amount='+data.limited_amount+'&st='+data.status;
                }else if(data.code==0){
                   // alert(data.info);
                    location.href='./add_club_result?code='+data.code;
                }
            }

        });
    }
});
$('#select_type').click(function(){
    $('#_type').val('');
    $('#_type_hidden').val('');
	toshow($('#_type_pull'));
});
$('#select_type_soma').click(function(){
    $('#_type').val('');
    $('#_type_hidden').val('');
    toshow($('#_type_pull'));
});
$('input[required]').change(button_change);
//$('#_type_pull li').click(function(){
	//$(this).addClass('color_main').siblings().removeClass('color_main');
//});
$('.hotel_li').click(function(){
	$('._tab_list,.soma_list').hide();
	$('._tab_list').eq($(this).index()).stop().show();
	$(this).find('input').get(0).checked=true;
    $(".hotel_li,.soma_li").removeClass('color_main');
	$(this).addClass('color_main');
})
$('._tab_list li').click(function(){
	if($(this).attr('val')==undefined||$(this).attr('val')=='')return;
	$(this).toggleClass('color_main');
	$(this).parent().siblings('._tab_list').find('li').removeClass('color_main');
})
var cunchu = {
    name : [],
    id : []
};
$(".soma_list li").click(function(){
    if($(this).attr('val')==undefined||$(this).attr('val')=='')return;
    $(this).toggleClass('color_main');
    var total = $(".soma_list li");
    cunchu = {
        name : [],
        id : []
    };
    for(var i = 0;i<total.length ; i++){
        if($(total[i]).hasClass('color_main')){
            cunchu.name.push(total[i].innerHTML);
            cunchu.id.push($(total[i]).attr('val'));
        }
    }
//    console.log(cunchu);
});
$(".soma_li").click(function(){
    $('._tab_list').hide();
    $(".soma_list").show();
    $(this).addClass('color_main');
    $(".hotel_li").removeClass('color_main');
});
$('#closebtn').click(function(){
	var array = [];
	if($('._tab_list .color_main').length>0){
        $("#select_type").removeClass("none");
		if($('#_type_hidden').val()!='')
			array = JSON.parse($('#_type_hidden').val());
		$('._tab_list .color_main').each(function() {
			array.push($(this).attr('val'));
			var val = $('#_type').val()!=''?$('#_type').val()+',':'';
			$('#_type').val(val+$(this).html());
		});
	}else if(cunchu.name.length != 0){
        $("#select_type").addClass("none");
    }
	$('#_type_hidden').val(JSON.stringify(array));
    if(cunchu.name.length != 0){
//        console.log(cunchu);
        $(".scj").removeClass("none");
        var thename = cunchu.name[0];
        var thevalue = [cunchu.id[0]];
        for(var i =1;i<cunchu.name.length;i++){
            thename = thename + "," + cunchu.name[i];
            thevalue.push(cunchu.id[i]);
        }
        $(".scj_show").html(thename);
        $("input[name=price_code_soma]").val(thevalue);
    }else{
        $(".scj").addClass("none");
        $(".scj_show").html("");
        $("input[name=price_code_soma]").val("");
    }
	button_change();
	toclose();
})

})
</script>
</body>
</html>
