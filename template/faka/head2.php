<?php
if(!defined('IN_CRONLITE'))exit();
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
	<meta http-equiv="Cache-Control" content="no-transform"/>
	<title><?php echo $hometitle?></title>
	<meta name="keywords" content="<?php echo $conf['keywords']?>">
	<meta name="description" content="<?php echo $conf['description']?>">
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/faka/css/frozen.css?v=2"/>
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/faka/css/public.css?v=2" />
	<?php echo $cssadd?>
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/faka/css/baoliao.css?v=2" />
	<link href="<?php echo $cdnserver?>assets/faka/css/component.css" rel="stylesheet" type="text/css" />
</head>
<body>
<header class="headerm2">
	<a href="javascript:history.back();" class="logo"><img src="assets/faka/images/iconfont-fanhui.png"></a>
	<a href="javascript:history.back();" class="logo2"><div style="margin-top: 5px;margin-left: 25px;"><font style="font-size: 22px;color: #FFFFFF;"><?php echo $conf['sitename']?></font></div> </a>
	<?php if($islogin2==1){?><a href="./user/" class="user-icon"><span>用户中心</span></a><?php }?>
	<a href="./?mod=wapquery" class="search2"><span>订单查询</span></a>
</header>

<?php if($islogin2==1){?>
<div class="menux"><div align="center"><a><b>你好,<?php echo $userrow['user']?></b></a>&nbsp;<a>余额: <?php echo $userrow['rmb']?>元</a><a href="./user/login.php?logout" onclick="return confirm('确定要退出吗？')">【退出登录】</a></div></div>
<?php }else{?>
<div class="menux"><div align="center">【<a href="./">商品列表</a>】&nbsp;【<a href="./?mod=wapquery">订单查询</a>】&nbsp;【<a href="./user/login.php">登录</a>】&nbsp;【<a href="./user/reg.php">注册</a>】</div></div>
<?php }?>
