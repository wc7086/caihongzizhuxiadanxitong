<?php
if(!defined('IN_CRONLITE'))exit();
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
  <title><?php echo $hometitle?></title>
  <meta name="keywords" content="<?php echo $conf['keywords']?>">
  <meta name="description" content="<?php echo $conf['description']?>">
  <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/plugins.css">
  <link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/main.css">
  <link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/oneui.css"> 
  <link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css?ver=<?php echo VERSION ?>">
  <script src="<?php echo $cdnpublic?>modernizr/2.8.3/modernizr.min.js"></script>
  <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>
.shuaibi-tip {
    background: #fafafa repeating-linear-gradient(-45deg,#fff,#fff 1.125rem,transparent 1.125rem,transparent 2.25rem);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    margin: 20px 0px;
    padding: 15px;
    border-radius: 5px;
    font-size: 14px;
    color: #555555;
}
</style>
<?php echo $background_css?>
</head>
<body>
<?php if($background_image){?>
<img src="<?php echo $background_image;?>" alt="Full Background" class="full-bg full-bg-bottom animated pulse " ondragstart="return false;" oncontextmenu="return false;">
<?php }?>
<br>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-5 center-block" style="float: none;">
  <!--弹出公告--> 
<div class="modal fade" align="left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header-tabs">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $conf['sitename']?></h4>
       </div>
        <div class="modal-body">
         	<?php echo $conf['modal']?>
  	    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">知道啦</button>
      </div>
    </div>
  </div>
</div>
<!--弹出公告-->

<!--平台公告开始-->
<div class="modal fade" align="left" id="anounce" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(120deg, #31B404 0%, #D7DF01 100%);">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
        <center><h4 class="modal-title" id="myModalLabel"><b><font color="#fff"><?php echo $conf['sitename']?></font></b></h4></center>  
      </div><div class="widget flat radius-bordered"> 
        <div class="widget-header bordered-top bordered-themesecondary"> <div class="modal-body">
           <?php echo $conf['anounce']?></div></div></div>  
         <div class="modal-footer">  
        <button type="button" class="btn btn-default" data-dismiss="modal">我明白了</button>     
      </div>     
    </div>     
  </div>    
 </div>  
<!--公告-->
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
<div class="widget">
<!--logo-->
    <div class="widget-content themed-background-flat text-center" style="background-image:url(assets/simple/img/baiyun.jpg);background-size: 100% 100%;">
        <a href="javascript:void(0)">
			<img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq'] ?>&spec=100" alt="Avatar" width="80" style="height: auto filter: alpha(Opacity=80);-moz-opacity: 0.80;opacity: 0.80;" class="img-circle img-thumbnail img-thumbnail-avatar-1x animated zoomInDown">
        </a>
    </div>
<!--logo-->
   <center>
<h2>     <a href="javascript:void(alert(&#39;<?php echo $conf['sitename']?>，建议收藏到浏览器书签哦！&#39;));"><b><?php echo $conf['sitename']?></b></a></h2>
</center>
<!--logo下面按钮-->
	<div class="widget-content text-center">
		<div class="text-center text-muted">
			<div class="btn-group btn-group-justified">
				<div class="btn-group">
					<a class="btn btn-default" data-toggle="modal" href="#anounce"><i class="fa fa-bullhorn"></i>&nbsp;<span style="font-weight:bold">公告</span></a>
					</div>
				<?php if($conf['appurl']){?>
			<a href="<?php echo $conf['appurl']; ?>" target="_blank" class="btn btn-effect-ripple btn-default"><i class="fa fa-android"></i> <span style="font-weight:bold">客户端</span></a>
			<?php }else{?>
	 	<a href="#lxkf" target="_blank" data-toggle="modal" class="btn btn-default"><i class="fa fa-qq"></i>&nbsp;<span style="font-weight:bold">客服</span></a>
			<?php }?>
                <div class="btn-group">
                 <a class="btn btn-default" data-toggle="modal" href="user/login.php"><i class="fa fa-users fa-1x"></i>&nbsp;登录</a>
				</div>
             </div>
		</div>
	</div>
	
<!--logo下面按钮-->
</div>
<div class="block full2">
<!--TAB标签开始-->
	<div class="block-title">
        <ul class="nav nav-tabs" data-toggle="tabs">
            <li style="width: 25%;" align="center" class="active"><a href="#shop"><i class="fa fa-shopping-cart"></i> <b>下单</b></a></li>
            <li style="width: 25%;" align="center" class=""><a href="#search" id="tab-query"><i class="fa fa-search"></i> <b>查询</b></a></li>
			<li style="width: 25%;" align="center" <?php if($conf['fenzhan_buy']==0){?>class="hide"<?php }?>><a href="#Substation"><font color="#FF4000"><i class="fa fa-location-arrow fa-spin"></i> <b>分站</b></font></a></li>
			<li style="width: 25%;" align="center" <?php if($conf['gift_open']==0||$conf['fenzhan_buy']==1){?>class="hide"<?php }?>><a href="#gift" data-toggle="tab"><span style="font-weight:bold"><i class="fa fa-gift fa-fw"></i> 抽奖</span></a></li>
			<li style="width: 25%;" align="center" <?php if($conf['fenzhan_buy']==1||$conf['gift_open']==1){?>class="hide"<?php }?>><a href="./user/"><span style="font-weight:bold"><i class="fa fa-user fa-fw"></i> 登录</span></a></li>
            <li style="width: 25%;" align="center" class=""><a href="#more"><i class="fa fa-list"></i> <b>更多</b></a></li>
</ul>
    </div>
<!--TAB标签结束-->
    <div class="tab-content">
<!--在线下单-->
    <div class="tab-pane active" id="shop">
       <center><div class="shuaibi-tip animated tada  text-center"><i class="fa fa-heart text-danger"></i> <b>［<?=date("m月d号")?>］最新业务通知&nbsp;<a href="#anounce" data-toggle="modal" class="label label-danger"><font color="#FFFFFF">点击查看</font></a></b></div></center> 
<?php include TEMPLATE_ROOT.'default/shop.inc.php'; ?>
	</div>
<!--在线下单-->
<!--查询订单-->
    <div class="tab-pane" id="search">
              <table class="table table-striped table-borderless table-vcenter remove-margin-bottom">
         <tbody>
            <tr class="shuaibi-tip animation-bigEntrance">
                <td class="text-center" style="width: 100px;">
                    <img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq'] ?>&spec=100" alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar">
                </td>
                <td>
                    <h4><strong>站长</strong></h4>
					<i class="fa fa-fw fa-qq text-primary"></i> <?php echo $conf['kfqq'] ?><br><i class="fa fa-fw fa-history text-danger"></i>售后订单问题请联系客服
                </td>
                <td class="text-right" style="width: 20%;">
                    <a href="#lxkf" target="_blank" data-toggle="modal" class="btn btn-sm btn-info">联系</a>
                </td>
            </tr>
         </tbody>
        </table>
		<br>
		<div class="col-xs-12 well well-sm animation-pullUp" <?php if(empty($conf['gg_search'])){?>style="display:none;"<?php }?>>
			<?php echo $conf['gg_search']?>
		</div>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-btn">
						<select class="form-control" id="searchtype" style="padding: 6px 4px;width:90px"><option value="0">下单账号</option><option value="1">订单号</option></select>
					</div>
					<input type="text" name="qq" id="qq3" value="" class="form-control" placeholder="请输入要查询的内容（留空则显示最新订单）" onkeydown="if(event.keyCode==13){submit_query.click()}" required="">
					<span class="input-group-btn"><a href="#cxsm" target="_blank" data-toggle="modal" class="btn btn-warning"><i class="glyphicon glyphicon-exclamation-sign"></i></a></span>
				</div>
			</div>		
			<input type="submit" id="submit_query" class="btn btn-primary btn-block btn-rounded" style="background: linear-gradient(to right,#87CEFA,#6495ED);color:#fff;" value="立即查询">		
            <br>
			<div id="result2" class="form-group" style="display:none;">
              <center><small><font color="#ff0000">手机用户可以左右滑动</font></small></center>
				<div class="table-responsive">
					<table class="table table-vcenter table-condensed table-striped">
					<thead><tr><th>下单账号</th><th>商品名称</th><th>数量</th><th class="hidden-xs">购买时间</th><th>状态</th><th>操作</th></tr></thead>
					<tbody id="list">
					</tbody>
					</table>
				</div>
			</div>
   </div> 
<!--查询订单-->
<!--开通分站开始-->
<div class="tab-pane animation-fadeInQuick2" id="Substation">
<table class="table table-borderless table-pricing">
            <tbody>
                <tr class="active">
                    <td class="btn-effect-ripple" style="overflow: hidden; position: relative;width: 100%; height: 8em;display: block;color: white;margin: auto;background-color: lightskyblue;"><span class="btn-ripple animate" style="height: 546px; width: 546px; top: -212.8px; left: 56.4px;"></span>
                       <h3 style="width:100%;font-size: 1.6em;">
 </h3><h3 style="width:100%;font-size: 1.6em;">
                       <i class="fa fa-user-o fa-fw" style="margin-top: 0.7em;"></i><strong>入门级</strong> /<i class="fa fa-user-circle-o fa-fw"></i><strong>旗舰级</strong>
                       </h3>
                       <span style="width: 100%;text-align: center;margin-top: 0.8em;font-size: 1.1em;display: block;"><?php echo $conf['fenzhan_price']?>元 / <?php echo $conf['fenzhan_price2']?>元</span></td>
                </tr>
                <tr>
                    <td>一模一样的独立网站</td>
                </tr>
				<tr>
                    <td>站长后台和超低秘价</td>
                </tr>
              	<tr>
                    <td>余额提成满<?php echo $conf['tixian_min']; ?>元提现</td>
                </tr>
                <tr>
                    <td><strong>旗舰级可以吃下级分站提成</strong></td>
                </tr>
                <tr class="active">
                    <td>
						<a href="#userjs" data-toggle="modal" class="btn btn-effect-ripple  btn-info" style="overflow: hidden; position: relative;"><i class="fa fa-align-justify"></i><span class="btn-ripple animate" style="height: 100px; width: 100px; top: -24.8px; left: 11.05px;"></span> 版本介绍</a>
                        <a href="user/regsite.php" target="_blank" class="btn btn-effect-ripple  btn-danger" style="overflow: hidden; position: relative;"><i class="fa fa-arrow-right"></i> 马上开通</a>
                    </td>
                </tr>
            </tbody>
        </table>
    
	</div>
<!--开通分站结束-->
<!--抽奖-->
    <div class="tab-pane" id="gift">
		<div class="panel-body text-center">
		<div id="roll">点击下方按钮开始抽奖</div>
		<hr>
		<p>
		<a class="btn btn-info" id="start" style="display:block;">开始抽奖</a>
		<a class="btn btn-danger" id="stop" style="display:none;">停止</a>
		</p> 
		<div id="result"></div><br>
		<div class="giftlist" style="display:none;"><strong>最近中奖记录</strong><ul id="pst_1"></ul></div>
		</div>
	</div>
<!--抽奖-->
 <!--更多按钮开始-->
<div class="tab-pane" id="more">
    <div class="row">
		<div class="col-sm-6<?php if($conf['gift_open']==0){?> hide<?php }?>">
            <a href="#gift" data-toggle="tab" class="widget">
                <div class="widget-content themed-background-info text-right clearfix" style="color: #fff;">
                    <div class="widget-icon pull-left">
                        <i class="fa fa-gift"></i>
                    </div>
                    <h2 class="widget-heading h3">
                        <strong>抽奖</strong>
                    </h2>
                    <span>在线抽奖领取免费商品</span>
                </div>
            </a>
        </div>
		<div class="col-sm-6<?php if(empty($conf['appurl']) || $conf['gift_open']==1){?> hide<?php }?>">
            <a href="<?php echo $conf['appurl']; ?>" target="_blank" class="widget">
                <div class="widget-content themed-background-info text-right clearfix" style="color: #fff;">
                    <div class="widget-icon pull-left">
                        <i class="fa fa-cloud-download"></i>
                    </div>
                    <h2 class="widget-heading h3">
                        <strong>APP下载</strong>
                    </h2>
                    <span>下载APP，下单更方便</span>
                </div>
            </a>
        </div>
		<div class="col-sm-6<?php if(empty($conf['invite_tid'])){?> hide<?php }?>">
            <a  href="./?mod=invite" target="_blank" class="widget">
                <div class="widget-content themed-background-warning text-right clearfix" style="color: #fff;">
                    <div class="widget-icon pull-left">
                        <i class="fa fa-paper-plane-o"></i>
                    </div>
                    <h2 class="widget-heading h3">
                        <strong>免费领赞</strong>
                    </h2>
                    <span>推广本站免费领取名片赞</span>
                </div>
            </a>
        </div>
		<div class="col-sm-6<?php if(empty($conf['daiguaurl'])){?> hide<?php }?>">
            <a href="./?mod=daigua" class="widget">
                <div class="widget-content themed-background-success text-right clearfix" style="color: #fff;">
                    <div class="widget-icon pull-left">
                        <i class="fa fa-rocket"></i>
                    </div>
                    <h2 class="widget-heading h3">
                        <strong>QQ等级代挂</strong>
                    </h2>
                    <span>管理自己的QQ代挂</span>
                </div>
            </a>
        </div>
		<div class="col-sm-6">
            <a  href="./user/" target="_blank" class="widget">
                <div class="widget-content themed-background-info text-right clearfix" style="color: #fff;">
                    <div class="widget-icon pull-left">
                        <i class="fa fa-certificate"></i>
                    </div>
                    <h2 class="widget-heading h3">
                        <strong>分站后台</strong>
                    </h2>
                    <span>登录分站后台</span>
                </div>
            </a>
        </div>
	</div>
</div>
<!--更多按钮结束-->
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
<div class="block block-themed">
	<div class="block-title">
		<h4><i class="fa fa-newspaper-o"></i> 文章列表</h4>
	</div>
	<?php foreach($msgrow as $row){
	echo '<a target="_blank" class="list-group-item" href="'.article_url($row['id']).'"><span class="btn btn-'.$class_arr[($i++)%5].' btn-xs">'.$i.'</span>&nbsp;'.$row['title'].'</a>';
	}?>
	<a href="<?php echo article_url()?>" title="查看全部文章" class="btn-default btn btn-block" target="_blank">查看全部文章</a>
</div>
<!--文章列表-->
<?php }?>

<div class="panel panel-primary" <?php if($conf['hide_tongji']==1){?>style="display:none;"<?php }?>>
<div class="panel-heading"><h3 class="panel-title"><font color="#000000"><i class="fa fa-bar-chart-o"></i>&nbsp;&nbsp;<b>数据统计</b></font></h3></div>
<table class="table table-bordered">
<tbody>
<tr>
<td align="center">
<font size="2"><span id="count_yxts"></span>天<br><font color="#65b1c9"><i class="fa fa-shield fa-2x"></i></font><br>安全运营</font></td>
<td align="center"><font size="2"><span id="count_money"></span>元<br><font color="#65b1c9"><i class="fa fa-shopping-cart fa-2x"></i></font><br>交易总数</font></td>
<td align="center"><font size="2"><span id="count_orders"></span>笔<br><font color="#65b1c9"><i class="fa fa-check-square-o fa-2x"></i></font><br>订单总数</font></td>
</tr>
<tr>
<td align="center"><font size="2"><span id="count_site"></span>个<br><font color="#65b1c9"><i class="fa fa-sitemap fa-2x"></i></font><br>代理分站</font></td>
<td align="center"><font size="2"><span id="count_money1"></span>元<br><font color="#65b1c9"><i class="fa fa-pie-chart fa-2x"></i></font><br>今日交易</font></td>
<td align="center"><font size="2"><span id="count_orders2"></span>笔<br><font color="#65b1c9"><i class="fa fa-check-square fa-2x"></i></font><br>今日订单</font></td>
</tr>
</tbody>
</table>
</div>

    <!--底部导航-->
    <div class="panel panel-default">
        <center>
            <div class="panel-body"><span style="font-weight:bold"><?php echo $conf['sitename'] ?> <i class="fa fa-heart text-danger"></i> 2020 | </span> </span><a href="./"><span style="font-weight:bold"><?php echo $_SERVER['HTTP_HOST']?></span></a><br/><?php echo $conf['footer']?>
            </div>
    </div>
    <!--底部导航-->
</div>

 <!--客服介绍开始-->
<div class="modal fade col-xs-12" align="left" id="lxkf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  <br>  <br>  
  <div class="modal-dialog panel panel-primary  animation-fadeInQuick2">
    <div class="modal-content">
         <div class="list-group-item reed" style="background:linear-gradient(120deg, #5ED1D7 10%, #71D7A2 90%);">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
    <center><h4 class="modal-title" id="myModalLabel"><b><font color="#fff">客服与帮助</font></b></h4></center></div>
        <div class="modal-body" id="accordion">
                <div class="panel panel-default" style="margin-bottom: 6px;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">为什么订单显示已完成了却一直没到账？</a>
					</h4>
				</div>
				<div id="collapseOne" class="panel-collapse in" style="height: auto;">
					<div class="panel-body">
					订单显示（已完成）就证明已经提交到服务器内！并不是订单已刷完。<br>
					如果长时间没到账请联系客服处理！<br>
					订单长时间显示（待处理）请联系客服！
					</div>
				</div>
			</div>
			<div class="panel panel-default" style="margin-bottom: 6px;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed">QQ会员/钻类等什么时候到账？</a>
					</h4>
				</div>
				<div id="collapseTwo" class="panel-collapse collapse" style="height: 0px;">
					<div class="panel-body">
					下单后的48小时内到账（会员或钻全部都是一样48小时内到账）！<br>
					如果超过48小时，请联系客服退款或补单，提供QQ号码！
					</div>
				</div>
			</div>
			<div class="panel panel-default" style="margin-bottom: 6px;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed">卡密/CDK没有发送我的邮箱？</a>
					</h4>
				</div>
				<div id="collapseThree" class="panel-collapse collapse" style="height: 0px;">
					<div class="panel-body">没有收到请检查自己邮箱的垃圾箱！也可以去查单区：输入自己下单时填写的邮箱进行查单。<br>
					查询到订单后点击（详细）就可以看到自己购买的卡密/cdk！
					</div>
				</div>
			</div>
			<div class="panel panel-default" style="margin-bottom: 6px;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseFourth" class="collapsed">已付款了没有查询到我订单？</a>
					</h4>
				</div>
				<div id="collapseFourth" class="panel-collapse collapse" style="height: 0px;">
					<div class="panel-body" style="margin-bottom: 6px;">联系客服处理，请提供（付款详细记录截图）（下单商品名称）（下单账号）<br>直接把三个信息发给客服，然后等待客服回复处理（请不要发抖动窗口或者QQ电话）！
					</div>
				</div>
			</div>
			<ul class="list-group" style="margin-bottom: 0px;">
			<li class="list-group-item">   
			   <div class="media">
					<span class="pull-left thumb-sm"><img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq'] ?>&spec=100" alt="..." class="img-circle img-thumbnail img-avatar"></span>
			   <div class="pull-right push-15-t">
					<a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $conf['kfqq'] ?>&site=qq&menu=yes" target="_blank"  class="btn btn-sm btn-info">联系</a>
			   </div>
			   <div class="pull-left push-10-t">
					<div class="font-w600 push-5">订单售后客服</div>
					<div class="text-muted"><b>QQ：<?php echo $conf['kfqq'] ?></b>
					</div>
			   </div>
			   </div>
			</li>
			<li class="list-group-item">
			想要快速回答你的问题就请把问题描述讲清楚!<br>
			下单账号+业务名称+问题，直奔主题，按顺序回复!<br>
			有问题直接留言，请勿抖动语音否则直接无视。<br>			
			</li>
			</ul>
</div> 
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>			
</div>	
    </div>
  </div>
</div>
<!--联系客服结束-->

     <!--球球大作战-->
<div class="modal fade" align="left" id="qqdzz" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
   <div class="modal-dialog">
    <div class="modal-content">
         <div class="list-group-item reed" style="background:linear-gradient(120deg, #5ED1D7 10%, #71D7A2 90%);">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
    <center><h4 class="modal-title" id="myModalLabel"><b><font color="#fff">数量要求</font></b></h4></center>
      </div>	  
      <br> 
  <div class="modal-body"><center>
<p class="bg-primary" style="background-color:#424242;padding: 10px;">
球球粉丝<br>固定数量:100,200,400,800,<br>1000,2000,4000,8000,10000,20000 </p>
    <p class="bg-primary" style="background-color:#FF6666;padding: 10px;">
球球爱心<br>固定数量:1000,2000,4000,<br>8000,10000,20000,40000,80000</p></center>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">我知道了</button>
     </div>
    </div>
   </div>
  </div>
 </div>
<!--球球大作战-->

      <!--全民K歌-->
<div class="modal fade" align="left" id="qmkg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
   <div class="modal-dialog">
    <div class="modal-content">
         <div class="list-group-item reed" style="background:linear-gradient(120deg, #DF01A5 10%, #FF0080 90%);">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
    <center><h4 class="modal-title" id="myModalLabel"><b><font color="#fff">经验上限表</font></b></h4></center>
      </div>	  
  <div class="modal-body"><center>
<p class="bg-primary" style="background-color:#424242;padding: 10px;">
0-6级： 每天可获得1000点经验 </p>
    <p class="bg-primary" style="background-color:#FF6666;padding: 10px;">
7-9级： 每天可获得1500点经验</p>
    <p class="bg-primary" style="background-color:#0404B4;padding: 10px;">
10-12级：每天可获得3500点经验 </p>
<p class="bg-primary" style="background-color:#FF8000;padding: 10px;">
13-15级：每天可获得26000点经验</p>
<p class="bg-primary" style="background-color:#04B431;padding: 10px;">
16-18级：每天可获得45000点经验</p></center>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">我知道了</button>
     </div>
    </div>
   </div>
  </div>
 </div>
<!--全民K歌-->
      <!--面值-->
<div class="modal fade" align="left" id="spmz" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
   <div class="modal-dialog">
    <div class="modal-content">
         <div class="list-group-item reed" style="background:linear-gradient(120deg, #0000FF 10%, #FE2EF7 90%);">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
    <center><h4 class="modal-title" id="myModalLabel"><b><font color="#fff">选择数量</font></b></h4></center>
      </div>	  
      <br> 
      <center>  
      <img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq'] ?>&spec=100" alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar">
	  <font color="red"> 数量就是要买的多少份！</font></center>
	 <hr>  <div class="modal-body">
<p class="bg-primary" style="background-color:#424242;padding: 10px;">
例如：1000名片赞，选5份就是5000名片赞！</p>
    <p class="bg-primary" style="background-color:#FF6666;padding: 10px;">
技巧：数量可以直接输入，比如直接输100！</p>   </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">我知道了</button>
     </div>
    </div>
  </div>
 </div>
<!--面值-->
  <!--分站介绍开始-->
<div class="modal fade" align="left" id="userjs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
		         <div class="list-group-item reed" style="background:linear-gradient(120deg, #FE2EF7 10%, #71D7A2 90%);">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
    <center><h4 class="modal-title" id="myModalLabel"><b><font color="#fff">版本介绍</font></b></h4></center>
		</div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-borderless table-vcenter">
                    <thead>
                        <tr>
                            <th style="width: 100px;">功能</th>
                            <th class="text-center" style="width: 20px;">普及版/专业版</th>
                        </tr>
                    </thead>
					<tbody>
						<tr class="active">
                            <td>独立网站/专属后台</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success" style="overflow: hidden; position: relative;"><i class="fa fa-check"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success" style="overflow: hidden; position: relative;"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="">
                            <td>低价拿货/调整价格</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success" style="overflow: hidden; position: relative;"><i class="fa fa-check"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success" style="overflow: hidden; position: relative;"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="info">
                            <td>搭建分站/管理分站</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-danger" style="overflow: hidden; position: relative;"><i class="fa fa-close"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success" style="overflow: hidden; position: relative;"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="">
                            <td>超低密价/高额提成</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-danger" style="overflow: hidden; position: relative;"><i class="fa fa-close"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success" style="overflow: hidden; position: relative;"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="danger">
                            <td>赠送代刷APP</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-danger" style="overflow: hidden; position: relative;"><i class="fa fa-close"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success" style="overflow: hidden; position: relative;"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
                    </tbody>
                </table>
            </div>
          </div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
		</div>
    </div>
  </div>
</div>
<!--分站介绍结束-->
  
        <!--钻类-->
<div class="modal fade" align="left" id="zlsm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
   <div class="modal-dialog">
    <div class="modal-content">
         <div class="list-group-item reed" style="background:linear-gradient(120deg, #0000FF 10%, #FE2EF7 90%);">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
    <center><h4 class="modal-title" id="myModalLabel"><b><font color="#fff">钻类介绍</font></b></h4></center>
      </div>	  
      <br> 
  <div class="modal-body">
<p class="bg-primary" style="background-color:#04B45F;padding: 10px;">
问题：什么是质保期，理论永久是什么？</p>
 <p class="bg-primary" style="background-color:#A8904B1;padding: 10px;">
质保：理论永久，每个人用的时间都不一样，质保期就像家电的保修期一样，有问题可以联系客服处理哦！</p>     </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">我知道了</button>
     </div>
   </div>
  </div>
 </div>
<!--面值-->
  
  <!--份数说明-->
<div class="modal fade" id="tisk" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="list-group-item reed" style="background:#FFD700;">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
    <center><h4 class="modal-title" id="myModalLabel"><b><font color="#fff">下单时显示的“份数”是什么</font></b></h4></center>
     </div>  
            <br> 
      <center>
      <font color="red">下单东西的面值×份数=下单份数（份数默认为1）</font>
      <hr>
       例如您购买：100个粉丝<br>下单份数选5，就是总共会获得500个粉丝
      <hr>
       例如您购买：1000个点赞<br>下单份数选3，就是总共会获得3000个点赞
      <hr>
      <font color="red">以此类推 本站其他东西都是如此，希望给您最好的购物体验</font>
      <br> 
      </center>
         <div class="modal-footer" style="background-color: white;">
      <button type="button" class="btn btn-default" data-dismiss="modal">我知道了</button>
     </div>
        </div>
    </div>
</div>
<!--份数说明-->

<div class="modal fade" align="left" id="ptyetz" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
	<div class="list-group-item reed" style="background:linear-gradient(120deg, #FF8000 10%, #FF8000 90%);">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
    <center><h4 class="modal-title" id="myModalLabel"><b><font color="#fff">最新业务状态</font></b></h4></center>
		</div>
              <div style="overflow:scroll; overflow-x:hidden;">
				 <div class="modal-body">
                     	<?php echo $conf['anounce']?>
                    </div>
				</div>
     <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">知道了</button>
    </div>
  </div>
</div>
</div>
</center></div></div>
<!--音乐代码-->
<div id="audio-play" <?php if(empty($conf['musicurl'])){?>style="display:none;"<?php }?>>
  <div id="audio-btn" class="on" onclick="audio_init.changeClass(this,'media')">
    <audio loop="loop" src="<?php echo $conf['musicurl']?>" id="media" preload="preload"></audio>
  </div>
</div>
<!--音乐代码-->
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnserver?>assets/appui/js/app.js"></script>
<!-- DT Time -->
<script type="text/javascript">
var isModal=<?php echo empty($conf['modal'])?'false':'true';?>;
var homepage=true;
var hashsalt=<?php echo $addsalt_js?>;
$(function() {
	$("img.lazy").lazyload({effect: "fadeIn"});
});
</script>
<script src="assets/js/main.js?ver=<?php echo VERSION ?>"></script>
<?php if($conf['classblock']==1 || $conf['classblock']==2 && checkmobile()==false)include TEMPLATE_ROOT.'default/classblock.inc.php'; ?>
</body>
</html>