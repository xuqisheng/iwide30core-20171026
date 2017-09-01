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
    .box-body .table-striped>tbody>tr>td{background: #FFFFFF;vertical-align: middle;text-align: center;}
    .control-group{background: #fff;  padding: 10px; float: left;width: 100%;}
    .controls{float: left;padding: 5px;width: 30%;}.control-input{float: right;}
    .controls span{margin-left: 10%;}.control-group .title{}
	.p_12_5{padding:12px 50px;}
	.m_10_0{margin:10px 0;}
	.m_r_8{margin-right:8px;}
	.m_l_8{margin-left:8px;}
	.w_100{width:100px;}
	.w_50{width:50px;}
	.t_notes{color:#999;font-size:12px;margin-left: 5%;}
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
          <h1>奖励设置<small></small></h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
                <?php echo $this->session->show_put_msg(); ?>
            <!-- Horizontal Form -->
            <div class="box box-info" style="background-color:#ecf0f5;box-shadow:0 0 0 rgba(0,0,0,0);">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                </div>
                <?php echo form_open(EA_const_url::inst()->get_url('*/*/rewards_edit'), array('class'=>'form-inline')); ?>
                    <input type="hidden" name="id" value="<?php echo isset($info['id'])?$info['id']:'';?>" />
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6">
                              <div class="box box-solid">
                                <div class="box-header with-border"><h3 class="box-title">老会员奖励</h3></div>
                                <div class="box-body p_12_5 old_mem_rew">
                                	<div class="m_10_0 ">
                                        <div class="checkbox m_r_8">
                                            <label class="control-checkbox">
                                                <input style="margin-right:8px;" type="radio" <?php if(isset($info['old_reward_type']) && $info['old_reward_type']=='0'):?>checked<?php endif;?><?php if(!isset($info) && empty($info)):?>checked<?php endif;?> name="old_reward_type" value="0">无奖励
                                            </label>
                                        </div>
                                    </div>
                                	<div class="m_10_0">
                                        <div class="checkbox m_r_8">
                                            <label class="control-checkbox">
                                                <input style="margin-right:8px;" type="radio" <?php if(isset($info['old_reward_type']) && $info['old_reward_type']=='1'):?>checked<?php endif;?> name="old_reward_type" value="1" />送
                                            </label>
                                        </div>
                                        <input type="number" value="<?php if(isset($info['old_reward_type']) && $info['old_reward_type']=='1'):?><?php echo $info['old_reward_value'];?><?php endif;?>" <?php if(!isset($info['old_reward_type']) || $info['old_reward_type']!='1'):?>disabled<?php endif;?> placeholder="请输入积分数值" name="old_reward_value" />积分
                                    </div>
                                	<div class="m_10_0">
                                        <div class="checkbox m_r_8">
                                            <label class="control-checkbox">
                                                <input style="margin-right:8px;" type="radio" <?php if(isset($info['old_reward_type']) && $info['old_reward_type']=='2'):?>checked<?php endif;?> name="old_reward_type" value="2">送
                                            </label>
                                        </div>
                                        <input type="number" value="<?php if(isset($info['old_reward_type']) && $info['old_reward_type']=='2'):?><?php echo $info['old_reward_value'];?><?php endif;?>" <?php if(!isset($info['old_reward_type']) || $info['old_reward_type']!='2'):?>disabled<?php endif;?> placeholder="请输入储值数值" name="old_reward_value" />储值
                                    </div>
                                	<div class="m_10_0">
                                        <div class="checkbox m_r_8">
                                            <label class="control-checkbox">
                                                <input style="margin-right:8px;" type="radio" <?php if(isset($info['old_reward_type']) && $info['old_reward_type']=='3'):?>checked<?php endif;?> name="old_reward_type" value="3">送优惠券
                                            </label>
                                        </div>
                                        <select name="old_reward_value" <?php if(!isset($info['old_reward_type']) || $info['old_reward_type']!='3'):?>disabled<?php endif;?>>
                                        	<option value="">选择优惠券</option>
                                        	<?php foreach ($cardlist as $key=>$val){?>
                                        	<option value="<?php echo $val['card_id']?>" <?php if(isset($info['old_reward_type']) && $info['old_reward_type']=='3' && $info['old_reward_value']==$val['card_id']):?> selected <?php endif;?>><?php echo $val['title']?></option>
                                        	<?php }?>
                                        </select>
                                    </div>
                                	<div class="m_10_0">
                                        <div class="checkbox m_r_8">
                                            <label class="control-checkbox">
                                                <input style="margin-right:8px;" type="radio" <?php if(isset($info['old_reward_type']) && $info['old_reward_type']=='4'):?>checked<?php endif;?> name="old_reward_type" value="4">送大礼包
                                            </label>
                                        </div>
                                        <select name="old_reward_value" <?php if(!isset($info['old_reward_type']) || $info['old_reward_type']!='4'):?>disabled<?php endif;?>>
                                        	<option value="">选择会员礼包</option>
                                        	<?php foreach ($package_list as $key=>$val){?>
                                        	<option value="<?php echo $val['package_id']?>" <?php if(isset($info['old_reward_type']) && $info['old_reward_type']=='4' && $info['old_reward_value']==$val['package_id']):?> selected <?php endif;?>><?php echo $val['name']?></option>
                                        	<?php }?>
                                        </select>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-xs-6">
                              <div class="box box-solid">
                                <div class="box-header with-border"><h3 class="box-title">新会员奖励</h3></div>
                                <div class="box-body p_12_5">
                                	<div class="m_10_0 ">
                                        <div class="checkbox m_r_8">
                                            <label class="control-checkbox"><input style="margin-right:8px;" type="radio" name="new_reward_type" <?php if(isset($info['new_reward_type']) && $info['new_reward_type']=='0'):?>checked<?php endif;?> <?php if(!isset($info) && empty($info)):?>checked<?php endif;?> value="0">无奖励</label>
                                        </div>
                                    </div>
                                	<div class="m_10_0">
                                        <div class="checkbox m_r_8">
                                            <label class="control-checkbox"><input style="margin-right:8px;" type="radio" name="new_reward_type" <?php if(isset($info['new_reward_type']) && $info['new_reward_type']=='1'):?>checked<?php endif;?> value="1">送</label>
                                        </div>
                                        <input type="number" <?php if(!isset($info['new_reward_type']) || $info['new_reward_type']!='1'):?>disabled<?php endif;?> name="new_reward_value" value="<?php if(isset($info['new_reward_type']) && $info['new_reward_type']=='1'):?><?php echo $info['new_reward_value'];?><?php endif;?>" placeholder="请输积分数值" />积分
                                    </div>
                                	<div class="m_10_0">
                                        <div class="checkbox m_r_8">
                                            <label class="control-checkbox"><input style="margin-right:8px;" type="radio"  name="new_reward_type" <?php if(isset($info['new_reward_type']) && $info['new_reward_type']=='2'):?>checked<?php endif;?> value="2">送</label>
                                        </div>
                                        <input type="number" <?php if(!isset($info['new_reward_type']) || $info['new_reward_type']!='2'):?>disabled<?php endif;?> name="new_reward_value" value="<?php if(isset($info['new_reward_type']) && $info['new_reward_type']=='2'):?><?php echo $info['new_reward_value'];?><?php endif;?>" placeholder="请输入储值数值" />储值
                                    </div>
                                	<div class="m_10_0">
                                        <div class="checkbox m_r_8">
                                            <label class="control-checkbox"><input style="margin-right:8px;" type="radio" name="new_reward_type" <?php if(isset($info['new_reward_type']) && $info['new_reward_type']=='3'):?> checked <?php endif;?> value="3">送优惠券</label>
                                        </div>
                                        <select name="new_reward_value" <?php if(!isset($info['new_reward_type']) || $info['new_reward_type']!='3'):?>disabled<?php endif;?>>
                                        <option value="">选择优惠券</option>
                                        	<?php foreach ($cardlist as $key=>$val){?>
                                        	 	<option value="<?php echo $val['card_id']?>" <?php if(isset($info['new_reward_type']) && $info['new_reward_type']=='3' && $info['new_reward_value']==$val['card_id']):?> selected <?php endif;?>><?php echo $val['title']?></option>
                                        	<?php }?>
                                        </select>
                                    </div>
                                	<div class="m_10_0">
                                        <div class="checkbox m_r_8">
                                            <label class="control-checkbox"><input style="margin-right:8px;" type="radio" name="new_reward_type" <?php if(isset($info['new_reward_type']) && $info['new_reward_type']=='4'):?> checked <?php endif;?> value="4">送大礼包</label>
                                        </div>
                                        <select name="new_reward_value" <?php if(!isset($info['new_reward_type']) || $info['new_reward_type']!='4'):?>disabled<?php endif;?>>
                                        	<option value="">选择会员礼包</option>
                                        	       	<?php foreach ($package_list as $key=>$val){?>
                                        	<option value="<?php echo $val['package_id']?>" <?php if(isset($info['new_reward_type']) && $info['new_reward_type']=='4' && $info['new_reward_value']==$val['package_id']):?> selected <?php endif;?>><?php echo $val['name']?></option>
                                        	<?php }?>
                                        </select>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="m_10_0">
                                <div class="checkbox m_r_8">
                                    <label class="control-checkbox-2"><input style="margin-right:8px;" type="radio" name="exchange_type" <?php if(isset($info['exchange_type']) && $info['exchange_type']=='1'):?>checked<?php endif;?> value="1">满</label>
                                </div>
                                <input class="m_r_8 w_100" type="number" value="<?php if(isset($info['exchange_type']) && $info['exchange_type']=='1'):?><?php echo $info['exchange_value'];?><?php endif;?>" <?php if(!isset($info['exchange_type']) || $info['exchange_type']!='1'):?>disabled<?php endif;?> name="exchange_value" placeholder="请输入积分量" />送兑换券
                                <select name="exchange_reward" <?php if(!isset($info['exchange_type']) || $info['exchange_type']!='1'):?>disabled<?php endif;?>>
                                    <option value="">选择兑换券</option>
                                    <?php foreach ($exchange_card as $key=>$val){?>
                                        <option value="<?php echo $val['card_id']?>" <?php if(isset($info['exchange_type']) && $info['exchange_type']=='1' && $info['exchange_reward']==$val['card_id']):?> selected <?php endif;?>><?php echo $val['title']?></option>
                                    <?php }?>
                                </select>
                                <span class="t_notes">（积分满）</span>
                            </div>
                            <div class="m_10_0">
                                <div class="checkbox m_r_8">
                                    <label class="control-checkbox-2"><input style="margin-right:8px;" type="radio" name="exchange_type" <?php if(isset($info['exchange_type']) && $info['exchange_type']=='2'):?>checked<?php endif;?> value="2">前</label>
                                </div>
                                <input class="m_r_8 w_100" type="number" value="<?php if(isset($info['exchange_type']) && $info['exchange_type']=='2'):?><?php echo $info['exchange_value'];?><?php endif;?>" <?php if(!isset($info['exchange_type']) || $info['exchange_type']!='2'):?>disabled<?php endif;?> name="exchange_value" placeholder="请输入名次" />送兑换券
                                <select name="exchange_reward" <?php if(!isset($info['exchange_type']) || $info['exchange_type']!='2'):?>disabled<?php endif;?>>
                                    <option value="">选择兑换券</option>
                                    <?php foreach ($exchange_card as $key=>$val){?>
                                        <option value="<?php echo $val['card_id']?>" <?php if(isset($info['exchange_type']) && $info['exchange_type']=='2' && $info['exchange_reward']==$val['card_id']):?> selected <?php endif;?>><?php echo $val['title']?></option>
                                    <?php }?>
                                </select>
                                <span class="t_notes">（排行榜）</span>
                            </div>
                        </div>
                        <div>
                            <div class="m_10_0">
                                <div class="checkbox m_r_8">
                                    活动积分是否独立
                                </div>
                                <div class="checkbox m_r_8">
                                    <label><input style="margin-right:8px;" type="radio" name="isalone" <?php if(isset($info['isalone']) && $info['isalone']=='1'):?>checked<?php endif;?><?php if(!isset($info) && empty($info)):?>checked<?php endif;?> value="1">是</label>
                                    <label><input style="margin-right:8px;" type="radio" name="isalone" <?php if(isset($info['isalone']) && $info['isalone']=='2'):?>checked<?php endif;?> value="2">否</label>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <button type="submit" class="btn btn-primary dosave">保存</button>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                <?php echo form_close() ?>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php /* Footer Block @see footer.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php'; ?>
    <?php /* Right Block @see right.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php'; ?>
</div><!-- ./wrapper -->
<?php /* Right Block @see right.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php'; ?>
</body>
<script type="text/javascript">
$(function () {
    $(document).on('click','.control-checkbox',function (e) {
        e.preventDefault();
        if($(this).find("input[type='radio']").prop("checked")===false){
            var parobj = $(this).parent().parent().parent();
            $(parobj).find("input[type='number']").prop('disabled',true);
            $(parobj).find("select").prop('disabled',true);
//            $(parobj).find("input[type='text']").val('');
//            $(parobj).find("select").val('0');
            $(this).parent().next().prop('disabled',false);
            $(this).find("input[type='radio']").prop("checked",true);
        }
    });

    $(document).on('click','.control-checkbox-2',function (e) {
        e.preventDefault();
        if($(this).find("input[type='radio']").prop("checked")===false){
            var parobj = $(this).parent().parent().parent();
            $(parobj).find("input[type='number']").prop('disabled',true);
            $(parobj).find("select").prop('disabled',true);
//            $(parobj).find("input[type='text']").val('');
//            $(parobj).find("select").val('0');
            $(this).parent().next().prop('disabled',false);
            $(this).parent().next().next().prop('disabled',false);
            $(this).find("input[type='radio']").prop("checked",true);
        }
    });

    Wind.use("ajaxForm",function () {
        $(document).on('click', '.dosave', function (e) {
            e.preventDefault();
            var _this = this, btn = $(this);
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
                    var inputObj = document.getElementsByTagName('input'),selectos=document.getElementsByTagName('select');
                    /*验证提交数据*/
                    var _null=false,_msg='',inputo=0,st=0,et=0;
                    for (i in inputObj) {
                        var name = inputObj[i].name, value = $.trim(inputObj[i].value);
                        inputo = inputObj[i];
                        if(inputObj[i].disabled===false && name!='q' && name!='id'){
                            if (!value) {
                                _null = true;
                                _msg = '';break;
                            }
                        }
                    }

                    if(_null===false){
                        for (i in selectos) {
                            var name = selectos[i].name, value = $.trim(selectos[i].value);
                            inputo = selectos[i];
                            if(selectos[i].disabled===false){
                                if (!value) {
                                    _null = true;
                                    _msg = '';break;
                                }
                            }
                        }
                    }

                    if (_null === true) {
                        inputo.focus();
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
</html>
