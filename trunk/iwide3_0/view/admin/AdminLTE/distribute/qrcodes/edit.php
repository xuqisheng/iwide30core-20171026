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
		<h3 class="box-title"><?php echo ( $this->input->get('ids') ) ? '编辑': '新增'; ?>信息</h3>
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array('id'=>$this->input->get('ids')) ); ?>
		<div class="box-body">
            <div class="form-group  has-feedback">
	<label for="el_name" class="col-sm-2 control-label">姓名</label>
	<div class="col-sm-8"><input type="text" class="form-control " name="name" id="el_name" placeholder="姓名" value="<?php echo empty($saler->name) ? '' : $saler->name?>" readonly=""></div>
</div>
<div class="form-group ">
	<label for="el_sex" class="col-sm-2 control-label">性别</label>
	<div class="col-sm-8 radio"><label><input type="radio" name="sex" value="1"<?php if($saler->sex == 1):echo ' checked';endif;?>>男</label>&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" name="sex" value="2"<?php if($saler->sex == 2):echo ' checked';endif;?>>女</label>
	</div>
</div>							<div class="form-group  has-feedback">
	<label for="el_birthday" class="col-sm-2 control-label">生日</label>
	<div class="col-sm-8"><input type="datebox" class="form-control " name="birthday" id="el_birthday" placeholder="生日" value="<?php echo empty($saler->birthday) ? '' : $saler->birthday?>"></div>
</div>							<div class="form-group  has-feedback">
	<label for="el_education" class="col-sm-2 control-label">学历</label>
	<div class="col-sm-8"><input type="text" class="form-control " name="education" id="el_education" placeholder="学历" value="<?php echo empty($saler->education) ? '' : $saler->education?>"></div>
</div>							<div class="form-group  has-feedback">
	<label for="el_graduation" class="col-sm-2 control-label">毕业院校</label>
	<div class="col-sm-8"><input type="text" class="form-control " name="graduation" id="el_graduation" placeholder="毕业院校" value="<?php echo empty($saler->graduation) ? '' : $saler->graduation?>"></div>
</div>							<div class="form-group  has-feedback">
	<label for="el_position" class="col-sm-2 control-label">职位/岗位</label>
	<div class="col-sm-8"><input type="text" class="form-control " name="position" id="el_position" placeholder="职位/岗位" value="<?php echo empty($saler->position) ? '' : $saler->position?>"></div>
</div>							<div class="form-group  has-feedback">
	<label for="el_business" class="col-sm-2 control-label">业务分工</label>
	<div class="col-sm-8"><input type="text" class="form-control " name="business" id="el_business" placeholder="业务分工" value="<?php echo empty($saler->business) ? '' : $saler->business?>"></div>
</div>							<div class="form-group  has-feedback">
	<label for="el_in_date" class="col-sm-2 control-label">入职日期</label>
	<div class="col-sm-8"><input type="datebox" class="form-control " name="in_date" id="el_in_date" placeholder="入职日期" value="<?php echo empty($saler->in_date) ? '' : $saler->in_date?>"></div>
</div>							<div class="form-group ">
	<label for="el_changes" class="col-sm-2 control-label">变动记录</label>
	<div class="col-sm-8">
		<textarea class="form-control" name="changes" id="el_changes" placeholder="变动记录"><?php echo empty($saler->changes) ? '' : $saler->changes?></textarea>
	</div>
</div>							<div class="form-group ">
	<label for="el_previous_job" class="col-sm-2 control-label">前份工作情况</label>
	<div class="col-sm-8">
		<textarea class="form-control" name="previous_job" id="el_previous_job" placeholder="前份工作情况"><?php echo empty($saler->previous_job) ? '' : $saler->previous_job?></textarea>
	</div>
</div>							<div class="form-group ">
	<label for="el_description" class="col-sm-2 control-label">备注信息</label>
	<div class="col-sm-8">
		<textarea class="form-control" name="description" id="el_description" placeholder="备注信息"><?php echo empty($saler->description) ? '' : $saler->description?></textarea>
	</div>
</div>							<div class="form-group ">
	<label for="el_hotel_id" class="col-sm-2 control-label">酒店</label>
	<div class="col-sm-8">
		<select class="form-control" name="hotel_id">
		  <?php foreach ($hotels as $key => $value): ?>
		  	<option value="<?php echo $key?>"<?php if($key == $saler->hotel_id):echo ' selected';endif;?>><?php echo $value?></option>
		  <?php endforeach ?>
		</select>
	</div>
</div>							<div class="form-group  has-feedback">
	<label for="el_master_dept" class="col-sm-2 control-label">部门</label>
	<div class="col-sm-8"><input type="text" class="form-control " name="master_dept" id="el_master_dept" placeholder="部门" value="<?php echo empty($saler->master_dept) ? '' : $saler->master_dept?>"></div>
</div>														<div class="form-group  has-feedback">
	<label for="el_employee_id" class="col-sm-2 control-label">人员编码</label>
	<div class="col-sm-8"><input type="text" class="form-control " name="employee_id" id="el_employee_id" placeholder="人员编码" value="<?php echo empty($saler->employee_id) ? '' : $saler->employee_id?>"></div>
</div>							<div class="form-group  has-feedback">
	<label for="el_in_group_date" class="col-sm-2 control-label">入集团日期</label>
	<div class="col-sm-8"><input type="datebox" class="form-control " name="in_group_date" id="el_in_group_date" placeholder="入集团日期" value="<?php echo empty($saler->in_group_date) ? '' : $saler->in_group_date?>"></div>
</div>							<div class="form-group  has-feedback">
	<label for="el_cellphone" class="col-sm-2 control-label">手机号码</label>
	<div class="col-sm-8"><input type="text" class="form-control " name="cellphone" id="el_cellphone" placeholder="手机号码" value="<?php echo empty($saler->cellphone) ? '' : $saler->cellphone?>"></div>
</div>
														<div class="form-group ">
	<label for="el_status" class="col-sm-2 control-label">状态</label>
	<div class="col-sm-8"><?php echo $status[$saler->status]?>
	</div>
</div>							<div class="form-group  has-feedback">
	<label for="el_qrcode_id" class="col-sm-2 control-label">分销号</label>
	<div class="col-sm-8"><input type="number" class="form-control " name="qrcode_id" id="el_qrcode_id" placeholder="分销号" value="<?php echo empty($saler->qrcode_id) ? '' : $saler->qrcode_id?>" readonly=""></div>
</div>						<div class="form-group  has-feedback">
	<label for="el_id_card" class="col-sm-2 control-label">id_card</label>
	<div class="col-sm-8"><input type="text" class="form-control " name="id_card" id="el_id_card" placeholder="id_card" value="<?php echo empty($saler->id_card) ? '' : $saler->id_card?>"></div>
</div>							
<div class="form-group ">
	<label for="el_is_distributed" class="col-sm-2 control-label">参与分销</label>
	<div class="col-sm-8">
		<div class="checkbox">
		  <label>
		    <input type="checkbox" value="1" name="is_distributed" disabled="disabled"<?php if($saler->is_distributed == 1): echo ' checked';endif;?>>
		    参与
		  </label>
		</div>
	</div>
</div>
<div class="form-group ">
    <label for="el_sex" class="col-sm-2 control-label">参与社群客</label>
    <div class="col-sm-8 radio"><label><input type="radio" name="is_club" value="1"<?php if($saler->is_club == 1):echo ' checked';endif;?>>参与</label>&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" name="is_club" value="0"<?php if($saler->is_club == 0):echo ' checked';endif;?>>不参与</label>
    </div>
</div>
<div class="form-group ">
    <label for="el_sex" class="col-sm-2 control-label">分销入口</label>
    <div class="col-sm-8 radio"><label><input type="radio" name="distribute_hidden" value="1"<?php if($saler->distribute_hidden == 1):echo ' checked';endif;?>>隐藏</label>&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" name="distribute_hidden" value="0"<?php if($saler->distribute_hidden == 0):echo ' checked';endif;?>>显示</label>
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
?>
</body>
</html>
