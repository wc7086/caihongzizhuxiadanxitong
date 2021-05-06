$(document).ready(function(){
	$("#cid").change(function () {
		var cid = $(this).val();
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$("#tid").empty();
		$("#tid").append('<option value="0">请选择商品</option>');
		$.ajax({
			type : "GET",
			url : "./ajax.php?act=gettool&cid="+cid,
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					var num = 0;
					$.each(data.data, function (i, res) {
						$("#tid").append('<option value="'+res.tid+'">'+res.name+'</option>');
						num++;
					});
					$("#tid").val(0);
					if(num==0 && cid!=0)$("#tid").html('<option value="0">该分类下没有商品</option>');
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
	$("#type").change(function () {
		var type = $(this).val();
		if(type == 1){
			$("#value").attr('placeholder', '输入累计访问次数（相同IP算1次）');
		}else{
			$("#value").attr('placeholder', '输入被推荐人下单金额超过多少元');
		}
	});
	if($("#cid").length>0){
		$("#cid").change();
	}
	$("#type").change();
	var items = $("select[default]");
	for (i = 0; i < items.length; i++) {
		$(items[i]).val($(items[i]).attr("default")||0);
	}
});