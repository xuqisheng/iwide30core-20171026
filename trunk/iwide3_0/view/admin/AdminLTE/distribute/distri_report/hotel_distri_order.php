<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="modal fade" id="setModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">显示设置</h4>
      </div>
      <div class="modal-body">
        <div id='cfg_items'>
        <?php echo form_open('distribute/distri_report/save_cofigs','id="setting_form"')?>
        	
        </form></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="set_btn_save">保存</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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
          <h1>酒店分销子订单数据
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
                <div class="row">
                	<div class="col-sm-12">
                	<?php echo form_open('distribute/distri_report/hotel_distri_order/',array('class'=>'form-inline','id'=>'para_form'));?>
                	<div class="form-group" >
                		<label>订单酒店</label><select id='hotel' name='hotel_id' class="form-control input-sm">
                		<option value=""<?php if(empty($hotel_id)):echo ' selected';endif;?>>-- 全部 --</option>
                		<?php foreach ($hotels as $key=>$val):?><option value="<?php echo $key?>"<?php if(!empty($posts['hotel_id']) && $key == $posts['hotel_id']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?>
                		</select>
                	</div>
                	<?php if(count($hotels)>10){?>
                	<br />
                	<div class="form-group">
						     <div >
						    	<input type="text" name="qhs" id="qhs" placeholder="快捷查询">
						 	  	<input type="button" onclick='quick_search()' value='查询' />
					 	  	<input type="button" onclick='go_hotel("next")' value='下一个' />
					 	  	<input type="button" onclick='go_hotel("prev")' value='上一个' />
					 	  	<span id='search_tip' style='color:red'></span>
						    </div>
                	</div>
				    <?php }?><br />
                	<div class="form-group" >
                		<label>订单状态</label><select id='order_status' name='order_status' class="form-control input-sm">
                		<option value="">-- 全部 --</option>
                		<option value="book" <?php if ($posts['order_status']=='book') echo 'selected';?>>预订中</option>
                		<option value="left" <?php if ($posts['order_status']=='left') echo 'selected';?>>已离店</option>
                		</select>
                	</div>
                	<div class="form-group" >
                		<label>微信订单号</label>
                		<input type='text' id='orderid' name='orderid' value='<?php if (!empty($posts['orderid'])) echo $posts['orderid'];?>' />
                	</div>
                	<div class="form-group" >
                		<label>PMS订单号</label>
                		<input type='text' id='web_orderid' name='web_orderid' value='<?php if (!empty($posts['web_orderid'])) echo $posts['web_orderid'];?>' />
                	</div><br /><div class="form-group">
                		<label>下单时间 </label>
                		<input class="form_datetime form-control input-sm" data-date-format="yyyymmdd" type="text" name="order_time_start" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['order_time_start']) ? '' : $posts['order_time_start']?>">00:00
                		<label>至 </label>
                		<input class="form_datetime form-control input-sm" data-date-format="yyyymmdd" type="text" name="order_time_end" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['order_time_end']) ? '' : $posts['order_time_end']?>">23:59
                	</div><br />
                	<div class="form-group">
                		<label>入住日期 </label>
                		<input class="form_datetime form-control input-sm" data-date-format="yyyymmdd" type="text" name="start_date_start" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['start_date_start']) ? '' : $posts['start_date_start']?>">
                		<label>至 </label>
                		<input class="form_datetime form-control input-sm" data-date-format="yyyymmdd" type="text" name="start_date_end" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['start_date_end']) ? '' : $posts['start_date_end']?>">
                	</div>
                	<div class="form-group">
                		<label>离店日期 </label>
                		<input class="form_datetime form-control input-sm" data-date-format="yyyymmdd" type="text" name="end_date_start" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['end_date_start']) ? '' : $posts['end_date_start']?>">
                		<label>至 </label>
                		<input class="form_datetime form-control input-sm" data-date-format="yyyymmdd" type="text" name="end_date_end" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['end_date_end']) ? '' : $posts['end_date_end']?>">
                	</div>
                	<div class="btn-group">
                		<button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                	</div>
                	<div class="btn-group">
                		<button type="button" class="btn btn-sm bg-green" id="grid-btn-set" data-toggle="modal" data-target="#setModal" ><i class="fa"></i>&nbsp;设置</button>
                	</div>
                	<div class="btn-group">
                		<a class="btn btn-sm bg-green" href="javascript:location.href='<?php echo site_url("distribute/distri_report/ext_hotel_distri_order").'?'?>'+$('#para_form').serialize();">导出</a>
                	</div>
                	</div>
                	</form>
                	</div>
                </div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    		<tr role="row">
                  <?php $index = 0;
		$fields = array();
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
?>             <th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="<?php echo $item['name'];?>: activate to sort column ascending"><?php echo $item['name'];?></th>
				<?php $index ++;
				$fields[] = $key;
			}
		}?>
                    </tr>
                    <tfoot></tfoot>
                    <tbody>
                    <?php
                    foreach ( $res as $item ) { ?>
	                    <tr>
		                    <?php foreach ($confs as $data_type=>$i){?>
		                    	<?php if ($i['must'] == 1||$i['choose'] == 1){?>
		                    		<td><?php if (isset($item[$data_type])) echo $item[$data_type];?></td>
	                    		<?php }?>
	                    	<?php }?>
	                    </tr> 
                    <?php } ?>
                    </tbody>
                  </table>
                  
                  <div class="row">
	                  <div class="col-sm-5">
	                  	<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条</div>
	                  </div>
	                  <div class="col-sm-7">
	                  	<div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
	                  		<ul class="pagination"><?php echo $pagination?></ul>
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>

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
		}
	}
}

var buttons = $('<div class="btn-group"></div>');

var grid_sort= [[ , "" ]];

<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];

$(".form_datetime").datepicker({format: 'yyyymmdd'});
$('#grid-btn-set').click(function(){
$('#setModal').on('show.bs.modal', function (event) {
// 	  modal.find('.modal-body input').val(recipient)
	var str = '<input type="hidden" name="<?php echo $this->security->get_csrf_token_name ();?>" value="<?php echo $this->security->get_csrf_hash ();?>" style="display:none;">';
	$.getJSON('<?php echo site_url("distribute/distri_report/get_cofigs?ctyp=HOTEL_DISTRI_ORDER")?>',function(data){
		$.each(data,function(k,v){
			str += '<div class="checkbox"><label><input type="checkbox" name="' + k + '"';
			if(v.must == 1){
				str += ' disabled checked ';
			}else if(v.choose == 1){
				str += ' checked ';
			}
			str += '>' + v.name + '</label></div>';
		});
		$('#setting_form').html(str);
	});

})});
$('#set_btn_save').click(function(){
	$.post('<?php echo site_url("distribute/distri_report/save_cofigs?ctyp=HOTEL_DISTRI_ORDER")?>',$("#setting_form").serialize(),function(data){
		if(data == 'success'){
			window.location.reload();
		}else{
			alert('保存失败');
		}
	});
});
$(document).ready(function() {
<?php 
// $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
// if( count($result['data'])<$num) 
// 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs.php';
// else 
// 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_ajax.php';
?>
});
</script>
</body>
</html>
