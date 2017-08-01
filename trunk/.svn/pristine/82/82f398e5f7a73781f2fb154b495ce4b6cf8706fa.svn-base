<!doctype html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/mycss.css");?>" rel="stylesheet">
<title>领取优惠券</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
.package_text{color: #fe8f00;font-size: 0.8rem;margin: 3% 0;}
.card-div{margin: 5% 0;}
.model-box{position: absolute;  left: 0;  top: 0;  width: 100%;  height: 100%;  background: rgba(0, 0, 0, 0.04);}
.model-box .load{position: absolute;  left: 40%;  top: 30%;  background: #000;  z-index: 999;  color: #fff;  padding: 10px;  border-radius: 10px;  opacity: 0.3;  -webkit-box-shadow: 0 0 10px #0a0a0a;  -moz-box-shadow: 0 0 10px #0CC;  box-shadow: 0 0 30px #0c0c0c;}
-->
</style>
<body>
<script>
wx.config({
    debug:false,
    appId:'<?php echo $signpackage["appId"];?>',
    timestamp:<?php echo $signpackage["timestamp"];?>,
    nonceStr:'<?php echo $signpackage["nonceStr"];?>',
    signature:'<?php echo $signpackage["signature"];?>',
    jsApiList: [
        'hideOptionMenu'
     ]
   });
   wx.ready(function () {
       wx.hideOptionMenu();
   });
</script>
<div class="model-box"><div class="load">loading...</div></div>
<div class="page_loading" style="display:none"><div>请稍候...</div></div>
<div class="nav">
    <img src="<?php echo isset($card_info['logo_url']) ? $card_info['logo_url'] : '';?>"/>
    <div class="p_ab"></div>
</div>
<?php if(!empty($card_info)):?>
    <?php if(!empty($card_info['is_package']) && $card_info['is_package']=='f'):?>
        <div class="content">
            <div class="con_name">
                <div class="na_img img_auto_cut"><img src="<?php echo $public['logo'];?>"></div>
                <div class="na_text">
                    <p class="na_title">Hi~我是<?php echo $public['name'];?></p>
                    <p class="con_text">送你一张券！</p>
                    <div class="aworr"><img src="<?php echo base_url("public/member/public/images/aworr.png");?>"/></div>
                </div>
                <div style="clear:both"></div>
            </div>

            <div class="coupon">
                <div class="cou_con">
                    <p class="money">
                        <?php if($card_info['card_type']==1){ ?>
                            <?php echo $card_info['reduce_cost']; ?>元
                        <?php }elseif($card_info['card_type']==2){ ?>
                            <?php echo $card_info['discount']; ?>折
                        <?php }elseif($card_info['card_type']==3){ ?>
                            兑换券
                        <?php }elseif($card_info['card_type']==4){ ?>
                            <?php echo $card_info['money']; ?>元
                        <?php }else{ ?>

                        <?php }?>
                    </p>
                    <p class="cou_titl"><?php echo $card_info['title']; ?></p>
                    <p class="cou_text"><?php echo $card_info['brand_name']; ?></p>
                    <p class="cou_time">领取时间:<br/><?php echo date('Y-m-d',$card_info['time_start']) ?>至<?php echo date('Y-m-d',$card_info['time_end']) ?></p>
                    <p>1张</p>
                    <div class="bg_1"></div>
                </div>
            </div>
        </div>
    <?php elseif (!empty($card_info['is_package']) && $card_info['is_package']=='t'):?>
        <div class="content">
            <div class="con_name">
                <div class="na_img img_auto_cut"><img src="<?php echo $public['logo'];?>"></div>
                <div class="na_text">
                    <p class="na_title">Hi~我是<?php echo $public['name'];?></p>
                    <p class="con_text">送你一个<?php echo isset($card_info['name'])?'"'.$card_info['name'].'"':'';?>大礼包！</p>
                    <div class="aworr"><img src="<?php echo base_url("public/member/public/images/aworr.png");?>"/></div>
                </div>
                <div style="clear:both"></div>
            </div>

            <div class="coupon">
                <div class="cou_con">
                    <p class="con_text">礼包内容:</p>
                </div>
                <div class="cou_con">
                    <?php if(!empty($card_info['lvl_name'])):?>
                        <p class="package_text">等级: <?=$card_info['lvl_name'];?></p>
                    <?php endif;?>
                    <?php if(isset($card_info['deposit']) && isset($card_info['deposit'])):?>
                        <p class="package_text">储值: <?php echo $card_info['deposit'];?></p>
                    <?php endif;?>
                    <?php if(isset($card_info['credit']) && isset($card_info['credit'])):?>
                        <p class="package_text"><?php echo $this->_ci_cached_vars['filed_name']['credit_name'];?>: <?php echo $card_info['credit'];?></p>
                    <?php endif;?>
                    <?php if(!empty($card_info['card'])):?>
                    <?php foreach ($card_info['card'] as $kc => $vo):?>
                        <?php if(isset($vo['inter_id'])):?>
                            <div class="card-div">
                                <p class="money">
                                    <?php if($vo['card_type']==1){ ?>
                                        <?php echo $vo['reduce_cost']; ?>元
                                    <?php }elseif($vo['card_type']==2){ ?>
                                        <?php echo $vo['discount']; ?>折
                                    <?php }elseif($vo['card_type']==3){ ?>
                                        兑换券
                                    <?php }elseif($vo['card_type']==4){ ?>
                                        <?php echo $vo['money']; ?>元
                                    <?php }else{ ?>

                                    <?php }?>
                                </p>
                                <p class="cou_titl"><?php echo $vo['title']; ?></p>
                                <p class="cou_text"><?php echo $vo['brand_name']; ?></p>
                                <p class="cou_time">领取时间:<br/><?php echo date('Y-m-d',$vo['time_start']) ?>至<?php echo date('Y-m-d',$vo['time_end']) ?></p>
                                <div class="bg_1"></div>
                            </div>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>
                </div>
            </div>
        </div>
    <?php endif;?>
<div class="flooter">
        <?php if(isset($card_info['frequency']) && $card_info['frequency']>$gain_count && isset($card_info['is_package']) && $card_info['is_package']=='f'){ ?>
            <a class="fl_btn gain_card" href="javascript:getcard();">立即领取</a>
            <a class="fl_btn look_card" style="display:none;" href="<?php echo site_url("membervip/card");?>">立即查看</a>
        <?php }elseif(isset($card_info['is_package']) && $card_info['is_package']=='f'){ ?>
            <a class="fl_btn gain_card" style="display:none;" href="javascript:getcard();">立即领取</a>
            <a class="fl_btn look_card" href="<?php echo site_url("membervip/card");?>">立即查看</a>
        <?php } ?>

        <?php if(isset($card_info['frequency']) && isset($card_info['is_package']) && $card_info['is_package']=='t' && $card_info['frequency']>$gain_count){ ?>
            <a class="fl_btn gain_package" href="javascript:void(0);">立即领取</a>
            <a class="fl_btn look_card" style="display:none;" href="<?php echo site_url("membervip/card");?>">立即查看</a>
        <?php }elseif(isset($card_info['is_package']) && $card_info['is_package']=='t'){ ?>
            <a class="fl_btn gain_package" style="display:none;" href="javascript:void(0);">立即领取</a>
            <a class="fl_btn look_card" href="<?php echo site_url("membervip/card");?>">立即查看</a>
        <?php } ?>
</div>
<?php endif;?>
<script>
    var card_rule_id = "<?php echo isset($card_rule_id) ? $card_rule_id : 0;?>";
    var package_id = "<?php echo isset($card_info['package_id']) ? $card_info['package_id'] : 0;?>";
    var frequency = "<?php echo isset($card_info['frequency']) ? $card_info['frequency'] : 0;?>";
    var ajaxload = false;

    $(document).ready(function () {
        setTimeout(function () {
            $("div.model-box").fadeOut();
        },1500);

        $(document).on('click','.gain_package',function (e) {
            e.preventDefault();
            if(ajaxload)return;
            $('.page_loading').show();
            var surl = "<?php echo base_url('index.php/membervip/card/getpackage');?>";
            var datas = {"package_id":package_id,"card_rule_id":card_rule_id,"frequency":frequency};
            var obj = $(this),obj_next = $(this).next();
            $.ajax({
                url:surl,
                data:datas,
                type:'POST',
                dataType:'json',
                timeout:6000,
                success: function (data) {
                    $('.page_loading').hide();
                    ajaxload=false;
                    if(data['err']>0){
                        alert(data['msg']);
                    }else{
                        alert('领取成功');
                        obj.hide();
                        obj_next.show();
                    }
                },
                error: function () {
                    $('.page_loading').hide();
                    ajaxload=false;
                    alert("请求失败");
                }
            });
        });
    });
	

    function getcard(){
		if(ajaxload)return;
		$('.page_loading').show();
        $.post("<?php echo base_url('index.php/membervip/card/addcard');?>",
            { "card_rule_id":card_rule_id},
        function(Result){
			$('.page_loading').hide();
			ajaxload=false;
            if(Result['err']>0){
                alert(Result['msg']);
            }else{
                alert('领取成功');
                $('.gain_card').hide();
                $('.look_card').show();
            }
        }, "json");
    }
</script>
</body>
</html>
