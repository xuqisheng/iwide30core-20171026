<div class="pull_searchbox">
	<input type="search" placeholder="搜地点/搜地标/搜车站/搜地铁站" class="search" id="search2">
	<div class="result_list" style="display:none">
		<div class="h4" style="padding:3%;display:none;">搜索到<span class="ui_color">0</span>个结果</div>
		<ul class="address_list">
			<li>广州</li>
			<li>深圳</li>
		</ul>
	</div>
</div>
<?php if (!empty($result)){?>
<div class="content_pull float tab_list" style='display: none' >
	<ul class="address_list">
	<?php if (!empty($result['region'])){?>
		<li class="cur"><span><?php echo $result['region']['filter_type_name']?></span></li>
		<?php }?>
		<?php //暂只显示，待确认 ||1
 			if (!empty($result['price'])){?>
 			<li><span>特价</span></li>
 		<?php }?>
		<?php if (!empty($result['land_mark'])){foreach ($result['land_mark'] as $lm){?>
		<li><span><?php echo $lm['filter_type_name'];?></span></li>
		<?php }}?>
		
	</ul>
</div>
<?php if (!empty($result['region'])){?>
<div class="content_pull get_result">
	<div class="title"><p><?php echo $result['region']['filter_type_name'];?></p></div>
	<ul class="address_list">
	<?php foreach ( $result['region']['marks'] as $region){?>
		<li filter='region' code='<?php echo $region['filter_id'];?>'><?php echo $region['filter_name'];?></li>
		<?php }?>
	</ul>
</div>
<?php }?>
<?php if (!empty($result['price'])){?>
<div class="content_pull get_result">
	<div class="title"><p><?php echo $result['price']['filter_type_name'];?></p></div>
	<ul class="address_list">
	<?php foreach ( $result['price']['marks'] as $price){?>
		<li filter='price' code='<?php echo $price['filter_id'];?>'><?php echo $price['filter_name'];?></li>
		<?php }?>
	</ul>
</div>
<?php }?>
<?php if (!empty($result['land_mark'])){foreach ($result['land_mark'] as $lm){?>
    <div class="content_pull get_result">
    	<?php foreach ($lm['marks'] as $lmk){ ?><div class="title"><p><?php echo $lmk['filter_type_name']?></p></div>
        <ul class="address_list">
        	<?php foreach ($lmk['filters'] as $filter){?>
        	<li  filter='land_mark' code='<?php echo $filter['filter_id'];?>'><?php echo $filter['filter_name'];?></li>
        	<?php }?>
        </ul>
<?php }?>
    </div>
<?php }}?>
<?php }?>