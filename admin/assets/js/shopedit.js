var shoplist;
function checkinput(){
	if($("input[name='name']").val()==''){
		layer.alert("商品名称不能为空");
		return false;
	}
	if($("select[name='prid']").val()=='0' && $("input[name='price']").val()=='' || $("select[name='prid']").val()!='0' && $("input[name='price1']").val()==''){
		layer.alert("商品价格不能为空");
		return false;
	}
	return true;
}
function setDesc(str){
	$("textarea[name='desc']").val(str);
	window.editor !== undefined && window.editor.html(str);
}
function changeinput(str){
	$("input[name='input']").val(str);
}
function changeinputs(str){
	$("input[name='inputs']").val(str);
}
function getFloat(number, n) {
	n = n ? parseInt(n) : 0;
	if (n <= 0) return Math.ceil(number);
	number = Math.round(number * Math.pow(10, n)) / Math.pow(10, n);
	return number;
}
function changeNum(){
	var num = parseInt($("#value").val());
	var price = parseFloat($("#price").val());
	var min = parseInt($("#value").attr('min'));
	var max = parseInt($("#value").attr('max'));
	if(num == 0 || isNaN(price))return false;
	$("input[name='price1']").val(getFloat(num * price, 2));
	$("input[name='price']").val(getFloat(num * price, 2));
	if(min == max || num >= max){
		$("select[name='multi']").val(0);
		$("input[name='min']").val('');
		$("input[name='max']").val('');
	}
	else{
		$("select[name='multi']").val(1);
		$("input[name='min']").val('');
		$("input[name='max']").val(Math.floor(max/num));
	}
	$("select[name='multi']").change();
}
function fileSelect(){
	$("#file").trigger("click");
}
function fileView(){
	var shopimg = $("#shopimg").val();
	if(shopimg=='') {
		layer.alert("请先上传图片，才能预览");
		return;
	}
	if(shopimg.indexOf('http') == -1)shopimg = '../'+shopimg;
	layer.open({
		type: 1,
		area: ['360px', '400px'],
		title: '商品图片查看',
		shade: 0.3,
		anim: 1,
		shadeClose: true,
		content: '<center><img width="300px" src="'+shopimg+'"></center>'
	});
}
function fileUpload(){
	var fileObj = $("#file")[0].files[0];
	if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
		return;
	}
	var formData = new FormData();
	formData.append("do","upload");
	formData.append("type","shop");
	formData.append("file",fileObj);
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		url: "ajax.php?act=uploadimg",
		data: formData,
		type: "POST",
		dataType: "json",
		cache: false,
		processData: false,
		contentType: false,
		success: function (data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('上传图片成功');
				$("#shopimg").val(data.url);
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	})
}
function Addstr(id, str) {
	$("#"+id).val($("#"+id).val()+str);
}
$(document).ready(function(){
	$("select[name='prid']").change(function(){
		if($(this).val() == 0){
			$("#prid0").show();
			$("#prid1").hide();
		}else{
			$("#prid1").show();
			$("#prid0").hide();
		}
	});
	$("select[name='multi']").change(function(){
		if($(this).val() == 1){
			$("#multi0").show();
		}else{
			$("#multi0").hide();
		}
	});
	$("select[name='validate']").change(function(){
		if($(this).val() >= 2){
			$("#valiserv").show();
		}else{
			$("#valiserv").hide();
		}
	});
	$("select[name='is_curl']").change(function(){
		if($(this).val() == 1){
			$("#curl_display1").show();
			$("#curl_display2").hide();
			$("#curl_display3").hide();
		}else if($(this).val() == 2){
			$("#curl_display1").hide();
			$("#curl_display2").show();
			$("#curl_display3").hide();
		}else if($(this).val() == 5){
			$("#curl_display1").hide();
			$("#curl_display2").hide();
			$("#curl_display3").show();
		}else{
			$("#curl_display1").hide();
			$("#curl_display2").hide();
			$("#curl_display3").hide();
		}
		if (!$('select[name="shequ"]').is(':hidden'))
            $('#goodslist').select2({
                placeholder: '请选择对接商品',
                language: 'zh-CN'
            });
	});
	$("select[name='shequ']").change(function(){
		var type = $("select[name='shequ'] option:selected").attr("type");
		if(type == 'jiuwu'){
			$("#goods_type").show();
			$("#goods_param").show();
		}else if(type == 'kayixin' || type == 'kalegou' || type == 'zhike'){
			$("#goods_type").hide();
			$("#goods_param").show();
		}else{
			$("#goods_type").hide();
			$("#goods_param").hide();
		}
		if(type == 'kayixin' || type == 'kalegou'){
			$("#show_value").hide();
			$("#value").val('1');
			$("#goods_id").hide();
			$("#show_goodslist").hide();
			$("#goods_param_name").html("下单页面地址：");
			if($("input[name='goods_param']").val().indexOf('http')<0)$("input[name='goods_param']").val("");
		}else if(type == 'zhike'){
			$("#show_value").show();
			$("#goods_id").hide();
			$("#show_goodslist").show();
		}else if(type == 'shangmeng' || type == 'yiqida'){
			$("#show_value").show();
			$("#goods_id").show();
			$("#show_goodslist").hide();
		}else if(type == 'kayisu' || type == 'kashangwl' || type == 'xingouka'){
			$("#show_value").hide();
			$("#value").val('1');
			$("#goods_id").show();
			$("#show_goodslist").hide();
		}else if(type == 'liuliangka' || type == 'shangzhanwl' || type == 'yunbao'){
			$("#show_value").hide();
			$("#value").val('1');
			$("#goods_id").show();
			$("#show_goodslist").show();
		}else if(type == 'extend'){

		}else{
			$("#show_value").show();
			$("#goods_id").show();
			$("#show_goodslist").show();
			$("#goods_param_name").html("参数名：");
		}
		if (type == 'kayixin') {
            $('#kyxCommunity').show();
        }else{
			$('#kyxCommunity').hide();
		}
		if (type == 'kayixin' || type == 'kayisu' || type == 'shangzhanwl' || type == 'daishua' || type == 'kakayun' || type == 'yunbao' || type == 'xingouka') {
			$('#goods_type_select_form').show();
		}else{
			$('#goods_type_select_form').hide();
		}
		$("#GoodsInfo").hide();
		if (!$('select[name="shequ"]').is(':hidden'))
            $('#goodslist').select2({
                placeholder: '请选择对接商品',
                language: 'zh-CN'
            });
	});
	$("#goods_type_select").change(function(){
		$("input[name='goods_type']").val($("#goods_type_select option:selected").val());
	});
	$("#getGoods").click(function(){
		var shequ=$("select[name='shequ']").val();
		if(shequ==''){layer.alert('请先选择一个对接网站');return false;}
		$('#goodslist').empty();
		shoplist = new Array();
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax_shop.php?act=getGoodsList",
			data : {shequ:shequ},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					$('#getGoods').attr('type',data.type);
					$('#goodslist').append('<option value="">--请选择商品--</option>');
					$.each(data.data, function(i, item){
						shoplist[item.id] = item;
						$('#goodslist').append('<option value="'+item.id+'">'+item.name+'</option>');
					});
					if(typeof($("#goodslist").attr('default'))!='undefined'){
						$('#goodslist').val($("#goodslist").attr('default'));
						if($('#goodslist').val()!=null)$("#goodslist").change();
					}
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
	$("#goodslist").change(function () {
		var type = $('#getGoods').attr('type');
		if(type == 'jiuwu'){
			var shequ=$("select[name='shequ']").val();
			var goodsid = $("#goodslist option:selected").val();
			var goodstype = shoplist[goodsid].type;
			var minnum = shoplist[goodsid].minnum;
			var maxnum = shoplist[goodsid].maxnum;
			var shopimg = shoplist[goodsid].shopimg;
			var price = shoplist[goodsid].price;
			var name = shoplist[goodsid].name;
			$("input[name='goods_id']").val(goodsid);
			$("input[name='goods_type']").val(goodstype);
			$("input[name='shopimg']").val(shopimg);
			$("#price").val(price);
			if($("#value").val()=='' || $("#value").val()<minnum || $("#value").val()>maxnum)$("#value").val(minnum);
			$("#value").attr('min',+minnum);
			$("#value").attr('max',+maxnum);
			if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(name);
			var ii = layer.load(2, {shade:[0.1,'#fff']});
			$.ajax({
				type : "POST",
				url : "ajax_shop.php?act=getGoodsParam",
				data : {shequ:shequ,goodsid:goodsid},
				dataType : 'json',
				success : function(data) {
					layer.close(ii);
					if(data.code == 0){
						$("input[name='goods_param']").val(data.param);
						$("#GoodsInfo").html('<b>商品名称：</b><a style="color:white" href="http://'+$("select[name='shequ'] option:selected").attr('domain')+'/index.php?m=Home&c=Goods&a=detail&id='+goodsid+'&goods_type='+goodstype+'" target="_blank" rel="noreferrer">'+name+'</a><br/><b>社区商品售价：</b>'+data.price+'<br/><b>最小下单数量：</b>'+minnum+'<br/><b>最大下单数量：</b>'+maxnum);
						$("#GoodsInfo").slideDown();
						changeNum();
					}else{
						layer.alert(data.msg);
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		}else if(type == 'yile'){
			var shequ=$("select[name='shequ']").val();
			var goodsid = $("#goodslist option:selected").val();
			$("input[name='goods_id']").val(goodsid);
			var ii = layer.load(2, {shade:[0.1,'#fff']});
			$.ajax({
				type : "POST",
				url : "ajax_shop.php?act=getGoodsParam",
				data : {shequ:shequ,goodsid:goodsid},
				dataType : 'json',
				success : function(data) {
					layer.close(ii);
					if(data.code == 0){
						$("input[name='shopimg']").val(data.image);
						var paramname = data.paramname;
						var inputs ='';
						$.each(paramname.split('|'), function(i, v) {
							if(i==0){
								$("input[name='input']").val(v);
							}else{
								if(v=='QQ空间说说ID')v='说说ID';
								inputs += '|'+v;
							}
						});
						$("input[name='inputs']").val(inputs.substr(1));
						$("#price").val(data.price);
						if($("#value").val()=='' || $("#value").val()<data.limit_min || $("#value").val()>data.limit_max)$("#value").val(data.limit_min);
						$("#value").attr('min',data.limit_min);
						$("#value").attr('max',data.limit_max);
						if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(data.name);
						if($("textarea[name='desc']").val()==''||isAdd)setDesc(data.desc);
						

						$("#GoodsInfo").html('<b>商品名称：</b><a style="color:white" href="http://'+$("select[name='shequ'] option:selected").attr('domain')+'/home/order/'+goodsid+'" target="_blank" rel="noreferrer">'+data.name+'</a><br/><b>商品简介：</b>'+data.desc+'<br/><b>社区商品售价：</b>'+data.price+' 元<br/><b>最小下单数量：</b>'+data.limit_min+'<br/><b>最大下单数量：</b>'+data.limit_max);
						$("#GoodsInfo").slideDown();
						changeNum();
					}else{
						layer.alert(data.msg);
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		}else if(type == 'daishua'){
			var shequ=$("select[name='shequ']").val();
			var goodsid = $("#goodslist option:selected").val();
			$("input[name='goods_id']").val(goodsid);
			var ii = layer.load(2, {shade:[0.1,'#fff']});
			$.ajax({
				type : "POST",
				url : "ajax_shop.php?act=getGoodsParam",
				data : {shequ:shequ,goodsid:goodsid},
				dataType : 'json',
				success : function(data) {
					layer.close(ii);
					if(data.code == 0){
						$("input[name='shopimg']").val(data.shopimg);
						$("#price").val(data.price);
						$("#value").val(1);
						$("input[name='input']").val(data.input);
						$("input[name='inputs']").val(data.inputs);
						$("#value").attr('min',data.min);
						if(data.max == 0){
							$("#value").attr('max','1');
						}else{
							$("#value").attr('max',data.max);
						}
						if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(data.name);
						if($("textarea[name='desc']").val()==''||isAdd)setDesc(data.desc);
						if($("textarea[name='alert']").val()==''||isAdd)$("input[name='alert']").val(data.alert);
						$('#goods_type_select').val(data.isfaka);

						$("#GoodsInfo").html('<b>商品名称：</b><a style="color:white" href="http://'+$("select[name='shequ'] option:selected").attr('domain')+'/?cid='+data.cid+'&tid='+goodsid+'" target="_blank" rel="noreferrer">'+data.name+'</a><br/><b>商品简介：</b>'+data.desc+'<br/><b>商品售价：</b>'+data.price+'元<br/><b>最小下单数量：</b>'+data.min+'<br/><b>最大下单数量：</b>'+data.max);
						$("#GoodsInfo").slideDown();
						changeNum();
					}else{
						layer.alert(data.msg);
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		}else if(type == 'zhike'){
			var shequ=$("select[name='shequ']").val();
			var goodsid = $("#goodslist option:selected").val();
			var ii = layer.load(2, {shade:[0.1,'#fff']});
			$.ajax({
				type : "POST",
				url : "ajax_shop.php?act=getGoodsParam",
				data : {shequ:shequ,goods_param:goodsid},
				dataType : 'json',
				success : function(data) {
					layer.close(ii);
					if(data.code == 0){
						$("input[name='goods_param']").val(goodsid+'#'+data.alias);
						$("input[name='shopimg']").val(data.image);
						$("#price").val(data.price);
						$("input[name='input']").val(data.input);
						$("input[name='inputs']").val(data.inputs);
						if($("#value").val()=='' || $("#value").val()<data.min || $("#value").val()>data.max)$("#value").val(data.min);
						if(data.max == 0){
							$("#value").attr('max','1');
						}else{
							$("#value").attr('max',data.max);
						}
						$("#value").attr('min',data.min);
						if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(data.name);
						if($("textarea[name='desc']").val()==''||isAdd)setDesc(data.desc);

						$("#GoodsInfo").html('<b>商品名称：</b><a style="color:white" href="http://'+$("select[name='shequ'] option:selected").attr('domain')+'/shop/goods/detail/?sn='+goodsid+'" target="_blank" rel="noreferrer">'+data.name+'</a><br/><b>商品简介：</b>'+data.desc+'<br/><b>商品售价：</b>'+data.price+'元<br/><b>最小下单数量：</b>'+data.min+'<br/><b>最大下单数量：</b>'+data.max);
						$("#GoodsInfo").slideDown();
						changeNum();
					}else{
						layer.alert(data.msg);
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		}else if(type == 'shangzhanwl'){
			var shequ=$("select[name='shequ']").val();
			var goodsid = $("#goodslist option:selected").val();
			$("input[name='goods_id']").val(goodsid);
			var ii = layer.load(2, {shade:[0.1,'#fff']});
			$.ajax({
				type : "POST",
				url : "ajax_shop.php?act=getGoodsParam",
				data : {shequ:shequ,goodsid:goodsid},
				dataType : 'json',
				success : function(data) {
					layer.close(ii);
					if(data.code == 0){
						$("input[name='input']").val(data.input);
						$("input[name='inputs']").val(data.inputs);
						$("input[name='price1']").val(getFloat(data.price, 2));
						$("input[name='price']").val(getFloat(data.price, 2));
						if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(data.name);
						$('#goods_type_select').val(data.type==1?1:0);
						$("input[name='goods_type']").val(data.type==1?1:0);
						if($("textarea[name='desc']").val()==''||isAdd)setDesc(data.info);

						$("#GoodsInfo").html('<b>商品名称：</b><a style="color:white" href="http://'+$("select[name='shequ'] option:selected").attr('domain')+'/product/'+goodsid+'.html" target="_blank" rel="noreferrer">'+data.name+'</a><br/><b>商品售价：</b>'+data.price+' 元<br/><b>最大下单数量：</b>'+data.quantity);
						$("#GoodsInfo").slideDown();
					}else{
						layer.alert(data.msg);
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		}else if(type == 'kakayun'){
			var shequ=$("select[name='shequ']").val();
			var goodsid = $("#goodslist option:selected").val();
			$("input[name='goods_id']").val(goodsid);
			var ii = layer.load(2, {shade:[0.1,'#fff']});
			$.ajax({
				type : "POST",
				url : "ajax_shop.php?act=getGoodsParam",
				data : {shequ:shequ,goodsid:goodsid},
				dataType : 'json',
				success : function(data) {
					layer.close(ii);
					if(data.code == 0){
						$("#goods_type_select").val(data.type==0?1:0);
						$("#goods_type_select").change();
						$("input[name='shopimg']").val(data.shopimg);
						$("#price").val(data.price);
						$("#value").val(1);
						$("#value").attr('min',data.min);
						if(data.max == 0){
							$("#value").attr('max','1');
						}else{
							$("#value").attr('max',data.max);
						}
						if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(data.name);
						if($("textarea[name='desc']").val()==''||isAdd)setDesc(data.desc);
						if($("textarea[name='alert']").val()==''||isAdd)$("input[name='alert']").val(data.alert);

						$("#GoodsInfo").html('<b>商品名称：</b><a style="color:white" href="http://'+$("select[name='shequ'] option:selected").attr('domain')+'/pg/'+goodsid+'.html" target="_blank" rel="noreferrer">'+data.name+'</a><br/><b>商品简介：</b>'+data.desc+'<br/><b>商品提示：</b>'+data.alert+'<br/><b>商品售价：</b>'+data.price+'元<br/><b>最小下单数量：</b>'+data.min+'<br/><b>最大下单数量：</b>'+data.max);
						$("#GoodsInfo").slideDown();
						changeNum();
					}else{
						layer.alert(data.msg);
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		}else if(type == 'yunbao'){
			var shequ=$("select[name='shequ']").val();
			var goodsid = $("#goodslist option:selected").val();
			if(!goodsid)return;
			var name = shoplist[goodsid].name;
			var price = shoplist[goodsid].price;
			var input = shoplist[goodsid].input;
			var type = shoplist[goodsid].type;
			$("input[name='goods_id']").val(goodsid);
			$("input[name='input']").val(input);
			$("input[name='price1']").val(price);
			$("input[name='price']").val(price);
			$('#goods_type_select').val(type=='1'?1:0);
			$("#goods_type_select").change();
			if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(name);

			$("#GoodsInfo").html('<b>商品名称：</b><a style="color:white" href="http://'+$("select[name='shequ'] option:selected").attr('domain')+'/index/p/id/'+goodsid+'" target="_blank" rel="noreferrer">'+name+'</a><br/><b>商品售价：</b>'+price+'元');
			$("#GoodsInfo").slideDown();
		}else if(type == 'liuliangka'){
			var goodsid = $("#goodslist option:selected").val();
			var name = $("#goodslist option:selected").html();
			$("input[name='goods_id']").val(goodsid);
			if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(name);
			$("#GoodsInfo").hide();
			$("#price").val('');
			$("input[name='input']").val('手机号码');
			$("input[name='inputs']").val('收货人姓名|收货人地址');
		}else{
			var goodsid = $("#goodslist option:selected").val();
			var shopimg = shoplist[goodsid].shopimg;
			var name = shoplist[goodsid].name;
			if(typeof (shopimg) != "undefined")$("input[name='shopimg']").val(shopimg);
			$("input[name='goods_id']").val(goodsid);
			if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(name);
			$("#GoodsInfo").hide();
			$("#price").val('');
		}
	});
	$("input[name='goods_id']").blur(function () {
		var type = $("select[name='shequ'] option:selected").attr("type");
		if(type == 'shangmeng'||type == 'yiqida'){
			var shequ=$("select[name='shequ']").val();
			var goodsid = $(this).val();
			if(goodsid=='' || goodsid==0)return;
			var ii = layer.load(2, {shade:[0.1,'#fff']});
			$.ajax({
				type : "POST",
				url : "ajax_shop.php?act=getGoodsParam",
				data : {shequ:shequ,goodsid:goodsid},
				dataType : 'json',
				success : function(data) {
					layer.close(ii);
					if(data.code == 0){
						$("input[name='shopimg']").val(data.img);
						$("input[name='input']").val(data.input);
						$("input[name='inputs']").val(data.inputs);
						$("input[name='price1']").val(data.price);
						$("input[name='price']").val(data.price);
						if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(data.name);

						$("#GoodsInfo").html('<b>商品名称：</b><a style="color:white" href="http://'+$("select[name='shequ'] option:selected").attr('domain')+'/#/goodsDetail?id='+data.mainid+'" target="_blank" rel="noreferrer">'+data.name+'</a><br/><b>商品售价：</b>'+data.price+' 元');
						$("#GoodsInfo").slideDown();
					}else{
						layer.alert(data.msg);
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		}else if(type == 'kashangwl'){
			var shequ=$("select[name='shequ']").val();
			var goodsid = $(this).val();
			if(goodsid=='' || goodsid==0)return;
			var ii = layer.load(2, {shade:[0.1,'#fff']});
			$.ajax({
				type : "POST",
				url : "ajax_shop.php?act=getGoodsParam",
				data : {shequ:shequ,goodsid:goodsid},
				dataType : 'json',
				success : function(data) {
					layer.close(ii);
					if(data.code == 0){
						$("input[name='input']").val(data.input);
						$("input[name='inputs']").val(data.inputs);
						$("input[name='price1']").val(getFloat(data.price, 2));
						$("input[name='price']").val(getFloat(data.price, 2));
						if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(data.name);

						$("#GoodsInfo").html('<b>商品名称：</b><a style="color:white" href="http://'+$("select[name='shequ'] option:selected").attr('domain')+'/buy/'+goodsid+'" target="_blank" rel="noreferrer">'+data.name+'</a><br/><b>商品售价：</b>'+data.price+' 元<br/><b>最小下单数量：</b>'+data.valid_purchasing_quantity.split('-')[0]+'<br/><b>最大下单数量：</b>'+data.valid_purchasing_quantity.split('-')[1]);
						$("#GoodsInfo").slideDown();
					}else{
						layer.alert(data.msg);
					}
				},
				error:function(data){
					layer.msg('服务器错误');
					return false;
				}
			});
		}else if(type == 'xingouka'){
			var shequ=$("select[name='shequ']").val();
			var goodsid = $(this).val();
			if(goodsid=='' || goodsid==0)return;
			$("input[name='goods_id']").val(goodsid);
			var ii = layer.load(2, {shade:[0.1,'#fff']});
			$.ajax({
				type : "POST",
				url : "ajax_shop.php?act=getGoodsParam",
				data : {shequ:shequ,goodsid:goodsid},
				dataType : 'json',
				success : function(data) {
					layer.close(ii);
					if(data.code == 0){
						$("input[name='input']").val(data.input);
						$("input[name='inputs']").val(data.inputs);
						$("input[name='shopimg']").val(data.img);
						$("input[name='price1']").val(getFloat(data.money, 2));
						$("input[name='price']").val(getFloat(data.money, 2));
						if($("input[name='name']").val()==''||isAdd)$("input[name='name']").val(data.name);
						$('#goods_type_select').val(data.type==1?1:0);
						$("input[name='goods_type']").val(data.type==1?1:0);
						$("input[name='alert']").val(data.note);
						if($("textarea[name='desc']").val()==''||isAdd)setDesc(data.desc);

						$("#GoodsInfo").html('<b>商品名称：</b><a style="color:white" href="http://'+$("select[name='shequ'] option:selected").attr('domain')+'/buy/'+goodsid+'.html" target="_blank" rel="noreferrer">'+data.name+'</a><br/><b>商品售价：</b>'+data.money+' 元<br/><b>最小下单数量：</b>'+data.min+'<br/><b>最大下单数量：</b>'+data.max);
						$("#GoodsInfo").slideDown();
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
	});
	$('#kyxGetCategory').click(function () {
        var ii = layer.load(2, {shade:[0.1,'#fff']});
        var shequ = $('select[name="shequ"]').val();
		$.post('./ajax_shop.php?act=getKyxCategory', {
            shequ: shequ
        }, function (data) {
            layer.close(ii);
            if (data.code !== 0) {
                layer.alert(data.msg);
                return true;
            }
            var tempDom = $('#kyxCategory').empty();

            $.each(data.data, function (key, item) {
				tempDom.append('<option value="'+item.id+'">'+item.name+'</option>');
            });

            tempDom.select2({
                placeholder: '请选择分类',
                language: 'zh-CN'
            });
        }, 'json');
    });

    $('#kyxCategory').change(function () {
		var shequ = $('select[name="shequ"]').val();
        var categoryid = $('#kyxCategory option:selected').val();

        var ii = layer.load(2, {shade:[0.1,'#fff']});

        $.post('./ajax_shop.php?act=getKyxProductList', {
            shequ: shequ,
            categoryid: categoryid
        }, function (data) {
            layer.close(ii);
            if (data.code !== 0) {
                layer.alert(data.msg);
                return true;
            }

            var tempDom = $('#kyxProductList').empty();

            if (data['data'] === undefined)
                return true;

            $.each(data.data, function (key, item) {
				tempDom.append('<option value="'+item.id+'" price="'+item.price+'" keyid="'+item.keyid+'" type="'+item.type+'" close="'+item.close+'">'+item.name+'</option>');
            });

            tempDom.change();

            tempDom.select2({
                placeholder: '请选择一级分类',
                language: 'zh-CN'
            });
        }, 'json');
    });
    $('#kyxProductList').change(function () {
        var categorySelectDom = $('#kyxCategory option:selected');
        var productSelectDom = $('#kyxProductList option:selected');

        var buildUrl = 'http://' + $("select[name='shequ'] option:selected").attr('domain') + '/front/inter/buyGoods.htm?goodId=' + productSelectDom.val() + '&keyId=' + productSelectDom.attr('keyid') + '&dirId=' + categorySelectDom.val();

        $('input[name="goods_param"]').val(buildUrl);
        $('input[name="name"]').val(productSelectDom.text());
        $('input[name="price1"]').val(productSelectDom.attr('price'));
		$('input[name="goods_type"]').val(productSelectDom.attr('type'));
		$('#goods_type_select').val(productSelectDom.attr('type'));
		if(productSelectDom.attr('close') == 1){
			var shoptype = '缺货';
		}else if(productSelectDom.attr('type') == 1){
			var shoptype = '发卡';
		}else{
			var shoptype = '代充';
		}

        var html = `<b>商品名称：</b><a style="color:white" href="${buildUrl}" target="_blank" rel="noreferrer">${productSelectDom.text()}</a><br>`;
        html += `<b>社区商品售价：</b>${productSelectDom.attr('price')}元 / 件<br>`;
		html += `<b>商品类型：</b>${shoptype}<br>`;

        $('#GoodsInfo').html(html).show();
    });
	var items = $("select[default]");
	for (i = 0; i < items.length; i++) {
		$(items[i]).val($(items[i]).attr("default")||0);
	}
	$("select[name='shequ']").change();
	$("select[name='prid']").change();
	$("select[name='multi']").change();
	$("select[name='validate']").change();
	$("input[name='goods_id']").blur();

});