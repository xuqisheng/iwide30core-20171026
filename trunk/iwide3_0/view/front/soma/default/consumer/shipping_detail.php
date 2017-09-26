<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.core.js'); ?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.widget.js'); ?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.scroller.js'); ?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.util.datetime.js'); ?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.datetimebase.js'); ?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.widget.ios.js'); ?>"></script>
<script src="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.i18n.zh.js'); ?>"></script>
<link href="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.animation.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.widget.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.widget.ios.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.scroller.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo get_cdn_url('public/soma/calendar/mobiscroll.scroller.ios.css'); ?>" rel="stylesheet" type="text/css">
<title><?php echo $lang->line('delivery_status'); ?></title>
<style>
.list_style {display:list-item;}
.list_style span{display:inline-block; min-width:6rem;}
.list_style > *{ display:block}
.list_style >*>div{ padding-left:15px;}
</style>
</head>
<body>

<div class="order_list bd_bottom">
    <?php foreach($orders['items'] as $v ): ?>
    <div class="item color_555 bg_fff">
        <div class="item_left">
            <div class="img"><img src="<?php echo $v['face_img'];?>"></div>
            <p class="txtclip h30"><b><?php echo $v['name'];?></b></p>
            <p class="txtclip h30 c_666"><?php echo $v['hotel_name'];?></p>
            <p class="txtclip h30 color_main"><?php echo $v['price_package'],'x',$v['consumer_qty'];?></span></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="whiteblock bd">
    <div>
        <p style="padding-bottom:8px;"><?php echo $lang->line('receipient'); ?>：<?php echo $orders['contacts']; ?></p>
        <p style="padding-bottom:8px;"><?php echo $lang->line('receipient_address'); ?>：<?php echo $orders['address']; ?></p>
        <p style="padding-bottom:8px;"><?php echo $lang->line('receipient_number'); ?>：<?php echo $orders['phone']; ?></p>
    </div>
</div>

<?php if( $orders['reserve_date'] && ( !$orders['tracking_no'] || !$orders['distributor'] ) ): ?>
<div class="whiteblock bd j_modify center"><?php echo $lang->line('modify'); ?></div>
<?php endif; ?>

<ul class="list_style martop bd">
    <li class="center pad3">
            <span class="color_main h32"><em class="iconfont" style="font-size:2rem">&#xe61d;</em>
            <?php if( $orders['status'] ==  $ConsumerShippingModel::STATUS_SHIPPED ):?>
                <?php echo $lang->line('good_shipped_tip'); ?>
            <?php elseif( $orders['status'] ==  $ConsumerShippingModel::STATUS_FINISHED ): ?>
                <?php echo $lang->line('good_signed_tip'); ?>
            <?php elseif( !empty( $orders['reserve_date'] )  ): ?>

                <?php 
                    $lang_tpl = $lang->line('order_ship_tip');
                    $reserve_date = explode(' ', $orders['reserve_date'] );
                    $lang_str = str_replace('[0]', $reserve_date[0], $lang_tpl);
                    echo $lang_str;
                ?>
                <!--
                你的订单已预约<?php $reserve_date = explode(' ', $orders['reserve_date'] ); echo $reserve_date[0]; ?>发货
                -->
            <?php elseif( $orders['status'] ==  $ConsumerShippingModel::STATUS_HOLDING  ): ?>
               <?php echo $lang->line('leak_good_tip'); ?>
            <?php elseif( $orders['status'] ==  $ConsumerShippingModel::STATUS_APPLY  ): ?>
                <?php echo $lang->line('orders_received'); ?>
            <?php endif; ?>
                <!-- <?php if( $orders['reserve_date'] ): echo '你的订单已预约'.$orders['reserve_date'].'发货';else : echo '你的商品已发货'; endif; ?> -->
            </span>
        <?php if( $orders['reserve_date'] ): ?>
            <!-- <div>
                <span>发货时间:</span>
                <span class="j_deliverGoods_time"><?php echo $orders['reserve_date']?></span>
            </div> -->
        <?php endif; ?>
    </li>
    <li>
        <span class="h32"><?php echo $lang->line('delivery_status'); ?>:</span>
        <div><span><?php echo $lang->line('receive_orders'); ?></span><?php echo $orders['create_time']?></div>
        <?php if(!empty($orders['post_time'])): ?>
        <div><span><?php echo $lang->line('shipped'); ?></span><?php echo $orders['post_time']?></div>
            <?php endif;?>
    </li>
    <?php if( $orders['status'] == $ConsumerShippingModel::STATUS_SHIPPED ||  $orders['status'] == $ConsumerShippingModel::STATUS_FINISHED ): ?>
    <li>
        <span class="h32"><?php echo $lang->line('delivery_info'); ?>:</span>
        <div><span><?php echo $lang->line('couier_company'); ?></span><?php echo $orders['distributor']?></div>
        <div><span><?php echo $lang->line('waybill_num'); ?></span><?php echo $orders['tracking_no']?></div>
    </li>
    <?php if(isset($shippingTrack) && !empty($shippingTrack)): ?>
    <li style="padding-top:0">
    	<ul class="list_style logistics">
        <?php foreach($shippingTrack as $list):?>
           <li class="color_888"><p><?php echo $list['datetime']?></p><p><?php echo $list['remark']?></p></li>
        <?php endforeach;?>
    	</ul>
    </li>
        <?php endif;?>
    <?php endif; ?>
</ul>
<div class="color_link pad10 center"><?php echo $lang->line('receipient_change_notice'); ?></div>
<!-- <div class="j_OtherUser p_2_0 bg_fff m_t_3">其他用户还看了 </div> -->
<!-- <div class="tp_list bg_fff">
    <a href="tp_detail.html" class="item">
        <div class="img">
            <img src="images/img/eg2.jpg" />
            <div class="fn"><span>可赠好友</span><span>其他其他</span></div>
        </div>
        <p class="txtclip">齐云山赏花祈福套餐＋齐云山门票2张黄山碧桂园酒店+齐云山赏花祈福套餐＋齐云山门票2张黄山碧桂园酒店</p>
        <div class="foot">
            <p class="tp_local">黄山</p>
            <p class="tp_price h2 color_main y qi">888</p>
        </div>
    </a>
    <a href="tp_detail.html" class="item">
        <div class="img"><img src="images/img/eg2.jpg" /></div>
        <p class="txtclip">齐云山赏花祈福套餐＋齐云山门票2张黄山碧桂园酒店+齐云山赏花祈福套餐＋齐云山门票2张黄山碧桂园酒店</p>
        <div class="foot">
            <p class="tp_local">黄山</p>
            <p class="tp_price h2 color_main y qi">888</p>
        </div>
    </a>
</div> -->
<!-- 推荐位  -->
<?php echo isset($block) ? $block: '';?>
<!-- 推荐位  -->
<div class="j_t_flex ui_pull">
    <div class="j_modify_time">
        <div class="b_Radio j_time_btn">
            <label>
                <input type="radio" <?php if( !$orders['reserve_date'] ): ?>checked<?php endif; ?> name="radio">
                <div><?php echo $lang->line('ship_now'); ?></div>
            </label><br>
            <label>
                <input type="radio" <?php if( $orders['reserve_date'] ): ?>checked<?php endif; ?> name="radio">
                <div class="in_block"><?php echo $lang->line('make_appointment'); ?></div>
                <input class="j_bt_time" type="text" name="datetime" id="test" readonly style="width:6rem;">
            </label>
            <input class="j_bt_time" type="hidden" name="bsn" id="bsn" value="<?php echo $bsn; ?>" readonly style="width:6rem;">
            <input class="j_bt_time" type="hidden" name="spid" id="spid" value="<?php echo $spid; ?>" readonly style="width:6rem;">
        </div>
        <div class="j_btn_list">
            <span class="j_cancel"><?php echo $lang->line('cancel'); ?></span>
            <span class="j_confirm"><?php echo $lang->line('confirm'); ?></span>
        </div>
    </div>
</div>
<script>
    //<input class="j_bt_time" type="text" name="test" id="test" readonly style="width:6rem;">
    var nowData=new Date();
    nowData = new Date(nowData.valueOf() + 1*24*60*60*1000);
    var year = <?php echo $year; ?>;
    var month = <?php echo $month; ?>;
    var date = <?php echo $date; ?>;
    var time = '';
    if( year != '' && month != '' & date != '' ){
        month = month - 1;//js 时间月份会自动加一
        // date = date - 5;//离过期时间还有5天
        time = new Date(year,month,date);
        // alert( time );return ;
    }else{

        time = new Date(nowData.getFullYear(),nowData.getMonth(),nowData.getDate()+7,22,00);
    }
    var opt= {
        theme:'ios', //设置显示主题
        mode:'scroller', //设置日期选择方式，这里用滚动
        display:'bottom', //设置控件出现方式及样式
        preset : 'date', //日期:年 月 日 时 分
        minDate: nowData,
        maxDate: time,
        stepMinute: 5, //设置分钟步长
        yearText:"<?php echo $lang->line('year'); ?>",
        monthText:"<?php echo $lang->line('month'); ?>",
        dayText:"<?php echo $lang->line('day'); ?>",
        lang:'zh' //设置控件语言};
    };
    $('#test').mobiscroll(opt);
    $('.j_modify').click(function(){
        $('.j_t_flex').fadeIn();
    })
    $('.j_cancel').click(function(){
        $('.j_t_flex').fadeOut();
    })
    var _date=new Date();
    var _j_time=_date.getFullYear()+"-"+(_date.getMonth()+1)+"-"+_date.getDate();
    $('.j_confirm').click(function(){
        var _modeGoods=$('.j_time_btn label input:checked+div').html();
        var _txt=$('.j_bt_time').val();

        var spid = $("#spid").val();
        var bsn = $("#bsn").val();

        //发送ajax到后台修改
        $.ajax({
            type: 'POST',
            url: "<?php echo $edit_url; ?>",
            data: {datetime:_txt,spid:spid,bsn:bsn},
            dataType: 'json',
            success:function(json){
                location.reload();
            }
        });

        if(_modeGoods=="<?php echo $lang->line('ship_now'); ?>"){
            $('.j_deliverGoods_time').html(_j_time);
        }else{
            if(_txt!=''){
                $('.j_deliverGoods_time').html(_txt);
            }
        }
        $('.j_mode').html(_modeGoods);
        $('.j_t_flex').fadeOut();

    })
</script>
</body>
</html>

