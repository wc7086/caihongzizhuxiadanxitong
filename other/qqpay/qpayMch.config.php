<?php

/**
 * qpayMch.config.php
 * Created by HelloWorld
 * vers: v1.0.0
 * User: Tencent.com
 */

define("QQ_MCH_ID", $conf['qqpay_mchid']);
define("QQ_MCH_KEY", $conf['qqpay_key']);

class QpayMchConf
{
    /**
     * QQ钱包商户号
     */
    const MCH_ID = QQ_MCH_ID;

    /**
     * API密钥。
     * QQ钱包商户平台(http://qpay.qq.com/)获取
     */
    const MCH_KEY = QQ_MCH_KEY;

}