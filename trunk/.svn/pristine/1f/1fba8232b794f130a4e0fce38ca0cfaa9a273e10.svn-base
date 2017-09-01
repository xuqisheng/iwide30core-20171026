<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<script type="text/javascript">
    //全局变量
    var GV = {
        DIMAUB: "<?php echo base_url();?>",
        JS_ROOT: "<?php echo FD_PUBLIC ?>/js/",
    };
</script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/wind.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php 
/* 顶部导航 */
echo $block_top;
?>

<?php 
/* 左栏菜单 */
echo $block_left;
?>
<style>
    html, body{min-width: 100%;}
.iconfont{
  font-family:"iconfont" !important;
  font-size:16px;font-style:normal;
  -webkit-font-smoothing: antialiased;
  -webkit-text-stroke-width: 0.2px;
  -moz-osx-font-smoothing: grayscale;
}
.over_x{width:100%;overflow-x:auto;}
.w_450{width:450px !important;border:1px solid #d7e0f1;}
.bg_fff{background:#fff;}
.bg_3f51b5{background:#3f51b5;}
.bg_ff503f{background:#ff503f;}
.bg_4caf50{background:#4caf50;}
.relative{position:relative;}
.absolute{position:absolute;}
.clearfix:after{content: "" ;display:block;height:0;clear:both;visibility:hidden;}
.display_none{display:none !important;}
.m_b_20{margin-bottom:20px;}
.float_left{float:left;}
.content-wrapper{color:#7e8e9f;}
.p_0_20{padding:0 20px;}
textarea{border:1px solid #d7e0f1;}
.banner{height:50px;width:100%;line-height:50px;border-bottom:1px solid #d7e0f1;}
.contents{padding:10px 20px 20px 20px;}
.contents_list{display:table;width:100%;border:1px solid #d7e0f1;margin-bottom:10px;}
.hotel_star >div:nth-of-type(2) >div,.con_right >div >div{display:inline-block;}
.con_left{width:150px;text-align:center;border-right:1px solid #d7e0f1;display:table-cell;vertical-align:middle;}
.con_right{padding:20px 0 20px 0px;}
.con_right>div{margin-bottom:12px;}
.con_right >div >div:nth-of-type(1){width:115px;height:30px;line-height:30px;text-align:center;}
.input_txt{height:30px;line-height:30px;}
.input_txt >input{height:30px;line-height:30px;border:1px solid #d7e0f1;width:450px;text-indent:3px;}
.input_txt >select{height:30px;line-height:30px;display:inline-block;border:1px solid #d7e0f1;background:#fff;margin-right:20px;padding:0 8px;}
.input_radio >div{margin-right:8px;}
.input_radio >div >input{display:none;}
.input_radio >div >input+label{font-weight:normal;text-indent:25px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/radio1.png) no-repeat center left;background-size:13%;width:170px;height:30px;line-height:30px;}
.input_radio >div >input:checked+label{background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/radio2.png) no-repeat center left;background-size:13%;}
.block{display:inline-block;height:18px;width:4px;vertical-align: middle;margin-right:5px;}
.introduce{width:450px;height:150px;margin-left:4px;resize:vertical;}
.add_img{width:77px;height:77px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/214598012363739107.png) no-repeat;background-size:100%;margin-right:20px;float:left;}

.input_checkbox >div >input{display:none;}
.input_checkbox >div >input+label{font-weight:normal;text-indent:25px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/bg.png) no-repeat center left;background-size:15%;width:110px;height:30px;line-height:30px;}
.input_checkbox >div >input:checked+label{background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/bg2.png) no-repeat center left;background-size:15%;}

.fom_btn{background:#ff9900;color:#fff;outline:none;border:0px;padding:6px 25px;border-radius:3px;margin:auto;display:block;}
.add_img_box:hover > .img_close{display:block !important;cursor:pointer;}
#file >input{text-indent:-9999px; height:80px;line-height:60px;width:80px;background-image:url("<?php echo base_url(FD_PUBLIC) ?>/js/img/upload.png");}
.f_l{float:left;}
.block_list{margin-left:4px;}
.block_list>div{margin-bottom:10px;}
.block_list>div:last-chlid{margin-bottom:0px;}
.clearfix:after{content:" ";display:block;clear:both;height:0;}
.btn_number+label{width:70px !important;background-size:30% !important;}
.btn_number:checked+label{background-size:30% !important;}
.btn_number+label+div{display:none}
.btn_number:checked+label+div{display:inline-block;}
.w_450 .dropdown-toggle{background:#fff;border:0px;}
</style>
<div class="over_x">
    <div class="content-wrapper">
        <div class="banner bg_fff p_0_20">新增规则</div>
        <div class="contents">
            <?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
            <?php
            if(!empty($card_rule_id))
                require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'memberservicerule'. DS .'tpl'. DS .'edit.php';
            else
                require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'memberservicerule'. DS. 'tpl'. DS .'add.php';
            ?>
            <div class="bg_fff" style="padding:15px;">
                <button type="submit" class="fom_btn dosave">保存规则</button>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>

<?php 
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>
<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>
</div><!-- ./wrapper -->
<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/areaData.js"></script>
<script type="text/javascript">
$(function () {
    $('#file').uploadify({
        'formData'     : {
            '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
            'timestamp' : '<?=time();?>',
            'token'     : '<?php echo md5('unique_salt' . time());?>'
        },
        'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
        //'uploader' : '<?php echo site_url("basic/upload/hotel_upload") ?>',
        'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
        'file_post_name': 'imgFile',
        'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/upload.png",
        'height':77,
        'width':77,
        'fileSizeLimit':'200', //限制文件大小
        'onUploadSuccess' : function(file, data, response) {
            var res = $.parseJSON(data);
            $('#el_intro_img').val(res.url);
            $('.add_img_box').remove();
            $(".file_img_list").prepend($('<div class="add_img_box" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="'+res.url+'"/><div class="img_close" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div></div>'));
            $('.add_img_box').delegate('.img_close','click',function(){
                $(this).parent().remove();
                $("#el_intro_img").val('');
            })

        }
    });
    $('.add_img_box').delegate('.img_close','click',function(){
        $(this).parent().remove();
        $("#el_intro_img").val('');
    });

    $('.star_77,.star_66').change(function(){
        if($('.star_77:checked').val()){
            $('.b_week').show();
            $('.b_s_data').hide();
            $("select[name=package_id]").prop('disabled', false);
            $("select[name=card_id]").prop('disabled', true);
            $("select[name=package_id]").val("");
        }else{
            $('.b_week').hide();
            $('.b_s_data').show();
            $("select[name=package_id]").prop('disabled', true);
            $("select[name=card_id]").prop('disabled', false);
            $("select[name=card_id]").val("");
        }
    });

    $(document).on("click",".channel",function (e) {
        if(this.value=='gaze'){
            $(".thumb-banner").show();
            $("input[name=banner]").prop('disabled', false);
        }else{
            $(".thumb-banner").hide();
            $("input[name=banner]").prop('disabled', true);
        }

    });


    Wind.use("ajaxForm","artDialog", function () {
        $(document).on('click', '.dosave', function (e) {
            e.preventDefault();
            var _this = this, ok_url = "<?php echo EA_const_url::inst()->get_url('*/*/');?>", btn = $(this);
            var form = $('.form-horizontal'), form_url = form.attr("action");
            //ie处理placeholder提交问题
            if ($.support.msie) {
                form.find('[placeholder]').each(function () {
                    var input = $(this);
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });
            }

            form.ajaxSubmit({
                url: form_url,
                dataType: 'json',
                beforeSubmit: function (arr, $form, options) {
                    /*验证提交数据*/
                    var _null = false, _msg = '', inputos = document.getElementsByTagName('input'), selectos = document.getElementsByTagName('select'), _inputo = null;
                    for (i in inputos) {
                        var name = inputos[i].name, value = $.trim(inputos[i].value);
                        _inputo = inputos[i];
                        switch (name) {
                            case 'rule_title':
                                if (!value && inputos[i].disabled === false) {
                                    _null = true;
                                    _msg = '请填写规则名称';
                                }
                                break;
                            case 'frequency':
                                if (!value && inputos[i].disabled === false) {
                                    _null = true;
                                    _msg = '请填写限领次数';
                                }
                                break;
                        }
                        if (_null === true) break;
                    }

                    if (_null === false) {
                        for (i in selectos) {
                            var name = selectos[i].name, value = $.trim(selectos[i].value);
                            _inputo = selectos[i];
                            switch (name) {
                                case 'package_id':
                                    if (!value && selectos[i].disabled === false) {
                                        _null = true;
                                        _msg = '请选择礼包';
                                    }
                                    break;
                                case 'card_id':
                                    if (!value && selectos[i].disabled === false) {
                                        _null = true;
                                        _msg = '请选择卡券';
                                    }
                                    break;
                            }
                            if (_null === true) break;
                        }
                    }

                    if (_null === true) {
                        $(_inputo).focus();
                        return false;
                    }
                    /*end*/

                    var text = btn.text();
                    btn.prop('disabled', true).addClass('disabled').text(text + '中...');
                },
                success: function (data) {
                    if (data.status == 1) {
                        var btnval = data.data.isadd === false ? '编辑' : '添加';
                        art.dialog({
                            title: '提示',
                            fixed: true,
                            icon: 'succeed',
                            content: data.message,
                            okVal: "返回列表页",
                            cancelVal: '继续' + btnval,
                            ok: function () {
                                window.location.href = ok_url;
                            },
                            cancel: true,
                            close: function () {
                                $(_this).focus(); //关闭时让触发弹窗的元素获取焦点
                                return true;
                            },
                        });
                    } else {
                        btn.parent().append("<span style='color: #ff0040;'>" + data.message + "</span>");
                        setTimeout(function () {
                            btn.parent().find('span').fadeOut('normal', function () {
                                btn.parent().find('span').remove();
                            });
                        }, 3000);
                    }
                },
                complete: function () {
                    var text = btn.text();
                    btn.prop('disabled', false).removeClass('disabled').text(text.replace('中...', ''));
                },
                error: function () {
                    btn.parent().append("<span style='color: #ff0040;'>请求失败,可能权限不够!</span>");
                    setTimeout(function () {
                        btn.parent().find('span').fadeOut('normal', function () {
                            btn.parent().find('span').remove();
                        });
                    }, 3000);
                }
            });
        });
    });
});
</script>
</body>
</html>
