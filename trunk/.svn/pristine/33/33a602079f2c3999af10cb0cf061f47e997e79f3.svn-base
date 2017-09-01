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
                <?php echo $this->session->show_put_msg(); ?>
                <div class="box-body">
                   <?php echo form_open('hotel/comment/index',array('method'=>'get'));?>
                  <div class="row">
                  	<div class="col-xs-3">
	                  	<div class="form-group">
						    <label for="hotel">请选择酒店</label>
						    <select id="hotel" name="hotel" class="form-control">
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
                  	
                  </div>
                 	<div class="col-xs-3">
	                  	<input type="submit" value="检索" class="btn btn-default" />
	                </div>
                  <?php echo form_close();?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
		
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
             <?php endforeach; ?><th>当前状态</th>
             </tr></thead>
                    <?php if(!empty($list)){ foreach($list as $lt){ ?>
                    <tr>
                    <?php foreach ($fields_config as $k=> $v):?>
             <td><?php echo $lt[$k];?></td>
             <?php endforeach; ?>
             <td><input type="button" status='<?php echo $lt['status'];?>' comment_id="<?php echo $lt['comment_id'];?>" onclick='switch_s(this)' value="<?php echo $lt['disp_status'];?>" class="btn btn-default" /></td>
             </tr>
                    <?php }}?>
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
<script>
var hotel_id=0;
var status_array={1:'显示',2:'隐藏'}
<?php if(!empty($hotel_id)){?>
hotel_id='<?php echo $hotel_id;?>';
<?php }?>
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
		}
	}
}
function switch_s(obj){
	if($(obj).val()!='修改中'){
		$(obj).val('修改中');
		comment_id=$(obj).attr('comment_id');
		status=$(obj).attr('status');
		$.getJSON('<?php echo site_url('hotel/comment/change_comment_status')?>',{'hid':hotel_id,'comment_id':comment_id,'status':status},function(data){
			if(data>0){
				$(obj).val(status_array[data]);
				$(obj).attr('status',data);
			}
		});
	}
}
</script>
</body>
</html>
