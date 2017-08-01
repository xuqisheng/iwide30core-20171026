<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate12.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php 
/* 顶部导航 */
echo $block_top;
?>

<?php 
/* 左栏菜单 */
echo $block_left;
?>
<style>
html, body, .wrapper{min-width:1480px;}
textarea{border:1px solid #d7e0f1;}
.banner{height:50px;width:100%;line-height:50px;border-bottom:1px solid #d7e0f1;}
.contents{padding:10px 20px 20px 20px;}
.contents_list{display:table;width:100%;border:1px solid #d7e0f1;margin-bottom:10px;}
.hotel_star >div:nth-of-type(2) >div,.con_right >div >div{display:inline-block;}
.con_left{width:150px;text-align:center;border-right:1px solid #d7e0f1;display:table-cell;vertical-align:middle;}
.con_right{padding:20px 0 20px 0px;}
.con_right>div{margin-bottom:12px;}
.con_right >div >div:nth-of-type(1){width:115px;height:30px;line-height:30px;text-align:center;}
.input_txt{height:30px;line-height:30px;}
.input_txt >input{height:30px;line-height:30px;border:1px solid #d7e0f1;width:450px;text-indent:3px;}
.input_txt >select{height:30px;line-height:30px;display:inline-block;border:1px solid #d7e0f1;background:#fff;margin-right:20px;padding:0 8px;}
.input_radio >div{margin-right:28px;}
.input_radio >div >input{display:none;}
.input_radio >div >input+label{font-weight:normal;text-indent:25px;background:url(http://test008.iwide.cn/public/js/img/radio1.png) no-repeat center left;background-size:13%;width:155px;height:30px;line-height:30px;}
.input_radio >div >input:checked+label{background:url(http://test008.iwide.cn/public/js/img/radio2.png) no-repeat center left;background-size:13%;}
.block{display:inline-block;height:18px;width:4px;vertical-align: middle;margin-right:5px;}
.introduce{width:450px;height:150px;margin-left:4px;resize:vertical;}
.add_img{width:77px;height:77px;background:url(http://test008.iwide.cn/public/js/img/214598012363739107.png) no-repeat;background-size:100%;margin-right:20px;float:left;}

.input_checkbox >div >input{display:none;}
.input_checkbox >div >input+label{font-weight:normal;text-indent:25px;background:url(http://test008.iwide.cn/public/js/img/bg.png) no-repeat center left;background-size:15%;width:110px;height:30px;line-height:30px;}
.input_checkbox >div >input:checked+label{background:url(http://test008.iwide.cn/public/js/img/bg2.png) no-repeat center left;background-size:15%;}

.fom_btn{background:#ff9900;color:#fff;outline:none;border:0px;padding:6px 25px;border-radius:3px;margin:auto;display:block;}
.add_img_box:hover > .img_close{display:block !important;cursor:pointer;}
#file >input{text-indent:-9999px; height:80px;line-height:60px;width:80px;background-image:url("http://test008.iwide.cn/public/js/img/upload.png");}
.f_l{float:left;}
.block_list{margin-left:4px;}
.block_list>div{margin-bottom:10px;}
.block_list>div:last-child{margin-bottom:0px;}
.clearfix:after{content:" ";display:block;clear:both;height:0;}
.btn_number+label{width:70px !important;background-size:30% !important;}
.btn_number:checked+label{background-size:30% !important;}
.btn_number+label+div{display:none}
.btn_number:checked+label+div{display:inline-block;}
.w_450 .dropdown-toggle{background:#fff;border:0px;}
.k_contents{min-width:680px;}
.k_contents td{border:1px solid #d7e0f1;text-align:center;height:35px;padding:0 10px;}
.k_contents td >input{border:0px;height:100%;width:100px;text-align:center;padding:0 0px; outline:none}
.add_commodity{display:block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;}
.del_btn{margin-left:10px;display:inline-block;}
.m_b_35{margin-bottom:20px;}
.m_b_15{margin-bottom:8px;}
.del_btn,.add_commodity{cursor:pointer;}
input[type=number] {  
    -moz-appearance:textfield;  
}  
input[type=number]::-webkit-inner-spin-button,  
input[type=number]::-webkit-outer-spin-button {  
    -webkit-appearance: none;  
    margin: 0;  
}


/*  上传图片 */
.addimg{display:inline-block; border:1px solid #dae0e9;}
.addimg>div{ overflow:hidden; position:relative; width:45px; height:45px;}
/*.addimg{ background:url(../img/add.png) center center no-repeat; background-size:59%}*/
.addimg img{position:absolute;width:100%; min-height:100%; left:0; z-index:5}
.addimg input{ position:absolute; left:100%;top:100%; z-index:0; opacity:0}

/*右上角删除按钮，父元素需要做relative*/
.candelete{position:relative}
.candelete del{
    text-decoration:none;
    cursor:pointer;
    position:absolute; top:-8px; right:-8px; z-index:10;
}
.candelete del:after{
    content: "\f057";
    font: normal normal normal 14px/1 FontAwesome;
    font-size: 16px;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.candelete:hover del:after{color:#f00}

.fixed_box{position:fixed;top:36%;left:48%;z-index:9999;border:1px solid #d7e0f1;border-radius:5px;padding:1% 2%;display:none;}
.tile{font-size:15px;text-align:center;margin-bottom:4%;}
.f_b_con{font-size:13px;text-align:center;margin-bottom:8%;width:260px;}
.new_mb,.n_title >div:nth-of-type(3) a,.cancel,.confirms{cursor:pointer;}
</style>
<div class="fixed_box bg_fff">
    <div class="tile">商品详情</div>
    <div class="f_b_con">商品详情不能为空</div>
    <div class="h_btn_list clearfix" style="">
        <div class="actives confirms">确认</div>
        <div class="cancel f_r">取消</div>
    </div>
</div>
<div class="over_x">
    <div class="content-wrapper" style="min-width: 1050px; min-height: 775px;">
        <div class="banner bg_fff p_0_20">抢尾房</div>
        <?php echo $this->session->show_put_msg();  ?>
        <div class="contents">
            <form action="<?php echo $form_url; ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $group['id']; ?>" style="display:none;">
                <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>" style="display:none;">
                <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
                    <div class="con_right">
                        <div class="hottel_name ">
                            <div class="requireds">公众号</div>
                            <div class="input_txt"><input required type="text" name="name" value="<?php echo $group['info']['name']; ?>"></div>
                        </div>
                        <div class="address">
                            <div class="requireds">活动名称</div>
                            <div class="input_txt"><input required type="text" name="name" value="<?php echo $group['name']; ?>"></div>
                        </div>
                        <div class="jingwei clearfix img_upload">
                            <div class="float_left requireds">首页背景图</div>
                            <input class="face_img" type="hidden" id="bg_img" name="bg_img"  value="<?php echo $group['bg_img']; ?>" >
                            <div class="file_img_list">
                                <div class="clearfix">
                                    <input type="hidden" required name="bg_img" id="el_face_img" value='<?php echo $group['bg_img']; ?>'>
                                    <div id="face_img" class="addimgs trim">
                                        <?php if($group['bg_img']):?>
                                        <div class="addimg candelete"><del></del><div><img src="<?php echo $group['bg_img'];?>"/></div></div>
                                        <?php endif;?>
                                    </div>
                                    <div id="face_img_add" class="img_adds"></div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="hottel_name" style="height:auto;">
                            <div class="float_left requireds">商品详情</div>
                            <div>
                                <div class="commodity_list">
                                    <?php  foreach ($extra as $item): ?>
                                    <div class="commodity_item m_b_35">
                                        <div class="m_b_15 candelete">
                                            <span>
                                                <select class="commodity_con selectpicker border_1 w_450" data-live-search="true">
                                                    <?php foreach ($productList as $product): ?><option value="<?php echo $product['product_id']; ?>" <?php if($item['product_id'] == $product['product_id']):?> selected <?php endif; ?> ><?php echo $product['name']; ?></option><?php endforeach; ?>
                                                </select>
                                            </span>
                                            <del class="del_btn" style="position:static; margin-left:5px;"></del>
                                        </div>
                                        <div class="">
                                            <div class="m_b_15">库存情况</div>
                                            <div>
                                                <table class="k_contents" style="">
                                                    <thead>
                                                    <tr class="week">
                                                        <td>星期</td>
                                                        <td><span data-id="1">星期一</span></td>
                                                        <td><span data-id="2">星期二</span></td>
                                                        <td><span data-id="3">星期三</span></td>
                                                        <td><span data-id="4">星期四</span></td>
                                                        <td><span data-id="5">星期五</span></td>
                                                        <td><span data-id="6">星期六</span></td>
                                                        <td><span data-id="7">星期日</span></td>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="places_list" ><td>名额</td>
                                                            <?php foreach ($item['schedule'] as $schedule): ?>
                                                            <td><input type="number" value="<?php echo $schedule['num']; ?>"/></td>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                        <tr class="price_list" >
                                                            <td>价格</td>
                                                            <?php foreach ($item['schedule'] as $schedule): ?>
                                                                <td><input min="0" max="5000"  step="0.01" type="number" value="<?php echo $schedule['price']; ?>"/></td>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="add_commodity">添加商品<input type="hidden" name="products"></div>
                            </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="requireds">开始时间</div>
                            <div class="input_txt"><input required class="form-control form-control12" type="text" name="start_time" id="el_un_validity_date" value="<?php echo $group['start_time'];?>"></div>
                        </div>
                        <div class="hottel_name ">
                            <div class="requireds">结束时间</div>
                            <div class="input_txt"><input required class="form-control form-control12" type="text" name="end_time" id="el_validity_date" value="<?php echo $group['end_time'];?>"></div>
                        </div>
                        <div class="hottel_name ">
                            <div class="requireds">提前进入</div>
                            <div class="input_txt">
                                <input class="w_150" required type="text" name="redirect_info[name]" value="<?php echo $group['redirect_info']['name']; ?>" placeholder="按钮名称(5个字以内)">
                                <input class="input_link" type="text" required name="redirect_info[url]" value="<?php echo $group['redirect_info']['url']; ?>" placeholder="跳转连接">
                            </div>
                        </div>

                        <div class="hottel_name ">
                            <div class="requireds">活动展示时间</div>
                            <div class="input_txt">
                              <span class="input-group w_90 select_input" style="position:relative;display:inline-flex;" id="drowdown">
                                <input required placeholder="" type="number" min="0" max="24" style="color:#92a0ae;" class="form-control moba show_time_h" id="" value="<?php echo substr($group['show_time'], 1, 2); ?>" required>
                                <ul class="drow_list silde_layer hhh">
                                </ul>
                              </span>时
                              <span class="input-group w_90 select_input" style="position:relative;display:inline-flex;" id="drowdown">
                                <input required placeholder="" type="number" min="0" max="60" style="color:#92a0ae;" class="form-control moba show_time_m" id="" value="<?php echo substr($group['show_time'], 4, 2); ?>" required>
                                <ul class="drow_list silde_layer mmm">
                                </ul>
                              </span>分
                              <span class="input-group w_90 select_input" style="position:relative;display:inline-flex;" id="drowdown">
                                <input required placeholder="" type="number" min="0" max="60" style="color:#92a0ae;" class="form-control moba show_time_s" id="" value="<?php echo substr($group['show_time'], 7, 2); ?>" required>
                                <ul class="drow_list silde_layer sss">
                                </ul>
                              </span>秒<input class="show_time" type="hidden" name="show_time" value="" />
                            </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="requireds">活动开始时间</div>
                            <div class="input_txt">
                              <span class="input-group w_90 select_input" style="position:relative;display:inline-flex;" id="drowdown">
                                <input required placeholder="" type="number" min="0" max="24" style="color:#92a0ae;" class="form-control moba kill_time_h" id="" value="<?php echo substr($group['kill_time'], 1, 2); ?>" required>
                                <ul class="drow_list silde_layer hhh">
                                </ul>
                              </span>时
                              <span class="input-group w_90 select_input" style="position:relative;display:inline-flex;" id="drowdown">
                                <input required placeholder="" type="number" min="0" max="60" style="color:#92a0ae;" class="form-control moba kill_time_m" id="" value="<?php echo substr($group['kill_time'], 4, 2); ?>" required>
                                <ul class="drow_list silde_layer mmm">
                                </ul>
                              </span>分
                              <span class="input-group w_90 select_input" style="position:relative;display:inline-flex;" id="drowdown">
                                <input required placeholder="" type="number" min="0" max="60" style="color:#92a0ae;" class="form-control moba kill_time_s" id="" value="<?php echo substr($group['kill_time'], 7, 2); ?>" required>
                                <ul class="drow_list silde_layer sss">
                                </ul>
                              </span>秒<input class="kill_time" type="hidden" name="kill_time" value="" />
                            </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="requireds">每人限购数量</div>
                            <div class="input_txt"><input required type="text" name="buy_limit" value="<?php echo $group['buy_limit'];?>"></div>
                        </div>
                        <div class="hottel_name ">
                            <div class="requireds">抢购持续时间</div>
                            <div class="input_txt"><input required type="text" name="last_time" value="<?php echo $group['last_time'];?>"><span>注：请确保当天的抢购结束时间早于活动结束时间</span></div>
                        </div>
                        <div class="hottel_name ">
                            <div class="requireds">分享名称</div>
                            <div class="input_txt"><input required type="text" name="share_info[title]" value="<?php echo $group['share_info']['title'];?>"></div>
                        </div>
                        <div class="hottel_name ">
                            <div class="requireds">分享简介</div>
                            <div class="input_txt"><input required type="text" name="share_info[desc]" value="<?php echo $group['share_info']['desc'];?>"></div>
                        </div>
                        <div class="hottel_name ">
                            <div class="requireds">分享缩略图</div>
                            <div class="file_img_list">
                                <div class="clearfix">
                                    <input class="face_img" type="hidden" required name="share_info[img]" id="el_share_img" value='<?php echo $group['share_info']['img'];?>'>
                                    <div id="share_img" class="addimgs trim">
                                        <?php if($group['share_info']['img']): ?>
                                        <div class="addimg candelete"><del></del><div><img src="<?php echo $group['share_info']['img'];?>"/></div></div>
                                        <?php endif;?>
                                    </div>
                                    <div id="share_img_add"  class="img_adds"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="requireds">状态</div>
                            <div class="input_txt">
                                <select style="width:450px;" name="status">
                                    <option value="a" <?php if ($group['status'] == 'a') {echo 'selected';}  ?>  >有效</option>
                                    <option value="b" <?php if ($group['status'] == 'b') {echo 'selected';}  ?>  >无效</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <link rel="stylesheet" href="http://mp.iwide.cn/public/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css">
                <script src="http://mp.iwide.cn/public/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js"></script>
                <script src="http://mp.iwide.cn/public/AdminLTE/plugins/bootstrap-select/i18n/defaults-zh_CN.min.js"></script>
                <div class="bg_fff center" style="padding:15px;">
                    <button class="fom_btn inline_block" style="margin-right:auto;" >保存活动</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php 
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>
<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>
</div><!-- ./wrapper -->
<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>

<!--kindEditor-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.css" />
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/kindeditor.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.js"></script>
<!--kindEditor-->

<!--
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
-->
<script src="http://test008.iwide.cn/public/uploadify/jquery.uploadify.min.js"></script>
<script src="http://test008.iwide.cn/public/js/areaData.js"></script>

<link rel="stylesheet" href="http://mp.iwide.cn/public/AdminLTE/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script src="http://mp.iwide.cn/public/AdminLTE/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="http://mp.iwide.cn/public/AdminLTE/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/layDate.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script>
$(".form-control12").datetimepicker({
    format:"yyyy-mm-dd hh:ii:ss", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left",
});
// $(".form-control_ss").datetimepicker({
//     format:"hh:ii:ss", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left",
// });
var hhh='',mmm='',sss='';
for(var i=0;i<=24;i++){
    hhh+='<li value="'+i+'">'+i+'</li>';
}
$('.hhh').html(hhh);
for(var i=0;i<=60;i++){
    mmm+='<li value="'+i+'">'+i+'</li>';
}
$('.mmm').html(mmm);
for(var i=0;i<=60;i++){
    sss+='<li value="'+i+'">'+i+'</li>';
}
$('.sss').html(sss);
$('.drow_list li').click(function(){
    $(this).parent().parent().find('input').val($(this).text());
    $(this).addClass('cur').siblings().removeClass('cur');
});
// $('.select_input input').bind('input propertychange',function(){
//     var _this = $(this).val();
//     if(_this>60){
//         alert(123456);
//     }
// });
var datas={};
$('.add_commodity').click(function(event){
    var l = $('.commodity_list .commodity_item').length;
    if(l>=20)return;
    if(l>=19){$('.add_commodity').hide();}
    var str='';
    var optiona='<?php foreach ($productList as $product): ?><option value="<?php echo $product['product_id']; ?>" ><?php echo $product['name']; ?></option><?php endforeach; ?>';
    var places='<td><input type="text" /></td><td><input type="number" /></td><td><input type="number" /></td><td><input type="number" /></td><td><input type="number" /></td><td><input type="number" /></td><td><input type="number" /></td>';
    var Price='<td><input type="text" /></td><td><input type="number" min="0" max="5000"  step="0.01" /></td><td><input min="0" max="5000"  step="0.01"  type="number" /></td><td><input min="0" max="5000"  step="0.01"  type="number" /></td><td><input min="0" max="5000"  step="0.01"  type="number" /></td><td><input min="0" max="5000"  step="0.01"  type="number" /></td><td><input min="0" max="5000"  step="0.01"  type="number" /></td>';
        str=$('<div class="commodity_item m_b_35"><div class="m_b_15 candelete"><span><select class="commodity_con selectpicker border_1 w_450" data-live-search="true">'+optiona+'</select></span><del class="del_btn" style="position:static; margin-left:5px;"></del></div><div class=""><div class="m_b_15">库存情况</div><div><table class="k_contents" style=""><thead><tr class="week"><td>星期</td><td data-id="1"><span data-id="1">星期一</span></td><td><span data-id="2">星期二</span></td><td data-id="3"><span data-id="3">星期三</span></td><td><span data-id="4">星期四</span></td><td><span data-id="5">星期五</span></td><td><span data-id="6">星期六</span></td><td><span data-id="7">星期日</span></td></tr></thead><tbody><tr class="places_list" ><td>名额</td>'+places+'</tr><tr class="price_list" ><td>价格</td>'+Price+'</tr></tbody></table></div></div></div>');
    $('.commodity_list').append(str);
    str.find('.selectpicker').selectpicker();
});
$('.commodity_list').delegate('.del_btn', 'click', function(event){
    $(this).parents('.commodity_item').remove();
    $('.add_commodity').show();
});

var show_time='';
var kill_time='';

function pad(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}

$('.fom_btn').click(function(){
    var commodity_list_length=$('.commodity_list .commodity_item');
    var commodity_list_arr=[];
    show_time=pad($('.show_time_h').val(), 2)+':' + pad($('.show_time_m').val(), 2)+':'+ pad($('.show_time_m').val(), 2);
    kill_time=pad($('.kill_time_h').val(), 2)+':'+pad($('.kill_time_m').val(), 2)+':'+pad($('.kill_time_m').val(), 2);
    
    $('.show_time').val(show_time);
    $('.kill_time').val(kill_time);

    
    if(commodity_list_length.length!=0){
        commodity_list_length.each(function(index, el) {
            var obj={};
            var arr=[];

            obj.product_id=$(this).find('select').val();

            for(var i=0;i<$(this).find('.k_contents thead td').length-1;i++){
                var json_price_item={};
                    json_price_item.time=$(this).find('.week span').eq(i).attr('data-id');
                    json_price_item.num=$(this).find('.places_list input').eq(i).val();
                    json_price_item.price=$(this).find('.price_list input').eq(i).val();
                    arr.push(json_price_item);
            }
            obj.schedule=arr;
            commodity_list_arr.push(obj);
        });
    }else{
        $('.fixed_box').fadeIn();
        return false;
    }
//    console.log(commodity_list_arr);
    var bool=true;
    $('.add_commodity input').val(JSON.stringify(commodity_list_arr))
    // $('input[required]').each(function(index, el) {
    //     if($(this).val()==''){
    //         bool=false;
    //     console.log(1);
    //         return false;
    //     }
    // });
    // if(bool){
    //     console.log(12);
    //     $('form').submit();
    // }
})

$('.confirms,.cancel').click(function(){
    $('.fixed_box').fadeOut();
})

function delimg(){  //缩略图
    $(this).parent().remove();
    $("#el_face_img").val('');
    $('#face_img_add').show();
}
if($('#face_img img').length>0){
    $('#face_img_add').hide();
}
$('#face_img_add').uploadify({//缩略图
    'formData'     : {
        '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
        'timestamp' : '<?php echo time();?>',
        'token'     : '<?php echo md5('unique_salt' . time());?>'
    },
    //'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
    'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
    'fileObjName': 'imgFile',
    'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
    'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/add_xs.png",
    'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
    'fileSizeLimit':'300', //限制文件大小
    'onUploadSuccess' : function(file, data) {
        var res = $.parseJSON(data);
        $('#el_face_img').val(res.url);
        var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
        $("#face_img").append(dom);
        dom.find('del').get(0).onclick=delimg;
        $('#face_img_add').hide();
    },   
    'onUploadError': function () {  
        alert('上传失败');  
    }
});
$('.addimg del').click(function(){
    $(this).parents('.clearfix').find('.face_img').val('');
    $(this).parents('.clearfix').find('.img_adds').show();
    $(this).parent().remove();
})
function delimgs(){  //缩略图2
    $(this).parent().remove();
    $("#el_share_img").val('');
    $('#share_img_add').show();
}
if($('#share_img img').length>0){
    $('#share_img_add').hide();
}
$('#share_img_add').uploadify({//缩略图
    'formData'     : {
        '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
        'timestamp' : '<?php echo time();?>',
        'token'     : '<?php echo md5('unique_salt' . time());?>'
    },
    //'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
    'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
    'fileObjName': 'imgFile',
    'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
    'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/add_xs.png",
    'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
    'fileSizeLimit':'300', //限制文件大小
    'onUploadSuccess' : function(file, data) {
        var res = $.parseJSON(data);
        $('#el_share_img').val(res.url);
        var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
        $("#share_img").html(dom);
        dom.find('del').get(0).onclick=delimgs;
        $('#share_img_add').hide();
    },   
    'onUploadError': function () {  
        alert('上传失败');  
    }
});
//图片上传排版end
$(function () {
    var commonItems = [
        'undo', 'redo', '|','cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat',  '|',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '/', 'image', 'multiimage',
        'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
        'anchor', 'link', 'unlink'
    ];
    KindEditor.ready(function(K) {
        //订购须知
        // var editor1 = K.create('textarea[name="order_notice"]', {
        //     cssPath : 'http://mp.iwide.cn/public/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
        //     uploadJson : 'http://mp.iwide.cn/index.php/basic/upload/kind_do_upload?t=images&p=inter_id|soma|product_package|order_notice&token=test',
        //     fileManagerJson : 'http://mp.iwide.cn/public/AdminLTE/plugins/kindeditor/php/file_manager_json.php',
        //     allowFileManager : true,
        //     resizeType : 1,
        //     items : commonItems,
        //     afterCreate : function() {
        //         setTimeout(function(){
        //             $('.ke-container').css('width','');
        //         },1)
        //     }
        // });

        //图文详情
        var editor2 = K.create('textarea[name="img_detail"]', {
            cssPath : 'http://mp.iwide.cn/public/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
            uploadJson : 'http://mp.iwide.cn/index.php/basic/upload/kind_do_upload?t=images&p=inter_id|soma|product_package|img_detail&token=test',
            fileManagerJson : 'http://mp.iwide.cn/public/AdminLTE/plugins/kindeditor/php/file_manager_json.php',
            allowFileManager : true,
            resizeType : 1,
            items : commonItems,
            afterCreate : function() {
                setTimeout(function(){
                    $('.ke-container').css('width','');
                    $('.ke-edit').height(300);
                    $('.ke-edit-iframe').height(300);
                },1)
            }
        });
        prettyPrint();
    });

});


</script>
</body>
</html>
