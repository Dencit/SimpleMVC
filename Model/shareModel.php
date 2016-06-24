<?php
/* Created by PhpStorm. | User: 陈鸿扬[SOMA] | Date: 2016/5/5 | Time: 15:47 */
header("Content-type:text/html;charset=utf-8");

require_once('../Common/app.php');//全局变量，路径不能用路径映射变量

//当前用户UID判断
$uid_get=isset($_SESSION[PREFIX.'uid'])?trim($_SESSION[PREFIX.'uid']):'';
if($uid_get==''){ tool::jsonExit(array("checkUid"=>'noUid')); }

//分享用户UID判断
$sid_get=isset($_POST['sid'])?trim($_POST['sid']):'';
if($sid_get==''){ tool::jsonExit(array("checkSid"=>'noSid')); }


//公用数据查询
$whereArray['uid']=$uid_get;

$user_row=$base->rowSelect(USR,'*',$whereArray);
if(!$user_row){
    $subscribe=0;
    $nickname=0;
    $head=0;
    $sex=0;
}else{
    $subscribe=$user_row->subscribe;
    $nickname=$user_row->nickname;
    $nickname=strip_tags($nickname);
    $head=$user_row->headimgurl;
    $sex=$user_row->sex;
}

$userGet_row=$base->rowSelect(USR_GET,'*',$whereArray,'time desc');
if(!$userGet_row){
    $gift=0;
}else{
    $gift=$userGet_row->gift;
}

//share

$whereArray['uid']=$sid_get;

$sharer_row=$base->rowSelect(USR,'*',$whereArray);
if(!$sharer_row){
    $nickname_s='0';
    $head_s='0';
    $sex_s='0';
}else{
    $nickname_s=$sharer_row->nickname;
    $nickname=strip_tags($nickname);
    $head_s=$sharer_row->headimgurl;
    $sex_s=$sharer_row->sex;
}

$sharerGet_row=$base->rowSelect(USR_GET,'*',$whereArray,'time desc');
if(!$sharerGet_row){
    $gift='0';
}
else{
    $gift=$sharerGet_row->gift;
}

$sharerInfo_row=$base->rowSelect(USR_INFO,'*',$whereArray);
if(!$sharerInfo_row){
    $mobile_s='0';
}else{
    $mobile_s=$sharerInfo_row->mobile;
}






