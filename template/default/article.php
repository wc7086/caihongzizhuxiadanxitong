<?php
if(!defined('IN_CRONLITE'))exit();

$id=isset($_GET['id'])?intval($_GET['id']):sysmsg('文章ID不存在');
$row=$DB->getRow("select * from pre_article where id='$id' and active=1 limit 1");
if(!$row)
	sysmsg('当前文章不存在！');
$downResult = $DB->getRow("SELECT * FROM pre_article WHERE id<'$id' AND active=1 ORDER BY id DESC LIMIT 1");
$upResult = $DB->getRow("SELECT * FROM pre_article WHERE id>'$id' AND active=1 ORDER BY id DESC LIMIT 1");
$DB->exec("UPDATE `pre_article` SET `count`=`count`+1 WHERE id='$id'");
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title><?php echo $row['title']?> - <?php echo $conf['sitename']?></title>
	<meta name="description" content="<?php echo $row['description']?>"/>
    <meta name="keywords" content="<?php echo $row['keywords']?>"/>
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
        .article-content img {
            max-width: 100% !important;
        }
    </style>
</head>
<body>
<br/>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
    <div class="block">
        <div class="block-title">
            <h2><i class="fa fa-list"></i>&nbsp;&nbsp;<b>文章内容</b></h2>
        </div>
<ol class="breadcrumb">
	<li>
		<a href="./">首页</a>
	</li>
	<li>
		<a href="<?php echo article_url()?>">文章列表</a>
	</li>
	<li class="active"><?php echo $row['title']?></li>
</ol>
<div class="text-center">
<h3><strong><?php echo $row['title']?></strong></h3>
<span class="text-muted"><i class="glyphicon glyphicon-time"></i>&nbsp;<?php echo $row['addtime']?>&nbsp;&nbsp;&nbsp;<i class="fa fa-mouse-pointer" aria-hidden="true"></i>&nbsp;<?php echo $row['count']?></span>
</div><hr/>
<div class="article-content">
<?php echo $row['content']?>
</div>

		<div style="margin-bottom: 10px;margin-top: 10px;">
            <p style="margin: 0;">
                上一篇：<?php echo empty($upResult) ? '没有了~' : ('<a href="'.article_url($upResult['id']).'">' . $upResult['title'] . '</a>'); ?>
            </p>
            <p style="margin: 0;">
                下一篇：<?php echo empty($downResult) ? '没有了~' : ('<a href="'.article_url($downResult['id']).'">' . $downResult['title'] . '</a>'); ?>
            </p>
        </div>
			<hr>
			<div class="form-group">
			<a href="./" class="btn btn-primary btn-rounded"><i class="fa fa-home"></i>&nbsp;返回首页</a>
			<a href="<?php echo article_url()?>" class="btn btn-info btn-rounded" style="float:right;"><i class="fa fa-list"></i>&nbsp;返回列表</a>
			</div>
        </div>
      </div>
    </div>
  </div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
</body>
</html>