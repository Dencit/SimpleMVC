<?php
/*
 *用于ajax调用自定义分享，格式：http://smvc.somatop.com/weiWebApi/jsApi.php?url=
 */
date_default_timezone_set("PRC");
define("TIME",time());
$url = isset($_GET['url']) ? trim($_GET['url']) : (isset($_POST['url']) ? trim($_POST['url']) : '');
$package = new stdClass();
if (!$url){
	$package->errcode = -1;
	$package->errmsg = 'url is empty.';
	echo json_encode($package);
	exit;
}
$url_arr = parse_url($url);
if(!$url_arr){
	$package->errcode = -2;
	$package->errmsg = 'url is invalid.';
	echo json_encode($package);
	exit;
}
if(!isset($url_arr["scheme"]) || !isset($url_arr["host"])){
	$package->errcode = -3;
	$package->errmsg = 'url is invalid!';
	echo json_encode($package);
	exit;
}
header("Access-Control-Allow-Credentials:true");
header("Access-Control-Allow-Origin:".$url_arr["scheme"]."://".$url_arr["host"]);


$redis = new redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(1);
$jsapi_ticket = $redis->get(PREFIX."jsapi_ticket");

//随机字符串
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$str = "";
for ($i = 0; $i < 16; $i++) {
	$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
}

//这里参数的顺序要按照 key 值 ASCII 码升序排序
$string = "jsapi_ticket=$jsapi_ticket&noncestr=$str&timestamp=".TIME."&url=$url";
$signature = sha1($string);

$package->noncestr = $str;
$package->jsapi_ticket = $jsapi_ticket;
$package->timestamp = TIME;
$package->url = $url;
$package->signature = $signature;
echo json_encode($package);