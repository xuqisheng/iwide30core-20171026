<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
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

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $breadcrumb_array['action']; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <?php echo $this->session->show_put_msg(); ?>
              <!-- 
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <form action="<?php echo site_url('hotel/room_status/index')?>" method="get">
                  <div class="row">
                  	<div class="col-xs-3">
	                  	<div class="form-group">
						    <label for="hotel">请选择酒店</label>
						    <select id="hotel" name="hotel" class="form-control">
						    <?php foreach ($hotels as $hotel):?><option value="<?=$hotel['hotel_id']?>"<?php if($param['hotel_id'] == $hotel['hotel_id']):?> selected<?php endif;?>><?=$hotel['name']?></option><?php endforeach;?>
						    </select>
						     <?php if(count($hotels)>10){?>
						     <div style='margin-top: 5px;'>
						    	<input type="text" name="qhs" id="qhs" placeholder="快捷查询">
						 	  	<input type="button" onclick='quick_search()' value='查询' />
						 	  	<input type="button" onclick='go_hotel("next")' value='下一个' />
						 	  	<input type="button" onclick='go_hotel("prev")' value='上一个' />
						 	  	<span id='search_tip' style='color:red'></span>
						    </div>
						    <?php }?>
						</div>
	                </div>
                  	<div class="col-xs-3">
	                  	<div class="form-group">
						    <label for="room_id">请选择房型</label>
						    <select id="room_id" name="room_id" class="form-control">
						    <?php if(!isset($param['room_id'])){$param['room_id'] = array_keys($ris['rooms'])[0];} foreach ($ris['rooms'] as $key=>$room): ?><option value="<?php echo $key;?>"<?php if($key == $param['room_id']):?> selected<?php endif;?>><?php echo $room;?></option><?php endforeach;?>
						    </select>
						</div>
	                </div>
                  	<div class="col-xs-3">
	                  	<div class="form-group">
						    <label for="price_code">请选择价格代码</label>
						    <select id="price_code" name="price_code" class="form-control">
						    <?php if(!isset($param['price_code'])){$param['price_code'] = array_keys($ris['codes'][$param['room_id']])[0];} 
						    foreach ($ris['codes'][$param['room_id']]['codes'] as $key=>$room): ?>
						    <option value="<?php echo $key;?>"<?php if($key == $param['price_code']):?> selected<?php endif;?>><?php echo $room;?></option><?php endforeach;?>
						    </select>
						</div>
	                </div>
                  </div>
                  <div class="row">
                  	<div class="col-xs-3">
                  		<div class="form-group">
						    <label for="begin">起始日期</label>
						    <input type="text" name="begin" id="begin" data-date-format="yyyy-mm-dd" class="form-control datepicker" value="<?php if(isset($param['begin'])):echo $param['begin'];endif;?>" />
						</div>
	                </div>
                  	<div class="col-xs-3">
                  		<div class="form-group">
						    <label for="end">结束日期</label>
						    <input type="text" name="end" id="end" data-date-format="yyyy-mm-dd" class="form-control datepicker" value="<?php if(isset($param['end'])):echo $param['end'];endif;?>" />
						</div>
	                </div>
                  	<div class="col-xs-3">
	                  	<input type="submit" value="检索" class="btn btn-default">
	                </div>
                  </div>
                  </form>
                  <?php echo form_open('hotel/room_status/save_day_price','',$param);?>
                  <div class="row">
                  	<div class="col-xs-3">
	                  	价格<input type="text" name="price" id="price" class="form-control" />
	                </div>
                  	<div class="col-xs-3">
	                  	房数<input type="number" name="room_num" id="room_num" class="form-control" />
	                </div>
                  	<div class="col-xs-3">
	                  	<input type="submit" value="保存" class="btn btn-default">
	                </div>
                  </div>
                  <div class="row">
                  	<div class="col-xs-12"><label><input type="checkbox" name="check_all">全选</label></div>
                  </div>
                  <div class="row">
                  <div class="col-xs-12">
                  <?php echo $calendar?>
                  </div>
                  </div>
                  <?php echo form_close();?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
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
<script>
var base_infos = <?php echo json_encode($ris);?>;
$(document).ready(function() {
	$(":input[name='check_all']").click(function(){
		var check_all = $(":input[name='check_all']").is(':checked');
		var daybox = $('input[t="daybox"]');
		check_all?daybox.prop('checked',true):daybox.prop('checked',false);
	});
	$('input[t="daybox"]').click(function(){
		$(":input[name='check_all']").prop('checked',false);
		$(":input[rel='weektitle'][week='"+$(this).attr('week')+"']").prop('checked',false);
	});
	$(":input[rel='weektitle']").click(function(){
		var v = $(this).attr('week');
		var weekbox = $(":input[rel='weektitle'][week='"+v+"']");
			var daybox = $(':input[t="daybox"][week="'+v+'"]');
			$(this).is(':checked')?daybox.prop('checked',true):daybox.prop('checked',false);
			$(this).is(':checked')?weekbox.prop('checked',true):weekbox.prop('checked',false);
	});
	$('#hotel').change(function(){
		var hotel_id = $(this).val();
		fill_rooms(hotel_id);
	});
	$('#room_id').change(function(){
		var hotel_id = $('#hotel').val();
		var room_id  = $(this).val();
		var _html = '';
		$.each(base_infos['codes'][room_id]['codes'],function(k,v){
			_html += '<option value="' + k + '">' + v + '</option>';
		});
		$('#price_code').html(_html);
	});
	$('.datepicker').datepicker({
			language: "zh-CN"
	});
});
$('#add').keyup(function(){
	var add  = parseInt($(this).val());
	
	var vals = $(':input[rel=pri]');
	var val = 0;
	if(isNaN(add)){
		for(var i = 0;i < vals.length;i++){
			var ele = $(vals[i]);
			ele.val(ele.attr('def'));
		}
	}else{
		for(var i = 0;i < vals.length;i++){
			val = parseInt($(vals[i]).attr('def'));
			$(vals[i]).val(val + add);
		}
	}
});
var search_index=0;
function fill_rooms(hotel_id){
	var _html = '';
	$('#room_id').html(_html);
	$('#price_code').html(_html);
	$.getJSON('<?php echo site_url('hotel/room_status/room_types')?>',{'hid':hotel_id},function(datas){
		base_infos = datas;
		$.each(base_infos['rooms'],function(k,v){
			_html += '<option value="' + k + '">' + v+ '</option>';
		});
		$('#room_id').html(_html);
		_html = '';
		$.each(base_infos['codes'],function(k,v){
			$.each(v['codes'],function(vk,vv){
				_html += '<option value="' + vk + '">' + vv + '</option>';
			});
			return false;
		});
		$('#price_code').html(_html);
	});
}
function quick_search() {
	var hk=$('#qhs').val();
	if(hk){
		$('#search_tip').html('');
		options=$('#hotel option');
		search_index=0;
		$.each(options,function(i,n){
			$(n).css('color','#555');
			$(n).removeAttr('be_search');
			if(n.innerHTML.indexOf(hk)>-1){
				search_index++;
				$(n).css('color','red');
				$(n).attr('be_search',search_index);
				if(search_index==1){
					n.selected=true;
					fill_rooms(n.value);
				}
			}
		});
		if(search_index==0){
			$('#search_tip').html('无结果');
		}
	}
}; 
function go_hotel(direction){
	selected_option=$('#hotel').find('option:selected');
	selected_option=selected_option[0];
	now_index=$(selected_option).attr('be_search');
	if(now_index){
		search_index=now_index;
	}
	if(search_index){
		if(direction=='next'){
			search_index++;
		}else{
			search_index--;
		}
	}
	if(search_index){
		option=$('#hotel>option[be_search="'+search_index+'"]');
		if(option[0]!=undefined){
			option=option[0];
			option.selected=true;
			fill_rooms(option.value);
		}
	}
}
</script>
</body>
</html>
