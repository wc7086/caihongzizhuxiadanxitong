
var pagesize = 30;

var dstatus = 0;
function listTable(query){
	var url = window.document.location.href.toString();
	var queryString = url.split("?")[1];
	query = query || queryString;
	if(query == 'start' || query == undefined){
		query = '';
		history.replaceState({}, null, './payorder.php?'+query);
	}else if(query != undefined){
		history.replaceState({}, null, './payorder.php?'+query);
	}
	layer.closeAll();
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'payorder-table.php?dstatus='+dstatus+'&num='+pagesize+'&'+query,
		dataType : 'html',
		cache : false,
		success : function(data) {
			layer.close(ii);
			$("#listTable").html(data)
		},
		error:function(data){
			layer.msg('服务器错误');
		}
	});
}
function searchItem(){
	var column=$("select[name='column']").val();
	var kw=$("input[name='kw']").val();
	var type=$("select[name='type']").val();
	if(kw==''){
		listTable('type='+type);
	}else{
		listTable('type='+type+'&column='+column+'&kw='+kw);
	}
	return false;
}
function clearItem(){
	$("input[name='kw']").val('');
	$("select[name='type']").val('all');
	listTable('start')
}
function fillOrder(trade_no) {
	var confirmobj = layer.confirm('你确定要将此订单状态修改为已完成并补单吗？', {
	  btn: ['确定','取消']
	}, function(){
	  var ii = layer.load(2, {shade:[0.1,'#fff']});
	  $.ajax({
		type : 'POST',
		url : 'ajax_order.php?act=fillPayOrder',
		data : {trade_no: trade_no},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.alert(data.msg, {icon:1}, function(){listTable();});	
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
		}
	  });
	}, function(){
	  layer.close(confirmobj);
	});
}
function delOrder(trade_no) {
	var confirmobj = layer.confirm('你确定要删除此支付记录吗？', {
	  btn: ['确定','取消']
	}, function(){
	  $.ajax({
		type : 'POST',
		url : 'ajax_order.php?act=delPayOrder',
		data : {trade_no: trade_no},
		dataType : 'json',
		success : function(data) {
			if(data.code == 0){
				layer.msg('删除成功');
				listTable();
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
		}
	  });
	}, function(){
	  layer.close(confirmobj);
	});
}
$(document).ready(function(){
	listTable();
	$("#dstatus").change(function () {
		var val = $(this).val();
		dstatus = val;
		listTable();
	});
	$("#pagesize").change(function () {
		var size = $(this).val();
		pagesize = size;
		listTable();
	});
})