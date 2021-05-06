<?php
/**
 * 余额提现处理
**/
include("../includes/common.php");
$title='余额提现处理';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<style>
.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}
.form-inline .form-group {
    display: inline-block;
    margin-bottom: 0;
    vertical-align: middle;
}
</style>
    <div class="col-md-12 center-block" style="float: none;">
<?php adminpermission('tixian', 1);?>
<div class="block">
<div class="block-title clearfix">
<h2>余额提现列表</h2><?php if($conf['fenzhan_daifu']>0){?><a class="btn btn-xs btn-info pull-right" href="javascript:config()">自动转账配置</a>&nbsp;<a class="btn btn-xs btn-primary pull-right" href="javascript:pl_config()">批量转账选中记录</a><?php }?>
</div>
<form onsubmit="return searchOrder()" method="GET" class="form-inline">
	<input type="hidden" name="zid" value="<?php echo @$_GET['zid']?>">
	<div class="form-group">
		<input type="text" placeholder="请输入要搜索的提现账号或者姓名" name="kw" class="form-control" style="min-width: 240px;">
		<select name="status" class="form-control"><option value="-1">全部状态</option><option value="0">未完成</option><option value="1">已完成</option><option value="2">失败</option></select>
		<select name="type" class="form-control"><option value="-1">全部方式</option><option value="2">QQ钱包</option><option value="1">微信</option><option value="0">支付宝</option></select>
	</div>
	<button type="submit" id="search_submit" class="btn btn-primary">搜索</button>&nbsp;
	<a href="javascript:clearOrder()" class="btn btn-default" title="刷新提现列表"><i class="fa fa-refresh"></i></a>
</form>
<div id="listTable"></div>
    </div>
  </div>
</div>
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script src="assets/js/tixian.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>