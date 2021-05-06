var orderid = null;
function emptyCart(){
	var confirmobj = layer.confirm('你确实要清空购物车所有商品吗？', {
	  btn: ['确定','取消']
	}, function(){
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "GET",
			url : "ajax.php?act=cart_empty",
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					layer.msg('清空购物车成功');
					window.location.reload();
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
function cart_shop_edit(id){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax.php?act=cart_shop_item",
		data : {id: id},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.open({
				  type: 1,
				  title: '修改下单信息',
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
function cart_shop_del(id){
	var confirmobj = layer.confirm('你确实要删除该购物车商品吗？', {
	  btn: ['确定','取消']
	}, function(){
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=cart_shop_del",
			data : {id: id},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					layer.msg('删除商品成功');
					window.location.reload();
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
function cart_shop_save(id) {
	var inputvalue=$("#inputvalue").val();
	if(inputvalue=='' || $("#inputvalue2").val()=='' || $("#inputvalue3").val()=='' || $("#inputvalue4").val()=='' || $("#inputvalue5").val()==''){layer.alert('请确保每项不能为空！');return false;}
	if(($('#inputname').html()=='下单ＱＱ' || $('#inputname').html()=='ＱＱ账号' || $("#inputname").html() == 'QQ账号') && (inputvalue.length<5 || inputvalue.length>11 || isNaN(inputvalue))){layer.alert('请输入正确的QQ号！');return false;}
	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
	if($('#inputname').html()=='你的邮箱' && !reg.test(inputvalue)){layer.alert('邮箱格式不正确！');return false;}
	reg=/^[1][0-9]{10}$/;
	if($('#inputname').html()=='手机号码' && !reg.test(inputvalue)){layer.alert('手机号码格式不正确！');return false;}
	$('#save').val('Loading');
	var ii = layer.load(0, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax.php?act=pay&method=cart_edit",
		data : {shop_id:id,inputvalue:inputvalue,inputvalue2:$("#inputvalue2").val(),inputvalue3:$("#inputvalue3").val(),inputvalue4:$("#inputvalue4").val(),inputvalue5:$("#inputvalue5").val(),num:$("#num").val(), hashsalt: hashsalt},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('保存成功！');
				window.location.reload();
			}else{
				layer.alert(data.msg);
			}
			$('#save').val('保存修改');
		} 
	});
}
function dopay(type,orderid){
	if(type == 'rmb'){
		var ii = layer.msg('正在提交订单请稍候...', {icon: 16,shade: 0.5,time: 15000});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=payrmb",
			data : {orderid: orderid},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 1){
					alert(data.msg);
					if(top.location != self.location)top.location.href='./user/shop.php?buyok=1';
					else window.location.href='?buyok=1';
				}else if(data.code == -2){
					alert(data.msg);
					if(top.location != self.location)top.location.href='./user/shop.php?buyok=1';
					else window.location.href='?buyok=1';
				}else if(data.code == -3){
					var confirmobj = layer.confirm('你的余额不足，请充值！', {
					  btn: ['立即充值','取消']
					}, function(){
						top.location.href='./user/recharge.php';
					}, function(){
						layer.close(confirmobj);
					});
				}else if(data.code == -4){
					var confirmobj = layer.confirm('你还未登录，是否现在登录？', {
					  btn: ['登录','注册','取消']
					}, function(){
						top.location.href='./user/login.php';
					}, function(){
						top.location.href='./user/reg.php';
					}, function(){
						layer.close(confirmobj);
					});
				}else{
					layer.alert(data.msg);
				}
			} 
		});
	}else{
		top.location.href='other/submit.php?type='+type+'&orderid='+orderid;
	}
}
function cancel(orderid){
	layer.closeAll();
	$.ajax({
		type : "POST",
		url : "ajax.php?act=cart_cancel",
		data : {orderid: orderid, hashsalt: hashsalt},
		dataType : 'json',
		async : true,
		success : function(data) {
			if(data.code == 0){
				orderid = null;
			}else{
				layer.msg(data.msg);
				window.location.reload();
			}
		},
		error:function(data){
			window.location.reload();
		}
	});
}
$(document).ready(function(){
if(top.location != self.location)$("#backHome").hide();
$("input:checkbox[name=checkbox]").change(function(){
	var money=0;
	$('input:checkbox[name=checkbox]:checked').each(function(){
     money=parseFloat(parseFloat(money)+parseFloat($(this).attr('money')));
	});
	$("#needmoney").text(money.toFixed(2));
});
$("input:checkbox[name=checkbox]").change();
$("#submit_cart").click(function(){
	var shop_id=new Array();
	$('input:checkbox[name=checkbox]:checked').each(function(){
		shop_id.push($(this).val());
	});
	if(shop_id.length==0){
		layer.msg('您未在购物车添加任何商品~');
	}else{
		$.ajax({
			type : "POST",
			url : "ajax.php?act=cart_buy",
			data : {shop_id: shop_id, hashsalt: hashsalt},
			dataType : 'json',
			success : function(data) {
				if(data.code == 0){
					orderid = data.trade_no;
					var paymsg = '';
					if("undefined" != typeof layui){
						if(data.pay_alipay>0){
							paymsg+='<button class="layui-btn layui-btn-primary layui-btn-fluid" onclick="dopay(\'alipay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/alipay.png" class="paylogo">支付宝</button><br/>';
						}
						if(data.pay_qqpay>0){
							paymsg+='<button class="layui-btn layui-btn-primary layui-btn-fluid" onclick="dopay(\'qqpay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/qqpay.png" class="paylogo">QQ钱包</button><br/>';
						}
						if(data.pay_wxpay>0){
							paymsg+='<button class="layui-btn layui-btn-primary layui-btn-fluid" onclick="dopay(\'wxpay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/wxpay.png" class="paylogo">微信支付</button><br/>';
						}
						if (data.pay_rmb>0) {
							paymsg+='<button class="layui-btn layui-btn-primary layui-btn-fluid" onclick="dopay(\'rmb\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/rmb.png" class="paylogo">余额支付<span class="text-muted">（剩'+data.user_rmb+'元）</span></button>';
						}
					}else{
						if(data.pay_alipay>0){
							paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'alipay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/alipay.png" class="logo">支付宝</button>';
						}
						if(data.pay_qqpay>0){
							paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'qqpay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/qqpay.png" class="logo">QQ钱包</button>';
						}
						if(data.pay_wxpay>0){
							paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'wxpay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/wxpay.png" class="logo">微信支付</button>';
						}
						if (data.pay_rmb>0) {
							paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'rmb\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/rmb.png" class="logo">余额支付<span class="text-muted">（剩'+data.user_rmb+'元）</span></button>';
						}
					}
					if(data.paymsg!=null)paymsg+=data.paymsg;
					layer.alert('<center><h2>￥ '+data.need+'</h2><hr>'+paymsg+'<hr><a class="btn btn-default btn-block" onclick="cancel(\''+data.trade_no+'\')">取消订单</a></center>',{
						btn:[],
						title:'提交订单成功',
						closeBtn: false
					});
				}else{
					layer.alert(data.msg);
				}
			} 
		});
	}
});
});