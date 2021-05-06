<?php
if(!defined('IN_CRONLITE'))exit();

$userid = $islogin2?$userrow['zid']:$cookiesid;
$rs=$DB->query("SELECT a.*,b.name,b.input AS inputname,b.shopimg FROM pre_cart AS a LEFT JOIN pre_tools AS b ON a.tid=b.tid WHERE a.userid='$userid' AND a.status<=1 ORDER BY a.id ASC");
$data = array();
while($res = $rs->fetch())
{
	$data[] = $res;
}
$count = count($data);
?>
<!DOCTYPE html>
<html lang="zh-cn"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no,user-scalable=0">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<title>购物车 - <?php echo $conf['sitename']?></title>
<meta name="keywords" content="<?php echo $conf['keywords']?>">
<meta name="description" content="<?php echo $conf['description']?>">
<link href="<?php echo $cdnserver?>assets/css/bootstrap.min.css" rel="stylesheet"/>
<link href="<?php echo $cdnpublic?>layui/2.5.7/css/layui.css" rel="stylesheet"/>
<link href="<?php echo $cdnserver?>assets/css/global.css" rel="stylesheet"/>
<link href="<?php echo $cdnserver?>assets/css/common.css?ver=<?php echo VERSION ?>" rel="stylesheet"/>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<?php echo $background_css?>
</head>
<body>
<div class="page-loading" style="display: none;">
<div id="loader"></div>
<div class="loader-section section-left"></div>
<div class="loader-section section-right"></div>
</div>
<nav class="navbar navbar-default" role="navigation">
<div class="container-fluid">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
<span class="sr-only">切换导航</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="#" id="sitename"><?php echo $conf['sitename']?></a>
</div>
<div class="collapse navbar-collapse" id="example-navbar-collapse">
<ul class="nav navbar-nav navbar-right">
<li class="active" onclick="activeselect(this)"><a href="./"><i class="layui-icon layui-icon-cart"></i> 在线下单</a></li>
<li class="" onclick="activeselect(this)"><a href="./?mod=query"><i class="layui-icon layui-icon-search"></i> 订单查询</a></li>
<li class="" onclick="activeselect(this)"><a href="./user/regsite.php" pjax="no"><i class="layui-icon layui-icon-website"></i> 网站搭建</a></li>
<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="layui-icon layui-icon-rate-solid"></i> 平台福利</a>
    <ul class="dropdown-menu">
         <li <?php if($conf['iskami']==0){?>style="display:none;"<?php }?>><a data-toggle="modal" data-target="#kamibuy">卡密下单</a></li>
         <li <?php if($conf['gift_open']==0){?>class="hide"<?php }?>><a data-toggle="modal" data-target="#choujiang">福利抽奖</a></li>
         <li <?php if(empty($conf['invite_tid'])){?>class="hide"<?php }?>><a href="./?mod=invite" pjax="no" target="_blank">免费领赞</a></li>
    </ul>
</li>
<li class="" onclick="activeselect(this)">
<?php if($islogin2==1){?>
<a href="./user/" pjax="no"><i class="layui-icon layui-icon-username"></i> 用户中心</a>
<?php }else{?>
<a href="./user/login.php" pjax="no"><i class="layui-icon layui-icon-username"></i> 用户登录</a>
<?php }?>
</li>

</ul>
</div>
</div>
</nav>
<div class="container"> 
<div class="col-sm-10 center-block" style="float: none;">
<div id="pjaxmain">
<style>
.icon {
    font-size: 18px;
}
</style>
<div class="col-md-auto box">
<div class="panel layui-anim layui-anim-scaleSpring">
<div class="panel-heading text-center panel-headcolor-pink" id="panel-heading">
<a class="btn btn-success btn-xs pull-left" href="./">返回首页</a>
<font color="#fff"><i class="layui-icon layui-icon-cart"></i>&nbsp;购物车共计 <?php echo $count?> 件商品</font>
<a class="btn btn-danger btn-xs pull-right" href="javascript:emptyCart()"><i class="layui-icon layui-icon-note" style="font-size: 12px;"></i>&nbsp;清空</a>
</div>

<div class="panel-body">
<?php
foreach($data as $row){
	$inputname = $row['inputname']?$row['inputname']:'下单ＱＱ';
	$inputvalue = explode('|',$row['input'])[0];
	echo '<li class="list-group-item">
<div class="media"><span class="pull-left">
<img src="'.$row['shopimg'].'" alt="..." class="img-circle" width="50" height="50" onerror="this.src=\'assets/img/Product/noimg.png\'"></span>
<div class="pull-right layui-form layui-form-pane">
<input type="checkbox" name="checkbox" id="lists" value="'.$row['id'].'" money="'.$row['money'].'" checked title="选中">
<a class="layui-btn layui-btn-danger layui-btn-sm" href="javascript:cart_shop_del('.$row['id'].')"><i class="layui-icon layui-icon-delete"></i></a></div>
<div class="pull-left">
<div><b>'.$row['name'].'</b><br> × '.$row['num'].'&nbsp;<font color="red">[￥'.$row['money'].'元]</font></div> 
<div class="text-muted"><span onclick="cart_shop_edit('.$row['id'].')" title="点击修改数据"><font size="1">'.$inputname.'：'.$inputvalue.'</font></span></div>
</div></div></li>';
}
?>
    <li class="list-group-item">
       <font color="red" size="4"><b>共需支付：<span id="needmoney">0</span>元</b></font>
       <input class="layui-btn layui-btn-normal pull-right" type="submit" name="submit" id="submit_cart" value="立即支付购买">
       <div class="text-center" id="buy_cart_alert"></div>
    </li>
</div>


</div>

<script src="<?php echo $cdnserver?>assets/js/layui.all.js"></script>
<script type="text/javascript">
var hashsalt=<?php echo $addsalt_js?>;
layui.use('element', function(){
	var element = layui.element;
});
</script>
<script src="assets/js/cart.js?ver=<?php echo VERSION ?>"></script>
</div>
</div>
</div>
<script src="<?php echo $cdnserver?>assets/js/pjax.js"></script>
<script>
$(document).pjax('a[target!=_blank][pjax!=no]', '#pjaxmain', {fragment:'#pjaxmain', timeout:5000});
$(document).on('pjax:send', function () {
	$(".page-loading").css('display','block');
});
$(document).on('pjax:complete', function () {
	$("#example-navbar-collapse").removeClass('in').attr('aria-expanded',false);
	$(".page-loading").css('display','none');
});
</script>
<script type="text/javascript">
layui.use('element', function(){
	var element = layui.element;
});
layui.use('form', function(){
	var form = layui.form;
	form.on('checkbox', function(data){
		$("input:checkbox[name=checkbox]").change()
	});
});
</script>
</body></html>