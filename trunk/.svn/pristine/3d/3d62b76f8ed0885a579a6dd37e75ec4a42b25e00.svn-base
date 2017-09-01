<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'header.php';
$data['discount'] = unserialize($data['discount']);
$data['discount']['type'] = isset($data['discount']['type'])?$data['discount']['type']:'';
$data['discount']['value'] = isset($data['discount']['value'])?$data['discount']['value']:'';
?>
<link rel="stylesheet" href="/static/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="/static/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/static/kindeditor/lang/zh_CN.js"></script>
<script>
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true,
			resizeType : 0
		});
		K('input[name=getHtml]').click(function(e) {
			alert(editor.html());
		});
		K('input[name=isEmpty]').click(function(e) {
			alert(editor.isEmpty());
		});
		K('input[name=getText]').click(function(e) {
			alert(editor.text());
		});
		K('input[name=selectedHtml]').click(function(e) {
			alert(editor.selectedHtml());
		});
		K('input[name=setHtml]').click(function(e) {
			editor.html('<h3></h3>');
		});
		K('input[name=setText]').click(function(e) {
			editor.text('<h3></h3>');
		});
		K('input[name=insertHtml]').click(function(e) {
			editor.insertHtml('<strong></strong>');
		});
		K('input[name=appendHtml]').click(function(e) {
			editor.appendHtml('<strong></strong>');
		});
		K('input[name=clear]').click(function(e) {
			editor.html('');
		});
		
		K('#testimg').click(function () {
			editor.loadPlugin('image', function () {
				editor.plugin.imageDialog({
					imageUrl: K('#textimg').val(),
					clickFn: function (url, title, width, height, border, align) {
						K('#textimg').val(url);
						editor.hideDialog();
					}
				});
			});
		});
	});
</script>

<body>
<form name="form1" method="post" action="">
<table align="center" cellpadding="0" cellspacing="0" width="800" style="height:30px; border:#CCCCCC solid 1px; margin-bottom:10px">
  <tr>
    <td>&nbsp;&nbsp;<a href="/index.php/superform/suform">返回上一页</a> </td>
  </tr>
</table>
<iframe id="uploads" name="uploads" src="/index.php/upload" style="display:none"></iframe>
<table class="addform" align="center" cellpadding="0" cellspacing="0" width="800">
  <tr>
    <td class="right" style="width:95px">*标题：</th>
	<td> <input name="title" type="text" value="<?php echo $data['title'];?>"></td>
  </tr>
  <tr>
    <td class="right">*关键词：</td>
    <td> <input type="text" name="keyword" value="<?php echo $data['keyword'];?>"></td>
  </tr>
  <tr>
    <td class="right">简介：</td>
    <td style="padding-bottom:5px; padding-top:5px"> <textarea name="intro" cols="50" rows="8" style="resize:none"><?php echo $data['intro'];?></textarea></td>
  </tr>
  <tr>
    <td class="right">*Logo地址：</td>
    <td> <input name="toppic" readonly="1" id="textimg" value="<?php echo $data['toppic'];?>" size="40" type="text"> <input id="testimg" value="上传图片" type="button">
	<!--自写的一个上插件<input type="text" disabled="disabled" value="<?php echo $data['toppic'];?>" size="40" class="showlogo"><input class="showlogo" type="hidden" name="toppic" value="<?php echo $data['toppic'];?>"> <input type="button" value="上传图片" id="upfiles" />--></td>
  </tr>
  <tr>
    <td class="right">表单提交限制：</td>
    <td>
	  <input type="checkbox" name="putstarttime"<?php if($data['isstarttime']==1){echo ' checked="checked"';} ?> id="starttime" value="1">
      开始时间  
      <input type="checkbox" name="putlimittime"<?php if($data['islimittime']==1){echo ' checked="checked"';} ?> id="limittime" value="1">
      截止时间   
      <input type="checkbox" name="putdaynum"<?php if($data['isdaynum']==1){echo ' checked="checked"';} ?> id="daynum" value="1">
      限定每日量   
      <input type="checkbox" name="puttotalnum"<?php if($data['istotalnum']==1){echo ' checked="checked"';} ?> id="totalnum" value="1">
      限定总量  	
	  <span class="needcheck"><input type="checkbox" name="ischeck"<?php if($data['ischeck']==1){echo ' checked="checked"';} ?> value="1">
	  需要审核
	  </span>
	  </td>
  </tr>
  <tr id="showlimit">
    <td class="right">&nbsp;</td>
    <td>
	  <p id="showstarttime">
        开始：
          <input type="text" name="dstarttime" value="<?php echo $data['starttime'];?>" readonly="1" onFocus="WdatePicker({startDate:'%y-%M-%dd 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:00',alwaysUseStartDate:true})">
	  </p>
	  <p id="showendtime">
      截止：
          <input type="text" name="dlimittime" value="<?php echo $data['limittime'];?>" readonly="1" onFocus="WdatePicker({startDate:'%y-%M-%dd 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:00',alwaysUseStartDate:true})">
    </p>
      <p id="showdaynum">
        每日：
          <input type="text" name="ddaynum" value="<?php echo $data['daynum'];?>">
      </p>
      <p id="showtotalnum">
        总量：
          <input type="text" name="dtotalnum" value="<?php echo $data['totalnum'];?>">
      </p>	</td>
  </tr>
  <tr>
    <td class="right">选择模板：</td>
    <td><select name="template" id="template">
      <option value="postform">经典报名模板</option>
      <option value="signuppay">报名+支付模板</option>
	  <option value="signuppaycard">报名+支付+优惠券</option>
      <option value="coupon">优惠券模板</option>
    </select>    </td>
  </tr>
  <tr id="showtempfun">
    <td class="right">&nbsp;</td>
    <td><p id="showprice">
        单价：
          <input type="text" name="dprice" value="<?php echo $data['price'];?>" size="5"> 元 &nbsp;&nbsp;&nbsp;
		  <input name="ddiscount[type]" type="radio" value="discount"<?php if($data['discount']['type']=='discount'){echo ' checked="checked"';} ?>>按折扣
		  <input name="ddiscount[type]" type="radio" value="reduce"<?php if($data['discount']['type']=='reduce'){echo ' checked="checked"';} ?>>按立减
		  <input id="adddiscount" type="button" value=" 添加优惠 " />
	  </p>
	  <p id="showdiscount"></p>
	  </td>
  </tr>
  <tr>
    <td class="right">提交成功提示：</td>
    <td> <input type="text" name="successtip" value="<?php echo $data['successtip'];?>"></td>
  </tr>
  <tr>
    <td class="right">提交失败提示：</td>
    <td> <input type="text" name="errtip" value="<?php echo $data['errtip'];?>"></td>
  </tr>
  <tr>
    <td class="right">图文详细页：</td>
    <td style="padding-top:10px; padding-bottom:10px; height:500px"><textarea name="content" style="height:500px; width:670px"><?php echo $data['content'];?></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="submit" id="addpostform" value="提交"> <input type="reset" name="reset" value="取消"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
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

$('#upfiles').click(function(){
	$(window.frames["uploads"].document).find("form input").click();
});

function qfupload(d){
	if(d){$('.showlogo').val(d.upload_path+d.file_name);alert('上传成功！');}
	else{alert('上传失败！');}
}

$('#addpostform').click(function(){
	if($('[name=title]').val().length<3){
		alert('标题过短！');return false;
	}
	if($('[name=keyword]').val().length<3){
		alert('关键词填写不正确！');return false;
	}
	if($('[name=toppic]').val().length<3){
		alert('请上传LOGO！');return false;
	}
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

	    $('#showdiscount').append('<span class="showdiscount'+len+'">至少数量：<input type="text" name="ddiscount[value][num][]" value="" size="3">(填写整数) &nbsp;&nbsp;折扣:<input type="text" name="ddiscount[value][dis][]" value="" size="3">(填写小数) <input type="button" onclick="deldiscount(\'showdiscount'+len+'\')" value=" 删除 " /><br></span>');

		lasttypediscount = $('#showdiscount').html();
	}

	else if(lasttype == 'reduce'){

	    $('#showdiscount').append('<span class="showdiscount'+len+'">购满金额：<input type="text" name="ddiscount[value][num][]" value="" size="3">(填写整数) &nbsp;&nbsp;立减:<input type="text" name="ddiscount[value][dis][]" value="" size="3">(填写整数) <input type="button" onclick="deldiscount(\'showdiscount'+len+'\')" value=" 删除 " /><br></span>');

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
</body>
</html>