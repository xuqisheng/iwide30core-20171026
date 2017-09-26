<body>
<script src="<?php echo get_cdn_url('public/soma/scripts/jquery.touchwipe.min.js');?>"></script>
<div class="pageloading"><p class="isload"></p></div>
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

        // wx.getLocation({
        //     success: function (res) {
        //         get_package_nearby(res.latitude,res.longitude);
        //     },
        //     cancel: function (res) {
        //         $.MsgBox.Confirm('为了更好的体验，请先授权获取地理位置');
        //     }
        // });
    });
</script>
<div class="fixed_body theme_bg" <?php if( $zongzi_bg ) :?>style="background-image: url(<?php echo $zongzi_bg;?>)"<?php endif;?>>
    <?php if( isset( $staff ) && $staff ):?>
        <div class="pull_btn"><img src="<?php echo get_cdn_url('public/soma/zongzi/pull_btn.png');?>"></div>
    <?php endif;?>
    <div class="relative main_content_box center">
        <div class="relative">
            <div class="imgscroll">
                <div class="headerslide">
                    <?php if($products): $products = array_slice($products, 0, 4); ?>
                        <?php
                            $mooncakeArr = array();
                            foreach($products as $k=>$v):
                                $mooncakeArr[] = $v['product_id'];
                        ?>
                                <a class="slideson" href="<?php echo Soma_const_url::inst()->get_url('soma/package/package_detail/', array('id' => $this->inter_id, 'bsn' => 'package','pid'=>$v['product_id']));?>"><img pid="<?php echo $v['product_id'];?>" src="<?php echo $v['face_img'];?>"></a>
                        <?php endforeach;?>
                    <?php endif;?>
                    <a class="slideson" href="<?php echo Soma_const_url::inst()->get_url('soma/package/zongzi_index/', array('id' => $this->inter_id, 'bsn' => 'package', 'tkid'=>$ticketId, 'catid'=>$catId));?>">
                        <div class="more_link">
                            <div class="h32">全部的商品</div>
                            <div class="h24 color_888">点击查看更多的端午特惠礼包</div>
                        </div>
                        <img style="display:none" title="占位图">
                    </a>
                </div>
            </div>
            <div class="pre_btn" style="display:none">&nbsp;</div>
            <div class="next_btn">&nbsp;</div>
        </div>
        <div class="h32" style="margin-top:4px" id="name"></div>
        <div class="img_price">
            <span id="ImgPrice"><span></span></span>
            <del class="h24"></del>
        </div>
    </div>
    <div class="webkitbox center martop img_btn_list h30" id="send_link">
        <div class="color_000" id="ToMyself">
            <span class="btnimg"><img src="<?php echo get_cdn_url('public/soma/zongzi/btn01.png');?>"></span>
        </div>
        <div class="color_000" id="ToFriend">
            <span class="btnimg"><img src="<?php echo get_cdn_url('public/soma/zongzi/btn02.png');?>"></span>
        </div>
    </div>
    <div class="btm_link webkitbox" style="position:fixed">
        <a href="<?php echo Soma_const_url::inst()->get_url('soma/package/index/', array('id' => $this->inter_id, 'bsn' => 'package', 'tkid' => $ticketId ));?>" class="icon1" style="color:#a3bf78">首 页</a>
        <a href="<?php echo Soma_const_url::inst()->get_url('soma/order/my_order_list/', array('id' => $this->inter_id, 'bsn' => 'package'));?>" class="icon2">订 单</a>
        <a href="<?php echo Soma_const_url::inst()->get_url('soma/gift/my_gift_list/', array('id' => $this->inter_id, 'bsn' => 'package'));?>" class="icon3">我的礼物</a>
        <a href="<?php echo $member_url;?>" class="icon4">个人中心</a>
    </div>
</div>
<?php if( isset( $staff ) && $staff ):?>
    <div class="alert_cont">
        <div class="conter_box">
            <div style="width:40px;margin:auto;"><img src="<?php echo get_cdn_url('public/soma/zongzi/pull_btn.png');?>" /></div>
            <div class="conter_txt">
                <p><span>姓名:</span><span><?php echo $staff['saler_name'];?></span></p>
                <p><span>分销id:</span><span><?php echo $staff['saler_id'];?></span></p>
            </div>
            <div class="btns_list">
                <div class="cancel_btn">取消</div>
                <div class="reward_btn">赚奖励</div>
            </div>
        </div>
        <div class="share_box">
            <div><img src="<?php echo get_cdn_url('public/soma/zongzi/alter_txt.png');?>"></div>
        </div>
    </div>
<?php endif;?>
</body>
<script src="<?php echo get_cdn_url('public/soma/zongzi/jie.js');?>"></script>
<script>
var cur_goods_index = 0;
var moonCakeArr = <?php if( $mooncakeArr ) { echo json_encode($mooncakeArr); } else { echo ''; } ?>;
var goods = [
//  {name:'金房卡蛋黄咸粽子大礼盒',origin:'300',price:'12.34'},
//  {name:'凑数1111111',origin:'999',price:'56.78'},
//  {name:'凑数333333333',origin:'1',price:'90.12'}
    <?php if($products): ?>
        <?php foreach($products as $k=>$v): ?>
            {name:'<?php echo $v['name'];?>',origin:'<?php echo $v['price_market'];?>',price:'<?php echo $v['price_package'];?>'},
        <?php endforeach;?>
    <?php endif;?>
];
function fill_data(index){
    if(goods.length>index){
        $('#send_link').show();
        $('#name').show().html(goods[index].name);
        $('.img_price del').show().html('原价: '+goods[index].origin);
        $('#ImgPrice').show();
        $('#ImgPrice span').html('');
        var num = goods[index].price;
        var point = num.indexOf('.');
        var array = num.split("");
        for(var i = 0;i<array.length;i++){
            var tt = $('<tt></tt>');
            if( point==i){
                tt.addClass('_point');
            }else{
                tt.addClass('_'+array[i]);
            }
            $('#ImgPrice span').append(tt);
            tt.addClass('fadein');
        }
    }else{
        $('#send_link').hide();
        $('#name').hide();
        $('.img_price del').hide();
        $('#ImgPrice').hide();
    }
}
fill_data(cur_goods_index);
$.fn.imgscroll({
    imgrate     :420/390,       
    partent_div :'imgscroll', 
    circleshow  :false,
    speed       :200,
    delay       :3,
    prebtn      :'.pre_btn',
    nextbtn     :'.next_btn',
    isround     :false, //循环
    autowipe    :false,
    callback    :function(data){
        cur_goods_index=data.index;
        fill_data(cur_goods_index);
        if(cur_goods_index==0)$('.pre_btn').hide();
        if(cur_goods_index >0)$('.pre_btn').show();
        if(cur_goods_index<$('.headerslide img').length/3-1)$('.next_btn').show();
        if(cur_goods_index==$('.headerslide img').length/3-1)$('.next_btn').hide();     
        autoHeight();
    }
});
var productPayUrl = "<?php echo Soma_const_url::inst()->get_url('soma/package/package_detail/', array('id' => $this->inter_id, 'bsn' => 'package'));?>";
$('#ToMyself').click(function(){
    var pid = moonCakeArr[cur_goods_index];
    location.href= productPayUrl + "&pid=" + pid + "&bType=";
    //console.log('送自己');
})

$('#ToFriend').click(function(){
    var pid = moonCakeArr[cur_goods_index];
    location.href= productPayUrl + "&pid=" + pid + "&bType=gift";
   // console.log('送朋友');
})
/*判断按钮是否被遮挡*/
function autoHeight(){
    try{
        var _h =$('#send_link').offset().top+$('#send_link').height();
        var _b =$('.btm_link').height();
        var _p =$(window).height(); 
        if( _h >= _p - _b ){
            $('.fixed_body').height(_p+_b+15);
        }
        console.log(_h,_b,_p)
    }catch(ev){
        console.log(ev);
    }
}
autoHeight();
</script>
</html>
