<?php
if(!defined('IN_CRONLITE'))exit();
if($_GET['buyok']==1 && checkmobile()){include_once TEMPLATE_ROOT.'faka/wapquery.php';exit;}
elseif($_GET['buyok']==1||$_GET['chadan']==1){include_once TEMPLATE_ROOT.'faka/query.php';exit;}
if(checkmobile() && !$_GET['pc'] || $_GET['mobile']){include_once TEMPLATE_ROOT.'faka/wapindex.php';exit;}

if(isset($_GET['tid']) && !empty($_GET['tid']))
{
	$tid=intval($_GET['tid']);
    $tool=$DB->getRow("select tid from pre_tools where tid='$tid' limit 1");
    if($tool)
    {
		exit("<script language='javascript'>window.location.href='./?mod=buy&tid=".$tool['tid']."';</script>");
    }
}

include_once TEMPLATE_ROOT.'faka/head.php';

if($islogin2==1){
	$price_obj = new \lib\Price($userrow['zid'],$userrow);
}elseif($is_fenzhan == true){
	$price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
	$price_obj = new \lib\Price(1);
}
$classhide = explode(',',$siterow['class']);
$rs=$DB->query("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$shua_class=array();
while($res = $rs->fetch()){
	if($is_fenzhan && in_array($res['cid'], $classhide))continue;
	$shua_class[$res['cid']]=$res['name'];
}

$template_label_auto = $conf['template_label_auto']?$conf['template_label_auto']:'自动';
$template_label_manual = $conf['template_label_manual']?$conf['template_label_manual']:'手动';

?>
<div class="g-body">
<br/>
<br/>
<div class="topliucheng"><img src="<?php echo $cdnserver?>assets/faka/images/goumaizn01.png" title="">
<?php echo $conf['anounce']?>
</div>
<div id="bd">

<div id="bar">
<div class="bar_top">商家信息</div>
<div class="from_wz_41">
<div class="kefu"><a target="blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo $conf['kfqq']?>&amp;Site=qq&amp;Menu=yes">QQ:<?php echo $conf['kfqq']?></a> </div>
<!--div class="kefu">
QQ群：168133539</div>
<div class="kefu">
微信：71007288</div-->
</div>

<div class="bar_top">手机扫码购买</div><div class="erweima">
<img src="//api.qrserver.com/v1/create-qr-code/?size=150x150&margin=10&data=<?php echo $siteurl?>"> </div>
</div>

<div id="bar_r">
<div id="body_xiao">
<table style="width:100%" border="0" cellpadding="10" cellspacing="0"  >
			<thead>
				<tr >
  				    <th class="indexlistlb ziti">商品名称</th>
		
					
					 <th class="indexlistlb ziti">售价</th>
					 
					 <th class="indexlistlb ziti">库存</th>
					 <th class="indexlistlb ziti">操作</th>
				</tr>
			</thead>
			<tbody>
<?php foreach($shua_class as $cid=>$classname){?>
<tr class="cid<?php echo $cid?>"><th colspan="4" class="tableth1 ziti" ><?php echo $classname?></th></tr>
<?php
$rs=$DB->query("SELECT * FROM pre_tools WHERE cid='$cid' and active=1 order by sort asc");
while($res = $rs->fetch()){
	if(isset($price_obj)){
		$price_obj->setToolInfo($res['tid'],$res);
		if($price_obj->getToolDel($res['tid'])==1)continue;
		$price=$price_obj->getToolPrice($res['tid']);
	}else $price=$res['price'];
	if($res['is_curl']==4){
		$count = $DB->getColumn("SELECT count(*) FROM pre_faka WHERE tid='{$res['tid']}' and orderid=0");
		if($count>0&&$conf['faka_showleft']==0)$status = '<font color="#000CF9" size="3" title="商品库存">'.$count.'个</font>';
		elseif($count>0)$status = '<font color="#000CF9" size="3" title="商品库存">充足</font>';
		else $status = '<font color="#CCCCCC" title="商品库存">缺货</font>';
	}elseif($res['stock']!==null){
		$count = $res['stock'];
		if($count>0&&$conf['faka_showleft']==0)$status = '<font color="#000CF9" size="3" title="商品库存">'.$count.'个</font>';
		elseif($count>0)$status = '<font color="#000CF9" size="3" title="商品库存">充足</font>';
		else $status = '<font color="#CCCCCC" title="商品库存">缺货</font>';
	}else{
		if($res['close']==1)$status = '<font color="#CCCCCC" title="商品状态">已下架</font>';
		else $status = '<font color="#000CF9" size="3" title="商品状态">正常</font>';
	}
	if($res['is_curl']==1||$res['is_curl']==2||$res['is_curl']==4||$res['is_curl']==5){
		$isauto = true;
	}else{
		$isauto = false;
	}
	echo '<tr class="cid'.$cid.'"><td align="left" class="stitle tableth2 ziti">'.($isauto?'<a class="button button-action button-circle button-small" title="自动发货商品，购买后自动发货">'.$template_label_auto.'</a>':'<a class="button button-highlight button-circle button-small" title="手动发货商品，购买后客服手动发货给你">'.$template_label_manual.'</a>').' <font size="3" title="'.$res['name'].'">'.$res['name'].'</font></td><td><font color="#FF5400" size="2" title="商品售价">'.$price.'元</font>'.($conf['template_showsales']==1?'<br><font color="#bbb" size="1">销量:'.$res['sales'].'</font>':'').'</td><td>'.$status.'</td><td>'.($isfaka&&$count==0||$res['close']==1?'<a class="button button-default button-rounded button-small" href="./?mod=buy&cid='.$cid.'&tid='.$res['tid'].'">缺货</a>':'<a class="button button-primary button-rounded button-small" href="./?mod=buy&cid='.$cid.'&tid='.$res['tid'].'">购买</a>').'</td></tr>';
}
}?>
			</tbody>
</table>

</div>
</div></div>
</div>

<div class="footer">
  <div class="nb">
    <div class="promise">
      <ul>
        <li>
          <div class="promise-box">
            <em class="yec-icon-qg yec-icon"></em>
            <div class="word ziti">
              <h3>正品保障</h3>
              <p>我们坚持“诚信为本、客户第一”的经营理为广大客户提供 优质的产品及服务，我们保证所售商品均为正品，请放心选购。</p>
            </div>
          </div>
        </li>
        <li>
          <div class="promise-box">
            <em class="yec-icon-fare yec-icon"></em>
            <div class="word ziti">
              <h3>折扣&amp;优惠</h3>
              <p>我们会将优惠商品第一时间上架并进行通知，购买数量超过商品设定的值，价格会更便宜，如果您是批发商可以联系我们定制会员级别。</p>
            </div>
          </div>
        </li>
        <li>
          <div class="promise-box">
            <em class="yec-icon-cs yec-icon"></em>
            <div class="word ziti">
              <h3>售后无忧</h3>
              <p>我们的上班时间是早8:00点-晚22:00点（节假日除外），如遇到紧急情况无法联系到客服请不要着急，客服上线后第一时间处理，感谢您的支持。</p>
            </div>
          </div>
        </li>
        <li>
          <div class="promise-box">
            <em class="yec-icon-help yec-icon"></em>
            <div class="word ziti">
              <h3>帮助中心</h3>
              <p>如果你对本网站有不会使用的地方，请阅读帮助中心提供的帮助文档，或者联系我们的客服人员。</p>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="foot-sortnav"></div>
    <div class="copyright ziti">
      <p>
         Copyright © <?php echo date("Y")?> <?php echo $conf['sitename']?>   All rights reserved. <?php echo $conf['footer']?>
    </div>
  </div>
</div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	if($.cookie('sec_defend_time'))$.removeCookie('sec_defend_time', { path: '/' });
})
</script>
<?php if($conf['classblock']==1 || $conf['classblock']==2)include TEMPLATE_ROOT.'default/classblock.inc.php'; ?>
</body>
</html>