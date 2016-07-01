<?php
/* Created by PhpStorm. | User: 陈鸿扬[SOMA] | Date: 2016/5/5 | Time: 15:47 */
header("Content-type:text/html;charset=utf-8");

require_once('../Common/app.php');//全局变量，路径不能用路径映射变量

//已登陆用户UID判断
$uid_get=isset($_SESSION[PREFIX.'uid'])?trim($_SESSION[PREFIX.'uid']):'';
if($uid_get==''){ tool::jsonExit(array("checkUid"=>'noUid')); }

//公用数据查询
$whereArray['uid']=$uid_get;

$user_row=$base->rowSelect(USR,'*',$whereArray);
if(!$user_row){
    $nickname=0;
    $head=0;
    $sex=0;
}else{
    $nickname=$user_row->nickname;
    $nickname=strip_tags($nickname);
    $head=$user_row->headimgurl;
    $sex=$user_row->sex;
}


$userRec_row=$base->rowSelect(USR_REC,'*',$whereArray,'reced desc');
if(!$userRec_row){
    $recEd=0;
}else{
    $recEd=$userRec_row->reced;
}

$userGet_row=$base->rowSelect(USR_GET,'*',$whereArray,'time desc');
if(!$userGet_row){
    $gift=0;
}else{
    $gift=$userGet_row->gift;
}

$userInfo_row=$base->rowSelect(USR_INFO,'*',$whereArray);
if(!$userInfo_row){
    $mobile=0;
}else{
    $mobile=$userInfo_row->mobile;
}





