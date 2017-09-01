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
        积分的赠送规则
        <small>Add Rules</small>
      </h1>
      <ol class="breadcrumb">
      </ol>
    </section>

<form role="form" method='post' id='form1' action='<?php echo site_url('hotel/Bonus/gr_edit_post');?>'>
<input type='hidden' name='<?php echo $csrf_token;?>' value='<?php echo $csrf_value;?>' />
<input type='hidden'  name='bonus_grules_id' value='<?php if(isset($list['bonus_grules_id'])&&!empty( $list['bonus_grules_id'])){ echo $list['bonus_grules_id'];}?>' />
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">编辑规则</h3></div>
            <div class="box-body">
                <style>
                .j_number .radio{margin-top:0px;}
				.j_width{width:110px;text-align:right;float:left;}
				.j_p_txt{color:#777;font-size:10px;margin-bottom:0px;}
				.border_1{border:1px solid #ccc;}
				.j_col-xs-2{width:104px;float:left;}
                </style>
                <div class="form-group col-xs-12"><!--  has-error错误  has-success正确 -->
                  <span class="j_width" style="padding-top:0.4rem">规则名称:</span>
                  <div class="col-xs-8" ><input style="padding:6px 12px;" type="text" name='rule_name' class="col-xs-12 border_1" placeholder="请输入规则名称" value="<?php if(isset($list['rule_name']) && !empty($list['rule_name']))echo $list['rule_name'];?>"></div>
                </div>
                <div class="form-group col-xs-12 j_number">
                  <span class="j_width">点评送积分:</span>
                  <div class="col-xs-8">
                      <div class="radio" style="padding:3px 0;">
                          <label><input type="radio" name="comment_bonus" value="" <?php if (!isset($list['comment_bonus_rule'])){?>checked='checked'<?php }?>>不设置</label>
                      </div>
                      <div class="radio" style="padding:3px 0;">
                      	<label><input type="radio" name="comment_bonus" value="all" <?php if (isset($list['comment_give_type'])&&$list['comment_give_type']=='all'){?>checked='checked'<?php }?>>全部会员等级</label>
                          点评一次获得<input class="border_1" style="width:50px;margin:0 5px;text-indent:3px;" name="all_comment_give" type="number" min="1" value="<?php if (isset($list['comment_give_amount'])&&$list['comment_give_type']=='all'){ echo $list['comment_give_amount'];}?>" />积分
                      </div>
                      <div class="radio part_show_radio"><label><input type="radio" name="comment_bonus" value="part" <?php if (isset($list['comment_give_type'])&&$list['comment_give_type']=='part'){ ?> checked='checked' <?php }?>>分别设置</label></div>
                      <div class="checkbox part_show" style="display:none;">
                          <table class="table table-bordered">
                              <tbody>
                                    <?php if(isset($levels)&&!empty($levels)){
                                         foreach($levels as $key=>$arr){  ?>
                                            <tr>
                                                <td>
                                                    <label style="width:10em;"><input type="checkbox" name="comment_levels[]" value="<?php echo $key;?>" <?php if (isset($list['comment_bonus_rule'][$key])){?>checked='checked'<?php }?>><?php echo $arr;?></label>
                                                    点评一次获得<input class="border_1" style="width:50px;margin:0 5px;text-indent:3px;" name='comment_give[<?php echo $key;?>]' type="number"  min="1" value="<?php if(isset($list['comment_bonus_rule'][$key])){ echo $list['comment_bonus_rule'][$key]->amount;}?>" />积分
                                                </td>
                                            </tr>
                                    <?php }} ?>
                              </tbody>
                          </table>
                      </div>
                  </div>
                </div>
                <div class="form-group col-xs-12 j_number">
                    <span class="j_width">消费送积分:</span>
                    <div class="col-xs-8">
                        <div class="radio" style="padding:3px 0;">
                            <label><input type="radio" name="member_level" value="" <?php if (!isset($list['give_type'])){?>checked='checked'<?php }?>>不设置</label>
                        </div>
                        <div class="radio" style="padding:3px 0;">
                            <label><input type="radio" name="member_level" value="all" <?php if (isset($list['give_type'])&&$list['give_type']=='all'){?>checked='checked'<?php }?>>全部会员等级</label>
                            消费<input class="border_1" style="width:50px;margin:0 5px;text-indent:3px;" name="allcost" type="number" min="1" value="<?php if (isset($list['give_type'])&&$list['give_type']=='all'){ echo $list['allCost'];}?>" />元，获得
                            <input class="border_1" name="allamount" style="width:50px;margin:0 5px;text-indent:3px;" type="number" min="1" value="<?php if (isset($list['give_type'])&&$list['give_type']=='all'){ echo $list['allAmount'];}?>" />分
                        </div>
                        <div class="radio part_show_radio"><label><input type="radio" name="member_level" value="part" <?php if (isset($list['give_type'])&&$list['give_type']=='part'){ ?> checked='checked' <?php }?>>分别设置</label></div>
                        <div class="checkbox part_show" style="display:none;">
                            <table class="table table-bordered">
                                <tbody>
                                <?php if(isset($levels)&&!empty($levels)){
                                    foreach($levels as $key=>$arr){  ?>
                                        <tr>
                                            <td>
                                                <label style="width:10em;"><input type="checkbox" name="levels[]" value="<?php echo $key;?>" <?php if (isset($list['bonus_rule'][$key])){?>checked='checked'<?php }?>><?php echo $arr;?></label>
                                                消费<input class="border_1" style="width:50px;margin:0 5px;text-indent:3px;" name='cost[<?php echo $key;?>]' type="number"  min="1" value="<?php if(isset($list['bonus_rule'][$key])){ echo $list['bonus_rule'][$key]->cost;}?>" />元，获得
                                                <input class="border_1" style="width:50px;margin:0 5px;text-indent:3px;" name='amount[<?php echo $key;?>]' type="number"  min="1" value="<?php if(isset($list['bonus_rule'][$key])){ echo $list['bonus_rule'][$key]->amount;}?>" />分
                                            </td>
                                        </tr>
                                    <?php }} ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group col-xs-12 j_number">
                  <span class="j_width">酒店和房型:</span><span id="hotel_tips" style="color:red;"></span>
                  <div class="col-xs-8">
                      <div class="radio"><label><input type="radio" name="hotel_rooms" value="all" <?php if (empty($list['hotels_id'])){?>checked<?php }?>>全部酒店和房型</label></div>
                      <div class="radio part_show_radio"><label><input type="radio" name="hotel_rooms" <?php if (!empty($list['hotels_id'])){?>checked<?php }?> value="part">部门酒店和房型</label></div>
                      <div class="btn btn-default btn-xs part_show add_hotel_btn" style="display: none;"><i class="fa fa-plus"></i> 添加适用门店</div>
                  </div>
                </div>
                <div class="form-group col-xs-12 j_number">
                  <span class="j_width">支付方式:</span>
                  <div class="col-xs-8">
                      <div class="radio"><label><input type="radio" name="byPay" value="all" <?php if (empty($list['paytype'])){?>checked<?php }?>>全部支付方式</label></div>
                      <div class="radio part_show_radio"><label><input type="radio" name="byPay" value="part" <?php if (!empty($list['paytype'])){?>checked<?php }?>>指定支付方式</label></div>
                      <div class="checkbox part_show" style="display:none">
                          <table class="table table-bordered">
                              <?php if (!empty($pay_ways)){?>
                                  <tr>
                                      <?php foreach ($pay_ways as $k=>$p){?>
                                          <td>
                                              <label>
                                                  <input type="checkbox" name='payType[]' <?php if (!empty($list['paytype'])&&in_array($p->pay_type, $list['paytype'])){?>checked='checked'<?php }?> value='<?php echo $p->pay_type;?>'> <?php echo $p->pay_name;?>
                                              </label>
                                          </td>
                                      <?php }?>
                                  </tr>
                              <?php }?>
                          </table>
                      </div>
                  </div>
                </div>
                <div class="form-group col-xs-12">
                  <span class="j_width"  style="padding-top:0.4rem;">执行日期:</span>
                  <div  class="col-xs-8 range_pick">
                      <p class="j_col-xs-2" style="padding:0"><input placeholder="开始日期" name="start_range" data-date-format="yyyy-mm-dd" class="form-control date" value="<?php if(isset($list['b_time']) && !empty($list['b_time']))echo $list['b_time'];?>"></p>
                      <p class="col-xs-1" style="width:auto;padding:0.4rem 2px 0;">&nbsp;至&nbsp;</p>
                      <p class="j_col-xs-2" style="padding:0"><input placeholder="结束日期" name="end_range" data-date-format="yyyy-mm-dd" class="form-control date" value="<?php if(isset($list['e_time']) && !empty($list['e_time']))echo $list['e_time'];?>"></p>
                  </div>
                </div>
                <div class="form-group col-xs-12">
                  <span class="j_width" style="padding-top:0.4rem;">优先级:</span>
                  <div  class="col-xs-8">
                    <input type="number" min="1" name='priority' class="form-control" placeholder="请输入数字" value='<?php if(isset($list['priority']) && !empty($list['priority']))echo $list['priority'];?>'>
                        <?php if (!empty($priorities)){?><span style="color:#777;font-size:10px">(已有优先级：<?php foreach ($priorities as $rule_id=>$priority){?>
                        <?php if (!isset($list['bonus_grules_id']) || $rule_id!=$list['bonus_grules_id'])echo $priority.' ';?>
                        <?php }?>)</span><?php }?>
                    <p class="j_p_txt">1. 数字越大,优先级越高,</p>
                    <p class="j_p_txt">2. 不能设置相同的优先级</p>
                    <p class="j_p_txt">3. 规则冲突时,执行优先级高的规则</p>
                  </div>
                </div>
                
                <div class="form-group col-xs-12 j_number">
                  <span class="j_width">是否激活:</span>
                  <div  class="col-xs-8">
                      <div class="radio"><label><input type="radio" name="status" value="1" <?php if ((isset($list['status'])&& $list['status']==1) || (!isset($list['bonus_grules_id'])))echo 'checked';?>>激活</label></div>
                      <div class="radio"><label><input type="radio" name="status" value="2" <?php if (isset($list['status'])&& $list['status']==2)echo 'checked';?>>取消激活</label></div>
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
var page_set={
    first_load:true,
}
$(function(){
	$('.radio').each(function(index, element) {
        $(this).get(0).addEventListener('click',function(){
			if($(this).hasClass('part_show_radio'))
				$(this).siblings('.part_show').show();
			else
				$(this).siblings('.part_show').hide();
		});
    });
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
	$('.add_hotel_btn').click(function(){
		$('.add_hotel').stop().fadeIn();
		$('body').css('overflow','hidden');
	});
	function layerclose(){
		$('.add_hotel').stop().fadeOut();
		$('body').removeAttr('style');
	}
	$('.add_hotel').bind('click',layerclose);	
	
	
	
	$('.date').datepicker({
		dateFormat: "yymmdd"
	});
	
	$('.rate_set_radio').click(function(){
		$('span',this).show();

		$(this).siblings().find('span').hide();
	})
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
});

	var submit_tag=0;
	var add_tag=0;
	function sub(){
		if(add_tag==1){
			$('#tips').html('已经添加，请勿重复添加。');
			return false;
		}
		var ranges=$('.range_pick');
		var check=true;
		if(submit_tag==0){
			submit_tag=1;
			$('#tips').html('提交中');
			$.each(ranges,function(i,n){
				var s='';
				start=$(n).find('input[name="start_range"]').val().replace(/-/g,'');
				end=$(n).find('input[name="end_range"]').val().replace(/-/g,'');
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
			$.post('<?php echo site_url('hotel/bonus/gr_edit_post')?>',
				{
					datas:JSON.stringify($('#form1').serializeArray()),
					<?php echo $csrf_token?>:'<?php echo $csrf_value?>'
				},function(data){
				if(data.code==1){
					add_tag=1;
				}
				$('#tips').html(data.msg);
				submit_tag=0;
			},'json');
		}else{
			$('#tips').html('提交中，请勿重复提交');
		}
	}

$('.add_hotel_btn').click(function(){
    $('.add_hotel').stop().fadeIn();
    $('body').css('overflow','hidden');
    if(page_set.first_load)	{
        $.get('<?php echo site_url('hotel/Bonus/ajax_gr_hotel_rooms')?>',{
            rid:'<?php if(isset($list['bonus_grules_id'])&&!empty( $list['bonus_grules_id'])){ echo $list['bonus_grules_id'];}?>'
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
function layerclose(){
    $('.add_hotel').stop().fadeOut();
    $('body').removeAttr('style');
}
</script>
</body>
</html>
