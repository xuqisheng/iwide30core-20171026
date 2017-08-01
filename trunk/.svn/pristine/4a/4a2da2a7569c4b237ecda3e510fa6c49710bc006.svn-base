<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<!-- 新版本后台 v.2.0.0 -->
<link type="text/css" href="<?php echo base_url(FD_PUBLIC) ?>/soma/css/tao.css" rel="stylesheet">
<style>
    .btn{background:rgb(221,221,221); border-left:1px solid #FFF}
    .btn:first-child{border-right:0}
    .table_banner_top{
        padding: 15px 35px;
        background-color: #f6f6f6;
    }
    .page_each_number{
        background-color: white;
        width: 85px;
        height: 28px;
        line-height: 28px;
    }
    .search{
        width: 200px;
        height: 28px;
        line-height: 28px;
    }
    .search_btn{
        width: 75px;
        height: 28px;
        line-height: 28px;
        text-align: center;
        background-color: #b69b69;
        color:white;
        cursor: pointer;
    }
    .add{
        width: 110px;
        height: 32px;
        line-height: 32px;
        text-align: center;
        color:#b69b69;
        background-color: #f6f6f6;
        border-radius: 5px;
        border:1px solid #ededed;
        cursor: pointer;
    }
    .table{
        border-collapse:collapse;border-spacing:0;
    }
    .table th:last-child{
        width: 150px;
    }
    .table th{
        color: #808080;
        border-bottom: 1px solid #ccd6e1;
        padding: 15px 0px;
    }
    .table td{
        padding: 15px 0px;
        color: #333;
    }
    .table tr:nth-child(2n+1) td{
        background-color: #eff2f7;
    }
    .table ib{
        color: #b69b69;
        border:1px solid #b69b69;
        border-radius: 5px;
        height: 28px;
        line-height: 28px;
        width: 75px;
        cursor: pointer;
    }
    .page_info{
        color:#bfbfbf;
    }
    .page_info>span{
        color: #808080;
    }
</style>
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
	<section class="content">
        <?php echo $this->session->show_put_msg(); ?>
        <?php if( $data ):?>
            <div class="land">
                <div class="table_banner_top" style="">
                    <ib>
                        <ib>每页显示</ib>
                        <ib ml-15>
                            <select class="page_each_number">
                                <option value="1">1</option>
                            </select>
                        </ib>
                    </ib>
                    <form style="display: inline-block;" class="the_form" action="<?php echo Soma_const_url::inst()->get_url('*/*/*'); ?>" method="post">
                        <ib ml-70>
                            <ib>关键词查找</ib>
                            <ib ml-15><input type="text" name="search" value="<?php echo $search;?>" search placeholder="输入名称／状态" class="search"></ib>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <ib ml-15><button type="submit" class="search_btn">查询</button></ib>
                        </ib>
                    </form>
                </div>
                <div mt-15 style="text-align: right;">
                    <a href="<?php echo Soma_const_url::inst()->get_url("*/*/add"); ?>"><ib class="add">+新增</ib></a>
                </div>
                <div mt-15>
                    <table class="w100 table" center>
                        <tr>
                            <th>编号</th>
                            <th>名称</th>
                            <th>有效期</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        <?php foreach( $data as $k=>$v ):?>
                            <tr>
                                <td><?php echo $v['id'];?></td>
                                <td><?php echo $v['name'];?></td>
                                <td><?php echo $v['start_time'];?>~<?php echo $v['end_time'];?></td>
                                <td><?php echo $status[$v['status']];?></td>
                                <td><a href="<?php echo Soma_const_url::inst()->get_url("*/*/edit?id={$v['id']}"); ?>"><ib>编辑</ib></a></td>
                            </tr>
                        <?php endforeach;?>
                    </table>
                </div>
                <flex between mt-15>
                    <ib class="page_info">当前共筛选到<span><?php echo $page_count;?></span>条／共<span><?php echo $total;?></span>条数据</ib>
                    <ib pagebtn>
<!--                        <ib pagebtn_gray><</ib>-->
<!--                        <ib pagebtn_gray ml-3>1</ib>-->
<!--                        <ib pagebtn_gray nowpage>2</ib>-->
<!--                        <ib pagebtn_gray>3</ib>-->
<!--                        <ib pagebtn_gray>4</ib>-->
<!--                        <ib pagebtn_gray>5</ib>-->
<!--                        <ib pagebtn_gray ml-3>></ib>-->
<!--                        <ib>第</ib>-->
<!--                        <ib pagebtn_gray><input value="2"></ib>-->
<!--                        <ib>页</ib>-->
<!--                        <ib pagebtn_gray>GO</ib>-->
                        <?php echo $pagination;?>
                    </ib>
                </flex>
            </div>
        <?php else:?>
            <div class="land">
                <div class="table_banner_top" style="">
                    <ib>
                        <ib>每页显示</ib>
                        <ib ml-15>
                            <select class="page_each_number">
                                <option value="1">1</option>
                            </select>
                        </ib>
                    </ib>
                    <form class="the_form" action="<?php echo Soma_const_url::inst()->get_url('*/*/*'); ?>" method="post">
                        <ib ml-70>
                            <ib>关键词查找</ib>
                            <ib ml-15><input type="text" name="search" value="<?php echo $search;?>" search placeholder="输入名称／状态" class="search"></ib>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <ib ml-15><button type="submit" class="search_btn">查询</button></ib>
                        </ib>
                    </form>
                </div>
                <div mt-15 style="text-align: right;">
                    <a href="<?php echo Soma_const_url::inst()->get_url("*/*/add"); ?>"><ib class="add">+新增</ib></a>
                </div>
                <div mt-15>
                    <table class="w100 center table">
                        <tr>
                            <th>编号</th>
                            <th>名称</th>
                            <th>有效期</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                    </table>
                </div>
                <div mt-65 center>
                    <img style="width: 280px;" src="<?php echo base_url(FD_PUBLIC) ?>/soma/img/none.png">
                </div>
                <div mt-40 pb-40 center>暂无价格数据</div>
            </div>
        <?php endif;?>
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
</body>
</html>
