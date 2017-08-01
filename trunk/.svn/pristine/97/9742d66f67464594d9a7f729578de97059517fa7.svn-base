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
      <h1>编辑社群</h1>
      <ol class="breadcrumb">
      </ol>
    </section>
<form role="form" method='post' id='form1' action=''>
<input type='hidden' name='<?php echo $csrf_token;?>' value='<?php echo $csrf_value;?>' />
<input type='hidden' name='club_id' value='<?php echo $list['club_id'];?>' />
<input type='hidden' id='saler_id' name='saler_id' value='' />
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">编辑社群客</h3>
            </div>
            <div class="box-body">
                <div class="form-group col-xs-12 col-sm-11 col-md-8"><!--  has-error错误  has-success正确 -->
                  <span class="col-xs-3 text-center">名称 :</span>
                  <input type="text" id='club_name' name='club_name' class="col-xs-8" value='<?php echo $list['club_name']?>'>
                </div>
                <div class="form-group col-xs-12 col-sm-11 col-md-8"><!--  has-error错误  has-success正确 -->
                  <span class="col-xs-3 text-center">人数 :</span>
                  <input type="text" id='limited_amount' name='limited_amount' class="col-xs-2" value='<?php echo $list['limited_amount']?>'>人
                </div>
                <div class="form-group col-xs-12 col-sm-11 col-md-8">
                	<span class="col-xs-3 text-center">有效期：</span>
                    <table class="range_pick col-xs-6">
                      <tr>
                        <td><input type="text" id='start_range' name='start_range' value='<?php if (!empty($list['valid_time']))echo date('Y-m-d',strtotime($list['valid_times'][0]))?>' data-date-format="yyyy-mm-dd" class="form-control datepicker"></td><td> - </td>
                        <td><input type="text" id='end_range' name='end_range' value='<?php if (!empty($list['valid_time']))echo date('Y-m-d',strtotime($list['valid_times'][1]))?>' data-date-format="yyyy-mm-dd" class="form-control datepicker"></td>
                      </tr>
                    </table>
                </div>
                <div class="form-group col-xs-12 col-sm-11 col-md-8">
                  <span class="col-xs-3 text-center">使用门店:</span><label id='hotel_tips' style='color:red;'></label>
                  <div class="col-xs-4">
                      <div class="radios"><label><input <?php if (empty($list['hotel_id'])){?>checked<?php }?> type="radio" name="hotel_id" value="all" >全部门店</label></div>
                      <div class="radios part_show_radio"><label><input <?php if (!empty($list['hotel_id'])){?>checked<?php }?> type="radio" name="hotel_id" value="part" >指定门店</label></div>
                      <div class="btn btn-default btn-xs part_show add_hotel_btn" style="display:none;" ><i class="fa fa-plus"></i> 添加适用门店</div>
                  </div>
                </div>
                <div class="form-group col-xs-12 col-sm-11 col-md-8">
                  <span class="col-xs-3 text-center">选择价格代码 :</span>
                  <?php if (!empty($price_codes)){?>
                      <div>
                      <?php foreach ($price_codes as $price_code=>$p){?>
                                  <input type="checkbox" name="price_code[]"  <?php if(isset($list['price_code']) && in_array($price_code,$list['price_code']))echo 'checked';?> value="<?php echo $price_code;?>"><?php echo $p['price_name'];?>
                      <?php }?>
                      </div>
                  <?php }else{?>
                  <span class="col-xs-8">暂无</span>
                  <?php }?>
                </div>
                <div class="form-group col-xs-12 col-sm-11 col-md-8">
                  <span class="col-xs-3 text-center">分销员 :</span>
                  <?php if (!empty($list['id'])){?>
                  <span class="col-xs-8"><?php echo $list['id'].'-'.$list['staff_name'];?></span>
                  <?php }else {?>
                  <input type="text" id='skey' name='skey' placeholder='输入分销号或姓名' class="col-xs-4" value='' />&nbsp;<span onclick='to_search()'>查找</span>
                  <?php }?>
                </div>
                <?php if (empty($list['id'])){?>
                <div class="form-group col-xs-12 col-sm-11 col-md-8">
                <span class="col-xs-3 text-center">搜索结果(点击选择)：</span>
                <div><p id="salers"></p></div>
                </div>
                <?php }?>
                <?php if (!empty($statuses)){?>
                <div class="form-group col-xs-12 col-sm-11 col-md-8">
                  <span class="col-xs-3 text-center">状态：</span>
                  <div class="col-xs-6">
                  <?php if (is_array($statuses)){?>
	                  <select name='status' class="form col-xs-6" <?php if ($sta_edit!=1){?>disabled<?php }?>>
	                  <?php foreach ($statuses as $status=>$s){?>
	                    <option <?php if ($status==$list['status']){?>selected<?php }?> value='<?php echo $status;?>'><?php echo $s;?></option>
	                    <?php }?>
	                  </select>
	                  <?php }else{?>
	                   <span class="col-xs-6"><?php echo $statuses;?></span>
	                  <?php }?>
                  </div>
                </div>
				<?php }?>
                <div class="form-group col-xs-12 col-sm-11 col-md-8"><!--  has-error错误  has-success正确 -->
                    <span class="col-xs-3 text-center">备注 :</span>
                    <input type="text" id='remark' name='remark' class="col-xs-4" value='<?php echo $list['remark']?>'>
                </div>
            </div>
            <div class="" style="padding-bottom:3%">
               <button type="button" onclick='sub()' class="btn btn-primary " style='margin-left: 40%'>保存</button>  
               <label id='tips' style='color:red;'></label>
            </div>    
           </div> 
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
          <div class="box">
            <div class="box-body">     
              <div class="form-group col-xs-6">
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
  <!-- /.content-wrapper --><!--
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
var submit_tag=0;
var add_tag=0;
$(document).on('click','#salers span',function(){
	sid=$(this).attr('sid');
	$('#saler_id').val(sid);
	$(this).css('color','red');
	$(this).siblings().css('color','black');
})
function to_search(){
	keyword=$('#skey').val();
	if(keyword){
		$('#salers').html('搜索中');
		$.get('<?php echo base_url() ?>index.php/club/club_list/ajax_search_salers',{
			keyword:keyword
		},function(data){
			$('#salers').html('');
			$('#keyword').val('');
			if(data.status==1){
				if(data.count>0){
					tmp='';
					$.each(data.content,function(i,n){
						tmp+="<span style='margin-left:10px' sid='"+n.saler_id+"'>"+n.name+"(分销号："+n.saler_id+")</span>";
					});
					$('#salers').html(tmp);
				}
				else{
					$('#salers').html('无结果');
				}
			}else{
				$('#salers').html(data.message);
			}
		},'json');
	}else{
		$('#salers').html('请输入分销号或分销员姓名进行搜索');
	}
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
		if($('#rule_name').val()==''){
			$('#tips').html('请填写名称');
			submit_tag=0;
			return false;
		}
		if($('#limited_amount').val()==''||isNaN($('#limited_amount').val())){
			$('#tips').html('请填写正确人数');
			submit_tag=0;
			return false;
		}
		var s='';
		start=$('#start_range').val().replace(/-/g,'');
		end=$('#end_range').val().replace(/-/g,'');
		if(isNaN(start)||isNaN(end)||start==''||end==''){
			$('#tips').html('日期错误');
			submit_tag=0;
			return false;
		}
		if(end<start){
			$('#tips').html('结束日期小于开始日期');
			submit_tag=0;
			return false;
		}
		$.post('<?php echo site_url('club/club_list/save_club')?>',$('#form1').serialize(),function(data){
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

$('.add_hotel_content').click(function(e){
	e.stopPropagation();
})
$('.add_hotel').bind('click',layerclose);
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
		var bool='checked';
		$('.hotelcheck').each(function() {
			if( !$(this).find('input').get(0).checked ) 
				bool=$(this).find('input').get(0).checked;
		});
		$('input[type="checkbox"]','.allhotelcheck').get(0).checked=bool;
	})
	$('.add_hotel_content').click(function(e){
		e.stopPropagation();
	})
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
	$('.add_hotel_btn').click(function(){
		$('.add_hotel').stop().fadeIn();
		$('body').css('overflow','hidden');

		if(page_set.first_load)	{
			$.get('<?php echo site_url('club/club_list/ajax_hotels')?>',{
				ids:'<?php echo $list['club_id']?>'
			},function(data){
				if(data.status==1){
					$('#coupons_table').html('<thead><tr><th>酒店</th></tr></thead><tbody>');
					s='';
					$.each(data.data,function(i,n){
						s+='<tr><td><div class="checkbox hotelcheck"><label><input name="hotel_ids[]" value="'+i+'" type="checkbox"';
						if(n.check!=undefined&&n.check==1){
							s+=' checked ';
						}
						s+='> '+n.name+'</label></div></td></tr>';
					});
					s+='</tbody>';
					$('#coupons_table').append(s);
					ajaxevent_bind();
					page_set.first_load=false;
					$('.pulltips').hide();
				}else{
					$('.pulltips').html(data.message);
				}
			},'json');	
		}
	});
	$('.radios').each(function(index, element) {
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
})
</script>
</body>
</html>
