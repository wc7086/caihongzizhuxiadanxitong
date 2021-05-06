<?php
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
adminpermission('site', 1);

$orderby = "zid desc";
if(isset($_GET['zid'])){
	$power = $DB->getRow("SELECT power FROM pre_site WHERE zid='{$_GET['zid']}' limit 1");
	if($power && $power['power']==0)exit("<script language='javascript'>top.location.href='./userlist.php?zid={$_GET['zid']}';</script>");
	$sql = " zid={$_GET['zid']} and power>0";
}elseif(isset($_GET['power'])){
	$sql = " power={$_GET['power']}";
	$link = '&power='.$_GET['power'];
}elseif(isset($_GET['sort'])){
	$sql = " power>0";
	if($_GET['sort'] == '0')$orderby = "rmb asc";
	elseif($_GET['sort'] == '1')$orderby = "rmb desc";
	$link = '&sort='.$_GET['sort'];
}elseif(isset($_GET['kw'])){
	$sql = " (user='{$_GET['kw']}' or domain='{$_GET['kw']}' or domain2='{$_GET['kw']}' or qq='{$_GET['kw']}') and power>0";
	$link = '&kw='.$_GET['kw'];
}else{
	$sql = " power>0";
}
$numrows=$DB->getColumn("SELECT count(*) from pre_site where{$sql}");
?>
	  <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ZID</th><th>类型</th><th>用户名</th><th>站点名称/站长QQ</th><th>余额</th><th>开通/到期时间</th><th>绑定域名</th><th>状态</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM pre_site WHERE{$sql} order by {$orderby} limit $offset,$pagesize");
while($res = $rs->fetch())
{
echo '<tr><td><b>'.$res['zid'].'</b></td><td><span onclick="setSuper('.$res['zid'].')" title="修改站点类型" class="btn btn-default btn-xs">'.($res['power']==2?'<font color=red>专业版</font>':'<font color=blue>普及版</font>').'</span></td><td>'.$res['user'].'</td><td>'.$res['sitename'].'<br/>'.$res['qq'].'</td><td><a href="javascript:showRecharge('.$res['zid'].')" title="点击充值">'.$res['rmb'].'</a></td><td>'.$res['addtime'].'<br/><a href="javascript:setEndtime('.$res['zid'].')" title="点击续期">'.$res['endtime'].'</a></td><td><a href="http://'.$res['domain'].'" target="_blank" rel="noreferrer">'.$res['domain'].'</a><br/><a href="http://'.$res['domain2'].'" target="_blank" rel="noreferrer">'.$res['domain2'].'</a></td><td>'.($res['status']==1?'<span class="btn btn-xs btn-success" onclick="setActive('.$res['zid'].',0)">开启</span>':'<span class="btn btn-xs btn-warning" onclick="setActive('.$res['zid'].',1)">关闭</span>').'</td><td><a href="./sitelist.php?my=edit&zid='.$res['zid'].'" class="btn btn-info btn-xs">编辑</a>&nbsp;<a href="./list.php?zid='.$res['zid'].'" class="btn btn-warning btn-xs">订单</a>&nbsp;<a href="./record.php?zid='.$res['zid'].'" class="btn btn-success btn-xs">明细</a>&nbsp;<a href="javascript:delSite('.$res['zid'].')" class="btn btn-xs btn-danger">删除</a>&nbsp;<a href="./sso.php?zid='.$res['zid'].'" class="btn btn-default btn-xs" target="_blank">登录</a></td></tr>';
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