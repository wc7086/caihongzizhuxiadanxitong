<?php
error_reporting(0);

session_start();
header('Content-type: application/json');
class wj_qrlogin{
	public function getqrpic(){
		$url = 'https://wj.qq.com/api/session/authorization/create_token';
		$data = $this->get_curl($url,'post=1');
		$arr = json_decode($data, true);
		if(isset($arr['status']) && $arr['status']==1){
			return array('code'=>0, 'qrurl'=>'https://wj.qq.com/api/session/authorization?token='.$arr['data']['token'].'&scene_type=user', 'token'=>$arr['data']['token']);
		}else{
			return array('code'=>-1,'msg'=>'二维码获取失败');
		}
	}
	public function qrlogin($token){
		if(empty($token)) return array('code'=>-1,'msg'=>'token不能为空');
		$url = 'https://wj.qq.com/api/session/authorization/check_token?token='.urlencode($token).'&scene_type=user';
		$result = $this->get_curl($url,0,0,1,1);
		$arr = json_decode($result['body'], true);
		if ($arr['data']['code'] == 3) {
			$cookie_wj = '';
			foreach ($result['cookie'] as $value) {
				if (strpos($value, 'session_user') !== false) {
					$cookie_wj = $value;
				}
			}
			$session = json_decode($this->get_curl('https://wj.qq.com/api/account', 0, $cookie_wj), true);
			if(isset($session['data']['user_id'])){
				$_SESSION['thirdlogin_type']=$session['data']['user_type'];
				$_SESSION['thirdlogin_uin']=$session['data']['user_id'];
				return array('code'=>0, 'uin'=>$session['data']['user_id'], 'type'=>$session['data']['user_type']);
			}else{
				return array('code'=>-1,'msg'=>'登录成功，获取用户信息失败');
			}
		}elseif ($arr['data']['code'] == 1) {
			return array('code'=>1,'msg'=>'请扫描二维码');
		}elseif ($arr['data']['code'] == 2) {
			return array('code'=>2,'msg'=>'正在验证二维码');
		}else{
			return array('code'=>-1,'msg'=>'二维码失效，请刷新页面');
		}
	}
	private function get_curl($url,$post=0,$cookie=0,$header=0,$split=0){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$httpheader[] = "Accept: application/json";
		$httpheader[] = "Accept-Encoding: gzip,deflate,sdch";
		$httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
		$httpheader[] = "Connection: close";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		if($post){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		if($header){
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
		}
		if($cookie){
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		}
		curl_setopt($ch, CURLOPT_REFERER, 'https://wj.qq.com/login.html?s_url=https%3A%2F%2Fwj.qq.com%2Fmine.html');
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36');
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$ret = curl_exec($ch);
		if ($split) {
			$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($ret, 0, $headerSize);
			$body = substr($ret, $headerSize);
			$ret=array();
			$ret['header']=$header;
			$ret['body']=$body;
			preg_match_all("/Set-Cookie: (.*?);/m", $header, $matches);
			$ret['cookie']=$matches[1];
		}
		curl_close($ch);
		return $ret;
	}
}

if(strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])===false)exit('{"code":-1}');

$login=new wj_qrlogin();
if($_GET['do']=='qrlogin'){
	$array=$login->qrlogin($_GET['token']);
}
elseif($_GET['do']=='getqrpic'){
	$array=$login->getqrpic();
}
echo json_encode($array);