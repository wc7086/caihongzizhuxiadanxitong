<?php
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

if(!checkRefererHost())exit('{"code":403}');

switch($act){
case 'getcount':
	$result = $CACHE->read('getcount');
	$isUpdate = false;
	if (!empty($result)) {
		$result = unserialize($result);
		if ((time() - $result['time']) > 60)
			$isUpdate = true;
		else
			$result = $result['data'];
	} else {
		$isUpdate = true;
	}
	if($isUpdate){
		$thtime=date("Y-m-d").' 00:00:00';
		$yesterday_time = date("Y-m-d",strtotime("-1 day")).' 00:00:00';
		$count1=$DB->getColumn("SELECT count(*) FROM pre_orders");
		$count2=$DB->getColumn("SELECT count(*) FROM pre_orders WHERE status=1");
		$count3=$DB->getColumn("SELECT count(*) FROM pre_orders WHERE status=0");
		$count4=$DB->getColumn("SELECT count(*) FROM pre_orders WHERE addtime>='$thtime'");
		$count5=$DB->getColumn("SELECT sum(money) FROM pre_pay WHERE `type` IN ('qqpay','wxpay','alipay') AND addtime>='$thtime' AND status=1");

		$strtotime=strtotime($conf['build']);//获取开始统计的日期的时间戳
		$now=time();//当前的时间戳
		$yxts=ceil(($now-$strtotime)/86400);//取相差值然后除于24小时(86400秒)

		$count6=$DB->getColumn("SELECT count(*) FROM pre_site");
		$count7=$DB->getColumn("SELECT count(*) FROM pre_site WHERE addtime>='$thtime'");
		$count8=$DB->getColumn("SELECT sum(point) FROM pre_points WHERE action='提成' and addtime>='$thtime'");

		$count11=$DB->getColumn("SELECT sum(realmoney) FROM `pre_tixian` WHERE `status` = 0");

		$count12=$DB->getColumn("SELECT sum(money) FROM `pre_pay` WHERE `type` = 'qqpay' AND `addtime` > '$thtime' AND `status` = 1");
		$count13=$DB->getColumn("SELECT sum(money) FROM `pre_pay` WHERE `type` = 'wxpay' AND `addtime` > '$thtime' AND `status` = 1");
		$count14=$DB->getColumn("SELECT sum(money) FROM `pre_pay` WHERE `type` = 'alipay' AND `addtime` > '$thtime' AND `status` = 1");

		//今日收益
		$id1 = $DB->getColumn("SELECT id FROM pre_orders WHERE `addtime`<'$thtime' ORDER BY id DESC LIMIT 1");
		$id2 = $DB->getColumn("SELECT id FROM pre_orders WHERE `addtime`<'$yesterday_time' ORDER BY id DESC LIMIT 1");
		$sql="select money,cost from pre_orders where (status = 1 or status = 2) and id > '$id1'";
		$today_list = $DB->getAll($sql);
		$today_total_money = 0;
		foreach($today_list as $k=>$v){
			$today_total_money += ($v['money'] - $v['cost']);
		}

		//昨日收益
		$sql="select money,cost from pre_orders where (status = 1 or status = 2) and id <= '$id1' and id > '$id2'";
		$yesterday_list = $DB->getAll($sql);
		$yesterday_total_money = 0;
		foreach($yesterday_list as $k=>$v){
			$yesterday_total_money += ($v['money'] - $v['cost']);
		}

		$count17=$DB->getColumn("SELECT count(*) FROM pre_workorder where status=0 or status=1");

		$result=array("code"=>0,"yxts"=>$yxts,"count1"=>$count1,"count2"=>$count2,"count3"=>$count3,"count4"=>$count4,"count5"=>round($count5,2),"count6"=>$count6,"count7"=>$count7,"count8"=>round($count8,2),"count9"=>round($count9,2),"count10"=>round($count10,2),"count11"=>round($count11,2),"count12"=>round($count12,2),"count13"=>round($count13,2),"count14"=>round($count14,2),"count15"=>round($today_total_money,2),"count16"=>round($yesterday_total_money,2),"count17"=>$count17,"chart"=>getDatePoint());
		$CACHE->save('getcount', serialize(['time' => time(), 'data' => $result]));
	}
	exit(json_encode($result));
break;
case 'qdcount':
	$day=date("Y-m-d");
	$lastday = date("Y-m-d",strtotime("-1 day"));
	$count1=$DB->getColumn("SELECT count(*) FROM pre_qiandao WHERE date='$day'");
	$count2=$DB->getColumn("SELECT count(*) FROM pre_qiandao WHERE date='$lastday'");
	$count3=$DB->getColumn("SELECT count(*) FROM pre_qiandao");
	$count4=$DB->getColumn("SELECT sum(reward) FROM pre_qiandao WHERE date='$day'");
	$count5=$DB->getColumn("SELECT sum(reward) FROM pre_qiandao WHERE date='$lastday'");
	$count6=$DB->getColumn("SELECT sum(reward) FROM pre_qiandao");
	$result=array("count1"=>$count1,"count2"=>$count2,"count3"=>$count3,"count4"=>round($count4,2),"count5"=>round($count5,2),"count6"=>round($count6,2));
	exit(json_encode($result));
break;
case 'tool':
	adminpermission('shop', 2);
	$tid=intval($_POST['tid']);
	$rows=$DB->getRow("select * from pre_tools where tid='$tid' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"商品ID不存在"}');
	exit('{"code":0,"name":"'.$rows['name'].'"}');
break;
case 'uploadimg':
	adminpermission('shop', 2);
	if($_POST['do']=='upload'){
		$type = $_POST['type'];
		$filename = $type.'_'.md5_file($_FILES['file']['tmp_name']).'.png';
		$fileurl = 'assets/img/Product/'.$filename;
		if(copy($_FILES['file']['tmp_name'], ROOT.'assets/img/Product/'.$filename)){
			exit('{"code":0,"msg":"succ","url":"'.$fileurl.'"}');
		}else{
			exit('{"code":-1,"msg":"上传失败，请确保有本地写入权限"}');
		}
	}
	exit('{"code":-1,"msg":"null"}');
break;
case 'article_upload':
	adminpermission('article', 2);
	$file_name = $_FILES['imgFile']['name'];
	$tmp_name = $_FILES['imgFile']['tmp_name'];
	//获得文件扩展名
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = strtolower(trim($file_ext));
	if (in_array($file_ext, array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'webp')) === false) {
		exit('{"error":1,"message":"上传文件扩展名是不允许的扩展名。"}');
	}
	$filename = md5_file($tmp_name).'.'.$file_ext;
	$fileurl = '/assets/img/article/'.$filename;
	if(copy($tmp_name, ROOT.'assets/img/article/'.$filename)){
		exit('{"error":0,"url":"'.$fileurl.'"}');
	}else{
		exit('{"error":1,"message":"上传失败，请确保有本地写入权限"}');
	}
break;

case 'kms':
	adminpermission('faka', 2);
	$id=intval($_GET['id']);
	$rows=$DB->getRow("select * from pre_faka where kid='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前卡密不存在！"}');
	$data = '<li class="list-group-item" style="word-break:break-all;"><b>卡号：</b>'.$rows['km'].'</li><li class="list-group-item" style="word-break:break-all;"><b>密码：</b>'.$rows['pw'].'</li>';
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'checkshequ':
	$url = $_POST['url'];
	if(gethostbyname($url)=='127.0.0.1'){
		exit('{"code":0}');
	}else{
		exit('{"code":1}');
	}
break;
case 'checkclone':
	$url = $_POST['url'];
	$url_arr = parse_url($url);
	if($url_arr['host']==$_SERVER['HTTP_HOST'])exit('{"code":2}');
	$data = get_curl($url.'api.php?act=clone');
	$arr = json_decode($data,true);
	if(is_array($arr) && array_key_exists('code',$arr) && array_key_exists('msg',$arr)){
		exit('{"code":1}');
	}elseif(substr(bin2hex($data),0,6)=='efbbbf'){
		exit('{"code":3}');
	}else{
		exit('{"code":0}');
	}
break;
case 'checkdwz':
	$url = $_POST['url'];
	$data = get_curl($url);
	if(json_decode($data,true)){
		exit('{"code":1}');
	}elseif($data){
		exit('{"code":2}');
	}else{
		exit('{"code":0}');
	}
break;

case 'gettool': //获取商品列表
	$cid=intval($_GET['cid']);
	$rs=$DB->query("SELECT * FROM pre_tools WHERE cid='$cid' AND active=1 order by sort asc");
	$data = array();
	while($res = $rs->fetch()){
		$data[]=array('tid'=>$res['tid'],'name'=>$res['name']);
	}
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'getfakatool': //获取发卡商品
	$cid=intval($_GET['cid']);
	$rs=$DB->query("SELECT * FROM pre_tools WHERE cid='$cid' and is_curl=4 and active=1 order by sort asc");
	$data = array();
	while($res = $rs->fetch()){
		$data[]=array('tid'=>$res['tid'],'name'=>$res['name']);
	}
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;

case 'setMessage': //站内通知状态
	adminpermission('message', 2);
	$id=intval($_GET['id']);
	$active=intval($_GET['active']);
	$DB->exec("update pre_message set active='$active' where id='{$id}'");
	exit('{"code":0,"msg":"succ"}');
break;
case 'getMessage': //查看站内通知
	$id=intval($_GET['id']);
	$row=$DB->getRow("select * from pre_message where id='$id' limit 1");
	if(!$row)
		exit('{"code":-1,"msg":"当前通知不存在！"}');
	$result=array("code"=>0,"msg"=>"succ","title"=>$row['title'],"type"=>$row['type'],"content"=>$row['content'],"date"=>$row['addtime']);
	exit(json_encode($result));
break;
case 'setArticle': //文章状态
	adminpermission('article', 2);
	$id=intval($_GET['id']);
	$active=intval($_GET['active']);
	$DB->exec("update pre_article set active='$active' where id='{$id}'");
	exit('{"code":0,"msg":"succ"}');
break;

case 'workorder_change':
	adminpermission('workorder', 2);
	$aid=$_POST['aid'];
	$checkbox=$_POST['checkbox'];
	$i=0;
	foreach($checkbox as $id){
		if($aid==1){
			$DB->exec("update pre_workorder set status=0 where id='$id' limit 1");
			$i++;
		}elseif($aid==2){
			$DB->exec("update pre_workorder set status=2 where id='$id' limit 1");
			$i++;
		}elseif($aid==3){
			$rows=$DB->getRow("select * from pre_workorder where id='$id' limit 1");
			$content=str_replace(array('*','^','|'),'',trim(strip_tags(daddslashes($_POST['content']))));
			if($rows && $rows['status']<2 && !empty($content)){
				$content = addslashes($rows['content']).'*1^'.$date.'^'.$content;
				$DB->exec("update pre_workorder set content='$content',status=1 where id='$id' limit 1");
				$i++;
			}
		}elseif($aid==4){
			$DB->exec("DELETE FROM pre_workorder WHERE id='$id' limit 1");
			$i++;
		}
	}
	exit('{"code":0,"msg":"成功改变'.$i.'个工单"}');
break;
case 'delworkorder':
	adminpermission('workorder', 2);
	$id=intval($_GET['id']);
	$sql="DELETE FROM pre_workorder WHERE id='$id' limit 1";
	if($DB->exec($sql)!==false){
		exit('{"code":0,"msg":"删除成功！"}');
	}else{
		exit('{"code":-1,"msg":"删除失败！'.$DB->error().'"}');
	}
break;
case 'add_speedy_text':
	$content = trim(strip_tags(daddslashes($_POST['content'])));
	if($conf['speedy_list']){
		$speedy_list = explode("^", $conf['speedy_list']);
	}else{
		$speedy_list = [];
	}
	$speedy_list[] = $content;
	saveSetting('speedy_list', implode("^",$speedy_list));
	$CACHE->clear();
	exit(json_encode(['code'=>0,'msg'=>'添加快捷回复成功！','id'=>count($speedy_list)-1,'content'=>$content]));
break;
case 'del_speedy_text':
	$ids = explode(',',$_POST['ids']);
	if (!isset($_POST['ids']) || count($ids)<=0) {
		exit(json_encode(['code' => -1, 'msg' => '缺少参数']));
	}
	$speedy_list = explode("^", $conf['speedy_list']);
	foreach($ids as $id){
		array_splice($speedy_list, $id, 1);
	}
	saveSetting('speedy_list', implode("^",$speedy_list));
	$CACHE->clear();
	exit(json_encode(['code'=>0,'msg'=>'删除快捷回复成功！']));
break;

case 'add_member':
	adminpermission('set', 2);
	$name=$_POST['name'];
	$tid=$_POST['tid'];
	$rate=str_replace('%','',$_POST['rate']);
	if(!$name||!$tid||!$rate){
		exit('{"code":-1,"msg":"请输入完整！"}');
	}
	$sql=$DB->exec("INSERT INTO `pre_gift`(`name`,`tid`,`rate`,`ok`) VALUES ('{$name}','{$tid}',{$rate},0)");
	if($sql){
		exit('{"code":0,"msg":"添加成功"}');
	}else{
		exit('{"code":1,"msg":"添加失败，'.$DB->error().'"}');
	}
break;
case 'edit_cj':
	adminpermission('set', 2);
	$id=$_POST['id'];
	if(!$id){
		exit('{"code":-1,"msg":"请输入完整！"}');
	}
	$sql=$DB->getRow("SELECT * FROM pre_gift where id='{$id}'");
	if($sql){
		$cid = $DB->getColumn("select cid from pre_tools where tid='{$sql['tid']}' limit 1");
		exit('{"code":0,"msg":"查询成功","id":"'.$id.'","name":"'.$sql['name'].'","cid":"'.$cid.'","tid":"'.$sql['tid'].'","rate":"'.$sql['rate'].'"}');
	}else{
		exit('{"code":1,"msg":"查询失败，'.$DB->error().'"}');
	}
break;
case 'edit_cj_ok':
	adminpermission('set', 2);
	$id=$_POST['id'];
	$name=$_POST['name'];
	$tid=$_POST['tid'];
	$rate=$_POST['rate'];
	if(!$id){
		exit('{"code":-1,"msg":"请输入完整！"}');
	}
	$sql=$DB->exec("UPDATE pre_gift set name='{$name}',tid='{$tid}',rate='{$rate}' where id='{$id}'");
	if($sql!==false){
		exit('{"code":0,"msg":"修改成功"}');
	}else{
		exit('{"code":1,"msg":"修改失败，'.$DB->error().'"}');
	}
break;
case 'del_member':
	adminpermission('set', 2);
	$id=$_POST['id'];
	if(!$id){
		exit('{"code":-1,"msg":"请输入完整！"}');
	}
	$sql=$DB->exec("DELETE FROM pre_gift WHERE id='{$id}'");
	if($sql!==false){
		exit('{"code":0,"msg":"删除成功"}');
	}else{
		exit('{"code":1,"msg":"删除失败，'.$DB->error().'"}');
	}
break;
case 'cishu':
	adminpermission('set', 2);
	$cishu=$_GET['cishu'];
	$gift_open=$_GET['gift_open'];
	$cjmsg=$_GET['cjmsg'];
	$cjmoney=$_GET['cjmoney'];
	$gift_log=$_GET['gift_log'];
	if($cishu==''||$cishu==0 || $gift_open==''||$cjmsg==''){
		exit('{"code":-1,"msg":"请输入完整！"}');
	}
	if($cjmoney==''){
		$cjmoney=0;
	}
	saveSetting('cjcishu',$cishu);
	saveSetting('gift_open',$gift_open);
	saveSetting('cjmsg',$cjmsg);
	saveSetting('cjmoney',$cjmoney);
	saveSetting('gift_log',$gift_log);
	$ad=$CACHE->clear();
	if($ad){
		exit('{"code":0,"msg":"修改成功"}');
	}else{
		exit('{"code":1,"msg":"修改失败，'.$DB->error().'"}');
	}
break;
case 'delInvite':
	adminpermission('shop', 2);
	$id=intval($_GET['id']);
	$sql="DELETE FROM pre_inviteshop WHERE id='$id' limit 1";
	if($DB->exec($sql)!==false){
		exit('{"code":0,"msg":"删除商品成功！"}');
	}else
		exit('{"code":-1,"msg":"删除商品失败！'.$DB->error().'"}');
break;
case 'setInvite': //商品上下架
	adminpermission('shop', 2);
	$id=intval($_GET['id']);
	$active=intval($_GET['active']);
	$DB->exec("update pre_inviteshop set active='$active' where id='{$id}'");
	exit('{"code":0,"msg":"succ"}');
break;
case 'delInviteLog':
	adminpermission('shop', 2);
	$id=intval($_GET['id']);
	$sql="DELETE FROM pre_invite WHERE id='$id' limit 1";
	if($DB->exec($sql)!==false){
		exit('{"code":0,"msg":"删除记录成功！"}');
	}else
		exit('{"code":-1,"msg":"删除记录失败！'.$DB->error().'"}');
break;
case 'create_url':
	$force = trim(daddslashes($_GET['force']));
    $url = trim(daddslashes($_GET['longurl']));
	if($force==1){
		$turl = fanghongdwz($url,true);
	}else{
		$turl = fanghongdwz($url);
	}
	if($turl == $url){
		$result = array('code'=>-1, 'msg'=>'生成失败，请更换接口');
	}elseif(strpos($turl,'/')){
		$result = array('code'=>0, 'msg'=>'succ', 'url'=>$turl);
	}else{
		$result = array('code'=>-1, 'msg'=>'生成失败：'.$turl);
	}
	exit(json_encode($result));
break;
case 'rewrite':
	adminpermission('set', 2);
	$article_rewrite = intval($_POST['article_rewrite']);
	$server_software = strtolower($_SERVER['SERVER_SOFTWARE']);
	if($article_rewrite==1 && (strpos($server_software,'apache')!==false || strpos($server_software,'kangle')!==false)){
		$filecontent = '<IfModule mod_rewrite.c>
  Options +FollowSymlinks
  RewriteEngine On

  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^article-(.[0-9]*).html$ index.php?mod=article&id=$1 [QSA,PT,L]
  RewriteRule ^(.[a-zA-Z0-9\-\_]+).html$ index.php?mod=$1 [QSA,PT,L]
</IfModule>';
		if(!file_put_contents(ROOT.'.htaccess', $filecontent)){
			exit('{"code":-1,"msg":"写入.htaccess失败，请确认有写入权限"}');
		}
	}
	saveSetting('article_rewrite', $article_rewrite);
	$ad=$CACHE->clear();
	if($ad)exit('{"code":0,"msg":"succ"}');
	else exit('{"code":-1,"msg":"修改设置失败['.$DB->error().']"}');
break;
case 'set':
	adminpermission('set', 2);
	foreach($_POST as $k=>$v){
		saveSetting($k, $v);
	}
	$ad=$CACHE->clear();
	if($ad)exit('{"code":0,"msg":"succ"}');
	else exit('{"code":-1,"msg":"修改设置失败['.$DB->error().']"}');
break;
case 'thirdloginunbind':
	adminpermission('set', 2);
	$type = isset($_POST['type'])?$_POST['type']:exit;
	$key = $type=='wx'?'thirdlogin_wx':'thirdlogin_qq';
	saveSetting($key, '');
	$CACHE->clear();
	exit('{"code":0,"msg":"succ"}');
break;
case 'getServerIp':
	$ip = getServerIp();
	exit('{"code":0,"ip":"'.$ip.'"}');
break;
case 'epayurl':
	$id = intval($_GET['id']);
	$conf['payapi']=$id;
	if($id>0 && $url = pay_api(false)){
		exit('{"code":0,"url":"'.$url.'"}');
	}else{
		exit('{"code":-1}');
	}
break;
case 'micropayurl':
	$id = intval($_GET['id']);
	$conf['micropayapi']=$id;
	if($url = micropay_api(false)){
		exit('{"code":0,"url":"'.$url.'"}');
	}else{
		exit('{"code":-1}');
	}
break;
case 'iptype':
	$result = [
	['name'=>'0_X_FORWARDED_FOR', 'ip'=>real_ip(0), 'city'=>get_ip_city(real_ip(0))],
	['name'=>'1_X_REAL_IP', 'ip'=>real_ip(1), 'city'=>get_ip_city(real_ip(1))],
	['name'=>'2_REMOTE_ADDR', 'ip'=>real_ip(2), 'city'=>get_ip_city(real_ip(2))]
	];
	exit(json_encode($result));
break;
case 'transfer':
	adminpermission('super', 2);
	$id = intval($_POST['id']);
	if(!$conf['fenzhan_daifu'])exit(json_encode(array('code'=>0,'msg'=>'请先在分站设置开启代付接口')));
	if(!$conf['transfer_id'] || !$conf['transfer_key'] || !$conf['transfer_check'] || !$_SESSION["transfer_pass"])exit(json_encode(array('code'=>0,'msg'=>'请先配置好自动转账接口信息')));
	$res = $DB->getRow("SELECT * FROM pre_tixian WHERE id='$id' AND status=0");
	if (!$res) exit(json_encode(array('code'=>0,'msg'=>'记录不存在或状态不是待处理！')));
	if ($res['pay_type'].'' == '1') {
		$type = '3';
	}elseif ($res['pay_type'].'' == '0') {
		$type = '1';
	}else{
		$type = $res['pay_type'];
	}
	$param = [
	    'api_id'=>trim($conf['transfer_id']),
	    'money'=>$res['realmoney'],
	    'payee_type'=>$type,
	    'payee_account'=>$res['pay_account'],
		'payee_name'=>$res['pay_name'],
		'realname'=>$conf['transfer_check'],
		'timestamp'=>time(),
		'pay_pass'=>$_SESSION["transfer_pass"],
	];
	$param['sign'] = yile_getSign($param, trim($conf['transfer_key']));
	$data = get_curl('https://api.fcypay.com/transfer', $param);
	$json = json_decode($data,true);
	if (isset($json['code']) && $json['code']) {
		if($DB->exec("update pre_tixian set status=1,endtime=NOW() where id='$id'")===false) exit(json_encode(array('code'=>0,'msg'=>'汇款成功!但是结算记录状态改变失败！')));
	    exit(json_encode(array('code'=>1,'msg'=>'汇款成功')));
	}else{
	    exit(json_encode(array('code'=>0,'msg'=>isset($json['msg'])?$json['msg']:'对接平台未知错误')));
	}
break;
case 'transfer_config':
	adminpermission('super', 2);
	if(!$conf['fenzhan_daifu'])exit(json_encode(array('code'=>0,'msg'=>'请先在分站设置开启代付接口')));
	if (!$_POST['id'] || !$_POST['key'] || !$_POST['pass']) exit(json_encode(['code'=>0,'msg'=>'请填写完整']));
	if ($_POST['check'] !== 'NO_CHECK' && $_POST['check'] !== 'FORCE_CHECK') exit(json_encode(['code'=>0,'msg'=>'验证选项错误']));
	saveSetting('transfer_id',$_POST['id']);
	saveSetting('transfer_key',$_POST['key']);
	saveSetting('transfer_check',$_POST['check']);
	$CACHE->clear();
	$_SESSION["transfer_pass"] = md5($_POST['pass']);
	$_SESSION["transfer"] = true;
	exit(json_encode(['code'=>1,'msg'=>'修改成功']));
break;

default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}