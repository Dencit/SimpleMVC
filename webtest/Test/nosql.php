<?php
/* Created by User: soma Worker:陈鸿扬  Date: 17/1/9  Time: 10:03 */

require_once('../Library/system.php');//全局变量，路径不能用路径映射变量

require_once(LIB_DEBUGS.'/testTool.class.php');//测试工具类
$TTol=new\Debugs\testTool();

require_once(LIB_COMMONS.'/config.class.php');//通用设置
$con=new \Commons\config();

use \Debugs\testTool as tsTool;

//调试信息开关
ini_set("display_errors","On");
error_reporting(E_ALL);

//------------------------------------------------------------------------


$TTol::typeLine('REDIS CURD ：redis');////////////////////////////////////////////////

require_once(LIBRARY.'/NoSql/RedisDB.class.php');
$DB=$con->data('DB');
$redis=new \NoSql\RedisDB($DB->REDIS);


//$redis->set('key','abcaaaa');
//var_dump( $redis->get('key') );exit;//
//$redis->del('key');//

//$redis::$con->lPush('runoob','');
//var_dump( $redis::$con->lRange('runoob','0','10') );
//$redis->del('runoob');//

$keyData['A']='1';
$keyData['B']='22';
$keyData['C']='333';
$keyData['D']='4444';
$keyData['E']='55555';
$keyData['F']='666666';
$keyData['V']=new \stdClass();
$keyData['V']->W='wwwww';
$keyData['V']->A='AAAAA';
$keyData['V']->C='CCCCC';


/*
$redis->lInsert('lInsert',$keyData);
var_dump( $redis->lSelect('lInsert',0,99) );
var_dump( $redis->del('lInsert') );
//
$redis->sInsert('sInsert',$keyData);
var_dump( $redis->sSelect('sInsert') );
var_dump( $redis->del('sInsert') );
//
$redis->zInsert('zInsert',$keyData);
var_dump( $redis->zSelect('zInsert',0,99) );
var_dump( $redis->del('zInsert') );
*/

/*
//var_dump( $redis->del('hInsert') );

//var_dump( $redis->hInsert('hInsert',$keyData) );
//var_dump( $redis->hInsert('hInsert','uid/3') );

//var_dump( $redis->hUpdate('hInsert',$keyData) );
//var_dump( $redis->hUpdate('hInsert','uid/3') );

//var_dump( $redis->fieldDel('hInsert','B') );//
//var_dump( $redis->hSelect('hInsert', $redis->arrayFlip($keyData) ) );//
//var_dump( $redis->hSelect('hInsert') );

//var_dump( $redis->reRowCount('hInsert') );//
//var_dump( $redis->ttlTable('hInsert') );

//var_dump( $redis->fieldExists('hInsert',"C") );
//var_dump( $redis->allFieldGet('hInsert') );
*/


////////hash键值对集合 json数据型

//var_dump( $redis->reRowDel('table') );
//var_dump( $redis->reRowDel('table','1') );
//var_dump( $redis->reResDel('table',array('1','2')) );

/*
var_dump( $redis->reRowInsert('table','1',$keyData) );
var_dump( $redis->reRowInsert('table','2',$keyData) );
var_dump( $redis->reResInsert('table',array('3'=>$keyData,'4'=>$keyData) ) );
*/

/*
var_dump( $redis->reRowUpdate('table','1',$keyData) );
var_dump( $redis->reRowUpdate('table','2',$keyData) );
var_dump( $redis->reResUpdate('table',array('3'=>$keyData,'4'=>$keyData) ) );
*/


//var_dump( $redis->reRowSelect('table','1') );
//var_dump( $redis->reResSelect('table',array('1','2')) );
//var_dump( $redis->reResSelect('table') );


/*
var_dump( $redis->reTableRows('table') );
var_dump( $redis->reTableKeys('table') );
var_dump( $redis->reRowFind('table','1') );
*/

//$redis->debug();

$redis->begin();
$redis->watch('table');//锁redis key表

$redis->reRowUpdate('table','8',array('A'=>'777') );
$redis->reRowInsert('table','9',array('B'=>'776') );
$redis->reRowDel('table','9');

$redis->unWatch('table');//解锁redis key表

//$redis->rollBack();
$redis->commit();

$redis->reResSelect('table');



$TTol::typeLine('PDO EXTENDS ：RePdoBaseModel');////////////////////////////////////////////////

require_once(LIB_MODELS.'/PdoDB.class.php');
require_once(LIB_MODELS.'/RePdoBaseModel.class.php');
$DB=$con->data('DB');
$TABLE=$con->data('TABLE');
$rePdoBase=new \Modelers\RePdoBaseModel($DB,1);

$rePdoBase->debug();

/*
$data=$rePdoBase->rowSelect($TABLE->USR,'uid,nickname,sex','uid/1,nickname/TA');
var_dump($data);
echo '<br/>';
$data=$rePdoBase->resultSelect($TABLE->USR,'uid,openid,sex','sex/1');
var_dump($data);
*/


//$rePdoBase->option('just/1');//获取最新 绕过redis
$rePdoBase->option('sign/1');//设置用户唯一标记,如uid,没有则自动以浏览器和ip代替。//一直有效的设置 单独执行
$rePdoBase->option('ttl/60')->rowSelect('test_vcode','uid,vcode','uid/8');


//$rePdoBase->just(1);//获取最新 绕过redis
//$rePdoBase->option('just/1')->resultSelect('test_vcode','uid,vcode','uid/8');


$rePdoBase->reTableTTL('test_vcode');



/*
$rePdoBase->reTableFields('test_vcode');
*/


/*
$rePdoBase->rowUpdate('test_vcode','createtime/'.time().',sendtime/'.(time()+203645330),'uid/1');

$rePdoBase->rowInsert('test_vcode','uid/7,vcode/777777');
*/


/*
$rePdoBase->rowDel('test_vcode','uid/7,vcode/777777');
*/

/*
$rePdoBase->begin();
$rowUpdate=$rePdoBase->rowUpdate('test_vcode','createtime/'.time().',sendtime/'.(time()+203645330),'uid/1');
$rowInsert=$rePdoBase->rowInsert('test_vcode','uid/7,vcode/777777');
$rePdoBase->commit();
*/


$tableArray['test_users']='u';
$tableArray['test_users_info']='i';
$tableArray['test_users_get']='g';
$tableArray['_join']=array('INNER','INNER','INNER');
$tableArray['_on']='u.uid=i.uid=g.uid';

//$tableArray='test_users u/test_users_info i/test_users_get g,INNER/INNER/INNER,u.uid=i.uid=g.uid';

$selectArray='u.uid,u.nickname,u.sex';
$whereArray='u.sex/2';

$orderArray['u.time']='DESC';
$orderArray['LIMIT']='0,3';

$groupArray['GROUP']='u.sex';


$rePdoBase->joinSelect($tableArray,$selectArray,$whereArray,$orderArray,$groupArray);

