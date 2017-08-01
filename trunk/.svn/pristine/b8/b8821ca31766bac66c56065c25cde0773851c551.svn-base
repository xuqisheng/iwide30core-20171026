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

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModal2Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModal2Label">选择导航图片</h4>
            </div>
            <div class="modal-body" style="height: auto;">
                <?php foreach ($icon_conf as $key=>$vo):?>
                    <div class="nav-box nav-icon select-icon" style="height: 74px;" data-icon="<?=$key?>" <?php if(strlen($vo)>12):?>title="<?=$vo?>"<?php endif;?>>
                        <p class="icon-box lvl_<?=$key?>" style="height: 100%;"></p>
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
                    <h3 class="box-title">会员等级显示设置</h3>
                </div>
                <?php echo form_open(EA_const_url::inst()->get_url('*/*/save_lvlconf'), array('class'=>'form-inline')); ?>
                <div class="box-body">
                    <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                    <thead>
                        <tr>
                            <th width="12%">会员等级</th>
                            <th width="8%">显示图标</th>
                        </tr>
                    </thead>
                    <tbody id="nav-group">
                        <?php foreach ($member_lvl as $key=>$item):?>
                        <tr>
                            <td><?=!empty($item['lvl_name'])?$item['lvl_name']:''?></td>
                            <td>
                                <div class="nav-icon rewrte select-nav">
                                    <input type="hidden" name="icon[<?=!empty($item['member_lvl_id'])?$item['member_lvl_id']:''?>]" value="<?=!empty($item['lvl_icon'])?$item['lvl_icon']:''?>"/>
                                    <p data-toggle="modal" data-target="#myModal2" class="rewrte-icon icon-box lvl_<?=!empty($item['lvl_icon'])?$item['lvl_icon']:''?>"><?php if(empty($item['lvl_icon'])):?>暂无<?php endif;?></p>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                    </table>
                </div>
                <div class="box-footer" style="text-align: center;">
                    <button type="submit" class="btn btn-primary dosave"><i class="fa fa-save">保存</i></button>
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

    $(document).on('click','.select-icon',function (e) {
        e.stopPropagation();
        var icon = $(this).data('icon');
        obj.find('p.icon-box').attr('class','rewrte-icon icon-box lvl_'+icon).html('');
        obj.find("input[type='hidden']").val(icon);
        $('button.close').click();
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
