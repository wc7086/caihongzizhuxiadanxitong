<?php
include("../includes/common.php");

if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

if($_GET['act']=='kms'){
adminpermission('faka', 2);
if(isset($_GET['tid'])){
	$tid=intval($_GET['tid']);
	$sql="tid='$tid'";
}elseif(isset($_GET['orderid'])){
	$orderid=intval($_GET['orderid']);
	$sql="orderid='$orderid'";
}elseif(isset($_GET['kid'])) {
	$kid=intval($_GET['kid']);
	$sql="kid='$kid'";
}else{
	$sql="1";
}
if(isset($_GET['use']) && $_GET['use']==1)$sql.= " and orderid!=0";
elseif(isset($_GET['use']) && $_GET['use']==0)$sql.= " and orderid=0";
if(isset($_GET['num']))$limit = " limit ".$_GET['num'];
$rs=$DB->query("SELECT * FROM pre_faka WHERE {$sql} order by kid asc{$limit}");
$data='';
while($res = $rs->fetch())
{
	$data.=($res['pw']?$res['km'].' '.$res['pw']:$res['km'])."\r\n";
	if($_GET['isuse']==1&&$_GET['use']==0)$DB->exec("update `pre_faka` set orderid=1,usetime=NOW() where `kid`='{$res['kid']}'");
}

}else{
adminpermission('order', 2);
$tid=intval($_GET['tid']);
$cid=intval($_GET['cid']);
$status=intval($_GET['status']);
$sign=intval($_GET['sign']);
$orderby=($_GET['orderby']==1)?"desc":"asc";

if($tid>0){
	$tool=$DB->getRow("SELECT * FROM pre_tools WHERE tid='$tid' limit 1");
	$values[$tid]=$tool['value']>0?$tool['value']:1;
	$sql="tid='$tid'";
}else{
	$rs=$DB->query("SELECT tid,value FROM pre_tools WHERE cid='$cid'");
	$tids='';
	while($res = $rs->fetch())
	{
		$values[$res['tid']]=$res['value']>0?$res['value']:1;
		$tids.=$res['tid'].",";
	}
	if($tids){
		$tids = trim($tids,',');
		$sql="tid IN ($tids)";
	}else{
		$sql="1";
	}
}
if(!empty($_GET['starttime']))$sql.=" AND addtime>='{$_GET['starttime']} 00:00:00'";
if(!empty($_GET['endtime']))$sql.=" AND addtime<='{$_GET['endtime']} 23:59:59'";


$date=date("Y-m-d");
$data='';

$rs=$DB->query("SELECT * FROM pre_orders WHERE {$sql} and status={$status} order by id {$orderby} limit 1000");

while($row = $rs->fetch())
{
	$data.=$row['input'] . ($row['input2']?'----'.$row['input2']:null) . ($row['input3']?'----'.$row['input3']:null) . ($row['input4']?'----'.$row['input4']:null) . ($row['input5']?'----'.$row['input5']:null) . '----' . $row['value']*$values[$row['tid']]."\r\n";
	if($sign>0)$DB->exec("update `pre_orders` set status={$sign} where `id`='{$row['id']}'");
}
}

$file_name='output_'.$tid.'_'.$date.'__'.time().'.txt';
$file_size=strlen($data);
header("Content-Description: File Transfer");
header("Content-Type:application/force-download");
header("Content-Length: {$file_size}");
header("Content-Disposition:attachment; filename={$file_name}");
echo $data;
?>