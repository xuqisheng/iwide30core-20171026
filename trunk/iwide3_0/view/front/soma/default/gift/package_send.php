<div class="pageloading"><p class="isload"><?php echo $lang->line('loading'); ?></p></div>

<script src="<?php echo get_cdn_url('public/soma/scripts/imgscroll.js');?>"></script>
<script src="<?php echo get_cdn_url('public/soma/scripts/jquery.touchwipe.min.js');?>"></script>
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
	<?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>

	<?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
	 $('.send_btn').removeClass('disable');
});
</script>

<!-- 以上为head -->

<?php if( !$is_group || $is_group==Soma_base::STATUS_FALSE ): 
//-----------单个赠送部分----------- ?>

    <!--div class="whiteblock webkitbox justify bd" style="margin-top:0">
        <span>赠送内容</span>
        <span></span>
    </div-->
    <a class="orderimg">
        <div class="goodsimg"><div class="squareimg"><img src="<?php echo $items[0]['face_img']; ?>" /></div></div>
        <div>
            <p class="h30"><?php echo $items[0]['name']; ?></p>
            <p class="color_888"><?php echo $items[0]['hotel_name']; ?></p>
            <!--p class="color_main">¥<?php echo $items[0]['price_package']; ?> </p-->
        </div>
    </a>

<?php else: 
//-----------群发部分----------- ?>

    <div class="whiteblock webkitbox justify bd" style="margin-top:0">
        <span><?php echo $lang->line('gift_name'); ?></span>
        <span><?php echo $items[0]['name']; ?></span>
    </div>
    <div class="whiteblock webkitbox justify bd">
        <span><?php echo $lang->line('recipient_numbers'); ?></span>
        <span style="text-align:left;width:40%">
            <span style="display:inline-block">
                <div class="num_control bd webkitbox">
                    <div class="down_num bd_left">-</div>
                    <div class="result_num bd_left"><input name="count_give" id="peopleNumber" value="1" type="tel" min="1" max="<?php echo $items[0]['qty']; ?>"></div>
                    <div class="up_num bd_lr">+</div>
                </div>
            </span><?php if($langDir == 'chinese') echo '人'; ?>
        </span>
    </div>
    <div class="whiteblock webkitbox justify bd">
        <span><?php echo $lang->line('number_of_gifts'); ?></span>
        <span style="text-align:left;width:40%">
            <span style="display:inline-block;">
                <div class="num_control bd webkitbox">
                    <div class="down_num bd_left">-</div>
                    <div class="result_num bd_left"><input name="per_give" id="sendNumber" value="1" type="tel" min="1" max="<?php echo $items[0]['qty']; ?>"></div>
                    <div class="up_num bd_lr">+</div>
                </div>
            </span><?php if($langDir == 'chinese') echo '份/人'; ?>
        </span>
    </div>
    <div class="color_888 count_num">
        <?php $num = $items[0]['qty']-$send_pertime; if( $num >= 0 ): ?>
        <?php
            $translate_tpl = $lang->line('send_voucher_tip');
            $translate_tpl = str_replace('[0]', $send_pertime, $translate_tpl);
            $translate = str_replace('[1]', $num, $translate_tpl);
        ?>
        <span id="giftNumber"><?php echo $translate; ?></span>
        <!-- <span id="giftNumber">送出<?php echo $send_pertime; ?>份，剩余<?php echo $num;?>份</span> -->
        <?php endif;?>
        <span class="tips" style="display:none;"><em class="bg_key">!</em><?php echo $lang->line('short_gifts_tip'); ?></span>
    </div>
    
<?php endif; //-----------顶部内容结束----------- ?>


<div class="whiteblock bd">
    <textarea id="get_txt" placeholder="<?php 
        if( isset( $js_share_config['desc'] ) && !empty( $js_share_config['desc'] ) )
            echo $js_share_config["desc"];
        else echo $lang->line('happy_life'); ?>" maxlength="20" rows="3" style="width:100%;"></textarea>
    <p class="color_888" style="text-align:right" id="font_num">20/20</p>
</div>
<div class="list_style martop bd" id="select_theme">
    <div class="webkitbox justify arrow">
        <span><?php echo $lang->line('select_theme'); ?></span>
        <span class="color_888" id="theme_name"><?php echo $lang->line('featured'); ?></span>
    </div>
</div>
<div class="martop center">
    <input type="hidden" name="tid" value="1" id="sendTheme" />
    <input type="hidden" name="is_group" value="<?php echo $is_group;?>" id="isGroup" />
    <div class="btn_main send_btn disable"><?php echo $lang->line('confirm_gift'); ?></div>
</div>
<a href="<?php echo Soma_const_url::inst()->get_soma_order_list(array('id'=>$inter_id)); ?>">
    <div class="center pad3 color_link h24" style="text-decoration:underline"><?php echo $lang->line('gift_later_tip'); ?></div>
</a>
<div class="ui_pull share_pull" style="display:none"></div>

<!-- 主题 -->

<style>
.preview_theme.theme50{<?php 
/*颜色*/
if( isset( $themeConfig['receive_main_color'] ) && !empty( $themeConfig['receive_main_color'] ) ){
	echo 'color:'.$themeConfig['receive_main_color'].';';
}
/*背景图*/
if( isset( $themeConfig['receive_bg'] ) && !empty( $themeConfig['receive_bg'] ) ){
    //效果图
	echo 'background-image:url('.$themeConfig['receive_bg'].');';
} 
?>}
</style>
<div class="ui_pull theme_pull" style="background:#f3f4f8; display:none">
    <div class="theme_box">
        <div class="headerslide">
            <?php foreach( $giftTheme as $k=>$v ):?>
            <div class="slideson">
                <img src="<?php 
                                //预览图
                                if( $v['theme_id'] == Soma_base::STATUS_TRUE && isset( $themeConfig['receive_preview_bg'] ) && !empty( $themeConfig['receive_preview_bg'] ) ) 
                                    echo $themeConfig['receive_preview_bg'];
                                else 
                                    echo get_cdn_url('public/soma/images/gift_send_theme/'.$v['theme'] );
                                ?>" />
                <p class="themefoot"><em class="btn_img h24"><?php echo $v['theme_name'];?></em></p>
            </div>
            <?php endforeach;?>
            
        </div>
        <p class="choose iconfont color_main">&#xe61e;</p>
    </div>
    
    <div class="preview_theme theme0" style="display:none;">
        <div class="relative themeparent scroll">
            <div class="themechid1">
                <div class="squareimg">
                    <img src="<?php 
                        if( isset( $items[0]['transparent_img'] ) && !empty( $items[0]['transparent_img'] ) ) 
                            echo $items[0]['transparent_img'];
                        else 
                            echo $items[0]['face_img']; ?>" 
                        title="<?php echo $items[0]['transparent_img']; ?>" />
                </div>
            </div>
            <div class="themechid2">
                <p><img src="<?php echo isset( $fans['headimgurl'] ) ? $fans['headimgurl'] : get_cdn_url('public/soma/images/ucenter_headimg.jpg'); ?>"></p><!--  用户头像 -->
                <p><span><?php if($nickname) echo $nickname;else echo $lang->line('friends');?></span></p>
                <p id="fill_txt"><?php echo $lang->line('happy_life'); ?></p>
            </div>
            <div class="themechid3"><?php echo $items[0]['name']; ?></div>
        </div>
    </div>
    <!--div class="preview_theme theme0" style="display:none">
        <div class="relative themeparent scroll">
            <div class="themechid1 center">
                <p><span>您的好友</span>送您一份中秋心意</p>
                <p>恭祝您：中秋快乐！</p>
            </div>
            <div class="themechid2"><div class="squareimg"><img src="<?php echo $items[0]['face_img']; ?>" /></div></div>
            <div class="center">
                <p>已使用0/份</p>
            </div>
            <div class="webkitbox themechid4">
                <div><a href="" class="btn_void h24">邮寄到家</a></div>
                <div><a href="" class="btn_void h24">送给朋友</a></div>
                <div><a href="" class="btn_void h24">到店用劵</a></div>
            </div>
            <a class="themechid3" id="addUrl" >暂不使用，放至订单中心<em class="iconfont">&#xe61B;</em></a>
        </div>
    </div-->
    <div class="pull_foot bd_top_img bg_F8F8F8 webkitbox">
        <div><span class="btn_void" id="preview"><?php echo $lang->line('preview'); ?></span></div>
        <div><span class="btn_main" id="sure_btn"><?php echo $lang->line('confirm'); ?></span></div>
    </div>
</div>
<script>
var first_load = true;
var theme_index= 0;
var count_give,per_give,total,old_number;

var gid_lock= false;
var redirectUrl = '<?php 
$redirect= urlencode( Soma_const_url::inst()->get_url('*/*/package_list_send', array('id'=> $this->inter_id) ) ); 
echo Soma_const_url::inst()->get_url('*/*/package_sending', array('id'=> $this->inter_id, 'redirect'=> $redirect ) );
?>';

old_number = <?php echo $items[0]['qty']; ?>;

function getval(){
//检查赠送份数是否为收礼人数的整数倍
	count_give = $("#peopleNumber").val()? $("#peopleNumber").val(): 1;
	per_give = $("#sendNumber").val()? $("#sendNumber").val(): 1;
	total= count_give * per_give;
}

function checkNum(){
	getval();
    if( total > old_number ){
        $(".tips").show();
        $("#giftNumber").hide();
    }else{
        $(".tips").hide();
        $("#giftNumber").html("<?php echo $lang->line('send_out'); ?>"+total+ "<?php echo $lang->line('copies'); ?>" + '，' + "<?php echo $lang->line('remain'); ?>" +(old_number-total)+"<?php echo $lang->line('copies'); ?>");
        $("#giftNumber").show();
    }
}

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
                leftLink = "<?php echo $lang->line('continue_use'); ?>";
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

function submit_ajax(){
	getval();
    var sendTheme = $("#sendTheme").val();
    var is_group = $("#isGroup").val();
    var msg = $('textarea').val();
	var aiids= {'<?php echo $items[0]['item_id']; ?>':total};
    pageloading("<?php echo $lang->line('packing'); ?>",0.2);
    $.ajax({
    	 type: 'POST',
    	 url: '<?php echo Soma_const_url::inst()->get_url("*/*/package_send_ajax"); ?>',
    	 data: {
    		 aiids: aiids,
    		 bsn: 'package',
    		 msg: msg,
    		 tid: sendTheme,
    		 count_give: count_give,
    		 per_give: per_give,
    		 is_group: is_group,
             send_from: <?php echo $send_from; ?>,
             send_order_id: <?php echo $send_order_id; ?>
    	 },
    	 success: function(json){
    		 gid_lock= true;
    		 $('.pageloading').remove();
    		 console.log(json)
    		 if(json.status == 1){
    			 //$.MsgBox.Confirm(json.message);
    			 //location.href = redirectUrl;
    			 var gid= json.data;
    			 var sign= json.sign;
    			 if( !gid ) {
    				 $.MsgBox.Confirm("<?php echo $lang->line('network_error_tip'); ?>",null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancel'); ?>");
    				 
    			 } else {
    				 //console.log(gid)
    				 var share_url= '<?php echo $js_share_config["link"]?>&gid='+ gid+ '&sign='+ sign;
    				 console.log(share_url)
    				 <?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>
    				 <?php if( $js_share_config ): ?>
    				wx.onMenuShareTimeline({
    					title: '<?php echo $js_share_config["title"]?>',
    					link: share_url,
    					imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
    					success: function () {
    						location.href = redirectUrl+ '&gid='+ gid+ '&sign='+ sign;
    					},
    					cancel: function () {}
    				});
    				wx.onMenuShareAppMessage({
    					title: '<?php echo $js_share_config["title"]?>',
    					// desc: '<?php echo $js_share_config["desc"]?>',
                        desc: json.desc,            
    					link: share_url, 
    					imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
    					//type: '', //music|video|link(default)
    					//dataUrl: '', //use in music|video
    					success: function () {
    						location.href = redirectUrl+ '&gid='+ gid+ '&sign='+ sign;
    					 },
    					cancel: function () {}
    				});
    				 <?php endif; ?>
    				 $('.share_pull').show();
    			 }
    		 } else if(json.status == 2){
    			 $.MsgBox.Confirm(json.message,null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancel'); ?>");
    			 return false;
    		 }
    	} ,
    	dataType: 'json'
    });
}
$(document).ready(function() {
    $('#preview').click(function(){
        $('.preview_theme').removeClass().addClass('preview_theme theme'+theme_index).show();
    })
    $('.preview_theme').click(function(){
        $('.preview_theme').hide();
    })
    $('#sure_btn').click(function(){
        toclose();
        if( gid_lock ){
            toshow($('.share_pull'));
            
        } else {
        	var val = $('.slideson').eq(theme_index).text();
            $('#theme_name').html(val);
            $('#sendTheme').val(theme_index+1);
        }
    })
    $('#select_theme').click(function(){
        toshow($('.theme_pull'));
		if( $('#get_txt').val()!=''){
			$('#fill_txt').html($('#get_txt').val());
		}else{
			$('#fill_txt').html($('#get_txt').attr('placeholder'));
		}
        if(first_load){
            first_load=false;
			var imgrate  = 480/770;
			var _h = $(window).height()-100;
			$('.theme_box').css('max-width',_h*imgrate);
            $.fn.imgscroll({
                imgrate     :imgrate,       
                partent_div :'theme_box', 
                circleshow  :false,
                speed       :200,
                delay       :3,
                autowipe    :false,
                overflow    :'visible',
                imgpadding  :'0 5px',
                callback    :function(data){
                    theme_index=data.index;
					console.log(data.index)
                }
            });
            $('.slideson').click(function(){
                $('.preview_theme').removeClass().addClass('preview_theme theme'+theme_index).show();
            })
			$('.theme_box').on('touchstart',function(){
				$('.theme_pull .choose').stop().hide();
			})
			$('.theme_box').on('touchend',function(){
				$('.theme_pull .choose').stop().fadeIn();
			})
        }
    });
    $('textarea').bind('input propertychange',function(){
        var length = parseInt($(this).val().length);
        var maxlength = parseInt($(this).attr('maxlength'));
        var html = maxlength-length;
		if( html<=0) html=0;
        html += '/'+maxlength;
        $('#font_num').html(html);
    });
    $('.share_pull').click(function(){$(this).hide();});
    
    //检查赠送数量是否超过总数
    $(".down_num").click(function(){
        checkNum();
    });
    $(".up_num").click(function(){
        checkNum();
    });
	$('.result_num').blur(function(){
		if (isNaN($(this).val()))
			$(this).val('1');
		if($(this).val()*1>$(this).attr('max')*1)
			$(this).val($(this).attr('max'));
        checkNum();
	})
    $("#checkFollow").click(function(){
        checkFollow();
        return;
    });

    //确定赠送
    $('.send_btn').click(function(){
		if($(this).hasClass('disable')){
			$.MsgBox.Confirm("<?php echo $lang->line('wait_loading_tip'); ?>",null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancel'); ?>");
			return;
		}
        count_give = $("#peopleNumber").val()? $("#peopleNumber").val(): 1;
        per_give = $("#sendNumber").val()? $("#sendNumber").val(): 1;
        total= count_give * per_give;

        if( total > old_number ){
            $.MsgBox.Confirm( "<?php echo $lang->line('not_enough_tip'); ?>",null,null,"<?php echo $lang->line('ok'); ?>","<?php echo $lang->line('cancel'); ?>" );
            return false;
        }
        var msg = $('textarea').val();
        if( msg ==''){
            $('textarea').val($('textarea').attr('placeholder'));
            msg = $('textarea').attr('placeholder');
        }
        if( gid_lock ){
            toshow($('.share_pull'));
        } else {
            if( $("#peopleNumber").val() ){
                var alert_msg = "<?php echo $lang->line('gift_send_stat_tip'); ?>";
                alert_msg = alert_msg.replace('[0]', total);
                alert_msg = alert_msg.replace('[1]', count_give);
                alert_msg = alert_msg.replace('[2]', per_give);
                // var alert_msg= total+ '份礼品将被'+ count_give+'人领取，每人'+ per_give+ '份，超过24小时未领取将退回';
            } else {
                var alert_msg= "<?php echo $lang->line('gift_packaged_to_friend_tip'); ?>";
            }
			$.MsgBox.Confirm(alert_msg,function(){
				submit_ajax();
			},function(){},"<?php echo $lang->line('confirm'); ?>","<?php echo $lang->line('rewrite'); ?>");			
        }
    });
});
</script>
</body>
</html>
