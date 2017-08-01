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
<!-- /.box -->

<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">会员中心皮肤</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
            <table class="table table-bordered table-striped table-condensed dataTable no-footer">
            <thead>
            	<tr>
            		<th>皮肤</th>
            		<!-- <th>提示信息</th> -->
            	</tr>
                <tr>
                    <td>
                        <select name="theme" class="form-control">

                <?php foreach ($themes as $key => $value){?>
                            <option value="<?php echo  $key;?>" <?php if($key == $template){ ?> selected <?php } ?>  ><?php echo $value;?></option>
                <?php } ?>

                        </select>
                    </td>
            </thead>
            <tbody>
            </table>
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary dosave">保存</button>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
</div>
<!-- /.box -->
<!-- Horizontal Form -->

<!-- <div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">微信菜单配置</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
            
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
</div> -->
<!-- Horizontal Form -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

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
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/jquery.dragsort-0.5.2.min.js"></script>
<script>

    $(function () {

        Wind.use("ajaxForm","artDialog",function () {
            $(document).on('click', '.dosave', function (e) {
                e.preventDefault();
                var _this = this, ok_url = "<?php echo EA_const_url::inst()->get_url('*/*/');?>", btn = $(this);
                var form = $('.form-inline'), form_url = form.attr("action");

                form.ajaxSubmit({
                    url: form_url,
                    dataType: 'json',
                    beforeSubmit: function (arr, $form, options) {
                        /*验证提交数据*/
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
</html>
