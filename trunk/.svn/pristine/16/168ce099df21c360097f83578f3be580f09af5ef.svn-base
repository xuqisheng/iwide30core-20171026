<div class="contents_list bg_fff">
    <div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
    <div class="con_right">
        <div class="hottel_name ">
            <div class="">规则名称</div>
            <div class="input_txt">
                <input type="text" name="rule_title" value="">
            </div>
        </div>
        <div class="hotel_star">
            <div class="">领取渠道</div>
            <div class="input_txt input_radio">
                <?php foreach ($channel as $k=>$v):?>
                    <div>
                        <input type="radio" id="<?=$k?>" class="channel" <?php if($k=='gazeini'):?>checked<?php endif;?> name="active" value="<?=$k?>">
                        <label for="<?=$k?>"><?=$v?></label>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
<div class="contents_list bg_fff">
    <div class="con_left"><span class="block bg_ff503f"></span>礼包关联</div>
    <div class="con_right">
        <div class="hotel_star">
            <div class="">赠送类型</div>
            <div class="input_txt input_radio">
                <div>
                    <input class="star_66" type="radio" id="star_66" name="is_package" value="f" checked="">
                    <label for="star_66">卡券(默认)</label>
                </div>
                <div>
                    <input class="star_77" type="radio" id="star_77" name="is_package" value="t">
                    <label for="star_77">礼包</label>
                </div>
            </div>
        </div>
        <div class="hotel_star clearfix b_week" style="display:none;">
            <div class="float_left">礼包选择</div>
            <div class="input_txt">
                <select style="width:450px;" name="package_id" disabled>
                    <option value=""  >--请选择礼包--</option>
                    <?php foreach ($package_list as $key => $value):?>
                        <option value="<?=$value['package_id']?>"><?=$value['name']?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="hottel_name b_s_data" style="display: block;">
            <div class="">卡券选择</div>
            <div class="input_txt">
                <select style="width:450px;" name="card_id">
                    <option value=""  >--请选择卡券--</option>
                    <?php foreach ($cardlist as $key => $value):?>
                        <option value="<?=$value['card_id']?>"><?=$value['title']?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="contents_list bg_fff">
    <div class="con_left"><span class="block bg_ff503f"></span>规则属性</div>
    <div class="con_right">
        <div class="jingwei">
            <div class="">限领次数</div>
            <div class="input_txt relative">
                <input type="number" name="frequency" value="">
                <span class="absolute" style="top:1px;right:20px;">次/用户</span>
            </div>
        </div>
        <div class="hotel_star">
            <div class="">规则状态</div>
            <div class="input_txt input_radio">
                <div>
                    <input type="radio" id="star_16" name="is_active" checked="" value="t">
                    <label for="star_16">启用</label>
                </div>
                <div>
                    <input class="btn_number" type="radio" id="star_17" name="is_active" value="f">
                    <label for="star_17">禁用</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="contents_list bg_fff thumb-banner" style="display: none;">
    <div class="con_left"><span class="block bg_ff503f"></span>图片</div>
    <div class="con_right" style="margin-left: 30px;">
        <div class="jingwei clearfix">
            <input required type="hidden" class="form-control hiddenimg" name="banner" id="el_intro_img" placeholder="banner" />
            <div class="input_txt file_img_list">
                <label id="file" class="add_img"><input class="display_none file_img" type="file" /></label>
            </div>
            <div style="color: #DDD;opacity:1;">建议尺寸：750*660，不超过200KB</div>
        </div>

        <div class="hottel_name ">
            <div class=""></div>
        </div>
    </div>
</div>