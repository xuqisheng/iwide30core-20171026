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
<!-- <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/morris/morris.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/raphael-min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/morris/morris.min.js"></script> -->
<!-- <script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script> -->
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/highcharts.js"></script>
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
          <h1>酒店复购率统计
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
                	<?php echo form_open('',array('class'=>'form-inline','id'=>'para_form'));?>
                	<div class="form-group" >
                		<label>酒店</label><select id='hotel' name='hotel_id' class="form-control input-sm">
                		<option value=""<?php if(empty($hotel_id)):echo ' selected';endif;?>>-- 全部 --</option>
                		<?php foreach ($hotels as $key=>$val):?><option value="<?php echo $key?>"<?php if(!empty($posts['hotel_id']) && $key == $posts['hotel_id']):echo ' selected';endif;?>><?php echo $val['name']?></option><?php endforeach;?>
                		</select>
                	</div><br /><br />
                	<?php if(count($hotels)>10){?>
                	<div class="form-group">
						     <div >
						    	<input type="text" name="qhs" id="qhs" placeholder="快捷查询">
						 	  	<input type="button" onclick='quick_search()' value='查询' />
					 	  	<input type="button" onclick='go_hotel("next")' value='下一个' />
					 	  	<input type="button" onclick='go_hotel("prev")' value='上一个' />
					 	  	<span id='search_tip' style='color:red'></span>
						    </div>
                	</div><br /><br />
				    <?php }?>
                  <select id='time_type' name='time_type' class="form-control input-sm">
                    <option value="1">下单时间</option>
                    <option value="2">入住时间</option>
                    <option value="3">离店时间</option>
                  </select>
                	<div class="form-group">
                		<label>下单时间 </label>
                		<input class="form_datetime form-control input-sm" data-date-format="yyyymm" type="text" name="month_start" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="">
                		<label>至 </label>
                		<input class="form_datetime form-control input-sm" data-date-format="yyyymm" type="text" name="month_end" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="">
                	</div><br />
                	<div class="btn-group">
                		<button type="button" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                    <button id="export" type="button">导出</button>
                	</div>
                	</div>
                	</form>
                	</div>
                </div>
                <!-- 统计表 -->
                <div id="purchase-total"></div>
                <!-- end-->
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    		<tr role="row">
                          <th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="2" colspan="1" aria-label="年份: activate to sort column ascending" style="text-align: center;">年份</th>
                          <th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="2" colspan="1" aria-label="月份: activate to sort column ascending" style="text-align: center;">月份</th>
                          <th width="40%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="5" aria-label="" style="text-align: center;">
                           累计用户数
                          </th>
                          <th width="40%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="5" aria-label="" style="text-align: center;">
                          累计订单数</th>
                        </tr>
                        <tr role="row">
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="总用户数: activate to sort column ascending" style="text-align: center;">累计总用户数</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="购买二次: activate to sort column ascending" style="text-align: center;">购买二次<br>累计数量/复购率</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="购买三次: activate to sort column ascending" style="text-align: center;">购买三次<br>累计数量/复购率</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="购买五次: activate to sort column ascending" style="text-align: center;">购买五次<br>累计数量/复购率</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="购买十次: activate to sort column ascending" style="text-align: center;">购买十次<br>累计数量/复购率</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="总订单数: activate to sort column ascending" style="text-align: center;">累计总订单数</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="二次订单: activate to sort column ascending" style="text-align: center;">二次订单<br>累计数量/复购率</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="三次订单: activate to sort column ascending" style="text-align: center;">三次订单<br>累计数量/复购率</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="五次订单: activate to sort column ascending" style="text-align: center;">五次订单<br>累计数量/复购率</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="十次订单: activate to sort column ascending" style="text-align: center;">十次订单<br>累计数量/复购率</th>
                        </tr>
                    <tfoot></tfoot>
                    <tbody id="res" style="text-align: center;">
                    
                    </tbody>
                  </table>
                  
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <div class="loading" style="position:fixed; top:45%; text-align:center; z-index:9999999; width:100%;display: none;">
        <span style="padding:10px 20px; border:1px solid #e4e4e4; background:#fff;">数据正在加载..</span>
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

$(".form_datetime").datepicker({
  format: 'yyyymm', 
  startDate:new Date(2013,12,01),
  endDate:'+1',
  weekStart: 1, 
  autoclose: true,
  startView: 2, 
  maxViewMode: 1,
  minViewMode:1,
  forceParse: false, 
  language: 'zh-CN',
});
$('#set_btn_save').click(function(){
	$.post('<?php echo site_url("hotel/hotel_report/save_cofigs?ctyp=ORDERS_BY_ROOMNIGHT")?>',$("#setting_form").serialize(),function(data){
		if(data == 'success'){
			window.location.reload();
		}else{
			alert('保存失败');
		}
	});
});
$(document).ready(function() {
  $('#export').click(function(){
    var hotel_id = $('#hotel').val();
    var time_type = $('#time_type').val();
    var month_start = $("input[name='month_start']").val().replace(/-/g,"");
    var month_end = $("input[name='month_end']").val().replace(/-/g,"");
    if(!hotel_id || hotel_id=='undefined'){
      hotel_id = '';
    }
    if(!month_start || !month_end){
      alert('请选择日期');
      return false;
    }
    if(month_start>=month_end){
      alert('结束月份要大于开始月份');
      return false;
    }
    $('.loading').show();
    location.href='<?php echo site_url("hotel/hotel_report/ext_re_purchase").'?'?>'+'hotel_id='+hotel_id+'&time_type='+time_type+'&month_start='+month_start+'&month_end='+month_end;
    $('.loading').hide();
  });
$('#grid-btn-search').click(function(){
  var hotel_id = $('#hotel').val();
  var time_type = $('#time_type').val();
  var month_start = $("input[name='month_start']").val();
  var month_end = $("input[name='month_end']").val();
  if(!month_start || !month_end){
    alert('请选择日期');
    return false;
  }
  if(month_start>=month_end){
    alert('结束月份要大于开始月份');
    return false;
  }
  $('.loading').show();
  $.get('<?php echo site_url("hotel/hotel_report/ajax_get_re_purchase")?>',
    {
      'hotel_id':hotel_id,
      'time_type':time_type,
      'month_start':month_start,
      'month_end':month_end
    },function(data){
      // console.log(data);
      $('.loading').hide();
      data = JSON.parse(data);
      var categories = [];//横坐标
      var usercounts = [];//用户数
      var two = [];//二次复购率
      var three = [];//三次复购率
      var five = [];//五次复购率
      var ten = [];//十次复购率
      var html = '';//表格信息

      for(x in data){
        categories.push(data[x]['date']);
        usercounts.push(parseInt(data[x]['user_count']));
        two.push(data[x]['u2']);
        three.push(data[x]['u3']);
        five.push(data[x]['u5']);
        ten.push(data[x]['u10']);
        html +='<tr><td>'+data[x]['date'].substr(0,4)+'</td>';//年份
        html +='<td>'+data[x]['date'].substr(4,2)+'</td>';//月份
        html +='<td>'+parseInt(data[x]['user_count'])+'</td>';//用户数
        html +='<td>'+data[x]['count2']+"&nbsp;&nbsp;|&nbsp;&nbsp;"+data[x]['u2']+'%'+'</td>';
        html +='<td>'+data[x]['count3']+"&nbsp;&nbsp;|&nbsp;&nbsp;"+data[x]['u3']+'%'+'</td>';
        html +='<td>'+data[x]['count5']+"&nbsp;&nbsp;|&nbsp;&nbsp;"+data[x]['u5']+'%'+'</td>';
        html +='<td>'+data[x]['count10']+"&nbsp;&nbsp;|&nbsp;&nbsp;"+data[x]['u10']+'%'+'</td>';
        html +='<td>'+parseInt(data[x]['order_count'])+'</td>';//订单数
        html +='<td>'+data[x]['allcount2']+"&nbsp;&nbsp;|&nbsp;&nbsp;"+data[x]['o2']+'%'+'</td>';
        html +='<td>'+data[x]['allcount3']+"&nbsp;&nbsp;|&nbsp;&nbsp;"+data[x]['o3']+'%'+'</td>';
        html +='<td>'+data[x]['allcount5']+"&nbsp;&nbsp;|&nbsp;&nbsp;"+data[x]['o5']+'%'+'</td>';
        html +='<td>'+data[x]['allcount10']+"&nbsp;&nbsp;|&nbsp;&nbsp;"+data[x]['o10']+'%'+'</td></tr>';
      }
      $('#res').html(html);
      // console.log(categories);
      // console.log(usercounts);
      // console.log(two);
      // console.log(three);
      // console.log(five);
      // console.log(ten);
      $('#purchase-total').highcharts({ //订单总额
          chart: {
                      zoomType: 'xy'
                  },
                  title: {
                      text: '酒店复购率统计 '
                  },
                  subtitle: {
                      text: '用户复购率=单位时间内：购买两次及以上的用户数/有购买行为的总用户数<br>订单复购率=单位时间内：第二次及以上购买的订单个数/总订单数'
                  },
                  xAxis: [{
                      categories: categories,
                      crosshair: true
                  }],
                  yAxis: [{ // Primary yAxis
                      labels: {
                          format: '{value}人',
                          style: {
                              color: Highcharts.getOptions().colors[2]
                          }
                      },
                      allowDecimals:false,
                      title: {
                          text: '用户数',
                          style: {
                              color: Highcharts.getOptions().colors[2]
                          }
                      },
                      opposite: true
                  }, { // Secondary yAxis
                      gridLineWidth: 0,
                      title: {
                          text: '复购率',
                          style: {
                              color: Highcharts.getOptions().colors[0]
                          }
                      },
                      labels: {
                          format: '{value} %',
                          style: {
                              color: Highcharts.getOptions().colors[0]
                          }
                      }
                  }],
                  tooltip: {
                      shared: true
                  },
                  legend: {
                      layout: 'vertical',
                      align: 'left',
                      x: 80,
                      verticalAlign: 'top',
                      y: 55,
                      floating: true,
                      backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                  },
                  series: [{
                      name: '用户数',
                      type: 'column',
                      yAxis: 0,
                      data: usercounts,
                      tooltip: {
                          valueSuffix: ' 人'
                      }
                  },{
                      name: '二次复购率',
                      type: 'spline',
                      yAxis: 1,
                      data: two,
                      tooltip: {
                          valueSuffix: ' %'
                    }
                  },{
                      name: '三次复购率',
                      type: 'spline',
                      yAxis: 1,
                      data: three,
                      tooltip: {
                          valueSuffix: ' %'
                    }
                  },{
                      name: '五次复购率',
                      type: 'spline',
                      yAxis: 1,
                      data: five,
                      tooltip: {
                          valueSuffix: ' %'
                    }
                  },{
                      name: '十次复购率',
                      type: 'spline',
                      yAxis: 1,
                      data: ten,
                      tooltip: {
                          valueSuffix: ' %'
                      }
                  }]
      });

  });
});
});
</script>
</body>
</html>
