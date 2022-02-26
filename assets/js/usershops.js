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
function getPoint() {
	if($('#tid option:selected').val()==undefined || $('#tid option:selected').val()=="0"){
		$('#inputsname').html("");
		$('#need').val('');
		$('#display_price').hide();
		$('#display_num').hide();
		$('#display_left').hide();
		$('#alert_frame').hide();
		$('#inputsname').html('下单账号');
		return false;
	}
	history.replaceState({}, null, './shops.php?cid='+$('#cid').val()+'&tid='+$('#tid option:selected').val());
	var multi = $('#tid option:selected').attr('multi');
	var count = $('#tid option:selected').attr('count');
	var price = $('#tid option:selected').attr('price');
	var shopimg = $('#tid option:selected').attr('shopimg');
	var close = $('#tid option:selected').attr('close');
	$('#display_price').show();
	if(multi==1 && count>1){
		$('#need').val('￥'+price +"元 ➠ "+count+"个");
	}else{
		$('#need').val('￥'+price +"元");
	}
	if(close == 1){
		$('#submit_buy').val('当前商品已停止下单');
		$('#submit_buy').html('当前商品已停止下单');
		layer.alert('当前商品维护中，停止下单！');
	}else if(price == 0){
		$('#submit_buy').val('立即免费领取');
		$('#submit_buy').html('立即免费领取');
	}else{
		$('#submit_buy').val('立即购买');
		$('#submit_buy').html('立即购买');
	}
	if(multi == 1){
		$('#display_num').show();
	}else{
		$('#display_num').hide();
	}
	var desc = $('#tid option:selected').attr('desc');
	if(desc!='' && alert!='null'){
		$('#alert_frame').show();
		$('#alert_frame').html(unescape(desc));
	}else{
		$('#alert_frame').hide();
	}
	var inputname = $('#tid option:selected').attr('inputname');
	if(inputname==''||inputname=='hide')inputname='下单账号';
	$('#inputsname').html(inputname);
	var inputsname = $('#tid option:selected').attr('inputsname');
	if(inputsname!=''){
		$.each(inputsname.split('|'), function(i, value) {
			if(value.indexOf('[')>0 && value.indexOf(']')>0){
				value = value.split('[')[0];
			}
			if(value.indexOf('{')>0 && value.indexOf('}')>0){
				value = value.split('{')[0];
			}
			$('#inputsname').append('|'+value);
		});
	}
	$('#inputvalues').attr('placeholder', $('#inputsname').html()+"\r\n"+$('#inputsname').html()+"\r\n......");
	var stock = $('#tid option:selected').attr('stock');
	if($('#tid option:selected').attr('isfaka')==1){
		$('#display_left').show();
		$.ajax({
			type : "POST",
			url : "../ajax.php?act=getleftcount",
			data : {tid:$('#tid option:selected').val()},
			dataType : 'json',
			success : function(data) {
				$('#leftcount').val(data.count)
			}
		});
	}else if(stock!=null && stock!='' && stock!='null'){
		$('#display_left').show();
		$('#leftcount').val(stock);
	}else{
		$('#display_left').hide();
	}
	var alert = $('#tid option:selected').attr('alert');
	if(alert!='' && alert!='null'){
		var ii=layer.alert(''+unescape(alert)+'',{
			btn:['我知道了'],
			title:'商品提示'
		},function(){
			layer.close(ii);
		});
	}
}
function dopay(type,orderid){
	if(type == 'rmb'){
		var ii = layer.msg('正在提交订单请稍候...', {icon: 16,shade: 0.5,time: 15000});
		$.ajax({
			type : "POST",
			url : "../ajax.php?act=payrmb",
			data : {orderid: orderid},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 1){
					alert(data.msg);
					window.location.href=islogin==1?'./list.php':'../?buyok=1';
				}else if(data.code == -2){
					alert(data.msg);
					window.location.href=islogin==1?'./list.php':'../?buyok=1';
				}else if(data.code == -3){
					var confirmobj = layer.confirm('你的余额不足，请充值！', {
					  btn: ['立即充值','取消']
					}, function(){
						window.location.href='./recharge.php';
					}, function(){
						layer.close(confirmobj);
					});
				}else{
					layer.alert(data.msg);
				}
			} 
		});
	}else{
		window.location.href='../other/submit.php?type='+type+'&orderid='+orderid;
	}
}
function cancel(orderid){
	layer.closeAll();
	$.ajax({
		type : "POST",
		url : "../ajax.php?act=cancel",
		data : {orderid: orderid, hashsalt: hashsalt},
		dataType : 'json',
		async : true,
		success : function(data) {
			if(data.code == 0){
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
$("#doSearch").click(function () {
	var kw = $("#searchkw").val();
	if(kw==''){layer.msg('请先输入要搜索的内容', {time: 500});return;}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$("#tid").empty();
	$("#tid").append('<option value="0">请选择商品</option>');
	$.ajax({
		type : "POST",
		url : "../ajax.php?act=gettool",
		data : {kw:kw},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var num = 0;
				$.each(data.data, function (i, res) {
					$("#tid").append('<option value="'+res.tid+'" cid="'+res.cid+'" price="'+res.price+'" desc="'+escape(res.desc)+'" alert="'+escape(res.alert)+'" inputname="'+res.input+'" inputsname="'+res.inputs+'" multi="'+res.multi+'" isfaka="'+res.isfaka+'" count="'+res.value+'" close="'+res.close+'" prices="'+res.prices+'" max="'+res.max+'" min="'+res.min+'" stock="'+res.stock+'">'+res.name+'</option>');
					num++;
				});
				$("#tid").val(0);
				getPoint();
				if(num==0 && cid!=0)layer.msg('<option value="0">没有搜索到相关商品</option>', {icon: 2, time: 500});
				else layer.msg('成功搜索到'+num+'个商品', {icon: 1, time: 1000});
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('加载失败，请刷新重试');
			return false;
		}
	});
});
$("#cid").change(function () {
	var cid = $(this).val();
	if(cid>0)history.replaceState({}, null, './shops.php?cid='+cid);
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$("#tid").empty();
	$("#tid").append('<option value="0">请选择商品</option>');
	$.ajax({
		type : "GET",
		url : "../ajax.php?act=gettool&cid="+cid+"&info=1",
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			$("#tid").empty();
			$("#tid").append('<option value="0">请选择商品</option>');
			if(data.code == 0){
				if(data.info!=null){
					$("#className").html(data.info.name);
					if(data.info.shopimg)
						$("#classImg").attr('src',data.info.shopimg.indexOf("://")>0?data.info.shopimg:'../'+data.info.shopimg);
					else
						$("#classImg").attr('src','');
				}
				var num = 0;
				$.each(data.data, function (i, res) {
					$("#tid").append('<option value="'+res.tid+'" cid="'+res.cid+'" price="'+res.price+'" desc="'+escape(res.desc)+'" alert="'+escape(res.alert)+'" inputname="'+res.input+'" inputsname="'+res.inputs+'" multi="'+res.multi+'" isfaka="'+res.isfaka+'" count="'+res.value+'" close="'+res.close+'" prices="'+res.prices+'" max="'+res.max+'" min="'+res.min+'" stock="'+res.stock+'">'+res.name+'</option>');
					num++;
				});
				if($_GET["tid"] && $_GET["cid"]==cid){
					var tid = parseInt($_GET["tid"]);
					$("#tid").val(tid);
				}else{
					$("#tid").val(0);
				}
				getPoint();
				if(num==0 && cid!=0)$("#tid").html('<option value="0">该分类下没有商品</option>');
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('加载失败，请刷新重试');
			return false;
		}
	});
});
	$("#submit_buy").click(function(){
		var tid=$("#tid").val();
		if(tid==0){layer.alert('请选择商品！');return false;}
		var inputvalues=$("#inputvalues").val();
		if(inputvalues=='' || tid==''){layer.alert('请确保每项不能为空！');return false;}
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "../ajax.php?act=pays",
			data : {tid:tid,inputvalues:inputvalues,num:$("#num").val(),hashsalt:hashsalt},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					var paymsg = '';
					if(data.pay_alipay>0){
						paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'alipay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="../assets/img/alipay.png" class="logo">支付宝</button>';
					}
					if(data.pay_qqpay>0){
						paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'qqpay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="../assets/img/qqpay.png" class="logo">QQ钱包</button>';
					}
					if(data.pay_wxpay>0){
						paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'wxpay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="../assets/img/wxpay.png" class="logo">微信支付</button>';
					}
					if (data.pay_rmb>0) {
						paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'rmb\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="../assets/img/rmb.png" class="logo">余额支付</button>';
					}
					if(data.paymsg!=null)paymsg+=data.paymsg;
					layer.alert('<center><h2>￥ '+data.need+'</h2><span class="text-muted">共 '+data.num+' 件商品</span><hr>'+paymsg+'<hr><a class="btn btn-default btn-block" onclick="cancel(\''+data.trade_no+'\')">取消订单</a></center>',{
						btn:[],
						title:'提交订单成功',
						closeBtn: false
					});
					$.cookie('user_order', data.trade_no, { path: '/' });
				}else if(data.code == 3){
					layer.alert(data.msg, {
						closeBtn: false
					}, function(){
						window.location.reload();
					});
				}else{
					layer.alert(data.msg);
				}
			} 
		});
	});

$("#num_add").click(function () {
	var i = parseInt($("#num").val());
	if ($("#need").val() == ''){
		layer.alert('请先选择商品');
		return false;
	}
	var multi = $('#tid option:selected').attr('multi');
	var count = parseInt($('#tid option:selected').attr('count'));
	if (multi == '0'){
		layer.alert('该商品不支持选择数量');
		return false;
	}
	i++;
	$("#num").val(i);
	var price = parseFloat($('#tid option:selected').attr('price'));
	var prices = $('#tid option:selected').attr('prices');
	if(prices!='' || prices!='null'){
		var discount = 0;
		$.each(prices.split(','), function(index, item){
			if(i>=parseInt(item.split('|')[0]))discount = parseFloat(item.split('|')[1]);
		});
		price = price - discount;
	}

	var mult = 1;
	price = price * i * mult;
	count = count * i;
	if(count>1)$('#need').val('￥'+price.toFixed(2) +"元 ➠ "+count+"个");
	else $('#need').val('￥'+price.toFixed(2) +"元");
});
$("#num_min").click(function (){
	var i = parseInt($("#num").val());
	if(i<=1){
    	layer.msg('最低下单一份哦！'); 
      	return false;
    }
	if ($("#need").val() == ''){
		layer.alert('请先选择商品');
		return false;
	}
	var multi = $('#tid option:selected').attr('multi');
	var count = parseInt($('#tid option:selected').attr('count'));
	if (multi == '0'){
		layer.alert('该商品不支持选择数量');
		return false;
	}
	i--;
	if (i <= 0) i = 1;
	$("#num").val(i);
	var price = parseFloat($('#tid option:selected').attr('price'));
	var prices = $('#tid option:selected').attr('prices');
	if(prices!='' || prices!='null'){
		var discount = 0;
		$.each(prices.split(','), function(index, item){
			if(i>=parseInt(item.split('|')[0]))discount = parseFloat(item.split('|')[1]);
		});
		price = price - discount;
	}

	var mult = 1;
	price = price * i * mult;
	count = count * i;
	if(count>1)$('#need').val('￥'+price.toFixed(2) +"元 ➠ "+count+"个");
	else $('#need').val('￥'+price.toFixed(2) +"元");
});
$("#num").keyup(function () {
	var i = parseInt($("#num").val());
	if(isNaN(i))return false;
	var price = parseFloat($('#tid option:selected').attr('price'));
	var count = parseInt($('#tid option:selected').attr('count'));
	var prices = $('#tid option:selected').attr('prices');
	if(i<1) $("#num").val(1);
	if(prices!='' || prices!='null'){
		var discount = 0;
		$.each(prices.split(','), function(index, item){
			if(i>=parseInt(item.split('|')[0]))discount = parseFloat(item.split('|')[1]);
		});
		price = price - discount;
	}

	var mult = 1;
	price = price * i * mult;
	count = count * i;
	if(count>1)$('#need').val('￥'+price.toFixed(2) +"元 ➠ "+count+"个");
	else $('#need').val('￥'+price.toFixed(2) +"元");
});

if($_GET['cid']){
	var cid = parseInt($_GET['cid']);
	$("#cid").val(cid);
}
$("#cid").change();

});

