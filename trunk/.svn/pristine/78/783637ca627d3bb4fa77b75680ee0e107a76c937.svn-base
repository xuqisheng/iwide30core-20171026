<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<?php
$data['discount'] = unserialize($data['discount']);
$data['discount']['type'] = isset($data['discount']['type'])?$data['discount']['type']:'';
$data['discount']['value'] = isset($data['discount']['value'])?$data['discount']['value']:'';
?>
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
  <script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/report/DatePicker/WdatePicker.js"></script>
  <script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/kindeditor/kindeditor.js"></script>
  <script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/kindeditor/lang/zh_CN.js"></script>
	<script>
	var editor1;
	KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="content"]', {
			cssPath : '<?php echo base_url(FD_PUBLIC) ?>/kindeditor/plugins/code/prettify.css',
			uploadJson : '<?php echo base_url() ?>index.php/basic/uploadftp/do_upload',
			fileManagerJson : '<?php echo base_url() ?>index.php/basic/uploadftp/listfiles',
			allowFileManager : true,
			minWidth : '100%',
			autoHeightMode : true,
			width : '100%',
			afterCreate : function() {
				$(window).on('resize', function() {
					if (editor1) editor1.resize('100%', null);
				});
			}
		});
	});
	</script>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>信息列表 <small></small> </h1>
      <ol class="breadcrumb">
        <li class="active">信息列表</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Horizontal Form -->
      <div class="box box-info">
        <!--
	<div class="box-header with-border">
		<h3 class="box-title">新增信息</h3>
	</div>
	 /.box-header -->
        <div class="tabbable ">
          <!-- Only required for left/right tabs -->
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
          </ul>
          <!-- form start -->
          <div class="tab-content">
            <div class="tab-pane active" id="tab1">
              <form action="" class="form-horizontal" method="post" accept-charset="utf-8">
			  <iframe id="uploads" name="uploads" src="/index.php/report/upload" style="display:none"></iframe>
                <div class="box-body">
				  <?php 
				  $dataid = isset($data['id'])?$data['id']:"";
				  if($dataid){ 
				  	$publics = $this->publics_model->get_public_by_id($data['inter_id']);
				  	$hotel_domain = $publics['domain'];
				  ?>
				  <div class="form-group ">
                    <label for="el_cat_id" class="col-sm-2 control-label">访问地址：</label>
                    <div class="col-sm-8">
					  <input class="form-control " type="text" readonly="1" value="http://<?php echo $hotel_domain;?>/index.php/chat/api/form?iad=<?php echo $data['id'];?>&id=<?php echo $data['inter_id'];?>">
                    </div>
                  </div>
				  <?php } ?>
                  <div class="form-group ">
                    <label for="el_cat_id" class="col-sm-2 control-label">表单标题</label>
                    <div class="col-sm-8">
					  <input name="titles" class="form-control " type="text" placeholder="表单标题" value="<?php echo $data['title'];?>">
                    </div>
                  </div>
                  <div class="form-group  has-feedback">
                    <label for="el_gs_name" class="col-sm-2 control-label">关键词</label>
                    <div class="col-sm-8">
					  <input type="text" class="form-control " name="keyword" placeholder="关键词" value="<?php echo $data['keyword'];?>">
                    </div>
                  </div>
				  <div class="form-group">
                    <label for="el_gs_name" class="col-sm-2 control-label">所属公众号</label>
                    <div class="col-sm-8">
					  <?php 
					  if($data['inter_id']=='ALL_PRIVILEGES'){
					  	echo '<select name="inter_id" class="form-control">';
					  	foreach ($publics as $k => $v) {
					  		echo '<option value="'.$k.'">'.$v.'</option>';
					  	}
					  	echo '</select>';
					  	//echo '<input type="text" class="form-control " name="inter_id" placeholder="所属公众号" value="">';
					  } else {
					    echo '<input type="text" class="form-control " name="inter_id" readonly="1" placeholder="所属公众号" value="'.$data['inter_id'].'">';
					  }
					  ?>
                    </div>
                  </div>
                  <div class="form-group ">
                    <label for="el_gs_brand" class="col-sm-2 control-label">简介</label>
                    <div class="col-sm-8">
                      <textarea name="intro" class="form-control " rows="3" style="resize:none"><?php echo $data['intro'];?></textarea>
                    </div>
                  </div>
                  <div class="form-group  has-feedback">
                    <label for="el_gs_nums" class="col-sm-2 control-label">访问界面图</label>
                    <div class="col-sm-8">
					  <input name="toppic" class="col-sm-8 showlogo" style="margin-right:20px" readonly="1" id="qfdo_upload_img" value="<?php echo $data['toppic'];?>" size="40" type="text">
					  <input id="qfdo_upload" value="上传图片" type="button">
                    </div>
                  </div>
                  <div class="form-group  has-feedback">
                    <label for="el_gs_weight" class="col-sm-2 control-label">表单限制</label>
                    <div class="col-sm-8">
                     	<input type="checkbox" name="putstarttime"<?php if($data['isstarttime']==1){echo ' checked="checked"';} ?> id="starttime" value="1">
						  开始时间  
						  <input type="checkbox" name="putlimittime"<?php if($data['islimittime']==1){echo ' checked="checked"';} ?> id="limittime" value="1">
						  截止时间   
						  <input type="checkbox" name="putdaynum"<?php if($data['isdaynum']==1){echo ' checked="checked"';} ?> id="daynum" value="1">
						  限定每日量   
						  <input type="checkbox" name="puttotalnum"<?php if($data['istotalnum']==1){echo ' checked="checked"';} ?> id="totalnum" value="1">
						  限定总量   <span class="needcheck">
						  <input type="checkbox" name="ischeck"<?php if($data['ischeck']==1){echo ' checked="checked"';} ?> value="1">
						  需要审核 </span>
						  

						  <div id="showlimit"><Br>
						  <p id="showstarttime"> 开始：
						  <input type="text" name="dstarttime" value="<?php echo $data['starttime'];?>" readonly="1" onFocus="WdatePicker({startDate:'%y-%M-%dd 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:00',alwaysUseStartDate:true})">
						  </p>
						  <p id="showendtime"> 截止：
							<input type="text" name="dlimittime" value="<?php echo $data['limittime'];?>" readonly="1" onFocus="WdatePicker({startDate:'%y-%M-%dd 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:00',alwaysUseStartDate:true})">
						  </p>
						  <p id="showdaynum"> 每日：
							<input type="text" name="ddaynum" value="<?php echo $data['daynum'];?>">
						  </p>
						  <p id="showtotalnum"> 总量：
							<input type="text" name="dtotalnum" value="<?php echo $data['totalnum'];?>">
						  </p>
					  </div>
                    </div>
                  </div>
                  <div class="form-group ">
                    <label for="el_inter_id" class="col-sm-2 control-label">选择模板</label>
                    <div class="col-sm-8">
					  <select class="form-control" name="template" id="template">
						<!--<option value="postform">经典报名模板</option>-->
						<!--<option value="signuppay">报名+支付模板</option>-->
						<!--<option value="signuppaycard">报名+支付+优惠券(酒店邦)</option>-->
						<option value="signuptopay">报名+支付(苏州为爱徒步)</option>
						<!--<option value="coupon">优惠券模板</option>-->
					  </select>
					  
					  <div id="showtempfun"><Br>
					  	<p id="showprice"> 单价：
							<input type="text" name="dprice" value="<?php echo $data['price'];?>" size="5">
							元 &nbsp;&nbsp;&nbsp;
							<input name="ddiscount[type]" type="radio" value="discount"<?php if($data['discount']['type']=='discount'){echo ' checked="checked"';} ?>>
							按折扣
							<input name="ddiscount[type]" type="radio" value="reduce"<?php if($data['discount']['type']=='reduce'){echo ' checked="checked"';} ?>>
							按立减
							<input id="adddiscount" type="button" value=" 添加优惠 " />
						  </p>
						  <p id="showdiscount"></p>
					  </div>
	  
	  
                    </div>
                  </div>
				  
				  <div class="form-group ">
                    <label for="el_cat_id" class="col-sm-2 control-label">提示成功</label>
                    <div class="col-sm-8">
					  <input type="text" class="form-control " name="successtip" value="<?php echo $data['successtip'];?>">
                    </div>
                  </div>
				  <div class="form-group ">
                    <label for="el_cat_id" class="col-sm-2 control-label">提示失败</label>
                    <div class="col-sm-8">
					  <input type="text" class="form-control " name="errtip" value="<?php echo $data['errtip'];?>">
                    </div>
                  </div>

				  
				  <div class="form-group ">
                    <label for="el_cat_id" class="col-sm-2 control-label">补充说明</label>
                    <div class="col-sm-8">
						<textarea name="content" class="form-control " style="height:300px"><?php echo $data['content'];?></textarea>
					    <?php if(isset($csrf)){echo '<input type="hidden" name="'.$csrf['name'].'" value="'.$csrf['hash'].'" />';}?>
                    </div>
                  </div>

				  
                  <!-- /.box-body -->
                  <div class="box-footer ">
                    <div class="col-sm-3 col-sm-offset-0">
                      <button type="submit" class="btn btn-info pull-right" id="addpostform">保存</button><input name="submit" type="hidden" value="submit">
                    </div>
                  </div>
                  <!-- /.box-footer -->
                </div>
              </form>
              <!-- /.box-body -->
            </div>
            <!-- /#tab1-->
          </div>
          <!-- /.tab-content -->
        </div>
        <!-- /.box -->
      </div>
    </section>
    <!-- /.content -->
  </div>
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
<script type="text/javascript">
function checkboxstatus(){
    var cklimittime = $('#limittime').attr('checked');
	var ckdaynum = $('#daynum').attr('checked');
	var cktotalnum = $('#totalnum').attr('checked');
	var ckstarttime = $('#starttime').attr('checked');
	
	
	if(cklimittime || ckdaynum || cktotalnum || ckstarttime){
	    $('#showlimit').show();
	}
	else {
	    $('#showlimit').hide();
	}
	if(cklimittime){
	    $('#showendtime').show();
	}
	else {
	    $('#showendtime').hide();
	}
	if(ckdaynum){
	    $('#showdaynum').show();
	}
	else {
	    $('#showdaynum').hide();
	}
	if(cktotalnum){
	    $('#showtotalnum').show();
	} 
	else {
	    $('#showtotalnum').hide();
	}
	if(ckstarttime){
	    $('#showstarttime').show();
	} 
	else {
	    $('#showstarttime').hide();
	}
}
function checkboxclick(id){
    $(id).click(function(){
		if($(id).attr('checked')){
		    $(id).attr('checked',false);
		}
		else {
			$(id).attr('checked','checked');
		}
		checkboxstatus();
	});
}
checkboxstatus();
checkboxclick('#limittime');
checkboxclick('#daynum');
checkboxclick('#totalnum');
checkboxclick('#starttime');
var template = '<?php echo $data['template'];?>';
if(template){
 $('#template').val(template);
}
$('#addpostform').click(function(){
	if($('[name=titles]').val().length<3){
		alert('标题过短！');return false;
	}
	if($('[name=keyword]').val().length<3){
		alert('关键词填写不正确！');return false;
	}
	if($('[name=toppic]').val().length<3){}
});

function tempfun(){
	/*******控制TR******/
	if($('#template').val()=='signuppay'){
		$('#showtempfun').show();
		$('.needcheck').hide();
	}
	else if($('#template').val()=='signuppaycard'){
		$('#showtempfun').show();
		$('.needcheck').hide();
	}
	else if($('#template').val()=='signuptopay'){
		$('#showtempfun').show();
		$('.needcheck').hide();
	}
	else {
	    $('#showtempfun').hide();
		$('.needcheck').show();
	}
	/*******控制P******/
	if($('#template').val()=='signuppay'){
		$('#showprice').show();
	}
	else if($('#template').val()=='signuppaycard'){
		$('#showprice').show();
	}
	else if($('#template').val()=='signuptopay'){
		$('#showprice').show();
	}
	else {
	    $('#showprice').hide();
	}
}
tempfun();
$('#template').change(function(){
	tempfun();
});
var len = 0;
var discountval = <?php echo json_encode($data['discount']);?>;
if(discountval.value){
	for(var i=0;i<discountval.value.num.length;i++){
		len += 1;
		lasttype = discountval.type;
		if(lasttype == 'discount'){
			$('#showdiscount').append('<span class="showdiscount'+len+'">至少数量：<input type="text" name="ddiscount[value][num][]" value="'+discountval.value.num[i]+'" size="3">(填写整数) &nbsp;&nbsp;折扣:<input type="text" name="ddiscount[value][dis][]" value="'+discountval.value.dis[i]+'" size="3">(填写小数) <input type="button" onclick="deldiscount(\'showdiscount'+len+'\')" value=" 删除 " /><br></span>');
		}
		else if(lasttype == 'reduce'){
			$('#showdiscount').append('<span class="showdiscount'+len+'">购满金额：<input type="text" name="ddiscount[value][num][]" value="'+discountval.value.num[i]+'" size="3">(填写整数) &nbsp;&nbsp;立减:<input type="text" name="ddiscount[value][dis][]" value="'+discountval.value.dis[i]+'" size="3">(填写整数) <input type="button" onclick="deldiscount(\'showdiscount'+len+'\')" value=" 删除 " /><br></span>');
		}
	
	}
}
var lasttype = $('input[name="ddiscount[type]"]:checked').val();
var lasttypediscount = '',lasttypereduce = '';
if(lasttype == 'discount'){
	lasttypediscount = $('#showdiscount').html();
}
else if(lasttype == 'reduce'){
	lasttypereduce = $('#showdiscount').html();
}
$('#adddiscount').click(function(){
	len += 1;
	lasttype = $('input[name="ddiscount[type]"]:checked').val();
	if(lasttype == 'discount'){
	    $('#showdiscount').append('<span class="showdiscount'+len+'">至少数量：<input type="text" name="ddiscount[value][num][]" value="" size="5">（填写整数） &nbsp;&nbsp;折扣：<input type="text" name="ddiscount[value][dis][]" value="" size="5">（填写小数） <input type="button" onclick="deldiscount(\'showdiscount'+len+'\')" value=" 删除 " /><br></span>');
		lasttypediscount = $('#showdiscount').html();
	}
	else if(lasttype == 'reduce'){
	    $('#showdiscount').append('<span class="showdiscount'+len+'">购满金额：<input type="text" name="ddiscount[value][num][]" value="" size="5">（填写整数） &nbsp;&nbsp;立减：<input type="text" name="ddiscount[value][dis][]" value="" size="5">（填写整数） <input type="button" onclick="deldiscount(\'showdiscount'+len+'\')" value=" 删除 " /><br></span>');
		lasttypereduce = $('#showdiscount').html();
	}
});
$('input[name="ddiscount[type]"]').click(function(){
    if($(this).val() == 'discount'){
	    $('#showdiscount').html(lasttypediscount);
	}
	else if($(this).val() == 'reduce'){
	    $('#showdiscount').html(lasttypereduce);
	}
})
function deldiscount(id){
    $('.'+id).remove();
	lasttype = $('input[name="ddiscount[type]"]:checked').val();
	if(lasttype == 'discount'){
		lasttypediscount = $('#showdiscount').html();
	}
	else if(lasttype == 'reduce'){
		lasttypereduce = $('#showdiscount').html();
	}
}
</script>

<script>
$(document).ready(function() {
	KindEditor.ready(function(K){qfkeupload(editor1);});
	$(".market_content>div").hide();
	$(".market_content div").eq(0).show();
	$(".market_list li").click(function() {
        $(".market_list li").eq($(this).index()).addClass("on").siblings().removeClass('on');
        $(".market_content>div").hide().eq($(this).index()).show(); 
    });
});
function change()
{
	var select = $("select[name='activity_time_type']").val();
	if(select==1) {
		$(".bdtime").show();
	} else {
		$(".bdtime").hide();
	}
}
function change2()
{
	var select = $("select[name='activity_product_type']").val();
	if(select==2) {
		$("#products").show();
	} else {
		$("#products").hide();
	}
}
</script>
</html>