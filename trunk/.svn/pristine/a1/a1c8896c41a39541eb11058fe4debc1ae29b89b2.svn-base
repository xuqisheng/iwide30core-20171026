<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
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
<style type="text/css">
    .box-body .table-striped>tbody>tr>td{background: #FFFFFF;vertical-align: middle;text-align: center;}
    .control-group{background: #fff;  padding: 10px; float: left;width: 100%;}
    .controls{float: left;padding: 5px;width: 30%;}.control-input{float: right;}
    .controls span{margin-left: 10%;}.control-group .title{}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php /* 顶部导航 */ echo $block_top; ?>

    <?php /* 左栏菜单 */ echo $block_left; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <small></small>
            </h1>
            <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <style>
            .controls1{float:left;padding:5px;}
            .m_r_75{margin-right:11px;}
            .m_r_20{margin-rgiht:20px;}
            .m_l_10{margin-left:10px;}
            .p_3{padding:3px !important;}
            .width_60{width:60px;}
            .min_w_80{width:100px;}
            .width_120{width:120px;display:inline-block;text-align:left;}
            .width_376{width:376px;}
            .f_notes{font-size:10px;color:#999999;float:left;padding-left:10px;}
        </style>
        <section class="content">
            <?php echo $this->session->show_put_msg(); ?>
            <!-- Horizontal Form -->
            <!--                 <form id="form1"> -->
            <?php echo form_open(EA_const_url::inst()->get_url('*/*/save_viewconf'), array('class'=>'form-inline','enctype'=>'multipart/form-data')); ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">邀请好友显示设置</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                        <tbody>
                        <tr>
                            <td width="10%">邀请好友主页面</td>
                            <td>
                                <div class="control-group p_3">
                                    <div class="controls1">标题</div>
                                    <div class="controls1" style="width: 50%;">
                                        <input type="text" style="width:100%;" name="home[title]"  value="<?=!empty($home['title'])?$home['title']:'';?>" placeholder="请输入标题" />
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">会员等级LOGO</div>
                                    <div class="controls1">
                                        <a href="<?php echo EA_const_url::inst()->get_url('*/*/lvlconf');?>" class="btn btn-default btn-sm minus-labels" target="_blank">设置</a>
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">页面提示</div>
                                    <div class="controls1" style="width: 50%;">
                                        <script type="text/plain" id="pagetip" name="home[pagetip]"><?=!empty($home['pagetip'])?$home['pagetip']:'';?></script>
                                    </div>
                                </div>

                                <div class="control-group p_3">
                                    <div class="controls1">活动规则按钮</div>
                                    <div class="controls1">
                                        <div class="controls1 m_r_75">
                                            <input type="radio" name="activity_rule[button_type]" <?php if(empty($activity_rule['button_type']) || (!empty($activity_rule['button_type']) && $activity_rule['button_type']=='default')):?>checked<?php endif;?> value="default" />
                                            <span class="m_r_20 m_l_10 width_120">查看活动规则</span>
                                        </div>
                                        <div class="controls1">
                                            <input type="radio" name="activity_rule[button_type]" <?php if(!empty($activity_rule['button_type']) && $activity_rule['button_type']=='custom'):?>checked<?php endif;?> value="custom" />
                                            <label class="" >自定义</label>
                                            <div class="control-input m_l_10">
                                                <input type="text" name="activity_rule[button_value]" value="<?=!empty($activity_rule['button_value'])?$activity_rule['button_value']:'';?>" placeholder="自定义页面标题" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>活动规则页面</td>
                            <td>
                                <div class="control-group p_3">
                                    <div class="controls1">活动规则</div>
                                    <div class="controls1" style="width: 50%;">
                                        <script type="text/plain" id="activity_rule" name="activity_rule[content]"><?=!empty($activity_rule['content'])?$activity_rule['content']:'';?></script>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>分享推送</td>
                            <td>
                                <div class="control-group p_3">
                                    <div class="controls1">分享标题</div>
                                    <div class="controls1" style="width: 50%;">
                                        <input type="text" style="width:100%;" name="invited_share[title]"  value="<?=!empty($invited_share['title'])?$invited_share['title']:'';?>" placeholder="Hi朋友，{invitename}送你金房卡大酒店{invitelevel}会员资格" />
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">分享副标题</div>
                                    <div class="controls1" style="width: 50%;">
                                        <input type="text" style="width:100%;" name="invited_share[sub_title]"  value="<?=!empty($invited_share['sub_title'])?$invited_share['sub_title']:'';?>" placeholder="马上注册成为会员领取{invitelevel}会员资格" />
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">分享LOGO</div>
                                    <div class="controls1">
                                        <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                                            <input type="hidden" name="invited_share[banner]" id="invited_share_banner" value="<?=!empty($invited_share['banner'])?$invited_share['banner']:'';?>">
                                            <a class="thumb-row" href="javascript:void(0);" onclick="flashupload('thumb_images', '背景图上传','invited_share_banner',thumb_images,'admin,admininvited,1,1024,jpg|gif|png');return false;">
                                                <?php if(!empty($invited_share['banner'])):?>
                                                    <img src="<?=$invited_share['banner']?>" id="invited_share_banner_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                <?php else:?>
                                                    <img src="<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png" id="invited_share_banner_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                <?php endif;?>
                                            </a>
                                            <input type="button" style="margin-top: 10px;color: #fff;font-weight: bold;" class="btn btn-small" onclick="$('#invited_share_banner_preview').attr('src','<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png');$('#invited_share_banner_preview').val('');return false;" value="取消图片">
                                        </div>
                                    </div>
                                </div>
                                <div class="f_notes">建议图片大小为：640/325px,jpg格式</div>
                            </td>
                        </tr>
                        <tr>
                            <td>注册和登陆页面</td>
                            <td>
                                <div class="control-group p_3">
                                    <div class="controls1">注册页面标题</div>
                                    <div class="controls1" style="width: 80%;">
                                        <input type="text" style="width:62%;" name="reg_login[reg_title]"  value="<?=!empty($reg_login['reg_title'])?$reg_login['reg_title']:'';?>" placeholder="请输入注册页面标题" /><em>如公众号为登陆模式则需要填写注册和登陆的标题</em>
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">登录页面标题</div>
                                    <div class="controls1" style="width: 50%;">
                                        <input type="text" style="width:100%;" name="reg_login[login_title]"  value="<?=!empty($reg_login['login_title'])?$reg_login['login_title']:'';?>" placeholder="请输入登录页面标题" />
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">完善资料页面标题</div>
                                    <div class="controls1" style="width: 80%;">
                                        <input type="text" style="width:62%;" name="reg_login[perfect_title]"  value="<?=!empty($reg_login['perfect_title'])?$reg_login['perfect_title']:'';?>" placeholder="请输入完善资料页面标题" /><em>如公众号为完善资料模式则只需要填写该标题即可</em>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>升级成功页面</td>
                            <td>
                                <div class="control-group p_3">
                                    <div class="controls1">页面标题</div>
                                    <div class="controls1" style="width:50%;">
                                        <input type="text" style="width:100%;" name="upgrade_success[title]"  value="<?=!empty($upgrade_success['title'])?$upgrade_success['title']:'';?>" placeholder="请输入页面标题" />
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">使用按钮名称</div>
                                    <div class="controls1" style="width: 33%;">
                                        <input type="text" style="width:62%;" name="upgrade_success[button_name]"  value="<?=!empty($upgrade_success['button_name'])?$upgrade_success['button_name']:''?>" placeholder="请输入按钮名称" /><em>最多可显示6个汉字</em>
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">跳转链接</div>
                                    <div class="controls1" style="width: 50%;">
                                        <input type="text" style="width:100%;" name="upgrade_success[url]"  value="<?=!empty($upgrade_success['url'])?$upgrade_success['url']:''?>" placeholder="请输入链接地址" />
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">扫码关注提示</div>
                                    <div class="controls1" style="width: 80%;">
                                        <input type="text" style="width:68%;" name="upgrade_success[scan_tip]"  value="<?=!empty($upgrade_success['scan_tip'])?$upgrade_success['scan_tip']:''?>" placeholder="请输入提示语" /> <em>举例：扫码关注xx酒店享受更多会员权益</em>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>自定义跳转</td>
                            <td>
                                <div style="text-align: left">如不设置链接则只做图文展示，不进行跳转。标题和图片需要一并设置才会显示。</div>
                                <div class="control-group p_3">
                                    <div class="controls1">自定义跳转栏1标题</div>
                                    <div class="controls1" style="width:33%;">
                                        <input type="text" style="width:62%;" name="custom[title1]"  value="<?=!empty($custom['title1'])?$custom['title1']:''?>" placeholder="请输入页面标题" /><em>最多可显示6个汉字</em>
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">自定义跳转栏1链接</div>
                                    <div class="controls1" style="width: 50%;">
                                        <input type="text" style="width:100%;" name="custom[url1]"  value="<?=!empty($custom['url1'])?$custom['url1']:''?>" placeholder="请输入按钮名称" />
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">自定义跳转栏1图片</div>
                                    <div class="controls1">
                                        <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                                            <input type="hidden" name="custom[banner1]" id="custom_banner1" value="<?=!empty($custom['banner1'])?$custom['banner1']:''?>">
                                            <a class="thumb-row" href="javascript:void(0);" onclick="flashupload('thumb_images', '背景图上传','custom_banner1',thumb_images,'admin,admininvited,1,1024,jpg|gif|png');return false;">
                                                <?php if(!empty($custom['banner1'])):?>
                                                    <img src="<?=$custom['banner1']?>" id="custom_banner1_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                <?php else:?>
                                                    <img src="<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png" id="custom_banner1_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                <?php endif;?>
                                            </a>
                                            <input type="button" style="margin-top: 10px;color: #fff;font-weight: bold;" class="btn btn-small" onclick="$('#custom_banner1_preview').attr('src','<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png');$('#custom_banner1_preview').val('');return false;" value="取消图片">
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">自定义跳转栏2标题</div>
                                    <div class="controls1" style="width:33%;">
                                        <input type="text" style="width:62%%;" name="custom[title2]"  value="<?=!empty($custom['title2'])?$custom['title2']:''?>" placeholder="请输入页面标题" /><em>最多可显示6个汉字</em>
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">自定义跳转栏2链接</div>
                                    <div class="controls1" style="width:50%;">
                                        <input type="text" style="width:100%;" name="custom[url2]"  value="<?=!empty($custom['url2'])?$custom['url2']:''?>" placeholder="请输入按钮名称" />
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">自定义跳转栏2图片</div>
                                    <div class="controls1">
                                        <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                                            <input type="hidden" name="custom[banner2]" id="custom_banner2" value="<?=!empty($custom['banner2'])?$custom['banner2']:''?>">
                                            <a class="thumb-row" href="javascript:void(0);" onclick="flashupload('thumb_images', '背景图上传','custom_banner2',thumb_images,'admin,admininvited,1,1024,jpg|gif|png');return false;">
                                                <?php if(!empty($custom['banner2'])):?>
                                                    <img src="<?=$custom['banner2']?>" id="custom_banner2_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                <?php else:?>
                                                    <img src="<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png" id="custom_banner2_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                <?php endif;?>
                                            </a>
                                            <input type="button" style="margin-top: 10px;color: #fff;font-weight: bold;" class="btn btn-small" onclick="$('#custom_banner2_preview').attr('src','<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png');$('#custom_banner2_preview').val('');return false;" value="取消图片">
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">自定义跳转栏3标题</div>
                                    <div class="controls1" style="width:33%;">
                                        <input type="text" style="width:62%;" name="custom[title3]"  value="<?=!empty($custom['title3'])?$custom['title3']:''?>" placeholder="请输入页面标题" /><em>最多可显示6个汉字</em>
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">自定义跳转栏3链接</div>
                                    <div class="controls1" style="width: 50%;">
                                        <input type="text" style="width:100%;" name="custom[url3]"  value="<?=!empty($custom['url3'])?$custom['url3']:''?>" placeholder="请输入按钮名称" />
                                    </div>
                                </div>
                                <div class="control-group p_3">
                                    <div class="controls1">自定义跳转栏3图片</div>
                                    <div class="controls1">
                                        <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                                            <input type="hidden" name="custom[banner3]" id="custom_banner3" value="<?=!empty($custom['banner3'])?$custom['banner3']:''?>">
                                            <a class="thumb-row" href="javascript:void(0);" onclick="flashupload('thumb_images', '背景图上传','custom_banner3',thumb_images,'admin,admininvited,1,1024,jpg|gif|png');return false;">
                                                <?php if(!empty($custom['banner3'])):?>
                                                    <img src="<?=$custom['banner3']?>" id="custom_banner3_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                <?php else:?>
                                                    <img src="<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png" id="custom_banner3_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                <?php endif;?>
                                            </a>
                                            <input type="button" style="margin-top: 10px;color: #fff;font-weight: bold;" class="btn btn-small" onclick="$('#custom_banner3_preview').attr('src','<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png');$('#custom_banner3_preview').val('');return false;" value="取消图片">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="100">
                                <div class="" style="padding:10px 0;width:420px;">
                                    <button id="bnt_sub" type="submit" class="btn btn-primary dosave">保存</button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
<?php /* Footer Block @see footer.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php'; ?>
<?php /* Right Block @see right.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php'; ?>
<?php /* Right Block @see right.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php'; ?>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/content_addtop.js"></script>
<script type="text/javascript">
    //编辑器路径定义
    var editorURL = GV.DIMAUB;
</script>
<script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
    $(function () {
        //编辑器
        var toolbars = [['fullscreen','source','|', 'bold', 'removeformat', 'pasteplain', '|','insertorderedlist', 'insertunorderedlist', 'selectall','emotion','date', 'time','drafts']];
        var editor1 = new baidu.editor.ui.Editor({toolbars:toolbars,maximumWords:200,elementPathEnabled:false,maxUndoCount:200});
        editor1.render('pagetip');
        var editor2 = new baidu.editor.ui.Editor({toolbars:toolbars,elementPathEnabled:false});
        editor2.render('activity_rule');

        Wind.use("ajaxForm", function () {
            $(document).on('click', '.dosave', function (e) {
                e.preventDefault();
                var _this = this, ok_url = "<?php echo EA_const_url::inst()->get_url('*/*/');?>", btn = $(this);
                var form = $('.form-inline'), form_url = form.attr("action");
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
                        var _null = false, _msg = '',$_inputo = null;
                        for (i in arr) {
                            var name = arr[i].name, value = $.trim(arr[i].value);
                            $_inputo = $("input[name='"+name+"']");
                            switch (name) {
                                case 'home[title]':
                                    if (!value) {
                                        _null = true;
                                        _msg = '请输入标题';
                                    }
                                    break;
                                case 'invited_share[title]':
                                    if (!value) {
                                        _null = true;
                                        _msg = '请输入分享标题';
                                    }
                                    break;
                                case 'reg_login[reg_title]':
                                    if (!value) {
                                        _null = true;
                                        _msg = '请输入注册页面标题';
                                    }
                                    break;
                                case 'upgrade_success[title]':
                                    if (!value) {
                                        _null = true;
                                        _msg = '请输入页面标题';
                                    }
                                    break;
                            }
                            if (_null === true) break;
                        }

                        if (_null === true) {
                            $_inputo.focus();
                            return false;
                        }
                        /*end*/
                        var text = btn.text();
                        btn.prop('disabled', true).addClass('disabled').text(text + '中...');
                    },
                    success: function (data) {
                        if (data.status == 1) {
                            var btnval = data.data.isadd === false ? '编辑' : '添加';
                            btn.parent().append("<span style='color: #3c8dbc;'>" + data.message + "</span>");
                            setTimeout(function () {
                                btn.parent().find('span').fadeOut('normal', function () {
                                    btn.parent().find('span').remove();
                                });
                            }, 3000);
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
                        btn.parent().append("<span style='color: #ff0040;'>请求异常,请刷新页面试试!</span>");
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