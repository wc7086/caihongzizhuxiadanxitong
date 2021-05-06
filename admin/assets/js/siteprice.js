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

var pagesize = 30;
var zid = $_GET['zid'];

function listTable(query){
	var url = window.document.location.href.toString();
	var queryString = url.split("?")[1];
	query = query || queryString;
	if(query == 'start' || query == undefined){
		query = 'zid='+zid;
		history.replaceState({}, null, './siteprice.php?'+query);
	}else if(query != undefined){
		query += '&zid='+zid;
		history.replaceState({}, null, './siteprice.php?'+query);
	}
	layer.closeAll();
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'siteprice-table.php?num='+pagesize+'&'+query,
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
function searchItem(){
	var kw=$("input[name='kw']").val();
	if(kw==''){
		listTable('start');
	}else{
		listTable('kw='+kw);
	}
	return false;
}
function setPrice(tid,iprice) {
	layer.prompt({title: '设置商品密价（填写0取消密价）', value: iprice, formType: 0}, function(text, index){
		$.ajax({
			type : 'POST',
			url : 'ajax_site.php?act=setiprice',
			data : {zid:zid,tid:tid,iprice:text},
			dataType : 'json',
			success : function(data) {
				if(data.code == 0){
					layer.msg(data.msg);
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
}
function clearPrice() {
	var confirmobj = layer.confirm('是否要重置该站点所有商品密价？', {
	  btn: ['确定','取消']
	}, function(){
	  $.ajax({
		type : 'POST',
		url : 'ajax_site.php?act=cleariprice',
		data : {zid:zid},
		dataType : 'json',
		success : function(data) {
			if(data.code == 0){
				layer.msg('重置密价成功');
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
	}, function(){
	  layer.close(confirmobj);
	});
}
$(document).ready(function(){
	listTable();
	$("#pagesize").change(function () {
		var size = $(this).val();
		pagesize = size;
		listTable();
	});
})