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
<?php echo $background_css?>
<style>
.input-group-addon{background:linear-gradient(135deg,#FFBEC9,#FFBEC9);}
.shuaibi-tip {
    background: #fafafa repeating-linear-gradient(-45deg,#ffb6c1,#ffb6c1 1.125rem,transparent 1.125rem,transparent 2.25rem);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    margin: 15px 15px;
    padding: 15px;
    border-radius: 5px;
    font-size: 15px;
    color: #555555;
}
</style>
</head>
<body>
<?php if($background_image){?>
<img src="<?php echo $background_image;?>" alt="Full Background" class="full-bg full-bg-bottom animated pulse " ondragstart="return false;" oncontextmenu="return false;">
<?php }?>
<br />
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-4 center-block" style="float: none;" >

<!--弹出公告-->
<div class="modal fade" align="left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header-tabs">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
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
		<div class="modal fade col-xs-12 " align="left" id="anounce" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  
			<br>  <br>  <br>  
			<div class="modal-dialog panel panel-primary  animation-fadeInQuick2">
		    <div class="modal-content">
         <div class="list-group-item reed" style="background:linear-gradient(120deg, #5ED1D7 10%, #71D7A2 90%);">
						<button type="button" class="close " data-dismiss="modal"><span aria-hidden="true"><i class="fa  fa-times-circle"></i></span><span class="sr-only">Close</span></button>
						<script type="text/javascript">
							var now=(new Date()).getHours();
							if(now>0&&now<=6){
							document.write("熬夜对身体不好，快睡觉！");
							}else if(now>6&&now<=11){
							document.write("早上好！美好的一天开始啦！");
							}else if(now>11&&now<=14){
							document.write("中午好！欢迎光临本站！");
							}else if(now>14&&now<=18){
							document.write("下午好！欢迎光临本站！");
							}else{
							document.write("晚上好！睡前下一单醒来有惊喜哟！");
							}
						</script>
					</div>      <center> <br>
  <p></p>
<strong style="color:#b#eaf0cce;font-family:微软雅黑, 宋体;font-size:12px">
<font color="#FF0000">平</font><font color="#F5000A">台</font><font color="#EB0014">誓</font><font color="#E1001E">言</font><font color="#D70028">：</font><font color="#CD0032">真</font><font color="#C3003C">诚</font><font color="#B90046">服</font><font color="#AF0050">务</font><font color="#A5005A">，</font><font color="#9B0064">努</font><font color="#91006E">力</font><font color="#870078">打</font><font color="#7D0082">造</font><font color="#73008C">全</font><font color="#690096">网</font><font color="#5F00A0">最</font><font color="#5500AA">强</font><font color="#4B00B4">代</font><font color="#4100BE">刷</font><font color="#3700C8">货</font><font color="#2D00D2">源</font><font color="#2300DC">站</font><font color="#1900E6">！</font><strong><p></p>
</center>
<?php echo $conf['anounce']?>
                  <p></p><center>
<strong style="color:#b#eaf0cce;font-family:微软雅黑, 宋体, "">
  <span class="glyphicon glyphicon-info-sign"></span> <b></b> 建议网址收藏到浏览器！方便下次打开<strong>			</strong></strong></center><strong style="color:#b#eaf0cce;font-family:微软雅黑, 宋体, ""><strong><br>
  
		</strong></strong></center><strong style="color:#b#eaf0cce;font-family:微软雅黑, 宋体, ""><strong><br> 				</strong></strong></div><strong style="color:#b#eaf0cce;font-family:微软雅黑, 宋体, ""><strong>			</strong></strong></div><strong style="color:#b#eaf0cce;font-family:微软雅黑, 宋体, ""><strong>		</strong></strong></div><strong style="color:#b#eaf0cce;font-family:微软雅黑, 宋体, ""><strong>     

	  <!-- 公告结束 !-->


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
<!--面值-->
<div class="modal fade" align="left" id="1circles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
   <div class="modal-dialog">
    <div class="modal-content">
         <div class="list-group-item reed" style="background:#FFD700;">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
    <center><h4 class="modal-title" id="myModalLabel"><b><font color="#fff">下单时显示的“数量”是什么</font></b></h4></center>
      </div>	  
      <BR> 
      <center>
	  <font color="red">商品的面值×数量=下单数量（数量默认为1）</font>
	  <hr>
	   例如您购买：100个快手粉丝<br>下单数量选5，就是总共会获得500个粉丝
	  <hr>
	   例如您购买：1000个QQ名片赞<br>下单数量选3，就是总共会获得3000个名片赞 
	  <hr>
	  例如您购买：1000个全民K歌粉丝<br>下单数量选10，就是总共会获得10000个粉丝
	  <hr>
	  <font color="red">以此类推 本站其他商品都是如此，希望给您最好的购物体验</font>
      <br /> 
      </center>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">我知道了</button>
     </div>
    </div>
   </div>
  </div>
<!--面值-->
<!--logo-->
    <div class="widget-content themed-background-flat text-center" style="background-image: url(assets/simple/img/head5.jpg);background-size: 100% 100%;">
      <a href="javascript:void(0)">
			<img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq'];?>&spec=100" alt="Avatar" width="60" alt="avatar" style="height: auto filter: alpha(Opacity=80);-moz-opacity: 0.80;opacity: 0.80;" class="img-circle img-thumbnail img-thumbnail-avatar-1x animated zoomInDown">
        </a>

    </div>
<!--logo-->
<!--logo下面按钮-->
	<div class="widget-content text-center">
		<div class="text-center text-muted">
			<div class="btn-group btn-group-justified">
                <div class="btn-group">
                    <a class="btn btn-default"style="background: linear-gradient(to right,pink ,#FFFFFF,#FFFFFF,#FFFFFF);"data-toggle="modal" href="#anounce"><i class="fa fa-wifi"></i> <span style="font-weight:bold"> 平台公告</span></a>
                </div>
				<div class="btn-group">
                     <a class="btn btn-default"style="background: linear-gradient(to right,pink ,#FFFFFF,#FFFFFF,#FFFFFF);"data-toggle="modal" href="#lxkf"><i class="fa fa-qq"></i> <span style="font-weight:bold"> 联系客服</span></a>
                </div>
				<?php if($islogin2==1){?>
                <div class="btn-group">
                      <a class="btn btn-default"style="background: linear-gradient(to right,pink ,#FFFFFF,#FFFFFF,#FFFFFF);" href="./user/"><i class="fa fa-user"></i> 管理后台</a>
                </div>
				<?php }else{?>
				<div class="btn-group">
                      <a class="btn btn-default"style="background: linear-gradient(to right,pink ,#FFFFFF,#FFFFFF,#FFFFFF);" href="./user/login.php"><i class="fa fa-user"></i> 登录</a>
                </div>
				<?php }?>
</div></div></div>
<!--logo下面按钮-->
      

<!--免费拉圈-->
<div class="modal fade" align="left" id="circles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">免费拉圈圈99+</h4>
      </div>
      <div class="modal-body">
		<div class="form-group">
			<div class="input-group"><div class="input-group-addon">请输入QQ</div>
				<input type="text" name="qq" id="qq4" value="" class="form-control" required/>
			</div>
		</div>
		<input type="submit" id="submit_lqq" class="btn btn-primary btn-block" value="立即提交">
		<div id="result3" class="form-group text-center" style="display:none;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>
<!--免费拉圈-->
</div>
<div class="block full2">
<!--TAB标签-->
	<div class="block-title">

        <ul class="nav nav-tabs" data-toggle="tabs" style="background: linear-gradient(to right,pink ,#ffb6c1,#ffb6c1,#ffb6c1);">
            <li style="width: 25%;" align="center" class="active"><a href="#shop" data-toggle="tab"><span style="font-weight:bold"><i class="fa fa-shopping-cart fa-fw"></i>下单</span></a></li>
            <li style="width: 25%;" align="center"><a href="#search" data-toggle="tab" id="tab-query"><span style="font-weight:bold"><i class="fa fa-search fa-fw"></i>查询</span></a></li>
	    <li style="width: 25%;" align="center" ><a href="#Substation" data-toggle="tab"><span style="font-weight:bold"><font color="#ff0000"><i class="fa fa-sitemap fa-fw"></i>分站</span></font></a></li>
	    <li style="width: 25%;" align="center" <?php if($conf['gift_open']==0||$conf['fenzhan_buy']==1){?>class="hide"<?php }?>><a href="#gift" data-toggle="tab"><span style="font-weight:bold"><i class="fa fa-gift fa-fw"></i>抽奖</span></a></li>
	     <li style="width: 25%;" align="center" <?php if($conf['fenzhan_buy']==1||$conf['gift_open']==1){?>class="hide"<?php }?>><a href="./user/"><span style="font-weight:bold"><i class="fa fa-user fa-fw"></i> 登录</span></a></li>
			<li style="width: 25%;" align="center"><a href="#more" data-toggle="tab"><span style="font-weight:bold"><font color="#FF7F00"><span style="font-weight:bold"><span class="glyphicon glyphicon-gift"></span> 更多</font></span></a></li>
        </ul>
    </div>
<!--TAB标签-->
    <div class="tab-content"><center>
<font color="ff0000"></font></center>
<!--在线下单-->
    <div class="tab-pane active" id="shop">	
<?php
$rs=$DB->query("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$select='<option value="0">请选择分类</option>';
$select_count=0;
$classhide = explode(',',$siterow['class']);
while($res = $rs->fetch()){
	if($is_fenzhan && in_array($res['cid'], $classhide))continue;
	$select_count++;
	$select.='<option value="'.$res['cid'].'">'.$res['name'].'</option>';
}
if($select_count==0)$hideclass = true;
?>
		<div id="goodTypeContents">
			<?php echo $conf['alert']?>
			<?php if($conf['search_open']==1){?>
			<div class="form-group" id="display_searchBar">
				<div class="input-group"><div class="input-group-addon">搜索商品</div>
				<input type="text" id="searchkw" class="form-control" placeholder="搜索商品" onkeydown="if(event.keyCode==13){$('#doSearch').click()}"/>
				<div class="input-group-addon"><span class="glyphicon glyphicon-search onclick" title="搜索" id="doSearch"></span></div>
			</div></div>
			<?php }?>
			<div class="form-group" id="display_selectclass"<?php if($hideclass){?> style="display:none;"<?php }?>>
				<div class="input-group"><div class="input-group-addon">选择分类</div>
				<select name="tid" id="cid" class="form-control"><?php echo $select?></select>
			</div></div>
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon">选择商品</div>
				<select name="tid" id="tid" class="form-control" onchange="getPoint();"><option value="0">请选择商品</option></select>
			</div></div>
			<div class="form-group" id="display_price" style="display:none;center;color:#4169E1;font-weight:bold">
				<div class="input-group"><div class="input-group-addon">商品价格</div>
				<input type="text" name="need" id="need" class="form-control" style="center;color:#4169E1;font-weight:bold" disabled/>
			</div></div>
			<div class="form-group" id="display_left" style="display:none;">
				<div class="input-group"><div class="input-group-addon">库存数量</div>
				<input type="text" name="leftcount" id="leftcount" class="form-control" disabled/>
			</div></div>
			<div class="form-group" id="display_num" style="display:none;">
                <div class="input-group">
                <div class="input-group-addon">下单份数</div>
                <span class="input-group-btn"><input id="num_min" type="button" class="btn btn-info" style="border-radius: 0px;" value="━"></span>
				<input id="num" name="num" class="form-control" type="number" min="1" value="1"/>
				<span class="input-group-btn"><input id="num_add" type="button" class="btn btn-info" style="border-radius: 0px;" value="✚"></span>
			</div></div>
			<div id="inputsname"></div>
			<div id="alert_frame" class="alert alert-danger animation-bigEntrance" style="background: linear-gradient(to right,#FFB6C1,#FFB6C1); display:none; font-weight:bold"></div>
			<?php if($conf['shoppingcart']==1){?>
			<div class="btn-group btn-group-justified form-group">
			    <a class="btn btn-block btn-rounded" style="background: #dea7d7;color:#fff;" type="button" id="submit_cart_shop">加入购物车</a>
				<a type="submit" id="submit_buy" class="btn btn-block btn-rounded" style="background: linear-gradient(to right,pink ,#ffb6c1,#ffb6c1,#ffb6c1);color:#fff;">立即购买</a>
            </div>
			<?php }else{?>
			<div class="form-group">
				<input type="submit" id="submit_buy" class="btn btn-block btn-rounded" style="background: linear-gradient(to right,pink ,#ffb6c1,#ffb6c1,#ffb6c1);color:#fff;" value="立即购买">
			</div>
			<?php }?>
			<div class="panel-body border-t" id="alert_cart" style="display:none;"><i class="fa fa-shopping-cart"></i>&nbsp;当前购物车已添加<b id="cart_count">0</b>个商品<a class="btn btn-xs btn-danger pull-right" href="javascript:openCart()">购物车列表</a></div>
		</div>
  	</div>
<!--在线下单-->

    <!--客服介绍开始-->
<div class="modal fade col-xs-12" align="left" id="lxkf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >  <br />  <br />  
  <div class="modal-dialog panel panel-primary  animation-fadeInQuick2">
    <div class="modal-content">

<div class="panel-body">
     <h4 style="color:red;font-size:17px;text-align:center;">请查看你的订单问题是否需要联系客服</h4><hr>
<center>
<div id="demo-acc-faq" class="panel-group accordion">
<div class="panel panel-trans pad-top">
<a href="#demo-acc-faq11" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">①ＱＱ刷名片赞好久都没开始刷？</a>
<div id="demo-acc-faq11" class="mar-ver collapse" aria-expanded="false" style="height: 0px;">
<hr>1.名片赞不刷通常是刷了一会停了，这说明是腾讯频繁限制了，等会即可！</br>
2.名片赞很慢说明您下的是便宜赞，因价格低订单很大，所以就会慢点啦！<hr>
</div></div>

<div class="panel panel-trans pad-top">
<a href="#demo-acc-faq22" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">②ＱＱ空间业务好久没都开始刷？</a>
<div id="demo-acc-faq22" class="mar-ver collapse" aria-expanded="false" style="height: 0px;">
<hr>1.目前访客是24小时内开刷，特殊情况延迟<br>
2.空间必须设置允许任何人可访问<br>
3.空间被单封的不要下单（非好友进不去的）<hr>
</div></div>

<div class="panel panel-trans pad-top">
<a href="#demo-acc-faq33" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">③快手代刷业务好久都没开始刷？</a>
<div id="demo-acc-faq33" class="mar-ver collapse" aria-expanded="false" style="height: 0px;">
<hr>1.目前快手限制的比较厉害，刷的慢 请耐心等待<br>
2.快手粉丝/播放/评论  都是当天到账<br>
3.快手双击 24小时内都会到账 勿催！<hr>
</div></div>

<div class="panel panel-trans pad-top">
<a href="#demo-acc-faq88" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">④抖音业务业务好久都没开始刷？</a>
<div id="demo-acc-faq88" class="mar-ver collapse" aria-expanded="false" style="height: 0px;">
<hr>1.目前抖音业务基本上下单秒刷，小部分30分钟之内<br>
2.如超过5小时还没到账,请仔细查看商品说明<br>
3.请勿使用短链接直接下单！不懂得联系客服处理<hr>
</div></div>

<div class="panel panel-trans pad-top">
<a href="#demo-acc-faq44" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">⑤ＱＱ永久钻会员好久没有到账？</a>
<div id="demo-acc-faq44" class="mar-ver collapse" aria-expanded="false" style="height: 0px;">
<hr>1.钻会员下单后72小时内到账，勿催！<br>
2.钻会员下单后72小时内到账，勿催！<br>
3.钻会员下单后72小时内到账，勿催！<hr>
</div></div>

<div class="panel panel-trans pad-top">
<a href="#demo-acc-faq55" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">⑥卡密CDK没有发送到我的邮箱？</a>
<div id="demo-acc-faq55" class="mar-ver collapse" aria-expanded="false" style="height: 0px;">
<hr>1.先点击网站上的“查单”按钮<br>
2.再输入自己下单时时填的ＱＱ邮箱<br>
3.查到订单后再点击“详细”即可看到<hr>
</div></div>

<div class="panel panel-trans pad-top">
<a href="#demo-acc-faq66" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">⑦已付款成功为什么查不到订单？</a>
<div id="demo-acc-faq66" class="mar-ver collapse" aria-expanded="false" style="height: 0px;">
<hr>1.如果出现这种问题，请联系客服处理<br>
2.提供“付款截图”“下单商品名称”“下单账号”<br>
3.直接把这三个信息发给客服，然后等待客服回复处理！<hr>
</div></div>

<div class="panel panel-trans pad-top">
<a href="#demo-acc-faq77" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">⑧以上帮助并不能解决我的问题？</a>
<div id="demo-acc-faq77" class="mar-ver collapse" aria-expanded="false" style="height: 0px;">
<hr>亲，很抱歉我们没有及时为您解决问题，业务基本24小时内到账，QQ钻类订单72小时之内到账，具体到账时间请去看商品介绍，超过时间未到账的请联系下面的人工客服。</br>
<font color="red">注意：有问题请联系客服，直接说明你遇到的问题，2小时客服没回复，请重新发送消息（禁止语音/抖动/骂人）</font>
</div></div>
</div>
</center> <hr> 
<table class="table table-striped table-borderless table-vcenter remove-margin-bottom">
         <tbody>
            <tr class="animation-fadeInQuick2">
               <tbody>
                    <tr class="animation-fadeInQuick2">
                        <td class="text-center" style="width: 100px;">
                            <img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq']?>&spec=100" alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar">
                        </td>
                        <td>
                            <h5><strong>订单售后客服</strong></h5>
                            <i class="fa fa-check-circle-o text-danger"></i> 客服当前<br>
                            <i class="fa fa-comment-o text-success"></i>
                            <script>var online = new Array();</script>
                                           <script>
                                if (online[0] == 0)
                                    document.write("在线,有事请留言!");
                                else
                                    document.write("在线中,有事直奔主题!");
                            </script>
                        </td>
                        <td class="text-right" style="width: 20%;">
                            <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $conf['kfqq'];?>&site=qq&menu=yes" target="_blank" data-toggle="modal" class="btn btn-sm btn-info">联系</a>
                </td>
            </tr>
         </tbody>
        </table>	
</div>   
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>			
</div>	
    </div>
  </div>
</div>
<!--联系客服结束-->

<!--查询订单-->
   <div class="tab-pane" id="search">
              <table class="table table-striped table-borderless table-vcenter remove-margin-bottom">
         <tbody>
            <tr class="shuaibi-tip animation-bigEntrance">
                <td class="text-center" style="width: 100px;">
                    <img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq'];?>&spec=100" alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar">
                </td>
                <td>
                    <h4><strong>站长</strong></h4>
					<i class="fa fa-fw fa-qq text-primary"></i> <?php echo $conf['kfqq'];?><br><i class="fa fa-fw fa-history text-danger"></i>售后订单问题请联系客服
                </td>
                <td class="text-right" style="width: 20%;">
                    <a href="#lxkf"  data-toggle="modal" class="btn btn-sm btn-info">联系</a>
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
					<span class="input-group-btn"><a href="#cxsm"  data-toggle="modal" class="btn btn-warning"><i class="glyphicon glyphicon-exclamation-sign"></i></a></span>
				</div>
			</div>		
			<input type="submit" id="submit_query" class="btn btn-primary btn-block btn-rounded" style="background: linear-gradient(to right,#87CEFA,#6495ED);color:#fff;" value="立即查询">		
            <br>
			<div id="result2" class="form-group" style="display:none;">
              <center><small><font color="#ff0000">手机用户可以左右滑动</font></small></center>
				<div class="table-responsive">
					<table class="table table-vcenter table-condensed table-striped">
					<thead><tr><th class="hidden-xs">下单账号</th><th>商品名称</th><th>数量</th><th class="hidden-xs">购买时间</th><th>状态</th><th>操作</th></tr></thead>
					<tbody id="list">
					</tbody>
					</table>
				</div>
			</div>

<!--查询订单-->
		
		<!--网站日志-->
		<div class="row text-center" <?php if($conf['hide_tongji']==1){?>style="display:none;"<?php }?>>
			<div class="col-xs-4">
				<h5 class="widget-heading"><small>订单总数</small><br><a href="javascript:void(0)" class="themed-color-flat"><span id="count_orders"></span>条</a></h5>
			</div>
			<div class="col-xs-4">
				 <h5 class="widget-heading"><small>今日订单</small><br><a href="javascript:void(0)" class="themed-color-flat"><span id="count_orders2"></span>条</a></h5>
			</div>
			<div class="col-xs-4">
				<h5 class="widget-heading"><small>运营天数</small><br><a href="javascript:void(0)" class="themed-color-flat"><span id="count_yxts"></span>天</a></h5>
			</div>
<!--网站日志-->
	</div>
   </div>
<!--查询订单-->
<!--开通分站-->
    <div class="tab-pane" id="Substation">
		<table class="table table-borderless table-pricing">
            <tbody>
                <tr class="active" >
                    <td>
                        <h4><span style="font-weight:bold"><font color="#FF8000">加</font><font color="#EC6D13">入</font><font color="#D95A26">我</font><font color="#C64739">们</font><font color="#B3344C"> </font><font color="#A0215F">赚</font><font color="#8D0E72">钱</font><font color="#7A0085">就</font><font color="#670098">是</font><font color="#5400AB">如</font><font color="#4100BE">此</font><font color="#2E00D1">简</font><font color="#1B00E4">单</font> </span></h4>
                    </td>
                </tr>
                <tr>
                    <td>学生/上班族/创业/休闲赚钱必备工具</td>
                </tr>
                <tr>
                    <td>轻松松推广网站/日赚上万元不是梦</td>
                </tr>
				<tr>
                    <td> 快加入我们成为大家庭中的一员吧</td>
                </tr>
                
                <tr class="active">
                    <td>
						<a href="#userjs" data-toggle="modal" class="btn btn-effect-ripple  btn-info"><i class="fa fa-th-list"></i><span class="btn-ripple animate" style="height: 100px; width: 100px; top: -34.4px; left: 2.58749px;"></span> 功能介绍</a>
                        <a href="user/reg.php" target="_blank" class="btn btn-effect-ripple  btn-danger"><i class="fa fa-arrow-right"></i> 马上加入</a>
                        <a href="user/" target="_blank" class="btn btn-effect-ripple btn-success" style="overflow: hidden; position: relative;"><i class="fa fa-arrow-right"></i><span class="btn-ripple animate" style="height: 100px; width: 100px; top: -34.4px; left: 2.58749px;"></span> 站长后台</a>
                       
                    </td>
                </tr>
            </tbody>
        </table>
    </table>
	</div>
<!--开通分站-->
<!--抽奖页面开始-->
    <div class="tab-pane" id="gift">
      <li class="list-group-item" style="background: linear-gradient;"><center><b>（完成全部任务 - 高机率中大奖）</b></center></li>
      <li class="list-group-item" style="background: linear-gradient;"><center>一 、拥有一个分站不限制版本 （<a href="/user/reg.php" target="_blank">点击开通</a>）</center></li>
      <li class="list-group-item" style="background: linear-gradient;"><center>二 、将本网站分享给一个朋友 （<a href="/?mod=invite" target="_blank">点击分享</a>） </center></li>
      <br>
		<div class="widget-content themed-background-flat text-right clearfix animation-pullup">
        <a id="start" style="display:block;"><img src="https://s2.ax1x.com/2019/04/18/EpCd41.gif" alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar pull-left">
        </a>
        <a id="stop" style="display:none;"><img src="https://s2.ax1x.com/2019/04/18/EpCd41.gif" alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar pull-left">
        </a>
        <p></p>
        <h4 id="roll" class="widget-heading h4"><font color="#00BFFF"><i class="fa fa-hand-o-left" aria-hidden="true"></i> 猛击按钮进行抽奖</font></h4>
		<h4 id="roll" class="widget-heading h4"><font color="#ff0000"><i class="fa fa-heartbeat" aria-hidden="true"></i> 再次猛击获取奖品</font></h4>
         </div>
		 <hr>
		 <font color="#FF7F00">
		 <li class="list-group-item bord-top"><b>抽奖规则：</b>每人每天限抽1次，欢迎您每天来抽奖！
         <br><b>奖品内容：</b>本站的N个商品，持续添加劲爆更新中！
         <br><font color="#008000">抽奖心得：赶快邀请你的朋友来吧，听说推广网站有几率中大奖哦！</font>
         <button id="copy-btn" class="btn btn-success btn-xs" data-clipboard-text="我在这里参与抽奖，你也快来吧！地址：<?php echo $_SERVER['HTTP_HOST'];?>        （请复制网址到浏览器内打开）">点我复制推广链接</button>
         <br>
<br></font></li><br>
		 <br>
      <br><br>
	</div>
<!--抽奖页面结束-->

<!--更多按钮-->
    <div class="tab-pane" id="more">
	 
	<div class="row">
		<div class="col-sm-6<?php if($conf['gift_open']==0){?> hide<?php }?>">
            <a href="#gift" data-toggle="tab" class="widget">
               <div class="widget-content themed-background-info text-right clearfix" style="background: linear-gradient(to right,#f093fb,#f5576c);color:#fff;">
                    <div class="widget-icon pull-left">
                        <i class="fa fa-gift"></i>
                    </div>
                    <h2 class="widget-heading h4">
                        <strong> 免费抽奖</strong>
                    </h2>
                    <span>在线抽奖领取免费商品</span>
                </div>
            </a>
        </div>

		<div class="col-sm-6<?php if(empty($conf['appurl']) || $conf['gift_open']==1 || $is_fenzhan){?> hide<?php }?>">
            <a href="<?php echo $conf['appurl']; ?>" target="_blank" class="widget">
              <div class="widget-content themed-background-warning text-right clearfix" style="background: linear-gradient(to right,#ff9a9e,#fecfef);color:#fff;">
                    <div class="widget-icon pull-left">
                        <i class="fa fa-cloud-download"></i>
                    </div>
                    <h2 class="widget-heading h4">
                        <strong>APP下载</strong>
                    </h2>
                    <span>下载APP，让您下单更方便</span>
                </div>
            </a>
        </div>
		<div class="col-sm-6<?php if(empty($conf['invite_tid'])){?> hide<?php }?>">
            <a  href="./?mod=invite" target="_blank" class="widget">
                 <div class="widget-content themed-background-success text-right clearfix" style="background: linear-gradient(to right,#f6d365,#fda085);color:#fff;">
                    <div class="widget-icon pull-left">
                        <i class="fa fa-paper-plane-o"></i>
                    </div>
                    <h2 class="widget-heading h4">
                        <strong>免费领赞</strong>
                    </h2>
                    <span>推广本站免费领名片赞</span>
                </div>
            </a>
        </div>

        <div class="col-sm-6">
            <a href="./user" target="_blank" class="widget">
                <div class="widget-content themed-background-warning text-right clearfix" style="background: linear-gradient(to right,#ff9a9e,#fecfef);color:#fff;">
                    <div class="widget-icon pull-left">
                        <i class="fa fa-circle-o"></i>
                    </div>
                    <h2 class="widget-heading h4">
                        <strong>用户中心</strong>
                    </h2>
                    <span>分站管理后台登录</span>
                </div>
            </a>
        </div>

		<div class="col-sm-6<?php if(empty($conf['daiguaurl'])){?> hide<?php }?>">
            <a href="./?mod=daigua" class="widget">
                <div class="widget-content themed-background-success text-right clearfix" style="color: #fff;">
                    <div class="widget-icon pull-left">
                        <i class="fa fa-rocket"></i>
                    </div>
                    <h2 class="widget-heading h4">
                        <strong>等级代挂</strong>
                    </h2>
                    <span>管理自己的QQ代挂</span>
                </div>
            </a>
        </div>
	</div>
	</div>
<!--更多按钮-->

<!--版本介绍-->
<div class="modal fade" align="left" id="userjs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">版本介绍</h4>
		</div>
		<div class="block">
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
                            <td>专属代刷平台</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
                        <tr class="">
                            <td>三种在线支付接口</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="success">
                            <td>专属网站域名</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="">
                            <td>赚取用户提成</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="info">
                            <td>赚取下级分站提成</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-danger"><i class="fa fa-close"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="">
                            <td>设置商品价格</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="warning">
                            <td>设置下级分站商品价格</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-danger"><i class="fa fa-close"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="">
                            <td>搭建下级分站</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-danger"><i class="fa fa-close"></i></span>
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						
                    </tbody>
                </table>
            </div>
				<center style="color: #b2b2b2;"><small><em>* 自己的能力决定着你的收入！</em></small></center>
        </div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
		</div>
    </div>
  </div>
</div>
<!--版本介绍-->

    </div>
</div>
<!--关于我们弹窗-->
<div class="modal fade" align="left" id="about" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">新手下单帮助</h4>
		</div>
		<div class="modal-body">
			<a href="javascript:void(0)" class="widget">
				<center>
						<strong><font size="3">站长ＱＱ：<?php echo $conf['kfqq'];?></font> </strong>
                      	 <br /> 
						<strong><font size="2">本站域名：<?php echo $_SERVER['HTTP_HOST'];?></font> </strong>
					</center>
					<center>
<div id="demo-acc-faq" class="panel-group accordion"><div class="panel panel-trans pad-top"><a href="#demo-acc-faq1" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">下单很久了都没有开始刷呢？</a><div id="demo-acc-faq1" class="mar-ver collapse" aria-expanded="false" style="height: 0px;">由于本站采用全自动订单处理，难免会出现漏单，部分单子处理时间可能会稍长一点，不过都会完成，最终解释权归本站所有。超过24小时没处理请联系客服！</div></div><div class="panel panel-trans pad-top"><a href="#demo-acc-faq2" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">ＱＱ空间业务类下单方法讲解</a><div id="demo-acc-faq2" class="mar-ver collapse" aria-expanded="false">1.下单前：空间必须是所有人可访问,必须自带1~4条原创说说!<br>2.代刷期间，禁止关闭访问权限，或者删除说说，删除说说的一律由自行负责，不给予补偿。</div></div><div class="panel panel-trans pad-top"><a href="#demo-acc-faq3" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">空间说说赞相关下单方法讲解</a><div id="demo-acc-faq3" class="mar-ver collapse" aria-expanded="false">1.下单前：空间必须是所有人可访问,必须自带1条原创说说!转发的说说不能刷！<br>2.在“QQ号码”栏目输入QQ号码，点击下面的获取说说ID并选择你需要刷的说说的ID，下单即可。<br>3.代刷期间，禁止关闭访问权限，或者删除说说，删除说说的一律由自行负责，不给予补偿。</div></div><div class="panel panel-trans pad-top"><a href="#demo-acc-faq4" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">视频/音乐业务类下单方法讲解</a><div id="demo-acc-faq4" class="mar-ver collapse" aria-expanded="false">1.打开你的视频/音乐APP<br>2.复制里面需要刷的视频/歌曲链接<br>3.然后把链接填入到输入框里面，然后提交购买。</div></div><div class="panel panel-trans pad-top"><a href="#demo-acc-faq5" class="text-semibold text-lg text-main collapsed" data-toggle="collapse" data-parent="#demo-acc-faq" aria-expanded="false">永久ＱＱ会员/钻下单方法讲解</a><div id="demo-acc-faq5" class="mar-ver collapse" aria-expanded="false">1.下单之前，先确认输的信息是不是正确的!<br>2.Q会员/钻因为需要人工处理，所以每天不定时开刷，24小时-48小时内到账！</div></div></div>
             </center>                                                           
			</a>
		</div>
    </div>
  </div>
</div>
<!--关于我们弹窗-->

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
<div class="block">
	<div class="block-title" style="background: linear-gradient(to right,pink ,#ffb6c1,#ffb6c1,#ffb6c1);">
		<h3 class="panel-title"><i class="fa fa-newspaper-o"></i> 文章列表</h3>
	</div>
	<?php foreach($msgrow as $row){
	echo '<a target="_blank" class="list-group-item" href="'.article_url($row['id']).'"><span class="btn btn-'.$class_arr[($i++)%5].' btn-xs">'.$i.'</span>&nbsp;'.$row['title'].'</a>';
	}?>
	<a href="<?php echo article_url()?>" title="查看全部文章" class="btn-default btn btn-block" target="_blank">查看全部文章</a>
</div>
<!--文章列表-->
<?php }?>

<!--底部导航-->
<div class="panel panel-default">
  <center>
    <div class="panel-body"><b>
<font color="#C00000">本</font><font color="#B5000B">站</font><font color="#AA0016">地</font><font color="#9F0021">址</font><font color="#94002C">：</font>
<font color="red">
<script language="javascript">
host = window.location.host;
document.write(""+host)
</script>
</font>
<font color="#890037">（</font><font color="#7E0042">欢</font><font color="#73004D">迎</font><font color="#680058">收</font><font color="#5D0063"></font><font color="#52006E">藏</font><font color="#470079">）</font></b><br><span style="font-weight:bold"><font color="#C00000">C</font><font color="#B5000B">o</font><font color="#AA0016">p</font><font color="#9F0021">y</font><font color="#94002C">R</font><font color="#890037">i</font><font color="#7E0042">g</font><font color="#73004D">h</font><font color="#680058">t</font><font color="#5D0063"></font> <i class="fa fa-heart text-danger"></i> <font color="#52006E">2</font><font color="#470079">0</font><font color="#3C0084">2</font><font color="#31008F">0</font><font color="#26009A"> | </font></span><a class="" href="#about" data-toggle="modal"><span style="font-weight:bold">新手下单帮助</span></a><br/>
<?php echo $conf['footer']?>
           <center>
<div style="display:none">

          </div>
   </center>
    </div>
<!--底部导航-->
<!--音乐代码-->
<div id="audio-play" <?php if(empty($conf['musicurl'])){?>style="display:none;"<?php }?>>
  <div id="audio-btn" class="on" onclick="audio_init.changeClass(this,'media')">
    <audio loop="loop" src="<?php echo $conf['musicurl']?>" id="media" preload="preload"></audio>
  </div>
</div>
<!--音乐代码-->
</div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
var clipboard = new Clipboard('#copy-btn');
clipboard.on('success', function(e) {
layer.msg('复制成功，快去发给你的朋友吧！');
});
clipboard.on('error', function(e) {
layer.msg('复制失败，请长按链接后手动复制');
});
</script>
<script src="<?php echo $cdnserver?>assets/appui/js/plugins.js"></script>
<script src="<?php echo $cdnserver?>assets/appui/js/app.js"></script>
<script type="text/javascript">
var isModal=<?php echo empty($conf['modal'])?'false':'true';?>;
var homepage=true;
var hashsalt=<?php echo $addsalt_js?>;
</script>
<script src="assets/js/main.js?ver=<?php echo VERSION ?>"></script>
<?php if($conf['classblock']==1 || $conf['classblock']==2 && checkmobile()==false)include TEMPLATE_ROOT.'default/classblock.inc.php'; ?>
</body>
</html>