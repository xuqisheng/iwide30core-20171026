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
		<h3 class="box-title">会员等级编辑</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <?php foreach($members as $k=>$member) {?>
		    <div class="row">
		        <div class="col-xs-2">
			        <label>会员名称:</label>
			        <input type="text" class="form-control" name="name[<?php echo $k?>]" value="<?php echo $member?>" placeholder="请输入会员名称">
			    </div>
		    </div>
		    &nbsp;&nbsp;
		    <?php } ?>
		    <div class="row">
		        <div class="col-xs-2">
			        <label>会员名称:</label>
			        <input type="text" class="form-control" name="name[]" placeholder="请输入会员名称">
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
		<h3 class="box-title">会员积分升级</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/upgrade_bonus_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <div class="row">
		        <div class="col-xs-10">
		            <label>是否开启:</label>
			        <select class="form-control" name="upgrade_bonus">
                        <option value="0" <?php if(!$upgrade_bonus) echo "selected";?>>否</option>
                        <option value="1" <?php if($upgrade_bonus) echo "selected";?>>是</option>
					</select>
				</div>
		    </div>
		    <?php foreach($level_bonus as $level=>$bonus) {?>
		        <div class="row">
			        <div class="col-xs-1">
				        <select class="form-control" name="mem_id[]">
						    <?php foreach($members as $k=>$member) {?>
	                        <option value="<?php echo $k;?>" <?php if($level==$k) echo "selected";?>><?php echo $member;?></option>
	                        <?php }?>
						</select>
					</div>
				    <div class="col-xs-3">
				        <input type="text" class="form-control" name="bonus[]" value="<?php echo $bonus;?>" placeholder="请输入">
				    </div>
			    </div>
		    <?php } ?>
		    <div class="row">
		        <div class="col-xs-1">
			        <select class="form-control" name="mem_id[]">
					    <?php foreach($members as $k=>$member) {?>
                        <option value="<?php echo $k;?>"><?php echo $member;?></option>
                        <?php }?>
					</select>
				</div>
			    <div class="col-xs-3">
			        <input type="text" class="form-control" name="bonus[]" value="" placeholder="积分">
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
		<h3 class="box-title">会员充值升级</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/upgrade_charge_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <div class="row">
		        <div class="col-xs-10">
		            <label>是否开启:</label>
			        <select class="form-control" name="upgrade_balance">
                        <option value="0" <?php if(!$upgrade_balance) echo "selected";?>>否</option>
                        <option value="1" <?php if($upgrade_balance) echo "selected";?>>是</option>
					</select>
				</div>
		    </div>
		    <?php foreach($level_balance as $level=>$balane) {?>
		    <div class="row">
		        <div class="col-xs-1">
			        <select class="form-control" name="mem_id[]">
					    <?php foreach($members as $k=>$member) {?>
                        <option value="<?php echo $k;?>" <?php if($level==$k) echo "selected";?>><?php echo $member;?></option>
                        <?php }?>
					</select>
				</div>
			    <div class="col-xs-2">
			        <input type="text" class="form-control" name="balance[]" value="<?php echo $balane;?>" placeholder="充值金额">
			    </div>
		    </div>
		    <?php }?>
		    <div class="row">
		        <div class="col-xs-1">
			        <select class="form-control" name="mem_id[]">
					    <?php foreach($members as $k=>$member) {?>
                        <option value="<?php echo $k;?>"><?php echo $member;?></option>
                        <?php }?>
					</select>
				</div>
			    <div class="col-xs-2">
			        <input type="text" class="form-control" name="balance[]" value="" placeholder="充值金额">
			    </div>
		    </div>
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
	<?php echo form_close() ?>
</div>

<!--
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">会员购卡升级</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/upgrade_card_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <div class="row">
		        <div class="col-xs-10">
		            <label>是否开启:</label>
			        <select class="form-control" name="upgrade_card">
                        <option value="0" <?php if(!$upgrade_card) echo "selected";?>>否</option>
                        <option value="1" <?php if($upgrade_card) echo "selected";?>>是</option>
					</select>
				</div>
		    </div>
		    <?php foreach($level_card as $level=>$card_id) {?>
		    <div class="row">
		        <div class="col-xs-1">
			        <select class="form-control" name="mem_id[]">
					    <?php foreach($members as $k=>$member) {?>
                        <option value="<?php echo $k;?>" <?php if($level==$k) echo "selected";?>><?php echo $member;?></option>
                        <?php }?>
					</select>
				</div>
			    <div class="col-xs-3">
			        <select class="form-control" name="card[]">
			        <?php foreach($cards as $card) {?>
                    <option value="<?php echo $card->ci_id;?>" <?php if($card_id==$card->ci_id) echo "selected";?>><?php echo $card->title;?></option>
                    <?php }?>
                    </select>
			    </div>
		    </div>
		    <?php }?>
		    <div class="row">
		        <div class="col-xs-1">
			        <select class="form-control" name="mem_id[]">
					    <?php foreach($members as $k=>$member) {?>
                        <option value="<?php echo $k;?>"><?php echo $member;?></option>
                        <?php }?>
					</select>
				</div>
			    <div class="col-xs-3">
			        <select class="form-control" name="card[]">
			            <option value="-1">取消选择</option>
					    <?php foreach($cards as $card) {?>
                        <option value="<?php echo $card->ci_id;?>"><?php echo $card->title;?></option>
                        <?php }?>
					</select>
			    </div>
		    </div>
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
	<?php echo form_close() ?>
</div>
-->
	
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">会员特权编辑</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/privilege_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <?php foreach($privilege as $module=>$memarr) {?>
		        <?php foreach($memarr as $memlevel=>$catarr) {?>
		            <?php foreach($catarr as $cat=>$value) {?>
		            <?php foreach($value as $mode=>$val) {?>
			        <div class="row">
				        <div class="col-xs-1">
					        <select class="form-control" name="module[]">
							    <?php foreach($modules as $k=>$modulename) {?>
		                        <option value="<?php echo $k;?>" <?php if($k==$module) {?>selected<?php }?>><?php echo $modulename;?></option>
		                        <?php }?>
							</select>
						</div>
				        <div class="col-xs-1">
					        <select class="form-control" name="mem_id[]">
							    <?php foreach($members as $k=>$member) {?>
		                        <option value="<?php echo $k;?>" <?php if($k==$memlevel) {?>selected<?php }?>><?php echo $member;?></option>
		                        <?php }?>
							</select>
						</div>
						<div class="col-xs-2">
					        <label>享受特权:</label>
					        <select class="form-control" name="mem_cat[]">
							    <?php foreach($categories as $k=>$catname) {?>
		                        <option value="<?php echo $k;?>" <?php if($k==$cat) {?>selected<?php }?>><?php echo $catname;?></option>
		                        <?php }?>
							</select>
					    </div>
					    <div class="col-xs-2">
					        <select class="form-control" name="mode[]">
		                        <option value="discount" <?php if('discount'==$mode) {?>selected<?php }?>>打折</option>
		                        <option value="reduce" <?php if('reduce'==$mode) {?>selected<?php }?>>现金优惠</option>
							</select>
						</div>
					    <div class="col-xs-3">
					        <input type="text" class="form-control" name="val[]" value="<?php echo $val;?>" placeholder="请输入">
					    </div>
				    </div>
				    <?php } ?>
			        <?php } ?>
			    <?php } ?>
		    <?php } ?>

		    <div class="row">
		        <div class="col-xs-1">
			        <select class="form-control" name="module[]">
					    <?php foreach($modules as $k=>$module) {?>
                        <option value="<?php echo $k;?>"><?php echo $module;?></option>
                        <?php }?>
					</select>
				</div>
		        <div class="col-xs-1">
			        <select class="form-control" name="mem_id[]">
					    <?php foreach($members as $k=>$member) {?>
                        <option value="<?php echo $k;?>"><?php echo $member;?></option>
                        <?php }?>
					</select>
				</div>
				<div class="col-xs-2">
			        <label>享受特权:</label>
			        <select class="form-control" name="mem_cat[]">
					    <?php foreach($categories as $k=>$cat) {?>
                        <option value="<?php echo $k;?>"><?php echo $cat;?></option>
                        <?php }?>
					</select>
			    </div>
			    <div class="col-xs-2">
			        <select class="form-control" name="mode[]">
                        <option value="discount">打折</option>
                        <option value="reduce">现金优惠</option>
					</select>
				</div>
			    <div class="col-xs-3">
			        <input type="text" class="form-control" name="val[]" value="" placeholder="请输入">
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
