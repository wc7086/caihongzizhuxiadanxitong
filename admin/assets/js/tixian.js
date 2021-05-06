function listTable(query){
	var url = window.document.location.href.toString();
	var queryString = url.split("?")[1];
	query = query || queryString;
	if(query == 'start' || query == undefined){
		query = '';
		history.replaceState({}, null, './tixian.php');
	}else if(query != undefined){
		history.replaceState({}, null, './tixian.php?'+query);
	}
	layer.closeAll();
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'tixian-table.php?'+query,
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
function searchOrder(){
	var kw=$("input[name='kw']").val();
	var type=$("select[name='type']").val();
	var status=$("select[name='status']").val();
	if(kw!=''){
		listTable('kw='+kw+'&type='+type+'&status='+status);
	}else{
		listTable('type='+type+'&status='+status);
	}
	return false;
}
function clearOrder(){
	$("input[name='kw']").val('');
	$("select[name='type']").val(-1);
	$("select[name='status']").val(-1);
	listTable('start')
}
function inputInfo(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax_site.php?act=getTixian&id='+id,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.open({
				  type: 1,
				  title: '修改数据',
				  skin: 'layui-layer-rim',
				  content: data.data
				});
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function saveInfo(id) {
	var pay_type=$("#pay_type").val();
	var pay_account=$("#pay_account").val();
	var pay_name=$("#pay_name").val();
	if(pay_account=='' || pay_name==''){layer.alert('请确保每项不能为空！');return false;}
	$('#save').val('Loading');
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax_site.php?act=editTixian",
		data : {id:id,pay_type:pay_type,pay_account:pay_account,pay_name:pay_name},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('保存成功！');
				window.location.reload();
			}else{
				layer.alert(data.msg);
			}
			$('#save').val('保存');
		} 
	});
}
function skimg(zid){
	layer.open({
		type: 1,
		area: ['360px', '480px'],
		title: '站点'+zid+'的收款图查看',
		shade: 0.3,
		anim: 1,
		shadeClose: true, //开启遮罩关闭
		content: '<center><img width="300px" src="../assets/img/skimg/sk_'+zid+'.png"></center>'
	});
}
function setfail(id, money) {
	var confirmobj = layer.confirm('修改为失败状态并将'+money+'元退回到该分站余额吗？', {
	  btn: ['确定','取消']
	}, function(){
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax_site.php?act=opTixian",
			data : {id:id,op:'fail'},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					listTable();
					layer.alert(data.msg);
				}else{
					layer.alert(data.msg);
				}
			} 
		});
	}, function(){
	  layer.close(confirmobj);
	});
}
function delItem(id) {
	var confirmobj = layer.confirm('你确实要删除此提现记录吗？', {
	  btn: ['确定','取消']
	}, function(){
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax_site.php?act=opTixian",
			data : {id:id,op:'delete'},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					listTable();
					layer.alert(data.msg);
				}else{
					layer.alert(data.msg);
				}
			} 
		});
	}, function(){
	  layer.close(confirmobj);
	});
}
function operation(id,op) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax_site.php?act=opTixian",
		data : {id:id,op:op},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				listTable();
				layer.alert(data.msg, {icon:1});
			}else{
				layer.alert(data.msg);
			}
		} 
	});
}
function setResult(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'ajax_site.php?act=tixian_note',
		data : {id:id},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var pro = layer.prompt({title: '填写提现失败原因', value: data.result, formType: 2}, function(text, index){
					var ii = layer.load(2, {shade:[0.1,'#fff']});
				$.ajax({
					type : 'POST',
					url : 'ajax_site.php?act=set_tixian_note',
					data : {id:id,result:text},
					dataType : 'json',
					success : function(data) {
						layer.close(ii);
						if(data.code == 0){
							layer.close(pro);
							layer.msg('填写提现失败原因成功',{time:500,icon:1});
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
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
$(document).ready(function(){
	listTable();
})