var checkflag1 = "false";
function check1(field) {
if (checkflag1 == "false") {
for (i = 0; i < field.length; i++) {
field[i].checked = true;}
checkflag1 = "true";
return "false"; }
else {
for (i = 0; i < field.length; i++) {
field[i].checked = false; }
checkflag1 = "false";
return "true"; }
}

function unselectall1()
{
    if(document.form1.chkAll1.checked){
	document.form1.chkAll1.checked = document.form1.chkAll1.checked&0;
	checkflag1 = "false";
    } 	
}

function listTable(query){
	var url = window.document.location.href.toString();
	var queryString = url.split("?")[1];
	query = query || queryString;
	if(query == 'start' || query == undefined){
		query = '';
		history.replaceState({}, null, './workorder.php');
	}else if(query != undefined){
		history.replaceState({}, null, './workorder.php?'+query);
	}
	layer.closeAll();
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'workorder-table.php?'+query,
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
function orderItem(id){
	var title = 'ID:'+id+' 工单详情';
	var url = './workorder-item.php?my=view&id='+id;
	var area = [$(window).width() > 800 ? '800px' : '90%', $(window).height() > 600 ? '600px' : '90%'];
	var options = {
		type: 2,
		title: title,
		shadeClose: true,
		shade: false,
		maxmin: true,
		moveOut: true,
		area: area,
		content: url,
		zIndex: layer.zIndex,
		success: function (layero, index) {
			var that = this;
			$(layero).data("callback", that.callback);
			layer.setTop(layero);
			if ($(layero).height() > $(window).height()) {
				layer.style(index, {
					top: 0,
					height: $(window).height()
				});
			}
		},
		cancel: function(){
			listTable()
		}
	}
	if ($(window).width() < 480 || (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream && top.$("body").size() > 0)) {
		options.area = [top.$("body").width() + "px", top.$("body").height() + "px"];
		options.offset = [top.$("body").scrollTop() + "px", "0px"];
	}
	layer.open(options);
}
function change(){
	if($("select[name='aid']").val() == 3 && $("input[name='content']").val()==''){
		layer.prompt({title: '请输入回复的内容', formType: 2}, function(text, index){
			layer.close(index);
			$("input[name='content']").val(text);
			change()
		});
		return false;
	}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'ajax.php?act=workorder_change',
		data : $('#form1').serialize(),
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				listTable();
				layer.alert(data.msg);
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('请求超时');
			listTable();
		}
	});
	return false;
}
function delworkorder(id) {
	var confirmobj = layer.confirm('你确实要删除此工单吗？', {
	  btn: ['确定','取消']
	}, function(){
	  $.ajax({
		type : 'GET',
		url : 'ajax.php?act=delworkorder&id='+id,
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
			return false;
		}
	  });
	}, function(){
	  layer.close(confirmobj);
	});
}
$(document).ready(function(){
	listTable();
})