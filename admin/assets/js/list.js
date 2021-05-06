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
		history.replaceState({}, null, './list.php');
	}else if(query != undefined){
		history.replaceState({}, null, './list.php?'+query);
	}
	layer.closeAll();
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'list-table.php?num='+pagesize+'&'+query,
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
	var tid=$("input[name='tid']").val();
	var zid=$("input[name='zid']").val();
	var uid=$("input[name='uid']").val();
	var starttime=$("input[name='starttime']").val();
	var endtime=$("input[name='endtime']").val();
	var addstr="";
	if(tid!='')addstr="tid="+tid+"&";
	else if(zid!='')addstr="zid="+zid+"&";
	else if(uid!='')addstr="uid="+uid+"&";
	if(starttime!='')addstr+="starttime="+starttime+"&";
	if(endtime!='')addstr+="endtime="+endtime+"&";
	if(kw==''){
		listTable(addstr+'type='+type);
	}else{
		$("select[name='type']").val(-1);
		listTable(addstr+'kw='+kw);
	}
	return false;
}
function clearOrder(){
	$("input[name='kw']").val('');
	$("input[name='starttime']").val('');
	$("input[name='endtime']").val('');
	listTable('start')
}
function operation(){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'ajax_order.php?act=operation',
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
function showStatus(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax_order.php?act=showStatus&id='+id,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var item = '以下数据来自'+data.domain+'  商品ID：<a '+(data.shopurl?'href="'+data.shopurl+'"':'javascript:;')+' target="_blank" rel="noreferrer">'+data.shopid+'</a><br/><table class="table">';
				if(typeof data.list.order_state !== "undefined" && data.list.order_state && typeof data.list.now_num !== "undefined"){
					item += '<tr><td class="warning">订单ID</td><td>'+data.list.orderid+'</td><td class="warning">订单状态</td><td><font color=blue>'+data.list.order_state+'</font></td></tr><tr><td class="warning">下单数量</td><td>'+data.list.num+'</td><td class="warning">下单时间</td><td>'+data.list.add_time+'</td></tr><tr><td class="warning">初始数量</td><td>'+data.list.start_num+'</td><td class="warning">当前数量</td><td>'+data.list.now_num+'</td></tr>';
				}else{
					$.each(data.list, function(i, v){
						item += '<tr><td class="warning">'+i+'</td><td>'+v+'</td></tr>';
					});
				}
				item += '</table>';
				var area = [$(window).width() > 400 ? '400px' : '100%', ';max-height:100%'];
				layer.open({
				  type: 1,
				  area: area,
				  title: '订单进度查询',
				  skin: 'layui-layer-rim',
				  content: item
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
function djOrder(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax_order.php?act=djOrder&id='+id,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
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
}
function showOrder(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax_order.php?act=order&id='+id,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.open({
				  type: 1,
				  title: '订单详情',
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
function inputOrder(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax_order.php?act=order2&id='+id,
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
function inputNum(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax_order.php?act=order3&id='+id,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.open({
				  type: 1,
				  title: '修改份数',
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
function refund(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'ajax_order.php?act=getmoney',
		data : {id:id},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.prompt({title: '填写退款金额', value: data.money, formType: 0}, function(text, index){
					var ii = layer.load(2, {shade:[0.1,'#fff']});
				$.ajax({
					type : 'POST',
					url : 'ajax_order.php?act=refund',
					data : {id:id,money:text},
					dataType : 'json',
					success : function(data) {
						layer.close(ii);
						if(data.code == 0){
							layer.alert(data.msg, {icon:1}, function(){ listTable() });
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
function setStatus(name, status) {
	if(status==6){
		refund(name);
		return false;
	}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'get',
		url : 'ajax_order.php',
		data : 'act=setStatus&name=' + name + '&status=' + status,
		dataType : 'json',
		success : function(ret) {
			layer.close(ii);
			if (ret['code'] != 200) {
				alert(ret['msg'] ? ret['msg'] : '操作失败');
			}
			listTable();
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function setResult(id,title) {
	var title = title || '异常原因';
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'ajax_order.php?act=result',
		data : {id:id},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var pro = layer.prompt({title: '填写'+title, value: data.result, formType: 2}, function(text, index){
					var ii = layer.load(2, {shade:[0.1,'#fff']});
				$.ajax({
					type : 'POST',
					url : 'ajax_order.php?act=setresult',
					data : {id:id,result:text},
					dataType : 'json',
					success : function(data) {
						layer.close(ii);
						if(data.code == 0){
							layer.close(pro);
							layer.msg('填写'+title+'成功',{time:500,icon:1});
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
function saveOrder(id) {
	var inputvalue=$("#inputvalue").val();
	if(inputvalue=='' || $("#inputvalue2").val()=='' || $("#inputvalue3").val()=='' || $("#inputvalue4").val()=='' || $("#inputvalue5").val()==''){layer.alert('请确保每项不能为空！');return false;}
	if($('#inputname').html()=='下单ＱＱ' && (inputvalue.length<5 || inputvalue.length>11)){layer.alert('请输入正确的QQ号！');return false;}
	$('#save').val('Loading');
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax_order.php?act=editOrder",
		data : {id:id,inputvalue:inputvalue,inputvalue2:$("#inputvalue2").val(),inputvalue3:$("#inputvalue3").val(),inputvalue4:$("#inputvalue4").val(),inputvalue5:$("#inputvalue5").val()},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('保存成功！');
				listTable();
			}else{
				layer.alert(data.msg);
			}
			$('#save').val('保存');
		} 
	});
}
function saveOrderNum(id) {
	var num=$("#num").val();
	if(num==''){layer.alert('请确保每项不能为空！');return false;}
	$('#save').val('Loading');
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax_order.php?act=editOrderNum",
		data : {id:id,num:num},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('保存成功！');
				listTable();
			}else{
				layer.alert(data.msg);
			}
			$('#save').val('保存');
		} 
	});
}
$(document).ready(function(){
	listTable();
	$('.input-datepicker, .input-daterange').datepicker({
        format: 'yyyy-mm-dd',
		autoclose: true,
        clearBtn: true,
        language: 'zh-CN'
    });
	$("#pagesize").change(function () {
		var size = $(this).val();
		pagesize = size;
		listTable();
	});
})

var ordersucc=0;
var orderfail=0;
function onekeyDj() {
	var confirmobj = layer.confirm('此操作会将当前页面所有对接状态<font color="red">失败</font>的订单重新提交，是否确定继续？', {
	  btn: ['确定','取消']
	}, function(){
	  djOrder2()
	}, function(){
	  layer.close(confirmobj);
	});
}
function djOrder2() {
	if($(".resubmit").length <= 0){
		layer.alert('一键补单完成！成功:'+ordersucc+'个，失败:'+orderfail+'个', {icon:1}, function(){
			ordersucc=0;orderfail=0;
			listTable();
		});
		return;
	}else{
		var obj = $(".resubmit").first();
		var orderid = obj.attr('data-id');
		layer.msg('正在重新提交订单ID:'+orderid, {icon: 16,time: 10000,shade:[0.3, "#000"]});
		$.ajax({
			type : 'GET',
			url : 'ajax_order.php?act=djOrder&id='+orderid,
			dataType : 'json',
			success : function(data) {
				if(data.code == 0){
					ordersucc++;
					layer.msg(data.msg, {icon:1});
				}else{
					orderfail++;
					layer.msg(data.msg, {icon:2});
				}
				obj.removeClass('resubmit');
				djOrder2();
			},
			error:function(data){
				layer.msg('服务器错误');
				return false;
			}
		});
	}
}