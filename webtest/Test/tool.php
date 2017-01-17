<?php
/* Created by User: soma Worker:陈鸿扬  Date: 17/1/9  Time: 10:03 */

require_once('../Library/system.php');//全局变量，路径不能用路径映射变量

require_once(LIB_DEBUGS.'/testTool.class.php');//测试工具类
$TTol=new\Debugs\testTool();
$TTol->performs('start');//性能信息开始

require_once(LIB_COMMONS.'/config.class.php');//通用设置
$con=new \Commons\config();

require_once(LIB_COMMONS.'/tool.php');//工具类
$tool=new\Commons\tool();

//调试信息开关
ini_set("display_errors","On");
error_reporting(E_ALL);

//------------------------------------------------------------------------

$TTol::typeLine('CONFIG default');//////////////////////////////////////////////////
//print_r($con);
print_r($con->data('DB'));echo'<br/>';
print_r($con->data('PATH'));echo'<br/>';
print_r($con->data('TABLE'));echo'<br/>';



$TTol::typeLine('startEndTime TEST HERE');//////////////////////////////////////////////////

//时间
define('TIME',time());
//开始&结束时间
require_once(LIB_COMMONS.'/timeInterval.php');
$timeVal=timeInterval::fileGetVal(CACHE.'/timeInterval.php');
//开始&结束日期
define('START_DATE',$timeVal[0]);
define('END_DATE',$timeVal[1]);
//开始&结束时间戳
$startTime=strtotime($timeVal[0]);
$endTime=strtotime($timeVal[1]);
define('START_TIME',$startTime);
define('END_TIME',$endTime);

echo "START_DATE::".START_DATE.'<br/>';
echo "END_DATE::".END_DATE.'<br/>';
echo "START_TIME::".START_TIME.'<br/>';
echo "END_TIME::".END_TIME.'<br/>';



$TTol::typeLine('INPUTCHECK');////////////////////////////////////////////////

require_once(LIB_COMMONS.'/inputCheck.php');

use Commons\inputCheck;

$check=inputCheck::check('010-12345678','phone');
var_dump($check);

$check=inputCheck::check('dencitASD4','name');
var_dump($check);

$check=inputCheck::check('toanky@msn.com','mail');
var_dump($check);

$str ="<ul><li>item 1</li><li>item 2</li></ul>";
$matchHtml=inputCheck::matchHtml($str,'li');
var_dump($check);

$str ="<ul><li>item 1 <img src='./path'  width='30px' alt='a'/></li><li>item 2 <img src='./path' width='30px' alt='a'/></li></ul>";
$matchHtml=inputCheck::matchHtml($str,'img',1);
var_dump($matchHtml);
echo $matchHtml[0]['src'];



$TTol::typeLine('性能测试工具');////////////////////////////////////////////////


echo $TTol->performs('end')->end.'<br/>';
echo $TTol->performs('end')->time;


$TTol::typeLine('加解密函数');////////////////////////////////////////////////

require_once(LIBRARY.'/NoSql/RedisDB.class.php');

