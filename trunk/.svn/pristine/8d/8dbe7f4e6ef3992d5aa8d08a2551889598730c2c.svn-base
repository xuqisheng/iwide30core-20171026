<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?=base_url(FD_PUBLIC)?>/js/html5shiv.min.js"></script>
    <script src="<?=base_url(FD_PUBLIC)?>/js/respond.min.js"></script>
<![endif]-->
<style>
@font-face {
  font-family: 'iconfont';
  src: url('<?=base_url(FD_PUBLIC)?>/newfont/iconfont.eot');
  src: url('<?=base_url(FD_PUBLIC)?>/newfont/iconfont.eot?#iefix') format('embedded-opentype'),
  url('<?=base_url(FD_PUBLIC)?>/newfont/iconfont.woff') format('woff'),
  url('<?=base_url(FD_PUBLIC)?>/newfont/iconfont.ttf') format('truetype'),
  url('<?=base_url(FD_PUBLIC)?>/newfont/iconfont.svg#iconfont') format('svg');
}
.iconfont{
  font-family:"iconfont" !important;
  font-size:16px;font-style:normal;
  -webkit-font-smoothing: antialiased;
  -webkit-text-stroke-width: 0.2px;
  -moz-osx-font-smoothing: grayscale;
}
.over_x{width:100%;overflow-x:auto;}
.bg_fff{background:#fff;}
.bg_3f51b5{background:#3f51b5;}
.bg_ff503f{background:#ff503f;}
.bg_4caf50{background:#4caf50;}
.clearfix:after{content: "" ;display:block;height:0;clear:both;visibility:hidden;}
.display_none{display:none !important;}
.m_b_20{margin-bottom:20px;}
.float_left{float:left;}
.content-wrapper{color:#7e8e9f;}
.p_0_20{padding:0 20px;}
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
.block{display:inline-block;height:18px;width:4px;vertical-align: middle;margin-right:5px;}
.introduce{width:450px;height:150px;margin-left:4px;resize:vertical;}
.add_img{width:77px;height:77px;background:url(<?=base_url(FD_PUBLIC)?>/js/img/214598012363739107.png) no-repeat;background-size:100%;margin-right:20px;float:left;}
.box-body{width:90%;margin:auto;padding:0px;}
#coupons_table thead{background:#f8f9fb;border:1px solid #d7e0f1;}
#coupons_table tbody tr{background:#fff;border:1px solid #d7e0f1;}
.form_con >td,.form_title >th{text-align:center;font-weight:normal;}

.fom_btn{background:#ff9900;color:#fff;outline:none;border:0px;padding:6px 25px;border-radius:3px;display:inline-block;margin-right:28px;width:120px;}
.fom_btn:nth-of-type(1){margin-left:250px;}
.add_img_box:hover > .img_close{display:block !important;cursor:pointer;}
.record{float:right;margin-right:20px;color:#649BE3;}
.c_9A958F{color:#9A958F;}
.erwm{height:180px;width:180px;float:left;margin:0 20px;border:1px solid #E7E6E5;padding:3px;}
.erwm >img{width:100%;height:100%;}
.color_A4C4ED{color:#A4C4ED;}
</style>
<link rel="stylesheet" href="<?=base_url(FD_PUBLIC)?>/AdminLTE/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?=base_url(FD_PUBLIC)?>/AdminLTE/plugins/datatables/images/laydate12.css">
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
<div class="over_x">
    <div class="content-wrapper">
        <div class="banner bg_fff p_0_20">优惠券核销</div>
        <div class="contents">
            <from>
                <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_ff503f"></span>优惠券核销</div>
                    <div class="con_right clearfix">
                        <a class="record" href="<?php echo Soma_const_url::inst()->get_url('privilege/adminuser/profile');?>">授权管理</a>
                        <div class="erwm"><img src="<?php  echo EA_const_url::inst()->get_url('*/tool/qrc')."?str="."http://".$public['domain']."/index.php/membervip/card/codeuseoff?id=".$public['inter_id'];?>" /></div>
                        <div>
                            <p>注意事项:</p>
                            <p>1.使用此功能必须先对扫码微号进行授权</p>
                            <p>2.授权只需一次操作,无须重复授权,本管理员有责任对授权账号进行审核通过和清退操作</p>
                            <p>3.授权后微售号所做的操作将等同于本管理员的操作,其操作造成的损失由本管理员承担</p>
                            <p>4.一旦进行扫码操作,即等同于同意以上内容</p>
                            <p>5.一切授权工作完成，扫一扫左边二维码即可开始核销等操作</p>
                        </div>
                    </div>
                </div>
            </from>
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
</body>
</html>
<script src="<?=base_url(FD_PUBLIC)?>/AdminLTE/plugins/datatables/layDate.js"></script>
<link rel="stylesheet" href="<?=base_url(FD_PUBLIC)?>/AdminLTE/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script src="<?=base_url(FD_PUBLIC)?>/AdminLTE/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="<?=base_url(FD_PUBLIC)?>/AdminLTE/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script>
;!function(){
    laydate({
       elem: '#datepicker'
    })
}();
</script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/areaData.js"></script>
<script type="text/javascript">
    <?php $timestamp = time();?>
    $(function() {
        $('#el_intro_img').parent().append('<input type="file" value="上传图片" id="upfiles">');
        $('#upfiles').uploadify({
            'formData'     : {
                '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
                'timestamp' : '<?php echo $timestamp;?>',
                'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
            },
            'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
            //'uploader' : '<?php echo site_url("basic/upload/hotel_upload") ?>',
            'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
            'file_post_name': 'imgFile',
            'onUploadSuccess' : function(file, data, response) {
                var res = $.parseJSON(data);
                $('#el_intro_img').val(res.url);
            }
        });

        $('.input_radio').change(function() {
            if($('.star_4:checked').val()){
                $('.img_upload').show();
                $('.coupon_code').hide();
            }else{
                $('.img_upload').hide();
                $('.coupon_code').show();
            }
        });
    });

    <?php
        // csrf
        $CI =& get_instance();
        $token_name = $CI->security->get_csrf_token_name(); 
        $token_hash = $CI->security->get_csrf_hash();
    ?>
    
    var consumer_code, consumer_item;
    function ajax_consumer_code_info() {
        consumer_code = $('#consumer_code').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo Soma_const_url::inst()->get_url('*/*/ajax_consumer_code_info')?>",
            data: {
                code : consumer_code,
                <?php echo $token_name; ?> : "<?php echo $token_hash; ?>",
            },
            dataType:'json',
            success: function(data) {
                // 调整券码核销的列表显示
                consumer_item = data['items'];
                $('#code_consumer .con_right .box-body').remove();
                var html = generate_table_html(data['header'], data['data']);
                $('#code_consumer .con_right').append(html);
            }
        });
    }

    function ajax_code_consumer(type) {

        if(consumer_code == undefined || consumer_item == undefined) {
            alert('请先查询并确认券码信息！');
            return;
        }

        var base_url = "<?php echo Soma_const_url::inst()->get_url('*/*/ajax_consumer_code')?>";

        $.ajax({
            type: 'POST',
            url: base_url + '?ids=' + consumer_item['item_id'] + '&code=' + consumer_code + '&type=' + type,
            data: {
                asset_id: '',
                <?php echo $token_name; ?> : "<?php echo $token_hash; ?>",
                remark: $('#code_consumer_remark').val(),
                item_id: consumer_item['item_id'],
                code: $('#consumer_code').val(),
            },
            dataType:'json',
            success: function(data) {
                alert(data['message']);
            }
        });
    }

    function add_table1(obj,data){
        var number1=obj.find('th').length;
        var str='';
        var i;
        var num=0;
            for(var j=0;j<data.items.length;j++){
                str+='<tr class="form_con">';
                for(i in data.items[j]){
                    str+="<td>"+data.items[j][i]+"</td>";
                    num+=1;
                    if(num>=number1){
                        break;
                    }
                }
                num=0;
                str+='</tr>';
            }
            obj.parent().parent().find('tbody').append(str);
    }

    function generate_table_html(header, data) {

        var html = '<div class="box-body">';
        html += ' <table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;">';
        html += '<thead class="bg_f8f9fb form_thead">';
        html += '<tr class="bg_f8f9fb form_title">';
        
        for(var i=0; i<header.length; i++) {
            html += "<th>" + header[i] + "</th>";
        }

        html += '</tr></thead>';
        html += '<tbody class="containers dataTables_wrapper form-inline dt-bootstrap">';
        
        for(var i=0; i<data.length; i++) {
            html += '<tr class="form_con">';
            for(var j=0; j<data[i].length; j++) {
                html += "<td>" + data[i][j] + "</td>";
            }
            html += '</tr>';
        }

        html += '</tbody></table></div>';
        return html;
    }

    var consumer_order;
    function ajax_consumer_order_info() {
        consumer_order = $('#consumer_order').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo Soma_const_url::inst()->get_url('*/*/ajax_consumer_order_info')?>",
            data: {
                order_id : consumer_order,
                <?php echo $token_name; ?> : "<?php echo $token_hash; ?>",
            },
            dataType:'json',
            success: function(data) {
                // 调整订单核销的列表显示
                $('#order_consumer .con_right .box-body').remove();
                var order_html = generate_table_html(data['order_data']['header'], data['order_data']['data']);
                var asset_html = generate_table_html(data['asset_data']['header'], data['asset_data']['data']);
                $('#order_consumer .con_right').append(order_html + asset_html);
            }
        });
    }

    function ajax_consumer_order() {
        if(consumer_order == undefined) {
            alert('请先查询并确认订单信息！');return;
        }
        var num = parseInt($('#consumer_order_num').val());
        if(num == undefined || isNaN(num) || num <= 0) {
            alert('请输入核销份数！');return;
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo Soma_const_url::inst()->get_url('*/*/ajax_consumer_order')?>",
            data: {
                order_id : consumer_order,
                <?php echo $token_name; ?> : "<?php echo $token_hash; ?>",
                num : num,
            },
            dataType:'json',
            success: function(data) {
                alert(data['message']);
            }
        });
    }

    function ajax_consumer_product_info() {
        // console.log($('#expire_product').val());
        $.ajax({
            type: 'POST',
            url: "<?php echo Soma_const_url::inst()->get_url('*/*/ajax_consumer_product_info')?>",
            data: {
                pid : $('#expire_product').val(),
                <?php echo $token_name; ?> : "<?php echo $token_hash; ?>",
            },
            dataType:'json',
            success: function(data) {
                // 调整延期产品的列表显示
                $('#expire_product_form').remove();
                var html = generate_table_html(data['header'], data['data']);
                var form = '<form id="expire_product_form" >' + html + '</form>'
                $('#modify_expire .con_right').append(form);
                // expireTime
                // $('#modify_expire').delegate('.expireTime','click',function(){

                // })
                $(".expireTime").datetimepicker({
                    format:"yyyy-mm-dd hh:ii:ss", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left",
                });
            }
        });

    }

    function ajax_consumer_product() {
        var form = $('#expire_product_form').serializeArray();
        if(Array.isArray(form) && form.length === 0) {
            alert('请先查询并确认产品信息！');return;
        }

        form.push({
            name: "<?php echo $token_name; ?>",
            value: "<?php echo $token_hash; ?>"
        });

        $.ajax({
            type: 'POST',
            url: "<?php echo Soma_const_url::inst()->get_url('*/*/ajax_consumer_product')?>",
            data: form,
            dataType:'json',
            success: function(data) {
                alert(data['message']);
            }
        });
    }

</script>