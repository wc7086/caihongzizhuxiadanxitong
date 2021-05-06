function getFloat(number, n) {
	n = n ? parseInt(n) : 0;
	if (n <= 0) return Math.round(number);
	number = Math.round(number * Math.pow(10, n)) / Math.pow(10, n);
	return number;
}
function changeKind(){
	var kind = $("#kind").val();
	if(kind == 1){
		$("#p_0").attr("placeholder", "输入加价价格");
		$("#p_1").attr("placeholder", "输入加价价格");
		$("#p_2").attr("placeholder", "输入加价价格");
	}else{
		$("#p_0").attr("placeholder", "输入加价倍数(大于1的小数)");
		$("#p_1").attr("placeholder", "输入加价倍数(大于1的小数)");
		$("#p_2").attr("placeholder", "输入加价倍数(大于1的小数)");
	}
	changeTest();
}
function changeTest(obj){
	obj = obj|'';
	var kind = $("#kind").val();
	var price = $("#test_price").val();
	if(price=='' || isNaN(price))return false;
	price = parseFloat(price);
	var p_2 = $("#p_2").val();
	var p_1 = $("#p_1").val();
	var p_0 = $("#p_0").val();
	p_2 = parseFloat(p_2);
	$("#test_p_2").html(getFloat(kind==1?price+p_2:price*p_2 ,2));
	p_1 = parseFloat(p_1);
	$("#test_p_1").html(getFloat(kind==1?price+p_1:price*p_1 ,2));
	p_0 = parseFloat(p_0);
	$("#test_p_0").html(getFloat(kind==1?price+p_0:price*p_0 ,2));
}
function addframe(){
	$("#modal-store").modal('show');
	$("#modal-title").html("新增加价模板");
	$("#action").val("add");
	$("#prid").val('');
	$("#name").val('');
	$("#kind").val(0);
	$("#p_2").val('');
	$("#p_1").val('');
	$("#p_0").val('');
	changeKind()
}
function editframe(id){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax_shop.php?act=getPriceRule&id='+id,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				$("#modal-store").modal('show');
				$("#modal-title").html("修改加价模板");
				$("#action").val("edit");
				$("#prid").val(data.id);
				$("#name").val(data.name);
				$("#kind").val(data.kind);
				$("#p_2").val(data.p_2);
				$("#p_1").val(data.p_1);
				$("#p_0").val(data.p_0);
				changeKind()
			}else{
				layer.alert(data.msg)
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function save(){
	var action = $("#action").val();
	if(action == 'add'){
		var queryurl = 'ajax_shop.php?act=addPriceRule';
	}else{
		var queryurl = 'ajax_shop.php?act=editPriceRule';
	}
	if($("#name").val()==''||$("#p_2").val()==''||$("#p_1").val()==''||$("#p_0").val()==''){
		layer.alert('请确保各项不能为空！');return false;
	}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : queryurl,
		data : $("#form-store").serialize(),
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.alert(data.msg,{
					icon: 1,
					closeBtn: false
				}, function(){
				  window.location.reload()
				});
			}else{
				layer.alert(data.msg)
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function delItem(id) {
	var confirmobj = layer.confirm('你确实要删除此模板吗？', {
	  btn: ['确定','取消']
	}, function(){
	  $.ajax({
		type : 'GET',
		url : 'ajax_shop.php?act=delPriceRule&id='+id,
		dataType : 'json',
		success : function(data) {
			if(data.code == 0){
				window.location.reload()
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
function change(id) {
	layer.open({
		area: ['360px'],
		title: '批量更改商品加价模板',
		content: $("#class-select").html(),
		yes: function(){
			var cidList=new Array();
			$('select[name="cids"] option:selected').each(function(){
				cidList.push($(this).val());//向数组中添加元素
			});
			$.ajax({
				type : 'POST',
				url : 'ajax_shop.php?act=changePriceRule',
				data : {id: id, cids: cidList},
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
}