<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/artDialog/css/ui-dialog.css" rel="stylesheet" />
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

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
<?php echo $this->session->show_put_msg(); ?>

<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">添加购卡</h3>
	</div>
    <?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal depositcard_add','enctype'=>'multipart/form-data')); ?>
        <input type="hidden" name="deposit_card_id" value="<?php if ( isset($cardinfo['deposit_card_id']) && $cardinfo['deposit_card_id'] ){ echo $cardinfo['deposit_card_id']; } ?>" />
		<div class="box-body">
        <table class="table">
            <thead>
                <tr>
                <tr>
                    <th width="20%">购卡LOGO</th>
                    <th>
                        <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                            <input type="hidden" name="logo_url" id="logo_url" value="">
                            <a class="thumb-row" href="javascript:void(0);" onclick="flashupload('thumb_images', 'LOGO上传','logo_url',thumb_images,'front,depositcard,1,1024,jpg|png');return false;">
                                <?php if(isset($cardinfo['logo_url']) && $cardinfo['logo_url']):?>
                                <img src="<?php echo $cardinfo['logo_url'];?>" id="logo_url_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                <?php else:?>
                                <img src="<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png" id="logo_url_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                <?php endif;?>
                            </a>
                            <input type="button" style="margin-top: 10px;color: #fff;font-weight: bold;" class="btn btn-small" onclick="$('#logo_url_preview').attr('src','<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png');$('#logo_url').val('');return false;" value="取消图片">
                        </div>
                    </th>
                </tr>
                <tr>
                    <th>购卡类型</th>
                    <th>
                        <select class="form-control deposit-type" name="deposit_type" >
                            <option value="g" <?php if(isset($cardinfo['deposit_type']) && $cardinfo['deposit_type']=='g' ){ echo 'selected'; } ?>>购卡储值</option>
                            <option value="c" <?php if(isset($cardinfo['deposit_type']) && $cardinfo['deposit_type']=='c' ){ echo 'selected'; } ?>>直接储值</option>
                        </select>
                    </th>
                </tr>
                <tr>
                    <th>副名</th>
                    <th><input class="form-control" name="brand_name" value="<?php if(isset($cardinfo['brand_name']) && $cardinfo['brand_name'] ){ echo $cardinfo['brand_name']; }?>"  placeholder="请填写副名"/></th>
                </tr>
                <tr>
                    <th>购卡名称</th>
                    <th><input class="form-control" name="title" value="<?php if(isset($cardinfo['title']) && $cardinfo['title'] ){ echo $cardinfo['title']; }?>" placeholder="请填写购卡名称" /></th>
                </tr>
                <!--<tr>
                    <th>使用说明</th>
                    <th><input class="form-control" name="notice" value="<?php /*if(isset($cardinfo['notice']) && $cardinfo['notice'] ){ echo $cardinfo['notice']; }*/?>" placeholder="请填写购卡副标题" /></th>
                </tr>-->
                <tr>
                    <th>使用提醒</th>
                    <th><input class="form-control" name="notice" value="<?php if(isset($cardinfo['notice']) && $cardinfo['notice'] ){ echo $cardinfo['notice']; }?>" placeholder="请填写使用提醒" /></th>
                </tr>
                <tr>
                    <th>使用说明</th>
                    <th>
                        <textarea name="description"  class="form-control" placeholder="请填写使用说明" ><?php if(isset($cardinfo['description']) && $cardinfo['description'] ){ echo $cardinfo['description']; }?></textarea>
                    </th>
                </tr>
                <tr>
                    <th>卡券库存</th>
                    <th><input class="form-control"  name="card_stock" value="<?php if(isset($cardinfo['card_stock']) && $cardinfo['card_stock'] ){ echo $cardinfo['card_stock']; }?>" placeholder="请填写卡券库存" ></th>
                </tr>
                <tr>
                    <th>卡券页面属性</th>
                    <th>
                        <input class="form-control" name="page_config" value="<?php if(isset($cardinfo['page_config']) && $cardinfo['page_config'] ){ echo $cardinfo['page_config']; } ?>" placeholder="请填写页面属性" />
                    </th>
                </tr>
                <tr class="money" <?php if( isset($cardinfo['card_type']) && $cardinfo['card_type']!=4 ){ echo "style='display:none;'"; } ?> >
                    <th>购卡金额</th>
                    <th>
                        <input class="form-control" name="money" value="<?php if(isset($cardinfo['money']) && $cardinfo['money'] ){ echo $cardinfo['money']; } ?>" placeholder="请填写储值券金额"/>
                    </th>
                </tr>
                <tr>
                    <th>备注</th>
                    <th>
                        <textarea class="form-control" name="remark" placeholder="请填写备注"><?php if(isset($cardinfo['remark']) && $cardinfo['remark'] ){ echo $cardinfo['remark']; } ?> </textarea>
                    </th>
                </tr>
                <tr>
                    <th>是否计入余额</th>
                    <th>
                        <select class="form-control" name="is_balance" >
                            <option value="f" <?php if(isset($cardinfo['is_balance']) && $cardinfo['is_balance']=='f' ){ echo 'selected'; } ?>>否</option>
                            <option value="t" <?php if(isset($cardinfo['is_balance']) && $cardinfo['is_balance']=='t' ){ echo 'selected'; } ?>>是</option>
                        </select>
                    </th>
                </tr>
                <tr>
                    <th>是否关联分销</th>
                    <th>
                        <select class="form-control" name="is_distribution" >
                            <option value="f" <?php if(isset($cardinfo['is_distribution']) && $cardinfo['is_distribution']=='f' ){ echo 'selected'; } ?>>否</option>
                            <option value="t" <?php if(isset($cardinfo['is_distribution']) && $cardinfo['is_distribution']=='t' ){ echo 'selected'; } ?>>是</option>
                        </select>
                    </th>
                </tr>
                <tr>
                    <th>请填写分销提成</th>
                    <th>
                        <input class="form-control" name="distribution_money" value="<?php if(isset($cardinfo['distribution_money']) && $cardinfo['distribution_money'] ){ echo $cardinfo['distribution_money']; } ?>" placeholder="请填写分销提成" />
                    </th>
                </tr>
                <tr>
                    <th>是否加入套餐</th>
                    <th>
                        <select class="form-control" name="is_package" >
                            <option value="f" <?php if(isset($cardinfo['is_package']) && $cardinfo['is_package']=='f' ){ echo 'selected'; } ?>>否</option>
                            <option value="t" <?php if(isset($cardinfo['is_package']) && $cardinfo['is_package']=='t' ){ echo 'selected'; } ?>>是</option>
                        </select>
                    </th>
                </tr>
                <tr>
                    <th>套餐ID</th>
                    <th>
                        <div class="bdtime" style="">
                            <input class="form-control" type="text" name="package_id" value="<?php if(isset($cardinfo['package_id']) && $cardinfo['package_id'] ){ echo $cardinfo['package_id']; } ?>">
                        </div>
                    </th>
                </tr>
                <tr>
                    <th>支付方式</th>
                    <th>
                        <div class="bdtime" style="">
                            <div class="controls1 m_r_75">
                                <label class="control-checkbox wechat">
                                    <input type="checkbox" name="pay_type[]" <?=$wechat_checked?> value="wechat" onclick="return false;" />
                                    <span class="m_r_20 m_l_10 width_20">微信支付</span>
                                </label>
                                <label class="control-checkbox balance" style="margin-left: 10px;">
                                    <input type="checkbox" name="pay_type[]" <?=$balance_checked?> <?=$balance_disabled?> value="balance" />
                                    <span class="m_r_20 m_l_10 width_20">储值支付</span>
                                </label>
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th>是否有效</th>
                    <th>
                        <select class="form-control" name="is_active" >
                            <option value="f" <?php if(isset($cardinfo['is_active']) && $cardinfo['is_active']=='f' ){ echo 'selected'; } ?>>否</option>
                            <option value="t" <?php if(isset($cardinfo['is_active']) && $cardinfo['is_active']=='t' ){ echo 'selected'; } ?>>是</option>
                        </select>
                    </th>
                </tr>
            </thead>
        </table>
		</div>
        <div class="box-footer ">
            <button type="submit" class="btn btn-primary dosave">保存</button>
        </div>
    <?php echo form_close() ?>
		<!-- /.box-footer -->
</div>

</div>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/content_addtop.js"></script>
<script type="text/javascript">
$(function(){
    $(document).on('change','.deposit-type',function (e) {
        e.preventDefault();
        var value = this.value;
        if(value == 'c'){
            $("label.balance").find("input[name='pay_type[]']").prop("disabled",true);
            $("label.balance").find("input[name='pay_type[]']").prop("checked",false);
        }else{
            $("input[name='pay_type[]']").prop("disabled",false);
        }
    });

    $(document).on('click','.dosave_',function (e) {
        e.preventDefault();
        var form = $('.form-horizontal');
        /*验证提交数据*/
        var _null = false, _msg = '',inputos=document.getElementsByTagName('input'),_inputo=null;
        for(i in inputos){
            var name = inputos[i].name,value=$.trim(inputos[i].value);
            _inputo = inputos[i];
            switch (name){
                case 'title':
                    if(!value && inputos[i].disabled===false){
                        _null = true; _msg = '请填写购卡名称';
                    }
                    break;
                case 'money':
                    if(!value && inputos[i].disabled===false){
                        _null = true; _msg = '请填写储值券金额';
                    }
                    break;
            }
            if(_null===true) break;
        }

        if(_null === true) {
            $(_inputo).focus();return false;
        }
        /*end*/
        form.submit();
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
                    var _null = false, _msg = '', inputos = $(".depositcard_add").find('input'), _inputo = null,_checkbox = false;
                    for (i in inputos) {
                        var name = inputos[i].name, value = $.trim(inputos[i].value);
                        _inputo = inputos[i];

                        switch (name) {
                            case 'title':
                                if (!value && inputos[i].disabled === false) {
                                    _null = true;
                                    _msg = '请填写购卡名称';
                                }
                                break;
                            case 'money':
                                var regexp = /^\+?\d+$/; //正整数
                                if (!value && inputos[i].disabled === false) {
                                    _null = true;
                                    _msg = '请填写储值券金额';
                                }else if(!regexp.test(value) && (value != '' || value != null)){
                                    _null = true;
                                    _msg = '购卡金额必须是正整数';
                                }
                                break;
                        }

                        if (_null === true) break;
                    }

                    if (_null === true) {
                        $(_inputo).focus();
                        art.dialog({
                            title: '提示',
                            fixed: true,
                            icon: 'warning',
                            content: _msg,
                            okVal: false,
                            cancelVal: '知道了',
                            cancel: true,
                            close: function () {
                                $(_inputo).focus(); //关闭时让触发弹窗的元素获取焦点
                                return true;
                            }
                        });
                        return false;
                    }

                    $("input[name='pay_type[]']").each(function (ide,ob) {
                        if($(ob).prop("checked")===true){
                            _checkbox = true;
                        }
                    });

                    if(_checkbox === false){
                        art.dialog({
                            title: '提示',
                            fixed: true,
                            icon: 'warning',
                            content: '请选择支付方式',
                            okVal: false,
                            cancelVal: '知道了',
                            cancel: true,
                        });
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
</body>
</html>
