<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>会员注册</title>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/css/alert.css");?>"/>
    <script src="<?php echo base_url("public/member/version4.0/js/jquery-1.11.0.min.js");?>"></script>
    <script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/zepto.min.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/example.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/js/login.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("public/member/version4.0/js/alert.js");?>"></script>
    <style type="text/css">
        .company-employee{margin-top: 10px;}.is-member_type{display: none;}
        .hd{padding: 0;}
        input[disabled]{color:#555;opacity:1}
    </style>
</head>
<body ontouchstart>
    <div class="vip_content" style="display:none;">
        <div class="hd">
            <h3 class="page_title"></h3>
        </div>
        <!--FROM DATA START-->
        <form id="loginSave" action="<?php echo base_url("index.php/membervip/reg/savereg");?>" method="post" >
            <input type="hidden" name="smstype" value="0" />
            <input type="hidden" name="salesId" value="<?php echo $sales_id;?>" />
        <div class="bd spacing">
            <div class="weui_cells weui_cells_form">
                <?php if($login_config['name']['show']){ ?>
                <div class="weui_cell name">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $login_config['name']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $login_config['name']['type']; ?>" name="name" pattern="<?php echo $login_config['name']['regular']; ?>"  placeholder="<?php echo $login_config['name']['note']; ?>"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($login_config['sex']['show']){ ?>
                <div class="weui_cell sex">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $login_config['sex']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <select class="weui_input" name="sex" >
                            <option value="3" >请选择</option>
                            <option value="2" >女</option>
                            <option value="1" >男</option>
                        </select>
                    </div>
                </div>
                <?php }?>
                <?php if($login_config['phone']['show']){ ?>
                <div class="weui_cell phone">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $login_config['phone']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $login_config['phone']['type']; ?>" name="phone" pattern="<?php echo $login_config['phone']['regular']; ?>"  placeholder="<?php echo $login_config['phone']['note']; ?>" />
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($login_config['birthday']['show']){ ?>
                <div class="weui_cell phone">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $login_config['birthday']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                    <div class="webkitbox justify birthday arrow" style="position:relative;padding: 0px;">
                        <input name="birthday" class="diydate" disabled type="text" placeholder="请选择<?php echo $vo['name']; ?>""   data-name="会员生日" type="text" style="width: 100%;height: 45px;padding: 12px 10px 12px 0;">
                   </div>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($login_config['password']['show']){ ?>
                <div class="weui_cell password">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $login_config['password']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $login_config['password']['type']; ?>" pattern="<?php echo $login_config['password']['regular']; ?>"  placeholder="<?php echo $login_config['password']['note']; ?>" name="password"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($login_config['email']['show']){ ?>
                <div class="weui_cell email">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $login_config['email']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $login_config['email']['type']; ?>" pattern="<?php echo $login_config['email']['regular']; ?>"  placeholder="<?php echo $login_config['email']['note']; ?>" name="email"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($login_config['idno']['show']){ ?>
                <div class="weui_cell idno">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $login_config['idno']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $login_config['idno']['type']; ?>" pattern="<?php echo $login_config['idno']['regular']; ?>"  placeholder="<?php echo $login_config['idno']['note']; ?>" name="idno"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($login_config['phonesms']['show']){  ?>
                    <div class="weui_cell phonesms">
                        <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="<?php echo $login_config['phonesms']['type']; ?>" pattern="<?php echo $login_config['phonesms']['regular']; ?>" placeholder="<?php echo $login_config['phonesms']['note']; ?>"  name="phonesms" />
                        </div>
                        <div class="weui_cell_ft">
                            <i class="weui_icon_warn"></i>
                        </div>
                        <div class="weui_cell_ft">
                            <a href="javascript:;" data-val='0' style="width:auto;padding:2px 6px; font-size:10px; line-height:1.5" class="weui_btn  weui_btn_plain_default smsSend">获取验证码</a>
                        </div>
                    </div>
                <?php }?>
                <?php if(isset($inter_id) && $inter_id == 'a421641095'){  ?>
<!--                     <div class="weui_cell weui_vcode smspic"> -->
<!--                         <div class="weui_cell_hd"><label class="weui_label">验证码</label></div> -->
<!--                         <div class="weui_cell_bd weui_cell_primary"> -->
<!--                             <input class="weui_input" type="pic_code" pattern="^[A-Za-z0-9]{4}$" name="smspic" placeholder="请输入图片验证码"/> --> 
<!--                         </div> -->
<!--                         <div class="weui_cell_ft"> -->
<!--                             <i class="weui_icon_warn"></i> -->
<!--                         </div> -->
<!--                         <div class="weui_cell_ft"> -->
                       <!--      <img src="./reg/pic_code" onClick="this.src='./reg/pic_code';" alt="点击刷新图片" title="点击刷新图片"> -->
<!--                         </div> -->
<!--                     </div> -->
                    <div class="is-member_type" data-show="0">
                        <input class="weui_input" disabled type="hidden" name="audit" value="2"/>
                        <div class="weui_cell idno">
                            <div class="weui_cell_hd"><label for='' class="weui_label">我是</label></div>
                            <div class="weui_cell_bd weui_cell_primary">
                                <label><input type="radio" class="select_mode" data-mode="1" id="member" disabled name="member_type" value="97" checked />&nbsp;员工</label>
                                &nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" class="select_mode" data-mode="2" id="owners" disabled name="member_type" value="98" />&nbsp;业主</label>
                            </div>
                            <div class="weui_cell_ft">
                                <i class="weui_icon_warn"></i>
                            </div>
                        </div>
                        <div class="weui_cell idno">
                            <div class="weui_cell_hd"><label for='' class="weui_label">公司名称</label></div>
                            <div class="weui_cell_bd weui_cell_primary">
                                <input class="weui_input" disabled type="text" pattern="^[\u4E00-\u9FA5a-zA-Z\d]+$"  placeholder="请输入公司名称" name="company_name"/>
                            </div>
                            <div class="weui_cell_ft">
                                <i class="weui_icon_warn"></i>
                            </div>
                        </div>
                        <div class="weui_cell idno">
                            <div class="weui_cell_hd"><label for='' class="weui_label owner_title">员工号</label></div>
                            <div class="weui_cell_bd weui_cell_primary">
                                <input class="weui_input owner_input" disabled type="text" pattern="^[A-Za-z0-9]{2,}$"  placeholder="请输入员工号" name="employee_id"/>
                            </div>
                            <div class="weui_cell_ft">
                                <i class="weui_icon_warn"></i>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
            <div class="weui_cells_title"></div>
            <div class="bd spacing">
                <a href="javascript:;" class="weui_btn weui_btn_primary">注册</a>
            </div>
            <?php if(isset($inter_id) &&  $inter_id == 'a421641095'):?>
                <div class="bd spacing company-employee">
                    <a href="javascript:;" class="weui_btn weui_btn_plain_primary">我是业主/员工</a>
                </div>
            <?php endif;?>
        </div>
        </form>

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
    <!--end END-->
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
            <div class="weui_dialog_bd">当前账号或密码错误</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog primary primary_jump ">确定</a>
            </div>
        </div>
    </div>
    <!--dialog end -->
    <script type="text/javascript">
        var inter_id = "<?php echo $inter_id;?>";

        //更还业主／员工模式
        $(document).on('click','.select_mode',function () {
            var mode = $(this).data('mode');
            if(mode==1){
                $(".owner_title").html("员工号");
                $(".owner_input").attr("placeholder","请输入员工号");
            }else if(mode==2){
                $(".owner_title").html("业主证号");
                $(".owner_input").attr("placeholder","请输入业主证号");
            }
        });

        //通用JS
        $(function(){
            /* 等待加载 START */
            $('.vip_content').attr('style',"");
            $("#loadingToast").attr('style',"display:none;");
            /* 等待加载 END */
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
                        new AlertBox({content:'请输入手机号码',type:'tip',site:'bottom'}).show();return false;
                    }else if(!regular.test(tel)){
                        new AlertBox({content:'请输入正确的手机号码',type:'tip',site:'bottom'}).show();return false;
                    }

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
                                new AlertBox({content:'短信已发送,请注意查收!',type:'tip',site:'bottom'}).show();
                            }else if(result.err != '0' && result.msg !='' && result.msg != undefined){
								if(result.err == '2019'){
									new AlertBox({content:result.msg,type:'confirm',site:'topmid',okVal:'登录',cancelVal:'关闭',dourl:"<?php echo base_url('index.php/membervip/login').'?redir='.$redir;?>",
		                        		ok:function () {	var locat_url = '<?php echo base_url('index.php/membervip/login').'?redir='.$redir;?>';location.href = locat_url;}
		                             }
		                              ).show();
		                              return false;
									}
                                
                                new AlertBox({content:result.msg,type:'tip',site:'bottom'}).show();
                            }
                        },
                        error: function () {
                            new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
                        }
                    });
                    Timeing();
                }else{
                    new AlertBox({content:'请在'+countdown+'秒后点击获取',type:'tip',site:'mid'}).show();
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
                    $("."+inputName+"").addClass('weui_cell_warn');
                    $(".weui_btn_primary").addClass('weui_btn_disabled');
                }else{
                    $("."+inputName+"").removeClass('weui_cell_warn');
                    $(".weui_btn_primary").removeClass('weui_btn_disabled');
                }
            });
            //失去焦点判断
            $("input").focusout(function(){
                var regular = new RegExp($(this).attr('pattern'));
                var inputValue = $(this).val();
                var inputName = $(this).attr('name');
                if(!regular.test(inputValue)){
                    $("."+inputName+"").addClass('weui_cell_warn');
                    $(".weui_btn_primary").addClass('weui_btn_disabled');
                }else{
                    $("."+inputName+"").removeClass('weui_cell_warn');
                    $(".weui_btn_primary").removeClass('weui_btn_disabled');
                }
            });
            /* 检测用户输入的是否合法 END */
            //提交JS
            /* 提交信息 START */
            $('.weui_btn_primary').click(function(){
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
                            var name = arr[i].name,value=$.trim(arr[i].value);
                            if(name == 'name' && !value) {
                                _null = true; _msg = '请输入真实姓名!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'email' && !value) {
                                _null = true; _msg = '请输入邮箱!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'phone' && !value) {
                                _null = true; _msg = '请输入手机号码!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'password' && !value) {
                                _null = true; _msg = '请输入密码!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'phonesms' && !value) {
                                _null = true; _msg = '请输入手机验证码!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'idno' && !value) {
                                _null = true; _msg = '请输入证件号码!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'company_name' && !value) {
                                _null = true; _msg = '请输入公司名称!';inputobj=$("input[name='"+name+"']");break;
                            }
                            if(name == 'birthday' && !value) {
                                _null = true; _msg = '请输入会员生日!';inputobj=$("input[name='"+name+"']");break;
                            }
                            if(name == 'employee_id' && !value) {
                                _null = true; _msg = '请输入员工号码!';inputobj=$("input[name='"+name+"']");break;
                            }
                        }

                        if(_null === true) {
                            new AlertBox({content:_msg,type:'tip',site:'bottom',time:2000}).show();
                            $(inputobj).focus();
                            return false;
                        }
                        /*end*/

                        var text = btn.text();
                        btn.prop('disabled', true).addClass('weui_btn_disabled').text(text+'中...');
                        loadtip = new AlertBox({content:'注册中',type:'loading',site:'topmid'}).show();
                    },
                    success: function(result){
                        if(loadtip) loadtip.closedLoading();
                        var text = btn.text();
                        btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                        if(result.err>1){
                        	if(result.err== 2019){
								new AlertBox({content:result.msg,type:'confirm',site:'topmid',okVal:'登录',cancelVal:'关闭',dourl:"<?php echo base_url('index.php/membervip/reg').'?redir='.$redir;?>",
	                        		ok:function () {	var locat_url = '<?php echo site_url('membervip/login').'?redir='.$redir;?>'; location.href = locat_url;}
	                             }
	                              ).show();
	                              return false;
								}
                            new AlertBox({content:result.msg,type:'info',site:'topmid'}).show();
                        }else if(result.err=='0'){
                            if(inter_id=='a421641095' && result.is_package=='1'){
                                handle_send_tmp();
                            }

                            var locat_url="<?php echo $succ_url;?>";
                            new AlertBox({content:result.msg,type:'tip',site:'bottom',dourl:locat_url,time:100}).show();
                        }
                    },
                    error:function () {
                        if(loadtip) loadtip.closedLoading();
                        var text = btn.text();
                        btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                        new AlertBox({content:'网络异常,请求失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
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
                    $(this).find('a').removeClass('weui_btn_plain_primary');
                    $(this).find('a').addClass('weui_btn_warn');
                }else{
                    obj.hide();
                    obj.data("show",0);
                    $(this).find('a').text(text.replace('不是', '是'));
                    obj.find("input[type='text']").val('');
                    obj.find('input').prop('disabled',true);
                    $(this).find('a').removeClass('weui_btn_warn');
                    $(this).find('a').addClass('weui_btn_plain_primary');
                }
            });
        });
        $(".birthday").on("click",function(){
            if($('.diydate').attr('type') == 'text') {
                $('.diydate').removeAttr("disabled").attr('type','date').focus().click()
                setTimeout(function(){
                    $('.diydate').focus().click()
                },100)
            }
        })
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
