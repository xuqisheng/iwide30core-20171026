<!-- DataTables -->
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/qrcode.js"></script>
<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>
<style>
.btn{min-width:100px}
.input{display:inline-block}
.input select,.input input{width:163px}
.marbtm{margin-bottom:12px;}
table a{color:#39C}
#qrcode>img{margin: 0 auto;text-align: center;}
table .check{margin-right:0}
</style>
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
<div class="modal fade" id="sendModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">发放福利</h4>
            </div>
            <div class="modal-body">
                <div id='cfg_items'>
<?php echo form_open('distribute/welfare/send_welfare',array('id'=>"setting_form",'class'=>'form-horizontal'))?>
                <input type="hidden" name="salers" value="" />
                <input type="hidden" name="typ" value="1" />
                    <div class="form-group">
                        <label class="col-sm-3 control-label">发放对象</label>
                        <div class="col-sm-9" id="send_to" style="padding-top: 7px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">福利标题</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm" name="title" value="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">福利金额</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm" name="amount" value="" rc="0" />
                            <p class="help-block">每日上限金额为<?php echo isset($configs->upper_limit_day_amount) ? $configs->upper_limit_day_amount : '--' ;?></p>
                        </div>/人
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">福利总额</label>
                        <div class="col-sm-9" id="welfare_amount" style="padding-top: 7px;">0</div>
                    </div>
                    <input type="hidden" name="saler" id="hsaler" value=""/>
                    <input type="hidden" name="token" id="ahtoken" value="<?php echo $token?>"/>
                    <div class="form-group" id="qrcode">
                    </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="setModelConfirm">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class="modal fade" id="detailModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div id='cfg_items'></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_auth_confirm">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Main content -->
			<section class="content">
                <?php echo $this->session->show_put_msg(); ?>
                	<?php echo form_open('distribute/welfare/index/',array('class'=>"bd bg_fff pad10",'id'=>'search_form','method'=>'post'))?>
	                
        	<div class="marbtm">
            	<span>所属酒店</span>
                <span class="input"><select name="hotel_id"><option value=""<?php if(empty($hotel_id)):echo ' selected';endif;?>>所有酒店</option>
				<?php foreach ($hotels as $hid=>$hname):?><option value="<?php echo $hid;?>"<?php if($hid == $hotel_id):echo ' selected';endif;?>><?php echo $hname;?></option><?php endforeach;?></select></span>
                            
            	<span style="margin-left:25px;">所属部门</span>
                <span class="input"><select name="department"><option value="">所有部门</option>
				<?php foreach ($depts as $k=>$v):if(!empty($v->master_dept)):?><option value="<?php echo $v->master_dept;?>"<?php if($deptment == $v->master_dept):echo ' selected';endif;?>><?php echo $v->master_dept;?></option><?php endif; endforeach;?></select></span>
            	<span style="margin-left:25px;">分销号</span>
                <span class="input"><input type="text" name="saler_no" aria-controls="data-grid" value="<?php echo $saler_no?>"></span>
            	<!--<span style="margin-left:25px;">姓名</span>
                <span class="input"><input type="text" name="saler_name" aria-controls="data-grid" value="<?php echo $saler_name?>"></span>-->
            	<span style="margin-left:25px;">核定日期</span>
                <span class="input"><input type="text" name="btime" class="datetime" aria-controls="data-grid" value="<?php echo $gtime_begin?>"></span>
            	<span>-</span>
                <span class="input"><input type="text" name="etime" class="datetime" aria-controls="data-grid" value="<?php echo $gtime_end?>"></span>
			</div>
			<button type="submit" class="btn bg-orange maright" id="grid-btn-search">查询</button>
            <button class="btn bg-red" type="button" data-toggle="modal" data-target="" onclick="showModel()">发放福利</button>
                <!--<a class="btn btn-sm bg-green" href="<?php echo site_url("distribute/qrcodes/ext_qrcodes/".$hotel_id.'_'.$saler_name.'_'.$saler_no.'_'.$deptment.'_'.$gtime_begin.'_'.$gtime_end)?>">导出当前</a>-->   
			<div class="martop" style="color:#f00">当前发放配设置：<?php if($configs): $str = $configs->upper_limit_typ == 2 ? '自定义结算上限,每日上限金额：￥'. $configs->upper_limit_day_amount . '，每日上限'.$configs->upper_limit_day_times.'次' : '结算上限金额为未结收益 '; echo $configs->welfare == 2 ? $str .= ',允许发放福利' : $str .= ',不允许发放福利';else: echo '没找到配置信息';endif;?></div>
                <?php echo form_close();?>
            <table id="data-grid" class="table martop table-striped table-condensed">
            <thead>
            <tr>
            <th style="width: 3em;color:#ff9900" id="select_all" prop="false">全选</th>
            <th>分销员</th>
            <th>分销号</th>
            <th>手机号</th>
            <th>所属酒店</th>
            <th>所属部门</th>
            <th>分销状态</th>
            <th>总收益</th>
            <th>未发收益</th>
            <th>今天成功</th>
            <th>操作</th>
            </tr>
            </thead>
            <tbody><?php foreach ($res as $item):?>
            <tr>
            <td>
            <label class="check"><input type="checkbox" name="sid[]" value="<?php echo $item->qrcode_id?>" rs="<?php echo $item->status?>" rn="<?php echo $item->name?>" /><span class="diyradio"><tt></tt></span></label></td>
            <td><?php echo $item->name?></td>
            <td><?php echo $item->qrcode_id?></td>
            <td><?php echo $item->cellphone?></td>
            <td><?php echo $item->hotel_name?></td>
            <td><?php echo $item->master_dept?></td>
            <td><?php echo isset($status_arr[$item->status]) ? $status_arr[$item->status] : '异常'; ?></td>
            <td><?php echo empty($item->grade_total) ? '0.00' : $item->grade_total;?></td>
            <td><?php echo $item->undeliver?></td>
            <td><?php echo empty($day_logs[$item->qrcode_id]) ? '0元，0次' : $day_logs[$item->qrcode_id]->amounts.'元，'.$day_logs[$item->qrcode_id]->counts.'次';?></td>
            <td><a href="" sid="<?php echo $item->qrcode_id?>" name="sendWelfare" value="<?php echo $item->qrcode_id?>" rn="<?php echo $item->name?>">发酒店福利</a>&nbsp;|&nbsp;<a href="" sid="<?php echo $item->qrcode_id?>" name="sendWelfare_jfk" value="<?php echo $item->qrcode_id?>" rn="<?php echo $item->name?>">发金房卡福利</a></td>
            </tr><?php endforeach;?>
            </tbody>
            </table>
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite" total_amount="<?=$total?>">共<?=$total?>条</div>
                </div>
                <div class="col-sm-7">
                    <div class="dataTables_paginate paging_simple_numbers"
                        id="data-grid_paginate">
                        <ul class="pagination"><?php echo $pagination?></ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
		<!-- /.content-wrapper -->
<?php 
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>

</div>
	<!-- ./wrapper -->

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>

</body>
</html>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<!-- SlimScroll -->
	<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
	<!-- page script -->
	<script>
$(".datetime").datepicker({format: 'yyyy-mm-dd',language: "ZH-CN"});
var url_extra= [
//'http://iwide.cn/',
];

	function showModel(){
		var total = $("input[name='sid[]']:checked");
		if(total.length == 1){
			$('#send_to').html(total.val() + '-' + total.attr('rn'));
		}else{
			$('#send_to').html('已选中 ' + total.length + ' 人');
		}
		$('input[name=amount]').attr('rc',total.length);
		var amountObj = $('input[name=amount]');
		$('#welfare_amount').html(parseFloat (amountObj.val())*parseFloat (amountObj.attr('rc')));
		$('input[name=salers]').val(total.serialize().replace(/sid%5B%5D=/g,'').replace(/&/g,','));
		$('#sendModal').modal('show');
	}
	$('a[name=sendWelfare]').on('click',function(e){
		e.preventDefault();
		var total = $(this);
		$('#send_to').html(total.attr('value') + '-' + total.attr('rn'));
		$('input[name=amount]').attr('rc',1);
		var amountObj = $('input[name=amount]');
		$('#welfare_amount').html(parseFloat (amountObj.val())*parseFloat (amountObj.attr('rc')));
		$('input[name=salers]').val(total.attr('value'));
		$('input[name=typ]').val(1);
		$('#qrcode').html('');
		var qrcode = new QRCode(document.getElementById("qrcode"), {
	        width : 155,//设置宽高
	        height : 155
	    });
	
	    qrcode.makeCode("http://<?php echo $domain?>/index.php/distribute/dis_v1/s/<?php echo $token?>?id=<?php echo $inter_id?>&t=1");
		$('#sendModal').modal('show');
	});
	$('a[name=sendWelfare_jfk]').on('click',function(e){
		e.preventDefault();
		var total = $(this);
		$('#send_to').html(total.attr('value') + '-' + total.attr('rn'));
		$('input[name=amount]').attr('rc',1);
		var amountObj = $('input[name=amount]');
		$('#welfare_amount').html(parseFloat (amountObj.val())*parseFloat (amountObj.attr('rc')));
		$('input[name=salers]').val(total.attr('value'));
		$('input[name=typ]').val(2);
		$('#qrcode').html('');
		var qrcode = new QRCode(document.getElementById("qrcode"), {
	        width : 155,//设置宽高
	        height : 155
	    });
	
	    qrcode.makeCode("http://<?php echo $domain?>/index.php/distribute/dis_v1/s/<?php echo $token?>?id=<?php echo $inter_id?>&t=2");
		$('#sendModal').modal('show');
	});
$('#setModelConfirm').on('click',function(){
	if($.trim($('input[name=title]').val()) == '' || $.trim($('input[name=amount]').val()) == '' || $.trim($('input[name=salers]').val()) ==''){
		return alert('发放失败，请检查填写的或选择的内容是否正确');
	}
	if(confirm('点击确认后无法撤回操作，请仔细核对')){
		$.post("<?php echo site_url('distribute/welfare/send_welfare')?>",$('#setting_form').serialize(),function(data){
			if(data.errmsg != undefined){
				alert(data.errmsg);
			}else{
				alert('成功发放：' + data.success + ',失败：' + data.failed + ',异常：' + data.error);
				location.reload();
			}
			$('#sendModal').modal('hide');
		},'json');
	}
});
$(document).ready(function() {
    $(".datetime").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
	$('#select_all').click(function (){
		if($(this).attr('prop') == "false"){
			$("input[name='sid[]']").prop('checked',true);
			$('#select_all').attr('prop',"true");
		}else{
			$("input[name='sid[]']").prop('checked',false);
			$('#select_all').attr('prop',"false");
		}
	});
	$("input[name='sid[]']").click(function(){
		$('#select_all').attr('prop',"false");
	});
	$('input[name=amount]').on('change',function(){
		// var amount = parseFloat ($(this).val());
		$('#welfare_amount').html(parseFloat ($(this).val())*parseFloat ($(this).attr('rc')));
	});
});
	$('#sendModal').on('show.bs.model',function (event) {
// 		var total_amount = parseInt($('div[role="status"]').attr('total_amount'));
		var total = $("input[name='sid[]']:checked");
		if(total.length > 1){
			$('#send_to').html(total.val() + '-' + total.attr('rn'));
		}
		
	});
</script>
