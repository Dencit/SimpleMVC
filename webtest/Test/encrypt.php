<?php
/* Created by User: soma Worker:陈鸿扬  Date: 17/1/9  Time: 10:03 */

require_once('../Library/system.php');//全局变量，路径不能用路径映射变量

require_once(LIB_DEBUGS.'/testTool.class.php');//测试工具类
$TTol=new\Debugs\testTool();

require_once(LIB_COMMONS.'/config.class.php');//通用设置
$con=new \Commons\config();

require_once(LIB_COMMONS.'/tool.php');//工具类
$tool=new\Commons\tool();

//调试信息开关
ini_set("display_errors","On");
error_reporting(E_ALL);

require_once(LIBRARY.'/Encrypts/Encrypt.class.php');//加密类
use \Encrypts\Encrypt as encrypt;

//------------------------------------------------------------------------

$TTol::typeLine('随机字符串');////////////////////////////////////////////////

var_dump( encrypt::randStr(32) );echo'<br/>';
var_dump( encrypt::randStr(64) );echo'<br/>';
var_dump( encrypt::randStr(128) );echo'<br/>';
var_dump( encrypt::randStr(6,'BANGJU') );echo'<br/>';


$TTol::typeLine('排列指定字符串');////////////////////////////////////////////////

$rankString=encrypt::rankString('ABCD');
print_r($rankString);


$TTol::typeLine('随机字符数组');////////////////////////////////////////////////
var_dump( encrypt::randSalt('10','BANGJU') );echo'<br/>';


$TTol::typeLine('密码加密 1000次MD5和随机盐密匙 不可逆');////////////////////////////////////////////////

$passWord=encrypt::pwCrypt('12345677777');
print_r($passWord);
echo'<br/>';
$comparePw=encrypt::pwCompare($passWord);
var_dump($comparePw);


$TTol::typeLine('密码、文本加解密 可逆');////////////////////////////////////////////////

$E=encrypt::edCrypt('我是密码我是文本ABC123','E','test_key');
print_r( $E );
echo'<br/>';
$D=encrypt::edCrypt($E,'D','test_key');
print_r( $D );

