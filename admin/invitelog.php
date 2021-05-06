<?php
/**
 * 推广记录
**/
include("../includes/common.php");
$title='推广记录';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
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
    <input type="text" class="form-control" name="kw" placeholder="请输入查单QQ">
  </div>
  <button type="submit" class="btn btn-info">搜索</button>&nbsp;
  <a href="javascript:listTable('start')" class="btn btn-default" title="刷新推广记录列表"><i class="fa fa-refresh"></i></a>
</form>

<div id="listTable"></div>
    </div>
  </div>
      
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script src="assets/js/invitelog.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>