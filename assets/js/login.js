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
function connect(type){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax.php?act=connect",
		data : {type:type,back:$_GET['back']},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				window.location.href = data.url;
			}else{
				layer.alert(data.msg, {icon: 7});
			}
		} 
	});
}
function quickreg(){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax.php?act=quickreg",
		data : {submit:'do'},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				if($_GET['back']=='index'){
					layer.msg('登录成功，正在跳转到首页', {icon: 1,shade: 0.01,time: 15000});
					window.location.href='../';
				}else if($_GET['back']=='recharge'){
					layer.msg('登录成功，正在跳转到充值页面', {icon: 1,shade: 0.01,time: 15000});
					window.location.href='./recharge.php';
				}else if($_GET['back']=='workorder'){
					layer.msg('登录成功，正在跳转到工单页面', {icon: 1,shade: 0.01,time: 15000});
					window.location.href='./workorder.php';
				}else{
					layer.msg('登录成功，正在跳转到用户中心', {icon: 1,shade: 0.01,time: 15000});
					window.location.href='./';
				}
			}else{
				layer.alert(data.msg, {icon: 7});
			}
		} 
	});
}
var handlerEmbed = function (captchaObj) {
	captchaObj.appendTo('#captcha');
	captchaObj.onReady(function () {
		$("#captcha_wait").hide();
	}).onSuccess(function () {
		var result = captchaObj.getValidate();
		if (!result) {
			return alert('请完成验证');
		}
		$("#captchaform").html('<input type="hidden" name="geetest_challenge" value="'+result.geetest_challenge+'" /><input type="hidden" name="geetest_validate" value="'+result.geetest_validate+'" /><input type="hidden" name="geetest_seccode" value="'+result.geetest_seccode+'" />');
	});
};
var handlerEmbed2 = function (token) {
	if (!token) {
		return alert('请完成验证');
	}
	$("#captchaform").html('<input type="hidden" name="token" value="'+token+'" />');
};
var handlerEmbed3 = function (vaptchaObj) {
	vaptchaObj.render();
	$('#captcha_text').hide();
	vaptchaObj.listen('pass', function() {
		var token = vaptchaObj.getToken();
		if (!token) {
			return alert('请完成验证');
		}
		$("#captchaform").html('<input type="hidden" name="token" value="'+token+'" />');
	})
};
$(document).ready(function(){
	var captcha_type = $("input[name='captcha_type']").val();
	$("#submit_login").click(function(){
		var user = $("input[name='user']").val();
		var pass = $("input[name='pass']").val();
		if(user=='' || pass==''){layer.alert('用户名或密码不能为空！');return false;}
		var data = {user:user, pass:pass};
		if(captcha_type == 1){
			var geetest_challenge = $("input[name='geetest_challenge']").val();
			var geetest_validate = $("input[name='geetest_validate']").val();
			var geetest_seccode = $("input[name='geetest_seccode']").val();
			if(geetest_challenge == undefined){
				layer.alert('请先完成滑动验证！'); return false;
			}
			var adddata = {geetest_challenge:geetest_challenge, geetest_validate:geetest_validate, geetest_seccode:geetest_seccode};
		}else if(captcha_type == 2||captcha_type == 3){
			var token = $("input[name='token']").val();
			if(token == undefined){
				layer.alert('请先完成滑动验证！'); return false;
			}
			var adddata = {token:token};
		}
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=login",
			data : Object.assign(data, adddata),
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					if($_GET['back']=='index'){
						layer.msg('登录成功，正在跳转到首页', {icon: 1,shade: 0.01,time: 15000});
						window.location.href='../';
					}else if($_GET['back']=='recharge'){
						layer.msg('登录成功，正在跳转到充值页面', {icon: 1,shade: 0.01,time: 15000});
						window.location.href='./recharge.php';
					}else if($_GET['back']=='workorder'){
						layer.msg('登录成功，正在跳转到工单页面', {icon: 1,shade: 0.01,time: 15000});
						window.location.href='./workorder.php';
					}else{
						layer.msg('登录成功，正在跳转到用户中心', {icon: 1,shade: 0.01,time: 15000});
						window.location.href='./';
					}
				}else{
					layer.alert(data.msg, {icon: 2});
				}
			} 
		});
	});
	if(captcha_type == 1){
		$.getScript("//static.geetest.com/static/tools/gt.js", function() {
			$.ajax({
				url: "../ajax.php?act=captcha&t=" + (new Date()).getTime(),
				type: "get",
				dataType: "json",
				success: function (data) {
					$('#captcha_text').hide();
					$('#captcha_wait').show();
					initGeetest({
						gt: data.gt,
						challenge: data.challenge,
						new_captcha: data.new_captcha,
						product: "popup",
						width: "100%",
						offline: !data.success
					}, handlerEmbed);
				}
			});
		});
	}else if(captcha_type == 2){
		var appid = $("input[name='appid']").val();
		$.getScript("//cdn.dingxiang-inc.com/ctu-group/captcha-ui/index.js", function() {
			var myCaptcha = _dx.Captcha(document.getElementById('captcha'), {
				appId: appid,
				type: 'basic',
				style: 'oneclick',
				width: "",
				success: handlerEmbed2
			})
			myCaptcha.on('ready', function () {
				$('#captcha_text').hide();
			})
		});
	}else if(captcha_type == 3){
		var appid = $("input[name='appid']").val();
		$.getScript("//v.vaptcha.com/v3.js", function() {
			vaptcha({
				vid: appid,
				type: 'click',
				container: '#captcha',
				offline_server: 'https://management.vaptcha.com/api/v3/demo/offline'
			}).then(handlerEmbed3);
		});
	}
});