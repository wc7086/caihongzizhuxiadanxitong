<?php
$template_info = [
	'name' => 'H5商城首页模板',
	'version' => 1.5,
];

//PC用户中心模板文件
$template_route = [
	'userreg' => TEMPLATE_ROOT.'store/user/reg.php',
	'userlogin' => TEMPLATE_ROOT.'store/user/login.php',
	'userfindpwd' => TEMPLATE_ROOT.'store/user/findpwd.php',
];

//手机用户中心模板文件
$template_route_m = [
	'userhead' => TEMPLATE_ROOT.'store/user/head.php',
	'userfoot' => TEMPLATE_ROOT.'store/user/foot.php',
	'userindex' => TEMPLATE_ROOT.'store/user/index.php',
	'userreg' => TEMPLATE_ROOT.'store/user/reg.php',
	'userlogin' => TEMPLATE_ROOT.'store/user/login.php',
	'userfindpwd' => TEMPLATE_ROOT.'store/user/findpwd.php',
];

$template_settings = [
	'banner' => ['name'=>'首页轮播图', 'type'=>'textarea', 'note'=>'填写格式：图片链接*跳转链接|图片链接*跳转链接'],
	'defaultcid' => ['name'=>'默认显示分类ID', 'type'=>'input', 'note'=>'首页默认显示商品的分类ID，不填写则显示所有'],
	'template_showprice' => [
		'name'=>'商品页面显示代理价格',
		'type'=>'select',
		'options'=> [
			'0'=>'关闭',
			'1'=>'开启',
		]
	],
	'template_virtualdata' => [
		'name'=>'首页是否显示虚拟数据',
		'type'=>'select',
		'options'=> [
			'0'=>'关闭',
			'1'=>'开启',
		]
	],
	'template_showsales' => [
		'name'=>'是否显示商品销量',
		'type'=>'select',
		'options'=> [
			'0'=>'关闭',
			'1'=>'开启',
		]
	],
	'index_class_num_style' => ['name'=>'首页分类展示几行', 'type'=>'input', 'note'=>'默认不填写为2'],
];
