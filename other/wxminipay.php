<?php
require 'inc.php';

//微信小程序支付

@header('Content-Type: application/json; charset=UTF-8');
$trade_no=daddslashes($_GET['trade_no']);
if($conf['wxpay_api']!=1 && $conf['wxpay_api']!=3)exit('{"code":-1,"msg":"当前支付接口未开启"}');
$row=$DB->getRow("SELECT * FROM pre_pay WHERE trade_no='{$trade_no}' LIMIT 1");
if(!$row)exit('{"code":-1,"msg":"该订单号不存在"}');

$code = isset($_GET['code'])?trim($_GET['code']):exit('{"code":-1,"msg":"code不能为空"}');

$ordername = !empty($conf['ordername'])?ordername_replace($conf['ordername'],$row['name'],$trade_no):$row['name'];

require_once SYSTEM_ROOT."wxpay/WxPay.Api.php";
require_once SYSTEM_ROOT."wxpay/WxPay.MiniAppPay.php";

//①、获取用户openid
$tools = new MiniAppPay();
$openId = $tools->GetOpenid($code);
if(!$openId)exit('{"code":-1,"msg":"OpenId获取失败('.$tools->data['errmsg'].')"}');

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($ordername);
$input->SetOut_trade_no($trade_no);
$input->SetTotal_fee($row['money']*100);
$input->SetSpbill_create_ip($clientip);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetNotify_url($siteurl.'wxpay_notify.php');
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);

if($order["result_code"]=='SUCCESS'){
	$jsApiParameters = $tools->GetJsApiParameters($order);
	exit(json_encode(['code'=>0, 'data'=>$jsApiParameters]));
}elseif(isset($result["err_code"])){
	exit(json_encode(['code'=>-1, 'msg'=>'微信支付下单失败！['.$result["err_code"].'] '.$result["err_code_des"]]));
}else{
	exit(json_encode(['code'=>-1, 'msg'=>'微信支付下单失败！['.$result["return_code"].'] '.$result["return_msg"]]));
}
