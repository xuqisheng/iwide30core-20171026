<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
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
        <section class="content-header">
          <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">

<?php echo $this->session->show_put_msg(); ?>
<?php $pk= $model->table_primary_key(); ?>
<!-- Horizontal Form -->
<div class="box box-info"><!--
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div>
	 /.box-header -->

<?php 
echo form_open( Soma_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data',), array($pk=>$model->m_get($pk) ) ); 
?>

    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li id="tab_header1" class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
            <li id="tab_header2"><a href="#tab2" data-toggle="tab"><i class="fa fa-link"></i> 绩效计算商品</a></li> 
        </ul>



<!-- form start -->
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
				<div class="box-body">
					<?php foreach ($fields_config as $k=>$v): ?>
						<?php if($k == 'group_mode'): ?>
                            <div id="el_group_mode_form_group" class="form-group" <?php if($model->m_get('reward_source') == Reward_rule_model::REWARD_SOURCE_FANS_SALER): ?> style="display:none" <?php endif; ?>>
                                <label for="el_group_mode" class="col-sm-2 control-label">绩效范围</label>
                                <div class="col-sm-8">
                                    <select id="el_group_mode" class="form-control selectpicker show-tick" data-live-search="true" name="group_mode" required >
                                        <?php foreach($model->get_group_mode_label() as $gk => $gv): ?>
                                            <option value="<?php echo $gk; ?>" <?php if($model->m_get('group_mode') == $gk): ?> selected <?php endif; ?>><?php echo $gv; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div id="el_group_compose_form_group" class="form-group" <?php if($model->m_get('reward_source') == Reward_rule_model::REWARD_SOURCE_FANS_SALER): ?> style="display:none" <?php endif; ?>>
                                <label for="el_group_mode" class="col-sm-2 control-label">选择适用分组<span style="color: red;"> * </span></label>
                                <div class="col-sm-8">
                                    <?php foreach($group_hash as $ghk => $ghv): ?>
                                        <label class='col-sm-3'><input type='checkbox' name='group_compose[]' value='<?php echo $ghk; ?>' <?php if(in_array($ghk, $group_compose)): ?> checked="checked" <?php endif; ?>><?php echo $ghv; ?></label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php continue; ?>
                        <?php endif ?>
                        <?php 
                            if($check_data==FALSE)
                            {
                                echo EA_block_admin::inst()->render_from_element($k, $v, $model);
                            }
                            else
                            {
		                        echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE);
                            }
		                ?>
					<?php endforeach; ?>
					<div class="form-group  has-feedback">
                    	<label for="el_name" class="col-sm-2 control-label">绩效计算商品</label>
                    	<div class="col-sm-8">
                    		<label class="col-sm-4" onclick="$('#tab_header2').hide();"><input type="radio" name="p_type" id="el_p_type" value="all" <?php 
                    		    echo ($model->m_get('product_ids'))? '': '  checked="checked" ';
                    		?> > <abbr title="下单时对所有的商品计算绩效">对全部商品</abbr> </label>
                    		<label class="col-sm-4" onclick="$('#tab_header2').show();"><input type="radio" name="p_type" id="el_p_type" value="ids" <?php 
                    		    echo ($model->m_get('product_ids'))? '  checked="checked" ': '';
                    		?> > <abbr title="下单时仅对所选择的商品计算绩效">选择部分商品 </abbr> </label>
                    	</div>
                    </div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer ">
				    <div class="col-sm-1 col-sm-offset-3">
				        <input type="hidden" name=product_ids id="el_product_ids" value="<?php echo $model->m_get('product_ids'); ?>">
				        <button type="submit" class="btn btn-info pull-right">保存</button>
					</div>
					<span style="color:red;line-height:35px;height:35px;" id="save_notice"></span>
				</div>
				<!-- /.box-footer -->
			</div>

		<div class="tab-pane" id="tab2">
			<div class="box-body">
<link href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<?php 
$current_pids= explode(',', $model->m_get('product_ids'));
$table_selected= '';
$table_header= '<tr role="row"> <th>#ID</th><th>商品名称</th><th>目前价格</th> </tr>'; 
$grid_data= array();
foreach($products as $k=>$v){
    $grid_data[]= array( $v['product_id'], $v['name'], show_price_prefix($v['price_package'], '￥'), 'DT_RowId'=>$v['product_id']);
    if(in_array($v['product_id'], $current_pids)) $table_selected.= "<tr><td>{$v['product_id']}</td><td>{$v['name']}</td><td>{$v['price_package']}</td></tr>";
}
?>
				<div style="height:450px;">
					<div class="col-sm-12">
					    <div class="alert alert-success">
					    <?php if( $model->m_get('product_ids') ): ?>
					        <table class="table ">
						        <thead><tr role="row center"> <th colspan="3">目前仅针对以下商品计算绩效</th> </tr></thead>
						        <tbody>
						        <?php echo $table_header; ?>
						        <?php echo $table_selected; ?>
						        </tbody>
					        </table>
					    <?php else: ?>
					        <label>目前针对所有商品计算绩效</label>
					    <?php endif; ?>
					    </div>
					</div>
					<div class="col-sm-12">
						<table id="data-grid" class="table table-bordered table-striped table-condensed">
						    <thead><?php echo $table_header; ?></thead>
						</table>
					</div>
				</div>

                <div class="box-footer ">
                    <div class="col-sm-4 col-sm-offset-4">
                        <button type="submit" class="btn btn-info pull-right">保存</button>
                    </div>
                </div>
		</div><!-- /#tab2-->


<?php echo form_close() ?>
	</div>
<!-- /.box -->

</section><!-- /.content -->
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
<script src="http://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script>
function reflesh_title(){
	<?php $rt= $model->get_reward_source(); ?>
	var rule_type= '';
	var note= '';
	//var rule_type= '<?php echo date('Y-m-d '); ?>_';
	// rule_type+= ($('#el_reward_source').val()=='<?php echo $model::REWARD_SOURCE_SALER ?>')? '<?php 
	//        echo $rt[$model::REWARD_SOURCE_SALER]; ?>_': '<?php echo $rt[$model::REWARD_SOURCE_FIXED]; ?>_';
	 rule_type+= ($('#el_reward_source').val()=='<?php echo $model::REWARD_SOURCE_SALER ?>')? '<?php 
	        echo $rt[$model::REWARD_SOURCE_SALER]; ?>_': ($('#el_reward_source').val()=='<?php echo $model::REWARD_SOURCE_FANS_SALER ?>')? '<?php echo $rt[$model::REWARD_SOURCE_FANS_SALER]; ?>_' : '<?php echo $rt[$model::REWARD_SOURCE_FIXED]; ?>_';
	rule_type+= ($('#el_rule_type').val()=='<?php echo $model::SETTLE_DEFAULT ?>')? '立即购买_': 
		($('#el_rule_type').val()=='<?php echo $model::SETTLE_KILLSEC ?>')? '秒杀购买_': '拼团购买_';
	rule_type+= ($('#el_reward_type').val()=='<?php echo $model::REWARD_TYPE_FIXED ?>')? '固定奖励_': '按百分比_';
	if($('#el_reward_type').val()=='<?php echo $model::REWARD_TYPE_FIXED ?>'){
		rule_type+= $('#el_reward_rate').val()+ '元';
		
	} else {
		var percent= parseFloat($('#el_reward_rate').val());
	    if( percent>=1 || percent<0.0001){
	    	note= '奖励比例不能大于100%，小于0.01%，请重新调整！';
	    	alert(note);
	    	$('#el_reward_rate').val(0.0001);
	    	$('#el_name').val('');
	        
	    } else {
	    	if( percent>=0.5 ) {
		    	note= '原则上奖励比例不要超过50%！请注意设置';
		    	alert(note);
	    	}
	    }
    	rule_type+= (percent *100).toFixed(2) + '%';
	}
	$('#save_notice').html(note);
	$('#el_name').val(rule_type);
}

function show_group_mode_input()
{
    if($('#el_reward_source').val() == <?php echo Reward_rule_model::REWARD_SOURCE_FANS_SALER; ?>)
    {
        // 泛分销不显示绩效范围
        $('#el_group_mode_form_group').hide();
        $('#el_group_mode_form_group').attr("disabled","disabled");
    }
    else
    {
        $('#el_group_mode_form_group').show();
        $('#el_group_mode_form_group').removeAttr("disabled");
    }
    show_group_compose_input();
}

function show_group_compose_input()
{
    if($('#el_reward_source').val() == <?php echo Reward_rule_model::REWARD_SOURCE_FANS_SALER; ?>)
    {
        // 泛分销不显示分组信息
        $('#el_group_compose_form_group').hide();
        $('#el_group_compose_form_group').attr("disabled","disabled");
    }
    else if($('#el_group_mode').val() == <?php echo Reward_rule_model::GROUP_MODE_SPEC; ?>)
    {
        $('#el_group_compose_form_group').show();
        $('#el_group_compose_form_group').removeAttr("disabled");
    }
    else
    {
        $('#el_group_compose_form_group').hide();
        $('#el_group_compose_form_group').attr("disabled","disabled");
    }
    check_group_compose_required();
}

function check_group_compose_required()
{
    if($('#el_group_mode').val() == undefined
        || $('#el_group_mode').val() == <?php echo Reward_rule_model::GROUP_MODE_ALL; ?>
        || $('#el_reward_source').val() == <?php echo Reward_rule_model::REWARD_SOURCE_FANS_SALER; ?>
        || $("input[name='group_compose[]']").is(':checked'))
    {
        // 泛分销、全员绩效、已选中取消分组信息required属性
        $("input[name='group_compose[]']").removeAttr('required');
    }
    else
    {
        $("input[name='group_compose[]']").attr('required', 'required');
    }
}

$('#el_reward_rate').change(function(){ reflesh_title(); });
$('#el_reward_source').change(function(){ reflesh_title(); show_group_mode_input(); });
$('#el_reward_type').change(function(){ reflesh_title(); });
$('#el_rule_type').change(function(){ reflesh_title(); });
$('#el_group_mode').change(function(){ show_group_compose_input(); });
$("input[name='group_compose[]']").change(function(){ check_group_compose_required(); });

<?php $tr_slt= $model->m_get('product_ids'); ?>; //selected out container.
/** gridjs start **/
<?php 
$click_event = <<<EOF
    var selected_ = selected.join();
    var id = parseInt(this.id);
    var index = $.inArray(id, selected);
    if ( index===-1 ){
        $(this).addClass('bg-gray');
        selected.push( id );
    } else {
        $(this).removeClass('bg-gray');
        selected.splice( index, 1 );
    }
    $('#el_product_ids').val(selected);
EOF
;
?>
var dataSet=<?php echo json_encode($grid_data); ?>;
var grid_sort= [[ 1, "asc" ]];
$(document).ready(function() {
	<?php require_once VIEWPATH. $tpl .DS .'soma'. DS. 'gridjs_lite.php'; ?>
    show_group_mode_input();
    show_group_compose_input();
    check_group_compose_required();
});
/** gridjs end **/

<?php if( !$model->m_get('product_ids') ): ?>
    $('#tab_header2').hide();
<?php endif; ?>
</script>
</body>
</html>
