<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>积分记录</title>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/zepto.min.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/example.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/js/login.js");?>"></script>
</head>
<body ontouchstart>
    <div class="vip_content" style="display:none;">
        <!--FROM DATA START-->
        <div class="container" id="container"><div class="navbar">
        <div class="bd" style="height: 100%;">
            <div class="weui_tab">
                <div class="weui_navbar">
                    <div class="weui_navbar_item <?php if(!$credit_type){ ?> weui_bar_item_on <?php } ?> ">
                        <a href="<?php echo base_url("index.php/membervip/bonus")?>">全部</a>
                    </div>
                    <div class="weui_navbar_item <?php if($credit_type==1){ ?> weui_bar_item_on <?php } ?> ">
                        <a href="<?php echo base_url("index.php/membervip/bonus?credit_type=1")?>">增加积分</a>
                    </div>
                    <div class="weui_navbar_item <?php if($credit_type==2){ ?> weui_bar_item_on <?php } ?> ">
                        <a href="<?php echo base_url("index.php/membervip/bonus?credit_type=2")?>">消费积分</a>
                    </div>
                </div>
                <div class="weui_tab_bd"  style="overflow-y:auto;overflow-x:hidden;width:100%;position:absolute;top:52px;left:0px;padding-top:0px">
                    <div style="position:relative;top:0px;left:0px;">
                        <div class="weui_panel_bd">
                        <?php if (!empty($bonuslist)){?>
                            <?php foreach ($bonuslist as $key => $value){ ?>
                            <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                                <div class="weui_media_hd" style="width:80px;">
                                    <?php if($value['log_type']==1){ ?>+<?php }else{ ?>-<?php } ?>
                                    <?php echo $value['amount']?>
                                </div>
                                <div class="weui_media_bd">
                                    <h4 class="weui_media_desc"><?php echo $value['note']?></h4>
                                    <p class="weui_media_desc"><?php echo $value['last_update_time']?></p>
                                </div>
                            </a>
                            <?php }}?>
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
        });
    </script>
</body>
</html>
