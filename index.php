<?php

include 'config.php';

$action = isset($_GET['a']) ? $_GET['a'] : '';

if ('i' == $action) { // get id
	$id = md5(uniqid());
	$id = substr($id, 0, 8);
	
	echo $id;
	exit();
} else if ('p' == $action) { // push url
	if (!isset($_GET['i']) || !isset($_GET['u'])) {
		echo '{"error": {"message": "Arguments error"}}';
		exit();
	}
	
	$id = trim($_GET['i']);
	$url = $_GET['u'];
	
	if ($id == '' || 0 !== strpos($url, 'http://')) {
		echo '{"error": {"message": "Arguments error"}}';
		exit();
	}
	
	$data = array(
		'platform' => 'all',
		'audience' => array(
			'tag' => array('id_' . $id)
		),
		'notification' => array(
			'alert' => $url
		)
	);
	
	$data['options'] = array('apns_production' => false);
	
	$ch = curl_init('https://api.jpush.cn/v3/push');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Basic ' . base64_encode(APP_KEY . ':' . APP_SECRET)
	));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_exec($ch);
	curl_close($ch);
}