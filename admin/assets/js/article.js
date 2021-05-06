var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default")||0);
}
function setActive(id,active) {
	$.ajax({
		type : 'GET',
		url : 'ajax.php?act=setArticle&id='+id+'&active='+active,
		dataType : 'json',
		success : function(data) {
			window.location.reload()
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}