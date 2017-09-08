<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
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
            <h1><?php echo isset($breadcrumb_array['action']) ? $breadcrumb_array['action'] : ''; ?>
                <small></small>
            </h1>
            <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">

            <?php echo $this->session->show_put_msg(); ?>
            <?php $pk = $model->table_primary_key(); ?>
            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo ($this->input->post($pk)) ? '编辑' : '新增'; ?>信息</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?php
                echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class' => 'form-horizontal', 'id' => 'form-edit-id'), array($pk => $model->m_get($pk))); ?>
                <div class="box-body">
                    <?php
                    /** @var EA_block_admin $EA_block_admin */
                    $EA_block_admin = EA_block_admin::inst();
                    /**
                     * @var array $fields_config
                     * @var boolean $check_data
                     */
                    foreach ($fields_config as $k => $v) {

                        if($k == 'file_url'){
                            $value = set_value($k) ? set_value($k) : $model->m_get($k);
                            echo <<<HTML
<input type="hidden" class="form-control " name="{$k}" value="{$value}" readonly/>
HTML;
                            continue;
                        }
                        if ($k == 'file_name') {
                            $value = set_value($k) ? set_value($k) : $model->m_get($k);
                            $label = $v['label'];
                            echo <<<HTML
<div class='form-group'>
	<label for='el_{$k}' class='col-sm-2 control-label'>{$label}</label>
	<div class='col-sm-8'>
	    <input type="text" class="form-control " name="{$k}" value="{$value}" readonly/>
	    <br/>
        <input type="file" class="form-control " name="imgFile" id="el_{$k}"/>
        <br/>
        <span style="position: absolute;top: 54px;left: 120px;"><a class=" btn btn-info" href="javascript:$('input[name=file_url]').val('');$('input[name=file_name]').val('');$('#el_{$k}').uploadify('upload')">上传</a></span>
	</div>
</div>
HTML;
                            continue;

                        }

                        if ($check_data == FALSE) {
                            echo $EA_block_admin->render_from_element($k, $v, $model);
                        } else {
                            echo $EA_block_admin->render_from_element($k, $v, $model, FALSE);
                        }
                    }
                    ?>
                </div>
                <!-- /.box-body -->
                <div class="box-footer ">
                    <div class="col-sm-3 col-sm-offset-2">
                        <!-- Button trigger modal -->
                        <button id="preview" type="button" class="btn btn-info pull-right">
<!--                        <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#myModal">-->
                            预览
                        </button>
                    </div>
                    <div class="col-sm-1 col-sm-offset-1">
                        <button type="submit" class="btn btn-info pull-right">保存</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?php echo form_close() ?>
            </div>
            <!-- /.box -->

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->


    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel" style="text-align: center;">预览</h4>
                </div>
                <div id="model-body" class="modal-body">

                </div>
                <div class="modal-footer modal-file">
                    <a id="modal-file" data-url="<?php echo EA_const_url::inst()->get_url("*/*/download"); ?>" download="操作手册.pdf" class="btn btn-info pull-right" href="#">下载资料</a>
<!--                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>-->
                </div>
            </div>
        </div>
    </div>

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
<!--kindEditor-->
<link rel="stylesheet"
      href="<?php echo base_url(FD_PUBLIC) . '/' . $tpl ?>/plugins/kindeditor/plugins/code/prettify.css"/>
<script src="<?php echo base_url(FD_PUBLIC) . '/' . $tpl ?>/plugins/kindeditor/kindeditor.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) . '/' . $tpl ?>/plugins/kindeditor/plugins/code/prettify.js"></script>
<!--kindEditor-->
<!--uploadify start -->
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<!--uploadify end -->
<script>
    <?php
    $subpath = 'notice'; //基准路径定位在 /public/media/ 下
    $params = array(
        't' => 'images',
        'p' => $subpath,
        'token' => 'test' //再完善校验机制
    );
    $timestamp = time();
    ?>
    $(function () {

        // 预览
        $('#preview').on('click', function(){
            var $html_title = $('#el_title').val();
            var $html_content = $(document.getElementsByTagName('iframe')[0].contentWindow.document.body).html();
            var $html_file_name = $('input[name=file_name]').val();
            var $html_file_url = $('input[name=file_url]').val();
            $model_file_body = $('.modal-file');
            $model_file = $('#modal-file');
            $model_file.attr('href', '#');
            $model_file_body.addClass('hide');
            if($html_file_name != '' && $html_file_url != ''){
                $model_file_body.removeClass('hide');
                $model_file.attr('download', $html_file_name);
                $model_file.attr('href', $model_file.data('url') + '?name=' + $html_file_name + '&url=' + $html_file_url);
            }
            $('#myModalLabel').html($html_title)
            $('#model-body').html($html_content)
            $('#myModal').modal('toggle');
        });

        var commonItems = [
            'undo', 'redo', '|', 'cut', 'copy', 'paste',
            'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
            'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
            'superscript', 'clearhtml', 'quickformat', '|',
            'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
            'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '/', 'image', 'multiimage',
            'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
            'anchor', 'link', 'unlink'
        ];
        KindEditor.ready(function (K) {
            // 公告内容
            editor1 = K.create('#el_content', {
                cssPath: '<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
                uploadJson: '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
                fileManagerJson: '<?php echo base_url(FD_PUBLIC) . '/' . $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
                allowFileManager: true,
                resizeType: 1,
                items: commonItems,
                afterCreate: function () {
                    setTimeout(function () {
                        $('.ke-container').css('width', '100%');
                    }, 1)
                }
            });
            prettyPrint()
        });

        // 默认每次清空
        $('#el_file_name').uploadify({
            'formData': {
                '<?php echo $this->security->get_csrf_token_name();?>': '<?php echo $this->security->get_csrf_hash();?>',
                'timestamp': '<?php echo $timestamp;?>',
                'token': '<?php echo md5('unique_salt' . $timestamp);?>'
            },
            'swf': '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
            'uploader': '<?php echo site_url('basic/uploadftp/do_upload') . '?file_post_name=imgFile' ?>',
            'file_post_name': 'imgFile',
            'buttonText': '选择文件',
            'fileTypeExts': '*.pdf',
            'onSWFReady': function () {
                $('.uploadify-button-text').addClass('btn btn-info');
            },
            'fileSizeLimit': 5 * 1024,//允许上传的文件大小，单位KB
            'auto': false, // 是否开启自动上传
            'onUploadSuccess': function (file, data, response) {
                var res = $.parseJSON(data);
                var msgType = 'alert-success';
                var msg = '上传成功';
                if (res.error != '0') {
                    msgType = 'alert-danger';
                    msg = res.errormsg;
                }
                if (res.error == 0) {
                    // 赋值
                    $('input[name=file_url]').val(res.url);
                    $('input[name=file_name]').val(file.name);
                }

                $('.alert-danger').remove();
                $('.alert-success').remove();

                if ($('.content > div > .alert').length > 0) {
                    $('.content > div > .alert').remove();
                }

                var alertStr =
                    '<div class="alert ' + msgType + ' alert-dismissible">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                    '<h4><i class="icon fa fa-ban"></i> ' + msg + '</h4>' +
                    '</div>';

                $('.content').prepend(alertStr);
            }
        });


    })
</script>