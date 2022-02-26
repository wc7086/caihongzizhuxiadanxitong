<?php
require 'inc.php';

@header('Content-Type: text/html; charset=UTF-8');

$trade_no=daddslashes($_GET['trade_no']);
if($conf['wxpay_api']!=1 && $conf['wxpay_api']!=3)exit('当前支付接口未开启');
$row=$DB->getRow("SELECT * FROM pre_pay WHERE trade_no='{$trade_no}' LIMIT 1");
if(!$row)exit('该订单号不存在，请返回来源地重新发起请求！');

if($conf['wxpay_domain'] && $conf['wxpay_domain']!=$_SERVER['HTTP_HOST']){
	$DB->exec("UPDATE `pre_pay` SET `domain` ='{$_SERVER['HTTP_HOST']}' WHERE `trade_no`='{$trade_no}'");
	echo '<script>window.location.href=\'http://'.$conf['wxpay_domain'].'/other/wxwappay.php?trade_no='.$trade_no.'\';</script>';
	exit;
}

$ordername = !empty($conf['ordername'])?ordername_replace($conf['ordername'],$row['name'],$trade_no):$row['name'];

if($conf['wxpay_api']==3){
require_once SYSTEM_ROOT."wxpay/WxPay.Api.php";
$input = new WxPayUnifiedOrder();
$input->SetBody($ordername);
$input->SetOut_trade_no($trade_no);
$input->SetTotal_fee($row['money']*100);
$input->SetSpbill_create_ip($clientip);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetNotify_url($siteurl.'wxpay_notify.php');
$input->SetTrade_type("MWEB");
$result = WxPayApi::unifiedOrder($input);
if($result["result_code"]=='SUCCESS'){
	$redirect_url=$siteurl.'wxwap_return.php?trade_no='.$trade_no;
	$url=$result['mweb_url'].'&redirect_url='.urlencode($redirect_url);
	exit("<script>window.location.replace('{$url}');</script>");
}elseif(isset($result["err_code"])){
	sysmsg('微信支付下单失败！['.$result["err_code"].'] '.$result["err_code_des"]);
}else{
	sysmsg('微信支付下单失败！['.$result["return_code"].'] '.$result["return_msg"]);
}
}else{
	$target_url = $siteurl.'wxjspay.php?trade_no='.$trade_no;
}
?>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
  <meta name="renderer" content="webkit"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>微信安全支付</title>
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>

<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
<div class="panel panel-primary">
	<div class="panel-heading" style="text-align: center;"><h3 class="panel-title">
		微信安全支付
	</div>
		<div class="list-group" style="text-align: center;">
			<div class="list-group-item list-group-item-info">长按保存到相册使用扫码扫码完成支付</div>
			<div class="list-group-item">
			<div class="qr-image" id="qrcode"></div>
			</div>
			<div class="list-group-item list-group-item-info">或复制以下链接到微信打开：</div>
			<div class="list-group-item" style="word-wrap: break-word;">
			<a href="<?php echo $target_url?>"><?php echo $target_url?></a><br/><button id="copy-btn" data-clipboard-text="<?php echo $target_url?>" class="btn btn-info btn-sm">一键复制</button>
			</div>
			<div class="list-group-item"><small>提示：你可以将以上链接发到自己微信的聊天框（在微信顶部搜索框可以搜到自己的微信），即可点击进入支付</small></div>
			<div class="list-group-item"><a href="weixin://" class="btn btn-primary">打开微信</a>&nbsp;<a href="#" onclick="checkresult()" class="btn btn-success">检测支付状态</a></div>
		</div>
</div>
</div>
<script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
<script src="//cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script src="//cdn.staticfile.org/layer/3.1.1/layer.min.js"></script>
<script src="//cdn.staticfile.org/clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
	var clipboard = new Clipboard('#copy-btn');
	clipboard.on('success', function(e) {
		layer.msg('复制成功，请到微信里面粘贴');
	});
	clipboard.on('error', function(e) {
		layer.msg('复制失败，请长按链接后手动复制');
	});
	$('#qrcode').qrcode({
        text: "<?php echo $target_url?>",
        width: 230,
        height: 230,
        foreground: "#000000",
        background: "#ffffff",
        typeNumber: -1
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
					layer.msg('支付成功，正在跳转中...', {icon: 16,shade: 0.1,time: 15000});
					setTimeout(window.location.href=data.backurl, 1000);
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
	function checkresult() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "getshop.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {type: "wxpay", trade_no: "<?php echo $row['trade_no']?>"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
					layer.msg('支付成功，正在跳转中...', {icon: 16,shade: 0.1,time: 15000});
					setTimeout(window.location.href=data.backurl, 1000);
                }else{
					layer.msg('您还未完成付款，请继续付款', {shade: 0,time: 1500});
				}
            }
        });
    }
    window.onload = loadmsg();
</script>
</body>
</html>