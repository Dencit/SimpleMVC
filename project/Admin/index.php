<?php
// Created by PhpStorm. | User: 陈鸿扬[SOMA] | Date: 2016/4/26 | Time: 16:14 //
header("Content-type:text/html;charset=utf-8");

require_once("../Common/app.php");
require_once("../Admin/Model/indexBase.php");

$user =isset($_GET['u'])?$_GET['u']:'';
if($user!="Admin888"){
    exit("请输入用户名");
}

echo'<html lang="zh-CN">';
include('./Tample/indexHead.tpl');
echo'<body>';

include('./Tample/navbar.tpl');


require_once('./Action/usrColect.php');//用户数量

include('./Tample/giftColect.tpl');//礼物数量

include('./Tample/lotteryProbability.tpl');//中奖概率

include('./Tample/outputData.tpl');//导出数据


//
require_once("./Action/tableTamp.php");
tableTamp($users_get_HonBao,'2元现金红包','显示最新30条数据');
tableTamp($users_get_CanLon,'长隆家庭乐票','显示最新30条数据');
tableTamp($users_get_XiBaKe,'星巴克电子咖啡券','显示最新30条数据');
tableTamp($users_get_HuaFei,'10元话费','显示最新30条数据');
tableTamp($users_get_YanXan,'院线通电影票','显示最新30条数据');

//
include('./Tample/indexJs.tpl');



include('./Tample/footer.tpl');

echo"
</body>
</html>
";