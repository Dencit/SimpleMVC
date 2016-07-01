<?php
// Created by PhpStorm. | User: 陈鸿扬[SOMA] | Date: 2016/4/26 | Time: 16:14 //


$usersTotal= $db->get_results("select uid from ".USR);

$users_get_HonBao = $db->get_results("select * from ".USR_GET." g left join ". USR ." u on g.uid=u.uid where g.gift=0  order by g.time DESC LIMIT 0,30");

$users_get_CanLon = $db->get_results("select * from ".USR_GET." g left join ". USR_INFO ." i on g.uid=i.uid where g.gift=2  order by g.time DESC LIMIT 0,30");

$users_get_XiBaKe = $db->get_results("select * from ".USR_GET." g left join ". USR_INFO ." i on g.uid=i.uid where g.gift=3  order by g.time DESC LIMIT 0,30");

$users_get_HuaFei = $db->get_results("select * from ".USR_GET." g left join ". USR_INFO ." i on g.uid=i.uid where g.gift=4  order by g.time DESC LIMIT 0,30");

$users_get_YanXan = $db->get_results("select * from ".USR_GET." g left join ". USR_INFO ." i on g.uid=i.uid where g.gift=5  order by g.time DESC LIMIT 0,30");


//

