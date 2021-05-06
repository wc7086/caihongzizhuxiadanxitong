<?php
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
adminpermission('tixian', 1);

function display_zt($zt,$id){
	if($zt==2)
		return '<a onclick="setResult('.$id.')" title="点此修改失败原因"><font color=red>失败</font></a>';
	elseif($zt==1)
		return '<font color=green>已完成</font>';
	else
		return '<font color=blue>未完成</font>';
}
function display_type($type){
	if($type==1)
		return '微信';
	elseif($type==2)
		return 'QQ钱包';
	else
		return '支付宝';
}

$sql=" 1";
$link="";
if(isset($_GET['status']) && $_GET['status']!='-1'){
	$status = intval($_GET['status']);
	$sql.=" AND status='$status'";
	$link.='&status='.$status;
}
if(isset($_GET['type']) && $_GET['type']!='-1'){
	$type = intval($_GET['type']);
	$sql.=" AND pay_type='$type'";
	$link.='&type='.$type;
}
if(isset($_GET['kw'])){
	$kw = daddslashes($_GET['kw']);
	$sql.=" AND (`pay_account`='$kw' or `pay_name`='$kw')";
	$link.='&kw='.$_GET['kw'];
}
$numrows=$DB->getColumn("SELECT count(*) from pre_tixian WHERE{$sql}");
?>
	<div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th><input type="checkbox" id="checkbox" value="" /></th><th>ID</th><th>ZID</th><th>金额</th><th>实际到账</th><th>提现方式</th><th>提现账号</th><th>姓名</th><?php echo $conf['fenzhan_skimg']==1?'<th>收款图</th>':null;?><th>申请时间</th><th>完成时间</th><th>状态</th><th>信息</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM pre_tixian WHERE{$sql} ORDER BY id DESC limit $offset,$pagesize");
while($res = $rs->fetch())
{
	$addstr='';
	if($res['status']==0){
		$addstr='<a href="javascript:operation('.$res['id'].',\'complete\')" class="btn btn-success btn-xs">完成</a>&nbsp;'.($conf['fenzhan_daifu']>0&&isset($_SESSION['transfer'])?'<a href="javascript:transfer('.$res['id'].')" class="btn btn-primary btn-xs transfer_do">转账</a>&nbsp;':null).'<a href="javascript:setfail('.$res['id'].',\''.$res['money'].'\')" class="btn btn-xs btn-warning">失败</a>&nbsp;';
	}elseif($res['status']==1){
		$addstr='<a href="javascript:operation('.$res['id'].',\'reset\')" class="btn btn-info btn-xs">撤销</a>&nbsp;';
	}
echo '<tr data-id="'.$res['id'].'"><td width="10" style="vertical-align:middle;"><input type="checkbox" id="'.$res['id'].'" value="" name="data" /></td><td><b>'.$res['id'].'</b></td><td><a href="sitelist.php?zid='.$res['zid'].'" target="_blank">'.$res['zid'].'</a></td><td>'.$res['money'].'</td><td>'.$res['realmoney'].'</td><td>'.display_type($res['pay_type']).'</td><td><span onclick="inputInfo('.$res['id'].')" title="修改信息">'.$res['pay_account'].'</span></td><td><span onclick="inputInfo('.$res['id'].')" title="修改信息">'.$res['pay_name'].'</span></td>'.($conf['fenzhan_skimg']==1?'<td><a href="javascript:skimg('.$res['zid'].')">点击查看</a></td>':null).'<td>'.$res['addtime'].'</td><td>'.($res['status']==1?$res['endtime']:null).'</td><td class="status">'.display_zt($res['status'],$res['id']).'</td><td class="transfer_info"></td><td class="op">'.$addstr.'<a href="./record.php?zid='.$res['zid'].'" class="btn btn-default btn-xs" target="_blank">明细</a>&nbsp;<a href="javascript:delItem('.$res['id'].')" class="btn btn-xs btn-danger">删除</a></td></tr>';
}
?>
          </tbody>
        </table>
      </div>
<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$first.$link.'\')">首页</a></li>';
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$prev.$link.'\')">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-10>1?$page-10:1;
$end=$page+10<$pages?$page+10:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$i.$link.'\')">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$i.$link.'\')">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$next.$link.'\')">&raquo;</a></li>';
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$last.$link.'\')">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
?>
  <script type="text/html" id="config">
	<div class="panel-body">
	  <form action="" id="editform" method="post" class="" role="form">
		<div class="alert alert-success">平台地址：<a href="http://www.fcypay.com" target="_blank" rel="noreferrer">www.fcypay.com</a><br>安全起见 每次重启打开浏览器都需重新设置支付密码</div>
		<div class="form-group">
		  <label class="control-label">Api_Id</label>
		  <input type="text" name="id" value="<?php echo (isset($conf['transfer_id'])?$conf['transfer_id']:'');?>" class="form-control" placeholder="对接ID"/>
		</div>
		<div class="form-group">
		  <label class="control-label">Api_Key</label>
		  <input type="text" name="key" value="<?php echo (isset($conf['transfer_key'])?$conf['transfer_key']:'');?>" class="form-control" placeholder="对接Key"/>
		</div>
		<div class="form-group">
		  <label class="control-label">支付密码</label>
		  <input type="text" name="pass" value="" class="form-control" placeholder="对接支付密码"/>
		</div>
		<div class="form-group">
		  <label class="control-label">汇款选项</label>
			<select name="check" class="form-control">
				<option value="FORCE_CHECK" <?php echo isset($conf['transfer_check']) && $conf['transfer_check'] == 'FORCE_CHECK'?'selected="selected"':'';?> >验证用户账号与姓名实名一致</option>
				<option value="NO_CHECK" <?php echo isset($conf['transfer_check']) && $conf['transfer_check'] == 'NO_CHECK'?'selected="selected"':'';?> >不验证真实姓名</option>
			</select>
		</div>
		<div class="form-group">
		  <input type="button" value="修改" onclick="configEdit();" class="btn btn-primary form-control"/><br/>
		</div>
	  </form>
	</div>
  </script>
<script type="text/javascript">
var transfer = function(id){
	var transfer = '<?php echo (isset($_SESSION['transfer'])?$_SESSION['transfer']:'')?>';
	if (!transfer) {
		layer.alert('请先配置信息');
		return false;
	}
	var load= layer.load();
	$.ajax({
		type: "POST",
		url: "./ajax.php?act=transfer",
		data: {"id":id},
		dataType: "json",
		success: function(res){
			layer.close(load);
			layer.alert(res.msg,function(index){
				layer.close(index);
				if (res.code) listTable();
			});
		},error: function(){
			layer.close(load);
			layer.msg('服务器连接失败');
		}
	});
}
var config = function(){
	var html = $("#config").html()
	layer.open({
		type:1,
		title:"自动转账信息配置",
		content:html
	})
}
var configEdit = function () {
	$.ajax({
		type: "POST",
		url: "./ajax.php?act=transfer_config",
		data: $("#editform").serialize(),
		dataType: "json",
		success: function(res){
			layer.alert(res.msg,function(index){
				if (res.code) {
					location.reload();
				}else{
					layer.close(index);
				}
			});
		}
	});
}
var pl_config = function () {
	var transfer = '<?php echo (isset($_SESSION['transfer'])?$_SESSION['transfer']:'')?>';
	if (!transfer) {
		layer.alert('请先配置信息');
		return false;
	}
	if(confirm('确认批量转账？不可取消')){
		var id='';
		var arrChk=$("input[name='data']:checked"); 
		if(arrChk.length<=0){
			alert('请先勾选数据');
			return false;
		}
		$(arrChk).each(function(){ 
			var id = this.id;
			$("table").find('tr[data-id="'+id+'"]').find('.transfer_info').html('<font color="red">转账中....</font>');
		});
		$(arrChk).each(function(){ 
			var id = this.id;
			$.ajax({
				type: "POST",
				url: "./ajax.php?act=transfer",
				data: {"id":id},
				dataType: "json",
				async: true,
				success: function(res){
					layer.close(load);
					if (res.code) {
						$("table").find('tr[data-id="'+id+'"]').find('.status').html("<font color='green'>已完成</font>");
						$("table").find('tr[data-id="'+id+'"]').find('.transfer_do').hide();
						var html = '<font color="green">'+res.msg+'</font>';
					}else{
						var html = '<font color="red">'+res.msg+'</font>';
					}
					$("table").find('tr[data-id="'+id+'"]').find('.transfer_info').html(html);
				},error: function(){
					layer.close(load);
					layer.msg('服务器连接失败');
				}
			});
		});
		return false;
	}else{
		return false;
	}
	var load= layer.load();
}
$(document).ready(function(){
	var checkboxes = document.getElementsByName('data');
	$("#checkbox").click(function() {
		for (var i = 0; i < checkboxes.length; i++) {
			var checkbox = checkboxes[i];
			if (!$(this).get(0).checked) {
				checkbox.checked = false;
			} else {
				checkbox.checked = true;
			}
		}
	});
})
</script>