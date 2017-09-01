<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/colorpickersliders/bootstrap.colorpickersliders.min.css'>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/colorpickersliders/tinycolor.min.js'></script>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/colorpickersliders/bootstrap.colorpickersliders.min.js'></script>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/colorpickersliders/bootstrap.colorpickersliders.nocielch.min.js'></script>
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
	<!-- <div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div> -->
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data'), array($pk=>$model->m_get($pk) ) ); ?>
		<!-- <div class="box-body">
		    <div class="col-sm-9" id="add_color">
				<input type="hidden" name="theme_id" id="el_theme_id" value="<?php echo $theme_id; ?>">
                <?php foreach ($fields_config as $k=>$v): ?>
    				<?php 
                    if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
                    else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
                    ?>
    			<?php endforeach; ?>
			</div>
		    <div class="col-sm-3 ">
		        <p>皮肤示例图</p>
		        <p><img src="<?php echo $ThemeModel->m_get('thumbnail');?>" class="img-thumbnail pull-left" alt="<?php echo $ThemeModel->m_get('theme_name');?>" /></p>
		        <p><?php echo $ThemeModel->m_get('theme_name');?></p>
			</div>
		</div> -->

    <div class="tabbable "> <!-- Only required for left/right tabs -->
          <ul class="nav nav-tabs">
              <li <?php if(isset($is_hide_theme)&& $is_hide_theme)echo 'class="hide"';else echo 'class="active"';?>><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本设置 </a></li>
              <li <?php if(isset($is_hide_theme)&& $is_hide_theme)echo 'class="active"';else echo 'class="hide"';?>><a href="#tab2" data-toggle="tab"><i class="fa fa-list-alt"></i> 赠送主题设置  </a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane <?php if(isset($is_hide_theme)&& $is_hide_theme)echo 'hide';else echo 'active';?>" id="tab1">
              <div class="box-body">
                <div class="col-sm-9" id="add_color">
                  <input type="hidden" name="theme_id" id="el_theme_id" value="<?php echo $theme_id; ?>">
                          <?php foreach ($fields_config as $k=>$v): ?>
                      <?php 
                              if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
                              else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
                              ?>
                    <?php endforeach; ?>
                </div>
                  <div class="col-sm-3 ">
                      <p>皮肤示例图</p>
                      <p><img src="<?php echo $ThemeModel->m_get('thumbnail');?>" class="img-thumbnail pull-left" alt="<?php echo $ThemeModel->m_get('theme_name');?>" /></p>
                      <!-- <p><?php echo $ThemeModel->m_get('theme_name');?></p> -->
                </div>
              </div>
          		
            </div>
            <div class="tab-pane <?php if(isset($is_hide_theme)&& $is_hide_theme)echo 'active';else echo 'hide';?>" id="tab2">
              <div class="box-body">
                <div class='form-group '>
                    <!-- <div class="form-group  has-feedback">
                      <label for="el_grand_total" class="col-sm-2 control-label">ddd</label>
                      <div class="col-sm-8"><input type="text" class="form-control " name="grand_total" id="el_grand_total" placeholder="" value="" ></div>
                    </div> -->
                    <div class="form-group  has-feedback">
                      <label for="el_receive_bg" class="col-sm-2 control-label">效果图</label>
                      <div class="col-sm-8">
                        <input type="file" class="form-control " name="receive_bg" id="el_receive_bg" placeholder="首页背景图" value="<?php echo $model->m_get('receive_bg');?>">
                        <?php if(!$model->m_get('receive_bg')): ?>
                          <span class="input-group-addon">文件大小必须 &lt; <b>1MB</b> </span>
                        <?php else:?>
                          <span class="input-group-addon">图片效果预览（圆型）：
                            <span><img src="<?php echo $model->m_get('receive_bg');?>" class="img-circle" width="100" height="100"></span>&nbsp;&nbsp;&nbsp;（方形）：
                            <span><img src="<?php echo $model->m_get('receive_bg');?>" class="img-polaroid" width="100" height="100"></span>
                          </span>
                        <?php endif;?>
                      </div>
                    </div>
                    <div class="form-group  has-feedback">
                      <label for="el_receive_preview_bg" class="col-sm-2 control-label">预览图</label>
                      <div class="col-sm-8">
                        <input type="file" class="form-control " name="receive_preview_bg" id="el_receive_preview_bg" placeholder="首页背景图" value="<?php echo $model->m_get('receive_preview_bg');?>">
                        <?php if(!$model->m_get('receive_preview_bg')): ?>
                          <span class="input-group-addon">文件大小必须 &lt; <b>1MB</b> </span>
                        <?php else:?>
                          <span class="input-group-addon">图片效果预览（圆型）：
                            <span><img src="<?php echo $model->m_get('receive_preview_bg');?>" class="img-circle" width="100" height="100"></span>&nbsp;&nbsp;&nbsp;（方形）：
                            <span><img src="<?php echo $model->m_get('receive_preview_bg');?>" class="img-polaroid" width="100" height="100"></span>
                          </span>
                        <?php endif;?>
                      </div>
                    </div>
                    <!-- <div class="form-group  has-feedback">
                      <label for="el_receive_mail_btn" class="col-sm-2 control-label">邮寄到家</label>
                      <div class="col-sm-8">
                        <input type="file" class="form-control " name="receive_mail_btn" id="el_receive_mail_btn" placeholder="首页背景图" value="<?php echo $model->m_get('receive_mail_btn');?>">
                        <?php if(!$model->m_get('receive_mail_btn')): ?>
                          <span class="input-group-addon">文件大小必须 &lt; <b>1MB</b> </span>
                        <?php else:?>
                          <span class="input-group-addon">图片效果预览（圆型）：
                            <span><img src="<?php echo $model->m_get('receive_mail_btn');?>" class="img-circle" width="100" height="100"></span>&nbsp;&nbsp;&nbsp;（方形）：
                            <span><img src="<?php echo $model->m_get('receive_mail_btn');?>" class="img-polaroid" width="100" height="100"></span>
                          </span>
                        <?php endif;?>
                      </div>
                    </div>
                    <div class="form-group  has-feedback">
                      <label for="el_receive_to_friend_btn" class="col-sm-2 control-label">送给朋友</label>
                      <div class="col-sm-8">
                        <input type="file" class="form-control " name="receive_to_friend_btn" id="el_receive_to_friend_btn" placeholder="首页背景图" value="<?php echo $model->m_get('receive_to_friend_btn');?>">
                        <?php if(!$model->m_get('receive_to_friend_btn')): ?>
                          <span class="input-group-addon">文件大小必须 &lt; <b>1MB</b> </span>
                        <?php else:?>
                          <span class="input-group-addon">图片效果预览（圆型）：
                            <span><img src="<?php echo $model->m_get('receive_to_friend_btn');?>" class="img-circle" width="100" height="100"></span>&nbsp;&nbsp;&nbsp;（方形）：
                            <span><img src="<?php echo $model->m_get('receive_to_friend_btn');?>" class="img-polaroid" width="100" height="100"></span>
                          </span>
                        <?php endif;?>
                      </div>
                    </div>
                    <div class="form-group  has-feedback">
                      <label for="el_receive_usage_btn" class="col-sm-2 control-label">到店用券</label>
                      <div class="col-sm-8">
                        <input type="file" class="form-control " name="receive_usage_btn" id="el_receive_usage_btn" placeholder="首页背景图" value="<?php echo $model->m_get('receive_usage_btn');?>">
                        <?php if(!$model->m_get('receive_usage_btn')): ?>
                          <span class="input-group-addon">文件大小必须 &lt; <b>1MB</b> </span>
                        <?php else:?>
                          <span class="input-group-addon">图片效果预览（圆型）：
                            <span><img src="<?php echo $model->m_get('receive_usage_btn');?>" class="img-circle" width="100" height="100"></span>&nbsp;&nbsp;&nbsp;（方形）：
                            <span><img src="<?php echo $model->m_get('receive_usage_btn');?>" class="img-polaroid" width="100" height="100"></span>
                          </span>
                        <?php endif;?>
                      </div>
                    </div>
                    <div class="form-group  has-feedback">
                      <label for="el_receive_buy_btn" class="col-sm-2 control-label">我也要买</label>
                      <div class="col-sm-8">
                        <input type="file" class="form-control " name="receive_buy_btn" id="el_receive_buy_btn" placeholder="首页背景图" value="<?php echo $model->m_get('receive_buy_btn');?>">
                        <?php if(!$model->m_get('receive_buy_btn')): ?>
                          <span class="input-group-addon">文件大小必须 &lt; <b>1MB</b> </span>
                        <?php else:?>
                          <span class="input-group-addon">图片效果预览（圆型）：
                            <span><img src="<?php echo $model->m_get('receive_buy_btn');?>" class="img-circle" width="100" height="100"></span>&nbsp;&nbsp;&nbsp;（方形）：
                            <span><img src="<?php echo $model->m_get('receive_buy_btn');?>" class="img-polaroid" width="100" height="100"></span>
                          </span>
                        <?php endif;?>
                      </div>
                    </div> -->
                    <div class="form-group  has-feedback">
                      <label for="el_receive_main_color" class="col-sm-2 control-label">文字颜色</label>
                        <div class="col-sm-8">
                            <div class=" input-group color">
                                <input type="text" class="form-control" name="receive_main_color" id="el_receive_main_color" value="<?php echo $model->m_get('receive_main_color');?>">
                                <span class="input-group-addon"><i class="fa fa-dashboard"></i></span>
                            </div>
                        </div>
                        <script type="text/javascript">$("#el_receive_main_color").ColorPickerSliders({size: "sm", placement: "top", hsvpanel: true, previewformat:"hex"});</script>
                    </div>
                </div>
              </div>
            </div>

            <!-- /.box-body -->
              <div class="box-footer ">
                      <div class="col-sm-4 col-sm-offset-4">
                          <button type="reset" class="btn btn-default">清除</button>
                          <input type="hidden" name="is_hide_theme" value="<?php if( isset($is_hide_theme) ) echo $is_hide_theme;else echo ''; ?>" />
                          <button type="submit" class="btn btn-info pull-right">保存</button>
                      </div>
              </div>
              <!-- /.box-footer -->
        </div>
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
<script>
//$(".wysihtml5").wysihtml5();
</script>
</body>
</html>
