var interval1,interval2;
function showqrcode(qrurl){
	$('#qrimg').qrcode({
		text: qrurl,
		width: 180,
		height: 180,
		foreground: "#000000",
		background: "#ffffff",
		typeNumber: -1
	});
}
function getqrpic(){
	cleartime();
	$('#qrimg').html('');
	var getvcurl='qrlogin.php?do=getqrpic&r='+Math.random(1);
	$.get(getvcurl, function(d) {
		if(d.code ==0){
			$('#qrimg').attr('qrtoken',d.token);
			$('#qrimg').attr('qrurl',d.qrurl);
			showqrcode(d.qrurl);
			if( /Android|SymbianOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Windows Phone|Midp/i.test(navigator.userAgent) && navigator.userAgent.indexOf("QQ/") == -1) {
				$('#mobile').show();
			}
			interval1=setInterval(loginload,1000);
			interval2=setInterval(qrlogin,1500);
		}else{
			alert(d.msg);
		}
	}, 'json');
}
function qrlogin(){
	if ($('#login').attr("data-lock") === "true") return;
	var token=$('#qrimg').attr('qrtoken');
	var url = 'qrlogin.php?do=qrlogin&token='+decodeURIComponent(token)+'&r='+Math.random(1);
	$.get(url, function(d) {
		if(d.code ==0){
			var typename = d.type=='qq'?'QQ':'微信';
			$('#loginmsg').html(typename+'已成功登录！');
			$('#qrimg').hide();
			$('#submit').hide();
			$('#login').attr("data-lock", "true");
			cleartime();
			if(isbind && bindtype != d.type){
				alert('请使用'+bindtypename+'扫描二维码！');
				top.location.reload();
			}
			$.get("login.php?act=qrlogin&r="+Math.random(1), function(arr) {
				if(arr.code==1) {
					alert(arr.msg);
					if(arr.url == 'reload') top.location.reload();
					else window.location.href=arr.url;
				}else{
					alert(arr.msg);
					top.location.reload();
				}
			}, 'json');
		}else if(d.code ==1){
			$('#loginmsg').html('请使用'+(isbind?bindtypename:'微信或QQ')+'扫描二维码');
		}else if(d.code ==2){
			$('#loginmsg').html('请在手机上确认授权登录');
		}else{
			cleartime();
			$('#loginmsg').html(d.msg);
		}
	}, 'json');
}
function loginload(){
	if ($('#login').attr("data-lock") === "true") return;
	var load=document.getElementById('loginload').innerHTML;
	var len=load.length;
	if(len>2){
		load='.';
	}else{
		load+='.';
	}
	document.getElementById('loginload').innerHTML=load;
}
function cleartime(){
	clearInterval(interval1);
	clearInterval(interval2);
}
function mloginurl(){
	var qrurl = $('#qrimg').attr('qrurl');
	$('#loginmsg').html('跳转到QQ登录后请返回此页面');
	window.location.href='mqqapi://forward/url?version=1&src_type=web&url_prefix='+window.btoa(qrurl);
}
$(document).ready(function(){
	getqrpic();
	$("#qrimg").click(function(){
		getqrpic();
	});
});