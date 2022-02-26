<?php
error_reporting(0);
define('IN_CRONLITE', true);
define('IN_OTHER', true);
define('CACHE_FILE', 0);
define('SYSTEM_ROOT', dirname(__FILE__).'/');
define('ROOT', dirname(SYSTEM_ROOT).'/');
date_default_timezone_set('Asia/Shanghai');
$date = date("Y-m-d H:i:s");

if (function_exists("set_time_limit"))
{
	@set_time_limit(0);
}
if (function_exists("ignore_user_abort"))
{
	@ignore_user_abort(true);
}

include_once(ROOT."includes/autoloader.php");
Autoloader::register();

require ROOT.'config.php';
//连接数据库
$DB = new \lib\PdoHelper($dbconfig);

$CACHE=new \lib\Cache();
$conf=$CACHE->pre_fetch();
if(empty($conf['version']))$conf=$CACHE->update();
define('SYS_KEY', $conf['syskey']);

include ROOT.'includes/authcode.php';
define('authcode',$authcode);
define('DIST_ID',hexdec($distid));
include ROOT.'includes/function.php';

$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = (is_https() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$sitepath.'/';
if(!isset($_SERVER['HTTP_USER_AGENT']) || !strpos($_SERVER['HTTP_USER_AGENT'],chr(46)))$_SERVER['HTTP_USER_AGENT']='Mozilla/5.0 (Windows NT 10.0) Safari/537.36';

include ROOT.'includes/core.func.php';
include ROOT.'includes/member.php';

$clientip = real_ip($conf['ip_type']?$conf['ip_type']:0);
$micropayapi = micropay_api();

function showalert($msg,$status,$orderid=null,$tid=0){
	if($tid==-1)$link = '../user/';
	elseif($tid==-2)$link = '../user/regok.php?orderid='.$orderid;
	elseif(isset($_COOKIE['user_order']) && $_COOKIE['user_order']==$orderid)$link = '../user/shop.php?buyok=1';
	else $link = '../?buyok=1';
	echo '<meta charset="utf-8"/><script>alert("'.$msg.'");window.location.href="'.$link.'";</script>';
}