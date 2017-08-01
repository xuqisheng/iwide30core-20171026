<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<style>
	.layer{position:fixed; top:0; left:0;overflow:hidden; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; display:none}
	.add_hotel_content{background:#f8f8f8; padding:15px; height:100%; overflow-y:scroll;width:100%;}
	.child_dom{ background:#fff; border:1px solid #3c8dbc;max-width:450px; vertical-align:middle}
	.parent_dom div{display:inline-block}
	.parent_dom .checkbox{margin:2px}
	.room_none {display:none}
	#hotels_table td{vertical-align:middle}
</style>
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
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 编辑房型标签</a></li>
        </ul>

<!-- form start -->
<div class="tab-content">
       <div class="tab-pane active" id="tab1">
 <?php if (empty($labels)){?>
 			 <div class="box-body"><a style='color: blue' href='<?php echo site_url('hotel/label/index');?>'>请先添加标签</a></div>
 <?php }else{?>
			<?php echo form_open( site_url('hotel/label/room_item_save'), array('id'=>'form1','class'=>'form-horizontal','enctype'=>'multipart/form-data' )); ?>
       <div class="box-body">
		<div class="col-xs-8 add_hotel_content">
          <div class="box">
            <div class="box-body">
              <div class="pulltips" style="display: none;"><i class="fa fa-spinner"></i> 正在加载...</div>
              <div class="form-group total_code" id="label_types">
              	  <?php foreach ($types as $t){?>
                  <div class="checkbox codecheck col-xs-4" code="<?php echo $t['type_id']?>">
                  	  <label><input type="checkbox"> <?php echo $t['label_name']?></label>
                  </div>
                  <?php }?>
              </div>
            </div>
          </div>
          <div class="box">
            <div class="box-body">      
              <div class="form-group col-xs-6">
                  <input type="text" class="form-control searchtable" placeholder="搜索酒店名">
              </div>        
              <div class="pulltips" style="display: none;"><i class="fa fa-spinner"></i> 正在加载...</div>     
              <table id="hotels_table" class="table table-bordered table-striped">
              	<thead><tr><th onclick="$('.room_none').slideToggle();">酒店(展开/收起房型)</th><th>房型</th></tr></thead>
              	<tbody>
              	<?php foreach ($labels as $hotel_id=>$label){?>
              		<tr>
              			<td onclick="$('#room_list_<?php echo $hotel_id?>').slideToggle();" style='width: 45%'>
              				<div class="checkbox hotelcheck">
                  				<label style='padding-left: 0'>
                  					<a href="javascript:void(0);" style='color:#3c8dbc'><?php echo $label['name'];?></a>
                  				</label>
              				</div>
              			</td>
              			<td style='width: 45%'>
              				<a href="javascript:$('#room_list_<?php echo $hotel_id?>').slideToggle();">展开</a>
                  			<div id='room_list_<?php echo $hotel_id?>' class='room_none'>
                  				<?php foreach ($label['items'] as $room_id=>$room){?>
                  				<div class="parent_dom">
                      				<div class="checkbox roomcheck">
                      					<label style='padding-left: 0'>
                      						<span><?php echo $room['name'];?></span>
                      					</label>
                      				</div>
                          			<div class="child_dom">
                          				<?php foreach ($types as $t){?>
                          				<div class="checkbox codecheck" code="<?php echo $t['type_id']?>">
                          					<label>
                          						<input name="label_types[]" <?php if ($room['types'][$t['type_id']]['check']==1) echo 'checked';?> value="<?php echo $hotel_id.'|'.$room_id.'|'.$t['type_id']?>" type="checkbox"> <?php echo $t['label_name']?>
                          					</label>
                          				</div> 
                          				<?php }?>
                          			</div>
                          		</div>
                          		<?php }?>
                              	<a href="javascript:$('#room_list_<?php echo $hotel_id?>').slideToggle();">收起</a>
                          	</div>
                        </td>
                      </tr>
                 <?php }?>
                 </tbody>
              </table>
              <ul class="pagination col-xs-6 pull-right page_concol">
                <li class="paginate_button active"><a href="#">1</a></li></ul>
            </div>
         </div>
      </div>
        <div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-4">
                <button type="button" onclick='sub();' class="btn btn-info pull-right">保存</button>
                <label id='tips' style='color:red;'></label>
            </div>
        </div>
        <!-- /.box-footer -->
    </div>
	<?php echo form_close() ?>
                <!-- /.box-body -->
<?php }?>
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
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
var submit_tag=0;
var add_tag=0;
function quick_search() {
	var hk=$('#qhs').val();
	options=$('#hotel input');
	$('#search_tip').html('');
	if(hk){
		search_index=0;
		$.each(options,function(i,n){
			if($(n).attr('keyword').indexOf(hk)<=-1){
				search_index++;
				$(n).parent().css('display','none');
			}else{
				$(n).parent().css('display','block');
			}
		});
		if(search_index==options.length){
			$('#search_tip').html('无结果');
		}
	}else{
		$.each(options,function(i,n){
			$(n).parent().css('display','block');
		});
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
		if(!check){
			submit_tag=0;
			return false;
		}
		$.post('<?php echo site_url('hotel/label/room_item_save')?>',
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
$(function(){
	var tr_length  = $('#hotels_table tbody tr').length;
	var per_page=10;
	if(tr_length>per_page){
		var page_length= tr_length/per_page;
		for( var i=per_page; i<tr_length;i++){
			$('#hotels_table tbody tr').eq(i).hide();
		}
		for (var i=1;i<page_length;i++){
			$('.page_concol').append('<li class="paginate_button"><a href="#">'+(i+1)+'</a></li>');
		}
		$('.paginate_button').click(function(){
			var _index=$(this).index();
			$(this).addClass('active').siblings().removeClass('active');
			$('#hotels_table tbody tr').hide();
			for( var i=_index*per_page; i<(_index+1)*per_page&&i<tr_length;i++){
				$('#hotels_table tbody tr').eq(i).show();
			}
		})
	}else{
		$('.page_concol').hide();
	}
	$('input[type="checkbox"]','.total_code .codecheck').click(function(){
		var code = $(this).parents('.codecheck').attr('code');
		var bool = $(this).get(0).checked;
		$('.codecheck[code="'+code+'"]').find('input').each(function() {
            $(this).get(0).checked=bool;
        });
	})
	$('.searchtable').bind('input propertychange',function(){
		var val=$(this).val();
		if(val==''){
			if(tr_length>per_page){
				$('.page_concol').show();
				var _index=$('.paginate_button.active').index();
				$('#hotels_table tbody tr').hide();
				for( var i=_index*per_page; i<(_index+1)*per_page&&i<tr_length;i++){
					$('#hotels_table tbody tr').eq(i).show();
				}
			}else{
				$('#hotels_table tbody tr').show()
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
});
</script>
</body>
</html>
