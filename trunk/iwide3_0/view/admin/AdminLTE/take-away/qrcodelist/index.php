<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
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
            <h1>二维码列表
                <small></small>
            </h1>
            <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <?php echo $this->session->show_put_msg(); ?>
                        <!--
                          <div class="box-header">
                            <h3 class="box-title">Data Table With Full Features</h3>
                          </div><!-- /.box-header -->

                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php echo form_open('','class="form-inline"')?>

                                    <div class="btn-group">
                                        <a href="<?php echo site_url('take-away/qrcodelist/add')?>" class="btn btn-sm bg-green" id="grid-btn-search">新增</a>&nbsp;&nbsp;</div>
                                </div>
                                </form>
                            </div>
                        </div>


                        <table id="data-grid" class="table table-bordered table-striped table-condensed">
                            <thead>
                            <tr role="row">
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">id</th>
                             <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">所属区域</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">所属酒店</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">售卖方式</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">二维码</th>


<!--                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">操作</th>
-->                            </tr>
                            <tfoot></tfoot>
                            <th></th>
                            <tbody>
                           <?php if(!empty($res)){
                               foreach($res as $k=>$v){
                           ?>
                            <tr>
                                <td><input type="checkbox" name="check[]" value="<?php echo $v['id']?>"><?php echo $v['id']?></td>
                                <td><?php echo $v['qrcode_name']?></td>
                                <td><?php echo $v['name']?></td>
                                <td><?php echo isset($sale_type[$v['sale_type']])?$sale_type[$v['sale_type']]:'--'?></td>
                                <td><img width="80px" src="<?php echo $v['url']?>"></td>
<!--                                <td><a class="btn btn-default bg-green" href="<?php /*//echo site_url('take-away/shop/edit?ids='.$v['shop_id'])*/?>">编辑</a></td>
-->                            </tr>
                            <?php }}?>
                        </table>

                        <div class="row">

                            <div class="col-sm-5">
                               <input type="checkbox" name="all_select" id="all_select" > 全选
                                <a class="btn btn-default"   id="output" name="output">导出</a>
                                |
                                <a class="btn btn-default"   id="output_all" name="output_all">导出全部</a>
                                <div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条</div>
                            </div>

                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                                    <ul class="pagination"><?php echo $pagination?></ul>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="data-grid_info" role="status"
                                     aria-live="polite">
                                    <input type="file" name="file" id="file" value="批量导入" />(公众号参数须为正式的参数才可生成正式号的二维码)
                                    <p>
                                        <a href="javascript:$('#file').uploadify('upload','*')">确定</a>|
                                        <a href="javascript:$('#file').uploadify('cancel')">取消上传</a>
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script src="<?php echo base_url('public/uploadify/jquery.uploadify.min.js')?>"></script>

<script>

    var buttons = $('<div class="btn-group"></div>');

    var grid_sort= [[ , "" ]];

    <?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
    var url_extra= [
//'http://iwide.cn/',
    ];
    var baseStr = "";
    $(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
    <?php $timestamp=time()?>

    $(document).ready(function() {
        $('#all_select').click(function(){
            if ($(this).prop("checked")) {
                $("input[name='check[]']").each(function() {
                    $(this).prop("checked", true);
                });
            } else {
                $("input[name='check[]']").each(function() {
                    $(this).prop("checked", false);
                });
            }
        });
        $('#output').click(function(){
            var tmp = '';
            $("input[name='check[]']").each(function() {
                if($(this).prop('checked')){
                    tmp += $(this).val() +'|';
                }
            });
            if(tmp == ''){
                alert('请选择');
                return false;
            }
            tmp=tmp.substring(0,tmp.length-1);
            //url = "<?php echo site_url('take-away/qrcodelist/output_qrcode')?>"+"?s="+tmp;
            //alert(url);return false;
            location.href="<?php echo site_url('take-away/qrcodelist/output_qrcode')?>"+"?s="+tmp;
        });

        $('#output_all').click(function(){
            location.href="<?php echo site_url('take-away/qrcodelist/output_qrcode')?>"+"?s=&all=1";
        });

        $('#file').uploadify({
            'formData'     : {'timestamp' : '{$timestamp}}','token':'<?php echo md5('unique_salt'.$timestamp)?>','<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'},
            'button_width':50,
            'auto':false,
            'button_height':23,
            'fileTypeExts':'*.xls;*.xlsx',
            'multi':false,
            'buttonText':'批量导入',
            'swf'      : '<?php echo base_url('public/uploadify/uploadify.swf')?>',
            'uploader' : '<?php echo site_url('take-away/qrcodelist/batch_import')?>',
            'onUploadSuccess' : function(file, data, response) {
                if(data == 'success' || data == ''){
                    location.reload();
                }else if(data=='limited'){
                    alert('最多一次录入100条！');
                }else if(data=='file error'){
                    alert( '操作失败，请重试！');
                }else{
                    alert('第'+data+'行录入的信息有误！')
                }
            }
        });
    });
</script>
</body>
</html>
