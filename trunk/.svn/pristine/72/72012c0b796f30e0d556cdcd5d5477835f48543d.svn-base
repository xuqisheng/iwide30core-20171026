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
              <!-- 
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                   <?php echo form_open('hotel/hotels/book_date',array('method'=>'get'));?>
                  <div class="row">
					  <div class="col-xs-3">
						  <div class="form-group">
							  <label for="hotel">选择城市</label>
							  <select id="city" onchange="get_hotels(this)" name="c" class="form-control">
								  <option value="">所有城市</option>
								  <?php foreach ($city_list as $py=>$v):?>
									  <optgroup label="<?php echo $py; ?>">
										  <?php foreach($v as $t){ ?>
											  <option value="<?=$t['city']?>"<?php if($city == $t['city']):?> selected<?php endif;?>><?=$t['city']?></option>
										  <?php } ?>
									  </optgroup>
								  <?php endforeach;?>
							  </select>
						  </div>
					  </div>
                  	<div class="col-xs-3">
	                  	<div class="form-group">
						    <label for="hotel">请选择酒店</label>
						    <select id="hotel" name="h" class="form-control">
								<option value="">所有酒店</option>
						    <?php foreach ($hotel_select as $hotel):?><option value="<?=$hotel['hotel_id']?>"<?php if($hotel_id == $hotel['hotel_id']):?> selected<?php endif;?>><?=$hotel['name']?></option><?php endforeach;?>
						    </select>
						    <?php if(count($hotel_select)>10){?>
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
             <?php endforeach; ?>
             </tr></thead>
                    <?php if(!empty($list)){ foreach($list as $lt){ ?>
                    <tr>
                    <?php foreach ($fields_config as $k=> $v):?>
             <td><?php if(!empty($v['enable'])){ ?>
             	<input name='<?php echo $k?>' value='<?php echo $lt[$k]; ?>' />
             <?php }else if(!empty($v['select'])){ ?>
				<select  name='<?php echo $k; ?>'>
				<?php foreach($v['select'] as $vk=>$vs) { ?>
				<option value='<?php echo $vk; ?>' <?php if($vk==$lt[$k]){ echo 'selected';} ?>><?php echo $vs; ?></option>
				<?php }?>
				</select>
				<?php }elseif(!empty($v['array'])){ ?>
				<?php if(!empty($lt[$k])){ ?>
				<?php foreach($v['array'] as $kt=>$tt){ ?>
				<?php if(!empty($tt['enable'])){ ?>
				<input name='<?php echo $kt; ?>' value='<?php echo $lt[$k][$kt]; ?>' />
				<?php }elseif(!empty($tt['hidden'])){ ?>
				<input type="hidden" name='<?php echo $kt; ?>' value='<?php echo $lt[$k][$kt]; ?>' />
				<?php }else if(!empty($tt['select'])){ ?>
				<select  name='<?php echo $kt; ?>'>
				<?php foreach($tt['select'] as $vk=>$vs) { ?>
				<option value='<?php echo $vk; ?>' <?php if($vk==$lt[$k][$kt]){ echo 'selected';} ?>><?php echo $vs; ?></option>
				<?php }?>
				</select>
				<?php } else { echo $lt[$k][$kt];}?>
				 <?php echo !empty($tt['label'])?$tt['label']:''; ?>
				<?php }; ?>
									<?php } ?>
			 <?php } else { echo $lt[$k];}?></td>
             <?php endforeach; ?>
             <td><a href="javascript:void(0);" onclick="quick_save(this,'<?php echo $lt['hotel_id'];?>');">快捷保存</a></td>
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
<?php if(!empty($hotel_id)){?>
hotel_id=<?php echo $hotel_id;?>;
<?php }?>

<?php if(!empty($city)){?>
fill_hotel('<?php echo $city;?>');
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
function get_hotels(obj){
	var city = $(obj).val();
	fill_hotel(city);
}
function fill_hotel(city){
	var _html = '<option value="0">--所有酒店--</option>';
	$('#hotel').html(_html);
	$.getJSON('<?php echo site_url('hotel/hotels/ajax_hotel')?>',{'c':city},function(datas){
		$.each(datas,function(k,v){
			_html += '<option value="' + v.hotel_id +'" ';
			if(v.hotel_id==hotel_id)
				_html+=' selected';
			_html+= '>' + v.name+ '</option>';
		});
		$('#hotel').html(_html);
	},'json');
}
function quick_save(obj,hotel){
	$(obj).html('快捷保存');
	var _parent=$(obj).parent().parent();
	var query=_parent.find('input,select').serialize();
	query += (query!=''?'&':'')+'hotel_id='+hotel;
	$.getJSON('<?php echo site_url('hotel/hotels/quick_save_set')?>',query,function(data){
		if(data.status==1){
			$(obj).html('快捷保存' + '<span style="color:red">修改成功</span>');
			if(data.conf_id){
				_parent.find('input[name="id"]').val(data.conf_id);
			}
		}else{
			$(obj).html('快捷保存' + '<span style="color:red">修改失败</span>');
		}
	},'json');
}
</script>
</body>
</html>
