<?php
/**
 * 订单管理
**/
include("../includes/common.php");
$title='订单管理';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<style>
td.wbreak{max-width:480px;word-break:break-all;}
.orderList{table-layout:initial!important}
.orderList thead>tr>th{text-align:center}
.orderList tbody tr>td:nth-child(1){min-width:60px;text-align:center}
.orderList tbody tr>td:nth-child(2),.orderList tbody tr>td:nth-child(3){max-width:360px;min-width:160px}
.orderList tbody tr>td:nth-child(4),.orderList tbody tr>td:nth-child(5){max-width:100px;min-width:50px;text-align:center}
.orderList tbody tr>td:nth-child(6){max-width:100px;min-width:50px;text-align:center}
.orderList tbody tr>td:nth-child(7){min-width:150px;text-align:center}
.orderList tbody tr>td:nth-child(8),.orderList tbody tr>td:nth-child(9){min-width:70px;text-align:center}
.orderList tbody tr>td:nth-child(10){min-width:110px;text-align:center}
.layui-layer-content{padding: 10px;}
.dates{max-width: 120px;}
.input-group-addon{min-width: unset;}
</style>
<link href="../assets/appui/css/datepicker.css" rel="stylesheet">
<script src="<?php echo $cdnpublic?>bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $cdnpublic?>bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.zh-CN.min.js"></script>
    <div class="col-md-12 center-block" style="float: none;">
<?php adminpermission('order', 1);?>
<div class="block">
<div class="block-title clearfix">
<form onsubmit="return searchOrder()" method="GET" class="form-inline">
<input type="hidden" name="tid" value="<?php echo @$_GET['tid']?>">
<input type="hidden" name="zid" value="<?php echo @$_GET['zid']?>">
<input type="hidden" name="uid" value="<?php echo @$_GET['uid']?>">
  <div class="form-group">
    <label><h2>搜索订单</h2></label>
    <input type="text" class="form-control" name="kw" placeholder="请输入下单账号或订单号" value="">
	<div class="input-group input-daterange">
	<input type="text" id="starttime" name="starttime" class="form-control dates" placeholder="开始日期" autocomplete="off" title="留空则不限时间范围">
	<span class="input-group-addon" onclick="$('#starttime').val('');$('#endtime').val('');" title="清除"><i class="fa fa-chevron-right"></i></span>
	<input type="text" id="endtime" name="endtime" class="form-control dates" placeholder="结束日期" autocomplete="off" title="留空则不限时间范围">
	</div>
	<select name="type" class="form-control"><option value="-1">全部状态</option><option value="0">待处理</option><option value="2">正在处理</option><option value="1">已完成</option><option value="3">异常</option><option value="4">已退单</option></select>
  </div>
  <button type="submit" class="btn btn-primary">搜索</button>&nbsp;
  <a href="./export.php" class="btn btn-success">导出订单</a>
  <a href="./log.php" class="btn btn-warning" target="_blank">对接日志</a>
  <a href="javascript:onekeyDj()" class="btn btn-default">一键补单</a>
  <a href="javascript:clearOrder()" class="btn btn-default" title="刷新订单列表"><i class="fa fa-refresh"></i></a>
<span class="pull-right"><select id="pagesize" class="form-control" title="每页显示"><option value="30">30</option><option value="50">50</option><option value="60">60</option><option value="80">80</option><option value="100">100</option></select><span>
</span></span>
</form>
</div>

<div id="listTable"></div>
</div>
  </div>
</div>
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script src="assets/js/list.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>