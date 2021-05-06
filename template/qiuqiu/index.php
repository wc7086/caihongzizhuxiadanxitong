<?php
if(!defined('IN_CRONLITE'))exit();

$rs=$DB->query("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$select='<option value="0">请选择分类</option>';
$shua_class=array();
while($res = $rs->fetch()){
	$shua_class[$res['cid']]=$res['name'];
	$select.='<option value="'.$res['cid'].'">'.$res['name'].'</option>';
}
if(count($shua_class)==0)$classhide = true;
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
  <title><?php echo $hometitle?></title>
  <meta name="keywords" content="<?php echo $conf['keywords']?>">
  <meta name="description" content="<?php echo $conf['description']?>">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link href="<?php echo $cdnserver?>assets/qiuqiu/css/style.css" rel="stylesheet" type="text/css">
  <link href="<?php echo $cdnserver?>assets/qiuqiu/css/shop.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>assets/qiuqiu/css/shop_style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>assets/qiuqiu/css/my_style.css">
  <link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css?ver=<?php echo VERSION ?>">
  <script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
  <script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
  <script src="<?php echo $cdnserver?>assets/qiuqiu/js/function.js"></script>
</head>
<div id="bob">
<div id="hd">
	<div class="head">
<style>
img.logo{width:14px;height:14px;margin:0 5px 0 3px;}
body{
background:#ecedf0 url("assets/img/bj.png") fixed;
background-repeat:repeat;}
.onclick{cursor: pointer;touch-action: manipulation;}
</style>

	 <div class="s3"><?php echo $conf['sitename']?></div>
		<div class="s2"><img src="assets/qiuqiu/images/y1.png" /></div>
		<div class="s1"><img src="assets/qiuqiu/images/hj.png" /></div>
	</div>
	<div class="nav">
		<ul>

			<li class="ks-active"><span class="nav-txt" onclick='location.href="./"'>首页</span></li>
			<li><s class="wao">送龙蛋</s><span class="nav-txt" onclick='location.href="./?mod=tool"' style="color:#ff585d;">秒点</span></li>
			<li><span class="nav-txt" onclick='location.href="./?mod=pattern";'>字体</span></li>
			<li><span class="nav-txt" onclick='location.href="./?mod=about";'>关于</span></li>
	</div>
</div>
	<div id="bd">
<div class="ct" style="padding:0;">

<body>
<!--查单说明开始-->
<div class="modal fade" align="left" id="cxsm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">查询内容是什么？该输入什么？</h4>
      </div>
      	<li class="list-group-item"><font color="red">请在右侧的输入框内输入您下单时，在第一个输入框内填写的信息</font></li>
      	<li class="list-group-item">例如您购买的是QQ赞类商品，输入下单的QQ账号即可查询订单</li>
      	<li class="list-group-item">例如您购买的是邮箱类商品，需要输入您的邮箱号，输入QQ号是查询不到的</li>
      	<li class="list-group-item">例如您购买的是短视频类商品，输入视频链接即可查询，不要带其他中文字符</li>
      	<li class="list-group-item"><font color="red">如果您不知道下单账号是什么，可以不填写，直接点击查询，则会根据浏览器缓存查询</font></li>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>
<!--查单说明结束-->

<section class="index" style="margin-top:1pc;">
<div id="bds">
	<div id="content">
	
	<div class="mall" id="contentss">
	
 <div class="alert-danger"><center>&nbsp搭建和本站一模一样的网站！<a href="./?mod=fzjs" class="btn btn-info btn-xs">了解分站</a> <a target="_blank" href="./user/regsite.php" class="btn btn-danger btn-xs">  点击搭建</a></center></div>

		
	<ul class="nav nav-tabs">
		<li class="active"><a href="#onlinebuy" data-toggle="tab">在线下单</a></li><li><a href="#query" data-toggle="tab" id="tab-query">进度查询</a></li><li><a href="#lqq" data-toggle="tab" style="display:none;">拉圈圈赞</a></li><li <?php if($conf['gift_open']==0){?>class="hide"<?php }?>><a href="#gift" data-toggle="tab">抽奖</a></li><li><a href="#chat" data-toggle="tab" style="display:none;">聊天交流</a></li><li><a href="#admin" data-toggle="tab" style="color:#FF0000" >站长后台</a></li>

	</ul>
	
	<div class="list-group-item">

		<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="onlinebuy">
		<?php echo $conf['alert']?>
			<div class="form-group" id="display_selectclass">
				<div class="input-group"><div class="input-group-addon">选择分类</div>
				<select name="tid" id="cid" class="form-control"><?php echo $select?></select>
				<div class="input-group-addon"><span class="glyphicon glyphicon-search onclick" title="搜索商品" id="showSearchBar"></span></div>
			</div></div>
			<div class="form-group" id="display_searchBar" style="display:none;">
				<div class="input-group"><div class="input-group-addon"><span class="glyphicon glyphicon-remove onclick" title="关闭" id="closeSearchBar"></span></div>
				<input type="text" id="searchkw" class="form-control" placeholder="搜索商品" onkeydown="if(event.keyCode==13){$('#doSearch').click()}"/>
				<div class="input-group-addon"><span class="glyphicon glyphicon-search onclick" title="搜索" id="doSearch"></span></div>
			</div></div>
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon">选择商品</div>
				<select name="tid" id="tid" class="form-control" onchange="getPoint();"><option value="0">请选择商品</option></select>
			</div></div>
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon">商品价格</div>
				<input type="text" name="need" id="need" class="form-control" disabled/>
			</div></div>
			<div class="form-group" id="display_left" style="display:none;">
				<div class="input-group"><div class="input-group-addon">库存数量</div>
				<input type="text" name="leftcount" id="leftcount" class="form-control" disabled/>
			</div></div>
			<div class="form-group" id="display_num" style="display:none;">
				<div class="input-group"><div class="input-group-addon">数量</div>
				<div class="input-group-addon"><input id="num_min" type="button" value="-"/></div>
				<div class="input-group-addon"><input id="num" name="num" class="form-control" type="number" min="1" value="1"/></div>
				<div class="input-group-addon"><input id="num_add" type="button" value="+"/></div>
			</div></div>
			<div id="inputsname"></div>
            <div id="alert_frame" class="alert alert-warning" style="display:none;"></div>
						<div style="color:blue;margin-top:10px;float:right; text-decoration: underline;" onclick="qqhao()">什么是球球号？</div>
			<div id="qqhao" style="display:none;"><img src="assets/qiuqiu/images/qqhao.png" style="width:100%;" /></div>
			<div style="clear:both;"></div>
			
			<?php if($conf['shoppingcart']==1){?>
			<div class="btn-group btn-group-justified form-group">
			    <a class="btn btn-block btn-success" type="button" id="submit_cart_shop">加入购物车</a>
				<a type="submit" id="submit_buy" class="btn btn-block btn-primary">立即购买</a>
            </div>
			<?php }else{?>
			<div class="form-group">
				<input type="submit" id="submit_buy" class="btn btn-primary btn-block" value="立即购买">
			</div>
			<?php }?>
			<div class="panel-body border-t" id="alert_cart" style="display:none;"><i class="fa fa-shopping-cart"></i>&nbsp;当前购物车已添加<b id="cart_count">0</b>个商品<a class="btn btn-xs btn-danger pull-right" href="./?mod=cart">购物车列表</a></div>

		</div>
		

		<div class="tab-pane fade in" id="gift">
			<div class="panel-body text-center">
			<div id="roll">点击下方按钮开始抽奖</div>
			<hr>
			<p>
			<a class="btn btn-info" id="start" style="display:block;">开始抽奖</a>
			<a class="btn btn-danger" id="stop" style="display:none;">停止</a>
			</p> 
			<div id="result"></div> 
			</div>
		</div>
		
<div class="tab-pane fade in" id="query">
<table class="table table-bordered">
<tbody>
<tr>
<td align="center">
  <font color="blue">
          <b>客服: </b></font><img src="assets/qiuqiu/images/auth.gif" title="正版认证"><br>
  <img src="http://q1.qlogo.cn/g?b=qq&amp;nk=<?php echo $conf['kfqq']?>&amp;s=100" alt="Avatar" width="40" height="40" style="border:1px solid #FFF;-moz-box-shadow:0 0 3px #AAA;-webkit-box-shadow:0 0 3px #AAA;border-radius: 50%;box-shadow:0 0 3px #AAA;padding:3px;margin-right: 3px;margin-left: 6px;"></font></br>
          <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $conf['kfqq']?>&site=qq&menu=yes" class="btn btn-primary btn-xs">QQ：<?php echo $conf['kfqq']?></a><br>
 </td>
</tr>
</tbody>
</table>
<div class="alert alert-info" <?php if(empty($conf['gg_search'])){?>style="display:none;"<?php }?>><?php echo $conf['gg_search']?></div>
			<div class="form-group">
				<div class="input-group">
				<div class="input-group-btn">
					<select class="form-control" id="searchtype" style="padding: 6px 0;width:90px"><option value="0">下单账号</option><option value="1">订单号</option></select>
				</div>
				<input type="text" name="qq" id="qq3" value="<?php echo $qq?>" class="form-control" placeholder="请输入要查询的内容（留空则显示最新订单）" onkeydown="if(event.keyCode==13){submit_query.click()}" required/>
				<span class="input-group-btn"><a href="#cxsm" data-toggle="modal" class="btn btn-warning"><i class="glyphicon glyphicon-exclamation-sign"></i></a></span>
			</div></div>
			<input type="submit" id="submit_query" class="btn btn-primary btn-block" value="立即查询">			<div id="result2" class="form-group text-center" style="display:none;">				<table class="table table-striped">				<tbody id="list">				</tbody>				</table>			</div>		</div>
			
		
<div class="tab-pane fade in" id="admin">
		<div class="alert alert-info">本站支持搭建分站，分站仅需8.88元，全自动自助搭建！<a target="_blank" href="./?mod=fzjs" class="btn btn-info btn-xs">了解分站</a> <a target="_blank" href="./user/regsite.php" class="btn btn-danger btn-xs">  点击搭建</a>
		</div>
 <div class="modal-body">      <form action="./user/login.php" method="post" class="form-horizontal" role="form">            <div class="input-group">              <span class="input-group-addon">管理员账号</span>              <input type="text" name="user" value="" class="form-control" placeholder="管理员账号" required="required">            </div><br>            <div class="input-group">              <span class="input-group-addon">管理员密码</span>              <input type="password" name="pass" class="form-control" placeholder="管理员密码" required="required">            </div><br>            <div class="form-group">              <div class="col-xs-12"><input type="submit" value="登陆分站管理员系统" class="btn btn-primary form-control"></div>            </div>
<input type="submit" value="登陆主站管理员系统" class="btn btn-primary form-control"></div>            </div>
		</div>
		
		<div class="tab-pane fade in" id="chat">
					</div>
		</div>
	</div>
</div>

<div class="panel">

	<ul class="nav nav-tabs">
		</ul>
		
	<div class="list-group-item">
		<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="onlinebuy">
		<p style="text-align:center">
<?php echo $conf['sitename']?>版权所有</br>
<?php echo $conf['bottom']?></p>
<?php echo $conf['footer']?>
</div>

<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
</div></div></div></div><div style="clear:both;"></div>
<script type="text/javascript">
var isModal=<?php echo empty($conf['modal'])?'false':'true';?>;
var homepage=true;
var hashsalt=<?php echo $addsalt_js?>;
</script>
<script src="assets/js/main.js?ver=<?php echo VERSION ?>"></script>
<script src="<?php echo $cdnserver?>assets/qiuqiu/js/snow.js"></script>
<script type="text/javascript">
		function qqhao(){
		layer.open({
  type: 1,
  title: false,
  closeBtn: 0,
  area: '',
  skin: 'layui-layer-nobg', //没有背景色
  shadeClose: true,
  content: $('#qqhao')
});
}
$(function(){
	if(isModal==true){
		$.Confirm(' ','<?php echo $conf["modal"]?>',1); 
	}
});
 </script>
 <?php if($conf['classblock']==1 || $conf['classblock']==2 && checkmobile()==false)include TEMPLATE_ROOT.'default/classblock.inc.php'; ?>
<div id="loading"></div>
</section>

<div class="copyright">


        </div>
</body></html>