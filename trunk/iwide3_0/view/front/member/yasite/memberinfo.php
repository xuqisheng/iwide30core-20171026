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
    <link href="<?php echo base_url("public/member/phase2/styles/global.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/mycss.css"); ?>" rel="stylesheet">
    <script src="<?php echo base_url("public/member/phase2/scripts/jquery.js"); ?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js"); ?>"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/ajaxForm.js"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <title>会员资料</title>
</head>
<body>
<form id="SaveMemberInfo" action="<?php echo base_url("index.php/membervip/perfectinfo/save"); ?>" method="post">
    <input type="hidden" name="smstype" value="0"/>
    <div class="list_style bd_bottom">
        <div class="webkitbox justify">
            <div>头像</div>
            <div><img class="headportrait" src="<?php echo $info['headimgurl'] ?>"/></div>
        </div>
    </div>
    <div class="list_style martop bd">
        <?php if ($modify_config['name']['show']) { ?>
            <div class="webkitbox justify name arrow">
                <div><?php echo $modify_config['name']['name']; ?></div>
                <input readonly type="<?php echo $modify_config['name']['type']; ?>"
                       value="<?php if ($centerinfo['name'] != '微信用户') {
                           echo $centerinfo['name'];
                       } ?>"/>
            </div>
        <?php } ?>
        <?php if ($modify_config['phone']['show']) { ?>
            <div class="webkitbox justify phone arrow">
                <div><?php echo $modify_config['phone']['name']; ?></div>
                <input readonly type="<?php echo $modify_config['phone']['type']; ?>"
                       value="<?php echo $centerinfo['cellphone'] ?>"/>
            </div>
        <?php } ?>
        <?php if ($modify_config['email']['show']) { ?>
            <div class="webkitbox justify arrow email">
                <div><?php echo $modify_config['email']['name']; ?></div>
                <input readonly type="<?php echo $modify_config['email']['type']; ?>"
                       value="<?php echo $centerinfo['email'] ?>"/>
            </div>
        <?php } ?>
        <?php if ($modify_config['sex']['show']) { ?>
            <div class="webkitbox justify arrow sex">
                <div><?php echo $modify_config['sex']['name']; ?></div>
                <div>
                    <select name="sex" class="h28">
                        <option <?php if ($centerinfo['sex'] == "3" || $centerinfo['sex'] == "3") {
                            echo 'selected';
                        } ?> value="3">保密
                        </option>
                        <option <?php if ($centerinfo['sex'] == "2") {
                            echo 'selected';
                        } ?> value="2">女
                        </option>
                        <option <?php if ($centerinfo['sex'] == "1") {
                            echo 'selected';
                        } ?> value="1">男
                        </option>
                    </select>
                </div>
            </div>
        <?php } ?>
        <?php if ($modify_config['birthday']['show']) { ?>
            <div class="webkitbox justify arrow birthday">
                <div><?php echo $modify_config['birthday']['name']; ?></div>
                <input name="birthday" value="<?php echo date('Y-m-d', $centerinfo['birth']); ?>"
                       pattern="<?php echo $modify_config['birthday']['regular']; ?>">
            </div>
        <?php } ?>
        <?php if ($modify_config['idno']['show']) { ?>
            <div class="webkitbox justify arrow idno">
                <div><?php echo $modify_config['idno']['name']; ?></div>
                <input readonly type="<?php echo $modify_config['idno']['type']; ?>"
                       value="<?php echo $centerinfo['id_card_no'] ?>"/>
            </div>
        <?php } ?>
    </div>
    <?php if ($centerinfo['member_mode'] == 2) { ?>
        <!--    <div class="sign_list center C_b5b5b5"><a class="" href="sgin_in.html">账户安全设置</a>-->
    <?php } ?>
    </div>
</form>

<script type="text/javascript">
    //通用JS
    $(function () {
        /* OUTLOGIN START */
        $('.sign_btn').click(function () {
            pageloading();
            $.post("<?php echo base_url("index.php/membervip/login/outlogin");?>",
                function (result) {
                    removeload();
                    if (!result) {
                        $.MsgBox.Alert('请求失败,请刷新重试或联系管理员!');
                        return false;
                    }
                    if (result.err > 1) {
                         $.MsgBox.Alert(result['msg']);
                    } else if (result.err == '0') {
                        var locat_url = "<?php echo base_url('index.php/membervip/center');?>";
                        $.MsgBox.Confirm(result.msg,function(){window.location.href=locat_url;});
						$('#mb_btn_no').remove();
                    }
                }, "json");
        })
        /* OUTLOGIN NED */
    });

</script>
</body>
</html>
