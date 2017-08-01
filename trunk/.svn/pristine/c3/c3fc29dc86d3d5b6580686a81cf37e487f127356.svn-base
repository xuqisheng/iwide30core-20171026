<div class="each_line">
	<table class="table2">
		<tr>
			<td colspan="5">操作记录</td>
		</tr>
		<tr>
			<th>时间</th>
			<th>来源</th>
			<th>账号</th>
			<th style="max-width:250px;">操作记录</th>
			<th>IP地址</th>
		</tr>
		<?php if(!empty($logs)){foreach ($logs as $k => $log) {?>
		<tr>
			<td><?php echo $log['addtime'];?></td>
			<td><?php echo $log['operate_type'];?></td>
			<td><?php echo $log['nickname'];?></td>
			<td style="max-width:250px;"><?php echo $log['operate_desc'];?></td>
			<td><?php echo $log['ip'];?></td>
		</tr>
		<?php }}?>
	</table>
</div>
<flex class="each_line" style="justify-content:space-between;margin-bottom:20px;">
	<ib style="margin:10px;">当前共<?php echo $count;?>条／共<?php echo $pages;?>页数据</ib>
	<ib style="margin:10px;">
		<?php echo $page;?>
	</ib>
</flex>