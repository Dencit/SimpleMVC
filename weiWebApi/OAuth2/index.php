<?php
define('Author','632112883@qq.com');
require_once('../app/app.php');

$acc = isset($_GET["acc"]) ? trim($_GET["acc"]) : "";
$time = isset($_GET["time"]) ? trim($_GET["time"]) : "";
$sign = isset($_GET["sign"]) ? trim($_GET["sign"]) : "";

$scope = isset($_GET['scope']) ? trim($_GET['scope']) : '';
$state = isset($_GET['state']) ? trim($_GET['state']) : '';
$debug = isset($_GET['debug']) ? trim($_GET['debug']) : '';

if($debug){
	$redirect_uri = urlencode(OAUTH2_URI.'/openid.php?debug=1');
}else{
	$redirect_uri = urlencode(OAUTH2_URI.'/openid.php');
}

$rsp = new stdClass();
if(!$acc || !$time || !$sign || !$scope || !$state){
	$rsp->errcode = -1;
	$rsp->errmsg = "Parameter missing!";
	echo json_encode($rsp);
    exit;
}
if(!isset($acc_arr[$acc])){
	if($debug){
		$rsp->errcode = -2;
		$rsp->errmsg = "No account!";
		echo json_encode($rsp);
	}else{
		header("location:".urldecode($state));
	}
    exit;
}
if(strlen($time) != 10){
	if($debug){
		$rsp->errcode = -3;
		$rsp->errmsg = "time parameter error!";
		echo json_encode($rsp);
	}else{
		header("location:".urldecode($state));
	}
    exit;
}
if(abs($time - TIME) > 300){
	if($debug){
		$rsp->errcode = -4;
		$rsp->errmsg = "time parameter is not match with the server time!";
		echo json_encode($rsp);
	}else{
		header("location:".urldecode($state));
	}
    exit;
}
if(md5("OAuth2".$acc.$passwd_arr[$acc].$time) != $sign){
	if($debug){
		$rsp->errcode = -5;
		$rsp->errmsg = "sign is not match!";
		echo json_encode($rsp);
	}else{
		header("location:".urldecode($state));
	}
    exit;
}
if (!in_array($scope,array('snsapi_base','snsapi_userinfo'))){
	if($debug){
		$rsp->errcode = -6;
		$rsp->errmsg = 'scope is invalid!';
		echo json_encode($rsp);
	}else{
		header("location:".urldecode($state));
	}
    exit;
}
if (!$state){
	if($debug){
		$rsp->errcode = -7;
		$rsp->errmsg = 'state is empty.state must be an url!';
		echo json_encode($rsp);
	}else{
		header("location:".urldecode($state));
	}
    exit;
}


$redis = new redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(1);

$key = PREFIX.'OAuth_'.md5($acc.$state.$scope.TIME.rand(0,100000));
while ($redis->exists($key)){
    $key = PREFIX.'OAuth_'.md5($acc.$state.$scope.TIME.rand(0,100000));
}

$redis->set($key,$state);
$redis->EXPIRE($key,60);
setcookie(PREFIX."OAuth_back",$state);

$weiApi = new weiApi();
$weiApi->get_code($scope,$key,$redirect_uri);