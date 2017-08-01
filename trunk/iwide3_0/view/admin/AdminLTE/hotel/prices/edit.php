<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
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
<div class="box box-info"><!--

    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
        </ul>

<!-- form start -->

        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
			<?php echo form_open( site_url('hotel/prices/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array('hotel_id'=>$hotel_id,'room_id'=>$room_id,'price_code'=>$list['price_code']) ); ?>
			<?php if(isset($list['use_condition']['member_level'])){?>
				<input type='hidden' name='imember_level' id='imember_level' value='<?php echo $list['use_condition']['member_level'];?>'/>
				<?php }?>
                <div class="box-body">
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">房型名称</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['room_name']?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">价格代码</label>
						<div class="col-sm-8">
							<?php if(!empty($list['price_code'])){?>
							<span  class="form-control " style='border:0;'><?php echo $list['price_name']?></span>
							<?php }else{?>
							<select class="form-control " name="price_code_sele" id="price_code_sele">
								<?php if(!empty($price_codes)) foreach($price_codes as $pc){?>
								<option value='<?php echo $pc['price_code'];?>'><?php echo $pc['price_name'];?></option>
								<?php }?>
							</select>
							<?php }?>
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">默认房价</label>
						<div class="col-sm-8">
							<input type="text" class="form-control " name="price" id="price" placeholder="默认房价" value="<?php echo $list['sprice']; ?>">
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">独立库存</label>
						<div class="col-sm-8">
							<input style="width: 50%;" type="text" class="form-control " name="nums" id="nums" placeholder="价格代码房间数(可不填)" value="<?php echo $list['snums']; ?>" />
						</div>
					</div>
					 <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">状态</label>
						<div class="col-sm-8">
							<select name='status' id='status' >
							<option value="1" <?php if($list['set_status']==1) echo 'selected';?>>有效</option>
							<option value="2" <?php if($list['set_status']==2) echo 'selected';?>>隐藏</option>
							<option value="3" <?php if($list['set_status']==3) echo 'selected';?>>无效</option>
							</select>
						</div>
					</div>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label" style="color:#7B7B7B">价格配置</label>
					<hr />
					</div>
					 <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">价格代码关联</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php if(!empty($list['related_code'])){ 
								echo $list['related_name'];echo '('.$enum_des['HOTEL_PRICE_CODE_RELATED_CAL_WAY'][$list['related_cal_way']].$list['related_cal_value'].')';?>
							<?php }else echo '无';?></span>
						</div>
					</div>
					<?php if(!empty($list['related_code'])){?>
					 <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">价格计算方式(不设置则使用默认值)</label>
						<div class="col-sm-8">
							<select class="form-control " name="related_cal_way" id="related_cal_way">
								<option value=''>--不设置--</option>
								<?php if(!empty($enum_des['HOTEL_PRICE_CODE_RELATED_CAL_WAY'])) foreach($enum_des['HOTEL_PRICE_CODE_RELATED_CAL_WAY'] as $code=>$des){?>
								<option value='<?php echo $code;?>' <?php if($code==$list['srelated_cal_way']){?>selected<?php }?>><?php echo $des;?></option>
								<?php }?>
							</select>
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">计算值</label>
						<div class="col-sm-8">
							<input type="text" class="form-control " name="related_cal_value" id="related_cal_value" placeholder="计算值" value="<?php echo $list['srelated_cal_value']; ?>">
						</div>
					</div>
					<?php }?>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label" style="color:#7B7B7B">预定政策</label>
					<div class="col-sm-8">
							<input type='radio' name='condition_bp' value='0' onclick="if(!$(this).checked)$('#bookpolicy_condition').hide();" <?php if(empty($list['sbookpolicy_condition'])){?>checked='checked'<?php }?>/>使用价格代码默认设置
							<input type='radio' name='condition_bp' value='1' onclick="$('#bookpolicy_condition').show();"  <?php if(!empty($list['sbookpolicy_condition'])){?>checked='checked'<?php }?> />单独设置
						</div>
					</div>
					<div id='bookpolicy_condition' <?php if(empty($list['sbookpolicy_condition'])){ ?>style="display:none" <?php }?> >
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">微信支付立减</label>
						<div class="col-sm-8">
							<input type="text" name="wxpay_favour" id="wxpay_favour" 
							value="<?php if(isset($list['sbookpolicy_condition']['wxpay_favour']))echo $list['sbookpolicy_condition']['wxpay_favour']; ?>" />元
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">早餐</label>
						<div class="col-sm-8">
							<select name='breakfast_nums' id='bfnums' >
							<?php foreach ($bf_fields as $kbf => $bf_field) {?>
							<option value="<?php echo $kbf;?>" <?php if($list['sbookpolicy_condition']['breakfast_nums']==$kbf) echo 'selected';?>><?php echo $bf_field;?></option>
							<?php }?>
							</select>
						</div>
					</div>
						<div class="form-group  has-feedback">
							<label class="col-sm-2 control-label">房间保留时间</label>
							<div class="col-sm-8">
								<ul>
									<?php foreach($pay_ways as $pw){ ?>
										<li style="margin-bottom:4px;">
											<b><?php echo $pw->pay_name; ?></b> &nbsp;入住日期的 <input type="text" name="retain_time[<?php echo $pw->pay_type; ?>]" id="retain_time" placeholder="(可不填)" value="<?php echo isset($list['sbookpolicy_condition']['retain_time'][$pw->pay_type])?$list['sbookpolicy_condition']['retain_time'][$pw->pay_type]:'';?>" />
										</li>
									<?php } ?>
								</ul>
							</div>
						</div>
						<div class="form-group  has-feedback">
							<label class="col-sm-2 control-label">延迟退房时间</label>
							<div class="col-sm-8">
								<ul>
									<?php foreach($pay_ways as $pw){ ?>
										<li style="margin-bottom:4px;">
											<b><?php echo $pw->pay_name; ?></b> &nbsp;离店日期的 <input type="text" name="delay_time[<?php echo $pw->pay_type; ?>]" id="retain_time" placeholder="(可不填)" value="<?php echo isset($list['sbookpolicy_condition']['delay_time'][$pw->pay_type])?$list['sbookpolicy_condition']['delay_time'][$pw->pay_type]:'';?>" />
										</li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label" style="color:#7B7B7B">使用条件</label>
					<div class="col-sm-8">
							<input type='radio' name='condition_way' value='0' onclick="if(!$(this).checked)$('#use_condition').hide();" <?php if(empty($list['suse_condition'])){?>checked='checked'<?php }?>/>使用价格代码默认设置
							<input type='radio' name='condition_way' value='1' onclick="$('#use_condition').show();"  <?php if(!empty($list['suse_condition'])){?>checked='checked'<?php }?> />单独设置
						</div>
					</div>
					<div id='use_condition' <?php if(empty($list['suse_condition'])){ ?>style="display:none" <?php }?> >
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">预付标记</label>
					<div class="col-sm-8">
							<input type='radio' name='pre_pay' value='0' <?php if(empty($list['suse_condition']['pre_pay'])){?>checked='checked'<?php }?> />不显示
							<input type='radio' name='pre_pay' value='1' <?php if(!empty($list['suse_condition']['pre_pay'])){?>checked='checked'<?php }?> />显示
						</div>
					</div>
					<?php if (!empty($has_package_pay)){?>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">仅用于套票预订</label>
					<div class="col-sm-8">
							<input type='radio' name='package_only' value='0' <?php if(empty($list['suse_condition']['package_only'])){?>checked='checked'<?php }?> />否
							<input type='radio' name='package_only' value='1' <?php if(!empty($list['suse_condition']['package_only'])){?>checked='checked'<?php }?> />是
						</div>
					</div>
					<?php }?>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">不使用</label>
					<?php foreach($pay_ways as $pw){?>
					<input type='checkbox' name='no_pay_way[]' value='<?php echo $pw->pay_type;?>' 
					<?php if(!empty($list['suse_condition']['no_pay_way'])&&in_array($pw->pay_type, $list['suse_condition']['no_pay_way'])){?>checked='checked'<?php }?>
					 /><?php echo $pw->pay_name;?>
					<?php }?>
					</div>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">需提前预订天数</label>
						<input type="text" name="pre_day" id="pre_day" placeholder="提前预订天数" value="<?php if(isset($list['suse_condition']['pre_d']))echo $list['suse_condition']['pre_d']; ?>" /> (仅当天可预订请填0，不限制请留空)
					</div>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">入住日期大于等于</label>
						<input type="text" name="s_date_s" id="s_date_s" data-date-format="yyyymmdd" class=" datepicker" value="<?php if(isset($list['suse_condition']['s_date_s']))echo $list['suse_condition']['s_date_s']; ?>" />方可预订
					</div>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">入住日期小于等于</label>
						<input type="text" name="s_date_e" id="s_date_e" data-date-format="yyyymmdd" class=" datepicker" value="<?php if(isset($list['suse_condition']['s_date_e']))echo $list['suse_condition']['s_date_e']; ?>" />方可预订
					</div>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">离店日期大于等于</label>
						<input type="text" name="e_date_s" id="e_date_s" data-date-format="yyyymmdd" class=" datepicker" value="<?php if(isset($list['suse_condition']['e_date_s']))echo $list['suse_condition']['e_date_s']; ?>" />方可预订
					</div>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">离店日期小于等于</label>
						<input type="text" name="e_date_e" id="e_date_e" data-date-format="yyyymmdd" class=" datepicker" value="<?php if(isset($list['suse_condition']['e_date_e']))echo $list['suse_condition']['e_date_e']; ?>" />方可预订
					</div>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">单次最大预订间数</label>
						<input type="text" name="max_num" id="max_num" value="<?php if(isset($list['suse_condition']['mxn']))echo $list['suse_condition']['mxn']; ?>" />
					</div>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">最多可订天数</label>
						<input type="text" name="max_day" id="max_day" value="<?php if(isset($list['suse_condition']['mxd']))echo $list['suse_condition']['mxd']; ?>" />
					</div>
                        <div class="form-group  has-feedback">
                            <label class="col-sm-2 control-label">最少可订天数</label>
                            <input type="text" name="min_day" id="min_day" value="<?php if(isset($list['suse_condition']['min_day']))echo $list['suse_condition']['min_day']; ?>" />
                        </div>
					</div>
					
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label" style="color:#7B7B7B">优惠券使用 </label>
					<div class="col-sm-8">
							<input type='radio' name='coupon_way' value='0' onclick="if(!$(this).checked)$('#coupon_condition').hide();" <?php if(empty($list['scoupon_condition'])){?>checked='checked'<?php }?>/>使用价格代码默认设置
							<input type='radio' name='coupon_way' value='1' onclick="$('#coupon_condition').show();"  <?php if(!empty($list['scoupon_condition'])){?>checked='checked'<?php }?> />单独设置
						</div>
					</div>
					<div id='coupon_condition' <?php if(empty($list['scoupon_condition'])){ ?>style="display:none" <?php }?> >
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">可否用券</label>
						<div class="col-sm-8">
							<input type='radio' name='no_coupon' value='0' <?php if(empty($list['scoupon_condition']['no_coupon'])){?>checked='checked'<?php }?> />可用
							<input type='radio' name='no_coupon' value='1' <?php if(!empty($list['scoupon_condition']['no_coupon'])){?>checked='checked'<?php }?> />不可用 (此配置优先于会员模块配置)
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">用券数量</label>
						<div class="col-sm-8">
							<select name='coupon_num_type'>
							<option value='order' <?php if(!empty($list['scoupon_condition']['num_type'])&&$list['scoupon_condition']['num_type']=='order'){?>selected<?php }?>>每个订单可用</option>
							<option value='roomnight' <?php if(!empty($list['scoupon_condition']['num_type'])&&$list['scoupon_condition']['num_type']=='roomnight'){?>selected<?php }?>>每个间夜可用</option>
							</select>
							<input type='text' name='coupon_num' value='<?php if(!empty($list['scoupon_condition']['coupon_num'])){echo $list['scoupon_condition']['coupon_num'];  }?>' /> 张
						</div>
					</div>
					<?php if (!empty($coupon_types)){?>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">券关联</label>
						<div class="col-sm-8">
							<select name='related_coupon'>
							<option value=''>不关联</option>
							<?php foreach ($coupon_types as $card_id=>$c){?>
							<option value='<?php echo $card_id;?>' <?php if(!empty($list['scoupon_condition']['couprel'])&&$list['scoupon_condition']['couprel']==$card_id){?>selected<?php }?>><?php echo $c['title'];?></option>
							<?php }?>
							</select>
						</div>
					</div>
					<?php }?>
					
					<?php if (!empty($is_pms)){?>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">使用pms券</label>
					<div class="col-sm-8">
							<input type='radio' name='coupon_is_pms' value='0' <?php if(empty($list['scoupon_condition']['is_pms'])){?>checked='checked'<?php }?> />否
							<input type='radio' name='coupon_is_pms' value='1' <?php if(!empty($list['scoupon_condition']['is_pms'])){?>checked='checked'<?php }?> />是
						</div>
					</div>
					<?php }?>
					
					</div>
					
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label" style="color:#7B7B7B">积分使用 </label>
					<div class="col-sm-8">
							<input type='radio' name='bonus_way' value='0' onclick="if(!$(this).checked)$('#bonus_condition').hide();" <?php if(empty($list['sbonus_condition'])){?>checked='checked'<?php }?>/>使用价格代码默认设置
							<input type='radio' name='bonus_way' value='1' onclick="$('#bonus_condition').show();"  <?php if(!empty($list['sbonus_condition'])){?>checked='checked'<?php }?> />单独设置
						</div>
					</div>
					<div id='bonus_condition' <?php if(empty($list['sbonus_condition'])){ ?>style="display:none" <?php }?>>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">可否用积分兑换</label>
						<div class="col-sm-8">
							<input type='radio' name='no_part_bonus' value='0' <?php if(empty($list['sbonus_condition']['no_part_bonus'])){?>checked='checked'<?php }?> />可用
							<input type='radio' name='no_part_bonus' value='1' <?php if(!empty($list['sbonus_condition']['no_part_bonus'])){?>checked='checked'<?php }?> />不可用 (此配置优先于会员模块配置)
						</div>
					</div>
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label">积分与券同用</label>
						<div class="col-sm-8">
							<input type='radio' name='poc' value='0' <?php if(empty($list['sbonus_condition']['poc'])){?>checked='checked'<?php }?> />可同用
							<input type='radio' name='poc' value='1' <?php if(!empty($list['sbonus_condition']['poc'])){?>checked='checked'<?php }?> />不可同用
						</div>
					</div>
					</div>
					
					<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label" style="color:#7B7B7B">营销功能</label>
					
					</div>
					<div id='must_date' >
						<div class="form-group  has-feedback">
					<label class="col-sm-2 control-label" >设置某时段内显示</label>
					<div class="col-sm-8">
					<input type='radio' name='must_date' value='0' <?php if($list['smust_date']==0){?>checked='checked'<?php }?>/>使用价格代码默认设置<br />
					<input type='radio' name='must_date' value='3' <?php if($list['smust_date']==3){?>checked='checked'<?php }?>/>不限制<br />
					<input type='radio' name='must_date' value='1' <?php if($list['smust_date']==1){?>checked='checked'<?php }?>/>全指向（前台搜索时指定日期必须全匹配）<br />
					<input type='radio' name='must_date' value='2' <?php if($list['smust_date']==2){?>checked='checked'<?php }?>/>半指向（前台搜索时只需包含指定日期）<br />
					<a href="<?php echo site_url('hotel/room_status/index')."?hotel=$hotel_id&room_id=$room_id&price_code=$price_code";?>"><span>指定日期设置</span></a>
					</div>
					</div>
					</div>
					</div>
                    <div class="box-footer ">
                        <div class="col-sm-4 col-sm-offset-4">
                            <button type="submit" class="btn btn-info pull-right">保存</button>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
	<?php echo form_close() ?>
                <!-- /.box-body -->

            </div><!-- /#tab1-->
            
        </div><!-- /.tab-content -->

        </section><!-- /.content -->
</div>
<!-- /.box -->

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
<!--
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
$(function () {
	//CKEDITOR.replace('el_gs_detail');
	$(".wysihtml5").wysihtml5();
	$('.date-pick').datepicker({
		dateFormat: "yymmdd"
	});
	$('.datepicker').datepicker();
});
</script>
</body>
</html>
