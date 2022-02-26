<?php
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

if(!checkRefererHost())exit('{"code":403}');

switch($act){

case 'delSite': //删除站点
	adminpermission('site', 2);
	$zid=intval($_GET['zid']);
	if($DB->exec("DELETE FROM pre_site WHERE zid='$zid'")!==false)
		exit('{"code":0,"msg":"succ"}');
	else
		exit('{"code":-1,"msg":"删除失败！'.$DB->error().'"}');
break;
case 'setSite': //开启关闭站点
	adminpermission('site', 2);
	$zid=intval($_GET['zid']);
	$active=intval($_GET['active']);
	$DB->exec("update pre_site set status='$active' where zid='{$zid}'");
	exit('{"code":0,"msg":"succ"}');
break;
case 'setSuper': //切换站点版本
	adminpermission('site', 2);
	$zid=intval($_GET['zid']);
	$row=$DB->getRow("select * from pre_site where zid='$zid' limit 1");
	if($row['power']==2)$power=1;
	else $power=2;
	$DB->exec("update pre_site set power='$power' where zid='{$zid}'");
	exit('{"code":0,"msg":"succ"}');
break;
case 'setEndtime': //分站延时
	adminpermission('site', 2);
	$zid=intval($_POST['zid']);
	$month=intval($_POST['month']);
	$row=$DB->getRow("select * from pre_site where zid='$zid' limit 1");
	if($row['endtime']>date("Y-m-d")) $endtime = date("Y-m-d", strtotime("+ {$month} months", strtotime($row['endtime'])));
	else $endtime = date("Y-m-d", strtotime("+ {$month} months"));
	$DB->exec("update pre_site set endtime='$endtime' where zid='{$zid}'");
	exit('{"code":0,"msg":"succ"}');
break;
case 'siteRecharge': //分站充值
	adminpermission('site', 2);
	$zid=intval($_POST['zid']);
	$do=intval($_POST['actdo']);
	$rmb=floatval($_POST['rmb']);
	$remark=trim($_POST['remark']);
	$row=$DB->getRow("select zid,rmb from pre_site where zid='$zid' limit 1");
	if(!$row)
		exit('{"code":-1,"msg":"当前分站不存在！"}');
	if($do==1 && $rmb>$row['rmb'])$rmb=$row['rmb'];
	if($remark)$addstr = '（'.$remark.'）';
	if($do==0){
		changeUserMoney($zid, $rmb, true, '加款', '后台加款'.$rmb.'元'.$addstr);
	}else{
		changeUserMoney($zid, $rmb, false, '扣除', '后台扣款'.$rmb.'元'.$addstr);
	}
	exit('{"code":0,"msg":"succ"}');
break;

case 'getTixian': //查看提现信息
	adminpermission('tixian', 2);
	$id=intval($_GET['id']);
	$rows=$DB->getRow("select * from pre_tixian where id='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前提现记录不存在！"}');
	$data = '<div class="form-group"><div class="input-group"><div class="input-group-addon">提现方式</div><select class="form-control" id="pay_type" default="'.$userrow['pay_type'].'"><option value="0">支付宝</option><option value="1">微信</option><option value="2">QQ钱包</option></select></div></div>';
	$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon">提现账号</div><input type="text" id="pay_account" value="'.$rows['pay_account'].'" class="form-control" required/></div></div>';
	$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon">提现姓名</div><input type="text" id="pay_name" value="'.$rows['pay_name'].'" class="form-control" required/></div></div>';
	$data .= '<input type="submit" id="save" onclick="saveInfo('.$id.')" class="btn btn-primary btn-block" value="保存">';
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'editTixian': //修改提现信息
	adminpermission('tixian', 2);
	$id=intval($_POST['id']);
	$pay_type=trim(daddslashes($_POST['pay_type']));
	$pay_account=trim(daddslashes($_POST['pay_account']));
	$pay_name=trim(daddslashes($_POST['pay_name']));
	$sds=$DB->exec("update `pre_tixian` set `pay_type`='$pay_type',`pay_account`='$pay_account',`pay_name`='$pay_name' where `id`='$id'");
	if($sds!==false)
		exit('{"code":0,"msg":"修改记录成功！"}');
	else
		exit('{"code":-1,"msg":"修改记录失败！'.$DB->error().'"}');
break;
case 'opTixian': //操作提现
	adminpermission('tixian', 2);
	$id=intval($_POST['id']);
	$op=$_POST['op'];
	if($op == 'delete'){
		$sql="DELETE FROM pre_tixian WHERE id='$id'";
		if($DB->exec($sql)!==false)
			exit('{"code":0,"msg":"删除成功！"}');
		else
			exit('{"code":-1,"msg":"删除失败！'.$DB->error().'"}');
	}elseif($op == 'complete'){
		if($DB->exec("update pre_tixian set status=1,endtime=NOW() where id='$id'")!==false)
			exit('{"code":0,"msg":"已变更为已完成状态"}');
		else
			exit('{"code":-1,"msg":"变更失败！'.$DB->error().'"}');
	}elseif($op == 'reset'){
		if($DB->exec("update pre_tixian set status=0 where id='$id'")!==false)
			exit('{"code":0,"msg":"已变更为未完成状态"}');
		else
			exit('{"code":-1,"msg":"变更失败！'.$DB->error().'"}');
	}elseif($op == 'fail'){
		$rows=$DB->getRow("select * from pre_tixian where id='$id' limit 1");
		$reason=$conf['fenzhan_skimg']==1?'提现账号错误或收款图无法识别，请修改后重新提交！':'提现账号错误，请修改后重新提交！';
		if($DB->exec("update pre_tixian set status=2,note='$reason' where id='$id'")!==false){
			changeUserMoney($rows['zid'], $rows['money'], true, '退回', '提现被退回到分站余额'.$rows['money'].'元');
			exit('{"code":0,"msg":"已成功退回到分站余额"}');
		}else
			exit('{"code":-1,"msg":"退回失败！'.$DB->error().'"}');
	}
break;
case 'tixian_note': //提现失败原因
	$id=intval($_POST['id']);
	$rows=$DB->getRow("select * from pre_tixian where id='$id' limit 1");
	$result=array("code"=>0,"msg"=>"succ","result"=>$rows['note']);
	exit(json_encode($result));
break;
case 'set_tixian_note': //修改提现失败原因
	adminpermission('tixian', 2);
	$id=intval($_POST['id']);
	$result=trim(daddslashes($_POST['result']));
	$sds=$DB->exec("update `pre_tixian` set `note`='$result' where `id`='$id'");
	if($sds!==false)
		exit('{"code":0,"msg":"修改成功！"}');
	else
		exit('{"code":-1,"msg":"修改失败！'.$DB->error().'"}');
break;

case 'setiprice':
	adminpermission('site', 2);
	$zid=intval($_POST['zid']);
	$tid=intval($_POST['tid']);
	$price=round(trim($_POST['iprice']),2);
	if(!is_numeric($price) || !preg_match('/^[0-9.]+$/', $price))exit('{"code":-1,"msg":"价格输入不规范"}');

	$price_obj = new \lib\Price($zid);
	if($price_obj->setiPriceInfo($tid,$price))
		exit('{"code":0,"msg":"succ"}');
	else
		exit('{"code":-1,"msg":"修改失败！'.$DB->error().'"}');
break;
case 'cleariprice':
	adminpermission('site', 2);
	$zid=intval($_POST['zid']);
	if($DB->exec("update pre_site set iprice=NULL where zid='{$zid}'")!==false)
		exit('{"code":0,"msg":"succ"}');
	else
		exit('{"code":-1,"msg":"清空失败！'.$DB->error().'"}');
break;

default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}