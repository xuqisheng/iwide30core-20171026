<?php echo referurl('css','swiper.css',1,$media_path) ?>
<?php echo referurl('js','swiper.js',1,$media_path) ?>
<?php include 'header.php' ?>   
    <section class="gradient_bg">
        <div class="wrapper order_details_wrap">
            <div class="relative overflow pad_t20 pad_l60 pad_b80">
            	<div class="shadow_r"></div>
                 <?php if($order['status']==0 || $order['status']==1){ ?>
                <p class="main_color1 iconfont h60 mar_b20"><?php echo $order['status_des'];?></p>
                <?php }else if($order['status']==9) { ?>
                <p class="color1 iconfont h60 mar_b20"><?php echo $order['status_des'];?></p>
                <?php } else { ?>
                <p class="color3 iconfont h60 mar_b20"><?php echo $order['status_des'];?></p>
                <?php } ?>
            	<?php if ($states['re_pay']==1){?>
                	<p class="order_payment_time color3 h26 mar_b60">支付倒计时 <span class="main_color4 h30 time_out" last_repay_time="<?php echo $states['last_repay_time']?>"></span></p>
                    <a class="button w80" href="<?php echo $states['repay_url']?>">
                        <span class="color1 h34 iconfont">立即支付</span>
                        <span class="h26 color2 mar_l10">￥<?php echo $order['paytype']=='point'?0:$order['price'];?></span>
                    </a>
                <?php }?>
            		<p class="order_payment_word color3 h26 mar_b60"><?php echo $states['status_tips'];?></p>
                	<span class="color3 h26" >您可以 :</span>
                	<p class="h26" style="display: inline-block;">
                		<?php if($states['can_cancel']==1){?>
                    		<span class="color1 order_button h28" onclick="cancel_order()">取消订单</span>
                    	<?php }elseif($states['can_comment']==1){ ?>
                            <a class="color1 order_button h28" href="<?php echo Hotel_base::inst()->get_url("TO_COMMENT",array('orderid'=>$order['orderid']));?>">评论</a>
                        <?php }?>
                    	<?php if ($states['self_checkout']==1){?>
                    		<a class="color1 order_button h28"  href="<?php echo Hotel_base::inst()->get_url("CHECK_OUT",array('oid'=>$order['id']));?>"><?php echo $states['self_checkout_des'];?></a>
                    	<?php }?>
                    	<a class="color1 order_button h28" href="<?php echo Hotel_base::inst()->get_url("INDEX",array('h'=>$order['hotel_id'],'type'=>$order['price_type']));?>">再次预定</a>
                	</p>
            </div> 
            <div class="layer_bg border_radius mar_b80 clearfix">
                <div class="img_bg_1 webkitbox pad_tb60 pad_l40 boxflex">
                    <div class="pad_r20" style="width:calc(100% - 90px);">
                        <p class="color1 h34 txtclip mar_b40"><?php echo $order['hname']?></p>
                        <p class="color2 h24">
                            <em class="coordinate_ico iconfont mar_r10">&#Xe002;</em>
                            <span><?php echo $order['haddress'];?></span> 
                        </p>
                    </div> 
                    <a href="javascript:tonavigate(<?php echo $order['latitude'];?>,<?php echo $order['longitude'];?>,'<?php echo $order['hname'];?>','<?php echo $order['haddress'];?>')" class="main_color1 bd_left getline">
                        <em class="iconfont light_ico h48">&#Xe001;</em>
                        <p class="h24">导 航</p>
                    </a> 
                </div>
                <div class="order_information webkitbox flexjustify">
                    <div class="h28 color1"><?php echo $order['first_detail']['roomname'];?>-<?php echo $order['first_detail']['price_code_name'];?></div>
                    <div class="h24 color2"><?php echo $order['roomnums'] ?> 间</div> 
                </div>
                <div class="order_information center bd webkitbox">
                    <div class="txt_l" style="    width: 35%;">
                        <p class="color3 h24 mar_b10">入 住</p>
                        <p class="color1 h32 mar_b10"><?php echo date('y/m/d',strtotime($order['startdate']));?></p>
                        <p class="color2 h24"><?php echo $startdate_weekday; ?></p>
                    </div>
                    <div class="main_shadow_wrap">
                    </div>
                    <div class="txt_l">
                        <p class="color3 h24 mar_b10">离 店</p>
                        <p class="color1 h32 mar_b10"><?php echo date('y/m/d',strtotime($order['enddate']));?></p>
                        <p class="color2 h24"><?php echo $enddate_weekday; ?></p>
                    </div>
                </div>
                <div class="order_information clearfix">
                    <p class="color2 h28"><span><?php echo $order['name']; ?></span><span class="mar_l30"><?php echo $order['tel']; ?></span></p>
                </div>
            </div>
            <?php if (!empty($order['goods_details'])){?>
            <div class="mar_b80">
                <p class="color3 h24 mar_b20">包含商品</p>
                <?php foreach ($order['goods_details'] as $k=>$go){?>
                <div class="commodity_rows border_radius layer_bg mar_b30">
                    <div class="commodity_img_wrap">
                       <div class="img">
                           <?php if(isset($go['goods_info']['external_info']['intro_img']) && !empty($go['goods_info']['external_info']['intro_img'])){ ?>
                           <div class="squareimg">
                                <img src="<?php echo $go['goods_info']['external_info']['intro_img'];?>" alt="">
                           </div>
                           <?php }else{ ?>
                           <span class="default_translate_img"></span>
                           <?php } ?>
                       </div> 
                       <div class="commodity_img_wrap_zhe"></div>
                    </div>
                    <div class="commodity_img_content">
                        <p class="color1 h32 commodity_rosw_title"><?php echo $go['goods_name'];?></p>
                        <div class="mar_tb30" style="margin-left: -18px;">
                            <span class="main_color1 h30 iconfont">&#xFFE5;</span>
                            <span class="main_color1 h36 mar_r10 iconfont"><?php echo $go['oprice'];?></span>
                            <span class="color2 h24"><?php echo $go['nums'];?><?php if(isset($go['goods_info']['unit']))echo $go['goods_info']['unit'];else echo '份';?></span>
                        </div>
                        <div>
                            <span class="color3 commodity_details_ico h26 commodity_details_ico" style="padding-top: .8rem;">详情 <i class="iconfont package_detail_ico">&#Xe013;</i></span>
                            <?php if (isset($states['goods_state'][$k])&&$states['goods_state'][$k]['usable']==1){?>
                            <a id="j-use" class="button xs floatr h30" href="<?php echo $states['goods_state'][$k]['use_link']?>">立即使用</a>
                            <?php }?>
                        </div> 
                    </div>
                    <div class="clear bd_top pad_lr40 pad_tb40 package_detail">
                        <div class="color2 h28 mar_b20"><?php echo $go['goods_name'];?></div>
                        <div class="lineheight color3 h24"><?php echo $go['goods_info']['short_intro'];?>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
            <?php }?>
            <div class="pad_b20 mar_b30 relative">
                <p class="color3 h24 mar_b20">订单信息</p>
                <div class="color2">
                    <div class="webkitbox mar_b30">
                        <p class="word_list_left color2 h28">订单编号</p>
                        <p class="color1 h30 from_list_word"><?php echo $order['show_orderid'];?></p>
                    </div>
                    <div class="webkitbox mar_b30">
                        <p class="word_list_left color2 h28">支付类型</p>
                        <p class="color1 h30 from_list_word"><?php echo $pay_ways[$order['paytype']]->pay_name;?></p>
                    </div>
                    <div class="webkitbox mar_b30">
                        <p class="word_list_left color2 h28">下单时间</p>
                        <p class="color1 h30 from_list_word"><?php echo date('y/m/d H:i:s',$order['order_time']);?></p>
                    </div>
                    <div class="webkitbox mar_b30">
                        <p class="word_list_left color2 h28">优 惠 券</p>
                        <p class="color1 h30 from_list_word"><?php echo $order['coupon_favour'];?></p>
                    </div>
                    <div class="webkitbox mar_b30">
                        <p class="word_list_left color2 h28">使用积分</p>
                        <p class="color1 h30 from_list_word"><?php echo round($order['point_used_amount']);?></p>
                    </div>
                    <div class="webkitbox mar_b30">
                        <p class="word_list_left color2 h28">积&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;分</p>
                        <p class="color1 h30 from_list_word"><?php echo $order['point_favour'];?></p>
                    </div>
                    <?php if($order['wxpay_favour']>0){ ?>
                        <div class="webkitbox">
                            <p class="word_list_left color2 h28">支付立减</p>
                            <p class="color1 h30 from_list_word"><?php echo $order['wxpay_favour'];?>元</p>
                        </div>
                    <?php }?>
                    <div class="webkitbox mar_b30">
                        <p class="word_list_left color2 h28">房 间 数</p>
                        <p class="color1 h30 from_list_word"><?php echo $order['roomnums'];?>间</p>
                    </div>
                    <div class="webkitbox mar_b30">
                        <p class="word_list_left color2 h28">实付总额</p>
                        <p class="color1 h30 from_list_word"><?php echo $order['paytype']=='point'?0:$order['price'];?></p>
                    </div>
                    <div class="webkitbox mar_b30">
                        <p class="word_list_left color2 h28">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</p>
                        <p class="color1 h30 from_list_word"><?php echo $order['customer_remark'];?></p>
                    </div>
                </div>
            </div>
            <div class="webkitbox webkittop color3 bd_top pad_tb40">
                <div class="iconfont h28 mar_r10">&#xe007;</div>
                <div class="h24 book_policy">
                    <div class="color2 mar_b20 h24">预定说明</div>
                    <div class="lineheight">
                        <?php echo nl2br($hotel['book_policy']);?>
                    </div>
                </div>
            </div>
            <!-- <div class="pad_b20 pad_t40 mar_b30 bd_top relative">
                <p class="color3 h24 mar_b20">相关推荐</p>
                <div class="formlist color2 order_recommend_wrap swiper-container">
                    <div class="swiper-wrapper">     
                        <div class="order_recommend color1_bg swiper-slide mar_r30">
                            <div class="squareimg">
                                <img src="/public/hotel/bigger/images/hotel_1.png" alt="">
                           </div>
                           <div class="pad_lr30 pad_b20">
                                <p class="color4 h28 mar_t20">通用温泉票</p>
                                <div class="mar_t20">
                                    <span class="main_color1 iconfont mar_r5">&#xFFE5;<tt class="h32">678</tt></span>
                                    <span class="color2"><del class="h24"><tt class="h20 iconfont">&#xFFE5;</tt>888</del></span>
                                </div>
                            </div> 
                        </div>
                        <div class="order_recommend color1_bg swiper-slide mar_r30">
                            <div class="squareimg">
                                <img src="/public/hotel/bigger/images/hotel_1.png" alt="">
                           </div>
                           <div class="pad_lr30 pad_b20">
                                <p class="color4 h28 mar_t20">通用温泉票</p>
                                <div class="mar_t20">
                                    <span class="main_color1 iconfont mar_r5">&#xFFE5;<tt class="h32">678</tt></span>
                                    <span class="color2"><del class="h24"><tt class="h20 iconfont">&#xFFE5;</tt>888</del></span>
                                </div>
                            </div> 
                        </div>
                        <div class="order_recommend color1_bg swiper-slide mar_r30">
                            <div class="squareimg">
                                <img src="/public/hotel/bigger/images/hotel_1.png" alt="">
                           </div>
                           <div class="pad_lr30 pad_b20">
                                <p class="color4 h28 mar_t20">通用温泉票</p>
                                <div class="mar_t20">
                                    <span class="main_color1 iconfont mar_r5">&#xFFE5;<tt class="h32">678</tt></span>
                                    <span class="color2"><del class="h24"><tt class="h20 iconfont">&#xFFE5;</tt>888</del></span>
                                </div>
                            </div> 
                        </div>
                        <div class="order_recommend color1_bg swiper-slide mar_r30">
                            <div class="squareimg">
                                <img src="/public/hotel/bigger/images/hotel_1.png" alt="">
                           </div>
                           <div class="pad_lr30 pad_b20">
                                <p class="color4 h28 mar_t20">通用温泉票</p>
                                <div class="mar_t20">
                                    <span class="main_color1 iconfont mar_r5">&#xFFE5;<tt class="h32">678</tt></span>
                                    <span class="color2"><del class="h24"><tt class="h20 iconfont">&#xFFE5;</tt>888</del></span>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div> -->
        </div> 
        <?php include 'footer.php' ?>

    </section>  
</body>
<script type="text/javascript">
    function cancel_order(){
    	var _msg;
    	<?php if ($inter_id=='a472731996'){?>
    	_msg='确认取消该订单吗？如预付房费退订需提前一天，退款在15个工作日内完成，收取20%手续费，详询客服';
    	<?php }else{?>
    	_msg='您确定要取消吗？';
    	<?php }?>
        $.MsgBox.Confirm(_msg,function(){
    		pageloading();
    		$.get('<?php echo Hotel_base::inst()->get_url("CANCEL_MAIN_ORDER");?>',{
    			oid:'<?php echo $order['orderid']?>'
    		},function(data){
    			removeload();
    			if(data.s==1){
                    $.MsgBox.Alert(data.errmsg);
    				location.reload();
    			}
    			else{
                    $.MsgBox.Alert(data.errmsg);
    			}
    		},'json');
        })
    }
    var swiper = new Swiper('.order_recommend_wrap', {
           slidesPerView: 'auto'
           // slidesPerView: 3.5,
        // paginationClickable: true,
        // centeredSlides:true,
    });
    $(".commodity_details_ico").on("click",function(){
        $(this).parents(".commodity_rows").toggleClass("package_detail_show")
    });

    var N =function(num){
            if(num<10) return '0'+num;
            else return num;
        }
    var time;
    time = setInterval(function(){
        $(".time_out").each(function(){
            var newtime = new Date(getNowFormatDate()),
                oldtime = new Date($(this).attr("last_repay_time")),
                s1 = newtime.getTime(),
                s2 = oldtime.getTime(),
                total = (s2 - s1)/1000,
                day = parseInt(total / (24*60*60)),//计算整数天数
                afterDay = total - day*24*60*60,//取得算出天数后剩余的秒数
                hour = parseInt(afterDay/(60*60)),//计算整数小时数
                afterHour = total - day*24*60*60 - hour*60*60,//取得算出小时数后剩余的秒数
                min = parseInt(afterHour/60),//计算整数分
                afterMin = total - day*24*60*60 - hour*60*60 - min*60;//取得算出分后剩余的秒数
                if(total < 0){return false;}
                $(this).html(N(hour)+":"+N(min)+":"+N(afterMin))
        })
    },1000)
</script>
</html>
