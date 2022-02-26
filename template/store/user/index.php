<?php
if (!defined('IN_CRONLITE')) die();
@header('Content-Type: text/html; charset=UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"/>
    <title>会员中心-<?php echo $conf['sitename']; ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>/assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>/assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>/assets/store/css/iconfont.css">
    <link href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo $cdnpublic?>toastr.js/latest/css/toastr.min.css">
    <script src="<?php echo $cdnpublic ?>jquery/3.4.1/jquery.min.js"></script>
    <script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
</head>
<style>
body {
    width: 100%;
    max-width: 650px;
    margin: auto;
    background: #f3f3f3;
    line-height: 24px;
    font: 14px Helvetica Neue,Helvetica,PingFang SC,Tahoma,Arial,sans-serif;
}
.label{
    color: unset;
    line-height: 1.8;
}
.account-main{
    height: 100% !important;
}
a {
    text-decoration:none;
}
a:hover{
    text-decoration:none;
}
</style>
<body>
<div id="body">


<div class="fui-page  fui-page-current" style="max-width: 650px;left: auto;">
    <div class="fui-header">
        <div class="fui-header-left">
            <a class="back" onclick="goback();"></a>
        </div>
        <div class="title">会员中心</div>
        <div class="fui-header-right"></div>
    </div>

    <div class="fui-content member-page navbar" style="">
    <?php 
if($islogin2==1){
    if($userrow['status']==0){
        sysmsg('你的账号已被封禁！',true);exit;
    }elseif($userrow['power']>0 && $conf['fenzhan_expiry']>0 && $userrow['endtime']<$date){
        //sysmsg('你的账号已到期，请联系管理员续费！',true);exit;
        echo '<script>layer.msg("您的分站已到期，请联系管理员续费！")</script>';
    }
}else{
    exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
?>
        <div style="overflow: hidden;height: 9rem;position: relative;background: #fff">
            <div class="headinfo" style="z-index:100;border: none;">
                <a class="setbtn" href="uset.php?mod=user"><i class="icon icon-shezhi"></i></a>
                <div class="child">
                    <a href="javascript:;">
                        <div class="title">余额</div>
                        <div class="num"><?php echo $userrow['rmb']?></div>
                    </a>
                    <a href="recharge.php">
                        <div class="btn">充值</div>
                    </a>              
                </div>
                <div class="child userinfo">
                    <a href="javascript:;" style="color: white;">
                        <div class="face"><img src="<?php echo $faceimg ?>"></div>
                        <div class="name"><?php echo $nickname?></div>
                        <div class="uid">UID:<?php echo $userrow['zid']?></div>
                    </a>
                    <div class="level">
                        <?php if($userrow['power'] == 2){ ?>
                            <font color="orange">[高级代理]</font>
                        <?php }else if($userrow['power'] == 1){ ?>
                            <font color="orange">[普通代理]</font>
                        <?php }else{ ?>
                            [普通会员]
                        <?php } ?>
                        
                    </div>
                </div>
                <div class="child">
                    <a href="record.php">
                        <div class="title">今日收益</div>
                        <div class="num" id="income_today"></div>
                    </a>
                    <?php if($userrow['power'] > 0){ ?>
                    <a href="tixian.php" class="external">
                        <div class="btn">提现</div>
                    </a>
                    <?php }else{ ?>
                    <a href="javascript:layer.msg('只有开通分站才可进行提现');" class="external">
                        <div class="btn">提现</div>
                    </a>
                    <?php } ?>
                </div>
            </div>

            <div class="member_header" style="background: #ff5555;">
                
            </div>
            <img class="cover-img" src="../assets/store/picture/cover.png">
        </div>

        <div class="fui-cell-group fui-cell-click" style="margin-top: 0">
            <a class="fui-cell external" href="../?mod=query">
                <div class="fui-cell-icon"><i class="icon icon-dingdan1"></i></div>
                <div class="fui-cell-text">我的订单</div>
                <div class="fui-cell-remark" style="font-size: 0.65rem;">查看全部订单</div>
            </a>
            <div class="fui-icon-group selecter col-5">
                <a class="fui-icon-col external" href="../?mod=query&status=1">
                    <div class="icon icon-green radius">
                        <i class="icon icon-daifukuan1"></i>
                    </div>
                    <div class="text">已完成</div>
                </a>
                <a class="fui-icon-col external" href="../?mod=query&status=0">
                    <div class="icon icon-orange radius">
                        <i class="icon icon-daifahuo1"></i>
                    </div>
                    <div class="text">待处理</div>
                </a>
                <a class="fui-icon-col external" href="../?mod=query&status=2">
                    <div class="icon icon-blue radius">
                        <i class="icon icon-daishouhuo1"></i>
                    </div>
                    <div class="text">处理中</div>
                </a>
                <a class="fui-icon-col external" href="../?mod=query&status=4">
                    <div class="icon icon-pink radius"><i class="icon icon-daituikuan2"></i></div>
                    <div class="text">已退单</div>
                </a>
                <a class="fui-icon-col external before" href="../?mod=query&status=3">
                    <div class="icon icon-pink radius"><i class="icon icon-xiangmuzhouqi" style="color: #ff6a54;"></i></div>
                    <div class="text">异常</div>
                </a>
            </div>
    </div>

    <div class="fui-cell-group fui-cell-click">
    <?php if($userrow['power']>0){?>
            <div class="fui-according-group " style="display: block;margin-top:unset;">
                <div class="fui-according expanded">
                    <div class="fui-according-header fui-cell">
                        <div class="fui-cell-icon"><i class="fa fa-codepen"></i></div>
                        <span class="text">网站管理</span>
                        <span class="remark"></span>
                    </div>
                    <div class="fui-according-content" style="display: block;">
                        <div class="fui-icon-group selecter col-<?php if($userrow['power']==2){echo '5';}else{echo '3';} ?>">
                            <a class="fui-icon-col external" href="siteinfo.php">
                                <div class="icon icon-green radius">
                                    <i class="fa fa-globe" style="color: #ff6a54;"></i>
                                </div>
                                <div class="text">站点信息</div>
                            </a>
                            <a class="fui-icon-col external" href="classlist.php">
                                <div class="icon icon-orange radius">
                                    <i class="icon icon-list"></i>
                                </div>
                                <div class="text">分类管理</div>
                            </a>
                            <a class="fui-icon-col external" href="shoplist.php">
                                <div class="icon icon-blue radius">
                                    <i class="icon icon-goods"></i>
                                </div>
                                <div class="text">商品管理</div>
                            </a>
                            <?php if($userrow['power']==2){?>
                            <a class="fui-icon-col external" href="sitelist.php">
                                <div class="icon icon-pink radius"><i class="icon icon-fenxiao"></i></div>
                                <div class="text">分站列表</div>
                            </a>
                            <a class="fui-icon-col external" href="userlist.php">
                                <div class="icon icon-pink radius"><i class="fa fa-users"></i></div>
                                <div class="text">用户列表</div>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <a class="fui-cell external" href="tuiguang.php">
                    <div class="fui-cell-icon"><i class="fa fa-share-alt"></i></div>
                    <div class="fui-cell-text"><p>推广文案</p></div>
                    <div class="fui-cell-remark"></div>
            </a>
			<?php if($conf['appcreate_open']==1){?>
			<a class="fui-cell external" href="appCreate.php">
                    <div class="fui-cell-icon"><i class="fa fa-android"></i></div>
                    <div class="fui-cell-text"><p>APP生成</p></div>
                    <div class="fui-cell-remark"></div>
            </a>
			<?php }?>
            <?php if( $conf['fenzhan_rank']==1){?>
                <a class="fui-cell external" href="rank.php">
                    <div class="fui-cell-icon"><i class="fa fa-line-chart"></i></div>
                    <div class="fui-cell-text"><p>分站排行</p></div>
                    <div class="fui-cell-remark"></div>
                </a>
            <?php }?>
			<a class="fui-cell external" href="list.php">
                    <div class="fui-cell-icon"><i class="fa fa-list"></i></div>
                    <div class="fui-cell-text"><p>订单管理</p></div>
                    <div class="fui-cell-remark"></div>
            </a>
    <?php }else{ ?>
        <a class="fui-cell" href="regsite.php">
            <div class="fui-cell-icon"><i class="fa fa-diamond"></i></div>
                <div class="fui-cell-text"><p>申请开通分站</p></div>
            <div class="fui-cell-remark"></div>
        </a>
    <?php } ?>
		<?php if($conf['qiandao_reward']){?>
		<a class="fui-cell external" href="qiandao.php">
				<div class="fui-cell-icon"><i class="fa fa-check-square"></i></div>
				<div class="fui-cell-text"><p>每日签到</p></div>
				<div class="fui-cell-remark"></div>
		</a>
		<?php }?>
        <a class="fui-cell external" href="record.php">
                <div class="fui-cell-icon"><i class="fa fa-credit-card"></i></div>
                <div class="fui-cell-text"><p>收支明细</p></div>
                <div class="fui-cell-remark"></div>
        </a>
    </div>
<!--     <div class="fui-according-group" id="container" style="display: block;">
            <div class="fui-according expanded">
                <div class="fui-according-header">
                    <span class="text">关于</span>
                    <span class="remark"></span>
                </div>
                <div class="fui-according-content" style="display: block;">
                    <div class="content-block"><p><span style="font-size:16px;font-family:黑体">12</span></p></div>
                </div>
            </div>
            </div> -->
    

    <div class="fui-cell-group fui-cell-click">
        <a class="fui-cell" href="message.php">
            <div class="fui-cell-icon"><i class="icon icon-notice"></i></div>
            <div class="fui-cell-text"><p>消息通知</p></div>
            <div class="fui-cell-remark" >
                <span class="badge tiaoshu_cont" style="display:none;"></span>
            </div>
        </a>
        <?php if($conf['workorder_open']==1){?>
        <a class="fui-cell" href="workorder.php">
                <div class="fui-cell-icon"><i class="fa fa-check-square-o"></i></div>
                <div class="fui-cell-text"><p>我的工单</p></div>
                <div class="fui-cell-remark">
                    <span class="badge work_cont" style="display:none;"></span>    
                </div>
        </a>
        <?php } ?>
        <?php if($userrow['power']>0){?>
        <a class="fui-cell" href="faq.php">
                <div class="fui-cell-icon"><i class="fa fa-exclamation-circle"></i></div>
                <div class="fui-cell-text"><p>常见问题</p></div>
                <div class="fui-cell-remark">           
                </div>
        </a>
        <?php } ?>
    </div>

    <div class="fui-cell-group fui-cell-click">
            <div class="fui-according-group " style="display: block;margin-top:unset;">
                <div class="fui-according">
                    <div class="fui-according-header fui-cell">
                        <div class="fui-cell-icon"><i class="fa fa-cogs"></i></div>
                        <span class="text">系统设置</span>
                        <span class="remark"></span>
                    </div>
                    <div class="fui-according-content" style="display: none;">
                        <div class="fui-icon-group selecter col-<?php if($userrow['power']>0){echo '3';}else{echo '1';}?>">
                            <a class="fui-icon-col external" href="uset.php?mod=user" >
                                <div class="icon icon-green radius">
                                    <i class="fa fa-cog"></i>
                                </div>
                                <div class="text">用户资料设置</div>
                            </a>
                            <?php if($userrow['power']>0){?>
                            <a class="fui-icon-col external" href="uset.php?mod=skimg">
                                <div class="icon icon-orange radius">
                                    <i class="icon icon-alipay"></i>
                                </div>
                                <div class="text">收款图设置</div>
                            </a>
                            <a class="fui-icon-col external" href="uset.php?mod=site">
                                <div class="icon icon-orange radius">
                                    <i class="fa fa-edit"></i>
                                </div>
                                <div class="text">网站信息设置</div>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
    </div>      
                
<!--         <div class="fui-cell-group fui-cell-click">
            <a class="fui-cell" href="">
                <div class="fui-cell-icon"><i class="icon icon-cart"></i></div>
                <div class="fui-cell-text"><p>我的购物车</p></div>
                <div class="fui-cell-remark"></div>
            </a>
            <a class="fui-cell external" href="">
                <div class="fui-cell-icon"><i class="icon icon-daituikuan2"></i></div>
                <div class="fui-cell-text"><p>收益明细</p></div>
                <div class="fui-cell-remark"></div>
            </a>
        </div> -->

        <div class="fui-cell-group fui-cell-click transparent">
<!--             <a class="fui-cell external changepwd" href="">
                <div class="fui-cell-text" style="text-align: center;"><p>修改密码</p></div>
            </a> -->
            <a class="fui-cell external btn-logout" href="login.php?logout">
                <div class="fui-cell-text" style="text-align: center;"><p>退出登录</p></div>
            </a>
        </div>
        <div class="footer" style="width: 100%;margin-top: 0.5rem;margin-bottom: 2.5rem;display: block;float: left;">
            <p style="text-align: center;"><span style="color: rgb(37, 36, 36); font-family: 微软雅黑, " microsoft="" font-size:="" text-align:="" background-color:="">© 版权所有 <?php echo $conf['sitename'];  ?></span></p>
        </div>
</div>

    <div class="fui-navbar" style="z-index: 100000;max-width: 650px;">
        <a href="../" class="nav-item  "> <span class="icon icon-home"></span> <span class="label">首页</span> </a>
        <a href="../?mod=query" class="nav-item "> <span class="icon icon-dingdan1"></span> <span class="label">订单</span> </a>
		<a href="../?mod=cart" class="nav-item " <?php if($conf['shoppingcart']==0){?>style="display:none"<?php }?>> <span class="icon icon-cart2"></span> <span class="label">购物车</span> </a>
        <a href="../?mod=kf" class="nav-item "> <span class=" icon icon-service1"></span> <span class="label">客服</span> </a>
        <a href="./" class="nav-item active"> <span class="icon icon-person2"></span> <span class="label">会员中心</span> </a>
    </div>
</div>

<script src="<?php echo $cdnpublic?>toastr.js/latest/toastr.min.js"></script>
<script src="../assets/store/js/foxui.js"></script>
<?php  if(substr($userrow['user'],0,3)=='qq_'){ ?>
<script>
toastr.warning('<a href="uset.php?mod=user">系统检测到您为QQ快捷登陆<br/>为确保您的账号后续能够正常使用建议设置登录账号！</a>', '账号安全提醒');
</script>
<?php } ?>
<?php  if($userrow['rmb']>4){ ?>
<?php if(strlen($userrow['pwd'])<6 || is_numeric($userrow['pwd']) && strlen($userrow['pwd'])<=10 || $userrow['pwd']===$userrow['qq']){ ?>
<script>
toastr.error('<a href="uset.php?mod=user">你的密码过于简单，请不要使用较短的纯数字或自己的QQ号当做密码，以免造成资金损失！</a>', '账号安全提醒');
</script>
<?php }else if($userrow['user']===$userrow['pwd']){ ?>
<script>
toastr.error('<a href="uset.php?mod=user">你的用户名与密码相同，极易被黑客破解，请及时修改密码</a>', '账号安全提醒');
</script>
<?php } ?>
<?php } ?>
<script>

function goback()
{
        if(window.document.referrer==""||window.document.referrer==window.location.href){  
        window.location.href="/";  
    }else{  
        window.location.href=window.document.referrer;  
    } 
    // document.referrer === '' ?window.location.href = '/' :window.history.go(-1);
}
$(document).ready(function(){
	$.ajax({
		type : "GET",
		url : "ajax_user.php?act=msg",
		dataType : 'json',
		async: true,
		success : function(data) {
			if(data.code==0){
				if(data.count>0){
					$(".tiaoshu_cont").text(data.count);
					$(".tiaoshu_cont").show();

				}
				if(data.count2>0){
					$(".work_cont").text(data.count2);
					$(".work_cont").show();
				}
				$("#income_today").html(data.income_today);
			}
		}
	});
});
</script>
</body>
</html>