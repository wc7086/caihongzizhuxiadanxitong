function SelectAll(chkAll) {
	var items = $('.shop');
	for (i = 0; i < items.length; i++) {
		if (items[i].id.indexOf("tid") != -1 && items[i].type == "checkbox") {
			items[i].checked = chkAll.checked;
		}
	}
}
var shoplist;
$(document).ready(function(){
$("#add_submit").click(function () {
	var shequ = $("input[name='shequ']").val();
	var mcid = $("#mcid").val();
	var prid = $("#prid").val();
	if(mcid == -1){
		layer.alert('请选择保存到本站的分类');return false;
	}
	if(prid == -1){
		layer.alert('请选择使用的加价模板');return false;
	}
	var newshoplist = new Array();
	var items = $('.shop');
	for (i = 0; i < items.length; i++) {
		if (items[i].id.indexOf("tid") != -1 && items[i].type == "checkbox" && items[i].checked == true) {
			var tid = items[i].value;
			newshoplist.push(shoplist[tid]);
		}
	}
	if(newshoplist.length <= 0){
		layer.alert('请至少选中一个商品');return false;
	}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax_shop.php?act=batchaddgoods",
		dataType : 'json',
		data : {shequ:shequ, mcid:mcid, prid:prid, list:newshoplist, cname:$("#cid option:selected").text(), cimg:$("#cid option:selected").attr('data-shopimg')},
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.alert(data.msg, {icon:1}, function(){window.location.reload()});
			}else{
				layer.alert(data.msg, {icon:2});
			}
		},
		error:function(data){
			layer.msg('加载失败，请刷新重试');
			return false;
		}
	});
});
$("#cid").change(function () {
	var cid = $(this).val();
	var shequ = $("input[name='shequ']").val();
	if(cid==-1)return;
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	shoplist = new Array();
	$("#shoplist").empty();
	$("#shoplist").append('<tr><td><label class="csscheckbox csscheckbox-primary">全选<input type="checkbox" onclick="SelectAll(this)"><span></span></label>&nbsp;ID</td><td>商品名称</td><td>成本价</td><td>状态</td></tr>');
	$.ajax({
		type : "POST",
		url : "ajax_shop.php?act=goodslistbycid",
		dataType : 'json',
		data : {shequ:shequ, cid:cid},
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var num = 0;
				$.each(data.data, function (i, item) {
					shoplist[item.tid] = JSON.stringify(item);
					$("#shoplist").append('<tr><td><label class="csscheckbox csscheckbox-primary"><input name="tid[]" type="checkbox" class="shop" id="tid" value="'+item.tid+'"><span></span>&nbsp;'+item.tid+'<label></label></label></td><td>'+item.name+'</td><td>'+item.price+'</td><td>'+(item.close==1?'<span class="label label-warning">已下架</span>':'<span class="label label-success">上架中</span>')+'</td></tr>');
					num++;
				});
				if(num==0)layer.msg('该分类下没有商品', {icon:0, time:800});
				else $("#newclass").html("--新建分类【"+$("#cid option:selected").html()+"】--");
			}else{
				layer.alert(data.msg, {icon:2});
			}
		},
		error:function(data){
			layer.msg('加载失败，请刷新重试');
			return false;
		}
	});
});
})