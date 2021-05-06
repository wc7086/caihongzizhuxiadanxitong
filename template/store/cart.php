<?php
if (!defined('IN_CRONLITE')) die();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
    <meta name="renderer" content="webkit"/>
    <meta name="force-rendering" content="webkit"/>
    <title>购物车 - <?php echo $conf['sitename'] . ($conf['title'] == '' ? '' : ' - ' . $conf['title']) ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    <link type="text/css" rel="stylesheet" href="<?php echo $cdnserver ?>assets/store/css/cart.css"/>
</head>

<body>
<div class="headerbox">
    <div class="header">
        <div class="headerC">
            <div class="headerL">
                <a onclick="javascript:history.back(-1)" class="goback"><img src="assets/store/images/goback.png"></a>
            </div>
            <p>购物车<span class="CartCount"></span></p>
        </div>
        <div class="headerR"></div>
    </div>
</div>

<div class="clear"></div>
<div class="hbox"></div>
<div class="kbox"></div>
<div class="gwcbox">
    <div class="gwcbox_1" id="CartContent"></div>
    <div class="kbox"></div>

    <div class="hejiBox">
        <div class="heji" style="bottom: 0">
            <div class="heji_1">
                <div class="gwccheck on"></div>
            </div>
            <div class="heji_2">全选</div>
            <div class="heji_3"><p>合计：<span id="price_all">￥0</span></p></div>
            <div class="heji_5">
                <a href="javascript:GoodsCart.submit()">去结算<span class="CartCount"></span></a>
            </div>
        </div>
    </div>

    <div id="CartNull" style="display: none">
        <div class="paysuccess">
            <div class="pay30">
                <img src="assets/store/images/gwc.jpg">
                <p>购物车还是空的</p>
            </div>
            <div class="pay40">
                <a href="./">去逛逛</a>
            </div>
        </div>
    </div>
    <div class="likebox" style="padding-bottom: 8em;">
        <div class="likeTit">
            <img src="./assets/store/images/heart.png"><span>猜你喜欢</span>
        </div>
        <ul id="GoodsRound"></ul>
        <div style="text-align: center;margin-top: 1em" class="layui-text">
            <div>已经到底了</div>
            <div></div>
            <div></div>
        </div>
    </div>

<script src="<?php echo $cdnpublic ?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script type="text/javascript">
var hashsalt=<?php echo $addsalt_js?>;
</script>
<script src="assets/store/js/cart.js?ver=<?php echo VERSION ?>"></script>
<script>
	GoodsCart.CartList();
</script>
</body>
</html>