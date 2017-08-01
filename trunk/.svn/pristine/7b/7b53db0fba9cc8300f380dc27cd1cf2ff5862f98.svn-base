<h1><?= date('Y年n月j日') ?></h1>
<hr/>
<h2>本月签到积分：<?= 0 ?></h2>
<hr/>
<?php if ($is_sign): ?>
    <span style="color: silver;">已签到</span>
<?php else: ?>
    <a href="javascript:;">签到</a>
<?php endif; ?>
<hr/>
<?php if ($serial_days > 1): ?>
    <h3>连续签到<?= $serial_days ?>天</h3>
<?php endif; ?>
<h3>累计签到22天</h3>
<hr/>
<h3>每连续签到7天可获得额外10积分奖励</h3>
<?php if ($serial_days > 1): ?>
    <h3>连续签到<?= $serial_days ?>天</h3>
    <h3 style="display: none;">恭喜你获得了额外10积分的连续签到奖励</h3>
<?php endif; ?>
<hr/>
<?php if ($is_sign): ?>
    <h2>今天签到排名第<?= $day_ranking ?>名</h2>
<?php endif; ?>
<hr/>
<a href="javascript:;">排行榜</a>
<a href="javascript:;">每月抽奖</a>
<a href="javascript:;">积分商城</a>