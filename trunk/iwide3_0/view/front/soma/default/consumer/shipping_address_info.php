
<!-- 以上为head -->
<script>
wx.config({
    debug: false,
    appId: '<?php echo $wx_config["appId"]?>',
    timestamp: <?php echo $wx_config["timestamp"]?>,
    nonceStr: '<?php echo $wx_config["nonceStr"]?>',
    signature: '<?php echo $wx_config["signature"]?>',
    jsApiList: [<?php echo $js_api_list; ?>]
});
wx.ready(function(){
    <?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
});
</script>

<body>
<div class="pageloading"><p class="isload" style="margin-top:150px"><?php echo $lang->line('loading'); ?></p></div>
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
<div class="whiteblock webkitbox justify bd" style="margin-top:0">
    <span><?php echo $lang->line('mailed_content'); ?></span>
    <span><?php echo isset( $items[0]['name'] ) ? $items[0]['name'] : '';?></span>
</div>
<div class="whiteblock webkitbox justify bd">
    <span><?php echo $lang->line('mailed_copies'); ?></span>
    <?php if( isset( $items ) && $items[0]['qty'] == 1 ): ?>
        <input name="num" value="1" max="1" readonly type="hidden" />
        <?php
            $tpl = $lang->line('item');
            $str = str_replace('[0]', '1', $tpl);
        ?>
        <span><?php echo $str; ?></span>
    <?php elseif( isset( $items ) && $items[0]['qty'] > 1 ): ?>
        <span>
            <div class="num_control bd webkitbox" style="float:right">
                <div class="down_num bd_left">-</div>
                <div class="result_num bd_left"><input name="num" value="<?php echo isset( $items[0]['qty'] ) ? $items[0]['qty'] : '1';?>" type="tel" min="1" max="<?php echo isset( $items[0]['qty'] ) ? $items[0]['qty'] : '';?>"></div>
                <div class="up_num bd_lr">+</div>
            </div>
        </span>
    <?php endif;?>
</div>
<?php if( isset( $items ) && $items[0]['qty'] > 1 ): ?>
    <?php 
        $tpl = $lang->line('total');
        $total_qty = 0;
        if(isset($items[0]['qty']))
        {
            $total_qty = $items[0]['qty'];
        }
        $msg = str_replace('[0]', $total_qty, $tpl);
    ?>

<div class="color_888 txt_r" style="padding: 8px 8px 0 8px;"><?php echo $msg; ?></div>
<?php endif;?>

<div class="list_style_1 martop bd">
    <div class="original_address center" id="originalAddress" style="float:right">
        <em class="iconfont">&#xe61f;</em>
        <p class="h24"><?php echo $lang->line('use_wechat_address'); ?></p>
    </div>
    <div class="input_item" style="border:0;">
        <span><?php echo $lang->line('recipient'); ?></span>
        <span><input type="text" placeholder="<?php echo $lang->line('fill_recipient_name_tip'); ?>"  name="name" value="<?php echo $address['contact'];?>" /></span>
    </div>
    <div class="input_item">
        <span><?php echo $lang->line('recipient_phone_number'); ?></span>
        <span><input type="tel" placeholder="<?php echo $lang->line('fill_recipient_call_tip'); ?>"  name="mobile" value="<?php echo $address['phone'];?>" /></span>
    </div>
    <div class="input_item arrow" id="select_area">
        <span><?php echo $lang->line('district'); ?></span>
        <span>
            <input type="text" placeholder="<?php echo $lang->line('district_please'); ?>" class="area_fill"  name="area" readonly value="<?php if( !empty( $address['province'] ) ) : echo $province_name.$city_name.$region_name; endif;?>" />
        </span>
    </div>
    <div class="input_item">
        <span><?php echo $lang->line('address'); ?></span>
        <span><input type="text" placeholder="<?php echo $lang->line('fill_recipient_address_tip'); ?>"  name="address" value="<?php echo $address['address'];?>" /></span>
    </div>
    <div class="input_item">
        <span><?php echo $lang->line('remarks'); ?></span>
        <span><input type="text" placeholder="<?php echo $lang->line('fill_remarks_tip'); ?>"  name="note" value="" /></span>
    </div>
</div>

<div class="martop pad3 color_888">
    <input type="radio" class="hide go_now" name='mail_type'/>
    <div class="radio addressPost"><div></div></div>
    <span class="radio_click"><?php echo $lang->line('ship_now'); ?></span>
</div>
<?php if( isset( $items[0]['is_hide_reserve_date'] ) && $items[0]['is_hide_reserve_date'] == Soma_base::STATUS_TRUE ):?>
<div class="pad3 color_888">
    <input type="radio" class="hide reserve_time" name='mail_type'/>
    <div class="radio addressPost"><div></div></div>
    <span class="radio_click"><?php echo $lang->line('make_appointment'); ?></span>
    <input class="color_888 datetime" type="text" name="datetime" placeholder="<?php echo $lang->line('select_ship_time'); ?>" readonly style="display:none">
</div>
<?php endif;?>

<!-- 邮费 -->
<?php if(isset($shipping_product)): ?>
<div class="pad3 color_000" id="postage"></div>
<div class="pad3 color_999 h22"><?php echo $lang->line('postage_desc'); ?> <?php echo $product['shipping_instruction'] ?></div>
<script>
$(function(){
    	var n = <?php echo $product['shipping_fee_unit']; ?> //邮费单位
    	var p = <?php echo $shipping_product['price_package']; ?> //单件价格
    	function  postage(){
            var val = $('input[name="num"]').val();
    		var sum = Math.ceil(val / n) * p;
            var mail_fee_tpl = "<?php echo $lang->line('postage'); ?>";
            var mail_fee_html = mail_fee_tpl.replace('[0]', sum);
            $('#postage').html(mail_fee_html);
    		// $('#postage').html('邮费：'+sum + '元');
    	}
    	 $('.num_control').click(postage);
    	postage();
});
</script>
<?php endif; ?>
<!-- end -->

<div class="martop center">
    <input class="j_bt_time" type="hidden" name="is_wx_address" id="is_wx_address" value="" style="width:6rem;">
    <input class="j_bt_time" type="hidden" name="arid" id="arid" value="<?php echo $arid; ?>" readonly style="width:6rem;">
    <input class="j_bt_time p_l_3" type="hidden" name="aiid" id="num" value="<?php echo $aiid; ?>" readonly style="width:6rem;">
    <?php if(isset($shipping_product)): ?>
        <div class="btn_main send_btn disable"><?php echo $lang->line('pay_and_mail'); ?></div>
    <?php else: ?>
        <div class="btn_main send_btn disable"><?php echo $lang->line('submit_for_mail'); ?></div>
    <?php endif; ?>
    <!-- <div class="btn_main shipping_order_btn">支付邮费并邮寄</div> -->
</div>
    <a href="<?php echo Soma_const_url::inst()->get_soma_order_list(array('id'=>$inter_id)); ?>">
        <div class="center pad3 color_link h24" style="text-decoration:underline"><?php echo $lang->line('mail_later'); ?></div>
    </a>
    <?php if( !empty( $mail_error_msg ) ): ?>
        <div class="center pad3 h24" style="color: red;"><?php echo $mail_error_msg; ?></div>
    <?php endif;?>


<div class="ui_pull area_pull" style="display:none" onClick="toclose();">
    <div class="relative _w" style="height:100%;">
        <div class="area_box bg_fff absolute _w">
            <p class="fcolor close_pull pad3" style="float:right" onClick="toclose();">&times;</p>
            <p class="fcolor center pad3"><?php echo $lang->line('select_area'); ?></p>
            <p class="border_bottom area_head">
                <span class="iscur" id="selectProvince"><?php if( !empty( $address['province'] ) ) {echo $province_name;}else{echo $lang->line('choose');} ?></span>
                <span id="selectCity"><?php if( !empty( $address['city']) ) { echo $city_name;} ?></span>
                <span id="selectRegion"><?php if( !empty( $address['region'] ) ) { echo $region_name;} ?></span>
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
	var isSubmit =0;
    var payUrl = '<?php echo Soma_const_url::inst()->go_to_pay( array('id'=> $this->inter_id ) );//订单生产请求地址?>';
    var mail_post_url = '<?php echo Soma_const_url::inst()->get_url('*/*/get_shipping_order_id_by_ajax', array('id'=> $this->inter_id));//订单生产请求地址?>';

    var goto_save_address = true;
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
        // if($('.reserve_time').get(0).checked && $('.datetime').val()=='')return;//点击提交邮寄按钮后才检测
        $('.send_btn').removeClass('disable');
    }
    function test_val(){
        getval();
        if( name == '' ){
            $.MsgBox.Confirm( "<?php echo $lang->line('enter_recipient_tip'); ?>",null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancle'); ?>" );
            return false;
        }
        if( mobile == '' ){
            $.MsgBox.Confirm( "<?php echo $lang->line('enter_contact_phone_tip'); ?>" ,null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancle'); ?>");
            return false;
        }
        if( !reg_phone.test(mobile)){
            $.MsgBox.Confirm( "<?php echo $lang->line('phone_number_wrong') . '!'; ?>",null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancle'); ?>");
            return false;
        }
        if( province == '' && goto_save_address ){
            $.MsgBox.Confirm( "<?php echo $lang->line('select_area_tip'); ?>" ,null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancle'); ?>");
            return false;
        }
        if( address == '' ){
            $.MsgBox.Confirm( "<?php echo $lang->line('enter_address_tip'); ?>" ,null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancle'); ?>");
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
            tmp_this.html('<?php echo $lang->line('choose'); ?>');
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
		var morrow=new Date((today/1000+86400)*1000);
        var opt= {
            theme:'ios', //设置显示主题
            mode:'scroller', //设置日期选择方式，这里用滚动
            display:'bottom', //设置控件出现方式及样式
            preset : 'date', //日期:年 月 日 时 分
            minDate: morrow,
            // maxDate: new Date(today.getTime()+24*60*60*1000*60),//60天内
            maxDate: new Date(<?php echo $expirtime*1000;?>),//60天内
            dateFormat: 'yy/mm/dd', // 日期格式
            dateOrder: 'yymmdd', //面板中日期排列格式
            stepMinute: 5, //设置分钟步长
            yearText:"<?php echo $lang->line('year'); ?>",
            monthText:"<?php echo $lang->line('month'); ?>",
            dayText:"<?php echo $lang->line('day'); ?>",
            lang:"<?php echo ($langDir == 'english') ? 'en' : 'zh';?>" //设置控件语言};

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
		$('.radio_click').click(function(){
			$(this).siblings('.radio').trigger('click');
		})
		$('input').change(button_change);
		$('input[name="num"]').blur(function(){
			if (isNaN($(this).val()))
				$(this).val('1');
			if($(this).val()*1>$(this).attr('max')*1)
				$(this).val($(this).attr('max'));
		});
		$('input').focus(function(){
            $('.reserve_time').get(0).checked = false;
            $('.go_now').get(0).checked = false;
            $('.send_btn').addClass('disable');
            $('.datetime').hide();
		});

        //选择发货方式,保存地址信息
        $(".addressPost").click(function(){
            //如果选择的是微信地址，不保存地址信息
            if( goto_save_address ){
                // getval();
    			pageloading("<?php echo $lang->line('please_wait_tip'); ?>",0.2);
                if (test_val())
                    $('.send_btn').addClass('disable');
                    $.post("<?php echo $save_address; ?>",{contact:name,phone:mobile,province:province,city:city,region:region,address:address},function(json){
    				removeload();
                    if( json.status == 1 ){
                        button_change();
                        $("#arid").val(json.data);
                    }
                },'json');
            }else{
                button_change();
            }
        });

        // 新邮寄提交，支付邮费与无需支付邮费并存
        $('.send_btn').click(function(){

            if( !test_val()) return;
            if($(this).hasClass('disable'))return;
			if(isSubmit>=1) { $.MsgBox.Confirm("<?php echo $lang->line('data_submit'); ?>",null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancel'); ?>");return; }
			isSubmit = 1;
            var form = $('#mailPost').serializeArray();
            form.push({name:"product_id", value: "<?php echo $items[0]['product_id']; ?>"});
			pageloading();
            $.ajax({
                async: true,
                type: 'POST',
                timeout : 10000,
                url: mail_post_url,
                data: form,
                success: function(data,text_status,re){
                    // console.log(data);return;
                    if(re.status == 200) {
                        if(data.status == 1){
                            if(data.step =='wxpay'){
                                location.href = payUrl+'&order_id='+data.data.orderId + '&wxpay_order_type=2';
                            }else{
                                location.href = data.data.url;
                            }
                        }else if(data.status == 2){
                            $('.pageloading').remove();
                            $.MsgBox.Confirm(data.message,null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancel'); ?>");
                            return false;
                        }
                    }
                    if(re.status == 302||re.status == 307) {
                        window.location.reload();
                    }           
                },
                error: function(e) {
                    //失败
                    $.MsgBox.Confirm("<?php echo $lang->line('mail_fail_tip'); ?>",function(){
                        //if(e.status == 302||e.status == 307) {
                            window.location.reload();
                        //}     
                    },function(){
                            window.location.reload();
                    },"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancel'); ?>");
                    return false;
                },
                timeout: function(e) {
                            //失败
                    $.MsgBox.Confirm("<?php echo $lang->line('mail_timeout_tip'); ?>",function(){
                        //if(e.status == 302||e.status == 307) {
                            window.location.reload();
                        //}     
                    },function(){
                            window.location.reload();
                    },"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancel'); ?>");
                },
                complete : function(XMLHttpRequest,status){ //请求完成后最终执行参数
					removeload();
            　　　　if(status=='timeout'){//超时,status还有success,error等值的情况
                        $('.pageloading').remove();
                        $.MsgBox.Confirm("<?php echo $lang->line('mail_timeout_tip'); ?>",function(){
                            //if(e.status == 302||e.status == 307) {
                                window.location.reload();
                            //}     
                        },function(){
                                window.location.reload();
                        },"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancel'); ?>");
            　　　　}
            　　},
                dataType: 'json'
            })

        });

        /**
        // 取消此种方式提交数据
        $('.send_btn').click(function(){
            if( !test_val()) return;
            if($(this).hasClass('disable'))return;
            <?php if( isset( $items[0]['is_hide_reserve_date'] ) && $items[0]['is_hide_reserve_date'] == Soma_base::STATUS_TRUE ):?>
                if($('.reserve_time').get(0).checked && $('.datetime').val()==''){
                    $.MsgBox.Confirm('请选择预约发货时间');
                    return;
                }
            <?php endif;?>
            pageloading('正在打包',0.3);
            $("#mailPost").submit();
        });

        */
        //提交按钮，第一次邮寄进来，需要编辑地址（提交按钮变灰），第二次进来不编辑有默认arid，编辑提交按钮变灰、选择发货方式变为未选状态
        // $('.send_btn').removeClass('disable');
    });

    //微信地址
    $("#originalAddress").click(function(){
        wx.checkJsApi({
            jsApiList: ['chooseImage'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
            success: function(res) {
                // return false;
                // alert(res.checkResult.chooseImage);
                // 以键值对的形式返回，可用的api值true，不可用为false
                // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
                if( res.checkResult.chooseImage ){
                    wx.openAddress({
                        success: function (addrRes) { 
                            // 用户成功拉出地址 
                            $("input[name=mobile]").val(addrRes.telNumber);
                            $("input[name=name]").val(addrRes.userName);
                            $('#selectProvince').html(addrRes.provinceName);
                            $('#selectCity').html(addrRes.cityName);
                            $('#selectRegion').html(addrRes.countryName);
                            $('input[name=area]').val(addrRes.provinceName+addrRes.cityName+addrRes.countryName);
                            $("input[name=address]").val(addrRes.detailInfo);
                            // for( var key in res ){
                            //     alert(key+'->'+res[key]);
                            // }
                            $('.reserve_time').get(0).checked = false;
                            $('.go_now').get(0).checked = false;
                            $('.send_btn').addClass('disable');
                            $('.datetime').hide();

                            $('#is_wx_address').val(1);
                            goto_save_address = false;
                        },
                        cancel: function () { 
                            // 用户取消拉出地址
                        }
                    });
                }
            }
        });

    });

    function checkFollow(){
        //异步查询是否关注
        $.ajax({
            type: 'POST',
            url: "<?php echo $check_follow_ajax; ?>",
            dataType: 'json',
            success:function(json){
                var tips = '';
                var leftLink = '';
                var leftUrl = '#';
                var rightLink = '';
                var rightUrl = '#';
                if( json.status == 2 ){
                    // alert( json.message );
                    // $("#addUrl").attr('href',json.data);
                    tips = json.message;
                    leftLink = "<?php echo $lang->line('use_now'); ?>";
                    rightLink = "<?php echo $lang->line('attention'); ?>";
                    rightUrl = json.data;
                }else{
                    // alert( json.message );
                    // $("#addUrl").attr('href',json.data);
                    tips = json.message;
                    leftLink = "<?php echo $lang->line('continue_use'); ?>";
                    rightLink = "<?php echo $lang->line('to_online_shop'); ?>";
                    rightUrl = json.data;
                }
				$.MsgBox.Confirm(tips,function(){
					window.location.href=rightUrl;
				},function(){
					window.location.href=leftUrl;					
				},rightLink,leftLink);
            }
        });
    }

    $("#addUrl").click(function(){
        checkFollow();
    });

</script>
</body>
</html>