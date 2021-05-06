<?php
if (!defined('IN_CRONLITE')) die();
@header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8" />
  <title><?php echo $title ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>assets/store/css/iconfont.css">
  <link href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="<?php echo $cdnserver?>assets/user/css/animate.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $cdnserver?>assets/user/css/app.css" type="text/css" />
  <link type="text/css" rel="stylesheet" href="<?php echo $cdnserver?>assets/store/css/user.css"/>
  <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<?php if($islogin2==1){
if($userrow['status']==0){
	sysmsg('你的账号已被封禁！',true);exit;
}elseif($userrow['power']>0 && $conf['fenzhan_expiry']>0 && $userrow['endtime']<$date){
	sysmsg('你的账号已到期，请联系管理员续费！',true);exit;
}
}
?>
<div class="fui-page  fui-page-current" style="max-width: 650px;left: auto;">
    <div class="fui-header">
        <div class="fui-header-left">
            <a class="back" onclick="goback();"></a>
        </div>
        <div class="title"><?php echo $title; ?></div>
        <div class="fui-header-right">
            <a class="icon icon-person2 external" href="./"></a>
        </div>
    </div>
    <div class="fui-content member-page navbar" style="">