<?php
/* Created by User: soma  Date: 16/6/18  Time: 21:34 */

header("Content-type:text/html;charset=utf-8");

require_once('./Common/app.php');//全局变量，路径不能用路径映射变量

$users_get_HonBao = $db->get_results("select * from ".USR_GET." g left join ". USR ." u on g.uid=u.uid where g.gift=0  order by g.time DESC LIMIT 0,30");

print_r($users_get_HonBao);

$whereArray['uid']='1';
$user= $base->rowSelect(USR,$whereArray);
print_r($user);