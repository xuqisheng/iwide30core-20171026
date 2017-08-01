<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.css'>
<script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.min.js"></script>
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
    .box-body .table-striped>tr>td{background: #FFFFFF;vertical-align: middle;text-align: left;}
    .controls span{margin-left: 10%;}
	input,select{ text-align:center;}
	div{overflow:hidden}
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
          <h1>活动设置<small></small></h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
                <?php echo $this->session->show_put_msg(); ?>
            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border">
<!--                     <h3 class="box-title">活动设置</h3> -->
                </div>
                <style>
                .boxs >div{display:inline-block;margin:10px; vertical-align:middle}
				/*.boxs >*:nth-of-type(1){width:100px;float:left;text-align:right;}*/
                </style>
                <?php echo form_open(EA_const_url::inst()->get_url('*/*/actset_edit'), array('class'=>'form-inline')); ?>
                <input type="hidden" name='id' value='<?php echo isset($set['id'])?$set['id']:''?>' />
                <input type="hidden" name='reward_id' value='<?php echo isset($set['reward_id'])?$set['reward_id']:''?>' />
                <div class="box-body">
                        <table class="table table-bordered table-condensed dataTable no-footer">
                            <tr>
                                <td><div class="boxs"><div>活动名称:</div></div></td>
                                <td><div class="boxs"><div><input class="form-control" placeholder="活动名称" type="text"   value='<?php echo isset($set['name'])? $set['name']:''?>' name="name" /></div></div></td>
                            </tr>
                            <tr>
                                <td><div class="boxs"><div>活动状态:</div></div></td>
                                <td>
                                    <div class="boxs">
                                        <?php
                                            $defaultck='';
                                            $yeschecked='';
                                            $nochecked='';
                                            if(isset($set['status'])&&$set['status']==1){
                                                $yeschecked = 'checked';
                                            }

                                            if(isset($set['status'])&&$set['status']==2){
                                                $nochecked = 'checked';
                                            }

                                            if(!isset($set['status']) || empty($set['status'])){
                                                $defaultck = 'checked';
                                            }
                                        ?>
                                        <div><input style="margin-right:8px;" type="radio" name="status" <?php echo $yeschecked;echo $defaultck;?> value='1'>有效  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input style="margin-right:8px;" type="radio" name="status" <?php echo $nochecked;?> value='2'> 无效</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="15%"><div class="boxs"><div>活动开始时间:</div></div></div></td>
                                <td align="left"><div class="boxs"><div><input class="date" autocomplete="off" placeholder="<?php echo date('Y-m-d');?>" type="text" name="start_time"  value='<?php echo isset($set['start_time'])?$set['start_time']:''?>'/></div></div></td>
                            </tr>
                            <tr>
                                <td><div class="boxs"><div>活动结束时间:</div></div></td>
                                <td><div class="boxs"><div><input class="date" autocomplete="off" placeholder="<?php echo date('Y-m-d');?>" type="text" name="end_time"  value='<?php echo isset($set['end_time'])?$set['end_time']:''?>' /></div></div></td>
                            </tr>
                            <tr>
                                <td><div class="boxs"><div>活动玩法设置:</div></div></td>
                                <td>
                                <div class="boxs">
                                    <div style="background:#F0DBFF;padding:10px; height:155px">
                                    	<div><label class="control-checkbox-3"><input type="radio" <?php if(isset($set['mode']) && $set['mode']=='1') echo 'checked';?> <?php if(!isset($set['mode'])) echo 'checked';?> name="mode" value="1" />积分玩法</label></div>
                                        <p>会员拉新一个新会员获得活动邀金
                                        	<input type="text" name="invite_gold" value="<?php if(isset($set['invite_gold']) && !empty($set['invite_gold'])) echo $set['invite_gold'];?>" <?php if(!isset($set['mode']) || $set['mode']!='1'):?>disabled<?php endif;?> style="width:105px"/>
                                        </p>
                                        <p>活动邀金满
                                        <input type="text" name="full_gold" value="<?php if(isset($set['full_gold']) && !empty($set['full_gold'])) echo $set['full_gold'];?>" <?php if(!isset($set['mode']) || $set['mode']!='1'):?>disabled<?php endif;?> style="width:90px" />
                                        可获得
                                        <select name="exchange_card" style="width:105px" <?php if(!isset($set['mode']) || $set['mode']!='1'):?>disabled<?php endif;?>>
                                            <option value="0">请选择兑换券</option>
                                            <?php foreach ($exchange_card as $key=>$val){?>
                                                <option value="<?php echo $val['card_id']?>" <?php if(isset($set['mode']) && $set['mode']=='1' && isset($set['exchange_card']) && $set['exchange_card']==$val['card_id']):?> selected <?php endif;?>><?php echo $val['title']?></option>
                                            <?php }?>
                                        </select>
                                        </p>
                                        <p>兑换说明
                                        <input type="text" name="exchange_note" value="<?php if(isset($set['exchange_note'])) echo $set['exchange_note'];?>" <?php if(!isset($set['mode']) || $set['mode']!='1'):?>disabled<?php endif;?> style="width:260px" />
                                        </p>
                                    </div>
                                    <div style="background:#D9FFBD; padding:10px; height:155px ">
                                    	<div><label class="control-checkbox-3"><input type="radio" name="mode" <?php if(isset($set['mode']) && $set['mode']=='2') echo 'checked';?> value="2" />排行榜玩法</label></div>
                                        <p>根据用户的排行榜名次进行发奖</p>
                                        <p>前
                                        <input type="text" name="full_rank" value="<?php if(isset($set['full_rank']) && !empty($set['full_rank'])) echo $set['full_rank'];?>" <?php if(!isset($set['mode']) || $set['mode']!='2'):?>disabled<?php endif;?> style="width:90px" />
                                        名可获得
                                        <select name="exchange_card" style="width:155px" <?php if(!isset($set['mode']) || $set['mode']!='2'):?>disabled<?php endif;?>>
                                            <option value="0">请选择兑换券</option>
                                            <?php foreach ($exchange_card as $key=>$val){?>
                                                <option value="<?php echo $val['card_id']?>" <?php if(isset($set['mode']) && $set['mode']=='2' && isset($set['exchange_card']) && $set['exchange_card']==$val['card_id']):?> selected <?php endif;?>><?php echo $val['title']?></option>
                                            <?php }?>
                                        </select>
                                        </p>
                                        <p>活动奖励将在活动结束后生成最终排行名单，请在排行榜中发奖。 </p>
                                    </div>
                                 </div>
                                </td>
                            </tr>
                            <tr>
                                <td><div class="boxs"><div><b>老</b>会员奖励</div></div></td>
                                <td>
                                <div class="boxs">
                                	<div>
                                		<p>老会员每拉新一个新会员获得的奖励。<i>奖励自动发送</i></p>
                                    	<div><label class="control-checkbox"><input type="radio" <?php if(isset($set['old_reward_type']) && $set['old_reward_type']=='0'):?>checked<?php endif;?><?php if(!isset($set['old_reward_type']) && empty($set['old_reward_type'])):?>checked<?php endif;?> name="old_reward_type" value="0" /> 无奖励</label></div>
                                        <div>
                                        	<label class="control-checkbox" style="float:left; margin-right:10px"><input type="radio" <?php if(isset($set['old_reward_type']) && $set['old_reward_type']=='1'):?>checked<?php endif;?>  name="old_reward_type" value="1" /> 送</label>
                                            <div class="input-group">
                                                <input type="number" name="old_reward_value" placeholder="请输入积分数值" class="form-control" value="<?php if(isset($set['old_reward_type']) && $set['old_reward_type']=='1'):?><?php echo $set['old_reward_value'];?><?php endif;?>" <?php if(!isset($set['old_reward_type']) || $set['old_reward_type']!='1'):?>disabled<?php endif;?> />
                                                <span class="input-group-addon">积分</span>
                                              </div>
                                        </div>
                                        <div style="margin:10px 0">
                                        	<label class="control-checkbox" style="float:left; margin-right:10px"><input type="radio" <?php if(isset($set['old_reward_type']) && $set['old_reward_type']=='2'):?>checked<?php endif;?> name="old_reward_type" value="2" /> 送</label>
                                            <div class="input-group">
                                                <input type="number" name="old_reward_value" placeholder="请输入储值数值" class="form-control" value="<?php if(isset($set['old_reward_type']) && $set['old_reward_type']=='2'):?><?php echo $set['old_reward_value'];?><?php endif;?>" <?php if(!isset($set['old_reward_type']) || $set['old_reward_type']!='2'):?>disabled<?php endif;?> />
                                                <span class="input-group-addon">储值</span>
                                              </div>
                                        </div>
                                    	<div style="margin-bottom:10px">
                                    	    <label class="control-checkbox" style="float:left; margin-right:10px">
                                            <input type="radio" <?php if(isset($set['old_reward_type']) && $set['old_reward_type']=='3'):?>checked<?php endif;?> name="old_reward_type" value="3" /> 送</label>
                                        	<div class="input-group">
                                        	    <select name="old_reward_value" style="width:230px" class="form-control" <?php if(!isset($set['old_reward_type']) || $set['old_reward_type']!='3'):?>disabled<?php endif;?>>
                                                    <option value="0">选择优惠券</option>
                                                    <?php foreach ($cardlist as $key=>$val){?>
                                                        <option value="<?php echo $val['card_id']?>" <?php if(isset($set['old_reward_type']) && $set['old_reward_type']=='3' && $set['old_reward_value']==$val['card_id']):?> selected <?php endif;?>><?php echo $val['title']?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    	<div><label class="control-checkbox" style="float:left; margin-right:10px"><input type="radio" <?php if(isset($set['old_reward_type']) && $set['old_reward_type']=='4'):?>checked<?php endif;?> name="old_reward_type" value="4" /> 送</label>
                                        	<div class="input-group">
                                        	    <select name="old_reward_value" style="width:230px" class="form-control" <?php if(!isset($set['old_reward_type']) || $set['old_reward_type']!='4'):?>disabled<?php endif;?>>
                                                    <option value="0">选择会员礼包</option>
                                                    <?php foreach ($package_list as $key=>$val){?>
                                                        <option value="<?php echo $val['package_id']?>" <?php if(isset($set['old_reward_type']) && $set['old_reward_type']=='4' && $set['old_reward_value']==$val['package_id']):?> selected <?php endif;?>><?php echo $val['name']?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                 </div>
                                </td>
                            </tr>
                            <tr>
                                <td><div class="boxs"><div><b>新</b>会员奖励</div></div></td>
                                <td>
                                <div class="boxs">
                                	<div>
                                		<p>新会员注册后可获得的奖励</p>
                                    	<div><label class="control-checkbox-2"><input type="radio" <?php if(isset($set['new_reward_type']) && $set['new_reward_type']=='0'):?>checked<?php endif;?><?php if(!isset($set['new_reward_type']) && empty($set['new_reward_type'])):?>checked<?php endif;?> name="new_reward_type" value="0" /> 无奖励</label></div>
                                        <div>
                                        	<label class="control-checkbox-2" style="float:left; margin-right:10px">
                                        	<input type="radio" <?php if(isset($set['new_reward_type']) && $set['new_reward_type']=='1'):?>checked<?php endif;?> name="new_reward_type" value="1" /> 送</label>
                                            <div class="input-group">
                                                <input type="number" name="new_reward_value" placeholder="请输入积分数值" class="form-control" value="<?php if(isset($set['new_reward_type']) && $set['new_reward_type']=='1'):?><?php echo $set['new_reward_value'];?><?php endif;?>" <?php if(!isset($set['new_reward_type']) || $set['new_reward_type']!='1'):?>disabled<?php endif;?> />
                                                <span class="input-group-addon">积分</span>
                                              </div>
                                        </div>
                                        <div style="margin:10px 0">
                                        	<label class="control-checkbox-2" style="float:left; margin-right:10px">
                                        	<input type="radio" <?php if(isset($set['new_reward_type']) && $set['new_reward_type']=='2'):?>checked<?php endif;?> name="new_reward_type" value="2" /> 送</label>
                                            <div class="input-group">
                                                <input type="number" name="new_reward_value" placeholder="请输入储值数值" class="form-control" value="<?php if(isset($set['new_reward_type']) && $set['new_reward_type']=='2'):?><?php echo $set['new_reward_value'];?><?php endif;?>" <?php if(!isset($set['new_reward_type']) || $set['new_reward_type']!='2'):?>disabled<?php endif;?> />
                                                <span class="input-group-addon">储值</span>
                                              </div>
                                        </div>
                                    	<div style="margin-bottom:10px">
                                    	    <label class="control-checkbox-2" style="float:left; margin-right:10px">
                                    	    <input type="radio" <?php if(isset($set['new_reward_type']) && $set['new_reward_type']=='3'):?>checked<?php endif;?> name="new_reward_type" value="3" /> 送</label>
                                        	<div class="input-group">
                                        	    <select name="new_reward_value" style="width:230px" class="form-control" <?php if(!isset($set['new_reward_type']) || $set['new_reward_type']!='3'):?>disabled<?php endif;?>>
                                                    <option value="0">选择优惠券</option>
                                                    <?php foreach ($cardlist as $key=>$val){?>
                                                        <option value="<?php echo $val['card_id']?>" <?php if(isset($set['new_reward_type']) && $set['new_reward_type']=='3' && $set['new_reward_value']==$val['card_id']):?> selected <?php endif;?>><?php echo $val['title']?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    	<div>
                                    	    <label class="control-checkbox-2" style="float:left; margin-right:10px">
                                    	    <input type="radio" <?php if(isset($set['new_reward_type']) && $set['new_reward_type']=='4'):?>checked<?php endif;?> name="new_reward_type" value="4" /> 送</label>
                                        	<div class="input-group">
                                        	    <select name="new_reward_value" style="width:230px" class="form-control" <?php if(!isset($set['new_reward_type']) || $set['new_reward_type']!='4'):?>disabled<?php endif;?>>
                                                    <option value="0">选择会员礼包</option>
                                                    <?php foreach ($package_list as $key=>$val){?>
                                                        <option value="<?php echo $val['package_id']?>" <?php if(isset($set['new_reward_type']) && $set['new_reward_type']=='4' && $set['new_reward_value']==$val['package_id']):?> selected <?php endif;?>><?php echo $val['name']?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                 </div>
                                </td>
                            </tr>
                        </table>
                        <div class="boxs">
                            <div></div>
                            <div class="">
                                <button id="sub_btn" type="submit" class="btn btn-primary dosave">保存</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                <?php echo form_close() ?>
            </div>
        </section><!-- /.content -->
        <script>
        $(function(){
            $(document).on('click','.control-checkbox',function (e) {
                var parobj = $(this).parent().parent().parent();
                $(parobj).find("input[type='number']").prop('disabled',true);
                $(parobj).find("select").prop('disabled',true);
                $(this).next().find("input[type='number']").prop('disabled',false);
                $(this).next().find("select").prop('disabled',false);
            });

            $(document).on('click','.control-checkbox-2',function (e) {
                var parobj = $(this).parent().parent().parent();
                $(parobj).find("input[type='number']").prop('disabled',true);
                $(parobj).find("select").prop('disabled',true);
                $(this).next().find("input[type='number']").prop('disabled',false);
                $(this).next().find("select").prop('disabled',false);
            });

            $(document).on('click','.control-checkbox-3',function (e) {
                var parobj = $(this).parent().parent().parent(), preobj = $(this).parent().parent();
                $(parobj).find("input[type='text']").prop('disabled',true);
                $(parobj).find("select").prop('disabled',true);
                $(preobj).find("input[type='text']").prop('disabled',false);
                $(preobj).find("select").prop('disabled',false);
            });

			var submit_tag=true;
            $(':input[name=start_time]').datetimepicker({
                format:'Y-m-d',
                lang:'ch',
                timepicker:false,
                scrollInput:false
            });
            $(':input[name=end_time]').datetimepicker({
                format:'Y-m-d',
                lang:'ch',
                timepicker:false,
                scrollInput:false
            });

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
                                    case 'start_time':
                                        st = value;
                                        if (!value) {
                                            _null = true;
                                            _msg = '请输入开始时间';
                                        }
                                        break;
                                    case 'end_time':
                                        et = value;
                                        if (!value) {
                                            _null = true;
                                            _msg = '请输入结束时间';
                                        }
                                        break;
                                    case 'name':
                                        if (!value) {
                                            _null = true;
                                            _msg = '请输入活动名称';
                                        }
                                        break;
                                }
                                if (_null === true) break;
                            }

                            if (_null === true) {
                                inputo.focus();
                                return false;
                            }
                            if(st>=et) {
                                art.dialog({
                                    title:'提示',
                                    icon:'warning',
                                    content:'结束时间必须大于开始时间',
                                    time:3,
                                    ok:false,
                                    cancel:false
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
                                        window.location.href = data.data.url;
                                    },
                                    cancel: function () {
                                        window.location.reload();
                                    },
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
    </div><!-- /.content-wrapper -->
    <?php /* Footer Block @see footer.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php'; ?>
    <?php /* Right Block @see right.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php'; ?>
</div><!-- ./wrapper -->
<?php /* Right Block @see right.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php'; ?>
</body>
</html>
