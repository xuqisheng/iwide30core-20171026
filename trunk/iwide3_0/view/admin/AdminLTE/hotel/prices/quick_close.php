<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">
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
<div class="over_x">
	<div class="content-wrapper" style="min-width:1050px;">
		<div class="banner bg_fff p_0_20">价格日历／一键关房</div>
		<div class="contents">
			<from>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_ff503f"></span>关房配置</div>
					<div class="con_right">
						<div class="hottel_name ">
							<div class="">酒店名称</div>
							<div class="input_txt">
								<select id="hotel" onchange="get_rooms(this)" name="hotel" class="w_450">
									<?php foreach ($hotels as $hotel):?>
								   		<option value="<?=$hotel['hotel_id']?>"<?php if($hotel_id == $hotel['hotel_id']):?> selected<?php endif;?>><?=$hotel['name']?></option>
									<?php endforeach;?>
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
						<div class="hotel_star clearfix">
							<div class="float_left">房型名称</div>
							<div id='rooms' class="input_txt input_checkbox p_l_4 w_600">
							</div>
						</div>
						<div class="address">
							<div class="">条件时间</div>
							<div class="input_txt">
								<span><input name="begin" id="datepicker" class="moba" type="text" /></span>
								<font>至</font>
								<span><input name="end" id="datepicker2" class="moba" type="text" /></span>
							</div>
						</div>
					</div>
				</div>
				<div class="bg_fff" style="padding:15px;text-align:center;">
					<button class="fom_btn" onclick='close_sub(1)'>一键关房</button>
					<button class="fom_btn border_1" style="color:#000;background:#fff;" onclick='close_sub(2)'>取消关房</button>
					<span id='tips' style='color:red'></span>
				</div>
			</from>
		</div>
	</div>
</div>

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
</body>
</html>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/layDate.js"></script>
<script>
;!function(){
  laydate({
     elem: '#datepicker',
     format: 'YYYYMMDD'
  })
  laydate({
     elem: '#datepicker2',
     format: 'YYYYMMDD',
  })
}();
</script>
<script type="text/javascript">
var room_id=0;
<?php if(!empty($room_id)){?>
room_id=<?php echo $room_id;?>;
<?php }?>

<?php if(!empty($hotel_id)){?>
fill_rooms('<?php echo $hotel_id;?>');
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
						$(":input[name='check_all']").prop('checked',false);
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
				$(":input[name='check_all']").prop('checked',false);
			}
		}
	}
	function get_rooms(obj){
		var hotel_id = $(obj).val();
		fill_rooms(hotel_id);
	}
	function fill_rooms(hotel_id){
		var _html = '';
		$('#rooms').html(_html);
		$.getJSON('<?php echo site_url('hotel/prices/room_types')?>',{'hid':hotel_id},function(datas){
			$.each(datas,function(k,v){
				_html += "<div><input type='checkbox' id='"+v.room_id+"' name='room_ids' value='"+v.room_id+"'/><label for='"+v.room_id+"'>"+v.name+'</label></div>';
			});
			$('#rooms').html(_html);
		},'json');
	}
	function close_sub(type){
		$('#tips').html('修改中');
		var rooms=$('#rooms').find('[name="room_ids"]');
		ids='';
		$.each(rooms,function(i,n){
			if($(n).is(":checked")==true){
				ids=ids+','+n.value;
			}
		});
		ids=ids.substring(1);
		begin=$('#datepicker').val();
		end=$('#datepicker2').val();
		if(!ids||!begin||!end){
			 $('#tips').html('请选择数据');
			 return;
		}
		if(begin>end||(begin<<?php echo $today;?>)){
			 $('#tips').html('日期错误');
			 return;
		}
			$.getJSON('<?php echo site_url('hotel/prices/quick_close_set')?>',{
					'hid':$('#hotel').val(),
					'room':ids,
					'start':begin,
					'end':end,
					'type':type
				},function(data){
					if(data==1)
						$('#tips').html('修改成功');
					else
						$('#tips').html('修改失败');
				},'text');
	}
</script>