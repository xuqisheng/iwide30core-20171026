<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<link href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/css/AdminMember.css" rel="stylesheet" />
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

<!-- 二维码弹层 -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="background:#fff;width:280px;height:340px;margin:220px auto;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 >请用微信扫一扫</h4>
    </div>
    <div class="modal-body" style="margin:10px 0 15px 15px ;text-align:center;">
        <img id="qrcode-img" src="
                <?php echo str_replace('vapi/', '', PMS_PATH_URL).'tool/qr/get?str='."http://".$public['domain']."/index.php/membervip/center?id=".$public['inter_id']; ?>
                " />
    </div>
</div>

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModal2Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModal2Label">选择导航图片</h4>
            </div>
            <div class="modal-body">
                <?php foreach ($icon_conf as $key=>$vo):?>
                    <div class="nav-box nav-icon select-icon" data-icon="<?=$key?>" <?php if(strlen($vo)>12):?>title="<?=$vo?>"<?php endif;?>>
                        <p class="icon-box ui_<?=$key?>"></p>
                        <p class="text"><?=$vo?></p>
                    </div>
                <?php endforeach;?>
            </div>
            <!--            <div class="modal-footer">-->
            <!--                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>-->
            <!--                <button type="button" class="btn btn-primary">确定</button>-->
            <!--            </div>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<div class="wrapper">
    <?php /* 顶部导航 */echo $block_top; ?>
    <?php /* 左栏菜单 */echo $block_left; ?>

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
                    <h3 class="box-title">
                        <strong>手机访问：</strong>
                        <code><?php if(isset($public)) echo "http://".$public['domain']."/index.php/membervip/center?id=".$public['inter_id'];?></code>或<a data-toggle="modal" data-target="#myModal" href="#" ><code>打开二维码</code></a>，用微信扫一扫
                    </h3>
                </div>
                    <!-- /.box-footer -->
            </div>
            <!-- /.box -->

            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">会员中心配置</h3>
                </div>
                <?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_membernav'), array('class'=>'form-inline')); ?>
                <div class="box-body">
                    <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                    <thead>
                        <tr>
                            <th width="11%">导航分组</th>
                            <th width="8%">导航图标</th>
                            <th width="12%">标签名</th>
                            <th width="55%">超链接</th>
                            <th>登录显示</th>
                            <th>编辑</th>
                        </tr>
                    </thead>
                    <tbody id="nav-group">
                        <?php $glv=1;$lv=0;?>
                        <?php foreach ($nav_conf as $key=>$item):?>
                        <?php foreach ($item as $k=>$vo):?>
                        <tr>
                            <td>
                                <select name="group[]" class="form-control">
                                    <option value="1" <?php if($key==1):?>selected<?php endif;?>>分组一</option>
                                    <option value="2" <?php if($key==2):?>selected<?php endif;?>>分组二</option>
                                    <option value="3" <?php if($key==3):?>selected<?php endif;?>>分组三</option>
                                    <option value="4" <?php if($key==4):?>selected<?php endif;?>>分组四</option>
                                    <option value="5" <?php if($key==5):?>selected<?php endif;?>>分组五</option>
                                </select>
                            </td>
                            <td>
                                <div class="nav-icon rewrte select-nav">
                                    <input type="hidden" name="icon[]" value="<?=$vo['icon']?>"/>
                                    <p data-toggle="modal" data-target="#myModal2" class="rewrte-icon icon-box <?=$vo['icon']?>"><?php if(empty($vo['icon'])):?>暂无<?php endif;?></p>
                                </div>
                            </td>
                            <td><input type="text" name="modelname[]" class="form-control" style="width: 100%;" value="<?=$vo['modelname']?>" /></td>
                            <td><input type="text" name="link[]" class="form-control" style="width: 100%;" value="<?=$vo['link']?>" /></td>
                            <td><div class="checkbox"><label><input type="checkbox" <?php if($vo['is_login']==1):?>checked<?php endif;?> name="is_login[<?=$lv?>]" value="1" /></label></div></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-nav"><i class="fa fa-remove"></i></button>
                                <?php if(count($nav_conf)==$glv && count($item)==$k+1):?>
                                    <button type="button" class="btn btn-primary btn-sm plus-nav"><i class="fa fa-plus"></i></button>
                                <?php endif;?>
                            </td>
                            <input type="hidden" name="listorder[]" value="<?=$vo['listorder']?>" />
                        </tr>
                        <?php $lv++;?>
                        <?php endforeach;?>
                        <?php $glv++;?>
                        <?php endforeach;?>
                        <?php if(empty($nav_conf)):?>
                        <tr>
                            <td>
                                <select name="group[]" class="form-control">
                                    <option value="1">分组一</option>
                                    <option value="2">分组二</option>
                                    <option value="3">分组三</option>
                                    <option value="4">分组四</option>
                                    <option value="5">分组五</option>
                                </select>
                            </td>
                            <td>
                                <div class="nav-icon rewrte select-nav">
                                    <input type="hidden" name="icon[]" />
                                    <p data-toggle="modal" data-target="#myModal2" class="rewrte-icon icon-box">暂无</p>
                                </div>
                            </td>
                            <td><input type="text" name="modelname[]" class="form-control" style="width: 100%;" /></td>
                            <td><input type="text" name="link[]" class="form-control" style="width: 100%;" /></td>
                            <td><div class="checkbox"><label><input type="checkbox" name="is_login[0]" value="1" /></label></div></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm plus-nav"><i class="fa fa-plus"></i></button>
                            </td>
                            <input type="hidden" name="listorder[]" value="0" />
                        </tr>
                        <?php endif;?>
                    </tbody>
                    </table>
                </div>
                <div class="box-footer" style="text-align: center;">
                    <button type="submit" class="btn btn-primary dosave"><i class="fa fa-save">保存</i></button>
<!--                    <button type="button" class="btn bg-green plus-nav"><i class="fa fa-plus">添加</i></button>-->
<!--                    <button type="button" class="btn bg-red remove-nav"><i class="fa fa-remove">删除</i></button>-->
                </div>
                <!-- /.box-footer -->
                <?php echo form_close() ?>
            </div>
        <!-- /.box -->
        </section>
        <!-- Horizontal Form -->
    </div>
</div>
<!-- Horizontal Form -->
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

<script src="<?php echo base_url(FD_PUBLIC) ?>/js/jquery.dragsort-0.5.2.min.js"></script>
<script type="text/javascript">
$(function () {
    var obj=null;
    $(document).on('click','.select-nav',function (e) {
        e.stopPropagation();
        obj = $(this);
    });

//    if($("#nav-group").length){
//        $("#nav-group").dragsort({
//            dragSelector: "tr",
//            dragBetween: false,
//            placeHolderTemplate: "<tr class='placeHolder'></tr>",
//            dragSelectorExclude:"a,input,select,div,p,button"});
//    }

    $(document).on('click','.select-icon',function (e) {
        e.stopPropagation();
        var icon = $(this).data('icon');
        obj.find('p.icon-box').attr('class','rewrte-icon icon-box ui_'+icon).html('');
        obj.find("input[type='hidden']").val('ui_'+icon);
        $('button.close').click();
    });

    $(document).on('click','button.plus-nav',function (e) {
        e.stopPropagation();
        var tr=$('<tr/>').appendTo('#nav-group');
        var td1=$('<td/>').appendTo(tr),td2=$('<td/>').appendTo(tr),td3=$('<td/>').appendTo(tr),td4=$('<td/>').appendTo(tr),td5=$('<td/>').appendTo(tr),td6=$('<td/>').appendTo(tr);
        var select1=$('<select/>',{name:'group[]',class:'form-control'}).appendTo(td1);
        $('<option/>',{value:1}).html('分组一').appendTo(select1);
        $('<option/>',{value:2}).html('分组二').appendTo(select1);
        $('<option/>',{value:3}).html('分组三').appendTo(select1);
        $('<option/>',{value:4}).html('分组四').appendTo(select1);
        $('<option/>',{value:5}).html('分组五').appendTo(select1);
        var div1=$('<div/>',{class:'nav-icon rewrte select-nav'}).appendTo(td2);
        $('<input/>',{type:'hidden',name:'icon[]'}).appendTo(div1);
        $('<p/>',{'data-toggle':'modal','data-target':'#myModal2',class:'rewrte-icon icon-box'}).html('暂无').appendTo(div1);
        $('<input/>',{type:'text',name:'modelname[]',class:'form-control',style:'width: 100%'}).appendTo(td3);
        $('<input/>',{type:'text',name:'link[]',class:'form-control',style:'width: 100%'}).appendTo(td4);
        var div2=$('<div/>',{class:'checkbox'}).appendTo(td5),label=$('<label/>').appendTo(div2);
        var listorder = $("#nav-group").find('tr').length;
        $('<input/>',{type:'checkbox',name:'is_login['+listorder-1+']'}).appendTo(label);
        $('<button/>',{type:'button',class:'btn btn-danger btn-sm remove-nav'}).html('<i class="fa fa-remove"></i>').appendTo(td6);
        td6.append('&nbsp;');
        $('<button/>',{type:'button',class:'btn btn-primary btn-sm plus-nav'}).html('<i class="fa fa-plus"></i>').appendTo(td6);
        var button = $('<button/>',{type:'button',class:'btn btn-danger btn-sm remove-nav'}).html('<i class="fa fa-remove"></i>');
        $(this).parent().html(button);
        $('<input/>',{type:'hidden',name:'listorder[]',value:listorder-1}).appendTo(tr);
    });

    $(document).on('click','button.remove-nav',function (e) {
        e.stopPropagation();
        var pretr = $(this).parent().parent().prev().find('td:last');
        var nexttr = $(this).parent().parent().next().find('td:last');
        if($("#nav-group").find('tr').length>1) {
            if(nexttr.length==0) {
                pretr.append('&nbsp;');
                $('<button/>',{type:'button',class:'btn btn-primary btn-sm plus-nav'}).html('<i class="fa fa-plus"></i>').appendTo(pretr);
            }
            $(this).parent().parent().remove();
        }
        $("#nav-group").find('tr').each(function (idex,item) {
            $(item).find("input[name='listorder[]']").val(idex);
        });
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
                            case 'modelname[]':
                                st = value;
                                if (!value) {
                                    _null = true;
                                    _msg = '请输入标签名';
                                }
                                break;
                            case 'link[]':
                                et = value;
                                if (!value) {
                                    _null = true;
                                    _msg = '请输入超链接';
                                }
                                break;
                        }
                        if (_null === true) break;
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
</body>
</html>
