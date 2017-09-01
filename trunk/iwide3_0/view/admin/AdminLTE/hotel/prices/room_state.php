<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/css/font-awesome.min.css">
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
          <h1>
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
                <div class="box-body">
                  <form action="<?php echo site_url('hotel/prices/room_state')?>" method="get">
                  <div class="row">
                  	<div class="col-xs-3">
	                  	<div class="form-group">
						    <label for="hotel">请选择酒店</label>
						    <select id="hotel" name="hotel" onchange="get_rooms(this)" class="form-control">
						    <?php foreach ($hotels as $hotel):?><option value="<?=$hotel['hotel_id']?>"<?php if($hotel_id == $hotel['hotel_id']):?> selected<?php endif;?>><?=$hotel['name']?></option><?php endforeach;?>
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
						    <select id="room_id" name="room_id" onchange="get_codes(this)" class="form-control">
						   <?php if(!empty($room_list)){ foreach($room_list as $rl){ ?>
						   <option value='<?php echo $rl['room_id'];?>' <?php if($room_id==$rl['room_id']) echo 'selected';?>><?php echo $rl['name'];?></option>
						   <?php }}?>
						   </select>
						</div>
	                </div>
                  	<div class="col-xs-3">
	                  	<div class="form-group">
						    <label for="price_code">请选择价格代码</label>
						    <select id="price_code" name="price_code"  class="form-control">
						    <?php if(!empty($price_codes)){ foreach($price_codes as $pcs){ ?>
						   <option value='<?php echo $pcs['price_code'];?>' <?php if($price_code==$pcs['price_code']) echo 'selected';?>><?php echo $pcs['price_name'];?></option>
						   <?php }}?>
						    </select>
						</div>
	                </div>
                  </div>
                  <div class="row">
                  	<div class="col-xs-3">
                  		<div class="form-group">
						    <label for="begin">起始日期</label>
						    <input type="text" name="begin" id="begin" data-date-format="yyyy-mm-dd" class="form-control datepicker" value="<?php if(isset($startdate)):echo date('Y-m-d',strtotime($startdate));endif;?>" />
						</div>
	                </div>
                  	<div class="col-xs-3">
                  		<div class="form-group">
						    <label for="end">结束日期</label>
						    <input type="text" name="end" id="end" data-date-format="yyyy-mm-dd" class="form-control datepicker" value="<?php if(isset($enddate)):echo date('Y-m-d',strtotime($enddate));endif;?>" />
						</div>
	                </div>
                  	<div class="col-xs-3">
	                  	<input type="submit" value="检索" class="btn btn-default">
	                </div>
                  </div>
                  </form>
                  <div class="row">
                  	<div class="col-xs-12"><i class="fa fa-fw fa-calendar"></i>来自日期设定</div>
                  	<div class="col-xs-12"><i class="fa fa-fw fa-file-powerpoint-o"></i>来自价格代码</div>
                  	<div class="col-xs-12"><i class="fa fa-fw fa-hotel"></i>来自房间默认</div>
                  	<div class="col-xs-12"><i class="fa fa-fw fa-connectdevelop"></i>来自关联价格代码</div>
                  	<div class="col-xs-12"><i class="fa fa-fw fa-close"></i>来自一键关房</div>
                  </div>
                  <div class="row">
                  <div class="col-xs-12">
                  <?php if(!empty($calendar)) echo $calendar;?>
                  </div>
                  </div>
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
var room_id=0;
var hotel_id=0;
<?php if(!empty($room_id)){?>
room_id=<?php echo $room_id;?>;
<?php }?>

<?php if(!empty($hotel_id)){?>
hotel_id='<?php echo $hotel_id;?>';
<?php }?>
function get_rooms(obj){
	hotel_id = $(obj).val();
	fill_rooms(hotel_id);
}
function fill_rooms(hotel_id){
	hotel_id=hotel_id;
	var _html = '<option value="0">--选择房型--</option>';
	$('#price_code').html('<option value="0">--价格代码--</option>');
	$('#room_id').html(_html);
	$.getJSON('<?php echo site_url('hotel/prices/room_types')?>',{'hid':hotel_id},function(datas){
		$.each(datas,function(k,v){
			_html += '<option value="' + v.room_id +'" ';
			_html+= '>' + v.name+ '</option>';
		});
		$('#room_id').html(_html);
	},'json');
}
function get_codes(obj){
	var room_id = $(obj).val();
	fill_price_code(room_id);
}
function fill_price_code(room_id){
	var _html = '<option value="0">--价格代码--</option>';
	$('#price_code').html(_html);
	$.getJSON('<?php echo site_url('hotel/prices/room_price_set')?>',{'hid':hotel_id,'rid':room_id},function(datas){
		$.each(datas,function(k,v){
			_html += '<option value="' + v.price_code +'" ';
			_html+= '>' + v.price_name+ '</option>';
		});
		$('#price_code').html(_html);
	},'json');
}
var search_index=0;
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
					hotel_id=n.value;
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
			hotel_id=option.value;
			fill_rooms(option.value);
		}
	}
}
</script>
<script>
$(document).ready(function() {
	$('.datepicker').datepicker();
});
$(function(){
	$('.date-pick').datepicker({
			dateFormat: "yymmdd"
	});
});
</script>
</body>
</html>
