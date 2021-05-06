<?php
/**
 * 自定义分站商品密价
**/
include("../includes/common.php");
$title='自定义分站商品密价';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

if(!isset($_SESSION[md5(authcode)]) || $_SESSION[md5(authcode)]!==authcode) {
	$string=authcode($_SERVER['HTTP_HOST'].'||||'.authcode, 'ENCODE', 'daishuaba_cloudkey1');
	$query=curl_get('http://auth2.cccyun.cc/bin/check.php?string='.urlencode($string));
	$query=authcode($query, 'DECODE', 'daishuaba_cloudkey2');
	if($query=json_decode($query,true)) {
		if($query['code']==1)$_SESSION[md5(authcode)]=authcode;
		else{sysmsg('<h3>'.$query['msg'].'</h3>',true);exit;}
	}
}

?>
    <div class="col-md-12 center-block" style="float: none;">
<?php

adminpermission('site', 1);

$zid = isset($_GET['zid'])?intval($_GET['zid']):showmsg('参数不完整');
?>
<div class="block">
<div class="block-title clearfix">
<h2 id="blocktitle"></h2>
<span class="pull-right"><select id="pagesize" class="form-control"><option value="30">30</option><option value="50">50</option><option value="60">60</option><option value="80">80</option><option value="100">100</option></select><span>
</span></span>
</div>
  <form onsubmit="return searchItem()" method="GET" class="form-inline">
  <div class="form-group">
    <input type="text" class="form-control" name="kw" placeholder="请输入商品名称">
  </div>
  <button type="submit" class="btn btn-info">搜索</button>&nbsp;
  <a href="javascript:clearPrice()" class="btn btn-warning">恢复价格</a>&nbsp;
  <a href="javascript:listTable('start')" class="btn btn-default" title="刷新商品列表"><i class="fa fa-refresh"></i></a>
</form>

<div id="listTable"></div>
    </div>
  </div>
      
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script src="assets/js/siteprice.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>