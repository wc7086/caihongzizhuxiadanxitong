<?php
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

if(!checkRefererHost())exit('{"code":403}');

switch($act){
case 'addClass':
	adminpermission('shop', 2);
	$name=trim($_POST['name']);
	if($name==null)
		exit('{"code":-1,"msg":"分类名不能为空"}');
	$rows=$DB->getRow("select * from pre_class where name='$name' limit 1");
	if($rows)
		exit('{"code":-1,"msg":"当前分类名称已存在"}');
	$sort = $DB->getColumn("select sort from pre_class order by sort desc limit 1");
	$sql="insert into `pre_class` (`name`,`sort`,`active`) values ('".$name."','".($sort+1)."','1')";
	if($DB->exec($sql)!==false){
		$cid=$DB->lastInsertId();
		exit('{"code":0,"msg":"添加分类成功！"}');
	}else
		exit('{"code":-1,"msg":"添加分类失败！'.$DB->error().'"}');
break;
case 'editClass':
	adminpermission('shop', 2);
	$cid=intval($_GET['cid']);
	$rows=$DB->getRow("select * from pre_class where cid='$cid' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"分类不存在"}');
	$name=$_POST['name'];
	if($name==null)
		exit('{"code":-1,"msg":"分类名不能为空"}');
	if($DB->exec("update pre_class set name='$name' where cid='{$cid}'")!==false)
		exit('{"code":0,"msg":"修改分类成功！"}');
	else
		exit('{"code":-1,"msg":"修改分类失败！'.$DB->error().'"}');
break;
case 'delClass':
	adminpermission('shop', 2);
	$cid=intval($_GET['cid']);
	$DB->exec("DELETE FROM pre_tools WHERE cid='$cid'");
	$sql="DELETE FROM pre_class WHERE cid='$cid'";
	if($DB->exec($sql)!==false)
		exit('{"code":0,"msg":"删除分类成功！"}');
	else
		exit('{"code":-1,"msg":"删除分类失败！'.$DB->error().'"}');
break;
case 'editClassAll':
	adminpermission('shop', 2);
	foreach($_POST['name'] as $cid=>$name){
		if(isset($_POST['sort'][$cid]) && $_POST['sort'][$cid]>0){
			$sort = intval($_POST['sort'][$cid]);
			$DB->exec("update pre_class set name='$name',sort='$sort' where cid='{$cid}'");
		}else{
			$DB->exec("update pre_class set name='$name' where cid='{$cid}'");
		}
	}
	exit('{"code":0,"msg":"修改分类成功！"}');
break;
case 'editClassImages':
	adminpermission('shop', 2);
	foreach($_POST['img'] as $k=>$v){
		$DB->exec("update pre_class set shopimg='$v' where cid='{$k}'");
	}
	exit('{"code":0,"msg":"修改分类成功！"}');
break;
case 'getClassImage':
	$cid=intval($_GET['cid']);
	$rows=$DB->getRow("select shopimg from pre_tools where cid='$cid' and shopimg is not null limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"分类不存在"}');
	exit('{"code":0,"msg":"succ","url":"'.$rows['shopimg'].'"}');
break;

case 'setBlock':
	adminpermission('shop', 2);
	$cid=intval($_POST['cid']);
	$data=trim($_POST['data']);
	if($DB->exec("update pre_class set block='$data' where cid='{$cid}'")!==false)
		exit('{"code":0,"msg":"设置成功"}');
	else
		exit('{"code":-1,"msg":"设置失败！'.$DB->error().'"}');
break;
case 'getBlock':
	$cid=intval($_POST['cid']);
	$rows=$DB->getRow("select * from pre_class where cid='$cid' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前分类不存在！"}');
	$result=array("code"=>0,"msg"=>"succ","data"=>$rows['block']);
	exit(json_encode($result));
break;
case 'setBlockPay':
	adminpermission('shop', 2);
	$cid=intval($_POST['cid']);
	$paytype=implode(',',$_POST['paytype']);
	if($DB->exec("update pre_class set blockpay='$paytype' where cid='{$cid}'")!==false)
		exit('{"code":0,"msg":"设置成功"}');
	else
		exit('{"code":-1,"msg":"设置失败！'.$DB->error().'"}');
break;
case 'getBlockPay':
	$cid=intval($_POST['cid']);
	$rows=$DB->getRow("select * from pre_class where cid='$cid' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前分类不存在！"}');
	$result=array("code"=>0,"msg"=>"succ","data"=>explode(',',$rows['blockpay']));
	exit(json_encode($result));
break;
case 'setClass': //分类上下架
	adminpermission('shop', 2);
	$cid=intval($_GET['cid']);
	$active=intval($_GET['active']);
	$DB->exec("update pre_class set active='$active' where cid='{$cid}'");
	exit('{"code":0,"msg":"succ"}');
break;
case 'setClassSort': //排序操作
	adminpermission('shop', 2);
	$cid=intval($_GET['cid']);
	$sort=intval($_GET['sort']);
	if(setClassSort($cid,$sort)){
		exit('{"code":0,"msg":"succ"}');
	}else{
		exit('{"code":-1,"msg":"失败"}');
	}
break;

default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}