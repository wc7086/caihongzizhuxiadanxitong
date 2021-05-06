<?php
if (!defined('IN_CRONLITE')) die();
$qqlink = 'https://wpa.qq.com/msgrd?v=3&uin='.$conf['kfqq'].'&site=qq&menu=yes';
if($is_fenzhan && !empty($conf['kfwx']) && file_exists(ROOT.'assets/img/qrcode/wxqrcode_'.$siterow['zid'].'.png')){
	$qrcodeimg = './assets/img/qrcode/wxqrcode_'.$siterow['zid'].'.png';
	$qrcodename = '微信';
}elseif(!empty($conf['kfwx']) && file_exists(ROOT.'assets/img/wxqrcode.png')){
	$qrcodeimg = './assets/img/wxqrcode.png';
	$qrcodename = '微信';
}else{
	$qrcodeimg = '//api.qrserver.com/v1/create-qr-code/?size=250x250&margin=10&data='.urlencode($qqlink);
	$qrcodename = 'QQ';
}
?>
<!DOCTYPE html>
<html lang="zh" style="font-size: 20px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,user-scalable=no">
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-param" content="_csrf">
    <title><?php echo $conf['sitename'] .($conf['title']==''?'':' - '.$conf['title']) ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <!-- Vendor styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
	<?php echo str_replace('body','html',$background_css)?>
</head>

<style>
    .fix-iphonex-bottom {
        padding-bottom: 34px;
    }
    .custormer-page {
        background: #f3f3f3;
    }

    .custormer-page .fixed {
        position: fixed;
        width: 15rem;
        height: 20rem;
        top: 5%;
        /*margin-top: -11rem;*/
        left: 50%;
        margin-left: -7.5rem;
        /*background: #000;*/
    }

    .custormer-page .box {
        width: 15rem;
        /*height: 17rem;*/
        background: #fff;
        border-radius: 0.4rem;
        text-align: center;
        overflow: hidden;
    }

    .custormer-page .box p {
        line-height: 2rem;
        margin-top: 1rem;
        font-weight: bold;
        font-size: 0.8rem;
    }

    .custormer-page .box img {
        width: 13rem;
        height: 13rem;
    }

    .custormer-text {
        color: #969696;
        line-height: 2rem;
        font-size: 0.65rem;
        font-weight: bold;
    }

    .complaint {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        color: #2d8cf0;
        width: 100%;
        height: 2.5rem;
        line-height: 2.5rem;
        justify-content: center;
        background: white;
        border-radius: 6px;
        margin-top: 0.5rem;
    }

    .complaint img {
        width: 1.5rem;
        margin-right: 0.2rem;
    }
</style>
<body style="margin: auto;    max-width: 650px;">
<div id="body">
    <div class="custormer-page">
        <div class="fixed" style="position: absolute">
            <div class="box">
                <br><br>
                <span style="font-weight: bold;font-size: 0.8rem;">客服ＱＱ：<?php echo $conf['kfqq'] ?> <a href="<?php echo $qqlink ?>" target="_blank">[添加]</a></span><br/><br/>
				<?php if(!empty($conf['kfwx'])){?>
				<span style="font-weight: bold;font-size: 0.8rem;">客服微信：<?php echo $conf['kfwx']; ?> <a href="javascript:;" class="wx_hao" data-clipboard-text="<?php echo $conf['kfwx']; ?>">[复制]</a></span><br/><br/>
				<?php }?>
                <img style="box-shadow: 3px 3px 16px #eee" src="<?php echo $qrcodeimg ?>">
            </div>

            <div>
                <div class="complaint">
                    打开<?php echo $qrcodename?>扫一扫添加客服
                </div>
            </div>
        </div>

    </div>

</div>
<div class="fui-navbar" style="bottom:-34px;background-color: white;max-width: 650px">
</div>
<div class="fui-navbar" style="max-width: 650px;z-index: 100;">
    <a href="./" class="nav-item  "> <span class="icon icon-home"></span> <span class="label">首页</span>
    </a>
    <a href="./?mod=query" class="nav-item "> <span class="icon icon-dingdan1"></span> <span class="label">订单</span> </a>
	<a href="./?mod=cart" class="nav-item " <?php if($conf['shoppingcart']==0){?>style="display:none"<?php }?>> <span class="icon icon-cart2"></span> <span class="label">购物车</span> </a>
    <a href="?mod=kf" class="nav-item active"> <span class=" icon icon-service1"></span> <span class="label">客服</span>
    </a>
    <a href="./user/" class="nav-item "> <span class="icon icon-person2"></span> <span class="label">会员中心</span> </a>
</div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
    var clipboard = new Clipboard('.wx_hao');
	clipboard.on('success', function (e) {
        layer.msg('复制成功');
    });
    clipboard.on('error', function (e) {
        layer.msg('复制失败');
    });
</script>
</body>
</html>