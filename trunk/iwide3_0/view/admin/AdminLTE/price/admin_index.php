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
<script src="<?php echo base_url(FD_PUBLIC)?>/js/jquery.min.js"></script>
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




.bg_fff{background:#fff;}
.m_b_3{margin-bottom:3px;}
.w_60{width:60px;}
.w_200{width:200px;}
select,input,.moba{height:30px;line-height:30px;border:1px solid #d7e0f1;text-indent:3px;}
.nav_b{font-size:18px;}
.nav_b a{color:#000;}
.box-title{font-size:24px !important;}
.r_btn{float:right;background:#009900;font-size:13px;color:#fff;padding:2px 22px;}
.m_width{max-width:450px;}
.title_t{background:#FFCC66;}

.tables{margin-bottom:20px;}
.table-bordereds{border:0px solid #f4f4f4;border-radius:4px;}
.new_body >tr:nth-of-type(1)>td a,.new_body >tr:nth-of-type(2)>td a,.new_body >tr:nth-of-type(3)>td a{color:#FF0000;}
.new_body >tr>td a{color:#000;}
.new_body >tr>td:after{content: "" ;display:block;height:0;clear:both;visibility:hidden;}
tbody > tr td> a span{display:inline-block;}
.numb_text{width:10px;margin-right:3px;}
.numb_na{width:250px;margin-right:10px;}
.fasd{float:right;color:#999;}
tbody > tr td{background:#fff;}
.box{box-shadow:0 0px 0px rgba(0,0,0,0.1)}
.hotel_ranking{background:#FFCC66;font-size:16px;padding:8px 12px;border:1px solid #E4E4E4;}
/*.box-body >div{display:inline-block;}*/
hr{margin-top:0px;margin-bottom:0px;}
.title{padding:10px;display:table;width:100%;}
.title >div{display:table-cell;text-align:center;}
.title >div:nth-of-type(1){width:40%;text-align:left;}
.title >div:nth-of-type(1) >div{display:inline-block;}
.search_btn{width:100px;text-align:center;color:#fff;background:#009900;height:31px;}

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
						<a> 运营版</a>
					</div> -->
					<div class="big_box" style="padding:0% 0%;margin-top:0%; min-width:850px;">
						<form id="searchf" action="<?php echo site_url('price/paritys/admin_index');?>" method="get">
						<div class="title bg_fff m_b_3">
							<div>
								<div><input class="" name="wd" type="text" value="<?php echo $wd;?>" placeholder="请输入公众号名" /></div>
								<button class="search_btn moba" id="search">搜索</button>
							</div>
							<div>
								<select class="w_200" name="s">
								<?php foreach ($ss as $ks => $vs) {?>
									<option value="<?php echo $ks;?>" <?php if($s==$ks){ echo 'selected';}?>><?php echo $vs;?></option>
								<?php }?>
								</select>
							</div>
							<div>
								每页显示
								<select class="w_60" name="n">
								<?php foreach ($ns as $vn) {?>
									<option value="<?php echo $vn;?>" <?php if($n==$vn){ echo 'selected';}?>><?php echo $vn;?></option>
								<?php }?>
								</select> 条
							</div>
						</div>
						</form>
						<?php foreach ($list as $kh => $vh) {?>
						<div id="coupons_table" class="tables table-bordereds" >
						    <div style="padding:2% 0;background:#fff;">
											<div class="box-header">
								            	<h3 class="box-title"><?php echo $kh;?></h3>
									            </div>
						            	<div class="box-body">
			            					<div class="">
								            	<div class="hotel_ranking">携程倒挂率排名												<a class="r_btn" target="_blank" href="<?php echo site_url('price/paritys/hotel_index?inter_id='.$inter_ids[$kh]);?>">点此查看更多   > </a></div>
								            	<table id="sam_box_yi" class="table table-bordered table-striped">
											        <tbody class="new_body">
											        <?php foreach ($vh as $key => $value) {?>
											            <tr>
											                <td>
											                	<a style="display:block;" target="_blank" href="<?php echo site_url('price/paritys/detail?inter_id='.$inter_ids[$kh].'&hotel_id='.$value['hotel_id']);?>"><i class="fa fasd fa-fw fa-arrow-circle-o-right"></i>
											                	<span class="numb_text"><?php echo $key+1;?></span>
											                	<span class="numb_na"><?php echo $value['hotel_name'];?></span>
											                	<span class="percentage"><?php echo $value['percent'].'%';?></span>
											                	</a>
											                </td>
											            </tr>
											            <?php }?>
											        </tbody>
											    </table>
								            </div>
										</div>
									</div>
						        <hr style="width:85%;color:#666;height:3px;background:#666;border:0px;border-radius:6px;">
							</div>
						<?php }?>
				<div class="pages">
					<div id="Pagination">
					<div class="pagination"><?php echo $page;?></div>
					</div>
					<div class="searchPage">
					<span class="page-sum">共<strong class="allPage"><?php echo $pages;?></strong>页</span>
					</div>						
				</div>
				</div>
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
	$('#search').click(function(){
		$('#searchf').submit();
	});
	$('select[name=s]').change(function(){
		$('#searchf').submit();
	});
	$('select[name=n]').change(function(){
		$('#searchf').submit();
	});
};
</script>