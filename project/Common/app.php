<?php
require_once('conf/debug.php');
require_once('conf/config.php');

//相对于 本文件的路径
define('ROOT',str_replace('\\','/',realpath(dirname(__FILE__).'/../')) );
//
define('ADMIN',ROOT."/Admin");
define('COMM',ROOT."/Common");
define('CACHE',ROOT."/Cache");
define('LIBRARY',ROOT."/Library");
define('COMCLASS',ROOT."/Library/ComClass");
define('FUNC',ROOT."/Library/Functions");
define('HTTP',ROOT."/Library/Http");
define('MODEL',ROOT."/Model");
define('PUBLIC_FILE',ROOT."/Public");
//

//工具类
require_once(FUNC.'/jump.php');
require_once(FUNC.'/tool.php');
require_once(FUNC.'/emoji.php');

//weiApi
require_once(HTTP.'/authApi.php');
require_once(HTTP.'/weiApi.php');


//时间
define('TIME',time());
//开始&结束时间
require_once(FUNC.'/timeInterval.php');
$timeVal=timeInterval::fileGetVal(CACHE.'/timeInterval.php');
//开始&结束日期
define('START_DATE',$timeVal[0]);
define('END_DATE',$timeVal[1]);
//开始&结束时间戳
$startTime=strtotime($timeVal[0]);
$endTime=strtotime($timeVal[1]);
define('START_TIME',$startTime);
define('END_TIME',$endTime);
//访问者IP
$IP_Get=tool::get_ip();
define('USR_IP',$IP_Get);


////class文件加载

//数据库操作相关
require_once(COMCLASS.'/wpDb.class.php');//绝对路径加载//相对路径容易出错
require_once(COMCLASS.'/baseModel.class.php');
require_once(COMCLASS.'/homeModel.class.php');
require_once('conf/database.php');//数据库账号
$home=new \model\homeModel;
$base=$home->base;
$db=$home->base->wpDb;

////数据表名全局变量
//PREFIX
define('PREFIX','test_');
//all databases name
define('USR',PREFIX.'users');
define('USR_INFO',PREFIX.'users_info');
define('USR_GET',PREFIX.'users_get');
define('USR_REC',PREFIX.'users_rec');
define('GIFT_COUNT',PREFIX.'giftcount');
