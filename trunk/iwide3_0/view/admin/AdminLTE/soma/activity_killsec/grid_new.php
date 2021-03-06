<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<!--<link rel="stylesheet" href="--><?php //echo base_url(FD_PUBLIC) ?><!--/soma/killsec/css/AdminLTE.css">-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/soma/killsec/css/jedate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/soma/killsec/css/style.css">
<!--<script src="--><?php //echo base_url(FD_PUBLIC) ?><!--/soma/killsec/js/jQuery-2.1.4.min.js"></script>-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/soma/killsec/js/jquery.jedate.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/soma/killsec/js/index.js"></script>
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
            <h1><?php echo $breadcrumb_array['action']; ?>
                <small></small>
            </h1>
            <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <!--秒杀后台 首页-->
            <div class="kill-index">

                <!-- tab切换 -->
                <div class="tab">
                    <a href="<?php echo $statusUrl;?>" class="c333 fl f28 <?php if(!$status):?>active<?php endif;?>">所有秒杀</a>
                    <a href="<?php echo $statusUrl,$instanceModel::STATUS_PREVIEW;?>" class="c333 fl f28 <?php if($status==$instanceModel::STATUS_PREVIEW):?>active<?php endif;?>">未开始</a>
                    <a href="<?php echo $statusUrl,$instanceModel::STATUS_GOING;?>" class="c333 fl f28 <?php if($status==$instanceModel::STATUS_GOING):?>active<?php endif;?>">进行中</a>
                    <a href="<?php echo $statusUrl,$instanceModel::STATUS_FINISH;?>" class="c333 fl f28 <?php if($status==$instanceModel::STATUS_FINISH):?>active<?php endif;?>">已结束</a>
                    <a href="<?php echo Soma_const_url::inst()->get_url("*/*/add"); ?>" class="add fr">
                        <span>＋新增秒杀活动</span>
                    </a>
                </div>
                <!-- tab 切换 -->

                <div class="kill-index-container">

                    <!-- 搜索 -->
                    <form class="the_form" action="<?php echo $statusUrl,$status; ?>" method="post" id="killIndexForm">
                        <div class="search">
                            <div class="fl page-title c333 f24">每页显示</div>
                            <div class="select-wrap fl">
                                <select class="select fl" name="per_page_num" id="switchPageNumber">
                                    <option value="10" <?php if($perPageNum==10) echo 'selected';?>>10</option>
                                    <option value="20" <?php if($perPageNum==20) echo 'selected';?>>20</option>
                                </select>
                            </div>
                            <div class="fl page-title c333 f24">关键词查找</div>

                            <div class="fl key">
                                <div class="input fl">
                                    <input type="text" name="search" value="<?php echo $search;?>" placeholder="活动id/活动名／商品名称" class="f24">
                                </div>
                                <a href="javascript:void(0)" class="fl"></a>
                            </div>

                            <input type="hidden" id="token" name="<?php echo $tokenArr['name'];?>" value="<?php echo $tokenArr['value'];?>">
                            <input type="submit" class="go-search f28" value="查询">
                        </div>
                    </form>
                    <!-- 搜索 -->



                    <!-- 表格 -->
                    <div class="table">
                        <table cellspacing="0">
                            <tr>
                                <th>活动id</th>
                                <th>活动名</th>
                                <th>商品名称</th>
                                <th>秒杀价</th>
                                <th>库存</th>
                                <th>启动秒杀时间</th>
                                <th>启动关闭时间</th>
                                <th>操作</th>
                            </tr>

                            <?php if( $data ):?>
                                <?php foreach( $data as $k=>$v ):?>
                                    <tr>
                                        <td><?php echo $v['act_id'];?></td>
                                        <td><?php echo $v['act_name'];?></td>
                                        <td><?php echo $v['product_name'];?></td>
                                        <td>￥<?php echo $v['killsec_price'];?></td>
                                        <td><?php echo $v['killsec_count'];?></td>
                                        <td><?php echo $v['killsec_time'];?></td>
                                        <td><?php echo $v['end_time'];?></td>
                                        <td class="operation">
                                            <a href="<?php echo $editUrl,$v['act_id'];?>" class="btn edit-list">编辑</a>
                                            <?php
                                                $statusArr = array();
                                                if( isset( $v['notice_status'] ) && !empty( $v['notice_status'] ) )
                                                {
                                                    $statusArr[] = $v['notice_status'];
                                                }
                                                if( isset( $status ) && !empty( $status ) )
                                                {
                                                    $statusArr[] = $status;
                                                }
                                                if( $v['status'] == Soma_base::STATUS_TRUE ):
                                            ?>
                                                <a href="javascript:void(0)" class="btn edit-invalid" itemId="1"
                                                    actId="<?php echo $v['act_id'];//活动ID，请求的时候需要传到后台?>"
                                                    <?php if( in_array( $instanceModel::STATUS_GOING, $statusArr ) ): ?>
                                                        status="run"
                                                    <?php else: ?>
                                                        status="start"
                                                    <?php endif;?>
                                                >使失效</a>

                                                <?php if( in_array( $instanceModel::STATUS_GOING, $statusArr ) ):?>
                                                    <a href="javascript:void(0)" class="btn edit-store" itemId="1"
                                                        actId="<?php echo $v['act_id'];//活动ID，请求的时候需要传到后台?>"
                                                        count="<?php echo $v['killsec_count'];//现有库存(限制库存x份)或者名额(限制名额x人)?>"
                                                        permax="<?php echo $v['killsec_permax'];//现有限购数量?>"
                                                        <?php if( $v['type'] == Soma_base::STATUS_TRUE ): ?>
                                                            status="people"
                                                        <?php elseif( $v['type'] == Soma_base::STATUS_FALSE ): ?>
                                                            status="store"
                                                        <?php endif;?>
                                                    >增加库存</a>
                                                <?php endif;?>
                                                <?php $temp = strtotime($v['killsec_time']) - time(); if( ( $temp <= 3000 && $temp > 0) || in_array( $instanceModel::STATUS_GOING, $statusArr ) ) {?>
                                                    <a href="javascript:void(0)" class="btn check-redis" actId="<?php echo $v['act_id'];//活动ID，请求的时候需要传到后台?>">活动检查</a>
                                                <?php } ?>
                                            <?php else:?>
                                                <a href="javascript:void(0)" class="btn disabled">已失效</a>
                                            <?php endif;?>

<!--                                            <a href="javascript:void(0)" class="btn placeholder">占位</a>-->
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                        </table>
                    </div>
                    <!-- 表格 -->

                    <div class="pagination">
                        <div class="fl page-number f24">
                            当前共筛选到 <span><?php echo $page_count;?></span> 条／共 <span><?php echo $total;?></span> 条数据
                        </div>
                        <div class="fr page">
                            <?php if($pageTotal>1):?>
                                <?php if($page>=2):?>
                                    <a href="<?php echo $pageUrl,$page-1;?>" class="last fl"></a>
                                <?php endif;?>
                                <div class="fl current">
                                    <?php if( $page - 2 > 0 ):?>
                                        <a href="<?php echo $pageUrl,$page-2;?>" class="fl"><?php echo $page-2;//当前页上二页?></a>
                                    <?php endif;?>
                                    <?php if( $page - 1 > 0 ):?>
                                        <a href="<?php echo $pageUrl,$page-1;?>" class="fl"><?php echo $page-1;//当前页上一页?></a>
                                    <?php endif;?>
                                    <a href="<?php echo $pageUrl,$page;?>" class="fl active "><?php echo $page;//当前页?></a>
                                    <?php if( $pageTotal - 1 >= $page ):?>
                                        <a href="<?php echo $pageUrl,$page+1;?>" class="fl"><?php echo $page+1;//当前页下一页?></a>
                                    <?php endif;?>
                                    <?php if( $pageTotal - 2 >= $page ):?>
                                        <a href="<?php echo $pageUrl,$page+2;?>" class="fl"><?php echo $page+2;//当前页下二页?></a>
                                    <?php endif;?>
                                </div>
                                <?php if($pageTotal-1>=$page):?>
                                    <a href="<?php echo $pageUrl,$page+1;?>" class="next fl"></a>
                                <?php endif;?>
                                <div class="tips fl">第</div>
                                <input class="go-to-input fl" value="<?php echo $page;?>" id="page">
                                <div class="tips fl ">页</div>
                                <a href="<?php echo $pageUrl;?>" class="go fl" id="go">GO</a>
                            <?php endif;?>
                        </div>
                    </div>
                </div>


                <div class="kill-layer" style="display: none" id="invalidLayer">
                    <div class="layer-content">
                        <div class="close"></div>
                        <p class="title f32">提 示</p>
                        <div class="tips-content f28">
                            确定让这个活动失效？
                        </div>
                        <a href="javascript:void(0)" class="btn f32">确 定</a>
<!--                        <a href="--><?php //echo $pageUrl,$page;?><!--" class="btn f32">确 定</a>-->
                    </div>
                </div>

                <div class="kill-layer" style="display: none" id="runInvalidLayer">
                    <div class="layer-content">
                        <div class="close"></div>
                        <p class="title f32">提 示</p>
                        <div class="tips-content f28">
                            <p class="f28">活动正在进行中，是否使其失效，</p>
                            <p class="f28">失效后将不能恢复！</p>
                        </div>
                        <a href="javascript:void(0)" class="btn f32">确 定</a>
                    </div>
                </div>


                 <div class="kill-layer" style="display: none" id="errorLayer">
                          <div class="layer-content">
                              <div class="close"></div>
                              <p class="title f32">提 示</p>
                              <div class="tips-content f28" id="errorLayerMessage">
                                  操作失败，请稍后重试
                              </div>
                              <a href="javascript:void(0)" class="btn f32">确 定</a>
                          </div>
                  </div>


                <div class="kill-layer" style="display: none" id="numberLayer">
                    <div class="layer-content">
                        <div class="close"></div>
                        <p class="title f32">提 示</p>

                        <ul class="store">
                            <li class="clearfix">
                                <div class="fl title f24">秒杀方式</div>
                                <div class="fl f24 show-tips">限制名额50人，每人限购2份</div>
                            </li>

                            <li class="clearfix">
                                <div class="fl title f24">增加库存</div>
                                <div class="fl f24 clearfix">
                                    <input type="text" name="add_stock" class="fl number" placeholder="数量">
                                    <div class="fl unit">人</div>
                                </div>
                            </li>

                            <li class="clearfix">
                                <div class="fl title f24">增加后总库存为</div>
                                <div class="fl f24 number-tips">150份，每人限购2份</div>
                            </li>

                        </ul>

                        <a href="javascript:void(0)" class="btn f32">确 定</a>
                    </div>
                </div>






                <div class="kill-layer" style="display: none" id="storeLayer">
                    <div class="layer-content">
                        <div class="close"></div>
                        <p class="title f32">提 示</p>

                        <ul class="store">
                            <li class="clearfix">
                                <div class="fl title f24">秒杀方式</div>
                                <div class="fl f24 show-tips">限制库存100份，每人限购2份</div>
                            </li>

                            <li class="clearfix">
                                <div class="fl title f24">增加库存</div>
                                <div class="fl f24 clearfix">
                                    <input type="text" name="add_stock" class="fl number" placeholder="数量">
                                    <div class="fl unit">份</div>
                                </div>
                            </li>

                            <li class="clearfix">
                                <div class="fl title f24">增加后总库存为</div>
                                <div class="fl f24 number-tips">150份，每人限购2份</div>
                            </li>

                        </ul>

                        <a href="javascript:void(0)" class="btn f32">确 定</a>
                    </div>
                </div>

                <div class="kill-layer" style="display: none" id="checkLayer">
                    <div class="layer-content">
                        <div class="close"></div>
                        <p class="title f32">检 测</p>

                        <ul class="store">
                            <li class="clearfix">
                                <div class="fl f24 show-tips">Loading...</div>
                            </li>

                        </ul>

                        <a href="javascript:void(0)" class="btn f32 check-close">关 闭</a>
                    </div>
                </div>

            </div>
            <!--秒杀后台 首页-->
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
