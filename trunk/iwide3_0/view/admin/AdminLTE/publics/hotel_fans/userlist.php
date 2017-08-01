<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/css/fsy0718.css">
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
    <div class="jfk-pages page__fans-user">

        <div class="fans-user__search-box jfk-search-component">
            <label class="search-box jfk-search-box">
                    <input type="text" id="keyword" class="jfk-search-box__input material-picword-input" placeholder="请输入用户昵称" />
<!--                    <i class="jfk-search-box__icon"></i>-->
                </label>
            <input type="button" value="查询" class="search-box-button" onclick="search()"/>
        </div>


        <div class="fans-user__content">
            <div class="fans-user__table-box">
                <table class="table-fans-user">
                    <?php if(!empty($fans)){ foreach($fans as $fan){ ?>
                    <tr>
                        <td>
<!--                            <label class="jfk-checkbox">-->
<!--                                <input type="checkbox" class="jfk-checkbox__input">-->
<!--                                <i class="jfk-checkbox__placeholder"></i>-->
<!--                            </label>-->
                        </td>
                        <td>
                            <img src="<?php if(!empty($fan['headimgurl']))echo  $fan['headimgurl'];?>" />

                        </td>
                        <td><span><?php if(!empty($fan['nickname']))echo  $fan['nickname'];?></span>
                            <span>来源<?php if($fan['source']<1)echo "自主关注";else echo "分销关注";?></span>
                            <span><?php if(!empty($fan['subscribe_time']))echo  $fan['subscribe_time'];?></span>
                        </td>
                    </tr>
                    <?php }}?>
                </table>
            </div>
            <div class="jfk-pagination jfk-pagination--left">
                <div class="jfk-pagination__tips">当前共筛选到<b><?php echo $per_nums;?></b>条/共<b><?php echo $count;?></b>条数据</div>
                <div class="jfk-pagination__pages">
                    <?php if(isset($page) && ($page-1>0)){ ?>
                        <a href="<?php echo site_url('publics/hotel_fans/userlist?key=').$key.'&p='.($page-1);?>" class="jfk-pagination__pages-trigger jfk-pagination__pages-button" data-type="prev">&lt;</a>
                        <a href="<?php echo site_url('publics/hotel_fans/userlist?key=').$key.'&p='.($page-1);?>"  class="jfk-pagination__pages-item jfk-pagination__pages-trigger "><?php echo $page-1;?></a>
                    <?php }?>
                        <a href="<?php echo site_url('publics/hotel_fans/userlist?key=').$key.'&p='.$page;?>"  class="jfk-pagination__pages-item jfk-pagination__pages-trigger "><?php if(isset($page))echo $page;else echo 1;?></a>
                    <?php if(isset($page) && (($page+1)<=$page_nums)){ ?>
                        <a href="<?php echo site_url('publics/hotel_fans/userlist?key=').$key.'&p='.($page+1);?>" class="jfk-pagination__pages-item jfk-pagination__pages-trigger "><?php echo $page+1;?></a>
                        <a href="<?php echo site_url('publics/hotel_fans/userlist?key=').$key.'&p='.($page+1);?>" class="jfk-pagination__pages-button jfk-pagination__pages-trigger " data-type="next">&gt;</a>
                    <?php }?>
                    <i class="jfk-pagination__pages-text">第</i>
                    <span class="jfk-pagination__pages-input">
                            <input type="text" id="go_page">
                        </span>
                    <i class="jfk-pagination__pages-text">页</i>
                    <a  class="jfk-pagination__pages-button jfk-pagination__pages-trigger "  onclick="go()">GO</a>
                </div>
            </div>
        </div>
    </div>
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
var keyword = '';
var page_nums = '<?php echo $page_nums;?>';
<?php
    if(!empty($key)){
?>
        keyword = "<?php echo $key;?>";
<?php    } ?>


function search(){
        var keyword = $("#keyword").val();
       location.href = "<?php echo site_url('publics/hotel_fans/userlist?key=');?>"+keyword;
   }


    function go(){
        var page = $("#go_page").val();
        if(page > page_nums){
            page = page_nums;
        }
        location.href = "<?php echo site_url('publics/hotel_fans/userlist?p=');?>"+page+'&key='+keyword;
    }



</script>
</body>
</html>
