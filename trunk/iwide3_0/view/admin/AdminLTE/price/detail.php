<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
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
.fixed:after{content:".";display:block;clear:both;height:0;visibility:hidden;}
i,em,cite{font-style: normal}
.pagination{margin-top:0px;margin-bottom:0px;}
.pages {
  float: right;
  margin: 20px 0;
}
.pages #Pagination {
  float: left;
  overflow: hidden;
}
.pages #Pagination .pagination {
  height: 40px;
  text-align: right;
  font-family: \u5b8b\u4f53,Arial;
}
.pages #Pagination .pagination a,
.pages #Pagination .pagination span {
  float: left;
  display: inline;
  padding: 11px 13px;
  border: 1px solid #e6e6e6;
  border-right: none;
  background: #f6f6f6;
  color: #666666;
  font-family: \u5b8b\u4f53,Arial;
  font-size: 14px;
  cursor: pointer;
}
.pages #Pagination .pagination .current {
  background: #ffac59;
  color: #fff;
}
.pages #Pagination .pagination .prev,
.pages #Pagination .pagination .next {
  float: left;
  padding: 11px 13px;
  border: 1px solid #e6e6e6;
  background: #f6f6f6;
  color: #666666;
  cursor: pointer;
}
.pages #Pagination .pagination .prev i,
.pages #Pagination .pagination .next i {
  display: inline-block;
  width: 4px;
  height: 11px;
  margin-right: 5px;
  /*background: url(icon.fw.png) no-repeat;*/
}
.pages #Pagination .pagination .prev {
  border-right: none;
}
.pages #Pagination .pagination .prev i {
  background-position: -144px -1px;
  *background-position: -144px -4px;
}
.pages #Pagination .pagination .next i {
  background-position: -156px -1px;
  *background-position: -156px -4px;
}
.pages #Pagination .pagination .pagination-break {
  padding: 11px 5px;
  border: none;
  border-left: 1px solid #e6e6e6;
  background: none;
  cursor: default;
}
.pages .searchPage {
  float: left;
  padding: 8px 0;
}
.pages .searchPage .page-sum {
  padding: 11px 13px;
  color: #999999;
  font-family: \u5b8b\u4f53,Arial;
  font-size: 14px;
}
.pages .searchPage .page-go {
  padding: 8px 0;
  color: #999999;
  font-family: \u5b8b\u4f53,Arial;
  font-size: 14px;
  padding: 10px 0\9;
  *padding: 6px 0;
}
.pages .searchPage .page-go input {
  width: 21px;
  height: 20px;
  margin: 0 5px;
  padding-left: 5px;
  border: 1px solid #e4e4e4;
}
.pages .searchPage .page-btn {
  margin: 9px 0 5px 5px;
  padding: 2px 5px;
  background: #ffac59;
  border-radius: 2px;
  color: #ffffff;
  font-family: Arial, 'Microsoft YaHei';
  font-size: 14px;
  text-decoration: none;
}
.nav_b{font-size:18px;}
.nav_b a{color:#000;}
.big_box{background:#fff;}
.h_row_list{margin-bottom:1%;}
.checks>div,.h_row_list>div{display:inline-block;}
.noe{width:100px;}
.cheng_fan,.wenxi_fan{font-weight:normal;}
tbody tr td,thead > tr >th{border: 1px solid #fff !important;}
thead > tr >th{color:#fff;font-weight:normal;font-size:13px;text-align:center;}
thead > tr >th:nth-of-type(1),thead > tr >th:nth-of-type(2){background:#33CC99;}
thead > tr >th:nth-of-type(3),thead > tr >th:nth-of-type(4){background:#FFCC66;}
tbody > tr >td:nth-of-type(1),tbody > tr >td:nth-of-type(3){text-align: left;}
tbody > tr >td:nth-of-type(2),tbody > tr >td:nth-of-type(4){text-align: right;}
tbody > tr >td:nth-of-type(5){text-align: right;}
tbody tr td{text-align:center;background:#F2F2F2;}
table{border:none !important;}
.bg_339{background:#339900;}
.active{color:#FF0000;}
.sech_btn{background:#009900;padding:3.5px 38px;margin-left:4%;color:#fff;border: 1px solid #d7e0f1;}
.h_row_list .a{margin-right:30px;position:relative;text-indent:9px;}
#u31_input,#u33_input{position:absolute;left:0px;}
.hotel_name {
    font-size: 16px;
    background: #F2F2F2;
    padding: 10px 10px;
    border-radius: 3px;
}
</style>
<div class="content-wrapper" style="min-height:775px;">
<div class="banner bg_fff p_0_20">
    <?php echo $breadcrumb_html; ?>
</div>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="big_box">
					<!-- <div class="nav_b">
						<a>比价 </a> >
						<a> 比价详情</a>
					</div> -->
					<?php if(empty($one_hotel)){?>
					<form action="<?php echo site_url('price/paritys/detail');?>" method="get">
					<input type="hidden" value="<?php echo $inter_id;?>" name="inter_id">
					<div class="big_box" style="background: #fff;padding: 12px 12px 0px 15px;">
						<div class="h_row_list">
							<div class="noe">酒店</div>
							<div>
								<select name="hotel_ids" style="padding:1.5px;">
									<option value="0">请选择酒店</option>	
									<?php foreach ($hotel_ids as $kh => $vh) {?>
									<option value="<?php echo $vh['hotel_id'];?>" <?php if($vh['hotel_id']==$hotel_id){ echo 'selected';}?>><?php echo $vh['name'];?></option>
									<?php }?>
								</select>
								<input type="text" style="text-indent:3px;" name="wd" value="<?php echo $wd;?>" placeholder="或输入关键字"/>
							</div>
							<button class="sech_btn" id="search">搜索</button>
						</div>
						<div class="h_row_list">
							<div class="noe">酒店排序</div>
							<div>
								<select name="s">
									<option value="0">默认</option>	
								<?php foreach ($ss as $ks => $vs) {?>
									<option value="<?php echo $ks;?>" <?php if($ks==$s){ echo 'selected';}?>><?php echo $vs;?></option>		
								<?php }?>
								</select>
							</div>
							<!-- <div style="margin-left:2%;"><?php echo implode('/', $ss);?></div> -->
						</div>
						
						<!-- <div class="h_row_list">
							<div class="noe">对比房型</div>
							<div class="checks">
								<?foreach ($third_type as $kt => $vt) {?>
								<div id="" class="a" style="margin-right:30px;">
							        <input id="u31_input" type="radio" name="ttype" value="<?php echo $kt;?>" class="xcfs" <?php if($ttype==$kt){ echo 'checked';}?>
							        >
							        <label for="u31_input" class="xcfs">
							          <div id="" class="">
							            <p class="" id=""><span class="wenxi_fan" id=""><?php echo $vt;?></span></p>
							          </div>
							        </label>
							    </div>
							    <?php }?>
							</div>
						</div> -->
					</div>
					</form>
					<?php };?>
					<div class="box-body contion_box" style="background: #fff;padding: 12px 12px 0px 15px;">
	            		<?php $i=0;foreach ($lists as $k => $list) {?>
	            		<div class="hotel_name"><?php echo ++$i;?>. <?php echo $k.' ( 倒挂率'.(!empty($list[0]['hotel_id'])?$down_rates[$list[0]['hotel_id']]:'--').'% )';?></div>
		            	<table id="hotel_fang" class="table table-bordered table-striped" style="margin-bottom:20px;">
		            		<thead style="background:#fff;">
						        <tr>
						            <th style="">微信房型</th>
						            <th style="">微信价格</th>
						            <th style="">携程房型</th>
						            <th style="">携程价格</th>
						            <th class="bg_339" style="">差价</th>
						        </tr>
						    </thead>
					        <tbody class="new_body">
					        	<?php if(!empty($list)){foreach ($list as $kl => $vl) {?>
					            <tr>
					            <?php if(!empty($vl['non'])){?>
					            <?php }else{?>
					                <td class="<?php if($vl['chajia_rev']>0){echo 'active';}?>" style="padding-left:30px;<?php if(isset($vl['cop'])&&$vl['cop']>0){echo 'vertical-align: inherit;';}?>" <?php if(isset($vl['cop'])&&$vl['cop']>0){echo 'rowspan="'.$vl['cop'].'"';}?>><?php echo $vl['iwide_name'].'--'.$vl['iwide_price_name'].$vl['ibreakfast'].$vl['book_status'];?></td>
					                <td class="<?php if($vl['chajia_rev']>0){echo 'active';}?>" style="padding-right:18px;<?php if(isset($vl['cop'])&&$vl['cop']>0){echo 'vertical-align: inherit;';}?>" <?php if(isset($vl['cop'])&&$vl['cop']>0){echo 'rowspan="'.$vl['cop'].'"';}?>><?php echo '¥'.$vl['iwide_price'];?></td>
					            <?php }?>
					                <td style="padding-left:30px;"><?php echo $vl['ctrip_name'].'--'.$vl['ctrip_bed'].'--'.$vl['ctrip_breakfast'];?></td>
					                <td style="padding-right:18px;"><?php echo !empty($vl['ctrip_price'])?'¥'.$vl['ctrip_price']:'¥0.00';?></td>
					                <td class="<?php if($vl['chajia_rev']>0){echo 'active';}?>" style="text-align:right;<?php if($vl['chajia_rev']<=0){ echo 'padding-right:19px;';}?>"><?php if($vl['chajia_rev']>0){echo '+'.$vl['chajia_rev'].' ↑';}else{echo $vl['chajia_rev'];}?></td>
					            </tr>
					            <?php }}?>
					        </tbody>
					    </table>
					    <?php if(empty($list)){echo '<div style="margin-bottom:10px;">无房型匹配结果！</div>';}?>
  				        <?php }?>
			        </div>
			        <?php if(empty($one_hotel)){?>
			    <div class="pages">
			        <div id="Pagination">
			        <div class="pagination"><?php echo $page;?></div>
			        </div>
					<div class="searchPage">
					<span class="page-sum">共<strong class="allPage"><?php echo $pages;?></strong>页</span>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
	</section>
</div><!-- /.content-wrapper -->
<?php
/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>

<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
?>



<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>
<script type="text/javascript">
window.onload=function() {
	// for(var i=0;i<$(".new_body tr").length;i++){
	// 	var money_td1=$(".new_body tr").eq(i).find("td:nth-of-type(2)").html()
	// 	var money_td2=$(".new_body tr").eq(i).find("td:nth-of-type(4)").html()
	// 	var money=money_td1.substring(1,money_td1.length-1);
	// 	var money2=money_td2.substring(1,money_td1.length-1);
	// 	if((money-money2)>0&&money2>0){
	// 		$(".new_body tr").eq(i).find("td:nth-of-type(1)").addClass('active');
	// 		$(".new_body tr").eq(i).find("td:nth-of-type(2)").addClass('active');
	// 		$(".new_body tr").eq(i).find("td:last-child").addClass('active');
	// 	}
	// }
	$('#search').click(function(){
		$('#searchf').submit();
	});
};
</script>