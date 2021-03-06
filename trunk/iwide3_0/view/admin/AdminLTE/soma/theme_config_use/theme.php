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
<?php $pk= $model->table_primary_key(); ?>
<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<!-- <h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3> -->
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/*'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); 
	$theme_name = isset( $use_theme['theme_name'] ) ? $use_theme['theme_name'] : NULL;
	$theme_use_id = isset( $use_theme['theme_use_id'] ) ? $use_theme['theme_use_id'] : NULL;
	?>
		<div class="box-body">
			<?php foreach($themes as $k=>$v):  ?>
	        	<div class="form-group ">
	        		<?php foreach($v as $sk=>$sv): ?>
		            <div class="col-sm-3 text-center ">
		                <p><img src="<?php echo $sv['thumbnail'];?>" style="height: 300px;" class="img-thumbnail <?php if( !empty( $theme_name ) && $theme_name == $sv['theme_name'] ) echo 'bg-primary';?>" alt="<?php echo $sv['theme_name'];?>" /></p>
		                <p>
		                	<label onclick="">
			                	<input type="radio" <?php if( !empty( $theme_name ) && $theme_name == $sv['theme_name'] ){ echo 'checked'; } ?> name="theme_id" value="<?php echo $sv['theme_id']; ?>" />
			                	<abbr title=""><?php echo $sv['theme_name']; ?></abbr>
			                </label>
		                	<?php  if( !empty( $theme_name ) && $theme_name == $sv['theme_name'] ): ?>
								<!--<a href="<?php /*echo $url;*/?>">编辑</a>-->
		                	<?php endif;?>
							<a href="<?php echo Soma_const_url::inst()->get_url('*/*/edit',array('inter_id' => $inter_id, 'tid' => $sv['theme_id']));?>">编辑</a>
		                </p>
		            </div>
		        	<?php endforeach;?>
	        	</div>
        	<?php endforeach;?>
        	<div class="form-group">
	    		<label for="" class="col-sm-4 control-label">是否显示首页导航栏</label>
	    		<div class="col-sm-8">
					<label class="col-sm-3"><input type="radio" id="un_show_navigation" name="is_show_navigation" <?php if( $is_show_navigation==Soma_base::STATUS_FALSE )echo "checked";?> value="2" required=""><abbr title=""> 否 </abbr></label>
					<label class="col-sm-3"><input type="radio" id="show_navigation" <?php if( $is_show_navigation==Soma_base::STATUS_TRUE )echo "checked";?> name="is_show_navigation" value="1" required=""><abbr title=""> 是 </abbr></label>
	    		</div>
	    	</div>
	    	<div class="form-group">
	    		<label for="" class="col-sm-4 control-label">是否显示中英文切换按钮</label>
	    		<div class="col-sm-8">
					<label class="col-sm-3"><input type="radio" id="un_show_lang_btn" name="is_show_lang_btn" <?php if( $is_show_lang_btn==Soma_base::STATUS_FALSE )echo "checked";?> value="2" required=""><abbr title=""> 否 </abbr></label>
					<label class="col-sm-3"><input type="radio" id="show_lang_btn" <?php if( $is_show_lang_btn==Soma_base::STATUS_TRUE )echo "checked";?> name="is_show_lang_btn" value="1" required=""><abbr title=""> 是 </abbr></label>
	    		</div>
	    	</div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-4">
                <!-- <button type="reset" class="btn btn-default">清除</button> -->
                <input type="hidden" name="theme_use_id" value="<?php echo $theme_use_id; ?>" />
                <button type="submit" <?php if( $disabled ) echo 'disabled'; ?> class="btn btn-info pull-right">保存</button>
            </div>
            <?php if( $disabled ) : ?><span style="color: red;">超级管理员不能编辑</span><?php endif;?>
		</div>
		<!-- /.box-footer -->
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
<script>
	$("input[name='theme_id']").click(function(){
		var val = $(this).val();
		// $("input[name='is_show_navigation']").attr('checked',false);
		if( val == <?php echo $theme_id;?> ){
			<?php if( $is_show_navigation == Soma_base::STATUS_TRUE):?>
				$("#show_navigation").attr('checked','checked');
			<?php else:?>
				$("#un_show_navigation").attr('checked','checked');
			<?php endif;?>
		}else{
			$("#un_show_navigation").attr('checked','checked');
		}
	});
</script>
</body>
</html>
