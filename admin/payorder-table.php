<?php
/**
 * 支付记录
**/
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
adminpermission('shop', 1);

$typename = ['alipay'=>'支付宝','wxpay'=>'微信支付','qqpay'=>'QQ钱包','tenpay'=>'财付通','bank'=>'银联支付','jdpay'=>'京东支付'];


$sqls="";
$links='';
if(!empty($_GET['type']) && $_GET['type']!='all'){
	$sqls.=" AND type='".$_GET['type']."'";
	$links.='&type='.$_GET['type'];
}
if(isset($_GET['dstatus']) && $_GET['dstatus']>0) {
	$dstatus = intval($_GET['dstatus'])-1;
	$sqls.=" AND status={$dstatus}";
	$links.='&dstatus='.$_GET['dstatus'];
}
if(isset($_GET['column']) && isset($_GET['kw']) && !empty($_GET['kw'])){
	$column = trim(daddslashes($_GET['column']));
	$kw = trim(daddslashes($_GET['kw']));
	if($column == 'input'){
		$sql=" `{$column}` LIKE '%{$kw}%'";
	}else{
		$sql=" `{$column}`='{$kw}'";
	}
	$sql.=$sqls;
	$link='&type='.$type.'&kw='.$kw.$links;
	$numrows=$DB->getColumn("SELECT count(*) FROM pre_pay WHERE channel IS NOT NULL AND{$sql}");
	$con='包含 <b>'.$kw.'</b> 的共有 <b>'.$numrows.'</b> 条支付记录';
}else{
	if(!empty($_GET['type']) && $_GET['type']!='all'){
		$sql=" type='".$_GET['type']."'";
		$link='&type='.$_GET['type'];
	}else{
		$sql=" 1";
	}
	$sql.=$sqls;
	$link=$links;
	$numrows=$DB->getColumn("SELECT count(*) FROM pre_pay WHERE channel IS NOT NULL AND{$sql}");
	$con='系统共有 <b>'.$numrows.'</b> 条支付记录<span class="text-muted">(未支付记录自动在24小时后删除)</span>';
}
?>
	  <div class="table-responsive">
        <table class="table table-striped table-bordered table-vcenter orderList">
          <thead><tr><th>支付订单号<br/>支付接口订单号</th><th>支付接口</th><th>订单名称<br/>订单金额</th><th>下单内容<br/>用户IP</th><th>站点ID<br/>用户ID</th><th>商品ID</th><th>创建时间<br/>完成时间</th><th>状态</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=isset($_GET['num'])?intval($_GET['num']):30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM `pre_pay` WHERE channel IS NOT NULL AND{$sql} ORDER BY trade_no DESC LIMIT $offset,$pagesize");
while($res = $rs->fetch())
{
	if(strpos($res['input'],'|')){
		$res['input'] = explode('|',$res['input'])[0];
	}
	if(mb_strlen($res['input'], 'utf-8')>15){
		$res['input'] = mb_substr($res['input'], 0, 15, 'utf-8').'...';
	}
	echo '<tr><td><b>'.$res['trade_no'].'</b><br/>'.$res['api_trade_no'].'</td><td>'.$typename[$res['type']].'<br/>('.$res['channel'].')</td><td>'.$res['name'].'<br/>￥'.$res['money'].'</td><td>'.$res['input'].'<br/><a href="https://m.ip138.com/iplookup.asp?ip='.$res['ip'].'" target="_blank" rel="noreferrer">'.$res['ip'].'</a></td><td><a href ="sitelist.php?zid='.$res['zid'].'" target="_blank">'.$res['zid'].'</a><br/>'.(strlen($res['userid'])!=32?'<a href ="userlist.php?zid='.$res['userid'].'" target="_blank">'.$res['userid'].'</a>':'游客').'</td><td>'.($res['tid']>0?'<a href ="shoplist.php?tid='.$res['tid'].'" target="_blank">'.$res['tid'].'</a>':$res['tid']).'</td><td>'.$res['addtime'].'<br/>'.$res['endtime'].'</td><td>'.($res['status']==1?'<font color="green">已支付</font>':'<font color="red">未支付</font>').'</td><td>'.($res['status']==1?'<a class="btn btn-xs btn-info" href="list.php?kw='.$res['trade_no'].'" target="_blank">订单</a>':'<a class="btn btn-xs btn-warning" href="javascript:fillOrder(\''.$res['trade_no'].'\')">补单</a>').' <a class="btn btn-xs btn-danger" href="javascript:delOrder(\''.$res['trade_no'].'\')">删除</a></td></tr>
';
}
?>
          </tbody>
        </table>
</div>
<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$first.$link.'\')">首页</a></li>';
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$prev.$link.'\')">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-10>1?$page-10:1;
$end=$page+10<$pages?$page+10:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$i.$link.'\')">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$i.$link.'\')">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$next.$link.'\')">&raquo;</a></li>';
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$last.$link.'\')">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
?>
<script>
$("#blocktitle").html('<?php echo $con?>');
</script>