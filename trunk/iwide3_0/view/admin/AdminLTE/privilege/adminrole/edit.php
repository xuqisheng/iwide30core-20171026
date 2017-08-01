<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/iCheck/square/blue.css">
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
<?php $priv_type= @unserialize( $model->m_get('acl_desc') ); ?>
<!-- Horizontal Form -->
<div class="box box-info">
    <div class="box-header with-border">
		<?php if($model->m_get($pk)): ?>
			<h3 class="box-title">正在修改 <?php echo $model->m_get('role_label'); ?> “<?php echo $model->m_get('role_name'); ?>”权限</h3>
		<?php else: ?>
			<h3 class="box-title">新增权限角色</h3>
		<?php endif; ?>
    </div>
    <!-- /.box-header -->

    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
		<?php if($model->m_get($pk)): ?>
            <li><a href="#tab_base" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
            <li class="active"><a href="#tab_acl" data-toggle="tab"><i class="fa fa-legal"></i> 权限修改 </a></li>
		<?php else: ?>
            <li class="active"><a href="#tab_base" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
            <li><a href="#tab_acl" data-toggle="tab"><i class="fa fa-legal"></i> 权限修改 </a></li>
		<?php endif; ?>
        </ul>
<!-- form start -->
<?php 
echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','id'=>'form-edit-id'), array($pk=>$model->m_get($pk) ) ); ?>
        <div class="tab-content">
		<?php if($model->m_get($pk)): ?>
            <div class="tab-pane" id="tab_base">
		<?php else: ?>
            <div class="tab-pane active" id="tab_base">
		<?php endif; ?>
                    <div class="box-body">
                        <?php foreach ($fields_config as $k=>$v): ?>
                            <?php 
                            if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
                            else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
                            ?>
                        <?php endforeach; ?>
            <!-- 
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" /> 选项
                                    </label>
                                </div>
                            </div>
                        </div>
             -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer ">
                        <div class="col-sm-4 col-sm-offset-4">
                            <!--<button type="reset" class="btn btn-default">清除</button>-->
                            <button type="submit" class="btn btn-info pull-right">保存</button>
                        </div>
                    </div>
                    <!-- /.box-footer -->


<!-- form start -->
            </div><!-- /#tab1 -->
<?php 
$open_acl= config_item('acl_open_method'); 
$role_array= $model->get_role_tree_array();
$module_keys= array_keys($role_array);
?>
		<?php if($model->m_get($pk)): ?>
            <div class="tab-pane active" id="tab_acl">
		<?php else: ?>
            <div class="tab-pane" id="tab_acl">
		<?php endif; ?>
                <div class="box-body">
                    <div class="tabbable tabs-left">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#m_all" data-toggle="tab" > 权限方案 </a></li>
                            <?php foreach ($module_keys as $mv) { ?>
								<?php if($priv_type[ADMINHTML]==FULL_ACCESS): ?>
                                <li class="m_tabs hidden"><a href="#<?php echo 'm_'. $mv; ?>" data-toggle="tab"> <?php echo $model->get_role_lang($mv); ?></a></li>
                            	<?php else: ?>
                                <li class="m_tabs"><a href="#<?php echo 'm_'. $mv; ?>" data-toggle="tab"> <?php echo $model->get_role_lang($mv); ?></a></li>
                            	<?php endif; ?>
                            <?php }?>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="m_all">
                                <div class="box-body">
                                    <div class='form-group'>
                                        <label for='el_acl_type' class='col-sm-2 control-label'> 是否订制？</label>
                                        <div class='col-sm-8'>
                                            <select class='form-control' name='acl_type' id='el_acl_type' >
<?php if($priv_type[ADMINHTML]==FULL_ACCESS): ?>
                                                <option value="<?php echo $model::ROLE_TYPE_ALL ?>" selected> 全部 </option>
                                                <!--<option value="<?php echo $model::ROLE_TYPE_MODULE ?>"> 模块定制 </option> -->
                                                <option value="<?php echo $model::ROLE_TYPE_DEFINE ?>"> 操作定制 </option>
<?php else: ?>
                                                <option value="<?php echo $model::ROLE_TYPE_ALL ?>"> 全部 </option>
                                                <!--<option value="<?php echo $model::ROLE_TYPE_MODULE ?>"> 模块定制 </option> -->
                                                <option value="<?php echo $model::ROLE_TYPE_DEFINE ?>" selected> 操作定制 </option>
<?php endif; ?>
                                            </select>
                                        </div>
<!-- 模块、控制器定制 开始  -->
                                        <br/>
                                        <br/>
                                        <div class='col-sm-8'>
<?php
$ogg= 0;
foreach ($module_keys as $mv):
?>
    <?php foreach ($role_array as $k=>$v): ?>

<?php //echo $model->get_role_lang($mv); ?>
                                        
    <?php endforeach; ?>

<?php endforeach; ?>
                                        </div>
<!-- 模块、控制器定制 结束 -->
                                    </div>
                                </div>
                            </div>
<?php
$ogg= 0;
foreach ($role_array as $k=>$v):	//模块循环 
$ctl_keys= array_keys($v);
?>
                            <div class="tab-pane" id="<?php echo 'm_'. $k; ?>">
    <?php foreach ($v as $sk=>$sv): $has_check= FALSE; $ogg++; //控制器循环 ?>
								<div style="<?php echo ($ogg%2==0)? 'background:#efefef;': ''; ?>">
			<?php $action_hmtl= '';
			foreach ($sv as $ssv):
				if(is_array($open_acl) && in_array($ssv, $open_acl) ) {
					$checked= ' checked ';
					$has_check= TRUE; 
				} else if(isset($priv_type[ADMINHTML][$k][$sk]) && in_array($ssv, $priv_type[ADMINHTML][$k][$sk]) ) {
					$checked= ' checked ';
					$has_check= TRUE; 
				} else $checked= '';
				
				$action_hmtl.= "<div class='checkbox col-sm-3'><label><input type='checkbox' name='acl_detail[{$k}][{$sk}][{$ssv}]' class='c_". $sk. "_s'"
					. $checked. " />". $model->get_role_lang($ssv, 'a'). "</label></div>";
			?>			
			<?php endforeach; ?>
									<div class="box-header with-border ">
										<h5 class="box-title"><input type="checkbox" id="<?php echo 'c_'. $sk; ?>" name="<?php echo $k; ?>[<?php echo $sk; ?>]" <?php echo ($has_check==TRUE)? ' checked': '';?>/> <b><?php echo $model->get_role_lang($sk, 'c'); ?></b></h5>
									</div>
									<div class="box-body"><?php echo $action_hmtl; ?></div>
								</div>
    <?php endforeach; ?>
                            </div>
<?php endforeach; ?>
                        </div>
                    </div><!-- /.tabbable -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer ">
                    <div class="col-sm-4 col-sm-offset-4">
                        <button type="submit" class="btn btn-info pull-right">保存</button>
                    </div>
                </div>

            </div><!-- /#tab2-->
        </div><!-- /.tab-content -->
<?php echo form_close() ?>

    </div><!-- /.tabbable -->
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
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/iCheck/icheck.min.js"></script>
<script>
$('#el_acl_type').bind('change', function(v){ 
	if($('#el_acl_type').val()=='<?php echo $model::ROLE_TYPE_ALL ?>') {
		$.each($('#tab_acl .m_tabs'), function(i, li){
		  $(li).addClass('hidden');
		});
	} else {
		$.each($('#tab_acl .m_tabs'), function(i, li){
		  $(li).removeClass('hidden');
		});
	}
});
<?php 
foreach($role_array as $v){
	$ctl_keys= array_keys($v);
	foreach($ctl_keys as $sv){
		echo <<<EOF
$('#c_{$sv}').bind('change', function(v){ 
	if($('#c_{$sv}').prop('checked')==true) {
		$.each($('.c_{$sv}_s'), function(i, s){
		  $(s).prop('checked', true);
		});
	} else {
		$.each($('.c_{$sv}_s'), function(i, s){
		  $(s).prop('checked',false);
		});
	}
});
EOF
;
	}
}
?>
</script>
</body>
</html>
