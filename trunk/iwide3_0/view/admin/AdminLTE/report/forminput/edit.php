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
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
		<div class="box-body">
            <?php foreach ($fields_config as $k=>$v): ?>
				<?php 
                if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
                else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
                ?>
			<?php endforeach; ?>
			<div class="form-group ">
				<input type="hidden" name="cid" value="<?php echo $this->input->get('ibd');?>">
				<input type="hidden" name="ids" value="<?php echo $this->input->get('ids');?>">
				<label for="el_isshow" class="col-sm-2 control-label">输入类型</label>
				<div class="col-sm-8" id="spanoptions">
					<select class="form-control" name="itype" id="itype">
						<option value="">请选择类型</option>
						<option value="text">单行文本框</option>
						<option value="textarea">多行文本框</option>
						<option value="checkbox">多选选框</option>
						<option value="radio">单选按钮</option>
						<option value="select">下拉框</option>
						<option value="date">日期选择</option>
					 </select>
					 <span style="display:none"><br>
					 多个选项值，多个用“|”隔,请不要使用空格！<br>	
					 <textarea name="filedoption" id="filedoption" style="width:300px; height:70px" cols="60" rows="8"></textarea>
					 </span>
				</div>
			</div>
			<div class="form-group " id="inputlimit">
				<label for="el_isshow" class="col-sm-2 control-label">输入限制</label>
				<div class="col-sm-8">
					<select class="form-control" name="fieldmatch" id="fieldmatch">
					   <option value="">请选择验证方式</option>
					   <option value="^(.*)$">英文数字汉字</option>
					   <option value="^[A-Za-z]+$">英文大小写字符</option>
					   <option value="^[1-9]\d*|0$">0或正整数</option>
					   <option value="^[1-9]\d*$">正整数</option>
					   <option value="^[-\+]?\d+(\.\d+)?$">小数</option>
					   <option value="\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*">邮箱</option>
					   <option value="^1[0-9]{10}$">手机</option>
					 </select>	
					 设置用户输入的类型，注：只有使用文本框类型选择才有效。
				</div>
			</div>
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


$ids = $this->input->get('ids');
if( !empty($ids) ){
	$inputobj= $this->db->select("*")->get_where('custom_input', array('id'=>$ids))->result_array();
}
?>

<script type="text/javascript">
$('#itype').val('<?php echo $inputobj[0]['itype'];?>');
$('#filedoption').val('<?php echo $inputobj[0]['filedoption'];?>');
$('#fieldmatch').val('<?php echo $inputobj[0]['fieldmatch'];?>');

$('#itype').change(function(){itypeshow($('#itype').val());});
function itypeshow(i){
	if(i=='textarea' || i=='text'){
	    $('#inputlimit').show();
	}
	else {$('#inputlimit').hide();}
	if(i=='checkbox' || i=='radio' || i=='select'){
		$('#spanoptions span').show();
		$('#spanoptions').css({'padding-top':'5px','padding-bottom':'5px'});
	}
	else {
		$('#spanoptions span').hide();$('#spanoptions').css({'padding-top':'','padding-bottom':''});
	}
}

$('button[type=submit]').click(function(){
	if($('[name=iname]').val().length<1){
		alert('字段名称填写不正确！');return false;
	}
	if($('[name=itype]').val().length<1){
		alert('请选择输入类型！');return false;
	}
	if($('[name=itype]').val()=='text' || $('[name=itype]').val()=='textarea'){
		if($('[name=fieldmatch]').val().length<3){
			alert('请选择输入限制！');return false;
		}
	}
});
</script>
</body>
</html>
