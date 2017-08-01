<!doctype html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no,email=no">
    <meta name="ML-Config" content="fullscreen=yes,preventMove=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=320,initial-scale=1,user-scalable=0">
    <?php echo referurl('css','new_index.css',1,$media_path) ?>
    <title>快乐付</title>
</head>
<body ontouchstart="" style="background: #fcfcfc;" >
<div class="hd">
    <h1 class="page_title logo_line_height clearfix" style="font-weight:normal;">
        <img src="<?php echo $hotel['intro_img']; ?>" class="logo">
        <p class="txt_nowrap h_txt" style="padding-top:1.5%;"><?php echo $hotel['name']; ?></p>
        <p class="txt_nowrap h_txt" style="font-size: 14px;"><?php echo $pay_type_desc; ?></p>
    </h1>

</div>
<div class="spacing">
    <div class="panel bg_f">
        <div class="weui_cell txt-line-height h32" style="padding:0 15px;">
            <div class="weui_cell_hd width" style="height:44px;line-height:44px;">
                <label class="weui_label">消费总额：</label>
            </div>
            <div class="weui_cell_bd weui_cell_primary txt-float-right" style="line-height:44px;height:44px;" >
                <input class="weui_input txt-float-right h32 w_100" id="money" name="money" type="number" pattern="[0-9].*" placeholder="询问服务员后输入">
            </div>
        </div>
    </div>
    <!--<div class="weui_cells_title weui_cells_checkbox">
       <label class="weui_cell weui_check_label" for="s_check" style="margin-left: -10px;">
           <div class="weui_cell_hd" style="margin-right:2%;">
               <input type="checkbox" class="weui_check" name="checkbox1" id="s_check">
               <div class="weui_icon_checked"></div>
           </div>
           <div class="weui_cell_bd weui_cell_primary">
               <p>输入不参与优惠金额(如酒水、套餐)</p>
               <input type="hidden" id="ck_add_no_sale" value="0">
           </div>
       </label>
   </div>
   <div class="weui_panel bg_f">
       <div class="weui_cell txt-line-height border_bottom border_top" id="no_sale_display" style="display:none;">
           <div class="weui_cell_hd width">
               <label class="weui_label emwith">不参与优惠金额：</label>
           </div>
           <div class="weui_cell_bd weui_cell_primary txt-float-right">
               <input class="weui_input txt-float-right" id="no_sale" type="number" pattern="[0-9].*" placeholder="询问服务员后输入">
           </div>
       </div>
       <input type="hidden" value="0" id="paycode">
   </div>-->
    <input type="hidden" value="<?php echo isset($pay_code)?$pay_code:0; ?>" id="paycode"/>
    <?php if(!empty($activity)){?>
    <div class="weui_panel border_bottom border_top bg_f m_t_4 h28">

            <div style="display:none;">
                <input type="hidden" id="isfor" value="<?php echo $activity['isfor']; ?>">
                <input type="hidden" id="isformoney" value="<?php echo $activity['isfor_money']; ?>">
                <input type="hidden" id="isfordiscount" value="<?php echo $activity['discount_amount']; ?>">
            </div>
        <div class="weui_cell weui_cells_access txt-line-height border_bottom">
            <div class="weui_cell_hd width ">
                <div class="weui_label emwith" >优惠折扣</div>
            </div>
            <div class="weui_cell_bd weui_cell_primary txt-float-right">
                <div class="" style="font-weight:normal;" ><?php echo $activity['title']; ?></div>
            </div>
        </div>
            <?php if($activity['isfor'] != 3){?>
        <div class="weui_cell txt-line-height" style="align-items:flex-start;">
            <div class="weui_cell_hd width">
                <label class="weui_label emwith">实付付款</label>
            </div>
            <div class="weui_cell_bd weui_cell_primary txt-float-right">
                <div class="h32 l_h_12" style="color:#ff7200;" id="show_money">¥0.0</div>
                <div class="l_h_12" style="font-size:11px; color:#a4a4a4;" id="sale_count">已优惠:¥0.0</div>
            </div>
        </div>
                <?php }?>
    </div>
        <?php }else{?>
            <div style="display:none;">
                <input type="hidden" id="isfor" value="0">
                <input type="hidden" id="isformoney" value="0">
                <input type="hidden" id="isfordiscount" value="0">
            </div>
        <?php }?>
        <input type="hidden" value="" id="pay_money" name="pay_money">
        <input type="hidden" value="" id="discount_money" name="discount_money">

    <?php if(!empty($membermoney)){?>
    <div class="weui_panel border_bottom border_top bg_f m_t_4" id="pay_ways" style="padding:0% 0 0% 5%;">

        <div class="weui_cell weui_cells_access txt-line-height border_bottom" style="padding-left:0;padding-right:0px;">
            <div class="weui_cell_hd width" style="padding:0;">
                <label class="weui_label emwith">支付方式</label>
            </div>
        </div>
        <div class="weui_cell txt-line-height j_add_btn add_bg border_bottom" style="padding:14px 0;">
            <div class="weui_cell_hd width">
                <span class="j_ico"><img src="<?php echo base_url('public/okpay/default/images/iconfont-weixinzhifu.png')?>"></span>
                <span>微信支付</span>
            </div>
        </div>
        <div class="weui_cell txt-line-height j_add_btn2" style="padding:14px 0;">
            <div class="weui_cell_hd width">
                <span class="j_ico balance_img"><img src="<?php echo base_url('public/okpay/default/images/iconfont-qianbaozhifu.png')?>"></span>
                <span>余额支付</span>
                <font class="balance" style="color:#999">(<?php echo $membermoney;?>元)</font>
            </div>
        </div>

    </div>
    <?php }?>
    <div class="weui_btn_area" style="margin-top:45px;">
        <button class="weui_btn bg_ff7" href="javascript:;" id="btn_submit">立即支付</button>
    </div>
    <!--<div class="weui_btn_area" style="margin-bottom: 40px;text-align:center;font-size:14px">
        <a class="" href="http://ihotels.iwide.cn/index.php/okpay/okpay/pay_record" id="showTooltips2">查看支付历史</a>
    </div>-->
</div>

<div class="flex" >
    <div class="play_box fle_box pb_4"  style='display:none;'>
        <div class="title" >
            <img class="close" src="<?php echo base_url('public/okpay/default/images/close.png')?>" style="">
            <div >余额支付</div>
        </div>
        <p class="ho_name" ><?php echo $hotel['name']; ?></p>
        <div class="f_money" >¥0.00</div>
        <div class="fle_btn" >确定</div>
    </div>
    <div class="false_box fle_box"  style='display:none;' >
        <div class="error border_bottom">支付密码错误,请重试.</div>
        <div class="btn_list">
            <a class='' href='javascript:;'><div class="border_right age_btn" >重试</div></a>
            <a><div class="in_close">关闭</div></a>
        </div>
    </div>
    <div class="password_box fle_box pb_4" style='display:none;'>
        <div class="title" >
            <img class="close" src="<?php echo base_url('public/okpay/default/images/close.png')?>" style="">
            <div >余额支付</div>
        </div>
        <p class="ho_name" ><?php echo $hotel['name']; ?></p>
        <div class="f_money" >¥0.00</div>
        <div class="list_input">
            <div class="box_input"><input type='tel' id='input' maxlength='6' /></div>
            <div class="box_input box_input2"><input type='tel' maxlength='1' readonly /></div>
            <div class="box_input"><input type='tel' maxlength='1' readonly /></div>
            <div class="box_input"><input type='tel' maxlength='1' readonly /></div>
            <div class="box_input"><input type='tel' maxlength='1' readonly /></div>
            <div class="box_input"><input type='tel' maxlength='1' readonly /></div>
        </div>
    </div>
</div>

<?php echo referurl('js','jquery.min.js',1,$media_path) ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.close').click(function(){
            $('.flex').fadeOut();
            $('.play_box').fadeOut();
            $('.false_box').fadeOut();
            $('.password_box').fadeOut();
            $('#btn_submit').removeAttr("disabled");
        });
        //$('.balance').html().substring(1,$('.balance').html().length-2) 余额
        var balance_val=parseFloat(<?php echo $membermoney;?>);
        $(".weui_input").focus(function(){
            $(".weui_input").attr("placeholder","");
            $(".weui_input").css({"line-height":"44px","height":"44px","font-size":"2.3rem"})
        })
        $(".weui_input").blur(function(){
            if($(".weui_input").val()==''){
                $(".weui_input").attr("placeholder","询问服务员后输入");
                $(".weui_input").css({"line-height":"","height":"","font-size":""})
            }
        })
        $("#money").blur(function(){
            checkSale();
        });
        $("#money").change(function(){
            checkSale();
        });
        /*$("#no_sale").blur(function(){
         checkSale();
         });*/

        $("#money").keyup(function(){
            var money = $.trim($(this).val());
            if(money.length >= 2 && money == "00"){
                $(this).val(money.substring(0,1));
            }
            var point = money.indexOf(".");
            if(point > 0){
                var little_num = money.substring((point+1));
                if(little_num.length > 2){
                    $(this).val(parseFloat(money).toFixed(2));
                }
            }
            var zero = money.substring(0,1);
            if(zero == "0" && money.length > 1 && point != 1){
                $(this).val(money.substring(1));
            }
        });

        function accSub(num1,num2){
            var r1,r2,m;
            try{
                r1 = num1.toString().split('.')[1].length;
            }catch(e){
                r1 = 0;
            }
            try{
                r2=num2.toString().split(".")[1].length;
            }catch(e){
                r2=0;
            }
            m=Math.pow(10,Math.max(r1,r2));
            n=(r1>=r2)?r1:r2;
            return (Math.round(num1*m-num2*m)/m).toFixed(n);
        }

        function checkSale(){
            //如果勾选了不打折金额，则进行处理，否则略过
            var no_sale = parseInt($("#ck_add_no_sale").val());
            if(no_sale < 0){
                alert("不打折金额不能小于0");
                $("#ck_add_no_sale").focus();
                return false;
            }
            var money = parseFloat($("#money").val()); //待支付总金额
            if(money < 0){
                alert("待支付金额不能小于0");
                $("#money").focus();
                return false;
            }
            $("#money").val(money); //避免先输入  小数点引起的问题  .3
            var tmp_money = money;

            var nosale = $("#no_sale").val();
            if(nosale == null || nosale == ""){
                nosale = 0;
            }
            var no_sale_money = parseFloat(nosale); //不打折金额
            if(no_sale_money != "" && no_sale_money < 0){
                alert("不优惠金额不能小于0");
                $("#ck_add_no_sale").focus();
                return false;
            }
           /* if(no_sale == 1){
                if(tmp_money < no_sale_money){
                    alert("不可优惠金额不能大于消费总额");
                    return false;
                }
                money = parseFloat(accSub(money,no_sale_money));

            }*/
            //判断是否存在优惠
            var isfor = parseInt($("#isfor").val());
            if(isfor > 0){
                var isformoney = parseFloat($("#isformoney").val());
                var isfordiscount = parseFloat($("#isfordiscount").val());

                //每满
                if(isfor == 1){
                    if(money >= isformoney){
                        //向下取整。满足 每满减
                        money = money - Math.floor(money / isformoney)  * isfordiscount;
                    }
                }else if(isfor == 2){
                    //满多少减去优惠
                    if(money >= isformoney){
                        money = money - isfordiscount;
                    }
                }
            }

            //有可能用户输入后，又删除金额
            if(isNaN(tmp_money) || tmp_money <= 0){
                $("#money").val('');
                money = 0;
                tmp_money = 0;
            }

            if(balance_val<parseFloat(money).toFixed(2)){
                $('.j_add_btn').click(function(){
                    $(this).addClass('add_bg').siblings().removeClass('add_bg');
                })
                $('.j_add_btn2').off('click');
                $('.j_add_btn2').removeClass('add_bg').addClass('active_color');
                $('.j_add_btn2').find('img').attr('src','<?php echo base_url('public/okpay/default/images/iconfont-qianbaozhifu2.png')?>');
                $('.j_add_btn').addClass('add_bg');
            }else{
                $('.j_add_btn,.j_add_btn2').bind('click',function(){
                    $(this).addClass('add_bg').siblings().removeClass('add_bg');
                });
                $('.j_add_btn').removeClass('add_bg');
                $('.j_add_btn2').removeClass('active_color').addClass('add_bg');
                $('.j_add_btn2').find('img').attr('src','<?php echo base_url('public/okpay/default/images/iconfont-qianbaozhifu.png')?>');
            }

            $("#show_money").text("¥"+parseFloat(money).toFixed(2));
            $("#pay_money").val(parseFloat(money).toFixed(2));


            $("#sale_count").text("总优惠：¥"+parseFloat(tmp_money - money).toFixed(2));
            $("#discount_money").val(parseFloat(tmp_money - money).toFixed(2));

        }
        var bra=0;
        $("#btn_submit").bind("click",function(){
            $(this).attr("disabled",true);
            var pay_money = parseFloat($("#pay_money").val());
            var pay_code = $.trim($("#paycode").val());

            //如果勾选了不打折金额，则进行处理，否则略过
            var no_sale = parseInt($("#ck_add_no_sale").val());
            var money = parseFloat($("#money").val()); //待支付总金额
            //var no_sale_money = parseFloat($("#no_sale").val()); //不打折金额

            if(pay_money != "" && pay_money > 0 /*&& no_sale>=0*/){
                var pay_ways = $("#pay_ways").find(".add_bg").index();
                //alert(pay_ways);
                var url = "<?php echo site_url('okpay/okpay/new_okpay_order')?>?id=<?php echo $inter_id?>"; //微信支付
                if(pay_ways==2){
                    url = "<?php echo site_url('okpay/okpay/okpay_by_banlance')?>?id=<?php echo $inter_id?>";//余额支付
                    $(".f_money").text("¥"+parseFloat(pay_money).toFixed(2));
                    //余额支付
                    $('.flex').fadeIn();
                    // if(bra){  //不要密码的
                    <?php if(!empty($membermoney)){?>
                    <?php if(isset($set_pass) && !$set_pass){?>
                    $('.play_box').fadeIn();
                    $('.fle_btn').one("click",function(){//不用密码 需要二次确认
                        <?php }else{?>
                        $('.password_box').fadeIn();//需要密码
                        $('#input').focus();
                        $('#input').on('keyup',function(){
                            for(var i=0;i<$('#input').val().length;i++){
                                $('.list_input >div').eq(i).addClass('active');
                            }
                            if($('#input').val().length==6){
                                $('#input').css({"display":"none"});
                                setTimeout(function(){
                                    $('#input').blur();
                                    $('.password_box').fadeOut();
                                    <?php }}?>
                                    $.post(url,
                                        {'money':parseFloat($("#money").val()),
                                            'pay_money':pay_money,
                                            <?php if(!empty($membermoney) && isset($set_pass) && $set_pass){?>'banlance_pwd':$('#input').val(),<?php }//余额支付密码?>
                                            'discount_money':$("#discount_money").val(),
                                            'pay_type':'<?php echo $pay_type; ?>',
                                            'pay_code':pay_code,
                                            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                                        },function(data){
                                            if(data.errmsg == 'ok'){
                                                if(pay_ways==2){
                                                    window.location.href="<?php echo site_url('okpay/okpay/pay_success')?>?id=<?php echo $inter_id?>&oid="+data.oid;
                                                }/*else{
                                                    window.location.href="<?php echo site_url('wxpay/okpay_pay')?>?id=<?php echo $inter_id?>&hid=<?php echo $hotel_id; ?>&oid="+data.oid;
                                                }*/
                                                $(this).removeAttr("disabled");
                                            }else if(data.errmsg == 'pwfail'){
                                                //$(this).removeAttr("disabled");
                                                alert(data.msg);
                                                $('#input').css({"display":"block"});
                                                $('.false_box').fadeIn();
                                                $('.age_btn').click(function(){
                                                    $('#input').val('');
                                                    for(var i=0;i<6;i++){
                                                        $('.list_input >div').eq(i).removeClass('active');
                                                    }
                                                    $('.false_box').hide();
                                                    $('.password_box').fadeIn();
                                                    $('#input').focus();
                                                });
                                                $('.in_close').click(function(){
                                                    $('.false_box').hide();
                                                    $('.flex').fadeOut();
                                                    $('#btn_submit').attr("disabled",false);
                                                });
                                            }else{
                                                $(this).removeAttr("disabled");
                                                alert('下单失败,请重试！');
                                            }
                                        },'json');
                                    <?php if(!empty($membermoney) && isset($set_pass) && $set_pass){?>
                                },100);
                            }      //有密码使用的
                            <?php }?>
                            <?php if(!empty($membermoney)){//这个结束标志是余额支付密码用的?>     }); <?php }?>
                }else{
                        $.post(url,
                            {'money':parseFloat($("#money").val()),
                                'pay_money':pay_money,
                                'discount_money':$("#discount_money").val(),
                                'pay_type':'<?php echo $pay_type; ?>',
                                'pay_code':pay_code,
                                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                            },function(data){
                                if(data.errmsg == 'ok'){
                                    /*if(pay_ways==2){
                                        window.location.href="<?php echo site_url('okpay/okpay/pay_success')?>?id=<?php echo $inter_id?>&oid="+data.oid;
                                    }else{*/
                                        window.location.href="<?php echo $wx_pay_url?>&oid="+data.oid;
                                    //}
                                    $(this).removeAttr("disabled");
                                }else{
                                    $(this).removeAttr("disabled");
                                    alert('下单失败,请重试！');
                                }
                            },'json');
                    }
            }else{
                alert("请输入支付金额");
                $("#money").focus();
                $(this).removeAttr("disabled");
            }
        });

    });


</script>
<?php echo referurl('js','hide_menu.js',1,$media_path) ?></body>
</html>
