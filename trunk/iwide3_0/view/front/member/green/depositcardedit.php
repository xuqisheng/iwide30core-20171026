<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>购卡信息</title>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/green.css");?>" rel="stylesheet">
    <script src="<?php echo base_url("public/member/phase2/scripts/jquery.js");?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
    <script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
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
</head>
<body ontouchstart>
    <div class="vip_content" style="display:none;">
        <div class="hd">
            <h3 class="page_title">购卡信息</h3>
        </div>
        <!--FROM DATA START-->
        <form id="createOrder" action="<?php echo base_url("index.php/membervip/depositcard/save_order?cardId=".$card_id);?>" method="post" >
            <input type="hidden" name="pay_type" value="<?=$pay_type?>">
        <div class="bd spacing">
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell name">
                    <div class="weui_cell_hd"><label for='' class="weui_label">用户姓名</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="text" value="<?php echo $info['name'] ?>" name="name" pattern="^[\u4E00-\u9FA5a-zA-Z]+$"  placeholder="请输入用户姓名"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
            </div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell name">
                    <div class="weui_cell_hd"><label for='' class="weui_label">用户手机</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="tel" value="<?php echo $info['cellphone'] ?>" name="phone" pattern="^[1][35789][0-9]{9}$"  placeholder="请输入用户手机"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
            </div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell name">
                    <div class="weui_cell_hd"><label for='' class="weui_label">证件号码</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="text" value="<?php echo $info['id_card_no'] ?>" name="idno" pattern="^[0-9Xx]{18}$"  placeholder="请输入证件号码"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
            </div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell name">
                    <div class="weui_cell_hd"><label for='' class="weui_label">分销号码</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="tel"  name="distribution_num"   placeholder="请输入分销号码(选填)"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
            </div>
            <div class="weui_cells_title"></div>
            <div class="bd spacing">
                <a href="javascript:;" class="weui_btn weui_btn_primary">提交</a>
            </div>
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
                <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <!--dialog end -->
    <script type="text/javascript">
        //通用JS
        $(function(){
            /* 等待加载 START */
            $('.vip_content').attr('style',"");
            $("#loadingToast").attr('style',"display:none;");
            /* 等待加载 END */
            var postUrl;
            /* 检测用户输入的是否合法 START */
            $("input").keyup(function(){
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

            /* 提交信息 START */
            $('.weui_btn_primary').click(function(){
                var form = $("#createOrder"),form_url=form.attr("action"),btn = $(this),loadtip=null;
                form.ajaxSubmit({
                    url:form_url,
                    dataType:'json',
                    timeout:20000,
                    beforeSubmit: function(arr, $form, options){
                        /*验证提交数据*/
                        var _null = false,
                            _msg = '',
                            inputos = form.find('input'),
                            _inputo = null;

                        for (i in inputos) {
                            var name = inputos[i].name, value = $.trim(inputos[i].value);
                            _inputo = inputos[i];
                            switch (name) {
                                case 'phone':
                                    if (!value && inputos[i].disabled === false) {
                                        _null = true;
                                        _msg = '请输入手机号码';
                                        break;
                                    }

                                    var regular = new RegExp("^[1][345789][0-9]{9}$");
                                    if(!regular.test(value)){
                                        _null = true;
                                        _msg = '手机号码不合法';
                                    }
                                    break;
                                case 'idno':
                                    if (!value && inputos[i].disabled === false) {
                                        _null = true;
                                        _msg = '请输入证件号码';
                                        break;
                                    }
                                    if(!validateIdCard(value)){
                                        _null = true;
                                        _msg = '身份证格式不正确';
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
                            window.location.href=result.data;
                        }else{
                            $.MsgBox.Alert(result.message);
                        }
                    },
                    error:function () {
                        removeload();
                        $.MsgBox.Alert('网络异常,请求失败,请刷新重试或联系管理员!');
                    }
                });
            });
            /* 提交信息 END */
        });

        /*
         * 身份证15位编码规则：dddddd yymmdd xx p
         * dddddd：6位地区编码
         * yymmdd: 出生年(两位年)月日，如：910215
         * xx: 顺序编码，系统产生，无法确定
         * p: 性别，奇数为男，偶数为女
         *
         * 身份证18位编码规则：dddddd yyyymmdd xxx y
         * dddddd：6位地区编码
         * yyyymmdd: 出生年(四位年)月日，如：19910215
         * xxx：顺序编码，系统产生，无法确定，奇数为男，偶数为女
         * y: 校验码，该位数值可通过前17位计算获得
         *
         * 前17位号码加权因子为 Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2 ]
         * 验证位 Y = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ]
         * 如果验证码恰好是10，为了保证身份证是十八位，那么第十八位将用X来代替
         * 校验位计算公式：Y_P = mod( ∑(Ai×Wi),11 )
         * i为身份证号码1...17 位; Y_P为校验码Y所在校验码数组位置
         */
        function validateIdCard(idCard){
            //15位和18位身份证号码的正则表达式
            var regIdCard=/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/;

            //如果通过该验证，说明身份证格式正确，但准确性还需计算
            if(regIdCard.test(idCard)){
                if(idCard.length==18){
                    var idCardWi=new Array( 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2 ); //将前17位加权因子保存在数组里
                    var idCardY=new Array( 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ); //这是除以11后，可能产生的11位余数、验证码，也保存成数组
                    var idCardWiSum=0; //用来保存前17位各自乘以加权因子后的总和
                    for(var i=0;i<17;i++){
                        idCardWiSum+=idCard.substring(i,i+1)*idCardWi[i];
                    }

                    var idCardMod=idCardWiSum%11;//计算出校验码所在数组的位置
                    var idCardLast=idCard.substring(17);//得到最后一位身份证号码

                    //如果等于2，则说明校验码是10，身份证号码最后一位应该是X
                    if(idCardMod==2){
                        if(idCardLast=="X"||idCardLast=="x"){
//                            alert("恭喜通过验证啦！");
                            return true;
                        }else{
//                            alert("身份证号码错误！");
                            return false;
                        }
                    }else{
                        //用计算出的验证码与最后一位身份证号码匹配，如果一致，说明通过，否则是无效的身份证号码
                        if(idCardLast==idCardY[idCardMod]){
                            return true;
                        }else{
                            return false;
                        }
                    }
                }else{
                    return true;
                }
            }else{
                return false;
            }
        }
    </script>
</body>
</html>
