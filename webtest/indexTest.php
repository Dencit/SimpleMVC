<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 16/6/18  Time: 21:34 */
//工具、函数、类、测试页

header("Content-type:text/html;charset=utf-8");
require_once('./Common/app.php');//全局变量，路径不能用路径映射变量
require_once(LIB_DEBUGS.'/testTool.class.php');//测试工具类
$testTool=new\Debugs\testTool();

$testTool::typeLine('LEFT JOIN TEST');//

$base=new \Modelers\baseModel();

$db=$base::$wpDb;
$users_get_HonBao = $db->get_results("select * from ".USR_GET." g left join ". USR ." u on g.uid=u.uid where g.gift=0  order by g.time DESC LIMIT 0,30");
print_r($users_get_HonBao);

$testTool::typeLine('BASE SELECT TEST');//


$whereArray['uid']='1';
$user= $base->rowSelect(USR,'*',$whereArray);
print_r($user);

$testTool::typeLine('startEndTime TEST HERE');//
echo "START_DATE::".START_DATE.'<br/>';
echo "END_DATE::".END_DATE.'<br/>';
echo "START_TIME::".START_TIME.'<br/>';
echo "END_TIME::".END_TIME.'<br/>';


$testTool::typeLine('大于短信测试//类测试');//

require_once(LIB_3RD.'/aliDayu.php');//
$aliDayu='\Thirds\aliDayu';//命名空间引用
$code=rand(100000,999999);
$aliDayu::SmsInit("邦聚网络","{code:'".$code."',product:'邦聚网络'}","SMS_12355379");//短信模板初始化//一次通用
$aliDayu::SmsNumSend('18588891945','');//目标号码//第二个参数设置“1”才发送，默认只打印参数


$testTool::typeLine('大于短信测试//直接调用');//

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
//$resp = $c ->execute( $req );
var_dump($req);


$testTool::typeLine('');
