<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'header.php';
?>
<script type="text/javascript" src="http://www.sud.cn/template/pjframe/pujun.js"></script>
<body>
<table align="center" cellpadding="0" cellspacing="0" width="800" style="height:30px; border:#CCCCCC solid 1px; margin-bottom:10px">
  <tr>
    <td></td>
  </tr>
</table>
<table class="showhotel" align="center" cellpadding="0" cellspacing="0" width="800">
  <tr>
    <th width="80">提交时间</th>
	<th>金额</th>
	<!--<th>用户名</th>
	<th>用户电话</th>-->
	<?php foreach($datainput as $v){ ?>
	<th><?php echo $v['iname'];?></th>
	<?php } ?>
	<th width="70">状态</th>
  </tr>
  <?php foreach($data as $k){ ?>
  <tr>
    <td><a href="#/index.php/chat/fapi/addresult?iad=<?php echo $k['id'];?>" target="_blank"><?php echo date('m-d H:i',$k['addtime']);?></a></td>
	<td><?php echo $k['payed'];?></td>
    <!--<td><?php echo $k['username'];?></td>
    <td><?php echo $k['phone'];?></td>-->
	<?php $subinfo =unserialize($k['subinfo']);
	foreach($datainput as $v){ ?>
    <td>&nbsp;<?php if(!empty($subinfo[$v['id']])){echo $subinfo[$v['id']];}?></td>
	<?php } ?>
    <td><?php if($k['status']==0 || $k['status']==2){ ?> <?php if($k['checkresult']){echo '已拒';}else{echo '未支付';};?> <?php }?>
	    <?php if($k['status']==3){ ?>已支付<?php }?>
	</td>
  </tr>
  <?php } ?>
</table>

<table cellpadding="">
<tr>
<td style="width:250px"></td>
</tr>
</table>

<script type="text/javascript">
function check(s,id,r){
	if(s==3){
		if(r){
		    if(!confirm('确认设为已审吗？上次拒绝的原因是：'+r)){
				return false;
			}
		}
		$.post('/index.php/chat/superform/showinfo?action=check&iad=<?php echo $_GET['iad'];?>',{s:s,id:id},function(d){
			if(d==1){
				alert('审核通过');location.href = location.href;
			}
		});
	}
    else {
	    pjcon({message:"请输入拒绝的原因：<br><br><input type=text id=reason />",height:180,title:"请输入拒绝的原因！",handler:function(tp){
		    var v = $("#reason").val();
			if(tp=='ok'){
				if(v==""){
					alert("请填写拒绝原因！");
				}
				else{
					$.post('/index.php/chat/superform/showinfo?action=check&iad=<?php echo $_GET['iad'];?>',{s:s,id:id,reason:v},function(d){
						if(d==1){
							alert('拒绝操作成功！');
							location.href = location.href;
						}
					});
				}
			}
		}});
	}
}

</script>



</body>
</html>