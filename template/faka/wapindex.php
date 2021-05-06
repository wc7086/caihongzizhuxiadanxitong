<?php
if(!defined('IN_CRONLITE'))exit();
if($_GET['buyok']==1){include_once TEMPLATE_ROOT.'faka/wapquery.php';exit;}

$cssadd = '<link rel="stylesheet" href="'.$cdnserver.'assets/faka/css/index.css?v=2" />';
include_once TEMPLATE_ROOT.'faka/head2.php';

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
<?php if($islogin2==1){?>
<div class="top w">
<div class="m_nav">
   <a href="./user/"><img src="assets/faka/images/m-index_27.png"><span>会员中心</span></a>
   <a href="./user/#chongzhi"><img src="assets/faka/images/m-index_24.png"><span>充值余额</span></a>
	<a href="./?mod=wapquery"><img src="assets/faka/images/m-index_26.png"><span>订单查询</span></a>
	<a href="./user/record.php"><img src="assets/faka/images/m-index_16.png"><span>消费记录</span></a>
</div></div>
<?php }?>
<div class="top w">
<div class="m_banner" >
<p><?php echo $conf['anounce']?></p>
</div>
</div>

<div class="baoliao w">
    <div class="panel-group" id="accordion">

<?php foreach($shua_class as $cid=>$classname){?>
<div class="panel cid<?php echo $cid?>">
<a data-toggle="collapse" data-parent="#accordion" data-target="#collapse<?php echo $cid?>"><div class="menux"><?php echo $classname?></div></a>
<div id="collapse<?php echo $cid?>" class="panel-collapse collapse in">
<?php
$num=0;
$rs=$DB->query("SELECT * FROM pre_tools WHERE cid='$cid' and active=1 order by sort asc");
while($res = $rs->fetch()){
	$num++;
	if(isset($price_obj)){
		$price_obj->setToolInfo($res['tid'],$res);
		if($price_obj->getToolDel($res['tid'])==1)continue;
		$price=$price_obj->getToolPrice($res['tid']);
	}else $price=$res['price'];
	if($res['is_curl']==4){
		$count = $DB->getColumn("SELECT count(*) FROM pre_faka WHERE tid='{$res['tid']}' and orderid=0");
		if($count>0&&$conf['faka_showleft']==0)$status = '<span class="bl_type" style="background-color:#0086ee">库存'.$count.'个</span>';
		elseif($count>0)$status = '<span class="bl_type" style="background-color:#0086ee">充足</span>';
		else $status = '<span class="bl_type" style="background-color:#6E6E6E;">缺货</span>';
	}elseif($res['stock']!==null){
		$count = $res['stock'];
		if($count>0&&$conf['faka_showleft']==0)$status = '<span class="bl_type" style="background-color:#0086ee">库存'.$count.'个</span>';
		elseif($count>0)$status = '<span class="bl_type" style="background-color:#0086ee">充足</span>';
		else $status = '<span class="bl_type" style="background-color:#6E6E6E;">缺货</span>';
	}else{
		if($res['close']==1)$status = '<span class="bl_type" style="background-color:#6E6E6E;">已下架</span>';
		else $status = '<span class="bl_type" style="background-color:#0086ee">正常</span>';
	}
	if($res['is_curl']==1||$res['is_curl']==2||$res['is_curl']==4||$res['is_curl']==5){
		$isauto = true;
	}else{
		$isauto = false;
	}
	$count = $DB->getColumn("SELECT count(*) FROM pre_faka WHERE tid='{$res['tid']}' and orderid=0");
	echo '<a href="./?mod=buy&cid='.$cid.'&tid='.$res['tid'].'" class="cid'.$cid.'"><div class="baoliao_content"><div class="bl_img" style="position:relative"><img data-original="'.($res['shopimg']?$res['shopimg']:'assets/faka/images/default.jpg').'" alt="'.$res['name'].'" class="lazy"><div style="width:100px;position:absolute;z-indent:2;left:1px;top:59px;">'.($isauto?'<div class="index_bl_type" style="background-color:#fe5604;max-width:56px;">'.$template_label_auto.'</div>':'<div class="index_bl_type" style="background-color:#49b41a;max-width:56px;">'.$template_label_manual.'</div>').'</div></div><div class="bl_right"><div class="bl_title">'.$res['name'].'</div><div class="bl_tag"><div class="bl_price">'.($conf['template_showsales']==1?'<span class="bl_type" style="background-color:#B187C1;">销量'.$res['sales'].'</span> ':'').$status.'  售价￥<b>'.$price.'</b></div></div></div></div></a>';
}
echo '</div></div>';
}?>
</div>
<div class="m_user w">
<a href="#">返回顶部</a>
</div>

<div class="copyright">Copyright &copy; <?php echo date("Y")?> <?php echo $conf['sitename']?>  <?php echo $conf['footer']?></div>
</div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script type="text/javascript">
$(function() {
	$("img.lazy").lazyload({effect: "fadeIn"});
	if($.cookie('sec_defend_time'))$.removeCookie('sec_defend_time', { path: '/' });
});
</script>
<?php if($conf['classblock']==1)include TEMPLATE_ROOT.'default/classblock.inc.php'; ?>
</body>
</html>