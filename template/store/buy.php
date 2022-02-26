<?php
if (!defined('IN_CRONLITE')) die();
$tid=intval($_GET['tid']);
$tool=$DB->getRow("select * from pre_tools where tid='$tid' limit 1");
if(!$tool)sysmsg('没有找到商品熬！');
function escape($string, $in_encoding = 'UTF-8',$out_encoding = 'UCS-2') { 
    $return = ''; 
    if (function_exists('mb_get_info')) { 
        for($x = 0; $x < mb_strlen ( $string, $in_encoding ); $x ++) { 
            $str = mb_substr ( $string, $x, 1, $in_encoding ); 
            if (strlen ( $str ) > 1) { // 多字节字符 
                $return .= '%u' . strtoupper ( bin2hex ( mb_convert_encoding ( $str, $out_encoding, $in_encoding ) ) ); 
            } else { 
                $return .= '%' . strtoupper ( bin2hex ( $str ) ); 
            } 
        } 
    } 
    return $return; 
}

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
	if($price_obj->getToolDel($tool['tid'])==1)sysmsg('商品已下架');
	$price=$price_obj->getToolPrice($tool['tid']);
	$islogin3=$islogin2;
	unset($islogin2);
	$price_pt=$price_obj->getToolPrice($tool['tid']);
	$price_1=$price_obj->getToolCost($tool['tid']);
	$price_2=$price_obj->getToolCost2($tool['tid']);
	$islogin2=$islogin3;
}else{
   $price=$tool['price'];
   $price_pt=$tool['price'];
   $price_1=$tool['cost1'];
   $price_2=$tool['cost2'];
}


if($tool['is_curl']==4){
	$count = $DB->getColumn("SELECT count(*) FROM pre_faka WHERE tid='{$tool['tid']}' and orderid=0");
	$fakainput = getFakaInput();
	$tool['input']=$fakainput;
	$isfaka = 1;
	$stock = '<span class="stock" style="">剩余:<span class="quota">'.$count.'</span>份</span>';
}elseif($tool['stock']!==null){
	$count = $tool['stock'];
	$isfaka = 1;
	$stock = '<span class="stock" style="">剩余:<span class="quota">'.$count.'</span>份</span>';
}else{
	$isfaka = 0;
}

if($tool['prices']){
	$arr = explode(',',$tool['prices']);
	if($arr[0]){
		$arr = explode('|',$tool['prices']);
		$view_mall = '<font color="#bdbdbd" size="2">购买'.$arr[0].'个以上按批发价￥'.($price-$arr[1]).'计算</font>';
	}
}

?>
<!DOCTYPE html>
<html lang="zh" style="font-size: 20px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,user-scalable=no">
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
    <meta name="format-detection" content="telephone=no">
    <title><?php echo $conf['sitename'] . ($conf['title'] == '' ? '' : ' - ' . $conf['title']) ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link href="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic ?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style(1).css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/detail.css">
    <link href="<?php echo $cdnpublic ?>limonte-sweetalert2/7.33.1/sweetalert2.min.css" rel="stylesheet">
    <link href="<?php echo $cdnpublic ?>animate.css/3.7.2/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    <link href="<?php echo $cdnpublic ?>Swiper/4.5.1/css/swiper.min.css" rel="stylesheet">
	<?php echo str_replace('body','html',$background_css)?>

</head>

<style>
    .fix-iphonex-bottom {
        padding-bottom: 34px;
    }
</style>

<style>
    select {
        /*Chrome和Firefox里面的边框是不一样的，所以复写了一下*/
        border: solid 1px #000;
        /*很关键：将默认的select选择框样式清除*/
        appearance: none;
        -moz-appearance: none;
        -webkit-appearance: none;
        /*将背景改为红色*/
        background: none;
        /*加padding防止文字覆盖*/
        padding-right: 14px;
    }

    /*清除ie的默认选择框样式清除，隐藏下拉箭头*/
    select::-ms-expand {
        display: none;
    }

	.onclick{cursor: pointer;touch-action: manipulation;}

    .fui-page,
    .fui-page-group {
        -webkit-overflow-scrolling: auto;
    }

    .fui-cell-group .fui-cell .fui-input {
        display: inline-block;
        width: 70%;
        height: 32px;
        line-height: 1.5;
        margin: 0 auto;
        padding: 2px 7px;
        font-size: 12px;
        border: 1px solid #dcdee2;
        border-radius: 4px;
        color: #515a6e;
        background-color: #fff;
        background-image: none;
        cursor: text;
        transition: border .2s ease-in-out, background .2s ease-in-out, box-shadow .2s ease-in-out;
    }

    .btnee {
        width: 20%;
        float: right;
        margin-top: -2.8em;
    }

	.btnee_left {
        width: 20%;
        float: lef;
        margin-top: -2.8em;
    }

    .fui-cell-group .fui-cell .fui-cell-label1 {
        padding: 0 0.4rem;
        line-height: 0.7rem;
    }

    .fui-cell-group .fui-cell.must .fui-cell-label:after {
        top: 40%;
    }

    /*支付方式*/
    .payment-method {
        position: fixed;
        bottom: 0;
        background: white;
        width: 100%;
        padding: 0.75rem 0.7rem;
        z-index: 1000 !important;
    }

    .payment-method .title {
        font-size: 0.75rem;
        text-align: center;
        color: #333333;
        line-height: 0.75rem;
        margin-bottom: 1rem;
    }

    .payment-method .title span {
        height: 0.75rem;
        position: absolute;
        right: 0.3rem;
        width: 2rem;
    }

    .payment-method .title .close:before {
        font-family: 'iconfont';
        content: '\e654';
        display: inline-block;
        transform: scale(1.5);
        color: #ccc;

    }

    .payment-method .payment {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        padding: 0.7rem 0;
    }

    .payment-method .payment .icon-weixin1 {
        color: #5ee467;
        font-size: 1.3rem;
        margin-right: 0.4rem;
    }

    .payment-method .payment .icon-zhifubao1 {
        color: #0b9ff5;
        font-size: 1.5rem;
        margin-right: 0.4rem;
    }

    .icon-zhifubao1::before {
        margin-left: 1px;
    }

    .payment-method .payment .paychoose {
        font-size: 1.2rem;
    }

    .payment-method .payment .icon-xuanzhong4 {
        color: #2e8cf0;
    }

    .payment-method .payment .icon-option_off {
        color: #ddd;
    }

    .payment-method .payment .paytext {
        flex: 1;
        font-size: 0.8rem;
        color: #333;
    }

    .payment-method button {
        margin-top: 0.8rem;
        background: #2e8cf0;
        color: white;
        letter-spacing: 1px;
        font-size: 0.7rem;
        border: none;
        outline: none;
        width: 17.25rem;
        height: 1.75rem;
        border-radius: 1.75rem;
    }

    .input_select {
        flex: 1;
        height: 1.5rem;
        border-radius: 2px;
        border: none;
        border-bottom: 1px solid #eee;
        outline: none;
        margin-left: 0.4rem;
    }
</style>
<style>
    html {
        font-size: 14px;
        color: #000;
        font-family: '微软雅黑'
    }

    a, a:hover {
        text-decoration: none;
    }

    pre {
        font-family: '微软雅黑'
    }

    .box {
        padding: 20px;
        background-color: #fff;
        margin: 50px 100px;
        border-radius: 5px;
    }

    .box a {
        padding-right: 15px;
    }

    #about_hide {
        display: none
    }

    .layer_text {
        background-color: #fff;
        padding: 20px;
    }

    .layer_text p {
        margin-bottom: 10px;
        text-indent: 2em;
        line-height: 23px;
    }

    .button {
        display: inline-block;
        *display: inline;
        *zoom: 1;
        line-height: 30px;
        padding: 0 20px;
        background-color: #56B4DC;
        color: #fff;
        font-size: 14px;
        border-radius: 3px;
        cursor: pointer;
        font-weight: normal;
    }

    .photos-demo img {
        width: 200px;
    }

    .layui-layer-content {
        margin: auto;
    }

    * {
        -webkit-overflow-scrolling: touch;
    }

    .pro_content {
        background-image: linear-gradient(130deg, #00F5B2, #1FC3FF, #00dbde);
        height: 120px;
        position: relative;
        margin-bottom: 4rem;
        background-size: 300%;
        animation: bganimation 10s infinite;
    }

    @keyframes bganimation {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    #picture {
        padding-top: 1em;
    }

    #picture div {
        text-align: center;
    }

    #picture img {
        width: auto;
        max-height: 38vh;
        margin: auto;
    }
	.hd_intro img{ max-width: 100%; }
</style>

<body ontouchstart="" style="overflow: auto;height: auto !important;max-width: 650px;margin: auto;">
<div class="fui-page-group statusbar" style="max-width: 650px;left: auto;">
    <div class="fui-page  fui-page-current " style="overflow: inherit">
        <div id="container" class="fui-content "
             style="background-color: rgb(255, 255, 255); padding-bottom: 60px;">
            <div class="pro_content" style="margin-bottom: 3.5rem;">
                <div class="list_item_box" style="top: 53px;">
                    <div class="bor_detail">
                        <div class="thumb" id="layer-photos-demo" class="layer-photos-demo">
                            <img alt="<?php echo $tool['name']?>" layer-src="<?php echo $tool['shopimg']?$tool['shopimg']:'assets/store/picture/error_img.png';?>"  src="<?php echo $tool['shopimg']?$tool['shopimg']:'assets/store/picture/error_img.png';?>">
                        </div>
                        <div class="pro_right fl">
                            <span id="level">当前级别：<?php echo $level?></span>
                            <a href="./?mod=cart" class="icon icon-cart2 color"
                               style="float: right;margin-right: 1em;background-color: #0079fa;color: white;padding: 0.3rem;border-radius: 3em;box-shadow: 3px 3px 6px #eee;<?php if($conf['shoppingcart']==0){?>display:none;<?php }?>" title="打开购物车"></a>
                            <span class="list_item_title" id="gootsp"><?php echo $tool['name']?></span>
                            <div class="list_tag">
                                <div class="price">
                                    <span class="t_price pay_prices">售价：<span class="pay_price"><?php echo $price?>元</span>
									<?php if($conf['template_showprice']==1){?>
									&nbsp;&nbsp;<button type="button" class="show_daili_price layui-btn layui-btn-warm layui-btn-xs layui-btn-radius "><i class="layui-icon layui-icon-fire"></i>查看等级价格</button>
                                    <?php } ?>
									</span>
                                    <?php echo $stock?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php if (!$islogin2) { ?>
            <marquee style="margin:1em;">
                 <a href="./user/login.php?back=index" style="color: salmon">您当前未登录，点击此段文字进行登陆后再下单，售后更加快捷方便哦~</a>
            </marquee>
			<?php } ?>
            <div class="content_friends">
                <div class="top_tit">
                    商品说明
                </div>
                <div class="hd_intro" style="word-break: break-all;"><?php echo $tool['desc']?></div>
            </div>
            <br/>
			<?php if($tool['shopimg']){?>
            <div style="margin: 0 10px 10px;">
                <section class="_135editor" data-tools="135编辑器" data-id="94815">
                    <section style="text-align: center;" class="">
                        <section style="display: inline-block;" class="">
                            <section class="135brush" data-brushtype="text" style="clear:both;margin:-18px 0px;text-align: center;color:#333;border-radius: 6px;padding:0px 1.5em;letter-spacing: 1.5px;">
                                <span style="color: #f79646;"><strong><span style="font-size: 15px;">产品宣传图</span></strong></span>
                            </section>
                        </section>
                    </section>
                </section>
                <section class="_135editor" data-tools="135编辑器" data-id="95391"></section>
                <p>
                    <br>
                </p>
                
                <section class="_135editor" data-tools="135编辑器" data-id="85548">
                    <section style="margin: 5px 0px 10px; line-height: 24px; color: #6c653b; border-color: #e0dcc6; background-color: #e0dcc6;">
                        <section style=" margin: 0px; clear: both; box-sizing: border-box; padding: 0px; color: inherit;">
                            <section style="color: inherit; float: right; width: 10px; margin-bottom: -3px; border-color: #e0dcc6; margin-right: 10px; height: 10px !important; background-color: #fefefe;"></section>
                            <section style="color: inherit; float: left; width: 10px; margin-bottom: -3px; border-color: #e0dcc6; margin-left: 10px; height: 10px !important; background-color: #fefefe;"></section>
                        </section>
                        <section style="margin: 0px; padding: 10px 0px 0px; color: inherit; border-color: #e0dcc6;" class="">
                            <section style="color: inherit; float: right; width: 10px; margin-bottom: -10px; border-color: #e0dcc6; height: 10px !important; background-color: #fefefe;"></section>
                            <section style="color: inherit; float: left; width: 10px; margin-bottom: -10px; border-color: #e0dcc6; height: 10px !important; background-color: #fefefe;"></section>
                            <p style="text-align:center;color: inherit; padding: 0px 10px; border-color: #e0dcc6; line-height: 0.5em;" align="center">
                                <img class="lazy" alt="<?php echo $tool['name'];?>" src="<?php echo $tool['shopimg']?$tool['shopimg']:'assets/store/picture/error_img.png';?>" border="0" opacity="" style="margin: 0px; height: auto !important; width: 100% !important;" width="100%" height="auto" mapurl="" title="<?php echo $tool['name'];?>" data-width="100%" data-ratio="1.8285714285714285" data-op="change" data-w="1050">
                            </p>
                        </section>
                        <section style="border: 0px none #e0dcc6; clear: both; box-sizing: border-box; padding: 0px; color: inherit;">
                            <section style="color: inherit; float: left; width: 10px; margin-top: -10px; border-color: #e0dcc6; height: 10px !important; background-color: #fefefe;"></section>
                            <section style="color: inherit; float: right; width: 10px; margin-top: -10px; border-color: #e0dcc6; height: 10px !important; background-color: #fefefe;"></section>
                            <section style="color: inherit; float: right; width: 10px; border-color: #e0dcc6; margin-right: 10px; height: 10px !important; background-color: #fefefe;"></section>
                            <section style="color: inherit; text-align: left; width: 10px; border-color: #e0dcc6; margin-left: 10px; height: 10px !important; background-color: #fefefe;"></section>
                        </section>
                </section>
                 </section>
            </div>
			<?php }?>

            <div class="swiper-container" id="swiper"
                 style="display: none;width: 94%;max-height: 42vh;box-shadow: 1px 1px 8px #eee;border-radius: 0.3em">
                <div class="swiper-wrapper" id="picture"></div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>

            <div class="assemble-footer footer">
            <a href="javascript:;" onclick="goback();" class="left"
                   style="width: 25% !important;border-left: solid 1px #eee">
                    <div class="wid all">
                        <span class="icon icon-left top"></span>
                        <p>返回</p>
                    </div>
                </a>
                <?php
                    if($tool['active'] == 0){
                        $msg = '<span class="pay_price">'.$price.'元</span><p id="submit_buys">商品已下架</p>';
                        $msg_style = "red";
                        $msg_fun = "layer.alert('当前商品已下架，停止下单！');";
                    }else if($tool['close'] == 1){
                        $msg = '<span class="pay_price">'.$price.'元</span><p id="submit_buys">商品维护中</p>';
                        $msg_style = "red";
                        $msg_fun = "layer.alert('当前商品缺货或维护中，停止下单！');";
                    }else if($isfaka == 1 && $count==0){
                        $msg = '<span class="pay_price">'.$price.'元</span><p id="submit_buys">商品缺货中</p>';
                        $msg_style = "red";
                        $msg_fun = "layer.alert('当前商品已售空，请联系站长补货！');";
                    }else{
                        $msg = '<span class="pay_price">'.$price.'元</span><p id="submit_buys">购买商品</p>';
                        $msg_style = "#528ff0";
                        $msg_fun = "$('#paymentmethod').show();";

                    }
                
                ?>
                <a class="middle" href="javascript:<?php echo $msg_fun; ?>"
                   style="background-color: <?php echo $msg_style; ?> !important;width: 50%">
                    <div class="wid y_buy " style="background-color: <?php echo $msg_style; ?>">
                        <?php  echo $msg; ?>
                    </div>
                </a>
                <a href="javascript:share_shop()" class="left"
                   style="width: 25% !important;border-left: solid 1px #eee">
                    <div class="wid all">
                        <span class="icon icon-share top"></span>
                        <p>分享</p>
                    </div>
                </a>

            </div>
        </div>
    </div>
</div>
</div>
<div id="form1">

<input type="hidden" id="tid" value="<?php echo $tid?>" cid="<?php echo $tool['cid']?>" price="<?php echo $price;?>" alert="<?php echo escape($tool['alert'])?>" inputname="<?php echo $tool['input']?>" inputsname="<?php echo $tool['inputs']?>" multi="<?php echo $tool['multi']?>" isfaka="<?php echo $isfaka?>" count="<?php echo $tool['value']?>" close="<?php echo $tool['close']?>" prices="<?php echo $tool['prices']?>" max="<?php echo $tool['max']?>" min="<?php echo $tool['min']?>">
<input type="hidden" id="leftcount" value="<?php echo $isfaka?$count:100?>">

    <div id="paymentmethod" class="common-mask" style="display:none;max-width: 650px">
        <div class="payment-method" style="position: absolute;max-height:70vh;">
            <div class="title" id="gid" data-tid="<?php echo $_GET['gid'] ?>" style="color: salmon;font-size: 1.3em;">
                下单信息确认
                <span class="close" onclick="$('#paymentmethod').hide()"></span>
            </div>
            <hr>
            <div style="height: 52vh;overflow:hidden;overflow-y: auto">
                <div class="layui-form-item">
                    <label class="layui-form-label" style="width: 100%;text-align: left;padding:0">商品价格</label>
                    <div class="layui-input-">
                        <input type="text" id="need" disabled class="layui-input" value="<?php echo $price?> 元">
                    </div>
                </div>
                <div id="inputsname"></div>
                <div class="layui-form-item" <?php echo $tool['multi']==0?'style="display: none"':null;?>>
                    <label class="layui-form-label" style="width: 100%;text-align: left;padding:0">下单份数：<?php if($isfaka == 1){echo "<span style='float:right;'>剩余：<font color='red'>".$count."</font>份</span>";} ?></label>
                    <div class="input-group">
                        <div class="input-group-addon" id="num_min" style="background-color: #FBFBFB;border-radius: 2px 0 0 2px;cursor: pointer;">-</div>
                        <input id="num" name="num" class="layui-input" type="number" value="1" placeholder="请输入购买数量" required min="1" <?php echo $isfaka==1?'max="'.$count.'"':null?>>
                        <div class="input-group-addon" id="num_add" style="background-color: #FBFBFB;border-radius: 2px 0 0 2px;cursor: pointer;">+</div>
                    </div>
                </div>
                <div id="matching_msg"
                     style="display:none;box-shadow: -3px 3px 16px #eee;margin-bottom: 0em;padding: 1em;text-align: center"></div>
            </div>
            <div class="form-group" style="text-align: center">
                <button type="button"
                        style="margin:auto;text-align: center;    line-height: 1.75rem;margin-top: 0.4rem;    background: linear-gradient(to right, #ff6a80, #ff3c77, #fa9c7b);    color: white;    letter-spacing: 1px;    font-size: 0.7rem;    border: none;    outline: none;    width: 48%;    height: 1.75rem; <?php if($conf['shoppingcart']==0){?>display:none;<?php }?>;"
                        id="submit_cart_shop" class="btn btn-primary btn-block">
                    <span class="icon icon-cart2"></span> 加入购物车<span id="cart_sum"></span>
                </button>
                <button type="button"
                        style="margin:auto;text-align: center;    line-height: 1.75rem;margin-top: 0.4rem;    background: linear-gradient(to right, #7f7fd5, #86a8e7, #91eae4);    color: white;    letter-spacing: 1px;    font-size: 0.7rem;    border: none;    outline: none;    width: 48%;    height: 1.75rem;;"
                        id="submit_buy" class="btn btn-primary btn-block">
                    立即购买
                </button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnpublic ?>Swiper/4.5.1/js/swiper.min.js"></script>
<script src="<?php echo $cdnpublic ?>limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
<script>
$(".show_daili_price").on("click",function(){
     layer.open({
          type: 1,
          title: "商品【<?php echo $tool['name']?>】代理价格",
          btnAlign: 'c',
          content: $('#show_daili_price_html'),
          <?php if($islogin2 && $userrow['power'] == 2) {?>
          btn: ['关闭'],
          <?php }else{ ?>
          btn: ['提升级别', '关闭'],
          yes: function(index, layero){
             window.location.href = "./user/regsite.php";
          },
          <?php } ?>
     });
});
</script>
<div id="show_daili_price_html" style="display:none;">
    <div class="price" style="text-align:center;">
        <hr>
        <p class="pay_prices" id="level"><font color="#48d1cc">普通用户售价</font>：<span class="pay_price"><?php echo $price_pt?>元</span></p>
        <p class="pay_prices" id="level"><font color="red">普通代理售价</font>：<span class="pay_price"><?php echo $price_1?>元</span></p>
        <p class="pay_prices" id="level"><font color="orange">高级代理售价</font>：<span class="pay_price"><?php echo $price_2?>元</span></p>
        <hr>
        <p class="pay_prices" id="level"><font color="blue">您当前所在级别</font>：<span class="pay_price"><?php echo $level?></span></p>
    </div>
</div>
<script type="text/javascript">
layer.photos({
  photos: '#layer-photos-demo'
  ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
});
layer.tips('点击图片查看大图', '#layer-photos-demo', {
  tips: [3, '#78BA32']
});
var hashsalt=<?php echo $addsalt_js?>;
function goback()
{
    document.referrer === '' ?window.location.href = './' :window.history.go(-1);
}
</script>
<script src="assets/store/js/main.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>