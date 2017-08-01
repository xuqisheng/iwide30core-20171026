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
        发放规则
        <small>Give Rules</small>
      </h1>
      <!--ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li><a href="#">酒店订房</a></li>
        <li><a href="#">优惠券配置</a></li>
        <li class="active">发放规则</li>
      </ol-->
    </section>

    <form role="form" method='post' id='form1' action='<?php echo site_url('hotel/coupons/gr_save');?>'>
    <input type='hidden' name='<?php echo $csrf_token;?>' value='<?php echo $csrf_value;?>' />
    <input type='hidden' name='rule_id' value='<?php if(!empty($list['rule_id']))echo $list['rule_id'];?>' />
    <!-- Main content -->
    <section class="content">
      <div class="row">
        
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-body">
                <div class="form-group col-xs-12"><!--  has-error错误  has-success正确 -->
                  <label>规则名称</label>
                  <input type="text" class="form-control" name="rule_name" value="<?php if(isset($list['rule_name']) && !empty($list['rule_name']))echo $list['rule_name'];?>" placeholder="请输入规则名称">
                </div>
                <div class="form-group col-xs-12">
                  <label>发放内容</label>
                    <?php foreach($coupons as $arr){ ?>
                        <div class="checkbox"><label><input type="checkbox" name="couponIds[]"  <?php if (!empty($list['coupon_ids']) && in_array($arr['card_id'], $list['coupon_ids'])){?>checked='checked'<?php }?> value="<?php echo $arr['card_id'];?>"><?php echo $arr['title'];?></label></div>
                    <?php }?>
                </div>
            </div>
          </div>
        </div>       
        
         
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">发放条件</h3>
            </div>
            <div class="box-body">
                <div class="form-group col-xs-4">
                  <label>按订单状态</label>
                  <div class="radio"><label><input type="radio" name="rule_type" value="left"  <?php if ((!empty($list['rule_type'])&&$list['rule_type']=='left')||empty( $list['rule_id'])){?>checked='checked'<?php }?>>订单离店后</label></div>
                  <div class="radio"><label><input type="radio" name="rule_type" value="ensure" <?php if (!empty($list['rule_type'])&&$list['rule_type']=='ensure'){?>checked='checked'<?php }?>>订单确认后</label></div>
                  <div class="radio"><label><input type="radio" name="rule_type" value="in" <?php if (!empty($list['rule_type'])&&$list['rule_type']=='in'){?>checked='checked'<?php }?>>订单入住后</label></div>
                  <div class="radio"><label><input type="radio" name="rule_type" value="hotel_cancel" <?php if (!empty($list['rule_type'])&&$list['rule_type']=='hotel_cancel'){?>checked='checked'<?php }?>>酒店取消后</label></div>
                  <div class="radio"><label><input type="radio" name="rule_type" value="custom_cancel" <?php if (!empty($list['rule_type'])&&$list['rule_type']=='custom_cancel'){?>checked='checked'<?php }?>>用户取消后</label></div>
                </div>
                <div class="form-group col-xs-4">
                  <label>按金额和次数(选填)</label>
                  <div class="checkbox">
                  	<label><input type="checkbox" name="byAmount" value="byAmount" <?php if (!empty($list['extra_rule']['min_amount'])&&$list['extra_rule']['min_amount']!=0){?>checked='checked'<?php }?>> 消费满</label>
					<label style="padding:0 5px;width:90px"><input type="text" name="orderCost"  <?php if (!empty($list['extra_rule']['min_amount'])&&$list['extra_rule']['min_amount']!=0){?>value=<?php echo $list['extra_rule']['min_amount'];}?>  class="form-control input-sm" placeholder="0.00"></label>后
                  </div>
                  <div class="checkbox">
                  	<label><input type="checkbox" name="byTimes" value="byTimes" <?php if (!empty($list['extra_rule']['order_nums'])&&$list['extra_rule']['order_nums']!=0){?>checked='checked'<?php }?>>订单达到</label>
                    <label style="padding:0 5px;width:90px"><input type="text" name="orderTimes" <?php if (!empty($list['extra_rule']['order_nums'])&&$list['extra_rule']['order_nums']!=0){?>value=<?php echo $list['extra_rule']['order_nums'];}?> class="form-control input-sm" placeholder="0"></label>后
                  </div>
                </div>  
                <div class="form-group col-xs-4">
                  <label>发放规则</label>
                  <div class="radio">
                  	<label><input type="radio" name="byOrder" value="aveOrder" <?php if (!empty($send_condition->num->order) || empty($list['rule_id'])){?>checked='checked'<?php }?>> 每个订单赠送</label>
                    <label style="padding:0 5px;width:70px"><input type="text" name="orderAmount" <?php if (!empty($send_condition->num->order)){?>value=<?php echo $send_condition->num->order;}?> class="form-control input-sm" placeholder="0"></label>张
                  </div>
                  <div class="radio part_show_radio">
                  <label><input type="radio" name="byOrder" value="aveNight" <?php if (!empty($send_condition->num->night)){?>checked='checked'<?php }?>> 每个间夜赠送</label>
                  <label style="padding:0 5px;width:70px"><input type="text" name="nightAmount" <?php if (!empty($send_condition->num->night)){?>value=<?php echo $send_condition->num->night;}?> class="form-control input-sm" placeholder="0"></label>张</div>
                </div>
            </div>
          </div>
        </div>      
        
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">其他条件(非必选)</h3>
            </div>
            <div class="box-body">
                <div class="form-group col-xs-6">
                    <label>使用门店</label><label id='hotel_tips' style='color:red;'></label>
                    <div class="radio"><label><input type="radio" name="hotel_rooms" value="all"
                                                     <?php if (empty($list['hotel_rooms'])){?>checked<?php }?>>全部门店和房型</label></div>
                    <div class="radio part_show_radio"><label><input type="radio" name="hotel_rooms" value="part"
                                                                     <?php if (!empty($list['hotel_rooms'])){?>checked<?php }?>>指定门店和房型</label></div>
                    <div class="btn btn-default btn-xs part_show add_hotel_btn" style="display:none"><i class="fa fa-plus"></i> 添加适用门店</div>
                </div>
                
                <div class="form-group col-xs-6">
                  <label>执行次数</label>
                  <div style="margin:10px 0"><label style="padding:0 5px;width:90px"><input type="text" name="times" <?php if (!empty($list['trigger_times'])){?>value=<?php echo $list['trigger_times'];}?> class="form-control input-sm" placeholder="0"></label>次</div>
                  <p class="text-light-blue">&nbsp;&nbsp;tips:每个用户可领取的上限, 0表示不限制</p>
                </div>
                <div style="clear:both"></div>
                <div class="form-group col-xs-6">
                  <label>会员等级</label>
                  <div class="radio"><label><input type="radio" name="byLevel" value="all" <?php if (empty($list['extra_rule']['level'])){?>checked='checked'<?php }?>>全部会员等级</label></div>
                  <div class="radio part_show_radio"><label><input type="radio" name="byLevel" value="" <?php if (!empty($list['extra_rule']['level'])){?>checked='checked'<?php }?>>指定会员等级</label></div>
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


                <div class="form-group col-xs-6">
                    <label>支付方式(非必选)</label>
                    <div class="radio"><label><input type="radio" name="byPay" value="all" <?php if (empty($list['extra_rule']['paytype'])){?>checked<?php }?>>全部支付方式</label></div>
                    <div class="radio part_show_radio"><label><input type="radio" name="byPay" value="part" <?php if (!empty($list['extra_rule']['paytype'])){?>checked<?php }?>>指定支付方式</label></div>
                    <div class="checkbox part_show" style="display:none">
                        <table class="table table-bordered">
                            <?php if (!empty($pay_ways)){?>
                                <tr>
                                    <?php foreach ($pay_ways as $k=>$p){?>
                                        <td><label><input type="checkbox" name='payType[]' <?php if (!empty($list['extra_rule']['paytype'])&&in_array($p->pay_type, $list['extra_rule']['paytype'])){?>checked='checked'<?php }?> value='<?php echo $p->pay_type;?>'> <?php echo $p->pay_name;?></label></td>
                                    <?php }?>
                                </tr>
                            <?php }?>
                        </table>
                    </div>
                </div>
                <div style="clear:both"></div>

                <div class="form-group col-xs-6" style="margin-top:25px; margin-bottom:0">
                    <label>随机发放</label>
                    <div class="radio"><label><input type="radio" name="random" value="2" <?php if ((isset($list['extra_rule']['is_random'])&& $list['extra_rule']['is_random']==2))echo 'checked';?>>是</label></div>
                    <div class="radio"><label><input type="radio" name="random" value="1" <?php if ((isset($list['extra_rule']['is_random'])&& $list['extra_rule']['is_random']==1) || (!isset($list['extra_rule']['is_random'])))echo 'checked';?>>否</label></div>
                </div>

                <div class="form-group col-xs-6" style="margin-top:25px; margin-bottom:0">
                    <label>随机发放条件</label>
                    <div style="margin:10px 0">
                        执行<label style="padding:0 5px;width:90px"><input class="form-control input-sm" type="number" name="r_amounts" value="<?php if (isset($list['extra_rule']['random_amounts'])&& !empty($list['extra_rule']['random_amounts']))echo $list['extra_rule']['random_amounts'];?>"></label>次
                        剩余<?php
                            if (isset($list['extra_rule']['random_amounts']) && !empty($list['extra_rule']['random_amounts']) && ($list['extra_rule']['random_amounts']-$list['random_times'])>0){
                                echo $list['extra_rule']['random_amounts']-$list['random_times'];
                            }else{
                                echo 0;
                            } ?>次
                    </div>
                    <div style="margin:10px 0">概率<label style="padding:0 5px;width:90px"><input class="form-control input-sm" type="number" name="percentage" onkeyup="check_percent(this)" value="<?php if (isset($list['extra_rule']['random_percent'])&& !empty($list['extra_rule']['random_percent']))echo $list['extra_rule']['random_percent'];?>"></label>%</div>
                </div>

            </div>
          </div>
        </div>


        
        <div class="col-xs-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">不执行日期(按客人实际入住日期)</h3>
            </div>
            <div class="box-body">
                <div class="form-group col-xs-12">
                  <label>星期</label>
                  <div class="checkbox">
                      <table class="table table-bordered"><tr>
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
                       </tr>
                      </table>
                  </div>
                </div>
                <div class="form-group col-xs-8">
                    <label>日期</label>

                    <table class="table table-bordered range_pick">
                        <?php if (!empty($list['rule_dates']['d']['d'])){
                            foreach ($list['rule_dates']['d']['d'] as $d){
                                if(!empty($d)){
                                $tmp=explode('-', $d);?>
                            <tr>
                                <td><input type="text" name='start_range[]' value='<?php echo date('Y-m-d',strtotime($tmp[0]));?>' data-date-format="yyyy-mm-dd" class="form-control datepicker"></td>
                                <td><input type="text" name='end_range[]' value='<?php if (!empty($tmp[1])) echo date('Y-m-d',strtotime($tmp[1]));?>' data-date-format="yyyy-mm-dd" class="form-control datepicker"></td>
                                <td><div type="button" class="btn btn-danger rm"><i class="fa fa-trash-o"></i> 删除</div></td>
                            </tr>
                        <?php }}}?>
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
                    <div class="radio"><label><input type="radio" name="status" value="1" <?php if ((isset($list['status'])&& $list['status']==1) || (!isset($list['rule_id'])))echo 'checked';?>>确定激活</label></div>
                    <div class="radio"><label><input type="radio" name="status" value="2" <?php if (isset($list['status'])&& $list['status']==2)echo 'checked';?>>取消激活</label></div>
                </div>
            </div>
          </div>
        </div>   
        
       <div class="col-xs-12">
           <button type="button" onclick='sub()' class="btn btn-primary">保存</button>
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
        <div class="col-xs-6 add_hotel_content">
        
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
              	<thead><tr><th>酒店</th><th>房型</th></tr></thead>
              </table>
              <ul class="pagination col-xs-6 pull-right page_concol">
                <li class="paginate_button active"><a href="#">1</a></li></ul>
            </div>
         </div>
         
         <div class="btn btn-primary pull-right layer_success">完成</div>

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
	first_load:true
}
var save_code='';
function sub(){
    var check = true;
	var ranges=$('.range_pick tr');
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

    if(save_code!=3 && check==true){
        $('#tips').html('保存中，请稍等');
        $.post('<?php echo site_url('hotel/coupons/gr_save')?>',
                {
        			datas:JSON.stringify($('#form1').serializeArray()),
        			<?php echo $csrf_token?>:'<?php echo $csrf_value?>'
   				},
    	function(data){
            save_code=data.code;
            $('#tips').html(data.msg);
        },'json');
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
function check_percent(o){
        var value=o.value;
        var min=0;
        var max=100;
        if(parseInt(value)<min||parseInt(value)>max){
            alert('概率至少要大于0，最大不能超过100');
            o.value='';
        }
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
			$('.pulltips').show();
            $.get('<?php echo site_url('hotel/coupons/ajax_gr_hotel_rooms')?>',{
                rid:'<?php if(isset($list['rule_id'])&&!empty( $list['rule_id'])){ echo $list['rule_id'];}?>'
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
