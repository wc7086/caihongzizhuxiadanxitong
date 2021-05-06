<?php
require 'inc.php';

@header('Content-Type: text/html; charset=UTF-8');

$trade_no=daddslashes($_GET['trade_no']);
if($conf['alipay_api']!=3)exit('当前支付接口未开启');
$row=$DB->getRow("SELECT * FROM pre_pay WHERE trade_no='{$trade_no}' LIMIT 1");
if(!$row)exit('该订单号不存在，请返回来源地重新发起请求！');

$ordername = !empty($conf['ordername'])?str_replace('[time]',time(),$conf['ordername']):$row['name'];

require_once(SYSTEM_ROOT."alipay/model/builder/AlipayTradePrecreateContentBuilder.php");
require_once(SYSTEM_ROOT."alipay/AlipayTradeService.php");

// 创建请求builder，设置请求参数
$qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
$qrPayRequestBuilder->setOutTradeNo($trade_no);
$qrPayRequestBuilder->setTotalAmount($row['money']);
$qrPayRequestBuilder->setSubject($ordername);

// 调用qrPay方法获取当面付应答
$qrPay = new AlipayTradeService($config);
try{
	$qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);
}catch(Exception $e){
	sysmsg('支付宝接口请求失败！'.$e->getMessage());
}

//	根据状态值进行业务处理
$status = $qrPayResult->getTradeStatus();
$response = $qrPayResult->getResponse();
if($status == 'SUCCESS'){
	$code_url = $response->qr_code;
}elseif($status == 'FAILED'){
	sysmsg('支付宝创建订单二维码失败！['.$response->sub_code.']'.$response->sub_msg);
}else{
	print_r($response);
	sysmsg('系统异常，状态未知！');
}

if(checkmobile()==true){
	include 'alipaywap.php';
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="zh-cn">
<meta name="renderer" content="webkit">
<title>支付宝扫码支付 - <?php echo $conf['sitename']?></title>
<link href="assets/css/alipay_pay.css" rel="stylesheet" media="screen">
</head>
<body>
<div class="body">
<h1 class="mod-title">
<span class="ico-wechat"></span><span class="text">支付宝扫码支付</span>
</h1>
<div class="mod-ct">
<div class="order">
</div>
<div class="amount">￥<?php echo $row['money']?></div>
<div class="qr-image" id="qrcode">
</div>
 
<div class="detail" id="orderDetail">
<dl class="detail-ct" style="display: none;">
<dt>购买物品</dt>
<dd id="productName"><?php echo $row['name']?></dd>
<dt>商户订单号</dt>
<dd id="billId"><?php echo $row['trade_no']?></dd>
<dt>创建时间</dt>
<dd id="createTime"><?php echo $row['addtime']?></dd>
</dl>
<a href="javascript:void(0)" class="arrow"><i class="ico-arrow"></i></a>
</div>
<div class="tip">
<span class="dec dec-left"></span>
<span class="dec dec-right"></span>
<div class="ico-scan"></div>
<div class="tip-text">
<p>请使用支付宝扫一扫</p>
<p>扫描二维码完成支付</p>
</div>
</div>
<div class="tip-text">
</div>
</div>
<?php if(checkmobile()==true){?>
<div class="foot">
<div class="inner">
<div id="J_downloadInteraction" class="download-interaction download-interaction-opening">
	<div class="inner-interaction">
		<p class="download-opening">正在打开支付宝<span class="download-opening-1">.</span><span class="download-opening-2">.</span><span class="download-opening-3">.</span></p>
		<p class="download-asking">如果没有打开支付宝，<a id="J_downloadBtn" href="javascript:;" onclick="openAli();">请点此重新唤起</a></p>
</div>
</div>
</div>
</div>
<?php }?>
<script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
<script src="//cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script>
	var code_url = '<?php echo $code_url?>';
    $('#qrcode').qrcode({
        text: code_url,
        width: 230,
        height: 230,
        foreground: "#000000",
        background: "#ffffff",
        typeNumber: -1
    });
    // 订单详情
    $('#orderDetail .arrow').click(function (event) {
        if ($('#orderDetail').hasClass('detail-open')) {
            $('#orderDetail .detail-ct').slideUp(500, function () {
                $('#orderDetail').removeClass('detail-open');
            });
        } else {
            $('#orderDetail .detail-ct').slideDown(500, function () {
                $('#orderDetail').addClass('detail-open');
            });
        }
    });
    // 检查是否支付完成
    function loadmsg() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "getshop.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {type: "wxpay", trade_no: "<?php echo $row['trade_no']?>"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
					if (confirm("您已支付完成，需要跳转到订单页面吗？")) {
                        window.location.href=data.backurl;
                    } else {
                        // 用户取消
                    }
                }else{
                    setTimeout("loadmsg()", 4000);
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


	function openAli(){
		var scheme = 'alipays://platformapi/startapp?saId=10000007&qrcode=';
		scheme += encodeURIComponent(code_url);

		if(navigator.userAgent.indexOf("Safari") > -1){
			window.location.href = scheme;
		}
		else{
			var iframe = document.createElement("iframe");
			iframe.style.display = "none";
			iframe.src = scheme;
			document.body.appendChild(iframe);
		}
	}
	window.onload = function(){
		openAli();
		setTimeout("loadmsg()", 2000);
	}
</script>
</body>
</html>