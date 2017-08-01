<input type="hidden" name="cat_id" value="" style="display:none;">
<div class="contents_list bg_fff">
    <div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
    <div class="con_right">
        <div class="hottel_name ">
            <div class="">商户名称</div>
            <div class="input_txt"><input type="text" name="brand_name" placeholder="请填写商户名称"/></div>
        </div>
        <div class="address">
            <div class="">优惠券类型</div>
            <div class="input_txt">
                <select class="w_450 card_type_c" name="card_type">
                    <option value="1">抵用券</option>
                    <option value="2">折扣券</option>
                    <option value="3">兑换券</option>
                    <option value="4">储值券</option>
                </select>
            </div>
        </div>

        <?php if($inter_id == 'ALL_PRIVILEGES'){ ?>
            <div class="address">
                <div class="">优惠券类型</div>
                <div class="input_txt">
                    <select class="w_450 card_type_c" name="apply_inter[]">
                        <?php foreach ($publics as $k=>$v){ ?>
                            <option value="<?php echo $v->inter_id; ?>" <?php if( isset($cardinfo['inter_id']) && strstr( $cardinfo['apply_inter'] , $v->inter_id )  ){ echo "selected"; } ?>  ><?php echo $v->name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php } ?>
        <div class="hotel_star clearfix b_week">
            <div class="float_left">渠道类型</div>
            <div class="input_txt input_checkbox p_l_4 w_600">
                <div>
                    <input type="checkbox" id="hotel" name="module[]" value="hotel">
                    <label for="hotel">订房</label>
                </div>
                <div>
                    <input type="checkbox" id="shop" name="module[]" value="shop">
                    <label for="shop">商城</label>
                </div>
                <div>
                    <input type="checkbox" id="soma" name="module[]" value="soma">
                    <label for="soma">套票</label>
                </div>
                <div>
                    <input type="checkbox" checked id="vip" name="module[]" value="vip">
                    <label for="vip">会员</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="contents_list bg_fff">
    <div class="con_left"><span class="block bg_ff503f"></span>优惠券信息</div>
    <div class="con_right">
        <div class="hottel_name ">
            <div class="">优惠券名称</div>
            <div class="input_txt"><input type="text" name="title" placeholder="请填写优惠券名称" /></div>
        </div>
        <div class="hottel_name ">
            <div class="">优惠券副名</div>
            <div class="input_txt"><input type="text" name="sub_title" placeholder="请填写优惠券副名" /></div>
        </div>
        <div class="hottel_name ">
            <div class="">优惠券库存</div>
            <div class="input_txt"><input type="text"  name="card_stock" placeholder="请填写卡券库存" ></div>
        </div>
        <div class="hottel_name" style="display: none">
            <div class="">优惠券f码</div>
            <!--                            <div class="input_txt"><input type="text" name="cat_name" value="" placeholder=""></div>-->
            <div class="input_txt input_checkbox">
                <div>
                    <input type="checkbox" id="is_f" name="is_f" value="t">
                    <label for="is_f">开启f码</label>
                </div>
            </div>
        </div>

        <div class="jingwei clearfix">
            <div class="float_left required">logo</div>
            <input required type="hidden" class="form-control hiddenimg" name="logo_url" id="el_intro_img" placeholder="logo" />
            <div class="input_txt file_img_list">
                <label id="file" class="add_img"><input class="display_none file_img" type="file" /></label>
            </div>
            <div style="color: #DDD;opacity:1;">建议尺寸：200*200，不超过200KB</div>
        </div>

        <div class="hottel_name ">
            <div class=""></div>
        </div>
    </div>
</div>

<div class="contents_list bg_fff">
    <div class="con_left"><span class="block bg_ff503f"></span>优惠券详情</div>
    <div class="con_right">
        <div class="hottel_name ">
            <div class="">使用提醒</div>
            <div class="input_txt"><input type="text" name="notice" placeholder="请填写使用提醒" /></div>
        </div>
        <div class="hottel_name clearfix">
            <div class="f_l">优惠券说明</div>
            <div class="input_txt block_list"><textarea class="w_450" name="card_note" id="" placeholder="请填写优惠券说明" style="width:796px;height:230px;" ></textarea></div>
            <div style="color: red">*仅在后台显示</div>
        </div>
        <div class="hottel_name clearfix">
            <div class="f_l">使用说明</div>
            <div class="input_txt block_list"><textarea name="description"  class="w_450" placeholder="请填写使用说明" ></textarea></div>
        </div>
    </div>
</div>
<div class="contents_list bg_fff">
    <div class="con_left"><span class="block bg_4caf50"></span>优惠券属性</div>
    <div class="con_right">
        <div class="hotel_star">
            <div class="">运营范围</div>
            <div class="input_txt input_radio">
                <div>
                    <input type="radio" id="star_16" checked name="is_online" value="1">
                    <label for="star_16">线上</label>
                </div>
                <div>
                    <input class="btn_number" type="radio" id="star_17" name="is_online" value="2">
                    <label for="star_17">线下</label>
                </div>
            </div>
        </div>
        <div class="hotel_star">
            <div class="">优惠券转赠</div>
            <div class="input_txt input_radio">
                <div>
                    <input type="radio" id="star_26" checked name="can_give_friend" value="f">
                    <label for="star_26">不支持</label>
                </div>
                <div>
                    <input class="" type="radio" id="star_37" name="can_give_friend" value="t">
                    <label for="star_37">支持</label>
                </div>
            </div>
        </div>

        <div class="hottel_name ">
            <div class="">消费密码</div>
            <div class="input_txt"><input type="text" name="passwd" /></div>
        </div>
        <div class="hottel_name" style="display: none;">
            <div class="">页面属性</div>
            <div class="input_txt"><input type="text" name="page_config" placeholder="请填写页面属性" /></div>
        </div>
        <div class="hottel_name ">
            <div class="">通用链接</div>
            <div class="input_txt"><input type="text" name="header_url" placeholder="请填通用链接" /></div>
        </div>
        <div class="hottel_name ">
            <div class="">订房链接</div>
            <div class="input_txt"><input type="text" name="hotel_header_url" placeholder="请填订房链接" /></div>
        </div>
        <div class="hottel_name ">
            <div class="">套票链接</div>
            <div class="input_txt"><input type="text" name="soma_header_url" placeholder="请填使用地址" /></div>
        </div>
        <div class="hottel_name ">
            <div class="">商城链接</div>
            <div class="input_txt"><input type="text" name="shop_header_url" placeholder="请填使用地址" /></div>
        </div>
        <div class="hottel_name least_cost">
            <div class="">起用金额</div>
            <div class="input_txt"><input type="text" name="least_cost" placeholder="请填写抵用券起用金额" /></div>
        </div>
        <div class="hottel_name over_limit">
            <div class="">抵用上限</div>
            <div class="input_txt"><input type="text" name="over_limit" placeholder="优惠劵抵用上限金额" /></div>
        </div>
        <div class="hottel_name reduce_cost">
            <div class="">抵减金额</div>
            <div class="input_txt"><input type="text" name="reduce_cost" placeholder="请填写抵用券减免金额" /></div>
        </div>
        <div class="hottel_name discount" style="display: none;">
            <div class="">打折额度</div>
            <div class="input_txt"><input type="text" disabled name="discount" placeholder="请填写折扣劵打折额度" /></div>
        </div>
        <div class="hottel_name exchange" style="display: none;">
            <div class="">兑换券说明</div>
            <div class="input_txt block_list"><textarea disabled name="exchange"  class="w_450" placeholder="请填写兑换券说明" ></textarea></div>
        </div>
        <div class="hottel_name money" style="display: none;">
            <div class="">储值券金额</div>
            <div class="input_txt"><input type="text" disabled name="money" placeholder="请填写储值券金额" /></div>
        </div>
        <div class="hottel_name ">
            <div class="">备注</div>
            <div class="input_txt"><input type="text" name="remark" placeholder="请填写备注" /></div>
            <div style="color: red">*仅在后台显示</div>
        </div>
        <div class="hottel_name ">
            <div class="">领取时间</div>
            <div class="input_txt"><span class="t_time"><input name="time_start" type="text" autocomplete="off" id="datepicker" class="datepicker moba" placeholder="<?php echo date('Y-m-d');?>" /></span>
                <font>至</font>
                <span class="t_time"><input name="time_end" type="text" autocomplete="off" id="datepicker2" class="datepicker moba" placeholder="<?php echo date('Y-m-d');?>" /></span></div>
        </div>
        <div class="hottel_name">
            <div class="">起用时间</div>
            <div class="input_txt"><input id="datepicker3" class="datepicker" type="text" autocomplete="off" name="use_time_start" placeholder="<?php echo date('Y-m-d');?>" /></div>
        </div>
        <div class="jingwei">
            <div class="">失效模式</div>
            <div class="input_txt">
                <select style="width:450px;" class="end_model" name="use_time_end_model">
                    <option value="g">固定失效时间</option>
                    <option value="y">领取后存活时间</option>
                </select>
            </div>
        </div>
        <div class="hottel_name use_time_end_c">
            <div class="">失效时间</div>
            <div class="input_txt"><input type="text" id="datepicker4" class="datepicker" autocomplete="off" name="use_time_end" placeholder="<?php echo date('Y-m-d');?>" /></div>
        </div>

        <div class="hottel_name use_time_end_d" style="display: none;">
            <div class="">使用失效天数</div>
            <div class="input_txt"><input type="number" disabled name="use_time_end_day" /></div>
        </div>
    </div>
</div>
<div class="contents_list bg_fff">
    <div class="con_left"><span class="block bg_4caf50"></span>其他</div>
    <div class="con_right">
        <div class="jingwei">
            <div class="">优惠券状态</div>
            <div class="input_txt">
                <select style="width:450px;" name="is_active">
                    <option value="t">启用</option>
                    <option value="f">停用</option>
                </select>
            </div>
        </div>
    </div>
</div>
