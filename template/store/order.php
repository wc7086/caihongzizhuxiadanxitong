<?php
if (!defined('IN_CRONLITE')) die();
if(checkmobile()){include_once TEMPLATE_ROOT.'store/orderm.php';exit;}

$orderid=trim(daddslashes($_GET['orderid']));
$row=$DB->getRow("select * from pre_pay where trade_no='$orderid' limit 1");
if(!$row)sysmsg('当前订单不存在');
if($row['status']==1)exit("<script language='javascript'>alert('当前订单已完成支付！');window.location.href='./?buyok=1';</script>");

$tool=$DB->getRow("SELECT A.*,B.blockpay FROM pre_tools A LEFT JOIN pre_class B ON A.cid=B.cid WHERE tid='{$row['tid']}' LIMIT 1");
if($tool['is_curl']==4){
	$isfaka=true;
}

$input=$tool['input']?$tool['input']:'下单账号';
$inputs=explode('|',$tool['inputs']);
$inputsdata=explode('|',$row['input']);
$show=$input.'：'.$inputsdata[0];
$i=1;
foreach($inputs as $input){
	if(!$input)continue;
	if(strpos($input,'{')!==false && strpos($input,'}')!==false){
		$input = substr($input,0,strpos($input,'{'));
	}
	if(strpos($input,'[')!==false && strpos($input,']')!==false){
		$input = substr($input,0,strpos($input,'['));
	}
	$show.='<br/>'.$input.'：'.(strpos($input,'密码')===false?$inputsdata[$i++]:'********');
}

if (isset($_GET['set'])) $show = '<font size="3" color="#f4a460">考虑到用户隐私问题，平台已经隐藏该用户下单信息</font>';

$level = '<font color="#48d1cc">普通用户售价</font>';
if($islogin2==1){
	$price_obj = new \lib\Price($userrow['zid'],$userrow);
	if($userrow['power']==2){
		$level = '<font color="orange">高级代理售价</font>';
	}elseif($userrow['power']==1){
		$level = '<font color="red">普通代理售价</font>';
	}
}elseif($is_fenzhan == true){
	$price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
	$price_obj = new \lib\Price(1);
}

if(isset($price_obj)){
	$price_obj->setToolInfo($tool['tid'],$tool);
	$price=$price_obj->getToolPrice($tool['tid']);
}else $price=$tool['price'];

$share_link = '我钱不够买这个东西，能够帮我买一下嘛~，这是付款订单,谢谢啦 '.$siteurl.'?mod=order&orderid='.$orderid.'&set';

if($conf['forcermb']==1){$conf['alipay_api']=0;$conf['wxpay_api']=0;$conf['qqpay_api']=0;}
if(!empty($tool['blockpay'])){
	$blockpay = explode(',',$tool['blockpay']);
	if(in_array('alipay',$blockpay))$conf['alipay_api']=0;
	if(in_array('qqpay',$blockpay))$conf['qqpay_api']=0;
	if(in_array('wxpay',$blockpay))$conf['wxpay_api']=0;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo '购买' . $row['name'] . '确认订单 - ' . $conf['sitename'].($conf['title']==''?'':' - '.$conf['title'])  ?></title>
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
<body style="background:#f5f5f5;overflow-x: hidden;padding: 1em;font-family: '微软雅黑 Light'">
<div class="layui-container" style="padding: 0;margin-top: 1em;">
    <div class="layui-row layui-col-space8">
        <div class="layui-col-sm12" style="padding: 0em;">
            <div class="layui-card" style="border-radius: 0.5em">
                <div class="layui-card-header">
                    <a href="javascript:history.go(-1)">
                        <p style="margin-top: 0.5em;font-size: 1.2em"><span
                                    class="layui-icon layui-icon-left"></span> 确认订单</p>
                    </a>
                </div>
                <div class="layui-row layui-col-space8">
                    <div class="layui-col-xs2">
                        <div style="border-radius: 0.5em;width: 3em;text-align: center;height: 3em;line-height: 3em;color:white;margin: 0.8em;box-shadow: 3px 3px 16px #eee">
                            <img src="./assets/img/pays.png" width="35"/>
                        </div>
                    </div>
                    <div class="layui-col-xs10">
                        <div class="layui-card-header" style="font-size: 1.1em;font-family: '微软雅黑 Light'">
                            下单信息
                        </div>
                        <div class="layui-card-body information">
                            <?php echo $show?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="layui-col-sm6" style="margin-top: 0.5em;">
            <div class="layui-card" style="border-radius: 0.5em">
                <div class="layui-card-header">
                    <i class="layui-icon layui-icon-website"></i> <?php echo $conf['sitename'] ?>
                    <span style="float: right;"><?php echo $isfaka ? '<span class="layui-badge layui-bg-orange">自动发卡' : '<span class="layui-badge" style="background-color: lightsalmon">自动发货' ?></span></span>
                </div>
                <div class="layui-row layui-col-space8">
                    <div class="layui-col-xs2">
                        <div style="border-radius: 0.5em;text-align: center;margin: 0.8em;box-shadow: 3px 3px 16px #eee" id="layer-photos-demo" class="layer-photos-demo">
                            <img alt="<?php echo $tool['name'] ?>" layer-src="<?php echo $tool['shopimg']?$tool['shopimg']:'assets/store/picture/error_img.png' ?>" src="<?php echo $tool['shopimg']?$tool['shopimg']:'assets/store/picture/error_img.png' ?>" style="width: 100%;border-radius: 0.5em"/>
                        </div>
                    </div>
                    <div class="layui-col-xs10">
                        <div class="layui-card-header"
                             style="font-size: 1.1em;line-height: 3.4em;height:auto;">
                            <?php echo $tool['name'] ?>
                        </div>
                        <div class="layui-card-body information layui-text" style="line-height: 2em">
                            <p><span>购买数量</span><span style="float: right;"><?php echo $row['num'] ?>个</span></p>
                            <p>售价等级<span style="float: right;"><?php echo $level ?></span></p>
                            <p>商品售价<span style="float: right;"><?php echo $row['money'] ?> 元</span>
                            </p>
                            <p style="float: right;margin-top: 0.8em;font-size: 1.05em">共<?php echo $row['num'] ?>份 小计：<font color="#ff4500" style="font-size: 1.1em">￥<?php echo $row['money'] ?></font>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="layui-col-sm6" style="margin-top: 0.5em;">
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

        <div class="layui-col-sm12">
            <div class="layui-card" style="border-radius: 0.5em">
                <div class="layui-card-body" style="padding: 0;height: 4em;line-height: 4em;text-align: right;border-radius: 0.8em">
					<input type="hidden" id="orderid" value="<?php echo $orderid?>">
					<input type="hidden" id="tid" value="<?php echo $row['tid']?>">
                    <button class="layui-btn layui-btn-danger layui-btn-radius" style="float: right;margin:0.5em 1em 0 1em;background: linear-gradient(to right, #f85032, #e73827);margin-right: 0.5em;" id="dopay">
                        提交订单
                    </button>
                    <span style="font-size: 1.2em;float: right;text-indent: 0.5em"><span style="color: #A6A6A6;font-size: 1em">共<?php echo $row['num'] ?>份，</span><span>合计：</span><font color="#ff4500">￥<?php echo $row['money'] ?>元</font></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $cdnpublic ?>jquery/3.4.1/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnpublic ?>limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script src="assets/store/js/order.js?ver=<?php echo VERSION ?>"></script>
<script>
layer.photos({
  photos: '#layer-photos-demo'
  ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
});
layer.tips('点击图片查看大图', '#layer-photos-demo', {
  tips: [3, '#78BA32']
});
layui.use('form', function(){
  var form = layui.form;
  $("input[name='pay']:first").prop('checked', true);
  form.render('radio');
});
</script>

</body>
</html>