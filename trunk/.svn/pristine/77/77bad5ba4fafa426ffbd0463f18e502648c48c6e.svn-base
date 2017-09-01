<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<title>评论设置</title>
<style>
    body {
        margin: 0;
        background-color: #f0f3f6;
        font-size: 16px;
        font-family: 微软雅黑;
        color: #7e8e9f;
    }

    .FL {
        float: left;
    }

    ib {
        display: inline-block;
    }

    .VA-M {
        vertical-align: middle;
    }

    .each_line {
        margin-top: 10px;
        background-color: white;
        border: 1px solid #d7e0f1;
    }

    .each_line .VA-M:first-child {
        width: 150px;
    }

    .each_line .VA-M:nth-of-type(2) {
        padding: 15px 20px;
        border-left: 1px solid #d7e0f1;
    }

    colorline {
        width: 5px;
        height: 100%;
        position: absolute;
        left: 30px;
    }

    .blue_line {
        background-color: #3f51b5;
    }

    .head div {
        padding: 2px 0px;
        margin: 1px 0px;
        margin-left: 40px;
    }

    .VA-M {
        position: relative;
    }

    .red_line {
        background-color: #ff503f;
    }

    .green_line {
        background-color: #4caf50;
    }

    .yellow_line {
        background-color: #ebe814;
    }

    .zi_line {
        background-color: #af4cac;
    }

    .keyword input {
        padding: 5px 10px;
        font-size: 16px;
        border: 1px solid #d7e0f1;
    }

    .keys div {
        margin-left: 25px;
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #d7e0f1;
    }

    .keys ib:nth-of-type(1) div {
        margin-left: 0px;
    }

    X {
        border: 1px solid #d7e0f1;
        border-radius: 100%;
        width: 15px;
        height: 15px;
        display: inline-block;
        vertical-align: middle;
        margin-left: 5px;
        position: relative;
        cursor: pointer;
    }

    X:before {
        content: "";
        position: absolute;
        width: 12px;
        height: 0px;
        border-top: 1px solid #d7e0f1;
        left: 2px;
        top: 7px;
        transform: rotate(45deg);
    }

    X:after {
        content: "";
        position: absolute;
        width: 12px;
        height: 0px;
        border-top: 1px solid #d7e0f1;
        left: 2px;
        top: 7px;
        transform: rotate(-45deg);
    }

    p {
        margin: 1px 0px;
    }

    table {
        text-align: left;
    }

    tr {
        height: 50px;
    }

    tr th:first-child {
        width: 50px;
    }

    th {
        font-weight: 100;
    }

    .btnx {
        background-color: #ff9900;
        display: inline-block;
        padding: 10px;
        color: white;
        border-radius: 5px;
        margin-top: 20px;
        margin-bottom: 20px;
        /*margin: 15px;*/
    }

    .jilu {
        text-align: center;
        width: 100%;
        border-collapse: collapse;
    }

    .jilu tr {
        border-bottom: 1px dashed #d7e0f1;
    }

    .jilu tr:first-child {
        border-bottom: 0;
    }

    .jilu tr:last-child {
        border-bottom: 0;
    }

    .keyword_title th{
        text-align: center;
    }

    #auth_t{
        margin-left: 50px;
    }

	*{
		box-sizing: inherit !important;
		-webkit-box-sizing: inherit !important;
	}
	select, input {
    height: inherit !imporant;
    line-height: inherit !important;
}
</style>

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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
    <section class="content-header"><h1>评论设置</h1></section>
<div>
<!--    <div class="each_line">
        <ib class="VA-M head">
            <colorline class="blue_line"></colorline>
            <div>评论机制</div>
        </ib>
        <ib class="VA-M">
            <input name="评论机制" type="radio">
            <ib>五分制(默认)</ib>
            <input name="评论机制" type="radio">
            <ib>百分制</ib>
        </ib>
    </div>-->
    <div class="each_line">
        <ib class="VA-M head">
            <colorline class="red_line"></colorline>
            <div>审核机制</div>
        </ib>
        <ib class="VA-M">
            <input  name="auth" id="auth_f" value="false" type="radio" <?php if(isset($type->auth)&&$type->auth=='false')echo "checked";?>>
            <ib>无需审核，用户评论后直接展示</ib>
            <input  name="auth" id="auth_t" value="true" type="radio" <?php if(isset($type->auth)&&$type->auth=='true')echo "checked";?>>
            <ib>需审核后，才展示评论</ib>
        </ib>
    </div>
    <div class="each_line">
        <ib class="VA-M head">
            <colorline class="green_line"></colorline>
            <div>评论关键词</div>
        </ib>
        <ib class="VA-M keyword">
            <input type="text" placeholder="输入标签" class="pinglun_input">
            <div style="margin-top: 10px;margin-left: 10px;">*按Enter键添加关键词</div>
            <div style="margin-top:10px;" class="keys">
                <?php if($list){foreach($list as $arr){ ?>
                    <ib>
                        <div><?php echo $arr['keyword'];?>
                            <X data-pinglun="<?php echo $arr['keyword_id'];?>"></X>
                        </div>
                    </ib>
                <?php }}?>
            </div>
        </ib>
    </div>
    <div class="each_line">
        <ib class="VA-M head">
            <colorline class="zi_line"></colorline>
            <div>出游类型</div>
        </ib>
        <ib class="VA-M keyword">
            <input type="text" placeholder="输入类型" class="chuyou_input">
            <div style="margin-top: 10px;margin-left: 10px;">*按Enter键添加出游类型</div>
            <div style="margin-top:10px;" class="keys">
                <?php if(isset($sign)){ foreach($sign as $arr_sign){ ?>
                <ib>
                    <div><?php echo $arr_sign;?>
                        <X data-chuyou="<?php echo $arr_sign;?>"></X>
                    </div>
                </ib>
            <?php }}?>
            </div>
        </ib>
    </div>
    <div class="each_line">
        <ib class="VA-M head">
            <colorline class="yellow_line"></colorline>
            <div>
                <p>评论维度</p>
                <p>文字自定义</p>
            </div>
        </ib>
        <ib class="VA-M keyword">
            <table>
                <tr>
                    <th>默认</th>
                    <th>修改成</th>
                </tr>
                <tr>
                    <td><?php if(isset($type->facilities_score)){ echo $type->facilities_score;}else{ echo "设施";}?></td>
                    <td><input type="text" id="facilities"></td>
                </tr>
                <tr>
                    <td><?php if(isset($type->clean_score)){ echo $type->clean_score;}else{ echo "卫生";}?></td>
                    <td><input type="text" id="clean"></td>
                </tr>
                <tr>
                    <td><?php if(isset($type->service_score)){ echo $type->service_score;}else{ echo "服务";}?></td>
                    <td><input type="text" id="service"></td>
                </tr>
                <tr>
                    <td><?php if(isset($type->net_score)){ echo $type->net_score;}else{ echo "网络";}?></td>
                    <td><input type="text" id="net"></td>
                </tr>
            </table>
        </ib>
    </div>
    <div class="each_line" style="text-align:center;">
        <div class="btnx">保存设置</div>
    </div>
    <div class="each_line" style="margin-bottom: 20px;display: none">
        <div style="margin-left: 10px;margin-top: 10px;">操作记录</div>
        <table class="jilu">
            <tr class="keyword_title">
                <th>序号</th><th>账号</th><th>操作描述</th><th>IP地址</th><th>时间</th>
            </tr>
            <?php if(isset($logs)){ foreach($logs as $arr){?>
                <tr>
                    <td><?php echo $arr['log_id'];?></td>
                    <td><?php echo json_decode($arr['admin'])->nm;?></td>
<!--                    <td>--><?php //echo $log_des[$arr['log_type']]."‘".$arr['key_data']."’";?><!--</td>-->
                    <td>修改了配置</td>
                    <td><?php echo $arr['ip'];?></td>
                    <td><?php echo $arr['record_time'];?></td>
                </tr>
            <?php }}?>
            </tr>
        </table>
    </div>
</div>
<script>
    var c_auth = "<?php if(isset($type->auth)){echo $type->auth;}else{echo "false";}?>";
    Array.prototype.indexOf = function(val) {
for (var i = 0; i < this.length; i++) {
if (this[i] == val) return i;
}
return -1;
};
	Array.prototype.remove = function(val) {
var index = this.indexOf(val);
if (index > -1) {
this.splice(index, 1);
}
};
	var pinglun = [];
	var chuyou = [];
	for(var i = 0; i < $("X[data-chuyou]").length;i++){
		chuyou.push($("X[data-chuyou]")[i].dataset.chuyou);
	}
	for(var i = 0; i < $("X[data-pinglun]").length;i++){
		pinglun.push($("X[data-pinglun]")[i].dataset.pinglun);
	}
	$("X[data-chuyou]").on("click",function(){
		chuyou.remove(this.dataset.chuyou);
		var father = this.parentElement.parentElement;
		$(".keys")[1].removeChild(father);
	});
	$("X[data-pinglun]").on("click",function(){
		pinglun.remove(this.dataset.pinglun);
		var father = this.parentElement.parentElement;
		$(".keys")[0].removeChild(father);
	});
	$(document).on("keydown",function(e){
		if(e && e.keyCode == "13"){
			var chuyou_input = $(".chuyou_input").val();
			var pinglun_input = $(".pinglun_input").val();
			if(chuyou_input != "" && chuyou_input != undefined){
				add($(".keys")[1],"chuyou",chuyou_input,chuyou);
				chuyou.push(chuyou_input);
			} 
			if(pinglun_input != "" && pinglun_input != undefined){
				add($(".keys")[0],"pinglun",pinglun_input,pinglun);
				pinglun.push(pinglun_input);
			}
			$(".chuyou_input").val("");
			$(".pinglun_input").val("");
		}
	});
	function add(obj,str0,str,bl){
		var ib = document.createElement("ib");
		var thehtml = '<div>'+str+'<X data-'+str0+'='+str+'></X></div>';
		ib.innerHTML = thehtml;
		obj.appendChild(ib);
		var thisone = ib.children[0].children[0];
		$(thisone).on("click",function(){
			bl.remove(this.dataset[str0]);
			var father = this.parentElement.parentElement;
			obj.removeChild(father);
		});
	}
</script>


</div><!-- /.content-wrapper -->
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
<script>
$('#addbtn').click(function(){
	var html = '';
	var val  = $('#keyword').val();
	if(val!=''){
        $.get('<?php echo site_url('hotel/Comment/keyword_post')?>',{
            keyword:val
        },function(data){
            console.log(data);
            if(data){
                html +='<tr><td>'+val+'</td><td><button type="button" value="'+data+'" class="btn btn-danger btn-xs">删除</button></td></tr>';
                $('#keyword_list').append(html);
            }else{
                alert('关键词已存在');return;
            }
        },'json');
	}
})

$('#auth_f').on('click',function(){
    c_auth = 'false';
})

$('#auth_t').on('click',function(){
    c_auth = 'true';
})

$('.btnx').on('click',function(){
    $.get('<?php echo site_url('hotel/comment/comment_setting_save')?>',{
        keyword_id:pinglun,
        sign:chuyou,
        net:$("#net").val(),
        clean:$("#clean").val(),
        service:$("#service").val(),
        facilities:$("#facilities").val(),
        auth:c_auth
    },function(data){
        alert(data.message)
    },'json');
    $(this).parents('tr').remove();
});
</script>
</body>
</html>
