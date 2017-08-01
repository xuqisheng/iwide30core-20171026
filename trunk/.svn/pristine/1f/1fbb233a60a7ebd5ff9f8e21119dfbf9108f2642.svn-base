<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
	<meta name="Maker" content="Taoja" tel="13544425200">
	<meta name="format-detection" content="telephone=no" />
	<title><?=!empty($view_conf['reg_login']['login_title'])?$view_conf['reg_login']['login_title']:'邀请好友-登录'?></title>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/css/alert.css");?>"/>
    <link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
    <script src="<?php echo base_url("public/member/nvitedkim/js/jquery.js");?>"></script>
    <script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
    <script type="text/javascript" src="<?php echo base_url("public/member/version4.0/js/alert.js");?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <style>
        .bg_F3F4F8, body, html {background-color: #ffffff;}p{margin:0;font-family:HeiTi SC}.w100{width:100%}.absolute{position:absolute}body{width:100%;height:100%;position:absolute;margin:0;font-size:14px;font-family:Heiti SC}.right{float:right}.full{width:100%;height:100%}.maxscreen{width:100%}.left{float:left}.coverpage,.coverpage2{width:100%;height:100%;background-color:#000;opacity:.8}.fixed{position:fixed}.none{display:none}.relative{position:relative}.left{float:left}.fullbg{background-size:100% 100%}.main{transform-origin:top left;-webkit-transform-origin:top left;-o-transform-origin:top left;-moz-transform-origin:top left}.full{height:100%}.center{text-align:center}.ib{display:inline-block}.title{margin-top:15px;text-align:center;font-size:1.2em;font-weight:bold}.said{margin-top:15px;text-align:center;font-size:1em}.kuang{width:70%;margin:auto;margin-top:10px}.kuang input,select{width:80%;margin:10px auto;border-radius:5px;display:block;padding:0;outline:0;border:1px solid #ddd;height:2.3rem;font-size:1em;line-height:1.5rem;text-indent:1em}.yzmk{width:80%;margin:10px auto}.yzmbtn{vertical-align:middle;width:38%;border-radius:5px;background-color:#f90;height:1.5rem;margin-left:4%;color:white;text-align:center;line-height:1.5rem}.yzmk input{width:54%;margin:0;display:inline-block}.btn{display:inline-block;padding:10px;background-color:#f90;width:45%;border-radius:5px;color:white}.check{text-align:center;margin-top:20px}.xuanxiang{text-align:center;margin-top:20px;font-size:1em}.xuanxiang span{color:#1a7dfa}@media screen and (max-width:375px){body{font-size:13px}}@media screen and (max-width:320px){body{font-size:12px}}
    </style>
</head>

<body>
    <div class="headimg">
        <img class="w100" src="<?php echo base_url("public/member/nvitedkim");?>/img/title.png">
    </div>
    <div class="said">请登录您的会员身份</div>
    <form id="fomr_con" class="fomr_con" action="<?php echo $save_url;?>" method="post" >
        <input type="hidden" name="smstype" data-name="smstype" data-check="0" value="2" />
        <input type="hidden" name="code" data-name="code" data-check="0" value="<?=$code?>" />
        <div class="kuang">
        <?php foreach ($login_conf as $key => $item):?>
        <?php if($item['type']!='select' && $key!='phonesms'):?>
            <input type="<?php echo $item['type'];?>" <?php if($item['type']=='password'):?>class="password"<?php endif;?> name="<?php echo $key;?>" pattern="<?php echo $item['regular']; ?>" data-name="<?php echo $item['name'];?>" data-check="<?php echo $item['check']; ?>" placeholder="<?php echo $item['note'];?>" />
        <?php elseif($key=='phonesms'):?>
            <div class="yzmk">
                <input class="yzm ib" data-name="<?php echo $item['name'];?>" name="<?php echo $key;?>" type="<?php echo $item['type'];?>" pattern="<?php echo $item['regular']; ?>" data-check="<?php echo $item['check']; ?>" placeholder="<?php echo $item['note'];?>" />
                <div class="yzmbtn ib send_code">获取</div>
            </div>
        <?php endif;?>
        <?php if($item['type']=='select' && $key=='sex'):?>
            <select class="">
                <option value="3"><?php echo $item['note'];?></option>
                <option value="2">女</option>
                <option value="1">男</option>
            </select>
        <?php endif;?>
        <?php endforeach;?>
    </div>
    <div class="check">
        <div class="btn login">登录</div>
    </div>
    </form>
    <div class="xuanxiang">还不是会员？<span><a href="<?php echo EA_const_url::inst()->get_url('*/*/register',array('id'=>$inter_id,'code'=>$code));?>">马上注册</a></span></div>
    <script type="text/javascript">
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
            wx.hideOptionMenu();
        });

        $(function(){
            var numbe=59,times=null,sendto=1;
            $(".send_code").click(function(){
                if(sendto==1){
                    var tel=$("input[name='phone']").val(),phonesms=$("input[name='phonesms']").val(),smstype=$("input[name='smstype']").val();
                    var regular = new RegExp($("input[name='phone']").attr('pattern'));
                    if(!tel || tel==''){
                        new AlertBox({content:'请输入手机号码',type:'tip',site:'bottom'}).show();return false;
                    }else if(!regular.test(tel)){
                        new AlertBox({content:'请输入正确的手机号码',type:'tip',site:'bottom'}).show();return false;
                    }

                    //请求发送验证码
                    var postUrl = "<?php echo site_url("membervip/sendsms");?>";
                    var datas = {phone:tel,phonesms:phonesms,smstype:smstype};
                    $.ajax({
                        url:postUrl,
                        type:'POST',
                        data:datas,
                        dataType:'json',
                        timeout:6000,
                        success: function (result) {
                            if(result.err=='0'){
                                new AlertBox({content:'短信已发送,请注意查收!',type:'tip',site:'bottom'}).show();
                            }
                        },
                        error: function () {
                            new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();
                            return false;
                        }
                    });
                }
                sendto = 0;
                autopay_time();
            });
            function autopay_time(){
                $(".send_code").html('重新发送'+numbe+"s");
                times=setInterval(function(){
                    numbe--;
                    $(".send_code").html('重新发送'+numbe+"s");
                    if(numbe==0){
                        clearInterval(times);
                        $(".send_code").html("重发验证码");
                        numbe=59;
                        sendto=1;
                    }
                },1000);
            }

            /* 提交信息 START */
            $('.login').click(function(){
                var form = $("#fomr_con"),form_url=form.attr("action"),btn = $(this);
                var share=$("input[name='share']").val();
                form.ajaxSubmit({
                    url:form_url,
                    dataType:'json',
                    timeout:30000,
                    beforeSubmit: function(arr){
                        /*验证提交数据*/
                        var _null = false, _msg = '',inputobj={};
                        for(i in arr){
                            var name = arr[i].name,value=$.trim(arr[i].value),obj=$("input[name='"+name+"']"),check=obj.data("check");
                            var regular = new RegExp(obj.attr('pattern'));
                            if(!value && (name!='share' && name!='actId')) {
                                inputobj = obj;
                                var text_name = obj.data("name");
                                _msg='请输入'+text_name;
                                _null = true;break;
                            }else if(!regular.test(value) && check==1 && (name!='share' && name!='actId')){
                                inputobj = obj;
                                var text_name = obj.data("name");
                                _msg=text_name+'不合法';
                                _null = true;break;
                            }
                        }
                        if(_null === true) {
                            new AlertBox({content:_msg,type:'tip',site:'bottom',time:2000}).show();
                            inputobj.focus();
                            return false;
                        }
                        /*end*/

                        var text = btn.text();
                        btn.prop('disabled', true).addClass('weui_btn_disabled').text(text+'中...');
                        pageloading('登录中...');
                    },
                    success: function(result){
                        removeload();
                        var text = btn.text();
                        btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                        if(result.status==1 || result.status > 1){
                            var reload_url = result.data;
                            new AlertBox({content:result.message,type:'tip',site:'bottom',dourl:reload_url,time:100}).show();
                        }else if(!result.status || result.status==-1){
                            new AlertBox({content:result.message,type:'info',site:'topmid'}).show();
                        }
                    },
                    error:function () {
                        removeload();
                        var text = btn.text();
                        btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                        new AlertBox({content:'网络异常,请求失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
                    }
                });
            });
            /* 提交信息 END */
        })
    </script>
</body>
</html>
