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
	<!-- <div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div> -->
	<!-- /.box-header -->
    
    <div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li id="top_tabs_1" class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
            <?php if($model->m_get($pk)): ?>
            <li id="top_tabs_2"><a href="#tab2" data-toggle="tab"><i class="fa fa-image"></i> 已选商品 </a></li>
            <li id="top_tabs_3"><a href="#tab3" data-toggle="tab"><i class="fa fa-image"></i> 添加商品 </a></li>
            <!--
            <li id="top_tabs_3"><a href="#tab3" data-toggle="tab"><i class="fa fa-link"></i> 关联分类 </a></li>
            <li id="top_tabs_4"><a href="#tab4" data-toggle="tab"><i class="fa fa-link"></i> 关联皮肤 </a></li>
            -->
            <?php endif; ?>
        </ul>

<!-- form start -->
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
        	<?php 
        	echo form_open( Soma_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data'), array($pk=>$model->m_get($pk) ) ); ?>
        		<div class="box-body">
                    <?php foreach ($fields_config as $k=>$v): ?>
        				<?php 
                        if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
                        else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
                        if( $k == 'inter_id' ){
                          // echo $select;
                        } 
                        ?>
        			<?php endforeach; ?>
              <!-- <?php echo $product_select, $product_sort; ?> -->
        		</div>
		<!-- /.box-body -->
		<div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-4">
                <button type="reset" class="btn btn-default">清除</button>
                <button type="submit" class="btn btn-info pull-right">保存</button>
            </div>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
</div>
<!-- /.box -->

                    <div class="tab-pane" id="tab2">
                      <div class="box-body">
                      <?php echo form_open( Soma_const_url::inst()->get_url('*/*/edit_product'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array($pk=>$model->m_get($pk), 'inter_id' =>$model->m_get('inter_id') ) ); ?>
                        <!-- 购买清单 -->
                        <div class=" col-sm-12 " >
                          <table class="table table-striped table-hover">
                            <thead>
                              <tr role="row">
                                <?php foreach($grid_field as $v): ?>
                                <th role="row">
                                  <?php echo $v; ?>
                                </th>
                                <?php endforeach; ?>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                              <?php foreach($products_select_list as $k=>$v): ?>
                              <tr>
                                <?php foreach($grid_field as $sk=> $sv): ?>
                                  <td>
                                    <?php if( $sk != 'sort' ){
                                              if( $sk == 'face_img' ){
                                                echo '<img src="'.$v[$sk].'" height="80" width="80">';
                                              }else{

                                                echo (isset($v[$sk]))? $v[$sk]: '-';
                                              }
                                          }
                                          else{ echo '<input class="col-sm-3" type="number" class="form-control " name="sort['.$k.']" id="el_product_sort" placeholder="排序" value="'.$v[$sk].'">';}
                                    ?>
                                  </td>
                                <?php endforeach; ?>
                                  <!-- <td>
                                    <input type="number" class="form-control " name="product_sort" id="el_product_sort" placeholder="排序" value="">
                                  </td> -->
                              </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                        <div class="box-footer ">
                            <div class="col-sm-4 col-sm-offset-4">
                                <button type="reset" class="btn btn-default">清除</button>
                                <button type="submit" class="btn btn-info pull-right">保存</button>
                            </div>
                         </div>
                      <?php echo form_close() ?>
                    </div><!-- /.box-body -->
                  </div><!-- /#tab2-->

                    <div class="tab-pane" id="tab3">
                        <div class="box-body">
                    <?php echo form_open( Soma_const_url::inst()->get_url('*/*/add_product'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array($pk=>$model->m_get($pk), 'inter_id' =>$model->m_get('inter_id') ) ); ?>
                            <?php echo $product_select, $product_sort; ?>
                            <div class="box-footer ">
                              <div class="col-sm-4 col-sm-offset-4">
                                  <button type="reset" class="btn btn-default">清除</button>
                                  <button type="submit" class="btn btn-info pull-right">保存</button>
                              </div>
                           </div>
                    <?php echo form_close() ?>
                        </div>
                        <!-- /.box-body -->
                    </div><!-- /#tab3-->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

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
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
<!--
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
-->
</body>
</html>
