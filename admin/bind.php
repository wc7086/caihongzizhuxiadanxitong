<?php
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$type = isset($_GET['type'])?$_GET['type']:exit;
$typename = $type=='qq'?'QQ':'微信';
?><!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
  <meta name="renderer" content="webkit"/>
  <title>扫码登录</title>
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"/>
  <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
  <!--[if lt IE 9]>
    <script src="//cdn.staticfile.org/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
      <div class="panel-body">
        <div class="list-group text-center">
          <div class="list-group-item" style="font-weight: bold;" id="login">
            <span id="loginmsg">请使用<?php echo $typename?>扫描二维码</span><span id="loginload" style="padding-left: 10px;color: #790909;">.</span>
          </div>
          <div class="list-group-item" id="qrimg" title="点击刷新二维码">
          </div>
          <div class="list-group-item" id="mobile" style="display:none;"><button type="button" id="mlogin" onclick="mloginurl()" class="btn btn-warning btn-block">跳转QQ快捷登录</button><br/><button type="button" onclick="qrlogin()" class="btn btn-success btn-block">我已完成登录</button><br/>
		  <span class="text-muted">提示：手机用户如需微信扫码，可截图保存二维码，在微信内扫一扫，从相册识别二维码。</span>
		  </div>
        </div>
	</div>
<script>var isbind = true;var bindtype = '<?php echo $type?>';var bindtypename = '<?php echo $typename?>';</script>
<script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
<script src="//cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script src="./assets/js/qrlogin.js"></script>
</body>
</html>