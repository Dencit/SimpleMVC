<?php
/* Created by User: soma  Date: 16/6/18  Time: 21:34 */
//工具、函数、类、测试页

header("Content-type:text/html;charset=utf-8");
require_once('./Common/app.php');//全局变量，路径不能用路径映射变量
require_once(COMCLASS.'/testTool.class.php');//测试工具类
$testTool=new\Tool\testTool;

$testTool::typeLine('LEFT JOIN TEST');//

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



