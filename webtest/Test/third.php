<?php
/* Created by User: soma Worker:陈鸿扬  Date: 17/1/9  Time: 10:09 */


require_once('../Library/system.php');//全局变量，路径不能用路径映射变量

require_once(LIB_DEBUGS.'/testTool.class.php');//测试工具类
$TTol=new\Debugs\testTool();

//调试信息开关
ini_set("display_errors","On");
error_reporting(E_ALL);


//------------------------------------------------------------------------


$TTol::typeLine('大于短信测试//类测试');//////////////////////////////////////////////////

require_once(LIB_3RD.'/aliDayu.php');//

$aliDayu='\Thirds\aliDayu';//命名空间引用
$code=rand(100000,999999);
$aliDayu::SmsInit("邦聚网络","{code:'".$code."',product:'邦聚网络'}","SMS_12355379");//短信模板初始化//一次通用
$aliDayu::SmsNumSend('18588891945','');//目标号码//第二个参数设置“1”才发送，默认只打印参数



$TTol::typeLine('大于短信测试//直接调用');//////////////////////////////////////////////////

//include(LIB_3RD.'/aliyunSdk/TopSdk.php');//此处 和上面的 类测试公用变量冲突//单独使用时需启用

$appkey='23409829';
$secret='94fb7df84c31116aab83241252f6b561';
$code=rand(100000,999999);

$c = new TopClient;
$c ->appkey = $appkey ;
$c ->secretKey = $secret ;
$req = new AlibabaAliqinFcSmsNumSendRequest;
$req ->setExtend( $code );
$req ->setSmsType( "normal" );
$req ->setSmsFreeSignName( "邦聚网络" );
$req ->setSmsParam( "{code:'".$code."',product:''}" );
$req ->setRecNum( "18588891945" );
$req ->setSmsTemplateCode( "SMS_12355379" );
//$resp = $c ->execute( $req );//正式发送时 启用
var_dump($req);
