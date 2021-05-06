<?php
if(!defined('IN_CRONLITE'))exit();
if($_GET['buyok']==1){include_once TEMPLATE_ROOT.'layui/query.php';exit;}

$classhide = explode(',',$siterow['class']);
$rs=$DB->query("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$select='<option value="0">请选择分类</option>';
while($res = $rs->fetch()){
	if($is_fenzhan && in_array($res['cid'], $classhide))continue;
	$select.='<option value="'.$res['cid'].'">'.$res['name'].'</option>';
}
?>
<!DOCTYPE html>
<html lang="zh-cn"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no,user-scalable=0">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<title><?php echo $hometitle?></title>
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
<div class="modal fade" align="left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary-dark">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $conf['sitename']?></h4>
      </div>
      <div class="modal-body">
	  <?php echo $conf['modal']?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">知道了</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="shoppost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary-dark">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="myModalLabel"><font color="#000000">下单须知</font></h4>
            </div>
            <div class="modal-body"><div id="shopad"></div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
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
<div class="panel-body text-center">
<img src="<?php echo $logo?>" style="max-width: 100%;">
</div>
</div>
<div class="panel layui-anim layui-anim-scaleSpring">
<div class="panel-heading text-center panel-headcolor-qq">
<font color="#fff">公告栏</font>
</div>
<div class="panel-body">
<?php echo $conf['anounce']?> 
</div>
</div>
<div class="panel layui-anim layui-anim-scaleSpring" id="display_selectclass">
<div class="panel-heading text-center panel-headcolor-pink" id="panel-heading">
<font color="#fff">24小时自助下单</font>
</div>
<center class="list-group-item" style="padding-top:0px;">
<?php echo $conf['alert']?><br/>
<div class="btn-group btn-group-justified">
<a class="layui-btn layui-btn-normal" href="./?mod=query"><i class="layui-icon layui-icon-search"></i> 订单查询</a>
<a class="layui-btn layui-btn-danger" href="./user/regsite.php"><i class="layui-icon layui-icon-website"></i> 注册分站/赚钱</a>
</div>
</center>
	<div class="panel-body list-group-item">
	<div class="layui-form-item layui-form layui-form-pane" id="form-cid">
	<div class="layui-form-item">
	<label class="layui-form-label">业务下单</label>
	<div class="layui-input-block">
	<select name="cid" id="cid" class=""><?php echo $select?></select>
	</div></div>
	</div>
	<div id="shoplist">
			
	</div>
	</div>
</div>
	<div class="form-group" id="shopinfo" style="display:none;">
			<div class="panel panel-info">
				<div class="panel-heading text-center panel-headcolor-pink" id="panel-heading">
					<font color="#fff"><div id="selected"></div></font>
				</div>
			<div class="panel-body">
			<div id="infoshop"></div>
			<hr class="layui-bg-green">
			<div class="layui-form layui-form-pane" id="display_num" style="display:none;">
				<div class="layui-form-item">
				<label class="layui-form-label">下单份数</label>
				<div class="layui-input-block">
				<div class="input-group">
				<div class="input-group-addon" id="num_min" style="background-color: #FBFBFB;border-radius: 2px 0 0 2px;cursor: pointer;">-</div>
				<input id="num" name="num" class="layui-input" type="number" min="1" value="1"/>
				<div class="input-group-addon" id="num_add" style="background-color: #FBFBFB;border-radius: 2px 0 0 2px;cursor: pointer;">+</div>
				</div>
				</div>
				</div>
			</div>
			<div class="layui-form layui-form-pane">
				<div class="layui-form-item">
				<label class="layui-form-label">所需金额</label>
				<div class="layui-input-block">
				<input type="text" name="need" id="need" class="layui-input" disabled/>
				</div>
				</div>
			</div>
			<div class="layui-form layui-form-pane" id="display_left" style="display:none;">
				<div class="layui-form-item">
				<label class="layui-form-label">库存数量</label>
				<div class="layui-input-block">
				<input type="text" name="leftcount" id="leftcount" class="layui-input" disabled/>
				</div>
				</div>
			</div>
			<div id="inputsname"></div>
			<?php if($conf['shoppingcart']==1){?>
			<div class="row" style="margin:0">
			<div class="col-md-3 col-xs-3 col-sm-3" style="padding:0px;">
			    <a class="layui-btn btn-block" type="button" id="submit_cart_shop">加入购物车</a>
			</div>
			<div class="col-md-9 col-xs-9 col-sm-9" style="padding:0px;">
				<a type="submit" id="submit_buy" class="layui-btn layui-btn-normal btn-block">立即购买</a>
			</div>
            </div>
			<?php }else{?>
			<input type="submit" id="submit_buy" class="layui-btn layui-btn-normal btn-block" value="立即购买">
			<?php }?>
			</div>
			</div>
		</div>
<!-- 抽奖弹框开始 -->
<div class="modal fade" id="choujiang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary-dark">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">平台抽奖</h4>
            </div>
            <div class="modal-body">
			<div class="panel-body text-center">
			<div id="roll">点击下方按钮开始抽奖</div>
			<hr>
			<p>
			<a class="btn btn-info" id="start" style="display:block;">开始抽奖</a>
			<a class="btn btn-danger" id="stop" style="display:none;">停止</a>
			</p> 
			<div id="result"></div><br/>
			<div class="giftlist" style="display:none;"><strong>最近中奖记录</strong><ul id="pst_1"></ul></div>
			</div>
			</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>

<?php if($conf['articlenum']>0){
$limit = intval($conf['articlenum']);
$rs=$DB->query("SELECT id,title FROM pre_article WHERE active=1 ORDER BY top DESC,id DESC LIMIT {$limit}");
$msgrow=array();
while($res = $rs->fetch()){
	$msgrow[]=$res;
}
$class_arr = ['danger','warning','primary','success','info'];
$i=0;
?>
<!--文章列表-->
<div class="panel layui-anim" <?php if($conf['hide_tongji']==1){?>style="display:none;"<?php }?>>
<div class="panel-heading text-center panel-headcolor-qq"><h3 class="panel-title">文章列表</h3></div>
<div class="block block-themed" style="box-shadow:0 5px 10px 0 rgba(0, 0, 0, 0.25);">
	<?php foreach($msgrow as $row){
	echo '<a target="_blank" class="list-group-item" href="'.article_url($row['id']).'"><span class="btn btn-'.$class_arr[($i++)%5].' btn-xs">'.$i.'</span>&nbsp;'.$row['title'].'</a>';
	}?>
	<a href="<?php echo article_url()?>" title="查看全部文章" class="btn-default btn btn-block" target="_blank">查看全部文章</a>
</div>
</div>
<!--文章列表-->
<?php }?>

<!-- 抽奖结束 -->
<div class="panel layui-anim" <?php if($conf['hide_tongji']==1){?>style="display:none;"<?php }?>>
<div class="panel-heading text-center panel-headcolor-qq"><h3 class="panel-title">本站运行数据</h3></div>
<table class="table table-bordered" style="background-color:#fff;border: 1px solid #ddd;">
<tbody>
<tr>
<td align="center"><font size="2"><span id="count_yxts"></span>天<br><font color="#65b1c9"><i class="layui-icon layui-icon-date" style="font-size: 22px; color: #1E9FFF;"></i></font><br>本站运营天数</font></td>
<td align="center"><font size="2"><span id="count_orders"></span>条<br><font color="#65b1c9"><i class="layui-icon layui-icon-upload" style="font-size: 22px; color: #1E9FFF;"></i></font><br>本站订单数量</font></td>
<td align="center"><font size="2"><span id="count_orders1"></span>条<br><font color="#65b1c9"><i class="layui-icon layui-icon-vercode" style="font-size: 22px; color: #1E9FFF;"></i></font><br>已处理订单数</font></td>
<td align="center"><font size="2"><span id="count_money"></span>元<br><font color="#65b1c9"><i class="layui-icon layui-icon-rmb" style="font-size: 22px; color: #1E9FFF;"></i></font><br>累计交易金额</font></td>
</tr>
</tbody>
</table>
</div>

<div class="panel layui-anim" <?php if($conf['bottom']=='' && $conf['footer']==''){?>style="display:none;"<?php }?>>
<div class="panel-heading text-center panel-headcolor-qq"><h3 class="panel-title">站点助手</h3></div>
<div class="panel-body">
<?php echo $conf['bottom']?> <?php echo $conf['footer']?>
</div>
</div>


<ul class="layui-fixbar" id="alert_cart" style="display:none;">
  <a href="./?mod=cart"><li class="layui-icon layui-icon-cart" style="background-color:#3e4425db"><div class="nav-counter" id="cart_count"></div></li></a>
</ul>

</div>

<script src="<?php echo $cdnserver?>assets/js/layui.all.js"></script>
<script type="text/javascript">
var isModal=<?php echo empty($conf['modal'])?'false':'true';?>;
var homepage=true;
var hashsalt=<?php echo $addsalt_js?>;
</script>
<script src="assets/js/HotLove.js?ver=<?php echo VERSION ?>"></script>
</div>
</div>
</div>
<!--音乐代码-->
<div id="audio-play" <?php if(empty($conf['musicurl'])){?>style="display:none;"<?php }?>>
  <div id="audio-btn" class="on" onclick="audio_init.changeClass(this,'media')">
    <audio loop="loop" src="<?php echo $conf['musicurl']?>" id="media" preload="preload"></audio>
  </div>
</div>
<!--音乐代码-->
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
</body></html>