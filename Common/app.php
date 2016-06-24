<?php
require_once('conf/debug.php');
require_once('conf/config.php');

//函数设置

//相对于 本文件的路径
define('ROOT',str_replace('\\','/',realpath(dirname(__FILE__).'/../')) );
define('ADMIN',ROOT."/Admin");
define('COMM',ROOT."/Common");
define('CACHE',ROOT."/Cache");
define('LIBRARY',ROOT."/Library");
define('FUNC',ROOT."/Library/Functions");
define('HTTP',ROOT."/Library/Http");
define('MODEL',ROOT."/Model");
define('PUBLIC_FILE',ROOT."/Public");

/*$arr=array(ROOT."<br/>",ADMIN."<br/>",COMMON."<br/>",LIBRARY."<br/>",MODEL."<br/>",PUBLIC_FILE."<br/>");
print_r($arr);exit;*/

//工具类
require_once(FUNC.'/jump.php');
require_once(FUNC.'/tool.php');
require_once(FUNC.'/emoji.php');

//api
require_once(HTTP.'/authApi.php');
require_once(HTTP.'/weiApi.php');


$IP_Get=tool::get_ip();
//print_r( tool::get_ip() );exit;


require_once(FUNC.'/timeInterval.php');//开始时间 结束时间
$timeVal=timeInterval::fileGetVal(CACHE.'/timeInterval.php');
define('START_DATE',$timeVal[0]);
define('END_DATE',$timeVal[1]);
$startTime=strtotime($timeVal[0]);
$endTime=strtotime($timeVal[1]);
define('START_TIME',$startTime);
define('END_TIME',$endTime);
//print_r($timeVal[1]);echo "||";
//print_r($endTime);exit();


//连接数据库
require_once('conf/database.php');

//class文件加载
require_once(MODEL.'/wpDb.class.php');//绝对路径加载//相对路径容易出错
require_once(MODEL.'/baseModel.class.php');
$base=new \model\baseModel;
$db=$base->wpDb;


//PREFIX
define('PREFIX','test_');
//all databases name
define('USR',PREFIX.'users');
define('USR_INFO',PREFIX.'users_info');
define('USR_GET',PREFIX.'users_get');
define('USR_REC',PREFIX.'users_rec');
define('GIFT_COUNT',PREFIX.'giftcount');



