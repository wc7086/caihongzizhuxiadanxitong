var pagesize = 30;
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
		history.replaceState({}, null, './shoplist.php');
	}else if(query != undefined){
		history.replaceState({}, null, './shoplist.php?'+query);
	}
	layer.closeAll();
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'shoplist-table.php?num='+pagesize+'&'+query,
		dataType : 'html',
		cache : false,
		success : function(data) {
			layer.close(ii);
			$("#listTable").html(data);
			$('[data-toggle="tooltip"], .enable-tooltips').tooltip({container: 'body', animation: false});
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function show(tid) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax_shop.php?act=getTool&tid='+tid,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var item = '<table class="table table-condensed table-hover">';
				item += '<tr><td colspan="6" style="text-align:center"><b>商品详情</b></td></tr><tr><td class="info">商品ID</td><td colspan="5">'+data.data.tid+'</td></tr><tr><td class="info">商品名称</td><td colspan="5">'+data.data.name+'</td></tr><tr><td class="info">商品链接</td><td colspan="5"><a href="'+data.data.link+'" target="_blank">'+data.data.link+'</a></td></tr><tr><td class="info">商品总销量</td><td colspan="5">'+data.data.sales+'</td></tr><tr><td class="info">添加时间</td><td colspan="5">'+data.data.addtime+'</td></tr>';
				item += '</table>';
				layer.open({
				  type: 1,
				  title: '商品详情',
				  skin: 'layui-layer-rim',
				  content: item,
				  shadeClose: true
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
function editAllPrice(){
	var prid = $("select[name='prid_n']").val();
	$("input[name='prid']").val(prid);
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'ajax_shop.php?act=editAllPrice',
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
}
function editAllStock(){
	layer.open({
		area: ['360px'],
		title: '批量设置商品库存（留空则为无限库存）',
		content: '<div class="form-group"><input type="text" class="form-control" name="stock_num" placeholder="请输入商品库存数量" value=""></div>',
		yes: function(){
			var num = $("input[name='stock_num']").val();
			$("input[name='stock']").val(num);
			$.ajax({
				type : 'POST',
				url : 'ajax_shop.php?act=editAllStock',
				data : $('#form1').serialize(),
				dataType : 'json',
				success : function(data) {
					if(data.code == 0){
						layer.alert(data.msg, {icon:1}, function(){listTable()});
					}else{
						layer.alert(data.msg, {icon: 2});
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		}
	});
}
function change(){
	if($("select[name='aid']").val() == 10){
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : 'GET',
			url : 'ajax_shop.php?act=getAllPrice',
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					layer.open({
					  type: 1,
					  title: '修改加价模板',
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
		return false;
	}
	else if($("select[name='aid']").val() == 11){
		editAllStock();
		return false;
	}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'ajax_shop.php?act=shop_change',
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
function move(){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'ajax_shop.php?act=shop_move',
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
function searchItem(){
	var kw=$("input[name='kw']").val();
	if(kw==''){
		listTable('start');
	}else{
		listTable('kw='+kw);
	}
	return false;
}
function setActive(tid,active) {
	$.ajax({
		type : 'GET',
		url : 'ajax_shop.php?act=setTools&tid='+tid+'&active='+active,
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
function setClose(tid,close) {
	$.ajax({
		type : 'GET',
		url : 'ajax_shop.php?act=setTools&tid='+tid+'&close='+close,
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
function delTool(tid) {
	var confirmobj = layer.confirm('你确实要删除此商品吗？', {
	  btn: ['确定','取消']
	}, function(){
	  $.ajax({
		type : 'GET',
		url : 'ajax_shop.php?act=delTool&tid='+tid,
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
function sort(cid,tid,sort) {
	$.ajax({
		type : 'GET',
		url : 'ajax_shop.php?act=setToolSort&cid='+cid+'&tid='+tid+'&sort='+sort,
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
function getPrice(tid) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax_shop.php?act=getPrice&tid='+tid,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.open({
				  type: 1,
				  title: '修改商品价格',
				  skin: 'layui-layer-rim',
				  content: data.data,
				  shadeClose: true
				});
				  changePrice();
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
function editPrice(tid) {
	var price=$("#price").val();
	var prid=$("#prid").val();
	var price_s=$("#price_s").val();
	var cost_s=$("#cost_s").val();
	var cost2_s=$("#cost2_s").val();
	if(parseInt(prid)>0 && price == '' || parseInt(prid)==0 && price_s == ''){
		layer.alert('商品售价不能为空！');
		return false;
	}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax_shop.php?act=editPrice",
		data : {tid:tid,price:price,prid:prid,price_s:price_s,cost_s:cost_s,cost2_s:cost2_s},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('保存成功！');
				listTable();
			}else{
				layer.alert(data.msg);
			}
		} 
	});
}
function getFloat(number, n) {
	n = n ? parseInt(n) : 0;
	if (n <= 0) return Math.round(number);
	number = Math.round(number * Math.pow(10, n)) / Math.pow(10, n);
	return number;
}
function changePrice(){
	var price=$("#price").val();
	var prid=$("#prid").val();
	if(prid=='0'){
		$("#price_s").attr("disabled",false);
		$("#cost_s").attr("disabled",false);
		$("#cost2_s").attr("disabled",false);
		$("#price").attr("disabled",true);
	}else{
		$("#price_s").attr("disabled",true);
		$("#cost_s").attr("disabled",true);
		$("#cost2_s").attr("disabled",true);
		$("#price").attr("disabled",false);
		if(price == '') return false;
		price = parseFloat(price);
		var kind = parseInt($("#prid option:selected").attr('kind'));
		var p_2 = parseFloat($("#prid option:selected").attr('p_2'));
		var p_1 = parseFloat($("#prid option:selected").attr('p_1'));
		var p_0 = parseFloat($("#prid option:selected").attr('p_0'));
		$("#price_s").val(getFloat(kind==1?price+p_0:price*p_0 ,2));
		$("#cost_s").val(getFloat(kind==1?price+p_1:price*p_1 ,2));
		$("#cost2_s").val(getFloat(kind==1?price+p_2:price*p_2 ,2));
	}
}
function reset_sort(cid){
	var confirmobj = layer.confirm('当你无法自定义排序时，可重置排序解决，重置排序后，该分类下的商品将以你创建商品时间倒序排序，确定继续？', function () {
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.post('ajax_shop.php?act=reset_sort', {
			cid: cid
		}, function (data) {
			layer.close(ii);
			if (data.code === 0) {
				layer.alert(data.msg, {icon: 1}, function () {
					listTable();
				});
			} else {
				layer.alert(data.msg, {icon: 2});
			}
		}, 'json');
	}, function(){
	  layer.close(confirmobj);
	});
}
function change_shopname() {
	layer.open({
		area: ['360px'],
		title: '批量替换商品名称',
		content: '<div class="form-group"><input type="text" class="form-control" name="oldName" placeholder="请输入原商品名称关键字" required=""></div><div class="form-group"><input type="text" class="form-control" name="newName" placeholder="请输入新商品名称关键字" required=""></div>',
		yes: function(){
			var oldName = $("input[name='oldName']").val();
			var newName = $("input[name='newName']").val();
			if(oldName==''||newName==''){
				layer.alert('不能为空', {icon: 2});return;
			}
			$.ajax({
				type : 'POST',
				url : 'ajax_shop.php?act=change_shopname',
				data : {oldName:oldName, newName:newName},
				dataType : 'json',
				success : function(data) {
					if(data.code == 0){
						layer.alert(data.msg, {icon:1}, function(){listTable()});
					}else{
						layer.alert(data.msg, {icon: 2});
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		}
	});
}
function change_inputs() {
	layer.open({
		area: ['360px'],
		title: '批量替换输入框标题',
		content: '<div class="form-group"><input type="text" class="form-control" name="oldName" placeholder="请输入原输入框标题" required=""></div><div class="form-group"><input type="text" class="form-control" name="newName" placeholder="请输入新输入框标题" required=""></div>',
		yes: function(){
			var oldName = $("input[name='oldName']").val();
			var newName = $("input[name='newName']").val();
			if(oldName==''||newName==''){
				layer.alert('不能为空', {icon: 2});return;
			}
			$.ajax({
				type : 'POST',
				url : 'ajax_shop.php?act=change_inputs',
				data : {oldName:oldName, newName:newName},
				dataType : 'json',
				success : function(data) {
					if(data.code == 0){
						layer.alert(data.msg, {icon:1}, function(){listTable()});
					}else{
						layer.alert(data.msg, {icon: 2});
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		}
	});
}
function setStock(tid,stock) {
	if(stock=='发卡'){window.open('./fakalist.php?tid='+tid);return;}
	else if(stock=='无限'){stock=''}
	layer.open({
		area: ['360px'],
		title: '设置商品库存（留空则为无限库存）',
		content: '<div class="form-group"><input type="text" class="form-control" name="stock_num" placeholder="请输入商品库存数量" value="'+stock+'"></div>',
		yes: function(){
			var num = $("input[name='stock_num']").val();
			$.ajax({
				type : 'POST',
				url : 'ajax_shop.php?act=setStock',
				data : {tid:tid, num:num},
				dataType : 'json',
				success : function(data) {
					if(data.code == 0){
						layer.alert(data.msg, {icon:1}, function(){listTable()});
					}else{
						layer.alert(data.msg, {icon: 2});
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		},
		shadeClose: true
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