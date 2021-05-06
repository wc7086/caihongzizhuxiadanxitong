<?php
include("../includes/common.php");

if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

adminpermission('site', 2);

$zid=intval($_GET['zid']);

$userrow=$DB->getRow("select * from pre_site where zid='$zid' limit 1");
if(!$userrow)sysmsg('当前用户不存在！');

$session=md5($userrow['user'].$userrow['pwd'].$password_hash);
$token=authcode("{$zid}\t{$session}", 'ENCODE', SYS_KEY);
setcookie("user_token", $token, time() + 604800, '/');

exit("<script language='javascript'>window.location.href='../user/';</script>");
