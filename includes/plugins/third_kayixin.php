<?php
namespace plugins;

class third_kayixin{

	private $config = [];

	static public $info = [
		'name'        => 'third_kayixin',
		'type'        => 'third',
		'title'       => '卡易信',
		'author'      => '彩虹',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 13,
		'showedit'    => false,
		'showip'      => false,
		'pricejk'     => 2,
		'input' => [
			'url' => '网站域名',
			'username' => '登录账号',
			'password' => '登录密码',
			'paypwd' => '支付密码',
			'paytype' => false,
		],
	];

	public function __construct($config)
	{
		$this->config = $config;
	}
	
	private function login(){
		$url = 'http://'.$this->config['url'].'/frontLogin.htm';
		$param = [
			'loginTimes' => '1',
			'userName' => $this->config['username'],
			'password' => $this->config['password']
		];
		$post = http_build_query($param);
		$data1 = $this->get_curl($url,$post,$url,0,1);
		$data2 = "{" . getSubstr($data1, "{", "}") . "}";
		$json = json_decode($data2,true);
		if (isset($json['code']) && $json['code'] == 10000)
		{
			$cookies='';
			preg_match_all('/Set-Cookie: (.*);/iU',$data1,$matchs);
			foreach ($matchs[1] as $val) {
				$cookies.=$val.'; ';
			}
			return $cookies;
		}else{
			return '登录失败：'.$json['mess'];
		}
	}

	public function do_goods($goods_id, $type, $orderurl, $num = 1, $input = array(), $money, $tradeno, $inputsname)
	{
		global $CACHE;
		$inputs=explode('|',$inputsname);
		$result['code'] = -1;
		$goodsid=getSubstr($orderurl, "goodId=", "&");
		$result['goodsid'] = $goodsid;
		$mainKey=explode(',',$orderurl);$mainKey=$mainKey[2];$mainKey=explode('&',$mainKey);$mainKey=$mainKey[0]?$mainKey[0]:'0';

		$url = 'http://'.$this->config['url'].'/front/inter/uploadOrder'.($type==1?'Kami':null).'.htm?salePwd='.urlencode($this->config['paypwd']);
		$post = 'goodsId='.$goodsid.'&mainKey='.$mainKey.'&sumprice='.$num.'&textAccountName='.urlencode($input[0]).'&reltextAccountName='.urlencode($input[0]);
		if (is_array($inputs) && $inputs[0]){
			$i=0;
			foreach ($inputs as $val){
				$post .= '&temptypeName'.$i.'='.urlencode($val).'&lblName'.$i.'='.urlencode($input[$i+1]);
				$i++;
			}
		}

		$cache_key = 'kayixin_'.substr(md5($this->config['url'].$this->config['username']),0,10);
		$cookies = $CACHE->read($cache_key);
		if(!$cookies){
			$cookies=$this->login();
			if(strpos($cookies,'失败')){
				$result['message'] = $cookies;
				return $result;
			}else{
				$CACHE->save($cache_key, $cookies);
			}
		}

		$data = $this->get_curl($url,$post,'http://'.$this->config['url'].'/front/inter/buyGoods.htm',$cookies);
		if (strstr($data,'须重新登录系统')){
			$cookies=$this->login();
			if(strpos($cookies,'失败')){
				$result['message'] = $cookies;
				return $result;
			}else{
				$CACHE->save($cache_key, $cookies);
				$data = $this->get_curl($url,$post,'http://'.$this->config['url'].'/front/inter/buyGoods.htm',$cookies);
			}
		}
		$json = json_decode($data,true);
		if(isset($json['orderNo'])){
			$result = array(
				'code' => 0,
				'id' => $json['orderNo']
			);
			if($type==1){
				$data = $this->get_curl('http://'.$this->config['url'].'/front/inter/backOrder.htm?orderNo='.$json['orderNo'].'&goodId='.$goodsid,0,'http://'.$this->config['url'].'/front/inter/buyGoods.htm',$cookies);
				$table = getSubstr($data, "<tbody>", "</tbody>");
				$kmdata=[];
				if(preg_match_all('/<td align="center">(.*?)<\/td>.*?<td align="center">(.*?)<\/td>.*?<td align="center">(.*?)<\/td>.*?<td align="center">(.*?)<\/td>/s',$data,$matchs)){
					foreach($matchs[1] as $kmkey=>$kmrow){
						$kmdata[] = ['card'=>$kmrow, 'pass'=>$matchs[2][$kmkey]];
					}
				}
				$result['faka']=true;
				$result['kmdata']=$kmdata;
			}
		}elseif(isset($json['mess'])){
			$result['message'] = $json['mess'];
		}else{
			$result['message'] = $data;
		}
		return $result;
	}

	public function goods_info($url){
		global $CACHE;
		$cache_key = 'kayixin_'.substr(md5($this->config['url'].$this->config['username']),0,10);
		$cookies = $CACHE->read($cache_key);
		if(!$cookies){
			$cookies=$this->login();
			if(strpos($cookies,'失败')){
				return $cookies;
			}else{
				$CACHE->save($cache_key, $cookies);
			}
		}

		if(strpos($url, 'http://')===false || strpos($url, 'front/inter/buyGoods.htm')===false)return null;
		$data = $this->get_curl($url,0,0,$cookies);
		if(strpos($data, '商品名称：')){
			$result['name'] = trim(getSubstr($data, '<td class="td-right td-buy-good-name">', '</td>'));
			$result['type'] = trim(getSubstr($data, '<td class="td-right td-buy-good-type">', '</td>'));
			$result['desc'] = trim(getSubstr($data, '<div class="content">', '</div>'));
			$result['input'] = [];
			if(preg_match('/<input type=\"hidden\" value=\"(.*?)\" id=\"accountName\"/',$data,$match)){
				$result['input'][] = $match[1];
			}
			if(preg_match('/<input type=\"hidden\" value=\"(.*?)\" name=\"temptypeName0\"/',$data,$match)){
				$result['input'][] = $match[1];
			}
			if(preg_match('/<input type=\"hidden\" value=\"(.*?)\" name=\"temptypeName1\"/',$data,$match)){
				$result['input'][] = $match[1];
			}
			if(preg_match('/<input type=\"hidden\" value=\"(.*?)\" name=\"temptypeName2\"/',$data,$match)){
				$result['input'][] = $match[1];
			}
			if(preg_match('/<input type=\"hidden\" value=\"(.*?)\" name=\"temptypeName3\"/',$data,$match)){
				$result['input'][] = $match[1];
			}
			if(preg_match('/<input type=\"hidden\" name=\"saleprice\" id=\"saleprice\" value=\"(.*?)\"\/>/',$data,$match)){
				$result['price'] = $match[1];
			}
			if(strpos($data, '库存不足,请联系站长补货')){
				$result['close'] = 1;
			}else{
				$result['close'] = 0;
			}
			return $result;
		}elseif(strpos($data, 'window.top.location.href=')){
			return '商品不存在';
		}else{
			return '打开对接网站失败';
		}
	}
	
	public function pricejk_one($tool){
		global $DB,$conf,$CACHE;
		$success=0;
		$details = $this->goods_info($tool['goods_param']);
		if(is_array($details)){
			$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ={$tool['shequ']} AND goods_param='{$tool['goods_param']}'");
			while($res2 = $rs2->fetch())
			{
				if($res2['price']==='0.00')continue;
				if($details['close']==0){
					$price = ceil($details['price'] * 100)/100;
					if($conf['pricejk_edit']==1 && $price>$res2['price'] && $res2['prid']>0){
						$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
						$success++;
					}elseif($conf['pricejk_edit']==0 && $price!=$res2['price'] && $res2['prid']>0){
						$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
						$success++;
					}
				}
				if($details['close']==1 && $res2['close']==0){
					$DB->exec("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
				}elseif($details['close']==0  && $res2['close']==1){
					$DB->exec("update `pre_tools` set `close`=0 where `tid`='{$res2['tid']}'");
				}
				$DB->exec("update `pre_tools` set `uptime`='".time()."' where `tid`='{$res2['tid']}'");
			}
		}elseif(strpos($details,'商品不存在')!==false){
			$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ={$tool['shequ']} AND goods_param='{$tool['goods_param']}'");
			while($res2 = $rs2->fetch())
			{
				$DB->exec("update `pre_tools` set `close`=1,`uptime`='".time()."' where `tid`='{$res2['tid']}'");
				$success++;
			}
		}
		return $success;
	}

	public function pricejk($shequid,&$success){
		global $DB,$conf;
		$pricejk_time = $conf['pricejk_time']?$conf['pricejk_time']:600;
		for($i=0;$i<30;$i++){
			$tool=$DB->getRow("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']}) AND uptime<'".(time()-$pricejk_time)."' ORDER BY uptime ASC");
			if(!$tool)break;
			$count = $this->pricejk_one($tool);
			$success+=$count;
		}
		return true;
	}

	public function getKyxCategory(){
		global $CACHE;
		$cache_key = 'kayixin_'.substr(md5($this->config['url'].$this->config['username']),0,10);
		$cookies = $CACHE->read($cache_key);
		if(!$cookies){
			$cookies=$this->login();
			if(strpos($cookies,'失败')){
				return $cookies;
			}else{
				$CACHE->save($cache_key, $cookies);
			}
		}
	
		$url = 'http://'.$this->config['url'].'/front/inter/dirList.htm';
		$data = $this->get_curl($url,0,0,$cookies);
		if(preg_match_all('/name=\"showgoods\" title=.*?id=\"(.*?)\">(.*?)<\/a>/',$data,$matchs)){
			$list = [];
			foreach($matchs[1] as $key=>$goodid){
				if(empty($goodid))continue;
				$list[] = ['id'=>$goodid, 'name'=>$matchs[2][$key]];
			}
			return $list;
		}else{
			return false;
		}
	}
	public function getKyxProductList($dirid){
		global $CACHE;
		$cache_key = 'kayixin_'.substr(md5($this->config['url'].$this->config['username']),0,10);
		$cookies = $CACHE->read($cache_key);
		if(!$cookies){
			$cookies=$this->login();
			if(strpos($cookies,'失败')){
				return $cookies;
			}else{
				$CACHE->save($cache_key, $cookies);
			}
		}
	
		$page = 1;
		$list = [];
		do{
			$count = 0;
			$url = 'http://'.$this->config['url'].'/front/inter/cutPageGoodsList.htm?nowPage='.$page;
			$post = 'dirId='.$dirid.'%2C0%2C0&keyWord=&dremId=';
			$data = $this->get_curl($url, $post, 0, $cookies);
			if(preg_match_all('/<td class="red name".*?>(.*?)<\/td>.*?<td name="hideMoneys" class="price">(.*?)<\/td>.*?<td id="1">(.*?)<\/td>/s',$data,$matchs)){
				//print_r($matchs);exit;
				foreach($matchs[1] as $key=>$datas){
					preg_match("/showgoodsmessage\(\'(\d+),(\d+)\'\)/",$datas,$match);
					if(strpos($matchs[3][$key], '提取卡密')){
						$type = 1;
						$close = 0;
					}elseif(strpos($matchs[3][$key], '立即充值')){
						$type = 0;
						$close = 0;
					}else{
						$type = 0;
						$close = 1;
					}
					$list[] = ['id'=>$match[1], 'keyid'=>$match[2], 'name'=>trim(strip_tags($datas)), 'price'=>trim(strip_tags(str_replace('¥','',$matchs[2][$key]))), 'type'=>$type, 'close'=>$close];
					$count++;
				}
			}
			$page++;
		}while($count==20);
		return $list;
	}

	private function get_curl($url,$post=0,$referer=0,$cookie=0,$header=0,$addheader=0){
		return shequ_get_curl($url,$post,$referer,$cookie,$header,$addheader);
	}
}