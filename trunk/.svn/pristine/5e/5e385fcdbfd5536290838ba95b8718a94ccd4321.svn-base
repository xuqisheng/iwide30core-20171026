<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title><?php if(isset($page_title)) echo $page_title; else echo '我的卡券';?></title>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/card.css");?>"/>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/zepto.min.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/example.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/js/login.js");?>"></script>
    <script>
    wx.config({
        debug:false,
        appId:'<?php if( isset($signpackage["appId"]) )echo $signpackage["appId"];?>',
        timestamp:'<?php if( isset($signpackage["timestamp"]) )echo $signpackage["timestamp"];?>',
        nonceStr:'<?php if( isset($signpackage["nonceStr"]) )echo $signpackage["nonceStr"];?>',
        signature:'<?php if( isset($signpackage["signature"]) )echo $signpackage["signature"];?>',
        jsApiList: [
           'hideOptionMenu'
         ]
       });
       wx.ready(function () {
           wx.hideOptionMenu();
       });
    </script>
</head>
<style>
    .b_rig{width: 70%; max-width: 200px;text-align: right}
    .box a{display:block; overflow: hidden;}
</style>
<body ontouchstart>
    <div class="vip_content" style="display:none;">
        <!--FROM DATA START-->
        <div class="container" id="container"><div class="navbar">
        <div class="bd" style="height: 100%;">
            <div class="weui_tab">
                <div class="weui_tab_bd"  style="overflow-y:auto;overflow-x:hidden;width:100%;position:absolute;top:0px;left:0px;padding-top:0px">
                    <div style="position:relative;top:0px;left:0px;">
                        <div class="weui_panel_bd pad_2">
                            <?php if($cardlist){ ?>
                                <?php foreach($cardlist as $card) { ?>
                                <div  class="weui_media_box weui_media_appmsg">
                                	<div class="box clearfix">
                                        <?php if(!isset($card['is_pms_card'])){ ?>
                                            <a href="<?php echo base_url("index.php/membervip/card/cardinfo?member_card_id=".$card['member_card_id'].'&id='.$inter_id)?>">
                                        <?php }else{ ?>
                                            <a href="<?php echo base_url("index.php/membervip/card/pcardinfo?member_card_id=".$card['member_card_id'].'&id='.$inter_id)?>">
                                        <?php } ?>
                                            <?php if( isset($card['use_time_start'] ) && ($card['use_time_start']+7200) >=time() ){ ?>
        									   <div class="sign bgc_73e">新到</div>
                                            <?php }?>
                                            <?php if( isset($card['expire_time']) && ($card['expire_time']+43200) <=time() ){ ?>
                                                <div class="sign bgc_f64">快过期</div>
                                            <?php } ?>
        									<div class="b_rig">
        										<div class="l_h_1 r_title"><?php echo $card['title']; ?></div>
        										<div class="arrow"></div>
        										<div><?php echo $card['brand_name']; ?></div>
        									</div>
        									<div class="logo_txt color_ff7">
                                                <?php if(!isset($card['is_pms_card'])){ ?>
                                                    <?php if($card['card_type']==1) {?>
                                                        抵用券
                                                    <?php }elseif($card['card_type']==2){ ?>
                                                        折扣券
                                                    <?php }elseif($card['card_type']==3){ ?>
                                                        兑换券
                                                    <?php }elseif($card['card_type']==4){ ?>
                                                        储值卡
                                                    <?php }else{ ?>
                                                        错误卡券
                                                    <?php } ?>
                                                <?php }else{ ?>
                                                    官方券
                                                <?php } ?>
                                            </div>
        									<div class="price color_ff7">

                                                    <?php if($card['card_type']==1) {?>
                                                        ¥<font><?php echo $card['reduce_cost']; ?></font>
                                                    <?php }elseif($card['card_type']==2){ ?>
                                                        <font><?php echo $card['discount']; ?></font> 折
                                                    <?php }elseif($card['card_type']==3){ ?>

                                                    <?php }elseif($card['card_type']==4){ ?>
                                                        ¥<font><?php echo $card['money']; ?></font>
                                                    <?php }elseif($card['card_type']==5){ ?>
                                                        <font>1</font> 张
                                                    <?php }else{ ?>
                                                        物品抵扣券
                                                    <?php } ?>

                                            </div>
        									<div class="ra_inline"></div>
                                        </a>
    									<div class="f_date" >
                                            <?php if(!isset($card['is_pms_card'])){ ?>
                                                <?php if($card['can_give_friend']=='t'){ ?>
                                                    <a href="<?php echo base_url("index.php/membervip/card/givecard?member_card_id=".$card['member_card_id'].'&id='.$inter_id)?>" class="weui_btn weui_btn_mini weui_btn_primary" style="float:left;display: none;">赠送好友</a>
                                                <?php }?>
                                                <?php echo isset($card['use_time_start'] ) ? date('Y.m.d',$card['use_time_start']):''; ?>
                                                <?php if(isset($card['expire_time']) && !empty($card['expire_time'])){?>
                                                    -
                                                    <?php echo date('Y.m.d',$card['expire_time']); ?>
                                                    <?php if( ($card['expire_time']+43200) < time() ){ ?>
                                                        <font class="color_f64">（仅剩1天）</font>
                                                        <?php } ?>
                                                <?php } ?>
                                            <?php }else{ ?>
                                                <?php echo isset($card['use_time_start'] ) ? date('Y.m.d',$card['use_time_start']):''; ?>
                                                <?php if(isset($card['expire_time']) && !empty($card['expire_time'])){?>
                                                -
                                                <?php echo date('Y.m.d',$card['expire_time']); ?>
                                                <?php if( ($card['expire_time']+43200) < time() ){ ?>
                                                    <font class="color_f64">（仅剩1天）</font>
                                                <?php } ?>
                                            <?php }?>
                                            <?php } ?>
                                            <?php if(isset($card['pms_card_sno_show']) && $card['pms_card_sno_show']== true){?>
                                            <br/>
                                            <?php echo $card['pms_card_sno'];?>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            <?php }else{ ?>
                                 <div  class="weui_media_box weui_media_appmsg">
                                    <div class="box clearfix">
                                    <center>暂无卡券</center>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <script>
                </script>
            </div>
        </div>
        </div></div>
        <!--FROM DATA END-->
    </div>
    <!--BEGIN START-->
    <div id="toast" style="display:none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <i class="weui_icon_toast"></i>
            <p class="weui_toast_content">已完成</p>
        </div>
    </div>
    <!--BEGIN END-->
    <!--Loading START-->
    <div id="loadingToast" class="weui_loading_toast" style="">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <div class="weui_loading">
                <div class="weui_loading_leaf weui_loading_leaf_0"></div>
                <div class="weui_loading_leaf weui_loading_leaf_1"></div>
                <div class="weui_loading_leaf weui_loading_leaf_2"></div>
                <div class="weui_loading_leaf weui_loading_leaf_3"></div>
                <div class="weui_loading_leaf weui_loading_leaf_4"></div>
                <div class="weui_loading_leaf weui_loading_leaf_5"></div>
                <div class="weui_loading_leaf weui_loading_leaf_6"></div>
                <div class="weui_loading_leaf weui_loading_leaf_7"></div>
                <div class="weui_loading_leaf weui_loading_leaf_8"></div>
                <div class="weui_loading_leaf weui_loading_leaf_9"></div>
                <div class="weui_loading_leaf weui_loading_leaf_10"></div>
                <div class="weui_loading_leaf weui_loading_leaf_11"></div>
            </div>
            <p class="weui_toast_content">努力加载中</p>
        </div>
    </div>
    <!--Loading END-->
    <!--dialog start -->
    <div class="weui_dialog_alert" id="dialog2" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">操作提示</strong></div>
            <div class="weui_dialog_bd">XXX</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <!--dialog end -->
    <script type="text/javascript">
    window.onload=function(){
        var w_height=$(window).height();
        var weui_navbar_height=$('.weui_navbar').height()
        var weui_tab_bd_height=w_height-weui_navbar_height;
       $('.weui_tab_bd').css('height',weui_tab_bd_height+'px');
    }
        //通用JS
        $(function(){
            /* 等待加载 START */
            $('.vip_content').attr('style',"");
            $("#loadingToast").attr('style',"display:none;");
            /* 等待加载 END */
            //分页信息自动加载

        });
    </script>
</body>
</html>
