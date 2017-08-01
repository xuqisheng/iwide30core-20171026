
<div class="box-body" >
	<br/>
	<div class="col-sm-3 col-sm-offset-1 inline">
		<img src="<?php echo $scancode_url; ?>" alt="用微信扫一扫" class="img-thumbnail" width="250" />
	</div>
	<div class="col-sm-5 inline">
		<p><b>注意事项：</b></p>
		<ol>
			<li><p>扫一扫<code>左边</code>二维码进行微信账号授权</p></li>
			<li><p>授权过的微信账号，才可进行扫码核销等操作</p></li>
			<li><p>授权后，该微信号所做的操作将等同于本授权账号的操作，因该微信号操作<code>造成之损失由本账号承担</code>，请谨慎使用</p></li>
			<li><p>一旦进行扫码操作，即等同于同意以上内容</p></li>
			<li><p>如需解除关联，请在下方删除该微信号</p></li>
			<!-- <li><p>扫码授权成功后，扫一扫<code>右边</code>二维码进行核销等操作</p></li> -->
		</ol>
	</div>
	<div class="col-sm-2 hide">
		<p><img src="<?php echo $consume_url; ?>" alt="用微信扫一扫" class="img-thumbnail" width="250" /></p>
		<p>授权后，再扫此二维码进行核销操作</p>
	</div>
</div>
<!--
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-switch/bootstrap-switch.min.css'>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-switch/bootstrap-switch.min.js"></script>
-->
<!-- /.box-body -->
<div class="box-footer ">
	<div class=" col-sm-12 ">
		<table class="table table-striped table-hover">
			<thead>
			<tr role="row">
<?php 
$label_items= $authid_model->attribute_labels();
$status_label= $authid_model->get_status_label();
$grid_field= array('auth_id', 'headimgurl', 'nickname', /*'openid',*/'apply_time','auth_time','delete_time','last_operation','status');
				$num_field= count($grid_field);
				foreach($grid_field as $v): 
				?>
					<th><?php echo $label_items[$v]; ?></th>
				<?php endforeach; ?><th class="text-center">操作</th>
			</tr>
			</thead>
			<tbody>
<?php foreach($authid_list as $lk=> $lv): ?>
				<tr>
	<?php foreach($grid_field as $v): 
			if($v=='status'): 
				$tmp= isset($status_label[$lv[$v]])? $status_label[$lv[$v]]: '-'; 
			elseif($v=='openid'): 
				$tmp= hide_string_prefix($lv[$v],6); 
			elseif($v=='nickname'): 
				$tmp= empty($lv[$v])? '<i>获取失败</i>': $lv[$v]; 
			elseif($v=='headimgurl'): 
				$tmp= empty($lv[$v])? base_url(FD_PUBLIC). '/'. $tpl. '/dist/img/ucenter_headimg.jpg': $lv[$v]; 
		        $tmp= "<img src='{$tmp}' alt='{$lv['openid']}' title='{$lv['openid']}' width='60' />";
			else: 
				$tmp= $lv[$v]? $lv[$v]: '-'; 
			endif; 
	?>
					<td><?php echo $tmp; ?></td>
	<?php endforeach; ?>
					<td>
					<?php if($model->m_get('admin_id')== $lv['admin_id']): 
    					$toggle_url= EA_const_url::inst()->get_url('*/*/authid_handle', array('do'=>'toggle', 'ids'=> $lv['auth_id'], 'aid'=> $lv['admin_id']) );
    					$delete_url= EA_const_url::inst()->get_url('*/*/authid_handle', array('do'=>'remove', 'ids'=> $lv['auth_id'], 'aid'=> $lv['admin_id']) );
    					if( $lv['status']== $authid_model::STATUS_APPLY ): ?>
    						<a href="<?php echo $toggle_url; ?>"><i class="fa fa-toggle-off"></i> 审核</a>
    						
    					<?php elseif( $lv['status']== $authid_model::STATUS_CHECK ): ?>
    						<a href="<?php echo $toggle_url; ?>"><i class="fa fa-toggle-on"></i> 取消</a>
    					
    					<?php endif; ?>
    						&nbsp;&nbsp;&nbsp;<a href="<?php echo $delete_url; ?>"><i class="fa fa-remove"></i> 删除</a>
    				<?php else: ?>
    				    <i style="color:gray;" >--</i>
    				<?php endif;?>
					</td>
				</tr>
<?php endforeach; ?>
			</tbody>
		</table>
		
		<div><hr><p>常见使用问题：</p><ol>
		    <li><code>扫码显示“已经授权”但看不到授权记录？</code></li>
		    <p>微信用户在同一个公众号下，只能跟一个后台管理员捆绑。如显示“已经授权”则可以直接扫右边码进入操作。</p>
		</ol></div>
	</div>
</div>