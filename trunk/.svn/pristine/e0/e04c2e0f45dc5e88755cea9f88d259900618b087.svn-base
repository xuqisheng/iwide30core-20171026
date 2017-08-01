<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">
<!-- <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script> -->
<style type="text/css">
.agreement:checked+label+input{display:inline-block;}
</style>
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


<div class="over_x">
	<div class="content-wrapper" style="min-width:900px;">
		<div class="banner bg_fff p_0_20"><?php echo $breadcrumb_html; ?></div>
		<div class="contents">
			<?php echo form_open( site_url('hotel/prices/edit_code_post'), array('id'=>'code_form','class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array('price_code'=>$list['price_code']) ); ?>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
					<div class="con_right">
						<div class="hottel_name ">
							<div class="required">代码名称</div>
							<div class="input_txt"><input required placeholder="建议8字内" type="text" name="price_name" id="price_name" value="<?php echo $list['price_name']?>"/></div>
						</div>
						<div class="address">
							<div class="">代码描述</div>
							<div class="input_txt"><input placeholder="显示在代码名称下面，建议10字内" type="text" name="des" id="des" value="<?php echo $list['des']?>"/></div>
						</div>
						<div class="address">
							<div class="">代码详情</div>
							<div class="input_txt"><textarea style='min-width:450px;min-height:100px;' placeholder="显示在价格代码栏下方，建议100字内" name="detail" id="detail"><?php echo $list['detail']?></textarea></div>
						</div>
						<div class="hotel_star">
							<div class="required">价格类型</div>
							<div class="input_txt input_radio">
								<?php if(!empty($enum_des['HOTEL_PRICE_CODE_TYPE'])){ foreach($enum_des['HOTEL_PRICE_CODE_TYPE'] as $code=>$des) {?>
									<div>
										<input <?php if($code=='protrol') echo 'class="agreement"';?> type="radio" id="<?php echo $code;?>" name="type" value="<?php echo $code;?>" <?php if($list['type']==$code) echo 'checked';?>/>
										<label for="<?php echo $code;?>"><?php echo $des;?></label><?php if($code=='protrol') echo '<input id="unlock_code" name="unlock_code" class="j_inupt" type="text" value="'.$list['unlock_code'].'"/>';?>
									</div>
								<?php }}?>

								
							</div>
						</div>
					</div>
				</div>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_3f51b5"></span>价格配置</div>
					<div class="con_right">
						<div class="hotel_star">
							<div class="">关联代码</div>
							<div class="input_txt input_radio">
								<select name='related_code' id='related_code' >
									<option value=''>--不关联--</option>
									<?php if(!empty($price_codes)){ foreach($price_codes as $pcs) {?>
									<?php if($pcs['price_code']!=$list['price_code']){ ?>
										<option value="<?php echo $pcs['price_code'];?>"
									<?php if($list['related_code']==$pcs['price_code']) echo 'selected';?>><?php echo $pcs['price_name'];}?></option>
									<?php }}?>
								</select>
							</div>
						</div>
						<div class="hotel_star member_condition" <?php if($list['type']=='member'){?>style="display:none;"<?php }?>>
							<div class="">计算公式</div>
							<div class="input_txt input_radio">
								<?php if(!empty($enum_des['HOTEL_PRICE_CODE_RELATED_CAL_WAY'])) foreach($enum_des['HOTEL_PRICE_CODE_RELATED_CAL_WAY'] as $code=>$des){?>
									<div>
										<input type="radio" id="<?php echo $code;?>" name="related_cal_way" value="<?php echo $code;?>" <?php if($code==$list['related_cal_way'] || (empty($list['related_cal_way'])&&$code=='divide')){?>checked<?php }?>/>
										<label for="<?php echo $code;?>"><?php echo $des;?></label>
									</div>
								<?php }?>
							</div>
						</div>
						<div class="hottel_name member_condition" <?php if($list['type']=='member'){?>style="display:none;"<?php }?>>
							<div class="">计算值</div>
							<div class="input_txt"><input type="text" name="related_cal_value" value="<?php echo $list['related_cal_value']; ?>"/></div>
						</div>
					</div>
				</div>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_ff503f"></span>使用条件</div>
					<div class="con_right">
						<div class="hotel_star">
							<div class="">预付标记</div>
							<div class="input_txt input_radio">
								<div>
									<input type="radio" id="display_1" name='pre_pay' value='0' <?php if(empty($list['use_condition']['pre_pay'])){?>checked='checked'<?php }?>/>
									<label for="display_1">不显示</label>
								</div>
								<div>
									<input type="radio" id="display_2" name='pre_pay' value='1' <?php if(!empty($list['use_condition']['pre_pay'])){?>checked='checked'<?php }?>/>
									<label for="display_2">显示</label>
								</div>
							</div>
						</div>
						<div class="hotel_star clearfix">
							<div class="">不使用</div>
							<div class="input_txt input_radio">
								<?php foreach($pay_ways as $pw){?>
									 <div>
									 	<input type="checkbox" id="<?php echo $pw->pay_type;?>" name="no_pay_way[]" value="<?php echo $pw->pay_type;?>" <?php if(!empty($list['use_condition']['no_pay_way'])&&in_array($pw->pay_type, $list['use_condition']['no_pay_way'])){?>checked='checked'<?php }?>/>
									 	<label for="<?php echo $pw->pay_type;?>"><?php echo $pw->pay_name;?></label>
									 </div>
								<?php }?>
							</div>
						</div>
						<?php if (!empty($has_package_pay)){?>
							<div class="hotel_star">
								<div class="">仅用于套票预订</div>
								<div class="input_txt input_radio label_parent_w">
									<div>
										<input type="radio" id="package_only_1" name="package_only" value='0' <?php if(empty($list['use_condition']['package_only'])){?>checked='checked'<?php }?>/>
										<label for="package_only_1">否</label>
									</div>
									<div>
										<input type="radio" id="package_only_2" name="package_only" value='1' <?php if(!empty($list['use_condition']['package_only'])){?>checked='checked'<?php }?>/>
										<label for="package_only_2">是</label>
									</div>
								</div>
							</div>
						<?php }?>
							<div class="hotel_star">
								<div class="required">会员等级</div>
								<div class="input_txt input_radio">
									<div>
										<input type="radio" id="geade_1" name="member_level" value="-1" checked/>
										<label for="geade_1">不关联</label>
									</div>
								<?php if(!empty($levels)) {?>
									<?php foreach($levels as $k=>$lv){?>
									<div>
										<input type="radio" id="<?php echo $k; ?>" name="member_level" value="<?php echo $k; ?>" <?php if(isset($list['use_condition']['member_level'])&&$list['use_condition']['member_level']==$k){?>checked<?php }?>/>
										<label for="<?php echo $k; ?>"><?php echo $lv;?></label>
									</div>
									<?php }?>
								<?php }?>
								</div>
							</div>
						<div class="hotel_star">
							<div class="">入住日期</div>
							<div class="w_550"><i class="iconfont symbol">&#xe672;</i><input name="s_date_s" id="datepicker" class="datepicker j_inupt" type="text" value="<?php if(isset($list['use_condition']['s_date_s']))echo $list['use_condition']['s_date_s']; ?>"/>，<i class="iconfont symbol">&#xe612;</i><input name="s_date_e" id="datepicker2" class="datepicker j_inupt m_r_2" type="text" value="<?php if(isset($list['use_condition']['s_date_e']))echo $list['use_condition']['s_date_e']; ?>"/>方可预定</div>
						</div>
						<div class="address">
							<div class="">离店日期</div>
							<div class="w_550"><i class="iconfont symbol">&#xe672;</i><input name="e_date_s" id="datepicker3" class="datepicker j_inupt" type="text" value="<?php if(isset($list['use_condition']['e_date_s']))echo $list['use_condition']['e_date_s']; ?>"/>，<i class="iconfont symbol">&#xe612;</i><input name="e_date_e" id="datepicker4" class="datepicker j_inupt m_r_2"  type="text" value="<?php if(isset($list['use_condition']['e_date_e']))echo $list['use_condition']['e_date_e']; ?>"/>方可预定</div>
						</div>
						<div class="address">
							<div class="">提前天数</div>
							<div class="input_txt"><input type="text" name="pre_day" id="pre_day" placeholder="提前预订天数" value="<?php if(isset($list['use_condition']['pre_d']))echo $list['use_condition']['pre_d']; ?>"/></div>
						</div>
						<div class="address">
							<div class="">最大间数</div>
							<div class="input_txt"><input type="text" placeholder="单次最多可预订多少间房" name="max_num" id="max_num" value="<?php if(isset($list['use_condition']['mxn']))echo $list['use_condition']['mxn']; ?>" /></div>
						</div>
						<div class="address">
							<div class="">可定天数</div>
							<div class=""><i class="iconfont symbol">&#xe672;</i><input class="j_inupt" type="text" name="min_day" id="min_day" value="<?php if(isset($list['use_condition']['min_day']))echo $list['use_condition']['min_day']; ?>" />，<i class="iconfont symbol">&#xe612;</i><input class="j_inupt" type="text" name="max_day" id="max_day" value="<?php if(isset($list['use_condition']['mxd']))echo $list['use_condition']['mxd']; ?>"/></div>
						</div>

						<div class="address time_condition" style="<?php if($list['type']!='athour')echo 'display:none;'; ?>" >
							<div class="required">到店时间段</div>
							<div class="w_550">
								<select id="book_time_s" name="book_time_s">
									<option value="0">请选择</option>
									<?php for ($i=0;$i<24;$i++) { $value = $i<10?'0'.$i.'00':$i.'00';?>
									<option value="<?php echo $value;?>" <?php if(isset($list['time_condition']['book_time']['s'])&&$list['time_condition']['book_time']['s'] == $value){ echo 'selected';}?>><?php echo $i<10?'0'.$i.':00':$i.':00';?></option>
									<?php }?>
								</select>
								~<select id="book_time_e" name="book_time_e">
									<option value="0">请选择</option>
									<?php for ($i=0;$i<24;$i++) { $value = $i<10?'0'.$i.'00':$i.'00';?>
									<option value="<?php echo $value;?>" <?php if(isset($list['time_condition']['book_time']['e'])&&$list['time_condition']['book_time']['e'] == $value){ echo 'selected';}?>><?php echo $i<10?'0'.$i.':00':$i.':00';?></option>
									<?php }?>
								</select>(价格类型为时租价时必填)
							</div>
						</div>
						<div class="address time_condition" style="<?php if($list['type']!='athour')echo 'display:none;'; ?>">
							<div class="required">时间间隔</div>
							<div class="w_550">
								<select id='book_time_mod' name='book_time_mod'>
									<option value="0">请选择</option>
									<option value='60' <?php if(!empty($list['time_condition']['book_time']['mod'])&&$list['time_condition']['book_time']['mod']=='60')echo 'selected'; ?>>一小时</option>
									<option value='30' <?php if(!empty($list['time_condition']['book_time']['mod'])&&$list['time_condition']['book_time']['mod']=='30')echo 'selected'; ?>>半小时</option>
								</select>(价格类型为时租价时必填)
							</div>
						</div>

					</div>
				</div>
			<div class="contents_list bg_fff">
				<div class="con_left"><span class="block bg_ff503f"></span>预订政策</div>
				<div class="con_right">
					<div class="hotel_star">
						<div class="">微信支付立减</div>
						<div class="rule_input">
							<input type="text" name="wxpay_favour" id="wxpay_favour" 
							value="<?php echo empty($list['bookpolicy_condition']['wxpay_favour']) ? '' : $list['bookpolicy_condition']['wxpay_favour']; ?>"/>元
						</div>
					</div>
					<div class="hotel_star">
						<div class="required">早餐</div>
						<div class="input_txt input_radio">
							<?php foreach ($bf_fields as $kbf => $bf_field) {?>
								<div>
									<input type="radio" id="bf-<?php echo $kbf; ?>" name="breakfast_nums" value="<?php echo $kbf;?>" <?php if((isset($list['bookpolicy_condition']['breakfast_nums'])&&$list['bookpolicy_condition']['breakfast_nums']==$kbf) || (!isset($list['bookpolicy_condition']['breakfast_nums'])&&$kbf=='-1')){?>checked='checked'<?php }?>/>
									<label for="bf-<?php echo $kbf; ?>"><?php echo $bf_field;?></label>
								</div>
							<?php }?>
						</div>
					</div>
					<div class="hotel_star">
						<div class="">保留时间</div>
						<div class="input_txt">
							<ul>
								<?php foreach($pay_ways as $pw){ ?>
									<li>
										<?php echo $pw->pay_name; ?>
										入住日期的
										<input type="text" name="retain_time[<?php echo $pw->pay_type; ?>]"
												id="external_code" placeholder="(可不填)"
												value="<?php echo isset($list['bookpolicy_condition']['retain_time'][$pw->pay_type]) ? $list['bookpolicy_condition']['retain_time'][$pw->pay_type] : '18'; ?>">点
									</li>
							<?php } ?>
							</ul>
						</div>
					</div>
					<div class="hotel_star">
						<div class="">退房时间</div>
						<div class="input_txt">
							<ul>
							<?php foreach($pay_ways as $pw){?>
								<li>
									<?php echo $pw->pay_name; ?>
									离店日期的
									<input type="text" name="delay_time[<?php echo $pw->pay_type; ?>]" id="external_code" placeholder="(可不填)" value="<?php echo isset($list['bookpolicy_condition']['delay_time'][$pw->pay_type])?$list['bookpolicy_condition']['delay_time'][$pw->pay_type]:'12'; ?>">点
								</li>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_ff503f"></span>营销规则</div>
					<div class="con_right">
						<div class="hotel_star">
							<div class="">用券规则</div>
							<div class="input_txt input_radio label_parent_w rule_select">
								<div>
									<input type="radio" id="rule_2" name='no_coupon' value='1' <?php if(!empty($list['coupon_condition']['no_coupon'])){?>checked='checked'<?php }?>/>
									<label for="rule_2">不可用</label>
								</div>
								<div>
									<input class="rule_input" type="radio" id="rule_1" name='no_coupon' value='0' <?php if(empty($list['coupon_condition']['no_coupon'])){?>checked='checked'<?php }?>/>
									<label for="rule_1" style="">可用</label><div class="rule_display" style="">
										<select class="j_inupt" name="coupon_num_type" style="width:125px;">
											<option value='order' <?php if(!empty($list['coupon_condition']['num_type'])&&$list['coupon_condition']['num_type']=='order'){?>selected<?php }?>>每个订单可用</option>
											<option value='roomnight' <?php if(!empty($list['coupon_condition']['num_type'])&&$list['coupon_condition']['num_type']=='roomnight'){?>selected<?php }?>>每个间夜可用</option>
										</select>
										<input class="j_inupt number_input" name='coupon_num' value='<?php if(!empty($list['coupon_condition']['coupon_num'])){echo $list['coupon_condition']['coupon_num'];  }?>'>&nbsp;张</div>
								</div>
							</div>
						</div>
						<?php if (!empty($coupon_types)){?>
							<div class="hotel_star">
								<div class="">券关联</div>
								<div class="input_txt input_radio">
									<select name='related_coupon' id='related_coupon' >
										<option value=''>--不关联--</option>
										<?php foreach ($coupon_types as $card_id=>$c){?>
										<option value='<?php echo $card_id;?>' <?php if(!empty($list['coupon_condition']['couprel'])&&$list['coupon_condition']['couprel']==$card_id){?>selected<?php }?>><?php echo $c['title'];?></option>
										<?php }?>
									</select>
								</div>
							</div>
						<?php }?>
						<?php if (!empty($is_pms)){?>
							<div class="hotel_star">
								<div class="">使用pms券</div>
								<div class="input_txt input_radio label_parent_w">
									<div>
										<input type="radio" id="exchange_pms1" name="coupon_is_pms" value='0' <?php if(empty($list['coupon_condition']['is_pms'])){?>checked='checked'<?php }?>/>
										<label for="exchange_pms1">否</label>
									</div>
									<div>
										<input type="radio" id="exchange_pms2" name="coupon_is_pms" value='1' <?php if(!empty($list['coupon_condition']['is_pms'])){?>checked='checked'<?php }?>/>
										<label for="exchange_pms2">是</label>
									</div>
								</div>
							</div>
						<?php }?>
						<div class="hotel_star">
							<div class="">积分兑换</div>
							<div class="input_txt input_radio label_parent_w">
								<div>
									<input type="radio" id="exchange_2" name='no_part_bonus' value='1' <?php if(!empty($list['bonus_condition']['no_part_bonus'])){?>checked='checked'<?php }?>/>
									<label for="exchange_2">不可用</label>
								</div>
								<div>
									<input type="radio" id="exchange_1" name='no_part_bonus' value='0' <?php if(empty($list['bonus_condition']['no_part_bonus'])){?>checked='checked'<?php }?>/>
									<label for="exchange_1">可用</label>
								</div>
							</div>
						</div>
						<div class="hotel_star">
							<div class="">积分与券</div>
							<div class="input_txt input_radio label_parent_w">
								<div>
									<input type="radio" id="rule_exchange_2" name='poc' value='1' <?php if(!empty($list['bonus_condition']['poc'])){?>checked='checked'<?php }?>/>
									<label for="rule_exchange_2">不可同时使用</label>
								</div>
								<div>
									<input type="radio" id="rule_exchange_1" name='poc' value='0' <?php if(empty($list['bonus_condition']['poc'])){?>checked='checked'<?php }?>/>
									<label for="rule_exchange_1">可同时使用</label>
								</div>
							</div>
						</div>
						<div class="hotel_star">
							<div class="">时段限制</div>
							<div class="input_txt input_radio label_parent_w">
								<div>
									<input type="radio" id="interval_1" name='must_date' value='3' <?php if($list['must_date']==3){?>checked='checked'<?php }?>/>
									<label for="interval_1">不限制</label>
								</div>
								<div>
									<input type="radio" id="interval_2" name='must_date' value='1' <?php if($list['must_date']==1){?>checked='checked'<?php }?>/>
									<label for="interval_2" style="width:220px;background-size:15px;">全指向(前端日期必须全匹配)</label>
								</div>
								<div>
									<input type="radio" id="interval_3" name='must_date' value='2' <?php if($list['must_date']==2){?>checked='checked'<?php }?>/>
									<label for="interval_3" style="width:220px;background-size:15px;">半指向(前端日期只需包含)</label>
								</div>
								<?php if(!empty($price_code)){?>
								<a href="<?php echo site_url('hotel/room_status/index')."?price_code=$price_code";?>"><span>指定日期设置</span></a>
								<?php }?>
							</div>
						</div>
						<?php if (!empty($is_pms)){?>
							<div class="hotel_star">
								<div class="">对应PMS价格代码</div>
								<div class="">
									<div>
										<input type="text" name="external_code" id="external_code" placeholder="对应PMS价格代码" value="<?php if(isset($list['external_code'])&&$list['external_code']!=='')echo $list['external_code']; ?>" />
										<label for="interval_1">(修改此项会马上影响到线上价格，若不确定请咨询相关工作人员)</label>
									</div>
								</div>
							</div>
						<?php }?>
					</div>
				</div>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_4caf50"></span>其他</div>
					<div class="con_right">
						<div class="hottel_name ">
							<div class="">代码排序</div>
							<div class="input_txt"><input type="text" name="sort" id="sort" placeholder="数字越大，排序越前" value="<?php echo $list['sort']?>"/></div>
						</div>
						<div class="hotel_star">
							<div class="">状态</div>
							<div class="input_txt input_radio label_parent_w">
								<?php if(!empty($enum_des['HOTEL_PRICE_CODE_STATUS'])){ foreach($enum_des['HOTEL_PRICE_CODE_STATUS'] as $code=>$des) {?>
								<div>
									<input type="radio" id='status<?php echo $code;?>' name='status' value='<?php echo $code;?>' 
									<?php if($list['status']==$code){?>checked='checked'<?php }?>/>
									<label for="status<?php echo $code;?>"><?php echo $des;?></label>
								</div>
								<?php }}?>
							</div>
						</div>
					</div>
				</div>
				<div class="bg_fff" style="padding:15px;padding-left: 30%;">
					<button type="button" onclick='sub_code();' class="fom_btn">保存</button>
					<button style="display: none;" type="submit" id="sub" value="submit" ></button>
				</div>
				<input id='service_data' name='service_data' type='hidden' value='' />
			<?php echo form_close() ?>
		</div>
	</div>
</div>

      <!-- Content Wrapper. Contains page content -->
  
<?php echo $this->session->show_put_msg(); ?>


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
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/layDate.js"></script><script>
;!function(){
	laydate({
	   elem: '#datepicker',
     format: 'YYYYMMDD',
	})
	laydate({
	   elem: '#datepicker2',
     format: 'YYYYMMDD',
	})
	laydate({
	   elem: '#datepicker3',
     format: 'YYYYMMDD',
	})
	laydate({
	   elem: '#datepicker4',
     format: 'YYYYMMDD',
	})
}();
</script>
<script>
var data={};
var submiting =false;
function sub_code(){
	if(submiting){
		return;
	}
	submiting = true;
	if($('input[name=type]:checked').val()=='member'&&($('input[name=member_level]:checked').val()==-1||$('#related_code').val()=='')){
		alert('会员类型价格必须设置关联价格代码和关联等级');
		submiting =false;
		return;
	}
	else if($('input[name=type]:checked').val()=='protrol'&&$('#unlock_code').val()==''){
		alert('协议价格必须设置协议代码');
		submiting =false;
		return;
	}
	else if($('input[name=type]:checked').val()=='athour'&&($('#book_time_e').find("option:selected").text()=='请选择' || $('#book_time_s').find("option:selected").text()=='请选择' || $('#book_time_mod').find("option:selected").text()=='请选择' ) ){
		alert('时租价格必须设置到店时间段与时间间隔');
		submiting =false;
		return;
	}
	ranges=$("[key='key']");
	$.each(ranges,function(i,n){
		service=$(n).find('input[name="add_service"]');
		if(service.is(":checked")==true){
			data[service.val()]={};
			data[service.val()]['max_num']=$(n).find("[name='max_num']").val();
			data[service.val()]['service_price']=$(n).find("[name='service_price']").val();
		}
	});
	json=JSON.stringify(data);
	$('#service_data').val(json);
	$('#sub').click();
	submiting =false;
}
function check_athour () {
	if($('input[name=type]:checked').val()=='athour'){
		$('.time_condition').show();
	}else{
		$('.time_condition').hide();
	}
	if($('input[name=type]:checked').val()=='member'){
		$('.member_condition').hide();
	}else{
		$('.member_condition').show();
	}
	
}
$("input[name=type]").click(function(){
	check_athour ();
});
</script>
</body>
</html>
