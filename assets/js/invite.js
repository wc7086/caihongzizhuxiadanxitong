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
		$('#alert_frame').hide();
		return false;
	}
	var multi = $('#tid option:selected').attr('multi');
	var count = $('#tid option:selected').attr('count');
	var price = $('#tid option:selected').attr('price');
	var shopimg = $('#tid option:selected').attr('shopimg');
	var close = $('#tid option:selected').attr('close');
	var desc = $('#tid option:selected').attr('desc');
	if(desc!='' && alert!='null'){
		$('#alert_frame').show();
		$('#alert_frame').html(unescape(desc));
	}else{
		$('#alert_frame').hide();
	}
	var inputnametype = '';
	$('#inputsname').html("");
	var inputname = $('#tid option:selected').attr('inputname');
	if(inputname=='hide'){
		$('#inputsname').append('<input type="hidden" name="inputvalue" id="inputvalue" value="'+$.cookie('mysid')+'"/>');
	}else{
		if(inputname=='')inputname='下单账号';
		if(inputname.indexOf('[')>0 && inputname.indexOf(']')>0){
			inputnametype = inputname.split('[')[1].split(']')[0];
			inputname = inputname.split('[')[0];
		}
		$('#inputsname').append('<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname">'+inputname+'</div><input type="text" name="inputvalue" id="inputvalue" value="'+($_GET['qq']?$_GET['qq']:'')+'" class="form-control" required onblur="checkInput()"/></div></div>');
	}
	var inputsname = $('#tid option:selected').attr('inputsname');
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
				$('#inputsname').append('<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname'+(i+2)+'">'+selectname+'</div><select name="inputvalue'+(i+2)+'" id="inputvalue'+(i+2)+'" class="form-control">'+addstr+'</select></div></div>');
			}else{
			if(value=='说说ID'||value=='说说ＩＤ'||inputsnametype=='ssid')
				var addstr='<div class="input-group-addon onclick" onclick="get_shuoshuo(\'inputvalue'+(i+2)+'\',$(\'#inputvalue\').val())">自动获取</div>';
			else if(value=='日志ID'||value=='日志ＩＤ'||inputsnametype=='rzid')
				var addstr='<div class="input-group-addon onclick" onclick="get_rizhi(\'inputvalue'+(i+2)+'\',$(\'#inputvalue\').val())">自动获取</div>';
			else if(value=='作品ID'||value=='作品ＩＤ'||inputsnametype=='zpid')
				var addstr='<div class="input-group-addon onclick" onclick="getshareid2(\'inputvalue'+(i+2)+'\',$(\'#inputvalue\').val())">自动获取</div>';
			else if(value=='收货地址'||value=='收货人地址'||inputsnametype=='address')
				var addstr='<div class="input-group-addon onclick" onclick="getCity(\'inputvalue'+(i+2)+'\')">点此选择</div>';
			else
				var addstr='';
			$('#inputsname').append('<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname'+(i+2)+'" gettype="'+inputsnametype+'">'+value+'</div><input type="text" name="inputvalue'+(i+2)+'" id="inputvalue'+(i+2)+'" value="" class="form-control" required/>'+addstr+'</div></div>');
			}
		});
	}
	if($("#inputname2").html() == '说说ID'||$("#inputname2").html() == '说说ＩＤ'||$("#inputname2").attr('gettype')=='ssid'){
		$('#inputvalue2').attr("disabled", true);
		$('#inputvalue2').attr("placeholder", "填写QQ账号后点击→");
	}else if($("#inputname").html() == '作品ID'||$("#inputname").html() == '作品ＩＤ'||$("#inputname").html() == '帖子ID'||$("#inputname").html() == '用户ID'||$("#inputname").html() == '用户ＩＤ'||inputnametype=='shareid'){
		$('#inputvalue').attr("placeholder", "在此输入分享链接 可自动获取");
		$('#inputname').attr("gettype", "shareid");
		if($("#inputname2").html() == '作品ID'||$("#inputname2").html() == '作品ＩＤ'||$("#inputname2").attr('gettype')=='zpid'){
			$('#inputvalue2').attr("placeholder", "填写作品链接后点击→");
			$("#inputvalue2").attr('disabled', true);
		}
	}else if($("#inputname").html() == '火山ID'||$("#inputname").html() == '火山作品ID'||$("#inputname").html() == '火山视频ID'||$("#inputname").html() == '火山ＩＤ'){
		$('#inputvalue').attr("placeholder", "在此输入火山视频的链接 可自动获取");
		$('#inputname').attr("gettype", "huoshan");
	}else if($("#inputname").html() == '微视ID'||$("#inputname").html() == '微视作品ID'||$("#inputname").html() == '微视ＩＤ'){
		$('#inputvalue').attr("placeholder", "在此输入微视的作品链接 可自动获取");
		$('#inputname').attr("gettype", "weishi");
	}else if($("#inputname").html() == '微视主页ID'){
		$('#inputvalue').attr("placeholder", "在此输入微视的主页链接 可自动获取");
		$('#inputname').attr("gettype", "weishi");
	}else if($("#inputname").html() == '小红书ID'||$("#inputname").html() == '小红书作品ID'){
		$('#inputvalue').attr("placeholder", "在此输入小红书的作品链接 可自动获取");
		$('#inputname').attr("gettype", "xiaohongshu");
	}else if($("#inputname").html() == '皮皮虾ID'||$("#inputname").html() == '皮皮虾作品ID'){
		$('#inputvalue').attr("placeholder", "在此输入皮皮虾的作品链接 可自动获取");
		$('#inputname').attr("gettype", "pipixia");
	}else if($("#inputname").html() == '头条ID'||$("#inputname").html() == '头条ＩＤ'){
		$('#inputvalue').attr("placeholder", "在此输入今日头条的链接 可自动获取");
		$('#inputname').attr("gettype", "toutiao");
	}else if($("#inputname").html() == '美拍ID'||$("#inputname").html() == '美拍ＩＤ'||$("#inputname").html() == '美拍作品ID'||$("#inputname").html() == '美拍视频ID'){
		$('#inputvalue').attr("placeholder", "在此输入美拍视频链接 可自动获取");
		$('#inputname').attr("gettype", "meipai");
	}else if($("#inputname").html() == '哔哩哔哩视频ID'||$("#inputname").html() == '哔哩哔哩ID'||$("#inputname").html() == '哔哩视频ID'){
		$('#inputvalue').attr("placeholder", "在此输入哔哩哔哩视频链接 可自动获取");
		$('#inputname').attr("gettype", "bilibili");
	}else if($("#inputname").html() == '最右帖子ID'){
		$('#inputvalue').attr("placeholder", "在此输入最右帖子链接 可自动获取");
		$('#inputname').attr("gettype", "zuiyou");
	}else if($("#inputname").html() == '全民视频ID'||$("#inputname").html() == '全民小视频ID'){
		$('#inputvalue').attr("placeholder", "在此输入全民小视频链接 可自动获取");
		$('#inputname').attr("gettype", "quanmin");
	}else if($("#inputname").html() == '美图作品ID'||$("#inputname").html() == '美图视频ID'){
		$('#inputvalue').attr("placeholder", "在此输入美图作品链接 可自动获取");
		$('#inputname').attr("gettype", "meitu");
	}else if($("#inputname").html() == '绿洲作品ID'||$("#inputname").html() == '绿洲视频ID'){
		$('#inputvalue').attr("placeholder", "在此输入绿洲作品分享链接 可自动获取");
		$('#inputname').attr("gettype", "oasis");
	}else if($("#inputname").html() == '作品链接'||$("#inputname").html() == '视频链接'||$("#inputname").html() == '分享链接'||inputnametype=='shareurl'){
		$('#inputvalue').attr("placeholder", "在此输入复制后的链接 可自动转换");
		$('#inputname').attr("gettype", "shareurl");
	}else{
		$('#inputvalue').removeAttr("placeholder");
		$('#inputvalue2').removeAttr("placeholder");
	}
	if($('#tid option:selected').attr('isfaka')==1){
		$('#inputvalue').attr("placeholder", "用于接收卡密以及查询订单使用");
		$('#display_left').show();
		$.ajax({
			type : "POST",
			url : "ajax.php?act=getleftcount",
			data : {tid:$('#tid option:selected').val()},
			dataType : 'json',
			success : function(data) {
				$('#leftcount').val(data.count)
			}
		});
		if($.cookie('email'))$('#inputvalue').val($.cookie('email'));
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
					if(km==1){
						$('#show_shuoshuo').html('<div class="input-group"><div class="input-group-addon onclick" title="上一页" onclick="get_shuoshuo(\''+id+'\',$(\'#km_inputvalue\').val(),'+km+','+lastpage+')"><i class="fa fa-chevron-left"></i></div><select id="shuoid" class="form-control" onchange="set_shuoshuo(\''+id+'\');">'+addstr+'</select><div class="input-group-addon onclick" title="下一页" onclick="get_shuoshuo(\''+id+'\',$(\'#km_inputvalue\').val(),'+km+','+nextpage+')"><i class="fa fa-chevron-right"></i></div></div>');
					}else{
						$('#show_shuoshuo').html('<div class="input-group"><div class="input-group-addon onclick" title="上一页" onclick="get_shuoshuo(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+lastpage+')"><i class="fa fa-chevron-left"></i></div><select id="shuoid" class="form-control" onchange="set_shuoshuo(\''+id+'\');">'+addstr+'</select><div class="input-group-addon onclick" title="下一页" onclick="get_shuoshuo(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+nextpage+')"><i class="fa fa-chevron-right"></i></div></div>');
					}
				}else{
					if(km==1){
						$('#km_inputsname').append('<div class="form-group" id="show_shuoshuo"><div class="input-group"><div class="input-group-addon onclick" title="上一页" onclick="get_shuoshuo(\''+id+'\',$(\'#km_inputvalue\').val(),'+km+','+lastpage+')"><i class="fa fa-chevron-left"></i></div><select id="shuoid" class="form-control" onchange="set_shuoshuo(\''+id+'\');">'+addstr+'</select><div class="input-group-addon onclick" title="下一页" onclick="get_shuoshuo(\''+id+'\',$(\'#km_inputvalue\').val(),'+km+','+nextpage+')"><i class="fa fa-chevron-right"></i></div></div></div>');
					}else{
						$('#inputsname').append('<div class="form-group" id="show_shuoshuo"><div class="input-group"><div class="input-group-addon onclick" title="上一页" onclick="get_shuoshuo(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+lastpage+')"><i class="fa fa-chevron-left"></i></div><select id="shuoid" class="form-control" onchange="set_shuoshuo(\''+id+'\');">'+addstr+'</select><div class="input-group-addon onclick" title="下一页" onclick="get_shuoshuo(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+nextpage+')"><i class="fa fa-chevron-right"></i></div></div></div>');
					}
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
					$('#show_rizhi').html('<div class="input-group"><div class="input-group-addon onclick" onclick="get_rizhi(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+lastpage+')"><i class="fa fa-chevron-left"></i></div><select id="blogid" class="form-control" onchange="set_rizhi(\''+id+'\');">'+addstr+'</select><div class="input-group-addon onclick" onclick="get_rizhi(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+nextpage+')"><i class="fa fa-chevron-right"></i></div></div>');
				}else{
					if(km==1){
						$('#km_inputsname').append('<div class="form-group" id="show_rizhi"><div class="input-group"><div class="input-group-addon onclick" onclick="get_rizhi(\''+id+'\',$(\'#km_inputvalue\').val(),'+km+','+lastpage+')"><i class="fa fa-chevron-left"></i></div><select id="blogid" class="form-control" onchange="set_rizhi(\''+id+'\');">'+addstr+'</select><div class="input-group-addon onclick" onclick="get_rizhi(\''+id+'\',$(\'#km_inputvalue\').val(),'+km+','+nextpage+')"><i class="fa fa-chevron-right"></i></div></div></div>');
					}else{
						$('#inputsname').append('<div class="form-group" id="show_rizhi"><div class="input-group"><div class="input-group-addon onclick" onclick="get_rizhi(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+lastpage+')"><i class="fa fa-chevron-left"></i></div><select id="blogid" class="form-control" onchange="set_rizhi(\''+id+'\');">'+addstr+'</select><div class="input-group-addon onclick" onclick="get_rizhi(\''+id+'\',$(\'#inputvalue\').val(),'+km+','+nextpage+')"><i class="fa fa-chevron-right"></i></div></div></div>');
					}
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
function fillOrder(id,skey){
	if(!confirm('是否确定补交订单？'))return;
	$.ajax({
		type : "POST",
		url : "ajax.php?act=fill",
		data : {orderid:id,skey:skey},
		dataType : 'json',
		success : function(data) {
			layer.alert(data.msg);
			$("#submit_query").click();
		}
	});
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
function gethuoshanid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('.huoshan.com')<0){layer.alert('请输入正确的链接！');return false;}
	if(songurl.indexOf('/s/')>0){
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=gethuoshan",
			data : {url:songurl},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					$('#inputvalue').val(data.songid);
					layer.msg('ID获取成功！下单即可');
				}else{
					layer.alert(data.msg);return false;
				}
			}
		});
	}else{
		try{
			if(songurl.indexOf('video/')>0){
				var songid = songurl.split('video/')[1].split('/')[0];
			}else if(songurl.indexOf('item/')>0){
				var songid = songurl.split('item/')[1].split('/')[0];
			}else if(songurl.indexOf('room/')>0){
				var songid = songurl.split('room/')[1].split('/')[0];
			}else{
				var songid = songurl.split('user/')[1].split('/')[0];
			}
			layer.msg('ID获取成功！下单即可');
		}catch(e){
			layer.alert('请输入正确的链接！');return false;
		}
		$('#inputvalue').val(songid);
	}
}
function getdouyinid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('.douyin.com')<0 && songurl.indexOf('.iesdouyin.com')<0){layer.alert('请输入正确的链接！');return false;}
	if(songurl.indexOf('/v.douyin.com/')>0){
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=getdouyin",
			data : {url:songurl},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					$('#inputvalue').val(data.songid);
					layer.msg('ID获取成功！下单即可');
				}else{
					layer.alert(data.msg);return false;
				}
			}
		});
	}else{
	try{
		if(songurl.indexOf('video/')>0){
			var songid = songurl.split('video/')[1].split('/')[0];
		}else if(songurl.indexOf('music/')>0){
			var songid = songurl.split('music/')[1].split('/')[0];
		}else{
			var songid = songurl.split('user/')[1].split('/')[0];
		}
		layer.msg('ID获取成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的链接！');return false;
	}
	$('#inputvalue').val(songid);
	}
}
function gettoutiaoid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('.toutiao.com')<0){layer.alert('请输入正确的链接！');return false;}
	try{
		if(songurl.indexOf('user/')>0){
			var songid = songurl.split('user/')[1].split('/')[0];
		}else{
			var songid = songurl.split('profile/')[1].split('/')[0];
		}
		layer.msg('ID获取成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的链接！');return false;
	}
	$('#inputvalue').val(songid);
}
function getweishiid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('.qq.com')<0){layer.alert('请输入正确的链接！');return false;}
	try{
		if(songurl.indexOf('feed/')>0){
			var songid = songurl.split('feed/')[1].split('/')[0];
		}else if(songurl.indexOf('personal/')>0){
			var songid = songurl.split('personal/')[1].split('/')[0];
		}else{
			var songid = songurl.split('id=')[1].split('&')[0];
		}
		layer.msg('ID获取成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的链接！');return false;
	}
	$('#inputvalue').val(songid);
}
function getxiaohongshuid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('/t.cn/')>0||songurl.indexOf('/xhsurl.com/')>0){
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=getxiaohongshu",
			data : {url:songurl},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					$('#inputvalue').val(data.songid);
					layer.msg('ID获取成功！下单即可');
				}else{
					layer.alert(data.msg);return false;
				}
			}
		});
	}else{
	if(songurl.indexOf('xiaohongshu.com')<0 && songurl.indexOf('pipix.com')<0){layer.alert('请输入正确的链接！');return false;}
	try{
		var songid = songurl.split('item/')[1].split('?')[0];
		layer.msg('ID获取成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的链接！');return false;
	}
	}
	$('#inputvalue').val(songid);
}
function getbilibiliid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('bilibili.com')<0){layer.alert('请输入正确的视频链接！');return false;}
	try{
		var songid = songurl.split('video/av')[1].split('/')[0];
		layer.msg('ID获取成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的视频链接！');return false;
	}
	$('#inputvalue').val(songid);
}
function getzuiyouid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('izuiyou.com')<0){layer.alert('请输入正确的帖子链接！');return false;}
	try{
		var songid = songurl.split('detail/')[1].split('?')[0];
		layer.msg('ID获取成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的帖子链接！');return false;
	}
	$('#inputvalue').val(songid);
}
function getmeipaiid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('meipai.com')<0){layer.alert('请输入正确的视频链接！');return false;}
	if(songurl.indexOf('/s/')>0){
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=gethuoshan",
			data : {url:songurl},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					$('#inputvalue').val(data.songid);
					layer.msg('ID获取成功！下单即可');
				}else{
					layer.alert(data.msg);return false;
				}
			}
		});
	}else{
	try{
		var songid = songurl.split('media/')[1].split('?')[0];
		layer.msg('ID获取成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的视频链接！');return false;
	}
	}
	$('#inputvalue').val(songid);
}
function getquanminid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('hao222.com')<0){layer.alert('请输入正确的视频链接！');return false;}
	try{
		var songid = songurl.split('vid=')[1].split('&')[0];
		layer.msg('ID获取成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的视频链接！');return false;
	}
	$('#inputvalue').val(songid);
}
function getmeituid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('meitu.com')<0){layer.alert('请输入正确的视频链接！');return false;}
	try{
		var songid = songurl.split('feed_id=')[1].split('&')[0];
		layer.msg('ID获取成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的视频链接！');return false;
	}
	$('#inputvalue').val(songid);
}
function getoasisid(){
	var songurl=$("#inputvalue").val();
	if(songurl==''){layer.alert('请确保每项不能为空！');return false;}
	if(songurl.indexOf('weibo.cn')<0&&songurl.indexOf('weibo.com')<0){layer.alert('请输入正确的视频链接！');return false;}
	try{
		var songid = songurl.split('sid=')[1].split('&')[0];
		layer.msg('ID获取成功！下单即可');
	}catch(e){
		layer.alert('请输入正确的视频链接！');return false;
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
function checkInput() {
	if($('#inputname').attr("gettype")=='shareid'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			getshareid();
		}
	}
	else if($('#inputname').attr("gettype")=='huoshan'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			gethuoshanid();
		}
	}
	else if($('#inputname').attr("gettype")=='weishi'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			getweishiid();
		}
	}
	else if($('#inputname').attr("gettype")=='toutiao'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			gettoutiaoid();
		}
	}
	else if($('#inputname').attr("gettype")=='xiaohongshu'||$('#inputname').attr("gettype")=='pipixia'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			getxiaohongshuid();
		}
	}
	else if($('#inputname').attr("gettype")=='meipai'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			getmeipaiid();
		}
	}
	else if($('#inputname').attr("gettype")=='bilibili'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			getbilibiliid();
		}
	}
	else if($('#inputname').attr("gettype")=='zuiyou'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			getzuiyouid();
		}
	}
	else if($('#inputname').attr("gettype")=='quanmin'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			getquanminid();
		}
	}
	else if($('#inputname').attr("gettype")=='meitu'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			getmeituid();
		}
	}
	else if($('#inputname').attr("gettype")=='oasis'){
		if($("#inputvalue").val()!='' && $("#inputvalue").val().indexOf('http')>=0){
			getoasisid();
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
function inviteshow(id) {
    var ii = layer.load(1, {shade: [0.1, '#fff']});
    $.ajax({
        type: 'POST',
        url: 'ajax.php?act=invite_content',
        data: {id: id},
        dataType: 'json',
        success: function (data) {
            layer.close(ii);
            if (data.code === 0) {
				layer.open({
				  type: 1,
				  shadeClose: true,
				  shade: false,
				  title: '复制以下内容邀请好友吧',
				  skin: 'layui-layer-lan',
				  content: '<div class="list-group-item well well-sm">' + data.content + '<center><button class="btn btn-warning btn-sm" data-clipboard-text="'+data.url+'" id="copyurl">一键复制链接</button>&nbsp;<button class="btn btn-success btn-sm" data-clipboard-text="'+data.content+'" id="copycontent">一键复制广告语</button></center></div>'
				});
            } else {
                layer.alert(data.msg);
            }
        },
        error: function () {
            layer.close(ii);
            layer.alert('请重试一遍即可', {icon: 6});
        }
    });
}
$(document).ready(function(){
	var clipboard = new Clipboard('#copyurl');
	clipboard.on('success', function (e) {
		layer.msg('复制成功！');
	});
	clipboard.on('error', function (e) {
		layer.msg('复制失败，请长按链接后手动复制');
	});
	var clipboard = new Clipboard('#copycontent');
	clipboard.on('success', function (e) {
		layer.msg('复制成功！');
	});
	clipboard.on('error', function (e) {
		layer.msg('复制失败，请长按选中后手动复制');
	});
	if($.cookie('query_qq')){
		$("#query_qq").val($.cookie('query_qq'));
		$("#qq").val($.cookie('query_qq'));
	}
	$("input[name=ctool]").each(function () {
        $(this).click(function () {
			var that = $(this);
			var tid = that.attr('data-tid');
			$("#tidframe").attr('nid', that.val());
			var ii = layer.load(2, {shade:[0.1,'#fff']});
			$.ajax({
				type : "GET",
				url : "ajax.php?act=gettool&tid="+tid,
				dataType : 'json',
				success : function(data) {
					layer.close(ii);
					if(data.code == 0){
						var res = data.data[0];
						$("#tidframe").html('<select name="tid" id="tid"><option value="'+res.tid+'" cid="'+res.cid+'" price="'+res.price+'" desc="'+escape(res.desc)+'" alert="'+escape(res.alert)+'" inputname="'+res.input+'" inputsname="'+res.inputs+'" multi="'+res.multi+'" isfaka="'+res.isfaka+'" count="'+res.value+'" close="'+res.close+'" prices="'+res.prices+'" max="'+res.max+'" min="'+res.min+'">'+res.name+'</option></select>');
						getPoint();
						$('#alert_invite').show();
						var value = that.attr('data-value');
						if(that.attr('data-type') == '1'){
							$('#alert_invite').html('<strong>当前商品推广规则：</strong><br/>创建推广订单后即可开始发送给好友，<b>'+parseInt(value)+'</b>人访问你的推广链接，你就可以获得<font color="red">'+res.name+'</font>！在【进度查询】页面可以看任务是否完成');
						}else{
							$('#alert_invite').html('<strong>当前商品推广规则：</strong><br/>创建推广订单后即可开始发送给好友，别人访问你的推广链接并购买任意商品，你就可以获得<font color="red">'+res.name+'</font>！');
						}
					}else{
						layer.alert(data.msg);
					}
				},
				error:function(data){
					layer.msg('加载失败，请刷新重试');
					return false;
				}
			});
        })
    })
	$("#submit_buy").click(function(){
		var tid=$("#tid").val();
		if(tid==0 || $("#tid").length==0){layer.alert('请先选择推广奖励商品！');return false;}
		var inputvalue=$("#inputvalue").val();
		var query_qq=$("#query_qq").val();
		if(inputvalue=='' || tid=='' || query_qq==''){layer.alert('请确保每项不能为空！');return false;}
		if($("#inputvalue2").val()=='' || $("#inputvalue3").val()=='' || $("#inputvalue4").val()=='' || $("#inputvalue5").val()==''){layer.alert('请确保每项不能为空！');return false;}
		if(query_qq.length<5 || query_qq.length>11 || isNaN(query_qq)){layer.alert('请输入正确的查单QQ！');return false;}
		if(($('#inputname').html()=='下单ＱＱ' || $('#inputname').html()=='ＱＱ账号' || $("#inputname").html() == 'QQ账号') && (inputvalue.length<5 || inputvalue.length>11 || isNaN(inputvalue))){layer.alert('请输入正确的QQ号！');return false;}
		var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
		if($('#inputname').html()=='你的邮箱' && !reg.test(inputvalue)){layer.alert('邮箱格式不正确！');return false;}
		reg=/^[1][0-9]{10}$/;
		if($('#inputname').html()=='手机号码' && !reg.test(inputvalue)){layer.alert('手机号码格式不正确！');return false;}
		if($("#inputname2").html() == '说说ID'||$("#inputname2").html() == '说说ＩＤ'){
			if($("#inputvalue2").val().length != 24){layer.alert('说说必须是原创说说！');return false;}
		}
		checkInput();
		if($("#inputname").html() == '抖音作品ID'||$("#inputname").html() == '火山作品ID'||$("#inputname").html() == '火山直播ID'){
			if($("#inputvalue").val().length != 19){layer.alert('您输入的作品ID有误！');return false;}
		}
		if($("#inputname2").html() == '抖音评论ID'){
			if($("#inputvalue2").val().length != 19){layer.alert('您输入的评论ID有误！请点击自动获取手动选择评论！');return false;}
		}
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=invite_create",
			data : {nid:$("#tidframe").attr('nid'),tid:tid,inputvalue:$("#inputvalue").val(),inputvalue2:$("#inputvalue2").val(),inputvalue3:$("#inputvalue3").val(),inputvalue4:$("#inputvalue4").val(),inputvalue5:$("#inputvalue5").val(),query_qq:query_qq,hashsalt:hashsalt},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				$.cookie('query_qq', query_qq);
				if (data.code === 0) {
					var value = data.content;
					$('#resulturl').html('<div class="list-group-item list-group-item-warning"><i class="fa fa-check-circle-o"></i>&nbsp;生成链接成功，复制以下内容邀请好友吧！</div><div class="list-group-item well well-sm">' + data.content + '<center><button class="btn btn-warning btn-sm" data-clipboard-text="'+data.url+'" id="copyurl">一键复制链接</button>&nbsp;<button class="btn btn-success btn-sm" data-clipboard-text="'+data.content+'" id="copycontent">一键复制广告语</button></center></div>');
				} else {
					layer.open({
						title: '错误提示'
						, icon: 2
						, content: data.msg
					});
				}
				$('#resulturl').slideDown();
			},
			error: function () {
				layer.close(ii);
				layer.alert('请重试一遍即可', {icon: 6});
			}
		});
	});

	$('#submit_sublog').click(function () {
		var query_qq = $('#qq').val();
		if (query_qq == '') {
			layer.alert('请填写您的查单QQ号！');
			return false;
		}
		$('#result').hide();
		$("#list").empty();
		var ii = layer.load(1, {shade: [0.1, '#fff']});
		$.ajax({
			type: 'POST',
			url: 'ajax.php?act=invite_query',
			data: {query_qq: query_qq},
			dataType: 'json',
			success: function (data) {
				layer.close(ii);
				if (data.code == 0) {
					$.each(data.data, function(i, item) {
						if(item.status==1){
							var type = '<font color="green">已完成</font>';
						}else var type = '<font color="#ff6347">进行中</font>'+(item.plan>0?'['+item.click+'/'+item.plan+']':'');
						$("#list").append('<tr><td>'+item.input+'</td><td>'+item.name+'</td><td><b>'+item.count+'</b></td><td>'+type+'</td><td><button class="btn btn-xs btn-success pull-right" onclick="inviteshow('+item.id+')">查看链接</button></td></tr>');
					});
					$("#result").slideDown();
				} else {
					layer.open({
						title: '错误提示'
						, icon: 2
						, content: data.msg
					});
				}
			},
			error: function () {
				layer.close(ii);
				layer.alert('请重试一遍即可', {icon: 6});
			}
		})
	})

});

