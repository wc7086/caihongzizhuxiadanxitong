function setActive(id,active) {
	$.ajax({
		type : 'GET',
		url : 'ajax.php?act=setMessage&id='+id+'&active='+active,
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
function show(id) {
	$.ajax({
		type : 'GET',
		url : 'ajax.php?act=getMessage&id='+id,
		dataType : 'json',
		success : function(data) {
			if(data.code==0){
				layer.open({
				  type: 1,
				  skin: 'layui-layer-lan',
				  anim: 2,
				  shadeClose: true,
				  title: '查看站内通知',
				  content: '<div class="widget"><div class="widget-content widget-content-mini themed-background-muted text-center"><b>'+data.title+'</b><br/><small><font color="grey">管理员  '+data.date+'</font></small></div><div class="widget-content">'+data.content+'</div></div>'
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