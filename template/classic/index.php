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
  <link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css?ver=<?php echo VERSION ?>">
  <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<?php echo $background_css?>
</head>
<body>
<div class="modal fade" align="left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $conf['sitename']?></h4>
      </div>
      <div class="modal-body">
	  <?php echo $conf['modal']?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<br/>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
<div class="panel panel-default">
	<div class="panel-body" style="text-align: center;">
		<img src="<?php echo $logo?>" style="max-width: 100%;">
	</div>
</div>
<div class="panel panel-info">
<div class="list-group-item reed" style="background:#64b2ca;"><h3 class="panel-title"><font color="#fff"><i class="fa fa-volume-up"></i>&nbsp;&nbsp;<b>????????????</b></font></h3></div>
<?php echo $conf['anounce']?>
</div>

<div class="panel panel-info">
<div class="list-group-item reed" style="background:#64b2ca;"><h3 class="panel-title"><font color="#fff"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;<b>????????????</b></font></h3></div>
	<ul class="nav nav-tabs">
		<li class="active"><a href="#onlinebuy" data-toggle="tab">??????</a></li>
		<li><a href="#query" data-toggle="tab" id="tab-query">??????</a></li>
		<?php if($conf['gift_open']==1){?><li><a href="#gift" data-toggle="tab">??????</a></li><?php }?>
		<?php if(!empty($conf['daiguaurl'])){?><li><a href="./?mod=daigua">??????</a></li><?php }?>
		<?php if($conf['fenzhan_buy']==1){?><li><a href="./user/regsite.php" style="color:red">????????????</a></li>
		<?php if($islogin2==1){?>
		<li><a href="./user/">????????????</a></li>
		<?php }else{?>
		<li><a href="./user/login.php">????????????</a></li>
		<?php }?>
		<?php }?>
	</ul>
	<div class="list-group-item">
		<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="onlinebuy">
<?php include TEMPLATE_ROOT.'default/shop.inc.php'; ?>
		</div>
		<div class="tab-pane fade in" id="query">
			<div class="alert alert-info" <?php if(empty($conf['gg_search'])){?>style="display:none;"<?php }?>><?php echo $conf['gg_search']?></div>
			<div class="form-group">
				<div class="input-group">
				<div class="input-group-btn">
					<select class="form-control" id="searchtype" style="padding: 6px 4px;width:90px"><option value="0">????????????</option><option value="1">?????????</option></select>
				</div>
				<input type="text" name="qq" id="qq3" value="<?php echo $qq?>" class="form-control" placeholder="????????????????????????????????????????????????????????????" onkeydown="if(event.keyCode==13){submit_query.click()}" required/>
				<span class="input-group-btn"><a tabindex="0" class="btn btn-default" role="button" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" title="????????????????????????" data-content="???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????"><i class="glyphicon glyphicon-exclamation-sign"></i></a></span>
			</div></div>
			<input type="submit" id="submit_query" class="btn btn-primary btn-block" value="????????????">
			<div id="result2" class="form-group" style="display:none;">
			<div class="table-responsive">
				<table class="table table-striped">
				<thead><tr><th>????????????</th><th>????????????</th><th>??????</th><th class="hidden-xs">????????????</th><th>??????</th><th>??????</th></tr></thead>
				<tbody id="list">
				</tbody>
				</table>
			</div>
			</div>
		</div>
		<?php if($conf['gift_open']==1){?><div class="tab-pane fade in" id="gift">
			<div class="panel-body text-center">
			<div id="roll">??????????????????????????????</div>
			<hr>
			<p>
			<a class="btn btn-info" id="start" style="display:block;">????????????</a>
			<a class="btn btn-danger" id="stop" style="display:none;">??????</a>
			</p> 
			<div id="result"></div><br/>
			<div class="giftlist" style="display:none;"><strong>??????????????????</strong><ul id="pst_1"></ul></div> 
			</div>
		</div>
		</div><?php }?>
	</div>
</div>

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
<!--????????????-->
<div class="panel panel-info">
<div class="list-group-item reed" style="background:#64b2ca;"><h3 class="panel-title"><font color="#fff"><i class="fa fa-newspaper-o"></i>&nbsp;&nbsp;<b>????????????</b></font></h3></div>
	<?php foreach($msgrow as $row){
	echo '<a target="_blank" style="color:blue" class="list-group-item" href="'.article_url($row['id']).'"><span class="btn btn-'.$class_arr[($i++)%5].' btn-xs">'.$i.'</span>&nbsp;'.$row['title'].'</a>';
	}?>
	<a href="<?php echo article_url()?>" title="??????????????????" class="btn-default btn btn-block" target="_blank">??????????????????</a>
</div>
<!--????????????-->
<?php }?>

<div class="panel panel-info" <?php if($conf['hide_tongji']==1){?>style="display:none;"<?php }?>>
<div class="list-group-item reed" style="background:#64b2ca;"><h3 class="panel-title"><font color="#fff"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;<b>????????????</b></font></h3></div>
<table class="table table-bordered">
<tbody>
<tr>
	<td align="center"><font color="#808080"><b>??????????????????</b></br><i class="fa fa-hourglass-2 fa-2x"></i></br><span id="count_yxts"></span>???</font></td>
	<td align="center"><font color="#808080"><b>??????????????????</b></br><span class="fa fa-shopping-cart fa-2x"></span></br><span id="count_orders"></span>???</font></td>
</tr>
<tr height=50>
         <td align="center"><font color="#808080"><b>??????????????????</b></br><i class="fa fa-check-square-o fa-2x"></i></span></br><span id="count_orders1"></span>???</font></td>
	<td align="center"><font color="#808080"><b>??????????????????</b></br><i class="fa fa-internet-explorer fa-2x"></i></span></br><span id="counter">1</span>???</font></td>
<tbody>
</table>
</div>

<div class="panel panel-info" <?php if($conf['bottom']==''){?>style="display:none;"<?php }?>>
<?php echo $conf['bottom']?>
</div>
<p style="text-align:center"><span style="font-weight:bold">CopyRight <i class="fa fa-heart text-danger"></i> <?php echo date("Y")?> <a href="/"><?php echo $conf['sitename']?></a></span><br/><?php echo $conf['footer']?></p></div>
</div>
<!--????????????-->
<div id="audio-play" <?php if(empty($conf['musicurl'])){?>style="display:none;"<?php }?>>
  <div id="audio-btn" class="on" onclick="audio_init.changeClass(this,'media')">
    <audio loop="loop" src="<?php echo $conf['musicurl']?>" id="media" preload="preload"></audio>
  </div>
</div>
<!--????????????-->
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>

<script type="text/javascript">
var isModal=<?php echo empty($conf['modal'])?'false':'true';?>;
var homepage=true;
var hashsalt=<?php echo $addsalt_js?>;
$(function() {
	$("img.lazy").lazyload({effect: "fadeIn"});
	$('a[data-toggle="popover"]').popover();
});
</script>
<script src="assets/js/main.js?ver=<?php echo VERSION ?>"></script>
<?php if($conf['classblock']==1 || $conf['classblock']==2 && checkmobile()==false)include TEMPLATE_ROOT.'default/classblock.inc.php'; ?>
</body>
</html>