<?php
require 'inc.php';

$urlarr=explode('/',$_SERVER['PATH_INFO']);
$act = $urlarr[1];

@header('Content-Type: text/html; charset=UTF-8');

if($act=='shequ')
{
	$shequid = intval($urlarr[2]);
	if(!$shequid)exit('No Shequ ID');
	$tradeno = daddslashes($urlarr[3]);
	if(!$tradeno)exit('No tradeno');
	$shequ = $DB->getRow("SELECT * FROM pre_shequ WHERE id='{$shequid}' LIMIT 1");
	if(!$shequ)exit('Shequ not exists');
	$order = $DB->getRow("SELECT * FROM pre_orders WHERE tradeno='{$tradeno}' LIMIT 1");
	if(!$order)exit('Order not exists');
	$list = third_call($shequ['type'], $shequ, 'notify', [$order]);
	if(!$list)echo 'No support';
}
else
{
	echo 'No Act';
}
