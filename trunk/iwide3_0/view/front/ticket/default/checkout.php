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
    <?php echo referurl('css','global.css',1,$media_path) ?>
    <?php echo referurl('css','service.css',1,$media_path) ?>
    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>
    <?php echo referurl('js','timepicker.js',1,$media_path) ?>
    <script type='text/javascript'>
        var _vds = _vds || [];
        window._vds = _vds;
        (function(){
            _vds.push(['setAccountId', '9035a905d6d239a4']);
            (function() {
                var vds = document.createElement('script');
                vds.type='text/javascript';
                vds.async = true;
                vds.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'dn-growing.qbox.me/vds.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(vds, s);
            })();
        })();
    </script>
    <script type='text/javascript' src='https://assets.growingio.com/sdk/wx/vds-wx-plugin.js'></script>
</head>
<body>
<div class="pageloading"></div>


<page class="page">
	<header>
        <?php if($shop['sale_type']==4){//外卖 echo site_url('ticket/ticket/address_list?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id)?>
            <?php if($user_address){?>
            <a href="<?php echo site_url('ticket/ticket/address_list?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id)?>" class="flex bg_fff pad10 linkblock">
            <em class="iconfont h36 color_main">&#XA8;</em>
        	<div class="marleft">
            	<div class="h26" id="userinfo"><?php echo $user_address[0]['contact'].'  '.$user_address[0]['phone']?></div>
                <!--
                <?php if($inter_id != 'a469543253' && $inter_id!='a469428180'){?>
            	<div class="h20 color_C3C3C3" id="address"><?php echo $user_address[0]['select_addr'] . ' ' . $user_address[0]['address']?></div>
                <?php }?>
                -->
				<input type="hidden" name="address_id" value="<?php echo $user_address[0]['address_id']?>"/>
            </div>
            </a>
            <?php }else{?>
            <a href="<?php echo site_url('ticket/ticket/address?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id)?>" class="flex bg_fff pad10 linkblock">
            <em class="iconfont h36 color_main">&#XA8;</em>
            <div class="marleft">
                <div class="h26" id="userinfo">请添加联系信息</div>
                <div class="h20 color_C3C3C3" id="address"></div>
				<input type="hidden" name="address_id" value=""/>
            </div>
            </a>
            <?php }?>
        <?php }else{?>
        <div class="flex bg_fff pad10">
            <em class="iconfont h36 color_main">&#XA8;</em>
            <div class="marleft">
                <div class="h26">
                    <?php
                        if($shop['sale_type']==1)
                        {
                            if ($shop['identify_type'] ==2)
                            {
                                echo '房间号：<input class="h24" name="room_name" placeholder="请输入房间号"/>';
                            }
                            else
                            {
                                echo '房间号：'.$type_id;
                            }
                        }
                        else if($shop['sale_type']==2)
                        {
                            echo '桌号：'.$type_id;
                        }
                    ?>
                </div>
                <div class="h20 color_C3C3C3"><?php echo $hotel['name']?></div>
            </div>
        </div>
        <?php }?>
    </header>
    <section class="scroll flexgrow h26" id="goodInfo">
        <div class="flex flexjustify bg_fff pad10 linkblock martop">
            <div>消费时间<input value="" name="dissipate" readonly type="hidden"></div>
            <span val="" id="select_time" class="h24 color_555">请选择时间</span>
            <!--<input class="h24 color_555 txt_r" id="select_time" value="" required readonly="readonly" placeholder="请选择时间">-->
        </div>
    	<div class="flex flexjustify bg_fff pad10 linkblock martop">
        	<div>支付方式</div>
            <select class="h24 color_555" style="padding-left:25%" id="pay_type">
                <?php if(!empty($shop['pay_type'])){
                            foreach($shop['pay_type'] as $v){
                ?>
            	<option value="<?php echo $v?>"><?php echo !empty($all_pay_type[$v])?$all_pay_type[$v]:''?></option>
               <?php }}?>
            </select>
        </div>
        <div class="list_style_1 martop goods">
        	<div id="order_goods">订单商品</div>
            <div class="arrow justify webkitbox hide" id="usecoupon">
            	<div>优惠券</div>
                <div class="color_minor"></div>
            </div>
            <?php
            if ($shipping_cost >0)
            {
            ?>
            <div class="justify webkitbox">
            	<div>配送费</div>
                <div class="color_minor y" id="cost"><?php echo $shipping_cost;?></div>
            </div>
            <?php
            }
            ?>
            <div class="color_555">
                <span id="orign">订单¥0</span>
                <span id="discount"></span>
                <span class="color_000">待支付<span class="y total">0</span></span>           
            </div>
        </div>
        <?php if($inter_id !='a469543253'&& $inter_id !='a469428180'){?>
    	<div class="flex flexjustify bg_fff pad10 martop">
        	<div>需求</div>
            <textarea id="info" class="h24 color_C3C3C3 flexgrow txt_r" style="padding-left:15px" placeholder="可留下您的喜好，我们将尽量满⾜" rows="1" maxlength="120" oninput="changerow(this)"></textarea>
        </div>
        <?php }?>
    </section>
    <footer>
    <div class="layer1">
        <div class="flex flexgrow">
            <div>待支付 <span class="y total">0</span></div>
        </div>
        <a class="bg_main flex flexrow submit" id="post_order" >
        	<div>确认下单</div>
        </a>
    </div>
    </footer>
</page>
<!-- 点击选择地址 用跳转的还是页面上选择？-->
<!--<page class="page" style="display:none">
    <header></header>
    <section class="scroll flexgrow h26">
        <div class="flexlist list_style_1">
            <?php /*if(!empty($user_address)){
                foreach($user_address as $k=>$v){
                    */?>
                    <a href="<?php /*echo site_url('ticket/ticket/address?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id.'&addr_id='.$v['address_id'])*/?>" class="flex bg_fff pad10 arrow">
                        <em class="iconfont h36 color_main">&#X;</em>
                        <div style="margin-left:5px">
                            <div class="h26"><?php /*echo $v['contact'].' '.$v['phone']*/?></div>
                            <div class="h20 color_C3C3C3"><?php /*echo $v['select_addr'].' '.$v['address']*/?></div>
                        </div>
                    </a>
                <?php /*}}*/?>
        </div>
    </section>
    <footer>
        <a class="bg_main center pad12" style="display:block" href="<?php /*echo site_url('ticket/ticket/address?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id)*/?>" ><em class="iconfont">&#X;</em> 新建地址</a>
    </footer>
</page>-->
</body>
<script>
<?php 
    $query = parse_str($_SERVER["QUERY_STRING"], $output)
?>
var publicId = '<?php echo($output["id"]) ?>';
var orign 	 = 0; //原价
var down	 = 0; //优惠金额
var total	 = 0; //折后价
var orign_fee	 = 0; //原价 - 预约优惠金额
var discount_fee	 = 0; //预约优惠金额
var user	 = '';
var cost	 = Number($('#cost').html());
var shopcart = $.getsession('shopcart');
function fillprice(){
	if(isNaN(cost))cost = 0;
    down = parseFloat(discount_fee) + parseFloat(down);
	total = orign - down+cost;
    if (total < 0)
    {
        total = 0;
    }
	$('#discount').html('优惠¥'+down.toFixed(2))
	$('#orign').html('订单¥'+orign.toFixed(2))
	$('.total').html(total.toFixed(2))
}
function discount(data){
    down = 0;
	if(data.discount_type!=undefined){
		if(data.discount_type == 3){
				down = orign_fee - Number(data.discount_config.discount)*orign_fee*0.1;
		}else if(orign_fee>=data.discount_config.sum){
			if(data.discount_type == 1){
				down = Number(data.discount_config.cut);
			}else{
				down = Number(data.discount_config.cut)*Math.floor(orign_fee/Number(data.discount_config.sum));
			}
		}
	}
}
function getRebate(){
	pageloading();
	$.post('<?php echo site_url('ticket/ticket/get_all_discount');?>',{
		'hotel_id':'<?php echo $hotel_id?>',
		'shop_id':'<?php echo $shop_id?>',
		'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
	},function(data){
		removeload();
		if(data.errcode==0){

		}
        discount(data.data);
        fillprice();
	},'JSON');
}

var timer = null;
var i = 0;
var $goodInfo = $('#goodInfo');
var isAndroid = /Android/.test(navigator.userAgent);

if(isAndroid){
    $(window).on('resize', function(){
        clearInterval(timer);
        timer = setInterval(function(){
            i++
            if(i >= 10 || $goodInfo.scrollTop()){
                i=0
                //$('body').css('background', 'red')
                clearInterval(timer);
                return;
            }
            $goodInfo.scrollTop(document.body.scrollHeight);
        },100);
    })
}

function load_goods(load_goods,type)
{
    $('.goods .item').remove();
    var html = '';
    var count= 0;
    var sum  = 0;

    if (type == 1)
    {
        $.each(load_goods,function(i,j){
            $.each(j,function(m,n){
                count+= Number(n.count);
                sum	 += Number(n.price)*Number(n.count);
                html += '<div class="item"><div>';
                html += '<p>'+n.goods_name+'</p>';
                html += '<p class="h20 color_555">'+n.spec_name+'</p></div>';
                html += '<div class="">X'+n.count+'</div>';
                html += '<div class=" color_minor">¥'+n.price+'</div></div>';
            })
        });

        orign = sum;
        orign_fee = orign;

    }
    else
    {
        $.each(load_goods,function(i,j){
            $.each(j,function(m,n){
                count+= Number(n.count);
                sum	 += Number(n.price)*Number(n.count);
                html += '<div class="item"><div>';
                html += '<p>'+n.goods_name+'</p>';
                html += '<p class="h20 color_555">'+n.spec_name+'</p></div>';
                html += '<div class="">X'+n.count+'</div>';
                html += '<div class=" color_minor">¥'+n.price+'</div></div>';
            })
        });
    }

    $('#order_goods').after(html);
    if(html=='')window.location.href = url;
    fillprice();
    getRebate();
}

$(function(){
	<?php if($shop['sale_type']==3){?>
		/*user = $.getsession('user');
		if(user!=''){
			user = $.parseJSON(user);
			$('#userinfo').html(user.contact+' ' +user.phone);
			$('#address').html(user.address);
			$('input[name="address_id"]').val(user.address);
		}*/
	<?php }?>
	var url = '<?php echo site_url('ticket/ticket/index?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id.'&type_id='.$type_id)?>';
	if(shopcart==''){
		window.location.href = url;
	}else{
		shopcart  = $.parseJSON($.getsession('shopcart'));

        load_goods(shopcart,1);
	}






	
});

function count_ticket_discount()
{
    pageloading();
    var array = [];
    var index = 0;
    //console.log(shopcart);
    $.each(shopcart,function(i,j){
        $.each(j,function(m,n){
            array[index++]={"goods_id":n.goods_id,"count":n.count,'spec_id':n.setting_id,'spec_name':n.spec_name};
        });
    });

    $.ajax({
        dataType:'json',
        type:'post',
        data:
        {
            'inter_id':'<?php echo $inter_id?>',
            'hotel_id':'<?php echo $hotel_id?>',
            'shop_id':'<?php echo $shop_id?>',
            'goods_info':array,
            'dissipate':$('input[name=dissipate]').val(),
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        url:'<?php echo site_url('ticket/ticket/count_ticket_discount');?>',
        success:function(res)
        {
            removeload();
            if(res.status == 1)
            {
                //处理价格

                shopcart = res.data.goods_info;
                console.log(shopcart);
                //goods_list  = $.parseJSON(goods_list);

                var count= 0;
                var sum  = 0;
                $.each(shopcart,function(i,j)
                {
                    $.each(j,function(m,n){
                        count+= Number(n.count);
                        sum	 += Number(n.price)*Number(n.count);
                    })
                });
                orign = sum;

                orign_fee = orign - res.data.discount_fee;
                discount_fee = res.data.discount_fee;

                load_goods(shopcart,2);
                //getRebate();
            }
            else
            {
                //$.MsgBox.Alert(res.msg);
                //$('#mb_btn_no').remove();
            }
        }
    })

}

var setting ={};
var callback = function(date){
    var today= new Date();
    var date = new Date(date);
    var d = date.getTime();
    $('input[name="dissipate"]').val(d);
    var tmp  = new Date(date);
    var str  = '';
    today.setHours(0,0,0,0);
    tmp.setHours(0,0,0,0);
    if(today.getTime()==tmp.getTime())str ='今天 '+$.getnumber(date.getHours())+':'+$.getnumber(date.getMinutes());
    else
        str = (date.getMonth()+1)+'月'+$.getnumber(date.getDate())+'日 '+$.getnumber(date.getHours())+':'+$.getnumber(date.getMinutes());
    $('#select_time').text(str).attr('value',d);

    //计算金额
    count_ticket_discount();
   // testval();
}



//提交订单
$('#post_order').click(function(){
    var address_id = $('input[name="address_id"]').val();
    if(address_id==''){
        $.MsgBox.Alert('请先填写联系信息');
        return;
    }

    //消费时间

    var dissipate = $('input[name=dissipate]').val();
    if (dissipate == '')
    {
        $.MsgBox.Alert('请选择消费时间');
        return;
    }

    if ($('footer .total').text() == 0.00)
    {
        $.MsgBox.Alert('当前预约日期商品库存不足');
        return;
    }

    var array = [];
    var index = 0;
    var _is_save = 0;
    var goods_name_spec = '';

    $.each(shopcart,function(i,j)
    {
        $.each(j,function(m,n){
            if (Number(n.count) > Number(n.stock))
            {
                _is_save = 1;
                goods_name_spec = n.goods_name +' '+n.spec_name;
                return false;
            }
            array[index++]={"goods_id":n.goods_id,"count":n.count,'spec_id':n.setting_id,'spec_name':n.spec_name};
        })
    });



    if (_is_save == 1)
    {
        $.MsgBox.Alert(goods_name_spec + '库存不足');
        return false;
    }


    pageloading();
    $.post('<?php echo site_url('ticket/ticket/saveOrder');?>',{
        'inter_id':'<?php echo $inter_id?>',
        'hotel_id':'<?php echo $hotel_id?>',
        'shop_id':'<?php echo $shop_id?>',
        'pay_type':$('#pay_type').val(),
        'room_name':$('input[name=room_name]').val(),
        'goods_info':array,
        'address_id':address_id?address_id:'',
        'type_id':'<?php echo !empty($type_id)?$type_id:0?>',
        'coupon_id':'0',
        'note':$('#info').val(),
        'dissipate':$('input[name=dissipate]').val(),
        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
    },function(res){
        removeload();
        if(res.errcode == 0){
            //清空购物车
            $.setsession('shopcart','');
            window.location.href = res.data.pay_url;
        }else{
            $.MsgBox.Alert(res.msg);
            $('#mb_btn_no').remove();
        }
    },'json');
});

/*
setting={
    range:['11:30-14:30','17:30-24:00'],
    text:['午餐','晚餐'],
    increment:30,  //时间 间隔 单位分钟
}
*/

setting = <?php echo $shop['setting']?>

setting['SelectDate']=new Date();
setting['SelectTime']='';
setting['callback']=function(date){callback(date);}
var blackPublics = ['a450089706', 'a492422322']
if(blackPublics.indexOf(publicId) > -1){
    setting['disabledTimes'] = {
        '2017-05-14': {
            'full': true
        }
    }
}

$('#select_time').timePicker(setting);
</script>
</html>
