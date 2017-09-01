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
<!--        <section class="content-header">
          <h1><?php /*echo $breadcrumb_array['action']; */?>
            <small><?php /*if(!empty($hotels)){*/?>
            <select onchange='h_jump($(this).val())' name='hotel' id='hotel'>
            	<option value=''>--- 全部酒店 ---</option>
                <?php /*foreach ($hotels as $h){*/?>
                <option value='<?php /*echo $h['hotel_id'];*/?>' <?php /*if($hotel_id==$h['hotel_id']){*/?>selected<?php /*}*/?>><?php /*echo $h['name'];*/?></option>
                <?php /*}*/?>
            </select>
            <?php /*if(count($hotels)>10){*/?>
			     <div >
			    	<input type="text" name="qhs" id="qhs" placeholder="快捷查询">
			 	  	<input type="button" onclick='quick_search()' value='查询' />
			 	  	<input type="button" onclick='go_hotel("next")' value='下一个' />
			 	  	<input type="button" onclick='go_hotel("prev")' value='上一个' />
			 	  	<input type="button" onclick='h_jump(0)' value='确定搜索' />
			 	  	<span id='search_tip' style='color:red'></span>
			    </div>
			    <?php /*}*/?>
            <?php /*}*/?></small>
          </h1>
          <ol class="breadcrumb"><?php /*echo $breadcrumb_html; */?></ol>
        </section>-->
          <section class="content-header">
              <form name="searchForm" action='' method="get">
                <input name="searchAll"><input style="margin-left:10px" type="submit" value="搜索">
              </form>
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
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead><tr><?php 
//print_r($fields_config);die;
	    foreach ($fields_config as $k=> $v):
		     ?><th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?> ><?php echo $v['label'];?></th><?php 
	    endforeach; ?></tr></thead>
	    
                  </table>
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>
<?php 
$sort_index= $model->field_index_in_grid($default_sort['field']);
$sort_direct= $default_sort['sort'];

$buttions= '';	//button之间不能有字符空格，用php组装输出
$buttions.= '<button type="button" class="btn btn-sm bg-green" id="grid-btn-add"><i class="fa fa-plus"></i>&nbsp;新增</button>';
$buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-edit"><i class="fa fa-edit"></i>&nbsp;编辑</button>';
$buttions.= '<button type="button" class="btn btn-sm bg-green" id="grid-btn-batch-audit" title="批量通过未审核人员">批量审核</button>';
$buttions.= '<a href="'.site_url('distribute/staffs/ext_staffs').'" class="btn btn-sm bg-green" title="导出全部">导出全部</a>';
/*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
//$buttions.= '<button type="button" class="btn btn-default" id="grid-btn-extra-0"><i class="fa fa-trash"></i>&nbsp;导出</button>';
if(isset($js_filter_btn)) $buttions.= $js_filter_btn;
?>
var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');

var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];

var dataSet= <?php echo json_encode($result['data']); ?>;
var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/*"); ?>';
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];


$(document).ready(function() {
<?php 
$num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;

//如 view/mall/gridjs.php 存在，则会覆盖 view/privilege/gridjs.php，个性化的部分请拷贝到模块内修改
if( count($result['data'])<$num){
    if( $module ) $m= $module;
    else $m= 'privilege';
    $gridjs= VIEWPATH. $tpl .DS . $m. DS. 'gridjs.php';
    if( file_exists($gridjs) )
        require_once $gridjs;
    else 
        require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs.php';
    
} else {
    if( $module ) $m= $module;
    else $m= 'privilege';
    $gridjs= VIEWPATH. $tpl .DS . $m. DS.'staffs'.DS. 'gridjs_ajax.php';
    if( file_exists($gridjs) )
        require_once $gridjs;
    else 
        require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_ajax.php';
}
?>

<?php if(isset($js_filter)) echo $js_filter; ?>
	$('#grid-btn-batch-audit').click(function(){
		$.getJSON('<?php echo site_url('distribute/staffs/batch_auth')?>',function(data){
			if(data.errmsg == 'ok'){
				alert('操作成功，已通过'+data.success+'人');
				window.location.reload();
			}else{
				alert('操作失败');
			}
		});
	});
});
//@author lGh 增加酒店筛选 2016-3-30 10:35:39
var search_index=0;
var hid='';
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
					hid=n.value;
					//h_jump(n.value);
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
			hid=option.value;
			//h_jump(option.value);
		}
	}
}
function h_jump(h){
	if(h==0){
		h=hid;
	}
	location.href="?h="+h;
}
</script>
</body>
</html>
