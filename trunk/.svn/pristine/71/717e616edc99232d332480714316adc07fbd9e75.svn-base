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
		<h3 class="box-title">积分获取规则</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <?php foreach($rules as $rule) {?>
		    <div class="col-xs-12">
		        <div class="form-group">
		            <label>模块:</label>
			        <select class="form-control" name="module[]">
					    <?php foreach($modules as $k=>$modulename) {?>
                        <option value="<?php echo $k;?>" <?php if($k==$rule['module']) {?>selected<?php }?>><?php echo $modulename;?></option>
                        <?php }?>
					</select>
				</div>
				<div class="form-group">
			        <label>选择行业:</label>
			        <select class="form-control" name="category[]">
					    <?php foreach($categories as $k=>$cat) {?>
                        <option value="<?php echo $k;?>" <?php if($rule['category']==$k) {?>selected<?php }?>><?php echo $cat;?></option>
                        <?php }?>
					</select>
			    </div>
			    <div class="form-group">
			        <select class="form-control" name="member[]">
                        <option value="-1" <?php if(isset($rule['member']) && $rule['member']=='-1') {?>selected<?php }?>>所有会员</option>
                        <?php foreach($members as $k=>$member) {?>
                            <option value="<?php echo $k;?>" <?php if(isset($rule['member']) && $rule['member']==$k) {?>selected<?php }?>><?php echo $member;?></option>
                        <?php }?>
                    </select>
		        </div>
			    <div class="form-group">
			        <select class="form-control" name="type[]">
                        <option value="BALANCE" <?php if(isset($rule["BALANCE"])) {?>selected<?php }?>>按金额</option>
                        <option value="ORDER" <?php if(isset($rule["ORDER"])) {?>selected<?php }?>>按订单</option>
                    </select>
			    </div>
			    <div class="form-group">
			        <input class="form-control" type="text" name="amount[]" value="<?php echo isset($rule["BALANCE"]) ? key($rule["BALANCE"]) : key($rule["ORDER"]); ?>"/>
			    </div>
			    <div class="form-group">
			             获得<input class="form-control" type="text" name="bonus[]" value="<?php echo isset($rule["BALANCE"]) ? $rule["BALANCE"][key($rule["BALANCE"])] : $rule["ORDER"][key($rule["ORDER"])]; ?>" />个积分
			    </div>
		    </div>
		    <?php }?>
		    <div class="col-xs-12">
		        <div class="form-group">
		            <label>模块:</label>
			        <select class="form-control" name="module[]">
					    <?php foreach($modules as $k=>$modulename) {?>
                        <option value="<?php echo $k;?>"><?php echo $modulename;?></option>
                        <?php }?>
					</select>
				</div>
				<div class="form-group">
			        <label>选择行业:</label>
			        <select class="form-control" name="category[]">
					    <?php foreach($categories as $k=>$cat) {?>
                        <option value="<?php echo $k;?>"><?php echo $cat;?></option>
                        <?php }?>
					</select>
			    </div>
			    <div class="form-group">
			        <select class="form-control" name="member[]">
                        <option value="-1">所有会员</option>
                        <?php foreach($members as $k=>$member) {?>
                            <option value="<?php echo $k;?>"><?php echo $member;?></option>
                        <?php }?>
                    </select>
		        </div>
			    <div class="form-group">
			        <select class="form-control" name="type[]">
                        <option value="BALANCE">按金额</option>
                        <option value="ORDER">按订单</option>
                    </select>
			    </div>
			    <div class="form-group">
			        <input class="form-control" type="text" name="amount[]" />
			    </div>
			    <div class="form-group">
			             获得<input class="form-control" type="text" name="bonus[]" />个积分
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
		<h3 class="box-title">积分兑换金额设置</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/exchange_edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <?php foreach($btom as $bm) {?>
		    <div class="col-xs-12">
		        <div class="form-group">
		            <label>模块:</label>
			        <select class="form-control" name="module[]">
					    <?php foreach($modules as $k=>$modulename) {?>
                        <option value="<?php echo $k;?>" <?php if($k==$bm['module']) {?>selected<?php }?>><?php echo $modulename;?></option>
                        <?php }?>
					</select>
				</div>
				<div class="form-group">
			        <label>选择行业:</label>
			        <select class="form-control" name="category[]">
					    <?php foreach($categories as $k=>$cat) {?>
                        <option value="<?php echo $k;?>" <?php if($bm['category']==$k) {?>selected<?php }?>><?php echo $cat;?></option>
                        <?php }?>
					</select>
			    </div>
			    <div class="form-group">
			        <select class="form-control" name="member[]">
                        <option value="-1" <?php if(isset($bm['member']) && $bm['member']=='-1') {?>selected<?php }?>>所有会员</option>
                        <?php foreach($members as $k=>$member) {?>
                            <option value="<?php echo $k;?>" <?php if(isset($bm['member']) && $bm['member']==$k) {?>selected<?php }?>><?php echo $member;?></option>
                        <?php }?>
                    </select>
		        </div>
			    <div class="form-group">
			        <input class="form-control" type="text" name="bonus[]" value="<?php echo key($bm['bonustomoney']);?>"/>
			    </div>
			    <div class="form-group">
			             积分兑换<input class="form-control" type="text" name="amount[]" value="<?php echo current($bm['bonustomoney']);?>"/>元
			    </div>
		    </div>
		    <?php }?>
		    <div class="col-xs-12">
		        <div class="form-group">
		            <label>模块:</label>
			        <select class="form-control" name="module[]">
					    <?php foreach($modules as $k=>$modulename) {?>
                        <option value="<?php echo $k;?>"><?php echo $modulename;?></option>
                        <?php }?>
					</select>
				</div>
				<div class="form-group">
			        <label>选择行业:</label>
			        <select class="form-control" name="category[]">
					    <?php foreach($categories as $k=>$cat) {?>
                        <option value="<?php echo $k;?>"><?php echo $cat;?></option>
                        <?php }?>
					</select>
			    </div>
			    <div class="form-group">
			        <select class="form-control" name="member[]">
                        <option value="-1">所有会员</option>
                        <?php foreach($members as $k=>$member) {?>
                            <option value="<?php echo $k;?>"><?php echo $member;?></option>
                        <?php }?>
                    </select>
		        </div>
			    <div class="form-group">
			        <input class="form-control" type="text" name="bonus[]" />
			    </div>
			    <div class="form-group">
			             积分兑换<input class="form-control" type="text" name="amount[]" />元
			    </div>
		    </div>
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
	<?php echo form_close() ?>
</div>

</section>
</div>
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
