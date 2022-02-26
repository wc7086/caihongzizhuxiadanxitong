<?php
if(!defined('IN_CRONLITE'))exit();
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?php echo $hometitle?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
	<link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/oneui.css">
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css?ver=<?php echo VERSION ?>">
	<script src="<?php echo $cdnpublic?>modernizr/2.8.3/modernizr.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<?php echo $background_css?>
</head>
<body>
<?php if($background_image){?>
<img src="<?php echo $background_image;?>" alt="Full Background" class="full-bg full-bg-bottom animated pulse " ondragstart="return false;" oncontextmenu="return false;">
<?php }?>
<!--弹出公告-->
<div class="modal fade" align="left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
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
<!--公告-->
<div class="modal fade" align="left" id="mustsee" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">公告</h4>
      </div>
	  <div class="modal-body">
	  <?php echo $conf['anounce']?>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
 </div>
<!--公告-->
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-5 center-block" style="float: none;">
    <br/>
    <!--顶部导航-->
    <div class="block block-link-hover3" href="javascript:void(0)">
        <div class="block-content block-content-full text-center bg-image"
             style="background-image: url('assets/simple/img/baiyun.jpg');background-size: 100% 100%;">
            <div>
                <div>
                    <img class="img-avatar img-avatar80 img-avatar-thumb animated zoomInDown"
                         src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq'] ?>&spec=100">
                </div>
            </div>
        </div>
        <div class="block-content block-content-mini block-content-full bg-gray-lighter">
            <div class="text-center text-muted">
                <div class="btn-group">
                    <div class="btn-group">
                        <a class="btn btn-default" data-toggle="modal" href="#mustsee"><font color="#ff0000"><i class="fa fa-wifi"></i>
                            平台公告</a></font>
                    </div>
					<?php if($islogin2==1){?>
                    <div class="btn-group">
                        <a href="./user/" class="btn btn-effect-ripple btn-default"><i class="glyphicon glyphicon-user"></i> <span style="font-weight:bold">管理后台</span></a>
					</div>
					<?php }else{?>
					<div class="btn-group">
                        <a href="./user/login.php" class="btn btn-effect-ripple btn-default"><i class="glyphicon glyphicon-user"></i> <span style="font-weight:bold">登录</span></a>
					</div>
					<div class="btn-group">
                        <a href="./user/reg.php" class="btn btn-effect-ripple btn-default"><i class="glyphicon glyphicon-plus"></i> <span style="font-weight:bold">注册</span></a>
					</div>
					<?php }?>
                </div>
            </div>
        </div>
    </div>
   
    <!--顶部导航-->

    <div class="block">
        <ul class="nav nav-tabs" data-toggle="tabs">
            <li class="active" style="width: 20%;" align="center">
                <a href="#shop" data-toggle="tab"><i class="fa fa-shopping-bag fa-fw"></i> 下单</a>
            </li>
            <li style="width: 20%;" align="center">
                <a href="#search" data-toggle="tab" id="tab-query"><i class="fa fa-search"></i> 查单</a>
            </li>
            <?php if($conf['fenzhan_buy']==1){?><li style="width: 20%;" align="center" >
                <a href="#ktfz" data-toggle="tab"><i class="fa fa-coffee fa-fw"></i> 分站</a>
            </li><?php }?>
			<?php if($conf['articlenum']>0){?><li style="width: 20%;" align="center">
                <a href="#article" data-toggle="tab"><i class="fa fa-newspaper-o fa-fw"></i> 文章</a>
            </li><?php }?>
            <?php if($conf['gift_open']==1&&!$conf['articlenum']){?><li style="width: 20%;" align="center">
                <a href="#gift" data-toggle="tab"><i class="fa fa-gift fa-fw"></i> 抽奖</a>
            </li><?php }?>
            <li style="width: 20%;" align="center">
                <a href="#more" data-toggle="tab"><i class="fa fa-folder-open"></i> 更多</a>
            </li>
        </ul>
        <!--TAB-->
        <div class="block-content tab-content">
            <!--在线下单-->
            <div class="tab-pane fade fade-up in active" id="shop">
<?php include TEMPLATE_ROOT.'default/shop.inc.php'; ?>
            </div>
            <!--在线下单-->
            <!--查询订单-->
            <div class="tab-pane fade fade-up" id="search">
                <table class="table table-striped table-borderless table-vcenter remove-margin-bottom">
                    <tbody>
                    <tr class="animation-fadeInQuick2">
                        <td class="text-center" style="width: 100px;">
                            <img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq']?>&spec=100"
                                 alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar">
                        </td>
                        <td>
                             <h5><strong>订单售后客服</strong></h5>
                            <i class="fa fa-check-circle-o text-danger"></i> 客服当前<br>
                            <i class="fa fa-comment-o text-success"></i>在线中,有事直奔主题!
                        </td>
                        <td class="text-right" style="width: 20%;">
                            <a href="#customerservice" data-toggle="modal" class="btn btn-sm btn-info">联系</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="col-xs-12 well well-sm animation-pullUp"
                     <?php if (empty($conf['gg_search'])){ ?>style="display:none;"<?php } ?>><?php echo $conf['gg_search'] ?></div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-btn">
							<select class="form-control" id="searchtype" style="padding: 6px 4px;width:90px"><option value="0">下单账号</option><option value="1">订单号</option></select>
						</div>
                        <input type="text" name="qq" id="qq3" value="<?php echo $qq ?>" class="form-control"
                               placeholder="请输入要查询的内容（留空则显示最新订单）" required/>
						<span class="input-group-btn"><a tabindex="0" class="btn btn-default" role="button" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" title="查询内容是什么？" data-content="请输入您下单时，在第一个输入框内填写的信息。如果您不知道下单账号是什么，可以不填写，直接点击查询，则会根据浏览器缓存查询！"><i class="glyphicon glyphicon-exclamation-sign"></i></a></span>
                    </div>
                </div>
                <input type="submit" id="submit_query" class="btn btn-primary btn-block" value="立即查询"><br/>
                <div id="result2" class="form-group" style="display:none;">
                    <center>
                        <small><font color="#ff0000">手机用户可以左右滑动</font></small>
                    </center>
                    <div class="table-responsive">
                        <table class="table table-vcenter table-condensed table-striped">
                            <thead>
                            <tr>
                                <th>下单账号</th>
                                <th>商品名称</th>
                                <th>数量</th>
                                <th class="hidden-xs">购买时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody id="list">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--查询订单-->


            <!--开通分站-->
            <?php if($conf['fenzhan_buy']==1){?><div class="tab-pane fade fade-up" id="ktfz">
                <div class="block block-link-hover2 text-center">
                    <div class="block-content block-content-full bg-success">
                        <div class="h4 font-w700 text-white push-10"><i
                                    class="fa fa-cny fa-fw"></i><strong><?php echo $conf['fenzhan_price'] ?>元</strong> /
                            <i
                                    class="fa fa-cny fa-fw"></i><strong><?php echo $conf['fenzhan_price2'] ?>元</strong>
                        </div>
                        <div class="h5 font-w300 text-white-op">普及版 / 专业版两种分站供你选择</div>
                    </div>
                    <div class="block-content">
                        <table class="table table-borderless table-condensed">
                            <tbody>
                            <tr>
                                <td>无聊时可以赚点零花钱</td>
                            </tr>
                            <tr>
                                <td>还可以锻炼自己销售口才</td>
                            </tr>
                            <tr>
                                <td>宝妈、学生等网络赚钱首选</td>
                            </tr>
                            <tr>
                                <td>分站满<?php echo $conf['tixian_min']; ?>元即可申请提现</td>
                            </tr>
                            <tr>
                                <td><strong>轻轻松松推广日赚100+不是梦</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="block-content block-content-mini block-content-full bg-gray-lighter">
                     <a href="#userjs" data-toggle="modal" class="btn btn-success">版本介绍</a>
                    <button onclick="window.open('./user/regsite.php')" class="btn btn-danger">开通分站</button>
                    </div>
                </div>
            </div><?php }?>
            <!--开通分站-->
			<!--抽奖-->
				<div class="tab-pane fade fade-up" id="gift">
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
			<!--抽奖-->
			<!--文章列表-->
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
<div class="tab-pane fade fade-up" id="article">
	<?php foreach($msgrow as $row){
	echo '<a target="_blank" class="list-group-item" href="'.article_url($row['id']).'"><span class="btn btn-'.$class_arr[($i++)%5].' btn-xs">'.$i.'</span>&nbsp;'.$row['title'].'</a>';
	}?>
	<a href="<?php echo article_url()?>" title="查看全部文章" class="btn-default btn btn-block" target="_blank">查看全部文章</a><br/>
</div>
<?php }?>
           <!--更多-->
            <div class="tab-pane fade fade-right" id="more">
                <?php if(!empty($conf['appurl'])){?><div class="col-xs-6 col-sm-4 col-lg-4">
                    <a class="block block-link-hover2 text-center" href="<?php echo $conf['appurl']; ?>" target="_blank">
                        <div class="block-content block-content-full bg-success">
                            <i class="fa fa-cloud-download fa-3x text-white"></i>
                            <div class="font-w600 text-white-op push-15-t">APP下载</div>
                        </div>
                    </a>
                </div><?php }?>
         
                <?php if(!empty($conf['daiguaurl'])){?><div class="col-xs-6 col-sm-4 col-lg-4">
                    <a class="block block-link-hover2 text-center" href="./?mod=daigua">
                        <div class="block-content block-content-full bg-primary">
                            <i class="fa fa-rocket fa-3x text-white"></i>
                            <div class="font-w600 text-white-op push-15-t">QQ等级代挂</div>
                        </div>
                    </a>
                </div><?php }?>
				<?php if($conf['gift_open']==1){?><div class="col-xs-6 col-sm-4 col-lg-4">
                    <a class="block block-link-hover2 text-center" href="#gift" data-toggle="tab">
                        <div class="block-content block-content-full bg-info">
                            <i class="fa fa-gift fa-3x text-white"></i>
                            <div class="font-w600 text-white-op push-15-t">抽奖</div>
                        </div>
                    </a>
                </div><?php }?>
				<?php if(!empty($conf['invite_tid'])){?><div class="col-xs-6 col-sm-4 col-lg-4">
					<a class="block block-link-hover2 text-center" href="./?mod=invite" target="_blank">
						<div class="block-content block-content-full bg-warning">
						  <i class="fa fa-paper-plane-o fa-3x text-white"></i>
							<div class="font-w600 text-white-op push-15-t">免费领赞</div>
						  </div>
					</a>
				</div><?php }?>

                <div class="col-xs-6 col-sm-4 col-lg-4">
                    <a class="block block-link-hover2 text-center" href="./user/" target="_blank">
                        <div class="block-content block-content-full bg-city">
                            <i class="fa fa-certificate fa-3x text-white"></i>
                            <div class="font-w600 text-white-op push-15-t">分站后台</div>
                        </div>
                    </a>
                </div>
            </div>
            <!--更多-->
        </div>
    </div>
    <!--版本介绍-->
    <?php if($conf['fenzhan_buy']==1){?><div class="modal fade" id="userjs" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin">
            <div class="modal-content">
                <div class="block block-themed block-transparent remove-margin-b">
                    <div class="block-header bg-primary-dark">
                        <ul class="block-options">
                            <li>
                                <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                            </li>
                        </ul>
                        <h4 class="block-title">版本介绍</h4>
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
                                    <td>专属商城平台</td>
                                    <td class="text-center">
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                    </td>
                                </tr>
                                <tr class="">
                                    <td>三种在线支付接口</td>
                                    <td class="text-center">
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                    </td>
                                </tr>
                                <tr class="success">
                                    <td>专属网站域名</td>
                                    <td class="text-center">
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                    </td>
                                </tr>
                                <tr class="">
                                    <td>赚取用户提成</td>
                                    <td class="text-center">
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                    </td>
                                </tr>
                                <tr class="info">
                                    <td>赚取下级分站提成</td>
                                    <td class="text-center">
                                        <span class="btn btn-effect-ripple btn-xs btn-danger"><i
                                                    class="fa fa-close"></i></span>
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                    </td>
                                </tr>
                                <tr class="">
                                    <td>设置商品价格</td>
                                    <td class="text-center">
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                    </td>
                                </tr>
                                <tr class="warning">
                                    <td>设置下级分站商品价格</td>
                                    <td class="text-center">
                                        <span class="btn btn-effect-ripple btn-xs btn-danger"><i
                                                    class="fa fa-close"></i></span>
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                    </td>
                                </tr>
                                <tr class="">
                                    <td>搭建下级分站</td>
                                    <td class="text-center">
                                        <span class="btn btn-effect-ripple btn-xs btn-danger"><i
                                                    class="fa fa-close"></i></span>
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td>赠送专属精致APP</td>
                                    <td class="text-center">
                                        <span class="btn btn-effect-ripple btn-xs btn-danger"><i
                                                    class="fa fa-close"></i></span>
                                        <span class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-check"></i></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <center style="color: #b2b2b2;">
                            <small><em>* 自己的能力决定着你的收入！</em></small>
                        </center>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div><?php }?>
    <!--版本介绍-->
    <!--联系客服开始-->
        <div class="modal fade" id="customerservice" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="block block-themed block-transparent remove-margin-b">
                        <div class="block-header bg-primary-dark">
                            <ul class="block-options">
                                <li>
                                    <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                </li>
                            </ul>
                            <h3 class="block-title">客服与帮助</h3>
                        </div>
						<div class="panel-body" id="accordion">
                            <div class="panel panel-default" style="margin-bottom: 6px;">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">为什么订单显示已完成了却一直没到账？</a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse in" style="height: auto;">
                                    <div class="panel-body">
									订单显示（已完成）就证明已经提交到服务器内！<br>
									如果长时间没到账请联系客服处理！<br>
									订单长时间显示（待处理）请联系客服！
									</div>
                                </div>
                            </div>
                            <div class="panel panel-default" style="margin-bottom: 6px;">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed">商品什么时候到账？</a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
									请参考商品简介里面，有关于到账时间的说明。
									</div>
                                </div>
                            </div>
							<div class="panel panel-default" style="margin-bottom: 6px;">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed">卡密没有发送我的邮箱？</a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">没有收到请检查自己邮箱的垃圾箱！也可以去查单区：输入自己下单时填写的邮箱进行查单。<br>
								    查询到订单后点击（详细）就可以看到自己购买的卡密！
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
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
    <!--联系客服结束-->


<?php if($conf['hide_tongji']==0){?><div class="block block-themed">
	<div class="block-header bg-success">
		<h3 class="block-title"><i class="fa fa-bar-chart-o"></i>&nbsp;&nbsp;数据统计</h3>
	</div>
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
</div><?php }?>

    <!--底部导航-->
    <div class="block">
            <div class="block-content text-center"><p><span style="font-weight:bold"><?php echo $conf['sitename'] ?> <i class="fa fa-heart text-danger"></i> <?php echo date("Y")?> | </span><a class="" href="#customerservice" style="font-weight:bold" data-toggle="modal">客服与帮助</span></a><br/><?php echo $conf['footer']?></p>
            </div>
    </div>
    <!--底部导航-->
</div>
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
<script src="<?php echo $cdnserver ?>assets/appui/js/app.js"></script>
<script type="text/javascript">
var isModal =<?php echo empty($conf['modal']) ? 'false' : 'true';?>;
var homepage = true;
var hashsalt =<?php echo $addsalt_js?>;
$(function() {
	$("img.lazy").lazyload({effect: "fadeIn"});
});
</script>
<script src="assets/js/main.js?ver=<?php echo VERSION ?>"></script>
<?php if($conf['classblock']==1 || $conf['classblock']==2 && checkmobile()==false)include TEMPLATE_ROOT.'default/classblock.inc.php'; ?>
</body>
</html>