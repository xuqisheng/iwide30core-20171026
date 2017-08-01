<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
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



  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        会员配置
        <small></small>
      </h1>
      <ol class="breadcrumb">
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">编辑规则</h3>
            </div>
          </div>
        </div>       
        <div class="col-sm-12">
        	<div class="form-group" >
                <label>公众号</label>
                <select id='hotel' name='hotel' class="form-control input-sm">
                	<option value="">-- 选择公众号 --</option>
                	<?php foreach ($pubs as $key=>$val):?><option value="<?php echo $key?>"><?php echo $val;?>(<?php echo $key;?>)</option><?php endforeach;?>
                </select>
            </div>
            <div class="form-group">
				<div >
					<input type="text" name="qhs" id="qhs" placeholder="快捷查询">
					<input type="button" onclick='quick_search()' value='查询' />
					<input type="button" onclick='go_hotel("next")' value='下一个' />
					<input type="button" onclick='go_hotel("prev")' value='上一个' />
					 <span id='search_tip' style='color:red'></span>
				</div>
             </div><br />
         </div>
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">使用新会员模块</h3>
            </div>
            <form id='form1'>
            <div class="box-body">
                <div class="form-group col-xs-6">
                  <label>用不用啊？(使用新模块后不能再转回旧模块)</label>
                  <div class="radio part_show_radio">
                  <label><input type="radio" name="new_vip" value="1" >用</label>
                  <label><input type="radio" name="new_vip" value="1" >用</label>
                  <label><input type="radio" name="new_vip" value="1" >用</label>
                  </div>
                </div>
                <div class="col-xs-12 ">
		         <button type="button" onclick='snew()' class="btn btn-primary " style='margin-left: 40%'>保存</button>  
		         <label id='tips' style='color:red;'></label>
		       </div>
            </div>
            </form>
          </div>
        </div>      
        <div class="col-xs-12" >
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">已配置公众号</h3>
            </div>
            <div class="box-body">
                <div class="form-group col-xs-6">
                <p>2016-07-18 10:00后创建的公众号已默认使用新版会员，不需再配置</p>
                <?php if (!empty($vps)){foreach ($vps as $k=>$v){?>
                <p><?php echo $v.'('.$k.')';?></p>
                <?php }}?>
                </div>
            </div>
          </div>
        </div>      
        
      </div>
      <!-- /.row -->
    </section>
    
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
var search_index=0;
var hid='';
function quick_search() {
	var hk=$('#qhs').val();
	if(hk){
		$('#search_tip').html('');
		options=$('#hotel option');
		search_index=0;
		$.each(options,function(i,n){
			$(n).css('color','#555');
			$(n).removeAttr('be_search');
			if(n.innerHTML.indexOf(hk)>-1){
				search_index++;
				$(n).css('color','red');
				$(n).attr('be_search',search_index);
				if(search_index==1){
					n.selected=true;
					hid=n.value;
					//h_jump(n.value);
				}
			}
		});
		if(search_index==0){
			$('#search_tip').html('无结果');
		}
	}
}; 
function go_hotel(direction){
	selected_option=$('#hotel').find('option:selected');
	selected_option=selected_option[0];
	now_index=$(selected_option).attr('be_search');
	if(now_index){
		search_index=now_index;
	}
	if(search_index){
		if(direction=='next'){
			search_index++;
		}else{
			search_index--;
		}
	}
	if(search_index){
		option=$('#hotel>option[be_search="'+search_index+'"]');
		if(option[0]!=undefined){
			option=option[0];
			option.selected=true;
			hid=option.value;
		}
	}
}

function snew(){
	inter_id=$('#hotel').val();
	if(!inter_id){
		alert('请选择公众号');
		return;
	}
	$.get('<?php echo site_url('hotel/memberv/to_new');?>'+'?inter_id='+inter_id,$('#form1').serialize(),function($data){
		alert($data.message);	
	},'json');
}
function tran(){
	inter_id=$('#hotel').val();
	if(!inter_id){
		alert('请选择公众号');
		return;
	}
	$.get('<?php echo site_url('hotel/memberv/to_tran');?>'+'?inter_id='+inter_id,'',function($data){
		alert($data.message);	
	},'json');
}

</script>
</body>
</html>
