<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<style>
	.layer{position:fixed; top:0; left:0;overflow:hidden; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; display:none}
	.add_hotel_content{background:#f8f8f8; padding:15px; height:100%; float:right; overflow-y:scroll;width:850px;}
	.child_dom{ padding:3px 5px; margin:5px;background:#fff; border:1px solid #3c8dbc;max-width:450px; vertical-align:middle}
	.parent_dom div{display:inline-block}
	.parent_dom .checkbox{margin:2px}
	#coupons_table td{vertical-align:middle}
</style>
<style>
    .j_number .radio{margin-top:0px;}
</style>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
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
      <h1> 部分兑换</h1>
      <ol class="breadcrumb">
      </ol>
    </section>

<form role="form" method='post' id='form1' action='<?php echo site_url('hotel/bonus/save_puserule');?>'>
<input type='hidden' name='<?php echo $csrf_token;?>' value='<?php echo $csrf_value;?>' />
<input type='hidden' name='rule_id' value='<?php echo $list['rule_id'];?>' />
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-xs-12">
          <div class="box box-primary">
          	<style>
            .j_width{width:110px;text-align:right;float:left;}
			.j_col-xs-2{width:104px;float:left;}
			.border_1{border:1px solid #ccc;}
			.j_p_txt{color:#777;font-size:10px;margin-bottom:0px;}
            </style>
            <div class="box-header">
              <h3 class="box-title"></h3>
            </div>
            <div class="box-body">
                <div class="form-group col-xs-12">
                  <span class="j_width" style="padding-top:0.4rem;">*规则名称:</span>
                  <div  class="col-xs-8">
                    <input type="text" class="form-control border_1" name='rule_name' id='rule_name' placeholder="请输入规则名称" value='<?php echo $list['rule_name'];?>'>
                  </div>
                </div>
                <div class="form-group col-xs-12 j_number">
                  <span class="j_width">*抵扣规则:</span>
                  <div class="col-xs-8">
                      <div class="radio"><label style="padding-left:0px;"><input class="border_1" name='rate_value' 
                      value='<?php if ($list['ex_way']==1)echo $list['ex_value'];?>' type="number" min="1" style="width:80px;margin-right:5px;text-indent:3px;" />元等于</label>1积分</div>
                  </div>
                </div>
                <div class="form-group col-xs-12 j_number">
                  <span class="j_width">*抵扣条件:</span>
                  <div class="col-xs-8">
                      <div class="radio"><label style="padding-left:0px;"><input type="checkbox" name='min_price' style="margin-right:5px;" value="1" 
                      <?php if (isset($list['extra_condition']['min_price']))echo 'checked'?>>订单满</label><input class="border_1" value='<?php if (isset($list['extra_condition']['min_price']))echo $list['extra_condition']['min_price'];?>' name='min_price_v' type="number" style="width:80px;margin:0 5px;text-indent:3px;" min="1" />元可用</div>
                      
                      <div class="radio"><label style="padding-left:0px;"><input type="checkbox" name='min_rn' style="margin-right:5px;" value="1"
                      <?php if (isset($list['extra_condition']['min_rn']))echo 'checked'?>>间夜满</label><input class="border_1" value='<?php if (isset($list['extra_condition']['min_rn']))echo $list['extra_condition']['min_rn'];?>' name='min_rn_v' type="number" style="width:80px;margin:0 5px;text-indent:3px;" min="1" />可用</div>
                      
                      <div class="radio"><label style="padding-left:0px;"><input type="checkbox" name='min_haven' style="margin-right:5px;" value="1"
                      <?php if (isset($list['extra_condition']['min_haven']))echo 'checked'?>>最低需要</label><input class="border_1" value='<?php if (isset($list['extra_condition']['min_haven']))echo $list['extra_condition']['min_haven'];?>' name='min_haven_v' type="number" style="width:80px;margin:0 5px;text-indent:3px;" min="1" />积分才可兑换</div>
                      
                      <div class="radio"><label style="padding-left:0px;"><input type="checkbox" name='max_use' style="margin-right:5px;" value="1"
                      <?php if (isset($list['extra_condition']['max_use']))echo 'checked'?>>最高可用</label><input class="border_1" value='<?php if (isset($list['extra_condition']['max_use']))echo $list['extra_condition']['max_use'];?>' name='max_use_v' type="number" style="width:80px;margin:0 5px;text-indent:3px;" min="1" />积分</div>
                      
                      <div class="radio"><label style="padding-left:0px;"><input type="checkbox" name='use_rate' style="margin-right:5px;" value="1"
                      <?php if (isset($list['extra_condition']['use_rate']))echo 'checked'?>>按</label><input class="border_1" value='<?php if (isset($list['extra_condition']['use_rate']))echo $list['extra_condition']['use_rate'];?>' name='use_rate_v' type="number" style="width:80px;margin:0 5px;text-indent:3px;" min="1" />积分的倍数起抵扣</div>
                  </div>
                </div>
            </div>
          </div>
        </div>    
        
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">其他条件（非必选）</h3>
            </div>
            <div class="box-body">
                <div class="form-group col-xs-12 j_number">
                  <span class="j_width">会员等级:</span>
                  <div class="col-xs-8">
                      <div class="radio"><label><input type="radio" name="member_level" value="all"  <?php if (empty($list['extra_condition']['level'])){?>checked<?php }?>>全部会员等级</label></div>
                      <div class="radio part_show_radio"><label><input type="radio" name="member_level" value="part" <?php if (!empty($list['extra_condition']['level'])){?>checked<?php }?>>指定会员等级</label></div>
                      <div class="checkbox part_show" style="display:none;">
                          <table class="table table-bordered">
                          <?php if (!empty($member_levels)){?>
                              <tbody>
                                <tr>
                                <?php foreach ($member_levels as $level=>$name){?>
                                    <td><label><input type="checkbox" name='levels[]' <?php if (!empty($list['extra_condition']['level'])&&in_array($level, $list['extra_condition']['level'])){?>checked='checked'<?php }?> value='<?php echo $level;?>'> <?php echo $name;?></label></td>
                                    <?php }?>
                                </tr>
                              </tbody>
                              <?php }?>
                          </table>
                      </div>
                  </div>
                </div>
                <div class="form-group col-xs-12 j_number">
                  <span class="j_width">使用门店:</span>
                  <div class="col-xs-8">
                      <div class="radio"><label><input type="radio" name="hotel_rooms" value="all" 
                      <?php if (empty($list['hotel_rooms'])){?>checked<?php }?>>全部门店和房型</label></div>
                      <div class="radio part_show_radio"><label><input type="radio" name="hotel_rooms" value="part"  
                      <?php if (!empty($list['hotel_rooms'])){?>checked<?php }?>>指定门店和房型</label></div>
                      <div class="btn btn-default btn-xs part_show add_hotel_btn" style="display:none"><i class="fa fa-plus"></i> 添加适用门店</div>
                  </div>
                </div>
                <div class="form-group col-xs-12 j_number">
                  <span class="j_width">支付方式:</span>
                  <div class="col-xs-8">
                      <div class="radio"><label><input type="radio" name="paytype" value="all" <?php if (empty($list['extra_condition']['paytype'])){?>checked<?php }?>>全部支付方式</label></div>
                      <div class="radio part_show_radio"><label><input type="radio" name="paytype" value="part" <?php if (!empty($list['extra_condition']['paytype'])){?>checked<?php }?>>指定支付方式</label></div>
                      <div class="checkbox part_show" style="display: none;">
                          <table class="table table-bordered">
                          <?php if (!empty($pay_ways)){?>
                              <tbody>
                                  <tr>
                                   <?php foreach ($pay_ways as $k=>$p){?>
                                    <td><label><input type="checkbox" name='pay_ways[]' <?php if (!empty($list['extra_condition']['paytype'])&&in_array($p->pay_type, $list['extra_condition']['paytype'])){?>checked='checked'<?php }?> value='<?php echo $p->pay_type;?>'> <?php echo $p->pay_name;?></label></td>
                                    <?php }?>
                                  </tr>
                              </tbody>
                               <?php }?>
                          </table>
                      </div>
                  </div>
                </div>
                <div class="form-group col-xs-12">
                  <span class="j_width"  style="padding-top:0.4rem;">执行日期<br>(不填为不限)</span>
                  <div  class="col-xs-8 range_pick">
                      <p class="j_col-xs-2"><input placeholder="开始日期" name="start_time" id="start_time" 
                      value='<?php if (!empty($list['start_time']))echo date('Y-m-d',$list['start_time']);?>' data-date-format="yyyy-mm-dd" class="form-control date"></p>
                      <p class="col-xs-1" style="width:auto;padding:0.4rem 2px 0;">&nbsp;至&nbsp;</p>
                      <p class="j_col-xs-2"><input placeholder="结束日期" name="end_time" id="end_time" 
                      value='<?php if (!empty($list['end_time']))echo date('Y-m-d',$list['end_time']);?>' data-date-format="yyyy-mm-dd" class="form-control date"></p>
                  </div>
                </div>
            </div>
          </div>
        </div>      
        
        
        <div class="col-xs-12">
          <div class="box box-warning">
            <div class="box-body">
                <div class="box-header">
                  <h3 class="box-title"></h3>
                </div>
                 <div class="form-group col-xs-12">
                  <span class="j_width" style="padding-top:0.4rem;">优先级:</span>
                  <div  class="col-xs-8">
                    <input type="number" min="1" class="form-control" placeholder="请输入数字" id='priority' name='priority' value='<?php echo $list['priority'];?>'>
					  <?php if (!empty($priorities)){?><span style="color:#777;font-size:10px">(已有优先级：<?php foreach ($priorities as $rule_id=>$priority){?>
                      <?php if ($rule_id!=$list['rule_id'])echo $priority;?>
                      <?php }?>)</span><?php }?>
                        <p class="j_p_txt">1. 数字越大,优先级越高,</p>
                        <p class="j_p_txt">2. 不能设置相同的优先级</p>
                        <p class="j_p_txt">3. 规则冲突时,执行优先级高的规则</p>
                  </div>
                </div>
                <div class="form-group col-xs-12 j_number">
                  <span class="j_width">是否激活:</span>
                  <div  class="col-xs-8">
                    <div class="radio"><label><input type="radio"
                    <?php if ($list['status']==1){?>checked<?php }?> name="status" value="1">激活</label></div>
          			<div class="radio"><label><input type="radio"
          			<?php if ($list['status']==2){?>checked<?php }?> name="status" value="2" >不激活</label></div>
                  </div>
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
	var check=true;
	if(submit_tag==0){
		submit_tag=1;
		$('#tips').html('提交中');
		start=$('#start_time').val().replace(/-/g,'');
		end=$('#end_time').val().replace(/-/g,'');
		if(start!=''&&start!=undefined&&isNaN(start)){
			$('#tips').html('开始日期错误');
			check=false;
		}
		if(end!=''&&end!=undefined&&isNaN(end)){
			$('#tips').html('结束日期错误');
			check=false;
		}
		if(end!=''&&start!=''&&end<start){
			$('#tips').html('日期错误');
			check=false;
		}
		var priority=$('#priority').val();
		if(priority==''||isNaN(priority)){
			$('#tips').html('请填写正确优先级');
			check=false;
		}
		if(!check){
			submit_tag=0;
			return false;
		}
		$.post('<?php echo site_url('hotel/bonus/save_puserule')?>',
			{
				datas:JSON.stringify($('#form1').serializeArray()),
				<?php echo $csrf_token?>:'<?php echo $csrf_value?>'
			},function(data){
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
	$('.date').datepicker({
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
			$.get('<?php echo site_url('hotel/bonus/ajax_ur_hotel_rooms')?>',{
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
