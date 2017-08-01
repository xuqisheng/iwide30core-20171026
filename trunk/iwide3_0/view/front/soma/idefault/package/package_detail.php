<?php 
    // 是否显示¥符号
    $show_y_flag = true;
    if($package['type'] == $packageModel::PRODUCT_TYPE_POINT)
    {
        $show_y_flag = false;
    }
?>
<style>
    #killsec_btn{
        position: relative;
        line-height: 33px;
    }
    #killsec_btn .mask{
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        display: none;
        background: #999;
        color: #fff;
    }
    #killsec_btn .text{
        display: block;
        margin-top: -5px;
    }
    #killsec_btn.disabled .mask{
        display: block;
    }
    .Ldn{
        display: none;
    }
</style>
<body>
<script src="<?php echo base_url('public/soma/scripts/imgscroll.js');?>"></script>
<script src="<?php echo base_url('public/soma/scripts/jquery.touchwipe.min.js');?>"></script>
<script>
wx.config({
    debug: false,
    appId: '<?php echo $wx_config["appId"]?>',
    timestamp: <?php echo $wx_config["timestamp"]?>,
    nonceStr: '<?php echo $wx_config["nonceStr"]?>',
    signature: '<?php echo $wx_config["signature"]?>',
    jsApiList: [<?php echo $js_api_list; ?>,'getLocation','openLocation']
});
wx.ready(function(){
	<?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
	<?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>

<?php if( $js_share_config ): ?>
      	wx.onMenuShareTimeline({
    	    title: '<?php echo $js_share_config["title"]?>',
    	    link: '<?php echo $js_share_config["link"]?>',
    	    imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
    	    success: function () {},
    	    cancel: function () {}
    	});
    	wx.onMenuShareAppMessage({
    	    title: '<?php echo $js_share_config["title"]?>',
    	    desc: '<?php echo $js_share_config["desc"]?>',
    	    link: '<?php echo $js_share_config["link"]?>', 
    	    imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
    	    //type: '', //music|video|link(default)
    	    //dataUrl: '', //use in music|video
    	    success: function () {},
    	    cancel: function () {}
    	});

<?php endif; ?>
    <?php if($inter_id != 'a455510007'){ //速8需要隐藏 ?>
        document.querySelector('#openLocation').onclick = function () {
            wx.openLocation({
                latitude:<?php echo $package['latitude'];?>,
                longitude:<?php echo $package['longitude'];?>,
                name: '<?php echo $package['hotel_name'];?>',
                address: '<?php echo $package['hotel_address'];?>',
                scale: 14,
                infoUrl: 'http://weixin.qq.com'
            });
        };
    <?php } ?>
});
</script>

<div class="pageloading"><p class="isload">正在加载</p></div>
<header class="headers">
    <div class="headerslide">
        <?php if( $gallery ): ?>
        <?php foreach($gallery as $k=>$v){?>
            <a class="slideson ui_img_auto_cut">
                <img src="<?php echo $v['gry_url'];?>" />
            </a>
        <?php }?>
        <?php else: ?>
            <a class="slideson ui_img_auto_cut">
                <img src="<?php echo base_url('public/soma/images/default.jpg'); ?>" />
            </a>
        <?php endif; ?>
    </div>
    <div class="bn_title webkitbox justify">
        <p class="txtclip color_fff"><?php echo $package['name'];?></p>
<!--        <p class="txtclip" style="text-align:right"><em class="iconfont">&#xE60F;</em>剩余23小时15分</p>-->
        <div><?php if($package['show_sales_cnt'] == Soma_base::STATUS_TRUE): ?>
           已售<?php echo $package['sales_cnt']; ?>
        <?php endif; ?></div>
    </div> 
    <div class="fn">
    <?php if($package['can_gift'] == $packageModel::CAN_T){ ?>
       <span class="bg_main">可赠好友</span>
    <?php } ?>
    <?php if($package['can_split_use'] == $packageModel::CAN_T){ ?>
       <span class="bg_main">分时可用</span>
    <?php } ?>
    </div>
</header>
<div class="whiteblock">
	<div><em class="iconfont color_main">&#xe620;</em>此商品由<?php echo $public['name']; ?>提供</div>
</div>
<?php if( isset( $isTicket ) && !$isTicket ):?>
    <div class="whiteblock select_type" <?php if(!$spec_product): ?> style="display:none" <?php endif; ?>>
        <div class="webkitbox input_item linkblock">
            <?php
                $spec_compose = json_decode($psp_summary['spec_compose'], true);
            ?>
            <div>请选择  <?php echo implode(' ', $spec_compose['spec_type']); ?></div>
            <div class="color_888 h24 result"></div>
        </div>
    </div>
<?php else:?>
    <div class="whiteblock select_type" <?php if(!$spec_product): ?> style="display:none" <?php endif; ?>>
        <a href="<?php echo Soma_const_url::inst()->get_url('*/*/ticket_select_time',array('id'=>$inter_id,'pid'=>$_GET['pid'],'tkid'=>$ticketId)); ?>">
            <div class="webkitbox input_item linkblock">
                <div>请选择门票时间</div>
                <div class="color_888 h24 result"></div>
            </div>
        </a>
    </div>
<?php endif; ?>
<?php if($spec_product): ?>
<div class="ui_pull color_666 page" style="display:none" onClick="toclose();" id="spec_page">
	<div class="flexgrow" style="min-height:40%"></div>
    <div class="bg_fff">
        <div class="flex bd_bottom bg_fff pad10">
            <div class="specimg"><div class="squareimg"><img src="<?php  if( $package['face_img'] )echo $package['face_img'];else echo base_url('public/soma/images/default2.jpg');?>" /></div></div>
            <div class="pad10 flexgrow">

                <?php 
                    $low_price = $psp_setting[$package['product_id']][0]['spec_price'];
                    $high_setting = end($psp_setting[$package['product_id']]);
                    $high_price = $high_setting['spec_price'];
                ?>

                <div class="y color_main specprice"><?php echo $low_price . '~' . $high_price; ?></div>
                <div class="h22 result"></div>
            </div>
            <div class="iconfont h34">&#xe612;</div>
        </div>
    </div>
    <div class="bg_fff _w flexgrow scroll">
        <div class="list_style_1 flexgrow speclist">
            <div class="webkitbox justify pad10 hide">
                <span>购买数量</span>
                <span>
                    <div class="num_control bd webkitbox" style="float:right">
                        <div class="down_num bd_left">-</div>
                        <div class="result_num bd_left"><input id="selece_num" value="1" type="tel" min="1" max="9"></div>
                        <div class="up_num bd_lr">+</div>
                    </div>
                </span>
            </div>
        </div>
    </div>
    <footer class="flex bg_fff bd_top">
    	<div class="flexgrow pad10 center color_999">取消</div>
        <div class="flexgrow sure_btn btn_main pad10 disable">确认</div>
    </footer>
</div>
<?php endif; ?>
<?php /**有秒杀**/ if( !empty($killsec) && isset($killsec['is_stock']) && $killsec['is_stock']==Soma_base::STATUS_TRUE ): ?>
<div class="whiteblock" id="ks_stock_div" style="display: none;">
	<div class="justify webkitbox">
    	<div class="progress"><span class="bg_main fill1" style="width:0<?php //echo $ks_percent; ?>%">&nbsp;<img src="<?php echo base_url('public/soma/images/ruler.png'); ?>"></span></div>
        <div><span class="color_main">剩余名额：</span><span class="color_888 fill2">0<?php //echo $ks_stock; ?>/1<?php //echo $ks_count; ?></span></div>
    </div>
</div>
<script>
var removeBtnMask = false;
function fill_progress(){
	$.post('<?php echo Soma_const_url::inst()->get_url('*/killsec/find_killsec_stock_ajax', array('id'=>$inter_id )); ?>',
			{act_id:'<?php echo $killsec['act_id']; ?>'}, function(json)
	{
	    if( json.status == 1 ){
	    	$('.fill1').animate({width:json.percent+ '%'});	
			$('.fill2').html(json.stock + '/' + json.total);
			$('#ks_stock_div').show();	
	    } else {

	    }
        if(!removeBtnMask){
            $('#killsec_btn').removeClass('disabled');
            removeBtnMask = true;
        }
	},'json');
}
fill_progress();
window.setInterval(fill_progress,<?php echo $stock_reflesh_rate; ?>);
</script>
<?php endif; ?>
<!--div class="bg_fff bd_bottom" style="padding:3%;">
    <p><b class="h2"><?php echo $package['hotel_name'];?></b> <span><!--span id="toshare"><em class="iconfont">&#xE610;</em>分享给好友</span></p>
    <p class="color_main h2">售价:<span class="y"><?php echo $package['price_package'];?></span></p>
    <p><del>门市价:<span class="y"><?php echo $package['price_market'];?></span></del></p>
</div-->
<!--
    <?php
    /**有拼团**/
    if(!empty($groupons)){
            foreach($groupons as $k=>$v){
        ?>
    <div class="bg_fff bd martop" style="padding:3%;">
        <p>
            <span class="color_main martop h2">拼团价:<span class="y"><?php echo $v['group_price'];?></span>
            <span class="color_888 h4">成团人数：<?php echo $v['group_count'];?>人</span>
        </p>
        <div class="buy_link">
            <a href="<?php echo Soma_const_url::inst ()->get_groupon_first_pay(array('act_id'=>$v['act_id'],'id'=>$inter_id));?>">开团购买</a>
        </div>
        <p class="h3">支付后并邀请<?php echo $v['group_count']-1;?>位好友参团，人数不足自动退款</p>
        <p class="h3 color_888">拼团流程<span class="color_main">(什么是拼团？)</span></p>
    </div>
    <?php  break;}}  /** end拼团**/ ?>
-->
<?php
$content = unserialize($package['compose']);
// 商品内容为空时隐藏
$flag = false;
if(is_array($content)) {
    foreach($content as $k=>$v) { 
        if(empty($v['content'])) continue;
        $flag = true;
    }
}

if(!empty($content) && $flag){ ?>
    <div class="bg_fff bd martop block">
        <p class="h30 bd_bottom">商品内容</p>
        <ul class="block_list">
            <li class="color_888 bd_bottom h24"><span>名称</span><span>数量</span></li>
            <?php if(is_array($content)): 
                      foreach($content as $k=>$v): 
                          if(empty($v['content'])) continue;?>
                    <li class="bd_bottom h24 color_555"><span><?php echo $v['content'];?></span><span><?php echo $v['num'];?>&nbsp;&nbsp;</span></li>
            <?php     endforeach; 
                  endif; ?>
        </ul>
    </div>
<?php } ?>
<div class="bg_fff bd martop block ">
    <p class="h30 bd_bottom">订购需知
        <?php /**有秒杀**/ if(!empty($killsec)){ ?>
            <?php if( isset($finish_killsec) && $finish_killsec ){ ?>
            <span class="bg_main bdradius h22 pad2">本轮秒杀已经结束</span>
            <?php } elseif($killsec['killsec_time'] >= date('Y-m-d H:i:s',time())){ ?>
            <span class="bg_main bdradius h22 pad2" id="timeCalc">
                <span class="curdate"></span>
                <span class="curtime"></span>
            </span>
                <script>
                    var oTime = '<?php echo date('Y/m/d H:i:s',strtotime($killsec['killsec_time']));?>';
                    var startFlag = true;
                    /*秒杀倒计时*/
                    function countdownTime(Time){
                        var endTime=new Date(Time);
                        var nowTime=new Date();
                        var s_time=endTime-nowTime;
                        var end_date=parseInt((s_time/1000)/86400);
                        var end_hour=parseInt((s_time/1000)%86400/3600);
                        var end_minute=parseInt((s_time/1000)%3600/60);
                        var end_second=parseInt((s_time/1000)%60);
                        return {
                            date : end_date,
                            hour : end_hour,
                            minute : end_minute,
                            second : end_second,
                            rest  : s_time
                        }
                    }
                    calcStrObj = countdownTime(oTime);

                    if(parseInt(calcStrObj.date) <=0){
                        $('#timeCalc').find('.curdate').html('秒杀开始倒计时：');
                    }else{
                        $('#timeCalc').find('.curdate').html('秒杀开始倒计时：'+ calcStrObj.date+'天');
                    }

                    if(parseInt(calcStrObj.date) <=0 && parseInt(calcStrObj.hour) <=0){
                        $('#timeCalc').find('.curtime').html(calcStrObj.minute+'分'+calcStrObj.second + '秒');
                    }else{
                        $('#timeCalc').find('.curtime').html(calcStrObj.hour+'小时'+ calcStrObj.minute+'分'+calcStrObj.second + '秒');
                    }
                    $('#timeCalc').time=setInterval(function(){
                        if(parseInt(countdownTime(oTime).rest) <= 0 ){
                            startFlag = true;
                            $('#timeCalc').html('秒杀进行中');
                            clearInterval($('#timeCalc').time);
                        }else{
                            startFlag = false;
                        }
                        calcStrObj = countdownTime(oTime);

                        if(parseInt(calcStrObj.date) <=0){
                            $('#timeCalc').find('.curdate').html('秒杀开始倒计时：');
                        }else{
                            $('#timeCalc').find('.curdate').html('秒杀开始倒计时：'+ calcStrObj.date+'天');
                        }

                        if(parseInt(calcStrObj.date) <=0 && parseInt(calcStrObj.hour) <=0){
                            $('#timeCalc').find('.curtime').html(calcStrObj.minute+'分'+calcStrObj.second + '秒');
                        }else{
                            $('#timeCalc').find('.curtime').html(calcStrObj.hour+'小时'+ calcStrObj.minute+'分'+calcStrObj.second + '秒');
                        }
                    },1000)
                </script>
            <?php }else{ ?>
                <span class="bg_main bdradius h22 pad2">秒杀进行中</span>
                <script>
                    var startFlag = true;
                </script>
            <?php } ?>
        <?php  /** end有秒杀*/?>
        <?php /**有拼团**/ } elseif(!empty($groupons)) {//foreach($groupons as $k=>$v){ ?>
            <span class="bg_main bdradius h22 pad2"><!--支付后并邀请 <?php echo $v['group_count']-1;?> 位好友参团，-->拼团购买超时，人数不足自动退款</span>
        
        <?php /**有活动**/ } elseif( isset($auto_rule[0]) ){ foreach($auto_rule as $k=>$v){ ?>
            <span class="bg_main bdradius h22 pad2"><?php echo $v['name']; ?></span>
        <?php } } /** end **/?>
    </p>
    <div class="fillcontent" ><?php echo $package['order_notice']; ?></div>
</div>

<!--div class="bg_fff bd martop block" id="showdetail">
    <p class="h2">点击查看图文详情<em class="iconfont color_888" style="margin-top:1%;float:right">&#xe61b;</em></p>
</div-->

<?php if($package['img_detail'] != null && $package['img_detail'] != ''): ?>
    <div class="block bg_fff bd martop diycontent">
        <p class="h30 bd_bottom">图文介绍</p>
        <div class="fillcontent"><?php echo $package['img_detail'];?></div>
    </div>
<?php endif; ?>

<?php if($package['hotel_address'] != null && $package['hotel_address'] != ''): ?>
    <?php if($inter_id != 'a455510007'){ ?>
    <script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp&key=6DMBZ-FXX3G-S2MQ7-IXSDK-P6XUH-3TBDD"></script>
    <div class="bg_fff bd martop block" id="openLocation">
    	<em class="iconfont color_888" style="float:right;">&#xe607;</em>
        <p class="txtclip" style="width:82%;">地址：<?php echo $package['hotel_address'];?></p>
    </div>
    <?php } ?>
<?php endif; ?>
<!-- 推荐位  -->
<?php echo isset($block) ? $block: '';?>
<!-- 推荐位  -->

<div class="ui_pull showdetail_pull scroll" style="display:none">
<!--    <div class="block bg_fff">-->
<!--        <p class="h2" style="padding-bottom:0">详情介绍</p>-->
<!--        <p class="h3 color_888">台山碧桂园是上冻最大的度假型酒店，已什么什么的特色和优质的服务水平深受打击啊的喜爱。</p>-->
<!--    </div>-->
</div>

<div class="foot_fixed">
    <div class="foot_fixed_list bd_top bg_fff">
        <a href="<?php echo Soma_const_url::inst()->get_pacakge_home_page(array('id'=>$inter_id)); ?>" class="color_888">
        	<em class="iconfont">&#xe61c;</em>
        	<p>首页</p>
        </a>
        <div style="padding:0;max-width:1px;" class="color_888">|</div>
        <a href="<?php echo Soma_const_url::inst()->get_soma_ucenter(array('id'=>$inter_id)); ?>" class="color_888">
        	<em class="iconfont">&#xe618;</em>
        	<p>订单</p>
        </a>
		<?php if( isset($finish_killsec) && $finish_killsec ){ ?>
        <div class="h30 bg_main disable">
            <?php if($show_y_flag):?><p class="color_fff y"><?php else: ?><p class="color_fff"><?php endif; ?><?php echo $killsec['killsec_price'];?></p>
            <p class=" color_fff">已售馨</p>
        </div>
        <?php /**有秒杀**/} else if( isset($killsec) &&  !empty($killsec)){ ?>
        <a class="h30 bg_main multi" id="killsec_btn">
            <span class="mask bdradius">活动加载中……</span>
            <span class="text">
            <?php if($show_y_flag):?><p class="color_fff y"><?php else: ?><p class="color_fff"><?php endif; ?><?php echo $killsec['killsec_price'];?></p>
            <?php if($package['type'] == $packageModel::PRODUCT_TYPE_BALANCE): ?>
                <p class=" color_fff"><?php echo $show_name;  ?>购买<em class="iconfont">&#xe61b;</em></p>
            <?php elseif($package['type'] == $packageModel::PRODUCT_TYPE_POINT): ?>
                <p class=" color_fff">积分购买<em class="iconfont">&#xe61b;</em></p>
            <?php else: ?>
                <p class=" color_fff">去秒杀<em class="iconfont">&#xe61b;</em></p>
            <?php endif; ?>
            </span>
        </a>
<script>
<?php /**有秒杀**/ if( isset($killsec) &&  !empty($killsec)){ ?>
var subscribe_lock= false;
$(function(){
    $('#killsec_btn').click(function(){
        var $this = $(this);
        if($this.hasClass('disabled')){
            return;
        }
        get_in_line()
    })
})
$(window).load(function(){
    $('#killsec_btn').removeClass('disabled');
})
function get_in_line(){
	if(!startFlag){
		var tmptime= new Date('<?php echo date('Y/m/d H:i:s',strtotime($killsec['killsec_time']));?>');
		var tmpnow = new Date();
		if ( tmptime.getTime()-tmpnow.getTime() < 15*60000 ){ // 小于30分钟
			$.MsgBox.Confirm( '秒杀尚未开始,敬请等待',null,null,'好的','取消' );
			
		} else {

        <?php if( isset($killsec['is_subscribe']) && $killsec['is_subscribe']==Soma_base::STATUS_TRUE ): ?>
            if( subscribe_lock== true){
            	$.MsgBox.Confirm( '您已成功订阅！' ,null,null,'好的','取消');
            
            } else {
            	$.MsgBox.Confirm('活动尚未开始，你可以订阅提醒，活动开始前10分钟将提醒您', function(){
            		//window.location.href='';//rightEvent;
            		pageloading('数据发送中，请稍后', 0.2);
            		$.ajax({
            		url:'<?php echo Soma_const_url::inst()->get_url('*/killsec/subscribe_killsec_notice_ajax', array('id'=>$inter_id )); ?>',
            			type: 'POST',
            			dataType:'JSON',
            			data:{
            			act_id :'<?php echo $killsec['act_id']; ?>',
            			},
            			success:function(json){
            				$('.pageloading').remove();
            				subscribe_lock= true;
            				if( json.status == 1 ){
            					$.MsgBox.Confirm( json.message,null,null,'好的','取消' );								
            				} else if( json.status == 2 ){
            					$.MsgBox.Confirm( json.message,null,null,'好的','取消' );
            				}
            			}
            		});
            	},function(){
            		//window.location.href='';//leftEvent;					
            	},'立即订阅', '稍候再说');
            }
<?php else: ?>
            $.MsgBox.Confirm( '秒杀尚未开始,敬请等待',null,null,'好的','取消' );
<?php endif; ?>
		}
		return false;
	}

	pageloading('排队中，请稍后',0.2);
	$.ajax({
		async: true,
		url:'<?php echo Soma_const_url::inst()->get_url('*/killsec/get_killsec_token_ajax', array('id'=>$inter_id )); ?>',
		type: 'POST',
		dataType:'JSON',
		data:{
			act_id :'<?php echo $killsec['act_id']; ?>',
		},
		success:function(json){
			$('.pageloading').remove();
			if( json.status == 1 ){
				var token= json.data.token;
				var instance_id= json.data.instance_id;
				//$.MsgBox.Confirm('', json.message,function(){
				location.href='<?php echo Soma_const_url::inst()->get_url('*/killsec/package_pay',
					array('id'=>$inter_id, 'pid'=>$_GET['pid'], 'act_id'=>$killsec['act_id'], )); ?>&instance_id='+ instance_id+ '&token='+ token;
				//} );
			} else if( json.status == 2 ){
				$.MsgBox.Confirm( json.message ,null,null,'好的','取消');
			}
		},
		error: function() {
			//失败
			$('.pageloading').remove();
			$.MsgBox.Confirm('哎呦喂，被挤爆了，请稍后重试！',function(){
				//if(e.status == 302||e.status == 307) {
					window.location.reload();
				//}		
			},function(){
					window.location.reload();
			},'好的','取消');
			return false;
		},
	　　complete : function(XMLHttpRequest,status){ //请求完成后最终执行参数
	　　　　if(status=='timeout'){//超时,status还有success,error等值的情况
				$('.pageloading').remove();
				$.MsgBox.Confirm('哎呦喂，被挤爆了，请稍后重试！',function(){
					//if(e.status == 302||e.status == 307) {
						window.location.reload();
					//}		
				},function(){
						window.location.reload();
				},'好的','取消');
	　　　　}
	　　},
		timeout: function() {
			$('.pageloading').remove();
					//失败
			$.MsgBox.Confirm('哎呦喂，被挤爆了，请稍后重试！',function(){
				//if(e.status == 302||e.status == 307) {
					window.location.reload();
				//}		
			},function(){
					window.location.reload();
			});
		}
	})
}
<?php } ?>
</script>
		<?php /**有拼团**/ } elseif( !empty($groupons) && !$is_expire ){ foreach($groupons as $k=>$v){ ?>
        <a class="h30 bg_main multi color_fff" href="<?php echo Soma_const_url::inst ()->get_groupon_first_pay(array('act_id'=>$v['act_id'],'id'=>$inter_id));?>">
            <?php if($show_y_flag):?><p class="y"><?php else: ?><p><?php endif;?><?php echo $v['group_price'];?></p>
            <p><?php echo $v['group_count'];?>人团,去开团<em class="iconfont">&#xe61b;</em></p>
        </a>
        <?php break;} } elseif( isset($auto_rule[0]) ){ /** end拼团**/?>
            <a class="h30 bg_minor multi color_fff" href="<?php echo Soma_const_url::inst()->get_package_pay(array(
                'pid'=>$_GET['pid'], 'id'=>$inter_id, 'rid'=>$auto_rule[0]['rule_id'] )); ?>">
                <div class="btn_center"  style="">
                    <div>
                        <p>团购特惠<em class="iconfont">&#xe61b;</em></p>
                    </div>
                </div>
            </a>
        <?php } ?>
            <?php if( $is_expire ): ?>
        <div class="h30 bg_minor multi color_fff disable"><p>已过期</p></div>
            <?php else: ?>
                <?php if( isset( $isTicket ) && !$isTicket ):?>
                    <a class="h30 bg_minor multi color_fff select_type" url="<?php echo Soma_const_url::inst ()->get_package_pay(array('pid'=>$_GET['pid'],'id'=>$inter_id));?>">
                        <div class="btn_center" style="">
                            <div>
                                <?php if($show_y_flag):?><p class="y"><?php else: ?><p><?php endif;?><?php echo $package['price_package'];?></p>
                                <?php if($package['type'] == $packageModel::PRODUCT_TYPE_BALANCE): ?>
                                    <p><?php echo $show_name;  ?>购买<em class="iconfont">&#xe61b;</em></p>
                                <?php elseif($package['type'] == $packageModel::PRODUCT_TYPE_POINT): ?>
                                    <p>积分购买<em class="iconfont">&#xe61b;</em></p>
                                <?php else: ?>
                                    <p>立即购买<em class="iconfont">&#xe61b;</em></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php else:?>
                    <a class="h30 bg_minor multi color_fff select_type" url="<?php echo Soma_const_url::inst()->get_url('*/*/ticket_select_time',array('id'=>$inter_id,'pid'=>$_GET['pid'],'tkid'=>$ticketId,'bType'=>$bType)); ?>">
                        <div class="btn_center"  style="">
                            <div>
                                <?php if($show_y_flag):?><p class="y"><?php else: ?><p><?php endif;?><?php echo $package['price_package'];?></p>
                                <?php if($package['type'] == $packageModel::PRODUCT_TYPE_BALANCE): ?>
                                    <p><?php echo $show_name;  ?>购买<em class="iconfont">&#xe61b;</em></p>
                                <?php elseif($package['type'] == $packageModel::PRODUCT_TYPE_POINT): ?>
                                    <p>积分购买<em class="iconfont">&#xe61b;</em></p>
                                <?php else: ?>
                                    <p>立即购买<em class="iconfont">&#xe61b;</em></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
    </div>
</div>
<div class="ui_pull share_pull" style="display:none" onClick="toclose()"></div>
<div style=" height:58px;"></div>
</body>
<script>

$.fn.imgscroll({
	imgrate : 640/290,
	circlesize: '8px',
})
$('#how_sent').click(function(){
	toshow($('.how_sent_pull'));
});
$('#toshare').click(function(){
	toshow($('.share_pull'));
});
$('.how_sent_pull').click(toclose);
$('#showdetail').click(function(){
	toshow($('.showdetail_pull'));
	$('.showdetail_pull .fillcontent').html('');
});
$('.showdetail_pull').click(toclose);

var specdata = '',specid = '', _url = '',setting_id='',stock = 0,price = '-';
$('.select_type').click(function(){
	if(specdata==''){
		pageloading();
		$.ajax({
			type: 'GET',
			url: '<?php echo Soma_const_url::inst()->get_url("*/*/ajax_product_spec"); ?>',
			data:{
				id:'<?php echo $this->inter_id; ?>',
				pid:<?php echo $package['product_id']; ?>
			},
			dataType:'JSON',
			error: function(){
				$.MsgBox.Alert('网络开小差了，请刷新重试');
				$('#mb_btn_no').remove();
			},
			success: function(data){
				if(data.status==1){
				  if(!jQuery.isEmptyObject(data.data.data)&&data.data.spec_type!=undefined){
					specdata = data.data;
					for(var i=specdata.spec_type.length-1;i>=0;i--){
						var html = $('<div class="flex flexrow"><div>商品'+specdata.spec_type[i]+'</div><div class="specbtn"></div></div>');
						for(var j=0;j<specdata.spec_name[i].length;j++){
							var specbtn = $('<span class="bg_F8F8F8" specid="'+specdata.spec_name_id[i][j]+'">'+specdata.spec_name[i][j]+'</span>');
							specbtn.get(0).onclick=function(e){
								e.stopPropagation();
                                stock = '-',price = '-',specid = '',_url='',setting_id='';
								var  text = '确认' ;
								$(this).addClass('bg_main').siblings().removeClass('bg_main');
								$('#spec_page .sure_btn').addClass('disable');
								$('.speclist .bg_main').each(function() {
									specid += $(this).attr('specid');
                                });
								for(var i =0;i< specdata.spec_id.length;i++){
									if( specid==specdata.spec_id[i].toString()){
										setting_id = specdata.setting_id[i];
									}
								}
								if( specdata.data[setting_id]!=undefined){
                                    _url = $('.foot_fixed .select_type').attr('url')+'&psp_sid='+specdata.data[setting_id].setting_id;
									stock = Number(specdata.data[setting_id].stock);
									price = Number(specdata.data[setting_id].specprice);
									var _html = ':'
									for(var d = 0;d<specdata.data[setting_id].spec_name.length;d++){
										_html+='"'+specdata.data[setting_id].spec_name[d]+'"';
									}
									$('.result').html(_html);
									if( stock<=0){
										text= '库存不足';
									}else{
										$('#spec_page .sure_btn').removeClass('disable');
									}
								}
								//console.log(stock)
								$('.specprice').html(price);
								$('.select_type span').html(price);
								$('#spec_page .sure_btn').html(text);
                        
							}
							html.find('.specbtn').append(specbtn);
						}
						$('.speclist').prepend(html);
					  }
					  $('.select_type').show();
					  toshow($('#spec_page'));
					}else{
						 window.location.href = $('.foot_fixed .select_type').attr('url');
					}
				}
				else{ $.MsgBox.Alert('似乎出了点问题，请稍后重试');}
			},
			complete: function(data){
				removeload();
			}
		});
	}else{
		if($(this).attr('url')!=undefined&&_url!=''&&stock>0) window.location.href = _url;
		else toshow($('#spec_page'));
	}
})
$('#spec_page .speclist').click(function(e){
	e.stopPropagation();
})
$('#spec_page .sure_btn').click(function(e){
    e.stopPropagation();
	if($(this).hasClass('disable'))return;
	if(specdata.data[setting_id]!=undefined&&_url!=''&&stock>0) window.location.href = _url;
});
</script>
</html>