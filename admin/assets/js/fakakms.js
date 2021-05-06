var checkflag1 = "false";
function check1(field) {
if (checkflag1 == "false") {
for (i = 0; i < field.length; i++) {
field[i].checked = true;}
checkflag1 = "true";
return "false"; }
else {
for (i = 0; i < field.length; i++) {
field[i].checked = false; }
checkflag1 = "false";
return "true"; }
}

function unselectall1()
{
    if(document.form1.chkAll1.checked){
	document.form1.chkAll1.checked = document.form1.chkAll1.checked&0;
	checkflag1 = "false";
    } 	
}

function showkms(obj) {
	$(obj).css("white-space","normal");
	$(obj).css("word-break","break-all");
}

function checkAdd(){
	if($("#tid").val()==0||$("#tid").val()==null){
		layer.alert('请先选择商品');return false;
	}
	if($("#kms").val()==''){
		layer.alert('卡密列表不能为空');return false;
	}
}
$(document).ready(function(){
	$("#cid").change(function () {
		var cid = $(this).val();
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$("#tid").empty();
		$("#tid").append('<option value="0">请选择商品</option>');
		$.ajax({
			type : "GET",
			url : "./ajax.php?act=getfakatool&cid="+cid,
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
					if(num==0 && cid!=0)$("#tid").html('<option value="0">该分类下没有发卡类商品</option>');
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
	var items = $("select[default]");
	for (i = 0; i < items.length; i++) {
		$(items[i]).val($(items[i]).attr("default")||0);
	}
});