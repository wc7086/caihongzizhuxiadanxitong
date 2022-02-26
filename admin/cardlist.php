<?php
/**
 * 商品兑换卡密列表
**/
include("../includes/common.php");
$title='商品兑换卡密列表';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
    <div class="col-sm-12 col-md-10 center-block" style="float: none;">
<div class="modal" align="left" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索卡密</h4>
      </div>
      <div class="modal-body">
      <form action="cardlist.php" method="GET">
<input type="text" class="form-control" name="kw" placeholder="请输入卡密"><br/>
<input type="submit" class="btn btn-primary btn-block" value="搜索"></form>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
adminpermission('site', 1);

function getkm($len = 18)
{
	$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	$strlen = strlen($str);
	$randstr = "";
	for ($i = 0; $i < $len; $i++) {
		$randstr .= $str[mt_rand(0, $strlen - 1)];
	}
	return $randstr;
}

$my=isset($_GET['my'])?$_GET['my']:null;

if($my=='add'){
$rs=$DB->query("SELECT * FROM pre_class WHERE 1 order by sort asc");
$select='<option value="0">请选择商品分类</option>';
while($res = $rs->fetch()){
	$select.='<option value="'.$res['cid'].'">'.$res['name'].'</option>';
}
?>
<div class="block">
<div class="block-title"><h3 class="panel-title">生成卡密</h3></div>
<div class="">
  <form action="./cardlist.php?my=add_submit" method="post" class="form" role="form">
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			选择商品
		</span>
		<select id="cid" class="form-control"><?php echo $select?></select>
		<select id="tid" name="tid" class="form-control"></select>
	</div>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			商品份数
		</span>
		<input type="number" min="1" max="1000" name="value" value="" class="form-control" placeholder="一张卡密可兑换几份该商品"/>
	</div>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			生成数量
		</span>
		<input type="number" min="1" max="1000" name="num" value="" class="form-control" placeholder="输入生成卡密的数量"/>
	</div>
  </div>
	<div class="form-group">
	  <input type="submit" name="submit" value="添加" class="btn btn-primary btn-block"/>
	</div>
  </form>
<br/><a href="./cardlist.php">>>返回卡密列表</a>
</div></div>
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script>
$(document).ready(function(){
	$("#cid").change(function () {
		var cid = $(this).val();
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$("#tid").empty();
		$("#tid").append('<option value="0">请选择商品</option>');
		$.ajax({
			type : "GET",
			url : "./ajax.php?act=gettool&cid="+cid,
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					var num = 0;
					$.each(data.data, function (i, res) {
						$("#tid").append('<option value="'+res.tid+'">'+res.name+'</option>');
						num++;
					});
					$("#tid").val(0);
					if(num==0 && cid!=0)$("#tid").html('<option value="0">该分类下没有商品</option>');
				}else{
					layer.alert(data.msg);
				}
			},
			error:function(data){
				layer.msg('服务器错误');
				return false;
			}
		});
	});
	if($("#cid").length>0){
		$("#cid").change();
	}
});
</script>
<?php
}elseif($my=='add_submit'){
if(!checkRefererHost())exit();
$tid=intval($_POST['tid']);
$value=intval($_POST['value']);
$num=intval($_POST['num']);
if($value==0)showmsg('购买份数不能为空',3);
echo "<ul class='list-group'><li class='list-group-item active'>成功生成以下卡密</li>";
for ($i = 0; $i < $num; $i++) {
	$km=getkm(18);
	$sql=$DB->exec("insert into `pre_kms` (`type`,`km`,`tid`,`num`,`addtime`) values (1,'".$km."','".$tid."','".$value."','".$date."')");
	if($sql) {
		echo "<li class='list-group-item'>$km</li>";
	}
}

echo '<a href="./cardlist.php" class="btn btn-default btn-block">>>返回卡密列表</a>';
}

elseif($my=='del'){
if(!checkRefererHost())exit();
echo '<div class="block">
<div class="block-title w h"><h3 class="panel-title">删除卡密</h3></div>
<div class=" box">';
$id=$_GET['id'];
$sql=$DB->exec("DELETE FROM pre_kms WHERE kid='$id'");
if($sql){echo '删除成功！';}
else{echo '删除失败！';}
echo '<hr/><a href="./cardlist.php">>>返回卡密列表</a></div></div>';
}

elseif($my=='qk'){//清空卡密
if(!checkRefererHost())exit();
echo '<div class="block">
<div class="block-title w h"><h3 class="panel-title">清空卡密</h3></div>
<div class=" box">
您确认要清空所有卡密吗？清空后无法恢复！<br><a href="./cardlist.php?my=qk2">确认</a> | <a href="javascript:history.back();">返回</a></div></div>';
}
elseif($my=='qk2'){//清空卡密结果
if(!checkRefererHost())exit();
echo '<div class="block">
<div class="block-title w h"><h3 class="panel-title">清空卡密</h3></div>
<div class=" box">';
if($DB->exec("DELETE FROM pre_kms WHERE type=1")!==false){
echo '<div class="box">清空成功.</div>';
}else{
echo'<div class="box">清空失败.</div>';
}
echo '<hr/><a href="./cardlist.php">>>返回卡密列表</a></div></div>';
}
elseif($my=='qkuse'){//清空已使用卡密
if(!checkRefererHost())exit();
echo '<div class="block">
<div class="block-title w h"><h3 class="panel-title">清空卡密</h3></div>
<div class=" box">
您确认要清空所有卡密吗？清空后无法恢复！<br><a href="./cardlist.php?my=qkuse2">确认</a> | <a href="javascript:history.back();">返回</a></div></div>';
}
elseif($my=='qkuse2'){//清空已使用卡密结果
if(!checkRefererHost())exit();
echo '<div class="block">
<div class="block-title w h"><h3 class="panel-title">清空卡密</h3></div>
<div class=" box">';
if($DB->exec("DELETE FROM pre_kms WHERE type=1 AND status=1")!==false){
echo '<div class="box">清空成功.</div>';
}else{
echo'<div class="box">清空失败.</div>';
}
echo '<hr/><a href="./cardlist.php">>>返回卡密列表</a></div></div>';
}
else
{

if(isset($_GET['kw'])) {
	$sql=" type=1 AND `km`='{$_GET['kw']}'";
	$numrows=$DB->getColumn("SELECT count(*) from pre_kms WHERE{$sql}");
	$con='包含 '.$_GET['kw'].' 的共有 <b>'.$numrows.'</b> 个卡密';
}else{
	$sql=" type=1";
	$numrows=$DB->getColumn("SELECT count(*) from pre_kms WHERE{$sql}");
	$con='共有 <b>'.$numrows.'</b> 个卡密';
}
?>
<div class="block">
<div class="block-title clearfix">
<h2><?php echo $con;?></h2>
</div>

  <a href="cardlist.php?my=add" class="btn btn-primary">生成卡密</a>
  <a href="cardlist.php?my=qk" class="btn btn-danger">清空</a>
  <a href="cardlist.php?my=qkuse" class="btn btn-danger">清空已使用</a>
  <a href="#" data-toggle="modal" data-target="#search" id="search" class="btn btn-success">搜索</a>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>卡密</th><th>商品</th><th>状态</th><th>添加时间</th><th>使用时间</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT A.*,B.name FROM pre_kms A LEFT JOIN pre_tools B ON A.tid=B.tid WHERE{$sql} order by kid desc limit $offset,$pagesize");
while($res = $rs->fetch())
{
if($res['status']==0) {
	$isuse='<font color="green">未使用</font>';
} else {
	$isuse='<font color="red">已使用</font>(<a href="./list.php?id='.$res['orderid'].'" target="_blank">'.$res['orderid'].'</a>)';
}
$shopname=mb_substr($res['name'], 0, 20, 'utf-8');
echo '<tr><td><b>'.$res['km'].'</b></td><td>'.$shopname.($res['num']>1?' X'.$res['num']:'').'</td><td>'.$isuse.'</td><td>'.$res['addtime'].'</td><td>'.$res['usetime'].'</td><td><a href="./cardlist.php?my=del&id='.$res['kid'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此卡密吗？\');">删除</a></td></tr>';
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
echo '<li><a href="cardlist.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="cardlist.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-10>1?$page-10:1;
$end=$page+10<$pages?$page+10:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="cardlist.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="cardlist.php?page='.$i.$link.'">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="cardlist.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="cardlist.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
}
?>
<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span> 卡密兑换页面地址：<a href="/?mod=cardbuy">/?mod=cardbuy</a>
</div>
    </div>
  </div>
