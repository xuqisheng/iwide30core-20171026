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
    <title>会员卡注册</title>
    <link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/mycss.css");?>" rel="stylesheet">
    <script src="<?php echo base_url("public/member/phase2/scripts/jquery.js");?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
    <script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
</head>
<body>
<form id="loginSave" action="<?php echo base_url("index.php/membervip/reg/savereg");?>" method="post" >
    <input type="hidden" name="smstype" value="0" />
    <div class="list_style bd_bottom">
        <?php if(!empty($login_config) && is_array($login_config)):?>
            <?php foreach ($login_config as $key=>$vo):?>
                <?php if($vo['show']=='1' && $key!='phonesms' && $key!='sex'&&$key!='birthday'):?>
                    <div class="input_item <?=$key?>">
                        <div><?=$vo['name'];?></div>
                        <div><input pattern="<?php if($vo['check']=='1'):?><?=$vo['regular'];?><?php endif;?>" placeholder="<?=$vo['note'];?>" type="<?=$vo['type'];?>" name="<?=$key?>" data-name="<?php echo $vo['name'];?>" data-check="<?php echo $vo['check']; ?>" /></div>
                    </div>
                <?php endif;?>
                <?php if($vo['show']=='1' && $key=='sex'):?>
                <div class="input_item <?=$key?>">
                    <div><?php echo $vo['name']; ?></div>
                    <div class="select_sex"><select class="weui_input select_sex" name="sex" ><option value="1" >男</option><option value="2" >女</option></select></div>
                </div>
                <?php endif;?>
                <?php if($vo['show']=='1' && $key=='birthday'){ ?>
             <div class="webkitbox justify birthday arrow" style="position:relative">
          <div><?php echo $vo['name']; ?></div>
            <input name="birthday" class="diydate"  type="date"  type="text" value="<?php echo date('Y-m-d',time()); ?>" pattern="" >
        </div>
        <?php }?>
                <?php if($vo['show']=='1' && $key=='phonesms'):?>
                    <div class="input_item <?=$key?>">
                        <div>验证码</div>
                        <div><input type="<?=$vo['type'];?>" pattern="<?php if($vo['check']=='1'):?><?=$vo['regular'];?><?php endif;?>" placeholder="<?=$vo['note'];?>" name="<?=$key?>" data-name="<?php echo $vo['name'];?>" data-check="<?php echo $vo['check']; ?>" /></div>
           				 <div><span class="smsSend send_out" data-val="0">获取验证码</span></div>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        <?php endif;?>
        <?php if (isset($inter_id) && $inter_id == 'a421641095') { ?>
            <div class="input_item audit">
                <div>我是</div>
                <div><input disabled type="hidden" name="audit" value="2"/>
                    <label><input type="radio" id="member"  name="member_type" value="97" checked/>&nbsp;员工</label>
                    <label><input type="radio" id="owners" disabled name="member_type" value="98"/>&nbsp;业主</label>
                </div>
            </div>
            <div class="input_item company_name">
                <div>公司名称</div>
                <div><input disabled type="text" pattern="^[\u4E00-\u9FA5a-zA-Z\d]+$" placeholder="请输入公司名称" name="company_name"/></div>
            </div>
            <div class="input_item employee_id">
                <div>员工号</div>
                <div><input disabled type="text" pattern="^[A-Za-z0-9]{2,}$" placeholder="请输入员工号" name="employee_id"/></div>
            </div>
        <?php } ?>
    </div>
    <?php if ($inter_id=='a491796658'){?>
    <div style="padding:5px 10px;">姓名、生日一旦确认，将不可更改，并作为您的身份识别标志，请确认填写</div>
    <?php }?>
    <div class="sign_btn bg_main">注册</div>
    <?php if (isset($inter_id) && $inter_id == 'a421641095'): ?>
        <div class="bg_main company-employee">
            <button type="button" class="bg_main pad12 bdradius" href="javascript:;" class="weui_btn_plain_primary">我是业主/员工</button>
        </div>
    <?php endif; ?>
    <div class="sign_list center C_b5b5b5">已有账户?<a class="" href="<?php echo base_url('index.php/membervip/login')?>">去<?php echo $inter_id=='a491796658'? '绑定':'登录'?></a></div>
</form>
<script type="text/javascript">
    var inter_id = "<?php echo $inter_id;?>";
    //通用JS
    $(function(){
        var postUrl;
        /*60S等待发送短信 START*/
        //发送短息
        var countdown = 60;
        $('.smsSend').click(function(){
            var timestr = $('.smsSend').attr('data-val');
            if(timestr==0){
                var tel=$("input[name='phone']").val(),phonesms=$("input[name='phonesms']").val(),smstype=$("input[name='smstype']").val();
                var regular = new RegExp($("input[name='phone']").attr('pattern'));
                if(!tel || tel==''){
                    $.MsgBox.Alert('请输入手机号码');return false;
                }else if(!regular.test(tel)){
                    $.MsgBox.Alert('请输入正确的手机号码');return false;
                }
                $('.smsSend').addClass('C_b5b5b5');

                //请求发送验证码
                var postUrl = "<?php echo base_url("index.php/membervip/sendsms");?>";
                var datas = {phone:tel,phonesms:phonesms,smstype:smstype};
                $.ajax({
                    url:postUrl,
                    type:'POST',
                    data:datas,
                    dataType:'json',
                    timeout:6000,
                    success: function (result) {
                        if(result.err=='0'){
                            $.MsgBox.Alert('短信已发送,请注意查收!');
                        }else if(result.err != '0' && result.msg !='' && result.msg != undefined){
                            $.MsgBox.Alert('result.msg');
                        }
                        $('.smsSend').removeClass('C_b5b5b5');
                        $('.smsSend').html('重新获取')
                    },
                    error: function () {
                        $.MsgBox.Alert('发送失败,请刷新重试或联系管理员!');
                    }
                });
                Timeing();
            }else{
                $.MsgBox.Alert('请在'+countdown+'秒后点击获取');
            }
            $('.smsSend').attr('data-val',1);
        });
        function Timeing(){
            if (countdown == 0) {
                $('.smsSend').html('获取验证码');
                countdown = 60;
                $('.smsSend').attr('data-val',0);
            } else {
                $('.smsSend').html("重新获取" + countdown + "S");
                countdown--;
                setTimeout(function() {
                    Timeing();
                },1000)
            }
        }
        /*60S等待发送短信 END*/

        /* 检测用户输入的是否合法 START */
        $("input").change(function(){
            var regular = new RegExp($(this).attr('pattern'));
            var inputValue = $(this).val();
            var inputName = $(this).attr('name');
            if(!regular.test(inputValue)){
                $("."+inputName+"").addClass('warn');
                $(".sign_btn").addClass('disabled');
            }else{
                $("."+inputName+"").removeClass('warn');
                $(".sign_btn").removeClass('disable');
            }
        });
        //失去焦点判断
        $("input").focusout(function(){
            var regular = new RegExp($(this).attr('pattern'));
            var inputValue = $(this).val();
            var inputName = $(this).attr('name');
            if(!regular.test(inputValue)){
                $("."+inputName+"").addClass('warn');
                $(".sign_btn").addClass('disable');
            }else{
                $("."+inputName+"").removeClass('warn');
                $(".sign_btn").removeClass('disable');
            }
        });
        /* 检测用户输入的是否合法 END */
        //提交JS
        /* 提交信息 START */
        $('.sign_btn').click(function(){
            var form = $("#loginSave"),form_url=form.attr("action"),btn = $(this),loadtip=null;
            form.ajaxSubmit({
                url:form_url,
                dataType:'json',
                timeout:20000,
//                    clearForm:true,
//                    resetForm:true,
                beforeSubmit: function(arr, $form, options){
                    /*验证提交数据*/
                    var _null = false, _msg = '',inputobj=false;
                    for(i in arr){
                        var name = arr[i].name,value=$.trim(arr[i].value),obj=$("input[name='"+name+"']"),check=obj.data("check");
                        var regular = new RegExp(obj.attr('pattern'));
                        if(!value) {
                            inputobj = obj;
                            var text_name = obj.data("name");
                            _msg='请输入'+text_name;
                            _null = true;break;
                        }else if(!regular.test(value) && check==1){
                            inputobj = obj;
                            var text_name = obj.data("name");
                            _msg=text_name+'不合法';
                            _null = true;break;
                        }
                    }

                    if(_null === true) {
                        $.MsgBox.Alert(_msg);
                        $(inputobj).focus();
                        return false;
                    }
                    /*end*/
                    pageloading();
                },
                success: function(result){
                    removeload();
                    var text = btn.text();
                    btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                    if(result.err>1){
                        $.MsgBox.Alert(result.msg);
                    }else if(result.err=='0'){
                        if(inter_id=='a421641095' && result.is_package=='1'){
                            handle_send_tmp();
                        }
                        <?php if(isset($_GET['redir']) && !empty($_GET['redir'])){ ?>
                        var locat_url="<?php echo urldecode($_GET['redir']);?>";
                        <?php }else{?>
                        var locat_url="<?php echo base_url('index.php/membervip/center');?>";
                        <?php } ?>

                        $.MsgBox.Alert(result.msg,function(){window.location.href=locat_url;});
                    }
                },
                error:function () {
                    removeload();
                    $.MsgBox.Alert('网络异常,请求失败,请刷新重试或联系管理员!');
                }
            });
        });
        /* 提交信息 END */

        $(document).on('click','.company-employee',function (e) {
            e.preventDefault();
            var obj=$('.is-member_type'),show=obj.data("show"),text=$(this).find('a').text();
            if(obj.length>0 && show==0){
                obj.show();
                obj.data("show",1);
                $(this).find('a').text(text.replace('是', '不是'));
                obj.find("input[type='text']").val('');
                obj.find('input').prop('disabled',false);
                $(this).find('a').addClass('warn');
            }else{
                obj.hide();
                obj.data("show",0);
                $(this).find('a').text(text.replace('不是', '是'));
                obj.find("input[type='text']").val('');
                obj.find('input').prop('disabled',true);
                $(this).find('a').removeClass('warn');
            }
        });
    });

    function handle_send_tmp() {
        var name = $("input[name='name']").val(),post_url="<?php echo site_url('membervip/reg/send_tmp_msg');?>";
        $.ajax({
            url:post_url,
            type:'get',
            data:{name:name},
            dataType:'json',
            timeout:20000,
            success: function (result) {
                console.log(result);
            },
            error: function (XMLHttpRequest, textStatus) {
                console.log(XMLHttpRequest);
                console.log(textStatus);
//                new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
            }
        });
    }
</script>
</body>
</html>

