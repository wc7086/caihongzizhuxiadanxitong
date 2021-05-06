<?php
if(!defined('IN_CRONLITE'))exit();
if($islogin2==1){
	$cookiesid = $userrow['zid'];
}

function display_zt2($zt){
	if($zt==1)
		return '<font color=green>已完成</font>';
	elseif($zt==2)
		return '<font color=orange>正在处理</font>';
	elseif($zt==3)
		return '<font color=red>异常</font>';
	elseif($zt==4)
		return '<font color=grey>已退单</font>';
	else
		return '<font color=blue>待处理</font>';
}

$data=trim(daddslashes($_GET['data']));
$page=isset($_GET['page'])?intval($_GET['page']):1;
if(!empty($data)){
	if(strlen($data)==17 && is_numeric($data))$sql=" A.`tradeno`='{$data}'";
	else $sql=" A.`input`='{$data}'";
	if($conf['queryorderlimit']==1)$sql.=" AND A.`userid`='$cookiesid'";
}
else $sql=" A.`userid`='{$cookiesid}'";
$limit = 10;
$start = $limit * ($page-1);
$sql = "SELECT A.*,B.`name` FROM `pre_orders` A LEFT JOIN `pre_tools` B ON A.`tid`=B.`tid` WHERE{$sql} ORDER BY A.`id` DESC LIMIT {$start},{$limit}";
$rs=$DB->query($sql);
$record=array();
$count = 0;
while($res = $rs->fetch()){
	$count++;
	$record[]=array('id'=>$res['id'],'tid'=>$res['tid'],'input'=>$res['input'],'money'=>$res['money'],'name'=>$res['name'],'value'=>$res['value'],'addtime'=>$res['addtime'],'endtime'=>$res['endtime'],'result'=>$res['result'],'status'=>$res['status'],'djzt'=>$res['djzt'],'skey'=>md5($res['id'].SYS_KEY.$res['id']));
}

$cssadd = '<link rel="stylesheet" href="'.$cdnserver.'assets/faka/css/user.css" />';
include_once TEMPLATE_ROOT.'faka/head2.php';
?>
<style>
td.wbreak{max-width:420px;word-break:break-all;}
#orderItem .orderTitle{word-break:keep-all;}
#orderItem .orderContent{word-break:break-all;}
td{padding: 5px;border-top: 1px solid #ddd;}
.table{width: 100%;max-width: 100%;margin-bottom: 20px;}
.table>tbody>tr>td.info{background-color: #d9edf7;}
</style>
<div class="search w">
<form action="?" method="get"><input type="hidden" name="mod" value="query"/>
	<input name="data" type="text" class="search_input" placeholder="请输入订单号或填写的联系方式查询"><input name="submit" type="submit" class="search_submit" value="查询">
</form>
</div>
<div class="menux"><div align="center">订单管理</div></div>

<?php
foreach($record as $row){
	echo '<div class="msg w"><div class="msg_title">&nbsp;<font class="bl_type" style="background-color:#019bbc">自动发货</font> ID:'.$row['id'].' <font size="2" color="#cbcbcb">'.$row['addtime'].'</font></div><div class="msg_title2"><h1>商品：<a href="./?mod=buy&tid='.$row['tid'].'">'.$row['name'].'</a></h1><span></span></div><div class="msg_content">总价'.$row['money'].'元 数量'.$row['value'].'个 '.display_zt2($row['status']).' '.(($row['djzt']==3||$row['djzt']==4)?'<a href="./?mod=faka&id='.$row['id'].'&skey='.$row['skey'].'" title="点击查看卡密"><span class="bl_type" style="background-color:#c500e8;">提取卡密</span></a>':'<a href="javascript:showOrder('.$row['id'].',\''.$row['skey'].'\')" title="点击查看订单详情"><span class="bl_type" style="background-color:#5aab03;">查看详情</span></a>').'</div></div>';
}
?>
<div class="bl_more"> <div  class='text-right'><ul class='pagination'>       <li><span class="rows">共<?php echo $count?>条</span> </li></ul></div></div>

<div class="m_user w">
<a href="#">返回顶部</a>
</div>

<div class="copyright">Copyright &copy; <?php echo date("Y")?> <?php echo $conf['sitename']?></div>
</div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script type="text/javascript">
function changepwd(id,skey) {
	pwdlayer = layer.open({
	  type: 1,
	  title: '修改密码',
	  skin: 'layui-layer-rim',
	  content: '<div class="form-group"><div class="bl_view_title"><div class="input-group-addon">密码</div><input type="text" id="pwd" value="" class="search_input2" placeholder="请填写新的密码" required/></div></div><div class="go_buy"><input type="submit" id="save" onclick="saveOrderPwd('+id+',\''+skey+'\')" class="btn btn-primary btn-block" value="保存"></div>'
	});
}
function saveOrderPwd(id,skey) {
	var pwd=$("#pwd").val();
	if(pwd==''){layer.alert('请确保每项不能为空！');return false;}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax.php?act=changepwd",
		data : {id:id,pwd:pwd,skey:skey},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('保存成功！');
				layer.close(pwdlayer);
			}else{
				layer.alert(data.msg);
			}
		} 
	});
}
function showOrder(id,skey){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	var status = ['<span class="label label-primary">待处理</span>','<span class="label label-success">已完成</span>','<span class="label label-warning">处理中</span>','<span class="label label-danger">异常</span>','<font color=red>已退款</font>'];
	$.ajax({
		type : "POST",
		url : "ajax.php?act=order",
		data : {id:id,skey:skey},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var item = '<table class="table table-condensed table-hover" id="orderItem">';
				item += '<tr><td colspan="6" style="text-align:center" class="orderTitle"><b>订单基本信息</b></td></tr><tr><td class="info orderTitle">订单编号</td><td colspan="5" class="orderContent">'+id+'</td></tr><tr><td class="info orderTitle">商品名称</td><td colspan="5" class="orderContent">'+data.name+'</td></tr><tr><td class="info orderTitle">订单金额</td><td colspan="5" class="orderContent">'+data.money+'元</td></tr><tr><td class="info orderTitle">购买时间</td><td colspan="5">'+data.date+'</td></tr><tr><td class="info orderTitle">下单信息</td><td colspan="5" class="orderContent">'+data.inputs+'</td><tr><td class="info orderTitle">订单状态</td><td colspan="5" class="orderContent">'+status[data.status]+'</td></tr>';
				if(data.complain){
					item += '<tr><td class="info orderTitle">订单操作</td><td class="orderContent"><a href="./user/workorder.php?my=add&orderid='+id+'&skey='+skey+'" target="_blank" onclick="return checklogin('+data.islogin+')" class="btn btn-xs btn-default">投诉订单</a></td></tr>';
				}
				if(data.list && typeof data.list === "object"){
					if(typeof data.list.order_state !== "undefined" && data.list.order_state && typeof data.list.now_num !== "undefined"){
						item += '<tr><td colspan="6" style="text-align:center" class="orderTitle"><b>订单实时状态</b></td><tr><td class="warning">下单数量</td><td>'+data.list.num+'</td><td class="warning">下单时间</td><td colspan="3">'+data.list.add_time+'</td></tr><tr><td class="warning">初始数量</td><td>'+data.list.start_num+'</td><td class="warning">当前数量</td><td>'+data.list.now_num+'</td><td class="warning">订单状态</td><td><font color=blue>'+data.list.order_state+'</font></td></tr>';
						if(typeof data.list.result !== "undefined" && data.list.result){
							item += '<tr><td class="warning orderTitle">异常信息</td><td class="orderContent">'+data.list.result+'</td></tr>';
						}
					}else{
						item += '<tr><td colspan="6" style="text-align:center" class="orderTitle"><b>订单实时状态</b></td>';
						$.each(data.list, function(i, v){
							item += '<tr><td class="warning orderTitle">'+i+'</td><td class="orderContent">'+v+'</td></tr>';
						});
					}
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
				  zIndex: 2001,
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
