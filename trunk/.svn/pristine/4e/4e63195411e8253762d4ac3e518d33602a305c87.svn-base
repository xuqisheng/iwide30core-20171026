<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/report/public/DatePicker/WdatePicker.js"></script>
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
      <div class="content-wrapper" style="min-height: 973px;">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>信息列表            <small></small>
  </h1>
  <ol class="breadcrumb"></ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
	<div class="col-xs-12">
	  <div class="box">
					  <!--
		<div class="box-header">
		  <h3 class="box-title">Data Table With Full Features</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div style="">
			    <form name="form1" method="get" action=""> 条件：
                <p>
                    <div style="float: left;width:80px;text-align: center">订单号</div><input type="text" name="orderid" value="<?php echo $condition['orderid'];?>">

                  <select name="time_type">
                    <option value="">下单时间</option>
                    <option value="2">入住时间</option>
                    <option value="3">离店时间</option>
                    <option value="4">在店时间</option>
                  </select>

                  <input id="qingfeng1" name="timedown" readonly="1" type="text" value="<?php echo $condition['timedown'];?>" onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'qingfeng2\')||\'2020-10-01\'}'})" style="width:100px" />
                  <input id="qingfeng2" name="timeup" readonly="1" type="text" value="<?php echo $condition['timeup'];?>" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'qingfeng1\')}',maxDate:'2020-10-01'})" style="width:100px" />
                </p>

	            <p><div style="float: left;width:80px;text-align: center">酒店名称</div><input type="text" name="hotel_name" value="<?php echo $condition['hotel_name'];?>"></p>

	            <p><div style="float: left;width:80px;text-align: center">房型名称</div><input type="text" name="roomname" value="<?php echo $condition['roomname'];?>"></p>

                <p><div style="float: left;width:80px;text-align: center">入住人</div><input type="text" name="name" value="<?php echo $condition['name'];?>"></p>

                <p><div style="float: left;width:80px;text-align: center">手机号码</div><input type="text" name="tel" value="<?php echo $condition['tel'];?>"></p>

	  <!--身份证号码
	  <input type="text" name="member_no" value="<?php echo $condition['member_no'];?>">-->

	  <!--ID
	  <input type="text" name="o_id" value="<?php echo $condition['o_id'];?>">-->

                    <p><div style="float: left;width:80px;text-align: center">支付状态</div>

                    <select name="pay_status">
                        <option value="">所有</option>
                        <?php
                        foreach($condition['o_pay_status'] as $k=>$v){
                            if(isset($get_status['pay_status']) && $k==$get_status['pay_status']){
                                echo '<option value="'.$k.'" selected="selected">'.$v.'</option>';
                            }else{
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }
                        }
                        ?>
                    </select>

                    订单状态
                    <select name="status">
                        <option value="">所有</option>
                        <?php
                        foreach($condition['o_status'] as $k=>$v){
                            if(isset($get_status['status']) && $k==$get_status['status']){
                                echo '<option value="'.$k.'" selected="selected">'.$v.'</option>';
                            }else{
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }
                        }
                        ?>
                    </select>
                    </p>

	  <input type="submit" name="Submit" value="提交">

				</form>
				</div>

		  <div id="data-grid_wrapper" class="dataTables_wrapper form-inline dt-bootstrap"><div class="row"><div class="col-sm-6"><div class="dataTables_length" id="data-grid_length"></div></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
		  <table id="data-grid" class="table table-bordered table-striped table-condensed dataTable" role="grid" aria-describedby="data-grid_info">
			<thead>
			  <tr role="row">
				  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">ID</th>
                  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">PMS酒店ID</th>
                  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">酒店名</th>
                  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">PMS订单号</th>
                  <th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">订单号</th>


                  <th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">下单时间</th>
                  <th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">入住时间</th>
                  <th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">离店时间</th>
				  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">入住人</th>
				  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">电话</th>
				  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">房间数</th>

                  <!--<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">代金券</th>
                  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">积分抵用</th>-->
                  <th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">优惠券金额</th>
<!--                  <th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">优惠券张数</th>-->
                  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">支付方式</th>
                  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">支付状态</th>
                  <th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">状态</th>
                  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">退款状态</th>
                  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">原价</th>
                  <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">总价</th>


			  </tr>
			</thead>
			<tfoot>
			  <tr>
			    <th rowspan="1" colspan="1">ID</th>
				<th rowspan="1" colspan="1">PMS酒店ID</th>
				<th rowspan="1" colspan="1">酒店名</th>
				<th rowspan="1" colspan="1">PMS订单号</th>

				<th rowspan="1" colspan="1">订单号</th>
                  <!--<th rowspan="1" colspan="1">代金券</th>
                  <th rowspan="1" colspan="1">积分抵用</th>-->
				<th rowspan="1" colspan="1">下单时间</th>

				<th rowspan="1" colspan="1">入住时间</th>
                  <th rowspan="1" colspan="1">离店时间</th>
				<th rowspan="1" colspan="1">入住人</th>
				<th rowspan="1" colspan="1">电话</th>

				<th rowspan="1" colspan="1">房间数</th>
				<th rowspan="1" colspan="1">优惠券金额</th>
<!--                  <th rowspan="1" colspan="1">优惠券张数</th>-->

				<th rowspan="1" colspan="1">支付方式</th>
				<th rowspan="1" colspan="1">支付状态</th>
				<th rowspan="1" colspan="1">状态</th>
				<th rowspan="1" colspan="1">退款状态</th>

				<th rowspan="1" colspan="1">原价</th>
                  <th rowspan="1" colspan="1">总价</th>
			  </tr>
			</tfoot>

		  <tbody>
		  <?php foreach($datalist as $dl){ ?>
			  <tr id="28" role="row" class="odd">

			    <td class="sorting_1"><?php echo $dl['item_id'];?></td>
				<td><?php if(!empty( $dl['hotel_web_id'])){echo $dl['hotel_web_id'];}else{ echo "";}?></td>
				<td><?php echo $dl['hotel_name'];?></td>
				<td><?php echo $dl['webs_orderid'];?></td>

				<td><?php echo $dl['orderid'];?></td>
                <td><?php echo date('Y-m-d H:i',$dl['order_time']);?></td>
				<td><?php echo $dl['istartdate'];?></td>
				<td><?php echo $dl['ienddate'];?></td>

				<td><?php echo $dl['name'];?></td>
                <td><?php echo $dl['tel'];?></td>
                <td><?php echo 1;?></td>
                  <td><?php
                      $coupon_favour = '';
                      $order_additions_new = $model->order_additions($dl['orderid']);
                      if ($order_additions_new) {
                          $coupon_favour = $order_additions_new[0]['coupon_favour'];
                      }
                      unset($order_additions_new);

                      echo round(($coupon_favour/$dl['roomnums']),2);
                      ?></td>
<!--                  <td><?php
/*                      if ($coupon_favour>0) {
                          echo 1;
                      }
                      else {
                          echo 0;
                      }
                      */?></td>-->
				<td><?php echo $condition['o_pay_way'][$dl['paytype']];?></td>
				<td><?php if($dl['paid']==0){
                        echo '未支付';

                    }else{
                                    if(isset($condition['o_pay_status'][$dl['paid']])){
                                        echo $condition['o_pay_status'][$dl['paid']];
                                    }else{

                                        echo"已支付";
                                    }

                            
                    };?></td>
                  <td><?php echo $condition['o_status'][$dl['istatus']];?></td>
                  <td><?php if($dl['refund']==0){
                          echo '未退款';

                      }elseif($dl['refund']==1){
                          echo '退款成功';

                      }elseif($dl['refund']==2){
                          echo '退款失败';
                      };?></td>
                  <td><?php echo $dl['oprice'];?></td>
                  <td><?php echo $dl['iprice'];?></td>
			  </tr>
		  <?php }?>
			</tbody>
		  </table>




			<div id="data-grid_processing" class="dataTables_processing" style="display: none;">Processing...</div>
			</div></div><div class="row"><div class="col-sm-5">

			<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite"><?php echo $qfpage['item'];?>
			<br>结算
			订单总数：<?php echo $count;?>
			总额：<?php echo $sum_price;?>
<!--			<form name="qfexport" id="qfexport" method="post" action="">-->
<!--			  <input type="hidden" name="export" value="1" />-->
<!--			  <input type="hidden" name="--><?php //echo $csrf['name'];?><!--" value="--><?php //echo $csrf['hash'];?><!--" />-->
<!--			  <input type="submit" name="Submit" value="导出数据" /> 导出大量数据时由于花费时间比较长，请耐心等待！-->
<!--			</form>-->
			</div>

			</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate"><?php echo $qfpage['html'];?></div></div></div></div>
		</div><!-- /.box-body -->
	  </div><!-- /.box -->
	</div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
var pay_type = '<?php echo $condition['pay_type'];?>';
var pay_status = '<?php echo $condition['pay_status'];?>';
var status = '<?php echo $condition['status'];?>';
var time_type = '<?php echo $condition['time_type'];?>';

$('select[name=pay_type]').val(pay_type);
$('select[name=pay_status]').val(pay_status);
$('select[name=status]').val(status);
$('select[name=time_type]').val(time_type);
</script>
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
$buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-del"><i class="fa fa-trash"></i>&nbsp;删除</button>';
/*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
//$buttions.= '<button type="button" class="btn btn-default" id="grid-btn-extra-0"><i class="fa fa-trash"></i>&nbsp;导出</button>';
if(isset($js_filter_btn)) $buttions.= $js_filter_btn;
?>
var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');

var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];

//var dataSet= <?php //echo json_encode($result['data']); ?>;
//var columnSet= <?php //echo json_encode( $model->get_column_config($fields_config) ); ?>;
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
?>
});
</script>
</body>
</html>
