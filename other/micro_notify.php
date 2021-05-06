<?php
/* *
 * 功能：彩虹易支付服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */

require_once("./inc.php");
require_once(SYSTEM_ROOT."epay/micro.config.php");
require_once(SYSTEM_ROOT."epay/micro_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result && ($conf['alipay_api']==6 || $conf['qqpay_api']==6 || $conf['wxpay_api']==6) && !empty($conf['micropay_pid']) && !empty($conf['micropay_key'])) {//验证成功
	//商户订单号

	$out_trade_no = daddslashes($_POST['out_trade_no']);

	//支付宝交易号

	$trade_no = daddslashes($_POST['trade_no']);

	//金额
	$money = $_POST['money'];

	$srow=$DB->getRow("SELECT * FROM pre_pay WHERE trade_no='{$out_trade_no}' LIMIT 1");

    if ($_POST['status'] == 1 && $srow['status']==0 && round($srow['money'],2)==round($money,2)) {
		//付款完成后，支付宝系统发送该交易状态通知
		if($DB->exec("UPDATE `pre_pay` SET `status` ='1' WHERE `trade_no`='{$out_trade_no}'")){
			$DB->exec("UPDATE `pre_pay` SET `endtime` ='$date',`api_trade_no` ='$trade_no' WHERE `trade_no`='{$out_trade_no}'");
			processOrder($srow);
		}
    }

	echo "success";
}
else {
    //验证失败
    echo "fail";
}
?>