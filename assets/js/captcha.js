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
var submitVerity = function (postData, failfunc){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax.php?act=invite_verify",
		data : postData,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.alert('恭喜您,您已经完成验证<br>点击确定进入首页！',{icon:1,end:function (layero,index) {
					$.cookie('invitecode', data.key, { expires: 7 });
					location.href='./';
				}});
			}else{
				layer.alert(data.msg, {icon:2});
				failfunc.call();
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
		var postData = {key:$_GET['i'],geetest_challenge:result.geetest_challenge,geetest_validate:result.geetest_validate,geetest_seccode:result.geetest_seccode};
		submitVerity(postData, function(){captchaObj.reset()});
	});
};
var handlerEmbed2 = function (token) {
	if (!token) {
		return alert('请完成验证');
	}
	var postData = {key:$_GET['i'],token:token};
	submitVerity(postData, function(){});
};
var handlerEmbed3 = function (vaptchaObj) {
	vaptchaObj.render();
	$('#captcha_text').hide();
	vaptchaObj.listen('pass', function() {
		var token = vaptchaObj.getToken();
		if (!token) {
			return alert('请完成验证');
		}
		var postData = {key:$_GET['i'],token:token};
		submitVerity(postData, function(){vaptchaObj.reset()});
	});
};
var codeSubmit = function () {
	var code = $("input[name=code]").val();
	if(code == ''){
		return alert('请输入验证码');
	}
	var postData = {key:$_GET['i'],code:code};
	submitVerity(postData, function(){});
};
$(document).ready(function(){
	var captcha_open = $("input[name=captcha_open]").val();
	var appid = $("input[name=appid]").val();
	if(captcha_open == 1){
		layer.open({
		  type: 1,
		  title: '完成验证',
		  closeBtn: false,
		  skin: 'layui-layer-rim',
		  area: ['320px', '100px'],
		  content: '<div id="captcha"><div id="captcha_text">正在加载验证码</div><div id="captcha_wait"><div class="loading"><div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div></div></div></div>',
		  success: function(){
			$.getScript("//static.geetest.com/static/tools/gt.js", function() {
				$.ajax({
					url: "./ajax.php?act=captcha&t=" + (new Date()).getTime(),
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
		  }
		});
	}else if(captcha_open == 2){
		layer.open({
		  type: 1,
		  title: '完成验证',
		  closeBtn: false,
		  skin: 'layui-layer-rim',
		  area: ['320px', '260px'],
		  content: '<div id="captcha" style="margin: auto;"><div id="captcha_text">正在加载验证码</div></div>',
		  success: function(){
			$.getScript("//cdn.dingxiang-inc.com/ctu-group/captcha-ui/index.js", function() {
				var myCaptcha = _dx.Captcha(document.getElementById('captcha'), {
					appId: appid,
					type: 'basic',
					style: 'embed',
					success: handlerEmbed2
				})
				myCaptcha.on('ready', function () {
					$('#captcha_text').hide();
				})
			});
		  }
		});
	}else if(captcha_open == 3){
		layer.open({
		  type: 1,
		  closeBtn: false,
		  title: '完成验证',
		  skin: 'layui-layer-rim',
		  area: ['320px', '231px'],
		  content: '<div id="captcha"><div id="captcha_text">正在加载验证码</div></div>',
		  success: function(){
			$.getScript("//v.vaptcha.com/v3.js", function() {
				vaptcha({
					vid: appid,
					type: 'embed',
					container: '#captcha',
					offline_server: 'https://management.vaptcha.com/api/v3/demo/offline'
				}).then(handlerEmbed3);
			});
		  }
		});
	}else{
		layer.open({
		  type: 1,
		  title: '完成验证',
		  skin: 'layui-layer-rim',
		  closeBtn: false,
		  area: ['320px', '146px'],
		  content: '<div class="input-group"><span class="input-group-addon" style="padding: 0"><img id="codeimg" src="./user/code.php?r=<?php echo time();?>" height="43" onclick="this.src=\'./user/code.php?r=\'+Math.random();" title="点击更换验证码"></span><input type="text" name="code" class="form-control input-lg" required="required" placeholder="输入验证码"/></div><input type="button" value="提交" onclick="codeSubmit()" id="submit_code" class="btn btn-success btn-block btn-lg"/>'
		});
	}
});