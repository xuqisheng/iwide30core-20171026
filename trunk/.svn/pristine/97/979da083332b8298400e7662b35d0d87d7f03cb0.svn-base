<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/js/artDialog/css/ui-dialog.css">
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
        JS__ROOT:"<?php echo base_url(FD_PUBLIC) ?>/js/"
    };
</script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/artDialog/lib/sea.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/validate.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/wind.js"></script>
<style type="text/css">
    .isopenok{color:#18BF0E;}
    .isopenfail{color:red;}
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
		<h3 class="box-title">活动列表</h3>
	</div>
		<div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?php echo form_open(EA_const_url::inst()->get_url('*/*/actsetlist'),array('method'=>'get','class'=>'form-inline'))?>
                    <div class="form-group">
                        <label>开始时间:</label>
                        <input class="form_datetime form-control" type="text" name="begin_time" placeholder="<?php echo date('Y-m-d')?>" aria-controls="data-grid" value="<?php echo empty($posts['begin_time']) ? '' : $posts['begin_time']; ?>">
                        <label>结束时间:</label>
                        <input class="form_datetime form-control" type="text" name="end_time" placeholder="<?php echo date('Y-m-d')?>" aria-controls="data-grid" value="<?php echo empty($posts['end_time']) ? '' : $posts['end_time']; ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                        <button type="button" style="margin-left: 10px;" class="btn btn-sm search-reset"><i class="fa fa-eraser"></i>清空</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="box-header with-border"></div>
            <table class="table table-bordered table-striped table-condensed dataTable no-footer">
            <thead>
            	<tr>
            		<th>序号</th>
            		<th>活动名称</th>
                    <th>开始时间</th>
            		<th>结束时间</th>
                    <th width="10%">活动状态</th>
                    <th>操作</th>
            	</tr>
            </thead>
            <tbody>
            <?php foreach ($list as $key=>$val){?>
                <tr>
                    <td> <?php echo $val['id']?></td>
                    <td><?php echo $val['name']?></td>
                    <td> <?php echo date('Y-m-d',$val['start_time']);?></td>
                    <td><?php echo date('Y-m-d',$val['end_time']);?></td>
                    <td class="state-open"><?php echo $val['state'];?></td>
                    <td>
                        <a href="<?php echo EA_const_url::inst()->get_url('*/*/actset').'?id='.$val['id']; ?>">编辑</a> |
                        <a href="<?php echo EA_const_url::inst()->get_url('*/*/delact').'?id='.$val['id']; ?>" class="J_btn_del">刪除</a> |
                        <?php if($val['isopen']=='1'):?>
                            <a href="javascript:void(0);" class="edit-act isopenfail" data-id="<?php echo $val['id'];?>" data-open="<?php echo $val['isopen'];?>">停用</a> |
                        <?php else:?>
                            <a href="javascript:void(0);" class="edit-act isopenok" data-id="<?php echo $val['id'];?>" data-open="<?php echo $val['isopen'];?>">启用</a> |
                        <?php endif;?>
                        <a href="<?php echo EA_const_url::inst()->get_url('*/*/rmranklist').'?id='.$val['id'];?>">排行榜</a> |
                        <a href="<?php echo EA_const_url::inst()->get_url('*/*/statistics').'?id='.$val['id'];?>">活动统计</a>
                    </td>
                </tr>
                <?php }?>
            </tbody>
            </table>
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                        <ul class="pagination"><?php echo $pagination?></ul>
                    </div>
                </div>
            </div>
		</div>
		<!-- /.box-footer -->
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
<script type="text/javascript">
    $(function () {
        $(':input[name=begin_time]').datetimepicker({
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
        $(document).on('click','.search-reset',function () {
            $(':input[name=begin_time]').val('')
            $(':input[name=end_time]').val('');
        });

        Wind.use("artDialog",function () {
            $(document).on('click', '.edit-act', function (e) {
                e.preventDefault();
                art.dialog({title: '操作中...',lock:true,background:'#fff',opacity:0.1,padding:'5px 5px',time:10});
                var obj = this, id = $(this).data('id'), openstate = $(this).attr("data-open"), isopen = openstate == 1 ? 2 : 1;
                $.getJSON('<?php echo EA_const_url::inst()->get_url('*/*/edit_act')?>', {
                    id: id,
                    isopen: isopen
                }, function (data) {
                    var list = art.dialog.list;
                    for (var i in list) {
                        list[i].close();
                    };
                    if (data.status == 1) {
                        if (isopen == 1) {
                            $(obj).html('停用');
                            $(obj).removeClass('isopenok');
                            $(obj).addClass('isopenfail');
                            $(obj).parent().prev(".state-open").html(data.data.state);
                        } else {
                            $(obj).html('启用');
                            $(obj).removeClass('isopenfail');
                            $(obj).addClass('isopenok');
                            $(obj).parent().prev(".state-open").html(data.data.state);
                        }
                        $(obj).attr("data-open", isopen);
                    } else {
                        art.dialog({
                            title: '提示',
                            fixed: true,
                            icon: 'warning',
                            content: data.message,
                            ok: true,
                            cancel: false,
                            time:3
                        });
                    }
                });
            });

            $(document).on('click', '.J_btn_del', function (e){
                e.preventDefault();
                var url = this.href,obj=this;
                seajs.use([GV.JS__ROOT+'artDialog/src/dialog'], function (dialog){
                    dialog({
                        title:false,
                        content:'确定要删除吗?',
                        btnStyle:'ui-dialog-mini',
                        okValue: '确定',
                        cancel: function () {
                            this.close();
                            obj.focus(); //关闭时让触发弹窗的元素获取焦点
                            return true;
                        },
                        ok: function (){
                            art.dialog({title: '正在刪除...',lock:true,background:'#fff',opacity:0.1,padding:'5px 5px',time:10});
                            $.getJSON(url,function (data) {
                                var list = art.dialog.list;
                                for (var i in list) {
                                    list[i].close();
                                };
                                if (data.status == 1){
                                    $(obj).parent().parent().remove();return false;
                                }
                                art.dialog({
                                    title: '提示',
                                    fixed: true,
                                    icon: 'warning',
                                    content: data.message,
                                    ok: true,
                                    cancel: false,
                                    time:3
                                });
                            });
                        },
                        padding: 10,
                        quickClose: true,
                        cancelValue: '关闭'
                    }).show(obj);
                });
            });
        });
    });
</script>
</html>
