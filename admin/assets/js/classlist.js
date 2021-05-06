function listTable(query){
	var url = window.document.location.href.toString();
	var queryString = url.split("?")[1];
	query = query || queryString;
	layer.closeAll();
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'classlist-table.php?'+query,
		dataType : 'html',
		cache : false,
		success : function(data) {
			layer.close(ii);
			$("#listTable").html(data);
			$("#classlisttbody").dragsort({
				dragSelector: ".sort_drag", 
				dragEnd: saveOrder, 
				placeHolderTemplate: "<tr></tr>",
			});
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function saveOrder() {
	var i=1;
	$("#classlisttbody tr").each(function(){
		var cid = $(this).attr('data-cid');
		$("input[name='sort["+cid+"]']").val(i++);
	})
};
function setActive(cid,active) {
	$.ajax({
		type : 'GET',
		url : 'ajax_class.php?act=setClass&cid='+cid+'&active='+active,
		dataType : 'json',
		success : function(data) {
			listTable();
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function sort(cid,sort) {
	$.ajax({
		type : 'GET',
		url : 'ajax_class.php?act=setClassSort&cid='+cid+'&sort='+sort,
		dataType : 'json',
		success : function(data) {
			listTable();
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function getImage(cid) {
	layer.confirm('是否从该分类下的商品图片获取一张作为分类图片？', {
		btn: ['确定'] //按钮
	}, function(){
	$.ajax({
		type : 'GET',
		url : 'ajax_class.php?act=getClassImage&cid='+cid,
		dataType : 'json',
		success : function(data) {
			if(data.code == 0){
				layer.msg('获取图片成功');
				$("input[name='img"+cid+"']").val(data.url);
			}else{
				layer.alert('该分类下商品都没有图片');
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
	});
}
function addClass() {
	var name = $("input[name='addname']").val();
	$.ajax({
		type : 'POST',
		url : 'ajax_class.php?act=addClass',
		data : {name:name},
		dataType : 'json',
		success : function(data) {
			if(data.code == 0){
				layer.msg('添加成功');
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
function editClass(cid) {
	var name = $("input[name='name["+cid+"]']").val();
	$.ajax({
		type : 'POST',
		url : 'ajax_class.php?act=editClass&cid='+cid,
		data : {name:name},
		dataType : 'json',
		success : function(data) {
			if(data.code == 0){
				layer.msg('修改成功');
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
function delClass(cid) {
	var confirmobj = layer.confirm('你确实要删除此分类和分类下全部商品吗？', {
	  btn: ['确定','取消']
	}, function(){
	  $.ajax({
		type : 'GET',
		url : 'ajax_class.php?act=delClass&cid='+cid,
		dataType : 'json',
		success : function(data) {
			if(data.code == 0){
				layer.msg('删除成功');
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
	}, function(){
	  layer.close(confirmobj);
	});
}
function saveAll() {
	$.ajax({
		type : 'POST',
		url : 'ajax_class.php?act=editClassAll',
		data : $('#classlist').serialize(),
		dataType : 'json',
		success : function(data) {
			alert('保存成功！');
			listTable();
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function saveAllImages() {
	$.ajax({
		type : 'POST',
		url : 'ajax_class.php?act=editClassImages',
		data : $('#classlist').serialize(),
		dataType : 'json',
		success : function(data) {
			alert('保存成功！');
			window.location.reload();
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function fileSelect(cid){
	$("#file"+cid).trigger("click");
}
function fileView(cid){
	var shopimg = $("input[name='img["+cid+"]']").val();
	if(shopimg=='') {
		layer.alert("请先上传图片，才能预览");
		return;
	}
	if(shopimg.indexOf('http') == -1)shopimg = '../'+shopimg;
	layer.open({
		type: 1,
		area: ['360px', '400px'],
		title: '分类图片查看',
		shade: 0.3,
		anim: 1,
		shadeClose: true,
		content: '<center><img width="300px" src="'+shopimg+'"></center>'
	});
}
function fileUpload(cid){
	var fileObj = $("#file"+cid)[0].files[0];
	if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
		return;
	}
	var formData = new FormData();
	formData.append("do","upload");
	formData.append("type","class");
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
				$("input[name='img["+cid+"]']").val(data.url);
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
function setClass(cid) {
	$.ajax({
		type : 'POST',
		url : 'ajax_class.php?act=getBlock',
		data : {cid:cid},
		dataType : 'json',
		success : function(data) {
			if(data.code == 0){
				layer.open({
					area: ['360px'],
					title: '不可售地区设置（多个城市用,分隔）',
					content: '<div class="form-group"><textarea class="form-control" name="blockcontent" placeholder="示例：北京市,广东省深圳市" rows="3">'+data.data+'</textarea></div>',
					yes: function(){
						var content = $("textarea[name='blockcontent']").val();
						$.ajax({
							type : 'POST',
							url : 'ajax_class.php?act=setBlock',
							data : {cid:cid,data: content.replace("，",",")},
							dataType : 'json',
							success : function(data) {
								if(data.code == 0){
									layer.msg(data.msg, {icon:1});
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
function setBlockPay(cid) {
	$.ajax({
		type : 'POST',
		url : 'ajax_class.php?act=getBlockPay',
		data : {cid:cid},
		dataType : 'json',
		success : function(data) {
			if(data.code == 0){
				layer.open({
					area: ['360px'],
					title: '设置此分类商品禁用支付方式',
					content: '<div class="form-group"><div class="checkbox"><label><input type="checkbox" name="paytype" value="alipay" '+($.inArray('alipay',data.data)>-1?'checked':null)+'> 禁用支付宝</label></div><div class="checkbox"><label><input type="checkbox" name="paytype" value="qqpay" '+($.inArray('qqpay',data.data)>-1?'checked':null)+'> 禁用QQ钱包</label></div><div class="checkbox"><label><input type="checkbox" name="paytype" value="wxpay" '+($.inArray('wxpay',data.data)>-1?'checked':null)+'> 禁用微信支付</label></div><div class="checkbox"><label><input type="checkbox" name="paytype" value="rmb" '+($.inArray('rmb',data.data)>-1?'checked':null)+'> 禁用余额</label></div></div>',
					yes: function(){
						var paytype = [];
						$.each($("input[name='paytype']:checked"),function(){
							paytype.push($(this).val());
						});
						var content = $("textarea[name='blockcontent']").val();
						$.ajax({
							type : 'POST',
							url : 'ajax_class.php?act=setBlockPay',
							data : {cid:cid,paytype: paytype},
							dataType : 'json',
							success : function(data) {
								if(data.code == 0){
									layer.msg(data.msg, {icon:1});
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
$(document).ready(function(){
	if($("#listTable").length>0){
		listTable()
	}
})