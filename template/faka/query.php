<?php
if(!defined('IN_CRONLITE'))exit();
if(checkmobile() && !$_GET['pc'] || $_GET['mobile']){include_once TEMPLATE_ROOT.'faka/wapquery.php';exit;}

function display_zt($zt){
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

if($islogin2==1){
	$cookiesid = $userrow['zid'];
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

include_once TEMPLATE_ROOT.'faka/head.php';
?>
<style type="text/css">
.query {
	padding: 25px;
	min-height: 300px;
}
.query .in {
	height: 45px;
	border: 1px solid #ff6600;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	width: 65%;
	margin-right: auto;
	margin-left: auto;
	overflow: hidden;
}
.query .in .in_l {
	float: left;
	width: 80%;
	height: 100%;
}
.query .in .in_r {
	float: right;
	height: 100%;
	width: 20%;
	
}
.query .in input {
	margin: 0px;
	border-top-width: 0px;
	border-right-width: 0px;
	border-bottom-width: 0px;
	border-left-width: 0px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	height: 100%;
	width: 100%;
	line-height: 45px;
	font-size: 18px;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 1%;
}
.query .in .in_r input {
	cursor: pointer;
	background-color: #F60;
	font-size: 20px;
	line-height: 45px;
	color: #FFF;
	font-weight: bold;
	padding: 0px;
	width: 100%;
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	border-radius: 0px;
}
#kong .query .title {
	text-align: center;
	height: 50px;
	line-height: 50px;
	margin-bottom: 15px;
}
.title2 {
	text-align: center;
	height: 25px;
	line-height: 25px;
	margin-bottom: 15px;
	font-size: 14px;
	color: #ff253a;
}
td.stitle{max-width:380px;}
td.wbreak{max-width:420px;word-break:break-all;}
#orderItem .orderTitle{word-break:keep-all;}
#orderItem .orderContent{word-break:break-all;}
.table>tbody>tr>td{padding: 5px;border-top: 1px solid #ddd;}
.table{width: 100%;max-width: 100%;margin-bottom: 20px;margin: 0;}
.table>tbody>tr>td.info{background-color: #d9edf7;}
</style>
<div class="g-body">
<br/>
<br/>
<div id="kong">
  <div class="query">
  <div class="title">请输入订单号，交易单号，手机号查询</div>
  	<form action="?" method="get"><input type="hidden" name="mod" value="query"/>
    <div class="in">
	  <div class="in_l"><input type="text" name="data" placeholder="请输入订单号或填写的联系方式查询"/></div>
      <div class="in_r"><input type="submit" value="订单查询" /></div>
    </div>
    </form><br/>
	  <div class="title2">注意：超过24小时的订单禁止查询，如果您想长期保留订单，请您注册成为我们的会员。</div>
 <br/><font size="4" color="#000000" title="这是您最近的购买记录">这是您最近的购买记录</font>
 <br/> <table width="100%" border="0" cellspacing="0" cellpadding="10">
      <thead>
        <th style="text-align:center">订单ID</th>
        <th>名称</th>
        <th style="text-align:center">数量</th>
        <th style="text-align:center">金额</th>
        <th style="text-align:center">状态</th>
		<th style="text-align:center">日期</th>
        <th style="text-align:center">操作</th>
      </thead>
	  <tbody>
<?php
if($count>0){
	foreach($record as $row){
	  echo '<tr><td>'.$row['id'].'</td><td class="stitle"><a target="_blank" href="./?mod=buy&tid='.$row['tid'].'">'.$row['name'].'</a></td><td>'.$row['value'].'</td><td>'.$row['money'].'元</td><td>'.display_zt($row['status']).'</td><td><a>'.$row['addtime'].'</a></td><td><a href="javascript:showOrder('.$row['id'].',\''.$row['skey'].'\')" title="点击查看订单详情" class="button button-primary button-rounded button-tiny">详情</a>&nbsp;'.(($row['djzt']==3||$row['djzt']==4)?'<a href="./?mod=faka&id='.$row['id'].'&skey='.$row['skey'].'" title="点击查看卡密" class="button button-highlight button-rounded button-tiny">提卡</a>':null).'</td></tr>';
	}
}else{
	echo '<td scope="col" colspan="7"><span class="empty">无记录!</span></td>';
}
?>
    </tbody></table><br/> <div  class='text-right'><ul class='pagination'>       <li><span class="rows">共<?php echo $count?>条</span> </li></ul></div></div>

</div>
<div id="footer">
    		&copy; <?php echo date("Y")?> <?php echo $conf['sitename']?>
</div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script>
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