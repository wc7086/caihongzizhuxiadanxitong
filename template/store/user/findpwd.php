<?php
if (!defined('IN_CRONLITE')) die();
@header('Content-Type: text/html; charset=UTF-8');
list($background_image, $background_css) = \lib\Template::getBackground();
?>
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no;"/>
    <title>找回密码 - <?php echo $conf['sitename'];  ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/main.css">
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css">
  <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<?php echo str_replace('body','html',$background_css)?>
<style>
body {
    width: 100%;
    max-width: 650px;
    margin: auto;
    background: #f3f3f3;
    line-height: 24px;
    font: 14px Helvetica Neue,Helvetica,PingFang SC,Tahoma,Arial,sans-serif;
}
.label{
    color: unset;
    line-height: 1.8;
}
.account-main{
    height: 100% !important;
}
a {
    text-decoration:none;
}
a:hover{
    text-decoration:none;
}
.fui-modal{z-index: 20;}
</style>
<body>
<div id="body">
    <div class="fui-page-group statusbar" style="max-width: 650px;left: auto;">
        <form id="form1">
            <div class="fui-modal popup-modal in">
                <div class="account-layer login" style="max-height:unset;margin:-13rem 0 0 -7.75rem;">
                    <div class="account-main">
                        <div class="account-back"><i class="icon icon-back"></i></div>
                        <div class="account-title">找　回　密　码</div>
                        <div class="form-group" style="text-align: center;">
                                <br/><span id="loginmsg" style="font-weight: bold;">请使用QQ手机版扫描二维码</span><span id="loginload" style="padding-left: 10px;color: #790909;">.</span><br/><br/>
                            
                            <div id="qrimg">
                            </div><br>
                            <div class="list-group-item" id="mobile" style="display:none;"><button type="button" id="mlogin" onclick="mloginurl()" class="btn btn-warning btn-block">跳转QQ快捷登录</button><br/><button type="button" onclick="qrlogin()" class="btn btn-success btn-block">我已完成登录</button></div>
                        </div>
                    </div>
                    <?php if($conf['login_qq']==1){?><center><div class="alert alert-info" style="position:unset;width:90%;">提示：只能找回注册时填写了QQ号码的帐号密码，QQ快捷登录的暂不持支该方式找回密码。</div></center><?php }?>
                    <div style="text-align:center;"><a href="javascript:goback();" class="">返回</a></div>
                    <br/>
                </div>
            </div>
        </form>    
    </div>
    <div class="fui-navbar" style="z-index: 100000;max-width: 650px;">
        <a href="../" class="nav-item  "> <span class="icon icon-home"></span> <span class="label">首页</span> </a>
        <a href="../?mod=query" class="nav-item "> <span class="icon icon-dingdan1"></span> <span class="label">订单</span> </a>
		<a href="../?mod=cart" class="nav-item " <?php if($conf['shoppingcart']==0){?>style="display:none"<?php }?>> <span class="icon icon-cart2"></span> <span class="label">购物车</span> </a>
        <a href="../?mod=kf" class="nav-item "> <span class=" icon icon-service1"></span> <span class="label">客服</span> </a>
        <a href="./" class="nav-item active"> <span class="icon icon-person2"></span> <span class="label">会员中心</span> </a>
    </div>
</div>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="../assets/js/qrlogin.js?ver=<?php echo VERSION ?>"></script>
<script>
function goback()
{
document.referrer === '' ?window.location.href = '/' :window.history.go(-1);
}
</script>
</body>
</html>