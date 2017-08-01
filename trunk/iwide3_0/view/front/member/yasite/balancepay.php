<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
    <meta name="Maker" content="Taoja" tel="13544425200">
    <meta name="format-detection" content="telephone=no" />
    <title><?php echo $this->_ci_cached_vars['filed_name']['balance_name'];?>支付</title>
    <link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
    <script src="<?php echo base_url("public/member/phase2/scripts/jquery.js");?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
    <script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <style>
        .bg_F3F4F8, body, html {background-color: #ffffff;}p{margin:0;font-family:HeiTi SC}.w100{width:100%}.absolute{position:absolute}body{width:100%;height:100%;position:absolute;margin:0;font-size:14px;font-family:Heiti SC}.right{float:right}.full{width:100%;height:100%}.maxscreen{width:100%}.left{float:left}.coverpage,.coverpage2{width:100%;height:100%;background-color:#000;opacity:.8}.fixed{position:fixed}.none{display:none}.relative{position:relative}.left{float:left}.fullbg{background-size:100% 100%}.main{transform-origin:top left;-webkit-transform-origin:top left;-o-transform-origin:top left;-moz-transform-origin:top left}.full{height:100%}.center{text-align:center}.ib{display:inline-block}.title{margin-top:15px;text-align:center;font-size:1.2em;font-weight:bold}.said{margin-top:15px;text-align:center;font-size:1.5em}.kuang{width:70%;margin:auto;margin-top:10px}.kuang input,select{width:80%;margin:10px auto;border-radius:5px;display:block;padding:0;outline:0;border:1px solid #ddd;height:2.3rem;font-size:1.1em;line-height:1.5rem;text-indent:1em}.yzmk{width:80%;margin:10px auto}.yzmbtn{vertical-align:middle;width:38%;border-radius:5px;background-color:#f90;height:1.5rem;margin-left:4%;color:white;text-align:center;line-height:1.5rem}.yzmk input{width:54%;margin:0;display:inline-block}.btn{display:inline-block;padding:10px;background-color:#00b620;width:45%;border-radius:5px;color:white}.check{text-align:center;margin-top:20px}.xuanxiang{text-align:center;margin-top:20px;font-size:1em}.xuanxiang span{color:#1a7dfa}@media screen and (max-width:375px){body{font-size:13px}}@media screen and (max-width:320px){body{font-size:12px}}
        .balance-pay{    height: 3.5rem;  line-height: 3.5rem;  font-size: 1.5rem;  text-align: center;  color: #ff9900;}
        .xuanxiang span{margin: 0 0.5rem;}
    </style>
</head>

<body>
<div class="said"><?=!empty($public['name'])?$public['name']:''?></div>
<div class="balance-pay">
    <flex between>
        <ib>￥<?=!empty($order['pay_money'])?$order['pay_money']:'0.00'?></ib>
    </flex>
</div>
<div class="xuanxiang">我的账户余额：<?=!empty($user_info['balance'])?$user_info['balance']:'0.00'?></div>
<form action="<?=EA_const_url::inst()->get_url('*/*/sub_pay');?>" class="form-save" method="post">
    <input type="hidden" name="orderid" value="<?=!empty($orderid)?$orderid:0?>" />
    <?php /*
    <div class="kuang">
        <input type="password" autocomplete="off" name="password" placeholder="请输入支付密码" />
    </div>
 */?>
    <div class="check">
        <div class="btn sub_pay">确认支付</div>
    </div>
<?php /*
    <div class="xuanxiang">
        <span><a href="<?=!empty($links[0]['url'])?$links[0]['url']:''?>"><?=!empty($links[0]['name'])?$links[0]['name']:''?></a></span>
        <span><a href="<?=!empty($links[1]['url'])?$links[1]['url']:''?>"><?=!empty($links[1]['name'])?$links[1]['name']:''?></a></span>
    </div>
 */?>
</form>
<script type="text/javascript">
    wx.config({
        debug: false,
        appId: '<?php if (isset($signpackage["appId"])) echo $signpackage["appId"];?>',
        timestamp: '<?php if (isset($signpackage["timestamp"])) echo $signpackage["timestamp"];?>',
        nonceStr: '<?php if (isset($signpackage["nonceStr"])) echo $signpackage["nonceStr"];?>',
        signature: '<?php if (isset($signpackage["signature"])) echo $signpackage["signature"];?>',
        jsApiList: [
            'hideOptionMenu'
        ]
    });
    wx.ready(function () {
        wx.hideOptionMenu();
    });

    /* 提交信息 START */
    $('.sub_pay').click(function(){
        var form = $(".form-save"),form_url=form.attr("action"),btn = $(this),loadtip=null;
        form.ajaxSubmit({
            url:form_url,
            dataType:'json',
            timeout:20000,
            beforeSubmit: function(arr, $form, options){
                /*验证提交数据*/
                var _null = false,
                    _msg = '',
                    inputos = $(".form-save").find('input'),
                    _inputo = null;

                for (i in inputos) {
                    var name = inputos[i].name, value = $.trim(inputos[i].value);
                    _inputo = inputos[i];
                    switch (name) {
                        case 'password':
                            if (!value && inputos[i].disabled === false) {
                                _null = true;
                                _msg = '请输入支付密码';
                            }else if(value.length < 6){
                                _null = true;
                                _msg = '支付密码小于六个字符';
                            }
                            break;
                    }
                    if (_null === true) break;
                }

                if (_null === true) {
                    $.MsgBox.Alert(_msg);
                    return false;
                }
                /*end*/
                pageloading();
            },
            success: function(result){
                removeload();
                if(result.status==1){
                    $.MsgBox.Alert(result.message);
                    window.location.href=result.data;
                }else{
                    $.MsgBox.Alert(result.message);
                    if(result.data.length){
                        setTimeout(function () {
                            window.location.href=result.data;
                        },1000);
                    }
                }
            },
            error:function () {
                removeload();
                $.MsgBox.Alert('网络异常,请求失败,请刷新重试或联系管理员!');
            }
        });
    });
    /* 提交信息 END */
</script>
</body>
</html>
