<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
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



  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        用券规则
        <small>Useing Rules</small>
      </h1>
      <ol class="breadcrumb">
      </ol>
    </section>

<form role="form" method='post' id='form1' action='<?php echo site_url('hotel/coupons/save_userule');?>'>
<input type='hidden' name='rule_id' value='<?php echo $list['rule_id'];?>' />
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">编辑规则</h3>
            </div>
            <div class="box-body">
                <div class="form-group col-xs-6"><!--  has-error错误  has-success正确 -->
                  <label>规则名称</label>
                  <input type="text" name='rule_name' class="form-control" placeholder="请输入规则名称" value='<?php echo $list['rule_name'];?>'>
                </div>
                <div class="form-group col-xs-6">
                  <label>卡券类型</label>
                  <select class="form-control" name='rule_type'>
                    <option <?php if ($list['rule_type']=='voucher') echo 'selected';?> value='voucher'>代金券</option>
                    <?php if ($list['rule_type']=='discount'){?><option <?php if ($list['rule_type']=='discount') echo 'selected';?> value='discount'>折扣券</option><?php }?>
                    <!-- <option <?php if ($list['rule_type']=='exchange') echo 'selected';?> value='exchange'>礼品券</option> -->
                  </select>
                </div>
                <div class="form-group col-xs-6">
                  <label>使用门店</label><label id='hotel_tips' style='color:red;'></label>
                  <div class="radio"><label><input type="radio" name="hotel_rooms" value="all" 
                  <?php if (empty($list['hotel_rooms'])){?>checked<?php }?>>全部门店和房型</label></div>
                  <div class="radio part_show_radio"><label><input type="radio" name="hotel_rooms" value="part"  
                  <?php if (!empty($list['hotel_rooms'])){?>checked<?php }?>>指定门店和房型</label></div>
                  <div class="btn btn-default btn-xs part_show add_hotel_btn" style="display:none"><i class="fa fa-plus"></i> 添加适用门店</div>
                </div>
                <div class="form-group col-xs-6">
                  <label>卡券列表</label>
                   <?php if (!empty($coupon_types)){foreach ($coupon_types as $c){?>
                  <div class="checkbox">
                  	<label><input type="checkbox" name='coupon_ids[]' <?php if (!empty($list['coupon_ids'])&&in_array($c['card_id'], $list['coupon_ids'])){?>checked='checked'<?php }?> value='<?php echo $c['card_id'];?>'> <?php echo $c['title'];?></label>
                  </div>
                   <?php }}?>
                </div>
            </div>
          </div>
        </div>       
        
         
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">其他规则</h3>
            </div>
            <div class="box-body">
                <div class="form-group col-xs-3">
                  <label>会员等级(非必选)</label>
                  <div class="radio"><label><input type="radio" name="member_level" value="all" <?php if (empty($list['extra_rule']['level'])){?>checked<?php }?>>全部会员等级</label></div>
                  <div class="radio part_show_radio"><label><input type="radio" name="member_level" value="part" <?php if (!empty($list['extra_rule']['level'])){?>checked<?php }?>>指定会员等级</label></div>
                  <div class="checkbox part_show" style="display:none">
                      <table class="table table-bordered">
                      <?php if (!empty($member_levels)){?>
                        <tr>
                        <?php foreach ($member_levels as $level=>$name){?>
                      		<td><label><input type="checkbox" name='levels[]' <?php if (!empty($list['extra_rule']['level'])&&in_array($level, $list['extra_rule']['level'])){?>checked='checked'<?php }?> value='<?php echo $level;?>'> <?php echo $name;?></label></td>
                      		<?php }?>
                        </tr>
                      <?php }?>
                      </table>
                  </div>
                </div>
                <div class="form-group col-xs-3">
                  <label>支付方式(非必选)</label>
                  <div class="radio"><label><input type="radio" name="paytype" value="all" <?php if (empty($list['extra_rule']['paytype'])){?>checked<?php }?>>全部支付方式</label></div>
                  <div class="radio part_show_radio"><label><input type="radio" name="paytype" value="part" <?php if (!empty($list['extra_rule']['paytype'])){?>checked<?php }?>>指定支付方式</label></div>
                  <div class="checkbox part_show" style="display:none">
                      <table class="table table-bordered">
                          <?php if (!empty($pay_ways)){?>
                          <tr>
                          <?php foreach ($pay_ways as $k=>$p){?>
                          <td><label><input type="checkbox" name='pay_ways[]' <?php if (!empty($list['extra_rule']['paytype'])&&in_array($p->pay_type, $list['extra_rule']['paytype'])){?>checked='checked'<?php }?> value='<?php echo $p->pay_type;?>'> <?php echo $p->pay_name;?></label></td>
                          <?php }?>
                          </tr>
                          <?php }?>
                      </table>
                  </div>
                </div>
                <div class="form-group col-xs-3">
                  <label>按金额</label>
                  <div class="radio">
                  	<label>消费满</label>
                  	<input type="number" name="min_money" style='width: 4em' placeholder="0" <?php if (!empty($list['extra_rule']['min_money'])){?>value="<?php echo $list['extra_rule']['min_money'];?>"<?php }?> />元可用(不填为不限)
                  </div>
                </div>
            </div>
          </div>
        </div>      
        
        
        <div class="col-xs-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">
	              <input type="radio" name="rule_dates_r" value="1" <?php if (empty($list['rule_dates'])||$list['rule_dates']['r']==1){?>checked<?php }?>>选中日期不可用
	              <input type="radio" name="rule_dates_r" value="2" <?php if (!empty($list['rule_dates'])&&$list['rule_dates']['r']==2){?>checked<?php }?>>选中日期可用
              </h3>
            </div>
            <div class="box-body">
                <div class="form-group col-xs-12">
                  <label>星期</label>
                  <div class="checkbox">
                      <table class="table table-bordered"><tr>
                      	<td><label><input type="checkbox" name='weekdays[]' value='1' <?php if (!empty($list['rule_dates']['d']['r']['week'])&&in_array(1, $list['rule_dates']['d']['r']['week']))echo 'checked';?>> 周一</label></td>
                        <td><label><input type="checkbox" name='weekdays[]' value='2' <?php if (!empty($list['rule_dates']['d']['r']['week'])&&in_array(2, $list['rule_dates']['d']['r']['week']))echo 'checked';?>> 周二</label></td>
                        <td><label><input type="checkbox" name='weekdays[]' value='3' <?php if (!empty($list['rule_dates']['d']['r']['week'])&&in_array(3, $list['rule_dates']['d']['r']['week']))echo 'checked';?>> 周三</label></td>
                        <td><label><input type="checkbox" name='weekdays[]' value='4' <?php if (!empty($list['rule_dates']['d']['r']['week'])&&in_array(4, $list['rule_dates']['d']['r']['week']))echo 'checked';?>> 周四</label></td>
                        <td><label><input type="checkbox" name='weekdays[]' value='5' <?php if (!empty($list['rule_dates']['d']['r']['week'])&&in_array(5, $list['rule_dates']['d']['r']['week']))echo 'checked';?>> 周五</label></td>
                        <td><label><input type="checkbox" name='weekdays[]' value='6' <?php if (!empty($list['rule_dates']['d']['r']['week'])&&in_array(6, $list['rule_dates']['d']['r']['week']))echo 'checked';?>> 周六</label></td>
                        <td><label><input type="checkbox" name='weekdays[]' value='0' <?php if (!empty($list['rule_dates']['d']['r']['week'])&&in_array(0, $list['rule_dates']['d']['r']['week']))echo 'checked';?>> 周日</label></td>
                       </tr>
                      </table>
                  </div>
                </div>
                <div class="form-group col-xs-8">
                	<label>日期(仅选择一天则只填开始日期即可)</label>
                    
                    <table class="table table-bordered range_pick">
                    <?php if (!empty($list['rule_dates']['d']['d'])){foreach ($list['rule_dates']['d']['d'] as $d){$tmp=explode('-', $d);?>
                      <tr>
                        <td><input type="text" name='start_range[]' value='<?php echo date('Y-m-d',strtotime($tmp[0]));?>' data-date-format="yyyy-mm-dd" class="form-control datepicker"></td>
                        <td><input type="text" name='end_range[]' value='<?php if (!empty($tmp[1])) echo date('Y-m-d',strtotime($tmp[1]));?>' data-date-format="yyyy-mm-dd" class="form-control datepicker"></td>
                        <td><div type="button" class="btn btn-danger rm"><i class="fa fa-trash-o"></i> 删除</div></td>
                      </tr>
                      <?php }}?>
                       <tr>
                      	<td><input type="text" name='start_range[]' data-date-format="yyyy-mm-dd" class="form-control datepicker"></td>
                        <td><input type="text" name='end_range[]' data-date-format="yyyy-mm-dd" class="form-control datepicker"></td>
                        <td><div type="button" class="btn btn-danger rm"><i class="fa fa-trash-o"></i> 删除</div></td>
                      </tr>
                    </table>
                    <div id="add_date_range" type="button" class="btn btn-success"><i class="fa fa-plus"></i> 添加</div>
                </div>
                <div class="form-group col-xs-12" style="margin-top:25px; margin-bottom:0">
                  <label>是否激活</label>
                  <div class="radio"><label><input type="radio" name="status" value="1" <?php if ($list['status']==1)echo 'checked';?>>确定激活</label></div>
                  <div class="radio"><label><input type="radio" name="status" value="2" <?php if ($list['status']==2)echo 'checked';?>>取消激活</label></div>
                </div>
            </div>
          </div>
        </div>   
       <div class="col-xs-12 ">
         <button type="button" onclick='sub()' class="btn btn-primary " style='margin-left: 40%'>保存</button>  
         <label id='tips' style='color:red;'></label>
       </div>
      </div>
      <!-- /.row -->
    </section>
    
    
    <style>
	.layer{position:fixed; top:0; left:0;overflow:hidden; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; display:none}
	.add_hotel_content{background:#f8f8f8; padding:15px; height:100%; float:right; overflow-y:scroll;width:850px;}
	.child_dom{ padding:3px 5px; margin:5px;background:#fff; border:1px solid #3c8dbc;max-width:450px; vertical-align:middle}
	.parent_dom div{display:inline-block}
	.parent_dom .checkbox{margin:2px}
	#coupons_table td{vertical-align:middle}
	</style>
    <div class="layer add_hotel">
        <div class="add_hotel_content">
          <div class="box box-primary">
            <div class="box-body"><div class="checkbox allhotelcheck"><label><input type="checkbox"> 全选所有</label></div></div>
          </div>
          
          <div class="box box-primary">
            <div class="box-body">
              <div class="pulltips" style="display:none"><i class="fa fa-spinner"></i> 正在加载...</div>
              <div class="form-group total_code" id='price_codes'>
              </div>
            </div>
          </div>
          
          <div class="box">
            <div class="box-body">     
              <div class="form-group col-xs-6 pull-right">
                  <input type="text" class="form-control searchtable" placeholder="搜索酒店名">
              </div>       
              <div class="pulltips" style="display:none"><i class="fa fa-spinner"></i> 正在加载...</div>   
              <table id="coupons_table" class="table table-bordered table-striped">
              </table>
              <ul class="pagination col-xs-6 pull-right page_concol">
                <li class="paginate_button active"><a href="#">1</a></li></ul>
            </div>
         </div>
         
         <div class="btn btn-primary layer_success" style='margin-left: 40%;'>完成</div>
      </div>
    </div>
</form>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<!--<script src="--><?php //echo base_url(FD_PUBLIC) ?><!--/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
var submit_tag=0;
var add_tag=0;
var page_set={
		first_load:true,
	}
function sub(){
	if(add_tag==1){
		$('#tips').html('已经添加，请勿重复添加。');
		return false;
	}
	var ranges=$('.range_pick tr');
	var check=true;
	if(submit_tag==0){
		submit_tag=1;
		$('#tips').html('提交中');
		$.each(ranges,function(i,n){
			var s='';
			start=$(n).find('input[name="start_range[]"]').val().replace(/-/g,'');
			end=$(n).find('input[name="end_range[]"]').val().replace(/-/g,'');
			if(start!=''&&start!=undefined){
				if(isNaN(start)){
					$('#tips').html('开始日期错误');
					check=false;
					return false;
				}
				if(end!=''&&end!=undefined){
					if(isNaN(end)||end<start){
						$('#tips').html('结束日期错误或大于开始日期');
						check=false;
						return false;
					}
				}
			}
		});
		if(!check){
			submit_tag=0;
			return false;
		}
		$.post('<?php echo site_url('hotel/coupons/save_userule')?>',
				{
					datas:JSON.stringify($('#form1').serializeArray()),
					<?php echo $csrf_token?>:'<?php echo $csrf_value?>'
				},
		function(data){
			if(data.status==10){
				add_tag=1;
			}
			$('#tips').html(data.message);
			submit_tag=0;
		},'json');
	}else{
		$('#tips').html('提交中，请勿重复提交');
	}
}
function rm(){
	if($('.range_pick tr').length<=1){
		$(this).parents('tr').find('input').val('');
		return;
	}
	$(this).parents('tr').remove();
	$('#add_date_range').show();
}
function layerclose(){
	$('.add_hotel').stop().fadeOut();
	$('body').removeAttr('style');
}
function ajaxevent_bind(){
	$('.add_hotel').bind('click',layerclose);	
	$('.layer_success').click(function(){
		$('.add_hotel').stop().fadeOut();
		$('body').removeAttr('style');
	})
	$('input[type="checkbox"]','.allcodecheck').click(function(){
		var bool =$(this).get(0).checked;
		var tmp = $('.codecheck');
		tmp.each(function() {
         	$(this).find('input').get(0).checked=bool;
        });
	})
	$('input[type="checkbox"]','.codecheck').click(function(){
		var bool   =$(this).get(0).checked;
		var parent =$(this).parents('.codecheck');
		var tmp    =parent.siblings('.allcodecheck');
		if(tmp.length>0){
			parent.siblings('.codecheck').each(function() {
				if( !$(this).find('input').get(0).checked ) 
					bool=$(this).find('input').get(0).checked;
			});
			tmp.find('input').get(0).checked=bool;
		}
	})
	$('input[type="checkbox"]','.allhotelcheck').click(function(){
		var bool   =$(this).get(0).checked;
		$('.add_hotel input').each(function() {
         	$(this).get(0).checked=bool;            
        });
	})
	$('input[type="checkbox"]','.hotelcheck').click(function(){
		var bool;
		$('.hotelcheck').each(function() {
			if( !$(this).find('input').get(0).checked ) 
				bool=$(this).find('input').get(0).checked;
		});
		$('input[type="checkbox"]','.allhotelcheck').get(0).checked=bool;
		bool=$(this).get(0).checked;
		$(this).parents('td').siblings().find('input').each(function(){
			$(this).get(0).checked=bool;
		});
	})
	$('.add_hotel_content').click(function(e){
		e.stopPropagation();
	})
	$('input[type="checkbox"]','.roomcheck').click(function(){
		var bool =$(this).get(0).checked;
		$(this).parents('.roomcheck').siblings().find('input').each(function() {
			$(this).get(0).checked=bool;
        });
	});
	$('input[type="checkbox"]','.total_code .codecheck').click(function(){
		var code = $(this).parents('.codecheck').attr('code');
		var bool = $(this).get(0).checked;
		$('.codecheck[code="'+code+'"]').find('input').each(function() {
            $(this).get(0).checked=bool;
        });
	})
	var tr_length  = $('#coupons_table tbody tr').length;
	if(tr_length>10){
		var page_length= tr_length/10;
		for( var i=10; i<tr_length;i++){
			$('#coupons_table tbody tr').eq(i).hide();
		}
		for (var i=1;i<page_length;i++){
			$('.page_concol').append('<li class="paginate_button"><a href="#">'+(i+1)+'</a></li>');
		}
		$('.paginate_button').click(function(){
			var _index=$(this).index();
			$(this).addClass('active').siblings().removeClass('active');
			$('#coupons_table tbody tr').hide();
			for( var i=_index*10; i<(_index+1)*10&&i<tr_length;i++){
				$('#coupons_table tbody tr').eq(i).show();
			}
		})
	}else{
		$('.page_concol').hide();
	}
	$('.searchtable').bind('input propertychange',function(){
		var val=$(this).val();
		if(val==''){
			if(tr_length>10){
				$('.page_concol').show();
				var _index=$('.paginate_button.active').index();
				for( var i=_index*10; i<(_index+1)*10&&i<tr_length;i++){
					$('#coupons_table tbody tr').eq(i).show();
				}
			}else{
				$('#coupons_table tbody tr').show()
			}
		}
		else{
			$('.page_concol').hide();
			$('.hotelcheck').each(function(index, element) {
                if( $(this).text().indexOf(val)>=0){
					$(this).parents('tr').show();
				}else{
					$(this).parents('tr').hide();
				}
            });
		}
	})
}
$(function(){
	$('.datepicker').datepicker({
		dateFormat: "yymmdd"
	});
	$('.rm').bind('click',rm);
	$('#add_date_range').click(function(){
		if($('.range_pick tr').length>=5){$(this).hide(); return;}
		var tmp=$('.range_pick tr').eq(0).clone(false);
		tmp.find('input').val('').datepicker({dateFormat: "yymmdd"});
		tmp.find('.rm').bind('click',rm);
		$('.range_pick').append(tmp);
	});
	$('.radio').each(function(index, element) {
        $(this).get(0).addEventListener('click',function(){
			if($(this).hasClass('part_show_radio'))
				$(this).siblings('.part_show').show();
			else
				$(this).siblings('.part_show').hide();
		});
    });
	$('.part_show_radio').each(function(index, element) {
       	var bool = $(this).find('input').get(0).checked;
		if(bool) $(this).siblings('.part_show').show();
    });
	$('.add_hotel_btn').click(function(){
		$('.add_hotel').stop().fadeIn();
		$('body').css('overflow','hidden');
		if(page_set.first_load)	{
			$.get('<?php echo site_url('hotel/coupons/ajax_ur_hotel_rooms')?>',{
				rid:'<?php echo $list['rule_id']?>'
			},function(data){
				if(data.status==1){
					s='<div class="checkbox allcodecheck"><label><input type="checkbox"> 所有价格代码</label></div>';
					$.each(data.data.price_codes,function(i,n){
						s+='<div class="checkbox codecheck col-xs-4" code="'+i+'"><label><input type="checkbox"> '+n+'</label></div>';
					});
					$('#price_codes').html(s);
					$('#coupons_table').html('<thead><tr><th>酒店</th><th>房型</th></tr></thead>');
					s='';
					$.each(data.data.hotel_rooms,function(i,n){
						s+='<tr><td><div class="checkbox hotelcheck"><label><input name="hotel_ids[]" value="'+i+'" type="checkbox"';
						if(n.check!=undefined&&n.check==1){
							s+=' checked ';
						}
						s+='> '+n.name+'</label></div></td>';
						s+='<td>';
						if(n.rooms!=undefined&&n.rooms!=[]){
							$.each(n.rooms,function(ri,rn){
								s+='<div class="parent_dom"><div class="checkbox roomcheck"><label>';
								s+='<input name="room_ids_'+i+'[]" value="'+ri+'" type="checkbox"';
								if(rn.check!=undefined&&rn.check==1){
									s+=' checked ';
								}
								s+='> '+rn.name+'</label></div>';
								if(rn.codes!=undefined&&rn.codes!=[]){
									s+='<div class="child_dom">';
									$.each(rn.codes,function(rci,rcn){
										s+='<div class="checkbox codecheck" code="'+rci+'"><label><input name="price_codes_'+ri+'[]" value="'+rci+'" type="checkbox"';
										if(rcn.check!=undefined&&rcn.check==1){
											s+=' checked ';
										}
										s+='> '+rcn.name+'</label></div> '
									});
									s+='</div>';
								}
								s+='</div>';
							});
						}
						s+='</td></tr>';
					});
					$('#coupons_table').append(s);
					ajaxevent_bind();
					page_set.first_load=false;
					$('.pulltips').hide();
				}else{
					$('.pulltips').html(data.message);
				}
			},'json');		
		}

	})
})
</script>
</body>
</html>
