<?php
require_once('./Common/app.php');//全局变量，路径不能用路径映射变量

$openid = isset($_GET['openid']) ? trim($_GET['openid']) : '';
$access_token = isset($_GET['access_token']) ? trim($_GET['access_token']) : '';


if (!$openid || !$access_token){
    exit('fail to get openid or access_token.');
}


$_SESSION[PREFIX.'openid'] = $openid;
$_SESSION[PREFIX.'access_token'] = $access_token;

//print_r($_SESSION[PREFIX.'openid']."|||".$_SESSION[PREFIX.'access_token']); exit;

$sid_get = isset($_GET['sid']) ? trim($_GET['sid']) : '';

if($sid_get){
    jump::head("./share.php?sid=".$sid_get);
}else{
    jump::head("./index.php");
}


