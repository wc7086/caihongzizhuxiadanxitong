<?php
$install = true;
require_once('../includes/common.php');
@header('Content-Type: text/html; charset=UTF-8');
if($conf['version']<2008){
	exit('网站程序版本太旧，不支持直接升级');
}elseif($conf['version']<2017){
	$sqls = file_get_contents('update13.sql');
	$version = 2017;
}elseif($conf['version']<2031){
	if($dbconfig['dbqz']!='shua'){
		$config='<?php
/*数据库配置*/
$dbconfig=array(
	"host" => "'.$dbconfig['host'].'", //数据库服务器
	"port" => '.$dbconfig['port'].', //数据库端口
	"user" => "'.$dbconfig['user'].'", //数据库用户名
	"pwd" => "'.$dbconfig['pwd'].'", //数据库密码
	"dbname" => "'.$dbconfig['dbname'].'", //数据库名
	"dbqz" => "shua" //数据表前缀
);
?>';
		file_put_contents('../config.php',$config);
	}
	$sqls = file_get_contents('update14.sql');
	$version = 2031;
	unlink(ROOT.'user/kmlist.php');
	unlink(ROOT.'includes/cache.class.php');
	unlink(ROOT.'includes/class.dingxiang.php');
	unlink(ROOT.'includes/class.geetestlib.php');
	unlink(ROOT.'includes/price.class.php');
	unlink(ROOT.'includes/db.class.php');
	unlink(ROOT.'includes/hieroglyphy.class.php');
	unlink(ROOT.'includes/smtp.class.php');
	unlink(ROOT.'includes/template.class.php');
}elseif($conf['version']<2032){
	$sqls = file_get_contents('update15.sql');
	$version = 2032;
}elseif($conf['version']<2035){
	$sqls = file_get_contents('update16.sql');
	$version = 2035;
}elseif($conf['version']<2036){
	$sqls = file_get_contents('update17.sql');
	$sqls .= file_get_contents('update18.sql');
	$version = 2038;
}elseif($conf['version']<2038){
	$sqls = file_get_contents('update18.sql');
	$version = 2038;
}elseif($conf['version']<2043){
	$sqls = file_get_contents('update19.sql');
	$sqls .= file_get_contents('update20.sql');
	$sqls .= file_get_contents('update21.sql');
	$sqls .= file_get_contents('update22.sql');
	$version = 2051;
}elseif($conf['version']<2047){
	$sqls = file_get_contents('update20.sql');
	$sqls .= file_get_contents('update21.sql');
	$sqls .= file_get_contents('update22.sql');
	$version = 2051;
}elseif($conf['version']<2049){
	$sqls = file_get_contents('update21.sql');
	$sqls .= file_get_contents('update22.sql');
	$version = 2051;
}elseif($conf['version']<2051){
	$sqls = file_get_contents('update22.sql');
	$version = 2051;
}else{
	exit('你的网站已经升级到最新版本了');
}
$explode = explode(';', $sqls);
$num = count($explode);
foreach ($explode as $sql) {
    if ($sql = trim($sql)) {
        $DB->exec($sql);
    }
}
saveSetting('version',$version);
$CACHE->clear();
exit("<script language='javascript'>alert('网站数据库升级完成！');window.location.href='../';</script>");
?>