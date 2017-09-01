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
		<h3 class="box-title">基础信息编辑  <?php if(isset($public)) echo "http://".$public['domain']."/index.php/member/center?id=".$public['inter_id'];?></h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <?php foreach($basicinfo as $k=>$basic) {?>
		    <?php foreach($basic as $info) {?>
		    <div class="row">
		        <div class="col-xs-2">
			        <label>所属分组:</label>
			        <select class="form-control" name="group[]">
			            <?php for($i=1;$i<10;$i++) {?>
                        <option value="<?php echo $i;?>" <?php if($k==$i) echo "selected";?>><?php echo $i;?></option>
                        <?php } ?>
					</select>
			    </div>
			    <div class="col-xs-2">
			        <label>模块名称:</label>
			        <input type="text" class="form-control" name="name[]" value="<?php if(isset($info['name'])) echo $info['name'];?>" placeholder="请输入模块名称">
			    </div>
			    <div class="col-xs-2">
			        <label>所属模块:</label>
			        <select class="form-control" name="module[]">
			            <?php foreach($module as $code=>$name) {?>
                        <option value="<?php echo $code;?>" <?php if($info['module']==$code) echo "selected";?>><?php echo $name;?></option>
                        <?php } ?>
					</select>
			    </div>
			    <div class="col-xs-3">
			        <label>超级链接:</label>
			        <input type="text" class="form-control" name="link[]" value="<?php if(isset($info['link'])) echo $info['link'];?>" placeholder="">
			    </div>
			    <div class="col-xs-2">
			        <label>图标样式:</label>
			        <select class="form-control" name="icocss[]">
			            <?php foreach($icos as $code=>$name) {?>
                        <option value="<?php echo $code;?>" <?php if(isset($info['icocss']) && $info['icocss']==$code) echo "selected";?>><?php echo $name;?></option>
                        <?php } ?>
					</select>
			    </div>
		    </div>
		    <?php } ?>
		    <?php } ?>
		    <div class="row">
		        <div class="col-xs-2">
			        <label>所属分组:</label>
			        <select class="form-control" name="group[]">
			            <?php for($i=1;$i<10;$i++) {?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php } ?>
					</select>
			    </div>
			    <div class="col-xs-2">
			        <label>模块名称:</label>
			        <input type="text" class="form-control" name="name[]" value="" placeholder="请输入模块名称">
			    </div>
			    <div class="col-xs-2">
			        <label>所属模块:</label>
			        <select class="form-control" name="module[]">
			            <?php foreach($module as $code=>$name) {?>
                        <option value="<?php echo $code;?>"><?php echo $name;?></option>
                        <?php } ?>
					</select>
			    </div>
			    <div class="col-xs-3">
			        <label>超级链接:</label>
			        <input type="text" class="form-control" name="link[]" value="" placeholder="">
			    </div>
			    <div class="col-xs-2">
			        <label>图标样式:</label>
			        <select class="form-control" name="icocss[]">
			            <?php foreach($icos as $code=>$name) {?>
                        <option value="<?php echo $code;?>"><?php echo $name;?></option>
                        <?php } ?>
					</select>
			    </div>
		    </div>
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
	<?php echo form_close() ?>
</div>
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">会员模式</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_memodel_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <div class="row">
		        <div class="col-xs-2">
			        <label>会员模式:</label>
			        <select class="form-control" name="membermodel">
                        <option value="perfect" <?php if(isset($membermodel) && $membermodel=='perfect') echo "selected";?>>完善资料模式</option>
                        <option value="login" <?php if(isset($membermodel) && $membermodel=='login') echo "selected";?>>登录模式</option>
					</select>
			    </div>
		    </div>
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
	<?php echo form_close() ?>
</div>
<!-- /.box -->

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
