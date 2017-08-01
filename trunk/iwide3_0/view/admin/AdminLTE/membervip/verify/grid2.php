<!-- DataTables -->
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet"
	href="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/images/laydate.css">
<link rel="stylesheet"
	href="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/images/laydate12.css">

<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script
	src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script
	src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script
	src="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script
	src="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
</head>
<style>
.btn{min-width:100px}
.input{display:inline-block}
.input select,.input input{width:163px}
.marbtm{margin-bottom:12px;}
table a{color:#39C}
main {
	background-color: white;
	position: absolute;
	width: 100%;
	height: 100%;
}

.begray{
    background-color: #e5e5e5 !important;
    color: white !important;
    cursor: not-allowed !important;
}

.tan {
	position: absolute;
	background-color: rgba(0, 0, 0, 0.5);
	width: 100%;
	height: 100%;
	z-index: 9999
}

.hysh {
	background-color: white;
	text-align: center;
	width: 400px;
	margin: 50px auto;
	border: 1px solid black;
	border-radius: 5px;
	position: relative;
}

title {
	display: block;
	font-size: 1.5em;
	font-weight: bold;
	margin: 10px;
}

line {
	display: flex;
	justify-content: center;
	align-items: center;
	margin: 10px;
}

left {
	width: 50%;
	text-align: right;
}

right {
	width: 50%;
	text-align: left;
}

ib {
	display: inline-block;
	text-align: left;
	width: 100px;
}

right textarea {
	width: 150px;
	height: 80px;
}

right input {
	width: 150px;
}

.none {
	display: none;
}

.a, .b, .c {
	display: none;
}

.tan {
	display: none;
}

.butongguo {
	animation: slideInDown 0.5s forwards;
	-webkit-animation: slideInDown 0.5s forwards;
	-moz-animation: slideInDown 0.5s forwards;
	-o-animation: slideInDown 0.5s forwards;
	display: none;
}

@
keyframes slideInDown {from { transform:translate3d(0, -100%, 0);
	visibility: visible;
}

to {
	transform: translate3d(0, 0, 0);
}

}
@
-webkit-keyframes slideInDown {from { -webkit-transform:translate3d(0,
	-100%, 0);
	visibility: visible;
}

to {
	-webkit-transform: translate3d(0, 0, 0);
}

}
@
-moz-keyframes slideInDown {from { -moz-transform:translate3d(0, -100%,
	0);
	visibility: visible;
}

to {
	-moz-transform: translate3d(0, 0, 0);
}

}
@
-o-keyframes slideInDown {from { -o-transform:translate3d(0, -100%, 0);
	visibility: visible;
}

to {
	-o-transform: translate3d(0, 0, 0);
}
}
</style>
<style>
.weborder {
	background: #FFFFFF !important;
	display: none;
}

.morder {
	background: #FAFAFA !important;
}

.a_like {
	cursor: pointer;
	color: #72afd2;
}

.page {
	text-align: right;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 2em;
}

.page a {
	padding: 10px;
}

.current {
	color: #000000;
}
</style>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="modal fade" id="setModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">显示设置</h4>
				</div>
				<div class="modal-body">
					<div id='cfg_items'>
        <?php echo form_open('distribute/distri_report/save_cofigs','id="setting_form"')?>
          
        </form>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					<button type="button" class="btn btn-primary" id="set_btn_save">保存</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
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
@font-face {
	font-family: 'iconfont';
	src: url('http://test008.iwide.cn/public/newfont/iconfont.eot');
	src: url('http://test008.iwide.cn/public/newfont/iconfont.eot?#iefix')
		format('embedded-opentype'),
		url('http://test008.iwide.cn/public/newfont/iconfont.woff')
		format('woff'),
		url('http://test008.iwide.cn/public/newfont/iconfont.ttf')
		format('truetype'),
		url('http://test008.iwide.cn/public/newfont/iconfont.svg#iconfont')
		format('svg');
}

.iconfont {
	font-family: "iconfont" !important;
	font-size: 16px;
	font-style: normal;
	-webkit-font-smoothing: antialiased;
	-webkit-text-stroke-width: 0.2px;
	-moz-osx-font-smoothing: grayscale;
}

.over_x {
	width: 100%;
	overflow-x: auto;
	-webkit-overflow-scrolling: touch;
	-webkit-overflow-scrolling: touch;
	overflow-scrolling: touch;
}

.clearfix:after {
	content: "";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}

.bg_fff {
	background: #fff;
}

.color_fff {
	color: #fff;
}

.bg_ff0000 {
	background: #ff0000;
}

.bg_f8f9fb {
	background: #f8f9fb;
}

.bg_ff9900 {
	background: #ff9900;
}

.bg_f8f9fb {
	background: #f8f9fb;
}

.bg_fe6464 {
	background: #fe6464;
}

.bg_eee {
	background: #EEEEEE
}

.color_72afd2 {
	color: #72afd2;
}

.color_ff9900 {
	color: #ff9900;
}

.color_F99E12 {
	color: #F99E12;
}

a {
	color: #92a0ae;
}

.relative {
	position: relative;
}

.absolute {
	position: absolute;
}

.border_1 {
	border: 1px solid #d7e0f1;
}

.f_r {
	float: right;
}

.f_l {
	float: left;
}

.p_0_20 {
	padding: 0 20px;
}

.w_90 {
	width: 90px;
}

.w_200 {
	width: 200px;
}

.p_r_30 {
	padding-right: 30px;
}

.m_t_10 {
	margin-top: 10px;
}

.p_0_30_0_10 {
	padding: 0 30px 0 10px;
}

.b_b_1 {
	border-bottom: 1px solid #d7e0f1;
}

.b_t_1 {
	border-top: 1px solid #d7e0f1;
}

.p_t_10 {
	padding-top: 10px;
}

.p_b_10 {
	padding-bottom: 10px;
}

 

.banner {
	height: 50px;
	width: 100%;
	line-height: 50px;
	border-bottom: 1px solid #d7e0f1;
	padding-right: 0px;
}

.banner>span {
	padding: 0px 5px;
	margin-left: 5px;
	border-radius: 3px;
	font-size: 11px;
}

.news {
	position: relative;
	cursor: pointer;
	background: #fe8f00;
	height: 100%;
	padding: 0px 12px;
	color: #fff;
}

.news_radius {
	padding: 0 2px;
	border-radius: 3px;
	background: #fff;
	color: #fe8f00;
	text-align: center;
	font-size: 8px;
	margin-left: 8px;
}

.display_flex {
	display: flex;
	display: -webkit-flex;
	display: box;
	display: -webkit-box;
	justify-content: top;
	align-items: center;
	-webkit-align-items: center;
}

.display_flex>th, .display_flex>td, .display_flex>div {
	-webkit-flex: 1;
	flex: 1;
	-webkit-box-flex: 1;
	box-flex: 1;
	cursor: pointer;
}

.j_toshow {
	width: 320px;
	min-height: 100%;
	position: absolute;
	top: 50px;
	right: -330px;
	box-shadow: -5px 0px 15px rgba(0, 0, 0, 0.1);
	-webkit-box-shadow: -5px 0px 15px rgba(0, 0, 0, 0.1);
	z-index: 9999;
}

.toshow_con {
	padding: 12px;
}

.t_con_list {
	margin-bottom: 12px;
	height: 170px;
}

.close_btn {
	cursor: pointer;
}

.toshow_con_titl {
	background: #f0f3f6;
	font-size: 13px;
	padding: 10px;
	border-bottom: 1px solid #d7e0f1;
}

.toshow_con_list {
	padding: 10px;
	font-size: 11px;
	height: 114px;
	overflow: hidden;
}

.toshow_con_list>a {
	display: block;
	margin-bottom: 5px;
}

.toshow_con_list>a:last-child {
	margin-bottom: 0px;
}

.toshow_titl_txt {
	position: relative;
}

.radius_txt {
	position: absolute;
	top: 0px;
	left: 105%;
	border-radius: 3px;
	text-align: center;
	padding: 0px 3px;
	font-size: 12px;
}

select, input, .moba {
	height: 30px;
	line-height: 30px;
	border: 1px solid #d7e0f1;
	text-indent: 3px;
}

.contents {
	padding: 20px 0 20px 20px;
}

.contents_list {
	display: table;
	width: 100%;
	border-bottom: 1px solid #d7e0f1;
	margin-bottom: 10px;
}

.head_cont {
	padding: 20px 0 20px 10px;
}

.head_cont>div {
	margin-bottom: 10px;
	cursor: pointer;
}

.head_cont>div:last-child {
	margin-bottom: 0px;
}

.j_head>div {
	display: inline-block;
}

.j_head>div:nth-of-type(1) {
	width: 307px;
}

.j_head>div:nth-of-type(2) {
	width: 432px;
}

.j_head>div:nth-of-type(3) {
	width: 255px;
}

.j_head>div>span:nth-of-type(1) {
	display: inline-block;
	width: 60px;
	text-align: center;
}

.h_btn_list .actives {
	background: #ff9900;
	color: #fff;
	border: 1px solid #ff9900 !important;
}

.h_btn_list>div {
	display: inline-block;
	width: 100px;
	border: 1px solid #d7e0f1;
	text-align: center;
	padding: 6px 0px;
	border-radius: 5px;
	margin-right: 8px;
	cursor:pointer;
	margin-top:6px;
	background:#fff;
}

.h_btn_list>div:last-child {
	margin-right: 0px;
}

.classification {
	height: 30px;
	line-height: 30px;
	border-top: 1px solid #d7e0f1;
	border-right: 1px solid #d7e0f1;
	border-left: 1px solid #d7e0f1;
	width: 300px;
}

.classification>div {
	text-align: center;
	height: 30px;
	border-right: 1px solid #d7e0f1;
}

.classification>div:last-child {
	border-right: none;
}

.classification .add_active {
	background: #ecf0f5;
	border-bottom: 1px solid #ecf0f5;
}

.fomr_term {
	height: 30px;
	line-height: 30px;
}

.classification>div, .all_open_order {
	cursor: pointer;
}

.all_open_order {
	margin-right: 10px;
	margin-top: 5px;
}

.template>div {
	text-align: center;
}

.template_img {
	float: left;
	width: 50px;
	height: 50px;
	overflow: hidden;
	vertical-align: middle;
	margin-right: 2%;
}

.template_span {
	display: inline-block;
	margin-top: 2px;
}

.template_btn {
	padding: 1px 8px;
	border-radius: 3px;
}

.form_con, .form_title {
	height: 30px;
	line-height: 30px;
}

.form_con>td, .form_title>th {
	text-align: center;
	font-weight: normal;
}

.form_con>td:nth-of-type(1)>img {
	display: inline-block;
	width: 48px;
	height: 48px;
	border-radius: 50%;
	border: 1px solid #d7e0f1;
	overflow: hidden;
}

.form_thead>tr, .containers>tr {
	padding: 8px 0;
}

.form_title>th:nth-of-type(2), .form_con>td:nth-of-type(2) {
	flex: 1.5;
}

.form_title>th:nth-of-type(7), .form_con>td:nth-of-type(7) {
	flex: 2.9;
}

.form_title>th:nth-of-type(6), .form_con>td:nth-of-type(6) {
	flex: 2.9;
}

.containers>tr:nth-child(even) {
	background: #F8F8F8 !important;
}

.containers>tr:nth-child(odd) {
	background: #fff !important;
}

.form_con>td {
	padding-right: 20px !important;
}

.box-body {
	padding: 0px;
	overflow: hidden;
}



.drow_list {

	position: absolute;
	width: 100%;
	top: 100%;
	left: 0;
	background: #fff;
	border: 1px solid #e4e4e4;
	padding: 0;
	overflow: auto;
	z-index: 999
}

.drow_list li {
	height: 35px;
	padding-left: 15px;
	line-height: 35px;
	list-style: none;
	cursor: pointer
}

.drow_list li:hover {
	background: #f1f1f1
}

.drow_list li.cur {
	background: #ff9900;
	color: #fff
}

#drowdown:hover .drow_list {
	display: block
}

#selects_membeb {
	display: inline-block;
	width: auto;
	vertical-align: middle;
	margin-right: 25px;
}

/* #data-grid_wrapper>.row:first-child { */
/* 	background: #fff; */
/* 	padding: 10px; */
/* } */

.fixed_box {
	position: fixed;
	top: 30%;
	left: 48%;
	z-index: 9999;
	border: 1px solid #d7e0f1;
	border-radius: 5px;
	padding: 1% 2%;
	display: none;
}

.tile {
	font-size: 15px;
	text-align: center;
	margin-bottom: 4%;
}

.f_b_con {
	font-size: 13px;
	margin-bottom: 8%;
}

.f_b_con span:first-child {
	display: inline-block;
	width: 80px;
	text-align: right;
	margin-right: 5px;
}

.pointer, .delivery, .confirms, .cancel {
	cursor: pointer;
}

.pagination {
	margin-top: 0px;
	margin-bottom: 0px;
}

.btn_list_r span {
	margin-right: 10px;
}

.btn_list_r span:last-child {
	margin-right: 0px;
}

.f_b_con i {
	right: 8px;
	top: 1px;
	font-style: normal;
}

.all_btn::after {
	content: "" !important;
}

label {
	margin-bottom: 0px;
	font-weight: normal;
}
.left {width: 35%;}
</style>
		<div class="fixed_box bg_fff">
			<div class="tile"></div>
			<div class="f_b_con" style="text-align: center;">审核通过了个0会员。</div>
			<div class="h_btn_list clearfix" style="">
				<div class="actives confirms">确定</div>
				<div class="cancel f_r">取消</div>
			</div>
		</div>
		<div style="color: #92a0ae;">
			<div class="over_x">
				<div class="content-wrapper" style="min-width: 1480px;">
					<!-- 弹出层 -->

					<div class="tan">
						<div class="tan_back"
							style="position: absolute; width: 100%; height: 100%;"></div>
						<div class="hysh a">
							<title>会员审核</title>
							<line> <left> <ib>会员ID:</ib></left> <right class='a_tan_id'></right>
							</line>
							<line> <left> <ib>会员名称:</ib></left> <right class='a_tan_name'></right>
							</line>
							<line> <left> <ib>会员卡号:</ib></left> <right> <input type="text"
								class='a_tan_num' value=""></right> </line>
							<line> <left> <ib>会员等级:</ib></left> <right class='a_tan_lvl'></right>
							</line>
							<line> <left> <ib>手机号码:</ib></left> <right class='a_tan_phone'></right>
							</line>
							<!-- <line> <left> <ib>邮箱地址:</ib></left> <right class='a_tan_email'></right>
							</line> -->
							<line> <left> <ib>身份证号码:</ib></left> <right class='a_tan_idno'></right>
							</line>
							<!-- <line> <left> <ib>公司名称:</ib></left> <right> <input type="text"
								value="" class='a_tan_company'></right> </line>
							<line> <left> <ib>职务:</ib></left> <right> <input type="text"
								value="" class='a_tan_duty'></right> </line> -->
							<line> <left> <ib>提交时间:</ib></left> <right class='a_tan_creat'></right>
							</line>
							<line> <left> <ib>备注:</ib></left> <right> <textarea name="" id=""
								cols="30" rows="10" class='a_tan_remark'></textarea></right> </line>
							<div class="guan">
								<div style="margin-top: 50px;">审核通过后，将自动升级为BOSS名人卡</div>
								<line>
								<button style="margin: 5px; padding: 5px;" id='a_pass'>审核通过</button>
								<button style="margin: 5px; padding: 5px;" class='no-verify'>审核不通过</button>
								</line>
							</div>
							<div style="overflow: hidden;">
								<div style="border-top: 1px solid black;" class="butongguo">
									<title>审核不通过原因</title>
									<div style="margin: 20px 0px;">
										<line
											style="margin: 0px 50px;text-align: left;justify-content:left;">
										<input type="radio" name="reason" value=1 checked="checked"> <span>资料不符合要求</span>
										</line>
									</div>
									<div style="margin: 20px 0px;">
										<line
											style="margin: 0px 50px;text-align: left;justify-content:left;">
										<input type="radio" name="reason" value=2> <span><input
											type="text" name="reason_remark"> 请至少输入5个字</span> </line>
									</div>
									<div style="margin: 20px 0px;">
										<div style="margin: 0px 50px; text-align: left;">选择的审核不通过原因将以模板消息方式通知给用户，请谨慎选择填写</div>
									</div>
									<line>
									<button style="margin: 5px; padding: 5px;" id='a_unpass'>提交审核结果</button>
									</line>
								</div>
							</div>
						</div>
						<!-- 弹出层B -->
						<div class="hysh b">
							<title>新会员注册</title>
							<line> <left> <ib>ID:</ib></left> <right class='b_tan_id'> </right>
							</line>
							<line> <left> <ib>会员名称:</ib></left> <right class='b_tan_name'> </right>
							</line>
							<line> <left> <ib>会员卡号:</ib></left> <right> <input type="number"
								value="" class='b_tan_num'> <span style="color: red;">9位必填</span></right>
							</line>
							<line> <left> <ib>会员等级:</ib></left> <right class='b_tan_lvl'></right>
							</line>
							<line> <left> <ib>手机号码:</ib></left> <right class='b_tan_phone'></right>
							</line>
							<line> <left> <ib>邮箱地址:</ib></left> <right class='b_tan_email'></right>
							</line>
							<line> <left> <ib>身份证号码:</ib></left> <right class='b_tan_idno'></right>
							</line>
							<line> <left> <ib>公司名称:</ib></left> <right> <input type="text"
								value="" class='b_tan_company'></right> </line>
							<line> <left> <ib>职务:</ib></left> <right> <input type="text"
								value="" class='b_tan_duty'></right> </line>
							<line> <left> <ib>提交时间:</ib></left> <right class='b_tan_creat'></right>
							</line>
							<line> <left> <ib>备注:</ib></left> <right> <textarea name="" id=""
								class='b_tan_remark' cols="30" rows="10"></textarea></right> </line>
							<div style="margin-top: 50px;">注册成功后，将自动升级为洲际优悦会员</div>
							<line>
							<button style="margin: 5px; padding: 5px;" class='b_modify'>完成注册</button>
							</line>
						</div>
						<!-- 弹出层C -->
						<div class="hysh c">
							<title>新会员注册</title>
							<line> <left> <ib>ID:</ib></left> <right class='c_tan_id'> </right>
							</line>
							<line> <left> <ib>会员名称:</ib></left> <right class='c_tan_name'> </right>
							</line>
							<line> <left> <ib>会员卡号:</ib></left> <right> <input type="number"
								value="" class='c_tan_num'> <span style="color: red;">9位必填</span></right>
							</line>
							<line> <left> <ib>会员等级:</ib></left> <right class='c_tan_lvl'></right>
							</line>
							<line> <left> <ib>手机号码:</ib></left> <right class='c_tan_phone'></right>
							</line>
							<line> <left> <ib>邮箱地址:</ib></left> <right class='c_tan_email'></right>
							</line>
							<line> <left> <ib>身份证号码:</ib></left> <right class='c_tan_idno'></right>
							</line>
							<line> <left> <ib>公司名称:</ib></left> <right> <input type="text"
								value="" class='c_tan_company'></right> </line>
							<line> <left> <ib>职务:</ib></left> <right> <input type="text"
								value="1" class='c_tan_duty'></right> </line>
							<line> <left> <ib>提交时间:</ib></left> <right class='c_tan_creat'></right>
							</line>
							<line> <left> <ib>备注:</ib></left> <right> <textarea name="" id=""
								class='c_tan_remark' cols="30" rows="10"></textarea></right> </line>
							<div style="margin-top: 50px;">注册成功后，将自动升级为洲际优悦会员</div>
							<line>
							<button style="margin: 5px; padding: 5px;" class='c_modify'>修改</button>
							</line>
						</div>
</div>
					<!-- 弹出层end -->
					<div class="banner bg_fff p_0_20">会员审核</div>
					<div class="contents">
						<div class="box-body" style="margin-top: 18px;">
							<table id="data-grid"
								class="table-striped table-condensed dataTable no-footer table "
								style="width: 100%;">
								<thead class="bg_f8f9fb form_thead">
									<tr>
                            <?php foreach ($fields_config as $k=> $v):?>
                            <th
											<?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?>>
                                <?php echo $v['label'];?>
                            </th>
                            <?php endforeach;?>
                        </tr>
								</thead>

								<tbody
									class="containers dataTables_wrapper form-inline dt-bootstrap">
                        
                      </tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	
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
<script
			src="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/layDate.js"></script>
		<!--日历调用结束-->
		<script>
;!function(){
  laydate({
     elem: '#datepicker'
  })
  laydate({
     elem: '#datepicker2'
  })
}();
</script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.member.min.js"></script>
 
		<script>
$(function(){

  var odiv=$('<div class="h_btn_list" style=""><div class="actives" id="subbtn">审核</div></div>');
  url_ajax="<?php echo base_url("index.php/membervip/verify/grid");?>";
  $('#data-grid').dataTable({
        "aLengthMenu": [8,50,100,200],
        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0 ] }],
      "iDisplayLength": 10,
      "bProcessing": true,
      "paging": true,
      "lengthChange": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "searching": true,
//       "serverSide": true,
      "ajax": {
          "type": 'POST',
          "url": url_ajax,
          "data": {<?php echo config_item('csrf_token_name') ?>: '<?php echo $this->security->get_csrf_hash() ?>' }
      },
      "language": {
        "sSearch": "搜索",
        "lengthMenu": "每页显示 _MENU_ 条记录",
        "zeroRecords": "找不到任何记录. ",
//         "info": "",
        "info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
        "infoEmpty": "",
        "infoFiltered": "(从 _MAX_ 条记录中过滤)",
        "paginate": {
          "sNext": "下一页",
          "sPrevious": "上一页",
        }
      }
  });
  $("#data-grid_length").parent().append( odiv );

  $('.row').delegate('#subbtn','click',function(){
	  $("input[name='member_id']:checked").each(function(){ 
		  //ajax资料回显
		  var postUrl = "<?php echo base_url("index.php/membervip/verify/ajax_get_member_info");?>";
		  var datas={id:$(this).val()};
		  $.ajax({
              url:postUrl,
              type:'GET',
              data:datas,
              dataType:'json',
              timeout:6000,
              success: function (result) {
                  if(result.err==0){
                	  data=result.data
                	  if(data.audit!=0&&data.audit!=2){
                    	  alert('该会员已经审核过');
							return false;
                    	  }
                	  if(data.type=='old'){
                	  $(".a_tan_id").text(data.id);
                	  $(".a_tan_name").text(data.name);
                	  $(".a_tan_num").val(data.membership_number);
                	  $(".a_tan_lvl").text(data.lvl_name);
                	  $(".a_tan_phone").text(data.telephone);
                	  $(".a_tan_email").text(data.email);
                	  $(".a_tan_idno").text(data.id_card_no);
                	  $(".a_tan_company").val(data.company_name);
                	  $(".a_tan_duty").val(data.duty);
                	  $(".a_tan_creat").text(data.createtime);
                	  tan_diapaly('.a');
                      }else{
                	  $(".b_tan_id").text(data.id);
                	  $(".b_tan_name").text(data.name);
                	  $(".b_tan_num").val(data.membership_number);
                	  $(".b_tan_lvl").text(data.lvl_name);
                	  $(".b_tan_phone").text(data.telephone);
                	  $(".b_tan_email").text(data.email);
                	  $(".b_tan_idno").text(data.id_card_no);
                	  $(".b_tan_company").val(data.company_name);
                	  $(".b_tan_duty").val(data.duty);
                	  $(".b_tan_creat").text(data.createtime);
                	  tan_diapaly('.b');
                      }
                      }else{
						alert('网络异常');
                          }
              },
              error: function () {
                  alert('网络繁忙');
              }
          });
		 
	  }); 
	  });
$(".tan_back").click(function(){
	tan_hiden();
	 $('.guan').css("display","block");
	 $('.butongguo').css("display","none");
});
$(".no-verify").click(function(){
	 $('.guan').css("display","none");
	 $('.butongguo').css("display","block");
});
	  function tan_diapaly(where){
		  //弹出层显示
		  $('.tan').css("z-index","9999");
		  $('.tan').css("display","block");
		  $(where).css("display","block");
		  }
	  
	  function tan_hiden(){
			//弹出层隐藏
		  $('.tan').css("z-index","0");
		  $('.tan').css("display","none");
		  $(".a").css("display","none");
		  $(".b").css("display","none");
		  $(".c").css("display","none");
		  }
  $('.row').delegate('#subbtn2','click',function(){
	  $("input[name='member_id']:checked").each(function(){ 
		  //ajax资料回显
		  var postUrl = "<?php echo base_url("index.php/membervip/verify/ajax_get_member_info");?>";
		  var datas={id:$(this).val(),inter_id:'<?php echo $inter_id?>'};
		  $.ajax({
              url:postUrl,
              type:'GET',
              data:datas,
              dataType:'json',
              timeout:6000,
              success: function (result) {
                  if(result.err==0){
                	  data=result.data
                      if(data.audit==1){
                	  $(".c_tan_id").text(data.id);
                	  $(".c_tan_name").text(data.name);
                	  $(".c_tan_num").val(data.membership_number);
                	  $(".c_tan_lvl").text(data.lvl_name);
                	  $(".c_tan_phone").text(data.telephone);
                	  $(".c_tan_email").text(data.email);
                	  $(".c_tan_idno").text(data.id_card_no);
                	  $(".c_tan_company").val(data.company_name);
                	  $(".c_tan_duty").val(data.duty);
                	  $(".c_tan_creat").text(data.createtime);
                	  tan_diapaly('.c');
                      }else{
							return false;
                          }
                      }else{
						alert('网络异常');
                          }
              },
              error: function () {
                  alert('网络繁忙');
              }
          });
	  }); 
  });
  $('body').on('click','input[name="member_id"]',function(){
	  
		var sw=$(this).attr('data-switch');
		if(sw=='false'){
			$("#subbtn2").addClass("begray");
			}else{
				$("#subbtn2").removeClass("begray")
				}
	  
	  })


  
  $('.c_modify').click(function(){
		id= $('.c_tan_id').html();
		member_ship_num= $('.c_tan_num').val();
		company= $('.c_tan_company').val();
		duty= $('.c_tan_duty').val();
	    remark= $('.c_tan_remark').val();
	    var  Url = "<?php echo base_url("index.php/membervip/verify/ajax_modify");?>";
		  var datas={id:id,remark:remark,duty:duty,company:company,member_ship_num:member_ship_num,audit:1,send:0};
		  $.ajax({
	          url:Url,
	          type:'GET',
	          data:datas,
	          dataType:'json',
	          timeout:6000,
	          success: function (result) {
	              if(result.message=='ok'){
	            	 alert('修改成功');
	            	 location.reload(true);
	                 }else if (result.message=='fail'){
						alert(result.data);
	                      }
	          },
	          error: function () {
	              alert('网络繁忙');
	          }
	      });
	    
	  })
  $('.b_modify').click(function(){
		id= $('.b_tan_id').html();
		member_ship_num= $('.b_tan_num').val();
		company= $('.b_tan_company').val();
		duty= $('.b_tan_duty').val();
	    remark= $('.b_tan_remark').val();
	    var  Url = "<?php echo base_url("index.php/membervip/verify/ajax_modify");?>";
		  var datas={id:id,remark:remark,duty:duty,company:company,member_ship_num:member_ship_num,audit:1,send:true};
		  $.ajax({
	          url:Url,
	          type:'GET',
	          data:datas,
	          dataType:'json',
	          timeout:6000,
	          success: function (result) {
	              if(result.message=='ok'){
	            	 alert('修改成功');
	            	 location.reload(true);
	                 }else if (result.message=='fail'){
						alert(result.data);
	                      }
	          },
	          error: function () {
	              alert('网络繁忙');
	          }
	      });
	    
	  })
  $('#a_pass').click(function(){
	  member_ship_num= $('.a_tan_num').val();
	  company= $('.a_tan_company').val();
	  duty= $('.a_tan_duty').val();
	  remark= $('.a_tan_remark').val();
	  id= $('.a_tan_id').html();
	  var postUrl = "<?php echo base_url("index.php/membervip/verify/member_audit_pass");?>";
	  var datas={id:id,remark:remark,duty:duty,company:company,member_ship_num:member_ship_num,audit:1};
	  $.ajax({
          url:postUrl,
          type:'GET',
          data:datas,
          dataType:'json',
          timeout:6000,
          success: function (result) {
              if(result.message=='ok'){
            	 alert('审核成功');
            	 location.reload(true);
                 }else{
                	 alert(result.data);
                      }
          },
          error: function () {
              alert('网络繁忙');
          }
      });
	  
  })
  $('#a_unpass').click(function(){
	  id=$('.a_tan_id').html();
	  type= $('input[name="reason"]:checked').val();
	  reason_remark='';
	  var huiyuanid = $(".a_tan_num").val();
	  var beizhu = $(".a_tan_remark").val();
	  if(type==''){
		  alert('请选择审核结果');
		  }
	  if(type==2){
		  reason_remark=$('input[name="reason_remark"]').val();
		  if(reason_remark.length<5){
 				alert('请至少输入5个字');
 				return false;
			  }
		  }
	  var  Url = "<?php echo base_url("index.php/membervip/verify/ajax_unpass");?>";
	  var datas={type:type,reason_remark:reason_remark,id:id,remark:beizhu,member_ship_num:huiyuanid};
	  $.ajax({
          url:Url,
          type:'GET',
          data:datas,
          dataType:'json',
          timeout:6000,
          success: function (result) {
              if(result.message=='ok'){
            	 alert('审核成功,模板消息将会发送到用户微信');
            	 location.reload(true);
                 }else if(result.message=='fail'){
					alert(result.data);
                      }else{
                    	  alert('网络繁忙');
                          }
          },
          error: function () {
              alert('网络繁忙');
          }
      });
	  
  })
  function det_btn(obj,colo,txt){
    $('.confirms').click(function(){
          check_to_tx(obj,colo,txt)
          $('.fixed_box').hide();
      });
      $('.cancel').click(function(){
          $('.fixed_box').hide();
      })
  }
  function check_to_tx(obj,colo,txt){
      obj.find('input').remove();
      obj.find('.to_examine').html(txt);
      obj.find('.to_examine').removeClass('color_F99E12');
      obj.find('.to_examine').addClass('color_ff9900');
      obj.find('label').css({backgroundImage: 'url()'});

  }
  $('.classification >div').click(function(){
    $(this).addClass('add_active').siblings().removeClass('add_active');
  })
  $('.news').click(function(){
      $('.j_toshow').animate({"right":"0px"},800);
  });
  $('.close_btn').click(function(){
      $('.j_toshow').animate({"right":"-330px"},800);
  });

  
  var tips=$('#tips');
  $('.btn_o').click(function(){
    //console.log( decodeURIComponent($(".form").serialize(),true));
    start=$('.t_time').find('input[name="start_t"]').val().replace(/-/g,'');
    end=$('.t_time').find('input[name="end_t"]').val().replace(/-/g,'');
    if(start!=''&&start!=undefined){
      if(isNaN(start)){
        tips.html('开始日期错误');
        setout(tips);
        return false;
      }
      if(end!=''&&end!=undefined){
        if(isNaN(end)||end<start){
          tips.html('结束日期错误或大于开始日期');
          setout(tips);
          return false;
        }
      }
    }
  })
})
<!--杰 2016/8/30-->
function setout(obj){
  setTimeout(function(){
    obj.fadeOut();  
  },2000) 
}
var orderid='';
function show_detail(obj){
  $('#status_detail').html('');
  $('#myModalLabel').html('单号：');
  var temp='';
  orderid='';
  $.get('<?php echo site_url('hotel/orders/order_status')?>',{
    oid:$(obj).attr('oid'),
    hotel:$(obj).attr('h')
  },function(data){
    orderid=data.order.orderid;
    if(data.after!=''){
      temp+='<select id="after_status">';
      $.each(data.after,function(i,n){
        if(i!=4)
          temp+='<option value="'+i+'">'+n+'</option>';   
      });
      temp+='</select>';
    }else{
      temp+=data.order.status_des;
      orderid='';
    }
    $('#status_detail').html(temp);
    $('#myModalLabel').append(data.order.orderid);
  },'json');
}
</script>

</body>
</html>
