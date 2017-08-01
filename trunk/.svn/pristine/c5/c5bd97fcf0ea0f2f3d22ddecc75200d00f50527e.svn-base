<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
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
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/validate.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/wind.js"></script>
<style type="text/css">

</style>
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
        a {
            color: #92a0ae;
        }

        .bg_90d08f {
            background: #90d08f;
        }

        .bg_85bdd0 {
            background: #85bdd0;
        }

        .bg_3dc6d0 {
            background: #3dc6d0;
        }

        .bg_a984d0 {
            background: #a984d0;
        }

        .s_btn {
            width: 100px;
            text-align: center;
            padding: 4px 0px;
            border-radius: 5px;
            margin-right: 8px;
            background: #ff9900;
            color: #fff;
            border: 1px solid #ff9900;
        }

        .display_flex {
            display: flex;
            display: -webkit-flex;
            justify-content: top;
            align-items: center;
            -webkit-align-items: center;
        }

        .display_flex > div {
            -webkit-flex: 1;
            flex: 1;
            cursor: pointer;
            text-align: center;
            margin: 0 15px;
            border: 1px solid #d7e0f1;
            padding: 10px 0px;
            border-radius: 5px;
        }

        .moba {
            height: 30px;
            line-height: 30px;
            border: 1px solid #d7e0f1;
            text-indent: 3px;
        }

        .boxs > div {
            margin-bottom: 20px;
        }

        .statistics > div {
            display: inline-block;
        }

        .s_title {
            font-size: 16px;
            margin-right: 10px;
        }

        .btn_hre {
            border: 1px solid #d7e0f1;
            padding: 3px 10px;
            border-radius: 4px;
            color: #d7e0f1;
        }

        .s_con {
            color: #fff;
        }

        .s_con > div > p {
            margin: 3px;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo isset($breadcrumb_array['action']) ? $breadcrumb_array['action'] : ''; ?>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <?php echo $this->session->show_put_msg(); ?>

            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">绩效配置</h3>
                </div>
                <?php echo form_open(EA_const_url::inst()->get_url('*/*/save_setting'), array('class'=>'form-inline')); ?>
                <input type="hidden" name="rule_id" value="<?php echo isset($ruleInfo['rule_id']) && $ruleInfo['rule_id'] ? $ruleInfo['rule_id']: '';?>" />
                <input type="hidden" name="rule_type" value="<?php echo isset($ruleInfo['rule_type']) && $ruleInfo['rule_type'] ? $ruleInfo['rule_type']: 'reg';?>" />
                <div class="box-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>规则名称</th>
                            <th>
                                <input type="text"  class="form-control" name="rule_title" value="<?php if(isset($ruleInfo['title']) && $ruleInfo['title'] ){ echo $ruleInfo['title']; }?>"  placeholder="规则名称" />
                            </th>
                        </tr>
                        <tr>
                            <th>分销规则</th>
                            <th>
                                <input type="radio" checked name="" disabled/>注册模式<br/>
                                <input type="checkbox" checked  name="rule_type[]" value="reg" disabled>正式会员（会员注册和绑定成为正式会员）
                            </th>
                        </tr>
                        <tr>
                            <th>激励方式</th>
                            <th>
                               每个注册会员奖励固定金额 <input type="text" name="reward" value="<?php if(isset($ruleInfo['reward']) && $ruleInfo['reward'] ){ echo $ruleInfo['reward']; }?>"  placeholder="奖励金额" /> 元
                            </th>
                        </tr>
                        <tr>
                            <th>规则状态</th>
                            <th>
                                <input type="radio" <?php if(isset($ruleInfo['status']) && $ruleInfo['status'] =='t'){?> checked <?php }  ?> name="status" value="t" />有效<br/>
                                <input type="radio" <?php if(isset($ruleInfo['status']) && $ruleInfo['status'] =='f'){?> checked <?php }  ?> name="status" value="f" />无效<br/>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="box-footer ">
                    <button type="submit" class="btn btn-primary dosave"><i class="fa fa-save">保存</i></button>
                </div>
                <?php echo form_close() ?>
                <!-- /.box-footer -->
            </div>

    </div>
    <!-- Horizontal Form -->

    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function () {
        Wind.use("ajaxForm","artDialog",function () {
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
                        var _null=false,_msg='',inputo=0,st=0,et=0;
                        for (i in arr) {
                            var name = arr[i].name, value = $.trim(arr[i].value);
                            inputo = $("input[name='"+name+"']");
                            switch (name) {
                                case 'reward':
                                    st = value;
                                    if (isNaN(value)) {
                                        _null = true;
                                        _msg = '奖金必须为数字';
                                    }
                                    if(value < 0.01){
                                        _null = true;
                                        _msg = '奖金不能少于0.01';
                                        break;
                                    }
                                    if(value > 5 ){
                                        _null = true;
                                        _msg = '奖金不能大于5';
                                        break;
                                    }
                            }
                            if (_null === true) {
                                inputo.focus();
                                alert(_msg);
                                return false;
                            }
                        }
                        /*end*/
                        var text = btn.text();
                        btn.prop('disabled', true).addClass('disabled').text(text + '中...');
                    },
                    success: function (data) {
                        console.log(data)
                        if (data.status == 1) {
                            btn.parent().append("<span style='color: #00b723;'>" + data.message + "</span>");
                            window.location.href=data.data.url;
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

<?php
/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>
<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
?>
</div><!-- ./wrapper -->
<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>
</body>
</html>
