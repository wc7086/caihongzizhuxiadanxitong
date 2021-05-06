<?php
/**
 * 快捷登录
**/
$is_defend=true;
include("../includes/common.php");
if($_GET['code'] && $_GET['type']){
	if(!$conf['login_qq'])sysmsg('当前站点未开启QQ快捷登录');
	if($_GET['state'] != $_SESSION['Oauth_state']){
		sysmsg("<h2>The state does not match. You may be a victim of CSRF.</h2>");
	}
	$Oauth = new \lib\Oauth($conf['login_apiurl'], $conf['login_appid'], $conf['login_appkey']);
	$arr = $Oauth->callback();
	if(isset($arr['code']) && $arr['code']==0){
		$openid=$arr['social_uid'];
		$access_token=$arr['access_token'];
		$nickname=$arr['nickname'];
		$faceimg=$arr['faceimg'];
	}elseif(isset($arr['code'])){
		sysmsg('<h3>error:</h3>'.$arr['errcode'].'<h3>msg  :</h3>'.$arr['msg']);
	}else{
		sysmsg('获取登录数据失败');
	}

	$row=$DB->getRow("SELECT * FROM pre_site WHERE qq_openid='{$openid}' limit 1");
	if($row){
		$user=$row['user'];
		$pass=$row['pwd'];
		if($islogin2==1){
			@header('Content-Type: text/html; charset=UTF-8');
			exit("<script language='javascript'>alert('当前QQ已绑定用户:{$user}，请勿重复绑定！');window.location.href='./uset.php?mod=user';</script>");
		}
		$session=md5($user.$pass.$password_hash);
		$token=authcode("{$row['zid']}\t{$session}", 'ENCODE', SYS_KEY);
		ob_clean();
		setcookie("user_token", $token, time() + 604800, '/');
		log_result('分站登录', 'User:'.$user.' IP:'.$clientip, null, 1);
		$DB->exec("UPDATE pre_site SET lasttime='$date' WHERE zid='{$row['zid']}'");
		if(isset($_SESSION['Oauth_back']) && $_SESSION['Oauth_back']=='index')$redirect = '../';
		elseif(isset($_SESSION['Oauth_back']) && $_SESSION['Oauth_back']=='recharge')$redirect = './recharge.php';
		elseif(isset($_SESSION['Oauth_back']) && $_SESSION['Oauth_back']=='workorder')$redirect = './workorder.php';
		else $redirect = './';
		exit("<script language='javascript'>window.location.href='{$redirect}';</script>");
	}elseif($islogin2==1){
		$sds=$DB->exec("update `pre_site` set `qq_openid` ='$openid' where `zid`='{$userrow['zid']}'");
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('已成功绑定QQ！');window.location.href='./uset.php?mod=user';</script>");
	}else{
		$_SESSION['Oauth_qq_openid']=$openid;
		$_SESSION['Oauth_qq_token']=$access_token;
		$_SESSION['Oauth_qq_nickname']=$nickname;
		$_SESSION['Oauth_qq_faceimg']=$faceimg;
		@header('Content-Type: text/html; charset=UTF-8');
		if($_SESSION['Oauth_back'])$addstr = '?back='.$_SESSION['Oauth_back'];
		exit("<script language='javascript'>window.location.href='./connect.php{$addstr}';</script>");
	}
}elseif($islogin2==1){
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./';</script>");
}elseif(!$_SESSION['Oauth_qq_openid'] || !$_SESSION['Oauth_qq_token']){
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}

$title='QQ快捷登录';
include './head2.php';

if($_SESSION['Oauth_qq_faceimg']){
	$faceimg = $_SESSION['Oauth_qq_faceimg'];
}else{
	$faceimg = '//q4.qlogo.cn/headimg_dl?dst_uin='.$conf['kfqq'].'&spec=100';
}

if($_GET['act'] == 'new'){
	$subtitle = '请完善以下信息';
}elseif($_GET['act'] == 'bind'){
	$subtitle = '绑定已有账号';
}else{
	$subtitle = 'QQ快捷登录';
}

if($_SESSION['Oauth_back'])$addstr = '&back='.$_SESSION['Oauth_back'];
?>
<?php if($background_image){?>
<img src="<?php echo $background_image;?>" alt="Full Background" class="full-bg full-bg-bottom animation-pulseSlow" ondragstart="return false;" oncontextmenu="return false;">
<?php }?>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-4 center-block " style="float: none;">
  <br /><br /><br />
    <div class="widget">
    <div class="widget-content themed-background-flat text-center"  style="background-image: url(<?php echo $cdnserver?>assets/simple/img/userbg.jpg);background-size: 100% 100%;" >
<img class="img-circle"src="<?php echo $faceimg?>" alt="Avatar" alt="avatar" height="60" width="60" />
<p></p>
    </div>

    <div class="block">
        <div class="block-title">
            <div class="block-options pull-right">
            <a href="../" class="btn btn-effect-ripple btn-default toggle-bordered enable-tooltip">返回首页</a>
            </div>
            <h2><i class="fa fa-user"></i>&nbsp;&nbsp;<b><?php echo $subtitle?></b></h2>
        </div>
          <form>
		    <div id="loginframe">
<?php if($_GET['act'] == 'bind'){?>
          <form>
            <div class="input-group"><div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
              <input type="text" name="user" value="" class="form-control" required="required" placeholder="用户名"/>
            </div><br/>
            <div class="input-group"><div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
              <input type="password" name="pass" class="form-control" required="required" placeholder="密码"/>
            </div><br/>
			<?php if($conf['captcha_open_login']==1 && $conf['captcha_open']>=1){?>
			<input type="hidden" name="captcha_type" value="<?php echo $conf['captcha_open']?>"/>
			<?php if($conf['captcha_open']>=2){?><input type="hidden" name="appid" value="<?php echo $conf['captcha_id']?>"/><?php }?>
			<div id="captcha" style="margin: auto;"><div id="captcha_text">
                正在加载验证码
            </div>
            <div id="captcha_wait">
                <div class="loading">
                    <div class="loading-dot"></div>
                    <div class="loading-dot"></div>
                    <div class="loading-dot"></div>
                    <div class="loading-dot"></div>
                </div>
            </div></div>
			<div id="captchaform"></div>
			<br/>
			<?php }?>
            <div class="form-group">
			  <input type="button" value="立即绑定账号" id="submit_login" class="btn btn-primary btn-block"/>
            </div>
			<hr>
			<div class="form-group">
			<a href="javascript:history.back(-1)" class="btn btn-danger btn-rounded"><i class="fa fa-reply"></i>&nbsp;返回</a>
			<a href="findpwd.php" class="btn btn-info btn-rounded" style="float:right;"><i class="fa fa-unlock"></i>&nbsp;找回密码</a>
			</div>
          </form>
<?php }else{?>
			<hr>
            <p><a href="javascript:quickreg()" class="btn btn-info btn-block"><i class="fa fa-user-plus"></i>&nbsp;我是新用户，直接登录</a></p>
			<hr>
			<p><a href="connect.php?act=bind<?php echo $addstr?>" class="btn btn-default btn-block"><i class="fa fa-user-circle-o"></i>&nbsp;我是老用户，绑定已有账号</a></p>
			</div>
			<hr>
<?php }?>
          </form>
    </div>
  </div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="../assets/js/login.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>