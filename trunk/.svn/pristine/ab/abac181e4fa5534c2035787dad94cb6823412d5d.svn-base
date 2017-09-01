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

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <small></small>
          </h1>
          <ol class="breadcrumb"></ol>
        </section>
        <!-- Main content -->
				<section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body">
			 <table id="data-grid" class="table table-bordered table-striped table-condensed dataTable">
             <thead><tr>
             <?php foreach ($fields_config as $k=> $v):?>
             <th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?> >
             <?php echo $v['label'];?></th>
             <?php endforeach; ?>
             </tr></thead>
                    <?php if(!empty($list)){ foreach($list as $lt){ ?>
                    <tr>
                    <?php foreach ($fields_config as $k=> $v):?>
             <td><?php echo $lt[$k];?></td>
             <?php endforeach; ?>
             <td><a href="<?php echo site_url('hotel/room_no/number_edit').'?nid='.$lt['num_id'];?>">修改</a></td>
             </tr>
                    <?php }}?>
                  </table>
                  </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
				  	<div class="col-xs-3">
	                  	<a href="<?php echo site_url('hotel/room_no/number_edit'); ?>" class="btn btn-default" >增加</a>
	                </div>
		
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
<?php if(!empty($room_id)){?>
room_id=<?php echo $room_id;?>;
<?php }?>

<?php if(!empty($hotel_id)){?>
fill_rooms('<?php echo $hotel_id;?>');
<?php }?>
function get_rooms(obj){
	var hotel_id = $(obj).val();
	fill_rooms(hotel_id);
}
function fill_rooms(hotel_id){
	var _html = '<option value="0">--选择房型--</option>';
	$('#room_id').html(_html);
	$.getJSON('<?php echo site_url('hotel/prices/room_types')?>',{'hid':hotel_id},function(datas){
		$.each(datas,function(k,v){
			_html += '<option value="' + v.room_id +'" ';
			if(v.room_id==room_id)
				_html+=' selected';
			_html+= '>' + v.name+ '</option>';
		});
		$('#room_id').html(_html);
	},'json');
}
</script>
</body>
</html>
