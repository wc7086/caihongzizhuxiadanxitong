<?php
if (!defined('IN_CRONLITE')) die();
$orderid=trim(daddslashes($_GET['orderid']));
$row=$DB->getRow("select * from pre_pay where trade_no='$orderid' limit 1");
if(!$row)sysmsg('当前订单不存在');
if($row['status']==1)exit("<script language='javascript'>alert('当前订单已完成支付！');window.location.href='./?buyok=1';</script>");

$share_link = '我钱不够买这个东西，能够帮我买一下嘛~，这是付款订单,谢谢啦 '.$siteurl.'?mod=cartorder&orderid='.$orderid.'&set';

$DataList = $row['input'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo '购物车结算 - ' . $conf['sitename'] . ($conf['title'] == '' ? '' : ' - ' . $conf['title']) ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    <link href="<?php echo $cdnpublic ?>limonte-sweetalert2/7.33.1/sweetalert2.min.css" rel="stylesheet">
    <link href="<?php echo $cdnpublic ?>animate.css/3.7.2/animate.min.css" rel="stylesheet">
</head>
<style>
    .information {
        font-size: 0.9em;
        color: black
    }
</style>
<body style="background:#f5f5f5;overflow-x: hidden;font-family: '微软雅黑 Light'">
<div class="layui-container" style="padding: 0">
    <div class="layui-row layui-col-space8">
        <div class="layui-col-xs12"
             style="z-index: 3;;background-color: white;position: fixed;top: 0;left: 0;height: 3em;line-height: 1.5em;box-shadow: 3px 3px 16px #ccc">
            <a href="?mod=cart">
                <p style="margin-top: 0.5em;font-size: 1.2em"><span class="layui-icon layui-icon-left"></span>
                    确认订单</span></p>
            </a>
        </div>
        <div class="layui-col-xs12" style="margin-top: 3.5em;padding: 1em;">
            <div class="layui-card" style="border-radius: 0.5em">
                <div class="layui-row layui-col-space8">
                    <div class="layui-col-xs2">
                        <div style="border-radius: 0.5em;width: 3em;text-align: center;height: 3em;line-height: 3em;color:white;margin: 0.8em;box-shadow: 3px 3px 16px #eee">
                            <img src="./assets/img/pays.png" width="35"/>
                        </div>
                    </div>
                    <div class="layui-col-xs10">
                        <div class="layui-card-header" style="width: 100%;font-size: 1.1em;font-family: '微软雅黑 Light'">
                            <?php echo $conf['sitename'] . ' - ' . $conf['title'] ?>
                        </div>
                        <div class="layui-card-body information">
                            付款后订单未到账可联系客服处理！
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="Content"></div>

        <div class="layui-col-xs12" style="padding: 0.1em 1em 0 1em;margin-top: 0.9em;margin-bottom: 5em;">
            <div class="layui-card" style="border-radius: 0.5em">
                <div class="layui-card-header">
                    <i class="layui-icon layui-icon-auz"></i> 请选择付款方式
                </div>
                <div class="layui-card-body layui-form layui-form-pane" style="padding: 0.5em">
                    <?php  if($islogin2==1 && $userrow['rmb']  > 0 ){ ?>
                    <div style="padding: 0.3em;border-radius: 0.3em;line-height: 2em;height: 2.5em;">
                        <input type="radio" name="pay" value="rmb"
                               title="<p style='width:100%'><img src='./assets/img/rmb.png' width='13px' /> 余额 <span style='color:#999;font-size:0.8em;margin-left:1em'>剩<?php echo $userrow['rmb']?>元</span></p>">
                    </div>
                    <?php } ?>
					
					<?php  if($conf['alipay_api'] != 0 ){ ?>
                    <div style="padding: 0.3em;border-radius: 0.3em;line-height: 2em;height: 2.5em;">
                        <input type="radio" name="pay" <?php echo ($conf['alipay_api'] != 0 ? '' : 'disabled') ?>
                               value="alipay"
                               title="<p style='width:100%'><img src='./assets/img/alipay.png' width='13px' /> 支付宝</p>">
                    </div>
                    <?php } ?>
                    
                    <?php  if($conf['wxpay_api'] != 0 ){ ?>
                    <div style="padding: 0.3em;border-radius: 0.3em;line-height: 2em;height: 2.5em;">
                        <input type="radio" name="pay" <?php echo ($conf['wxpay_api'] != 0 ? '' : 'disabled') ?> value="wxpay"
                               title="<p style='width:100%'><img src='./assets/img/wxpay.png' width='13px' /> 微信</p>">
                    </div>
                    <?php } ?>
                    
                    <?php  if($conf['qqpay_api'] != 0 ){ ?>
                    <div style="padding: 0.3em;border-radius: 0.3em;line-height: 2em;height: 2.5em;">
                        <input type="radio" name="pay" <?php echo ($conf['qqpay_api'] != 0 ? '' : 'disabled') ?> value="qqpay"
                               title="<p style='width:100%'><img src='./assets/img/qqpay.png' width='13px' /> QQ钱包</p>">
                    </div>
                    <?php } ?>
                    
                    <div id="demo_url" data-url="<?php echo $share_link ?>" style="padding: 0.3em;border-radius: 0.3em;line-height: 2em;height: 2.5em">
                        <input type="radio" name="pay" value="help"
                               title="<p style='width:100%'><img src='./assets/img/payd.png' width='13px' /> 帮我付 (朋友代付)</p>">
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-xs12"
             style="z-index: 3;;background-color: white;position: fixed;bottom: 0;left: 0;height: 4em;line-height: 3.5em;box-shadow: 0px 0px 8px #ccc;text-align: right;">
			<input type="hidden" id="orderid" value="<?php echo $orderid?>">
            <span style="font-size: 1.2em;float: left;text-indent: 0.5em"><span style="color: #A6A6A6;font-size: 0.9em">共<?php echo $row['num'] ?>份，</span><span>合计：</span><font color="#ff4500">￥<?php echo $row['money'] ?>元</font></span>
            <button class="layui-btn layui-btn-danger layui-btn-radius" type="submit" style="background: linear-gradient(to right, #f85032, #e73827);margin-right: 0.5em;" onclick="GoodsCart.PaySubmit('<?php echo $DataList ?>')">提交订单
            </button>
        </div>
    </div>
</div>

<script src="<?php echo $cdnpublic ?>jquery/3.4.1/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnpublic ?>limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script src="assets/store/js/cart.js?ver=<?php echo VERSION ?>"></script>
<script>
    GoodsCart.PaySrt('<?php echo $DataList?>');
layui.use('form', function(){
  var form = layui.form;
  $("input[name='pay']:first").prop('checked', true);
  form.render('radio');
});
</script>

</body>
</html>
