<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
    <meta name="Maker" content="Taoja" tel="13544425200">
    <meta name="format-detection" content="telephone=no" />
    <title><?=!empty($view_conf['home']['title'])?$view_conf['home']['title']:'邀请好友'?></title>
    <script src="<?php echo base_url("public/member/nvitedkim/js/jquery.js");?>"></script>
    <link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/css/alert.css");?>"/>
    <script type="text/javascript" src="<?php echo base_url("public/member/version4.0/js/alert.js");?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <style>
        .bg_F3F4F8, body, html {background-color: #ffffff;}p{margin:0;font-family:HeiTi SC}.w100{width:100%}.absolute{position:absolute}body{width:100%;height:100%;position:absolute;margin:0;font-size:14px;font-family:Heiti SC}.right{float:right}.full{width:100%;height:100%}.maxscreen{width:100%}.left{float:left}.coverpage,.coverpage2{width:100%;height:100%;background-color:#000;opacity:.8}.fixed{position:fixed}.none{display:none}.relative{position:relative}.left{float:left}.fullbg{background-size:100% 100%}.main{transform-origin:top left;-webkit-transform-origin:top left;-o-transform-origin:top left;-moz-transform-origin:top left}.full{height:100%}.center{text-align:center}.ib{display:inline-block}.icon{width:12%}.pad20{margin:15px 20px}.info{margin-left:5px}.line1{font-size:1.2em}.line2{font-size:1.1em;margin-top:5px;color:#6b6b6b}.line{font-size:1.2em;color:#6b6b6b}.rule_img{width:100px;margin:auto}.middle{vertical-align:middle}.rule{margin-top:50px;font-size:1.1em;line-height:1.4em;color:#666;margin-bottom:30px}.tubiao{width:15%;display:inline-block}.num{background-color:#ffc266;padding:5px;border-radius:100%;color:white;width:10px!important;height:10px!important;line-height:10px;text-align:center;font-weight:bold}.wenzi{display:inline-block;width:85%}.see{color:black;text-align:center;font-size:1.1em}.rule_body{margin-top:80px}.ewm_body{position:absolute;top:40%;left:25%;width:50%}.ewm_title{text-align:center;width:100%;top:26%;color:#333;font-size:1.3em;font-weight:bold}.pad10{margin:10px 20px}.guize_body{background-color:white;width:90%;position:absolute;left:5%;top:15%;border-radius:10px;max-height:75%;overflow:auto}@media screen and (max-width:375px){body{font-size:13px}}@media screen and (max-width:320px){body{font-size:12px}}.zhengwen{margin:15px 15px;}.zhengwen p{line-height: 25px;}.global {color: black;text-align: center;ffont-size: 0.9em;margin-top: 3px;}
    </style>
</head>

<body>
<div class="main absolute">
    <div class="headimg">
        <img class="w100" src="<?php echo base_url("public/member/nvitedkim");?>/img/title.png">
    </div>
    <div class="rank">
        <?php foreach ($member_lvl as $mlk => $lvlvo):?>
        <?php if(!empty($hold_lvl_group[$mlk]['count']) && floatval($hold_lvl_group[$mlk]['count'])>0 && $lvlvo['is_default']=='f'):?>
        <div class="gold pad20" data-lvl="<?=$mlk?>">
            <a href="<?=EA_const_url::inst()->get_url('*/*/share',array('c'=>md5($mlk)))?>">
                <div class="icon ib">
                    <img class="w100" src="<?php echo base_url("public/member/public/images/lvl_icon");?>/<?=!empty($lvlvo['lvl_icon'])?$lvlvo['lvl_icon']:''?>.png">
                </div>
                <div class="info ib">
                    <div class="line1"><?=!empty($lvlvo['lvl_name'])?$lvlvo['lvl_name']:'--'?>资格，您可邀请<?=!empty($hold_lvl_group[$mlk]['total'])?$hold_lvl_group[$mlk]['total']:0?>位好友</div>
                    <div class="line2">您的邀请资格还剩<?=!empty($hold_lvl_group[$mlk]['count'])?$hold_lvl_group[$mlk]['count']:0?>次</div>
                </div>
            </a>
        </div>
        <?php elseif($lvlvo['is_default']=='f' && in_array($mlk,$lvl_group)):?>
        <div class="pad20" data-lvl="<?=$mlk?>">
            <a href="javascript:void(0);">
                <div class="icon ib">
                    <img class="w100" src="<?php echo base_url("public/member/public/images/de_lvl_icon");?>/<?=!empty($lvlvo['lvl_icon'])?$lvlvo['lvl_icon']:''?>.png">
                </div>
                <div class="info ib">
                    <div class="line1"><?=!empty($lvlvo['lvl_name'])?$lvlvo['lvl_name']:'--'?>资格，您可邀请<?=!empty($hold_lvl_group[$mlk]['total'])?$hold_lvl_group[$mlk]['total']:0?>位好友</div>
                    <div class="line2">您的邀请资格还剩0次</div>
                </div>
            </a>
        </div>
        <?php endif;?>
        <?php endforeach;?>
    </div>
    <div class="rule">
        <div class="rule_title">
            <div class="rule_img">
                <img src="<?php echo base_url("public/member/nvitedkim");?>/img/jj.png" class="w100">
            </div>
        </div>
        <div class="sm1 pad20" style="margin-bottom:5px;">
            <?=!empty($view_conf['home']['pagetip'])?$view_conf['home']['pagetip']:''?>
        </div>
        <div class="see"><?=$view_conf['activity_rule']['button_value']?>></div>
        <div class="global">会员等级权益？</div>
    </div>
</div>
<div class="coverpage full fixed none"></div>
<div class="rule_main fixed full none">
    <div class="rule_back absolute full"></div>
    <div class="rule_body absolute">
        <img src="<?php echo base_url("public/member/nvitedkim");?>/img/saoma.png" alt="" class="rule_body_img w100">
        <div class="ewm_title absolute">请朋友扫码,领取会员权益</div>
        <div class="ewm_body">
            <img src="<?php echo base_url("public/member/nvitedkim");?>/img/ewm.png" id="qrcode_img" class="w100" alt="">
        </div>
    </div>
</div>
<div class="guize full fixed none">
    <div class="guize_back absolute full"></div>
    <div class="guize_body">
        <div class="rule_img" style="margin-top:10px;">
            <img src="<?php echo base_url("public/member/nvitedkim");?>/img/jj.png" class="w100">
        </div>
        <div class="zhengwen">
        <?=!empty($view_conf['activity_rule']['content'])?$view_conf['activity_rule']['content']:''?>
        </div>
    </div>
</div>

<div class="global_lvl_group full fixed none">
    <div class="global_back absolute full"></div>
    <div class="guize_body">
        <div class="zhengwen">
            <?php foreach ($global_lvl_group as $gk=>$gc):?>
            <div>
            <?php if(!empty($member_lvl[$gk]) && $member_lvl[$gk]['is_default']=='f'):?>
                <div style="font-weight: bold;font-size: 1.1em;">
                    <?=!empty($member_lvl[$gk]['lvl_name'])?$member_lvl[$gk]['lvl_name']:''?>：
                </div>
                <?php foreach ($gc as $zk=>$zc):?>
                <p><?=!empty($member_lvl[$zk]['lvl_name'])?$member_lvl[$zk]['lvl_name']:''?>资格，可邀请<?=$zc?>位好友</p>
                <?php endforeach;?>
            <?php endif;?>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<script>
    /*微信JSSDK*/
    wx.config({
        debug: false,
        appId: '<?php if (!empty($signpackage["appId"])) echo $signpackage["appId"];?>',
        timestamp: '<?php if (isset($signpackage["timestamp"])) echo $signpackage["timestamp"];?>',
        nonceStr: '<?php if (isset($signpackage["nonceStr"])) echo $signpackage["nonceStr"];?>',
        signature: '<?php if (isset($signpackage["signature"])) echo $signpackage["signature"];?>',
        jsApiList: ['hideMenuItems']
    });
    wx.ready(function () {
        <?php if($js_menu_hide):?>wx.hideMenuItems({menuList:[<?php echo $js_menu_hide;?>]});<?php endif;?>
        <?php if($js_menu_show):?>wx.showMenuItems({menuList:[<?php echo $js_menu_show;?>]});<?php endif;?>
    });
    /*end*/

    var click = "ontouchstart" in window ? "touchstart" : "mousedown";
    var coverpage = document.querySelector(".coverpage");
    var rule = document.querySelector(".rule_main");
    var guize = document.querySelector(".guize");
    var global_lvl_group = document.querySelector(".global_lvl_group");
    var see = document.querySelector(".see");
    var global = document.querySelector(".global");
    var back = document.querySelector(".rule_back");
    var guize_back = document.querySelector(".guize_back");
    var global_back = document.querySelector(".global_back");

    see.addEventListener(click, function() {
        coverpage.style.display = "block";
        guize.style.display = "block";
    });

    global.addEventListener(click, function() {
        coverpage.style.display = "block";
        global_lvl_group.style.display = "block";
    });

    guize_back.addEventListener(click, function() {
        coverpage.style.display = "none";
        guize.style.display = "none";
    });

    back.addEventListener(click, function() {
        coverpage.style.display = "none";
        rule.style.display = "none";
    });

    global_back.addEventListener(click, function() {
        coverpage.style.display = "none";
        global_lvl_group.style.display = "none";
    });

    $('.j_btn_star').click(function(){
        var btn = $(this),lvl=btn.data('lvl');
        if(!lvl) {
            new AlertBox({content:'您没有权限邀请该等级',type:'tip',site:'bottom',time:2000}).show();return false;
        }
        pageloading();
        var postUrl = "<?php echo EA_const_url::inst()->get_url('*/api/invitate/start')?>";
        var datas = {code: lvl,channel:'face'};
        $.ajax({
            url:postUrl,
            type:'POST',
            data:datas,
            dataType:'json',
            timeout:15000,
            success: function (data) {
                removeload();
                if(data.status==1){
                    var qrcode_url = "<?php echo EA_const_url::inst()->get_url('*/api/myqrcode')?>?code="+data.data;
                    $("#qrcode_img").attr("src",qrcode_url);
                    setTimeout(function () {
                        coverpage.style.display = "block";
                        rule.style.display = "block";
                    },100);
                }else if(data.status==0)
                new AlertBox({content:data.message,type:'info',site:'topmid'}).show();
            },
            error: function () {
                new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
            }
        });
    });
</script>
</body>

</html>
