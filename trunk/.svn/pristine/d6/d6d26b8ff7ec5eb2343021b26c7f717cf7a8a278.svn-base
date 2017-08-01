<?php require_once('header.php');?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/hotel/public/styles/receipt.css');?>">
<?php if(isset($hotel['invoice']) && $hotel['invoice']==2){ ?>
<div class="statustep webkitbox center">
	<div>
    	<span class="bg_main active h24">1</span>
        <p class="h22 color_main">填写信息</span>
    </div>
    <div>
    	<span class="bg_999 h24">2</span><hr>
        <p class="h22">开具发票</span>
    </div>
    <div>
    	<span class="bg_999 h24">3</span><hr>
        <p class="h22">预约完成</p>
    </div>
</div>
<?php }?>
<form action="<?php if(isset($hotel['invoice']) && $hotel['invoice']==2){ echo site_url('hotel/invoice/choose');}else{ echo site_url('hotel/invoice/book_checkout');}?>" method="post">
<input name="oid" type="hidden"  placeholder="" tips="" id="oid" value="<?php echo $oid;?>">
<input name="hid" type="hidden"  placeholder="" tips="" id="hid" value="<?php echo $hid;?>">
<input name="status" type="hidden"  placeholder="" tips="" id="status" value='0'>
<div class="bg_E4E4E4 h20" style="padding:4px 8px">填写退房信息</div>
<div class="list_style bd_bottom add_new_list">
    <div class="input_item webkitbox">
        <div>入住人</div>
        <div><input maxlength="10" onkeyup="checkcode()" oninput="" class="name" name="name" type="text" required placeholder="请输入入住人姓名" tips="请输入入住人姓名" id="name" value="<?php if(isset($name))echo $name;?>"></div>
    </div>
    <div class="input_item webkitbox">
        <div>电话</div>
        <div><input maxlength="11" name="tel" oninput="this.value=this.value.replace(/\D/g,'')" class="tel" type="text" required placeholder="请输入电话" tips="请输入电话" id="tel" value="<?php if(isset($tel))echo $tel;?>"></div>
    </div>
	<div class="input_item webkitbox">
    	<div>所住房号</div>
        <div><input name="room_nums" type="text" required placeholder="请输入入住房间号" tips="输入入住房间号" id="room_nums"></div>
    </div>
	<div class="input_item webkitbox arrow">
    	<div>退房时间</div>
        <div><select class="h28"  name="checkout_time" id="checkout_time"></select></div>
    </div>
</div>
<div class="pad3" style="margin-top:10px">
	<button class="btn_main h32 submitbtn color_fff disable" type="button" id="_submit">预约退房</button>
</div>
</form>

<script>
var check_start = 0;
var check_end = 24;
<?php if(isset($hotel['retreat_time']) && !empty($hotel['retreat_time'])){
     $retreat_time = json_decode($hotel['retreat_time']);
?>
check_start = <?php echo ($retreat_time->start/100);?>;
check_end = <?php echo ($retreat_time->end/100);?>;
<?php }  ?>
{
    <?php
        if(isset($hotel['retreat_time']) && !empty($hotel['retreat_time'])){
            $retreat_time = json_decode($hotel['retreat_time']);
            $time = (date("H",time()) + 2)*100;
            if($time < $retreat_time->start || $time > $retreat_time->end){
    ?>
//                checkout_tips();
    <?php  }}?>

	var start = new Date();
	start.setMinutes(0,0,0);
	var tmp_str = '';
	for(var i= start.getHours()+2; i<=24; i++){
		start.setHours(i);
        if(start.getHours()>=check_start && start.getHours()<=check_end && start.getHours()!=0){
            tmp_str += '<option value="'+(start.getTime()/1000)+'">'+start.toLocaleDateString()+' '+start.getHours()+':00'+'<\/option>';
        }
	}
    for(var i= start.getHours(); i<=24; i++){
        start.setHours(i);
        if(start.getHours()>=check_start && start.getHours()<=check_end && start.getHours()!=0){
            tmp_str += '<option value="'+(start.getTime()/1000)+'">'+start.toLocaleDateString()+' '+start.getHours()+':00'+'<\/option>';
        }
    }
	$('#checkout_time').html(tmp_str);
}
function testval(){
	$('form input').each(function(index, element) {
        if($(this).val()==''){
			$.MsgBox.Alert( '你还没有'+$(this).attr('tips'));
			$('#_submit').removeClass('disable').addClass('disable');
		}

        if($(this).hasClass('name')){
            if(($(this).val()).length>10){
                $.MsgBox.Alert('输入的名字过长');
                $('#_submit').removeClass('disable').addClass('disable');
            }
        }

        if($(this).hasClass('tel')){
            if(!reg_phone.test($(this).val())){
                $.MsgBox.Alert('请输入正确的手机号码');
                $('#_submit').removeClass('disable').addClass('disable');
            }
        }
    });
}
function button_change(){
	for ( var i=0;i<$('input','form').length;i++){
		if ( $('input','form').eq(i).val()=='')return;
	}
	$('#_submit').removeClass('disable');
}
$('#_submit').click(function(){

    if( $('#status').val()==1){
        alert('已经提交过了');
    }
	testval();
	if($(this).hasClass('disable')){
		testval();
		return;
	}

    if( $('#status').val()==0){
        $('#status').val(1);
        $('form').submit();
    }

});
$('input').change(button_change);


function checkcode(){
    var name_text = $("#name").val();
    name_text=name_text.replace(/[\d]/g,'')
    name_text=name_text.replace(' ','');
    if(name_text.length > 10){
        name_text=name_text.slice(0,10);
    }
    $("#name").val(name_text);
}


function checkout_tips(){
    <?php if(isset($retreat_time->end) && isset($retreat_time->start)){ ?>
    var checkout_start = '<?php echo substr($retreat_time->start,0,2);?>';
    var checkout_end = '<?php echo substr($retreat_time->end,0,2);?>';
//    alert('退房时间为'+checkout_start+':00～'+checkout_end+':00哦');
    location.href = "<?php echo site_url ( 'hotel/invoice/processing' ) . '?id=' . $hotel['inter_id'] . '&h=' . $hotel['hotel_id'];?>"+'&s='+checkout_start+':00&e='+checkout_end+":00";
    <?php }?>
}

</script>
</body>
</html>
