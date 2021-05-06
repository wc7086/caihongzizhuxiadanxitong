<?php
/**
 * 支付记录
**/
include("../includes/common.php");
$title='支付记录';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<style>
.table thead > tr > th{ font-size:15px}
.orderList tbody tr>td:nth-child(3),.orderList tbody tr>td:nth-child(4){max-width:320px;}
.orderList tbody tr>td:nth-child(5),.orderList tbody tr>td:nth-child(6),.orderList tbody tr>td:nth-child(8){min-width:65px;text-align:center}
.orderList tbody tr>td:nth-child(7){min-width:150px;text-align:center}
</style>
    <div class="col-md-12 center-block" style="float: none;">
<?php

adminpermission('shop', 1);

?>
<div class="block">
<div class="block-title clearfix">
<h2 id="blocktitle"></h2>
<span class="pull-right"><select id="pagesize" class="form-control"><option value="30">30</option><option value="50">50</option><option value="60">60</option><option value="80">80</option><option value="100">100</option></select><span>
</span></span>
</div>
  <form onsubmit="return searchItem()" method="GET" class="form-inline">
  <div class="form-group">
  <label>搜索</label>
	<select class="form-control" name="column">
	  <option value="trade_no">支付订单号</option>
	  <option value="api_trade_no">支付接口订单号</option>
	  <option value="zid">站点ID</option>
	  <option value="userid">用户ID</option>
	  <option value="tid">商品ID</option>
	  <option value="name">订单名称</option>
	  <option value="money">支付金额</option>
	  <option value="input">下单内容</option>
	  <option value="ip">用户IP</option>
	</select>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="kw" placeholder="请输入搜索内容">
  </div>
  <div class="form-group">
    <select name="type" class="form-control" default="0"><option value="all">所有支付方式</option><option value="alipay">支付宝</option><option value="wxpay">微信支付</option><option value="qqpay">QQ钱包</option></select>
  </div>
  <button type="submit" class="btn btn-info">搜索</button>&nbsp;
  <a href="javascript:clearItem()" class="btn btn-default" title="刷新支付记录列表"><i class="fa fa-refresh"></i></a>
  <div class="form-group">
	<select id="dstatus" class="form-control"><option value="0">显示全部</option><option value="2">只显示已支付</option><option value="1">只显示未支付</option></select>
  </div>
</form>

<div id="listTable"></div>
    </div>
  </div>
      
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script src="assets/js/payorder.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>