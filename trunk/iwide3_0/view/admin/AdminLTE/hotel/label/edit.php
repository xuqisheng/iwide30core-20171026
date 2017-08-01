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
			<?php echo form_open( site_url('hotel/label/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ),array('type_id'=>$list['type_id'])); ?>
                <div class="box-body">
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">标签名称</label>
						<div class="col-sm-8">
							<input type="text" class="form-control " name="label_name" id="label_name" placeholder="标签名称" 
							value="<?php echo $list['label_name']; ?>">
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">标签类型</label>
						<div class="col-sm-8">
							<?php if (!empty($type_desc)&&isset($type_desc[$list['type']])){?>
							<select id='label_type' name='label_type'>
								<?php foreach ($type_desc as $type=>$des){?>
								<option value='<?php echo $type?>' <?php if ($type==$list['type'])echo 'selected';?>><?php echo $des;?></option>
								<?php }?>
							</select>
							<?php }else{?>
								<span><?php echo $list['type'];?></span>
							<?php }?>
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">排序(越大越前)</label>
						<div class="col-sm-8">
							<input type="text" class="form-control " name="sort" id="sort" placeholder="默认为0" 
							value="<?php echo $list['sort']; ?>">
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">状态</label>
						<div class="col-sm-8">
							<select name='status' id='status' >
							<option value="1" <?php if($list['status']==1) echo 'selected';?>>有效</option>
							<option value="2" <?php if($list['status']==2) echo 'selected';?>>无效</option>
							</select>
						</div>
					</div>
                    <div class="box-footer ">
                        <div class="col-sm-4 col-sm-offset-4">
                            <button type="submit" class="btn btn-info pull-right">保存</button>
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
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
$(function () {
});
</script>
</body>
</html>
