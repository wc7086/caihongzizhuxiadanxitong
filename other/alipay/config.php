<?php
$config = array (
	//签名方式,默认为RSA2(RSA2048)
	'sign_type' => "RSA2",

	//支付宝公钥
	'alipay_public_key' => $conf['alipay_publickey'],

	//商户私钥
	'merchant_private_key' => $conf['alipay_privatekey'],

	//编码格式
	'charset' => "UTF-8",

	//支付宝网关
	'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

	//应用ID
	'app_id' => $conf['alipay_appid'],

	//异步通知地址
	'notify_url' => $siteurl.'alipay_notify.php',

	//同步通知地址
	'return_url' => $siteurl.'alipay_return.php',

);