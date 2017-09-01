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
    p.remind-tip{text-indent: 2em;}
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
            <?php echo form_open(EA_const_url::inst()->get_url('*/*/save_setting'), array('class'=>'form-inline','enctype'=>'multipart/form-data')); ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">邀请好友设置</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                        <tbody>
                        <tr>
                            <td width="22%">邀请好友是否启动</td>
                            <td>
                                <div class="control-group p_3">
                                    <div class="controls1">
                                        <div class="controls1 m_r_75">
                                            <label class="control-checkbox">
                                                <input type="radio" name="is_open" <?php if(!empty($is_open) && $is_open=='t'):?>checked<?php endif;?> value="t" />
                                                <span class="m_r_20 m_l_10 width_20">是</span>
                                            </label>
                                        </div>
                                        <div class="controls1">
                                            <label class="control-checkbox">
                                                <input type="radio" name="is_open" <?php if(empty($is_open) || (!empty($is_open) && $is_open=='f')):?>checked<?php endif;?> value="f" />
                                                <span class="m_r_20 m_l_10 width_20">否</span>
                                            </label>
                                            <span>（活动关闭后，重新打开，所有邀请次数重新计算）</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>设置会员邀请好友权限的有效期为</td>
                            <td>
                                <div class="control-group p_3">
                                    <div class="controls1">
                                        <div class="controls1 m_r_75">
                                            <input type="number" style="width: 50px;" min="1" name="effective_time" value="<?=!empty($effective_time)?$effective_time:'';?>" placeholder="1" />
                                            <span>年（默认在12月31日23:59:59失效，1月1日00:00:01生效新的会员权益）</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>升级保留邀请资格</td>
                            <td>
                                <div class="control-group p_3">
                                    <div class="controls1">
                                        <div class="controls1 m_r_75">
                                            <label class="control-checkbox">
                                                <input type="radio" name="is_keep" disabled <?php if((!empty($is_keep) && $is_keep=='t')):?>checked<?php endif;?> value="t" />
                                                <span class="m_r_20 m_l_10 width_20">保留</span>
                                            </label>
                                        </div>
                                        <div class="controls1">
                                            <label class="control-checkbox">
                                                <input type="radio" name="is_keep" <?php if(empty($is_keep) || !empty($is_keep) && $is_keep=='f'):?>checked<?php endif;?> value="f" />
                                                <span class="m_r_20 m_l_10 width_20">不保留</span>
                                            </label>
                                            <span>说明：会员达到升级标准升级后，是否保留低等级会员邀请权益？</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>被邀请的新会员如何激活邀请权益？</td>
                            <td>
                                <div class="control-group p_3">
                                    <div class="controls1">
                                        <div class="controls1 m_r_75">
                                            <label class="control-checkbox">
                                                <input type="radio" name="to_activate" <?php if(empty($to_activate) || (!empty($to_activate) && $to_activate=='f')):?>checked<?php endif;?> value="f" />
                                                <span class="m_r_20 m_l_10 width_20">无需激活</span>
                                            </label>
                                        </div>
                                        <div class="controls1">
                                            <label class="control-checkbox">
                                                <input type="radio" disabled name="to_activate" <?php if(!empty($to_activate) && $to_activate=='t'):?>checked<?php endif;?> value="t" />
                                                <span class="m_r_20 m_l_10 width_20">该会员当年有入住</span>
                                            </label>
                                            <input type="number" disabled style="width: 50px;" min="0" name="activate_value" value="<?=!empty($activate_value)?$activate_value:'';?>" placeholder="1" />
                                            <span>间夜即可激活</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="box-header with-border" style="margin-top: 10px;">
                        <h3 class="box-title">邀请权益</h3>
                    </div>
                    <?php if(!empty($member_lvl)):?>
                    <table class="table table-bordered table-striped table-condensed dataTable no-footer" style="margin-top: 5px;">
                        <tbody id="nav-lvl">
                            <?php if(!empty($hold_lvl_group)):?>
                            <?php $labeli=0;?>
                            <?php foreach ($hold_lvl_group as $mlvl => $vlvl):?>
                            <tr class="main_lvl" data-fk="<?=$labeli?>">
                                <td width="18%">
                                    <div class="control-group p_3">
                                        <select name="main_lvl[<?=$labeli?>]" class="form-control">
                                            <?php foreach ($member_lvl as $vo):?>
                                                <option value="<?=$vo['member_lvl_id']?>" <?php if($mlvl==$vo['member_lvl_id']):?>selected<?php endif;?>><?=$vo['lvl_name']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <?php $labeli2=0;?>
                                    <?php foreach ($vlvl as $lvlid => $lvlcount):?>
                                    <div class="control-group p_3 child-lvl-group">
                                        <div class="controls1">
                                            <div class="controls1 m_r_75">
                                                <span>可邀请</span>
                                                <input type="number" min="1" style="width: 50px;" name="lvl_cout[<?=$labeli?>][]" value="<?=!empty($lvlcount)?$lvlcount:'';?>" placeholder="1" />
                                                <span>个</span>
                                                <select name="group_lvl[<?=$labeli?>][]" class="form-control">
                                                    <?php foreach ($member_lvl as $vo):?>
                                                        <option value="<?=$vo['member_lvl_id']?>" <?php if($lvlid==$vo['member_lvl_id']):?>selected<?php endif;?>><?=$vo['lvl_name']?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <button type="button" class="btn btn-success btn-sm plus-labels"><i class="fa fa-plus"></i></button>
                                                <?php if($labeli2>0):?>
                                                    <button type="button" class="btn btn-default btn-sm minus-labels"><i class="fa fa-minus"></i></button>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $labeli2++;?>
                                    <?php endforeach;?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger remove-labels-group"><i class="fa fa-remove"></i></button>
                                </td>
                            </tr>
                            <?php $labeli++;?>
                            <?php endforeach;?>
                            <?php endif;?>
                            <tr>
                                <td colspan="3">
                                    <button type="button" class="btn btn-sm btn-primary plus-labels-group"><i class="fa fa-plus"></i>增加邀请权益</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php endif;?>
                    <div class="" style="padding:10px 0;width:420px;">
                        <button id="bnt_sub" type="submit" class="btn btn-primary dosave">保存</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div id="tip-box" style="display: none;">
    <p class="remind-tip">邀请好友活动开始后，将马上生效，活动以自然年计算。</p>
    <p class="remind-tip">下一自然年开始后，上一年活动将自动结束，未使用的邀请权益将自动清零重新计算。</p>
    <p class="remind-tip">如您选择了邀请权益需要激活的话，则下一自然年开始后，所有权益默认不激活。</p>
    <p class="remind-tip" style="color: red;font-size: 16px;">活动期间所有活动内容进行修改，则重新计算所有邀请权益。</p>
    <p class="remind-tip" style="color: red;font-size: 16px;">活动关闭后再重新打开，所有权益将重新计算，不会沿用之前的活动数据。</p>
    <h4 style="text-align: center;color: red;">请慎重保存！</h4>
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
        //添加主等级群标签
        $(document).on('click','.plus-labels-group',function (e) {
            e.stopPropagation();
            var btn=$(this),
                _member_lvl='<?=json_encode($member_lvl)?>',
                member_lvl=eval("("+_member_lvl+")"),
                lvl_len=member_lvl.length;

            var k_len = $(".main_lvl").length;
            if(k_len==lvl_len){
                if(btn.parent().find('span').length==0){
                    btn.parent().append("<span style='color: #ff0040;'>最多添加"+lvl_len+"组</span>");
                    setTimeout(function () {
                        btn.parent().find('span').fadeOut('normal', function () {
                            btn.parent().find('span').remove();
                        });
                    }, 1000);
                }
                return false;
            }
            var tr=$('<tr/>',{class:'main_lvl',"data-fk":k_len}); //tr组
            var tr_td1=$('<td/>',{width:'18%'}).appendTo(tr),
                tr_td2=$('<td/>').appendTo(tr),
                tr_td3=$('<td/>').appendTo(tr); //td组
            //div组
            var tr_td1_div1=$('<div/>',{class:'control-group p_3'}).appendTo(tr_td1),
                tr_td2_div1=$('<div/>',{class:'control-group p_3 child-lvl-group'}).appendTo(tr_td2);

            var tr_td2_div1_1=$('<div/>',{class:'controls1'}).appendTo(tr_td2_div1),
                tr_td2_div1_1_1=$('<div/>',{class:'controls1 m_r_75'}).appendTo(tr_td2_div1_1);

            var tr_td1_div1_select1=$('<select/>',{name:'main_lvl['+k_len+']',class:'form-control'}).appendTo(tr_td1_div1),
                tr_td2_div1_1_1_select2=$('<select/>',{name:'group_lvl['+k_len+'][]',class:'form-control'});

            if(member_lvl.length>0){
                for(var lvl in member_lvl){
                    $('<option/>',{value:member_lvl[lvl].member_lvl_id}).html(member_lvl[lvl].lvl_name).appendTo(tr_td1_div1_select1);
                }
            }

            $('<span/>').html('可邀请').appendTo(tr_td2_div1_1_1);
            tr_td2_div1_1_1.append('&nbsp;');
            var tr_td2_div1_1_1_span2=$('<span/>').html('个');
            $('<input/>',{type:'number',min:1,style:'width: 50px;',name:'lvl_cout['+k_len+'][]',placeholder:1}).appendTo(tr_td2_div1_1_1);
            tr_td2_div1_1_1.append('&nbsp;');
            tr_td2_div1_1_1_span2.appendTo(tr_td2_div1_1_1);
            tr_td2_div1_1_1.append('&nbsp;');
            if(member_lvl.length>0){
                for(var lvl in member_lvl){
                    $('<option/>',{value:member_lvl[lvl].member_lvl_id}).html(member_lvl[lvl].lvl_name).appendTo(tr_td2_div1_1_1_select2);
                }
            }
            tr_td2_div1_1_1_select2.appendTo(tr_td2_div1_1_1);
            tr_td2_div1_1_1.append('&nbsp;');

            $('<button/>',{type:'button',class:'btn btn-success btn-sm plus-labels'}).html('<i class="fa fa-plus"></i>').appendTo(tr_td2_div1_1_1);
            $('<button/>',{type:'button',class:'btn btn-sm btn-danger remove-labels-group'}).html('<i class="fa fa-remove"></i>').appendTo(tr_td3);
            $(this).parent().parent().before(tr);//输出
        });

        //删除主等级群标签
        $(document).on('click','.remove-labels-group',function (e){
            e.stopPropagation();
            $(this).parent().parent().remove();
        });

        //添加子等级标签
        $(document).on('click','.plus-labels',function (e){
            e.stopPropagation();
            var btn=$(this),
                btn_p=$(this).parent().parent().parent(),
                _member_lvl='<?=json_encode($member_lvl)?>',
                member_lvl=eval("("+_member_lvl+")"),
                k_len = btn_p.parent().parent().data("fk"),
                child_k_len = btn_p.parent().find(".child-lvl-group").length,
                lvl_len=member_lvl.length;

            if(child_k_len==lvl_len){
                if(btn.parent().find('span.f-tip').length==0){
                    btn.parent().append("<span class='f-tip' style='color: #ff0040;'>最多添加"+lvl_len+"组</span>");
                    setTimeout(function () {
                        btn.parent().find('span.f-tip').fadeOut('normal', function () {
                            btn.parent().find('span.f-tip').remove();
                        });
                    }, 1000);
                }
                return false;
            }
            var div1=$('<div/>',{class:'control-group p_3 child-lvl-group'});
            var div1_1=$('<div/>',{class:'controls1'}).appendTo(div1),
                div1_1_1=$('<div/>',{class:'controls1 m_r_75'}).appendTo(div1_1);
            var div1_1_1_select1=$('<select/>',{name:'group_lvl['+k_len+'][]',class:'form-control'});
            $('<span/>').html('可邀请').appendTo(div1_1_1);
            div1_1_1.append('&nbsp;');
            var div1_1_1_span1=$('<span/>').html('个');
            $('<input/>',{type:'number',min:1,style:'width: 50px;',name:'lvl_cout['+k_len+'][]',placeholder:1}).appendTo(div1_1_1);
            div1_1_1.append('&nbsp;');
            div1_1_1_span1.appendTo(div1_1_1);
            div1_1_1.append('&nbsp;');
            if(member_lvl.length>0){
                for(var lvl in member_lvl){
                    $('<option/>',{value:member_lvl[lvl].member_lvl_id}).html(member_lvl[lvl].lvl_name).appendTo(div1_1_1_select1);
                }
            }

            div1_1_1_select1.appendTo(div1_1_1);
            div1_1_1.append('&nbsp;');

            $('<button/>',{type:'button',class:'btn btn-success btn-sm plus-labels'}).html('<i class="fa fa-plus"></i>').appendTo(div1_1_1);
            div1_1_1.append('&nbsp;');
            $('<button/>',{type:'button',class:'btn btn-default btn-sm minus-labels'}).html('<i class="fa fa-minus"></i>').appendTo(div1_1_1);
            btn_p.after(div1);
        });

        //删除子等级标签
        $(document).on('click','.minus-labels',function (e){
            e.stopPropagation();
            $(this).parent().parent().parent().remove();
        });

        Wind.use("ajaxForm","artDialog", function () {
            $(document).on('click', '.dosave', function (e) {
                e.preventDefault();
                var btn = $(this),
                    form = $('.form-inline');

                //ie处理placeholder提交问题
                if ($.support.msie) {
                    form.find('[placeholder]').each(function () {
                        var input = $(this);
                        if (input.val() == input.attr('placeholder')) {
                            input.val('');
                        }
                    });
                }

                var idv=$("input[name='id']").val(),is_open='f';
                $("input[name='is_open']").each(function (index,item) {
                    if(item.checked===true) is_open = item.value;
                });
                art.dialog({
                    title:'活动须知请认真阅读',
                    width:'30%',
                    content:$("#tip-box").html(),
                    fixed:true,
                    ok:function (){
                        var art_dialog = this;
                        if(idv && is_open=='t'){
                            art.dialog({
                                title:'提示',
                                content:'确定开启邀请好友活动？',
                                ok:function () {
                                    submit_post(btn,form);
                                    art_dialog.close();
                                },
                                cancel:function () {
                                    art_dialog.close();
                                },
                                close:function () {
                                    art_dialog.close();
                                },
                                okVal:'开启',
                                cancelVal:'再看看'
                            });
                        }else if(idv && is_open=='f'){
                            art.dialog({
                                title:'提示',
                                content:'确定关闭邀请好友活动？',
                                ok:function () {
                                    submit_post(btn,form);
                                    art_dialog.close();
                                },
                                cancel:function () {
                                    art_dialog.close();
                                },
                                close:function () {
                                    art_dialog.close();
                                },
                                okVal:'关闭',
                                cancelVal:'再看看'
                            });
                        }else if(!idv){
                            art.dialog({
                                title:'提示',
                                content:'确定保存邀请好友活动？',
                                ok:function () {
                                    submit_post(btn,form);
                                    art_dialog.close();
                                },
                                cancel:function () {
                                    art_dialog.close();
                                },
                                close:function () {
                                    art_dialog.close();
                                },
                                okVal:'确定',
                                cancelVal:'再看看'
                            });
                        }
                        return false;
                    },
                    cancel:true,
                    okVal:'我已了解',
                    cancelVal:'再想想'
                });
                return false;
            });
        });
    });

    function submit_post(btn,form) {
        var ok_url = "<?php echo EA_const_url::inst()->get_url('*/*/');?>",form_url = form.attr("action");
        form.ajaxSubmit({
            url: form_url,
            dataType: 'json',
            beforeSubmit: function (arr) {
                /*验证提交数据*/
                var _null = false, _msg = '',_inputo = null;
                for (var i in arr) {
                    var name = arr[i].name, value = $.trim(arr[i].value);
                    _inputo = $("input[name='"+name+"']");
                    switch (name) {
                        case 'effective_time':
                            if (!value) {
                                _null = true;
                                _msg = '请输入有效期';
                            }
                            break;
                        case 'activate_value':
                            var to_activate = $("input[name='to_activate']").val();
                            if (!value && to_activate=='t') {
                                _null = true;
                                _msg = '请输入激活会员邀请权益所需间夜数';
                            }
                            break;
                    }
                    if (_null === true) break;
                }

                if (_null === true) {
                    _inputo.focus();
                    if(btn.parent().find('span').length==0){
                        btn.parent().append("<span style='color: #ff0040;'>"+_msg+"</span>");
                        setTimeout(function () {
                            btn.parent().find('span').fadeOut('normal', function () {
                                btn.parent().find('span').remove();
                            });
                        }, 3000);
                    }
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
    }
</script>
</body>
</html>