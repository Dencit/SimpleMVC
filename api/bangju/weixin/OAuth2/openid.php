<?php
define('Author','632112883@qq.com');
require_once('../app/app.php');

$code = isset($_GET['code']) ? trim($_GET['code']) : '';
$state = isset($_GET['state']) ? trim($_GET['state']) : '';
$debug = isset($_GET['debug']) ? trim($_GET['debug']) : '';
$oAuth_back = isset($_COOKIE[PREFIX."OAuth_back"]) ? trim($_COOKIE[PREFIX."OAuth_back"]) : "";

$rsp = new stdClass();
if (!$state){
	if($debug){
		$rsp->errcode = -11;
		$rsp->errmsg = 'state is empty from weixin server.';
		echo json_encode($rsp);
	}else{
		header("location:".urldecode($oAuth_back));
	}
    exit;
}

if (!$code){
	if($debug){
		$rsp->errcode = -12;
		$rsp->errmsg = 'code is empty from weixin server.';
		echo json_encode($rsp);
	}else{
		header("location:".urldecode($oAuth_back));
	}
    exit;
}

$redis = new redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth("【redis密码】");
$redis->select(1);

if (!$redis->exists($state)){
	if($debug){
		$rsp->errcode = -13;
		$rsp->errmsg = 'state is not match or request timeout from weixin server.';
		echo json_encode($rsp);
	}else{
		header("location:".urldecode($oAuth_back));
	}
    exit;
}
$back = urldecode($redis->get($state));

$weiApi = new weiApi();
$data = $weiApi->OAuth2_access_token($code);

if (!$data){
    header("location:$back");
}else {
    $data = json_decode($data);
    if(isset($data->openid)) {
        if (strpos($back, '?')) {
            header("location:$back&openid=$data->openid&access_token=$data->access_token");
        } else {
            header("location:$back?openid=$data->openid&access_token=$data->access_token");
        }
    }else{
        header("location:$back");
    }
}