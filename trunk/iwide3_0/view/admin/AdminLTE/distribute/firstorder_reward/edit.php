<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>
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
		<!--<section class="content-header">
			<h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
				<small></small>
			</h1>
			<ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
		</section>-->
		<!-- Main content -->
		<section class="content">

			<?php echo $this->session->show_put_msg(); ?>
			<?php //$pk= $model->table_primary_key(); ?>
			<!-- Horizontal Form -->
				<?php if(isset($id)){?>
					<?php echo form_open('distribute/firstorder_reward/edit?ids='.$id,array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data'))?>
					<?php }else{?>
					<?php echo form_open('distribute/firstorder_reward/add',array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data'))?>
					<?php }?>

            <div class="whitetable">
                <div>
                    <span style="border-color:#3f51b5">首单奖励</span>
                </div>
                <div class="bd_left list_layout">
                    <div>
                        <div>规则类型</div>
                        <div class="input flexgrow">
                            <select name="type" <?php echo isset($id)?"disabled":''?>>
                                <?php if(!empty($type)){
                                    foreach($type as $hk=>$hv){
                                        ?>
                                        <option value="<?php echo $hk?>" <?php echo isset($posts['type'])&&$posts['type']==$hk?' selected ':''?>><?php echo $hv?></option>
                                    <?php }}else{?>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div>核算单位</div>
                        <div class="input flexgrow">
                            <select name="reward_type" id="reward_type" <?php echo isset($id)?"disabled='disabled' ":''?>>
                                <?php if(!empty($reward_type)){
                                    foreach($reward_type as $hk=>$hv){
                                        ?>
                                        <option value="<?php echo $hk?>" <?php echo isset($posts['reward_type'])&&$posts['reward_type']==$hk?' selected ':''?>><?php echo $hv?></option>
                                    <?php }}else{?>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div>核算值</div>
                        <div class="input flexgrow">
							<input type="text" name="reward" value="<?php echo isset($posts['reward'])?$posts['reward']:''?>"><span id="danwei"><?php echo isset($posts['reward_type']) && $posts['reward_type']==3?'%':'元'?></span>
						</div>
					</div>
                    <div>
                        <div>规则状态</div>
                        <div class="flexgrow">
                            <label class="check maright">
                            <input name="status" type="radio" value="1" <?php echo isset($posts['status'])&&$posts['status']==1?' checked ':''?>/><span class="diyradio"><tt></tt></span>有效</label>
                            <label class="check">
                            <input name="status" type="radio" value="0" <?php echo isset($posts['status'])&&$posts['status']==0?' checked ':''?>/><span class="diyradio"><tt></tt></span>无效</label>
                        </div>
					</div>
				</div>
			</div>
            <div class="bg_fff bd center pad10">
                <input type="hidden" name="submit" value="1"/>
                 <button type="reset" class="bg_main button maright">清空</button>
                 <button class="bg_main button " type="submit" id="set_btn_save">保存</button>
            </div>
        </section>
			<!-- /.box-footer -->
			<?php echo form_close() ?>
    </div><!-- /.content-wrapper -->
</div>
</div>
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
<script>
	$(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
	$(document).ready(function(){

		$("#reward_type").bind("change",function(){
            val = $(this).val();
            if(val == 3){
                $('#danwei').text('%');
            }else{
                $('#danwei').text('元');
            }
		});

	});

	function sub(){
		if($("input[name='reward']").val() == ''||$("input[name='reward']").val() < 0){
			alert('核算值数据有误');
			return false;
		}
		return true;
		$('#tosave').form.submit();
	}
</script>
