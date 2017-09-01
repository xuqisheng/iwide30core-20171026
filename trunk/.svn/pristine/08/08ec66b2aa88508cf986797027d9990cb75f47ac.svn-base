<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'header.php';

//print_r($pages);

?>
<body>
<table align="center" cellpadding="0" cellspacing="0" width="800" style="height:30px; border:#CCCCCC solid 1px; margin-bottom:10px">
  <tr>
    <td>&nbsp;&nbsp;<a href="/index.php/chat/superform/addform">添加表单</a> </td>
  </tr>
</table>
<table class="showhotel" align="center" cellpadding="0" cellspacing="0" width="800">
  <tr>
    <th>标题名称</th>
	<th>关键词</th>
	<th>创建时间</th>
	<th>提交总数</th>
	<th width="330">操作</th>
  </tr>
  <?php foreach($data as $v){ ?>
  <tr>
    <td><a href="/index.php/chat/fapi?iad=<?php echo $v['id'];?>" target="_blank"><?php echo $v['title'];?></a></td>
    <td><?php echo $v['keyword'];?></td>
    <td><?php echo date('Y-m-d H:i:s',$v['addtime']);?></td>
    <td><?php echo $v['addnum'];?></td>
    <td><a href="showinfo?action=show&iad=<?php echo $v['id'];?>">提交信息管理</a> | 
	<a href="forminput?action=show&iad=<?php echo $v['id'];?>">输入项管理</a> | 
	<a href="formcount?action=show&iad=<?php echo $v['id'];?>">表单统计</a> | 
	<a href="addform?action=upd&iad=<?php echo $v['id'];?>">修改</a> | 
	<a href="javascript:void(0)" onClick="if(confirm('确定要删除吗?')){location.href='?action=del&iad=<?php echo $v['id'];?>';}">删除</a></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="5"><?php echo $pages['html'];?></td>
  </tr>
</table>


</body>
</html>