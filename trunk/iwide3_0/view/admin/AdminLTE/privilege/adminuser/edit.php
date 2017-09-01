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
    <!--
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div>-->
	<!-- /.box-header -->

    <div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
			<?php if($model->m_get($pk)): ?>
            <li><a href="#tab3" data-toggle="tab"><i class="fa fa-building"></i> 对应酒店 </a></li>
			<?php endif; ?>
            <li><a href="#tab2" data-toggle="tab"><i class="fa fa-lock"></i> 登陆密码 </a></li>
            <!--
			<li><a href="#tab4" data-toggle="tab"><i class="fa fa-wechat"></i> 扫码授权 </a></li>
			-->
        </ul>
<!-- form start -->
<?php 
echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data','id'=>'form-edit-id'), array($pk=>$model->m_get($pk) ) ); ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                    <div class="box-body">
                        <?php foreach ($fields_config as $k=>$v): ?>
                            <?php 
                            if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
                            else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
                            ?>
                        <?php endforeach; ?>
                        <?php if($model->m_get($pk)): ?>
                            <div class="form-group">
                                <label for="el_create_time" class="col-sm-2 control-label">创建时间</label>
                                <div class="col-sm-8">
                                    <input type="datebox" class="form-control" id="el_create_time" value="<?php echo $model->m_get('create_time'); ?>" disabled="">
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="el_update_time" class="col-sm-2 control-label">最后登录</label>
                                <div class="col-sm-8">
                                    <input type="datebox" class="form-control" id="el_update_time" value="<?php echo $model->m_get('update_time'); ?>" disabled="">
                                    
                                </div>
                            </div>
                        <?php endif; ?>
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

            <div class="tab-pane" id="tab2">

                <div class="box-body">
                    <div class="form-group has-feedback">
                        <label for="el_nickname" class="col-sm-2 control-label">登陆密码</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="password" id="el_password" placeholder="登陆密码" disabled value="******" >
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="el_nickname" class="col-sm-2 control-label">确认密码</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="password_cf" id="el_password_cf" placeholder="确认密码" disabled value="******">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label><input type="checkbox" id="checkbox_change_password" /> 修改密码？ </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer ">
                    <div class="col-sm-4 col-sm-offset-4">
                        <button type="submit" class="btn btn-info pull-right">保存</button>
                    </div>
                </div>

            </div><!-- /#tab2-->

            <div class="tab-pane" id="tab3">
                <div class="box-body" style="text-align:center;color:gray;" >
					<div class="alert alert-success" >请先选择公众号后保存，默认不选为公众号下的全部酒店</div>


<!--  普通列表形式选择酒店  -->
<?php 
$hotels= $fields_config['entity_id']['select'];
$checked= '';
$ids= explode(',', $model->m_get('entity_id') );
if(false): 
//if(count($hotels)>0): 
	foreach($hotels as $k=>$v):
		if( $ids && in_array($k, $ids)) $checked= 'checked="checked"';
		else $checked= '';
?>
<div class="col-sm-3">
	<div class="checkbox pull-left">
		<label><input type="checkbox" name="hotel_ids[]" value="<?php echo $k ?>" <?php echo $checked ?> /> <?php echo $v; ?> </label>
	</div>
</div>
<?php endforeach; else: ?><!--
	<abbr title="酒店列表跟'当前登陆的账号'查看权限有关，跟您'所编辑的账号'无关，请注意区分。">为何保存后依然看不到任何酒店？</abbr>-->
<?php endif; ?>
                </div>
                <!-- /.box-body -->



<!--  table+select形式选择酒店  -->

<link href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
				<div style="height:450px;">
					<div class="col-sm-6">
						<table id="data-grid" class="table table-bordered table-striped table-condensed">
						<thead><tr role="row"> <th>#ID</th><th>公众号名</th> </tr></thead>
						</table>
					</div>
					<div class="col-sm-6">
						<div id="ajax_hotel_select" style="line-height:400px;height:400px;color:gray;">
							请先选择公众号
						</div>
						<p style="color:gray;line-height:35px;height:35px;">多选请按住键盘 “ctrl 或 shift键 + 鼠标左键” 进行</p>
					</div>
					
				</div>



                <div class="box-footer ">
                    <div class="col-sm-4 col-sm-offset-4">
                        <button type="submit" class="btn btn-info pull-right">保存</button>
                    </div>
                </div>
            </div><!-- /#tab2-->

            <div class="tab-pane" id="tab4">
<?php 
/* 扫码跟公众号inter_id有关，此菜单可更改，不利于信息的统一性 */
//require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'adminuser'. DS. 'openid.php';
?>
            </div><!-- /#tab4-->

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
<script>
function change_password(){
	var chk= $('#checkbox_change_password');
	if(chk.prop('checked')==true){
		$('#el_password').prop('disabled', '');
		$('#el_password').val('');
		$('#el_password_cf').prop('disabled', '');
		$('#el_password_cf').val('');
	}else {
		$('#el_password').prop('disabled', 'disabled');
		$('#el_password').val('******');
		$('#el_password_cf').prop('disabled', 'disabled');
		$('#el_password_cf').val('******');
	}
}
$('#checkbox_change_password').bind('click', change_password);
<?php 
if(!$model->m_get($pk)):
	echo '$("#checkbox_change_password").prop("checked","checked").parent().hide();change_password();'; 

else: 
?>
	<?php if($t= $this->input->get('tab')) echo "$('". '#top_tabs a[href="#tab'. $t. '"]'. "').tab('show');"; ?>

	/** gridjs start **/
	<?php 
	$loading= base_url(FD_PUBLIC). '/'. $tpl. '/dist/img/loading.gif';
	$grid_single= TRUE;  //令表格selected值保持单个，去掉即为数组
	$ajax_url= EA_const_url::inst()->get_url('*/*/ajax_admin_hotels');
	$token_name= config_item('csrf_token_name');
	$token_val= $this->security->get_csrf_hash();
	$admin_id= $model->m_get('admin_id');
	$inter_id= $model->m_get('inter_id');
	$click_event_after= <<<EOF
	$('#ajax_hotel_select').html('<div style="text-align:center;width:100%;padding-top:80px;"><img src="$loading" width="50"/></div>');
	$.post("{$ajax_url}", { $token_name: "$token_val",id:selected[0],admin_id:$admin_id }, function(obj){
		if(obj.status==1){
			$('#ajax_hotel_select').html(obj.html);
		} else {
			alert(obj.message);
			$('#ajax_hotel_select').html(obj.message);
		}
	},'json');
EOF
; //EOF一定要顶格否则程序会报错
	$grid_data= array();
	foreach($fields_config['inter_id']['select'] as $k=>$v){
		if($k==FULL_ACCESS) continue;
		$grid_data[]= array($k, $v, 'DT_RowId'=>$k);
	}
	?>
	var dataSet=<?php echo json_encode($grid_data); ?>;
	var grid_sort= [[ 1, "asc" ]];
	$(document).ready(function() {
		<?php require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_lite.php'; ?>
	});
	/** gridjs end **/

	/** tab打开时请求当前选中酒店 **/
	<?php echo <<<EOF
	$('#top_tabs a[href="#tab3"]').on('shown.bs.tab', function (e) {
		$.post("{$ajax_url}", { $token_name: "$token_val",id:"$inter_id",admin_id:$admin_id }, function(obj){
			if(obj.status==1){
				$('#ajax_hotel_select').html(obj.html);
			} else {
				alert(obj.message);
			}
		},'json');
	})
EOF
; //EOF一定要顶格否则程序会报错
endif;
?>

</script>
</body>
</html>
