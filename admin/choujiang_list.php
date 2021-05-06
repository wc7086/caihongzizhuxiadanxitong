<?php
include("../includes/common.php");
$title="中奖记录";
include("head.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<div class="col-sm-12 col-lg-10 center-block" style="float: none;">
	<div class="block">
	<div class="block-title"><h3 class="panel-title">中奖记录</h3></div>
<div class="table-responsive">
<table class="table table-striped">
<thead><tr><th>站点ID</th><th>中奖商品ID</th><th>奖品名称</th><th>订单号</th><th>中奖时间</th></tr></thead>
<tbody>
<?php
$list=$DB->query("SELECT a.*,(select b.name from pre_gift as b where a.gid=b.id) as name FROM pre_giftlog as a WHERE status=1 ORDER BY id DESC");
while($cjlist=$list->fetch()){
?>
<tr>
<td><b><?=$cjlist['zid']?></b></td>
<td><b><?=$cjlist['tid']?></b></td>
<td><?=$cjlist['name']?></td>
<td><a href="./list.php?kw=<?=$cjlist['tradeno']?>&type=0" target="_blank"><?=$cjlist['tradeno']?></a></td>
<td><?=$cjlist['addtime']?></td>
</tr>
<?php }?>
</tbody>
</table>
</div>
	</div>
</div>
</div>