<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<head>
    <link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet"/>
    <!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        //全局变量
        var GV = {
            DIMAUB: "<?php echo base_url();?>",
            JS_ROOT: "<?php echo FD_PUBLIC ?>/js/",
        };
    </script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/wind.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/content_addtop.js"></script>
    
<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>
<style>
    div[required]>div:first-child:before {
        content: '*';
        color: #f00
    }

    .addimg {
        margin-right: 15px
    }

    .flex_aligntop {
        padding-top: 5px
    }

    .warning {
        color: #f00
    }
    .quantity {position:fixed; width:100%; height:100%; z-index:999999; top:0; left:0;background: rgba(0,0,0,0.5);}
    .quantity .layer{background:#fff; border-radius:5px; margin:auto; width:300px; padding:20px 10px 10px 10px;}
    .quantity .layer>*{margin-bottom:10px}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php /* 顶部导航 */
echo $block_top; ?>

<?php /*左栏菜单*/
echo $block_left; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">

<form id="myForm" action="<?php echo base_url('index.php/membervip/membermodel/custom_edit'); ?>" method="post">
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
       value="<?php echo $this->security->get_csrf_hash(); ?>"/>
<input type="hidden" name="config_id" value="<?php if(isset($config_id)) echo $config_id; ?>" />
<div class="whitetable">
    <div>
        <span style="border-color:#3f51b5">积分</span>
    </div>
    <div class="bd_left list_layout">
        <div style="display: none">
            <div>是否启用栏目</div>
            <div class="flexgrow">
                <label class="check"><input class="" name="credit_use"  type="radio" value="t" <?php if(!isset($credit_use) || $credit_use!= 'f'){?> checked <?php } ?>/><span class="diyradio"><tt></tt></span>启用</label>
                <label class="check"><input class="" name="credit_use"  type="radio" value="f" <?php if(isset($credit_use) && $credit_use == 'f'){?> checked <?php } ?> /><span class="diyradio"><tt></tt></span>不启用</label>
            </div>
            <div class="flexgrow"></div>
        </div>
        <div>
            <div>栏目名称</div>
            <div class="flexgrow" style="max-width:150px;">
                <label class="check"><input class="" name="credit_default_name"  type="radio" value="t" <?php if(!isset($credit['default']) || $credit['default']!= 'f'){?> checked <?php } ?>/><span class="diyradio"><tt></tt></span>积分</label>
                <label class="check"><input class="" name="credit_default_name"  type="radio" value="f" <?php if(isset($credit['default']) && $credit['default']== 'f'){?> checked <?php } ?>/><span class="diyradio"><tt></tt></span>自定义</label>
            </div>
            <div class="">
                <div class="input maright"><input placeholder="名字，最多3个汉字" name="credit_default_name_value" value="<?php if(isset($credit['name']) && !empty($credit['name'])) echo $credit['name'];else echo '积分';?>"  maxlength="8"/></div>
            </div>
            <div class="flexgrow">
                <label>自定义名称限制2-3个汉字或者8个英文字母以内</label>
            </div>
        </div>
    </div>

</div>
    <div class="whitetable">
        <div>
            <span style="border-color:#3f51b5">余额</span>
        </div>
        <div class="bd_left list_layout">
            <div style="display: none">
                <div>是否启用栏目</div>
                <div class="flexgrow">
                    <label class="check"><input class="" name="balance_use"  type="radio" value="t" <?php if(!isset($balance_use) || $balance_use!= 'f'){?> checked <?php } ?>/><span class="diyradio"><tt></tt></span>启用</label>
                    <label class="check"><input class="" name="balance_use"  type="radio" value="f" <?php if(isset($balance_use) && $balance_use == 'f'){?> checked <?php } ?>/><span class="diyradio"><tt></tt></span>不启用</label>
                </div>
                <div class="flexgrow"></div>
            </div>
            <div>
                <div>栏目名称</div>
                <div class="flexgrow"style="max-width:150px;">
                    <label class="check"><input class="" name="balance_default_name"  type="radio" value="t" <?php if(!isset($balance['default']) || $balance['default']!= 'f'){?> checked <?php } ?>/><span class="diyradio"><tt></tt></span>余额</label>
                    <label class="check"><input class="" name="balance_default_name"  type="radio" value="f" <?php if(isset($balance['default']) && $balance['default'] =='f'){?> checked <?php } ?>/><span class="diyradio"><tt></tt></span>自定义</label>
                </div>
                <div class="">
                    <div class="input maright"><input placeholder="最多3个汉字" name="balance_default_name_value" value="<?php if(isset($balance['name']) && !empty($balance['name'])) echo $balance['name']; else echo '余额'?>"  maxlength="8"/></div>
                </div>
                <div class="flexgrow">
                    <label>自定义名称限制2-3个汉字或者8个英文字母以内</label>
                </div>
            </div>
        </div>

    </div>


<div class="bg_fff bd center pad10">
    <button class="bg_main button spaced dosave" type="submit" id="btn_sub"  >保存配置</button>
</div>

</form>

    </section>
</div><!-- /.content-wrapper -->
			</div>
            </div>
            </div>
<!-- ./wrapper -->
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script type="application/javascript">

$(function () {
    Wind.use("ajaxForm", function () {
        $(document).on('click', '.dosave', function (e) {
            $('#btn_sub').attr("disable",true);
            e.preventDefault();
            var form = $('#myForm');
            var form_url = form.attr("action");
//            console.log(form_url);
            form.ajaxSubmit({
                url: form_url,
                dataType: 'json',
                type: 'post',
                beforeSubmit: function (arr) {
//                    console.log(arr);
                    var reg= /^[A-Za-z]+$/;
                    if($('input[name=credit_default_name]')[1].checked){
                       var credit_name = $('input[name="credit_default_name_value"]').val();
                       if (reg.test(credit_name)) { //判断是否符合正则表达式
                                if(credit_name.length > 8){
                                    alert("积分字数不能超过8个英文字");
                                    $('#btn_sub').removeAttr("disable");
                                    return false;
                                }

                       }else{
                           if(credit_name.length > 3){
                               alert("积分字数不能超过3个汉字");
                               $('#btn_sub').removeAttr("disable");
                               return false;
                           }

                           if(credit_name == '储值' || credit_name == '余额'){
                               alert("积分栏目命名不能为"+credit_name);
                               $('#btn_sub').removeAttr("disable");
                               return false;
                           }
                       }
                    }

                    if($('input[name=balance_default_name]')[1].checked){
                        var balance_name = $('input[name="balance_default_name_value"]').val();
                        if (reg.test(balance_name)) { //判断是否符合正则表达式
                            if(balance_name.length > 8){
                                alert("余额字数不能超过8个英文字");
                                $('#btn_sub').removeAttr("disable");
                                return false;
                            }

                        }else{
                            if(balance_name.length > 3){
                                alert("余额字数不能超过3个汉字");
                                $('#btn_sub').removeAttr("disable");
                                return false;
                            }
                            if(balance_name == '积分'){
                                alert("积分栏目命名不能为"+balance_name);
                                $('#btn_sub').removeAttr("disable");
                                return false;
                            }
                        }
                    }



                },
                success: function (data) {
                    console.log(data);

                    if (data.err == 0 && data.errmsg =="ok") {
                        alert('保存成功');
                        window.location.reload();
                    } else if(data.err >0 && data.errmsg !='' ){
                        $('#btn_sub').removeAttr("disable");
                        alert(data.msg);
                    }else{
                        $('#btn_sub').removeAttr("disable");
                        alert('保存失败');
                    }
                },
                error: function (XmlHttpRequest, textStatus, errorThrown) {
                    $('#btn_sub').removeAttr("disable");
                    console.log(textStatus);
                    console.log(XmlHttpRequest);
                    console.log(errorThrown);
                    alert('网络异常');
                }
            });
        });
    });


    $(document).on('click','.activate_type',function(e){
        if(this.value =='wx_activate'){
            $('.activate_select').css("display","");
        }else{
            $('.activate_select').css("display","none")
        }
    });
});
</script>
<?php /* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php'; ?>
</body>
</html>
