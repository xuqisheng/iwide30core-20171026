<input type="hidden" name="card_rule_id" value="<?=$card_rule_id?>" style="display:none;">
<div class="contents_list bg_fff">
    <div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
    <div class="con_right">
        <div class="hottel_name ">
            <div class="">规则名称</div>
            <div class="input_txt">
                <input type="text" name="rule_title" value="<?=$rule_title?>">
            </div>
        </div>
        <div class="hotel_star">
            <div class="">领取渠道</div>
            <div class="input_txt input_radio">
                <?php foreach ($channel as $k=>$v):?>
                    <div>
                        <input type="radio" id="<?=$k?>" class="channel" name="active" <?php if($active==$k):?>checked<?php endif?> value="<?=$k?>">
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
                    <input class="star_66" type="radio" id="star_66" name="is_package" <?php if($is_package=='f'):?>checked<?php endif?> value="f" />
                    <label for="star_66">卡券(默认)</label>
                </div>
                <div>
                    <input class="star_77" type="radio" id="star_77" name="is_package" <?php if($is_package=='t'):?>checked<?php endif?> value="t">
                    <label for="star_77">礼包</label>
                </div>
            </div>
        </div>
        <div class="hotel_star clearfix b_week" <?=$pk_display_t?>>
            <div class="float_left">礼包选择</div>
            <div class="input_txt">
                <select style="width:450px;" name="package_id" <?=$pk_disabled_t?>>
                    <option value=""  >--请选择礼包--</option>
                    <?php foreach ($package_list as $key => $value):?>
                        <option value="<?=$value['package_id']?>" <?php if($package_id==$value['package_id']):?>selected<?php endif?>><?=$value['name']?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="hottel_name b_s_data" <?=$pk_display_f?>>
            <div class="">卡券选择</div>
            <div class="input_txt">
                <select style="width:450px;" name="card_id" <?=$pk_disabled_f?>>
                    <option value=""  >--请选择卡券--</option>
                    <?php foreach ($cardlist as $key => $value):?>
                        <option value="<?=$value['card_id']?>" <?php if($card_id==$value['card_id']):?>selected<?php endif?>><?=$value['title']?></option>
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
                <input type="number" name="frequency" value="<?=$frequency?>">
                <span class="absolute" style="top:1px;right:20px;">次/用户</span>
            </div>
        </div>
        <div class="hotel_star">
            <div class="">规则状态</div>
            <div class="input_txt input_radio">
                <div>
                    <input type="radio" id="star_16" name="is_active" <?php if($is_active=='t'):?>checked<?php endif?> value="t">
                    <label for="star_16">启用</label>
                </div>
                <div>
                    <input class="btn_number" type="radio" id="star_17" name="is_active" <?php if($is_active=='f'):?>checked<?php endif?> value="f">
                    <label for="star_17">禁用</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="contents_list bg_fff thumb-banner" <?php if($active!='gaze'):?>style="display: none;"<?php endif?>>
    <div class="con_left"><span class="block bg_ff503f"></span>图片</div>
    <div class="con_right" style="margin-left: 30px;">
        <div class="jingwei clearfix">
            <input required type="hidden" class="form-control hiddenimg" name="banner" id="el_intro_img" placeholder="banner" value="<?=$banner?>" />
            <div class="input_txt file_img_list">
                <?php if(!empty($banner)):?>
                    <div class="add_img_box" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="<?=$banner?>"/>
                        <div class="img_close" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div>
                    </div>
                <?php endif;?>
                <label id="file" class="add_img"><input class="display_none file_img" type="file" /></label>
            </div>
            <div style="color: #DDD;opacity:1;">建议尺寸：750*660，不超过200KB</div>
        </div>

        <div class="hottel_name ">
            <div class=""></div>
        </div>
    </div>
</div>