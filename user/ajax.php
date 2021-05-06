<?php
include("../includes/common.php");

$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

if(!checkRefererHost())exit('{"code":403}');

switch($act){
case 'login':
	$user=daddslashes($_POST['user']);
	$pass=daddslashes($_POST['pass']);
	if(!$user || !$pass){
		exit('{"code":-1,"msg":"用户名或密码不能为空"}');
	}
	if($conf['captcha_open_login']==1 && $conf['captcha_open']==1){
		if(isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
			if(!isset($_SESSION['gtserver']))exit('{"code":-1,"msg":"验证加载失败"}');
			$GtSdk = new \lib\GeetestLib($conf['captcha_id'], $conf['captcha_key']);

			$data = array(
				'user_id' => $cookiesid,
				'client_type' => "web",
				'ip_address' => $clientip
			);

			if ($_SESSION['gtserver'] == 1) {   //服务器正常
				$result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
				if ($result) {
					//echo '{"status":"success"}';
				} else{
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}else{  //服务器宕机,走failback模式
				if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
					//echo '{"status":"success"}';
				}else{
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}
		}else{
			exit('{"code":2,"type":1,"msg":"请先完成验证"}');
		}
	}elseif($conf['captcha_open_login']==1 && $conf['captcha_open']==2){
		if(isset($_POST['token'])){
			$client = new \lib\CaptchaClient($conf['captcha_id'], $conf['captcha_key']);
			$client->setTimeOut(2);
			$response = $client->verifyToken($_POST['token']);
			if($response->result){
				/**token验证通过，继续其他流程**/
			}else{
				/**token验证失败**/
				exit('{"code":-1,"msg":"验证失败，请重新验证"}');
			}
		}else{
			exit('{"code":2,"type":2,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
		}
	}elseif($conf['captcha_open_login']==1 && $conf['captcha_open']==3){
		if(isset($_POST['token'])){
			if(vaptcha_verify($conf['captcha_id'], $conf['captcha_key'], $_POST['token'], $clientip)){
				/**token验证通过，继续其他流程**/
			}else{
				/**token验证失败**/
				exit('{"code":-1,"msg":"验证失败，请重新验证"}');
			}
		}else{
			exit('{"code":2,"type":3,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
		}
	}
	$row=$DB->getRow("SELECT zid,user,pwd,status FROM pre_site WHERE user=:user LIMIT 1", [':user'=>$user]);
	if($row && $user===$row['user'] && $pass===$row['pwd']) {
		if($row['status']==0){
			exit('{"code":-1,"msg":"当前账号已被封禁！"}');
		}
		$session=md5($user.$pass.$password_hash);
		$token=authcode("{$row['zid']}\t{$session}", 'ENCODE', SYS_KEY);
		ob_clean();
		setcookie("user_token", $token, time() + 604800, '/');
		log_result('分站登录', 'User:'.$user.' IP:'.$clientip, null, 1);
		if($_SESSION['Oauth_qq_openid'] && $_SESSION['Oauth_qq_token']){
			$DB->exec("UPDATE pre_site SET qq_openid=:qq_openid,lasttime=NOW() WHERE zid=:zid", [':qq_openid'=>$_SESSION['Oauth_qq_openid'], ':zid'=>$row['zid']]);
			unset($_SESSION['Oauth_qq_openid']);
			unset($_SESSION['Oauth_qq_token']);
			unset($_SESSION['Oauth_qq_nickname']);
			unset($_SESSION['Oauth_qq_faceimg']);
			exit('{"code":0,"msg":"绑定QQ快捷登录成功！"}');
		}else{
			$DB->exec("UPDATE pre_site SET lasttime=NOW() WHERE zid=:zid", [':zid'=>$row['zid']]);
			exit('{"code":0,"msg":"登陆用户中心成功！"}');
		}
	}else {
		exit('{"code":-1,"msg":"用户名或密码不正确！"}');
	}
break;
case 'connect':
	if(!$conf['login_qq'])exit('{"code":-1,"msg":"当前站点未开启QQ快捷登录"}');
	$type = isset($_POST['type'])?$_POST['type']:exit('{"code":-1,"msg":"no type"}');
	$back = isset($_POST['back'])?$_POST['back']:null;
	$Oauth = new \lib\Oauth($conf['login_apiurl'], $conf['login_appid'], $conf['login_appkey']);
	$res = $Oauth->login($type);
	if(isset($res['code']) && $res['code']==0){
		$result = ['code'=>0, 'url'=>$res['url']];
		if($back){
			$_SESSION['Oauth_back'] = $back;
		}elseif(isset($_SESSION['Oauth_back'])){
			unset($_SESSION['Oauth_back']);
		}
	}elseif(isset($res['code'])){
		$result = ['code'=>-1, 'msg'=>$res['msg']];
	}else{
		$result = ['code'=>-1, 'msg'=>'快捷登录接口请求失败'];
	}
	exit(json_encode($result));
break;
case 'unbind':
	if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
	if(!$conf['login_qq'])exit('{"code":-1,"msg":"当前站点未开启QQ快捷登录"}');
	$type = isset($_POST['type'])?$_POST['type']:exit('{"code":-1,"msg":"no type"}');
	if($DB->exec("update `pre_site` set `qq_openid` =NULL where `zid`='{$userrow['zid']}'")){
		exit('{"code":0,"msg":"您已成功解绑QQ！"}');
	}else{
		exit('{"code":-1,"msg":"解绑QQ失败！'.$DB->error().'"}');
	}
break;
case 'quickreg':
	if(!$conf['login_qq'])exit('{"code":-1,"msg":"当前站点未开启QQ快捷登录"}');
	if(!$_SESSION['Oauth_qq_openid'] || !$_SESSION['Oauth_qq_token'])exit('{"code":-1,"msg":"请返回重新登录"}');
	if(!$_POST['submit'])exit('{"code":-1,"msg":"access"}');
	$user = 'qq_'.random(8);
	$pwd = $_SESSION['Oauth_qq_token'];
	$openid = $_SESSION['Oauth_qq_openid'];
	$nickname = $_SESSION['Oauth_qq_nickname'];
	if(strlen($nickname)>32) $nickname = mb_strcut($nickname, 0, 32);
	$faceimg = $_SESSION['Oauth_qq_faceimg'];

	$sql="insert into `pre_site` (`upzid`,`power`,`domain`,`domain2`,`user`,`pwd`,`qq_openid`,`nickname`,`faceimg`,`rmb`,`qq`,`sitename`,`keywords`,`description`,`addtime`,`lasttime`,`status`) values (:upzid,0,NULL,NULL,:user,:pwd,:qq_openid,:nickname,:faceimg,'0',NULL,NULL,NULL,NULL,NOW(),NOW(),'1')";
	$data = [':upzid'=>$siterow['zid']?$siterow['zid']:0, ':user'=>$user, ':pwd'=>$pwd, ':qq_openid'=>$openid, ':nickname'=>$nickname, ':faceimg'=>$faceimg];
	if($DB->exec($sql, $data)){
		$zid = $DB->lastInsertId();
		unset($_SESSION['Oauth_qq_openid']);
		unset($_SESSION['Oauth_qq_token']);
		unset($_SESSION['Oauth_qq_nickname']);
		unset($_SESSION['Oauth_qq_faceimg']);
		$DB->exec("UPDATE `pre_orders` SET `userid`='".$zid."' WHERE `userid`='".$cookiesid."'");
		$session=md5($user.$pwd.$password_hash);
		$token=authcode("{$zid}\t{$session}", 'ENCODE', SYS_KEY);
		ob_clean();
		setcookie("user_token", $token, time() + 604800, '/');
		log_result('分站登录', 'User:'.$user.' IP:'.$clientip, null, 1);
		exit('{"code":0,"msg":"注册用户成功","zid":"'.$zid.'"}');
	}else{
		exit('{"code":-1,"msg":"注册用户失败！'.$DB->error().'"}');
	}
break;
case 'checkdomain':
	$qz = daddslashes($_GET['qz']);
	$domain = $qz . '.' . daddslashes($_GET['domain']);
	$srow=$DB->getRow("SELECT zid FROM pre_site WHERE domain=:domain OR domain2=:domain LIMIT 1", [':domain'=>$domain]);
	if($srow)exit('1');
	else exit('0');
break;
case 'checkuser':
	$user = trim($_GET['user']);
	$srow=$DB->getRow("SELECT zid FROM pre_site WHERE user=:user LIMIT 1", [':user'=>$user]);
	if($srow)exit('1');
	else exit('0');
break;
case 'reguser':
	if($islogin2==1)exit('{"code":-1,"msg":"您已登陆！"}');
	elseif($conf['user_open']==0)exit('{"code":-1,"msg":"当前站点未开启用户注册功能！"}');
	$user = trim(htmlspecialchars(strip_tags(daddslashes($_POST['user']))));
	$pwd = trim(htmlspecialchars(strip_tags(daddslashes($_POST['pwd']))));
	$qq = trim(daddslashes($_POST['qq']));
	$hashsalt = isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	$code = isset($_POST['code'])?$_POST['code']:null;
	if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
		exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
	}
	if (!preg_match('/^[a-zA-Z0-9\x7f-\xff]+$/',$user)) {
		exit('{"code":-1,"msg":"用户名只能为英文、数字与汉字！"}');
	} elseif ($DB->getRow("SELECT zid FROM pre_site WHERE user=:user LIMIT 1", [':user'=>$user])) {
		exit('{"code":-1,"msg":"用户名已存在！"}');
	} elseif (strlen($pwd) < 6) {
		exit('{"code":-1,"msg":"密码不能低于6位"}');
	} elseif (strlen($qq) < 5 || !preg_match('/^[0-9]+$/',$qq)) {
		exit('{"code":-1,"msg":"QQ格式不正确！"}');
	} elseif ($pwd == $user) {
		exit('{"code":-1,"msg":"用户名和密码不能相同！"}');
	}
	if($conf['captcha_open']==1){
		if(isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
			if(!isset($_SESSION['gtserver']))exit('{"code":-1,"msg":"验证加载失败"}');
			$GtSdk = new \lib\GeetestLib($conf['captcha_id'], $conf['captcha_key']);

			$data = array(
				'user_id' => $cookiesid,
				'client_type' => "web",
				'ip_address' => $clientip
			);

			if ($_SESSION['gtserver'] == 1) {   //服务器正常
				$result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
				if ($result) {
					//echo '{"status":"success"}';
				} else{
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}else{  //服务器宕机,走failback模式
				if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
					//echo '{"status":"success"}';
				}else{
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}
		}else{
			exit('{"code":2,"type":1,"msg":"请先完成验证"}');
		}
	}elseif($conf['captcha_open']==2){
		if(isset($_POST['token'])){
			$client = new \lib\CaptchaClient($conf['captcha_id'], $conf['captcha_key']);
			$client->setTimeOut(2);
			$response = $client->verifyToken($_POST['token']);
			if($response->result){
				/**token验证通过，继续其他流程**/
			}else{
				/**token验证失败**/
				exit('{"code":-1,"msg":"验证失败，请重新验证"}');
			}
		}else{
			exit('{"code":2,"type":2,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
		}
	}elseif($conf['captcha_open']==3){
		if(isset($_POST['token'])){
			if(vaptcha_verify($conf['captcha_id'], $conf['captcha_key'], $_POST['token'], $clientip)){
				/**token验证通过，继续其他流程**/
			}else{
				/**token验证失败**/
				exit('{"code":-1,"msg":"验证失败，请重新验证"}');
			}
		}else{
			exit('{"code":2,"type":3,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
		}
	}elseif (!$code || strtolower($code) != $_SESSION['vc_code']) {
		unset($_SESSION['vc_code']);
		exit('{"code":2,"msg":"验证码错误！"}');
	}
	$sql="insert into `pre_site` (`upzid`,`power`,`domain`,`domain2`,`user`,`pwd`,`rmb`,`qq`,`sitename`,`keywords`,`description`,`anounce`,`bottom`,`modal`,`addtime`,`lasttime`,`status`) values (:upzid,0,NULL,NULL,:user,:pwd,'0',:qq,NULL,NULL,NULL,NULL,NULL,NULL,NOW(),NOW(),'1')";
	$data = [':upzid'=>$siterow['zid']?$siterow['zid']:0, ':user'=>$user, ':pwd'=>$pwd, ':qq'=>$qq];
	if($DB->exec($sql, $data)){
		$zid = $DB->lastInsertId();
		unset($_SESSION['addsalt']);
		$DB->exec("UPDATE `pre_orders` SET `userid`='".$zid."' WHERE `userid`='".$cookiesid."'");
		$session=md5($user.$pwd.$password_hash);
		$token=authcode("{$zid}\t{$session}", 'ENCODE', SYS_KEY);
		ob_clean();
		setcookie("user_token", $token, time() + 604800, '/');
		log_result('分站登录', 'User:'.$user.' IP:'.$clientip, null, 1);
		exit('{"code":1,"msg":"注册用户成功","zid":"'.$zid.'"}');
	}else{
		exit('{"code":-1,"msg":"注册用户失败！'.$DB->error().'"}');
	}
break;
case 'paysite':
	if($islogin2==1 && $userrow['power']>0)exit('{"code":-1,"msg":"您已开通过分站！"}');
	elseif($conf['fenzhan_buy']==0)exit('{"code":-1,"msg":"当前站点未开启自助开通分站功能！"}');
	if($is_fenzhan == true && $siterow['power']==2){
		if($siterow['ktfz_price']>0)$conf['fenzhan_price']=$siterow['ktfz_price'];
		if($conf['fenzhan_cost2']<=0)$conf['fenzhan_cost2']=$conf['fenzhan_price2'];
		if($siterow['ktfz_price2']>0 && $siterow['ktfz_price2']>=$conf['fenzhan_cost2'])$conf['fenzhan_price2']=$siterow['ktfz_price2'];
	}
	$kind = intval($_POST['kind']);
	$qz = trim(strtolower(daddslashes($_POST['qz'])));
	$domain = trim(strtolower(htmlspecialchars(strip_tags(daddslashes($_POST['domain'])))));
	$user = trim(htmlspecialchars(strip_tags(daddslashes($_POST['user']))));
	$pwd = trim(htmlspecialchars(strip_tags(daddslashes($_POST['pwd']))));
	$name = trim(htmlspecialchars(strip_tags(daddslashes($_POST['name']))));
	$qq = trim(daddslashes($_POST['qq']));
	$hashsalt = isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	$domain = $qz . '.' . $domain;
	if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
		exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
	}
	if ($kind!=0 && $kind!=1 && $kind!=2) {
		exit('{"code":-1,"msg":"分站类型错误！"}');
	} elseif (empty($_POST['domain'])) {
		exit('{"code":-1,"msg":"域名后缀不能为空，请主站站长在后台设置:分站可用域名"}');
	} elseif (strlen($qz) < 2 || strlen($qz) > 10 || !preg_match('/^[a-z0-9\-]+$/',$qz)) {
		exit('{"code":-1,"msg":"域名前缀不合格！"}');
	} elseif (!preg_match('/^[a-zA-Z0-9\_\-\.]+$/',$domain)) {
		exit('{"code":-1,"msg":"域名格式不正确！"}');
	} elseif ($DB->getRow("SELECT zid FROM pre_site WHERE domain=:domain OR domain2=:domain LIMIT 1", [':domain'=>$domain]) || $qz=='www' || $domain==$_SERVER['HTTP_HOST'] || in_array($domain,explode(',',$conf['fenzhan_remain']))) {
		exit('{"code":-1,"msg":"此前缀已被使用！"}');
	}
	if(!$islogin2){
		if (!preg_match('/^[a-zA-Z0-9\x7f-\xff]+$/',$user)) {
			exit('{"code":-1,"msg":"用户名只能为英文、数字与汉字！"}');
		} elseif ($DB->getRow("SELECT zid FROM pre_site WHERE user=:user LIMIT 1", [':user'=>$user])) {
			exit('{"code":-1,"msg":"用户名已存在！"}');
		} elseif (strlen($pwd) < 6) {
			exit('{"code":-1,"msg":"密码不能低于6位"}');
		} elseif (strlen($name) < 2) {
			exit('{"code":-1,"msg":"网站名称太短！"}');
		} elseif (strlen($qq) < 5 || !preg_match('/^[0-9]+$/',$qq)) {
			exit('{"code":-1,"msg":"QQ格式不正确！"}');
		} elseif ($pwd == $user) {
			exit('{"code":-1,"msg":"用户名和密码不能相同！"}');
		}
	}
	$fenzhan_expiry = $conf['fenzhan_expiry']>0?$conf['fenzhan_expiry']:12;
	$endtime = date("Y-m-d H:i:s", strtotime("+ {$fenzhan_expiry} months", time()));
	$trade_no=date("YmdHis").rand(111,999);
	if($kind==2){
		$need=addslashes($conf['fenzhan_price2']);
	}else{
		$need=addslashes($conf['fenzhan_price']);
	}
	if($need==0){
		if($conf['captcha_open_free']==1 && $conf['captcha_open']==1){
			if(isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
				if(!isset($_SESSION['gtserver']))exit('{"code":-1,"msg":"验证加载失败"}');
				$GtSdk = new \lib\GeetestLib($conf['captcha_id'], $conf['captcha_key']);

				$data = array(
					'user_id' => $cookiesid,
					'client_type' => "web",
					'ip_address' => $clientip
				);

				if ($_SESSION['gtserver'] == 1) {   //服务器正常
					$result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
					if ($result) {
						//echo '{"status":"success"}';
					} else{
						exit('{"code":-1,"msg":"验证失败，请重新验证"}');
					}
				}else{  //服务器宕机,走failback模式
					if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
						//echo '{"status":"success"}';
					}else{
						exit('{"code":-1,"msg":"验证失败，请重新验证"}');
					}
				}
			}else{
				exit('{"code":2,"type":1,"msg":"请先完成验证"}');
			}
		}elseif($conf['captcha_open_free']==1 && $conf['captcha_open']==2){
			if(isset($_POST['token'])){
				$client = new \lib\CaptchaClient($conf['captcha_id'], $conf['captcha_key']);
				$client->setTimeOut(2);
				$response = $client->verifyToken($_POST['token']);
				if($response->result){
					/**token验证通过，继续其他流程**/
				}else{
					/**token验证失败**/
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}else{
				exit('{"code":2,"type":2,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
			}
		}elseif($conf['captcha_open_free']==1 && $conf['captcha_open']==3){
			if(isset($_POST['token'])){
				if(vaptcha_verify($conf['captcha_id'], $conf['captcha_key'], $_POST['token'], $clientip)){
					/**token验证通过，继续其他流程**/
				}else{
					/**token验证失败**/
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}else{
				exit('{"code":2,"type":3,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
			}
		}
		$keywords=$conf['keywords'];
		$description=$conf['description'];
		if($islogin2==1){
			$sql="UPDATE `pre_site` SET `power`=:power,`domain`=:domain,`sitename`=:sitename,`title`=:title,`keywords`=:keywords,`description`=:description,`kfqq`=`qq`,`endtime`=:endtime WHERE `zid`=:zid";
			$data = [':power'=>$kind, ':domain'=>$domain, ':sitename'=>$name, ':title'=>$conf['title'], ':keywords'=>$keywords, ':description'=>$description, ':endtime'=>$endtime, ':zid'=>$userrow['zid']];
			$DB->exec($sql, $data);
			$zid=$userrow['zid'];
		}else{
			$sql="INSERT INTO `pre_site` (`upzid`,`power`,`domain`,`domain2`,`user`,`pwd`,`rmb`,`qq`,`sitename`,`title`,`keywords`,`description`,`kfqq`,`addtime`,`endtime`,`status`) VALUES (:upzid, :power, :domain, NULL, :user, :pwd, :rmb, :qq, :sitename, :title, :keywords, :description, :kfqq, NOW(), :endtime, 1)";
			$data = [':upzid'=>$siterow['zid']?$siterow['zid']:0, ':power'=>$kind, ':domain'=>$domain, ':user'=>$user, ':pwd'=>$pwd, ':rmb'=>'0.00', ':qq'=>$qq, ':sitename'=>$name, ':title'=>$conf['title'], ':keywords'=>$keywords, ':description'=>$description, ':kfqq'=>$qq, ':endtime'=>$endtime];
			$DB->exec($sql, $data);
			$zid = $DB->lastInsertId();
		}
		if($zid){
			$_SESSION['newzid']=$zid;
			unset($_SESSION['addsalt']);
			if(!$islogin2)$DB->exec("UPDATE `pre_orders` SET `userid`='".$zid."' WHERE `userid`='".$cookiesid."'");
			$DB->exec("UPDATE `pre_orders` SET `zid`='".$zid."' WHERE `userid`='".$zid."'");
			exit('{"code":1,"msg":"开通分站成功","zid":"'.$zid.'"}');
		}else{
			exit('{"code":-1,"msg":"开通分站失败！'.$DB->error().'"}');
		}
	}else{
		if($islogin2==1){
			$input='update|'.$userrow['zid'].'|'.$kind.'|'.$domain.'|'.$name.'|'.$endtime;
		}else{
			$input='add|'.$kind.'|'.$domain.'|'.$user.'|'.$pwd.'|'.$name.'|'.$qq.'|'.$endtime;
		}
		$sql="INSERT INTO `pre_pay` (`trade_no`,`tid`,`zid`,`input`,`num`,`name`,`money`,`ip`,`userid`,`addtime`,`status`) VALUES (:trade_no, :tid, :zid, :input, :num, :name, :money, :ip, :userid, NOW(), 0)";
		$data = [':trade_no'=>$trade_no, ':tid'=>-2, ':zid'=>$siterow['zid']?$siterow['zid']:1, ':input'=>$input, ':num'=>1, ':name'=>'自助开通分站', ':money'=>$need, ':ip'=>$clientip, ':userid'=>$cookiesid];
		if($DB->exec($sql, $data)){
			unset($_SESSION['addsalt']);
			exit('{"code":0,"msg":"提交订单成功！","trade_no":"'.$trade_no.'","need":"'.$need.'","pay_alipay":"'.$conf['alipay_api'].'","pay_wxpay":"'.$conf['wxpay_api'].'","pay_qqpay":"'.$conf['qqpay_api'].'","pay_rmb":"'.$islogin2.'","user_rmb":"'.$userrow['rmb'].'"}');
		}else{
			exit('{"code":-1,"msg":"提交订单失败！'.$DB->error().'"}');
		}
	}
break;
default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}