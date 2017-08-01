<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/ajaxForm.js"></script>
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/js/artDialog/css/ui-dialog.css">
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/datepicker3.css'>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/bootstrap-datepicker.js'></script>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js'></script>
<script type="text/javascript">
    //全局变量
    var GV = {
        DIMAUB: "<?php echo base_url();?>",
        JS_ROOT: "<?php echo FD_PUBLIC ?>/js/",
    };
</script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/wind.js"></script>
<style type="text/css">
    html, body{min-width: 100%;}
.table tr>th>label{padding-left: 20px;}
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
		<h3 class="box-title"><?php if ( isset($packageInfo['package_id']) && $packageInfo['package_id'] ):?>编辑套餐<?php else:?>添加套餐<?php endif;?></h3>
	</div>
    <?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
        <input type="hidden" name="package_id" value="<?php if (isset($packageInfo['package_id']) && $packageInfo['package_id']) echo $packageInfo['package_id'];?>" />

        <?php if(isset($packageInfo['package_element']) && !empty($packageInfo['package_element']) && is_array($packageInfo['package_element'])):?>
            <?php foreach ($packageInfo['package_element'] as $ak => $pk):?>
                <input type="hidden" name="<?php if (isset($ak) && $ak) echo $packageInfo['package_element'][$ak].'_element_id';?>" value="<?php if (isset($ak) && $ak) echo $ak;?>" />
            <?php endforeach;?>
        <?php endif;?>
    <table class="table">
            <thead>
                <tr>
                    <th>礼包名称</th>
                    <th>
                        <input class="form-control" name="name" value="<?=!empty($packageInfo['name'])?$packageInfo['name']:''?>" placeholder="请填套餐名称" />
                    </th>
                </tr>
                <tr>
                    <th>礼包描述</th>
                    <th>
                        <input class="form-control" name="remark" value="<?=!empty($packageInfo['remark'])?$packageInfo['remark']:''?>" placeholder="请填套餐描述" />
                    </th>
                </tr>
                <tr>
                    <th>赠送积分</th>
                    <th>
                        <input class="form-control" name="credit" value="<?=!empty($packageInfo['credit'])?$packageInfo['credit']:0?>" placeholder="请填赠送积分" />
                    </th>
                </tr>
                <tr>
                    <th>赠送储值</th>
                    <th>
                        <input class="form-control" name="deposit" value="<?=!empty($packageInfo['balance'])?$packageInfo['balance']:''?>" placeholder="请填赠送储值" />
                    </th>
                </tr>
                <tr>
                    <th>赠送等级</th>
                    <th>
                        <select class="form-control card_type_c" name="membership" >
                            <option value=""  >--请选择等级--</option>
                            <?php foreach ($level_list as $key => $item):?>
                            <option value="<?php echo $item['member_lvl_id'];?>" <?php if(!empty($packageInfo['lvl_name']) && $packageInfo['lvl_name']==$item['member_lvl_id']):?> selected <?php endif;?>><?php echo $item['lvl_name'];?></option>
                            <?php endforeach;?>
                        </select>
                    </th>
                </tr>
                <tr>
                    <th>是否激活</th>
                    <th>
                        <label><input type="radio" name="is_active" value="f" <?php if(!empty($packageInfo['is_active'])):?><?php if($packageInfo['is_active']=='f' ):?> checked="checked" <?php endif;?><?php else:?>checked="checked"<?php endif;?>>否</label>

                        <label><input type="radio" name="is_active" value="t" <?php if(isset($packageInfo['is_active']) && $packageInfo['is_active']=='t' ):?>checked="checked"<?php endif;?>>是</label>
                    </th>
                </tr>
            </thead>
        </table>
        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
            <thead>
                <tr>
                    <th>赠送卡券</th>
                    <th>赠送数量</th>
                    <th width="120">状态</th>
                    <th width="120">操作</th>
                </tr>
            </thead>
            <tbody class="addinfo">
                <?php if(isset($packageInfo['card']) && !empty($packageInfo['card']) && is_array($packageInfo['card']) && !empty($card_list)):?>
                    <?php foreach ($packageInfo['card'] as $kc => $itc):?>
                        <?php if($itc['status'] == 1):?>
                        <tr class="add1" data-add="lock">
                            <td>
                                <select class="form-control card-list edit_<?=$kc;?>" name="cards[]">
                                    <option value="" attr-msg="" attr-disabled="" attr-color="">--请选择卡劵--</option>
                                    <?php foreach ($card_list as $kt => $cl):?>
                                        <option value="<?php echo $cl['card_id'];?>" <?php if($cl['card_id']==$itc['card_id']):?>selected<?php endif;?> attr-msg="<?=$cl['err_msg']?>" attr-disabled="<?=$cl['card_disabled']?>" attr-color="<?=$cl['status_color']?>">
                                            <?php echo $cl['title'];?>
                                        </option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control edit_<?=$kc;?>" name="cardvalue[]" value="<?php if(!empty($itc['count'])) echo $itc['count'];?>" placeholder="请填赠送数量" />
                            </td>
                            <td>
                                <span style="<?=$itc['status_color']?>" ><?=$itc['err_msg']?></span>
                            </td>
                            <td>
                                <input type="hidden" name="status[]" class="edit_<?=$itc['element_id'];?>" value="<?php echo $itc['status'];?>" <?php if(isset($itc['status']) && $itc['status'] == 2):?>disabled<?php endif;?> />
                                <input type="hidden" name="element_id[]" class="edit_<?=$itc['element_id'];?>" value="<?=$itc['element_id']?>" <?php if(isset($itc['status']) && $itc['status'] == 2):?>disabled<?php endif;?> />
                                <?php if(isset($itc['status'])):?>
                                    <a href="<?php echo EA_const_url::inst()->get_url('*/*/del_element').'?element_id='.$itc['element_id']; ?>" class="del" data-status="<?php echo $itc['status'];?>" data-id="<?=$itc['element_id'];?>">
                                        <?php if($itc['status'] == 1):?>删除<?php else:?>还原<?php endif;?>
                                    </a>
                                <?php endif;?>
                            </td>
                        </tr>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>
                <tr class="active">
                    <td>
                        <button type="button" class="btn btn-primary plus">+</button>
                        <button type="button" class="btn btn-primary down">-</button>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            </tbody>
            </table><div class="box-footer ">
                    <button type="submit" class="btn btn-primary dosave">保存</button>
                </div>
    <?php echo form_close() ?>
		<!-- /.box-footer -->
</div>

</div> -->
<script type="text/javascript">
$(function(){
    var count = 1,ok_url="<?php echo EA_const_url::inst()->get_url('*/*');?>",card_list = <?php if(!empty($card_list) && is_array($card_list)) echo json_encode($card_list);?>;

    //添加赠送卡劵标签
    $(document).on('click','.plus',function (e){
        e.preventDefault();
        if (isObject(card_list)){
           var pidobj =$(this).parent().parent(),pre_lab = $(pidobj).prev(),_str = '';
           _str += '<tr class="add add_temp"> <td> <select class="form-control card_type_c card-list" name="cards[]" > <option value="" attr-msg="" attr-disabled="" attr-color="">--请选择卡劵--</option>';
           for (i in card_list){
               var _disabled = card_list[i].card_disabled?card_list[i].card_disabled:'';
               var _msg = card_list[i].err_msg?card_list[i].err_msg:'';
               var _color = card_list[i].status_color?card_list[i].status_color:'';
               var title = card_list[i].title ? card_list[i].title : '暂无名称',cid = card_list[i].card_id ? card_list[i].card_id : '0';
               _str += '<option value="'+cid+'" attr-msg="'+ _msg +'" attr-disabled="'+ _disabled +'" attr-color="'+ _color +'">'+title+'</option>';
           }
           _str += '</select></td>';
           _str += '<td> <input type="number" class="form-control" name="cardvalue[]" value="" placeholder="请填赠送数量" /> </td> <td></td> <td></td></tr>';
           $(pidobj).before(_str);
       }
       return false;
    });

    //删除赠送卡劵标签
    $(document).on('click','.down',function (e) {
        e.preventDefault();
        var pidobj =$(this).parent().parent(),pre_lab = $(pidobj).prev(),lock = $(pre_lab).data("add");
        if(lock) return false;
        $(pre_lab).remove();
    });

    //
    $(document).on("change",".card-list",function (e) {
        var _disabled = $(this).find("option:selected").attr("attr-disabled");
        var _msg = $(this).find("option:selected").attr("attr-msg");
        var _color = $(this).find("option:selected").attr("attr-color");
        var __disabled = _disabled == 'disabled' ? true : false;
//        $(this).parent().siblings().find("input").prop("disabled",__disabled);
        $(this).parent().parent().find("td").eq(2).html('<span style="'+ _color +'">'+ _msg +'</span>');
    });

    $(document).on('click','.del',function (e) {
        e.preventDefault();
        var url = this.href,obj=$(this),onthis = this,ele_id = obj.data('id'),status = obj.data('status'),prev = obj.parent().prev(),input_prev = obj.prev(),surl = url+'&status='+status,content = status == 1 ? '确定要删除吗?' : '确定要还原吗?';
        Wind.use("ajaxForm","artDialog","art_dialog", function () {
            dialog({
                title:false,
                content:content,
                align:'left',
                btnStyle:'ui-dialog-mini',
                okValue: '确定',
                cancel: function () {
                    this.close();
                    onthis.focus(); //关闭时让触发弹窗的元素获取焦点
                    return true;
                },
                ok: function () {
                    var dthis = this;
                    var text = $.trim(obj.text());
                    obj.prop('disabled', true).addClass('disabled').text(text+'中...');
                    $.ajax({
                        url:surl,
                        type:'get',
                        dataType:'json',
                        timeout:6000,
                        success: function (data) {
                            console.log(data);
                            dthis.close();
                            if(data.status==1){
                                obj.parent().parent().remove();
                            }else{
                                var d = dialog({
                                    content: data.message,quickClose:true
                                });
                                d.showModal();
                                setTimeout(function () {
                                    d.close().remove();
                                }, 3000);
                                if(data.status == 2){
                                    obj.parent().parent().remove();
                                }
                            }
                        },
                        error: function () {
                            var d = dialog({
                                content: '请求异常,请刷新页面试试!',quickClose:true
                            });
                            d.showModal(onthis);
                            setTimeout(function () {
                                d.close().remove();
                            }, 3000);
                            var text = $.trim(obj.text());
                            obj.prop('disabled',false).removeClass('disabled').text(text.replace('中...', ''));
                        }
                    });
                },
                padding: 10,
                quickClose: true,
                cancelValue: '关闭'
            }).show(onthis);
        });
    });

    Wind.use("ajaxForm","artDialog", function (){
        $(document).on('click','.dosave',function (e) {
            e.preventDefault();
            var btn = $(this);
            var form = $('.form-horizontal'),form_url=form.attr("action");
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
                url:form_url,
                dataType:'json',
                beforeSubmit: function(arr, $form, options){
                    /*验证提交数据*/
                    var _null = false, _msg = '';
                    for(i in arr){
                        var name = arr[i].name,value=$.trim(arr[i].value);
                        if(name == 'name' && !value) {
                            _null = true; _msg = '请填写礼包名称';break;
                        }

                        if(name == 'credit' && (!value || value=='0')) arr[i].value='0.00';
                        if(name == 'deposit' && (!value || value=='0')) arr[i].value='0.00';
                        if(name == 'membership' && (!value || value=='0')) arr[i].value='null';
                    }

                    if(_null === true) {
                        art.dialog({
                            title: '提示',
                            fixed: true,
                            icon: 'warning',
                            content: _msg,
                            cancelVal: '了解',
                            ok: false,
                        });
                        return false;
                    }

                    /*判断卡劵是否重复*/
                    var selob=$("select[name='cards[]']");
                    var cards=[],ison=false,issel=false;
                    selob.each(function (ide,ite) {
                        if($(ite).parent().parent().is(":visible")){
                            if($.inArray(ite.value,cards) > -1 && ite.value > 0) {
                                ison = true;return false;
                            }
                            cards.push(ite.value);
                            if(!ite.value && ite.disabled === false) issel=true;
                        }
                    });

                    if(ison===true){
                        art.dialog({
                            title: '提示',
                            fixed: true,
                            icon: 'warning',
                            content: '选择卡劵有重复',
                            cancelVal: '了解',
                            ok: false,
                            cancel: true
                        });
                        return false;
                    }
                    if(issel===true){
                        art.dialog({
                            title: '提示',
                            fixed: true,
                            icon: 'warning',
                            content: '请选择卡券',
                            cancelVal: '了解',
                            ok: false,
                            cancel: true
                        });
                        return false;
                    }
                    /*end*/
                    /*end*/

                    var text = btn.text();
                    btn.prop('disabled', true).addClass('disabled').text(text+'中...');
                },
                success: function(data){
                    var btnval = data.data.isadd === false ? '编辑' : '添加';
                    if(data.status==1){
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
                                return true;
                            },
                        });
                    }else{
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
                    btn.prop('disabled',false).removeClass('disabled').text(text.replace('中...', ''));
                },
                error:function () {
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

function isObject(obj){
    return (typeof obj=='object');
}
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
