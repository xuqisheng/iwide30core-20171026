<body>
<?php 
    require_once VIEWPATH. 'soma' . DS. $this->theme. DS. 'package'. DS.'header_var.php';
    $bg_img = (isset($themeConfig['index_bg']) && !empty($themeConfig['index_bg'])) ? $themeConfig['index_bg'] : get_cdn_url("public/soma/mooncake_v1/{$theme_key}/bg.jpg");
    $idx_color= (isset($themeConfig['idx_color']) && !empty($themeConfig['idx_color'])) ? $themeConfig['idx_color'] : '#000';
?>
<script>
    var package_obj= {
		'appId': '<?php echo $wx_config["appId"]?>',
        'timestamp': <?php echo $wx_config["timestamp"]?>,
        'nonceStr': '<?php echo $wx_config["nonceStr"]?>',
        'signature': '<?php echo $wx_config["signature"]?>'
    }
    /*下列字符不能删除，用作替换之用*/
    //[<sign_update_code>]
    wx.config({
        debug: false,
        appId: package_obj.appId,
        timestamp: package_obj.timestamp,
        nonceStr: package_obj.nonceStr,
        signature: package_obj.signature, 
        jsApiList: [<?php echo $js_api_list; ?>,'getLocation']
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

        wx.getLocation({
            success: function (res) {
                get_package_nearby(res.latitude,res.longitude);
            },
            cancel: function (res) {
                $.MsgBox.Alert('为了更好的体验，请先授权获取地理位置');
            }
        });
    });
</script>
<style>
    .theme_bg_img{background-image:url(<?php echo $bg_img; ?>);}
	
</style>

<div class="pageloading"><p class="isload" style="margin-top:150px">正在加载</p></div>
<!-- 以上为head -->
<script src="<?php echo get_cdn_url('public/soma/scripts/imgscroll.js');?>"></script>
<script src="<?php echo get_cdn_url('public/soma/scripts/jquery.touchwipe.min.js');?>"></script>
<script>var moonCakeArr ;</script>
<div class="fixed_body theme_bg <?php echo $theme_key; ?> theme_bg_img">
    <div class="relative main_content_box">
        <div class="relative">
            <div class="imgscroll">
                <div class="headerslide">
                    <?php
                    $mooncakeArr = array();
                    foreach($products as $k=>$v){
//                        $v['transparent_img'] = $v['face_img']; //测试
                        if(isset($v['transparent_img'])):?>
                            <?php $mooncakeArr[] = $v['product_id'];?>
                            <a class="slideson" title="<?php echo Soma_const_url::inst()->get_package_detail(array('pid'=>$v['product_id'],'id'=>$inter_id) ); ?>" 
                            href="<?php echo Soma_const_url::inst()->get_url('soma/package/mooncake_list',array('id'=>$inter_id, 'fcid'=> $filter_cat));?>"><img src="<?php echo $v['transparent_img'];?>"></a>
                        <?php endif;?>
                    <?php } ?>
                </div>
            </div>
            <div class="pre_btn">&nbsp;</div>
            <div class="next_btn">&nbsp;</div>
            <?php if(count($mooncakeArr) > 1 ):?>
            <?php endif;?>
            <?php if(!empty($mooncakeArr)){ ?>
                   <script>  moonCakeArr =  <?php echo json_encode($mooncakeArr); ?>;</script>
            <?php }?>

        </div>

        <?php
            $buyBtnImg = (isset($themeConfig['buy_btn']) && !empty($themeConfig['buy_btn'])) ? $themeConfig['buy_btn'] : get_cdn_url("public/soma/mooncake_v1/{$theme_key}/send_1.png");
            $toFriendImg = (isset($themeConfig['to_friend_btn']) && !empty($themeConfig['to_friend_btn'])) ? $themeConfig['to_friend_btn'] : get_cdn_url("public/soma/mooncake_v1/{$theme_key}/send_2.png");
            $toGroupImg = (isset($themeConfig['to_group_btn']) && !empty($themeConfig['to_group_btn'])) ? $themeConfig['to_group_btn'] : get_cdn_url("public/soma/mooncake_v1/{$theme_key}/send_3.png");
            $more_btn_img = (isset($themeConfig['more_btn']) && !empty($themeConfig['more_btn'])) ? $themeConfig['more_btn'] : get_cdn_url("public/soma/mooncake_v1/{$theme_key}/more.png");
        ?>
    </div>

    <div class="webkitbox center martop img_btn_list h30" id="send_link">
        <div class="color_000" id="ToMyself">
            <span class="btnimg"><img src="<?php echo $buyBtnImg;?>"></span>
        </div>
        <div class="color_000" id="ToFriend">
            <span class="btnimg"><img src="<?php echo $toFriendImg;?>"></span>
        </div>
        <div class="color_000" id="ToGroup">
            <span class="btnimg"><img src="<?php echo $toGroupImg;?>"></span>
        </div>
    </div>

    <!--div class="attribute absolute h30 _w hide">
        <span>邮寄</span>
        <span>自提</span>
        <span>退款 </span>
    </div-->
	<style>
	#foot_btn span{color:<?php echo $idx_color;?> !important;}
	.theme1 ._more_link{display:none}
	</style>
    <div class="webkitbox center absolute img_btn_list h30 _w" id="foot_btn">
        <a href="<?php echo Soma_const_url::inst()->get_soma_order_list(array('id'=>$inter_id)); ?>">
            <span>我的订单</span>
        </a>
        <a href="<?php echo Soma_const_url::inst()->get_soma_gift_list();?>">
            <span>我的礼物</span>
        </a>
        <?php if( isset($themeConfig['phone']) ): ?>
        <a href="tel:<?php echo $themeConfig['phone']; ?>">
            <span>在线咨询</span>
        </a>
        <?php endif; ?>
    </div>
    <a href="<?php echo Soma_const_url::inst()->get_url('soma/package/mooncake_list',array('id'=>$inter_id));?>" class="_more_link absolute">
        <span class="btnimg"><img src="<?php echo $more_btn_img;?>"></span>
    </a>
<!-- 显示分销号start -->
    <div class="distribute_btn" style="display:none">
        <span><img src="<?php echo get_cdn_url('public/soma/images/distributeimg.jpg');?>" /></span>
    </div>
    <div class="ui_pull distribute" style="display:none" >
        <div class="pullbox center bg_fff">
            <div class="pullclose bg_999" onClick="toclose()">&times;</div>
            <div class="pullimg"><div class="squareimg"><img src="<?php echo get_cdn_url('public/soma/images/distributeimg.jpg');?>" /></div></div>
            <div>分销号:<span id="distribute_id"></span></div>
            <div>姓　名:<span id="distribute_name"></span></div>
            <div class="bg_999 pullbtn h26" onClick="toclose()">取消</div>
            <a class="bg_main pullbtn h26" id="distribute_url" href="">进入分销</a>
        </div>
    </div>
    <script>
        $('.distribute_btn').click(function(){toshow($('.distribute'));});

        //异步查询分销员号
        function get_saler(){
            var saler = "<?php echo $this->input->get('saler');?>";
            var url = "<?php echo Soma_const_url::inst()->get_url('*/package/get_saler_id_by_ajax',array( 'id'=> $this->inter_id) );?>";
            $.ajax({
                url: url,
                type: 'post',
                data: {saler:saler},
                dataType: 'json',
                success:function( json ){
                    if( json.status == 1 ){
                        if(json.jump_url== 1){
                        	window.location="<?php 
                            	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' 
                            	    || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                            	echo "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        	?>&saler="+ json.sid;
                        }
                        if(json.show_button== 1){
                        	//alert( json.sid + json.name );
                            $("#distribute_id").html(json.sid);
                            $("#distribute_name").html(json.name);
                            $("#distribute_url").attr('href',json.url);
                            $(".distribute_btn").show();
                        }
                    }
                }
            });
        }
        // get_saler();
    </script>
<!-- 显示分销号end -->

</div>
<script>

/* 首页适配 */
var _w = $(window).width();
var _h = $(window).height();
var _minrate=0.5625;
var _maxrate=0.6245;
	console.log(_w/_h)
if(_w/_h<_minrate){
	$('.fixed_body').height(_w/_minrate);
}
if(_w/_h>_maxrate){
	$('.fixed_body').height(_w/_maxrate);
}
/* 首页适配 end */


var cur_goods_index = 0;
$.fn.imgscroll({
	imgrate     :480/480,       
	partent_div :'imgscroll', 
	circleshow  :false,
	speed		:200,
	delay	    :3,
	prebtn		:'.pre_btn',
	nextbtn		:'.next_btn',
	autowipe    :false,
	callback    :function(data){
        //console.log(data);
        cur_goods_index=data.index;
    }
});

var productPayUrl = "<?php echo Soma_const_url::inst()->get_package_pay();?>";
$('#ToMyself').click(function(){
    var pid = moonCakeArr[cur_goods_index];
    location.href= productPayUrl + "&pid=" + pid + "&bType=mail";

	//console.log('送自己');
})

$('#ToFriend').click(function(){
    var pid = moonCakeArr[cur_goods_index];
    location.href= productPayUrl + "&pid=" + pid + "&bType=gift";

   // console.log('送朋友');
})

$('#ToGroup').click(function(){
    var pid = moonCakeArr[cur_goods_index];
    location.href= productPayUrl + "&pid=" + pid + "&bType=gift";

    //console.log('送群友');
})

</script>
</body>
</html>