function dopay(type,orderid){
	if(type == 'help'){
		var content = $("#demo_url").attr('data-url');
		var clipboard = new Clipboard('.btssn', {
			text: function () {
				return content;
			}
		});
		layer.open({
			title: '代付订单创建成功',
			btn: false,
			content: '<center style="color: seagreen">代付订单已经创建成功，发给好友帮你付款吧~</center><hr><textarea  class="layui-textarea">' + content + '</textarea><hr>' +
				'<center><button class="layui-btn layui-btn-fluid layui-btn-radius layui-btn-sm layui-btn-normal btssn">点击复制</button></center>',
		});
		clipboard.on('success', function (e) {
			swal({
				title: '恭喜',
				type: 'success',
				html: '代付订单链接已经帮您复制到剪切板上啦，快去发送给朋友让他帮你付款吧~',
				confirmButtonText: '好的',
			});
			layer.closeAll();
		});
		clipboard.on('error', function (e) {
			console.log(e);
			swal({
				title: '异常',
				type: 'warning',
				html: '复制功能好像出了点问题，去手动复制代付订单链接发给朋友吧',
				confirmButtonText: '好的',
			});
			layer.closeAll();
		});
		return false;
	}else if(type == 'rmb'){
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
					window.location.href='?buyok=1';
				}else if(data.code == -2){
					alert(data.msg);
					window.location.href='?buyok=1';
				}else if(data.code == -3){
					var confirmobj = layer.confirm('你的余额不足，请充值！', {
					  btn: ['立即充值','取消']
					}, function(){
						window.location.href='./user/#chongzhi';
					}, function(){
						layer.close(confirmobj);
					});
				}else if(data.code == -4){
					var confirmobj = layer.confirm('你还未登录，是否现在登录？', {
					  btn: ['登录','注册','取消']
					}, function(){
						window.location.href='./user/login.php';
					}, function(){
						window.location.href='./user/reg.php';
					}, function(){
						layer.close(confirmobj);
					});
				}else{
					layer.alert(data.msg);
				}
			} 
		});
	}else{
		window.location.href='other/submit.php?type='+type+'&orderid='+orderid;
	}
}
$(document).ready(function(){
$("#dopay").click(function(){
	var paytype = $("input[name=pay]:checked").val();
	var orderid = $('#orderid').val();
	if (paytype == undefined) {
		swal({
			title: '提示',
			type: 'warning',
			html: '<h4>请选择付款方式！</h4>',
			confirmButtonText: '好的',
		});
		return false;
	}
	dopay(paytype,orderid);
});
})