<?php
if(!defined('IN_CRONLITE'))exit();
?>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
  <meta name="renderer" content="webkit"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>支付宝支付 - <?php echo $conf['sitename']?></title>
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
    <br>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
<div class="panel panel-primary">
    <div class="panel-heading" style="text-align: center;"><h3 class="panel-title">
        <img src="../assets/img/alipay.png" style="height:22px">
     支付宝支付手机版
    </div>
        <div class="list-group" style="text-align: center;">
            <div class="list-group-item list-group-item-info">手机截图保存到相册使用扫码完成支付</div>
            <div class="list-group-item">
            <div class="qr-image" id="qrcode"></div>
            <br>
            <font style="font-size:18px">应付金额：<?php echo $row['money']?>元</font>
            <br>
            <font color="red">(订单遇到问题请联系购买网站客服获取帮助)</font><br><font color="red">(支付完成需要耐心等待一会！！！)</font>

             <hr>
             <div class="foot">
<div class="inner">
<div id="J_downloadInteraction" class="download-interaction download-interaction-opening">
    <div class="inner-interaction">
        <p class="download-opening">正在打开支付宝<span class="download-opening-1">.</span><span class="download-opening-2">.</span><span class="download-opening-3">.</span></p>
        <p class="download-asking">如果没有打开支付宝，<a class="btn btn-info btn-block" href="javascript:;" onclick="alijspaywap();">请点此重新唤起</a></p>
	</div>
</div>
</div>
</div>
				<font color="red">如果自动打开支付宝无法支付 请关闭支付宝应用后 手动保存二维码 再次打开支付宝扫码支付！</font>
            </div>
        </div>
</div>
</div>
<script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
<script src="//cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script>
    var code_url = '<?php echo $code_url?>';
    $('#qrcode').qrcode({
        text: code_url,
        width: 180,
        height: 180,
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
           data: {type: "alipay", trade_no: "<?php echo $row['trade_no']?>",'r':Math.random()}, 
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
    function checkresult() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/getshop.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {type: "alipay", trade_no: "<?php echo $order['trade_no']?>"},
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
                    if (confirm("您已支付完成，需要跳转到订单页面吗？")) {
                        window.location.href=data.backurl;
                    } else {
                        // 用户取消
                    }
                }
            }
        });
    }
    window.onload = loadmsg();
</script>
<script>
    function alijswap(qrcode, flag) {
	var isflag = false;
	if (isflag) {
		return
	};
	isflag = true;
	url = 'alipayqr://platformapi/startapp?saId=10000007&clientVersion=3.7.0.0718&qrcode=' + encodeURIComponent(qrcode);
	location['href'] = url;
	flag = typeof(flag) == 'undefined' ? '': flag;
	setTimeout(function() {
		if (typeof flag !== 'string') {
			flag = ''
		};
		if (flag && typeof flag === 'string') {
			location['href'] = flag
		}
	},
	2000);
	setTimeout(function() {
		isflag = false
	},
	800)
	}
          function alijspaywap()
          {
             alijswap(code_url);
          }
          alijspaywap();
    </script>
</body>
</html>