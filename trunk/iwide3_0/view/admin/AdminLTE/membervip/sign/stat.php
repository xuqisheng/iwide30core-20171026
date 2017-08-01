<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<!-- <link rel="stylesheet" href="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/images/laydate12.css"> -->
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
        a {
            color: #92a0ae;
        }

        .bg_90d08f {
            background: #90d08f;
        }

        .bg_85bdd0 {
            background: #85bdd0;
        }

        .bg_3dc6d0 {
            background: #3dc6d0;
        }

        .bg_a984d0 {
            background: #a984d0;
        }

        .s_btn {
            width: 100px;
            text-align: center;
            padding: 4px 0px;
            border-radius: 5px;
            margin-right: 8px;
            background: #ff9900;
            color: #fff;
            border: 1px solid #ff9900;
        }

        .display_flex {
            display: flex;
            display: -webkit-flex;
            justify-content: top;
            align-items: center;
            -webkit-align-items: center;
        }

        .display_flex > div {
            -webkit-flex: 1;
            flex: 1;
            cursor: pointer;
            text-align: center;
            margin: 0 15px;
            border: 1px solid #d7e0f1;
            padding: 10px 0px;
            border-radius: 5px;
        }

        .moba {
            height: 30px;
            line-height: 30px;
            border: 1px solid #d7e0f1;
            text-indent: 3px;
        }

        .boxs > div {
            margin-bottom: 20px;
        }

        .statistics > div {
            display: inline-block;
        }

        .s_title {
            font-size: 16px;
            margin-right: 10px;
        }

        .btn_hre {
            border: 1px solid #d7e0f1;
            padding: 3px 10px;
            border-radius: 4px;
            color: #d7e0f1;
        }

        .s_con {
            color: #fff;
        }

        .s_con > div > p {
            margin: 3px;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="color:#92a0ae;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo isset($breadcrumb_array['action']) ? $breadcrumb_array['action'] : ''; ?>
                <small></small>
            </h1>
            <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <?php echo $this->session->show_put_msg(); ?>

            <!-- Horizontal Form -->
            <div class="box box-info">
                <!--<div class="box-header with-border">
                    <h3 class="box-title">签到统计</h3>
                </div>-->
                <div class="box-body">
                    <div class="boxs">
                        <div class="statistics">
                            <!--<div class="s_title">签到统计</div>-->
                            <div>
                                <a class="btn_hre"
                                   href="<?php echo base_url("index.php/membervip/sign/conf"); ?>">签到设置</a>
                                <?php if (!empty($confInfo) && $confInfo['is_active'] == 1): ?>
                                    <span>已于<?php echo $confInfo['active_at']; ?>启动活动签到</span>
                                <?php else: ?>
                                    <span>活动签到在关闭中</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="display_flex s_con">
                            <div class="bg_90d08f">
                                <p>今日签到<?php echo $statData['time_today']; ?>人次</p>

                                <p>昨日签到<?php echo $statData['time_yesterday']; ?>人次</p>
                            </div>
                            <div class="bg_85bdd0">
                                <p>本周累计签到<?php echo $statData['time_this_week']; ?>人次</p>

                                <p>上周累计签到<?php echo $statData['time_last_week']; ?>人次</p>
                            </div>
                            <div class="bg_3dc6d0">
                                <p>本月累计签到<?php echo $statData['time_this_month']; ?>人次</p>

                                <p>上月累计签到<?php echo $statData['time_last_month']; ?>人次</p>
                            </div>
                            <div class="bg_a984d0">
                                <p><?php echo $statData['this_month']; ?>月发放积分</p>

                                <p><?php echo $statData['bonus_this_month']; ?>分</p>
                            </div>
                        </div>
                        <div>
                            <span>签到活动情况导出</span>
                            <select id="year">
                                <?php for ($i = date('Y'); $i >= 2016; $i--): ?>
                                    <option value="<?php echo $i; ?>" <?php echo $i == date('Y') ? 'selected' : '' ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>年
                            <select id="month">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo $i == date('n') ? 'selected' : '' ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>月
                            <select id="sign_export_type">
                                <option value="1">签到活动统计</option>
                                <option value="2">签到清单</option>
                            </select>
                            <button class="s_btn" id="sign_export">导出</button>
                        </div>
                    </div>
                </div>
                <!-- /.box-footer -->
            </div>

    </div>
    <!-- Horizontal Form -->

    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(function(){
        // 导出
        $('#sign_export').on('click', function () {
            var type = $("#sign_export_type").val();
            var year = $('#year').val();
            var month = $('#month').val();
            location.href = '<?php echo base_url("index.php/membervip/sign/export"); ?>?type=' + type + '&year=' + year + '&month=' + month;
        });
    });

</script>

<?php
/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>
<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
?>
</div><!-- ./wrapper -->
<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>
</body>
</html>
