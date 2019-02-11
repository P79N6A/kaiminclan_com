<?php
if(strpos($_SERVER['HTTP_ACCEPT'],'application/json') === false || !isset($_GET['api']))
{
	// exit('Access Denied');
}

$apilist = array(
	'pay'=>'wappay/pay',
	'query'=>'wappay/query',
	'close'=>'wappay/close',
	'refund'=>'wappay/refund',
	'return_url'=>'return_url',
	'AopSdk'=>'AopSdk',
	'refundquery'=>'wappay/refundquery'
);
if(!array_key_exists($_GET['api'],$apilist))
{
	exit('Access Denied');
}

require_once $apilist[$_GET['api']].'.php';
?>