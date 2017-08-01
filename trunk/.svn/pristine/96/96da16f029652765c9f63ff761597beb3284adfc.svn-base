<!-- DataTables -->

<style>
  .bor {border:1px solid;}
</style>
<h1>订单</h1>
<table class="bor">
  <tr class="bor">
    <td class="bor">id</td>
    <td class="bor">模块</td>
    <td class="bor">订单号</td>
    <td class="bor">金额</td>
    <td class="bor">分账状态</td>
    <td class="bor">退款</td>
    <td class="bor">查询</td>
  </tr>
  <?php if(!empty($res)){
          foreach($res as $k=>$v){
    ?>
     <tr class="bor">
     <td class="bor"><?php echo $v['id']?></td>
    <td class="bor"><?php echo $v['module']?></td>
    <td class="bor"><?php echo $v['order_no']?></td>
    <td class="bor"><?php echo $v['trans_amt']?></td>
    <td class="bor"><?php echo $transfer_status[$v['transfer_status']]?></td>
    <?php if(!in_array($v['transfer_status'],array(999))){?>
    <td class="bor"><a href='<?php echo site_url('iwidepay/orders/get_refund') . '?oid=' . $v['order_no']?>'>退款</a></td>
    <?php }else{?>
    <td class="bor"></td>
    <?php }?>
        <td class="bor"><a href='<?php echo site_url('iwidepay/orders/order_query') . '?oid=' . $v['order_no']?>'>查询订单状态</a></td>

    </tr>
  <?php }}?>
</table>