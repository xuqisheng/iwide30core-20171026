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
    <meta name="viewport" content="width=320,user-scalable=0,shrink-to-fit=no">
    <script src="<?php echo get_cdn_url('public/soma/scripts/jquery.js');?>"></script>
    <script src="<?php echo get_cdn_url('public/soma/scripts/ui_control.js');?>"></script>
    <link href="<?php echo get_cdn_url('public/soma/styles/global.css');?>" rel="stylesheet">
    <link href="<?php echo get_cdn_url('public/soma/styles/default.css');?>" rel="stylesheet">
    <link href="<?php echo get_cdn_url('public/soma/styles/theme_v1.css');?>" rel="stylesheet">
    <script src="<?php echo get_cdn_url('public/soma/scripts/lazyload.js');?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
    <title><?php echo $title;?></title>
</head>
<style>
        /*颜色控制*/

        /*主色*/
     <?php if(isset($main_color) && !empty($main_color)) { ?>
        .color_main,a.color_main,.btn_void,a.btn_void{color:<?php echo $main_color;?>;}
        .bg_main,a.bg_main,.btn_main,a.btn_main{background:<?php echo $main_color;?>;}
		.bd_main_color,.btn_void,a.btn_void{ border-color:<?php echo $main_color;?> !important}
    <?php } ?>

        /*副色*/

    <?php if(isset($sub_color) && !empty($sub_color)) { ?>
        .color_minor,a.color_minor{color:<?php echo $sub_color;?>;}
        .bg_minor,a.bg_minor,.btn_minor,a.btn_minor{background:<?php echo $sub_color;?>;}
    <?php } ?>

</style>
    <script>
    $(function(){
        $("img.lazy").lazyload();  //惰性加载
    });
    </script>

<div class="pageloading" ><p class="isload" style="margin-top:150px">正在加载</p></div>
<!-- 以上为head -->

<!-- 选择日历插件 -->
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.core.js');?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.widget.js');?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.scroller.js');?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.util.datetime.js');?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.datetimebase.js');?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.widget.ios.js');?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.i18n.zh.js');?>"></script>
<link href="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.animation.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.widget.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.widget.ios.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.scroller.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.scroller.ios.css');?>" rel="stylesheet" type="text/css">
<!-- 日历插件 -->
<form action="<?php echo $shipping_url; ?>" method="post" id="mailPost">

<div class="block webkitbox justify bd" style="margin-top:0">
    <span>赠送内容</span>
    <span class="color_999"><?php echo isset( $items[0]['name'] ) ? $items[0]['name'] : '';?></span>
</div>
<div class="block webkitbox justify bd">
    <span>邮寄份数</span>
    <?php if( isset( $items ) && $items[0]['qty'] == 1 ): ?>
        <span>1件</span>
    <?php elseif( isset( $items ) && $items[0]['qty'] > 1 ): ?>
        <span>
            <div class="num_control bd webkitbox" style="float:right">
                <div class="down_num bd_left">-</div>
                <div class="result_num bd_left"><input name="num" value="1" type="tel" min="1" max="10"></div>
                <div class="up_num bd_lr">+</div>
            </div>
        </span>
    <?php endif;?>
</div>
<?php if( isset( $items ) && $items[0]['qty'] > 1 ): ?>
<div class="color_888 count_num">总共拥有<?php echo isset( $items[0]['qty'] ) ? $items[0]['qty'] : '';?>份</div>
<?php endif;?>

<div class="list_style_1 martop bd">
    <!-- <div id="wx_address" class="original_address center" style="float:right">
        <em class="iconfont">&#xe61e;</em>
        <p class="h24">使用微信地址</p>
    </div> -->
    <div class="input_item" style="border:0;">
        <span>购买人</span>
        <span><input type="text" placeholder="请填写收件人姓名"  name="name" value="<?php echo $address['contact'];?>" /></span>
    </div>
    <div class="input_item">
        <span>购买电话</span>
        <span><input type="tel" placeholder="请填写收件人电话"  name="mobile" value="<?php echo $address['phone'];?>" /></span>
    </div>
    <a href="javascript:;" class="input_item" id="select_area">
        <span>选择区域</span>
        <span>
            <input type="text" placeholder="请选择区域" class="area_fill"  name="area" readonly value="<?php if( empty( $address['province'] ) ) : echo '请选择省/市/区';
                  else: echo $province_name.$city_name.$region_name; endif;?>" />
        </span>
    </a>
    <div class="input_item">
        <span>详细地址</span>
        <span><input type="text" placeholder="请填写收件人详细地址"  name="address" value="<?php echo $address['address'];?>" /></span>
    </div>
</div>

<div class="martop pad3 color_888">
    <input type="radio" class="hide" name='mail_type'/>
    <div class="radio addressPost"><div></div></div>
    <span>立即发货</span>
</div>
<div class="pad3 color_888">
    <input type="radio" class="hide reserve_time" name='mail_type'/>
    <div class="radio addressPost"><div></div></div>
    <span>预约发货</span>
    <input class="color_888" type="text" name="datetime" placeholder="点击选择发货时间" readonly style="display:none">
</div>

<div class="martop center">
    <input class="j_bt_time" type="hidden" name="arid" id="arid" value="<?php echo $arid; ?>" readonly style="width:6rem;">
    <input class="j_bt_time p_l_3" type="hidden" name="aiid" id="num" value="<?php echo $aiid; ?>" readonly style="width:6rem;">
    <div class="btn_main send_btn disable">提交邮寄</div>
</div>
<div class="center pad3 color_link h24" style="text-decoration:underline">暂不处理，放至订单中心</div>


<div class="ui_pull area_pull" style="display:none" onClick="toclose();">
    <div class="relative _w" style="height:100%;">
        <div class="area_box bg_fff absolute _w">
            <p class="color_888 close_pull pad3" style="float:right" onClick="toclose();">&times;</p>
            <p class="color_888 center pad3">选择地区</p>
            <p class="bd_bottom area_head">
                <span class="iscur"><?php if( !empty( $address['province'] ) ) {echo $province_name;}else{echo '请选择';} ?></span>
                <span><?php if( !empty( $address['city']) ) { echo $city_name;} ?></span>
                <span><?php if( !empty( $address['region'] ) ) { echo $region_name;} ?></span>
            </p>
            <div class="area_data relative">
                <ul class="province_box scroll" style="left:0;">
                    <input id="province" name='province' type="hidden" value="<?php echo $address['province'];?>">
                    <?php foreach( $provinces as $k=>$v ): ?>
                        <li onclick="area_data_click(this,event)"  val="<?php echo $v['region_id']; ?>" class="<?php if( $address['province'] == $v['region_id'] ) echo 'iscur'; ?>"><?php echo $v['region_name']; ?></li>
                    <?php endforeach; ?>
                </ul>
                <ul class="city_box scroll" url="<?php echo $citys_url; ?>">
                    <input id='city' name="city" type="hidden" value="<?php echo $address['city'];?>">
                    <!-- iscur表示当前选择的城市
                    <li onclick="area_data_click(this,event)"  val="id" class="iscur">城市</li>
                    -->
                    <?php foreach( $citys as $k=>$v ): ?>
                        <li onclick="area_data_click(this,event)" val="<?php //echo $v['region_id']; ?>" class="<?php if( $address['city'] == $v['region_id'] ) echo 'iscur'; ?>"><?php echo $v['region_name']; ?></li>
                    <?php endforeach; ?>
                </ul>
                <ul class="region_box scroll" url="<?php echo $regions_url; ?>">
                    <input id='region' name="region" type="hidden" value="<?php echo $address['region'];?>">
                    <!-- iscur表示当前选择的区域
                    <li onclick="area_data_click(this,event)"  val="id" class="iscur">区域</li>
                    -->
                    <?php foreach( $regions as $k=>$v ): ?>
                        <li onclick="area_data_click(this,event)" val="<?php echo $v['region_id']; ?>" class="<?php if( $address['region'] == $v['region_id'] ) echo 'iscur'; ?>"><?php echo $v['region_name']; ?></li>
                    <?php  endforeach; ?>
                </ul>
                <ul class="boxloading"></ul>
            </div>
        </div>
    </div>
</div>
</form>
<script>
    var name,mobile,area,province,city,region,address;
    function getval(){
        name = $("input[name=name]").val();
        mobile = $("input[name=mobile]").val();
        area = $('input[name=area]').val();
        province = $('#province').val();
        city = $('#city').val();
        region = $('#region').val();
        address = $("input[name=address]").val();
    }
    function button_change(){
        getval();
        $('.send_btn').addClass('disable');
        if($('input[name=mail_type]:checked').length==0 )return;
        if(name=='' ||!reg_phone.test(mobile) ||area == '' || address == '')return;
        if($('.reserve_time').get(0).checked && $('.datetime').val()=='')return;
        $('.send_btn').removeClass('disable');
    }
    function test_val(){
        getval();
        if( name == '' ){
            alert( '请输入收货人！' );
            return false;
        }
        if( mobile == '' ){
            alert( '请输入联系电话！' );
            return false;
        }
        if( !reg_phone.test(mobile)){
            alert( '手机号码格式有误！' );
            return false;
        }
        if( province == '' ){
            alert( '请选择地区！' );
            return false;
        }
        if( address == '' ){
            alert( '请输入详情地址！' );
            return false;
        }
        button_change();
        return true;
    }

    function area_data_click(dom,e){
        e.stopPropagation();
        var _this =$(dom);
        var _index = _this.parent().index();
        var _length = $('.area_head *').length;
        var _id = _this.attr('val');
        for( var i=_index;i<_length;i++){
            $('.area_head *').eq(i).html('');
        }
        _this.addClass('iscur').siblings().removeClass('iscur');
        $('.area_head *').eq(_index).html(_this.html());
        $('.boxloading').stop().show();
        if ( _index+1<_length){
            var _url = $('.area_data ul').eq(_index+1).attr('url');
            var tmp_this=$('.area_head *').eq(_index+1);
            tmp_this.html('请选择');
            tmp_this.addClass('iscur').siblings().removeClass('iscur');
            $.post( _url,  {"pid":_id},function(data){
                var _html = '';
                for (var i=0;i<data.length;i++){
                    _html += '<li onclick="area_data_click(this,event)" val="'+data[i].region_id+'">'+data[i].region_name+'</li>';
                }
                $('.area_data ul').eq(_index+1).find('li').remove();
                $('.area_data ul').eq(_index).animate({'left':'-100%'},300);
                $('.area_data ul').eq(_index+1).append(_html).animate({'left':'0'},300);
                $('.boxloading').stop().hide();
            },'json');
        }else{
            var _html = '';
            $('.area_box').stop().animate({'bottom':'-100%'},300,function(){
                for(var i=0;i<$('.area_head *').length;i++)
                    _html+=$('.area_head *').eq(i).html();
                $('.area_fill').val(_html);
                $('.boxloading').stop().hide();
            });
            for( var i=0;i<_length;i++){
                _id=$('.area_data ul').eq(i).find('li.iscur').attr('val');
                $('.area_data ul').eq(i).find('input').val(_id);
            }
            toclose();
        }
    }
    $(function(){
        var today =  new Date();
        var opt= {
            theme:'ios', //设置显示主题
            mode:'scroller', //设置日期选择方式，这里用滚动
            display:'bottom', //设置控件出现方式及样式
            preset : 'date', //日期:年 月 日 时 分
            minDate: today,
            maxDate: new Date(today.getTime()+24*60*60*1000*60),//60天内
            dateFormat: 'yy/mm/dd', // 日期格式
            dateOrder: 'yymmdd', //面板中日期排列格式
            stepMinute: 5, //设置分钟步长
            yearText:'年',
            monthText:'月',
            dayText:'日',
            lang:'zh' //设置控件语言};

        };

        $('input[name="datetime"]').mobiscroll(opt);
        window.setTimeout(removeload,200);
        $('#select_area').click(function(){
            toshow($('.area_pull'));
            $('.area_box').stop().animate({'bottom':0});
        });
		$('.area_box ').click(function(e){e.stopPropagation();});
		$('.area_head *').click(function(e){
			e.stopPropagation();
			if($(this).html()=='')return;
			var _index=$(this).index();
			var old_index=$('.area_head .iscur').index();
			var _left='0';
			var _dir = '';
			if(_index<old_index){_left='100%';}
			if(_index>old_index){_left='-100%';_dir='-'}
			$('.area_data ul').eq(old_index).animate({'left':_left},300);
			$('.area_data ul').eq(_index).animate({'left':'0'},300);
			for (var i=Math.min(_index,old_index);i<Math.max(_index,old_index);i++){
				$('.area_data ul').eq(i).css('left',_dir+'100%');
			}
			$(this).addClass('iscur').siblings().removeClass('iscur');
		})
		
        $('.radio').click(function(){
            $('input[name=datetime]').hide();
            $(this).siblings('input[name=datetime]').show();
        });
        $('input').blur(function(){
            if($(this).val()==''){
                alert($(this).attr('placeholder'));
                $('.send_btn').addClass('disable');
            }
        });
        $('input').focus(function(){
            // alert($('radio[name="mail_type"]').val());
            $('.send_btn').addClass('disable');
        });

        //选择发货方式,保存地址信息
        $(".addressPost").click(function(){
            // getval();
            if (test_val())
                $('.send_btn').addClass('disable');
                $.post("<?php echo $save_address; ?>",{contact:name,phone:mobile,province:province,city:city,region:region,address:address},function(json){
                if( json.status == 1 ){
                    button_change();
                    $("#arid").val(json.data);
                }
            },'json');
        });

        $('.send_btn').click(function(){
            if( !test_val()) return;
            if($(this).hasClass('disable'))return;
            $("#mailPost").submit();
        });
    });
</script>
</body>
</html>