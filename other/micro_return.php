<?php
require_once("./inc.php");

@header('Content-Type: text/html; charset=UTF-8');

require_once(SYSTEM_ROOT."epay/micro.config.php");
require_once(SYSTEM_ROOT."epay/micro_notify.class.php");

if(!isset($_GET['out_trade_no'])) exit();
$out_trade_no = daddslashes($_GET['out_trade_no']);
$srow=$DB->getRow("SELECT * FROM pre_pay WHERE trade_no='{$out_trade_no}' LIMIT 1");
if(!$srow)exit('该订单号不存在');

if ($srow['status']==1) {
	showalert('您所购买的商品已付款成功，感谢购买！',1,$out_trade_no,$srow['tid']);
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <link href="//cdn.staticfile.org/ionic/1.3.2/css/ionic.min.css" rel="stylesheet" />
</head>
<body>
<div class="bar bar-header bar-light" align-title="center">
	<h1 class="title">订单处理结果</h1>
</div>
<div class="has-header" style="padding: 5px;position: absolute;width: 100%;">
<div class="text-center" style="color: #a09ee5;">
<i class="icon ion-information-circled" style="font-size: 80px;"></i><br>
<span>正在检测付款结果...</span>
<script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
<script>
	// 检查是否支付完成
    function loadmsg() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "getshop.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {type: "wxpay", trade_no: "<?php echo $out_trade_no?>"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
                    if (confirm("您已支付完成，需要跳转到订单页面吗？")) {
                        window.location.href=data.backurl;
                    } else {
                        // 用户取消
                    }
                }else{
                    setTimeout("loadmsg()", 3000);
                }
            },
            //Ajax请求超时，继续查询
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == "timeout") {
                    setTimeout("loadmsg()", 1000);
                } else { //异常
                    setTimeout("loadmsg()", 4000);
                }
            }
        });
    }
    window.onload = loadmsg();
</script>
</div>
</div>
</body>
</html>