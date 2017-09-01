<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'header.php';
$common['isshow']['1'] = '显示';
$common['isshow']['0'] = '隐藏';
?>
<body>
<table align="center" cellpadding="0" cellspacing="0" width="800" style="height:30px; border:#CCCCCC solid 1px; margin-bottom:10px">
  <tr>
    <td><span style="float:left">&nbsp;&nbsp;</span><a href="/index.php/chat/superform/suform" style="float:left">返回上一页</a><a href="addinput?action=add&iad=<?php echo $_GET['iad'];?>" style="float:right; margin-right:10px">新增字段</a></td>
  </tr>
</table>
<table class="showhotel" align="center" cellpadding="0" cellspacing="0" width="800">
  <tr>
    <th>显示名称</th>
	<th>输入类型</th>
	<th>效果预览</th>
	<th>排序</th>
	<th>是否显示</th>
	<th width="100">操作</th>
  </tr>
  <?php foreach($data as $v){ ?>
  <tr>
    <td>&nbsp;<?php echo $v['iname'];?></td>
    <td>&nbsp;<?php echo $v['itype'];?></td>
    <td style="line-height:41px"><?php echo $v['iname'];?>:<?php if($v['itype']=='select'){echo '<select><option>预览1</option><option>预览2</option></select>';}
	else if($v['itype']=='textarea'){echo '<textarea cols="10" rows="1" style="height:28px;position:absolute;margin-top:3px;"></textarea>';}
	else if($v['itype']=='date'){echo '<input type="text" value="2015-08-01">';}
	else {echo '<input type="'.$v['itype'].'">';}
	?></td>
    <td>&nbsp;<?php echo $v['listorder'];?></td>
	<td>&nbsp;<?php echo $common['isshow'][$v['isshow']];?></td>
    <td><a href="addinput?action=upd&iid=<?php echo $v['id'];?>&cid=<?php echo $v['cid'];?>">修改</a> | 
	<a href="javascript:void(0)" onClick="if(confirm('确定要删除吗?')){location.href='?action=del&iid=<?php echo $v['id'];?>&iad=<?php echo $v['cid'];?>';}">删除</a></td>
  </tr>
  <?php } ?>
</table>


</body>
</html>