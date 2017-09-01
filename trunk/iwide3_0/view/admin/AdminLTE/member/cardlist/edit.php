<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/datepicker3.css'>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/bootstrap-datepicker.js'></script>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js'></script>
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
		<h3 class="box-title"><?php echo (isset($id)&&!empty($id)) ? '编辑': '新增'; ?>卡劵</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
		<div class="box-body">
            <div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">卡劵种类:</label>
				<div class="col-sm-8">
					<select class="form-control" name="ct_id">
					    <option value="">请选择种类</option>
		                <?php foreach($cardtypes as $type) {?>
		                    <option value="<?php echo $type->ct_id;?>" <?php if(isset($card->ct_id) && $card->ct_id==$type->ct_id) echo "selected";?>><?php echo $type->type_name;?></option>
		                <?php }?>
					</select>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">Code展示类:</label>
				<div class="col-sm-8">
					<select class="form-control" name="code_type">
					    <option value="">请选择种类</option>
					    <?php foreach($codetypes as $key=>$val) {?>
	                    <option value="<?php echo $key;?>" <?php if(isset($card->code_type) && $card->code_type==$key) echo "selected";?>><?php echo $val;?></option>
	                    <?php }?>
					</select>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">商户名字:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="brand_name" placeholder="商户名称" value="<?php if(isset($card->brand_name)) echo $card->brand_name;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">卡劵名字:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="title" placeholder="商户名称" value="<?php if(isset($card->title)) echo $card->title;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">劵名:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="sub_title" placeholder="商户名称" value="<?php if(isset($card->sub_title)) echo $card->sub_title;?>">
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">是否可以转赠:</label>
				<div class="col-sm-8">
				    <select class="form-control" name="can_give_friend">
		                <option value="0" <?php if(isset($card->can_give_friend) && $card->can_give_friend==0) echo "selected";?>>否</option>
		                <option value="1" <?php if(isset($card->can_give_friend) && $card->can_give_friend==1) echo "selected";?>>是</option>
                    </select>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">上传图片(LOGO)文件:</label>
				<div class="col-sm-8">
					<input type="file" name="logo" size="20" />
					<?php if(!empty($card->logo_url)) {?>
					<span class="input-group-addon">图片效果预览：
                        <span><img src="<?php echo $card->logo_url; ?>" class="img-circle" width="100" height="100"></span>
                    </span>
                    <?php } ?>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">卡劵页面是否可分享:</label>
				<div class="col-sm-8">
					<select class="form-control" name="can_share">
		                <option value="0" <?php if(isset($card->can_share) && $card->can_share==0) echo "selected";?>>否</option>
		                <option value="1" <?php if(isset($card->can_share) && $card->can_share==1) echo "selected";?>>是</option>
		            </select>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">颜色:</label>
				<div class="col-sm-8">
				    <select class="form-control" name="color">
		                <option value="">请选择</option>
		                <option value="Color010" <?php if(isset($card->color) && $card->color=="Color010") echo "selected";?>>Color010</option>
		                <option value="Color020" <?php if(isset($card->color) && $card->color=="Color020") echo "selected";?>>Color020</option>
		                <option value="Color030" <?php if(isset($card->color) && $card->color=="Color030") echo "selected";?>>Color030</option>
		                <option value="Color040" <?php if(isset($card->color) && $card->color=="Color040") echo "selected";?>>Color040</option>
		                <option value="Color050" <?php if(isset($card->color) && $card->color=="Color050") echo "selected";?>>Color050</option>
		                <option value="Color060" <?php if(isset($card->color) && $card->color=="Color060") echo "selected";?>>Color060</option>
		                <option value="Color070" <?php if(isset($card->color) && $card->color=="Color070") echo "selected";?>>Color070</option>
		                <option value="Color080" <?php if(isset($card->color) && $card->color=="Color080") echo "selected";?>>Color080</option>
		                <option value="Color081" <?php if(isset($card->color) && $card->color=="Color081") echo "selected";?>>Color081</option>
		                <option value="Color082" <?php if(isset($card->color) && $card->color=="Color082") echo "selected";?>>Color082</option>
		                <option value="Color090" <?php if(isset($card->color) && $card->color=="Color090") echo "selected";?>>Color090</option>
		                <option value="Color100" <?php if(isset($card->color) && $card->color=="Color100") echo "selected";?>>Color100</option>
		                <option value="Color101" <?php if(isset($card->color) && $card->color=="Color101") echo "selected";?>>Color101</option>
		                <option value="Color102" <?php if(isset($card->color) && $card->color=="Color102") echo "selected";?>>Color102</option>
		            </select>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">每人可领券的数量限制:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="get_limit" value="<?php if(isset($card->get_limit)) echo $card->get_limit;?>">
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">是否激活:</label>
				<div class="col-sm-8">
					<select class="form-control" name="is_active">
		                <option value="0" <?php if(isset($card->is_active) && $card->is_active==0) echo "selected";?>>否</option>
                        <option value="1"<?php if(isset($card->is_active) && $card->is_active==1) echo "selected";?>>是</option>
		            </select>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">时期类型:</label>
				<div class="col-sm-8">
					<select class="form-control" name="date_info_type">
	                    <option value="DATE_TYPE_FIX_TIME_RANGE">固定日期区间 </option>
	                </select>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">起用时间:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="date_info_begin_timestamp" value="<?php if(!empty($card->date_info_begin_timestamp)) echo date('Y-m-d',$card->date_info_begin_timestamp);?>" />
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">结束时间:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="date_info_end_timestamp" value="<?php if(!empty($card->date_info_end_timestamp)) echo date('Y-m-d',$card->date_info_end_timestamp);?>" />
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">卡劵使用提醒:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="notice" value="<?php if(isset($card->notice)) echo $card->notice;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">卡劵使用说明:</label>
				<div class="col-sm-8">
				    <textarea class="form-control" name="description"><?php if(isset($card->description)) echo $card->description;?></textarea>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">卡券数量:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="sku_total_quantity" value="<?php if(isset($card->sku_total_quantity)) echo $card->sku_total_quantity;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">当前库存:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="sku_quantity" value="<?php if(isset($card->sku_quantity)) echo $card->sku_quantity;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">客服电话:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="service_phone" value="<?php if(isset($card->service_phone)) echo $card->service_phone;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">商户自定义入口名称:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="custom_url_name" value="<?php if(isset($card->custom_url_name)) echo $card->custom_url_name;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">跳转页面地址:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="custom_url" value="<?php if(isset($card->custom_url)) echo $card->custom_url;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">入口右侧的tips:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="custom_url_sub_title" value="<?php if(isset($card->custom_url_sub_title)) echo $card->custom_url_sub_title;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">营销场景的自定义入口:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="promotion_url_name" value="<?php if(isset($card->promotion_url_name)) echo $card->promotion_url_name;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">跳转页面地址:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="promotion_url" value="<?php if(isset($card->promotion_url)) echo $card->promotion_url;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">营销入口右侧的提示语:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="promotion_url_sub_title" value="<?php if(isset($card->promotion_url_sub_title)) echo $card->promotion_url_sub_title;?>">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">团购卷详情:</label>
				<div class="col-sm-8">
				    <textarea class="form-control" name="deal_detail" placeholder="团购券专用"><?php if(isset($card->deal_detail)) echo $card->deal_detail;?></textarea>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">起用金额:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="least_cost" value="<?php if(isset($card->least_cost)) echo $card->least_cost;?>" placeholder="代金券专用">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">减免金额:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="reduce_cost" value="<?php if(isset($card->reduce_cost)) echo $card->reduce_cost;?>" placeholder="代金券专用">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">打折额度:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="discount" value="<?php if(isset($card->discount)) echo $card->discount;?>" placeholder="折扣券专用">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">礼品名字:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="gift" value="<?php if(isset($card->gift)) echo $card->gift;?>" placeholder="礼品券专用"><span>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">优惠详情:</label>
				<div class="col-sm-8">
				    <input type="text" class="form-control" name="default_detail" value="<?php if(isset($card->default_detail)) echo $card->default_detail;?>" placeholder="优惠券专用">
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">备注:</label>
				<div class="col-sm-8">
				    <textarea class="form-control" name="note"><?php if(isset($card->note)) echo $card->note;?></textarea>
				</div>
			</div>
		</div>
		<?php if(isset($card->ct_id)) {?>
            <input name="ci_id" type="hidden" value="<?php echo $card->ci_id;?>" />
        <?php }?>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
	<?php echo form_close() ?>
</div>

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
<script type="text/javascript">
$(':input[name=date_info_begin_timestamp]').datepicker({format:"yyyy-mm-dd", language: "zh-CN",});
$(':input[name=date_info_end_timestamp]').datepicker({format:"yyyy-mm-dd", language: "zh-CN",});
</script>
</body>
</html>
