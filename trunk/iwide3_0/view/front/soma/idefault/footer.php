        <script>
            var jfkConfig = {
                wxConfig: <?php echo json_encode($wx_config); ?>,
                wxApiList: [<?php echo $js_api_list; ?>],
                wxMenuHide: [<?php echo $js_menu_hide; ?>],
                wxShare: <?php echo json_encode($js_share_config); ?>,
                interID: '<?php echo $inter_id;?>',
                token: <?php echo json_encode($token)?>
            }
            wx.config({
                debug: false,
                appId: '<?php echo $wx_config["appId"]?>',
                timestamp: <?php echo $wx_config["timestamp"]?>,
                nonceStr: '<?php echo $wx_config["nonceStr"]?>',
                signature: '<?php echo $wx_config["signature"]?>',
                jsApiList: [<?php echo $js_api_list; ?>,'getLocation','openLocation']
            });
            wx.ready(function() {

                <?php if( $js_menu_hide ): ?>wx.hideMenuItems({menuList: [<?php echo $js_menu_hide; ?>]});<?php endif; ?>
                <?php if( $js_menu_show ): ?>wx.showMenuItems({menuList: [<?php echo $js_menu_show; ?>]});<?php endif; ?>

                <?php if( $js_share_config ): ?>
                wx.onMenuShareTimeline({
                    title: '<?php echo $js_share_config["title"]?>',
                    link: '<?php echo $js_share_config["link"]?>',
                    imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
                    success: function () {
                        <?php if (isset($js_share_success_function) && $js_share_success_function) {
                            echo $js_share_success_function;
                        }?>
                    },
                    cancel: function () {
                        <?php if (isset($js_share_cancel_function) && $js_share_cancel_function) {
                            echo $js_share_cancel_function;
                        }?>
                    }
                });
                wx.onMenuShareAppMessage({
                    title: '<?php echo $js_share_config["title"]?>',
                    desc: '<?php echo $js_share_config["desc"]?>',
                    link: '<?php echo $js_share_config["link"]?>',
                    imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
                    //type: '', //music|video|link(default)
                    //dataUrl: '', //use in music|video
                    success: function () {
                        <?php if (isset($js_share_app_success_function) && $js_share_app_success_function) {
                            echo $js_share_app_success_function;
                        }?>
                    },
                    cancel: function () {
                        <?php if (isset($js_share_app_cancel_function) && $js_share_app_cancel_function) {
                            echo $js_share_app_cancel_function;
                        }?>
                    }
                });
                <?php endif; ?>
            })
        </script>
        <script type=text/javascript src="<?php echo refer_res('manifest.js', $path) ?>"></script>
        <script type=text/javascript src="<?php echo refer_res('vendor.js', $path) ?>"></script>
        <script type=text/javascript src="<?php echo refer_res('app.js', $path) ?>"></script>
        <script>
            /*下列字符不能删除，用作替换之用*/
            //[<sign_update_code>]
            <?php echo isset($statistics_js) ? $statistics_js :'' ;?>
        </script>
    </body>
</html>