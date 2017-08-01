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
		<h3 class="box-title">活动排名</h3>
	</div>
		<div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?php echo form_open(EA_const_url::inst()->get_url('*/*/rmranking'),array('method'=>'get','class'=>'form-inline'))?>
                    <div class="form-group">
                        <label>活动:</label>
                        <select name="act_id" class="form-control">
                            <?php foreach ($activited_list as $key => $vo):?>
                            <option value="<?php echo $vo['id'];?>" <?php if(isset($gets['id']) && $gets['id']==1){ ?> selected <?php } ?>  ><?php echo $vo['name'];?></option>
                            <?php endforeach;?>
                        </select>
<!--                        <label>结束时间:</label>-->
<!--                        <input class="form-control" type="text" name="name" placeholder="请输入推荐人名称" value="--><?php //echo empty($gets['name']) ? '' : $gets['name']; ?><!--">-->
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
                    <th width="60">头像</th>
                    <th>推荐人</th>
                    <th>推荐数量</th>
                    <th>排行</th>
                    <th>操作</th>
            	</tr>
            </thead>
            <tbody>
            <?php if(!empty($list)):?>
                <?php foreach ($list as $key=>$val){?>
                    <tr data-id="<?php echo $val['id'];?>">
                        <td><img width="50" height="50" src="<?php echo $val['headimgurl'];?>" alt="<?php echo $val['headimgurl'];?>"></td>
                        <td><?php echo $val['name'];?></td>
                        <td><?php echo $val['total_recom'].'人';?></td>
                        <td><?php echo '第'.$val['ranking'].'名';?></td>
                        <td>
                            <?php if(isset($val['issend']) && $val['issend']=='1'):?>
                            <a href="javascript:void(0);" data-actid="<?php echo $val['activited_id'];?>" data-memid="<?php echo $val['fromuser_id'];?>" data-openid="<?php echo $val['from_openid'];?>" data-value="<?php echo $val['reward_value'];?>" class="send-rw">发放奖励</a>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php }?>
            <?php endif;?>
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
        <?php if(isset($val['issend']) && $val['issend']=='1'):?>
        Wind.use("artDialog",function () {
            $(document).on('click', '.send-rw', function (e) {
                e.preventDefault();
                var obj=this,actid=$(this).data('actid'),memid=$(this).data("memid"),openid=$(this).data("openid"),value=$(this).data("value");
                $.getJSON('<?php echo EA_const_url::inst()->get_url('*/*/send_reward')?>', {
                    actid: actid,
                    memid: memid,
                    openid:openid,
                    value:value
                }, function (data) {
                    if (data.status == 1) {
                        $(obj).remove();
                        art.dialog({title:'提示',fixed:true,icon:'succeed',content:data.message,ok:true,cancel:false,time:5});
                    } else {
                        art.dialog({title:'提示',fixed:true,icon:'warning',content:data.message,ok:true,cancel:false,time:5});
                    }
                });
            });
        });
        <?php endif;?>
    });
</script>
</html>
