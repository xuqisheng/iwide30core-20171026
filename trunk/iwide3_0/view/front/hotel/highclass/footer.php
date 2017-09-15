<script type=text/javascript src="<?php echo refer_res('manifest.js','public/hotel/highclass') ?>"></script>
<script type=text/javascript src="<?php echo refer_res('vendor.js','public/hotel/highclass') ?>"></script>
<script type=text/javascript src="<?php echo refer_res('app.js','public/hotel/highclass') ?>"></script>
<script>
var jfkConfig = {
    interID: '<?php echo $inter_id;?>',
    token: '<?php echo json_encode($csrf_token_arr);?>'
};
</script>