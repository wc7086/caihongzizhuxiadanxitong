<?php
/**
 * 订单列表
**/
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
adminpermission('order', 1);

function display_zt($zt,$id=0){
	if($zt==1)
		return '<a onclick="setResult('.$id.',\'订单结果\')" title="点此填写结果"><font color=green>已完成</font></a>';
	elseif($zt==2)
		return '<font color=orange>正在处理</font>';
	elseif($zt==3)
		return '<a onclick="setResult('.$id.')" title="点此填写异常原因"><font color=red>异常</font></a>';
	elseif($zt==4)
		return '<a onclick="setResult('.$id.')" title="点此填写异常原因"><font color=grey>已退单</font></a>';
	else
		return '<font color=blue>待处理</font>';
}
function display_djzt($zt,$id=0){
	if($zt==1)
		return '<span onclick="showStatus('.$id.')" title="查看订单进度" class="btn btn-success btn-xs">成功</span>';
	elseif($zt==2)
		return '<span onclick="djOrder('.$id.')" title="点击重试" class="btn btn-danger btn-xs resubmit" data-id="'.$id.'">失败</span>';
	elseif($zt==3)
		return '<a onclick="window.open(\'fakakms.php?orderid='.$id.'\')" title="查看卡密信息"><font color=green>已发卡</font></a>';
	elseif($zt==4)
		return '<span onclick="djOrder('.$id.')" title="点击重试" class="btn btn-danger btn-xs">未发卡</span>';
	else
		return '<font color=grey>未对接</font>';
}

$sqls="";
$links="";
if(!empty($_GET['starttime']) || !empty($_GET['endtime'])){
	if(!empty($_GET['starttime'])){
		$sqls.=" AND A.addtime>='{$_GET['starttime']} 00:00:00'";
		$links.="&starttime=".$_GET['starttime'];
	}
	if(!empty($_GET['endtime'])){
		$sqls.=" AND A.addtime<='{$_GET['endtime']} 23:59:59'";
		$links.="&endtime=".$_GET['endtime'];
	}
}

if(isset($_GET['kw']) && !empty($_GET['kw'])) {
	$kw = daddslashes($_GET['kw']);
	$sql=" A.`input`='{$kw}' or A.`id`='{$kw}' or `tradeno`='{$kw}'";
	if($sqls)$sql="(".$sql.")".$sqls;
	$numrows=$DB->getColumn("SELECT count(*) from pre_orders A WHERE{$sql}");
	$con='包含 '.$_GET['kw'].' 的共有 <b>'.$numrows.'</b> 个订单';
	$link='&kw='.$_GET['kw'].$links;
}elseif(isset($_GET['id'])) {
	$id = intval($_GET['id']);
	$sql=" A.`id`='$id'".$sqls;
	$numrows=$DB->getColumn("SELECT count(*) from pre_orders A WHERE{$sql}");
	$con='';
	$link='&id='.$_GET['id'].$links;
}elseif(isset($_GET['tid'])) {
	$tid = intval($_GET['tid']);
	$sql=" A.`tid`='$tid'".$sqls;
	if(isset($_GET['type']) && $_GET['type']>=0) {
		$sql.=" AND `status`='{$_GET['type']}'";
		$addstr=display_zt($_GET['type']).' 状态的';
		$links.='&type='.$_GET['type'];
	}
	$tool_name = $DB->getColumn("SELECT name FROM pre_tools WHERE tid='$tid' limit 1");
	$numrows=$DB->getColumn("SELECT count(*) from pre_orders A WHERE{$sql}");
	$con=$tool_name.' '.$addstr.'共有 <b>'.$numrows.'</b> 个订单';
	$link='&tid='.$_GET['tid'].$links;
}elseif(isset($_GET['cid'])) {
	$cid = intval($_GET['cid']);
	$tidlist = $DB->getAll("SELECT tid FROM pre_tools WHERE cid='$cid'");
	$tids = [];
	foreach($tidlist as $tidrow){
		$tids[] = $tidrow['tid'];
	}
	$sql=" A.`tid` IN (".implode(',',$tids).")".$sqls;
	if(isset($_GET['type']) && $_GET['type']>=0) {
		$sql.=" AND `status`='{$_GET['type']}'";
		$addstr=display_zt($_GET['type']).' 状态的';
		$links.='&type='.$_GET['type'];
	}
	$class_name = $DB->getColumn("SELECT name FROM pre_class WHERE cid='$cid' limit 1");
	$numrows=$DB->getColumn("SELECT count(*) from pre_orders A WHERE{$sql}");
	$con=$class_name.' '.$addstr.'共有 <b>'.$numrows.'</b> 个订单';
	$link='&cid='.$_GET['cid'].$links;
}elseif(isset($_GET['zid'])) {
	$zid = intval($_GET['zid']);
	$sql=" A.`zid`='$zid'".$sqls;
	if(isset($_GET['type']) && $_GET['type']>=0) {
		$sql.=" AND `status`='{$_GET['type']}'";
		$addstr=display_zt($_GET['type']).' 状态的';
		$links.='&type='.$_GET['type'];
	}
	$numrows=$DB->getColumn("SELECT count(*) from pre_orders A WHERE{$sql}");
	$con='站点ID:'.$_GET['zid'].' '.$addstr.'共有 <b>'.$numrows.'</b> 个订单';
	$link='&zid='.$_GET['zid'].$links;
}elseif(isset($_GET['uid'])) {
	$uid = intval($_GET['uid']);
	$sql=" A.`userid`='$uid'".$sqls;
	if(isset($_GET['type']) && $_GET['type']>=0) {
		$sql.=" AND A.`status`='{$_GET['type']}'";
		$addstr=display_zt($_GET['type']).' 状态的';
		$links.='&type='.$_GET['type'];
	}
	$numrows=$DB->getColumn("SELECT count(*) from pre_orders A WHERE{$sql}");
	$con='用户ID:'.$_GET['uid'].' '.$addstr.'共有 <b>'.$numrows.'</b> 个订单';
	$link='&uid='.$_GET['uid'].$links;
}elseif(isset($_GET['type']) && $_GET['type']>=0) {
	$sql=" A.`status`='{$_GET['type']}'".$sqls;
	$numrows=$DB->getColumn("SELECT count(*) from pre_orders A WHERE{$sql}");
	$con=''.display_zt($_GET['type']).' 状态的共有 <b>'.$numrows.'</b> 个订单';
	if($_GET['type']==3)$con.='&nbsp;[<a href="list.php?my=fillall" onclick="return confirm(\'你确定要将所有异常订单改为待处理状态吗？\');">将所有异常订单改为待处理状态</a>]';
	$link='&type='.$_GET['type'].$links;
}else{
	$sql=" 1".$sqls;
	$link=$links;
	$numrows=$DB->getColumn("SELECT count(*) from pre_orders A WHERE{$sql}");
	$ondate=$DB->getColumn("select count(*) from pre_orders A where status=1{$sqls}");
	$ondate2=$DB->getColumn("select count(*) from pre_orders A where status=2{$sqls}");
	$con='系统共有 <b>'.$numrows.'</b> 个订单，其中已完成的有 <b>'.$ondate.'</b> 个，正在处理的有 <b>'.$ondate2.'</b> 个。';
}
?>
	  <form name="form1" id="form1">
	  <div class="table-responsive">
<?php echo $con?>
        <table class="table table-striped table-bordered table-vcenter orderList">
          <thead><tr><th>订单ID</th><th>商品名称</th><th>下单数据</th><th>份数</th><th>站点ID</th><th>用户ID</th><th>添加时间</th><th>对接状态</th><th>订单状态</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=isset($_GET['num'])?intval($_GET['num']):30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT A.*,B.name FROM pre_orders A left join pre_tools B on A.tid=B.tid WHERE{$sql} order by id desc limit $offset,$pagesize");
while($res = $rs->fetch())
{
echo '<tr><td><input type="checkbox" name="checkbox[]" id="list1" value="'.$res['id'].'" onClick="unselectall1()"><b>'.$res['id'].'</b></td><td><span onclick="showOrder('.$res['id'].')" title="点击查看详情">'.$res['name'].'</span></td><td class="wbreak"><span onclick="inputOrder('.$res['id'].')" title="点击修改数据">'.$res['input'].($res['input2']?'<br/>'.$res['input2']:null).($res['input3']?'<br/>'.$res['input3']:null).($res['input4']?'<br/>'.$res['input4']:null).($res['input5']?'<br/>'.$res['input5']:null).'</span></td><td><span onclick="inputNum('.$res['id'].')" title="点击修改份数">'.$res['value'].'</span></td><td><a href ="sitelist.php?zid='.$res['zid'].'" target="_blank">'.$res['zid'].'</a></td><td>'.(strlen($res['userid'])!=32?'<a href ="userlist.php?zid='.$res['userid'].'" target="_blank">'.$res['userid'].'</a>':'0').'</td><td>'.$res['addtime'].'</td><td>'.display_djzt($res['djzt'],$res['id']).'</td><td>'.display_zt($res['status'],$res['id']).'</td><td><select onChange="javascript:setStatus(\''.$res['id'].'\',this.value)" class="form-control"><option selected>操作订单</option><option value="0">待处理</option><option value="2">正在处理</option><option value="1">已完成</option><option value="4">已退单</option><option value="3">异常</option>'.($res['zid']>1||is_numeric($res['userid'])?'<option value="6">退款</option>':null).'<option value="5">删除订单</option></select></td></tr>';
}
?>
          </tbody>
        </table>
<label><input name="chkAll1" type="checkbox" id="chkAll1" onClick="this.value=check1(this.form.list1)" value="checkbox">全选</label>&nbsp;
<select name="status"><option selected>操作订单</option><option value="0">待处理</option><option value="2">正在处理</option><option value="1">已完成</option><option value="3">异常</option><option value="5">重新下单</option><option value="6">订单退款</option><option value="4">删除订单</option></select>
<button type="button" onclick="operation()">确定</button>
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
