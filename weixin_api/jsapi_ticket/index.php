<?php
define("Author","632112883@qq.com");

require_once("../app/app.php");

$acc = isset($_GET["acc"]) ? trim($_GET["acc"]) : "";
$time = isset($_GET["time"]) ? trim($_GET["time"]) : "";
$sign = isset($_GET["sign"]) ? trim($_GET["sign"]) : "";

$rsp = new stdClass();
if(!$acc || !$time || !$sign){
    $rsp->errcode = -1;
    $rsp->errmsg = "Parameter missing!";
    echo json_encode($rsp);
    exit;
}
if(!isset($acc_arr[$acc])){
    $rsp->errcode = -2;
    $rsp->errmsg = "No account!";
    echo json_encode($rsp);
    exit;
}
if(strlen($time) != 10){
    $rsp->errcode = -3;
    $rsp->errmsg = "time parameter error!";
    echo json_encode($rsp);
    exit;
}
if(abs($time - TIME) > 300){
    $rsp->errcode = -4;
    $rsp->errmsg = "time parameter is not match with the server time!";
    echo json_encode($rsp);
    exit;
}
if(md5("jsapi".$acc.$passwd_arr[$acc].$time."ticket") != $sign){
    $rsp->errcode = -5;
    $rsp->errmsg = "sign is not match";
    echo json_encode($rsp);
    exit;
}

$redis = new redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth("【redis密码】");
$redis->select(1);
$jsapi_ticket = $redis->get(PREFIX."jsapi_ticket");
if($jsapi_ticket){
    $expires_in = $redis->ttl(PREFIX."jsapi_ticket") - 300;
	$expires_in = ($expires_in <= 0) ? 5 : $expires_in;
    $rsp->errcode = 1;
    $rsp->errmsg = "success";
    $rsp->jsapi_ticket = $jsapi_ticket;
    $rsp->expires_in = $expires_in;
    echo json_encode($rsp);
    exit;
}else{
    $rsp->errcode = 0;
    $rsp->errmsg = "fail";
    echo json_encode($rsp);
    exit;
}