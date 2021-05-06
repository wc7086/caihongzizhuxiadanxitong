<?php
$template_info = [
	'name' => '电脑手机发卡首页模板',
	'version' => 1.1
];

$template_settings = [
	'template_style' => [
		'name'=>'选择界面风格',
		'type'=>'select',
		'options'=> [
			'1'=>'红色',
			'2'=>'黑色',
			'3'=>'天蓝',
			'4'=>'橙色',
			'5'=>'绿色',
			'6'=>'灰色',
			'7'=>'蓝色',
		]
	],
	'template_bgopen' => [
		'name'=>'是否显示背景图',
		'type'=>'select',
		'options'=> [
			'0'=>'不显示',
			'1'=>'显示',
		]
	],
	'template_showsales' => [
		'name'=>'是否显示销量',
		'type'=>'select',
		'options'=> [
			'0'=>'不显示',
			'1'=>'显示',
		]
	],
	'template_label_auto' => [
		'name'=>'发卡和对接商品标签文字',
		'type'=>'input',
		'note'=> '不填写默认是“自动”'
	],
	'template_label_manual' => [
		'name'=>'自营商品标签文字',
		'type'=>'input',
		'note'=> '不填写默认是“手动”'
	],
];
