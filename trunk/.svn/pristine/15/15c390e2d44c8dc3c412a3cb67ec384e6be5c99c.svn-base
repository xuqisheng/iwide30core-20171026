<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>赠送卡券</title>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/card.css");?>"/>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/zepto.min.js");?>"></script>
    <script>
    wx.config({
        debug:false,
        appId:'<?php echo $signpackage["appId"];?>',
        timestamp:<?php echo $signpackage["timestamp"];?>,
        nonceStr:'<?php echo $signpackage["nonceStr"];?>',
        signature:'<?php echo $signpackage["signature"];?>',
        jsApiList: [
            'showAllNonBaseMenuItem',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
         ]
       });
        wx.ready(function (){
            wx.showAllNonBaseMenuItem();
            <?php if($card_info){ ?>
            //分享到朋友圈
            wx.onMenuShareTimeline({
                title: "送你一张【<?php echo $card_info['title'] ?>】",
                link: "<?php echo base_url('index.php/membervip/card/givecard?member_card_id='.$card_info['member_card_id'].'&cardOpenid='.$card_openid.'&id='.$inter_id)?>", // 分享链接
                imgUrl: "<?php echo $card_info['logo_url'] ?>", // 分享图标
                success: function () {
                    var post_url = "<?php echo base_url('index.php/membervip/card/hang_card?id='.$inter_id); ?>";
                    var post_cardId = <?php echo $card_info['member_card_id']; ?>;
                    var openid = "<?php echo $card_openid; ?>";
                    var module = "<?php echo $card_info['receive_module'] ?>";
                    $.post(post_url, {
                        'card_id': post_cardId,
                    },
                    function(data){
                        window.location.href="<?php echo base_url('index.php/membervip/center?id='.$inter_id);?>";
                    }, "json");
                },
                cancel: function () {

                }
            });
            //分享给朋友
            wx.onMenuShareAppMessage({
                title: "送你一张【<?php echo $card_info['title'] ?>】", // 分享标题
                desc: "送你一张【<?php echo $card_info['title'] ?>】,帮你轻松享优惠.", // 分享描述
                link: "<?php echo base_url('index.php/membervip/card/givecard?member_card_id='.$card_info['member_card_id'].'&cardOpenid='.$card_openid.'&id='.$inter_id)?>", // 分享链接
                imgUrl: "<?php echo $card_info['logo_url'] ?>", // 分享图标
                type: 'link', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    var post_url = "<?php echo base_url('index.php/membervip/card/hang_card?id='.$inter_id); ?>";
                    var post_cardId = <?php echo $card_info['member_card_id']; ?>;
                    var openid = "<?php echo $card_openid; ?>";
                    var module = "<?php echo $card_info['receive_module'] ?>";
                    $.post(post_url, {
                        'card_id': post_cardId,
                    },
                    function(data){
                        window.location.href="<?php echo base_url('index.php/membervip/center?id='.$inter_id);?>";
                    }, "json");
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            <?php }else{ ?>
                wx.hideOptionMenu();
            <?php }?>
        });
    </script>
</head>
<style>

</style>
<body>
<div class="ticket">
	<div class="t_head"></div>
	<h1><?php echo $public['name'];?></h1>
    <?php if($card_info){ ?>
	<div class="t_img"><img src="<?php echo $card_info['logo_url'] ?>"/></div>
	<div class="con_list">
		<div class="c_868">卡券名称</div>
		<div class="c_l_c ellipsis"><?php echo $card_info['title'] ?></div>
	</div>
	<div class="con_list">
		<div class="c_868">卡券内容</div>
		<div class="c_l_c ellipsis"><?php echo $card_info['notice'] ?></div>
	</div>
	<div class="con_list">
		<div class="c_868">有效期</div>
		<div class="c_l_c ellipsis"><?php echo date('Y-m-d H:i:s',$card_info['createtime']) ?>至<?php echo date('Y-m-d H:i:s',$card_info['expire_time']) ?></div>
	</div>
	<div class="btn_lst">
        <?php if($card_openid==$openid){ ?>
            <span class="fen_s">赠送给好友</span>
        <?php }else{ ?>
            <a class="m_r_4 getcard" href="javascript:void(0);"><span>领取</span></a>
        <?php } ?>
	</div>
    <?php }else{ ?>
    <div class="btn_lst">
        <div class="c_l_c ellipsis">卡券信息不存在或已经赠送</div>
        <a class="m_r_4" href="javascript:history.go(-1);location.reload();"><span>返回</span></a>
    </div>
    <?php } ?>
</div>
<div class="fixed"></div>
<script>
$(function(){
    $('.fen_s').click(function(){
            $('.fixed').show();
        })
    $('.fixed').click(function(){
        $('.fixed').hide();
    })
    //点击领取
    <?php if($card_info){ ?>
        $('.getcard').click(function(){
            var post_url = "<?php echo base_url('index.php/membervip/card/savegivecard'); ?>";
            var post_cardId = <?php echo $card_info['member_card_id']; ?>;
            var openid = "<?php echo $card_openid; ?>";
            var module = "<?php echo $card_info['receive_module'] ?>";
            $.post(post_url, {
                'card_id': post_cardId,
                'cardOpenid': openid,
                'cardModule':module,
            },
                function(data){
                    if(data['err']>0){
                        alert(data['msg']);
                    }else{
                        window.location.href="<?php echo base_url('index.php/membervip/center?id='.$inter_id);?>";
                    }
                }, "json");
        });
    <?php } ?>
});
</script>
</body>
</html>
