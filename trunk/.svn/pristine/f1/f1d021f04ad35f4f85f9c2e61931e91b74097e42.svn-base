<?php include 'header.php'?>
<?php echo referurl('css','search_result.css',1,$media_path) ?>
<style>
.filter_pull{background:#f7f7f7;}
.room_list,.title{background:#fff;}
.title{ padding:3% 0; border-bottom:1px solid #e4e4e4; margin-bottom:3%; text-align:center}
.checkbtn{float:right}
.checkbtn .ui_ico {width: 1em;height: 1em;border:1px solid #e4e4e4;border-radius: 50%;background-size: 50% auto;margin-right: 0.5em; margin-top:1em;}
.ui_foot_btn{ position:fixed; width:100%; bottom:0; color:#fff;}
.ui_foot_btn >*{color:#fff;}
</style>

<form onSubmit="return _search();">
<div class="filter_pull">
	<div class="title h5">请选择房间（房型：商务大床间 x1）</div>
    <div class="content_pull float tab_list">
        <ul class="address_list">
        	<li class="cur">1层</li>
        	<li>2层</li>
        </ul>
    </div>
    <div class="content_pull room_list">
        <ul class="address_list">
        	<li class="ischeck">
            	<div class="checkbtn"><em class="ui_ico ui_ico13"></em></div>
            	<p><b>1209房</b></p>
                <p>20m-25m，朝东，窗朝马路，有沙发，独立空调，吸烟房</p>
                <input name="room_num" type="checkbox" style="display:none">
            </li>
        	<li class="">
            	<div class="checkbtn"><em class="ui_ico ui_ico13"></em></div>
            	<p><b>1209房</b></p>
                <p>20m-25m，朝东，窗朝马路，有沙发，独立空调，吸烟房</p>
                <input name="room_num" type="checkbox" style="display:none">
            </li>
        </ul>     
        <ul class="address_list" style="display:none">
        	<li class="ischeck">
            	<div class="checkbtn"><em class="ui_ico ui_ico13"></em></div>
            	<p><b>2209房</b></p>
                <p>20m-25m，朝东，窗朝马路，有沙发，独立空调，吸烟房</p>
                <input name="room_num" type="checkbox" style="display:none">
            </li>
        	<li class="">
            	<div class="checkbtn"><em class="ui_ico ui_ico13"></em></div>
            	<p><b>2209房</b></p>
                <p>20m-25m，朝东，窗朝马路，有沙发，独立空调，吸烟房</p>
                <input name="room_num" type="checkbox" style="display:none">
            </li>
        </ul>       
    </div>
</div>
<div class="ui_foot_btn mbg">
	<button>确认选房</button>
</div>
</form>
</body>
<script>
var count = 1; //可预订的房间数量;

	$('.room_list li').click(function(){
		if( $('.ischeck').length>count ){ alert('超过可预订的房间数量!'); return false;}
		$(this).toggleClass('ischeck');
		if($('input',this).get(0).checked==true){
			$('input',this).get(0).checked=false;
		}
		else{
			$('input',this).get(0).checked=true;
		}
	})
	$('.filter_pull .tab_list li').click(function(){
		$(this).addClass('cur').siblings().removeClass('cur');
		$('.room_list ul').eq($(this).index()).show().siblings().hide();
	})
</script>
</html>

