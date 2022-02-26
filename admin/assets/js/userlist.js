function listTable(query){
	var url = window.document.location.href.toString();
	var queryString = url.split("?")[1];
	query = query || queryString;
	if(query == 'start' || query == undefined){
		query = '';
		history.replaceState({}, null, './userlist.php');
	}else if(query != undefined){
		history.replaceState({}, null, './userlist.php?'+query);
	}
	layer.closeAll();
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'userlist-table.php?'+query,
		dataType : 'html',
		cache : false,
		success : function(data) {
			layer.close(ii);
			$("#listTable").html(data)
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function showRecharge(zid) {
	$("input[name='zid']").val(zid);
	$('#modal-rmb').modal('show');
}
function setActive(zid,active) {
	$.ajax({
		type : 'GET',
		url : 'ajax_site.php?act=setSite&zid='+zid+'&active='+active,
		dataType : 'json',
		success : function(data) {
			listTable();
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function delUser(zid) {
	var confirmobj = layer.confirm('你确实要删除此用户吗？', {
	  btn: ['确定','取消']
	}, function(){
	  $.ajax({
		type : 'GET',
		url : 'ajax_site.php?act=delSite&zid='+zid,
		dataType : 'json',
		success : function(data) {
			if(data.code == 0){
				layer.msg('删除成功');
				listTable();
			}else{
				layer.alert(data.msg,{icon:0});
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	  });
	}, function(){
	  layer.close(confirmobj);
	});
}
$(document).ready(function(){
	$("#recharge").click(function(){
		var zid=$("input[name='zid']").val();
		var actdo=$("select[name='do']").val();
		var rmb=$("input[name='rmb']").val();
		var remark=$("input[name='remark']").val();
		if(rmb==''){layer.alert('请输入金额');return false;}
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax_site.php?act=siteRecharge",
			data : {zid:zid,actdo:actdo,rmb:rmb,remark:remark},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					layer.msg('修改余额成功');
					$('#modal-rmb').modal('hide');
					listTable();
				}else{
					layer.alert(data.msg);
				}
			},
			error:function(data){
				layer.msg('服务器错误');
				return false;
			}
		});
	});
	$("#search_submit").click(function(){
		var kw=$("input[name='kw']").val();
		$("#search").modal('hide');
		if(kw == ''){
			listTable('start');
		}else{
			listTable('kw='+kw);
		}
	});
	$("#tabSort").change(function(){
		if($(this).val() == '0'){
			listTable('sort=0');
		}else if($(this).val() == '1'){
			listTable('sort=1');
		}else{
			listTable('start');
		}
	});
});
$(document).ready(function(){
	listTable();
})