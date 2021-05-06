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
	var multi = $('#tid').attr('multi');
	var count = $('#tid').attr('count');
	var price = $('#tid').attr('price');
	var shopimg = $('#tid').attr('shopimg');
	var close = $('#tid').attr('close');
	$('#display_price').show();
	if(multi==1 && count>1){
		$('#need').val('￥'+price +"元 ➠ "+count+"个");
	}else{
		$('#need').val('￥'+price +"元");
	}
	if(close == 1){
		$('#submit_buy').val('停止下单');
		$('#submit_buy').html('停止下单');
		layer.alert('当前商品维护中，停止下单！');
	}else if(price == 0){
		$('#submit_buy').val('免费领取');
		$('#submit_buy').html('免费领取');
	}else{
		$('#submit_buy').val('立即购买');
		$('#submit_buy').html('立即购买');
	}
	if(multi == 1){
		$('#display_num').show();
	}else{
		$('#display_num').hide();
	}
	var desc = $('#tid').attr('desc');
	if(desc!='' && alert!='null'){
		$('#alert_frame').show();
		$('#alert_frame').html(unescape(desc));
	}else{
		$('#alert_frame').hide();
	}
	var inputnametype = '';
	$('#inputsname').html("");
	var inputname = $('#tid').attr('inputname');
	if(inputname=='hide'){
		$('#inputsname').append('<input type="hidden" name="inputvalue" id="inputvalue" value="'+$.cookie('mysid')+'"/>');
	}else{
		if(inputname=='')inputname='下单账号';
		if(inputname.indexOf('[')>0 && inputname.indexOf(']')>0){
			inputnametype = inputname.split('[')[1].split(']')[0];
			inputname = inputname.split('[')[0];
		}
		$('#inputsname').append('<div class="from bl_view_title"><div class="from_wz_3" id="inputname">'+inputname+'：</div><div class="from_in_2"><input type="text" name="inputvalue" id="inputvalue" value="'+($_GET['qq']?$_GET['qq']:'')+'" class="form-control" required onblur="checkInput()"/></div></div>');
	}
	var inputsname = $('#tid').attr('inputsname');
	if(inputsname!=''){
		$.each(inputsname.split('|'), function(i, value) {
			var inputsnametype = '';
			if(value.indexOf('[')>0 && value.indexOf(']')>0){
				inputsnametype = value.split('[')[1].split(']')[0];
				value = value.split('[')[0];
			}
			if(value.indexOf('{')>0 && value.indexOf('}')>0){
				var addstr = '';
				var selectname = value.split('{')[0];
				var selectstr = value.split('{')[1].split('}')[0];
				$.each(selectstr.split(','), function(i, v) {
					if(v.indexOf(':')>0){
						i = v.split(':')[0];
						v = v.split(':')[1];
					}else{
						i = v;
					}
					addstr += '<option value="'+i+'">'+v+'</option>';
				});
				$('#inputsname').append('<div class="from bl_view_title"><div class="from_wz_3" id="inputname'+(i+2)+'">'+selectname+'：</div><div class="from_in_2"><select name="inputvalue'+(i+2)+'" id="inputvalue'+(i+2)+'" class="form-control">'+addstr+'</select></div></div>');
			}else{
			if(value=='说说ID'||value=='说说ＩＤ'||inputsnametype=='ssid')
				var addstr='<div class="from_in_2 yanzheng onclick" onclick="get_shuoshuo(\'inputvalue'+(i+2)+'\',$(\'#inputvalue\').val())">自动获取</div>';
			else if(value=='日志ID'||value=='日志ＩＤ'||inputsnametype=='rzid')
				var addstr='<div class="from_in_2 yanzheng onclick" onclick="get_rizhi(\'inputvalue'+(i+2)+'\',$(\'#inputvalue\').val())">自动获取</div>';
			else if(value=='作品ID'||value=='作品ＩＤ'||inputsnametype=='zpid')
				var addstr='<div class="from_in_2 yanzheng onclick" onclick="getshareid2(\'inputvalue'+(i+2)+'\',$(\'#inputvalue\').val())">自动获取</div>';
			else if(value=='收货地址'||value=='收货人地址'||inputsnametype=='address')
				var addstr='<div class="from_in_2 yanzheng onclick" onclick="getCity(\'inputvalue'+(i+2)+'\')">点此选择</div>';
			else
				var addstr='';
			$('#inputsname').append('<div class="from bl_view_title"><div class="from_wz_3" id="inputname'+(i+2)+'" gettype="'+inputsnametype+'">'+value+'：</div><div class="from_in_2"><input type="text" name="inputvalue'+(i+2)+'" id="inputvalue'+(i+2)+'" value="" class="form-control" required/></div>'+addstr+'</div>');
			}
		});
	}
	if($("#inputname2").html() == '说说ID：'||$("#inputname2").html() == '说说ＩＤ：'||$("#inputname2").attr('gettype')=='ssid'){
		$('#inputvalue2').attr("disabled", true);
		$('#inputvalue2').attr("placeholder", "填写QQ账号后点击→");
	}else if($("#inputname").html() == '作品ID：'||$("#inputname").html() == '作品ＩＤ：'||$("#inputname").html() == '帖子ID：'||$("#inputname").html() == '用户ID：'||$("#inputname").html() == '用户ＩＤ：'||inputnametype=='shareid'){
		$('#inputvalue').attr("placeholder", "在此输入分享链接 可自动获取");
		$('#inputname').attr("gettype", "shareid");
		if($("#inputname2").html() == '作品ID：'||$("#inputname2").html() == '作品ＩＤ：'||$("#inputname2").attr('gettype')=='zpid'){
			$('#inputvalue2').attr("placeholder", "填写作品链接后点击→");
			$("#inputvalue2").attr('disabled', true);
		}
	}else if($("#inputname").html() == '作品链接：'||$("#inputname").html() == '视频链接：'||$("#inputname").html() == '分享链接：'||inputnametype=='shareurl'){
		$('#inputvalue').attr("placeholder", "在此输入复制后的链接 可自动转换");
		$('#inputname').attr("gettype", "shareurl");
	}else{
		$('#inputvalue').removeAttr("placeholder");
		$('#inputvalue2').removeAttr("placeholder");
	}
	if($('#tid').attr('isfaka')==1){
		$('#inputvalue').attr("placeholder", "用于接收卡密和查询订单");
		$('#display_left').show();
		if($.cookie('email'))$('#inputvalue').val($.cookie('email'));
	}else{
		$('#display_left').hide();
	}
	var alert = $('#tid').attr('alert');
	if(alert!='' && alert!='null'){
		var ii=layer.alert(''+unescape(alert)+'',{
			btn:['我知道了'],
			title:'商品提示'
		},function(){
			layer.close(ii);
		});
	}
}
function get_shuoshuo(id,uin,km,page){
	km = km || 0;
	page = page || 1;
	if(uin==''){
		layer.alert('请先填写QQ号！');return false;
	}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "GET",
		url : "ajax.php?act=getshuoshuo&uin="+uin+"&page="+page+"&hashsalt="+hashsalt,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var addstr='';
				$.each(data.data, function(i, item){
					addstr+='<option value="'+item.tid+'">'+item.content+'</option>';
				});
				var nextpage = page+1;
				var lastpage = page>1?page-1:1;
				if($('#show_shuoshuo').length > 0){
					$('#show_shuoshuo').html('<div class="from_wz_3 onclick" title="上一页" onclick="get_shuoshuo(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+lastpage+')">上一页</div><div class="from_in_2"><select id="shuoid" class="form-control" onchange="set_shuoshuo(\''+id+'\');">'+addstr+'</select></div><div class="from_in_2 yanzheng onclick" title="下一页" onclick="get_shuoshuo(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+nextpage+')">下一页</div>');
				}else{
					$('#inputsname').append('<div class="from bl_view_title" id="show_shuoshuo"><div class="from_wz_3 onclick" title="上一页" onclick="get_shuoshuo(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+lastpage+')">上一页</div><div class="from_in_2"><select id="shuoid" class="form-control" onchange="set_shuoshuo(\''+id+'\');">'+addstr+'</select></div><div class="from_in_2 yanzheng onclick" title="下一页" onclick="get_shuoshuo(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+nextpage+')">下一页</div></div>');
				}
				set_shuoshuo(id);
			}else{
				layer.alert(data.msg);
			}
		} 
	});
}
function set_shuoshuo(id){
	var shuoid = $('#shuoid').val();
	$('#'+id).val(shuoid);
}
function get_rizhi(id,uin,km,page){
	km = km || 0;
	page = page || 1;
	if(uin==''){
		layer.alert('请先填写QQ号！');return false;
	}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "GET",
		url : "ajax.php?act=getrizhi&uin="+uin+"&page="+page+"&hashsalt="+hashsalt,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var addstr='';
				$.each(data.data, function(i, item){
					addstr+='<option value="'+item.blogId+'">'+item.title+'</option>';
				});
				var nextpage = page+1;
				var lastpage = page>1?page-1:1;
				if($('#show_rizhi').length > 0){
					$('#show_rizhi').html('<div class="input-group"><div class="from_wz_3 onclick" onclick="get_rizhi(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+lastpage+')"><i class="fa fa-chevron-left"></i></div><select id="blogid" class="form-control" onchange="set_rizhi(\''+id+'\');">'+addstr+'</select><div class="from_in_2 yanzheng onclick" onclick="get_rizhi(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+nextpage+')"><i class="fa fa-chevron-right"></i></div></div>');
				}else{
					$('#inputsname').append('<div class="from bl_view_title" id="show_rizhi"><div class="input-group"><div class="from_wz_3 onclick" onclick="get_rizhi(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+lastpage+')"><i class="fa fa-chevron-left"></i></div><select id="blogid" class="form-control" onchange="set_rizhi(\''+id+'\');">'+addstr+'</select><div class="from_in_2 yanzheng onclick" onclick="get_rizhi(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+nextpage+')"><i class="fa fa-chevron-right"></i></div></div></div>');
				}
				set_rizhi(id);
			}else{
				layer.alert(data.msg);
			}
		} 
	});
}
function set_rizhi(id){
	var blogid = $('#blogid').val();
	$('#'+id).val(blogid);
}
function getsongid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('.qq.com')<0){layer.alert('请输入正确的歌曲的分享链接！');return false;}
	try{
		var songid = songurl.split('s=')[1].split('&')[0];
		layer.msg('ID获取成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的歌曲的分享链接！');return false;
	}
	$('#inputvalue').val(songid);
}
function getsharelink(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('http')<0){layer.alert('请输入正确的内容！');return false;}
	try{
		if(songurl.indexOf('http://')>=0){
			var songid = 'http://' + songurl.split('http://')[1].split(' ')[0].split('，')[0];
		}else if(songurl.indexOf('https://')>=0){
			var songid = 'https://' + songurl.split('https://')[1].split(' ')[0].split('，')[0];
		}
		if(songid != $("#inputvalue").val())layer.msg('链接转换成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的内容！');return false;
	}
	$('#inputvalue').val(songid);
}
function getshareid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('http')<0){layer.alert('请输入正确的内容！');return false;}
	try{
		if(songurl.indexOf('http://')>=0){
			var songurl = 'http://' + songurl.split('http://')[1].split(' ')[0].split('，')[0];
		}else if(songurl.indexOf('https://')>=0){
			var songurl = 'https://' + songurl.split('https://')[1].split(' ')[0].split('，')[0];
		}else{
			throw false;
		}
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=getshareid",
			data : {url:songurl, hashsalt:hashsalt},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					$('#inputvalue').val(data.songid);
					if(typeof data.songid2 != "undefined" && $('#inputvalue2').length>0)$('#inputvalue2').val(data.songid2);
					layer.msg('ID获取成功！下单即可');
				}else{
					layer.alert(data.msg);return false;
				}
			}
		});
	}catch(e){
		layer.alert('请输入正确的内容！');return false;
	}
}
function getshareid2(id, songurl){
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('http')<0){return false;}
	getshareid();
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
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=pay",
			data : {tid:$("#tid").val(),inputvalue:$("#inputvalue").val(),inputvalue2:$("#inputvalue2").val(),inputvalue3:$("#inputvalue3").val(),inputvalue4:$("#inputvalue4").val(),inputvalue5:$("#inputvalue5").val(),num:$("#num").val(),hashsalt:hashsalt,geetest_challenge:result.geetest_challenge,geetest_validate:result.geetest_validate,geetest_seccode:result.geetest_seccode},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code >= 0){
					$('#alert_frame').hide();
					alert('领取成功！');
					window.location.href='?buyok=1';
				}else{
					layer.alert(data.msg);
					captchaObj.reset();
				}
			} 
		});
	});
};
var handlerEmbed2 = function (token) {
	if (!token) {
		return alert('请完成验证');
	}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax.php?act=pay",
		data : {tid:$("#tid").val(),inputvalue:$("#inputvalue").val(),inputvalue2:$("#inputvalue2").val(),inputvalue3:$("#inputvalue3").val(),inputvalue4:$("#inputvalue4").val(),inputvalue5:$("#inputvalue5").val(),num:$("#num").val(),hashsalt:hashsalt,token:token},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code >= 0){
				$('#alert_frame').hide();
				alert('领取成功！');
				window.location.href='?buyok=1';
			}else{
				layer.alert(data.msg);
			}
		} 
	});
};
var handlerEmbed3 = function (vaptchaObj) {
	vaptchaObj.render();
	$('#captcha_text').hide();
	vaptchaObj.listen('pass', function() {
		var token = vaptchaObj.getToken();
		if (!token) {
			return alert('请完成验证');
		}
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=pay",
			data : {tid:$("#tid").val(),inputvalue:$("#inputvalue").val(),inputvalue2:$("#inputvalue2").val(),inputvalue3:$("#inputvalue3").val(),inputvalue4:$("#inputvalue4").val(),inputvalue5:$("#inputvalue5").val(),num:$("#num").val(),hashsalt:hashsalt,token:token},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code >= 0){
					$('#alert_frame').hide();
					alert('领取成功！');
					window.location.href='?buyok=1';
				}else{
					layer.alert(data.msg);
					vaptchaObj.reset();
				}
			}
		});
	});
};
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
					window.location.href='?buyok=1';
				}else if(data.code == -2){
					alert(data.msg);
					window.location.href='?buyok=1';
				}else if(data.code == -3){
					var confirmobj = layer.confirm('你的余额不足，请充值！', {
					  btn: ['立即充值','取消']
					}, function(){
						window.location.href='./user/recharge.php';
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
function cancel(orderid){
	layer.closeAll();
	$.ajax({
		type : "POST",
		url : "ajax.php?act=cancel",
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
function checkInput() {
	if($('#inputname').attr("gettype")=='shareid'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			getshareid();
		}
	}
	else if($('#inputname').attr("gettype")=='shareurl'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			getsharelink();
		}
	}
}
function getCity(inputid,fid,i){
	i = i || 0;
	fid = fid || 0;
	if(i == 0){
		var options='<select class="form-control" id="biaozhi_'+(i+1)+'" onchange="getCity(\''+inputid+'\',this.value,'+(i+1)+')">';
		options+='<option>请选择地址</option>';
		$.each("\u5317\u4eac|1|72|1,\u4e0a\u6d77|2|78|1,\u5929\u6d25|3|51035|1,\u91cd\u5e86|4|113|1,\u6cb3\u5317|5|142,\u5c71\u897f|6|303,\u6cb3\u5357|7|412,\u8fbd\u5b81|8|560,\u5409\u6797|9|639,\u9ed1\u9f99\u6c5f|10|698,\u5185\u8499\u53e4|11|799,\u6c5f\u82cf|12|904,\u5c71\u4e1c|13|1000,\u5b89\u5fbd|14|1116,\u6d59\u6c5f|15|1158,\u798f\u5efa|16|1303,\u6e56\u5317|17|1381,\u6e56\u5357|18|1482,\u5e7f\u4e1c|19|1601,\u5e7f\u897f|20|1715,\u6c5f\u897f|21|1827,\u56db\u5ddd|22|1930,\u6d77\u5357|23|2121,\u8d35\u5dde|24|2144,\u4e91\u5357|25|2235,\u897f\u85cf|26|2951,\u9655\u897f|27|2376,\u7518\u8083|28|2487,\u9752\u6d77|29|2580,\u5b81\u590f|30|2628,\u65b0\u7586|31|2652,\u6e2f\u6fb3|52993|52994,\u53f0\u6e7e|32|2768,\u9493\u9c7c\u5c9b|84|84".split(","), function(a, c) {
			c = c.split("|"),
			options+='<option value="'+c[1]+'">'+c[0]+'</option>'
		});
		options+='</select>';
		layer.alert('<div id="layer_button">'+options+'</div>',function(index){
			var con='';
			$("#layer_button select").each(function(){
				con+=$(this.options[this.selectedIndex]).text();
			});
			if($("#more_dizhi").length>0)con+=$("#more_dizhi").val();
			if(con.length<7)return layer.alert('请选择完整的收货地址！');
			$("#"+inputid).val(con).show();
			$("#button_"+inputid).hide();
			layer.close(index);
		});
	}else{
	$.ajax({
		type:"get",
		url:"https://fts.jd.com/area/get?fid="+fid,
		dataType:"jsonp",
		success:function(data){
			if(data.length<1){
				if($("#layer_button").html().indexOf("getCity('"+inputid+"',this.value,"+(i+1)+")")!=-1){
					$("#biaozhi_"+(i+1)).remove();
				}
				if($("#more_dizhi").length>0){}else $("#layer_button").append('<input class="form-control" id="more_dizhi" placeholder="详细地址(村、门牌号)">');
				return false;
			}
			var options='<select class="form-control" id="biaozhi_'+(i+1)+'" onchange="getCity(\''+inputid+'\',this.value,'+(i+1)+')">';
			options+='<option>请选择地址</option>';
			$.each(data,function(index,res){
				options+='<option value="'+res.id+'">'+res.name+'</option>';
			});
			options+='</select>';
			if($("#layer_button").html().indexOf("getCity('"+inputid+"',this.value,"+(i+1)+")")!=-1){
				$("#more_dizhi").remove();
				$("#biaozhi_"+(i+1)).html(options);
			}else{
				$("#layer_button").append(options);
			}
		}
	});
	}
}
function openCart(){
	window.location.href='./?mod=cart';
}
$(document).ready(function(){
	$("#submit_buy").click(function(){
		var tid=$("#tid").val();
		if(tid==0){layer.alert('请选择商品！');return false;}
		var inputvalue=$("#inputvalue").val();
		if(inputvalue=='' || tid==''){layer.alert('请确保每项不能为空！');return false;}
		if($("#inputvalue2").val()=='' || $("#inputvalue3").val()=='' || $("#inputvalue4").val()=='' || $("#inputvalue5").val()==''){layer.alert('请确保每项不能为空！');return false;}
		if(($('#inputname').html()=='下单ＱＱ：' || $('#inputname').html()=='ＱＱ账号：' || $("#inputname").html() == 'QQ账号：') && (inputvalue.length<5 || inputvalue.length>11 || isNaN(inputvalue))){layer.alert('请输入正确的QQ号！');return false;}
		var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
		if($('#inputname').html()=='你的邮箱：' && !reg.test(inputvalue)){layer.alert('邮箱格式不正确！');return false;}
		reg=/^[1][0-9]{10}$/;
		if($('#inputname').html()=='手机号码：' && !reg.test(inputvalue)){layer.alert('手机号码格式不正确！');return false;}
		if($("#inputname2").html() == '说说ID：'||$("#inputname2").html() == '说说ＩＤ：'){
			if($("#inputvalue2").val().length != 24){layer.alert('说说必须是原创说说！');return false;}
		}
		checkInput();
		if($("#inputname").html() == '抖音作品ID：'||$("#inputname").html() == '火山作品ID：'||$("#inputname").html() == '火山直播ID：'){
			if($("#inputvalue").val().length != 19){layer.alert('您输入的作品ID有误！');return false;}
		}
		if($("#inputname2").html() == '抖音评论ID：'){
			if($("#inputvalue2").val().length != 19){layer.alert('您输入的评论ID有误！请点击自动获取手动选择评论！');return false;}
		}
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=pay",
			data : {tid:tid,inputvalue:$("#inputvalue").val(),inputvalue2:$("#inputvalue2").val(),inputvalue3:$("#inputvalue3").val(),inputvalue4:$("#inputvalue4").val(),inputvalue5:$("#inputvalue5").val(),num:$("#num").val(),hashsalt:hashsalt},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					if($('#inputname').html()=='你的邮箱：'){
						$.cookie('email', inputvalue);
					}
					window.location.href='./?mod=order&orderid='+data.trade_no;
				}else if(data.code == 1){
					if($('#inputname').html()=='你的邮箱：'){
						$.cookie('email', inputvalue);
					}
					alert('领取成功！');
					window.location.href='?buyok=1';
				}else if(data.code == 2){
					if(data.type == 1){
						layer.open({
						  type: 1,
						  title: '完成验证',
						  skin: 'layui-layer-rim',
						  area: ['320px', '100px'],
						  content: '<div id="captcha"><div id="captcha_text">正在加载验证码</div><div id="captcha_wait"><div class="loading"><div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div></div></div></div>',
						  success: function(){
							$.getScript("//static.geetest.com/static/tools/gt.js", function() {
								$.ajax({
									url: "ajax.php?act=captcha&t=" + (new Date()).getTime(),
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
					}else if(data.type == 2){
						layer.open({
						  type: 1,
						  title: '完成验证',
						  skin: 'layui-layer-rim',
						  area: ['320px', '260px'],
						  content: '<div id="captcha" style="margin: auto;"><div id="captcha_text">正在加载验证码</div></div>',
						  success: function(){
							$.getScript("//cdn.dingxiang-inc.com/ctu-group/captcha-ui/index.js", function() {
								var myCaptcha = _dx.Captcha(document.getElementById('captcha'), {
									appId: data.appid,
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
					}else if(data.type == 3){
						layer.open({
						  type: 1,
						  title: '完成验证',
						  skin: 'layui-layer-rim',
						  area: ['320px', '231px'],
						  content: '<div id="captcha"><div id="captcha_text">正在加载验证码</div></div>',
						  success: function(){
							$.getScript("//v.vaptcha.com/v3.js", function() {
								vaptcha({
									vid: data.appid,
									type: 'embed',
									container: '#captcha',
									offline_server: 'https://management.vaptcha.com/api/v3/demo/offline'
								}).then(handlerEmbed3);
							});
						  }
						});
					}
				}else if(data.code == 3){
					layer.alert(data.msg, {
						closeBtn: false
					}, function(){
						window.location.reload();
					});
				}else if(data.code == 4){
					var confirmobj = layer.confirm('请登录后再购买，是否现在登录？', {
					  btn: ['登录','注册','取消']
					}, function(){
						window.location.href='./user/login.php';
					}, function(){
						window.location.href='./user/reg.php';
					}, function(){
						layer.close(confirmobj);
					});
				}else{
					layer.alert(data.msg,{icon:2});
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
	var multi = $('#tid').attr('multi');
	var count = parseInt($('#tid').attr('count'));
	if (multi == '0'){
		layer.alert('该商品不支持选择数量');
		return false;
	}
	i++;
	$("#num").val(i);
	var price = parseFloat($('#tid').attr('price'));
	var prices = $('#tid').attr('prices');
	if(prices!='' || prices!='null'){
		var discount = 0;
		$.each(prices.split(','), function(index, item){
			if(i>=parseInt(item.split('|')[0]))discount = parseFloat(item.split('|')[1]);
		});
		price = price - discount;
	}
	price = price * i;
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
	var multi = $('#tid').attr('multi');
	var count = parseInt($('#tid').attr('count'));
	if (multi == '0'){
		layer.alert('该商品不支持选择数量');
		return false;
	}
	i--;
	if (i <= 0) i = 1;
	$("#num").val(i);
	var price = parseFloat($('#tid').attr('price'));
	var prices = $('#tid').attr('prices');
	if(prices!='' || prices!='null'){
		var discount = 0;
		$.each(prices.split(','), function(index, item){
			if(i>=parseInt(item.split('|')[0]))discount = parseFloat(item.split('|')[1]);
		});
		price = price - discount;
	}
	price = price * i;
	count = count * i;
	if(count>1)$('#need').val('￥'+price.toFixed(2) +"元 ➠ "+count+"个");
	else $('#need').val('￥'+price.toFixed(2) +"元");
});
$("#num").keyup(function () {
	var i = parseInt($("#num").val());
	if(isNaN(i))return false;
	var price = parseFloat($('#tid').attr('price'));
	var count = parseInt($('#tid').attr('count'));
	var prices = $('#tid').attr('prices');
	if(i<1) $("#num").val(1);
	if(prices!='' || prices!='null'){
		var discount = 0;
		$.each(prices.split(','), function(index, item){
			if(i>=parseInt(item.split('|')[0]))discount = parseFloat(item.split('|')[1]);
		});
		price = price - discount;
	}
	price = price * i;
	count = count * i;
	if(count>1)$('#need').val('￥'+price.toFixed(2) +"元 ➠ "+count+"个");
	else $('#need').val('￥'+price.toFixed(2) +"元");
});

getPoint();
});

