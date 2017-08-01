<?php if (!empty($result)){?>
<?php if (!empty($result['tag'])){?>

<div class="clearfix pad_t60">
    <div class="results_search_left h30 color3 float">
        <p class="active">区域<span class="shadow_b"></span></p>
        <?php if (!empty($result['tag'])){ $k=1;?>
            <?php foreach ($result['tag'] as $t){?>
            <p <?php if($k==1) echo 'class="active"';?>>商圈<span class="shadow_b"></span></p>
            <?php $k=0;}?>
        <?php }?>
    </div>
    <div class="results_search_right h28 color3 float pad_l60 bd_left" id="region_search">
        <?php foreach ($result['tag'] as $t){?>
            <div>
                <?php foreach ( $t['items'] as $i){?>
                    <p  filter='tag' c class="bd_bottom"  code='<?php echo $i['item_id'];?>'><?php echo $i['name'];?> <em class="iconfont floatr main_color1">&#xE010;</em></p>
                <?php }?>
            </div> 
        </div>
        <?php }?>
    </div>
</div>
<div class="clearfix mar_t60 mar_b40">
    <div class="results_search_left float">
        <div class="center h28 mar_t30" id="search_react">重&nbsp;&nbsp;置</div>
    </div>
    <div class="results_search_right float">
        <a class="iconfont button mar_b40 spacing h34" href="javascript:;" id="search_sure">确定</a>
    </div>
</div>


<?php }?>
<?php }?>