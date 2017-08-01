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
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo (isset($id)&&!empty($id)) ? '编辑': '新增'; ?>卡劵种类</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal')); ?>
		<div class="box-body">
            <div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">名称:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="type_name" placeholder="名称" value="<?php if(isset($typemodel->type_name)) echo $typemodel->type_name;?>">
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">卡劵种类:</label>
				<div class="col-sm-8">
					<select class="form-control" name="card_type">
					    <option value="">请选择种类</option>
		                <?php foreach($cardtypes as $k=>$name) {?>
		                    <option value="<?php echo $k;?>" <?php if(isset($typemodel->card_type) && $typemodel->card_type==$k) echo "selected";?>><?php echo $name;?></option>
		                <?php }?>
					</select>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">是否储值卡:</label>
				<div class="col-sm-8">
					<select class="form-control" name="is_vcard">
					    <option value="0" <?php if(isset($typemodel->is_vcard) && $typemodel->is_vcard==0) echo "selected";?>>否</option>
	                    <option value="1" <?php if(isset($typemodel->is_vcard) && $typemodel->is_vcard==1) echo "selected";?>>是</option>
					</select>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">是否关联微信卡包:</label>
				<div class="col-sm-8">
					<select class="form-control" name="is_package">
					    <option value="0" <?php if(isset($typemodel->is_package) && $typemodel->is_package==0) echo "selected";?>>否</option>
                        <option value="1" <?php if(isset($typemodel->is_package) && $typemodel->is_package==1) echo "selected";?>>是</option>
					</select>
				</div>
			</div>
		</div>
		<?php if(isset($typemodel->ct_id)) {?>
            <input name="ct_id" type="hidden" value="<?php echo $typemodel->ct_id;?>" />
        <?php }?>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
	<?php echo form_close() ?>
</div>

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
</html>
