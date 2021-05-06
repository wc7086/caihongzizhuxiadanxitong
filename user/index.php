<?php
require '../includes/common.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

if($_GET['mod']=='faka'){
	exit("<script language='javascript'>window.location.href='../?mod=faka&&id={$_GET['id']}&skey={$_GET['skey']}';</script>");
}
$title = '平台首页';
include 'head.php';
?>
<link rel="stylesheet" href="<?php echo $cdnpublic?>toastr.js/latest/css/toastr.min.css">
<style>
img.logo{width:14px;height:14px;margin:0 5px 0 3px;}
.span_position{display:inline;background:red;border-radius:50%;width:10px;height:10px;position:absolute}
.nickname{overflow: hidden;text-overflow: ellipsis;white-space: nowrap;max-width:100px;}
</style>
<div class="wrapper">
<div class="col-sm-12">
<?php
if($userrow['rmb']>4){
if(strlen($userrow['pwd'])<6 || is_numeric($userrow['pwd']) && strlen($userrow['pwd'])<=10 || $userrow['pwd']===$userrow['qq']){
	echo '<div class="alert alert-danger"><span class="btn-sm btn-danger">重要</span>&nbsp;你的密码过于简单，请不要使用较短的纯数字或自己的QQ号当做密码，以免造成资金损失！ <a href="uset.php?mod=user">点此修改密码</a></div>';
}elseif($userrow['user']===$userrow['pwd']){
	echo '<div class="alert alert-danger"><span class="btn-sm btn-danger">重要</span>&nbsp;你的用户名与密码相同，极易被黑客破解，请及时修改密码 <a href="uset.php?mod=user">点此修改密码</a></div>';
}
}
?>
</div>
	<div class="col-lg-4 col-md-6 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);padding: 15px;color: white;">
				<div class="widget-content text-right clearfix">
					<img src="<?php echo $faceimg ?>" alt="Avatar"
					width="66" class="img-circle img-thumbnail img-thumbnail-avatar pull-left">
					<h4><b>余额：<?php echo $userrow['rmb']?>元</b></h4>
					<span class="text-muted">
						<a href="recharge.php" class="btn btn-xs btn-success"><b>充值余额</b></a>&nbsp;<a href="tixian.php" class="btn btn-xs btn-info">申请提现</a>
					</span>
				</div>
			</div>
<table class="table">
	<tbody>
		<tr>
			<th class="text-center nickname">
				<font color="#a9a9a9">用户名</font><br><font size="4"><?php echo $nickname?></font>
			</th>
			<th class="text-center">
				<font color="#a9a9a9">UID</font><br><font size="4"><?php echo $userrow['zid']?></font>
			</th>
			<th class="text-center">
				<font color="#a9a9a9">今日收益</font><br><font size="4" id="income_today">0元</font>
			</th>
		</tr>
		<tr>
			<td><a href="<?php echo $userrow['power']>0?'./shop.php':'../';?>" class="btn btn-primary btn-block"><i class="fa fa-shopping-cart"></i><br/><b><?php echo $userrow['power']>0?'低价下单':'自助下单';?></b></a></td>
			<?php if($conf['qiandao_reward']){?>
			<td><a href="./qiandao.php" class="btn btn-success btn-block"><i class="fa fa-check-square"></i><br/><b>每日签到</b></a></td>
			<?php }else{?>
			<td><a href="recharge.php" class="btn btn-success btn-block"><i class="fa fa-money"></i><br/><b>充值余额</b></a></td>
			<?php }?>
			<td><a href="message.php" class="btn btn-primary btn-block"><i class="fa fa-bullhorn"></i><br/><b>站内通知</b><span id="message_count"></span></a></td>
		</tr>
		<tr>
			<td><a href="<?php echo $userrow['power']>0?'./shop.php?chadan=1':'../?chadan=1';?>" class="btn btn-info btn-block"><i class="fa fa-search"></i><br/><b>自助查单</b></a></td>
			<td><a href="./workorder.php" class="btn btn-warning btn-block"><i class="fa fa-check-square-o"></i><br/><b>我的工单</b><span id="work_count"></span></a></td>
			<td><a href="record.php" class="btn btn-info btn-block"><i class="fa fa-hashtag"></i><br/><b>收支明细</b></a></td>
		</tr>
		<?php if($userrow['power']>0){?>
		<tr>
			<td><a href="shoplist.php" class="btn btn-primary btn-block"><i class="fa fa-list-alt"></i><br/><b>商品管理</b></a></td>
			<td><a href="list.php" class="btn btn-info btn-block"><i class="fa fa-list"></i><br/><b>订单记录</b></a></td>
			<?php if($userrow['power']==2){?>
			<td><a href="sitelist.php" class="btn btn-primary btn-block"><i class="fa fa-sitemap"></i><br/><b>分站管理</b></a></td>
			<?php }else{?>
			<td><a href="login.php?logout" class="btn btn-danger btn-block"><i class="fa fa-sign-out"></i><br/><b>安全退出</b></a></td>
			<?php }?>
		</tr>
		<?php }?>
	</tbody>
</table>
	</div>
</div>

<div class="col-lg-4 col-md-6 col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading font-bold text-center" style="background: linear-gradient(to right,#14b7ff,#b221ff);"><h3 class="panel-title"><font color="#fff"><i class="fa fa-globe"></i>&nbsp;&nbsp;<b>我的站点信息</b></font></h3></div>
		<ul class="list-group no-radius">
		<?php if($userrow['power']>0){?>
			<li class="list-group-item"><b>通知提醒：</b>你当前有<font color="orange"><b id="tiaosu">0</b></font>条信息未阅读<a href="./message.php" class="btn btn-primary btn-xs pull-right">立即查看</a></li>
			<li style="font-weight:bold" class="list-group-item">我的域名：<a href="http://<?php echo $userrow['domain']?>/" target="_blank" rel="noreferrer"><?php echo $userrow['domain']?></a><a href="uset.php?mod=site" class="btn btn-info btn-xs pull-right">编辑信息</a></li>
			<?php if($conf['fanghong_api']){?>
			<li style="font-weight:bold;overflow: hidden;" class="list-group-item">防红链接：<a href="javascript:;" id="copy-btn" data-clipboard-text="" >Loading...</a>&nbsp;&nbsp;&nbsp;<span class="pull-right"><button class="btn btn-default btn-xs" id="recreate_url">重新生成</button>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="layer.alert('防红链接：该链接可以在QQ直接打开的您的网站，方便推广！<br />Tips：点击短网址即可复制哦~',{icon: 3,title: '小提示',skin: 'layui-layer-molv layui-layer-wxd'});" class="btn btn-info btn-xs">说明</a></span></li>
			<?php }?>
			<li style="font-weight:bold" class="list-group-item">网站名称：<font color="blue"><?php echo $userrow['sitename']?></font></li>
			<li style="font-weight:bold" class="list-group-item">站点类型：<?php echo ($userrow['power']==2?'<font color=red>专业版</font>':'<font color=red>普及版</font>')?>&nbsp;<?php if($conf['fenzhan_upgrade']>0 && $userrow['power']==1){echo '<a href="upsite.php" class="btn btn-danger btn-xs pull-right">升级站点</a>';}else{echo '<a href="./sitelist.php" class="btn btn-danger btn-xs pull-right">下级管理</a>';}?></li>
			<?php if($conf['fenzhan_expiry']>0){?>
			<li style="font-weight:bold" class="list-group-item">到期时间：<font color="orange"><?php echo $userrow['endtime']?></font> <a href="renew.php" class="btn btn-primary btn-xs pull-right">立即续期</a></li>
			<?php }?>
			<?php if($conf['appcreate_open']==1){?><li style="font-weight:bold" class="list-group-item">客户端APP：<?php echo ($userrow['appurl']?'<a href="'.$userrow['appurl'].'" target="_blank" style="color:#337ab7;">点击下载</a>':'<font color="grey">未生成</font>');?><a href="appCreate.php" class="btn btn-warning btn-xs pull-right">在线生成</a></li><?php }?>
	<?php }else{?>
	<li style="font-weight:bold" class="list-group-item">你还未开通分站<br/><a href="regsite.php" class="btn btn-primary btn-sm btn-block">点此开通分站</a></li>
	<?php }?>
	</ul>
	</div>
</div>

<?php if($userrow['power']>0 || $conf['user_level']==1){?>
	<div class="col-lg-4 col-md-6 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading font-bold"  style="background: linear-gradient(to right,#14b7ff,#b221ff);">
				<h3 class="panel-title"><font color="#fff"><i class="fa fa-volume-up"></i>&nbsp;&nbsp;<b>站点公告</b></font></h3>
			</div>
			<?php echo $conf['gg_panel']?>
		</div>
	</div>
<?php }?>
</div>
</div>
</div>
<?php include './foot.php';?>
<script src="<?php echo $cdnpublic?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script src="<?php echo $cdnpublic?>toastr.js/latest/toastr.min.js"></script>
<script>
$(document).ready(function(){
var clipboard = new Clipboard('#copy-btn');
clipboard.on('success', function (e) {
	layer.msg('复制成功！', {icon: 1});
});
clipboard.on('error', function (e) {
	layer.msg('复制失败，请长按链接后手动复制', {icon: 2});
});

$("#recreate_url").click(function(){
	var self = $(this);
	if (self.attr("data-lock") === "true") return;
	else self.attr("data-lock", "true");
	var ii = layer.load(1, {shade: [0.1, '#fff']});
	$.get("ajax_user.php?act=create_url&force=1", function(data) {
		layer.close(ii);
		if(data.code == 0){
			layer.msg('生成链接成功');
			$("#copy-btn").html(data.url);
			$("#copy-btn").attr('data-clipboard-text',data.url);
		}else{
			layer.alert(data.msg);
		}
		self.attr("data-lock", "false");
	}, 'json');
});
if(window.location.hash=='#chongzhi'){
	$("#userjs").modal('show');
}
	$.ajax({
		type : "GET",
		url : "ajax_user.php?act=msg",
		dataType : 'json',
		async: true,
		success : function(data) {
			if(data.code==0){
				if(data.count>0){
					$("#tiaosu").text(data.count);
					$("#message_count").addClass('span_position');
					toastr.info('<a href="message.php">您有<b>'+data.count+'</b>条新消息，请注意查收！</a>', '消息提醒');
				}
				if(data.count2>0){
					$("#work_count").addClass('span_position');
					toastr.warning('<a href="workorder.php">您有<b>'+data.count2+'</b>个工单已被管理员回复！</a>', '工单提醒');
				}
				$("#income_today").html(data.income_today+'元');
			}
		}
	});
	$.ajax({
		type : "GET",
		url : "ajax_user.php?act=create_url",
		dataType : 'json',
		async: true,
		success : function(data) {
			if(data.code == 0){
				$("#copy-btn").html(data.url);
				$("#copy-btn").attr('data-clipboard-text',data.url);
			}else{
				$("#copy-btn").html(data.msg);
			}
		}
	});
});
</script>
</body>
</html>