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
          <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">

<?php echo $this->session->show_put_msg(); ?>
<!-- Horizontal Form -->
<div class="box box-info"><!--

    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
        </ul>
<!-- form start -->

        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
			<?php echo form_open( site_url('hotel/orders/item_edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array('orderid'=>$list['orderid'],'item_id'=>$list['id']) ); ?>
                <div class="box-body">
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">单号</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['orderid']?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">房型名称</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['roomname']?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">价格代码</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['price_code_name']?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">总价</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['iprice']?></span>
							<input type="text" class="form-control" style='width:20%' name="new_price" id="new_price" placeholder="修改价格" value="" />
						</div>
					</div>
                    <div class="form-group  has-feedback">
                        <label class="col-sm-2 control-label">房号</label>
                        <div class="col-sm-8">
                            <span  class="form-control " style='border:0;'><?php echo $list['mt_room_id']?></span>
                            <input type="text" class="form-control" style='width:20%' name="mt_room_id" id="mt_room_id" placeholder="修改房号" value="" />
                        </div>
                    </div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">入住日期</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo date('Y-m-d',strtotime($list['startdate']));?></span>
							<?php if(!empty($begin_range)){?>
							<select name='startdate' id='startdate' >
							<?php foreach($begin_range as $dr){?>
							<option value='<?php echo $dr;?>' <?php if($dr==$list['startdate']){ echo 'selected';}?>><?php echo date('Y-m-d',strtotime($dr));?></option>
							<?php }?>
							</select>
							<?php } ?>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">离店日期</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo date('Y-m-d',strtotime($list['enddate']));?></span>
							<?php if(!empty($end_range)){?>
							<select name='enddate' id='enddate' >
							<?php foreach($end_range as $dr){?>
							<option value='<?php echo $dr;?>' <?php if($dr==$list['enddate']){ echo 'selected';}?>><?php echo date('Y-m-d',strtotime($dr));?></option>
							<?php }?>
							</select>
							<?php }?>
						</div>
					</div>
					 <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">状态</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['status_des']?></span>
						</div>
					</div>
					</div>
                    <div class="box-footer ">
                        <div class="col-sm-2 col-sm-offset-2">
						<?php if(!empty($can_edit)){?>
                            <button type="submit" class="btn btn-info pull-right">保存</button>
                           <?php }?>
                            <button type="button" class="btn btn-info " onclick="history.go(-1);">返回</button>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
	<?php echo form_close() ?>
                <!-- /.box-body -->

            </div><!-- /#tab1-->
            
        </div><!-- /.tab-content -->

        </section><!-- /.content -->
</div>
<!-- /.box -->

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
<!--
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
$(function () {
	//CKEDITOR.replace('el_gs_detail');
	$(".wysihtml5").wysihtml5();
});
</script>
</body>
</html>
