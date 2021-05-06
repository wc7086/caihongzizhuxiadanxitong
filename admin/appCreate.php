<?php
/**
 * APP在线生成
**/
include("../includes/common.php");
$title='APP在线生成';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

$url = (is_https() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'];
?>
<style>
#orderItem .orderTitle{word-break:keep-all;}
#orderItem .orderContent{word-break:break-all;}
</style>
	<div class="col-sm-12 col-md-10 col-lg-8 center-block" style="float: none;">
<div class="block">
<div class="block-title"><ul class="nav nav-tabs">
  <li class="active"><a href="#set" data-toggle="tab" aria-expanded="true">APP生成配置</a></li>
  <li><a href="#create" data-toggle="tab" aria-expanded="true">生成APP</a></li>
  <li><a href="#query" data-toggle="tab" aria-expanded="true" onclick="querytask()">我的生成</a></li>
  <li><a href="#other" data-toggle="tab" aria-expanded="true">其他</a></li>
</ul></div>
<div class="">
    <div id="myTabContent" class="tab-content">

<div class="tab-pane fade in active" id="set">
<form onsubmit="return saveSetting(this)" method="post" class="form-horizontal form-bordered" role="form">
	<div class="form-group">
	  <label class="col-sm-3 control-label">分站自助生成APP功能</label>
	  <div class="col-sm-9"><select class="form-control" name="appcreate_open" default="<?php echo $conf['appcreate_open']?>"><option value="0">关闭</option><option value="1">开启</option></select></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-3 control-label">APP生成平台密钥</label>
	  <div class="col-sm-9"><div class="input-group"><input type="text" name="appcreate_key" value="<?php echo $conf['appcreate_key']; ?>" class="form-control"/><span class="input-group-btn"><a href="http://user.997665.cn/" class="btn btn-default" target="_blank" rel="noreferrer">获取密钥</a></span></div></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-3 control-label">允许自定义图标和启动图</label>
	  <div class="col-sm-9"><select class="form-control" name="appcreate_diy" default="<?php echo $conf['appcreate_diy']?>"><option value="0">否</option><option value="1">是</option></select></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-3 control-label">APP生成价格(普及版)</label>
	  <div class="col-sm-9"><input type="text" name="appcreate_price" value="<?php echo $conf['appcreate_price']; ?>" class="form-control" placeholder="填写0或留空则免费生成APP"/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-3 control-label">APP生成价格(专业版)</label>
	  <div class="col-sm-9"><input type="text" name="appcreate_price2" value="<?php echo $conf['appcreate_price2']; ?>" class="form-control" placeholder="填写0或留空则免费生成APP"/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-3 control-label">APP默认主题颜色</label>
	  <div class="col-sm-9"><select class="form-control" name="appcreate_theme" default="<?php echo $conf['appcreate_theme']?>"><option value="#00A7AA">绿色</option><option value="#FFB6C1">浅粉丝</option><option value="#00BFFF">深蓝色</option><option value="#FF4500">橙红色</option><option value="#4169E1">皇家蓝</option></select></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-3 control-label">APP是否隐藏下方导航栏</label>
	  <div class="col-sm-9"><select class="form-control" name="appcreate_nonav" default="<?php echo $conf['appcreate_nonav']?>"><option value="0">否</option><option value="1">是</option></select></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-3 control-label">分站未配置APP下载地址默认使用主站的</label>
	  <div class="col-sm-9"><select class="form-control" name="appcreate_default" default="<?php echo $conf['appcreate_default']?>"><option value="0">否</option><option value="1">是</option></select></div>
	</div>
	<div class="form-group">
	  <div class="col-sm-offset-3 col-sm-9"><input type="submit" name="submit" value="修改" class="btn btn-primary btn-block"/>
	 </div>
	</div>
	<div class="panel-footer"><span class="glyphicon glyphicon-info-sign"></span>APP自动化打包平台：<a href="http://user.997665.cn/" target="_blank" rel="noreferrer">点此进入</a></div>
  </form>
</div>
		<div class="tab-pane fade in" id="create">
			<form method="post" role="form">
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">
						应用名称
					</div>
					<input name="name" class="form-control" value="<?php echo $conf['sitename']?>" maxlength="12"/>
				</div>
			</div>
            <div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">
						应用网址
					</div>
                    <input name="url" class="form-control" value="<?php echo $url?>"/>
				</div>
			</div>
            <div class="form-group">
                <input type="file" id="file_icon" onchange="fileUpload(this, 'icon')" style="display:none;"/>
				<div class="input-group">
					<div class="input-group-addon">
						应用图标
					</div>
					<input name="icon" class="form-control" value="" placeholder="不上传则使用默认应用图标" data-fileid="" disabled/>
                    <div class="input-group-btn"><a href="javascript:fileSelect('file_icon')" class="btn btn-success" title="上传图片"><i class="glyphicon glyphicon-upload"></i></a></div>
				</div>
			</div>
            <div class="form-group">
                <input type="file" id="file_background" onchange="fileUpload(this, 'background')" style="display:none;"/>
				<div class="input-group">
					<div class="input-group-addon">
						应用启动图
					</div>
					<input name="background" class="form-control" value="" placeholder="不上传则使用默认应用启动图" data-fileid="" disabled/>
                    <div class="input-group-btn"><a href="javascript:fileSelect('file_background')" class="btn btn-success" title="上传图片"><i class="glyphicon glyphicon-upload"></i></a></div>
				</div>
			</div>
			<div class="form-group">
				<input type="button" id="submit" value="立即生成" class="btn btn-primary btn-block"/>
			</div>
			</form>
        </div>
        <div class="tab-pane fade in" id="query">
			<div class="form-group">
				<div class="input-group">
				<input type="text" name="queryurl" value="<?php echo $qq?>" class="form-control" placeholder="请输入要查询的应用网址（留空则为上一次生成的应用网址）" onkeydown="if(event.keyCode==13){submit_query.click()}"/>
				<span class="input-group-btn"><input type="submit" id="submit_query" class="btn btn-primary btn-block" value="立即查询"></span>
			</div></div>
            <div id="result">
            </div>
        </div>
		<div class="tab-pane fade in" id="other">
			<div class="form-group">
				<input type="button" id="cleanApp" value="清空全部已生成的APP链接" class="btn btn-danger btn-block btn-sm"/>
				<br/><font color="green">此功能是当APP下载链接集体失效的时候，点击“刷新全部生成的APP状态”后，可以强制重新获取每个APP的下载链接。</font>
			</div>
        </div>
    </div>
</div>
</div>
</div>
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script>
function checkURL(name)
{
	var url = $("input[name='"+name+"']").val();
	if(url == "")return;

	if (url.indexOf(" ")>=0){
		url = url.replace(/ /g,"");
	}
	if (url.toLowerCase().indexOf("http://")<0 && url.toLowerCase().indexOf("https://")<0){
		url = "http://"+url;
	}
	if (url.slice(url.length-1)=="/"){
		url = url.substring(0,url.length-1);
	}
	$("input[name='"+name+"']").val(url);
}
var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default")||0);
}
function saveSetting(obj){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'ajax.php?act=set',
		data : $(obj).serialize(),
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.alert('设置保存成功！', {
					icon: 1,
					closeBtn: false
				}, function(){
				  window.location.reload()
				});
			}else{
				layer.alert(data.msg, {icon: 2})
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
	return false;
}
function fileSelect(obj){
	$("#"+obj).trigger("click");
}
function fileUpload(obj, des){
	var fileObj = $(obj)[0].files[0];
	if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
		return;
	}
	var formData = new FormData();
	formData.append("file",fileObj);
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		url: "ajax_app.php?act=app_upload",
		data: formData,
		type: "POST",
		dataType: "json",
		cache: false,
		processData: false,
		contentType: false,
		success: function (data) {
			layer.close(ii);
			if(data.code == 0){
				$("input[name='"+des+"']").val('图片上传成功('+data.fileid+')');
                $("input[name='"+des+"']").attr('data-fileid', data.fileid);
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	})
}
    function querytask(){
		checkURL('queryurl');
		var url = $("input[name='queryurl']").val();
        var ii = layer.load(0);
        $("#result").hide();
        $.ajax({
            type : 'POST',
            url : 'ajax_app.php?act=app_query',
			data : {url:url},
            dataType : 'json',
            success : function(data) {
                layer.close(ii);
                if(data.code == 0){
                    var item = '<table class="table table-hover" id="orderItem">';
				    item += '<tr><td colspan="6" style="text-align:center" class="orderTitle"><b>APP生成任务结果<a href="javascript:querytask()" class="pull-right btn btn-xs btn-default"><i class="fa fa-refresh"></i>&nbsp;刷新</a></b></td></tr><tr><td class="info orderTitle">应用名称</td><td colspan="5" class="orderContent">'+data.data.name+'</td></tr><tr><td class="info orderTitle">应用网址</td><td colspan="5" class="orderContent">'+data.url+'</td></tr></tr><tr><td class="info orderTitle">创建时间</td><td colspan="5" class="orderContent">'+data.data.created_at+'</td></tr><tr><td class="info orderTitle">任务状态</td><td colspan="5" class="orderContent">'+(data.data.status==1?'<span class="label label-success">成功</span>':data.data.status==-1?'<span class="label label-danger">打包失败</span>':'<span class="label label-warning">正在打包，请稍候点击刷新按钮查看</span>')+'</td></tr>';
                    if(data.data.status==1){
                        item += '<tr><td class="info orderTitle">双端下载页面</td><td colspan="5" class="orderContent"><a href="'+data.download_url+'" target="_blank" style="color:blue">'+data.download_url_show+'</a><br/></td></tr>';
						item += '<tr><td class="info orderTitle">安卓APP下载</td><td colspan="5" class="orderContent"><a href="'+data.android_url+'" target="_blank" style="color:blue">'+data.android_url+'</a></tr>';
						item += '<tr><td class="info orderTitle">iOS APP下载</td><td colspan="5" class="orderContent"><a href="'+data.download_url+'" target="_blank" style="color:blue">'+data.download_url_show+'</a>（必须Safari访问）</tr>';
                        if(navigator.userAgent.indexOf('Windows')>-1){
                            item += '<tr><td class="info orderTitle">扫码下载</td><td colspan="5" class="orderContent"><img style="box-shadow: 3px 3px 16px #eee" src="//api.qrserver.com/v1/create-qr-code/?size=150x150&margin=10&data='+encodeURIComponent(data.download_url_show)+'"></td></tr>';
                        }
                    }
                    item += '</table>';
                    $("#result").html(item);
                    $("#result").show();
                }else{
                    item = '<div class="alert alert-danger"><i class="glyphicon glyphicon-info-sign"></i>&nbsp;'+data.msg+'</div>';
                    $("#result").html(item);
                    $("#result").show();
                }
            }
        });
    }
$(document).ready(function(){
    $("#submit").click(function(){
		checkURL('url');
		var name = $("input[name='name']").val();
		var url = $("input[name='url']").val();
        var icon = $("input[name='icon']").attr('data-fileid');
        var background = $("input[name='background']").attr('data-fileid');
		if(name==''){layer.alert('应用名称不能为空！');return false;}
        var confirmobj = layer.confirm('请确认你的APP信息↓<br/>应用名称：<font color="blue">'+name+'</font><br/>应用网址：<font color="blue">'+url+'</font>', {
            btn: ['确定','取消']
        }, function(){
            var ii = layer.load(0);
            $.ajax({
                type : 'POST',
                url : 'ajax_app.php?act=app_submit',
                data : {name: name, url: url, icon: icon, background: background},
                dataType : 'json',
                success : function(data) {
                    layer.close(ii);
                    if(data.code == 0){
                        layer.alert(data.msg, {icon:1});
                    }else{
                        layer.alert(data.msg, {icon:2});
                    }
                }
            });
        }, function(){
            layer.close(confirmobj);
        });
	});
	$("#submit_query").click(function(){
		querytask();
	});
	$("#cleanApp").click(function(){
        var confirmobj = layer.confirm('是否确定清空全部已生成的APP链接？', {
            btn: ['确定','取消']
        }, function(){
            var ii = layer.load(0);
            $.ajax({
                type : 'POST',
                url : 'ajax_app.php?act=app_clean',
                data : {action: 'yes'},
                dataType : 'json',
                success : function(data) {
                    layer.close(ii);
                    if(data.code == 0){
                        layer.alert(data.msg, {icon:1});
                    }else{
                        layer.alert(data.msg, {icon:2});
                    }
                }
            });
        }, function(){
            layer.close(confirmobj);
        });
	});
})
</script>
</body>
</html>