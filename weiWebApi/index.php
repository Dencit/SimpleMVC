<?php
//phpinfo();exit();

define("Author","632112883@qq.com");
require_once("./app/app.php");

$refresh = isset($_GET["r"]) ? trim($_GET["r"]) : "";

$key = isset($_GET["k"]) ? trim($_GET["k"]) : "";
if("KJkdlk7jDSp" != $key){
    exit(PREFIX."_api");
}

$redis = new redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(1);
$weiApi = new weiApi();

/*
 * access_token
 */
$access_token = $redis->get(PREFIX."access_token");
$access_token_ttl = $redis->ttl(PREFIX."access_token");

if(!$access_token || $access_token_ttl < 300 || $refresh=='refresh'){
    $data = $weiApi->access_token();
    if(!$data){
        //发送警报给管理员
    }else{
        $data = json_decode($data);
    }
    if(!isset($data->access_token) || !isset($data->expires_in)){
        //发送警报给管理员
    }else {
        $redis->set(PREFIX."access_token", $data->access_token);
        $redis->expire(PREFIX."access_token", ($data->expires_in - 10));
        echo "access_token OK\n";
    }
}else{
    echo "access_token NOT Refresh\n";
}
$access_token = $redis->get(PREFIX."access_token");

/*
 * jsapi_ticket
 */
$jsapi_ticket = $redis->get(PREFIX."jsapi_ticket");
$jsapi_ticket_ttl = $redis->ttl(PREFIX."jsapi_ticket");

if(!$jsapi_ticket || $jsapi_ticket_ttl < 300 || $refresh=='refresh'){
    $data = $weiApi->jsapi_ticket($access_token);
    if(!$data){
        //发送警报给管理员
    }else{
        $data = json_decode($data);
    }
    if(!isset($data->ticket) || !isset($data->expires_in)){
        //发送警报给管理员
    }else {
        $redis->set(PREFIX."jsapi_ticket", $data->ticket);
        $redis->expire(PREFIX."jsapi_ticket", ($data->expires_in - 10));
        echo "jsapi_ticket OK\n";
    }
}else{
    echo "jsapi_ticket NOT Refresh\n";
}

/*
 * api_ticket
 */
$api_ticket = $redis->get(PREFIX."api_ticket");
$api_ticket_ttl = $redis->ttl(PREFIX."api_ticket");
if(!$api_ticket || $api_ticket_ttl < 300 || $refresh=='refresh'){
    $data = $weiApi->api_ticket($access_token);
    if(!$data){
        //发送警报给管理员
    }else{
        $data = json_decode($data);
    }
    if(!isset($data->ticket) || !isset($data->expires_in)){
        //发送警报给管理员
    }else {
        $redis->set(PREFIX."api_ticket", $data->ticket);
        $redis->expire(PREFIX."api_ticket", ($data->expires_in - 10));
        echo "api_ticket OK\n";
    }
}else{
    echo "api_ticket NOT Refresh\n";
}