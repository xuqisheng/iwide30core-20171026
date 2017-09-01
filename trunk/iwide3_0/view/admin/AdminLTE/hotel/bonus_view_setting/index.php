<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
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
<style>

#upfiles-button{ text-align:center; margin-top:15px; background:#F90; color:#fff;}
.radio{display:inline-block; margin-right:50px;}
table.table-bordered tbody td{cursor:default; text-align:center; vertical-align:middle}
#menu .fa{font-size:30px}
td[bgimg]{width:60px; height:60px; background-size:80% 80%; background-repeat:no-repeat; background-position:center center;}
.always{ background-image:url("<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/img/always.png");}
.athour{background-image:url("<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/img/athour.png"); }
.collect{background-image:url("<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/img/collect.png");}
.order{background-image:url("<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/img/order.png");}
.ticket{background-image:url("<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/img/ticket.png");}
.eg_img{width:100%;height:100%;}
</style>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>积分显示配置
            <small></small>
          </h1>
          <ol class="breadcrumb"></ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body">
                <?php echo $this->session->show_put_msg(); ?>
                <?php if (!empty($disp_set)&&$disp_set['skin_name']!=$default_skin){?>
                <div>当前所用皮肤不支持配置首页</div>
                <?php }else{?>
                   <?php echo form_open('hotel/home_setting/edit_post',array('method'=>'post'));?> 
                   <input type="hidden" name="id" value="<?php if(isset($id)) echo $id;?>">
					<div class="form-group col-xs-8">
						<label>展示</label>
						<table class="table table-bordered"  >
                        	<tr><td data-toggle="popover" ><label><input  type="radio"  name="home_disp"  value="0"  <?php if(!isset($value) or $value==0) echo 'checked';?>> 简版</label></td>
                        	<td data-toggle="popover" ><label><input  c type="radio" name="home_disp"  value="1" <?php if(isset($value) and $value==1) echo 'checked';?>> 新版</label></td></tr>
                        </table>
					</div>

                    <div class="col-xs-12" style="margin-top:20px">
                        <button type="button" onclick='sub()' class="btn btn-primary " style='margin-left: 40%'>保存</button>
                        <label id='tips' style='color:red;'></label>
                    </div>
                  <?php echo form_close();?>
                <?php }?>
                </div><!-- /.box-body -->
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
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script>

function sub(){

    var ranges=$('.range_pick');
    var check=true;
    $('#tips').html('保存成功');
    $.post('<?php echo site_url('hotel/bonus_view_setting/edit_post')?>',
    {
       value:$('input[name="home_disp"]:checked').val(),
    <?php echo $csrf_token?>:'<?php echo $csrf_value?>'
    },function(data){

    },'json');

}

</script>
</body>
</html>
