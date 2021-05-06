<?php
/* *
 * 码支付异步通知页面
 */

require_once("./inc.php");
require_once(SYSTEM_ROOT."codepay/codepay_config.php");
ksort($_POST); //排序post参数
reset($_POST); //内部指针指向数组中的第一个元素
$sign = '';
foreach ($_POST AS $key => $val) {
    if ($val == '') continue;
    if ($key != 'sign') {
        if ($sign != '') {
            $sign .= "&";
            $urls .= "&";
        }
        $sign .= "$key=$val"; //拼接为url参数形式
        $urls .= "$key=" . urlencode($val); //拼接为url参数形式
    }
}

if ($conf['alipay_api']!=5 && $conf['qqpay_api']!=5 && $conf['wxpay_api']!=5) {
	exit('fail');
} elseif (empty($codepay_config['key']) || !$_POST['pay_no'] || md5($sign . $codepay_config['key']) != $_POST['sign']) { //不合法的数据 KEY密钥为你的密钥
    exit('fail');
} else { //合法的数据

    $out_trade_no = daddslashes($_POST['param']);

    //支付宝交易号
    $trade_no = daddslashes($_POST['pay_no']);

    $srow=$DB->getRow("SELECT * FROM pre_pay WHERE trade_no='{$out_trade_no}' LIMIT 1");
    if($srow['status']==0) {
		if($DB->exec("UPDATE `pre_pay` SET `status` ='1' WHERE `trade_no`='{$out_trade_no}'")){
			$DB->exec("UPDATE `pre_pay` SET `endtime` ='$date',`api_trade_no` ='$trade_no' WHERE `trade_no`='{$out_trade_no}'");
			processOrder($srow);
		}
    }
    exit('success');
}

?>