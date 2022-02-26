<?php
/**
 * 商品管理
**/
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
adminpermission('shop', 1);

$shequlist=$DB->getAll("SELECT id,url FROM pre_shequ order by id asc");
$shequurls=[];
foreach($shequlist as $res){
	$shequurls[$res['id']]=$res['url'].($res['remark']?' ('.$res['remark'].')':null);
}

function display_shoptype($type, $shequ=0){
	global $shequurls;
	if($type==1||$type==2)
		return '<span class="btn-warning btn-xs enable-tooltips" title="'.$shequurls[$shequ].'" data-toggle="tooltip" data-original-title="'.$shequurls[$shequ].'">对接</span>';
	elseif($type==4)
		return '<span class="btn-success btn-xs">发卡</span>';
	else
		return '<span class="btn-info btn-xs">自营</span>';
}

$classlist=$DB->getAll("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$select='<option value="0">未分类</option>';
$shua_class[0]='未分类';
foreach($classlist as $res){
	$shua_class[$res['cid']]=$res['name'];
	$select.='<option value="'.$res['cid'].'">'.$res['name'].'</option>';
}

if($_SESSION['price_class']){
	$price_class = $_SESSION['price_class'];
}else{
	$pricelist=$DB->getAll("SELECT * FROM pre_price order by id asc");
	$price_class[0]='不加价';
	foreach($pricelist as $res){
		$price_class[$res['id']]=$res['name'];
	}
}

$pagesize = isset($_GET['num'])?intval($_GET['num']):30;
$orderby = 'A.tid desc';
if(isset($_GET['kw'])){
	$kw = trim(daddslashes($_GET['kw']));
	$sql=" A.name LIKE '%$kw%'";
	if(is_numeric($kw))$sql.=" OR A.tid='$kw'";
	$numrows=$DB->getColumn("SELECT count(*) from pre_tools A where{$sql}");
	$con='包含 <b>'.$kw.'</b> 的共有 <b>'.$numrows.'</b> 个商品';
	$link='&kw='.$kw;
}elseif(isset($_GET['cid'])){
	$cid = intval($_GET['cid']);
	$sql=" A.cid='$cid'";
	$numrows=$DB->getColumn("SELECT count(*) from pre_tools A where{$sql}");
	$con='分类 <a href="../?cid='.$cid.'" target="_blank">'.$shua_class[$cid].'</a> 共有 <b>'.$numrows.'</b> 个商品';
	$link='&cid='.$cid;
	$orderby = 'A.sort asc';
	if($pagesize<$numrows)$pagesize=$numrows;
}elseif(isset($_GET['prid'])){
	$prid = intval($_GET['prid']);
	$sql=" prid='$prid'";
	$numrows=$DB->getColumn("SELECT count(*) from pre_tools where{$sql}");
	$con='加价模板 '.$price_class[$prid].' 共有 <b>'.$numrows.'</b> 个商品';
	$link='&prid='.$prid;
}elseif(isset($_GET['tid'])){
	$tid = intval($_GET['tid']);
	$sql=" tid='$tid'";
	$numrows=$DB->getColumn("SELECT count(*) from pre_tools where{$sql}");
	$con='商品列表';
	$link='&tid='.$tid;
}else{
	$numrows=$DB->getColumn("SELECT count(*) from pre_tools");
	$sql=" 1";
	$con='系统共有 <b>'.$numrows.'</b> 个商品';
}
?>
	  <form name="form1" id="form1">
	  <div class="table-responsive">
        <table class="table table-striped" id="shoplist">
          <thead><tr><th>商品名称</th><th>商品价格设置</th><th>商品类型</th><th class="<?php echo isset($_GET['cid'])?'hide':'';?>">所属分类</th><th class="<?php echo isset($_GET['cid'])?'':'hide';?>">排序操作</th><th>库存</th><th>状态</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT A.*,B.name classname FROM pre_tools A LEFT JOIN pre_class B ON A.cid=B.cid WHERE{$sql} order by {$orderby} limit $offset,$pagesize");
while($res = $rs->fetch())
{
if($res['is_curl']==4){
	$stock = '发卡';
}elseif($res['stock']===null){
	$stock = '无限';
}else{
	$stock = $res['stock'];
}
echo '<tr><td><input type="checkbox" name="checkbox[]" id="list1" value="'.$res['tid'].'" onClick="unselectall1()">&nbsp;<a href="javascript:show('.$res['tid'].')" style="color:#000">'.$res['name'].'</a></td>'.($res['prid']>0?'<td><span onclick="getPrice('.$res['tid'].')"><font color="blue">'.$price_class[$res['prid']].'</font>&nbsp;(成本:'.$res['price'].')</span></td>':'<td><span onclick="getPrice('.$res['tid'].')">'.$res['price'].'｜'.$res['cost'].'｜'.$res['cost2'].'</span></td>').'<td>'.display_shoptype($res['is_curl'],$res['shequ']).'
</td><td class="'.(isset($_GET['cid'])?'hide':'').'"><a href="./shoplist.php?cid='.$res['cid'].'">'.($res['classname']?$res['classname']:'未分类').'</a></td><td class="'.(isset($_GET['cid'])?'':'hide').'"><a class="btn btn-xs sort_btn" title="移到顶部" onclick="sort('.$res['cid'].','.$res['tid'].',0)"><i class="fa fa-long-arrow-up"></i></a><a class="btn btn-xs sort_btn" title="移到上一行" onclick="sort('.$res['cid'].','.$res['tid'].',1)"><i class="fa fa-chevron-circle-up"></i></a><a class="btn btn-xs sort_btn" title="移到下一行" onclick="sort('.$res['cid'].','.$res['tid'].',2)"><i class="fa fa-chevron-circle-down"></i></a><a class="btn btn-xs sort_btn" title="移到底部" onclick="sort('.$res['cid'].','.$res['tid'].',3)"><i class="fa fa-long-arrow-down"></i></a></td><td><a href="javascript:setStock('.$res['tid'].',\''.$stock.'\')">'.$stock.'</a></td><td>'.($res['close']==1?'<span class="btn btn-xs btn-warning" onclick="setClose('.$res['tid'].',0)">已下架</span>':'<span class="btn btn-xs btn-success" onclick="setClose('.$res['tid'].',1)">上架中</span>').'&nbsp;'.($res['active']==1?'<span class="btn btn-xs btn-success" onclick="setActive('.$res['tid'].',0)">显示</span>':'<span class="btn btn-xs btn-warning" onclick="setActive('.$res['tid'].',1)">隐藏</span>').'</td><td><a href="./shopedit.php?my=edit&tid='.$res['tid'].'" class="btn btn-info btn-xs">编辑</a>&nbsp;<a href="./list.php?tid='.$res['tid'].'" class="btn btn-warning btn-xs">订单</a>&nbsp;<span href="./shopedit.php?my=delete&tid='.$res['tid'].'" class="btn btn-xs btn-danger" onclick="delTool('.$res['tid'].')">删除</span></td></tr>
';
}
?>
          </tbody>
        </table>
<input type="hidden" name="prid"/>
<input type="hidden" name="stock"/>
<label><input name="chkAll1" type="checkbox" id="chkAll1" onClick="this.value=check1(this.form.list1)" value="checkbox">全选</label>&nbsp;
<select name="aid"><option selected>批量操作</option><option value="10">&gt;改加价模板</option><option value="11">&gt;改商品库存</option><option value="1">&gt;改为显示</option><option value="2">&gt;改为隐藏</option><option value="3">&gt;改为上架中</option><option value="4">&gt;改为已下架</option><option value="5">&gt;删除选中</option><option value="6">&gt;复制选中</option></select><button type="button" onclick="change()">执行</button>&nbsp;&nbsp;
<select name="cid"><option selected>将选定商品移动到分类</option><?php echo $select?></select><button type="button" onclick="move()">确定移动</button>
</div>
</form>
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