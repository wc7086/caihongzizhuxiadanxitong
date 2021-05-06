<?php
namespace lib;
/*
 * 安卓APP自动生成
 */

class AppCreate{
	private $apiurl = 'http://997665.cn';
	private $projectid = '3';
	private $theme = '#00A7AA';
	private $key;
	public $msg = null;
	public $fileid;
	public $taskid;

	function __construct($key){
		$this->key = $key;
	}

	//上传图片
	public function uploadimg($path){
		if(!$path)return false;
		$url = $this->apiurl.'/files?key='.$this->key;
		$ch = curl_init();
		$data = [];
		curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
		$data['file'] = curl_file_create($path, "application/octet-stream", 'logo'.time().'.png');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1 );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36");
		$response = curl_exec($ch);
		curl_close($ch);

		$arr = json_decode($response, true);
		if(isset($arr['code']) && $arr['code']==0){
			$this->fileid = $arr['data']['id'];
			return true;
		}elseif(isset($arr['message'])){
			$this->msg = $arr['message'];
		}else{
			$this->msg = '在线生成系统暂时维护，有问题请联系管理员！';
		}
		return false;
	}

	//提交任务
	public function submittask($name, $appurl, $icon = "1", $background = "2", $theme = '', $nonav = 0){
		$url = $this->apiurl.'/tasks?key='.$this->key;
		if(empty($theme))$theme = $this->theme;
		if($nonav==1)$this->projectid='6';
		$args = json_encode(["url" => $appurl, 'theme' => $theme]);
		$post = [
			'project_id' => $this->projectid,
			'name' => $name,
			'icon' => $icon,
			'background' => $background,
			'args' => $args
		];
		$response = get_curl($url, http_build_query($post));

		$arr = json_decode($response, true);
		if(isset($arr['code']) && $arr['code']==0){
			$this->taskid = $arr['data']['id'];
			return true;
		}elseif(isset($arr['message'])){
			$this->msg = $arr['message'];
		}else{
			$this->msg = '在线生成系统暂时维护，有问题请联系管理员！';
		}
		return false;
	}

	//查询任务进度
	public function querytask($taskid){
		$url = $this->apiurl.'/tasks/query';
		$post = [
			'key' => $this->key,
			'id' => $taskid,
		];
		$response = get_curl($url . '?' . http_build_query($post));

		$arr = json_decode($response, true);
		if(isset($arr['code']) && $arr['code']==0){
			if(isset($arr['data'][0])){
				$this->taskid = $arr['data'][0]['id'];
				return $arr['data'][0];
			}
			else{
				$this->msg = '当前APP生成任务不存在';
			}
		}elseif(isset($arr['message'])){
			$this->msg = $arr['message'];
		}else{
			$this->msg = '在线生成系统暂时维护，有问题请联系管理员！';
		}
		return null;
	}

	//根据网址查询APP
	public function queryurl($appurl){
		$url = $this->apiurl.'/tasks/query';
		$post = [
			'key' => $this->key,
			'url' => $appurl
		];
		$response = get_curl($url . '?' . http_build_query($post));

		$arr = json_decode($response, true);
		if(isset($arr['code']) && $arr['code']==0){
			if(isset($arr['data'][0])){
				$this->taskid = $arr['data'][0]['id'];
				return $arr['data'][0];
			}
			else{
				$this->msg = '当前APP生成任务不存在';
			}
		}elseif(isset($arr['message'])){
			$this->msg = $arr['message'];
		}else{
			$this->msg = '在线生成系统暂时维护，有问题请联系管理员！';
		}
		return null;
	}
	
}
