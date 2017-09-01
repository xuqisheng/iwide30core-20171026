
    <div class="pad3 relative bg_F8F8F8">
        <div class="pull_searchbox bg_fff">
            <em class="iconfont">&#x2C;</em>
            <!--<form onSubmit="return false" style="display:inline-block;width:80%">-->
            <input type="search" placeholder="搜地点/搜地标/搜车站/搜地铁站" class="search" id="search2">
            <!--</form>-->
            <span class="color_999 h22" onClick="toclose()">取消</span>
        </div>
        <div class="result_list" style="display:none">
            <div class="h24 pad3 bg_E4E4E4" length style="display:none">搜索到<span class="color_main">0</span>家酒店</div>
            <ul class="address_list">
                <li>广州</li>
                <li>深圳</li>
            </ul>
        </div>
    </div>
<?php if (!empty($result)){?>
<?php if (!empty($result['tag'])){?>

<div class="content_pull float tab_list bg_fff bd_right">
	<ul class="address_list color_main">
	<?php if (!empty($result['tag'])){ $k=1;?>
        <?php foreach ($result['tag'] as $t){?>
        <li><span><?php echo $t['type_name']?></span><p <?php if($k==1) echo 'class="bg_main"';?>></p></li>
        <?php $k=0;}?>
    <?php }?>
	</ul>
</div>

<?php foreach ($result['tag'] as $t){?>
<div class="content_pull get_result scroll bg_fff h24">
	<div class="title"><p><?php echo $t['type_name'];?></p></div>
	<ul class="address_list">
	<?php foreach ( $t['items'] as $i){?>
		<li filter='tag' code='<?php echo $i['item_id'];?>'><?php echo $i['name'];?></li>
		<?php }?>
	</ul>
</div>
<?php }?>
<?php }?>
<?php }?>