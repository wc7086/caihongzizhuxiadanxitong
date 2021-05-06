<?php
/**
 * 推广记录
**/
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
adminpermission('shop', 1);

if(isset($_GET['kw'])){
	$kw = trim(daddslashes($_GET['kw']));
	$sql=" A.`qq`='$kw'";
	$numrows=$DB->getColumn("SELECT count(*) FROM pre_invite A LEFT JOIN pre_tools B ON A.tid=B.tid WHERE{$sql}");
	$con='查单QQ <b>'.$kw.'</b> 的共有 <b>'.$numrows.'</b> 条推广记录';
	$link='&kw='.$kw;
}elseif(isset($_GET['nid'])){
	$nid = intval($_GET['nid']);
	$numrows=$DB->getColumn("SELECT count(*) from pre_invite where nid='$nid'");
	$sql=" nid='$nid'";
	$con='该商品共有 <b>'.$numrows.'</b> 条推广记录';
	$link='&nid='.$nid;
}else{
	$numrows=$DB->getColumn("SELECT count(*) from pre_invite");
	$sql=" 1";
	$con='系统共有 <b>'.$numrows.'</b> 条推广记录';
}
?>
	  <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>商品名称</th><th>查单QQ</th><th>下单账号</th><th>奖励次数</th><th>进度</th><th>状态</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=isset($_GET['num'])?intval($_GET['num']):30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT A.*,B.`name` FROM `pre_invite` A LEFT JOIN `pre_tools` B ON A.`tid`=B.`tid` WHERE{$sql} ORDER BY A.`id` DESC LIMIT $offset,$pagesize");
while($res = $rs->fetch())
{
	if($res['plan']>0){
		$progress = $res['click'].'/'.$res['plan'];
	}else{
		$progress = '--';
	}
	if($res['status']==1){
		$status = '<font color="green">已完成</font>';
	}else{
		$status = '<font color="orange">进行中</font>';
	}
	$input_arr = explode('|',$res['input']);
echo '<tr><td><b>'.$res['id'].'</b></td><td><a href="./shoplist.php?tid='.$res['tid'].'">'.$res['name'].'</a></td><td>'.$res['qq'].'</td><td>'.$input_arr[0].'</td><td>'.$res['count'].'</td><td>'.$progress.'</td><td>'.$status.'</td><td><span class="btn btn-xs btn-danger" onclick="delInvite('.$res['id'].')">删除</span></td></tr>
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