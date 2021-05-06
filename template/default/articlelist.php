<?php
if(!defined('IN_CRONLITE'))exit();
$kw=!empty($_GET['kw'])?trim(daddslashes($_GET['kw'])):null;
if($kw){
	$sql=" title LIKE '%$kw%'";
	$link="&kw=".$kw;
}else{
	$sql=" 1";
}
$msgcount=$DB->getColumn("SELECT count(*) FROM pre_article WHERE active=1");
$pagesize=10;
$pages=ceil($msgcount/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);
$rs=$DB->query("SELECT id,title,content,addtime FROM pre_article WHERE{$sql} AND active=1 ORDER BY top DESC,id DESC LIMIT $offset,$pagesize");
$msgrow=array();
while($res = $rs->fetch()){
	$msgrow[]=$res;
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title><?php echo $conf['sitename']?> - 文章列表</title>
    <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/plugins.css">
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/main.css">
    <script src="<?php echo $cdnpublic?>modernizr/2.8.3/modernizr.min.js"></script>
    <!--[if lt IE 9]>
      <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<?php echo $background_css?>
<style>
.onclick{cursor: pointer;touch-action: manipulation;}
#msglist a:hover, #msglist a:active, #msglist a {text-decoration: none;display: block;color: #337ab7;}
</style>
</head>
<body>
<br/>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
    <div class="block">
        <div class="block-title">
            <h2><i class="fa fa-list"></i>&nbsp;&nbsp;<b>文章列表</b></h2>
        </div>
		<div class="form-group">
			<div class="input-group"><div class="input-group-addon">搜索</div>
			<input type="text" name="kw" value="" class="form-control" placeholder="输入关键词" onkeydown="if(event.keyCode==13){doSearch.click()}" required/>
			<span class="input-group-addon btn" id="doSearch"><span class="glyphicon glyphicon-search" title="搜索"></span></span>
		</div></div>
		<div class="table-responsive">
			<table class="table table-hover table-bordered">
				<tbody id="msglist">
<?php
foreach($msgrow as $row){
	 $content = strip_tags($row['content']);
	 if (mb_strlen($content) > 80)
		 $content = mb_substr($content, 0, 80, 'utf-8') . '......';
	echo '<tr class="animation-fadeInQuick"><td><a href="'.article_url($row['id']).'"><div>
<b class="pull-left">'.strip_tags($row['title']).'</b>
<small class="pull-right"><span class="text-muted">'.$row['addtime'].'</span></small>
</div>
<br>
<p style="margin-bottom: 0;color: #6b6b6b;white-space: normal;word-break: break-all;word-wrap: break-word;">'.$content.'</p>
</a>
</td>
</tr>';
}
if($msgcount==0){
	echo '<tr><td class="text-center"><font color="grey">文章列表空空如也</font></td></tr>';
}
?>			
			</tbody>
        </table>
		<?php if($msgcount>$pagesize){
		if($page>1){
			echo '<a href="'.article_url(0, 'page='.($page-1).$link).'" class="btn btn-default">上一页</a>';
		}
		if($page<$pages){
			echo '<a href="'.article_url(0, 'page='.($page+1).$link).'" class="btn btn-default pull-right">下一页</a>';
		}
		}?>
			</div>
			<hr>
			<div class="form-group">
			<a href="./" class="btn btn-primary btn-rounded"><i class="fa fa-home"></i>&nbsp;返回首页</a>
			<a href="./user/" class="btn btn-info btn-rounded pull-right"><i class="fa fa-user"></i>&nbsp;用户中心</a>
			</div>
        </div>
      </div>
    </div>
  </div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script>
var $_GET = (function(){
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if(typeof(u[1]) == "string"){
        u = u[1].split("&");
        var get = {};
        for(var i in u){
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
})();
$(document).ready(function(){
if($_GET['kw']){
	$("input[name='kw']").val(decodeURIComponent($_GET['kw']))
}
$("#doSearch").click(function () {
	var kw = $("input[name='kw']").val();
	window.location.href="./?mod=articlelist&kw="+encodeURIComponent(kw);
});
});
</script>
</body>
</html>