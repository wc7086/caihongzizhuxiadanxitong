<?php
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

if(!checkRefererHost())exit('{"code":403}');

switch($act){
case 'app_upload':
	if(!$conf['appcreate_key'])exit('{"code":-1,"msg":"未配置APP生成平台密钥"}');
	$file = $_FILES['file'];
	if(!$file)exit(json_encode(['code' => -1, 'msg' => '上传失败']));
	$type = strtolower(substr($file['name'], strrpos($file['name'], '.') + 1));
	if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
		exit(json_encode(['code' => -1, 'msg' => '上传图片格式错误']));
	}
	$app = new \lib\AppCreate($conf['appcreate_key']);
	if($app->uploadimg($file['tmp_name'])){
		exit(json_encode(['code' => 0, 'msg' => '图片上传成功', 'fileid' => $app->fileid]));
	}else{
		exit(json_encode(['code' => -1, 'msg' => $app->msg]));
	}
break;
case 'app_submit':
	if(!$conf['appcreate_key'])exit('{"code":-1,"msg":"未配置APP生成平台密钥"}');
	$app = new \lib\AppCreate($conf['appcreate_key']);
	$name=trim(daddslashes($_POST['name']));
	$url=trim(daddslashes($_POST['url']));
	if(empty($name))exit('{"code":-1,"msg":"应用名称不能为空"}');
	if(!preg_match('/^[a-zA-Z0-9\x7f-\xff\.\-\! ]+$/',$name) || strlen($name)<3){
		exit('{"code":-1,"msg":"应用名称不合法"}');
	}
	if(mb_strlen($name, "UTF-8")>12)exit('{"code":-1,"msg":"应用名称长度不能超过12个字"}');
	if(empty($url))exit('{"code":-1,"msg":"应用网址不能为空"}');
	if(!strpos($url,'.'))exit('{"code":-1,"msg":"应用网址不正确"}');
	//if(isset($_SESSION['appurl']) && $_SESSION['appurl']==$url)exit(json_encode(['code' => -1, 'msg' => '你已经生成过了，请在"我的生成"中查看。']));
	if($conf['appcreate_diy']==1){
		$icon = !empty($_POST['icon'])?trim($_POST['icon']):'1';
		$background = !empty($_POST['background'])?trim($_POST['background']):'2';
	}else{
		$icon = '1';
		$background = '2';
	}
	$theme = $conf['appcreate_theme'];
	if($app->submittask($name, $url, $icon, $background, $theme, $conf['appcreate_nonav'])){
		$_SESSION['appurl2'] = $url;
		exit(json_encode(['code' => 0, 'msg' => '成功提交生成任务，生成大约需要半分钟，生成成功后请在"我的生成"中查看。', 'taskid' => $app->taskid]));
	}else{
		exit(json_encode(['code' => -1, 'msg' => $app->msg]));
	}
break;
case 'app_query':
	if(!$conf['appcreate_key'])exit('{"code":-1,"msg":"未配置APP生成平台密钥"}');
	$app = new \lib\AppCreate($conf['appcreate_key']);
	$url = (is_https() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'];
	$url=isset($_SESSION['appurl2'])?$_SESSION['appurl2']:$url;
	$url = !empty($_POST['url'])?trim($_POST['url']):$url;
	$domain = parse_url($url)['host'];
	$res=$app->queryurl($url);
	if($res && is_array($res)){
		$appurl = "";
		if($res['status']==1){
			$android_url = $res['lanzou_url']?$res['lanzou_url']:$res['android_url'];
			$ios_url = $res['ios_url'];
			$approw = $DB->find('apps','*',['domain'=>$domain]);
			if($approw){
				$id = $approw['id'];
				$DB->update('apps',['taskid'=>$res['id'], 'domain'=>$domain, 'name'=>$res['name'], 'package'=>$res['package'], 'android_url'=>$android_url, 'ios_url'=>$ios_url, 'icon'=>$res['icon'], 'addtime'=>$res['created_at'], 'status'=>1], ['id'=>$id]);
			}else{
				$id = $DB->insert('apps',['taskid'=>$res['id'], 'domain'=>$domain, 'name'=>$res['name'], 'package'=>$res['package'], 'android_url'=>$android_url, 'ios_url'=>$ios_url, 'icon'=>$res['icon'], 'addtime'=>$res['created_at'], 'status'=>1]);
			}
			$appurl = '/?mod=app&id='.$id;
		}
		$result=array("code"=>0,"msg"=>"succ","url"=>$url,"download_url"=>$appurl,"download_url_show"=>$url.$appurl,"android_url"=>$android_url,"ios_url"=>$ios_url,"data"=>$res);
		exit(json_encode($result));
	}else{
		exit(json_encode(['code' => -1, 'msg' => $app->msg]));
	}
break;
case 'app_clean':
	$count = $DB->exec("UPDATE pre_apps SET status=2 WHERE taskid>0 AND status=1");
	if($count!==false){
		exit(json_encode(['code' => 0, 'msg' => '清空成功，影响'.$count.'条数据', 'count' => $count]));
	}else{
		exit(json_encode(['code' => -1, 'msg' => '清空失败'.$DB->error()]));
	}
break;
default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}