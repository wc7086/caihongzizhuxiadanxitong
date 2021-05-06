<?php if (!defined('IN_CRONLITE')) die();?>
<div class="footer" style="width: 100%;margin-top: 0.5rem;margin-bottom: 2.5rem;display: block;float: left;">
            <p style="text-align: center;"><span style="color: rgb(37, 36, 36); font-family: 微软雅黑, " microsoft="" font-size:="" text-align:="" background-color:="">© 版权所有 <?php echo $conf['sitename'];  ?></span></p>
        </div>
        </div>
    </div>
    <div class="fui-navbar" style="z-index: 100;max-width: 650px;">
        <a href="../" class="nav-item  "> <span class="icon icon-home"></span> <span class="label">首页</span> </a>
        <a href="../?mod=query" class="nav-item "> <span class="icon icon-dingdan1"></span> <span class="label">订单</span> </a>
		<a href="../?mod=cart" class="nav-item " <?php if($conf['shoppingcart']==0){?>style="display:none"<?php }?>> <span class="icon icon-cart2"></span> <span class="label">购物车</span> </a>
        <a href="../?mod=kf" class="nav-item "> <span class=" icon icon-service1"></span> <span class="label">客服</span> </a>
        <a href="./" class="nav-item active"> <span class="icon icon-person2"></span> <span class="label">会员中心</span> </a>
    </div>
</div>
<script>
function goback()
{
    if(window.document.referrer==""||window.document.referrer==window.location.href){  
        window.location.href="/";  
    }else{  
        window.location.href=window.document.referrer;  
    }
}
</script>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script src="<?php echo $cdnserver?>assets/user/js/app.js"></script>