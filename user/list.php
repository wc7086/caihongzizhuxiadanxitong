<?php
/**
 * 订单管理
**/
include("../includes/common.php");
$title='订单管理';
include './head.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<style>
td.wbreak{max-width:420px;word-break:break-all;}
#orderItem .orderTitle{word-break:keep-all;}
#orderItem .orderContent{word-break:break-all;}
.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}
.form-inline .form-group {
    display: inline-block;
    margin-bottom: 0;
    vertical-align: middle;
}
</style>
<div class="wrapper">
  <div class="col-sm-12">
<div class="panel panel-default">
<div class="modal fade" id="search2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">
						&times;
					</span>
					<span class="sr-only">
						Close
					</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					订单状态说明
				</h4>
			</div>
			<div class="modal-body">
			<?php echo $conf['gg_search']?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					关闭
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">
						&times;
					</span>
					<span class="sr-only">
						Close
					</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					搜索订单
				</h4>
			</div>
			<div class="modal-body">
				<form action="list.php" method="GET">
					<input type="text" class="form-control" name="kw" placeholder="请输入下单账号">
					<br/>
					<input type="submit" class="btn btn-primary btn-block" value="搜索">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					关闭
				</button>
			</div>
		</div>
	</div>
</div>
<?php
function display_zt($zt){
	if($zt==1)
		return '<font color=green>已完成</font>';
	elseif($zt==2)
		return '<font color=orange>正在处理</font>';
	elseif($zt==3)
		return '<font color=red>异常</font>';
	elseif($zt==4)
		return '<font color=grey>已退款</font>';
	else
		return '<font color=blue>待处理</font>';
}

$sqls = $userrow['power']>0?"A.zid='{$userrow['zid']}'":"A.userid='{$userrow['zid']}'";

if(isset($_GET['kw']) && !empty($_GET['kw'])) {
	$kw=daddslashes($_GET['kw']);
	$sql=" (A.`input`='{$kw}' OR A.`id`='{$kw}' OR A.`tradeno`='{$kw}') AND {$sqls}";
	$numrows=$DB->getColumn("SELECT count(*) FROM pre_orders A WHERE{$sql}");
	$con='
	<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;"> '.$_GET['kw'].' 订单查询 - [<a href="list.php" style="color:#fff00f">查看全部</a>]</div>
	<div class="well well-sm" style="margin: 0;">包含 '.$_GET['kw'].' 的共有 <b>'.$numrows.'</b> 个订单</div>
	<div class="wrapper">';
	$link='&kw='.$_GET['kw'];
}else{
	$sql=" {$sqls}";
	$numrows=$DB->getColumn("SELECT count(*) FROM pre_orders A WHERE{$sql}");
	$ondate=$DB->getColumn("SELECT count(*) FROM pre_orders A WHERE status=1 AND{$sql}");
	$ondate2=$DB->getColumn("SELECT count(*) FROM pre_orders A WHERE status=2 AND{$sql}");
	$con='
	<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">订单查询</div>
	<div class="well well-sm" style="margin: 0;">共有 <b>'.$numrows.'</b> 个订单，其中已完成的有 <b>'.$ondate.'</b> 个，正在处理的有 <b>'.$ondate2.'</b> 个。</div>
	<div class="wrapper">';
}
echo $con;
?>
<form action="./list.php" method="GET" class="form-inline">
  <div class="form-group">
    <label><b>搜索订单</b></label>
    <input type="text" class="form-control" name="kw" placeholder="请输入下单账号或订单号" value="">
	<button type="submit" class="btn btn-info"><i class="fa fa-search"></i>&nbsp;搜索</button>
	<a href="#" data-toggle="modal" data-target="#search2" id="search2" class="btn btn-success"><i class="fa fa-exclamation-circle"></i>&nbsp;订单状态说明</a>
  </div>
</form>
			</div>
			<div class="table-responsive">
				<table class="table table-striped b-t b-light">
					<thead>
						<tr>
							<th>
								操作
							</th>
							<th>
								订单ID
							</th>
							<th>
								商品名称
							</th>
							<th>
								下单信息
							</th>
							<th>
								份数
							</th>
							<th>
								下单时间
							</th>
							<th>
								状态
							</th>
						</tr>
					</thead>
					<tbody>
						
<?php
$pagesize=30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT A.*,B.name FROM pre_orders A left join pre_tools B on A.tid=B.tid WHERE{$sql} ORDER BY id DESC LIMIT $offset,$pagesize");
while($res = $rs->fetch())
{
	if($res['djzt']==3 && $res['userid']!=$userrow['zid']){
		$input = '****'.substr($res['input'],4);
	}else{
		$input = $res['input'];
	}
echo '<tr>
	<td>
								'.($res['userid']==$userrow['zid']?'<a href="javascript:showOrder('.$res['id'].',\''.md5($res['id'].SYS_KEY.$res['id']).'\')" title="查看订单详细" class="btn btn-info btn-xs">详细</a>':'<a href="javascript:;" class="btn btn-info btn-xs" disabled title="不是你支付的订单">详细</a>').'
							</td>
							<td>
								'.$res['id'].'
							</td>
							<td>
								'.$res['name'].'
							</td>
							<td class="wbreak">
								'.$input.'
							</td>
							<td>
								'.$res['value'].'
							</td>
							<td>
								'.$res['addtime'].'
							</td>
							<td>
								<font color=green>
									'.display_zt($res['status']).'
								</font>
							</td>
						</tr>';}
?>
					</tbody>
				</table>
			</div>
			<center>
<?php
echo'<ul class="pagination"  style="margin-left:1em">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="list.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="list.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-10>1?$page-10:1;
$end=$page+10<$pages?$page+10:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="list.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="list.php?page='.$i.$link.'">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="list.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="list.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul></center>';
#分页
?>
	</div>
</div>
<?php include './foot.php';?>
<script>
function showOrder(id,skey){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	var status = ['<span class="label label-primary">待处理</span>','<span class="label label-success">已完成</span>','<span class="label label-warning">处理中</span>','<span class="label label-danger">异常</span>','<font color=red>已退款</font>'];
	$.ajax({
		type : "POST",
		url : "../ajax.php?act=order",
		data : {id:id,skey:skey},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var item = '<table class="table table-condensed table-hover" id="orderItem">';
				item += '<tr><td colspan="6" style="text-align:center" class="orderTitle"><b>订单基本信息</b></td></tr><tr class="orderTitle"><td class="info" class="orderTitle">订单编号</td><td colspan="5" class="orderContent">'+id+'</td></tr><tr><td class="info" class="orderTitle">商品名称</td><td colspan="5" class="orderContent">'+data.name+'</td></tr><tr><td class="info">订单金额</td class="orderTitle"><td colspan="5" class="orderContent">'+data.money+'元</td></tr><tr><td class="info">购买时间</td class="orderTitle"><td colspan="5">'+data.date+'</td></tr><tr><td class="info" class="orderTitle">下单信息</td><td colspan="5" class="orderContent">'+data.inputs+'</td><tr><td class="info" class="orderTitle">订单状态</td><td colspan="5" class="orderContent">'+status[data.status]+'</td></tr>';
				if(data.complain){
					item += '<tr><td class="info" class="orderTitle">订单操作</td><td class="orderContent"><a href="./workorder.php?my=add&orderid='+id+'&skey='+skey+'" target="_blank" onclick="return checklogin('+data.islogin+')" class="btn btn-xs btn-default">投诉订单</a></td></tr>';
				}
				if(data.list && data.list.order_state){
					item += '<tr><td colspan="6" style="text-align:center" class="orderTitle"><b>订单实时状态</b></td><tr><td class="warning">下单数量</td><td>'+data.list.num+'</td><td class="warning">下单时间</td><td colspan="3">'+data.list.add_time+'</td></tr><tr><td class="warning">初始数量</td><td>'+data.list.start_num+'</td><td class="warning">当前数量</td><td>'+data.list.now_num+'</td><td class="warning">订单状态</td><td><font color=blue>'+data.list.order_state+'</font></td></tr>';
				}else if(data.kminfo){
					item += '<tr><td colspan="6" style="text-align:center" class="orderTitle"><b>以下是你的卡密信息</b></td><tr><td colspan="6" class="orderContent">'+data.kminfo+'</td></tr>';
				}else if(data.result){
					item += '<tr><td colspan="6" style="text-align:center" class="orderTitle"><b>处理结果</b></td><tr><td colspan="6" class="orderContent">'+data.result+'</td></tr>';
				}
				if(data.desc){
					item += '<tr><td colspan="6" style="text-align:center" class="orderTitle"><b>商品简介</b></td><tr><td colspan="6" class="orderContent">'+data.desc+'</td></tr>';
				}
				item += '</table>';
				var area = [$(window).width() > 480 ? '480px' : '100%', ';max-height:100%'];
				layer.open({
				  type: 1,
				  area: area,
				  title: '订单详细信息',
				  skin: 'layui-layer-rim',
				  content: item
				});
			}else{
				layer.alert(data.msg);
			}
		}
	});
}
</script>
</body>
</html>