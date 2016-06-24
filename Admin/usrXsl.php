<?php
// Created by PhpStorm. | User: 陈鸿扬[SOMA] | Date: 2016/4/26 | Time: 16:14 //

header("Content-type:text/html;charset=UTF-8");

require_once("../Common/app.php");
//require_once("../Admin/Model/indexBase.php");


$user =isset($_GET['u'])?$_GET['u']:'';
if($user!=="Admin888"){
    exit("请输入用户名");
}

$gift_t =isset($_GET['ty'])?$_GET['ty']:'';
if(!$gift_t){
    exit("请输入奖品类型");
}

$date_s =isset($_GET['ds'])?$_GET['ds']:'';
if(!$date_s){
    exit("请输入开始日期");
}
$date_e =isset($_GET['de'])?$_GET['de']:'';
if(!$date_e){
    exit("请输入结束日期");
}
$page=isset($_GET['p'])?$_GET['p']:'';
if($page==''||$page=='0'){
    exit("请输入页码：1/2/3/...");
}
$sta =(($page-1)*65530);
$per=($sta+65530);
$start = strtotime("$date_s");
$end = strtotime("$date_e");

//

//print_r($start.'||'.$end);exit;

if($gift_t==0){
    $usrGetRes=$db->get_results("select * from ".USR_GET." g left join ".USR." u on g.uid = u.uid where g.gift=".$gift_t." AND g.time >= ".$start." AND g.time < ".$end." ORDER BY g.time desc limit ".$sta.",".$per);
    if(!$usrGetRes){
        exit("没有这些数据0");
    }
}else{
    $usrGetRes=$db->get_results("select * from ".USR_GET." g left join ".USR_INFO." i on g.uid = i.uid where g.gift=".$gift_t." AND g.time >= ".$start." AND g.time < ".$end." ORDER BY g.time desc limit ".$sta.",".$per);
    if(!$usrGetRes){
        exit("没有这些数据1");
    }
}


require_once("./Action/xslTamp.php");
$state=isset($_GET['sta'])?$_GET['sta']:'';

switch($gift_t){
    case 0 :$gift_s="2元现金红包";break;
    case 2 :$gift_s="长隆家庭乐票";break;
    case 3 :$gift_s="星巴克电子咖啡券";break;
    case 4 :$gift_s="10元话费";break;
    case 5 :$gift_s="院线通电影票";break;
}

$tableName="获取[".$gift_s."]用户[".$date_s."_".$date_e."][P".$page."]";

if($state!='view'){ xslTamp($usrGetRes,$tableName); }
else{ xslTamp($usrGetRes,$tableName,'view'); }

