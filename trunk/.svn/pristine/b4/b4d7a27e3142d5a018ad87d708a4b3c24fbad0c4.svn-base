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
		<h3 class="box-title">储值卡设置</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <?php foreach($vcards as $vcard) {?>
		    <div class="row">
			    <div class="col-xs-1">
			        <select class="form-control" name="card[]">
			            <option value="-1">请选择</option>
					    <?php foreach($cards as $card) {?>
                        <option value="<?php echo $card->ci_id;?>" <?php if($vcard['card']==$card->ci_id) echo "selected";?>><?php echo $card->title;?></option>
                        <?php }?>
					</select>
			    </div>
			    <div class="col-xs-1">
			        <select class="form-control" name="mem_id[]">
			            <option value="-1" <?php if($vcard['mem_id']=='-1') echo "selected";?>>等级不变</option>
					    <?php foreach($members as $k=>$member) {?>
                        <option value="<?php echo $k;?>" <?php if($vcard['mem_id']==$k) echo "selected";?>><?php echo $member;?></option>
                        <?php }?>
					</select>
				</div>
				<div class="form-group">
				    <label>奖励:</label>
			        <select class="form-control" name="type[]">
                        <option value="bonus" <?php if($vcard['type']=="bonus") echo "selected";?>>积分</option>
                        <option value="balance" <?php if($vcard['type']=="balance") echo "selected";?>>金额</option>
					</select>
				</div>
				<div class="form-group">
			        <input class="form-control" type="text" name="value[]" value="<?php echo $vcard['value'];?>"/>
				</div>
				<div class="form-group">
				    <label>是否计算余额:</label>
			        <select class="form-control" name="is_balance[]">
                        <option value="0" <?php if($vcard['is_balance']==0) echo "selected";?>>否</option>
                        <option value="1" <?php if($vcard['is_balance']==1) echo "selected";?>>是</option>
					</select>
				</div>
				<div class="form-group">
				    <label>是否存进储值卡:</label>
			        <select class="form-control" name="is_balance_card[]">
                        <option value="0" <?php if($vcard['is_balance_card']==0) echo "selected";?>>否</option>
                        <option value="1" <?php if($vcard['is_balance_card']==1) echo "selected";?>>是</option>
					</select>
				</div>
				<div class="form-group">
				    <label>最大购买数量:</label>
			        <input class="form-control" type="text" name="maxnum[]" value="<?php echo $vcard['maxnum'];?>"/>
				</div>
		    </div>
		    <?php } ?>
		    <div class="row">
			    <div class="col-xs-1">
			        <select class="form-control" name="card[]">
			            <option value="-1">请选择</option>
					    <?php foreach($cards as $card) {?>
                        <option value="<?php echo $card->ci_id;?>"><?php echo $card->title;?></option>
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
				<div class="form-group">
				    <label>奖励:</label>
			        <select class="form-control" name="type[]">
                        <option value="bonus">积分</option>
                        <option value="balance">金额</option>
					</select>
				</div>
				<div class="form-group">
			        <input class="form-control" type="text" name="value[]" value=""/>
				</div>
				<div class="form-group">
				    <label>是否计算余额:</label>
			        <select class="form-control" name="is_balance[]">
                        <option value="0">否</option>
                        <option value="1">是</option>
					</select>
				</div>
				<div class="form-group">
				    <label>是否存进储值卡:</label>
			        <select class="form-control" name="is_balance_card[]">
                        <option value="0">否</option>
                        <option value="1">是</option>
					</select>
				</div>
				<div class="form-group">
				    <label>最大购买数量:</label>
			        <input class="form-control" type="text" name="maxnum[]" value="" placeholder="输入0为不限制" />
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
