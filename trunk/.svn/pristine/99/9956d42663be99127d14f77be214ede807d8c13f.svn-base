<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no,email=no">
    <meta name="ML-Config" content="fullscreen=yes,preventMove=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=320,initial-scale=1,user-scalable=0">
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/card.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/css/alert.css");?>"/>
    <link href="<?php echo base_url("public/member/phase2/styles/global.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/mycss.css"); ?>" rel="stylesheet">
    <script src="<?php echo base_url("public/member/phase2/scripts/jquery.js"); ?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js"); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("public/member/version4.0/js/alert.js");?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <title>优惠券详情</title>
    <style type="text/css">
        .card_fixed {
            width: 100%;
            height: 100%;
            position: fixed;
            left: 0px;
            top: 0px;
            background: rgba(0,0,0,0.8) url(<?php echo base_url("public/member/version4.0");?>/images/62714544492951331.png) no-repeat center top;
            background-size: 94%;
            display: none;
        }
    </style>
</head>
<body class="">
<div class="use_coupon">
    <div class="u_c_box">
        <!--<span class="f_r h26 appoint">指定门店可用</span>-->
        <a href="<?=!empty($center_url)?$center_url:''?>"><img class="hotel_img" src="<?php echo isset($public['logo'])?$public['logo']:'';?>"><span><?php echo isset($public['name'])?$public['name']:'';?></span></a>
    </div>
    <div class="bg_fff use_coupo_txt">
        <!-- <img src="images/iconfont-erweima.png" /> -->
        <div class="single">
            <div class="room_na"><?=$card_info['title']?></div>
            <?php if(!isset($card_info['is_pms_card']) && isset($card_info['card_type'])):?>
                <div class="discount">
                    <?php
                    switch ($card_info['card_type']) {
                        case 1:
                            // 抵用券样式
                            echo $card_info['reduce_cost'] . '元';
                            break;
                        case 2:
                            // 折扣券样式
                            echo $card_info['discount'] . '折';
                            break;
                        case 3:
                            // 兑换券样式
                            echo '兑换券';
                            break;
                        case 4:
                            // 储值卡样式
                            echo $card_info['money'] . '元';
                            break;
                        default:
                            //错误卡卷样式
                    }
                    ?>
                </div>
            <?php endif;?>
            <?php if($card_info['is_active']=='t'):?>
                <?php if($card_info['is_online']=='1'):?>
                    <div>仅线上可用</div>
                <?php elseif($card_info['is_online']=='2'):?>
                    <div>仅线下可用</div>
                <?php endif;?>
                <?php if($card_info['is_giving']=='t' && $user['member_info_id']!=$gift_mem_info['member_info_id']):?>
                    <div>
                        您的好友<?=empty($gift_mem_info['name'])?$gift_mem_info['nickname']:$gift_mem_info['name'];?>送你一张
                        <?php if(!isset($card_info['is_pms_card']) && isset($card_info['card_type'])):?>
                            <?php
                            switch ($card_info['card_type']) {
                                case 1:
                                    // 抵用券样式
                                    echo $card_info['reduce_cost'] . '元';
                                    break;
                                case 2:
                                    // 折扣券样式
                                    echo $card_info['discount'] . '折';
                                    break;
                                case 3:
                                    // 兑换券样式
                                    echo '"兑换券"';
                                    break;
                                case 4:
                                    // 储值卡样式
                                    echo $card_info['money'] . '元';
                                    break;
                                default:
                                    //错误卡卷样式
                            }
                            ?>
                        <?php endif;?>
                    </div>
                <?php elseif ($card_info['is_giving']=='t'):?>
                <div>转赠中</div>
                <?php endif;?>
                <?php if($card_info['is_given_by_friend']=='t' && $card_info['is_giving']=='t' && $user['member_info_id'] != $gift_mem_info['member_info_id']):?>
                    <div class="use bg_ff9900 c_fff" id="action_receive">立即领取</div>
                <?php endif;?>
            <?php else:?>
                <div><strong>优惠券已失效或已被领取</strong></div>
            <?php endif;?>
        </div>
        <div class="multi_line">
            <div class="m_line_con">
                <div>券码:</div>
                <div><?=$card_info['coupon_code']?></div>
            </div>
            <div class="m_line_con">
                <div>使用说明:</div>
                <div><?=$card_info['description']?></div>
            </div>
            <div class="m_line_con">
                <div>使用提醒:</div>
                <div><?=$card_info['notice']?></div>
            </div>
            <div class="m_line_con">
                <div>可用时间:</div>
                <div><?=isset($card_info['expire_time'])?date('Y.m.d', $card_info['use_time_start']).'-'.date('Y.m.d', $card_info['expire_time']):''?></div>
            </div>
            <div class="m_line_con">
                <div>使用方式:</div>
                <div><?=isset($card_info['use_way'])?$card_info['use_way']:''?></div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    //点击领取
    <?php if($card_info){ ?>
        $('#action_receive').one('click',function(){//防止多次请求，只绑定一次
            var text = $(this).text(),obj = $(this);
            obj.prop('disabled',true).addClass('disabled').text('领取中...');
            var post_url = "<?=!empty($receive_card_url)?$receive_card_url:''?>",ec_code = "<?=$ec_code?>";
            $.post(post_url,{ec_code:ec_code},function(data){
                obj.remove();
                if(data.err>0){
                   new AlertBox({content:data.msg,type:'tip',site:'bottom',time:1000}).show();
                }else if(data.err==0){
                    var jump_url = "<?=!empty($center_url)?$center_url:''?>";
                    new AlertBox({content:'领取成功',type:'tip',site:'bottom',dourl:jump_url,time:100}).show();
                    return false;
                }
                setTimeout(function () {
                    window.location.reload();
                },1000);
            }, "json");
        });
    <?php } ?>
});
</script>
</body>
</html>
